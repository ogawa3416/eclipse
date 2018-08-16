<?php
// no direct access
defined('_JEXEC') or die;

function com_install()
{
	$rmfile1 = JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_bsbooking'.DIRECTORY_SEPARATOR.'admin.bsbooking.php';
	if( file_exists($rmfile1) ) {
		unlink($rmfile1);
	}
	echo "<br/>  Thank you for the installation BsBooking<br/><br/>If you have any problems, contact <a href=\"mailto:infodesk@groon.co.jp\">infodesk@groon.co.jp</a><br/>Visit the <a href=\"http://www.groon.co.jp\">GROON solutions web site</a> for the lastest news and update." ;
    
    return true;
}
