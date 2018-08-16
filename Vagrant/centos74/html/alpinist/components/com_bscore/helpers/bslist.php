<?php
/**
 * Bscore component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		BsAlpinist
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2011 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: bslist.php 180 2012-05-28 22:01:49Z BsAlpinist2.3.2 $
 **/

// no direct access
defined('_JEXEC') or die;

/**
 * Blogstone Component Controller for 'applylist'
 *
 * @package		BsAlpinist
 * @subpackage	Components
 * @since		joomla 2.5
 */
class JHtmlBslist
{
	protected static $items = array();
	/**
	 * Crates a select list of categories
	 * 
	 * @param   string   $name         Name of the field  
	 * @param   string   $extension    Extension for which the categories will be listed
	 * @param   string   $selected     Selected value
	 * @param   string   $javascript   Custom javascript
	 * @param   integer  $size         Size of the field
	 * @param   boolean  $sel_cat      If null do not include a Select Categories row
	 * 
	 * @since   11.1
	 * 
	 * @deprecated    12.1  Use JHtmlCategory instead
	 * @see           JHtmlCategory
	 */
	public static function category($name, $extension, $selected = NULL, $javascript = NULL, $order = null, $size = 1, $sel_cat = 1)
	{
		$categories = self::options($extension,null,$selected);
		if ($sel_cat) {
			array_unshift($categories, JHtml::_('select.option',  '0', JText::_('JOPTION_SELECT_CATEGORY')));
		}
		for( $i=0;count($categories)>$i;$i++ ) {
			if( is_null($categories[$i]->value ) ) unset($categories[$i]);
		}
		$category = JHtml::_(
			'select.genericlist',
			$categories,
			$name,
			'class="inputbox" size="'. $size .'" '. $javascript,
			'value', 'text',
			$selected
		);

		return $category;
	}
	public static function options($extension, $config = array('filter.published' => array(0,1)),$selected)
	{
		$hash = md5($extension.'.'.serialize($config));

		if (!isset(self::$items[$hash])) {
			$config	= (array) $config;
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			$user	= JFactory::getUser();
			$groups	= implode(',', $user->getAuthorisedViewLevels());

			$query->select('a.id, a.title, a.level');
			$query->from('#__categories AS a');
			$query->where('a.parent_id > 0');
			$query->where('a.access IN ('.$groups.')');

			// Filter on extension.
			$query->where('extension = '.$db->quote($extension));

			// Filter on the published state
			if (isset($config['filter.published'])) {
				if (is_numeric($config['filter.published'])) {
					$query->where('a.published = '.(int) $config['filter.published']);
				} else if (is_array($config['filter.published'])) {
					JArrayHelper::toInteger($config['filter.published']);
					$query->where('a.published IN ('.implode(',', $config['filter.published']).')');
				}
			}

			$query->order('a.lft');

			$db->setQuery($query);
			$items = $db->loadObjectList();

			// check of selected
			$chk = false;
			foreach ($items as $cat) {
				if( $selected && $cat->id == $selected ) {
					$chk = true;
					break;
				}
			}
			if( $selected || $chk == false ) {
				$query2	= $db->getQuery(true);
				$query2->select('a.id, a.title, a.level');
				$query2->from('#__categories AS a');
				$query2->where('a.id = '.$db->quote($selected));
				$db->setQuery($query2);
				$selitem[] = $db->loadObject();
				$items = array_merge($selitem,$items);
			}
			
			// Assemble the list options.
			self::$items[$hash] = array();
			foreach ($items as &$item) {
				if(!isset($item->title))continue;
				$repeat = ( $item->level - 1 >= 0 ) ? $item->level - 1 : 0;
				$title = str_repeat('- ', $repeat).$item->title;
				self::$items[$hash][] = JHtml::_('select.option', $item->id, $title);
			}
		}

		return self::$items[$hash];
	}
}
