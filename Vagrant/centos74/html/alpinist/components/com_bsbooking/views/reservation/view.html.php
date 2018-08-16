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
 * @version		$Id: view.html.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
jimport( 'joomla.html.pane');

/**
 * HTML View class for the Bsbooking component
 *
 * @static
 * @package		Joomla
 * @subpackage	Bsbooking
 * @since 1.5
 */
class BsbookingViewReservation extends JViewLegacy
{
    function display($tpl = NULL)
    {
    	$app = JFactory::getApplication();
		$input = $app->input;
    	$ret = $input->getString('ret','');
		if( strlen($ret) == 0 ) {
			$ret = $_SERVER['HTTP_REFERER'];
			if( strlen($ret) == 0 ) {
				$app = JFactory::getApplication();
				$pathway = $app->getPathway()->getPathway();
				$paths = count($pathway);
				if( $paths > 0 ){
					$paths-- ; 
					$ret = $pathway[$paths]->link;
				} else {
					$ret = 'index.php';
				}
			}
			$ret = base64_encode($ret);
		}
		$this->assignRef('ret', $ret);
        JHTML::_('behavior.formvalidation');
        JHTML::_('behavior.calendar');
        if ($this->getLayout()=='form') {
            $document = JFactory::getDocument();
        
            $js = JURI::base(true).'/components/com_bsbooking/assets/js/reservation.js?'.mt_rand();
            $document->addScript($js);
            $js = JURI::base(true).'/components/com_bsbooking/assets/js/ajax.js?'.mt_rand();
            $document->addScript($js);
            $js = JURI::base(true).'/components/com_bsbooking/assets/js/bsbmembers.js';
			$document->addScript($js);
            
            $reservation = $this->get('Reservation');
            $ajaxUrl = JURI::root().'index.php?option=com_bsbooking&task=reservation.ajaxcheck&format=raw';
            $this->assignRef( 'reservation', $reservation );
            $this->assignRef('ajaxCheckUrl', $ajaxUrl);
        }else if ($this->getLayout()=='message'){
            $reservation = $this->get('Reservation');
            if ( count($reservation->getErrors()) ){
//                $message = $reservation->getError();
                $success = false;    
            }else{
//                $message = $reservation->getSuccessMessage();
                $success = true;
            }
            $message = $reservation->getError();
            $message .= '<br /><br />' .$reservation->getSuccessMessage();
                
            $this->assign( 'message', $message);
            $this->assign( 'success', $success);
        }
        parent::display($tpl);
    }
}