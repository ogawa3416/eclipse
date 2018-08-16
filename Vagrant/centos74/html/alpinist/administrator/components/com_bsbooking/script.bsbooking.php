<?php
/**
 * BsBooking component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		BsAlpinist
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2010 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: install.bswkflow.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.5.0 $
 **/
// no direct access
defined('_JEXEC') or die('Restricted access');

class com_bsbookingInstallerScript {

	function install($parent) {
		echo "<br/>  Thank you for the installation BsBooking<br/><br/>If you have any problems, contact <a href=\"mailto:infodesk@groon.co.jp\">infodesk@groon.co.jp</a><br/>Visit the <a href=\"http://www.groon.co.jp\">GROON solutions web site</a> for the lastest news and update." ;
	}
	function uninstall($parent) 
	{
		// $parent is the class calling this method
		echo "<br/>  BsBooking component successfully uninstalled.<br/><br/>If you have any problems, contact <a href=\"mailto:infodesk@groon.co.jp\">infodesk@groon.co.jp</a><br/>Visit the <a href=\"http://www.groon.co.jp\">GROON solutions web site</a> for the lastest news and update." ;
	}
	function update($parent) 
	{
		$db = JFactory::getDbo();
		$fields1 = $db->getTableColumns ( '#__bs_resources' );
		if (! isset ( $fields1['alert_msg'] ) ) {
			//resource table
			$query="ALTER TABLE #__bs_resources ADD alert_msg varchar(255) DEFAULT '' AFTER max_notice_time";
			$db->setQuery ( $query );
			$db->query ();
		}

		$fields2 = $db->getTableColumns ( '#__bs_reservations' );
		if (! isset ( $fields2['private_flg'] ) ) {
			//resource table
			$query="ALTER TABLE #__bs_reservations ADD private_flg tinyint(3) DEFAULT '0' AFTER members";
			$db->setQuery ( $query );
			$db->query ();
		}

		// $parent is the class calling this method
		echo "<br/>  BsBooking component successfully updated.<br/><br/>If you have any problems, contact <a href=\"mailto:infodesk@groon.co.jp\">infodesk@groon.co.jp</a><br/>Visit the <a href=\"http://www.groon.co.jp\">GROON solutions web site</a> for the lastest news and update." ;


	}
}
