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
 * @copyright	Copyright (C) 2009-2010 GROON Project. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: view.html.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
 **/

// no direct access
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
// Blogstone common object
require_once( JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_bscore'.DIRECTORY_SEPARATOR.'helper'.DIRECTORY_SEPARATOR.'bscore.helper.php' );
require_once( JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'toolbar.php' );

class BscoreViewDivlist extends JViewLegacy
{
	
	function display($tpl = null) {
		JToolBarHelper::title(JText::_('COM_BSCORE').':'.JText::_("BSC_DIVISION_MANAGER"));
		JToolBarHelper::publish('divlist.publish');
		JToolBarHelper::unpublish('divlist.unpublish');
		$bar = JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Popup', 'new', 'JTOOLBAR_NEW', "index.php?option=com_bscore&tmpl=component&task=divlist.subnew", 740, 400 );
		JToolBarHelper::deleteList(JText::_("BSC_DELETE_MESSAGE"),'divlist.remove');
		JToolBarHelper::back();
		
		global $comcfg;
		$app = JFactory::getApplication();
		$input = $app->input;
		
		JHTML::stylesheet('administrator/components/com_bscore/assets/css/bscoreadmin.css');
		if( $input->get('layout') == 'edit' ) {
			// set of joomla default validation
			JHTML::_('behavior.formvalidation');
			$data = $this->get('OneData');
			$this->assignRef('data', $data);
		} else {
			$rows = $this->get('Data');
			$pageNav = $this->get('Pagination');
			JHTML::_('behavior.modal');
			$this->assignRef('rows', $rows);
			$this->assignRef('pageNav', $pageNav);
		}
		parent::display($tpl);
	}
	function gettmpllist($active = null) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('template, template AS tmplname');
		$query->from('#__template_styles as s');
		$query->where('s.client_id = 0');
		$query->where('e.enabled = 1');
		$query->leftJoin('#__extensions as e ON e.element=s.template AND e.type='.$db->quote('template').' AND e.client_id=s.client_id');
		$db->setQuery($query);
		$templates = array(0 => JText::_('JDEFAULT'));
		$templates = array_merge( $templates, $db->loadObjectList() );
		if( !$active ) $active = 0;
		$tmpllist = JHTML::_('select.genericlist',   $templates, 'divtmpl', 'class="inputbox" size="1" '. null, 'template', 'tmplname', $active );
		return $tmpllist;
	}
}
?>