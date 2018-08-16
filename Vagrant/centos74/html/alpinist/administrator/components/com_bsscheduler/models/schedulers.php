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
jimport( 'joomla.application.component.model' );
require_once(JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_bsscheduler'.DIRECTORY_SEPARATOR.'codebase'.DIRECTORY_SEPARATOR.'BSSchedulerConfigurator.php');

class BsschedulerModelSchedulers extends JModelLegacy {

	var $_data;

	function store($data) {
		$row = $this->getTable('Scheduler', 'Table');
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
			}

		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
			}

		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
			}

	return true;
	}

}
?>