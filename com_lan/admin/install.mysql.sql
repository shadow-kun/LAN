CREATE TABLE IF NOT EXISTS `#__lan_event` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` INT(11) UNSIGNED NOT NULL,
  `title` VARCHAR(100) CHARACTER SET 'utf8' NOT NULL,
  `alias` VARCHAR(100) NOT NULL,
  `description` MEDIUMTEXT NULL DEFAULT NULL,
  `note` VARCHAR(255) NULL DEFAULT NULL,
  `published` INT(11) NOT NULL DEFAULT 0,
  `ordering` INT(11) NOT NULL DEFAULT 0,
  `access` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `language` CHAR(7) NULL,
  `metadesc` VARCHAR(512) NULL,
  `metakey` VARCHAR(255) NULL,
  `checked_out` INT(11) UNSIGNED NOT NULL DEFAULT 0,
  `checked_out_time` DATETIME NOT NULL DEFAULT 0000-00-00 00:00:00,
  `created_user_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
  `created_time` DATETIME NOT NULL DEFAULT 0000-00-00 00:00:00,
  `modified_user_id_copy` INT(11) UNSIGNED NOT NULL DEFAULT 0,
  `modified_time` DATETIME NOT NULL DEFAULT 0000-00-00 00:00:00,
  `params` MEDIUMTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__lan_teams` (
	`team_id` int(11) NOT NULL AUTO_INCREMENT,
	`team_name` varchar(45) NOT NULL,
	`team_description` blob DEFAULT NULL,
	`event_id` int(11),
	PRIMARY KEY (`team_id`)
);

CREATE TABLE IF NOT EXISTS `#__lan_team_members` (
	`team_id` int(11) NOT NULL,
	`userid` int(11) NOT NULL,
	`team_member_id` int(11) NOT NULL AUTO_INCREMENT,
	`approved` BOOLEAN NOT NULL DEFAULT FALSE,
	PRIMARY KEY (`team_member_id`)
);

CREATE TABLE IF NOT EXISTS `#__lan_permissions` (
	`permission_id` int(11) NOT NULL,
	`permission_name` varchar(63) NOT NULL,
	PRIMARY KEY (`permission_id`)
);

CREATE TABLE IF NOT EXISTS `#__lan_settings` (
	`id` int(11) UNSIGNED NOT NULL,
	`name` varchar(45) NOT NULL,
	`value` mediumtext DEFAULT NULL,
	PRIMARY KEY (`id`)
);
	

