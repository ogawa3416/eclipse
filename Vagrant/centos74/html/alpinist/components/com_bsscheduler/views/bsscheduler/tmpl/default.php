<?php 
/**
* @Copyright Copyright (C) 2010 dhtmlx.com
* @Copyright Copyright (C) 2010 groon.co.jp (by modified portion)
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

/**
 This file is part of BSScheduler for Joomla.

    BSScheduler for Joomla is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    BSScheduler for Joomla is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with BSScheduler for Joomla.  If not, see <http://www.gnu.org/licenses/>.
**/
// no direct access
defined('_JEXEC') or die;
JHtml::_('formbehavior.chosen', 'select');
require_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php');
JHTML::stylesheet('components/com_bsscheduler/assets/css/bsscheduler.css' );

jimport( 'joomla.application.web.webclient' );
$changeUserUrl = 'index.php?option=com_bsscheduler&tmpl=component&task=getulist';
$linkAttribs = array("class"=>"modallink", "height"=>"480px");
?>
<script language="javascript" type="text/javascript">
<!--
function setgood() {
	// TODO: Put setGood back
	return false;
}
function bsssubmitform(f) {
	var idx = f.useridx.selectedIndex;
	var uid = f.useridx.options[idx].value;
	document.getElementById('userid').value = uid;
	document.hiddenForm.submit(f);
}

function updateReservedFor(user_id, user_name,user_divname){
//    var old_user_id = document.getElementById('userid').value;
//    if (old_user_id != user_id)
//    {
        document.getElementById('userid').value = user_id;
//		document.getElementById('username').innerHTML = user_name;
        document.getElementById('userlabel').innerHTML = "<?php echo JText::_('CHANGE_USER_NOW'); ?>";
        document.getElementById('divname').innerHTML = "";
		document.hiddenForm.submit();
//	}
	return true;
}
function resetuser() {
	document.getElementById('userid').value = bssgetmyuserid();
	document.hiddenForm.submit(this.form);
}
function bssgetmyuserid() {
	var myid = "<?php echo $this->myuserid; ?>";
	return myid;
}
function bssgetmyusername() {
	var myname = "<?php echo $this->myname; ?>";
	return myname;
}
function bssgetdivlist() {
	<?php $rows = BsschedulerHelper::getDivlist();
	for($i=0;$i<count($rows);$i++) {
		$dc[$i] = $rows[$i]->divcode;
		$dn[$i] = $rows[$i]->divname_s;
	} ?>
	var dcstr = "<?php if( count($dc) ) echo implode(",",$dc); else echo ""; ?>";
	var dnstr = "<?php if( count($dn) ) echo implode(",",$dn); else echo ""; ?>";
	var dvarr = new Array();
	dvarr['code'] = new Array(); 
	dvarr['name'] = new Array(); 
	dvarr['code'] = dcstr.length ? dcstr.split(",") : "" ;
	dvarr['name'] = dcstr.length ? dnstr.split(",") : "" ;
	return dvarr;
}
function bsgetulistlink() {
	var link = "<?php echo JURI::base().'index.php?option=com_bsscheduler&task=loadulist&did='; ?>";
	return link;
}
function getHolidayslink(setdate,mode) {
	var link = "<?php echo JURI::base().'index.php?option=com_bsscheduler&task=getholidays&setdate=" + setdate + "&mode=" + mode + "' ; ?>";
	return link;
}
function setmodal() {
	// TODO: Put setGood back
	
	return false;
}
// -->
</script>

<?php if ($this->paramm->get('show_page_title',1)) : ?>
<h2 class="componentheading<?php echo $this->escape($this->paramm->get('pageclass_sfx')); ?>">
	<?php echo $this->escape($this->paramm->get('page_title')); ?>
</h2>
<?php endif; ?>
<div>
<form action="<?php echo $this->action ?>" method="post" name="hiddenForm"  class="form-validate" >
	<input type="hidden" name="userid" id="userid" value="<?php echo $this->user->id?>" />
	<input type="hidden" name="divcode" id="divcode" value="<?php echo $this->user->divcode?>" />
	<input type="hidden" name="reqdiv" id="reqdiv" value="" />
    <div id="bsschfilter" class="mainBorder">
		<div id="bssfilblock1" class="hdselectlist">
			<label for="userlabel" id="userlabel"><?php echo JText::_('SELECT_USER'); ?> &nbsp;:&nbsp;</label>
			<?php 
			// echo JHTML::link($changeUserUrl, $this->user->name, $linkAttribs); 
				echo $this->userlist("1",'onchange="bsssubmitform(this.form)"');
				echo BscoreHelper::modal($changeUserUrl, JText::_('OTHER_DIVISION'), $linkAttribs,"username",'<i class="icon-user"></i>');
			?>
		</div>
		<div id="bssfilblock2" class="hdselectlist">
			<label for="division"><?php echo JText::_('DIVISION');?>&nbsp;:&nbsp;</label>
			<div id="divname" ><?php echo $this->user->divname;?></div>
		</div>
		<div id="bssfilblock3">
			<button type="button" onclick="resetuser()"  data-inline="true"><i class="fa fa-refresh"></i><?php echo JText::_("RESET") ?></button>
		</div>
    </div>
</form>
</div>
<div class="clr"></div>
<div class="icon_explain">
<?php echo JText::_('RES_PRIORITY');?>&nbsp;
<i class="fa fa-exclamation-circle" style="color:red"></i>:<?php echo JText::_('PRIORITY_HIGH');?>&nbsp;
<i class="fa fa-arrow-circle-down" style="color:blue"></i>:<?php echo JText::_('PRIORITY_LOW');?>&nbsp;
<i class="fa fa-question-circle" style="color:green"></i>:<?php echo JText::_('PRIORITY_TEMP');?>
</div>
<div id="schdlboard" data-role='none' >
<?php
echo $this->scheduler;
?>
</div>

