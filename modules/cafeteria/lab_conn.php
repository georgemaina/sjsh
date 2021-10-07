<?php

$res=mysql_connect("192.168.1.231","admin","chak");
mysql_select_db("care2x");

require("../../include/dhtmlxConnector/codebase/grid_connector.php");
$gridConn=new GridConnector($res);
$gridConn->enable_log("temp.log",true);
$gridConn->dynamic_loading(100);
$gridConn->render_sql("select * from care2x.care_tz_drugsandservices where purchasing_class = 'labtest'","item_id", "item_number,item_description,unit_price");

?>
