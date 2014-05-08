-- Add the following columns

ALTER TABLE `users` CHANGE `hkt` `hcoin` INT DEFAULT 0;

drop table `hkt_logs`;
-- -----------------------------------------------------
-- Table `hyakkaten`.`hcoin_logs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hyakkaten`.`hcoin_logs` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `hcoin_before` INT NULL,
  `hcoin_after` INT NULL,
  `created_at` DATETIME NULL,
  PRIMARY KEY (`id`))
  ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `hyakkaten`.`faqs` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `lang` INT DEFAULT 0,
  `created_by` INT NOT NULL,
  `question` TEXT ,
  `answer` TEXT ,
  `priority` INT DEFAULT 0,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`))
  ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `hyakkaten`.`inquiries` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT DEFAULT 0,
  `name` VARCHAR(45),
  `email` VARCHAR(45),
  `subject` VARCHAR(256),
  `content` TEXT,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`))
  ENGINE = InnoDB;