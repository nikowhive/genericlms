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
-- Table structure for table `online_exam_user_answer_option`
--

DROP TABLE IF EXISTS `online_exam_user_answer_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `online_exam_user_answer_option` (
  `onlineExamUserAnswerOptionID` int NOT NULL AUTO_INCREMENT,
  `questionID` int DEFAULT NULL,
  `optionID` int DEFAULT NULL,
  `typeID` int DEFAULT NULL,
  `text` text,
  `correct_ans` varchar(255) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `ans_status` int DEFAULT NULL COMMENT '0=wrong,1=right',
  `obtained_mark` decimal(10,2) DEFAULT NULL,
  `full_mark` decimal(10,2) DEFAULT NULL,
  `onlineExamQuestionID` int DEFAULT NULL,
  `onlineExamUserAnswerID` int DEFAULT NULL,
  `subimg` varchar(255) DEFAULT NULL,
  `attend` int DEFAULT NULL COMMENT '0=yes;1=no',
  `examID` int DEFAULT NULL,
  PRIMARY KEY (`onlineExamUserAnswerOptionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `online_exam_user_answer_option`
--

LOCK TABLES `online_exam_user_answer_option` WRITE;
/*!40000 ALTER TABLE `online_exam_user_answer_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `online_exam_user_answer_option` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-09-14 16:26:41
