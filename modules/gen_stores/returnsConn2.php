<?php

require_once('roots.php');
require($root_path.'include/inc_environment_global.php');
global $db;
    $orderNo=$_GET[order_no];
    require("../../include/dhtmlxConnector/codebase/grid_connector.php");
    $gridConn=new GridConnector($db);
    $gridConn->enable_log("temp.log",true);
    $gridConn->dynamic_loading(100);


    $sql = "SELECT c.partcode,c.item_description,d.item_id,d.qty,d.unit_cost,d.total
            FROM care_tz_drugsandservices c INNER JOIN care_ke_internal_orders d
            ON d.item_id=c.partcode where d.order_no='".$orderNo."'";

      //if(isset($pid))
        //  $sql.=" Where `IP-OP`='1' and pid ='".$pid."'";

    $gridConn->render_sql("$sql","partcode", "item_id,item_description,qty,unit_cost,total");

?>
