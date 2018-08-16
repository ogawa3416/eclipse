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

function com_install()
{

$db =& JFactory::getDBO();

$query = "SHOW TABLES LIKE '".$db->getPrefix()."bs_events_rec'";
$db->setQuery($query);
$events_rec_exists = $db->loadResult($query);
if (!$events_rec_exists) {
	$query = "CREATE TABLE IF NOT EXISTS `#__bs_events_rec` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `text` varchar(255) NOT NULL,
  `rec_type` varchar(64) NOT NULL,
  `event_pid` int(11) NOT NULL,
  `event_length` int(11) NOT NULL,
  `user` int(11) unsigned NOT NULL,
  `member` text NOT NULL,
  `eventdv` text NOT NULL,
  `event_mid` int(11) unsigned NOT NULL,
  `createdby` int(11) unsigned NOT NULL,
  `createdbyname` varchar(255) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;";
	$db->setQuery($query);
	$db->query();
/**
	$query = "INSERT IGNORE INTO `#__bs_events_rec`
(`event_id`, `start_date`, `end_date`, `text`, `event_pid`, `event_length`) VALUES
(1, NOW(), DATE_ADD(NOW(), INTERVAL 5 MINUTE), 'The Scheduler Calendar was installed!', 0, 0);";
	$db->setQuery($query);
	$db->query();
**/

	$query = "ALTER TABLE `#__bs_events_rec`  DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
	$db->setQuery($query);
	$db->query();

	$query = "ALTER TABLE `#__bs_events_rec` CHANGE `text` `text` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `rec_type` `rec_type` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;";
	$db->setQuery($query);
	$db->query();
}

$query = "SELECT * FROM #__bs_scheduler_options WHERE `name`='scheduler_php'";
$db->setQuery($query);
$config_exists = $db->loadResult($query);

if (!$config_exists) {

	$query = "SELECT * FROM #__bs_scheduler_options";
	$db->setQuery($query);
	$options = $db->loadObjectList();

	$export = false;
	for ($i = 0; $i < count($options); $i++) {
		if ($options[$i]->name == 'scheduler_xml') {
			$export = true;
		}
	}

	if (count($options) > 0) {
		if ($export == false) {
			for ($i = 0; $i < count($options); $i++) {
				$optionsArr[$options[$i]->name] = $options[$i]->value;
			}

			$optionsArr['width'] = '500px';
			$optionsArr['height'] = '500px';
			$optionsArr['sidebar'] = 5;
			$optionsArr['url'] = '';
			$optionsArr['multiline'] = 1;
			$optionsArr['on_click'] = 1;
			$optionsArr['collision'] = 'false';
			$optionsArr['expand'] = 'true';
			$optionsArr['print'] = 'false';
			$optionsArr['minical'] = 'false';
			$exportArray_settings['width'] = 'settings_width';
			$exportArray_settings['height'] = 'settings_height';
			$exportArray_settings['sidebar'] = 'settings_eventnumber';
			$exportArray_settings['url'] = 'settings_link';
			$exportArray_settings['repeat'] = 'settings_repeat';
			$exportArray_settings['first_day'] = 'settings_firstday';
			$exportArray_settings['multiline'] = 'settings_multiday';
			$exportArray_settings['on_click'] = 'settings_singleclick';
			$exportArray_settings['day'] = 'settings_day';
			$exportArray_settings['week'] = 'settings_week';
			$exportArray_settings['month'] = 'settings_month';
			$exportArray_settings['year'] = 'settings_year';
			$exportArray_settings['agenda'] = 'settings_agenda';
			$exportArray_settings['default_mode'] = 'settings_defaultmode';
			$exportArray_settings['debug'] = 'settings_debug';
			$exportArray_settings['collision'] = 'settings_collision';
			$exportArray_settings['expand'] = 'settings_expand';
			$exportArray_settings['print'] = 'settings_print';
			$exportArray_settings['minical'] = 'settings_minical';

			$exportArray_access['guests'] = 'access_guest';
			$exportArray_access['registered'] = 'access_registered';
			$exportArray_access['author'] = 'access_author';
			$exportArray_access['editor'] = 'access_editor';
			$exportArray_access['publisher'] = 'access_publisher';
			$exportArray_access['manager'] = 'access_manager';
			$exportArray_access['administrator'] = 'access_administrator';
			$exportArray_access['super_administrator'] = 'access_superadministrator';


			$xml = '&ltesc;config&gtesc;&ltesc;active_tab&gtesc;&ltesc;settings&gtesc;';
			foreach ($exportArray_settings as $k => $v) {
				$value = $optionsArr[$k];
				if ($value == '1') {
					$value = 'true';
				}
				if ($value == '0') {
					$value = 'false';
				}
				$xml .= "&ltesc;".$v."&gtesc;".$value."&ltesc;/".$v."&gtesc;";
			}
			$xml .= '&ltesc;/settings&gtesc;&ltesc;access&gtesc;';

			foreach ($exportArray_access as $k => $v) {
				$value = $optionsArr[$k];
				if ($value == '1') {
					$value = 'true';
				} else {
					$value = 'false';
				}
				$xml .= '&ltesc;'.$v.'View_j&gtesc;'.$value.'&ltesc;/'.$v.'View_j&gtesc;';
				$xml .= '&ltesc;'.$v.'Add_j&gtesc;'.$value.'&ltesc;/'.$v.'Add_j&gtesc;';
				$xml .= '&ltesc;'.$v.'Edit_j&gtesc;'.$value.'&ltesc;/'.$v.'Edit_j&gtesc;';
			}
			$xml .= '&ltesc;privatemode&gtesc;off&ltesc;/privatemode&gtesc;&ltesc;/access&gtesc;&ltesc;templates&gtesc;&ltesc;templates_defaultdate&gtesc;&ltesc;![CDATA[%d %M %Y]]&gtesc;&ltesc;/templates_defaultdate&gtesc;&ltesc;templates_monthdate&gtesc;&ltesc;![CDATA[%F %Y]]&gtesc;&ltesc;/templates_monthdate&gtesc;&ltesc;templates_weekdate&gtesc;&ltesc;![CDATA[%l]]&gtesc;&ltesc;/templates_weekdate&gtesc;&ltesc;templates_daydate&gtesc;&ltesc;![CDATA[%d/%m/%Y]]&gtesc;&ltesc;/templates_daydate&gtesc;&ltesc;templates_hourdate&gtesc;&ltesc;![CDATA[%H:%i]]&gtesc;&ltesc;/templates_hourdate&gtesc;&ltesc;templates_monthday&gtesc;&ltesc;![CDATA[%d]]&gtesc;&ltesc;/templates_monthday&gtesc;&ltesc;templates_minmin&gtesc;&ltesc;![CDATA[5]]&gtesc;&ltesc;/templates_minmin&gtesc;&ltesc;templates_hourheight&gtesc;&ltesc;![CDATA[40]]&gtesc;&ltesc;/templates_hourheight&gtesc;&ltesc;templates_starthour&gtesc;&ltesc;![CDATA[0]]&gtesc;&ltesc;/templates_starthour&gtesc;&ltesc;templates_endhour&gtesc;&ltesc;![CDATA[24]]&gtesc;&ltesc;/templates_endhour&gtesc;&ltesc;templates_agendatime&gtesc;&ltesc;![CDATA[30]]&gtesc;&ltesc;/templates_agendatime&gtesc;&ltesc;templates_eventtext&gtesc;&ltesc;![CDATA[return event.text;]]&gtesc;&ltesc;/templates_eventtext&gtesc;&ltesc;templates_eventheader&gtesc;&ltesc;![CDATA[return scheduler.templates.hour_scale(start) + " - " + scheduler.templates.hour_scale(end);]]&gtesc;&ltesc;/templates_eventheader&gtesc;&ltesc;templates_eventbartext&gtesc;&ltesc;![CDATA[return "&ltesc;span title=&#8242;"+event.text+ "&#8242;&gtesc;" + event.text + "&ltesc;/span&gtesc;";]]&gtesc;&ltesc;/templates_eventbartext&gtesc;&ltesc;/templates&gtesc;&ltesc;customfields&gtesc;&ltesc;customfield name="Text" dsc="Description" type="textarea" old_name="Text" use_colors="false" units="false" height="150" /&gtesc;&ltesc;/customfields&gtesc;&ltesc;/config&gtesc;';
		} else {
			$xml = '&ltesc;config&gtesc;&ltesc;active_tab&gtesc;a1&ltesc;/active_tab&gtesc;&ltesc;settings&gtesc;&ltesc;settings_width&gtesc;680px&ltesc;/settings_width&gtesc;&ltesc;settings_height&gtesc;680px&ltesc;/settings_height&gtesc;&ltesc;settings_eventnumber&gtesc;30&ltesc;/settings_eventnumber&gtesc;&ltesc;settings_link&gtesc;&ltesc;/settings_link&gtesc;&ltesc;settings_posts&gtesc;false&ltesc;/settings_posts&gtesc;&ltesc;settings_repeat&gtesc;true&ltesc;/settings_repeat&gtesc;&ltesc;settings_firstday&gtesc;true&ltesc;/settings_firstday&gtesc;&ltesc;settings_multiday&gtesc;true&ltesc;/settings_multiday&gtesc;&ltesc;settings_singleclick&gtesc;true&ltesc;/settings_singleclick&gtesc;&ltesc;settings_day&gtesc;false&ltesc;/settings_day&gtesc;&ltesc;settings_week&gtesc;true&ltesc;/settings_week&gtesc;&ltesc;settings_month&gtesc;true&ltesc;/settings_month&gtesc;&ltesc;settings_agenda&gtesc;true&ltesc;/settings_agenda&gtesc;&ltesc;settings_year&gtesc;true&ltesc;/settings_year&gtesc;&ltesc;settings_defaultmode&gtesc;month&ltesc;/settings_defaultmode&gtesc;&ltesc;settings_debug&gtesc;false&ltesc;/settings_debug&gtesc;&ltesc;settings_eventnumber&gtesc;30&ltesc;/settings_eventnumber&gtesc;&ltesc;settings_collision&gtesc;true&ltesc;/settings_collision&gtesc;&ltesc;settings_expand&gtesc;true&ltesc;/settings_expand&gtesc;&ltesc;settings_print&gtesc;true&ltesc;/settings_print&gtesc;&ltesc;settings_minical&gtesc;true&ltesc;/settings_minical&gtesc;&ltesc;/settings&gtesc;&ltesc;access&gtesc;&ltesc;access_guestView_j&gtesc;false&ltesc;/access_guestView_j&gtesc;&ltesc;access_guestAdd_j&gtesc;false&ltesc;/access_guestAdd_j&gtesc;&ltesc;access_guestEdit_j&gtesc;false&ltesc;/access_guestEdit_j&gtesc;&ltesc;access_registeredView_j&gtesc;true&ltesc;/access_registeredView_j&gtesc;&ltesc;access_registeredAdd_j&gtesc;true&ltesc;/access_registeredAdd_j&gtesc;&ltesc;access_registeredEdit_j&gtesc;true&ltesc;/access_registeredEdit_j&gtesc;&ltesc;access_authorView_j&gtesc;true&ltesc;/access_authorView_j&gtesc;&ltesc;access_authorAdd_j&gtesc;true&ltesc;/access_authorAdd_j&gtesc;&ltesc;access_authorEdit_j&gtesc;true&ltesc;/access_authorEdit_j&gtesc;&ltesc;access_editorView_j&gtesc;true&ltesc;/access_editorView_j&gtesc;&ltesc;access_editorAdd_j&gtesc;true&ltesc;/access_editorAdd_j&gtesc;&ltesc;access_editorEdit_j&gtesc;true&ltesc;/access_editorEdit_j&gtesc;&ltesc;access_publisherView_j&gtesc;true&ltesc;/access_publisherView_j&gtesc;&ltesc;access_publisherAdd_j&gtesc;true&ltesc;/access_publisherAdd_j&gtesc;&ltesc;access_publisherEdit_j&gtesc;true&ltesc;/access_publisherEdit_j&gtesc;&ltesc;access_managerView_j&gtesc;true&ltesc;/access_managerView_j&gtesc;&ltesc;access_managerAdd_j&gtesc;true&ltesc;/access_managerAdd_j&gtesc;&ltesc;access_managerEdit_j&gtesc;true&ltesc;/access_managerEdit_j&gtesc;&ltesc;access_administratorView_j&gtesc;true&ltesc;/access_administratorView_j&gtesc;&ltesc;access_administratorAdd_j&gtesc;false&ltesc;/access_administratorAdd_j&gtesc;&ltesc;access_administratorEdit_j&gtesc;false&ltesc;/access_administratorEdit_j&gtesc;&ltesc;access_superadministratorView_j&gtesc;true&ltesc;/access_superadministratorView_j&gtesc;&ltesc;access_superadministratorAdd_j&gtesc;true&ltesc;/access_superadministratorAdd_j&gtesc;&ltesc;access_superadministratorEdit_j&gtesc;true&ltesc;/access_superadministratorEdit_j&gtesc;&ltesc;privatemode&gtesc;on&ltesc;/privatemode&gtesc;&ltesc;/access&gtesc;&ltesc;templates&gtesc;&ltesc;templates_defaultdate&gtesc;&ltesc;![CDATA[%Y-%m-%d]]&gtesc;&ltesc;/templates_defaultdate&gtesc;&ltesc;templates_monthdate&gtesc;&ltesc;![CDATA[%Y年 %m月]]&gtesc;&ltesc;/templates_monthdate&gtesc;&ltesc;templates_weekdate&gtesc;&ltesc;![CDATA[%l]]&gtesc;&ltesc;/templates_weekdate&gtesc;&ltesc;templates_daydate&gtesc;&ltesc;![CDATA[%Y-%m-%d ]]&gtesc;&ltesc;/templates_daydate&gtesc;&ltesc;templates_hourdate&gtesc;&ltesc;![CDATA[%H:%i]]&gtesc;&ltesc;/templates_hourdate&gtesc;&ltesc;templates_monthday&gtesc;&ltesc;![CDATA[%d]]&gtesc;&ltesc;/templates_monthday&gtesc;&ltesc;templates_minmin&gtesc;&ltesc;![CDATA[15]]&gtesc;&ltesc;/templates_minmin&gtesc;&ltesc;templates_hourheight&gtesc;&ltesc;![CDATA[42]]&gtesc;&ltesc;/templates_hourheight&gtesc;&ltesc;templates_starthour&gtesc;&ltesc;![CDATA[8]]&gtesc;&ltesc;/templates_starthour&gtesc;&ltesc;templates_endhour&gtesc;&ltesc;![CDATA[22]]&gtesc;&ltesc;/templates_endhour&gtesc;&ltesc;templates_agendatime&gtesc;&ltesc;![CDATA[30]]&gtesc;&ltesc;/templates_agendatime&gtesc;&ltesc;templates_eventtext&gtesc;&ltesc;![CDATA[return event.text;]]&gtesc;&ltesc;/templates_eventtext&gtesc;&ltesc;templates_eventheader&gtesc;&ltesc;![CDATA[return scheduler.templates.hour_scale(start) + " - " + scheduler.templates.hour_scale(end);]]&gtesc;&ltesc;/templates_eventheader&gtesc;&ltesc;templates_eventbartext&gtesc;&ltesc;![CDATA[return "&ltesc;span title=\'&quot;+scheduler.templates.hour_scale(start) + &quot; - &quot; + scheduler.templates.hour_scale(end)+&quot; \n&quot;+event.text+&quot; \n[登録者]&quot;+event.createdbyname+&quot;\'&gtesc;&quot; + event.text + &quot;&ltesc;/span&gtesc;&quot;;]]&gtesc;&ltesc;/templates_eventbartext&gtesc;&ltesc;/templates&gtesc;&ltesc;customfields&gtesc;&ltesc;customfield name=&quot;Text&quot; dsc=&quot;イベント概要&quot; type=&quot;textarea&quot; old_name=&quot;Text&quot; use_colors=&quot;false&quot; units=&quot;false&quot; timeline=&quot;null&quot; height=&quot;50&quot; /&gtesc;&ltesc;customfield name=&quot;member&quot; dsc=&quot;参加者　※）登録者のみ参加者全員のイベントを追加・削除・変更します&quot; type=&quot;textarea&quot; old_name=&quot;member&quot; use_colors=&quot;false&quot; units=&quot;false&quot; timeline=&quot;off&quot; height=&quot;100&quot; /&gtesc;&ltesc;customfield name=&quot;eventdv&quot; dsc=&quot;区分&quot; type=&quot;select&quot; old_name=&quot;eventdv&quot; use_colors=&quot;true&quot; units=&quot;false&quot; timeline=&quot;off&quot; &gtesc;&ltesc;option color=&quot;#ffff3f&quot;&gtesc;社内会議&ltesc;/option&gtesc;&ltesc;option color=&quot;#ff7fbf&quot;&gtesc;外出（直帰）&ltesc;/option&gtesc;&ltesc;option color=&quot;#bfff00&quot;&gtesc;外出（帰社予定）&ltesc;/option&gtesc;&ltesc;option color=&quot;#bf7fff&quot;&gtesc;出張&ltesc;/option&gtesc;&ltesc;option color=&quot;#00bfff&quot;&gtesc;来客&ltesc;/option&gtesc;&ltesc;option color=&quot;#dfdfdf&quot;&gtesc;その他&ltesc;/option&gtesc;&ltesc;/customfield&gtesc;&ltesc;/customfields&gtesc;&ltesc;/config&gtesc;';
		}
	} else {
		$xml = '&ltesc;config&gtesc;&ltesc;active_tab&gtesc;a1&ltesc;/active_tab&gtesc;&ltesc;settings&gtesc;&ltesc;settings_width&gtesc;680px&ltesc;/settings_width&gtesc;&ltesc;settings_height&gtesc;680px&ltesc;/settings_height&gtesc;&ltesc;settings_eventnumber&gtesc;30&ltesc;/settings_eventnumber&gtesc;&ltesc;settings_link&gtesc;&ltesc;/settings_link&gtesc;&ltesc;settings_posts&gtesc;false&ltesc;/settings_posts&gtesc;&ltesc;settings_repeat&gtesc;true&ltesc;/settings_repeat&gtesc;&ltesc;settings_firstday&gtesc;true&ltesc;/settings_firstday&gtesc;&ltesc;settings_multiday&gtesc;true&ltesc;/settings_multiday&gtesc;&ltesc;settings_singleclick&gtesc;true&ltesc;/settings_singleclick&gtesc;&ltesc;settings_day&gtesc;false&ltesc;/settings_day&gtesc;&ltesc;settings_week&gtesc;true&ltesc;/settings_week&gtesc;&ltesc;settings_month&gtesc;true&ltesc;/settings_month&gtesc;&ltesc;settings_agenda&gtesc;true&ltesc;/settings_agenda&gtesc;&ltesc;settings_year&gtesc;true&ltesc;/settings_year&gtesc;&ltesc;settings_defaultmode&gtesc;month&ltesc;/settings_defaultmode&gtesc;&ltesc;settings_debug&gtesc;false&ltesc;/settings_debug&gtesc;&ltesc;settings_eventnumber&gtesc;30&ltesc;/settings_eventnumber&gtesc;&ltesc;settings_collision&gtesc;true&ltesc;/settings_collision&gtesc;&ltesc;settings_expand&gtesc;true&ltesc;/settings_expand&gtesc;&ltesc;settings_print&gtesc;true&ltesc;/settings_print&gtesc;&ltesc;settings_minical&gtesc;true&ltesc;/settings_minical&gtesc;&ltesc;/settings&gtesc;&ltesc;access&gtesc;&ltesc;access_guestView_j&gtesc;false&ltesc;/access_guestView_j&gtesc;&ltesc;access_guestAdd_j&gtesc;false&ltesc;/access_guestAdd_j&gtesc;&ltesc;access_guestEdit_j&gtesc;false&ltesc;/access_guestEdit_j&gtesc;&ltesc;access_registeredView_j&gtesc;true&ltesc;/access_registeredView_j&gtesc;&ltesc;access_registeredAdd_j&gtesc;true&ltesc;/access_registeredAdd_j&gtesc;&ltesc;access_registeredEdit_j&gtesc;true&ltesc;/access_registeredEdit_j&gtesc;&ltesc;access_authorView_j&gtesc;true&ltesc;/access_authorView_j&gtesc;&ltesc;access_authorAdd_j&gtesc;true&ltesc;/access_authorAdd_j&gtesc;&ltesc;access_authorEdit_j&gtesc;true&ltesc;/access_authorEdit_j&gtesc;&ltesc;access_editorView_j&gtesc;true&ltesc;/access_editorView_j&gtesc;&ltesc;access_editorAdd_j&gtesc;true&ltesc;/access_editorAdd_j&gtesc;&ltesc;access_editorEdit_j&gtesc;true&ltesc;/access_editorEdit_j&gtesc;&ltesc;access_publisherView_j&gtesc;true&ltesc;/access_publisherView_j&gtesc;&ltesc;access_publisherAdd_j&gtesc;true&ltesc;/access_publisherAdd_j&gtesc;&ltesc;access_publisherEdit_j&gtesc;true&ltesc;/access_publisherEdit_j&gtesc;&ltesc;access_managerView_j&gtesc;true&ltesc;/access_managerView_j&gtesc;&ltesc;access_managerAdd_j&gtesc;true&ltesc;/access_managerAdd_j&gtesc;&ltesc;access_managerEdit_j&gtesc;true&ltesc;/access_managerEdit_j&gtesc;&ltesc;access_administratorView_j&gtesc;true&ltesc;/access_administratorView_j&gtesc;&ltesc;access_administratorAdd_j&gtesc;false&ltesc;/access_administratorAdd_j&gtesc;&ltesc;access_administratorEdit_j&gtesc;false&ltesc;/access_administratorEdit_j&gtesc;&ltesc;access_superadministratorView_j&gtesc;true&ltesc;/access_superadministratorView_j&gtesc;&ltesc;access_superadministratorAdd_j&gtesc;true&ltesc;/access_superadministratorAdd_j&gtesc;&ltesc;access_superadministratorEdit_j&gtesc;true&ltesc;/access_superadministratorEdit_j&gtesc;&ltesc;privatemode&gtesc;on&ltesc;/privatemode&gtesc;&ltesc;/access&gtesc;&ltesc;templates&gtesc;&ltesc;templates_defaultdate&gtesc;&ltesc;![CDATA[%Y-%m-%d]]&gtesc;&ltesc;/templates_defaultdate&gtesc;&ltesc;templates_monthdate&gtesc;&ltesc;![CDATA[%Y年 %m月]]&gtesc;&ltesc;/templates_monthdate&gtesc;&ltesc;templates_weekdate&gtesc;&ltesc;![CDATA[%l]]&gtesc;&ltesc;/templates_weekdate&gtesc;&ltesc;templates_daydate&gtesc;&ltesc;![CDATA[%Y-%m-%d ]]&gtesc;&ltesc;/templates_daydate&gtesc;&ltesc;templates_hourdate&gtesc;&ltesc;![CDATA[%H:%i]]&gtesc;&ltesc;/templates_hourdate&gtesc;&ltesc;templates_monthday&gtesc;&ltesc;![CDATA[%d]]&gtesc;&ltesc;/templates_monthday&gtesc;&ltesc;templates_minmin&gtesc;&ltesc;![CDATA[15]]&gtesc;&ltesc;/templates_minmin&gtesc;&ltesc;templates_hourheight&gtesc;&ltesc;![CDATA[42]]&gtesc;&ltesc;/templates_hourheight&gtesc;&ltesc;templates_starthour&gtesc;&ltesc;![CDATA[8]]&gtesc;&ltesc;/templates_starthour&gtesc;&ltesc;templates_endhour&gtesc;&ltesc;![CDATA[22]]&gtesc;&ltesc;/templates_endhour&gtesc;&ltesc;templates_agendatime&gtesc;&ltesc;![CDATA[30]]&gtesc;&ltesc;/templates_agendatime&gtesc;&ltesc;templates_eventtext&gtesc;&ltesc;![CDATA[return event.text;]]&gtesc;&ltesc;/templates_eventtext&gtesc;&ltesc;templates_eventheader&gtesc;&ltesc;![CDATA[return scheduler.templates.hour_scale(start) + " - " + scheduler.templates.hour_scale(end);]]&gtesc;&ltesc;/templates_eventheader&gtesc;&ltesc;templates_eventbartext&gtesc;&ltesc;![CDATA[return "&ltesc;span title=\'&quot;+scheduler.templates.hour_scale(start) + &quot; - &quot; + scheduler.templates.hour_scale(end)+&quot; \n&quot;+event.text+&quot; \n[登録者]&quot;+event.createdbyname+&quot;\'&gtesc;&quot; + event.text + &quot;&ltesc;/span&gtesc;&quot;;]]&gtesc;&ltesc;/templates_eventbartext&gtesc;&ltesc;/templates&gtesc;&ltesc;customfields&gtesc;&ltesc;customfield name=&quot;Text&quot; dsc=&quot;イベント概要&quot; type=&quot;textarea&quot; old_name=&quot;Text&quot; use_colors=&quot;false&quot; units=&quot;false&quot; timeline=&quot;null&quot; height=&quot;50&quot; /&gtesc;&ltesc;customfield name=&quot;member&quot; dsc=&quot;参加者　※）登録者のみ参加者全員のイベントを追加・削除・変更します&quot; type=&quot;textarea&quot; old_name=&quot;member&quot; use_colors=&quot;false&quot; units=&quot;false&quot; timeline=&quot;off&quot; height=&quot;100&quot; /&gtesc;&ltesc;customfield name=&quot;eventdv&quot; dsc=&quot;区分&quot; type=&quot;select&quot; old_name=&quot;eventdv&quot; use_colors=&quot;true&quot; units=&quot;false&quot; timeline=&quot;off&quot; &gtesc;&ltesc;option color=&quot;#ffff3f&quot;&gtesc;社内会議&ltesc;/option&gtesc;&ltesc;option color=&quot;#ff7fbf&quot;&gtesc;外出（直帰）&ltesc;/option&gtesc;&ltesc;option color=&quot;#bfff00&quot;&gtesc;外出（帰社予定）&ltesc;/option&gtesc;&ltesc;option color=&quot;#bf7fff&quot;&gtesc;出張&ltesc;/option&gtesc;&ltesc;option color=&quot;#00bfff&quot;&gtesc;来客&ltesc;/option&gtesc;&ltesc;option color=&quot;#dfdfdf&quot;&gtesc;その他&ltesc;/option&gtesc;&ltesc;/customfield&gtesc;&ltesc;/customfields&gtesc;&ltesc;/config&gtesc;';
	}

	$query = "DROP TABLE IF EXISTS  `#__bs_scheduler_options`;";
	$db->setQuery($query);
	$db->query();

	$query = "CREATE TABLE IF NOT EXISTS `#__bs_scheduler_options` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(255) NOT NULL, `value` text NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;";
	$db->setQuery($query);
	$db->query();

	$query = "INSERT INTO `#__bs_scheduler_options` (`id`, `name`, `value`) VALUES (null, 'scheduler_xml', '".$xml."'), (null, 'scheduler_php', ''), (null, 'scheduler_php_version', '0'), (null, 'scheduler_xml_version', '1'), (null, 'sidebar_num', '5'), (null, 'scheduler_stable_config', '".$xml."');";
	$db->setQuery($query);
	$db->query();
}

$query = "SELECT * FROM #__bs_scheduler_options WHERE `name`='scheduler_stable_config'";
$db->setQuery($query);
$stable_config_exists = $db->loadResult($query);
if (!$stable_config_exists) {
	$xml = '&ltesc;config&gtesc;&ltesc;active_tab&gtesc;a1&ltesc;/active_tab&gtesc;&ltesc;settings&gtesc;&ltesc;settings_width&gtesc;680px&ltesc;/settings_width&gtesc;&ltesc;settings_height&gtesc;600px&ltesc;/settings_height&gtesc;&ltesc;settings_eventnumber&gtesc;2&ltesc;/settings_eventnumber&gtesc;&ltesc;settings_link&gtesc;&ltesc;/settings_link&gtesc;&ltesc;settings_posts&gtesc;false&ltesc;/settings_posts&gtesc;&ltesc;settings_repeat&gtesc;true&ltesc;/settings_repeat&gtesc;&ltesc;settings_firstday&gtesc;false&ltesc;/settings_firstday&gtesc;&ltesc;settings_multiday&gtesc;true&ltesc;/settings_multiday&gtesc;&ltesc;settings_singleclick&gtesc;false&ltesc;/settings_singleclick&gtesc;&ltesc;settings_day&gtesc;true&ltesc;/settings_day&gtesc;&ltesc;settings_week&gtesc;true&ltesc;/settings_week&gtesc;&ltesc;settings_month&gtesc;true&ltesc;/settings_month&gtesc;&ltesc;settings_agenda&gtesc;false&ltesc;/settings_agenda&gtesc;&ltesc;settings_year&gtesc;false&ltesc;/settings_year&gtesc;&ltesc;settings_defaultmode&gtesc;month&ltesc;/settings_defaultmode&gtesc;&ltesc;settings_debug&gtesc;false&ltesc;/settings_debug&gtesc;&ltesc;settings_eventnumber&gtesc;2&ltesc;/settings_eventnumber&gtesc;&ltesc;settings_collision&gtesc;false&ltesc;/settings_collision&gtesc;&ltesc;settings_expand&gtesc;true&ltesc;/settings_expand&gtesc;&ltesc;settings_print&gtesc;false&ltesc;/settings_print&gtesc;&ltesc;settings_minical&gtesc;false&ltesc;/settings_minical&gtesc;&ltesc;/settings&gtesc;&ltesc;access&gtesc;&ltesc;access_guestView_j&gtesc;true&ltesc;/access_guestView_j&gtesc;&ltesc;access_guestAdd_j&gtesc;false&ltesc;/access_guestAdd_j&gtesc;&ltesc;access_guestEdit_j&gtesc;false&ltesc;/access_guestEdit_j&gtesc;&ltesc;access_registeredView_j&gtesc;true&ltesc;/access_registeredView_j&gtesc;&ltesc;access_registeredAdd_j&gtesc;false&ltesc;/access_registeredAdd_j&gtesc;&ltesc;access_registeredEdit_j&gtesc;false&ltesc;/access_registeredEdit_j&gtesc;&ltesc;access_authorView_j&gtesc;true&ltesc;/access_authorView_j&gtesc;&ltesc;access_authorAdd_j&gtesc;false&ltesc;/access_authorAdd_j&gtesc;&ltesc;access_authorEdit_j&gtesc;false&ltesc;/access_authorEdit_j&gtesc;&ltesc;access_editorView_j&gtesc;true&ltesc;/access_editorView_j&gtesc;&ltesc;access_editorAdd_j&gtesc;false&ltesc;/access_editorAdd_j&gtesc;&ltesc;access_editorEdit_j&gtesc;false&ltesc;/access_editorEdit_j&gtesc;&ltesc;access_publisherView_j&gtesc;true&ltesc;/access_publisherView_j&gtesc;&ltesc;access_publisherAdd_j&gtesc;false&ltesc;/access_publisherAdd_j&gtesc;&ltesc;access_publisherEdit_j&gtesc;false&ltesc;/access_publisherEdit_j&gtesc;&ltesc;access_managerView_j&gtesc;true&ltesc;/access_managerView_j&gtesc;&ltesc;access_managerAdd_j&gtesc;true&ltesc;/access_managerAdd_j&gtesc;&ltesc;access_managerEdit_j&gtesc;true&ltesc;/access_managerEdit_j&gtesc;&ltesc;access_administratorView_j&gtesc;true&ltesc;/access_administratorView_j&gtesc;&ltesc;access_administratorAdd_j&gtesc;true&ltesc;/access_administratorAdd_j&gtesc;&ltesc;access_administratorEdit_j&gtesc;true&ltesc;/access_administratorEdit_j&gtesc;&ltesc;access_superadministratorView_j&gtesc;true&ltesc;/access_superadministratorView_j&gtesc;&ltesc;access_superadministratorAdd_j&gtesc;true&ltesc;/access_superadministratorAdd_j&gtesc;&ltesc;access_superadministratorEdit_j&gtesc;true&ltesc;/access_superadministratorEdit_j&gtesc;&ltesc;privatemode&gtesc;off&ltesc;/privatemode&gtesc;&ltesc;/access&gtesc;&ltesc;templates&gtesc;&ltesc;templates_defaultdate&gtesc;&ltesc;![CDATA[%d %M %Y]]&gtesc;&ltesc;/templates_defaultdate&gtesc;&ltesc;templates_monthdate&gtesc;&ltesc;![CDATA[%F %Y]]&gtesc;&ltesc;/templates_monthdate&gtesc;&ltesc;templates_weekdate&gtesc;&ltesc;![CDATA[%l]]&gtesc;&ltesc;/templates_weekdate&gtesc;&ltesc;templates_daydate&gtesc;&ltesc;![CDATA[%d/%m/%Y]]&gtesc;&ltesc;/templates_daydate&gtesc;&ltesc;templates_hourdate&gtesc;&ltesc;![CDATA[%H:%i]]&gtesc;&ltesc;/templates_hourdate&gtesc;&ltesc;templates_monthday&gtesc;&ltesc;![CDATA[%d]]&gtesc;&ltesc;/templates_monthday&gtesc;&ltesc;templates_minmin&gtesc;&ltesc;![CDATA[5]]&gtesc;&ltesc;/templates_minmin&gtesc;&ltesc;templates_hourheight&gtesc;&ltesc;![CDATA[40]]&gtesc;&ltesc;/templates_hourheight&gtesc;&ltesc;templates_starthour&gtesc;&ltesc;![CDATA[0]]&gtesc;&ltesc;/templates_starthour&gtesc;&ltesc;templates_endhour&gtesc;&ltesc;![CDATA[24]]&gtesc;&ltesc;/templates_endhour&gtesc;&ltesc;templates_agendatime&gtesc;&ltesc;![CDATA[30]]&gtesc;&ltesc;/templates_agendatime&gtesc;&ltesc;templates_eventtext&gtesc;&ltesc;![CDATA[return event.text;]]&gtesc;&ltesc;/templates_eventtext&gtesc;&ltesc;templates_eventheader&gtesc;&ltesc;![CDATA[return scheduler.templates.hour_scale(start) + " - " + scheduler.templates.hour_scale(end);]]&gtesc;&ltesc;/templates_eventheader&gtesc;&ltesc;templates_eventbartext&gtesc;&ltesc;![CDATA[return "&ltesc;span title=&#8242;"+event.text+"&#8242;&gtesc;" + event.text + "&ltesc;/span&gtesc;";]]&gtesc;&ltesc;/templates_eventbartext&gtesc;&ltesc;/templates&gtesc;&ltesc;customfields&gtesc;&ltesc;customfield name="Text" dsc="Description" type="textarea" old_name="Text" use_colors="false" units="false" height="150" /&gtesc;&ltesc;/customfields&gtesc;&ltesc;/config&gtesc;';
	$query = "INSERT INTO `#__bs_scheduler_options` (`id`, `name`, `value`) VALUES (null, 'scheduler_stable_config', '".$xml."');";
	$db->setQuery($query);
	$db->query();
}

$query = "ALTER TABLE `#__bs_events_rec` ADD COLUMN `user` int(11) NOT NULL";
$db->setQuery($query);
$db->query();

	echo "<br/>  Thank you for the installation BsScheduler<br/><br/>If you have any problems, contact <a href=\"mailto:infodesk@groon.co.jp\">infodesk@groon.co.jp</a><br/>Visit the <a href=\"http://www.groon.co.jp\">GROON solutions web site</a> for the lastest news and update." ;
    
    return true;
}