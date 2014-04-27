-- MySQL dump 10.13  Distrib 5.6.17, for osx10.9 (x86_64)
--
-- Host: localhost    Database: hyakkaten
-- ------------------------------------------------------
-- Server version	5.6.17

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `failed_logins`
--

DROP TABLE IF EXISTS `failed_logins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_logins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_agent` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_logins`
--

LOCK TABLES `failed_logins` WRITE;
/*!40000 ALTER TABLE `failed_logins` DISABLE KEYS */;
INSERT INTO `failed_logins` VALUES (1,1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36','2014-04-26 14:38:49');
/*!40000 ALTER TABLE `failed_logins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_user_id` int(11) NOT NULL,
  `to_user_id` int(11) DEFAULT NULL,
  `to_shop_id` int(11) DEFAULT NULL,
  `item_id` int(11) NOT NULL,
  `item_count` int(11) NOT NULL DEFAULT '1',
  `price` int(11) DEFAULT '0',
  `status` tinyint(4) NOT NULL COMMENT '1: SENT, 2: REJECTED, 3: ACCEPTED',
  `set_items_id` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'If user buy a set of items (for example: lunch), so all the items id will be stored here. The item_id field will store the price of the set.',
  `comment` text COLLATE utf8_unicode_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (1,2,1,NULL,9,1,90,1,NULL,NULL,'2014-04-26 14:40:22',NULL),(2,2,1,NULL,10,1,100,1,NULL,NULL,'2014-04-26 14:45:39',NULL);
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_shops`
--

DROP TABLE IF EXISTS `item_shops`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_shops` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `force_sale` INT DEFAULT 0,
  `price` INT DEFAULT 0,
  `start_sale_date` datetime DEFAULT NULL,
  `end_sale_date` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_shops`
--

LOCK TABLES `item_shops` WRITE;
/*!40000 ALTER TABLE `item_shops` DISABLE KEYS */;
/*!40000 ALTER TABLE `item_shops` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_users`
--

DROP TABLE IF EXISTS `item_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `force_sale` INT DEFAULT 0,
  `price` INT DEFAULT 0,
  `start_sale_date` datetime DEFAULT NULL,
  `end_sale_date` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_users`
--

LOCK TABLES `item_users` WRITE;
/*!40000 ALTER TABLE `item_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `item_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `price` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL COMMENT '1: DEPOSIT - The wallet of users will be increased after users buy these items, 2: WITHDRAW - The wallet of users will be decreased after users buy these items, 3: NORMAL - The normal items. If an user buy these item, the money will be transfered to the seller, 4: SET - For special purpose. The price will be ignored. (For example: dishes for lunch), 5: SET_TICKET - Items to buy set of items.',
  `status` tinyint(4) DEFAULT NULL COMMENT '0: UNAVAILABLE, 1: AVAILABLE',
  `description` text COLLATE utf8_unicode_ci,
  `img` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `public_range` tinyint(4) DEFAULT NULL COMMENT '1: ONY_CREATED_USER: Only the user who created this item can sell it, 2: PUBLIC: All users can sell it',
  `created_by` int(11) DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL COMMENT 'The admin who allow this item to be able to sell',
  `approved_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (1,'Deposit 10HKT',10,1,NULL,'','',NULL,1,NULL,NULL,'2014-04-26 14:32:59',NULL,NULL),(2,'Deposit 20HKT',20,1,NULL,'','',NULL,1,NULL,NULL,'2014-04-26 14:33:12',NULL,NULL),(3,'Deposit 30HKT',30,1,NULL,'','',NULL,1,NULL,NULL,'2014-04-26 14:33:22',NULL,NULL),(4,'Deposit 40HKT',40,1,NULL,'','',NULL,1,NULL,NULL,'2014-04-26 14:33:34',NULL,NULL),(5,'Deposit 50HKT',50,1,NULL,'','',NULL,1,NULL,NULL,'2014-04-26 14:33:48',NULL,NULL),(6,'Deposit 60HKT',60,1,NULL,'','',NULL,1,NULL,NULL,'2014-04-26 14:33:59',NULL,NULL),(7,'Deposit 70HKT',70,1,NULL,'','',NULL,1,NULL,NULL,'2014-04-26 14:34:16',NULL,NULL),(8,'Depoist 80HKT',80,1,NULL,'','',NULL,1,NULL,NULL,'2014-04-26 14:34:28',NULL,NULL),(9,'Deposit 90HKT',90,1,NULL,'','',NULL,1,NULL,NULL,'2014-04-26 14:34:47',NULL,NULL),(10,'Deposit 100HKT',100,1,NULL,'','',NULL,1,NULL,NULL,'2014-04-26 14:34:58',NULL,NULL);
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `remember_tokens`
--

DROP TABLE IF EXISTS `remember_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `remember_tokens` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `token` char(32) COLLATE utf8_unicode_ci NOT NULL,
  `user_agent` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `remember_tokens`
--

LOCK TABLES `remember_tokens` WRITE;
/*!40000 ALTER TABLE `remember_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `remember_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `requests`
--

DROP TABLE IF EXISTS `requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_user_id` int(11) DEFAULT NULL,
  `to_user_id` int(11) DEFAULT NULL COMMENT 'If the value is 0, this request is sent to all admins.',
  `from_shop_id` int(11) DEFAULT NULL,
  `type` tinyint(4) NOT NULL COMMENT '1: REGISTER: A new user registered, 2: CREATE_ITEM: An user want to create an item,3: CREATE_SHOP: An user want to create an shop, 4: USER_SELL_ITEM: An user want to register an item to sell, 5: SHOP_SELL_ITEM: A shop want to register an item to sell.',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1: SENT, 2: REJECTED, 3: ACCEPTED',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `requests`
--

LOCK TABLES `requests` WRITE;
/*!40000 ALTER TABLE `requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shops`
--

DROP TABLE IF EXISTS `shops`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shops` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0: UNAUTHORIZED, 1: CLOSED, 2: OPEN',
  `description` text COLLATE utf8_unicode_ci,
  `img` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shops`
--

LOCK TABLES `shops` WRITE;
/*!40000 ALTER TABLE `shops` DISABLE KEYS */;
/*!40000 ALTER TABLE `shops` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `success_logins`
--

DROP TABLE IF EXISTS `success_logins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `success_logins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_agent` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `success_logins`
--

LOCK TABLES `success_logins` WRITE;
/*!40000 ALTER TABLE `success_logins` DISABLE KEYS */;
INSERT INTO `success_logins` VALUES (1,1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36','2014-04-26 14:30:37'),(2,1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36','2014-04-26 14:32:26'),(3,1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36','2014-04-26 14:38:56'),(4,2,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36','2014-04-26 14:39:21');
/*!40000 ALTER TABLE `success_logins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_shops`
--

DROP TABLE IF EXISTS `user_shops`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_shops` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `role` int(11) NOT NULL COMMENT '1: FOUNDER - The user will take the money when an item is bought, 2: ASSISTANT - The users who help founder manage the shop',
  `sales` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_shops`
--

LOCK TABLES `user_shops` WRITE;
/*!40000 ALTER TABLE `user_shops` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_shops` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `wallet` int(11) DEFAULT '0',
  `role` tinyint(4) DEFAULT NULL COMMENT '0: UNAUTHORIZED - Can not login, 1: USER - Can login and buy items, 2: MODERATOR - Can buy, can create and sell items , 3: ADMIN - Can accept requests, authorize users, change role of user ..., 4: SUPER ADMIN - Most powerful user',
  `secret_key` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `wallet_updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'sadmin','$2a$08$mSgE0mwydCs.bXqfwcTBceNIw7FTGCbbVs5gM1sL6tV1AU8NyvZ4i','thangtd90@gmail.com',1000,40,'d11a6ddfeaefe62cd8cbbecaf1327df329e5fb90','2014-04-26 14:30:28','2014-04-26 14:35:21',NULL,NULL),(2,'admin','$2a$08$mSgE0mwydCs.bXqfwcTBceNIw7FTGCbbVs5gM1sL6tV1AU8NyvZ4i','test1@framgia.com',720,30,'374b9388587079661543ed12a4ae2789d476064b','2014-04-26 14:31:16','2014-04-26 14:45:39',NULL,NULL),(3,'test2','$2a$08$mSgE0mwydCs.bXqfwcTBceNIw7FTGCbbVs5gM1sL6tV1AU8NyvZ4i','test2@framgia.com',1000,10,'655b4cd11c0b09855f41fcf021dbcdc92468fa7c','2014-04-26 14:31:37',NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wallet_logs`
--

DROP TABLE IF EXISTS `wallet_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wallet_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `wallet_before` int(11) DEFAULT NULL,
  `wallet_after` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wallet_logs`
--

LOCK TABLES `wallet_logs` WRITE;
/*!40000 ALTER TABLE `wallet_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `wallet_logs` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-04-26 14:46:43
