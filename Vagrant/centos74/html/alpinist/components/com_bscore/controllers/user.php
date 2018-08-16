<?php
/**
 * BsCore component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		BsAlpinist
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2011 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: user.php  BsAlpinist ver.2.5.0 $
 **/
// no direct access
defined('_JEXEC') or die;

jimport( 'joomla.application.component.controller' );

class BscoreControllerUser extends JControllerLegacy
{
	function __construct()
	{
		parent::__construct();
		$app = JFactory::getApplication();
		$input = $app->input;
		$cmd = $input->getCmd('task');
//		if( $cmd !== 'user.getlist'  ) { 
		if( $cmd !== 'getlist' && $cmd !== 'getuserimage' ) { 
			$input->set('task','getlist');
		}
	}
    function getlist()
    {
    	$app = JFactory::getApplication();
		$input = $app->input;
		$myuser = JFactory::getUser();
		if( !$myuser->id ) {
			$msg = JText::_('JLIB_ENVIRONMENT_SESSION_EXPIRED');
			$link = 'index.php';
			$this->setRedirect($link, $msg);
			return false;
		}
        $model = $this->getModel("User");
		if( !$model->checkDirectAccess() ){
			// Error Saving Application
			$msg	= JText::_('JERROR_LAYOUT_YOU_HAVE_NO_ACCESS_TO_THIS_PAGE');
			$retlink = $input->get('ret','','base64');
			if( $retlink ) {
				$link = base64_decode($retlink);
			} else {
				$link = 'index.php';
			}
			$this->setRedirect($link, $msg);
			return false;
		}
        
        $view = $this->getView("User", "html");
        $view->setModel($model, true);
                
        $view->display();    
    }

    function getuserimage()
	{
		$app = JFactory::getApplication();
		$input = $app->input;
		$employeeno = $input->get( 'employeeno' );
		$menuid = $input->get( 'menuid' );

		$menus	= $app->getMenu('site');
		$params = $menus->getParams($menuid);
		if (is_object( $params )) {
			$menu_params = new JRegistry;
			$menu_params->loadString($params);

			$path = $menu_params->get('user_image_path');

			$extension = $menu_params->get('user_image_extension');

			if ( substr(trim($path), -1) != "/" ){
				$path = trim($path) . "/";
			}
			$path = ltrim($path,'/');
	
			$image_path = $path . $employeeno . "." .$extension;
			$image_path2 = str_replace('/',DIRECTORY_SEPARATOR,$image_path);
			$image_path2 = JPATH_ROOT.DIRECTORY_SEPARATOR.$image_path2;

			$image_path = JURI::root().$image_path;
			$image_default = $menu_params->get('user_image_default');
			if ( file_exists($image_path2) ) {
				$ret = array('imagepath' => $image_path);
				echo json_encode($ret);
			} else {
				$image_path = $path . $image_default;
				$ret = array('imagepath' => $image_path);
				echo json_encode($ret);
			}
		}
		else {
			$ret = array('imagepath' => "" );
			echo json_encode($ret);
		}
		exit;

    }
}