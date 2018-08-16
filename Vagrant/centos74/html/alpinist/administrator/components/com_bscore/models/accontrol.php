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
 * @version		$Id: accontrol.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
 **/
 
defined ('_JEXEC') or die();
jimport('joomla.application.component.modellist');

class BscoreModelAccontrol extends JModelList {
	var $atotal = 0;
	var $limitstart = null;
	var $limit = null;
	var $group = null;
	var $divkey = null;
	
	function __construct()
	{
		parent::__construct();
		
		$app = JFactory::getApplication();
		$input = $app->input;
		
		$acc = $input->get('com_group');
		if( $acc ) {
			$this->group = $acc;
		} else {
			$db	= JFactory::getDBO();
			$query = 'SELECT distinct com_group FROM #__bs_accontrol ORDER BY modified';
			$db->setQuery( $query );
			$accar = $db->loadObjectList();
			$this->group = $accar[0]->com_group;
		}
		$this->divkey = $input->get('divkey');
		
		$limitstart = $input->get('limitstart',0,'uint');
		$app = JFactory::getApplication();
		if( !isset($limitstart) ) {
			$view = 'accontrol';
			$app->setUserState("view{$view}limitstart", 0);
		}
	}

	function grstore($data) {
		$db	= JFactory::getDBO();
		$my = JFactory::getUser();
		$date = JFactory::getDate();
		$time = $date->tosql();
		if( !$data['new_group'] || !$data['divkey'] ) {
			JError::raiseWarning( 0, JText::_('BSC_ERR_NOTACCDEFINE'));
			return true;
		}
		$query = 'SELECT COUNT(*) FROM #__bs_accontrol '
				.' WHERE com_group ='.$db->Quote($data['new_group'])
				.' AND divkey='.$db->Quote($data['divkey']);
				;
		$db->setQuery($query);
		$cnt = $db->loadResult();
		if ($cnt > 0) {
			JError::raiseWarning( 0, JText::_('BSC_ERR_DUPLICATEDIV'));
			return true;
		}
		$query = "INSERT INTO #__bs_accontrol (`com_group`,`divkey`,"
				."\n `modified`, `modified_by`, `created`, `createdby` )  "
				."\n VALUES ( ".$db->Quote($data['new_group']).",".$db->Quote($data['divkey']).","
				."\n ".$db->Quote($time).",".$db->Quote($my->id).",".$db->Quote($time).",".$db->Quote($my->id)
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
		$this->group = $data['new_group'];
	}
	function divstore($data) {
		$db	= JFactory::getDBO();
		$my = JFactory::getUser();
		$date = JFactory::getDate();
		$time = $date->tosql();
		if( !$data['com_group'] || !$data['divkey'] ) {
			JError::raiseWarning( 0, JText::_('BSC_ERR_NOTACCDEFINE'));
			return true;
		}

		$query = "UPDATE #__bs_accontrol SET "
				."\n `ondiv`=".$db->Quote($data['ondivnew'])
				."\n,`modified`=" .$db->Quote($time).	",`modified_by`=". $db->Quote($my->id)
				." WHERE `com_group` = ". $db->Quote($data['com_group']). " AND `divkey` = ". $db->Quote($data['divkey'])
				;
		$db->setQuery($query);
		$db->execute();
		if ($db->getErrorNum()) {
				JError::raiseError(500, $db->getErrorMsg() );
		}
		return true;
	}
	function userstore($data) {
		$app = JFactory::getApplication();
		$input = $app->input;
		
		$task = $input->get('task');
		$db	= JFactory::getDBO();
		$my = JFactory::getUser();
		$date = JFactory::getDate();
		$time = $date->tosql();
		if( !$data['com_group'] || !$data['divkey'] ) {
			JError::raiseWarning( 0, JText::_('BSC_ERR_NOTACCDEFINE'));
			return true;
		}
		$onusers = "";
		if( $task == 'useradd' ) {
			if( !$data['onusernew'] ) return true;
			$query = 'SELECT onuser FROM #__bs_accontrol' 
				.' WHERE com_group ='.$db->Quote($data['com_group']).' AND divkey ='.$db->Quote($data['divkey'])
				;
			$db->setQuery($query);
			$oldonuser = $db->loadResult();
			if( strlen($oldonuser) > 2 ) { 
				$oldarr = explode("/", substr($oldonuser,1,strlen($oldonuser)-2));
				$addarr = explode("/", substr($data['onusernew'],1,strlen($data['onusernew'])-2));
				$newarr = array_merge($oldarr,$addarr) ;
				$newarr = array_unique($newarr);
				sort($newarr);
				$onusers = "/".implode("/",$newarr)."/";
			} else {
				$onusers = $data['onusernew'];
			}
		} else {
			$onusers = $data['onusernew'];
		}

		$query = "UPDATE #__bs_accontrol SET "
				."\n `onuser`=".$db->Quote($onusers)
				."\n,`modified`=" .$db->Quote($time).	",`modified_by`=". $db->Quote($my->id)
				." WHERE `com_group` = ". $db->Quote($data['com_group']). " AND `divkey` = ". $db->Quote($data['divkey'])
				;
		$db->setQuery($query);
		$db->execute();
		if ($db->getErrorNum()) {
				JError::raiseError(500, $db->getErrorMsg() );
		}
		return true;
	}
	function delete($data) {
		$db	= JFactory::getDBO();
		foreach ($data['cid'] as $divkey) {
			if( !$divkey ) continue;
			$query = "DELETE FROM #__bs_accontrol "
					." WHERE `com_group` = ". $db->Quote($data['com_group']) ." AND `divkey` = ".$db->Quote($divkey);
			$db->setQuery($query);
			$db->execute();
			if ($db->getErrorNum()) {
				JError::raiseError(500, $_db->getErrorMsg() );
			}
		}
		return true;
	}

	function getData() {
		$view = 'accontrol';
		$app = JFactory::getApplication();
		$db	= JFactory::getDBO();
		
		if( !$this->group ) {
			JError::raiseWarning( 0, JText::_('BSC_ERR_NOTACCDEFINE'));
			return true;
		}
		
		$limit		= $app->getUserStateFromRequest('global.list.limit', 'limit', 20, 'int');
		$limitstart	= $app->getUserStateFromRequest("view{$view}limitstart", 'limitstart', 0, 'int');

		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ( $limit != 0 ? (floor($limitstart / $limit) * $limit) : 0 );

		// Get the total number of records
		$query = 'SELECT COUNT(*)' .
				' FROM #__bs_accontrol WHERE com_group ='.$db->Quote($this->group)
				;
		$db->setQuery($query);
		$this->atotal = $db->loadResult();
		if( $this->atotal <= $limitstart ) {
			$limitstart = $limitstart-$limit;
			if( $limitstart < 0 ) $limitstart = 0;
		}
		$this->limitstart = $limitstart;
		$this->limit = $limit;
		
		
		// Get access control list
		$query = 'SELECT a.*, b.divname_s as divname,"" ondivstr, "" onuserstr '
				.' FROM #__bs_accontrol AS a ' 
				.' LEFT JOIN #__bs_division AS b ON a.divkey = b.divcode' 
				.' WHERE a.com_group ='.$db->Quote($this->group)
				.' ORDER BY a.divkey ';
		$db->setQuery($query, $this->limitstart, $this->limit);
		$rows = $db->loadObjectList();
		// If there is a database query error, throw a HTTP 500 and exit
		if ($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr() );
			return false;
		}
		for( $i=0;$i<count($rows);$i++ ) { 
			$rows[$i]->ondivstr = $this->divcodetoname($rows[$i]->ondiv);
			$rows[$i]->onuserstr = $this->usercodetoname($rows[$i]->onuser);
		}
		return $rows;
	}

	function getPagination() {
		// Create the pagination object
		jimport('joomla.html.pagination');
		$pagination = new JPagination($this->atotal, $this->limitstart, $this->limit);
		return $pagination;
	}
	function getOneData() {
		if( !$this->group ) {
			JError::raiseWarning( 0, JText::_('BSC_ERR_NOTACCDEFINE'));
			return true;
		}
		$db	= JFactory::getDBO();
		if( $this->divkey ) {
			// Get division list
			$query = 'SELECT a.*, b.divname_s as divname '
				.' FROM #__bs_accontrol AS a '
				.' LEFT JOIN #__bs_division AS b ON a.divkey = b.divcode' 
				.' WHERE a.com_group ='.$db->Quote($this->group)
				.' AND a.divkey='.$db->Quote($this->divkey);
			$db->setQuery($query);
			$row = $db->loadObject();
			// If there is a database query error, throw a HTTP 500 and exit
			if ($db->getErrorNum()) {
				JError::raiseError( 500, $db->stderr() );
				return false;
			}
			// make division sellect-list
			$row->divlist = new stdClass();
			$row->divlist = $this->getAllListDiv();
			$row->ondivlist = array(); 
			$row->ondivlist = explode("/", substr($row->ondiv,1,strlen($row->ondiv)-2));
			$allcnt = count($row->divlist);
			$rowcnt = count($row->ondivlist);
			if( $rowcnt == 0 ) {
				$row->divpages = 'none';
			} else if( $rowcnt == $allcnt ) {
				$row->divpages = 'all';
			} else {
				$row->divpages = '';
			}
			// make user sellect-list
			$row->userlist = new stdClass();
			$row->userlist = $this->getAllListUser();
			$row->onuserlist = array(); 
			$row->onuserlist = explode("/", substr($row->onuser,1,strlen($row->onuser)-2));
			$allcnt = count($row->userlist);
			$rowcnt = count($row->onuserlist);
			if( $rowcnt == 0 ) {
				$row->userpages = 'none';
			} else if( $rowcnt == $allcnt ) {
				$row->userpages = 'all';
			} else {
				$row->userpages = '';
			}
		} else {
			$row = new stdClass();
			$row->com_group=$this->group;
			$row->divkey="0";
			$row->divname="";
			$row->ondiv="";
			$row->onuser="";
			$row->divlist=$this->getAllListDiv();
			$row->ondivlist=null;
			$row->divpages="none";
			$row->userlist=$this->getAllListUser();
			$row->onuserlist=null;
			$row->userpages="none";
		} 
		return $row;
	}
	function getAccgrlist($name, $active = NULL, $javascript = NULL) {
		$db = JFactory::getDBO();
		$query = 'SELECT distinct com_group as value, com_group as text FROM #__bs_accontrol ORDER BY modified';
		$db->setQuery( $query );
		$acc = $db->loadObjectList();
		if( !$active ) $active = $this->group;
		$aclist = JHTML::_('select.genericlist',   $acc, $name, 'class="inputbox" size="1" '. $javascript, 'value', 'text', $active );
		return $aclist;
	}
	function divcodetoname($indiv)  {
		if( !$indiv ) return "";
		$search = "'".str_replace("/","','",substr($indiv,1,strlen($indiv)-2))."'";
		$db = JFactory::getDBO();
		$query = "SELECT divname_s " 
			."\n FROM #__bs_division  "
			."\n WHERE divcode IN (".$search.") "
		;
		$db->setQuery( $query );
		$divarr = $db->loadColumn();
		if( !count($divarr) ) return "";
		$outdiv = implode(", ", $divarr);
		return $outdiv;
	}
	function usercodetoname($inuser)  {
		if( !$inuser ) return "";
		$search = "'".str_replace("/","','",substr($inuser,1,strlen($inuser)-2))."'";
		$db = JFactory::getDBO();
		$query = "SELECT name " 
			."\n FROM #__users "
			."\n WHERE id IN (".$search.") "
		;
		$db->setQuery( $query );
		$userarr = $db->loadColumn();
		if( !count($userarr) ) return "";
		$outuser = implode(", ", $userarr);
		return $outuser;
	}
	function getAllListDiv()  {
		$db = JFactory::getDBO();
		$query = 'SELECT divcode `value`,divname_s `text` FROM #__bs_division  '
				.' WHERE div_stat = 1 ORDER BY divcode '
				;
		$db->setQuery( $query );
		return $db->loadObjectList();
	}
	function getAllListUser()  {
		$db = JFactory::getDBO();
		$app = JFactory::getApplication();
		$input = $app->input;
		$cdiv = $input->get('selectdiv');
		if( $cdiv && $cdiv != "0" ) {
			$addwhere = " AND b.divcode =".$db->Quote($cdiv);
		} else {
			$addwhere = "";
		}
		$query = 'SELECT a.id `value`,CONCAT(c.divname_s," : ",a.name) `text` FROM #__users a,#__bs_users_detail b,#__bs_division c '
				.' WHERE a.block = 0 AND a.id = b.userid AND b.divcode = c.divcode '
				. $addwhere ;
		$db->setQuery( $query );
		return $db->loadObjectList();
	}
	function getGroup()  {
		return $this->group;
	}
}
?>