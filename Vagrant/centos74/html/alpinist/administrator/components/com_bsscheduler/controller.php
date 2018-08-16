<?php
/**
* @Copyright Copyright (C) 2010 dhtmlx.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

/**
 This file is part of dhtmlxScheduler for Joomla.

    dhtmlxScheduler for Joomla is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    dhtmlxScheduler for Joomla is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with dhtmlxScheduler for Joomla.  If not, see <http://www.gnu.org/licenses/>.
**/

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class BsschedulerController extends JControllerLegacy
{
	private $scheduler_log = false;

	function __construct() {
		parent::__construct();
		$this->registerTask( 'save' , 'save' );
		$this->registerTask( 'cancel' , 'cancel' );
		$config = JFactory::getConfig();
	}


	function save() {
		$link = 'index.php?option=com_bsscheduler';
		$model = $this->getModel('schedulers');
		$app = JFactory::getApplication();
		$input = $app->input;
		$data_post = $input->getArray();
		$data['name'] = 'scheduler_xml';
		$data['value'] = rtrim($data_post['scheduler_xml'], "'");
		$model->store($data);
		$data['name'] = 'scheduler_xml_version';
		$data['value'] = $data_post['scheduler_xml_version'];
		$model->store($data);
		$this->setRedirect($link);
	}

	function cancel() {
		$this->setRedirect( 'index.php');
	}

	function default_xml() {
		$model = $this->getModel('schedulers');
		$app = JFactory::getApplication();
		$input = $app->input;
		
		$data_post = $input->getArray();
		$data['name'] = 'scheduler_xml';
		$data['value'] = '&ltesc;config&gtesc;&ltesc;active_tab&gtesc;a1&ltesc;/active_tab&gtesc;&ltesc;settings&gtesc;&ltesc;settings_width&gtesc;680px&ltesc;/settings_width&gtesc;&ltesc;settings_height&gtesc;680px&ltesc;/settings_height&gtesc;&ltesc;settings_eventnumber&gtesc;30&ltesc;/settings_eventnumber&gtesc;&ltesc;settings_link&gtesc;&ltesc;/settings_link&gtesc;&ltesc;settings_posts&gtesc;false&ltesc;/settings_posts&gtesc;&ltesc;settings_repeat&gtesc;true&ltesc;/settings_repeat&gtesc;&ltesc;settings_firstday&gtesc;true&ltesc;/settings_firstday&gtesc;&ltesc;settings_multiday&gtesc;true&ltesc;/settings_multiday&gtesc;&ltesc;settings_singleclick&gtesc;true&ltesc;/settings_singleclick&gtesc;&ltesc;settings_day&gtesc;false&ltesc;/settings_day&gtesc;&ltesc;settings_week&gtesc;true&ltesc;/settings_week&gtesc;&ltesc;settings_month&gtesc;true&ltesc;/settings_month&gtesc;&ltesc;settings_agenda&gtesc;true&ltesc;/settings_agenda&gtesc;&ltesc;settings_year&gtesc;true&ltesc;/settings_year&gtesc;&ltesc;settings_defaultmode&gtesc;month&ltesc;/settings_defaultmode&gtesc;&ltesc;settings_debug&gtesc;false&ltesc;/settings_debug&gtesc;&ltesc;settings_eventnumber&gtesc;30&ltesc;/settings_eventnumber&gtesc;&ltesc;settings_collision&gtesc;true&ltesc;/settings_collision&gtesc;&ltesc;settings_expand&gtesc;true&ltesc;/settings_expand&gtesc;&ltesc;settings_print&gtesc;true&ltesc;/settings_print&gtesc;&ltesc;settings_minical&gtesc;true&ltesc;/settings_minical&gtesc;&ltesc;/settings&gtesc;&ltesc;access&gtesc;&ltesc;access_guestView_j&gtesc;false&ltesc;/access_guestView_j&gtesc;&ltesc;access_guestAdd_j&gtesc;false&ltesc;/access_guestAdd_j&gtesc;&ltesc;access_guestEdit_j&gtesc;false&ltesc;/access_guestEdit_j&gtesc;&ltesc;access_registeredView_j&gtesc;true&ltesc;/access_registeredView_j&gtesc;&ltesc;access_registeredAdd_j&gtesc;true&ltesc;/access_registeredAdd_j&gtesc;&ltesc;access_registeredEdit_j&gtesc;true&ltesc;/access_registeredEdit_j&gtesc;&ltesc;access_authorView_j&gtesc;true&ltesc;/access_authorView_j&gtesc;&ltesc;access_authorAdd_j&gtesc;true&ltesc;/access_authorAdd_j&gtesc;&ltesc;access_authorEdit_j&gtesc;true&ltesc;/access_authorEdit_j&gtesc;&ltesc;access_editorView_j&gtesc;true&ltesc;/access_editorView_j&gtesc;&ltesc;access_editorAdd_j&gtesc;true&ltesc;/access_editorAdd_j&gtesc;&ltesc;access_editorEdit_j&gtesc;true&ltesc;/access_editorEdit_j&gtesc;&ltesc;access_publisherView_j&gtesc;true&ltesc;/access_publisherView_j&gtesc;&ltesc;access_publisherAdd_j&gtesc;true&ltesc;/access_publisherAdd_j&gtesc;&ltesc;access_publisherEdit_j&gtesc;true&ltesc;/access_publisherEdit_j&gtesc;&ltesc;access_managerView_j&gtesc;true&ltesc;/access_managerView_j&gtesc;&ltesc;access_managerAdd_j&gtesc;true&ltesc;/access_managerAdd_j&gtesc;&ltesc;access_managerEdit_j&gtesc;true&ltesc;/access_managerEdit_j&gtesc;&ltesc;access_administratorView_j&gtesc;true&ltesc;/access_administratorView_j&gtesc;&ltesc;access_administratorAdd_j&gtesc;true&ltesc;/access_administratorAdd_j&gtesc;&ltesc;access_administratorEdit_j&gtesc;true&ltesc;/access_administratorEdit_j&gtesc;&ltesc;access_superadministratorView_j&gtesc;true&ltesc;/access_superadministratorView_j&gtesc;&ltesc;access_superadministratorAdd_j&gtesc;true&ltesc;/access_superadministratorAdd_j&gtesc;&ltesc;access_superadministratorEdit_j&gtesc;true&ltesc;/access_superadministratorEdit_j&gtesc;&ltesc;privatemode&gtesc;on&ltesc;/privatemode&gtesc;&ltesc;/access&gtesc;&ltesc;templates&gtesc;&ltesc;templates_defaultdate&gtesc;&ltesc;![CDATA[%Y-%m-%d]]&gtesc;&ltesc;/templates_defaultdate&gtesc;&ltesc;templates_monthdate&gtesc;&ltesc;![CDATA[%Y年 %m月]]&gtesc;&ltesc;/templates_monthdate&gtesc;&ltesc;templates_weekdate&gtesc;&ltesc;![CDATA[%l]]&gtesc;&ltesc;/templates_weekdate&gtesc;&ltesc;templates_daydate&gtesc;&ltesc;![CDATA[%Y-%m-%d ]]&gtesc;&ltesc;/templates_daydate&gtesc;&ltesc;templates_hourdate&gtesc;&ltesc;![CDATA[%H:%i]]&gtesc;&ltesc;/templates_hourdate&gtesc;&ltesc;templates_monthday&gtesc;&ltesc;![CDATA[%d]]&gtesc;&ltesc;/templates_monthday&gtesc;&ltesc;templates_minmin&gtesc;&ltesc;![CDATA[15]]&gtesc;&ltesc;/templates_minmin&gtesc;&ltesc;templates_hourheight&gtesc;&ltesc;![CDATA[42]]&gtesc;&ltesc;/templates_hourheight&gtesc;&ltesc;templates_starthour&gtesc;&ltesc;![CDATA[8]]&gtesc;&ltesc;/templates_starthour&gtesc;&ltesc;templates_endhour&gtesc;&ltesc;![CDATA[22]]&gtesc;&ltesc;/templates_endhour&gtesc;&ltesc;templates_agendatime&gtesc;&ltesc;![CDATA[30]]&gtesc;&ltesc;/templates_agendatime&gtesc;&ltesc;templates_eventtext&gtesc;&ltesc;![CDATA[return event.text;]]&gtesc;&ltesc;/templates_eventtext&gtesc;&ltesc;templates_eventheader&gtesc;&ltesc;![CDATA[return scheduler.templates.hour_scale(start) + " - " + scheduler.templates.hour_scale(end);]]&gtesc;&ltesc;/templates_eventheader&gtesc;&ltesc;templates_eventbartext&gtesc;&ltesc;![CDATA[return "&ltesc;span title=&#8242"+scheduler.templates.hour_scale(start) + " - " + scheduler.templates.hour_scale(end)+" \n"+event.text+" \n[登録者]"+event.createdbyname+"&#8242&gtesc;" + event.text + "&ltesc;/span&gtesc;";]]&gtesc;&ltesc;/templates_eventbartext&gtesc;&ltesc;/templates&gtesc;&ltesc;customfields&gtesc;&ltesc;customfield name="Text" dsc="イベント概要" type="textarea" old_name="Text" use_colors="false" units="false" timeline="null" height="50" /&gtesc;&ltesc;customfield name="member" dsc="参加者　※）登録者のみ参加者全員のイベントを追加・削除・変更します" type="textarea" old_name="member" use_colors="false" units="false" timeline="off" height="100" /&gtesc;&ltesc;customfield name="eventdv" dsc="区分" type="select" old_name="eventdv" use_colors="true" units="false" timeline="off" &gtesc;&ltesc;option color="#ffff3f"&gtesc;社内会議&ltesc;/option&gtesc;&ltesc;option color="#ff7fbf"&gtesc;外出（直帰）&ltesc;/option&gtesc;&ltesc;option color="#bfff00"&gtesc;外出（帰社予定）&ltesc;/option&gtesc;&ltesc;option color="#bf7fff"&gtesc;出張&ltesc;/option&gtesc;&ltesc;option color="#00bfff"&gtesc;来客&ltesc;/option&gtesc;&ltesc;option color="#dfdfdf"&gtesc;その他&ltesc;/option&gtesc;&ltesc;/customfield&gtesc;&ltesc;/customfields&gtesc;&ltesc;/config&gtesc;';
		$model->store($data);
		$data['name'] = 'scheduler_xml_version';
		$data['value'] = $data_post['scheduler_xml_version'];
		$model->store($data);
		$link = 'index.php?option=com_bsscheduler';
		$this->setRedirect($link);
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

		$user = JFactory::getUser();
		$usertype = $this->getUser();
		$userid = $user->id;

		$cfg = new JConfig;
		$prefix = $cfg->dbprefix;
		$res = mysqli_connect($cfg->host, $cfg->user, $cfg->password);
		if (!$res) {
			die('Incorrect db configuration');
		}
		mysqli_select_db( $res,$cfg->db);

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

	function getUser() {
		$user = JFactory::getUser();
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
		return $usertype;
	}

}