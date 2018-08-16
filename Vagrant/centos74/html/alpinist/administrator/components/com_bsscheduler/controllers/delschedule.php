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

jimport('joomla.application.component.controller');

//class BsschedulerControllerDelschedule extends JControllerLegacy {
class BsschedulerControllerDelschedule extends JControllerForm {
	/**
	 * Controler for the Control Panel
	 * @param array		configuration
	 */
	function __construct()
	{
		parent::__construct();
		$app = JFactory::getApplication();
		$input = $app->input;
		$input->set('view', $input->getCmd('view','Delschedule'));
		$this->registerTask( 'save',  'save' );
		$this->registerTask( 'apply', 'save' );
	}
	function show() 
	{
		$model = $this->getModel('delschedule');
		
		$view = $this->getView('delschedule', 'html');
		$view->setModel($model, true);
		
		$view->display();

	}
	function cancel($key = NULL) 
    {
        $url = 'index.php?option=com_bsscheduler';
        $this->setRedirect($url);
    }

	function delete() {
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$ids = $this->input->get('cid', array(), 'array');

		if (!JFactory::getUser()->authorise('core.admin', $this->option))
		{
			JError::raiseError(500, JText::_('JERROR_ALERTNOAUTHOR'));
			jexit();
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			JArrayHelper::toInteger($ids);

			// Remove the items.
			if (!$model->delete($ids))
			{
				JError::raiseWarning(500, $model->getError());
			}
			else
			{
				$this->setMessage(JText::plural('COM_BSSCHEDULER_SCHEDULE_DELETED', "ID:".implode(",",$ids)));
			}
		}

		$this->setRedirect('index.php?option=com_bsscheduler&task=delschedule.show');
	}

}
