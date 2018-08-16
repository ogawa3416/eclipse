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
 * @version		$Id: form.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.3 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;
JHtml::_('formbehavior.chosen', 'select');
require_once( JPATH_COMPONENT.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'dateutil.class.php' );
require_once( JPATH_COMPONENT.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php' );
require_once( JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_bscore'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'calendar_setup.php' ); 
JHTML::stylesheet('components/com_bsbooking/assets/css/layout.css');

$changeUserUrl = 'index.php?option=com_bsbooking&tmpl=component&task=user.getlist';
$linkAttribs = array("class"=>"modallink", "height"=>"480px");
$addmembersUrl = 'index.php?option=com_bsbooking&tmpl=component&task=members.edit';
$memlinkAttribs = array("class"=>"modallink", "height"=>"480px");

$type = $this->reservation->type;
$allow_multi = ($this->reservation->resource->allow_multi == 1);
$display_startdate = DateUtil::formatDate($this->reservation->start_date,'%Y-%m-%d');
$display_enddate = DateUtil::formatDate($this->reservation->end_date,'%Y-%m-%d');
$is_mobile = 0;
?>
<script type="text/javascript">
<!--
var is_mobile = 0;
//console.log('[DEB:is_mobile] ', is_mobile);
if( is_mobile ) {
	(function($){
		$(document).bind('pagecontainershow', function (){
			divh = $('.main-page-header').outerHeight();
			if( ! $('#modalmask').length ) {
				$('.main-page-header').append('<div id="modalmask" style="height:'+divh+'px"></div>');
			}
		});
	})(jQuery);
}

// -->
</script>
<div class="userblock">
<div class="jm_title">
    <h2>
    <strong><?php echo $this->reservation->resource->title?></strong>
    </h2>
</div>
<form name="adminForm" id="resform" action="index.php?option=com_bsbooking" method="post" >
<?php if (empty($this->reservation->id)) : ?>
<div id="bkmainbox" style="width:47%">
<?php else : ?>
<div id="bkmainbox" style="width:100%" >
<?php endif;?>
	<table class="reservation_tbl" width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr class="tableBorder2">
			<td>
				<table width="100%" border="0" cellspacing="1" cellpadding="0">
					<tr>
						<td class="formNames"><?php echo JText::_('LOCATION')?></td>
						<td class="cellColor"><?php echo $this->reservation->resource->location?></td>
					</tr>
					<tr>
						<td class="formNames"><?php echo JText::_('MAX_PARTICIPANTS')?></td>
						<td class="cellColor"><?php echo $this->reservation->resource->max_participants?></td>
					</tr>
					<tr>
						<td class="formNames"><?php echo JText::_('CONTROL_DIVISION')?></td>
						<td class="cellColor"><?php echo $this->reservation->resource->divname?></td>
					</tr>
					<tr>
						<td class="formNames"><?php echo JText::_('PHONE')?></td>
						<td class="cellColor"><?php echo $this->reservation->resource->rphone?></td>
					</tr>
					<tr>
						<td class="formNames"><?php echo JText::_('NOTES')?></td>
						<td class="cellColor"><?php echo $this->reservation->resource->notes?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<table class="reservation_tbl" width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr class="tableBorder2">
			<td>
				<table width="100%" border="0" cellspacing="1" cellpadding="0">
					<tr>
					<?php if ($this->reservation->type == RES_TYPE_ADD ): ?>
						<td colspan="2" class="formNames"> 
							<?php echo JText::_('PLEASE_SELECT_DATE_AND_TIME'); ?>
						</td>
					<?php elseif ($this->reservation->type == RES_TYPE_MODIFY) : ?>
						<td colspan="2" class="formNames">
							<?php echo JText::_('YOU_CAN_CHANGE_TIME_ONLY');  ?>
						</td>
					<?php else : ?>
						<td colspan="2" class="formNames">
							<?php echo JText::_('RESERVED_TIME');  ?>
						</td>
					<?php endif;?>
					</tr>
					<tr>
						<td class="formNames"><?php echo JText::_('START_DATE_TIME');?></td><td class="formNames"><?php echo JText::_('END_DATE_TIME');?></td>
					</tr>
					<tr>
				<?php if ( $type == RES_TYPE_ADD || $type == RES_TYPE_APPROVE || $type == RES_TYPE_MODIFY) :?>
						<td class="formNames dateblock">
							<div id="div_start_date" style="float:left;width:86px;">
								<?php echo $display_startdate?>
							</div>
							<input type="hidden" name="start_date" id="start_date" value="<?php echo DateUtil::formatDate($this->reservation->start_date,'%Y-%m-%d')?>" onchange="checkCalendarDates(this)"/>
					<?php if ($allow_multi) : ?>
							<a href="javascript:void(0);" class="datesubblock"><i class="fa fa-calendar" id="img_start_date" ></i></a>
					<?php endif;?>
							<?php echo BsbookingHelper::getHourSelectList('start_time',$this->reservation->schedule->day_start, 
										$this->reservation->schedule->day_end, $this->reservation->schedule->time_span, 
										$this->reservation->start_time);?>
						</td>
						<td class="formNames dateblock">
							<div id="div_end_date" style="float:left;width:86px;">
								<?php echo $display_enddate?>
							</div>
							<input type="hidden" name="end_date" id="end_date" value="<?php echo DateUtil::formatDate($this->reservation->end_date,'%Y-%m-%d')?>" onchange="checkCalendarDates(this)"/>
						<?php if ($allow_multi) : ?>
							<a href="javascript:void(0);" class="datesubblock"><i class="fa fa-calendar" id="img_end_date" ></i></a>
						<?php endif;?>
							<?php echo BsbookingHelper::getHourSelectList('end_time',$this->reservation->schedule->day_start, 
											$this->reservation->schedule->day_end, $this->reservation->schedule->time_span, 
											$this->reservation->end_time);?>
						</td>
				<?php else: ?>
						<td class="formNames">
							<?php echo $display_startdate." ".DateUtil::formatTime($this->reservation->start_time,false); ?>
						</td>
						<td class="formNames">
							<?php echo $display_enddate." ".DateUtil::formatTime($this->reservation->end_time,false); ?>
						</td>   
				<?php endif; ?>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<table class="reservation_tbl" width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr class="tableBorder2">
			<td>
				<table width="100%" border="0" cellspacing="1" cellpadding="0">

					<tr>
						<td class="formNames"><?php echo JText::_('RES_PRIVATE');?></td>
						<td class="cellColor">
						<?php $checked ='';
							if($this->reservation->private_flg == 1){
								$checked = 'checked="checked"';
							}
						?>
				<?php if ( $type == RES_TYPE_ADD || $type == RES_TYPE_APPROVE || $type == RES_TYPE_MODIFY) :?>
						<input type="hidden" name="private_flg" value="0">
						<input type="checkbox" name="private_flg" value="1" <?php echo $checked ?>/>
				<?php else: ?>
						<input type="checkbox" value="<?php echo $this->reservation->private_flg ?>" <?php echo $checked ?> disabled='disabled'/>
				<?php endif; ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<table class="reservation_tbl" width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr class="tableBorder2">
			<td>
				<table width="100%" border="0" cellspacing="1" cellpadding="0">
					<tr>
						<td colspan="2" class="formNames" ><?php echo JText::_('RESERVED_FOR');?></td>
					</tr>
					<tr>
						<td class="formNames"><?php echo JText::_('FULL_NAME');?></td>
						<td class="cellColor" id="reserved_for_fullname">
						<span id="reserved_for_name"><?php echo $this->reservation->user->name?></span> 
							&nbsp; 
						<?php
							if ($this->reservation->type != RES_TYPE_VIEW) {
								echo BscoreHelper::modal($changeUserUrl, JText::_('CHANGE'), $linkAttribs,'modal-userch');
							}
						?>
						</td>
					</tr>
					<tr>
						<td class="formNames"><?php echo JText::_('DIVISION');?></td>
						<td class="cellColor" id="reserved_for_divname"><?php echo $this->reservation->divname;?></td>
					</tr>
					<tr>
						<td class="formNames">E-mail</td><td class="cellColor" id="reserved_for_email"><?php echo $this->reservation->user->email?></td>
					</tr>
					<tr>
						<td class="formNames"><?php echo JText::_('TELEPHONE_NO');?></td><td class="cellColor" id="reserverved_for_telephone"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
			
	<table class="reservation_tbl" width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr class="tableBorder2">
			<td>
				<table width="100%" border="0" cellspacing="1" cellpadding="0">
					<tr>
						<td class="formNames"><?php echo JText::_('COM_BSBOOKING_MEMBERS');?>
							<br />
						<?php
							if ($this->reservation->type != RES_TYPE_VIEW) {
								echo BscoreHelper::modal($addmembersUrl, JText::_('COM_BSBOOKING_ADDMEMBER'), $memlinkAttribs,'modal-ulist');
							}
						?>
						</td>
						<td class="cellColor" id="bookingmembers">
							<span id="bookingmembersname"><?php echo $this->reservation->dispmembers?></span> 
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
<?php if( !$is_mobile ) : ?>
	<table class="reservation_tbl" width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr class="tableBorder2">
			<td>
				<table width="100%" border="0" cellspacing="1" cellpadding="0">
					<tr>
						<td class="formNames"><?php echo JText::_('SUMMARY');?></td>
					</tr>
					<tr>
						<td class="cellColor">
					<?php if ($this->reservation->type == RES_TYPE_ADD || $this->reservation->type== RES_TYPE_MODIFY || $this->reservation->type == RES_TYPE_APPROVE) : ?>
							<textarea name="summary" rows="3" cols="46"><?php echo $this->reservation->summary?></textarea>
					<?php else : ?>
							<?php echo $this->reservation->summary ?>
					<?php endif ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
<?php endif ?>
</div>
<div id="sch_checkbox" >
<?php if (empty($this->reservation->id)) : ?>
	<table border="0" cellspacing="0" cellpadding="0" class="recur_box" id="repeat_table">
		<tr>
			<td>
				<div id="bkptblock" >
				<div id="subtitle"><h3><?php echo JText::_('REPEAT_EVERY')?></h3></div>
				<select name="frequency" class="textbox selectrepeat">
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
				</select>
				<select name="interval" class="textbox selectinterval" onchange="showHideDays(this,<?php echo (int)$is_mobile;?>);">
					<option value="none"><?php echo JText::_('NEVER')?></option>
					<option value="day"><?php echo JText::_('DAYS')?></option>
					<option value="week"><?php echo JText::_('WEEKS')?></option>
					<option value="month_date"><?php echo JText::_('MONTHS_DATE')?></option>
					<option value="month_day"><?php echo JText::_('MONTHS_DAY')?></option>
				</select>
				</div>
				<div id="week_num" style="position: relative; visibility: hidden; overflow: show; display: none;">
					<p>
						<select name="week_number" class="textbox">
							<option value="1"><?php echo JText::_('FIRST_DAYS')?></option>
							<option value="2"><?php echo JText::_('SECOND_DAYS')?></option>
							<option value="3"><?php echo JText::_('THIRD_DAYS')?></option>
							<option value="4"><?php echo JText::_('FOURTH_DAYS')?></option>
<?php /***
	                       <option value="last"><?php echo JText::_('LAST_DAYS')?></option>
***/ ?>
						</select>
					</p>
				</div>
				<div id="days" style="position: relative; visibility: hidden; overflow: show; display: none;">
					<div id="dayslab"><i class="fa fa-check-circle"></i>
						<span>&nbsp;<?php echo JText::_('REPEAT_ON')?></span> 
					</div>
					<div  data-role="fieldcontain"> 
						<fieldset data-role="controlgroup"> 
							<div class="dayow_label"><input type="checkbox" name="repeat_day[]" id="checkbox-1" value="0" onclick="selectHideWeek(this,0,<?php echo (int)$is_mobile;?>);" data-mini="true" /><label for="checkbox-1"><?php echo JText::_('SUNDAY')?></label></div> 
							<div class="dayow_label"><input type="checkbox" name="repeat_day[]" id="checkbox-2" value="1" onclick="selectHideWeek(this,1,<?php echo (int)$is_mobile;?>);" data-mini="true" /><label for="checkbox-2"><?php echo JText::_('MONDAY')?></label></div> 
							<div class="dayow_label"><input type="checkbox" name="repeat_day[]" id="checkbox-3" value="2" onclick="selectHideWeek(this,2,<?php echo (int)$is_mobile;?>);" data-mini="true" /><label for="checkbox-3"><?php echo JText::_('TUESDAY')?></label></div> 
							<div class="dayow_label"><input type="checkbox" name="repeat_day[]" id="checkbox-4" value="3" onclick="selectHideWeek(this,3,<?php echo (int)$is_mobile;?>);" data-mini="true" /><label for="checkbox-4"><?php echo JText::_('WEDNESDAY')?></label></div> 
							<div class="dayow_label"><input type="checkbox" name="repeat_day[]" id="checkbox-5" value="4" onclick="selectHideWeek(this,4,<?php echo (int)$is_mobile;?>);" data-mini="true" /><label for="checkbox-5"><?php echo JText::_('THURSDAY')?></label></div> 
							<div class="dayow_label"><input type="checkbox" name="repeat_day[]" id="checkbox-6" value="5" onclick="selectHideWeek(this,5,<?php echo (int)$is_mobile;?>);" data-mini="true" /><label for="checkbox-6"><?php echo JText::_('FRIDAY')?></label></div> 
							<div class="dayow_label"><input type="checkbox" name="repeat_day[]" id="checkbox-7" value="6" onclick="selectHideWeek(this,6,<?php echo (int)$is_mobile;?>);" data-mini="true" /><label for="checkbox-7"><?php echo JText::_('SATURDAY')?></label></div> 
						</fieldset>
					</div>
				</div>
				<div id="until_div" style="position: relative; visibility: hidden; overflow: show; display: none;">
					<div><strong><?php echo JText::_('REPEAT_UNTIL_DATE')?></strong></div>
					<div id="until_lab"><?php echo JText::_('CHOOSE_DATE')?> : &nbsp;</div>
					<div id="div_repeat_until" ></div><input type="hidden" name="repeat_until" id="repeat_until" value=""/>
					<a href="javascript:void(0);"><i class="fa fa-calendar" id="img_repeat_until_date" ></i></a>
				</div>
			</td>
		</tr>
	</table>
<?php endif ?>
</div>
<?php if( $is_mobile ) : ?>
<div id="sch_summary" >
	<table class="reservation_tbl" width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr class="tableBorder2">
			<td>
				<table width="100%" border="0" cellspacing="1" cellpadding="0">
					<tr>
						<td class="formNames"><?php echo JText::_('SUMMARY');?></td>
					</tr>
					<tr>
						<td class="cellColor">
					<?php if ($this->reservation->type == RES_TYPE_ADD || $this->reservation->type== RES_TYPE_MODIFY || $this->reservation->type == REST_TYPE_APPROVE) : ?>
							<textarea name="summary" rows="4" cols="46" placeholder="<?php echo JText::_('PLEASE_ENTER_SUMMARY_TEXT')?>"><?php echo $this->reservation->summary?></textarea>
					<?php else : ?>
							<?php echo $this->reservation->summary ?>
					<?php endif ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
<?php endif; ?>
<div class="clr" ></div>
<?php if ( $this->reservation->type == RES_TYPE_MODIFY )  : ?>
<div id="bssbox" data-role="fieldcontain"> 
	<fieldset data-role="controlgroup"> 
		<input type="checkbox" name="del" id="del" value="1" data-mini="true"/><label for="del"><?php echo JText::_('JACTION_DELETE');?>?</label>
		<input type="checkbox" name="include_child" id="include_child" value="1" data-mini="true"/><label for="include_child"><?php echo JText::_('UPDATE_ALL_RECURRING_RECORDS_IN_GROUP');?>?</label>
	</fieldset>
</div>
<?php endif ?>
<div class="clr"></div>
<input type="hidden" id="tmpmembers" value="" />
<input type="hidden" id="members" name="members" value="<?php echo $this->reservation->members ?>" />
<input type="hidden" name="id" value="<?php echo $this->reservation->id?>" />
<input type="hidden" name="schedule_id" value="<?php echo $this->reservation->schedule_id?>" />
<input type="hidden" name="resource_id" value="<?php echo $this->reservation->resource_id?>" />
<input type="hidden" name="reserved_for" id="reserved_for" value="<?php echo $this->reservation->user->id?>" />
<input type="hidden" name="is_blackout" value="<?php echo $this->reservation->is_blackout?>" />
<input type="hidden" name="task" value="reservation.save" />
<input type="hidden" name="ret" value="<?php echo $this->ret;?>" />
<?php if ($type == RES_TYPE_ADD) : ?>
<input type="hidden" name="fn" value="create" />
<?php elseif ($type == RES_TYPE_MODIFY) : ?>
<input type="hidden" name="fn" value="modify" />
<?php elseif ($type == RES_TYPE_DELETE) : ?>
<input type="hidden" name="fn" value="delete" />
<?php elseif ($type == RES_TYPE_APPROVE) : ?>
<input type="hidden" name="fn" value="approve" />
<?php elseif ($type == RES_TYPE_VIEW) : ?>
<input type="hidden" name="fn" value="view" />
<?php endif; ?>
<div class="resbuton">
<?php if ($type !== RES_TYPE_VIEW) : ?>
<button type="button" name="save"  onclick="bssubmitform('reservation.save',this.form);"/><i class="icon-save"></i><?php echo JText::_('JSAVE');?></button>
<?php endif; ?> 
<button type="button" name="cancel"  onclick="Joomla.submitform('reservation.back',this.form);" /><i class="icon-cancel"></i><?php echo JText::_('JCANCEL');?></button>
<div class="clr"></div>
</div>
<div id="checkDiv" style="display:none;width:100%;padding-top:15px;"></div>
<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
<script type="text/javascript">
<!--
var now = new Date(<?php echo date("Y").','.date("m").','.date("d") ?>);
<?php if ($allow_multi && ($type == RES_TYPE_ADD || $type == RES_TYPE_MODIFY)) :?>
//Start date calendar
jQuery(document).ready(function($) {
Calendar.setup(
    {
    inputField : "start_date", // ID of the input field
    ifFormat : "%Y-%m-%d", // the date format
    daFormat : "%Y-%m-%d", // the date format
    button : "img_start_date", // ID of the button
    align: "Tl",
    firstDay:<?php echo JFactory::getLanguage()->getFirstDay()?>,
    date : now,
    displayArea : "div_start_date",
    dateStatusFunc: ourDateStatusFunc
    }
)});
// End date calendar
jQuery(document).ready(function($) {
Calendar.setup(
    {
    inputField : "end_date", // ID of the input field
    ifFormat : "%Y-%m-%d", // the date format
    daFormat : "%Y-%m-%d", // the date format
    button : "img_end_date", // ID of the button
    firstDay:<?php echo JFactory::getLanguage()->getFirstDay()?>,
    date : now,
    displayArea : "div_end_date",
    dateStatusFunc: ourDateStatusFunc
    }
)});
<?php endif ?>
<?php if ($type == RES_TYPE_ADD) : ?>
// Repeat until date calendar
jQuery(document).ready(function($) {
Calendar.setup(
    {
    inputField : "repeat_until", // ID of the input field
    ifFormat : "%Y-%m-%d", // the date format
    daFormat : "%Y-%m-%d", // the date format
    button : "img_repeat_until_date", // ID of the button
    firstDay:<?php echo JFactory::getLanguage()->getFirstDay()?>,
    date : now,
    displayArea : "div_repeat_until",
    dateStatusFunc: ourDateStatusFunc
    }
)});
<?php endif; ?>
    
function updateReservedFor(user_id, user_name, user_email,user_divname){
    var old_user_id = document.getElementById('reserved_for').value;
    if (old_user_id != user_id)
    {
        document.getElementById('reserved_for').value = user_id;
        document.getElementById('reserved_for_name').innerHTML = user_name;
        document.getElementById('reserved_for_email').innerHTML = user_email;
        document.getElementById('reserved_for_divname').innerHTML = user_divname;
    } 
    return true;
}
/*
 Reservation form validation
 Status : Tested
*/
function bssubmitform(task,f) {
	var recur_ok = false;
	var days_ok = false;
	var is_repeat = false;
	var msg = "";
	if ((typeof f.interval != 'undefined') && f.interval.value != "none") {
		is_repeat = true;
		if (f.interval.value == "week" || f.interval.value == "month_day") {
			for (var i=0; i < f.elements["repeat_day[]"].length; i++) {
				if (f.elements["repeat_day[]"][i].checked == true)
					days_ok = true;
			}
		}
		else {
			days_ok = true;
		}
		
		if (f.repeat_until.value == "") {
			msg += "<?php echo JText::_('PLEASE_CHOOSE_AN_ENDING_DATE'); ?>\n";
			recur_ok = false;
		}else{
            var start_date = new String(f.start_date.value);
		    var end_date = new String(f.repeat_until.value);
            var startDs = start_date.split('-');
            var endDs = end_date.split('-');
            var startDate = new Date(startDs[0], startDs[1], startDs[2]);
            var endDate = new Date(endDs[0], endDs[1], endDs[2]);
            var today = new Date();

            if (startDate < today) {
                msg += "<?php echo JText::_('START_DATE_CANNOT_LESS_THAN_TODAY'); ?>\n";   
            }else if (endDate < today){
                msg += "<?php echo JText::_('UNTIL_DATE_CANNOT_LESS_THAN_TODAY'); ?>\n";
            }else if (startDate > endDate) {
                msg += "<?php echo JText::_('UNTIL_DATE_CANNOT_LESS_THAN_START_DATE'); ?>\n";
            }
            
		}
	}
	else {
        var start_date = new String(f.start_date.value);
        var startDs = start_date.split('-');
        var startDate = new Date(startDs[0], startDs[1], startDs[2]);
        var today = new Date();
    <?php if ( $this->reservation->type == RES_TYPE_MODIFY )  : ?>
		if( f.include_child.checked && (f.start_date.value != f.end_date.value)) {
			alert("<?php echo JText::_('COM_BSBOOKING_CANNOT_REPEAT'); ?>");
			return false;
		} 
	<?php endif ?>
		if (startDate < today){
			msg += "<?php echo JText::_('Reservation date cannot less than today'); ?>\n";
		}
		recur_ok = true;
		days_ok = true;
	}
	
	if (days_ok == false) {
		recur_ok = false;
		msg += "<?php echo JText::_('RESERVATION_DATE_CANNOT_LESS_THAN_TODAY');?>\n";
	}
	if( f.start_date.value > f.end_date.value ){
		msg += "<?php echo JText::_('COM_BSBOOKING_ERROR_ENDDATE'); ?>\n";
	}
    if ( (parseInt(f.start_time.value) >= parseInt(f.end_time.value)) && (f.start_date.value==f.end_date.value) ) {
        msg += "<?php echo JText::_('INVALID_TIME_RANGE_PLEASE_VERIFY'); ?>\n";
    }
    if (f.summary.value ==""){
        msg += "<?php echo JText::_('PLEASE_ENTER_SUMMARY_TEXT'); ?>\n";
    }
    
	if (msg != "") {
		alert(msg);
		return false;
	}
	if( task == 'reservation.save' ) {
		//Max member check
		var max_cmf = false;
		var maxpart = '<?php echo $this->reservation->resource->max_participants?>';
		if(parseInt(maxpart,10) > 0 ){
			var member = document.getElementById('bookingmembersname').innerHTML;
			if(member != ''){
				member = member.split(",");
				if (maxpart < member.length){
					max_cmf = true;
					if( !confirm("<?php echo JText::_('PLEASE_CHECK_MAX_PARTICIPANTS');?>") ) return false;
			    	}
			}
		}

		if(max_cmf == false){
			if( !confirm("<?php echo JText::_('COM_BSBOOKING_REALLYSAVE_QUESTION');?>") ) return false;
		}
	}
	Joomla.submitform(task,f);

	<?php $alert_msg_arr = trim($this->reservation->resource->alert_msg);
	if($alert_msg_arr != '' && $type == RES_TYPE_ADD) :?>
	alert('<?php echo $alert_msg_arr; ?>');
	<?php endif; ?>

	return (msg == "");
}
// -->
</script>