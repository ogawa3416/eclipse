<?php
/**
 * JComments plugin for standart content objects support
 *
 * @version 2.1
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2009 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/

class jc_com_blogstone extends JCommentsPlugin
{
        var $component = 'com_blogstone';
        var $tableName = '#__bs_pj_report';
	var $keyField = 'reportno';
	var $titleField = 'title';
	var $ownerField = 'createdby';

	function getTitles($ids)
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery( 'SELECT  b.pjname AS title, a.reportno AS id '
						.' FROM #__bs_pj_report a LEFT JOIN #__bs_pj_master b'
						.' ON a.pjid = b.pjid '
						.' WHERE a.reportno IN (' . implode(',', $ids) . ')' 
						);
		return $db->loadObjectList('id');
	}

	function getObjectTitle($id)
	{
		$db = JCommentsFactory::getDBO();
		// we need select primary key for JoomFish support
		$db->setQuery( 'SELECT  b.pjname AS title, a.reportno AS id '
						.' FROM #__bs_pj_report a LEFT JOIN #__bs_pj_master b'
						.' ON a.pjid = b.pjid '
						.' WHERE a.reportno = ' .$id 
						);
		return $db->loadResult();
	}

	function getObjectLink($id)
	{
		$mainframe = JFactory::getApplication();
		
		$query = 'SELECT a.reportno AS id, c.access,b.pjid ' 
				.' FROM #__bs_pj_report AS a, #__bs_pj_master AS b, #__categories AS c'
				.' WHERE a.reportno = ' . intval($id)
				.' AND a.pjid = b.pjid AND b.catid = c.id '
				;

		$db = JCommentsFactory::getDBO();
		$db->setQuery( $query );
		$row = $db->loadObject();

		$user = JFactory::getUser();
			
		$groups	= $user->getAuthorisedViewLevels();
		if( in_array($row->access,$groups ) ) {
//			$link = JRoute::_("index.php?option=com_blogstone&view=pjreport&reportno=".intval($id));
			$link = JRoute::_("index.php?option=com_blogstone&view=repolist&pjid=".intval($row->pjid));
		} else {
			$link = JRoute::_("index.php?option=com_user&task=register");
		}
		return $link;
	}

	function getObjectOwner($id)
	{
		$db = & JCommentsFactory::getDBO();
		$db->setQuery( 'SELECT createdby, pjid FROM #__bs_pj_report WHERE reportno = ' . $id );
		$userid = $db->loadResult();
		
		return $userid;
	}

	function getCategories($filter = '')
	{
		$db = JCommentsFactory::getDBO();

		$query = "SELECT c.id AS `value`, c.title AS `text`"
			. "\n FROM  #__categories AS c "
			. (($filter != '') ? "\n WHERE c.id IN ( ".$filter." )" : '')
			. "\n ORDER BY c.name"
			;
		$db->setQuery( $query );
		$rows = $db->loadObjectList();

		return $rows;
	}
}
?>