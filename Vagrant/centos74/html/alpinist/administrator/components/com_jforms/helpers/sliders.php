<?php
/**
 * @package     Joomla.Platform
 * @subpackage  HTML
 *
 */

defined('JPATH_PLATFORM') or die;

/**
 * JPane abstract class
 *
 * @package     Joomla.Platform
 * @subpackage  HTML
 * @since       11.1
 * @deprecated  12.1    Use JHtml::_ static helpers
 */


class JFormssliders
{
	public static function start($group = 'sliders', $params = array())
	{
		self::_loadBehavior($group, $params);

		return '<div id="' . $group . '" class="pane-sliders">';
	}
	public static function panel($text, $id)
	{
		return '<div class="panel"><h3 class="pane-toggler title" id="' . $id . '"><a href="javascript:void(0);"><span>' . $text
			. '</span></a></h3><div class="pane-slider content">';
	}
	
	public static function panelend()
	{
		return '</div></div>';
	}
	public static function end()
	{
		return '</div>';
	}
	protected static function _loadBehavior($group, $params = array())
	{
		static $loaded = array();
		if (!array_key_exists($group, $loaded))
		{
			// Get the JInput object
			$input = JFactory::getApplication()->input;

			$loaded[$group] = true;

			// Include mootools framework.
			JHtml::_('behavior.framework', true);

			$document = JFactory::getDocument();

			$display = (isset($params['startOffset']) && isset($params['startTransition']) && $params['startTransition'])
				? (int) $params['startOffset'] : null;
			$show = (isset($params['startOffset']) && !(isset($params['startTransition']) && $params['startTransition']))
				? (int) $params['startOffset'] : null;

			$opt['onActive'] = "function(toggler, i) {toggler.addClass('pane-toggler-down');" .
				"toggler.removeClass('pane-toggler');i.addClass('pane-down');i.removeClass('pane-hide');Cookie.write('jpanesliders_"
				. $group . "',$$('div#" . $group . ".pane-sliders > .panel > h3').indexOf(toggler));}";
			$opt['onBackground'] = "function(toggler, i) {toggler.addClass('pane-toggler');" .
				"toggler.removeClass('pane-toggler-down');i.addClass('pane-hide');i.removeClass('pane-down');if($$('div#"
				. $group . ".pane-sliders > .panel > h3').length==$$('div#" . $group
				. ".pane-sliders > .panel > h3.pane-toggler').length) Cookie.write('jpanesliders_" . $group . "',-1);}";
			$opt['duration'] = (isset($params['duration'])) ? (int) $params['duration'] : 300;
			$opt['display'] = (isset($params['useCookie']) && $params['useCookie']) ? $input->cookie->get('jpanesliders_' . $group, $display, 'integer')
				: $display;
			$opt['show'] = (isset($params['useCookie']) && $params['useCookie']) ? $input->cookie->get('jpanesliders_' . $group, $show, 'integer') : $show;
			$opt['opacity'] = (isset($params['opacityTransition']) && ($params['opacityTransition'])) ? 'true' : 'false';
			$opt['alwaysHide'] = (isset($params['allowAllClose']) && (!$params['allowAllClose'])) ? 'false' : 'true';

//			$options = JHTML::getJSObject($opt);
			$options = '{';
			foreach ($opt as $k => $v)
			{
				if ($v)
				{
					$options .= $k . ': ' . $v . ',';
				}
			}
			if (substr($options, -1) == ',')
			{
				$options = substr($options, 0, -1);
			}
			$options .= '}';

			$js = "window.addEvent('domready', function(){ new Fx.Accordion($$('div#" . $group
				. ".pane-sliders .panel h3.pane-toggler'), $$('div#" . $group . ".pane-sliders .panel div.pane-slider'), " . $options
				. "); });";

			$document->addScriptDeclaration($js);
		}
	}
}
