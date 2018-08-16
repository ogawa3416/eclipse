<?php
/**
 * BsBooking component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		BsBooking
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2011 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: bsclink.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.3 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;

// Component Helper
jimport('joomla.application.component.helper');
jimport('joomla.utilities.date');
require_once( JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_bscore'.DIRECTORY_SEPARATOR.'configuration.php' );

/**
 * This is interface helper for 'com_bsscheduler'
 *
 * @package		BsBooking
 * @subpackage	Components
 * @since		joomla 1.5
 */
class BsbookinkLinkHelper
{
	function linktobsscheduler($orgdata) {
		global	$comcfg;
		$pt = "/^#[^#]*#/";
	   	preg_match($pt,$orgdata['members'],$matches) ;
	   	if( count($matches) == 0 ) $matches[0] = '';
		if( strlen($matches[0]) == 0 || $matches[0] == '#MDFY#' || $matches[0] == '#MEMB#'  ) return;
		$linkdata = array();

/**
		$config =& JFactory::getConfig();
		$tzoffset = $config->get('offset');
		$dt = new JDate($orgdata['start_date'] + $orgdata['start_time']*60,$tzoffset);
		$linkdata['start_date'] = $dt->format('Y-m-d H:i:s',true);
		$dt = new JDate($orgdata['end_date'] + $orgdata['end_time']*60,$tzoffset);
		$linkdata['end_date'] = $dt->format('Y-m-d H:i:s',true);
**/
		$linkdata['start_date'] = DateUtil::formatDate($orgdata['start_date'],'%Y-%m-%d').' '.DateUtil::formatTime($orgdata['start_time'],false);
		$linkdata['end_date'] = DateUtil::formatDate($orgdata['end_date'],'%Y-%m-%d').' '.DateUtil::formatTime($orgdata['end_time'],false);
		
		$linkdata['text'] =  substr($orgdata['summary'],0,255);
		$_db = JFactory::getDBO();
		$query = "SELECT title FROM #__bs_resources WHERE id =".$_db->Quote($orgdata['resource_id']);
		$_db->setQuery( $query );
		$resname = $_db->loadResult();
		if ( $_db->getErrorNum() ) {
			JError::raiseWarning( 0, $_db->getErrorMsg() );
		}
		$linkdata['text'] .= "\n".sprintf(JText::_("COM_BSBOOKING_MAIL_FORM"),$resname);
		$linkdata['rec_type'] = $this->getrectype($orgdata,$linkdata['end_date']);
		$linkdata['event_pid'] = '0';
		if( $linkdata['rec_type'] ) {
			$linkdata['event_length'] = ($orgdata['end_time'] - $orgdata['start_time'])*60;
		} else {
			$linkdata['event_length'] = '0';
		}
		$linkdata['user'] = $orgdata['reserved_for'];
		$optitem = explode(',',$comcfg['bsbtobscoptions']);
		$optdata = explode(',',$comcfg['bsbtobscoptdata']);
		for($i=0;$i<count($optitem);$i++){
			$linkdata[$optitem[$i]] = $optdata[$i];
		}
		$user = JFactory::getUser();
		$linkdata['createdby'] = $user->id;
		$linkdata['createdbyname'] = $user->name;
		$linkdata['member'] = $orgdata['members'];
//		$linkdata['event_mid'] = '0';
// Add V3
		$linkdata['private_flg'] = $orgdata['private_flg'];
		if( $this->bssinsert($linkdata) ) {
			$this->mailtomember($linkdata);
		}
	}
	function getrectype($orgdata,&$enddate) {
		$rectyp = '';
		if( substr($orgdata['interval'],0,5) == 'month' ) {
			$rectyp = 'month'.'_';
			$spn = intval($orgdata['frequency']);
			if( $orgdata['frequency'] > 0 ) {
				$rectyp .= $spn.'_';
			} else {
				$rectyp .= '1'.'_';
			}
			if( $orgdata['interval'] == 'month_date') {
				$rectyp .= '__#';
			} else { // month_day
				$rectyp .= implode(',',$orgdata['repeat_day']);
				$rectyp .= '_';
				$rectyp .= $orgdata['week_number'];
				$rectyp .= '_#';
			}
			$enddate = $orgdata['repeat_until'] . ' 00:00:00';
		} else if(substr($orgdata['interval'],0,4) == 'week' ) {
			$rectyp = 'week'.'_';
			$spn = intval($orgdata['frequency']);
			if( $orgdata['frequency'] > 0 ) {
				$rectyp .= $spn.'_';
			} else {
				$rectyp .= '1'.'_';
			}
			$rectyp .= '__';
			$rectyp .= implode(',',$orgdata['repeat_day']);
			$rectyp .= '#';
			$enddate = $orgdata['repeat_until'] . ' 00:00:00';
		} else if(substr($orgdata['interval'],0,3) == 'day' ) {
			$rectyp = 'day'.'_';
			$spn = intval($orgdata['frequency']);
			if( $orgdata['frequency'] > 0 ) {
				$rectyp .= $spn.'_';
			} 
			$rectyp .= '__#';
			$enddate = $orgdata['repeat_until'] . ' 00:00:00';
		} else {
			$rectyp = '';
			return $rectyp;
		}
		return $rectyp;
	}
	function bssinsert($lkdata) {
		$pt = "/^#[^#]*#/";
	   	preg_match($pt,$lkdata['member'],$matches) ;
		if( strlen($matches[0]) == 0 || $matches[0] == '#MDFY#' || $matches[0] == '#MEMB#' ) return false;
		$_db = JFactory::getDBO();
		$item = array();
		$value = array();
		foreach ( $lkdata as $key => $val ){
			$item[] = $key;
			$value[] = $_db->Quote($val);
		}
		$insa = implode("`,`",$item);
		$insb = implode(",",$value);
		$query = "INSERT INTO #__bs_events_rec (`".$insa."`) "." VALUES (".$insb.") ";
		$_db->setQuery($query);
		$_db->execute();
		if ( $_db->getErrorNum() ) {
			JError::raiseWarning( 0, $_db->getErrorMsg() );
			return false;
		}
		$event_mid = $_db->insertid();
		$lkdata['event_mid'] = $event_mid;
		$query = "UPDATE #__bs_events_rec SET event_mid = ".$_db->Quote($event_mid)." WHERE event_id = ".$_db->Quote($event_mid);
		$_db->setQuery($query);
		$_db->execute();
		if ( $_db->getErrorNum() ) {
			JError::raiseWarning( 0, $_db->getErrorMsg() );
			return false;
		}
	
		$memberstr = str_replace($matches[0],"",$lkdata['member']);
		$darr = explode ("/",$memberstr);
		
		$j = 0;
		$uids = array();
		for( $i=0;$i<count($darr);$i++ ) {
			$dmyid = explode(",",$darr[$i]);
			if( strlen($dmyid[0]) == 0  ) continue;
			if( $lkdata['createdby'] == $dmyid[0] || $lkdata['user'] == $dmyid[0]) continue;
			$uids[$j++] = $dmyid[0];
		}
		foreach( $uids as $uid ) {
			$lkdata['user'] = $uid;
			$item = array();
			$value = array();
			foreach ( $lkdata as $key => $val ){
				$item[] = $key;
				$value[] = $_db->Quote($val);
			}
			$insa = implode("`,`",$item);
			$insb = implode(",",$value);
			$_db = JFactory::getDBO();
			$query = "INSERT INTO #__bs_events_rec (`".$insa."`) "." VALUES (".$insb.") ";

			$_db->setQuery($query);
			$_db->execute();
			if ( $_db->getErrorNum() ) {
				JError::raiseWarning( 0, $_db->getErrorMsg() );
				return false;
			}
		}
		return true;
	}
/***  Mail to member  (blogstone) ***/
	function mailtomember($eventdata) {
		global $comcfg;
		$loginuser = JFactory::getUser();
		/** E-mail SEND ?? */
		if( $comcfg['bsscheduler_sendmail'] == '0' ) return ;
		$member = $eventdata["member"];
		if( $member ) {
			if( substr($member,0,6) != "#SEND#" ) return;
	        $dstr = str_replace("#SEND#","",$member);
			$darr = explode ("/",$dstr);
			$j = 0;
			$uids[$j++] = $eventdata['user'];
			for( $i=0;$i<count($darr);$i++ ) {
				$dmyid = explode(",",$darr[$i]);
				if( strlen($dmyid[0]) == 0  ) continue;
				if( $dmyid[0] == $eventdata['user']  ) continue;
				$uids[$j++] = $dmyid[0];
			}
			$searchid = '("'.implode('","',$uids).'")';
			$_db =& JFactory::getDBO();
			$query = "SELECT email,name FROM #__users WHERE id in ".$searchid;
			$_db->setQuery( $query );
			$senddata = $_db->loadAssocList();
			if ( $_db->getErrorNum() ) {
				JError::raiseWarning( 0, $_db->getErrorMsg() );
				return;
			}
			if( count($senddata) == 0 ) return;
			$i=0;
			foreach( $senddata as $row ) $sendlist[$i++] = $row['email'];
			// Build e-mail message format

			$event = $eventdata["text"];
			$dt = new JDate($eventdata["start_date"]);
			$start =$dt->format('Y/m/d H:i (l)');
			$dt = new JDate($eventdata["end_date"]);
			$end = $dt->format('Y/m/d H:i (l)');
			$createdby = $loginuser->name;
			$subjebuf = JText::_("BSC_EVENTMAIL");
			$mailforma = JPATH_ROOT.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_bsscheduler".DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."mail".DIRECTORY_SEPARATOR."eventmail_entry";
			$lang =& JFactory::getLanguage();
			if( $lang->getTag() ) 
				$mailformb = $mailforma .".". $lang->getTag().".ini";
			else 
				$mailformb = $mailforma ."ja-JP.ini";
			if( file_exists($mailformb) == false ) $mailformb = $mailforma.ja-JP.ini;
			$filecontent = implode( '',  @file( $mailformb ) );
			$message_body = sprintf( $filecontent,$start,$end,$createdby,$event );
			$subjebuf .= " [".$start."]";
			$mailer =& JFactory::getMailer();
			$mailer->setSender(array($comcfg['get_mail_user'], $comcfg['get_mail_username']));
			$mailer->setSubject( $subjebuf );
			$mailer->setBody($message_body);
			$mailer->IsHTML(false);
			// Add recipients
			$mailer->addRecipient($sendlist);
			// Send the Mail
			$rs	= $mailer->Send();
			// Check for an error
			if ( JError::isError($rs) ) {
				$msg = $rs->getError();
				JError::raiseWarning( 0, $msg );
			}
		} 
	}
}
?>
