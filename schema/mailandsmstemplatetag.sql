-- MySQL dump 10.13  Distrib 8.0.21, for Linux (x86_64)
--
-- Host: localhost    Database: erp
-- ------------------------------------------------------
-- Server version	8.0.21-0ubuntu0.20.04.4

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `mailandsmstemplatetag`
--

DROP TABLE IF EXISTS `mailandsmstemplatetag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mailandsmstemplatetag` (
  `mailandsmstemplatetagID` int unsigned NOT NULL AUTO_INCREMENT,
  `usertypeID` int NOT NULL,
  `tagname` varchar(128) NOT NULL,
  `mailandsmstemplatetag_extra` varchar(255) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`mailandsmstemplatetagID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mailandsmstemplatetag`
--

LOCK TABLES `mailandsmstemplatetag` WRITE;
/*!40000 ALTER TABLE `mailandsmstemplatetag` DISABLE KEYS */;
INSERT INTO `mailandsmstemplatetag` VALUES (1,1,'[name]',NULL,'2016-12-10 08:51:33'),(2,1,'[dob]',NULL,'2016-12-10 08:52:31'),(3,1,'[gender]',NULL,'2016-12-10 08:52:31'),(4,1,'[religion]',NULL,'2016-12-10 08:54:51'),(5,1,'[email]',NULL,'2016-12-10 08:54:51'),(6,1,'[phone]',NULL,'2016-12-10 08:54:51'),(7,1,'[address]',NULL,'2016-12-10 08:54:51'),(8,1,'[jod]',NULL,'2016-12-10 08:54:51'),(9,1,'[username]',NULL,'2016-12-10 08:54:51'),(10,2,'[name]',NULL,'2016-12-10 08:55:50'),(11,2,'[designation]',NULL,'2016-12-10 08:58:27'),(12,2,'[dob]',NULL,'2016-12-10 09:01:21'),(13,2,'[gender]',NULL,'2016-12-10 09:01:21'),(14,2,'[religion]',NULL,'2016-12-10 09:01:21'),(15,2,'[email]',NULL,'2016-12-10 09:01:21'),(16,2,'[phone]',NULL,'2016-12-10 09:01:21'),(17,2,'[address]',NULL,'2016-12-10 09:01:21'),(18,2,'[jod]',NULL,'2016-12-10 09:01:21'),(19,2,'[username]',NULL,'2016-12-10 09:01:21'),(20,3,'[name]',NULL,'2016-12-10 09:02:09'),(21,3,'[dob]',NULL,'2016-12-10 09:10:54'),(22,3,'[gender]',NULL,'2016-12-10 09:10:54'),(23,3,'[blood_group]',NULL,'2016-12-10 09:10:54'),(24,3,'[religion]',NULL,'2016-12-10 09:10:54'),(25,3,'[email]',NULL,'2016-12-10 09:10:54'),(26,3,'[phone]',NULL,'2016-12-10 09:10:54'),(27,3,'[address]',NULL,'2016-12-10 09:10:54'),(28,3,'[state]',NULL,'2017-02-11 06:36:49'),(29,3,'[country]',NULL,'2017-02-11 06:36:27'),(30,3,'[class]',NULL,'2016-12-18 09:49:20'),(31,3,'[section]',NULL,'2016-12-10 09:10:54'),(32,3,'[group]',NULL,'2016-12-10 09:10:54'),(33,3,'[optional_subject]',NULL,'2016-12-10 09:10:54'),(34,3,'[register_no]',NULL,'2017-02-11 06:36:27'),(35,3,'[roll]',NULL,'2017-02-11 06:37:56'),(36,3,'[extra_curricular_activities]',NULL,'2017-02-11 06:37:56'),(37,3,'[remarks]',NULL,'2017-02-11 06:37:56'),(38,3,'[username]',NULL,'2016-12-10 09:10:54'),(39,3,'[result_table]',NULL,'2016-12-10 09:10:54'),(40,4,'[name]',NULL,'2016-12-10 09:12:31'),(41,4,'[father\'s_name]',NULL,'2016-12-10 09:19:19'),(42,4,'[mother\'s_name]',NULL,'2016-12-10 09:19:19'),(43,4,'[father\'s_profession]',NULL,'2016-12-10 09:19:19'),(44,4,'[mother\'s_profession]',NULL,'2016-12-10 09:19:19'),(45,4,'[email]',NULL,'2016-12-10 09:19:19'),(46,4,'[phone]',NULL,'2016-12-10 09:19:19'),(47,4,'[address]',NULL,'2016-12-10 09:19:19'),(48,4,'[username]',NULL,'2016-12-10 09:19:19'),(49,1,'[date]',NULL,'2018-05-10 22:27:12'),(50,2,'[date]',NULL,'2018-05-10 22:27:27'),(51,3,'[date]',NULL,'2018-05-10 22:27:36'),(52,4,'[date]',NULL,'2018-05-10 22:27:49');
/*!40000 ALTER TABLE `mailandsmstemplatetag` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-09-14 16:26:49
