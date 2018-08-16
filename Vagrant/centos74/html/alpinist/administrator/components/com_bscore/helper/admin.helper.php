<?php
/**
 * BlogStone component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		GROON UGMS
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2010 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: admin.helper.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.0 $
 **/

// no direct access
defined('_JEXEC') or die;

class BscoreAdminHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param	string	The name of the active view.
	 * @since	1.6
	 */
	public static function addSubmenu($vName = 'userlist')
	{
		JSubMenuHelper::addEntry(
			JText::_('BSC_ADDITIONAL_USER_INFORMATION'),
			'index.php?option=com_bscore&amp;task=userlist.show',
			$vName == 'userlist'
		);
		JSubMenuHelper::addEntry(
			JText::_('BSC_DIVISION_MANAGER'),
			'index.php?option=com_bscore&amp;task=divlist.show',
			$vName == 'divlist'
		);
		JSubMenuHelper::addEntry(
			JText::_('BSC_ACCESS_CONTROL_MANAGER'),
			'index.php?option=com_bscore&amp;task=accontrol.show',
			$vName == 'accontrol'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_BSCORE_HOLIDAY_MANAGER'),
			'index.php?option=com_bscore&amp;task=holiday.show',
			$vName == 'holiday'
		);
	}
}
