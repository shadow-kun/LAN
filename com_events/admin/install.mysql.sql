CREATE TABLE IF NOT EXISTS `#__events_competitions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) unsigned NOT NULL,
  `title` varchar(100) CHARACTER SET 'utf8' NOT NULL,
  `alias` varchar(100) NOT NULL,
  `body` mediumtext CHARACTER SET 'utf8',
  `note` varchar(255) DEFAULT NULL,
  `published` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `access` smallint(5) unsigned NOT NULL DEFAULT '0',
  `language` char(7) DEFAULT NULL,
  `metadesc` varchar(512) DEFAULT NULL,
  `metakey` varchar(255) DEFAULT NULL,
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `created_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `modified_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `params` mediumtext,
  `competition_start` datetime DEFAULT NULL,
  `competition_end` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__events_competition_players` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `competition` int(10) unsigned NOT NULL,
  `user` int(10) unsigned NOT NULL,
  `note` text CHARACTER SET 'utf8',
  `params` mediumtext CHARACTER SET 'utf8',
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__events_competition_teams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `competition` int(11) NOT NULL,
  `team` int(11) NOT NULL,
  `note` text,
  `params` mediumtext,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__events_events` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) unsigned NOT NULL,
  `title` varchar(100) CHARACTER SET 'utf8' NOT NULL,
  `alias` varchar(100) NOT NULL,
  `body` mediumtext CHARACTER SET 'utf8',
  `summary` text CHARACTER SET 'utf8',
  `terms` mediumtext CHARACTER SET 'utf8',
  `note` varchar(255) DEFAULT NULL,
  `published` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `access` smallint(5) unsigned NOT NULL DEFAULT '0',
  `language` char(7) DEFAULT NULL,
  `metadesc` varchar(512) DEFAULT NULL,
  `metakey` varchar(255) DEFAULT NULL,
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `created_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `modified_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `params` mediumtext,
  `details` text CHARACTER SET 'utf8',
  `players_max` int(11) unsigned NOT NULL DEFAULT '0',
  `players_current` int(11) unsigned NOT NULL DEFAULT '0',
  `players_confirmed` int(11) unsigned NOT NULL DEFAULT '0',
  `players_prepay` int(11) unsigned NOT NULL DEFAULT '0',
  `players_prepaid` int(11) unsigned NOT NULL DEFAULT '0',
  `event_start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `event_end_time` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__events_payments` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userEventID` int(10) unsigned DEFAULT NULL,
  `item_number` varchar(30) CHARACTER SET 'utf8' DEFAULT NULL,
  `amount` float NOT NULL DEFAULT '0',
  `currency` varchar(10) CHARACTER SET 'utf8' DEFAULT NULL,
  `params` text CHARACTER SET 'utf8',
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__events_permissions` (
  `permission_id` int(11) NOT NULL,
  `permission_name` varchar(63) NOT NULL,
  PRIMARY KEY (`permission_id`)
);

CREATE TABLE IF NOT EXISTS `#__events_players` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `event` int(11) unsigned NOT NULL,
  `user` int(11) unsigned NOT NULL,
  `status` smallint(11) DEFAULT NULL,
  `checked_in` datetime DEFAULT NULL,
  `note` text CHARACTER SET 'utf8',
  `params` mediumblob,
  `checked_out` int(11) DEFAULT '0',
  `checked_out_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__events_settings` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(45) NOT NULL,
  `value` text CHARACTER SET 'utf8',
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__events_teams` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) unsigned NOT NULL,
  `title` varchar(100) CHARACTER SET 'utf8' NOT NULL,
  `alias` varchar(100) NOT NULL,
  `body` mediumtext CHARACTER SET 'utf8',
  `note` varchar(255) DEFAULT NULL,
  `published` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `access` smallint(5) unsigned NOT NULL DEFAULT '0',
  `language` char(7) DEFAULT NULL,
  `metadesc` varchar(512) DEFAULT NULL,
  `metakey` varchar(255) DEFAULT NULL,
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `created_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `modified_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `params` mediumtext,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__events_team_players` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `team` int(10) unsigned NOT NULL,
  `user` int(10) unsigned NOT NULL,
  `status` int(11) NOT NULL,
  `note` text CHARACTER SET 'utf8',
  `params` mediumtext CHARACTER SET 'utf8',
  PRIMARY KEY (`id`)
);