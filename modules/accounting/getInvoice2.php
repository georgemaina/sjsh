<?php
// we'll generate XML output

require('roots.php');
require($root_path.'include/inc_environment_global.php');
require_once($root_path.'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path.'include/inc_init_xmlrpc.php');
require_once($root_path.'include/care_api_classes/class_tz_billing.php');
$debug=true;
$pid= $_GET['pid'];
$billnumber= $_GET['billnumber'];


    $sql2="INSERT INTO care_ke_billing_archive
                (
                    pid,encounter_nr,`IP-OP`,insurance_id,bill_date,bill_number,batch_no,rev_code,service_type,
                    Description,price,qty,total,prescribe_date,times_per_day,days,input_user,notes,
                    partcode,item_number,dosage,STATUS,billed,service_desc)
           SELECT k.pid,k.encounter_nr,k.`IP-OP`,p.insurance_id,k.bill_date,k.bill_number,k.batch_no,k.rev_code,k.service_type,
                    k.Description,k.price,k.qty,k.total,k.prescribe_date,k.times_per_day,k.days,k.input_user,
                    k.notes,k.partcode,k.item_number,k.dosage,k.STATUS,k.billed,k.service_desc
                FROM care_ke_billing k left join care_person p on k.pid=p.pid
                WHERE k.`pid`='$pid' AND k.bill_number='".$billnumber."'";
               
    if($debug) echo $sql2;
    
    $result=$db->Execute($sql2);

    if($result) {
        $sql3='INSERT INTO `care_ke_debtors`(`company_id`,`trans_type`,`trans_date`,
             `trans_time`,`pid`,`bill_number`,`amount`,`input_user`)
              SELECT p.insurance_id,"bill","'.date('Y-m-d').'","'.date('H:i:s').'",k.pid,k.bill_number,k.input_user 
              FROM care_ke_billing k left join care_person p on k.pid=p.pid WHERE k.`pid`="'.$pid.'" 
              AND k.bill_number="'.$billnumber.'"';
        
        $result3=$db->Execute($sql3);
         if($debug) echo $sql3;
        
        $sql="DELETE FROM care_ke_billing where `pid`='".$pid."' and bill_number='".$billnumber."'";
        $result2=$db->Execute($sql);
        if($debug) echo $sql;
        if(!$result2){
           echo $patientno."bill not deleted";
        }
    } else {
        echo "Error in updateBill, err desc=".mysql_error();
    }



////$arr=Array();
//while($row=$result->FetchRow()){
// $weberp_obj = new_weberp();
//        if(!$weberp_obj->transfer_bill_to_webERP_asSalesInvoice($row))
//        {
////                echo 'success member transmission<br>';
////                echo date($weberp_obj->defaultDateFormat);
//                  echo '';
//        }
//        else
//        {
//                echo 'failed member transmission';
//        }
//        destroy_weberp($weberp_obj);
//}
   

//        $csql='Insert into care_ke_billing()'

        $sql1 = "SELECT a.service_type,a.Description,a.price,a.qty,a.total
                FROM care2x.care_ke_billing a
                where pid='".$name."' and insurance_id is null";
        $sql2="SELECT SUM(total) AS total
                FROM care2x.care_ke_billing a
                WHERE pid='".$name."' and insurance_id is null";
        if($debug) echo $sql1;
        $result=$db->Execute($sql1);
        $result2=$db->Execute($sql2);
       $row2 = $result2->FetchRow($result2);

       echo '<table width=100%>
                    <tr><td colspan=5 class="pgtitle2">Invoice Details</td></tr>
                    <tr class="prow">
                        <td>Service Type</td>
                        <td>Description</td>
                        <td>Price</td>
                        <td>Quatity</td>
                        <td>Total</td>
                     </tr>';
        while($row = $result->FetchRow($result)){
            $rowbg='white';
              echo '<tr bgcolor='.$rowbg.'>
                            <td>'.$row[0].'</td>
                            <td>'.$row[1].'</td>
                            <td>'.$row[2].'</td>
                            <td>'.$row[3].'</td>
                            <td id>'.$row[4].'</td>
                     </tr>';
                    
                     $rowbg='white';
        }
      echo  '<tr><td colspan=4 align=right><b>Total</b></td><td><b>Ksh.'.$row2[0].'</b></td></tr>';
      echo '</table>';
//echo 'patient is '.$name;





?>
