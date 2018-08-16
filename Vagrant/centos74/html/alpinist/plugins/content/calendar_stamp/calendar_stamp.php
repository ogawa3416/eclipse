<?php
/**
 * Calendar Stamp
 * @package calendar_stamp
 * @author Ahmad Alfy
 * @modified Groon solutions
 * @version 1.5
 * @copyright Non-Commercial
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @based on Calendar Icon by Sebastian Pieczynski
 * @version		$Id: default.php BsAlpinist 2.5.0 $
 **/
defined('_JEXEC') or die;
jimport('joomla.plugin.plugin');
require_once( JPATH_ROOT.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_bscore'.DIRECTORY_SEPARATOR.'helper'.DIRECTORY_SEPARATOR.'bscore.helper.php' );

class plgContentCalendar_Stamp extends JPlugin
{
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
        $config_style = $this->params->get('style', 'classic_dark_blue.css');
/****
		jimport('joomla.document.document');
		$lang	= JFactory::getLanguage();
		$attributes = array (
			'charset'	=> 'utf-8',
			'lineend'	=> 'unix',
			'tab'		=> '  ',
			'language'	=> $lang->getTag(),
			'direction'	=> $lang->isRTL() ? 'rtl' : 'ltr'
		);
		$document = JDocument::getInstance('html', $attributes);
****/
		$document = JFactory::getDocument ();
		
        if ($config_style == 'user_style') {
            $config_user_style = $this->params->get('style_user');
            $document->addCustomTag('<style type="text/css"><!--' . $config_user_style . '--></style>');
        } else {
            $document->addCustomTag('<link rel="stylesheet" href="'.JURI::root().'plugins/content/calendar_stamp/calendar_stamp/' . $config_style . '" type="text/css" media="screen"/>');
        }
    }
    function _code_td_calendar(&$style, &$article_date)
    {
        $config_style = $this->params->get('style');
		/* Creating date format according to the template */
        if ($config_style != 'user_style') {
            $fulldate    = JHTML::_('date', $article_date,"d.m.y");
            $month_dec   = date('m', strtotime($article_date));
            $fulldate    = str_replace('  ', ' ', $fulldate);
            $explodedate = explode('.', $fulldate);
            $day         = null;
            $month       = null;
            $year        = null;
            $stamp       = null;
            /* day [01] */
            $day .= $explodedate[0];
            /* month */
            if (($style == 'classic') || ($style == 'thin') || ($style == 'simple')) {
                $month .= JText::_('SHORT_'.$explodedate[1]);
            } else {
                $month .= JText::_('FULL_'.$explodedate[1]);
            }
            /* year [2009] */
            if ($style == 'classic') {
                $year .= '20'.$explodedate[2];
            }
            /* building output */
            $stamp .= '<div class="day">' . $day . '</div>';
            $stamp .= '<div class="month">' . $month . '</div>';
            if ($style == 'classic') {
                $stamp .= '<div class="year">' . $year . '</div>';
            }
            return $stamp;
        } else if ($config_style == 'user_style') {
            /* If style_user -> load date parameters */
            $config_show_day   = $this->params->get('show_day', '1');
            $config_show_month = $this->params->get('show_month', '1');
            $config_show_year  = $this->params->get('show_year', '1');
			$config_month_lenght = $this->params->get('month_lenght', '0');
            /* getting month format
            2 formats used Numbers (01,02,03...) and Letters (January,February...)
			*/
            $month_format      = $this->params->get('month_format', 'LETTERS');
            /* getting year format
            2 formats used YEAR_FORMAT_4 (2009) and YEAR_FORMAT_2 (09)
            */
            $year_format       = $this->params->get('year_format', 'YEAR_FORMAT_4');
            //$fulldate          = JHTML::_('date', $article_date, JText::_('DATE_FORMAT_LC4'));
            $month_dec         = date('m', strtotime($article_date));
            $fulldate    = JHTML::_('date', $article_date,"d.m.y");
            $explodedate = explode('.', $fulldate);
            $day   = null;
            $year  = null;
            $month = null;
            $stamp = null;
			$day .= $explodedate[0];
			if ($month_format == "NUMBERS"){
				$month .= $explodedate[1];
			} else if ($month_format == "LETTERS"){
				if ($config_month_lenght == 0){
					$month .= JText::_('SHORT_'.$explodedate[1]);
				}else if ($config_month_lenght == 1){
					$month .= JText::_('FULL_'.$explodedate[1]);
				}
			}
			if ($year_format == "YEAR_FORMAT_4") {
				$year .= '20' . $explodedate[2];
			} else {
				$year .= $explodedate[2];
            }
            if ($config_show_day == 1) {
                $stamp .= '<div class="day">' . $day . '</div>';
            }
            if ($config_show_month == 1) {
                $stamp .= '<div class="month">' . $month . '</div>';
            }
            if ($config_show_year == 1) {
                $stamp .= '<div class="year">' . $year . '</div>';
            }
            return $stamp;
        }
    }
    function plgContentCalendarCheckSecCatArt(&$row,&$params)
    {
		if( ($params->get('show_author')) or ($params->get('show_category')) or ($params->get('show_create_date')) 
			or ($params->get('show_modify_date')) or ($params->get('show_publish_date')) or ($params->get('show_parent_category')) 
			or ($params->get('show_hits')) or ($params->get('show_vote')) or ($params->get('show_title')) ) {
			;
		} else {
			return true;
		}
        $value_sec      = 0;
        $value_cat      = 0;
        $value_art      = 1;
		$sections = $this->params->get('sections', '');
        if($sections != '') {
            // Check accepted section
			$_db = JFactory::getDbo();	
			$query = ' SELECT path'
					.' FROM #__categories WHERE `id` = '.$row->catid
					;
			$_db->setQuery( $query );
			$parent_route = $_db->loadResult();
			$sectionalias = explode('/', $parent_route);
			
            $aAcceptedSectionsArray = array();
            $aAcceptedSectionsArray = explode(',', $sections);
			$value_sec = 0;
			if( isset($sectionalias) ) {
				foreach ($sectionalias as $section){
					if( in_array($section, $aAcceptedSectionsArray)) {
   	     				$value_sec = '1';
						break;
					}
				}
			}
            unset($aAcceptedSectionsArray);
        }
        // Check accepted category
		$categories = $this->params->get('categories', '');
        if ($categories != '') {
            $aAcceptedCategoryArray = array();
            $aAcceptedCategoryArray = explode(',', $categories);
            if (in_array($row->catid, $aAcceptedCategoryArray)) {
                $value_cat = '1';
            }
            unset($aAcceptedCategoryArray);
        }

        // Check ignored articles
		$articles = $this->params->get('articles', '');
        if ($articles != '') {
            $aIgnoredArticleArray = array();
            $aIgnoredArticleArray = explode(',', $articles);
            if (in_array($row->id, $aIgnoredArticleArray)) {
                $value_art = '0';
            }
            unset($aIgnoredArticleArray);
        }
        if ((($value_sec == 1) || ($value_cat == 1)) && ($value_art == 1)) {
            return false; // Output Ok
        } else {
            return true;
        }
    }
    function _draw(&$row, &$params)
    {
        $config_layout = $this->params->get('layout', 'table');
        if ($this->plgContentCalendarCheckSecCatArt($row,$params) == false) {
            $style_name   = $this->params->get('style');
            $explodestyle = explode('_', $style_name);
            $style        = $explodestyle[0];
            /* If the article has not been modified yet; use the creation date
            Thanks to Agostino Zanutto for the fix
            */
            if ($this->params->get('showing', '0') == '1' && $row->modified != '0000-00-00 00:00:00') {
                $date_used = $row->modified;
			} else if($this->params->get('showing', '0') == '2' && $row->publish_up != '0000-00-00 00:00:00') {
				$date_used = $row->publish_up;
            } else {
                $date_used = $row->created;
            }

            $calendar = $this->_code_td_calendar($style, $date_used);
            /* Show/Hide original date */
            if (!$this->params->get('original_date', '1'))
                $params->set('show_create_date', '0');
            $send = null;
            if ($config_layout == 'table') {
                $send .= '<table width="100%" border="0" class="caldraw"><tr valign="middle">';
                $send .= '<td class="stamp"><div class="datetime">';
                $send .= $calendar . '</div></td><td>';

                /* building end of table */
            } else if ($config_layout == 'css') {
                $send .= '<div class="datetime">';
                $send .= $calendar . '</div>';
            }
			return $send;
        }
		return '';
    }
    function onContentAfterTitle($context, &$row, &$params, $page=0)
    {
		$html = '';
		$app = JFactory::getApplication();
		$input = $app->input;

        $config_displaying = $this->params->get('displaying');
        if ($config_displaying == 0) {
            //featured only
            if (isset($row->author) && ($input->get('view') == 'featured'))
                $html = $this->_draw($row, $params);
        } else if ($config_displaying == 1) {
            //featured + articles
            if (isset($row->author) && (($input->get('view') == 'featured') || ($input->get('view') == 'article')))
                $html = $this->_draw($row, $params);
        } else if ($config_displaying == 2) {
            //everywhere
            if (isset($row->author))
                $html = $this->_draw($row, $params);
        } else if ($config_displaying == 3) {
            //articles only
            if (isset($row->author) && ($input->get('view') == 'article'))
                $html = $this->_draw($row, $params);
        } else if ($config_displaying == 4) {
            //articles only + blogs
            if (isset($row->author) && (($input->get('view') == 'article') || ($input->get('view') == 'featured') || ($input->get('layout') == 'blog')|| ($input->get('option') == 'com_blog_calendar'))) {
                $html = $this->_draw($row, $params);
            }
        }
		return $html;
    }
    function _afterdraw(&$row,&$params)
    {
		$send = '';
		if ($this->plgContentCalendarCheckSecCatArt($row,$params) == false) {
			$config_layout = $this->params->get('layout', 'table');
			if ($config_layout == 'table') {
				$send = '</td></tr></table><div class="clear"></div>';
			} else if ($config_layout == 'css') {
				$send = '<div class="clear"></div>' ;
			}
		}
		return $send;
	}
    function onContentBeforeDisplay($context, &$row, &$params, $page=0)
    {
    	$app = JFactory::getApplication();
		$input = $app->input;
		
		$html = '';
        $config_displaying = $this->params->get('displaying');
        if ($config_displaying == 0) {
            //featured only
            if (isset($row->author) && ($input->get('view') == 'featured')) 
                $html = $this->_afterdraw($row, $params);
        } else if ($config_displaying == 1) {
            //featured + articles
            if (isset($row->author) && (($input->get('view') == 'featured') || ($input->get('view') == 'article')))
                $html = $this->_afterdraw($row, $params);
        } else if ($config_displaying == 2) {
            //everywhere
            if (isset($row->author))
                $html = $this->_afterdraw($row, $params);
        } else if ($config_displaying == 3) {
            //articles only
            if (isset($row->author) && ($input->get('view') == 'article'))
                $html = $this->_afterdraw($row, $params);
        } else if ($config_displaying == 4) {
            //articles only + blogs
            if (isset($row->author) && (($input->get('view') == 'article') || ($input->get('view') == 'featured') || ($input->get('layout') == 'blog')|| ($input->get('option') == 'com_blog_calendar'))) {
                $html = $this->_afterdraw($row, $params);
            }
        }
		return $html;
    }
}
?>
