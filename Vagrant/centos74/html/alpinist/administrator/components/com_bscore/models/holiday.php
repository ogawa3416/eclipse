<?php
/**
 * BsCore component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		BsAlpinist
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2010 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: holiday.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.6.0 $
 **/
 
defined ('_JEXEC') or die();
jimport('joomla.application.component.modellist');
require_once( JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_bscore'.DIRECTORY_SEPARATOR.'helper'.DIRECTORY_SEPARATOR.'holiday.helper.php' );

class BscoreModelHoliday extends JModelList {
	var $manual = 0;
	
	function __construct()
	{
		parent::__construct();
		$this->manual = 0;
	}
	function publish($onoff,$data) {
		$db	= JFactory::getDBO();
		$my = JFactory::getUser();
		$date = JFactory::getDate();
		$time = $date->tosql();

		foreach ($data['cid'] as $rowhol)	{
			$holid = null;
			$holiday = null;
			$holiday_stat = null;
			for ($i=0;$i<count($data['code_stat']);$i++)	{
				list($holid, $holiday_stat) = explode('/', $data['code_stat'][$i]);
				if( $holid == $rowhol ) break; 
			}
			if( $holiday_stat == $onoff || !(intval($holiday_stat) == 0 || intval($holiday_stat) == 1)) continue;
			if( !$holid ) continue;

			$query = "UPDATE #__bs_coholiday SET `holiday_stat` = ".$onoff
				." ,`modified` = ".$db->Quote($time) .", `modified_by` = ".$my->id
				." WHERE `id` = ". $db->Quote($rowhol)
				;

			$db->setQuery($query);
			if ( !$db->execute() ) {
				JError::raiseError(500, $_db->getErrorMsg() );
			}
		}
		return true;
	}
	function store($data) {
		$app = JFactory::getApplication();
		$input = $app->input;
		
		$task = $input->getCmd('task');
		$db	= JFactory::getDBO();
		$my = JFactory::getUser();
		$date = JFactory::getDate();
		$time = $date->tosql();

		if( !$data['holiday'] ) {
			JError::raiseWarning( 0, JText::_('BSC_ERR_NOTHOLIDAY'));
			return true;
		}
		if( $task == 'editnew' ) {
			$query = 'SELECT COUNT(*)' .
					' FROM #__bs_coholiday WHERE holiday='.$db->Quote($data['holiday']);
					;
			$db->setQuery($query);
			$cnt = $db->loadResult();
			if ($cnt > 0) {
				JError::raiseWarning( 0, JText::_('BSC_ERR_DUPLICATEHOL'));
				return true;
			}
				
			$query = "INSERT INTO #__bs_coholiday (`holiday`,`holidayname`,`holiday_stat`,`manual`,"
				."\n `modified`, `modified_by`, `created`, `createdby` )  "
				."\n VALUES ( ".$db->Quote($data['holiday']).",".$db->Quote($data['holidayname']).",".$db->Quote($data['holiday_stat']).","
				."\n ".$db->Quote($data['manual']).","
				."\n ".$db->Quote($time).",".$db->Quote($my->id).",".$db->Quote($time).",".$db->Quote($my->id)
				.")";
			$db->setQuery($query);
			$db->execute();
			if ($db->getErrorNum()) {
				if ($db->getErrorNum() == 1062) {
					JError::raiseWarning( 0, JText::_('BSC_ERR_DUPLICATEHOLIDAY'));
				} else {
					JError::raiseError(500, $db->getErrorMsg() );
				}
			}
		} else {
			$query = 'SELECT COUNT(*) ' .
				' FROM #__bs_coholiday AS a' .
				' WHERE a.holiday='.$db->Quote($data['holiday']).' AND `id` != '. $db->Quote($data['holid']);
			$db->setQuery($query);
			$hdcnt = $db->loadResult();
			if( $hdcnt > 0 ) {
				JError::raiseWarning( 0, JText::_('BSC_ERR_DUPLICATEHOLIDAY').'   ['.$data['holiday'].']');
				return true;
			}
			
			$query = "UPDATE #__bs_coholiday SET "
				."\n `holiday`=".$db->Quote($data['holiday']).",`holidayname`=".$db->Quote($data['holidayname'])
				."\n,`holiday_stat`=".$db->Quote($data['holiday_stat']).",`manual`="   .$db->Quote($data['manual'])
				."\n,`modified`=" .$db->Quote($time).		",`modified_by`=". $db->Quote($my->id)
				." WHERE `id` = ". $db->Quote($data['holid'])
				;
			$db->setQuery($query);
			$db->execute();
			if ($db->getErrorNum()) {
				JError::raiseError(500, $db->getErrorMsg() );
			}
		}
		return true;
	}
	function delete($data) {
		$db	= JFactory::getDBO();
		$app = JFactory::getApplication();
		$input = $app->input;
		
		foreach ($data['cid'] as $holid) {
			if( !$holid ) continue;
			
			$query = "DELETE FROM #__bs_coholiday WHERE `id` = ". $db->Quote($holid)
				;
			$db->setQuery($query);
			if ( !$db->execute() ) {
				JError::raiseError(500, $_db->getErrorMsg() );
			}
		}
		$year = $input->get('selected_y');
		if( $year ) {
			$yearstart = sprintf("%s-1-1",$year);
			$yearend = sprintf("%s-12-31",$year);
			$query = 'SELECT COUNT(*) ' .
				' FROM #__bs_coholiday AS a'
				.' WHERE a.holiday >= '.$db->Quote($yearstart).' AND a.holiday <= '.$db->Quote($yearend)
				;
			$db->setQuery($query);
			$yearnum = $db->loadResult();
		}
		if( !$yearnum ) {
			$year = null;
		}
		return $year;
	}
	function getData() {
		$app = JFactory::getApplication();
		$input = $app->input;
		$db	= JFactory::getDBO();

		$year = $input->get('selected_y');
		if( !$year ) {
			$db	= JFactory::getDBO();
			// Get year list
			$query = 'SELECT MAX(a.holiday) AS year' .
				' FROM #__bs_coholiday AS a' .
				' WHERE 1 ';
			$db->setQuery($query);
			$year = $db->loadResult();
			if( strlen( $year ) > 4 ) {
				$year = substr( $year,0,4 ) ;
			}
		}

		if( !$year ) {
			// Get the now year
			$config = JFactory::getConfig();
			$now = JFactory::getDate();
			$now->setTimeZone(new DateTimeZone($config->get('offset')));
			$year = $now->format( "Y",true );
		}

		$yearstart = sprintf("%s-1-1",$year);
		$yearend = sprintf("%s-12-31",$year);

		$order = " ORDER BY a.holiday ";
		
		// Get holiday list
		$query = 'SELECT a.* ' .
				' FROM #__bs_coholiday AS a'
				.' WHERE a.holiday >= '.$db->Quote($yearstart).' AND a.holiday <= '.$db->Quote($yearend) .
				$order;
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		// If there is a database query error, throw a HTTP 500 and exit
		if ($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr() );
			return false;
		}
		return $rows;
	}

	function getOneData() {
		$db	= JFactory::getDBO();
		$app = JFactory::getApplication();
		$input = $app->input;
		
		$holid = $input->get( 'cid', array(0), 'array' );
		if( $holid[0] ) {
			// Get users list
			$query = 'SELECT a.* ' .
				' FROM #__bs_coholiday AS a' .
				' WHERE a.id='.$db->Quote($holid[0]);
			$db->setQuery($query);
			$row = $db->loadObject();
			// If there is a database query error, throw a HTTP 500 and exit
			if ($db->getErrorNum()) {
				JError::raiseError( 500, $db->stderr() );
				return false;
			}
		} else {
			$row = new stdClass();
			$row->holiday="";
			$row->holidayname="";
			$row->holiday_stat="0";
			$row->manual="1";
		} 
		return $row;
	}
	function newyear($data) {
		$app = JFactory::getApplication();
		$input = $app->input;
		$task = $input->getCmd('task');
		$db	= JFactory::getDBO();
		$my = JFactory::getUser();
		$date = JFactory::getDate();
		$time = $date->tosql();

		$query = 'SELECT MAX(a.holiday) AS year' .
				' FROM #__bs_coholiday AS a' .
				' WHERE 1 ';
		$db->setQuery($query);
		$year = $db->loadResult();
		if( strlen( $year ) > 4 ) {
			$year = substr( $year,0,4 ) ;
			$new_year = $year+1;
		} else {
			// Get the now year
			$config = JFactory::getConfig();
			$now = JFactory::getDate();
			$now->setTimeZone(new DateTimeZone($config->get('offset')));
			$year = null;
			$new_year = $now->format( "Y",true );
		}
		
		if( $year ) {
			$yearstart = sprintf("%s-1-1",$year);
			$yearend = sprintf("%s-12-31",$year);

			$order = " ORDER BY a.holiday ";
		
			// Get division list
			$query = 'SELECT a.* ' .
				' FROM #__bs_coholiday AS a'
				.' WHERE a.manual = 1 '
				.' AND a.holiday >= '.$db->Quote($yearstart).' AND a.holiday <= '.$db->Quote($yearend) .
				$order;
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			
			for( $i=0;$i<count($rows);$i++)  {
				$rows[$i]->holiday = $new_year.substr( $rows[$i]->holiday,4 );
				
				$query = "INSERT INTO #__bs_coholiday (`holiday`,`holidayname`,`holiday_stat`,`manual`,"
					."\n `modified`, `modified_by`, `created`, `createdby` )  "
					."\n VALUES ( ".$db->Quote($rows[$i]->holiday).",".$db->Quote($rows[$i]->holidayname).",0,1,"
					."\n ".$db->Quote($time).",".$db->Quote($my->id).",".$db->Quote($time).",".$db->Quote($my->id)
					.")";
				$db->setQuery($query);
				$db->execute();
				if ($db->getErrorNum()) {
					if ($db->getErrorNum() == 1062) {
						JError::raiseWarning( 0, JText::_('BSC_ERR_DUPLICATEHOLIDAY'));
					} else {
						JError::raiseError(500, $db->getErrorMsg() );
					}
				}
			}
		}
		$rows = HolidayHelper::makeholiday($new_year);
		for( $i=0;$i<count($rows);$i++)  {
			$query = "INSERT INTO #__bs_coholiday (`holiday`,`holidayname`,`holiday_stat`,`manual`,"
					."\n `modified`, `modified_by`, `created`, `createdby` )  "
					."\n VALUES ( ".$db->Quote($rows[$i]->holiday).",".$db->Quote($rows[$i]->holidayname).",0,0,"
					."\n ".$db->Quote($time).",".$db->Quote($my->id).",".$db->Quote($time).",".$db->Quote($my->id)
					.")";
			$db->setQuery($query);
			$db->execute();
			if ($db->getErrorNum()) {
				if ($db->getErrorNum() == 1062) {
					JError::raiseWarning( 0, JText::_('BSC_ERR_DUPLICATEHOLIDAY'));
				} else {
					JError::raiseError(500, $db->getErrorMsg() );
				}
			}
		}
		return $new_year;
	}
	function getYearall() {
		$db	= JFactory::getDBO();
		// Get year list
		$query = 'SELECT DISTINCT LEFT(a.holiday,4) AS value' .
				' FROM #__bs_coholiday AS a' .
				' WHERE 1 ORDER BY a.holiday DESC';
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		// If there is a database query error, throw a HTTP 500 and exit
		if ($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr() );
			return false;
		}
		foreach( $rows as $row ) {

			$row->text = $row->value;
		} 
		return $rows;
	}
}
?>