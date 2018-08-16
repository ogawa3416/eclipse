<?php
/**
 * BsBooking component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		BsAlpinistt
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2011 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: view.html.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;
//ensure that we have JView
jimport('joomla.application.component.view');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php');

class BsbookingViewCpanel extends JViewLegacy
{
	function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	function display($tpl=null) 
	{
		JToolBarHelper::title( 'BsBooking :: '. JText::_('COM_BSBOOKING_CONTROL_PANEL'), 'generic.png');
		JToolBarHelper::preferences( 'com_bsbooking', '500','600' );
        
		$contents = '';
		ob_start();
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'cpanel'.DIRECTORY_SEPARATOR.'tmpl'.DIRECTORY_SEPARATOR.'navigation.php');
		$contents = ob_get_contents();
		ob_end_clean();	

		$document = JFactory::getDocument();
		$document->setBuffer($contents, 'modules', 'submenu');
		$document->setTitle( ' BsBooking :: ' .JText::_('COM_BSBOOKING_CONTROL_PANEL'));
		jimport('joomla.html.pane');
				
		echo "<div id=\"cpanel\">";
		$link = "index.php?option=com_bsbooking&task=schedules.display"; 
		BsbookingHelper::quickIconButton($link,  'bsbooking_scheduleadmin_48.png', JText::_('COM_BSBOOKING_SCHEDULE_MANAGER'));
				
		$link = 'index.php?option=com_bsbooking&task=resource.display';
		BsbookingHelper::quickiconButton( $link, 'bsbooking_resourceadmin_48.png', JText::_('COM_BSBOOKING_RESOURCE_MANAGER') );
		
		$link = 'index.php?option=com_bsbooking&task=reservation.getlist';
		BsbookingHelper::quickiconButton( $link, 'bsbooking_reservationadmin_48.png', JText::_('COM_BSBOOKING_RESERVATION_MANAGER') );			
		
		echo "</div>";
        
		parent::display($tpl);
	}

}