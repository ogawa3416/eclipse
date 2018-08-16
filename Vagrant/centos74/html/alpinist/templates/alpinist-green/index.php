<?php
/**
 * @package		Joomla.Site
 * @subpackage	Templates.alpinist-blue
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// check modules
$showbottom		= ($this->countModules('bottom') or $this->countModules('bottom2') or $this->countModules('bottom3'));

JHtml::_('behavior.framework', true);

// get params
$logo			= $this->params->get('logo');
$navposition	= $this->params->get('navposition');
$app			= JFactory::getApplication();
$doc			= JFactory::getDocument();
$templateparams	= $app->getTemplate(true)->params;
$this->language = $doc->language;
$contentcenter	= '';
$agent 			= getenv("HTTP_USER_AGENT");
//JHtml::_('formbehavior.chosen', 'select');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
		<jdoc:include type="head" />
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/layout.css" type="text/css" media="screen,projection,print" />
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/bsportal3.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/color.css" type="text/css" />
<?php
	$files = JHtml::_('stylesheet', 'templates/'.$this->template.'/css/general.css', null, false, true);
	if ($files):
		if (!is_array($files)):
			$files = array($files);
		endif;
		foreach($files as $file):
?>
		<link rel="stylesheet" href="<?php echo $file;?>" type="text/css" />
<?php
	 	endforeach;
	endif;
?>
		<?php if ($this->direction == 'rtl') : ?>
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/template_rtl.css" type="text/css" />
		<?php endif; ?>
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/print.css" type="text/css" media="Print" />

		<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/javascript/hide.js"></script>
		<script type="text/javascript" src="<?php echo $this->baseurl;?>/templates/<?php echo $this->template; ?>/javascript/main.js"></script>
		<script src="<?php echo $this->baseurl;?>/components/com_bscore/assets/js/bscore.js" type="text/javascript"></script>
		<script type="text/javascript">
			var altopen='<?php echo JText::_('TPL_BSPORTAL3_ALTOPEN', true); ?>';
			var altclose='<?php echo JText::_('TPL_BSPORTAL3_ALTCLOSE', true); ?>';
			var bildauf='<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/images/plus.png';
			var bildzu='<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/images/minus.png';
			var rightopen='<img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/images/plus.png" alt="<?php echo JText::_(TPL_BSPORTAL3_TEXTRIGHTOPEN);?>" />';
			var rightclose='<img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/images/minus.png" alt="<?php echo JText::_(TPL_BSPORTAL3_TEXTRIGHTCLOSE);?>" />';
		</script>
		<?php require_once( 'components'.DIRECTORY_SEPARATOR.'com_bscore'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'calendar_setup.php' );?> 
	</head>

	<body>
	<div id="all">
		<div id="back">
			<div id="header">
				<jdoc:include type="modules" name="user10" />
				<div class="logoheader">
					<h1 id="logo">
					<a href="<?php echo JURI::base() ?>">
					<?php if ( file_exists(JPATH_ROOT.DIRECTORY_SEPARATOR.htmlspecialchars($logo)) ): ?>
						<img src="<?php echo $this->baseurl ?>/<?php echo htmlspecialchars($logo); ?>" alt="<?php echo htmlspecialchars($templateparams->get('sitetitle'));?>" />
					<?php else: ?>
						<?php echo htmlspecialchars($templateparams->get('sitetitle'));?>
					<?php endif; ?>
					</a>
					</h1>
				</div><!-- end logoheader -->
				<div class="topicheader">
					<jdoc:include type="modules" name="user4" />
				</div>
					
				<div class="searchheader">
					<h3 class="unseen"><?php echo JText::_('TPL_BSPORTAL3_SEARCH'); ?></h3>
					<jdoc:include type="modules" name="search" />
				</div>
				<div class="menuheader">
					<jdoc:include type="modules" name="top" />
				</div>
			
				<div class="clr"></div>
				
				<div id="breadcrumbs">
				<jdoc:include type="modules" name="breadcrumb" />
				</div>
				<div id="langswitcher">
				<jdoc:include type="modules" name="langswitcher" />
				</div>
				<div id="header-image"> </div>
			</div><!-- end header -->
			<div class="clr"></div>
			
			<div id="<?php echo $navposition ? 'contentarea2' : 'contentarea'; ?>">
<?php /* class= left or right */ ?>
			<?php if($this->countModules('left')) : ?>
				<div class="<?php echo $navposition; ?>">
					<jdoc:include type="modules" name="left" style="beezDivision" headerLevel="3" />
				</div>
			<?php else :
				$contentcenter = 'style="left:130px"'; ?>
			<?php endif; ?>
<?php /* main contentarea */ ?>
				<div id="main" <?php echo $contentcenter;?> >
			<?php if($this->countModules('user1 or user2 or user3')) : ?>
					<table class="user1user2" <?php echo $contentcenter;?> >
					<tr valign="top">
				<?php if($this->countModules('user1') && $this->countModules('user2') && $this->countModules('user3')) :
						$userwidth = 'style="width:254px;max-width:254px"';
					elseif (($this->countModules('user1') && $this->countModules('user2') && !$this->countModules('user3'))
						|| ($this->countModules('user1') && !$this->countModules('user2') && $this->countModules('user3'))
						|| (!$this->countModules('user1') && $this->countModules('user2') && $this->countModules('user3'))) :
						$userwidth = 'style="width:381px;max-width:381px"';
					elseif (($this->countModules('user1') && !$this->countModules('user2') && !$this->countModules('user3'))
						|| (!$this->countModules('user1') && $this->countModules('user2') && !$this->countModules('user3'))
						|| (!$this->countModules('user1') && !$this->countModules('user2') && $this->countModules('user3'))) :
						$userwidth = 'style="width:100%"';
				endif; ?>
			   	<?php if($this->countModules('user1')) : ?>
					<td class="user1" <?php echo $userwidth;?> >
						<div class="moduleblock">
						<jdoc:include type="modules" name="user1" style="beezDivision" headerLevel="3" />
						</div>
					</td>
				<?php endif; ?>
				<?php if($this->countModules('user2')) : ?>
					<td class="user2" <?php echo $userwidth;?> >
						<div class="moduleblock">
						<jdoc:include type="modules" name="user2" style="beezDivision" headerLevel="3" />
						</div>
					</td>
				<?php endif; ?>
				<?php if( $this->countModules('user3' )) : ?>
					<td class="user3" <?php echo $userwidth;?> >
						<div class="moduleblock">
						<jdoc:include type="modules" name="user3" style="beezDivision" headerLevel="3" />
						</div>
					</td>    
				<?php endif; ?>
					</tr>
					</table>
			<?php endif; ?>

					<div id="component">
						<jdoc:include type="message" />
						<jdoc:include type="component" />
					</div><!-- end component -->
			<?php if($this->countModules('user5 or user6 or user7 or user8')) : ?>
				<?php if($this->countModules('user6')) : ?>
					<div id="close">
						<a href="#" onclick="auf('user6')">
							<span id="bild">
							<img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/images/minus.png" alt="<?php echo JText::_('TPL_BSPORTAL3_TEXTRIGHTCLOSE');?>" />
							</span></a>
					</div>
				<?php endif; ?>
					
					<table class="user5user6">
					<tr valign="top">
				<?php if( $this->countModules('user5 or user7') && $this->countModules('user6 or user8') ) :
						$userwidth2 = 'style="width:50%"';
					else :
						$userwidth2 = 'style="width:100%"';
					endif; ?>
				<?php if($this->countModules('user5 or user7')) : ?>
						<td class="user5" <?php echo $userwidth2;?> >
						<div class="moduleblock">
							<jdoc:include type="modules" name="user5" style="beezTabs" headerLevel="3"  id="2" />
							<jdoc:include type="modules" name="user7" style="beezDivision" headerLevel="3" />
						</div>
						</td>
				<?php endif; ?>
				<?php if($this->countModules('user6 or user8')) : ?>
						<td class="user6" id="user6"  >
						<div class="moduleblock">
							<jdoc:include type="modules" name="user6" style="beezTabs" headerLevel="3"  id="3" />
							<jdoc:include type="modules" name="user8" style="beezDivision" headerLevel="3" />
						</td>
				<?php endif; ?>
					</tr>
					</table>
			<?php endif; ?>
				</div><!-- end main -->
				<div class="wrap"></div>
			</div><!-- end contentarea -->
		</div><!-- back -->
		<div style="clear:both;"></div>
		<div id="footer-outer">
			<?php if ($showbottom) : ?>
			<div id="footer-inner">
				<div id="bottom">
				<?php if ($this->countModules('bottom')): ?>
					<div class="box box1"> <jdoc:include type="modules" name="bottom" style="beezDivision" headerlevel="3" /></div>
				<?php endif; ?>
				<?php if ($this->countModules('bottom2')): ?>
					<div class="box box2"> <jdoc:include type="modules" name="bottom2" style="beezDivision" headerlevel="3" /></div>
				<?php endif ; ?>
				<?php if ($this->countModules('bottom3')): ?>
					<div class="box box3"> <jdoc:include type="modules" name="bottom3" style="beezDivision" headerlevel="3" /></div>
				<?php endif ; ?>
				</div>
			</div><!-- footer-inne -->
			<?php endif ; ?>

			<div id="footer-sub">
				<div id="footer">
					<jdoc:include type="modules" name="syndicate" />
				</div><!-- end footer -->
			</div><!-- end footer-sub -->
		</div><!-- end footer-outer -->
		<div id="alpinistver">Powered by&nbsp;
			<a target="_blank" href="http://www.groon.co.jp"><font color="#FFFFFF">BsAlpinist</font></a>
		</div>
	<?php if (BscoreHelper::in_mobile()) : ?>
		<div id="backmobile">
			<a href="index.php?jtpl=<?php echo BscoreHelper::getTmplidbyName('alpmobile');?>"><?php echo JText::_('TPL_BSPORTAL3L_CHANGE_MOBILE'); ?></a>
		</div>
	<?php endif ; ?>
	</div><!-- all -->
	<jdoc:include type="modules" name="debug" />
	</body>
</html>
