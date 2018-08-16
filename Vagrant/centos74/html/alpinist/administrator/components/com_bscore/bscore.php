<?php
/**
 * BlogStone component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		GROON CGMS
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2011 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: admin.bscore.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.3 $
 **/

// no direct access
defined('_JEXEC') or die;
// Require the base controller
//require_once (JPATH_COMPONENT.DS.'admin.controller.php');
// Set the helper directory
require_once (JPATH_COMPONENT.DIRECTORY_SEPARATOR.'helper'.DIRECTORY_SEPARATOR.'bscore.helper.php');

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_bscore')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

$app = JFactory::getApplication();
$input = $app->input;
$cmd = $input->getCmd('task');
if (strpos($cmd, '.') != false) {
	list($controllerName, $task) = explode('.', $cmd);
	// Define the controller name and path
	$controllerName	= strtolower($controllerName);
	$input->set('task',$task);
} else {
	// Base controller, just set the task
	$controllerName = $input->get('view');
	if( !$cmd ) $input->set('task','show');
}

if(!$controllerName ) $controllerName = 'userlist';
$input->set('controller',$controllerName);

$controllerPath	= JPATH_COMPONENT.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.$controllerName.'.php';

if (file_exists($controllerPath)) {
	require_once($controllerPath);
} else {
	JError::raiseError(500, 'Invalid Controller - '.$controllerPath);
}
// Set the name for the controller and instantiate it
$controllerClass = 'BscoreController'.ucfirst($controllerName);
if (class_exists($controllerClass)) {
	$controller = new $controllerClass();
} else {
	JError::raiseError(500, 'Invalid Controller Class - '.$controllerClass );
}

$controller->execute($input->getCmd('task'));
$controller->redirect();