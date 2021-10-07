<?php
error_reporting(E_ALL);
require('./roots.php');
require('../include/inc_environment_global.php');
require_once('../include/care_api_classes/class_tz_insurance.php');
require_once('../include/care_api_classes/class_tz_billing.php');
$insurance_obj = new Insurance_tz;
$bill_obj = new Bill;
global $db;
$debug = false;

$dtNow = date('Y-m-d');
$disTime = date('H:i:s');
$sql = "select * from care_encounter where encounter_class_nr=2 and is_discharged=0 and encounter_date<'$dtNow'";
if($debug) echo $sql;
$result = $db->Execute($sql);
$counter=0;
while ($row = $result->FetchRow()) {

//    if ($row['encounter_date'] < $dtNow) {
      $sql1 = "UPDATE `care_encounter_location` SET `discharge_type_nr` = '1',`date_to` = '$dtNow',
            `time_to` = '$disTime', status='discharged'
            WHERE date_to = '0000-00-00' and encounter_nr='$row[encounter_nr]'";
	if($debug) echo $sql1;
        $db->Execute($sql1);
                
        $sql = "UPDATE `care_encounter` SET `is_discharged` = '1',`discharge_date` = '$dtNow',
            `discharge_time` = '$disTime', finalised=1
            WHERE IS_discharged = '0' and encounter_class_nr=2 and 
            pid='$row[pid]' and encounter_nr='$row[encounter_nr]'";
			if($debug) echo $sql;
			
        if ($db->Execute($sql)) {

            $IS_PATIENT_INSURED = $insurance_obj->is_patient_insured($row[pid]);
            $insuCompanyID = $insurance_obj->GetCompanyFromPID2($row[pid]);
            if ($IS_PATIENT_INSURED) {
               $balance=$bill_obj->getDebtorBalance($row[encounter_nr]);
                if($insuCompanyID=='2088'){
                    if($balance<1500){
                        $capitation=1500-$balance;
                        $bill_obj->updateDebtorBill($row[encounter_nr],$insuCompanyID,$row[pid],$capitation,$dtNow);
                    }
                }

//                 updateIncome($row[pid],$row[encounter_nr],$bill_obj);
                 $bill_obj ->updateDebtorsTrans($row[pid],$insurance_obj,$row[encounter_nr]);
            }

            $sql="update care_encounter set finalised=1 where encounter_nr='$row[encounter_nr]'";
			if($debug) echo $sql;
            $db->Execute($sql);

            echo "Patient $row[pid] discharged successfully <br> ";
        } else {
            echo 'Error discharging patients $row[pid]' . $sql;
        }
        $counter=$counter+1;
//    }
}

echo "<br><br><br> TOTAL DISCHARGES ARE ".$counter;


function updateIncome($pid,$encNr,$bill_obj){
    global $db;
    $debug=false;

    $sql="SELECT b.pid,encounter_nr,`ip-op`,bill_date,bill_number,b.`partcode`,b.`rev_code`,
                                b.`service_type`,b.`Description`,b.`total`,b.`status`,d.`gl_sales_acct`
                                FROM care_ke_billing b left join `care_tz_drugsandservices` d on b.`partcode`=d.`partcode`
                                 WHERE b.pid=$pid and encounter_nr=$encNr
                                AND service_type NOT IN('payment','NHIF','NHIF2','NHIF3','NHIF4')";
    if($debug) echo $sql;
    $results=$db->Execute($sql);

    if($bill_obj->checkIncomeTrans('invoice')){
        while($row=$results->FetchRow()){
//                                $glCode=$bill_obj->getItemGL($rev_code);
            $bill_obj->updateIncomeTrans($row[gl_sales_acct],$row[total],$row[bill_date],$row[bill_number],'+','invoice');
        }
    }
}



?>
<!--<script>
    window.close();
</script>-->