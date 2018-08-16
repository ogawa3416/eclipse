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
 * @copyright	Copyright (C) 2009-2010 GROON Project. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: default.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.0 $
 **/

// no direct access
defined('_JEXEC') or die;
global $comcfg;
?>
   <form action="index.php?option=com_bscore" method="post" name="adminForm" id="adminForm">
    <?php if(version_compare(JVERSION, '3.0', 'ge')) : ?>
	<div class="btn-group pull-right hidden-phone">
		<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
		<?php echo $this->pageNav->getLimitBox(); ?>
	</div>
	<?php endif; ?>
   	<div>
		<label for="divselect"><?php echo JText::_('BSC_SELECT_DIVISION'); ?> : </label>
		<?php 
		echo $this->divlist;
		?>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<label for="search"><?php echo JText::_('BSC_NAME_SEARCH'); ?> : </label>
		<input type="text" id="search" name="search" value="<?php echo $this->search ?>" onchange="document.adminForm.limitstart.value=0;submit(this.form);return false;" />
	</div>

   <table cellpadding="4" cellspacing="0" border="0" width="100%" class="table adminlist">
   <thead>
      <tr>
         <th width="20px">#</th>
         <th class="title"><?php echo JText::_('BSC_ID') ?></th>
         <th ><?php echo JText::_('BSC_NAME') ?></th>
         <th ><?php echo JText::_('BSC_NAME1') ?></th>
         <th ><?php echo JText::_('BSC_NAME2') ?></th>
         <th ><?php echo JText::_('BSC_EMAIL') ?></th>
         <th ><?php echo JText::_('BSC_COMPANY') ?></th>
         <th ><?php echo JText::_('BSC_DIVNAME') ?></th>
         <th ><?php echo JText::_('BSC_EMPLOYEENO') ?></th>
		 <th ><?php echo JText::_('BSC_EMPMARK') ?></th>
         <th ><?php echo JText::_('BSC_TELNO') ?></th>
      </tr>
	</thead>
	<tbody>
      <?php
      $k = 0;
      $i = 0;
      for ($i=0, $n=count( $this->rows ); $i < $n; $i++) {
         $row = $this->rows[$i];
		 ?>
		<tr class="row<?php echo $k; ?>">
            <td width="3%" align="right"><?php echo $i+$this->pageNav->limitstart+1;?></td>
			<td width="5%" align="center"><?php echo $row->id; ?></td>
			<td width="14%" align="left">
				<span class="hasTip" title="<?php echo $row->username ?>">
				<?php echo $row->name; ?>
				</span>
			</td>
			<td width="6%" align="left"><?php echo $row->name1; ?></td>
			<td width="6%" align="left"><?php echo $row->name2; ?></td>
			<td width="19%" align="left"><?php echo $row->email; ?></td>
			<td width="14%" align="left"><?php echo $row->company; ?></td>
			<td width="14%" align="left"><?php echo $row->divname_s; ?></td>
			<td width="7%" align="left"><?php echo $row->employeeno; ?></td>
			<td width="4%" align="center"><?php if( $row->isbusiness ) echo '*'; else ''; ?></td>
			<td width="8%" align="left"><?php echo $row->teleno; ?></td>
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
		<input type="hidden" name="option" value="com_bscore" />
		<input type="hidden" name="task" value="userlist.show" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_( 'form.token' ); ?>
   </form>
<p align="center" ><?php echo JText::_('BSC_VERSION').":".$comcfg['version']; ?></p>

