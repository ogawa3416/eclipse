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
 * Renders a radio element
 *
 * @package     Joomla.Platform
 * @subpackage  Parameter
 * @since       11.1
 * @deprecated  12.1    Use JFormFieldRadio instead
 */
class JFormsElementRadio extends JFormsElement
{
	/**
	 * Element name
	 *
	 * @var    string
	 */
	protected $_name = 'Radio';

	/**
	 * Fetch a html for a radio button
	 *
	 * @param   string       $name          Element name
	 * @param   string       $value         Element value
	 * @param   JXMLElement  &$node         JXMLElement node object containing the settings for the element
	 * @param   string       $control_name  Control name
	 *
	 * @return  string
	 *
	 * @deprecated    12.1  Use JFormFieldRadio::getInput and JFormFieldRadio::getOptions indsead.
	 * @since   11.1
	 */
	public function fetchElement($name, $value, &$node, $control_name)
	{
		$options = array();
		foreach ($node->children() as $option)
		{
			$att = $option->attributes();
			$val = (string)$att['value'];
			$text = $option;
			$options[] = JHtml::_('select.option', $val, $text);
		}
		return JHtml::_('select.radiolist', $options, '' . $control_name . '[' . $name . ']', '', 'value', 'text', $value, $control_name . $name, true);
	}
}
