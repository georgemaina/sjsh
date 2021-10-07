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

//updatePaymentErp($payMode,$cashpoint);
//function updatePaymentErp($payMode,$cashpoint) {
      global $db, $root_path;
      $debug=false;
        if ($debug) echo "<b>class_tz_billing::updatePaymentErp()</b><br>";
        if ($debug) echo "Voucher no: $Voucher_No <br>";
        ($debug) ? $db->debug=TRUE : $db->debug=FALSE;




        $sql="SELECT 'orderNo' AS Order_no,a.nr AS presc_nr,'Issued' AS `status`,prescribe_date AS order_date,DATE_FORMAT(a.create_time,'%H:%i:%s') AS order_time,
                'cash Sale' AS order_type,'25' AS store_loc,'PHARMACY' AS store_desc,p.`pid` AS OP_no,CONCAT(p.`name_first`,' ',p.`name_2`,' ',p.`name_last`) AS patient_name
                ,`partcode` AS item_id,a.`article` AS item_desc,(a.`dosage`*a.`times_per_day`*a.`days`) AS qty,a.`price`
                ,(a.`dosage`*a.`times_per_day`*a.`days`*a.`price`) AS unit_cost,(a.`dosage`*a.`times_per_day`*a.`days`) AS orign_qty,DATE_FORMAT(NOW(),'%Y') AS period,
                'admin' AS input_user,(a.`dosage`*a.`times_per_day`*a.`days`*a.`price`) AS total,'0' as balance
                FROM care_encounter_prescription a
                INNER JOIN  care_encounter b ON a.encounter_nr=b.encounter_nr
                INNER JOIN care_person p ON b.`pid`=p.`pid`
                WHERE  a.drug_class IN ('drug_list','Medical-Supplies') AND a.status='serviced' AND prescribe_date BETWEEN '2014-06-27' AND '2014-07-09' and
                  a.updateStatus='pending'";
        if($debug) echo $sql.'<br>';
        $result = $db->Execute($sql);
        if ($debug)
            echo $sql . '<br>';
        while ($row = $result->fetchRow()) {
            $sql1='Select CurrOrderNo from care_ke_invoice';
            $result1=$db->Execute($sql1);
            $row1=$result1->FetchRow();
            $currNo=$row1[0];
            $nextNo=$currNo;

            $sql="INSERT INTO `care_ke_internal_orders` (
                    `Order_no`,`presc_nr`,`status`,`order_date`,`order_time`,`order_type`,`store_loc`,`store_desc`,`OP_no`,
                          `patient_name`,`item_id`,`Item_desc`,`qty`,`price`,`unit_cost`,`orign_qty`,`balance`,`period`,`input_user`,`total`)
                  VALUES
                        ('$nextNo','$row[presc_nr]', '$row[status]','$row[order_date]','$row[order_time]', '$row[order_type]','$row[store_loc]','$row[store_desc]',
                        '$row[OP_no]','$row[patient_name]','$row[item_id]','$row[item_desc]',
                            '$row[qty]','$row[price]','$row[unit_cost]','$row[orign_qty]','$row[balance]','$row[period]','$row[input_user]','$row[total]' )";
           // echo $sql;
            if($db->Execute($sql)){
                $nextNo=intval($nextNo)+1;
                echo 'Seccessfully Inserted the Order Number '.$nextNo.'<br>';
                $sql2="update care_ke_invoice set CurrOrderNo='$nextNo'";
                $db->Execute($sql2);

                $sql3="update care_encounter_prescription set updateStatus='updated' where nr='$row[presc_nr]'";
                $db->Execute($sql3);
            }
        }


?>
