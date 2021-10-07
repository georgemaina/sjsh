<?php

require_once('roots.php');
require($root_path.'include/inc_environment_global.php');
global $db;
    $pid=$_GET[patientId];
    $prescDate=$_GET[prescDate];
    require("../../include/dhtmlxConnector/codebase/grid_connector.php");
    $gridConn=new GridConnector($db);
    $gridConn->enable_log("temp.log",true);
    $gridConn->dynamic_loading(200);


$accDB=$_SESSION['sess_accountingdb'];
$pharmLoc=$_SESSION['sess_pharmloc'];

if($pharmLoc<>"care2x"){
    $stockDb=$accDB.locstock;
}else{
    $stockDb="care_ke_locstock";
}

    $sql = "SELECT  a.nr,a.`status`,a.create_time AS prescribe_date,a.partcode,a.article_item_number,a.article,
            a.dosage,times_per_day,a.days,a.price AS amnt,(a.dosage*times_per_day*a.days) AS qty,(a.price*(a.dosage*times_per_day*a.days)) AS total,
            l.quantity  FROM  care_encounter_prescription a
            INNER JOIN  care_encounter b ON a.encounter_nr=b.encounter_nr
            LEFT JOIN care_ke_locstock l ON a.partcode=l.stockid
            LEFT JOIN care_ke_stlocation c ON l.loccode=c.st_id
             WHERE  b.pid='$pid' AND a.drug_class IN ('drug_list','Medical-Supplies') ";

       //echo $sql;
      //if(isset($pid))
     //  $sql.=" Where `IP-OP`='1' and pid ='".$pid."'";

    $gridConn->render_sql("$sql","nr", "prescribe_date,status,partcode,article,dosage,times_per_day,days, quantity,amnt,qty,total");

?>
