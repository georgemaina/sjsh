<?php
/**
 * Created by PhpStorm.
 * User: george
 * Date: 6/30/2015
 * Time: 11:25 AM
 */


require('./roots.php');
require('../include/inc_environment_global.php');

$debug=TRUE;

$sql="SELECT e.pid,e.`encounter_nr`,e.`encounter_class_nr`,e.`is_discharged`,e.`discharge_date`,l.`discharge_type_nr`
            ,l.`date_to`,l.`time_to`,l.`status`,l.`type_nr`
             FROM care_encounter e LEFT JOIN care_encounter_location l
            ON e.`encounter_nr`=l.`encounter_nr`
             WHERE e.`is_discharged`<>1 AND e.encounter_class_nr=1
            AND l.`date_to`<>'0000-00-00' AND l.`type_nr`=2";
//echo $sql;

$results=$db->Execute($sql);

while($row=$results->FetchRow()){
    //echo 'Selected Rows are '.$row[pid].' - '.$row[encounter_nr].'<br>';
    $sql="Update care_encounter set is_discharged='1' and discharge_date='$row[discharge_date]' where pid='$row[pid]' and encounter_nr='$row[encounter_nr]'";
  //  echo $sql;
    if($db->Execute($sql)){
        echo "Discharged PID $row[pid] successfully <br>";
    }
}

?>