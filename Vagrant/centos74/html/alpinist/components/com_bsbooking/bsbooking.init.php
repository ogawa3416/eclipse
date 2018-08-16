<?php 
/**
 * BsBooking component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		GROON project
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2011 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: bsbooking.init.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.3 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;

require_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php');
require_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'dateutil.class.php');

define( 'RESERVATION_ONLY', 2 );
define( 'ALL', 3 );
define( 'READ_ONLY', 4 );

define('RES_TYPE_ADD', 'r');
define('RES_TYPE_MODIFY', 'm');
define('RES_TYPE_DELETE', 'd');
define('RES_TYPE_VIEW', 'v');
define('RES_TYPE_APPROVE', 'a');

define('SECONDS_IN_DAY', 86400);
define('RES_STATUS_APPROVED', 0);
define('RES_STATUS_REJECTED', 2);