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
 * @version		$Id: default.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
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
function updatedataFor(pressbutton,dr) {
	var ndcode = document.getElementById("divcode") ;
	var ndstat = document.getElementById("div_stat") ;
	var ndname = document.getElementById("divname") ;
	var ndnames = document.getElementById("divname_s") ;
	var ndaddr = document.getElementById("divaddr") ;
	var ndzip = document.getElementById("divzip") ;
	var ndtel = document.getElementById("divtel") ;
	var ndcom = document.getElementById("company") ;
	var ndtmpl = document.getElementById("divtmpl") ;
	ndcode.value = dr['divcode'];
	ndstat.value = dr['divstat'];
	ndname.value = dr['divname'];
	ndnames.value = dr['divname_s'];
	ndaddr.value = dr['divaddr'];
	ndzip.value = dr['divzip'];
	ndtel.value = dr['divtel'];
	ndcom.value = dr['company'];
	ndtmpl.value = dr['divtmpl'];
	Joomla.submitform( pressbutton );
}
// -->
</script>


   <form action="index.php?option=com_bscore&task=divlist.show" method="post" name="adminForm" id="adminForm">
    <?php if(version_compare(JVERSION, '3.0', 'ge')) : ?>
	<div class="btn-group pull-right hidden-phone">
		<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
		<?php echo $this->pageNav->getLimitBox(); ?>
	</div>
	<?php endif; ?>
   <table cellpadding="4" cellspacing="0" border="0" width="100%" class="table adminlist">
   <thead>
      <tr>
         <th >#</th>
		 <th ><input type="checkbox" name="toggle" value="" disabled="disabled" /></th>
         <th ><?php echo JText::_('BSC_DIVSTATUS') ?></th>
         <th ><?php echo JText::_('BSC_DIVISION_CODE') ?></th>
         <th ><?php echo JText::_('BSC_DIVNAME') ?></th>
         <th ><?php echo JText::_('BSC_DIVNAME_S') ?></th>
         <th ><?php echo JText::_('BSC_DIVADRR') ?></th>
         <th ><?php echo JText::_('BSC_DIVZIP') ?></th>
         <th ><?php echo JText::_('BSC_DIVTEL') ?></th>
		 <th ><?php echo JText::_('BSC_DIVTMPL') ?></th>
      </tr>
	</thead>
	<tbody>
      <?php
      $k = 0;
      $i = 0;
      for ($i=0, $n=count( $this->rows ); $i < $n; $i++) {
    	$row = $this->rows[$i];
		if($row->div_stat == 1 ){
		 	$msg = JText::_('BSC_DIVSTATUS_ON');
			$img = 'publish_g.png';
			$alt = JText::_('JPUBLISHED');
			$sect_link = JRoute::_( 'index.php?option=com_bscore&tmpl=component&task=divlist.submod&cid[]='.$row->divcode );
		} else {
		 	$msg = JText::_('BSC_DIVSTATUS_OFF');
			$img = 'publish_x.png';
			$alt = JText::_('JUNPUBLISHED');
			$sect_link = JRoute::_( 'index.php?option=com_bscore&tmpl=component&task=divlist.submod&cid[]='.$row->divcode );
	  	}
		$att = 'class="modal" rel="{handler: \'iframe\', size: {x:540, y:400}}"';
		$rowinfo = sprintf($msg,$row->divcode,JHTML::_('date', $row->created, JText::_('DATE_FORMAT_LC1')));

		 ?>
		<tr class="row<?php echo $k; ?>">
			<input type="hidden" name="code_stat[]" value="<?php echo $row->divcode .'/'. $row->div_stat; ?>" />
            <td width="3%" align="right"><?php echo $i+$this->pageNav->limitstart+1;?></td>
			<td width="2%" align="center"><input type="checkbox" id="cb<?php echo $i ?>" name="cid[]" value="<?php echo $row->divcode ?>" onclick="return boxlistcheck()"/></td>
			<td width="2%" align="center">
				<span class="editlinktip hasTip" title="<?php echo JText::_('BSC_INSERT_INFORMATION');?>::<?php echo $rowinfo; ?>">
 				<img src="components/com_bscore/assets/images/<?php echo $img;?>" width="16" height="16" border="0" alt="<?php echo $alt; ?>" /></span>
 			</td>
			<td width="9%" align="left"><a href="<?php echo $sect_link .'" '.$att ?>><?php echo $row->divcode; ?></td>
			<td width="21%" align="left"><?php echo $row->divname; ?></td>
			<td width="12%" align="left"><?php echo $row->divname_s; ?></td>
			<td width="25%" align="left"><?php echo $row->divaddr; ?></td>
			<td width="8%" align="left"><?php echo $row->divzip; ?></td>
			<td width="8%" align="left"><?php echo $row->divtel; ?></td>
			<td width="10%" align="left"><?php echo $row->divtmpl ? $row->divtmpl : JText::_('JDEFAULT'); ?></td>
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
		<input type="hidden" id="divcode" name="divcode" value="" />
		<input type="hidden" id="div_stat" name="div_stat" value="" />
		<input type="hidden" id="divname" name="divname" value="" />
		<input type="hidden" id="divname_s" name="divname_s" value="" />
		<input type="hidden" id="divaddr" name="divaddr" value="" />
		<input type="hidden" id="divzip" name="divzip" value="" />
		<input type="hidden" id="divtel" name="divtel" value="" />
		<input type="hidden" id="company" name="company" value="" />
		<input type="hidden" id="divtmpl" name="divtmpl" value="" />
		<input type="hidden" name="option" value="com_bscore" />
		<input type="hidden" name="task" value="divlist.show" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_( 'form.token' ); ?>
   </form>
<p align="center" ><?php echo JText::_('BSC_VERSION').":".$comcfg['version']; ?></p>

