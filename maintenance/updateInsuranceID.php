<?php


require('./roots.php');
require('../include/inc_environment_global.php');

$sql = "SELECT pid,insurance_id from care_person where insurance_id<>'-1'";

if ($debug)
    echo $sql;
$results = $db->Execute($sql);

while ($row = $results->FetchRow()) {

    $sql="Update care_encounter set insurance_firm_id='$row[1]' where pid='$row[0]'";
    if($db->Execute($sql)){
        echo "successfully update debtor $row[0] insurance_id $row[1]<br>";
    }else{
        echo " Could not update debtor $row[0] insurance_id $row[1]  $sql<br>";
    }
}
?>