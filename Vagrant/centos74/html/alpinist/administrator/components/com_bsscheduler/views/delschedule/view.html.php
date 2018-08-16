<?php
/**
* @Copyright Copyright (C) 2010 dhtmlx.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

/**
 This file is part of dhtmlxScheduler for Joomla.

    dhtmlxScheduler for Joomla is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    dhtmlxScheduler for Joomla is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with dhtmlxScheduler for Joomla.  If not, see <http://www.gnu.org/licenses/>.
**/

// no direct access
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
 
class BsschedulerViewDelschedule extends JViewLegacy
{

	function display($tpl = null)
	{
        if ($this->getLayout()=='default') 
        {

			JToolBarHelper::title(   JText::_('COM_BSSCHEDULER_SCH_MANAGER').'::'.JText::_('SUB_BSSCHEDULER_DELRSV_TITLE'), 'generic.png' );

			JToolbarHelper::deleteList('COM_BSSCHEDULER_CONFIRM_DELETE', 'delschedule.delete', 'JTOOLBAR_DELETE');

			JToolBarHelper::cancel('delschedule.cancel');

            $rows =  $this->get("Items");
            $pagination =  $this->get("Pagination");
    		$state  = $this->get('State');
            $this->assignRef('rows', $rows);
            $this->assignRef('pagination', $pagination);
            $this->assignRef('state', $state);

			$language = JFactory::getLanguage();
			$language->load('com_users');

        }
        parent::display($tpl);


	}
}