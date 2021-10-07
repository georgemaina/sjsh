<?php
// we'll generate XML output

require('roots.php');
require($root_path.'include/inc_environment_global.php');
require_once($root_path.'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path.'include/inc_init_xmlrpc.php');
require_once($root_path.'include/care_api_classes/class_tz_billing.php');
$debug=false;
$name = $_GET['desc'];
require_once($root_path.'include/care_api_classes/class_tz_insurance.php');

$insurance_obj = new Insurance_tz;
$sql='SELECT a.insurance_id as pid, b.pid as pids,a.price,a.partcode,a.description as article,a.prescribe_date,a.bill_number,
(a.dosage*a.times_per_day*a.days) AS amount,((a.dosage*a.times_per_day*a.days)*a.price) as total
FROM care2x.care_ke_billing a, care2x.care_encounter b
WHERE a.encounter_nr=b.encounter_nr AND a.pid="'.$name.'" and insurance_id is not null and `IP-OP`=2';
$result=$db->Execute($sql);
$rows=$result->FetchRow();
  $IS_PATIENT_INSURED=$insurance_obj->is_patient_insured($name);
   if($IS_PATIENT_INSURED){
    $result=$db->Execute($sql);

//$arr=Array();
while($row=$result->FetchRow()){
 $weberp_obj = new_weberp();
        if(!$weberp_obj->transfer_bill_to_webERP_asSalesInvoice($row))
        {
//                echo 'success member transmission<br>';
//                echo date($weberp_obj->defaultDateFormat);
                  echo '';
        }
        else
        {
                echo 'failed member transmission';
        }
        destroy_weberp($weberp_obj);
}
   }

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
