<?php
/**
* Entry date Element plugin
*
* @version		$Id: entrydate.php BsAlpinist 2.5.0 $
* @package		Joomla
* @subpackage	JForms.Plugins
* @copyright	Copyright (C) 2008 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
/**
 * Entry date Element plugin 
 *
 * @package    Joomla
 * @subpackage JForms.Plugins
 */

class JFormEPluginEntrydate extends JFormEPlugin{

	function render( $elementData ){
	
		$p = JArrayHelper::toObject($elementData->parameters);
		$htmlId = $p->hash.'_'.$elementData->id;
		return _line("<input type='hidden' value='' name='$p->hash' id='$htmlId' />",2);
	}
	
	
	function getSQL( $elementData, $criteria ){
		
		$db = JFactory::getDBO();
		$from = $criteria->from;
		$to   = $criteria->to;
		$mode = $criteria->mode=='or'?' OR ':' AND ';
		$fragments = array();
		$field= $elementData->parameters['hash'];
		
		if($from != '' )
			$fragments[] = "`$field` >= '$from'";
		if($to != '' )
			$fragments[] = "`$field` <= '$to'";
		$sql = implode( ' AND ', $fragments );
		
		if( trim( $sql ) != '' ){
			$sql = "($sql) $mode";
		}
		return $sql;
		
	}
	
	function beforeSave($elementData, $input, $fsInfo = NULL){
		
		$config	= JFactory::getConfig();
//		$now	= JFactory::getDate();
//		$now->setTimeZone(new DateTimeZone($config->get('offset')));
//		return $now->toSql();
/**** Modified by groon ****/
		$tz = $config->get('offset');
		$now = new JDate('now', $tz);
		return $now->toSql(true);
	}
}