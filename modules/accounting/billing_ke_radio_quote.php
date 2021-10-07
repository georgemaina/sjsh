<?php
//require_once ($root_path.'config.php');
require_once 'roots.php';
//require($root_path.'include/inc_environment_global.php');

$debug=true;

require_once($root_path.'include/inc_init_xmlrpc.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
require_once($root_path.'include/care_api_classes/class_person.php');
require_once($root_path.'include/care_api_classes/class_tz_billing.php');
//$billing_tz = new Bill();
require_once($root_path.'include/care_api_classes/class_tz_insurance.php');
//$insurance_tz = new Insurance_tz();
require_once($root_path.'include/care_api_classes/class_tz_drugsandservices.php');
//require_once($root_path.'include/care_api_classes/class_tz_insurance_reports.php');
//$insurance_tz_report = new Insurance_Reports_tz();


  //echo '<br> Prescription No ';
  
function createRadQuote($batch_nr,$pn,$target) {
    $per_obj = new Person;
    $enc_obj = new Encounter;
    $bill_obj = new Bill;
    $insurance_obj = new Insurance_tz;
    $drg_obj = new DrugsAndServices;
    Global $company_id;
    Global $db;
    $user_origin='quotation';
    $encounter_no=$pn;
    if(!isset($new_bill_number)) {
        $new_bill_number=$bill_obj->checkBillEncounter($encounter_no);
    }


//    $IS_PATIENT_INSURED=$insurance_obj->is_patient_insured($pn);


    $result1=$bill_obj->getPrescriptionNo($encounter_no);
    $in_op=$bill_obj->getEncounterClassRad($encounter_no, $batch_nr);

    //echo '<br> Prescription No '.$result1;
    
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
            //$price=$row['unit_price_1'];
             $financialClass=$bill_obj->getFinancialClass($encounter_no);
             $price=$bill_obj->getItemPrice($test_number, $financialClass);

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
    $bill_obj->updateFinalRadBill($encounter_no,$new_bill_number);

    $sql='SELECT k.pid,k.price,k.partcode,k.Description AS article,k.bill_date,k.total AS ovamount,k.bill_number,k.ledger AS salesArea FROM care_ke_billing k
WHERE k.encounter_nr="'.$encounter_no.'" AND k.service_type="xray" AND k.weberpsync=0';
    if ($debug) echo $sql;
    $result=$db->Execute($sql);
//    if($weberp_obj = new_weberp()) {
//    //$arr=Array();
//
//        while($row=$result->FetchRow()) {
//        //$weberp_obj = new_weberp();
//            if(!$weberp_obj->transfer_bill_to_webERP_asSalesInvoice($row)) {
//                    $sql="update care_test_request_radio set weberpsync=1 where weberpsync=0";
//                    $db->Execute($sql);
//                    $sql2="update care_ke_billing set weberpSync=1 where weberpSync=0";
//                    $db->Execute($sql2);
//            }
//            else {
//                echo 'failed';
//            }
//            destroy_weberp($weberp_obj);
//        }
//    }else {
//        echo 'could not create object: debug level ';
//    }

header("location:../laboratory_tz/labor_test_request_aftersave.php?sid=$sid&lang=$lang&edit=$edit&saved=insert&pn=$pn&station=$station&user_origin=$user_origin&status=$status&target=$target&noresize=$noresize&batch_nr=$batch_nr");
}




