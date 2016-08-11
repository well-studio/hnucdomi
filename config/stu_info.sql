-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016-07-26 10:54:01
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `hnucdomi`
--
CREATE DATABASE IF NOT EXISTS `hnucdomi` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `hnucdomi`;

-- --------------------------------------------------------

--
-- 表的结构 `stu_info`
--

DROP TABLE IF EXISTS `stu_info`;
CREATE TABLE IF NOT EXISTS `stu_info` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `academy` varchar(100) NOT NULL,
  `class` varchar(100) NOT NULL,
  `domi_num` varchar(20) NOT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `name` (`name`,`domi_num`),
  KEY `domi_num` (`domi_num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 插入之前先把表清空（truncate） `stu_info`
--

TRUNCATE TABLE `stu_info`;