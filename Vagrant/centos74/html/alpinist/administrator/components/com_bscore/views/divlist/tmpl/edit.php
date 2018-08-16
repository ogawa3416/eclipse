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
 * @copyright	Copyright (C) 2009-2010 GROON Project. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: edit.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
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
/*
window.addEvent('domready', function() {
*/
jQuery(function() {
	document.formvalidator.setHandler('divcode', function (value) {
		regex=/[^0-9\-()A-Za-z]/;
		return !regex.test(value);});});
/*
window.addEvent('domready', function() {
*/
jQuery(function() {
	document.formvalidator.setHandler('divzip', function (value) {
		regex=/[^0-9\-()]/;
		return !regex.test(value);});});
/*
window.addEvent('domready', function() {
*/
jQuery(function() {
	document.formvalidator.setHandler('divtel', function (value) {
		regex=/[^0-9\-()]/;
		return !regex.test(value);});});
		
function setgood() {
	// TODO: Put setGood back
	return false;
}
Joomla.submitbutton = function(pressbutton)
{
	var form = document.adminForm;
	if (pressbutton == 'divlist.editcancel') {
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
	if( pressbutton == 'divlist.editnew' || pressbutton == 'divlist.editmod') {
		dc=document.getElementsByName("divcode") ;
		ds=document.getElementsByName("div_stat") ;
		dn=document.getElementsByName("divname") ;
		dns=document.getElementsByName("divname_s") ;
		da=document.getElementsByName("divaddr") ;
		dz=document.getElementsByName("divzip") ;
		dt=document.getElementsByName("divtel") ;
		co=document.getElementsByName("company") ;
		tml=document.getElementsByName("divtmpl") ;
		var dr = [];
		dr['divcode']=dc[0].value ;
		dr['divstat']=ds[0].value ;
		dr['divname']=dn[0].value ;
		dr['divname_s']=dns[0].value ;
		dr['divaddr']=da[0].value ;
		dr['divzip']=dz[0].value ;
		dr['divtel']=dt[0].value ;
		dr['company']=co[0].value ;
		dr['divtmpl']=tml[0].value ;
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
   <form action="index.php?option=com_bscore" method="post" name="adminForm" id="adminForm"  class="form-validate">
	<fieldset class="adminform">
    <legend>

	<?php	if( $task == 'subnew' ) echo JText::_('BSC_DIVISION_NEW');
			else echo JText::_('BSC_DIVISION_EDIT');
	?>

    </legend>
   <table cellpadding="4" cellspacing="0" border="0" width="100%" class="admintable">

	<tbody>
		<tr>
			<td class="key">*<label for="divcode"><?php echo JText::_('BSC_DIVISION_CODE'); ?></label></td>
			<td><?php 
			if( $task == 'subnew' ) { ?>
			<input class="inputbox required validate-divcode" type="text" id="divcode" name="divcode" value="<?php echo $this->data->divcode; ?>" size="20" maxlength="20" ></td>
			<?php } else { ?>
			<input type="hidden" id="divcode" name="divcode" value="<?php echo $this->data->divcode; ?>" />
			<?php echo $this->data->divcode;
			} ?>
			</td>
		</tr>
		<tr>
			<td class="key">*<label for="divname"><?php echo JText::_('BSC_DIVNAME'); ?></label></td>
			<td><input class="inputbox required" type="text" id="divname" name="divname" value="<?php echo $this->data->divname; ?>" size="80" maxlength="100" ></td>
		</tr>
		<tr>
			<td class="key">*<label for="divname_s"><?php echo JText::_('BSC_DIVNAME_S'); ?></label></td>
			<td><input class="inputbox required" type="text" id="divname_s" name="divname_s" value="<?php echo $this->data->divname_s; ?>" size="30" maxlength="40" ></td>
		</tr>
		<tr>
			<td class="key"><label for="divaddr"><?php echo JText::_('BSC_DIVADRR'); ?></label></td>
			<td><input class="inputbox " type="text" id="divaddr" name="divaddr" value="<?php echo $this->data->divaddr; ?>" size="100" maxlength="100" ></td>
		</tr>
		<tr>
			<td class="key"><label for="divzip"><?php echo JText::_('BSC_DIVZIP'); ?></label></td>
			<td><input class="inputbox validate-divzip" type="text" id="divzip" name="divzip" value="<?php echo $this->data->divzip; ?>" size="20" maxlength="20" ></td>
		</tr>
		<tr>
			<td class="key"><label for="divtel"><?php echo JText::_('BSC_DIVTEL'); ?></label></td>
			<td><input class="inputbox validate-divtel" type="text" id="divtel" name="divtel" value="<?php echo $this->data->divtel; ?>" size="20" maxlength="20" ></td>
		</tr>
		<tr>
			<td class="key"><label for="company"><?php echo JText::_('BSC_COMPANY'); ?></label></td>
			<td><input class="inputbox " type="text" id="company" name="company" value="<?php echo $this->data->company; ?>" size="100" maxlength="100" ></td>
		</tr>
		<tr>
			<td class="key"><label for="divtmpl"><?php echo JText::_('BSC_DIVTMPL'); ?></label></td>
			<td><?php echo $this->gettmpllist($this->data->divtmpl); ?></td>
		</tr>
	</tbody>
    </table>
	</fieldset>
	&nbsp;&nbsp;&nbsp;&nbsp;* : <?php echo JText::_('BSC_MUST_ENTRYFIELDS' ); ?>
	<?php if($task == 'submod' ) $st = 'divlist.editmod'; else $st = 'divlist.editnew'; ?>
	<input class="button" type="button" onclick="Joomla.submitbutton('<?php echo $st ?>')" value="<?php echo JText::_('BSC_ENTRY_NEW') ?>" style="margin-left: 100px;width: 60px;">
	<input class="button" type="button" onclick="Joomla.submitbutton('divlist.editcancel')" value="<?php echo JText::_('BSC_ENTRY_CANCEL') ?>" data-dismiss="modal" >
	<input type="hidden" name="option" value="com_bscore" />
	<input type="hidden" id="div_stat" name="div_stat" value="<?php echo $this->data->div_stat; ?>" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
   </form>

