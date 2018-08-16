<?php
/**
* @Copyright Copyright (C) 2010 dhtmlx.com
* @Copyright Copyright (C) 2010 Groon solutions (by modified portion)
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

/**
 This file is part of BSScheduler for Joomla.

    BSScheduler for Joomla is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    BSScheduler for Joomla is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with BSScheduler for Joomla.  If not, see <http://www.gnu.org/licenses/>.
**/

// no direct access
defined('_JEXEC') or die;
jimport( 'joomla.application.component.view');
jimport( 'joomla.html.parameter' );

class BSSchedulerViewBSScheduler extends JViewLegacy
{
	var $myname;
	var $myuserid;
	var $divcode;
	
	function display($tpl = null)
	{
		$document = JFactory::getDocument();
        
		$mainframe = JFactory::getApplication();
		$model = $this->getModel();
		$scheduler = $model->getScheduler();
		$document	= JFactory::getDocument();
		
		// Get the page/component configuration
		$paramm = clone($mainframe->getParams('com_bsscheduler'));
		// Set page title
		$menus	= $mainframe->getMenu();
		$menu	= $menus->getActive();
		if (is_object( $menu )) {
			$menu_params = new JRegistry;
			$menu_params->loadString($menu->params);
			if (!$menu_params->get( 'page_title')) {
				$paramm->set('page_title', JText::_('BSC_PAGETITLE') );
			}
		} else {
			$paramm->set('page_title',JText::_('BSC_PAGETITLE') );
		}
		$document->setTitle( $paramm->get( 'page_title' ) );

		// Parameters
		if( !$paramm->get('show_title') ) $paramm->def('show_title',1);
		$paramm->def('show_page_title', 	$paramm->get('show_title'));
		
		$userlist = null;
		$divlist = null;
		$user = $model->getUser();
		if( !$user->id ) {
			JError::raiseWarning( 0, JText::_('COM_BSSCHEDULER_NOTLOGIN') );
		}
		$this->userid = $user->id;
		$this->divcode = $user->divcode;
		$myu = JFactory::getUser();
		$this->myuserid = $myu->id;
		$this->myname = $myu->name;
		$uri = JURI::getInstance();
		$action = $uri->toString();
		
		$this->assignRef('scheduler', $scheduler);
		$this->assignRef('paramm', $paramm);
		$this->assignRef('user', $user);
		$this->assignRef('action', 	$action);
		parent::display($tpl);
	}
	
	function userlist($size,$javascript )
	{
		$_db = JFactory::getDBO();

		$query = "SELECT u.`id` `value`,u.`name` `text` " 
			."\n FROM #__users as u, #__bs_accontrol a, #__bs_users_detail b, #__bs_users_detail c  "
			."\n WHERE u.block = 0 AND a.com_group = 'com_bsscheduler'"
			."\n AND c.userid = u.id AND b.userid = ".$_db->Quote($this->myuserid)
			."\n AND a.divkey = c.divcode"
			."\n AND c.divcode = ".$_db->Quote($this->divcode)
			."\n AND ( a.ondiv like concat('%/',b.divcode,'/%')"
			."\n OR  a.onuser like concat('%/',b.userid,'/%' ))"
			."\n ORDER BY u.name ASC";
			;
		$_db->setQuery( $query );
		$users = $_db->loadObjectList();
		$usernames = JHTML::_('select.genericlist',   $users, 'useridx', 'class="inputbox" size="'. $size .'" '. $javascript, 'value', 'text', $this->userid );
		return $usernames;
	}
}