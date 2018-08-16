<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_loginuser
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the latest functions only once
require_once( JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_bscore'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php' );

$user = JFactory::getUser();
$loginname = $user->get('name');
$loginusername = $user->get('username');
$logindivname = BscoreHelper::getuserdiv($user->get('id'));
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');

require JModuleHelper::getLayoutPath('mod_loginuser', $params->get('layout', 'default'));
