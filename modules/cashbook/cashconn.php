<?php
$debug=false;

($debug) ? $db->debug=FALSE : $db->debug=FALSE;
$bill_obj = new Bill;
$cash_point=$_POST[cash_point];
//$ref_no=$_POST[salesReceiptNo];
$paymode=$_POST[paymode];
$paymode_desc=$_POST[paymode_desc];
$mdate=$_POST[calInput];
$payer=ucwords($_POST[payer]);
$patientno=$_POST[patientId];
$encounter_nr=$_POST[encounter_nr];
$encounter_class_nr=$_POST[encounter_class_nr];
$pname= ucwords($_POST[patient_name]);
$inputDt=date("Y-m-d");
$inputTime=date("H:i:s");
$period=date('m');
$name = $_SESSION['sess_login_username'];
$username=$name;
$total=$_POST[total];
$mpesa=$_POST[mpesa];
$visa=$_POST[visa];
$cash=$_POST[cash];
$department=$_POST[department];

//$gl_acc=$_POST[gl_acc];
//echo var_dump($_POST);

$ref_no=$bill_obj->getNextReceiptNo($cash_point);
$gl_acc=$bill_obj->getPayModeGl($cash_point,$paymode);
$encounter_class_nr=$bill_obj->getEncounterClass($encounter_nr);

if(!$shiftNo){
    $sql='select current_shift from care_ke_cashpoints where cashier="'.$name.'" and 
        active=1 AND pcode="'.$cash_point.'"';
    $result2=$db->Execute($sql);
    $row=$result2->FetchRow();
    $shiftNo=$row[0];
}

//0722145522

$new_bill_number=$bill_obj->checkBillEncounter($encounter_nr);

if($paymode=='CAS') {
    $cheque_no='0';
}else {
    $cheque_no=$_POST[cheq_no];
}

 

function insertData($db,$rowid,$cash_point,$ref_no,$paymode,$mydate,$payer,$patientno,
    $pname,$gl_acc,$cheque_no,$inputDt,$inputTime,$period,$shiftNo,$username,$new_bill_number,$salesArea,$bill_obj) {
      $debug=false;
    //global $db;
    $rev_code=$_POST["gridbox_".$rowid."_0"];
    $rev_Desc=$_POST["gridbox_".$rowid."_1"];
    $Proc_code=$_POST["gridbox_".$rowid."_2"];
    $Prec_Desc=$_POST["gridbox_".$rowid."_3"];
    $amount=round($_POST["gridbox_".$rowid."_4"]);
    $proc_qty=$_POST["gridbox_".$rowid."_5"];
    $total=round($_POST["gridbox_".$rowid."_6"]);
    $encounter_nr=$_POST['encounter_nr'];
    $mpesa=$_POST['mpesa'];
    $mpesaRef=$_POST['mpesaref'];
    $visa=$_POST['visa'];
    $cash=round($_POST['cash']);
    $bal=$_POST[bal];

    $desc= addslashes($Prec_Desc);
    $csql="INSERT INTO `care_ke_receipts`
            (`cash_point`, `ref_no`, `pay_mode`,`type`,`currdate`,`payer`, `patient`,`name`, `gl_acc`,
             `cheque_no`,`rev_code`,`rev_desc`,`proc_code`, `Prec_desc`, `proc_qty`,
             `amount`, `total`,`input_date`,`input_time`,`period`,`Shift_No`,`username`,weberp_syncd,bill_number,
             mpesa,visa,cash,balance,mpesaref) VALUES
            ('".$cash_point."', '$ref_no','$paymode','RC','".date('Y-m-d')."','$payer','$patientno',
             '$pname','$gl_acc','$cheque_no','$rev_code','$rev_Desc','$Proc_code',
            '$desc','$proc_qty', '$amount', '$total','$inputDt','$inputTime','$period','$shiftNo',
            '$username','0','$new_bill_number','$mpesa','$visa','$cash','$bal','$mpesaRef')";

    if($debug) echo $csql;
    $db->Execute($csql);

     $sql="update care_ke_billing set status='paid' where pid='$patientno' AND bill_number='$new_bill_number' and status='pending'";
     if($debug) echo $sql;
     $db->Execute($sql);
    
   $sql3="Update care_encounter_prescription set
                    bill_status='paid', bill_number='".$new_bill_number."' where status='pending' and 
                        encounter_nr='".$encounter_nr."'";
   if($debug) echo $sql3;
        $db->Execute($sql3);

        $sql="Update care_test_request_chemlabor set bill_status='PAID',bill_number='$new_bill_number' "
                . "where encounter_nr='$encounter_nr'";
    if($debug) echo $sql;
    $result=$db->Execute($sql);

    $sql6="select isConsultation from care_tz_drugsandservices where partcode='$Proc_code'";
    $result=$db->Execute($sql6);
    $row=$result->FetchRow();
    if($row[0]=='Yes'){
        $sql7="Update care_encounter set conStatus='billed' where encounter_nr=$encounter_nr";
        $db->Execute($sql7);
    }

    if($patientno==20828) {
        $sql = "update care_ke_locstock set quantity=quantity-'$proc_qty' where stockid='$Proc_code' and loccode='Dispens'";
        $db->Execute($sql);
    }

//    if($bill_obj->checkIncomeTrans('receipt')){
//        $glCode=$bill_obj->getItemGL($rev_code);
//        $bill_obj->updateIncomeTrans($glCode,$total,date('Y-m-d'),$ref_no,'+','receipt');
//    }

   }


$sql="SELECT id,encounter_nr,bill_number,salesArea FROM care_ke_billing  WHERE status='pending' and pid='".$patientno."'";
if($debug) echo $sql;
$result=$db->Execute($sql);
$rows=$result->RecordCount();

if(!isset($_POST[gridbox_rowsadded])) {
//   echo '0';
    while($row3=$result->FetchRow()) {
//        echo 'gridbox fields are'.$rows.'<br>';

        insertData($db,$row3[0],$cash_point,$ref_no,$paymode,$mydate,$payer,$patientno,
    $pname,$gl_acc,$cheque_no,$inputDt,$inputTime,$period,$shiftNo,$username,$row3[2],$salesArea,$bill_obj);
//       $bill_obj->updateCashErp($db,$patientno);
       
//        updateLabPmt($db,$patientno,$row3[encounter_nr]);
//       updateBill($db,$row[0],$patientno);
       nxtsalesReceiptNo($db,$ref_no,$cash_point);
       updateBalances($db,$encounter_nr,$cash_point,$ref_no,$paymode,$mydate,$payer,$patientno,
   $inputDt,$username,$new_bill_number,$encounter_class_nr,$row3[0],$ref_no);
    }

//    echo 'rows update are '.$rows;
}else {
    if(strstr($_POST[gridbox_rowsadded],",")) {
        $added_rows=$_POST[gridbox_rowsadded];
        $arr_rows= explode(",", $added_rows);
        for($i=0;$i<count($arr_rows);$i++) {
//            echo "$arr_rows[$i]<br>";

            insertData($db,$arr_rows[$i],$cash_point,$ref_no,$paymode,$mydate,$payer,$patientno,
            $pname,$gl_acc,$cheque_no,$inputDt,$inputTime,$period,$shiftNo,$username,$row3[2],$salesArea,$bill_obj);
//        $bill_obj->updateCashErp($db,$patientno);
            
//             updateLabPmt($db,$patientno,$encounter_nr);
//            updateBill($db,$arr_rows[$i],$patientno);
            nxtsalesReceiptNo($db,$ref_no,$cash_point);
            updateBalances($db,$encounter_nr,$cash_point,$ref_no,$paymode,$mydate,$payer,$patientno,
                $inputDt,$username,$new_bill_number,$encounter_class_nr,$arr_rows[$i],$ref_no);

        }
    }else {
            insertData($db,$_POST[gridbox_rowsadded],$cash_point,$ref_no,$paymode,$mydate,$payer,$patientno,
            $pname,$gl_acc,$cheque_no,$inputDt,$inputTime,$period,$shiftNo,$username,$row3[2],$salesArea,$bill_obj);
//    $bill_obj->updateCashErp($db,$patientno);
           
//            updateLabPmt($db,$patientno,$encounter_nr);
//            updateBill($db,$_POST[gridbox_rowsadded],$patientno);
            nxtsalesReceiptNo($db,$ref_no,$cash_point);
            updateBalances($db,$encounter_nr,$cash_point,$ref_no,$paymode,$mydate,$payer,$patientno,
   $inputDt,$username,$new_bill_number,$encounter_class_nr,$_POST[gridbox_rowsadded],$ref_no);
            
    }
}

//$bill_obj->updateIncomePayments($inputDt,$mpesa,$visa,$cash);

updateInvoiceErp($db, $patientno,$ref_no);
updateCashErp($db,$patientno,$ref_no);

function nxtsalesReceiptNo($db,$currRcptNo,$cashpoint) {
   $debug=false;
    $nextRctpNo=intval(substr($currRcptNo,1)+1);

    $sql="update care_ke_cashpoints set next_receipt_no='$nextRctpNo' where pcode='$cashpoint'";
    if($debug) echo $sql;
    $result=$db->Execute($sql);

    if($result)
        echo " ";
    else
        echo "Error in nxtsalesReceiptNo err desc=".mysql_error();
}

function updateLabPmt($db,$pid,$encounter_nr) {
    $debug=false;
    $sql2="SELECT a.batch_nr,a.encounter_nr, b.pid,a.bill_number, a.bill_status
        FROM care_test_request_chemlabor a
        left JOIN care_encounter b ON b.encounter_nr=a.encounter_nr
         where b.pid='$pid' and a.encounter_nr='$encounter_nr'";
    if($debug) echo $sql2;
    $result=$db->Execute($sql2);

    // if($result){
    while($row=$result->FetchRow()) {
        $batch=$row['batch_nr'];
        $bill_no=$row['bill_number'];

        $sql="Update care_test_request_chemlabor set bill_status='done',bill_number='$bill_no' where batch_nr='$batch'";
        if($debug) echo $sql;
        $result=$db->Execute($sql);
       
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
   $inputDt,$username,$new_bill_number,$encounter_class_nr,$rowid,$batch_no){
      $debug=false;
    $rev_code=$_POST["gridbox_".$rowid."_0"];
    $rev_Desc=$_POST["gridbox_".$rowid."_1"];
    $Proc_code=$_POST["gridbox_".$rowid."_2"];
    $Prec_Desc=$_POST["gridbox_".$rowid."_3"];
    $amount=$_POST["gridbox_".$rowid."_4"];
    $proc_qty=$_POST["gridbox_".$rowid."_5"];
    $total=$_POST["gridbox_".$rowid."_6"];

//    $new_bill_number=$bill_obj->checkBillEncounter($encounter_nr);
//    if($encounter_class_nr==1){
//        $revcode='IP';
//    }else{
//        $revcode=$_POST["gridbox_".$rowid."_0"];
//    }


    
    $sql2="INSERT INTO care_ke_billing
                (
                    pid,encounter_nr,`IP-OP`, bill_date,bill_number,service_type,Description,
                    price,qty,total,input_user,notes, STATUS,batch_no,bill_time,rev_code,partcode
        ,item_number,weberpsync)
                VALUES('$patientno','$encounter_nr','$encounter_class_nr','$inputDt','$new_bill_number','Payment',
    '$Prec_Desc','$amount','$proc_qty','$total','$username','Receipt Payment','Paid','$batch_no',
    '".date('H:i:s')."','$revcode','$Proc_code','$Proc_code',0)";
    
    if($debug) echo $sql2;

    $result=$db->Execute($sql2);
    
}

function updateInvoiceErp($db, $pn,$ref_no) {
//global $db, $root_path;
    $debug = false;
    if ($debug)
        echo "<b>class_tz_billing::updateInvoiceErp()</b><br>";
    if ($debug)
        echo "encounter no: $pn <br>";
    ($debug) ? $db->debug = TRUE : $db->debug = FALSE;
        $sql = "SELECT a.patient AS pid, a.total AS ovamount,a.`proc_code` AS partcode,a.`Prec_desc` AS article,
                    a.input_date AS prescribe_date, a. proc_qty AS qty, amount,a.bill_number,sale_id,d.`category`,d.`salesAreas` AS salesArea
                    FROM care_ke_receipts a LEFT JOIN care_tz_drugsandservices d ON a.proc_code=d.partcode
                    WHERE a.patient='$pn' AND a.`ref_no`='$ref_no' AND a.weberp_syncd=0";

    if ($debug)
        echo $sql;
    $result = $db->Execute($sql);
    if ($weberp_obj = new_weberp()) {
//$arr=Array();

        while ($row = $result->FetchRow()) {
//$weberp_obj = new_weberp();
            if (!$weberp_obj->transfer_bill_to_webERP_asSalesInvoice($row)) {
                $sql = "update care_encounter_prescription set weberpSync=1 where weberpSync=0";
                $db->Execute($sql);
                if ($debug)
                    echo $sql;
            }
            else {
                echo 'failed';
            }
            destroy_weberp($weberp_obj);
        }
    } else {
        echo 'could not create object: debug level ';
    }
}



function updateCashErp($db,$pn,$ref_no) {
      //global $db, $root_path;
      $debug=false;
        if ($debug) echo "<b>class_tz_billing::updateCashErp()</b><br>";
        if ($debug) echo "encounter no: $pn <br>";
        ($debug) ? $db->debug=TRUE : $db->debug=FALSE;
        $sql='SELECT patient,`name`,input_date,total AS mytotal,ref_no,input_date,
            Proc_code as partcode,"rct" as salesType,rev_code,sale_id,bill_number,d.`category`,d.`salesAreas`
                    FROM care_ke_receipts a LEFT JOIN care_tz_drugsandservices d ON a.rev_code=d.partcode
                    WHERE patient="'.$pn.'" AND a.`ref_no`="'.$ref_no.'"  and weberp_syncd="0"';

        $result=$db->Execute($sql);

        if($weberp_obj = new_weberp()) {
        //$arr=Array();

            while($row=$result->FetchRow()) {
            //$weberp_obj = new_weberp();
                        if(!$weberp_obj->transfer_bill_to_webERP_asCreditInvoice($row)) {
                            $sql2="Update care_ke_receipts set
                            weberp_syncd='1' where weberp_syncd='0' and sale_id=$row[sale_id]";
                            $db->Execute($sql2);

                            $sql="update care_ke_billing set weberpSync=1,status='paid' where bill_number='$row[bill_number]' and status='pending'";
                            if($debug) echo $sql;
                            $db->Execute($sql);
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
