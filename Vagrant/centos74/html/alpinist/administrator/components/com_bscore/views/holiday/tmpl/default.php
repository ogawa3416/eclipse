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
JHtml::_('formbehavior.chosen', 'select');
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
	var holid = document.getElementById("holid") ;
	var hddate = document.getElementById("holiday") ;
	var hdname = document.getElementById("holidayname") ;
	var hdstat = document.getElementById("holiday_stat") ;
	var hdmanu = document.getElementById("manual") ;
	holid.value = dr['holid'];
	hddate.value = dr['holiday'];
	hdname.value = dr['holidayname'];
	hdstat.value = dr['holiday_stat'];
	hdmanu.value = dr['manual'];
	Joomla.submitform( pressbutton );
}
// -->
</script>


	<form action="index.php?option=com_bscore&task=holiday.show" method="post" name="adminForm" id="adminForm">
	<?php
		$javascript = 'onchange="Joomla.submitform(\'holiday.show\')";';
		$size = 1;
		echo JHTML::_('select.genericlist',   $this->yearall, 'selected_y', 'class="inputbox" size="'. $size .'" '. $javascript, 'value', 'text', $this->active );
	?>
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="table adminlist">
	<thead>
	  <tr>
		 <th width="5%"><input type="checkbox" name="toggle" value="" onclick="return Joomla.checkAll(this);" /></th>
		 <th width="5%"><?php echo JText::_('BSC_HOLIDAY_STATUS') ?></th>
         <th width="10%"><?php echo JText::_('BSC_HOLIDAY_DATE') ?></th>
         <th ><?php echo JText::_('BSC_HOLIDAY_NAME') ?></th>
      </tr>
	</thead>
	<tbody>
      <?php
      $k = 0;
      $i = 0;
      for ($i=0, $n=count( $this->rows ); $i < $n; $i++) {
		$row = $this->rows[$i];
		if($row->holiday_stat == 1 ){
		 	$msg = JText::_('BSC_HOLIDAY_ON');
			$img = 'publish_g.png';
			$alt = JText::_('JPUBLISHED');
			$sect_link = JRoute::_( 'index.php?option=com_bscore&tmpl=component&task=holiday.submod&cid[]='.$row->id );
		} else {
		 	$msg = JText::_('BSC_HOLIDAY_OFF');
			$img = 'publish_x.png';
			$alt = JText::_('JUNPUBLISHED');
			$sect_link = JRoute::_( 'index.php?option=com_bscore&tmpl=component&task=holiday.submod&cid[]='.$row->id );
	  	}
		$att = 'class="modal" rel="{handler: \'iframe\', size: {x:540, y:200}}"';
		$rowinfo = sprintf($msg,"",JHTML::_('date', $row->created, JText::_('DATE_FORMAT_LC1')));
		 ?>
		<tr class="row<?php echo $k; ?>">
			<input type="hidden" name="code_stat[]" value="<?php echo $row->id .'/'. $row->holiday_stat; ?>" />
			<td  align="center"><input type="checkbox" id="cb<?php echo $i ?>" name="cid[]" value="<?php echo $row->id ?>" onclick="return boxlistcheck()"/></td>
			<td  align="center">
				<span class="editlinktip hasTip" title="<?php echo JText::_('BSC_INSERT_INFORMATION');?>::<?php echo $rowinfo; ?>">
 				<img src="components/com_bscore/assets/images/<?php echo $img;?>" width="16" height="16" border="0" alt="<?php echo $alt; ?>" /></span>
 			</td>
			<td align="left"><a href="<?php echo $sect_link .'" '.$att ?>><?php echo $row->holiday; ?></td>
			<td align="left"><?php echo $row->holidayname; ?></td>
		 </tr>
            <?php
               $k = 1 - $k;
         }?>
      </tr>
	</tbody>
    </table>
		<input type="hidden" id="holid" name="holid" value="" />
		<input type="hidden" id="holiday" name="holiday" value="" />
		<input type="hidden" id="holidayname" name="holidayname" value="" />
		<input type="hidden" id="holiday_stat" name="holiday_stat" value="" />
		<input type="hidden" id="manual" name="manual" value="" />
		<input type="hidden" name="option" value="com_bscore" />
		<input type="hidden" name="task" value="holiday.show" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_( 'form.token' ); ?>
   </form>

