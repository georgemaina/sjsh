<?php
require('./roots.php');
require('../include/inc_environment_global.php');
require_once('../include/care_api_classes/class_tz_insurance.php');
require_once('../include/care_api_classes/class_tz_billing.php');
$insurance_obj = new Insurance_tz;
 $bill_obj = new Bill;
 
global $db;
$debug = false;

$sql = "SELECT p.pid,c.encounter_nr FROM care_encounter c LEFT JOIN care_person p ON c.pid=p.pid 
        WHERE is_discharged=1 AND
        encounter_date BETWEEN '2012-01-01' AND '2013-04-30' AND p.insurance_id>0 and debtorUpdate=1";

$results = $db->Execute($sql);
if($debug) echo $sql;

while ($rows = $results->FetchRow()) {
   $sql="UPDATE care_ke_billing set debtorUpdate=0 where debtorUpdate=1 and pid=$rows[pid] and encounter_nr=$rows[encounter_nr]";
}

?>
