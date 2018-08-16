<?php 
/**
* @package		Blog Calendar Reload
* @author		Juan Padial
* @authorweb	        http://www.bloogie.es
* @license		GNU/GPL
*
* modified from the default.php file of the Blog Calendar 1.2.2.1 component by Justo Gonzalez de Rivera
*/

// no direct access
defined('_JEXEC') or die('Restricted access'); 
JHtml::_('formbehavior.chosen', 'select');

$canEdit = false;
if ($this->user->authorise('core.edit','com_content') || $this->user->authorise('core.edit.own','com_content')) {
	$canEdit = true;
}
?>
<!--component start-->
<?php
$app = JFactory::getApplication();
$input = $app->input;
$aca = new BlogCalendarViewBlogCalendar;
   if(is_array($this->contents) && count($this->contents)<1 && $input->getInt('ajaxCalMod')!=1) {
     $app->redirect($aca->getLastmontwithcontents($this->params),JText::_('COM_BLOG_CALENDAR_NO_ARTICLE_FOR_DATE_VIEWING_LAST_MONTH'),'message');
   } else {
?>
<div class="blog">
	<h1>
		<?php echo ucfirst($this->date) ?>
	</h1>
	<?php if ($this->params->get('page_subheading')) : ?>
	<h2>
		<?php echo $this->escape($this->params->get('page_subheading')); ?>
	</h2>
	<?php endif; ?>

<?php 
		$dispatcherClassName = (class_exists('JDispatcher')? 'JDispatcher' : 'JEventDispatcher');
		// Process the content preparation plugins
		JPluginHelper::importPlugin('content');
		$dispatcher = new $dispatcherClassName();
		$dispatcher = $dispatcher->getInstance();
		$app = JFactory::getApplication();
		
		// Get the page/component configuration
		$mainparams = $app->getParams();
?>

<?php foreach($this->contents as $article):?>
 <?php if($article->created_new_day) : ?>
   <h3>
     <?php echo ucfirst($article->created_new_day) ?>
   </h3>
 <?php endif;?>
   <div class="items-row cols-1 row-0">
     <?php foreach($dispatcher->trigger('onContentAfterTitle', array ('com_content.article',&$article, &$this->params, 0)) as $plugin){
		if($plugin){echo $plugin;}} 
     ?>
     <h2 class="arttitle"><a href="<?php echo $article->link; ?>"><?php echo $article->title; ?></a></h2>

     <?php if($canEdit || $this->params->get('show_print_icon') || $this->params->get('show_email_icon') || ($this->user->authorise('core.edit','com_content') || $this->user->authorize('core.edit.own','com_content'))) : ?>
     <div class="btn-group pull-right">
     	<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"> <span class="icon-cog"></span> <span class="caret"></span> </a>
		<ul class="dropdown-menu actions">
     	
		<?php if($this->params->get('show_print_icon')) : ?>
			<li class="print-icon">
			<?php echo JHTML::_('icon.print_popup', $article, $this->params); ?>
			</li>
		<?php endif; ?>
		<?php if($this->params->get('show_email_icon')) : ?>
			<li class="email-icon">
			<?php echo JHTML::_('icon.email', $article, $this->params); ?>
			</li>
		<?php endif;?>
		<?php if ($canEdit && $this->access ) : ?>
			<li class="edit-icon">
			<?php echo JHTML::_('icon.edit', $article, $this->params ); ?>
			</li>
		<?php endif; ?>
		</ul>
	</div>
      <?php endif; ?> 
     
      <dl class="article-info">
	<dt class="article-info-term"><?php echo JText::_('COM_BLOG_CALENDAR_ARTICLE_INFO'); ?></dt>
	<?php if($this->params->get('show_category')) :?>
		<dd class="category-name"><?php echo JText::_('COM_BLOG_CALENDAR_CATEGORY'); ?>
			<?php echo ($this->params->get('link_category')? '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($article->catid),false).'">': '' ) ?>
			<?php echo ($this->params->get('show_category')? $article->catTitle : ''); ?>
			<?php echo ($this->params->get('link_category')? '</a>' : '');?>
		</dd>
	<?php endif;?>
	<?php if($this->params->get('show_create_date')) :?>
		<dd class="created">
			<?php echo JText::_('COM_BLOG_CALENDAR_CREATED_ON'); ?><?php echo JHtml::_('date', $article->created, JText::_('DATE_FORMAT_LC2')); ?>
		</dd>
	<?php endif;?>
	<?php if ($this->params->get('show_modify_date')) : ?>
		<dd class="modified">
			<?php echo JText::_('COM_BLOG_CALENDAR_LAST_UPDATED'); ?><?php echo JHtml::_('date', $article->modified, JText::_('DATE_FORMAT_LC2')); ?>
		</dd>
	<?php endif; ?>
	<?php if ($this->params->get('show_publish_date')) : ?>
		<dd class="publish">
			<?php echo JText::_('COM_BLOG_CALENDAR_PUBLISHED_DATE'); ?><?php echo JHtml::_('date', $article->publish_up, JText::_('DATE_FORMAT_LC2')); ?>
		</dd>
	<?php endif; ?>
	<?php if($this->params->get('show_author')) :?>
		<dd class="createdby">
			<?php echo $aca->aname($article,$this->params); ?>
		</dd>
	<?php endif;?>
	<?php if($this->params->get('show_hits')) :?>
		<dd class="hits">
			<?php echo JText::_('COM_BLOG_CALENDAR_ARTICLE_HITS'); ?>
			<?php echo $article->hits; ?>
		</dd>
	<?php endif;?>
     </dl>

     <?php foreach($dispatcher->trigger('onContentBeforeDisplay', array ('com_content.article',& $article, & $this->params, 0)) as $plugin){
	if($plugin){echo $plugin;}}
     ?>
     
      <?php if ($this->params->get('show_intro') && !$this->params->get('show_fulltext')):?>
            <?php echo JHtml::_('content.prepare',$article->introtext); ?>
            <a href="<?php echo $article->link ?>" rel="nofollow" class="readon"><?php echo JText::_('COM_BLOG_CALENDAR_READ_MORE')?></a>
      <?php elseif (!$this->params->get('show_intro') && !$this->params->get('show_fulltext')):?>
             <a href="<?php echo $article->link ?>" rel="nofollow" class="readon"><?php echo JText::_('COM_BLOG_CALENDAR_READ_MORE')?></a>
      <?php elseif($this->params->get('show_fulltext') && !$this->params->get('show_intro')):?> 
              <?php if($article->fulltext!='') echo JHtml::_('content.prepare',$article->fulltext);else echo JHtml::_('content.prepare',$article->text); ?>
      <?php elseif($this->params->get('show_fulltext') && $this->params->get('show_intro')) :?> 
              <?php echo JHtml::_('content.prepare',$article->text); ?>
      <?php endif;?>
      
      <div class="item-separator"></div>
      
      <?php foreach($dispatcher->trigger('onContentAfterDisplay', array ('com_content.article',& $article, & $this->params, 0)) as $plugin){
	if($plugin){echo $plugin;}}
      ?>
 </div>
<?php endforeach; ?>
</div>
<?php if ($this->pagination->total > $this->pagination->limit) : ?>
<div class="pagination">
<?php
 echo $this->pagination->getPagesLinks();
?>
</div>
<?php endif; ?>
<?php } ?>
<!--component end-->