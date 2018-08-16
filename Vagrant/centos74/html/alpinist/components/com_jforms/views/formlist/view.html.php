<?php
/**
 * JForms component for BsAlpinist.
 *
 * @package		BsAlpinist
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2011 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: formlist.php 2013-12-24 00:00:00 BsAlpinist ver.2.5.0 $
 **/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class FrontendViewFormlist extends JViewLegacy
{
	function display($tpl = NULL)
	{

		JHTML::stylesheet('components/com_jforms/assets/css/jforms.css' );
		$app = JFactory::getApplication();
		// Get the page/component configuration
		$document = JFactory::getDocument();

		$uri = JFactory::getURI();

		$data = $this->get("Items");
		$labels = $this->get("Labels");
		
		$params = clone($app->getParams('com_jforms'));

		// Set page title
		$menus	= $app->getMenu();
		$menu	= $menus->getActive();
		$title = $menu->title;
		if (is_object( $menu )) {
			$menu_params = new JRegistry;
			$menu_params->loadString($menu->params);
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title',$title  );
			}
		} else {
			$params->set('page_title',$title );
		}
		$document->setTitle( $params->get( 'page_title' ) );

		$params->def('show_headings', 			1);
		$params->def('show_pagination',			2);
		$params->def('show_pagination_results',	1);
		$params->def('show_pagination_limit',	1);

		// set of joomla default validation
		//In case we are in a blog view set the limit
		$total		= $this->get( 'Total' );
		$pagination =  $this->get("Pagination");

		$this->assign('action', 	str_replace('&', '&amp;', $uri->toString()));
		$this->assignRef('params', $params);
		$this->assignRef('data', $data);
		$this->assignRef('total', $total);
		$this->assignRef('labels', $labels);
		$this->assignRef('pagination',	$pagination);

		parent::display($tpl);    
	}
}