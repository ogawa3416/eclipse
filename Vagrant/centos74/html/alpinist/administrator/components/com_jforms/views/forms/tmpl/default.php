<?php
/**
* Default layout for list view
*
* @version		$Id: default.php BsAlpinist 2.5.0 $
* @package		Joomla
* @subpackage	JForms.Layouts
* @copyright	Copyright (C) 2008 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
?>

<script type="text/javascript">
//<![CDATA[
	var formsArray = new Array();
<?php
	for ($i=0; $i<count( $this->forms ); $i++){
		$trow = &$this->forms[$i];
		echo "\tformsArray[$i] = '$trow->title';\n";
	}
	?>

	Joomla.submitbutton = function(task){
	
/**		var form = $('adminForm'); **/
		var form = document.adminForm;
		var checkedCount = 0;
		var firstCheckedItem  = -1;
		
		var checkboxGroup = form.elements['cid[]'];
		if(checkboxGroup){
			if(checkboxGroup && checkboxGroup.length == undefined){
				checkboxGroup    = new Array();
				if( form.elements['cid[]'] != undefined )
					checkboxGroup[0] = form.elements['cid[]'];
			}
			for( i=0; i<checkboxGroup.length; i++ ){
				if(checkboxGroup[i].checked){
					checkedCount++;
					if( firstCheckedItem == -1 )firstCheckedItem = i;
				}
			}
		} else {
			checkedCount = 0;
		}


		if( task == 'remove' ){
				var returnValue = confirm('<?php echo JText::_('Are_you_sure')?>');
				if(!returnValue)return;
		}
		
		if( task == 'edit' ){
			if(checkedCount>1){
				alert('<?php echo JText::_("YOU_MAY_NOT_SELECT_MORE_THAN_ONE_ITEM_FOR_THIS_FUNCTION")?>');
				return;
			}
		}
		
		if( task == 'copy' ){
			if(checkedCount>1){
				alert('<?php echo JText::_("YOU_MAY_NOT_SELECT_MORE_THAN_ONE_ITEM_FOR_THIS_FUNCTION")?>');
				return;
			}
			var newName = '';
			while( newName == '' ){
				newName = prompt('<?php echo JText::_('PLEASE_SPECIFY_A_NAME_FOR_THE_NEW_FORM') ?>', '<?php echo JText::_('Copy_of') ?> '+formsArray[firstCheckedItem]);
			}
			if( newName == null )return;
			form.newName.value = newName;
		}
		Joomla.submitform(task);	
	}
//]]>
</script>
<form action="index.php" method="get" name="adminForm" id='adminForm'>
<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="5">
				<?php echo JText::_( 'Id' ); ?>
			</th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
			</th>			
			<th>
				<?php echo JText::_( 'Title' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Records' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Table_name' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Publish_Information' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Author' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Date' ); ?>
			</th>
			
			
		</tr>			
	</thead>
	<?php
	$k = 0;
	$config	= JFactory::getConfig();
	$db     = JFactory::getDBO();
	$now	= JFactory::getDate();
	$nullDate = $db->getNullDate();
	$app		= JFactory::getApplication();
	$template	= $app->getTemplate();
	$tmplpath = "templates/".$template."/images/admin/";

	for ($i=0, $n=count( $this->forms ); $i < $n; $i++){
		$row = $this->forms[$i];
		$checked 	= JHTML::_('grid.checkedout',   $row, $i );
		$link 		= JRoute::_( 'index.php?option=com_jforms&task=edit&cid[]='. $row->id );
		$recordLink	= JRoute::_( 'index.php?option=com_jforms&controller=records&id='. $row->id );
		
		/* Publish icon stuff , faithfully copied "with minor modifications" from com_content */
		$date		= JHTML::_('date',  $row->created, JText::_('DATE_FORMAT_LC4') );
		$publish_up = JFactory::getDate($row->publish_up);
		$publish_down = JFactory::getDate($row->publish_down);
		$publish_up->setTimeZone(new DateTimeZone($config->get('offset')));
		$publish_down->setTimeZone(new DateTimeZone($config->get('offset')));
		if ( $now->toUnix() <= $publish_up->toUnix() && $row->state == 1 ) {
				$img = 'publish_y.png';
				$alt = JText::_( 'Published' );
		} else if ( ( $now->toUnix() <= $publish_down->toUnix() ||
					  $row->publish_down == $nullDate ) && $row->state == 1 ) {
			$img = 'publish_g.png';
			$alt = JText::_( 'Published' );
		} else if ( $now->toUnix() > $publish_down->toUnix() && $row->state == 1 ) {
			$img = 'publish_r.png';
			$alt = JText::_( 'Expired' );
		} else if ( $row->state == 0 ) {
			$img = 'publish_x.png';
			$alt = JText::_( 'Unpublished' );
		}	
		$times = '';
		if (isset($row->publish_up)) {
			if ($row->publish_up == $nullDate) {
				$times .= JText::_( 'START_ALWAYS' );
			} else {
				$times .= JText::_( 'Start' ) .": ". $publish_up->format('Y-m-d H:i:s');
			}
		}
		if (isset($row->publish_down)) {
			if ($row->publish_down == $nullDate) {
				$times .= "<br />". JText::_( 'FINISH_NO_EXPIRY' );
			} else {
				$times .= "<br />". JText::_( 'Finish' ) .": ". $publish_down->format('Y-m-d H:i:s');
			}
		}
		/* End of publish icon stuff */
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $row->id; ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td>
				<a href="<?php echo $link; ?>"><?php echo $row->title; ?></a>
			</td>
			<td align="center">
				<a href='<?php echo $recordLink ?>'><?php echo JText::_('Records') ?></a>
			</td>
			<td align="center">
				<?php echo $db->getPrefix().'jforms_'.$row->table_name; ?>
				
			</td>			
			<td align="center">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'PUBLISH_INFORMATION' );?>::<?php echo $times; ?>">
				<a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $row->state ? 'unpublish' : 'publish' ?>')">
					<img src="<?php echo $tmplpath . $img;?>" width="16" height="16" border="0" alt="<?php echo $alt; ?>" />
				</a>
			</span>
			</td>
			<td align="center">
				<?php echo $row->author; ?>
			</td>
			<td align="center">
				<?php echo JHTML::_('date',  $row->created, JText::_('DATE_FORMAT_LC4') ); ?>
			</td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	</table>
</div>

<input type="hidden" name="option" value="com_jforms" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="newName" value="" />
<?php echo JHTML::_( 'form.token' ); ?>	
</form>
<?php JHTML::_('JForms.Forms.legend'); ?>
<?php JHTML::_('JForms.General.version'); ?>