
<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');

require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
$bill_obj = new Bill;

$limit = $_POST['limit'];
$start = $_POST['start'];

$id = $_REQUEST['id'];
$encounterNr = $_REQUEST['pn'];
$financialClass=$_REQUEST['financeClass'];

$input_user = $_SESSION['sess_login_username'];

$task = ($_POST['task']) ? ($_POST['task']) : $_REQUEST['task'];
switch ($task) {
    case "getLabcost":
        getLabcost($id,$encounterNr,$bill_obj,$financialClass);
        break;
    default:
        echo "{failure:true}";
        break;
}//end switch

function getLabcost($id,$encounter_nr,$bill_obj,$financialClass){
        global $db;
        $debug=false;
        $psql = "SELECT item_id,NAME,price FROM `care_tz_laboratory_param` WHERE id='$id'";
        if($debug) echo $psql;

        $result = $db->Execute($psql);
        $row = $result->FetchRow();

        $price=$bill_obj->getItemPrice($row[item_id], $financialClass);

       echo $row[1].','.$price;
    }

?>

