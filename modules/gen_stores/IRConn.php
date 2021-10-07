<?php

require_once('roots.php');
require($root_path.'include/inc_environment_global.php');
global $db;
    $req_no=$_GET[reqno];
    $storeLoc=$_GET[sup_store];

     require("../../include/dhtmlxConnector/codebase/grid_connector.php");
    $gridConn=new GridConnector($db);
    $gridConn->enable_log("temp.log",true);
    $gridConn->dynamic_loading(100);

    $accDB=$_SESSION['sess_accountingdb'];
    $pharmLoc=$_SESSION['sess_pharmloc'];

    $sql = "select item_id,Item_desc,qty,price,qty_issued,balance,s.quantity AS Qty_In_Store
     from care_ke_internalreq ";


        $sql .= "r LEFT JOIN $accDB.`locstock` s ON r.`item_id`=s.`stockid` WHERE s.loccode in ('GEN','44')";

    $sql.=" AND req_no='$req_no' and status='pending'";

//    echo $sql;


//      if(isset($req_no))
//          $sql.=" and req_no='$req_no'";
          
    $gridConn->render_sql("$sql","id","item_id,Item_desc,qty,price,qty_issued,balance,Qty_In_Store,".$sql);

?>
