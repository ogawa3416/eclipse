<?php
/**
 * BSScheduler component for Joomla.
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
 * @version		$Id: divlist.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.3 $
 **/

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');
require_once (JPATH_COMPONENT.DIRECTORY_SEPARATOR.'helper'.DIRECTORY_SEPARATOR.'bscore.helper.php');

class BscoreControllerDivlist extends JControllerForm {
	/**
	 * Controler for the Control Panel
	 * @param array		configuration
	 */
	function __construct()
	{
		parent::__construct();
		$app = JFactory::getApplication();
		$input = $app->input;
		$input->set('view', $input->getCmd('view','divlist'));
		$this->registerTask( 'submod',  'subnew' );
		$this->registerTask( 'editmod',  'editnew' );
	}
	function show() {
		require_once JPATH_COMPONENT.'/helper/admin.helper.php';

		$app = JFactory::getApplication();
		$input = $app->input;
		// Load the submenu.
		BscoreAdminHelper::addSubmenu($input->getCmd('view', 'divlist'));

		$view		= $input->getCmd('view', 'divlist');
		$layout 	= $input->getCmd('layout', 'default');
		$id			= $input->getInt('id');

		// Check for edit form.
		if ($view == 'divlist' && $layout == 'edit' && !$this->checkEditId('com_bscore.edit.divlist', $id)) {
			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_bscore&amp;task=divlist.show', false));

			return false;
		}
		parent::display();
	}
	function publish() {
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$app = JFactory::getApplication();
		$input = $app->input;
		$link = 'index.php?option=com_bscore&task=divlist.show';
		$data_post = $input->getArray();
		$model = $this->getModel('divlist');
		$model->publish("1",$data_post);
		$this->setRedirect($link);
	}
	function unpublish() {
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$app = JFactory::getApplication();
		$input = $app->input;
		$link = 'index.php?option=com_bscore&task=divlist.show';
		$data_post = $input->getArray();
		$model = $this->getModel('divlist');
		$model->publish("0",$data_post);
		$this->setRedirect($link);
	}
	function subnew() {
		$app = JFactory::getApplication();
		$input = $app->input;
		$input->set('layout','edit') ;
		parent::display();
	}
	function editnew() {
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$app = JFactory::getApplication();
		$input = $app->input;
		$link = 'index.php?option=com_bscore&task=divlist.show';
		$data_post = $input->getArray();
		$model = $this->getModel('divlist');
		$model->store($data_post);
		$this->setRedirect($link);
	}
	function editcancel() {
		$link = 'index.php?option=com_bscore&task=divlist.show';
		$this->setRedirect($link);
	}
	function remove() {
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$app = JFactory::getApplication();
		$input = $app->input;
		$link = 'index.php?option=com_bscore&task=divlist.show';
		$data_post = $input->getArray();
		$model = $this->getModel('divlist');
		$model->delete($data_post);
		$this->setRedirect($link);
	}
	function cancel($key = NULL) {
		$this->setRedirect( 'index.php');
	}
}
