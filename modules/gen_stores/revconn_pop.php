<?php
require_once('roots.php');
require($root_path.'include/inc_environment_global.php');

require("../../include/dhtmlxConnector/codebase/grid_connector.php");

$accDB=$_SESSION['sess_accountingdb'];

$gridConn=new GridConnector($db);
$gridConn->enable_log("temp.log",true);
$gridConn->dynamic_loading(200);

$store=$_REQUEST['store'];

if($store=='CSSD'){
    $store='CSSD';
}else{
    $store='Dispens';
}

$accDB=$_SESSION['sess_accountingdb'];
$pharmLoc=$_SESSION['sess_pharmloc'];
//		}

    $gridConn->render_sql("SELECT DISTINCT c.partcode,c.item_description,d.stockid, d.quantity,c.unit_price,d.reorderlevel FROM care_tz_drugsandservices c
      INNER JOIN maua.locstock d ON  d.stockid=c.partcode WHERE loccode IN ('GEN','KIT') AND d.`quantity`>0","partcode",
        "partcode,item_description,quantity,unit_price,reorderlevel");



// $gridConn->render_sql("$sql","id", "rev_code,service_type,partcode,Description,amnt,qty,total"); 0724884327

?>
