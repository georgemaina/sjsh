<?php

require_once('roots.php');
require($root_path.'include/inc_environment_global.php');
global $db;
    $pid=$_GET[patientId];
    require("../../include/dhtmlxConnector/codebase/grid_connector.php");
    $gridConn=new GridConnector($db);
    $gridConn->enable_log("temp.log",true);
    $gridConn->dynamic_loading(100);


    $sql = "SELECT a.id,a.ledger,a.sup_id,a.Desc,a.AmountPaid
    FROM care2x.care_ke_Suppliers a";

      //if(isset($pid))
        //  $sql.=" Where `IP-OP`='1' and pid ='".$pid."'";
          
    $gridConn->render_sql("$sql","id", "ledger,sup_id,Desc,AmountPaid");

?>
