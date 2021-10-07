<?php
require('./roots.php');
require('../include/inc_environment_global.php');
require_once('../include/care_api_classes/class_tz_insurance.php');
require_once('../include/care_api_classes/class_tz_billing.php');
$insurance_obj = new Insurance_tz;
 $bill_obj = new Bill;
 
global $db;
$debug = true;

$sql = "SELECT e.encounter_nr,e.pid FROM care_encounter e LEFT JOIN care_person p ON e.`pid`=p.`pid` WHERE encounter_date>'2017-06-18' 
AND p.`insurance_ID`='1336' and e.finalised=1";

$results = $db->Execute($sql);
if($debug) echo $sql;

while ($rows = $results->FetchRow()) {
  $rsql="UPDATE care_encounter SET finalised=0 WHERE encounter_nr=$rows[0]";
    if($db->Execute($rsql)){
        echo "Encounter number $rows[0] updated successfully<br>";
    }else{
        echo "Could no update Encounter number $rows[0]<br>";
    }
}

?>
