<?php

require_once('roots.php');
require($root_path.'include/inc_environment_global.php');
global $db;
    $pid=$_GET[patientId];
    require("../../include/dhtmlxConnector/codebase/grid_connector.php");
    $gridConn=new GridConnector($db);
    $gridConn->enable_log("temp.log",true);
    $gridConn->dynamic_loading(100);

    
    
    $sql = "SELECT a.id,a.partcode,a.rev_code,a.service_type,a.pid,a.item_number,a.Description,
    a.price AS amnt,a.qty,a.total FROM care_ke_billing a LEFT JOIN care_person p ON a.pid=p.pid
    WHERE a.pid='".$pid."' AND a.status='pending' AND `IP-OP`=2";

      //if(isset($pid))
        //  $sql.=" Where `IP-OP`='1' and pid ='".$pid."'";
          
    $gridConn->render_sql("$sql","id", "rev_code,service_type,partcode,Description,amnt,qty,total");

?>
