-- MariaDB dump 10.19-11.3.2-MariaDB, for debian-linux-gnu (aarch64)
--
-- Host: localhost    Database: adshowcase
-- ------------------------------------------------------
-- Server version	11.3.2-MariaDB-1:11.3.2+maria~ubu2204

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ADSHOWCASE_agency`
--

DROP TABLE IF EXISTS `ADSHOWCASE_agency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ADSHOWCASE_agency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` char(16) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('active','archived','pending') NOT NULL DEFAULT 'active',
  `country_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`),
  UNIQUE KEY `name` (`name`),
  KEY `fk_agency_country` (`country_id`),
  CONSTRAINT `fk_agency_country` FOREIGN KEY (`country_id`) REFERENCES `ADSHOWCASE_country` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ADSHOWCASE_agency`
--

LOCK TABLES `ADSHOWCASE_agency` WRITE;
/*!40000 ALTER TABLE `ADSHOWCASE_agency` DISABLE KEYS */;
INSERT INTO `ADSHOWCASE_agency` VALUES
(1,'Rgeo9r8J85ISRFFU','Ogilvy','pending',19,'2025-01-15 02:24:19','2025-02-04 10:08:08'),
(2,'P_3tIxidWMuS_Jnb','McCann Worldgroup','active',27,'2025-10-26 11:17:13','2025-12-10 22:35:26'),
(3,'ZTdxvvOC-YVsoHNJ','DDB Worldwide','pending',6,'2025-10-30 11:29:49','2025-12-29 08:45:13'),
(4,'MbcdLD_j7MGkcSpn','BBDO','archived',30,'2025-09-20 10:58:15','2025-10-03 17:17:56'),
(5,'pJan5TOJTYvHDUD3','TBWA\\Worldwide','active',29,'2025-01-26 16:12:13','2025-03-26 21:22:47'),
(6,'voj9ruackg7krk3Q','Leo Burnett','archived',12,'2025-11-22 17:52:55','2025-12-15 00:16:22'),
(7,'ykVd06-SeZ7WqCdp','Publicis Worldwide','archived',37,'2025-09-10 23:50:29','2025-10-15 12:31:29'),
(8,'91wQfix-3R-c_Cs0','Saatchi & Saatchi','active',49,'2025-12-18 20:04:12','2026-02-16 05:43:00'),
(9,'K7Uv5ZFVm-sUXKlr','Grey Group','active',9,'2025-05-19 17:59:39','2025-05-21 05:37:43'),
(10,'4ZvvMLVutsM2czSv','VML','active',46,'2025-11-25 05:46:51','2026-01-11 18:11:16'),
(11,'v3Bs9axRDHi8-XgC','Wunderman Thompson','active',17,'2025-04-12 22:59:21','2025-05-24 08:28:27'),
(12,'izLBE0rrANRoBI8o','Havas Creative','active',25,'2025-10-22 20:52:40','2025-11-24 14:49:55'),
(13,'CKAr-Mom4ViphyPw','Dentsu International','active',35,'2025-02-19 22:35:54','2025-03-21 09:34:42'),
(14,'VbmreKvo08gcpBaJ','FCB (Foote, Cone & Belding)','pending',44,'2025-08-05 18:25:51','2025-08-25 08:11:10'),
(15,'9s1KOPKiYMTPZApY','MullenLowe Group','active',8,'2025-12-27 01:11:06','2026-01-16 23:57:15'),
(16,'EL07cbQs_HNVKwLc','R/GA','pending',27,'2025-03-16 20:40:49','2025-04-24 03:43:45'),
(17,'fm5sbVMfJPV6D3ts','AKQA','active',37,'2025-06-10 15:44:17','2025-06-13 14:13:20'),
(18,'TB1Clhj5GGLHhaBp','VaynerMedia','active',25,'2025-08-17 05:04:51','2025-10-03 20:14:52'),
(19,'s_3y9hqQ9uViwvuj','Droga5','active',31,'2025-11-14 16:04:54','2026-01-02 23:04:07'),
(20,'pbAclpeGd0UNSYYn','Wieden+Kennedy','active',23,'2025-03-11 04:00:31','2025-05-06 01:20:26'),
(21,'_P6QJMtQY2OwsAmt','72andSunny','active',4,'2025-12-05 04:37:30','2026-01-23 17:12:42'),
(22,'e6eSYU2DErskJdGQ','Anomaly','active',40,'2025-04-26 09:39:52','2025-05-02 15:36:50'),
(23,'M-yhpQ8NfytsI6DH','Crispin Porter Bogusky','active',24,'2025-02-19 09:21:36','2025-03-14 22:23:09'),
(24,'Ox6k2xKjGtVLptVH','Forsman & Bodenfors','pending',4,'2025-07-27 10:14:22','2025-08-19 09:24:56'),
(25,'DHfhQBTxKUcWyD54','Mother London','active',32,'2025-05-30 21:02:18','2025-07-28 04:32:18'),
(26,'p5GNSE_1FF_oGB0R','BBH (Bartle Bogle Hegarty)','pending',47,'2025-10-10 17:04:32','2025-12-08 09:42:05'),
(27,'AeEH-9bnK2ne7_pL','Goodby Silverstein & Partners','pending',30,'2025-09-29 04:19:35','2025-11-17 05:23:11'),
(28,'RxmaNcGaXRUMINcP','Deutsch','archived',4,'2025-05-24 11:59:09','2025-07-14 21:22:23'),
(29,'E5CqmvDmdpuJIO0A','Jung von Matt','active',22,'2025-08-28 04:59:49','2025-09-21 18:49:29'),
(30,'nE4HOJcDQDKgDNdZ','Serviceplan Group','active',44,'2025-11-12 19:42:56','2025-12-27 13:56:15'),
(31,'jpL-MGn4TJPvJPih','Hakuhodo','active',5,'2025-08-11 11:01:31','2025-10-05 18:59:44'),
(32,'IOtTRuoJIscebts_','Cheil Worldwide','archived',35,'2025-11-05 05:10:45','2025-12-17 22:57:53'),
(33,'Jv69FGKdPgyGPWqv','Mindshare','active',18,'2025-01-06 09:25:37','2025-01-28 07:19:07'),
(34,'1mBPV4bbQBPFWJui','OMD','active',37,'2025-03-04 23:45:40','2025-04-19 02:07:15'),
(35,'OxfSp0we0isbdKWX','Carat','active',10,'2025-02-10 23:22:48','2025-02-21 07:17:47'),
(36,'LZz6JrKJ7NP3z747','MediaCom','active',7,'2025-11-14 02:23:15','2025-12-08 04:18:03'),
(37,'3-8p39tFlLjsITuW','Wavemaker','pending',11,'2025-10-23 06:14:33','2025-11-09 15:56:48'),
(38,'noDTKcb9lJc5Ko3D','Starcom','pending',15,'2025-12-26 11:52:24','2026-02-02 09:09:03'),
(39,'vl_gTF_j1o8H26t6','Zenith','active',18,'2025-08-17 00:10:04','2025-09-14 04:24:44'),
(40,'cVAgm-3DJFijWCu7','PHD Media','active',31,'2025-10-14 22:44:15','2025-11-26 12:06:10'),
(41,'naggX_BbqojM-WLV','Initiative','active',4,'2025-10-17 14:48:47','2025-12-08 23:41:56'),
(42,'qfuxCEcaylLpPUOP','UM (Universal McCann)','active',24,'2025-11-20 17:38:45','2025-11-23 15:21:32'),
(43,'3rdMcwKXbIqz7BSe','Havas Media','pending',34,'2025-06-07 11:28:52','2025-06-26 14:15:19'),
(44,'1QMQ_g3AuFaxCGj1','iProspect','active',40,'2025-01-08 00:12:46','2025-02-03 05:57:29'),
(45,'i4jEFPtUKezVnChc','Essence','active',3,'2025-02-04 16:27:58','2025-02-15 04:46:29'),
(46,'qox9RU2Eu_O9zVds','Spark Foundry','active',11,'2025-07-13 17:04:25','2025-08-14 18:00:36'),
(47,'P4Vw3_SigepNbu41','Assembly','active',16,'2025-11-30 11:50:21','2026-01-28 15:45:21'),
(48,'msvovSrMs271qgfO','Hearts & Science','pending',15,'2025-07-30 02:48:14','2025-09-11 21:47:32'),
(49,'NDDMlPIAx9twTX9i','Horizon Media','pending',38,'2025-01-05 10:54:58','2025-02-01 15:43:27'),
(50,'gMlxuDmmVk5f0Joq','Accenture Song','active',6,'2025-02-22 00:47:46','2025-04-13 23:02:53'),
(51,'9vrRNYd-e5Y910LD','Deloitte Digital','active',35,'2025-04-12 08:47:33','2025-04-26 16:55:46'),
(52,'aRDrijMFUxzKcAKs','IBM iX','active',45,'2025-11-15 07:42:01','2025-12-28 00:17:24'),
(53,'lkS_7aUDxh0qua17','PwC Digital','active',39,'2025-07-26 08:39:55','2025-09-16 06:26:09'),
(54,'2AXlHawNBE122HmH','Globant','pending',6,'2025-08-29 06:04:41','2025-10-02 00:35:19'),
(55,'Lv7TpD7nAmFFVVWu','Media.Monks','pending',28,'2025-08-14 19:34:04','2025-10-03 23:44:27'),
(56,'RILPwwVTL9-rPkuS','Huge','active',14,'2025-12-04 05:05:07','2026-01-28 18:48:59'),
(57,'HOo-G3uOR-T3_Cb3','Critical Mass','active',26,'2025-02-25 20:32:49','2025-04-13 14:09:23'),
(58,'lkeBf5sfLd5rjpxe','Mirum','pending',9,'2025-09-29 00:27:16','2025-11-08 06:13:21'),
(59,'ST2AW3sUAnN8y-x3','Isobar','pending',12,'2025-11-27 14:38:00','2026-01-01 21:29:38'),
(60,'dDrSq5CQtF7ePBGA','Digitas','active',31,'2025-12-30 14:07:24','2026-01-22 17:44:25'),
(61,'YV2mH_58MVjSwXP7','Razorfish','active',49,'2025-08-10 22:52:34','2025-09-12 23:30:17'),
(62,'7d9JhjPa8fJdqfv9','Edelman','active',1,'2025-01-30 03:45:07','2025-02-12 02:30:19'),
(63,'gQp86T-0CBEZEGNl','Weber Shandwick','active',47,'2025-11-19 19:20:16','2025-12-08 03:01:47'),
(64,'rGxgb0YoXkbbgmdf','FleishmanHillard','pending',32,'2025-01-10 11:28:20','2025-02-14 19:08:23'),
(65,'zlauY5Rofdq9LNIb','Ketchum','pending',40,'2025-09-22 18:21:45','2025-11-08 20:01:04'),
(66,'B2xr50k1Tq-BvH4Y','Burson','pending',30,'2025-08-12 16:13:28','2025-09-07 15:13:54'),
(67,'OX6skGu5riaQdchC','Hill & Knowlton','active',41,'2025-04-28 20:40:19','2025-05-03 23:51:04'),
(68,'BdbXzUpGjInGroQr','MSL','active',20,'2025-05-09 04:10:39','2025-06-10 16:31:15'),
(69,'Z9jEtU6pg0rEfPbE','Golin','pending',7,'2025-11-09 00:50:41','2025-12-16 10:26:04'),
(70,'TEEOodQhtKPnO47K','Ogilvy PR','active',31,'2025-01-11 15:17:25','2025-02-25 17:20:08'),
(71,'3ooJmBitmjUVRyvt','Wintheiser Agency','active',3,'2025-05-21 22:35:49','2025-06-04 08:12:42'),
(72,'18Wr8y_Ov-pKY_Ll','Kertzmann Partners','active',41,'2025-11-15 23:33:54','2026-01-01 16:41:46'),
(73,'z7eUYXMvhwuygbNA','Koepp Media','active',29,'2025-01-26 23:32:22','2025-01-27 11:06:34'),
(74,'qdvr_f-jtzB-xAtO','Jacobi Communications','active',39,'2025-09-04 15:47:40','2025-10-04 09:47:00'),
(75,'WzUzuodS_gJ4OGnB','Hahn Worldwide','active',45,'2025-06-01 12:53:00','2025-07-22 19:43:22'),
(76,'s5CFkZyqHWyoWSj9','Kovacek Communications','pending',28,'2025-07-27 05:39:04','2025-09-23 12:25:22'),
(77,'IG2Q-UsBEsxK5dwh','Mohr Agency','pending',37,'2025-12-05 04:50:17','2026-01-06 08:14:56'),
(78,'6xUXHlrQDcCGYxTs','Hermiston Group','pending',1,'2025-09-01 10:22:08','2025-09-27 13:42:26'),
(79,'oCRg4dragTXcMGaf','Renner Agency','active',15,'2025-06-30 10:26:28','2025-08-04 12:31:16'),
(80,'-JzAE-HmFwJgK4tF','Larkin Media','archived',41,'2025-05-24 01:17:24','2025-05-31 17:06:03'),
(81,'_QUyRoYjBEOG1Ue9','Stiedemann Partners','active',45,'2025-08-24 10:18:07','2025-10-09 01:26:34'),
(82,'5Bx9MpjshhrOUJNj','Schmitt Media','archived',5,'2025-11-20 16:26:54','2025-12-17 14:22:23'),
(83,'udseU6-dOJRYU3Gi','Baumbach Communications','archived',25,'2025-12-01 15:57:18','2026-01-18 07:38:41'),
(84,'G86jeB5drj6SK7DC','Bogisich Worldwide','active',3,'2025-04-10 11:50:16','2025-05-15 23:56:16'),
(85,'1u2TO83ROje8bG__','Cassin Agency','active',18,'2025-02-01 18:06:06','2025-02-23 06:17:27'),
(86,'gEXbGEdxcD3Lran7','Lemke Agency','pending',9,'2025-07-21 18:43:31','2025-08-17 20:36:42'),
(87,'8z_N6Eaz_0nHQbxv','Bergstrom Partners','active',43,'2025-08-28 14:51:28','2025-10-04 01:58:55'),
(88,'9lrJOOB6HLSt0KBb','Nicolas Group','archived',40,'2025-08-11 20:40:03','2025-10-02 19:48:02'),
(89,'WLF0m1C2u5-_KhVE','Sawayn Communications','active',41,'2025-11-20 16:51:09','2026-01-17 02:47:24'),
(90,'8ug6qErm3aYisu6t','Jerde Group','active',42,'2025-08-10 19:35:11','2025-09-16 02:30:33'),
(91,'4btfNe9X6IQaRFeN','Ferry Worldwide','active',41,'2025-12-14 17:21:40','2025-12-19 11:38:44'),
(92,'dv2Xj0flBBZqqrSA','Aufderhar Partners','pending',23,'2025-12-13 17:32:47','2025-12-17 12:37:08'),
(93,'bkFSNNJpwWDIUJU-','Moen Partners','active',25,'2025-06-24 16:11:03','2025-07-28 01:07:49'),
(94,'ouN3-EoMh1loTsfG','Nienow Media','active',45,'2025-10-25 03:11:18','2025-12-07 10:11:03'),
(95,'So91b8knY-pZnPce','Davis Group','pending',15,'2025-01-09 22:26:47','2025-01-25 02:50:48'),
(96,'n-3m-wCjiv0WLhGQ','Smith Group','active',2,'2025-01-19 13:24:43','2025-02-26 11:11:41'),
(97,'VT6ptZcNsqVPoHrI','West Communications','active',30,'2025-04-20 01:30:09','2025-05-07 12:10:49'),
(98,'DvYroHU2j0HTWW11','McKenzie Media','active',49,'2025-02-28 22:21:33','2025-04-18 22:28:12'),
(99,'mKnZnAilQNXnOxbW','Champlin Communications','archived',43,'2025-06-11 12:02:30','2025-07-02 16:24:39'),
(100,'BXmNiqbXI8NTDAfN','Herman Communications','active',6,'2025-10-27 16:26:18','2025-12-02 15:46:42');
/*!40000 ALTER TABLE `ADSHOWCASE_agency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ADSHOWCASE_asset_file`
--

DROP TABLE IF EXISTS `ADSHOWCASE_asset_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ADSHOWCASE_asset_file` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `hash_sha256` char(64) NOT NULL,
  `storage_path` varchar(500) NOT NULL,
  `mime` varchar(100) NOT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `duration_sec` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash_sha256` (`hash_sha256`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ADSHOWCASE_asset_file`
--

LOCK TABLES `ADSHOWCASE_asset_file` WRITE;
/*!40000 ALTER TABLE `ADSHOWCASE_asset_file` DISABLE KEYS */;
INSERT INTO `ADSHOWCASE_asset_file` VALUES
(1,'21e6ebd86415aea65f2a2ac6d8c705a128321f684c45da3dbdc30302f8bf4479','/uploads/assets/21/e6/21e6ebd86415aea65f2a2ac6d8c705a128321f684c45da3dbdc30302f8bf4479.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(2,'2d85d3ec97ef920dd39c7323b31ae56c9056e2d406c83df6dc29aedfb99b489a','/uploads/assets/2d/85/2d85d3ec97ef920dd39c7323b31ae56c9056e2d406c83df6dc29aedfb99b489a.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(3,'37a51e1de730399844225071c425fb25ac06faf214bc19d37639d639e662e814','/uploads/assets/37/a5/37a51e1de730399844225071c425fb25ac06faf214bc19d37639d639e662e814.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(4,'62777963b7b6b51d9d96b9558c14fe01c59b0b2fa3895cb52591aecc8e110c9b','/uploads/assets/62/77/62777963b7b6b51d9d96b9558c14fe01c59b0b2fa3895cb52591aecc8e110c9b.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(5,'e2e180e3c6bcc4d65bf0883b20646b303dd9b15aca8d633e08853e116cee5f91','/uploads/assets/e2/e1/e2e180e3c6bcc4d65bf0883b20646b303dd9b15aca8d633e08853e116cee5f91.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(6,'93b1bfdca9a2a27ea37ba41fffd4f8875acddf3e907100be4896e363a81d8049','/uploads/assets/93/b1/93b1bfdca9a2a27ea37ba41fffd4f8875acddf3e907100be4896e363a81d8049.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(7,'a598ec08dcfb05187c534de33afdb1ea3e484ab334d50a8224deacabee9042b5','/uploads/assets/a5/98/a598ec08dcfb05187c534de33afdb1ea3e484ab334d50a8224deacabee9042b5.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(8,'37a7aeb83f5f12756e2d146a4eff825413ba6d404c0d304ae1c297f070abb184','/uploads/assets/37/a7/37a7aeb83f5f12756e2d146a4eff825413ba6d404c0d304ae1c297f070abb184.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(9,'a4ec430bff08674c14ae1162a97e18f3b6374df5e72b8b0c5a7ce5ee4e2eb77e','/uploads/assets/a4/ec/a4ec430bff08674c14ae1162a97e18f3b6374df5e72b8b0c5a7ce5ee4e2eb77e.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(10,'810e1e21c331f96a89d6ca9804d87ee48f0a4631f12ac678818fe9a258d69468','/uploads/assets/81/0e/810e1e21c331f96a89d6ca9804d87ee48f0a4631f12ac678818fe9a258d69468.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(11,'310fe4548123e9eddaa08824e59a48c912cb372b63d4953f431288906462bdde','/uploads/assets/31/0f/310fe4548123e9eddaa08824e59a48c912cb372b63d4953f431288906462bdde.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(12,'1378ab260a69be18c958b8bfec98a8202e5ebb0d3409838a57e037cfabebb20e','/uploads/assets/13/78/1378ab260a69be18c958b8bfec98a8202e5ebb0d3409838a57e037cfabebb20e.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(13,'2118180f3e268d5b8c0aef5a74d0bd8f67f1bc79975b21f50e82b1598ae414b9','/uploads/assets/21/18/2118180f3e268d5b8c0aef5a74d0bd8f67f1bc79975b21f50e82b1598ae414b9.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(14,'c3d41e736932312d40f34de3c4c3cd138e029708f9e852fc69b276f2e76c804f','/uploads/assets/c3/d4/c3d41e736932312d40f34de3c4c3cd138e029708f9e852fc69b276f2e76c804f.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(15,'f5c2e657bf70e171da5b769d99992d789c18d67791972a8c057b673e99fd18f3','/uploads/assets/f5/c2/f5c2e657bf70e171da5b769d99992d789c18d67791972a8c057b673e99fd18f3.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(16,'959419fbf0ac5b39d405f3dfb00951bd4457dc9431e5fb70fec5bf6043d2c33c','/uploads/assets/95/94/959419fbf0ac5b39d405f3dfb00951bd4457dc9431e5fb70fec5bf6043d2c33c.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(17,'d1034330cf633153f91b93de7db5d85abba844fe0e54c09380a5797d45f3d7f8','/uploads/assets/d1/03/d1034330cf633153f91b93de7db5d85abba844fe0e54c09380a5797d45f3d7f8.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(18,'69d45dd61d56feba461fb5de911be53cdfcfc0957ee41a6f8abe0d9c3d67f5c3','/uploads/assets/69/d4/69d45dd61d56feba461fb5de911be53cdfcfc0957ee41a6f8abe0d9c3d67f5c3.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(19,'397eba2b7cace8370b10398ac462238b420198bda882acd8cd62652b95dcfe0b','/uploads/assets/39/7e/397eba2b7cace8370b10398ac462238b420198bda882acd8cd62652b95dcfe0b.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(20,'bf9f90ad0190489353ba9ee6a9d32abc66d00138250d9f7c61e140d3326bcbc2','/uploads/assets/bf/9f/bf9f90ad0190489353ba9ee6a9d32abc66d00138250d9f7c61e140d3326bcbc2.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(21,'a1dfc94a5c7ae6234b555ab0ec6feb500905f4e884462a01ad713402b1d0606a','/uploads/assets/a1/df/a1dfc94a5c7ae6234b555ab0ec6feb500905f4e884462a01ad713402b1d0606a.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(22,'508584adbf1bbece60834f70a6d22857bcba5dd8bcad79b084794474badb6e2b','/uploads/assets/50/85/508584adbf1bbece60834f70a6d22857bcba5dd8bcad79b084794474badb6e2b.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(23,'dc636a242108470a26fd3cf4298fe8850dd9c9a669a1fd897550be537bba9f3b','/uploads/assets/dc/63/dc636a242108470a26fd3cf4298fe8850dd9c9a669a1fd897550be537bba9f3b.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(24,'d2a3bf5839753b0f7c2a60ea8b6de76c506ca0aab21dc2242453417304fa5e68','/uploads/assets/d2/a3/d2a3bf5839753b0f7c2a60ea8b6de76c506ca0aab21dc2242453417304fa5e68.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(25,'8b83a1e847503189ef5193f6aa6e79ddd24b48ea85a53f14bd0f9b1da2e296c3','/uploads/assets/8b/83/8b83a1e847503189ef5193f6aa6e79ddd24b48ea85a53f14bd0f9b1da2e296c3.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(26,'27ecf3561637e58072500fb3e541f46b239f9546674a24f7927640a957416558','/uploads/assets/27/ec/27ecf3561637e58072500fb3e541f46b239f9546674a24f7927640a957416558.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(27,'294233c3bb895ac3c92638e3143788e3bb63b4da0d17c5d5e62ce5b25597ddfa','/uploads/assets/29/42/294233c3bb895ac3c92638e3143788e3bb63b4da0d17c5d5e62ce5b25597ddfa.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(28,'a56e0a2744913eda0d2cb1b3028b4f47c4c9171b034ebc2cff8b98366b0537ab','/uploads/assets/a5/6e/a56e0a2744913eda0d2cb1b3028b4f47c4c9171b034ebc2cff8b98366b0537ab.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(29,'08e3aaefa1a87b2a24c1edb076bb1add3e0dc7345b62518bb96f81ec9523ac2d','/uploads/assets/08/e3/08e3aaefa1a87b2a24c1edb076bb1add3e0dc7345b62518bb96f81ec9523ac2d.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(30,'a12719c90a3c0d559be000c60a0357f615c00b2dd457bcfc988a302d9885306f','/uploads/assets/a1/27/a12719c90a3c0d559be000c60a0357f615c00b2dd457bcfc988a302d9885306f.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(31,'cbf6e4a7c5a0e3952d3620d01abb67f078380061927b1124be2f4987b8203207','/uploads/assets/cb/f6/cbf6e4a7c5a0e3952d3620d01abb67f078380061927b1124be2f4987b8203207.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(32,'e3f52bacc27697446b0eb795af61215b419fc46e0f1b77f24b1ba5cd8d5e2e49','/uploads/assets/e3/f5/e3f52bacc27697446b0eb795af61215b419fc46e0f1b77f24b1ba5cd8d5e2e49.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(33,'a2944192f898eac1451a80ea891e9ce0b21daf53ead1874889288d05eec0618b','/uploads/assets/a2/94/a2944192f898eac1451a80ea891e9ce0b21daf53ead1874889288d05eec0618b.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(34,'b2b956905cee6c6357cc698593e1d1705b5ba8a1c4f6a43d29848eec03d76b5e','/uploads/assets/b2/b9/b2b956905cee6c6357cc698593e1d1705b5ba8a1c4f6a43d29848eec03d76b5e.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(35,'d773d329905ff878d9187717501b6cb0d2b8ff08c53328d00a7f2a2a8fda7b8b','/uploads/assets/d7/73/d773d329905ff878d9187717501b6cb0d2b8ff08c53328d00a7f2a2a8fda7b8b.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(36,'6ecde8b1627923dbc6a39be7d4943f5a0ca1b0776bced4e8c3bf7921f09b11b2','/uploads/assets/6e/cd/6ecde8b1627923dbc6a39be7d4943f5a0ca1b0776bced4e8c3bf7921f09b11b2.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(37,'5e61a85fa20f346a7309b1761dc1290fc52b0888cf22743f4c6633e6cc4bedd9','/uploads/assets/5e/61/5e61a85fa20f346a7309b1761dc1290fc52b0888cf22743f4c6633e6cc4bedd9.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(38,'f20d364ad3bea30dd4c5c8c923be38d87c24af5c859368ade20f7e966c2fe527','/uploads/assets/f2/0d/f20d364ad3bea30dd4c5c8c923be38d87c24af5c859368ade20f7e966c2fe527.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(39,'e87aadd816217db210b716b210d29d5dc1c70da07a05af52902845fad9117c5d','/uploads/assets/e8/7a/e87aadd816217db210b716b210d29d5dc1c70da07a05af52902845fad9117c5d.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(40,'5ad5071c52efc7bb545ba5352f99b956b2e0d071e17d8bc0e389212551d0c8e4','/uploads/assets/5a/d5/5ad5071c52efc7bb545ba5352f99b956b2e0d071e17d8bc0e389212551d0c8e4.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(41,'c3b42fdd26b3d83eee35474596a62556a85f68d3f3556eefb82ebba995c134f0','/uploads/assets/c3/b4/c3b42fdd26b3d83eee35474596a62556a85f68d3f3556eefb82ebba995c134f0.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(42,'016dd4d1a50da00b6e1bb8952781c6feb0605939a36af67794cedf53ced11559','/uploads/assets/01/6d/016dd4d1a50da00b6e1bb8952781c6feb0605939a36af67794cedf53ced11559.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(43,'a2594328035ab4babad9be87d0081e0339da1291827bfe3060365c0eb455cdc2','/uploads/assets/a2/59/a2594328035ab4babad9be87d0081e0339da1291827bfe3060365c0eb455cdc2.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(44,'97dae2faa7a65ef7d50160f56b916b876fa96866f04522c7567c34550cbf0437','/uploads/assets/97/da/97dae2faa7a65ef7d50160f56b916b876fa96866f04522c7567c34550cbf0437.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(45,'ffb2e9f10f7a4a083d11d01cafc33c4bfec14c262a8316a6539df99a04f40d1b','/uploads/assets/ff/b2/ffb2e9f10f7a4a083d11d01cafc33c4bfec14c262a8316a6539df99a04f40d1b.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(46,'6e8ddbb8df284393a1aca85e975f29da6df2d692e28c84b66c6df7e5f912ef1b','/uploads/assets/6e/8d/6e8ddbb8df284393a1aca85e975f29da6df2d692e28c84b66c6df7e5f912ef1b.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(47,'29b20956e84b8d3a4e4afb8a9fb381787877a0198fd265a11de37869466dcac9','/uploads/assets/29/b2/29b20956e84b8d3a4e4afb8a9fb381787877a0198fd265a11de37869466dcac9.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(48,'5e16067a324f992e75a3c03785044a84d952481237fd7e4d4a0d4328d38540fb','/uploads/assets/5e/16/5e16067a324f992e75a3c03785044a84d952481237fd7e4d4a0d4328d38540fb.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(49,'f9f32f8f7d1bcabd17a2f49399aa44e01cbd554e1461f63e3e32516b247c9b62','/uploads/assets/f9/f3/f9f32f8f7d1bcabd17a2f49399aa44e01cbd554e1461f63e3e32516b247c9b62.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(50,'91a91b701160ad60bbed9a23606518a45c6adf16b3c19790528dba87b6e8efa8','/uploads/assets/91/a9/91a91b701160ad60bbed9a23606518a45c6adf16b3c19790528dba87b6e8efa8.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(51,'0aff3fea713c82eae0631e34957f4469b13fbdb410fc0963bf2f2fd74861f6a7','/uploads/assets/0a/ff/0aff3fea713c82eae0631e34957f4469b13fbdb410fc0963bf2f2fd74861f6a7.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(52,'a2c67519c2999a431f87f3e01686edf9b7b3b7729bd9cc4568ce091b10c98fed','/uploads/assets/a2/c6/a2c67519c2999a431f87f3e01686edf9b7b3b7729bd9cc4568ce091b10c98fed.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(53,'2f5a7895dec437279ffca78cbf94c8115019c4ff83a0e79bc2ea5c09dd73528d','/uploads/assets/2f/5a/2f5a7895dec437279ffca78cbf94c8115019c4ff83a0e79bc2ea5c09dd73528d.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(54,'3e54cbc846144043353a7421ffcffcae0e3accc0bbc5d1a969cde2836a449c52','/uploads/assets/3e/54/3e54cbc846144043353a7421ffcffcae0e3accc0bbc5d1a969cde2836a449c52.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(55,'a96eae82e1927c87137546873b853cc07abed4bfa2c4b81c4cc3677ddcc8197b','/uploads/assets/a9/6e/a96eae82e1927c87137546873b853cc07abed4bfa2c4b81c4cc3677ddcc8197b.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(56,'c1f8610ee794264c2caf81679bf51d6599ce92d6a085e4bdf6bd9d92d36ff6f3','/uploads/assets/c1/f8/c1f8610ee794264c2caf81679bf51d6599ce92d6a085e4bdf6bd9d92d36ff6f3.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(57,'3c30f003c208fed81a2c2fc7f1a05375ca148035c5676870c5bb3b05fffd3df6','/uploads/assets/3c/30/3c30f003c208fed81a2c2fc7f1a05375ca148035c5676870c5bb3b05fffd3df6.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(58,'5cedd85b06e3bd18f19b27d129903d244c8f6f0effac6af3422917edbbcdb95b','/uploads/assets/5c/ed/5cedd85b06e3bd18f19b27d129903d244c8f6f0effac6af3422917edbbcdb95b.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(59,'43c3efa400ca3caee1dfdf355156e7b721f0bb40f5084cc7d71d2b55342b30db','/uploads/assets/43/c3/43c3efa400ca3caee1dfdf355156e7b721f0bb40f5084cc7d71d2b55342b30db.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(60,'179309c486b1f39da7bf5b54565e43a7fee177f0be5a366c3275a77bf56dd37f','/uploads/assets/17/93/179309c486b1f39da7bf5b54565e43a7fee177f0be5a366c3275a77bf56dd37f.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(61,'ad666e6db55037adaf759c429bf7d239ba229106b6c8bf2de3a86311eb2df016','/uploads/assets/ad/66/ad666e6db55037adaf759c429bf7d239ba229106b6c8bf2de3a86311eb2df016.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(62,'68783cb29ffc7e3360372b2de17d92430d05f37516eee4e2c4e39d03fe2a3934','/uploads/assets/68/78/68783cb29ffc7e3360372b2de17d92430d05f37516eee4e2c4e39d03fe2a3934.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(63,'ccb07311e88920e0dcc5c549aa1a4821ffd2f51ea3f7a50b1f7fd3f609617809','/uploads/assets/cc/b0/ccb07311e88920e0dcc5c549aa1a4821ffd2f51ea3f7a50b1f7fd3f609617809.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(64,'88cee73bf3375062925dac98f892f02a9841d94b78e51ce6764781a7ba79f184','/uploads/assets/88/ce/88cee73bf3375062925dac98f892f02a9841d94b78e51ce6764781a7ba79f184.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(65,'81adf046c63d0854292a1fb0e2c0d0d4363ed582348f5621c0f6455b29efec2c','/uploads/assets/81/ad/81adf046c63d0854292a1fb0e2c0d0d4363ed582348f5621c0f6455b29efec2c.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(66,'273363eda64474742e7e4a1611223ae9dd4f129ef89e40fb43b8d1371f364482','/uploads/assets/27/33/273363eda64474742e7e4a1611223ae9dd4f129ef89e40fb43b8d1371f364482.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(67,'905d56dbe521d8b1791ace6404286d145953f8f92b11f995197f6f942a86333e','/uploads/assets/90/5d/905d56dbe521d8b1791ace6404286d145953f8f92b11f995197f6f942a86333e.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(68,'b9cdeee25a08e5991419cdf2507ad0ba04fb7c1ba1ce27975938acdc4ec9564b','/uploads/assets/b9/cd/b9cdeee25a08e5991419cdf2507ad0ba04fb7c1ba1ce27975938acdc4ec9564b.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(69,'f8b849c55aff9dacab29a89af486e1121f9d8e072868b7b75e4028618d32dcbe','/uploads/assets/f8/b8/f8b849c55aff9dacab29a89af486e1121f9d8e072868b7b75e4028618d32dcbe.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(70,'6e8513a74065123b6d00a3154703823664de148ee46adc1ca87c7c73cf4b7def','/uploads/assets/6e/85/6e8513a74065123b6d00a3154703823664de148ee46adc1ca87c7c73cf4b7def.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(71,'aff5a53446a6eba4f438bf9434b876efcc981b7e497d08bf245b4abda7e2a345','/uploads/assets/af/f5/aff5a53446a6eba4f438bf9434b876efcc981b7e497d08bf245b4abda7e2a345.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(72,'726a81997941e9c2a874528b7722cac92b49daf6dcaf4445cda4299424d5236d','/uploads/assets/72/6a/726a81997941e9c2a874528b7722cac92b49daf6dcaf4445cda4299424d5236d.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(73,'4a1b3b47cc5d2afabf513345785b5415da854bfe8ff6663df3f5bb9402926733','/uploads/assets/4a/1b/4a1b3b47cc5d2afabf513345785b5415da854bfe8ff6663df3f5bb9402926733.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(74,'de6fd2750cb07dbc02de0a141ee8ad58752348b321360bbfd384ee94c89eb945','/uploads/assets/de/6f/de6fd2750cb07dbc02de0a141ee8ad58752348b321360bbfd384ee94c89eb945.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(75,'737df0b494d3885c1288ffaf19a76bbf66a273ad581d27e31b47ae9bbd693a00','/uploads/assets/73/7d/737df0b494d3885c1288ffaf19a76bbf66a273ad581d27e31b47ae9bbd693a00.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(76,'6dcc1c02b826f1b58f3ca513ea1216c299f9a092d6dca64c4f9a124b2a0be372','/uploads/assets/6d/cc/6dcc1c02b826f1b58f3ca513ea1216c299f9a092d6dca64c4f9a124b2a0be372.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(77,'10346197da06143c0a3b5916ea351d61ca6ea9a04da6704d5145be098d42ef0c','/uploads/assets/10/34/10346197da06143c0a3b5916ea351d61ca6ea9a04da6704d5145be098d42ef0c.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(78,'05e0fe295a87681f5292360ff13c48902ac44a282a4b0a562db197e364cc09db','/uploads/assets/05/e0/05e0fe295a87681f5292360ff13c48902ac44a282a4b0a562db197e364cc09db.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(79,'2a7024d2cab4b5704cd72ef6e6114290944007908807b9b1e51c248ab780fa3f','/uploads/assets/2a/70/2a7024d2cab4b5704cd72ef6e6114290944007908807b9b1e51c248ab780fa3f.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(80,'6967636f09e2e698cf387352d86d1543211d8c13fc1e40bdc7de4f2e2f4f7ac4','/uploads/assets/69/67/6967636f09e2e698cf387352d86d1543211d8c13fc1e40bdc7de4f2e2f4f7ac4.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(81,'47bafb0e479364de9f10754e08c36ab6647456afc29da5605037da91d51c762f','/uploads/assets/47/ba/47bafb0e479364de9f10754e08c36ab6647456afc29da5605037da91d51c762f.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(82,'614aa9c4ccad0b201ab28e22d1bbdf5d877e780354e3cfd09673e50eafc331d9','/uploads/assets/61/4a/614aa9c4ccad0b201ab28e22d1bbdf5d877e780354e3cfd09673e50eafc331d9.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(83,'6bbd1d1b2bd3813cebd02d65f923bc63ec1efd8616b24ab556cb0e91637c133f','/uploads/assets/6b/bd/6bbd1d1b2bd3813cebd02d65f923bc63ec1efd8616b24ab556cb0e91637c133f.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(84,'e22cdb08ac74c5f85bd00300687bf9e944df9652e2369d0c45797436f84a82e7','/uploads/assets/e2/2c/e22cdb08ac74c5f85bd00300687bf9e944df9652e2369d0c45797436f84a82e7.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(85,'5e4f57891aeb8d04d31525b107c7f8383d394e9c81d2180689a62f8ef534abe7','/uploads/assets/5e/4f/5e4f57891aeb8d04d31525b107c7f8383d394e9c81d2180689a62f8ef534abe7.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(86,'f7e60426c86527f7e9ff145ab8d9858278de904d6c47c676a20a9d95cbee46ba','/uploads/assets/f7/e6/f7e60426c86527f7e9ff145ab8d9858278de904d6c47c676a20a9d95cbee46ba.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(87,'2dbc5aead04201a65172de42be8110bc34008dd5f2d32649f0b49db36361cb35','/uploads/assets/2d/bc/2dbc5aead04201a65172de42be8110bc34008dd5f2d32649f0b49db36361cb35.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(88,'45cb9e54766be7ca06f11018b24c5becbe49b761b9f8042a0113d79fef28e393','/uploads/assets/45/cb/45cb9e54766be7ca06f11018b24c5becbe49b761b9f8042a0113d79fef28e393.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(89,'082710e695ae49581885d6fc2ca7e3ccea21c6db12f423608ecb28a938426fb8','/uploads/assets/08/27/082710e695ae49581885d6fc2ca7e3ccea21c6db12f423608ecb28a938426fb8.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(90,'c385d83a18525c81d1462a73dc46ff1bd8d8fd798af426c761967010e7161fba','/uploads/assets/c3/85/c385d83a18525c81d1462a73dc46ff1bd8d8fd798af426c761967010e7161fba.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(91,'5a062a0af38d40cd8136d22c95c53c9bebbec9efdd1c43edffd2e9756ae981f0','/uploads/assets/5a/06/5a062a0af38d40cd8136d22c95c53c9bebbec9efdd1c43edffd2e9756ae981f0.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(92,'589e37baccef475fc76fd4265ea693760696d36c7b67cc21fa5a551034241b14','/uploads/assets/58/9e/589e37baccef475fc76fd4265ea693760696d36c7b67cc21fa5a551034241b14.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(93,'22a3e7968f0a3adb8ec73b7d87c289c7efdb7756bec73e6d2b0628d91e64f0aa','/uploads/assets/22/a3/22a3e7968f0a3adb8ec73b7d87c289c7efdb7756bec73e6d2b0628d91e64f0aa.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(94,'0732e9120e0664fab6ae1ed96681e60ffbb22334c13262d49fdc741332d681c7','/uploads/assets/07/32/0732e9120e0664fab6ae1ed96681e60ffbb22334c13262d49fdc741332d681c7.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(95,'7f79ca34fc147bf5ac2038318edd1063aa406031514a04b86d011d0b26ef33cc','/uploads/assets/7f/79/7f79ca34fc147bf5ac2038318edd1063aa406031514a04b86d011d0b26ef33cc.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(96,'04769655a9e740b8c179bd0c7efec4684264b53cf0117ec095a89121d6b90c98','/uploads/assets/04/76/04769655a9e740b8c179bd0c7efec4684264b53cf0117ec095a89121d6b90c98.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(97,'4c0cc7225185be4b3a0a9efa2f5152045eca6d62b10db03f827304d0375c0ace','/uploads/assets/4c/0c/4c0cc7225185be4b3a0a9efa2f5152045eca6d62b10db03f827304d0375c0ace.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(98,'12f4d2081a754fe17bbfc50f28ae64742747044748ecf80f0acc7f3279b8938e','/uploads/assets/12/f4/12f4d2081a754fe17bbfc50f28ae64742747044748ecf80f0acc7f3279b8938e.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(99,'1e95ebf7dc99582dcb15237a78c61e5388800c50a70b185c291a755e731dfcac','/uploads/assets/1e/95/1e95ebf7dc99582dcb15237a78c61e5388800c50a70b185c291a755e731dfcac.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34'),
(100,'d26be7847410f90f70ef06edfa8be40e9d73fbe36e6a8d7b420a1cfe26a98e6a','/uploads/assets/d2/6b/d26be7847410f90f70ef06edfa8be40e9d73fbe36e6a8d7b420a1cfe26a98e6a.jpg','image/jpeg',800,600,0,'2026-01-03 23:36:34');
/*!40000 ALTER TABLE `ADSHOWCASE_asset_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ADSHOWCASE_audit_log`
--

DROP TABLE IF EXISTS `ADSHOWCASE_audit_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ADSHOWCASE_audit_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `entity` varchar(100) NOT NULL,
  `entity_id` bigint(20) NOT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`meta`)),
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_audit_entity` (`entity`,`entity_id`),
  KEY `fk_audit_user` (`user_id`),
  CONSTRAINT `fk_audit_user` FOREIGN KEY (`user_id`) REFERENCES `ADSHOWCASE_user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ADSHOWCASE_audit_log`
--

LOCK TABLES `ADSHOWCASE_audit_log` WRITE;
/*!40000 ALTER TABLE `ADSHOWCASE_audit_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `ADSHOWCASE_audit_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ADSHOWCASE_auth_assignment`
--

DROP TABLE IF EXISTS `ADSHOWCASE_auth_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ADSHOWCASE_auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `ADSHOWCASE_idx-auth_assignment-user_id` (`user_id`),
  CONSTRAINT `ADSHOWCASE_auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `ADSHOWCASE_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ADSHOWCASE_auth_assignment`
--

LOCK TABLES `ADSHOWCASE_auth_assignment` WRITE;
/*!40000 ALTER TABLE `ADSHOWCASE_auth_assignment` DISABLE KEYS */;
INSERT INTO `ADSHOWCASE_auth_assignment` VALUES
('admin','1',1767483391),
('editor','2',1767483392),
('sales','3',1767483392),
('viewer','4',1767483393);
/*!40000 ALTER TABLE `ADSHOWCASE_auth_assignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ADSHOWCASE_auth_item`
--

DROP TABLE IF EXISTS `ADSHOWCASE_auth_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ADSHOWCASE_auth_item` (
  `name` varchar(64) NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text DEFAULT NULL,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` blob DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `ADSHOWCASE_idx-auth_item-type` (`type`),
  CONSTRAINT `ADSHOWCASE_auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `ADSHOWCASE_auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ADSHOWCASE_auth_item`
--

LOCK TABLES `ADSHOWCASE_auth_item` WRITE;
/*!40000 ALTER TABLE `ADSHOWCASE_auth_item` DISABLE KEYS */;
INSERT INTO `ADSHOWCASE_auth_item` VALUES
('admin',1,'Administrator',NULL,NULL,1767483391,1767483391),
('audit.view',2,'Ver auditoría',NULL,NULL,1767483391,1767483391),
('backoffice.access',2,'Entrar al backoffice',NULL,NULL,1767483391,1767483391),
('creative.manage',2,'Crear/editar/eliminar creatives',NULL,NULL,1767483391,1767483391),
('creative.view',2,'Ver creatives y metadatos',NULL,NULL,1767483391,1767483391),
('editor',1,'Editor',NULL,NULL,1767483391,1767483391),
('favorite.manage',2,'Gestión de favoritos',NULL,NULL,1767483391,1767483391),
('sales',1,'Sales',NULL,NULL,1767483391,1767483391),
('share.manage',2,'Gestión de enlaces compartidos',NULL,NULL,1767483391,1767483391),
('taxonomies.manage',2,'Gestión de taxonomías',NULL,NULL,1767483391,1767483391),
('users.manage',2,'Gestión de usuarios',NULL,NULL,1767483391,1767483391),
('viewer',1,'Viewer',NULL,NULL,1767483391,1767483391);
/*!40000 ALTER TABLE `ADSHOWCASE_auth_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ADSHOWCASE_auth_item_child`
--

DROP TABLE IF EXISTS `ADSHOWCASE_auth_item_child`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ADSHOWCASE_auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `ADSHOWCASE_auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `ADSHOWCASE_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ADSHOWCASE_auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `ADSHOWCASE_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ADSHOWCASE_auth_item_child`
--

LOCK TABLES `ADSHOWCASE_auth_item_child` WRITE;
/*!40000 ALTER TABLE `ADSHOWCASE_auth_item_child` DISABLE KEYS */;
INSERT INTO `ADSHOWCASE_auth_item_child` VALUES
('admin','audit.view'),
('admin','backoffice.access'),
('editor','backoffice.access'),
('admin','creative.manage'),
('editor','creative.manage'),
('admin','creative.view'),
('editor','creative.view'),
('sales','creative.view'),
('viewer','creative.view'),
('admin','favorite.manage'),
('editor','favorite.manage'),
('sales','favorite.manage'),
('admin','share.manage'),
('editor','share.manage'),
('sales','share.manage'),
('admin','taxonomies.manage'),
('admin','users.manage');
/*!40000 ALTER TABLE `ADSHOWCASE_auth_item_child` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ADSHOWCASE_auth_rule`
--

DROP TABLE IF EXISTS `ADSHOWCASE_auth_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ADSHOWCASE_auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` blob DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ADSHOWCASE_auth_rule`
--

LOCK TABLES `ADSHOWCASE_auth_rule` WRITE;
/*!40000 ALTER TABLE `ADSHOWCASE_auth_rule` DISABLE KEYS */;
/*!40000 ALTER TABLE `ADSHOWCASE_auth_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ADSHOWCASE_brand`
--

DROP TABLE IF EXISTS `ADSHOWCASE_brand`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ADSHOWCASE_brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` char(16) NOT NULL,
  `name` varchar(255) NOT NULL,
  `url_slug` varchar(255) NOT NULL,
  `status` enum('active','archived','pending') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `url_slug` (`url_slug`)
) ENGINE=InnoDB AUTO_INCREMENT=501 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ADSHOWCASE_brand`
--

LOCK TABLES `ADSHOWCASE_brand` WRITE;
/*!40000 ALTER TABLE `ADSHOWCASE_brand` DISABLE KEYS */;
INSERT INTO `ADSHOWCASE_brand` VALUES
(1,'0nfy7kIrmIF-yE37','Apple','apple','active','2025-06-29 19:11:39','2025-08-06 02:08:36'),
(2,'IuYTpoUUtGbVZiVg','Samsung','samsung','active','2025-07-22 01:26:54','2025-08-29 12:32:51'),
(3,'_vopDNb4SAp3IwDS','Google','google','active','2025-06-13 20:28:47','2025-07-18 08:37:48'),
(4,'9HhW9WiTMO4Q5u1A','Microsoft','microsoft','archived','2025-03-02 09:35:07','2025-03-26 00:39:05'),
(5,'uwYdv07MczNtbpzx','Sony','sony','active','2025-06-04 19:02:15','2025-07-15 21:16:27'),
(6,'2MZR6wpxKXl3irYJ','Dell','dell','active','2025-01-10 20:45:41','2025-02-02 06:24:56'),
(7,'_cplxRHoZAo8iL-L','HP','hp','active','2025-02-22 21:53:59','2025-03-23 04:35:30'),
(8,'KzVF_o9BZtK41MVd','Lenovo','lenovo','archived','2025-04-14 22:26:29','2025-06-01 18:14:20'),
(9,'l0bwmu9_5HmudPMX','LG','lg','active','2025-04-15 03:50:44','2025-04-24 07:31:28'),
(10,'FbrwzPb0IGcnkdZe','Panasonic','panasonic','active','2026-01-01 02:49:16','2026-02-07 04:40:21'),
(11,'jTjbLsIEiI6GxD0F','Intel','intel','pending','2025-11-04 20:25:26','2025-12-28 17:31:58'),
(12,'rCyESKOjEsJt0UIE','Nvidia','nvidia','active','2025-04-22 14:19:25','2025-06-05 08:12:53'),
(13,'0qJ_IlfqUXe6htiO','AMD','amd','active','2025-06-18 01:28:06','2025-07-12 07:36:35'),
(14,'RmFMuIPkGk_Z9IfC','Cisco','cisco','active','2025-08-09 01:37:58','2025-09-18 05:35:37'),
(15,'Fn6v8FH_4ZEChWjG','Oracle','oracle','archived','2025-08-12 09:37:06','2025-09-18 15:27:53'),
(16,'d9mUaZirCpmXu7lg','IBM','ibm','active','2025-01-21 18:49:34','2025-02-10 09:25:43'),
(17,'Ar5GGHOdjmE_ToSX','Adobe','adobe','pending','2025-12-30 05:07:09','2026-01-29 16:52:24'),
(18,'r9RxUpq0Kav_GIOB','Salesforce','salesforce','archived','2025-11-22 14:09:55','2025-12-27 16:31:03'),
(19,'pbycbecufhJjdbuW','SAP','sap','pending','2025-08-02 00:22:30','2025-08-26 20:28:55'),
(20,'zzWKOyOUlNYSRbaW','Spotify','spotify','archived','2025-11-29 11:28:18','2025-12-30 04:04:35'),
(21,'PuG7Y23I5JGPl76v','Netflix','netflix','active','2025-11-10 15:33:48','2025-12-09 14:40:32'),
(22,'CpWmaWZS9af9pdR2','Meta','meta','active','2025-08-01 12:12:57','2025-09-24 07:47:56'),
(23,'OzYVhWV7Q4sFv0Zu','TikTok','tiktok','active','2025-09-29 05:59:54','2025-10-22 19:29:41'),
(24,'vtE2SRBmbw6nklEE','Uber','uber','active','2025-06-04 18:30:35','2025-06-07 01:13:26'),
(25,'zz-Cmm8S155uUdou','Airbnb','airbnb','active','2025-08-30 23:24:05','2025-09-19 07:53:49'),
(26,'aVaIwf9j5RGt_qTh','Tesla','tesla','archived','2025-01-24 02:25:51','2025-02-08 08:14:38'),
(27,'PB4qnLDi5yOvPubg','SpaceX','spacex','archived','2025-06-22 05:38:48','2025-08-05 20:31:44'),
(28,'3AwMfdN7_eIR5RgZ','Garmin','garmin','active','2025-10-17 21:31:38','2025-12-14 16:54:19'),
(29,'UiQOiTzCai_j_KpR','Canon','canon','active','2025-12-20 06:01:06','2026-01-10 07:54:55'),
(30,'EilnPo391rdqHRPC','Nikon','nikon','active','2025-11-06 00:37:57','2025-12-15 13:01:42'),
(31,'5vEltKbeo8_cUQeB','GoPro','gopro','archived','2025-03-19 02:25:19','2025-05-01 08:10:54'),
(32,'Y5orVUpEOrmChjbw','Dyson','dyson','active','2025-01-24 03:01:45','2025-02-05 03:14:34'),
(33,'uivlkvRk55RQ4B9j','Bose','bose','active','2025-10-17 20:25:15','2025-12-09 23:36:59'),
(34,'UCAKHL4tx997LlfV','Sonos','sonos','active','2025-02-08 22:37:53','2025-03-13 02:52:36'),
(35,'MigHoMyA3cT2Yav1','Nintendo','nintendo','active','2025-09-16 15:49:06','2025-10-10 12:01:58'),
(36,'x7D_-Axrzl5oSq4I','PlayStation','playstation','active','2025-04-16 09:44:24','2025-06-06 09:22:18'),
(37,'zUMn7m5FjNzYUkCI','Xbox','xbox','active','2025-06-17 13:33:10','2025-08-07 13:49:42'),
(38,'07qZBz5XLWxucnx-','Razer','razer','active','2025-05-31 21:34:43','2025-06-03 10:28:51'),
(39,'mL1726iZVe5SN8TO','Logitech','logitech','pending','2025-12-20 23:58:07','2025-12-27 13:02:55'),
(40,'YLGxnjhVCZvPhXH7','Toyota','toyota','archived','2025-12-13 21:32:54','2026-01-23 13:00:16'),
(41,'4BS1rgnFEMgZMQNK','Volkswagen','volkswagen','archived','2025-07-12 07:28:11','2025-07-17 02:45:54'),
(42,'oMWeJlNq4pGyvV-9','Ford','ford','active','2025-12-19 12:40:36','2026-01-19 09:36:19'),
(43,'rn48rVrnyjXrgWnZ','Honda','honda','pending','2025-10-14 14:33:03','2025-11-30 22:25:09'),
(44,'olOEE2HeqBEDgYV4','BMW','bmw','active','2025-02-03 21:16:53','2025-03-09 13:35:49'),
(45,'npHDFGAERvWEtZBb','Mercedes-Benz','mercedes-benz','active','2025-05-23 07:55:38','2025-06-06 23:04:29'),
(46,'k0GefjoDgJsuS0HG','Audi','audi','active','2025-11-25 07:45:57','2026-01-18 03:17:09'),
(47,'owp1fk-O6ZebEbVo','Porsche','porsche','active','2025-06-21 07:14:04','2025-08-19 09:47:03'),
(48,'dfe5Kg6gH06FZYJo','Ferrari','ferrari','pending','2025-03-11 00:10:14','2025-03-28 20:58:20'),
(49,'WqUgKwJ6swolt08Z','Lamborghini','lamborghini','active','2025-02-04 21:49:55','2025-02-07 21:24:20'),
(50,'-N4bmF-wOIQ9cdYR','Hyundai','hyundai','archived','2025-01-14 23:22:18','2025-02-13 16:42:48'),
(51,'HCwaEMwTb9BxC6uH','Kia','kia','active','2025-08-16 19:58:21','2025-10-02 15:51:23'),
(52,'P6czn5kb9k1MGoxi','Nissan','nissan','active','2025-12-11 20:03:25','2025-12-16 07:50:16'),
(53,'EQr3OeXZoj-o0PfE','Chevrolet','chevrolet','pending','2025-06-30 21:37:58','2025-07-29 17:20:59'),
(54,'cTpvixpyYhRL4XH8','Jeep','jeep','archived','2025-11-23 09:15:20','2025-12-14 11:58:02'),
(55,'GfhGpKfGoVNAhrk4','Land Rover','land-rover','active','2025-02-25 17:14:44','2025-04-05 21:48:05'),
(56,'ZmAl0D5WhOpj90RG','Volvo','volvo','active','2025-11-14 08:53:45','2025-12-05 05:02:56'),
(57,'3SCy-xFYPMBTL8yC','Lexus','lexus','active','2025-09-12 15:26:59','2025-09-12 23:36:55'),
(58,'n3E2Ux0_7Lk3-_Bw','Mazda','mazda','active','2025-09-06 01:39:06','2025-09-28 18:40:31'),
(59,'TbZbqxj2Pf9n5s7Z','Subaru','subaru','active','2025-05-04 05:30:48','2025-05-09 23:29:29'),
(60,'T_VlyojwvjIqqRan','Yamaha','yamaha','active','2025-12-30 03:54:27','2026-01-22 15:45:29'),
(61,'OcTWQX9lQk0htBH1','Ducati','ducati','active','2025-07-21 04:24:48','2025-09-05 05:37:48'),
(62,'3Zc7DlGHDfcPjpyR','Harley-Davidson','harley-davidson','active','2025-01-15 21:22:13','2025-03-02 07:51:07'),
(63,'u6Z50JMKN8zaAat_','Nike','nike','active','2025-03-18 16:57:09','2025-04-28 18:49:45'),
(64,'lvrIGT3ZttJVZmvA','Adidas','adidas','active','2025-07-03 18:15:41','2025-07-04 14:33:12'),
(65,'qCf9z1BdPSjcnOl4','Puma','puma','pending','2025-08-19 15:26:16','2025-10-09 18:58:27'),
(66,'8ElSUgvMUJ1Hv6KW','Under Armour','under-armour','active','2025-07-21 00:17:27','2025-09-05 07:53:39'),
(67,'a0hK0kAP0ADTYPP8','New Balance','new-balance','active','2025-09-30 03:37:17','2025-11-12 16:59:40'),
(68,'T-JeNgYW1XZShdYh','Reebok','reebok','active','2025-12-11 11:46:26','2026-01-02 15:13:22'),
(69,'djG5hJsK_mqlbR06','Zara','zara','active','2025-09-07 04:20:02','2025-09-23 02:15:33'),
(70,'g1DUc16rtrSc5I5A','H&M','hm','active','2025-10-21 17:36:02','2025-11-17 09:35:46'),
(71,'4zHLg1FygS-vIvAd','Uniqlo','uniqlo','pending','2026-01-01 05:27:31','2026-01-23 04:40:50'),
(72,'UGELM2XSViZcss6D','Levi\'s','levis','active','2025-12-13 22:26:50','2026-02-09 00:00:04'),
(73,'NLkSbLmCWDIB53FI','Gap','gap','active','2025-12-01 23:34:43','2026-01-28 06:56:28'),
(74,'9H_zgpkKwFFgy25D','Calvin Klein','calvin-klein','active','2025-07-10 02:03:45','2025-08-04 15:11:18'),
(75,'R0paR7w8FEafgIoL','Tommy Hilfiger','tommy-hilfiger','active','2025-09-11 03:34:12','2025-10-24 02:46:27'),
(76,'Fxx6EiAlcpc22D0l','Ralph Lauren','ralph-lauren','active','2025-07-18 09:28:29','2025-08-17 10:03:15'),
(77,'5nCmPZF3woRYVG_V','Lacoste','lacoste','active','2025-06-27 23:52:14','2025-08-18 16:08:39'),
(78,'huUp_8JyxACt2unE','The North Face','the-north-face','archived','2025-04-06 09:57:01','2025-05-16 04:14:09'),
(79,'jgGYyLIdKViu1sB-','Patagonia','patagonia','pending','2025-06-28 15:12:33','2025-07-13 09:23:01'),
(80,'rWKL1o1PevJwvu_n','Columbia','columbia','active','2025-05-25 23:39:35','2025-07-03 23:35:04'),
(81,'MGUEQ6EIGIrTbepR','Vans','vans','active','2025-02-18 20:09:53','2025-04-19 18:52:29'),
(82,'DJsHvBtwGcGkQ79l','Converse','converse','pending','2025-05-28 13:20:22','2025-06-04 07:56:55'),
(83,'d2H6n6jsJMwu3IHh','Dr. Martens','dr-martens','active','2025-06-30 19:45:55','2025-08-27 00:13:18'),
(84,'J3talCj2R2Cvkbnm','Timberland','timberland','active','2025-11-19 04:11:10','2025-11-30 19:28:57'),
(85,'jMsi9sDJSem8Mj2W','Louis Vuitton','louis-vuitton','active','2025-02-24 05:41:19','2025-03-06 06:08:09'),
(86,'EnptN3L8r0562UX5','Gucci','gucci','active','2025-10-28 17:56:32','2025-12-12 02:07:49'),
(87,'CK6OkTXBfHnFXihB','Chanel','chanel','active','2025-09-09 03:48:25','2025-10-16 23:18:42'),
(88,'phEeDqLwfRcih6LB','Hermès','hermes','active','2025-09-11 09:25:34','2025-09-17 18:57:24'),
(89,'OH6JF3KhoevZV5XJ','Prada','prada','pending','2025-04-11 02:32:39','2025-04-25 10:38:56'),
(90,'80LBTng_Nghbq-VT','Dior','dior','active','2025-09-11 04:52:23','2025-11-06 12:57:25'),
(91,'UY3aYuE4fN83MH7X','Rolex','rolex','active','2025-11-01 17:24:09','2025-11-28 11:26:39'),
(92,'uhq7y2ph3gHvHufJ','Cartier','cartier','active','2025-01-26 07:48:30','2025-03-18 03:07:26'),
(93,'L_axPtgJX3dKrpz7','Tiffany & Co.','tiffany-co','pending','2025-08-01 14:33:04','2025-09-12 18:30:28'),
(94,'mO0vafeNBOaHID65','Burberry','burberry','active','2025-01-26 11:04:10','2025-03-13 00:25:14'),
(95,'r3iMQugbKnM-u-IC','Versace','versace','active','2025-09-26 04:58:01','2025-10-19 17:41:34'),
(96,'sNuRVitPI0J5kGsq','Armani','armani','archived','2025-10-10 03:25:51','2025-11-22 12:37:19'),
(97,'StaDNUcgdcKGknh4','Balenciaga','balenciaga','active','2025-08-16 05:40:20','2025-08-30 15:03:32'),
(98,'sLWh4o7vnxoIEN8d','Saint Laurent','saint-laurent','active','2025-10-02 02:43:10','2025-11-05 19:47:28'),
(99,'P7LQBQlAEMAVw0yU','Omega','omega','archived','2025-10-03 06:10:33','2025-10-09 01:38:48'),
(100,'O20s3fnqd-So5xwI','Tag Heuer','tag-heuer','archived','2025-10-29 02:40:44','2025-12-21 00:31:26'),
(101,'ZZ4H_TZD_miOEqxk','Coca-Cola','coca-cola','active','2025-08-23 04:40:09','2025-10-01 11:50:16'),
(102,'BN7xKRqXaGFc1U1q','Pepsi','pepsi','active','2025-04-20 11:08:32','2025-06-17 07:23:53'),
(103,'w7H07gmM3ZO9roCX','Red Bull','red-bull','active','2025-06-04 14:10:05','2025-07-04 14:59:54'),
(104,'C6BDNH6yxH5rD0rb','Nestlé','nestle','active','2025-07-23 14:03:14','2025-09-15 16:38:34'),
(105,'Nd177ORsbm__XLNP','Danone','danone','active','2025-05-14 16:50:08','2025-06-17 11:09:55'),
(106,'Ly_-jr-h-guvMqRs','Kellogg\'s','kelloggs','active','2025-10-28 18:34:58','2025-12-10 12:40:43'),
(107,'up8OQZMAy-7ohYH5','General Mills','general-mills','pending','2025-11-13 06:44:29','2025-12-22 21:22:56'),
(108,'yhagEyiojD1u0u4j','Kraft Heinz','kraft-heinz','active','2025-11-28 12:22:40','2025-12-17 14:30:33'),
(109,'xBWL6TZpKDeHM4g_','Mars','mars','active','2025-09-01 05:48:15','2025-10-15 08:13:20'),
(110,'QcFWcnkTfuEmBpSY','Hershey\'s','hersheys','active','2025-12-05 05:52:34','2026-02-02 02:56:05'),
(111,'lcoBIS6Q4_I3iCsB','Ferrero','ferrero','active','2025-10-17 14:59:13','2025-10-21 03:55:09'),
(112,'yWXTLqMBK7c8jPKm','Mondelez','mondelez','active','2025-03-19 07:52:30','2025-04-08 02:43:14'),
(113,'A6cBEHneMZeOOjI1','Unilever','unilever','active','2025-08-24 09:17:48','2025-09-12 17:05:56'),
(114,'BaXlHrOqPE74-UD_','Starbucks','starbucks','active','2025-12-10 19:56:36','2026-01-24 14:09:22'),
(115,'i4Ou7LofvfLtoo9Y','McDonald\'s','mcdonalds','active','2025-07-18 19:11:47','2025-08-25 19:55:50'),
(116,'Iwe0FijOWmhNouSe','Burger King','burger-king','active','2025-02-12 04:43:29','2025-03-03 02:44:23'),
(117,'5XJPvjcTRnHQ0SxJ','KFC','kfc','active','2025-04-21 09:36:50','2025-05-20 23:56:45'),
(118,'fbi7beoGlCMhfwVI','Subway','subway','archived','2025-12-14 22:50:02','2026-02-05 11:53:31'),
(119,'MlZyJeSP2uqNTGmD','Domino\'s','dominos','pending','2025-03-30 06:20:59','2025-04-25 10:21:36'),
(120,'GpQiNk0DCIDQOmaA','Pizza Hut','pizza-hut','active','2025-11-03 06:29:25','2025-12-10 05:15:27'),
(121,'AQbLm4TdGyQ2ALYw','Taco Bell','taco-bell','active','2025-07-13 22:55:48','2025-08-14 21:22:38'),
(122,'VGAZCt4QDTaEN2xD','Heineken','heineken','pending','2025-10-15 06:05:46','2025-10-28 23:10:53'),
(123,'BxDE9InVDPwAR4xY','Budweiser','budweiser','active','2025-02-16 10:22:39','2025-04-09 10:55:09'),
(124,'I3XXkruqK4RVF0Gn','Corona','corona','active','2025-03-14 01:58:01','2025-04-04 22:41:14'),
(125,'e88lxj-Rn7xIVpN0','Jack Daniel\'s','jack-daniels','active','2025-10-12 03:49:51','2025-10-17 17:54:00'),
(126,'ec8pZtCDSBqSkD3b','Johnnie Walker','johnnie-walker','active','2025-03-21 02:18:45','2025-05-15 22:24:51'),
(127,'jXM6qisU9zRNkjZL','Nespresso','nespresso','active','2025-08-17 12:53:06','2025-08-31 17:23:03'),
(128,'EG_oc4tZVXnlK6fg','Lipton','lipton','archived','2025-01-11 07:41:39','2025-02-20 15:14:46'),
(129,'9bIL0NFqopJ4Nock','L\'Oréal','loreal','archived','2025-08-18 13:18:01','2025-08-18 22:04:33'),
(130,'_BRUL5n3h7-FizGP','Estée Lauder','estee-lauder','pending','2025-02-09 10:14:11','2025-03-06 21:35:03'),
(131,'iFKoi4Fk5N9UoQOh','Nivea','nivea','pending','2025-05-03 15:09:36','2025-06-11 11:25:19'),
(132,'naqI7-oGN3-SOk70','Dove','dove','active','2025-02-28 05:30:36','2025-04-05 07:04:04'),
(133,'gLBIYD43EA9Sj1vC','Gillette','gillette','active','2025-05-22 16:20:02','2025-07-14 11:54:48'),
(134,'Tz7CyqDjOI3d_sJl','Colgate','colgate','active','2025-09-05 18:54:12','2025-09-28 22:46:01'),
(135,'ZbwcgXgINMoXdF1a','Oral-B','oral-b','archived','2025-11-21 02:47:09','2025-12-15 15:50:38'),
(136,'1MBiliV3bn-YfriV','Pantene','pantene','active','2025-07-13 06:22:03','2025-08-07 19:14:24'),
(137,'96KHO6DjveEmOv-g','Head & Shoulders','head-shoulders','active','2025-05-21 13:28:15','2025-06-19 11:16:52'),
(138,'Mmb9dQPzKW73c8_3','Garnier','garnier','active','2025-11-05 05:39:16','2025-11-28 06:44:17'),
(139,'dt2WljGQWVWI3eVb','Maybelline','maybelline','active','2025-10-01 19:48:23','2025-11-19 15:22:26'),
(140,'s3fScot98uPFyhZ0','MAC Cosmetics','mac-cosmetics','pending','2025-11-23 19:08:51','2025-12-25 16:48:38'),
(141,'KOOw49g_vbnvJwgq','Sephora','sephora','archived','2025-04-05 10:56:14','2025-04-21 16:06:46'),
(142,'G21SXmDyABVg3zX8','Lush','lush','active','2025-06-27 21:35:20','2025-08-01 05:14:23'),
(143,'3DkFp0BDgjIfDOJt','Amazon','amazon','active','2025-12-26 09:54:10','2026-01-15 22:35:13'),
(144,'725Sm3TM6ovHhzVU','Walmart','walmart','active','2025-10-09 23:55:27','2025-10-30 10:46:54'),
(145,'11hau9KsDHUow1Kz','Target','target','archived','2025-03-18 02:07:34','2025-04-13 03:31:12'),
(146,'9_9LYYKvu_V7F4ex','Costco','costco','active','2025-07-20 12:40:00','2025-07-26 05:30:09'),
(147,'7tObfIdnAPcc8h-n','IKEA','ikea','active','2025-08-12 21:11:23','2025-08-17 12:45:39'),
(148,'9nf8EHwIQY1xajGm','Home Depot','home-depot','active','2025-08-05 02:20:10','2025-08-29 22:12:47'),
(149,'-XasHptwLjzFqv2h','Best Buy','best-buy','archived','2025-08-28 01:41:25','2025-08-31 03:05:54'),
(150,'sqZkX9w4YAa771rH','Alibaba','alibaba','active','2025-04-10 18:04:17','2025-05-04 15:32:49'),
(151,'51yK3bkZaiie6HWU','eBay','ebay','active','2025-04-11 04:15:43','2025-06-09 02:13:33'),
(152,'6P_cBvtKU3D2Wsw5','Etsy','etsy','active','2025-10-04 20:09:56','2025-12-01 06:31:04'),
(153,'oDI-_46G-wdgxSKx','Shopify','shopify','active','2025-02-06 21:36:29','2025-03-21 07:11:21'),
(154,'GVGxlbMuoYI8svzB','Visa','visa','active','2025-09-21 20:02:05','2025-10-06 15:03:52'),
(155,'7JunvkJ4Dry76dpw','Mastercard','mastercard','pending','2025-09-09 11:41:15','2025-09-19 15:08:02'),
(156,'5-63O2AiHr-FXhcv','American Express','american-express','active','2025-06-25 11:01:04','2025-07-04 01:11:13'),
(157,'gf7gnuePzIlI1B3T','PayPal','paypal','active','2025-11-14 01:31:22','2025-12-19 11:57:35'),
(158,'62CYVX3iCySPORiY','JP Morgan','jp-morgan','active','2025-10-15 09:57:07','2025-12-01 03:23:03'),
(159,'oWs4uv-51mtgbv6L','Goldman Sachs','goldman-sachs','active','2025-04-19 14:13:00','2025-05-08 04:59:48'),
(160,'Iwn579fx630IrkLu','HSBC','hsbc','archived','2025-04-08 15:05:31','2025-04-15 22:50:41'),
(161,'LR3CBtrUSx07K-W0','Allianz','allianz','archived','2025-01-30 20:51:11','2025-02-12 22:07:15'),
(162,'YH-6Dy0-RAdOBBSI','AXA','axa','active','2025-08-25 07:50:59','2025-09-10 12:56:24'),
(163,'NL7HqcfWuazn92Af','Santander','santander','active','2025-11-13 08:39:07','2025-12-14 06:20:56'),
(164,'caOOYG81BW1dzdEH','BBVA','bbva','active','2025-08-31 21:35:36','2025-09-28 03:20:48'),
(165,'pX7J_gwj5_WU6p8f','Delta','delta','active','2025-02-19 05:28:35','2025-04-11 14:36:35'),
(166,'TimcRuhmv1D4SV9S','American Airlines','american-airlines','active','2025-10-04 04:09:19','2025-11-13 19:45:22'),
(167,'WsvmKHiOxyJyFu-m','United Airlines','united-airlines','active','2025-07-04 04:51:45','2025-08-10 10:37:46'),
(168,'343-8IH5DUnsYOMf','Emirates','emirates','archived','2025-11-04 16:30:24','2025-11-10 22:11:54'),
(169,'D0enmhfJcpHYwWSD','Lufthansa','lufthansa','active','2025-11-09 23:16:08','2026-01-05 07:00:52'),
(170,'GIWpdAnnjYPIflNk','British Airways','british-airways','active','2025-11-03 07:05:37','2025-11-07 13:08:19'),
(171,'BNq48Hdtbo7vVSAz','Air France','air-france','pending','2025-03-08 12:47:21','2025-03-15 23:50:50'),
(172,'lCpRna5zzuWWvjJW','Ryanair','ryanair','pending','2025-02-26 01:16:43','2025-03-05 01:27:55'),
(173,'5tfK0EVffol4JBLp','Booking.com','bookingcom','active','2025-06-10 03:01:11','2025-06-20 04:42:53'),
(174,'68tmWBXa_bhm0d1I','Expedia','expedia','active','2025-04-01 16:32:20','2025-05-18 16:44:00'),
(175,'G58h4qiw4gdzwQK1','TripAdvisor','tripadvisor','active','2025-12-04 18:33:35','2026-01-31 09:33:13'),
(176,'QzdHnQ0nVKE3pqw2','Hilton','hilton','active','2025-07-22 03:41:31','2025-08-31 21:17:28'),
(177,'ttUjYhYCP9gzWQqV','Marriott','marriott','active','2025-09-22 09:53:29','2025-10-06 20:55:42'),
(178,'cypduAZW68SjOvPn','Hyatt','hyatt','active','2025-08-10 12:56:28','2025-08-31 12:00:47'),
(179,'xX_eKyQhl8SPd8-N','Hackett Ltd','hackett-ltd','active','2025-03-18 17:18:33','2025-04-07 17:39:32'),
(180,'qwQIPO4Aew_9UDRl','Strosin, Lueilwitz and McLaughlin','strosin-lueilwitz-and-mclaughlin','active','2025-06-27 03:04:07','2025-08-24 19:18:23'),
(181,'g7mRb1fXcKRWYysE','Stracke and Sons','stracke-and-sons','active','2025-08-11 11:05:00','2025-10-04 19:39:29'),
(182,'I1dj5jMp_vosJyXC','Purdy PLC','purdy-plc','active','2025-04-22 00:32:28','2025-05-31 15:28:15'),
(183,'KRIjZDZu2y_pY51a','Balistreri-Schroeder','balistreri-schroeder','active','2025-01-08 21:58:47','2025-02-19 11:40:08'),
(184,'e8dXMAK60UcB0Jew','Russel, Metz and Romaguera','russel-metz-and-romaguera','active','2025-01-04 05:44:45','2025-02-03 10:26:41'),
(185,'4YWywezRHpx4qNGa','Bruen, Windler and Hills','bruen-windler-and-hills','active','2025-11-16 04:11:45','2025-12-25 01:14:05'),
(186,'l2wWgwG_ZhOmuZab','Nader-Gerlach','nader-gerlach','pending','2025-06-09 19:40:02','2025-07-11 00:14:43'),
(187,'E3DUe2xTO1lVjck5','Prohaska Ltd','prohaska-ltd','pending','2025-12-28 12:00:14','2026-01-26 17:54:26'),
(188,'Dw4jb1UevEmQu45g','Ullrich-Hauck','ullrich-hauck','active','2025-08-30 06:55:10','2025-09-02 18:27:48'),
(189,'2UDS0N5TmekiNKad','Huel Ltd','huel-ltd','active','2025-05-10 10:33:32','2025-07-08 06:20:37'),
(190,'GY_SBMJ-7jU_arKn','Marks-Borer','marks-borer','active','2025-08-26 13:29:55','2025-09-30 19:08:39'),
(191,'3HZ8cNSxBfTgocsl','Crooks PLC','crooks-plc','active','2025-01-04 18:15:34','2025-02-19 13:02:54'),
(192,'hw6id05a9lzupi8n','Langworth and Sons','langworth-and-sons','pending','2025-05-04 06:34:06','2025-05-18 11:48:49'),
(193,'2we-N9-8P12pv57c','Considine-Eichmann','considine-eichmann','active','2025-06-17 11:09:46','2025-07-27 19:41:11'),
(194,'ZfbwZStlTpklvRzz','Schmitt, Roob and Schneider','schmitt-roob-and-schneider','active','2025-10-14 21:43:23','2025-11-04 19:00:48'),
(195,'CLdx7t7j0EBW_23r','Dach-Schaefer','dach-schaefer','active','2025-08-09 23:38:07','2025-08-19 09:58:19'),
(196,'0I5L2upV1nW9Myhm','Macejkovic-Kuphal','macejkovic-kuphal','active','2025-08-21 12:23:00','2025-10-17 13:37:23'),
(197,'fC3uSqTYxSlWTBdb','Wilkinson-Christiansen','wilkinson-christiansen','active','2025-03-03 16:15:52','2025-03-29 15:16:41'),
(198,'TMH_dQ5Z2pyQa-Y1','Kling, Ferry and Murazik','kling-ferry-and-murazik','active','2025-03-31 00:30:21','2025-04-13 08:32:27'),
(199,'RPBL6uOpcHeFU272','Roob-O\'Conner','roob-oconner','active','2025-09-22 05:28:20','2025-10-12 03:27:17'),
(200,'SJtVokmUdYkDDOiq','Flatley PLC','flatley-plc','active','2025-12-14 12:06:02','2026-02-03 15:37:32'),
(201,'tIGi2P_ryp0iG_Yr','Dooley-Bruen','dooley-bruen','active','2025-08-03 05:10:27','2025-08-16 10:52:42'),
(202,'m3EVC31IUJV7_wAa','Kessler Ltd','kessler-ltd','active','2025-03-14 07:43:02','2025-04-16 15:16:14'),
(203,'aufHl4Le4hYQMLpj','Ryan, Veum and Orn','ryan-veum-and-orn','active','2025-12-30 04:25:27','2026-01-01 08:26:27'),
(204,'6mTKMM5RR8MuDw8Q','Daniel, Frami and Haag','daniel-frami-and-haag','active','2025-02-03 13:16:54','2025-02-07 13:37:01'),
(205,'aBEPnPgOgVUpk_j2','Ullrich-Reynolds','ullrich-reynolds','active','2025-12-12 18:35:20','2026-01-13 00:39:50'),
(206,'p0NA4HrfWaKzgAT0','Kihn-Schmitt','kihn-schmitt','pending','2025-07-04 04:35:36','2025-08-10 14:55:08'),
(207,'qfQsOmW9KMXXKXkc','Lakin-Bogisich','lakin-bogisich','active','2025-12-12 17:10:54','2026-01-10 08:51:06'),
(208,'wgtbrWIPdX3Fuqpv','Frami, Bernhard and Crist','frami-bernhard-and-crist','active','2025-03-29 11:15:44','2025-04-29 11:06:04'),
(209,'A5USwtbDlqea2NKd','Heaney and Sons','heaney-and-sons','active','2025-11-06 04:55:02','2025-12-16 19:51:07'),
(210,'Ss1Ir9FOO1NXPayo','Lowe, Brakus and Hessel','lowe-brakus-and-hessel','active','2025-10-06 21:21:23','2025-11-17 06:12:31'),
(211,'Lxgy8rTcJoaN7-6d','Macejkovic, Kuphal and Schulist','macejkovic-kuphal-and-schulist','pending','2025-03-23 14:08:55','2025-05-15 13:22:53'),
(212,'6b5igpyeTUoVef4W','Hyatt Group','hyatt-group','active','2025-12-12 22:28:38','2026-01-09 16:25:29'),
(213,'IDjsr0Vn__ApBmrv','Nolan LLC','nolan-llc','active','2025-05-05 18:14:09','2025-05-19 16:17:05'),
(214,'3Vc1qtTgi9MI1bTn','Homenick Ltd','homenick-ltd','active','2025-10-18 19:44:49','2025-11-27 17:22:41'),
(215,'SQygcJIsJs0vGSth','Erdman, Graham and Fisher','erdman-graham-and-fisher','active','2025-11-19 18:02:46','2025-12-21 00:09:23'),
(216,'j1wb6jRBBGOJFbvu','Schuster Inc','schuster-inc','active','2025-09-30 20:10:35','2025-11-12 00:08:17'),
(217,'fBa7wnEGMWuQERae','Considine-Conn','considine-conn','active','2025-11-21 03:02:54','2025-12-14 09:00:15'),
(218,'kKpsYcN611ZDhqdw','Brekke, Walter and Lind','brekke-walter-and-lind','active','2025-07-11 22:01:33','2025-07-28 11:30:27'),
(219,'W9SdLHvxKvs7Gsf5','Flatley, Mills and Flatley','flatley-mills-and-flatley','active','2025-06-30 21:59:00','2025-07-18 04:41:43'),
(220,'pi1UpB9HzvSh8YWK','Swaniawski-Abbott','swaniawski-abbott','archived','2025-08-18 18:38:30','2025-08-30 23:27:22'),
(221,'AoB7nQF-kbYI5PWA','Balistreri LLC','balistreri-llc','active','2025-11-03 17:23:45','2025-12-31 22:07:42'),
(222,'MuyQ3BgoOOc5F6RX','Emmerich-Durgan','emmerich-durgan','active','2025-11-06 09:04:37','2025-11-11 14:16:46'),
(223,'aHYkC3usUaZkmdy4','Heathcote-Konopelski','heathcote-konopelski','active','2025-07-15 14:01:24','2025-07-24 18:31:59'),
(224,'Mf0wR5VbSdnrhPkC','Hettinger-Hackett','hettinger-hackett','active','2025-03-08 08:16:11','2025-05-02 00:02:50'),
(225,'Oddx340qtyToM8BS','Wolf-Nitzsche','wolf-nitzsche','active','2025-07-30 11:19:32','2025-08-15 14:24:54'),
(226,'kRMwzFTCxcupyt5t','Mann-Schiller','mann-schiller','active','2025-06-20 11:49:24','2025-07-11 15:02:34'),
(227,'yBWIx8X8VE4Ar8l3','Brakus-Kertzmann','brakus-kertzmann','active','2025-09-18 23:57:55','2025-10-13 16:35:57'),
(228,'MDClSjjdqMYB4Xh1','Wiza, Goodwin and Boehm','wiza-goodwin-and-boehm','active','2025-06-16 11:51:53','2025-07-16 11:12:22'),
(229,'NohllbuRkcjM6UEu','Farrell, Kuhic and Feeney','farrell-kuhic-and-feeney','pending','2025-06-06 16:15:50','2025-07-02 16:46:56'),
(230,'4Uzf7HtAckfP1w5y','Marvin-Wisoky','marvin-wisoky','active','2025-01-25 04:26:20','2025-02-25 11:41:44'),
(231,'oA31Ujsk4uwV3u62','Klein-Feil','klein-feil','active','2025-08-23 19:50:32','2025-08-26 20:56:37'),
(232,'zopNmdHNTbJHRlER','Rutherford, Hickle and Dooley','rutherford-hickle-and-dooley','active','2025-03-14 05:35:09','2025-04-09 03:00:03'),
(233,'ElOaJ2y_vnz5CZFs','Bernhard Inc','bernhard-inc','active','2025-01-29 21:52:38','2025-03-28 09:03:01'),
(234,'x4SaxbK6nrdbDGnx','Corwin-Glover','corwin-glover','active','2025-07-10 15:55:06','2025-08-24 07:13:08'),
(235,'dX7p7KeqOdvoIPOr','Brakus, Luettgen and Harvey','brakus-luettgen-and-harvey','active','2025-10-25 14:52:12','2025-11-24 01:52:37'),
(236,'gEWCqGb6y1p8jKfK','Frami, Brekke and Fisher','frami-brekke-and-fisher','active','2025-07-30 02:43:29','2025-08-14 19:15:23'),
(237,'v7cnam-GeVehU7bk','Zemlak-Purdy','zemlak-purdy','active','2025-04-09 09:49:30','2025-05-12 15:31:38'),
(238,'IRMLBNc7UG27gNWz','Russel and Sons','russel-and-sons','active','2025-05-10 11:19:05','2025-06-30 23:10:55'),
(239,'OZuTpQ01hOhO3vzr','Nikolaus-Kautzer','nikolaus-kautzer','pending','2025-07-27 08:39:14','2025-08-27 10:35:30'),
(240,'K3Zd0TJrHKJcc9pb','Waters, Jenkins and Ratke','waters-jenkins-and-ratke','archived','2025-05-09 04:47:51','2025-06-09 19:05:33'),
(241,'u3aUPKwjIrHDqwg6','Cronin, Padberg and Lakin','cronin-padberg-and-lakin','active','2025-10-22 04:41:36','2025-10-27 06:02:16'),
(242,'z0lY0jwWOoT0CtcX','Erdman LLC','erdman-llc','active','2025-06-20 16:38:40','2025-07-07 14:29:09'),
(243,'hRUkBHrPmQw1Um0N','Metz, Bernier and Kuhlman','metz-bernier-and-kuhlman','active','2025-04-22 04:44:45','2025-05-24 02:36:24'),
(244,'CJtFpFGXKVbrY7Oi','Keeling, Herzog and Heaney','keeling-herzog-and-heaney','active','2025-12-28 12:25:19','2025-12-28 20:26:58'),
(245,'Q4ZfG2ynNHXqSJ6t','Pfannerstill, Kerluke and Lindgren','pfannerstill-kerluke-and-lindgren','pending','2025-06-20 23:00:28','2025-08-18 00:17:05'),
(246,'Y-bHTflNlVBnbCLb','Sipes-Emmerich','sipes-emmerich','active','2025-05-20 16:08:39','2025-06-04 02:26:24'),
(247,'O-IR3p3IDLGPtllV','Ward-Hane','ward-hane','active','2025-04-13 21:10:34','2025-05-30 21:38:19'),
(248,'MR154LKgWIchx8b7','Senger-Pouros','senger-pouros','active','2025-09-19 00:25:13','2025-10-08 08:51:43'),
(249,'tvmGEpBaqdqV_9wc','Adams, Mills and Gerlach','adams-mills-and-gerlach','active','2025-02-08 15:17:03','2025-04-08 07:19:31'),
(250,'nNm8Qm6yqx5b8fza','Sanford, Collins and Gusikowski','sanford-collins-and-gusikowski','active','2025-07-09 05:51:32','2025-07-27 02:43:27'),
(251,'sPdReTkooSjNzGPB','Hickle, Gutkowski and Pouros','hickle-gutkowski-and-pouros','pending','2025-05-31 05:58:18','2025-07-02 05:36:39'),
(252,'PpUNNydCnSkCivKQ','Rempel, Reilly and Abbott','rempel-reilly-and-abbott','active','2025-02-14 03:17:42','2025-03-10 09:02:44'),
(253,'oSBJhmFlUtwGYiTI','Toy, Grant and Will','toy-grant-and-will','active','2025-12-01 20:40:04','2026-01-24 03:24:58'),
(254,'giTeQqkmrm0tQMOO','Wyman Ltd','wyman-ltd','active','2025-08-01 08:43:24','2025-08-15 18:10:08'),
(255,'bQDrzAaOUHzqvfI7','Collier, Leannon and Hills','collier-leannon-and-hills','active','2025-01-14 06:33:04','2025-03-01 21:43:05'),
(256,'iIFBVTWfvFR0JbJo','Bernhard LLC','bernhard-llc','active','2025-02-12 23:58:06','2025-02-20 01:19:57'),
(257,'x1rm6ENIAJv6CP7v','Balistreri Ltd','balistreri-ltd','pending','2025-12-30 07:28:18','2026-01-08 21:26:51'),
(258,'hEYUtAkz5kJpj09Z','Bahringer-Herman','bahringer-herman','active','2025-08-02 06:03:39','2025-08-11 02:53:59'),
(259,'Iu--OetS-eHSLtX_','Hill PLC','hill-plc','archived','2025-10-07 04:30:05','2025-10-24 18:07:05'),
(260,'sLOGEWJ5BFMwS3qn','Hickle-Conn','hickle-conn','active','2025-04-02 02:15:02','2025-04-15 06:15:38'),
(261,'IlGVLY_g2Nx8h-Wg','Koss, Konopelski and Johns','koss-konopelski-and-johns','pending','2025-05-14 14:39:53','2025-05-16 12:39:20'),
(262,'C9bx2A5K935Ec8xw','Koss Group','koss-group','active','2025-04-27 00:19:39','2025-06-21 23:15:09'),
(263,'TI3MdCRYQU8qSkyA','Nicolas Inc','nicolas-inc','pending','2025-08-16 16:23:40','2025-08-20 23:57:40'),
(264,'RGGJXTbd-G__bYsP','McGlynn and Sons','mcglynn-and-sons','pending','2025-03-29 01:15:17','2025-05-13 10:42:51'),
(265,'VmTT5NBM7g7qC-Hg','Schuster-Gorczany','schuster-gorczany','active','2025-06-18 01:49:13','2025-06-19 06:52:10'),
(266,'bBKlm9FyxJNHq4rM','Dibbert Inc','dibbert-inc','active','2025-09-20 06:02:43','2025-10-07 21:52:06'),
(267,'6fiObWWtHqeKjNPj','Rempel, Bashirian and Miller','rempel-bashirian-and-miller','active','2025-08-18 08:58:07','2025-09-04 08:08:48'),
(268,'INLsz0dncLqWE0Le','Ziemann, Gerhold and Schinner','ziemann-gerhold-and-schinner','active','2025-07-09 19:30:57','2025-08-12 16:36:24'),
(269,'5zNz_3Vbp-2Fa7uf','O\'Connell, Collier and Cassin','oconnell-collier-and-cassin','active','2025-10-19 16:56:20','2025-10-26 17:39:21'),
(270,'PCt6Z2TnXb8LNhPZ','Kutch and Sons','kutch-and-sons','active','2025-10-30 14:55:47','2025-12-07 18:52:44'),
(271,'XeQR1KuzWLZj682K','Brakus-Witting','brakus-witting','active','2025-12-04 08:04:20','2026-01-29 12:02:31'),
(272,'0CqsqCJwXvLXpKfZ','Ondricka, Dietrich and Feil','ondricka-dietrich-and-feil','active','2025-11-30 05:40:23','2025-12-14 07:55:51'),
(273,'7Z9TwkRRcoxSd8UK','Okuneva Group','okuneva-group','active','2025-10-03 14:52:00','2025-10-14 11:57:04'),
(274,'weh34B6iLxnk4DJ3','Bosco, Ortiz and Heller','bosco-ortiz-and-heller','pending','2025-10-23 19:13:26','2025-10-24 16:53:00'),
(275,'bqJtzP3b5NzpQXWT','Nitzsche-Jaskolski','nitzsche-jaskolski','archived','2025-05-23 08:05:12','2025-06-11 04:13:58'),
(276,'AYSMwyCVO8ScWpAx','Hoppe, Okuneva and Okuneva','hoppe-okuneva-and-okuneva','pending','2025-06-26 20:21:17','2025-08-25 04:34:10'),
(277,'YcKNBTfi1q3sMqsy','Cruickshank PLC','cruickshank-plc','active','2025-01-20 06:06:41','2025-03-16 17:36:01'),
(278,'dH3lY4WIuTP-VCYN','Schulist-Denesik','schulist-denesik','pending','2025-09-29 08:37:38','2025-10-01 03:08:21'),
(279,'Wd43ESqiKAUATbft','Parker, McKenzie and Kuphal','parker-mckenzie-and-kuphal','active','2025-05-31 08:54:39','2025-07-15 04:19:18'),
(280,'2gxcdI2fXiEaHW1g','Crona LLC','crona-llc','pending','2025-03-09 02:59:39','2025-04-05 22:54:05'),
(281,'CNwCz4EAYPCH51Zp','Schaden, Rutherford and Ebert','schaden-rutherford-and-ebert','active','2025-05-08 08:21:10','2025-05-29 00:19:15'),
(282,'Y6oNSzz61_jqlIUD','Stracke-Rodriguez','stracke-rodriguez','active','2025-08-29 11:46:41','2025-09-17 04:21:54'),
(283,'GEf3utZUkMs0_whH','Turcotte Inc','turcotte-inc','pending','2025-12-04 23:09:42','2026-01-15 17:16:01'),
(284,'pqNLY7OsjP0BhgCv','Osinski-Morar','osinski-morar','active','2026-01-02 00:37:07','2026-01-04 23:37:59'),
(285,'3Z5zoaNm_jywRBjz','Oberbrunner, Gottlieb and Olson','oberbrunner-gottlieb-and-olson','active','2025-07-10 00:22:30','2025-08-04 02:11:33'),
(286,'M1UwM2oeRwxm9B7B','Pfeffer, Kuphal and Cormier','pfeffer-kuphal-and-cormier','active','2025-04-21 15:20:21','2025-04-30 09:22:33'),
(287,'3DMnr9ncKQ-ECCWm','Wilkinson PLC','wilkinson-plc','pending','2025-03-01 11:14:08','2025-03-07 04:15:41'),
(288,'QzEb5XlPxTwQlahu','Beier and Sons','beier-and-sons','active','2025-08-30 22:20:26','2025-09-26 13:06:32'),
(289,'woNOwGgOw3wp3WyS','Cormier PLC','cormier-plc','archived','2025-02-08 14:46:07','2025-02-20 14:50:25'),
(290,'1f8fDI4N68-N_vzl','Doyle PLC','doyle-plc','active','2025-07-27 13:55:46','2025-08-29 06:55:40'),
(291,'fDdGPzIXuTg_UoxK','Stanton, Bednar and Jerde','stanton-bednar-and-jerde','active','2025-07-23 23:17:57','2025-09-03 17:43:20'),
(292,'QjmRURpk21h62_jd','Steuber-Schoen','steuber-schoen','active','2025-04-17 22:05:07','2025-04-21 02:41:34'),
(293,'3DQEg9mx3CFuGN3h','Morissette Group','morissette-group','active','2025-09-25 20:02:39','2025-10-30 10:06:42'),
(294,'zgINeeRoeWka2UTF','Cummerata-Predovic','cummerata-predovic','active','2025-09-14 17:31:01','2025-11-12 11:17:49'),
(295,'VL99fe6p4pLqJnnT','Kris-Franecki','kris-franecki','active','2025-08-20 11:56:44','2025-08-21 05:26:36'),
(296,'P2j2Zzf9SItDTrKM','Bradtke-Schaefer','bradtke-schaefer','active','2025-07-09 20:14:51','2025-08-28 08:02:45'),
(297,'SB0mLk4-6yOGDU0m','Kuhn and Sons','kuhn-and-sons','pending','2025-03-21 06:55:05','2025-04-21 07:59:47'),
(298,'9bctS25FTFlzBmdf','Schiller, Wuckert and Mann','schiller-wuckert-and-mann','active','2025-08-26 17:10:12','2025-09-27 11:56:56'),
(299,'MJscABRwZL9tdnuN','Funk, Crona and Ullrich','funk-crona-and-ullrich','active','2025-07-11 21:40:44','2025-08-17 15:49:26'),
(300,'RAjSNDTQ6TTshyCK','Bode-Hermiston','bode-hermiston','active','2025-10-24 08:02:19','2025-11-26 08:16:34'),
(301,'5gwxyKp5c9lfTlKc','Roberts, Kub and Gorczany','roberts-kub-and-gorczany','archived','2025-06-10 16:43:42','2025-07-18 17:11:38'),
(302,'wusKbJCd412oyOOx','Harber Inc','harber-inc','active','2025-11-22 02:29:48','2025-11-26 08:21:34'),
(303,'xF1ML4XkvJVipa0Z','Stehr, Fadel and Streich','stehr-fadel-and-streich','active','2025-12-03 19:49:17','2025-12-18 03:51:26'),
(304,'tdNOTFePyNiL1gfX','Kessler-Runolfsdottir','kessler-runolfsdottir','active','2025-12-08 23:53:47','2026-02-06 06:56:41'),
(305,'ANfu7h6nK6m4GXYw','Okuneva PLC','okuneva-plc','active','2025-07-14 01:43:49','2025-09-01 02:25:32'),
(306,'Mu82WsXA-l86ueQO','Rohan-Sauer','rohan-sauer','archived','2025-02-23 11:17:02','2025-03-06 03:57:56'),
(307,'vljGR550N980nZYl','Hessel-Haley','hessel-haley','active','2025-11-26 05:52:00','2025-12-18 04:49:28'),
(308,'-ak_xQkF2wYfuFwN','Thiel Group','thiel-group','active','2025-10-09 10:15:17','2025-10-10 16:21:11'),
(309,'Kg9fPSEAHn8IMoTE','Thiel Ltd','thiel-ltd','pending','2025-03-25 22:02:36','2025-05-12 08:49:48'),
(310,'FCq-K0DOUQb6D7xs','Lakin PLC','lakin-plc','active','2025-09-18 22:27:27','2025-11-01 04:38:44'),
(311,'FGH_SOJLzeVxdugz','Zulauf and Sons','zulauf-and-sons','active','2025-03-26 09:37:10','2025-04-21 21:08:10'),
(312,'gapMmNq1v3nZj8Pu','Schowalter-Schinner','schowalter-schinner','active','2025-05-10 16:25:59','2025-06-04 01:47:31'),
(313,'IKKYGpd45-mt1zHV','Kling, Kub and Little','kling-kub-and-little','pending','2025-05-25 02:22:28','2025-07-22 19:48:21'),
(314,'T-9D9KaxV2oBy49T','Halvorson-Bode','halvorson-bode','active','2025-05-14 22:32:16','2025-05-28 14:28:22'),
(315,'S7xB7TV8w7oM5MhL','Stroman PLC','stroman-plc','active','2025-09-11 20:02:29','2025-10-02 22:28:34'),
(316,'tSV_dwWM9SRcd73-','Padberg Group','padberg-group','active','2025-12-29 07:22:34','2026-02-15 18:04:32'),
(317,'_OjzczwXdUum4lb7','Goodwin, Schulist and Crona','goodwin-schulist-and-crona','active','2025-01-22 03:46:29','2025-03-22 18:51:07'),
(318,'KcRlljff8afHoDCM','Schultz-Schiller','schultz-schiller','active','2025-08-21 13:26:05','2025-09-06 07:03:55'),
(319,'0GWx8sErQzO5TUBN','Brekke-Reinger','brekke-reinger','active','2025-01-22 02:01:47','2025-02-21 22:06:49'),
(320,'Oj18twisM-wJimZm','Will, Batz and Kreiger','will-batz-and-kreiger','active','2025-06-06 21:35:51','2025-07-02 07:10:35'),
(321,'vgo6HFyAVLzMetIF','Hessel-Schmidt','hessel-schmidt','pending','2025-07-25 17:09:47','2025-08-19 20:11:19'),
(322,'yoOJbJrpj9v8Rcu_','Kris, Gaylord and Harris','kris-gaylord-and-harris','active','2025-01-04 22:31:29','2025-02-07 14:43:25'),
(323,'Rn1alNVWOgahuU-2','Gleichner, Konopelski and Ledner','gleichner-konopelski-and-ledner','archived','2025-04-08 18:28:20','2025-05-01 20:11:14'),
(324,'ZHIiavj6IHNBnA6y','Kovacek, Hickle and Brown','kovacek-hickle-and-brown','active','2025-12-03 22:58:13','2025-12-29 08:46:36'),
(325,'fN98ri0yAZ-cNOtV','Haley Group','haley-group','active','2025-01-26 09:19:53','2025-03-21 17:59:25'),
(326,'c0-a1GN4yn7M4cKO','Hackett LLC','hackett-llc','active','2025-08-20 06:45:52','2025-08-26 02:54:14'),
(327,'LM0KtvupESJbgFQg','O\'Reilly-Gleichner','oreilly-gleichner','pending','2025-02-20 01:44:45','2025-03-12 10:38:05'),
(328,'vPzIQNXPfPxZYhBv','Lesch-Russel','lesch-russel','active','2025-10-16 06:56:34','2025-10-29 18:12:47'),
(329,'nHU7D1-mZD9uKhrq','Jast, Dare and Stoltenberg','jast-dare-and-stoltenberg','active','2025-05-18 06:32:48','2025-05-30 07:13:19'),
(330,'Q66YT7VflxzVBXo4','Gleichner, Dibbert and Mohr','gleichner-dibbert-and-mohr','active','2025-12-20 13:45:14','2026-02-01 03:53:25'),
(331,'xu7qy0_L5D8jJv5p','Kiehn, Gorczany and Mante','kiehn-gorczany-and-mante','active','2025-12-16 02:55:34','2026-02-13 21:06:15'),
(332,'IJX6ryoOn3GVgUP8','Schulist-Rippin','schulist-rippin','active','2025-10-08 14:58:33','2025-11-29 01:18:47'),
(333,'_ffVwJGhfEUUC343','Trantow-Yundt','trantow-yundt','active','2025-11-30 10:13:55','2026-01-10 13:10:06'),
(334,'30Wf-UhmzBblhyZ1','Kub-Barrows','kub-barrows','archived','2025-12-29 08:54:45','2026-02-15 12:39:20'),
(335,'y-d5uRmvmv1vw94B','Davis LLC','davis-llc','active','2025-05-04 17:00:40','2025-06-14 06:50:07'),
(336,'GUqVfkWgpdeBizWP','Legros PLC','legros-plc','active','2025-10-11 20:38:01','2025-10-14 16:06:33'),
(337,'o1tsWDeLSOXPG9Uh','Wiegand Group','wiegand-group','active','2025-04-01 13:28:54','2025-04-10 04:08:06'),
(338,'m-cNnEheAQWY3r-m','Von-Terry','von-terry','archived','2025-01-06 23:10:57','2025-02-09 12:29:07'),
(339,'HJ164KQmai8K3iz7','Weimann-Bogan','weimann-bogan','active','2025-12-01 10:09:58','2025-12-29 04:00:34'),
(340,'gYaxWp4O__ED-iLs','Heidenreich-Osinski','heidenreich-osinski','active','2025-09-02 23:13:41','2025-09-11 03:16:26'),
(341,'y4-YANAyYE9dyWlN','Wehner, Pacocha and White','wehner-pacocha-and-white','pending','2025-08-13 17:23:12','2025-08-19 21:00:15'),
(342,'LeiYuq5eW8vfMswe','Corwin, Schuster and Roob','corwin-schuster-and-roob','pending','2025-05-15 04:09:24','2025-06-25 22:32:20'),
(343,'bjuYsuBTkihr8BNC','Mosciski, Hill and Schneider','mosciski-hill-and-schneider','active','2025-01-06 12:51:29','2025-02-03 00:53:55'),
(344,'cqnWcEsEsXOYDILK','Gorczany-Greenfelder','gorczany-greenfelder','pending','2025-06-07 19:15:47','2025-07-08 03:37:46'),
(345,'CbYXXq7O6A6I3aGr','Abernathy LLC','abernathy-llc','active','2025-07-04 02:03:11','2025-08-28 16:04:43'),
(346,'7UA1zgONdbtFI2Bt','Collier-Senger','collier-senger','active','2025-12-07 09:11:44','2025-12-13 08:43:20'),
(347,'RBafFnHoqh95HtoT','Hirthe-Koepp','hirthe-koepp','active','2025-02-06 16:35:56','2025-03-21 09:33:21'),
(348,'hS0QbNijdqNUhpkP','Stroman-Swift','stroman-swift','active','2025-08-22 19:57:58','2025-08-28 04:38:47'),
(349,'iUYi080lwDy5lLta','Lueilwitz-Watsica','lueilwitz-watsica','active','2025-01-16 20:02:44','2025-02-02 16:40:11'),
(350,'V8-x1IEF13XXqMYu','Wolf-Christiansen','wolf-christiansen','active','2025-08-16 05:02:47','2025-08-18 13:06:08'),
(351,'aRQo_xXEi-RQ2eaM','Herzog Ltd','herzog-ltd','active','2025-08-22 01:18:25','2025-10-12 05:39:52'),
(352,'rAF9dMA706KW2pa9','Gleichner Inc','gleichner-inc','active','2025-05-22 15:52:01','2025-07-18 04:34:24'),
(353,'b_TkCrZJhzSjSswF','Jast-Klein','jast-klein','active','2025-10-26 20:35:30','2025-11-26 22:19:18'),
(354,'dzyqNv5X9qnl11fc','Fay, Green and Jacobs','fay-green-and-jacobs','active','2025-10-06 19:05:42','2025-11-25 08:11:02'),
(355,'eb4VIEDgvAUIOX9a','Herzog-Pfeffer','herzog-pfeffer','active','2025-12-10 05:31:00','2025-12-19 18:15:37'),
(356,'30k3stq6H5ISvPmd','Thiel Inc','thiel-inc','archived','2025-09-25 02:40:04','2025-10-29 08:45:55'),
(357,'VvzlfantLBgQbikl','Ortiz PLC','ortiz-plc','pending','2025-05-28 08:19:39','2025-07-18 09:46:28'),
(358,'hxiS-av5n9PEIigq','Schultz-Veum','schultz-veum','active','2025-12-21 07:18:02','2026-02-07 21:16:27'),
(359,'q0wBhRUTfYsb3CL5','Walker and Sons','walker-and-sons','archived','2025-09-13 01:08:46','2025-10-26 04:35:22'),
(360,'vung10lFQGP6Tb_8','Kunze, Heathcote and Schinner','kunze-heathcote-and-schinner','active','2025-04-09 08:25:53','2025-05-23 06:43:10'),
(361,'hrTk-_G2fmFw9ZCx','Kozey-Dooley','kozey-dooley','active','2025-07-28 10:11:13','2025-08-23 13:08:13'),
(362,'6j2S8Y4A8XZqcmsq','Nitzsche Inc','nitzsche-inc','active','2025-04-20 22:11:40','2025-05-16 08:21:02'),
(363,'vuCJR441ZPbNMj9n','Reinger LLC','reinger-llc','active','2025-06-03 14:24:56','2025-06-03 18:43:54'),
(364,'lV_CqgfaR2QZXkJN','Fisher Group','fisher-group','active','2025-11-24 17:20:04','2026-01-03 20:44:06'),
(365,'pa6MSk-mX00GlHE3','Wolff, Mayer and Lockman','wolff-mayer-and-lockman','active','2025-04-25 12:04:30','2025-06-02 22:05:29'),
(366,'5rmKlYMH1vYL37jB','Block Ltd','block-ltd','active','2025-10-30 14:00:02','2025-11-26 08:21:15'),
(367,'cjcF_GlSxJmCXAdD','Rath, Cummerata and Skiles','rath-cummerata-and-skiles','active','2025-02-01 22:26:32','2025-03-08 15:10:19'),
(368,'eWCbCzjyAuST3HOF','Russel, Pollich and Gutkowski','russel-pollich-and-gutkowski','active','2025-01-13 18:37:44','2025-02-06 11:57:09'),
(369,'rCHl0OVeph3fRNqP','Farrell, Romaguera and Sauer','farrell-romaguera-and-sauer','archived','2025-03-05 16:33:58','2025-04-22 05:02:33'),
(370,'k_pM2znNRG0CPcNL','McCullough Ltd','mccullough-ltd','pending','2025-09-05 20:50:02','2025-09-08 08:16:42'),
(371,'cPJWNGoiJ55pR-Lw','Kshlerin Inc','kshlerin-inc','active','2025-11-02 09:06:26','2025-12-29 14:56:44'),
(372,'8Q61LO7tXNGfQcn7','Bogisich LLC','bogisich-llc','active','2025-11-27 04:06:04','2026-01-20 03:08:34'),
(373,'z23HN7kiVAjQ5Xtw','Windler Group','windler-group','active','2025-09-05 13:18:53','2025-10-04 03:07:15'),
(374,'uv3WvEOfE3GOe8hW','Kling Inc','kling-inc','active','2025-05-30 21:45:23','2025-06-05 15:13:29'),
(375,'ONb2gYBTFj_Ovy9e','Jones-Ruecker','jones-ruecker','pending','2025-02-01 15:42:37','2025-02-08 03:32:24'),
(376,'VwE407VZWSeNVcyv','Hoppe Ltd','hoppe-ltd','active','2025-11-08 10:43:26','2025-12-31 20:45:40'),
(377,'KG1SIRf7YcN6k4B0','Zulauf, Breitenberg and Beer','zulauf-breitenberg-and-beer','active','2025-07-19 06:55:27','2025-08-14 12:35:53'),
(378,'fNo5pZPf6cl68hiH','Ferry, Bernier and Adams','ferry-bernier-and-adams','active','2025-02-24 19:06:40','2025-03-24 01:20:36'),
(379,'lCAtOVDlkxEuU9aD','Doyle, Haley and Schaefer','doyle-haley-and-schaefer','active','2025-10-20 18:57:54','2025-11-11 00:18:42'),
(380,'b3YfLqHSxTHEeg6m','Schaden-Goldner','schaden-goldner','active','2025-03-19 16:02:43','2025-04-26 22:20:53'),
(381,'19CgAqXrH-mNIax9','Kerluke, Thiel and Doyle','kerluke-thiel-and-doyle','active','2025-11-23 22:18:02','2026-01-09 20:41:54'),
(382,'pogqASf9iJHWuoBH','Frami, Prohaska and Frami','frami-prohaska-and-frami','pending','2025-09-28 20:57:05','2025-10-17 00:45:23'),
(383,'VIm9mrPHxfQyI2Ok','Kshlerin, Walker and Gorczany','kshlerin-walker-and-gorczany','active','2025-05-17 00:46:37','2025-06-25 23:57:52'),
(384,'wfdolv9n1YDbezdy','Okuneva, Hammes and Wintheiser','okuneva-hammes-and-wintheiser','pending','2025-02-25 13:43:12','2025-02-26 07:54:02'),
(385,'Nrbbtq-fB5ImGbzX','Reynolds-Lang','reynolds-lang','active','2025-08-13 14:17:44','2025-09-26 06:38:29'),
(386,'PH7ZDY71Wp2F1awx','Beahan, Ortiz and McKenzie','beahan-ortiz-and-mckenzie','active','2025-07-08 08:25:05','2025-07-27 14:03:45'),
(387,'VCIw8-IAPau3s1Bo','DuBuque Inc','dubuque-inc','active','2025-05-12 19:07:22','2025-06-11 19:53:24'),
(388,'UyZdbp0OMKqWcPNS','Upton, Jast and Schimmel','upton-jast-and-schimmel','active','2025-12-09 15:18:43','2026-01-28 21:22:01'),
(389,'ukOsszlfm4m9uoO9','Spencer LLC','spencer-llc','active','2025-06-06 23:02:02','2025-07-25 19:33:51'),
(390,'LglA7ftkiwqjYlda','Price Inc','price-inc','active','2025-09-23 01:20:26','2025-10-27 22:21:46'),
(391,'aRU_81L8lhndQMGl','Altenwerth-Abbott','altenwerth-abbott','active','2025-04-04 02:35:28','2025-05-02 21:21:01'),
(392,'YgNHddYg_B7_y1SF','Schneider-Schowalter','schneider-schowalter','active','2025-04-12 18:40:40','2025-04-13 07:24:34'),
(393,'IJ_ln7eZsf4Pqe-w','Gottlieb-Herzog','gottlieb-herzog','active','2025-06-08 08:50:16','2025-08-01 00:46:49'),
(394,'YOpjMkNjfzvFc9-T','Stark-Schiller','stark-schiller','active','2025-08-22 08:51:37','2025-09-07 21:26:13'),
(395,'faiwISflDLU-WiLh','Runte Ltd','runte-ltd','active','2025-04-10 03:35:40','2025-05-17 08:58:23'),
(396,'v0_sAYP60moPnG_4','Waelchi PLC','waelchi-plc','active','2025-07-07 05:08:51','2025-08-04 17:44:12'),
(397,'9p02UlTO8NF7ukDN','Jast-Swaniawski','jast-swaniawski','active','2025-01-09 13:56:37','2025-01-28 06:23:02'),
(398,'RofFgMPkI4hV_2ET','Carter-Hoeger','carter-hoeger','active','2025-04-04 19:47:53','2025-04-11 19:55:06'),
(399,'KvTS8BAFsTLKER9-','Heller-Lehner','heller-lehner','active','2025-09-08 03:57:00','2025-09-18 22:28:12'),
(400,'IIWnw3OQ9nhVxJLj','Goodwin-Dietrich','goodwin-dietrich','archived','2025-06-04 07:29:40','2025-07-05 01:55:38'),
(401,'U4ZYSvIat-_EY356','Olson, Collins and Bernier','olson-collins-and-bernier','active','2025-02-23 10:01:51','2025-04-21 03:50:43'),
(402,'D4oSGYNHDI_aGyCr','McClure Group','mcclure-group','active','2025-01-29 08:42:39','2025-03-03 14:06:47'),
(403,'Tn2J8o8rIjG9BLvZ','Lakin-Heller','lakin-heller','active','2025-06-12 11:02:41','2025-08-07 08:21:32'),
(404,'8pF0J3Tk0yuW8hMT','Smith-Cummings','smith-cummings','pending','2025-12-19 10:52:59','2026-02-13 02:36:47'),
(405,'KX78UuQQNqZRJ1xd','Wilderman-Smitham','wilderman-smitham','archived','2025-03-20 20:54:16','2025-04-12 08:38:43'),
(406,'IPHF5gXxJM-6i1Mq','Marvin, Cummerata and Ledner','marvin-cummerata-and-ledner','pending','2025-12-20 17:20:44','2026-01-17 18:43:17'),
(407,'AvBzkgUm5a4osYi9','Streich and Sons','streich-and-sons','active','2025-10-14 21:30:27','2025-12-02 22:54:20'),
(408,'91yJYWr7G_K90ehc','Lueilwitz LLC','lueilwitz-llc','active','2025-12-20 05:48:58','2025-12-21 23:41:35'),
(409,'bxT2Qu9FchUuEg_t','Thompson, Strosin and Pollich','thompson-strosin-and-pollich','active','2025-03-10 18:03:13','2025-04-19 00:05:51'),
(410,'JB1wPCNM5ossoI4N','Mitchell, Mante and Lakin','mitchell-mante-and-lakin','active','2025-11-28 19:38:51','2025-12-18 12:04:59'),
(411,'8CrSdT1_HFaU1xvi','Rosenbaum, Crist and Greenholt','rosenbaum-crist-and-greenholt','archived','2025-07-09 22:52:20','2025-09-05 11:57:38'),
(412,'fGzsQ2A5Qp5qC2z8','Brekke, Wilkinson and Walter','brekke-wilkinson-and-walter','active','2025-11-22 12:24:11','2025-12-05 20:47:24'),
(413,'d4PtIf767UEaTE0x','Abbott, Baumbach and Graham','abbott-baumbach-and-graham','archived','2025-02-18 15:28:16','2025-02-20 07:35:40'),
(414,'_g_67iSFxvbqFc2F','Johnston Inc','johnston-inc','pending','2025-03-15 11:39:25','2025-05-07 19:37:41'),
(415,'oLCtfLup8SMfUg6F','Jast and Sons','jast-and-sons','pending','2025-10-24 19:00:21','2025-11-15 08:25:44'),
(416,'-j1_uag3Ed0J5lNm','Reichel-Dibbert','reichel-dibbert','active','2025-11-21 03:49:01','2025-12-06 17:40:12'),
(417,'tHpZOY60eKXv_7Sf','Wolf, Mills and Abernathy','wolf-mills-and-abernathy','active','2025-02-17 15:18:04','2025-03-26 04:55:40'),
(418,'ScWzeZSIPJk68zug','Flatley, Kirlin and Bode','flatley-kirlin-and-bode','pending','2025-02-04 11:10:28','2025-03-27 14:30:55'),
(419,'cDhIPmJGZLGL7pos','Schaefer, Greenholt and McGlynn','schaefer-greenholt-and-mcglynn','active','2025-02-03 04:21:04','2025-03-03 13:33:00'),
(420,'pq21YX_jFTVkhnpV','Hansen-Beahan','hansen-beahan','active','2025-07-10 23:04:47','2025-08-03 11:43:52'),
(421,'WHCnIHzvk_8w_AHm','Hayes-Effertz','hayes-effertz','archived','2025-05-07 04:36:26','2025-05-20 02:02:41'),
(422,'AGGQVKW-x3P_TfY1','Ward-Hauck','ward-hauck','archived','2025-08-19 17:20:22','2025-09-04 07:08:40'),
(423,'ssWcXOSIHkO0OsKu','Larson Ltd','larson-ltd','active','2025-05-19 13:33:30','2025-07-07 12:23:14'),
(424,'UZPzmnyHWcHY3Wde','Koepp, Bailey and Keebler','koepp-bailey-and-keebler','active','2025-10-05 15:07:35','2025-10-07 00:55:49'),
(425,'ufA1fY0kVSUpZPrs','Kulas, Anderson and Hirthe','kulas-anderson-and-hirthe','active','2025-05-21 21:30:51','2025-07-14 22:53:23'),
(426,'n-115JBfKN1IHcFV','Wiegand PLC','wiegand-plc','active','2025-09-16 13:18:50','2025-10-26 05:02:57'),
(427,'1ko8_f72z8foHcEO','Hand, Ward and Greenfelder','hand-ward-and-greenfelder','active','2025-08-11 00:31:20','2025-09-03 18:26:37'),
(428,'9cwuI7MEmlgQpp-w','Collier, Kutch and Beatty','collier-kutch-and-beatty','active','2025-04-14 00:32:52','2025-04-28 03:33:23'),
(429,'aI3h1tQiLmIYbsFl','Roob, Wiza and Powlowski','roob-wiza-and-powlowski','active','2025-11-01 17:06:01','2025-12-05 11:19:35'),
(430,'kf-aVKqK-nBla55J','Zemlak, DuBuque and Quigley','zemlak-dubuque-and-quigley','active','2025-04-21 00:10:02','2025-05-11 07:13:10'),
(431,'W37RSb2iX65x1Uo8','Gaylord LLC','gaylord-llc','active','2025-03-02 06:05:53','2025-04-09 06:00:37'),
(432,'IL0owK18ci_eMKLa','Purdy Ltd','purdy-ltd','active','2025-02-22 20:35:32','2025-03-18 15:49:59'),
(433,'PEqULAF1-VZWuoDQ','Daugherty, Bosco and Zulauf','daugherty-bosco-and-zulauf','active','2025-01-28 19:40:02','2025-03-02 09:54:54'),
(434,'t0JI6fGbe-6sve7-','O\'Reilly-Heller','oreilly-heller','active','2025-08-21 20:04:24','2025-10-12 13:34:54'),
(435,'XFFYdK9en0sUpJRI','Predovic-Cummerata','predovic-cummerata','active','2025-05-01 22:53:16','2025-06-21 09:37:19'),
(436,'GzyBKmOTXz2qLp13','Leuschke, Renner and Pouros','leuschke-renner-and-pouros','archived','2025-10-16 20:54:07','2025-10-28 20:43:06'),
(437,'94lmb92P_naLRwHb','Kautzer, Jacobson and Ratke','kautzer-jacobson-and-ratke','active','2025-10-07 00:34:00','2025-11-30 19:04:23'),
(438,'llKNQbRSPlyUrep5','Kreiger-Cronin','kreiger-cronin','active','2025-08-11 03:24:12','2025-08-27 05:09:37'),
(439,'O9IqtY86hm2CrBAV','Goyette-Ruecker','goyette-ruecker','active','2025-03-28 11:38:49','2025-05-27 07:19:19'),
(440,'AAWP_oasoOALvpFM','Miller-Renner','miller-renner','active','2025-04-23 00:19:50','2025-05-31 16:50:26'),
(441,'U-ynixjYY3m5RuEc','Kohler Inc','kohler-inc','pending','2025-08-28 02:23:14','2025-09-11 08:14:21'),
(442,'Djl2HiE_IGwN6LlG','Skiles-Effertz','skiles-effertz','active','2025-01-28 21:13:16','2025-03-23 18:06:04'),
(443,'KdntXadbRLOvVmiU','Ebert-Zboncak','ebert-zboncak','active','2025-11-19 20:24:50','2025-12-16 11:04:09'),
(444,'b3Gp784y5BRuIAWh','Jones-Welch','jones-welch','active','2025-12-12 09:41:46','2026-01-10 08:01:45'),
(445,'OmFDCQway-zDLXF3','Dickens-Treutel','dickens-treutel','active','2025-06-30 10:12:30','2025-08-04 11:50:01'),
(446,'Nm2rZ-UQH6Z8NN6k','Rempel, Kunze and Mraz','rempel-kunze-and-mraz','active','2025-12-13 22:55:20','2025-12-24 12:44:46'),
(447,'M0jHV5_a3w6xlugc','Feil-Weimann','feil-weimann','pending','2025-01-30 01:45:26','2025-02-21 05:34:45'),
(448,'u3OS06yhfmy6I1e3','Halvorson-Strosin','halvorson-strosin','archived','2025-03-24 20:21:18','2025-04-26 04:53:00'),
(449,'qTSKuu78IKi6U6o2','O\'Hara Inc','ohara-inc','active','2025-01-30 00:21:59','2025-02-27 13:26:28'),
(450,'U3ZVIULd1XkIzX-K','Kuhn-Christiansen','kuhn-christiansen','active','2025-08-18 20:21:14','2025-10-02 07:02:31'),
(451,'b21Jy4Gz43Ix2Q2y','Ritchie, Smith and Doyle','ritchie-smith-and-doyle','active','2025-01-24 07:38:22','2025-03-22 16:23:17'),
(452,'iykIE6ggXC15DnQ5','Bashirian, Sporer and Vandervort','bashirian-sporer-and-vandervort','pending','2025-03-26 00:59:35','2025-04-14 06:59:25'),
(453,'6f_3Hwhvz6N6pDOo','Borer-Lowe','borer-lowe','active','2025-11-09 02:04:54','2025-11-28 02:42:12'),
(454,'cv81FYK6F-sU6gM_','Rowe-Gutkowski','rowe-gutkowski','active','2025-04-05 09:39:42','2025-04-28 22:03:21'),
(455,'ctMSRkXRdkToxWCm','Eichmann Ltd','eichmann-ltd','active','2025-09-04 12:40:38','2025-10-19 13:04:43'),
(456,'d4S8nGmE95bCo2B1','Koch, Kunde and Roberts','koch-kunde-and-roberts','active','2025-06-14 06:55:45','2025-08-06 09:58:17'),
(457,'1U_CNNzsxhSzfU4N','Reynolds, Watsica and Hudson','reynolds-watsica-and-hudson','active','2025-01-04 00:06:08','2025-01-09 20:14:12'),
(458,'wBw4h25Ucy2ait5c','Maggio-Rogahn','maggio-rogahn','active','2025-04-27 21:15:51','2025-06-17 21:24:57'),
(459,'Qnb7amNs4eYkifBK','Wisozk Inc','wisozk-inc','active','2025-07-28 11:25:44','2025-09-22 15:58:32'),
(460,'34XEEFJx_oTw9bKe','Conn-Ortiz','conn-ortiz','active','2025-12-04 12:48:10','2025-12-14 03:28:45'),
(461,'4sgaow1q6ENcoWBF','Anderson-Schaefer','anderson-schaefer','active','2025-01-26 04:15:50','2025-02-20 07:52:57'),
(462,'TpepeeS6cJ4b1kNw','Kshlerin, Brekke and Lindgren','kshlerin-brekke-and-lindgren','active','2025-07-10 13:40:59','2025-08-18 06:21:02'),
(463,'Z7_FZqIkZfciHjxo','Leannon Group','leannon-group','active','2025-03-19 09:42:00','2025-05-12 22:52:01'),
(464,'pf0b8ioFEg7pZbs9','Bosco, Torp and Kessler','bosco-torp-and-kessler','active','2025-11-28 21:32:26','2026-01-23 20:06:04'),
(465,'gikmk6SgYLQoV5OX','Farrell, Welch and Johns','farrell-welch-and-johns','pending','2025-02-07 20:25:34','2025-03-31 11:51:59'),
(466,'jPh8v-4aO3GaCQwK','Kihn Ltd','kihn-ltd','active','2025-09-24 12:58:45','2025-10-11 18:34:35'),
(467,'3csLJotUCXksdLw-','Lockman-Green','lockman-green','active','2025-08-14 12:12:45','2025-09-08 12:46:22'),
(468,'myJTx2XuOiFgvbwp','Ullrich, Moore and Kerluke','ullrich-moore-and-kerluke','active','2025-08-09 11:59:24','2025-08-20 14:33:53'),
(469,'U95P9Y2UzGcZ5Vlk','Senger-Cronin','senger-cronin','active','2025-10-09 14:00:01','2025-10-15 02:41:41'),
(470,'kWWzLOQIrrzT91Ri','Parisian Ltd','parisian-ltd','active','2025-03-07 08:17:35','2025-04-18 20:00:17'),
(471,'HMbAWhK932RCHQSm','Tremblay Group','tremblay-group','active','2025-04-22 02:00:01','2025-05-15 03:59:04'),
(472,'Mr_Lyd9XQo3Y2f6i','Lind Inc','lind-inc','active','2025-03-02 21:23:56','2025-04-29 07:38:38'),
(473,'PZeLlpOq5NDh-nEm','Heaney, Douglas and Jenkins','heaney-douglas-and-jenkins','archived','2025-01-18 16:55:15','2025-02-21 10:25:13'),
(474,'jbAYXBmHpy8trAfO','Satterfield and Sons','satterfield-and-sons','active','2025-11-14 07:45:49','2025-11-22 00:49:08'),
(475,'iv-GWJK3j6JsKIGg','Bosco-Sporer','bosco-sporer','pending','2025-06-23 21:44:21','2025-06-26 17:02:01'),
(476,'IOu8HLEz_VZb-Il7','Berge Inc','berge-inc','archived','2025-01-09 07:20:12','2025-01-12 11:37:28'),
(477,'0RxGZGqTQFjxWPRp','Kozey-Schumm','kozey-schumm','active','2025-03-05 00:32:53','2025-04-17 03:44:31'),
(478,'60WFyn5eot6qyTRZ','McCullough LLC','mccullough-llc','active','2025-04-05 22:06:54','2025-05-18 00:59:24'),
(479,'TdE6_rxgwQLChmN_','Cummerata, Weber and Wilkinson','cummerata-weber-and-wilkinson','active','2025-09-06 18:01:29','2025-09-06 18:28:06'),
(480,'FT_QzE9TbW87dpC8','Mills-Parker','mills-parker','pending','2025-01-15 12:31:15','2025-01-29 03:34:29'),
(481,'uVsKgxc1QsIc8xh6','Prohaska Group','prohaska-group','active','2025-05-23 07:23:09','2025-07-19 01:30:02'),
(482,'Z9UAAYuReLLXEpnj','Boehm Group','boehm-group','pending','2025-01-26 12:59:19','2025-03-01 07:44:41'),
(483,'PAIKs7F1LekGjlDm','Trantow Ltd','trantow-ltd','active','2025-12-16 23:32:48','2026-02-04 15:01:09'),
(484,'glzh1pQHWwpGvxZd','Veum-Waelchi','veum-waelchi','active','2025-12-07 07:47:09','2026-01-12 02:29:09'),
(485,'tu8PzgezCcYQ2hSm','Zieme Inc','zieme-inc','archived','2025-02-19 22:28:35','2025-02-22 12:29:24'),
(486,'G4zLKyHnA7P4Vhzg','Herzog, Altenwerth and Abbott','herzog-altenwerth-and-abbott','active','2025-07-18 01:41:04','2025-09-06 04:03:39'),
(487,'86mBZKpP9z0XxkJJ','Brekke Inc','brekke-inc','active','2025-10-26 06:32:12','2025-12-06 09:41:27'),
(488,'cU6Hg80Z8-LZ0j3p','Klein and Sons','klein-and-sons','pending','2025-08-03 10:32:34','2025-09-21 14:47:25'),
(489,'l5TfYbroAxR4T-hX','Sporer, O\'Kon and Corwin','sporer-okon-and-corwin','active','2025-11-16 00:38:44','2025-12-30 19:31:42'),
(490,'G3NYpL56FGXSKDzw','Sanford Inc','sanford-inc','active','2025-12-20 14:51:47','2026-01-12 10:43:29'),
(491,'49tO04_8MLknjvxL','Ritchie-Ullrich','ritchie-ullrich','active','2025-10-16 10:21:19','2025-11-27 01:37:47'),
(492,'5sEL-szBvrOMWB74','Hagenes-Hartmann','hagenes-hartmann','active','2025-03-19 07:15:01','2025-05-13 20:00:46'),
(493,'EVGlWKnx6sDnQEle','Okuneva-Hirthe','okuneva-hirthe','active','2025-12-28 11:50:02','2026-02-06 11:38:32'),
(494,'FzTfh05YzeCCpWQf','Keeling PLC','keeling-plc','active','2025-07-09 11:21:58','2025-07-29 02:46:59'),
(495,'rNZCIkHaZzMDjz3r','Wintheiser, Ebert and Armstrong','wintheiser-ebert-and-armstrong','active','2025-07-12 05:26:04','2025-08-12 14:42:28'),
(496,'ldMQInqfqny0UQEL','Crist, Roberts and Strosin','crist-roberts-and-strosin','archived','2025-07-04 16:55:18','2025-07-19 16:36:21'),
(497,'R8b8vxCMsF0N8ifm','O\'Conner-Ruecker','oconner-ruecker','pending','2025-10-15 01:47:12','2025-10-18 09:19:06'),
(498,'3MkT71ILiBuOqLlf','Maggio-Collier','maggio-collier','active','2025-04-02 20:47:54','2025-04-29 06:07:17'),
(499,'BY3xsx6stfkwJL4j','Ratke-Aufderhar','ratke-aufderhar','active','2025-04-20 12:06:50','2025-06-18 03:50:24'),
(500,'XXBHC43NcAUiwE6X','Ebert, Pagac and Toy','ebert-pagac-and-toy','active','2025-12-08 00:21:53','2026-01-12 07:04:16');
/*!40000 ALTER TABLE `ADSHOWCASE_brand` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ADSHOWCASE_country`
--

DROP TABLE IF EXISTS `ADSHOWCASE_country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ADSHOWCASE_country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` char(16) NOT NULL,
  `iso` char(2) NOT NULL,
  `iso3` char(3) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `continent_code` char(2) DEFAULT NULL,
  `currency_code` char(3) DEFAULT NULL,
  `status` enum('active','archived','pending') NOT NULL DEFAULT 'active',
  `url_slug` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`),
  UNIQUE KEY `iso` (`iso`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ADSHOWCASE_country`
--

LOCK TABLES `ADSHOWCASE_country` WRITE;
/*!40000 ALTER TABLE `ADSHOWCASE_country` DISABLE KEYS */;
INSERT INTO `ADSHOWCASE_country` VALUES
(1,'lzYQw7Pj82DSmE1Z','ES','ESP','Spain','EU','EUR','active','spain','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(2,'OQKPRfNgU31Wx1EI','AD','AND','Andorra','EU','EUR','active','andorra','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(3,'uoaS7wHgQ2n2opuD','PT','PRT','Portugal','EU','EUR','active','portugal','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(4,'gIBN8-klj5G6Ovx8','FR','FRA','France','EU','EUR','active','france','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(5,'Y53FrK76d7fZwv7_','DE','DEU','Germany','EU','EUR','active','germany','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(6,'n9wpO_JiYzLwQj1a','IT','ITA','Italy','EU','EUR','active','italy','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(7,'3IvV7mmF0LT5gDHi','GB','GBR','United Kingdom','EU','GBP','active','united-kingdom','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(8,'TAZV_3KlTmYE16ga','IE','IRL','Ireland','EU','EUR','active','ireland','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(9,'eq9srtFDTLRPLsvq','NL','NLD','Netherlands','EU','EUR','active','netherlands','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(10,'88U9wRNWn4qHX9b_','BE','BEL','Belgium','EU','EUR','active','belgium','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(11,'8DAceczYbsG6ZsSE','SE','SWE','Sweden','EU','SEK','active','sweden','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(12,'2tvcINjAF8gz9K_W','NO','NOR','Norway','EU','NOK','active','norway','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(13,'pXJ16kLt_37s5oTu','DK','DNK','Denmark','EU','DKK','active','denmark','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(14,'BskzpQ9lEGVIdbAQ','FI','FIN','Finland','EU','EUR','active','finland','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(15,'n70P9gX08S_WvoFU','PL','POL','Poland','EU','PLN','active','poland','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(16,'q-m1VD7YhBcTnY5k','CZ','CZE','Czechia','EU','CZK','active','czechia','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(17,'TQX-90Rk748YJ5je','AT','AUT','Austria','EU','EUR','active','austria','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(18,'GsMUatCWvJK4sZDt','CH','CHE','Switzerland','EU','CHF','active','switzerland','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(19,'MzFOzJUn6p_TeBiw','GR','GRC','Greece','EU','EUR','active','greece','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(20,'vlrLI7wrwmIbl1yz','HU','HUN','Hungary','EU','HUF','active','hungary','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(21,'F4Qv5C5zoNcFxhgD','RO','ROU','Romania','EU','RON','active','romania','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(22,'Ugr3w8VoQfFWy6VY','BG','BGR','Bulgaria','EU','BGN','active','bulgaria','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(23,'roS0MRWOAy7Q6oR8','SK','SVK','Slovakia','EU','EUR','active','slovakia','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(24,'rSn6PIYNrNaE3rWn','SI','SVN','Slovenia','EU','EUR','active','slovenia','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(25,'14WkdSn5rDfDRzaC','HR','HRV','Croatia','EU','EUR','active','croatia','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(26,'5Wsq4cfIPUC5SuX5','UA','UKR','Ukraine','EU','UAH','active','ukraine','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(27,'btz9ZmT8CTxWdeAR','US','USA','United States','NA','USD','active','united-states','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(28,'pN6vkBPibmOP6lcQ','CA','CAN','Canada','NA','CAD','active','canada','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(29,'pTyB2Ta8m0DPH5p1','MX','MEX','Mexico','NA','MXN','active','mexico','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(30,'BzJeyGtVS6BXgwg7','BR','BRA','Brazil','SA','BRL','active','brazil','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(31,'DLv1BbU0r_3noniV','AR','ARG','Argentina','SA','ARS','active','argentina','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(32,'_Dg4IBbag5PkrVew','CL','CHL','Chile','SA','CLP','active','chile','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(33,'2iPxU9S1TRGAzQ1N','CO','COL','Colombia','SA','COP','active','colombia','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(34,'PxexzIBIN8PzjL4x','PE','PER','Peru','SA','PEN','active','peru','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(35,'GYSzJbiw9Us6xzdi','JP','JPN','Japan','AS','JPY','active','japan','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(36,'2rIOMyWXdXAPtWRJ','CN','CHN','China','AS','CNY','active','china','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(37,'BjolFsAAGekYKVFV','KR','KOR','South Korea','AS','KRW','active','south-korea','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(38,'zQTT0cdffm4CVYk5','IN','IND','India','AS','INR','active','india','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(39,'CMrH7UL0GNfRj3-a','SG','SGP','Singapore','AS','SGD','active','singapore','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(40,'htvkxkQQzcIko-J9','AE','ARE','United Arab Emirates','AS','AED','active','united-arab-emirates','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(41,'cI6WNZ-8pncESOGz','SA','SAU','Saudi Arabia','AS','SAR','active','saudi-arabia','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(42,'GPzpoI9Zg-TjOFxN','IL','ISR','Israel','AS','ILS','active','israel','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(43,'3m2xx7q5ZBth8lBO','TR','TUR','Türkiye','AS','TRY','active','turkiye','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(44,'EraW2M5EitVbKaNl','TH','THA','Thailand','AS','THB','active','thailand','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(45,'WjT8FK2ny7PB6Yb3','AU','AUS','Australia','OC','AUD','active','australia','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(46,'w18otYm7gQkYmTx8','NZ','NZL','New Zealand','OC','NZD','active','new-zealand','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(47,'yqrVxIiEBlF7ug8l','ZA','ZAF','South Africa','AF','ZAR','active','south-africa','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(48,'O2NZJd58ESf2QMCZ','EG','EGY','Egypt','AF','EGP','active','egypt','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(49,'v9lp3jMychMsMYk0','MA','MAR','Morocco','AF','MAD','active','morocco','2026-01-04 00:36:33','2026-01-04 00:36:33');
/*!40000 ALTER TABLE `ADSHOWCASE_country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ADSHOWCASE_creative`
--

DROP TABLE IF EXISTS `ADSHOWCASE_creative`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ADSHOWCASE_creative` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `hash` char(16) NOT NULL,
  `asset_file_id` bigint(20) NOT NULL,
  `url_thumbnail` varchar(500) NOT NULL,
  `title` varchar(255) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `agency_id` int(11) NOT NULL,
  `device_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `format_id` int(11) NOT NULL,
  `sales_type_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `click_url` varchar(500) DEFAULT NULL,
  `workflow_status` enum('draft','reviewed','approved') NOT NULL DEFAULT 'draft',
  `status` enum('active','archived','pending') NOT NULL DEFAULT 'active',
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`),
  KEY `idx_creative_brand` (`brand_id`),
  KEY `idx_creative_agency` (`agency_id`),
  KEY `idx_creative_device` (`device_id`),
  KEY `idx_creative_format` (`format_id`),
  KEY `idx_creative_country` (`country_id`),
  KEY `idx_creative_sales_type` (`sales_type_id`),
  KEY `idx_creative_status` (`status`),
  KEY `idx_creative_language_id` (`language_id`),
  KEY `fk_creative_asset` (`asset_file_id`),
  KEY `fk_creative_product` (`product_id`),
  KEY `fk_creative_user` (`user_id`),
  CONSTRAINT `fk_creative_agency` FOREIGN KEY (`agency_id`) REFERENCES `ADSHOWCASE_agency` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_creative_asset` FOREIGN KEY (`asset_file_id`) REFERENCES `ADSHOWCASE_asset_file` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_creative_brand` FOREIGN KEY (`brand_id`) REFERENCES `ADSHOWCASE_brand` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_creative_country` FOREIGN KEY (`country_id`) REFERENCES `ADSHOWCASE_country` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_creative_device` FOREIGN KEY (`device_id`) REFERENCES `ADSHOWCASE_device` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_creative_format` FOREIGN KEY (`format_id`) REFERENCES `ADSHOWCASE_format` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_creative_language_id` FOREIGN KEY (`language_id`) REFERENCES `ADSHOWCASE_language_locale` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_creative_product` FOREIGN KEY (`product_id`) REFERENCES `ADSHOWCASE_product` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_creative_sales_type` FOREIGN KEY (`sales_type_id`) REFERENCES `ADSHOWCASE_sales_type` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_creative_user` FOREIGN KEY (`user_id`) REFERENCES `ADSHOWCASE_user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=151 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ADSHOWCASE_creative`
--

LOCK TABLES `ADSHOWCASE_creative` WRITE;
/*!40000 ALTER TABLE `ADSHOWCASE_creative` DISABLE KEYS */;
INSERT INTO `ADSHOWCASE_creative` VALUES
(1,'Eb6vV1PkVWoXLPnM',16,'https://picsum.photos/seed/6959a80286bdf/1280/720','Polarised bottom-line implementation',245,67,3,30,13,1,4,3,'https://www.bbc.com','approved','archived',1,'2025-07-16 10:17:46','2026-01-03 23:36:34'),
(2,'ssIPRP8Te8asadYT',21,'https://picsum.photos/seed/6959a8028755d/1280/720','Switchable disintermediate complexity',203,35,3,40,3,6,7,2,'https://www.github.com','reviewed','pending',4,'2025-11-24 05:14:53','2026-01-03 23:36:34'),
(3,'sgf5qZFVh8S62JHA',31,'https://picsum.photos/seed/6959a802875ad/1280/720','Configurable didactic archive',31,5,3,32,29,1,1,2,'https://www.cnn.com','draft','pending',1,'2025-08-06 09:09:41','2026-01-03 23:36:34'),
(4,'Rk27lBiVDF7QHNpn',4,'https://picsum.photos/seed/6959a802875ed/1280/720','Universal optimal hierarchy',427,59,2,32,6,4,9,3,'https://www.spotify.com','draft','active',1,'2025-08-17 21:53:29','2026-01-03 23:36:34'),
(5,'80RKDRvYX33qlie2',33,'https://picsum.photos/seed/6959a8028762a/1280/720','Distributed client-server collaboration',371,31,1,9,16,5,10,1,'https://www.forbes.com','draft','archived',1,'2025-01-05 07:02:14','2026-01-03 23:36:34'),
(6,'1dIK1jfOzcH9fOqY',81,'https://picsum.photos/seed/6959a80287666/1280/720','Innovative empowering contingency',19,77,3,32,11,2,13,1,'https://www.facebook.com','approved','pending',4,'2025-03-07 06:04:30','2026-01-03 23:36:34'),
(7,'A2E0mulmTYclembq',45,'https://picsum.photos/seed/6959a802876a3/1280/720','Upgradable bi-directional budgetarymanagement',301,68,3,31,17,4,18,1,'https://www.adidas.com','draft','archived',3,'2025-11-04 06:50:43','2026-01-03 23:36:34'),
(8,'AiAvwYVRnFfR3BuA',100,'https://picsum.photos/seed/6959a802876df/1280/720','Automated client-driven model',308,57,3,39,30,2,3,3,'https://www.salesforce.com','reviewed','active',4,'2026-01-02 09:16:21','2026-01-03 23:36:34'),
(9,'GMyGPuDSCktw6fpY',20,'https://picsum.photos/seed/6959a8028771c/1280/720','Synergistic executive knowledgeuser',475,8,2,17,1,3,12,3,'https://www.pinterest.com','reviewed','archived',2,'2025-03-18 01:47:35','2026-01-03 23:36:34'),
(10,'KgJC8O8n9ExVJ83N',42,'https://picsum.photos/seed/6959a80287758/1280/720','Synergistic system-worthy instructionset',437,76,1,30,6,5,8,2,'https://www.tesla.com','draft','active',1,'2025-05-24 12:11:52','2026-01-03 23:36:34'),
(11,'ED6EOLZtVOVUU6jF',62,'https://picsum.photos/seed/6959a80287795/1280/720','Integrated actuating analyzer',255,1,2,28,3,3,9,2,'https://www.pinterest.com','reviewed','pending',2,'2025-12-18 05:22:43','2026-01-03 23:36:34'),
(12,'2KVHXySSxCd4qVyM',35,'https://picsum.photos/seed/6959a802877d0/1280/720','Extended static orchestration',67,84,1,14,28,6,6,1,'https://www.pinterest.com','approved','pending',1,'2025-04-20 15:00:48','2026-01-03 23:36:34'),
(13,'bVtY4d3tXUFYM4f4',42,'https://picsum.photos/seed/6959a8028780a/1280/720','Public-key human-resource hardware',240,65,3,2,28,5,17,1,'https://www.zoom.us','approved','pending',1,'2025-06-02 08:06:09','2026-01-03 23:36:34'),
(14,'mmOcQdPqou2ZQrA5',34,'https://picsum.photos/seed/6959a80287844/1280/720','Profit-focused 5thgeneration migration',467,26,1,1,1,4,19,3,'https://www.linkedin.com','approved','active',2,'2025-03-08 03:53:55','2026-01-03 23:36:34'),
(15,'zBLDIm3WLSARTD8x',21,'https://picsum.photos/seed/6959a80287883/1280/720','Mandatory nextgeneration knowledgeuser',253,77,2,13,21,1,14,1,'https://www.skyscanner.com','reviewed','active',1,'2025-05-08 19:57:13','2026-01-03 23:36:34'),
(16,'P0yyTkgeQ5aQoP7q',89,'https://picsum.photos/seed/6959a802878bf/1280/720','Self-enabling fault-tolerant processimprovement',309,59,2,15,4,1,16,2,'https://www.booking.com','draft','archived',3,'2025-11-12 18:56:22','2026-01-03 23:36:34'),
(17,'NoLAXSyOH9jmaSCt',100,'https://picsum.photos/seed/6959a80287903/1280/720','Multi-tiered optimizing neural-net',139,14,3,35,26,4,9,2,'https://www.nike.com','reviewed','archived',1,'2025-03-26 19:40:01','2026-01-03 23:36:34'),
(18,'VlQANYvFa25cfs2f',22,'https://picsum.photos/seed/6959a8028793f/1280/720','Centralized coherent complexity',189,55,2,40,3,3,15,3,'https://www.airbnb.com','approved','archived',4,'2025-02-20 23:03:17','2026-01-03 23:36:34'),
(19,'ZMcymCAbg7s1NK4p',63,'https://picsum.photos/seed/6959a8028797a/1280/720','Progressive zerotolerance GraphicInterface',202,9,1,16,8,6,15,2,'https://www.bestbuy.com','approved','pending',1,'2025-07-16 22:18:45','2026-01-03 23:36:34'),
(20,'W5zk2wvCNTYY6hFP',5,'https://picsum.photos/seed/6959a802879ba/1280/720','Inverse exuding knowledgeuser',392,94,2,28,24,4,8,2,'https://www.tesla.com','draft','pending',2,'2025-02-19 11:41:12','2026-01-03 23:36:34'),
(21,'ElKpais24ZGrTE3W',45,'https://picsum.photos/seed/6959a80287a31/1280/720','Re-contextualized optimal knowledgeuser',80,13,2,26,29,2,21,1,'https://www.apple.com','approved','pending',2,'2025-04-07 04:02:10','2026-01-03 23:36:34'),
(22,'yJawPSLdJJGCd8jm',60,'https://picsum.photos/seed/6959a80287a6f/1280/720','Proactive discrete help-desk',166,53,3,5,18,6,12,3,'https://www.spotify.com','reviewed','archived',1,'2025-07-14 11:07:33','2026-01-03 23:36:34'),
(23,'SBnkABsZB2IIAq7G',86,'https://picsum.photos/seed/6959a80287aab/1280/720','Configurable regional GraphicalUserInterface',437,32,2,49,18,2,10,2,'https://www.zara.com','approved','archived',2,'2025-01-11 14:44:17','2026-01-03 23:36:34'),
(24,'iAKyxu2dXCyTjitM',53,'https://picsum.photos/seed/6959a80287ae7/1280/720','Right-sized logistical matrix',486,58,2,19,14,1,18,1,'https://www.amazon.com','draft','archived',2,'2025-03-31 09:43:55','2026-01-03 23:36:34'),
(25,'yyEZyQcCeKJOD4t6',45,'https://picsum.photos/seed/6959a80287b22/1280/720','Secured empowering opensystem',210,68,3,19,10,1,6,2,'https://www.expedia.com','approved','active',3,'2025-11-15 04:15:45','2026-01-03 23:36:34'),
(26,'dRwSqfwF5rBTOl7d',83,'https://picsum.photos/seed/6959a80287b5e/1280/720','Upgradable demand-driven architecture',497,39,3,36,14,1,4,3,'https://www.nytimes.com','draft','active',2,'2025-04-22 10:29:39','2026-01-03 23:36:34'),
(27,'4d43TJPRjGMQxWBb',30,'https://picsum.photos/seed/6959a80287b9a/1280/720','Monitored neutral data-warehouse',320,81,2,49,3,2,13,2,'https://www.youtube.com','approved','active',1,'2025-02-25 15:53:28','2026-01-03 23:36:34'),
(28,'9ivSPw161nYIIevW',82,'https://picsum.photos/seed/6959a80287bd3/1280/720','Function-based incremental framework',368,8,3,2,3,1,20,2,'https://www.wired.com','approved','archived',4,'2025-02-04 21:24:59','2026-01-03 23:36:34'),
(29,'gcFFtF3Dosyi79K3',55,'https://picsum.photos/seed/6959a80287c12/1280/720','Reverse-engineered needs-based intranet',102,58,1,20,28,5,14,2,'https://www.techcrunch.com','reviewed','pending',3,'2025-01-13 05:19:36','2026-01-03 23:36:34'),
(30,'56cdweHnPzvCZfF3',45,'https://picsum.photos/seed/6959a80287c4c/1280/720','Configurable national encryption',486,86,3,30,19,5,18,3,'https://www.tesla.com','reviewed','active',4,'2025-12-09 01:01:20','2026-01-03 23:36:34'),
(31,'LQ8KdcVlY6UImU45',37,'https://picsum.photos/seed/6959a80287c89/1280/720','Cloned tangible conglomeration',368,71,2,41,18,1,14,2,'https://www.dropbox.com','approved','active',1,'2025-12-10 04:02:05','2026-01-03 23:36:34'),
(32,'AHXh8IF4CRnz33ff',24,'https://picsum.photos/seed/6959a80287cd0/1280/720','Programmable homogeneous functionalities',90,24,2,10,15,6,2,3,'https://www.adobe.com','reviewed','pending',1,'2025-10-27 05:39:00','2026-01-03 23:36:34'),
(33,'XprFFluoifFbfXxC',49,'https://picsum.photos/seed/6959a80287d0c/1280/720','Public-key logistical opensystem',360,89,1,40,8,6,12,1,'https://www.github.com','reviewed','pending',2,'2025-05-19 23:45:08','2026-01-03 23:36:34'),
(34,'lTuJnvcPxJX23bZb',78,'https://picsum.photos/seed/6959a80287d48/1280/720','Monitored coherent software',268,11,3,10,13,4,9,1,'https://www.booking.com','reviewed','archived',1,'2025-04-01 18:15:41','2026-01-03 23:36:34'),
(35,'8nx4a7fPz4Sn2hO4',57,'https://picsum.photos/seed/6959a80287d84/1280/720','Robust global help-desk',305,45,2,35,18,3,18,1,'https://www.walmart.com','approved','pending',2,'2025-03-15 04:27:02','2026-01-03 23:36:34'),
(36,'mzZM0j4Ij8vXmMmD',91,'https://picsum.photos/seed/6959a80287dc0/1280/720','Decentralized composite productivity',280,41,3,3,18,6,18,1,'https://www.forbes.com','approved','active',1,'2025-11-29 16:47:18','2026-01-03 23:36:34'),
(37,'DAWPEKYTLQlls8pw',64,'https://picsum.photos/seed/6959a80287dfb/1280/720','Persistent nextgeneration project',272,71,2,13,6,5,20,3,'https://www.tripadvisor.com','draft','pending',1,'2025-04-22 04:46:36','2026-01-03 23:36:34'),
(38,'McGCNpmavaLWF1iB',31,'https://picsum.photos/seed/6959a80287e37/1280/720','Polarised contextually-based adapter',164,29,1,38,23,6,15,2,'https://www.pinterest.com','approved','archived',3,'2025-09-18 15:48:46','2026-01-03 23:36:34'),
(39,'lDusWKwAHTfgWNjp',3,'https://picsum.photos/seed/6959a80287e73/1280/720','Team-oriented zeroadministration opensystem',136,94,1,21,16,1,2,2,'https://www.zoom.us','draft','active',2,'2025-06-09 22:55:22','2026-01-03 23:36:34'),
(40,'ZwI8dAr0VQiZfEvq',68,'https://picsum.photos/seed/6959a80287eae/1280/720','Decentralized intermediate help-desk',235,85,1,36,27,6,16,1,'https://www.dropbox.com','approved','active',3,'2025-02-27 20:00:24','2026-01-03 23:36:34'),
(41,'d9s4MVKVi72dNr8p',33,'https://picsum.photos/seed/6959a80287eea/1280/720','Synchronised web-enabled budgetarymanagement',327,78,2,2,21,2,21,2,'https://www.pinterest.com','draft','pending',2,'2025-05-09 22:15:42','2026-01-03 23:36:34'),
(42,'EAiwJyGh3xoFUpmL',32,'https://picsum.photos/seed/6959a80287f26/1280/720','Organic 24/7 protocol',445,21,2,13,12,1,20,3,'https://www.twitter.com','approved','archived',2,'2025-10-18 23:06:35','2026-01-03 23:36:34'),
(43,'x8FRUS4rMVlzWuq4',76,'https://picsum.photos/seed/6959a80287f61/1280/720','Ameliorated background benchmark',360,85,1,37,12,5,14,2,'https://www.apple.com','draft','active',2,'2025-09-14 01:17:24','2026-01-03 23:36:34'),
(44,'xG0A8FRA2WOUo1E4',85,'https://picsum.photos/seed/6959a80287f9f/1280/720','Fundamental incremental structure',162,59,3,36,9,2,20,1,'https://www.target.com','approved','pending',4,'2025-07-20 17:52:07','2026-01-03 23:36:34'),
(45,'9kYf5qvAXx6iPnjT',82,'https://picsum.photos/seed/6959a80287fdb/1280/720','Digitized scalable collaboration',253,32,3,33,7,2,17,3,'https://www.techcrunch.com','approved','pending',1,'2025-03-21 09:25:29','2026-01-03 23:36:34'),
(46,'6RhRcR64qXeEU3WF',39,'https://picsum.photos/seed/6959a80288017/1280/720','Devolved multi-state website',90,84,1,27,20,1,3,3,'https://www.walmart.com','approved','pending',1,'2025-02-19 01:33:10','2026-01-03 23:36:34'),
(47,'ZOabwldJFRQPS0MF',73,'https://picsum.photos/seed/6959a80288053/1280/720','De-engineered 3rdgeneration policy',122,9,3,33,23,2,14,1,'https://www.nike.com','reviewed','archived',3,'2025-12-12 19:18:09','2026-01-03 23:36:34'),
(48,'xIXDYpXxaH8Eho05',68,'https://picsum.photos/seed/6959a8028808e/1280/720','Ameliorated reciprocal groupware',149,95,1,48,20,6,10,3,'https://www.skyscanner.com','reviewed','archived',2,'2025-04-12 01:13:08','2026-01-03 23:36:34'),
(49,'6A8KQo3v6WbZRaX2',30,'https://picsum.photos/seed/6959a802880d7/1280/720','Object-based fresh-thinking artificialintelligence',1,67,3,47,17,6,17,2,'https://www.techcrunch.com','approved','active',4,'2025-03-15 22:49:28','2026-01-03 23:36:34'),
(50,'eYUDqYrEMSFJixbp',41,'https://picsum.photos/seed/6959a80288117/1280/720','Seamless transitional alliance',488,22,1,11,23,1,17,3,'https://www.youtube.com','reviewed','active',4,'2025-05-05 19:57:33','2026-01-03 23:36:34'),
(51,'gXaYqURHIbmyZU64',42,'https://picsum.photos/seed/6959a80288156/1280/720','Persistent motivating adapter',238,86,2,39,24,3,18,1,'https://www.pinterest.com','reviewed','archived',1,'2025-01-07 20:49:36','2026-01-03 23:36:34'),
(52,'daaTdkDURWOjOhjX',52,'https://picsum.photos/seed/6959a80288192/1280/720','Front-line fault-tolerant framework',84,80,2,20,4,1,15,2,'https://www.tesla.com','draft','active',1,'2025-12-11 13:50:13','2026-01-03 23:36:34'),
(53,'5iUVXvHD792ERZNv',96,'https://picsum.photos/seed/6959a802881ce/1280/720','Expanded bi-directional database',408,64,3,13,11,2,13,3,'https://www.salesforce.com','reviewed','pending',1,'2025-09-24 06:49:28','2026-01-03 23:36:34'),
(54,'cbRLer3wqW7vnJ26',84,'https://picsum.photos/seed/6959a8028820a/1280/720','Pre-emptive attitude-oriented moderator',401,66,3,12,23,5,5,1,'https://www.cnn.com','approved','active',1,'2025-12-20 08:08:45','2026-01-03 23:36:34'),
(55,'SPuiVnZVMYGJJC9U',73,'https://picsum.photos/seed/6959a80288246/1280/720','Re-engineered maximized capability',302,60,3,15,3,2,12,1,'https://www.pinterest.com','reviewed','active',4,'2025-11-17 05:07:37','2026-01-03 23:36:34'),
(56,'UZm76pC1FXTqaw9w',13,'https://picsum.photos/seed/6959a80288282/1280/720','Universal non-volatile algorithm',213,88,3,29,16,6,7,3,'https://www.nike.com','approved','archived',2,'2025-03-04 08:10:50','2026-01-03 23:36:34'),
(57,'pOcjV2rXIqdzCdA5',49,'https://picsum.photos/seed/6959a802882bd/1280/720','Right-sized upward-trending projection',202,30,3,39,10,6,5,2,'https://www.twitter.com','draft','archived',3,'2025-10-21 22:51:59','2026-01-03 23:36:34'),
(58,'ptbDqd9EOmcqhMFt',60,'https://picsum.photos/seed/6959a802882f9/1280/720','Diverse directional initiative',112,45,3,27,2,5,15,2,'https://www.slack.com','reviewed','archived',2,'2025-09-29 13:31:00','2026-01-03 23:36:34'),
(59,'rJROyGwGYilsSs3x',9,'https://picsum.photos/seed/6959a80288334/1280/720','Devolved discrete project',165,41,1,10,18,6,13,1,'https://www.booking.com','draft','pending',3,'2025-06-14 23:28:54','2026-01-03 23:36:34'),
(60,'K16aupG81qbT13eV',95,'https://picsum.photos/seed/6959a80288370/1280/720','Team-oriented background definition',283,63,2,45,8,4,21,2,'https://www.wikipedia.org','reviewed','pending',2,'2025-08-02 07:40:52','2026-01-03 23:36:34'),
(61,'brEFh1T4MHcqkhft',93,'https://picsum.photos/seed/6959a802883ac/1280/720','Phased exuding forecast',174,77,1,6,18,6,12,1,'https://www.microsoft.com','approved','active',1,'2025-10-24 15:10:48','2026-01-03 23:36:34'),
(62,'pOkYLhk0diZISpcg',29,'https://picsum.photos/seed/6959a80288436/1280/720','Decentralized zerodefect contingency',105,96,2,24,2,2,3,1,'https://www.slack.com','approved','active',2,'2025-02-04 12:53:54','2026-01-03 23:36:34'),
(63,'ZIesH2whGzN3KRYn',1,'https://picsum.photos/seed/6959a802884ab/1280/720','Quality-focused incremental GraphicInterface',365,99,3,16,30,2,4,3,'https://www.techcrunch.com','approved','archived',3,'2025-03-29 09:33:20','2026-01-03 23:36:34'),
(64,'pZY0VhfprbdfJ2qI',18,'https://picsum.photos/seed/6959a802884eb/1280/720','Reverse-engineered zerotolerance intranet',79,19,2,44,27,3,11,1,'https://www.slack.com','reviewed','pending',3,'2025-12-23 05:42:37','2026-01-03 23:36:34'),
(65,'FCetldT02ySOnUi1',33,'https://picsum.photos/seed/6959a80288529/1280/720','Decentralized bifurcated groupware',69,57,2,29,25,4,7,1,'https://www.samsung.com','reviewed','pending',3,'2025-10-19 10:16:38','2026-01-03 23:36:34'),
(66,'UctFeWBs0RUYWR0J',71,'https://picsum.photos/seed/6959a80288567/1280/720','Adaptive leadingedge project',10,49,2,47,19,5,12,3,'https://www.uber.com','reviewed','archived',2,'2025-01-13 00:55:21','2026-01-03 23:36:34'),
(67,'kEBhaauE5Yymhsw0',28,'https://picsum.photos/seed/6959a802885a1/1280/720','Seamless system-worthy synergy',33,86,2,4,6,3,10,3,'https://www.nikon.com','approved','active',3,'2025-05-23 08:25:26','2026-01-03 23:36:34'),
(68,'CBG8TN1D6OxjcHNj',22,'https://picsum.photos/seed/6959a802885da/1280/720','Function-based system-worthy customerloyalty',146,57,2,27,12,5,6,3,'https://www.nytimes.com','approved','pending',1,'2025-07-27 20:28:30','2026-01-03 23:36:34'),
(69,'0PBhf2co0R0fJtIc',55,'https://picsum.photos/seed/6959a80288613/1280/720','Robust bandwidth-monitored paradigm',234,5,3,31,17,6,21,2,'https://www.walmart.com','draft','archived',3,'2025-03-21 10:37:39','2026-01-03 23:36:34'),
(70,'IKfbrLHvon6xyFbA',50,'https://picsum.photos/seed/6959a8028864b/1280/720','Customizable fault-tolerant knowledgeuser',154,61,1,25,30,4,9,1,'https://www.google.com','approved','archived',3,'2025-07-27 05:59:32','2026-01-03 23:36:34'),
(71,'TyiOHiwypjJNkmre',2,'https://picsum.photos/seed/6959a80288684/1280/720','Virtual uniform model',102,60,3,12,4,3,12,3,'https://www.forbes.com','reviewed','active',1,'2025-07-18 18:43:30','2026-01-03 23:36:34'),
(72,'Gkp2JH3j0PZPNYt8',13,'https://picsum.photos/seed/6959a802886bc/1280/720','Profound bifurcated emulation',382,85,1,39,30,3,20,2,'https://www.wired.com','approved','active',1,'2025-07-13 21:22:07','2026-01-03 23:36:34'),
(73,'pxWdltGxsPVDlfab',12,'https://picsum.photos/seed/6959a802886f5/1280/720','Sharable cohesive ability',449,46,3,32,24,3,2,1,'https://www.nytimes.com','draft','pending',1,'2025-08-02 08:53:27','2026-01-03 23:36:34'),
(74,'I3ED7l1b0yxvkM7x',63,'https://picsum.photos/seed/6959a8028872d/1280/720','Multi-channelled bandwidth-monitored flexibility',383,45,1,17,30,2,4,1,'https://www.bestbuy.com','draft','archived',2,'2025-10-06 01:07:50','2026-01-03 23:36:34'),
(75,'4xTL99kDiRdpJiuH',78,'https://picsum.photos/seed/6959a80288765/1280/720','Reduced coherent processimprovement',278,76,1,27,17,6,19,2,'https://www.wired.com','reviewed','pending',1,'2025-06-15 09:07:23','2026-01-03 23:36:34'),
(76,'XykOj9xSmu6bKZkJ',98,'https://picsum.photos/seed/6959a8028879e/1280/720','Customer-focused web-enabled strategy',218,52,1,18,11,2,9,2,'https://www.facebook.com','draft','pending',2,'2025-11-29 00:21:52','2026-01-03 23:36:34'),
(77,'ZNAHM9SbcjCni42M',89,'https://picsum.photos/seed/6959a802887d6/1280/720','Enterprise-wide neutral encryption',213,3,1,13,26,3,18,2,'https://www.twitch.tv','draft','pending',3,'2025-09-22 19:27:47','2026-01-03 23:36:34'),
(78,'BXnV5MyUyNUGEqdw',41,'https://picsum.photos/seed/6959a8028880f/1280/720','Fully-configurable solution-oriented migration',295,40,3,38,12,6,14,3,'https://www.bestbuy.com','reviewed','pending',2,'2025-04-05 21:59:15','2026-01-03 23:36:34'),
(79,'Z0nlQYTStjkjOexx',26,'https://picsum.photos/seed/6959a80288847/1280/720','Balanced disintermediate software',96,3,1,17,12,5,6,2,'https://www.spotify.com','draft','active',1,'2025-07-03 01:10:44','2026-01-03 23:36:34'),
(80,'PJefZQ2mQPX01GPz',83,'https://picsum.photos/seed/6959a80288887/1280/720','Robust leadingedge processimprovement',82,70,3,22,25,1,13,2,'https://www.slack.com','draft','pending',1,'2025-11-04 08:02:13','2026-01-03 23:36:34'),
(81,'g4MQ6KwqvuuZYlNl',52,'https://picsum.photos/seed/6959a802888ec/1280/720','Advanced asymmetric application',425,83,2,5,9,3,18,2,'https://www.reddit.com','approved','archived',1,'2025-02-07 19:38:45','2026-01-03 23:36:34'),
(82,'y3fuFqhgd9vyHFsv',73,'https://picsum.photos/seed/6959a8028893c/1280/720','Diverse national definition',200,34,3,4,11,3,13,2,'https://www.amazon.com','draft','archived',2,'2025-12-01 19:20:24','2026-01-03 23:36:34'),
(83,'MrYnoSWIJpXHwG3h',56,'https://picsum.photos/seed/6959a80288979/1280/720','Quality-focused reciprocal alliance',4,80,1,29,12,6,4,1,'https://www.airbnb.com','draft','archived',4,'2025-04-18 07:50:49','2026-01-03 23:36:34'),
(84,'owIpDpSkTAIRniR2',48,'https://picsum.photos/seed/6959a802889b3/1280/720','Progressive client-server encoding',262,3,2,28,30,5,16,2,'https://www.skyscanner.com','draft','pending',3,'2025-03-23 08:12:27','2026-01-03 23:36:34'),
(85,'AZyi6VN7pP2Tbh3n',74,'https://picsum.photos/seed/6959a802889eb/1280/720','Cross-platform 4thgeneration parallelism',237,1,3,41,8,6,21,3,'https://www.twitch.tv','reviewed','archived',1,'2025-12-14 18:04:11','2026-01-03 23:36:34'),
(86,'0p0DleUybYV64mqp',100,'https://picsum.photos/seed/6959a80288a24/1280/720','Re-engineered well-modulated task-force',105,58,1,19,18,3,18,2,'https://www.twitch.tv','draft','pending',4,'2025-04-17 22:50:06','2026-01-03 23:36:34'),
(87,'EASDfUFU3xt79JAC',89,'https://picsum.photos/seed/6959a80288a5c/1280/720','Function-based local approach',20,35,3,42,4,1,9,1,'https://www.adidas.com','approved','active',1,'2025-12-01 18:29:28','2026-01-03 23:36:34'),
(88,'hegB54jUkGTu4Egx',24,'https://picsum.photos/seed/6959a80288a95/1280/720','Persistent cohesive collaboration',144,99,3,9,8,5,19,3,'https://www.forbes.com','draft','active',1,'2025-03-02 13:16:30','2026-01-03 23:36:34'),
(89,'8OefZZOgW2PkWrem',84,'https://picsum.photos/seed/6959a80288ace/1280/720','Fundamental bottom-line intranet',278,83,3,25,2,3,7,3,'https://www.slack.com','reviewed','pending',1,'2025-06-05 23:03:58','2026-01-03 23:36:34'),
(90,'rAbrhXtQEj5a9u51',4,'https://picsum.photos/seed/6959a80288b06/1280/720','Up-sized fault-tolerant application',252,79,1,33,8,4,20,3,'https://www.google.com','draft','active',4,'2025-02-22 14:07:24','2026-01-03 23:36:34'),
(91,'pGTtiqpZ5Evj5Nxw',90,'https://picsum.photos/seed/6959a80288b3e/1280/720','Focused motivating migration',464,87,2,17,20,2,18,1,'https://www.wikipedia.org','reviewed','active',1,'2025-12-02 20:23:41','2026-01-03 23:36:34'),
(92,'be5cdat9DLtoGYFk',40,'https://picsum.photos/seed/6959a80288b76/1280/720','Down-sized empowering securedline',9,68,2,5,22,3,4,3,'https://www.nikon.com','draft','pending',4,'2025-08-23 12:01:37','2026-01-03 23:36:34'),
(93,'s0i3OtyQghtPliJ9',21,'https://picsum.photos/seed/6959a80288bae/1280/720','Object-based asynchronous function',127,97,2,49,21,5,16,2,'https://www.google.com','draft','active',2,'2025-10-02 23:27:27','2026-01-03 23:36:34'),
(94,'2YJkNE0LUn73SSI3',33,'https://picsum.photos/seed/6959a80288be7/1280/720','Multi-channelled coherent portal',276,61,2,5,2,3,15,1,'https://www.salesforce.com','reviewed','active',2,'2025-11-09 09:12:04','2026-01-03 23:36:34'),
(95,'gW3ux2JNhkQdpIZb',89,'https://picsum.photos/seed/6959a80288c1f/1280/720','Reactive clear-thinking conglomeration',14,33,3,49,30,4,10,2,'https://www.canon.com','reviewed','archived',4,'2025-02-04 12:13:28','2026-01-03 23:36:34'),
(96,'AqRfMNlTS7ewhfxF',25,'https://picsum.photos/seed/6959a80288c65/1280/720','Proactive non-volatile installation',148,27,1,35,1,3,14,2,'https://www.theverge.com','draft','pending',4,'2025-05-10 12:03:22','2026-01-03 23:36:34'),
(97,'Bo2DDB1OTqNfLnOj',54,'https://picsum.photos/seed/6959a80288ca2/1280/720','Re-engineered national approach',268,23,3,39,24,6,16,3,'https://www.theverge.com','approved','active',4,'2025-11-28 05:28:01','2026-01-03 23:36:34'),
(98,'gQWJ36QAEX6y9Am5',71,'https://picsum.photos/seed/6959a80288cde/1280/720','Centralized cohesive neural-net',119,10,2,38,13,1,6,3,'https://www.sony.com','reviewed','pending',4,'2025-07-06 08:17:15','2026-01-03 23:36:34'),
(99,'T95HxDc1zGpvETph',98,'https://picsum.photos/seed/6959a80288d1a/1280/720','Proactive discrete application',421,52,1,15,9,5,17,2,'https://www.target.com','approved','pending',2,'2025-09-21 15:22:45','2026-01-03 23:36:34'),
(100,'wGeq4lwooVP4W6i4',7,'https://picsum.photos/seed/6959a80288d56/1280/720','Cross-platform discrete time-frame',237,19,2,25,27,6,4,2,'https://www.bestbuy.com','approved','archived',1,'2025-01-07 16:06:54','2026-01-03 23:36:34'),
(101,'Thl2mDnaWsz7Pj9b',4,'https://picsum.photos/seed/6959a80288d91/1280/720','Future-proofed background moderator',63,82,3,26,25,3,18,1,'https://www.airbnb.com','draft','active',1,'2025-05-06 16:03:04','2026-01-03 23:36:34'),
(102,'abT5UQCEnidwZA8l',24,'https://picsum.photos/seed/6959a80288dcc/1280/720','Ergonomic real-time toolset',367,24,3,9,8,1,16,3,'https://www.lg.com','approved','archived',1,'2025-09-21 01:51:37','2026-01-03 23:36:34'),
(103,'HyLhE4O31KuAeZwf',68,'https://picsum.photos/seed/6959a80288e05/1280/720','Universal executive customerloyalty',182,77,2,19,2,1,18,1,'https://www.wikipedia.org','draft','pending',1,'2025-09-04 03:48:30','2026-01-03 23:36:34'),
(104,'N5VK6QYBGkAxhFHV',63,'https://picsum.photos/seed/6959a80288e3d/1280/720','Enhanced reciprocal product',4,98,2,36,28,1,16,3,'https://www.airbnb.com','reviewed','active',3,'2025-11-13 00:14:02','2026-01-03 23:36:34'),
(105,'FGqCTCl61YNhsWX2',49,'https://picsum.photos/seed/6959a80288e76/1280/720','Monitored bandwidth-monitored success',472,45,3,26,27,6,3,2,'https://www.sony.com','draft','archived',3,'2025-12-10 15:22:47','2026-01-03 23:36:34'),
(106,'iVtDJbk3u6Lyf7hT',91,'https://picsum.photos/seed/6959a80288eae/1280/720','Extended systematic opensystem',141,47,2,35,4,2,7,1,'https://www.skyscanner.com','draft','archived',3,'2025-08-16 01:44:28','2026-01-03 23:36:34'),
(107,'zsQMoUfkCvyZ401f',90,'https://picsum.photos/seed/6959a80288ee6/1280/720','Programmable dedicated implementation',395,26,2,40,25,6,20,1,'https://www.amazon.com','draft','active',2,'2025-11-02 19:00:45','2026-01-03 23:36:34'),
(108,'m4jYVZccrdYB0WI4',15,'https://picsum.photos/seed/6959a80288f1f/1280/720','Vision-oriented actuating portal',33,52,1,14,17,4,16,1,'https://www.adidas.com','reviewed','active',2,'2025-04-05 21:09:18','2026-01-03 23:36:34'),
(109,'wVeqxjtx6uy4Ok50',97,'https://picsum.photos/seed/6959a80288f56/1280/720','Polarised solution-oriented interface',138,63,3,8,2,5,20,1,'https://www.apple.com','draft','pending',3,'2025-08-27 17:42:02','2026-01-03 23:36:34'),
(110,'nUKXSkSPvDDeHASB',91,'https://picsum.photos/seed/6959a80288f8e/1280/720','Assimilated bi-directional opensystem',469,41,2,15,23,5,13,2,'https://www.lg.com','draft','archived',1,'2025-08-10 02:45:22','2026-01-03 23:36:34'),
(111,'QySP3KUDXPF29fYI',43,'https://picsum.photos/seed/6959a80288fc6/1280/720','Quality-focused global middleware',322,56,3,32,29,1,9,3,'https://www.canon.com','draft','active',3,'2025-07-19 17:26:21','2026-01-03 23:36:34'),
(112,'eUQDP5hJKUtuntCn',84,'https://picsum.photos/seed/6959a80288fff/1280/720','Fully-configurable fresh-thinking model',313,60,1,8,28,1,2,3,'https://www.twitter.com','draft','active',4,'2025-03-18 05:26:40','2026-01-03 23:36:34'),
(113,'v4nHCq6Q3Rk5XdEP',23,'https://picsum.photos/seed/6959a80289037/1280/720','Streamlined background database',398,82,2,16,12,5,20,3,'https://www.nytimes.com','reviewed','archived',1,'2025-07-08 03:58:24','2026-01-03 23:36:34'),
(114,'7hKXSPGEp7psbIj9',84,'https://picsum.photos/seed/6959a80289076/1280/720','Automated cohesive concept',193,86,2,32,19,4,1,3,'https://www.twitch.tv','draft','active',1,'2025-12-08 00:12:18','2026-01-03 23:36:34'),
(115,'QAqSeFJncQXCjECb',3,'https://picsum.photos/seed/6959a802890ae/1280/720','Reverse-engineered exuding support',107,14,2,30,20,4,5,3,'https://www.dropbox.com','approved','pending',1,'2025-11-08 18:24:25','2026-01-03 23:36:34'),
(116,'SITStJ1H7vEjuf7L',16,'https://picsum.photos/seed/6959a802890e7/1280/720','Seamless encompassing localareanetwork',364,68,1,29,16,1,11,2,'https://www.wikipedia.org','draft','pending',2,'2025-12-04 08:13:26','2026-01-03 23:36:34'),
(117,'WzvdDXz20a5RJtMv',52,'https://picsum.photos/seed/6959a8028911f/1280/720','Future-proofed value-added installation',96,68,2,5,29,1,1,2,'https://www.github.com','draft','archived',4,'2025-03-09 12:52:52','2026-01-03 23:36:34'),
(118,'fbSVweHEafSyn8qj',53,'https://picsum.photos/seed/6959a80289157/1280/720','Reverse-engineered 5thgeneration extranet',219,73,3,28,9,4,3,3,'https://www.youtube.com','approved','active',4,'2025-07-25 12:59:44','2026-01-03 23:36:34'),
(119,'gI9LxORIxFQD2SmB',83,'https://picsum.photos/seed/6959a80289190/1280/720','Fully-configurable content-based strategy',6,47,3,31,1,6,15,1,'https://www.theverge.com','reviewed','active',3,'2025-03-04 12:20:10','2026-01-03 23:36:34'),
(120,'bRhk0SsYYcSzgEE2',87,'https://picsum.photos/seed/6959a802891c8/1280/720','Stand-alone composite benchmark',92,100,3,4,29,2,20,2,'https://www.techcrunch.com','approved','archived',3,'2025-09-02 04:52:29','2026-01-03 23:36:34'),
(121,'4aCYtWp3vflEc7Bb',61,'https://picsum.photos/seed/6959a80289200/1280/720','Virtual motivating hardware',304,22,1,14,13,5,18,2,'https://www.cnn.com','reviewed','active',1,'2025-05-14 07:27:06','2026-01-03 23:36:34'),
(122,'JP4sh3M3gjK5JcNl',33,'https://picsum.photos/seed/6959a80289238/1280/720','User-friendly 6thgeneration encryption',438,14,3,30,23,1,5,3,'https://www.twitch.tv','draft','pending',2,'2025-12-25 22:22:29','2026-01-03 23:36:34'),
(123,'ToQAea6usUiIPAMj',76,'https://picsum.photos/seed/6959a80289271/1280/720','Grass-roots actuating workforce',48,75,1,13,22,2,1,2,'https://www.sony.com','approved','pending',3,'2025-05-19 17:54:06','2026-01-03 23:36:34'),
(124,'9JCzDKFKvJceCERs',48,'https://picsum.photos/seed/6959a802892a9/1280/720','Decentralized content-based toolset',73,12,3,12,12,5,21,2,'https://www.netflix.com','draft','archived',3,'2025-12-07 20:44:44','2026-01-03 23:36:34'),
(125,'JEkWhnHlNp5K4oAa',17,'https://picsum.photos/seed/6959a802892e0/1280/720','Synchronised composite framework',77,49,3,45,27,5,20,1,'https://www.dropbox.com','approved','active',2,'2025-08-04 02:07:55','2026-01-03 23:36:34'),
(126,'sVf8FbPjEgPxLVhy',63,'https://picsum.photos/seed/6959a80289319/1280/720','Mandatory 4thgeneration core',65,18,1,48,26,3,19,1,'https://www.google.com','reviewed','pending',4,'2025-12-02 09:00:31','2026-01-03 23:36:34'),
(127,'zs1VPAp6H8qcRfuB',9,'https://picsum.photos/seed/6959a80289350/1280/720','Organized asynchronous internetsolution',305,21,3,24,7,5,17,3,'https://www.forbes.com','reviewed','pending',3,'2025-05-06 03:19:36','2026-01-03 23:36:34'),
(128,'pMvjj17jS0xsVIIC',20,'https://picsum.photos/seed/6959a80289389/1280/720','Innovative secondary installation',298,28,1,44,25,2,6,1,'https://www.airbnb.com','reviewed','archived',3,'2025-11-10 06:33:31','2026-01-03 23:36:34'),
(129,'qmSjcBZEziMwJaFS',2,'https://picsum.photos/seed/6959a802893c1/1280/720','Future-proofed solution-oriented benchmark',243,89,1,31,18,5,8,1,'https://www.nike.com','reviewed','active',4,'2025-02-21 23:43:18','2026-01-03 23:36:34'),
(130,'8U5RMzQzGJfPuJAf',92,'https://picsum.photos/seed/6959a802893fb/1280/720','Multi-lateral intangible productivity',122,66,3,26,26,3,16,3,'https://www.airbnb.com','approved','pending',3,'2025-03-20 15:32:56','2026-01-03 23:36:34'),
(131,'8YSutrmqYdiAHic7',9,'https://picsum.photos/seed/6959a80289449/1280/720','Focused dedicated software',307,59,3,9,20,4,1,2,'https://www.sony.com','approved','pending',2,'2025-05-22 13:52:38','2026-01-03 23:36:34'),
(132,'HhXNKDbpfZtuesbD',39,'https://picsum.photos/seed/6959a80289482/1280/720','Pre-emptive intangible paradigm',175,17,3,7,30,1,10,1,'https://www.wikipedia.org','approved','archived',2,'2025-10-19 07:25:40','2026-01-03 23:36:34'),
(133,'xqxerVl7vbfjU6Gd',79,'https://picsum.photos/seed/6959a802894cc/1280/720','Persevering 4thgeneration core',384,3,2,15,6,3,8,3,'https://www.zara.com','draft','active',1,'2025-03-03 06:11:56','2026-01-03 23:36:34'),
(134,'T1SoNINI93Q6HpVK',84,'https://picsum.photos/seed/6959a80289509/1280/720','Phased client-server definition',234,52,2,14,8,2,21,2,'https://www.reddit.com','draft','active',3,'2025-10-18 19:21:49','2026-01-03 23:36:34'),
(135,'31u3pkWJtt55aa5z',27,'https://picsum.photos/seed/6959a8028955f/1280/720','Fully-configurable asynchronous archive',264,38,3,6,10,4,20,1,'https://www.expedia.com','approved','pending',2,'2025-03-02 19:31:53','2026-01-03 23:36:34'),
(136,'6ZMqYlaNZSE0GCxw',78,'https://picsum.photos/seed/6959a802895c5/1280/720','Upgradable multimedia hierarchy',397,100,3,5,10,2,5,3,'https://www.amazon.com','reviewed','archived',1,'2025-06-28 15:44:35','2026-01-03 23:36:34'),
(137,'AgWsf6bFfbLOQAWI',45,'https://picsum.photos/seed/6959a80289616/1280/720','Horizontal hybrid artificialintelligence',7,61,3,44,16,6,8,2,'https://www.spotify.com','draft','pending',1,'2025-02-02 01:46:53','2026-01-03 23:36:34'),
(138,'kCSjQpyNPvGMNU70',8,'https://picsum.photos/seed/6959a8028965f/1280/720','Realigned clear-thinking implementation',120,97,1,41,4,6,4,1,'https://www.uber.com','reviewed','archived',3,'2025-11-26 19:37:00','2026-01-03 23:36:34'),
(139,'rbErvjB4pTLd9iKh',97,'https://picsum.photos/seed/6959a802896ab/1280/720','Open-architected actuating benchmark',69,32,3,29,24,1,19,3,'https://www.samsung.com','approved','pending',2,'2026-01-03 03:33:54','2026-01-03 23:36:34'),
(140,'lTVEpAA5xGIoPNMA',46,'https://picsum.photos/seed/6959a802896f7/1280/720','Extended upward-trending standardization',142,97,2,24,17,2,1,2,'https://www.nytimes.com','approved','archived',1,'2025-12-21 05:35:49','2026-01-03 23:36:34'),
(141,'NVAA7aBUHFuXFkbv',25,'https://picsum.photos/seed/6959a80289734/1280/720','Ergonomic high-level leverage',136,56,3,17,8,3,21,1,'https://www.zoom.us','draft','active',4,'2025-03-07 10:16:28','2026-01-03 23:36:34'),
(142,'IlO634KJ2S3rhHRX',80,'https://picsum.photos/seed/6959a80289770/1280/720','Profit-focused context-sensitive productivity',382,76,1,23,6,5,3,1,'https://www.bestbuy.com','draft','active',4,'2025-11-11 04:49:16','2026-01-03 23:36:34'),
(143,'0rim37zpxsKxh7na',45,'https://picsum.photos/seed/6959a802897aa/1280/720','De-engineered bandwidth-monitored customerloyalty',350,20,2,36,6,5,11,2,'https://www.github.com','approved','active',2,'2025-06-19 05:49:40','2026-01-03 23:36:34'),
(144,'tDaXsCk80bNUsKzs',11,'https://picsum.photos/seed/6959a802897e4/1280/720','Diverse bottom-line strategy',414,11,1,8,3,5,3,1,'https://www.cnn.com','draft','archived',4,'2025-03-02 02:33:09','2026-01-03 23:36:34'),
(145,'l4nsLrtp6zKBivVa',14,'https://picsum.photos/seed/6959a80289828/1280/720','Total clear-thinking focusgroup',138,40,3,43,1,2,20,3,'https://www.ikea.com','draft','pending',1,'2025-08-30 02:47:22','2026-01-03 23:36:34'),
(146,'E0zl2o7DcIpGX9TK',43,'https://picsum.photos/seed/6959a80289861/1280/720','Universal optimizing migration',426,77,3,45,12,2,19,2,'https://www.pinterest.com','draft','active',3,'2025-08-05 22:28:03','2026-01-03 23:36:34'),
(147,'e8Fhj3kthVzTKBfv',43,'https://picsum.photos/seed/6959a8028989b/1280/720','Organic neutral artificialintelligence',418,1,3,45,28,3,3,1,'https://www.sony.com','approved','archived',2,'2025-11-16 21:16:31','2026-01-03 23:36:34'),
(148,'vl2MBH8b9WmEeg4v',73,'https://picsum.photos/seed/6959a802898d5/1280/720','Self-enabling 3rdgeneration definition',210,21,3,49,10,1,4,3,'https://www.twitch.tv','approved','pending',2,'2025-10-04 00:12:53','2026-01-03 23:36:34'),
(149,'ebqFPdq1pC7nSni1',75,'https://picsum.photos/seed/6959a8028990f/1280/720','Exclusive bi-directional leverage',138,75,2,19,30,2,14,3,'https://www.pinterest.com','reviewed','active',2,'2025-07-09 06:05:33','2026-01-03 23:36:34'),
(150,'jNahSRcQQpUXZvJu',96,'https://picsum.photos/seed/6959a80289948/1280/720','De-engineered bi-directional extranet',54,73,2,24,15,4,8,1,'https://www.uber.com','reviewed','archived',1,'2025-03-15 18:47:36','2026-01-03 23:36:34');
/*!40000 ALTER TABLE `ADSHOWCASE_creative` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ADSHOWCASE_device`
--

DROP TABLE IF EXISTS `ADSHOWCASE_device`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ADSHOWCASE_device` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` char(16) NOT NULL,
  `name` varchar(100) NOT NULL,
  `status` enum('active','archived','pending') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ADSHOWCASE_device`
--

LOCK TABLES `ADSHOWCASE_device` WRITE;
/*!40000 ALTER TABLE `ADSHOWCASE_device` DISABLE KEYS */;
INSERT INTO `ADSHOWCASE_device` VALUES
(1,'nZNUtiw0b8rvAj-2','Desktop','active','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(2,'XppuRuKcRDT7jOpQ','Mobile','active','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(3,'Tzjb8NTKhwsKvLc6','Tablet','active','2026-01-04 00:36:33','2026-01-04 00:36:33');
/*!40000 ALTER TABLE `ADSHOWCASE_device` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ADSHOWCASE_fav_list`
--

DROP TABLE IF EXISTS `ADSHOWCASE_fav_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ADSHOWCASE_fav_list` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `hash` char(16) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`),
  KEY `fk_favlist_user` (`user_id`),
  CONSTRAINT `fk_favlist_user` FOREIGN KEY (`user_id`) REFERENCES `ADSHOWCASE_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ADSHOWCASE_fav_list`
--

LOCK TABLES `ADSHOWCASE_fav_list` WRITE;
/*!40000 ALTER TABLE `ADSHOWCASE_fav_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `ADSHOWCASE_fav_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ADSHOWCASE_fav_list_item`
--

DROP TABLE IF EXISTS `ADSHOWCASE_fav_list_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ADSHOWCASE_fav_list_item` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `hash` char(16) NOT NULL,
  `list_id` bigint(20) NOT NULL,
  `creative_id` bigint(20) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`),
  KEY `fk_favitem_list` (`list_id`),
  KEY `fk_favitem_creative` (`creative_id`),
  CONSTRAINT `fk_favitem_creative` FOREIGN KEY (`creative_id`) REFERENCES `ADSHOWCASE_creative` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_favitem_list` FOREIGN KEY (`list_id`) REFERENCES `ADSHOWCASE_fav_list` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ADSHOWCASE_fav_list_item`
--

LOCK TABLES `ADSHOWCASE_fav_list_item` WRITE;
/*!40000 ALTER TABLE `ADSHOWCASE_fav_list_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `ADSHOWCASE_fav_list_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ADSHOWCASE_favorite`
--

DROP TABLE IF EXISTS `ADSHOWCASE_favorite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ADSHOWCASE_favorite` (
  `user_id` int(11) NOT NULL,
  `creative_id` bigint(20) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`,`creative_id`),
  KEY `fk_fav_creative` (`creative_id`),
  CONSTRAINT `fk_fav_creative` FOREIGN KEY (`creative_id`) REFERENCES `ADSHOWCASE_creative` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_fav_user` FOREIGN KEY (`user_id`) REFERENCES `ADSHOWCASE_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ADSHOWCASE_favorite`
--

LOCK TABLES `ADSHOWCASE_favorite` WRITE;
/*!40000 ALTER TABLE `ADSHOWCASE_favorite` DISABLE KEYS */;
/*!40000 ALTER TABLE `ADSHOWCASE_favorite` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ADSHOWCASE_format`
--

DROP TABLE IF EXISTS `ADSHOWCASE_format`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ADSHOWCASE_format` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` char(16) NOT NULL,
  `name` varchar(150) NOT NULL,
  `format` varchar(100) NOT NULL,
  `family` varchar(100) NOT NULL,
  `experience` varchar(100) NOT NULL,
  `subtype` varchar(100) DEFAULT NULL,
  `status` enum('active','archived','pending') NOT NULL DEFAULT 'active',
  `url_slug` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`),
  UNIQUE KEY `url_slug` (`url_slug`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ADSHOWCASE_format`
--

LOCK TABLES `ADSHOWCASE_format` WRITE;
/*!40000 ALTER TABLE `ADSHOWCASE_format` DISABLE KEYS */;
INSERT INTO `ADSHOWCASE_format` VALUES
(1,'Dj5MBlWEFCLbyTsf','Medium Rectangle (MPU)','300x250','banner','web',NULL,'active','medium-rectangle-mpu','2025-11-28 06:21:11','2025-12-16 09:52:29'),
(2,'VHE5D516FU9wrSpK','Leaderboard','728x90','banner','web',NULL,'active','leaderboard','2025-08-16 08:32:32','2025-09-08 19:41:25'),
(3,'CEAOMkkI5Zf0L7BJ','Wide Skyscraper','160x600','banner','web',NULL,'active','wide-skyscraper','2025-08-13 21:35:17','2025-08-19 13:06:12'),
(4,'JPEKpeFdbDs2riOl','Half Page (Double MPU)','300x600','banner','web',NULL,'active','half-page-double-mpu','2025-09-22 18:05:56','2025-09-26 10:41:23'),
(5,'qF85AI0dbjEtSKLJ','Billboard','970x250','banner','web',NULL,'active','billboard','2025-04-22 12:48:44','2025-04-24 13:49:23'),
(6,'XQoeXI2J3xlArk3P','Large Leaderboard','970x90','banner','web',NULL,'active','large-leaderboard','2025-02-09 03:12:50','2025-02-09 09:10:52'),
(7,'afnSDQ0L7GaPgagv','Smartphone Static Banner','320x50','banner','app','sticky','active','smartphone-static-banner','2025-09-18 20:37:50','2025-09-23 15:53:52'),
(8,'x3veDbTQPf0BKkYX','Large Mobile Banner','320x100','banner','app',NULL,'active','large-mobile-banner','2025-08-11 04:43:51','2025-08-19 10:00:32'),
(9,'_JhDnOvK3kRM58sj','Mobile Interstitial','320x480','banner','app','interstitial','active','mobile-interstitial','2025-06-22 15:15:56','2025-07-10 23:19:20'),
(10,'fwfWGOOT5SCOO-8v','In-Stream Pre-Roll (16:9)','1920x1080','video','in-stream','linear','pending','in-stream-pre-roll-169','2025-05-29 04:10:56','2025-06-19 03:45:23'),
(11,'MJCzyorlzGc93nBs','Out-Stream Video','Responsive','video','out-stream',NULL,'active','out-stream-video','2025-03-14 10:26:46','2025-03-26 00:29:41'),
(12,'O_GHLz8-p84Aebrv','Vertical Video (Stories)','1080x1920','video','app','interstitial','active','vertical-video-stories','2025-08-03 12:52:09','2025-08-21 06:51:17'),
(13,'fxwHAq_L6n4wkwLx','Rewarded Video','Fullscreen','video','app','rewarded','pending','rewarded-video','2025-01-27 00:30:58','2025-02-11 20:06:06'),
(14,'z9KWxv0cYWfrGVTe','Native In-Feed','Fluid','native','web','feed','active','native-in-feed','2025-02-23 12:30:15','2025-03-04 02:32:11'),
(15,'vlqp_vXwDCf2CJaL','Recommendation Widget','Grid','native','web','recommendation','active','recommendation-widget','2025-02-22 20:30:55','2025-03-01 16:48:08'),
(16,'UT4wVJyP-U8Z_gzl','Native App Install','Fluid','native','app','app-install','active','native-app-install','2025-07-25 11:53:39','2025-08-22 18:54:18'),
(17,'HymJdLTKA51RxpD3','Wallpaper / Skin','Custom','banner','web','skin','active','wallpaper-skin','2025-06-05 09:25:03','2025-06-09 03:48:45'),
(18,'0xUMz4ik4EElwu4l','Push Notification','Icon+Text','native','app','push','active','push-notification','2025-05-18 22:49:01','2025-06-10 16:32:49'),
(19,'LeZy_gje6ESyqZro','Banner 346x457 (App)','346x457','banner','app','sticky','active','banner-346x457-app','2025-03-08 01:43:56','2025-03-26 18:14:00'),
(20,'red8lXgbkrUpAZnK','Native 172x717 (App)','172x717','native','app','sticky','active','native-172x717-app','2025-11-05 17:28:55','2025-12-05 08:47:13'),
(21,'RJySJfWEHmDaORpd','Banner 001x880 (Dooh)','001x880','banner','dooh','sticky','active','banner-001x880-dooh','2025-07-03 19:03:20','2025-07-27 07:35:35'),
(22,'oQnjxHCM2VHqO2sG','Native 525x875 (Web)','525x875','native','web','interstitial','active','native-525x875-web','2025-07-03 18:47:36','2025-07-27 02:45:58'),
(23,'XbSX60ec89K0Er1q','Video 483x113 (Web)','483x113','video','web',NULL,'active','video-483x113-web','2025-06-06 15:56:47','2025-06-29 18:32:10'),
(24,'hr0Cq-yiiBPGoMy1','Video 369x241 (Web)','369x241','video','web','sticky','active','video-369x241-web','2025-11-09 17:32:44','2025-11-14 05:19:11'),
(25,'4tSGAQ3pkjFJcy7v','Audio 975x015 (Web)','975x015','audio','web','sticky','active','audio-975x015-web','2025-07-28 08:12:11','2025-08-26 23:39:20'),
(26,'gv02sUFRwLecMe0V','Video 365x617 (Web)','365x617','video','web',NULL,'active','video-365x617-web','2025-11-11 18:16:40','2025-12-04 17:54:25'),
(27,'2g9_04Fiqr2_GYMm','Video 054x956 (Web)','054x956','video','web','sticky','pending','video-054x956-web','2025-09-15 04:39:43','2025-10-11 01:01:58'),
(28,'jjSPv-i3hR6s7KJO','Banner 426x059 (Web)','426x059','banner','web','interstitial','active','banner-426x059-web','2025-12-04 02:54:37','2025-12-15 20:45:57'),
(29,'tshWE8zWKJW3TOnR','Audio 167x337 (Web)','167x337','audio','web',NULL,'active','audio-167x337-web','2025-07-03 02:46:38','2025-07-24 04:20:49'),
(30,'H01-LTu18Bk4ludN','Audio 913x931 (Dooh)','913x931','audio','dooh','sticky','active','audio-913x931-dooh','2025-11-19 17:05:05','2025-11-20 02:42:14');
/*!40000 ALTER TABLE `ADSHOWCASE_format` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ADSHOWCASE_language_locale`
--

DROP TABLE IF EXISTS `ADSHOWCASE_language_locale`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ADSHOWCASE_language_locale` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_code` char(2) NOT NULL,
  `region_code` char(2) DEFAULT NULL,
  `locale_code` varchar(10) NOT NULL,
  `display_name_en` varchar(128) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('active','archived','pending') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale_code` (`locale_code`),
  KEY `idx_language_locale_language_region` (`language_code`,`region_code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ADSHOWCASE_language_locale`
--

LOCK TABLES `ADSHOWCASE_language_locale` WRITE;
/*!40000 ALTER TABLE `ADSHOWCASE_language_locale` DISABLE KEYS */;
INSERT INTO `ADSHOWCASE_language_locale` VALUES
(1,'en','US','en-US','English (United States)',0,'active','2026-01-04 00:36:31','2026-01-04 00:36:31'),
(2,'es','ES','es-ES','Spanish (Spain)',1,'active','2026-01-04 00:36:31','2026-01-04 00:36:31'),
(3,'ca','ES','ca-ES','Catalan (Spain)',0,'active','2026-01-04 00:36:31','2026-01-04 00:36:31');
/*!40000 ALTER TABLE `ADSHOWCASE_language_locale` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ADSHOWCASE_migration`
--

DROP TABLE IF EXISTS `ADSHOWCASE_migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ADSHOWCASE_migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ADSHOWCASE_migration`
--

LOCK TABLES `ADSHOWCASE_migration` WRITE;
/*!40000 ALTER TABLE `ADSHOWCASE_migration` DISABLE KEYS */;
INSERT INTO `ADSHOWCASE_migration` VALUES
('m000000_000000_base',1767483381),
('m140506_102106_rbac_init',1767483382),
('m170907_052038_rbac_add_index_on_auth_assignment_user_id',1767483382),
('m180523_151638_rbac_updates_indexes_without_prefix',1767483382),
('m200409_110543_rbac_update_mssql_trigger',1767483382),
('m251115_195201_create_adshowcase_core',1767483391),
('m251115_200055_seed_rbac',1767483391),
('m251115_205301_seed_language_locale',1767483391),
('m251115_205302_seed_users_per_role',1767483393),
('m251122_082844_seed_brands_fake',1767483393),
('m251123_072819_seed_countries_fake',1767483393),
('m251123_072820_seed_agencies_fake',1767483393),
('m251123_074208_seed_formats_fake',1767483393),
('m251123_075448_seed_devices_fake',1767483393),
('m251123_082603_seed_products_fake',1767483393),
('m251123_084929_seed_sales_type_fake',1767483393),
('m251220_193049_seed_creative_table',1767483394);
/*!40000 ALTER TABLE `ADSHOWCASE_migration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ADSHOWCASE_product`
--

DROP TABLE IF EXISTS `ADSHOWCASE_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ADSHOWCASE_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` char(16) NOT NULL,
  `name` varchar(255) NOT NULL,
  `url_slug` varchar(255) DEFAULT NULL,
  `status` enum('active','archived','pending') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ADSHOWCASE_product`
--

LOCK TABLES `ADSHOWCASE_product` WRITE;
/*!40000 ALTER TABLE `ADSHOWCASE_product` DISABLE KEYS */;
INSERT INTO `ADSHOWCASE_product` VALUES
(1,'g73czesnRvBybZ-Y','Art & Entertainment','art-entertainment','active','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(2,'Zg9BAhQPCJe92ZMy','Animals & Pet','animals-pet','active','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(3,'oZP-2YGDX7VQONpm','Apparel / Fashion & Jewelry','apparel-fashion-jewelry','active','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(4,'KeK8JujpKFZZO_Xr','Automotive','automotive','active','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(5,'GEHS9HeulND93Umd','Beauty & Personal Care','beauty-personal-care','active','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(6,'sQ_euXgY9XydMUSs','Alcoholic Beverages','alcoholic-beverages','active','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(7,'EPtVGKjxjtmwdKM0','Education & Employment','education-employment','active','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(8,'-sctm0xDiHdH-oIp','Finance / Insurance & Business','finance-insurance-business','active','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(9,'7B5UlmdEeJ5V6LpN','Pharma / Health & Fitness','pharma-health-fitness','active','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(10,'vjG4eTQD7heL8tF3','Home & Garden','home-garden','active','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(11,'jQr1PmF2HODvzcyw','Restaurants','restaurants','active','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(12,'bIH8XQDm_Kp9woUd','Sports','sports','active','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(13,'Wk5mXXwKbuN2Fhk4','Retail','retail','active','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(14,'f0F3IlgY8oMtLB_S','Travel','travel','active','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(15,'MfoqSIrzEXVwC3tH','Utilities (Energy / Telco and Water)','utilities-energy-telco-and-water','active','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(16,'D43p3uGjDv3TYjPy','Government / Institutional','government-institutional','active','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(17,'WxV1AqcztndJF3Nn','Kids','kids','active','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(18,'zQuvEJhGCgd-CxZj','Gambling','gambling','active','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(19,'VSWO_GR52yBRqYGH','Tech & Electronics','tech-electronics','active','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(20,'oekDxOmcwy0TuExh','Luxury','luxury','active','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(21,'mLqEPHR1GPsbLs_L','Other','other','active','2026-01-04 00:36:33','2026-01-04 00:36:33');
/*!40000 ALTER TABLE `ADSHOWCASE_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ADSHOWCASE_sales_type`
--

DROP TABLE IF EXISTS `ADSHOWCASE_sales_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ADSHOWCASE_sales_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` char(16) NOT NULL,
  `name` varchar(150) NOT NULL,
  `status` enum('active','archived','pending') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ADSHOWCASE_sales_type`
--

LOCK TABLES `ADSHOWCASE_sales_type` WRITE;
/*!40000 ALTER TABLE `ADSHOWCASE_sales_type` DISABLE KEYS */;
INSERT INTO `ADSHOWCASE_sales_type` VALUES
(1,'vGArgSHTt0I1DGXF','Open','active','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(2,'-d1Jd6l_TEAWf2bz','Private Deal','active','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(3,'YqkkmC9CP1Iyw6DW','Direct Campaign','active','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(4,'QgcEBpKMefl5x95v','Partner Deal','active','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(5,'I3LPS7_SfywmL1LP','Autopromo','active','2026-01-04 00:36:33','2026-01-04 00:36:33'),
(6,'LIR7qUFfrN6w2yqI','Mock-up','active','2026-01-04 00:36:33','2026-01-04 00:36:33');
/*!40000 ALTER TABLE `ADSHOWCASE_sales_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ADSHOWCASE_shared_link`
--

DROP TABLE IF EXISTS `ADSHOWCASE_shared_link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ADSHOWCASE_shared_link` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `hash` char(16) NOT NULL,
  `token` char(43) NOT NULL,
  `creative_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `max_uses` int(11) DEFAULT NULL,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `revoked_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `note` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`),
  UNIQUE KEY `token` (`token`),
  KEY `fk_shared_creative` (`creative_id`),
  KEY `fk_shared_owner` (`user_id`),
  CONSTRAINT `fk_shared_creative` FOREIGN KEY (`creative_id`) REFERENCES `ADSHOWCASE_creative` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_shared_owner` FOREIGN KEY (`user_id`) REFERENCES `ADSHOWCASE_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ADSHOWCASE_shared_link`
--

LOCK TABLES `ADSHOWCASE_shared_link` WRITE;
/*!40000 ALTER TABLE `ADSHOWCASE_shared_link` DISABLE KEYS */;
INSERT INTO `ADSHOWCASE_shared_link` VALUES
(1,'TkdIPhVDdlBUIsUO','FYZcYHTDA5CFmuXfjVvxegku-ddbIaYztM5xbkYURX4',150,1,'2026-01-06 00:28:49',20,1,NULL,'2026-01-04 01:28:49',NULL),
(2,'FzEB-Zj_RyDEDLjC','4Z5VRzc2UhDR2Z6Bq3H90DXlURzJv8Ca9_zYfoHg33o',150,1,'2026-01-05 00:37:23',10,0,NULL,'2026-01-04 01:37:23',NULL);
/*!40000 ALTER TABLE `ADSHOWCASE_shared_link` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ADSHOWCASE_shared_link_access_log`
--

DROP TABLE IF EXISTS `ADSHOWCASE_shared_link_access_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ADSHOWCASE_shared_link_access_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `shared_link_id` bigint(20) NOT NULL,
  `accessed_at` datetime NOT NULL DEFAULT current_timestamp(),
  `ip` varchar(45) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sharedlog_link` (`shared_link_id`),
  CONSTRAINT `fk_sharedlog_link` FOREIGN KEY (`shared_link_id`) REFERENCES `ADSHOWCASE_shared_link` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ADSHOWCASE_shared_link_access_log`
--

LOCK TABLES `ADSHOWCASE_shared_link_access_log` WRITE;
/*!40000 ALTER TABLE `ADSHOWCASE_shared_link_access_log` DISABLE KEYS */;
INSERT INTO `ADSHOWCASE_shared_link_access_log` VALUES
(1,1,'2026-01-04 01:32:10','172.27.0.2','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36');
/*!40000 ALTER TABLE `ADSHOWCASE_shared_link_access_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ADSHOWCASE_user`
--

DROP TABLE IF EXISTS `ADSHOWCASE_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ADSHOWCASE_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` char(16) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(10) NOT NULL,
  `type` varchar(32) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `status` enum('active','archived','banned','inactive','pending') NOT NULL DEFAULT 'active',
  `language_id` int(11) DEFAULT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `verification_token` varchar(255) DEFAULT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `failed_login_attempts` int(11) NOT NULL DEFAULT 0,
  `locked_until` datetime DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `last_login_ip` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`),
  UNIQUE KEY `verification_token` (`verification_token`),
  KEY `idx_user_language_id` (`language_id`),
  CONSTRAINT `fk_user_language` FOREIGN KEY (`language_id`) REFERENCES `ADSHOWCASE_language_locale` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ADSHOWCASE_user`
--

LOCK TABLES `ADSHOWCASE_user` WRITE;
/*!40000 ALTER TABLE `ADSHOWCASE_user` DISABLE KEYS */;
INSERT INTO `ADSHOWCASE_user` VALUES
(1,'7ac5-1EwusLBXq3t','admin@adshowcase.com','admin','admin','Admin','Seed','active',2,NULL,'$2y$13$mx8XpwUYntEHokfDIDjGE.0FRPwyJC2acuVqmDPtmSumou6hyIXC6','mk2Fr-KR-GMmK87lOWf8DoLEH9hpz6GG',NULL,NULL,'2026-01-04 00:36:31',0,NULL,'2026-01-04 11:26:56','172.27.0.2','2026-01-04 00:36:31','2026-01-04 11:26:56'),
(2,'eyNZrU0Cov_ZthHO','editor@adshowcase.com','editor','editor','Editor','Seed','active',2,NULL,'$2y$13$9N9X7C8Ef6WizzzbKwhUF.FpePC9C5M9.fhDa3vQ4TU49.vywIjeS','el_C04toAlDsO8bxO80dGwmt4maosmXT',NULL,NULL,'2026-01-04 00:36:32',0,NULL,NULL,NULL,'2026-01-04 00:36:32','2026-01-04 00:36:32'),
(3,'wUscVDOjUy2jU6qv','sales@adshowcase.com','sales','sales','Sales','Seed','active',2,NULL,'$2y$13$FvbLsmH82wA3UZseV.xxa.5Qba/h0PC8xzENGsOVl18.eikr/apAe','gOcNqFT3PWqoqPRr099jspvn4eW9aN8F',NULL,NULL,'2026-01-04 00:36:32',0,NULL,NULL,NULL,'2026-01-04 00:36:32','2026-01-04 00:36:32'),
(4,'XyakrMqw_TnlqGlW','viewer@adshowcase.com','viewer','viewer','Viewer','Seed','active',2,NULL,'$2y$13$wWFxLXM45mz.Df4sv5n4gerVGW5sVRuHLoB5QkbrkF7oQ.JShFbE6','hyTyyL_jFlre58174WnTjma9m4AQnZDV',NULL,NULL,'2026-01-04 00:36:33',0,NULL,NULL,NULL,'2026-01-04 00:36:33','2026-01-04 00:36:33');
/*!40000 ALTER TABLE `ADSHOWCASE_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-04 12:47:37
