<?php
/**
 * JForms component for Joomla.
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
 * @version		$Id: formlist.php  BsAlpinist ver.2.4.1 $
 **/
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.controller' );

class FrontendControllerFormlist extends JControllerLegacy
{
	function __construct()
	{
		parent::__construct();
	}
    function display($cachable = false, $urlparams = Array())
    {
		// check session
		$myuser = JFactory::getUser();
		if( !$myuser->id ) {
			$msg = JText::_('JLIB_ENVIRONMENT_SESSION_EXPIRED');
			$link = 'index.php';
			$this->setRedirect($link, $msg);
			return false;
		}
        $model = $this->getModel("formlist","FrontendModel");
        $view = $this->getView("formlist", "html","FrontendView");
        $view->setModel($model, true);
        $view->setLayout( 'default' );  

        $view->display();    
    }  

    function csv()
    {
		// check session
		$myuser = JFactory::getUser();
		if( !$myuser->id ) {
			$msg = JText::_('JLIB_ENVIRONMENT_SESSION_EXPIRED');
			$link = 'index.php';
			$this->setRedirect($link, $msg);
			return false;
		}
		$model = $this->getModel("formlist","FrontendModel");
    	$csvdata = $model->makecsv($myuser);
		$csvdata = mb_convert_encoding($csvdata,"SJIS","UTF-8");
		ob_clean();
		// MIME type
		header("Content-Type: application/octet-stream");
		// output filename
		header("Content-Disposition: attachment; filename=formlist".JHTML::_('date', 'now', 'YmdHis',true).".csv");
		// output data
		echo ($csvdata);
		exit;
    }
}