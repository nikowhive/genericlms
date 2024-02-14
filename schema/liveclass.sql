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
-- Table structure for table `liveclass`
--

DROP TABLE IF EXISTS `liveclass`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `liveclass` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(765) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `duration` int DEFAULT NULL,
  `classes_id` int DEFAULT NULL,
  `section_id` int DEFAULT NULL,
  `subject_id` int DEFAULT NULL,
  `usertype_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `creator_id` int DEFAULT NULL,
  `editor_id` int DEFAULT NULL,
  `school_year_id` int DEFAULT NULL,
  `teacher_join_url` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `join_url` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `password` varchar(765) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `metting_id` varchar(765) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `added_to_job` int(1) NOT NULL DEFAULT '0',
  `reminder` int(123) NOT NULL DEFAULT 5,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1256;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `liveclass`
--

LOCK TABLES `liveclass` WRITE;
/*!40000 ALTER TABLE `liveclass` DISABLE KEYS */;
/*!40000 ALTER TABLE `liveclass` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-04-20 14:49:56
