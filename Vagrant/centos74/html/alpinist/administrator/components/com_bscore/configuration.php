<?php
/**
 * @package		Alpinist
 * @subpackage	Components
 * @copyright	Copyright (C) 2009-2011 GROON solutions. All rights reserved.
 * @license		GROON solutions
 * @version		$Id: configuration.php 180 2012-05-28 22:01:49Z BsAlpinist3.0.0 $
 **/
$cfg = new JConfig();
$comcfg['version']='3.0.0';
$comcfg['siteservice']='1';
$comcfg['sitedivition']='0';
$comcfg['advanced_public']='1';
$comcfg['contract_stat']='2';
$comcfg['usecaptcha']='0';
$comcfg['get_mail_username']=$cfg->mailfrom;
$comcfg['get_mail_user']=$cfg->fromname;
$comcfg['applyclass']='1';
$comcfg['diskvol_pub']='1073741824';
$comcfg['diskvol_def']='1GB';
$comcfg['uunit_cost']='0';
$comcfg['pj_maxusers']='99999';
$comcfg['jcepjbase_dir']='images/alpinist/projects/PJ';
$comcfg['bsscheduler_sendmail']='1';
$comcfg['bsbtobscoptions']='eventdv';
$comcfg['bsbtobscoptdata']='eventdv_0';
$comcfg['bswkflowplugins']='jforms,bsexpense';
$comcfg['bswkflow_sendmail']='1';
$comcfg['taxrate']='5,8';
$comcfg['taxdate']='1997-4-1,2014-4-1';
$comcfg['accgroup']='com_bsscheduler';
foreach( $comcfg as $_k => $_v ) { $comcfg[$_k] = stripslashes( $_v ); }
?>
