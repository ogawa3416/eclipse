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
 * @version		$Id: schedule.class.php BsAlpinist ver.2.5.0 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;
require_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php');
require_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'summary.class.php');
jimport('joomla.error.log');

class BsbookingSchedule extends JObject
{
	var $_date = array();
    var $resources = array();		// resources belonging to this schedule
    var $reservations = array();	// reservation made	
    var $user;
    var $scheduleType;
    var $scheduleId;
    var $viewDays;      //view_days
    var $startDay;      //day_start
    var $endDay;        //day_end
    var $timeSpan;      //time_span
    var $timeFormat;    //time_format
    var $weekdayStart;
    var $showSummary;
    var $title;
    var $admin;
    var $isValid = false;
    var $divcode;
	var $holidays = array();

	function __construct($scheduleId, $scheduleType=ALL )
	{

		$app = JFactory::getApplication();
		$input = $app->input;

		$this->scheduleId 	= (int)$scheduleId;
		$this->scheduleType = $scheduleType;
		
		$this->db = JFactory::getDBO();
		$sql = "SELECT * FROM #__bs_schedules WHERE id = ".$this->scheduleId;
		$this->db->setQuery( $sql );
		$_schedule = $this->db->loadObject();
		if (empty($_schedule)){
            JError::raise(E_WARNING, '500', JText::_('COM_BSBOOKING_SCHEDULE_DELETED') );
            $this->setError(JText::_('COM_BSBOOKING_SCHEDULE_DELETED'));
            return false;
		}

		$this->viewDays = $_schedule->view_days;
		$this->startDay = $_schedule->day_start;
		$this->endDay = $_schedule->day_end;
		$this->timeSpan = $_schedule->time_span;
		$this->timeFormat = $_schedule->time_format;
		$this->weekdayStart = $_schedule->weekday_start;
        $this->showSummary = $_schedule->show_summary;
		$this->title = $_schedule->title;
		
		$this->_date = $this->_get_date_vars();
		
		$where_add = '';
		$this->divcode = $input->get('divcode');
		$my = JFactory::getUser();
		// get user division
		if( !$my->id ) {
            JError::raise(E_WARNING, '500', JText::_('COM_BSBOOKING_SCHEDULE_NOTLOGIN') );
            return false;
		}

		$this->setHolidays();

		$query = ' SELECT a.divcode'
			.' FROM #__bs_division a,#__bs_users_detail b '
			.' WHERE b.userid = '.$my->id
			.' AND b.divcode = a.divcode AND a.div_stat = 1 ' 
		;
		$this->db->setQuery( $query );
		$my_divcode = $this->db->loadObject();

		if( $this->divcode == '0' ) {
			$where_add = '';
		} else if( $this->divcode ) {
			$where_add = ' AND a.divcode = '.$this->db->Quote($this->divcode).' ';
		} else {
			$apparams = $app->getParams('com_bsbooking');
			if( !$apparams->get('alldivision',1) ) {

				if( $my_divcode->divcode ) {
					$this->divcode = $my_divcode->divcode;
					$where_add = ' AND a.divcode = '.$this->db->Quote($my_divcode->divcode).' ';
				}
			} else {
				$where_add = '';
			}
		} 

  		$sql = "SELECT a.id, a.title, a.status, a.need_approval, a.min_notice_time, a.max_notice_time, a.ordering "
			."\n FROM #__bs_resources a,#__bs_reservation_division b "
			."\n WHERE a.schedule_id = ".$this->scheduleId." AND a.published=1 ".$where_add
			."\n AND a.id = b.resource_id AND b.can_edit = 1 AND b.divcode=".$this->db->Quote($my_divcode->divcode)
			."\n ORDER BY a.ordering ASC";
		$this->db->setQuery( $sql );
        $this->resources = $this->db->loadObjectList();
        
        $sql = "SELECT id FROM #__bs_resources WHERE schedule_id = ".$this->scheduleId." AND published=1 ORDER BY ordering ASC";
        $this->db->setQuery( $sql );
        $resource_ids = $this->db->loadColumn();

        $this->reservations = BsbookingHelper::getReservations($this->_date['firstDayTs'], $this->_date['lastDayTs'],  $resource_ids, ALL);
	}
	
	function render($params)
	{
	    $app = JFactory::getApplication();
		// Break first day we are viewing into an array of date pieces
  		$temp_date = getdate($this->_date['firstDayTs']);
	 	// Get the headers (same for all tables)
        $hour_header = BsbookingHelper::getHourHeader($this->_get_time_array(), $this->startDay, $this->endDay, $this->timeSpan); 
        $this->print_color_key($params);
        $this->print_date_span($this->_date, $this->title);
        if ($params->get('topNavigation')==1) {
            BsbookingHelper::print_jump_links($this, false);
        }

		// Repeat this for each day we need to show
        for ($dayCount = 0; $dayCount < $this->viewDays; $dayCount++) 
		{
            // Timestamp for whatever day we are currently viewing
            $this->_date['current'] = mktime(0,0,0, $temp_date['mon'], $temp_date['mday'] + $dayCount, $temp_date['year']);
            echo BsbookingHelper::getStartDayTable($this->getDisplayDate(), $hour_header, $this->_date['now'] == $this->_date['current'] ,$this->holidays,$this->_date['current']);    // Start the table for this day
            $this->print_reservations($params);    // Print reservations for this day
            echo BsbookingHelper::getEndDayTable();  // End the table for this day
        }
	}
	
 	/**
    * Sets up all date variables needed in the scheduler
    * @param none
    * @return array of all needed date variables
    */
    function _get_date_vars() {
        $default = false;

        $dv = array();
        
		$app = JFactory::getApplication();
		$input = $app->input;

        // For Back, Current, Next Week clicked links
        // pull values into an array month,day,year
        $date = $input->get('date', null);
        if (!empty($date)) {
        	list($year, $month, $day) = explode('-', $date);
        }	

		//$jumpDay = $input->get('jumpDay', null);
		//$jumpMonth = $input->get('jumpMonth', null);
		//$jumpYear = $input->get('jumpYear', null);
		//$jumpFrom = $input->get('jumpFrom', null);
		
		$config = JFactory::getConfig();
		$now = JFactory::getDate();
		$now->setTimeZone(new DateTimeZone($config->get('offset')));
		$nowyear = $now->format( "Y",true );
		$nowmonth = $now->format( "m",true );
		$nowday = $now->format( "d",true );
			
        // Set date values if a date has been passed in (these will always be set to a valid date)
        if ( !empty($date) ) 
		{
            $dv['month']  = date('m', mktime(0,0,0,$month,1));
            $dv['day']    = date('d', mktime(0,0,0, $dv['month'], $day));
            $dv['year']   = date('Y', mktime(0,0,0, $dv['month'], $dv['day'], $year));
        }
        else {
            // Else set values to user defined starting day of week
/******** 20130601 *****
            $d = getdate();
            $dv['month']  = $d['mon'];
            $dv['day']    = $d['mday'];
            $dv['year']   = $d['year'];
*********/
			$dv['year'] = $nowyear;
			$dv['month'] = $nowmonth;
			$dv['day'] = $nowday;
            $default = true;
        }

        // Make timestamp for today's date

        $dv['todayTs'] = mktime(0,0,0, $dv['month'], $dv['day'], $dv['year']);

        // Get proper starting day, 0=Sunday, 1=Monday
        $dayNo = date('w', $dv['todayTs']);

        if ($default) {
            // weekdayStart == 7 is current date
        //    if ($this->weekdayStart < 7)
        //        $dv['day'] = $dv['day'] - ($dayNo - $this->weekdayStart); // Make sure week starts on correct day
        }
        // If default view and first day has passed, move up one week
        // if ($default && (date(mktime(0,0,0,$dv['month'], $dv['day'] + $this->viewdays, $dv['year'])) <= mktime(0,0,0)))

        $dv['firstDayTs'] = mktime(0,0,0, $dv['month'], $dv['day'], $dv['year']);
//		$date_parts = getdate();

        // Make timestamp for last date
        // by adding # of days to view minus the day of the week to $day
        $dv['lastDayTs'] = mktime(0,0,0, $dv['month'], ($dv['day'] + $this->viewDays - 1), $dv['year']);
        $dv['current'] = $dv['firstDayTs'];
        $dv['now'] = mktime(0,0,0,$nowmonth,$nowday,$nowyear);

        return $dv;
    }
    
    /**
    * Get associative array of available times and rowspans
    * This function computes and returns an associative array
    * containing a timezone adjusted time value and it's rowspan value as
    * $array[time] => rowspan
    * @param none
    * @return array of time value and it's associated rowspan value
    * @global $conf
    */
    function _get_time_array() {

        $startDay = $startingTime = $this->startDay;
        $endDay   = $endingTime   = $this->endDay;
        $interval = $this->timeSpan;
        $timeHash = array();

        // Compute the available times
        $prevTime = $startDay;

        if ( (($startDay % 60) != 0) && ($interval < 60) ) {
            $time = DateUtil::formatTime($startDay, false, $this->timeFormat);
            
            $timeHash[$time] = intval((60-($startDay%60))/$interval);
            $prevTime += $interval*$timeHash[$time];
        }

        while ($prevTime < $endingTime) {
            if ($interval < 60) {
            	$time = DateUtil::formatTime($prevTime, false, $this->timeFormat ); 

                $timeHash[$time] = intval(60 / $interval);
                $prevTime += 60;        // Always increment by 1 hour
            }
            else {
                $colspan = 1;           // Colspan is always 1
               	$time = DateUtil::formatTime( $prevTime, false, $this->timeFormat ); 
                
				$timeHash[$time] = $colspan;
                $prevTime += $interval;
            }
        }
        return $timeHash;
    }
    
    function getDisplayDate()
    {

		return DateUtil::formatReservationDate($this->_date['current'], $this->startDay, null);
    }

	/**
	* Whether the reservation link is shown/clickable
	* @param bool $viewable_date if the date is viewable
	* @param array $current_record the currently iterated machid record
	* @return if this reservation link is available to view
	*/
    static function canShowReservation($viewable_date, $current_record) {	

		$is_active = ($current_record->status == 'a');
		$has_permission = true; //$this->user->has_perm($current_record['machid']);
		
		return ( $viewable_date && $is_active && $has_permission );
    }

	/**
	* Whether the reservation link is shown/clickable on this date
	* @param int $current_date the current datestamp
	* @param int $min_notice the minimum number of notice hours for the current resource
	* @param int $max_notice the maximum number of notice hours for the current resource
	* @return if this reservation link is available to view
	*/
    static function isViewableDate($current_date, $min_notice, $max_notice) {
    	$config = JFactory::getConfig();
		$now = JFactory::getDate();
		$now->setTimeZone(new DateTimeZone($config->get('offset')));
		$nowyear = $now->format( "Y",true );
		$nowmonth = $now->format( "m",true );
		$nowday = $now->format( "d",true );
			
		$min_days = intval($min_notice / 24);

		$min_date = mktime(0,0,0, $nowmonth, $nowday + $min_days,$nowyear);
		
		if ($current_date < $min_date)
		{
			return false;
		}

		if ($max_notice != 0) {
			$max_days = ceil($max_notice / 24);
	
			$max_date = mktime(0,0,0, $nowmonth, $nowday + $max_days,$nowyear);
	
			if ($current_date > $max_date)
			{
				return false;
			}
		}

		return true;
    }

    /**
    * Print out the reservations for each resource on each day
    * @param none
    */
    function print_reservations($params) {
        if (!$this->resources) return;
        $current_date = $this->_date['current']; // Store current_date so we dont have to access the array every time

        // Repeat this whole process for each resource in the database (in schedule)
        for ($count = 0; $count < count($this->resources); $count++) {
            $prevTime = $this->startDay;        // Previous time holder
            $totCol = intval(($this->endDay - $this->startDay) / $this->timeSpan);    // Total columns holder
			$cur_resource = $this->resources[ $count ];

            // Store info about this current resource in local vars
            $id = $cur_resource->id;
            $name = $cur_resource->title;
            $status = $cur_resource->status;

            $shown = false;        // Default resource visiblilty to not shown
			$viewable_date = $this->isViewableDate($current_date, $cur_resource->min_notice_time, $cur_resource->max_notice_time);

            // If the date has not passed, resource is active and user has permission,
            //  or the user is the admin allow reservations to be made
            $shown = $this->canShowReservation($viewable_date, $cur_resource);

			if ($this->scheduleType == READ_ONLY)
			{
				$color = 'ro' . ($count%2);
			}
			else
			{
            	$color = 'r' . ($count%2);
			}
            BsbookingHelper::print_name_cell($current_date, $id, $name, $shown, $this->scheduleId, $color);

            $index = $id; //resource id
            if (isset($this->reservations[$index])) {

                for ($i = 0; $i < count($this->reservations[$index]); $i++) {
                    /** FIXED by Prasit Gebsaap: 
                        For PHP5, we have to clone it, otherwise it will overwrite orginal reservation properties **/
                    $rs = clone $this->reservations[$index][$i];
                    // If it doesnt start sometime today, end sometime today, or surround today, just skip over it
                    if (
                        !(($rs->start_date >= $current_date && $rs->start_date <= $current_date)
                        || ($rs->end_date >= $current_date && $rs->end_date <= $current_date)
                        || ($rs->start_date <= $current_date && $rs->end_date >= $current_date))
                       ) {
                        continue;
                    }
                    // Just skip the reservation if the ending date/time is todays start time
                    if ($rs->end_date == $current_date && $rs->end_time == $this->startDay) { continue; }

                    // If the reservation starts before or ends after todays date, just pretend it ends today so it shows correctly
                    if ($rs->start_date < $current_date) {
                        $rs->start_time = $this->startDay;
                    }
                    if ($rs->end_date > $current_date) {
                        $rs->end_time = $this->endDay;
                    }

                    // Print out row of reservations
                    $thisStart = $rs->start_time;
                    $thisEnd = $rs->end_time;

                    if ($thisStart < $this->startDay && $thisEnd > $this->startDay)
                        $thisStart = $this->startDay;
                    else if ($thisStart < $this->startDay && $thisEnd <= $this->startDay)
                        continue;    // Ignore reservation, its off the schedule

                    if ($thisStart < $this->endDay && $thisEnd > $this->endDay)
                        $thisEnd = $this->endDay;
                    else if ($thisStart >= $this->endDay && $thisEnd > $this->startDay)
                        continue;    // Ignore reservation, its off the schedule

                    $colspan = intval(($thisEnd - $thisStart) / $this->timeSpan);

                    $this->move_to_starting_col($rs, $thisStart, $prevTime, $this->timeSpan, $id, $current_date, $shown, $color);
				
					$rsvspan = $thisEnd - $thisStart;

					$this->write_reservation($params, $rs, $colspan, $viewable_date, $rsvspan, $id, $thisStart, $current_date);

                    // Set prevTime to this reservation's ending time
                    $prevTime = $thisEnd;
                }
            }
            $this->finish_row($this->endDay, $prevTime, $this->timeSpan, $id, $current_date, $shown, $color);
        }
    }
    /**
    * Return color_select for given reservation
    * @param array $rs object of reservation information
    */
    function get_reservation_colorstr($rs) {
        global $conf;
        $is_mine = false;
		$is_participant = false;
        $is_past = false;
        $color_select = 'other_res';        // Default color (if anything else is true, it will be changed)

        /*
         * We did not user reservation_users as it PHPScheduleIt, but we store owner in reserved_for field 
         */
        $my =  JFactory::getUser();
		if ($this->scheduleType != READ_ONLY) {
            if ($rs->reserved_for == $my->id) 
            /* if($rs->owner == 1)*/  {
                $is_mine = true;
                $color_select = 'my_res';
            }
//			else if ($rs->participantid != null && $rs->owner == 0) { //Will be fixed later
			else if (isset($rs->participantid) && isset($rs->owner)) { //Will be fixed later
				if ($rs->participantid != null && $rs->owner == 0) { //Will be fixed later
					$is_participant = true;
					$color_select = 'participant_res';
				}
			}
        }
		$config = JFactory::getConfig();
		$now = JFactory::getDate();
		$now->setTimeZone(new DateTimeZone($config->get('offset')));
		$nowyear = $now->format( "Y",true );
		$nowmonth = $now->format( "m",true );
		$nowday = $now->format( "d",true );
        if (mktime(0,0,0,$nowmonth,$nowday,$nowyear) > $this->_date['current']) { // If todays date is still before or on the day of this reservation
            $is_past = true;
			if ($is_mine) {
				 $color_select = 'my_past_res';
			}
			else if ($is_participant) {
				$color_select = 'participant_past_res';
			}
			else {
				$color_select ='other_past_res';
			}
        }

        return $color_select;
    }

    /**
    * Calculates and calls the template function to print out leading columns
	* @param array $rs array of reservation information
    * @param int $start starting time of reservation
    * @param int $prev previous ending reservation time
    * @param int $span time span for reservations
    * @param string $machid id of the resource on this table row
    * @param int $ts timestamp for the reservation start date
    * @param bool $clickable if this row's cells can be clicked to start a reservation
	* @param string $color class of column background
    */
    function move_to_starting_col($rs, $start, $prev, $span, $machid, $ts, $clickable, $color) {
        $cols = (($start-$prev) / $span) - 1;
		BsbookingHelper::print_blank_cols($cols, $prev, $span, $ts, $machid, $this->scheduleId, $this->scheduleType, $clickable, $color);
    }

    /**
    * Calculates and calls template function to print out trailing columns
    * @param int $end ending time of day
    * @param int $prev previous ending reservation time
    * @param int $span time span for reservations
    * @param string $machid id of the resource on this table row
    * @param int $ts timestamp for the reservation start date
    * @param bool $clickable if this row's cells can be clicked to start a reservation
	* @param string $color class of column background
    */
    function finish_row($end, $prev, $span, $machid, $ts, $clickable, $color) {
        global $conf;
        $cols = (($end-$prev) / $span) - 1;
		BsbookingHelper::print_blank_cols($cols, $prev, $span, $ts, $machid, $this->scheduleId, $this->scheduleType, $clickable, $color);
        BsbookingHelper::print_closing_tr();
    }

    /**
    * Calls template function to write out the reservation cell
    * @param object $rs object of reservation information
    * @param int $colspan column span value
	* @param bool $viewable_date if the date is clickable/viewable
    */
    function write_reservation($params,$rs, $colspan, $viewable_date, $rsvspan, $id, $tstart, $ts) {
        $is_mine = false;
        $is_past = false;
		$is_private = $params->get('privacyMode', 0) && !BsbookingHelper::isAdmin();
        $color_select = $this->get_reservation_colorstr($rs);

        if ($this->scheduleType != READ_ONLY) {
        	$user = JFactory::getUser();
            if ( ($rs->reserved_for == $user->get('id')) || ($rs->created_by == $user->get('id') ) ){
                $is_mine = true;
            }
        }

		$summary = new BsbookingSummary($rs->summary,$rs->private_flg,$rs->reserved_for);
        $summary->title = $rs->name;
		if ((bool)$params->get('prefixNameOnSummary')) {
			$summary->user_name = "{$rs->name}";
		}
		$sttime = sprintf("%02d:%02d",floor($rs->start_time/60),$rs->start_time%60);
		$endtime = sprintf("%02d:%02d",floor($rs->end_time/60) ,$rs->end_time%60);
		$summary->time = $sttime . ' - ' . $endtime;

		if ($rs->start_date == $rs->end_date){
//			$datetmp = date('Y.m.d (D)',$rs->start_date);
			$datetmp = JHTML::date($rs->start_date,'Y.m.d (D)');
			$datespan = false;
		}
		else{
			$datetmp = JHTML::date($rs->start_date,'Y.m.d (D)').' - '.JHTML::date($rs->end_date,'Y.m.d (D)');
			$datespan = true;
		}

		$summary->date = $datetmp ;

        // If this is the user who made the reservation or the admin,
        //  and time has not passed, allow them to edit it
        //  else only allow view
        $mod_view = ( ($is_mine && $viewable_date) || BsbookingHelper::isAdmin()) ? 'm' : 'v';    // To use in javascript edit/view box
        $showsummary = ($this->scheduleType != READ_ONLY || ($this->scheduleType == READ_ONLY && $params->get('readOnlySummary', 1)) && $this->showSummary && !$is_private);
        $viewable = ($this->scheduleType != READ_ONLY || ($this->scheduleType == READ_ONLY && $params->get('readOnlyDetail', 1)));
        $summary->visible = (bool)$showsummary;

		$my =  JFactory::getUser();
		$privateview = false;
		if($rs->private_flg == 1 && $rs->reserved_for != $my->id){
			$privateview = true;
		}	

		BsbookingHelper::write_reservation($params,$colspan, $color_select, $mod_view, $rs->id, $summary, $viewable, $this->scheduleType == READ_ONLY,$this->timeSpan ,$privateview ,$rsvspan ,$rs->created_by, $id, $this->scheduleId, $tstart, $ts ,$datespan);
    }

    /**
    * Prints out an error message for the user
    * @param none
    */
    static function print_error() {
        //CmnFns::do_error_box(translate('That schedule is not available.') . '<br/><a href="javascript: history.back();">' . translate('Back') . '</a>', '', false);
    }
    
    /**
    * Print out a key to identify what the colors mean
    * @param none
    */
    static function print_color_key($params) 
    {
    ?>
<table class="color-key" align="center" ><tr style="font-size: 10px; font-weight: bold; text-align: center; vertical-align: center;height: 30px;">
<td style="width: 75px; background-color:#<?php echo $params->get('my_res_color')?>; border: 0px #000000 solid;"><?php echo JText::_('MY_RESERVATIONS')?></td>
<td style="width: 75px; background-color:#<?php echo $params->get('my_past_res_color')?>; border: 0px #000000 solid;"><?php echo JText::_('MY_PAST_RESERVATIONS')?></td>
<td style="width: 75px; background-color:#<?php echo $params->get('other_res_color')?>; border: 0px #000000 solid;"><?php echo JText::_('OTHER_RESERVATIONS')?></td>
<td style="width: 75px; background-color:#<?php echo $params->get('other_past_res_color')?>; border: 0px #000000 solid;"><?php echo JText::_('OTHER_PAST_RESERVATIONS')?></td>
</tr></table>
    <?php
    }
    
    /**
    * Print out week being viewed above schedule tables
    * @param array $d array of date information about this schedule
    * @param string $title title of schedule
    */
    static function print_date_span($d, $title) 
    {
        // Print out current week being viewed
        echo '<div class="sch_title"><h3>' . JHTML::date($d['firstDayTs'],JText::_("DATE_FORMAT_LC3")) . ' - ' . JHTML::date($d['lastDayTs'],JText::_("DATE_FORMAT_LC3")) . '</h3></div>';
    }
    function getDivcode() 
    {
		return $this->divcode;
	}
	private function setHolidays()
	{
 		$_db = JFactory::getDBO();
		$query = ' SELECT holiday FROM #__bs_coholiday WHERE holiday_stat = 1 ORDER BY holiday' ;
		$_db->setQuery( $query );
		$result = $_db->loadObjectList();

		foreach ($result as $row) {
		    $this->holidays[] = $row->holiday;
		}

		return ;

	}
} 
?>