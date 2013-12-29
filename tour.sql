# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.6.15)
# Database: tours
# Generation Time: 2013-12-29 01:34:03 +0000
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
  `street` varchar(50) DEFAULT NULL,
  `unit_type` varchar(10) DEFAULT NULL,
  `unit` varchar(10) DEFAULT NULL,
  `city` varchar(25) NOT NULL DEFAULT '',
  `state` varchar(2) NOT NULL DEFAULT 'MN',
  `zip` varchar(10) NOT NULL DEFAULT '',
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table phone
# ------------------------------------------------------------

DROP TABLE IF EXISTS `phone`;

CREATE TABLE `phone` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `phone` varchar(25) NOT NULL DEFAULT '',
  `phone_type` varchar(10) DEFAULT NULL,
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
  `password` varchar(32) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `is_verified` tinyint(1) DEFAULT NULL,
  `reset_hash` varchar(32) NOT NULL COMMENT 'used for verifying password resets',
  `is_active` tinyint(1) NOT NULL,
  `role` enum('user','admin') NOT NULL,
  `start_date` date DEFAULT NULL COMMENT 'Date joined the household',
  `end_date` date DEFAULT NULL COMMENT 'Date left the household',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



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




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
