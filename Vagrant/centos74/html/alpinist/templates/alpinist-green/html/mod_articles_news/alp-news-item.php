<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_news
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div class="newsflash<?php echo $moduleclass_sfx; ?>">
<div class="altlayout<?php echo $moduleclass_sfx; ?>">

<?php foreach ($list as $item) : ?>

	<?php $item_heading = $params->get('item_heading', 'h4'); ?>

	<?php if ($params->get('item_title')) : ?>

		<<?php echo $item_heading; ?> class="newsflash-title<?php echo $params->get('moduleclass_sfx'); ?>">
		<?php if ($params->get('link_titles') && $item->link != '') : ?>
			<a href="<?php echo $item->link;?>">
				<?php echo $item->title;?></a>
		<?php else : ?>
			<?php echo $item->title; ?>
		<?php endif; ?>
		</<?php echo $item_heading; ?>>

	<?php endif; ?>

	<?php if (!$params->get('intro_only')) :
		echo $item->afterDisplayTitle;
	endif; ?>

	<?php echo $item->beforeDisplayContent; ?>

	<?php echo $item->introtext; ?>

	<?php if (isset($item->link) && $item->readmore != 0 && $params->get('readmore')) :
		echo '<a class="readmore" href="'.$item->link.'">'.$item->linkText.'</a>';
	endif; ?>

	<div class="create">
		<span class="icon-calendar"></span>
		<time datetime="<?php echo JHtml::_('date', $item->publish_up, 'c'); ?>" itemprop="dateCreated">
			<?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', JHtml::_('date', $item->publish_up, JText::_('DATE_FORMAT_LC2'))); ?>
		</time>
	</div>
<?php endforeach; ?>
</div>
</div>
