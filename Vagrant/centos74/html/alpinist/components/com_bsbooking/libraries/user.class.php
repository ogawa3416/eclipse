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
 * @version		$Id: user.class.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.0 $
 **/
// no direct access
defined('_JEXEC') or die;

class BsbookingUser
{
    function __construct( $id )
    {
        
    }
    
    function getInstance( $id )
    {
        static $_instance;
        
        if (empty($_instance))
        {
            if (empty($id)) 
            {
                $user = JFactory::getUser();
            }
            $_instance = new BsbookingUser($id)
        }
    }
}