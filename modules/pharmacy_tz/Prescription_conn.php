<?php

require_once('roots.php');
require($root_path.'include/inc_environment_global.php');
global $db;
    $issDate=$_GET[issDate];
    $pid=$_GET[pid];
    require("../../include/dhtmlxConnector/codebase/grid_connector.php");
    $gridConn=new GridConnector($db);
    $gridConn->enable_log("temp.log",true);
    $gridConn->dynamic_loading(100);


    $sql = "SELECT id,op_no,order_no,presc_nr,item_id,item_desc,price,qty,unit_cost,issued,orign_qty,balance  
        FROM care_ke_internal_orders o LEFT JOIN care_encounter e ON o.`OP_no`=e.`pid`";
      if(isset($pid))
          $sql.=" Where  pid ='".$pid."'";
     // echo $sql;
    
    
    $gridConn->render_sql("$sql","id", "op_no,order_no,presc_nr,item_id,item_desc,price,qty,unit_cost,issued,orign_qty,balance");

?>
