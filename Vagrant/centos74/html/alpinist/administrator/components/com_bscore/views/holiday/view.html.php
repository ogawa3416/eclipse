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
 * @copyright	Copyright (C) 2009-2010 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: holiday.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.6.0 $
 **/

// no direct access
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
require_once( JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'toolbar.php' );

class BscoreViewHoliday extends JViewLegacy
{
	
	function display($tpl = null) {
		JToolBarHelper::title("com_bscore".':'.JText::_("BSC_HOLIDAY_MANAGER"));
		$bar = JToolBar::getInstance('toolbar');
		JToolBarHelper::preferences('com_bscore');
		JToolBarHelper::publish('holiday.publish');
		JToolBarHelper::unpublish('holiday.unpublish');
		$bar->appendButton( 'Popup', 'new', 'JTOOLBAR_NEW', "index.php?option=com_bscore&tmpl=component&task=holiday.subnew", 740, 200 );
		JToolBarHelper::deleteList(JText::_("BSC_DELETE_MESSAGE"),'holiday.remove');
		$bar->appendButton('Confirm',JText::_('BSC_HOLIDAY_ADDYEAR_CONFIRM'), 'apply', JText::_('BSC_HOLIDAY_ADDYEAR'), 'holiday.newyear', false);
		JToolBarHelper::back();
		
		global $comcfg;
		JHTML::stylesheet('administrator/components/com_bscore/assets/css/bscoreadmin.css');
		$app = JFactory::getApplication();
		$input = $app->input;
		if( $input->get('layout') == 'edit' ) {
			// set of joomla default validation
			JHTML::_('behavior.formvalidation');
			$data = $this->get('OneData');
			$this->assignRef('data', $data);
		} else {
			$rows = $this->get('Data');
			JHTML::_('behavior.modal');
			$this->assignRef('rows', $rows);
			$yearall = $this->get('Yearall');
			$this->assignRef('yearall', $yearall);

			$year = $input->get('selected_y');
			if( !$year ) {
				if( isset($rows[0]->holiday) ) {
					$year = substr($rows[0]->holiday, 0, 4);
				} else {
					// Get the now year
					$config = JFactory::getConfig();
					$now = JFactory::getDate();
					$now->setTimeZone(new DateTimeZone($config->get('offset')));
					$year = $now->format( "Y",true );
				}
			}
			$this->assignRef('active', $year);
		}
		parent::display($tpl);
	}
}
?>