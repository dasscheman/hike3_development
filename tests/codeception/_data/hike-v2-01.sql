-- phpMyAdmin SQL Dump
-- version 4.4.9
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Jul 30, 2015 at 09:10 AM
-- Server version: 5.5.44-cll-lve
-- PHP Version: 5.5.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hike-v2-01`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bonuspunten`
--

CREATE TABLE IF NOT EXISTS `tbl_bonuspunten` (
  `bouspunten_ID` int(11) NOT NULL,
  `event_ID` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `post_ID` int(11) DEFAULT NULL,
  `group_ID` int(11) NOT NULL,
  `omschrijving` varchar(255) NOT NULL,
  `score` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_bonuspunten`
--

INSERT INTO `tbl_bonuspunten` (`bouspunten_ID`, `event_ID`, `date`, `post_ID`, `group_ID`, `omschrijving`, `score`, `create_time`, `create_user_ID`, `update_time`, `update_user_ID`) VALUES
(1, 3, NULL, NULL, 5, 'bonus gestart organisatie', 3, '2015-07-07 07:25:46', 1, '2015-07-07 07:25:46', 1),
(2, 3, NULL, NULL, 5, 'bonus gestart players groep A', 3, '2015-07-07 07:25:46', 1, '2015-07-07 07:25:46', 1),
(3, 3, NULL, NULL, 6, 'bonus gestart players groep B', 3, '2015-07-07 07:25:46', 1, '2015-07-07 07:25:46', 1),
(4, 2, NULL, NULL, 3, 'bonus intro organisatie', 3, '2015-07-07 07:25:46', 1, '2015-07-07 07:25:46', 1),
(5, 2, NULL, NULL, 3, 'bonus intro players groep A', 3, '2015-07-07 07:25:46', 1, '2015-07-07 07:25:46', 1),
(6, 2, NULL, NULL, 4, 'bonus intro players groep B', 3, '2015-07-07 07:25:46', 1, '2015-07-07 07:25:46', 1),
(7, 4, NULL, NULL, 7, 'bonus beindigd players groep A', 3, '2015-07-07 07:25:46', 1, '2015-07-07 07:25:46', 1),
(8, 4, NULL, NULL, 8, 'bonus beindigd players groep B', 3, '2015-07-07 07:25:46', 1, '2015-07-07 07:25:46', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_deelnemers_event`
--

CREATE TABLE IF NOT EXISTS `tbl_deelnemers_event` (
  `deelnemers_ID` int(11) NOT NULL,
  `event_ID` int(11) NOT NULL,
  `user_ID` int(11) NOT NULL,
  `rol` int(11) DEFAULT NULL,
  `group_ID` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_deelnemers_event`
--

INSERT INTO `tbl_deelnemers_event` (`deelnemers_ID`, `event_ID`, `user_ID`, `rol`, `group_ID`, `create_time`, `create_user_ID`, `update_time`, `update_user_ID`) VALUES
(1, 1, 1, 1, NULL, '2014-08-25 12:49:00', 1, '2014-08-25 12:49:00', 1),
(2, 1, 2, 3, 1, '2014-08-25 12:49:00', 1, '2014-08-25 12:49:00', 1),
(3, 1, 3, 3, 2, '2014-08-25 12:49:00', 1, '2014-08-25 12:49:00', 1),
(4, 2, 1, 1, NULL, '2014-08-25 12:49:00', 1, '2014-08-25 12:49:00', 1),
(5, 2, 2, 3, 3, '2014-08-25 12:49:00', 1, '2014-08-25 12:49:00', 1),
(6, 2, 3, 3, 4, '2014-08-25 12:49:00', 1, '2014-08-25 12:49:00', 1),
(7, 3, 1, 1, NULL, '2014-08-25 12:49:00', 1, '2014-08-25 12:49:00', 1),
(8, 3, 2, 3, 5, '2014-08-25 12:49:00', 1, '2014-08-25 12:49:00', 1),
(9, 3, 3, 3, 6, '2014-08-25 12:49:00', 1, '2014-08-25 12:49:00', 1),
(10, 4, 1, 1, NULL, '2014-08-25 12:49:00', 1, '2014-08-25 12:49:00', 1),
(11, 4, 2, 3, 7, '2014-08-25 12:49:00', 1, '2014-08-25 12:49:00', 1),
(12, 4, 3, 3, 8, '2014-08-25 12:49:00', 1, '2014-08-25 12:49:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_event_names`
--

CREATE TABLE IF NOT EXISTS `tbl_event_names` (
  `event_ID` int(11) NOT NULL,
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
  `update_user_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_event_names`
--

INSERT INTO `tbl_event_names` (`event_ID`, `event_name`, `start_date`, `end_date`, `status`, `active_day`, `max_time`, `organisatie`, `create_time`, `create_user_ID`, `update_time`, `update_user_ID`) VALUES
(1, 'opstart', '2015-02-25', '2015-03-01', 1, NULL, NULL, 'de Bison', '2014-08-25 12:47:51', 1, '2015-07-28 16:03:11', 2),
(2, 'introductie', '2015-02-25', '2015-03-01', 2, '0000-00-00', NULL, 'de Bison', '2015-02-23 19:45:17', 2, '2015-02-23 19:45:17', 2),
(3, 'gestart', '2015-02-25', '2015-03-01', 3, '2015-02-27', '24:00:00', 'de Bison', '2015-02-23 19:46:08', 2, '2015-07-10 14:36:10', 2),
(4, 'beindigd', '2015-02-25', '2015-03-01', 4, NULL, NULL, 'de Bison', '2015-02-23 19:46:08', 2, '2015-07-10 14:36:10', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_friend_list`
--

CREATE TABLE IF NOT EXISTS `tbl_friend_list` (
  `friend_list_ID` int(11) NOT NULL,
  `user_ID` int(11) DEFAULT NULL,
  `friends_with_user_ID` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_friend_list`
--

INSERT INTO `tbl_friend_list` (`friend_list_ID`, `user_ID`, `friends_with_user_ID`, `status`, `create_time`, `create_user_ID`, `update_time`, `update_user_ID`) VALUES
(1, 1, 2, 2, '2015-01-26 13:14:37', 2, '2015-01-26 13:14:37', 2),
(2, 1, 3, 2, '2015-01-26 13:14:37', 2, '2015-01-26 13:14:37', 2),
(3, 2, 1, 2, '2015-01-26 13:14:37', 2, '2015-01-26 13:14:37', 2),
(4, 2, 3, 2, '2015-01-26 13:14:37', 2, '2015-01-26 13:14:37', 2),
(5, 3, 1, 2, '2015-01-26 13:14:37', 2, '2015-01-26 13:14:37', 2),
(6, 3, 2, 2, '2015-01-26 13:14:37', 2, '2015-01-26 13:14:37', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_groups`
--

CREATE TABLE IF NOT EXISTS `tbl_groups` (
  `group_ID` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `event_ID` int(11) NOT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_groups`
--

INSERT INTO `tbl_groups` (`group_ID`, `group_name`, `event_ID`, `create_time`, `create_user_ID`, `update_time`, `update_user_ID`) VALUES
(1, 'groep A opstart', 1, '2014-08-25 21:44:36', 2, '2014-08-31 11:48:15', 2),
(2, 'groep B opstart', 1, '2014-08-25 21:44:52', 2, '2014-08-31 11:47:46', 2),
(3, 'groep A introductie', 2, '2014-08-25 21:45:04', 2, '2014-08-31 21:42:09', 2),
(4, 'groep B introductie', 2, '2014-08-25 21:45:17', 2, '2014-08-31 11:48:00', 2),
(5, 'groep A gestart', 3, '2014-08-25 21:45:44', 2, '2014-08-31 11:47:11', 2),
(6, 'groep B gestart', 3, '2014-08-31 11:48:30', 2, '2014-09-04 20:52:33', 2),
(7, 'groep A beindigd', 4, '2014-08-25 21:45:44', 2, '2014-08-31 11:47:11', 2),
(8, 'groep B beindigd', 4, '2014-08-31 11:48:30', 2, '2014-09-04 20:52:33', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_migration`
--

CREATE TABLE IF NOT EXISTS `tbl_migration` (
  `version` varchar(255) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_migration`
--

INSERT INTO `tbl_migration` (`version`, `apply_time`) VALUES
('m131112_211037_hike_tables', 1420373075);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_nood_envelop`
--

CREATE TABLE IF NOT EXISTS `tbl_nood_envelop` (
  `nood_envelop_ID` int(11) NOT NULL,
  `nood_envelop_name` varchar(255) NOT NULL,
  `event_ID` int(11) NOT NULL,
  `route_ID` int(11) NOT NULL,
  `nood_envelop_volgorde` int(11) DEFAULT NULL,
  `coordinaat` varchar(255) NOT NULL,
  `opmerkingen` varchar(255) NOT NULL,
  `score` int(11) NOT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_nood_envelop`
--

INSERT INTO `tbl_nood_envelop` (`nood_envelop_ID`, `nood_envelop_name`, `event_ID`, `route_ID`, `nood_envelop_volgorde`, `coordinaat`, `opmerkingen`, `score`, `create_time`, `create_user_ID`, `update_time`, `update_user_ID`) VALUES
(1, 'Hint gestart organisatie', 3, 6, 1, '190971, 417461', 'Je had uit de richting van  300 graden moeten komen', 7, '2014-08-30 11:30:06', 1, '2014-08-30 11:30:06', 1),
(2, 'Hint gestart players', 3, 6, 1, '190971, 417461', 'Je had uit de richting van  300 graden moeten komen', 5, '2014-08-30 11:30:06', 1, '2014-08-30 11:30:06', 1),
(3, 'Hint gestart players groep B', 3, 6, 1, '190971, 417461', 'Je had uit de richting van  300 graden moeten komen', 7, '2014-08-30 11:30:06', 1, '2014-08-30 11:30:06', 1),
(4, 'Hint opstart', 1, 6, 1, '190971, 417461', 'Je had uit de richting van  300 graden moeten komen', 7, '2014-08-30 11:30:06', 1, '2014-08-30 11:30:06', 1),
(5, 'Hint intro', 2, 4, 1, '190971, 417461', 'Je had uit de richting van  300 graden moeten komen', 7, '2014-08-30 11:30:06', 1, '2014-08-30 11:30:06', 1),
(6, 'Hint beindigd', 4, 8, 1, '190971, 417461', 'Je had uit de richting van  300 graden moeten komen', 7, '2014-08-30 11:30:06', 1, '2014-08-30 11:30:06', 1),
(7, 'Hint beindigd2', 4, 8, 2, '190971, 417461', 'Je had uit de richting van  300 graden moeten komen', 7, '2014-08-30 11:30:06', 1, '2014-08-30 11:30:06', 1);

-- (5, ' ''Coördinaat eerste punt van "Good old..."''', 6, 25, 2030, '190319, 418016', 'Je had moeten komen vanuit de richting 325 graden. ', 5, '2014-08-30 11:31:31', 2, '2014-08-30 11:31:31', 2),
-- (6, ' ''Coördinaat eerste kruispunt van "Kruispuntjepuzzelen"''', 6, 27, 2050, '191934, 416899', 'Je had uit de richting van  300 graden moeten komen', 5, '2014-08-30 11:36:53', 2, '2014-08-30 11:36:53', 2),
-- (7, ' ''Coordinaat eerste punt van "Evert, bedankt!"''', 6, 28, 2065, '192056, 416942', 'Je had uit de richting van  200 graden moeten komen', 5, '2014-08-30 12:31:38', 2, '2014-08-30 12:31:38', 2),
-- (8, 'Coördinaat eerste punt van de eerste Origami', 6, 29, 2070, '193174, 417140', 'Start met de eerste richting op dit punt. ', 5, '2014-08-30 12:34:46', 2, '2014-08-30 12:34:46', 2),
-- (9, 'Coördinaat eerste punt van de tweede Origami', 6, 29, 2075, '193466, 416919', 'Schiet het eerste richting vanaf dit punt. ', 5, '2014-08-30 12:36:43', 2, '2014-08-30 12:36:43', 2),
-- (10, ' ''Coördinaat punt van "Tja"''', 6, 30, 2080, '194208, 416799', 'Spreekt voor zich', 5, '2014-08-30 12:39:00', 2, '2014-08-30 12:39:00', 2),
-- (11, ' ''Coördinaat van startpunt van "Kruispeiling"''', 6, 31, 2090, '194348, 416803', 'geen', 5, '2014-08-30 12:51:26', 2, '2014-08-30 12:51:26', 2),
-- (12, ' ''Coördinaat van startpunt "Even die grijze massa aanzwengelen" ''', 6, 32, 2100, '194533, 417343', 'geen', 5, '2014-08-30 12:53:36', 2, '2014-08-30 12:53:36', 2),

-- --------------------------------------------------------

--
-- Table structure for table `tbl_open_nood_envelop`
--

CREATE TABLE IF NOT EXISTS `tbl_open_nood_envelop` (
  `open_nood_envelop_ID` int(11) NOT NULL,
  `nood_envelop_ID` int(11) NOT NULL,
  `event_ID` int(11) NOT NULL,
  `group_ID` int(11) NOT NULL,
  `opened` tinyint(1) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_open_nood_envelop`
--

INSERT INTO `tbl_open_nood_envelop` (`open_nood_envelop_ID`, `nood_envelop_ID`, `event_ID`, `group_ID`, `opened`, `create_time`, `create_user_ID`, `update_time`, `update_user_ID`) VALUES
(1, 1, 3, 5, 1, '2014-09-05 21:03:04', 2, '2014-09-05 21:03:04', 2),
(2, 6, 4, 7, 1, '2014-09-05 21:03:04', 2, '2014-09-05 21:03:04', 2);
-- (2, 14, 6, 6, 1, '2014-09-05 21:43:15', 26, '2014-09-05 21:43:15', 26),
-- (3, 17, 6, 8, 1, '2014-09-05 22:55:27', 29, '2014-09-05 22:55:27', 29),
-- (4, 29, 6, 6, 1, '2014-09-06 01:27:51', 27, '2014-09-06 01:27:51', 27);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_open_vragen`
--

CREATE TABLE IF NOT EXISTS `tbl_open_vragen` (
  `open_vragen_ID` int(11) NOT NULL,
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
  `update_user_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_open_vragen`
--

INSERT INTO `tbl_open_vragen` (`open_vragen_ID`, `open_vragen_name`, `event_ID`, `route_ID`, `vraag_volgorde`, `omschrijving`, `vraag`, `goede_antwoord`, `score`, `create_time`, `create_user_ID`, `update_time`, `update_user_ID`) VALUES
(1, 'Hoofdletter a', 3, 6, 1, 'Vraag voor ActionsGestartOrganisatieTest', 'Hoofdletter a? ', 'A', 5, '2014-08-25 22:09:39', 1, '2014-09-05 10:55:13', 1),
(2, 'Hoofdletter b', 3, 6, 2, 'Vraag voor ActionsGestartOrganisatieTest', 'Hoofdletter b? ', 'B ', 5, '2014-08-25 22:11:30', 1, '2014-08-28 21:05:07', 1),
(3, 'Hoofdletter c', 3, 6, 3, 'Vraag voor ActionsGestartOrganisatieTest', 'Hoofdletter c? ', 'C', 5, '2014-08-25 22:09:39', 1, '2014-09-05 10:55:13', 1),
(4, 'Hoofdletter d', 3, 6, 4, 'Vraag voor ActionsGestartOrganisatieTest', 'Hoofdletter v? ', 'B ', 5, '2014-08-25 22:11:30', 1, '2014-08-28 21:05:07', 1),
(5, 'Hoofdletter e', 3, 5, 1, 'Vraag voor ActionsGestartOrganisatie introductie', 'Hoofdletter E? ', 'E ', 5, '2014-08-25 22:11:30', 1, '2014-08-28 21:05:07', 1),
(6, 'Hoofdletter f', 1, 1, 1, 'Vraag voor status opstart players introvraag', 'Hoofdletter a? ', 'A', 5, '2014-08-25 22:09:39', 1, '2014-09-05 10:55:13', 1),
(7, 'Hoofdletter g', 1, 2, 1, 'Vraag voor status opstart players', 'Hoofdletter b? ', 'B ', 5, '2014-08-25 22:11:30', 1, '2014-08-28 21:05:07', 1),
(8, 'Hoofdletter h', 2, 3, 1, 'Vraag voor status introductie introvraag', 'Hoofdletter h? ', 'H', 5, '2014-08-25 22:09:39', 1, '2014-09-05 10:55:13', 1),
(9, 'Hoofdletter i', 2, 3, 2, 'Vraag voor status introductie players', 'Hoofdletter i? ', 'I ', 5, '2014-08-25 22:11:30', 1, '2014-08-28 21:05:07', 1),
(10, 'Hoofdletter j', 4, 7, 1, 'Vraag voor status beindigd introvraag', 'Hoofdletter j? ', 'J', 5, '2014-08-25 22:09:39', 1, '2014-09-05 10:55:13', 1),
(11, 'Hoofdletter k', 4, 8, 1, 'Vraag voor status beindigd players', 'Hoofdletter k? ', 'K', 5, '2014-08-25 22:11:30', 1, '2014-08-28 21:05:07', 1),
(12, 'Hoofdletter l', 2, 3, 3, 'Vraag voor status introductie beantwoorden players', 'Hoofdletter l? ', 'L', 5, '2014-08-25 22:11:30', 1, '2014-08-28 21:05:07', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_open_vragen_antwoorden`
--

CREATE TABLE IF NOT EXISTS `tbl_open_vragen_antwoorden` (
  `open_vragen_antwoorden_ID` int(11) NOT NULL,
  `open_vragen_ID` int(11) NOT NULL,
  `event_ID` int(11) NOT NULL,
  `group_ID` int(11) NOT NULL,
  `antwoord_spelers` varchar(255) NOT NULL,
  `checked` tinyint(1) DEFAULT NULL,
  `correct` tinyint(1) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_open_vragen_antwoorden`
--

INSERT INTO `tbl_open_vragen_antwoorden` (`open_vragen_antwoorden_ID`, `open_vragen_ID`, `event_ID`, `group_ID`, `antwoord_spelers`, `checked`, `correct`, `create_time`, `create_user_ID`, `update_time`, `update_user_ID`) VALUES
(1, 1, 3, 5, 'A', 0, 0, '2014-08-31 13:46:28', 2, '2014-08-31 13:48:55', 1),
(2, 2, 3, 5, 'B', 0, 0, '2014-08-31 13:49:10', 2, '2014-08-31 13:50:22', 1),
(3, 2, 3, 6, 'B', 0, 0, '2014-08-31 13:49:10', 2, '2014-08-31 13:50:22', 1),
(4, 8, 2, 3, 'H', 0, 0, '2014-08-31 13:46:28', 2, '2014-08-31 13:48:55', 1),
(5, 9, 2, 3, 'I', 0, 0, '2014-08-31 13:49:10', 2, '2014-08-31 13:50:22', 1),
(6, 8, 2, 4, 'H', 0, 0, '2014-08-31 13:46:28', 2, '2014-08-31 13:48:55', 1),
(7, 10, 4, 3, 'H', 0, 0, '2014-08-31 13:46:28', 2, '2014-08-31 13:48:55', 1),
(8, 11, 4, 3, 'I', 0, 0, '2014-08-31 13:49:10', 2, '2014-08-31 13:50:22', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_posten`
--

CREATE TABLE IF NOT EXISTS `tbl_posten` (
  `post_ID` int(11) NOT NULL,
  `post_name` varchar(255) NOT NULL,
  `event_ID` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `post_volgorde` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_posten`
--

INSERT INTO `tbl_posten` (`post_ID`, `post_name`, `event_ID`, `date`, `post_volgorde`, `score`, `create_time`, `create_user_ID`, `update_time`, `update_user_ID`) VALUES
(1, 'post 1 gestart organisatie test', 3, '2015-02-28', 1, 13, '2014-08-30 10:53:28', 2, '2015-07-15 12:53:08', 2),
(2, 'post 2 gestart organisatie test', 3, '2015-02-28', 2, 13, '2014-08-30 10:54:06', 2, '2015-07-15 12:53:08', 2),
(3, 'post 3 gestart organisatie START', 3, '2015-02-27', 3, 0, '2014-08-30 10:54:06', 2, '2015-07-15 12:53:08', 2),
(4, 'post 3 gestart organisatie LUNCH', 3, '2015-02-27', 4, 13, '2014-08-30 10:54:06', 2, '2015-07-15 12:53:08', 2),
(5, 'post 3 gestart organisatie EIND', 3, '2015-02-27', 5, 13, '2014-08-30 10:54:06', 2, '2015-07-15 12:53:08', 2),
(6, 'post 1 opstart', 3, '2015-02-28', 1, 13, '2014-08-30 10:53:28', 2, '2015-07-15 12:53:08', 2),
(7, 'post 2 opstart', 3, '2015-02-28', 2, 13, '2014-08-30 10:54:06', 2, '2015-07-15 12:53:08', 2),
(8, 'post 1 introductie', 2, '2015-02-28', 1, 13, '2014-08-30 10:53:28', 2, '2015-07-15 12:53:08', 2),
(9, 'post 2 introductie', 2, '2015-02-28', 2, 13, '2014-08-30 10:54:06', 2, '2015-07-15 12:53:08', 2),
(10, 'post 1 beindigd', 4, '2015-02-28', 1, 13, '2014-08-30 10:53:28', 2, '2015-07-15 12:53:08', 2),
(11, 'post 2 beindigd', 4, '2015-02-28', 2, 13, '2014-08-30 10:54:06', 2, '2015-07-15 12:53:08', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_post_passage`
--

CREATE TABLE IF NOT EXISTS `tbl_post_passage` (
  `posten_passage_ID` int(11) NOT NULL,
  `post_ID` int(11) NOT NULL,
  `event_ID` int(11) NOT NULL,
  `group_ID` int(11) NOT NULL,
  `gepasseerd` tinyint(11) NOT NULL,
  `binnenkomst` datetime DEFAULT NULL,
  `vertrek` datetime DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_post_passage`
--

INSERT INTO `tbl_post_passage` (`posten_passage_ID`, `post_ID`, `event_ID`, `group_ID`, `gepasseerd`, `binnenkomst`, `vertrek`, `create_time`, `create_user_ID`, `update_time`, `update_user_ID`) VALUES
(1, 3, 3, 5, 1, null, '2015-02-27 09:57:00', '2014-09-05 20:47:54', 1, '2014-09-05 20:50:46', 1),
(2, 4, 3, 5, 1, '2015-02-27 11:55:00', '2015-02-27 11:57:00', '2014-09-05 20:55:34', 1, '2014-09-05 20:57:48', 1),
(3, 5, 3, 5, 1, '2015-02-27 20:55:00', null, '2014-09-05 20:55:34', 1, '2014-09-05 20:57:48', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_qr`
--

CREATE TABLE IF NOT EXISTS `tbl_qr` (
  `qr_ID` int(11) NOT NULL,
  `qr_name` varchar(255) NOT NULL,
  `qr_code` varchar(255) NOT NULL,
  `event_ID` int(11) NOT NULL,
  `route_ID` int(11) NOT NULL,
  `qr_volgorde` int(11) DEFAULT NULL,
  `score` int(11) NOT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_qr`
--

INSERT INTO `tbl_qr` (`qr_ID`, `qr_name`, `qr_code`, `event_ID`, `route_ID`, `qr_volgorde`, `score`, `create_time`, `create_user_ID`, `update_time`, `update_user_ID`) VALUES
(1, 'gestart organisatie', '1wDlYLbS8Ws9EutrUMjNv6', 3, 6, 1, 7, '2014-08-30 14:44:11', 1, '2014-08-30 14:44:11', 2),
(2, 'gestart organisatie Introductie', '2wDlYLbS8Ws9EutrUMjNv6', 3, 5, 1, 7, '2014-08-30 14:44:11', 1, '2014-08-30 14:44:11', 2),
(3, 'opstart', '1wDlYLbS8Ws9EutrUMjNv6', 1, 1, 1, 7, '2014-08-30 14:44:11', 1, '2014-08-30 14:44:11', 2),
(4, 'opstartIntroductie', '2wDlYLbS8Ws9EutrUMjNv6', 1, 2, 1, 7, '2014-08-30 14:44:11', 1, '2014-08-30 14:44:11', 2),
(5, 'intro', '1wDlYLbS8Ws9EutrUMjNv6', 2, 4, 1, 7, '2014-08-30 14:44:11', 1, '2014-08-30 14:44:11', 2),
(6, 'introIntroductie', '2wDlYLbS8Ws9EutrUMjNv6', 2, 3, 1, 7, '2014-08-30 14:44:11', 1, '2014-08-30 14:44:11', 2),
(7, 'beindigd', '1wDlYLbS8Ws9EutrUMjNv6', 4, 8, 1, 7, '2014-08-30 14:44:11', 1, '2014-08-30 14:44:11', 2),
(8, 'beindigdIntroductie', '2wDlYLbS8Ws9EutrUMjNv6', 4, 7, 1, 7, '2014-08-30 14:44:11', 1, '2014-08-30 14:44:11', 2),
(9, 'gestart players', '33DlYLbS8Ws9EutrUMjNv6', 3, 6, 2, 7, '2014-08-30 14:44:11', 1, '2014-08-30 14:44:11', 2),
(10, 'introductie players', '55DlYLbS8Ws9EutrUMjNv6', 2, 3, 3, 7, '2014-08-30 14:44:11', 1, '2014-08-30 14:44:11', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_qr_check`
--

CREATE TABLE IF NOT EXISTS `tbl_qr_check` (
  `qr_check_ID` int(11) NOT NULL,
  `qr_ID` int(11) NOT NULL,
  `event_ID` int(11) NOT NULL,
  `group_ID` int(11) NOT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_qr_check`
--

INSERT INTO `tbl_qr_check` (`qr_check_ID`, `qr_ID`, `event_ID`, `group_ID`, `create_time`, `create_user_ID`, `update_time`, `update_user_ID`) VALUES
(1, 1, 3, 5, '2014-08-31 14:03:05', 2, '2014-08-31 14:03:05', 2),
(2, 6, 2, 3, '2014-08-31 14:03:05', 2, '2014-08-31 14:03:05', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_route`
--

CREATE TABLE IF NOT EXISTS `tbl_route` (
  `route_ID` int(11) NOT NULL,
  `route_name` varchar(255) NOT NULL,
  `event_ID` int(11) NOT NULL,
  `day_date` date NOT NULL,
  `route_volgorde` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_ID` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_route`
--

INSERT INTO `tbl_route` (`route_ID`, `route_name`, `event_ID`, `day_date`, `route_volgorde`, `create_time`, `create_user_ID`, `update_time`, `update_user_ID`) VALUES
(1, 'Introductie', 1, '0000-00-00', 1, NULL, 2, NULL, NULL),
(2, '9 - Homerun', 1, '2015-02-27', 2, NULL, 2, NULL, NULL),
(3, 'Introductie', 2, '0000-00-00', 1, NULL, 2, NULL, NULL),
(4, '9 - Homerun', 2, '2015-02-27', 2, NULL, 2, NULL, NULL),
(5, 'Introductie', 3, '0000-00-00', 1, NULL, 2, NULL, NULL),
(6, '9 - Homerun', 3, '2015-02-27', 2, NULL, 2, NULL, NULL),
(7, 'Introductie', 4, '0000-00-00', 1, NULL, 2, NULL, NULL),
(8, '9 - Homerun', 4, '2015-02-27', 2, NULL, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE IF NOT EXISTS `tbl_users` (
  `user_ID` int(11) NOT NULL,
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
  `update_user_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_ID`, `username`, `voornaam`, `achternaam`, `email`, `password`, `macadres`, `birthdate`, `last_login_time`, `create_time`, `create_user_ID`, `update_time`, `update_user_ID`) VALUES
(1, 'organisatie', 'organisatie', 'organisatie', 'daheman1@hotmail.com', '098f6bcd4621d373cade4e832627b4f6', NULL, '0000-00-00', '2015-07-28 15:47:22', '2015-07-10 07:05:13', NULL, '2015-07-10 07:05:13', NULL),
(2, 'deelnemera', 'deelnemersa', 'deelnemersa', 'daheman2@hotmail.com', '098f6bcd4621d373cade4e832627b4f6', NULL, '0000-00-00', '2015-07-28 15:47:22', '2015-07-10 07:05:13', NULL, '2015-07-10 07:05:13', NULL),
(3, 'deelnemerb', 'deelnemersb', 'deelnemersb', 'daheman3@hotmail.com', '098f6bcd4621d373cade4e832627b4f6', NULL, '0000-00-00', '2015-07-28 15:47:22', '2015-07-10 07:05:13', NULL, '2015-07-10 07:05:13', NULL);


--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_bonuspunten`
--
ALTER TABLE `tbl_bonuspunten`
  ADD PRIMARY KEY (`bouspunten_ID`),
  ADD KEY `fk_bonuspunten_event_id` (`event_ID`),
  ADD KEY `fk_bonuspunten_post_id` (`post_ID`),
  ADD KEY `fk_bonuspunten_group_id` (`group_ID`),
  ADD KEY `fk_bonuspunten_create_user` (`create_user_ID`),
  ADD KEY `fk_bonuspunten_update_user` (`update_user_ID`);

--
-- Indexes for table `tbl_deelnemers_event`
--
ALTER TABLE `tbl_deelnemers_event`
  ADD PRIMARY KEY (`deelnemers_ID`),
  ADD UNIQUE KEY `event_ID` (`event_ID`,`user_ID`),
  ADD KEY `fk_deelnemers_user_id` (`user_ID`),
  ADD KEY `fk_deelnemers_group_ID` (`group_ID`),
  ADD KEY `fk_deelnemers_create_user` (`create_user_ID`),
  ADD KEY `fk_deelnemers_update_user` (`update_user_ID`);

--
-- Indexes for table `tbl_event_names`
--
ALTER TABLE `tbl_event_names`
  ADD PRIMARY KEY (`event_ID`),
  ADD UNIQUE KEY `event_name` (`event_name`),
  ADD KEY `fk_events_create_user_name` (`create_user_ID`),
  ADD KEY `fk_events_update_user_name` (`update_user_ID`);

--
-- Indexes for table `tbl_friend_list`
--
ALTER TABLE `tbl_friend_list`
  ADD PRIMARY KEY (`friend_list_ID`),
  ADD UNIQUE KEY `friendship_ID` (`user_ID`,`friends_with_user_ID`),
  ADD KEY `fk_friend_list_friends_with_user` (`friends_with_user_ID`),
  ADD KEY `fk_friend_list_create_user` (`create_user_ID`),
  ADD KEY `fk_friend_list_update_user` (`update_user_ID`);

--
-- Indexes for table `tbl_groups`
--
ALTER TABLE `tbl_groups`
  ADD PRIMARY KEY (`group_ID`),
  ADD UNIQUE KEY `event_ID` (`event_ID`,`group_name`),
  ADD KEY `fk_groups_create_user` (`create_user_ID`),
  ADD KEY `fk_groups_update_user` (`update_user_ID`);

--
-- Indexes for table `tbl_migration`
--
ALTER TABLE `tbl_migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `tbl_nood_envelop`
--
ALTER TABLE `tbl_nood_envelop`
  ADD PRIMARY KEY (`nood_envelop_ID`),
  ADD UNIQUE KEY `envelop_id` (`nood_envelop_name`,`event_ID`,`route_ID`),
  ADD KEY `fk_nood_envelop_event_id` (`event_ID`),
  ADD KEY `fk_nood_envelop_route` (`route_ID`),
  ADD KEY `fk_nood_envelop_create_user` (`create_user_ID`),
  ADD KEY `fk_nood_envelop_update_user` (`update_user_ID`);

--
-- Indexes for table `tbl_open_nood_envelop`
--
ALTER TABLE `tbl_open_nood_envelop`
  ADD PRIMARY KEY (`open_nood_envelop_ID`),
  ADD UNIQUE KEY `nood_envelop_ID` (`nood_envelop_ID`,`group_ID`),
  ADD KEY `fk_open_nood_envelop_event_id` (`event_ID`),
  ADD KEY `fk_open_nood_envelop_group_id` (`group_ID`),
  ADD KEY `fk_onood_envelop_create_user` (`create_user_ID`),
  ADD KEY `fk_onood_envelop_update_user` (`update_user_ID`);

--
-- Indexes for table `tbl_open_vragen`
--
ALTER TABLE `tbl_open_vragen`
  ADD PRIMARY KEY (`open_vragen_ID`),
  ADD UNIQUE KEY `open_vragen_name` (`open_vragen_name`,`event_ID`,`route_ID`),
  ADD KEY `fk_open_vragen_event_id` (`event_ID`),
  ADD KEY `fk_open_vragen_route` (`route_ID`),
  ADD KEY `fk_open_vragen_create_user` (`create_user_ID`),
  ADD KEY `fk_open_vragen_update_user` (`update_user_ID`);

--
-- Indexes for table `tbl_open_vragen_antwoorden`
--
ALTER TABLE `tbl_open_vragen_antwoorden`
  ADD PRIMARY KEY (`open_vragen_antwoorden_ID`),
  ADD UNIQUE KEY `open_vragen_ID` (`open_vragen_ID`,`group_ID`),
  ADD KEY `fk_open_vragen_antwoorden_event_id` (`event_ID`),
  ADD KEY `fk_open_vragen_antwoorden_group_id` (`group_ID`),
  ADD KEY `fk_open_vragen_antwoorden_create_user` (`create_user_ID`),
  ADD KEY `fk_open_vragen_antwoorden_update_user` (`update_user_ID`);

--
-- Indexes for table `tbl_posten`
--
ALTER TABLE `tbl_posten`
  ADD PRIMARY KEY (`post_ID`),
  ADD UNIQUE KEY `post_name` (`post_name`,`event_ID`,`date`),
  ADD KEY `fk_posten_event_name` (`event_ID`),
  ADD KEY `fk_posten_create_user` (`create_user_ID`),
  ADD KEY `fk_posten_update_user` (`update_user_ID`);

--
-- Indexes for table `tbl_post_passage`
--
ALTER TABLE `tbl_post_passage`
  ADD PRIMARY KEY (`posten_passage_ID`),
  ADD UNIQUE KEY `post_ID` (`post_ID`,`event_ID`,`group_ID`),
  ADD KEY `fk_post_passage_event_id` (`event_ID`),
  ADD KEY `fk_post_passage_group_name` (`group_ID`),
  ADD KEY `fk_post_passage_create_user` (`create_user_ID`),
  ADD KEY `fk_post_passage_update_user` (`update_user_ID`);

--
-- Indexes for table `tbl_qr`
--
ALTER TABLE `tbl_qr`
  ADD PRIMARY KEY (`qr_ID`),
  ADD UNIQUE KEY `qr_code` (`qr_code`,`event_ID`),
  ADD KEY `fk_qr_event_id` (`event_ID`),
  ADD KEY `fk_qr_route` (`route_ID`),
  ADD KEY `fk_qr_create_user` (`create_user_ID`),
  ADD KEY `fk_qr_update_user` (`update_user_ID`);

--
-- Indexes for table `tbl_qr_check`
--
ALTER TABLE `tbl_qr_check`
  ADD PRIMARY KEY (`qr_check_ID`),
  ADD UNIQUE KEY `qr_ID` (`qr_ID`,`group_ID`),
  ADD KEY `fk_qr_check_qr_event_id` (`event_ID`),
  ADD KEY `fk_qr_check_qr_group_id` (`group_ID`),
  ADD KEY `fk_qr_check_create_user` (`create_user_ID`),
  ADD KEY `fk_qr_check_update_user` (`update_user_ID`);

--
-- Indexes for table `tbl_route`
--
ALTER TABLE `tbl_route`
  ADD PRIMARY KEY (`route_ID`),
  ADD UNIQUE KEY `event_ID` (`event_ID`,`day_date`,`route_name`),
  ADD KEY `fk_route_create_user_name` (`create_user_ID`),
  ADD KEY `fk_route_update_user_name` (`update_user_ID`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_ID`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_bonuspunten`
--
ALTER TABLE `tbl_bonuspunten`
  MODIFY `bouspunten_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tbl_deelnemers_event`
--
ALTER TABLE `tbl_deelnemers_event`
  MODIFY `deelnemers_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=49;
--
-- AUTO_INCREMENT for table `tbl_event_names`
--
ALTER TABLE `tbl_event_names`
  MODIFY `event_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `tbl_friend_list`
--
ALTER TABLE `tbl_friend_list`
  MODIFY `friend_list_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=55;
--
-- AUTO_INCREMENT for table `tbl_groups`
--
ALTER TABLE `tbl_groups`
  MODIFY `group_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `tbl_nood_envelop`
--
ALTER TABLE `tbl_nood_envelop`
  MODIFY `nood_envelop_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `tbl_open_nood_envelop`
--
ALTER TABLE `tbl_open_nood_envelop`
  MODIFY `open_nood_envelop_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `tbl_open_vragen`
--
ALTER TABLE `tbl_open_vragen`
  MODIFY `open_vragen_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT for table `tbl_open_vragen_antwoorden`
--
ALTER TABLE `tbl_open_vragen_antwoorden`
  MODIFY `open_vragen_antwoorden_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=121;
--
-- AUTO_INCREMENT for table `tbl_posten`
--
ALTER TABLE `tbl_posten`
  MODIFY `post_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `tbl_post_passage`
--
ALTER TABLE `tbl_post_passage`
  MODIFY `posten_passage_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT for table `tbl_qr`
--
ALTER TABLE `tbl_qr`
  MODIFY `qr_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `tbl_qr_check`
--
ALTER TABLE `tbl_qr_check`
  MODIFY `qr_check_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=86;
--
-- AUTO_INCREMENT for table `tbl_route`
--
ALTER TABLE `tbl_route`
  MODIFY `route_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=44;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_bonuspunten`
--
ALTER TABLE `tbl_bonuspunten`
  ADD CONSTRAINT `fk_bonuspunten_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bonuspunten_event_id` FOREIGN KEY (`event_ID`) REFERENCES `tbl_event_names` (`event_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bonuspunten_group_id` FOREIGN KEY (`group_ID`) REFERENCES `tbl_groups` (`group_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bonuspunten_post_id` FOREIGN KEY (`post_ID`) REFERENCES `tbl_posten` (`post_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bonuspunten_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_deelnemers_event`
--
ALTER TABLE `tbl_deelnemers_event`
  ADD CONSTRAINT `fk_deelnemers_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_deelnemers_event_event_ID` FOREIGN KEY (`event_ID`) REFERENCES `tbl_event_names` (`event_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_deelnemers_group_ID` FOREIGN KEY (`group_ID`) REFERENCES `tbl_groups` (`group_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_deelnemers_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_deelnemers_user_id` FOREIGN KEY (`user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_event_names`
--
ALTER TABLE `tbl_event_names`
  ADD CONSTRAINT `fk_events_create_user_name` FOREIGN KEY (`create_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_events_update_user_name` FOREIGN KEY (`update_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_friend_list`
--
ALTER TABLE `tbl_friend_list`
  ADD CONSTRAINT `fk_friend_list_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_friend_list_friends_with_user` FOREIGN KEY (`friends_with_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_friend_list_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_friend_list_user` FOREIGN KEY (`user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_groups`
--
ALTER TABLE `tbl_groups`
  ADD CONSTRAINT `fk_groups_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_groups_event_ID` FOREIGN KEY (`event_ID`) REFERENCES `tbl_event_names` (`event_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_groups_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_nood_envelop`
--
ALTER TABLE `tbl_nood_envelop`
  ADD CONSTRAINT `fk_nood_envelop_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_nood_envelop_event_id` FOREIGN KEY (`event_ID`) REFERENCES `tbl_event_names` (`event_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_nood_envelop_route` FOREIGN KEY (`route_ID`) REFERENCES `tbl_route` (`route_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_nood_envelop_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_open_nood_envelop`
--
ALTER TABLE `tbl_open_nood_envelop`
  ADD CONSTRAINT `fk_onood_envelop_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_onood_envelop_id` FOREIGN KEY (`nood_envelop_ID`) REFERENCES `tbl_nood_envelop` (`nood_envelop_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_onood_envelop_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_open_nood_envelop_event_id` FOREIGN KEY (`event_ID`) REFERENCES `tbl_event_names` (`event_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_open_nood_envelop_group_id` FOREIGN KEY (`group_ID`) REFERENCES `tbl_groups` (`group_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_open_vragen`
--
ALTER TABLE `tbl_open_vragen`
  ADD CONSTRAINT `fk_open_vragen_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_open_vragen_event_id` FOREIGN KEY (`event_ID`) REFERENCES `tbl_event_names` (`event_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_open_vragen_route` FOREIGN KEY (`route_ID`) REFERENCES `tbl_route` (`route_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_open_vragen_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_open_vragen_antwoorden`
--
ALTER TABLE `tbl_open_vragen_antwoorden`
  ADD CONSTRAINT `fk_open_vragen_antwoorden_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_open_vragen_antwoorden_event_id` FOREIGN KEY (`event_ID`) REFERENCES `tbl_event_names` (`event_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_open_vragen_antwoorden_group_id` FOREIGN KEY (`group_ID`) REFERENCES `tbl_groups` (`group_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_open_vragen_antwoorden_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_open_vragen_antwoorden_vragen_id` FOREIGN KEY (`open_vragen_ID`) REFERENCES `tbl_open_vragen` (`open_vragen_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_posten`
--
ALTER TABLE `tbl_posten`
  ADD CONSTRAINT `fk_posten_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_posten_event_name` FOREIGN KEY (`event_ID`) REFERENCES `tbl_event_names` (`event_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_posten_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_post_passage`
--
ALTER TABLE `tbl_post_passage`
  ADD CONSTRAINT `fk_post_passage_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_post_passage_event_id` FOREIGN KEY (`event_ID`) REFERENCES `tbl_event_names` (`event_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_post_passage_group_name` FOREIGN KEY (`group_ID`) REFERENCES `tbl_groups` (`group_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_post_passage_post_id` FOREIGN KEY (`post_ID`) REFERENCES `tbl_posten` (`post_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_post_passage_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_qr`
--
ALTER TABLE `tbl_qr`
  ADD CONSTRAINT `fk_qr_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_qr_event_id` FOREIGN KEY (`event_ID`) REFERENCES `tbl_event_names` (`event_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_qr_route` FOREIGN KEY (`route_ID`) REFERENCES `tbl_route` (`route_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_qr_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_qr_check`
--
ALTER TABLE `tbl_qr_check`
  ADD CONSTRAINT `fk_qr_check_create_user` FOREIGN KEY (`create_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_qr_check_qr_event_id` FOREIGN KEY (`event_ID`) REFERENCES `tbl_event_names` (`event_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_qr_check_qr_group_id` FOREIGN KEY (`group_ID`) REFERENCES `tbl_groups` (`group_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_qr_check_qr_id` FOREIGN KEY (`qr_ID`) REFERENCES `tbl_qr` (`qr_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_qr_check_update_user` FOREIGN KEY (`update_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_route`
--
ALTER TABLE `tbl_route`
  ADD CONSTRAINT `fk_route_create_user_name` FOREIGN KEY (`create_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_route_event_id` FOREIGN KEY (`event_ID`) REFERENCES `tbl_event_names` (`event_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_route_update_user_name` FOREIGN KEY (`update_user_ID`) REFERENCES `tbl_users` (`user_ID`) ON UPDATE CASCADE;


--
-- Stand-in structure for view `tbl_hint_score`
--
CREATE TABLE IF NOT EXISTS `tbl_hint_score` (
`event_ID` int(11)
,`group_ID` int(11)
,`hint_score` decimal(32,0)
);

-- --------------------------------------------------------
-- Stand-in structure for view `tbl_bonus_score`
--
CREATE TABLE IF NOT EXISTS `tbl_bonus_score` (
`event_ID` int(11)
,`group_ID` int(11)
,`bonus_score` decimal(32,0)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `tbl_posten_score`
--
CREATE TABLE IF NOT EXISTS `tbl_posten_score` (
`event_ID` int(11)
,`group_ID` int(11)
,`post_score` decimal(32,0)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `tbl_qr_score`
--
CREATE TABLE IF NOT EXISTS `tbl_qr_score` (
`event_ID` int(11)
,`group_ID` int(11)
,`qr_score` decimal(32,0)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `tbl_totaal_score`
--
CREATE TABLE IF NOT EXISTS `tbl_totaal_score` (
`event_ID` int(11)
,`group_ID` int(11)
,`totaal_score` decimal(36,0)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `tbl_vragen_score`
--
CREATE TABLE IF NOT EXISTS `tbl_vragen_score` (
`event_ID` int(11)
,`group_ID` int(11)
,`vragen_score` decimal(32,0)
);
-- --------------------------------------------------------

--
-- Structure for view `tbl_hint_score`
--
DROP TABLE IF EXISTS `tbl_hint_score`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tbl_hint_score` AS select `tbl_open_nood_envelop`.`event_ID` AS `event_ID`,`tbl_open_nood_envelop`.`group_ID` AS `group_ID`,sum(`tbl_nood_envelop`.`score`) AS `hint_score` from (`tbl_open_nood_envelop` left join `tbl_nood_envelop` on((`tbl_nood_envelop`.`nood_envelop_ID` = `tbl_open_nood_envelop`.`nood_envelop_ID`))) group by `tbl_open_nood_envelop`.`group_ID`;

-- --------------------------------------------------------
--
-- Structure for view `tbl_bonus_score`
--
DROP TABLE IF EXISTS `tbl_bonus_score`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tbl_bonus_score` AS select `tbl_bonuspunten`.`event_ID` AS `event_ID`,`tbl_bonuspunten`.`group_ID` AS `group_ID`,sum(`tbl_bonuspunten`.`score`) AS `bonus_score` from `tbl_bonuspunten` group by `tbl_bonuspunten`.`group_ID`;

-- --------------------------------------------------------

--
-- Structure for view `tbl_posten_score`
--
DROP TABLE IF EXISTS `tbl_posten_score`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tbl_posten_score` AS select `tbl_post_passage`.`event_ID` AS `event_ID`,`tbl_post_passage`.`group_ID` AS `group_ID`,sum(`tbl_posten`.`score`) AS `post_score` from (`tbl_post_passage` left join `tbl_posten` on((`tbl_posten`.`post_ID` = `tbl_post_passage`.`post_ID`))) group by `tbl_post_passage`.`group_ID`;

-- --------------------------------------------------------

--
-- Structure for view `tbl_qr_score`
--
DROP TABLE IF EXISTS `tbl_qr_score`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tbl_qr_score` AS select `tbl_qr_check`.`event_ID` AS `event_ID`,`tbl_qr_check`.`group_ID` AS `group_ID`,sum(`tbl_qr`.`score`) AS `qr_score` from (`tbl_qr_check` left join `tbl_qr` on((`tbl_qr`.`qr_ID` = `tbl_qr_check`.`qr_ID`))) group by `tbl_qr_check`.`group_ID`;

-- --------------------------------------------------------

--
-- Structure for view `tbl_totaal_score`
--
DROP TABLE IF EXISTS `tbl_totaal_score`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tbl_totaal_score` AS select `groups`.`event_ID` AS `event_ID`,`groups`.`group_ID` AS `group_ID`,((((coalesce(sum(`tbl_bonuspunten`.`score`),0) - coalesce(`tbl_hint_score`.`hint_score`,0)) + coalesce(`tbl_vragen_score`.`vragen_score`,0)) + coalesce(`tbl_posten_score`.`post_score`,0)) + coalesce(`tbl_qr_score`.`qr_score`,0)) AS `totaal_score` from (((((`tbl_groups` `groups` left join `tbl_posten_score` on((`tbl_posten_score`.`group_ID` = `groups`.`group_ID`))) left join `tbl_vragen_score` on((`tbl_vragen_score`.`group_ID` = `groups`.`group_ID`))) left join `tbl_qr_score` on((`tbl_qr_score`.`group_ID` = `groups`.`group_ID`))) left join `tbl_hint_score` on((`tbl_hint_score`.`group_ID` = `groups`.`group_ID`))) left join `tbl_bonuspunten` on((`tbl_bonuspunten`.`group_ID` = `groups`.`group_ID`))) group by `group_ID`;

-- --------------------------------------------------------

--
-- Structure for view `tbl_vragen_score`
--
DROP TABLE IF EXISTS `tbl_vragen_score`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tbl_vragen_score` AS select `tbl_open_vragen_antwoorden`.`event_ID` AS `event_ID`,`tbl_open_vragen_antwoorden`.`group_ID` AS `group_ID`,sum(`tbl_open_vragen`.`score`) AS `vragen_score` from (`tbl_open_vragen_antwoorden` left join `tbl_open_vragen` on((`tbl_open_vragen`.`open_vragen_ID` = `tbl_open_vragen_antwoorden`.`open_vragen_ID`))) where `tbl_open_vragen_antwoorden`.`checked` = 1 and `tbl_open_vragen_antwoorden`.`correct` = 1 group by `tbl_open_vragen_antwoorden`.`group_ID`;




/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
