<?php
/**
 * BlogStone component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		BlogStone UGMS
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2011 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: bscore.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
 **/

// no direct access
defined('_JEXEC') or die;
// Include dependancies
jimport('joomla.application.component.controller');

$app = JFactory::getApplication();
$input = $app->input;
$cmd = $input->getCmd('task');
if (!$cmd) {
	$input->set('task',"user.getlist");
}

$controller = JControllerLegacy::getInstance('Bscore');

$controller->execute($input->get('task'));
$controller->redirect();