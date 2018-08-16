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
 * @version		$Id: divlist.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
 **/
 
defined ('_JEXEC') or die();
jimport('joomla.application.component.modellist');

class BscoreModelDivlist extends JModelList {
	var $dtotal = 0;
	var $limitstart = null;
	var $limit = null;
	
	function __construct()
	{
		parent::__construct();
		
		$app = JFactory::getApplication();
		$input = $app->input;
		
		$limitstart = $input->get('limitstart',0,'uint');
		$app = JFactory::getApplication();
		if( !isset($limitstart) ) {
			$view = 'divlist';
			$app->setUserState("view{$view}limitstart", 0);
		}
	}
	function publish($onoff,$data) {
		$db	= JFactory::getDBO();
		$my = JFactory::getUser();
		$date = JFactory::getDate();
		$time = $date->tosql();

		foreach ($data['cid'] as $rowdiv)	{
			$divcode = null;
			$div_stat = null;
			for ($i=0;$i<count($data['code_stat']);$i++)	{
				list($divcode, $div_stat) = explode('/', $data['code_stat'][$i]);
				if( $divcode == $rowdiv ) break; 
			}
			if( $div_stat == $onoff || !(intval($div_stat) == 0 || intval($div_stat) == 1)) continue;
			if( !$divcode ) continue;

			$query = "UPDATE #__bs_division SET `div_stat` = ".$onoff
				." ,`modified` = ".$db->Quote($time) .", `modified_by` = ".$my->id
				." WHERE `divcode` = ". $db->Quote($rowdiv)
				;
			$db->setQuery($query);
			if ( !$db->execute() ) {
				JError::raiseError(500, $_db->getErrorMsg() );
			}
		}
		return true;
	}
	function store($data) {
		$app = JFactory::getApplication();
		$input = $app->input;
		
		$task = $input->getCmd('task');
		$db	= JFactory::getDBO();
		$my = JFactory::getUser();
		$date = JFactory::getDate();
		$time = $date->tosql();

		if( !$data['divcode'] ) {
			JError::raiseWarning( 0, JText::_('BSC_ERR_NOTDIVCODE'));
			return true;
		}
		if( $task == 'editnew' ) {
			$query = 'SELECT COUNT(*)' .
					' FROM #__bs_division WHERE divcode='.$db->Quote($data['divcode']);
					;
			$db->setQuery($query);
			$cnt = $db->loadResult();
			if ($cnt > 0) {
				JError::raiseWarning( 0, JText::_('BSC_ERR_DUPLICATEDIV'));
				return true;
			}
				
			$query = "INSERT INTO #__bs_division (`divcode`,`div_stat`,`divname`,`divname_s`,`divaddr`,`divzip`,`divtel`,`company`,"
				."\n `modified`, `modified_by`, `created`, `createdby`,`divtmpl` )  "
				."\n VALUES ( ".$db->Quote($data['divcode']).",".$db->Quote($data['div_stat']).",".$db->Quote($data['divname']).","
				."\n ".$db->Quote($data['divname_s']).",".$db->Quote($data['divaddr']).",".$db->Quote($data['divzip']).",".$db->Quote($data['divtel']). ",".$db->Quote($data['company']). ","
				."\n ".$db->Quote($time).",".$db->Quote($my->id).",".$db->Quote($time).",".$db->Quote($my->id).",".$db->Quote($data['divtmpl'])
				.")";
			$db->setQuery($query);
			$db->execute();
			if ($db->getErrorNum()) {
				if ($db->getErrorNum() == 1062) {
					JError::raiseWarning( 0, JText::_('BSC_ERR_DUPLICATEDIV'));
				} else {
					JError::raiseError(500, $db->getErrorMsg() );
				}
			}
		} else {
			$query = "UPDATE #__bs_division SET "
				."\n `divname`=".$db->Quote($data['divname']).",`divname_s`=".$db->Quote($data['divname_s'])
				."\n,`divaddr`=".$db->Quote($data['divaddr']).",`divzip`="   .$db->Quote($data['divzip'])
				."\n,`divtel`=" .$db->Quote($data['divtel']). ",`company`="  .$db->Quote($data['company'])
				."\n,`divtmpl`=".$db->Quote($data['divtmpl'])
				."\n,`modified`=" .$db->Quote($time).		",`modified_by`=". $db->Quote($my->id)
				." WHERE `divcode` = ". $db->Quote($data['divcode'])
				;
			$db->setQuery($query);
			$db->execute();
			if ($db->getErrorNum()) {
				JError::raiseError(500, $db->getErrorMsg() );
			}
		}
		return true;
	}
	function delete($data) {
		$db	= JFactory::getDBO();
		foreach ($data['cid'] as $divcode) {
			if( !$divcode ) continue;
			
			$query = "DELETE FROM #__bs_division WHERE `divcode` = ". $db->Quote($divcode)
				;
			$db->setQuery($query);
			if ( !$db->execute() ) {
				JError::raiseError(500, $_db->getErrorMsg() );
			}
		}
		return true;
	}
	function getData() {
		$view = 'divlist';
		$app = JFactory::getApplication();
		$db	= JFactory::getDBO();

		$limit		= $app->getUserStateFromRequest("global.list.limit", 'limit', 20, 'int');
		$limitstart	= $app->getUserStateFromRequest("view{$view}limitstart", 'limitstart', 0, 'int');

		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ( $limit != 0 ? (floor($limitstart / $limit) * $limit) : 0 );

		// Get the total number of records
		$query = 'SELECT COUNT(*)' .
				' FROM #__bs_division '
				;
		$db->setQuery($query);
		$this->dtotal = $db->loadResult();
		if( $this->dtotal <= $limitstart ) {
			$limitstart = $limitstart-$limit;
			if( $limitstart < 0 ) $limitstart = 0;
		}
		$this->limitstart = $limitstart;
		$this->limit = $limit;
		$order = " ORDER BY a.div_stat desc,a.divcode ";
		
		// Get division list
		$query = 'SELECT a.* ' .
				' FROM #__bs_division AS a' .
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
		$pagination = new JPagination($this->dtotal, $this->limitstart, $this->limit);
		return $pagination;
	}
	function getOneData() {
		$db	= JFactory::getDBO();
		$app = JFactory::getApplication();
		$input = $app->input;
		$divcode = $input->getVar( 'cid', array(0), 'array' );
		if( $divcode[0] ) {
			// Get users list
			$query = 'SELECT a.* ' .
				' FROM #__bs_division AS a' .
				' WHERE a.divcode='.$db->Quote($divcode[0]);
			$db->setQuery($query);
			$row = $db->loadObject();
			// If there is a database query error, throw a HTTP 500 and exit
			if ($db->getErrorNum()) {
				JError::raiseError( 500, $db->stderr() );
				return false;
			}
		} else {
			$row = new stdClass();
			$row->divcode="";
			$row->div_stat="0";
			$row->divname="";
			$row->divname_s="";
			$row->divaddr="";
			$row->divzip="";
			$row->divtel="";
			$row->company="";
			$row->divtmpl="";
		} 
		return $row;
	}
}
?>