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

class BsbookingControllerSchedule extends JControllerLegacy
{
	function __construct( $config = array() )
	{
		parent::__construct( $config );
		$this->registerTask( 'apply', 'save' );
	}
	
	function add()
	{
	   $id = 0;
	   $model =  $this->getModel('schedule', 'BsbookingModel');
	   $model->setId($id);
       
		$app = JFactory::getApplication();
		$input = $app->input;
		
		$input->set('hidemainmenu', 1);
       
  		$view = $this->getView( 'schedule','html' );
		$view->setLayout( 'form' );
		
		$view->setModel($model, true);
		$view->display();   	
	}
	
	function edit()
	{
		$app = JFactory::getApplication();
		$input = $app->input;
		
		$cid = $input->get('cid', array(),'array');
		$id = $cid [0];
		$my =  JFactory::getUser();
		
		$model =  $this->getModel('schedule', 'BsbookingModel');
		$model->setId($id);
		
		$input->set('hidemainmenu', 1);
		
		if (!$model->checkout( $my->id, $id ))
		{
			$url = "index.php?option=com_bsbooking&task=schedules.display";
			$this->setRedirect($url, 'error');
		}
		$view = $this->getView( 'schedule','html' );
		$view->setLayout( 'form' );
		
		$view->setModel($model, true);
		$view->display();
	}
	
	function save()
	{
		$app = JFactory::getApplication();
		$input = $app->input;
        $cid = $input->get( 'cid', array(), 'array' );
		$id = $cid [0];

        $model =  $this->getModel( 'schedule', 'BsbookingModel' );
		$model->setId( $id );
        
        if (!empty($id)) $input->set('id', $id);
        
        $start_hour = $input->getInt( 'start_hour', 8 );
        $start_minute = $input->getInt( 'start_minute', 0 );
        
        $input->set('day_start', $start_hour*60 + $start_minute);
        
        $end_hour = $input->getInt( 'end_hour', 17 );
        $end_minute = $input->getInt( 'end_minute', 0 );
        $input->set( 'day_end', $end_hour*60 + $end_minute );
        
        $data = $input->getArray();
        $my =  JFactory::getUser();
        if ($model->store($data))
        {
            if ($this->getTask()=='save') 
            {
                $model->checkin( $model->getId() );
                $url = 'index.php?option=com_bsbooking&task=schedules.display';
                $this->setRedirect( $url, JText::_('COM_BSBOOKING_SCH_SUCCESSSAVED'));
            }else{
                if (!empty($id))
                {
                    $model->checkout($my->id);
                }
                $url = 'index.php?option=com_bsbooking&task=schedule.edit&cid[]='.$model->getId();
                $this->setRedirect( $url, JText::_('COM_BSBOOKING_SCH_SUCCESSSAVED'));
            }
        }else{
            $message = $model->getError();
            $url = 'index.php?option=com_bsbooking&task=schedule.edit&cid[]='.$id;
            $this->setRedirect($url, $message);
        }
	}
	
    function remove()
    {
    	$app = JFactory::getApplication();
		$input = $app->input;
        $cid = $input->get('cid', array(), 'array' );   
        $model =  $this->getModel( 'schedule', 'BsbookingModel' );
        if ($model->delete( $cid )) 
        {
            $message = JText::_('COM_BSBOOKING_SCHSTORE_DELMSG');
        }else{
            $message = $model->getError();
        }
        $url = 'index.php?option=com_bsbooking&task=schedules.display';
        $this->setRedirect( $url, $message );
    }
    
	function cancel()
	{
		$app = JFactory::getApplication();
		$input = $app->input;
        $cid = $input->get('cid', array(),  'array' );
        if (!empty($cid[0]))
        {
            $model =  $this->getModel( 'schedule', 'BsbookingModel' );
            if ( !$model->checkin( $cid[0] ) )
            {
                $message = JText::_('COM_BSBOOKING_CANNOT_CHECKIN');
            }
                
        }  
		$url = 'index.php?option=com_bsbooking&task=schedules.display';
		$this->setRedirect($url, $message); 
	}	
}