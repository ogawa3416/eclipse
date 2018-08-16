<?php
/**
 * BsBooking component for Joomla.
 *
 * Distributed under the terms of the GNU General Public License v2
 * http://www.gnu.org/copyleft/gpl.html
 * This software may be used without warrany provided and
 * copyright statements are left intact.
 *
 * @package		GROON project
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2011 GROON solutions. All rights reserved.
 * @license		GNU/GPL
 * @version		$Id: message.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.0 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;
JHTML::stylesheet('components/com_bsbooking/assets/css/layout.css');
?>
<div class="msgform">
<form name="adminForm" id="resform" action="index.php?option=com_bsbooking" method="post" >
<fieldset style="padding-left: 5px ; border: 2px blue;  ">
    <div style="float: right">
        <button class="button" type="button" id="btn"  onclick="Joomla.submitform('reservation.back',this.form);"/><i class="icon-undo"></i><?php echo JText::_( "COM_BSBOOKING_TOLIST" );?></button>
    </div>
    <div id="jm-container">
        <legend>
            <?php echo ($this->success)? JText::_('YOUR_OPERATION_HAVE_DONE_SUCCESSFULLY'):JText::_("YOUR_OPERATION_HAS_FAILED")?>
        </legend>
        <?php echo ucfirst($this->message)?>
    </div>
</fieldset>
<input type="hidden" name="task" value="" />
<input type="hidden" name="ret" value="<?php echo $this->ret;?>" />
</form>
</div>
