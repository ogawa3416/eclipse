<?php
/**
* JForms Installation script
*
* @version		$Id: install.php 362 2010-02-20 06:50:23Z dr_drsh $
* @package		Joomla
* @subpackage	JForms.Install
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/


// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

ignore_user_abort( true );

define('JFORMS_BACKEND_PATH' ,  JPATH_ROOT.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_jforms');
define('JFORMS_FRONTEND_PATH',  JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_jforms');

/**
 * Installs and publishes JForms content plugin
 * 
 * @access	public
 */
function com_install() {
/**
	$db =& JFactory::getDBO();

	/*					Install plugin 					*/
/**
	if(!JFile::exists(JPATH_ROOT.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'content'.DIRECTORY_SEPARATOR.'jforms.php')){
		JFile::move(
				JFORMS_BACKEND_PATH.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'content'.DIRECTORY_SEPARATOR.'jforms.php',
				JPATH_ROOT.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'content'.DIRECTORY_SEPARATOR.'jforms.php'
		);
		JFile::move(
				JFORMS_BACKEND_PATH.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'content'.DIRECTORY_SEPARATOR.'jforms.xml',
				JPATH_ROOT.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'content'.DIRECTORY_SEPARATOR.'jforms.xml'
		);
	}
	JFolder::delete(JFORMS_BACKEND_PATH.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'content');

	$query = 'SELECT `published` FROM `#__plugins` WHERE `element`="jforms"';
	$db->setQuery($query);
	$result = $db->loadObject();
	
	if($result == null){
		$query = 
		"INSERT INTO `#__plugins` ( `name`, `element`, `folder`, `access`, `ordering`, `published`, `iscore`, `client_id`, `checked_out`, `checked_out_time`, `params`)"
		."\nVALUES"
		."\n('Content - JForms', 'jforms', 'content', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', '')";
		$db->setQuery($query);
		$db->execute();
	} else {
		$query = 'UPDATE `#__plugins` SET `published` = 1 WHERE `element`="jforms"';
		$db->setQuery($query);
		$db->execute();
	}

	/*					Done installing plugin					*/
	
	//Set Component Icon
//	$db->setQuery("UPDATE #__components SET admin_menu_img='../media/com_jforms/images/icon-16-component.png' WHERE admin_menu_link='option=com_jforms'");
//	$res = $db->execute();
	
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
//	JFolder::delete(JFORMS_BACKEND_PATH.DIRECTORY_SEPARATOR.'media');	
	$ret = "JForms component successfully installed.";
	echo "<br/>  Thank you for the installation JForms<br/><br/>This 'JFroms' is the modifier for BsAlpinist Ver.2.4 <br/>If you have any problems, contact <a href=\"mailto:infodesk@groon.co.jp\">infodesk@groon.co.jp</a><br/>Visit the <a href=\"http://www.groon.co.jp\">GROON solutions web site</a> for the lastest news and update." ;
	return $ret;
}