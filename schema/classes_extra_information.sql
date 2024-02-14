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
-- Table structure for table `classes_extra_information`
--

DROP TABLE IF EXISTS `classes_extra_information`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `classes_extra_information` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `classes_id` int NOT NULL,
  `description` text DEFAULT NULL,
  `student` varchar(200) DEFAULT NULL,
  `study_mode` varchar(200) DEFAULT NULL,
  `campus_location` varchar(200) DEFAULT NULL,
  `duration` varchar(200) DEFAULT NULL,
  `total_hours` varchar(200) DEFAULT NULL,
  `start_date` varchar(200) DEFAULT NULL,
  `fees` varchar(200) DEFAULT NULL,
  `tution_fees_onshore` varchar(200) DEFAULT NULL,
  `tution_fees_offshore_no_coe_visa` varchar(200) DEFAULT NULL,
  `domestic_vet` varchar(200) DEFAULT NULL,
  `discounted_fees` varchar(200) DEFAULT NULL,
  `material_fees` varchar(200) DEFAULT NULL,
  `enrollment_fees` varchar(200) DEFAULT NULL,
  `covid_scholarship` varchar(200) DEFAULT NULL,
  `cricos` varchar(200) DEFAULT NULL,
  `create_date` datetime NOT NULL,
  `modify_date` datetime NOT NULL,
  `create_userID` int NOT NULL,
  `create_usertypeID` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classes_extra_information`
--

LOCK TABLES `classes_extra_information` WRITE;
/*!40000 ALTER TABLE `classes_extra_information` DISABLE KEYS */;
/*!40000 ALTER TABLE `classes_extra_information` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-09-14 16:26:48
