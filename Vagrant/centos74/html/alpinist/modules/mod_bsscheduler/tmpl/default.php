<?php 
/**
 * BsScheduler module for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		BsScheduler
 * @subpackage	Modules
 * @copyright	Copyright (C) 2009-2011 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: default.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.0 $
 **/
// no direct access
defined('_JEXEC') or die;
JHtml::_('formbehavior.chosen', 'select');
$dayfrom = 0;
$dayto = 0;
$comlink = "index.php?option=com_bsscheduler&view=bsscheduler&changeuser=";
$modlink = "index.php?option=com_bsscheduler&tmpl=component&task=modload";
//$modlink = "index.php?option=mod_bsscheduler&tmpl=component";
$ldlink = JURI::base(true).'/modules/mod_bsscheduler/img/loading.gif';
$op = 'en';

?>
<link rel="stylesheet" href="<?php echo JURI::base(true) ?>/modules/mod_bsscheduler/tmpl/style.css" type="text/css"> 
<div class="bssscheduleview" id="bssscheduleview<?php echo $module->id?>" style="position: relative;" >
<!--bsscheduler###mainview-begin###-->
	<div id="bsschehead">
		<?php echo $helper->divcodelist('modivcode', $divcode, "onchange='bssdivchange();' data-role='none' ", $order = 'divcode', $size = 1,$all );?>
		<div id='bsmodpagebt'><span><a href="javascript:void(0);" id="bsmodpagebt-l" name='bsmodpagebt' onclick="bssdatechange('<?php echo $lastdate ?>');" ><i class="fa fa-chevron-circle-left"></i></a></span>
							<span><a href="javascript:void(0);" id="bsmodpagebt-r" name='bsmodpagebt' onclick="bssdatechange('<?php echo $nextdate ?>');" ><i class="fa fa-chevron-circle-right"></i></a></span>
		</div>
		<input type="hidden" id="stdate" value="<?php echo $year.'-'.$month.'-'.$days[0] ?>" >
	</div>
	<div class="clr"></div>
	<div id="userlist" style="max-height:<?php echo $ulistheight?>px;">
	<table id="bsschetable" cellspacing="1" >
	<thead>
	<tr id='bsscheheadline0'>
        <th id="bsscheumonthhd"><?php echo $year."/".$month ?></th>
		<?php for($i=0;$i<$dispnum;$i++) : ?>
        	<th name='bsschedaylabel' class='bsschedaylabel' colspan="<?php echo $tmparts?>" ><?php echo $days[$i] ?></th>
		<?php endfor;  ?> 
	</tr>
	<tr id='bsscheheadlineb'>
        <th id='bsscheunamehd'><?php echo JText::_("JGLOBAL_USERNAME")?></th>
		<?php for($i=0;$i<$dispnum;$i++) : 
			$inputy = date( 'Y',mktime(0,0,0,$month,$days[0]+$i,$year));
			$inputm = date( 'm',mktime(0,0,0,$month,$days[0]+$i,$year));
			$inputd = date( 'd',mktime(0,0,0,$month,$days[0]+$i,$year));
		?>
        	<th class='bsscheweeklabel d_<?php echo $helper->getweekname($inputy,$inputm,$inputd,$op) ?>' colspan="<?php echo $tmparts?>" ><?php echo $helper->getweekname($inputy,$inputm,$inputd) ?></th>
		<?php endfor;  ?> 
	</tr>
	</thead>
	<tbody>
	<?php foreach($userlist as $urow) : ?>
	<tr name='bsscheuname' class='bsscheuname<?php echo $urow->id ?>'>
        <td class='bsschenamelabel'><a href="<?php echo JRoute::_($comlink.$urow->id) ?>" title="<?php echo $urow->name ?>"><?php echo $urow->name ?></a></td>
		<?php for($i=0;$i<$dispnum;$i++) :
			$inputy = date( 'Y',mktime(0,0,0,$month,$days[0]+$i,$year));
			$inputm = date( 'm',mktime(0,0,0,$month,$days[0]+$i,$year));
			$inputd = date( 'd',mktime(0,0,0,$month,$days[0]+$i,$year));

			for($j=0;$j<$tmparts;$j++) : ?>
				<td class='tmparts-<?php echo intval($days[$i]) ?>-<?php echo $j ?> tmcell d_<?php echo $helper->getweekname($inputy,$inputm,$inputd,$op) ?>'></td>
			<?php endfor; ?>  
		<?php endfor; ?>  
	</tr>
	<?php  endforeach; ?> 
	</tbody>
	</table>
	<div id="bstooltipblk" name="bstooltipblk" ><span id="bstooltip" name="bstooltip"></span></div>
	</div>
<!--bsscheduler###mainview-end###-->
</div>

<?php if( $ajaxmode ) exit();  ?>
<script language="javascript" type="text/javascript">
<!--

function bsscheduleload(loadmode,div,dt) {
	var modid = '<?php echo $module->id?>';
	var dnum = '<?php echo $dispnum?>';
	var year = '<?php echo $year?>';
	var month = '<?php echo $month?>';
	var day = '<?php echo $days[0]?>';
	var divcode = '<?php echo $divcode?>';
	if( !div ) div = divcode;
	if( dt ) {
		dbf = dt.split('-');
		year = dbf[0];
		month = dbf[1];
		day = dbf[2];
	}
	var divc = document.getElementById('modivcode');
	divc.disabled=true;
	var pgc = document.getElementsByName('bsmodpagebt');
	pgc[0].disabled=true;
	pgc[1].disabled=true;
	loadHtml  = "<p class='bsloadingDiv' id='bsloadingDiv-"+'<?php echo $module->id?>'+"' >";
	loadHtml += "<img src='<?php echo $ldlink?>'>";
	loadHtml += " Loading...</p>";
	document.getElementById( 'bssscheduleview'+'<?php echo $module->id?>' ).innerHTML +=  loadHtml ;
	bsstimeplot = null;
	getbsplotmgr(dnum,'<?php echo $tmparts?>',div,year,month,modid,day,'<?php echo $starthour?>','<?php echo $endhour?>','<?php echo $fieldbgcolor?>');
	modbsscheloadAjax(modid,loadmode,'<?php echo $modlink?>');
	try {
		document.addEventListener("click", bsToolTip, false);
	} catch(e) {
		document.attachEvent("onclick", bsToolTip );
	}
}
function bssdivchange() {
	var divc = document.getElementById('modivcode');
	divc.disabled=true;
	var divcode = divc.options[divc.selectedIndex].value;
	var bsday = document.getElementById('stdate').value;
	bsscheduleload('reload',divcode,bsday);
}
function bssdatechange(bsday) {
	var divc = document.getElementById('modivcode');
	var divcode = divc.options[divc.selectedIndex].value;
	bsscheduleload('reload',divcode,bsday );
	return false;
}

window.onLoad = bsscheduleload('load',null,null);

// -->
</script>
