<?php
/**
* General Purpose Helper
* This class is the HTML Workhorse that provides several general purpose functions
*
* @version		$Id: general.php BsAlpinist2.5.0 $
* @package		Joomla
* @subpackage	JForms.Helpers
* @copyright	Copyright (C) 2008 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
* General purpose HTML Helper
* This class is the HTML Workhorse that provides several general purpose functions
 *
 * @package    Joomla
 * @subpackage JForms.Helpers
*/
class JFormsGeneral{

	static function IE(){
		$document = JFactory::getDocument();
		$document->addCustomTag(
			
			 '<!--[if IE]>'."\r\n"
			.'<link rel="stylesheet" href="'.JURI::root().'media/com_jforms/styles/design-backend-ie.css" type="text/css" />'."\r\n" 
			.'<![endif]-->'."\r\n"
		);
	}
	
	static function createFunction($args, $code){
	    static $n = 0;
	    $functionName = sprintf('ref_lambda_%d',++$n);
	    $declaration = sprintf('function %s(%s) {?> %s <?php } ',$functionName,$args,$code);
		eval($declaration);
   	    return $functionName;
	}

	static function script( $filename, $path ){
		
		if (!is_file($path.DIRECTORY_SEPARATOR.$filename))return;
		//A fix for PHP 5.3 func_get_args() warning
		$func = JFormsGeneral::createFunction('',file_get_contents($path.DIRECTORY_SEPARATOR.$filename));
	 
		ob_start();
		$params = func_get_args();
		call_user_func_array($func,$params);
		$contents = ob_get_contents();
		ob_end_clean();
		
		echo
		"<script type='text/javascript'>"
		."\n// <![CDATA["
		."\n$contents"
		."\n// ]]>"
		."\n</script>";
	}
	
	static function version(){
		
		$version = new JFormsVersion();
		
		echo '<div style="text-align:right;font-size:x-small;font-weight:bold;color:green">';
		echo $version->getLongVersion();
		echo '</div>';

	}
	
	function mootools(){
		//Nasty hack warning [Unload Mootools 1.11 and load 1.2]
		JHTML::_('script', 'media/com_jforms/scripts/dummy.js');
		$doc = JFactory::getDocument();
		$newScriptArray = array();
		$foundMootools = false;
		foreach( $doc->_scripts as $k => $v ){
			if( strpos($k, 'media/system/js/mootools') === false )$newScriptArray[$k] = $v;
			else $foundMootools=true;
		}
		if( $foundMootools ) $newScriptArray[JURI::root().'media/com_jforms/scripts/mootools.js'] = 'text/javascript' ;
//		$doc->_scripts = $newScriptArray;
		foreach($newScriptArray as $url => $v) {
			$doc->addScript($url,$v);
		}
		//Fix for Modal.js conflict
		echo '<script type="text/javascript" src="'.JURI::root().'media/com_jforms/scripts/modal.js'.'"></script>';
		//$newScriptArray[];
		//End of nasty hack
	}
	
	static function fixPane(){
	
		$output  = '<div style="display:none">';
		$output .= JHtml::_('sliders.start','xyz');
		$output .= JHtml::_('sliders.panel','xyz-p', 'xyz-p');
		$output .= JHtml::_('sliders.end');
		$output .= '</div>';
		echo $output;
	}
	

	

	static function indentedLine($text, $indention){
		$tabs = str_repeat( "\t" , $indention );
		return $tabs.$text."\n";
	}

	static function legend()
	{
		$app		= JFactory::getApplication();
		$template	= $app->getTemplate();
		$tmplpath = "templates/".$template."/";
		?>
		<table cellspacing="0" cellpadding="4" border="0" align="center">
		<tr align="center">
			<td>
			<img src="<?php echo $tmplpath ?>images/admin/publish_y.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'Pending' ); ?>" />
			</td>
			<td>
			<?php echo JText::_( 'PUBLISHED_BUT_IS' ); ?> <u><?php echo JText::_( 'Pending' ); ?></u> |
			</td>
			<td>
			<img src="<?php echo $tmplpath ?>images/admin/publish_g.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'Visible' ); ?>" />
			</td>
			<td>
			<?php echo JText::_( 'PUBLISHED_AND_IS' ); ?> <u><?php echo JText::_( 'Current' ); ?></u> |
			</td>
			<td>
			<img src="<?php echo $tmplpath ?>images/admin/publish_r.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'Finished' ); ?>" />
			</td>
			<td>
			<?php echo JText::_( 'PUBLISHED_BUT_HAS' ); ?> <u><?php echo JText::_( 'Expired' ); ?></u> |
			</td>
			<td>
			<img src="<?php echo $tmplpath ?>images/admin/publish_x.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'Finished' ); ?>" />
			</td>
			<td>
			<?php echo JText::_( 'NOT_PUBLISHED' ); ?>
			</td>


		</tr>
		<tr>
			<td colspan="10" align="center">
			<?php echo JText::_( 'CLICK_ON_ICON_TO_TOGGLE_STATE' ); ?>
			</td>
		</tr>
		</table>
		<?php
	}
}