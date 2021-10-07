<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require('roots.php');
//require($root_path . 'include/inc_environment_global.php');

$task = ($_POST['task']) ? ($_POST['task']) : $_REQUEST['task'];
$pid = $_REQUEST['pid'];
$partCode=$_REQUEST[partCode];
$storeID=$_REQUEST[storeID];
$rowNo=$_REQUEST[rowNo];
switch ($task) {
    case "getDOB":
        getDOB();
        break;
    case "getAge":
        getAge();
        break;
    case "getHtcReasons":
        getHtcReasons();
        break;
    case "getQuantityByItem":
        getQuantityByItem($partCode,$storeID,$rowNo);
        break;
    default:
        echo "{failure:true}";
        break;
}//

function getQuantityByItem($partCode,$storeID,$rowNo){
    global $db;
    $debug=false;
    
    $sql="Select Quantity from care_ke_locstock where stockid='$partCode' and loccode='$storeID'";
    if($debug) echo $sql;
    $results=$db->Execute($sql);
    $rcount=$results->RecordCount();
    $row=$results->FetchRow();
    if($rcount>0){
        echo $row[0].",".$rowNo;
    }else{
        echo "0,".$rowNo;
    }
    
    
}

function getHtcReasons(){
    global $db;
    $debug=false;

    $sql="Select ID,Description from care_ke_Reasons where rType='Htc'";
    if($debug) echo $sql;

    $results=$db->Execute($sql);

    $strhtc="<SELECT id='htc_reason' name='htc_reason'>";
    $strhtc=$strhtc."<OPTION></OPTION>";
    while($row=$results->FetchRow()){
        $strhtc.="<OPTION Value='$row[Description]'>$row[Description]</OPTION>";
    }

    $strhtc.="</SELECT>";

    echo $strhtc;
}

function dateDiff($dformat, $endDate, $beginDate) {
    $split_endDate = explode(" ",$endDate );
    $split_beginDate = explode(" ",$beginDate );

    $date_parts1 = explode($dformat, $split_beginDate[0]);
    $date_parts2 = explode($dformat, $split_endDate[0]);

    $start_date = gregoriantojd($date_parts1[1], $date_parts1[2], $date_parts1[0]);

    $end_date = gregoriantojd($date_parts2[1], $date_parts2[2], $date_parts2[0]);

    return $days = $end_date - $start_date;
}

//end of function

function yearcalculate($days) {
    $year = floor($days / 365);
    $monthday = $days % 365;
    $month = round($monthday / 30);

    if ($year == 0) {
        if ($month == 1) {
            $str = $month;
        } else if ($month == 0) {
            $str = "No experience";
        } else {
            $str = $month;
        }
    } //end of main if statement
    else {
        if ($year == 1) {

            if ($month == 1) {
                $str = $year . ",".$month;
            } else if ($month == 0) {
                $str = $year;
            } else {
                $str = $year . ",".$month;
            }
        } //end of year=1 if statement
        else {
            if ($month == 1) {
                $str = $year . ",".$month;
            } else if ($month == 0) {
                $str = $year ;
            } else {
                $str = $year . ",".$month;
            }
        }
    } //end of main else statement
    return $str;
}

function getDOB() {
    $dte = date('Y-m-d');
    $age = $_GET["age"];
    $mnts = $_GET["months"];

    if (!isset($_GET["months"]) || empty($_GET["months"])) {
        $mnts = 0;
    }
    if (!isset($_GET["age"]) || empty($_GET["age"])) {
        $age = 0;
    }
    $date = new DateTime($dte);
    date_sub($date, new DateInterval("P" . $age . "Y" . $mnts . "M"));
    $newdate = $date->format("d-m-Y");

    echo $newdate;
}


function getAge() {
    $dte =date('Y-m-d');
    $dob = $_GET["dob"];

     $date = new DateTime($dob);
    $sdob=$date->format("Y-m-d");


    $days = dateDiff("-", $dte, $sdob);
    return yearcalculate($days);
}
?>
