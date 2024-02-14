-- MySQL dump 10.13  Distrib 8.0.23, for Linux (x86_64)
--
-- Host: localhost    Database: erp
-- ------------------------------------------------------
-- Server version	8.0.23-0ubuntu0.20.04.1

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
-- Table structure for table `update`
--

DROP TABLE IF EXISTS `update`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `update` (
  `updateID` int NOT NULL AUTO_INCREMENT,
  `version` varchar(100) NOT NULL,
  `date` datetime NOT NULL,
  `userID` int NOT NULL,
  `usertypeID` int NOT NULL,
  `log` longtext NOT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`updateID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `update`
--

LOCK TABLES `update` WRITE;
/*!40000 ALTER TABLE `update` DISABLE KEYS */;
INSERT INTO `update` VALUES (1,'4.5','2020-09-14 11:46:07',1,1,'<h4>1. initial install</h4>',1),(9,'4.6','2021-03-12 12:52:23',1,1,'<h4>+ [Feature] Addons feature </h4>\n<h4>+ [Feature] Overtime feature </h4>\n<h4>+ [Feature] Overtime report feature </h4>\n<h4>+ [Update] Online admission feature update </h4>\n<h4>+ [Update] Routine feature update </h4>\n<h4>+ [Update] Mail & SMS feature update </h4>\n<h4>+ [Update] Activities feature update </h4>\n<h4>+ [Fix] Online exam bug fixing </h4>',1),(10,'4.7','2021-03-12 12:52:47',1,1,'<h4>+ [Feature] Candidate feature </h4>\n<h4>+ [Feature] Sponsor feature </h4>\n<h4>+ [Feature] Sponsorship feature </h4>\n<h4>+ [Feature] Sponsorship report feature </h4>\n<h4>+ [Feature] Online exam question answer report feature </h4>\n<h4>+ [Update] Stripe gateway update </h4>\n<h4>+ [Fixing] Online exam feature update & minor bug fix </h4>\n<h4>+ [Fixing] Message feature minor bug fix </h4>\n<h4>+ [Fixing] Promotion feature minor bug fix </h4>\n<h4>+ [Fixing] Addon feature minor bug fix </h4>',1),(11,'4.8','2021-03-12 12:55:27',1,1,'<h4>+ [Fixing] Candidate feature bug solve </h4>\n<h4>+ [Fixing] Routine feature bug solve </h4>\n<h4>+ [Fixing] Sale feature minor bug solve </h4>\n<h4>+ [Fixing] Child Care feature minor bug solve </h4>\n<h4>+ [Fixing] Section feature minor bug solve </h4>\n<h4>+ [Fixing] mark feaure minor bug solve </h4>',1);
/*!40000 ALTER TABLE `update` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-04-20 14:50:17
