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
 * @version		$Id: cpanel.php BsAlpinist ver.2.5.0 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class BsbookingControllerCpanel extends JControllerLegacy
{
	function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	/** this is default task */
	function display($cachable = false, $urlparams = Array() )
	{
		$app = JFactory::getApplication();
		$input = $app->input;
		
		$view = $input->getCmd('view', null);
		if (empty($view)) {
			/* JController->display uses this var to create view */
			$input->set('view','cpanel');
		}
		parent::display($cachable,$urlparams);
	}

}