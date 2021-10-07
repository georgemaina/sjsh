<?php
require_once('roots.php');
require($root_path.'include/inc_environment_global.php');
$desc=$_GET[rev];
$desc2=$_GET[rev2];
$rowID=$_GET[rowID];

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
}else{
    echo "....";
}



?>
