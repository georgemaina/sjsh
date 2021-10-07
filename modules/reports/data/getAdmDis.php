<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');

$limit = $_POST[limit];
$start = $_POST[start];
$item_number = $_POST[item_number];
//getIPMorbidity(20, 1);
$task = ($_POST['task']) ? ($_POST['task']) : $_REQUEST['task'];
//$task='getDistype';
switch ($task) {
    case "getAdmDis":
        getAdmDis($limit, $start);
        break;
    case "getDistype":
        getDistype($limit, $start);
        break;
    default:
        echo "{failure:true}";
        break;
}//end switch

function getAdmDis($limit, $start) {
    global $db;
    echo '{
   "getAdmDis":[';

        $sql1 = 'SELECT p.pid,p.name_first,p.name_last,p.name_2,e.encounter_date,e.is_discharged,l.discharge_type_nr, l.date_from,l.date_to,
e.current_ward_nr FROM care_person p 
INNER JOIN care_encounter e ON p.pid=e.pid LEFT JOIN care_encounter_location l
ON e.encounter_nr=l.encounter_nr';
        $result1 = $db->Execute($sql1);
        while ($row = $result1->FetchRow()) {

            echo '{"pid":"' . $row[0] . '"},';

           
        }
    
    echo ']}';
}

function getDistype($limit, $start) {
    global $db;
    echo '{
   "getDistype":[';

        $sql1 = 'SELECT nr,`type` FROM care_type_discharge';
        $result1 = $db->Execute($sql1);
        while ($row = $result1->FetchRow()) {

            echo '{"No":"' . $row[0] . '","disType":"' . $row[1] . '"},';

           
        }
    
    echo ']}';
}

?>
