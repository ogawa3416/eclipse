<?php
/**
* Plugins Ajax calls controller
*
* @version		$Id: plugins.php BsAlpinist2.5.0 $
* @package		Joomla
* @subpackage	JForms.Controllers
* @copyright	Copyright (C) 2008 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

jimport('joomla.application.component.controller');

/**
 * Plugins Ajax calls controller
 *
 * @package    Joomla
 * @subpackage JForms.Controllers
 */
class PluginsController extends JControllerLegacy{

	/**
	 * constructor (registers additional tasks to methods)
	 *
	 * @return void
	 */
	function __construct(){
		parent::__construct();
		$this->registerTask( 'invoke'  , 'invoke');
	}
	
	/**
	 * Task handler (Invokes a method on a given plugin, called via Ajax, this allows element plugins to access server-side proceedures)
	 *
	 * @return void
	 */
	function invoke(){
		
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$app = JFactory::getApplication();
		$input = $app->input;
		
		$pManager = JFormsGetPluginManager();
		
		require_once JFORMS_BACKEND_PATH.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'Services_JSON'.DIRECTORY_SEPARATOR.'Services_JSON.php';
	
		//Decode JSON value
		
		$json = new Services_JSON();
		
		$method = $input->get( 'method' , '' );
		list($pluginType, $pluginName) = explode('.',$input->getString( 'plugin' , '' ));
		$parameters = $json->decode($input->getString( 'parameter'   , '' ));
		
		$pManager->loadPlugins( $pluginType );
		$output = $pManager->invokeMethod($pluginType, $method, array($pluginName), array( $parameters ) );
		jexit( $output );
	}
}