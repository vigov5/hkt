-- Add the following columns
-- `price` to item_users, item_shop
-- `force_sale` to item_users, item_shop

ALTER TABLE `item_users` ADD COLUMN `force_sale` INT DEFAULT 0 AFTER `user_id`;
ALTER TABLE `item_shops` ADD COLUMN `force_sale` INT DEFAULT 0 AFTER `shop_id`;
ALTER TABLE `item_users` ADD COLUMN `price` INT DEFAULT 0 AFTER `user_id`;
ALTER TABLE `item_shops` ADD COLUMN `price` INT DEFAULT 0 AFTER `shop_id`;
