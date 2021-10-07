<?php
//require_once ($root_path.'config.php');
require_once 'roots.php';
//require($root_path.'include/inc_environment_global.php');

//$link = mysql_connect($mysql_host, $mysql_user, $mysql_pass);
//$res = mysql_select_db ($mysql_db);

$debug=false;

require_once($root_path.'include/care_api_classes/class_encounter.php');
require_once($root_path.'include/care_api_classes/class_person.php');
require_once($root_path.'include/care_api_classes/class_tz_billing.php');
//$billing_tz = new Bill();
require_once($root_path.'include/care_api_classes/class_tz_insurance.php');
//$insurance_tz = new Insurance_tz();
require_once($root_path.'include/care_api_classes/class_tz_drugsandservices.php');
//require_once($root_path.'include/care_api_classes/class_tz_insurance_reports.php');
//$insurance_tz_report = new Insurance_Reports_tz();

function createPhrmQuote($encounter_nr){
    $debug=false;

$per_obj = new Person;
$enc_obj = new Encounter;
$bill_obj = new Bill;
//$insurance_obj = new Insurance_tz;
$drg_obj = new DrugsAndServices;
Global $company_id;
$user_origin='quotation';

//$IS_PATIENT_INSURED=$insurance_obj->is_patient_insured($encounter_nr);

$result1=$bill_obj->getPrescriptionNo($encounter_nr);

while ($row=$result1->FetchRow()) {
    $prescriptions_nr = $row['nr'];

    $dosageNew = $_POST['dosage_'.$prescriptions_nr];
    $timesPerDayNew = $_POST['times_per_day_'.$prescriptions_nr];
    $daysNew = $_POST['days_'.$prescriptions_nr];

    $in_op=$bill_obj->getEncounterClassPres($encounter_nr, $prescriptions_nr);

    $result=$bill_obj->GetNewQuotation_Prescriptions($encounter_nr, $in_op);


            //if ($result) $row=$result->FetchRow();
            while ($row=$result->FetchRow()) {
                $dosageOld = $row['dosage'];
                $timesPerDayOld = $row['times_per_day'];
                $daysOld = $row['days'];
                $drug = $row['article'];
                $old_bill_nr = $row['bill_number'];
                $drug_class = $row['drug_class'];

                $comment = $_POST['notes_'.$prescriptions_nr];
                $user = $_SESSION['sess_user_name'];


                if ($debug) echo "dosage:".$dosageOld."<br>";
                if ($debug) echo "drug:".$drug."<br>";
                if ($debug) echo "timesPerDayOld:".$timesPerDayOld."<br>";
                if ($debug) echo "daysOld:".$daysOld."<br>";
                if ($debug) echo "old_bill_nr:".$old_bill_nr."<br>";
                if ($debug) echo "drug_class:".$drug_class."<br>";
                if ($debug) echo "comment:".$comment."<br>";
                if ($debug) echo "user:".$user."<br>";
//                if ($debug) echo "pid:".$pid."<br>";

                if ($debug) echo "encounter_nr:".$encounter_nr."<br>";
                if ($debug) echo "insurance_$prescriptions_nr".$_POST['insurance_'.$prescriptions_nr]."<br>";
                if ($debug) echo "insurance:".$_POST['insurance']."<br>";
                if ($debug) echo "showprice_".$row['price']."<br>";
                if ($debug) echo "price_".$row['unit_price_1'].'<br>';
                if ($debug) echo 'times per day: '.$timesPerDayOld.'<br>';
                if ($debug) echo 'days: '.$daysOld.'<br>';
                if ($debug) echo 'unit_price: '.$row['unit_price'].'<br>';
 
            }

    $new_bill_number = $bill_obj->checkBillEncounter($encounter_nr);

if ($debug) echo "Prescription: allocate2insurance(".$new_bill_number.", ".$_POST['showprice_'.$prescriptions_nr].",".$_POST['insurance'].");";
$bill_obj->StorePrescriptionItemToBill($encounter_nr,$prescriptions_nr,$new_bill_number);
}

//$bill_obj->UpdateBillNumberNewPrescription($encounter_nr,$new_bill_number);


//$bill_obj->updateBillQty($encounter_nr,$new_bill_number);

}


?>
