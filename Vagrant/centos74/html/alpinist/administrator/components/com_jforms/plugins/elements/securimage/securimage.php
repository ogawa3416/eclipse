<?php
/**
 * Securimage captcha element plugin
*
* @version		$Id: securimage.php BsAlpinist 2.5.0 $
* @package		Joomla
* @subpackage	JForms.Plugins
* @copyright	Copyright (C) 2008 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * Securimage captcha element plugin
 *
 * @package    Joomla
 * @subpackage JForms.Plugins
*/
class JFormEPluginSecurimage extends JFormEPlugin{

	function render( $elementData ){
	    
		$p = JArrayHelper::toObject($elementData->parameters);
		
		$error   = isset($elementData->validationError)?$elementData->validationError:'';
	
		$htmlId = $p->hash.'_'.$elementData->id;

		$output  = '';

		$css = $p->css;
		
		$output .= _line('<p style="margin-top:10px;">'.JText::_("TYPE_THE_CHARACTERS_YOU_SEE_IN_THE_PICTURE_BELOW").'</p>',2);           
		$output .= _line('<img class="'.$css.'" src="'.JURI::root().'media/com_jforms/plugins/elements/securimage/'.'securimage_show.php?sid='. session_id().'" /><br />',2);       
		$output .= _line('<br /><input name="'.$p->hash.'" class="'.$css.'" />',2);       
		$output .= _line("<div class='error-message' id='{$htmlId}_error'>$error</div>",2	);
		$output .= _line('<div class="clear"></div>',2);

		return $output;

	}
	
	function validate( $elementData, $input ){
		
		$p = JArrayHelper::toObject($elementData->parameters);
		include("securimagelib.php");
		$session = JFactory::getSession();
		$correctValue = $session->get('securimage_code_value');
		if( strtolower($input) == strtolower($correctValue) ){
			return '';
		} else {
			return JText::_("THE_TEXT_YOU_HAVE_ENTERED_DIDNT_MATCH_THE_IMAGE");	
		}
	}
}