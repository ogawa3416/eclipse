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

jimport('joomla.application.component.model');
require_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'reservation.class.php');

class BsbookingModelReservation extends JModelLegacy 
{
    var $_id = null;
    
    var $_reservation = null;
    
    function __construct($config=array())
    {
        parent::__construct( $config );
        $this->_reservation =  BsbookingReservation::getInstance( $config );
        $this->_id = $this->_reservation->id;
    }
    
    function  getReservation()
    {
		$_db = JFactory::getDBO();
		// Resource Control-division 
		if( $this->_reservation->resource->divcode ) {
			$query = null;
			$_divname = null;
			$query = ' SELECT divname'
				.' FROM #__bs_division a '
				.' WHERE a.divcode = '.$_db->Quote($this->_reservation->resource->divcode)
				.' AND a.div_stat = 1 ' 
			;
			$_db->setQuery( $query );
			$_divname = $_db->loadObject();
			if( $_divname ) $this->_reservation->resource->divname = $_divname->divname;
			else $this->_reservation->resource->divname = '';
		} else {
			$this->_reservation->resource->divname = '';
		}
		// Reserved for
		if( $this->_reservation->reserved_for ) {
			$query = null;
			$_divname = null;
			$query = ' SELECT divname'
				.' FROM #__bs_division a, #__bs_users_detail b '
				.' WHERE b.userid = '.$this->_reservation->reserved_for
				.' AND b.divcode = a.divcode AND a.div_stat = 1 ' 
			;
			$_db->setQuery( $query );
			$_divname = $_db->loadObject();
			if( $_divname ) $this->_reservation->divname = $_divname->divname;
			else $this->_reservation->divname = '';
		} else {
			$this->_reservation->divname = '';
		}
        return $this->_reservation;
    }
    
    function processReservation($data)
    {
        if (empty($this->_reservation)) $this->_reservation =  BsbookingReservation::getInstance($data);
        $reservation =  $this->_reservation;
   
        if ( !$data['id'] )
        {
            if ($data['interval']!= 'none')
            {
                if ($data['start_date']==$data['end_date'])
                {
                    $reservation->is_repeat = true;
                    $repeat = BsbookingHelper::get_repeat_dates($data['start_date'], $data['interval'],
                        $data['repeat_day'], $data['repeat_until'], $data['frequency'], $data['week_number'] );
                
                }else{
                    $repeat = array($data['start_date']);
                    $reservation->is_repeat = false;
                }
            }else{
                /* Not repeat type */
                $repeat = array($data['start_date']);
                $reservation->is_repeat = false;
            }
            $reservation->repeat = $repeat;
        }
        
        if ($data['fn']=='create'){
            if( $reservation->addReservation() ) {
/** blogstone link to bsscheduler **/
				if( file_exists(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'bsclink.php') ) {
					require_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'bsclink.php');
					$setd = new BsbookinkLinkHelper(); 
					$setd->linktobsscheduler($data);
				}
			}
        }elseif ($data['fn']=='modify') {
            /* This are properties that we allow user to change */
            $reservation->start_date = $data['start_date'];
            $reservation->end_date = $data['end_date'];   
            $reservation->start_time = $data['start_time'];
            $reservation->end_time = $data['end_time'];     
            $reservation->summary = $data['summary'];   //we do clean up in controller
			$reservation->private_flg = $data['private_flg'];

            if ($data['reserved_for'] != $reservation->reserved_for) 
            {
                $reservation->reserved_for =  $data['reserved_for'];
            }
            $reservation->modified = date("Y-m-d H:i:s");
/** blogstone add member **/
			$reservation->members = $data['members'];
            $reservation->modifyReservation($data['delete'], $data['include_child']); 
        }elseif ($data['fn']=='delete') {
            $reservation->deleteReservation($data['include_child']);    
        }elseif ($data['fn']=='approve'){
            
        }
        elseif($data['fn']=='rsvmove'){
            /* This are properties that we allow user to change */
            $reservation->start_date = $data['start_date'];
            $reservation->end_date = $data['end_date'];   
            $reservation->start_time = $data['start_time'];
            $reservation->end_time = $data['end_time'];     
            $reservation->resource_id = $data['resource_id'];
            $reservation->schedule_id = $data['schedule_id'];
            $reservation->parent_id = 0;

            if ($data['reserved_for'] != $reservation->reserved_for) 
            {
                $reservation->reserved_for =  $data['reserved_for'];
            }
            $reservation->modified = JFactory::getDate()->toSql();
            $reservation->modified_by = JFactory::getUser()->get('id');

            $reservation->modifyReservation('', ''); 
		}

    }
    
    function validateReservation($data)
    {
        if (empty($this->_reservation)) $this->_reservation =  BsbookingReservation::getInstance($data);
        $reservation =  $this->_reservation;

        if ( empty($data['id']) )
        {
            if ( isset($data['interval']) && ($data['interval'] != 'none') )
            {
                if ($data['start_date']==$data['end_date'])
                {
                    $reservation->is_repeat = true;
                    $repeat_dates = BsbookingHelper::get_repeat_dates($data['start_date'], $data['interval'],
                        $data['repeat_day'], $data['repeat_until'], $data['frequency'], $data['week_number'] );
                
                }else{
                    $repeat_dates = array($data['start_date']);
                    $reservation->is_repeat = false;
                }
            }else{
                /* Not repeat type */
                $repeat_dates = array($data['start_date']);
                $reservation->is_repeat = false;
            }  
            $reservation->repeat = $repeat_dates;
        }
        
        $errors = array();
        if ( $reservation->checkStartDate() )
        {
            if ( $reservation->checkTimes() )
            {
                if ( $reservation->checkMinMax() )
                {
                    for ($i=0; $i < count($repeat_dates); $i++ )
                    {
                        $reservation->start_date = $repeat_dates[$i];
                        if ($reservation->is_repeat) 
                        {
                            $reservation->end_date = $reservation->start_date;
                        }
                        if (!$reservation->checkReservation())
                        {
                            $errors[] = $reservation->getError();
                        }    
                      
                    }
                }else{
                    $errors[] = Jtext::_('COM_BSBOOKING_ERROR_NOT_IN_RESOURCE_LENGTH');
                }
            }else{
                $errors[] = JText::_('COM_BSBOOKING_ERROR_START_TIME_MUST_LESS_THAN_END_TIME');
            }
        }else{
            $errors[] = $reservation->getError();
        }
        
        return $errors;   
    }
    
    function getConflictReservation($oReservation=null)
    {
        if (empty($oReservation)) $oReservation = $this->_reservation;
        
        $dbo =  $this->getDBO();
        $sql = "SELECT res.*, u.name as for_fullname " 
            ." \nFROM #__bs_reservations res "
            ." \nLEFT JOIN #__users u ON u.id = res.reserved_for"
            ." \nWHERE resource_id = {$oReservation->resource_id} AND start_date = {$oReservation->start_date} AND res.id <> {$oReservation->id} "
                ."AND ( "
                    ." (start_time < {$oReservation->start_time} AND end_time > {$oReservation->start_time} AND end_time <= {$oReservation->end_time}) "
                    // (2)
                    ." OR (start_time >= {$oReservation->start_time} AND start_time < {$oReservation->end_time} AND end_time > {$oReservation->end_time}) "
                    // (3)
                    ." OR (start_time <= {$oReservation->start_time} AND end_time >= {$oReservation->end_time}) "
                    // (4)
                    ." OR (start_time >= {$oReservation->start_time} AND end_time <= {$oReservation->end_time}) "
                    .")";
        $dbo->setQuery($sql);
        $rows = $dbo->loadObjectList();
        
        return $rows;   
    }
}