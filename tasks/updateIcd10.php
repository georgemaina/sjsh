<?php
require('./roots.php');
require('include/inc_environment_global.php');

$sql="select ID, diagnosis_code from OP ";
$results=$db->Execute($sql);
while($row=$results->FetchRow()){
    $sql2="update care_icd10_en set sub_level='$row[ID]' where diagnosis_code='$row[diagnosis_code]'";
    if($db->Execute($sql2)){
        echo "updated successfully $row[diagnosis_code] $sql2<br>";
    }
}