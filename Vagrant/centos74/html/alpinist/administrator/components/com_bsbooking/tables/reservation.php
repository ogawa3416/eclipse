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
 * @version		$Id: reservation.php BsAlpinist ver.2.5.0 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;

class TableReservation extends JTable {
	
	var $id = null;
	
	var $resource_id = null;
	
    var $schedule_id = null;
    
	var $start_date = null;
	
	var $end_date = null;
	
	var $start_time = 0;
	
	var $end_time = 0;
    
    var $summary = null;
	
	var $created_by = 0;
    
    var $created = null;
    
    var $modified = null;
	
    var $reserved_for = null;
    
	var $checked_out = null;
	
	var $checked_out_time = null;
/* blogstone */
	var $members = null;
	var $private_flg = null;
    
    /**
     * Parent 's reservation id of this reservation
     */
    var $parent_id = null;

	function __construct () {
		$db = JFactory::getDBO();
		parent::__construct( '#__bs_reservations', 'id', $db );
	}
    
    function check ()
    {
        if (empty($this->schedule_id) || empty($this->resource_id)) return false;
        return true;
    }
    
    /**
     * Check to see if the provided reservation is reserved or not
     * @return true if booked
     */
    function isBooked($res)
    {     
  		// If it starts between the 2 dates, ends between the 2 dates, or surrounds the 2 dates, get it
		$query = "SELECT COUNT(id) AS num FROM #__bs_reservations "
				. " WHERE resource_id = {$res->resource_id} "
				. " AND ("
					// Is surrounded by
					//(starts on a later day OR starts on same day at a later time) AND (ends on an earlier day OR ends on the same day at an earlier time)					
					. " ( (start_date > {$res->start_date} OR (start_date = {$res->start_date} AND start_time > {$res->start_time})) AND ( end_date < {$res->end_date} OR (end_date = {$res->end_date} AND end_time < {$res->end_time})) )"
					// Surrounds
					//(starts on an earlier day OR starts on the same day at an earlier time) AND (ends on a later day OR ends on the same day at a later time)
					. " OR ( (start_date < {$res->start_date}  OR (start_date = {$res->start_date}  AND start_time < {$res->start_time})) AND (end_date > {$res->end_date}  OR (end_date = {$res->end_date}  AND end_time > {$res->end_time})) )"
					// Conflicts with the starting time
					//(starts on an earlier day OR starts on the same day at an earlier time) AND (ends on a later day than the starting day OR ends on the same day as the starting day but at a later time)
					. " OR ( (start_date < {$res->start_date} OR (start_date = {$res->start_date} AND start_time <= {$res->start_time} )) AND (end_date > {$res->start_date} OR (end_date = {$res->start_date} AND end_time > {$res->start_time} )) ) "
					// Conflicts with the ending time
					//(starts on an earlier day than this ends OR starts on the same day as this ends but at an earlier time) AND (ends on a day later than the ending day OR ends on the same day as the ending day but at a later time) 
					. " OR ( (start_date < {$res->end_date} OR (start_date = {$res->end_date}  AND start_time < {$res->end_time})) AND (end_date > {$res->end_date}  OR (end_date = {$res->end_date} AND end_time >= {$res->end_time} )) )"
				. " ) "; 
        if (!empty($res->id)) $query .= " AND id <> ".$res->id;
        $dbo =  $this->getDBO();
        $dbo->setQuery($query);

        return $dbo->loadResult() > 0;   
    }
    
    /**
     * Get all recurring id of reservation in group
     * @param parent_id reservation id of parent
     * @param start_date timestamp of date to start from
     */
    function getRecurringIds($id,$parent_id, $start_date)
    {
  		$return = array();
		if( $parent_id > 0 ) {
			$searchid = $parent_id;
		} else {
			$searchid = $id;
		}
		$sql = 'SELECT id, parent_id, start_date FROM '
				. '#__bs_reservations '
				. ' WHERE (parent_id = '.$searchid
				. ' OR id = '.$searchid.') AND id <> 0'
				. ' AND start_date >= '.$start_date
				. ' ORDER BY start_date ASC';
        $dbo =  $this->getDBO();
		$dbo->setQuery($sql);
        return $dbo->loadObjectList();    
    } 	
}