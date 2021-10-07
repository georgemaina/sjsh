<?php
$debug=false;;

($debug) ? $db->debug=FALSE : $db->debug=FALSE;
$bill_obj = new Bill;
$cash_point=$_POST[cash_point];
$ref_no=$_POST[receiptNo];
$paymode=$_POST[paymode];
$paymode_desc=$_POST[paymode_desc];
$mdate=$_POST[calInput];
$payer=$_POST[payer];
$patientno=$_POST[patientId];
$encounter_nr=$_POST[encounter_nr];
$encounter_class_nr=$_POST[encounter_class_nr];
$pname=$_POST[patient_name];
$inputDt=date("Y-m-d");
$inputTime=date("H:i:s");
$period='2009';
$name = $_SESSION['sess_login_username'];
$username=$name;
$total=$_POST[total];
$gl_acc=$_POST[gl_acc];

if(!$shiftNo){
    $sql='select current_shift from care_ke_cashpoints where cashier="'.$name.'" and active=1 AND pcode="'.$cash_point.'"';
    $result2=$db->Execute($sql);
    $row=$result2->FetchRow();
    $shiftNo=$row[0];
}

$new_bill_number=$bill_obj->checkBillEncounter($encounter_nr);

if($paymode=='CAS') {
    $cheque_no='0';
}ELSE {
    $cheque_no=$_POST[cheq_no];
}

function insertData($db,$rowid,$cash_point,$ref_no,$paymode,$mydate,$payer,$patientno,
    $pname,$gl_acc,$cheque_no,$inputDt,$inputTime,$period,$shiftNo,$username,$bill_number) {
      $debug=false;
    //global $db;
    $rev_code=$_POST["gridbox_".$rowid."_0"];
    $rev_Desc=$_POST["gridbox_".$rowid."_1"];
    $Proc_code=$_POST["gridbox_".$rowid."_2"];
    $Prec_Desc=$_POST["gridbox_".$rowid."_3"];
    $amount=$_POST["gridbox_".$rowid."_4"];
    $proc_qty=$_POST["gridbox_".$rowid."_5"];
    $total=$_POST["gridbox_".$rowid."_6"];
    $encounter_nr=$_POST[encounter_nr];

    $csql="INSERT INTO `care_ke_receipts`
            (`cash_point`, `ref_no`, `pay_mode`,`type`,`currdate`,`payer`, `patient`,`name`, `gl_acc`,
             `cheque_no`,`rev_code`,`rev_desc`,`proc_code`, `Prec_desc`, `proc_qty`,
             `amount`, `total`,`input_date`,`input_time`,`period`,`Shift_No`,`username`,weberp_syncd,bill_number) VALUES
            ('".$cash_point."', '$ref_no','$paymode','RC','".date('Y-m-d')."','$payer','$patientno',
             '$pname','$gl_acc','$cheque_no','$rev_code','$rev_Desc','$Proc_code',
            '$Prec_Desc','$proc_qty', -'$amount', -'$total','$inputDt','$inputTime','$period','$shiftNo','$username','0','$bill_number')";

    if($debug) echo $csql;
    $result2=$db->Execute($csql);
}
////
//echo var_dump($_POST);
//echo ' rows '.$_POST[gridbox_rowsadded];

    if(strstr($_POST[gridbox_rowsadded],",")) {
        $added_rows=$_POST[gridbox_rowsadded];
        $arr_rows= explode(",", $added_rows);
        for($i=0;$i<count($arr_rows);$i++) {
//            echo "$arr_rows[$i]<br>";

            insertData($db,$arr_rows[$i],$cash_point,$ref_no,$paymode,$mydate,$payer,$patientno,
            $pname,$gl_acc,$cheque_no,$inputDt,$inputTime,$period,$shiftNo,$username,$row3[2]);

//            updateBalances($db,$encounter_nr,$cash_point,$ref_no,$paymode,$mydate,$payer,$patientno,
//   $inputDt,$username,$new_bill_number,$encounter_class_nr,$arr_rows[$i],$ref_no);
        }
    }else {
            insertData($db,$_POST[gridbox_rowsadded],$cash_point,$ref_no,$paymode,$mydate,$payer,$patientno,
            $pname,$gl_acc,$cheque_no,$inputDt,$inputTime,$period,$shiftNo,$username,$row3[2]);

//            updateBalances($db,$encounter_nr,$cash_point,$ref_no,$paymode,$mydate,$payer,$patientno,
//   $inputDt,$username,$new_bill_number,$encounter_class_nr,$_POST[gridbox_rowsadded],$ref_no);
            
    }


//updateCashErp($db,$patientno,$ref_no);

function updateLabPmt($db,$pid) {
$debug=false;
    $sql2="SELECT a.batch_nr,a.encounter_nr, b.pid,a.bill_number, a.bill_status
        FROM care_test_request_chemlabor a
        INNER JOIN care_encounter b ON b.encounter_nr=a.encounter_nr
         where b.pid='".$pid."'";
    if($debug) echo $csql2;
    $result=$db->Execute($sql2);

    // if($result){
    if ($row=$result->FetchRow()) {
        $batch=$row['batch_nr'];
        $bill_no=$row['bill_number'];

        $sql="Update care_test_request_chemlabor set bill_status='done' where batch_nr='$batch'";
        if($debug) echo $sql;
        $result=$db->Execute($sql);
       
    } else {
        if($debug) echo $sql;
    }

}

function updateBill($db,$rowid,$patientno) {
    $debug=false;
    $rev_code=$_POST["gridbox_".$rowid."_0"];
    $rev_Desc=$_POST["gridbox_".$rowid."_1"];
    $Proc_code=$_POST["gridbox_".$rowid."_2"];
    $Prec_Desc=$_POST["gridbox_".$rowid."_3"];
    $amount=$_POST["gridbox_".$rowid."_4"];
    $proc_qty=$_POST["gridbox_".$rowid."_5"];
    $total=$_POST["gridbox_".$rowid."_6"];

    $sql2="INSERT INTO care_ke_billing_archive
                (
                    pid,
                    encounter_nr,
                    `IP-OP`,
                    insurance_id,
                    bill_date,
                    bill_number,
                    batch_no,
                    rev_code,
                    service_type,
                    Description,price,qty,total,prescribe_date,times_per_day,
                    days,
                    input_user,
                    notes,
                    partcode,
                    item_number,
                    dosage,
                    STATUS,
                    billed,
                    service_desc)
           SELECT
                    pid,
                    encounter_nr,
                    `IP-OP`,
                    insurance_id,
                    bill_date,
                    bill_number,
                    batch_no,
                    rev_code,
                    service_type,
                    Description,
                    price,
                    qty,
                    total,
                    prescribe_date,
                    times_per_day,
                    days,
                    input_user,
                    notes,
                    partcode,
                    item_number,
                    dosage,
                    STATUS,
                    billed,
                    service_desc
                FROM care_ke_billing
                WHERE `pid`='$patientno' AND partcode='".$Proc_code."'";
    if($debug) echo $sql2;
    
    $result=$db->Execute($sql2);

    if($result) {

        $sql="DELETE FROM care_ke_billing where `pid`='".$patientno."' and partcode='".$Proc_code."'";
        $result2=$db->Execute($sql);
        if($debug) echo $sql;
        if(!$result2){
           echo $patientno."bill not deleted";
        }
    } else {
        echo "Error in updateBill, err desc=".mysql_error();
    }
}

function updateBalances($db,$encounter_nr,$cash_point,$ref_no,$paymode,$mydate,$payer,$patientno,
   $inputDt,$username,$bill_number,$encounter_class_nr,$rowid,$batch_no){
      $debug=false;
    $rev_code=$_POST["gridbox_".$rowid."_0"];
    $rev_Desc=$_POST["gridbox_".$rowid."_1"];
    $Proc_code=$_POST["gridbox_".$rowid."_2"];
    $Prec_Desc=$_POST["gridbox_".$rowid."_3"];
    $amount=$_POST["gridbox_".$rowid."_4"];
    $proc_qty=$_POST["gridbox_".$rowid."_5"];
    $total=$_POST["gridbox_".$rowid."_6"];

    $sql2="INSERT INTO care_ke_billing
                (
                    pid,encounter_nr,`IP-OP`, bill_date,bill_number,service_type,rev_code,Description,
                    price,qty,total,input_user,notes, STATUS,batch_no,bill_time,partcode,item_number,weberpsync)
                VALUES('$patientno','$encounter_nr','$encounter_class_nr','$inputDt','$bill_number',
                 'Payment Adjustment','$rev_code','$rev_Desc',-'$amount','$proc_qty',-'$total','$username','Receipt Payment Adj',
                'Paid','$batch_no','".date('H:i:s')."','$rev_code','$rev_code',0)";
    
    if($debug) echo $sql2;

    $result=$db->Execute($sql2);

    
}

function updateCashErp($db,$pn,$ref_no) {
      //global $db, $root_path;
      $debug=false;
        if ($debug) echo "<b>class_tz_billing::updateCashErp()</b><br>";
        if ($debug) echo "encounter no: $pn <br>";
        ($debug) ? $db->debug=TRUE : $db->debug=FALSE;
        $sql='SELECT patient,`name`,input_date,SUM(total) AS mytotal,ref_no,input_date,rev_code as partcode,
            "rctAdj" as salesType FROM care_ke_receipts
                WHERE patient LIKE "'.$pn.'" and weberp_syncd="0" GROUP BY patient';
        $result=$db->Execute($sql);
        if($weberp_obj = new_weberp()) {
        //$arr=Array();

            while($row=$result->FetchRow()) {
            //$weberp_obj = new_weberp();
                if(!$weberp_obj->transfer_bill_to_webERP_asCreditInvoice($row)) {
                     $sql4="Update care_ke_billing set
                    status='paid' where status='pending' and pid='".$row[0]."'";
                    $db->Execute($sql4);
                     $sql2="Update care_ke_receipts set
                    weberp_syncd='1' where weberp_syncd='0'";
                    $db->Execute($sql2);
                }
                else {
                    echo 'failed';
                }
                destroy_weberp($weberp_obj);
            }
        }else {
            echo 'could not create object: debug level ';
        }
    }


?>



