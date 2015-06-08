CREATE DATABASE IF NOT EXISTS `cdvphp_new` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `cdvphp_new`;

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(50) NOT NULL DEFAULT '',
  `pass` char(32) NOT NULL DEFAULT '',
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;