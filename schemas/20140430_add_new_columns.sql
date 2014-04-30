-- Add the following columns

ALTER TABLE `requests` ADD COLUMN `item_id` INT DEFAULT 0;
ALTER TABLE `requests` ADD COLUMN `updated_by` INT;
ALTER TABLE `requests` ADD COLUMN `created_at` DATETIME ;
ALTER TABLE `requests` ADD COLUMN `updated_at` DATETIME ;

