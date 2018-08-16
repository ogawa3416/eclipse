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
 * @version		$Id: helper.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;

class BsbookingHelper
{
	public static function minuteToTime ( $minuteCount )
	{
		$hour 	= floor($minuteCount / 60);
		$minute = $minuteCount % 60;
		return (($hour < 10)?'0':'').$hour.':'.(($minute < 10)?'0':'').$minute;
	}
	
	public static function getHourSelectList( $minuteCount, $tagName )
	{
		$hour 	= floor($minuteCount / 60);
		$options = array();		
		for ($i=0; $i <= 23; $i++)
		{
			$options[] = JHTML::_('select.option', $i, ($i <10)?'0'.$i:$i);
		}
		return JHTML::_('select.genericlist', $options, $tagName, null, 'value', 'text', $hour );
	}

	public static function getMinute( $minuteCount ) 
	{
		$minute = $minuteCount % 60;
		return ($minute<10)?'0'.$minute:$minute;
	}
	
	public static function getDayName ( $day )
	{
		$result = 'NA';
		switch ($day)
		{
			case 0 : $result = JText::_('Sunday'); break;
			case 1 : $result = JText::_('Monday'); break;
			case 2 : $result = JText::_('Tuesday'); break;
			case 3 : $result = JText::_('Wednesday'); break;
			case 4 : $result = JText::_('Thursday'); break;
			case 5 : $result = JText::_('Friday'); break;
			case 6 : $result = JText::_('Saturday'); break;
            case 7 : $result = JText::_('COM_BSBOOKING_CURRENT_DATE'); break;
		}
		return $result;
	}
	
	public static function getWeekDayStartSelectList( $selected )
	{
		$options = array();
		$options[] = JHTML::_('select.option', 0, JText::_('Sunday') );
		$options[] = JHTML::_('select.option', 1, JText::_('Monday') );
		$options[] = JHTML::_('select.option', 2, JText::_('Tuesday') );
		$options[] = JHTML::_('select.option', 3, JText::_('Wednesday') );
		$options[] = JHTML::_('select.option', 4, JText::_('Thursday') );
		$options[] = JHTML::_('select.option', 5, JText::_('Friday') );
		$options[] = JHTML::_('select.option', 6, JText::_('Saturday') );
        $options[] = JHTML::_('select.option', 7, JText::_('COM_BSBOOKING_CURRENT_DATE'));	
		
		return JHTML::_('select.genericlist', $options, 'weekday_start', null, 'value', 'text', $selected );
	}
	
	public static function getTimeSpanSelectList( $span )
	{
		$options = array();
		$options[] = JHTML::_('select.option', 15, JText::_('COM_BSBOOKING_15_minutes'));
		$options[] = JHTML::_('select.option', 30, JText::_('COM_BSBOOKING_30_minutes'));
		$options[] = JHTML::_('select.option', 60, JText::_('COM_BSBOOKING_1_hour'));
		$options[] = JHTML::_('select.option', 120, JText::_('COM_BSBOOKING_2_hours'));
/**** 20130601 ***
		$options[] = JHTML::_('select.option', 240, JText::_('COM_BSBOOKING_4_hours'));
		$options[] = JHTML::_('select.option', 360, JText::_('COM_BSBOOKING_6_hours'));
		$options[] = JHTML::_('select.option', 480, JText::_('COM_BSBOOKING_8_hours'));
		$options[] = JHTML::_('select.option', 720, JText::_('COM_BSBOOKING_12_hours'));
******************/
		return JHTML::_('select.genericlist', $options, 'time_span', null, 'value', 'text', $span );
		
	}
	
	public static function getTimeModeSelectList( $format )
	{
		$options = array();
		$options[] = JHTML::_('select.option', 12, '12');
		$options[] = JHTML::_('select.option', 24, '24');
		return JHTML::_('select.genericlist', $options, 'time_format', null, 'value', 'text', $format );
		 		
	}
	
	public static function getViewDaySelectList( $days )
	{
		$options = array();		
		for ($i=1; $i <= 7; $i++)
		{
			$options[] = JHTML::_('select.option', $i, $i);
		}
		return JHTML::_('select.genericlist', $options, 'view_days', null, 'value', 'text', $days );
	}
    
    
    public static function getScheduleSelectList( $current, $tag_name, $for_filter=false )
    {
        $query = "SELECT id, title FROM #__bs_schedules";
        $dbo =  JFactory::getDBO();
        $dbo->setQuery($query);
        $rows = $dbo->loadObjectList();
        $options = array();
        if ($for_filter) $options[] = JHTML::_('select.option', 0, JText::_('JALL'));
        foreach($rows as $row)
        {
            $options[] = JHTML::_('select.option', $row->id, $row->title);
        }
        $onchange = "";
        if ($for_filter) $onchange = 'onchange="this.form.submit();"'; 
        return JHTML::_('select.genericlist', $options, $tag_name, $onchange, 'value', 'text', $current );
    }
    
   	public static function quickiconButton( $link, $image, $text )
	{
		$mainframe = JFactory::getApplication();
		$lang		= JFactory::getLanguage();
		$template	= $mainframe->getTemplate();
		$path = 'administrator/components/com_bsbooking/assets/images/';
?>
		<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
			<div class="icon">
				<a href="<?php echo $link; ?>">
					<?php echo JHTML::_('image',  $path.$image, $text ); ?>
					<span><?php echo $text; ?></span></a>
			</div>
		</div>
<?php
	}
	
    public static function getDivisionSelectList( $resource,&$pages )
    {
        $dbo =  JFactory::getDBO();
        $query = "SELECT a.divcode `value`,a.divname `text`,b.can_edit FROM #__bs_division a "
				."\n LEFT JOIN  #__bs_reservation_division b ON  a.divcode = b.divcode AND b.resource_id = ".$dbo->Quote($resource)
				."\n WHERE a.div_stat = 1 "
				;
        $dbo->setQuery($query);
        $rows = $dbo->loadObjectList();
        $selections = array();
		$rowcnt = 0;
		$lookup = array();
        foreach($rows as $row) {
	        $selections[] = JHTML::_('select.option', $row->value, $row->text);
			if( $row->can_edit ) {
	            $lookup[] = $row->value;
				$rowcnt++;
			}
        }
		$dbo->setQuery('SELECT count(divcode) FROM #__bs_division WHERE div_stat = 1 ' );
		$allcnt = $dbo->loadResult();
		if( $rowcnt == 0 ) {
			$pages = 'none';
		} else if( $rowcnt == $allcnt ) {
			$pages = 'all';
		} else {
			$pages = '';
		}
		
        return JHTML::_('select.genericlist',   $selections, 'selections[]', 'class="inputbox" size="15" multiple="multiple"', 'value', 'text', $lookup, 'selections' );
    }
	public static function getAllListDiv()  {
		$db = JFactory::getDBO();
		$query = 'SELECT divcode `value`,divname_s `text` FROM #__bs_division  '
				.' WHERE div_stat = 1 ORDER BY divcode '
				;
		$db->setQuery( $query );
		return $db->loadObjectList();
	}
}
?>