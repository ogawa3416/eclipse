<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_articles_latest
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<ul class="latestnews<?php echo $moduleclass_sfx; ?>">
<?php foreach ($list as $item) :  ?>
	<li>
		<a href="<?php echo $item->link; ?>">
			<?php echo $item->title; ?>
			<?php 
				$mainframe = JFactory::getApplication();
				$templ	= $mainframe->getTemplate(true);
				$path = 'templates/'.$templ->template.'/images/newentry.png';
				$mkspan = $templ->params->get('newmark');
				$strtime = strtotime($item->created);
				$strtime = (int)$strtime+(int)($mkspan*3600*24);
				$enddate = date("Y-m-d H:i:s",$strtime);
				$now = JFactory::getDate();
				if( $now < $enddate ) {
					echo '<img src="'.$path.'" alt="new">';
				}
			?>
			</a>
	</li>
<?php endforeach; ?>
</ul>
