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
 * @version		$Id: default.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.0 $
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
$app = JFactory::getApplication();
$input = $app->input;
$itemId = $input->getInt('Itemid',null);
$append = '';
if(isset($itemId)) $append = '&Itemid=' . $itemId;
?>
<form name="adminForm" id="adminForm" action="<?php echo JRoute::_('index.php?option=com_bsbooking&task=reservations.getlist&id='.$this->scheduleid.$append,false)?>" method="post">
    <div id="jm-filter">Search in Summary Text : <input type="text" name="search" value="<?php echo $this->searchword;?>" onchange="this.form.submit();"/></div>
    <table class="adminlist">
    <thead>
        <tr>
            <th width="3"><input type="checkbox" name="toggle" onclick="Joomla.checkAll(this)"/></th>
            <th width="3">#</th>
            <th><?php echo JHTML::_('grid.sort', com_bsbooking_RESOURCE_NAME, 'resource_name', $this->sorting->filter_order_Dir, $this->sorting->filter_order)?></th>
            <th><?php echo JHTML::_('grid.sort', com_bsbooking_START_DATETIME, 'start_datetime', $this->sorting->filter_order_Dir, $this->sorting->filter_order)?></th>
            <th><?php echo JHTML::_('grid.sort', com_bsbooking_END_DATETIME, 'end_datetime', $this->sorting->filter_order_Dir, $this->sorting->filter_order)?></th>
            <th><?php echo JHTML::_('grid.sort', com_bsbooking_RESERVED_FOR_NAME, 'reserved_for_name', $this->sorting->filter_order_Dir, $this->sorting->filter_order)?></th>
            <th>Reserved By</th>
            <th>Summary Text</th>
        </tr>
    </thead>
    <tbody>
<?php 
$k = 1; $i = -1;
foreach ($this->rows as $row) :
    $k = 1 - $k; $i++;
    $oStartDate = new JDate($row->start_datetime);
    $oEndDate = new JDate($row->end_datetime);
    $oCreatedDate = new JDate($row->created);
    $checkbox = JHTML::_('grid.id', $i, $row->id, false, 'cid' );
?>
        <tr class="row<?php echo $k?>">
            <td><?php echo $checkbox?></td>
            <td>
                <?php echo $this->pagination->getRowOffset($i)?>
            </td>
            <td><?php echo $row->resource_name?></td>
            <td><?php echo $oStartDate->format("Y-m-d H:i")?></td>
            <td><?php echo $oEndDate->format("Y-m-d H:i")?></td>
            <td><?php echo $row->reserved_for_name?></td>
            <td><?php echo $row->reserved_by_name.' @ '.$oCreatedDate->format("Y-m-d H:i")?></td>
            <td><?php echo mb_substr(htmlspecialchars($row->summary), 0, 20)?></td>   
        </tr>
    </tbody>
<?php endforeach?>
    <tfoot>
        <tr>
            <td colspan="9"><?php echo $this->pagination->getListFooter()?></td>
        </tr>
    </tfoot>
    </table>
    <input type="hidden" name="task" value="reservations.getlist" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $this->sorting->filter_order?>" />
    <input type="hidden" name="filter_order_Dir" value="" />
    <input type="hidden" name="schedule_id" value="<?php echo $this->scheduleid?>" />
    <input type="hidden" name="return_task" value="reservations.getlist" />
</form>
<script type="text/javascript">
    /* Override toolbar 's method */
	Joomla.submitbutton = function(task){
        var id = 0;
        
        if (task=='reservation.remove') {
            Joomla.submitform(task);
            return true;
        }
        /* Get elements having name starting word "cid" */
        var elements = $('adminForm').getElements('input[name^=cid]');
        for (var i=0; i < elements.length; i++){
            if (elements[i].checked) {
                id = elements[i].value;
                break;    
            }
        }
        if (task=='reservation.edit') {
            var url = '<?php echo JRoute::_("index.php?option=com_bsbooking&task=reservation.edit&type=m",false)."&id="?>'+id;
        }else{
            var url = '<?php echo JRoute::_("index.php?option=com_bsbooking&task=reservation.view&type=v",false)."&id="?>'+id;
        }
        location.href=url;
        return false;
    }
    /* Called by SqueezeBox to refresh opener */
    function onSuccessReservation() {
        document.adminForm.submit();
    }
</script>