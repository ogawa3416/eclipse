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

// no direct access
defined('_JEXEC') or die;

class HolidayHelper
{
	public static function makeholiday($year)
	{
		$holiday = array();
		$hdpos = 0;
		// ----- 元日 -----
		$holiday[$hdpos] = new stdClass;
		$holiday[$hdpos]->holiday = date('Y-m-d',mktime( 0, 0, 0, 1, 1, $year ));
		$holiday[$hdpos]->holidayname = JText::_('BSC_HOLIDAY_1_1');
		// ----- 成人の日　（１月第二月曜）-----
		$tmpts = mktime(0,0,0,1,14,$year);
		$i = date("w", $tmpts); // 1/14の曜日を取得
		if($i == 0 ) $i = 6;
		else $i = $i - 1;
	
		$tmp = date("j", strtotime("-$i day",$tmpts ));
		$hdpos++;
		$holiday[$hdpos] = new stdClass;
		$holiday[$hdpos]->holiday = date('Y-m-d',mktime( 0, 0, 0, 1, $tmp, $year ));
		$holiday[$hdpos]->holidayname = JText::_('BSC_HOLIDAY_1_14');
	
		// ----- 建国記念の日
		$hdpos++;
		$holiday[$hdpos] = new stdClass;
		$holiday[$hdpos]->holiday = date('Y-m-d',mktime( 0, 0, 0, 2, 11, $year ));
		$holiday[$hdpos]->holidayname = JText::_('BSC_HOLIDAY_2_11');
		// ----- 春分の日　（春分日） // data wikipedia
		switch ($year % 4){
		case 0:
			if( 1900 <= $year && $year <= 1956 ) $tmp = 21;
			else if( 1960 <= $year && $year <= 2088 ) $tmp = 20;
			else if( 2092 <= $year && $year <= 2096 ) $tmp = 19;
			break;
		case 1:
			if( 1901 <= $year && $year <= 1989 ) $tmp = 21;
			else if( 1993 <= $year && $year <= 2097 ) $tmp = 20;
			break;
		case 2:
			if( 1902 <= $year && $y <= 2022 ) $tmp = 21;
			else if( 2026 <= $year && $year <= 2098 ) $tmp = 20;
			break;
		case 3:
			if( 1903 <= $year && $year <= 1923 ) $tmp = 22;
			else if( 1927 <= $year && $year <= 2055 ) $tmp = 21;
			else if( 2059 <= $year && $year <= 2099 ) $tmp = 20;
			break;
		}
		$hdpos++;
		$holiday[$hdpos] = new stdClass;
		$holiday[$hdpos]->holiday = date('Y-m-d',mktime( 0, 0, 0, 3, $tmp, $year ));
		$holiday[$hdpos]->holidayname = JText::_('BSC_HOLIDAY_3_21');

		// ----- 昭和の日 （４月２９日）
		$hdpos++;
		$holiday[$hdpos] = new stdClass;
		$holiday[$hdpos]->holiday = date('Y-m-d',mktime( 0, 0, 0, 4, 29, $year ));
		$holiday[$hdpos]->holidayname = JText::_('BSC_HOLIDAY_4_29');
		// ----- 憲法記念日 （５月３日）
		$hdpos++;
		$holiday[$hdpos] = new stdClass;
		$holiday[$hdpos]->holiday = date('Y-m-d',mktime( 0, 0, 0, 5, 3, $year ));
		$holiday[$hdpos]->holidayname = JText::_('BSC_HOLIDAY_5_3');
		// ----- みどりの日 （５月４日）
		$hdpos++;
		$holiday[$hdpos] = new stdClass;
		$holiday[$hdpos]->holiday = date('Y-m-d',mktime( 0, 0, 0, 5, 4, $year ));
		$holiday[$hdpos]->holidayname = JText::_('BSC_HOLIDAY_5_4');
		// ----- こどもの日 （５月５日）
		$hdpos++;
		$holiday[$hdpos] = new stdClass;
		$holiday[$hdpos]->holiday = date('Y-m-d',mktime( 0, 0, 0, 5, 5, $year ));
		$holiday[$hdpos]->holidayname = JText::_('BSC_HOLIDAY_5_5');
		// ----- 海の日 （７月第三月曜日）
		$tmpts = mktime(0,0,0,7,21,$year);
		$i = date("w", $tmpts); // 7/21の曜日を取得
		if($i == 0 ) $i = 6;
		else $i = $i - 1;
		$tmp = date("j", strtotime("-$i day",$tmpts ));
		$hdpos++;
		$holiday[$hdpos] = new stdClass;
		$holiday[$hdpos]->holiday = date('Y-m-d',mktime( 0, 0, 0, 7, $tmp, $year ));
		$holiday[$hdpos]->holidayname = JText::_('BSC_HOLIDAY_7_21');
		// ----- 山の日 （８月１１日）
		$hdpos++;
		$holiday[$hdpos] = new stdClass;
		$holiday[$hdpos]->holiday = date('Y-m-d',mktime( 0, 0, 0, 8, 11, $year ));
		$holiday[$hdpos]->holidayname = JText::_('BSC_HOLIDAY_8_11');
		// ----- 敬老の日 （９月第三月曜日）
		$tmpts = mktime(0,0,0,9,21,$year);
		$i = date("w", $tmpts); // 9/21の曜日を取得
		if($i == 0 ) $i = 6;
		else $i = $i - 1;
		$tmp = date("j", strtotime("-$i day",$tmpts ));
		$hdpos++;
		$holiday[$hdpos] = new stdClass;
		$holiday[$hdpos]->holiday = date('Y-m-d',mktime( 0, 0, 0, 9, $tmp, $year));
		$holiday[$hdpos]->holidayname = JText::_('BSC_HOLIDAY_9_21');
 
		// ----- 秋分の日 （秋分日） // data wikipedia
		switch ($year % 4){
		case 0:
			if( 1900 <= $year && $year <= 2008 ) $tmp = 23;
			else if( 2012 <= $year && $year <= 2096 ) $tmp = 22;
			break;
		case 1:
			if( 1901 <= $year && $year <= 1917 ) $tmp = 24;
			else if( 1921 <= $year && $year <= 2041 ) $tmp = 23;
			else if( 2045 <= $year && $year <= 2097 ) $tmp = 22;
			break;
		case 2:
			if( 1902 <= $year && $year <= 1946 ) $tmp = 24;
			else if( 1950 <= $y && $year <= 2074 ) $tmp = 23;
			else if( 2078 <= $y && $year <= 2098 ) $tmp = 22;
			break;
 
		case 3:
			if( 1903 <= $year && $year <= 1979 ) $tmp = 24;
			else if( 1983 <= $year && $year <= 2099 ) $tmp = 23;
			break;
		}
		$hdpos++;
		$holiday[$hdpos] = new stdClass;
		$holiday[$hdpos]->holiday = date('Y-m-d',mktime( 0, 0, 0, 9, $tmp, $year ));
		$holiday[$hdpos]->holidayname = JText::_('BSC_HOLIDAY_9_23');
		// ----- 体育の日 （１０月第二月曜日）
		$tmpts = mktime(0,0,0,10,14,$year);
		$i = date("w", $tmpts); // 10/14の曜日を取得
		if($i == 0 ) $i = 6;
		else $i = $i - 1;
		$tmp = date("j", strtotime("-$i day",$tmpts ));
		$hdpos++;
		$holiday[$hdpos] = new stdClass;
		$holiday[$hdpos]->holiday = date('Y-m-d',mktime( 0, 0, 0, 10, $tmp, $year));
		$holiday[$hdpos]->holidayname = JText::_('BSC_HOLIDAY_10_14');
		// ----- 文化の日 （１１月３日）
		$hdpos++;
		$holiday[$hdpos] = new stdClass;
		$holiday[$hdpos]->holiday = date('Y-m-d',mktime( 0, 0, 0, 11, 3, $year ));
		$holiday[$hdpos]->holidayname = JText::_('BSC_HOLIDAY_11_3');
		// ----- 勤労感謝の日 （１１月２３日）
		$hdpos++;
		$holiday[$hdpos] = new stdClass;
		$holiday[$hdpos]->holiday = date('Y-m-d',mktime( 0, 0, 0, 11, 23, $year ));
		$holiday[$hdpos]->holidayname = JText::_('BSC_HOLIDAY_11_23');
		// ----- 天皇誕生日 （１２月２３日）
		$hdpos++;
		$holiday[$hdpos] = new stdClass;
		$holiday[$hdpos]->holiday = date('Y-m-d',mktime( 0, 0, 0, 12, 23, $year ));
		$holiday[$hdpos]->holidayname = JText::_('BSC_HOLIDAY_12_23');

		// --- 国民の休日
		$chkdate = false;
		switch ($year){
		case 2009:
			$chkdate = date('Y-m-d',mktime( 0, 0, 0, 9, 22, 2009));
			break;
		case 2015:
			$chkdate = date('Y-m-d',mktime( 0, 0, 0, 9, 22, 2015));
			break;
		case 2026:
			$chkdate = date('Y-m-d',mktime( 0, 0, 0, 9, 22, 2026));
			break;
		case 2032:
			$chkdate = date('Y-m-d',mktime( 0, 0, 0, 9, 21, 2032));
			break;
		case 2037:
			$chkdate = date('Y-m-d',mktime( 0, 0, 0, 9, 22, 2037));
			break;
		case 2043:
			$chkdate = date('Y-m-d',mktime( 0, 0, 0, 9, 22, 2043));
			break;
		case 2049:
			$chkdate = date('Y-m-d',mktime( 0, 0, 0, 9, 21, 2049));
			break;
		}
		if( $chkdate ) {
			$hdpos++;
			$holiday[$hdpos] = new stdClass;
			$holiday[$hdpos]->holiday = $chkdate;
			$holiday[$hdpos]->holidayname = JText::_('BSC_HOLIDAY_NATIONAL');
		}
 
		// --- 振替休日
		$cnt_holiday = count($holiday);
		for( $i=0;$i<$cnt_holiday;$i++ ) {
			$tgtime = strtotime( $holiday[$i]->holiday );
			$week = date("w", $tgtime);
			if( $week == 0 ) {
				$tgtime = $tgtime + 86400;
				$chkdate = date("Y-m-d",$tgtime);
				$chkok = true;
				for( $j=0;$j<$cnt_holiday;$j++ ) {
					if( $holiday[$j]->holiday == $chkdate ) {
						$chkok = false;
						break;
					}
				}
				if( $chkok ) {
					$hdpos++;
					$holiday[$hdpos] = new stdClass;
					$holiday[$hdpos]->holiday = $chkdate;
					$holiday[$hdpos]->holidayname = JText::_('BSC_HOLIDAY_FURIKAE');
				}
			}
		}
		return $holiday;
	}
}
