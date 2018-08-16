<?php
/**
* @version		$Id: bsbooking.php  BsAlpinist ver.2.5.0 $
* @package		Joomla
* @subpackage	BsBooking
* @copyright	Copyright (C) 2010 GROON slutions. All rights reserved.
* @license		GNU/GPL, see GNU-LICENSE.txt in the installation package
* 
* Inspired by and partially based on:
*   The "jongman" compornent for Joomla 1.5.x 
*   Authors: Prasit Gebsaap
*   Copyright (c) 2009 Prasit Gebsaap.
*/
// no direct access
defined('_JEXEC') or die;
require_once(JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'controller.php');

$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::base().'components/com_bsbooking/assets/css/bsbooking.css');

$app = JFactory::getApplication();
$input = $app->input;
// Split task into controller and task
$cmd = $input->getCmd('task', 'cpanel.display');

if (strpos($cmd, '.') != false) {
	// We have a defined controller/task pair -- lets split them out
	list($controllerName, $task) = explode('.', $cmd);
	
	// Define the controller name and path
	$controllerName	= strtolower($controllerName);
	$controllerPath	= JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.$controllerName.'.php';

	// If the controller file path exists, include it ... else lets die with a 500 error
	if (file_exists($controllerPath)) {
		require_once($controllerPath);
	} else {
		JError::raiseError(500, 'Invalid Controller Path - '.$controllerPath);
	}
} else {
	// Base controller, just set the task 
	$controllerName = null;
	$task = $cmd;
}

// Set the name for the controller and instantiate it
$controllerClass = "BsbookingController".ucfirst($controllerName);
if (class_exists($controllerClass)) 
{
	$controller = new $controllerClass();
} else {
	JError::raiseError(500, 'Invalid Controller Class - '.$controllerClass );
}

// Perform the Request task
$controller->execute($task);

// Redirect if set by the controller
$controller->redirect();
