<?php

require('roots.php');
require($root_path . 'include/inc_environment_global.php');

global $db;

$sql = "SELECT distinct pid,encounter_nr,bill_number FROM care_ke_billing WHERE `ip-op`='1' AND bill_date>'2011-10-31'";
$result = $db->Execute($sql);
//echo '---------------------------------------------------------------------------------<br>';
//echo $sql.'<br>';
//echo '---------------------------------------------------------------------------------<br>';
while ($row = $result->FetchRow()) {

    $sql1 = "SELECT pid,encounter_nr,bill_number,bill_date,count(bill_number) as coun FROM care_ke_billing WHERE
    `ip-op`=1 and bill_number<>'$row[bill_number]' AND bill_date>'2011-10-31' group by bill_number having count(pid)>1";
//    
    if($result1 = $db->Execute($sql1)){
    while ($row2 = $result1->FetchRow()) {
//        echo $row2[pid] . '  ' . $row2[encounter_nr] . '  ' . $row2[bill_date] . '  ' . $row2[bill_number] . ' <br>';
        $sql3 = "SELECT bill_number,encounter_nr FROM care_ke_billing WHERE
            `ip-op`=1 and bill_number<>'$row2[bill_number]' and pid='$row2[pid]' and 
        encounter_nr='$row2[encounter_nr]' AND bill_date>'2011-10-31'";
        if ($result3 = $db->Execute($sql3)) {
            echo 'PID'.$row2[pid].'<br>';
            while ($row3 = $result3->FetchRow()) {
                echo ' =========> Wring Bill No is '.$row2[pid].' ' . $row3[0] .' ' . $row3[1] .'<br>';
            }
        }
//        $sql3 = "Update care_ke_billing set bill_number='$row2[2]' WHERE 
//                `ip-op`='1' and pid='$row2[pid]' and encounter_nr='$row2[encounter_nr]'";
//        $db->Execute($sql3);
//        echo ' ====================================================================================><br>';
//        echo 'sql executed: ' . $sql3 . '<br>';
//        echo ' ====================================================================================><br>';
    }
    }
//   
}
echo 'Total Records ' . $result1->RecordCount();
?>