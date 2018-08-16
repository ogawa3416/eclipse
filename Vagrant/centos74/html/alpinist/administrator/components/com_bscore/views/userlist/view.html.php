<?php
/**
 * BlogStone component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		GROON UGMS
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2010 GROON Project. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: view.html.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.3 $
 **/

// no direct access
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
// Blogstone common object
require_once( JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_bscore'.DIRECTORY_SEPARATOR.'helper'.DIRECTORY_SEPARATOR.'bscore.helper.php' );
require_once( JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'toolbar.php' );

class BscoreViewUserlist extends JViewLegacy
{
	
	function display($tpl = null) {
		JToolBarHelper::title(JText::_('COM_BSCORE').':'.JText::_('BSC_ADDITIONAL_USER_INFORMATION'));
		JToolBarHelper::back();
		
		$app = JFactory::getApplication();
		$view = 'userlist';
		$search =  $app->getUserStateFromRequest( "view{$view}search",	'search','','string' );
		
		$rows = $this->get('Data');
		$pageNav = $this->get('Pagination');
		$divs = $this->get('Division');
		
		$divs[] = JHTML::_('select.option',  '0', '- '. JText::_('BSC_ALL_DIVISION') .' -', 'divcode', 'divname_s' );
		$divs = array_merge( $divs, $this->get('Divlist') );
		$actdiv = $app->getUserStateFromRequest( "view{$view}divcode",	'divcode','','string' );
		if( !$actdiv )  $actdiv = '0';
		$att = 'onchange="document.adminForm.limitstart.value=0;submit(this.form);return false;"';
		$divlist = JHTML::_('select.genericlist',   $divs, 'divcode', 'class="inputbox" size="'. 1 .'" '. $att, 'divcode', 'divname_s',$actdiv );
		JHTML::stylesheet('administrator/components/com_bscore/assets/css/bscoreadmin.css');

		$this->assignRef('rows', $rows);
		$this->assignRef('divlist', $divlist);
		$this->assignRef('search', $search);
		$this->assignRef('pageNav', $pageNav);
		parent::display($tpl);
	}
}
?>