<?php
/**
* @package		Blog Calendar Reload
* @author		Juan Padial
* @authorweb	        http://www.bloogie.es
* @license		GNU/GPL
*
* modified from the view.html file of the Blog Calendar 1.2.2.1 component by Justo Gonzalez de Rivera
*/

jimport( 'joomla.application.component.view');
require_once (JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_content'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'route.php');
require_once (JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_blog_calendar'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'icon.php');
JLoader::register('JHtmlString', JPATH_LIBRARIES.'/joomla/html/html/string.php');

class BlogCalendarViewBlogCalendar extends JViewLegacy
{

	function display($tpl = null)
	{
		$app = JFactory::getApplication('site');
		$input = $app->input;
		
		$model = $this->getModel();
		
		$year= $input->get('year', false);
		$month= $input->get('month', false);  //get the date requested
		$day= $input->get('day', false);
		
		
		$user = JFactory::getUser();
		
		$params = JComponentHelper::getParams('com_content');
		
		$modid = $input->get('modid');
		
		$db = JFactory::getDBO();
		
		if($modid){ //if the component is called from the a Blog Calendar module, load the parameters of that module
		   $query = 'SELECT params'
	                   . ' FROM #__modules'
			   . ' WHERE id = ' . $modid;
	            
		
		  $db->setQuery( $query );
		
		  $param = $db->loadResult();
		  $oparams = new JRegistry($param);
		  $params->merge($oparams);

		} else { //the component is being called from a menu item, get the parameters of that menu item		  	
			$menuitemid = $input->getInt( 'Itemid' );
			if ($menuitemid)
			{
				$menus	= $app->getMenu();
				$menu = $menus->getParams( $menuitemid );
				
				$menu_params = new JRegistry;
				$menu_params->loadString($menu->params);
				$params->merge( $menu_params );
			}
		}

		if($params->get('date')) {//if there is a date set in the parameters, use that date for the list
		  list($year,$month,$day) = explode('-',$params->get('date'));
		}

		//call the function that returns the arrays of articles
		$this->assign('contents', $model->getContent($params,$year,$month,$day)); 
				
		$access   	        = new stdClass();
		$access->canEdit	= $user->authorise('core.edit', 'com_content');
		$access->canEditOwn	= $user->authorise('core.edit.own', 'com_content');
		$this->assignRef('params', $params);
		$this->assignRef('user', $user);
		$this->assignRef('access', $access);
		
		jimport('joomla.html.pagination');
		$this->pagination= new JPagination($this->contents['total'], $this->contents['limitstart'], $this->contents['limit']);
		
		unset($this->contents['total']); 		//unset the pagination entries in the array, 
		unset($this->contents['limitstart']); 	//  so they dont interfere with the 
		unset($this->contents['limit']);		//  foreach bucle used in default.php
		
		$this->date= $this->contents['date'];
		
		
		unset($this->contents['date']); //unset this variable so that the numbers of elements in the array $this->contents
										//is exactly the number of articles found. This is needed because tmpl/default.php
										//works with a foreach bucle to output the article data

		parent::display($tpl);
	} 
	
	function aname($article,$params){
	
	         $user= JFactory::getUser($article->created_by);
	         $s = '';
	         if ($params->get('show_author_username')==1){
	              $s=$user->name;
	              }
	
	         if ($params->get('show_author_username')==2) {
	             $user= JFactory::getUser($article->created_by); 
	             $s=$user->username;
	             }
	             
                if ($params->get('cbintegration')) {
                    $database = JFactory::getDBO();
	            $query = "SELECT id FROM #__menu WHERE link='index.php?option=com_comprofiler' AND published='1'";
                    $database->SetQuery($query);
                    $menid = $database->loadResult();
	            $cburi='index.php?option=com_comprofiler&task=userProfile&user=';
	            $cburi.=$user->id;
	            $cburi.='&Itemid=';
	            $cburi.=$menid;
	            $s=JHtml::_('link',$cburi,$s,null);
	            }
	            
	            return JText::_('COM_BLOG_CALENDAR_WRITTEN_BY').' '.$s;
	}
	
       function mh(){
                  $ur='http://www.bloogie.es';
                  $urm='Powered by Bloogie';
                  return $mh=JHTML::_('link',$ur,$urm,null);
       }
       function gentruncatedcontent($article,$params){
		  $str=$article->text;
		  if($params->get('clean_xhtml')){
		    $str=strip_tags($article->text);
		    $str = str_replace('<p>', ' ', $str);
		    $str = str_replace('</p>', ' ', $str);
		    $str = strip_tags($str, '<a><em><strong>');
		   }

		  $str = trim($str);
          return $str;
      } 
   //this functions gets the date (month) of last article published and return a link to com_blog_calendar to that month
   function getLastmontwithcontents($params)
	{
	 $app = JFactory::getApplication();
	$input = $app->input;
	 $offset= $app->getCfg('offset');	
	 $modid = $input->getgetInt('modid');
	 if($modid != "") {
	  $modid = "&modid=".$modid;
	  $catid= $params->get('category_ids');
	  //if no $modid params are empty try to get params from com_blog_calendar Itemid
	 } else {
	        $modid = "";
	        $menu = $app->getMenu()->getActive();
		$menuparams = new JRegistry($menu->params);
	        $catid = $menuparams->get('category_ids');
	 }
	 $catCondition = '';
         if(is_array($catid) && $catid[0] != '') {
	   JArrayHelper::toInteger( $catid );
	   $catCondition = 'AND (catid=' . implode( ' OR catid=', $catid ) . ')';
	 }
	 $db = JFactory::getDBO();
	 $query = "SELECT created FROM #__content WHERE state = 1 ".( $catCondition!='' ? $catCondition : '' )." AND language IN ('".JFactory::getLanguage()->getTag()."','*')"." ORDER BY created DESC LIMIT 1";
	 $db -> setQuery($query);
         $lastarticledate = $db->loadResult();
	 jimport('joomla.utilities.date');
	 $lastarticledate = new JDate($lastarticledate ,-$offset);
	 $lastmonth = $lastarticledate ->format('m');
	 $lastyear = $lastarticledate ->format('Y');

	 return JRoute::_('index.php?option=com_blog_calendar&year='.$lastyear.'&month='.$lastmonth.$modid.'&Itemid='.$input->getgetInt('Itemid'),false);
    }
}
?>