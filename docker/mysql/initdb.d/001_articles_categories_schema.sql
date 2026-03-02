SET NAMES utf8mb4;
SET time_zone = '+00:00';

CREATE TABLE IF NOT EXISTS `categories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `articles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` BIGINT UNSIGNED NOT NULL,
  `image` VARCHAR(2048) NULL,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `content` LONGTEXT NOT NULL,
  `views` BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `published_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_articles_category_id` (`category_id`),
  KEY `idx_articles_views` (`views`),
  KEY `idx_articles_published_at` (`published_at`),
  CONSTRAINT `fk_articles_category`
    FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- MySQL doesn't support CREATE PROCEDURE IF NOT EXISTS,
-- so we conditionally create it without dropping.
SET @db := DATABASE();
SET @proc_exists := (
  SELECT COUNT(*)
  FROM information_schema.routines
  WHERE routine_schema = @db
    AND routine_type = 'PROCEDURE'
    AND routine_name = 'soft_delete_category'
);

SET @sql := IF(
  @proc_exists = 0,
  'CREATE PROCEDURE `soft_delete_category`( IN p_category_id BIGINT UNSIGNED, IN p_deleted_at TIMESTAMP ) BEGIN DECLARE v_deleted_at TIMESTAMP; SET v_deleted_at = COALESCE(p_deleted_at, UTC_TIMESTAMP()); START TRANSACTION; UPDATE `articles` SET `deleted_at` = v_deleted_at WHERE `id` = p_category_id AND `deleted_at` IS NULL; IF ROW_COUNT() > 0 THEN UPDATE `articles` SET `deleted_at` = v_deleted_at WHERE `category_id` = p_category_id AND `deleted_at` IS NULL; END IF; COMMIT; END',
  'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

