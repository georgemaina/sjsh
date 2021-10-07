<?php
require_once('roots.php');
require($root_path.'include/inc_environment_global.php');
require($root_path.'include/inc_date_format_functions.php');
require($root_path.'include/care_api_classes/class_tz_billing.php');
$bill_obj = new Bill;


$desc=$_REQUEST[desc];
$desc2=$_REQUEST[desc2];
$desc3=$_REQUEST[desc3];
$desc4=$_REQUEST[desc4];
$desc5=$_REQUEST[desc5];
$desc6=$_REQUEST[desc6];
$desc7=$_REQUEST[desc7];
$desc8=$_REQUEST[desc8];
$desc9=$_REQUEST[desc9];
$desc10=$_REQUEST[desc10];
$cashier=$_REQUEST[cashier];
$pid=$_REQUEST[pid];
$caller=$_REQUEST[callerID];
$prescNr=$_REQUEST[prescNr];
$enc_no=$_REQUEST[enc_no];


if($caller=='service'){
            if($desc) {
               $sql="SELECT st_name FROM care_ke_stlocation WHERE `st_id`='$desc'";
               $result=$db->Execute($sql);
                if (!$result) {
                    echo 'Could not run query: ' . mysql_error();
                    exit;
                }

                $row=$result->FetchRow();

                echo "$row[0]";// 42

            } else {
                echo "---";
            }
}else if($caller=='getReceiptNo'){
    if($pid) {
//        $sql3="SELECT batch_no,SUM(total) AS amount FROM care_ke_billing r
//                LEFT JOIN care_encounter e ON r.`pid`=e.`pid`
//                WHERE  r.pid=$pid AND service_type='Payment'
//                AND e.`is_discharged`=0 AND r.bill_date='".date('Y-m-d')."'";
        
        $sql3="SELECT batch_no,SUM(total) AS amount FROM care_ke_billing r
                LEFT JOIN care_encounter e ON r.`pid`=e.`pid`
                LEFT JOIN care_tz_drugsandservices d ON r.`partcode`=d.`partcode`
                WHERE  r.pid='$pid' AND service_type='Payment'
                AND e.encounter_nr='$enc_no' AND d.`purchasing_class` IN('drug_list','medical-supplies')";
        //echo $sql3;
        $result=$db->Execute($sql3);
        if (!$result) {
            echo 'Could not run query: ' . mysql_error();
            exit;
        }

        $row3=$result->FetchRow();

        $receipt=$row3[0];
        $amount=$row3[1];
      }else{
        $receipt='Nil';
        $amount='Nil';
    }
        echo $receipt.",".$amount;// 42


}else if($caller=='irnNo'){
            if($desc2) {
               $sql3="SELECT MAX(req_no) as req_no FROM care_ke_internalreq";
               $result=$db->Execute($sql3);
                if (!$result) {
                    echo 'Could not run query: ' . mysql_error();
                    exit;
                }

                $row3=$result->FetchRow();
                if(!$row3[0]){
                    $nextIRno='1000001';
                }else{
                    $nextIRno=intval($row3[0])+1;
                }
                echo $nextIRno;// 42

            } else {
                echo "1000001";
            }
}else if($caller=='supSto'){
            if($desc3) {
               $sql="SELECT st_name FROM care_ke_stlocation WHERE st_id='$desc3'";
               $result=$db->Execute($sql);
                if (!$result) {
                    echo 'Could not run query: ' . mysql_error();
                    exit;
                }

                $row=$result->FetchRow();

                echo "$row[0]";// 42

            } else {
                echo "---";
            }
}else if($caller=='issue'){
            if($desc4) {
               $sql="SELECT st_name FROM care_ke_stlocation WHERE st_id='$desc4'";
               $result=$db->Execute($sql);
                if (!$result) {
                    echo 'Could not run query: ' . mysql_error();
                    exit;
                }

                $row=$result->FetchRow();

                echo "$row[0]";// 42

            } else {
                echo "---";
            }
}else if($caller=='issNo'){
           if($desc5) {
               $sql="SELECT MAX(order_no) FROM care_ke_internal_orders";
               $result=$db->Execute($sql);
                if (!$result) {
                    echo 'Could not run query: ' . mysql_error();
                    exit;
                }

                $row=$result->FetchRow();
                if(!$row[0]){
                    $nextIRno='1000001';
                }else{
                    $nextIRno=intval($row[0])+1;
                }
                echo "$nextIRno";// 42

            } else {
                echo "1000001";
            }
}else if($caller=='Cancel'){
           if($desc8) {
               $sql="UPDATE care_ke_internalreq set `status`='Cancelled' where req_no='$desc8'";
               
              
                if($result=$db->Execute($sql)){
                    echo "Order Cancelled Successfull";
                }else{
                    echo "Error Cancelling the Order  $sql";
                }
            

            } else {
                echo "Order No: Please Select an Order to cancel";
            }
}else if($caller=='getPrescriber') {
    if ($enc_no) {

         $sql = "SELECT DISTINCT prescriber,encounter_nr FROM `care_encounter_prescription` WHERE encounter_nr='$enc_no' AND drug_class='drug_list'";

        $result = $db->Execute($sql);

        if ($row = $result->FetchRow()) {
            echo $row[0].",".$row[1] ;
        } else {
            echo "Error: Please enter patients PID";
        }


    } else {
        echo "Error: Please enter patients PID";
    }
}else if($caller=='getNames'){
           if($desc9) {

               $encNo=$_REQUEST[encNo];

               $sql="select concat(p.name_first,' ',p.name_2,' ',p.name_last) as empnames,max(b.encounter_nr), b.is_discharged ,max(a.prescriber) AS prescriber,p.date_birth
                from care_person p 
                INNER JOIN  care_encounter b ON p.pid=b.pid
                left join care_encounter_prescription a on b.encounter_nr=a.encounter_nr
                LEFT JOIN care_ke_locstock l on a.partcode=l.stockid
                LEFT JOIN care_ke_stlocation c on l.loccode=c.st_id
                WHERE b.pid='$desc9' AND a.drug_class IN('drug_list','Medical-Supplies','THEATRE')";
               
              $result=$db->Execute($sql);


                if($row=$result->FetchRow()){
                    $age=exactAge($row[date_birth]);
                    echo $row[0].",".$row[1],",".$row[prescriber],",".$age;
                }else{
                    echo "Error: Please enter patients PID";
                }
            

            } else {
                echo "Error: Please enter patients PID";
            }
}else if($caller=='returnNo'){
           if($desc10) {
               $sql="SELECT MAX(return_no) FROM care_ke_internal_returns";
                $result=$db->Execute($sql);
            if (!$result) {
                    echo 'Could not run query: ' . mysql_error();
                    exit;
                }

                $row=$result->FetchRow();
                if(!$row[0]){
                    $nextReturnNo='1000001';
                }else{
                    $nextReturnNo=intval($row[0])+1;
                }
                echo "$nextReturnNo";// 42

            } else {
                echo "1000001";
            }
}else if($caller=='confirmCounts'){
    $sql="select * from care_ke_stockCountTemp where status='pending'";
     $result=$db->Execute($sql);
     while($row=$result->FetchRow()){
         $sql1="insert into `care_ke_stockcount`
            (`partcode`,`loccode`,`stockCountDate`,`CurrQty`,`currCount`,`inputUser`,`status`)
            values ('$row[partcode]','$row[loccode]','$row[stockCountDate]',$row[CurrQty],
                    $row[currCount],'$row[inputUser]','$row[status]')";
//         echo $sql1;
         if($db->Execute($sql1)){
             $sql3="delete from care_ke_stockCountTemp where id=$row[id]";
             $db->Execute($sql3);
         }
     }
     
     $sql2="select COUNT(partcode) as pcount from care_ke_stockCountTemp where status='pending'";
     $result=$db->Execute($sql2);
     $row=$result->FetchRow();
     if($row[0]>0){
         echo 1;
     }else{
         echo 0;
     }
     
     
}else if($caller=='processCounts'){
    $sql="select * from care_ke_stockCount where status='pending'";
     $result=$db->Execute($sql);
     while($row=$result->FetchRow()){
         $sql1="update care_ke_locstock set quantity=$row[currCount] where stockid='$row[partcode]' and loccode='$row[loccode]'";
//         echo $sql1;
         if($db->Execute($sql1)){
             $sql3="delete from care_ke_stockCount where id=$row[id]";
             $db->Execute($sql3);
         }
     }
     
     $sql2="select * from care_ke_stockCount where status='pending'";
     $result=$db->Execute($sql2);
     $row=$result->FetchRow();
     if($row[0]>0){
         echo 1;
     }else{
         echo 0;
     }
     
     
}else if($caller=='clearImports'){
    
    $sql3="delete from care_ke_stockCounttemp ";
    if($db->Execute($sql3)){
        echo 1;
     }else{
         echo 0;
     }
     
     
}else if($caller=='delete'){
    //echo $prescNr;
    if($prescNr){
        $sql3="update care_encounter_prescription set status='Cancelled' where nr='$prescNr'";
       //echo $sql3;
        if($db->Execute($sql3)){
            echo "Successful";
        }else{
            echo "Unsuccessful";
        }
    }

}else if($caller=='getPaymentMethod') {
    if ($pid) {

//        $sql3 = "SELECT `insurance_ID` FROM care_person WHERE pid='$pid'";
//       // echo $sql3;
//        $result = $db->Execute($sql3);
//        $row = $result->FetchRow();
//
//        if ($row[0] <> '-1') {
//            $accName = getAccountName($row[0]);
//        } else {
//            $accName = "CASH PAYMENT";
//        }


        $accName=$bill_obj->getBillPaymentMethod($pid);
        echo $accName;


    }
}

//function getAccountName($accNO){
//    global $db;
//    $debug=false;
//
//    $sql="SELECT `name` FROM care_tz_company WHERE id='$accNO'";
//    if($debug) echo $sql;
//
//    $results=$db->Execute($sql);
//    $row=$results->FetchRow();
//
//    return $row[0];
//
//}

?>


