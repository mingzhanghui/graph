-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 25, 2018 at 05:01 PM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 7.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

create database if not exists `knowledgegraph`;

use `knowledgegraph`;

--
-- Database: `knowledgegraph`
--

-- --------------------------------------------------------

--
-- Table structure for table `content`
--
drop table if exists `content`;

CREATE TABLE `content` (
  `id` int(11) NOT NULL,
  `nodeid` int(11) NOT NULL,
  `name` varchar(32) NOT NULL COMMENT '知识内容名称',
  `url` varchar(256) NOT NULL COMMENT '知识内容URL'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='结点下面对应的知识内容';

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`id`, `nodeid`, `name`, `url`) VALUES
(1, 4, 'XAMPP', 'https://www.apachefriends.org/index.html'),
(2, 4, ' LAMP environment', 'http://wiki.jikexueyuan.com/project/linux/lamp.html'),
(3, 7, 'eclipse', 'https://www.eclipse.org/downloads/'),
(4, 7, 'phpstorm', 'https://www.jetbrains.com/phpstorm/');

-- --------------------------------------------------------

--
-- Table structure for table `link`
--
drop table if exists `link`;

CREATE TABLE `link` (
  `id` int(11) NOT NULL COMMENT 'auto_increment id',
  `des` varchar(32) DEFAULT '' COMMENT 'relationship description (show on nodes bond)',
  `source` int(11) NOT NULL COMMENT 'parent node id',
  `target` int(11) NOT NULL COMMENT 'this node id',
  `type` varchar(16) NOT NULL DEFAULT 'REL',
  `structid` int(11) NOT NULL COMMENT 'structure id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='nodes link';

--
-- Dumping data for table `link`
--

INSERT INTO `link` (`id`, `des`, `source`, `target`, `type`, `structid`) VALUES
(1, 'php development', 1, 2, 'REL', 1),
(2, '', 2, 3, 'REL', 1),
(3, 'LAMP => Linux', 3, 4, 'REL', 1),
(4, 'LAMP => mysql', 3, 5, 'REL', 1),
(5, 'LAMP => php', 3, 6, 'REL', 1),
(6, '', 2, 7, 'REL', 1),
(7, '', 2, 8, 'REL', 1),
(8, '', 1, 9, 'REL', 1),
(9, '', 1, 10, 'REL', 1),
(10, '', 1, 11, 'REL', 1),
(11, '', 1, 12, 'REL', 1),
(12, '', 1, 13, 'REL', 1),
(13, '', 1, 14, 'REL', 1),
(14, '', 1, 14, 'REL', 1),
(15, '', 14, 15, 'REL', 1),
(16, '', 14, 16, 'REL', 1),
(17, '', 16, 17, 'REL', 1),
(18, '', 1, 18, 'REL', 1),
(19, '', 1, 19, 'REL', 1);

-- --------------------------------------------------------

--
-- Table structure for table `node`
--

drop table if exists `node`;

CREATE TABLE `node` (
  `id` int(11) NOT NULL COMMENT 'auto_increment id',
  `name` varchar(64) NOT NULL COMMENT 'node name',
  `href` varchar(255) DEFAULT NULL COMMENT 'node url',
  `depth` int(11) NOT NULL COMMENT 'node depth (index), depth=0 represents root node of a structure',
  `structid` int(11) NOT NULL COMMENT 'root node id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `node`
--

INSERT INTO `node` (`id`, `name`, `href`, `depth`, `structid`) VALUES
(1, 'PHP', 'structure.html?structid=1&nodeid=1&name=PHP', 0, 1),
(2, 'Development Environment', NULL, 1, 1),
(3, 'LAMP environment', NULL, 2, 1),
(4, 'linux', NULL, 3, 1),
(5, 'mysql', NULL, 3, 1),
(6, 'php', NULL, 3, 1),
(7, 'IDE', NULL, 2, 1),
(8, 'Tools', NULL, 2, 1),
(9, '基础知识', NULL, 1, 1),
(10, '常用扩展', NULL, 1, 1),
(11, '格式解析', NULL, 1, 1),
(12, '面向对象编程', NULL, 1, 1),
(13, 'Web开发', NULL, 1, 1),
(14, '开发框架', NULL, 1, 1),
(15, 'YII', NULL, 2, 1),
(16, 'Laravel', NULL, 2, 1),
(17, 'laravel china', NULL, 3, 1),
(18, '系统架构', NULL, 1, 1),
(19, '代码片', NULL, 1, 1),
(20, 'JAVA', 'http://lib.csdn.net/my/structure/JAVA%20', 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `structure`
--

CREATE TABLE `structure` (
  `id` int(11) NOT NULL COMMENT 'auto_increment id',
  `name` varchar(64) NOT NULL COMMENT 'structure name'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `structure`
--

INSERT INTO `structure` (`id`, `name`) VALUES
(1, 'PHP'),
(2, 'Java'),
(3, 'require.js'),
(4, 'vue.js');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `link`
--
ALTER TABLE `link`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `node`
--
ALTER TABLE `node`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `structure`
--
ALTER TABLE `structure`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `content`
--
ALTER TABLE `content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `link`
--
ALTER TABLE `link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto_increment id', AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `node`
--
ALTER TABLE `node`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto_increment id', AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `structure`
--
ALTER TABLE `structure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto_increment id', AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
