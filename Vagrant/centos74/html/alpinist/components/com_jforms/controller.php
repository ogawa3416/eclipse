<?php
/**
* Main Frontend controller for Forms Component
*
* @version		$Id: controller.php 365 2010-03-26 12:42:55Z BsAlpinist 2.5.0 $
* @package		Joomla
* @subpackage	JForms.Controllers
* @copyright	Copyright (C) 2008 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

jimport('joomla.application.component.controller');

/**
 * Main Frontend Controller
 * 
 * @package    Joomla
 * @subpackage JForms.Controllers
 */
class FrontendControllerForm extends JControllerLegacy
{

	/**
	 * constructor (registers additional tasks to methods)
	 *
	 * @return void
	 */
	function __construct(){
	
		parent::__construct();
		JModelLegacy::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'models');
		$this->registerTask( 'thank'  , 'thank');
		$this->registerTask( 'submit' , 'submit');
		
	}
	
	function gotoPage( $name, $form ){
	
		global $Itemid;
		
		switch( strtolower($name) ){
			case "thank you":
				//This will break Thank you pages created by JForms 0.6
//				$uri = JRoute::_( $form->redirections['thank'], false );
				$uri = $form->redirections['thank'];		
				$this->setRedirect( $uri );
			break;
			
			case "unauthorized":

				if( empty($form->redirections['not_auth'])) {
					JError::raiseWarning( 10403, JText::_("JLIB_ENVIRONMENT_SESSION_EXPIRED") );
					$this->setRedirect('index.php');
				} else { 
					$this->setRedirect(JRoute::_( $form->redirections['not_auth'], false ));
				}
			break;
			
			case "expired":
				if( empty($form->redirections['expired']))
					JError::raiseError( 404, JText::_("Form_not_found") );
				else 
					$this->setRedirect(JRoute::_( $form->redirections['expired'], false ));
			break;
			
			default:
				JError::raiseError( 404, JText::_("Form_not_found") );
				break;
		}
	}	
	
	/**
	 * Validates record and stores it
	 *
	 * @access	public
	 */
	function submit()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$app = JFactory::getApplication();
		$input = $app->input;
		
		//Load element plugins
		$pManager = JFormsGetPluginManager();
		$pManager->loadPlugins('storage');
		$pManager->loadPlugins('element');
		
		$id     = $input->getInt( 'id' );
		$itemid = $input->getInt( 'Itemid' );
		
		$formModel = JModelLegacy::getInstance('form','JFormsModel');
		$form      = $formModel->get( $id );
		
		//Form not found
		if( $form == null ){
			JError::raiseError( 404, JText::_("Form_not_found") );
		}
		
		//Form is either beyond record count or has expired
		if( !$form->isPublished || (($form->recordCount >= $form->maximum) && $form->maximum != 0) ){
			$this->gotoPage('Expired', $form);
			return;
		}

		
		//Check premissions
		$user   = JFactory::getUser();
		$allowedGroups = explode(',', $form->groups);
		
		//User isn't among the allowed groups
//		if( !in_array( $user->gid, $allowedGroups ) ){
//			$this->gotoPage('Unauthorized', $form);
//			return;
//		}
		
		//If Profile mode form, Allow only non-guests
		if( !$user->id && $form->type == JFORMS_TYPE_PROFILE ){
			$this->gotoPage('Unauthorized', $form);
			return;
		}
		
		
		//The record data
		$postVars = $input->getArray();
		
		$isValidationError = false;
		for($i=0;$i<count($form->fields);$i++ ){

			$field = $form->fields[$i];
					
			//Deal with checkboxes and radios, elements that aren't submitted to the server if they have no value "not checked radio box,etc."
			//This will set to null the fields that are present in form definition and not submitted to the server
			if( !array_key_exists( $field->parameters['hash'], $postVars ) ) {
				$postVars[$field->parameters['hash']] = null;
			}
			$data = $postVars[$field->parameters['hash']];
	
			//Validate
			$error = $pManager->invokeMethod('element','validate', 
											 array($field->type), array( $field, $data ) );
			//If there's an error
			if( $error != '' ){
				//Raise error flag
				$isValidationError = true;
			}
			$form->fields[$i]->validationError = $error;
			$form->fields[$i]->defaultValue    = $data;		
		}
		
		/*
		*	Validation errors
		*/
		
		if(	$isValidationError ){
			//There has been a validation error, return to previous page and save error data in session
			if(array_key_exists( 'from_jforms_plugin', $postVars ) && 
			   intval($postVars['from_jforms_plugin'])){
				

				//Use session to store form previous State
				$formStateInfo = array();
				for($i=0;$i<count($form->fields);$i++ ){
					$formStateInfo[$form->fields[$i]->parameters['hash']] = 
						array(
							$form->fields[$i]->validationError,
							$form->fields[$i]->defaultValue    
						);
				}
				$document   = JFactory::getDocument();
				$_SESSION['JFormsSession']['FormState'][$form->id] = $formStateInfo;
				
				$uri = $postVars['url'];
				
				//Appends JFormsReturn=1 to the request URL to inform the plugin that we're returning form vaildation
				$jrouter = JRouter::getInstance('site');
				$juri    = JURI::getInstance( $uri );
				$juri->setVar('JFormsReturn', '1');

				if( !$juri->isInternal( $uri ) ){
					die('Hacking attempt');
				}

				$redirectURL =  JRoute::_($juri->toString(), false);
		
				$this->setRedirect( $redirectURL );
				
			
			} else {
			
				//Running from com_jforms context
				if( 
					!JFile::exists( JFORMS_FRONTEND_PATH.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'form'.DIRECTORY_SEPARATOR.'tmpl'.DIRECTORY_SEPARATOR.$form->theme.'.php') ||
					!JFile::exists( JFORMS_FRONTEND_PATH.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'form'.DIRECTORY_SEPARATOR.'tmpl'.DIRECTORY_SEPARATOR.$form->theme.'_thank.php') ||
					!JFile::exists( JFORMS_FRONTEND_PATH.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'form'.DIRECTORY_SEPARATOR.$form->theme.'.css')
				)$form->theme = 'default';
				
				$document   = JFactory::getDocument();
				$viewType	= $document->getType();
				$viewName	= 'form';
				$viewLayout	= $form->theme;
				
				$view = $this->getView( $viewName, $viewType );
				
				// Set the layout
				$view->setLayout($viewLayout);
				
				// Display the view
				$view->form($form);
			
			}
		} else {
		
			//Everything went okay?
			//Clear session Form states
			unset($_SESSION['JFormsSession']);
			
			$response = $pManager->invokeMethod('storage', 
												'getNextInsertID', 
												array('Database'), 
												array( $form ) );
			$nextInsertId = intval( $response['Database'] );
					
			//Everything is okay , proceed to save data
			for($i=0;$i<count($form->fields);$i++ ){
				
				//No longer needed
				unset( $form->fields[$i]->validationError );
				unset( $form->fields[$i]->defaultValue );		
				
				$field = $form->fields[$i];
				$data  = $postVars[$field->parameters['hash']];

				$storage = $pManager->settings['element'][$field->type]->storage;
				
				if( $storage && $storage->requirefs ){
					
					//Get Filesystem paths from  Database plugin
					$response = $pManager->invokeMethod('storage', 
												'getFSPath', 
												array('Database'), 
												array( $form, $field->parameters['hash'], $nextInsertId ) );
					$fsInfo  =  $response['Database'];
					
					//trigger the "onBeforeSave" event on plugin elements
					$postVars[$field->parameters['hash']] = 
					$pManager->invokeMethod('element','beforeSave', 
											array($field->type), array( $field, $data, $fsInfo  ) );
				
				} else {
				
					//trigger the "onBeforeSave" event on plugin elements
					$postVars[$field->parameters['hash']] =
					$pManager->invokeMethod( 'element','beforeSave', 
											array($field->type), array( $field, $data, null ) );

				}
			}
			
			$recordModel = JModelLegacy::getInstance('record','JFormsModel');
			$recordModel->save($form, $postVars);
			
			$this->gotoPage( 'Thank You', $form );
		}
	}
	
	/**
	 * Displays the thank you page
	 *
	 * @access	public
	 */
	function thank()
	{
		$app = JFactory::getApplication();
		$input = $app->input;
		$id	= $input->getInt( 'id' );

		$document = JFactory::getDocument();
		
		$formModel = JModelLegacy::getInstance('form','JFormsModel');
		$form      = $formModel->get( $id,false );
		
		if( is_null( $form ) ){
			JError::raiseError( 404, JText::_("PAGE_NOT_FOUND") );
		}
		

		if( 
			!JFile::exists( JFORMS_FRONTEND_PATH.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'form'.DIRECTORY_SEPARATOR.'tmpl'.DIRECTORY_SEPARATOR.$form->theme.'.php') ||
			!JFile::exists( JFORMS_FRONTEND_PATH.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'form'.DIRECTORY_SEPARATOR.'tmpl'.DIRECTORY_SEPARATOR.$form->theme.'_thank.php') ||
			!JFile::exists( JFORMS_FRONTEND_PATH.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'form'.DIRECTORY_SEPARATOR.$form->theme.'.css')
		)$form->theme = 'default';

		$viewType	= $document->getType();
		$viewName	= 'form';
		$viewLayout	= $form->theme.'_thank';

		$view =  $this->getView( $viewName, $viewType );
		
		// Set the layout
		$view->setLayout($viewLayout);
		// Display the view
		$view->thank( $form );
	}
	
	/**
	 * Method to display the view,  (Currently it is a faithful copy from the base)
	 *
	 * @access	public
	 */
	function display($cachable = false, $urlparams = Array())
	{
		jimport('joomla.filesystem.file');
		$user   = JFactory::getUser();
		
		$app = JFactory::getApplication();
		$input = $app->input;
		$id	= $input->getInt( 'id' );

		$formModel = JModelLegacy::getInstance('form','JFormsModel');
		$form      = $formModel->get( $id );
		
		//Form not found
		if( $form == null ){
			if( !$user->id ) {
				$this->gotoPage('Unauthorized', $form);
			} else {
				JError::raiseError( 404, JText::_("Form_not_found") );
			}
			return;
		}
		
		// workflow ?
		$app	= JFactory::getApplication('site');
		$params = $app->getParams();
		if( !$user->id && $params->get('workflow') ) {
			$msg	= JText::_('JGLOBAL_YOU_MUST_LOGIN_FIRST');
			$link = 'index.php';
			$this->setRedirect($link, $msg);
			return false;
		}
		//Form is either beyond record count or has expired
		if( !$form->isPublished || (($form->recordCount >= $form->maximum) && $form->maximum != 0) ){
			$this->gotoPage('Expired', $form);
			return;
		}

		//Check premissions
		$allowedGroups = explode(',', $form->groups);
		
		//User isn't among the allowed groups
//		if( !in_array( $user->gid, $allowedGroups ) ){
//			$this->gotoPage('Unauthorized', $form);
//			return;
//		}
		
		//If Profile mode form, Allow only non-guests
		if( !$user->id && $form->type == JFORMS_TYPE_PROFILE ){
			$this->gotoPage('Unauthorized', $form);
			return;
		}		
		
		//Sort elements
		$sortedElements = array();
		foreach( $form->fields as $f ){
			$sortedElements[$f->position] = $f;
		}
		$form->fields = $sortedElements;
		
		
		$recordModel = JModelLegacy::getInstance('record','JFormsModel');
		
		//Load previously stored Data
		if( $user->id && $form->type == JFORMS_TYPE_PROFILE ){
			$record = $recordModel->getByUid( $form, $user->id );
			if( count($record) ){
				foreach( $form->fields as $key => $value ){
				//Fix for PHP4 since foreach doesn't return references.
					$f = &$form->fields[$key];
					if( array_key_exists( $f->parameters['hash'], $record  ))
						$f->parameters['defaultValue'] = $record[$f->parameters['hash']];
				}
			}
		}
		
		
		//Check the selected theme, if any file is missing, load default
		
		if( 
			!JFile::exists( JFORMS_FRONTEND_PATH.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'form'.DIRECTORY_SEPARATOR.'tmpl'.DIRECTORY_SEPARATOR.$form->theme.'.php') ||
			!JFile::exists( JFORMS_FRONTEND_PATH.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'form'.DIRECTORY_SEPARATOR.'tmpl'.DIRECTORY_SEPARATOR.$form->theme.'_thank.php') ||
			!JFile::exists( JFORMS_FRONTEND_PATH.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'form'.DIRECTORY_SEPARATOR.$form->theme.'.css')
		)$form->theme = 'default';
		
		
		
		//MVC View stuff
		$document = JFactory::getDocument();
		
		$viewType	= $document->getType();
		$viewName	= 'form';
		$viewLayout	= $form->theme;

		$view =  $this->getView( $viewName, $viewType );
		
		$view->setLayout($viewLayout);

		$view->form($form);
	}
}
