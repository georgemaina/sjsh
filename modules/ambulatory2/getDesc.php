<?php

require_once('roots.php');
require($root_path . 'include/inc_environment_global.php');
$desc = $_GET[rev];
if (!empty($_REQUEST['callerID'])) {
    $caller = $_REQUEST['callerID'];
} else {
    $caller = $_POST['callerID'];
}
//$pid = $_GET[desc3];
if (!empty($_GET['pid'])) {
    $pid = $_GET['pid'];
} else if (empty($_POST['pid'])) {
    $pid = $_POST['pid'];
} else {
    $pid = $_REQUEST['pid'];
}


//$caller = ($_POST['caller']) ? ($_POST['caller']) : $_POST['callerID'];

switch ($caller) {
    case "debit":
        getPNames($pid);
        break;
    case "grid":
        getDescription($desc);
        break;
    case "checkInvoice":
        checkInvoice($pid);
        break;
    case "getBillNumbers":
        getBillNumbers($db, $pid);
        break;
    case "getSlipPatient":
        getSlipPatient($pid);
        break;
    default:
        echo "{failure:true}";
        break;
}//end switc

function getSlipPatient($pid){
    global $db;
    $debug=true;
    
    $sql="SELECT CONCAT(b.name_first,' ',b.name_2,' ',b.name_last) AS pnames,a.encounter_nr,a.encounter_class_nr,a.pid from care_person b
                inner join care_encounter a on a.pid=b.pid WHERE b.pid='$pid' and b.insurance_id<>'-1'";
    
    $result=$db->Execute($sql);
    if (!$result) {
        echo 'Could not run query: ' . mysql_error();
        exit;
    }

    $row=$result->FetchRow();
    if($result->RecordCount()>0){
         echo $row[pnames].",true"; // 42
    }else{
         echo "1,".$row[2]; // 42
    }
  
}

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

        echo $row[0] . "," . $row[1] . "," . $row[2] . "," . $rowID; // 42
        //echo "Laboratory $rowID";
    } else {
        echo "....";
    }
}

function getPNames($desc3) {
     global $db;
    if ($desc3) {
        $sql = "SELECT b.name_first,b.name_2,b.name_last,max(a.encounter_nr) as encounter_nr,a.encounter_class_nr
                from care_person b
                inner join care_encounter a on a.pid=b.pid 
                WHERE b.pid='$desc3' and a.encounter_class_nr=2";
        // $result = mysql_query("SELECT name,next_receipt_no FROM care_ke_cashpoints WHERE pcode='$desc2'");
//        echo $sql;
        $result = $db->Execute($sql);
        if (!$result) {
            echo 'Could not run query: ' . mysql_error();
            exit;
        }
        $row = $result->FetchRow();

        $sql2 = "SELECT newdebitNo from care_ke_invoice";
        $result2 = $db->Execute($sql2);
        $row2 = $result2->FetchRow();
        if ($row2[0] <> '')
            $debitNo = $row2[0];
        else
            $debitNo = 'D10001';

        echo $row[0] . "," . $row[1] . "," . $row[2] . "," . $row[3] . "," . $row[4] .
         "," . $debitNo; // 42
    } else {
        echo "---";
  }
}

function checkInvoice($pid) {
    global $db;
//    if ($pid) {
        $billNumber=$_REQUEST[billNumber];
        $sql = "select pid,bill_number from care_ke_billing where pid=$pid and finalised=0";
        $result = $db->Execute($sql);
//        echo $sql;
        if (!$result) {
            echo 'Could not run query: ' . mysql_error();
            exit;
        }

        $row = $result->FetchRow();
        $rowCount = $result->Recordcount();

        echo $rowCount . ',' . $pid.','.$billNumber; // 42
        //echo "Laboratory $rowID";
//    } else {
//        echo "0";
//    }
}

//getBillNumbers($pid2);
function getBillNumbers($db, $pid) {
    global $db;
    $debug = false;
    $sql = "select DISTINCT bill_number from care_ke_billing where pid=$pid 
    and `IP-OP`=2 order by bill_date desc";
    if ($debug)
        echo $sql;
    $billNos = '';
    if ($results = $db->Execute($sql)) {
        $billNos = $billNos . "<form name='bills'><select id='billNumbers' name='billNumbers'>
            <option>--Select Bill No--</option>";
        while ($rows = $results->FetchRow()) {
//             if($row[0]<>''){
            $billNos = $billNos . "<option value='$rows[0]'>$rows[0]</option>";
//             }
        }
        $billNos = $billNos . "</select><forms>";
    } else {
          $billNos = 'Error runing qry:'.$sql;
    }
    echo $billNos;
}

?>
