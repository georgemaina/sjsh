<?php

require_once('roots.php');
require($root_path.'include/inc_environment_global.php');
global $db;
$pid=$_GET[patientId];
$store=$_REQUEST[storeID];

require("../../include/dhtmlxConnector/codebase/grid_connector.php");
require("../../include/dhtmlxConnector/codebase/db_mysqli.php");

$mysqli = $conn;
$gridConn = new GridConnector($mysqli,"MySQLi");

$accDB=$_SESSION['sess_accountingdb'];
$gridConn->enable_log("temp.log",true);
$gridConn->dynamic_loading(200);

$accDB=$_SESSION['sess_accountingdb'];
$pharmLoc=$_SESSION['sess_pharmloc'];

$gridConn->render_sql("SELECT c.partcode,c.item_description,d.stockid,d.quantity,c.unit_price,d.reorderlevel FROM care_tz_drugsandservices c
INNER JOIN care_ke_locstock d ON d.stockid=c.partcode","partcode", "stockid,item_description,quantity,reorderlevel,unit_price");

?>
