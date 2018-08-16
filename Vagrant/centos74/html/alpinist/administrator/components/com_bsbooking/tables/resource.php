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
 * @version		$Id: resource.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;

class TableResource extends JTable {
	
	var $id = null;
	
	var $schedule_id = null;			//belongs to this schedule 
	
	var $title = null;
	
	var $divcode = null;
		
	var $location = null;
	
	var $rphone = null;
	
	var $notes = null;
	
	var $status = 'a';
	
    /**
     * Minimum reservation duration
    */
	var $min_res = null;
	/**
     * Maximum reservation duration
     */
	var $max_res = null;
	
	var $auto_assign = null;
    
	/**
     * //need admin approval or no
     */ 
	var $need_approval = null;				
	/**
     * allow multiple day reservation (recurring reservation)
     */
	var $allow_multi = null;			
	/**
     * number maximum of participants, 0 is unlimited.
     */
	var $max_participants = null;		
	
	var $min_notice_time = null;
	
	var $max_notice_time = null; 

	var $alert_msg = null; 
	
	var $ordering = 0;
    
    var $published = null;
    
    var $checked_out = 0;
    
    var $checked_out_time = null;

	function __construct () {
		$db = JFactory::getDBO();
		parent::__construct('#__bs_resources', 'id', $db);
	} 	
}