<?php
/**
* Element PluginManager class
*
* @version		$Id: element.php BsAlpinist2.5.0 $
* @package		Joomla
* @subpackage	JForms.Core
* @copyright	Copyright (C) 2008 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

require_once JFORMS_BACKEND_PATH.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'JFormEPlugin.php';

/**
* Element PluginManager class
*
* @package		Joomla
* @subpackage	JForms.Core
*/
class JFormsElementPluginManager extends JFormsPluginManager{

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
		$className = 'JFormEPlugin'.ucfirst($this->loadedPlugins[$which[0]]->name);
		//PHP 4 fix
		$methodExists = false;
		eval( '$x = new '.$className.'();$methodExists = method_exists($x,"'.$name.'");$x=null;' );
		//End of PHP 4 Fix
		
		if( !$methodExists )return null;
//		return call_user_func_array(array($className,$name),$params);
		$obj = new $className();
		return call_user_func_array(array($obj,$name),$params);
	}

	function getCategorizedElements(){
		
		$this->loadPlugins();
		
		$categories = array();
		
		foreach( $this->loadedPlugins as $e ){
			
			if( !array_key_exists($e->group, $categories)){
				$categories[$e->group] = array();
			}
			$categories[$e->group][$e->name] = $e;
		}
		return $categories;
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
		
		$path = JFORMS_BACKEND_PATH.DIRECTORY_SEPARATOR."plugins".DIRECTORY_SEPARATOR."elements".DIRECTORY_SEPARATOR;
	
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
		$pluginCorePath  = JFORMS_BACKEND_PATH.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'elements'.DIRECTORY_SEPARATOR.$name.DIRECTORY_SEPARATOR;
		$pluginMediaPath = JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_jforms'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'elements'.DIRECTORY_SEPARATOR.$name.DIRECTORY_SEPARATOR;
		$filename = $pluginCorePath.$name.".xml";
		
		//$root  = JFactory::getXML($filename,true);
		$root  = new SimpleXMLElement($filename,NULL,true);

		$a = $root->attributes();
		if( (string)$a['type'] != 'element' ){
			unset($root);
			return null;
		}
		
		$pluginURI  = JURI::root()."media/com_jforms/plugins/elements/$name/";
		
		$plugin = new stdClass();
		$plugin->name = (string)$root->name[0];
		$plugin->description = (string)$root->description[0];
		$plugin->limit = isset($a['limit'])?(string)$a['limit']:0;
		$plugin->group = isset($a['group'])?(string)$a['group']:'basic';
		$plugin->searchXML = $pluginCorePath . 'search.xml';
		$plugin->paramXML  = $pluginCorePath . 'parameters.xml';
		
		//Read <files>
		foreach( $root->files[0]->children() as $child ){
		
			$a = $child->attributes();
			$type = (string)$a['type'];

			switch( $type ){
				
				case 'jsEntryPoint':
					$plugin->js = $pluginCorePath . $child;
					break;

				case 'phpEntryPoint':
					$plugin->php = $pluginCorePath . $child;
					break;

				case 'icon':
					$plugin->button = $pluginURI . $child;
					break;
			}
		}
		
		//Read <storage>
		if( !isset( $root->storage ) ){
			$plugin->storage = null;
		} else {
			$a = $root->storage[0]->attributes();
			
			$plugin->storage = new stdClass();
			$plugin->storage->type = (string)$a['type'];
			
			$plugin->storage->size = 0;
			if( isset($a['size']))
				$plugin->storage->size = (int)$a['size'];
			
			$plugin->storage->requirefs = false;
			if( isset( $a['requirefs'] ))
				$plugin->storage->requirefs = strtolower($a['requirefs'])=='true'?true:false;
			
		}
		
		
		//Read parameters.xml
		$root  = JFactory::getXML($plugin->paramXML,true);
		$plugin->parameters = array();
		foreach( $root->params->children() as $child ){
			$a = $child->attributes();

			$parameterName = (string)$a['name'];
			$plugin->parameters[$parameterName] = new stdClass();
			$plugin->parameters[$parameterName]->name = $parameterName;

			$plugin->parameters[$parameterName]->valueType = (string)$a['valueType'];
			$plugin->parameters[$parameterName]->type = (string)$a['type'];

			if( property_exists($a,'translate') && (string)$a['translate'] == '1' ){
				$plugin->parameters[$parameterName]->translate = true;
			} else {
				$plugin->parameters[$parameterName]->translate = false;
			}
			
			$plugin->parameters[$parameterName]->default = (string)$a['default'];
		}
		//Load language files
		$lang = JFactory::getLanguage();
		$lang->load('element.'.ucfirst($name),JFORMS_BACKEND_PATH,null,false);

		return $plugin;

	}
	
	function hasStorageRequirements( $e ){
		$this->loadPlugins();
		if(
			property_exists($this->loadedPlugins[$e->type],'storage') && 
			$this->loadedPlugins[$e->type]->storage == null 
		   )return false;
		return true;
	}
	
	function getPluginsCategories(){
		
		$this->loadPlugins();
		
		$categories = array();
		
		foreach( $this->loadedPlugins as $e ){
			
			if( !array_key_exists($e->group, $categories)){
				$categories[$e->group]   = array();
			}
			$categories[$e->group][$e->name] = $e;
		}
		return $categories;
		
	}
	
	/**
	 *  Reads the "plugins.list" file and returns an array containing the names of element plugions 
	 *
	 * @return array : Element plugins to be loaded
	 */
	function _getPlugins()
	{
		$b = file_get_contents(JFORMS_BACKEND_PATH.DIRECTORY_SEPARATOR."plugins".DIRECTORY_SEPARATOR."elements".DIRECTORY_SEPARATOR."plugins.list");
		$plugins = explode( "\r\n", $b );
		for($i=0;$i<count($plugins);$i++){
			$plugins[$i] = trim($plugins[$i]);
		}
		return $plugins;
	}

}