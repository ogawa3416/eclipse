<?php
/**
 * BsBooking component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		GROON project
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2011 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: form.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.3 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;
require_once(JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php');
?>
<form action="index.php?option=com_bsbooking" name="adminForm" id="adminForm" method="post">
<div class="col100">
<fieldset class="adminform">
    <legend><?php echo JText::_('COM_BSBOOKING_DETAILS')?></legend>
    <table class="admintable">
	<tr>
<?php /* 20101101 blogstone. change to JText::_() */ ?>
		<td width="20%" class="key"><label for="title"><?php echo JText::_('JGLOBAL_TITLE');?></label></td>
		<td><input type="text" name="title" class="inputbox" value="<?php echo $this->row->title?>" size="50" maxlength="100"/></td>
	</tr>
	<tr>
		<td width="20%" class="key">
            <span class="hasTip" title="<?php echo JText::_('COM_BSBOOKING_START_DAY')?>::<?php echo JText::_('COM_BSBOOKING_START_DAY_DESC')?>"><?php echo JText::_('COM_BSBOOKING_START_DAY')?></span>
        </td>
		<td><?php echo BsbookingHelper::getHourSelectList( $this->row->day_start, 'start_hour' );?> :
			<input type="text" name="start_minute" value="<?php echo BsbookingHelper::getMinute($this->row->day_start)?>" maxlength="2" size="3" style="float:none"/> 
			
		</td>
	</tr>
	<tr>
		<td width="20%" class="key">
            <span class="hasTip" title="<?php echo JText::_('COM_BSBOOKING_END_DAY')?>::<?php echo JText::_('COM_BSBOOKING_END_DAY_DESC')?>"><?php echo JText::_('COM_BSBOOKING_END_DAY')?></span>
        </td>
		<td><?php echo BsbookingHelper::getHourSelectList( $this->row->day_end, 'end_hour' );?> :
			<input type="text" name="end_minute" value="<?php echo BsbookingHelper::getMinute($this->row->day_end)?>" maxlength="2" size="3" style="float:none"/>  
		</td>
	</tr>
	<tr>
		<td width="20%" class="key">
            <span class="hasTip" title="<?php echo JText::_('COM_BSBOOKING_TIME_SPAN')?>::<?php echo JText::_('COM_BSBOOKING_TIME_SPAN_DESC')?>"><?php echo JText::_('COM_BSBOOKING_TIME_SPAN')?></span>
        </td>
		<td><?php echo BsbookingHelper::getTimeSpanSelectList($this->row->time_span)?></td>
	</tr>
	<tr>
		<td width="20%" class="key">
            <span class="hasTip" title="<?php echo JText::_('COM_BSBOOKING_TIME_FORMAT')?>::<?php echo JText::_('COM_BSBOOKING_TIME_FORMAT_DESC')?>"><?php echo JText::_('COM_BSBOOKING_TIME_FORMAT')?></span>
        </td>
		<td><?php echo BsbookingHelper::getTimeModeSelectList($this->row->time_format)?></td>
	</tr>
	<tr>
		<td width="20%" class="key">
            <span class="hasTip" title="<?php echo JText::_('COM_BSBOOKING_VIEW_DAYS')?>::<?php echo JText::_('COM_BSBOOKING_VIEW_DAYS_DESC')?>"><?php echo JText::_('COM_BSBOOKING_VIEW_DAYS')?></span>
        </td>
		<td><?php echo BsbookingHelper::getViewDaySelectList($this->row->view_days)?></td>
	</tr>
	<tr>
		<td width="20%" class="key">
            <span class="hasTip" title="<?php echo JText::_('COM_BSBOOKING_SHOW_SUMMARY')?>::<?php echo JText::_('COM_BSBOOKING_SHOW_SUMMARY_DESC')?>"><?php echo JText::_('COM_BSBOOKING_SHOW_SUMMARY')?></span>
        </td>
		<td>
		<fieldset class="radio">
		<?php echo JHTML::_('select.booleanlist', 'show_summary', '', $this->row->show_summary );?>
		</fieldset>
		</td>
	</tr>
    </table>
</fieldset>
</div>
<input type="hidden" name="cid[]" value="<?php echo $this->row->id?>" />
<input type="hidden" name="task" value="" />
</form>