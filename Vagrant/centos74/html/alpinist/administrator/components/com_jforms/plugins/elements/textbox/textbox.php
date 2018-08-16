<?php
/**
* Textbox element plugin
*
* @version		$Id: textbox.php BsAlpinist 2.5.0 $
* @package		Joomla
* @subpackage	JForms.Plugins
* @copyright	Copyright (C) 2008 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
jimport('joomla.filesystem.file');

/**
 * Textbox element plugin
 *
 * @package    Joomla
 * @subpackage JForms.Plugins
*/
class JFormEPluginTextbox extends JFormEPlugin{

	
	function getSQL( $elementData, $criteria ){
		
		$db = JFactory::getDBO();
		$f = $elementData->parameters['hash'];
		
		$mode = $criteria->mode=='or'?' OR ':' AND ';
		$keywordJoin = '';
		$kArray = null;
		switch($criteria->keyword_mode){
		
			case 'All':
				$keywordJoin = ' AND ';
				$kArray = explode(' ', $criteria->keywords );
			break;
			
			case 'Any':
				$keywordJoin = ' OR ';
				$kArray = explode(' ', $criteria->keywords );
			break;
			
			case 'Exact':
				$keywordJoin = ' ';
				$kArray = array( $criteria->keywords );
			break;
				
		}
		
		
		$fragments = array();
		foreach( $kArray as $k ){
			$k = $db->escape( "$k", true );
			if( $k == '')continue;
			$fragments[] = '`'.$f.'` LIKE '."'%".$k."%'";
		}
		$sql = implode( $keywordJoin,  $fragments );
		if( trim( $sql ) != '' ){
			$sql = "($sql) $mode";
		}
		return $sql;
	}
	
	function beforeSave( $elementData, $input, $fsInfo=null ){
		//Leave the sanitization to the core, just strip tags
		return strip_tags(str_replace( array("\r","\n"),'', $input ));
	}

	function render( $elementData ){
	
		$p = JArrayHelper::toObject($elementData->parameters);
	
		$htmlId = $p->hash.'_'.$elementData->id;
			
		$readonly = '';
		$db = JFactory::getDbo();
		if( $p->isAutoSet ) {
			$userId = JFactory::getUser()->get('id');
			$query = "SELECT u.name, CONCAT_WS(' : ',a.divcode,a.divname_s) division ,b.employeeno " 
			."\n FROM #__users as u, #__bs_division a, #__bs_users_detail b "
			."\n WHERE b.userid = u.id AND u.id = ".$userId
			."\n AND b.divcode = a.divcode  "
			;
			$db->setQuery( $query );
			$userdt = $db->loadObject();
			$readonly = " readonly='readonly' ";
		}
		switch( $p->isAutoSet ) {
		case '1':		// employeeno
			$default = $userdt->employeeno;
			break;
		case '2':		// name
			$default = $userdt->name;
			break;
		case '3':	// division
			$default = $userdt->division;
			break;
		case '0':
		default :
			$default = property_exists($elementData,'defaultValue' )?$elementData->defaultValue:$p->defaultValue;
			break;
		}  
		$error   = property_exists($elementData,'validationError' )?$elementData->validationError:'';
		$default = htmlspecialchars($default,ENT_QUOTES);
		$css = $p->css;
		$inputClass = $css . (empty( $error )?'':' input-error');
		$labelClass = $css . (empty( $error )?'':' label-error');
//		$inputStyle = "width:{$p->cw}px;height:{$p->ch}px;";
//		$labelStyle = "width:{$p->lw}px;height:{$p->lh}px;";
		$inputStyle = "width:{$p->cw}px;";
		$labelStyle = "width:{$p->lw}px;";
		
		$p->label = htmlspecialchars($p->label, ENT_QUOTES);
		if( $p->required ) {
			$p->label = $p->label . '<span class="required" style="color:red"> * </span>';
		}
		
		$output  = '';
	
		$p->maxLength = intval($p->maxLength);
		$output .= _line("<div class='error-message' id='{$htmlId}_error'>$error</div>",2	);
		$output .= _line("<label class='$labelClass' id='{$htmlId}_label' for='$htmlId' style='$labelStyle'>$p->label</label> ",2);
		$output .= _line("<input type='text' maxlength='{$p->maxLength}' class='$inputClass' value='$default' name='$p->hash' id='$htmlId' style='$inputStyle'".$readonly."/>",2);
		$output .= _line('<div class="clear"></div>',2);
		
		return $output;
		
	}
			 
	function jsValidation( $elementData ){
	
		$p = JArrayHelper::toObject($elementData->parameters);
		
		$htmlId = $p->hash.'_'.$elementData->id;
		$css = $p->css;
		
		$validationRule = '';
		if( $p->validation == 'Other' ){
			$validationRule = stripslashes($p->altValidation);
		} else {
			if( JFile::exists(JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'elements'.DIRECTORY_SEPARATOR.'textbox'.DIRECTORY_SEPARATOR.'validation'.DIRECTORY_SEPARATOR.$p->validation .'.regx') ){
				$validationRule = JFile::read(JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'elements'.DIRECTORY_SEPARATOR.'textbox'.DIRECTORY_SEPARATOR.'validation'.DIRECTORY_SEPARATOR.$p->validation .'.regx');
			}
		}
	
		
		$p->required = $p->required?'true':'false';
		$p->maxLength = intval($p->maxLength);
		
		$output  = "\n";
		$output .= _line("var $p->hash = document.getElementById('$htmlId');" ,2);
		$output .= _line("var {$p->hash}_label = document.getElementById('{$htmlId}_label');" ,2);
		$output .= _line("var {$p->hash}_error = document.getElementById('{$htmlId}_error');" ,2);
		$output .= _line("if($p->hash.value.length == 0){" ,2);
		$output .= _line("var required = $p->required;" ,3);
		$output .= _line("if(required){" ,3);
		$output .= _line("errorArray.push({id:$p->hash,msg:'error'});" ,4);
		$output .= _line("{$p->hash}.className ='input-error $css';",4);
		$output .= _line("{$p->hash}_label.className ='label-error $css';",4);
		$output .= _line("{$p->hash}_error.innerHTML='".JText::_('FIELD_REQUIRED')."';",4);	
		
		$output .= _line("}" ,3);
		$output .= _line("} else if({$p->hash}.value.length > {$p->maxLength}){" ,2);
		$output .= _line("errorArray.push({id:$p->hash,msg:'error'});" ,4);
		$output .= _line("{$p->hash}.className ='input-error $css';",4);
		$output .= _line("{$p->hash}_label.className ='label-error $css';",4);
		$output .= _line("{$p->hash}_error.innerHTML='".JText::_('TOO_LONG')."';",4);	
		$output .= _line("} else {" ,2);
		
		if( !empty( $validationRule ) ){ 
		
			$output .= _line("var regEx = /$validationRule/" ,3);
			$output .= _line("if(!regEx.test($p->hash.value)){ " ,3);
		
			$output .= _line("errorArray.push({id:$p->hash,msg:'error'})" ,4);
			$output .= _line("{$p->hash}.className ='input-error $css';",4);
			$output .= _line("{$p->hash}_label.className ='label-error $css';",4);
			$output .= _line("{$p->hash}_error.innerHTML='".JText::_('INVALID_FORMAT')."';",4);	
		
			$output .= _line("}" ,3);
		
		}
		$output .= _line("}" ,2);
		return $output;
	
	
	}
	function jsClearErrors( $elementData ){

		$p = JArrayHelper::toObject($elementData->parameters);
		$output  = "";

		$htmlId = $p->hash.'_'.$elementData->id;
		$css = $p->css;
		
		$output .= _line("var $p->hash = document.getElementById('$htmlId');" ,2);
		$output .= _line("var {$p->hash}_error = document.getElementById('{$htmlId}_error');" ,2);
		$output .= _line("var {$p->hash}_label = document.getElementById('{$htmlId}_label');" ,2);
		
		$output .= _line("{$p->hash}.className = '$css';",2);
		$output .= _line("{$p->hash}_label.className = '$css';",2);
		$output .= _line("{$p->hash}_error.innerHTML = '';",2);	
		
		return $output;
	}
	
	function validate( $elementData, $input ){
		
		$p = JArrayHelper::toObject($elementData->parameters);
		$validationRule = '';
		if( $p->validation == 'Other' ){
			$validationRule = stripslashes($p->altValidation);
		} else {
			if( JFile::exists(JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'elements'.DIRECTORY_SEPARATOR.'textbox'.DIRECTORY_SEPARATOR.'validation'.DIRECTORY_SEPARATOR.$p->validation .'.regx') ){
				$validationRule = JFile::read(JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'elements'.DIRECTORY_SEPARATOR.'textbox'.DIRECTORY_SEPARATOR.'validation'.DIRECTORY_SEPARATOR.$p->validation .'.regx');
			}
		}
	
		if( !strlen( trim( $input ))) {
			if( $p->required ){
				return JText::_("FIELD_REQUIRED");
			}
		} else {
		
			if( empty($validationRule) ){
				return "";
			}
			if(!preg_match('/'.$validationRule.'/',$input )){
				return JText::_("INVALID_FORMAT");
			}
		}
		return "";
	}
}