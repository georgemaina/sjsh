<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require('roots.php');
require($root_path . 'include/inc_environment_global.php');
require_once($root_path.'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path.'include/inc_init_xmlrpc.php');

global $db;
$payMode='CHQ';
$cashpoint='P01';

$debug=true;

$sql="SELECT Cash_Point,Voucher_No,gl_acc,GL_Desc,pdate,cheque_no,ledger_code,
                    ledger_desc,payee,toward,Amount,Total,input_user,pay_mode,ledger
              FROM `care_ke_payments`
              WHERE weberp_syncd='0'";
if($debug) echo $sql;
$result=$db->Execute($sql);
if($weberp_obj = new_weberp()) {

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
?>
