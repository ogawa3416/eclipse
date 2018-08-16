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
 * @version		$Id: helper.php 121 2012-04-01 22:53:33Z BsAlpinist ver.2.3.1 $
 **/
// no direct access
defined('_JEXEC') or die;

class BscoreHelper 
{
	/**
	 * Retrieves division code 
	 * @return array Array of objects containing the data from the database
	 */
	static function divcodelist($name, $active = '0', $javascript = NULL, $order = 'divcode', $size = 1,$alluser=1 )
	{
		if( !$active ) $active = '0';
		$_db = JFactory::getDBO();
		$query = 'SELECT divcode as value, divname_s as text'
		. ' FROM #__bs_division'
		. ' WHERE div_stat = 1'
		. ' ORDER BY '. $order
		;
		$_db->setQuery( $query );
		if( $alluser ) {
			$divs[] = JHTML::_('select.option',  '0', '--'. JText::_('BSC_ALL_DIVISION') .'--' );
			$divs = array_merge( $divs, $_db->loadObjectList() );
		} else {
			$divs = $_db->loadObjectList();
		}
		$divnames = JHTML::_('select.genericlist',   $divs, $name, 'class="inputbox" size="'. $size .'" '. $javascript, 'value', 'text', $active );
		return $divnames;
	}
	static function getuserlistAC( $divcode=null, $alldiv, $com )
	{
		$_db = JFactory::getDBO();
		$user		= JFactory::getUser();
		$userId		= (int) $user->get('id');
		$wherediv = '';
		if( !$divcode ) {
			if( !$alldiv ) {
				$query = ' SELECT a.divcode'
					.' FROM #__bs_division a,#__bs_users_detail b '
					.' WHERE b.userid = '.$userId
					.' AND b.divcode = a.divcode AND a.div_stat = 1 ' 
					;
					$_db->setQuery( $query );
					$divcode = $_db->loadResult();
				$wherediv = " AND c.divcode = ".$_db->Quote($divcode);
			} else {
				$wherediv = "";
			}
		} else {
			$wherediv = " AND c.divcode = ".$_db->Quote($divcode);
		}
		$query = "SELECT u.`id`,u.`name` as `name` " 
			."\n FROM #__users as u, #__bs_accontrol a, #__bs_users_detail b, #__bs_users_detail c  "
			."\n WHERE u.block = 0 AND a.com_group = ".$_db->Quote($com)
			."\n AND c.userid = u.id AND b.userid = ".$_db->Quote($userId)
			."\n AND a.divkey = c.divcode"
			."\n AND ( a.ondiv like concat('%/',b.divcode,'/%')"
			."\n OR  a.onuser like concat('%/',b.userid,'/%' ))"
			."\n ".$wherediv
			."\n ORDER BY c.divcode,u.name ASC";
			;
		$_db->setQuery( $query );
		$userlist = $_db->loadObjectList();
		if ( $_db->getErrorNum() ) {
				JError::raiseError(500, $_db->getErrorMsg() );
				return false;
		}
		return $userlist;
	}
	static function getuserlist( $divcode=null, $alldiv )
	{
		$_db = JFactory::getDBO();
		$user		= JFactory::getUser();
		$userId		= (int) $user->get('id');
		$wherediv = '';
		if( !$divcode ) {
			if( !$alldiv ) {
				$query = ' SELECT a.divcode'
					.' FROM #__bs_division a,#__bs_users_detail b '
					.' WHERE b.userid = '.$userId
					.' AND b.divcode = a.divcode AND a.div_stat = 1 ' 
					;
					$_db->setQuery( $query );
					$divcode = $_db->loadResult();
				$wherediv = " AND c.divcode = ".$_db->Quote($divcode);
			} else {
				$wherediv = "";
			}
		} else {
			$wherediv = " AND c.divcode = ".$_db->Quote($divcode);
		}
		$query = "SELECT u.`id`,u.`name` as `name` " 
			."\n FROM #__users as u, #__bs_users_detail c"
			."\n WHERE u.block = 0 AND c.userid = u.id "
			."\n ".$wherediv
			."\n ORDER BY c.divcode ASC,u.name ASC";
			;
		$_db->setQuery( $query );
		$userlist = $_db->loadObjectList();
		if ( $_db->getErrorNum() ) {
				JError::raiseError(500, $_db->getErrorMsg() );
				return false;
		}
		return $userlist;
	}
	static function getuserdiv( $uid ) {
		if( !$uid ) return false;
		$_db = JFactory::getDBO();
		$query = "SELECT u.`userid`,u.`name1`, u.`name2`, d.`divcode`,d.`divname_s`, d.`divname`, d.`div_stat` " 
			."\n FROM #__bs_users_detail u, #__bs_division d"
			."\n WHERE u.divcode = d.divcode AND u.userid = ".$_db->Quote($uid);
			;
		$_db->setQuery( $query );
		$userdiv = $_db->loadObject();
		if ( $_db->getErrorNum() ) {
			JError::raiseError(500, $_db->getErrorMsg() );
			return false;
		}
		return $userdiv;
	}
	static function BsgetItemId( $iview,$ilayout,$option='com_bscore' ) {
		$dbb = JFactory::getDBO();
		$link = "index.php?option=".$option."&view=".$iview ;
		if( $ilayout ) {
			$link .= "&layout=".$ilayout ;
		}
		$query = "SELECT id FROM #__menu WHERE link='".$link."'"." and published>=0";
		$dbb->setQuery( $query );
		$rows = $dbb->loadObjectList();
		if( !isset($rows[0]) ) return false;
		return $rows[0]->id;
	}
	static function getWorkDate($idate,$afn) {
		$dt = explode("-",$idate);
		if( !$dt[0] )  $dt = explode("/",$idate);
		mktime (0,0,0,$dt[1],$dt[2]+$afn,$dt[0]);
		$rweek = date('w',$mtime);
		if( $rweek == 0 ) {
			$afn = $afn+1;
			$mtime = mktime (0,0,0,$dt[1],$dt[2]+$afn+1,$dt[0]);
		} else if ( $rweek == 6 ) {
			$afn = $afn+2;
		} 
		$mtime = mktime (0,0,0,$dt[1],$dt[2]+$afn,$dt[0]);
		$rdate = date('Y-m-d',$mtime);
	 
 		$_db = JFactory::getDBO();
		$query = ' SELECT * FROM #__bs_coholiday WHERE holiday_stat = 1 and holiday >= '.$rdate
				.' ORDER BY holiday' ;
		$_db->setQuery( $query );
		$hdate = $_db->loadObjectList();
		if (!$hdate)	{	
			return $rdate;
		}
	
		for ($i=0; $i < count($hdate); $i++) {
			$afn++;
			if( $hdate[$i]->holiday == $rdate ) {
				$mtime = mktime(0,0,0,$dt[1],$dt[2]+$afn,$dt[0]);
				$rweek = date('w',$mtime);
				if( $rweek == 0 ) {
					$afn =$afn+1;
					$mtime = mktime (0,0,0,$dt[1],$dt[2]+$afn,$dt[0]);
				} else if ( $rweek == 6 ) {
					$afn = $afn+2;
					$mtime = mktime (0,0,0,$dt[1],$dt[2]+$afn,$dt[0]);
				} 
				$rdate = date('Y-m-d',$mtime);
			} else if( $hdate[$i]->holiday < $rdate ) {
				continue;
			} else {
				break;
			}
		}
		return $rdate;
	}
	/**
	 * Retrieves division code 
	 * @return array()  Login URI , Logout URI
	 */
	static function getLoginout()
	{
		$db = JFactory::getDbo();
		$rdata['loginItemid'] = null;
		$rdata['login'] = null;
		$rdata['logout'] = null;
		// com_users login
		$query = "SELECT id,params FROM #__menu WHERE link ='index.php?option=com_users&view=login' and published=1 ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if( count($rows) ) {
			$paraobj = new JRegistry;
			$paraobj->loadString($rows[0]->params);
			$rdata['login'] = $paraobj->get('login_redirect_url');
			$rdata['logout'] = $paraobj->get('logout_redirect_url');
			if( !JURI::isInternal($rdata['login']) ) $rdata['login'] = null;
			if( !JURI::isInternal($rdata['logout']) ) $rdata['logout'] = null;
			$rdata['loginItemid'] = $rows[0]->id;
		}
		// mod_login
		$modlogin = null;
		$modlogout = null;
		$query = "SELECT params FROM #__modules WHERE module='mod_login' and published>0 and client_id=0";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if( count($rows) ) {
			$paraobj = new JRegistry;
			$paraobj->loadString($rows[0]->params);
			if( $paraobj->get('login')  ) { 
				$query = "SELECT link FROM #__menu WHERE id=".$paraobj->get('login');
				$db->setQuery( $query );
				$modlogin = $db->loadResult();
				if( $modlogin ) {
					$modlogin = $modlogin."&Itemid=".$paraobj->get('login');
				}
			}
			if( $paraobj->get('logout')  ) { 
				$query = "SELECT link FROM #__menu WHERE id=".$paraobj->get('logout');
				$db->setQuery( $query );
				$modlogout = $db->loadResult();
			}
		}
		if( !isset($rdata['login']) ) $rdata['login'] = $modlogin;
		if( !isset($rdata['logout']) ) $rdata['logout'] = $modlogout;
		if( isset($rdata['logout']) )  $rdata['logout'] = strstr($rdata['logout'],'index.php');
		return $rdata;
	}
	/**
	 * Get Tax rate 
	 * @return tax rate
	 */
	static function getTaxrate($recdate=null)
	{
		global	$comcfg;
		$retrate = 0;
		if( $recdate ) {
			$recdate = str_replace('/','-',$recdate);
			$dtarr = explode('-',$recdate);
			$recdate = sprintf("%d-%02d-%02d",$dtarr[0],$dtarr[1],$dtarr[2]);
		} else {
			$config = JFactory::getConfig();
			$now = JFactory::getDate();
			$now->setTimeZone(new DateTimeZone($config->get('offset')));
			$recdate = $now->format( "Y-m-d",true );
		} 
		$taxrate = explode(',',$comcfg['taxrate']);
		$taxdate = explode(',',$comcfg['taxdate']);
		arsort($taxdate);
		foreach( $taxdate as $key => $val ) {
			$stdate = str_replace('/','-',$val);
			$dtarr = explode('-',$stdate);
			$stdate = sprintf("%d-%02d-%02d",$dtarr[0],$dtarr[1],$dtarr[2]);
			if( $stdate <= $recdate ) {
				$retrate = $taxrate[$key];
				break;
			}
		}
		return $retrate;
	}
	static function taxlist($name,$javascript=null,$active=null,$id=null )
	{
		global	$comcfg;
/****
		$retrate = 0;
		if( $recdate ) {
			$recdate = str_replace('/','-',$recdate);
			$dtarr = explode('-',$recdate);
			$recdate = sprintf("%d-%02d-%02d",$dtarr[0],$dtarr[1],$dtarr[2]);
		} else {
		}
****/ 
		$taxrate = explode(',',$comcfg['taxrate']);
		$taxdate = explode(',',$comcfg['taxdate']);
		arsort($taxdate);
		$i = 0;
		$taxs = array();
		foreach( $taxdate as $key => $val ) {
			$taxs[$i] = new stdClass;
			$taxs[$i]->text = $taxrate[$key].'%';
			$taxs[$i]->value = $taxrate[$key];
			$i++;
		}
		$taxs[$i] = new stdClass;
		$taxs[$i]->text = JText::_('JNONE');
		$taxs[$i]->value = 0;
		if( is_null($active) ) $active = BscoreHelper::getTaxrate();
		$taxlist = JHTML::_('select.genericlist',   $taxs, $name, 'class="inputbox" size="1" '. $javascript, 'value', 'text', $active );
		if( isset($id) ){
			$tmpid = str_replace(array('[', ']', ' '), '', $name);
			$taxlist = str_replace('id="'.$tmpid.'"', 'id="'.$id.'"', $taxlist);
		}
		return $taxlist;
	}
/**
 * Convert from the SqueezeBox to the dialog for mobile use
 **/
	static function modal($url,$text='',$attri=null,$idname='modal',$icon='' )
	{
		$url = JRoute::_($url,false);
		$retval = '';

		$addclass = '';
		if( is_array($attri) && isset($attri['class']) ){
			$addclass = $attri['class'];
		}
		if( empty($addclass) ) {
			$addclass = "modallink";
		}
		$onclick = '';
		if( is_array($attri) && isset($attri['onclick']) ){
			$onclick = ' onclick="'.$attri['onclick'].'"';;
		}
		$title = $text;
		if( is_array($attri) && isset($attri['title']) ){
			$title = $attri['title'];
		}

		$params = array();
		$params['title']  = $title;
		$params['url']    = $url;
		if( is_array($attri) && isset($attri['height']) ){
			if( $attri['height'] == 'auto' ) {
				$addclass .= " modalauto";
			} else {
				$params['height'] = $attri['height'];
			}
		}
		if( is_array($attri) && isset($attri['width']) ){
			$params['width'] = $attri['width'];
		}

//		$params['footer'] = '<button class="btn" data-dismiss="modal" aria-hidden="true">'. JText::_("JLIB_HTML_BEHAVIOR_CLOSE") . '</button>';

		$retval = '<a href="#' . $idname.'" data-toggle="modal" class="'.$addclass.'"'.$onclick.'>'.$icon.$text.'</a>';
		$retval .= JHtml::_('bootstrap.renderModal', $idname, $params);

		return $retval;
	}
	static function is_mobile()
	{
		jimport( 'joomla.application.web.webclient' );
		$client = new JWebClient();
		if( $client->__get('mobile') ) return true;
		else return false;
	}
	static function in_mobile()
	{
		self::is_mobile();
	}
/**
 * I ask for an ID from the template name
 **/
	static function getTmplidbyName($name){
  		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, template, params');
		$query->from('`#__template_styles`');
		$query->where('`client_id` = 0 AND `template` LIKE \''.$name.'\' ');
		$query->order('`id` ASC');
		$db->setQuery( $query );
		$row = $db->loadObject();
		if(!$row){
			return false;
		}else{
			return $row->id;
		}	
  	}
}