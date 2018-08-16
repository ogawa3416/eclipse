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
 * @version		$Id: summary.class.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.0 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;
/**
* Formats and truncates reservation summaries for display on the schedule
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 04-06-06
* @package phpScheduleIt
*
* Copyright (C) 2003 - 2007 phpScheduleIt
* License: GPL, see LICENSE
*/

class BsbookingSummary
{
	var $visible = false;
	var $user_name = '';
    var $title = '';
	var $text = '';
	var $private_flg = '';
	var $reserved_for = '';

	var $EMPTY_SUMMARY = '&nbsp;';
	
//	function BsbookingSummary( $text ) {
//	function __construct( $text ) {
	function __construct( $text ,$private_flg ,$reserved_for) {
		$my =  JFactory::getUser();
		if($private_flg == 1 && $reserved_for != $my->id){
			$this->text = JText::_('PRIVATE_SUMMARY') ;
		}
		else{
			$this->text = $text;
		}
	}
	
	function toScheduleCell($available_chars = -1) {
		$summary = $this->EMPTY_SUMMARY;
		
		if ($available_chars == -1 || $available_chars > $this->getSize()) {
			$available_chars = $this->getSize();
		}
		
		if ($this->isVisible()) {
			if (!empty($this->user_name) && strlen($this->user_name) >= $available_chars) {
				$summary = substr($this->user_name, 0, $available_chars);
			}
			else if (!empty($this->user_name) && $this->getSize() >= $available_chars) {
				$summary = "{$this->user_name}\n<i>" . htmlspecialchars(substr($this->text, 0, $available_chars - strlen($this->user_name))) . '</i>';
			}
			else {
				$summary = htmlspecialchars(substr($this->text, 0, $available_chars));
			}
			
			if ($this->getSize() > $available_chars && $available_chars > 0) {
				$summary .= '...';
			}
		}
		
		return $summary;
	}
	/**
     * Prepare text for Mootools tooltip
     */
	function toScheduleHover() {
		if (!$this->isVisible()) {
			return $this->EMPTY_SUMMARY;
		}
		if (!empty($this->title)) {
			return "{$this->title}::"  . htmlspecialchars($this->text) ;
		}
		else {
			return "(na)::".htmlspecialchars($this->text);
		}		
	}
	
	function getSize() {
		return strlen($this->user_name) + strlen($this->text);
	}
	
	function isVisible() {
		return $this->visible && ( strlen($this->text) + strlen($this->user_name) ) > 0;
	}
}
?>