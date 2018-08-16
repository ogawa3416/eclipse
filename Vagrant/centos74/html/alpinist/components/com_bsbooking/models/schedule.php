<?php
/**
 * BsBooking component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		GROON project
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2011 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: schedule.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.3 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;

require_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'schedule.class.php');

class BsbookingModelSchedule extends JModelLegacy {
    
	var $_scheduleId = null;
	
	var $_scheduleType = null;
    
    function __construct($config = array())
    {
        parent::__construct($config);
        
        if (array_key_exists('type', $config)) 
        {
            $this->_scheduleType = $config['type'];
        }     
        if (array_key_exists('id', $config)) 
        {
            $this->_scheduleId = $config['id'];
        }
    }
	
	function setScheduleId($id)
	{
		$this->_scheduleId = $id;	
	}
	
	function getScheduleId()
	{
		return $this->_scheduleId;
	}
	
	function setScheduleType($type)
	{
		$this->_scheduleType = $type;
	}
	
	function getScheduleType()
	{
		return $this->_scheduleType;
	}
	
	function getSchedule()
	{
		static $_instance;
		
		if (empty($_instance))
		{
			$_instance = new BsbookingSchedule($this->_scheduleId, $this->_scheduleType);
		}	
		return $_instance;
	}
}