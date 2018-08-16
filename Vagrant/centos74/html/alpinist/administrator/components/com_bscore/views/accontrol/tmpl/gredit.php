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
 * @version		$Id: gredit.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.0 $
 **/

// no direct access
defined('_JEXEC') or die;
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
		if( window.parent && window.parent.SqueezeBox ) {
			window.parent.SqueezeBox.close();
			if( window.parent && window.parent.SqueezeBox ) {
				window.parent.location.href=window.parent.location.href;
			}
		} else {
			window.parent.location.href=window.parent.location.href;
		}
		return false;
	}

	if (!document.formvalidator.isValid(form)) {
		alert( "<?php echo JText::_('BSC_ERR_SETTING'); ?>");
		return false;
	}
	var div = document.getElementById('divkey');
	if( div.value == '0' ) {
		alert( "<?php echo JText::_('BSC_ERR_SETTING'); ?>");
		return false;
	}

	if( pressbutton == 'accontrol.grnew' ) {
		dc=document.getElementById("com_group") ;
		dk=document.getElementById("divkey") ;
		var dr = [];
		dr['com_group']=dc.value ;
		dr['divkey']=dk.value ;
        if (window.parent)
        {
            window.parent.updategroupFor(pressbutton,dr);
			window.parent.SqueezeBox.close();   
        }
		return false;
	}
	return false;
}
// -->
</script>
<br />
<form action="index.php?option=com_bscore" method="post" name="adminForm" id="adminForm"  class="form-validate">
	<fieldset class="adminform">
    <legend>
		<?php echo JText::_('BSC_ACC_GROUP_ADD');?>
    </legend>

	<table class="admintable" cellspacing="1">
		<tr>
			<td valign="top" class="key" style="width:200px;"><?php echo JText::_('BSC_ACCESS_CONTROLGROUP'); ?>: </td>
			<td>
				<?php echo $this->accgrlist; ?>
			</td>
		</tr>
		<tr>
			<td valign="top" class="key"><?php echo JText::_('BSC_DIVKEYNAME'); ?>:</td>
			<td>
				<?php  echo $this->alldivlist; ?>
			</td>	
		</tr>
	</table>
	</fieldset>
	
	<input class="button" type="button" onclick="Joomla.submitbutton('accontrol.grnew')" value="<?php echo JText::_('BSC_ENTRY_NEW')?>" style="margin-left: 100px;width: 60px;">
	<input class="button" type="button" onclick="Joomla.submitbutton('accontrol.editcancel')" value="<?php echo JText::_('BSC_ENTRY_CANCEL') ?>" >

	<input type="hidden" name="option" value="com_bscore" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>

