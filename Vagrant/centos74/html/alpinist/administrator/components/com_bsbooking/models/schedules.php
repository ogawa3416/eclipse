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
 * @version		$Id: schedules.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.0 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class BsbookingModelSchedules extends JModelLegacy
{
	/**
	 * schedules data
	 *
	 * @var array
	 */
	var $_data = null;

	var $_total = null;
	
	var $_pagination = null;
	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
  	function __construct()
	{
        parent::__construct();
 
        $app = JFactory::getApplication();
		$input = $app->input;
 
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
  	
	function getData()
	{
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			$this->_db->setQuery($query, $this->getState('limitstart'), $this->getState('limit'));
			$this->_data = $this->_db->loadObjectList();
		}
		return $this->_data;
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
	   $sql = "SELECT SC.*, (SELECT COUNT(id) FROM #__bs_resources WHERE schedule_id = SC.id) AS resource_count"
            ."\nFROM #__bs_schedules as SC";
	   $search = trim( $this->getSearchWord() );
	   if ($search != '')	
	   {
	       $sql .= "\nWHERE title LIKE '%".$search."%'";		
	   }	
			
	   return $sql;
	}
  	
	
}