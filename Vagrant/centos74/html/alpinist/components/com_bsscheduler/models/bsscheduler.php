<?php
/**
* @Copyright Copyright (C) 2010 dhtmlx.com
* @Copyright Copyright (C) 2010 Groon solutions (by modified portion)
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

/**
 This file is part of BSScheduler for Joomla.

    BSScheduler for Joomla is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    BSScheduler for Joomla is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with BSScheduler for Joomla.  If not, see <http://www.gnu.org/licenses/>.
**/

// no direct access
defined('_JEXEC') or die;
jimport( 'joomla.application.component.model' );
require_once(JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_bsscheduler'.DIRECTORY_SEPARATOR.'codebase'.DIRECTORY_SEPARATOR.'BSSchedulerConfigurator.php');

class BSSchedulerModelBSScheduler extends JModelLegacy {
	var $scheduler_log = false;
	var $scheduler_debug = false;

	function getScheduler() {
		$document = JFactory::getDocument();
		$locale = substr($document->language,0 ,2);

		$table = 'bs_scheduler_options';
		$tableEvents = 'bs_events_rec';
		$fieldName = 'name';
		$fieldValue = 'value';

		$url = JURI::root().'components/com_bsscheduler/codebase/';
		$cfg = new JConfig;
		$host = $cfg->host;
		$user = $cfg->user;
		$pass = $cfg->password;
		$db = $cfg->db;
		$prefix = $cfg->dbprefix;
		$userIdField = 'id';
		$userLoginField = 'username';
		$tableUsers = 'users';

		$curuser = $this->getUser();
		if( !$curuser->id ) {
			$usertype = "guest";
		} else {
			if (JAccess::check($curuser->id, 'core.admin')) {
				$usertype = "administrator";
				
			} else {
				$usertype = "registered";
			}
		}
		$userid = $curuser->id;
		$res=mysqli_connect($host,$user,$pass);
		mysqli_select_db($res,$db);
		
		$loader_url = JURI::root()."index.php?option=com_bsscheduler&view=bsscheduler&task=loadxml&changeuser=".$userid."&scheduler_events=";

		$cfg = new SchedulerConfig('scheduler_config_xml', $res, $table, $fieldName, $fieldValue, $tableEvents, $userIdField, $userLoginField, $tableUsers, $prefix, $userid, true);
		$scheduler = $cfg->schedulerInit($usertype, $locale, $url, $loader_url);
		return $scheduler;
	}


	function getUser() {
		$app = JFactory::getApplication();
		$input = $app->input;

		$ids = $input->get( 'ids' );
		$dispuid = intval($input->get('userid'));
		if( !$dispuid && $ids ) {
			$dispuid = intval($input->get($ids.'_user'));		
		}
		if( !$dispuid ) {
			$dispuid = intval($input->get('changeuser'));	
		}
		$user = clone JFactory::getUser();
		if( $user->id ) {
			if( $dispuid ) $user->id = $dispuid;
		}

		if( !$user->id ) {
			$usertype = "guest";
		} else {
			if (JAccess::check($user->id, 'core.admin')) {
				$usertype = "administrator";
//				$usertype = "superadministrator";
				
			} else {
				$usertype = "registered";
			}
		}

		$user->usertype = $usertype;
		if( $user->id  ) {
			$_db = JFactory::getDBO();
			$query = "SELECT a.name,c.divcode,c.divname  FROM #__users a,#__bs_users_detail b,#__bs_division c"
					."  WHERE b.divcode = c.divcode and a.id = b.userid"
					." and b.userid = ".$_db->Quote($user->id) ;
			$_db->setQuery( $query );
			$us = $_db->loadObject();
			if( $us ) {
				$user->name = $us->name; 
				$user->divcode = $us->divcode; 
				$user->divname = $us->divname; 
			}
		}

		if ($this->scheduler_log == true)
			error_log("SchedulerModelScheduler->getUser() was called\r\nresult = ".$usertype."\r\n\r\n", 3, JPATH_ROOT."/scheduler.log");
		return $user;
	}

	function getHolidays() {
		$app = JFactory::getApplication();
		$input = $app->input;
		$setdate = $input->get( 'setdate' );
		$mode = $input->get( 'mode' );

		$setdate = date('Y-m-d',  strtotime($setdate));

		$db	= JFactory::getDBO();
		
		$startday = $setdate;
		if($mode == 'day'){
			$endday = $setdate;
		}
		elseif($mode == 'week'){
			$endday = date('Y-m-d', strtotime($setdate.'+6 day'));
		}
		elseif($mode == 'month'){
			$endday = date('Y-m-t', strtotime($setdate));
		}
		elseif($mode == 'year'){
			$endday = date('Y-12-t', strtotime($setdate));
		}
		else{
			return [];
		}
		
		$query = 'SELECT a.holiday AS holiday' .
				' FROM #__bs_coholiday AS a' .
				' WHERE a.holiday >= '.$db->Quote($startday).' AND a.holiday <= '.$db->Quote($endday) .
				' AND holiday_stat = 1'.
				' ORDER BY a.holiday';
		$db->setQuery($query);
		$rows = $db->loadColumn();
		if ($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr() );
			return false;
		}
		return $rows;

	}

}