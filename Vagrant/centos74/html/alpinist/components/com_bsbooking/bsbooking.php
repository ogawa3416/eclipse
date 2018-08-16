<?php
/**
* @version		$Id: bsbooking.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.3 $
* @package		Joomla
* @subpackage	BsBooking
* @copyright	Copyright (C) 2009 Prasit Gebsaap. All rights reserved.
* @copyright	Copyright (C) 2010 Groon solutions. All rights reserved.
* @license		GNU/GPL, see GNU-LICENSE.txt in the installation package
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*
* Inspired by and partially based on:
*   The "jongman" compornent for Joomla 1.5.x 
*   Authors: Prasit Gebsaap
*   Copyright (c) 2009 Prasit Gebsaap.
*/
// no direct access
defined('_JEXEC') or die;

// Require the base controller
require_once (JPATH_COMPONENT.DIRECTORY_SEPARATOR.'controller.php');
require_once (JPATH_COMPONENT.DIRECTORY_SEPARATOR.'bsbooking.init.php');

$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::base().'components/com_bsbooking/assets/css/core.css');

// Require specific controller if requested
// Split task into command and task
$app = JFactory::getApplication();
$input = $app->input;
$cmd = $input->getCmd('task');
if (!$cmd) {
	$cmd = "schedule.display";
}

if (strpos($cmd, '.') != false) {
	// We have a defined controller/task pair -- lets split them out
	list($controllerName, $task) = explode('.', $cmd);
	
	// Define the controller name and path
	$controllerName	= strtolower($controllerName);
	$controllerPath	= JPATH_COMPONENT.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.$controllerName.'.php';
	
	// If the controller file path exists, include it ... else lets die with a 500 error
	if (file_exists($controllerPath)) {
		require_once($controllerPath);
	} else {
		JError::raiseError(500, 'Invalid Controller '.$controllerPath);
	}
} else {
	// Base controller, just set the task 
	$controllerName = null;
	$task = $cmd;
}

// Create the controller
$className	= 'BsbookingController'.ucfirst($controllerName);
$controller = new $className();


// Perform the Request task
$controller->execute($task);

// Redirect if set by the controller
$controller->redirect();