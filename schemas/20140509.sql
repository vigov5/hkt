ALTER TABLE `hyakkaten`.`invoices` ADD COLUMN `hcoin_receive` INT DEFAULT 0 AFTER `price`;
ALTER TABLE `hyakkaten`.`invoices` ADD COLUMN `real_price` INT DEFAULT 0 AFTER `price`;
ALTER TABLE `hyakkaten`.`wallet_logs` ADD COLUMN `hcoin_receive` INT DEFAULT 0 AFTER `price`;

CREATE TABLE IF NOT EXISTS `hyakkaten`.`setting` (
  `maintain` INT DEFAULT 0,
  `hcoin_rate` INT DEFAULT 1,
  `charge_rate` INT DEFAULT 5,
  `updated_by` INT NOT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL)
  ENGINE = InnoDB;

INSERT INTO `hyakkaten`.`setting` VALUES (0, 1, 5, 1, null, null);