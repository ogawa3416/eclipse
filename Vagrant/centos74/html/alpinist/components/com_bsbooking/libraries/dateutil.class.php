<?php
/**
 * BsBooking component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		BsAlpinist
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2011 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: dateutil.class.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;

class DateUtil
{
	/**
	* Formats number of minutes past midnight into a readable string and optionally adjust for timezone
	* @param double $time time to convert in minutes
	* @param bool $adjust if the time should be adjusted for timezone
	* @param int $offset the timezone offset to use
	* @return string time in 12 hour time
	*/
	static function formatTime($time, $adjust = true, $timeFormat=24, $offset = null) {
		
		if ($adjust) {
			$time = DateUtil::getAdjustedMinutes($time, false, $offset);
		}

		// Set up time array with $timeArray[0]=hour, $timeArray[1]=minute
		// If time does not contain decimal point
		// then set time array manually
		// else explode on the decimal point
		$hour = intval($time / 60);
		$min = $time % 60;
		if ($timeFormat == 24) {
			$a = '';									// AM/PM does not exist
			if ($hour < 10) $hour = '0' . $hour;
		}
		else {
			$a = ($hour < 12 || $hour == 24) ? JText::_('COM_BSBOOKING_AM') : JText::_('COM_BSBOOKING_PM');// Set am/pm
			if ($hour > 12) $hour = $hour - 12;			// Take out of 24hr clock
			if ($hour == 0) $hour = 12;					// Don't show 0hr, show 12 am
		}
		// Set proper minutes (the same for 12/24 format)
		if ($min < 10) 
		{ 
			$min = 0 . $min;
		}
		return $hour . ':' . $min . $a;
	}


	/**
	* Convert timestamp to date format and adjust for timezone
	* @param string $date timestamp
	* @param string $format format to put datestamp into
	* @param bool $adjust if the time should be adjusted for timezone
	* @param int $offset the timezone offset to use
	* @return string date as $format or as default format
	*/
	static function formatDate($date, $format = '', $adjust = true, $offset = null) {
		global $dates;

		if ($adjust) {
			$date = DateUtil::getAdjustedTime($date, null, false, $offset);
		}

		if (empty($format)) {
			$format = $dates['general_date'];
		}
		return strftime($format, $date);
	}


	/**
	* Convert UNIX timestamp to datetime format and adjust for timezone
	* @param string $ts MySQL timestamp
	* @param string $format format to put datestamp into
	* @return string date/time as $format or as default format
	*/
	static function formatDateTime($ts, $format = '', $adjust = true) {
        $timeFormat = 24;
        $dateFormat = '%Y-%m-%d';
		if ($adjust) {
			$ts = DateUtil::getAdjustedTime($ts);
		}

		if (empty($format)) {
			$format = $dateFormat . ' ' . (($timeFormat == 24) ? '%H' : '%I') . ':%M:%S' . (($timeFormat == 24) ? '' : ' %p');
		}
		return strftime($format, $ts);
	}

	/**
	* Formats a timezone-adjusted timestamp for a reservation with this date and time
	* @param int $res_ts the reservation start_date or end_date timestamp
	* @param int $res_time the reservation starttime or endtime as minutes
	* @param string $format the PHP format string for the resulting date
	* @return the adjusted and formatted timestamp for the reservation
	*/
	static function formatReservationDate($res_ts, $res_time, $format = '', $format_key = '', $offset = null) {
		global $conf;
		global $dates;

//		$start_ts = $res_ts + (60 * $res_time);
//		$res_ts = DateUtil::getAdjustedTime($start_ts, null, false, $offset);

		return JHTML::date($res_ts);
	}

	/**
	* Gets the timezone adjusted timestamp for the current user
	* @param int $timestamp the timestamp to adjust
	* @param int $res_time the reservation starttime or endtime as minutes
	* @param bool $to_server_time if this is going to server time or user time
	* @return the timezone adjusted timestamp for the current user, or the server timestamp if user is not logged in
	*/
	static function getAdjustedTime($timestamp, $res_time = null, $to_server_time = false, $offset = null) {
		$hourOffset = $offset != null ? $offset : DateUtil::getHourOffset($to_server_time);
		if ($hourOffset == 0) {
			return $timestamp;
		}

		if (!empty($res_time)) {
			$timestamp += ($res_time + (60 * $res_time));
		}

		return $timestamp + 3600 * $hourOffset;
	}

	/**
	* Gets the timezone adjusted datestamp for the current user with 0 hour/minute/second
	* @param int $timestamp the timestamp to adjust
	* @param int $res_time the reservation starttime or endtime as minutes
	* @param bool $to_server_time if this is going to server time or user time
	* @return the timezone adjusted timestamp for the current user, or the server timestamp if user is not logged in
	*/
	static function getAdjustedDate($timestamp, $res_time = null, $to_server_time = false) {
		$tmp = getdate(DateUtil::getAdjustedTime($timestamp, $res_time, $to_server_time));
		return mktime(0,0,0, $tmp['mon'], $tmp['mday'], $tmp['year']);
	}

	/**
	* Gets the user selected time and converts it into the server stored timezone
	* @param int $datestamp the datestamp to adjust
	* @param int $minutes number of minutes past midnight
	*/
	static function getServerTime($datestamp, $minutes = null) {
		if (DateUtil::getHourOffset() == 0) {
			$date = $datestamp;
			$time = $minutes;
		}
		else {
			$date = DateUtil::getAdjustedDate($datestamp, $minutes, true);
			$time = DateUtil::getAdjustedMinutes($minutes, true);
		}

		return new ReservationTime($date, $time);
	}

	/**
	* Gets the current hour, adjusted for timezone
	* @param int $hour the 24 hour time to adjust
	* @return the 24-hour adjusted hour
	*/
	static function getAdjustedHour($hour) {
		return ($hour + DateUtil::getHourOffset() + 24)%24;
	}

	/**
	* Returns the timezone adjusted number of minutes past midnight
	* @param int $minutes minutes to adjust
	* @return the timezone adjusted number of minutes past midnight
	*/
	static function getAdjustedMinutes($minutes, $to_server_time = false, $offset = null) {
		$hourOffset = $offset != null ? $offset : DateUtil::getHourOffset($to_server_time);
		
		return ($minutes + (60 * $hourOffset + 1440)) % 1440;
	}

	/**
	* Gets the hourOffset for the currently logged in user or 0 if they are not logged in
	* @return the hour offset between user timezone and server timezone
	*/
	static function getHourOffset($to_server_time = false) {
        $user = JFactory::getUser();
        if ( $user->get('guest') ) return 0;
        
        $config = JFactory::getConfig();
        $zone = $config->get('offset');
        $tmzone = new DateTimeZone($zone);
		$dtm = new DateTime("now", $tmzone);
        $siteOffset = $tmzone->getOffset($dtm) / 3600;

        $userOffset = $user->getParam('timezone', 0);
        return $siteOffset - $userOffset;
	}

	/**
	* Convert minutes to hours/minutes
	* @param int $minutes minutes to convert
	* @return string version of hours and minutes
	*/
	static function minutesToHours($minutes) {
		if ($minutes == 0) {
			return '0 ' . JText::_('COM_BSBOOKING_HOURS');
		}

		$hours = (intval($minutes / 60) != 0) ? intval($minutes / 60) . ' ' . JText::_('COM_BSBOOKING_HOURS') : '';
		$min = (intval($minutes % 60) != 0) ? intval($minutes % 60) . ' ' . JText::_('COM_BSBOOKING_MINUTES') : '';
		return ($hours . ' ' . $min);
	}

	/**
	* Gets the hour part from the number of minutes past midnight
	* @param $minutes the number of minutes past midnight
	* @return the string value of the hour part in 24 hour time
	*/
	static function getHours($minutes) {
		$hour = (intval($minutes / 60) != 0) ? intval($minutes / 60) : 0;
		return ($hour < 10) ? "0$hour" : $hour;
	}

	/**
	* Gets the hour part from the number of minutes past midnight
	* @param $minutes the number of minutes past midnight
	* @return the string value of the hour part in 24 hour time
	*/
	static function getMinutes($minutes) {
		$min = (intval($minutes % 60) != 0) ? intval($minutes % 60) : 0;
		return ($min < 10) ? "0$min" : $min;
	}
}
?>