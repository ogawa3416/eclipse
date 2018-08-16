<?php
/**
 * @version		$Id: Bsuserdetail.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.6.0 $
 * @package		Joomla Plugin for BlogStone
 * @subpackage	JFramework
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

defined('JPATH_BASE') or die;

jimport('joomla.plugin.plugin');
require_once( JPATH_ROOT.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_bscore'.DIRECTORY_SEPARATOR.'helper'.DIRECTORY_SEPARATOR.'updateuserhelper.php' );

/**
 * Example User Plugin
 *
 * @package		Joomla
 * @subpackage	JFramework
 * @since 		2.5
 */
class plgUserBsuserdetail extends JPlugin {
	/**
	 * input user id
	 * @var int
	 */
	var $_userid = null;
	/**
	 * Project master insert/Update status
	 * @var int
	 */
	var $_isInsert = null;
	var $_isUpdate = null;
	
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param object $subject The object to observe
	 * @param 	array  $config  An array that holds the plugin configuration
	 * @since 1.5
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}

	/**
	 * Example store user method
	 *
	 * Method is called after user data is stored in the database
	 *
	 * @param 	array		holds the new user data
	 * @param 	boolean		true if a new user is stored
	 * @param	boolean		true if user was succesfully stored in the database
	 * @param	string		message
	 */
	function onUserAfterSave($user, $isnew, $result, $error)
	{
		// Call a function in the external app to update the user
		// ThirdPartyApp::updateUser($user['id'], $args);
		BsupdatedetailHelper::updateuser($user);
		return true;
	}

	/**
	 * Example store user method
	 *
	 * Method is called before user data is deleted from the database
	 *
	 * @param 	array		holds the user data
	 */
	function onUserAfterDelete($user, $succes, $msg)
	{
	}

	/**
	 * This method should handle any login logic and report back to the subject
	 *
	 * @access	public
	 * @param 	array 	holds the user data
	 * @param 	array    extra options
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function onUserLogin($user, $options = array())
	{
		// Initialize variables
	}

	/**
	 * This method should handle any logout logic and report back to the subject
	 *
	 * @access public
	 * @param array holds the user data
	 * @return boolean True on success
	 * @since 1.5
	 */
	function onUserLogout($user, $options = array())
	{
		// Initialize variables
	}
}
