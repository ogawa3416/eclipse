<?php
/**
* @Copyright Copyright (C) 2010 dhtmlx.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

// no direct access
defined('_JEXEC') or die;
jimport( 'joomla.application.component.model' );
jimport('joomla.application.component.modellist');

class BsschedulerModelDelschedule extends JModelList {

	var $_id = null;
    
    var $_item = null;

	var $_items = null;
    
    var $_total = null;
	
	var $_pagination = null;

	var $_db = null;

  	function __construct($config = array())
	{

		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'name', 'a.name',
				'username', 'a.username',
				'email', 'a.email',
				'divcode', 'c.divcode',
			);
		}

		parent::__construct($config);
  	}
  		/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function populateState($ordering = "a.name", $direction = "asc")
	{
		$app = JFactory::getApplication('administrator');

		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout', 'default', 'cmd'))
		{
			$this->context .= '.' . $layout;
		}

		// Set up the list limits (not sure why the base class version of this does not work)
		$value = $app->getUserStateFromRequest($this->context.'.list.limit', 'limit', $app->getCfg('list_limit'));
		$limit = $value;
		$this->setState('list.limit', $limit);

		$value = $app->getUserStateFromRequest($this->context.'.limitstart', 'limitstart', 0);
		$limitstart = ($limit != 0 ? (floor($value / $limit) * $limit) : 0);
		$this->setState('list.start', $limitstart);

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		// Check if the ordering field is in the white list, otherwise use the incoming value.
		$value = $app->getUserStateFromRequest($this->context.'.ordercol', 'filter_order', $ordering);
		if (!in_array($value, $this->filter_fields)) {
			$value = $ordering;
			$app->setUserState($this->context.'.ordercol', $value);
			}
		$this->setState('list.ordering', $value);

		// Check if the ordering direction is valid, otherwise use the incoming value.
		$value = $app->getUserStateFromRequest($this->context.'.orderdirn', 'filter_order_Dir', $direction);
		if (!in_array(strtoupper($value), array('ASC', 'DESC', ''))) {
			$value = $direction;
			$app->setUserState($this->context.'.orderdirn', $value);
			}
		$this->setState('list.direction', $value);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_bsscheduler');
		$this->setState('params', $params);

		// List state information.
		parent::populateState($ordering, $direction);

	}

	function getItems()
	{
		if (empty($this->_items))
		{
			$query = $this->_buildQuery();
			$this->_db->setQuery($query, $this->getState('list.start'), $this->getState('list.limit'));
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
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('list.start'), $this->getState('list.limit') );
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
	
	function _buildQuery()
	{

		$wh ='';

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$wh .= ' AND a.id = ' .$this->_db->Quote(substr($search, 3));
			}else{
				$serchwd = "LIKE '%".$search."%'";
        		$wh .= "\n AND (a.name ".$serchwd;
        		$wh .= "\nOR a.username ".$serchwd;
        		$wh .= "\nOR a.email ".$serchwd;
        		$wh .= ")";
			}
	   }

		$sql = "SELECT a.*,b.profile_value,c.divname_s as divname  "
            ." FROM #__users as a "
            ." LEFT JOIN #__user_profiles as b ON b.user_id=a.id AND b.profile_key = 'bsprofile.divcode'"
            ." LEFT JOIN #__bs_division as c ON c.divcode=b.profile_value"
			." WHERE a.name != 'Super User'"
			.$wh
			." ORDER BY ".$this->_db->escape($this->getState('list.ordering', 'a.name')) . ' ' . $this->_db->escape($this->getState('list.direction', 'ASC'));

		return $sql;
	}

	function delete($ids){

		foreach($ids as $delrsv){
			//ユーザのスケジュール削除
			//他人のスケジュールに追加したものは削除しない
			$sql = "DELETE FROM `#__bs_events_rec` "
				." WHERE createdby=".$this->_db->Quote($delrsv)
				." AND user=".$this->_db->Quote($delrsv);
			$this->_db->setQuery($sql);

			try
			{
				$this->_db->execute();
			}
			catch (RuntimeException $e)
			{
				$this->setError($e->getMessage());

				return false;
			}
		}

		return true;
	}
}
?>