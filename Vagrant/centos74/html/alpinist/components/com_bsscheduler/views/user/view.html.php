<?php
/**
 * BsScheduler component for Joomla.
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
 * @version		$Id: view.html.php BsAlpinist ver2.5.0 $
 **/
// Check to ensure this file is included in Joomla!
// no direct access
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );
require_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php');

class BSSchedulerViewUser extends JViewLegacy
{
    function display($tpl = NULL)
    {
    	$document = JFactory::getDocument();
        
        $rows = $this->get("Items");
        $pagination =  $this->get("Pagination");
        $user = $this->get("User");
		
		$divs[] = JHTML::_('select.option',  '0', '--'. JText::_('ALL_DIVISION') .'--', 'divcode', 'divname_s' );
		$divs = array_merge( $divs, BsschedulerHelper::getDivlist() );
		$actdiv = $this->get("Divcode");
//		$att = 'onchange="submit(this.form)"';
		$att = 'onchange="BsFormsubmit(this.form,0)"';
		$divlist = JHTML::_('select.genericlist',   $divs, 'divcode', 'class="inputbox" size="'. 1 .'" '. $att, 'divcode', 'divname_s',$actdiv );
		$searchword = $this->get('SearchWord');

		JHtml::_('formbehavior.chosen', 'select');
		
        $this->assignRef("rows", $rows);
        $this->assignRef("pagination", $pagination);
        $this->assignRef("divlist", $divlist);
        $this->assignRef("searchword", $searchword);
        parent::display($tpl);    
    }
    
}