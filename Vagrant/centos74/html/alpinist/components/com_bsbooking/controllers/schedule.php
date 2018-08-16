<?php
/**
 * BsBooking component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		BsAlpinist
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2011 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: schedule.php BsAlpinist ver.2.5.0 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * Bsbooking Schedule Controller
 *
 * @package		Joomla
 * @subpackage	Bsbooking
 * @since 1.0
 */
class BsbookingControllerSchedule extends BsbookingController
{
	
	function display($cachable = false, $urlparams = Array())
	{
		$app = JFactory::getApplication();
		$input = $app->input;

		$id = $input->getInt('id', null);
		$type = $input->getInt('type', ALL);
        
        $user = JFactory::getUser();
        if (empty($user->id)){
            $type = READ_ONLY;
        }
		if (empty($id)) 
		{
            $menuitemid = $input->getInt( 'Itemid' );
            if ($menuitemid)
            {
                $link = JSite::getMenu()->getActive()->link;
                $parts = explode('&', $link);
                foreach ($parts as $str)
                {
                    list($name, $value) = explode('=', $str);
                    if ($name=='id')
                    {
                        $id = (int)$value;
                        break;
                    }
                } 
            }
		}
        if (empty($id)) {
            JError::raise(E_WARNING,'Internal error', JText::_('COM_BSBOOKING_SCHID_NOTFOUND') );
            return false;
        }
		$model = $this->getModel('schedule');
		$model->setScheduleId( $id );
		$model->setScheduleType( $type );
		
		$view = $this->getView('schedule', 'html');
		$view->setModel($model, true);
		
		$view->display();
	}
	
	/**
	* Edit a schedule and show the edit form
	*
	* @acces public
	* @since 1.5
	*/
	function edit()
	{
		$app = JFactory::getApplication();
		$input = $app->input;
		
		$user = JFactory::getUser();

		// Make sure you are logged in
		if ($user->get('aid', 0) < 1) {
			JError::raiseError( 403, JText::_('JERROR_ALERTNOAUTHOR') );
			return;
		}

		$input->set('view', 'schedule');
		$input->set('layout', 'form');

		$model = $this->getModel('schedule');
		$model->checkout();

		parent::display();
	}

	/**
	* Saves the record on an edit form submit
	*
	* @acces public
	* @since 1.5
	*/
	function save()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Get some objects from the JApplication
		$db		= JFactory::getDBO();
		$user	= JFactory::getUser();

		// Must be logged in
		if ($user->get('id') < 1) {
			JError::raiseError( 403, JText::_('JERROR_ALERTNOAUTHOR') );
			return;
		}

		//get data from the request
		$post = $input->get('jform', array(), 'array');

		$model = $this->getModel('schedule');

		if ($model->store($post)) {
			$msg = JText::_('COM_BSBOOKING_SCH_SAVED');
		} else {
			$msg = JText::_('COM_BSBOOKING_ERROR_SAVING');
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();

		$itemId = $input->getInt('Itemid',null);
		$append = '';
		if(isset($itemId)) $append = '&Itemid=' . $itemId;
		$this->setRedirect(JRoute::_('index.php?option=com_bsbooking&view=schedules&id='.$post['id'].$append, false), $msg);
	}

	/**
	* Cancel the editing of a web link
	*
	* @access	public
	* @since	1.5
	*/
	function cancel()
	{
		// Get some objects from the JApplication
		$user	=  JFactory::getUser();

		// Must be logged in
		if ($user->get('id') < 1) {
			JError::raiseError( 403, JText::_('JERROR_ALERTNOAUTHOR') );
			return;
		}

		// Checkin the schedule
		$model = $this->getModel('schedule');
		$model->checkin();

		$itemId = $input->getInt('Itemid',null);
		$append = '';
		if(isset($itemId)) $append = '&Itemid=' . $itemId;
		$this->setRedirect(JRoute::_('index.php?option=com_bsbooking&view=schedules'.$append, false));
	}
}

?>
