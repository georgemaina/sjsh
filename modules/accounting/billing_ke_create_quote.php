<?php
//require_once 'roots.php';
//require($root_path.'include/inc_environment_global.php');

//require_once($root_path.'include/care_api_classes/class_encounter.php');
//require_once($root_path.'include/care_api_classes/class_person.php');
//require_once($root_path.'include/care_api_classes/class_tz_billing.php');
////$billing_tz = new Bill();
//require_once($root_path.'include/care_api_classes/class_tz_insurance.php');
//require_once($root_path.'include/care_api_classes/class_weberp.php');
//require_once($root_path.'include/inc_init_xmlrpc.php');
//require_once($root_path.'include/care_api_classes/class_tz_drugsandservices.php');
//require_once($root_path.'include/care_api_classes/class_tz_insurance_reports.php');
function createlabQuote($batch_nr,$pn,$breakfile,$root_path) {
//     Global $company_id;
//    Global $db;
//   /// require_once 'roots.php';
// require_once($root_path.'include/care_api_classes/class_tz_insurance.php');
//// $insurance_obj = new Insurance_tz;
//    $per_obj = new Person;
//    $enc_obj = new Encounter;
//    $bill_obj = new Bill;
//    $insurance_obj = new Insurance_tz;
//    $drg_obj = new DrugsAndServices;
//   
//    $user_origin='quotation';
//    
//    $debug=false;
//
//    ($debug) ? $db->debug=TRUE : $db->debug=FALSE;
//
//    $IS_PATIENT_INSURED=$insurance_obj->is_patient_insured($pn);
//    if(!isset($new_bill_number)) {
//        $new_bill_number=$bill_obj->checkBillEncounter($pn);
//    }
//
//    $bill_obj->setItemID($batch_nr);
//    if ($debug) echo "<b>billing_tz_quotation_create.php</b>";
//    $in_op=$bill_obj->getEncounterClassLab($pn, $batch_nr);
//
//    $result=$bill_obj->GetNewQuotation_Laboratory($pn, $in_op);
//    if (!$result) { echo "error ".mysql_error();
//    }else {
//        while ($row=$result->FetchRow()) {
//            $billcounter=0;
//            $pid=$row[pid];
//            //$batch_nr=$_REQUEST[batchno];
//            $encounter_nr=$pn;
//            $in_outpatient='inpatient';
//            $createid=$row['create_id'];
//            $drug_class = 'lab test';
//            $bill_nr = $row['bill_number'];
//            $parameter_name= $row['parameter_name'];
//            $labtest_nr=$row['sub_id'];
//            $item_id=$row['item_number'];
//            $description=$row['item_description'];
//            $price=$row['unit_price'];
//            $test_date=$row['create_time'];
//            $billnumber= $row['bill_number'];
//
//            $user = $_SESSION['sess_user_name'];
//            $encr_class=$row['encounter_class_nr'];
//
//            $per_obj = new Person;
//            $enc_obj = new Encounter;
//            $bill_obj = new Bill;
//            $insurance_obj = new Insurance_tz;
//            $drg_obj = new DrugsAndServices;
//
//
//            if ($debug) echo 'batch_nr: '.$batch_nr.'<br>';
//            if ($debug) echo 'item_number: '.$item_id.'<br>';
//            if ($debug) echo "drug_class:".$drug_class."<br>";
//            if ($debug) echo "old_bill_nr:".$bill_nr."<br>";
//            if ($debug) echo "labtest_nr:".$labtest_nr."<br>";
//            if ($debug) echo 'unit_price: '.$price.'<br>';
//            if ($debug) echo "bill number:".$billnumber."<br>";
//            if ($debug) echo "user:".$user."<br>";
//            if ($debug) echo "pid:".$pid."<br>";
//            if ($debug) echo "encounter_nr:".$pn."<br>";
//            if ($debug) echo "encounter class:".$encr_class."<br>";
//            if ($debug) echo "Test Date:".$test_date."<br>";
//
//            if ($debug) echo "Description:".$description."<br>";
//
//           
//            $bill_obj->StoreLaboratoryItemToBill($pid, $labtest_nr, $batch_nr, $new_bill_number,$price,$description);
//
//            $bill_obj->UpdateBillNumberNewLaboratory($labtest_nr,$new_bill_number);
//
//            
//        }
//     
// }
//   $bill_obj->updateFinalLabBill($pn,$new_bill_number);
//        $sql='SELECT b.pid, c.unit_price AS price,c.partcode,c.item_Description AS article,a.prescribe_date,a.qty AS amount,a.bill_number
//    FROM care2x.care_ke_billing a INNER JOIN care_tz_drugsandservices c
//    ON a.item_number=c.item_number
//    INNER JOIN care2x.care_encounter b
//    ON a.encounter_nr=b.encounter_nr and a.encounter_nr="'.$pn.'" and service_type="lab test" and a.weberpsync=0';
//        $result=$db->Execute($sql);
//        if($debug) echo $sql;
//        if($weberp_obj = new_weberp()) {
//        //$arr=Array();
//
//            while($row=$result->FetchRow()) {
//            //$weberp_obj = new_weberp();
//                if(!$weberp_obj->transfer_bill_to_webERP_asSalesInvoice($row)) {
//                    $sql="update care_test_request_chemlabor set weberpSync=1 where weberpSync=0";
//                    $db->Execute($sql);
//                    $sql2="update care_ke_billing set weberpSync=1 where weberpSync=0";
//                    $db->Execute($sql2);
//                     
//                }
//                else {
//                     echo 'failed to transmit to weberp';
//                }
//                destroy_weberp($weberp_obj);
//            }
//        }else {
//            return 'could not create object: debug level ';
//        }
// header("location:../laboratory_tz/labor_test_request_aftersave.php.php".URL_REDIRECT_APPEND."&edit=$edit&saved=insert&pn=$pn&station=$station&user_origin=$user_origin&status=$status&target=chemlabor&noresize=$noresize&batch_nr=$batch_nr");
return true;
                         

}


?>

