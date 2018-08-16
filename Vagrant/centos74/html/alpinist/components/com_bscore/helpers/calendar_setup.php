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

defined('_JEXEC') or die;

class CalendarSetupHelper 
{
	static function getHolidaylist() {
		$db	= JFactory::getDBO();
		
		$config = JFactory::getConfig();
		$now = JFactory::getDate();
		$now->setTimeZone(new DateTimeZone($config->get('offset')));
		$year = $now->format( "Y",true );
		$yearstart = sprintf("%s-1-1",(intval($year)-1));
		$yearend = sprintf("%s-12-31",(intval($year)+1));
			
		// Get year list
		$query = 'SELECT a.holiday AS holiday' .
				' FROM #__bs_coholiday AS a' .
				' WHERE a.holiday >= '.$db->Quote($yearstart).' AND a.holiday <= '.$db->Quote($yearend) .
				' ORDER BY a.holiday';
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		// If there is a database query error, throw a HTTP 500 and exit
		if ($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr() );
			return false;
		}
		return $rows;
	}
}
?>
<style type="text/css">
	tbody td.weekend { background-color: #e6e6fa; }
	tbody td.calspecial { background-color: #ddd;}
	.calendar tbody td.calspecial { color: #FF0000; }
</style>

<script type="text/javascript">
  function dateIsSpecial(year, month, day) {
	var SPECIAL_DAYS = new Array;
	<?php 
		$hdlist = CalendarSetupHelper::getHolidaylist();
		$yi = -1;
		$mi = -1;
		foreach( $hdlist as $val) {
			$dt = explode('-',$val->holiday);
			if( $dt[0] != $yi || $dt[1] != $mi ) {
				if( $yi != -1 ) echo " ];\n";
				if( $dt[0] != $yi ) {
					echo "         SPECIAL_DAYS[".$dt[0]."] = [ 0,1,2,3,4,5,6,7,8,9,10,11 ];\n";
				}
				echo "        SPECIAL_DAYS[".$dt[0]."][".(intval($dt[1])-1)."] = [ ".intval($dt[2]);
			} else {
				echo ", ".intval($dt[2]);
			}
			$yi = $dt[0];
			$mi = $dt[1];
		}
		if( count($hdlist) > 0 ) echo " ];\n";
	?>
	if( year in SPECIAL_DAYS  ) {}
	else {
		return false;
	}
	var m = SPECIAL_DAYS[year][month];
	if (!m) return false;
	for (var i in m) if (m[i] == day) return true;
	return false;
  };

  function ourDateStatusFunc(dt, y, m, d) {
    if (dateIsSpecial(y, m, d))
      return "calspecial";
    else
      return ""; // other dates are enabled
      // return true if you want to disable other dates
  };
</script>