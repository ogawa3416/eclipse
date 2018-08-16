<?php
/**
 * BsBooking component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		GROON project
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2011 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: baseview.class.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.3 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
jimport('joomla.html.toolbar');
require_once(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'toolbar.php');

class JBaseView extends JViewLegacy
{
	function display($tpl=null)
	{
        $app = JFactory::getApplication();
		JHTML::_('behavior.framework');
		$doc = JFactory::getDocument();
		$user = JFactory::getUser();
		
        JHTML::script('components/com_bsbooking/assets/js/cookie.js');
        JHTML::stylesheet('components/com_bsbooking/assets/css/rounded.css');
        JHTML::stylesheet('components/com_bsbooking/assets/css/layout.css');

		$bar = JToolBar::getInstance('toolbar');
		$bar->addButtonPath(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'toolbar');

?>
	<div id="mainbk" class="hide-right">
      <div id="dashbdheader">
	  	<?php echo $bar->render(); ?>
      	<?php /*echo $app->JComponentTitle;*/ ?>
      </div>
      <div id="columns">
        <div class="cols-wrapper">
          <div class="float-wrapper">
            <div class="main-content">
              <?php parent::display($tpl);?>
            </div>
          </div>
          <div class="clear" id="em"></div>
        </div>
      </div>
    </div>
<?php			
	}
}