<?php
/**
 * JForms component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		BsAlpinist
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2010 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: install.JForms.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
 **/
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

ignore_user_abort( true );

define('JFORMS_BACKEND_PATH' ,  JPATH_ROOT.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_jforms');
define('JFORMS_FRONTEND_PATH',  JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_jforms');


class com_jformsInstallerScript {

	function install($parent) {
		if(JFolder::exists(JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms')){
			JFolder::delete(JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms');
		}
		JFolder::create(JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms');
		$textbuf = '<html><body bgcolor="#FFFFFF"></body></html>';
		JFile::write( JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms'.DIRECTORY_SEPARATOR.'index.html', $textbuf);
	
		//Copy Media files to Joomla's media directory
		$return =
			JFolder::move(  JFORMS_BACKEND_PATH.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms'.DIRECTORY_SEPARATOR.'styles',
					JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms'.DIRECTORY_SEPARATOR.'styles' );
		if( !is_bool($return)) {
			echo $return ;
		}
	
	
		$return =
		JFolder::move(  JFORMS_BACKEND_PATH.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms'.DIRECTORY_SEPARATOR.'scripts',
					JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms'.DIRECTORY_SEPARATOR.'scripts' );
		if( !is_bool($return)) {
			echo $return ;
		}

		$return =
		JFolder::move(  JFORMS_BACKEND_PATH.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms'.DIRECTORY_SEPARATOR.'images',
					JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms'.DIRECTORY_SEPARATOR.'images' );
		if( !is_bool($return)) {
			echo $return ;
		}

		$return =
		JFolder::move(  JFORMS_BACKEND_PATH.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms'.DIRECTORY_SEPARATOR.'plugins',
						JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms'.DIRECTORY_SEPARATOR.'plugins' );
		if( !is_bool($return)) {
			echo $return ;
		}
					
		$return =
		JFolder::move(  JFORMS_BACKEND_PATH.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms'.DIRECTORY_SEPARATOR.'files',
					JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms'.DIRECTORY_SEPARATOR.'files' );					
		if( !is_bool($return)) {
			echo $return ;
		}
//		JFolder::delete(JFORMS_BACKEND_PATH.DIRECTORY_SEPARATOR.'media');	
		$ret = "JForms component successfully installed.";
		echo "<br/>  Thank you for the installation JForms<br/><br/>This 'JFroms' is the modifier for BsAlpinist Ver.2.5 <br/>If you have any problems, contact <a href=\"mailto:infodesk@groon.co.jp\">infodesk@groon.co.jp</a><br/>Visit the <a href=\"http://www.groon.co.jp\">GROON solutions web site</a> for the lastest news and update." ;
		echo $ret;
	}
	function uninstall($parent) 
	{
		if(JFolder::exists(JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms')){
			JFolder::delete(JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms');	
		}
		// $parent is the class calling this method
		echo "<br/>  JForms component successfully uninstalled.<br/><br/>If you have any problems, contact <a href=\"mailto:infodesk@groon.co.jp\">infodesk@groon.co.jp</a><br/>Visit the <a href=\"http://www.groon.co.jp\">GROON solutions web site</a> for the lastest news and update." ;
	}
	function update($parent) 
	{
		if(JFolder::exists(JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms'.DIRECTORY_SEPARATOR.'styles')){
			JFolder::delete(JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms'.DIRECTORY_SEPARATOR.'styles');
		}
		$return =
			JFolder::move(  JFORMS_BACKEND_PATH.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms'.DIRECTORY_SEPARATOR.'styles',
					JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms'.DIRECTORY_SEPARATOR.'styles' );
		if( !is_bool($return)) {
			echo $return ;
		}
		if(JFolder::exists(JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms'.DIRECTORY_SEPARATOR.'scripts')){
			JFolder::delete(JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms'.DIRECTORY_SEPARATOR.'scripts');
		}
		$return =
		JFolder::move(  JFORMS_BACKEND_PATH.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms'.DIRECTORY_SEPARATOR.'scripts',
					JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms'.DIRECTORY_SEPARATOR.'scripts' );
		if( !is_bool($return)) {
			echo $return ;
		}
		if(JFolder::exists(JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms'.DIRECTORY_SEPARATOR.'images')){
			JFolder::delete(JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms'.DIRECTORY_SEPARATOR.'images');
		}
		$return =
		JFolder::move(  JFORMS_BACKEND_PATH.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms'.DIRECTORY_SEPARATOR.'images',
					JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms'.DIRECTORY_SEPARATOR.'images' );
		if( !is_bool($return)) {
			echo $return ;
		}
		if(JFolder::exists(JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms'.DIRECTORY_SEPARATOR.'plugins')){
			JFolder::delete(JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms'.DIRECTORY_SEPARATOR.'plugins');
		}
		$return =
		JFolder::move(  JFORMS_BACKEND_PATH.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms'.DIRECTORY_SEPARATOR.'plugins',
						JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms'.DIRECTORY_SEPARATOR.'plugins' );
		if( !is_bool($return)) {
			echo $return ;
		}
		
		// $parent is the class calling this method
		echo "<br/>  JForms component successfully updated.<br/><br/>If you have any problems, contact <a href=\"mailto:infodesk@groon.co.jp\">infodesk@groon.co.jp</a><br/>Visit the <a href=\"http://www.groon.co.jp\">GROON solutions web site</a> for the lastest news and update." ;
	}
}
