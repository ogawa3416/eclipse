<?php
/**
* Forms list view HTML Helper
* This class is the HTML Workhorse for the Forms List view
*
* @version		$Id: forms.php BsAlpinist2.5.0 $
* @package		Joomla
* @subpackage	JForms.Helpers
* @copyright	Copyright (C) 2008 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
* Forms list view HTML Helper
* This class is the HTML Workhorse for the Forms List view
 *
 * @package    Joomla
 * @subpackage JForms.Helpers
*/
class JFormsForms{

	static function legend(){
		$app		= JFactory::getApplication();
		$template	= $app->getTemplate();
		$tmplpath = "templates/".$template."/";
		?>
		<table cellspacing="0" cellpadding="4" border="0" align="center">
		<tr align="center">
			<td>
			<img src="<?php echo $tmplpath ?>images/admin/publish_y.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'Pending' ); ?>" />
			</td>
			<td>
			<?php echo JText::_( 'PUBLISHED_BUT_IS' ); ?> <u><?php echo JText::_( 'Pending' ); ?></u> |
			</td>
			<td>
			<img src="<?php echo $tmplpath ?>images/admin/publish_g.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'Visible' ); ?>" />
			</td>
			<td>
			<?php echo JText::_( 'PUBLISHED_AND_IS' ); ?> <u><?php echo JText::_( 'Current' ); ?></u> |
			</td>
			<td>
			<img src="<?php echo $tmplpath ?>images/admin/publish_r.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'Finished' ); ?>" />
			</td>
			<td>
			<?php echo JText::_( 'PUBLISHED_BUT_HAS' ); ?> <u><?php echo JText::_( 'Expired' ); ?></u> |
			</td>
			<td>
			<img src="<?php echo $tmplpath ?>images/admin/publish_x.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'Finished' ); ?>" />
			</td>
			<td>
			<?php echo JText::_( 'NOT_PUBLISHED' ); ?>
			</td>


		</tr>
		<tr>
			<td colspan="10" align="center">
			<?php echo JText::_( 'CLICK_ON_ICON_TO_TOGGLE_STATE' ); ?>
			</td>
		</tr>
		</table>
		<?php
	}
}