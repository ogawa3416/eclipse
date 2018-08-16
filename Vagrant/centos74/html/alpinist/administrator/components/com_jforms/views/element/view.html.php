<?php
/**
* Element View
*
* @version		$Id: view.html.php BsAlpinist 2.5.0 $
* @package		Joomla
* @subpackage	JForms.Views
* @copyright	Copyright (C) 2008 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

/**
 * Element View (Handles the Form Selection dialog in menu manager)
 *
 * @package    Joomla
 * @subpackage JForms.Views
 */
class FormsViewElement extends JViewLegacy
{
	/**
	 * Element view display method
	 *
	 * Displays a list of all forms available in the database
	 *
	 * @return void
	 **/
	function display( $forms= null)
	{
		//Send data to the view
		$this->assignRef('forms', $forms);

		//Display the template
		parent::display();
	}
}
