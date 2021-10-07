<?php

require('./roots.php');
require('../include/inc_environment_global.php');
require_once('../include/care_api_classes/class_tz_insurance.php');
require_once('../include/care_api_classes/class_tz_billing.php');
$insurance_obj = new Insurance_tz;
$bill_obj = new Bill;


$debug = false;
require('./roots.php');
require_once($root_path . 'include/care_api_classes/class_tz_billing.php');

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
