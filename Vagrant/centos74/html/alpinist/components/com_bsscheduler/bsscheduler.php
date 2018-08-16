<?php
/**
* @Copyright Copyright (C) 2010 dhtmlx.com
* @Copyright Copyright (C) 2010 Groon solutions (by modified portion) ver.2.0
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

/**
 This file is part of BSScheduler for Joomla.

    BSScheduler for Joomla is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    BSScheduler for Joomla is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with BSScheduler for Joomla.  If not, see <http://www.gnu.org/licenses/>.
**/

// no direct access
defined('_JEXEC') or die;
require_once( JPATH_COMPONENT.DIRECTORY_SEPARATOR.'controller.php' );

$app = JFactory::getApplication();
$input = $app->input;

if ($controller = $input->get('controller')) {
	$path = JPATH_COMPONENT.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}

$classname	= 'BSSchedulerController'.$controller;
$controller	= new $classname();
$controller->execute( $input->get( 'task' ) );

$controller->redirect();
