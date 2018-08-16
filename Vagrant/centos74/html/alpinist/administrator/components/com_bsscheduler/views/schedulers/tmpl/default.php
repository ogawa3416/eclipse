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

$document =  JFactory::getDocument();
$scheduler_locale = substr($document->language,0 ,2);

$scheduler_table = 'bs_scheduler_options';
$scheduler_tableEvents = 'bs_events_rec';
$scheduler_fieldName = 'name';
$scheduler_fieldValue = 'value';

$scheduler_usertype = getUser();

$scheduler_cfg = new JConfig;
$scheduler_host = $scheduler_cfg->host;
$scheduler_user = $scheduler_cfg->user;
$scheduler_pass = $scheduler_cfg->password;
$scheduler_db = $scheduler_cfg->db;
$scheduler_prefix = $scheduler_cfg->dbprefix;
$scheduler_userIdField = 'id';
$scheduler_userLoginField = 'username';
$scheduler_tableUsers = 'users';

$scheduler_res=mysqli_connect($scheduler_host,$scheduler_user,$scheduler_pass);
mysqli_select_db($scheduler_res,$scheduler_db);

$scheduler_cfg = new SchedulerConfig('scheduler_config_xml', $scheduler_res, $scheduler_table, $scheduler_fieldName, $scheduler_fieldValue, $scheduler_tableEvents, $scheduler_userIdField, $scheduler_userLoginField, $scheduler_tableUsers, $scheduler_prefix, false, true);

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

?>



<link rel='STYLESHEET' type='text/css' href='<?php echo JURI::root(); ?>components/com_bsscheduler/codebase/dhtmlx.css'>
<script src='<?php echo JURI::root(); ?>components/com_bsscheduler/codebase/dhtmlx.js' charset="utf-8"></script>
<script src="<?php echo JURI::root(); ?>components/com_bsscheduler/codebase/connector/connector.js" type="text/javascript" charset="utf-8"></script>
<script src='<?php echo JURI::root(); ?>components/com_bsscheduler/codebase/BSSchedulerConfigurator.js' charset="utf-8"></script>
<script src='<?php echo JURI::root(); ?>components/com_bsscheduler/codebase/locale/locale_<?php echo $scheduler_locale; ?>.js' charset="utf-8"></script>
<style>

ul.scheduler_problems {
	width: 47%;
	padding-top: 10px;
	padding-right: 10px;
	padding-bottom: 0px;
	padding-left: 14px;
	font-family: Tahoma;
	font-size: 12px;
	color: #555555;
	list-style-type: none;
}

ul.scheduler_problems li {
	background-color: #FFFBCC;
	border: 1px solid #E6DB55;
	padding-top: 10px;
	padding-left: 10px;
	padding-bottom: 10px;
}
</style>
<?php echo $scheduler_cfg->getProblems(); ?>
<script>
		var conf;
		window.onload = function() {
			conf = new SchedulerConfig({
				parent: 'schedulerConfigurator',
				hidden: 'scheduler_xml',
				access: 'joomla',
				url: '<?php echo JURI::root(); ?>components/com_bsscheduler/codebase/',
				url_load: '<?php echo JURI::root(); ?>/administrator/index.php?option=com_bsscheduler&view=scheduler&task=loadxml',
				wp_specific: false
			});
		}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<div id="schedulerConfigurator" style="position: relative; width: 800px; height: 620px; float: left;"></div>
<input type="hidden" id="scheduler_xml_version" name="scheduler_xml_version" value="<?php echo $scheduler_cfg->getXmlVersion(); ?>" />
<input type="hidden" id="scheduler_xml" name="scheduler_xml" value='' />
<input type="hidden" name="option" value="com_bsscheduler" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="" />
</form>