-- MySQL dump 10.11
--
-- Host: localhost    Database: nags
-- ------------------------------------------------------
-- Server version	5.0.45-Debian_1ubuntu3.4-log

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
-- Table structure for table `exits`
--

DROP TABLE IF EXISTS `exits`;
CREATE TABLE `exits` (
  `id` int(11) NOT NULL auto_increment,
  `zone` int(4) NOT NULL,
  `room` int(4) NOT NULL,
  `direction` char(2) NOT NULL,
  `dest_zone` int(4) NOT NULL,
  `dest_room` int(4) NOT NULL,
  `door` int(1) default '0' COMMENT 'True = 1, False = 0',
  `lockable` int(1) default '0' COMMENT '1 = Door can be locked, 0 = Door can not be locked',
  `closed` int(1) default '0' COMMENT '1 = Door is closed, 0 = Door is open.',
  `lock_owner` int(12) default NULL COMMENT 'id of player that has the door locked.',
  `key` int(12) default '0' COMMENT 'id of object that acts as the key to lock or unlock door, if',
  `locked` int(1) default '0' COMMENT '1 = Door is locked, 0 = Door is unlocked',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `exits`
--

LOCK TABLES `exits` WRITE;
/*!40000 ALTER TABLE `exits` DISABLE KEYS */;
INSERT INTO `exits` VALUES (1,1,1,'NO',1,2,1,1,0,NULL,0,0);
INSERT INTO `exits` VALUES (3,1,1,'SO',0,0,1,0,0,NULL,0,0);
INSERT INTO `exits` VALUES (4,0,0,'NO',1,1,1,0,0,NULL,0,0);
INSERT INTO `exits` VALUES (5,1,2,'SO',1,1,1,1,0,NULL,0,0);
INSERT INTO `exits` VALUES (6,1,1,'NE',1,4,1,0,0,NULL,0,0);
INSERT INTO `exits` VALUES (7,1,4,'SW',1,1,1,0,0,NULL,0,0);
/*!40000 ALTER TABLE `exits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `help`
--

DROP TABLE IF EXISTS `help`;
CREATE TABLE `help` (
  `help_id` int(12) NOT NULL auto_increment,
  `topic` varchar(30) NOT NULL,
  `descr` text NOT NULL,
  `see_also` text,
  PRIMARY KEY  (`help_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `help`
--

LOCK TABLES `help` WRITE;
/*!40000 ALTER TABLE `help` DISABLE KEYS */;
INSERT INTO `help` VALUES (1,'help','Displays help on a command or subject.\r\n\\tUsage: help <command>\r\n\\tExample: help look\r\n\\tFor a list of commands that you can use type `commands`.','commands,?');
INSERT INTO `help` VALUES (2,'look','This command allows you to look at a room, person, monster, npc or object.\r\n\\tUsage: look\\t\\tLooks at the room you are in.\r\n\\tUsage: look <person>\\tLooks at the person you specify.\r\n\\tExample: look John Doe\\tWill give you general information on the player John Doe if they are in the room with you.','l');
INSERT INTO `help` VALUES (3,'l','This is an alias for the `look` command.\r\nThis command allows you to look at a room, person, monster, npc or object.\r\n\\tUsage: look\\t\\tLooks at the room you are in.\r\n\\tUsage: look <person>\\tLooks at the person you specify.\r\n\\tExample: look John Doe\\tWill give you general information on the player John Doe if they are in the room with you.','look');
INSERT INTO `help` VALUES (7,'commands','This command will provide a list of all the commands you are able to use.\r\n	Usage: commands\r\n	Example: commands','help');
INSERT INTO `help` VALUES (8,'?','This is an alias for the `help` command\r\nDisplays help on a command or subject.\r\n\\tUsage: help <command>\r\n\\tExample: help look\r\n\\tFor a list of commands that you can use type `commands`.','commands,help');
/*!40000 ALTER TABLE `help` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pflags`
--

DROP TABLE IF EXISTS `pflags`;
CREATE TABLE `pflags` (
  `FLAG_ID` int(12) unsigned zerofill NOT NULL auto_increment,
  `P_FLAG` varchar(20) default NULL,
  PRIMARY KEY  (`FLAG_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pflags`
--

LOCK TABLES `pflags` WRITE;
/*!40000 ALTER TABLE `pflags` DISABLE KEYS */;
INSERT INTO `pflags` VALUES (000000000001,'SYSADMIN');
INSERT INTO `pflags` VALUES (000000000010,'USE_PSET');
INSERT INTO `pflags` VALUES (000000000003,'HEADBUILDER');
INSERT INTO `pflags` VALUES (000000000004,'USE_BUILD');
INSERT INTO `pflags` VALUES (000000000005,'USE_GOTO');
INSERT INTO `pflags` VALUES (000000000006,'USE_REBOOT');
INSERT INTO `pflags` VALUES (000000000007,'USE_SHUTDOWN');
INSERT INTO `pflags` VALUES (000000000008,'USE_RLIST');
INSERT INTO `pflags` VALUES (000000000009,'USE_ZLIST');
INSERT INTO `pflags` VALUES (000000000011,'USE_PSTAT');
INSERT INTO `pflags` VALUES (000000000012,'USE_REDIT');
INSERT INTO `pflags` VALUES (000000000013,'USE_PEDIT');
/*!40000 ALTER TABLE `pflags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `player_flags`
--

DROP TABLE IF EXISTS `player_flags`;
CREATE TABLE `player_flags` (
  `id` int(12) NOT NULL auto_increment,
  `user_id` int(12) default NULL,
  `flag` varchar(60) NOT NULL,
  `value` varchar(60) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `player_flags`
--

LOCK TABLES `player_flags` WRITE;
/*!40000 ALTER TABLE `player_flags` DISABLE KEYS */;
INSERT INTO `player_flags` VALUES (1,1,'SYSADMIN','true');
INSERT INTO `player_flags` VALUES (26,4,'BUILDER_ZONE','2');
INSERT INTO `player_flags` VALUES (27,4,'BUILDER','true');
INSERT INTO `player_flags` VALUES (7,5,'HEADBUILDER','true');
INSERT INTO `player_flags` VALUES (8,1,'USE_BUILD','true');
INSERT INTO `player_flags` VALUES (9,4,'USE_BUILD','true');
INSERT INTO `player_flags` VALUES (10,5,'USE_BUILD','true');
INSERT INTO `player_flags` VALUES (11,1,'USE_GOTO','true');
INSERT INTO `player_flags` VALUES (12,4,'USE_GOTO','true');
INSERT INTO `player_flags` VALUES (13,5,'USE_GOTO','true');
INSERT INTO `player_flags` VALUES (14,1,'USE_REBOOT','true');
INSERT INTO `player_flags` VALUES (15,4,'USE_REBOOT','true');
INSERT INTO `player_flags` VALUES (16,5,'USE_REBOOT','true');
INSERT INTO `player_flags` VALUES (17,1,'USE_SHUTDOWN','true');
INSERT INTO `player_flags` VALUES (18,1,'USE_RLIST','true');
INSERT INTO `player_flags` VALUES (19,1,'USE_ZLIST','true');
INSERT INTO `player_flags` VALUES (20,1,'USE_PSTAT','true');
INSERT INTO `player_flags` VALUES (21,1,'USE_REDIT','true');
INSERT INTO `player_flags` VALUES (22,1,'USE_PEDIT','true');
INSERT INTO `player_flags` VALUES (23,1,'USE_PLIST','true');
INSERT INTO `player_flags` VALUES (24,1,'USE_PFLAGS','true');
INSERT INTO `player_flags` VALUES (25,4,'USE_REDIT','true');
INSERT INTO `player_flags` VALUES (28,1,'USE_USERS','true');
/*!40000 ALTER TABLE `player_flags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `players`
--

DROP TABLE IF EXISTS `players`;
CREATE TABLE `players` (
  `user_id` int(12) unsigned zerofill NOT NULL auto_increment COMMENT 'Auto index',
  `username` varchar(25) NOT NULL default '',
  `password` char(32) default NULL,
  `first_name` varchar(25) default NULL,
  `last_name` varchar(25) default NULL,
  `email` varchar(150) NOT NULL,
  `sex` char(1) default 'm',
  `level` int(3) default '0',
  `zone` int(4) unsigned zerofill default '0000',
  `room` int(4) unsigned zerofill default '0000',
  PRIMARY KEY  (`user_id`,`username`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `username` (`username`),
  KEY `zone` (`zone`),
  KEY `room` (`room`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `players`
--

LOCK TABLES `players` WRITE;
/*!40000 ALTER TABLE `players` DISABLE KEYS */;
INSERT INTO `players` VALUES (000000000001,'Admin','5f4dcc3b5aa765d61d8327deb882cf99','Admin','Account','admin@mydomain.com','m',0,0000,0000);
INSERT INTO `players` VALUES (000000000002,'guest','5f4dcc3b5aa765d61d8327deb882cf99','Guest','Account','guest@mydomain.com','M',0,0001,0001);
INSERT INTO `players` VALUES (000000000004,'builder','5f4dcc3b5aa765d61d8327deb882cf99','Builder','Account','builder@mydomain.com','F',0,0002,0000);
INSERT INTO `players` VALUES (000000000005,'headbuilder','5f4dcc3b5aa765d61d8327deb882cf99','Head','Builder','headbuilder@mydomain.com','m',0,0000,0000);
/*!40000 ALTER TABLE `players` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
CREATE TABLE `rooms` (
  `real_room_id` int(8) unsigned zerofill NOT NULL auto_increment,
  `room_id` int(4) unsigned zerofill NOT NULL,
  `zone_id` int(4) unsigned zerofill NOT NULL,
  `room_name` varchar(80) default NULL,
  `room_descr` text,
  `room_flags` varchar(24) default NULL,
  PRIMARY KEY  (`real_room_id`,`room_id`),
  UNIQUE KEY `real_room_id` (`real_room_id`),
  KEY `room_id` (`room_id`),
  KEY `zone_id` (`zone_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rooms`
--

LOCK TABLES `rooms` WRITE;
/*!40000 ALTER TABLE `rooms` DISABLE KEYS */;
INSERT INTO `rooms` VALUES (00000001,0000,0000,'Purgatory','You stand before a black abyss streaching in all directions for as far as you\ncan see. The hard, heat cracked mud shows no sign of footprints. Nothing do do\nhere but wait.',NULL);
INSERT INTO `rooms` VALUES (00000002,0001,0001,'A Rancid Pit.','A liquid-filled pit extends to every wall of this chamber. The liquid lies about\n10 feet below your feet and is so murky that you can\'t see its bottom. The room\nsmells sour. A rope bridge extends from your door to the room\'s other exit.',NULL);
INSERT INTO `rooms` VALUES (00000003,0002,0001,'The Bone Keeper','You gaze into the room and hundreds of skulls gaze coldly back at you. They\'re\nset in niches in the walls in a checkerboard pattern, each skull bearing a\nhalf-melted candle on its head. The grinning bones stare vacantly into the room,\nwhich otherwise seems empty.',NULL);
INSERT INTO `rooms` VALUES (00000004,0003,0001,'A really really really reallyreally really really reallyreally really really rea','not much here',NULL);
INSERT INTO `rooms` VALUES (00000005,0004,0001,'A small chamber','This small chamber seems divided into three parts. The first has several hooks\non the walls from which hang dusty robes. An open curtain separates that space\nfrom the next, which has a dry basin set in the floor. Beyond that lies another\nparted curtain behind which you can see several straw mats in a semicircle\npointing toward a statue of a dog-headed man.',NULL);
INSERT INTO `rooms` VALUES (00000017,0000,0002,'A new room','This is a sample room description.\n',NULL);
/*!40000 ALTER TABLE `rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `socials`
--

DROP TABLE IF EXISTS `socials`;
CREATE TABLE `socials` (
  `social_id` int(12) unsigned zerofill NOT NULL auto_increment,
  `social_command` char(20) NOT NULL,
  `act_char_no_vict` varchar(255) NOT NULL,
  `act_room_no_vict` varchar(255) NOT NULL,
  `act_char_vict_self` varchar(255) default NULL,
  `act_room_vict_self` varchar(255) default NULL,
  `act_char_vict_found` varchar(255) default NULL,
  `act_room_vict_found` varchar(255) default NULL,
  `act_vict_vict_found` varchar(255) default NULL,
  `act_char_vict_not_found` varchar(255) default NULL,
  `act_room_vict_not_found` varchar(255) default NULL,
  PRIMARY KEY  (`social_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `socials`
--

LOCK TABLES `socials` WRITE;
/*!40000 ALTER TABLE `socials` DISABLE KEYS */;
INSERT INTO `socials` VALUES (000000000001,'hug','Hug who?','','You hug yourself.','%A hugs %eself.','You hug %V.','%A hugs %V.','%A hugs you.','Sorry, friend, I can\'t see that person here.','');
INSERT INTO `socials` VALUES (000000000003,'french','French whom??','','You gather yourself in your arms and try to kiss yourself.','%A makes an attempt at kissing %eself.','You give %V a long and passionate kiss, it seems to take forever...','%A kisses %V passionately.','%A gives you a long and passionate kiss, it seems to take forever...','Your heart is filled with despair as that person is not here.',NULL);
INSERT INTO `socials` VALUES (000000000002,'accuse','Accuse who?','','You accuse yourself.','%A seems to have a bad conscience.','You look accusingly at %V.','%A looks accusingly at %V.','%A looks accusingly at you.','Accuse somebody who\'s not even there??','');
/*!40000 ALTER TABLE `socials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `socket`
--

DROP TABLE IF EXISTS `socket`;
CREATE TABLE `socket` (
  `socket` int(3) NOT NULL,
  `user_id` int(12) default NULL,
  `ip_address` varchar(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `socket`
--

LOCK TABLES `socket` WRITE;
/*!40000 ALTER TABLE `socket` DISABLE KEYS */;
/*!40000 ALTER TABLE `socket` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `types`
--

DROP TABLE IF EXISTS `types`;
CREATE TABLE `types` (
  `type_id` int(1) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  PRIMARY KEY  (`type_id`),
  UNIQUE KEY `type_id` (`type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1000000 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `types`
--

LOCK TABLES `types` WRITE;
/*!40000 ALTER TABLE `types` DISABLE KEYS */;
INSERT INTO `types` VALUES (1,'PLAYER');
INSERT INTO `types` VALUES (700000,'BUILDER');
INSERT INTO `types` VALUES (800000,'MASTER');
INSERT INTO `types` VALUES (900000,'WIZARD');
INSERT INTO `types` VALUES (999998,'COADMIN');
INSERT INTO `types` VALUES (999999,'SYSADMIN');
/*!40000 ALTER TABLE `types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zones`
--

DROP TABLE IF EXISTS `zones`;
CREATE TABLE `zones` (
  `zone_id` int(4) unsigned zerofill NOT NULL auto_increment,
  `zone_name` varchar(40) default 'NEW ZONE',
  PRIMARY KEY  (`zone_id`),
  UNIQUE KEY `zone_id` (`zone_id`),
  KEY `zone_name` (`zone_name`)
) ENGINE=MyISAM AUTO_INCREMENT=1001 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `zones`
--

LOCK TABLES `zones` WRITE;
/*!40000 ALTER TABLE `zones` DISABLE KEYS */;
INSERT INTO `zones` VALUES (0000,'Internal Use Rooms');
INSERT INTO `zones` VALUES (0001,'Staff Area');
INSERT INTO `zones` VALUES (0002,'A New Zone');
/*!40000 ALTER TABLE `zones` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2009-02-01  6:14:11
