<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');
$limit = $_REQUEST['limit'];
$start = $_REQUEST['start'];
$sparam = $_REQUEST['sParam'];
$startDate=$_REQUEST['startDate'];
$endDate=$_REQUEST['endDate'];


//getSlips();

$task = ($_POST['task']) ? ($_POST['task']) : $_REQUEST['task'];
switch ($task) {
    case "getSlips":
        getSlips($sparam,$startDate,$endDate,$limit, $start);
        break;

    default:
        echo "{failure:true}";
        break;
}//end switch

function getSlips($sparam,$startDate,$endDate,$limit, $start) {
    global $db;
	$debug=false;
    $sql = "SELECT s.pid,s.Slip_no,s.slip_date,p.name_first,p.name_last,p.name_2,c.id,c.accno,c.name,s.slip_time FROM care_ke_slips s
            LEFT JOIN care_person p ON s.pid=p.pid INNER JOIN care_tz_company c ON c.id=p.insurance_ID where s.pid<>''";


    if (is_numeric($sparam)) {
        $sql = $sql . " and s.pid= $sparam";
    }
    if(isset($startDate) and isset($endDate)){
    	$sql=$sql. " and s.slip_date between '$startDate' and '$endDate'";
    }

    if(!is_numeric($sparam)&& $sparam<>''){
        $sql = $sql . " and name_first like '%$sparam%' or name_last like '%$sparam%'
                 or accno like '%$sparam%' or c.name like '%$sparam%'";
    }

    $sql = $sql . " order by ID desc";

   if(isset($start) && isset($limit)){
        $sql.=" limit $start,$limit";
    }

   if($debug) echo $sql;
    $request2 = $db->Execute($sql);

    $sql1="Select count(pid) as totai from care_ke_slips";
    $results1=$db->Execute($sql1);
    $row=$results1->FetchRow();
    $total = $row[0];

    echo '{"total":"' . $total . '","slips":[';
    $counter = 0;
    //echo "sdsds";
    while ($row = $request2->FetchRow()) {

        $pid = $row[0];
        $slipNo =  $row[1];
        $slipDate = $row[slip_date];
        $name_first =$row[3];
        $name_last =  $row[4];
        $name_2 = $row[5];
        $slipTime=$row[slip_time];

        echo '{"id":"' . $row[6] . '","pid":"' . $pid . '","slipNo":"' . $slipNo . '",
       "slipDate":"' . $slipDate . '","slipTime":"' . $slipTime . '","Names":"' . $name_first . ' ' . $name_last . ' ' . $name_2 .'",
           "accNo":"' . trim($row[7]) .'","accName":"' . trim($row[8]) . '"}';

        $counter++;

        if ($counter < $total) {
            echo ",";
        }

    }
    echo ']}';
}

?>
