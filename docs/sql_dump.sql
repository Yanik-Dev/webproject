-- MySQL dump 10.15  Distrib 10.0.31-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: app_db
-- ------------------------------------------------------
-- Server version	10.0.31-MariaDB-0ubuntu0.16.04.2

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
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `addresses` (
  `fk_business_id` int(11) NOT NULL,
  `street` varchar(45) NOT NULL,
  `city` varchar(45) NOT NULL,
  `province` varchar(45) NOT NULL,
  KEY `fk_addresses_1_idx` (`fk_business_id`),
  CONSTRAINT `fk_addresses_1` FOREIGN KEY (`fk_business_id`) REFERENCES `businesses` (`business_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addresses`
--

LOCK TABLES `addresses` WRITE;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `businesses`
--

DROP TABLE IF EXISTS `businesses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `businesses` (
  `business_id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_user_id` int(11) NOT NULL,
  `business_name` varchar(50) NOT NULL,
  `is_verified` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `business_logo` varchar(255) DEFAULT NULL,
  `business_description` text,
  PRIMARY KEY (`business_id`),
  KEY `fk_businesses_1_idx` (`fk_user_id`),
  CONSTRAINT `fk_businesses_1` FOREIGN KEY (`fk_user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `businesses`
--

LOCK TABLES `businesses` WRITE;
/*!40000 ALTER TABLE `businesses` DISABLE KEYS */;
/*!40000 ALTER TABLE `businesses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_information`
--

DROP TABLE IF EXISTS `contact_information`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_information` (
  `fk_business_id` int(11) NOT NULL,
  `contact_number_1` varchar(20) NOT NULL,
  `contact_number_2` varchar(20) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  KEY `fk_contact_information_1_idx` (`fk_business_id`),
  CONSTRAINT `fk_contact_information_1` FOREIGN KEY (`fk_business_id`) REFERENCES `businesses` (`business_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_information`
--

LOCK TABLES `contact_information` WRITE;
/*!40000 ALTER TABLE `contact_information` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_information` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `offering_categories`
--

DROP TABLE IF EXISTS `offering_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `offering_categories` (
  `offering_category_id` int(11) NOT NULL,
  `offering_category` varchar(70) NOT NULL,
  `fk_offering_type` int(11) NOT NULL,
  PRIMARY KEY (`offering_category_id`),
  KEY `fk_offering_categories_1_idx` (`fk_offering_type`),
  CONSTRAINT `fk_offering_categories_1` FOREIGN KEY (`fk_offering_type`) REFERENCES `offering_types` (`offering_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `offering_categories`
--

LOCK TABLES `offering_categories` WRITE;
/*!40000 ALTER TABLE `offering_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `offering_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `offering_images`
--

DROP TABLE IF EXISTS `offering_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `offering_images` (
  `offering_images_id` int(11) NOT NULL AUTO_INCREMENT,
  `offering_image` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fk_offering_id` int(11) NOT NULL,
  PRIMARY KEY (`offering_images_id`),
  KEY `fk_offering_images_1_idx` (`fk_offering_id`),
  CONSTRAINT `fk_offering_images_1` FOREIGN KEY (`fk_offering_id`) REFERENCES `offerings` (`offering_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `offering_images`
--

LOCK TABLES `offering_images` WRITE;
/*!40000 ALTER TABLE `offering_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `offering_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `offering_types`
--

DROP TABLE IF EXISTS `offering_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `offering_types` (
  `offering_type_id` int(11) NOT NULL,
  `offering_type` varchar(70) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`offering_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `offering_types`
--

LOCK TABLES `offering_types` WRITE;
/*!40000 ALTER TABLE `offering_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `offering_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `offerings`
--

DROP TABLE IF EXISTS `offerings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `offerings` (
  `offering_id` int(11) NOT NULL,
  `fk_business_id` int(11) DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `offering_name` varchar(45) DEFAULT NULL,
  `offering_cost` double DEFAULT NULL,
  `offering_description` text,
  `tags` text,
  `fk_offering_category_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`offering_id`),
  KEY `fk_offerings_1_idx` (`fk_offering_category_id`),
  KEY `fk_offerings_2_idx` (`fk_business_id`),
  CONSTRAINT `fk_offerings_1` FOREIGN KEY (`fk_offering_category_id`) REFERENCES `offering_categories` (`offering_category_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_offerings_2` FOREIGN KEY (`fk_business_id`) REFERENCES `businesses` (`business_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `offerings`
--

LOCK TABLES `offerings` WRITE;
/*!40000 ALTER TABLE `offerings` DISABLE KEYS */;
/*!40000 ALTER TABLE `offerings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `user_type` enum('ADMIN','BUSINESS') NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `account_verified` enum('YES','NO') DEFAULT 'NO',
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-10-05  2:38:12
