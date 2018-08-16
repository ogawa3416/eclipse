-- --------------------------------------------------------

--
-- Table structure for table `#__bsbooking_reservations`
--

CREATE TABLE IF NOT EXISTS `#__bs_reservations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) unsigned NOT NULL COMMENT 'resource id',
  `schedule_id` int(11) unsigned NOT NULL COMMENT 'schedule id',
  `start_date` int(11) NOT NULL DEFAULT '0',
  `end_date` int(11) NOT NULL DEFAULT '0',
  `start_time` int(11) NOT NULL,
  `end_time` int(11) NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'reserved by (user id)',
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  `reserved_for` int(11) DEFAULT NULL COMMENT 'reserver for (user id)',
  `parent_id` int(11) DEFAULT '0',
  `summary` text,
  `allow_participation` smallint(6) NOT NULL DEFAULT '0',
  `allow_anon_participation` smallint(6) NOT NULL DEFAULT '0',
  `members` text,
  PRIMARY KEY (`id`),
  KEY `res_machid` (`resource_id`),
  KEY `res_scheduleid` (`schedule_id`),
  KEY `reservations_startdate` (`start_date`),
  KEY `reservations_enddate` (`end_date`),
  KEY `res_startTime` (`start_time`),
  KEY `res_endTime` (`end_time`),
  KEY `res_created` (`created`),
  KEY `res_modified` (`modified`),
  KEY `res_parentid` (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Store reservation data' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__bsbooking_reservation_division`
--

CREATE TABLE IF NOT EXISTS `#__bs_reservation_division` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) NOT NULL,
  `divcode` varchar(16) NOT NULL,
  `can_edit` tinyint(4) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `createdby` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `reservation_id` (`resource_id`,`divcode`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Defines whether the reservation division' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__bsbooking_resources`
--

CREATE TABLE IF NOT EXISTS `#__bs_resources` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `schedule_id` int(11) NOT NULL,
  `title` varchar(75) NOT NULL,
  `location` varchar(250) DEFAULT NULL,
  `divcode` varchar(16) DEFAULT NULL,
  `rphone` varchar(16) DEFAULT NULL,
  `notes` text,
  `status` char(1) NOT NULL DEFAULT 'a',
  `min_res` int(11) NOT NULL COMMENT 'Minimum reservation length for this resource',
  `max_res` int(11) NOT NULL COMMENT 'Maximum reservation length for this resource',
  `auto_assign` smallint(6) DEFAULT '0',
  `need_approval` tinyint(3) NOT NULL DEFAULT '0' COMMENT 'Need admin approval or not',
  `allow_multi` smallint(6) DEFAULT '1' COMMENT 'Allow multiple day reservation (not same as recur. )',
  `max_participants` int(11) DEFAULT '0',
  `min_notice_time` int(11) NOT NULL DEFAULT '0' COMMENT 'hours prior to start time',
  `max_notice_time` int(11) NOT NULL DEFAULT '0' COMMENT 'hours from current time',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `rs_scheduleid` (`schedule_id`),
  KEY `rs_name` (`title`),
  KEY `rs_status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__bsbooking_schedules`
--

CREATE TABLE IF NOT EXISTS `#__bs_schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `day_start` int(11) NOT NULL DEFAULT '480' COMMENT 'time start, default = 8:00',
  `day_end` int(11) NOT NULL DEFAULT '1200' COMMENT 'time end for reservation, default=20:00',
  `time_span` tinyint(3) NOT NULL DEFAULT '60' COMMENT 'minimum period for resevation in minutes',
  `time_format` tinyint(3) NOT NULL DEFAULT '24' COMMENT 'time format to show in calendar (12/24)',
  `view_days` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT 'number of days to show (1-7)',
  `show_summary` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT 'show summary text on reservation block in schedule',
  `weekday_start` tinyint(3) NOT NULL DEFAULT '1' COMMENT 'Start day in calendar, 0=sunday, 1=monday..,7=Current Date',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `admin_email` varchar(100) NOT NULL,
  `notify_admin` tinyint(3) NOT NULL DEFAULT '0' COMMENT 'Notify admin on reservation made or change',
  `published` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
