<?php
require_once('roots.php');
require($root_path.'include/inc_environment_global.php');
global $db;

        $pid=$_REQUEST['pid'];
	$sql="SELECT * FROM care_ke_billing where pid='10000005'";
        $result=$db->Execute($sql);
	$data = array();

	while ($row = $result->FetchRow($result))
	{
		$data [] = $row;
	}

	echo json_encode($data);
?>