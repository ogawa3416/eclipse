<?php
/**
* @package		Blog Calendar
* @author		http://www.bloogie.es
* Copyright 	Copyright (C) 2010 Groon solutions (by modified portion) ver.2.3
* @license		GNU/GPL
*/


// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_content'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'route.php');
require_once (dirname(__FILE__).DIRECTORY_SEPARATOR.'calendarClass.php');


class modBlogCalendarHelper
{
	
	function showCal(&$params,$year,$month,$day='',$ajax=0,$modid) //this function returns the html of the calendar for a given month
	{
	
		$language= JFactory::getLanguage(); //get the current language
		$language->load( 'mod_blog_calendar', JPATH_SITE.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'mod_blog_calendar' );
		$article= $language->_('MOD_BLOG_CALENDAR_VALUEARTICLE');
		$articles= $language->_('MOD_BLOG_CALENDAR_VALUEARTICLES'); //this strings are used for the titles of the links
		$article2= $language->_('MOD_BLOG_CALENDAR_VALUEARTICLE2');
	
		$cal = new MyCalendar; //this object creates the html for the calendar
		$dayNamLen= $params->get('cal_length_days');
	
		$cal->dayNames = array(JString::substr(JText::_( 'SUN' ),0,$dayNamLen), JString::substr(JText::_( 'MON' ),0,$dayNamLen),
		JString::substr(JText::_( 'TUE' ),0,$dayNamLen), JString::substr(JText::_( 'WED' ),0,$dayNamLen),
		JString::substr(JText::_( 'THU' ),0,$dayNamLen),	JString::substr(JText::_( 'FRI' ),0,$dayNamLen),
		JString::substr(JText::_( 'SAT' ),0,$dayNamLen));

		$cal->monthNames = array(JText::_( 'JANUARY' ), JText::_( 'FEBRUARY' ), JText::_( 'MARCH' ), 
							JText::_( 'APRIL' ), JText::_( 'MAY' ), JText::_( 'JUNE' ),
                            JText::_( 'JULY' ), JText::_( 'AUGUST' ), JText::_( 'SEPTEMBER' ), 
							JText::_( 'OCTOBER' ), JText::_( 'NOVEMBER' ), JText::_( 'DECEMBER' ) );
							
		$cal->startDay = $params->get('cal_start_day'); //set the startday (this is the day that appears in the first column). Sunday = 0
							//it is loaded from the language ini because it may vary from one country to another, in Spain
							//for example, the startday is Monday (1)
		$rows= $this->setTheQuery($params,$year,$month,$day,$ajax,0);
		$ComBlogID = $params->get('associatedItemid');
		if ($ComBlogID!='') {
			$ComBlogItemID = '&Itemid='.$ComBlogID;
		} else { $ComBlogItemID=''; }
		//set the link for the month, this will be the link for the calendar header (ex. December 2007)
		if(is_array($rows) and count($rows)>0) {
			$cal->monthLink=JRoute::_('index.php?option=com_blog_calendar' . '&year=' . $year .
										'&month=' . $month . '&modid=' . $modid).$ComBlogItemID;
		} else {
			$cal->monthLink="";
		} 
		$cal->modid= $modid;
		$cal->itemid = $ComBlogID;

		if(!isset($counter)) {
			$counter = array();
		}

		foreach ( $rows as $row ) {
		 
			$created= new JDate($row->created);
			$createdYear=$created->format('Y');
			$createdMonth=$created->format('m');
			$createdDay=$created->format('d'); //have to use %d because %e doesn't works on windows
			$createdDate=$createdYear . $createdMonth . $createdDay; //this makes an unique variable for every day
			if(!isset($counter[$createdDay]['total'])) {
				$counter[$createdDay]['total'] = 0;
		 	}

			$counter[$createdDay]['total']++;
		 	//linklist is the array that stores the link strings for each day
		 	$cal->linklist[$createdDate]=	JRoute::_('index.php?option=com_blog_calendar&'.
													'&year=' . $createdYear . '&month=' . $createdMonth . '&day=' . 
													$createdDay . '&modid=' . $modid).$ComBlogItemID;
		 	$cal->linklist[$createdDate].="\" title=\""; //the calendar class sets the links this way: <a href=" . THE LINK STRING . ">
											 //so, the easiest way to add a title to that link is by setting THE LINK STRING = the link" title="the title
											 //the result link would be <a href="the link" title="the title">
		 	$cal->linklist[$createdDate].=$counter[$createdDay]['total'] . ' ';
		 	$cal->linklist[$createdDate].= ($counter[$createdDay]['total'] > 1)? $articles : $article;
		 	$cal->linklist[$createdDate].= ' ' . $article2;
		 	//the above 3 lines output something like: 3 articles on this day. Or: 1 article on this day
		
		}
		return $cal->getMonthView($month,$year,$day,$params->get('dateformat_my','%B %Y'));
	}
	
	static function getDate_byId($id){
		
		$app = JFactory::getApplication();
		$input = $app->input;
		$offset= $app->getCfg('offset');
		
		$query=	' SELECT created' .
			' FROM #__content'.
			' WHERE id=\'' . $id . '\'';
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$row= $db->loadObjectList();
		
		jimport('joomla.utilities.date');
		if(isset($row[0])) $created=new JDate($row[0]->created);
		else $created=new JDate();
		$created->setTimeZone(new DateTimeZone($offset));
		
		$createdYear=$created->format('Y',true);
		$createdMonth=$created->format('m',true);
		$createdDay=$created->format('d',true);
		
		$createdDate=Array($createdYear,$createdMonth,$createdDay);
		return $createdDate;
	}
	
	function showDropDown($params,$year,$month,$day,$ajax=0){
	
		$results= $this->setTheQuery($params,$year,$month,$day,$ajax,1);
	    	
		if(count($results)>0){
			if(!isset($articleCounter)){
				$articleCounter = array();
			}
		
			foreach($results as $key => $result){
				$created=new JDate($results[$key]->created);
				$createdYear= $created->format('Y');
				$createdMonth= $created->format('m');

				if(!isset($articleCounter[$createdYear]['total'])) {
					$articleCounter[$createdYear]['total'] = 0;
				}
				if(!isset($articleCounter[$createdYear][$createdMonth]['total'])) {
					$articleCounter[$createdYear][$createdMonth]['total'] = 0;
				}
				$results[$key]->link=JRoute::_(ContentHelperRoute::getArticleRoute($results[$key]->slug, $results[$key]->catslug));
		
				$results[$key]->year = $createdYear; $results[$key]->month = $createdMonth;
		
				$createdYear==$year? $articleCounter[$createdYear]['now']= true : '';
				$createdMonth==$month? $articleCounter[$createdYear][$createdMonth]['now']= true : '';
		
				$articleCounter[$createdYear][$createdMonth]['total']++;
				$articleCounter[$createdYear]['total']++;
			}
		}
	
		return array($results,$articleCounter);
	}
	
	function setTheQuery($params,$year,$month,$day='',$ajax=0,$type){
	
		$app = JFactory::getApplication();
		$input = $app->input;
		$offset= $app->getCfg('offset');
		
		$db = JFactory::getDbo();
		$user		= JFactory::getUser();
		$userId		= (int) $user->get('id');
		
		$catids		= $params->get('category_ids');		
		$juser = JFactory::getUser();
		$groups = implode(',', $juser->getAuthorisedViewLevels());
        $access = !JComponentHelper::getParams('com_content')->get('show_noauth');
		
		jimport('joomla.utilities.date');

		$nullDate	= $db->getNullDate();
			
		$date[0]= sprintf("%d-%d-%d 0:0:0",$year,$month,1);
		if( $month == 12 ) {
			$year2 = $year+1;
			$month2 = 1;
		} else {
			$year2 = $year;
			$month2 = $month+1;
		}
		$date[1]= sprintf("%d-%d-%d 0:0:0",$year2,$month2,1);		
		$date[0]= new JDate($date[0], $offset);
		$date[1]= new JDate($date[1], $offset);
		$date[0]= $date[0]->toSql();
		$date[1]= $date[1]->toSql();
		$dateNow = new JDate();
		$now = $dateNow->toSql();
		$catCondition = '';
		//if there are specific categories selected, the variable $catCondition will be added to the query, to get only the articles of this categories
		if (count($catids)>0 && $catids[0]!='') {
			$catCondition = ' AND (cc.id=' . implode( ' OR cc.id=', $catids ) . ')';
		} 

		if($type == 0){ //query for the calendar		
			$where = 'a.state = 1'
					. ' AND  a.created >= '. $db->Quote($date[0]) .' AND a.created < '. $db->Quote($date[1])
					. ' AND ( a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' )'
					. ' AND ( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )'
					;
// for BsAlpinist 2011-11-15
			$tmzone = new DateTimeZone($offset);
			$dtm = new DateTime("now", $tmzone);
			$tmoffset = $tmzone->getOffset($dtm);
//			$query ='SELECT a.id,a.catid,a.created,a.publish_up,a.publish_down,a.state,a.access'.
			$query ='SELECT a.id,a.catid, '.
					' a.created + INTERVAL'.sprintf(" %d ",$tmoffset).'SECOND as created, '.
					' a.publish_up + INTERVAL'.sprintf(" %d ",$tmoffset).'SECOND as publish_up, '.
					' a.publish_down + INTERVAL'.sprintf(" %d ",$tmoffset).'SECOND as publish_down, '.
					' a.state,a.access '.
					' FROM #__content AS a'.
					' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
					' WHERE '. $where .
					' AND a.language IN (\''.JFactory::getLanguage()->getTag().'\',\'*\')'.
					($access ? ' AND a.access IN ('.$groups.')' : ''). //select only the content that the current user is allowed to see
					' AND cc.published = 1' .
					($catCondition != '' ? $catCondition : '' ); //add the $catCondition if $catid exists				
		} elseif($type == 1) { //query for the list		
			$where		= 'a.state = 1'
				. ' AND ( a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' )'
				. ' AND ( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )'
				;
			
// for BsAlpinist 2011-11-15
			$tmzone = new DateTimeZone($offset);
			$dtm = new DateTime("now", $tmzone);
			$tmoffset = $tmzone->getOffset($dtm);

//			$query ='SELECT a.id,a.catid,a.created,a.publish_up,a.publish_down,a.state,a.access,a.title, '.
			$query ='SELECT a.id,a.catid, '.
				' a.created + INTERVAL'.sprintf(" %d ",$tmoffset).'SECOND as created, '.
				' a.publish_up + INTERVAL'.sprintf(" %d ",$tmoffset).'SECOND as publish_up, '.
				' a.publish_down + INTERVAL'.sprintf(" %d ",$tmoffset).'SECOND as publish_down, '.
				' a.state,a.access,a.title, '.
				' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'.
				' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug'.
				' FROM #__content AS a'.
				' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
				' WHERE '. $where .
				($access ? ' AND a.access IN ('.$groups.')' : '').
				' AND cc.published = 1' .
				($catCondition != '' ? $catCondition : '' ). //add the $catCondition if $catid exists
				' ORDER BY created DESC'; //order by date created descending				
		}
		//set the query and load the results
		$db->setQuery($query);
		$results = $db->loadObjectList();
	
		return $results;
	}
}


class MyCalendar extends Calendar
{

var $linklist; //this variable will be an array that contains all the links of the month

    function getDateLink($day, $month, $year) //this function is called from getMonthView(month,year) to get the link of the given day
    {										  //if this function returns nothing (""), then getMonthView wont put a link on that day
        
        $link = "";
		if(strlen($month)<2)
		$month = '0'.$month;
		if(strlen($day)<2)
		$day = '0'.$day;
	
		$date= $year . $month . $day;
		if(isset($this->linklist[$date])){
			$link=$this->linklist[$date];  //$this->linklist[$date] was set for every date in the foreach bucle at lines 50-83
		}

		return $link;
    }

  

  //Return the URL to link to in order to display a calendar for a given month/year.
  //this function is called to get the links of the two arrows in the header.
    function getCalendarLink($month, $year)
    {
    	$app = JFactory::getApplication();
		$input = $app->input;

        $option = $input->get('option');
        if($option != 'com_blog_calendar') {
			$calendarLink = JRoute::_('index.php?option=com_blog_calendar&modid='.$this->modid);
			$calendarLink.='month='.$month.'&amp;year='.$year.'&amp;Itemid='.$this->itemid;
        } else {
			$getquery = $input->get->getArray(); //get the GET query
			$calendarLink = JURI::current().'?';
			foreach($getquery as $key => $value){  //this bucle goes through every GET variable that was in the url
				if($key!='month' AND $key!='year' AND $key!='day' AND $value){ //the month,year, and day Variables must be diferent of the current ones, because this is a link for a diferent month
					$calendarLink.= $key . '=' . $value . '&amp;';
				}
			}
			$calendarLink.= '&Itemid='.$this->itemid;
        }
		
		return $calendarLink;
    }
}
?>