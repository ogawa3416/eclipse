<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_loginuser
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div class="mod_loginuser<?php echo $moduleclass_sfx; ?>">
	<div class="loginuserdivision">
	<?php if ($params->get('disp_divname') == 1) : 
		echo htmlspecialchars($logindivname->divname_s, ENT_COMPAT, 'UTF-8');
	else :
		echo JText::_('MOD_LOGINUSERNAME_LABEL');
	endif; ?>
	<span class=""><?php echo JText::_('MOD_LOGINUSERNAME_SEPA');?></span>
	</div>
	<div class="loginusername">
	<?php if ($params->get('disp_name') == 0) :
		echo JText::sprintf('MOD_LOGINUSER_NAME', htmlspecialchars($loginname));
	else :
		echo JText::sprintf('MOD_LOGINUSER_NAME', htmlspecialchars($loginusername));
	endif; ?>
	</div>
</div>
