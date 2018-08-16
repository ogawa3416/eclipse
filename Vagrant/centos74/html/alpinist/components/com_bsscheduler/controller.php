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
jimport('joomla.application.component.controller');

class BSSchedulerController extends JControllerLegacy
{

	function display($cachable = false, $urlparams = array())
	{
		$myuser = JFactory::getUser();
		if( !$myuser->id ) {
			$msg = JText::_('JLIB_ENVIRONMENT_SESSION_EXPIRED');
			$link = 'index.php';
			$this->setRedirect($link, $msg);
			return false;
		}
		parent::display($cachable,$urlparams);
	}

	function loadxml() {
		require_once(JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_bsscheduler'.DIRECTORY_SEPARATOR.'codebase'.DIRECTORY_SEPARATOR.'BSSchedulerConfigurator.php');
		$table = 'bs_scheduler_options';
		$tableEvents = 'bs_events_rec';
		$fieldName = 'name';
		$fieldValue = 'value';
		$userIdField = 'id';
		$userLoginField = 'username';
		$tableUsers = 'users';

		$model = $this->getModel();
		$user = $model->getUser(); 
		$userid = $user->id;
		$curuser = JFactory::getUser();
		if( !$curuser->id ) {
			$usertype = "guest";
		} else {
			if (JAccess::check($curuser->id, 'core.admin')) {
				$usertype = "administrator";
				
			} else {
				$usertype = "registered";
			}
		}		
		$cfg = new JConfig;
		$prefix = $cfg->dbprefix;
		$res = mysqli_connect($cfg->host, $cfg->user, $cfg->password);

		if (!$res) {
			die('Incorrect db configuration');
		}
		mysqli_select_db($res,$cfg->db);

		$cfg = new SchedulerConfig('scheduler_config_xml', $res, $table, $fieldName, $fieldValue, $tableEvents, $userIdField, $userLoginField, $tableUsers, $prefix, $userid, true);
		if (isset($_GET['config_xml'])) {
			header('Content-type: text/xml');
			echo $cfg->getXML();
			die();
		} else {
			if (isset($_GET['grid_events'])) {
				$cfg->getEventsRecGrid(true);
			} else {
				if (isset($_GET['scheduler_events'])) {
					$cfg->getEventsRec($usertype, true);
					return "";
				}
			}
		}
	}
	function getulist()
	{
		// check session
		$myuser = JFactory::getUser();
		if( !$myuser->id ) {
			$msg = JText::_('JLIB_ENVIRONMENT_SESSION_EXPIRED');
			$link = 'index.php';
			$this->setRedirect($link, $msg);
			return false;
		}
        $model =  $this->getModel('User','BSSchedulerModel');
        $view =  $this->getView("User", "html");
        $view->setModel($model, true);
        $view->display();  
	}
	function loadulist() {
		$app = JFactory::getApplication();
		$input = $app->input;
		// check session
		$myuser = JFactory::getUser();
		if( !$myuser->id ) {
			return "";
		}
		$divcode = $input->get('did');
		if( !$divcode ) return "";
		$_db = JFactory::getDBO();
		$query = "SELECT u.`id`,u.`name` " 
			."\n FROM #__users as u, #__bs_accontrol a, #__bs_users_detail b, #__bs_users_detail c  "
			."\n WHERE u.block = 0 AND a.com_group = 'com_bsscheduler'"
			."\n AND c.userid = u.id AND b.userid = ".$_db->Quote($myuser->id)
			."\n AND a.divkey = c.divcode"
			."\n AND ( a.ondiv like concat('%/',b.divcode,'/%')"
			."\n OR  a.onuser like concat('%/',b.userid,'/%' ))"
			."\n AND c.divcode = ".$_db->Quote($divcode)
			."\n ORDER BY u.name ASC";
			;
		$_db->setQuery( $query );
		$users = $_db->loadAssocList();
		ob_clean();
		header("Content-type:text/xml");
		echo "<?xml version='1.0' ?>";
		$snddata = "<data>";
		$snddata .= "<userlist division='{$divcode}' >";
		foreach ($users as $v) {
			$snddata .= "<uid><![CDATA[".$v['id']."]]></uid>";
			$snddata .= "<uname><![CDATA[".$v['name']."]]></uname>";
		}
		$snddata .= "</userlist>";
		$snddata .= "</data>";
		echo $snddata;
		die();
	}

	function getHolidays(){

		$model = $this->getModel();
		$hash = $model->getHolidays(); 

		ob_clean();
		echo json_encode($hash);
		jexit();

	}

	function modload() {
		include(JPATH_ROOT.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'mod_bsscheduler'.DIRECTORY_SEPARATOR.'mod_bsscheduler.php');
	}
}