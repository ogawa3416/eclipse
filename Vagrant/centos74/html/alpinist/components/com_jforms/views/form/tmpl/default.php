<?php
/**
* Form layout (Default Theme)
*
* @version		$Id: default.php 362 2010-02-20 06:50:23Z dr_drsh $
* @package		Joomla
* @subpackage	JForms.Layouts
* @copyright	Copyright (C) 2008 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
JHtml::_('formbehavior.chosen', 'select');

JHTML::_('JForms.Frontend.javascript', $this->form);
$formUrl = JRoute::_('index.php?option=com_jforms&id='.$this->form->id.'&Itemid='.$this->Itemid.'&task=submit');
?>
<div style="color:red;font-size:2em;font-weight: bolder;"><?php echo $this->message;?></div>
<div class="jform">		
	<table class="jform">
	<tr>
	<td>
		<h2><?php echo $this->form->title ?></h2>
		<form name="jform" action="<?php echo $formUrl ?>" method="post" enctype="multipart/form-data" onsubmit="return validateForm_<?php echo $this->form->id ?>(this);">
			<?php JHTML::_('JForms.Frontend.elements', $this->form); ?>
			<input type="hidden" name="option" value="com_jforms" />
			<input type="hidden" name="task" value="submit" />
			<input type="hidden" name="uid" value="<?php echo $this->user->id ?>" />
			<?php echo JHTML::_( 'form.token' ); ?>
		</form>
	</td>
	</tr>
</table>
</div>