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

$app = JFactory::getApplication();
$input = $app->input;
$task = $input->getCmd('task');
JHTML::script('administrator/components/com_bscore/assets/js/bscore.js');
?>
<script language="javascript" type="text/javascript">
<!--
/*
window.addEvent('domready', function() {
*/
jQuery(function() {
	document.formvalidator.setHandler('holiday',
		function (value) {
			return isValidDate(value,0);
	});
});
		
function setgood() {
	// TODO: Put setGood back
	return false;
}
Joomla.submitbutton = function(pressbutton)
{
	var form = document.adminForm;
	if (pressbutton == 'holiday.editcancel') {
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
	if( pressbutton == 'holiday.editnew' || pressbutton == 'holiday.editmod') {
		di=document.getElementsByName("holid") ;
		dd=document.getElementsByName("holiday") ;
		dn=document.getElementsByName("holidayname") ;
		ds=document.getElementsByName("holiday_stat") ;
		dm=document.getElementsByName("manual") ;
		var dr = [];
		dr['holid']=di[0].value ;
		dr['holiday']=dd[0].value ;
		dr['holidayname']=dn[0].value ;
		dr['holiday_stst']=ds[0].value ;
		dr['manual']=dm[0].value ;
        if (window.parent)
        {
            window.parent.updatedataFor(pressbutton,dr);
			window.parent.SqueezeBox.close();
        }
		return false;
	}
}
// -->
</script>
   <form action="index.php?option=com_bscore" method="post" name="adminForm" id="adminForm" onSubmit="return setgood();" class="form-validate">
	<fieldset class="adminform">
    <legend>

	<?php	if( $task == 'subnew' ) echo JText::_('BSC_HOLIDAY_NEW');
			else echo JText::_('BSC_HOLIDAY_EDIT');
	?>

    </legend>
   <table cellpadding="4" cellspacing="0" border="0" width="100%" class="admintable">

	<tbody>
		<tr>
			<td class="key">*<label for="holiday"><?php echo JText::_('BSC_HOLIDAY_DATE'); ?></label></td>
			<td>
			<?php 
				echo JHTML::_('calendar', $this->data->holiday, 'holiday', 'holiday',JText::_("%Y-%m-%d"), array('class'=>'inputbox required validate-holiday', 'size'=>'10',  'maxlength'=>'10'));
			?>
			</td>
		</tr>
		<tr>
			<td class="key">*<label for="holidayname"><?php echo JText::_('BSC_HOLIDAY_NAME'); ?></label></td>
			<td><input class="inputbox required" type="text" id="holidayname" name="holidayname" value="<?php echo $this->data->holidayname; ?>" size="100" maxlength="100" ></td>
		</tr>
	</tbody>
    </table>
	</fieldset>
	<br />
	&nbsp;&nbsp;&nbsp;&nbsp;* : <?php echo JText::_( 'BSC_MUST_ENTRYFIELDS' ); ?>
	<?php if($task == 'submod' ) $st = 'holiday.editmod'; else $st = 'holiday.editnew'; ?>
	<input class="button" type="button" onclick="Joomla.submitbutton('<?php echo $st ?>')" value="<?php echo JText::_('BSC_ENTRY_NEW') ?>" style="margin-left: 100px;width: 60px;">
	<input class="button" type="button" onclick="Joomla.submitbutton('holiday.editcancel')" value="<?php echo JText::_('BSC_ENTRY_CANCEL') ?>" data-dismiss="modal" >
	<input type="hidden" name="option" value="com_bscore" />
	<input type="hidden" id="holid" name="holid" value="<?php echo $this->data->id; ?>" />
	<input type="hidden" id="holiday_stat" name="holiday_stat" value="<?php echo $this->data->holiday_stat; ?>" />
	<input type="hidden" id="manual" name="manual" value="<?php echo $this->data->manual; ?>" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
   </form>

