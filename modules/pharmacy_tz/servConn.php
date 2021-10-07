<?php

require_once('roots.php');
require($root_path.'include/inc_environment_global.php');
//global $db;
//    $req_no=$_GET[reqno];
    require("../../include/dhtmlxConnector/codebase/grid_connector.php");
    $gridConn=new GridConnector($db);
    $gridConn->enable_log("temp.log",true);
    $gridConn->dynamic_loading(500);
    $gridConn->render_sql("select * from care_ke_internalreq WHERE status='pending'","id", "item_id,Item_desc,qty,price,qty_issued,`Total`");


//    $sql = "select * from care_ke_internalreq WHERE status='pending'";

//      if(isset($req_no))
//          $sql.=" and req_no='$req_no'";
          
//    $gridConn->render_sql("select * from care_ke_internalreq WHERE status='pending'","id", "item_id,Item_desc,qty,price,qty_issued,`Total`");

?>
