<?php
/**
* @package		BlogCalendar Reload
* @author		Juan Padial
* @authorwebsite	http://www.bloogie.es
* @license		GNU/GPL
*/
// no direct access
defined('_JEXEC') or die('Restricted access');
// import joomla controller library
jimport('joomla.application.component.controller');

$app = JFactory::getApplication();
$input = $app->input;

// Get an instance of the controller prefixed by BlogCalendar
$controller = JControllerLegacy::getInstance('BlogCalendar');
 
// Perform the Request task
$controller->execute($input->getCmd('task'));
 
// Redirect if set by the controller
$controller->redirect();
?>