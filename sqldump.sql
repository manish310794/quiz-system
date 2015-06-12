/*
SQLyog Trial v12.12 (64 bit)
MySQL - 5.6.16 : Database - zobrando
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`zobrando` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `zobrando`;

/*Table structure for table `answers` */

DROP TABLE IF EXISTS `answers`;

CREATE TABLE `answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_num` int(11) unsigned NOT NULL,
  `quiz_id` int(11) unsigned NOT NULL,
  `text` varchar(255) NOT NULL,
  `correct` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `question_id` (`correct`),
  KEY `quiz_id` (`quiz_id`),
  KEY `quiz_question_num` (`question_num`,`quiz_id`),
  CONSTRAINT `answers_ibfk_2` FOREIGN KEY (`question_num`, `quiz_id`) REFERENCES `questions` (`num`, `quiz_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `answers_ibfk_3` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=639 DEFAULT CHARSET=latin1;

/*Data for the table `answers` */

insert  into `answers`(`id`,`question_num`,`quiz_id`,`text`,`correct`) values (441,3,6,'Norman Bates',1),(442,3,6,'Norman Tebbit',0),(443,3,6,'Norman Wisdom',0),(444,3,6,'Norman Cooke',0),(448,5,6,'Morgan Freeman',1),(449,5,6,'Marlon Brando',0),(450,5,6,'Tim Robbins',0),(451,5,6,'Tom Cruise',0),(452,6,6,'4',1),(453,6,6,'1',0),(454,6,6,'2',0),(455,6,6,'3',0),(456,2,6,'Robert De Niro',1),(457,2,6,'Tom Hanks',0),(458,2,6,'Bob Hope',0),(459,2,6,'Christopher Hall',0),(460,7,6,'Jeff Daniels',1),(461,7,6,'Jeff Goldblum',0),(462,7,6,'Jeff Bridges',0),(463,7,6,'Jeff Branson',0),(536,1,6,'Tom Hanks',1),(537,1,6,'Bob Hoskins',0),(538,1,6,'Robert De Niro',0),(539,1,6,'Marlon Brando',0),(549,1,10,'Freddie',1),(550,1,10,'Bob',0),(551,1,10,'Jimmy',0),(552,1,10,'Richard',0),(587,4,6,'Stan and Oliver',1),(588,4,6,'Stan and Groucho',0),(589,4,6,'Bob and Billy',0),(607,1,11,'Alexander Graham Bell',1),(608,1,11,'Barry Manilow',0),(609,1,11,'Albert Einstein',0),(610,2,11,'Albert Einstein',1),(611,2,11,'David Beckham',0),(612,2,11,'Samuel Johnson',0),(633,1,15,'abc',0),(634,1,15,'xyz',1),(637,2,15,'Nice',1),(638,2,15,'Great',0);

/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Data for the table `categories` */

insert  into `categories`(`id`,`name`,`description`) values (1,'Sports','Sports related quizzes'),(2,'Films','Movie related quizzes'),(3,'Technology','Tecnology related quizzes'),(4,'General Knowledge','General Knowledge related quizzes'),(5,'Science','Science related quizzes'),(6,'Music','Music related quizzes');

/*Table structure for table `questions` */

DROP TABLE IF EXISTS `questions`;

CREATE TABLE `questions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `num` int(11) unsigned NOT NULL,
  `quiz_id` int(11) unsigned NOT NULL,
  `text` varchar(255) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quiz_id` (`quiz_id`),
  KEY `num` (`num`),
  KEY `num_2` (`num`,`quiz_id`),
  KEY `id` (`id`),
  CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=latin1;

/*Data for the table `questions` */

insert  into `questions`(`id`,`num`,`quiz_id`,`text`,`url`,`type`) values (15,1,6,'Who played Forrest Gump?','https://www.youtube.com/watch?v=4pby9Pe3Y2E',NULL),(16,2,6,'Who played the lead role in Taxi Driver?',NULL,NULL),(17,3,6,'What was the name of the killer in Psycho?',NULL,NULL),(18,4,6,'What were the first names of Laurel And Hardy?',NULL,NULL),(19,5,6,'Who played \'Red\' in The Shawshank Redemption?',NULL,NULL),(20,6,6,'How many films were there in the \'Alien\' series?',NULL,NULL),(21,7,6,'Who starred in \'Chasing Sleep\' from 2000',NULL,NULL),(32,1,10,'Lead singer of the band Queen',NULL,NULL),(45,1,11,'Invented The Telephone',NULL,NULL),(46,2,11,'Known For e=mc2',NULL,NULL),(57,1,15,'this question','284.png',2),(58,2,15,'What you think of bat','18515.png',2);

/*Table structure for table `quiz_users` */

DROP TABLE IF EXISTS `quiz_users`;

CREATE TABLE `quiz_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quiz_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `score` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `date_submitted` datetime NOT NULL,
  `time_taken` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `quiz_id` (`quiz_id`),
  CONSTRAINT `quiz_users_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;

/*Data for the table `quiz_users` */

insert  into `quiz_users`(`id`,`quiz_id`,`user_id`,`score`,`start_time`,`date_submitted`,`time_taken`) values (23,6,193,3,'2014-10-30 20:35:46','2014-10-30 20:36:14','00:28'),(26,6,192,3,'2014-10-31 07:54:37','2014-10-31 07:57:16','02:39'),(28,10,192,1,'2014-10-31 08:40:57','2014-10-31 08:41:04','00:07'),(29,10,194,2,'2014-10-31 08:43:18','2014-10-31 08:43:24','00:06'),(30,11,196,3,'2014-10-31 19:42:15','2014-10-31 19:42:32','00:17'),(31,6,194,4,'2014-11-06 21:07:09','2014-11-06 21:07:34','25'),(32,11,194,1,'2015-04-25 17:38:46','2015-04-25 17:39:00','14'),(33,10,228,3,'2015-05-03 11:49:49','2015-05-03 11:51:25','96'),(34,6,228,6,'2015-05-03 22:03:05','2015-05-03 22:03:43','38');

/*Table structure for table `quizzes` */

DROP TABLE IF EXISTS `quizzes`;

CREATE TABLE `quizzes` (
  `duration` int(3) NOT NULL DEFAULT '-1',
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `category` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `active` (`active`),
  KEY `id` (`id`),
  KEY `created` (`created`),
  KEY `updated` (`updated`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

/*Data for the table `quizzes` */

insert  into `quizzes`(`duration`,`id`,`name`,`description`,`category`,`active`,`created`,`updated`) values (-1,6,'Movies R Us','Know your movie trivia? Prove it!',2,1,'2013-11-22 03:25:12','2014-10-19 22:33:36'),(1,10,'Band Members','Name the member of these famous bands',6,1,'2014-10-20 21:49:01','2015-06-05 14:12:54'),(-1,11,'Famous Scientists','Name These Pioneers Of Science',5,1,'2014-11-01 01:05:56','2014-10-31 19:35:56'),(9,15,'My Quiz','This is a quiz',1,1,'2015-06-08 22:16:08','2015-06-08 22:16:08');

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(32) NOT NULL,
  `access` int(10) unsigned DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `sessions` */

insert  into `sessions`(`id`,`access`,`data`) values ('006r040es637e60mvg7ltei8s1',1433837032,'slim.flash|a:0:{}quizid|s:1:\"6\";score|i:0;correct|a:0:{}wrong|a:0:{}finished|s:2:\"no\";num|i:0;last|N;timetaken|N;starttime|N;'),('3ob35459svir7s692ori67i254',1433765512,'slim.flash|a:0:{}'),('9dnfi4p7cpuaasil0veh8rs5a0',1433774293,'slim.flash|a:0:{}'),('bmvlo0r32a9i4sh94gbps51dl7',1433838770,'slim.flash|a:0:{}'),('c1becsdh3hc9ksg807cbe2qog0',1433783620,'slim.flash|a:0:{}'),('eh5efvta1jn5ndtnc908pikan2',1433765678,'slim.flash|a:0:{}'),('llnqofoaehbgmv070cet1ulg26',1433848750,'slim.flash|a:0:{}user|O:31:\"SimpleQuiz\\Utils\\User\\AdminUser\":5:{s:32:\"\0SimpleQuiz\\Utils\\Base\\User\0name\";s:5:\"Admin\";s:33:\"\0SimpleQuiz\\Utils\\Base\\User\0email\";s:17:\"example@gmail.com\";s:35:\"\0SimpleQuiz\\Utils\\Base\\User\0quizzes\";N;s:36:\"\0SimpleQuiz\\Utils\\Base\\User\0password\";N;s:30:\"\0SimpleQuiz\\Utils\\Base\\User\0id\";s:3:\"157\";}'),('ltnfpia4a1pf8q8fu2e3cr5215',1433765401,'slim.flash|a:0:{}'),('m8afqsnn0prgjc6eouu7c7g044',1433771047,'slim.flash|a:0:{}user|O:31:\"SimpleQuiz\\Utils\\User\\AdminUser\":5:{s:32:\"\0SimpleQuiz\\Utils\\Base\\User\0name\";s:5:\"Admin\";s:33:\"\0SimpleQuiz\\Utils\\Base\\User\0email\";s:17:\"example@gmail.com\";s:35:\"\0SimpleQuiz\\Utils\\Base\\User\0quizzes\";N;s:36:\"\0SimpleQuiz\\Utils\\Base\\User\0password\";N;s:30:\"\0SimpleQuiz\\Utils\\Base\\User\0id\";s:3:\"157\";}'),('n5dnu8d6qo4ra16gfdea9g8rv4',1433848412,'slim.flash|a:0:{}'),('phbv6g0b4t6kaf538ije9351d4',1433783696,'slim.flash|a:0:{}'),('rrt7gdf621g4ojeph3q6gkb036',1433838836,'slim.flash|a:0:{}user|O:29:\"SimpleQuiz\\Utils\\User\\EndUser\":6:{s:38:\"\0SimpleQuiz\\Utils\\User\\EndUser\0quizzes\";N;s:32:\"\0SimpleQuiz\\Utils\\Base\\User\0name\";s:13:\"manish.310794\";s:33:\"\0SimpleQuiz\\Utils\\Base\\User\0email\";s:23:\"manish.310794@gmail.com\";s:36:\"\0SimpleQuiz\\Utils\\Base\\User\0password\";N;s:30:\"\0SimpleQuiz\\Utils\\Base\\User\0id\";s:3:\"229\";s:35:\"\0SimpleQuiz\\Utils\\Base\\User\0quizzes\";N;}quizid|s:2:\"15\";score|i:0;correct|a:0:{}wrong|a:1:{i:1;a:1:{i:0;s:3:\"abc\";}}finished|s:2:\"no\";num|i:2;last|N;timetaken|N;starttime|i:1433838806;nonce|s:32:\"934b3b7d6e80600af207a4c6bcfe1a52\";'),('uqvbeaqao73e88r856bmreqhi6',1433774305,'slim.flash|a:0:{}');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `email` varchar(40) NOT NULL,
  `level` int(1) NOT NULL DEFAULT '0',
  `confirmed` tinyint(4) NOT NULL DEFAULT '0',
  `confirmhash` varchar(40) DEFAULT NULL,
  `hashstamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `pass` (`pass`),
  KEY `confirmed` (`confirmed`)
) ENGINE=InnoDB AUTO_INCREMENT=230 DEFAULT CHARSET=latin1;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`pass`,`email`,`level`,`confirmed`,`confirmhash`,`hashstamp`) values (157,'Admin','$2y$10$LK9O0BesGScRkDWPnpVP3uGVcN6JqB/xsuFTq/xQFpNjsx2DvTOl2','example@gmail.com',1,1,NULL,NULL),(192,'user1','$2y$10$D2tpVb9i6GsPawn1H18tCu2s.2T9uHHWMQY7Osyeh12AzJsJ9Y5VO','examples@gmail.com',0,0,NULL,NULL),(193,'user2','$2y$10$DOueZ880b4buKA2sm0a67OzZNSfv3ev7DT31tI53Moq1pGA9h/Dx6','example2@gmail.com',0,0,NULL,NULL),(194,'user3','$2y$10$cUcIj1qyd1rWYE3vQTXW8emBx27Je9ZWcgNMDUnKN3a5n9kCED/S2','example@gmail.com1',0,0,NULL,NULL),(195,'user4','$2y$10$n1Y3HJSwWxq0toQa8pQzb.kra1mfMySsaCsC/bH0/oE3oMNLM7GmO','example@gmail.com432432',0,0,NULL,NULL),(196,'user6','$2y$10$B4ufMwQ9BzhGLVfY0CTBseqVIhSbRk1XQB8zu5LmOor9uAeLXeQIa','example4@gmail.com',0,0,NULL,NULL),(229,'manish.310794','$2y$10$xnFuyRBggTePJXmYJvpDAuUZ4hOPVZQt8g8tKQGlLN.t6gJK/gqgO','manish.310794@gmail.com',0,0,NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
