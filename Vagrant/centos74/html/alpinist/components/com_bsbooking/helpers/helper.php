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
 * @version		$Id: helper.php BsAlpinist ver.2.5.0 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;

class BsbookingHelper 
{
	
	static function isAdmin( $uid = null )
	{
		$admin = false;
		if (empty($uid))
		{
			$user = JFactory::getUser();
		}else{
			$user = JFactory::getUser( $uid );
		}
		if( !$user->id ) {
			$admin = false;
		} else {
			if (JAccess::check($user->id, 'core.admin')) {
				$admin = true;
				
			} else {
				$admin = false;
			}
		}
		return $admin;
	}
	
    static function getUserSelectList($tagname, $attribs = '', $selected)
    {
        $dbo = JFactory::getDBO();
        $sql = "SELECT id as value, name as text FROM #__users WHERE block = 0";
        $dbo->setQuery($sql);
        $rows = $dbo->loadObjectList();
        
  		$html = JHTML::_('select.genericlist', $rows, $tagname, $attribs, 'value', 'text', $selected);
		
		return $html;   
    }
	/**
	* Formats and returns the time header of the table (it is the same for every one)
	* @param array $th array of time values and their rowspans
	* @param int $startDay starting time of day
	* @param int $endDay ending time of day
	* @param int $timespan time intervals
	* @global $conf
	*/
	static function getHourHeader( $th, $startDay, $endDay, $timespan ) 
	{	
		$header = '';

    	// Write out the available times
    	foreach ($th as $time => $cols) {
        	$header .= "<td colspan=\"$cols\">$time</td>";
   	 	}

    	// Close row, start next
    	$header .= "</tr>\n<tr class=\"scheduleTimes\">";

    	// Compute total # of cols
    	$totCol = intval(($endDay - $startDay) / $timespan);
    	$width = (80/$totCol);
    	
    	// Create the fraction hour minute marks
    	for ($x = 0; $x < $totCol; $x++)
    	{
        	$header .= "<td width=\"$width%\">&nbsp;</td>";
    	}

    	return $header;
	}
	
	/**
	* Start table for one day on schedule
	* This function starts the table for each day
	* on the schedule, printing out it's date
	* and the time value cells
	* @param string $displayDate date string to print
	*/
	static function getStartDayTable( $displayDate, $hour_header, $isCurrentDate ,$holidays,$currentdate) 
	{
		$year = date( 'Y',$currentdate);
		$month = date( 'm',$currentdate);
		$day = date( 'd',$currentdate);
		$wkd = "d_bsbook_".mb_strtolower(date( 'D',$currentdate));
		$hday ='';
		if(in_array($year.'-'.$month.'-'.$day, $holidays)){
			//add classname
			$hday = ' d_bsbook_hday';
		}
		
		$hour_header = str_replace('scheduleTimes','scheduleTimes '.$wkd.$hday,$hour_header);

		return 
			"\n".'<div class="bookingdaydiv"><table class="bookingdaytable" width="100%" border="0" cellspacing="0" cellpadding="1">
			    <tr class="tableBorder">
				   <td>
                     <table width="100%" border="0" cellspacing="1" cellpadding="0">
			             <tr class="scheduleTimes '.$wkd.$hday.'">
			                <td rowspan="2" width="20%" class="'.($isCurrentDate ? 'scheduleDateCurrent' : 'scheduleDate').'">'.$displayDate.'</td>'
                            .$hour_header.'</tr>';
	}
	
	static function getEndDayTable()
	{
		return "\n".'</table></td></tr></table></div><br />';
	}

	/**
	* Prints out the cell containing all the resource information
	* @param int $ts timestamp for the current day
	* @param int $id id of this resource
	* @param string $name name of this resource
	* @param boolean $shown whether this resource can be reserved
	* @param int $scheduleid id of the current schedule
	* @param string $color background color of row
	*/
	static function print_name_cell($ts, $id, $name, $shown, $scheduleid, $color = '') 
    {
        $url = 'index.php?option=com_bsbooking&task=reservation.add&type=r&resource_id='.$id.'&schedule_id='.$scheduleid.'&ts='.$ts;
        $color = (empty($color)) ? 'r0' : $color;

    	// Start a new row and print out resource name
    	echo "\n<tr class=\"$color\"><td class=\"resourceName\">";
  	  	// If the user is allowed to make reservations on this resource
        // then provide a link
        // Else do not
        if ($shown) {
        	$attribs = '';
            echo JHTML::link($url, $name, $attribs);
       	} else {
           	echo '<span class="inact">' . $name . '</span>';
    	}
    	// Close cell
    	echo "</td>";
	}

	/**
	* Prints out blank columns
	* @param int $cols number of columns to print out
	* @param int $start starting time of the first column printed out
	* @param int $span time span of the schedule
	* @param int $ts timestamp for the reservation start date
	* @param int $resourceid id of the resource on this table row
	* @param int $scheduleid id of the current schedule
	* @param int $scheduleType type of the current schedule
	* @param bool $clickable if this row can be clicked
	* @param string $color class of column background
	*/
	static function print_blank_cols($cols, $start, $span, $ts, $resourceid, $scheduleid, $scheduleType, $clickable, $class = '') {
		$offClass = $clickable ? '' : 'class="o"';
	
    	$js = '';
    	for ($i = 0; $i <= $cols; $i++) {
        	if ($scheduleType != READ_ONLY && $clickable) {
            	$tstart = $start + ($i * $span);
            	$tend = $tstart + $span;
            	//$js = "onclick=\"javascript:reserve('r','$machid','$ts','','$scheduleid',$is_blackout,'','',$tstart, $tend);\"";
                $url = 'index.php?option=com_bsbooking&task=reservation.add&type=r&'
                    .'resource_id='.$resourceid.'&schedule_id='.$scheduleid.'&ts='.$ts.'&tstart='.$tstart
                    .'&tend='.$tend.'&type='.$scheduleType;
                $url = JRoute::_($url,false);
                $js = "onclick=\"location.href='$url'\"";
				$js .= " ondrop=\"rsvedDrop( $resourceid,$scheduleid,$ts,$tstart,$tend);event.preventDefault();\"";
				$js .= "ondragenter=\"event.preventDefault();\"";
				$js .= "ondragover=\"event.preventDefault();\"";
        	}
        	echo "<td $offClass $js>&nbsp;</td>";
    	}
	}

	/**
	* Prints the closing tr tag
	* @param none
	*/
	static function print_closing_tr() {
    	echo "</tr>";
	}

	/**
	* Writes out the reservation cell
	* @param int $colspan column span of this reservation
	* @param string $color_select array identifier for which color to use
	* @param string $mod_view indentifying character for javascript reserve function to mod or view reservation
	* @param string $resid id of this reservation
	* @param Summary $summary summary for this reservation
	* @param string $viewable whether the user can click on this reservation and bring up a details box
	* @param int $read_only whether this is a read only schedule
	*/
	static function write_reservation($params,$colspan, $color_select, $mod_view, $resid, $summary = '', $viewable = false, $read_only = false,$tmspan=0,$privateview,$rsvspan,$created_by,$resourceid,$scheduleId,$tstart,$ts,$datespan) {
    	$tipClass = $params->get('customTooltip')?'hasCustomTip':'hasTooltip';
        $js = '';
    	$color = '#' . $params->get($color_select . '_color');
    	$text  = '#' . $params->get($color_select . '_text' );
        
        
    	$chars = ($colspan > 1) ? 4 * $colspan : 0;

    	$read_only = intval($read_only);

        $summary_text = $summary->toScheduleCell();
	
		$cellSummary = '';

//        $tipText .= $summary->toScheduleHover();
		$tipText = $summary->date . "<br>";
		$tipText .=  $summary->time ." "."{$summary->user_name}::"  . htmlspecialchars($summary->text) . "<br>";

        $defspan = 5;
        $bspan = $tmspan/15 ;
        $bwidth = $bspan*$defspan+(($bspan-1)*2) ;
		$colwidth = sprintf("%spx",($bwidth*$colspan)+(($colspan-1)*2));

        if ($viewable == true && $privateview==false) {
        	$app = JFactory::getApplication();
			$input = $app->input;
			$itemId = $input->getInt('Itemid',null);
			$append = '';
			if(isset($itemId)) $append = '&Itemid=' . $itemId;
            if ($read_only){
                $url = 'index.php?option=com_bsbooking&task=reservation.view&type=v&id='.$resid.$append;
            }else{
                $url = 'index.php?option=com_bsbooking&task=reservation.edit&type=m&id='.$resid.$append;
            }
                
            $url = JRoute::_($url,false);
            $js = "onclick=\"location.href='$url'\"";
        	if ($summary->isVisible()) {
                if ($summary_text != $summary->EMPTY_SUMMARY)
                {
			         $cellSummary = "<div class=\"inlineSummary $tipClass \" title=\"$tipText\" style=\"color:$text;background-color:$color;width:$colwidth;\">$summary_text</div>";
                }
			}
    	}
    	else {
        	if ($summary->isVisible()) {
            	$cellSummary = "<div class=\"inlineSummary $tipClass \" title=\"$tipText\" style=\"color:$text;background-color:$color;width:$colwidth;\">$summary_text</div>";
			}
    	}

		$user = JFactory::getUser();
		if($created_by == $user->id && $datespan == false){
	    	echo "<td id=\"reservid_$resid\"ondragstart=\"rsvedDragStart($resid,$rsvspan,$tmspan)\" ondrop=\"rsvedDropself($resid,$resourceid,$scheduleId,$tstart,$ts,$tmspan);event.preventDefault();\" draggable=\"true\" ondragenter=\"event.preventDefault();\" ondragover=\"event.preventDefault();\" colspan=\"$colspan\" style=\"color:$text;background-color:$color;\" $js>$cellSummary</td>";
		}
		else{
	    	echo "<td colspan=\"$colspan\" draggable=\"false\" style=\"color:$text;background-color:$color;\" $js>$cellSummary</td>";
		}

	}

	/**
	* Print out a link without creating a new Link object
	* @param string $url url to link to
	* @param string $text text of link
	* @param string $class link class
	* @param string $style inline style of link (overrides class)
	* @param string $text_on_over text to display in status bar onmouseover
	*/
	static function doLink($url=null, $text=null, $class=null, $style=null, $text_on_over=null) 
	{
				
	}
    
    /**
     * Resturn HTML select list for start/ stop hour selection.
     * @param string $tag_name tag name of HTML select list
     * @param integer $tstart minimum minute of schedule
     * @param integer $tend maximum minute of schedule
     * @param integer $time span of schedule
     * @param integer $selected selected minute
     * @param string $attrib attribute of select list  
     */
    static function getHourSelectList($tag_name, $tstart, $tend, $tspan, $selected, $attrib='')
    {
        $html='';
        $options = array();
        for ($i = $tstart; $i < $tend+$tspan; $i += $tspan)
        {
            $options[] = JHTML::_('select.option', $i, DateUtil::formatTime($i,false));
        }
        //$arr, $name, $attribs = null, $key = 'value', $text = 'text', $selected = NULL, $idtag = false, $translate = false
        $html = JHTML::_('select.genericlist', $options, $tag_name, $attrib, 'value', 'text', $selected );
        
        return $html;    
    }
    
    
    /**
    * Print links to jump to new dates
    * This function prints out the HTML links to allow users to navigate back/forward one week.
    * It also prints the form for users to jump to any given week.
    * @param object schedule to print jump link for 
    */
    static function print_jump_links( $schedule, $include_jumpl_link = false ) {
    	$app = JFactory::getApplication();
		$input = $app->input;

        $_date = $schedule->_date['firstDayTs'];
//		$viewdays = $schedule->viewdays;
        $printAllCols = ($schedule->viewDays!=7);
        $app = JFactory::getApplication();
		$url = $app->getMenu()->getActive()->link;    
        $date = getdate($_date);
        $m = $date['mon'];
        $d = $date['mday'];
        $y = $date['year'];
        $divreq = $input->getVar('divcode');
        if( isset($divreq) ) {
        	$divreq = '&divcode='.$divreq;
        }
        // Write out the previous, today and next links and the form to jump to a date
        
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td class="jump_link" align="center"><h5><a href="<?php echo JRoute::_( $url.'&date='.date('Y-m-d',mktime(0,0,0,$m, $d - 7, $y)).$divreq,false)?>"><?php echo JText::_('COM_BSBOOKING_PREV_WEEK')?></a></h5></td>
        <td class="jump_link" align="center"><h5><a href="<?php echo JRoute::_( $url.$divreq,false )?>"><?php echo  JText::_('COM_BSBOOKING_THIS_WEEK')?></a></h5></td>
        <td class="jump_link" align="center"><h5><a href="<?php echo JRoute::_( $url.'&date='.date('Y-m-d',mktime(0,0,0,$m, $d + 7, $y)).$divreq,false)?>"><?php echo JText::_('COM_BSBOOKING_NEXT_WEEK')?></a></h5></td>
    </tr>
    <tr>
		<td>
	<?php if ($include_jumpl_link):?>
	<?php endif ?>
		</td>
    </tr>
</table>

<?php
    }
    
   	/**
	* Returns an array of all timestamps for repeat reservations
	* @param string $initial_ts timestamp of first reservation
	* @param string $interval interval of reservation recurrances
	* @param array $days days of week to repeat on
	* @param string $until final date of recurrance (IsoDate Format)
	* @param int $frequency frequency of interval
	* @param string $week_number week of month number (for reserve by day of month)
	* @return array of all timestamps that the reservation is repeated on
	*/
	static function get_repeat_dates($initial_ts, $interval, $days, $until, $frequency, $week_number) {
		$res_dates = array();
		$initial_date = getdate($initial_ts);
		
		list($last_y, $last_m, $last_d) = explode('-', $until);
		$last_ts = mktime(0,0,0,$last_m, $last_d, $last_y);
		$last_date = getdate($last_ts);
		
		$day_of_week = $initial_date['wday'];
		$day_of_month = $initial_date['mday'];
		
		$ts = $initial_ts;
		
		if ($initial_ts > $last_ts)		// Recurring date is in the past
			return array($ts);
		
		switch ($interval) {
			case 'day' :
				for ($i = $frequency; $ts <= $last_ts; $i += $frequency) {
					$res_dates[] = $ts;
					$ts = mktime(0,0,0, $initial_date['mon'], $i + $initial_date['mday'], $initial_date['year']);						
				}
			break;
			case 'week' :
				$additional_days = 0;
				/* blogstone */
				for($ii=0;$ii<count($days);$ii++)  {
					if( $days[$ii] == $initial_date['wday'] ) {
						$res_dates[] = $ts;		// Add initial reservation
						break;
					}
				}
				
				while ($ts <= $last_ts) {		
					for ($i = 0; $i < count($days); $i++) {					// Repeat for all days selected
						$days_between = ($days[$i] - $day_of_week) + $additional_days;
						// If the day of week is less than reservation day of week, move ahead one week
						if ($days[$i] <= $day_of_week) {
							$days_between += $frequency * 7;
						}
						$ts = mktime(0,0,0,$initial_date['mon'], $initial_date['mday'] + $days_between, $initial_date['year']);
						
						if ($ts <= $last_ts)
							$res_dates[] = $ts;
					}
					$additional_days += $frequency * 7;	// Move ahead week
				}
			break;
			case 'month_date' :
				$next_month = $initial_date['mon'];
				$res_dates[] = $ts;			// Add initial reservation
				
				while ($ts <= $last_ts) {			
					$next_month += $frequency;
					if (date('t',mktime(0,0,0, $next_month, 1, $initial_date['year'])) >= $initial_date['mday']) {		// Make sure month has enough days
						$ts = mktime(0,0,0,$next_month, $initial_date['mday'], $initial_date['year']);
						if ($ts <= $last_ts)
							$res_dates[] = $ts;
					}
				}
			break;
			case 'month_day' :
				/* blogstone */
				$st = mktime(0, 0, 0, $initial_date['mon'], $initial_date['mday'], $initial_date['year']);
				$wk = date("w", mktime(0, 0, 0, $initial_date['mon'], 1,$initial_date['year']));
				for($ii=0;$ii<count($days);$ii++)  {
					$day = $days[$ii] - $wk + 1;
					if($day <= 0) $day += 7;
					$dt = mktime(0, 0, 0, $initial_date['mon'], $day, $initial_date['year']);
					$dt += (86400 * 7 * ($week_number - 1));
					if( $dt == $st ) {
						$res_dates[] = $ts;		// Add initial reservation
						break;
					}
				}
				$days_in_month = date('t', mktime(0,0,0, $initial_date['mon'], $initial_date['mday'], $initial_date['year']));
				$next_month = $initial_date['mon'];
				
				// Fill in all months			
				while ($ts <= $last_ts) {
					
					$days_in_month = date('t', mktime(0,0,0, $next_month, 1, $initial_date['year']));
					$first_day_of_month = date('w', mktime(0,0,0, $next_month, 1, $initial_date['year']));
					$last_day_of_month = date('w', mktime(0,0,0, $next_month, $days_in_month, $initial_date['year']));	
				
					if ($week_number != 'last') {
						$offset_date = ($week_number - 1) * 7 + 1; 		// Starting date
						$day_of_week = $first_day_of_month;				// Day of week
					}
					else {
						$offset_date = $days_in_month - 6;
						$day_of_week = $last_day_of_month + 1;
					}

					// Repeat on chosen days for this week
					for ($i = 0; $i < count($days); $i++) {					// Repeat for all days selected
						$days_between = ($days[$i] - $day_of_week);
						
						// If the day of week is less than reservation day of week, move ahead one week
						if ($days[$i] < $day_of_week) {
							$days_between += 7;
						}
						
						$current_date = $offset_date + $days_between;
						
						$need_to_add = ( ($current_date <= $days_in_month) && ($next_month > $initial_date['mon'] || ($current_date >= $initial_date['mday'] && $next_month >= $initial_date['mon'])) );
						
						if ($need_to_add)
							$ts = mktime(0,0,0, $next_month, $current_date, $initial_date['year']);
						if ( $ts <= $last_ts && $need_to_add && $ts != $initial_ts)// && ($current_date <= $days_in_month) && ($current_date >= $initial_date['mday'] && $next_month >= $initial_date['mon']) )
							$res_dates[] = $ts;
					}
						
					$next_month += $frequency;
				}	
			break;
		}
		return $res_dates;
	}
    
   	/**
	* Get all reservation data
	* This function gets all reservation data
	* between a given start and end date
	* @param int $start_date the starting date to get reservations for
	* @param int $end_date the ending date to get reservations for
	* @param array $resourceids list of resource ids to get reservations for
	* @param string $current_memberid the id of the currently logged in user
	* @return array of reservation data formatted: $array[date|machid][#] = array of data
	*  or an empty array
	*/
	static function getReservations($start_date, $end_date, $resourceids, $schedule_type = RESERVATION_ONLY, $user_id = null) {
		if (is_array($resourceids)) $resource_ids = join(',', $resourceids);
        
		$table_login = '#__users';
		$dbo =  JFactory::getDBO();
        // If it starts between the 2 dates, ends between the 2 dates, or surrounds the 2 dates, get it
		$sql = "SELECT res.*, login.name as name "
		          . " FROM #__bs_reservations as res"
                  . " LEFT JOIN ".$table_login." as login ON res.reserved_for = login.id "
                  . "\nWHERE ( "
						. "( "
							. "(start_date >= $start_date AND start_date <= $end_date)"
							. " OR "
							. "(end_date >= $start_date AND end_date <= $end_date)"
						. " )"
						. " OR "
						. "(start_date <= $start_date  AND end_date >= $end_date)"
                    . " )";

		if ($schedule_type == RESERVATION_ONLY)
		
		$sql .= ' AND res.resource_id IN (' . $resource_ids . ')';
		
		$sql .= "\n ORDER BY res.start_date, res.start_time, res.end_date, res.end_time";
        
		
		$dbo->setQuery($sql);
        
        $rows = $dbo->loadObjectList();
        if (JError::isError($rows) || empty($rows) ) return array();
        $return = array();
		foreach ($rows as $row) {
			$index = $row->resource_id;
			$return[$index][] = $row;
		}
        
        return $return;
	}
    
    static function getScheduleFromMenuItem($Itemid=null)
    {
    	$app = JFactory::getApplication();
		$input = $app->input;

        $menuitemid = ($Itemid?$Itemid:$input->getInt( 'Itemid' ));
        if ($menuitemid)
        {
        	$app = JFactory::getApplication();
            $link = $app->getMenu()->getActive()->link;
            $parts = explode('&', $link);
            foreach ($parts as $str)
            {
                list($name, $value) = explode('=', $str);
                if ($name=='id')
                {
                    $id = (int)$value;
                    break;
                }
            }
            
            return $id; 
        }
        
        return null;    
    }
    
    static function getVersion()
    {
        return array(longtext=>' BsBooking 1.0.0 ',
            shortText=>'1.0.0');
    }

    public static function getPriorityList( $selected )
    {
    	$options = array();
		$options[] = JHTML::_('select.option', 0, JText::_('PRIORITY_NONE') );
		$options[] = JHTML::_('select.option', 1, JText::_('PRIORITY_HIGH') );
		$options[] = JHTML::_('select.option', 2, JText::_('PRIORITY_MID') );
		$options[] = JHTML::_('select.option', 3, JText::_('PRIORITY_LOW') );

		$style = 'style="width: 100px;"';
		return JHTML::_('select.genericlist', $options, 'priority_flg', $style, 'value', 'text', $selected );
    }

}