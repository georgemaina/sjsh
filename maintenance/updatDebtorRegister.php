<?php


require('./roots.php');
require('../include/inc_environment_global.php');

$sql = "SELECT PAYROLLNO,PERCENTAGE,name FROM stafflist";

if ($debug)
    echo $sql;
$results = $db->Execute($sql);

while ($row = $results->FetchRow()) {

    $sql="Update care_ke_debtors set staffDiscount='$row[1]' where accno='$row[PAYROLLNO]'";
    if($db->Execute($sql)){
        echo "successfully update debtor $row[2] percentage $row[1]<br>";
    }else{
        echo " Could not update debtor $row[2] percentage $row[1]  $sql<br>";
    }
}
?>