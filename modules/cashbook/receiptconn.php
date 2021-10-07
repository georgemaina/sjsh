<?php

$debug = false;
($debug) ? $db->debug = FALSE : $db->debug = FALSE;
$bill_obj = new Bill;
$pid = $_POST[pid];
$pname = $_POST[pname];
$Cash_Point = $_POST[cashPoint];
//$receiptNo = $_POST[receiptNo];
$Pay_mode = $_POST[paymode];

$pdate1 = $_POST[strDate1];
$date1 = new DateTime(date($pdate1));
$pdate = $date1->format("Y-m-d");

$ptime = date("H:i:s");
//$gl_acc = $_POST[gl_acc];
$gl_desc = $_POST[gl_Desc];
$cheque_no = $_POST[chequeNo];
$payee = $_POST[payee];
$toward = $_POST[toward];
$total = $_POST[total];
$drawerBank = $_POST[drawerBank];
$period = 5;
$shiftNo = $_POST[shiftNo];
$proc_qty = 0;
$input_User = $_SESSION['sess_login_username'];

$total = $_POST[total];

$receiptNo=$bill_obj->getNextReceiptNo($Cash_Point);
$gl_acc=$bill_obj->getPayModeGl($Cash_Point,$Pay_mode);

//echo var_dump($_POST);
//echo ' rows '.$_POST[gridbox_rowsadded];


if (!$shiftNo) {
    $sql = 'select current_shift from care_ke_cashpoints where cashier="' . $input_User . '" and active=1 AND pcode="' . $Cash_Point . '"';
    $result2 = $db->Execute($sql);
    $row = $result2->FetchRow();
    $shiftNo = $row[0];
}

function insertData($db, $rowid, $pid, $pname, $Cash_Point, $receiptNo, $Pay_mode, $pdate, $ptime, $gl_acc, $gl_desc, $cheque_no,
        $payee, $toward, $total, $input_User, $shiftNo, $drawerBank, $period, $proc_qty) {
    
    $bill_obj=new Bill();
    $debug = false;
    //global $db;
    $ledger = $_POST["gridbox_" . $rowid . "_0"];
    $ledger_code = $_POST["gridbox_" . $rowid . "_1"];
    $ledger_desc = $_POST["gridbox_" . $rowid . "_2"];
    $amount = $_POST["gridbox_" . $rowid . "_3"];
    $bill_number=$receiptNo;
    $mpesa=$_POST['mpesa'];
    $mpesaRef=$_POST['mpesaref'];
    $visa=$_POST['visa'];
    $cash=round($_POST['cash']);
    $bal=$_POST['bal'];

    $encounter_nr = $_POST[encounter_nr];
    if ($ledger == "IP") {
        $levDesc = "BILL PAYMENT";
     } else if ($ledger == "DB") {
        $levDesc = "DEBTORS ACCOUNT";
     } else if ($ledger == "GL") {
        $levDesc = $pname;
    }

    $csql = "INSERT INTO `care_ke_receipts`
            (`cash_point`, `ref_no`, `pay_mode`,`type`,`currdate`,`payer`, `patient`,`name`, `gl_acc`,
             `cheque_no`,`rev_code`,`rev_desc`, `proc_qty`,
             `amount`, `total`,`input_date`,`input_time`,`period`,`Shift_No`,`username`,weberp_syncd,
             drawer_bank,towards,ledger,`proc_code`, `Prec_desc`,mpesa,visa,cash,balance,mpesaref) VALUES
            ('" . $Cash_Point . "', '$receiptNo','$Pay_mode','RC','$pdate','$ledger_desc','$pid',
             '$pname','$gl_acc','$cheque_no','$ledger','$levDesc',
             '$proc_qty', '$amount', '$total','$pdate','$ptime','$period','$shiftNo','$input_User','0',
             '$drawerBank','$toward','$ledger','$ledger','$toward','$mpesa','$visa','$cash','$bal','$mpesaRef')";

    if ($debug)
        echo $csql;
    $result2 = $db->Execute($csql);

    if ($ledger == "DB") {
        $bill_obj->updateDebtorsReceipts($Cash_Point, $receiptNo, $shiftNo, $input_User, $cheque_no, $total,$pid,$pdate);
    }

    if($bill_obj->checkIncomeTrans('receipt')){
//        $glCode=$bill_obj->getItemGL($rev_code);
        $bill_obj->updateIncomeTrans($ledger_code,$amount,date('Y-m-d'),$receiptNo,'+','receipt');
    }


//    if ($ledger == 'IP') {
//        updateBill($db, $encounter_nr, $Cash_Point, $receiptNo, $Pay_mode, $payee, $pid, $pdate, $input_User,
//            $bill_number, $ledger, $ledger_code, $ledger_desc, $amount);
//    }
}

if (strstr($_POST[gridbox_rowsadded], ",")) {
    $added_rows = $_POST[gridbox_rowsadded];
    $arr_rows = explode(",", $added_rows);
    for ($i = 0; $i < count($arr_rows); $i++) {
        insertData($db, $arr_rows[$i], $pid, $pname, $Cash_Point, $receiptNo, $Pay_mode, $pdate, $ptime, $gl_acc, $gl_desc, $cheque_no, $payee, $toward, $total, $input_User, $shiftNo, $drawerBank, $period, $proc_qty);

        if ($_POST["gridbox_" . $arr_rows[$i] . "_0"] == 'IP') {
            $encounter_nr = $bill_obj->getLastEncounterNo($pid);
            $new_bill_number = $bill_obj->checkBillEncounter($encounter_nr);
            updateBalances($db, $pid, $encounter_nr, $Cash_Point, $receiptNo, $Pay_mode, $pdate, $payee, $pid, $pdate, $input_User, $new_bill_number, $arr_rows[$i], $receiptNo);
        }

//             if($_POST["gridbox_".$arr_rows[$i]."_0"]=='DB'){  
//                 updatedDebtors($db, $Cash_Point, $receiptNo, $Pay_mode, $payee, $pid, $pdate, $input_User, $cheque_no,$total);
//             } 

        nextReceiptNo($db, $receiptNo, $Cash_Point);

    }
} else {
    insertData($db, $_POST[gridbox_rowsadded], $pid, $pname, $Cash_Point, $receiptNo, $Pay_mode, $pdate, $ptime, $gl_acc, $gl_desc, $cheque_no, $payee, $toward, $total, $input_User, $shiftNo, $drawerBank, $period, $proc_qty);

    $rev = $_POST[gridbox_rowsadded];
    if ($_POST["gridbox_" . $rev . "_0"] == 'IP') {
        $encounter_nr = $bill_obj->getLastEncounterNo($pid);
        $new_bill_number = $bill_obj->checkBillEncounter($encounter_nr);
        updateBalances($db, $pid, $encounter_nr, $Cash_Point, $receiptNo, $Pay_mode, $pdate, $payee, $pid, $pdate, $input_User, $new_bill_number, $rev, $receiptNo);
    }


    nextReceiptNo($db, $receiptNo, $Cash_Point);
       
}
//$rev=$_POST[gridbox_rowsadded];
//echo 'current'.$_POST["gridbox_".$rev."_0"];
//nextChequeNo($db,$cheque_no,$Cash_Point,$Pay_mode);


updateCashErp($db, $pid, $receiptNo, $Cash_Point, $shiftNo);

function updateBalances($db, $pid, $encounter_nr, $cash_point, $ref_no, $paymode, $mydate, $payer, $patientno,
                        $inputDt, $username, $new_bill_number, $rowid, $batch_no) {

    $debug = false;
    $ledger = $_POST["gridbox_" . $rowid . "_0"];
    $ledger_code = $_POST["gridbox_" . $rowid . "_1"];
    $ledger_desc = $_POST["gridbox_" . $rowid . "_2"];
    $amount = $_POST["gridbox_" . $rowid . "_3"];

//    $new_bill_number=$bill_obj->checkBillEncounter($encounter_nr);
    if ($encounter_class_nr == 1) {
        $revcode = 'IP';
    } else {
        $revcode = $_POST["gridbox_" . $rowid . "_0"];
    }

    $sql2 = "INSERT INTO care_ke_billing
                (
                    pid,encounter_nr,`IP-OP`, bill_date,bill_number,service_type,Description,
                    price,qty,total,input_user,notes, STATUS,batch_no,bill_time,rev_code,
                    partcode,item_number)
                VALUES('$ledger_code','$encounter_nr','1','$inputDt','$new_bill_number','Payment',
    'Bill Payment','$amount','1','$amount','$username','Debtor Payment','Paid','$batch_no','" . date('H:i:s') . "',
            '$revcode','$revcode','$revcode')";

    if ($debug)
        echo $sql2;

    $result = $db->Execute($sql2);
}

function nextReceiptNo($db, $nextReceipt, $Cash_Point) {
    $debug = TRUE;
    $nextRctpNo = intval(substr($nextReceipt, 1) + 1);

    $sql = "update care_ke_cashpoints set next_receipt_no='$nextRctpNo' where pcode='$Cash_Point'";
    if ($debug)
        echo $sql;
    $result = $db->Execute($sql);

    if ($result)
        echo " ";
    else
        echo "Error in nxtReceiptNo err desc=" . mysql_error();
}

function updateCashErp($db, $pn, $ref_no, $cash_point, $shift_no) {
    //global $db, $root_path;
    $debug = FALSE;
    if ($debug)
        echo "<b>class_tz_billing::updateCashErp()</b><br>";
    if ($debug)
        echo "encounter no: $pn <br>";
    ($debug) ? $db->debug = TRUE : $db->debug = FALSE;
    $sql = "SELECT patient,`name`,currdate,SUM(total) AS total,ref_no,input_date,rev_code as partcode,
            ledger,gl_acc,proc_code,prec_desc,payer,towards FROM care_ke_receipts
                WHERE  ref_no='$ref_no' and weberp_syncd='0' 
                and cash_point='$cash_point' and shift_no='$shift_no' GROUP BY patient";
    $result = $db->Execute($sql);



    if ($weberp_obj = new_weberp()) {
        //$arr=Array();
        while ($row = $result->FetchRow()) {
            //$weberp_obj = new_weberp();
            if ($row[ledger] == 'GL') {
                if (!$weberp_obj->transfer_trans_to_webERP_asGLReceipt($row)) {
                    $sql4 = "Update care_ke_billing set
                    status='paid' where status='pending' and pid='" . $row[0] . "'";
                    $db->Execute($sql4);
                    $sql2 = "Update care_ke_receipts set
                    weberp_syncd='1' where weberp_syncd='0'";
                    $db->Execute($sql2);
                } else {
                    echo 'failed';
                }
                destroy_weberp($weberp_obj);
            } else if ($row[ledger] == 'DB') {
                if (!$weberp_obj->transfer_trans_to_webERP_asDBReceipt($row)) {
                    $sql4 = "Update care_ke_billing set
                    status='paid' where status='pending' and pid='" . $row[0] . "'";
                    $db->Execute($sql4);
                    $sql2 = "Update care_ke_receipts set
                    weberp_syncd='1' where weberp_syncd='0'";
                    $db->Execute($sql2);

//                  updateDebtorsTrans($db,$row[total],$row[patient],$row[refNo]);
                } else {
                    echo 'failed';
                }
//                destroy_weberp($weberp_obj);
            } else if ($row[ledger] == 'IP') {
                if (!$weberp_obj->transfer_trans_to_webERP_asIPReceipt($row)) {
                    $sql4 = "Update care_ke_billing set
                    status='paid' where status='pending' and pid='" . $row[0] . "'";
                    $db->Execute($sql4);
                    $sql2 = "Update care_ke_receipts set
                    weberp_syncd='1' where weberp_syncd='0'";
                    $db->Execute($sql2);
                } else {
                    echo 'failed';
                }
                destroy_weberp($weberp_obj);
            }
        }
    } else {
        echo 'could not create object: debug level ';
    }
}
?>



