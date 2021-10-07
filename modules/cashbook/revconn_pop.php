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
$gridConn->render_sql("SELECT i.catID,i.item_Cat,d.partcode,d.item_description, d.unit_price,d.`gl_sales_acct`,c.`accountname` FROM care_tz_drugsandservices d
LEFT JOIN care_tz_itemscat i ON d.category=i.catID 
LEFT JOIN $accDB.`chartmaster` c ON d.`gl_sales_acct`=c.`accountcode`
WHERE  d.item_status NOT IN (2,3,4)","item_number", "catID,item_Cat,partcode,item_description,unit_price,gl_sales_acct,accountname");

?>
