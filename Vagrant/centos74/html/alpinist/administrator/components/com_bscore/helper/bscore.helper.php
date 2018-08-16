<?php
/**
 * BlogStone component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		GROON UGMS
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2010 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: bscore.helper.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.4.0 $
 **/

// no direct access
defined('_JEXEC') or die;

global $comcfg;
/** Common Include ***************************/
// Blogstone configuration object
require_once( JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_bscore'.DIRECTORY_SEPARATOR.'configuration.php' );

/*
 *  Blogstone debug log function  
 */ 
function bs_debuglog($message_log)
{
	//get text string based on languages
	$msg = JText::_($message_log); 
   	// write variable int log
   //	$log->addEntry(array("level" => 0,"status"=> 1, "comment" => "[BS]".$ret));
	JLog::add("[BS] ".$msg,JLog::DEBUG);
	//print string
//	echo $ret."<br>";
}


 
