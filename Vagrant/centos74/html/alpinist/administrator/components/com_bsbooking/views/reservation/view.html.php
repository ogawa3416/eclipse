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

class BsbookingViewReservation extends JViewLegacy
{
    function display($tpl = NULL)
    {   
        if ($this->getLayout()=='default') 
        {
      		$document = JFactory::getDocument();
            $document->setTitle('BsBooking :: ' .JText::_('COM_BSBOOKING_RESERVATION_MANAGER'));
		
            JToolBarHelper::title('BsBooking :: ' .JText::_( 'COM_BSBOOKING_RESERVATION_MANAGER' ),'generic.png' );
            JToolBarHelper::custom( 'cpanel.display', 'cpanel', 'cpanel', 'CPanel', false, false );
            $rows =  $this->get("Items");
            $pagination =  $this->get("Pagination");
            $this->assignRef('rows', $rows);
            $this->assignRef('pagination', $pagination);
        }
        parent::display($tpl);
        
    }
}