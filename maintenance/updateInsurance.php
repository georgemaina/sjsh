<?php
require('roots.php');
require($root_path . 'include/inc_environment_global.php');

global $db;

$sql="select id,accno from care_tz_company";
$result=$db->Execute($sql);
while($row=$result->FetchRow()){
    echo $row[0].'  '.$row[1].' <br>';
    $sql2="select pid from care_person where insurance_id=$row[0]";
    $result2= $db->Execute($sql2);
    while($row2=$result2->FetchRow()){
        $sql1="UPDATE care_ke_billing SET insurance_id='$row[0]' WHERE PID=$row2[0]";
        echo '<br>'.$sql1.'<br>';
        $db->Execute($sql1);
    }
   
}
echo 'Total Records '.$result->RecordCount();

?>