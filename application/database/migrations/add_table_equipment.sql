CREATE TABLE `equipment_tbl` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `unique_id` VARCHAR(100) NOT NULL UNIQUE,
  `status` ENUM('available','in_use','in_repair','damaged', 'missing') NOT NULL DEFAULT 'available',
  `image` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
