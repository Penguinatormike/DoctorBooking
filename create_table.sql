CREATE TABLE `user` (
  `user_id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `u_name` VARCHAR(255) UNIQUE
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
CREATE TABLE `booking` (
  `booking_id` INT(11)  UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `bk_user_id` INT(11) UNSIGNED NOT NULL,
  `bk_reason` TEXT,
  `bk_start_date` BIGINT(11) NOT NULL,
  `bk_end_date` BIGINT(11) NULL,
  INDEX(`bk_user_id`),
  FOREIGN KEY (`bk_user_id`) REFERENCES user(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;