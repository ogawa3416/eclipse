<?php
/**
* Backend Entry point for JForms Component
*
* @version		$Id: jforms.php 362 2010-02-20 06:50:23Z dr_drsh $
* @package		Joomla
* @subpackage	JForms.Core
* @copyright	Copyright (C) 2008 Mostafa Muhammad. All rights reserved.
* @Copyright 	Copyright (C) 2010 Groon solutions (by modified portion) ver.2.3
* @license		GNU/GPL
*/

// no direct access
defined('_JEXEC') or die;

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

JTable::addIncludePath( JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'tables' );
JHTML::addIncludePath( JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'helpers' );

require_once JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'globals.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'version'.DIRECTORY_SEPARATOR.'version.php';

JFormsInitializePluginManager();

$app = JFactory::getApplication();
$input = $app->input;
$controller = $input->getWord('controller');
if($controller == ''){
	$controller = 'Forms';
} else {
	$controller = ucfirst(strtolower($controller));
}

//JSubMenuHelper::addEntry(JText::_('Forms')    , 'index.php?option=com_jforms&controller=Forms'   ,$controller=='Forms'  );
	
$controllerFilename  = JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.strtolower($controller).'.php';
$controllerClassname = $controller.'Controller';

if(!JFile::exists($controllerFilename)){
	JError::raiseError( 500, "Couldn't find file $controllerFilename" );
}

require_once $controllerFilename;

if(!class_exists($controllerClassname)){
	JError::raiseError( 500, "Couldn't find class $controllerClassname" );
}

$controller = new $controllerClassname();
// Perform the Request task
$controller->execute( $input->get('task') );

// Redirect if set by the controller
$controller->redirect();