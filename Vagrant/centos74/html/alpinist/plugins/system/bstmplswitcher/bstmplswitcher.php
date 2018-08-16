<?php
/**
 * BsAlpinist component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		BsAlpinist 
 * @subpackage	Components for Bscore
 * @copyright	Copyright (C) 2009-2010 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: bstmplswitcher.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.0 $
 **/

// no direct access
defined('_JEXEC') or die;

jimport( 'joomla.plugin.plugin');

class plgSystemBstmplswitcher extends JPlugin
{
	function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}

	/**
	* Converting the site URL to fit to the HTTP request
	*
	*/
	function onAfterInitialise()
	{
		$app	= JFactory::getApplication();
		$input = $app->input;
		$user	= JFactory::getUser();

		if ($app->isAdmin()) {
			return;
		}
		if ($user->get('guest') ) {
			return;
		}
		$template = $input->get('template');
		if( !$template ) {
			$menu = $app->getMenu();
			$item = $menu->getActive();
			if (!$item) {
				$item = $menu->getItem($input->getInt('Itemid'));
			}

			$id = 0;
			if (is_object($item)) { // valid item retrieved
				$id = $item->template_style_id;
			}
			$condition = '';

			$tid = $input->get('templateStyle', 0);
			if (is_numeric($tid) && (int) $tid > 0) {
				$id = (int) $tid;
			}
			if( !$id ) {
				$db = JFactory::getDbo();
				$query = 'SELECT a.divtmpl, d.params'
						.' FROM #__bs_division a, #__bs_users_detail b, #__extensions c, #__template_styles d'
						.' WHERE b.userid = '.$user->id
						.' AND b.divcode = a.divcode AND a.divtmpl = c.element'
						.' AND c.type = '.$db->quote('template')
						.' AND c.enabled = 1'
						.' AND c.element = d.template '
						;
				$db->setQuery($query);
				$tmpl = $db->loadObject();
				if( $tmpl ) {
					$app->setTemplate($tmpl->divtmpl,$tmpl->params);
					return;
				}
			}
		}
	}
}
?>