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
 * @version		$Id: default.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;
require_once(JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php');
$user = JFactory::getUser();
?>
<form name="adminForm" id="adminForm" action="index.php?option=com_bsbooking" method="post">
    <?php if(version_compare(JVERSION, '3.0', 'ge')) : ?>
	<div class="btn-group pull-right hidden-phone">
		<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
		<?php echo $this->pagination->getLimitBox(); ?>
	</div>
	<?php endif; ?>
<table>
	<tr>
		<td><?php echo JText::_('COM_BSBOOKING_SCHEDULE_FILTER');?></td><td><?php echo BsbookingHelper::getScheduleSelectList($this->schedule_filter,'schedule_filter', true)?></td>
	</tr>
</table>
<table class="adminlist">
<thead>
	<tr>
<?php /* 20101101 blogstone. change to Num -> No */ ?>
		<th width="5%">No</th>
		<th width="5%"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
		<th width="17%" class="title"><?php echo JText::_('RESOURCE');?></th>
		<th width="16%"><?php echo JText::_('LOCATION')?></th>
        <th width="10%"><?php echo JText::_('ORDERING')?><?php echo JHTML::_('grid.order', $this->rows, 'filesave.png', 'resource.saveordering' )?></th>
<?php /* 20101101 blogstone. change to JText::_() */ ?>
		<th width="7%"><?php echo JText::_('MIN_RESERVATION');?></th>
		<th width="7%"><?php echo JText::_('MAX_RESERVATION');?></th>
		<th width="6%"><?php echo JText::_('MULTI_DAY_RESERVATION');?></th>
        <th width="16%"><?php echo JText::_('SCHEDULE_NAME');?></th>
        <th width="5%"><?php echo JText::_('JPUBLISHED');?></th>
		<th width="6%"><?php echo JText::_('COM_BSBOOKING_CHECKED_OUT');?></th>
	</tr>
</thead>
<tbody>
<?php 
$k = 0;
$i = 0;
$n = count($this->rows);
foreach ($this->rows as $row):
	$link = JRoute::_( 'index.php?option=com_bsbooking&task=resource.edit&cid[]='. $row->id );
	$checkbox = JHTML::_('grid.checkedout', $row, $i );
	$checked_out_by =  JFactory::getUser($row->checked_out);
    $ordering = true;
?>
	<tr>
		<td align="right"><?php echo $this->pagination->getRowOffset($i);?></td>
		<td align="center"><?php echo $checkbox?></td>
		<td><?php
//				if (  JTable::isCheckedOut($user->get ('id'), $row->checked_out ) ) 
				$table = JTable::getInstance('Resource', 'Table');
				if( $table->isCheckedOut($user->get ('id')) ) 
				{
					echo $row->name;
				} else {
			?>
				<span class="editlinktip hasTip" title="<?php echo JText::_('COM_BSBOOKING_EDIT_CONTENT');?>::<?php echo $row->title; ?>">
					<a href="<?php echo $link; ?>">
					<?php echo $row->title; ?></a></span>
				<?php
				}
			?>
		</td>
		<td><?php echo $row->location?></td>
        <td class="order">
        	<div class="orderset">
            <span><?php echo $this->pagination->orderUpIcon( $i, ($row->schedule_id == @$this->rows[$i-1]->schedule_id), 'resource.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
            <span><?php echo $this->pagination->orderDownIcon( $i, $n, ($row->schedule_id == @$this->rows[$i+1]->schedule_id), 'resource.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering ); ?></span>
            <input type="text" name="order[]" size="3" value="<?php echo $row->ordering?>" class="text_area" style="text-align: center;"/>
            </div>
        </td>
		<td><?php echo $row->min_res?></td>
		<td><?php echo $row->max_res?></td>
		<td><?php if($row->allow_multi)  echo JText::_('JYES') ; else echo JText::_('JNO') ; ?></td>
        <td><?php echo $row->schedule_title?></td>
		<td align="center"><?php echo JHTML::_('grid.published', $row, $i,'publish_g.png','publish_x.png', 'resource.')?></td>
        <td><?php echo ($row->checked_out?JFactory::getUser($row->checked_out)->name:"&nbsp;")?></td> 
	</tr>
<?php 
$i++;
$k = 1 - $k;
endforeach
?>	
</tbody>
<tfoot>
	<tr><td colspan="12"><?php echo $this->pagination->getListFooter();?></td></tr>
</tfoot>
</table>
<input type="hidden" name="task" value="resource.display" />
<input type="hidden" name="boxchecked" value="0" />
</form>