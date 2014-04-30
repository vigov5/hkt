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
INSERT INTO `failed_logins` VALUES (1,1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36','2014-04-26 14:38:49');
/*!40000 ALTER TABLE `failed_logins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (1,2,1,NULL,9,1,90,1,NULL,NULL,'2014-04-26 14:40:22',NULL),(2,2,1,NULL,10,1,100,1,NULL,NULL,'2014-04-26 14:45:39',NULL),(3,2,1,NULL,9,1,90,1,NULL,NULL,'2014-04-26 19:17:49',NULL),(4,2,1,NULL,9,1,90,1,NULL,NULL,'2014-04-26 19:18:00',NULL),(5,2,1,NULL,11,1,12,1,NULL,NULL,'2014-04-28 21:24:18',NULL),(6,2,1,NULL,11,1,12,1,NULL,NULL,'2014-04-28 21:24:32',NULL);
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
INSERT INTO `item_users` VALUES (1,1,1,10,1,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `item_users` VALUES (2,2,1,20,1,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `item_users` VALUES (3,3,1,30,1,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `item_users` VALUES (4,4,1,40,1,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `item_users` VALUES (5,5,1,50,1,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `item_users` VALUES (6,6,1,60,1,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `item_users` VALUES (7,7,1,70,1,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `item_users` VALUES (8,8,1,80,1,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `item_users` VALUES (9,9,1,90,1,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `item_users` VALUES (10,10,1,100,1,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `item_users` VALUES (11,11,1,13,1,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `item_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (1,'Deposit 10HKT',10,1,NULL,'','',NULL,1,NULL,NULL,'2014-04-26 14:32:59',NULL,NULL),(2,'Deposit 20HKT',20,1,NULL,'','',NULL,1,NULL,NULL,'2014-04-26 14:33:12',NULL,NULL),(3,'Deposit 30HKT',30,1,NULL,'','',NULL,1,NULL,NULL,'2014-04-26 14:33:22',NULL,NULL),(4,'Deposit 40HKT',40,1,NULL,'','',NULL,1,NULL,NULL,'2014-04-26 14:33:34',NULL,NULL),(5,'Deposit 50HKT',50,1,NULL,'','',NULL,1,NULL,NULL,'2014-04-26 14:33:48',NULL,NULL),(6,'Deposit 60HKT',60,1,NULL,'','',NULL,1,NULL,NULL,'2014-04-26 14:33:59',NULL,NULL),(7,'Deposit 70HKT',70,1,NULL,'','',NULL,1,NULL,NULL,'2014-04-26 14:34:16',NULL,NULL),(8,'Depoist 80HKT',80,1,NULL,'','',NULL,1,NULL,NULL,'2014-04-26 14:34:28',NULL,NULL),(9,'Deposit 90HKT',90,1,NULL,'','',NULL,1,NULL,NULL,'2014-04-26 14:34:47',NULL,NULL),(10,'Deposit 100HKT',100,1,NULL,'','',NULL,1,NULL,NULL,'2014-04-26 14:34:58',NULL,NULL),(11,'Sua chua mit ',12,3,NULL,'Sua chua mit day du ','',NULL,2,NULL,NULL,'2014-04-27 20:48:13',NULL,NULL);
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
INSERT INTO `success_logins` VALUES (1,1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36','2014-04-26 14:30:37'),(2,1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36','2014-04-26 14:32:26'),(3,1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36','2014-04-26 14:38:56'),(4,2,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36','2014-04-26 14:39:21'),(5,2,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36','2014-04-27 17:57:00');
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
INSERT INTO `users` VALUES (1,'sadmin','$2a$08$mSgE0mwydCs.bXqfwcTBceNIw7FTGCbbVs5gM1sL6tV1AU8NyvZ4i','thangtd90@gmail.com',1000,40,'d11a6ddfeaefe62cd8cbbecaf1327df329e5fb90','2014-04-26 14:30:28','2014-04-26 14:35:21',NULL,NULL),(2,'admin','$2a$08$mSgE0mwydCs.bXqfwcTBceNIw7FTGCbbVs5gM1sL6tV1AU8NyvZ4i','test1@framgia.com',516,30,'374b9388587079661543ed12a4ae2789d476064b','2014-04-26 14:31:16','2014-04-28 21:24:32',NULL,NULL),(3,'test2','$2a$08$mSgE0mwydCs.bXqfwcTBceNIw7FTGCbbVs5gM1sL6tV1AU8NyvZ4i','test2@framgia.com',1000,10,'655b4cd11c0b09855f41fcf021dbcdc92468fa7c','2014-04-26 14:31:37',NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

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

-- Dump completed on 2014-04-30  8:23:15
