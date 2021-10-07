<?php

require_once('roots.php');
require($root_path.'include/inc_environment_global.php');
global $db;
    $pid=$_GET[patientId];
    $store=$_REQUEST[storeID];

require("../../include/dhtmlxConnector/codebase/grid_connector.php");
require("../../include/dhtmlxConnector/codebase/db_mysqli.php");

$mysqli = $conn;
$gridConn = new GridConnector($mysqli,"MySQLi");

$accDB=$_SESSION['sess_accountingdb'];
$gridConn->enable_log("temp.log",true);
$gridConn->dynamic_loading(200);

    $accDB=$_SESSION['sess_accountingdb'];
    $pharmLoc=$_SESSION['sess_pharmloc'];

    if($pharmLoc<>"care2x"){
        $stockDb=$accDB.locstock;
    }else{
        $stockDb="care_ke_locstock";
    }

    $sql = "SELECT a.nr,b.encounter_nr,b.encounter_class_nr,a.create_time as prescribe_date,a.partcode,b.pid,a.article_item_number,a.article,
            a.dosage,times_per_day,a.days,a.article,
            a.price AS amnt,(a.dosage*times_per_day*a.days) AS qty,(a.price*(a.dosage*times_per_day*a.days)) AS total,
            l.quantity,'Issued' FROM care_encounter_prescription a
            INNER JOIN  care_encounter b ON a.encounter_nr=b.encounter_nr
            LEFT JOIN $stockDb l on a.partcode=l.stockid
            LEFT JOIN care_ke_stlocation c on l.loccode=c.st_id
            WHERE b.pid='".$pid."' AND a.drug_class in ('drug_list','Medical-Supplies') and b.encounter_class_nr=1 
            AND a.status NOT IN('serviced','done','Cancelled') ";

      //if(isset($pid))
        //  $sql.=" Where `IP-OP`='1' and pid ='".$pid."'";
          
    $gridConn->render_sql("$sql","nr", "article_item_number,prescribe_date,partcode,article,dosage,times_per_day,days,
    quantity,amnt,qty,issued,total");

?>
