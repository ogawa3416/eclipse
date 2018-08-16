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
require_once(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_users".DIRECTORY_SEPARATOR."helpers".DIRECTORY_SEPARATOR."html".DIRECTORY_SEPARATOR."users.php");

$listOrder  = $this->escape($this->state->get('list.ordering'));
$listDirn   = $this->escape($this->state->get('list.direction'));
$loggeduser = JFactory::getUser();

?>
<p><?php echo JText::_('COM_BSSCHEDULER_USER_DELMES');?></p>
<form name="adminForm" id="adminForm" action="<?php echo JRoute::_('index.php?option=com_bsscheduler&view=delschedule');?>" method="post">
    <?php if(version_compare(JVERSION, '3.0', 'ge')) : ?>
	<div class="btn-group pull-right hidden-phone">
		<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
		<?php echo $this->pagination->getLimitBox(); ?>
	</div>
	<?php endif; ?>
<table>
	<tr>
		<td><?php echo JText::_('COM_BSSCHEDULER_SEARCH');?></td><td><input type="text"  size="50" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" onblur="this.form.submit();" title="<?php echo JText::_('COM_USERS_SEARCH_IN_NAME'); ?>"/></td>
	</tr>
</table>

<table class="table table-striped" id="userList">
<?php if (empty($this->rows)) : ?>
		<div class="alert alert-no-items">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
<?php else : ?>
    <thead>
    <tr>
  		<th width="15px">No</th>
		<th width="20px"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
        <th class="left"><?php echo JHtml::_('grid.sort', 'COM_USERS_HEADING_NAME', 'a.name', $listDirn, $listOrder); ?></th>
        <th class="left"><?php echo JHtml::_('grid.sort', 'JGLOBAL_USERNAME', 'a.username', $listDirn, $listOrder); ?></th>
        <th class="left"><?php echo JText::_('COM_USERS_HEADING_ENABLED');?></th>
        <th class="left"><?php echo JText::_('COM_USERS_HEADING_ACTIVATED');?></th>
        <th class="left"><?php echo JHtml::_('grid.sort', 'COM_BSSCHEDULER_DIVNAME', 'c.divcode', $listDirn, $listOrder); ?></th>
        <th class="left"><?php echo JHtml::_('grid.sort', 'JGLOBAL_EMAIL', 'a.email', $listDirn, $listOrder); ?></th>
        <th class="left"><?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?></th>
    </tr>
    </thead>
    <tbody>
<?php
    $i = 0;
    $k = 0;
    foreach ($this->rows as $item) : 

        $checked 	= JHTML::_('grid.checkedout', $item, $i );
?>
    <tr class="rows<?php echo $k;?>">
        <td width="15px"><?php echo $this->pagination->getRowOffset($i);?></td>
        <td width="20px"><?php echo $checked?></td>
		<td class="break-word">
			<?php echo $this->escape($item->name); ?>
		</td>
		<td class="break-word">
			<?php echo $this->escape($item->username); ?>
		</td>
		<td class="center hidden-phone">
			<?php
			$self = $loggeduser->id == $item->id;
			echo JHtml::_('jgrid.state', JHtmlUsers::blockStates($self), $item->block, $i, 'users.', false);
			?>
		</td>
		<td class="center hidden-phone">
			<?php
			$activated = empty( $item->activation) ? 0 : 1;
			echo JHtml::_('jgrid.state', JHtmlUsers::activateStates(), $activated, $i, 'users.', false);
			?>
		</td>
        <td><?php echo $item->divname?></td>
		<td class="hidden-phone break-word hidden-tablet">
			<?php echo JStringPunycode::emailToUTF8($this->escape($item->email)); ?>
		</td>
		<td class="hidden-phone">
			<?php echo (int) $item->id; ?>
		</td>
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
<?php endif ?>
</table>
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0"/>
<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
<?php echo JHtml::_('form.token'); ?>

</form>