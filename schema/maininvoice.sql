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
-- Table structure for table `maininvoice`
--

DROP TABLE IF EXISTS `maininvoice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `maininvoice` (
  `maininvoiceID` int unsigned NOT NULL AUTO_INCREMENT,
  `maininvoiceschoolyearID` int NOT NULL,
  `maininvoiceclassesID` int NOT NULL,
  `maininvoicestudentID` int NOT NULL,
  `maininvoiceuserID` int DEFAULT NULL,
  `maininvoiceusertypeID` int DEFAULT NULL,
  `maininvoiceuname` varchar(60) DEFAULT NULL,
  `maininvoicedate` date NOT NULL,
  `maininvoicecreate_date` date NOT NULL,
  `maininvoiceday` varchar(20) DEFAULT NULL,
  `maininvoicemonth` varchar(20) DEFAULT NULL,
  `maininvoiceyear` year NOT NULL,
  `maininvoicestatus` int DEFAULT NULL,
  `maininvoicedeleted_at` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`maininvoiceID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maininvoice`
--

LOCK TABLES `maininvoice` WRITE;
/*!40000 ALTER TABLE `maininvoice` DISABLE KEYS */;
/*!40000 ALTER TABLE `maininvoice` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-09-14 16:26:52
