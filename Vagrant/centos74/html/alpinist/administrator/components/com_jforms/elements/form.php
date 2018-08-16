<?php
/**
* Forms Selector HTML Element
*
* @version		$Id: form.php BsAlpinist 2.5.0 $
* @package		Joomla
* @subpackage	JForms.Elements
* @copyright	Copyright (C) 2008 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// Check to ensure this file is included in Joomla!
defined('JPATH_PLATFORM') or die;
jimport('joomla.form.formfield');
/**
 * Forms Selector HTML Element, allows the user to pick a form from the Database
 *
 * @package    Joomla
 * @subpackage JForms.Elements
 */
class JFormFieldForm extends JFormField
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	public $type = 'Form';
	
	protected function getInput()
	{
		$id = $this->id;
		$name = $this->name;
		$value = $this->value;
		$db			= JFactory::getDbo();
		$doc 		= JFactory::getDocument();
		$fieldName	= $this->type.'['.$name.']';
		
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_jforms'.DIRECTORY_SEPARATOR.'tables' );
		$form = JTable::getInstance( 'Forms', 'Table' );
		if ( $value ) {
			$form->load( $value );
		} else {
			$form->title = JText::_('SELECT_A_FORM');
		}
	
		$js = "
		function jSelectForm(id, title, object) {
			document.getElementById(object + '_id').value = id;
			document.getElementById(object + '_name').value = title;
			window.parent.SqueezeBox.close();
		}";
		$doc->addScriptDeclaration($js);

		$link = 'index.php?option=com_jforms&amp;task=element&amp;tmpl=component';

		JHTML::_('behavior.modal', 'a.modal');
		$html = "\n".'<div style="float: left;"><input style="background: #ffffff;" type="text" id="'.$id.'_name" value="'.htmlspecialchars($form->title, ENT_QUOTES, 'UTF-8').'" disabled="disabled" /></div>';
		$html .= '<div class="button2-left"><div class="blank"><a class="modal" title="'.JText::_('SELECT_A_FORM').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 650, y: 375}}">'.JText::_('Select').'</a></div></div>'."\n";
		$html .= "\n".'<input type="hidden" id="'.$id.'_id" name="'.$name.'" value="'.(int)$value.'" required="required" />';

		return $html;
	}
}