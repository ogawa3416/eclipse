<?php
/**
* Record View Helper
* This class is the HTML Workhorse for the Records view 
*
* @version		$Id: records.php BsAlpinist2.5.0 $
* @package		Joomla
* @subpackage	JForms.Helpers
* @copyright	Copyright (C) 2008 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

if(!version_compare(JVERSION, '3.0', 'ge'))
	require_once( JPATH_LIBRARIES.DIRECTORY_SEPARATOR.'joomla'.DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.'sliders.php');
require_once( JFORMS_BACKEND_PATH.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'parameter.php' );
require_once( JFORMS_BACKEND_PATH.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'sliders.php' );

/**
 * Records View Helper
 * This class is the HTML Workhorse for the Records view
 *
 * @package    Joomla
 * @subpackage JForms.Helpers
*/
class JFormsRecords{
	
	static function elementForms( $form ){
		
		$pManager = JFormsGetPluginManager();
		
		$pManager->loadPlugins('element');
		$plugins  = $pManager->settings['element'];
		
		$output = '';
		$output .= JFormssliders::start("search-pane");
		$output .= '<ul id="search-pane-list">';
		
		foreach($form->fields as $f){
			$pluginType = $plugins[$f->type];
			if( $pluginType->storage ){
					
					$title   = stripslashes( $f->parameters['label'] );
					$hash    = $f->parameters['hash'];
					
					$parameters = new JFormsParameter('',$pluginType->searchXML);
				
					$output .= '<li title="'.$hash.'|'.$title.'">';
					$output .= '<div class="search-pane-list-handle"></div>';
					$output .= '<div class="search-pane-list-check"><input value="$hash|$title" type="checkbox" name="loaded_headers[]" checked="checked" id="header_'.$hash.'" /></div>';
					
					$output .= JFormssliders::panel($title, $hash."-page" );
					$output .= $parameters->render('JFormEPlugin'.$hash.'Parameters');
					$output .= JFormssliders::panelend();
					$output .= '</li>';
			}	
		}
		$output .= '</ul>';
		$output .= JFormssliders::end();
		echo $output;
	}
	
	static function exportForms( $form ){
		
		$pManager = JFormsGetPluginManager();

		$pManager->loadPlugins('export');
		$plugins  = $pManager->settings['export'];
		

		$output  = JHtml::_('sliders.start','export-pane');
		foreach($plugins as $p){

			$parameters = new JFormsParameter('',$p->paramXML);
			$parameters->set('name', $p->name);
			$parameters->set('fid' , $form->id);
			
			$title   = JText::_( $p->name );
			$output .= JHtml::_('sliders.panel',$title,  $p->name."-page" );
			$output .= _line('<form action="index.php" method="post" name="JFormXPlugin'.$p->name.'" id="JFormXPlugin'.$p->name.'">',1);	
			$output .= $parameters->render('JFormXPlugin'.$p->name.'Parameters');
			$output .= _line('<input type="hidden" name="option" value="com_jforms" />',2);
			$output .= _line('<input type="hidden" name="task" value="export" />',2);
			$output .= _line('<input type="hidden" name="controller" value="records" />',2);
			$output .= _line("<input class='export-button' type='button' onclick='exportRecords(\"JFormXPlugin{$p->name}\");' class='button' value='".JText::_('Export')."' />",2);
			$output .= _line(JHTML::_( 'form.token' ),2); 
			$output .= _line('</form>',1);
			

		}
		$output .= JHtml::_('sliders.end');
		
		JHTML::_('JForms.General.fixPane');
		echo $output;
	}
	
	static function mapping($form){
		
		$pManager = JFormsGetPluginManager();
		$pManager->loadPlugins('element');
				
		$output  = JHtml::_('sliders.start','mapping-pane');
		$output .= JHtml::_('sliders.panel',JText::_('Expand'), "mapping-page" );
		$output .= _line('<table style="font-weight:bold;">',1);
		$output .= _line('<tr>',1);
		$output .= _line('<td>ID</td>',2);
		$output .= _line('<td style="color:green">id</td>',2);
		$output .= _line('</tr>',1);
		
		foreach($form->fields as $f){
			if( !$pManager->invokeMethod('element','hasStorageRequirements', array('_MANAGER'), array($f)))continue;
			$output .= _line('<tr>',1);
			$output .= _line('<td>'.stripslashes($f->parameters['label']).'</td>',2);
			$output .= _line('<td style="color:green">'.$f->parameters['hash'].'</td>',2);
			$output .= _line('</tr>',1);
		}
		$output .= _line('</table>',1);
		$output .= JHtml::_('sliders.end');
				
		echo $output;
	}
	
	static function controls(){
	
		$output  = "<label for='record_per_page'>".JText::_('RECORDS_PER_PAGE')."</label>";
		$output .= "<select id='record_per_page' name='record_per_page' onchange='refreshPageList()'>";
		$output .= "<option value='5'>5</option>";
		$output .= "<option value='20' selected='selected'>20</option>";
		$output .= "<option value='50'>50</option>";
		$output .= "<option value='100'>100</option>";
		$output .= "<option value='200'>200</option>";
		$output .= "</select><br clear='all' />";
		
		$output .= "<label for='current_page'>".JText::_('CURRENT_PAGE')."</label>";
		$output .= "<select id='current_page' name='current_page' >";
		$output .= "<option value='1'>1</option>";
		$output .= "</select><br clear='all' />";
		
		$output .= "<input id='reload-button' value='".JText::_('Reload')."' onclick='reloadRecords()' type='button' />";		
		$output .= "<input id='delete-button' value='".JText::_('DELETE_SELECTED')."' onclick='deleteSelected()' type='button' />";		
		
		echo $output;
	}
	
	static function javascript_constructKeyword( $form ){
	
		$output  = _line("<script type='text/javascript'>",1);
		$output .= _line('//<![CDATA[',1);
		
		$output .= _line("function constructKeyword(){",1);
		$output .= _line("var KeywordsObject = new Object();",2);
		foreach( $form->fields as $f ){
			$hash = $f->parameters['hash'];
			$arrayBase = 'JFormEPlugin'.$hash.'Parameters';
			$output .= _line("var Children = getHTMLArrayChildren('$arrayBase',$('filter_form'));",2);
			$output .= _line("KeywordsObject.$hash = new Object();",2);
			$output .= _line("for(i=0;i<Children.length;i++){",2);
			$output .= _line("KeywordsObject.$hash [Children[i][1]]=$('filter_form').elements[Children[i][0]].value;",3);
			$output .= _line("}",2);
		}
		$output .= _line("var orderedKeywords = new Object();",2);
		$output .= _line("$$('#search-pane-list li').each(function(li) { var hash = li.get('title').split('|')[0];orderedKeywords[hash] = KeywordsObject[hash]; })",2);
		$output .= _line("return JSON.encode(orderedKeywords);",2);
		$output .= _line("}",1);
		
		$output .= _line('//]]>',1);
		$output .= _line('</script>',1);
		
		echo $output;
		
	}	
	
	/**
	 * Outputs HTML <script> tags that includes the javascript
	 *
	 * @return void
	 */
	static function javascript( $form )
	{	
		$jsScriptsURI  = JURI::root() . 'media/com_jforms/scripts/';
		$jsRecordsPath  = JFORMS_BACKEND_PATH.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'scripts'.DIRECTORY_SEPARATOR.'records'.DIRECTORY_SEPARATOR;

		JHTML::_('script', $jsScriptsURI.'utilities.js');
		JHTML::_('script', $jsScriptsURI.'dhtmlxcommon.js');
		JHTML::_('script', $jsScriptsURI.'dhtmlxgrid.js');
		JHTML::_('script', $jsScriptsURI.'dhtmlxgridcell.js');

		JHTML::_('JForms.General.script', 'records.js' ,$jsRecordsPath, $form);
		JHTML::_('JForms.General.script', 'events.js'  ,$jsRecordsPath);
		JFormsRecords::javascript_constructKeyword( $form );
	
	}
	
}