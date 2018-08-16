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
 * Renders a SQL element
 *
 * @package     Joomla.Platform
 * @subpackage  Parameter
 * @since       11.1
 * @deprecated  12.1    Use JFormFieldSQL Instead.
 */
class JFormsElementSQL extends JFormsElement
{
	/**
	 * Element name
	 *
	 * @var    string
	 */
	protected $_name = 'SQL';

	/**
	 * Fetch the sql element
	 *
	 * @param   string       $name          Element name
	 * @param   string       $value         Element value
	 * @param   JXMLElement  &$node         JXMLElement node object containing the settings for the element
	 * @param   string       $control_name  Control name
	 *
	 * @return  string
	 *
	 * @deprecated  12.1
	 * @since   11.1
	 */
	public function fetchElement($name, $value, &$node, $control_name)
	{

		$db = JFactory::getDbo();
		$att = $node->attributes();
		$db->setQuery((string)$att['query']);
		$key = ($att['key_field'] ? (string)$att['key_field'] : 'value');
		$val = ($att['value_field'] ? (string)$att['value_field'] : $name);

		$options = $db->loadObjectlist();

		// Check for an error.
		if ($db->getErrorNum())
		{
			JError::raiseWarning(500, $db->getErrorMsg());
			return false;
		}

		if (!$options)
		{
			$options = array();
		}

		return JHtml::_(
			'select.genericlist',
			$options,
			$control_name . '[' . $name . ']',
			array(
				'id' => $control_name . $name,
				'list.attr' => 'class="inputbox"',
				'list.select' => $value,
				'option.key' => $key,
				'option.text' => $val
			)
		);
	}
}
