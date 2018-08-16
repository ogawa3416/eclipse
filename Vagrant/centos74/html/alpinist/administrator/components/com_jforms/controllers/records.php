<?php
/**
* Records backend controller
*
* @version		$Id: records.php 362 2010-02-20 06:50:23Z dr_drsh $
* @package		Joomla
* @subpackage	JForms.Controllers
* @copyright	Copyright (C) 2008 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

jimport('joomla.application.component.controller');

/**
 * Records backend controller
 *
 * @package    Joomla
 * @subpackage JForms.Controllers
 */
class RecordsController extends JControllerLegacy
{
	var $_basePath ;
	/**
	 * constructor (registers additional tasks to methods)
	 *
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'export'  , 'export'   );
		$this->registerTask( 'delete'  , 'delete'   );
		$this->registerTask( 'get'     , 'retrieve' );
		$this->registerTask( 'back'    , 'back'     );
		$this->_basePath = JPATH_COMPONENT;
	}
	
	/**
	 * Task handler (Exports records)
	 *
	 * @return void
	 */
	function export(){
	
//		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$app = JFactory::getApplication();
		$input = $app->input;

		if(!$app->isAdmin()) {
			exit('Not Admin!');
		}
		
		$pManager = JFormsGetPluginManager();
		$pManager->loadPlugins('element');
		$pManager->loadPlugins('export');
	
		$tempArray = $input->getArray();

		$requestParameters = reset( $tempArray );
		
		require_once JFORMS_BACKEND_PATH.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'Services_JSON'.DIRECTORY_SEPARATOR.'Services_JSON.php';
	
		//Decode JSON value
		$json = new Services_JSON();
		
		$name         = $requestParameters['name'];
		$fid          = $requestParameters['fid'];
		$rowStart     = $requestParameters['start'];
		$rowCount     = $requestParameters['rpp'];
		$fields       = $requestParameters['fields'];
		$requestParameters['labels'] = $json->decode($requestParameters['labels']);
		
		$criteria     = $requestParameters['keyword'];
		$currentPage  = $requestParameters['page'];
		$pageCount    = $requestParameters['pageCount'];
		$totalRecords = $requestParameters['recordCount'];
		$selectedIds  = $requestParameters['ids'];
		

		$postVarName  = 'JFormXPlugin'.$name.'Parameters';
		$pluginParams =  $input->get( $postVarName, null );
		

		$criteria = $json->decode($criteria);	

		if( isset( $pluginParams['exportRange'] )){
			switch( $pluginParams['exportRange'] ){
			
			case 'selected':
				$criteria->id = new stdClass();
				$criteria->id->numbers = explode(',', $selectedIds);
				$criteria->id->mode = 'or';
				break;
				
			case 'visible':
				$selectedIds = null;
				break;
				
			case 'pages':
				$selectedIds = null;
				$rowStart = 0;
				$rowCount = $totalRecords;
				break;
				
			case 'all':
				$selectedIds = null;
				$rowStart = -1;
				$rowCount = -1;
				$keyword  = '';
				break;
			
			}
		}
		


		if( $fields ){
			$fields = explode(',', $fields);
		}
		
		if( !array_key_exists( $name, $pManager->settings['export'] )){
			die(JText::_('EXPORT_PLUGIN_NOT_FOUND'));
		}
		
		//Translation mode is passed to the Element plugin to let it know in which format should it output the data
		//For instance, JUser element can output the data in HTML format or in raw format, the translation mode lets it know which to use
		$translationMode = $pManager->settings['export'][$name]->format;
		
		$recordModel = $this->getModel('record', 'JFormsModel');		
		
		$response = $recordModel->search( $fid, $fields, $rowStart, $rowCount, $criteria, $translationMode, true );

		$pManager->invokeMethod('export' ,'onExport', array($name), array( $pluginParams, $requestParameters, $response ) );
		
		jexit( 0 );
		
	}
	
	
	/**
	 * Default task handler (View Records for a given form)
	 *
	 * @return void
	 */
	function display($cachable = false, $urlparams = Array())
	{
		$app = JFactory::getApplication();
		$input = $app->input;
		$document = JFactory::getDocument();
		$db = JFactory::getDBO();
		
		$viewType	= $document->getType();
		$viewName	= 'records';
		$viewLayout	= 'default';
		
		$view = $this->getView( $viewName, $viewType, '', array( 'base_path'=>$this->_basePath));

		$id = $input->getInt( 'id', 0 );
		
		// Get/Create the model
		$recordsModel = $this->getModel('record','JFormsModel');
		$form = $recordsModel->get( $id );

		// Set the layout
		$view->setLayout($viewLayout);

		// Display the view
		$view->display( $form );
	
	}
	
	/**
	 * Task handler (Back)
	 *
	 * @return void
	 */
	function back(){$this->setRedirect('index.php?option=com_jforms');}

	/**
	 * Task handler (Deletes records)
	 *
	 * @return void
	 */
	function delete(){
		
		//TODO: Harden
//		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$app = JFactory::getApplication();
		$input = $app->input;

		if(!$app->isAdmin()) {
			exit('Not Admin!');
		}
		
		$pManager = JFormsGetPluginManager();
		$pManager->loadPlugins('element');
		$pManager->loadPlugins('storage');
				
		
		$document = JFactory::getDocument();
		$document->setCharset('utf-8');
		$document->setMimeEncoding('text/plain');
		
		$ids   = $input->get( 'ids', array(), 'array' );
		$fid   = $input->getInt( 'fid'   , 0 );
		$jsIds = $input->get( 'jsRows', array(), 'array' );
		
		//Sanitize incoming ids
		JArrayHelper::toInteger( $ids );
		JArrayHelper::toInteger( $jsIds );
		
		$model = $this->getModel('record','JFormsModel');		
		$model->delete( $fid, $ids );

		echo implode( ',', $jsIds );

		jexit( 0 );
	}

	/**
	 * Task handler (retrieves records , called via Ajax)
	 *
	 * @return void
	 */
	function retrieve(){

		$requestMode = 'get';
		//TODO: Harden

//		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$app = \JFactory::getApplication();
		if(!$app->isAdmin()) {
			exit('Not Admin!');
		}
		
		$app = JFactory::getApplication();
		$input = $app->input;
		
		require_once JFORMS_BACKEND_PATH.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'Services_JSON'.DIRECTORY_SEPARATOR.'Services_JSON.php';
		
		$document = JFactory::getDocument();
		$document->setCharset('utf-8');
		$document->setMimeEncoding('text/plain');
	
		$pManager = JFormsGetPluginManager();
		$pManager->loadPlugins('element');
		
		$fid   	  = $input->getInt( 'fid', 0 );
		$rowStart = $input->getInt( 'start', -1 );
		$rowCount = $input->getInt( 'count', -1 );
		$fields   = $input->getString( 'fields', null );
		$keywords = $input->getString( 'keyword', null ); 	
		$ids      = $input->getString( 'ids', null );

		
		//Decode JSON value
		$json = new Services_JSON();
		$criteria = $json->decode($keywords);	

		if( $fields ){
			$fields = explode(',', $fields);
		}
		
		if( $rowCount > 200 ){
			return;
		}
		$model = $this->getModel('record','JFormsModel');		
		
		$response = $model->search( $fid, $fields, $rowStart, $rowCount, $criteria );
		echo $response;
		
		jexit( 0 );
	}
}
