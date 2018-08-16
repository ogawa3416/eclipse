<?php
/**
* Records View
*
* @version		$Id: view.html.php 362 2010-02-20 06:50:23Z dr_drsh $
* @package		Joomla
* @subpackage	JForms.Tables
* @copyright	Copyright (C) 2008 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

/**
 * Records View
 *
 * @package    Joomla
 * @subpackage JForms.Tables
 */
class RecordsViewRecords extends JViewLegacy
{

	/**
	 * Records view display method
	 *
	 * Displays Records (Stored data)
	 *
	 * @return void
	 **/
	function display($form = NULL )
	{
		$app = JFactory::getApplication();
		$input = $app->input;
		
		if( !count($form->records)){
			echo '<div style="font-size:150%;text-align:center;color:red;font-weight:bold">'.JText::_('NO_RECORDS_STORED_YET').'<br /><br /><a href="javascript:history.back();">&lt;&lt; '.JText::_('GO_BACK').'</a></div>';
			return;
		}
	
		//JHTML::_('stylesheet', 'media/com_jforms/styles/general/reset.css');
		JHTML::_('stylesheet', 'media/com_jforms/styles/records-backend.css');
		JHTML::_('stylesheet', 'media/com_jforms/styles/grid.css');

		$input->set('hidemainmenu', 1);		
		
//		JHTML::_('JForms.General.mootools');

		JToolBarHelper::title(   JText::_( 'Records' ), 'jforms-records' );
		JToolBarHelper::back();

		$this->assignRef('form', $form);
	
		//Display the template
		parent::display();
	}
}