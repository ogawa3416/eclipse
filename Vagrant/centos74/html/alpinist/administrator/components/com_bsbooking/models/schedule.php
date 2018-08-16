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
 * @version		$Id: schedule.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.0 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class BsbookingModelSchedule extends JModelLegacy
{
	var $_id;
	var $_data;
	
	function setId( $id )
	{
		$this->_id = $id;
		$this->getData();	
	}
	
	function getId()
	{
		return $this->_id;
	}
	
	function getData()
	{
//		if (empty($this->_id) ) return null; 
		if (empty($this->_data))
		{
//			$query = "SELECT * FROM #__bs_schedules WHERE id = ".intval( $this->_id );
//			$this->_db->setQuery($query);
//			$this->_data = $this->_db->loadObject();

            $this->_data = $this->getTable( 'schedule');
            $this->_data->load( $this->_id );

		}
		return $this->_data;	
	}
	
	function checkout($who, $oid = null)
	{
		if (empty($this->_id) && empty($oid))
		{ 
			$this->setError('No object id specified for checkout');
			return false;
		}
		$query = "UPDATE #__bs_schedules SET checked_out = ".intval( $who ).", checked_out_time = NOW() "
				." WHERE id = ".(empty($oid)?$this->_id:$oid);
				
		$this->_db->setQuery( $query );
		$this->_db->execute();
		
		return true;
    }
    
    function checkin($oid = null)
    {
        if (empty($oid)) $oid = $this->_id;
        if ( !empty($oid) )
        {
            $query = "UPDATE #__bs_schedules SET checked_out = 0, checked_out_time = ".$this->_db->quote($this->_db->getNullDate())
                ." WHERE id = ".$oid;
                
            $this->_db->setQuery( $query );
            $this->_db->execute();
            
            return true;
        }
        
        return false;    
    }
    
    function store( $data )
    {
        $table = & $this->getTable( 'Schedule' );
        if (!empty($this->_data)) $table->load($this->_id);
        
        $table->bind( $data ); 
        if ( !$table->check() )
        {
            $this->setError (JText::_('COM_BSBOOKING_SCHSTORE_ERR'));
            return false;
        }
        if (!$table->store())
        {
            $this->setError( $table->getError() );
            return false;
        }
        
        if (empty($this->_id)) $this->_id = $table->id;
        
        return true;   
    }
	
    /**
     * Delete schedule as well as reservations and resources associated with it from database.
     * @param array schedule id to delete
     * @todo Inform users on reservation delete (if they are not passed)? 
     */
    function delete( $cid )
    {
        JArrayHelper::toInteger( $cid );
        
		if (count( $cid ))
		{ 
            $query = "DELETE FROM #__bs_resources "
                ." WHERE schedule_id IN (".join(',', $cid).")";
            $this->_db->setQuery($query);
            if (!$this->_db->execute()){
                $this->setError( $this->_db->getErrorMsg() );
                return false;
            }
                
            $query = "DELETE FROM #__bs_reservations "
                    ." WHERE schedule_id IN (".join(',', $cid).")";
            $this->_db->setQuery($query);
            if (!$this->_db->execute()){
                $this->setError( $this->_db->getErrorMsg() );
                return false;
            }    
                      
			$query = "DELETE FROM #__bs_schedules "
                ." WHERE id IN (".join(',', $cid).")";
			$this->_db->setQuery( $query );
			if (!$this->_db->execute()) 
            {
				$this->setError( $this->_db->getErrorMsg() );
				return false;
			}
		}
        
		return true;
    }
}