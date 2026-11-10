<?php
declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use App\Controllers\CandidateController;
use App\Controllers\EmployerController;
use App\Services\AiService;

class AiFeaturesTest extends TestCase
{
    private CandidateController $candidateController;
    private EmployerController $employerController;

    public function __construct()
    {
        $this->candidateController = new CandidateController();
        $this->employerController = new EmployerController();
    }

    public function testAiControllerMethodsExist(): void
    {
        // Candidate AI methods
        $this->assertTrue(method_exists($this->candidateController, 'aiAssistant'), 'aiAssistant method must exist on CandidateController');
        $this->assertTrue(method_exists($this->candidateController, 'aiCvAnalyze'), 'aiCvAnalyze method must exist on CandidateController');
        $this->assertTrue(method_exists($this->candidateController, 'processAiCvAnalyze'), 'processAiCvAnalyze method must exist on CandidateController');
        $this->assertTrue(method_exists($this->candidateController, 'aiCvResults'), 'aiCvResults method must exist on CandidateController');
        $this->assertTrue(method_exists($this->candidateController, 'aiMatchedInternships'), 'aiMatchedInternships method must exist on CandidateController');
        $this->assertTrue(method_exists($this->candidateController, 'aiInterviewPrep'), 'aiInterviewPrep method must exist on CandidateController');
        $this->assertTrue(method_exists($this->candidateController, 'aiCoverLetter'), 'aiCoverLetter method must exist on CandidateController');
        $this->assertTrue(method_exists($this->candidateController, 'generateAiCoverLetter'), 'generateAiCoverLetter method must exist on CandidateController');
        $this->assertTrue(method_exists($this->candidateController, 'aiCareerCoach'), 'aiCareerCoach method must exist on CandidateController');
        $this->assertTrue(method_exists($this->candidateController, 'generateAiRoadmap'), 'generateAiRoadmap method must exist on CandidateController');

        // Employer AI methods
        $this->assertTrue(method_exists($this->employerController, 'aiAssistant'), 'aiAssistant method must exist on EmployerController');
    }

    public function testAiUrlHelperRoutes(): void
    {
        $hubUrl = url('/candidate/ai-assistant');
        $this->assertStringContains('/candidate/ai-assistant', $hubUrl, 'url(/candidate/ai-assistant) must generate valid path');

        $analyzeUrl = url('/candidate/ai-assistant/cv-analyze');
        $this->assertStringContains('/candidate/ai-assistant/cv-analyze', $analyzeUrl, 'url(/candidate/ai-assistant/cv-analyze) must generate valid path');

        $resultsUrl = url('/candidate/ai-assistant/cv-results');
        $this->assertStringContains('/candidate/ai-assistant/cv-results', $resultsUrl, 'url(/candidate/ai-assistant/cv-results) must generate valid path');

        $matchedUrl = url('/candidate/ai-assistant/matched');
        $this->assertStringContains('/candidate/ai-assistant/matched', $matchedUrl, 'url(/candidate/ai-assistant/matched) must generate valid path');

        $prepUrl = url('/candidate/ai-assistant/interview-prep');
        $this->assertStringContains('/candidate/ai-assistant/interview-prep', $prepUrl, 'url(/candidate/ai-assistant/interview-prep) must generate valid path');

        $coverUrl = url('/candidate/ai-assistant/cover-letter');
        $this->assertStringContains('/candidate/ai-assistant/cover-letter', $coverUrl, 'url(/candidate/ai-assistant/cover-letter) must generate valid path');

        $coachUrl = url('/candidate/ai-assistant/career-coach');
        $this->assertStringContains('/candidate/ai-assistant/career-coach', $coachUrl, 'url(/candidate/ai-assistant/career-coach) must generate valid path');

        $empAiUrl = url('/employer/ai-assistant');
        $this->assertStringContains('/employer/ai-assistant', $empAiUrl, 'url(/employer/ai-assistant) must generate valid path');
    }

    public function testZeroColoredSideBordersInAiViews(): void
    {
        $aiFiles = [
            dirname(__DIR__, 2) . '/frontend/candidate/ai-assistant/index.php',
            dirname(__DIR__, 2) . '/frontend/candidate/ai-assistant/cv-analyze.php',
            dirname(__DIR__, 2) . '/frontend/candidate/ai-assistant/cv-results.php',
            dirname(__DIR__, 2) . '/frontend/candidate/ai-assistant/matched.php',
            dirname(__DIR__, 2) . '/frontend/candidate/ai-assistant/cover-letter.php',
            dirname(__DIR__, 2) . '/frontend/candidate/ai-assistant/career-coach.php',
            dirname(__DIR__, 2) . '/frontend/employer/ai-assistant/index.php',
        ];

        foreach ($aiFiles as $file) {
            $this->assertTrue(file_exists($file), "AI view file must exist: {$file}");
            $content = (string)file_get_contents($file);

            $this->assertFalse(
                (bool)preg_match('/border-left:\s*[2-9]px\s+solid\s+(?!#[eE]2[eE]8[fF]0)/', $content),
                "No colored accent border-left strips permitted in {$file}"
            );
            $this->assertFalse(
                (bool)preg_match('/border-top:\s*[2-9]px\s+solid\s+#(?!E2E8F0|EDE9FE|F1F5F9)/i', $content),
                "No colored accent border-top strips permitted in {$file}"
            );
        }
    }

    public function testZeroNativeAlertsInAiViews(): void
    {
        $aiFiles = [
            dirname(__DIR__, 2) . '/frontend/candidate/ai-assistant/index.php',
            dirname(__DIR__, 2) . '/frontend/candidate/ai-assistant/cv-analyze.php',
            dirname(__DIR__, 2) . '/frontend/candidate/ai-assistant/cv-results.php',
            dirname(__DIR__, 2) . '/frontend/candidate/ai-assistant/matched.php',
            dirname(__DIR__, 2) . '/frontend/candidate/ai-assistant/cover-letter.php',
            dirname(__DIR__, 2) . '/frontend/candidate/ai-assistant/career-coach.php',
            dirname(__DIR__, 2) . '/frontend/employer/ai-assistant/index.php',
        ];

        foreach ($aiFiles as $file) {
            $content = (string)file_get_contents($file);
            $this->assertFalse(
                str_contains($content, 'alert('),
                "Native alert() prohibited in {$file}"
            );
            $this->assertFalse(
                str_contains($content, 'confirm('),
                "Native confirm() prohibited in {$file}"
            );
            $this->assertFalse(
                str_contains($content, 'prompt('),
                "Native prompt() prohibited in {$file}"
            );
        }
    }

    public function testSingleLineButtonIntegrityInAiViews(): void
    {
        $cvAnalyze = (string)file_get_contents(dirname(__DIR__, 2) . '/frontend/candidate/ai-assistant/cv-analyze.php');
        $this->assertStringContains(
            'white-space: nowrap !important;',
            $cvAnalyze,
            'CTA and action buttons in cv-analyze.php must enforce single-line integrity'
        );

        $cvResults = (string)file_get_contents(dirname(__DIR__, 2) . '/frontend/candidate/ai-assistant/cv-results.php');
        $this->assertStringContains(
            'flex-shrink: 0;',
            $cvResults,
            'Icon wrappers and badges in cv-results.php must enforce flex-shrink: 0'
        );
    }

    public function testAiServiceCvAnalysisCalculation(): void
    {
        $cvText = "I am a passionate UI/UX Designer experienced in Figma, Wireframing, User Research, Prototyping, and Design Systems. Built responsive mobile apps with agile sprints.";
        $analysis = AiService::analyzeCv($cvText, 'Product Designer', 'Fintech Internship', ['user_id' => 1]);

        $this->assertTrue(is_array($analysis), 'AiService::analyzeCv must return an associative array');
        $this->assertTrue(isset($analysis['score']), 'Analysis must contain score key');
        $this->assertTrue(isset($analysis['metrics']), 'Analysis must contain metrics');
        $this->assertTrue(isset($analysis['detected_skills']), 'Analysis must contain detected_skills');
        $this->assertTrue(isset($analysis['missing_skills']), 'Analysis must contain missing_skills');
        $this->assertTrue(isset($analysis['suggestions']), 'Analysis must contain suggestions');
        $this->assertTrue(isset($analysis['whats_working']), 'Analysis must contain whats_working');

        $this->assertTrue($analysis['score'] >= 70, 'Score should be >= 70 for CV with relevant keywords');
        $this->assertTrue($analysis['score'] <= 100, 'Score should be <= 100');
        $this->assertTrue(in_array('Figma', $analysis['detected_skills']), 'Detected skills must include Figma');
    }

    public function testAiServiceExtractCvTextGracefulHandling(): void
    {
        // Non-existent file should return empty string without warning
        $empty = AiService::extractCvText('non_existent_file.pdf', 'pdf');
        $this->assertEquals('', $empty, 'Missing file must return empty string');

        // Existing docx test file if present
        $docxPath = dirname(__DIR__, 2) . '/testing.docx';
        if (file_exists($docxPath)) {
            $extracted = AiService::extractCvText($docxPath, 'docx');
            $this->assertNotEmpty($extracted, 'DOCX extraction must extract content from testing.docx');
        }
    }

    public function testAiMockInterviewQuestions(): void
    {
        $questions = AiService::getInterviewQuestions('Product Designer');
        $this->assertTrue(is_array($questions), 'getInterviewQuestions must return an array');
        $this->assertTrue(count($questions) >= 3, 'Questions bank must contain at least 3 questions');

        $first = $questions[0];
        $this->assertTrue(isset($first['id']), 'Question must have id');
        $this->assertTrue(isset($first['question']), 'Question must have question text');
        $this->assertTrue(isset($first['category']), 'Question must have category');
        $this->assertTrue(isset($first['focus']), 'Question must have focus');
        $this->assertTrue(isset($first['sample_tip']), 'Question must have sample_tip');
        $this->assertTrue(isset($first['model_answer']), 'Question must have model_answer');
    }

    public function testAiMockInterviewEvaluation(): void
    {
        $role = 'Backend Developer';
        $question = 'Walk me through how you design a secure, high-throughput RESTful API.';
        $answer = 'In my previous project, we faced high latency. My task was to architect an authentication service. I implemented stateless JWT with Redis caching and rate limiting in PHP and MySQL. As a result, API response times improved by 45% and handled 10,000 peak concurrent requests.';

        $evaluation = AiService::evaluateInterviewAnswer($role, $question, $answer, ['user_id' => 1]);

        $this->assertTrue(is_array($evaluation), 'Evaluation must return an array');
        $this->assertTrue(isset($evaluation['score']), 'Evaluation must have score');
        $this->assertTrue(isset($evaluation['rating']), 'Evaluation must have rating');
        $this->assertTrue(isset($evaluation['star_adherence']), 'Evaluation must have star_adherence');
        $this->assertTrue(isset($evaluation['strengths']), 'Evaluation must have strengths');
        $this->assertTrue(isset($evaluation['improvements']), 'Evaluation must have improvements');
        $this->assertTrue(isset($evaluation['model_answer']), 'Evaluation must have model_answer');

        $this->assertTrue($evaluation['score'] >= 75, 'Score should be >= 75 for structured answer');
        $this->assertTrue($evaluation['star_adherence']['action'] === true, 'STAR Action should be detected');
        $this->assertTrue($evaluation['star_adherence']['result'] === true, 'STAR Result should be detected');
    }

    public function testAiMockInterviewControllerEndpoints(): void
    {
        $this->assertTrue(
            method_exists($this->candidateController, 'evaluateMockAnswer'),
            'CandidateController must have evaluateMockAnswer method'
        );

        $prepView = dirname(__DIR__, 2) . '/frontend/candidate/ai-assistant/interview-prep.php';
        $this->assertTrue(file_exists($prepView), 'interview-prep.php view file must exist');

        $content = (string)file_get_contents($prepView);
        $this->assertFalse(
            (bool)preg_match('/border-left:\s*[2-9]px\s+solid\s+(?!#[eE]2[eE]8[fF]0)/', $content),
            'No colored accent border strips in interview-prep.php'
        );
        $this->assertFalse(
            str_contains($content, 'alert('),
            'No native alert() in interview-prep.php'
        );
        $this->assertStringContains(
            'white-space: nowrap !important;',
            $content,
            'interview-prep.php must enforce single-line button integrity'
        );
    }

    public function testAiCoverLetterGeneration(): void
    {
        $candidate = [
            'user_id' => 1,
            'name'    => 'Sarah Peters',
            'school'  => 'University of Lagos',
            'degree'  => 'B.Sc Systems Engineering',
            'skills'  => ['Figma', 'UI Design', 'Wireframing']
        ];
        $job = [
            'company'  => 'Paystack',
            'role'     => 'UI/UX Design Intern',
            'location' => 'Lagos, Nigeria'
        ];

        $letter = AiService::generateCoverLetter($candidate, $job, 'professional');

        $this->assertTrue(is_array($letter), 'generateCoverLetter must return an array');
        $this->assertTrue(isset($letter['full_letter']), 'Must contain full_letter string');
        $this->assertTrue(isset($letter['paragraphs']) && count($letter['paragraphs']) === 4, 'Must contain 4 structured paragraphs');
        $this->assertTrue($letter['word_count'] > 100, 'Word count must be substantial');
        $this->assertStringContains('Paystack', $letter['full_letter'], 'Letter must reference target company');
    }

    public function testAiCareerCoachRoadmap(): void
    {
        $candidate = [
            'user_id' => 1,
            'name'    => 'Sarah Peters',
            'skills'  => ['Figma', 'User Research']
        ];

        $roadmap = AiService::generateCareerRoadmap($candidate, 'Product Designer');

        $this->assertTrue(is_array($roadmap), 'generateCareerRoadmap must return an array');
        $this->assertTrue(isset($roadmap['mastered_skills']), 'Must include mastered_skills');
        $this->assertTrue(isset($roadmap['in_progress_skills']), 'Must include in_progress_skills');
        $this->assertTrue(isset($roadmap['missing_skills']), 'Must include missing_skills');
        $this->assertTrue(isset($roadmap['phases']) && count($roadmap['phases']) === 4, 'Must include 4 progressive milestone phases');
        $this->assertTrue(isset($roadmap['courses']) && count($roadmap['courses']) >= 2, 'Must recommend courses');
    }
}

