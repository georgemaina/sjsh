<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');

$task = ($_POST['task']) ? ($_POST['task']) : ($_REQUEST['task']);
$fileNo = $_REQUEST['fileNo'];
$updateStat= $_REQUEST['update'];

switch ($task) {
    case "validate":
        validateFileNo($fileNo,$updateStat);
        break;

    default:
        echo "{failure:true}";
        break;
}//end switch

function validateFileNo($fileNo,$updateStat) {
    global $db;

    $sql = "SELECT count(SELIAN_PID) as countPid FROM CARE_PERSON WHERE SELIAN_PID='$fileNo'";
    $result = $db->Execute($sql);
    $row = $result->FetchRow();
    
    if ($row[0]>0) {
        echo '1';
    } else {
        echo '0';
    } 
}
?>

