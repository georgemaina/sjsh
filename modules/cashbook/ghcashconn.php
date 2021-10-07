<?php
$debug=false;

($debug) ? $db->debug=FALSE : $db->debug=FALSE;
$bill_obj = new Bill;
$cash_point=$_POST[cash_point];
$ref_no=$_POST[receiptNo];
$paymode=$_POST[paymode];
$paymode_desc=$_POST[paymode_desc];

$pSdate=new DateTime($_POST[calInput]);
$pdateS=$pSdate->format('Y-m-d');
$inputDt= $pdateS;


$custNo=$_POST[customerID];
$custName=$_POST[customer];
$period='2012';
$name = $_SESSION['sess_login_username'];
$username=$name;
$total=$_POST[total];
$gl_acc=$_POST[gl_acc];

if(!$shiftNo){
    $sql='select current_shift from care_ke_cashpoints where cashier="'.$name.'" and 
        active=1 AND pcode="'.$cash_point.'"';
    $result2=$db->Execute($sql);
    $row=$result2->FetchRow();
    $shiftNo=$row[0];
}

if($paymode=='CAS') {
    $cheque_no='0';
}ELSE {
    $cheque_no=$_POST[cheq_no];
}

function insertData($db,$rowid,$cash_point,$ref_no,$paymode,$custNo,
    $custName,$gl_acc,$period,$shiftNo,$username,$inputDt,$gl_acc) {
      $debug=true;
    //global $db;
    $rev_code=$_POST["gridbox_".$rowid."_0"];
    $rev_Desc=$_POST["gridbox_".$rowid."_1"];
    $amount=$_POST["gridbox_".$rowid."_2"];
    $proc_qty=$_POST["gridbox_".$rowid."_3"];
    $total=$_POST["gridbox_".$rowid."_4"];
    
    if($custNo){
        $custID=$custNo;
    }else{
        $custID=0;
    }
    
     if($custName){
        $custNames=$custName;
    }else{
        $custNames='Guest Customer';
    }

    $csql="INSERT INTO `care_ke_receipts`
            (`cash_point`, `ref_no`, `pay_mode`,`type`,`currdate`,`payer`, `patient`,`name`, `gl_acc`,
             `cheque_no`,`rev_code`,`rev_desc`,`proc_code`, `Prec_desc`, `proc_qty`,
             `amount`, `total`,`input_date`,`input_time`,`period`,`Shift_No`,`username`,
             weberp_syncd,bill_number) VALUES
            ('".$cash_point."', '$ref_no','$paymode','RC','$inputDt','$custNames','$custID',
             '$custNames','$gl_acc','0','Gues','GUEST HOSTEL','$rev_code',
            '$rev_Desc','$proc_qty', '$amount', '$total','$inputDt','".date('H:i:s')."',
            '$period','$shiftNo','$username','0','0')";

    if($debug) echo $csql;
   $db->Execute($csql);

}
////
//echo var_dump($_POST);
//echo ' rows '.$_POST[gridbox_rowsadded];

    if(strstr($_POST[gridbox_rowsadded],",")) {
        $added_rows=$_POST[gridbox_rowsadded];
        $arr_rows= explode(",", $added_rows);
        for($i=0;$i<count($arr_rows);$i++) {
//            echo "$arr_rows[$i]<br>";

            insertData($db,$arr_rows[$i],$cash_point,$ref_no,$paymode,$custNo,
            $custName,$gl_acc,$period,$shiftNo,$username,$inputDt,$gl_acc);

            nxtReceiptNo($db,$ref_no,$cash_point);
//            updateBalances($db,$encounter_nr,$cash_point,$ref_no,$paymode,$mydate,$payer,$patientno,
//            $inputDt,$username,$new_bill_number,$encounter_class_nr,$arr_rows[$i],$ref_no);
        }
    }else {
            insertData($db,$_POST[gridbox_rowsadded],$cash_point,$ref_no,$paymode,$custNo,
    $custName,$gl_acc,$period,$shiftNo,$username,$inputDt,$gl_acc);

            nxtReceiptNo($db,$ref_no,$cash_point);
//            updateBalances($db,$encounter_nr,$cash_point,$ref_no,$paymode,$mydate,$payer,$patientno,
//            $inputDt,$username,$new_bill_number,$encounter_class_nr,$_POST[gridbox_rowsadded],$ref_no);
            
}

//updateCashErp($db,$patientno,$ref_no);


function nxtReceiptNo($db,$currRcptNo,$cashpoint) {
   $debug=FALSE;
    $nextRctpNo=intval(substr($currRcptNo,1)+1);

    $sql="update care_ke_cashpoints set next_receipt_no='$nextRctpNo' where pcode='$cashpoint'";
    if($debug) echo $sql;
    $result=$db->Execute($sql);

    if($result)
        echo " ";
    else
        echo "Error in nxtReceiptNo err desc=".mysql_error();
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
    if($encounter_class_nr==1){
        $revcode='IP';
    }else{
        $revcode=$_POST["gridbox_".$rowid."_0"];
    }
    
    $sql2="INSERT INTO care_ke_billing
                (
                    pid,encounter_nr,`IP-OP`, bill_date,bill_number,service_type,Description,
                    price,qty,total,input_user,notes, STATUS,batch_no,bill_time,rev_code,partcode
        ,item_number,weberpsync)
                VALUES('$patientno','$encounter_nr','$encounter_class_nr','$inputDt','$new_bill_number','Payment',
    '$Prec_Desc','$amount','$proc_qty','$total','$username','Receipt Payment','Paid','$batch_no',
    '".date('H:i:s')."','$revcode','$Proc_code','$Proc_code',1)";
    
    if($debug) echo $sql2;

    $result=$db->Execute($sql2);
    
}

function updateCashErp($db,$pn,$ref_no) {
      //global $db, $root_path;
      $debug=false;;
        if ($debug) echo "<b>class_tz_billing::updateCashErp()</b><br>";
        if ($debug) echo "encounter no: $pn <br>";
        ($debug) ? $db->debug=TRUE : $db->debug=FALSE;
        $sql='SELECT patient,`name`,input_date,SUM(total) AS mytotal,ref_no,input_date,
            Proc_code as partcode,"rct" as salesType,rev_code 
                    FROM care_ke_receipts
                    WHERE patient="'.$pn.'" and weberp_syncd="0" GROUP BY patient';
        
        $result=$db->Execute($sql);
        
         
        if($weberp_obj = new_weberp()) {
        //$arr=Array();

            while($row=$result->FetchRow()) {
            //$weberp_obj = new_weberp();
                        if(!$weberp_obj->transfer_bill_to_webERP_asCreditInvoice($row)) {
                            $sql4="Update care_ke_billing set
                            status='paid',weberpsync=1 where status='pending' and  pid='".$row[0]."'";
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