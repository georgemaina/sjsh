<?php
require_once('roots.php');
require($root_path.'include/inc_environment_global.php');
require("../../include/dhtmlxConnector/codebase/grid_connector.php");
require("../../include/dhtmlxConnector/codebase/db_mysqli.php");

$mysqli = $conn;
$gridConn = new GridConnector($mysqli,"MySQLi");
$accDB=$_SESSION['sess_accountingdb'];
$pharmLoc=$_SESSION['sess_pharmloc'];

$gridConn->enable_log("temp.log",true);
$gridConn->dynamic_loading(100);
$gridConn->render_sql("select * from care2x.care_tz_drugsandservices where purchasing_class = 'labtest'","item_id", "item_number,item_description,unit_price");

?>
