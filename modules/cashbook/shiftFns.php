<?php
require_once('roots.php');
require($root_path.'include/inc_environment_global.php');

$cashpoint=$_REQUEST[cashPoint];
$User = $_SESSION['sess_login_username'];
$receiptNo=$_REQUEST[receiptDetails];

if($cashpoint){
    checkPoint($cashpoint,$User);
}elseif(receiptDetails){
    getReceiptDetails($receiptNo);
}

function getReceiptDetails($receiptNo){
    global $db;
    $sql="SELECT cash_point,shift_no,ref_no FROM care_ke_receipts WHERE cash_point='$receiptNo'";
    // echo $sql;
    $result=$db->Execute($sql);
    if (!$result) {
        echo 'Could not run query: ' . mysql_error();
        exit;
    }

    $row=$result->FetchRow();

    echo $row[cash_point].','.$row[shift_no];

}

function checkPoint($cashpoint,$User){
    global $db;
    $sql="SELECT pcode,`name`,cashier,next_receipt_no,next_voucher_no,`active`,prefix FROM care_ke_cashpoints 
    WHERE pcode='$cashpoint'";
   // echo $sql;
               $result=$db->Execute($sql);
                if (!$result) {
                    echo 'Could not run query: ' . mysql_error();
                    exit;
                }

                $row=$result->FetchRow();

                echo $row[1].','.$row[3].','.$row[4].','.$row[6];

}

?>