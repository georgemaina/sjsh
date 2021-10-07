<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 7/20/2016
 * Time: 10:30 PM
 */

require('./roots.php');
require('../include/inc_environment_global.php');
require_once($root_path.'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path.'include/inc_init_xmlrpc.php');
require_once($root_path.'include/care_api_classes/class_tz_billing.php');

$debug=false;;
if ($debug) echo "<b>class_tz_billing::updateCashErp()</b><br>";
if ($debug) echo "encounter no: $pn <br>";
($debug) ? $db->debug=TRUE : $db->debug=FALSE;
$sql='SELECT patient,`name`,input_date,total AS mytotal,ref_no,input_date,
            Proc_code as partcode,"rct" as salesType,rev_code,sale_id,bill_number
                    FROM care_ke_receipts
                    WHERE weberp_syncd="0"';

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