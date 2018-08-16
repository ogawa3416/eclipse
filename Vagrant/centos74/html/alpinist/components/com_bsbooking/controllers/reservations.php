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
 * @version		$Id: reservations.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.0 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * Bsbooking Reservations Controller
 *
 * @package		Joomla
 * @subpackage	BsBooking
 * @since 1.0
 */
class BsbookingControllerReservations extends BsbookingController
{
    function getlist()
    {
    	$app = JFactory::getApplication();
		$input = $app->input;
        $user = & JFactory::getUser();
        if (empty($user->id))
        {
            $link = JSite::getMenu()->getActive()->link;
            $msg = JText::_('COM_BSBOOKING_NOT_ALLOW_OR_PLEASE_LOGIN');
            $this->setRedirect(JRoute::_($link, false), $msg, 'warning');
            return false;
        }
        $id = $input->getInt('id', 0); //schedule id
        $type = $input->getInt('type', ALL); //type of schedule
        
        $config = array();
        $config['schedule_id'] = (int)$id;
        $config['type'] = $type;
        
        $model = & $this->getModel('reservations', 'BsbookingModel', $config);
        
        $view = & $this->getView('reservations', 'html');
        $view->setModel($model, true);
        $view->display();
            
    }
    
    function approve()
    {
        $this->remove();    
    }
    
    function remove()
    {
        $id = BsbookingHelper::getScheduleFromMenuItem();
        $itemId = $input->getInt('Itemid',null);
        $append = '';
        if(isset($itemId)) $append = '&Itemid=' . $itemId;
        $msg = 'Sorry, but this function will be implemented in next beta release';
        $this->setRedirect(JRoute::_('index.php?option=com_bsbooking&task=reservations.getlist&id='.$id.$append, false), $msg);
    }	
}