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
-- Table structure for table `permission_relationships`
--

DROP TABLE IF EXISTS `permission_relationships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permission_relationships` (
  `permission_id` int NOT NULL,
  `usertype_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission_relationships`
--

LOCK TABLES `permission_relationships` WRITE;
/*!40000 ALTER TABLE `permission_relationships` DISABLE KEYS */;
INSERT INTO `permission_relationships` VALUES
  (501, 1),
  (502, 1),
  (503, 1),
  (504, 1),
  (505, 1),
  (506, 1),
  (507, 1),
  (508, 1),
  (509, 1),
  (510, 1),
  (511, 1),
  (512, 1),
  (513, 1),
  (514, 1),
  (515, 1),
  (516, 1),
  (517, 1),
  (518, 1),
  (519, 1),
  (520, 1),
  (521, 1),
  (522, 1),
  (523, 1),
  (524, 1),
  (525, 1),
  (526, 1),
  (527, 1),
  (528, 1),
  (530, 1),
  (531, 1),
  (532, 1),
  (533, 1),
  (534, 1),
  (535, 1),
  (536, 1),
  (537, 1),
  (538, 1),
  (539, 1),
  (540, 1),
  (541, 1),
  (542, 1),
  (543, 1),
  (544, 1),
  (545, 1),
  (546, 1),
  (547, 1),
  (548, 1),
  (549, 1),
  (550, 1),
  (551, 1),
  (552, 1),
  (553, 1),
  (554, 1),
  (555, 1),
  (556, 1),
  (557, 1),
  (558, 1),
  (559, 1),
  (560, 1),
  (561, 1),
  (562, 1),
  (563, 1),
  (564, 1),
  (565, 1),
  (566, 1),
  (567, 1),
  (568, 1),
  (569, 1),
  (570, 1),
  (571, 1),
  (572, 1),
  (573, 1),
  (574, 1),
  (575, 1),
  (576, 1),
  (577, 1),
  (578, 1),
  (579, 1),
  (580, 1),
  (581, 1),
  (582, 1),
  (583, 1),
  (584, 1),
  (585, 1),
  (586, 1),
  (587, 1),
  (588, 1),
  (589, 1),
  (590, 1),
  (591, 1),
  (592, 1),
  (593, 1),
  (594, 1),
  (595, 1),
  (596, 1),
  (597, 1),
  (598, 1),
  (599, 1),
  (600, 1),
  (601, 1),
  (602, 1),
  (603, 1),
  (604, 1),
  (605, 1),
  (606, 1),
  (607, 1),
  (608, 1),
  (609, 1),
  (610, 1),
  (611, 1),
  (612, 1),
  (613, 1),
  (614, 1),
  (615, 1),
  (616, 1),
  (617, 1),
  (618, 1),
  (619, 1),
  (620, 1),
  (621, 1),
  (622, 1),
  (623, 1),
  (624, 1),
  (625, 1),
  (626, 1),
  (627, 1),
  (628, 1),
  (629, 1),
  (630, 1),
  (631, 1),
  (632, 1),
  (633, 1),
  (634, 1),
  (635, 1),
  (636, 1),
  (637, 1),
  (638, 1),
  (639, 1),
  (640, 1),
  (641, 1),
  (642, 1),
  (643, 1),
  (644, 1),
  (645, 1),
  (646, 1),
  (647, 1),
  (648, 1),
  (649, 1),
  (650, 1),
  (651, 1),
  (652, 1),
  (653, 1),
  (654, 1),
  (655, 1),
  (656, 1),
  (657, 1),
  (658, 1),
  (659, 1),
  (660, 1),
  (661, 1),
  (662, 1),
  (663, 1),
  (664, 1),
  (665, 1),
  (666, 1),
  (667, 1),
  (668, 1),
  (669, 1),
  (670, 1),
  (671, 1),
  (672, 1),
  (673, 1),
  (674, 1),
  (675, 1),
  (676, 1),
  (677, 1),
  (678, 1),
  (679, 1),
  (680, 1),
  (681, 1),
  (682, 1),
  (683, 1),
  (684, 1),
  (685, 1),
  (686, 1),
  (687, 1),
  (688, 1),
  (689, 1),
  (690, 1),
  (691, 1),
  (692, 1),
  (693, 1),
  (694, 1),
  (695, 1),
  (696, 1),
  (697, 1),
  (698, 1),
  (699, 1),
  (700, 1),
  (701, 1),
  (702, 1),
  (703, 1),
  (704, 1),
  (705, 1),
  (706, 1),
  (707, 1),
  (708, 1),
  (709, 1),
  (710, 1),
  (711, 1),
  (712, 1),
  (713, 1),
  (714, 1),
  (715, 1),
  (716, 1),
  (717, 1),
  (718, 1),
  (719, 1),
  (720, 1),
  (721, 1),
  (722, 1),
  (723, 1),
  (724, 1),
  (725, 1),
  (726, 1),
  (727, 1),
  (728, 1),
  (729, 1),
  (730, 1),
  (731, 1),
  (732, 1),
  (733, 1),
  (734, 1),
  (735, 1),
  (736, 1),
  (737, 1),
  (738, 1),
  (739, 1),
  (740, 1),
  (741, 1),
  (742, 1),
  (743, 1),
  (744, 1),
  (745, 1),
  (746, 1),
  (747, 1),
  (748, 1),
  (749, 1),
  (750, 1),
  (751, 1),
  (752, 1),
  (753, 1),
  (754, 1),
  (755, 1),
  (756, 1),
  (757, 1),
  (758, 1),
  (759, 1),
  (760, 1),
  (761, 1),
  (762, 1),
  (763, 1),
  (764, 1),
  (765, 1),
  (766, 1),
  (767, 1),
  (768, 1),
  (769, 1),
  (770, 1),
  (771, 1),
  (772, 1),
  (773, 1),
  (774, 1),
  (775, 1),
  (776, 1),
  (777, 1),
  (778, 1),
  (779, 1),
  (780, 1),
  (781, 1),
  (782, 1),
  (783, 1),
  (784, 1),
  (785, 1),
  (786, 1),
  (787, 1),
  (788, 1),
  (789, 1),
  (790, 1),
  (791, 1),
  (792, 1),
  (793, 1),
  (794, 1),
  (795, 1),
  (796, 1),
  (797, 1),
  (798, 1),
  (799, 1),
  (800, 1),
  (801, 1),
  (802, 1),
  (803, 1),
  (804, 1),
  (805, 1),
  (806, 1),
  (807, 1),
  (808, 1),
  (809, 1),
  (810, 1),
  (811, 1),
  (812, 1),
  (813, 1),
  (814, 1),
  (815, 1),
  (816, 1),
  (817, 1),
  (818, 1),
  (819, 1),
  (820, 1),
  (821, 1),
  (822, 1),
  (823, 1),
  (824, 1),
  (825, 1),
  (826, 1),
  (827, 1),
  (828, 1),
  (829, 1),
  (830, 1),
  (831, 1),
  (832, 1),
  (833, 1),
  (834, 1),
  (835, 1),
  (836, 1),
  (837, 1),
  (838, 1),
  (839, 1),
  (840, 1),
  (841, 1),
  (842, 1),
  (843, 1),
  (844, 1),
  (845, 1),
  (846, 1),
  (847, 1),
  (848, 1),
  (849, 1),
  (850, 1),
  (851, 1),
  (852, 1),
  (853, 1),
  (854, 1),
  (855, 1),
  (856, 1),
  (857, 1),
  (858, 1),
  (859, 1),
  (860, 1),
  (861, 1),
  (862, 1),
  (863, 1),
  (864, 1),
  (865, 1),
  (866, 1),
  (867, 1),
  (868, 1),
  (869, 1),
  (870, 1),
  (871, 1),
  (872, 1),
  (873, 1),
  (874, 1),
  (875, 1),
  (876, 1),
  (877, 1),
  (878, 1),
  (879, 1),  
  (880, 1),  
  (881, 1),  
  (882, 1),  
  (883, 1),
  (884, 1),
  (886, 1),
  (887, 1),
  (888, 1),
  (889, 1),
  (890, 1),
  (891, 1),
  (892, 1),
  (893, 1),
  (894, 1),
  (895, 1),
  (896, 1),
  (897, 1),
  (898, 1),
  (899, 1),
  (901, 1),
  (902, 1),
  (903, 1),
  (904, 1),
  (905, 1),
  (906, 1),
  (501, 2),
  (502, 2),
  (506, 2),
  (507, 2),
  (511, 2),
  (512, 2),
  (516, 2),
  (531, 2),
  (535, 2),
  (536, 2),
  (537, 2),
  (538, 2),
  (539, 2),
  (540, 2),
  (541, 2),
  (542, 2),
  (543, 2),
  (544, 2),
  (548, 2),
  (549, 2),
  (550, 2),
  (551, 2),
  (553, 2),
  (554, 2),
  (556, 2),
  (561, 2),
  (569, 2),
  (570, 2),
  (571, 2),
  (572, 2),
  (573, 2),
  (579, 2),
  (580, 2),
  (581, 2),
  (582, 2),
  (586, 2),
  (587, 2),
  (588, 2),
  (590, 2),
  (591, 2),
  (592, 2),
  (594, 2),
  (595, 2),
  (596, 2),
  (598, 2),
  (599, 2),
  (600, 2),
  (601, 2),
  (603, 2),
  (604, 2),
  (605, 2),
  (607, 2),
  (683, 2),
  (684, 2),
  (685, 2),
  (686, 2),
  (687, 2),
  (688, 2),
  (693, 2),
  (694, 2),
  (695, 2),
  (705, 2),
  (713, 2),
  (717, 2),
  (718, 2),
  (727, 2),
  (731, 2),
  (761, 2),
  (765, 2),
  (766, 2),
  (770, 2),
  (771, 2),
  (775, 2),
  (777, 2),
  (780, 2),
  (781, 2),
  (782, 2),
  (783, 2),
  (787, 2),
  (788, 2),
  (789, 2),
  (790, 2),
  (791, 2),
  (793, 2),
  (794, 2),
  (820, 2),
  (821, 2),
  (824, 2),
  (872, 2),
  (873, 2),
  (874, 2),
  (875, 2),
  (876, 2),
  (877, 2),
  (878, 2),
  (879, 2),
  (880, 2),
  (881, 2),
  (882, 2),
  (883, 2),
  (884, 2),
  (886, 2),
  (887, 2),
  (888, 2),
  (889, 2),
  (890, 2),
  (891, 2),
  (892, 2),
  (893, 2),
  (894, 2),
  (895, 2),
  (896, 2),
  (897, 2),
  (898, 2),
  (899, 2),
  (902, 2),
  (903, 2),
  (904, 2),
  (905, 2),
  (906, 2),
  (501, 3),
  (502, 3),
  (512, 3),
  (516, 3),
  (531, 3),
  (539, 3),
  (543, 3),
  (544, 3),
  (548, 3),
  (561, 3),
  (571, 3),
  (579, 3),
  (580, 3),
  (683, 3),
  (684, 3),
  (685, 3),
  (686, 3),
  (687, 3),
  (693, 3),
  (700, 3),
  (705, 3),
  (709, 3),
  (712, 3),
  (713, 3),
  (717, 3),
  (718, 3),
  (722, 3),
  (727, 3),
  (731, 3),
  (744, 3),
  (748, 3),
  (749, 3),
  (761, 3),
  (765, 3),
  (766, 3),
  (770, 3),
  (771, 3),
  (775, 3),
  (820, 3),
  (821, 3),
  (824, 3),
  (884, 3),
  (885, 3),
  (889, 3),
  (893, 3),
  (894, 3),
  (898, 3),
  (903, 3),
  (900, 3),
  (501, 4),
  (502, 4),
  (506, 4),
  (512, 4),
  (516, 4),
  (531, 4),
  (535, 4),
  (544, 4),
  (548, 4),
  (550, 4),
  (561, 4),
  (571, 4),
  (573, 4),
  (579, 4),
  (580, 4),
  (693, 4),
  (696, 4),
  (700, 4),
  (704, 4),
  (705, 4),
  (709, 4),
  (712, 4),
  (718, 4),
  (722, 4),
  (726, 4),
  (727, 4),
  (731, 4),
  (735, 4),
  (739, 4),
  (744, 4),
  (748, 4),
  (749, 4),
  (761, 4),
  (765, 4),
  (766, 4),
  (770, 4),
  (771, 4),
  (775, 4),
  (820, 4),
  (821, 4),
  (824, 4),
  (884, 4),
  (885, 4),
  (900, 4),
  (903, 4),
  (501, 5),
  (512, 5),
  (516, 5),
  (554, 5),
  (556, 5),
  (579, 5),
  (580, 5),
  (608, 5),
  (609, 5),
  (610, 5),
  (611, 5),
  (612, 5),
  (613, 5),
  (614, 5),
  (615, 5),
  (616, 5),
  (617, 5),
  (618, 5),
  (619, 5),
  (620, 5),
  (621, 5),
  (622, 5),
  (649, 5),
  (650, 5),
  (651, 5),
  (652, 5),
  (653, 5),
  (654, 5),
  (655, 5),
  (656, 5),
  (657, 5),
  (658, 5),
  (659, 5),
  (660, 5),
  (661, 5),
  (662, 5),
  (663, 5),
  (664, 5),
  (665, 5),
  (666, 5),
  (667, 5),
  (668, 5),
  (669, 5),
  (670, 5),
  (671, 5),
  (672, 5),
  (673, 5),
  (674, 5),
  (683, 5),
  (684, 5),
  (685, 5),
  (686, 5),
  (687, 5),
  (718, 5),
  (722, 5),
  (723, 5),
  (724, 5),
  (725, 5),
  (726, 5),
  (727, 5),
  (731, 5),
  (735, 5),
  (736, 5),
  (737, 5),
  (738, 5),
  (739, 5),
  (740, 5),
  (741, 5),
  (742, 5),
  (743, 5),
  (744, 5),
  (745, 5),
  (746, 5),
  (747, 5),
  (748, 5),
  (749, 5),
  (750, 5),
  (751, 5),
  (752, 5),
  (753, 5),
  (754, 5),
  (755, 5),
  (756, 5),
  (757, 5),
  (758, 5),
  (759, 5),
  (760, 5),
  (761, 5),
  (765, 5),
  (766, 5),
  (770, 5),
  (771, 5),
  (775, 5),
  (798, 5),
  (799, 5),
  (800, 5),
  (801, 5),
  (802, 5),
  (803, 5),
  (804, 5),
  (805, 5),
  (806, 5),
  (820, 5),
  (821, 5),
  (824, 5),
  (903, 5),
  (501, 6),
  (512, 6),
  (516, 6),
  (531, 6),
  (554, 6),
  (556, 6),
  (579, 6),
  (580, 6),
  (683, 6),
  (684, 6),
  (685, 6),
  (686, 6),
  (687, 6),
  (700, 6),
  (701, 6),
  (702, 6),
  (703, 6),
  (704, 6),
  (705, 6),
  (706, 6),
  (707, 6),
  (708, 6),
  (709, 6),
  (710, 6),
  (711, 6),
  (712, 6),
  (713, 6),
  (714, 6),
  (715, 6),
  (716, 6),
  (717, 6),
  (718, 6),
  (727, 6),
  (731, 6),
  (761, 6),
  (765, 6),
  (766, 6),
  (770, 6),
  (771, 6),
  (775, 6),
  (777, 6),
  (784, 6),
  (785, 6),
  (786, 6),
  (820, 6),
  (821, 6),
  (824, 6),
  (903, 6),
  (501, 7),
  (502, 7),
  (506, 7),
  (507, 7),
  (511, 7),
  (512, 7),
  (516, 7),
  (517, 7),
  (521, 7),
  (548, 7),
  (550, 7),
  (551, 7),
  (553, 7),
  (554, 7),
  (556, 7),
  (579, 7),
  (580, 7),
  (683, 7),
  (684, 7),
  (685, 7),
  (686, 7),
  (687, 7),
  (727, 7),
  (731, 7),
  (761, 7),
  (765, 7),
  (766, 7),
  (770, 7),
  (771, 7),
  (775, 7),
  (809, 7),
  (810, 7),
  (811, 7),
  (820, 7),
  (821, 7),
  (824, 7),
  (903, 7);
/*!40000 ALTER TABLE `permission_relationships` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-09-14 16:26:49