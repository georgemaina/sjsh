<?php
require('./roots.php');
require('../include/inc_environment_global.php');
require_once('../include/care_api_classes/class_tz_insurance.php');
require_once('../include/care_api_classes/class_tz_billing.php');
$insurance_obj = new Insurance_tz;
 $bill_obj = new Bill;
 
global $db;
$debug = true;

     $sql="SELECT b.pid,encounter_nr,`ip-op`,bill_date,bill_number,SUM(total) AS total,debtorUpdate,c.id,p.`insurance_ID`
     FROM care_ke_billing b LEFT JOIN care_person p ON b.`pid`=p.`pid` 
     LEFT JOIN CARE_TZ_COMPANY C ON P.`insurance_ID`=c.`id` WHERE service_type
    NOT IN('payment','NHIF','NHIF2','NHIF3','NHIF4') AND c.`accno`='KWS' AND debtorUpdate=0
        AND bill_date<>'2017-03-22'  GROUP BY encounter_nr";

    if ($debug) echo $sql;
    $result = $db->Execute($sql);

    while($row = $result->FetchRow()) {

        $sql2 = "SELECT pid,encounter_nr,`ip-op`,bill_date,bill_number,SUM(total) AS total,debtorUpdate,id
            FROM care_ke_billing WHERE pid=$row[pid] and encounter_nr=$row[encounter_nr]
            AND service_type IN('payment','NHIF','NHIF2','NHIF3','NHIF4')
            GROUP BY encounter_nr";

        $result2 = $db->Execute($sql2);
        $row2 = $result2->FetchRow();
        $payments = $row2[total];

        $transType = 2; //Invoices
        $trnsNo = $bill_obj->getTransNo($transType);

////        if ($transType == 2) {
//            $amount2 = $row[total] - $payments;
//        }

        $balance=$bill_obj->getDebtorBalance($row[encounter_nr]);
//        $capitation=0;
        $insuCompanyID = $insurance_obj->GetCompanyFromPID2($row[pid]);
        if($insuCompanyID=='11046' || $insuCompanyID=='11047'){
            if($balance<2000){
                $capitation=2000-$balance;
                $bill_obj->updateDebtorBill($row[encounter_nr],$insuCompanyID,$row[pid],$capitation);
            }
        }

        $bill_obj ->updateDebtorsTrans($row[pid],$insuCompanyID,$row[encounter_nr]);



            echo "Created transaction for $row[pid] and encounter_nr=$row[encounter_nr] <br>";


    }


?>