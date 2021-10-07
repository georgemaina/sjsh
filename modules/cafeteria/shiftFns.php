<?php
require_once('roots.php');
require($root_path.'include/inc_environment_global.php');

$cashpoint=$_REQUEST[cashPoint];
$User = $_SESSION['sess_login_username'];

if($cashpoint){
    checkPoint($cashpoint,$User);
}

function checkPoint($cashpoint,$User){
    global $db;
    $sql="SELECT pcode,`name`,cashier,next_receipt_no,next_voucher_no,`active`,prefix FROM care_ke_cashpoints 
    WHERE pcode='$cashpoint'";
               $result=$db->Execute($sql);
                if (!$result) {
                    echo 'Could not run query: ' . mysql_error();
                    exit;
                }

                $row=$result->FetchRow();
                
                if($row[2]==$User){
                    if($row[5]=0){
                        echo "1,Closed";
                    }else{
                         echo $row[1].','.$row[3].','.$row[4].','.$row[6];
                    }
                }else{
                    echo '2,'.$row[2];
                }

}

?>