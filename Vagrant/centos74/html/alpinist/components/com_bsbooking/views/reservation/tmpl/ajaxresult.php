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
 * @version		$Id: ajaxresult.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.0 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;
if (count($this->errors)) :
?>
<table align="center" width="100%" cellspacing="0" cellpadding="1">
    <tr class="messageNegative">
        <td width="25"><img src="<?php echo JURI::base()?>/components/com_bsbooking/assets/images/x.gif" alt="x"/></td>
        <td><?php echo JText::_('COM_BSBOOKING_RESERVATION_IS_UNAVAILABLE')?></td>
    </tr>
    <tr>
        <td class="messageNegativeBG" colspan="2">
            <table width="100%" cellspacing="0" cellpadding="0">
            <?php for ($i=0; $i < count($this->errors); $i++) : ?>
                <tr>
                    <td class="warningCell<?php echo ($i%2)?>"><?php echo $this->errors[$i]?></td>
                </tr>
            <?php endfor ?>
            </table>
        </td>
    </tr>
</table>
<?php else: ?>
<table align="center" width="100%" cellspacing="0" cellpadding="1">
    <tr class="messagePositive">
        <td width="25"><img src="<?php echo JURI::base()?>/components/com_bsbooking/assets/images/checkbox.gif" alt="ok"/></td>
        <td><?php echo JText::_('COM_BSBOOKING_RESERVATION_IS_AVAILABLE')?></td>
    </tr>
</table>
<?php endif; ?>