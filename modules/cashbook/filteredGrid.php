<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('roots.php');
    require($root_path.'include/inc_environment_global.php');
    global $db;

    $id=$_REQUEST['desc6'];
    require("../../include/dhtmlxConnector/codebase/grid_connector.php");
    $gridConn=new GridConnector($db);
    $gridConn->enable_log("temp.log",true);
    $gridConn->dynamic_loading(100);
    $gridConn->render_sql("SELECT service_type,service_type,item_number,Description,price,qty,total from care2x.care_ke_billing
              Where `IP-OP`='2' and pid='$id'","pid", "service_type,service_type,item_number,Description,price,qty,total");
    
?>