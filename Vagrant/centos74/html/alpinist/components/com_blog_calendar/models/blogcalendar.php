<?php
/**
* @package		Blog Calendar Reload
* @author		Juan Padial
* @authorweb	        http://www.bloogie.es
* @license		GNU/GPL
* @version		$Id: default.php 21518 2011-06-10 21:38:12Z chdemko $
* 				modified by Groon solutions
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

// import Joomla modelist library
jimport('joomla.application.component.modellist');

require_once (JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_content'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'route.php');
require_once( JPATH_ROOT.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_bscore'.DIRECTORY_SEPARATOR.'helper'.DIRECTORY_SEPARATOR.'bscore.helper.php' );

class BlogCalendarModelBlogCalendar extends JModelList
{
	//this functions gets the articles on a given date
	function getContent($params,$year,$month,$day)
	{
		$app = JFactory::getApplication();
		$input = $app->input;

		$fullmonth = false;
		$fullyear = false;
		$full = false;
		if(!$day)
		$fullmonth=true;
		
		
		if($fullmonth && !$month)
		$fullyear=true;
		
		if($fullyear && !$year)
		$full=true;

// for BsAlpinist 2011-11-15
//		$dateformat_dmy = $params->get('dateformat_dmy','%A, %d %B %Y');
//		$dateformat_my = $params->get('dateformat_my','%B %Y');
//		$dateformat_y = $params->get('dateformat_y','%Y');
		$dateformat_dmy = $params->get('dateformat_dmy','l, d F Y');
		$dateformat_my = $params->get('dateformat_my','F Y');
		$dateformat_y = $params->get('dateformat_y','Y');
		
		$catids = $params->get('category_ids');

		$limitstart=(int) $input->get('limitstart',0,'uint');
		$count=(int) $params->get('count');
		
		$juser = JFactory::getUser();
		$groups = implode(',', $juser->getAuthorisedViewLevels());
        $access = !JComponentHelper::getParams('com_content')->get('show_noauth');
		
		
		$db = JFactory::getDBO();
		
		$nullDate	= $db->getNullDate();
		jimport('joomla.utilities.date');
		$dateNow = new JDate();
		$now = $dateNow->toSql();
		
		
		$offset= $app->getCfg('offset');		
		
		if($fullmonth){
		$date[0]= mktime(0,0,0,$month, 1,$year);
		$date[1]= mktime(0,0,0, $month+1, 1, $year);
		}
		else{
		$date[0]= mktime(0,0,0, $month, $day, $year);
		$date[1]= mktime(0,0,0, $month, $day+1, $year);
		}
		
		if($fullyear){
		$date[0]= mktime(0, 0, 0, 1, 1, $year);
		$date[1]= mktime(0, 0, 0, 1, 1, $year+1);
		}
		
		$date[0]= new JDate(date('Y-m-d',$date[0]), $offset);
		$date[1]= new JDate(date('Y-m-d',$date[1]), $offset);
		
				
		$date[0]= $date[0]->toSql();
		$date[1]= $date[1]->toSql();
		
		
		
		
		//this expression selects all the articles that match the date, and that are published at the moment
		$where		= 'a.state = 1'
			. ($full? '' : ' AND  a.created >= '. $db->Quote($date[0]) .' AND a.created < '. $db->Quote($date[1]))
			. ' AND ( a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' )'
			. ' AND ( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )'
			;
		$catCondition = '';
                 if (count($catids)>0 && $catids[0]!='')
		  {
		        JArrayHelper::toInteger( $catids );
			$catCondition = ' AND (cc.id=' . implode( ' OR cc.id=', $catids ) . ')';
		  }
		$query = 'SELECT a.id,a.catid '.
				' FROM #__content AS a'.
				' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
				' WHERE '. $where .
				' AND a.language IN (\''.JFactory::getLanguage()->getTag().'\',\'*\')'.
				($access ? ' AND a.access IN ('.$groups.')' : '').
				' AND cc.published = 1' .  
				($catCondition != '' ? $catCondition : '' ); //add the $catCondition if $catid exists
				
		$db->setQuery($query); //this query is used to get the total number of articles
		
		$total= count($db->loadObjectList()); //the total number of articles
		$query ='SELECT a.*, '.
				' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'.
				' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug,'.
				' cc.title as catTitle,'.
				' auth.name as author'.
				' FROM #__content AS a'.
				' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
				' INNER JOIN #__users AS auth ON auth.id = a.created_by' .
				' WHERE '. $where .
				' AND a.language IN (\''.JFactory::getLanguage()->getTag().'\',\'*\')'.
				($access ? ' AND a.access IN ('.$groups.')' : '').
				' AND cc.published = 1' .
				($catCondition != '' ? $catCondition : '' ). //add the $catCondition if $catid exists
				' ORDER BY created DESC'; //order by date created descending
				
		//set the query 
		$db->setQuery( $query, $limitstart, $count );
		//and load the results
		$results = $db->loadObjectList();

		$daycount= $day; $monthcount= $month; $yearcount= $year;
		$i=1; $j=1;
		//this foreach adds a specific link to each result
		foreach($results as $key=>$result){
		
		  $createdDate= new JDate( $results[$key]->created, $offset);		
		  $results[$key]->created_new_day=""; //empty if it's not a new day in the list
		
		  //this checks if the created day is a new day in the list
		  if($createdDate->format('d')!=$daycount || $createdDate->format('m')!= $monthcount){ 
// for BsAlpinist
//		    $results[$key]->created_new_day = $createdDate->toFoformatrmat($dateformat_dmy);
			$results[$key]->created_new_day = JHtml::_('date', $createdDate, JText::_($dateFormat));
		   }
		 
		   $results[$key]->date = $createdDate->format(JText::_('DATE_FORMAT_LC2'));		 
		   $daycount  = $createdDate->format('d');
		   $monthcount= $createdDate->format('m');
		   $yearcount = $createdDate->format('Y');
		 
		  $results[$key]->link=JRoute::_(ContentHelperRoute::getArticleRoute($results[$key]->slug, $results[$key]->catslug, $results[$key]->sectionid),false);
		  
		  $results[$key]->text = $results[$key]->introtext.($params->get('show_fulltext')? $results[$key]->fulltext : '');

		  $j++;
		  $i++;
		}
		
		
		$dateFormat=$dateformat_dmy;
		
		if($fullmonth){
		 $day='15'; $dateFormat=$dateformat_my;
	        }
		if($fullyear){
		 $month='06'; $dateFormat=$dateformat_y;
		}
		
		if($full){
		 $year='2008'; $dateFormat="";
		}
			
		$date=new JDate(mktime(12,30,30, $month, $day, $year)); 
		
		/*$results['date'] = JText::_($i>1? 'ARTICLES' : 'NOARTICLES') . ' '; //Articles published on OR No articles published on*/
// for BsAlpinist
//		$results['date'] = $date->format($dateFormat);  				  //the date
		$results['date'] = JHtml::_('date', $date, JText::_($dateFormat));	  //the date
		
		$full? $results['date']=JText::_('COM_BLOG_CALENDAR_ALLARTICLES') : ''; //if it's displaying all the articles */
		
		$results['total']     = $total; 
		$results['limitstart']= $limitstart;	//Pagination variables 
		$results['limit']     = $count;
		
		return $results;
}
}
?>