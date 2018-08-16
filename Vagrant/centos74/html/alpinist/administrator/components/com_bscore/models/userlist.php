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
 * @version		$Id: userlist.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
 **/
 
defined ('_JEXEC') or die();
jimport('joomla.application.component.modellist');
require_once( JPATH_ROOT.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_bscore'.DIRECTORY_SEPARATOR.'helper'.DIRECTORY_SEPARATOR.'updateuserhelper.php' );

class BscoreModelUserlist extends JModelList {
	var $utotal = 0;
	var $limitstart = null;
	var $limit = null;
	
	function __construct()
	{
		parent::__construct();
		$app = JFactory::getApplication();
		$input = $app->input;
		$divcode = $input->getString('divcode');
		$search = $input->getString('search','');
		$limitstart = $input->get('limitstart',0,'uint');
		
		$app = JFactory::getApplication();
		if( !isset($divcode) && !isset($search) && !isset($limitstart) ) {
			$view = 'userlist';
			$app->setUserState("view{$view}divcode", 0);
			$app->setUserState("view{$view}search", '');
			$app->setUserState("view{$view}limitstart", 0);
		}
	}
	function store($data) {
		return true;
	}
	function getData() {
		$view = 'userlist';
		$app = JFactory::getApplication();
		$db	= JFactory::getDBO();
		
		$filter_divcode		= $app->getUserStateFromRequest( "view{$view}divcode",	'divcode','','string' );
		$search				= $app->getUserStateFromRequest( "view{$view}search",	'search','','string' );
		if (strpos($search, '"') !== false) {
			$search = str_replace(array('=', '<'), '', $search);
		}
		
		$limit		= $app->getUserStateFromRequest("global.list.limit", 'limit', 20, 'int');
		$limitstart	= $app->getUserStateFromRequest("view{$view}limitstart", 'limitstart', 0, 'int');

		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ( $limit != 0 ? (floor($limitstart / $limit) * $limit) : 0 );

		$wheres[] = 'a.`block` = 0';
		/*
		 * Add the filter specific information to the where clause
		 */
		// Section filter
		if ($filter_divcode) {
			$wheres[] = 'b.divcode = "' . $filter_divcode . '"';
		}

		// Keyword filter
		if ($search) {
			$wheres[] = '(LOWER( a.name ) LIKE '.$db->Quote( '%'.$db->escape( $search, true ).'%', false ) 
					  .' OR LOWER( a.username ) LIKE '.$db->Quote( '%'.$db->escape( $search, true ).'%', false ) 
					  .' OR LOWER( b.name1 ) LIKE '.$db->Quote( '%'.$db->escape( $search, true ).'%', false ) 
					  .'OR LOWER( b.name2 ) LIKE '.$db->Quote( '%'.$db->escape( $search, true ).'%', false ) 
					  .')' ;
		}

		// Build the where clause of the content record query
		$where = (count($wheres) ? ' WHERE '.implode(' AND ', $wheres) : '');

		// Get the total number of records
		$query = 'SELECT COUNT(*)' .
				' FROM #__users AS a' .
				' LEFT JOIN #__bs_users_detail AS b ON a.id = b.userid' .
				$where;
		$db->setQuery($query);
		$this->utotal = $db->loadResult();
		$this->limitstart = $limitstart;
		$this->limit = $limit;
		$order = " ORDER BY b.divcode,b.name1,b.name2 ";
		
		// Get users list
		$query = 'SELECT a.*, b.* , c.divname_s' .
				' FROM #__users AS a' .
				' LEFT JOIN #__bs_users_detail AS b ON a.id = b.userid' .
				' LEFT JOIN #__bs_division AS c ON b.divcode = c.divcode' .
				$where .
				$order;

		$db->setQuery($query, $this->limitstart, $this->limit);
		$rows = $db->loadObjectList();

		// If there is a database query error, throw a HTTP 500 and exit
		if ($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr() );
			return false;
		}
		return $rows;
	}
	function getPagination() {
		// Create the pagination object
		jimport('joomla.html.pagination');
		$pagination = new JPagination($this->utotal, $this->limitstart, $this->limit);
		return $pagination;
	}
	function getDivlist() {
		$db	= JFactory::getDBO();
		// Get divisions list
		$query = 'SELECT divcode,divname_s' .
				' FROM #__bs_division' .
				' WHERE div_stat = 1';
		$db->setQuery($query);
		$divlist = $db->loadObjectList();
		return $divlist;
	}
	function allupdate() {
		$ret = BsupdatedetailHelper::updateuserALL();
		return $ret;
	}
}
?>