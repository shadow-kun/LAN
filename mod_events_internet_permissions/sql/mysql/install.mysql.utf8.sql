CREATE TABLE IF NOT EXISTS `#__events_internet` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(11) unsigned NOT NULL DEFAULT '0',
  `mac_address` varchar(12) NOT NULL,
  `ip_address` varchar(16) NOT NULL,
  `created_user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `created_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expire_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `interface` varchar(16) NOT NULL DEFAULT 'net',
  `params` mediumtext,
  PRIMARY KEY (`id`)
);