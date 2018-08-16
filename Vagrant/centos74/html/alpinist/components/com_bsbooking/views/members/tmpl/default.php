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
 * @version		$Id: default.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.0 $
 **/
// no direct access
defined('_JEXEC') or die;
JHTML::stylesheet('components/com_bsbooking/assets/css/layout.css');
JHtml::_('jquery.framework');
$is_mobile = 0;
?>
<div class="bsdialogbox">
<div class="dialogheader" data-role="header"></div>
<div data-role="content" >
<form name="adminForm" id="adminForm" action="index.php?option=com_bsbooking&tmpl=component&task=members.edit" method="post">

<div id="memberselector" >
	<div id="bsbtitleblk" >
        <?php echo JText::_('COM_BSBOOKING_ADDMEMBER') ?>
	</div>
	<input type='hidden' id='memlist' value='' />
	<div id="memselect">
	<table id="selecttable"  width="100%" border="0" cellspacing="0" cellpadding="5" id="bsbmemmainblk" >
	<tr>
		<td class="memberblk1" style='width:40%;'>
			<p><?php echo JText::_('COM_BSBOOKING_MEMBERS')?></p>
			<div>
<?php /*				<select id='memberslist' size='10' multiple='multiple' data-mini="true" onchange='bsbmemberchange("Out",<?php echo (int)$is_mobile;?>);'></select> */ ?>
				<div id='memberslist'></div>
			</div>
			<div id="mbmemlist"><div>
		</td>
		<td class="memberblk2" style='width:10%;'>
			<div class="bsbeditbtn">&#60;&#60;</div>
			<div class="bsbeditbtn">&#62;&#62;</div>
		</td>
		<td class='memberblk3' style='width:40%;'>
			<p><?php echo JText::_('COM_BSBOOKING_MEMBER_DIVISION'); ?> </p>
			<div id="bsbdiv">
			<?php 
			$att = 'onchange="memsubmitform(this.form,'.$is_mobile.')" data-mini="true" ';
			echo BscoreHelper::divcodelist( 'divcode',$this->divcode,$att,'divcode',1,$this->alldiv );
			?>
			</div>
			<div class="memberselecter">
				<?php /* echo JHTML::_('select.genericlist', $this->userlist, "uselectlist", 'class="inputbox " multiple="multiple" size="9" onchange="bsbmemberchange(\'In\','.(int)$is_mobile.');" data-role="none" '. NULL, 'id', 'name' ); */ ?>
				<div id="uselectlist">
				<?php foreach($this->userlist as $ulst) : ?>
				<div value="<?php echo $ulst->id ?>" onclick='bsbmemberchange("In",this,<?php echo (int)$is_mobile;?>);'><?php echo $ulst->name ?></div>
				<?php endforeach?>
				</div>
			</div>
		</td>

	</tr>
	</table>
	</div>
	<div class='memberblk4'>
		<div id='bsbmailcheck' data-role="fieldcontain"> 
			<fieldset data-role="controlgroup"> 
				<input type='checkbox' id='eventry' name='eventry' value='' onchange='bsevchange(<?php echo $is_mobile;?>);' data-mini="true" /><label for="eventry"><?php echo JText::_('COM_BSBOOKING_EVENT_ENTRY') ?></label>
				<input type='checkbox' id='emsend' name='emsend' value='' data-mini="true" /><label for="emsend"><?php echo JText::_('COM_BSBOOKING_SENDMAIL') ?></label>
			</fieldset>
		</div>
	</div>
	<div class="br"></div>
	<div>
		<?php echo JText::_("COM_BSBOOKING_MAILINFO1"); ?>
		<br />
		<?php echo JText::_("COM_BSBOOKING_MAILINFO2"); ?>
	</div>
	<button type="button" name="save" onclick="updateMembers('save');" class="btn modal-button"/><i class="icon-save"></i><?php echo JText::_('JSAVE');?></button>
	<button type="button" name="cancel" onclick="updateMembers('cancel');" class="btn modal-button" /><i class="icon-cancel"></i><?php echo JText::_('JCANCEL');?></button>
    <input type="hidden" name="tmpl" value="component" />
    <input type="hidden" name="task" value="members.edit" />
</div>
</form>
</div>
<div class="dialogfooter" data-role="footer" ></div>
</div>
<script language="javascript" type="text/javascript">
<!--
window.onLoad = bsbmenbersload(<?php echo (int)$is_mobile;?>);
// -->
</script>