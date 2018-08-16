<?php
/**
* @package		Blog Calendar Reload
* @author		Juan Padial
* @authorweb	        http://www.bloogie.es
* @license		GNU/GPL
*
* modified from the icon.php file of the Blog Calendar 1.2.2.1 component by Justo Gonzalez de Rivera
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
require_once (JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_content'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'route.php');
class JHTMLIcon
{
	function email($article, $params, $access, $attribs = array())
	{
		if(!$params->get('show_email_icon')){ return; }
		
		$user = JFactory::getUser();

		//$link	= JURI::base()."index.php?view=article&id=".$article->slug;
		$uri     = JURI::getInstance();
		$base  = $uri->toString( array('scheme', 'host', 'port'));
		$link    = $base.JRoute::_( "index.php?view=article&id=".$article->slug, false );
		$url	= 'index.php?option=com_mailto&tmpl=component&link='.base64_encode( $link );

		$status = 'width=400,height=300,menubar=yes,resizable=yes';

		if ($params->get('show_icons')) 	{
			$text = JHTML::_('image', 'media/system/images/emailButton.png', JText::_('JGLOBAL_EMAIL'));
		} else {
			$text = '&nbsp;'.JText::_('JGLOBAL_EMAIL');
		}
		
		$attribs['title']	= JText::_( 'JGLOBAL_EMAIL' );
		$attribs['onclick'] = "window.open(this.href,'win2','".$status."'); return false;";

		$output = JHTML::_('link', JRoute::_($url,false), $text, $attribs);
		return $output;
	}

	function edit($article, $params, $access, $attribs = array())
	{
		$user = JFactory::getUser();

		

		if ($article->state < 0) {
			return;
		}

		if (!$access->canEdit && !($access->canEditOwn && $article->created_by == $user->get('id'))) {
			return;
		}

		JHTML::_('behavior.tooltip');
		
		$ret = base64_encode(JFactory::getURI()->toString());
		$url = 'index.php?view=article&a_id='.$article->slug.'&task=article.edit'.'&option=com_content'.'&return='.$ret;
		$icon = $article->state ? 'edit.png' : 'edit_unpublished.png';
		$text = JHTML::_('image','media/system/images/'.$icon, JText::_('JGLOBAL_EDIT'));

		if ($article->state == 0) {
			$overlib = JText::_('JUNPUBLISHED');
		} else {
			$overlib = JText::_('JPUBLISHED');
		}
		$date = JHTML::_('date', $article->created);
		$author = $article->created_by_alias ? $article->created_by_alias : $article->author;

		$overlib .= '<br />';
		$overlib .= $date;
		$overlib .= '<br />';
		$overlib .= $author;

		$button = JHTML::_('link', JRoute::_($url,false), $text);

		$output = '<span class="hasTooltip" title="'.JText::_( 'JGLOBAL_EDIT' ).' :: '.$overlib.'">'.$button.'</span>';
		return $output;
	}


	function print_popup($article, $params, $access, $attribs = array())
	{
		if(!$params->get('show_print_icon')){ return; }
		
		$user = JFactory::getUser();

		$url  = JURI::base().'index.php?view=article';
		$url .=  @$article->catslug ? '&catid='.$article->catslug : '';
		$url .= '&id='.$article->slug.'&tmpl=component&print=1&page='.@ $request->limitstart.'&option=com_content';

		$status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';

		// checks template image directory for image, if non found default are loaded
		if ( $params->get( 'show_icons' ) ) {
			$text = JHTML::_('image', 'media/system/images/printButton.png', JText::_( 'JGLOBAL_PRINT' ) );
		} else {
			$text = JText::_( 'JGLOBAL_ICON_SEP' ) .'&nbsp;'. JText::_( 'JGLOBAL_PRINT' ) .'&nbsp;'. JText::_( 'JGLOBAL_ICON_SEP' );
		}

		$attribs['title']	= JText::_( 'JGLOBAL_PRINT' );
		$attribs['onclick'] = "window.open(this.href,'win2','".$status."'); return false;";

		return JHTML::_('link', $url, $text, $attribs);
	}

	function print_screen($article, $params, $access, $attribs = array())
	{
		$user = JFactory::getUser();

		// checks template image directory for image, if non found default are loaded
		
			$text = JHTML::_('image', 'images/M_images/printButton.png', JText::_( 'JGLOBAL_PRINT' ) );
		
		
		return '<a href="#" onclick="window.print();return false;">'.$text.'</a>';
	}
}
?>