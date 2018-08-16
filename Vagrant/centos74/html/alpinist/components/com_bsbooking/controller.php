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
 * @version		$Id: controller.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
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
 * Bsbooking Component Controller
 *
 * @package		Joomla
 * @subpackage	Bsbooking
 * @since 1.5
 */
class BsbookingController extends JControllerLegacy
{
	/**
	 * Method to show a schedule view
	 *
	 * @access	public
	 * @since	1.5
	 */
	function display($cachable = false, $urlparams = Array())
	{
		$app = JFactory::getApplication();
		$input = $app->input;
		// Set a default view if none exists
		if ( ! $input->getCmd( 'view' ) ) {
			$input->set('view', 'schedule' );
		}
		// Default view is responsible for get associated model
		parent::display($cachable,$urlparams);
	}
}