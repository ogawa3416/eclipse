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
 * @version		$Id: view.html.php BsAlpinist ver.2.5.0 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
require_once( JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_bsbooking'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php'  );

class BsbookingViewResource extends JViewLegacy
{
    function display( $mode='list', $tpl = null )
    {
        $document = JFactory::getDocument();
        if ($mode==='list')
        {
            $document->setTitle('BsBooking :: ' .JText::_('COM_BSBOOKING_RESOURCE_MANAGER'));
            JToolBarHelper::title( 'BsBooking :: '.JText::_('COM_BSBOOKING_RESOURCE_MANAGER') );
            JToolBarHelper::addNew('resource.add');
            JToolBarHelper::editList( 'resource.edit' );
            JToolBarHelper::publishList( 'resource.publish' );
            JToolBarHelper::unpublishList( 'resource.unpublish' );
            JToolBarHelper::divider();
            JToolBarHelper::deleteList(JText::_('COM_BSBOOKING_DELETECONFIRM'), 'resource.remove' );
            JToolBarHelper::custom( 'cpanel.display', 'cpanel', 'cpanel', 'CPanel', false, false );
            
            $rows = $this->get( 'Items' );
            $pagination = $this->get( 'Pagination' );
            $schedule_filter = $this->get('ScheduleFilter');
            $this->assignRef( 'rows', $rows );
            $this->assignRef( 'pagination', $pagination );
            $this->assignRef( 'schedule_filter', $schedule_filter );
        }else{
            JHTML::_('behavior.tooltip');
            JToolBarHelper::title( 'BsBooking :: '.JText::_('COM_BSBOOKING_RESOURCE_EDIT') );
            JToolBarHelper::apply( 'resource.apply' );
            JToolBarHelper::save( 'resource.save' );
            JToolBarHelper::cancel( 'resource.cancel' );
			
			$row = $this->get( 'Item' );
            $schedule_id = $this->get('ScheduleId');
			
			$divs[] = JHTML::_('select.option',  '0', '- '. JText::_('DIVISION_SELECTION') .' -' );
			$divs = array_merge( $divs, BsbookingHelper::getAllListDiv() );
			$att = '';
			$alldivlist = JHTML::_('select.genericlist',   $divs, 'divcode', 'class="inputbox" size="1" '. $att, 'value', 'text', $row->divcode );
			JHTML::_('behavior.formvalidation');
			$this->assignRef('alldivlist', $alldivlist);
			
            $this->assignRef( 'row', $row );
            $this->assignRef( 'schedule_id', $schedule_id );
        }
        parent::display( $tpl );
    }
    
}