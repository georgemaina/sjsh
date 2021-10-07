<?php
require_once('roots.php');
require($root_path.'include/inc_environment_global.php');

require("../../include/dhtmlxConnector/codebase/grid_connector.php");
$gridConn=new GridConnector($db);
$gridConn->enable_log("temp.log",true);
$gridConn->dynamic_loading(100);
$gridConn->render_sql("SELECT c.partcode,c.item_description,d.stockid,d.quantity,c.unit_price,d.reorderlevel FROM care_tz_drugsandservices c
INNER JOIN care_ke_locstock d ON d.stockid=c.partcode","partcode", "stockid,item_description,quantity,reorderlevel,unit_price");

?>
