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
 * @version		$Id: reservation.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
require_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php');

/**
 * Bsbooking Reservation Controller
 *
 * @package		Joomla
 * @subpackage	Bsbooking
 * @since 1.0
 */

class BsbookingControllerReservation extends BsbookingController
{
    /**
     * Present form for user to make a new reservation.
     */
	function add()
	{
		$app = JFactory::getApplication();
		$input = $app->input;

        $config = array();
        $config['id']               = 0;
        $config['schedule_id']      = $input->getInt('schedule_id', 0);
        $config['resource_id']      = $input->getInt('resource_id', 0);
        $config['start_date']       = $input->getInt('ts', 0); //timestamp (date)
        $config['end_date']         = $input->getInt('ts', 0);
        $config['start_time']       = $input->getInt('tstart'); //time start (minute from 00:00)
        $config['end_time']         = $input->getInt('tend'); //time end (minute from 00:00)
        
        $model   =  $this->getModel( 'reservation', 'BsbookingModel', $config );
        $reservation =  $model->getReservation();

        $user =  JFactory::getUser();
        
        if ($user->id){
            $reservation->type = RES_TYPE_ADD;    
        }else{
            /**
                @todo Need some thing to do better than this to block user from 
            */
            $reservation->type = RES_TYPE_VIEW;
        }
       
       $view    = $this->getView( 'reservation', 'html' );
       $view->setLayout( 'form' );
       
       $view->setModel( $model, true ); //set model as default model of view
       $view->display();
	}
    
    /**
     */
    function edit()
    {   
    	$app = JFactory::getApplication();
		$input = $app->input;
		
        $config = array();    
        $config['id'] = $input->getInt('id', 0);
        $type = $input->getCmd('type', RES_TYPE_VIEW);
        if (!empty($config['id']))
        {
            $model =  $this->getModel('reservation', 'BsbookingModel', $config);
            $reservation =  $model->getReservation();
            
            $user =  JFactory::getUser();
            if( $reservation->private_flg == 1 ) {
            	if( $reservation->reserved_for != $user->get('id') ) {
					$memchk = false;
/***
					$pt = "/^#[^#]*#/";
					preg_match($pt,$reservation->members,$matches) ;
					$memberstr = str_replace($matches[0],"",$reservation->members);
					$darr = explode ("/",$memberstr);
			
					for( $i=0;$i<count($darr);$i++ ) {
						$dmyid = explode(",",$darr[$i]);
						if( $dmyid[0] == $user->get('id') ) {
							$memchk = true;
							break;
						}
					}
***/
					if( !$memchk ) {
						JError::raiseError( 403, JText::_('JERROR_ALERTNOAUTHOR') );
						return;
					}
				}
            }
            $reservation->setType($type);
            /* Re-check permission if user want to modify */
            if ($type == RES_TYPE_MODIFY)
            {
//              $days_has_passed = !$reservation->checkStartDate();
                $days_has_passed = !$reservation->checkEndDate();
                $user_has_perms = false;
            
                if (($reservation->reserved_for == $user->get('id')) || ($reservation->created_by == $user->get('id')) ){
                    $user_has_perms = true;
                } 
                if (!$days_has_passed){
                    if ($user_has_perms || $reservation->adminMode){
                        $reservation->type = RES_TYPE_MODIFY;
                    }else{
                        $reservation->type = RES_TYPE_VIEW;
                    }
                }else{
                    if (!$reservation->adminMode){
                        $reservation->type = RES_TYPE_VIEW;
                    }                
                }
            }
            $view  =  $this->getView( 'reservation', 'html' );
            $view->setLayout( 'form' );
       
            $view->setModel( $model, true ); //set model as default model of view
            $view->display(); 
        }else{
            
        }     
    }
    
    /**
     * User want to save reservation to database.
     */
    function save()
    {        
    	$app = JFactory::getApplication();
		$input = $app->input;
		
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
        $config = array();
        $config['id']               = $input->getInt('id', 0);  
        $config['schedule_id']      = $input->getInt('schedule_id', 0);
        $config['resource_id']      = $input->getInt('resource_id', 0);
        $config['reserved_for']     = $input->getInt('reserved_for', JFactory::getUser()->get('id'));

//		$tmzone = date_default_timezone_get();
//		date_default_timezone_set('UTC');
        $start_date                 = $input->getString('start_date',''); // date
        list($y, $m, $d)            = explode('-', $start_date);
        $config['start_date']       = mktime(0, 0, 0, $m, $d, $y);
        $end_date                   = $input->getString('end_date', $start_date); // date
        list($y, $m, $d)            = explode('-', $end_date);
        $config['end_date']         = mktime(0, 0, 0, $m, $d, $y);
//        date_default_timezone_set($tmzone);

        $config['start_time']       = $input->getInt('start_time', 0); //time start
        $config['end_time']         = $input->getInt('end_time', 0); //time end

        $config['summary']          = stripslashes($input->getString('summary', ''));
       
        $config['frequency']        = $input->getInt('frequency', 1);
        $config['interval']         = $input->getString('interval', 'none');
        $config['week_number']      = $input->getInt('week_number', 0);
        $config['repeat_day']       = $input->get('repeat_day', array(), 'array');
        $config['repeat_until']     = $input->get('repeat_until','');
        $config['private_flg']        = $input->getInt('private_flg');

        $config['fn']               = $input->getCmd('fn', 'create');
        $config['delete']           = $input->getInt('del', 0); 
        $config['include_child']    = $input->getInt('include_child', 0);
		$config['members']    		= $input->getString('members', '');
        $model =  $this->getModel('reservation', 'BsbookingModel', $config);

        $model->processReservation( $config );
               
        $view  = $this->getView( 'reservation', 'html' );
        $view->setLayout( 'message' );
       
        $view->setModel( $model, true ); //set model as default model of view
        $view->display();
       
    }

    /**
     * User canceled the reservation
     */
    function back()
    {        
    	$app = JFactory::getApplication();
		$input = $app->input;

    	$link = $input->getString('ret');
    	if( strlen($link) == 0 ) {
    		$app = JFactory::getApplication();
			$pathway = $app->getPathway()->getPathway();
			$paths = count($pathway);
			if( $paths > 0 ){
				$paths--;
				$link = $pathway[$paths]->link;
			} else {
				$link = 'index.php';
			}
		} else {
			$link = base64_decode($link);
		}
    	$msg = null;
    	$rlink = JRoute::_($link,false);
		$this->setRedirect($link, $msg);
    }
    
    function remove()
    {
        require_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'reservation.class.php');
        
        $app = JFactory::getApplication();
		$input = $app->input;
		
        $cid = $input->get( 'cid', array(), 'array' );
        JArrayHelper::toInteger( $cid );
        
        $config = array();

        $config['schedule_id']      = $input->getInt('schedule_id', 0);
        $config['fn']               = 'delete';
        $config['delete']           = 1;
        $config['include_child']    = 0;
        
        foreach ($cid as $id)
        {
            $config['id'] = $id;
            $reservation = new BsbookingReservation($config);
            $reservation->deleteReservation($config['include_child']);     
        }
        $itemId = $input->getInt('Itemid',null);
        $append = '';
        if(isset($itemId)) $append = '&Itemid=' . $itemId;
        $return_task = $input->getString('return_task', 'reservations.getlist');
        $this->setRedirect(JRoute::_('index.php?option=com_bsbooking&task='.$return_task.'&id='.$config['schedule_id'].$append, false));
    }
    
    function view()
    {
    	$app = JFactory::getApplication();
		$input = $app->input;
		
        $config['id'] = $input->getInt('id', 0);
        $type = $input->getCmd('type', RES_TYPE_VIEW);
        
        $model =  $this->getModel('reservation', 'BsbookingModel', $config);
        $reservation =  $model->getReservation();
            
        $reservation->setType($type);
        
        $view  = $this->getView( 'reservation', 'html' );
        $view->setLayout( 'form' );
       
        $view->setModel( $model, true ); //set model as default model of view
        $view->display();       
    }
    
    function ajaxcheck()
    {
    	$app = JFactory::getApplication();
		$input = $app->input;
		
        $config = array();
        $config['id']               = $input->getInt('id', 0);
        $config['schedule_id']      = $input->getInt('schedule_id', 0);
        $config['resource_id']      = $input->getInt('resource_id', 0);
        $config['reserved_for']     = $input->getInt('reserved_for', JFactory::getUser()->get('id'));
       
        $start_date                 = $input->getString('start_date',''); // date
        $end_date                   = $input->getString('end_date', $start_date); // date
        
        list($y, $m, $d)            = explode('-', $start_date);
        $config['start_date']       = mktime(0, 0, 0, $m, $d, $y);
        
        list($y, $m, $d)            = explode('-', $end_date);
        $config['end_date']         = mktime(0, 0, 0, $m, $d, $y);
       
        $config['start_time']       = $input->getInt('start_time', 0); //time start
        $config['end_time']         = $input->getInt('end_time', 0); //time end
        $config['summary']          = stripslashes($input->getString('summary', ''));
       
        $config['frequency']        = $input->getInt('frequency', 1);
        $config['interval']         = $input->getString('interval', 'none');
        $config['week_number']      = $input->getInt('week_number', 0);
        $config['repeat_day']       = $input->get('repeat_day', array(), 'array');
        $config['repeat_until']     = $input->getString('repeat_until','');
       
        $config['fn']               = $input->getCmd('fn', 'create'); 
        $config['include_child']    = $input->getInt('include_child', 0);
              
        $model =  $this->getModel('reservation', 'BsbookingModel', $config);
        
        $errors = $model->validateReservation($config);
        
        $view  = $this->getView( 'reservation', 'raw' );
        $view->setLayout( 'ajaxresult' );
        $view->assignRef('errors', $errors);   
        $view->setModel( $model, true ); //set model as default model of view
        $view->display();
        
        jexit();
    }

    function rsvmove()
    {        
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app = JFactory::getApplication();
		$input = $app->input;

        $config = array();
        $config['id']               = $input->get('moveid');  
        $config['schedule_id']      = $input->get('schedule_id', 0);
        $config['resource_id']      = $input->get('resource_id', 0);
        $config['reserved_for']     = JFactory::getUser()->get('id');

        $config['start_date']       = $input->get('ts');
        $config['end_date']         = $input->get('ts');

        $config['start_time']       = $input->get('tstart'); //time start
        $config['end_time']         = $input->get('tend'); //time end
       
        $config['fn']               = 'rsvmove';
        $config['delete']           = 0; 
        $config['include_child']    = 0;


        $model =  $this->getModel('reservation', 'BsbookingModel', $config);

        $model->processReservation( $config );

        $reservation = $model->getReservation();
            if ( count($reservation->getErrors()) ){
///                $message = $reservation->getError();
                $success = false;    
	            $message =JText::_('YOUR_OPERATION_HAS_FAILED');
                $message .= "\n".$reservation->getError();
            }else{
//                $message = $reservation->getSuccessMessage();
                $success = true;
	            $message =JText::_('YOUR_OPERATION_HAVE_DONE_SUCCESSFULLY');
	            $message .= $reservation->getError();
	            $message .= "\n" .$reservation->getSuccessMessage();
            }


		$hash = [$message,$success];

		ob_clean();
		echo json_encode($hash);
		jexit();
       
    }

}