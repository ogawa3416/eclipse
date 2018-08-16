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
 * @version		$Id: view.html.php  BsAlpinist ver.2.5.0 $
 **/
// no direct access
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.parameter' );

class BscoreViewUser extends JViewLegacy 
{
    function display($tpl = NULL)
    {
        $app = JFactory::getApplication();
		// Get the page/component configuration
		$document	= JFactory::getDocument();

		
		$params = clone($app->getParams('com_bscore'));
		// Set page title
		$menus	= $app->getMenu();
		$menu	= $menus->getActive();
		if (is_object( $menu )) {
			$menu_params = new JRegistry;
			$menu_params->loadString($menu->params);
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title', JText::_('BSC_SELECT_USER_TITLE') );
			}
		} else {
			$params->set('page_title',JText::_('BSC_SELECT_USER_TITLE') );
		}
		$document->setTitle( $params->get( 'page_title' ) );
		
		$this->assignRef('params', $params);
        $rows = $this->get("Items");
        $pagination = $this->get("Pagination");
        $divcode = $this->get("Divcode");
        $searchword = $this->get("SearchWord");
        $this->assignRef("rows", $rows);
        $this->assignRef("pagination", $pagination);
        $this->assignRef("divcode", $divcode);
        $this->assignRef("searchword", $searchword);
        parent::display();    
    }
    
}