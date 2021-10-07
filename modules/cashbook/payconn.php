<?php
$debug=false;

($debug) ? $db->debug=FALSE : $db->debug=FALSE;

  $Cash_Point=$_POST[cashPoint];
  $Voucher_No=$_POST[vouchNo];
  $Pay_mode=$_POST[paymode];
//  $shiftNo='0';
  
  
  $pjdate=new DateTime($_POST[strDate1]);
  $pdate=$pjdate->format('Y-m-d');
  
  $ptime=date("H:i:s");
  $gl_acc=$_POST[gl_acc];
  $gl_desc=$_POST[gl_Desc];
  $cheque_no=$_POST[chequeNo];
  $payee=$_POST[payee];
  $toward=$_POST[toward];
  $control=$_POST[amtControl];
  $total=$_POST[total];
  $department=$_POST[department];

  $input_User = $_SESSION['sess_login_username'];


  $total=$_POST[total];

//echo var_dump($_POST);
//echo ' rows '.$_POST[gridbox_rowsadded];

if(!$shiftNo){
    $sql='select current_shift from care_ke_cashpoints where cashier="'.$name.'" and active=1 AND pcode="'.$cash_point.'"';
    $result2=$db->Execute($sql);
    $row=$result2->FetchRow();
    $shiftNo=$row[0];
}

//$new_bill_number=$bill_obj->checkBillEncounter($encounter_nr);

//if($paymode=='CAS') {
//    $cheque_no='0';
//}ELSE {
//    $cheque_no=$_POST[cheq_no];
//}

function insertData($db,$rowid,$Cash_Point,$Voucher_No,$Pay_mode,$pdate,$ptime,$gl_acc,
  $gl_desc,$cheque_no,$payee,$toward,$control,$total,$input_User,$shiftNo,$department){
    require_once('../../include/care_api_classes/class_tz_billing.php');

    $debug=false;

    global $db;

    $bill_obj=new Bill();
    $ledger=$_POST["gridbox_".$rowid."_0"];
    $ledger_code=$_POST["gridbox_".$rowid."_1"];
    $ledger_desc=$_POST["gridbox_".$rowid."_2"];
    $amount=$_POST["gridbox_".$rowid."_3"];

    $pledger=$ledger;

    $encounter_nr=$_POST[encounter_nr];

    $sql="INSERT INTO `care_ke_payments`
            (`Cash_Point`,`Voucher_No`,`Pay_mode`,`pdate`,`ptime`,`gl_acc`,`gl_desc`,
             `cheque_no`,`payee`,`toward`,`control`,`ledger`,`ledger_code`,`ledger_desc`,
             `amount`,`input_User`,`total`,`printed`,`shift_no`,weberp_syncd,department)
            VALUES (
              '$Cash_Point','$Voucher_No','$Pay_mode','$pdate','$ptime','$gl_acc',
              '$gl_desc','$cheque_no','$payee','$toward','$control','$ledger','$ledger_code','$ledger_desc',
                '$amount','$input_User','$total','0','$shiftNo','0','$department')" ;


    $db->Execute($sql);
    if($debug) echo $sql;
//    echo $ledger_code;
    if($ledger=="DB"){
        $sql="select os_bal from care_ke_debtors where accno='$ledger_code'";
        $result=$db->Execute($sql);
        if($debug) echo $sql;
        if($row=$result->FetchRow()){
            $newBal=intval($row[0]+$amount);
                $sql2="Update care_ke_Debtors set
                    os_bal='".$newBal."' where accno='$ledger_code'";
                if($debug) echo $sql2;
                $db->Execute($sql2);
        }
    }else if($ledger=="SUP"){
         $sql="select currBalance from suppliers where supplierid='$ledger_code'";
        $result=$db->Execute($sql);
        if($debug) echo $sql;
        if($row=$result->FetchRow()){

             $newBal=intval($row[0]+$amount);
         
                $sql2="Update Suppliers set
                    currBalance='".$newBal."' where supplierid='$ledger_code'";
                if($debug) echo $sql2;
                $db->Execute($sql2);
                
                $sql2="INSERT INTO `care_ke_suppliertrans`(`supplierID`, `transDate`,
                 `transTime`, `CurrBal`, `newBal`, `inputUser`) VALUE ('$ledger_code',
                 '".date("Y-m-d")."', '".date("H:i:s")."', '$CurrBal', '$newBal', '$input_User')";
                if($debug) echo $sql2;
                $db->Execute($sql2);
                
        }
    }else if($ledger=="IP"){
                             
                $sql="SELECT max(encounter_nr) as encounter_nr FROM care_encounter WHERE pid='$ledger_code' 
            AND encounter_status <> 'cancelled' AND status NOT IN ('deleted','hidden','inactive','void')";
                if($debug) echo $sql;
                 if($result=$db->Execute($sql)){
                    $row=$result->FetchRow();
                    $encounter_nr=$row[0];
                 }
                 
                 $new_bill_number=$bill_obj->checkBillEncounter($encounter_nr);
                $sql2="INSERT INTO care_ke_billing
                    (pid,encounter_nr,`IP-OP`, bill_date,bill_number,service_type,Description,
                    price,qty,total,input_user,notes, STATUS,batch_no,bill_time,rev_code,partcode,item_number)
                    VALUES('$ledger_code','$encounter_nr','1','$pdate','$new_bill_number','Payment',
                    'Patient fund',-'$total','1',-'$total','$input_User','Patient Refund',
                    'Paid','$new_bill_number',
                    '".date('H:i:s')."','IP','Patient Refund','IP')";

                if($debug) echo $sql2;

                    $result=$db->Execute($sql2);
        }
}


    if(strstr($_POST[gridbox_rowsadded],",")) {
        $added_rows=$_POST[gridbox_rowsadded];
        $arr_rows= explode(",", $added_rows);
        for($i=0;$i<count($arr_rows);$i++) {
            insertData($db,$arr_rows[$i],$Cash_Point,$Voucher_No,$Pay_mode,$pdate,$ptime,$gl_acc,
            $gl_desc,$cheque_no,$payee,$toward,$control,$total,$input_User,$shiftNo,$department);
           
        }
    }else {
            insertData($db,$_POST[gridbox_rowsadded],$Cash_Point,$Voucher_No,$Pay_mode,$pdate,$ptime,$gl_acc,
            $gl_desc,$cheque_no,$payee,$toward,$control,$total,$input_User,$shiftNo,$department);
             
    }
nextChequeNo($db,$Cash_Point,$Pay_mode);
nextVoucherNo($db,$Cash_Point);

updatePaymentErp($db,$Voucher_No,$payMode,$cashpoint);

function nextChequeNo($db,$cashpoint,$paymode) {
    $debug=false;
    
    $sql1="select NextChequeNo from care_ke_paymentmode where cash_point='$cashpoint' and payment_mode='$paymode'";
    $result=$db->Execute($sql1);
    $row=$result->FetchRow();
    $newChqNo=intval($row[0]+1);

    $sql="update care_ke_paymentmode set NextChequeNo='$newChqNo' where cash_point='$cashpoint' and payment_mode='$paymode'";
    if($debug) echo $sql;
    $result=$db->Execute($sql);

    if($result)
        echo " ";
    else
        echo "Error in NextChequeNo err desc=".mysql_error();
}

function nextVoucherNo($db,$cashpoint) {
    $debug=TRUE;
    $sql1="select Next_voucher_no from care_ke_cashpoints where pcode='$cashpoint'";
    $result=$db->Execute($sql1);
    $row=$result->FetchRow();
    $newVcnNo=intval($row[0]+1);

    $sql="update care_ke_cashpoints set Next_voucher_no='$newVcnNo' where pcode='$cashpoint'";
    if($debug) echo $sql;
    $result=$db->Execute($sql);

    if($result)
        echo " ";
    else
        echo "Error in Next_voucher_no err desc=".mysql_error();
}

function updatePaymentErp($db,$Voucher_No,$payMode,$cashpoint) {
      //global $db, $root_path;
      $debug=false;
        if ($debug) echo "<b>class_tz_billing::updatePaymentErp()</b><br>";
        if ($debug) echo "Voucher no: $Voucher_No <br>";
        ($debug) ? $db->debug=TRUE : $db->debug=FALSE;

   
        $sql="SELECT Cash_Point,Voucher_No,gl_acc,GL_Desc,pdate,cheque_no,ledger_code,
                    ledger_desc,payee,toward,Amount,Total,input_user,pay_mode,ledger,department
              FROM `care_ke_payments`
              WHERE cash_point='$cashpoint' AND pay_mode='$payMode' AND voucher_no='$Voucher_No' AND  weberp_syncd='0'";
        if($debug) echo $sql;
        $result=$db->Execute($sql);
        if($weberp_obj = new_weberp()) {
        //$arr=Array();

            while($row=$result->FetchRow()) {
                      if($row[ledger]=='SUP'){
                            if(!$weberp_obj->transfer_trans_to_webERP_asPayment($row)) {

                                 $sql2="Update care_ke_payments set
                                weberp_syncd='1' where weberp_syncd='0' and cash_point='$cashpoint' AND 
                                pay_mode='$payMode' AND voucher_no='$Voucher_No'";
                                 if($debug) echo $sql2;
                                $db->Execute($sql2);

                            }
                            else {
                                echo 'failed';
                            }
                            destroy_weberp($weberp_obj);
                      }else if($row[ledger]=='GL'){
                          if(!$weberp_obj->transfer_gl_to_webERP_asPayment($row)) {

                                 $sql2="Update care_ke_payments set
                                weberp_syncd='1' where weberp_syncd='0' and cash_point='$cashpoint' AND 
                                pay_mode='$payMode' AND voucher_no='$Voucher_No'";
                                 if($debug) echo $sql2;
                                $db->Execute($sql2);
                            }
                            else {
                                echo 'failed';
                            }
                            destroy_weberp($weberp_obj);
                      }else if($row[ledger]=='PC'){
                          if(!$weberp_obj->transfer_pc_to_webERP_asPayment($row)) {

                              $sql2="Update care_ke_payments set
                                weberp_syncd='1' where weberp_syncd='0' and cash_point='$cashpoint' AND
                                pay_mode='$payMode' AND voucher_no='$Voucher_No'";
                              if($debug) echo $sql2;
                              $db->Execute($sql2);
                          }
                          else {
                              echo 'failed';
                          }
                          destroy_weberp($weberp_obj);
                      }else if($row[ledger]=='DB'){
                          if(!$weberp_obj->transfer_DB_to_webERP_asPayment($row)) {
                                 $sql2="Update care_ke_payments set
                                weberp_syncd='1' where weberp_syncd='0' and cash_point='$cashpoint' AND 
                                pay_mode='$payMode' AND voucher_no='$Voucher_No'";
                                 if($debug) echo $sql2;
                                $db->Execute($sql2);
                            }
                            else {
                                echo 'failed';
                            }
                            destroy_weberp($weberp_obj);
                      }else if($row[ledger]=='IP'){
                          if(!$weberp_obj->transfer_IP_to_webERP_asPayment($row)) {
                                 $sql2="Update care_ke_payments set
                                weberp_syncd='1' where weberp_syncd='0' and cash_point='$cashpoint' AND 
                                pay_mode='$payMode' AND voucher_no='$Voucher_No'";
                                 if($debug) echo $sql2;
                                $db->Execute($sql2);
                                
                           }
                            else {
                                echo 'failed';
                            }
                            destroy_weberp($weberp_obj);
                      }
            }
        }else {
            echo 'could not create object: debug level ';
        }
    }

?>



