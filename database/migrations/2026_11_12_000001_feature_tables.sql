-- InternConnect migration 2026_11_12_000001: feature tables used by the running app (MySQL).
-- Additive only: CREATE TABLE IF NOT EXISTS, nothing is dropped or altered.
-- Taken from database/schema_mysql.sql without foreign keys to users / candidate_profiles / employer_profiles:
-- those account tables move to MySQL in the accounts phase, and the demo accounts are not in them yet.
-- Apply with: php bin/migrate.php   (or import this file in phpMyAdmin).

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS `interview_evaluations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `interview_id` BIGINT UNSIGNED NOT NULL UNIQUE,
    `technical_score` TINYINT UNSIGNED NOT NULL DEFAULT 0, -- 0 = not rated, 1-5 stars
    `problem_solving_score` TINYINT UNSIGNED NOT NULL DEFAULT 4,
    `communication_score` TINYINT UNSIGNED NOT NULL DEFAULT 5,
    `culture_score` TINYINT UNSIGNED NOT NULL DEFAULT 5,
    `overall_score` DECIMAL(3, 2) GENERATED ALWAYS AS (
        (technical_score + problem_solving_score + communication_score + culture_score) / 4.0
    ) STORED,
    `overall_recommendation` ENUM('strong_hire', 'hire', 'hold', 'reject') NOT NULL DEFAULT 'hire',
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_eval_recommendation` (`overall_recommendation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `interview_rooms` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `interview_id` BIGINT UNSIGNED NOT NULL UNIQUE,
    `room_name` VARCHAR(100) NOT NULL UNIQUE,
    `room_url` VARCHAR(500) NOT NULL,
    `status` ENUM('open', 'ended') NOT NULL DEFAULT 'open',
    `ended_by` VARCHAR(20) NULL,
    `created_at` DATETIME NOT NULL,
    `ended_at` DATETIME NULL,
    `started_at` DATETIME NULL COMMENT 'First join of the current session (timer start)',
    PRIMARY KEY (`id`),
    INDEX `idx_rooms_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `interview_room_events` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `interview_id` BIGINT UNSIGNED NOT NULL,
    `role` VARCHAR(20) NOT NULL,
    `event` VARCHAR(40) NOT NULL,
    `detail` VARCHAR(255) NULL,
    `created_at` DATETIME NOT NULL,
    `ip_masked` VARCHAR(64) NULL COMMENT 'Last octet hidden, never the full IP',
    `city` VARCHAR(120) NULL,
    `country` VARCHAR(120) NULL,
    `country_code` CHAR(2) NULL,
    `lat` DECIMAL(9, 6) NULL,
    `lng` DECIMAL(9, 6) NULL,
    `device` VARCHAR(80) NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_room_events_interview` (`interview_id`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `platform_settings` (
    `setting_key` VARCHAR(100) NOT NULL,
    `setting_value` TEXT NOT NULL,
    `updated_at` DATETIME NOT NULL,
    PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `scheduled_interviews` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `candidate_id` BIGINT UNSIGNED NOT NULL DEFAULT 1,
    `employer_id` BIGINT UNSIGNED NOT NULL DEFAULT 1,
    `candidate_name` VARCHAR(150) NOT NULL,
    `candidate_role` VARCHAR(150) NOT NULL,
    `company_name` VARCHAR(150) NOT NULL,
    `interviewer_name` VARCHAR(150) NOT NULL,
    `meeting_type` VARCHAR(20) NOT NULL DEFAULT 'virtual',
    `meeting_link` VARCHAR(500) NOT NULL DEFAULT '',
    `interview_date` VARCHAR(20) NOT NULL,
    `interview_time` VARCHAR(20) NOT NULL,
    `duration` VARCHAR(20) NOT NULL DEFAULT '30 mins',
    `status` VARCHAR(20) NOT NULL DEFAULT 'Scheduled',
    `notes` TEXT NULL,
    `scheduled_at` DATETIME NULL,
    `duration_min` SMALLINT UNSIGNED NULL,
    `setup_json` TEXT NULL COMMENT 'Employer criteria, questions and decision options',
    `created_by` VARCHAR(20) NULL COMMENT 'employer | admin (NULL = legacy row, hidden from lists)',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_sched_employer` (`employer_id`, `scheduled_at`),
    INDEX `idx_sched_candidate` (`candidate_id`, `scheduled_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `interview_scorecards` (
    `interview_id` BIGINT UNSIGNED NOT NULL,
    `scores` TEXT NOT NULL COMMENT 'JSON: criterion key -> 0..5',
    `recommendation` VARCHAR(40) NULL,
    `notes` TEXT NULL,
    `asked` TEXT NOT NULL COMMENT 'JSON array of asked question ids',
    `updated_at` DATETIME NOT NULL,
    PRIMARY KEY (`interview_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `interview_files` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `interview_id` BIGINT UNSIGNED NOT NULL,
    `role` VARCHAR(20) NOT NULL,
    `uploader` VARCHAR(80) NOT NULL,
    `meta` TEXT NOT NULL COMMENT 'UploadStorage metadata (private file in storage/uploads/interview_file)',
    `created_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_files_interview` (`interview_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `interview_chat` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `interview_id` BIGINT UNSIGNED NOT NULL,
    `role` VARCHAR(20) NOT NULL,
    `sender` VARCHAR(80) NOT NULL,
    `body` TEXT NOT NULL,
    `file_id` BIGINT UNSIGNED NULL,
    `created_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_chat_interview` (`interview_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `interview_reports` (
    `interview_id` BIGINT UNSIGNED NOT NULL,
    `summary` TEXT NOT NULL COMMENT 'JSON: overview, highlights, concerns, suggested rating/decision',
    `source` VARCHAR(20) NOT NULL COMMENT 'gemini | rules',
    `updated_at` DATETIME NOT NULL,
    PRIMARY KEY (`interview_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `geo_cache` (
    `ip_key` VARCHAR(64) NOT NULL,
    `data` TEXT NULL,
    `fetched_at` DATETIME NOT NULL,
    PRIMARY KEY (`ip_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `message_threads` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `employer_id` BIGINT UNSIGNED NOT NULL,
    `candidate_id` BIGINT UNSIGNED NOT NULL,
    `internship_id` BIGINT UNSIGNED NULL,
    `last_message_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_thread_participants` (`employer_id`, `candidate_id`),
    INDEX `idx_thread_last_message` (`last_message_at` DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `direct_messages` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `thread_id` BIGINT UNSIGNED NOT NULL,
    `sender_role` ENUM('candidate', 'employer', 'admin') NOT NULL,
    `sender_user_id` BIGINT UNSIGNED NOT NULL,
    `message_text` TEXT NOT NULL,
    `attachment_url` VARCHAR(500) NULL,
    `is_read` TINYINT(1) NOT NULL DEFAULT 0,
    `read_at` TIMESTAMP NULL DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_dm_thread_created` (`thread_id`, `created_at` ASC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `in_app_notifications` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `recipient_role` ENUM('candidate', 'employer', 'admin') NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `message` TEXT NOT NULL,
    `type` ENUM('message', 'interview', 'application', 'offer', 'verification', 'system') NOT NULL DEFAULT 'system',
    `action_url` VARCHAR(500) NOT NULL,
    `is_read` TINYINT(1) NOT NULL DEFAULT 0,
    `read_at` TIMESTAMP NULL DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_notif_user_unread` (`user_id`, `is_read`, `created_at` DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `ai_usage_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `feature` ENUM('cv_analysis', 'mock_interview', 'cover_letter', 'career_coach', 'smart_match') NOT NULL,
    `prompt_tokens` INT UNSIGNED NOT NULL DEFAULT 0,
    `completion_tokens` INT UNSIGNED NOT NULL DEFAULT 0,
    `total_tokens` INT UNSIGNED NOT NULL DEFAULT 0,
    `latency_ms` INT UNSIGNED NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_ai_feature_created` (`feature`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `audit_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NULL,
    `action` VARCHAR(100) NOT NULL,
    `entity_type` VARCHAR(100) NOT NULL,
    `entity_id` BIGINT UNSIGNED NULL,
    `old_values` JSON NULL,
    `new_values` JSON NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `user_agent` VARCHAR(500) NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_audit_entity` (`entity_type`, `entity_id`),
    INDEX `idx_audit_user` (`user_id`),
    INDEX `idx_audit_created` (`created_at` DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

