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
 * @version		$Id: default.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.0 $
 **/

// no direct access
defined('_JEXEC') or die;
global $comcfg;
?>
<script language="javascript" type="text/javascript">
<!--
function boxlistcheck() {
	var form = document.adminForm;
	var cidn = document.getElementsByName("cid[]") ;
	form.boxchecked.value = 0;
	for(i=0;i<cidn.length;i++ ) {
		if(cidn[i].checked) form.boxchecked.value = 1; 
	}
	return true;
}
function updatedivFor(key,dr) {
	var ndk = document.getElementById("divkey") ;
	var nod = document.getElementById("ondivnew") ;
	ndk.value = key;
	nod.value = dr;
	Joomla.submitform( 'accontrol.divnew' );
}
function updateuserFor(pb,key,dr) {
	var ndk = document.getElementById("divkey") ;
	var nod = document.getElementById("onusernew") ;
	ndk.value = key;
	nod.value = dr;
	Joomla.submitform( pb );
}
function updategroupFor(pb,dr) {
	var ndk = document.getElementById("divkey") ;
	var ngr = document.getElementById("new_group") ;
	ndk.value = dr['divkey'];
	ngr.value = dr['com_group'];
	Joomla.submitform( pb );
}
// -->
</script>
   <form action="index.php?option=com_bscore&task=accontrol.show" method="post" name="adminForm" id="adminForm">
    <?php if(version_compare(JVERSION, '3.0', 'ge')) : ?>
	<div class="btn-group pull-right hidden-phone">
		<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
		<?php echo $this->pageNav->getLimitBox(); ?>
	</div>
	<?php endif; ?>
	<div>
		<label for="com_group"><?php echo JText::_('BSC_ACCESS_CONTROLGROUP') ?>:<span><?php echo $this->acclist ?></span></label>
		&nbsp;&nbsp;&nbsp;&nbsp;*<?php echo JText::_('BSC_ACC_DESCRIPTION')?>
	</div>
   <table cellpadding="4" cellspacing="0" border="0" width="100%" class="table adminlist">
   <thead>
      <tr>
         <th width="3%" >#</th>
		 <th width="2%" ><input type="checkbox" name="toggle" value="" disabled="disabled" /></th>
         <th width="12%" ><?php echo JText::_('BSC_DIVKEY') ?></th>
         <th width="12%" ><?php echo JText::_('BSC_DIVKEYNAME') ?></th>
         <th colspan="2" ><?php echo JText::_('BSC_ONDIV') ?></th>
         <th colspan="2" ><?php echo JText::_('BSC_ONUSER') ?></th>
      </tr>
	</thead>
	<tbody>
      <?php
      $k = 0;
      $i = 0;
      for ($i=0, $n=count( $this->rows ); $i < $n; $i++) {
    	$row = $this->rows[$i];
		$imgd = 'components/com_bscore/assets/images/edit.png';
		$altd = JText::_('BSC_DIV_ACCEDIT_INFO');
		$sect_linkd = JRoute::_( 'index.php?option=com_bscore&tmpl=component&task=accontrol.divmod&com_group='.$row->com_group.'&divkey='.$row->divkey );
		$attd = 'class="modal" rel="{handler: \'iframe\', size: {x:440, y:420}}"';
		$imgu = 'components/com_bscore/assets/images/users.png';
		$altu = JText::_('BSC_USER_ACCEDIT_INFO');
		$sect_linku = JRoute::_( 'index.php?option=com_bscore&tmpl=component&task=accontrol.usermod&com_group='.$row->com_group.'&divkey='.$row->divkey );
		$attu = 'class="modal" rel="{handler: \'iframe\', size: {x:440, y:460}}"';

		 ?>
		<tr class="row<?php echo $k; ?>">
            <td align="right"><?php echo $i+$this->pageNav->limitstart+1;?></td>
			<td align="center"><input type="checkbox" id="cb<?php echo $i ?>" name="cid[]" value="<?php echo $row->divkey ?>" onclick="return boxlistcheck()"/></td>
			<td align="left"><?php echo $row->divkey; ?></td>
			<td align="left"><?php echo $row->divname; ?></td>
			<td width="20px" align="center">
				<span class="editlinktip hasTip" title="<?php echo JText::_('BSC_DIV_ACCEDIT_INFO');?>::<?php echo JText::_('BSC_DIV_ACCEDIT_MSG'); ?>">
				<a href="<?php echo $sect_linkd ?>" <?php echo $attd ?> >
 				<img src="<?php echo $imgd;?>" width="16" height="16" border="0" alt="<?php echo $altd; ?>" /></a></span>
 			</td>
			<td align="left"><?php echo $row->ondivstr; ?></td>
			<td width="20px" align="center">
				<span class="editlinktip hasTip" title="<?php echo JText::_('BSC_USER_ACCEDIT_INFO');?>::<?php echo JText::_('BSC_USER_ACCEDIT_MSG'); ?>">
				<a href="<?php echo $sect_linku ?>" <?php echo $attu ?> >
 				<img src="<?php echo $imgu;?>" width="16" height="16" border="0" alt="<?php echo $altu; ?>" /></a></span>
 			</td>
			<td align="left"><?php echo $row->onuserstr; ?></td>
		 </tr>
            <?php
               $k = 1 - $k;
         }?>
      </tr>
	</tbody>
	<tfoot>
		<tr>
		<td colspan="15">
			<?php echo $this->pageNav->getListFooter(); ?>
		</td>
		</tr>
	</tfoot>
    </table>
		<input type="hidden" id="divkey" name="divkey" value="" />
		<input type="hidden" id="new_group" name="new_group" value="" />
		<input type="hidden" id="ondivnew" name="ondivnew" value="" />
		<input type="hidden" id="onusernew" name="onusernew" value="" />
		<input type="hidden" name="option" value="com_bscore" />
		<input type="hidden" name="task" value="accontrol.show" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_( 'form.token' ); ?>
   </form>
<p align="center" ><?php echo JText::_('BSC_VERSION').":".$comcfg['version']; ?></p>

