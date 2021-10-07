<?php

error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('roots.php');
require($root_path.'include/inc_environment_global.php');

$desc1=$_REQUEST[desc1];
$desc2=$_REQUEST[desc2];
$desc3=$_REQUEST[desc3];
$desc4=$_REQUEST[desc4];
$desc5=$_REQUEST[desc5];
$desc6=$_REQUEST[desc6];
$desc7=$_REQUEST[desc7];
$desc8 = $_REQUEST[desc8];
$caller=$_REQUEST[caller];//debitGetPt

if($caller==debitGetPt) {
    if($desc3) {
        global $db;

        $sql = "SELECT name_first,name_2,name_last,encounter_nr,encounter_class_nr FROM care_person,care_encounter
        WHERE care_person.pid=care_encounter.pid AND care_person.pid='$desc3' and encounter_class_nr=1";
        // $result = mysql_query("SELECT name,next_receipt_no FROM care_ke_cashpoints WHERE pcode='$desc2'");
        echo $sql;
        $result=$db->Execute($sql);
        if (!$result) {
            echo 'Could not run query: ' . mysql_error();
            exit;
        }

        $row=$result->FetchRow();

        echo $row[0]." ".$row[1]." ".$row[2]." ".$row[3]; // 42

    } else {
        echo " $desc3";
    }
} else if($caller==debitGetRv) {
    if($desc4) {
        global $db;

        $sql = "SELECT item_description FROM care_tz_drugsandservices WHERE purchasing_class LIKE 'small%' and partcode like '$desc4%'";
        // $result = mysql_query("SELECT name,next_receipt_no FROM care_ke_cashpoints WHERE pcode='$desc2'");
        $result=$db->Execute($sql);
        if (!$result) {
            echo 'Could not run query: ' . mysql_error();
            exit;
        }

        $row=$result->FetchRow();

        echo $row[0]." ".$row[1]." ".$row[2]; // 42

    } else {
        echo " ";
    }
}else if($caller==getAdDsDate) {
    if($desc2) {
        global $db;

        $sql = "SELECT date_from,date_to,DATEDIFF(date_to,date_from)
      FROM care_encounter_location WHERE encounter_nr='$desc2'";
       
        $result=$db->Execute($sql);
        if (!$result) {
            echo 'Could not run query: ' . mysql_error();
            exit;
        }

        $row=$result->FetchRow();
         $date =$row[0];
         list($year,$month, $day ) = split('[/.-]', $date);
         echo $month.'/'.$day.'/'.$year;

    } else {
        echo " ";
    }
}else if($caller=='creditNo'){
           if($desc1) {
               $sql="SELECT MAX(creditNo) as crno FROM care_ke_nhifcredits";
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
               $sql="SELECT discharge_date FROM care_encounter WHERE pid='$desc5' and encounter_class_nr=1";
               $result=$db->Execute($sql);
                if (!$result) {
                    echo 'Could not run query: ' . mysql_error();
                    exit;
                }

                if($row=$result->FetchRow()){
                       $date =$row[0];
                    list($year,$month, $day ) = split('[/.-]', $date);
                    echo $month.'/'.$day.'/'.$year;
////                        echo "$row[0]";
                }else{
                       echo "not yet";
                }
            } 
               
            
}else if($caller=='getNHIFCredit'){
           if($desc6) {
               $sql="SELECT code,description,rate FROM care_ke_revenuecodes WHERE code='NHIF'";
               $result=$db->Execute($sql);
                if (!$result) {
                    echo 'Could not run query: ' . mysql_error();
                    exit;
                }

                if($row=$result->FetchRow()){
                     
                    echo $row[0].','.$row[1].','.$row[2];
////                        echo "$row[0]";
                }else{
                       echo "nil";
                }
            }

} else if($caller==getInvoiceno) {
    if($desc7) {
        global $db;

        $sql = "SELECT bill_number,SUM(total) AS amount FROM care_ke_billing WHERE pid='$desc7'";
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
}else if ($caller == 'getBills') {
    if ($desc8) {
        global $db;

        $sql = "select DISTINCT bill_number from care_ke_billing where pid=$desc8";

        $result = $db->Execute($sql);
        if (!$result) {
            echo 'Could not run query: ' . $sql;
            exit;
        }

        echo "Invoice No's<select id='bills'>";
        while ($row = $result->FetchRow()) {
            echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
        }
        echo '<select>';
    } else {
        echo "nil";
    }
}

?>

