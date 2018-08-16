<?php
/**
* Design View
*
* @version		$Id: view.html.php BsAlpinist2.5.0 $
* @package		Joomla
* @subpackage	JForms.Views
* @copyright	Copyright (C) 2008 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.view');

/**
 * Design View
 *
 * @package    Joomla
 * @subpackage JForms.Views
 */
class FormsViewDesign extends JViewLegacy
{
	/**
	 * Design view display method
	 *
	 * The WYSIWYG form design environment , Where all the magic is going to happen 
	 *
	 * @return void
	 **/
	function display( $form = NULL )
	{	
		$app = JFactory::getApplication();
		$input = $app->input;
		
		//Disable top menu "Thanks Ercan :)"
		$input->set('hidemainmenu', 1);		
		
		//Loads latest mootools version
//		JHTML::_('JForms.General.mootools');
		JHTML::_('JForms.General.IE');
		
		JHTML::_('stylesheet', 'media/com_jforms/styles/design-backend.css');
		JHTML::_('stylesheet', 'media/com_jforms/styles/design-form.css');
		
		//Toolbar
		JToolBarHelper::title(   JText::_( 'Design' ), 'jforms-design' );
		JToolBarHelper::save();
		JToolBarHelper::cancel();
		
		$this->assignRef('form' ,$form);
		//Display the template
		parent::display();
		
	}
}