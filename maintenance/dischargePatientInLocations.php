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

$sql="select * from care_encounter_location  where encounter_nr in
            (SELECT encounter_nr FROM care_encounter WHERE encounter_class_nr=1 AND is_discharged=0 AND pid<>55899)  and date_to<>'0000-00-00'
            and type_nr=5";
echo $sql;

$results=$db->Execute($sql);

while($row=$results->FetchRow()){
    //echo 'Selected Rows are '.$row[pid].' - '.$row[encounter_nr].'<br>';
    $sql="update care_encounter set is_discharged='1',status='discharged',discharge_date='$row[date_to]',release_date='$row[date_to]'
                WHERE encounter_class_nr=1 AND is_discharged=0 AND encounter_nr='$row[encounter_nr]'";
  //  echo $sql;
    if($db->Execute($sql)){
        echo "Discharged PID $row[pid] successfully $sql<br>";
    }
}

?>