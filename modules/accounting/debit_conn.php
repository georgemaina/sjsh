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
$gridConn->render_sql("SELECT partcode,purchasing_class,item_description,item_id,unit_price FROM care_tz_drugsandservices 
                where item_status NOT IN (2,3,4) and purchasing_class not in('General Supplies','Kitchen')
","item_id", "partcode,purchasing_class,item_description,unit_price");

?>
