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
 * @version		$Id: schedule.php BsAlpinist ver.2.5.0 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;

class TableSchedule extends JTable {
	
	var $id = null;
	
	var $title = null;
	
	var $day_start = 480;
	
	var $day_end = 1200;
	
	var $time_span = 60;
	
	var $view_days = 7;
    
	var $weekday_start = 2;
	
    var $show_summary = 1;
	
	var $checked_out = null;
	
	var $checked_out_time = null;
	
	var $admin_email = null;
    
    var $notify_admin = null;
	
	var $published = null;

	function __construct () {
		$db = JFactory::getDBO();
		parent::__construct( '#__bs_schedules', 'id', $db );
	}
    
    function check ()
    {
        if (($this->day_start < 0) || ($this->day_start > 1380))
        {
            return false;   
        } 
        
        if (($this->dat_end < 0) || ($this->day_end > 1380)) 
        {
            return false;
        } 
        
        if ( $this->day_start >= $this->day_end ) 
        {
            return false;
        }
        
        if ( ($this->day_end - $this->day_start < $this->time_span) )
        {
            return false;
        }
        
        if ( empty($this->title) )
        {
            return false;
        }
        
        if ( ($this->view_days <= 0) || ($this->view_days > 7) )
        {
            $this->view_days = 7;
        }
        
        return true;
    } 	
}