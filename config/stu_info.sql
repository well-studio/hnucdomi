-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016-11-28 08:23:25
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

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
  `bed_num` int(11) NOT NULL DEFAULT '0',
  `note` text,
  UNIQUE KEY `id` (`id`),
  KEY `name` (`name`,`domi_num`),
  KEY `domi_num` (`domi_num`),
  KEY `exm_id` (`exm_id`),
  KEY `class` (`class`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 插入之前先把表清空（truncate） `stu_info`
--

TRUNCATE TABLE `stu_info`;
-- --------------------------------------------------------

--
-- 表的结构 `wl_vote`
--

DROP TABLE IF EXISTS `wl_vote`;
CREATE TABLE IF NOT EXISTS `wl_vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `uuid` varchar(32) DEFAULT NULL,
  `need_code` tinyint(1) NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL,
  `start_time` int(11) NOT NULL,
  `end_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`),
  KEY `uuid_2` (`uuid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

--
-- 插入之前先把表清空（truncate） `wl_vote`
--

TRUNCATE TABLE `wl_vote`;
-- --------------------------------------------------------

--
-- 表的结构 `wl_vote_invitation`
--

DROP TABLE IF EXISTS `wl_vote_invitation`;
CREATE TABLE IF NOT EXISTS `wl_vote_invitation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `created` int(11) NOT NULL,
  `vid` int(11) NOT NULL,
  `used` int(11) NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

--
-- 插入之前先把表清空（truncate） `wl_vote_invitation`
--

TRUNCATE TABLE `wl_vote_invitation`;
-- --------------------------------------------------------

--
-- 表的结构 `wl_vote_item`
--

DROP TABLE IF EXISTS `wl_vote_item`;
CREATE TABLE IF NOT EXISTS `wl_vote_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vid` int(11) NOT NULL,
  `description` text NOT NULL,
  `limit_count` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `vid` (`vid`),
  KEY `vid_2` (`vid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

--
-- 插入之前先把表清空（truncate） `wl_vote_item`
--

TRUNCATE TABLE `wl_vote_item`;
-- --------------------------------------------------------

--
-- 表的结构 `wl_vote_option`
--

DROP TABLE IF EXISTS `wl_vote_option`;
CREATE TABLE IF NOT EXISTS `wl_vote_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iid` int(11) NOT NULL COMMENT 'item_id',
  `content` text NOT NULL,
  `cnt` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `iid` (`iid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

--
-- 插入之前先把表清空（truncate） `wl_vote_option`
--

TRUNCATE TABLE `wl_vote_option`;
