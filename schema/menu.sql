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
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu` (
  `menuID` int NOT NULL AUTO_INCREMENT,
  `menuName` varchar(128) NOT NULL,
  `link` varchar(512) NOT NULL,
  `icon` varchar(128) DEFAULT NULL,
  `pullRight` text,
  `status` int NOT NULL DEFAULT '1',
  `parentID` int NOT NULL DEFAULT '0',
  `priority` int NOT NULL DEFAULT '1000',
  PRIMARY KEY (`menuID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` VALUES 
(1,'dashboard','dashboard','fa-laptop','',1,0,10000),
(2,'student','student','icon-student',NULL,1,0,9997),
(3,'parents','parents','fa-user',NULL,1,0,1001),
(4,'teacher','teacher','icon-teacher',NULL,1,0,1000),
(5,'user','user','fa-users',NULL,1,0,1000),
(6,'main_academic','#','icon-academicmain','',1,0,1006),
(7,'main_attendance','#','icon-attendance',NULL,1,0,9998),
(8,'main_exam','#','icon-exam',NULL,1,0,9995),
/*(9,'main_mark','#','icon-markmain',NULL,1,0,1000),*/
(10,'conversation','conversation','fa-envelope',NULL,1,0,9993),
(11,'media','media','fa-film',NULL,1,0,9996),
(12,'mailandsms','mailandsms','icon-mailandsms',NULL,1,0,1000),
(13,'main_library','#','icon-library','',1,0,1002),
(14,'main_transport','#','icon-bus','',1,0,350),
(15,'main_hostel','#','icon-hhostel','',1,0,320),
(16,'main_account','http://paccounts.eduwise.com.np','icon-account','',1,0,280),
(17,'main_announcement','#','icon-noticemain','',1,0,9992),
(18,'main_report','#','fa-clipboard','',1,0,9994),
(19,'visitorinfo','visitorinfo','icon-visitorinfo','',1,0,150),
(20,'main_administrator','#','icon-administrator','',1,0,140),
(21,'main_settings','#','fa-gavel','',1,0,30),
(22,'classes','classes','fa-sitemap',NULL,1,6,5000),
(23,'section','section','fa-star','',1,6,4500),
(24,'subject','subject','icon-subject','',1,6,4000),
(25,'routine','routine','icon-routine',NULL,1,6,1000),
(26,'syllabus','syllabus','icon-syllabus',NULL,1,6,3500),
(28,'sattendance','attendance/studentlist','icon-sattendance',NULL,1,7,1000),
(29,'tattendance','tattendance','icon-tattendance',NULL,1,7,1000),
(30,'exam','exam','fa-pencil',NULL,1,8,1111),
(31,'examschedule','examschedule','fa-puzzle-piece',NULL,1,8,1090),
(32,'grade','grade','fa-signal',NULL,1,8,1085),
(33,'eattendance','eattendance','icon-eattendance',NULL,1,8,1110),
(34,'mark','mark','fa-flask',NULL,1,8,1080),
(35,'markpercentage','markpercentage','icon-markpercentage',NULL,1,8,1075),
(36,'promotion','promotion','icon-promotion',NULL,1,8,999),
(37,'notice','notice','fa-calendar','',1,17,220),
(38,'event','event','fa-calendar-check-o','',1,17,210),
(39,'holiday','holiday','icon-holiday','',1,17,200),
(40,'classreport','classesreport','icon-classreport','',1,18,1000),
(41,'attendancereport','attendancereport','icon-attendancereport','',1,18,940),
(42,'studentreport','studentreport','icon-studentreport','',1,18,990),
(43,'schoolyear','schoolyear','fa fa-calendar-plus-o','',1,20,130),
(44,'mailandsmstemplate','mailandsmstemplate','icon-template','',1,20,100),
(46,'backup','backup','fa-download','',1,20,80),
(47,'systemadmin','systemadmin','icon-systemadmin','',1,20,120),
(48,'resetpassword','resetpassword','icon-reset_password','',1,20,110),
(49,'permission','permission','icon-permission','',1,20,60),
(50,'usertype','usertype','icon-role','',1,20,70),
(51,'setting','setting','fa-gears','',1,21,30),
(52,'paymentsettings','paymentsettings','icon-paymentsettings','',1,21,20),
(53,'smssettings','smssettings','fa-wrench','',1,21,10),
-- (54,'invoice','invoice','icon-invoice','',1,16,260),
-- (55,'paymenthistory','paymenthistory','icon-payment','',1,16,250),
(56,'transport','transport','icon-sbus','',1,14,340),
(57,'member','tmember','icon-member','',1,14,330),
(58,'hostel','hostel','icon-hostel','',1,15,310),
(59,'category','category','fa-leaf','',1,15,300),
(61,'member','hmember','icon-member','',1,15,290),
-- (62,'feetypes','feetypes','icon-feetypes','',1,16,270),
-- (63,'expense','expense','icon-expense','',1,16,240),
(64,'member','lmember','icon-member','',1,13,380),
(65,'books','book','icon-lbooks','',1,13,370),
(66,'issue','issue','icon-issue','',1,13,360),
(69,'import','bulkimport','fa-upload','',1,20,90),
(70,'update','update','fa-refresh','',1,20,50),
(71,'main_child','#','fa-child','',1,0,1002),
(72,'activitiescategory','activitiescategory','fa-pagelines','',1,71,420),
(73,'activities','activities','fa-fighter-jet','',1,71,410),
(74,'childcare','childcare','fa-wheelchair','',1,71,400),
(75,'uattendance','uattendance','fa-user-secret',NULL,1,7,1000),
(76,'studentgroup','studentgroup','fa-object-group','',1,20,129),
(77,'vendor','vendor','fa-rss','',1,96,1000),
(78,'location','location','fa-newspaper-o','',1,96,1000),
(79,'asset_category','asset_category','fa-life-ring','',1,96,1000),
(80,'asset','asset','fa-fax','',1,96,1000),
(81,'complain','complain','fa-commenting','',1,0,1003),
(82,'question_group','question_group','fa-question-circle','',1,88,1000),
(83,'question_level','question_level','fa-level-up','',1,88,1000),
(84,'question_bank','question_bank','fa-qrcode','',1,88,1000),
(85,'online_exam','online_exam','fa-slideshare','',1,8,1099),
(86,'instruction','instruction','fa-map-signs','',1,8,1000),
(87,'take_exam','take_exam','fa-user-secret','',1,8,1000),
(88,'Question','#','fa-graduation-cap','',1,0,1000),
(89,'certificatereport','certificatereport','fa-diamond','',1,18,860),
(90,'certificate_template','certificate_template','fa-certificate','',1,20,128),
(91,'main_payroll','#','fa-usd',NULL,1,0,1000),
(92,'salary_template','salary_template','fa-calculator','',1,91,1000),
(93,'hourly_template','hourly_template','fa fa-clock-o','',1,91,1000),
(94,'manage_salary','manage_salary','fa-beer','',1,91,1000),
(95,'make_payment','make_payment','fa-money',NULL,1,91,1000),
(96,'main_asset_management','#','fa-archive',NULL,1,0,1000),
(97,'asset_assignment','asset_assignment','fa-plug',NULL,1,96,1000),
(98,'purchase','purchase','fa-cart-plus',NULL,1,96,1000),
(99,'main_frontend','#','fa-home','',1,0,40),
(100,'pages','pages','fa-connectdevelop','',1,99,1000),
(101,'frontend_setting','frontend_setting','fa-asterisk','',1,21,25),
(102,'routinereport','routinereport','iniicon-routinereport','',1,18,960),
(103,'examschedulereport','examschedulereport','iniicon-examschedulereport','',1,18,950),
(104,'feesreport','feesreport','iniicon-feesreport','',1,18,850),
(105,'duefeesreport','duefeesreport','iniicon-duefeesreport','',1,18,840),
(106,'balancefeesreport','balancefeesreport','iniicon-balancefeesreport','',1,18,830),
(107,'transactionreport','transactionreport','iniicon-transactionreport','',1,18,820),
(108,'sociallink','sociallink','iniicon-sociallink','',1,20,109),
(109,'idcardreport','idcardreport','iniicon-idcardreport','',1,18,980),
(110,'admitcardreport','admitcardreport','iniicon-admitcardreport','',1,18,970),
(111,'studentfinereport','studentfinereport','iniicon-studentfinereport','',1,18,810),
(112,'attendanceoverviewreport','attendanceoverviewreport','iniicon-attendanceoverviewreport','',1,18,930),
-- (113,'income','income','iniicon-income','',1,16,239),
-- (114,'global_payment','global_payment','fa-balance-scale','',1,16,238),
(115,'terminalreport','terminalreport1','iniicon-terminalreport','',1,8,920),
(116,'tabulationsheetreport','tabulationsheetreport','iniicon-tabulationsheetreport','',1,18,900),
(117,'marksheetreport','marksheetreport','iniicon-marksheetreport','',1,18,890),
(118,'meritstagereport','meritstagereport','iniicon-meritstagereport','',1,18,910),
(119,'progresscardreport','progresscardreport','iniicon-progresscardreport','',1,18,880),
(120,'onlineexamreport','onlineexamreport','iniicon-onlineexamreport','',1,18,870),
(121,'main_inventory','#','iniicon-maininventory','',1,0,1000),
(122,'productcategory','productcategory','iniicon-productcategory','',1,121,1000),
(123,'product','product','iniicon-product','',1,121,1000),
(124,'productwarehouse','productwarehouse','iniicon-productwarehouse','',1,121,1000),
(125,'productsupplier','productsupplier','iniicon-productsupplier','',1,121,1000),
(126,'productpurchase','productpurchase','iniicon-productpurchase','',1,121,1000),
(127,'productsale','productsale','iniicon-productsale','',1,121,1000),
(128,'main_leaveapplication','#','iniicon-mainleaveapplication','',1,0,1000),
(129,'leavecategory','leavecategory','iniicon-leavecategory','',1,128,1000),
(130,'leaveassign','leaveassign','iniicon-leaveassign','',1,128,1000),
(131,'leaveapply','leaveapply','iniicon-leaveapply','',1,128,1000),
(132,'leaveapplication','leaveapplication','iniicon-leaveapplication','',1,128,1000),
(133,'librarybooksreport','librarybooksreport','iniicon-librarybooksreport','',1,18,925),
(134,'searchpaymentfeesreport','searchpaymentfeesreport','iniicon-searchpaymentfeesreport','',1,18,852),
(135,'salaryreport','salaryreport','iniicon-salaryreport','',1,18,805),
(136,'productpurchasereport','productpurchasereport','iniicon-productpurchasereport','',1,18,854),
(137,'productsalereport','productsalereport','iniicon-productsalereport','',1,18,853),
(138,'leaveapplicationreport','leaveapplicationreport','iniicon-leaveapplicationreport','',1,18,855),
(139,'posts','posts','fa-thumb-tack','',1,99,1005),
(140,'posts_categories','posts_categories','fa-anchor',NULL,1,99,1010),
(141,'menu','frontendmenu','iniicon-fmenu','',1,99,1000),
(142,'librarycardreport','librarycardreport','iniicon-librarycardreport','',1,18,924),
(143,'librarybookissuereport','librarybookissuereport','iniicon-librarybookissuereport','',1,18,923),
(144,'onlineexamquestionreport','onlineexamquestionreport','iniicon-onlineexamquestionreport','',1,18,865),
(145,'ebooks','ebooks','iniicon-ebook','',1,13,350),
(146,'accountledgerreport','accountledgerreport','iniicon-accountledgerreport','',1,18,800),
(147,'onlineadmission','onlineadmission','iniicon-onlineadmission','',1,0,160),
(148,'emailsetting','emailsetting','iniicon-ini-emailsetting','',1,21,5),
(149,'onlineadmissionreport','onlineadmissionreport','iniicon-onlineadmissionreport','',1,18,863),
(150,'marksetting','marksetting1','fa-futbol-o','',1,8,1070),
(151,'studentsessionreport','studentsessionreport','fa-recycle','',1,18,876),
(152,'unit','unit','icon-subject','',1,6,3999),
(153,'chapter','chapter','icon-subject','',1,6,3998),
(154,'exam_setting','exam_setting','fa-slideshare','',1,8,1065),
(155,'courses','courses','icon-library','',1,0,9999),
(156,'coursesreport','coursesreport','icon-studentreport','',1,18,989),
(157,'attendancereport','attendancereport','icon-attendancereport','',1,7,940),
(158,'terminalreport','terminalreport','iniicon-terminalreport','',1,18,880),
(159,'Student Result','studentresult','fa-flask',NULL,1,8,1000),
(160,'kindergarten','kindergarten','fa-flask',NULL,1,8,1080),
(162,'kindergartenreport','terminalreport2','iniicon-terminalreport','',1,8,920),
(163,'feed','feed','fa-rss','',1,0,10001),
(164,'studentattendance','studentattendance','icon-attendancereport','',1,7,940),
(165,'studentremark','studentremark','iniicon-terminalreport','',1,8,920),
(166,'studentattendancebyexam','studentattendancebyexam','icon-attendancereport','',1,7,940),
(167,'importreport','terminalreport/importreport','iniicon-terminalreport','',1,8,920),
(168,'studentsubjectview','graphicalreport/studentsubjectview','iniicon-terminalreport','',1,8,920),
(169,'studentlineview','graphicalreport/studentlineview','iniicon-terminalreport','',1,8,920),
(170,'studentclassview','graphicalreport/studentclassview','iniicon-terminalreport','',1,8,920),
(171,'schoolinformation','schoolinformation','fa-gears','',1,21,30),
(172,'bulk_mark','bulk_mark','fa-flask',NULL,1,8,1080),
(173,'examtermsetting','examtermsetting','fa-slideshare','',1,8,1065),
(174,'finalterminalreport','finalterminalreport','fa-slideshare','',1,8,1065),
(175,'main_zoom_meeting','#','fa-video-camera',NULL,1,0,1000),
(176,'liveclass','liveclass','fa-video-camera',NULL,1,175,990),
(177,'zoomsettings','zoomsettings','fa-camera-retro',NULL,1,175,3),
(178,'view_optional_subject','student/view_optional_subject','fa-sitemap',NULL,1,6,5000);
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-04-20 14:49:52
