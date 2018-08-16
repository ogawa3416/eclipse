<?php
/**
* @Copyright Copyright (C) 2010 dhtmlx.com
* @Copyright Copyright (C) 2010 Groon solutions (by modified portion)
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

/**
 This file is part of BSScheduler for Joomla.

    BSScheduler for Joomla is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    BSScheduler for Joomla is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with BSScheduler for Joomla.  If not, see <http://www.gnu.org/licenses/>.
**/

// no direct access
defined('_JEXEC') or die;
//define( 'DS', DIRECTORY_SEPARATOR );
define('JPATH_BASE', dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..');
require_once ( JPATH_BASE .DIRECTORY_SEPARATOR.'configuration.php' );
require_once ( JPATH_BASE .DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'defines.php' );
require_once ( JPATH_BASE .DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'framework.php' );
$mainframe =& JFactory::getApplication ('site');
$mainframe->initialise ();
$mainframe->route ();


function makeIcal() {
	$db =& JFactory::getDBO();

	$query = 'SET NAMES utf8';
	$db->setQuery( $query );
	$db->execute();
	
	$cfg = new JConfig;
	$prefix = $cfg->dbprefix;
	
	$tableName = $prefix."scheduler_options";
	$query = "SELECT `value` FROM `".$tableName."` WHERE `name`='table_name'";
	$db->setQuery($query);
	$tableName = $prefix.$db->loadResult();

	if (isset($_GET['oncoming'])) {
		$query = "SELECT `event_id`, `start_date`, `end_date`, `text` FROM `".$tableName."` WHERE `rec_type`='' AND `event_pid`='0' AND `end_date` > NOW()";
	} else {
		$query = "SELECT `event_id`, `start_date`, `end_date`, `text` FROM `".$tableName."` WHERE `rec_type`='' AND `event_pid`='0'";
	}
	
	$db->setQuery($query);
	$events = $db->loadObjectList();

	$offset = -1*$cfg->offset;
	if ($offset > 0) {
		$offset = "+".$offset;
	}
	$timeZone = 'Etc/GMT'.$offset;
	$blogName = $cfg->sitename;

	$ics = "BEGIN:VCALENDAR\r\nPRODID: BSScheduler\r\nVERSION: 2.0\r\nCALSCALE:GREGORIAN\r\nMETHOD:PUBLISH\r\nX-WR-CALNAME:".$blogName."\r\nX-WR-TIMEZONE:".$timezone."\r\n";

	for ($i = 0; $i < count($events); $i ++) {
		$event = $events[$i];
		$start_date = makeTime($event->start_date);
		$end_date = makeTime($event->end_date);
		$timest_end = date_parse($event->end_date);
		$timest_end = mktime($timest_end['hour'], $timest_end['minute'], $timest_end['second'], $timest_end['month'], $timest_end['day'], $timest_end['year']);

		if ($timest_end < time()) {
			$status = 'CANCELLED';
		} else {
			$status = 'CONFIRMED';
		}

		$dsc = $event->text;
		$uid = md5($event->event_id.time());
		$ics .= "BEGIN:VEVENT\r\nDTSTART:".$start_date."\r\nDTEND:".$end_date."\r\nUID:".$uid."\r\nDESCRIPTION:".$dsc."\r\nSTATUS:".$status."\r\nSUMMARY:".$dsc."\r\nTRANSP:OPAQUE\r\nEND:VEVENT\r\n";
	}


	$ics .= "END:VCALENDAR";
	header('Content-type: text/calendar; charset=utf-8');
	header("Content-Disposition: attachment; filename=BSScheduler.ics");
	echo $ics;
}


function makeTime($date) {
	$date = str_replace("-", "", $date);
	$date = str_replace(":", "", $date);
	$date = str_replace(" ", "T", $date);
	return $date;
}

makeIcal();

?>