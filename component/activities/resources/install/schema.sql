
-- --------------------------------------------------------
--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
    `activities_activity_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `uuid` VARCHAR(36) NOT NULL DEFAULT '' UNIQUE,
    `application` VARCHAR(10) NOT NULL DEFAULT '',
    `package` VARCHAR(50) NOT NULL DEFAULT '',
    `name` VARCHAR(50) NOT NULL DEFAULT '',
    `action` VARCHAR(50) NOT NULL DEFAULT '',
    `row` BIGINT NOT NULL DEFAULT '0',
    `title` VARCHAR(255) NOT NULL DEFAULT '',
    `status` varchar(100) NOT NULL,
    `created_on` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` INT(11) NOT NULL DEFAULT '0',
    `ip` varchar(255) DEFAULT NULL,
	PRIMARY KEY(`activities_activity_id`),
	KEY `idx-ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;