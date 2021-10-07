<?php

require_once('roots.php');
require($root_path . 'include/inc_environment_global.php');
$desc = $_GET[rev];
 if(!empty($_GET['callerID'])){
      $caller =$_GET['callerID'];
  }else{
      $caller =$_POST['callerID'];
  }
$pid = $_GET[desc3];
$pid2 = $_POST[pid];

//$caller = ($_POST['caller']) ? ($_POST['caller']) : $_POST['callerID'];

switch ($caller) {
    case "debit":
        getPNames($pid);
        break;
    case "grid":
        getDescription($desc);
        break;
    case "checkInvoice":
        checkInvoice($desc);
        break;
     case "getBillNumbers":
        getBillNumbers($pid2);
        break;
    default:
        echo "{failure:true}";
        break;
}//end switc

function getDescription($desc) {
    global $db;
    if ($desc) {

        $sql = "select purchasing_class,item_description, unit_price from care_tz_drugsandservices WHERE partcode='$desc'";
        $result = $db->Execute($sql);
        if (!$result) {
            echo 'Could not run query: ' . mysql_error();
            exit;
        }

        $row = $result->FetchRow();

        echo $row[0] . "," . $row[1] . "," . $row[2]. "," . $rowID; // 42
        //echo "Laboratory $rowID";
    } else {
        echo "....";
    }
}

function getPNames($desc3) {
      global $db;
    if ($desc3) {
        $sql = "SELECT b.name_first,b.name_2,b.name_last,a.encounter_nr,a.encounter_class_nr,a.current_ward_nr from care_person b
                inner join care_encounter a on a.pid=b.pid WHERE b.pid='$desc3' and a.encounter_class_nr=2";
        // $result = mysql_query("SELECT name,next_receipt_no FROM care_ke_cashpoints WHERE pcode='$desc2'");
        $result = $db->Execute($sql);
        if (!$result) {
            echo 'Could not run query: ' . mysql_error();
            exit;
        }

        $row = $result->FetchRow();

        echo $row[0] . "," . $row[1] . "," . $row[2] . "," . $row[3] . "," . $row[4]. "," . $row[5]; // 42
    } else {
        echo "---";
    }
}

function checkInvoice() {
    global $db;
    if ($pid) {

        $sql = "select pid from care_ke_billing where pid=$pid and finalised=0";
        $result = $db->Execute($sql);
        if (!$result) {
            echo 'Could not run query: ' . mysql_error();
            exit;
        }

        $row = $result->FetchRow();
        $rowCount= $result->Recordcount();

        echo $rowCount.','.$pid; // 42
        //echo "Laboratory $rowID";
    } else {
        echo "0";
 }
}

//getBillNumbers($pid2);
function getBillNumbers($pid2){
    global $db;

        $sql = "select DISTINCT bill_number from care_ke_billing where pid=$pid2";

        $result = $db->Execute($sql);
        if (!$result) {
            echo 'Could not run query: ' . $sql;
            exit;
        }

        echo '{"bills":[';
        while ($row = $result->FetchRow()) {
            echo '{"billNumber":"' . $row[0] .'"},';
        }
        echo ']}';
   
}

?>
