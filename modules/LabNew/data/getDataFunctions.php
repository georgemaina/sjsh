<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 8/7/2019
 * Time: 3:10 PM
 */


error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');
require ($root_path . 'include/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_measurement.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
require_once($root_path.'include/care_api_classes/class_tz_insurance.php');
include_once($root_path.'include/care_api_classes/class_person.php');
require_once($root_path.'include/care_api_classes/class_ward.php');
require_once($root_path.'include/care_api_classes/class_tz_billing.php');
require_once($root_path . 'include/care_api_classes/class_department.php');
require_once($root_path . 'include/care_api_classes/class_tz_drugsandservices.php');
$ward_obj= new Ward;
$bill_obj= new bill;
$person=new Person;
$enc_obj=new Encounter;
$insurance=new Insurance_tz;
$dept_obj = new Department;
$items_obj= new DrugsAndServices;


$task = ($_REQUEST['task']) ? ($_REQUEST['task']) : $_POST['task'];

switch ($task) {

    case 'getLabPatients':
        getLabPatients();
        break;
    case 'getLabParams':
        getLabParams();
        break;
    default:
        echo "{failure:true}";
        break;
}//end switch


function getLabParams(){
    global $db;
    $debug=true;
    $enclass=2;
    $sql = "SELECT `item_id`,`group_id`,`name`,`price` FROM` care_tz_laboratory_param`";

    if ($debug) echo $sql;

    $result = $db->Execute($sql);

    $numRows = $result->RecordCount();
    echo '[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        echo '{"PartCode":"' . $row['item_id'] . '","Description":"' . $row['name'] . '","Price":"' . $row['price']. '"}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }

    }
    echo ']';
}

function getLabPatients(){
    global $db;
    $debug=true;
    $enclass=2;
    $sql = "SELECT p.pid,concat(name_first,' ',name_last) as names, batch_nr, tr.encounter_nr,tr.send_date
                FROM care_test_request_chemlabor tr
                left join care_encounter e on tr.encounter_nr=e.encounter_nr
                left join care_person p on e.pid=p.pid
                WHERE (tr.status='pending' OR tr.status='')";

    if($enclass<>''){
        $sql=$sql." and e.encounter_class_nr=$enclass";
    }
    $sql=$sql." and date_format(tr.send_date,'%Y-%m-%d')='".date('Y-m-d')."' ORDER BY  tr.send_date ASC";

    if ($debug) echo $sql;

    $result = $db->Execute($sql);

    $numRows = $result->RecordCount();
    echo '[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
      //  $description = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row['Full Description']);
        echo '{"Pid":"' . $row['pid'] . '","EncounterNo":"' . $row['encounter_nr'] . '","Names":"' . $row['names']
            . '","LabNo":"' . $row['batch_nr']. '"}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }

    }
    echo ']';


}

?>
