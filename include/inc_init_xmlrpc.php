<?php
	require_once('care_api_classes/class_globalconfig.php');
	require_once('inc_environment_global.php');

	$glob_obj=new GlobalConfig($GLOBAL_CONFIG);
	$glob_obj->getConfig('is%');

	if($GLOBAL_CONFIG['is_transmit_to_weberp_enable'] == "")
	{
		$sql = 'INSERT INTO care_config_global (`type`, `value`, `notes`, `status`, `history`, `modify_id`, `modify_time`, `create_id`, `create_time`) VALUES (\'is_transmit_to_weberp_enable\', \'0\', NULL, \'\', \'\', \'\', NOW(), \'\', \'0000-00-00 00:00:00\');';
		$db->Execute($sql);
	}

	$webERPServerURL = "http://http://localhost/maseno/accounts/api/api_xml-rpc.php";
 	$weberpuser = "admin";
	$weberppassword = "weberp";
	$weberpDebugLevel = 2;
	$is_transmit_to_weberp_enable=$GLOBAL_CONFIG['is_transmit_to_weberp_enable'];

?>
