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
 * @version		$Id: default.php  BsAlpinist ver.2.5.0 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;
require_once(JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php');

$user =  JFactory::getUser();

?>
<form action="index.php?option=com_bsbooking" name="adminForm" id="adminForm" method="post">
    <?php if(version_compare(JVERSION, '3.0', 'ge')) : ?>
	<div class="btn-group pull-right hidden-phone">
		<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
		<?php echo $this->pagination->getLimitBox(); ?>
	</div>
	<?php endif; ?>
<table>
	<tr>
		<td><?php echo JText::_('COM_BSBOOKING_SEARCH');?></td><td><input type="text" name="search" size="50" value="<?php echo $this->searchword;?>" onblur="this.form.submit();"/></td>
	</tr>
</table>
<table class="adminlist">
<thead>
	<tr>
<?php /* 20101101 blogstone. change to Num -> No */ ?>
		<th width="5">No</th>
		<th width="20"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
		<th class="title left"><?php echo JText::_('JGLOBAL_TITLE');?></th>
<?php /* 20101101 blogstone. change to JText::_() */ ?>
		<th class="left"><?php echo JText::_('COM_BSBOOKING_START_DAY');?></th>
		<th class="left"><?php echo JText::_('COM_BSBOOKING_END_DAY');?></th>
		<th class="left"><?php echo JText::_('COM_BSBOOKING_TIME_SPAN');?></th>
		<th class="left"><?php echo JText::_('COM_BSBOOKING_VIEW_DAYS');?></th>
        <th class="left"><?php echo JText::_('RESOURCE_COUNT');?></th>
		<th class="left"><?php echo JText::_('COM_BSBOOKING_CHECKED_OUT');?></th>
	</tr>
</thead>
<tbody>
<?php 
$k = 0;
$i = 0;
foreach ($this->schedules as $schedule):
	$link = JRoute::_( 'index.php?option=com_bsbooking&task=schedule.edit&cid[]='. $schedule->id );
	$checkbox = JHTML::_('grid.checkedout', $schedule, $i );
	$checked_out_by =  JFactory::getUser($schedule->checked_out);
?>
	<tr>
		<td><?php echo $this->pagination->getRowOffset($i);?></td>
		<td align="center"><?php echo $checkbox?></td>
		<td><?php
//				if (  JTable::isCheckedOut($user->get ('id'), $schedule->checked_out ) ) 
				$table = JTable::getInstance('Schedule', 'Table');
				if( $table->isCheckedOut($user->get ('id')) ) 
				{
					echo $row->title;
				} else {
			?>
				<span class="editlinktip hasTip" title="<?php echo JText::_('COM_BSBOOKING_EDIT_CONTENT');?>::<?php echo $schedule->title; ?>">
					<a href="<?php echo $link; ?>">
					<?php echo $schedule->title; ?></a></span>
				<?php
				}
			?>
		</td>
		<td><?php echo BsbookingHelper::minuteToTime( $schedule->day_start )?></td>
		<td><?php echo BsbookingHelper::minuteToTime( $schedule->day_end )?></td>
		<td><?php echo $schedule->time_span?></td>
		<td><?php echo $schedule->view_days?></td>
        <td><?php echo $schedule->resource_count?></td>
		<td><?php echo $checked_out_by->name?></td> 
	</tr>
<?php 
$i++;
$k = 1 - $k;
endforeach
?>	
</tbody>
<tfoot>
	<tr><td colspan="10"><?php echo $this->pagination->getListFooter();?></td></tr>
</tfoot>
</table>
<input type="hidden" name="task" value="schedules.display" />
<input type="hidden" name="boxchecked" value="0" /> 
</form>