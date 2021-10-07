<?php
require_once('roots.php');
require($root_path.'include/inc_environment_global.php');

require("../../include/dhtmlxConnector/codebase/grid_connector.php");
$gridConn=new GridConnector($db);
$gridConn->enable_log("temp.log",true);
$gridConn->dynamic_loading(100);
$gridConn->render_sql("SELECT partcode,purchasing_class,item_description,item_id,unit_price FROM care_tz_drugsandservices
","item_id", "partcode,purchasing_class,item_description,unit_price");

?>
