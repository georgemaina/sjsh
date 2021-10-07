<?php
require('./roots.php');
require('include/inc_environment_global.php');

global $db;

$sql="SELECT * FROM care_ke_billing WHERE encounter_nr='' AND `ip-op`=1 and pid<>''";
$result=$db->Execute($sql);
while($row=$result->FetchRow()){
    echo $row[pid].'  '.$row[encounter_nr].'  '.$row[bill_date].' <br>';
    $sql1="SELECT pid,encounter_nr,bill_number FROM care_ke_billing WHERE pid= $row[pid] AND encounter_nr<>'' AND `ip-op`=1";
    echo '<br>'.$sql1.'<br>';
    $result1=$db->Execute($sql1);
    $row2=$result1->FetchRow();
//    echo ' =========> encounter No is '.$row2[0].' '.$row2[1].' '.$row2[2].'<br>';
//    $sql3="Update care_ke_billing set encounter_nr='$row2[1]' WHERE bill_number='$row[bill_number]' AND 
//                `ip-op`='1' and pid='$row[pid]'";
//    $db->Execute($sql3);
//     echo ' ====================================================================================><br>';
//     echo 'sql executed: ' .$sql3.'<br>';
//      echo ' ====================================================================================><br>';
}
echo 'Total Records '.$result->RecordCount();

?>