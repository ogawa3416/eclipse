<?php
/**
* Export PluginManager class
*
* @version		$Id: export.php BsAlpinist2.5.0 $
* @package		Joomla
* @subpackage	JForms.Core
* @copyright	Copyright (C) 2008 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

require_once JFORMS_BACKEND_PATH.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'JFormXPlugin.php';

/**
* Export PluginManager class
*
* @package		Joomla
* @subpackage	JForms.Core
*/
class JFormsExportPluginManager extends JFormsPluginManager{
	
	/**
	 * @var array $loadedPlugins Stores loaded plugins parameters and settings
	 */
	var $loadedPlugins = null;
	
	function getSettings(){return $this->loadedPlugins;}
	
	function invokeMethod( $name, $which, $params ){
		
		//Deals with call_user_func_array warning as of PHP 5.3
		if( $params == null )$params = array();
		
		//Error checking
		if( $which == null || count( $which ) != 1 )return null;
		
		if( $which[0] == '_MANAGER' ){
			if( !method_exists($this,$name) )return null;
			return call_user_func_array(array($this,$name),$params);		
		}
		
		require_once $this->loadedPlugins[$which[0]]->php;
		$className = 'JFormXPlugin'.ucfirst($this->loadedPlugins[$which[0]]->name);
		//PHP 4 fix
		$methodExists = false;
		eval( '$x = new '.$className.'();$methodExists = method_exists($x,"'.$name.'");$x=null;' );
		//End of PHP 4 Fix
			
		if( !$methodExists )return null;
//		return call_user_func_array(array($className,$name),$params);
		$obj = new $className();
		return call_user_func_array(array($obj,$name),$params);
	}
		
	/**
	 *  Loads the active element plugins "listed in plugins/plugin.list" 
	 *
	 * @return void
	 */
	function loadPlugins()
	{
		//Performance check
		if( !empty($this->loadedPlugins)){
			return;
		}
		
		$path = JFORMS_BACKEND_PATH.DIRECTORY_SEPARATOR."plugins".DIRECTORY_SEPARATOR."export".DIRECTORY_SEPARATOR;
		$plugins = $this->_getPlugins();
		foreach($plugins as $plugin){
			$p = $this->_loadPlugin( $plugin );
			if($p != null){
				$this->loadedPlugins[$plugin] = $p;
			}
		}
	}


	
	/**
	 *  Loads a single Element plugin from XML file
	 *
	 * @return object : an object that holds information that was loaded from the XML file
	 */
	function _loadPlugin( $name )
	{
		
		$pluginPath = JFORMS_BACKEND_PATH.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'export'.DIRECTORY_SEPARATOR.$name.DIRECTORY_SEPARATOR;
		$filename = $pluginPath.$name.".xml";
		
		$root = new SimpleXMLElement($filename,NULL,true);

		$a = $root->attributes();
		if( (string)$a['type'] != 'export' ){
			unset($xml);
			return null;
		}
		
		$pluginURI  = JURI::base()."components/com_jforms/plugins/export/$name/";
		
		$plugin = new stdClass();
		$plugin->name = $root->name[0];
		$plugin->description = $root->description[0];
		$plugin->format = $root->format[0];
		$plugin->php = $pluginPath . $name . '.php';
		$plugin->paramXML = $pluginPath . 'param.xml';
				
		//Load language files
		$lang = JFactory::getLanguage();
		$lang->load('export.'.ucfirst($name),JFORMS_BACKEND_PATH,null,false);

		return $plugin;

	}

	/**
	 *  Reads the "plugins.list" file and returns an array containing the names of element plugions 
	 *
	 * @return array : Element plugins to be loaded
	 */
	function _getPlugins()
	{
		$plugins = file(JFORMS_BACKEND_PATH.DIRECTORY_SEPARATOR."plugins".DIRECTORY_SEPARATOR."export".DIRECTORY_SEPARATOR."plugins.list" );
		for($i=0;$i<count($plugins);$i++){
			$plugins[$i] = trim($plugins[$i]);
		}
		return $plugins;
	}
}