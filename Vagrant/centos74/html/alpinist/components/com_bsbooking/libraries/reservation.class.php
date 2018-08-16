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
 * @version		$Id: reservation.class.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;

class BsbookingReservation extends JObject 
{
	var $id 		= null;
	/**
     * Start date (only date) of reservation
     */
    var $start_date	= null;	
    /**
     * End date (only date) of reservation
     */			
	var $end_date	= null;				
    /**
     * Start minute of reservation in that day
     */
	var $start_time	 	= null;				
    /**
     * End minute of reservation
     */
	var $end_time	 	= null;
    /**
     * Resource object of this reservation
     */ 				
	var $resource 	= null;	
    
    /**
     * Joomla user id of this reservation made for
     */
    var $reserved_for = null;			
    /**
     * owner of reservation 's user object (id from $reserved_for)
     */
	var $user		= null;				
	/**
     * Other resources reserved for this reservation
     */
    var $resources = array();			

    /**
     * Who make this reservation transaction (user id)
     */
     
    var $created_by = null;
    
    /**
     * datetime when record created
    */    
	var $created 	= null;	
    			//
    /**
     * latest modified datetime
     */
	var $modified 	= null;				//
	/**
     * Type of add, edit, delete
     */
    var $type 		= null;				//
    /**
     *  Is this reservation is repeat
     */
	var $is_repeat	= false;			
    /**
     *  Repeat (recurring) dates in group of reservation
     */
	var $repeat		= null;				
    /**
     * Minimum reservation length
     */
	var $minres		= null;				
    /**
     * Maximum reservation length
     */ 
	var $maxres		= null;				
    /**
     * Parent ID of this reservation
     */
	var $parent_id	= null;				//
	
    var $summary	= null;				//
    /**
     * Members (by blogstone)
     */
    var $members	= null;	
    var $dispmembers	= null;				//
	/**
     * Schedule ID this reservation belongs to.
     */
    var $schedule_id    = null;				//
    
    var $resource_id    = null;
	/**
     * Schedule object
    */
    var $schedule	= null;				//
    
	var $users		= null;				//
	
    var $allow_participation = 0;		//
	
    var $allow_anon_participation = 0;	//
	
    var $reminderid	= null;				//
	
    var $invited_users = array();
	
    var $participating_users = array();

	var $word		=  'RESERVATION';
	
    var $adminMode  = false;
	
    var $is_participant = false;
	
    var $reminder_minutes_prior = 0;

	var $_table;
    
    var $_message = null;

    var $private_flg	= null;	

	/**
	* Reservation constructor
	* Sets id (if applicable)
	* Sets the reservation action type
	* Sets the database reference
	* @param int $id id of reservation to load
	* @param int $scheduleid id of the schedule this belongs to
	*/
	function __construct( $config=array() ) 
    {
		$this->_message = null;
		if (array_key_exists('table', $config)) 
        {
            $this->_table = $config['table'];
        }else{
            JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'tables');
            $this->_table =  JTable::getInstance('Reservation', 'Table');
        }
        if (array_key_exists('id', $config)) 
        {
            $this->id = (int)$config['id'];
        }

		if( $this->id ) {
			$this->loadById();
		}
		else {
			$date = JFactory::getDate();
			$time = $date->toSql();
            /* This is a new record */
			$this->schedule_id = array_key_exists('schedule_id', $config)?$config['schedule_id']:null;
            $this->resource_id = array_key_exists('resource_id', $config)?$config['resource_id']:null;
            $this->start_date = array_key_exists('start_date', $config)?$config['start_date']:null;
            $this->end_date = array_key_exists('end_date', $config)?$config['end_date']:null;
            $this->start_time = array_key_exists('start_time', $config)?$config['start_time']:null;
            $this->end_time = array_key_exists('end_time', $config)?$config['end_time']:null;
            $this->summary = array_key_exists('summary', $config)?$config['summary']:null;
            $this->private_flg = array_key_exists('private_flg', $config)?$config['private_flg']:null;
            $this->created_by   =  JFactory::getUser()->get('id');
            $this->created       = $time;
            $this->adminMode = BsbookingHelper::isAdmin();
/** Add blogstone **/
			$this->members = array_key_exists('members', $config)?$config['members']:null;
			$pt = "/^#[^#]*#/";
		   	preg_match($pt,$this->members,$matches) ;
		   	$memberstr = '';
		   	if( count($matches) > 0 ) {
				$memberstr = str_replace($matches[0],"",$this->members);
			}
			$this->dispmembers = '';
			if( strlen($memberstr) == 0 ) {
				$this->dispmembers = '';
			} else {
				$darr = explode ("/",$memberstr);
				$fst = 0;
				for( $i=0;$i<count($darr);$i++ ) {
					$dmymem = explode(",",$darr[$i]);
					if( strlen($dmymem[0]) == 0  ) continue;
					if( $fst != 0 ) $this->dispmembers .= ',';
					$this->dispmembers .= $dmymem[1];
					 $fst = 1;
				}
			}
/** Add blogstone **/
            if (array_key_exists('reserved_for', $config)) {
            	$this->reserved_for = (int)$config['reserved_for'];
            	$this->user =  JFactory::getUser($this->reserved_for);
            }else{
            	$this->user =  JFactory::getUser();
                $this->reserved_for = $this->user->get('id');    
            }   
            
            if (!empty($this->schedule_id)) 
            {
                $this->schedule =  JTable::getInstance('Schedule', 'Table');
                $this->schedule->load($this->schedule_id);
            }
        
            if ($this->start_time == $this->end_time) $this->end_time = $this->end_time + $this->schedule->time_span;
            if (!empty($this->resource_id))
            {
                $this->resource =  JTable::getInstance('Resource', 'Table');
                $this->resource->load($this->resource_id);
            }
		}
	}

    public static function getInstance( $config=array() )
    {
        static $_instance;
        if (empty($_instance)) 
        {
            $_instance = new BsbookingReservation( $config );
        }    
        return $_instance;
    }

    /**
     * Load all properties from database
     */
    function loadById()
    {
        $query = "SELECT * FROM #__bs_reservations WHERE id = ".$this->id;
        $_db = JFactory::getDBO(); 
        $_db->setQuery($query);
        $row = $_db->loadObject();
        $this->schedule_id = $row->schedule_id;
        $this->resource_id = $row->resource_id;
        $this->start_date  = $row->start_date;
        $this->end_date    = $row->end_date;
        $this->start_time  = $row->start_time;
        $this->end_time    = $row->end_time;
        $this->summary     = $row->summary;
        $this->reserved_for= $row->reserved_for;
        $this->private_flg = $row->private_flg;

        $this->created     = $row->created;
        $this->created_by   = $row->created_by;
        
        $this->modified    = $row->modified;
        
        $this->parent_id   = $row->parent_id;

        $this->adminMode = BsbookingHelper::isAdmin();
        
/** Add blogstone **/
		$this->members = $row->members;
		$pt = "/^#[^#]*#/";
	   	preg_match($pt,$row->members,$matches) ;
	   	$memberstr = $row->members;
	   	if( is_array($matches) && count($matches)>0 ) {
			$memberstr = str_replace($matches[0],"",$row->members);
			$this->members = str_replace($matches[0],"#MDFY#",$row->members);
		}
		
		$this->dispmembers = '';
		if( strlen($memberstr) == 0 ) {
			$this->dispmembers = '';
		} else {
			$darr = explode ("/",$memberstr);
			$fst = 0;
			for( $i=0;$i<count($darr);$i++ ) {
				$dmymem = explode(",",$darr[$i]);
				if( strlen($dmymem[0]) == 0  ) continue;
				if( $fst != 0 ) $this->dispmembers .= ',';
				$this->dispmembers .= $dmymem[1];
				 $fst = 1;
			}
		}
/** Add blogstone **/
		
        if (!empty($this->schedule_id)) 
        {
            $this->schedule =  JTable::getInstance('Schedule', 'Table');
            $this->schedule->load($this->schedule_id);
        }
        
        if (!empty($this->resource_id))
        {
            $this->resource =  JTable::getInstance('Resource', 'Table');
            $this->resource->load($this->resource_id);
        }
        if (!empty($this->reserved_for))
        {
            $this->user = JFactory::getUser($this->reserved_for);
        }      
    }
    
    function addReservation()
    {
        $this->type = RES_TYPE_ADD;
        
        $orig_start_date        = $this->start_date;
        $orig_end_date          = $this->end_date;
        
        /** Use this dates to inform user */
        $dates              = array();
        $is_parent          = $this->is_repeat;
        
        $creator =  JFactory::getUser();
  		
		$this->checkPermission();	         // Check permissions
		if( $this->checkTimes() ) {	       // Check min/max reservation times
			$this->checkMinMax();			// Check valid times
			$this->checkStartDate();
		}
		if ( count($this->getErrors()) > 0 ) {			// Print any errors generated above and kill app
			return false;
		}
        
        $repeat = $this->repeat; //recurring date in group
        for($i=0; $i < count($repeat); $i++)
        {
            $this->start_date = $repeat[ $i ]; //Date from repeat dates
            if ($this->is_repeat)
            {
                /* for repeat type, we set start date = end date */
                $this->end_date = $this->start_date;
            }
            if ($i==0)
            {
                $tmp_date = $this->start_date;
            }
            $is_valid = $this->checkPermission();
            if ($is_valid) $is_valid = $this->checkReservation();
            if ($is_valid)
            {
                $tmp_valid = true;
                $this->_table->id = 0;
                $this->_table->bind($this, array('id'));
                if ($this->_table->store())
                {
                    $this->id = $this->_table->id;
                    if (!$is_parent)
                    {
                        array_push($dates, $this->start_date);
                    }else{
                        $this->parent_id = $this->_table->id;
                    }  
                }
            }
            $is_parent = false;
        }
        $this->start_date = $tmp_date;				// Restore first date for use in email
		if ($this->is_repeat) 
		{
			array_unshift($dates, $this->start_date);		// Add to list of successful dates
		}

		sort($dates);

        $this->start_date    = $orig_start_date;
        $this->end_date      = $orig_end_date;
        
        $this->setSuccessMessage(JText::_('COM_BSBOOKING_CREATED'), $dates);    
		/* blogstone */
		return count($dates);
    }
    
    function modifyReservation($delete, $mod_recur)
    {
    	$config = JFactory::getConfig();
		$now = JFactory::getDate();
		$now->setTimeZone(new DateTimeZone($config->get('offset')));
		$year = $now->format( "Y",true );
		$month = $now->format( "m",true );
		$day = $now->format( "d",true );
		
        /* First check if user want to delete this reservation or not */
        if ($delete==1) {
            $this->deleteReservation($mod_recur);
            return;
        }
        
        $this->type = RES_TYPE_MODIFY;    
  		$recurs = array();
		$valid_reservation_ids = array();

		$orig_start_date = $this->start_date;// Store the original dates because they will be changed if we repeat
		$orig_end_date = $this->end_date;

		$this->checkPermission();	         // Check permissions
		if( $this->checkTimes() ) {	       // Check min/max reservation times
			$this->checkMinMax();			// Check valid times
			$this->checkStartDate();
		}


		if ( count($this->getErrors()) > 0 ) {			// Print any errors generated above and kill app
			return false;
		}

		$this->is_repeat = $mod_recur;	// If the mod_recur flag is set, it must be a recurring reservation
		$dates = array();
        $valid_res_ids = array();
		// First, modify the current reservation
		if ($this->is_repeat) {				// Check and place all recurring reservations
			$recurs = $this->_table->getRecurringIds($this->id,$this->parent_id, mktime(0,0,0,$month,$day,$year));
			for ($i = 0; $i < count($recurs); $i++) {
				$this->id = $recurs[$i]->id;		// Load reservation data
				$this->parent_id = $recurs[$i]->parent_id;
				$this->start_date = $recurs[$i]->start_date;
				if ($this->is_repeat) {
					// End date will always be the same as the start date for recurring reservations
					$this->end_date = $this->start_date;
				}
				$is_valid = $this->checkReservation();		// Check overlap (dont kill)
				if ($is_valid) {
					$tmp_valid = true;						// Only one recurring needs to pass
					// And place the reservation
                    $this->_table->bind($this);
                    $this->_table->store();
					
                    $dates[] = $this->start_date;
					$valid_res_ids[] = $this->id;
				}
			}
		}
		else {
			if ($this->checkReservation()) {     // Check overlap
				//$this->db->mod_res($this, $users_to_invite, $users_to_remove, $resources_to_add, $resources_to_remove, $accept_code);		// And place the reservation
				// And place the reservation
                $this->_table->bind($this);
                $this->_table->store();
                $tmp_valid = true;
				$dates[] = $this->start_date;
				$valid_res_ids[] = $this->id;
			}
		}

		// Restore original reservation dates
		$this->start_date = $orig_start_date;
		$this->end_date = $orig_end_date;

//		if ($tmp_valid) {
		if ( count($dates) ) {
			$this->setSuccessMessage(JText::_('COM_BSBOOKING_MODIFIED'), $dates);
            return true;
        }

    }

    function deleteReservation($del_recur)
    {
        $this->type = RES_TYPE_DELETE;
        $this->is_repeat = $del_recur;
        
        $dbo = JFactory::getDBO();
        $sql = "SELECT id FROM #__bs_reservations  WHERE id = ".$this->id;
        if ($del_recur==1){
            if ($this->parent_id != 0) { 
                $sql .= " OR parent_id =".$this->parent_id;
            }else{
                $sql .= " OR parent_id =".$this->id;
            }  
        }
        if (!$this->adminMode) {   
            $config = JFactory::getConfig();
            $now = JFactory::getDate();
            $year = $now->format( "Y",true );
            $month = $now->format( "m",true );
            $day = $now->format( "d",true );
            $now->setTimeZone(new DateTimeZone($config->get('offset')));

            $sql .= " AND start_date >= ".mktime(0,0,0,$month,$day,$year);
        }
        $dbo->setQuery($sql);
        $rows = $dbo->loadColumn();
        if (count($rows)) {
            $sql = "DELETE FROM #__bs_reservations WHERE id IN (".join(',', $rows).")";
            $dbo->setQuery($sql);
            $dbo->execute();
        }
    }
    
    function approveReservation()
    {
        
    }
    
    /**
     * Verify that start date is not passed
     */
    function checkStartDate() 
    {
		$config = JFactory::getConfig();
		$now = JFactory::getDate();
		$now->setTimeZone(new DateTimeZone($config->get('offset')));
		
        if ($this->adminMode) return true;
        $dates_valid = true;
        $min_res = $this->resource->get('min_res');
  		$min_notice = $this->resource->get('min_notice_time');
		$max_notice = $this->resource->get('max_notice_time');
        
/******* 20130601 *******
  		$date_vals = getdate();
		$month = $date_vals['mon'];
		$day = $date_vals['mday'];
		$hour = $date_vals['hours'];
		$min = $date_vals['minutes'];
		$sec = $date_vals['seconds'];
		$year = $date_vals['year'];

		$now_date_full = mktime($hour, $min, $sec, $month, $day);
		$now_date_vals = getdate($now_date_full);
		$now_date = mktime(0,0,0, $now_date_vals['mon'], $now_date_vals['mday'], $now_date_vals['year']);
		$now_time = $now_date_vals['hours'] * 60 + $now_date_vals['minutes'];
*******/
		
		$year = $now->format( "Y",true );
		$month = $now->format( "m",true );
		$day = $now->format( "d",true );
		$hour = $now->format( "H",true );
		$min = $now->format( "i",true );
		$sec = $now->format( "s",true );
		$now_date = mktime(0,0,0, $month, $day, $year);
		$now_time = $now->__get('hour') * 60 + $now->__get('minute') - $min_res;
		
		if( $this->type != RES_TYPE_MODIFY ||
		  ( $this->type == RES_TYPE_MODIFY && ($this->start_date > $now_date || ($this->start_date == $now_date && $this->start_time > $now_time))) ) {
			$min_date_full = mktime($hour + $min_notice, $min, $sec, $month, $day,$year);
			$min_date_vals = getdate($min_date_full);
			$min_date = mktime(0,0,0, $min_date_vals['mon'], $min_date_vals['mday'], $min_date_vals['year']);
//			$min_time = $min_date_vals['hours'] * 60 + $min_date_vals['minutes'];
			$min_time = $now->__get('hour') * 60 + $now->__get('minute') - $min_res;

			if ($this->start_date < $min_date) {
				$dates_valid = false;
				$this->setError( JText::sprintf('com_bsbooking_ERROR_CANNOT_RESERVE_LESS_THAN_X_HOURS_IN_ADVANCE', $min_notice) ) ;
			} else if($this->start_date == $min_date && $this->start_time < $min_time) {
				$dates_valid = false;
				$this->setError( JText::_('COM_BSBOOKING_ERROR_CANNOT_RESERVE_OVER_THAN_X_HOURS_IN_ADVANCE' ) ) ;
			}
        }
		
		if ($max_notice != 0 && $dates_valid) 
		{
			// Only need to check this if the min notice check passed	
			$max_date_full = mktime($hour + $max_notice, $min, $sec, $month, $day,$year);
			$max_date_vals = getdate($max_date_full);
			$max_date = mktime(0,0,0, $max_date_vals['mon'], $max_date_vals['mday'], $year);
			$max_time = $max_date_vals['hours'] * 60 + $max_date_vals['minutes'];
			
			if ( ($this->start_date > $max_date) ||
				 ($this->start_date == $max_date && $this->start_time > $max_time) )
			{
				$dates_valid = false;
				$this->setError( JText::sprintf('com_bsbooking_ERROR_CANNOT_RESERVE_MORE_THAN_X_HOURS_IN_ADVANCE', $max_notice) );
			}
		}
        return $dates_valid;
    }    

    /**
     * Verify that end date is not passed
     */
    function checkEndDate() 
    {
		$config = JFactory::getConfig();
		$now = JFactory::getDate();
		$now->setTimeZone(new DateTimeZone($config->get('offset')));
		
        if ($this->adminMode) return true;
        $dates_valid = true;
        
        $min_res = $this->resource->get('min_res');
  		$min_notice = $this->resource->get('min_notice_time');
		$max_notice = $this->resource->get('max_notice_time');
/******* 20130601 *******
  		$date_vals = getdate();
		$month = $date_vals['mon'];
		$day = $date_vals['mday'];
		$hour = $date_vals['hours'];
		$min = $date_vals['minutes'];
		$sec = $date_vals['seconds'];
		$year = $date_vals['year'];
		
		$now_date_full = mktime($hour, $min, $sec, $month, $day);
		$now_date_vals = getdate($now_date_full);
*******/
		$now_date_vals['year'] = $now->format( "Y",true );
		$now_date_vals['mon'] = $now->format( "m",true );
		$now_date_vals['mday'] = $now->format( "d",true );
		$now_date = mktime(0,0,0, $now_date_vals['mon'], $now_date_vals['mday'], $now_date_vals['year']);
//		$now_time = $now_date_vals['hours'] * 60 + $now_date_vals['minutes'];

		$now_time = $now->__get('hour') * 60 + $now->__get('minute') - $min_res;

		

		if ( $this->end_date < $now_date || ($this->end_date == $now_date && $this->end_time < $now_time)) {
			$dates_valid = false;
			$this->setError( JText::_('COM_BSBOOKING_ERROR_BACKDATE') ) ;
			
		}
        return $dates_valid;
    }
	
    function checkTimes()
    {

  		$is_valid = ( (intval($this->start_date) < intval($this->end_date)) || ( intval($this->start_date) == intval($this->end_date) ) && (intval($this->start_time) < intval($this->end_time)) );
		// It is valid if the start date is less than or equal to the end date or (if the dates are equal), the start time is less than the end time
		if (!$is_valid) {
			$this->setError(JText::_('COM_BSBOOKING_ERROR_START_TIME_MUST_LESS_THAN_END_TIME') . '<br /><br />'
					. JText::_('COM_BSBOOKING_CURRENT_START_TIME') . ' ' . DateUtil::formatDate($this->start_date,'%Y-%m-%d').' '.DateUtil::formatTime($this->start_time,false) . '<br />'
					. JText::_('COM_BSBOOKING_CURRENT_END_TIME') . ' ' . DateUtil::formatDate($this->end_date,'%Y-%m-%d').' '.DateUtil::formatTime($this->end_time,false) );
		}
		return $is_valid;
    }
    
    /**
     * Check minimum and maximum reservation length
     */
    function checkMinMax()
    {
        /* Always valid for admin */
        if ($this->adminMode) return true;
		
        $min = $this->resource->get('min_res');
        $max = $this->resource->get('max_res');

        if ($this->start_date == $this->end_date) {
	        $this_length = $this->end_time - $this->start_time;
  			$is_valid = ($this_length >= ($min)) && (($this_length) <= ($max));
		} else {
			if( (($this->end_date-$this->start_date)/(3600*24)) > 1 ) {
				$this_length = 1440;  // 60*24
  				$is_valid = (($this_length) <= ($max));
			} else {
				if( 1440 - $this->start_time < $this->end_time ) 
						$this_length = $this->end_time;
				else 	$this_length = 1440 - $this->start_time;
  				$is_valid = (($this_length_s) <= ($max));
			}
		}
        
  		$is_valid = ($this_length >= ($min)) && (($this_length) <= ($max));

		if (!$is_valid)
		$this->setError(JText::_('COM_BSBOOKING_ERROR_NOT_IN_RESOURCE_LENGTH') . '<br /><br >'
					. JText::_('COM_BSBOOKING_YOUR_RESERVATION') . ' ' . DateUtil::minutesToHours($this_length) . '<br />'
					. JText::_('COM_BSBOOKING_MINIMUM_RESERVATION_LENGTH') . ' ' . DateUtil::minutesToHours($min). '<br />'
					. JText::_('COM_BSBOOKING_MAXIMUM_RESERVATION_LENGTH') . ' ' . DateUtil::minutesToHours($max)
					);
		return $is_valid;
    }
    
    function checkReservation()
    {
        $is_valid = ! ($this->_table->isBooked($this));
        if (!$is_valid) {
            $this->setError( JText::sprintf('COM_BSBOOKING_ERROR_RESERVERD_OR_UNAVAILABLE', 
                DateUtil::formatDate($this->start_date,'%Y-%m-%d').' '.DateUtil::formatTime($this->start_time,false),
                DateUtil::formatDate($this->end_date,'%Y-%m-%d').' '.DateUtil::formatTime($this->end_time,false)
                ) );
        }
        
        return $is_valid;   
    }
    
    function checkPermission()
    {
        return true;
    }
        
    function setType($type)
    {
        $this->type = isset($type)?substr($type, 0, 1) : null;   
    }
    
    function setSuccessMessage($verb, $dates)
    {
        $date_text = '';
        for ($i=0; $i < count($dates); $i++){
            $date_text .= DateUtil::formatDate($dates[$i], '%Y-%m-%d')."<br />";
        }
        $this->_message = JText::_($this->word).' '.JText::_('COM_BSBOOKING_WASSUCCESS').' '.$verb.' '
            .JText::_('COM_BSBOOKING_FOLLOWDATES').'<br /><br />'
            .$date_text;             
    }
    
    function getSuccessMessage()
    {
        return $this->_message;
    }
 }