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
 * @version		$Id: default.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.4.0 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;
JHtml::_('formbehavior.chosen', 'select');
jimport('joomla.utilities.date'); 
$is_mobile = 0;
$app = JFactory::getApplication();
$input = $app->input;
$itemId = $input->getInt('Itemid',null);
$append = '';
if(isset($itemId)) $append = '&Itemid=' . $itemId;
?>
<script>
	function bssubmitbutton(task){
		if (task=='reservation.back') {
        	Joomla.submitform(task);
        	return true;
        }
        
        var id = 0;
        /* Get elements having name starting word "cid" */
        var elements = document.getElementsByName('cid[]');
        for (var i=0; i < elements.length; i++){
            if (elements[i].checked) {
                id = elements[i].value;
                break;    
            }
        }
        if(task=='reservation.remove') {
        	if( id ) {
        		if( confirm("<?php echo JText::_('COM_BSBOOKING_REALLYDELETE_QUESTION');?>") )
					Joomla.submitform(task);
				return true;
			} else {
				alert("<?php echo JText::_('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST');?>");
				return true;
			}
        }
		
        return false;
    }
</script>
<?php if ($this->apparams->get('show_page_title',1)) : ?>
<h2 class="componentheading<?php echo $this->escape($this->apparams->get('pageclass_sfx')); ?>">
	<strong><?php echo $this->escape($this->apparams->get('page_title')); ?></strong>
</h2>
<?php endif; ?>
<form name="adminForm" id="adminForm" action="<?php echo JRoute::_('index.php?option=com_bsbooking&task=dashboard.display&id='.$this->scheduleid.$append,false)?>" method="post">
<div id="jm-myres-dashboard">
    <div id="opebtn">
    	<button class="btn-danger" type="button" onclick="bssubmitbutton('reservation.remove');"  data-inline="true"  ><i class="icon-delete"></i><?php echo JText::_('JACTION_DELETE') ?></button>
		<button class="button" type="button" onclick="bssubmitbutton('reservation.back');"  data-inline="true"  ><i class="icon-undo"></i><?php echo JText::_('COM_BSBOOKING_BACK') ?></button>
    </div>
    <div class="br"></div>
    <div id="jm-filter" data-role="fieldcontain"> <label for="search"><?php echo JText::_('SEARCH_IN_SUMMARY_TEXT'); ?>&nbsp; : &nbsp; </label><input type="text" name="search" value="<?php echo $this->searchword;?>" onchange="this.form.submit();" data-inline="true"/></div>	
	<div class="display">
		<?php echo JText::_('COM_BSBOOKING_DISPNUM'); ?>&nbsp;
		<?php echo $this->pagination->getLimitBox(); ?>
	</div>

    <table class="category table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th id="bsscatlab1" class="bstableheader"><input type="checkbox" name="toggle" onclick="Joomla.checkAll(this)"/></th>
            <th id="bsscatlab2" class="bstableheader">#</th>
            <th id="bsscatlab3" class="bstableheader"><?php echo JHTML::_('grid.sort',  JText::_('COM_BSBOOKING_RESOURCE_NAME'), 'resource_name', $this->sorting->filter_order_Dir, $this->sorting->filter_order)?></th>
<?php if( !$is_mobile ) : ?>
			<th id="bsscatlab4" class="bstableheader"><?php echo JHTML::_('grid.sort', JText::_('COM_BSBOOKING_START_DATETIME'), 'start_datetime', $this->sorting->filter_order_Dir, $this->sorting->filter_order)?></th>
            <th id="bsscatlab5" class="bstableheader"><?php echo JHTML::_('grid.sort', JText::_('COM_BSBOOKING_END_DATETIME'), 'end_datetime', $this->sorting->filter_order_Dir, $this->sorting->filter_order)?></th>
            <th id="bsscatlab6" class="bstableheader"><?php echo JText::_('RESERVED_BY'); ?></th>
<?php else : ?>
			<th id="bsscatlab8" class="bstableheader"><?php echo JHTML::_('grid.sort', RESERVED_TIME, 'start_datetime', $this->sorting->filter_order_Dir, $this->sorting->filter_order)?></th>
<?php endif; ?>
            <th id="bsscatlab7" class="bstableheader"><?php echo JText::_('SUMMARY_TEXT');?></th>
        </tr>
    </thead>
    <tbody>
<?php 
$k = 1; $i = -1;
foreach ($this->rows as $row) :
    $k = 1 - $k; $i++;
    $checkbox = JHTML::_('grid.id', $i, $row->id, false, 'cid' );
?>
        <tr class="bstableentry<?php echo $k+1?>">
            <td class="bsscatcal1" ><?php echo $checkbox?></td>
            <td class="bsscatcal2" >
                <?php echo $this->pagination->getRowOffset($i)?>
            </td>
            <td><?php 
            	$link = "index.php?option=com_bsbooking&task=reservation.edit&type=m&id=".$row->id."&Itemid=".$input->getInt('Itemid');
            	?> 
            	<a href="<?php echo $link;?>"><?php echo htmlspecialchars($row->resource_name); ?></a>
            </td>
<?php if( !$is_mobile ) : ?></td><td><?php else: ?><td class="bssresfromto"><?php endif; ?>
            	<?php echo DateUtil::formatDate($row->start_date,'%Y-%m-%d').' '.DateUtil::formatTime($row->start_time,false)?>
<?php if( !$is_mobile ) : ?></td><td><?php else: ?><span class="bssresend">&nbsp;-&nbsp;<?php endif; ?>
            	<?php echo DateUtil::formatDate($row->end_date,'%Y-%m-%d').' '.DateUtil::formatTime($row->end_time,false)?>
<?php if( !$is_mobile ) : ?></td><?php else: ?></span></td><?php endif; ?>
<?php if( !$is_mobile ) : ?>
            <td><?php echo $row->reserved_by_name ?></td>
<?php endif; ?>
            <td><?php echo mb_substr(htmlspecialchars($row->summary), 0, 20)?></td>   
        </tr>
<?php endforeach?>
	</tbody>
    </table>
	<p class="counter">
		<?php echo $this->pagination->getPagesCounter(); ?>
	</p>
	<div class="pagenavline">
	<?php echo $this->pagination->getPagesLinks();?>
	</div>
    <input type="hidden" name="task" value="dashboard.display" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $this->sorting->filter_order?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->sorting->filter_order_Dir?>" />
    <input type="hidden" name="limitstart" value="" />
    <input type="hidden" name="schedule_id" value="<?php echo $this->scheduleid?>" />
    <input type="hidden" name="return_task" value="dashboard.display" />
</div>
</form>
