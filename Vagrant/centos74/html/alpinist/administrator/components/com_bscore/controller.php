<?php
/**
 * BlogStone component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		BlogStone CGMS
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2010 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: admin.controller.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.0 $
 **/


// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * Blogstone Administrator Controller
 *
 * @package		BlogStone CGMS
 * @subpackage	Components
 * @since		joomla 1.5
 */
class BScoreController extends JController
{

	/**
	 * constructor (registers additional tasks to methods)
	 *
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
	}	

}
