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
 * @version		$Id: view.html.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
 **/
// no direct access
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );

class BsbookingViewUser extends JViewLegacy
{
    function display($tpl = NULL)
    {
    	$document = JFactory::getDocument();
        
        $rows =  $this->get("Items");
        $pagination =  $this->get("Pagination");
        $divcode =  $this->get("Divcode");
        $searchword =  $this->get("SearchWord");
        
        JHtml::_('formbehavior.chosen', 'select');
        
        $this->assignRef("rows", $rows);
        $this->assignRef("pagination", $pagination);
        $this->assignRef("divcode", $divcode);
        $this->assignRef("searchword", $searchword);
        parent::display($tpl);    
    }
    
}