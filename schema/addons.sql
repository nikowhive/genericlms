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
-- Table structure for table `addons`
--

DROP TABLE IF EXISTS `addons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `addons` (
  `addonsID` int unsigned NOT NULL AUTO_INCREMENT,
  `package_name` varchar(180) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `slug` varchar(180) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `description` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `version` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `author` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `init` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `files` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `preview_image` varchar(180) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `date` datetime NOT NULL,
  `userID` int NOT NULL,
  `usertypeID` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`addonsID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addons`
--

LOCK TABLES `addons` WRITE;
/*!40000 ALTER TABLE `addons` DISABLE KEYS */;
INSERT INTO `addons` VALUES (1,'Zoom Live Class','zoom-addon','Zoom live class is a great addon for online class. The student and teacher can easily manage online class.','V1.1','inilabs','zoom-addon/src/Init.php','{\"0\":\"mvc\\/controllers\\/Liveclass.php\",\"1\":\"mvc\\/controllers\\/Zoomsettings.php\",\"2\":\"mvc\\/models\\/Liveclass_m.php\",\"3\":\"mvc\\/models\\/Zoomsettings_m.php\",\"4\":\"mvc\\/views\\/liveclass\",\"5\":\"mvc\\/views\\/zoomsettings\",\"6\":\"assets\\/liveclass\",\"7\":\"assets\\/settings\",\"8\":\"mvc\\/libraries\\/Zoom.php\",\"9\":\"mvc\\/language\\/arabic\\/liveclass_lang.php\",\"10\":\"mvc\\/language\\/arabic\\/zoomsettings_lang.php\",\"11\":\"mvc\\/language\\/bengali\\/liveclass_lang.php\",\"12\":\"mvc\\/language\\/bengali\\/zoomsettings_lang.php\",\"13\":\"mvc\\/language\\/chinese\\/liveclass_lang.php\",\"14\":\"mvc\\/language\\/chinese\\/zoomsettings_lang.php\",\"15\":\"mvc\\/language\\/english\\/liveclass_lang.php\",\"16\":\"mvc\\/language\\/english\\/zoomsettings_lang.php\",\"17\":\"mvc\\/language\\/french\\/liveclass_lang.php\",\"18\":\"mvc\\/language\\/french\\/zoomsettings_lang.php\",\"19\":\"mvc\\/language\\/german\\/liveclass_lang.php\",\"20\":\"mvc\\/language\\/german\\/zoomsettings_lang.php\",\"21\":\"mvc\\/language\\/hindi\\/liveclass_lang.php\",\"22\":\"mvc\\/language\\/hindi\\/zoomsettings_lang.php\",\"23\":\"mvc\\/language\\/indonesian\\/liveclass_lang.php\",\"24\":\"mvc\\/language\\/indonesian\\/zoomsettings_lang.php\",\"25\":\"mvc\\/language\\/italian\\/liveclass_lang.php\",\"26\":\"mvc\\/language\\/italian\\/zoomsettings_lang.php\",\"27\":\"mvc\\/language\\/portuguese\\/liveclass_lang.php\",\"28\":\"mvc\\/language\\/portuguese\\/zoomsettings_lang.php\",\"29\":\"mvc\\/language\\/romanian\\/liveclass_lang.php\",\"30\":\"mvc\\/language\\/romanian\\/zoomsettings_lang.php\",\"31\":\"mvc\\/language\\/russian\\/liveclass_lang.php\",\"32\":\"mvc\\/language\\/russian\\/zoomsettings_lang.php\",\"33\":\"mvc\\/language\\/spanish\\/liveclass_lang.php\",\"34\":\"mvc\\/language\\/spanish\\/zoomsettings_lang.php\",\"35\":\"mvc\\/language\\/thai\\/liveclass_lang.php\",\"36\":\"mvc\\/language\\/thai\\/zoomsettings_lang.php\",\"37\":\"mvc\\/language\\/turkish\\/liveclass_lang.php\",\"38\":\"mvc\\/language\\/turkish\\/zoomsettings_lang.php\"}','zoom-live-class.png','2021-04-20 14:41:21',1,'1',1);
/*!40000 ALTER TABLE `addons` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-04-20 14:50:22
