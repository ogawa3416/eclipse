<?php
/**
 * Alpinist component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		Alpinist
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2011 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: webclient.php 121 2016-04-01 22:53:33Z Alpinist ver.3.0.0 $
 **/
// no direct access
defined('_JEXEC') or die;

class BscoreHelperWebclient 
{
	static function getbrowser()
	{
		$classes = '';
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if( ( strpos($user_agent,'Android') !== false )
			&& strpos($user_agent,'Version') !== false ) {
			$classes = 'Android';
		} elseif( strpos($user_agent,'MSIE') !== false 
			|| ( strpos($user_agent,'Trident/7.0') !== false && strpos($user_agent,'rv:11') !== false )) {
			$classes = 'ie';
		} elseif( (strpos($user_agent,'iPhone') !== false 
			|| strpos($user_agent,'iPod') !== false )
			&& (strpos($user_agent,'Safari') !== false )) {
			$classes = 'iPhone';
		} elseif( (strpos($user_agent,'iPad') !== false )
			&& (strpos($user_agent,'Safari') !== false )) {
			$classes = 'iPad';
		}
		
		return $classes;
	}
}