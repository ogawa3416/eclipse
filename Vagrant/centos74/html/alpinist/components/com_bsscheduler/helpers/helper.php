<?php
/**
 * BlogStone component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		BlogStone UGMS
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2011 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: user.php 46 2011-03-12 00:01:24Z BsAlpinist ver.2.4 $
 **/
// no direct access
defined('_JEXEC') or die;
require_once( JPATH_ROOT.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_bscore'.DIRECTORY_SEPARATOR.'helper'.DIRECTORY_SEPARATOR.'bscore.helper.php' );
jimport( 'joomla.utilities.date' );
jimport( 'joomla.factory' );

class BsschedulerHelper 
{
	static function editmemschedule($action) {
		global $comcfg;
		$_db = JFactory::getDBO();
		$loginuser = JFactory::getUser();
		$status = $action->get_status();
		$orgevent = $action->get_data();
		if( $action->get_status() == 'inserted' ) {
			$eventid = $action->get_new_id();
			if( !$orgevent['event_mid'] || $orgevent['event_mid'] == '0' ) {
				$orgevent['event_mid'] = $eventid;
			}
			$query = "UPDATE #__bs_events_rec SET event_mid = ".$_db->Quote($orgevent['event_mid'])." WHERE event_id = ".$_db->Quote($eventid);
			$_db->setQuery($query);
			$_db->execute();
			if ( $_db->getErrorNum() ) {
				JError::raiseError(500, $_db->getErrorMsg() );
				return false;
			}
		} else if ($action->get_status() == 'updated') {
			$eventid = $action->get_id();
		} else if ($action->get_status() == 'deleted') {
			if( $orgevent['rec_pattern'] == 'none' ) {
				$status = 'inserted';
			} else {
				$eventid = $action->get_id();
				if( $orgevent['createdby'] == $loginuser->id && $orgevent['rec_pattern'] != 'none' && (!$orgevent['event_pid'] || $orgevent['event_pid'] == '0') ) {
					$query = "DELETE FROM #__bs_events_rec WHERE event_mid = ".$_db->Quote($orgevent['event_mid']);
					$_db->setQuery($query);
					$_db->execute();
					LogMaster::log( __CLASS__.':'.__LINE__."!!!!!!!!!Start DELETE :".$query);
					BsschedulerHelper::mailtomember($orgevent,'deleted','all');
				} else if ( $orgevent['createdby'] == $loginuser->id && ($orgevent['rec_pattern'] == 'none' || $orgevent['event_pid'] > '0')) {
					$query = "UPDATE #__bs_events_rec SET rec_type = 'none'"
								." WHERE `event_mid` = ".$_db->Quote($orgevent['event_mid']). " AND `event_length` = ".$_db->Quote($orgevent['event_length']) ;
					$_db->setQuery($query);
					$_db->execute();
					LogMaster::log( __CLASS__.':'.__LINE__."!!!!!!!!!Start DELETE UPDATE:".$query);
					BsschedulerHelper::mailtomember($orgevent,'deleted','all');
				} else {
					BsschedulerHelper::mailtomember($orgevent,'deleted','');
				}
				return;
			}
		} else {
			return;
		}
		if( $orgevent['createdby'] != $loginuser->id ) {
			BsschedulerHelper::mailtomember($orgevent,$action->get_status(),'');
			return;
		}
		if( strlen($orgevent['member']) == 0 ) {
			BsschedulerHelper::editmem_endproc($action,$orgevent,$eventid);
			return;
		}
		$pt = "/^#[^#]*#/";
	   	preg_match($pt,$orgevent['member'],$matches) ;
		if( strlen($matches[0]) == 0 ) {
			BsschedulerHelper::editmem_endproc($action,$orgevent,$eventid);
			return;
		}
		$memberstr = str_replace($matches[0],"",$orgevent['member']);
		if( strlen($memberstr) == 0 ) {
			BsschedulerHelper::editmem_endproc($action,$orgevent,$eventid);
			return;
		}

		$query = "SELECT * FROM #__bs_events_rec WHERE event_id = event_mid AND event_mid = ".$_db->Quote($orgevent['event_mid']);
		$_db->setQuery($query);
		$orgdata = $_db->loadAssoc();
		if ( $_db->getErrorNum() ) {
			JError::raiseError(500, $_db->getErrorMsg() );
			return false;
		}
		$darr = explode ("/",$memberstr);
		$j = 0;
		$uflg = 0;
		for( $i=0;$i<count($darr);$i++ ) {
			$dmyid = explode(",",$darr[$i]);
			if( strlen($dmyid[0]) == 0  ) continue;
			$uids[$j++] = $dmyid[0];
			if( $dmyid[0] == $orgdata['user'] ) $uflg = 1;
			reset($dmyid);
		}
		if( $uflg == 0 && $orgdata['user'] != $orgevent['user'] ) {
			$uids[$j] = $orgdata['user'] ;
		}
		for( $i=0;$i<count($uids);$i++) {
			if( !intval($uids[$i]) ) continue;
			if( intval($uids[$i]) == intval($orgevent['user']) ) continue;

			if( $orgevent['event_pid'] ) {
				if( $status == 'inserted' ) {
					$query = "SELECT * FROM #__bs_events_rec WHERE `event_pid` = '0' AND `user` = ".$_db->Quote($uids[$i])." AND `event_mid` = ".$_db->Quote($orgdata['event_mid']) ;
				} else {
					$query = "SELECT * FROM #__bs_events_rec WHERE `event_pid` != '0' AND `user` = ".$_db->Quote($uids[$i])." AND `event_mid` = ".$_db->Quote($orgdata['event_mid'])." AND `event_length` = ".$_db->Quote($action->get_oldevlength()) ;
				}
				$_db->setQuery($query);
				$memdata = $_db->loadAssoc();
				if ( $_db->getErrorNum() ) {
					JError::raiseError(500, $_db->getErrorMsg() );
					return false;
				}
				if( !$memdata ) {
					LogMaster::log( __CLASS__.':'.__LINE__."!!!Start ERROR get mid SELECT:".$query);
					continue;
				}
				$j = 0;
				foreach ($orgdata as $key => $val){
					if( $key == 'event_id' ) {
						continue;
					} else if( $key == 'user' ) {
						$item[$j] = 'user';
						$value[$j] =  $_db->Quote($uids[$i]);
					} else if( $key == 'event_pid' ) {
						$item[$j] = 'event_pid';
						if( $status == 'inserted' ) {
							$value[$j] =  $_db->Quote($memdata['event_id']);
						} else {
							$value[$j] =  $_db->Quote($memdata['event_pid']);
						}
					} else if( $key == 'rec_type' ) {
						$item[$j] = 'rec_type';
						if( $orgevent['rec_pattern'] == 'none' ) {
							$value[$j] = $_db->Quote('none');
						} else {
							$value[$j] =  $_db->Quote($orgevent[$key]);
						}
					} else {
						$item[$j] = $key;
						$value[$j] =  $_db->Quote($orgevent[$key]);
					}
					$j++;
				}
				if( $status == 'inserted' ) {
					$insa = implode("`,`",$item);
					$insb = implode(",",$value);
					$query = "INSERT INTO #__bs_events_rec (`".$insa."`) "." VALUES (".$insb.") ";
					LogMaster::log( __CLASS__.':'.__LINE__."!!!Start INSERT:".$query);
				} else { 
					$ista = "";
					for($j=0;$j<count($item);$j++){
						if( $j !=0 ) $ista .= ", ";
						$ista .= "`".$item[$j]."`"."=".$value[$j] ;
					}
					$query = "UPDATE #__bs_events_rec SET ".$ista
							." WHERE `user` = ".$_db->Quote($uids[$i])." AND event_pid = ".$_db->Quote($memdata['event_pid'])." AND event_mid = ".$_db->Quote($memdata['event_mid'])
							." AND `event_length` = ".$_db->Quote($action->get_oldevlength()) ;
					LogMaster::log( __CLASS__.':'.__LINE__."!!!Start UPDATE:".$query);
				}
				$_db->setQuery($query);
				$_db->execute();
				if ( $_db->getErrorNum() ) {
					JError::raiseError(500, $_db->getErrorMsg() );
					return false;
				}
			} else {
				$j = 0;
				foreach ($orgdata as $key => $val){
					if( $key == 'event_id' ) {
						continue;
					} else if( $key == 'user' ) {
						$item[$j] = 'user';
						$value[$j] =  $_db->Quote($uids[$i]);
					} else if( $key == 'event_pid' && $status == 'updated' ) {
						continue;
					} else {
						$item[$j] = $key;
						$value[$j] =  $_db->Quote($orgevent[$key]);
					}
					$j++;
				}

				$query = "SELECT count(*) FROM #__bs_events_rec WHERE `user` = ".$_db->Quote($uids[$i])." AND event_mid = ".$_db->Quote($orgdata['event_mid']) ;
				$_db->setQuery($query);
				$cnt = $_db->loadResult();
				if ( $_db->getErrorNum() ) {
					JError::raiseError(500, $_db->getErrorMsg() );
					return false;
				}
				if( $cnt ) {
					$query = "DELETE FROM #__bs_events_rec WHERE event_mid = ".$_db->Quote($orgdata['event_mid'])
								." AND event_pid != '0' ";
					$_db->setQuery($query);
					$_db->execute();
					LogMaster::log( __CLASS__.':'.__LINE__."!!!Start update DELETE:".$query);				
					$ista = "";
					for($j=0;$j<count($item);$j++){
						if( $j !=0 ) $ista .= ", ";
						$ista .= "`".$item[$j]."`"."=".$value[$j] ;
					}
					$query = "UPDATE #__bs_events_rec SET ".$ista
							." WHERE `user` = ".$_db->Quote($uids[$i])." AND event_mid = ".$_db->Quote($orgdata['event_mid']) ;
					$_db->setQuery($query);
					$_db->execute();
					LogMaster::log( __CLASS__.':'.__LINE__."!!!Start update:".$query);
				} else {
					if( intval($uids[$i]) == intval($orgdata['createdby']) && intval($uids[$i]) == intval($orgevent['user']) ) continue;
					
					$insa = implode("`,`",$item);
					$insb = implode(",",$value);
					$query = "INSERT INTO #__bs_events_rec (`".$insa."`) "." VALUES (".$insb.") ";
					LogMaster::log( __CLASS__.':'.__LINE__."!!!Start INSERT:".$query);
					$_db->setQuery($query);
					$_db->execute();
					if ( $_db->getErrorNum() ) {
						JError::raiseError(500, $_db->getErrorMsg() );
						return false;
					}
				}
			}
		}
		BsschedulerHelper::editmem_endproc($action,$orgevent,$eventid,$orgdata,$uids);
	}
	
	static function editmem_endproc($action,$orgevent,$eventid,$orgdata=NULL,$uids=NULL) {
		$_db = JFactory::getDBO();
		if ($action->get_status() == 'updated' ) {
			$delu = '';
			if( is_array($uids) ) {
				for( $i=0;$i<count($uids);$i++) {
					if( $i != 0 ) $delu .= "','";
					$delu .= $uids[$i];
				}
				$delu = $delu."','".$orgdata['user'];
			} else {
				$delu = $orgevent['user'];
			}
			if( $orgdata ) $delu = $delu."','".$orgevent['user'];
			$delu =  "('".$delu."')";
			$query = "DELETE FROM #__bs_events_rec WHERE event_mid = ".$_db->Quote($eventid)
					." AND user NOT IN ".$delu;
			LogMaster::log( __CLASS__.':'.__LINE__."!!!Start delete:".$query);
			$_db->setQuery($query);
			$_db->execute();
		}
		BsschedulerHelper::mailtomember($orgevent,$action->get_status(),'all');
	}
	
	static function mailtomember($orgevent,$status,$all) {
		global $comcfg;
		if( $orgevent['rec_pattern'] == 'none' ) {
			$status = 'deleted';
		}
		$loginuser = JFactory::getUser();
		/** E-mail SEND ?? */
		if( $comcfg['bsscheduler_sendmail'] == '0' ) return ;
		$member = $orgevent["member"];
		if( $member ) {
			if( strpos($member, "#SEND#" ) === false ) return;
	        $dstr = str_replace("#SEND#","",$member);
			if( strlen($dstr) == 0 ) return;
			$darr = explode ("/",$dstr);
			$j = 0;
			for( $i=0;$i<count($darr);$i++ ) {
				$dmyid = explode(",",$darr[$i]);
				if( strlen($dmyid[0]) == 0  ) continue;
				$uids[$j++] = $dmyid[0];
			}
			if( $all == 'all' ) {
				$searchid = '("'.implode('","',$uids).'")';
			} else {
				$ck = 0;
				for( $i=0;$i<count($uids);$i++) if( $uids[$i] == $loginuser->id ) $ck = 1;
				if( $ck == 0 ) return ; 
				$searchid = '("'.$loginuser->id.'")';
			}
			$_db = JFactory::getDBO();
			$query = "SELECT email,name FROM #__users WHERE id in ".$searchid;
			$_db->setQuery( $query );
			$senddata = $_db->loadAssocList();
			if ( $_db->getErrorNum() ) {
				JError::raiseError(500, $_db->getErrorMsg() );
				return false;
			}
			if( count($senddata) == 0 ) return;
			$i=0;
			foreach( $senddata as $row ) $sendlist[$i++] = $row['email'];
			// Build e-mail message format

			$event = $orgevent["text"];
			$dt = new JDate($orgevent["start_date"]);
			$start = $dt->format('Y/m/d H:i (l)');
			$dt = new JDate($orgevent["end_date"]);
			$end = $dt->format('Y/m/d H:i (l)');

			$createdby = $orgevent["createdbyname"];
			if( $status == 'deleted' ) {
				$subjebuf = JText::_('BSC_EVENTMAIL_DEL');
				$mailforma = JPATH_COMPONENT.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."mail".DIRECTORY_SEPARATOR."eventmail_delete";
			} else {
				if( $status == 'updated' ) $subjebuf = JText::_('BSC_EVENTMAIL_CNG');
				else 	$subjebuf = JText::_('BSC_EVENTMAIL');
				$mailforma = JPATH_COMPONENT.DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."mail".DIRECTORY_SEPARATOR."eventmail_entry";
			}
			$lang = JFactory::getLanguage();
			if( $lang->getTag() ) 
				$mailformb = $mailforma .".". $lang->getTag().".ini";
			else 
				$mailformb = $mailforma ."ja-JP.ini";
			if( file_exists($mailformb) == false ) $mailformb = $mailforma.ja-JP.ini;
			$filecontent = implode( '',  @file( $mailformb ) );
			$message_body = sprintf( $filecontent,$start,$end,$createdby,$event );
			$subjebuf .= " [".$start."]";
			$mailer = JFactory::getMailer();
			$mailer->setSender(array($comcfg['get_mail_user'], $comcfg['get_mail_username']));
			$mailer->setSubject( $subjebuf );
			$mailer->setBody($message_body);
			$mailer->IsHTML(false);
			LogMaster::log( __CLASS__.':'.__LINE__."!!!Start slist:".print_r($sendlist,true));
			LogMaster::log( __CLASS__.':'.__LINE__."!!!Start body:".$message_body);
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
	
	static function getDivlist() {
		$acuser = JFactory::getUser();
		if( $acuser->id ) {
			$_db = JFactory::getDBO();
			$query = "SELECT a.divkey divcode,c.divname_s"
				."\n FROM #__bs_accontrol a, #__bs_users_detail b,#__bs_division c"
				."\n WHERE a.com_group = 'com_bsscheduler'"
				."\n and b.userid = ".$_db->Quote($acuser->id)
				."\n and ( a.ondiv like concat('%/',b.divcode,'/%')"
				."\n or  a.onuser like concat('%/',b.userid,'/%' ))"
				."\n and a.divkey = c.divcode and c.div_stat = 1"
			;
			$_db->setQuery( $query );
			$acdiv = $_db->loadObjectList();
			if( $_db->getErrorNum() )	{
				JError::raiseError( 500, $this->_db->stderr() );
			} 
		} else {
			return false;
		}
		return $acdiv;
	}

}