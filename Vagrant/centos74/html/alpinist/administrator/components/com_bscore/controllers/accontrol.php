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
 * @version		$Id: accontrol.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
 **/

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');
require_once (JPATH_COMPONENT.DIRECTORY_SEPARATOR.'helper'.DIRECTORY_SEPARATOR.'bscore.helper.php');

class BscoreControllerAccontrol extends JControllerForm {
	/**
	 * Controler for the Control Panel
	 * @param array		configuration
	 */
	function __construct()
	{
		parent::__construct();
		$app = JFactory::getApplication();
		$input = $app->input;
		$input->set('view', $input->getCmd('view','accontrol'));
		$this->registerTask( 'useradd',  'usernew' );
	}
	function show() {
		require_once JPATH_COMPONENT.'/helper/admin.helper.php';

		$app = JFactory::getApplication();
		$input = $app->input;
		
		// Load the submenu.
		BscoreAdminHelper::addSubmenu($input->getCmd('view', 'accontrol'));

		$view		= $input->getCmd('view', 'accontrol');
		$layout 	= $input->getCmd('layout', 'default');
		$id			= $input->getInt('id');

		// Check for edit form.
		if ($view == 'accontrol' && ($layout == 'uedit' || $layout == 'gredit' || $layout == 'dedit')
				&& !$this->checkEditId('com_bscore.uedit.accontrol', $id)) {
			$com_group = $input->get('com_group');
			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_bscore&amp;task=accontrol.show&amp;com_group='.$com_group, false));

			return false;
		}
		parent::display();
	}
	function grmod() {
		$app = JFactory::getApplication();
		$input = $app->input;
		$input->set('layout','gredit') ;
		parent::display();
	}
	function grnew() {
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$app = JFactory::getApplication();
		$input = $app->input;
		$data_post = $input->getArray();
		$model = $this->getModel('accontrol');
		$model->grstore($data_post);
		$link = 'index.php?option=com_bscore&task=accontrol.show&com_group='.$model->getGroup();
		$this->setRedirect($link);
	}
	function remove() {
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$app = JFactory::getApplication();
		$input = $app->input;
		$data_post = $input->getArray();
		$model = $this->getModel('accontrol');
		$model->delete($data_post);
		$link = 'index.php?option=com_bscore&task=accontrol.show&com_group='.$model->getGroup();
		$this->setRedirect($link);
	}
	function cancel($key = NULL) {
		$this->setRedirect( 'index.php');
	}
	function divmod() {
		$app = JFactory::getApplication();
		$input = $app->input;
		$input->set('layout','dedit') ;
		parent::display();
	}
	function usermod() {
		$app = JFactory::getApplication();
		$input = $app->input;
		$input->set('layout','uedit') ;
		parent::display();
	}
	function divnew() {
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$app = JFactory::getApplication();
		$input = $app->input;
		$data_post = $input->getArray();
		$model = $this->getModel('accontrol');
		$model->divstore($data_post);
		$link = 'index.php?option=com_bscore&task=accontrol.show&com_group='.$model->getGroup();
		$this->setRedirect($link);
	}
	function usernew() {
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$app = JFactory::getApplication();
		$input = $app->input;
		$data_post = $input->getArray();
		$model = $this->getModel('accontrol');
		$model->userstore($data_post);
		$link = 'index.php?option=com_bscore&task=accontrol.show&com_group='.$model->getGroup();
		$this->setRedirect($link);
	}
	function editcancel() {
		$app = JFactory::getApplication();
		$input = $app->input;
		$com_group = $input->get('com_group');
		$link = 'index.php?option=com_bscore&task=accontrol.show&com_group='.$com_group;
		$this->setRedirect($link);
	}
}
