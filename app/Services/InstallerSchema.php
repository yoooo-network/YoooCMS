<?php

namespace App\Services;

use CodeIgniter\Database\BaseConnection;
use RuntimeException;

/** Creates the schema required by the website and its installer. */
class InstallerSchema
{
    public function createRequiredTables(BaseConnection $db): void
    {
        $statements = [
            "CREATE TABLE IF NOT EXISTS `users` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(100) NOT NULL,
                `email` VARCHAR(100) NOT NULL,
                `password` VARCHAR(255) NOT NULL,
                `reset_token` VARCHAR(255) DEFAULT NULL,
                `reset_expires` DATETIME DEFAULT NULL,
                `email_verification_token` VARCHAR(255) DEFAULT NULL,
                `email_verified_at` DATETIME DEFAULT NULL,
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`), UNIQUE KEY `email` (`email`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            "CREATE TABLE IF NOT EXISTS `countries` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(100) NOT NULL,
                `slug` VARCHAR(120) NOT NULL,
                `phone_code` VARCHAR(10) DEFAULT NULL,
                `currency_code` CHAR(3) DEFAULT NULL,
                `is_active` TINYINT DEFAULT 1,
                `sort_order` INT DEFAULT 0,
                `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`), UNIQUE KEY `slug` (`slug`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
            "CREATE TABLE IF NOT EXISTS `cities` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `country_id` INT NOT NULL,
                `name` VARCHAR(150) NOT NULL,
                `slug` VARCHAR(180) NOT NULL,
                `is_active` TINYINT DEFAULT 1,
                `sort_order` INT DEFAULT 0,
                `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`), UNIQUE KEY `country_id_slug_unique` (`country_id`, `slug`),
                KEY `country_id_2` (`country_id`),
                CONSTRAINT `fk_city_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
            "CREATE TABLE IF NOT EXISTS `profiles` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `user_id` INT NOT NULL,
                `name` VARCHAR(100) NOT NULL,
                `gender` ENUM('Male','Female','Trans','Other') DEFAULT NULL,
                `sexuality` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
                `dob` DATE DEFAULT NULL,
                `location` VARCHAR(150) DEFAULT NULL,
                `description` TEXT DEFAULT NULL,
                `height` VARCHAR(20) DEFAULT NULL,
                `weight` VARCHAR(20) DEFAULT NULL,
                `eye_color` VARCHAR(50) DEFAULT NULL,
                `hair_type` VARCHAR(50) DEFAULT NULL,
                `skin_color` VARCHAR(50) DEFAULT NULL,
                `body_structure` VARCHAR(50) DEFAULT NULL,
                `ethnicity` VARCHAR(50) DEFAULT NULL,
                `phone` VARCHAR(20) DEFAULT NULL,
                `whatsapp` VARCHAR(20) DEFAULT NULL,
                `telegram` VARCHAR(50) DEFAULT NULL,
                `facebook` VARCHAR(255) DEFAULT NULL,
                `instagram` VARCHAR(255) DEFAULT NULL,
                `discord` VARCHAR(255) DEFAULT NULL,
                `website` VARCHAR(255) DEFAULT NULL,
                `images` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
                `services` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
                `languages` TEXT DEFAULT NULL,
                `pricing` LONGTEXT DEFAULT NULL,
                `is_blocked` TINYINT(1) DEFAULT 1,
                `membership` ENUM('free','premium') NOT NULL DEFAULT 'free',
                `is_verified` TINYINT(1) NOT NULL DEFAULT 0,
                `status` ENUM('draft','pending','approved') NOT NULL DEFAULT 'draft',
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `other_pages` TEXT DEFAULT NULL,
                PRIMARY KEY (`id`), KEY `user_id` (`user_id`),
                CONSTRAINT `profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci",
            "CREATE TABLE IF NOT EXISTS `seo` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `url` VARCHAR(255) NOT NULL,
                `title` VARCHAR(255) NOT NULL,
                `description` TEXT DEFAULT NULL,
                `meta_keywords` TEXT DEFAULT NULL,
                `h1` VARCHAR(255) DEFAULT NULL,
                `intro_content` TEXT DEFAULT NULL,
                `seo_content` MEDIUMTEXT DEFAULT NULL,
                `created_at` DATETIME DEFAULT NULL,
                `updated_at` DATETIME DEFAULT NULL,
                PRIMARY KEY (`id`), UNIQUE KEY `url_unique` (`url`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            "CREATE TABLE IF NOT EXISTS `bookings` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `profile_id` INT NOT NULL,
                `name` VARCHAR(100) NOT NULL,
                `phone` VARCHAR(40) NOT NULL,
                `message` TEXT NOT NULL,
                `status` ENUM('pending','approved') NOT NULL DEFAULT 'pending',
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`), KEY `bookings_profile_status` (`profile_id`, `status`),
                CONSTRAINT `bookings_profile_fk` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        ];

        foreach ($statements as $statement) {
            if ($db->query($statement) === false) {
                $error = $db->error();
                throw new RuntimeException($error['message'] ?? 'A required database table could not be created.');
            }
        }
    }
}
