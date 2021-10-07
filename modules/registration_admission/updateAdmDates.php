<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');

$task = ($_POST['task']) ? ($_POST['task']) : ($_REQUEST['task']);

switch ($task) {
    case "updateAdm":
        updateAdmDate();
        break;
    
    default:
        echo "{failure:true}";
        break;
}//end switch


function updateAdmDate() {
    global $db;
    $pid = $_POST[pid];
    $encNr = $_POST[encNo];
    
    $admDate=new DateTime($_POST[admDate]);
    $newAdmdate = $admDate->Format('Y-m-d');
    
    $disDate=new DateTime($_POST[disDate]);
    $newDisDate=$disDate->Format('Y-m-d');

    $sql = "update care_encounter set encounter_date='$newAdmdate',discharge_date='$newDisDate' where encounter_nr='$encNr' and pid=$pid";
    $result = $db->Execute($sql);
     echo $sql;
     
    $sql = "update care_encounter_location set date_from='$newAdmdate',date_to='$newDisDate' where encounter_nr='$encNr'";
    $result = $db->Execute($sql);
    echo $sql;
    echo '{success:true}';
}

?>

