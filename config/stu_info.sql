SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


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
  KEY `exm_id` (`exm_id`),
  KEY `class` (`class`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

