<?php
require_once('roots.php');
require($root_path.'include/inc_environment_global.php');

require("../../include/dhtmlxConnector/codebase/grid_connector.php");
$gridConn=new GridConnector($db);
$gridConn->enable_log("temp.log",true);
$gridConn->dynamic_loading(100);
$gridConn->render_sql("SELECT i.catID,i.item_Cat,d.partcode,d.item_description, d.unit_price FROM care_tz_drugsandservices d 
LEFT JOIN care_tz_itemscat i ON d.category=i.catID
    WHERE d.purchasing_class IN('FOOD STUFF') ","item_number", "catID,item_Cat,partcode,item_description,unit_price");

?>
