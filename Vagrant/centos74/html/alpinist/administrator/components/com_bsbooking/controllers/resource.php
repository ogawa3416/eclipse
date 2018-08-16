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
 * @version		$Id: resource.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class BsbookingControllerResource extends JControllerLegacy
{
    function __construct( $config = array())
    {
  		parent::__construct( $config );

		// Register Extra tasks
		$this->registerTask( 'apply', 'save' );
		$this->registerTask( 'unpublish', 'publish' );
		$this->registerTask( 'orderup', 'reorder' );
		$this->registerTask( 'orderdown', 'reorder' );        
    }
    
    function display($cachable = false, $urlparams = Array())
    {
  		$model = $this->getModel('resource','BsbookingModel');
		$view = $this->getView('resource', 'html');
		$view->setModel($model, true);
		$view->display('list');    
    }
    
    function edit()
    {
    	$app = JFactory::getApplication();
		$input = $app->input;
		
        $cid = $input->get('cid', array(), 'array' );
        if (is_array($cid)) $id = intval($cid[0]);
        
  		$model = $this->getModel('resource','BsbookingModel');
        if (!empty($id)) 
        {
            $model->setId($id);    
        }
		$view = $this->getView('resource', 'html');
        $view->setLayout('form');
        
		$view->setModel($model, true);
		
		$view->display('edit'); 
    }
    
    function add()
    {
        $model = $this->getModel('resource','BsbookingModel');
         
  		$view = $this->getView('resource', 'html');
        $view->setLayout('form');
        
		$view->setModel($model, true);
		
		$view->display('edit');  
    }
    
    
    function save()
    {
        $model =  $this->getModel('resource','BsbookingModel');
        
        $app = JFactory::getApplication();
		$input = $app->input;
		
        $data = $input->getArray();
        $cid = $input->get('cid', array(),'array');
        
        JArrayHelper::ToInteger( $cid );
        $id = $cid[0];
        $model->setId( $id );
        if ($model->store( $data ))
        {
            if ($this->getTask() == 'save')
            {
				$url = "index.php?option=com_bsbooking&task=resource.display";
			}else{
                $url = "index.php?option=com_bsbooking&task=resource.edit&cid[]=".$model->getId();

            }
            $message = JText::_('COM_BSBOOKING_DATA_SUCCESSSAVED');
        }else{
            //Error store data
            $messgae = $model->getError();
            $url = "index.php?option=com_bsbooking&task=resource.display";
        }
        $this->setRedirect( $url, $message );
    }
    
    function remove()
    {
    	$app = JFactory::getApplication();
		$input = $app->input;
		
        $cid = $input->get( 'cid', array(),'array' );
        JArrayHelper::toInteger( $cid );
        $model = $this->getModel('resource','BsbookingModel');
        $model->remove($cid);
        
        $this->setRedirect( 'index.php?option=com_bsbooking&task=resource.display' );
    }
    
    function publish()
    {
    	$app = JFactory::getApplication();
		$input = $app->input;
		
  		$cid = $input->get( 'cid', array(),'array' );
		JArrayHelper::toInteger( $cid );
        
        $task = $this->getTask();
        
        $model = $this->getModel('resource','BsbookingModel');
        $model->publish( $cid, $task=='publish' );
        $this->setRedirect( 'index.php?option=com_bsbooking&task=resource.display' );
    }
   	
    /**
	 * Moves the order of a record
	 */
	function reorder()
	{
		$app = JFactory::getApplication();
		$input = $app->input;
		
		// Check for request forgeries
		//JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialize some variables
		$db		= JFactory::getDBO();

		$cid 	= $input->get( 'cid', array(), 'array' );
		JArrayHelper::toInteger($cid);

		$task	= $this->getTask();
		$inc	= ($task == 'orderup' ? -1 : 1);

		if (empty( $cid )) {
			return JError::raiseWarning( 500, 'No items selected' );
		}
        
        JTable::addIncludePath( JPATH_COMPONENT.DIRECTORY_SEPARATOR.'tables' );
		$row = JTable::getInstance( 'Resource','Table' );
		$row->load( (int) $cid[0] );

		$row->move( $inc, 'schedule_id = '.$db->Quote( $row->schedule_id )  );
		$this->setRedirect( 'index.php?option=com_bsbooking&task=resource.display' );
	}
    
    function saveordering()
    {
    	$app = JFactory::getApplication();
		$input = $app->input;
		
  		// Check for request forgeries
		//JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialize some variables
		$db		= JFactory::getDBO();

		$cid 	= $input->get( 'cid', array(),'array' );
		JArrayHelper::toInteger($cid);

		if (empty( $cid )) {
			return JError::raiseWarning( 500, 'No items selected' );
		}

		$total		= count( $cid );
        JTable::addIncludePath( JPATH_COMPONENT.DIRECTORY_SEPARATOR.'tables' );
		$row 		=  JTable::getInstance( 'Resource', 'Table' );
        if (!is_a($row, 'JTable'))
        {
            $this->setMessage($row->getErrorMsg());
            $this->setRedirect( 'index.php?option=com_bsbooking&task=resource.display' );
            //echo "<pre>"; print_r($this); echo "</pre>";
            
            return false;
        }
		$groupings  = array();

		$order 		= $input->get( 'order', array(0), 'array' );
		JArrayHelper::toInteger($order);

		// update ordering values
		for ($i = 0; $i < $total; $i++)
		{
			$row->load( (int) $cid[$i] );
			// track postions
			$groupings[] = $row->schedule_id;

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store()) {
					return JError::raiseWarning( 500, $db->getErrorMsg() );
				}
			}
		}

		// execute updateOrder for each parent group
		$groupings = array_unique( $groupings );
		foreach ($groupings as $group){
			$row->reorder('schedule_id = '.$db->Quote($group));
		}

		$this->setMessage (JText::_( 'COM_BSBOOKING_NEW_ORDERING_SAVED' ));
        $this->setRedirect( 'index.php?option=com_bsbooking&task=resource.display' );
    }
    /**
     * Cancel from edit from to resource listing display
     */
    function cancel()
    {
        $url = 'index.php?option=com_bsbooking&task=resource.display';
        $this->setRedirect( $url );
    }
    

}