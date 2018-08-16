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
 * @version		$Id: holiday.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.6.0 $
 **/

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class BscoreControllerHoliday extends JControllerForm {
	/**
	 * Controler for the Control Panel
	 * @param array		configuration
	 */
	function __construct()
	{
		parent::__construct();
		$app = JFactory::getApplication();
		$input = $app->input;
		$input->set('view', $input->getCmd('view','holiday'));
		$this->registerTask( 'submod',  'subnew' );
		$this->registerTask( 'editmod',  'editnew' );
	}
	function show() {
		require_once JPATH_COMPONENT.'/helper/admin.helper.php';

		$app = JFactory::getApplication();
		$input = $app->input;
		// Load the submenu.
		BscoreAdminHelper::addSubmenu($input->getCmd('view', 'holiday'));

		$view		= $input->getCmd('view', 'holiday');
		$layout 	= $input->getCmd('layout', 'default');
		$id			= $input->getInt('id');

		// Check for edit form.
		if ($view == 'holiday' && $layout == 'edit' && !$this->checkEditId('com_bscore.edit.holiday', $id)) {
			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_bscore&amp;task=holiday.show', false));

			return false;
		}
		parent::display();
	}
	function publish() {
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$app = JFactory::getApplication();
		$input = $app->input;
		$year = $input->get('selected_y');
		$link = 'index.php?option=com_bscore&task=holiday.show&selected_y='.$year;
		$data_post = $input->getArray();
		$model = $this->getModel('holiday');
		$model->publish("1",$data_post);
		$this->setRedirect($link);
	}
	function unpublish() {
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$app = JFactory::getApplication();
		$input = $app->input;
		$year = $input->get('selected_y');
		$link = 'index.php?option=com_bscore&task=holiday.show&selected_y='.$year;
		$data_post = $input->getArray();
		$model = $this->getModel('holiday');
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
		$year = $input->get('selected_y');
		$link = 'index.php?option=com_bscore&task=holiday.show&selected_y='.$year;
		$data_post = $input->getArray();
		$model = $this->getModel('holiday');
		$model->store($data_post);
		$this->setRedirect($link);
	}
	function editcancel() {
		$link = 'index.php?option=com_bscore&task=holiday.show';
		$this->setRedirect($link);
	}
	function remove() {
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$app = JFactory::getApplication();
		$input = $app->input;
		$data_post = $input->getArray();
		$model = $this->getModel('holiday');
		$year = $model->delete($data_post);
		$link = 'index.php?option=com_bscore&task=holiday.show&selected_y='.$year;
		$this->setRedirect($link);
	}
	function newyear() {
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$app = JFactory::getApplication();
		$input = $app->input;
		$data_post = $input->getArray();
		$model = $this->getModel('holiday');
		$year = $model->newyear($data_post);
		$link = 'index.php?option=com_bscore&task=holiday.show&selected_y='.$year;
		$this->setRedirect($link);
	}
	function cancel($key = NULL) {
		$this->setRedirect( 'index.php');
	}
}
