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
-- Table structure for table `markpercentage`
--

DROP TABLE IF EXISTS `markpercentage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `markpercentage` (
  `markpercentageID` int NOT NULL AUTO_INCREMENT,
  `markpercentagetype` varchar(100) NOT NULL,
  `percentage` double NOT NULL,
  `examID` int DEFAULT NULL,
  `classesID` int DEFAULT NULL,
  `subjectID` int DEFAULT NULL,
  `create_date` datetime NOT NULL,
  `modify_date` datetime NOT NULL,
  `create_userID` int NOT NULL,
  `create_username` varchar(60) NOT NULL,
  `create_usertype` varchar(60) NOT NULL,
  PRIMARY KEY (`markpercentageID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `markpercentage`
--

LOCK TABLES `markpercentage` WRITE;
/*!40000 ALTER TABLE `markpercentage` DISABLE KEYS */;
INSERT INTO `markpercentage` VALUES (1,'Exam',70,NULL,NULL,NULL,'2017-01-05 06:11:54','2019-01-23 08:07:37',1,'admin','Admin'),(2,'Attendance',10,NULL,NULL,NULL,'2019-01-23 08:07:10','2019-01-23 08:07:10',1,'admin','Admin'),(3,'Class Test',10,NULL,NULL,NULL,'2019-01-23 08:07:20','2019-01-23 08:07:20',1,'admin','Admin'),(4,'Assignment',10,NULL,NULL,NULL,'2019-01-23 08:07:32','2019-01-23 08:07:32',1,'admin','Admin'),(5,'Practical',10,NULL,NULL,NULL,'2019-01-23 08:08:16','2019-01-23 08:08:16',1,'admin','Admin'),(6,'Quiz Test',10,NULL,NULL,NULL,'2019-01-23 08:08:40','2019-01-23 08:08:40',1,'admin','Admin'),(7,'Lab Report',10,NULL,NULL,NULL,'2019-01-23 08:08:49','2019-01-23 08:08:49',1,'admin','Admin');
/*!40000 ALTER TABLE `markpercentage` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-09-14 16:26:39
