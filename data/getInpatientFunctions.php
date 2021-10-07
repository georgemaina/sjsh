<?php

require_once('roots.php');
require($root_path . 'include/inc_environment_global.php');
require_once($root_path . 'include/care_api_classes/class_tz_insurance.php');
require_once($root_path . 'include/care_api_classes/class_encounter.php');
require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
require_once($root_path.'include/care_api_classes/class_ward.php');
// Load the wards info
$ward_obj=new Ward;
$items='nr,ward_id,name';
$ward_info=$ward_obj->getAllWardsItemsObject($items);


$insurance_obj = new Insurance_tz;
$bill_obj = new Bill;
$enc_obj = new Encounter;

$desc = $_GET['rev'];

$caller = $_REQUEST['caller'];

$pid = $_REQUEST['pid'];
$ID = $_REQUEST['ID'];
$table = $_REQUEST['table'];

$date1 = new DateTime($_REQUEST['startDate']);
$startDate = $date1->format("Y-m-d");

$date2 = new DateTime($_REQUEST['endDate']);
$endDate = $date2->format("Y-m-d");

$claimNo = $_REQUEST['claimNo'];
$bill_number = $_REQUEST['bill_number'];

$ward_nr=$_REQUEST['wardId'];
$wrdNo=$_REQUEST['wardNo'];

$limit = $_REQUEST['limit'];
$start = $_REQUEST['start'];

$searchParam=$_REQUEST['searchParam'];

switch ($caller) {
    case "debit":
        getPNames($pid);
        break;
    case "grid":
        getDescription($desc);
        break;
    case "getBillNumbers":
        getBillNumbers($pid);
        break;
    case "finalize":
        finalizeInvoice($db, $pid, $bill_number, $fdate);
        break;
    case "getNewReceipt":
        getNewReceipt();
        break;
    case "nhif":
        getNHIFCredits($dt1, $dt2);
        break;
    case "closeBill":
        closeBill($insurance_obj, $bill_obj);
        break;
    case "deleteClaim":
        deleteClaim($claimNo, $pid, $bill_number);
        break;
    case "getBills":
        getBills($pid, $bill_number);
        break;
    case "getItemsList":
        getItemsList($searchParam,$start,$limit);
        break;
    case "saveDebit":
        saveDebits($enc_obj,$insurance_obj);
        break;
    case "getEncounter":
        getEncounter($enc_obj, $pid);
        break;
    case "getBillNumbers":
        getBillNumbers($pid);
        break;
    case "getEncounterNumbers":
        getEncounterNumbers($pid);
        break;
    case "combineBills":
        combineBills($pid);
        break;
    case "deleteBillItem":
        deleteBillItem($pid);
        break;
    case "updateBillItems":
        updateBillItems($_POST);
        break;
    case "getAllBills":
        getAllBills($pid, $bill_number);
        break;
    case "checkFinaliseStatus":
        checkFinaliseStatus($pid, $bill_obj, $bill_number);
        break;
    case "DeleteItems":
        deleteMethod($ID, $table);
        break;
    case "getReceipts":
        getReceipts($pid, $refNo);
        break;
    case "deleteReceiptItem":
        deleteReceiptItem($pid);  //32367
        break;
    case "insuranceCredit":
        insuranceCredit($_POST);
        break;
    case "getInsuranceCompanies":
        getInsuranceCompanies();
        break;
    case "getCreditNo":
        getCreditNo();
        break;
    case "getTotalBill":
        getTotalBill($pid, $bill_number);
        break;
    case "saveInsuranceCredit":
        saveInsuranceCredit($_POST, $bill_obj, $insurance_obj);
        break;
    case "getNhifCreditNo":
        getNhifCreditNo();
        break;
    case "getNhifRates":
        getNhifRates();
        break;
    case "insertNhifCredit";
        insertNhifCredit($_POST, $bill_obj);
        break;
    case "getDebitNo":
        getDebitNo();
        break;
    case "getWardInfo":
        getWardInfo($ward_info);
        break;
    case "getPatientsInWard":
        getPatientsInWard($ward_obj,$ward_nr);
        break;
    case "getWaitingList":
        getWaitingList($wrdNo,$ward_obj);
        break;
    default:
        echo "{failure:true}";
        break;
}

function getWaitingList($wrdNo,$ward_obj){

    $waitlist=$ward_obj->createWaitingInpatientList($wrdNo);
    $waitlist_count=$ward_obj->LastRecordCount();

    echo '{
    "total":"' . $waitlist_count . '","patients":[';
    $counter=0;
    while($row=$waitlist->FetchRow()){
        $names=$row['name_first']." " . $row['name_last'];
        echo '{"Pid":"' . $row['pid'] .'","Encounter_Nr":"' . $row['encounter_nr'] .'","Names":"' . $names.'","Dob":"' . $row['date_birth'] .'"}';

        $counter++;

        if ($counter <> $waitlist_count) {
            echo ",";
        }
    }
    echo ']}';
}

function getPatientsInWard($ward_obj,$ward_nr){
global $db;
$debug=false;
$ward_info=$ward_obj->getWardInfo($ward_nr);
$room_obj=$ward_obj->getRoomInfo($ward_nr,$ward_info['room_nr_start'],$ward_info['room_nr_end']);
    if(is_object($room_obj)) {
        $room_ok=true;
    }else{
        $room_ok=false;
    }

$nr_beds=$ward_obj->countBeds($ward_nr);
$patients_obj=$ward_obj->getDayWardOccupants($ward_nr);

# Prepare patients data into array matrix
    if(is_object($patients_obj)){
        # Prepare patients data into array matrix
        while($buf=$patients_obj->FetchRow()){
            $patient[$buf['room_nr']][$buf['bed_nr']]=$buf;
        }
        $patients_ok=true;
        $occup='ja';
    }else{
        $patients_ok=false;
    }
    $ward_ok=true;

    $toggle=1;
    $room_info=array();
    # Set occupied bed counter
    $occ_beds=0;
    $cflag=$ward_info['room_nr_start'];
    $sListRows='';
    $strPatientRecord='';

    echo '{"occupancy":[';
for ($i=$ward_info['room_nr_start'];$i<=$ward_info['room_nr_end'];$i++){
        if($room_ok){
            $room_info=$room_obj->FetchRow();
        }else{
            $room_info['nr_of_beds']=1;
            $edit=false;
        }

    for($j= $room_info['firstBedNo'];$j<=$room_info['lastBedNo'];$j++){
            if($patients_ok){

                if(isset($patient[$i][$j])){
                    $bed=$patient[$i][$j];
                    $is_patient=true;
                    # Increase occupied bed nr
                    $occ_beds++;
                }else{
                    $is_patient=false;
                    $bed=NULL;
                }
            }

            //echo "Bed parient is ".$bed['name_first']."<br>";

//        if($j== $room_info['firstBedNo']) {
//            echo '{"RoomNo":"Room ' . $i;
//        } else{
//            echo'{"RoomNo":"';
//        }
//1116012251     tsj221

        echo '{"RoomNo":"Room ' . $i;

        echo '","BedNo":"' . $j;
        echo'","Sex":"' . $bed['sex'];

            $names=$bed['name_first']." ".$bed['name_last']." ".$bed['name_2'];

        echo '","Names":"'.$names;
        echo '","BirthDate":"'.$bed['date_birth'];
        echo '","PID":"'. $bed['pid'];
        echo '","AdmissionDate":"'. $bed['encounter_date'];

            if($bed['insurance']<>""){
                $payment=preg_replace('/[^a-zA-Z0-9_ -]/s', '', $bed['insurance']);
               // $paymentMode=$bed['insurance'];
            }elseif($bed['insurance']=="" && $bed['pid']<>""){
                $payment="CASH";
            }else{
                $payment="";
            }

        echo '","PaymentMode":"'.$payment;
        echo '","EncounterNo":"'.$bed['encounter_nr'];
        echo '","UrlAppend":"'.URL_REDIRECT_APPEND;
        echo '","BillNumber":"'.$bed['bill_number'];
        echo '","WardID":"'.$bed['wardID'].'"}';

        if($i<=$ward_info['room_nr_end']){
            echo ',';
        }

        }
   // echo  $strPatientRecord;
    }
    echo ']}';
}

function getWardInfo($ward_info){
    $total=$ward_info->RecordCount();
    echo '{"total":"' . $total . '","wards":[';
    $counter = 0;
   while($stations=$ward_info->FetchRow()) {
        echo '{"Nr":"' .  $stations['nr'] . '","WardID":"' . $stations['ward_id'] . '","Name":"' .  $stations['name'] . '"}';

        $counter++;

        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']}';

}

function insertNhifCredit($nhifDetails, $bill_obj) {
    global $db;
    $debug = false;

    $new_bill_number = $bill_obj->checkBillEncounter($nhifDetails['encounterNr']);
    $input_User = $_SESSION['sess_login_username'];

    $user = $_SESSION['sess_login_username'];
    $sql = "INSERT INTO care_ke_nhifcredits(creditNo,bill_number,inputDate,admno,NAMES, admDate,disDate,wrdDays,nhifNo,nhifDebtorNo,debtordesc, totalCredit,invAmount, balance,inputUser)
	    VALUES('$nhifDetails[creditNo]','$new_bill_number','" . date('Y-m-d') . "','$nhifDetails[txtPid3]', '$nhifDetails[pname]','$nhifDetails[admissionDate]', '$nhifDetails[dischargeDate]','$nhifDetails[days]', 
                '$nhifDetails[nhifNo]','$nhifDetails[accno]', '$nhifDetails[nhifAccount]','$nhifDetails[totalCredit]','$nhifDetails[invoiceAmount]','$nhifDetails[balance]','$input_User')";

    if ($debug)echo $sql;
    if ($db->Execute($sql)) {
        updateNhifBill($nhifDetails, $bill_obj);
        $invBalance = ($nhifDetails[totalCredit] - $nhifDetails[invAmount]);
        if ($invBalance <> 0) {
            updateNhifGainloss($nhifDetails, $bill_obj);
        }
        echo '{success:true,"msg":"Successfully saved NHIF Credit"}';
    } else {
        echo '{failure:true,"msg":"Could not save NHIF Credit, Please check your values"}';
    }
}

function updateNhifBill($nhifDetails, $bill_obj) {
    global $db;
    $debug = true;
    $user = $_SESSION['sess_user_name'];
    $sql3 = "INSERT INTO care_ke_billing (pid, encounter_nr,insurance_id,bill_date,bill_time,`ip-op`,bill_number,service_type, price,`Description`,notes,input_user,status,days,qty,total,rev_code,batch_no)
             VALUES('" . $nhifDetails[txtPid3] . "','" . $nhifDetails[encounterNr] . "','" . $nhifDetails[nhifDbAc] . "','" . date("Y-m-d") . "','" . date("H:i:s") . "','1',
            '" . $nhifDetails[billNumber] . "','NHIF','$nhifDetails[totalCredit]','NHIF Credit No','NHIF Credit','$user','billed','" . $nhifDetails[days] . "','1','$nhifDetails[totalCredit]','NHIF','$nhifDetails[creditNo]')";

    if ($debug) echo $sql3 . "<br>";
    if ($db->Execute($sql3)) {
        $trnsNo = $bill_obj->getTransNo(2);
        $sql4 = "INSERT into `care_ke_debtortrans`(`transno`,`transtype`,`accno`, `pid`,`transdate`,`bill_number`,`amount`,`lastTransDate`,`lasttransTime`,`settled`,encounter_nr,accountNo)
                 VALUES('$trnsNo','2','NHIF', '$nhifDetails[txtPid3]','" . date('Y-m-d') . "','$nhifDetails[billNumber]','$nhifDetails[totalCredit]','" . date('Y-m-d') 
                . "','" . date('H:i:s') . "',0,'$nhifDetails[encounterNr]','$nhifDetails[nhifNo]')";
        if ($debug)echo $sql4;
        if ($db->Execute($sql4)) {
            $newTransNo = ($trnsNo + 1);
            $sql3 = "update care_ke_transactionNos set transNo=$newTransNo where typeid=2";
            if ($debug) echo $sql3;
            $db->Execute($sql3);
        }
    }
}

function updateNhifGainloss($nhifDetails, $bill_obj) {
    global $db;
    $debug = true;

    $invBalance = ($nhifDetails[totalCredit] - $nhifDetails[invAmount]);
    $trnsNo = $bill_obj->getTransNo(2);
    $sql = "INSERT into `care_ke_debtortrans`(`transno`,`transtype`,`accno`, `pid`,`transdate`,`bill_number`,`amount`,`lastTransDate`,`lasttransTime`,`settled`,encounter_nr,accountNo)
             VALUE('$trnsNo','2','NHIF2', '$nhifDetails[txtPid3]','" . date('Y-m-d') . "','$nhifDetails[billNumber]','$invBalance','" . date('Y-m-d') . "','" . date('H:i:s') 
            . "',0,'$nhifDetails[encounterNr]','$nhifDetails[nhifNo]')";
    if ($debug) echo $sql;

    if ($db->Execute($sql)) {
        $newTransNo = ($trnsNo + 1);
        $sql2 = "update care_ke_transactionNos set transNo=$newTransNo where typeid=2";
        if ($debug) echo $sql2;
        $db->Execute($sql2);
    }
}

function updateInsuranceBill($pid, $newBal) {
    global $db;
    $debug = false;
    $sql = "SELECT d.accno,d.os_bal FROM care_ke_debtors d LEFT JOIN care_tz_company c ON d.accno=c.accno
            LEFT JOIN care_person p ON c.id=p.insurance_ID WHERE p.pid=$pid";

    if ($debug)
        echo $sql . "<br>";
    $result = $db->Execute($sql);

    $row = $result->FetchRow();
    $bal = intval($row[1] + $newBal);

    $sql = "update care_ke_debtors set os_bal='$bal' where accno='$row[0]'";
    if ($debug)
        echo $sql . "<br>";
    $db->Execute($sql);
}

function getNhifRates() {
    global $db;
    $debug = false;

    $rates = $_REQUEST['rate'];

    $sql = "SELECT ID,RateType,RateValue,rateCalc FROM care_ke_Rates";

    if ($rates <> '') {
        $sql = $sql . " where ID=$rates";
    }
    if ($debug)
        echo $sql;

    $result = $db->Execute($sql);
    $total=$result->RecordCount();

    echo '{
    "total":"' . $total . '","nhifRates":[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        // $description=preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[name]);
        echo '{"ID":"' . $row[ID] . '","RateType":"' . $row[RateType] . '","RateValue":"' . $row[RateValue] . '","rateCalc":"' . $row[rateCalc] . '"}';

        $counter++;

        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']}';
}

function saveInsuranceCredit($creditDetails, $bill_obj, $insurance_obj) {
    global $db;
    $debug = true;

    $billDate = date('Y-m-d');
    $new_bill_number = $bill_obj->checkBillEncounter($creditDetails[encounterNr]);
    $input_User = $_SESSION['sess_login_username'];
    $trnsNo = $bill_obj->getTransNo(2);

    $admDate1 = new DateTime($creditDetails[admissionDate]);
    $admDate = $admDate1->format('Y-m-d');

    $disDate1 = new DateTime($creditDetails[dischargeDate]);
    $disDate = $disDate1->format('Y-m-d');

    $realDate1 = new DateTime($creditDetails[releaseDate]);
    $realDate = $realDate1->format('Y-m-d');

    $insuranceID = $insurance_obj->Get_insuranceID_from_pid($creditDetails[pid]);

    $sql = "INSERT INTO `care_ke_insurancecredits` (
            `creditNo`,`accno`,`inputDate`,`pid`,`names`,`admissionDate`,`dischargeDate`,`releaseDate`,
            `days`,`invoiceNo`,`invoiceAmount`,`creditAmount`,`balance`) 
          VALUES('$creditDetails[creditNo]','$creditDetails[accno]','$billDate','$creditDetails[pid]','$creditDetails[names]'
              ,'$admDate','$disDate','$realDate','$creditDetails[days]',
               '$creditDetails[invoiceNo]','$creditDetails[invoiceAmount]','$creditDetails[creditAmount]','$creditDetails[balance]')";

    if ($debug)
        echo $sql;

    if ($db->Execute($sql)) {
        $sql2 = "INSERT INTO care_ke_billing
                    (pid,encounter_nr,`IP-OP`,insurance_id, bill_date,bill_number,service_type,Description,
                    price,qty,total,input_user,notes, STATUS,batch_no,bill_time,rev_code,partcode,item_number)
                 VALUES('$creditDetails[pid]','$creditDetails[encounterNr]','1','$insuranceID','$billDate','$new_bill_number','Payment',
                    'INSURANCE CREDIT','$creditDetails[creditAmount]','1','$creditDetails[creditAmount]','$input_User','INSURANCE CREDIT',
                    'Paid','$new_bill_number','" . date('H:i:s') . "','IP','INSURANCE CREDIT','IP')";

        if ($debug)
            echo $sql2;
        $db->Execute($sql2);

        //if($creditDetails[isNHIF]=='on'){
        $sql3 = "insert into `care_ke_debtortrans`
                                (`transno`,`transtype`,`accno`, `pid`,`transdate`,`bill_number`,`amount`,`lastTransDate`,
                                `lasttransTime`,`settled`,encounter_nr,encounter_class_nr,reference,inputUser,claimNo)
                                values('$trnsNo','2','$creditDetails[accno]', '$creditDetails[pid]','" . $billDate . "','$new_bill_number',
                                '$creditDetails[creditAmount]','" . $billDate . "','" . date('H:i:s') . "',0,
                                '$creditDetails[encounterNr]','1','$creditDetails[creditNo]','$input_User','$creditDetails[creditNo]')";

        if ($debug)
            echo $sql3;
        $db->Execute($sql3);
        //}

        $newTransNo = ($trnsNo + 1);
        $sql4 = "update care_ke_transactionNos set transNo='$newTransNo' where typeid='2'";
        if ($debug)
            echo $sql4;
        if ($db->Execute($sql4)) {
            echo '{success:true,"msg":"Successfully saved Inssurance Credit"}';
        } else {
            echo '{failure:true,"msg":"Could not save Insurance Credit, Please check your values"}';
        }
    }
}

function getTotalBill($pid, $bill_number) {
    global $db;
    $debug = false;

    $sql = "SELECT sum(total) as total FROM care_ke_billing WHERE pid = '$pid' AND 
        service_type IN ('payment','payment adjustment','NHIF') and `ip-op`=1 and bill_number=$bill_number";
    if ($debug)
        echo $sql;

    $results = $db->Execute($sql);
    $row = $results->FetchRow();
    $totalPayment = $row[0];

    $sql2 = "SELECT sum(total) as total FROM care_ke_billing WHERE pid = '$pid' AND 
        service_type NOT IN ('payment','payment adjustment','NHIF') and `ip-op`=1 and bill_number=$bill_number";
    if ($debug)
        echo $sql2;

    $results2 = $db->Execute($sql2);
    $row2 = $results2->FetchRow();
    $totalBill = $row2[0];

    $billBalance = $totalBill - $totalPayment;

    echo "{'invoiceAmount':[{'amount':'$billBalance'}]}";
}

function getDebitNo() {
    global $db;
    $debug = false;

    $sql = "select max(batch_no) as debitNo from care_ke_billing where ledger='DB'";
    $results = $db->Execute($sql);
    $recCount = $results->RecordCount();
    if ($recCount > 0) {
        $row = $results->FetchRow();
        $debitNo = $row[0] + 1;
    } else {
        $debitNo = '1000';
    }

    echo "{'debits':[{'debitNo':'$debitNo'}]}";
}


function getCreditNo() {
    global $db;
    $debug = false;

    $sql = "select max(creditNo) as creditNo from care_ke_insurancecredits";
    $results = $db->Execute($sql);
    $recCount = $results->RecordCount();
    if ($recCount > 0) {
        $row = $results->FetchRow();
        $creditNo = $row[0] + 1;
    } else {
        $creditNo = '1000';
    }

    echo "{'credits':[{'creditNo':'$creditNo'}]}";
}

function getNhifCreditNo() {
    global $db;
    $debug = false;

    $sql = "select max(creditNo) as creditNo from care_ke_nhifcredits";
    $results = $db->Execute($sql);
    $recCount = $results->RecordCount();
    if ($recCount > 0) {
        $row = $results->FetchRow();
        $creditNo = $row[0] + 1;
    } else {
        $creditNo = '1000';
    }

    echo "{'credits':[{'creditNo':'$creditNo'}]}";
}

function getInsuranceCompanies() {
    global $db;
    $debug = false;

    $sql = "Select accno,name from care_ke_debtors";
    $result = $db->Execute($sql);
    $total=$result->RecordCount();

    echo '{
    "total":"' . $total . '","insurancecompanies":[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        $description = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[name]);

        echo '{"Accno":"' . $row[accno] . '","Description":"' . $description . '"}';

        $counter++;

        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']}';
}

function deleteMethod($ID, $table) {
    global $db;
    $debug = false;

    $sql = "Delete from $table where ID='$ID'";
    if ($debug) {
        echo $sql;
    }

    if ($db->Execute($sql)) {
        echo '{Success:true}';
    } else {
        echo '{Failure:true}';
    }
}

function checkFinaliseStatus($pid, $bill_obj, $billNumber) {
    global $db;
    $debug = false;
    $billnumber = $_REQUEST['billNumber'];

    $encNr = $bill_obj->getLastEncounterNo($pid);

    $sql = "SELECT DISTINCT e.pid,e.encounter_nr,e.bill_number, e.finalised FROM care_ke_billing e
            WHERE e.pid='$pid' and e.bill_number=$billnumber and e.`IP-OP`=1";

    if ($debug)
        echo $sql;

    if ($request = $db->Execute($sql)) {
        $row = $request->FetchRow();
        echo $row[finalised] . ',' . $row[pid] . ',' . $row[encounter_nr] . ',' . $row[bill_number];
    }
}

function updateReceiptItems($strData, $pid) {
    global $db;
    $debug = false;
    $UpdateRowsCount = $_POST[selectedCount];

    $error = 0;
    if ($UpdateRowsCount > 1) {
        foreach ($strData as $key => $value) {
            //echo "<br> Items are in group ". $key;
            $sql = "UPDATE care_ke_receipts SET ";
            foreach ($value as $k => $strVal) {
                //echo "<br> Items in second loop  $k and Value $strVal";
                if ($k == 'Sale_ID') {
                    $id = $strVal;
                }

                $sql .= $k . '="' . $strVal . '", ';
                foreach ($strVal as $x => $xval) {
                    //echo "<br> Items in third loop  $x and Value $xval";
                }
            }
            $sql = substr($sql, 0, -2) . " WHERE sale_id='$id'";

            if ($debug)
                echo $sql;

            if ($db->Execute($sql)) {
                $error = 0;
            } else {
                $error = 1;
            }
        }
    } else {
        $sql = "UPDATE CARE_KE_RECEIPTS SET ";
        $id = '';
        foreach ($strData as $key => $value) {
            $sql .= $key . '="' . $value . '", ';
            if ($key == 'Sale_ID') {
                $id = $value;
            }

            if ($key == 'Bill_Date') {
                $date1 = new DateTime($value);
                $value = $date1->format("Y-m-d");
            }
        }
        $sql = substr($sql, 0, -2) . " WHERE sale_id='$id'";
        if ($debug)
            echo $sql;
        if ($db->Execute($sql)) {
            $error = 0;
        } else {
            $error = 1;
        }
    }
    if ($error == 0) {
        $results = "{success: true,'Error':'Successfully saved Receipt'}";
//        getAllBills($pid,$bill_number);
    } else {
        $results = "{failure: true,'Error':'Cannot update Receipt Error No $error'}"; // Return the error message(s)
    }
    echo $results;
}

function updateBillItems($strData, $pid) {
    global $db;
    $debug = false;
    $UpdateRowsCount = $_POST[selectedCount];

    $error = 0;
    if ($UpdateRowsCount > 1) {
        foreach ($strData as $key => $value) {
            //echo "<br> Items are in group ". $key;
            $sql = "UPDATE care_ke_billing SET ";
            foreach ($value as $k => $strVal) {
                //echo "<br> Items in second loop  $k and Value $strVal";
                if ($k == 'ID') {
                    $id = $strVal;
                }
                
                if($k=="Bill_Date"){
                    $date1 = new DateTime($strVal);
                    $strVal = $date1->format("Y-m-d");
                }
                
                $sql .= $k . '="' . $strVal . '", ';
                foreach ($strVal as $x => $xval) {
                    //echo "<br> Items in third loop  $x and Value $xval";
                }
            }
            $sql = substr($sql, 0, -2) . " WHERE ID='$id'";

            if ($debug)
                echo $sql;

            if ($db->Execute($sql)) {
                $error = 0;
            } else {
                $error = 1;
            }
        }
    } else {
        $sql = "UPDATE CARE_KE_BILLING SET ";
        $id = '';
        foreach ($strData as $key => $value) {
            $sql .= $key . '="' . $value . '", ';
            if ($key == 'ID') {
                $id = $value;
            }
            if ($key == 'Bill_Date') {
                $date1 = new DateTime($value);
                $value = $date1->format("Y-m-d");
            }
        }
        $sql = substr($sql, 0, -2) . " WHERE ID='$id'";
        if ($debug)
            echo $sql;
        if ($db->Execute($sql)) {
            $error = 0;
        } else {
            $error = 1;
        }
    }
    if ($error == 0) {
        $results = "{success: true,'Error':'Successfully saved Bill'}";
//        getAllBills($pid,$bill_number);
    } else {
        $results = "{failure: true,'Error':'Cannot update bill Error No $error'}"; // Return the error message(s)
    }
    echo $results;
}

function deleteReceiptItem($pid) {
    global $db;
    $debug = false;

    $ID = chop($_REQUEST[ID], ',');

    $sql = "Delete from care_ke_receipts where sale_id in ($ID) and patient='$pid'";
    if ($debug)
        echo $sql;

    if ($db->Execute($sql)) {
        echo "{success:true,'Error':'Successfully Deleted Item'}";
    } else {
        echo "{failure:true,'Error':'Unable to Delete Item $sql'}";
    }
}

function deleteBillItem($pid) {
    global $db;
    $debug = false;

    $ID = chop($_REQUEST[ID], ',');

    $sql = "Delete from care_ke_billing where ID in ($ID) and pid='$pid'";
    if ($debug)
        echo $sql;

    if ($db->Execute($sql)) {
        echo "{success:true,'Error':'Successfully Deleted Item'}";
    } else {
        echo "{failure:true,'Error':'Unable to Delete Item $sql'}";
    }
}

function combineBills($pid) {
    global $db;
    $debug = false;

    $bill1 = $_REQUEST[bill1];
    $bill2 = $_REQUEST[bill2];
    $enc1 = $_REQUEST[enc1];
    $enc2 = $_REQUEST[enc2];

    $sql = "update care_ke_billing set bill_number='$bill1',encounter_nr='$enc1' "
            . "where bill_number='$bill2' and pid='$pid'";
    if ($debug)
        echo $sql;

    if ($db->Execute($sql)) {
        echo "{success:true}";
    } else {
        echo "{failure:true}";
    }
}

function getEncounter($enc_obj, $pid) {
    $encounterJson = $enc_obj->getCurrentEncounter($pid);

    echo $encounterJson;
}

function saveDebits($enc_obj, $insurance_obj) {
    global $db;
    $debug = false;

    $billDate = $_REQUEST[debitDate];

    $date1 = new DateTime($_POST['debitDate']);
    $debitDate = $date1->format("Y-m-d");

    $inputUser = $_SESSION['sess_login_username'];
     $pid=$_REQUEST['pid'];
     
    $debitData = $_REQUEST[gridData];
    $data = json_decode($debitData, true);

    $encounterNr = $_REQUEST['encounterNo'];
    $billNumber = $_REQUEST['billNumber'];
    $debitNo=$_REQUEST['debitNo'];
       
    $insuranceID=$insurance_obj->Get_insuranceID_from_pid($pid);

    $error=0;
    foreach ($data as $row) {
        $partcode = $row['PartCode'];
        $description = $row['Description'];
        $serviceType = $row['Category'];
        $price = $row['Price'];
        $qty = $row['Qty'];
        $Total = $row['Price']*$row['Qty'];

        $sql = "INSERT care_ke_billing(encounter_nr,pid,bill_number,bill_date,service_type,item_number,
            Description,price,qty,total,`status`,`IP-OP`,prescribe_date,weberpSync,partcode,
            bill_time,ledger,insurance_id,batch_no,input_user,rev_code,debtorUpdate)
            values('" . $encounterNr . "','" . $pid . "','" . $billNumber . "','" . $debitDate
                . "','" . $serviceType . "','" . $partcode . "','" . $description . "','" . $price
                . "','" . $qty . "','" . $Total . "','billed','1','" . $debitDate
                . "',0,'" . $partcode . "','" . date('H:i:s')
                . "','DB','".$insuranceID."','".$debitNo."','" . $inputUser . "','" . $partcode . "',0)";
        if ($debug) echo $sql;

        if ($db->Execute($sql)) {
           $error=0;
        } else {
           $error=1;
        }
    }
    
        if ($error==0) {
            echo "{success:true}";
        } else {
            echo "{failure:true}";
        }
}

function getItemsList($searchParam,$start,$limit) {
    global $db;
    $debug = false;

    $sql = "SELECT d.`partcode`,d.`item_description`,c.`item_Cat`,d.`unit_price` FROM care_tz_drugsandservices d 
            LEFT JOIN care_tz_itemscat c ON d.`category`=c.`catID` where d.purchasing_class not in('drug_list','medical-supplies')";

    if($searchParam<>''){
        $sql=$sql." and partcode='$searchParam' OR d.item_description like '%$searchParam%'";
    }

    $sql=$sql." limit $start,$limit";

    if ($debug)
        echo $sql;

    $result = $db->Execute($sql);
    $total = $result->RecordCount();
    echo '{
    "total":"' . $total . '","items":[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        $description = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[item_description]);

        echo '{"PartCode":"' . $row[partcode] . '","Description":"' . $description
        . '","Category":"' . $row[item_Cat - OP] . '","Price":"' . $row[unit_price] . '"}';

        $counter++;

        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']}';
}

function getReceipts($pid, $refNo) {
//    var_dump($_POST);
    $json_data = $_POST['updateReceipts']; // file_get_contents('php://input');
    $strData = json_decode($json_data);

    if (!empty($strData)) {
        updateReceiptItems($strData, $pid);
    } else {
        getAllReceipts($pid, $refNo);
    }
}

function getAllReceipts($pid, $refNo) {
    global $db;
    $debug = false;

    $pid2 = ($pid <> "" ? $pid = $pid : $pid = '1000');

    $sql = "SELECT `sale_id`,`cash_point`,`Shift_no`, `username`,`type`,`ref_no`,`currdate`,`payer`,`patient`,`towards`,
            `name`, `rev_code`,`rev_desc`,`proc_code`,`Prec_desc`,`proc_qty`,`pay_mode`,`amount`,`total`,`input_Time`
          FROM `care_ke_receipts` where patient='$pid2'";

    if (isset($refNo) && $refNo <> '') {
        $sql = $sql . " and ref_no='$refNo'";
    }

    if ($debug)
        echo $sql;

    $result = $db->Execute($sql);
    $total = $result->RecordCount();
    echo '{"receipts":[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        $rDate = new DateTime($row[currdate]);
        $receiptDate = $rDate->format('Y-m-d');

        echo '{"Sale_ID":"' . $row[sale_id] . '","Pid":"' . $row[patient] . '","CashPoint":"' . $row[cash_point] . '","ShiftNo":"' . $row[Shift_no] . '","Cashier":"' . $row[username]
        . '","ReceiptNo":"' . $row[ref_no] . '","ReceiptDate":"' . $receiptDate . '","Customer":"' . $row[name] . '","PayMode":"' . $row[pay_mode]
        . '","Towards":"' . $row[towards] . '","PartCode":"' . $row[proc_code] . '","Description":"' . $row[Prec_desc] . '","ServiceType":"' . $row[rev_desc]
        . '","Qty":"' . $row[proc_qty] . '","Amount":"' . number_format($row[amount], 2) . '","Total":"' . number_format($row[total], 2) . '","InputTime":"' . $row[input_Time] . '","Payer":"' . $row[payer] . '"}';

        $counter++;

        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']}';
}

function getBills($pid, $bill_number) {
//    var_dump($_POST);
    $json_data = $_POST[updatedBills]; // file_get_contents('php://input');
    $strData = json_decode($json_data);

    if (!empty($strData)) {
        updateBillItems($strData, $pid);
    } else {
        getAllBills($pid, $bill_number);
    }
}

function getAllBills($pid, $bill_number) {
    global $db;
    $debug = false;

    $pid2 = ($pid <> "" ? $pid = $pid : $pid = '1000');

    $sql = "SELECT ID,`pid`,`encounter_nr`,`insurance_id`,`IP-OP` as IPOP,`bill_date`,`bill_time`, `bill_number`, `batch_no`,`service_type`,
          `partcode`,`Description`,`price`,`qty`,`total`,`input_user`,
          `notes` FROM `care_ke_billing` where pid='$pid2'";

    if (isset($bill_number) && $bill_number <> '') {
        $sql = $sql . " and bill_number='$bill_number'";
    }

    if ($debug)
        echo $sql;

    $result = $db->Execute($sql);
    $total = $result->RecordCount();
    echo '{"bills":[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        echo '{"ID":"' . $row[ID] . '","Pid":"' . $row[pid] . '","Encounter_Nr":"' . $row[encounter_nr] . '","IP-OP":"' . $row[IPOP] . '","Bill_Date":"' . $row[bill_date]
        . '","Bill_Time":"' . $row[bill_time] . '","Bill_Number":"' . $row[bill_number] . '","Service_Type":"' . $row[service_type]
        . '","PartCode":"' . $row[partcode] . '","Description":"' . $row[Description] . '","Price":"' . number_format($row[price], 2) . '","Qty":"' . $row[qty]
        . '","Total":"' . number_format($row[total], 2) . '","InputUser":"' . $row[input_user] . '"}';

        $counter++;

        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']}';
}

function deleteClaim($claimNo, $pid, $bill_number) {
    global $db;


    $sql3 = "DELETE FROM CARE_KE_debtortrans WHERE PID='$pid' and bill_number='$bill_number' and accno in('NHIF','NHIF2')";
    if ($db->Execute($sql3)) {
        $sql2 = "DELETE FROM CARE_KE_billing WHERE PID='$pid' and batch_no='$claimNo' and service_type='NHIF'";
        if ($db->Execute($sql2)) {
            $sql = "DELETE FROM CARE_KE_NHIFCREDITS WHERE admno='$pid' and creditno='$claimNo'";
            if ($db->Execute($sql)) {
                echo "Successfully Removed NHIF Credit for PID $pid";
            } else {
                echo 'Failure Delete Credit:' . $sql;
            }
        } else {
            echo 'Failure Delete bill:' . $sql2;
        }
    } else {
        echo 'Failure Delete Credit:' . $sql3;
    }
}

function closeBill($insurance_obj, $bill_obj) {
    // global $db;
    $pid = $_REQUEST[pid];
    $encounterNo = $_REQUEST[enc_nr];
    $insuCompanyID = $insurance_obj->GetCompanyFromPID2($pid);
    $bill_obj->updateDebtorsTrans($pid, $insuCompanyID, $encounterNo);
}

function getNHIFCredits($dt1, $dt2) {
    global $db;
    $debug = false;

    $sql = "SELECT b.creditNo,b.inputDate,b.admno,b.Names,b.admDate,b.disDate,b.wrdDays,b.nhifNo,b.nhifDebtorNo,
	b.debtorDesc, b.invAmount,b.totalCredit,b.balance,n.bill_number,b.inputUser
	FROM care_ke_nhifcredits b left join care_ke_billing n on b.creditno=n.batch_no
    WHERE n.rev_code='NHIF' ";

    if ($dt1<>'' && $dt2<>'') {
        $date1 = new DateTime(date($dt1));
        $dt1 = $date1->format("Y-m-d");

        $date2 = new DateTime(date($dt2));
        $dt2 = $date2->format("Y-m-d");

        $sql = $sql . "and b.inputDate between '$dt1' and '$dt2'";
    }
    $result = $db->Execute($sql);
    $total=$result->RecordCount();
    if ($debug)
        echo $sql;

    $counter=0;

    echo '{"total":'.$total.',"nhifcredits":[';
    while ($row = $result->FetchRow($result)) {
        echo '{"CreditNo":"'.$row['CreditNo'] . '","PID:"'. $row['admno'] . '","Names":"'. $row['Name']
            . '","Billnumber":"'. $row['bill_number'] .'","InputDate":"'.$row['inputDate'] .'","AdmissionDate":"'.$row['admDate']
            . '","DischargeDate":"'.$row['disDate'] .'","ReleaseDate":"'.$row['releasedate'] . '","WardDays":"'. $row['wrddays']
            . '","NhifNo":"'.$row['nhifNo'] .'","NhifDebtorNo":"'.$row['nhifDebtorNo'] . '","TotalCredit":"'. $row['totalCredit']
            . '","InvoiceAmount":"' . $row['invAmount'] . '","Balance":"' . $row['balance'] .'"}';

        if($total>$counter){
            $counter++;
        }

    }

    echo "]}";
}

function getItemDescription($desc) {
    global $db;
    if ($desc) {

        $sql = "select purchasing_class,item_description, unit_price from care_tz_drugsandservices WHERE partcode='$desc'";
        $result = $db->Execute($sql);
        if (!$result) {
            echo 'Could not run query: ';
            exit;
        }

        $row = $result->FetchRow();

        echo $row[0] . "," . $row[1] . "," . $row[2]; // 42
        //echo "Laboratory $rowID";
    } else {
        echo "....";
    }
}


function getAdmissionDetails($pid) {
    global $db;
    if ($pid) {
        $sql = "SELECT CONCAT(b.name_first,' ',b.name_2,' ',b.name_last) AS names,max(a.encounter_nr) as encounter_nr,a.encounter_class_nr,
        a.current_ward_nr,w.description,a.finalised,a,encounter_date,a.discharge_date,b.citizenship,b.phone_1_nr
        from care_person b  left join care_encounter a on a.pid=b.pid 
        inner join care_ward w on w.nr=a.current_ward_nr WHERE b.pid='$pid' and a.encounter_class_nr=1";
        // $result = mysql_query("SELECT name,next_receipt_no FROM care_ke_cashpoints WHERE pcode='$desc2'");
        $result = $db->Execute($sql);
        if (!$result) {
            echo 'Could not run query: ' . mysql_error();
            exit;
        }
        $row = $result->FetchRow();

        $total = $result->RecordCount();
        echo '{
    "total":"' . $total . '","billnumbers":[';
        $counter = 0;
        while ($row = $result->FetchRow()) {
            echo '{"BillNumbers":"' . $row[bill_number] . '"}';

            $counter++;

            if ($counter <> $total) {
                echo ",";
            }
        }
        echo ']}';
    } else {
        echo "---";
    }
}

//getBillNumbers($pid2);
function getBillNumbers($pid) {
    global $db;
    $debug = false;

    $pid2 = ($pid <> "" ? $pid = $pid : $pid = '1000');

    $sql = "select DISTINCT bill_number from care_ke_billing where pid=$pid2
    order by bill_date desc";

    if ($debug)
        echo $sql;

    $result = $db->Execute($sql);
    if (!$result) {
        echo 'Could not run query: ' . $sql;
        exit;
    }
    $total = $result->RecordCount();
    echo '{
    "total":"' . $total . '","billnumbers":[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        echo '{"BillNumbers":"' . $row[bill_number] . '"}';

        $counter++;

        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']}';
}

function getEncounterNumbers($pid) {
    global $db;

    $pid2 = ($pid <> "" ? $pid = $pid : $pid = '1000');

    $sql = "select DISTINCT encounter_nr from care_ke_billing where pid=$pid2
    and `IP-OP`=1 order by bill_date desc";

    $result = $db->Execute($sql);
    if (!$result) {
        echo 'Could not run query: ' . $sql;
        exit;
    }
    $total = $result->RecordCount();
    echo '{
    "total":"' . $total . '","encounters":[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        echo '{"EncounterNumbers":"' . $row[encounter_nr] . '"}';

        $counter++;

        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']}';
}

function finalizeInvoice($db, $pid, $billNumber, $fdate) {
    global $db, $root_path;
    require_once($root_path . 'include/care_api_classes/class_tz_insurance.php');
    require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
    $insurance_obj = new Insurance_tz;
    $bill_obj = new Bill;
    $debug = false;
    $finalResults='';

    if ($billNumber) {
        $sql = "SELECT distinct b.pid,b.bill_number,b.finalised,b.encounter_nr FROM care_ke_billing b LEFT JOIN care_encounter e 
                ON b.pid=e.pid WHERE b.pid='$pid' AND b.`ip-op`=1 and bill_number='$billNumber' AND e.is_discharged=1";

        if ($results = $db->Execute($sql)) {
//           echo 'success success';

            if ($debug)
                echo $sql;
            while ($rows = $results->FetchRow()) {
                $bill_number = $rows[1];
                $enc_nr = $rows[encounter_nr];
                $stat = $rows[2];
                if ($stat <> "1") {
                    $sql2 = "SELECT sum(total) as total FROM care_ke_billing WHERE pid = '$pid' and `IP-OP`=1 
            and service_type no in('payment','nhif') and bill_number=$billNumber";
                    $result2 = $db->Execute($sql2);
                    $row2 = $result2->FetchRow();
                    if ($debug)
                        echo $sql2;
                    $finalResults = $finalResults . 'bill=' . $row2[0] . '<br>';

                    $sql3 = "SELECT sum(total) as total FROM care_ke_billing WHERE pid = '$pid' and `IP-OP`=1 
                        and service_type in ('Payment','NHIF') and bill_number=$billNumber";
                    if ($debug)
                        echo $sql3;
                    $result2 = $db->Execute($sql3);
                    $row3 = $result2->FetchRow();
                    $finalResults = $finalResults . 'paid=' . $row3[0] . '<br>';

                    $balance = intval($row2[0] - $row3[0]);
                    $finalResults = $finalResults . "balance=" . $balance;

                    $sql4 = "select insurance_id from care_person where pid='$pid'";
                    $result4 = $db->Execute($sql4);
                    $row4 = $result4->FetchRow();

                    if ($balance == 0 || $row4[0] > 0) {

                        $sql = "Update care_ke_billing set `status`='Finalized',finalised='1' where pid='$pid' and 
                            bill_number='$bill_number'";
                        if ($debug)
                            echo $sql;
                        if ($db->Execute($sql)) {
//                            $IS_PATIENT_INSURED = $insurance_obj->is_patient_insured($pid);
//                            if ($IS_PATIENT_INSURED) {
//                                $insuCompanyID = $insurance_obj->GetCompanyFromPID2($pid);
//                                $bill_obj->updateDebtorsTrans($pid, $insuCompanyID, $enc_nr);
//                            }

                            $finalResults = $finalResults . "<div class='myMessage'>Invoice number  $bill_number finalized succefully</div>";
                            $finalResults = $finalResults . "<div> <button id='print' onclick=invoicePdf('" . $pid . "')>Print Finalized Invoice</button></div>";
                        }
                    } else {
                        $finalResults = $finalResults . "<div class='myMessage'>The patient has a pending balance of Ksh $balance</div>";
                    }
//                } else if ($balance < 0) {
//                    echo "<div class='myMessage'>The patient has paid in Excess of $balance</div>";
//                }
                } else {
                    $finalResults = $finalResults . "<div class='myMessage'>Invoice number  $bill_number is already finalized</div>";
                }
            }
        }
    } else {
        $finalResults = $finalResults . "<div class='myMessage'>Please Select Bill Numnber to Finalize $billNumber</div>";
    }



    echo $finalResults;
}

?>
