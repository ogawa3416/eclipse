<?php
/**
 * JForms component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		BsAlpinist
 * @subpackage	JForms Components
 * @copyright	Copyright (C) 2009-2011 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: default.php 123 2012-12-24 00:00:00Z BsAlpinist ver.2.4.1 $
 **/
// no direct access
defined('_JEXEC') or die; 
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('bootstrap.popover');
?>

<div class="category-list<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
<?php if ($this->params->get('show_page_title',1)) : ?>
<h2 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?> comtitle">
	<?php echo $this->escape($this->params->get('page_title')); ?>
</h2>
<?php endif; ?>
<form name="adminForm" id="adminForm" action="<?php echo $this->action; ?>" method="post" class="form-validate">
	<div class="listsum">
		<?php echo JText::_('JFM_DATATOTAL') ?>
		&nbsp;:&nbsp;
		<span id="comtotal"><?php echo number_format($this->total); ?></span>
	</div>
	<div class="csvbtnblock">
		<a href="#" onclick="if (confirm('<?php echo JText::_('JFORMS_CSVCONFIRM');?>')){Joomla.submitbutton('csv');document.adminForm.task.value='display';return setgood();}" class="anchorbtn btn modal-button btn-small hasPopover" title="" data-content="<?php echo JText::_('JFORMS_CSVBTN_DESC'); ?>" data-original-title="<?php echo JText::_('JFORMS_CSVBTN_LABEL');?>">
 			<?php /* Joomla.submitbutton('formlist.csv'); */ ?>
			<i class="fa fa-file-text"></i><?php echo JText::_('JFORMS_CSVBTN_LABEL');?>
		</a>
	</div>
<?php if ($this->params->get('show_pagination')) : ?>
	<div class="display">
		<?php echo JText::_('JFM_DISPLAY_NUM'); ?>&nbsp;
		<?php echo $this->pagination->getLimitBox(); ?>
	</div>
<?php endif; ?>
<div id="formlist">
	<table class="listtable table table-striped table-bordered table-hover" cellspacing="1" width="<?php echo $this->params->get('tablewidth','100%'); ?>">
<?php if ($this->params->get('show_headings')) : ?>
    <thead>
    <tr>
        <th id="header_0">ID</th>
	<?php foreach($this->labels as $id =>$row) { ?>
		<th id="header_<?php echo $id + 1 ;?>" ><?php echo $this->escape($row->label);?></th>
	<?php } ?>
    </tr>
    </thead>
<?php endif; ?>
    <tbody>
      <?php
      for ($i=0, $n=count( $this->data ); $i < $n; $i++) {
    	$row = $this->data[$i];
	  ?>
		<tr class="bstablline<?php echo ($i+1)%2; ?>">
			<td style="text-align:right"><?php echo $row->id; ?> </td>
		<?php foreach($this->labels as $item) { ?>
			<td >
				<?php if( property_exists($row,$item->item) ) {
					$rowitem = $row->{$item->item};
					if( $item->plugin_name == "file" ) {
						$obj = unserialize(base64_decode($rowitem));
						$linkText = $obj->name;
						echo '<a target="_blank" href="'.JFORMS_FS_URL.$obj->link.'">'.$linkText.'</a>';
					} else {
						echo $this->escape($rowitem); 
					}
				} ?>
			</td>
		<?php } ?>
		</tr>
		<?php } ?>
    </tbody>
	</table>
</div>
<?php if ($this->params->get('show_pagination')) : ?>
<p class="counter">
	<?php echo $this->pagination->getPagesCounter(); ?>
</p>
<div class="pagenavline">
<?php echo $this->pagination->getPagesLinks();?>
</div>
<?php endif; ?>

	<input type="hidden" name="option" value="com_jforms" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="formlist" />
	<input type="hidden" name="limitstart" value="0" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>