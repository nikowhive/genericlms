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
-- Table structure for table `productpurchasepaid`
--

DROP TABLE IF EXISTS `productpurchasepaid`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productpurchasepaid` (
  `productpurchasepaidID` int NOT NULL AUTO_INCREMENT,
  `productpurchasepaidschoolyearID` int NOT NULL,
  `schoolyearID` int NOT NULL,
  `productpurchaseID` int NOT NULL,
  `productpurchasepaiddate` date NOT NULL,
  `productpurchasepaidreferenceno` varchar(100) NOT NULL,
  `productpurchasepaidamount` double NOT NULL,
  `productpurchasepaidpaymentmethod` int NOT NULL COMMENT '1 = cash, 2 = cheque, 3 = crediit card, 4 = other',
  `productpurchasepaidfile` varchar(200) DEFAULT NULL,
  `productpurchasepaidorginalname` varchar(200) DEFAULT NULL,
  `productpurchasepaiddescription` text,
  `create_date` datetime NOT NULL,
  `modify_date` datetime NOT NULL,
  `create_userID` int NOT NULL,
  `create_usertypeID` int NOT NULL,
  PRIMARY KEY (`productpurchasepaidID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productpurchasepaid`
--

LOCK TABLES `productpurchasepaid` WRITE;
/*!40000 ALTER TABLE `productpurchasepaid` DISABLE KEYS */;
/*!40000 ALTER TABLE `productpurchasepaid` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-09-14 16:26:58
