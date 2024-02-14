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
-- Table structure for table `feetypes`
--

DROP TABLE IF EXISTS `feetypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `feetypes` (
  `feetypesID` int unsigned NOT NULL AUTO_INCREMENT,
  `feetypes` varchar(60) NOT NULL,
  `note` text,
  PRIMARY KEY (`feetypesID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feetypes`
--

LOCK TABLES `feetypes` WRITE;
/*!40000 ALTER TABLE `feetypes` DISABLE KEYS */;
INSERT INTO `feetypes` VALUES (1,'Books Fine','Don\'t delete it!'),(2,'Library Fee','Don\'t delete it!'),(3,'Transport Fee','Don\'t delete it!'),(4,'Hostel Fee','Don\'t delete it!'),(5,'Tuition Fee [Jan]',''),(6,'Tuition Fee [Feb]',''),(7,'Tuition Fee [Mar]',''),(8,'Tuition Fee [Apr]',''),(9,'Tuition Fee [May]',''),(10,'Tuition Fee [Jun]',''),(11,'Tuition Fee [Jul]',''),(12,'Tuition Fee [Aug]',''),(13,'Tuition Fee [Sep]',''),(14,'Tuition Fee [Oct]',''),(15,'Tuition Fee [Nov]',''),(16,'Tuition Fee [Dec]','');
/*!40000 ALTER TABLE `feetypes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-09-14 16:26:32
