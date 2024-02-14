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
-- Table structure for table `themes`
--

DROP TABLE IF EXISTS `themes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `themes` (
  `themesID` int NOT NULL AUTO_INCREMENT,
  `sortID` int NOT NULL DEFAULT '1',
  `themename` varchar(128) NOT NULL,
  `backend` int NOT NULL DEFAULT '1',
  `frontend` int NOT NULL DEFAULT '1',
  `topcolor` text NOT NULL,
  `leftcolor` text NOT NULL,
  PRIMARY KEY (`themesID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `themes`
--

LOCK TABLES `themes` WRITE;
/*!40000 ALTER TABLE `themes` DISABLE KEYS */;
INSERT INTO `themes` VALUES (1,1,'Default',1,1,'#FFFFFF','#2d353c'),(2,0,'Blue',0,1,'#3c8dbc','#2d353c'),(3,3,'Black',1,1,'#fefefe','#222222'),(4,4,'Purple',1,1,'#605ca8','#2d353c'),(5,5,'Green',1,1,'#00a65a','#2d353c'),(6,6,'Red',1,1,'#dd4b39','#2d353c'),(7,0,'Yellow',0,1,'#f39c12','#2d353c'),(8,7,'Blue Light',1,1,'#3c8dbc','#f9fafc'),(9,8,'Black Light',1,1,'#fefefe','#f9fafc'),(10,9,'Purple Light',1,1,'#605ca8','#f9fafc'),(11,10,'Green Light',1,1,'#00a65a','#f9fafc'),(12,11,'Red Light',1,1,'#dd4b39','#f9fafc'),(13,12,'Yellow Light',1,1,'#f39c12','#f9fafc'),(14,2,'White Blue',1,1,'#ffffff','#132035'),(15,2,'Green Sidebar',1,1,'#FFFFFF','#00a65a'),(16,2,'Purple Sidebar',1,1,'#FFFFFF','#605ca8'),(17,2,'Red Sidebar',1,1,'#FFFFFF','#dd4b39'), (18,2,'Yellow Sidebar',1,1,'#FFFFFF','#f39c12');
/*!40000 ALTER TABLE `themes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-09-14 16:26:24
