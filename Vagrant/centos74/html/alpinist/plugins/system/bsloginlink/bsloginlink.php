<?php
/**
 * BsAlpinist plugin for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		BsAlpinist 
 * @subpackage	Plugins
 * @copyright	Copyright (C) 2009-2011 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: bsloginlink.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.6.0 $
 **/

// no direct access
defined('JPATH_BASE') or die;

jimport('joomla.plugin.plugin');
jimport('joomla.html.parameter');

require_once( JPATH_ROOT.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_bscore'.DIRECTORY_SEPARATOR.'helper'.DIRECTORY_SEPARATOR.'bscore.helper.php' );
require_once( JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_bscore'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php' );

class plgSystemBsloginlink extends JPlugin
{

	var $_db = null;
	
	function __construct(& $subject, $config)
	{
		parent :: __construct($subject, $config);
		$this->_db = JFactory::getDbo();
	}

	function onAfterDispatch()
	{
		$app = JFactory::getApplication();
		$input = $app->input;
		
		if( $app->isAdmin() ) return; 

		$uri = clone JFactory::getURI();
		$router = $app->getRouter();
		$vars = $router->parse($uri);
		foreach ($vars as $key => $value) {
			if( $key != 'option' && $key != 'view' ) unset($vars[$key]);
		}
		$url = 'index.php?'.JURI::buildQuery($vars);
		$user = JFactory::getUser();
		if( $user->id > 0 ) {
			$loginurl = 'index.php?option=com_users&view=login';
			if( !strncasecmp($loginurl,$url,strlen($loginurl)) ) {
				$app->redirect('index.php');
			}
			return;
		}
		$data = $app->getUserState('users.login.form.data');
		// get login/logout URI
		$inout = BscoreHelper::getLoginout();
		$ret = $input->get('return','','base64');
		if( $ret ) {
			$ret = base64_decode($ret);
		} else { 
			$ret = $inout['login'];
			if( !$ret ) {
				$ret = "index.php";
			}
		}
		$data['return'] = $ret;
		$app->setUserState('users.login.form.data', $data);
		if( !$this->params->get('login_required',0) ) { 
			if( $router->getMode() == JROUTER_MODE_SEF ) {
				if( $uri->toString(array('path')) ) {
					return;
				}
			} else {
				if( strstr($uri->toString(),'option') ) {
					return;
				}
			}
		}
		if( $input->getCmd('option') != "com_users" || $input->get('view') != "login" ) {
			$itm = '';
			if( isset($inout[loginItemid]) ) $itm = "&Itemid=".$inout['loginItemid'];
			$link = 'index.php?option=com_users&view=login'.$itm;
			$link = JRoute :: _($link,false);
			$app->redirect($link);
		}
	}
}