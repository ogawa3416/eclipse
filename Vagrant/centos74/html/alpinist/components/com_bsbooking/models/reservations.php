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
 * @version		$Id: reservations.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
 *
 **/
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class BsbookingModelReservations extends JModelLegacy
{
	var $_schedule_id = null;

	var $_items = null;
    
    var $_total = null;
	
	var $_pagination = null;
    
    var $_type = null;

  	function __construct($config=array())
	{
        parent::__construct($config);
 
        $app = JFactory::getApplication();
        $input = $app->input;
        
        if (array_key_exists('schedule_id', $config)) 
            $this->_schedule_id = $config['schedule_id'];
        $this->_type = RESERVATION_ONLY;
        if (array_key_exists('type', $config))
            $this->_type = $config['type'];
        if (array_key_exists('own_items', $config))
            $this->setState('ownitems', $config['own_items']);
        if (array_key_exists('upcoming_items', $config))
            $this->setState('upcomingitems', $config['upcoming_items']);
        // Get pagination request variables
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
        $limitstart = $input->get('limitstart', 0, 'uint');
 
        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
 		$search = $input->getString('search', '');
 		
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
        $this->setState('search', $search);
  	}
  	
	function getItems()
	{
		if (empty($this->_items))
		{
			$query = $this->_buildQuery();
			$this->_db->setQuery($query, $this->getState('limitstart'), $this->getState('limit'));
			$this->_items = $this->_db->loadObjectList();
		}
        
		return $this->_items;
	}
	
	function getTotal()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
		
	}
	
    function getSorting()
    {
        $app = JFactory::getApplication();
		$option = 'com_bsbooking';;
 
        if (empty($this->_sorting) || empty($this->_sorting->filter_order) || empty($this->_sorting->filter_order_Dir) ) 
        {
            $filter_order = $app->getUserStateFromRequest($option.'reservations.filter_order', 'filter_order', 'start_datetime','string');
            $filter_order_Dir = $app->getUserStateFromRequest($option.'.reservations.filter_order_Dir', 'filter_order_Dir', 'asc','string');
            if( strlen(trim($filter_order_Dir,' ')) == 0 ) $filter_order_Dir = 'asc';
            $this->_sorting = new stdClass();
            $this->_sorting->filter_order = $filter_order;
            $this->_sorting->filter_order_Dir = $filter_order_Dir;
        }
        return $this->_sorting;
    }
    
    function getScheduleId()
    {
        return $this->_schedule_id;    
    }
    
 	function getPagination()
  	{
        // Load the content if it doesn't already exist
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
        }
        return $this->_pagination;
  	}
  	
	/**
	 * Get search key word
	 */
	function getSearchWord()
	{
		return $this->getState('search');	
	}
	
	function _buildQuery()
	{
		$sql = "SELECT re.*, from_unixtime(re.start_date+re.start_time*60) as start_datetime,  from_unixtime(re.end_date+re.end_time*60) as end_datetime
            , rs.title as resource_name, u.name as reserved_for_name, u2.name as reserved_by_name "
            ." FROM #__bs_reservations as re "
            ." LEFT JOIN #__bs_resources as rs ON re.resource_id = rs.id "
            ." LEFT JOIN #__users as u ON re.reserved_for=u.id "
            ." LEFT JOIN #__users as u2 ON re.created_by=u2.id ";

        $where = array();
        if (!empty($this->_schedule_id)) $where[] = 're.schedule_id = '.$this->_schedule_id;
		$search = $this->getState('search');
        if (!empty($search)){
            $where[] = "re.summary LIKE '%".$search."%'";            
        }
        
        if ($this->getState('ownitems')) {
            $user = JFactory::getUser();
            $where[] = "re.reserved_for = ".$user->id;     
        }
        if ($this->getState('upcomingitems')){
			$config = JFactory::getConfig();
			$now = JFactory::getDate();
			$now->setTimeZone(new DateTimeZone($config->get('offset')));
			$nowyear = $now->format( "Y",true );
			$nowmonth = $now->format( "m",true );
			$nowday = $now->format( "d",true );
			$nowhour = $now->format( "H",true );
			$nowminu = $now->format( "i",true );
			$nowsec = $now->format( "s",true );
//			$date = mktime(0,0,0, $nowmonth, $nowday + $min_days,$nowyear) + $nowhour*3600 + $nowminu*60 + $nowsec;
			$date = mktime(0,0,0, $nowmonth, $nowday,$nowyear) + $nowhour*3600 + $nowminu*60 + $nowsec;

			$where[] = "re.end_date+re.end_time*60 >= "."'".$date."'";
        }
        
	    $sql .= count($where)?"\nWHERE ".implode(' AND ', $where):"";	
        

        $sorting = $this->getSorting();
        $filter_order = $sorting->filter_order;
        $filter_order_Dir = $sorting->filter_order_Dir;
        
        $sortList = array('resource_name', 'reserved_for_name', 'start_datetime', 'end_datetime');
   
        if (!in_array($filter_order, $sortList)) $filter_order = 'start_datetime';
        if (!in_array($filter_order_Dir, array('asc', 'desc'))) $filter_order_Dir = 'desc';
        
        $sql .= "\nORDER BY ".$filter_order." ".$filter_order_Dir;   	
		return $sql;
	}
  	
	
}