--
-- Install SQL Script
--
-- @version		$Id: bs_install.sql 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.0 $
-- @package		GROON UGMS
-- @subpackage	Blogstone
-- @copyright	Copyright (C) 2009-2010 GROON solutions. All rights reserved.
-- @license		GROON solutions.
--

CREATE TABLE IF NOT EXISTS `#__bs_coholiday` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `holiday` date NOT NULL,
  `holidayname` varchar(255) NOT NULL,
  `manual` tinyint(4) NOT NULL DEFAULT '0',
  `holiday_stat` tinyint(4) NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `createdby` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `holiday_idx` (`holiday`)
) ENGINE=InnoDB ;
CREATE TABLE IF NOT EXISTS `#__bs_division` (
              `divcode` varchar(16) NOT NULL,
              `div_stat` tinyint(4) NOT NULL default '0',
              `divname` varchar(255) NOT NULL,
              `divname_s` varchar(16) NOT NULL,
              `company` varchar(255),
              `divaddr` varchar(255),
              `divzip` varchar(14),
              `divtel` varchar(14),
              `divtmpl` varchar(255),
              `modified` datetime NOT NULL default '0000-00-00 00:00:00',
              `modified_by` int(11) NOT NULL default '0',
              `created` datetime NOT NULL default '0000-00-00 00:00:00',
              `createdby` int(11) NOT NULL default '0',
              PRIMARY KEY  (`divcode`)
) ENGINE=InnoDB ;
CREATE TABLE IF NOT EXISTS `#__bs_accontrol` (
              `com_group` varchar(50) NOT NULL,
              `divkey` varchar(16) NOT NULL,
              `ondiv` varchar(255) ,
              `onuser` varchar(255) ,
              `modified` datetime NOT NULL default '0000-00-00 00:00:00',
              `modified_by` int(11) NOT NULL default '0',
              `created` datetime NOT NULL default '0000-00-00 00:00:00',
              `createdby` int(11) NOT NULL default '0',
              PRIMARY KEY  (`com_group`,`divkey`)
) ENGINE=InnoDB ;
CREATE TABLE IF NOT EXISTS `#__bs_users_detail` (
  `userid` int(11) NOT NULL ,
  `isbusiness` varchar(8) NOT NULL,
  `divcode` varchar(16) NOT NULL,
  `name1` varchar(64) NOT NULL,
  `name2` varchar(64) NOT NULL,
  `teleno` varchar(16) NOT NULL,
  `company` varchar(64) NOT NULL,
  `employeeno` varchar(16) NOT NULL,
  `zipcode` varchar(16) NOT NULL,
  `address` varchar(16) NOT NULL,
  `company_hp` varchar(255) NOT NULL,
  PRIMARY KEY (`userid`),
  KEY `divcode_hash_idx` (`divcode`),
  KEY `name1name2_hash_idx` (`name1`,`name2`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;
