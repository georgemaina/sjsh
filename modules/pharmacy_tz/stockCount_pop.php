<?php
require_once('roots.php');
require($root_path.'include/inc_environment_global.php');

require("../../include/dhtmlxConnector/codebase/grid_connector.php");
$gridConn=new GridConnector($db);
$gridConn->enable_log("temp.log",true);
$gridConn->dynamic_loading(50);
$gridConn->render_sql("SELECT d.`loccode`,c.partcode,c.item_description,d.quantity FROM care_tz_drugsandservices c
LEFT JOIN care_ke_locstock d
ON d.stockid=c.partcode WHERE d.loccode IN('25','buffer')","partcode", "loccode,partcode,item_description,quantity");

// $gridConn->render_sql("$sql","id", "rev_code,service_type,partcode,Description,amnt,qty,total");

?>
