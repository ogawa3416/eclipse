<?php
/**
 * BsBooking component for Joomla.
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
 * @version		$Id: user.php BsAlpinist ver.2.5.0 $
 **/
// no direct access
defined('_JEXEC') or die;

jimport( 'joomla.application.component.controller' );

class BsbookingControllerUser extends JControllerLegacy
{
    function getlist()
    {
    	$myuser = JFactory::getUser();
		if( !$myuser->id ) {
			$msg = JText::_('JLIB_ENVIRONMENT_SESSION_EXPIRED');
			$link = 'index.php';
			$this->setRedirect($link, $msg);
			return false;
		}
        $model = $this->getModel("User");
        
        $view = $this->getView("User", "html");
        $view->setModel($model, true);
                
        $view->display();    
    }    
}