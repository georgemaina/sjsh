<?php
require('./roots.php');
require('include/inc_environment_global.php');

$sql="select item_id,name from care_tz_laboratory_param where item_id<>''";
$results=$db->Execute($sql);
while($row=$results->FetchRow()){
    $sql2="update care_tz_drugsandservices set partcode='$row[item_id]',item_number='$row[item_id]' 
    where item_description='$row[name]'";
    if($db->Execute($sql2)){
        echo "updated successfully $row[name] $sql2<br>";
    }
}