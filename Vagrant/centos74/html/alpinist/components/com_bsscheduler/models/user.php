<?php
// Check to ensure this file is included in Joomla!
// no direct access
defined('_JEXEC') or die;
jimport( 'joomla.application.component.model' );

class BSSchedulerModelUser extends JModelLegacy 
{
    var $_items = null;
    
    var $_total = null;
    
    var $_pagination = null;
    
    var $_sorting = null;
	
    var $_divcode = null;
    
    function __construct($config = array())
    {
        parent::__construct($config);
        
		$app = JFactory::getApplication();
		$input = $app->input;
 
        // Get pagination request variables
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
        $limitstart = $input->get('limitstart', 0,'uint');
 
        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
 		$search = $input->getString('search', '' );
 		
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
        $this->setState('search', $search);
    }   
      
    function getItems()
    {
		if (empty($this->_items))
		{
			$query = $this->_buildQuery();
			$this->_db->setQuery($query, $this->getState('limitstart'), $this->getState('limit'));
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
	
 	function getPagination()
  	{
        // Load the content if it doesn't already exist
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
        }
        return $this->_pagination;
  	}
  	
	/**
	 * Get search key word
	 */
	function getSearchWord()
	{
		return $this->getState('search');	
	}
	function getDivcode()
	{
		return $this->_divcode;
	}
	
	function _buildQuery()
	{
		$app = JFactory::getApplication();
		$input = $app->input;
		
		$my = JFactory::getUser();
		$sql = "SELECT u.*, aa.divcode,aa.divname " 
			."\n FROM #__users as u, #__bs_division aa, #__bs_accontrol a, #__bs_users_detail b, #__bs_users_detail c  "
			."\n WHERE u.block = 0 AND a.com_group = 'com_bsscheduler'"
			."\n AND c.userid = u.id AND b.userid = ".$this->_db->Quote($my->id)
			."\n AND a.divkey = c.divcode"
			."\n AND ( a.ondiv like concat('%/',b.divcode,'/%')"
			."\n OR  a.onuser like concat('%/',b.userid,'/%' ))"
			."\n AND c.divcode = aa.divcode AND aa.div_stat = 1 "
			;

		$this->_divcode = $input->get('divcode');
		$where_add = '';
		if( $this->_divcode == '0' ) {
			$where_add = '';
		} else if( $this->_divcode ) {
			$where_add = ' AND aa.divcode = '.$this->_db->Quote($this->_divcode).' ';
		} else {
			$apparams = $app->getParams('com_bsscheduler');
			if( !$apparams->get('alldivision',1) ) {
				// get user division
				if( $my->id ) {
					$query = ' SELECT a.divcode'
						.' FROM #__bs_division a,#__bs_users_detail b '
						.' WHERE b.userid = '.$my->id
						.' AND b.divcode = a.divcode AND a.div_stat = 1 ' 
					;
					$this->_db->setQuery( $query );
					$divcode = $this->_db->loadObject();
					if( $divcode->divcode ) {
						$this->_divcode = $divcode->divcode;
						$where_add = ' AND aa.divcode = '.$this->_db->Quote($divcode->divcode).' ';
					} 
				}
			}
		}
		$sql .= $where_add;
		
		$search = $this->getSearchWord();
		if ($search != '')	
		{
            $search = $this->_db->Quote("%".$search."%");
			$sql .= " AND (u.name LIKE ".$search
                ." OR u.email LIKE ".$search." OR u.username LIKE ".$search
                .")";
		}	
		
        $sql .= "\n ORDER BY aa.divcode,u.name ASC";	
		return $sql;
	}    

}