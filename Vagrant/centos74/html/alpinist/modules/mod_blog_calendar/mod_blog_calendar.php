<?php
/**
* @package		Blog Calendar
* @author		Juan Padial, based on previous version of Justo Gonzalez
* @license		GNU/GPL
*/


	// no direct access

	// Include the syndicate functions only once
	require_once (dirname(__FILE__).DIRECTORY_SEPARATOR.'helper.php');

	$app = JFactory::getApplication();
	$input = $app->input;

	$ajax= $input->getInt('ajaxCalMod',0);
	$ajaxmod= $input->getInt('ajaxmodid',0);	
	
	$offset= $app->getCfg('offset');
	$now = new JDate('now', $offset);
	if(!$params->get('cal_start_date')){
		$year	= $input->get('year',$now->format('Y',true));    /*if there is no date requested, use the current month*/
		$month	= $input->get('month',$now->format('m',true));
		$day	= $ajax? '' : $input->get('day');
	}
	else{
		
		$startDate= new JDate($params->get('cal_start_date'));		
		$year	= $input->get('year', $startDate->format('Y'));
		$month	= $input->get('month', $startDate->format('m'));
		$day	= $ajax? '' : $input->get('day', $startDate->format('d'));		
	}
	$helper = new modBlogCalendarHelper;
	$doc = JFactory::getDocument();

	if($ajax) {
		if($input->get('option')=='com_content' && $input->get('view')=='article') {
			list($ty,$tm,$td)=modBlogCalendarHelper::getDate_byId($input->get('id'));
			if( isset($ty) && isset($tm) && isset($td) && $ty == $year && $tm == $month ) $day = $td;
		}
		$calendar = $helper->showCal($params,$year,$month,$day,$ajax,$module->id); 
	} else {
		if($params->get('use_article_date','no') == 'yes') {
			if($input->get('option')=='com_content' && $input->get('view')=='article') {
				list($year,$month,$day)=modBlogCalendarHelper::getDate_byId($input->get('id'));
			}
		}
		$livesite = JURI::base();
		if($params->get('show_what') == '1') {/*a list*/
			$doc->addScript( $livesite.'modules/mod_blog_calendar/js/blog_list.js' );
			list($dropdown,$articleCounter) = $helper->showDropDown($params,$year,$month,$day,$ajax);	
		} else {
			$doc->addScript( $livesite.'modules/mod_blog_calendar/js/blog_calendar.js' );
			$calendar = $helper->showCal($params,$year,$month,$day,$ajax,$module->id);
		}
		JHTML::_('behavior.framework');
		$doc->addScriptDeclaration('var month=' . $month . '; var year=' . $year . '; var calendar_baseurl=\''. JURI::base() . '\';');
		JHTML::stylesheet(JUri::base().'modules/mod_blog_calendar/tmpl/style.css');
	}
require(JModuleHelper::getLayoutPath('mod_blog_calendar'));

?>