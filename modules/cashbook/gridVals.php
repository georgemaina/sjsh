<?php
require_once('roots.php');
require($root_path.'include/inc_environment_global.php');
$desc=$_GET[rev];
$desc2=$_GET[rev2];
$desc3=$_GET[desc3];
$rowID=$_GET[rowID];

$Pcode=$_REQUEST[cashPoint];
$name=$_REQUEST[desc];
$prefix=$_REQUEST[prefix];
$delete=$_REQUEST[delete];

if($desc) {

    $sql="select item_description, unit_price,partcode,purchasing_class from care_tz_drugsandservices WHERE partcode='$desc'";
    $result=$db->Execute($sql);
    if (!$result) {
        echo 'Could not run query: ' . mysql_error();
       exit;
    }

    $row=$result->FetchRow();

    echo $row[0].",".$row[1].",".$rowID.",".$row[2].",".$row[3]; // 42
    //echo "Laboratory $rowID";
} else if($desc2) {
    $sql="select item_description, unit_price,partcode,purchasing_class from care_tz_drugsandservices WHERE partcode='$desc2'";
    $result=$db->Execute($sql);
    if (!$result) {
        echo 'Could not run query: ' . mysql_error();
       exit;
    }

    $row=$result->FetchRow();

    echo $row[0].",".$row[1].",".$rowID.",".$row[2].",".$row[3]; // 42
    //echo "Laboratory $rowID";
} else if($desc3) {
    $sql="INSERT INTO `care_ke_cashpoints`
                (`Pcode`,`active`,`name`,`prefix`,`next_receipt_no`,`next_voucher_no`,`next_shift_no`,`current_shift`) 
                VALUES('$Pcode','0','$name','$prefix','10001','10001','1','0');";
    
    $result=$db->Execute($sql);
       
    if (!$result) {
        echo 'Could not run query: ' .$sql;
       exit;
    }else{
        echo 'success';
    }

   // echo $row[0].",".$row[1].",".$rowID.",".$row[2].",".$row[3]; // 42
    //echo "Laboratory $rowID";
}else if($delete) {
    $id=$delete;
    $sql="delete from care_ke_billing where id=$id";
    $result=$db->Execute($sql);
    if (!$result) {
        echo 'Could not run query: ' . mysql_error();
       exit;
    }
    
    echo '1'; // 42
    //echo "Laboratory $rowID";
}else{
    echo "....";
}



?>
