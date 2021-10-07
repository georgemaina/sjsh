<?php
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');
require_once($root_path . 'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path . 'include/inc_init_xmlrpc.php');

$debug=true;

$sql="SELECT a.patient AS pid, a.total AS ovamount,a.`proc_code` AS partcode,a.`Prec_desc` AS article,a.proc_code,
        a.input_date AS prescribe_date, a. proc_qty AS qty, amount,a.bill_number,
        sale_id,d.`category`,d.`salesAreas` AS salesArea,a.weberp_syncd
        FROM care_ke_receipts a LEFT JOIN care_tz_drugsandservices d ON a.proc_code=d.partcode
        WHERE a.weberp_syncd=0 and a.currdate>'2019-01-30' and patient regexp '[0-9]'";

        if ($debug)echo $sql;
        $result = $db->Execute($sql);
    
     if ($weberp_obj = new_weberp()) {
        while ($row = $result->FetchRow()) {
            if (!$weberp_obj->transfer_bill_to_webERP_asSalesInvoice($row)) {
                $sql = "update care_encounter_prescription set weberpSync=1 where weberpSync=0";
                $db->Execute($sql);
                if ($debug)echo $sql;
                echo "Successfully updated sales invoice for pid $row[pid], $row[Prec_desc], $row[amount] <br>";
            }
            else {
                echo 'failed';
            }
            
            if(!$weberp_obj->transfer_bill_to_webERP_asCreditInvoice($row)) {
                $sql2="Update care_ke_receipts set weberp_syncd='1' where weberp_syncd='0' and sale_id=$row[sale_id]";
                if($debug) echo $sql2;
                $db->Execute($sql2);

                $sql="update care_ke_billing set weberpSync=1,status='paid' where bill_number='$row[bill_number]' and status='pending'";
                if($debug) echo $sql;
                $db->Execute($sql);
                
                 echo "Successfully updated sales receipt for pid $row[pid], $row[Prec_desc], $row[amount] <br>";
            }
            else {
                echo 'failed';
            }

            destroy_weberp($weberp_obj);
        }
    } else {
        echo 'could not create object: debug level ';
    }
