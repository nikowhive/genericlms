-- MySQL dump 10.13  Distrib 8.0.23, for Linux (x86_64)
--
-- Host: localhost    Database: erp
-- ------------------------------------------------------
-- Server version	5.7.33-0ubuntu0.18.04.1

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
-- Table structure for table `marksentries`
--

DROP TABLE IF EXISTS `marksentries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `marksentries` (
  `marksentriesID` int(11) NOT NULL AUTO_INCREMENT,
  `classesID` int(11) NOT NULL,
  `classesName` varchar(20) NOT NULL,
  `sectionID` int(11) NOT NULL,
  `sectionName` varchar(20) NOT NULL,
  `examID` int(11) NOT NULL,
  `examName` varchar(100) NOT NULL,
  `studentID` int(11) NOT NULL,
  `studentName` varchar(100) NOT NULL,
  `roll_no` varchar(20) NOT NULL,
  `subjectID` int(11) NOT NULL,
  `subjectName` varchar(100) NOT NULL,
  `schoolyearID` int(11) NOT NULL,
  `marks` varchar(10) NOT NULL,
  `grade` varchar(10) NOT NULL,
  `gpa` varchar(10) NOT NULL,
  PRIMARY KEY (`marksentriesID`),
  KEY `classesID` (`classesID`),
  KEY `sectionID` (`sectionID`),
  KEY `examID` (`examID`),
  KEY `studentID` (`studentID`),
  KEY `subjectID` (`subjectID`),
  KEY `schoolyearID` (`schoolyearID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-04-01 18:47:50
