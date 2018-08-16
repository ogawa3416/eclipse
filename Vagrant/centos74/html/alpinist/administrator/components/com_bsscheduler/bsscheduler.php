<?php
/**
* @Copyright Copyright (C) 2010 dhtmlx.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

/**
 This file is part of dhtmlxScheduler for Joomla.

    dhtmlxScheduler for Joomla is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    dhtmlxScheduler for Joomla is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with dhtmlxScheduler for Joomla.  If not, see <http://www.gnu.org/licenses/>.
**/
// no direct access
defined('_JEXEC') or die;

require_once( JPATH_COMPONENT.DIRECTORY_SEPARATOR.'controller.php' );

$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::base().'components/com_bsscheduler/assets/css/bsscheduler.css');

$app = JFactory::getApplication();
$input = $app->input;

//if($controller = $input->get( 'controller' )) {
//    require_once( JPATH_COMPONENT.DIRECTORY_SEPARATOR.'controller.php' );
//}

$app = JFactory::getApplication();
$input = $app->input;
// Split task into controller and task
$cmd = $input->getCmd('task', 'schedulers.show');

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

$classname    = 'BSschedulerController'.ucfirst($controllerName);
$controller   = new $classname( );
//$controller->execute($input->get('task'));
$controller->execute($task);
$controller->redirect();

?>
