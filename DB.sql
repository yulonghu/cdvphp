CREATE DATABASE IF NOT EXISTS `cdvphp_new`;
USE `cdvphp_new`;

CREATE TABLE IF NOT EXISTS `book` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `name` char(50) NOT NULL DEFAULT '' COMMENT '姓名',
  `content` varchar(100) NOT NULL DEFAULT '' COMMENT '内容',
  `reply` varchar(100) DEFAULT '' COMMENT '回复',
  `isrecycled` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '回收站',
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `isrecycled` (`isrecycled`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='简易留言本 - 框架入门例子';

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(50) NOT NULL DEFAULT '',
  `pass` char(32) NOT NULL DEFAULT '',
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `book` (`id`, `name`, `content`, `reply`, `isrecycled`, `mtime`, `addtime`) VALUES
	(1, 'one', 'aaaa', '', 0, '2015-07-09 01:36:19', 1436373503),
	(2, 'two', 'bbbb', '', 0, '2015-07-09 01:36:36', 1436373806),
	(3, 'three', 'cccc', 'reply_3', 0, '2015-07-09 01:36:11', 1436373854),
	(4, 'four', 'dddd', '', 0, '2015-07-09 01:36:01', 1436373913),
	(5, 'five', 'eeee', 'reply_5', 0, '2015-07-09 01:36:15', 1436374091);
