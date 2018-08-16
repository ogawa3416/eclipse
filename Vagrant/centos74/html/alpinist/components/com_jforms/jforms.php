<?php
/**
* Frontend entry point for JForms Component
*
* @version		$Id: jforms.php BsAlpinist 2.4.1 $
* @package		Joomla
* @subpackage	JForms.Core
* @copyright	Copyright (C) 2008 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

require_once JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'globals.php' ;
require_once JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'pluginmanager'.DIRECTORY_SEPARATOR.'manager.php';
require_once JPATH_COMPONENT.DIRECTORY_SEPARATOR.'controller.php';

JHTML::addIncludePath ( JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'helpers' );

JFormsInitializePluginManager();

$app = JFactory::getApplication();
$input = $app->input;
$view = $input->get('view');
if( strlen($view) == 0 ) $view = 'form';
else if( $view === 'form' ) {
} else {
// Define the controller name and path
	$controllerName	= strtolower($view);
	$controllerPath	= JPATH_COMPONENT.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.$controllerName.'.php';
	// If the controller file path exists, include it ... else lets die with a 500 error
	if (file_exists($controllerPath)) {
		require_once($controllerPath);
	} else {
		JError::raiseError(500, 'Invalid Controller '.$controllerPath);
	}
}
// Create the controller "Frontend"
$classname	= 'FrontendController'.$view;

$controller = new $classname();

// Perform the Request task
$controller->execute( $input->get('task'));

// Redirect if set by the controller
$controller->redirect();