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
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `permissionID` int unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'In most cases, this should be the name of the module (e.g. news)',
  `active` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`permissionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES
  (501,'Dashboard','dashboard','yes'),
  (502,'Student','student','yes'),
  (503,'Student Add','student_add','yes'),
  (504,'Student Edit','student_edit','yes'),
  (505,'Student Delete','student_delete','yes'),
  (506,'Student View','student_view','yes'),
  (507,'Parents','parents','yes'),
  (508,'Parents Add','parents_add','yes'),
  (509,'Parents Edit','parents_edit','yes'),
  (510,'Parents Delete','parents_delete','yes'),
  (511,'Parents View','parents_view','yes'),
  (512,'Teacher','teacher','yes'),
  (513,'Teacher Add','teacher_add','yes'),
  (514,'Teacher Edit','teacher_edit','yes'),
  (515,'Teacher Delete','teacher_delete','yes'),
  (516,'Teacher View','teacher_view','yes'),
  (517,'User','user','yes'),
  (518,'User Add','user_add','yes'),
  (519,'User Edit','user_edit','yes'),
  (520,'User Delete','user_delete','yes'),
  (521,'User View','user_view','yes'),
  (522,'Class','classes','yes'),
  (523,'Class Add','classes_add','yes'),
  (524,'Class Edit','classes_edit','yes'),
  (525,'Class Delete','classes_delete','yes'),
  (526,'Section','section','yes'),
  (527,'Section Add','section_add','yes'),
  (528,'Section Edit','section_edit','yes'),
  (529,'Semester Delete','semester_delete','yes'),
  (530,'Section Delete','section_delete','yes'),
  (531,'Subject','subject','yes'),
  (532,'Subject Add','subject_add','yes'),
  (533,'Subject Edit','subject_edit','yes'),
  (534,'Subject Delete','subject_delete','yes'),
  (535,'Syllabus','syllabus','yes'),
  (536,'Syllabus Add','syllabus_add','yes'),
  (537,'Syllabus Edit','syllabus_edit','yes'),
  (538,'Syllabus Delete','syllabus_delete','yes'),
  (539,'Assignment','assignment','yes'),
  (540,'Assignment Add','assignment_add','yes'),
  (541,'Assignment Edit','assignment_edit','yes'),
  (542,'Assignment Delete','assignment_delete','yes'),
  (543,'Assignment View','assignment_view','yes'),
  (544,'Routine','routine','yes'),
  (545,'Routine Add','routine_add','yes'),
  (546,'Routine Edit','routine_edit','yes'),
  (547,'Routine Delete','routine_delete','yes'),
  (548,'Old Student Attendance','sattendance','yes'),
  (549,'Student Attendance Add','sattendance_add','yes'),
  (550,'Student Attendance View','sattendance_view','yes'),
  (551,'Teacher Attendance','tattendance','yes'),
  (552,'Teacher Attendance Add','tattendance_add','yes'),
  (553,'Teacher Attendance View','tattendance_view','yes'),
  (554,'User Attendance','uattendance','yes'),
  (555,'User Attendance Add','uattendance_add','yes'),
  (556,'User Attendance View','uattendance_view','yes'),
  (557,'Exam','exam','yes'),
  (558,'Exam Add','exam_add','yes'),
  (559,'Exam Edit','exam_edit','yes'),
  (560,'Exam Delete','exam_delete','yes'),
  (561,'Examschedule','examschedule','yes'),
  (562,'Examschedule Add','examschedule_add','yes'),
  (563,'Examschedule Edit','examschedule_edit','yes'),
  (564,'Examschedule Delete','examschedule_delete','yes'),
  (565,'Grade','grade','yes'),
  (566,'Grade Add','grade_add','yes'),
  (567,'Grade Edit','grade_edit','yes'),
  (568,'Grade Delete','grade_delete','yes'),
  (569,'Exam Attendance','eattendance','yes'),
  (570,'Exam Attendance Add','eattendance_add','yes'),
  (571,'Mark','mark','yes'),
  (572,'Mark Add','mark_add','yes'),
  (573,'Mark View','mark_view','yes'),
  (574,'Mark Distribution','markpercentage','yes'),
  (575,'Mark Distribution Add','markpercentage_add','yes'),
  (576,'Mark Distribution Edit','markpercentage_edit','yes'),
  (577,'Mark Distribution Delete','markpercentage_delete','yes'),
  (578,'Promotion','promotion','yes'),
  (579,'Message','conversation','yes'),
  (580,'Media','media','yes'),
  (581,'Media Add','media_add','yes'),
  (582,'Media Delete','media_delete','yes'),
  (583,'Mail / SMS','mailandsms','yes'),
  (584,'Mail / SMS Add','mailandsms_add','yes'),
  (585,'Mail / SMS View','mailandsms_view','yes'),
  (586,'Question Group','question_group','yes'),
  (587,'Question Group Add','question_group_add','yes'),
  (588,'Question Group Edit','question_group_edit','yes'),
  (589,'Question Group Delete','question_group_delete','yes'),
  (590,'Question Level','question_level','yes'),
  (591,'Question Level Add','question_level_add','yes'),
  (592,'Question Level Edit','question_level_edit','yes'),
  (593,'Question Level Delete','question_level_delete','yes'),
  (594,'Question Bank','question_bank','yes'),
  (595,'Question Bank Add','question_bank_add','yes'),
  (596,'Question Bank Edit','question_bank_edit','yes'),
  (597,'Question Bank Delete','question_bank_delete','yes'),
  (598,'Question Bank View','question_bank_view','yes'),
  (599,'Online Exam','online_exam','yes'),
  (600,'Online Exam Add','online_exam_add','yes'),
  (601,'Online Exam Edit','online_exam_edit','yes'),
  (602,'Online Exam Delete','online_exam_delete','yes'),
  (603,'Instruction','instruction','yes'),
  (604,'Instruction Add','instruction_add','yes'),
  (605,'Instruction Edit','instruction_edit','yes'),
  (606,'Instruction Delete','instruction_delete','yes'),
  (607,'Instruction View','instruction_view','yes'),
  (608,'Salary Template','salary_template','yes'),
  (609,'Salary Template Add','salary_template_add','yes'),
  (610,'Salary Template Edit','salary_template_edit','yes'),
  (611,'Salary Template Delete','salary_template_delete','yes'),
  (612,'Salary Template View','salary_template_view','yes'),
  (613,'Hourly Template','hourly_template','yes'),
  (614,'Hourly Template Add','hourly_template_add','yes'),
  (615,'Hourly Template Edit','hourly_template_edit','yes'),
  (616,'Hourly Template Delete','hourly_template_delete','yes'),
  (617,'Manage Salary','manage_salary','yes'),
  (618,'Manage Salary Add','manage_salary_add','yes'),
  (619,'Manage Salary Edit','manage_salary_edit','yes'),
  (620,'Manage Salary Delete','manage_salary_delete','yes'),
  (621,'Manage Salary View','manage_salary_view','yes'),
  (622,'Make Payment','make_payment','yes'),
  (623,'Vendor','vendor','yes'),
  (624,'Vendor Add','vendor_add','yes'),
  (625,'Vendor Edit','vendor_edit','yes'),
  (626,'Vendor Delete','vendor_delete','yes'),
  (627,'Location','location','yes'),
  (628,'Location Add','location_add','yes'),
  (629,'Location Edit','location_edit','yes'),
  (630,'Location Delete','location_delete','yes'),
  (631,'Asset Category','asset_category','yes'),
  (632,'Asset Category Add','asset_category_add','yes'),
  (633,'Asset Category Edit','asset_category_edit','yes'),
  (634,'Asset Category Delete','asset_category_delete','yes'),
  (635,'Asset','asset','yes'),
  (636,'Asset Add','asset_add','yes'),
  (637,'Asset Edit','asset_edit','yes'),
  (638,'Asset Delete','asset_delete','yes'),
  (639,'Asset View','asset_view','yes'),
  (640,'Asset Assignment','asset_assignment','yes'),
  (641,'Asset Assignment Add','asset_assignment_add','yes'),
  (642,'Asset Assignment Edit','asset_assignment_edit','yes'),
  (643,'Asset Assignment Delete','asset_assignment_delete','yes'),
  (644,'Asset Assignment View','asset_assignment_view','yes'),
  (645,'Purchase','purchase','yes'),
  (646,'Purchase Add','purchase_add','yes'),
  (647,'Purchase Edit','purchase_edit','yes'),
  (648,'Purchase Delete','purchase_delete','yes'),
  (649,'Product Category','productcategory','yes'),
  (650,'Product Category Add','productcategory_add','yes'),
  (651,'Product Category Edit','productcategory_edit','yes'),
  (652,'Product Category Delete','productcategory_delete','yes'),
  (653,'Product','product','yes'),
  (654,'Product Add','product_add','yes'),
  (655,'Product Edit','product_edit','yes'),
  (656,'Product Delete','product_delete','yes'),
  (657,'Warehouse','productwarehouse','yes'),
  (658,'Warehouse Add','productwarehouse_add','yes'),
  (659,'Warehouse Edit','productwarehouse_edit','yes'),
  (660,'Warehouse Delete','productwarehouse_delete','yes'),
  (661,'Supplier','productsupplier','yes'),
  (662,'Supplier Add','productsupplier_add','yes'),
  (663,'Supplier Edit','productsupplier_edit','yes'),
  (664,'Supplier Delete','productsupplier_delete','yes'),
  (665,'Purchase','productpurchase','yes'),
  (666,'Purchase Add','productpurchase_add','yes'),
  (667,'Purchase Edit','productpurchase_edit','yes'),
  (668,'Purchase Delete','productpurchase_delete','yes'),
  (669,'Purchase View','productpurchase_view','yes'),
  (670,'Sale','productsale','yes'),
  (671,'Sale Add','productsale_add','yes'),
  (672,'Sale Edit','productsale_edit','yes'),
  (673,'Sale Delete','productsale_delete','yes'),
  (674,'Sale View','productsale_view','yes'),
  (675,'Leave Category','leavecategory','yes'),
  (676,'Leave Category Add','leavecategory_add','yes'),
  (677,'Leave Category Edit','leavecategory_edit','yes'),
  (678,'Leave Category Delete','leavecategory_delete','yes'),
  (679,'Leave Assign','leaveassign','yes'),
  (680,'Leave Assign Add','leaveassign_add','yes'),
  (681,'Leave Assign Edit','leaveassign_edit','yes'),
  (682,'Leave Assign Delete','leaveassign_delete','yes'),
  (683,'Leave Apply','leaveapply','yes'),
  (684,'Leave Apply Add','leaveapply_add','yes'),
  (685,'Leave Apply Edit','leaveapply_edit','yes'),
  (686,'Leave Apply Delete','leaveapply_delete','yes'),
  (687,'Leave Apply View','leaveapply_view','yes'),
  (688,'Leave Application','leaveapplication','yes'),
  (689,'Activities Category','activitiescategory','yes'),
  (690,'Activities Category Add','activitiescategory_add','yes'),
  (691,'Activities Category Edit','activitiescategory_edit','yes'),
  (692,'Activities Category Delete','activitiescategory_delete','yes'),
  (693,'Activities','activities','yes'),
  (694,'Activities Add','activities_add','yes'),
  (695,'Activities Delete','activities_delete','yes'),
  (696,'Child Care','childcare','yes'),
  (697,'Child Care Add','childcare_add','yes'),
  (698,'Child Care Edit','childcare_edit','yes'),
  (699,'Child Care Delete','childcare_delete','yes'),
  (700,'Library Member','lmember','yes'),
  (701,'Library Member Add','lmember_add','yes'),
  (702,'Library Member Edit','lmember_edit','yes'),
  (703,'Library Member Delete','lmember_delete','yes'),
  (704,'Library Member View','lmember_view','yes'),
  (705,'Books','book','yes'),
  (706,'Books Add','book_add','yes'),
  (707,'Books Edit','book_edit','yes'),
  (708,'Books Delete','book_delete','yes'),
  (709,'Issue Book','issue','yes'),
  (710,'Issue Book Add','issue_add','yes'),
  (711,'Issue Book Edit','issue_edit','yes'),
  (712,'Issue Book View','issue_view','yes'),
  (713,'E-Books','ebooks','yes'),
  (714,'E-Books Add','ebooks_add','yes'),
  (715,'E-Books Edit','ebooks_edit','yes'),
  (716,'E-Books Delete','ebooks_delete','yes'),
  (717,'E-Books View','ebooks_view','yes'),
  (718,'Transport','transport','yes'),
  (719,'Transport Add','transport_add','yes'),
  (720,'Transport Edit','transport_edit','yes'),
  (721,'Transport Delete','transport_delete','yes'),
  (722,'Transport Member','tmember','yes'),
  (723,'Transport Member Add','tmember_add','yes'),
  (724,'Transport Member Edit','tmember_edit','yes'),
  (725,'Transport Member Delete','tmember_delete','yes'),
  (726,'Transport Member View','tmember_view','yes'),
  (727,'Hostel','hostel','yes'),
  (728,'Hostel Add','hostel_add','yes'),
  (729,'Hostel Edit','hostel_edit','yes'),
  (730,'Hostel Delete','hostel_delete','yes'),
  (731,'Hostel Category','category','yes'),
  (732,'Hostel Category Add','category_add','yes'),
  (733,'Hostel Category Edit','category_edit','yes'),
  (734,'Hostel Category Delete','category_delete','yes'),
  (735,'Hostel Member','hmember','yes'),
  (736,'Hostel Member Add','hmember_add','yes'),
  (737,'Hostel Member Edit','hmember_edit','yes'),
  (738,'Hostel Member Delete','hmember_delete','yes'),
  (739,'Hostel Member View','hmember_view','yes'),
  (740,'Fee Types','feetypes','yes'),
  (741,'Fee Types Add','feetypes_add','yes'),
  (742,'Fee Types Edit','feetypes_edit','yes'),
  (743,'Fee Types Delete','feetypes_delete','yes'),
  (744,'Invoice','invoice','yes'),
  (745,'Invoice Add','invoice_add','yes'),
  (746,'Invoice Edit','invoice_edit','yes'),
  (747,'Invoice Delete','invoice_delete','yes'),
  (748,'Invoice View','invoice_view','yes'),
  (749,'Payment History','paymenthistory','yes'),
  (750,'Payment History Edit','paymenthistory_edit','yes'),
  (751,'Payment History Delete','paymenthistory_delete','yes'),
  (752,'Expense','expense','yes'),
  (753,'Expense Add','expense_add','yes'),
  (754,'Expense Edit','expense_edit','yes'),
  (755,'Expense Delete','expense_delete','yes'),
  (756,'Income','income','yes'),
  (757,'Income Add','income_add','yes'),
  (758,'Income Edit','income_edit','yes'),
  (759,'Income Delete','income_delete','yes'),
  (760,'Global Payment','global_payment','yes'),
  (761,'Notice','notice','yes'),
  (762,'Notice Add','notice_add','yes'),
  (763,'Notice Edit','notice_edit','yes'),
  (764,'Notice Delete','notice_delete','yes'),
  (765,'Notice View','notice_view','yes'),
  (766,'Event','event','yes'),
  (767,'Event Add','event_add','yes'),
  (768,'Event Edit','event_edit','yes'),
  (769,'Event Delete','event_delete','yes'),
  (770,'Event View','event_view','yes'),
  (771,'Holiday','holiday','yes'),
  (772,'Holiday Add','holiday_add','yes'),
  (773,'Holiday Edit','holiday_edit','yes'),
  (774,'Holiday Delete','holiday_delete','yes'),
  (775,'Holiday View','holiday_view','yes'),
  (776,'Classes Report','classesreport','yes'),
  (777,'Student Report','studentreport','yes'),
  (778,'ID Card Report','idcardreport','yes'),
  (779,'Admit Card Report','admitcardreport','yes'),
  (780,'Routine Report','routinereport','yes'),
  (781,'Exam Schedule Report','examschedulereport','yes'),
  (782,'Attendance Report','attendancereport','yes'),
  (783,'Attendance Overview Report','attendanceoverviewreport','yes'),
  (784,'Library Books Report','librarybooksreport','yes'),
  (785,'Library Card Report','librarycardreport','yes'),
  (786,'Library Book Issue Report','librarybookissuereport','yes'),
  (787,'Terminal Report','terminalreport','yes'),
  (788,'Merit Stage Report','meritstagereport','yes'),
  (789,'Tabulation Sheet Report','tabulationsheetreport','yes'),
  (790,'Mark Sheet Report','marksheetreport','yes'),
  (791,'Progress Card Report','progresscardreport','yes'),
  (792,'Student Session Report','studentsessionreport','yes'),
  (793,'Online Exam Report','onlineexamreport','yes'),
  (794,'Online Exam Question Report','onlineexamquestionreport','yes'),
  (795,'Online Admission Report','onlineadmissionreport','yes'),
  (796,'Certificate Report','certificatereport','yes'),
  (797,'Leave Application Report','leaveapplicationreport','yes'),
  (798,'Product Purchase Report','productpurchasereport','yes'),
  (799,'Product Sale Report','productsalereport','yes'),
  (800,'Search Payment Fees Report','searchpaymentfeesreport','yes'),
  (801,'Fees Report','feesreport','yes'),
  (802,'Due Fees Report','duefeesreport','yes'),
  (803,'Balance Fees Report','balancefeesreport','yes'),
  (804,'Transaction','transactionreport','yes'),
  (805,'Student Fine Report','studentfinereport','yes'),
  (806,'Salary Report','salaryreport','yes'),
  (807,'Account Ledger Report','accountledgerreport','yes'),
  (808,'Online Admission','onlineadmission','yes'),
  (809,'Visitor Information','visitorinfo','yes'),
  (810,'Visitor Information Delete','visitorinfo_delete','yes'),
  (811,'Visitor Infomation View','visitorinfo_view','yes'),
  (812,'Academic Year','schoolyear','yes'),
  (813,'Academic Year Add','schoolyear_add','yes'),
  (814,'Academic Year Edit','schoolyear_edit','yes'),
  (815,'Academic Year Delete','schoolyear_delete','yes'),
  (816,'Student Group','studentgroup','yes'),
  (817,'Student Group Add','studentgroup_add','yes'),
  (818,'Student Group Edit','studentgroup_edit','yes'),
  (819,'Student Group Delete','studentgroup_delete','yes'),
  (820,'Complain','complain','yes'),
  (821,'Complain Add','complain_add','yes'),
  (822,'Complain Edit','complain_edit','yes'),
  (823,'Complain Delete','complain_delete','yes'),
  (824,'Complain View','complain_view','yes'),
  (825,'Certificate Template','certificate_template','yes'),
  (826,'Certificate Template Add','certificate_template_add','yes'),
  (827,'Certificate Template Edit','certificate_template_edit','yes'),
  (828,'Certificate Template Delete','certificate_template_delete','yes'),
  (829,'Certificate Template View','certificate_template_view','yes'),
  (830,'System Admin','systemadmin','yes'),
  (831,'System Admin Add','systemadmin_add','yes'),
  (832,'System Admin Edit','systemadmin_edit','yes'),
  (833,'System Admin Delete','systemadmin_delete','yes'),
  (834,'System Admin View','systemadmin_view','yes'),
  (835,'Reset Password','resetpassword','yes'),
  (836,'Social Link','sociallink','yes'),
  (837,'Social Link Add','sociallink_add','yes'),
  (838,'Social Link Edit','sociallink_edit','yes'),
  (839,'Social Link Delete','sociallink_delete','yes'),
  (840,'Mail / SMS Template','mailandsmstemplate','yes'),
  (841,'Mail / SMS Template Add','mailandsmstemplate_add','yes'),
  (842,'Mail / SMS Template Edit','mailandsmstemplate_edit','yes'),
  (843,'Mail / SMS Template Delete','mailandsmstemplate_delete','yes'),
  (844,'Mail / SMS Template View','mailandsmstemplate_view','yes'),
  (845,'Import','bulkimport ','yes'),
  (846,'Backup','backup','yes'),
  (847,'Role','usertype','yes'),
  (848,'Role Add','usertype_add','yes'),
  (849,'Role Edit','usertype_edit','yes'),
  (850,'Role Delete','usertype_delete','yes'),
  (851,'Permission','permission','yes'),
  (852,'Auto Update','update','yes'),
  (853,'Posts Categories','posts_categories','yes'),
  (854,'Posts Categories Add','posts_categories_add','yes'),
  (855,'Posts Categories Edit','posts_categories_edit','yes'),
  (856,'Posts Categories Delete','posts_categories_delete','yes'),
  (857,'Posts','posts','yes'),
  (858,'Posts Add','posts_add','yes'),
  (859,'Posts Edit','posts_edit','yes'),
  (860,'Posts Delete','posts_delete','yes'),
  (861,'Pages','pages','yes'),
  (862,'Pages Add','pages_add','yes'),
  (863,'Pages Edit','pages_edit','yes'),
  (864,'Pages Delete','pages_delete','yes'),
  (865,'Menu','frontendmenu','yes'),
  (866,'General Setting','setting','yes'),
  (867,'Frontend Setting','frontend_setting','yes'),
  (868,'Payment Settings','paymentsettings','yes'),
  (869,'SMS Settings','smssettings','yes'),
  (870,'Email Setting','emailsetting','yes'),
  (871,'Mark Settings','marksetting1','yes'),
  (872,'Unit','unit','yes'),
  (873,'Unit Add','unit_add','yes'),
  (874,'Unit Edit','unit_edit','yes'),
  (875,'Unit Delete','unit_delete','yes'),
  (876,'Chapter','chapter','yes'),
  (877,'Chapter Add','chapter_add','yes'),
  (878,'Chapter Edit','chapter_edit','yes'),
  (879,'Chapter Delete','chapter_delete','yes'),
  (880,'Exam Setting','exam_setting','yes'),
  (881,'Exam Setting Add','exam_setting_add','yes'),
  (882,'Exam Setting Edit','exam_setting_edit','yes'),
  (883,'Exam Setting Delete','exam_setting_delete','yes'),
  (884,'Courses','courses','yes'),
  (885,'Courses View','courses_view','yes'),
  (886,'Courses Add','courses_add','yes'),
  (887,'Courses Edit','courses_edit','yes'),
  (888,'Course Report','coursesreport','yes'),
  (889,'Homework','homework','yes'),
  (890,'Homework Add','homework_add','yes'),
  (891,'Homework Edit','homework_edit','yes'),
  (892,'Homework Delete','homework_delete','yes'),
  (893,'Homework View','homework_view','yes'),
  (894,'Classwork','classwork','yes'),
  (895,'Classwork Add','classwork_add','yes'),
  (896,'Classwork Edit','classwork_edit','yes'),
  (897,'Classwork Delete','classwork_delete','yes'),
  (898,'Classwork View','classwork_view','yes'),
  (899,'Terminal Report','terminalreport1','yes'),
  (900,'Student Result','studentresult','yes'),
  (901,'Kindergarden','kindergarten','yes'),
  (902,'Kindergarten Report','terminalreport2','yes'),
  (903,'Feed','feed','yes'),
  (904,'Student Remark','studentremark','yes'),
  (905,'Accumulated Student Attendance','studentattendance','yes'),
  (906,'Student Attendance By Exam','studentattendancebyexam','yes'),
  (907,'Books View','book_view','yes'),
  (908,'Import Report','terminalreport/importreport','yes'),
  (909,'Student Subject Report','graphicalreport/studentsubjectview','yes'),
  (910,'Student Line Report','graphicalreport/studentlineview','yes'),
  (911,'Student Class Report','graphicalreport/studentclassview','yes'),
  (912,'School Information','schoolinformation','yes'),
  (913,'Account','http://paccounts.eduwise.com.np','yes'),
  (914,'Popup Images Add','popupimages_add','yes'),
  (915,'Popup Images Delete','popupimages_delete','yes'),
  (916,'Bulk Mark','bulk_mark','yes'),
  (917,'Student Attendance','attendance/studentlist','yes'),
  (918,'Exam Term Setting','examtermsetting','yes'),
  (919,'Final Terminal Report','finalterminalreport','yes'),
  (920,'Student Search','studentsearch','yes'),
  (921,'Subject Mark','subject_mark','yes'),
  (922,'Live Class','liveclass','yes'),
  (923,'Live Class Add','liveclass_add','yes'),
  (924,'Live Class Edit','liveclass_edit','yes'),
  (925,'Live Class Delete','liveclass_delete','yes'),
  (926,'Live Class View','liveclass_view','yes'),
  (927,'Zoom Settings','zoomsettings','yes'),
  (928,'View Optional Subject','student/view_optional_subject','yes');
  (929, 'Daily_plan', 'daily_plan', 'yes'),
  (930, 'Daily_plan Add', 'daily_plan_add', 'yes'),
  (931, 'Daily_plan Edit', 'daily_plan_edit', 'yes'),
  (932, 'Daily_plan Delete', 'daily_plan_delete', 'yes'),
  (933, 'Daily_plan View', 'daily_plan_view', 'yes'),
  (934, 'Bulk_sms Add', 'bulk_sms_add', 'yes'),
  (935, 'Bulk_sms View', 'bulk_sms_view', 'yes'),
  (936,'FAQ','faq','yes'),
  (937,'FAQ View','faq_view','yes'),
  (938,'FAQ Add','faq_add','yes'),
  (939,'FAQ Edit','faq_edit','yes'),
  (940,'FAQ Delete','faq_delete','yes');
  (941,'Enrollment','enrollment','yes')
  (942,'Enrollment Add','enrollment_add','yes')
  (943,'Enrollment Edit','enrollment_edit','yes')
  (944,'Enrollment Delete','enrollment_delete','yes');
  (945,'Attendance Notification','attendance_notification','yes')
  (946,'Daily Plan Notification','daily_plan_notification','yes')
  (947,'Mark Add Notification','mark_notification','yes');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-04-20 14:49:34
