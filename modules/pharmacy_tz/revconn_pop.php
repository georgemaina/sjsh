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
$gridConn->dynamic_loading(50);

$store=$_REQUEST['store'];
//		}
if($store=="MAIN" || $store=="GEN"){
    $gridConn->render_sql("SELECT DISTINCT c.partcode,c.item_description,c.purchasing_unit,d.`quantity`,c.unit_price,d.reorderlevel FROM care_tz_drugsandservices c
      LEFT JOIN $accDB.locstock d ON  d.stockid=c.partcode where loccode IN ('MAIN','GEN') and c.item_status NOT IN (2,3) order by c.item_description asc","partcode",
        "partcode,item_description,purchasing_unit,quantity,reorderlevel");
}else{

    $gridConn->render_sql("SELECT DISTINCT c.partcode,c.item_description,c.purchasing_unit,d.`quantity`,c.unit_price,d.reorderlevel,c.purchasing_unit FROM care_tz_drugsandservices c
      LEFT JOIN care_ke_locstock d ON  d.stockid=c.partcode where loccode='$store' and c.item_status NOT IN (2,3) AND d.`quantity`>0  order by c.item_description asc","partcode",
        "partcode,item_description,purchasing_unit,quantity,reorderlevel");
}


// $gridConn->render_sql("$sql","id", "rev_code,service_type,partcode,Description,amnt,qty,total"); 0724884327

?>
