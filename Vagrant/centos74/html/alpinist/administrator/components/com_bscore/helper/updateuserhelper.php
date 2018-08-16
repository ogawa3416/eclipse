<?php
/**
 * Alpinist component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		Alpinist
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2011 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: updateuserhelper.php 121 2015-04-01 22:53:33Z BsAlpinist ver.2.6.0 $
 **/
// no direct access
defined('_JEXEC') or die;

class BsupdatedetailHelper 
{
	/**
	 * User Detail table Update 
	 */
	static function updateuser( $user, $columns=null)
	{
		if( empty($user['id']) ) return false;
		
		$db = JFactory::getDBO();
		if( is_null($columns) ) {
			$columns = $db->getTableColumns('#__bs_users_detail');
			if( !$columns ) return false;
		}
	
		if( isset($user['bsprofile']) ) {
			$profile = $user['bsprofile'];
		} else {
			$query = 'SELECT * FROM #__user_profiles'
			. ' WHERE user_id = '.$db->Quote($user['id']);
			;
			$db->setQuery( $query );
			$profinf = $db->loadObjectList();

			if( !$profinf ) return false;
		
			$profile = array();
			$isMgr = false;
			for($i=0;$i<count($profinf);$i++) {
				$itemnm = str_replace("bsprofile.","",$profinf[$i]->profile_key);
				$profile[$itemnm] = $profinf[$i]->profile_value;
			}
		}
		foreach($profile as $key => $val) {
			if( $key == "divcode" ) {
				if( strlen(trim($val)) == 0 ) {
					$isMgr = true;
				}
			}
		}
		
		if( $isMgr ) $user['block'] = 1;
		
		$data = array();
		$i=0;
		foreach($columns as $key => $val) {
			if( isset($profile[$key]) ) {
				$data[$key] = $profile[$key];
			} else {
				if( $key == 'userid' ) continue;
				$data[$key] = '';
			}
			$i++;
		}
		
		$query = "SELECT count(userid) FROM #__bs_users_detail WHERE userid = ".$db->Quote($user['id'])
			;
		$db->setQuery( $query );
		$isuser = $db->loadResult();
		$ustate = '';
		if( $isuser == 0 && $user['block'] == 0 ) $ustate = 'new';
		elseif( $isuser > 0 && $user['block'] == 0 ) $ustate = 'update';
		elseif( $isuser > 0 && $user['block'] == 1 ) $ustate = 'delete';
		else $ustate = '';
		
		if( $ustate=='new' ) { 	// NEW
			$sql = "INSERT INTO #__bs_users_detail ( userid";
			foreach( $data as $key => $val ){
				$sql .= ",". $key;
			}
			$sql .= ") VALUES (".$db->Quote($user['id']);
			foreach( $data as $key => $val ){
				$sql .= ",". $db->Quote($val);
			}
			$sql .= ")";
		} else if( $ustate=='update' )  { 			// UPDATE
			$sql = "UPDATE #__bs_users_detail SET ";
			$fst = true;
			foreach( $data as $key => $val ){
				if(!$fst) $sql .= ",";
				$sql .= $key." = ". $db->Quote($val);
				$fst = false;
			}
			$sql .= " WHERE userid=".$db->Quote($user['id']);
		} else if( $ustate=='delete' )  { 			// UPDATE
			$sql = "DELETE FROM #__bs_users_detail WHERE userid=".$db->Quote($user['id']);
		}
		if( !empty($ustate) ) { 
			$db->setQuery($sql);
			if ( !$db->execute() ) {
				JError::raiseError(500, $db->getErrorMsg() );
			}
		}
		return true;
	}
	/**
	 * ALL User Detail table Update 
	 */
	static function updateuserALL( )
	{
		$db = JFactory::getDBO();
		
		$query = 'SELECT * FROM #__users WHERE 1';
		;
		$db->setQuery( $query );
		$users = $db->loadObjectList();
		
		$columns = $db->getTableColumns('#__bs_users_detail');
		if( !$columns ) return false;
		
		$ucnt = 0;
		for($i=0;$i<count($users);$i++){
			if( $users[$i]->id == 0 ) continue;
			$user['id'] = $users[$i]->id;
			$user['name'] = $users[$i]->name;
			$user['email'] = $users[$i]->email;
			$user['block'] = $users[$i]->block;
			if( !self::updateuser($user,$columns) ) continue;
			$ucnt++;
		}
		return $ucnt;
	}
}