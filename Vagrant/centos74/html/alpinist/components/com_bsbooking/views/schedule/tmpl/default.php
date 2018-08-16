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
 * @version		$Id: default.php 120 2012-04-01 22:52:24Z BsAlpinist ver.2.3.3 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;
JHtml::_('formbehavior.chosen', 'select');
require_once( JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_bscore'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php' );
JHTML::stylesheet('components/com_bsbooking/assets/css/rounded.css');
JHTML::stylesheet('components/com_bsbooking/assets/css/layout.css');
?>

<?php if ($this->apparams->get('show_page_title',1)) : ?>
<h2 class="componentheading<?php echo $this->escape($this->apparams->get('pageclass_sfx')); ?>">
	<strong><?php echo $this->escape($this->apparams->get('page_title')); ?></strong>
</h2>
<?php endif; ?>
<div id="toolbar-box">
	 <div id="mylistbtn">
		<button class="btn-small button" type="button" onclick="location.href='<?php echo $this->dashboardUrl; ?>'" ><i class="icon-chevron-right "></i><?php echo JText::_('COM_BSBOOKING_DASHBOARD') ?></button>
    </div>
<form action="<?php echo $this->action ?>" method="post" name="adminForm" id="adminForm" class="form-validate" >
<div class="mainfilter">
	<div class="hdselectlist" >
		<label for="division"><?php echo JText::_('SELECT_DIVISION'); ?> </label>
		<?php 
			$att = 'onchange="submit(this.form);"';
			echo BscoreHelper::divcodelist( 'divcode',$this->schedule->getDivcode(),$att,'divcode',1 );
		?>
	</div>
	<div class="hdselectlist" id="jumpdate">
		<label for="date" style="display:none;"><?php echo  JText::_('COM_BSBOOKING_JUMP_TO_DATE');?></label>
		<div id="srchdatebtn">
		<?php
			$date = "";
			echo JHTML::_('calendar', $date, 'date', 'date', "%Y-%m-%d", 
					array('class'=>'inputbox validate-date', 'size'=>'14',  'maxlength'=>'10' ));
		?>
		</div>
		<span class="ButtonInput"><span>
			<button type="button" class="btn-small button" onclick="Joomla.submitbutton();" ><i class="icon-search"></i><?php echo JText::_('COM_BSBOOKING_JUMP_TO_DATE'); ?></button>
		</span></span>
	</div>
</div>
<input type="hidden" id="drag_rsvid" value="" />
<input type="hidden" id="drag_rsvspan" value="" />
<input type="hidden" id="drag_move" value="" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
<div class="clr"></div>
<div class="bookingview">
<?php
$this->schedule->render($this->apparams);
BsbookingHelper::print_jump_links($this->schedule, true);
?>
</div>
<script language="javascript" type="text/javascript">

    function onSuccessReservation()
    {
		submitform();
<?php /* if ($this->browserType == 'msie') : */ ?>
<?php /*        setTimeout("window.location.reload()", 200); */ ?>
<?php /* else: */ ?>
<?php /*        setTimeout("document.location.reload()", 200); */?>    
<?php /* endif */?>
    };
function setgood() {
	// TODO: Put setGood back
	return false;
}
/**
window.addEvent('domready', function(){
**/
jQuery(function() {
	document.formvalidator.setHandler('date', function (value) {
		return isValidDate(value,0);});});
function isValidDate(dstr,f)
{
	if( !dstr || dstr.length == 0 ) {
		if( f!=1 ) {  // not required
			return true;
		}  
	}	
	regex=/\d{4}-\d{1,2}-\d{1,2}$/;
	if( !regex.test(dstr) ) return false;
	r = dstr.match(/\d+/g);
    if(r){
		if( r.length == 3 ) {
			var di = new Date(r[0],r[1]-1,r[2]);
			if(di.getFullYear() == r[0] && di.getMonth() == r[1]-1 && di.getDate() == r[2]){
				return true;
			}
		}
	}		
	return false;
}
Joomla.submitbutton = function()
{
	var form = document.adminForm;
	if(!document.formvalidator.isValid(form)) {
		return false;
	}
	Joomla.submitform();
}

function rsvedDragStart(id,rsvspan,tmspan){
	document.getElementById('drag_rsvid').value = id ;
	document.getElementById('drag_rsvspan').value = rsvspan ;

	// マウス位置を取得する
	var mouseX = event.pageX ;	// X座標
	var mouseY = event.pageY ;	// Y座標

	// 要素の位置を取得する
	var element = document.getElementById( "reservid_" + id ) ;
	var rect = element.getBoundingClientRect() ;

	// 座標を計算する
	var positionX = rect.left + window.pageXOffset ;	// 要素のX座標
	var positionY = rect.top + window.pageYOffset ;	// 要素のY座標

	//1つのセル幅
	cellwidth = rect.width / parseInt(element.colSpan,10)

	// 要素の左上からのマウス距離を計算
	var offsetX = mouseX - positionX ;

	//小数点切り捨て
	cellmove = Math.floor(offsetX / cellwidth);

	//移動先先頭調整
	document.getElementById('drag_move').value = cellmove * tmspan;


}

function rsvedDrop(resourceid,scheduleid,ts,tstart,tend ){

	rsvspan = document.getElementById('drag_rsvspan').value;

	//予約開始位置調整
	tstart = parseInt(tstart,10) - parseInt(document.getElementById('drag_move').value,10);

	tend = parseInt(tstart,10) + parseInt(rsvspan,10);

	ajaxRsvMove(resourceid,scheduleid,ts,tstart,tend);
};
function rsvedDropself(id,resourceid,scheduleid,tstart,ts,tmspan){

	if(document.getElementById('drag_rsvid').value != id){
		return;
	}
	//自身の予約と重なる部分がある場合の変更
	// マウス位置を取得する
	var mouseX = event.pageX ;	// X座標
	var mouseY = event.pageY ;	// Y座標

	// 要素の位置を取得する
	var element = document.getElementById( "reservid_" + id ) ;
	var rect = element.getBoundingClientRect() ;

	// 座標を計算する
	var positionX = rect.left + window.pageXOffset ;	// 要素のX座標
	var positionY = rect.top + window.pageYOffset ;	// 要素のY座標

	//1つのセル幅
	cellwidth = rect.width / parseInt(element.colSpan,10)

	// 要素の左上からのマウス距離を計算
	var offsetX = mouseX - positionX ;

	//小数点切り捨て
	cellmove = Math.floor(offsetX / cellwidth);

	//移動先スパン算出
	prepos = document.getElementById('drag_move').value;
	dragpos = cellmove * tmspan;
	movespan = parseInt(prepos,10) - parseInt(dragpos,10);

	rsvspan = document.getElementById('drag_rsvspan').value;

	//予約開始位置調整
	tstart = parseInt(tstart,10) - movespan;
	tend = parseInt(tstart,10) + parseInt(rsvspan,10);

	ajaxRsvMove(resourceid,scheduleid,ts,tstart,tend);

}
function addmodSchedule(msg,success){

	if(success == false){
		msg = msg.replace(/(<br>|<br \/>)/gi, '\n');
		alert(msg);
	}

	if(success == true){
		window.parent.location.reload();
	}

}
function ajaxRsvMove(resourceid,scheduleid,ts,tstart,tend){
	moveid = document.getElementById('drag_rsvid').value ;

	var ajax_location = 'index.php?option=com_bsbooking&task=reservation.rsvMove';

	jQuery.ajax({
		type: 'POST',
		url:ajax_location,
		data: {
			'moveid':moveid,
			'resource_id':resourceid,
			'schedule_id':scheduleid,
			'ts':ts,
			'tstart':tstart,
			'tend':tend,
			'<?php echo JSession::getFormToken()?>': 1,
		},
		success:function (data) {

			addmodSchedule(data[0],data[1]);
		 },
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			alert(XMLHttpRequest.responseText);
		},
			dataType:"json"
	});

	return;

}

</script>
