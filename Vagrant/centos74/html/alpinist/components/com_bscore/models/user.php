<?php
/**
 * BsCore component for Joomla.
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
 * @version		$Id: user.php  BsAlpinist ver.2.5.0 $
 **/
// no direct access
defined('_JEXEC') or die;
jimport( 'joomla.application.component.model' );

class BscoreModelUser extends JModelLegacy 
{
    var $_items = null;
    
    var $_total = null;
    
    var $_pagination = null;
    
    var $_sorting = null;
	
    var $_divcode = null;
    
    function __construct($config = array())
    {
        parent::__construct($config);
		$app = JFactory::getApplication();
		$input = $app->input;
 
        // Get pagination request variables
		$params = clone($app->getParams('com_bscore'));
		$params->def('display_num', $app->getCfg('list_limit'));
		$default_limit = $params->get('display_num');
        $limit = $app->getUserStateFromRequest('com_bscore'.$input->get('layout').'limit', 'limit', $default_limit, 'int');
        $limitstart = $input->get('limitstart', 0,'uint');
 
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
	function getDivcode()
	{
		return $this->_divcode;
	}
	
	function _buildQuery()
	{
		$app = JFactory::getApplication();
		$input = $app->input;
		$sql = "SELECT u.*, a.divcode,a.divname,b.teleno,b.employeeno " 
			."\n FROM #__users as u, #__bs_division a, #__bs_users_detail b "
			."\n WHERE u.block = 0 "
			."\n AND b.userid = u.id "
			."\n AND b.divcode = a.divcode AND a.div_stat = 1 "
			;
		$this->_divcode = $input->get('divcode');
		if( $this->_divcode == '0' ) {
			$where_add = '';
		} else if( $this->_divcode ) {
			$where_add = ' AND a.divcode = '.$this->_db->Quote($this->_divcode).' ';
		} else {
			$where_add = '';
			$apparams = $app->getParams('com_bscore');
			if( !$apparams->get('alldivision',1) ) {
				$my = JFactory::getUser();
				// get user division
				if( $my->id ) {
					$query = ' SELECT a.divcode'
						.' FROM #__bs_division a,#__bs_users_detail b '
						.' WHERE b.userid = '.$my->id
						.' AND b.divcode = a.divcode AND a.div_stat = 1 ' 
					;
					$this->_db->setQuery( $query );
					$divcode = $this->_db->loadObject();
					if( $divcode->divcode ) {
						$this->_divcode = $divcode->divcode;
						$where_add = ' AND a.divcode = '.$this->_db->Quote($divcode->divcode).' ';
					} 
				}
			}
		}
		$sql .= $where_add;
		
		$search = $this->getSearchWord();
		if ($search != '')	
		{
            $search = $this->_db->Quote("%".$search."%");
			$sql .= " AND (u.name LIKE ".$search
                ." OR u.email LIKE ".$search." OR u.username LIKE ".$search
                .")";
		}	
		
        $sql .= "\n ORDER BY b.employeeno ASC";	
		return $sql;
	}    
	function checkDirectAccess() {
		$app = JFactory::getApplication();
		$input = $app->input;
		$itemid = 0;
		if( !($itemid = $input->get('Itemid')) ) {
			return false;
		}
		$user	= JFactory::getUser();
		$groups	= implode(',', $user->getAuthorisedViewLevels());
		$dbb = JFactory::getDbo();
		$link = "index.php?option=com_bscore&view=user" ;
		$query = "SELECT id FROM #__menu a"
				." WHERE link='".$link."'"
				." and published>=0"
				." and access IN (".$groups.")"
				." and id = ".$itemid;
		$dbb->setQuery( $query );
		$row = $dbb->loadResult();
		if( !$row ) return false;
		return true;
	}
}