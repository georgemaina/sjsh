<?php
/**
 * Created by PhpStorm.
 * User: george
 * Date: 10/9/2014
 * Time: 12:52 PM
 */
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');
require_once($root_path.'include/care_api_classes/class_measurement.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
require_once($root_path.'include/care_api_classes/class_tz_insurance.php');
include_once($root_path.'include/care_api_classes/class_person.php');
require_once($root_path.'include/care_api_classes/class_ward.php');
require_once($root_path.'include/care_api_classes/class_tz_billing.php');
require_once($root_path . 'include/care_api_classes/class_department.php');
//require_once($root_path . 'include/care_api_classes/class_weberp_c2x.php');
$ward_obj= new Ward;
$bill_obj= new bill;
$person_obj=new Person;
$encounter_obj=new Encounter;
$insurance_obj=new Insurance_tz;
$dept_obj = new Department;
//$weberp_obj = new weberp_c2x();


$limit = $_REQUEST['limit'];
$start = $_REQUEST['start'];
$formStatus = $_REQUEST['formStatus'];
$searchParam = $_REQUEST['sParam'];

//Insert and Update tables parameter
$ID = $_POST['ID'];
$tableName = $_POST['tableName'];
$fieldParam = $_POST['fieldParam'];
$valueParam = $_POST['fieldValue'];

$itemdetails = $_POST;

$task = ($_REQUEST['task']) ? ($_REQUEST['task']) : '';

$encoder = $_SESSION['sess_user_name'];

if ($pid == '') $pid = $_POST['pid'];
$_SESSION['pid']=$pid;

switch ($task) {
    case "createPatient":
        createPatient($person_obj);
        break;
    case "admitPatient":
        admitPatient($pid,$encoder,$insurance_obj,$encounter_obj,$bill_obj,$person_obj,$weberp_obj);
        break;
    default:
        echo "{failure:true}";
        break;
}//end switch

function admitPatient($pid,$encoder,$insurance_obj,$encounter_obj,$bill_obj,$person_obj,$weberp_obj){
    global $db;

    $_POST['encounter_date'] = date('Y-m-d');
    $_POST['modify_id'] = $encoder;
    $_POST['encounter_time'] = date('H:i:s');
    $_POST['create_id'] = $encoder;
    $_POST['create_time'] = date('YmdHis');
    $_POST['history'] = 'Create: ' . date('Y-m-d H:i:s') . ' = ' . $encoder;
    if (isset($_POST['encounter_nr']))
        unset($_POST['encounter_nr']);

    $is_transmit_to_weberp_enable=1;

    $current_dept_nr=$_POST['current_dept_nr'];
    $paymentPlan=$_POST['financial_class'];
    $consultation_fee=$_POST['consultation_fee'];

    $encounter_obj->setDataArray($_POST);

    if ($encounter_obj->insertDataFromInternalArray()) {
        /* Get last insert id */
        $encounter_nr = $encounter_obj->getCurrentEncounterNr($pid);

        $encounter_obj->assignInDept($encounter_nr, $current_dept_nr, $current_dept_nr, date('Y-m-d'), date('H:i:s'));

        $new_bill_number = $bill_obj->checkBillEncounter($encounter_nr);
        $sql2 = "Update care_encounter set bill_number='$new_bill_number' where encounter_nr='$encounter_nr'";

        $db->Execute($sql2);

        $IS_PATIENT_INSURED = $insurance_obj->is_patient_insured($pid);
        $insurance_ID = $insurance_obj->Get_insuranceID_from_pid($pid);
        if ($IS_PATIENT_INSURED) {
            $sql4 = "update care_encounter set insurance_firm_id='$insurance_ID' where encounter_nr='$encounter_nr'";
            $db->Execute($sql4);
        }

        if ($consultation_fee != '') {
            $bill_obj->new_reg($encounter_nr, $consultation_fee, $encoder, $paymentPlan);
        }

        $bill_obj->updateRegBill($db, $encounter_nr, $pid);

//        $bill_obj->getCashDeposit($db, $pid, $encounter_nr);

//        if ($is_transmit_to_weberp_enable == 1) {
//            $persondata = $person_obj->getAllInfoArray($pid);
//
//            if (!$weberp_obj->transfer_patient_to_webERP_asCustomer($pid, $persondata)) {
//                $person_obj->setPatientIsTransmit2ERP($pid, 0);
//            } else {
//                $person_obj->setPatientIsTransmit2ERP($pid, 1);
//            }
//            destroy_weberp($weberp_obj);
//        }

        echo "{'success':true}";
    }else{
        echo "{'failure':true}";
    }
}

function createPatient($person_obj){
    global $db;
    $debug=false;

    $dob=new DateTime($_POST['date_birth']);
    $dob2=$dob->format("Y-m-d");

    $_POST['date_birth'] = $dob2 ;
    $_POST['date_reg'] = date('Y-m-d H:i:s');
    $_POST['blood_group'] = trim($_POST['blood_group']);
    $_POST['status'] = 'normal';
    $_POST['history'] = "Init.reg. " . date('Y-m-d H:i:s') . " " . $_SESSION['sess_user_name'] . "\n";
    $_POST['create_id'] = $_SESSION['sess_user_name'];
    $_POST['create_time'] = date('YmdHis');
    $sql = "select last_encounter_no from care_ke_invoice";
    $result = $db->Execute($sql);
    $row = $result->FetchRow();
    $new_nr = intval($row[0] + 1)."/".date('y');

    if(!$_POST['selian_pid']){
        $_POST['selian_pid']=$new_nr;
    }
    # Persons are existing. Check if duplicate might exist
    $error_person_exists='';
    if (is_object($duperson = $person_obj->PIDbyData($_POST))) {
        $error_person_exists = TRUE;
    }

    if (!$error_person_exists) {
        if ($person_obj->insertDataFromInternalArray()) {
            $new_nr = intval($new_nr + 1).'/'.date('y');
            $sql2 = "update care_ke_invoice set last_encounter_no='$new_nr'";
            if($debug) echo $sql2;
            $db->Execute($sql2);
            echo "{success:true}";
        } else {
            echo "{failure:true; Error:'Could not save patient'}";
        }
    }else{
        echo "{failure:true; Error:'Patient Already Exists'}";
    }
}




