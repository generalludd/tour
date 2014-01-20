# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.31-0ubuntu0.12.04.2)
# Database: tours
# Generation Time: 2014-01-20 02:33:13 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table address
# ------------------------------------------------------------

DROP TABLE IF EXISTS `address`;

CREATE TABLE `address` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `num` varchar(25) DEFAULT NULL,
  `street` varchar(65) DEFAULT NULL,
  `unit` varchar(35) DEFAULT NULL,
  `city` varchar(25) NOT NULL DEFAULT '',
  `state` varchar(2) NOT NULL DEFAULT 'MN',
  `zip` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table contact
# ------------------------------------------------------------

DROP TABLE IF EXISTS `contact`;

CREATE TABLE `contact` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hotel_id` int(11) NOT NULL,
  `contact` varchar(50) NOT NULL DEFAULT '',
  `position` varchar(55) DEFAULT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `fax` varchar(25) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Contacts for hotels and possibly other future businesses that might be included in the tour planning';



# Dump of table hotel
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hotel`;

CREATE TABLE `hotel` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hotel_name` varchar(255) NOT NULL DEFAULT '',
  `tour_id` int(11) NOT NULL,
  `stay` int(11) DEFAULT NULL,
  `arrival_date` date DEFAULT NULL,
  `arrival_time` varchar(7) DEFAULT NULL,
  `departure_date` date DEFAULT NULL,
  `departure_time` varchar(7) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `url` varchar(90) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `first_contact` varchar(90) DEFAULT NULL,
  `first_contact_position` varchar(30) DEFAULT NULL,
  `first_contact_phone` varchar(25) DEFAULT NULL,
  `first_contact_email` varchar(50) DEFAULT NULL,
  `second_contact` varchar(90) DEFAULT NULL,
  `second_contact_position` varchar(30) DEFAULT NULL,
  `second_contact_phone` varchar(25) DEFAULT NULL,
  `second_contact_email` varchar(50) DEFAULT NULL,
  `third_contact` varchar(90) DEFAULT NULL,
  `third_contact_position` varchar(30) DEFAULT NULL,
  `third_contact_phone` varchar(25) DEFAULT NULL,
  `third_contact_email` varchar(50) DEFAULT NULL,
  `address` text,
  `note` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table message
# ------------------------------------------------------------

DROP TABLE IF EXISTS `message`;

CREATE TABLE `message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `subject` varchar(100) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `message_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table payer
# ------------------------------------------------------------

DROP TABLE IF EXISTS `payer`;

CREATE TABLE `payer` (
  `payer_id` int(11) NOT NULL COMMENT 'if of the person paying for the tickets',
  `tour_id` int(11) NOT NULL,
  `payment_type` varchar(25) DEFAULT NULL,
  `room_size` varchar(25) DEFAULT 'double_rate',
  `discount` int(11) DEFAULT NULL,
  `amt_paid` int(11) DEFAULT NULL,
  `notes` text,
  `is_comp` tinyint(1) DEFAULT '0',
  `is_cancelled` tinyint(1) DEFAULT '0',
  `note` text,
  PRIMARY KEY (`payer_id`,`tour_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table person
# ------------------------------------------------------------

DROP TABLE IF EXISTS `person`;

CREATE TABLE `person` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `address_id` int(11) DEFAULT NULL,
  `first_name` varchar(80) NOT NULL DEFAULT '',
  `last_name` varchar(80) NOT NULL DEFAULT '',
  `email` varchar(60) DEFAULT NULL,
  `shirt_size` varchar(10) DEFAULT NULL,
  `salutation` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table phone
# ------------------------------------------------------------

DROP TABLE IF EXISTS `phone`;

CREATE TABLE `phone` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `phone` varchar(25) NOT NULL DEFAULT '',
  `phone_type` varchar(10) DEFAULT NULL,
  `person_link` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table phone_person
# ------------------------------------------------------------

DROP TABLE IF EXISTS `phone_person`;

CREATE TABLE `phone_person` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `phone_id` int(11) DEFAULT NULL,
  `person_id` int(11) DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table receipt
# ------------------------------------------------------------

DROP TABLE IF EXISTS `receipt`;

CREATE TABLE `receipt` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `message_id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `status` varchar(25) NOT NULL DEFAULT '',
  `body` text,
  `receipt_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `message_id` (`message_id`,`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table room
# ------------------------------------------------------------

DROP TABLE IF EXISTS `room`;

CREATE TABLE `room` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) DEFAULT NULL,
  `stay` int(11) DEFAULT NULL,
  `room` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table roommate
# ------------------------------------------------------------

DROP TABLE IF EXISTS `roommate`;

CREATE TABLE `roommate` (
  `person_id` int(11) NOT NULL DEFAULT '0',
  `tour_id` int(11) NOT NULL DEFAULT '0',
  `stay` int(11) NOT NULL DEFAULT '0',
  `room` int(11) NOT NULL,
  PRIMARY KEY (`person_id`,`tour_id`,`stay`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table tour
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tour`;

CREATE TABLE `tour` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tour_name` varchar(80) NOT NULL DEFAULT '',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `full_price` int(11) DEFAULT NULL,
  `banquet_price` int(11) DEFAULT NULL,
  `early_price` int(11) DEFAULT NULL,
  `regular_price` int(11) DEFAULT NULL,
  `single_room` int(11) DEFAULT NULL,
  `double_room` int(11) DEFAULT '0',
  `triple_room` int(11) DEFAULT NULL,
  `quad_room` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table tourist
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tourist`;

CREATE TABLE `tourist` (
  `tour_id` int(11) NOT NULL DEFAULT '0',
  `person_id` int(11) NOT NULL DEFAULT '0',
  `payer_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`tour_id`,`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(55) NOT NULL,
  `password` varchar(32) DEFAULT '',
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `is_verified` tinyint(1) DEFAULT NULL,
  `reset_hash` varchar(32) DEFAULT '' COMMENT 'used for verifying password resets',
  `is_active` tinyint(1) NOT NULL,
  `role` enum('user','admin') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table user_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_log`;

CREATE TABLE `user_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(4) NOT NULL,
  `username` varchar(55) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `action` enum('login','logout') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `logTime` (`time`),
  KEY `logAction` (`action`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='database for tracking user logins/logouts';



# Dump of table user_sessions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_sessions`;

CREATE TABLE `user_sessions` (
  `session_id` varchar(64) NOT NULL DEFAULT '',
  `ip_address` varchar(18) NOT NULL,
  `user_agent` varchar(120) DEFAULT NULL,
  `last_activity` int(11) NOT NULL,
  `user_data` text NOT NULL,
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table variable
# ------------------------------------------------------------

DROP TABLE IF EXISTS `variable`;

CREATE TABLE `variable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` varchar(25) NOT NULL,
  `name` varchar(55) NOT NULL,
  `value` varchar(55) NOT NULL,
  `type` enum('varchar','int') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `variable` (`id`, `class`, `name`, `value`, `type`)
VALUES
	(13, 'room_size', 'Double', 'double_room', 'varchar'),
	(14, 'room_size', 'Single', 'single_room', 'varchar'),
	(15, 'room_size', 'Triple', 'triple_room', 'varchar'),
	(16, 'room_size', 'Quad', 'quad_room', 'varchar'),
	(17, 'payment_type', 'Pay in Full', 'full_price', 'varchar'),
	(18, 'payment_type', 'Banquet Price', 'banquet_price', 'varchar'),
	(19, 'payment_type', 'Early Bird Price', 'early_price', 'varchar'),
	(20, 'payment_type', 'Regular Price', 'regular_price', 'varchar'),
	(22, 'shirt_size', 'S', 'S', 'varchar'),
	(23, 'shirt_size', 'M', 'M', 'varchar'),
	(24, 'shirt_size', 'L', 'L', 'varchar'),
	(25, 'shirt_size', 'XL', 'XL', 'varchar'),
	(26, 'shirt_size', 'XXL', 'XXL', 'varchar'),
	(27, 'shirt_size', 'XXXL', 'XXXL', 'varchar'),
	(28, 'shirt_size', 'XXXXL', 'XXXXL', 'varchar'),
	(32, 'user_status', 'active', '1', 'varchar'),
	(33, 'user_status', 'inactive', '0', 'varchar'),
	(34, 'user_role', 'Administrator', 'admin', 'varchar'),
	(35, 'user_role', 'User', 'user', 'varchar');



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
