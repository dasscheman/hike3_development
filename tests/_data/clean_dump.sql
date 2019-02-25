-- MySQL dump 10.13  Distrib 5.7.25, for Linux (x86_64)
--
-- Host: localhost    Database: hike-app-test
-- ------------------------------------------------------
-- Server version	5.7.25-0ubuntu0.18.10.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `auth_assignment`
--

DROP TABLE IF EXISTS `auth_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `idx-auth_assignment-user_id` (`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_assignment`
--

LOCK TABLES `auth_assignment` WRITE;
/*!40000 ALTER TABLE `auth_assignment` DISABLE KEYS */;
INSERT INTO `auth_assignment` VALUES ('gebruiker','1',NULL),('gebruiker','2',NULL),('gebruiker','3',NULL),('gebruiker','4',NULL);
/*!40000 ALTER TABLE `auth_assignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_item`
--

DROP TABLE IF EXISTS `auth_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_item`
--

LOCK TABLES `auth_item` WRITE;
/*!40000 ALTER TABLE `auth_item` DISABLE KEYS */;
INSERT INTO `auth_item` VALUES ('deelnemer',1,'','deelnemer',NULL,1520807822,1520807822),('deelnemerEnded',1,'','deelnemerEnded',NULL,1520807822,1520807822),('deelnemerGestart',1,'','deelnemerGestart',NULL,1520839189,1520839189),('deelnemerGestartTime',1,'','deelnemerGestartTime',NULL,1520839203,1520839203),('deelnemerIntroductie',1,'','deelnemerItroductie',NULL,1520839245,1520839245),('gebruiker',1,'',NULL,NULL,1520807887,1520839361),('organisatie',1,'','organisatie',NULL,1520807836,1520807836),('organisatieGestart',1,'','organisatieGestart',NULL,1520839275,1520839275),('organisatieGestartTime',1,'','organisatieGestartTime',NULL,1520839315,1520839315),('organisatieIntroductie',1,'','organisatieIntroductie',NULL,1520839332,1520839332),('organisatieOpstart',1,'','organisatieOpstart',NULL,1520839352,1520839352),('organisatiePostCheck',1,'','organisatiePostCheck',NULL,1520807822,1520807822),('post',1,'','post',NULL,1520807822,1520807822);
/*!40000 ALTER TABLE `auth_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_item_child`
--

DROP TABLE IF EXISTS `auth_item_child`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_item_child`
--

LOCK TABLES `auth_item_child` WRITE;
/*!40000 ALTER TABLE `auth_item_child` DISABLE KEYS */;
INSERT INTO `auth_item_child` VALUES ('gebruiker','deelnemer'),('gebruiker','deelnemerEnded'),('gebruiker','deelnemerGestart'),('gebruiker','deelnemerGestartTime'),('gebruiker','deelnemerIntroductie'),('gebruiker','organisatie'),('gebruiker','organisatieGestart'),('gebruiker','organisatieGestartTime'),('gebruiker','organisatieIntroductie'),('gebruiker','organisatieOpstart'),('gebruiker','organisatiePostCheck'),('gebruiker','post');
/*!40000 ALTER TABLE `auth_item_child` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_rule`
--

DROP TABLE IF EXISTS `auth_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_rule`
--

LOCK TABLES `auth_rule` WRITE;
/*!40000 ALTER TABLE `auth_rule` DISABLE KEYS */;
INSERT INTO `auth_rule` VALUES ('deelnemer',_binary 'O:22:\"app\\rbac\\DeelnemerRule\":3:{s:4:\"name\";s:9:\"deelnemer\";s:9:\"createdAt\";N;s:9:\"updatedAt\";i:1520806839;}',1509809287,1520806839),('deelnemerEnded',_binary 'O:27:\"app\\rbac\\DeelnemerEndedRule\":3:{s:4:\"name\";s:14:\"deelnemerEnded\";s:9:\"createdAt\";i:1522098239;s:9:\"updatedAt\";i:1522098239;}',1522098239,1522098239),('deelnemerGestart',_binary 'O:29:\"app\\rbac\\DeelnemerGestartRule\":3:{s:4:\"name\";s:16:\"deelnemerGestart\";s:9:\"createdAt\";i:1520838975;s:9:\"updatedAt\";i:1520838975;}',1520838975,1520838975),('deelnemerGestartTime',_binary 'O:33:\"app\\rbac\\DeelnemerGestartTimeRule\":3:{s:4:\"name\";s:20:\"deelnemerGestartTime\";s:9:\"createdAt\";i:1520839024;s:9:\"updatedAt\";i:1520839024;}',1520839024,1520839024),('deelnemerItroductie',_binary 'O:33:\"app\\rbac\\DeelnemerIntroductieRule\":3:{s:4:\"name\";s:19:\"deelnemerItroductie\";s:9:\"createdAt\";i:1520839060;s:9:\"updatedAt\";i:1520839060;}',1520839060,1520839060),('organisatie',_binary 'O:24:\"app\\rbac\\OrganisatieRule\":3:{s:4:\"name\";s:11:\"organisatie\";s:9:\"createdAt\";N;s:9:\"updatedAt\";i:1520806865;}',1509911519,1520806865),('organisatieGestart',_binary 'O:31:\"app\\rbac\\OrganisatieGestartRule\":3:{s:4:\"name\";s:18:\"organisatieGestart\";s:9:\"createdAt\";i:1520839114;s:9:\"updatedAt\";i:1520839114;}',1520839114,1520839114),('organisatieGestartTime',_binary 'O:35:\"app\\rbac\\OrganisatieGestartTimeRule\":3:{s:4:\"name\";s:22:\"organisatieGestartTime\";s:9:\"createdAt\";i:1520839136;s:9:\"updatedAt\";i:1520839136;}',1520839136,1520839136),('organisatieIntroductie',_binary 'O:35:\"app\\rbac\\OrganisatieIntroductieRule\":3:{s:4:\"name\";s:22:\"organisatieIntroductie\";s:9:\"createdAt\";i:1520839158;s:9:\"updatedAt\";i:1520839158;}',1520839158,1520839158),('organisatieOpstart',_binary 'O:31:\"app\\rbac\\OrganisatieOpstartRule\":3:{s:4:\"name\";s:18:\"organisatieOpstart\";s:9:\"createdAt\";N;s:9:\"updatedAt\";i:1520806858;}',1520806213,1520806858),('organisatiePostCheck',_binary 'O:33:\"app\\rbac\\OrganisatiePostCheckRule\":3:{s:4:\"name\";s:20:\"organisatiePostCheck\";s:9:\"createdAt\";i:1532860443;s:9:\"updatedAt\";i:1532860443;}',1532860443,1532860443),('post',_binary 'O:17:\"app\\rbac\\PostRule\":3:{s:4:\"name\";s:4:\"post\";s:9:\"createdAt\";i:1532114207;s:9:\"updatedAt\";i:1532114207;}',1522098239,1522098239);
/*!40000 ALTER TABLE `auth_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration`
--

LOCK TABLES `migration` WRITE;
/*!40000 ALTER TABLE `migration` DISABLE KEYS */;
INSERT INTO `migration` VALUES ('m000000_000000_base',1551121640),('m140209_132017_init',1551121641),('m140403_174025_create_account_table',1551121641),('m140504_113157_update_tables',1551121641),('m140504_130429_create_token_table',1551121641),('m140506_102106_rbac_init',1551121640),('m140830_171933_fix_ip_field',1551121641),('m140830_172703_change_account_table_name',1551121641),('m141222_110026_update_ip_field',1551121641),('m141222_135246_alter_username_length',1551121641),('m150614_103145_update_social_account_table',1551121641),('m150623_212711_fix_username_notnull',1551121641),('m151218_234654_add_timezone_to_profile',1551121641),('m160121_073307_users_table',1551121641),('m160121_075552_event_name_table',1551121641),('m160121_175529_groups_table',1551121642),('m160121_181534_deelnemers_event_table',1551121642),('m160121_183043_friend_list_table',1551121642),('m160121_200634_route_table',1551121642),('m160121_202321_vragen_table',1551121643),('m160121_202333_qr_table',1551121644),('m160121_202345_posten_table',1551121644),('m160121_202415_hints_table',1551121645),('m160121_202503_bonuspunten_table',1551121645),('m160130_212853_add_active_event_to_users',1551121645),('m160929_103127_add_last_login_at_to_user_table',1551121641),('m170820_095955_create_time_trail_tables',1551121646),('m170907_052038_rbac_add_index_on_auth_assignment_user_id',1551121640),('m170930_133206_fix_data_issues_before_migrate',1551121646),('m171002_151254_add_columns_user_table',1551121647),('m171130_143306_migrate_user_table',1551121647),('m171130_153306_delete_foreignkeys_user_table',1551121647),('m171130_163306_create_foreignkeys_user_table',1551121649),('m180315_000000_fill_auth_rule_table',1551121649),('m180315_000010_fill_auth_item_table',1551121649),('m180315_000020_fill_auth_item_child_table',1551121649),('m180315_000030_assign_role_to_users',1551121649),('m180316_000030_activate_users',1551121649),('m180318_151254_add_coordinates_to_tables',1551121650),('m180323_151254_add_boolean_to_hint_tables',1551121650),('m180326_000000_fill_auth_rule_table',1551121650),('m180326_000010_fill_auth_item_table',1551121650),('m180326_000020_fill_auth_item_child_table',1551121650),('m180425_123909_newsletter_init',1551121651),('m180426_000030_newsletter_users',1551121651),('m180522_202503_track_table',1551121651),('m180523_151638_rbac_updates_indexes_without_prefix',1551121640),('m180524_212853_add_allow_track_to_user',1551121651),('m180602_072853_add_color_to_deelnemerevent',1551121651),('m180624_000030_assign_role_to_users_1',1551121651),('m180720_000000_fill_auth_rule_table',1551121651),('m180720_000010_fill_auth_item_table',1551121651),('m180720_000020_fill_auth_item_child_table',1551121651),('m180726_000000_fill_auth_rule_table',1551121651),('m180726_000010_fill_auth_item_table',1551121651),('m180726_000020_fill_auth_item_child_table',1551121651),('m181022_202415_route_track_table',1551121651),('m190204_082333_message_to_qr_table',1551121651);
/*!40000 ALTER TABLE `migration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile`
--

DROP TABLE IF EXISTS `profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profile` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `public_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gravatar_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gravatar_id` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8_unicode_ci,
  `timezone` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `fk_user_profile` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profile`
--

LOCK TABLES `profile` WRITE;
/*!40000 ALTER TABLE `profile` DISABLE KEYS */;
/*!40000 ALTER TABLE `profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `social_account`
--

DROP TABLE IF EXISTS `social_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `social_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `client_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `code` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_unique` (`provider`,`client_id`),
  UNIQUE KEY `account_unique_code` (`code`),
  KEY `fk_user_account` (`user_id`),
  CONSTRAINT `fk_user_account` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `social_account`
--

LOCK TABLES `social_account` WRITE;
/*!40000 ALTER TABLE `social_account` DISABLE KEYS */;
/*!40000 ALTER TABLE `social_account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_bonuspunten`
--

DROP TABLE IF EXISTS `tbl_bonuspunten`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_bonuspunten` (
  `bouspunten_ID` int(11) NOT NULL AUTO_INCREMENT,
  `event_ID` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `post_ID` int(11) DEFAULT NULL,
  `group_ID` int(11) NOT NULL,
  `omschrijving` varchar(255) NOT NULL,
  `score` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`bouspunten_ID`),
  KEY `fk_bonuspunten_event_id` (`event_ID`),
  KEY `fk_bonuspunten_post_id` (`post_ID`),
  KEY `fk_bonuspunten_group_id` (`group_ID`),
  KEY `fk_bonuspunten_create_user` (`create_user_ID`),
  KEY `fk_bonuspunten_update_user` (`update_user_ID`),
  CONSTRAINT `fk_bonuspunten_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_bonuspunten_event_id` FOREIGN KEY (`event_ID`) REFERENCES `tbl_event_names` (`event_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_bonuspunten_group_id` FOREIGN KEY (`group_ID`) REFERENCES `tbl_groups` (`group_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_bonuspunten_post_id` FOREIGN KEY (`post_ID`) REFERENCES `tbl_posten` (`post_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_bonuspunten_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_bonuspunten`
--

LOCK TABLES `tbl_bonuspunten` WRITE;
/*!40000 ALTER TABLE `tbl_bonuspunten` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_bonuspunten` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_deelnemers_event`
--

DROP TABLE IF EXISTS `tbl_deelnemers_event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_deelnemers_event` (
  `deelnemers_ID` int(11) NOT NULL AUTO_INCREMENT,
  `event_ID` int(11) NOT NULL,
  `user_ID` int(11) NOT NULL,
  `rol` int(11) DEFAULT NULL,
  `group_ID` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`deelnemers_ID`),
  UNIQUE KEY `event_ID` (`event_ID`,`user_ID`),
  KEY `fk_deelnemers_group_ID` (`group_ID`),
  KEY `fk_deelnemers_user_id` (`user_ID`),
  KEY `fk_deelnemers_create_user` (`create_user_ID`),
  KEY `fk_deelnemers_update_user` (`update_user_ID`),
  CONSTRAINT `fk_deelnemers_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_deelnemers_event_event_ID` FOREIGN KEY (`event_ID`) REFERENCES `tbl_event_names` (`event_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_deelnemers_group_ID` FOREIGN KEY (`group_ID`) REFERENCES `tbl_groups` (`group_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_deelnemers_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_deelnemers_user_id` FOREIGN KEY (`user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_deelnemers_event`
--

LOCK TABLES `tbl_deelnemers_event` WRITE;
/*!40000 ALTER TABLE `tbl_deelnemers_event` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_deelnemers_event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_event_names`
--

DROP TABLE IF EXISTS `tbl_event_names`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_event_names` (
  `event_ID` int(11) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(255) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `active_day` date DEFAULT NULL,
  `max_time` time DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `organisatie` varchar(255) NOT NULL,
  `website` varchar(255) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`event_ID`),
  KEY `fk_events_create_user_name` (`create_user_ID`),
  KEY `fk_events_update_user_name` (`update_user_ID`),
  CONSTRAINT `fk_events_create_user_name` FOREIGN KEY (`create_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_events_update_user_name` FOREIGN KEY (`update_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_event_names`
--

LOCK TABLES `tbl_event_names` WRITE;
/*!40000 ALTER TABLE `tbl_event_names` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_event_names` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_friend_list`
--

DROP TABLE IF EXISTS `tbl_friend_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_friend_list` (
  `friend_list_ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_ID` int(11) DEFAULT NULL,
  `friends_with_user_ID` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`friend_list_ID`),
  UNIQUE KEY `friendship_ID` (`user_ID`,`friends_with_user_ID`),
  KEY `fk_friend_list_friends_with_user` (`friends_with_user_ID`),
  KEY `fk_friend_list_create_user` (`create_user_ID`),
  KEY `fk_friend_list_update_user` (`update_user_ID`),
  CONSTRAINT `fk_friend_list_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_friend_list_friends_with_user` FOREIGN KEY (`friends_with_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_friend_list_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_friend_list_user` FOREIGN KEY (`user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_friend_list`
--

LOCK TABLES `tbl_friend_list` WRITE;
/*!40000 ALTER TABLE `tbl_friend_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_friend_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_groups`
--

DROP TABLE IF EXISTS `tbl_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_groups` (
  `group_ID` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) NOT NULL,
  `event_ID` int(11) NOT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`group_ID`),
  UNIQUE KEY `event_ID` (`event_ID`,`group_name`),
  KEY `fk_groups_create_user` (`create_user_ID`),
  KEY `fk_groups_update_user` (`update_user_ID`),
  CONSTRAINT `fk_groups_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_groups_event_ID` FOREIGN KEY (`event_ID`) REFERENCES `tbl_event_names` (`event_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_groups_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_groups`
--

LOCK TABLES `tbl_groups` WRITE;
/*!40000 ALTER TABLE `tbl_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_newsletter`
--

DROP TABLE IF EXISTS `tbl_newsletter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_newsletter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(45) NOT NULL,
  `body` varchar(1050) NOT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `schedule_date_time` datetime DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_newsletter_create_user` (`create_user_ID`),
  KEY `fk_newsletter_update_user` (`update_user_ID`),
  CONSTRAINT `fk_newsletter_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_newsletter_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_newsletter`
--

LOCK TABLES `tbl_newsletter` WRITE;
/*!40000 ALTER TABLE `tbl_newsletter` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_newsletter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_newsletter_mail_list`
--

DROP TABLE IF EXISTS `tbl_newsletter_mail_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_newsletter_mail_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `newsletter_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `send_time` datetime DEFAULT NULL,
  `is_sent` tinyint(1) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_newsletter_mail_list_id` (`newsletter_id`),
  KEY `fk_newsletter_mail_list_receive_user_id` (`user_id`),
  KEY `fk_newsletter_mail_list_create_user` (`create_user_ID`),
  KEY `fk_newsletter_mail_list_update_user` (`update_user_ID`),
  CONSTRAINT `fk_newsletter_mail_list_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_newsletter_mail_list_id` FOREIGN KEY (`newsletter_id`) REFERENCES `tbl_newsletter` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_newsletter_mail_list_receive_user_id` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_newsletter_mail_list_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_newsletter_mail_list`
--

LOCK TABLES `tbl_newsletter_mail_list` WRITE;
/*!40000 ALTER TABLE `tbl_newsletter_mail_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_newsletter_mail_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_nood_envelop`
--

DROP TABLE IF EXISTS `tbl_nood_envelop`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_nood_envelop` (
  `nood_envelop_ID` int(11) NOT NULL AUTO_INCREMENT,
  `nood_envelop_name` varchar(255) NOT NULL,
  `event_ID` int(11) NOT NULL,
  `route_ID` int(11) NOT NULL,
  `nood_envelop_volgorde` int(11) DEFAULT NULL,
  `coordinaat` varchar(255) DEFAULT NULL,
  `opmerkingen` varchar(1050) NOT NULL,
  `score` int(11) NOT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `show_coordinates` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`nood_envelop_ID`),
  UNIQUE KEY `nood_envelop_name` (`nood_envelop_name`,`event_ID`,`route_ID`),
  KEY `fk_nood_envelop_event_id` (`event_ID`),
  KEY `fk_nood_envelop_route` (`route_ID`),
  KEY `fk_nood_envelop_create_user` (`create_user_ID`),
  KEY `fk_nood_envelop_update_user` (`update_user_ID`),
  CONSTRAINT `fk_nood_envelop_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_nood_envelop_event_id` FOREIGN KEY (`event_ID`) REFERENCES `tbl_event_names` (`event_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_nood_envelop_route` FOREIGN KEY (`route_ID`) REFERENCES `tbl_route` (`route_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_nood_envelop_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_nood_envelop`
--

LOCK TABLES `tbl_nood_envelop` WRITE;
/*!40000 ALTER TABLE `tbl_nood_envelop` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_nood_envelop` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_open_nood_envelop`
--

DROP TABLE IF EXISTS `tbl_open_nood_envelop`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_open_nood_envelop` (
  `open_nood_envelop_ID` int(11) NOT NULL AUTO_INCREMENT,
  `nood_envelop_ID` int(11) NOT NULL,
  `event_ID` int(11) NOT NULL,
  `group_ID` int(11) NOT NULL,
  `opened` tinyint(1) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`open_nood_envelop_ID`),
  UNIQUE KEY `nood_envelop_ID` (`nood_envelop_ID`,`group_ID`),
  KEY `fk_open_nood_envelop_event_id` (`event_ID`),
  KEY `fk_open_nood_envelop_group_id` (`group_ID`),
  KEY `fk_open_envelop_create_user` (`create_user_ID`),
  KEY `fk_open_envelop_update_user` (`update_user_ID`),
  CONSTRAINT `fk_open_envelop_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_open_envelop_id` FOREIGN KEY (`nood_envelop_ID`) REFERENCES `tbl_nood_envelop` (`nood_envelop_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_open_envelop_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_open_nood_envelop_event_id` FOREIGN KEY (`event_ID`) REFERENCES `tbl_event_names` (`event_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_open_nood_envelop_group_id` FOREIGN KEY (`group_ID`) REFERENCES `tbl_groups` (`group_ID`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_open_nood_envelop`
--

LOCK TABLES `tbl_open_nood_envelop` WRITE;
/*!40000 ALTER TABLE `tbl_open_nood_envelop` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_open_nood_envelop` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_open_vragen`
--

DROP TABLE IF EXISTS `tbl_open_vragen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_open_vragen` (
  `open_vragen_ID` int(11) NOT NULL AUTO_INCREMENT,
  `open_vragen_name` varchar(255) NOT NULL,
  `event_ID` int(11) NOT NULL,
  `route_ID` int(11) NOT NULL,
  `vraag_volgorde` int(11) DEFAULT NULL,
  `omschrijving` text NOT NULL,
  `vraag` varchar(255) NOT NULL,
  `goede_antwoord` varchar(255) NOT NULL,
  `score` int(11) NOT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  PRIMARY KEY (`open_vragen_ID`),
  UNIQUE KEY `open_vragen_name` (`open_vragen_name`,`event_ID`,`route_ID`),
  KEY `fk_open_vragen_event_id` (`event_ID`),
  KEY `fk_open_vragen_route` (`route_ID`),
  KEY `fk_open_vragen_create_user` (`create_user_ID`),
  KEY `fk_open_vragen_update_user` (`update_user_ID`),
  CONSTRAINT `fk_open_vragen_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_open_vragen_event_id` FOREIGN KEY (`event_ID`) REFERENCES `tbl_event_names` (`event_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_open_vragen_route` FOREIGN KEY (`route_ID`) REFERENCES `tbl_route` (`route_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_open_vragen_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_open_vragen`
--

LOCK TABLES `tbl_open_vragen` WRITE;
/*!40000 ALTER TABLE `tbl_open_vragen` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_open_vragen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_open_vragen_antwoorden`
--

DROP TABLE IF EXISTS `tbl_open_vragen_antwoorden`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_open_vragen_antwoorden` (
  `open_vragen_antwoorden_ID` int(11) NOT NULL AUTO_INCREMENT,
  `open_vragen_ID` int(11) NOT NULL,
  `event_ID` int(11) NOT NULL,
  `group_ID` int(11) NOT NULL,
  `antwoord_spelers` varchar(255) NOT NULL,
  `checked` tinyint(1) DEFAULT NULL,
  `correct` tinyint(1) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`open_vragen_antwoorden_ID`),
  UNIQUE KEY `open_vragen_ID` (`open_vragen_ID`,`group_ID`),
  KEY `fk_open_vragen_antwoorden_event_id` (`event_ID`),
  KEY `fk_open_vragen_antwoorden_group_id` (`group_ID`),
  KEY `fk_open_vragen_antwoorden_create_user` (`create_user_ID`),
  KEY `fk_open_vragen_antwoorden_update_user` (`update_user_ID`),
  CONSTRAINT `fk_open_vragen_antwoorden_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_open_vragen_antwoorden_event_id` FOREIGN KEY (`event_ID`) REFERENCES `tbl_event_names` (`event_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_open_vragen_antwoorden_group_id` FOREIGN KEY (`group_ID`) REFERENCES `tbl_groups` (`group_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_open_vragen_antwoorden_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_open_vragen_antwoorden_vragen_id` FOREIGN KEY (`open_vragen_ID`) REFERENCES `tbl_open_vragen` (`open_vragen_ID`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_open_vragen_antwoorden`
--

LOCK TABLES `tbl_open_vragen_antwoorden` WRITE;
/*!40000 ALTER TABLE `tbl_open_vragen_antwoorden` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_open_vragen_antwoorden` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_post_passage`
--

DROP TABLE IF EXISTS `tbl_post_passage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_post_passage` (
  `posten_passage_ID` int(11) NOT NULL AUTO_INCREMENT,
  `post_ID` int(11) NOT NULL,
  `event_ID` int(11) NOT NULL,
  `group_ID` int(11) NOT NULL,
  `gepasseerd` tinyint(1) NOT NULL,
  `binnenkomst` datetime DEFAULT NULL,
  `vertrek` datetime DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`posten_passage_ID`),
  UNIQUE KEY `post_ID` (`post_ID`,`event_ID`,`group_ID`),
  KEY `fk_post_passage_event_id` (`event_ID`),
  KEY `fk_post_passage_group_name` (`group_ID`),
  KEY `fk_post_passage_create_user` (`create_user_ID`),
  KEY `fk_post_passage_update_user` (`update_user_ID`),
  CONSTRAINT `fk_post_passage_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_post_passage_event_id` FOREIGN KEY (`event_ID`) REFERENCES `tbl_event_names` (`event_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_post_passage_group_name` FOREIGN KEY (`group_ID`) REFERENCES `tbl_groups` (`group_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_post_passage_post_id` FOREIGN KEY (`post_ID`) REFERENCES `tbl_posten` (`post_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_post_passage_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_post_passage`
--

LOCK TABLES `tbl_post_passage` WRITE;
/*!40000 ALTER TABLE `tbl_post_passage` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_post_passage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_posten`
--

DROP TABLE IF EXISTS `tbl_posten`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_posten` (
  `post_ID` int(11) NOT NULL AUTO_INCREMENT,
  `post_name` varchar(255) NOT NULL,
  `event_ID` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `post_volgorde` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  PRIMARY KEY (`post_ID`),
  UNIQUE KEY `post_name` (`post_name`,`event_ID`,`date`),
  KEY `fk_posten_event_name` (`event_ID`),
  KEY `fk_posten_create_user` (`create_user_ID`),
  KEY `fk_posten_update_user` (`update_user_ID`),
  CONSTRAINT `fk_posten_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_posten_event_name` FOREIGN KEY (`event_ID`) REFERENCES `tbl_event_names` (`event_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_posten_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_posten`
--

LOCK TABLES `tbl_posten` WRITE;
/*!40000 ALTER TABLE `tbl_posten` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_posten` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_qr`
--

DROP TABLE IF EXISTS `tbl_qr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_qr` (
  `qr_ID` int(11) NOT NULL AUTO_INCREMENT,
  `qr_name` varchar(255) NOT NULL,
  `qr_code` varchar(255) NOT NULL,
  `event_ID` int(11) NOT NULL,
  `route_ID` int(11) NOT NULL,
  `qr_volgorde` int(11) DEFAULT NULL,
  `score` int(11) NOT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `message` varchar(1050) DEFAULT NULL,
  PRIMARY KEY (`qr_ID`),
  UNIQUE KEY `qr_code` (`qr_code`,`event_ID`),
  KEY `fk_qr_event_id` (`event_ID`),
  KEY `fk_qr_route` (`route_ID`),
  KEY `fk_qr_create_user` (`create_user_ID`),
  KEY `fk_qr_update_user` (`update_user_ID`),
  CONSTRAINT `fk_qr_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_qr_event_id` FOREIGN KEY (`event_ID`) REFERENCES `tbl_event_names` (`event_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_qr_route` FOREIGN KEY (`route_ID`) REFERENCES `tbl_route` (`route_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_qr_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_qr`
--

LOCK TABLES `tbl_qr` WRITE;
/*!40000 ALTER TABLE `tbl_qr` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_qr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_qr_check`
--

DROP TABLE IF EXISTS `tbl_qr_check`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_qr_check` (
  `qr_check_ID` int(11) NOT NULL AUTO_INCREMENT,
  `qr_ID` int(11) NOT NULL,
  `event_ID` int(11) NOT NULL,
  `group_ID` int(11) NOT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`qr_check_ID`),
  UNIQUE KEY `qr_ID` (`qr_ID`,`group_ID`),
  KEY `fk_qr_check_qr_event_id` (`event_ID`),
  KEY `fk_qr_check_qr_group_id` (`group_ID`),
  KEY `fk_qr_check_create_user` (`create_user_ID`),
  KEY `fk_qr_check_update_user` (`update_user_ID`),
  CONSTRAINT `fk_qr_check_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_qr_check_qr_event_id` FOREIGN KEY (`event_ID`) REFERENCES `tbl_event_names` (`event_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_qr_check_qr_group_id` FOREIGN KEY (`group_ID`) REFERENCES `tbl_groups` (`group_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_qr_check_qr_id` FOREIGN KEY (`qr_ID`) REFERENCES `tbl_qr` (`qr_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_qr_check_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_qr_check`
--

LOCK TABLES `tbl_qr_check` WRITE;
/*!40000 ALTER TABLE `tbl_qr_check` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_qr_check` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_route`
--

DROP TABLE IF EXISTS `tbl_route`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_route` (
  `route_ID` int(11) NOT NULL AUTO_INCREMENT,
  `route_name` varchar(255) NOT NULL,
  `event_ID` int(11) NOT NULL,
  `day_date` date DEFAULT NULL,
  `route_volgorde` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`route_ID`),
  UNIQUE KEY `event_ID` (`event_ID`,`day_date`,`route_name`),
  KEY `fk_route_create_user_name` (`create_user_ID`),
  KEY `fk_route_update_user_name` (`update_user_ID`),
  CONSTRAINT `fk_route_create_user_name` FOREIGN KEY (`create_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_route_event_id` FOREIGN KEY (`event_ID`) REFERENCES `tbl_event_names` (`event_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_route_update_user_name` FOREIGN KEY (`update_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_route`
--

LOCK TABLES `tbl_route` WRITE;
/*!40000 ALTER TABLE `tbl_route` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_route` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_route_track`
--

DROP TABLE IF EXISTS `tbl_route_track`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_route_track` (
  `route_track_ID` int(11) NOT NULL AUTO_INCREMENT,
  `event_ID` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `elevation` decimal(15,2) DEFAULT NULL,
  `latitude` decimal(11,9) DEFAULT NULL,
  `longitude` decimal(11,9) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`route_track_ID`),
  KEY `fk_route_track_event_id` (`event_ID`),
  KEY `fk_route_track_create_user` (`create_user_ID`),
  KEY `fk_route_track_update_user` (`update_user_ID`),
  CONSTRAINT `fk_route_track_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_route_track_event_id` FOREIGN KEY (`event_ID`) REFERENCES `tbl_event_names` (`event_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_route_track_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_route_track`
--

LOCK TABLES `tbl_route_track` WRITE;
/*!40000 ALTER TABLE `tbl_route_track` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_route_track` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_time_trail`
--

DROP TABLE IF EXISTS `tbl_time_trail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_time_trail` (
  `time_trail_ID` int(11) NOT NULL AUTO_INCREMENT,
  `time_trail_name` varchar(255) NOT NULL,
  `event_ID` int(11) NOT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`time_trail_ID`),
  UNIQUE KEY `time_trail_name` (`time_trail_name`,`event_ID`),
  KEY `fk_time_trail_event_id` (`event_ID`),
  KEY `fk_time_trail_create_user` (`create_user_ID`),
  KEY `fk_time_trail_update_user` (`update_user_ID`),
  CONSTRAINT `fk_time_trail_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_time_trail_event_id` FOREIGN KEY (`event_ID`) REFERENCES `tbl_event_names` (`event_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_time_trail_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_time_trail`
--

LOCK TABLES `tbl_time_trail` WRITE;
/*!40000 ALTER TABLE `tbl_time_trail` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_time_trail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_time_trail_check`
--

DROP TABLE IF EXISTS `tbl_time_trail_check`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_time_trail_check` (
  `time_trail_check_ID` int(11) NOT NULL AUTO_INCREMENT,
  `time_trail_item_ID` int(11) NOT NULL,
  `event_ID` int(11) NOT NULL,
  `group_ID` int(11) NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `succeded` tinyint(1) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`time_trail_check_ID`),
  UNIQUE KEY `time_trail_item_ID` (`time_trail_item_ID`,`group_ID`),
  KEY `fk_time_trail_check_time_trail_check_event_id` (`event_ID`),
  KEY `fk_time_trail_check_time_trail_check_group_id` (`group_ID`),
  KEY `fk_time_trail_check_create_user` (`create_user_ID`),
  KEY `fk_time_trail_check_update_user` (`update_user_ID`),
  CONSTRAINT `fk_time_trail_check_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_time_trail_check_time_trail_check_event_id` FOREIGN KEY (`event_ID`) REFERENCES `tbl_event_names` (`event_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_time_trail_check_time_trail_check_group_id` FOREIGN KEY (`group_ID`) REFERENCES `tbl_groups` (`group_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_time_trail_check_time_trail_item_id` FOREIGN KEY (`time_trail_item_ID`) REFERENCES `tbl_time_trail_item` (`time_trail_item_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_time_trail_check_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_time_trail_check`
--

LOCK TABLES `tbl_time_trail_check` WRITE;
/*!40000 ALTER TABLE `tbl_time_trail_check` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_time_trail_check` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_time_trail_item`
--

DROP TABLE IF EXISTS `tbl_time_trail_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_time_trail_item` (
  `time_trail_item_ID` int(11) NOT NULL AUTO_INCREMENT,
  `time_trail_ID` int(11) DEFAULT NULL,
  `time_trail_item_name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `instruction` varchar(255) NOT NULL,
  `event_ID` int(11) NOT NULL,
  `volgorde` int(11) DEFAULT NULL,
  `score` int(11) NOT NULL,
  `max_time` time DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  PRIMARY KEY (`time_trail_item_ID`),
  UNIQUE KEY `code` (`code`,`event_ID`),
  KEY `fk_time_trail_item_time_trail_id` (`time_trail_ID`),
  KEY `fk_time_trail_item_event_id` (`event_ID`),
  KEY `fk_time_trail_item_create_user` (`create_user_ID`),
  KEY `fk_time_trail_item_update_user` (`update_user_ID`),
  CONSTRAINT `fk_time_trail_item_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_time_trail_item_event_id` FOREIGN KEY (`event_ID`) REFERENCES `tbl_event_names` (`event_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_time_trail_item_time_trail_id` FOREIGN KEY (`time_trail_ID`) REFERENCES `tbl_time_trail` (`time_trail_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_time_trail_item_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_time_trail_item`
--

LOCK TABLES `tbl_time_trail_item` WRITE;
/*!40000 ALTER TABLE `tbl_time_trail_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_time_trail_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_track`
--

DROP TABLE IF EXISTS `tbl_track`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_track` (
  `track_ID` int(11) NOT NULL AUTO_INCREMENT,
  `event_ID` int(11) NOT NULL,
  `user_ID` int(11) NOT NULL,
  `group_ID` int(11) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `accuracy` int(11) DEFAULT NULL,
  `timestamp` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`track_ID`),
  KEY `fk_track_event_id` (`event_ID`),
  KEY `fk_track_group_id` (`group_ID`),
  KEY `fk_track_user` (`user_ID`),
  KEY `fk_track_create_user` (`create_user_ID`),
  KEY `fk_track_update_user` (`update_user_ID`),
  CONSTRAINT `fk_track_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_track_event_id` FOREIGN KEY (`event_ID`) REFERENCES `tbl_event_names` (`event_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_track_group_id` FOREIGN KEY (`group_ID`) REFERENCES `tbl_groups` (`group_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_track_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_track_user` FOREIGN KEY (`user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_track`
--

LOCK TABLES `tbl_track` WRITE;
/*!40000 ALTER TABLE `tbl_track` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_track` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_users`
--

DROP TABLE IF EXISTS `tbl_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_users` (
  `user_ID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `voornaam` varchar(255) NOT NULL,
  `achternaam` varchar(255) NOT NULL,
  `organisatie` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `macadres` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `last_login_time` datetime DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL,
  `selected_event_ID` int(11) DEFAULT NULL,
  `authKey` varchar(255) DEFAULT NULL,
  `accessToken` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_ID`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_users`
--

LOCK TABLES `tbl_users` WRITE;
/*!40000 ALTER TABLE `tbl_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `token`
--

DROP TABLE IF EXISTS `token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `token` (
  `user_id` int(11) NOT NULL,
  `code` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) NOT NULL,
  `type` smallint(6) NOT NULL,
  UNIQUE KEY `token_unique` (`user_id`,`code`,`type`),
  CONSTRAINT `fk_user_token` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `token`
--

LOCK TABLES `token` WRITE;
/*!40000 ALTER TABLE `token` DISABLE KEYS */;
/*!40000 ALTER TABLE `token` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `confirmed_at` int(11) DEFAULT NULL,
  `unconfirmed_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `blocked_at` int(11) DEFAULT NULL,
  `registration_ip` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `flags` int(11) NOT NULL DEFAULT '0',
  `last_login_at` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL,
  `selected_event_ID` int(11) DEFAULT NULL,
  `voornaam` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tussenvoegsel` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `achternaam` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `organisatie` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `newsletter` tinyint(1) DEFAULT NULL,
  `allow_track` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_unique_username` (`username`),
  UNIQUE KEY `user_unique_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-02-25 20:07:32
