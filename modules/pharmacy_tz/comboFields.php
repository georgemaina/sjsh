
<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');

$task = ($_POST['task']) ? ($_POST['task']) : null;
$pid= ($_POST['pid']) ? ($_POST['pid']) : 'P001';
$bankID= ($_POST['bankID']) ? ($_POST['bankID']) : '1';

switch ($task) {
    case "getEmpType":
        getEmpType();
        break;
    case "getEmpDept":
        getEmpDept();
        break;
    case "getEmpTitle":
        getEmpTitle();
        break;
     case "getBanks":
        getBanks();
        break;
    case "getBranch":
        getBranch($bankID);
        break;
     case "getPaye":
        getPaye($pid);
        break;
    default:
        echo "{failure:true}";
        break;
}//end switch

function getPaye($pid) {
    global $db;
    $sql = 'SELECT `ID`, `EmpType`, `Duration` FROM `proll_emptypes`';
    $result = $db->Execute($sql);
    $numRows = $result->RecordCount();
    echo '{
    "getEmpTypes":[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        echo '{"ID":"' . $row[0] .'","EmpType":"' . $row[1] . '","Duration":"' . $row[2] . '"}';
        if ($counter < $numRows) {
            echo ",";
        }
        $counter++;
    }
    echo ']}';
}

function getEmpType($pid) {
    global $db;
    $sql = 'SELECT `ID`, `EmpType`, `Duration` FROM `proll_emptypes`';
    $result = $db->Execute($sql);
    $numRows = $result->RecordCount();
    echo '{
    "getEmpTypes":[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        echo '{"ID":"' . $row[0] .'","EmpType":"' . $row[1] . '","Duration":"' . $row[2] . '"}';
        if ($counter < $numRows) {
            echo ",";
        }
        $counter++;
    }
    echo ']}';
}

function getEmpDept() {
    global $db;
    $sql = 'SELECT ID,`Name` FROM proll_departments';
    $result=$db->Execute($sql);
    $numRows=$result->RecordCount();
    echo '{
    "getEmpDept":[';
    $counter=0;
    while ($row = $result->FetchRow()) {
        echo '{"ID":"'. $row[0].'","DeptName":"'. $row[1].'"}';
        if ($counter<>$numRows) {
            echo ",";
        }
        $counter++;
    }
    echo ']}';
}

function getEmpTitle() {
    global $db;
    $sql = 'SELECT p.`ID`, p.`JobTitle` FROM proll_jobtitles p';
    $result=$db->Execute($sql);
    $numRows=$result->RecordCount();
    echo '{
    "getEmpTitle":[';
    $counter=0;
    while ($row = $result->FetchRow()) {
        echo '{"ID":"'. $row[0].'","JobTitle":"'. $row[1].'"}';
        if ($counter<>$numRows) {
            echo ",";
        }
        $counter++;
    }
    echo ']}';
}

function getBanks() {
    global $db;
    $sql = 'SELECT p.`bankID`, p.`BankName`, p.`BankCode`, p.`branchCount` FROM proll_banks p';
    $result=$db->Execute($sql);
    $numRows=$result->RecordCount();
    echo '{
    "getBanks":[';
    $counter=0;
    while ($row = $result->FetchRow()) {
        echo '{"bankID":"'. $row[0].'","BankName":"'. $row[1].'"}';
        if ($counter<>$numRows) {
            echo ",";
        }
        $counter++;
    }
    echo ']}';
}

function getBranch($bankid) {
    global $db;
    $sql = 'SELECT p.`BranchID`, p.`BankID`, p.`BankBranch`, p.`BankBranchCode` FROM proll_bankbranches p where p.`bankid`="'.$bankid.'"';
    $result=$db->Execute($sql);
    $numRows=$result->RecordCount();
    echo '{
    "getBranch":[';
    $counter=0;
    while ($row = $result->FetchRow()) {
        echo '{"BranchID":"'. $row[0].'","BankBranch":"'. $row[2].'"}';
        if ($counter<>$numRows) {
            echo ",";
        }
        $counter++;
    }
    echo ']}';
}
?>

