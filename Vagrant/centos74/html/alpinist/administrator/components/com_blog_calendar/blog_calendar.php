<?php
/**
* @version		1.5.8
* @package		BlogCalendar Reload
* @author		Juan Padial
* @authorwebsite	http://www.shikle.com
* @license		GNU/GPL
*/


		require_once (JPATH_COMPONENT.DIRECTORY_SEPARATOR.'controller.php');
		
		JToolBarHelper::title( JText::_( 'Blog Calendar' ) );
		
		$app = JFactory::getApplication();
		$input = $app->input;
		
$task = $input->getCmd('task');


switch ($task)
{
	case 'edit_css' :
		BlogCalendarController::editCSS();
		break;

	case 'save_css'  :
		BlogCalendarController::saveCSS();
		break;
		
	case 'preview' :
		BlogCalendarController::previewCalendar();
		break;

	default :
		BlogCalendarController::viewGuide();
		break;
}


?>