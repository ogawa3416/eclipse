<?php
/**
 * BsBooking component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		GROON project
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2011 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: view.html.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.3 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;

require_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'baseview.class.php');

class BsbookingViewReservations extends JBaseView
{
    function display()
    {
//        JHTML::_('behavior.modal');
        JToolBarHelper::title('Bsbooking :: '.JText::_('COM_BSBOOKING_MANAGE_RESERVATIONS'));
        JToolBarHelper::custom('reservation.view', 'preview' , 'preview', "View" );
        JToolBarHelper::editList('reservation.edit');  
        JToolBarHelper::deleteList('', 'reservation.remove', "Delete");
       
        $rows = & $this->get("Items");
        $pagination = & $this->get("Pagination");
        $sorting = & $this->get("Sorting");
        $searchword = & $this->get("SearchWord");
        $schedule_id = $this->get("ScheduleId");
        $this->assignRef('rows', $rows);
        $this->assignRef('pagination', $pagination);
        $this->assignRef('sorting', $sorting);
        $this->assignRef('searchword', $searchword);
        $this->assignRef('scheduleid', $schedule_id);

        parent::display();
    }
}