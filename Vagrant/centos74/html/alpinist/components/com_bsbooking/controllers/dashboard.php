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
 * @version		$Id: dashboard.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
 *
 **/
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class BsbookingControllerDashboard extends BsbookingController
{
    function display($cachable = false, $urlparams = Array())
    {        
    	$app = JFactory::getApplication();
		$input = $app->input;
        $id = $input->getInt('id', 0); //schedule id
        
        $config = array();
        $config['schedule_id'] = (int)$id;
        $config['type'] = RESERVATION_ONLY;
        $config['own_items'] = true;
        $config['upcoming_items'] = true;
        
        $model = $this->getModel('reservations', 'BsbookingModel', $config);
        
        $view = $this->getView('dashboard', 'html');
        $view->setModel($model, true);
        $view->display();
    }
} 