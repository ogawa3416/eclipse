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
 * @version		$Id: view.html.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class BsbookingViewSchedules extends JViewLegacy
{
	function display($tpl=null)
	{
		$document = JFactory::getDocument();
		$document->setTitle('BsBooking :: ' .JText::_('COM_BSBOOKING_SCHEDULE_MANAGER'));
        JToolBarHelper::title('BsBooking :: '.JText::_('COM_BSBOOKING_SCHEDULE_MANAGER'));
       	JToolBarHelper::addNew('schedule.add', JText::_('COM_BSBOOKING_Add'));
      	JToolBarHelper::editList('schedule.edit');
      	JToolBarHelper::divider();
      	JToolBarHelper::deleteList(JText::_('COM_BSBOOKING_DELETECONFIRM'),'schedule.remove');
      	JToolBarHelper::custom( 'cpanel.display', 'cpanel', 'cpanel', 'CPanel', false, false );

      	$schedules =  $this->get('Data');
      	$pagination =  $this->get('Pagination');
        $searchword =  $this->get('SearchWord');
      	$this->assignRef('schedules', $schedules);
      	$this->assignRef('pagination', $pagination);
      	$this->assignRef('searchword', $searchword);
      	parent::display($tpl);
	}
}