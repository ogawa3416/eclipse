<?php
/**
 * BsScheduler module for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		BsScheduler
 * @subpackage	Modules
 * @copyright	Copyright (C) 2009-2011 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: helper.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
 **/
// no direct access
defined('_JEXEC') or die;

require_once( JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_bsscheduler'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php' );
require_once( JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_bscore'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php' );

class modBsschedulerHelper
{	
    var $_params = null;
	var $_starthour = 0;
	var $_endhour = 0;
	var $_field = 0;
	var $_fieldbgcolor = array();
	var $_users;
	var $_divcode;
	var $_holidays = array();

	function __construct($params,$divcode)
	{
		$this->_params = $params;
		$this->setholidays();
		$this->_divcode = $divcode;
		$_db = JFactory::getDBO();
		$query = " SELECT value FROM #__bs_scheduler_options WHERE name = 'scheduler_php'";
		$_db->setQuery( $query );
		$pval = $_db->loadResult();
		if ( $_db->getErrorNum() ) {
			JError::raiseError(500, $_db->getErrorMsg() );
		}
		$pvaltmp = explode("\n" , $pval);
		foreach( $pvaltmp as $ival ) {
			$cmp = 'templates_starthour{*:*}';
			if( strncmp( $cmp,$ival,strlen($cmp)) == 0 ){
				$this->_starthour = str_replace($cmp,'',$ival);
				continue;
			}
			$cmp = 'templates_endhour{*:*}';
			if( strncmp( $cmp,$ival,strlen($cmp)) == 0 ){
				$this->_endhour = str_replace($cmp,'',$ival);
				continue;
			}
			if( $field = $this->_params->get('customfield') ) {
				$cmp = 'customfieldsCSS{*:*}';
				if( strncmp( $cmp,$ival,strlen($cmp)) == 0 ){
					$this->_field = $field;
					$lstr = $ival;
					$Q=0;
					while(1) {
						$cka = 'dhx_cal_event.'.$field.'_';
						$lstr = strstr($lstr,$cka);
						if( $lstr == FALSE ) break; 
						if(($spos = strpos($lstr,' ')) === FALSE ) break;
						$num = substr($lstr,strlen($cka),$spos-strlen($cka));
						$num = str_replace(' ','',$num);
						$ckb = 'background-color:';
						$lstr = strstr($lstr,$ckb);
						if( $lstr == FALSE ) break;
						if(($bgpos = strpos($lstr,';')) === FALSE ) break;
						$bgc = substr($lstr,strlen($ckb),$bgpos-strlen($ckb));
						$bgc = str_replace('!important','',$bgc);
						$bgc = str_replace(' ','',$bgc);
						$this->_fieldbgcolor[$num] = $bgc;
						$Q++;
					}
				}
			}
		}
	}
	function showschedule( $ajax=0,$year,$month,$day,$modid ) 
	{
		$_db = JFactory::getDBO();
		$language= JFactory::getLanguage(); //get the current language
//		$language->load( 'mod_bsscheduler' ); 
		$language->load( 'mod_bsscheduler', JPATH_SITE.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'mod_bsscheduler' ); //load the language ini file of the module
		$userlist = BscoreHelper::getuserlistAC($this->_divcode,$this->_params->get('alldivision',1),"com_bsscheduler");
		$this->_users = $userlist;
		$usrch = '';
		for($i=0;$i<count($userlist);$i++ ){
			if( $i > 0 ) $usrch .= ",";
			$usrch .= "'".$userlist[$i]->id."'";
		}
		if( strlen($usrch) !=0 ) $usrch = "(".$usrch.")";
		else $usrch = '(0)';
		$days = $this->_params->get('display_num',5);
		$starttime = mktime(0,0,0, $month, $day, $year);
		$start = date('Y-m-d H:i:s',$starttime);
		$endtime = $starttime + (86400*$days);
		$end = date('Y-m-d H:i:s',$endtime);
		$query = "SELECT *, unix_timestamp(start_date) as uxstdate, unix_timestamp(end_date)  as uxendate, ".$this->_field
				." FROM #__bs_events_rec WHERE (`user` IN ".$usrch." OR `category`='category_1')"
				." AND `start_date` < ". $_db->Quote($end)." AND `end_date` >=".$_db->Quote($start)
				;
		$_db->setQuery( $query );

		$schelist = $_db->loadObjectList();
		if ( $_db->getErrorNum() ) {
				JError::raiseError(500, $_db->getErrorMsg() );
				return false;
		}
		$schplot = null;
		$schplot = $this->changeplot($schelist,$starttime,$endtime);
		$schdata = array();

		$myuserid = JFactory::getUser()->get('id');
		foreach( $userlist as $rowu ) {
			$schdata[$rowu->id] = new stdClass;
			$schdata[$rowu->id]->id = $rowu->id;
			$schdata[$rowu->id]->data = array();
			$ii=0;
			for($i=0;$i<count($schplot);$i++){
				if( $schplot[$i]->user == $rowu->id || $schplot[$i]->category == 'category_1' ) {
					$schdata[$rowu->id]->data[$ii] = new stdClass;
					$schdata[$rowu->id]->data[$ii]->event_id = $schplot[$i]->event_id;
					if($schplot[$i]->createdby != $myuserid && strpos($schplot[$i]->member, $myuserid.",") == false && $schplot[$i]->private_flg == '1'){
						$schdata[$rowu->id]->data[$ii]->text = JText::_('MOD_BSSCHEDULER_PRIVATE');
					}
					else{
						$schdata[$rowu->id]->data[$ii]->text = $schplot[$i]->text;
					}
					$schdata[$rowu->id]->data[$ii]->start = $schplot[$i]->start_date;
					$schdata[$rowu->id]->data[$ii]->end = $schplot[$i]->end_date;
					$schdata[$rowu->id]->data[$ii]->pday = $schplot[$i]->plot_date;
					$schdata[$rowu->id]->data[$ii]->field = intval(str_replace($this->_field.'_','',$schplot[$i]->{$this->_field}));
					$ii++;
				}
			}
		}
		return $schdata;
	}
	function changeplot($schelist,$starttime,$endtime)
	{
		$wkschelist = array();
		$plot = array();
		foreach( $schelist as $row ) {
			//  for MySQL:unix_timestamp
			if( !$row->uxendate ) {
				$row->uxendate = '9999999999';
				$ckd = date('Y','9999999999');
				if( $ckd < 2000 ) {
					$row->uxendate = '2147353200'; // 2038-01-18
				} else {
					$row->uxendate = '9999999999';
				}
			}
			$wkschelist[$row->event_id] = $row;
			if( !$row->event_pid && !$row->rec_type ) {
				$stdstr =date('Y-m-d',$row->uxstdate);
				$stdaytime = strtotime($stdstr);
				$endstr = date('Y-m-d',$row->uxendate);
				$endaytime = strtotime($endstr) + 86400;

				if( $starttime <= $row->uxstdate ) {
					$cktime =$stdaytime;
				} else {
					$cktime = $starttime;
				}
				while( $cktime < $endaytime ) {
					$rowcp = clone $row;
					$plot[$row->event_id][$cktime] = $rowcp;
					$setdate = date('Y-m-d',$cktime);
					$plot[$row->event_id][$cktime]->plot_date = $setdate;
					$cktime = $cktime + 86400;
				}
			}
		}
		foreach( $wkschelist as $key => $row ) {
			if( !$row->event_pid && $row->rec_type ) {
				$cnvdata = $this->cnvday($row,$starttime,$endtime);
				if( $cnvdata ) {
					foreach($cnvdata as $keya => $vala) {
						foreach($cnvdata[$keya] as $keyb => $valb) {
							$plot[$keya][$keyb] = $cnvdata[$keya][$keyb];
						}
					}
				}
			} else if( $row->event_pid && $row->event_length ) {
				$rowcp = clone $row;
				$plot[$row->event_pid][$row->event_length] = $rowcp;
				$ptdstr = date('Y-m-d',$row->uxstdate);
				$plot[$row->event_pid][$row->event_length]->plot_date = $ptdstr;
			}
		}
		$plotdata = array();
		$i = 0;
		foreach( $plot as $rowa ) {
			foreach( $rowa as $row ) {
				if( $row->rec_type == 'none' ) continue;
				$plotdata[$i++] = $row;
			}
		}
		return $plotdata;
	}
	function cnvday($data,$starttime,$endtime) 
	{
		$ret = null;
		if(!$data->rec_type) return $ret;
		$pat = explode("_",$data->rec_type);
		if(count($pat) < 5) return $ret;
        switch ($pat[0])
        {
            case "day":
				$pat4 = substr($pat[4],1);
                $ret = $this->getpatday($data,$starttime,$endtime,$pat[1],$pat[2],$pat[3],$pat4);
                break;
            case "week":
				$patx = explode("#",$pat[4]);
				$pat4 = $patx[0];
				$pat5 = $patx[1];
                $ret = $this->getpatweek($data,$starttime,$endtime,$pat[1],$pat[2],$pat[3],$pat4,$pat5);
                break;
            case "month":
				$pat4 = substr($pat[4],1);
                $ret = $this->getpatmonth('M',$data,$starttime,$endtime,$pat[1],$pat[2],$pat[3],$pat4);
                break;
            case "year":
				$pat4 = substr($pat[4],1);
                $ret = $this->getpatmonth('Y',$data,$starttime,$endtime,$pat[1],$pat[2],$pat[3],$pat4);
                break;
            default:
                return $ret;
        }
        return $ret;
	}
	function getpatday($data,$uxstart,$uxend,$pat1,$pat2,$pat3,$pat4) 
	{
		$ret = array();
		if( $pat1 <= 0 ) return $ret; 
		$lp = 0;
		$loop = 0;
		if( strlen($pat4) == 0 ) $loop = 0;
		else if( $pat4 == 'no' ) $loop = 0;
		else $loop = intval($pat4);
		$uxckend = strtotime(date('Y-m-d',$data->uxendate). ' 23:59:59');
		$wkstart = $data->uxstdate;
		while( $uxstart > $wkstart ) {
			$uptime = $pat1*60*60*24;
			$wkstart = $wkstart + $uptime;
			$lp++;
			if( $uptime == 0 ) return $ret;
		}
		if( $loop > 0 && $lp > $loop ) {
			 return $ret;
		}
		if( $loop == 0 && $wkstart > $uxckend ) {
			 return $ret;
		}

		$lpcheck = true;
		while( $uxend >= $wkstart ) {
			$wkrow = clone $data;
			$wkrow->start_date = date('Y-m-d H:i:s',$wkstart);
			$wkrow->end_date = date('Y-m-d H:i:s',$wkstart+$data->event_length);
			$wkrow->uxstdate = $wkstart;
			$wkrow->plot_date = date('Y-m-d',$wkstart);
			
			if( $lpcheck && !$this->changecheck( $data->event_id,$wkstart ) ) {
				$ret[$wkrow->event_id][$wkrow->uxstdate] = $wkrow;
				$lpcheck = false;
			} elseif(!$lpcheck) {
				$ret[$wkrow->event_id][$wkrow->uxstdate] = $wkrow;
			}
			$uptime = $pat1*60*60*24;
			$wkstart = $wkstart + $uptime;
			$lp++;
			if( $loop > 0 && $lp > $loop ) {
				 return $ret;
			}
			if( $loop == 0 && $wkstart > $uxckend ) {
				 return $ret;
			}
			if( $uptime == 0 ) return $ret;
		}

		return $ret;
	}
	function getpatweek($data,$uxstart,$uxend,$pat1,$pat2,$pat3,$pat4,$pat5) 
	{
		$ret = array();
		if( $pat1 <= 0 ) return $ret; 
		$lp = 0;
		$loop = 0;
		if( strlen($pat5) == 0 ) $loop = 0;
		else if( $pat5 == 'no' ) $loop = 0;
		else $loop = intval($pat5); 
		if( !$pat4 ) $pat4 = 0;
		$span = explode(',',$pat4);
		$i=0; 
		$wkstart = $data->uxstdate;
		$uxckend = strtotime(date('Y-m-d',$data->uxendate). ' 23:59:59');
		$startw = date('w',$data->uxstdate);
		$pos = array_search($startw,$span);
		if($pos === FALSE){
			for($pos=0;$pos<count($span);$pos++){
				if($startw < $span[$pos]) break;
			}
			if( $pos == count($span) ) $i=0;
			else $i=$pos; 
		} else {
			if( $pos+1 >= count($span) ) $i=0;
			else $i = $pos+1;
		}
		while( $uxstart > $wkstart ) {
			$wd = $span[$i]-$startw;
			if( $wd <= 0){
				$wd = $wd + $pat1 * 7;
			} 
			$uptime = $wd*60*60*24;
			$wkstart = $wkstart + $uptime;
			$startw = $span[$i];
			$lp++;
			$i++;
			if( count($span) <= $i ) $i = 0;
			if( $uptime == 0 ) return $ret;
		}
		if( $loop > 0 && $lp > $loop ) {
			 return $ret;
		}
		if( $loop == 0 && $wkstart > $uxckend ) {
			 return $ret;
		}
		$lpcheck = true;
		while( $uxend >= $wkstart ) {
			$startw = date('w',$wkstart);
			if( in_array($startw,$span) ) {
				$wkrow = clone $data;
				$wkrow->start_date = date('Y-m-d H:i:s',$wkstart);
				$wkrow->end_date = date('Y-m-d H:i:s',$wkstart+$data->event_length);
				$wkrow->uxstdate = $wkstart;
				$wkrow->plot_date = date('Y-m-d',$wkstart);
				if( $lpcheck && !$this->changecheck( $data->event_id,$wkstart ) ) {
					$ret[$wkrow->event_id][$wkrow->uxstdate] = $wkrow;
					$lpcheck = false;
				} elseif(!$lpcheck) {
					$ret[$wkrow->event_id][$wkrow->uxstdate] = $wkrow;
				}
			}
			$wd = $span[$i]-$startw;
			if( $wd <= 0){
				$wd = $wd + $pat1 * 7;
			} 
			$uptime = $wd*60*60*24;
			$wkstart = $wkstart + $uptime;
			$lp++;
			$i++;
			if( count($span) <= $i ) $i = 0;
			if( $loop > 0 && $lp > $loop ) {
				 return $ret;
			}
			if( $loop == 0 && $wkstart > $uxckend ) {
				 return $ret;
			}
			if( $uptime == 0 ) return $ret;
		}
		return $ret;
	}
	function getpatmonth($ym,$data,$uxstart,$uxend,$pat1,$pat2,$pat3,$pat4) 
	{
		$ret = array();
		if( $pat1 <= 0 ) return $ret; 
		$lp = 0;
		$loop = 0;
		if( strlen($pat4) == 0 ) $loop = 0;
		else if( $pat4 == 'no' ) $loop = 0;
		else $loop = intval($pat4); 
		if( !$pat2 ) $pat2 = 0;
		$i=0;
		$uxckend = strtotime(date('Y-m-d',$data->uxendate). ' 23:59:59');
		$wkstart = $data->uxstdate;
		$sdy = date("Y",$wkstart);
		$sdm = date("m",$wkstart);
		$sdd = date("d",$wkstart);
		$evtime = $wkstart - mktime(0, 0, 0, $sdm, $sdd, $sdy);
		while( $uxstart > $wkstart ) {
			if( $pat2 && $pat3 ) {
				if( $ym == 'Y' ) {
					$sdy = date("Y",$wkstart) + $pat1;
					$sdm = date("m",$wkstart) ;
				} else {
					$sdy = date("Y",$wkstart);
					$sdm = date("m",$wkstart) + $pat1;
				}
				$wkstart = $this->getmonthweek($sdy, $sdm, $pat3, $pat2) + $evtime;
			} else {
				if( $ym == 'Y' ) {
					$sdy = date("Y",$wkstart)+ $pat1;
					$sdm = date("m",$wkstart);
					$sdd = date("d",$wkstart);
					$wkstart = mktime(0, 0, 0, $sdm, $sdd, $sdy) + $evtime;
				} else {
					$sdy = date("Y",$wkstart);
					$sdm = date("m",$wkstart) + $pat1;
					$sdd = date("d",$wkstart);
					$wkstart = mktime(0, 0, 0, $sdm, $sdd, $sdy) + $evtime;
				}
			}
			$lp++;
			if( $evtime == 0 ) return $ret;
		}
		if( $loop > 0 && $lp > $loop ) {
			 return $ret;
		}
		if( $loop == 0 && $wkstart > $uxckend ) {
			 return $ret;
		}
		$lpcheck = true;
		while( $uxend >= $wkstart ) {
			$wkrow = clone $data;
			$wkrow->start_date = date('Y-m-d H:i:s',$wkstart);
			$wkrow->end_date = date('Y-m-d H:i:s',$wkstart+$data->event_length);
			$wkrow->uxstdate = $wkstart;
			$wkrow->plot_date = date('Y-m-d',$wkstart);

			if( $lpcheck && !$this->changecheck( $data->event_id,$wkstart ) ) {
				$ret[$wkrow->event_id][$wkrow->uxstdate] = $wkrow;
				$lpcheck = false;
			} elseif(!$lpcheck) {
				$ret[$wkrow->event_id][$wkrow->uxstdate] = $wkrow;
			}
			if( $pat2 && $pat3 ) {
				if( $ym == 'Y' ) {
					$sdy = date("Y",$wkstart) + $pat1;
					$sdm = date("m",$wkstart) ;
					$wkstart = $this->getmonthweek($sdy, $sdm, $pat3, $pat2) + $evtime;
				} else {
					$sdy = date("Y",$wkstart);
					$sdm = date("m",$wkstart) + $pat1;
					$wkstart = $this->getmonthweek($sdy, $sdm, $pat3, $pat2) + $evtime;
				}
			} else {
				if( $ym == 'Y' ) {
					$sdy = date("Y",$wkstart)+ $pat1;
					$sdm = date("m",$wkstart);
					$sdd = date("d",$wkstart);
					$wkstart = mktime(0, 0, 0, $sdm, $sdd, $sdy) + $evtime;
				} else {
					$sdy = date("Y",$wkstart);
					$sdm = date("m",$wkstart) + $pat1;
					$sdd = date("d",$wkstart);
					$wkstart = mktime(0, 0, 0, $sdm, $sdd, $sdy) + $evtime;
				}
			}
			$lp++;
			if( $loop > 0 && $lp > $loop ) {
				 return $ret;
			}
			if( $loop == 0 && $wkstart > $uxckend ) {
				 return $ret;
			}
			if( $evtime == 0 ) return $ret;
		}
		return $ret;
	}
	function getmonthweek($year, $month, $num, $week)
	{
		$wk = date("w", mktime(0, 0, 0, $month, 1, $year));
		$day = $week - $wk + 1;
		if($day <= 0) $day += 7;
		$dt = mktime(0, 0, 0, $month, $day, $year);
		$dt += (86400 * 7 * ($num - 1));
		return $dt;
	}
	function getweekname( $year, $month, $day ,$op = null )
	{

		$hday = '';

		if(!$op){
			$dayNames = array(JText::_( 'SUN' ), JText::_( 'MON' ),JText::_( 'TUE' ), JText::_( 'WED' ),
							JText::_( 'THU' ),	JText::_( 'FRI' ),JText::_( 'SAT' ));
		}
		else{
			$dayNames = array('sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat');
			//check holiday		
			if(in_array($year.'-'.$month.'-'.$day, $this->_holidays)){
				//add classname
				$hday = ' d_hday';
			}
		}
		$time = mktime(0,0,0, $month, $day, $year);
		$wk = date('w',$time); 

		return $dayNames[$wk].$hday;
	}

	function divcodelist($name, $active = '0', $javascript = NULL, $order = 'divcode', $size = 1,$all='0' )
	{
		if( !$active ) $active = '0';
		$divarr = BsschedulerHelper::getDivlist();
		if( $all ) {
			$divs[] = JHTML::_('select.option',  '0', '--'. JText::_('BSC_ALL_DIVISION') .'--' ,'divcode', 'divname_s');
			if( $divarr ) {
				$divs = array_merge( $divs, $divarr );
			} 
		} else {
			if( is_array($divarr) ) {
				$divs = $divarr;
			} else {
				$divs[] = '';
			}
		}
		if( count($divs) > 0 ) {
			$divnames = JHTML::_('select.genericlist',   $divs, $name, 'class="inputbox" size="'. $size .'" '. $javascript, 'divcode', 'divname_s', $active );
		} else {
			$divnames = '';
		}
		return $divnames;
	}
	function getstarthour()
	{
		return $this->_starthour;
	}
	function getendhour()
	{
		return $this->_endhour;
	}
	function getfieldbgcolor()
	{
		return $this->_fieldbgcolor;
	}
    function loadnow($schdata) 
    {	
		ob_clean();
		echo "<!--bsscheduler###dataview-begin###-->";
		header("Content-type:text/xml");
		echo "<?xml version='1.0' ?>";
		$snddata = "<data>";
		$snddata .= "<users>";
		foreach ($schdata as $v) {
			$snddata .= "<uid><![CDATA[".$v->id."]]></uid>";
			$snddata .= "<schdata>";
			foreach ($v->data as $vv) {
				$snddata .= "<eventid><![CDATA[".$vv->event_id."]]></eventid>";
				$snddata .= "<text><![CDATA[".$vv->text."]]></text>";
				$snddata .= "<start><![CDATA[".$vv->start."]]></start>";
				$snddata .= "<end><![CDATA[".$vv->end."]]></end>";
				$snddata .= "<pday><![CDATA[".$vv->pday."]]></pday>";
				$snddata .= "<field><![CDATA[".$vv->field."]]></field>";
			}
			$snddata .= "</schdata>";
		}
		$snddata .= "</users>";
		$snddata .= "</data>";
		echo $snddata;

		echo "<!--bsscheduler###dataview-end###-->";
	}
	function changecheck($event_pid,$wkstart)
	{
		$_db = JFactory::getDBO();
		$query = "SELECT count(*) FROM #__bs_events_rec "
				." WHERE `event_pid` = ".$_db->Quote($event_pid)." AND `event_length` = ". $_db->Quote($wkstart)
				;
		$_db->setQuery( $query );
		$exist = $_db->loadResult();
		if( $exist > 0 ) return true;
		return false;
	}
	private function setholidays()
	{
 		$_db = JFactory::getDBO();
		$query = ' SELECT holiday FROM #__bs_coholiday WHERE holiday_stat = 1 ORDER BY holiday' ;
		$_db->setQuery( $query );
		$result = $_db->loadObjectList();

		foreach ($result as $row) {
		    $this->_holidays[] = $row->holiday;
		}

		return ;

	}
}
?>