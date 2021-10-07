<?php


require('./roots.php');
require('../include/inc_environment_global.php');

$sql = "SELECT pid,encounter_nr,bill_number from care_ke_billing where bill_number<>'' and bill_number<>0 and pid is not null
and encounter_nr in(select encounter_nr from care_encounter where bill_number=0)
            group by encounter_nr";

if ($debug)
    echo $sql;
$results = $db->Execute($sql);

$counter=0;
while ($row = $results->FetchRow()) {

    $sql="Update care_encounter set bill_number='$row[2]' where pid='$row[0]' and encounter_nr='$row[1]'";
    if($db->Execute($sql)){
        echo "successfully update pid $row[0] encounter_nr $row[1]<br>";
    }else{
        echo " Could not update pid $row[0] encounter_nr $row[1]  $sql<br>";
    }
    $counter=$counter+1;
}
echo "<br><br> Total records updated is ".$counter;
?>