<?php
/**
 * BlogStone component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		GROON UGMS
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2010 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: uedit.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.0 $
 **/

// no direct access
defined('_JEXEC') or die;
global $comcfg;

$app = JFactory::getApplication();
$input = $app->input;
$task = $input->getCmd('task');
?>
<script language="javascript" type="text/javascript">
<!--

function setgood() {
	// TODO: Put setGood back
	return false;
}
Joomla.submitbutton = function(pressbutton)
{
	var form = document.adminForm;
	if (pressbutton == 'accontrol.editcancel') {
		window.parent.SqueezeBox.close();
		return false;
	}

	var dk=document.getElementById("divkey") ;
	var e = document.getElementById('selections');
	var dr = "";
	var m = 0;
	var n = e.options.length;
	for (i = 0; i < n; i++) {
		if( e.options[i].selected == true ) {
			dr += e.options[i].value+"/";
		}
	}
	if( dr.length ) dr = "/"+dr;
	if( pressbutton == 'accontrol.usernew' || pressbutton == 'accontrol.useradd') {
        if (window.parent)
        {
            window.parent.updateuserFor(pressbutton,dk.value,dr);
			window.parent.SqueezeBox.close();  
        }
		return false;
	}
	return false;
}
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
<br />
<form action="index.php?option=com_bscore" method="post" name="adminForm" id="adminForm" >
	<fieldset class="adminform">
    <legend>
		<?php echo JText::_('BSC_USER_ACCEDIT_INFO');?>
    </legend>

	<table class="admintable" cellspacing="1">
		<tr>
			<td valign="top" class="key"><?php echo JText::_('BSC_DIVKEYNAME'); ?>: </td>
			<td>
				<input type="hidden" id="divkey" name="divkey" value="<?php echo $this->data->divkey?>" >
				<?php echo $this->data->divname; ?>
			</td>
		</tr>
		<tr>
			<td valign="top" class="key"><?php echo JText::_('BSC_DIVISION_SELECTION'); ?>: </td>
			<td>
				<input type="hidden" id="divkey" name="divkey" value="<?php echo $this->data->divkey?>" >
				<?php echo $this->alldivlist; ?>
			</td>
		</tr>
		<tr>
			<td valign="top" class="key">
			<?php echo JText::_('BSC_ACCESS_CAN_BE'); ?>:
			</td>
			<td>

				<?php if ($this->data->userpages == 'all') { ?>
				<label for="menus-all"><input id="menus-all" type="radio" name="menus" value="all" onclick="allselections();" checked="checked" /><?php echo JText::_('BSC_ALL'); ?></label>
				<label for="menus-none"><input id="menus-none" type="radio" name="menus" value="none" onclick="disableselections();" /><?php echo JText::_('BSC_NONE'); ?></label>
				<label for="menus-select"><input id="menus-select" type="radio" name="menus" value="select" onclick="enableselections();" /><?php echo JText::_('BSC_SELECT_FROM_LIST'); ?></label>
				<?php } elseif ($this->data->userpages == 'none') { ?>
				<label for="menus-all"><input id="menus-all" type="radio" name="menus" value="all" onclick="allselections();" /><?php echo JText::_('BSC_ALL'); ?></label>
				<label for="menus-none"><input id="menus-none" type="radio" name="menus" value="none" onclick="disableselections();" checked="checked" /><?php echo JText::_('BSC_NONE'); ?></label>
				<label for="menus-select"><input id="menus-select" type="radio" name="menus" value="select" onclick="enableselections();" /><?php echo JText::_('BSC_SELECT_FROM_LIST'); ?></label>
				<?php } else { ?>
				<label for="menus-all"><input id="menus-all" type="radio" name="menus" value="all" onclick="allselections();" /><?php echo JText::_('BSC_ALL'); ?></label>
				<label for="menus-none"><input id="menus-none" type="radio" name="menus" value="none" onclick="disableselections();" /><?php echo JText::_('BSC_NONE'); ?></label>
				<label for="menus-select"><input id="menus-select" type="radio" name="menus" value="select" onclick="enableselections();" checked="checked" /><?php echo JText::_('BSC_SELECT_FROM_LIST'); ?></label>
				<?php } ?>

			</td>
		</tr>
		<tr>
			<td valign="top" class="key">
				<?php echo JText::_( 'BSC_USER_SELECTION'); ?>:
			</td>
			<td>
				<?php  echo $this->accuserlist; ?>
			</td>	
		</tr>
	</table>

		<?php if ($this->data->userpages == 'all') { ?>
		<script type="text/javascript">allselections();</script>
		<?php } elseif ($this->data->userpages == 'none') { ?>
		<script type="text/javascript">disableselections();</script>
		<?php } else { ?>
		<?php } ?>


	</fieldset>
	
	<input class="button" type="button" onclick="Joomla.submitbutton('accontrol.usernew')" value="<?php echo $this->data->divkey?JText::_('BSC_ENTRY_UPDATE'):JText::_('BSC_ENTRY_NEW') ?>" style="margin-left: 100px;width: 60px;">
	<input class="button" type="button" onclick="Joomla.submitbutton('accontrol.useradd')" value="<?php echo JText::_('BSC_ENTRY_ADD') ?>" style="width: 60px;">
	<input class="button" type="button" onclick="Joomla.submitbutton('accontrol.editcancel')" value="<?php echo JText::_('BSC_ENTRY_CANCEL') ?>" >

	<input type="hidden" name="option" value="com_bscore" />
	<input type="hidden" name="tmpl" value="component" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>

