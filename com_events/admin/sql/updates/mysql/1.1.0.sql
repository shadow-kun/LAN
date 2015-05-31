CREATE TABLE IF NOT EXISTS `#__events_shop_stores` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
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

CREATE TABLE IF NOT EXISTS `#__events_shop_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
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
  `amount` decimal(10,2) not null DEFAULT '0.00',
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__events_shop_item_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
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
  `items` mediumtext,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__events_shop_orders` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(11) unsigned NOT NULL DEFAULT '0',
  `amount` decimal(10,2) not null DEFAULT '0.00',
  `status` int(11) NOT NULL DEFAULT '0',
  `note` varchar(255) DEFAULT NULL,
  `items` mediumtext,
  `params` mediumtext,
  PRIMARY KEY (`id`)
);

