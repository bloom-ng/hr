CREATE TABLE `equipment_log_tbl` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `staff_id` INT(11) NOT NULL,
  `equipment_id` INT(11) UNSIGNED NOT NULL,
  `purpose` text NOT NULL,
  `requested_date` DATETIME NOT NULL,
  `returned_date` DATETIME DEFAULT NULL,
  `status` ENUM('in_use','returned') NOT NULL DEFAULT 'in_use',
  `request_status` ENUM('pending','approved','declined','cancelled') NOT NULL DEFAULT 'pending',  
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`staff_id`) REFERENCES `staff_tbl`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`equipment_id`) REFERENCES `equipment_tbl`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;