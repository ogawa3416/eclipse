<?php
/**
* Frontend form View for JForms Component
*
* @version		$Id: view.html.php 362 2010-02-20 06:50:23Z BsAlpinist 2.5.0 $
* @package		Joomla
* @subpackage	JForms.Views
* @copyright	Copyright (C) 2008 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

/**
 * Form View
 *
 * @package    Joomla
 * @subpackage JForms.Views
 */
class FrontendViewForm extends JViewLegacy
{

	function thank( $form ){

		JHTML::_('stylesheet', 'media/com_jforms/styles/themes/'.$form->theme.'.css');
		
		$this->assignRef('form', $form );
		
		parent::display();
		
	}

	/**
	 * Form view display method
	 *
	 * Displays requested form
	 *
	 * @return void
	 **/
	function form($form)
	{
		global $Itemid;
		if( !$Itemid ) {
			$Itemid = '';
			$dbb = JFactory::getDBO();
			$link = "index.php?option=com_jforms&view=form&id=".$form->id;
			$query = "SELECT id FROM #__menu WHERE link='".$link."'"." and published>=0";
			$dbb->setQuery( $query );
			$rows = $dbb->loadObjectList();
			if( isset($rows[0]) ) $Itemid =  $rows[0]->id;
		}
		
		JHTML::_('stylesheet', 'media/com_jforms/styles/themes/'.$form->theme.'.css');

		$user   = JFactory::getUser();
		$this->assignRef('form'     , $form );
		$this->assignRef('user'     , $user );
		$this->assignRef('Itemid'  , $Itemid );	

		// Check WorkFlow Setting
		$app = JFactory::getApplication();
		$message = '';
		$menus	= $app->getMenu();
		$menu	= $menus->getActive();
		if (is_object( $menu )) {
			$menu_params = new JRegistry;
			$menu_params->loadString($menu->params);
			$wkflow = $menu_params->get("workflow");
			if( $wkflow ) {
				$dbb = JFactory::getDBO();
				$query = "SELECT count(fmid) FROM #__bs_wfassign a, #__bs_users_detail b "
						." WHERE a.divcode = b.divcode"
						." AND b.userid = ".$dbb->Quote($user->id)
						." AND a.fmid ="
						." (SELECT CONCAT('jforms_',aa.parameter_value)  fmid"
						."	FROM `#__jforms_parameters` aa"
						."	WHERE aa.parameter_name='tableName' AND aa.plugin_name='Database' AND aa.fid =".$form->id
						." )"
						;	
				$dbb->setQuery($query);
				$exist = $dbb->loadResult();
				if( !$exist ) $message = JText::_('JFORMS_WKFLOWROOT_UNSET');
			}
		}
		$this->assignRef('message'  , $message );	
				
		parent::display();
	}
}