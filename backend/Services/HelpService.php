<?php

declare(strict_types=1);

namespace App\Services;

/**
 * HelpService
 * 
 * Provides Help Center knowledge base content, category metadata,
 * article retrieval, search indexing, and FAQ content for candidate & employer portals.
 */
class HelpService
{
    /**
     * Get all Help Center Categories
     */
    public static function getCategories(): array
    {
        return [
            [
                'slug'        => 'getting-started',
                'title'       => 'Getting Started',
                'description' => 'Learn the basics of using InternConnect for your journey.',
                'count_text'  => '24 articles',
                'icon_svg'    => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>',
                'badge'       => null,
            ],
            [
                'slug'        => 'applying-for-internships',
                'title'       => 'Applying for Internships',
                'description' => 'Master the application process and tracking your status.',
                'count_text'  => '12 articles',
                'icon_svg'    => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>',
                'badge'       => null,
            ],
            [
                'slug'        => 'profile-settings',
                'title'       => 'Profile & Settings',
                'description' => 'Optimizing your student profile for recruiter visibility.',
                'count_text'  => '8 articles',
                'icon_svg'    => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                'badge'       => null,
            ],
            [
                'slug'        => 'saved-internships',
                'title'       => 'Saved Internships',
                'description' => 'How to manage your lists and set interest alerts.',
                'count_text'  => '5 articles',
                'icon_svg'    => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>',
                'badge'       => null,
            ],
            [
                'slug'        => 'notifications-alerts',
                'title'       => 'Notifications & Alerts',
                'description' => 'Control how and when you receive internship updates.',
                'count_text'  => '10 articles',
                'icon_svg'    => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>',
                'badge'       => null,
            ],
            [
                'slug'        => 'account-privacy',
                'title'       => 'Account & Privacy',
                'description' => 'Privacy controls and data management for students.',
                'count_text'  => '15 articles',
                'icon_svg'    => '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>',
                'badge'       => 'NEW',
            ],
        ];
    }

    /**
     * Get Popular FAQs for the accordion
     */
    public static function getPopularFaqs(): array
    {
        return [
            [
                'slug'        => 'how-does-quick-apply-work',
                'question'    => 'How does Quick Apply work?',
                'answer'      => 'Quick Apply allows you to use your verified InternConnect profile as a universal application. Once your profile completion reaches 80%, you can submit your details to participating employers with a single click, saving you hours on repetitive forms.',
                'article_url' => url('/help/article/how-does-quick-apply-work'),
            ],
            [
                'slug'        => 'can-i-edit-an-application-after-submission',
                'question'    => 'Can I edit an application after submission?',
                'answer'      => 'Once an application is submitted, the core details, your resume and answers at the time of submission are locked in and can\'t be edited. This keeps things fair, since employers are reviewing exactly what was submitted.',
                'article_url' => url('/help/article/can-i-edit-an-application-after-submission'),
            ],
            [
                'slug'        => 'how-do-i-contact-a-recruiter-directly',
                'question'    => 'How do I contact a recruiter directly?',
                'answer'      => 'You can message recruiters directly from the My Applications section once your application has advanced to In Review or Interview status, or through your interview invitation card.',
                'article_url' => url('/help/article/how-do-i-contact-a-recruiter-directly'),
            ],
            [
                'slug'        => 'what-are-verified-badges-on-my-profile',
                'question'    => 'What are verified badges on my profile?',
                'answer'      => 'Verified badges indicate institution-confirmed student status, verified university email domains, and verified coursework. Employers prioritize applications with verified badges by up to 60%.',
                'article_url' => url('/help/article/what-are-verified-badges-on-my-profile'),
            ],
            [
                'slug'        => 'is-my-academic-info-visible-to-everyone',
                'question'    => 'Is my academic info visible to everyone?',
                'answer'      => 'No. Your academic grades and private documents are only visible to verified recruiters of organizations where you have submitted an active application.',
                'article_url' => url('/help/article/is-my-academic-info-visible-to-everyone'),
            ],
            [
                'slug'        => 'how-do-i-delete-my-account',
                'question'    => 'How do I delete my account?',
                'answer'      => 'You can request account deactivation under Profile Settings > Security. Choosing to delete your account will permanently wipe your profile and resume data in compliance with data privacy standards.',
                'article_url' => url('/help/article/how-do-i-delete-my-account'),
            ],
        ];
    }

    /**
     * Get All Articles Repository
     */
    public static function getAllArticles(): array
    {
        return [
            'can-i-edit-an-application-after-submission' => [
                'slug'             => 'can-i-edit-an-application-after-submission',
                'title'            => 'Can i edit an application after submission?',
                'category'         => 'Applying for Internships',
                'category_slug'    => 'applying-for-internships',
                'read_time'        => '2 min read',
                'snippet'          => 'Once an application is submitted, the core details, your resume and answers at the time of submission are locked in and can\'t be edited.',
                'paragraphs'       => [
                    'Once an application is submitted, the core details, your resume and answers at the time of submission are locked in and can\'t be edited. This keeps things fair, since employers are reviewing exactly what was submitted.'
                ],
                'sections'         => [
                    [
                        'heading' => 'What you can still do',
                        'bullets' => [
                            'Send a follow-up message to the recruiter if you need to correct something important',
                            'Update your general profile. This won\'t change what was already sent, but will apply to future applications'
                        ]
                    ],
                    [
                        'heading' => 'If you made a mistake',
                        'content' => 'Your best option is to message the recruiter directly and explain the correction. Most employers are understanding about small errors like a typo in a cover letter.'
                    ]
                ],
                'related_articles' => [
                    [
                        'title'         => 'How do I Apply for internships?',
                        'category'      => 'Applying for Internships',
                        'category_slug' => 'applying-for-internships',
                        'slug'          => 'how-does-quick-apply-work'
                    ],
                    [
                        'title'         => 'Understanding your application status',
                        'category'      => 'Applying for Internships',
                        'category_slug' => 'applying-for-internships',
                        'slug'          => 'understanding-your-application-status'
                    ]
                ]
            ],

            'interview-reminder-notifications' => [
                'slug'             => 'interview-reminder-notifications',
                'title'            => 'Interview reminder notifications',
                'category'         => 'Notifications & Alerts',
                'category_slug'    => 'notifications-alerts',
                'read_time'        => '3 min read',
                'snippet'          => 'Everything you need to know interview reminder notifications on InternConnect.',
                'paragraphs'       => [
                    'InternConnect provides multi-channel interview reminder notifications to ensure you never miss an interview opportunity with prospective employers.',
                    'Reminders are dispatched 24 hours and 1 hour before your scheduled time via email, SMS (if enabled in your profile settings), and in-app system alerts.'
                ],
                'sections'         => [
                    [
                        'heading' => 'Notification Channels',
                        'bullets' => [
                            'In-App Bell Alerts: Real-time popups with direct links to the interview room guide',
                            'Calendar Sync: Add scheduled interview slots directly to Google Calendar or Outlook with one click',
                            'Email Summaries: Detailed agenda including interviewer names, roles, and technical prerequisites'
                        ]
                    ],
                    [
                        'heading' => 'Managing Your Alerts',
                        'content' => 'You can customize your notification preferences under Profile Settings > Notifications to adjust delivery frequencies or silence non-critical updates.'
                    ]
                ],
                'related_articles' => [
                    [
                        'title'         => 'How do I contact a recruiter directly?',
                        'category'      => 'Applying for Internships',
                        'category_slug' => 'applying-for-internships',
                        'slug'          => 'how-do-i-contact-a-recruiter-directly'
                    ],
                    [
                        'title'         => 'Understanding your application status',
                        'category'      => 'Applying for Internships',
                        'category_slug' => 'applying-for-internships',
                        'slug'          => 'understanding-your-application-status'
                    ]
                ]
            ],

            'how-does-quick-apply-work' => [
                'slug'             => 'how-does-quick-apply-work',
                'title'            => 'How does Quick Apply work?',
                'category'         => 'Applying for Internships',
                'category_slug'    => 'applying-for-internships',
                'read_time'        => '3 min read',
                'snippet'          => 'Quick Apply allows you to use your verified InternConnect profile as a universal application.',
                'paragraphs'       => [
                    'Quick Apply allows you to use your verified InternConnect profile as a universal application. Once your profile completion reaches 80%, you can submit your details to participating employers with a single click, saving you hours on repetitive forms.',
                    'Employers receiving Quick Apply submissions receive your complete verified portfolio, academic credentials, and customized profile pitch instantly.'
                ],
                'sections'         => [
                    [
                        'heading' => 'Eligibility Requirements',
                        'bullets' => [
                            'Profile completeness of at least 80% (including academic background & target roles)',
                            'At least one uploaded CV or Resume in PDF format',
                            'Verified institutional or personal email address'
                        ]
                    ],
                    [
                        'heading' => 'Tips for Best Results',
                        'content' => 'Keep your skills tags and project links up-to-date in your Profile Settings so employers always see your latest achievements when reviewing Quick Apply submissions.'
                    ]
                ],
                'related_articles' => [
                    [
                        'title'         => 'Can i edit an application after submission?',
                        'category'      => 'Applying for Internships',
                        'category_slug' => 'applying-for-internships',
                        'slug'          => 'can-i-edit-an-application-after-submission'
                    ],
                    [
                        'title'         => 'What are verified badges on my profile?',
                        'category'      => 'Profile & Settings',
                        'category_slug' => 'profile-settings',
                        'slug'          => 'what-are-verified-badges-on-my-profile'
                    ]
                ]
            ],

            'understanding-your-application-status' => [
                'slug'             => 'understanding-your-application-status',
                'title'            => 'Understanding your application status',
                'category'         => 'Applying for Internships',
                'category_slug'    => 'applying-for-internships',
                'read_time'        => '2 min read',
                'snippet'          => 'Learn what each status means as your internship application progresses through hiring stages.',
                'paragraphs'       => [
                    'Every internship application on InternConnect moves through standardized stages to give you transparent, real-time insight into the employer review process.'
                ],
                'sections'         => [
                    [
                        'heading' => 'Status Definitions',
                        'bullets' => [
                            'Submitted: Your application has been delivered to the employer hiring inbox',
                            'In Review: The recruitment team is actively evaluating your resume and profile',
                            'Interview Scheduled: You have received an interview invitation with date and meeting link',
                            'Offer Extended: Congratulations! The employer has issued a formal internship placement offer',
                            'Archived / Declined: The position has been filled or the employer chose another candidate'
                        ]
                    ]
                ],
                'related_articles' => [
                    [
                        'title'         => 'Can i edit an application after submission?',
                        'category'      => 'Applying for Internships',
                        'category_slug' => 'applying-for-internships',
                        'slug'          => 'can-i-edit-an-application-after-submission'
                    ],
                    [
                        'title'         => 'Interview reminder notifications',
                        'category'      => 'Notifications & Alerts',
                        'category_slug' => 'notifications-alerts',
                        'slug'          => 'interview-reminder-notifications'
                    ]
                ]
            ],

            'how-do-i-contact-a-recruiter-directly' => [
                'slug'             => 'how-do-i-contact-a-recruiter-directly',
                'title'            => 'How do I contact a recruiter directly?',
                'category'         => 'Applying for Internships',
                'category_slug'    => 'applying-for-internships',
                'read_time'        => '2 min read',
                'snippet'          => 'Learn how to initiate direct messaging with hiring managers and follow up professionally on your applications.',
                'paragraphs'       => [
                    'Direct communication with hiring managers is enabled once an employer initiates contact or advances your application to the In Review stage.'
                ],
                'sections'         => [
                    [
                        'heading' => 'Best practices for messaging recruiters',
                        'bullets' => [
                            'Keep your message concise, polite, and directly related to the role',
                            'Reference your specific application ID or scheduled interview date',
                            'Allow 1 to 2 business days for recruiters to respond'
                        ]
                    ]
                ],
                'related_articles' => [
                    [
                        'title'         => 'Interview reminder notifications',
                        'category'      => 'Notifications & Alerts',
                        'category_slug' => 'notifications-alerts',
                        'slug'          => 'interview-reminder-notifications'
                    ]
                ]
            ],

            'what-are-verified-badges-on-my-profile' => [
                'slug'             => 'what-are-verified-badges-on-my-profile',
                'title'            => 'What are verified badges on my profile?',
                'category'         => 'Profile & Settings',
                'category_slug'    => 'profile-settings',
                'read_time'        => '2 min read',
                'snippet'          => 'Verified badges indicate institution-confirmed student status and verified academic achievements.',
                'paragraphs'       => [
                    'Verified badges help employers distinguish verified students and authentic academic credentials from unverified submissions.'
                ],
                'sections'         => [
                    [
                        'heading' => 'How to earn verified badges',
                        'bullets' => [
                            'Verify your institutional (.edu / university) email address during onboarding',
                            'Upload an official student ID or enrollment transcript in Profile Settings',
                            'Complete identity verification via institutional Single Sign-On (SSO) when supported'
                        ]
                    ]
                ],
                'related_articles' => [
                    [
                        'title'         => 'How does Quick Apply work?',
                        'category'      => 'Applying for Internships',
                        'category_slug' => 'applying-for-internships',
                        'slug'          => 'how-does-quick-apply-work'
                    ]
                ]
            ],

            'is-my-academic-info-visible-to-everyone' => [
                'slug'             => 'is-my-academic-info-visible-to-everyone',
                'title'            => 'Is my academic info visible to everyone?',
                'category'         => 'Account & Privacy',
                'category_slug'    => 'account-privacy',
                'read_time'        => '2 min read',
                'snippet'          => 'Your GPA and transcript details are only visible to employers whose internship postings you specifically apply to.',
                'paragraphs'       => [
                    'InternConnect prioritizes candidate privacy. Your confidential academic data, phone numbers, and detailed transcripts are never published publicly on the web or indexed in public directories.'
                ],
                'sections'         => [
                    [
                        'heading' => 'Who can see your details?',
                        'bullets' => [
                            'Employers you submit an application to can see your complete profile and resume',
                            'Other students cannot view your grades or contact information',
                            'Public visitors only see basic non-sensitive profile summaries if you explicitly enable public portfolio view'
                        ]
                    ]
                ],
                'related_articles' => [
                    [
                        'title'         => 'How do I delete my account?',
                        'category'      => 'Account & Privacy',
                        'category_slug' => 'account-privacy',
                        'slug'          => 'how-do-i-delete-my-account'
                    ]
                ]
            ],

            'how-do-i-delete-my-account' => [
                'slug'             => 'how-do-i-delete-my-account',
                'title'            => 'How do I delete my account?',
                'category'         => 'Account & Privacy',
                'category_slug'    => 'account-privacy',
                'read_time'        => '2 min read',
                'snippet'          => 'Learn about account deactivation and complete data erasure procedures under privacy compliance.',
                'paragraphs'       => [
                    'We believe you should have total control over your personal data. You can delete your account and all associated application history at any time.'
                ],
                'sections'         => [
                    [
                        'heading' => 'Account Deletion Process',
                        'bullets' => [
                            'Go to Profile Settings > Security & Account',
                            'Scroll to the Danger Zone and select "Delete Account"',
                            'Confirm your password to authorize permanent data erasure',
                            'All uploaded documents and application records are irreversibly purged within 30 days'
                        ]
                    ]
                ],
                'related_articles' => [
                    [
                        'title'         => 'Is my academic info visible to everyone?',
                        'category'      => 'Account & Privacy',
                        'category_slug' => 'account-privacy',
                        'slug'          => 'is-my-academic-info-visible-to-everyone'
                    ]
                ]
            ],
        ];
    }

    /**
     * Retrieve single article by slug
     */
    public static function getArticleBySlug(string $slug): ?array
    {
        $articles = self::getAllArticles();
        return $articles[$slug] ?? null;
    }

    /**
     * Search articles by keyword query
     */
    public static function search(string $query): array
    {
        $cleanQuery = trim($query);
        if ($cleanQuery === '') {
            return [
                'query'   => '',
                'count'   => 0,
                'results' => [],
            ];
        }

        $all = self::getAllArticles();
        $results = [];
        $words = array_filter(explode(' ', strtolower($cleanQuery)));

        foreach ($all as $article) {
            $haystack = strtolower($article['title'] . ' ' . $article['category'] . ' ' . ($article['snippet'] ?? '') . ' ' . implode(' ', $article['paragraphs'] ?? []));
            
            $matched = false;
            foreach ($words as $word) {
                if (str_contains($haystack, $word)) {
                    $matched = true;
                    break;
                }
            }

            if ($matched) {
                // Highlight matches in title and snippet
                $highlightedTitle = self::highlightTerm($article['title'], $cleanQuery);
                $snippetSource = $article['snippet'] ?? ($article['paragraphs'][0] ?? '');
                $highlightedSnippet = self::highlightTerm($snippetSource, $cleanQuery);

                $results[] = [
                    'slug'                => $article['slug'],
                    'title'               => $article['title'],
                    'highlighted_title'   => $highlightedTitle,
                    'snippet'             => $snippetSource,
                    'highlighted_snippet' => $highlightedSnippet,
                    'category'            => $article['category'],
                    'category_slug'       => $article['category_slug'] ?? 'general',
                    'read_time'           => $article['read_time'] ?? '2 min read',
                ];
            }
        }

        return [
            'query'   => $cleanQuery,
            'count'   => count($results),
            'results' => $results,
        ];
    }

    /**
     * Wrap matching search terms with highlight tags matching Screenshot 2
     */
    public static function highlightTerm(string $text, string $query): string
    {
        $words = array_filter(explode(' ', trim($query)));
        if (empty($words)) {
            return htmlspecialchars($text);
        }

        $escapedText = htmlspecialchars($text);
        foreach ($words as $w) {
            $w = trim($w);
            if (strlen($w) < 2) continue;
            $pattern = '/(' . preg_quote(htmlspecialchars($w), '/') . ')/i';
            $escapedText = preg_replace($pattern, '<mark style="background: #FEF08A; padding: 0 2px; border-radius: 2px;">$1</mark>', $escapedText);
        }

        return $escapedText;
    }
}
