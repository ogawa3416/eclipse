<?php
/**
 * BsScheduler component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		BsScheduler
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2011 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: blogstone.php 39 2011-03-11 12:22:03Z BlogStone ver2.5.0 $
 **/
// Check to ensure this file is included in Joomla!
// no direct access
defined('_JEXEC') or die;
JHTML::stylesheet('components/com_bsscheduler/assets/css/bsscheduler.css' );
jimport( 'joomla.application.web.webclient' );

$is_mobile = 0;
?>
<div class="bsdialogbox">
<div data-role="header">&nbsp;</div>
<div data-role="content" >
<form name="adminForm" id="adminForm" action="index.php?option=com_bsscheduler&tmpl=component&task=getulist" method="put" data-transition=“none”   >
<div class="userblock" >
     <div class="subtitle" >
        <?php echo JText::_('COM_BSSCHEDULER_SELECT_USER_TITLE') ?>
     </div>
	<table width="100%" border="0" cellspacing="0" cellpadding="5" class="mainselector" >
    <tr>
    <?php if( !$is_mobile ) : ?>
		<td class="hdselectlist">
			<div id="jm_search">
    		<?php echo JText::_('COM_BSSCHEDULER_FILTER_BY_NAME')?> : <input type="text" name="search" value="<?php echo $this->searchword?>" onchange="this.form.submit();"  />
			</div>
		</td>
	<?php endif; ?>
		<td class="hdselectlist">
			<label for="division"><?php echo JText::_('SELECT_DIVISION'); ?> : </label>
			<?php 
			echo $this->divlist;
			?>
		</td>
	<?php if( !$is_mobile ) : ?>
		<td>
		<div class="display">
		<?php echo JText::_('DISPLAY_NUM'); ?>&nbsp;
		<?php
			echo $this->pagination->getLimitBox(); 
		 ?>
		</div>
		</td>
	<?php endif; ?>
	</tr>
	</table>
	<table class="userlist table table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th style="text-align: center;"><?php echo JText::_("NUM")?></th>
        <th><?php echo JText::_("FULL_NAME")?></th>
        <th><?php echo JText::_("DIVISION")?></th>
        <th><?php echo JText::_("EMAIL")?></th>
    </tr>
    </thead>
    <tbody>
<?php 
if (count($this->rows) == 0) : ?>
    <tr>
        <td colspan="5">No records</td>
    </tr>
<?php else : ?>
	<?php
		$i = 0; $k = 0;
		foreach ($this->rows as $row) :       
	?>
    <tr class="bstableentry<?php echo $k+1?>">
        <td style="text-align:right"><?php echo $this->pagination->getRowOffset($i)?></td>
        <td><a href="#" onclick="updateUser(<?php echo  $row->id?>, '<?php echo $row->name?>','<?php echo $row->divname ?>');"><?php  echo $row->name?></a></td>
        <td><?php echo $row->divname?></td>
        <td><?php echo $row->email?></td>
    </tr>
    <?php
            $i++; $k = 1 - $k; 
        endforeach 
    ?>

<?php endif ?>
    </tbody>
	</table>
	<p class="counter">
	<?php echo $this->pagination->getPagesCounter(); ?>
	</p>
	<div class="pagenavline">
		<?php echo $this->pagination->getPagesLinks();?>
	</div>
	<div class="closebtn">
		<button type="button" class="btn modal-button" onclick="BsmodalCancel('username');" ><i class="icon-cancel"></i><?php echo JText::_("JCANCEL") ?></button>
	</div>
    <input type="hidden" name="option" value="com_bsscheduler" />
    <input type="hidden" name="tmpl" value="component" />
    <input type="hidden" name="task" value="getulist" />
</div>
</form>
</div>
<div data-role="footer" >&nbsp;</div>
</div>
<script type="text/javascript">
    function updateUser(userid, name,divname)
    {
        if (window.parent ) {
			window.parent.updateReservedFor(userid, name, divname);
			window.parent.jQuery('#username').modal('hide');
        } 
    }
</script>