<?php
require_once('roots.php');
require($root_path.'include/inc_environment_global.php');
require_once($root_path.'include/care_api_classes/class_tz_billing.php');
$bill_obj = new Bill;
$desc=$_REQUEST['desc'];
$desc2=$_REQUEST['desc2'];
$desc3=$_REQUEST['desc3'];
$desc4=$_REQUEST['desc4'];
$desc5=$_REQUEST['desc5'];
$desc6=$_REQUEST['desc6'];
$desc7=$_REQUEST['desc7'];
$desc8=$_REQUEST['desc8'];
$desc10=$_REQUEST['desc10'];
$pointCode=$_REQUEST['pointCode'];
$cashpoint=$_REQUEST['cashpoint'];
$payMode=$_REQUEST['payMode'];
$PayDesc=$_REQUEST['PayDesc'];
$Prefix=$_REQUEST['Prefix'];
$cashier=$_REQUEST['cashier'];
$suppID=$_REQUEST['suplierid'];
$gl=$_REQUEST['GL'];
$glDesc=$_REQUEST['glDesc'];
$glCode=$_REQUEST['glCode'];

$pid=$_REQUEST['PID'];
$accno=$_REQUEST['accNo'];
$caller=$_REQUEST['callerID'];
$encNr=$_REQUEST['encNo'];

$ids=$_REQUEST['ids'];

if(!isset($_REQUEST['cashpoint'])){
    $strp=$_REQUEST['point'];
}else{
    $strp=$_REQUEST['cashpoint'];
}

$accDB=$_SESSION['sess_accountingdb'];
$pharmLoc=$_SESSION['sess_pharmloc'];

if($caller=='closePayments'){
    $debug=false;

    $sql="Update care_ke_payments set printed ='1' where ID in($ids)";
    if($debug) echo $sql;

    if($db->Execute($sql)){
        echo "{success:true}";
    }else{
        echo "{failure:true}";
    }
}else if($caller=='paymentMode'){
    if($desc10) {
        $sql="INSERT INTO `care_ke_paymentmode`
                    (`cash_point`,`Payment_mode`,`Description`,`GL_Account`,`GL_Desc`)
            VALUES ('$cashpoint','$payMode','$PayDesc','$gl','$glDesc')";

        $result=$db->Execute($sql);
        if (!$result) {
            echo 'Could not run query: ' . " "();
            exit;
        }else{
            echo '1';
        }
    }
}else if($caller=='glCode'){
    if($glCode) {
        $sql="SELECT accountcode,accountname FROM $accDB.chartmaster WHERE accountcode='$glCode'";
        $result=$db->Execute($sql);
        if (!$result) {
            echo 'Could not run query: ' . " "();
            exit;
        }

        $row=$result->FetchRow();

        echo $row[1]; // 42

    } else {
        echo "Error: GL Code";
    }
}else if($caller=='points'){
            if($desc) {
               $sql="SELECT name,next_receipt_no FROM care_ke_cashpoints WHERE pcode='$desc'";
               $result=$db->Execute($sql);
                if (!$result) {
                    echo 'Could not run query: ' . " "();
                    exit;
                }

                $row=$result->FetchRow();

                echo $row[0].",".$row[1]; // 42

            } //else {
//                echo "---";
//            }
}else if($caller=='points2'){
            if($desc7) {
               $sql='SELECT pcode FROM care_ke_cashpoints WHERE cashier="'.$cashier.'" AND active=1 AND pcode="'.$desc7.'"';
               $result=$db->Execute($sql);
                if (!$result) {
                    echo 'Could not run query: ' . " "();
                    exit;
                }

                $row=$result->FetchRow();

                echo $row[0]; // 42

            }
//            else {
//                echo "---";
//             }
}else if($caller=='paymode'){
    if($desc2) {
             
                $sql="SELECT description,GL_Account FROM care_ke_paymentmode WHERE payment_mode='$desc2' and cash_point='$strp'";
               // $result = mysql_query("SELECT name,next_receipt_no FROM care_ke_cashpoints WHERE pcode='$desc2'");
               $result=$db->Execute($sql);
                if (!$result) {
                    echo 'Could not run query: ';
                    exit;
                }

                $row=$result->FetchRow();

                echo $row[0].",".$row[1]; // 42

            }
//            else {
//                echo "---";
//            }
}else if($caller=='patient'){
            if($desc3) {
                $sql="SELECT  b.name_first,b.name_2,b.name_last,MAX(a.encounter_nr) AS encounter_nr,a.pid,MAX(a.`bill_number`) AS bill_number,a.`encounter_class_nr`
                        FROM care_encounter a
                        LEFT JOIN care_person b ON a.pid=b.pid                               
                    WHERE b.pid='$desc3'
                    GROUP BY a.`pid`";
               // $result = mysql_query("SELECT name,next_receipt_no FROM care_ke_cashpoints WHERE pcode='$desc2'");
               $result=$db->Execute($sql);
//               echo $sql;
                if (!$result) {
                    echo 'Could not run query: ';
                    exit;
                }

                $row=$result->FetchRow();

                echo $row['name_first'].",".$row['name_2'].",".$row['name_last'].",".$row['encounter_nr'].",".$row['pid'].",".$row['bill_number'].",".$row['encounter_class_nr']; // 42
            }

}else if($caller=='bill'){
            if($desc8) {
                $sql="SELECT distinct b.bill_number FROM care_ke_billing b 
                    INNER JOIN care_encounter e ON b.pid=e.pid WHERE 
                    b.encounter_nr=$encNr";
                 $result1=$db->Execute($sql);
                 $row1=$result1->FetchRow();
                 $newBillNumber=$row1[0];

                echo $newBillNumber; // 42

            }
//            else {
//                echo "---";
//            }
}else if($caller=='filterCount'){
            if($desc4) {
                $sql="SELECT count(pid) as pidcount from care_ke_billing
                    Where `IP-OP`='2' and pid like '".$desc4."%'";
               // $result = mysql_query("SELECT name,next_receipt_no FROM care_ke_cashpoints WHERE pcode='$desc2'");
               $result=$db->Execute($sql);
                if (!$result) {
                    echo 'Could not run query: ' . " "();
                    exit;
                }

                $row=$result->FetchRow();

                echo $row[0]; // 42

            } //else {
//                echo "---";
//            }
 }else if($caller=='updateGrid'){
            if($desc5) {
                $sql="SELECT service_type,bill_number,item_number,Description,price,qty,total from care_ke_billing
                    Where `IP-OP`='2' and pid like '".$desc5."%'";
               // $result = mysql_query("SELECT name,next_receipt_no FROM care_ke_cashpoints WHERE pcode='$desc2'");
               $result=$db->Execute($sql);
                if (!$result) {
                    echo 'Could not run query: ' . " "();
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

}else if($caller=='Payments'){
            if($pointCode) {
               $sql="SELECT name,next_voucher_no FROM care_ke_cashpoints WHERE pcode='$pointCode'";
               $result=$db->Execute($sql);
                if (!$result) {
                    echo 'Could not run query: ' . " "();
                    exit;
                }

                $row=$result->FetchRow();

                echo $row[0].",".$row[1]; // 42

            } //else  {
//                echo "---";
//            }
}else if($caller=='payDesc'){
            if($payMode) {
               $sql="select payment_mode,description,GL_Account,GL_Desc,nextChequeNo from care_ke_paymentmode 
                   where cash_point='$cashpoint' and payment_mode='$payMode'";
               //echo $sql;

               $result=$db->Execute($sql);
                if (!$result) {                                                                                                          
                    echo 'Could not run query: ' . " "();
                    exit;
                }

                $row=$result->FetchRow();

                echo $row[1].",".$row[2].",".$row[3].",".$row[4]; // 42

            } //else  {
//                echo "---";
//            }
}else if($caller=='Suppliers'){
            if($suppID) {
               $sql="SELECT suppname FROM suppliers where supplierid='$suppID'";
               $result=$db->Execute($sql);
                if (!$result) {
                    echo 'Could not run query: ' . " "();
                    exit;
                }

                $row=$result->FetchRow();

                echo $row[0];

            } //else
//                echo "---";
//            }
}else if($caller=='GL'){
            if($gl) {
               $sql="SELECT accountcode,accountname FROM $accDB.chartmaster WHERE accountcode='$gl'";
               $result=$db->Execute($sql);
                if (!$result) {
                    echo 'Could not run query: ' . " "();
                    exit;
                }

                $row=$result->FetchRow();

                echo $row[0];

            } //else  {
//                echo "Nul";
//            }
}else if($caller=='Patients'){
            if($pid) {
               $sql="SELECT p.pid,p.name_first,p.name_2,p.name_last FROM care_person p LEFT JOIN care_encounter e ON p.pid=e.pid
                WHERE P.pid='$pid'";
               $result=$db->Execute($sql);
                if (!$result) {
                    echo 'Could not run query: ' . " "();
                    exit;
                }
//                echo $sql;

                $row=$result->FetchRow();
                $pid=$row[1].' '.$row[2].' '.$row[3];
                echo $pid;

            } //else  {
//                echo "---";
//            }
}else if($caller=='debtors'){
            if($accno) {
               $sql="SELECT accno,name,category,os_bal,last_trans FROM care_ke_debtors where accno='$accno'";
               $result=$db->Execute($sql);
                if (!$result) {
                    echo 'Could not run query: ' . " "();
                    exit;
                }

                $row=$result->FetchRow();
                $debtor=$row[1].','.$row[2].','.$row[3];
                echo $debtor;

            } //else  {
//                echo "---";
//            }
}else if($caller=='getLastPayment'){
            if($accno) {
               $sql="SELECT accno,name,category,os_bal,last_trans FROM care_ke_debtors where accno='$accno'";
               $result=$db->Execute($sql);
                if (!$result) {
                    echo 'Could not run query: ' . " "();
                    exit;
                }

                $row=$result->FetchRow();
                $debtor=$row[1].' '.$row[2].' '.$row[3];
                echo $debtor;

            } //else  {
              //  echo "---";
           // }
}



//Call the function and pass it our array

?>


