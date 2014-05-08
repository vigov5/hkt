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
-- Dumping data for table `failed_logins`
--

LOCK TABLES `failed_logins` WRITE;
/*!40000 ALTER TABLE `failed_logins` DISABLE KEYS */;
INSERT INTO `failed_logins` VALUES (1,1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36','2014-04-26 14:38:49'),(2,5,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36','2014-05-04 16:51:20'),(3,5,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36','2014-05-04 16:51:26'),(4,0,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36','2014-05-04 16:51:31'),(5,0,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36','2014-05-04 16:51:37'),(6,1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36','2014-05-07 21:34:24');
/*!40000 ALTER TABLE `failed_logins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `faqs`
--

LOCK TABLES `faqs` WRITE;
/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;
INSERT INTO `faqs` VALUES (1,0,1,'What is Framgia Hyakkaten','The Hyakkaten (百貨店) in Japanese means \"department store\" (Cửa hàng bách hoá).<br>\r\n        And the purpose of this service is to provide Framgia\'s Members a store where they can buy or sell what they want.<br>\r\n        Framgia Hyakkaten is powered by the PhalconPHP Framework.',0,'2014-05-08 21:36:45',NULL),(2,0,1,'Why couldn\'t I access to any pages after logging in ?','Because Framgia Hyakkaten is a restricted service, your account needs to be authorized.<br>\r\n        So please use an ease-to-realize username and email when register.<br>\r\n        After that please contact the administrators  to authorize it for you.',0,'2014-05-08 21:37:11',NULL),(3,0,1,'How can I deposit money to my account in Hyakkaten','Go to the Deposit page, just buy it like other items.<br>\r\nThen an invoice will be created. <br> \r\nGive money to the administrator, ask him to accept the invoice then your wallet will be increased !<br>\r\nAt the moment, there is no way to deposit accept for give money directly to administrator, but the \"Internet Banking Transfer\" feature is being considered and may be available in the feature !',0,'2014-05-08 21:41:40','2014-05-08 21:57:35'),(4,0,1,'How can I withdraw money to my account in Hyakkaten','Go to Withdraw page, choose how many money you want to withdraw. <br>\r\nCreate an invoice, then contact the administrator to receive the money !',0,'2014-05-08 21:43:39',NULL),(5,0,1,'Beside the main wallet, I also have some HCoin, what does it mean ?','The HCoin (HKT Coin) is the Framgia Hyakkaten\'s virtual money. <br>\r\nYou can use it to buy goods in Framgia Hyakkaten, but you can not change it to real money (VND). <br>\r\nIt can not be withdrawn.',0,'2014-05-08 21:44:34',NULL),(6,0,1,'How can I gain HCoin ?','You can receive HCoin when you buy goods in Hyakkaten. In general, you will receive 1 HCoin after purchasing 100 VND.<br>\r\nDuring special campaigns, you can receive more than that rate.',0,'2014-05-08 21:45:29',NULL),(7,0,1,'Why didn\'t I receive any HCoin after purchasing an item ?','After purchasing an item, an invoices will be created, and your wallet will be deducted. But it does not mean the transaction had been completed. You can still <span class=\"text-danger\">CANCEL</span> the invoice to get your money back.<br>\r\n        Or because of some reason, your invoice can be <span class=\"text-warning\">REJECTED</span> by the seller, so at that time you will also get the refund.<br>\r\n        Only when the invoice is <span class=\"text-success\">ACCEPTED</span> by the seller, the transaction is considered as <span class=\"text-primary\">COMPLETED</span>, and you can not get the refund any more. But at that time, you will receive HCoin.',0,'2014-05-08 21:46:00',NULL),(8,0,1,'I want to sell a good, what do I need to do ?','At first, you should check the currently available items in the item page. If the item you want to sell has already existed, you can send request to become the seller of that item.<br>\r\nAfter the administrator accepts your request, you can sell it to other members.',0,'2014-05-08 21:46:32',NULL),(9,0,1,'I have created an item but why doesn\'t it appear in my selling items list ?','After you create an item, by default it will be unavailable which means that it will not appear in your selling items page, and it also be unavailable for other members to access.<br>\r\nYour item need to be checked by the administrator. If it is OK, it will become available, and then you can start selling it.<br>\r\nSo if you have waited too long, fell free to contact the administrators :D',0,'2014-05-08 21:47:02',NULL),(10,0,1,'Can I send money to other accounts ?','At that time, you can only send HCoin to others.<br>\r\nSending real money to other accounts will be available in the feature after we find an ease and secure way for users do it.',0,'2014-05-08 21:47:22','2014-05-08 21:53:25'),(11,0,1,'I want to develop other services for Framgia Member, which sometime requires payment or something else like this. Do you provide API for me to make payment through Hyakkaten.','\"Paying with HKT\" is one of the interesting features that I really want to deploy :D<br>\r\nBut it seems to be very hard for me to do it alone so that feature will not available in the near feature.',0,'2014-05-08 21:47:51',NULL),(12,0,1,'I want to learn PHP. I want to learn Phalcon Framework. I want to help you to deploy more features to Framgia Hyakkaten. What should I do ?','Please fell free to contact us. <br>\r\nFind more information at <a href=\"/contact\">contact page</a><br>You are always welcome (dance)',0,'2014-05-08 21:49:54',NULL);
/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `hcoin_logs`
--

LOCK TABLES `hcoin_logs` WRITE;
/*!40000 ALTER TABLE `hcoin_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `hcoin_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `inquiries`
--

LOCK TABLES `inquiries` WRITE;
/*!40000 ALTER TABLE `inquiries` DISABLE KEYS */;
/*!40000 ALTER TABLE `inquiries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (1,2,1,NULL,9,1,90,4,NULL,NULL,'2014-04-26 14:40:22','2014-05-01 19:43:11'),(2,2,1,NULL,10,1,100,4,NULL,NULL,'2014-04-26 14:45:39','2014-05-01 21:18:58'),(3,2,1,NULL,9,1,90,4,NULL,NULL,'2014-04-26 19:17:49','2014-05-01 21:17:01'),(4,2,1,NULL,9,1,90,4,NULL,NULL,'2014-04-26 19:18:00','2014-05-01 21:16:58'),(5,2,1,NULL,11,1,12,2,NULL,NULL,'2014-04-28 21:24:18','2014-05-01 20:04:31'),(6,2,1,NULL,11,1,12,2,NULL,NULL,'2014-04-28 21:24:32','2014-05-01 20:04:26'),(7,2,1,NULL,10,1,100,2,NULL,NULL,'2014-04-30 19:41:04','2014-05-01 20:04:19'),(8,2,1,NULL,10,1,100,4,NULL,NULL,'2014-04-30 19:41:10','2014-05-01 20:01:56'),(9,2,1,NULL,11,1,12,4,NULL,NULL,'2014-04-30 19:41:30','2014-05-01 19:51:20'),(10,2,1,NULL,11,1,13,4,NULL,NULL,'2014-04-30 19:46:14','2014-05-01 19:41:29'),(11,2,1,NULL,1,1,10,4,NULL,NULL,'2014-04-30 19:46:48','2014-05-01 19:40:17'),(12,2,1,NULL,11,2,26,4,NULL,NULL,'2014-04-30 19:52:14','2014-05-01 19:39:32'),(13,2,1,NULL,11,38,494,3,NULL,NULL,'2014-04-30 19:52:39','2014-05-01 19:27:05'),(14,2,1,NULL,11,1,13,2,NULL,NULL,'2014-04-30 19:54:42','2014-05-01 18:10:39'),(15,2,1,NULL,11,1,13,4,NULL,NULL,'2014-04-30 19:55:34','2014-05-01 18:09:06'),(16,2,1,NULL,5,1,50,4,NULL,NULL,'2014-04-30 19:55:56','2014-05-01 18:08:55'),(17,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 16:57:01','2014-05-01 18:05:23'),(18,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 16:58:13','2014-05-01 17:56:34'),(19,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 16:58:39','2014-05-01 17:53:12'),(20,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 16:59:52','2014-05-01 17:43:38'),(21,1,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 20:04:44','2014-05-01 20:04:54'),(22,1,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 20:21:55','2014-05-01 20:22:01'),(23,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 20:39:12','2014-05-01 20:41:04'),(24,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 20:41:37','2014-05-01 20:48:58'),(25,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 20:41:41','2014-05-01 20:48:51'),(26,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 20:42:07','2014-05-01 20:46:32'),(27,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 20:46:01','2014-05-01 20:53:48'),(28,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 20:50:45','2014-05-01 20:53:33'),(29,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 20:54:32','2014-05-01 21:16:29'),(30,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 20:54:37','2014-05-01 21:16:27'),(31,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 20:54:40','2014-05-01 20:56:26'),(32,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 21:06:05','2014-05-01 21:08:23'),(33,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 21:06:45','2014-05-01 21:08:58'),(34,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 21:06:55','2014-05-01 21:09:22'),(35,1,1,NULL,11,1,13,3,NULL,NULL,'2014-05-01 21:08:00','2014-05-04 10:58:34'),(36,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 21:08:16','2014-05-01 21:12:26'),(37,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 21:10:46','2014-05-01 21:12:56'),(38,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 21:19:28','2014-05-01 21:23:39'),(39,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 21:19:31','2014-05-01 21:23:34'),(40,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 21:19:34','2014-05-01 21:23:04'),(41,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 21:19:37','2014-05-01 21:21:33'),(42,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 21:24:27','2014-05-01 21:28:01'),(43,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 21:24:30','2014-05-01 21:27:45'),(44,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 21:24:33','2014-05-01 21:27:13'),(45,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 21:24:36','2014-05-01 21:26:59'),(46,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 21:24:39','2014-05-01 21:26:53'),(47,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 21:24:42','2014-05-01 21:25:49'),(48,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-01 21:24:48','2014-05-01 21:25:27'),(49,2,1,NULL,1,1,10,4,NULL,NULL,'2014-05-01 21:28:46','2014-05-01 21:34:02'),(50,2,1,NULL,9,1,90,4,NULL,NULL,'2014-05-01 21:28:50','2014-05-01 21:34:00'),(51,2,1,NULL,10,1,100,4,NULL,NULL,'2014-05-01 21:28:54','2014-05-01 21:33:59'),(52,2,1,NULL,5,1,50,4,NULL,NULL,'2014-05-01 21:28:59','2014-05-01 21:33:54'),(53,2,1,NULL,11,1,13,2,NULL,NULL,'2014-05-01 21:34:26','2014-05-01 21:34:48'),(54,2,1,NULL,11,1,13,3,NULL,NULL,'2014-05-01 21:34:29','2014-05-01 21:34:46'),(55,2,1,NULL,11,1,13,2,NULL,NULL,'2014-05-01 21:34:32','2014-05-01 21:34:43'),(56,2,1,NULL,11,1,13,2,NULL,NULL,'2014-05-01 21:34:35','2014-05-01 21:34:41'),(57,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-03 10:02:50','2014-05-03 10:27:51'),(58,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-03 10:27:35','2014-05-03 10:27:51'),(59,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-03 10:27:38','2014-05-03 10:27:47'),(60,2,1,NULL,11,1,13,2,NULL,NULL,'2014-05-03 12:09:10','2014-05-03 12:42:04'),(61,2,1,NULL,11,1,13,2,NULL,NULL,'2014-05-03 12:25:01','2014-05-03 12:27:07'),(62,2,1,NULL,11,1,13,2,NULL,NULL,'2014-05-03 12:25:04','2014-05-03 12:26:21'),(63,2,1,NULL,11,2,26,2,NULL,NULL,'2014-05-03 12:25:09','2014-05-03 12:41:38'),(64,2,1,NULL,11,1,13,2,NULL,NULL,'2014-05-03 12:42:34','2014-05-03 12:42:49'),(65,2,1,NULL,11,1,13,2,NULL,NULL,'2014-05-03 12:42:37','2014-05-03 12:42:51'),(66,2,1,NULL,11,1,13,3,NULL,NULL,'2014-05-03 12:42:41','2014-05-03 12:43:00'),(67,2,1,NULL,11,1,13,3,NULL,NULL,'2014-05-03 12:52:20','2014-05-03 12:52:41'),(68,2,1,NULL,11,1,13,3,NULL,NULL,'2014-05-03 12:52:23','2014-05-03 12:52:32'),(69,2,1,NULL,11,1,13,4,NULL,NULL,'2014-05-03 12:52:26','2014-05-03 18:03:31'),(70,2,1,NULL,1,1,10,4,NULL,NULL,'2014-05-03 12:59:42','2014-05-03 12:59:48'),(72,1,5,NULL,2,1,20,1,NULL,NULL,'2014-05-04 20:51:38',NULL),(73,1,5,NULL,2,1,20,1,NULL,NULL,'2014-05-04 20:52:26',NULL),(74,1,5,NULL,2,1,20,1,NULL,NULL,'2014-05-04 20:53:24',NULL),(75,1,5,NULL,2,1,20,1,NULL,NULL,'2014-05-04 21:20:19',NULL),(76,1,5,NULL,12,1,20,2,NULL,NULL,'2014-05-04 21:27:28','2014-05-04 21:28:52'),(77,5,1,NULL,11,1,13,2,NULL,NULL,'2014-05-06 20:55:10','2014-05-06 20:55:17'),(78,5,1,NULL,11,1,13,2,NULL,NULL,'2014-05-06 20:57:13','2014-05-06 20:58:25'),(79,5,1,NULL,11,1,13,2,NULL,NULL,'2014-05-06 20:57:37','2014-05-06 20:58:34'),(80,5,1,NULL,11,2,26,3,NULL,NULL,'2014-05-06 20:57:43','2014-05-06 20:58:32');
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `item_shops`
--

LOCK TABLES `item_shops` WRITE;
/*!40000 ALTER TABLE `item_shops` DISABLE KEYS */;
/*!40000 ALTER TABLE `item_shops` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `item_users`
--

LOCK TABLES `item_users` WRITE;
/*!40000 ALTER TABLE `item_users` DISABLE KEYS */;
INSERT INTO `item_users` VALUES (1,1,1,12,0,'2014-05-03 12:00:00','2014-05-05 12:00:00',NULL,'2014-05-04 12:44:50',NULL),(2,2,1,20,0,'2014-05-04 12:45:00','2014-05-04 15:45:00',NULL,'2014-05-04 12:45:25',NULL),(3,3,1,30,0,'2014-05-04 15:00:00','2014-05-05 15:00:00',NULL,'2014-05-04 15:55:04',NULL),(4,4,1,40,0,'2014-05-03 00:00:00','2014-05-04 00:00:00',NULL,'2014-05-04 11:54:31',NULL),(5,5,1,50,0,NULL,NULL,NULL,'2014-05-03 22:05:59',NULL),(6,6,1,60,1,NULL,NULL,NULL,'2014-05-04 10:58:04',NULL),(7,7,1,70,1,'2014-05-05 09:30:00','2014-05-06 12:30:00',NULL,'2014-05-04 12:31:30',NULL),(8,8,1,80,1,NULL,NULL,NULL,'2014-05-06 21:31:22',NULL),(9,9,1,90,2,NULL,NULL,NULL,'2014-05-03 21:46:07',NULL),(10,10,1,100,2,NULL,NULL,NULL,'2014-05-03 21:46:10',NULL),(11,11,1,13,1,NULL,NULL,NULL,NULL,NULL),(12,2,5,0,0,'2014-05-04 20:30:00','2014-05-05 20:30:00','2014-05-04 20:19:53','2014-05-04 20:33:59',NULL),(13,12,5,NULL,1,NULL,NULL,'2014-05-04 20:49:50','2014-05-04 20:50:04',NULL),(14,12,1,NULL,1,NULL,NULL,'2014-05-06 19:28:13','2014-05-06 19:28:50',NULL),(15,11,2,NULL,1,NULL,NULL,'2014-05-06 21:28:21','2014-05-06 21:28:49',NULL),(16,12,2,NULL,1,NULL,NULL,'2014-05-06 21:28:31','2014-05-06 21:28:51',NULL);
/*!40000 ALTER TABLE `item_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (1,'Deposit 10HKT',10,1,0,'','',NULL,1,NULL,NULL,'2014-04-26 14:32:59',NULL,NULL),(2,'Deposit 20HKT',20,1,1,'','',NULL,1,NULL,NULL,'2014-04-26 14:33:12',NULL,NULL),(3,'Deposit 30HKT',30,1,1,'','',NULL,1,NULL,NULL,'2014-04-26 14:33:22',NULL,NULL),(4,'Deposit 40HKT',40,1,1,'','',NULL,1,NULL,NULL,'2014-04-26 14:33:34',NULL,NULL),(5,'Deposit 50HKT',50,1,1,'','',NULL,1,NULL,NULL,'2014-04-26 14:33:48',NULL,NULL),(6,'Deposit 60HKT',60,1,1,'','',NULL,1,NULL,NULL,'2014-04-26 14:33:59',NULL,NULL),(7,'Deposit 70HKT',70,1,1,'','',NULL,1,NULL,NULL,'2014-04-26 14:34:16',NULL,NULL),(8,'Depoist 80HKT',80,1,1,'','',NULL,1,NULL,NULL,'2014-04-26 14:34:28',NULL,NULL),(9,'Deposit 90HKT',90,1,1,'','',NULL,1,NULL,NULL,'2014-04-26 14:34:47',NULL,NULL),(10,'Deposit 100HKT',100,1,1,'','',NULL,1,NULL,NULL,'2014-04-26 14:34:58',NULL,NULL),(11,'Sua chua mit ',12,3,1,'Sua chua mit day du ','',NULL,2,NULL,NULL,'2014-04-27 20:48:13',NULL,NULL),(12,'Sua chua mit 2',20,3,1,'','',NULL,5,NULL,NULL,'2014-05-04 20:48:51','2014-05-04 20:49:30',NULL);
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `remember_tokens`
--

LOCK TABLES `remember_tokens` WRITE;
/*!40000 ALTER TABLE `remember_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `remember_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `requests`
--

LOCK TABLES `requests` WRITE;
/*!40000 ALTER TABLE `requests` DISABLE KEYS */;
INSERT INTO `requests` VALUES (1,5,0,NULL,1,2,NULL,NULL,'2014-04-30 20:52:35','2014-05-04 18:17:27'),(2,5,0,NULL,4,3,2,1,'2014-05-04 20:07:25','2014-05-04 20:19:31'),(3,5,0,NULL,4,2,2,NULL,'2014-05-04 20:19:38','2014-05-04 20:19:53'),(4,5,0,NULL,2,2,12,NULL,'2014-05-04 20:48:51','2014-05-04 20:49:30'),(5,5,0,NULL,4,2,12,NULL,'2014-05-04 20:48:51','2014-05-04 20:49:50'),(6,1,0,NULL,4,2,12,NULL,'2014-05-06 19:28:00','2014-05-06 19:28:13'),(7,2,0,NULL,4,3,12,2,'2014-05-06 21:27:44','2014-05-06 21:27:58'),(8,2,0,NULL,4,2,11,NULL,'2014-05-06 21:28:03','2014-05-06 21:28:21'),(9,2,0,NULL,4,3,12,2,'2014-05-06 21:28:08','2014-05-06 21:28:19'),(10,2,0,NULL,4,2,12,NULL,'2014-05-06 21:28:27','2014-05-06 21:28:31'),(11,6,0,NULL,1,2,NULL,NULL,'2014-05-07 21:51:22','2014-05-07 21:53:34');
/*!40000 ALTER TABLE `requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `shops`
--

LOCK TABLES `shops` WRITE;
/*!40000 ALTER TABLE `shops` DISABLE KEYS */;
/*!40000 ALTER TABLE `shops` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `success_logins`
--

LOCK TABLES `success_logins` WRITE;
/*!40000 ALTER TABLE `success_logins` DISABLE KEYS */;
INSERT INTO `success_logins` VALUES (1,1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36','2014-04-26 14:30:37'),(2,1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36','2014-04-26 14:32:26'),(3,1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36','2014-04-26 14:38:56'),(4,2,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36','2014-04-26 14:39:21'),(5,2,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36','2014-04-27 17:57:00'),(6,1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36','2014-04-30 21:21:28'),(7,2,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36','2014-05-01 16:58:31'),(8,1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36','2014-05-03 12:22:42'),(9,1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36','2014-05-03 12:32:34'),(10,3,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36','2014-05-04 11:55:02'),(11,2,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36','2014-05-04 16:47:34'),(12,3,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36','2014-05-04 16:47:49'),(13,5,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36','2014-05-04 16:52:04'),(14,5,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36','2014-05-04 18:46:05'),(15,2,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36','2014-05-06 21:17:09'),(16,2,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36','2014-05-07 21:34:38'),(17,1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36','2014-05-07 21:53:26'),(18,6,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36','2014-05-07 21:54:06'),(19,2,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36','2014-05-07 22:12:16'),(20,2,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36','2014-05-08 20:59:32'),(21,1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36','2014-05-08 21:01:34'),(22,6,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36','2014-05-08 21:04:19'),(23,2,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36','2014-05-08 21:07:19');
/*!40000 ALTER TABLE `success_logins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `user_shops`
--

LOCK TABLES `user_shops` WRITE;
/*!40000 ALTER TABLE `user_shops` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_shops` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'sadmin','$2a$08$mSgE0mwydCs.bXqfwcTBceNIw7FTGCbbVs5gM1sL6tV1AU8NyvZ4i','thangtd90@gmail.com',1206,40,'d11a6ddfeaefe62cd8cbbecaf1327df329e5fb90','2014-04-26 14:30:28','2014-05-06 20:58:34',NULL,NULL,1000),(2,'admin','$2a$08$mSgE0mwydCs.bXqfwcTBceNIw7FTGCbbVs5gM1sL6tV1AU8NyvZ4i','test1@framgia.com',1137,30,'374b9388587079661543ed12a4ae2789d476064b','2014-04-26 14:31:16','2014-05-03 18:03:31',NULL,NULL,1000),(3,'test2','$2a$08$mSgE0mwydCs.bXqfwcTBceNIw7FTGCbbVs5gM1sL6tV1AU8NyvZ4i','test2@framgia.com',1000,10,'655b4cd11c0b09855f41fcf021dbcdc92468fa7c','2014-04-26 14:31:37',NULL,NULL,NULL,1000),(4,'test3','$2a$08$dTELC1mPipR/OAH6QgQz6.wY81wYyENdwG6ogGNUlI1mzYA3aYskW','test3@framgia.com',0,0,'a8ce41e911a2b9a7edf0b82aabbe323b163b4cc4','2014-04-30 20:50:18',NULL,NULL,NULL,1000),(5,'test4','$2a$08$UVq.p6HzmSkFB9NpOO0i5eT8i2H4nmWQ7eDowUS3tamX8saeegwOe','test4@framgia.com',974,10,'aa7e942b9d48d9f805f62bb59eaed1d6bbba55ce','2014-04-30 20:52:35','2014-05-06 20:58:32',NULL,NULL,1000),(6,'test6','$2a$08$ymqG4hrQ5hiOT7ayftk7AepR48RSNWdlLpLf7YV0vshkMxUCoMDCC','test6@framgia.com',0,10,'fbb0d59de504d03ec36430a442d197263d441e77','2014-05-07 21:51:22','2014-05-07 21:53:34',NULL,NULL,1000);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `wallet_logs`
--

LOCK TABLES `wallet_logs` WRITE;
/*!40000 ALTER TABLE `wallet_logs` DISABLE KEYS */;
INSERT INTO `wallet_logs` VALUES (1,2,127,140,'2014-05-01 17:43:38'),(2,2,127,140,'2014-05-01 17:43:38'),(3,2,140,153,'2014-05-01 17:53:12'),(4,2,140,153,'2014-05-01 17:53:12'),(5,2,153,166,'2014-05-01 17:56:34'),(6,2,153,166,'2014-05-01 17:56:34'),(7,2,166,179,'2014-05-01 18:05:23'),(8,2,166,179,'2014-05-01 18:05:23'),(9,2,179,229,'2014-05-01 18:08:55'),(10,2,229,242,'2014-05-01 18:09:06'),(11,1,1000,1013,'2014-05-01 18:10:39'),(12,2,242,736,'2014-05-01 19:27:05'),(13,2,736,762,'2014-05-01 19:39:32'),(14,2,762,772,'2014-05-01 19:40:17'),(15,2,772,785,'2014-05-01 19:41:29'),(16,2,785,875,'2014-05-01 19:43:11'),(17,2,875,887,'2014-05-01 19:51:20'),(18,2,887,987,'2014-05-01 20:01:56'),(19,1,1013,1113,'2014-05-01 20:04:19'),(20,1,1113,1125,'2014-05-01 20:04:26'),(21,1,1125,1137,'2014-05-01 20:04:31'),(22,1,1137,1124,'2014-05-01 20:04:44'),(23,1,1124,1137,'2014-05-01 20:04:54'),(24,1,1137,1124,'2014-05-01 20:21:55'),(25,1,1124,1137,'2014-05-01 20:22:01'),(26,2,987,974,'2014-05-01 20:39:12'),(27,2,974,987,'2014-05-01 20:41:04'),(28,2,987,974,'2014-05-01 20:41:37'),(29,2,974,961,'2014-05-01 20:41:41'),(30,2,961,948,'2014-05-01 20:42:07'),(31,2,948,935,'2014-05-01 20:46:01'),(32,2,935,948,'2014-05-01 20:46:32'),(33,2,948,961,'2014-05-01 20:48:51'),(34,2,961,974,'2014-05-01 20:48:58'),(35,2,974,961,'2014-05-01 20:50:45'),(36,2,961,974,'2014-05-01 20:53:33'),(37,2,974,987,'2014-05-01 20:53:48'),(38,2,987,974,'2014-05-01 20:54:32'),(39,2,974,961,'2014-05-01 20:54:37'),(40,2,961,948,'2014-05-01 20:54:40'),(41,2,948,961,'2014-05-01 20:56:26'),(42,2,961,948,'2014-05-01 21:06:05'),(43,2,948,935,'2014-05-01 21:06:45'),(44,2,935,922,'2014-05-01 21:06:55'),(45,1,1137,1124,'2014-05-01 21:08:00'),(46,2,922,909,'2014-05-01 21:08:16'),(47,2,909,922,'2014-05-01 21:08:23'),(48,2,922,935,'2014-05-01 21:08:58'),(49,2,935,948,'2014-05-01 21:09:22'),(50,2,948,935,'2014-05-01 21:10:46'),(51,2,935,948,'2014-05-01 21:12:26'),(52,2,948,961,'2014-05-01 21:12:56'),(53,2,961,974,'2014-05-01 21:16:27'),(54,2,974,987,'2014-05-01 21:16:29'),(55,2,987,1077,'2014-05-01 21:16:58'),(56,2,1077,1167,'2014-05-01 21:17:01'),(57,2,1167,1267,'2014-05-01 21:18:58'),(58,2,1267,1254,'2014-05-01 21:19:28'),(59,2,1254,1241,'2014-05-01 21:19:31'),(60,2,1241,1228,'2014-05-01 21:19:34'),(61,2,1228,1215,'2014-05-01 21:19:37'),(62,2,1215,1228,'2014-05-01 21:21:33'),(63,2,1228,1241,'2014-05-01 21:23:04'),(64,2,1241,1254,'2014-05-01 21:23:34'),(65,2,1254,1267,'2014-05-01 21:23:39'),(66,2,1267,1254,'2014-05-01 21:24:27'),(67,2,1254,1241,'2014-05-01 21:24:30'),(68,2,1241,1228,'2014-05-01 21:24:33'),(69,2,1228,1215,'2014-05-01 21:24:36'),(70,2,1215,1202,'2014-05-01 21:24:39'),(71,2,1202,1189,'2014-05-01 21:24:42'),(72,2,1189,1176,'2014-05-01 21:24:48'),(73,2,1176,1189,'2014-05-01 21:25:27'),(74,2,1189,1202,'2014-05-01 21:25:49'),(75,2,1202,1215,'2014-05-01 21:26:53'),(76,2,1215,1228,'2014-05-01 21:26:59'),(77,2,1228,1241,'2014-05-01 21:27:13'),(78,2,1241,1254,'2014-05-01 21:27:45'),(79,2,1254,1267,'2014-05-01 21:28:01'),(80,2,1267,1257,'2014-05-01 21:28:46'),(81,2,1257,1167,'2014-05-01 21:28:50'),(82,2,1167,1067,'2014-05-01 21:28:54'),(83,2,1067,1017,'2014-05-01 21:28:59'),(84,2,1017,1067,'2014-05-01 21:33:54'),(85,2,1067,1167,'2014-05-01 21:33:59'),(86,2,1167,1257,'2014-05-01 21:34:00'),(87,2,1257,1267,'2014-05-01 21:34:02'),(88,2,1267,1254,'2014-05-01 21:34:26'),(89,2,1254,1241,'2014-05-01 21:34:29'),(90,2,1241,1228,'2014-05-01 21:34:32'),(91,2,1228,1215,'2014-05-01 21:34:35'),(92,1,1124,1137,'2014-05-01 21:34:41'),(93,1,1137,1150,'2014-05-01 21:34:43'),(94,2,1215,1228,'2014-05-01 21:34:46'),(95,1,1150,1163,'2014-05-01 21:34:48'),(96,2,1228,1215,'2014-05-03 10:02:50'),(97,2,1215,1202,'2014-05-03 10:27:35'),(98,2,1202,1189,'2014-05-03 10:27:38'),(99,2,1189,1202,'2014-05-03 10:27:47'),(100,2,1202,1215,'2014-05-03 10:27:51'),(101,2,1215,1228,'2014-05-03 10:27:51'),(102,2,1228,1215,'2014-05-03 12:09:10'),(103,2,1215,1202,'2014-05-03 12:25:01'),(104,2,1202,1189,'2014-05-03 12:25:04'),(105,2,1189,1163,'2014-05-03 12:25:09'),(106,1,1163,1176,'2014-05-03 12:26:21'),(107,1,1176,1189,'2014-05-03 12:27:07'),(108,1,1189,1215,'2014-05-03 12:41:38'),(109,1,1215,1228,'2014-05-03 12:42:04'),(110,2,1163,1150,'2014-05-03 12:42:34'),(111,2,1150,1137,'2014-05-03 12:42:37'),(112,2,1137,1124,'2014-05-03 12:42:41'),(113,1,1228,1241,'2014-05-03 12:42:49'),(114,1,1241,1254,'2014-05-03 12:42:51'),(115,2,1124,1137,'2014-05-03 12:43:00'),(116,2,1137,1124,'2014-05-03 12:52:20'),(117,2,1124,1111,'2014-05-03 12:52:23'),(118,2,1111,1098,'2014-05-03 12:52:26'),(119,2,1098,1111,'2014-05-03 12:52:32'),(120,2,1111,1124,'2014-05-03 12:52:41'),(121,2,1124,1114,'2014-05-03 12:59:42'),(122,2,1114,1124,'2014-05-03 12:59:48'),(123,2,1124,1137,'2014-05-03 18:03:31'),(124,1,1254,1267,'2014-05-04 10:58:34'),(125,1,1267,1247,'2014-05-04 20:51:38'),(126,1,1247,1227,'2014-05-04 20:52:26'),(127,1,1227,1207,'2014-05-04 20:53:24'),(128,1,1207,1187,'2014-05-04 21:20:19'),(129,1,1187,1167,'2014-05-04 21:27:28'),(130,5,0,20,'2014-05-04 21:28:52'),(131,5,20,7,'2014-05-06 20:55:10'),(132,1,1167,1180,'2014-05-06 20:55:17'),(133,5,1000,987,'2014-05-06 20:57:13'),(134,5,987,974,'2014-05-06 20:57:37'),(135,5,974,948,'2014-05-06 20:57:43'),(136,1,1180,1193,'2014-05-06 20:58:25'),(137,5,948,974,'2014-05-06 20:58:32'),(138,1,1193,1206,'2014-05-06 20:58:34');
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

-- Dump completed on 2014-05-08 22:03:34
