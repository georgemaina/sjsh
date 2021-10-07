<?php
require_once('roots.php');
require($root_path.'include/inc_environment_global.php');
$desc=$_REQUEST[desc];
$desc2=$_REQUEST[desc2];
$desc3=$_REQUEST[desc3];
$desc4=$_REQUEST[desc4];
$desc5=$_REQUEST[desc5];
$desc6=$_REQUEST[desc6];
$desc7=$_REQUEST[desc7];
$desc8=$_REQUEST[desc8];
$cashier=$_REQUEST[cashier];

$caller=$_REQUEST[callerID];

if(!isset($_REQUEST[cashpoint])){
    $strp=$_REQUEST[point];
}else{
    $strp=$_REQUEST[cashpoint];
}

if($caller=='points'){
            if($desc) {
               $sql="SELECT name,next_receipt_no FROM care_ke_cashpoints WHERE pcode='$desc'";
               $result=$db->Execute($sql);
                if (!$result) {
                    echo 'Could not run query: ' . mysql_error();
                    exit;
                }

                $row=$result->FetchRow();

                echo $row[0].",".$row[1]; // 42

            } else {
                echo "---";
            }
}else if($caller=='points2'){
            if($desc7) {
               $sql='SELECT pcode FROM care_ke_cashpoints WHERE cashier="'.$cashier.'" AND active=1 AND pcode="'.$desc7.'"';
               $result=$db->Execute($sql);
                if (!$result) {
                    echo 'Could not run query: ' . mysql_error();
                    exit;
                }

                $row=$result->FetchRow();

                echo $row[0]; // 42

            } else {
                echo "---";
             }
}else if($caller=='paymode'){
    if($desc2) {
             
                $sql="SELECT description,GL_Account FROM care_ke_paymentmode WHERE payment_mode='$desc2' and cash_point='$strp'";
               // $result = mysql_query("SELECT name,next_receipt_no FROM care_ke_cashpoints WHERE pcode='$desc2'");
               $result=$db->Execute($sql);
                if (!$result) {
                    echo 'Could not run query: ' . mysql_error();
                    exit;
                }

                $row=$result->FetchRow();

                echo $row[0].",".$row[1]; // 42

            } else {
                echo "---";
            }
}else if($caller=='patient'){
            if($desc3) {
                $sql="SELECT b.name_first,b.name_2,b.name_last,a.encounter_nr,a.encounter_class_nr,a.pid from care_person b
                inner join care_encounter a on a.pid=b.pid WHERE b.pid='$desc3'";

                //echo $sql;
               // $result = mysql_query("SELECT name,next_receipt_no FROM care_ke_cashpoints WHERE pcode='$desc2'");
               $result=$db->Execute($sql);
                if (!$result) {
                    echo 'Could not run query: ' . mysql_error();
                    exit;
                }

                $row=$result->FetchRow();

                echo $row[0].",".$row[1].",".$row[2]; // 42

            } else {
                echo "---";
            }
}else if($caller=='bill'){
            if($desc8) {
                $sql="SELECT a.bill_number FROM  care_ke_billing a
    WHERE pid='$desc8' and status='pending'";
               // $result = mysql_query("SELECT name,next_receipt_no FROM care_ke_cashpoints WHERE pcode='$desc2'");
               $result=$db->Execute($sql);
                if (!$result) {
                    echo 'Could not run query: ' . mysql_error();
                    exit;
                }

                $row=$result->FetchRow();

                echo $row[0]; // 42

            } else {
                echo "---";
            }
}else if($caller=='filterCount'){
            if($desc4) {
                $sql="SELECT count(pid) as pidcount from  care_ke_billing
                    Where `IP-OP`='2' and pid like '".$desc4."%'";
               // $result = mysql_query("SELECT name,next_receipt_no FROM care_ke_cashpoints WHERE pcode='$desc2'");
               $result=$db->Execute($sql);
                if (!$result) {
                    echo 'Could not run query: ' . mysql_error();
                    exit;
                }

                $row=$result->FetchRow();

                echo $row[0]; // 42

            } else {
                echo "---";
            }
 }else if($caller=='updateGrid'){
            if($desc5) {
                $sql="SELECT service_type,bill_number,item_number,Description,price,qty,total from  care_ke_billing
                    Where `IP-OP`='2' and pid like '".$desc5."%'";
               // $result = mysql_query("SELECT name,next_receipt_no FROM care_ke_cashpoints WHERE pcode='$desc2'");
               $result=$db->Execute($sql);
                if (!$result) {
                    echo 'Could not run query: ' . mysql_error();
                    exit;
                }
               
                $arr=Array();
                while($row=$result->FetchRow()){
                    $arr[] = $row;
                }

                if (version_compare(PHP_VERSION,"5.2","<"))
                    {
                        require_once("JSON.php"); //if php<5.2 need JSON class
                        $json = new Services_JSON();//instantiate new json object
                        $data=$json->encode($arr);  //encode the data in json format
                    } else
                    {
                        $data = json_encode($arr);  //encode the data in json format
                    }
                    echo '({"results":' . $data . '})';
        }

}

//Call the function and pass it our array

?>


