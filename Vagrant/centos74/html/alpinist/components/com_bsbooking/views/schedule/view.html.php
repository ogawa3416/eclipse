<?php
/**
 * BsBooking component for Joomla.
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
 * @version		$Id: view.html.php BsAlpinist ver.2.5.0 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
jimport('joomla.environment.browser');
jimport('joomla.html.toolbar');
require_once(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'toolbar.php');
//require_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'baseview.class.php');
/**
 * HTML View class for the Bsbooking component
 *
 * @static
 * @package		Joomla
 * @subpackage	Bsbooking
 * @since 1.0
 */
class BsbookingViewSchedule extends JViewLegacy
{
	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$input = $app->input;
		
		$params = $app->getParams('com_bsbooking');
		$apparams = new JRegistry($params->get('params'));
		$apparams->merge($params);

		if( !$apparams->get('show_title') ) $apparams->def('show_title',1);
       JHTML::_('behavior.calendar');
	   JHTML::_('behavior.framework');
 //      JHTML::_('behavior.modal');
	   
       if ($apparams->get('customTooltip')) {
            $toolTipArray = array('className' => 'jm-tool', 'showDelay'=>'500', 'hideDelay'=>'500', 
                'fixed'=>false, 
                'onShow'=>"function(tip) {tip.effect('opacity', {duration: 500, wait: false}).start(0,1)}",
                'onHide'=>"function(tip) {tip.effect('opacity', {duration: 500, wait: false}).start(1,0)}");
            JHTML::_('behavior.tooltip', '.hasCustomTip', $toolTipArray);    
       }else{
            JHTML::_('behavior.tooltip');
       }
	   
	   $apparams->def('show_page_title', 	$params->get('show_title'));

	   $schedule = $this->get('Schedule');
       if (empty($schedule)){
            return false;
       }			   
       $user = JFactory::getUser();
       $dashboardUrl = '';
       if ( $user->id ){
	   		$dashboardUrl = 'index.php?option=com_bsbooking&task=dashboard.display&id='.$this->get('ScheduleId').'&Itemid='. $input->getInt('Itemid');
			$dashboardUrl = JRoute::_($dashboardUrl,false);
	   } else {
            /* If user is not login, we have to manually load joomla.javascript.js */
       }
       
       $browser = JBrowser::getInstance();
       $browserType = $browser->getBrowser();
       $ismobile = $browser->isMobile();
       if (count($schedule->getErrors())){
            JError::raise(E_WARNING, '500', 'Internal error, please contact system administrator.');
            return false;
       }
       // set of joomla default validation
	   JHTML::_('behavior.formvalidation');
		
       $user = JFactory::getUser();
	   $uri = JURI::getInstance()->toString();
       $this->assignRef('schedule', $schedule);
       $this->assignRef('browserType', $browserType);
       $this->assignRef('ismobile', $ismobile);
       $this->assignRef('dashboardUrl', $dashboardUrl);
	   $this->assignRef('user', $user);
	   $this->assignRef('apparams', $apparams);
	   $this->assignRef('action', 	$uri);
	   $this->assignRef('bar', 	$bar);
       parent::display($tpl);
	}

}
?>
