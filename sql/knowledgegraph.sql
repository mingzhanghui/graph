-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2018 年 3 月 11 日 23:31
-- サーバのバージョン： 10.1.22-MariaDB
-- PHP Version: 7.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `knowledgegraph`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `link`
--

CREATE TABLE `link` (
  `id` int(11) NOT NULL COMMENT 'auto_increment id',
  `des` varchar(32) DEFAULT '' COMMENT 'relationship description (show on nodes bond)',
  `source` int(11) NOT NULL COMMENT 'parent node id',
  `target` int(11) NOT NULL COMMENT 'this node id',
  `type` varchar(16) NOT NULL DEFAULT 'REL'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='nodes link';

--
-- テーブルのデータのダンプ `link`
--

INSERT INTO `link` (`id`, `des`, `source`, `target`, `type`) VALUES
(1, 'php development', 1, 2, 'REL'),
(2, '', 2, 3, 'REL'),
(3, 'LAMP => Linux', 3, 4, 'REL'),
(4, 'LAMP => mysql', 3, 5, 'REL'),
(5, 'LAMP => php', 3, 6, 'REL'),
(6, '', 2, 7, 'REL'),
(7, '', 2, 8, 'REL'),
(8, '', 1, 9, 'REL'),
(9, '', 1, 10, 'REL'),
(10, '', 1, 11, 'REL'),
(11, '', 1, 12, 'REL'),
(12, '', 1, 13, 'REL'),
(13, '', 1, 14, 'REL'),
(14, '', 1, 14, 'REL'),
(15, '', 14, 15, 'REL'),
(16, '', 14, 16, 'REL'),
(17, '', 16, 17, 'REL'),
(18, '', 1, 18, 'REL'),
(19, '', 1, 19, 'REL');

-- --------------------------------------------------------

--
-- テーブルの構造 `node`
--

CREATE TABLE `node` (
  `id` int(11) NOT NULL COMMENT 'auto_increment id',
  `name` varchar(64) NOT NULL COMMENT 'node name',
  `href` varchar(255) DEFAULT NULL COMMENT 'node url',
  `depth` int(11) NOT NULL COMMENT 'node depth (index), depth=0 represents root node of a structure'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `node`
--

INSERT INTO `node` (`id`, `name`, `href`, `depth`) VALUES
(1, 'PHP', 'http://lib.csdn.net/my/structure/PHP', 0),
(2, 'Development Environment', NULL, 1),
(3, 'LAMP environment', NULL, 2),
(4, 'linux', NULL, 3),
(5, 'mysql', NULL, 3),
(6, 'php', NULL, 3),
(7, 'IDE', NULL, 2),
(8, 'Tools', NULL, 2),
(9, '基础知识', NULL, 1),
(10, '常用扩展', NULL, 1),
(11, '格式解析', NULL, 1),
(12, '面向对象编程', NULL, 1),
(13, 'Web开发', NULL, 1),
(14, '开发框架', NULL, 1),
(15, 'YII', NULL, 2),
(16, 'Laravel', NULL, 2),
(17, 'laravel china', NULL, 3),
(18, '系统架构', NULL, 1),
(19, '代码片', NULL, 1);

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `link`
--
ALTER TABLE `link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto_increment id', AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `node`
--
ALTER TABLE `node`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto_increment id', AUTO_INCREMENT=20;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
