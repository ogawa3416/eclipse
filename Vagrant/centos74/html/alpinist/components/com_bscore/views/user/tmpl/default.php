<?php
/**
 * BlogStone component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		BlogStone UGMS
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2011 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: default.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.3 $
 **/
// no direct access
defined('_JEXEC') or die;
JHtml::_('formbehavior.chosen', 'select');
require_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php');
JHTML::stylesheet('components/com_bscore/assets/css/bscore.css' );
$app = JFactory::getApplication();
$input = $app->input;
$menuitemid = $input->getInt( 'Itemid' );

?>
<div class="userblock" style="position: relative;">
<form name="adminForm" id="adminForm" action="<?php echo htmlspecialchars(JURI::getInstance()->toString()); ?>" method="post" >

<?php if ($this->params->get('show_page_title',1)) : ?>
<h2 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
	<strong><?php echo $this->escape($this->params->get('page_title')); ?></strong>
</h2>
<?php endif; ?>

<div class="mainselector" >
	<div id="searchword" class="bs_search" data-role="fieldcontain">
		<label class="filter-search-lbl" for="search">
		<?php echo JText::_('BSC_FILTER_BY_NAME')?> : 
		</label>
		<input type="search" name="search" value="<?php echo $this->searchword?>" onchange="this.form.submit();" />
	</div>
	<div id="searchdiv" class="bs_search" data-role="fieldcontain">
		<label for="division"><?php echo JText::_('BSC_SELECT_DIVISION'); ?> : </label>
		<?php 
			$att = 'onchange="submit(this.form)" ';
			echo BscoreHelper::divcodelist( 'divcode',$this->divcode,$att,'divcode',1 );
		?>
	</div>
	<div class="display-limit">
		<?php echo JText::_('Display_Num'); ?>&nbsp;
		<?php echo $this->pagination->getLimitBox(); ?>
	</div>
</div>
<div class="scrolldiv">
<table class="category table table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th><?php echo JText::_('BSC_EMPLOYEENO')?></th>
        <th style="display:none"><?php echo ""?></th>
        <th><?php echo JText::_('BSC_NAME')?></th>
        <th><?php echo JText::_('BSC_DIVNAME')?></th>
        <th><?php echo JText::_('BSC_TELENO')?></th>
        <th><?php echo JText::_('BSC_EMAIL')?></th>
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
        <td style="text-align:left;"><?php echo $row->employeeno?></td>
        <td style="display:none";><?php echo $row->id?></td>
      <?php if($this->params->def('user_image_onoff', 0) == 1) : ?>
        <td><a href="javascript:void(0)" onclick="OnNameLinkClick('<?php echo $row->name ?>','<?php echo $row->employeeno ?>','<?php echo $row->divname ?>','<?php echo $row->teleno ?>','<?php echo $row->email ?>',event);"><?php echo $row->name?></td>
      <?php else : ?>
      	<td><?php echo $row->name?></td>
      <?php endif; ?>
        <td><?php echo $row->divname?></td>
        <td><?php echo $row->teleno?></td>
        <td><?php echo JHtml::_('email.cloak', $row->email);?></td>
    </tr>
    <?php
            $i++; $k = 1 - $k; 
        endforeach 
    ?>
    </tbody>
<?php endif ?>
</table>
</div>
<p class="counter">
	<?php echo $this->pagination->getPagesCounter(); ?>
</p>
<div class="pagenavline">
	<?php echo $this->pagination->getPagesLinks();?>
</div>

    <input type="hidden" name="task" value="user.getlist" />
    <input type="hidden" name="limitstart" value="" />
<div id="userdesc">
	<div class="close_button">&nbsp;</div>
	<img src="" alt="User Description" class="img"  >
	<div id="userdetail">
		<span id="username"></span><br />
		<span id="employeeno"></span><br />
		<span id="divname"></span><br />
		<span id="teleno"></span><br />
		<span id="email"></span>
	</div>
</div>
</form>
</div>
<script language="javascript" type="text/javascript">
<!--
    function OnNameLinkClick(username,employeeno,divname,teleno,email,ev) {
		jQuery.post("index.php?option=com_bscore&view=user&task=user.getuserimage",
			{"employeeno":employeeno,"menuid":"<?php echo $menuitemid; ?>"},
			function( data, status, xhr){
				openUserDesc(username,employeeno,divname,teleno,email,data.imagepath,ev);
			}, "json");

	}
	function openUserDesc(username,employeeno,divname,teleno,email,imgpath,ev ) {
		if( document.documentElement.scrollTop ) 
			var scl = document.documentElement.scrollTop;
		else 
			scl = document.body.scrollTop;
		var elp =  ev.target?ev.target:window.event.srcElement;
		var elm =  jQuery('.userblock')[0];
		var mh = 0;
		while(elm){ 
			mh += elm.offsetTop;
			elm = elm.offsetParent;
		}
		oy = ev.clientY+scl-mh-30;

		imgblk = jQuery('#userdesc img');
		imgblk[0].src = imgpath;
		document.getElementById("username").innerHTML = username;
		document.getElementById("employeeno").innerHTML = employeeno;
		document.getElementById("divname").innerHTML = divname;
		document.getElementById("teleno").innerHTML = teleno;
		document.getElementById("email").innerHTML = email;
		var descblk = jQuery('#userdesc');
		descblk[0].style.top = oy + "px";
		descblk[0].style.display = 'block';
	}
	jQuery("#userdesc").click(function(){
		imgblk = jQuery('#userdesc img');
		imgblk[0].src = '';
		document.getElementById("username").innerHTML = username;
		document.getElementById("employeeno").innerHTML = "";
		document.getElementById("divname").innerHTML = "";
		document.getElementById("teleno").innerHTML = "";
		document.getElementById("email").innerHTML = "";
		var descblk = jQuery('#userdesc');
		descblk[0].style.top = "9999px";
		descblk[0].style.display = 'none';
	});
//-->
</script>

