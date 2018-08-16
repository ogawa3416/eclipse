<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Document
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * JDocument system message renderer
 *
 * @package     Joomla.Platform
 * @subpackage  Document
 * @since       11.1
 */
	/**
	 * Render the system message if no message template file found
	 *
	 * @param   array  $msgList  An array contains system message
	 *
	 * @return  string  System message markup
	 *
	 * @since   12.2
	 */
	function renderMessage($msgList)
	{
		$alert = array('error' => 'alert-error', 'warning' => 'alert-warning', 'notice' => 'alert-info', 'message' => 'alert-success');
		// Build the return string
		$buffer = '';
		$buffer .= "\n<div id=\"system-message-container\">";

		// If messages exist render them
		if (is_array($msgList))
		{
			$buffer .= "\n<div id=\"system-message\">";
			foreach ($msgList as $type => $msgs)
			{
				$buffer .= "\n<div class=\"alert " . $alert[$type] . "\">";

				// This requires JS so we should add it trough JS. Progressive enhancement and stuff.
				$buffer .= "<a class=\"close\" data-dismiss=\"alert\">×</a>";

				if (count($msgs))
				{
					$buffer .= "\n<h4 class=\"alert-heading\">" . JText::_($type) . "</h4>";
					$buffer .= "\n<div>";
// Bug Fix. 20130101 by Groon solutions >>>>>
					$msgbuf = "";
					foreach ($msgs as $msg)
					{
// Bug Fix. 20130101 by Groon solutions >>>>>
						if( $msgbuf === $msg ) continue;
// Bug Fix. 20130101 by Groon solutions <<<<<
						$buffer .= "\n\t\t<p>" . $msg . "</p>";
// Bug Fix. 20130101 by Groon solutions >>>>>
						$msgbuf = $msg;
// Bug Fix. 20130101 by Groon solutions <<<<<
					}
					$buffer .= "\n</div>";
				}
				$buffer .= "\n</div>";
			}
			$buffer .= "\n</div>";
		}

		$buffer .= "\n</div>";

		return $buffer;
	}

