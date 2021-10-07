<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'roots.php';
require($root_path . 'include/inc_environment_global.php');

$debug = false;

$pid = $_REQUEST['pid'];
$inputUser= $_SESSION['sess_login_username'];


$sql2 = 'Select b.pid,b.name_first,name_last,name_2 from care_person b left join care_encounter c 
    on b.pid=c.pid where c.pid="' . $pid . '"';
$result2 = $db->Execute($sql2);
$row2 = $result2->FetchRow();

if ($debug)echo $sql2;

$sql1 = 'select pid,slip_date from care_ke_slips where pid="' . $row2[0] . '" and slip_date="'.date('Y-m-d').'"';
$result1 = $db->Execute($sql1);
$row1 = $result1->FetchRow();
if ($debug)
        echo $sql1;

if ($row1[1] == date('Y-m-d') && $row1[0] == $row2[0]) {
    echo 'error,1';
} else {
    $sql1='SELECT MAX(slip_no) as slipno FROM care_ke_slips';
     $result1 = $db->Execute($sql1);
     $row1=$result1->FetchRow();
     
     $newSlipNo=intval($row1[0])+1;
    
    $sql3 = 'INSERT INTO `care_ke_slips`
            (`Slip_no`,`pid`,`slip_date`,`slip_time`,`served_by`)
VALUES ("' . $newSlipNo . '","' . $pid . '","' . date('Y-m-d') . '","' . date('H:i:s') . '","'.$inputUser.'")';
    $result3 = $db->Execute($sql3);

    if ($debug)
        echo $sql3;

    echo $pid.",".$newSlipNo.",".$inputUser;
}
?>
