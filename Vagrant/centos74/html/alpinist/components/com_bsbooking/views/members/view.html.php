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
require_once( JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_bscore'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php' );

class BsbookingViewMembers extends JViewLegacy
{
    function display($tpl = null)
    {
		$document = JFactory::getDocument();
        $js = JURI::base(true).'/components/com_bsbooking/assets/js/bsbmembers.js';
        $document->addScript($js);
		$app = JFactory::getApplication();
		$input = $app->input;
		
		$params = $app->getParams('com_bsbooking');
		$alldiv = $params->get('alldivision',1);
		$divcode = $input->get('divcode');
		$userlist = BscoreHelper::getuserlist($divcode,$alldiv);
        $members = $this->get("Members");
        
        
        $this->assignRef("members", $members);
        $this->assignRef("modify", $modify);
        $this->assignRef("userlist", $userlist);
        $this->assignRef("divcode", $divcode);
        $this->assignRef("alldiv", $alldiv);
        parent::display($tpl);    
    }
    
}