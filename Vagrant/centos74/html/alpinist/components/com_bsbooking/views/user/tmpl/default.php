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
 * @version		$Id: default.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
 **/
// no direct access
defined('_JEXEC') or die;
require_once( JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_bscore'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php' );
JHTML::stylesheet('components/com_bsbooking/assets/css/layout.css');
$is_mobile = 0;
?>
<div class="bsdialogbox" >
<div data-role="header">&nbsp;</div>
<div data-role="content" >
<form name="adminForm" id="adminForm" action="index.php?option=com_bsbooking&tmpl=component&task=user.getlist" method="post">
<div class="userblock">
	<div class="subtitle" >
		<?php echo JText::_('COM_BSBOOKING_SELECT_USER_TITLE') ?>
	</div>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="mainselector" >
    <tr>
		<td class="hdselectlist">
			<div id="jm_search">
    		<?php echo JText::_('COM_BSBOOKING_FILTER_BY_NAME');  ?> : <input type="text" name="search" value="<?php echo $this->searchword?>" onchange="this.form.submit();" />
			</div>
		</td>
		<td class="hdselectlist">
			<label for="division"><?php echo JText::_('SELECT_DIVISION'); ?> </label>
			<?php 
			$att = 'onchange="BsFormsubmit(this.form,0)"';
			echo BscoreHelper::divcodelist( 'divcode',$this->divcode,$att,'divcode',1 );
			?>
		</td>
		<td>
		<div class="display">
		<?php echo JText::_('COM_BSBOOKING_DISPNUM'); ?>&nbsp;
		<?php echo $this->pagination->getLimitBox(); ?>
		</div>
		</td>
	</tr>
</table>
<table class="userlist table table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th style="text-align: center;"><?php echo JText::_("COM_BSBOOKING_NUM")?></th>
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
        <td><a href="#" onclick="updateUser(<?php echo $row->id?>, '<?php echo $row->name?>', '<?php echo $row->email?>','<?php echo $row->divname?>');"><?php echo $row->name?></a></td>
        <td><?php echo $row->divname?></td>
        <td><?php echo $row->email?></td>
    </tr>
    <?php
            $i++; $k = 1 - $k; 
        endforeach 
    ?>
    </tbody>
<?php endif ?>
</table>
	<p class="counter">
		<?php echo $this->pagination->getPagesCounter(); ?>
	</p>
	<div class="pagenavline">
		<?php echo $this->pagination->getPagesLinks();?>
	</div>
	<div class="closebtn">
		<button type="button" onclick="BsmodalCancel('modal-userch');" class="btn modal-button"><i class="icon-cancel"></i><?php echo JText::_("CANCEL") ?></button>
	</div>
</div>
    <input type="hidden" name="tmpl" value="component" />
    <input type="hidden" name="task" value="user.getlist" />
	<input type="hidden" name="limitstart" value="" />
</form>
</div>
<div data-role="footer" >&nbsp;</div>
</div>

<script type="text/javascript">
    function updateUser(user_id, fullname, email,divname)
    {
    	if (window.parent ) {
			window.parent.updateReservedFor(user_id, fullname, email,divname);
			window.parent.jQuery('#modal-userch').modal('hide');
        }
    }
</script>