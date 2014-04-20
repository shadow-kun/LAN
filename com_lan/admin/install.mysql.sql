CREATE TABLE IF NOT EXISTS `#__lan_events` (
	`event_id` int(11) NOT NULL AUTO_INCREMENT,
	`event_name` varchar(127) NOT NULL,
	`event_description` blob DEFAULT NULL,
	PRIMARY KEY (`event_id`)
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
	

