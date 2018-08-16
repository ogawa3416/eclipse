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
 **/
// no direct access
defined('_JEXEC') or die;

require_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'baseview.class.php');

class BsbookingViewDashboard extends JBaseView
{
    function display($tpl = null)
    {
		$app = JFactory::getApplication();
		$pathway = $app->getPathway()->getPathway();
		$paths = count($pathway);
		if( $paths > 0 ) $paths-- ; 
		$link = $pathway[$paths]->link;
		
		$apparams = new JRegistry();
		$apparams->def('show_page_title', 1);
		$apparams->def('page_title', JText::_('COM_BSBOOKING_DASHBOARD'));
		
        $rows = $this->get("Items");
        $pagination = $this->get("Pagination");
        $sorting = $this->get("Sorting");
        $searchword = $this->get("SearchWord");
        $schedule_id = $this->get("ScheduleId");
        $this->assignRef('rows', $rows);
        $this->assignRef('pagination', $pagination);
        $this->assignRef('apparams', $apparams);
        $this->assignRef('sorting', $sorting);
        $this->assignRef('searchword', $searchword);
        $this->assignRef('scheduleid', $schedule_id);
        parent::display($tpl);
    }
}