<?php
/**
 * BsCore component for Joomla.
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
 * @version		$Id: userlist.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
 **/

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');
require_once (JPATH_COMPONENT.DIRECTORY_SEPARATOR.'helper'.DIRECTORY_SEPARATOR.'bscore.helper.php');

class BscoreControllerUserlist extends JControllerLegacy {
	/**
	 * Controler for the Control Panel
	 * @param array		configuration
	 */
	function __construct()
	{
		parent::__construct();
		$app = JFactory::getApplication();
		$input = $app->input;
		$input->set('view', $input->getCmd('view','userlist'));
		$this->registerTask( 'save',  'save' );
		$this->registerTask( 'apply', 'save' );
	}
	function show() {
		require_once JPATH_COMPONENT.'/helper/admin.helper.php';

		$app = JFactory::getApplication();
		$input = $app->input;
		
		// Load the submenu.
		BscoreAdminHelper::addSubmenu($input->getCmd('view', 'userlist'));

		$view		= $input->getCmd('view', 'userlist');
		$layout 	= $input->getCmd('layout', 'default');
		$id			= $input->getInt('id');

		parent::display();
	}
	/**
	* Saves the Session Record
	*/
	function save() {
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$app = JFactory::getApplication();
		$input = $app->input;
		
		if( $input->get('task') == 'save' ) 
			$link = 'index.php';
		else {
			$link = 'index.php?option=com_bscore&task=userlist.show';
		}
			
		$model = $this->getModel();
		if( !$model ) 
			JError::raiseError(500, 'Not found Model' );
		$data_post = $input->getArray();
		
		$model->store($data_post);
		$this->setRedirect($link);
	}

	function cancel() {
		$this->setRedirect( 'index.php');
	}
	function allupdate() {
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$app = JFactory::getApplication();
		$input = $app->input;
		$model = $this->getModel('userlist');
		$resulcnt = $model->allupdate();
		$msg = JText::sprintf("BSC_ALLUSERUPDATE_COUNT",$resulcnt);
		$link = 'index.php?option=com_bscore&task=userlist.show';
		$this->setRedirect($link,$msg);
	}
	function allupdateAP() {
		$model = $this->getModel('userlist');
		$resulcnt = $model->allupdate();
		if( !$resulcnt ) exit("failed");
		exit("success");
	}
}
