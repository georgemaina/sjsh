<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');
require_once($root_path . 'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path . 'include/inc_init_xmlrpc.php');

$debug=true;

$sql="SELECT Cash_Point,Voucher_No,gl_acc,GL_Desc,pdate,cheque_no,ledger_code,
                    ledger_desc,payee,toward,Amount,Total,input_user,pay_mode,ledger
              FROM `care_ke_payments`
              WHERE weberp_syncd='0'";

if($debug) echo $sql;
$result=$db->Execute($sql);

while($row=$result->FetchRow()){
    updatePaymentErp($db,$row[Voucher_No],$row[pay_mode],$row[Cash_Point]);
}

function updatePaymentErp($db,$Voucher_No,$payMode,$cashpoint) {
      //global $db, $root_path;
      $debug=false;
        if ($debug) echo "<b>class_tz_billing::updatePaymentErp()</b><br>";
        if ($debug) echo "Voucher no: $Voucher_No <br>";
        ($debug) ? $db->debug=TRUE : $db->debug=FALSE;

   
        $sql="SELECT Cash_Point,Voucher_No,gl_acc,GL_Desc,pdate,cheque_no,ledger_code,
                    ledger_desc,payee,toward,Amount,Total,input_user,pay_mode,ledger
              FROM `care_ke_payments`
              WHERE cash_point='$cashpoint' AND pay_mode='$payMode' AND voucher_no='$Voucher_No' AND  weberp_syncd='0'";
        if($debug) echo $sql;
        $result=$db->Execute($sql);
        if($weberp_obj = new_weberp()) {
        //$arr=Array();

            while($row=$result->FetchRow()) {
                  if($row[ledger]==''){
                      $row[ledger]=='gl';
                  }
                      if($row[ledger]=='SUP' || $row[ledger]=='sup'){
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
                      }else if($row[ledger]=='GL' || $row[ledger]=='gl'){
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
                      }else if($row[ledger]=='PC' || $row[ledger]=='pc'){
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
                      }else if($row[ledger]=='DB' || $row[ledger]=='db'){
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
                      }else if($row[ledger]=='IP' || $row[ledger]=='ip'){
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