-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016-08-20 09:57:59
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hnucdomi`
--

-- --------------------------------------------------------

--
-- 表的结构 `stu_info`
--

DROP TABLE IF EXISTS `stu_info`;
CREATE TABLE IF NOT EXISTS `stu_info` (
  `exm_id` bigint(18) unsigned NOT NULL,
  `id` bigint(11) unsigned NOT NULL,
  `name` varchar(20) NOT NULL,
  `academy` varchar(100) NOT NULL,
  `major` varchar(50) NOT NULL DEFAULT '',
  `class` varchar(100) NOT NULL,
  `domi_num` varchar(20) DEFAULT '',
  `note` text,
  UNIQUE KEY `id` (`id`),
  KEY `name` (`name`,`domi_num`),
  KEY `domi_num` (`domi_num`),
  KEY `exm_id` (`exm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
