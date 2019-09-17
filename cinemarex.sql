-- MySQL dump 10.13  Distrib 5.7.26, for Linux (x86_64)
--
-- Host: localhost    Database: cinemarex
-- ------------------------------------------------------
-- Server version	5.7.26-0ubuntu0.18.04.1

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
-- Table structure for table `admin_role`
--

DROP TABLE IF EXISTS `admin_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `admin_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_role_admin_id_foreign` (`admin_id`),
  CONSTRAINT `admin_role_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_role`
--

LOCK TABLES `admin_role` WRITE;
/*!40000 ALTER TABLE `admin_role` DISABLE KEYS */;
INSERT INTO `admin_role` VALUES (1,1,'76eb3570-8b12-11e9-96a3-93e863f24af6','2019-06-09 20:58:23','2019-06-09 20:58:23'),(2,2,'76f7a0c0-8b12-11e9-9cef-2f7c9ca43077','2019-06-09 20:58:23','2019-06-09 20:58:23');
/*!40000 ALTER TABLE `admin_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admins` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '/img/avatar.png',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES ('76eb3570-8b12-11e9-96a3-93e863f24af6','admin Name','admin@example.com','$2y$10$paEMSVchaE5457AsWL.yP.nz3ZmpmsIWdWH5s/PTM2BHSbna0pMu.','/img/avatar.png','uKBFzphyH9nvkDA8RtuJh0s7MFNrGyyEznhKVZVGq650QIGz3cYwxQK78UAo','2019-06-09 20:58:23','2019-06-09 20:58:23'),('76f7a0c0-8b12-11e9-9cef-2f7c9ca43077','Manager Name','manager@example.com','$2y$10$QP3LXAORX9ADjNVs4Jyro.wfpen5l7.2foCBfh62QJIo9a04xjGz2','/img/avatar.png',NULL,'2019-06-09 20:58:23','2019-06-09 20:58:23');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `backups`
--

DROP TABLE IF EXISTS `backups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `backups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `backups`
--

LOCK TABLES `backups` WRITE;
/*!40000 ALTER TABLE `backups` DISABLE KEYS */;
/*!40000 ALTER TABLE `backups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `braintrees`
--

DROP TABLE IF EXISTS `braintrees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `braintrees` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `plan_id` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `plan_name` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `plan_amount` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `plan_interval` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plan_currency` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `plan_trial` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `braintrees`
--

LOCK TABLES `braintrees` WRITE;
/*!40000 ALTER TABLE `braintrees` DISABLE KEYS */;
/*!40000 ALTER TABLE `braintrees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `casts`
--

DROP TABLE IF EXISTS `casts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `casts` (
  `c_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `credit_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `c_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `c_image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `c_cloud` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`c_id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `casts`
--

LOCK TABLES `casts` WRITE;
/*!40000 ALTER TABLE `casts` DISABLE KEYS */;
INSERT INTO `casts` VALUES (1,'5a28975e0e0a264cbe107d7f','Ryan Reynolds','v8rS3llxAk7BP7vqrChm.jpg','aws','2019-06-09 21:53:16','2019-06-09 21:53:16'),(2,'5a1fafb1c3a3680b8d088f16','Justice Smith','b28pY4bw3rvKtcaSutkC.jpg','aws','2019-06-09 21:53:16','2019-06-09 21:53:16'),(3,'5a1faf95925141033608a00b','Kathryn Newton','7KPT6Zf9rJ4tFIFaQWSt.jpg','aws','2019-06-09 21:53:16','2019-06-09 21:53:16'),(4,'5a7aaa770e0a26021f003fcb','Bill Nighy','9U8RpTaGXwN9J9Fv9I1S.jpg','aws','2019-06-09 21:53:17','2019-06-09 21:53:17'),(5,'5a7aaa8b9251411c5200379f','Ken Watanabe','BR0JdsZX0Gl5szw6pAIu.jpg','aws','2019-06-09 21:53:17','2019-06-09 21:53:17'),(6,'5a7aaa950e0a26020c003ea3','Chris Geere','kHBHSUzDfPSYBkeCBpQR.jpg','aws','2019-06-09 21:53:17','2019-06-09 21:53:17'),(7,'5a7aaa7ec3a3687b6a004263','Suki Waterhouse','sXfa7iBggT5joHb4KRvD.jpg','aws','2019-06-09 21:53:17','2019-06-09 21:53:17'),(8,'5cc76dcb0e0a263743efb31b','Josette Simon','EX2CEGVQUJtaC1qFpEer.jpg','aws','2019-06-09 21:53:17','2019-06-09 21:53:17'),(9,'5b328e2a0e0a263ff600e88e','Yuusuke Kobayashi','r5BGcbYEK3b9Cm0QEm1y.jpg','aws','2019-06-10 01:16:27','2019-06-10 01:16:27'),(10,'5b328e38c3a3685317012009','Rie Takahashi','9SWyYK7faDxW14k9COrF.jpg','aws','2019-06-10 01:16:27','2019-06-10 01:16:27'),(11,'5b328e430e0a26400a010967','Inori Minase','xO1F1DVvHZg6KhoDZQjB.jpg','aws','2019-06-10 01:16:27','2019-06-10 01:16:27'),(12,'5b328e56c3a368533200e1a8','Yumi Uchiyama','AlsnLvPeLkkWqagjTKPP.jpg','aws','2019-06-10 01:16:27','2019-06-10 01:16:27'),(13,'5b328e620e0a2640080100d2','Rie Murakawa','GmYt3Z3v4pyPb7iJzsiY.jpg','aws','2019-06-10 01:16:27','2019-06-10 01:16:27'),(14,'5b328e78c3a3685326012beb','Satomi Arai','FVqXSja2dBipdnjbKB0F.jpg','aws','2019-06-10 01:16:27','2019-06-10 01:16:27'),(15,'5b328e8c0e0a26401a00f120','Takehito Koyasu','Yg1SPTbIKTTb05K6blm5.jpg','aws','2019-06-10 01:16:28','2019-06-10 01:16:28'),(16,'52fe49c8c3a368484e13f7bd','Czinkóczi Zsuzsa','KxNPXVIsPa1bjEtnZm3H.jpg','aws','2019-06-10 04:21:12','2019-06-10 04:21:12'),(17,'52fe49c8c3a368484e13f7c9','Marianna Moór','epaCAIuQ7RhnxIjV6Qxq.jpg','aws','2019-06-10 04:21:13','2019-06-10 04:21:13'),(18,'58acd4e7c3a3682ae6001146','Kyle Chandler','BP6XAZws55rkcvHkvoyS.jpg','aws','2019-06-10 04:37:35','2019-06-10 04:37:35'),(19,'58b4c73b9251410a90002aaf','Vera Farmiga','nVhcQC0VvCAYIK5TzwDd.jpg','aws','2019-06-10 04:37:35','2019-06-10 04:37:35'),(20,'588c01cd925141438c002552','Millie Bobby Brown','pMDf2wsBbHl7XaM5OuIL.jpg','aws','2019-06-10 04:37:35','2019-06-10 04:37:35'),(21,'585d500592514123b900bfbd','Ken Watanabe','JZ5zauoH3Ig4Q1NPcEX1.jpg','aws','2019-06-10 04:37:35','2019-06-10 04:37:35'),(22,'591e2071c3a3687912023ed8','Sally Hawkins','yNxg4IDJkvxYTWerbii7.jpg','aws','2019-06-10 04:37:36','2019-06-10 04:37:36'),(23,'59335dda9251417e1d013d63','Bradley Whitford','B1ud5LnpZW3XGNcNHmHa.jpg','aws','2019-06-10 04:37:36','2019-06-10 04:37:36'),(24,'591e204192514149f302686e','Charles Dance','2m4JBDSgElhCkMr2yLA6.jpg','aws','2019-06-10 04:37:36','2019-06-10 04:37:36'),(25,'593a02fdc3a36823710044ac','Zhang Ziyi','GmjZQc9GwXFIMznlkomG.jpg','aws','2019-06-10 04:37:36','2019-06-10 04:37:36'),(26,'58a923d092514173fa0008c2','Kay Kay Menon','trvU6ezZDSsXz8UdTtUQ.jpg','aws','2019-06-21 14:49:46','2019-06-21 14:49:46'),(27,'58a9243bc3a3680b8d0008d1','Rana Daggubati','QKI74MJzcVa6NdEhABSr.jpg','aws','2019-06-21 14:49:46','2019-06-21 14:49:46'),(28,'58a92413c3a3680bed000879','Atul Kulkarni','J5FlAaMJeEA2MdPrDNQD.jpg','aws','2019-06-21 14:49:46','2019-06-21 14:49:46'),(29,'5926b782c3a36877df022b8f','Taapsee Pannu','LP9lNzb5bVAyuNi5BUB3.jpg','aws','2019-06-21 14:49:47','2019-06-21 14:49:47'),(30,'58ad90c1c3a3682fc00056e3','Amitabh Bachchan','OJGEQj73qdbVbDSyvMeU.jpg','aws','2019-06-21 14:49:47','2019-06-21 14:49:47'),(31,'58a92497c3a3680bc200096a','Om Puri','OPzaGazSR72Ozjgoh0wt.jpg','aws','2019-06-21 14:49:47','2019-06-21 14:49:47'),(32,'58a924d4c3a3680bd40008ec','Milind Gunaji','23gqNy2SoJFhseSFwvET.jpg','aws','2019-06-21 14:49:48','2019-06-21 14:49:48'),(33,'597331c9c3a3681d7000237b','Rahul Singh','FGGRwtBnB0bWd0bl0lBm.jpg','aws','2019-06-21 14:49:48','2019-06-21 14:49:48'),(34,'577a78b8c3a368770400058f','Tom Hanks','EiCMcnp2OR9fUqaSFKPB.jpg','aws','2019-06-28 08:32:16','2019-06-28 08:32:16'),(35,'5c6d12ad92514129a2035517','Tim Allen','imWh1Pfi4dvyi8NZa6B9.jpg','aws','2019-06-28 08:32:16','2019-06-28 08:32:16'),(36,'5c6d12aa9251417df40bff9f','Annie Potts','ps5ckBbecJX3NKKV7sch.jpg','aws','2019-06-28 08:32:16','2019-06-28 08:32:16'),(37,'5c6d12ac9251412fc40f59a2','Joan Cusack','TGC8BNhGWIXHmzL2Rf1N.jpg','aws','2019-06-28 08:32:16','2019-06-28 08:32:16'),(38,'5c6d12af92514129d503c75f','Blake Clark','IYea3SGEPSJD615goGmm.jpg','aws','2019-06-28 08:32:16','2019-06-28 08:32:16'),(39,'5c6d12ac0e0a262c999fb6d5','Wallace Shawn','8kBxk9CfBvpdmd1XAhzl.jpg','aws','2019-06-28 08:32:16','2019-06-28 08:32:16'),(40,'5c6d12ad0e0a260e9e9b90e3','John Ratzenberger','pXt3FC1Fxh76CBSJyz1m.jpg','aws','2019-06-28 08:32:16','2019-06-28 08:32:16'),(41,'5c6d12aa9251412fc40f599b','Keanu Reeves','hhAKg9LPOT5aNHTdwmzV.jpg','aws','2019-06-28 08:32:17','2019-06-28 08:32:17'),(42,'5b5cef40925141522701ca2f','Josephine Langford','EAm0LnW3x63j8GUiSZOj.jpg','aws','2019-06-28 16:32:52','2019-06-28 16:32:52'),(43,'5b5cef1a0e0a262e8d01fa93','Hero Fiennes Tiffin','aeoboycy6rmfUR80XSfR.jpg','aws','2019-06-28 16:32:52','2019-06-28 16:32:52'),(44,'5bd353bb9251410387003cde','Selma Blair','sc36hSSDBzUXRlhOs8kW.jpg','aws','2019-06-28 16:32:52','2019-06-28 16:32:52'),(45,'5b5cef0e0e0a262e9f01d7d5','Jennifer Beals','y4tVXJgUX6NLePO9gzx6.jpg','aws','2019-06-28 16:32:53','2019-06-28 16:32:53'),(46,'5b5cef23925141522401d1ee','Peter Gallagher','udGl21P2dTRHrO2vbVpe.jpg','aws','2019-06-28 16:32:53','2019-06-28 16:32:53'),(47,'5b5cef72925141521a01e6bf','Samuel Larsen','r0BRZn9ZvDi6g8OvgM6r.jpg','aws','2019-06-28 16:32:53','2019-06-28 16:32:53'),(48,'5b5cef57c3a368422701e868','Dylan Arnold','PtoP3i7jIu9ApjYX79wz.jpg','aws','2019-06-28 16:32:53','2019-06-28 16:32:53');
/*!40000 ALTER TABLE `casts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `casts_rules`
--

DROP TABLE IF EXISTS `casts_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `casts_rules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `casts_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `casts_movies` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `casts_series` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `casts_rules`
--

LOCK TABLES `casts_rules` WRITE;
/*!40000 ALTER TABLE `casts_rules` DISABLE KEYS */;
INSERT INTO `casts_rules` VALUES (1,'5a28975e0e0a264cbe107d7f','216bf6a0-8b1a-11e9-acae-331e448e27af',NULL,'2019-06-09 21:53:16','2019-06-09 21:53:16'),(2,'5a1fafb1c3a3680b8d088f16','216bf6a0-8b1a-11e9-acae-331e448e27af',NULL,'2019-06-09 21:53:16','2019-06-09 21:53:16'),(3,'5a1faf95925141033608a00b','216bf6a0-8b1a-11e9-acae-331e448e27af',NULL,'2019-06-09 21:53:16','2019-06-09 21:53:16'),(4,'5a7aaa770e0a26021f003fcb','216bf6a0-8b1a-11e9-acae-331e448e27af',NULL,'2019-06-09 21:53:17','2019-06-09 21:53:17'),(5,'5a7aaa8b9251411c5200379f','216bf6a0-8b1a-11e9-acae-331e448e27af',NULL,'2019-06-09 21:53:17','2019-06-09 21:53:17'),(6,'5a7aaa950e0a26020c003ea3','216bf6a0-8b1a-11e9-acae-331e448e27af',NULL,'2019-06-09 21:53:17','2019-06-09 21:53:17'),(7,'5a7aaa7ec3a3687b6a004263','216bf6a0-8b1a-11e9-acae-331e448e27af',NULL,'2019-06-09 21:53:17','2019-06-09 21:53:17'),(8,'5cc76dcb0e0a263743efb31b','216bf6a0-8b1a-11e9-acae-331e448e27af',NULL,'2019-06-09 21:53:17','2019-06-09 21:53:17'),(9,'5a28975e0e0a264cbe107d7f','fea9b480-8b1e-11e9-9ceb-9f4a7ae03b04',NULL,'2019-06-09 22:28:05','2019-06-09 22:28:05'),(10,'5a1fafb1c3a3680b8d088f16','fea9b480-8b1e-11e9-9ceb-9f4a7ae03b04',NULL,'2019-06-09 22:28:05','2019-06-09 22:28:05'),(11,'5a1faf95925141033608a00b','fea9b480-8b1e-11e9-9ceb-9f4a7ae03b04',NULL,'2019-06-09 22:28:06','2019-06-09 22:28:06'),(12,'5a7aaa770e0a26021f003fcb','fea9b480-8b1e-11e9-9ceb-9f4a7ae03b04',NULL,'2019-06-09 22:28:06','2019-06-09 22:28:06'),(13,'5a7aaa8b9251411c5200379f','fea9b480-8b1e-11e9-9ceb-9f4a7ae03b04',NULL,'2019-06-09 22:28:06','2019-06-09 22:28:06'),(14,'5a7aaa950e0a26020c003ea3','fea9b480-8b1e-11e9-9ceb-9f4a7ae03b04',NULL,'2019-06-09 22:28:06','2019-06-09 22:28:06'),(15,'5a7aaa7ec3a3687b6a004263','fea9b480-8b1e-11e9-9ceb-9f4a7ae03b04',NULL,'2019-06-09 22:28:06','2019-06-09 22:28:06'),(16,'5cc76dcb0e0a263743efb31b','fea9b480-8b1e-11e9-9ceb-9f4a7ae03b04',NULL,'2019-06-09 22:28:06','2019-06-09 22:28:06'),(17,'5a28975e0e0a264cbe107d7f','6d233610-8b1f-11e9-b9a6-a5fe57ec1781',NULL,'2019-06-09 22:31:11','2019-06-09 22:31:11'),(18,'5a1fafb1c3a3680b8d088f16','6d233610-8b1f-11e9-b9a6-a5fe57ec1781',NULL,'2019-06-09 22:31:11','2019-06-09 22:31:11'),(19,'5a1faf95925141033608a00b','6d233610-8b1f-11e9-b9a6-a5fe57ec1781',NULL,'2019-06-09 22:31:11','2019-06-09 22:31:11'),(20,'5a7aaa770e0a26021f003fcb','6d233610-8b1f-11e9-b9a6-a5fe57ec1781',NULL,'2019-06-09 22:31:11','2019-06-09 22:31:11'),(21,'5a7aaa8b9251411c5200379f','6d233610-8b1f-11e9-b9a6-a5fe57ec1781',NULL,'2019-06-09 22:31:11','2019-06-09 22:31:11'),(22,'5a7aaa950e0a26020c003ea3','6d233610-8b1f-11e9-b9a6-a5fe57ec1781',NULL,'2019-06-09 22:31:11','2019-06-09 22:31:11'),(23,'5a7aaa7ec3a3687b6a004263','6d233610-8b1f-11e9-b9a6-a5fe57ec1781',NULL,'2019-06-09 22:31:12','2019-06-09 22:31:12'),(24,'5cc76dcb0e0a263743efb31b','6d233610-8b1f-11e9-b9a6-a5fe57ec1781',NULL,'2019-06-09 22:31:12','2019-06-09 22:31:12'),(25,'5a28975e0e0a264cbe107d7f','51aab8c0-8b20-11e9-8779-bffd00a227ee',NULL,'2019-06-09 22:37:34','2019-06-09 22:37:34'),(26,'5a1fafb1c3a3680b8d088f16','51aab8c0-8b20-11e9-8779-bffd00a227ee',NULL,'2019-06-09 22:37:34','2019-06-09 22:37:34'),(27,'5a1faf95925141033608a00b','51aab8c0-8b20-11e9-8779-bffd00a227ee',NULL,'2019-06-09 22:37:34','2019-06-09 22:37:34'),(28,'5a7aaa770e0a26021f003fcb','51aab8c0-8b20-11e9-8779-bffd00a227ee',NULL,'2019-06-09 22:37:35','2019-06-09 22:37:35'),(29,'5a7aaa8b9251411c5200379f','51aab8c0-8b20-11e9-8779-bffd00a227ee',NULL,'2019-06-09 22:37:35','2019-06-09 22:37:35'),(30,'5a7aaa950e0a26020c003ea3','51aab8c0-8b20-11e9-8779-bffd00a227ee',NULL,'2019-06-09 22:37:35','2019-06-09 22:37:35'),(31,'5a7aaa7ec3a3687b6a004263','51aab8c0-8b20-11e9-8779-bffd00a227ee',NULL,'2019-06-09 22:37:35','2019-06-09 22:37:35'),(32,'5cc76dcb0e0a263743efb31b','51aab8c0-8b20-11e9-8779-bffd00a227ee',NULL,'2019-06-09 22:37:35','2019-06-09 22:37:35'),(33,'5a28975e0e0a264cbe107d7f','1aa43470-8b21-11e9-bd69-e9613446b9dd',NULL,'2019-06-09 22:43:11','2019-06-09 22:43:11'),(34,'5a1fafb1c3a3680b8d088f16','1aa43470-8b21-11e9-bd69-e9613446b9dd',NULL,'2019-06-09 22:43:11','2019-06-09 22:43:11'),(35,'5a1faf95925141033608a00b','1aa43470-8b21-11e9-bd69-e9613446b9dd',NULL,'2019-06-09 22:43:12','2019-06-09 22:43:12'),(36,'5a7aaa770e0a26021f003fcb','1aa43470-8b21-11e9-bd69-e9613446b9dd',NULL,'2019-06-09 22:43:12','2019-06-09 22:43:12'),(37,'5a7aaa8b9251411c5200379f','1aa43470-8b21-11e9-bd69-e9613446b9dd',NULL,'2019-06-09 22:43:12','2019-06-09 22:43:12'),(38,'5a7aaa950e0a26020c003ea3','1aa43470-8b21-11e9-bd69-e9613446b9dd',NULL,'2019-06-09 22:43:12','2019-06-09 22:43:12'),(39,'5a7aaa7ec3a3687b6a004263','1aa43470-8b21-11e9-bd69-e9613446b9dd',NULL,'2019-06-09 22:43:12','2019-06-09 22:43:12'),(40,'5cc76dcb0e0a263743efb31b','1aa43470-8b21-11e9-bd69-e9613446b9dd',NULL,'2019-06-09 22:43:12','2019-06-09 22:43:12'),(41,'5a28975e0e0a264cbe107d7f','dffa1b90-8b21-11e9-ae2f-9dcddd52cbd3',NULL,'2019-06-09 22:48:42','2019-06-09 22:48:42'),(42,'5a1fafb1c3a3680b8d088f16','dffa1b90-8b21-11e9-ae2f-9dcddd52cbd3',NULL,'2019-06-09 22:48:42','2019-06-09 22:48:42'),(43,'5a1faf95925141033608a00b','dffa1b90-8b21-11e9-ae2f-9dcddd52cbd3',NULL,'2019-06-09 22:48:43','2019-06-09 22:48:43'),(44,'5a7aaa770e0a26021f003fcb','dffa1b90-8b21-11e9-ae2f-9dcddd52cbd3',NULL,'2019-06-09 22:48:43','2019-06-09 22:48:43'),(45,'5a7aaa8b9251411c5200379f','dffa1b90-8b21-11e9-ae2f-9dcddd52cbd3',NULL,'2019-06-09 22:48:43','2019-06-09 22:48:43'),(46,'5a7aaa950e0a26020c003ea3','dffa1b90-8b21-11e9-ae2f-9dcddd52cbd3',NULL,'2019-06-09 22:48:43','2019-06-09 22:48:43'),(47,'5a7aaa7ec3a3687b6a004263','dffa1b90-8b21-11e9-ae2f-9dcddd52cbd3',NULL,'2019-06-09 22:48:43','2019-06-09 22:48:43'),(48,'5cc76dcb0e0a263743efb31b','dffa1b90-8b21-11e9-ae2f-9dcddd52cbd3',NULL,'2019-06-09 22:48:43','2019-06-09 22:48:43'),(49,'5a28975e0e0a264cbe107d7f','edd51f90-8b22-11e9-b9ec-6d07f48406d8',NULL,'2019-06-09 22:56:15','2019-06-09 22:56:15'),(50,'5a1fafb1c3a3680b8d088f16','edd51f90-8b22-11e9-b9ec-6d07f48406d8',NULL,'2019-06-09 22:56:15','2019-06-09 22:56:15'),(51,'5a1faf95925141033608a00b','edd51f90-8b22-11e9-b9ec-6d07f48406d8',NULL,'2019-06-09 22:56:15','2019-06-09 22:56:15'),(52,'5a7aaa770e0a26021f003fcb','edd51f90-8b22-11e9-b9ec-6d07f48406d8',NULL,'2019-06-09 22:56:15','2019-06-09 22:56:15'),(53,'5a7aaa8b9251411c5200379f','edd51f90-8b22-11e9-b9ec-6d07f48406d8',NULL,'2019-06-09 22:56:16','2019-06-09 22:56:16'),(54,'5a7aaa950e0a26020c003ea3','edd51f90-8b22-11e9-b9ec-6d07f48406d8',NULL,'2019-06-09 22:56:16','2019-06-09 22:56:16'),(55,'5a7aaa7ec3a3687b6a004263','edd51f90-8b22-11e9-b9ec-6d07f48406d8',NULL,'2019-06-09 22:56:16','2019-06-09 22:56:16'),(56,'5cc76dcb0e0a263743efb31b','edd51f90-8b22-11e9-b9ec-6d07f48406d8',NULL,'2019-06-09 22:56:16','2019-06-09 22:56:16'),(57,'5a28975e0e0a264cbe107d7f','2029e590-8b25-11e9-b06e-adca20003bd5',NULL,'2019-06-09 23:11:58','2019-06-09 23:11:58'),(58,'5a1fafb1c3a3680b8d088f16','2029e590-8b25-11e9-b06e-adca20003bd5',NULL,'2019-06-09 23:11:59','2019-06-09 23:11:59'),(59,'5a1faf95925141033608a00b','2029e590-8b25-11e9-b06e-adca20003bd5',NULL,'2019-06-09 23:11:59','2019-06-09 23:11:59'),(60,'5a7aaa770e0a26021f003fcb','2029e590-8b25-11e9-b06e-adca20003bd5',NULL,'2019-06-09 23:11:59','2019-06-09 23:11:59'),(61,'5a7aaa8b9251411c5200379f','2029e590-8b25-11e9-b06e-adca20003bd5',NULL,'2019-06-09 23:11:59','2019-06-09 23:11:59'),(62,'5a7aaa950e0a26020c003ea3','2029e590-8b25-11e9-b06e-adca20003bd5',NULL,'2019-06-09 23:11:59','2019-06-09 23:11:59'),(63,'5a7aaa7ec3a3687b6a004263','2029e590-8b25-11e9-b06e-adca20003bd5',NULL,'2019-06-09 23:11:59','2019-06-09 23:11:59'),(64,'5cc76dcb0e0a263743efb31b','2029e590-8b25-11e9-b06e-adca20003bd5',NULL,'2019-06-09 23:11:59','2019-06-09 23:11:59'),(65,'5b328e2a0e0a263ff600e88e','83c90d10-8b36-11e9-89d0-f5f7ff29b9c1',NULL,'2019-06-10 01:16:27','2019-06-10 01:16:27'),(66,'5b328e38c3a3685317012009','83c90d10-8b36-11e9-89d0-f5f7ff29b9c1',NULL,'2019-06-10 01:16:27','2019-06-10 01:16:27'),(67,'5b328e430e0a26400a010967','83c90d10-8b36-11e9-89d0-f5f7ff29b9c1',NULL,'2019-06-10 01:16:27','2019-06-10 01:16:27'),(68,'5b328e56c3a368533200e1a8','83c90d10-8b36-11e9-89d0-f5f7ff29b9c1',NULL,'2019-06-10 01:16:27','2019-06-10 01:16:27'),(69,'5b328e620e0a2640080100d2','83c90d10-8b36-11e9-89d0-f5f7ff29b9c1',NULL,'2019-06-10 01:16:27','2019-06-10 01:16:27'),(70,'5b328e78c3a3685326012beb','83c90d10-8b36-11e9-89d0-f5f7ff29b9c1',NULL,'2019-06-10 01:16:27','2019-06-10 01:16:27'),(71,'5b328e8c0e0a26401a00f120','83c90d10-8b36-11e9-89d0-f5f7ff29b9c1',NULL,'2019-06-10 01:16:28','2019-06-10 01:16:28'),(72,'52fe49c8c3a368484e13f7bd','53226a80-8b50-11e9-b30d-6be2a69e2e2a',NULL,'2019-06-10 04:21:12','2019-06-10 04:21:12'),(73,'52fe49c8c3a368484e13f7c9','53226a80-8b50-11e9-b30d-6be2a69e2e2a',NULL,'2019-06-10 04:21:13','2019-06-10 04:21:13'),(74,'58acd4e7c3a3682ae6001146','9cfea820-8b52-11e9-a40f-39e9f617210b',NULL,'2019-06-10 04:37:35','2019-06-10 04:37:35'),(75,'58b4c73b9251410a90002aaf','9cfea820-8b52-11e9-a40f-39e9f617210b',NULL,'2019-06-10 04:37:35','2019-06-10 04:37:35'),(76,'588c01cd925141438c002552','9cfea820-8b52-11e9-a40f-39e9f617210b',NULL,'2019-06-10 04:37:35','2019-06-10 04:37:35'),(77,'585d500592514123b900bfbd','9cfea820-8b52-11e9-a40f-39e9f617210b',NULL,'2019-06-10 04:37:35','2019-06-10 04:37:35'),(78,'591e2071c3a3687912023ed8','9cfea820-8b52-11e9-a40f-39e9f617210b',NULL,'2019-06-10 04:37:36','2019-06-10 04:37:36'),(79,'59335dda9251417e1d013d63','9cfea820-8b52-11e9-a40f-39e9f617210b',NULL,'2019-06-10 04:37:36','2019-06-10 04:37:36'),(80,'591e204192514149f302686e','9cfea820-8b52-11e9-a40f-39e9f617210b',NULL,'2019-06-10 04:37:36','2019-06-10 04:37:36'),(81,'593a02fdc3a36823710044ac','9cfea820-8b52-11e9-a40f-39e9f617210b',NULL,'2019-06-10 04:37:36','2019-06-10 04:37:36'),(82,'58a923d092514173fa0008c2','f4860a50-944c-11e9-b37c-4541177f1c1d',NULL,'2019-06-21 14:49:46','2019-06-21 14:49:46'),(83,'58a9243bc3a3680b8d0008d1','f4860a50-944c-11e9-b37c-4541177f1c1d',NULL,'2019-06-21 14:49:46','2019-06-21 14:49:46'),(84,'58a92413c3a3680bed000879','f4860a50-944c-11e9-b37c-4541177f1c1d',NULL,'2019-06-21 14:49:46','2019-06-21 14:49:46'),(85,'5926b782c3a36877df022b8f','f4860a50-944c-11e9-b37c-4541177f1c1d',NULL,'2019-06-21 14:49:47','2019-06-21 14:49:47'),(86,'58ad90c1c3a3682fc00056e3','f4860a50-944c-11e9-b37c-4541177f1c1d',NULL,'2019-06-21 14:49:47','2019-06-21 14:49:47'),(87,'58a92497c3a3680bc200096a','f4860a50-944c-11e9-b37c-4541177f1c1d',NULL,'2019-06-21 14:49:47','2019-06-21 14:49:47'),(88,'58a924d4c3a3680bd40008ec','f4860a50-944c-11e9-b37c-4541177f1c1d',NULL,'2019-06-21 14:49:48','2019-06-21 14:49:48'),(89,'597331c9c3a3681d7000237b','f4860a50-944c-11e9-b37c-4541177f1c1d',NULL,'2019-06-21 14:49:48','2019-06-21 14:49:48'),(90,'577a78b8c3a368770400058f','61738ac0-9998-11e9-a1ab-cd35a673069d',NULL,'2019-06-28 08:32:16','2019-06-28 08:32:16'),(91,'5c6d12ad92514129a2035517','61738ac0-9998-11e9-a1ab-cd35a673069d',NULL,'2019-06-28 08:32:16','2019-06-28 08:32:16'),(92,'5c6d12aa9251417df40bff9f','61738ac0-9998-11e9-a1ab-cd35a673069d',NULL,'2019-06-28 08:32:16','2019-06-28 08:32:16'),(93,'5c6d12ac9251412fc40f59a2','61738ac0-9998-11e9-a1ab-cd35a673069d',NULL,'2019-06-28 08:32:16','2019-06-28 08:32:16'),(94,'5c6d12af92514129d503c75f','61738ac0-9998-11e9-a1ab-cd35a673069d',NULL,'2019-06-28 08:32:16','2019-06-28 08:32:16'),(95,'5c6d12ac0e0a262c999fb6d5','61738ac0-9998-11e9-a1ab-cd35a673069d',NULL,'2019-06-28 08:32:16','2019-06-28 08:32:16'),(96,'5c6d12ad0e0a260e9e9b90e3','61738ac0-9998-11e9-a1ab-cd35a673069d',NULL,'2019-06-28 08:32:16','2019-06-28 08:32:16'),(97,'5c6d12aa9251412fc40f599b','61738ac0-9998-11e9-a1ab-cd35a673069d',NULL,'2019-06-28 08:32:17','2019-06-28 08:32:17'),(98,'5b5cef40925141522701ca2f','8521d3e0-99db-11e9-a7bc-79acb8cf03ea',NULL,'2019-06-28 16:32:52','2019-06-28 16:32:52'),(99,'5b5cef1a0e0a262e8d01fa93','8521d3e0-99db-11e9-a7bc-79acb8cf03ea',NULL,'2019-06-28 16:32:52','2019-06-28 16:32:52'),(100,'5bd353bb9251410387003cde','8521d3e0-99db-11e9-a7bc-79acb8cf03ea',NULL,'2019-06-28 16:32:52','2019-06-28 16:32:52'),(101,'5b5cef0e0e0a262e9f01d7d5','8521d3e0-99db-11e9-a7bc-79acb8cf03ea',NULL,'2019-06-28 16:32:53','2019-06-28 16:32:53'),(102,'5b5cef23925141522401d1ee','8521d3e0-99db-11e9-a7bc-79acb8cf03ea',NULL,'2019-06-28 16:32:53','2019-06-28 16:32:53'),(103,'5b5cef72925141521a01e6bf','8521d3e0-99db-11e9-a7bc-79acb8cf03ea',NULL,'2019-06-28 16:32:53','2019-06-28 16:32:53'),(104,'5b5cef57c3a368422701e868','8521d3e0-99db-11e9-a7bc-79acb8cf03ea',NULL,'2019-06-28 16:32:53','2019-06-28 16:32:53'),(105,'5b5cef40925141522701ca2f','8bd00ad0-99de-11e9-b933-35f071c29f15',NULL,'2019-06-28 16:54:32','2019-06-28 16:54:32'),(106,'5b5cef1a0e0a262e8d01fa93','8bd00ad0-99de-11e9-b933-35f071c29f15',NULL,'2019-06-28 16:54:32','2019-06-28 16:54:32'),(107,'5bd353bb9251410387003cde','8bd00ad0-99de-11e9-b933-35f071c29f15',NULL,'2019-06-28 16:54:32','2019-06-28 16:54:32'),(108,'5b5cef0e0e0a262e9f01d7d5','8bd00ad0-99de-11e9-b933-35f071c29f15',NULL,'2019-06-28 16:54:32','2019-06-28 16:54:32'),(109,'5b5cef23925141522401d1ee','8bd00ad0-99de-11e9-b933-35f071c29f15',NULL,'2019-06-28 16:54:32','2019-06-28 16:54:32'),(110,'5b5cef72925141521a01e6bf','8bd00ad0-99de-11e9-b933-35f071c29f15',NULL,'2019-06-28 16:54:32','2019-06-28 16:54:32'),(111,'5b5cef57c3a368422701e868','8bd00ad0-99de-11e9-b933-35f071c29f15',NULL,'2019-06-28 16:54:32','2019-06-28 16:54:32');
/*!40000 ALTER TABLE `casts_rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kind` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Action','movie','/storage/categories/CEgzBTGoutSEfHCGL4vO.jpg',0,'2019-06-09 21:48:46','2019-06-09 21:48:46'),(2,'jh','live','/storage/categories/mCSUJjlSLHUyK74dnbfp.jpg',0,'2019-06-10 01:20:48','2019-06-10 01:20:48');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `collection_lists`
--

DROP TABLE IF EXISTS `collection_lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collection_lists` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `movie_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `series_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `collection_id` int(10) unsigned NOT NULL,
  `uid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `collection_lists_movie_id_foreign` (`movie_id`),
  KEY `collection_lists_series_id_foreign` (`series_id`),
  KEY `collection_lists_collection_id_foreign` (`collection_id`),
  CONSTRAINT `collection_lists_collection_id_foreign` FOREIGN KEY (`collection_id`) REFERENCES `collections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `collection_lists_movie_id_foreign` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`m_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `collection_lists_series_id_foreign` FOREIGN KEY (`series_id`) REFERENCES `series` (`t_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `collection_lists`
--

LOCK TABLES `collection_lists` WRITE;
/*!40000 ALTER TABLE `collection_lists` DISABLE KEYS */;
/*!40000 ALTER TABLE `collection_lists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `collections`
--

DROP TABLE IF EXISTS `collections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collections` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `collections`
--

LOCK TABLES `collections` WRITE;
/*!40000 ALTER TABLE `collections` DISABLE KEYS */;
/*!40000 ALTER TABLE `collections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `episodes`
--

DROP TABLE IF EXISTS `episodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `episodes` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `series_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `episode_number` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `overview` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `backdrop` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `season_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `show` tinyint(1) NOT NULL DEFAULT '0',
  `cloud` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `episodes_series_id_foreign` (`series_id`),
  CONSTRAINT `episodes_series_id_foreign` FOREIGN KEY (`series_id`) REFERENCES `series` (`t_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `episodes`
--

LOCK TABLES `episodes` WRITE;
/*!40000 ALTER TABLE `episodes` DISABLE KEYS */;
/*!40000 ALTER TABLE `episodes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `likes`
--

DROP TABLE IF EXISTS `likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `likes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `movie_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `series_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `likes_movie_id_foreign` (`movie_id`),
  KEY `likes_series_id_foreign` (`series_id`),
  KEY `likes_uid_foreign` (`uid`),
  CONSTRAINT `likes_movie_id_foreign` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`m_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `likes_series_id_foreign` FOREIGN KEY (`series_id`) REFERENCES `series` (`t_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `likes_uid_foreign` FOREIGN KEY (`uid`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `likes`
--

LOCK TABLES `likes` WRITE;
/*!40000 ALTER TABLE `likes` DISABLE KEYS */;
/*!40000 ALTER TABLE `likes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `location_logs`
--

DROP TABLE IF EXISTS `location_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `location_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_code` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isp` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `confirm_hash` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_logs_uid_foreign` (`uid`),
  CONSTRAINT `location_logs_uid_foreign` FOREIGN KEY (`uid`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `location_logs`
--

LOCK TABLES `location_logs` WRITE;
/*!40000 ALTER TABLE `location_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `location_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `message_type` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_title` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_info` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_image` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2016_06_01_000001_create_oauth_auth_codes_table',1),(4,'2016_06_01_000002_create_oauth_access_tokens_table',1),(5,'2016_06_01_000003_create_oauth_refresh_tokens_table',1),(6,'2016_06_01_000004_create_oauth_clients_table',1),(7,'2016_06_01_000005_create_oauth_personal_access_clients_table',1),(8,'2017_08_08_071505_create_movies_table',1),(9,'2017_08_08_071518_create_series_table',1),(10,'2017_08_08_071535_create_episodes_table',1),(11,'2017_08_08_071606_create_messages_table',1),(12,'2017_08_08_071623_create_reports_table',1),(13,'2017_08_08_071633_create_tops_table',1),(14,'2017_08_08_072816_create_collections_table',1),(15,'2017_08_08_073428_create_collection_lists_table',1),(16,'2017_08_08_081525_create_casts_table',1),(17,'2017_08_08_081907_create_casts_rules_table',1),(18,'2017_08_29_111910_create_likes_table',1),(19,'2017_09_03_101540_create_subtitles_table',1),(20,'2017_09_15_125358_create_admins_table',1),(21,'2017_09_23_095647_create_roles_table',1),(22,'2017_09_23_095819_create_admin_role_table',1),(23,'2017_09_25_140048_create_videos_table',1),(24,'2017_10_19_152803_create-backups-table',1),(25,'2017_10_23_105047_create_subscriptions_table',1),(26,'2017_11_09_162219_create_recenlty_watcheds_table',1),(27,'2017_11_18_130344_create_tvs_table',1),(28,'2017_11_30_181331_create_plugins_table',1),(29,'2018_01_15_184527_create_siteinfos_table',1),(30,'2018_01_31_144058_create_tmdbs_table',1),(31,'2018_02_02_111302_create_transcoders_table',1),(32,'2018_02_12_123619_create_braintrees_table',1),(33,'2018_04_09_172854_create_supports_table',1),(34,'2018_04_09_173816_create_support_responses_table',1),(35,'2018_07_13_172459_create_location_logs_table',1),(36,'2018_10_25_165021_create_categories_table',1),(37,'2019_02_14_005451_alter_table_oauth_access_tokens',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movies`
--

DROP TABLE IF EXISTS `movies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `movies` (
  `m_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `m_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `m_desc` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `m_genre` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `m_year` int(11) NOT NULL,
  `m_age` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `m_rate` double(8,2) NOT NULL,
  `m_runtime` double(8,2) NOT NULL DEFAULT '0.00',
  `m_youtube` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `m_cloud` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `m_poster` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `m_backdrop` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `m_category` int(11) NOT NULL,
  `show` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`m_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movies`
--

LOCK TABLES `movies` WRITE;
/*!40000 ALTER TABLE `movies` DISABLE KEYS */;
INSERT INTO `movies` VALUES ('2029e590-8b25-11e9-b06e-adca20003bd5','Pokémon Detective Pikachu','In a world where people collect pocket-size monsters (Pokémon) to do battle, a boy comes across an intelligent monster who seeks to be a detective.','Mystery, Family, Comedy, Science Fiction',2019,'PG-13',7.00,105.00,'https://www.youtube.com/watch?v=1roy4o4tqQM','aws','wDFSMNsr0dhIjYCU6S4H.jpg','xFTqEBJnNiE2kvaINARh.jpg',1,1,'2019-06-09 23:11:58','2019-06-09 23:12:48'),('53226a80-8b50-11e9-b30d-6be2a69e2e2a','Nobody\'s Daughter','An orphan girl suffers abuse from her adoptive parents.','Drama',1976,'PG',6.80,83.00,NULL,'aws','ZiAEhxtcERthU93vEXde.jpg','pxK9sJk2bXajeagQRteb.jpg',1,1,'2019-06-10 04:21:11','2019-06-10 04:32:59'),('61738ac0-9998-11e9-a1ab-cd35a673069d','Toy Story 4','Woody has always been confident about his place in the world and that his priority is taking care of his kid, whether that\'s Andy or Bonnie. But when Bonnie adds a reluctant new toy called \"Forky\" to her room, a road trip adventure alongside old and new friends will show Woody how big the world can be for a toy.','Adventure, Animation, Comedy, Family',2019,'PG-13',7.60,100.00,'https://www.youtube.com/watch?v=LDXYRzerjzU','aws','QFefU0uHv4eD4f4TZM3E.jpg','OubGboeDPinoCEiz0elV.jpg',1,1,'2019-06-28 08:32:16','2019-06-28 10:33:09'),('83c90d10-8b36-11e9-89d0-f5f7ff29b9c1','Re: Zero kara Hajimeru Isekai Seikatsu - Memory Sn','Subaru and friends finally get a moment of peace, and Subaru goes on a certain secret mission that he must not let anyone find out about! However, even though Subaru is wearing a disguise, Petra and other children of the village immediately figure out who he is. Now that his mission was exposed within five seconds of it starting, what will happen with Subaru\'s \"date course\" with Emilia?','Animation, Adventure',2018,'PG-13',5.10,75.00,'https://www.youtube.com/watch?v=Vg40gSVVI2M','aws','hwYq9u2XBrpVG4qRaykQ.jpg','LgcOR07HhJ0DyXUMmoPv.jpg',1,1,'2019-06-10 01:16:26','2019-06-10 01:20:13'),('8bd00ad0-99de-11e9-b933-35f071c29f15','After','A young woman falls for a guy with a dark secret and the two embark on a rocky relationship.','Drama, Romance',2019,'PG-13',5.80,106.00,'https://www.youtube.com/watch?v=2ZAdcWHuCmY','aws','fMqy1e1jbPOTl9BupyT5.jpg','zw9wEii7E8lrZA9WXmd5.jpg',1,1,'2019-06-28 16:54:31','2019-06-28 18:27:29'),('9cfea820-8b52-11e9-a40f-39e9f617210b','Godzilla: King of the Monsters','The new story follows the heroic efforts of the crypto-zoological agency Monarch as its members face off against a battery of god-sized monsters, including the mighty Godzilla, who collides with Mothra, Rodan, and his ultimate nemesis, the three-headed King Ghidorah. When these ancient super-species—thought to be mere myths—rise again, they all vie for supremacy, leaving humanity’s very existence hanging in the balance.','Science Fiction, Thriller, Action',2019,'PG-13',6.40,132.00,'https://www.youtube.com/watch?v=wVDtmouV9kM','aws','XMcx2rWBsPdQSdr1Lacu.jpg','CK3UmmrCLkaj8pfnbKmV.jpg',1,1,'2019-06-10 04:37:34','2019-06-10 04:55:53'),('f4860a50-944c-11e9-b37c-4541177f1c1d','The Ghazi Attack','India’s first underwater war film tries to decode the mystery behind the sinking of Pakistani submarine PNS Ghazi during the Indo-Pak war of 1971.','Action, War, Thriller',2017,'PG-13',7.00,126.00,NULL,'aws','8IwdHkCwQT54RKfDeeWZ.jpg','pbTSSDfqzeZH09m8GcJO.jpg',1,0,'2019-06-21 14:49:45','2019-06-21 14:49:45');
/*!40000 ALTER TABLE `movies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_access_tokens`
--

DROP TABLE IF EXISTS `oauth_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_access_tokens`
--

LOCK TABLES `oauth_access_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_access_tokens` DISABLE KEYS */;
INSERT INTO `oauth_access_tokens` VALUES ('1fb86306bb0df5e2e554882ba887f575cde29cc3bf61f5c60a56eddd43a4d20499c82a1d00bbf874',2,NULL,'[]',0,'2019-06-10 15:11:55','2019-06-10 15:11:55','2019-09-08 18:11:55','39495b70-8bab-11e9-959b-c5b39ccdb584'),('2240ea58b55505532d5251bc5f19dbe61264f8c0f8f8ff3121a127918801eebfcfaa8dd07a1e3458',2,NULL,'[]',0,'2019-06-10 01:18:02','2019-06-10 01:18:02','2019-09-08 04:18:02','688c8650-8b14-11e9-87d1-edc01857dfa6'),('228262444b088afa1d6e3880356aeec391526ee244145c73c96fe74ac5fc0c9719207a9fedf15489',2,NULL,'[]',0,'2019-06-29 01:37:24','2019-06-29 01:37:24','2019-09-27 04:37:24','95af8a80-9a27-11e9-8c8a-ab6bff83dd99'),('2bb518d30d3526500a3e1cb15ea7819d55e3fa69c007ad5f8ef7c2d967a5a655ddb51e58e2584679',2,NULL,'[]',0,'2019-07-13 13:51:08','2019-07-13 13:51:08','2019-10-11 16:51:08','67995ef0-a58e-11e9-af69-35868409dcdf'),('39187c2aee045942043c57c588de19d5dda0dfb02ac2fd22e837a9f815aec4666f1c9596ca7bb807',2,NULL,'[]',0,'2019-06-10 07:31:35','2019-06-10 07:31:35','2019-09-08 10:31:35','688c8650-8b14-11e9-87d1-edc01857dfa6'),('4a617096fd4f58973a2a4408d2114a3914c4a7e9ddad93e77896f875dfc090f6ffd67488c92c1ac4',2,NULL,'[]',0,'2019-06-28 10:35:08','2019-06-28 10:35:08','2019-09-26 13:35:08','8a072400-99a9-11e9-8f9f-33f8fa440db4'),('84a7f6ce1b0eaff07c4d9fcec99d5cd73dc37afcf778797e5445c2f91d228e22e20051c34f5a6b26',2,NULL,'[]',0,'2019-06-11 12:49:13','2019-06-11 12:49:13','2019-09-09 15:49:13','688c8650-8b14-11e9-87d1-edc01857dfa6'),('9f6ccacf528802520b2e75a404fe16bf6a0c7a215e01527fe0d19e8a5029c16fee958741544ed3e4',2,NULL,'[]',0,'2019-06-09 21:16:15','2019-06-09 21:16:15','2019-09-08 00:16:15','f47ae2e0-8b14-11e9-adbd-d978d3209a25'),('a6aa935d945f5414ff3ab58baced85b2db2d0f9176645ab8df460a4c83a9e8374ce3351558dc5ad4',2,NULL,'[]',0,'2019-06-10 04:35:21','2019-06-10 04:35:21','2019-09-08 07:35:21','688c8650-8b14-11e9-87d1-edc01857dfa6'),('c7730c50570d069bb75e5e1d6176ccb230dfcb5c48b6f2b1052fe83ff366918185b63c59afee1484',2,NULL,'[]',0,'2019-07-01 16:43:13','2019-07-01 16:43:13','2019-09-29 19:43:13','74b1c700-9c38-11e9-b25c-33771e795f35'),('d8bf927148ac879f9c255b517c7edceffeaa3e4cb5c36677286e49becd5ed2035af09ab4ec8e7c22',2,NULL,'[]',0,'2019-06-10 02:25:11','2019-06-10 02:25:11','2019-09-08 05:25:11','1cfd2690-8b40-11e9-b385-bdfea6b194fc'),('da3b68097eccc7450cdd2e8028f1707f45c618a3b25eb92166963c46029d5af3475c280dea0c560d',2,NULL,'[]',0,'2019-06-30 04:44:54','2019-06-30 04:44:54','2019-09-28 07:44:54','f17a6ec0-9b0a-11e9-8d67-a76c4e71b4f2'),('ec30f05dab6dbd145e1020017af0dc348b171ff245537c843493407bb738c0868e0d1f9723698d7b',2,NULL,'[]',0,'2019-07-01 17:01:44','2019-07-01 17:01:44','2019-09-29 20:01:44','8a072400-99a9-11e9-8f9f-33f8fa440db4'),('f42db9be9b48bff4a757678487c02eef5d00b7e7d585de6542b9031cc8988287b42613504f29ce50',2,NULL,'[]',0,'2019-06-10 04:33:19','2019-06-10 04:33:19','2019-09-08 07:33:19','1cfd2690-8b40-11e9-b385-bdfea6b194fc'),('f6b270a840ea8dd2586834244f6b1de2b4ac25968138e3b7d51f3c682707f8d61ccefb149c660883',2,NULL,'[]',0,'2019-06-09 23:25:36','2019-06-09 23:25:36','2019-09-08 02:25:36','688c8650-8b14-11e9-87d1-edc01857dfa6');
/*!40000 ALTER TABLE `oauth_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_auth_codes`
--

DROP TABLE IF EXISTS `oauth_auth_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_auth_codes`
--

LOCK TABLES `oauth_auth_codes` WRITE;
/*!40000 ALTER TABLE `oauth_auth_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_auth_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_clients`
--

DROP TABLE IF EXISTS `oauth_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_clients`
--

LOCK TABLES `oauth_clients` WRITE;
/*!40000 ALTER TABLE `oauth_clients` DISABLE KEYS */;
INSERT INTO `oauth_clients` VALUES (1,NULL,'Timeless Entertainment Personal Access Client','nh9BLsHTK2gJxYoA8NPHuQg5dngXhUTJ4mQZNbXC','http://localhost',1,0,0,'2019-06-06 12:59:30','2019-06-06 12:59:30'),(2,NULL,'Timeless Entertainment Password Grant Client','wFC7KVmofGtoL2geqRMfz5nW3F7AMKabZ1gzhwvP','http://13.235.33.102/callback',0,1,0,'2019-06-06 12:59:30','2019-06-06 12:59:30'),(3,NULL,'Timeless Entertainment Personal Access Client','w0evhTmkcPFGnoP93SPcqoSROStSrnMWh0s3cwKZ','http://localhost',1,0,0,'2019-06-06 13:39:54','2019-06-06 13:39:54'),(4,NULL,'Timeless Entertainment Password Grant Client','WGLviejAkD7VDasJBw1OXmaSnlixU9r5W847Em5F','http://localhost',0,1,0,'2019-06-06 13:39:54','2019-06-06 13:39:54'),(5,NULL,'Timeless Entertainment Personal Access Client','e99B72ejSrJrhK0RrHufvqfHw0hmw1VIAc3w1DPf','http://localhost',1,0,0,'2019-06-06 13:40:10','2019-06-06 13:40:10'),(6,NULL,'Timeless Entertainment Password Grant Client','42Tyl1qhljzwig6VB3PFotCeKQwZMZXyrhf4ooXD','http://localhost',0,1,0,'2019-06-06 13:40:10','2019-06-06 13:40:10'),(7,2,'chitano','XCyvjcpCNdzQx18oF45Ds937Bd5akOgKDtlJmWPQ','http://13.235.33.102/oauth/callback',0,0,0,'2019-06-06 14:28:33','2019-06-06 14:28:33'),(8,NULL,'Timeless Entertainment Password Grant Client','hZ4js15h85nUYyyRZF53eYKZWd2qlHfTDNjG8q52','http://localhost',0,1,0,'2019-06-06 14:29:46','2019-06-06 14:29:46'),(9,7,'client','d3nGFINAhYG32VE2zzBZESva6U0LWoQXJXONB2Ku','http://13.235.33.102/callback',0,0,0,'2019-06-06 14:35:30','2019-06-06 14:35:30'),(10,NULL,'Timeless Entertainment Personal Access Client','0vQqqHuITEDwRlmweYjCB8tA6whQ2H4NsWBEFdgP','http://localhost',1,0,0,'2019-06-07 03:41:18','2019-06-07 03:41:18'),(11,NULL,'Timeless Entertainment Password Grant Client','T3QDIfcEaeMXNhJcMXSGgCXBI9M9mb8svCojKKOi','http://localhost',0,1,0,'2019-06-07 03:41:18','2019-06-07 03:41:18'),(12,NULL,'Timeless Entertainment Personal Access Client','0nQjvuDvYEPh77lf81un3PU6IrEOQCTMHWSsK9UO','http://localhost',1,0,0,'2019-06-09 12:57:14','2019-06-09 12:57:14'),(13,NULL,'Timeless Entertainment Password Grant Client','JgooI8jEY0vZvCStLQU8XC9YcK9vORQ4db5HDNZO','http://localhost',0,1,0,'2019-06-09 12:57:14','2019-06-09 12:57:14'),(14,NULL,'Timeless Entertainment Personal Access Client','glzyR7BhBQrivXx3pXsDRludMoEJtdIa7v9ZivWH','http://localhost',1,0,0,'2019-06-09 16:56:06','2019-06-09 16:56:06'),(15,NULL,'Timeless Entertainment Password Grant Client','P5gKlQT2wMDMi5ZwJo3SW0GUjaUMJFt42sXoAjLm','http://localhost',0,1,0,'2019-06-09 16:56:06','2019-06-09 16:56:06'),(16,NULL,'Timeless Entertainment Personal Access Client','B3RaGgILNgSDbkZXnOZ9sdPUuLwdz2JZ3Lz1A2Z9','http://localhost',1,0,0,'2019-06-09 16:56:11','2019-06-09 16:56:11'),(17,NULL,'Timeless Entertainment Password Grant Client','JTfBbnZOcDShbN6CGSj63mjfOqUWdWoc2Hv1vgzT','http://localhost',0,1,0,'2019-06-09 16:56:11','2019-06-09 16:56:11');
/*!40000 ALTER TABLE `oauth_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_personal_access_clients`
--

DROP TABLE IF EXISTS `oauth_personal_access_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_personal_access_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_personal_access_clients_client_id_index` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_personal_access_clients`
--

LOCK TABLES `oauth_personal_access_clients` WRITE;
/*!40000 ALTER TABLE `oauth_personal_access_clients` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_personal_access_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_refresh_tokens`
--

DROP TABLE IF EXISTS `oauth_refresh_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_refresh_tokens`
--

LOCK TABLES `oauth_refresh_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_refresh_tokens` DISABLE KEYS */;
INSERT INTO `oauth_refresh_tokens` VALUES ('1804efa2edf8433e9d0dcdbd622231c27458c91bb1a0a81a3ebffe9729ac3411ba939ede4bed2bfd','a6aa935d945f5414ff3ab58baced85b2db2d0f9176645ab8df460a4c83a9e8374ce3351558dc5ad4',0,'2020-06-10 07:35:21'),('2477d30cf626f41f2a7e82c946de72cfd04f5e0f278299cf9eab71805b978670b9d90cbb726e5da5','2240ea58b55505532d5251bc5f19dbe61264f8c0f8f8ff3121a127918801eebfcfaa8dd07a1e3458',0,'2020-06-10 04:18:02'),('2e78c9770af095149b1e172a00bd19dc72d6f5e954b6358ca9d697560df15cbedf4e7a1e5eb04d13','f6b270a840ea8dd2586834244f6b1de2b4ac25968138e3b7d51f3c682707f8d61ccefb149c660883',0,'2020-06-10 02:25:36'),('30ec5ff1c44794fe97183d088c1949d57a568c71cd191cdcf5411e6978016eb67186425b7a5719b1','da3b68097eccc7450cdd2e8028f1707f45c618a3b25eb92166963c46029d5af3475c280dea0c560d',0,'2020-06-30 07:44:54'),('4099084cfefd676d0a8b9dbd4d6243f3513019da62d22edfe6f6e900cb0a064f02b1128577f1d4e0','cec5a8c8db71008c3f897f1797ebfd5eeb9f520072403092a72a7adb00778fbcea24dac74e29dae0',0,'2020-06-10 02:14:06'),('5b80cac72bdc226aad45affd8efd46ddd661b3dec8e781ab57e799c4d7aefd551e25f6f821130a36','228262444b088afa1d6e3880356aeec391526ee244145c73c96fe74ac5fc0c9719207a9fedf15489',0,'2020-06-29 04:37:24'),('5d4e5d4c36dc9c0c2327cc9fcf96b577f856998adc8df05975d4c48594f18ded2f8c372333551cfa','1fb86306bb0df5e2e554882ba887f575cde29cc3bf61f5c60a56eddd43a4d20499c82a1d00bbf874',0,'2020-06-10 18:11:55'),('6c5d0ab8f5b00234f7469de78731a6ebfa31ba4173499a6652b29779aef6130ba85a53479896270e','9f6ccacf528802520b2e75a404fe16bf6a0c7a215e01527fe0d19e8a5029c16fee958741544ed3e4',0,'2020-06-10 00:16:15'),('6ccf12f466f3728e30a1f3850396d1a4ba856e590938a041f326a22b7abb81e52d9d7fb366e5cc48','2bb518d30d3526500a3e1cb15ea7819d55e3fa69c007ad5f8ef7c2d967a5a655ddb51e58e2584679',0,'2020-07-13 16:51:08'),('6f41079ea59ddc6dafc0f9b6a756473a1ee9d21af3478c08d537b892c9da7070908bcafa9b252703','4a617096fd4f58973a2a4408d2114a3914c4a7e9ddad93e77896f875dfc090f6ffd67488c92c1ac4',0,'2020-06-28 13:35:08'),('8a1e95c3ec98268b44effbeb7649703198977d26d14fb0455df47f3d667b2184d89a3fa8bcb7b4b4','c7730c50570d069bb75e5e1d6176ccb230dfcb5c48b6f2b1052fe83ff366918185b63c59afee1484',0,'2020-07-01 19:43:13'),('94efa16950061204d754b16a53da08adebceee9a2bd1c79ef87627fb431fb106d14b7b781a48bf84','7397679337aa727ba6eda0e0b54d5659678694f51d5cc3b49f3328b95be6c83ace78075c1f16dbb8',0,'2020-06-10 01:16:02'),('cd8f92cf14ed3b87e2d347cbfb46c5256d42c78609cfe89847507aef81b48d8212fefeaebe50d7c9','39187c2aee045942043c57c588de19d5dda0dfb02ac2fd22e837a9f815aec4666f1c9596ca7bb807',0,'2020-06-10 10:31:35'),('d101e98d113a38207610b40574f950ed26dbb58bd07d558fafcc606750eb24d3cc7a2abfff450602','84a7f6ce1b0eaff07c4d9fcec99d5cd73dc37afcf778797e5445c2f91d228e22e20051c34f5a6b26',0,'2020-06-11 15:49:13'),('f22fcc7091337378028b54d42aae03ffe4cca0ffe730f255327c8790e1916676ae1c507d21de190d','d8bf927148ac879f9c255b517c7edceffeaa3e4cb5c36677286e49becd5ed2035af09ab4ec8e7c22',0,'2020-06-10 05:25:11'),('f26b6476663ac88897a484d19b98cf4d2d192147220eb2068d032303e69208b894da28f746d4e0ae','f42db9be9b48bff4a757678487c02eef5d00b7e7d585de6542b9031cc8988287b42613504f29ce50',0,'2020-06-10 07:33:19'),('f5f1c36993b07468a2780ddfe84bec8f135f78559d78c0b74ba39af8009553703130804d9f621726','ec30f05dab6dbd145e1020017af0dc348b171ff245537c843493407bb738c0868e0d1f9723698d7b',0,'2020-07-01 20:01:44');
/*!40000 ALTER TABLE `oauth_refresh_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plugins`
--

DROP TABLE IF EXISTS `plugins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plugins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'thumbnail.png',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `type` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plugins`
--

LOCK TABLES `plugins` WRITE;
/*!40000 ALTER TABLE `plugins` DISABLE KEYS */;
INSERT INTO `plugins` VALUES (1,'default','/themes/default/thumbnail.png',0,'theme','2019-06-09 20:58:23','2019-06-09 20:58:23');
/*!40000 ALTER TABLE `plugins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recently_watcheds`
--

DROP TABLE IF EXISTS `recently_watcheds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recently_watcheds` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `episode_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `series_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `movie_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_time` int(11) NOT NULL,
  `duration_time` int(11) NOT NULL,
  `uid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `recently_watcheds_movie_id_foreign` (`movie_id`),
  KEY `recently_watcheds_episode_id_foreign` (`episode_id`),
  KEY `recently_watcheds_uid_foreign` (`uid`),
  CONSTRAINT `recently_watcheds_episode_id_foreign` FOREIGN KEY (`episode_id`) REFERENCES `episodes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `recently_watcheds_movie_id_foreign` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`m_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `recently_watcheds_uid_foreign` FOREIGN KEY (`uid`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recently_watcheds`
--

LOCK TABLES `recently_watcheds` WRITE;
/*!40000 ALTER TABLE `recently_watcheds` DISABLE KEYS */;
/*!40000 ALTER TABLE `recently_watcheds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `report_details` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_userid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_movie` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_episode` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_series` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_from` smallint(6) DEFAULT NULL,
  `report_readit` smallint(6) NOT NULL DEFAULT '0',
  `report_type` smallint(6) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reports_report_userid_foreign` (`report_userid`),
  KEY `reports_report_movie_foreign` (`report_movie`),
  KEY `reports_report_episode_foreign` (`report_episode`),
  KEY `reports_report_series_foreign` (`report_series`),
  CONSTRAINT `reports_report_episode_foreign` FOREIGN KEY (`report_episode`) REFERENCES `episodes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reports_report_movie_foreign` FOREIGN KEY (`report_movie`) REFERENCES `movies` (`m_id`) ON DELETE CASCADE,
  CONSTRAINT `reports_report_series_foreign` FOREIGN KEY (`report_series`) REFERENCES `series` (`t_id`) ON DELETE CASCADE,
  CONSTRAINT `reports_report_userid_foreign` FOREIGN KEY (`report_userid`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reports`
--

LOCK TABLES `reports` WRITE;
/*!40000 ALTER TABLE `reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','A Admin','2019-06-09 20:58:23','2019-06-09 20:58:23'),(2,'manager','A User','2019-06-09 20:58:23','2019-06-09 20:58:23'),(3,'blocked','A blocked User','2019-06-09 20:58:23','2019-06-09 20:58:23'),(4,'admin','A Admin','2019-06-09 21:13:32','2019-06-09 21:13:32'),(5,'manager','A User','2019-06-09 21:13:32','2019-06-09 21:13:32'),(6,'blocked','A blocked User','2019-06-09 21:13:32','2019-06-09 21:13:32');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `series`
--

DROP TABLE IF EXISTS `series`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `series` (
  `t_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `t_moviedbid` int(11) NOT NULL,
  `t_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `t_desc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `t_genre` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `t_year` int(11) NOT NULL,
  `t_age` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `t_rate` double(8,2) NOT NULL,
  `t_cloud` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `t_poster` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `t_category` int(11) NOT NULL,
  `t_backdrop` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`t_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `series`
--

LOCK TABLES `series` WRITE;
/*!40000 ALTER TABLE `series` DISABLE KEYS */;
/*!40000 ALTER TABLE `series` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `siteinfos`
--

DROP TABLE IF EXISTS `siteinfos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `siteinfos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `social_facebook` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social_twitter` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social_instagram` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `privacy` text COLLATE utf8mb4_unicode_ci,
  `terms` text COLLATE utf8mb4_unicode_ci,
  `about` text COLLATE utf8mb4_unicode_ci,
  `payment_status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `siteinfos`
--

LOCK TABLES `siteinfos` WRITE;
/*!40000 ALTER TABLE `siteinfos` DISABLE KEYS */;
INSERT INTO `siteinfos` VALUES (1,'https://facaebook.com','https://twitter.com','https://instagram.com','Here is privacy','Here is terms','Here is about',1,'2019-06-09 20:58:23','2019-06-09 20:58:23');
/*!40000 ALTER TABLE `siteinfos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscriptions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `braintree_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `braintree_plan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscriptions`
--

LOCK TABLES `subscriptions` WRITE;
/*!40000 ALTER TABLE `subscriptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subtitles`
--

DROP TABLE IF EXISTS `subtitles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subtitles` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `language` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `movie_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `episode_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subtitles_movie_id_foreign` (`movie_id`),
  KEY `subtitles_episode_id_foreign` (`episode_id`),
  CONSTRAINT `subtitles_episode_id_foreign` FOREIGN KEY (`episode_id`) REFERENCES `episodes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `subtitles_movie_id_foreign` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`m_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subtitles`
--

LOCK TABLES `subtitles` WRITE;
/*!40000 ALTER TABLE `subtitles` DISABLE KEYS */;
/*!40000 ALTER TABLE `subtitles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `support_responses`
--

DROP TABLE IF EXISTS `support_responses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `support_responses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `request_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reply` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `readit` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `support_responses_request_id_foreign` (`request_id`),
  CONSTRAINT `support_responses_request_id_foreign` FOREIGN KEY (`request_id`) REFERENCES `supports` (`request_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `support_responses`
--

LOCK TABLES `support_responses` WRITE;
/*!40000 ALTER TABLE `support_responses` DISABLE KEYS */;
/*!40000 ALTER TABLE `support_responses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supports`
--

DROP TABLE IF EXISTS `supports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` varchar(2000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `request_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `supports_request_id_unique` (`request_id`),
  KEY `supports_uid_foreign` (`uid`),
  CONSTRAINT `supports_uid_foreign` FOREIGN KEY (`uid`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supports`
--

LOCK TABLES `supports` WRITE;
/*!40000 ALTER TABLE `supports` DISABLE KEYS */;
/*!40000 ALTER TABLE `supports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tmdbs`
--

DROP TABLE IF EXISTS `tmdbs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tmdbs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `api` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tmdbs`
--

LOCK TABLES `tmdbs` WRITE;
/*!40000 ALTER TABLE `tmdbs` DISABLE KEYS */;
INSERT INTO `tmdbs` VALUES (1,'8bc34de1f5106c30f2f2224a4ed46f29','en-US','2019-06-09 20:58:23','2019-06-09 21:52:44');
/*!40000 ALTER TABLE `tmdbs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tops`
--

DROP TABLE IF EXISTS `tops`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tops` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `movie_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `series_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tops_movie_id_foreign` (`movie_id`),
  KEY `tops_series_id_foreign` (`series_id`),
  CONSTRAINT `tops_movie_id_foreign` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`m_id`) ON DELETE CASCADE,
  CONSTRAINT `tops_series_id_foreign` FOREIGN KEY (`series_id`) REFERENCES `series` (`t_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tops`
--

LOCK TABLES `tops` WRITE;
/*!40000 ALTER TABLE `tops` DISABLE KEYS */;
/*!40000 ALTER TABLE `tops` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transcoders`
--

DROP TABLE IF EXISTS `transcoders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transcoders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `segment_duration` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `watermark_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `watermark_position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transcoders`
--

LOCK TABLES `transcoders` WRITE;
/*!40000 ALTER TABLE `transcoders` DISABLE KEYS */;
INSERT INTO `transcoders` VALUES (1,'10','YJzbN1oi92t2qVGD2QH3.png','TopRight','2019-06-09 20:58:23','2019-06-28 16:54:04');
/*!40000 ALTER TABLE `transcoders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tvs`
--

DROP TABLE IF EXISTS `tvs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tvs` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `genre` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `streaming_status` tinyint(1) NOT NULL DEFAULT '0',
  `streaming_pid` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `streaming_name` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `streaming_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `streaming_resolutions` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `streaming_type` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `streaming_transcoding` tinyint(1) NOT NULL DEFAULT '0',
  `category` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tvs`
--

LOCK TABLES `tvs` WRITE;
/*!40000 ALTER TABLE `tvs` DISABLE KEYS */;
INSERT INTO `tvs` VALUES ('418374e0-8bb4-11e9-a554-c5bbad49a84f','jvjh','','oQWfp8J0WWW3SvvMeltc.png',1,NULL,NULL,'https://bitdash-a.akamaihd.net/content/sintel/hls/playlist.m3u8','null','',0,'2','2019-06-10 16:16:32','2019-06-11 13:35:03'),('42362820-8b37-11e9-9c6a-37719ea2861b','jv,h','','v9ZWAKiQARbxXVMWPiCC.png',0,NULL,NULL,'http://server.internext.tv:8080/get.php?username=testline&password=test20&type=m3u_plus&output=m3u8',NULL,'',0,'2','2019-06-10 01:21:46','2019-06-10 16:15:38'),('68a60a80-8b6b-11e9-88ea-774b7c464a4d','test','','Bfx2Rq4PpK10gUGe9Ssm.png',0,NULL,NULL,'http://portal.geniptv.com:8080/get.php?username=vWtfCwoQpG&password=aAQPsgVO4R&type=m3u_plus&output=ts',NULL,'',0,'2','2019-06-10 07:35:04','2019-06-10 16:15:37');
/*!40000 ALTER TABLE `tvs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `confirmation_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `forget_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_block` tinyint(1) NOT NULL DEFAULT '0',
  `braintree_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paypal_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_last_four` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `period_end` date DEFAULT NULL,
  `language` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `theme` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `location` text COLLATE utf8mb4_unicode_ci,
  `caption` text COLLATE utf8mb4_unicode_ci,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('39495b70-8bab-11e9-959b-c5b39ccdb584','Arup Ghosh','arupg1896@gmail.com','$2y$10$Q6ZN8Qapnh/mcvWCxIOMdOCoMyTTo6/tOnPMUJ/1GtFUq56vM0Ay2',0,'GBt3XF1XfAggFoSY6XLtqseDCFfFdK',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'default','{\"city\":\"Kolkata\",\"timezone\":\"Asia\\/Kolkata\",\"isp\":\"Alliance Broadband Services\",\"org\":\"\",\"regionName\":\"West Bengal\",\"lat\":22.57200050354004,\"lon\":88.36699676513672,\"as\":\"AS23860 Alliance Broadband Services Pvt. Ltd.\",\"status\":\"success\",\"country\":\"India\",\"countryCode\":\"IN\",\"region\":\"WB\",\"query\":\"45.251.235.109\",\"zip\":\"700037\"}',NULL,NULL,'2019-06-10 15:11:52','2019-06-10 15:11:52'),('67995ef0-a58e-11e9-af69-35868409dcdf','Rohit sharma','rohitbhardwaj883@gmail.com','$2y$10$/3iFZAjpFNjehR2P.IbqjO8ZO8hg.nyuryHSw2RbR20iYUSTIfwmm',0,'ihkNhuNaTzqmdvPhyNmhgfAybSMn4K',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,'en','default','{\"country\":\"India\",\"lat\":28.60140037536621,\"org\":\"Reliance Jio infocomm ltd\",\"status\":\"success\",\"city\":\"New Delhi\",\"lon\":77.19889831542969,\"as\":\"AS55836 Reliance Jio Infocomm Limited\",\"query\":\"47.30.209.12\",\"regionName\":\"National Capital Territory of Delhi\",\"timezone\":\"Asia\\/Kolkata\",\"isp\":\"Reliance Jio Infocomm Ltd\",\"countryCode\":\"IN\",\"region\":\"DL\",\"zip\":\"110015\"}',NULL,NULL,'2019-07-13 13:51:05','2019-07-13 13:51:39'),('688c8650-8b14-11e9-87d1-edc01857dfa6','fjhkjgh','anouar1@example.com','$2y$10$PqzKL6v.Y8fT7OlYlm6xieQCtVjbV7R94NgyQRDXCrc1WbsI9XPrm',0,'eDuCdGfOC8Yg5RTxSiX1V2CjRMgoUR',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,'en','default','{\"query\":\"41.251.95.235\",\"as\":\"AS36903 Office National des Postes et Telecommunications ONPT (Maroc Telecom) \\/ IAM\",\"country\":\"Morocco\",\"region\":\"09\",\"zip\":\"\",\"org\":\"\",\"status\":\"success\",\"city\":\"Agadir\",\"isp\":\"ADSL Maroc telecom\",\"countryCode\":\"MA\",\"regionName\":\"Souss-Massa\",\"lat\":30.420700073242188,\"lon\":-9.593199729919434,\"timezone\":\"Africa\\/Casablanca\"}',NULL,NULL,'2019-06-09 21:12:18','2019-06-13 09:18:13'),('6afee380-8b25-11e9-9b06-57b031789614','edfgdgdg','anouar15@example.com','$2y$10$5uwJcOTsQJNJ792dMlDdHeA0mIggf.HT1gDlckekVNHYYJ.gLslwm',0,'Mjn7sXpvFjMdl1NgoLpgLmawVIJvyK',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'default','{\"status\":\"success\",\"lat\":30.420700073242188,\"lon\":-9.593199729919434,\"countryCode\":\"MA\",\"regionName\":\"Souss-Massa\",\"org\":\"\",\"as\":\"AS36903 Office National des Postes et Telecommunications ONPT (Maroc Telecom) \\/ IAM\",\"isp\":\"ADSL Maroc telecom\",\"query\":\"41.251.95.235\",\"region\":\"09\",\"city\":\"Agadir\",\"zip\":\"\",\"country\":\"Morocco\",\"timezone\":\"Africa\\/Casablanca\"}',NULL,NULL,'2019-06-09 23:14:03','2019-06-09 23:14:03'),('74b1c700-9c38-11e9-b25c-33771e795f35','anoircpo','anoirazer@gmail.com','$2y$10$ipYBRSSSn9llXRtoxVat3.8ErkWo4Y5URrN5JJPOOj/Unse/hQ32.',0,'c6EVjiYDhOaUJhx4IFAGvHvfIc6Cna',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'default','{\"timezone\":\"Africa\\/Casablanca\",\"regionName\":\"Casablanca-Settat\",\"lon\":-7.589839935302734,\"city\":\"Casablanca\",\"zip\":\"\",\"org\":\"\",\"status\":\"success\",\"countryCode\":\"MA\",\"as\":\"AS36925 MEDITELECOM\",\"query\":\"45.218.250.117\",\"lat\":33.57310104370117,\"isp\":\"Mobile Costumers\",\"country\":\"Morocco\",\"region\":\"06\"}',NULL,NULL,'2019-07-01 16:43:10','2019-07-01 16:43:10'),('7e427d80-8b14-11e9-ac22-e9c4b4c221a3','gfhklklmklm','anouar12@example.com','$2y$10$.zKGSjNfMBxZDeUWTuwZm.5QbSf1rrqKqJa0dUA4QZrNXpD.k3dfy',0,'uHcwA93fN7iYVHRHLG9Tfx9b50PVcr',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'default','{\"lat\":30.420700073242188,\"timezone\":\"Africa\\/Casablanca\",\"status\":\"success\",\"zip\":\"\",\"regionName\":\"Souss-Massa\",\"city\":\"Agadir\",\"org\":\"\",\"as\":\"AS36903 Office National des Postes et Telecommunications ONPT (Maroc Telecom) \\/ IAM\",\"country\":\"Morocco\",\"countryCode\":\"MA\",\"region\":\"09\",\"lon\":-9.593199729919434,\"query\":\"41.251.95.235\",\"isp\":\"ADSL Maroc telecom\"}',NULL,NULL,'2019-06-09 21:12:54','2019-06-09 21:12:54'),('8a072400-99a9-11e9-8f9f-33f8fa440db4','Sumit Dutta','sumit@gmail.com','$2y$10$hn67B39YGFM3AK3aW4VEx.RgxBC6hdqbtkPSxuNKiDjci72tDbk.e',0,'crTxtKnb1sXSnT1MqplOZmjWxBbuTD',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,'en','default','{\"zip\":\"711413\",\"as\":\"AS135872 GTPL KCBPL BROADBAND PVT LTD\",\"countryCode\":\"IN\",\"city\":\"Barddhaman\",\"lon\":87.86940002441406,\"regionName\":\"West Bengal\",\"isp\":\"Gtpl Kcbpl Broadband PVT LTD\",\"org\":\"Gtpl Kcbpl Broadband PVT LTD\",\"timezone\":\"Asia\\/Kolkata\",\"status\":\"success\",\"query\":\"103.211.23.215\",\"country\":\"India\",\"region\":\"WB\",\"lat\":23.2406005859375}','{\"font-size\":\"30px\",\"background-color\":\"rgba(74,25,77,1)\",\"font-weight\":100,\"color\":\"#4D2519\"}',NULL,'2019-06-28 10:35:05','2019-06-28 15:49:06'),('95af8a80-9a27-11e9-8c8a-ab6bff83dd99','Mohan sharma','msf149895@gmail.com','$2y$10$zwFX1bXSMSC3xZwlutX37upGy.MYC/WrBRYaYn76hhTvy4a4mwNPC',0,'JHgvRmciAvsR34h0S6UCNyr1PIDuRW',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'default','{\"timezone\":\"Asia\\/Kolkata\",\"as\":\"AS58640 NEXTRA TELESERVICES PVT. LTD.\",\"query\":\"144.48.77.115\",\"region\":\"HR\",\"city\":\"Gurgaon\",\"org\":\"Callcast Services Private Limited\",\"country\":\"India\",\"regionName\":\"Haryana\",\"zip\":\"122016\",\"lat\":28.46660041809082,\"isp\":\"Nextra\",\"status\":\"success\",\"countryCode\":\"IN\",\"lon\":77.03089904785156}',NULL,NULL,'2019-06-29 01:37:21','2019-06-29 01:37:21'),('f17a6ec0-9b0a-11e9-8d67-a76c4e71b4f2','Yujin Boby','admin@serverok.in','$2y$10$A6Qq6oWbjHHRID4RdcrjfueSM3PyJwJcwIdl/vobavf1wgS3PCctK',0,'NWbmP1YbY8fQLDXSofonjgJntztsoL',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'default','{\"status\":\"success\",\"zip\":\"110006\",\"timezone\":\"Asia\\/Kolkata\",\"isp\":\"FTTH Project O\\/O DGM BB\",\"lon\":77.22740173339844,\"country\":\"India\",\"region\":\"DL\",\"regionName\":\"Delhi\",\"city\":\"Delhi\",\"as\":\"AS9829 Bharat Sanchar Nigam Ltd\",\"query\":\"103.35.199.82\",\"countryCode\":\"IN\",\"lat\":28.65239906311035,\"org\":\"Silpa Agencies\"}',NULL,NULL,'2019-06-30 04:44:51','2019-06-30 04:44:51'),('f47ae2e0-8b14-11e9-adbd-d978d3209a25','jljkjlj','anouar155@example.com','$2y$10$186aNS.wgAvJjQ0gKHmSHOodDjOAq1MeltV/OssNbRM5vLJ5qx/RW',0,'pqFrbovdrL1ITEKVbW2dXWM0OuGoAG',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'default','{\"query\":\"41.251.95.235\",\"zip\":\"\",\"isp\":\"ADSL Maroc telecom\",\"status\":\"success\",\"region\":\"09\",\"regionName\":\"Souss-Massa\",\"city\":\"Agadir\",\"lon\":-9.593199729919434,\"timezone\":\"Africa\\/Casablanca\",\"org\":\"\",\"country\":\"Morocco\",\"countryCode\":\"MA\",\"lat\":30.420700073242188,\"as\":\"AS36903 Office National des Postes et Telecommunications ONPT (Maroc Telecom) \\/ IAM\"}',NULL,NULL,'2019-06-09 21:16:12','2019-06-09 21:16:12');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `videos`
--

DROP TABLE IF EXISTS `videos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `videos` (
  `v_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `movie_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `episode_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resolution` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration` varchar(35) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_cloud` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'local',
  `video_format` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hls',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`v_id`),
  KEY `videos_movie_id_foreign` (`movie_id`),
  KEY `videos_episode_id_foreign` (`episode_id`),
  CONSTRAINT `videos_episode_id_foreign` FOREIGN KEY (`episode_id`) REFERENCES `episodes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `videos_movie_id_foreign` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`m_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `videos`
--

LOCK TABLES `videos` WRITE;
/*!40000 ALTER TABLE `videos` DISABLE KEYS */;
INSERT INTO `videos` VALUES ('095ce3d0-8b37-11e9-824a-6902e97b29c4','83c90d10-8b36-11e9-89d0-f5f7ff29b9c1',NULL,'1080',NULL,'/movies/Re: Zero kara Hajimeru Isekai Seikatsu - Memory Sn/4ZqsQREc2JZQ3EIgxYeY-1080.mp4','aws','mp4','2019-06-10 01:20:10','2019-06-10 01:20:10'),('1dd7ecf0-8b54-11e9-ade6-8775c7037dd0','9cfea820-8b52-11e9-a40f-39e9f617210b',NULL,'1080',NULL,'/movies/Godzilla: King of the Monsters/L81PjIIt3l3x483Ac2KN-1080.mp4','aws','mp4','2019-06-10 04:48:20','2019-06-10 04:48:20'),('25eae540-999b-11e9-867c-f17432294124','61738ac0-9998-11e9-a1ab-cd35a673069d',NULL,'720',NULL,'/movies/Toy Story 4/dCSoMSJGco47I6pX4WWG-720.mp4','aws','mp4','2019-06-28 08:52:04','2019-06-28 08:52:04'),('2fad7520-8b25-11e9-ba21-672a7826f4db','2029e590-8b25-11e9-b06e-adca20003bd5',NULL,'320',NULL,'/movies/Pokémon Detective Pikachu/Y2IJVKBjQYfBaYkRcmn7-320.mp4','aws','mp4','2019-06-09 23:12:24','2019-06-09 23:12:24'),('9096efe0-8b50-11e9-8afb-774e294fe511','53226a80-8b50-11e9-b30d-6be2a69e2e2a',NULL,'720',NULL,'/movies/Nobody\'s Daughter/BkbJOG2tcnLg1xMs2ahP.m3u8','aws','hls','2019-06-10 04:22:55','2019-06-10 04:22:55'),('9a60a5b0-99e0-11e9-a10d-b77224833c06','8bd00ad0-99de-11e9-b933-35f071c29f15',NULL,'1080',NULL,'/movies/After/270Dxy5FdTZlWoiXI3Kg-1080.mp4','aws','mp4','2019-06-28 17:09:15','2019-06-28 17:09:15'),('b0dc3200-944e-11e9-943d-c776614eae14','f4860a50-944c-11e9-b37c-4541177f1c1d',NULL,'1080',NULL,'/movies/The Ghazi Attack/QpIwXmzOybLM9aflE2sL-1080.mp4','aws','mp4','2019-06-21 15:02:10','2019-06-21 15:02:10'),('da7472c0-944f-11e9-8008-51f5750cbcef','f4860a50-944c-11e9-b37c-4541177f1c1d',NULL,'720',NULL,'/movies/The Ghazi Attack/MgoyCFhZHOu6hsCWRqCn-720.mp4','aws','mp4','2019-06-21 15:10:29','2019-06-21 15:10:29'),('ea190c00-9999-11e9-9531-afad01bc7846','61738ac0-9998-11e9-a1ab-cd35a673069d',NULL,'1080',NULL,'/movies/Toy Story 4/Ia7ITHoTbgOVgB3VGQyH-1080.mp4','aws','mp4','2019-06-28 08:43:14','2019-06-28 08:43:14');
/*!40000 ALTER TABLE `videos` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-07-19 21:20:52
