<?php

require('./roots.php');
require('../include/inc_environment_global.php');
require_once($root_path.'include/care_api_classes/class_tz_billing.php');
        
global $db;

$receipt=$_POST['receipt'];
$pid=$_POST['pid'];
$encClass=$_POST['encClass'];
$billTime=date('Y-m-d');
    
$bill_number=$bill_obj->checkBillEncounter($encounter_nr);
$bill_obj = new Bill;

$sql="select * from care_ke_receipts where patient='$pid' and ref_no='$receipt'";
$result=$db->Execute($sql);

while($row=$result->FetchRow()){
  
 $sql2="INSERT INTO care_ke_billing
          (
             pid,encounter_nr,`IP-OP`, bill_date,bill_number,service_type,Description,
              price,qty,total,input_user,notes, STATUS,batch_no,bill_time,rev_code,partcode,item_number)
             SELECT r.patient,e.encounter_nr,'$encClass',r.currdate,'$bill_number','Payment',r.rev_desc,
              r.amount,r.proc_qty,r.TotaL,r.username,'payment','paid',r.ref_no,$billTime,r.rev_code,r.rev_code,r.rev_code
             FROM care_ke_receipts r INNER JOIN care_encounter e 
              ON r.patient=e.pid WHERE patient= $pid AND e.is_discharged=0";
    
    if($result1=$db->Execute($sql1)){
          echo "Success receipt transefere  <br>";
    }else{
        echo "error:".$sql1.'<br>';
    }
}


?>