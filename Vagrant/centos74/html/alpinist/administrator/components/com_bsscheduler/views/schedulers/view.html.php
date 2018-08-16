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

 
//class SchedulersViewSchedulers extends JViewLegacy
class BsschedulerViewSchedulers extends JViewLegacy
{

	function display($tpl = null)
	{
		JToolBarHelper::title(   JText::_('COM_BSSCHEDULER_SCH_MANAGER'), 'generic.png' );
//		JToolBarHelper::custom("default_xml", 'default', '', 'Default', false, false);
		JToolBarHelper::apply('save');

        $document = JFactory::getDocument();
		$document->setMetadata('X-UA-Compatible','IE=emulateIE8',true);
//		$this->document->setMetadata('X-UA-Compatible','IE=emulateIE8',true);
//		$this->document->setMetadata('content', );

		JToolBarHelper::cancel();
		$options = $this->get( 'Data');
		$this->assignRef('options', $options);
		parent::display($tpl);
	}
}