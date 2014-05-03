-- Add the following columns

ALTER TABLE `item_users` CHANGE `force_sale` `status` INT DEFAULT 0;

ALTER TABLE `users` ADD COLUMN `hkt` INT DEFAULT 0;

-- -----------------------------------------------------
-- Table `hyakkaten`.`hkt_logs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hyakkaten`.`hkt_logs` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `hkt_before` INT NULL,
  `hkt_after` INT NULL,
  `created_at` DATETIME NULL,
  PRIMARY KEY (`id`))
  ENGINE = InnoDB;

