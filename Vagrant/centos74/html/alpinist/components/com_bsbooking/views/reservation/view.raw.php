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
 * @version		$Id: view.raw.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.0 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');

/**
 *  View class for the Bsbooking component
 *
 * @static
 * @package		Joomla
 * @subpackage	Bsbooking
 * @since 1.5
 */
class BsbookingViewReservation extends JViewLegacy
{
    function display()
    {
        
        $doc = & JFactory::getDocument();
        $doc->setMimeEncoding("application/xml");
        
        echo '<?xml version="1.0" encoding="utf8" ?>';
    
        parent::display();
    }
}        