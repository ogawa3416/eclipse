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
 * @version		$Id: router.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.0 $
 *
 * Inspired by and partially based on:
 *   The "jongman" compornent for Joomla 1.5.x 
 *   Authors: Prasit Gebsaap
 *   Copyright (c) 2009 Prasit Gebsaap.
 **/
// no direct access
defined('_JEXEC') or die;

function BsbookingBuildRoute( &$query )
{
    $segments = array();
    if (isset($query['view']))
    {
        /** first call alias is ok */
        unset($query['view']);
        unset($query['id']);
        /*
        if (isset($query['date']))
        {
            $segments[] = 'date-'.$query['date'];
            unset($query['date']);
        } */   
    }
    
    if (isset($query['task']))
    {
        $segments[] = $query['task'];
        switch($query['task'])
        {
            case 'dashboard.display':
                if (isset($query['id']))
                {
                    $segments[]= $query['id'];
                    unset($query['id']);
                }      
            break;
            case 'reservations.getlist':
                if (isset($query['id']))
                {
                    $segments[]= $query['id'];
                    unset($query['id']);
                }
            break; 
            case 'schedule.display':    
                if (isset($query['type'])) {
                    /** uses - to separate between type and its value
                     *  but when retriving we must use : to split it off
                     */
                    $segments[] = 'type-'.$query['type'];
                    unset($query['type']);
                }
                if (isset($query['id'])) {
                    $segments[] = $query['id'];
                    unset($query['id']);     
                }
            break;
            case 'user.getlist':
                $segments[] = $query['tmpl'];
                unset($query['tmpl']);
            break;
            case 'members.edit':
                $segments[] = $query['tmpl'];
                unset($query['tmpl']);
            break;
            case 'reservation.view':
            case 'reservation.add':
            case 'reservation.edit':
                $segments[] = $query['type'];
                unset($query['type']);
            break;
        }
        unset($query['task']);
    } 
    return $segments;   
}

function BsbookingParseRoute( &$segments )
{
    $vars = array();
    if (count($segments)==1) {
        list($name, $value) = explode(':', $segments[0]);
        $vars['date']= $value;
        return $vars;
    }
    switch ($segments[0])
    {
        case 'dashboard.display':
        case 'reservations.getlist':
            /** task=reservations.getlist&id=1 
                task=dashboard.displat&id=1
            */
            $vars['task'] = $segments[0];
            if (isset($segments[1])) $vars['id'] = (int)$segments[1];
            break;
        case 'schedule.display':
            $vars['task'] = $segments[0];
            
            list($name, $value) = explode(':', $segments[1]);
            $vars['type'] = $value;
            if (count($segments)==3) $vars['id'] = $segments[2];     
            
            break;
        case 'user.getlist':
            $vars['task'] = $segments[0];
            $vars['tmpl'] = $segments[1];
            break;
        case 'members.edit':
            $vars['task'] = $segments[0];
            $vars['tmpl'] = $segments[1];
            break;
        case 'reservation.view':
        case 'reservation.add':
        case 'reservation.edit':
            $vars['task'] = $segments[0];
            $vars['type'] = $segments[1];       
            break;      
    } 
    
    return $vars;        
}