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
 * @version		$Id: schedules.php  BsAlpinist ver.2.5.0 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class BsbookingControllerSchedules extends JControllerLegacy
{
	
	function display( $cachable = false, $urlparams = Array() )
	{
		$model = $this->getModel('schedules','BsbookingModel');
		
		$view = $this->getView('schedules', 'html');
		$view->setModel($model, true);
		
		$view->display();
	}
    
    function cancel()
    {
        $url = 'index.php?option=com_bsbooking';
        $this->setRedirect($url);
    }
}