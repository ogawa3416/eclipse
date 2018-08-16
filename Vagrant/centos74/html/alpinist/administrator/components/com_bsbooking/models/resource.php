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

jimport('joomla.application.component.model');

class BsbookingModelResource extends JModelLegacy
{
    var $_id = null;
    var $_item = null;
    var $_items = null;
    var $_total = null;
    var $_pagination = null;
    var $_schedule_id = null;
    
   	function __construct()
	{
        parent::__construct();
 
		$app = JFactory::getApplication();
		$input = $app->input;
		
        // Get pagination request variables
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
        $limitstart = $input->get('limitstart', 0,'uint');
 
        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
 		$schedule_filter = $input->getInt( 'schedule_filter', '' );
        $this->setState('schedule_filter', $schedule_filter);        
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
  	}
    
    function setId( $id )
    {
        $this->_id = $id;
        if (!empty($this->_id)) 
        {
            $this->getItem();
        }
		$this->_schedule_id = $this->_item->schedule_id;
    }
    
    function getId()
    {
        return $this->_id;    
    }
    
    function getScheduleFilter()
    {
        return $this->getState( 'schedule_filter' );    
    }
    function getScheduleId()
    {
        return $this->_schedule_id;    
    }
    function getItem()
    {
        if (empty($this->_item))
        {
            $this->_item = $this->getTable( 'resource');
            $this->_item->load( $this->_id );
        }
        return $this->_item;
    }
    
    function getItems()
    {
        if ( empty($this->_items) )
        {
            $query = $this->_buildQuery();
            $this->_items = $this->_getList( $query, $this->getState('limitstart'), $this->getState('limit'));
        }
        return $this->_items;
    }
    
    function getTotal()
    {
        if ( empty($this->_total) )
        {
            $query = $this->_buildQuery();
            $this->_total = $this->_getListCount( $query );
        }
        
        return $this->_total;
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
    
    function _buildQuery()
    {
        $query = "SELECT rs.*, sc.title as schedule_title FROM #__bs_resources rs "
            ."\n LEFT JOIN #__bs_schedules sc ON rs.schedule_id = sc.id";
        $schedule_filter = $this->getState('schedule_filter');
        if (!empty($schedule_filter))
        {
            $query .= "\n WHERE schedule_id = ".intval( $schedule_filter ); 
        }
        $query .= "\nORDER BY schedule_id, ordering ASC";
        return $query;
    }
    
    function store( $data )
    {
        if (empty($this->_item)) $this->getItem();
        
        if (!$this->_item->bind( $data ))
        {
            return false;       
        }
        
        if (!$this->_item->check())
        {
            return false;
        }
        
        if (!$this->_item->store())
        {
            return false;
        }

        if (empty($this->_id)) $this->_id = $this->_item->get('id');

        if( !$this->divstore( $data ) ) 
	    {
            return false;
        }
        
        return true;    
    }
    
    function remove( $cid )
    {
        $user = JFactory::getUser();
  		if (empty( $cid )) 
        {
			return JError::raiseWarning( 500, 'No items selected' );
		}
        $db = $this->getDBO();
		
        $cids = implode( ',', $cid );

		$query = 'DELETE FROM #__bs_resources'
		. ' WHERE id IN ( '.$cids.' )'
		. ' AND ( checked_out = 0 OR ( checked_out = '.(int) $user->get('id').' ) )'
		;
		$db->setQuery( $query );
		if (!$db->execute()) 
        {
			return JError::raiseWarning( 500, $db->getErrorMsg() );
		}
        
        $query = "DELETE FROM #__bs_reservations "
            ." WHERE resource_id IN (".$cids.")";
        $db->setQuery($query);
        if (!$db->execute())
        {
            return JError::raiseWarning( 500, $db->getErrorMsg() );    
        }
        
        $query = "DELETE FROM #__bs_reservation_division "
            ." WHERE resource_id IN (".$cids.")";
        $db->setQuery($query);
        if (!$db->execute())
        {
            return JError::raiseWarning( 500, $db->getErrorMsg() );    
        }

        return true;
    }
    
    /**
     * Publish/unpublish record(s)
     * @param array Array contain PK of records
     * @param boolean if true then make published=1
     * @internal tested
     */
    function publish( $cid, $publish)
    {
        $user = JFactory::getUser();
        $db = $this->getDBO();          //Better OOP concept than $this->_db
        
  		if (empty( $cid )) 
        {
			return JError::raiseWarning( 500, 'No items selected' );
		}

		$cids = implode( ',', $cid );

		$query = 'UPDATE #__bs_resources'
		. ' SET published = ' . intval( $publish )
		. ' WHERE id IN ( '.$cids.' )'
		. ' AND ( checked_out = 0 OR ( checked_out = '.(int) $user->get('id').' ) )'
		;
		$db->setQuery( $query );
		if (!$db->execute()) 
        {
			return JError::raiseWarning( 500, $db->getErrorMsg() );
		}
		if (count( $cid ) == 1) {
			$row = JTable::getInstance('Resource', 'Table');
            $row->checkin($cid[0]);
		}
        return true;
    }
	
	function divstore( $data )
    {
		$now = JFactory::getDate();
        $db = $this->getDBO();    
		if( !($_myuser = JFactory::getUser()) ){
			return false;
		}
        $insdiv = array();
		if( $data['menus'] == 'all' ) {
	        $query = "SELECT divcode FROM #__bs_division WHERE div_stat = 1 ";
        	$db->setQuery($query);
        	$rows = $db->loadObjectList();
			if( !$rows ) return false;
			foreach($rows as $row) { $insdiv[] =$row->divcode; }
		} else {
			$insdiv = $data['selections'];
		}
		
	    foreach($insdiv as $row) {
			$query = "SELECT count(*) FROM #__bs_reservation_division WHERE `resource_id` = ".$this->getId()." AND `divcode` = ".$this->_db->Quote($row) ;
			$db->setQuery( $query );
			$dtcnt = $db->loadResult();
			if( !$dtcnt ) { 
				$query = "INSERT INTO  #__bs_reservation_division "
					."\n ( `resource_id`, `divcode`, `can_edit`, "
					."\n `created`, `createdby` ) "
					."\n VALUES "
					."\n(".$this->getId().",".$this->_db->Quote($row). ","."1".","
					."\n ".$db->Quote($now->toSql()).",".$db->Quote($_myuser->id) 
					.")"
					;
				$db->setQuery( $query );
				$db->execute();
			}
		}
		
		if( $data['menus'] != 'all' ) {
			$query = "DELETE FROM #__bs_reservation_division"
				." WHERE resource_id = ".$this->getId() 
				." AND divcode NOT IN ( '" . implode("','",$insdiv) . "' )"
			;
			$db->setQuery( $query );
			if (!$db->execute()) 
       	 	{
				JError::raiseError( 500, $db->getErrorMsg() );
				return false;
			}
		}
		return true;
	}
}