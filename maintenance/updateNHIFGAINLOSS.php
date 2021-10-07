<?php

require('./roots.php');
require('../include/inc_environment_global.php');
//require_once('../include/care_api_classes/class_tz_insurance.php');
//require_once('../include/care_api_classes/class_tz_billing.php');
//$insurance_obj = new Insurance_tz;
// $bill_obj = new Bill;

global $db;
$debug = false;

//$sql = "SELECT p.pid,c.encounter_nr FROM care_encounter c LEFT JOIN care_person p ON c.pid=p.pid 
//        WHERE is_discharged=1 AND encounter_class_nr=2 AND
//        encounter_date BETWEEN '2012-01-01' AND '2013-05-14' AND p.insurance_id>0 and debtorUpdate=0";

$username = $_SESSION['sess_login_username'];

$sql = "SELECT n.creditNo,n.inputDate,n.admno AS pid,b.`encounter_nr`,n.admDate,n.disDate,n.totalCredit,n.invAmount,
        n.balance,n.debtorUpdate,b.`input_user`,n.`ID`,b.`bill_number` FROM care_ke_nhifcredits n
            LEFT JOIN care_ke_billing b ON n.`creditNo`=b.`batch_no`
            WHERE b.`rev_code`='NHIF' AND n.`debtorUpdate`=0 and n.inputDate>'2012-12-31'";

$results = $db->Execute($sql);
if ($debug)
    echo $sql;


while ($rows = $results->FetchRow()) {

        $insuCompanyID = 'NHIF2';

//        $amount = intval($rows[total] - $row[sumTotal]);
        
        $transType = 2; //Invoices
        $trnsNo = getTransNo($transType);

        $sql4 = "insert into `care_ke_debtortrans`
                                (`transno`,`transtype`,`accno`, `pid`,`transdate`,`bill_number`,`amount`,`lastTransDate`,
                                `lasttransTime`,`settled`,encounter_nr,encounter_class_nr,reference)
                                values('$trnsNo','$transType','$insuCompanyID', '$rows[pid]','" . $rows[inputDate] . "','$rows[bill_number]',
                                '$rows[balance]','" . $rows[inputDate] . "','" . date('H:i:s') . "',0,'$rows[encounter_nr] ','1','$rows[ID]-$rows[creditNo]')";
        if ($debug)
            echo $sql4;
 
        if ($db->Execute($sql4)) {
            $sql7 = "INSERT INTO care_ke_billing (pid, encounter_nr,insurance_id,bill_date,bill_time,`ip-op`,bill_number,
                        service_type, price,`Description`,notes,
                        input_user,status,qty,total,rev_code,batch_no,debtorUpdate)
                        value('" . $rows[pid] . "','" . $rows[encounter_nr] . "','NHIF2','" . $rows[inputDate] . "','" . date('H:i:s') . "','1',
                            '" . $rows[bill_number] . "','NHIF','$rows[balance]','NHIF GAIN/LOSS','NHIF GAIN/LOSS','$rows[input_user]',
                        'billed','1','$rows[balance]','NHIF2','$trnsNo','1')";
            if($db->Execute($sql7)){
            
            $newTransNo = ($trnsNo + 1);
            $sql3 = "update care_ke_transactionNos set transNo='$newTransNo' where typeid='$transType'";
            if ($debug)
                echo $sql3;
            $db->Execute($sql3);

            $sql5 = "update care_ke_nhifcredits set debtorUpdate=1 where ID='$rows[ID]'";
            if ($debug)
                echo $sql5;
            $db->Execute($sql5);

            echo "Success sending $rows[pid] , $rows[bill_date], $rows[balance],$rows[bill_number] <br>";
            }else{
                echo "failure <br>$sql7 <br>";
            }
        }else {
            echo "failure <br>$sql4 <br>";
        }
    }

function getTransNo($transType) {
    global $db;
    $debug = false;
    $sql = "select `transNo` from `care_ke_transactionNos` where typeID=$transType";
    if ($debug)
        echo $sql;
    $result = $db->Execute($sql);
    $row = $result->FetchRow();

    $currNo = $row[0];
    $nextNo = ($currNo + 1);
    return $nextNo;
}

?>
