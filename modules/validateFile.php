<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('roots.php');
require('../include/inc_environment_global.php');
require('../include/care_api_classes/class_tz_billing.php');
$target=$_REQUEST['target'];
$bill_obj = new Bill;

 //$consultation=$bill_obj->checkConsultationPayment($_SESSION['sess_en']);
 //echo "Consultation is ".$consultation;
?>
<!DOCTYPE html>
<!--

-->
<html>
    <head>
        <title>Consultation Payment Validation File</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="reportsCss.css">
    </head>
    <body>
        <div  class="book">
            <div class='page'>
                <div class='errorHeading'>Finance Management </div>
                <div class='message1'>You are not allowed to Enter <?php echo $target; ?> if the patient has not paid for Consultation Fees.</div>
                <div class='message2'>Please send the Patient to Cashiers to make the payment.</div>
                <div class='payment'>Details of Payment are as Below</div>
                <div class='paymentDetails'>
                    <?php 
                        
                         $sql="SELECT pid,encounter_nr,bill_date,service_type,rev_code,Description,price,qty,total
                             FROM care_ke_billing where encounter_nr='".$_SESSION['sess_en']."' and rev_code in 
                                (select partcode from care_tz_drugsandservices where isConsultation='Yes') and 
                                bill_date='".date('Y-m-d')."' AND status='pending'";
                         // echo $sql;
                          $result=$db->Execute($sql);
                          echo "<table ><tr><th>Pid</th><th>Date</th><th>Service Type</th><th>PartCode</th><th>Description</th><th>Price</th></tr>";
                          while($row=$result->FetchRow()){
                             echo "<tr><td>$row[pid]</td><td>$row[bill_date]</td><td>$row[service_type]</td><td>$row[rev_code]</td><td>$row[Description]</td><td>$row[total]</td></tr>";
                          }
                          echo "</table>";
                                 
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>
