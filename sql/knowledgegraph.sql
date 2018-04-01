-- MySQL dump 10.16  Distrib 10.1.10-MariaDB, for osx10.6 (i386)
--
-- Host: localhost    Database: knowledgegraph
-- ------------------------------------------------------
-- Server version	10.1.10-MariaDB

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
-- Current Database: `knowledgegraph`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `knowledgegraph` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `knowledgegraph`;

--
-- Table structure for table `content`
--

DROP TABLE IF EXISTS `content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nodeid` int(11) NOT NULL,
  `name` varchar(32) CHARACTER SET utf8 NOT NULL COMMENT '知识内容名称',
  `url` varchar(256) NOT NULL COMMENT '知识内容URL',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1 COMMENT='结点下面对应的知识内容';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content`
--

LOCK TABLES `content` WRITE;
/*!40000 ALTER TABLE `content` DISABLE KEYS */;
INSERT INTO `content` VALUES (1,4,'sed','http://man.linuxde.net/sed'),(2,3,'LAMP极客学院','http://wiki.jikexueyuan.com/project/linux/lamp.html'),(3,7,'eclipse','https://www.eclipse.org/downloads/'),(4,7,'phpstorm','https://www.jetbrains.com/phpstorm/'),(5,5,'mysql official website','https://www.mysql.com/'),(6,6,'php official website','http://jp2.php.net/'),(7,8,' json online format','https://www.bejson.com/'),(8,21,'apache website','http://apache.org/'),(9,4,'awk','http://www.runoob.com/linux/linux-comm-awk.html'),(17,7,'emacs','http://www.gnu.org/software/emacs/'),(18,22,'Intellij Idea','https://www.jetbrains.com/idea/'),(19,4,'vim','https://www.vim.org'),(20,17,'关于 Laravel China','https://laravel-china.org/about');
/*!40000 ALTER TABLE `content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `link`
--

DROP TABLE IF EXISTS `link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto_increment id',
  `des` varchar(32) DEFAULT '' COMMENT 'relationship description (show on nodes bond)',
  `source` int(11) NOT NULL COMMENT 'parent node id',
  `target` int(11) NOT NULL COMMENT 'this node id',
  `type` varchar(16) NOT NULL DEFAULT 'REL',
  `structid` int(11) NOT NULL COMMENT 'structure id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COMMENT='nodes link';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `link`
--

LOCK TABLES `link` WRITE;
/*!40000 ALTER TABLE `link` DISABLE KEYS */;
INSERT INTO `link` VALUES (1,'php development',1,2,'REL',1),(2,'',2,3,'REL',1),(3,'LAMP => Linux',3,4,'REL',1),(4,'LAMP => mysql',3,5,'REL',1),(5,'LAMP => php',3,6,'REL',1),(6,'',2,7,'REL',1),(7,'',2,8,'REL',1),(8,'',1,9,'REL',1),(9,'',1,10,'REL',1),(10,'',1,11,'REL',1),(11,'',1,12,'REL',1),(12,'',1,13,'REL',1),(13,'',1,14,'REL',1),(14,'',1,14,'REL',1),(15,'',14,15,'REL',1),(16,'',14,16,'REL',1),(17,'',16,17,'REL',1),(18,'',1,18,'REL',1),(19,'',1,19,'REL',1),(20,'',3,21,'REL',1),(21,'',20,22,'REL',2);
/*!40000 ALTER TABLE `link` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `node`
--

DROP TABLE IF EXISTS `node`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `node` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto_increment id',
  `name` varchar(64) NOT NULL COMMENT 'node name',
  `href` varchar(255) DEFAULT NULL COMMENT 'node url',
  `depth` int(11) NOT NULL COMMENT 'node depth (index), depth=0 represents root node of a structure',
  `structid` int(11) NOT NULL COMMENT 'root node id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `node`
--

LOCK TABLES `node` WRITE;
/*!40000 ALTER TABLE `node` DISABLE KEYS */;
INSERT INTO `node` VALUES (1,'PHP','structure.html?structid=1&nodeid=1&name=PHP',0,1),(2,'Development Environment',NULL,1,1),(3,'LAMP environment',NULL,2,1),(4,'linux',NULL,3,1),(5,'mysql',NULL,3,1),(6,'php',NULL,3,1),(7,'IDE',NULL,2,1),(8,'Tools',NULL,2,1),(9,'基础知识',NULL,1,1),(10,'常用扩展',NULL,1,1),(11,'格式解析',NULL,1,1),(12,'面向对象编程',NULL,1,1),(13,'Web开发',NULL,1,1),(14,'开发框架',NULL,1,1),(15,'YII',NULL,2,1),(16,'Laravel',NULL,2,1),(17,'laravel china',NULL,3,1),(18,'系统架构',NULL,1,1),(19,'代码片',NULL,1,1),(20,'JAVA','http://lib.csdn.net/my/structure/JAVA%20',0,2),(21,'apache',NULL,3,1);
/*!40000 ALTER TABLE `node` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `structure`
--

DROP TABLE IF EXISTS `structure`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `structure` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto_increment id',
  `name` varchar(64) NOT NULL COMMENT 'structure name',
  `info` varchar(512) DEFAULT NULL COMMENT '图谱说明',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `structure`
--

LOCK TABLES `structure` WRITE;
/*!40000 ALTER TABLE `structure` DISABLE KEYS */;
INSERT INTO `structure` VALUES (1,'PHP','PHP是一种通用开源脚本语言，语法吸收了C、Java和Perl的特点，利于学习，使用广泛，主要适用于Web开发领域。它支持几乎所有流行的数'),(2,'Java',NULL),(3,'require.js',NULL),(4,'vue.js',NULL);
/*!40000 ALTER TABLE `structure` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-04-01 23:30:05
