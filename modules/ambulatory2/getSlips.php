<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');
$limit = $_POST[limit];
$start = $_POST[start];
$sparam = $_POST[sparam];


//getSlips();

$task = ($_POST['task']) ? ($_POST['task']) : $_REQUEST['task'];
switch ($task) {
    case "getSlips":
        getSlips($limit, $start);
        break;

    default:
        echo "{failure:true}";
        break;
}//end switch

function getSlips($limit, $start) {
    global $db;
    $sparam = $_POST[sparam];


    $sql = "SELECT s.pid,s.Slip_no,s.slip_date,p.name_first,p.name_last,p.name_2,c.id,c.accno,c.name FROM care_ke_slips s 
            LEFT JOIN care_person p ON s.pid=p.pid INNER JOIN CARE_tz_company c ON c.id=p.insurance_ID";
    if (is_numeric($sparam)) {
        $sql = $sql . " where pid= $sparam";
    } else {
        $sql = $sql . " where name_first like '%$sparam%' or name_last like '%$sparam%' or name_2 like '%$sparam%'";
    }

    $sql = $sql . " order by ID desc limit  $start,$limit";

//    echo $sql;
    $request2 = $db->Execute($sql);

    $total = $request2->RecordCount();

    echo '{"Total":"' . $total . '","slips":[';
    $counter = 0;
    //echo "sdsds";
    while ($row = $request2->FetchRow()) {

        $pid = $row[0];
        $slipNo =  $row[1];
        $slipDate = $row[2];
        $name_first =$row[3];
        $name_last =  $row[4];
        $name_2 = $row[5];

        echo '{"id":"' . $row[6] . '","pid":"' . $pid . '","slipNo":"' . $slipNo . '",
       "slipDate":"' . $slipDate . '","Names":"' . $name_first . ' ' . $name_last . ' ' . $name_2 .'",
           "accNo":"' . trim($row[7]) .'","accName":"' . trim($row[8]) . '"}';

        if ($counter < $total) {
            echo ",";
        }
        $counter++;
    }
    echo ']}';
}

?>
