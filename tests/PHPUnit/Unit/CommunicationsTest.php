<?php

declare(strict_types=1);

namespace Tests\PHPUnit\Unit;

use App\Core\Database;
use App\Core\Security;
use App\Services\ContactService;
use App\Services\MailService;
use App\Services\NewsletterService;
use PDO;
use PHPUnit\Framework\TestCase;

/**
 * E-mail queue, newsletter double opt-in / unsubscribe / campaigns, contact form (docs/54).
 * No .env in tests: SMTP is not configured, so every message must wait in the queue (never lost).
 */
final class CommunicationsTest extends TestCase
{
    protected function setUp(): void
    {
        $_ENV['MAIL_PASSWORD'] = '';
        $_ENV['APP_URL'] = 'https://internconnect.test';
    }

    private function mails(string $to): array
    {
        $stmt = Database::getConnection()->prepare('SELECT * FROM mail_queue WHERE to_email = ? ORDER BY id');
        $stmt->execute([$to]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function token(string $email): string
    {
        return (string)NewsletterService::tokenFor($email);
    }

    public function testMailWaitsInQueueWithoutSmtp(): void
    {
        $this->assertFalse(MailService::configured());
        $to = 'queue-' . bin2hex(random_bytes(4)) . '@example.com';
        $this->assertFalse(MailService::sendNow($to, 'Hello', MailService::render('message', ['title' => 'Hi', 'paragraphs' => ['Body <b>text</b>']]), 'test'));
        $m = $this->mails($to)[0];
        $this->assertSame('queued', $m['status']);
        $this->assertStringContainsString('MAIL_PASSWORD', (string)$m['last_error']);
        $this->assertStringContainsString('Body &lt;b&gt;text&lt;/b&gt;', $m['html'], 'Content is escaped in templates.');
        $this->assertStringContainsString('res.cloudinary.com', $m['html'], 'Branded header image from Cloudinary.');
        $this->assertStringContainsString('Body <b>text</b>', (string)$m['text_body'], 'Plain-text part included.');
        $this->assertSame(0, MailService::processQueue()['sent']);
    }

    public function testInvalidRecipientIsRefused(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        MailService::queue('not-an-email', 'x', 'x', 'test');
    }

    public function testDoubleOptInAndUnsubscribe(): void
    {
        $email = 'Sub-' . bin2hex(random_bytes(4)) . '@Example.com';
        $lower = strtolower($email);
        $this->assertFalse(NewsletterService::subscribe('nope')['ok']);

        $r = NewsletterService::subscribe($email, 'home');
        $this->assertSame('pending', $r['state']);
        $this->assertSame('pending', NewsletterService::find($lower)['status'], 'Not subscribed until confirmed.');
        $confirm = $this->mails($lower)[0];
        $token = $this->token($lower);
        $this->assertStringContainsString('/newsletter/confirm/' . $token, $confirm['html']);
        $this->assertStringStartsWith('https://internconnect.test/', MailService::link('/x'), 'Links use APP_URL.');

        $this->assertNull(NewsletterService::confirm(str_repeat('a', 40)), 'Unknown token.');
        $this->assertNull(NewsletterService::confirm('../../etc'), 'Malformed token.');
        $this->assertSame('subscribed', NewsletterService::confirm($token)['status']);
        $this->assertSame('already', NewsletterService::subscribe($email)['state']);
        $welcome = $this->mails($lower)[1];
        $this->assertSame('newsletter', $welcome['category']);
        $this->assertStringContainsString('/newsletter/unsubscribe/' . $token, $welcome['html']);
        $this->assertStringContainsString('/m/o/' . $welcome['track_token'] . '.gif', $welcome['html'], 'Open pixel bound to this message.');

        MailService::markOpened($welcome['track_token']);
        $this->assertNotNull($this->mails($lower)[1]['opened_at']);

        $this->assertSame('unsubscribed', NewsletterService::unsubscribe($token)['status']);
        $this->assertSame('cancelled', $this->mails($lower)[1]['status'], 'Queued newsletter mail is cancelled on unsubscribe.');
        // Subscribing again needs a new confirmation and a new token.
        $this->assertSame('pending', NewsletterService::subscribe($email)['state']);
        $this->assertNotSame($token, $this->token($lower));
    }

    public function testCampaignQueuesOncePerSubscriberWithOwnUnsubscribeLink(): void
    {
        $a = 'camp-a-' . bin2hex(random_bytes(3)) . '@example.com';
        $b = 'camp-b-' . bin2hex(random_bytes(3)) . '@example.com';
        foreach ([$a, $b] as $x) {
            NewsletterService::subscribe($x);
            NewsletterService::confirm($this->token($x));
        }
        $this->assertFalse(NewsletterService::saveCampaign(['subject' => 'x', 'title' => '', 'body' => 'short'])['ok']);
        $this->assertFalse(NewsletterService::saveCampaign(['subject' => 'S', 'title' => 'T', 'body' => str_repeat('a', 30), 'cta_url' => 'javascript:alert(1)'])['ok'], 'Only http(s) or site links.');
        $r = NewsletterService::saveCampaign([
            'subject' => 'New internships', 'title' => 'This week', 'hero' => 'celebrate', 'cta_label' => 'See all', 'cta_url' => '/internships',
            'body' => "Hello <script>alert(1)</script>\n\n## New roles\n- Design intern\n- Data intern", 'jenny_tip' => 'Tailor your CV.',
        ], null, 'Tester');
        $this->assertTrue($r['ok']);
        $html = NewsletterService::renderCampaign(NewsletterService::campaign($r['id']));
        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringContainsString('New roles</h2>', $html);
        $this->assertStringContainsString('Tip from Jenny', $html);
        $this->assertStringContainsString('https://internconnect.test/internships', $html);

        $n = NewsletterService::queueCampaign($r['id']);
        $this->assertGreaterThanOrEqual(2, $n);
        $this->assertSame(0, NewsletterService::queueCampaign($r['id']), 'A campaign is never sent twice.');
        $this->assertFalse(NewsletterService::saveCampaign(['subject' => 'S', 'title' => 'T', 'body' => str_repeat('a', 30)], $r['id'])['ok'], 'Sent campaigns are read-only.');
        $mailA = array_values(array_filter($this->mails($a), static fn ($m) => $m['category'] === 'campaign'));
        $this->assertCount(1, $mailA);
        $this->assertStringContainsString('/newsletter/unsubscribe/' . $this->token($a), $mailA[0]['html']);
        $this->assertStringNotContainsString($this->token($b), $mailA[0]['html'], 'No one gets another subscriber\'s link.');

        $csv = NewsletterService::exportCsv();
        $this->assertStringContainsString($a, $csv);
    }

    public function testContactFormStoresAndNotifies(): void
    {
        $_ENV['MAIL_ADMIN_ADDRESS'] = 'team@example.com';
        $bad = ContactService::submit(['name' => '', 'email' => 'x', 'message' => 'hi']);
        $this->assertFalse($bad['ok']);
        $this->assertSame(['name', 'email', 'message'], array_keys($bad['errors']));

        $email = 'contact-' . bin2hex(random_bytes(3)) . '@example.com';
        $r = ContactService::submit(['name' => 'Ada <b>Obi</b>', 'email' => $email, 'role' => 'hacker', 'subject' => 'Billing', 'message' => 'I was charged twice for Pro.']);
        $this->assertTrue($r['ok']);
        $this->assertMatchesRegularExpression('/^IC-[A-F0-9]{6}$/', $r['reference']);
        $row = ContactService::list('new', 1, 5)['rows'][0];
        $this->assertSame($r['reference'], $row['reference']);
        $this->assertSame('Ada Obi', $row['name'], 'Tags stripped.');
        $this->assertSame('candidate', $row['role'], 'Unknown roles fall back.');
        $this->assertSame('contact_ack', $this->mails($email)[0]['category']);
        $this->assertNotEmpty(array_filter($this->mails('team@example.com'), static fn ($m) => str_contains($m['subject'], $r['reference'])));

        $reply = ContactService::reply((int)$row['id'], 'We refunded the duplicate charge.', 'Jayden');
        $this->assertTrue($reply['ok']);
        $this->assertFalse($reply['sent'], 'Queued until SMTP is configured.');
        $this->assertSame('replied', ContactService::find((int)$row['id'])['status']);
    }

    public function testOneClickUnsubscribeIsTheOnlyCsrfFreeNewsletterPost(): void
    {
        $ref = new \ReflectionClassConstant(Security::class, 'CSRF_EXEMPT_PREFIXES');
        $this->assertSame(['/newsletter/one-click/'], $ref->getValue());
    }
}
