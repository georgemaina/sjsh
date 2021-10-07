<?php

require_once('roots.php');
require($root_path.'include/inc_environment_global.php');
global $db;
    $prescNo=$_GET[prescNo];
    require("../../include/dhtmlxConnector/codebase/grid_connector.php");
    $gridConn=new GridConnector($db);
    $gridConn->enable_log("temp.log",true);
    $gridConn->dynamic_loading(100);


    $sql = "SELECT id,order_no,presc_nr,item_id,item_desc,price,qty,unit_cost FROM care_ke_internal_orders WHERE 
        presc_nr='$prescNo'";

      if(isset($pid))
          $sql.=" Where pid ='".$pid."'";
    
    
    $gridConn->render_sql("$sql","id", "order_no,presc_nr,item_id,item_desc,price,qty,unit_cost");

?>
