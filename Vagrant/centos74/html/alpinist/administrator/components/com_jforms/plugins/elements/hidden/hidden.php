<?php
/**
* Hidden Element plugin
*
* @version		$Id: hidden.php BsAlpinist 2.5.0 $
* @package		Joomla
* @subpackage	JForms.Plugins
* @copyright	Copyright (C) 2008 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
/**
 * Hidden Element plugin 
 *
 * @package    Joomla
 * @subpackage JForms.Plugins
 */
class JFormEPluginHidden extends JFormEPlugin{

	function getSQL( $elementData, $criteria ){
		$db = JFactory::getDBO();
		$value = $db->escape($criteria->value);
		if(!strlen(trim($value)))return '';
		$field = $elementData->parameters['hash'];
		$sql = " `$field` = '$value' ";	
		return $sql;
	}
	
	function translate( $elementData, $input, $format='html', $segment=''){return $input;}

	function render( $elementData ){
		$p = JArrayHelper::toObject($elementData->parameters);
		$htmlId = $p->hash.'_'.$elementData->id;

		$default = property_exists($elementData,'defaultValue' )?$elementData->defaultValue:$p->defaultValue;
		$default = htmlspecialchars($default,ENT_QUOTES);

		return _line("<input type='hidden' value='$default' name='$p->hash' id='$htmlId' />",2);
	}
	
	function beforeSave($elementData, $input, $fsInfo = NULL){return $input;}
}