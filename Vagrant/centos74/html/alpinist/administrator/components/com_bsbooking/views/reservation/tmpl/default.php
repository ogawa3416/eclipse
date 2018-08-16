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
 * @version		$Id: default.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.3 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;
jimport('joomla.utilities.date');
require_once(JPATH_SITE.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_bsbooking".DIRECTORY_SEPARATOR."libraries".DIRECTORY_SEPARATOR."dateutil.class.php");
$dateFormat = "Y-m-d";
$mainframe = JFactory::getApplication();
$offset= $mainframe->getCfg('offset');
?>
<form name="adminForm" id="adminForm" action="index.php?option=com_bsbooking" method="post">
    <?php if(version_compare(JVERSION, '3.0', 'ge')) : ?>
	<div class="btn-group pull-right hidden-phone">
		<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
		<?php echo $this->pagination->getLimitBox(); ?>
	</div>
	<?php endif; ?>
<table class="adminlist">
    <thead>
    <tr>
<?php /* 20101101 blogstone. change to Num -> No */ ?>
  		<th width="15px">No</th>
		<th width="20px"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
        <th class="left"><?php echo JText::_('RESOURCE_NAME');?></th>
        <th class="left"><?php echo JText::_('START_DATE');?></th>
        <th class="left"><?php echo JText::_('END_DATE');?></th>
        <th class="left"><?php echo JText::_('START_TIME');?></th>
        <th class="left"><?php echo JText::_('END_TIME');?></th>
        <th class="left"><?php echo JText::_('SUMMARY');?></th>
        <th class="left"><?php echo JText::_('RESERVED_FOR');?></th>
        <th class="left"><?php echo JText::_('COM_BSBOOKING_CREATED');?></th>
    </tr>
    </thead>
<?php 
if (count($this->rows)>0) : ?>
    <tbody>
<?php
    $i = 0;
    $k = 0;
    foreach ($this->rows as $row) : 

        $checked 	= JHTML::_('grid.checkedout', $row, $i );
?>
    <tr class="rows<?php echo $k;?>">
        <td width="15px"><?php echo $this->pagination->getRowOffset($i);?></td>
        <td width="20px"><?php echo $checked?></td>
        <td><?php echo $row->resource_name?></td>
        <td><?php echo DateUtil::formatDate($row->start_date,'%Y-%m-%d')?></td>
        <td><?php echo DateUtil::formatDate($row->end_date,'%Y-%m-%d')?></td>
        <td><?php echo DateUtil::formatTime($row->start_time,false, 24)?></td>
        <td><?php echo DateUtil::formatTime($row->end_time,false, 24)?></td>
        <td><?php echo htmlspecialchars($row->summary)?></td>
        <td><?php echo $row->reserved_for_name?></td>
        <td><?php echo JHtml::_('date', $row->created, 'Y-m-d H:i'); ?></td>
    </tr>
<?php 
        $i++; $k = 1 - $k;
    endforeach; 
?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="10" align="center"><?php echo $this->pagination->getListFooter()?></td>
        </tr>
    </tfoot>
<?php else : ?>
    <tbody>
        <tr><td colspan="10" align="center">No record</td></tr>
    </tbody>
<?php endif ?>
</table>
<input type="hidden" name="task" value="reservation.getlist" />
<input type="hidden" name="boxchecked" value="0"/>
</form>