<?php
/**
 * @package     Joomla.Platform
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Renders a filelist element
 *
 * @package     Joomla.Platform
 * @subpackage  Parameter
 * @since       11.1
 * @deprecated  12.1 Use JFormFieldFolderList instead.
 */
class JFormsElementFolderlist extends JFormsElement
{
	/**
	 * Element name
	 *
	 * @var    string
	 */
	protected $_name = 'Folderlist';

	/**
	 * Fetch a folderlist element
	 *
	 * @param   string       $name          Element name
	 * @param   string       $value         Element value
	 * @param   JXMLElement  &$node         JXMLElement node object containing the settings for the element
	 * @param   string       $control_name  Control name
	 *
	 * @return  string
	 *
	 * @deprecated    12.1  Use JFormFieldFolderlist::getOptions instead.
	 * @since   11.1
	 */
	public function fetchElement($name, $value, &$node, $control_name)
	{

		jimport('joomla.filesystem.folder');

		// Initialise variables.
		$att = $node->attributes();
		$path = JPATH_ROOT . '/' . (string)$att['directory'];
		$filter = (string)$att['filter'];
		$exclude = (string)$att['exclude'];
		$folders = JFolder::folders($path, $filter);

		$options = array();
		foreach ($folders as $folder)
		{
			if ($exclude)
			{
				if (preg_match(chr(1) . $exclude . chr(1), $folder))
				{
					continue;
				}
			}
			$options[] = JHtml::_('select.option', $folder, $folder);
		}

		if (!$att['hide_none'])
		{
			array_unshift($options, JHtml::_('select.option', '-1', JText::_('JOPTION_DO_NOT_USE')));
		}

		if (!$att['hide_default'])
		{
			array_unshift($options, JHtml::_('select.option', '', JText::_('JOPTION_USE_DEFAULT')));
		}

		return JHtml::_(
			'select.genericlist',
			$options,
			$control_name . '[' . $name . ']',
			array('id' => 'param' . $name, 'list.attr' => 'class="inputbox"', 'list.select' => $value)
		);
	}
}
