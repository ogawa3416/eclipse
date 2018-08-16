<?php
/**
* @version		1.05
* @package		BlogCalendar Reload
* @author		Juan Padial
* @authorwebsite	http://www.shikle.com
* @license		GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.controller' );	
class BlogCalendarController extends JControllerLegacy
{		
		static function editCSS(){
		jimport('joomla.filesystem.path');
		$app = JFactory::getApplication();
		$input = $app->input;
		
		$cname = JPATH_ROOT.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'mod_blog_calendar'.DIRECTORY_SEPARATOR.'tmpl'.DIRECTORY_SEPARATOR.'style.css';
                $c = fopen($cname,"r");
	        $content = fread($c, filesize($cname));
	        $content = htmlspecialchars($content);


		echo '
		<form action="index.php" method="post" name="adminForm" id="adminForm">
					<table class="adminform">
		<tr>
			<th>
				Editing '.JPATH_ROOT.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'mod_blog_calendar'.DIRECTORY_SEPARATOR.'tmpl'.DIRECTORY_SEPARATOR.'style.css'.'
			</th>
		</tr>
		<tr>
			<td>
				<textarea style="width:80%;height:500px" cols="110" rows="25" name="filecontent" class="inputbox">'.
				$content.
				'</textarea>
			</td>
		</tr>
		</table>
		<input type=\'hidden\' name=\'task\' value=\'save_css\'>
		<input type=\'hidden\' name=\'option\' value=\'com_blog_calendar\'>
		<input type=\'submit\' value=\'Save\'>
		';
		}
		
		static function saveCSS(){
		
		// Initialize some variables
		$filename		= JPATH_ROOT;
		$filename	   .= '\modules\mod_blog_calendar\tmpl\style.css';
		$filecontent	= $input->getString('filecontent', '');
		$file = $filename;
		jimport('joomla.filesystem.file');
		$return = JFile::write($file, $filecontent);
		if($return) echo 'Success saving the file';		
		}
		
		static function viewGuide(){
                echo '<div style="text-align:center;"><img src="components/com_blog_calendar/blogcalendar.gif"></img></div>';
		echo '<ul><li><h2><a href=\'?option=com_blog_calendar&task=edit_css\'>Edit CSS</a></h2><br>';
		echo '</li></ul>';
		
		}

}
?>