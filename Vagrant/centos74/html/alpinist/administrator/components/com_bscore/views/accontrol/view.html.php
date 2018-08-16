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
 * @copyright	Copyright (C) 2009-2010 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: view.html.php  BsAlpinist ver.2.5.0 $
 **/

// no direct access
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
// Blogstone common object
require_once( JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_bscore'.DIRECTORY_SEPARATOR.'helper'.DIRECTORY_SEPARATOR.'bscore.helper.php' );
require_once( JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'toolbar.php' );

class BscoreViewAccontrol extends JViewLegacy
{
	
	function display($tpl = null) {
		global $comcfg;
		JHTML::stylesheet('administrator/components/com_bscore/assets/css/bscoreadmin.css');
		$app = JFactory::getApplication();
		$input = $app->input;
		
		if( $input->get('layout') == 'dedit' ) {
			$data = $this->get('OneData');
        	$selections = array();
			foreach($data->divlist as $row) {
				$selections[] = JHTML::_('select.option', $row->value,$row->text);
    	    }
        	$accdivlist = JHTML::_('select.genericlist',   $selections, 'selections[]', 'class="inputbox" size="15" multiple="multiple"', 'value', 'text', $data->ondivlist, 'selections' );
			JHTML::_('behavior.formvalidation');
			$this->assignRef('data', $data);
			$this->assignRef('accdivlist', $accdivlist);
		} else if( $input->get('layout') == 'uedit' ) {
			$data = $this->get('OneData');
        	$selections = array();
			foreach($data->userlist as $row) {
				$selections[] = JHTML::_('select.option', $row->value,$row->text);
    	    }
        	$accuserlist = JHTML::_('select.genericlist',   $selections, 'selections[]', 'class="inputbox" size="15" multiple="multiple"', 'value', 'text', $data->onuserlist, 'selections' );

			$cdiv = $input->get('selectdiv');
			$divs[] = JHTML::_('select.option',  '0', '- '. JText::_('BSC_ALL_DIVISION') .' -' );
			$divs = array_merge( $divs, $this->get('AllListDiv') );
			$att = 'onchange="Joomla.submitform(\'accontrol.usermod\');"';
			$alldivlist = JHTML::_('select.genericlist',   $divs, 'selectdiv', 'class="inputbox" size="1" '. $att, 'value', 'text', $cdiv );
			JHTML::_('behavior.formvalidation');
			$this->assignRef('data', $data);
			$this->assignRef('accuserlist', $accuserlist);
			$this->assignRef('alldivlist', $alldivlist);
		} else if( $input->get('layout') == 'gredit' ) {
			// set of joomla default validation

			$divs[] = JHTML::_('select.option',  '0', '- '. JText::_('BSC_SELECT_DIVISION') .' -' );
			$divs = array_merge( $divs, $this->get('AllListDiv') );
			$att = '';
			$alldivlist = JHTML::_('select.genericlist',   $divs, 'divkey', 'class="inputbox required" size="1" '. $att, 'value', 'text', '0' );
			JHTML::_('behavior.formvalidation');
			$this->assignRef('alldivlist', $alldivlist);
			$acclist = explode(",", $comcfg['accgroup']);
			for( $i=0;$i<count($acclist);$i++) {
				$acc[$i] = new stdClass();
				$acc[$i]->value = $acclist[$i];
				$acc[$i]->text = $acclist[$i];
			}
			$accgrlist = JHTML::_('select.genericlist',   $acc, 'com_group', 'class="inputbox" size="1" '. null, 'value', 'text', $input->get('com_group') );
			$this->assignRef('accgrlist', $accgrlist);
		} else {
			$com_group = $input->get('com_group');
			JToolBarHelper::title(JText::_('COM_BSCORE').':'.JText::_("BSC_ACCESS_CONTROL_MANAGER"));
			$bar =  JToolBar::getInstance('toolbar');
			$bar->appendButton( 'Popup', 'new', 'JTOOLBAR_NEW', "index.php?option=com_bscore&tmpl=component&task=accontrol.grmod&com_group=".$com_group, 500, 180 );
			JToolBarHelper::deleteList(JText::_("BSC_DELETE_MESSAGE"),'accontrol.remove');
			JToolBarHelper::back();
			
			$rows = $this->get('Data');
			$model = $this->getModel();
			$att = 'onchange="Joomla.submitform(\'accontrol.show\');"';
			$acclist = $model->getAccgrlist('com_group', $input->get('com_group'),$att);
			$pageNav = $this->get('Pagination');
			JHTML::_('behavior.modal');
			$this->assignRef('rows', $rows);
			$this->assignRef('acclist', $acclist);
			$this->assignRef('pageNav', $pageNav);
		}
		parent::display($tpl);
	}
}
?>