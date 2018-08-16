<?php
/**
 * BsScheduler module for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		BsScheduler
 * @subpackage	Modules
 * @copyright	Copyright (C) 2009-2011 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: mod_bsscheduler.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
 **/
// no direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once (dirname(__FILE__).DIRECTORY_SEPARATOR.'helper.php');
require_once( JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_bsscheduler'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php' );
//require_once( JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_bscore'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php' );
$app = JFactory::getApplication();
$input = $app->input;

	$ajaxmode = $input->get('bssmode',0);
	$params = new JRegistry;
//	if( $ajaxmode ){
		// Get module parameters
		$_db = JFactory::getDBO();
		$query = ' SELECT params FROM #__modules '
				.' WHERE module = "mod_bsscheduler" AND published = 1 LIMIT 0, 1' 
			;
		$_db->setQuery( $query );
		$para = $_db->loadResult();
		$params->loadString($para);
//	}

	$user = JFactory::getUser();
	if( !$user->id ) return '';
	$doc = JFactory::getDocument();

	$year	= $input->get('year',date('Y'));
	if( (int)$year < 1 ) $year = date('Y');
	$month	= $input->get('month',date('m'));
	if( (int)$month < 1 ) $month = date('m');

	$day	= $input->get('day',date('d'));
	if( (int)$day < 1 ) $day = date('d');
	$divcode = $input->get('divcd');
	$all= $params->get('alldivision',0);

	if( !$divcode ) {
		if( !$all ) {
			$_db = JFactory::getDBO();
			$query = ' SELECT a.divcode FROM #__bs_division a,#__bs_users_detail b '
					.' WHERE b.userid = '.$_db->Quote($user->id)
					.' AND b.divcode = a.divcode AND a.div_stat = 1 ' 
					;
			$_db->setQuery( $query );
			$divcode = $_db->loadResult();
			if ( $_db->getErrorNum() ) {
				JError::raiseError(500, $_db->getErrorMsg() );
			}
		} else {
			$division = 0;
		}
	}
	$helper = new modBsschedulerHelper($params,$divcode);
	if( $ajaxmode ) {
//		$schedata = $helper->showschedule($ajaxmode,$year,$month,$day,$module->id); 
		$ajaxmodid = $input->get('ajaxmodid');
		$schedata = $helper->showschedule($ajaxmode,$year,$month,$day,$ajaxmodid); 
		$helper->loadnow($schedata);
		if( $ajaxmode != 'reload' ) {
			exit();
		}
	}
	$ulistheight = $params->get('window_height',100);
	$dispnum= $params->get('display_num',5);
	$tmparts= $params->get('time_parts_num',3);
	$starthour = $helper->getstarthour();
	$endhour = $helper->getendhour();
	$fbgc = $helper->getfieldbgcolor();
	$fieldbgcolor = implode(',',$fbgc);
	$days = array();
	for($i=0;$i<$dispnum;$i++) {
		$time = mktime(0,0,0, $month, $day++, $year);
		$days[$i] = date('d',$time); 
	}
	$time0 = mktime(0,0,0, $month, $days[0], $year);
	$time = $time0 - $dispnum*86400;
	$lastdate = date('Y-m-d', $time); 
	$time = $time0 + $dispnum*86400;
	$nextdate  = date('Y-m-d', $time); 
	$userlist = BscoreHelper::getuserlistAC($divcode,$params->get('alldivision',1),"com_bsscheduler");
	JHtmlBehavior::framework();
//	$doc->addScriptDeclaration('location.href='. JURI::base().' '\';');
	$doc->addScript( 'modules/mod_bsscheduler/js/mod_bsscheduler.js' );

require(JModuleHelper::getLayoutPath('mod_bsscheduler'));
