<?php

require_once('roots.php');
require($root_path.'include/inc_environment_global.php');
global $db;
    $req_no=$_GET['reqno'];
    $storeLoc=$_GET['sup_store'];
require("../../include/dhtmlxConnector/codebase/grid_connector.php");
require("../../include/dhtmlxConnector/codebase/db_mysqli.php");

$mysqli = $conn;
$gridConn = new GridConnector($mysqli,"MySQLi");
$accDB=$_SESSION['sess_accountingdb'];
$pharmLoc=$_SESSION['sess_pharmloc'];

    $gridConn->enable_log("temp.log",true);
    $gridConn->dynamic_loading(100);


    $sql = "select r.item_id,Item_desc,d.purchasing_unit,qty,qty_issued,balance,s.quantity AS Qty_In_Store,Unit_qty as TotalUnits,Unit_qty
     from care_ke_internalreq r";

    if($storeLoc=='MAIN' || $storeLoc=='GEN') {
        $sql .= " INNER JOIN $accDB.`locstock` s ON r.`item_id`=s.`stockid`
                 INNER JOIN care_tz_drugsandservices d ON r.item_id=d.partcode";
    }else{
        $sql.=" INNER JOIN `care_ke_locstock` s ON r.`item_id`=s.`stockid` 
                 INNER JOIN care_tz_drugsandservices d ON r.item_id=d.partcode";
    }

    $sql.=" AND req_no='$req_no' and status='pending' and s.loccode='$storeLoc'";

    echo $sql;


//      if(isset($req_no))
//          $sql.=" and req_no='$req_no'";
          
    $gridConn->render_sql("$sql","id","item_id,Item_desc,Unit_qty,purchasing_unit,qty,qty_issued,balance,Qty_In_Store,TotalUnits");

?>
