<?php
/**
 * BsWorkFlow component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		BlogStone UGMS
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2011 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: mystatus.php 125 2012-04-01 23:00:45Z BsAlpinist ver.2.4.1 $
 **/
// no direct access
defined('_JEXEC') or die;
jimport( 'joomla.application.component.model' );

class FrontendModelFormlist extends JModelLegacy
{
    var $_items = null;
    var $_item = null;
    
    var $_total = null;
    
    var $_pagination = null;
    
    var $_sorting = null;
	
    var $_fmid = null;
    var $_fromdate = null;
    var $_todate = null;
    
    var $_labels = array();
    
    function __construct($config = array())
    {
        parent::__construct($config);
        
		$app = JFactory::getApplication();
		$input = $app->input;
 		
		$params = $app->getParams('com_jforms');
		$this->_fmid = $input->getInt( 'id' );
		$this->_fromdate = $params->get('fromdate');
		$this->_todate = $params->get('todate');
		
        // Get pagination request variables
		$params->def('display_num', $app->getCfg('list_limit'));
		$default_limit = $params->get('display_num');
        $limit = $app->getUserStateFromRequest('com_jforms'.$this->_fmid.$input->get('view').'limit', 'limit', $default_limit, 'int');
        $limitstart = $input->get('limitstart', 0, 'uint');
 
        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
 		$search = $input->getStrin('search', '' );
 		
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
        $this->setState('search', $search);
    }   
      
    function getItems($all=false)
    {
		if (empty($this->_items))
		{
			$query = $this->_buildQuery();
			if( $all ) {
				$this->_db->setQuery($query);
			} else {
				$this->_db->setQuery($query, $this->getState('limitstart'), $this->getState('limit'));
			}
			$this->_items = $this->_db->loadObjectList();
		}
		return $this->_items;        
    }

   	function getTotal()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
		
	}
   	function getLabels()
	{
		$query = "SELECT a.parameter_value item,b.parameter_value label, a.plugin_name"
				." FROM #__jforms_parameters a, #__jforms_tparameters b "
				."\n WHERE a.pid = b.pid AND a.parameter_name = 'hash' "
				."\n AND b.parameter_name = 'label' AND a.plugin_name != 'button'"
				."\n AND a.fid = ".$this->_db->Quote($this->_fmid)
				."\n ORDER BY b.pid";
		$this->_db->setQuery($query);
		$labels = $this->_db->loadObjectList();

		$this->_labels = array_merge($this->_labels,$labels);
		return $this->_labels;
		
	}
 	function getPagination()
  	{
        // Load the content if it doesn't already exist
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
        }
        return $this->_pagination;
  	}

	
	function _buildQuery()
	{
		$my = JFactory::getUser();
		$where = ' WHERE 1 ';
 		$query = "SELECT plugin_name,parameter_name,parameter_value FROM #__jforms_parameters "
				."\n WHERE fid = ".$this->_db->Quote($this->_fmid)
				;
		$this->_db->setQuery($query);
		$defarr = $this->_db->loadObjectList();
		$tablename = '';
		$item_date = '';
		for($i=0;$i<count($defarr);$i++) {
			if( $defarr[$i]->plugin_name == 'Database' && $defarr[$i]->parameter_name == 'tableName' ) {
				$tablename = $defarr[$i]->parameter_value;
			} else if( $defarr[$i]->plugin_name == 'entrydate' && $defarr[$i]->parameter_name == 'hash' ) {
				$item_date = $defarr[$i]->parameter_value;
			} 
		}
		if( strlen($tablename) > 0 ) {
			$tablename = '#__jforms_'.$tablename;
		} else {
			JError::raiseError( 500, JText::_('JFM_ERR_SETTING') );
		}
		if( strlen($item_date) > 0 && strlen($this->_fromdate) > 0 ) {
			$where .= ' AND '.$item_date.' >= "'.$this->_fromdate.'"';
		}
		if( strlen($item_date) > 0 && strlen($this->_todate) > 0 ) {
			$where .= ' AND '.$item_date.' <= "'.$this->_todate.'"';
		}
		if( strlen($item_date) > 0 && count($this->_labels)==0 ) {

				$this->_labels[0] = new stdClass();
				$this->_labels[0]->item = $item_date;
				$this->_labels[0]->label = JText::_('JFM_ENTRYDATE_LABEL');
		}
			
		$query = "SELECT * FROM ".$tablename.$where;	
        $query .= "\n ORDER BY id DESC";	
		return $query;
	}  
	function makecsv($user)
	{
		$rows = $this->getItems(true);
		$labels = $this->getLabels();

		$csvdata = '';
		if( count($labels) < 1 ) {
			$csvdata =  JText::_('COM_FULFILL_CSVNOTFOUND');
			return $csvdata;
		}
		$csvdata .= '"ID"';
		foreach($labels as $label) {
			$csvdata .= ','.'"'.htmlspecialchars($label->label,ENT_QUOTES).'"';
		}
		$csvdata .= "\n";
		if( count($rows) < 1 ) {
			$csvdata .=  JText::_('COM_FULFILL_CSVNOTFOUND');
			return $csvdata;
		}
		
		for($i=0;$i<count($rows);$i++){
			$row = $rows[$i];
			$csvdata .= '"'.$row->id.'"';
			
			foreach($labels as $label){
				$csvdata .= ',"';
				if( property_exists($row,$label->item) ) {
					$csvdata .= htmlspecialchars($row->{$label->item},ENT_QUOTES); 
				}
				$csvdata .= '"';
			}
			$csvdata .= "\n";
		}
		return $csvdata;
	} 
}