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
function createRadQuote($batch_nr,$pn,$target) {
$per_obj = new Person;
$enc_obj = new Encounter;
$bill_obj = new Bill;
$insurance_obj = new Insurance_tz;
$drg_obj = new DrugsAndServices;
Global $company_id;
$user_origin='quotation';
$encounter_no=$pn;
if(!isset($new_bill_number)) {
      $result3=$bill_obj->checkBillEncounter($encounter_no);
          if($result3) {
               $row=$result3->FetchRow();
               $new_bill_number=$bill_obj->$row[1];
          } else{
              $new_bill_number=$bill_obj->newBillNo();
          }

    }

$IS_PATIENT_INSURED=$insurance_obj->is_patient_insured($pn);


$result1=$bill_obj->getPrescriptionNo($encounter_no);
$in_op=$bill_obj->getEncounterClassRad($encounter_no, $batch_nr);

while ($row=$result1->FetchRow()) {
    $prescriptions_nr = $row['nr'];

       $result=$bill_obj->GetNewQuotation_Radiology($pn, 1);

            //if ($result) $row=$result->FetchRow();
            while ($row=$result->FetchRow()) {
               
               
                $radiology_test = $prescriptions_nr;
                $user = $_SESSION['sess_user_name'];
                //$batch_nr=$row['batch_no'];
                $pid=$row['pid'];
                $test_number=$row['test_request'];
                $test_desc=$row['item_description'];
                $user=$row['create_id'];
                $price=$row['unit_price_1'];

                if ($debug) echo "test No:".$test_number."<br>";
                if ($debug) echo "test description:".$test_desc."<br>";
                if ($debug) echo "user:".$user."<br>";
                if ($debug) echo "pid:".$pid."<br>";
                if ($debug) echo "radiology_test:".$radiology_test."<br>";
                if ($debug) echo "encounter_nr:".$encounter_no."<br>";
                if ($debug) ECHO "price :".$price.'<br>';
            }

$billcounter++;

    //Okay, this one has to be billed!
    


$bill_obj->StoreRadiologyItemToBill($encounter_no,$batch_nr,$new_bill_number,$price,$test_desc);

$bill_obj->UpdateBillNumberNewRadiology($prescriptions_nr,$new_bill_number);

}
$bill_obj->updateFinalRadBill($encounter_no);

header("location:../laboratory_tz/labor_test_request_aftersave.php?sid=$sid&lang=$lang&edit=$edit&saved=insert&pn=$pn&station=$station&user_origin=$user_origin&status=$status&target=$target&noresize=$noresize&batch_nr=$batch_nr");
}




?>
