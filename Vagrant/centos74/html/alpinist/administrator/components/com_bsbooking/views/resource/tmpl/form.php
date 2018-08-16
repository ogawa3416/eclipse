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
 * @version		$Id: form.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
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
<script language="javascript" type="text/javascript">
<!--
function setgood() {
	// TODO: Put setGood back
	return false;
}
Joomla.submitbutton = function(pressbutton)
{
	if( pressbutton != 'resource.cancel' ) {
		var form = document.adminForm;
		if (!document.formvalidator.isValid(form)) {
			alert( "<?php echo JText::_( "INPUT_DATA_ERROR" ); ?>");
			return false;
		}
		if( form.divcode.value == '0' ) {
			alert( "<?php echo JText::_( "NO_DIVISION_SELECTED" ); ?>");
			return false;
		}
	}
	Joomla.submitform(pressbutton);
	return false;
}
// -->
</script>
<form name="adminForm" id="adminForm" action="index.php?option=com_bsbooking" method="post" class="form-validate">
<table class="admintable">
    <tr>
        <td min-width="140px" class="key">ID</td>
        <td><?php echo $this->row->id?></td>
    </tr>
    <tr>
<?php /* 20101101 blogstone. change to JText::_() */ ?>
        <td width="140px" class="key"><?php echo JText::_('RESOURCE_NAME');?></td>
        <td><input type="text" name="title" value="<?php echo $this->row->title?>" class="inputbox required" size="50"/></td>
    </tr>
    <tr>
        <td width="140px" class="key"><?php echo JText::_('LOCATION');?></td>
        <td><input type="text" name="location" value="<?php echo $this->row->location?>" class="inputbox" size="50"/></td>
    </tr>
    <tr>
        <td width="140px" class="key"><?php echo JText::_('CONTROL_DIVISION');?></td>
        <td><?php  echo $this->alldivlist; ?></td>
    </tr>
    <tr>
        <td width="140px" class="key"><?php echo JText::_('CONTACT_TELEPHONE_NO');?></td>
        <td><input type="text" name="rphone" value="<?php echo $this->row->rphone?>" class="inputbox" size="14" maxlength="14"/></td>
    </tr>
    <tr>
        <td width="140px" class="key"><?php echo JText::_('NOTE');?></td>
        <td><input type="text" name="notes" value="<?php echo $this->row->notes?>" class="inputbox" size="50"/></td>
    </tr>
    <tr>
        <td width="140px" class="key">
            <span class="hasTip" title="<?php echo JText::_(com_bsbooking_ALLOW_MULTI)?>::<?php echo JText::_('COM_BSBOOKING_ALLOW_MULTI_DESC')?>"><?php echo JText::_('COM_BSBOOKING_ALLOW_MULTI')?></span>
        </td>
        <td><?php echo JHTML::_('select.booleanlist', 'allow_multi', '', $this->row->allow_multi );?></td>
    </tr>
    <tr>
<?php /* 20101101 blogstone. change to JText::_() */ ?>
        <td width="140px" class="key"><?php echo JText::_('MAX_PARTICIPANTS');?></td>
        <td><input type="text" name="max_participants" value="<?php echo $this->row->max_participants?>" class="inputbox" size="12" maxlength="12"/></td>
    </tr>
    <tr>
        <td width="140px" class="key">
            <span class="hasTip" title="<?php echo JText::_('COM_BSBOOKING_MINIMUM_NOTICE')?>::<?php echo JText::_('COM_BSBOOKING_MINIMUM_NOTICE_DESC')?>"><?php echo JText::_('COM_BSBOOKING_MINIMUM_NOTICE')?></span>    
        </td>
        <td><input type="text" name="min_notice_time" value="<?php echo $this->row->min_notice_time?>" class="inputbox required" size="12" maxlength="5"/></td>
    </tr>
    <tr>
        <td width="140px" class="key">
            <span class="hasTip" title="<?php echo JText::_('COM_BSBOOKING_MAXIMUM_NOTICE')?>::<?php echo JText::_('COM_BSBOOKING_MAXIMUM_NOTICE_DESC')?>"><?php echo JText::_('COM_BSBOOKING_MAXIMUM_NOTICE')?></span>
        </td>
        <td><input type="text" name="max_notice_time" value="<?php echo $this->row->max_notice_time?>" class="inputbox required" size="12" maxlength="5"/></td>
    </tr>
    <tr>
        <td width="140px" class="key">
            <span class="hasTip" title="<?php echo JText::_('COM_BSBOOKING_MINIMUM_RESERVATION_LENGTH')?>::<?php echo JText::_('COM_BSBOOKING_MINIMUM_RESERVATION_LENGTH_DESC')?>"><?php echo JText::_('COM_BSBOOKING_MINIMUM_RESERVATION_LENGTH')?></span>
        </td>
        <td><input type="text" name="min_res" value="<?php echo $this->row->min_res?>" class="inputbox required" size="12" maxlength="12"/></td>
    </tr>
    <tr>
        <td width="140px" class="key">
            <span class="hasTip" title="<?php echo JText::_('COM_BSBOOKING_MAXIMUM_RESERVATION_LENGTH')?>::<?php echo JText::_('COM_BSBOOKING_MAXIMUM_RESERVATION_LENGTH_DESC')?>"><?php echo JText::_('COM_BSBOOKING_MAXIMUM_RESERVATION_LENGTH')?></span>
        </td>
        <td><input type="text" name="max_res" value="<?php echo $this->row->max_res?>" class="inputbox required" size="12" maxlength="12"/></td>
    </tr>    
    <tr>
<?php /* 20101101 blogstone. change to JText::_() */ ?>
        <td width="140px" class="key"><?php echo JText::_('SCHEDULE_NAME');?></td>
        <td><?php echo BsbookingHelper::getScheduleSelectList($this->schedule_id, 'schedule_id')?></td>
    </tr>
    <tr>
        <td width="140px" class="key">
            <span class="hasTip" title="<?php echo JText::_('RSV_ALERT')?>::<?php echo JText::_('RSV_ALERT_DESC')?>"><?php echo JText::_('RSV_ALERT')?></span>
        </td>
        <td><input type="text" name="alert_msg" value="<?php echo $this->row->alert_msg?>" class="inputbox" size="50"/></td>
    </tr>
    <tr>
        <td width="140px" class="key"><?php echo JText::_('JPUBLISHED');?></td>
        <td><?php echo JHTML::_('select.booleanlist', 'published', '', $this->row->published );?></td>
    </tr>
</table>
<div class="fieldsetlegend">
<fieldset class="adminform">
	<legend><?php echo JText::_( 'DIVISION_ASSIGNMENT' ); ?></legend>
	<script type="text/javascript">
<!--
	function allselections() {
		var e = document.getElementById('selections');
		e.disabled = true;
		var i = 0;
		var n = e.options.length;
		for (i = 0; i < n; i++) {
			e.options[i].disabled = true;
			e.options[i].selected = true;
		}
	}
	function disableselections() {
		var e = document.getElementById('selections');
		e.disabled = true;
		var i = 0;
		var n = e.options.length;
		for (i = 0; i < n; i++) {
			e.options[i].disabled = true;
			e.options[i].selected = false;
		}
	}
	function enableselections() {
		var e = document.getElementById('selections');
		e.disabled = false;
		var i = 0;
		var n = e.options.length;
		for (i = 0; i < n; i++) {
			e.options[i].disabled = false;
		}
	}
// -->
	</script>
	<table class="admintable" cellspacing="1">
		<tr>
			<td class="key" width="130px" >
			<div style="margin-bottom:7px;"><?php echo JText::_( 'RESERVATION_CAN_BE' ); ?>:</div>
			<?php $selectlist = BsbookingHelper::getDivisionSelectList($this->row->id,$pages); ?>
			</td>
			<td>
				<fieldset class="radio">
				<div class="controls">
				<?php if ($pages == 'all') { ?>
				<label for="menus-all" class="radio"><input id="menus-all" type="radio" name="menus" value="all" onclick="allselections();" checked="checked" /><?php echo JText::_('JALL'); ?></label>
				<label for="menus-none" class="radio"><input id="menus-none" type="radio" name="menus" value="none" onclick="disableselections();" /><?php echo JText::_('JNONE'); ?></label>
				<label for="menus-select" class="radio"><input id="menus-select" type="radio" name="menus" value="select" onclick="enableselections();" /><?php echo JText::_('COM_BSBOOKING_SELECT_FMLIST'); ?></label>
				<?php } elseif ($pages == 'none') { ?>
				<label for="menus-all" class="radio"><input id="menus-all" type="radio" name="menus" value="all" onclick="allselections();" /><?php echo JText::_('JALL'); ?></label>
				<label for="menus-none" class="radio"><input id="menus-none" type="radio" name="menus" value="none" onclick="disableselections();" checked="checked" /><?php echo JText::_('JNONE'); ?></label>
				<label for="menus-select" class="radio"><input id="menus-select" type="radio" name="menus" value="select" onclick="enableselections();" /><?php echo JText::_('COM_BSBOOKING_SELECT_FMLIST'); ?></label>
				<?php } else { ?>
				<label for="menus-all" class="radio"><input id="menus-all" type="radio" name="menus" value="all" onclick="allselections();" /><?php echo JText::_('JALL'); ?></label>
				<label for="menus-none" class="radio"><input id="menus-none" type="radio" name="menus" value="none" onclick="disableselections();" /><?php echo JText::_('JNONE'); ?></label>
				<label for="menus-select" class="radio"><input id="menus-select" type="radio" name="menus" value="select" onclick="enableselections();" checked="checked" /><?php echo JText::_('COM_BSBOOKING_SELECT_FMLIST'); ?></label>
				<?php } ?>
				</div>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td valign="top" class="key" width="130px">
				<?php echo JText::_( 'DIVISION_SELECTION' ); ?>:
			</td>
			<td>
				<?php  echo $selectlist;?>
			</td>	
		</tr>
	</table>

		<?php if ($pages == 'all') { ?>
		<script type="text/javascript">allselections();</script>
		<?php } elseif ($pages == 'none') { ?>
		<script type="text/javascript">disableselections();</script>
		<?php } else { ?>
		<?php } ?>

</fieldset>
</div>
<input type="hidden" name="cid[]" value="<?php echo $this->row->id?>" />
<input type="hidden" name="task" value="" />
</form>