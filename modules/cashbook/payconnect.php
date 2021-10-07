<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('roots.php');
require($root_path.'include/inc_environment_global.php');
 global $db;
require("../../include/dhtmlxConnector/codebase/grid_connector.php");
$gridConn=new GridConnector($db);
$gridConn->enable_log("temp.log",true);
$gridConn->dynamic_loading(100);
$gridConn->render_table("care_ke_paymentmode","pay_id","cash_point,Payment_mode,Description,GL_Account");

?>

