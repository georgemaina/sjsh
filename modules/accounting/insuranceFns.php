<?php

error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('roots.php');
require($root_path.'include/inc_environment_global.php');
require_once($root_path.'include/care_api_classes/class_tz_insurance.php');
$insurance_obj = new Insurance_tz;
$desc1=$_REQUEST[desc1];
$desc2=$_REQUEST[desc2];
$desc3=$_REQUEST[desc3];
$desc4=$_REQUEST[desc4];
$desc5=$_REQUEST[desc5];
$caller=$_REQUEST[caller];//debitGetPt

if($caller==debitGetPt) {
    if($desc3) {
        global $db;

        $sql = "SELECT name_first,name_2,name_last,encounter_nr FROM care_person,care_encounter
        WHERE care_person.pid=care_encounter.pid AND care_person.pid='$desc3'";
        // $result = mysql_query("SELECT name,next_receipt_no FROM care_ke_cashpoints WHERE pcode='$desc2'");
        $result=$db->Execute($sql);
        if (!$result) {
            echo 'Could not run query: ' . mysql_error();
            exit;
        }

        $row=$result->FetchRow();

        echo $row[0]." ".$row[1]." ".$row[2]." ".$row[3]; // 42

    } else {
        echo " ";
    }
} else if($caller==getInvoiceno) {
    if($desc4) {
        global $db;

        $sql = "SELECT bill_number,SUM(total) AS amount FROM care_ke_billing WHERE pid='$desc4'";
        // $result = mysql_query("SELECT name,next_receipt_no FROM care_ke_cashpoints WHERE pcode='$desc2'");
        $result=$db->Execute($sql);
        if (!$result) {
            echo 'Could not run query: ' . mysql_error();
            exit;
        }

        if($row=$result->FetchRow()){
            echo $row[0].",".$row[1]; // 42
        }else{
            echo 'nil';
        }
        
    } else {
        echo "nil";
    }
}else if($caller==getInsurance) {
    if($desc2) {
        global $db;

            echo $insurance_obj->Get_insuranceID_from_pid($desc2).','.$insurance_obj->GetName_insurance_from_pid($desc2); // 42

    } else {
        echo " ";
    }
}else if($caller=='creditNo'){
           if($desc1) {
               $sql="SELECT MAX(creditNo) as crno FROM care_ke_insurancecredits";
               $result=$db->Execute($sql);
                if (!$result) {
                    echo 'Could not run query: ' . mysql_error();
                    exit;
                }

                $row=$result->FetchRow();
                 if($row[0]>1){
                   $nextIRno=intval($row[0])+1;
                }else{
                   echo "1000001";
                }
                 echo "$nextIRno";
            } 
               
            
}else if($caller=='getDisDate'){
           if($desc5) {
               $sql="SELECT distinct date_to FROM care_encounter_location WHERE encounter_nr='$desc5'";
               $result=$db->Execute($sql);
                if (!$result) {
                    echo 'Could not run query: ' . mysql_error();
                    exit;
                }

                $row=$result->FetchRow();
                           
                 echo "$row[0]";
            } 
               
            
}


?>


