<?php
/**
* Form list View
*
* @version		$Id: view.html.php BsAlpinist2.5.0 $
* @package		Joomla
* @subpackage	JForms.Views
* @copyright	Copyright (C) 2008 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

/**
 * Form list View
 *
 * @package    Joomla
 * @subpackage JForms.Views
 */
class FormsViewForms extends JViewLegacy
{
	/**
	 * List view display method
	 *
	 * Displays a list of all forms available in the database showing 
	 *   - id
	 *   - Title
	 *   - Storage plugins
	 *   - Author
	 *   - Date of creation
	 *   - Database table
	 *
	 * @return void
	 **/
	function display( $forms = null )
	{
		//JHTML::_('stylesheet', 'media/com_jforms/styles/general/reset.css');
		JHTML::_('stylesheet', 'media/com_jforms/styles/forms-backend.css');
	
		//Toolbar
		JToolBarHelper::title(   JText::_( 'Forms' ), 'jforms-logo' );
		JToolBarHelper::addNew();
		JToolBarHelper::editList();
		JToolBarHelper::custom( 'copy', 'copy.png', 'copy_f2.png', 'Copy' );	
		JToolBarHelper::deleteList();

		//Send data to the view
		$this->assignRef('forms', $forms);

		//Display the template
		parent::display();
	}
}
