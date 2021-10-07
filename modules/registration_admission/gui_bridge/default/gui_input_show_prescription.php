<?php

require_once($root_path . 'include/inc_environment_global.php');
include_once($root_path . 'include/care_api_classes/class_prescription.php');
if (!isset($pres_obj))
    $pres_obj = new Prescription;
$app_types = $pres_obj->getAppTypes();
$pres_types = $pres_obj->getPrescriptionTypes();

require_once($root_path . 'include/care_api_classes/class_person.php');
$person_obj = new Person;
if (empty($encounter_nr) and !empty($pid))
    $encounter_nr = $person_obj->CurrentEncounter($pid);

$debug = false;

if ($debug) {
    if (!empty($back_path))
        $backpath = $back_path;

    echo "file: show_prescription<br>";
    if (!isset($externalcall))
        echo "internal call<br>";
    else
        echo "external call<br>";

    echo "mode=" . $mode . "<br>";

    echo "show=" . $show . "<br>";

    echo "nr=" . $nr . "<br>";

    echo "breakfile: " . $breakfile . "<br>";

    echo "backpath: " . $backpath . "<br>";

    echo "pid:" . $pid . "<br>";

    echo "encounter_nr:" . $encounter_nr;
}

if (!empty($show)) {
    // The $show-Value comes from the input-type button and will be send by javascript (check_prescriptions.js)
    if ($show == 'Drug List') {
        $activated_tab = 'druglist';
        $db_drug_filter = 'drug_list';
    }
    if ($show == 'service') {
        $activated_tab = 'service';
        $db_drug_filter = 'service';
    }
    if ($show == 'MEDICAL-SUPPLIES') {
        $activated_tab = 'MEDICAL-SUPPLIES';
        $db_drug_filter = 'MEDICAL-SUPPLIES';
    }
    if ($show == 'physiotherapy') {
        $activated_tab = 'physiotherapy';
        $db_drug_filter = 'physiotherapy';
    }
    if ($show == 'Dental') {
        $activated_tab = 'Dental';
        $db_drug_filter = 'Dental';
    }
    if ($show == 'Others') {
        $activated_tab = 'Others';
        $db_drug_filter = 'Others';
    }
    if ($show == 'Supplies') {
        $activated_tab = 'Supplies';
        $db_drug_filter = 'Supplies';
    }
    if ($show == 'smallop') {
        $activated_tab = 'smallop';
        $db_drug_filter = 'smallop';
    }
    if ($show == 'Theatre') {
        $activated_tab = 'Theatre';
        $db_drug_filter = 'Theatre';
    }
    if ($show == 'eye-service') {
        $activated_tab = 'eye-service';
        $db_drug_filter = 'eye-service';
    }
    if ($show == 'eye-surgery') {
        $activated_tab = 'eye-surgery';
        $db_drug_filter = 'eye-surgery';
    }
    if ($show == 'eye-glasses') {
        $activated_tab = 'eye-glasses';
        $db_drug_filter = 'eye-glasses';
    }


    if ($show == 'insert') {
        if (empty($_SESSION['item_array'])) {
            //echo "Taking items from get values...<br>";
//            for ($i = 0; $i < count($item_no); $i++) {
//                $sql = 'select partcode from care_tz_drugsandservices where item_id="' . $item_no[$i] . '"';
//                $result = $db->Execute($sql);
//                $row = $result->FetchRow();
//                $itemno = $row[0];
//
//                echo $sql;
//            }

            $_SESSION[item_array] = $item_no; // this is comming from gui_inpuit_prescription_preselection.php as GET variables!
        }

        // The prescription list is complete, so we can go to ask about the details
        // of each item

        include('./gui_bridge/default/gui_input_prescription_details.php');
    } else {

        include('./gui_bridge/default/gui_input_prescription_preselection.php');
    }
} else {

    if ($prescrServ == "serv") {
        $show = "xray";
        echo 'show XRAY activated';
    } else {
        $show = "druglist";
        echo 'show DRUGLIST activated';
    }

    // first call of descriptions. The value $show is not set in this case.
    include('./gui_bridge/default/gui_input_prescription_preselection.php');
}