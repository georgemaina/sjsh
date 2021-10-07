<?php

/* ------begin------ This protection code was suggested by Luki R. luki@karet.org ---- */
if (preg_match('save_admission_data.inc.php', $_SERVER['PHP_SELF']))
    die('<meta http-equiv="refresh" content="0; url=../">');
 //require_once($root_path.'include/care_api_classes/accounting.php');
require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
require_once($root_path . 'include/care_api_classes/class_tz_insurance.php');
require_once($root_path . 'include/care_api_classes/class_encounter.php');
require_once($root_path . 'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path . 'include/inc_init_xmlrpc.php');
$bill_obj = new Bill;
$enc_obj=new Encounter;
$insurance_obj = new Insurance_tz;

$debug = false;
($debug) ? $db->debug = TRUE : $db->debug = FALSE;
if ($debug) {
    if (!isset($externalcall))
        echo "internal call<br>";
    else
        echo "external call<br>";

    echo "mode=" . $mode . "<br>";

    echo "show=" . $show . "<br>";

    echo "nr=" . $nr . "<br>";

    echo "breakfile: " . $breakfile . "<br>";

    echo "backpath: " . $backpath . "<br>";

    echo "pid: d " . $_SESSION['sess_pid']. "<br>";

    echo "encounter_nr:" . $encounter_nr.' '.$pn;

    echo "prescrServ: " . $_GET['prescrServ'];
}
$i = 0;
if ($mode == 'delete')
    $arr_item_number[0] = $nr;

$is_new_post = true;
// is a previous session set for this form and is the form being posted
if (isset($_SESSION["myform_key"]) && isset($_POST["myform_key"])) {
    // is the form posted and do the keys match
    if($_POST["myform_key"] == $_SESSION["myform_key"] ){
        $is_new_post = false;
    }
}
if($is_new_post) {
    // register the session key variable
    $_SESSION["myform_key"] = $_POST["myform_key"];
    // do what ya gotta do
//                    require('cashconn.php');
//            add_data_to_database($_POST);

foreach ($arr_item_number AS $item_number) {

    $dosage = $arr_dosage[$i];
    $notes = $arr_notes[$i];
    $article_item_number = $arr_article_item_number[$i];
    //$price = $arr_price[$i];
    //$price=$bill_obj->getItemPrice($partcode, $priceType);
    $article = $arr_article[$i];
    $timesperday = $arr_timesperday[$i];
    $days = $arr_days[$i];
    $history = $arr_history[$i];
    $store=$arr_storeID[$i];

    $i++;

    //$obj->setDataArray($_POST);
    $searchsql = "SELECT item_id, item_description,unit_price,partcode,purchasing_class FROM care_tz_drugsandservices WHERE item_id='" . $article_item_number . "'";
    $searchresult = $db->Execute($searchsql);
    $row = $searchresult->FetchRow();

    $financialClass=$bill_obj->getFinancialClass($encounter_nr);
    $price=$bill_obj->getItemPrice($row[partcode], $financialClass);
    
    switch ($mode) {
        case 'create':
            $desc=addslashes($row[item_description]);
                $sql = "INSERT INTO care_encounter_prescription (
  		                          `encounter_nr`,
  		                          `prescription_type_nr`,
  		                          `article`,
  		                          `article_item_number`,
                                          `partcode`,
  		                          `price`,
  		                          `drug_class`,
  		                          `dosage`,
 		                          `application_type_nr`,
  		                          `notes`,
  		                          `times_per_day`,
  		                          `days`,
  		                          `prescribe_date`,
  		                          `prescriber`,
  		                          `is_outpatient_prescription`,
  		                          `history`,
  		                          `create_time`,
  		                          `modify_id`,
                                           status,
                                           weberpSync,
                                           bill_status,
                                           store,posted)
  		                          VALUES (
  		                          '" . $encounter_nr . "',
  		                          0,
  		                          '" .$desc . "',
  		                          '" . $row[item_id] . "',
                                          '" . $row[partcode] . "',
  		                          '" . $price . "',
  		                          '" . $row[purchasing_class] . "',
  		                          '" . $dosage . "',
  		                          0,
  		                          '" . $notes . "',
  		                          '" . $timesperday . "',
  		                          '" . $days . "',
  		                          '" . date('Y-m-d H:i:s') . "',
  		                          '" . $prescriber . "',
  		                          '1',
  		                          '',
  		                          '" . date('Y-m-d H:i:s') . "',
  		                          '" . $_SESSION['create_id'] . "','pending',0,'pending','$store',0
  		                          )";
                $db->Execute($sql);


            $statusType="Doctors Room";
            $status="Drugs Prescription Created in the Doctors room";
            $statusDesc="Drugs Prescription Created in the Doctors room";
            $bill_obj->updatePatientStatus($encounter_nr,$_SESSION['sess_full_en'],$statusType,$status,$statusDesc,$currUser);

            //   createPhrmQuote($encounter_nr);
//            echo $sql;
            //if($is_transmit_to_weberp_enable == 1)
                        // {
            //			$weberp_obj->issue_to_patient_workorder_in_weberp($WONumber, $StockID, $Location, $Quantity, $Batch);
            //weberp_destroy($weberp_obj);
            // }
            //if (isset($externalcall))
            //  header("location:".$thisfile.URL_REDIRECT_APPEND."&target=$target&type_nr=$type_nr&allow_update=1&externalcall=".$externalcall."&pid=".$_SESSION['sess_pid']);
            //exit;
            //dosage ausgeben:
            //echo 'Dosage: '.$dosage;
            //*******
            // Load the visual signalling functions
            include_once($root_path . 'include/inc_visual_signalling_fx.php');
            // Set the visual signal
            setEventSignalColor($encounter_nr, SIGNAL_COLOR_DOCTOR_INFO);


            break;

        case 'update':

            $sqlOld = "SELECT * from care_encounter_prescription WHERE nr=$nr";
            $result = $db->Execute($sqlOld);
            $row = $result->FetchRow();

            $historyEntry = '';
            $core = new Core;

            if ($row['dosage'] != $dosage) {

                $historyEntry .= 'history =' . $core->ConcatFieldString('history', "Update dosage from " . $row['dosage'] . " to " . $dosage . " / " . date('Y-m-d H:i:s') . " " . $_SESSION['sess_user_name'] . " \n") . ', ';
                //echo $historyEntry;
            }



            if ($row['times_per_day'] != $timesperday) {

                $historyEntry .= "history =" . $core->ConcatFieldString('history', "Update times_per_day from " . $row['times_per_day'] . " to " . $timesperday . " / " . date('Y-m-d H:i:s') . " " . $_SESSION['sess_user_name'] . " \n") . ", ";
                //echo $historyEntry;
            }

            if ($row['days'] != $days) {

                $historyEntry .= "history =" . $core->ConcatFieldString('history', "Update days from " . $row['days'] . " to " . $days . " / " . date('Y-m-d H:i:s') . " " . $_SESSION['sess_user_name'] . " \n") . ", ";
                //echo $historyEntry;
            }

            if ($row['notes'] != $notes) {

                $historyEntry .= "history =" . $core->ConcatFieldString('history', "Update notes from" . $row['notes'] . " to " . $notes . " / " . date('Y-m-d H:i:s') . " " . $_SESSION['notes'] . " \n") . ", ";
            }

            if ($historyEntry != '') {
                $historyEntry = substr($historyEntry, 0, -2);
                $sqlHist = 'UPDATE care_encounter_prescription SET ' . $historyEntry . ' WHERE nr = ' . $nr;
                //echo $sqlHist;
                $db->execute($sqlHist);
            }

            //echo 'UPDATE care_encounter_prescription SET '.$historyEntry.' WHERE nr = '.$nr;

            $sql = "UPDATE care_encounter_prescription SET
  		                          `dosage`='$dosage',
  		                          `times_per_day`='$timesperday',
  		                          `days`='$days',
  		                          `notes`='$notes',
  		                          `prescriber`='$prescriber'
  		                  WHERE nr=$nr";

//            echo $sql;

            $db->Execute($sql);

            $sql2 = "UPDATE care_ke_billing SET
  		                          `dosage`='$dosage',
  		                          `times_per_day`='$timesperday',
  		                          `days`='$days',
  		                          `notes`='$notes',
  		                          `input_user`='$prescriber',
                                  `qty`='" . intval($dosage) * intval($timesperday) * intval($days)."',"
                                  ."`total`='" . $price * intval($dosage) * intval($timesperday) * intval($days). "' WHERE batch_no='$nr' and rev_code='$row[partcode]'";

//         echo $sql2;

            $db->Execute($sql2);

            $statusType="Doctors Room";
            $status="Drugs Prescription Update in the Doctors room";
            $statusDesc="Drugs Prescription Updated in the Doctors room";
            $bill_obj->updatePatientStatus($encounter_nr,$nr,$statusType,$status,$statusDesc,$currUser);
            //*******
            // Load the visual signalling functions
            include_once($root_path . 'include/inc_visual_signalling_fx.php');
            // Set the visual signal
            setEventSignalColor($encounter_nr, SIGNAL_COLOR_DOCTOR_INFO);
            //********
            break;
        case 'delete':
            $sql = "DELETE FROM care_encounter_prescription WHERE nr=$nr";
            //echo $sql;
            if($db->Execute($sql)){
                $sql2="delete from care_ke_billing where batch_no='$nr'";
               // echo $sql2;
                $db->Execute($sql2);
            }
            
            //if (isset($externalcall))
            //  header("location:".$thisfile.URL_REDIRECT_APPEND."&target=$target&type_nr=$type_nr&allow_update=1&externalcall=".$externalcall."&pid=".$_SESSION['sess_pid']);
            //exit;
            break;
    }// end of switch
} // end of foreach



$pid=$_SESSION['sess_pid'];
$mysql = "SELECT c.pid,c.encounter_nr,c.encounter_class_nr,
                    b.article, b.article_item_number,b.partcode, b.price,(b.dosage*b.times_per_day*b.days) as qty,
                    d.salesAreas as salesArea , b.dosage, b.notes, b.prescribe_date as bill_date, b.times_per_day, b.days, b.prescriber,c.current_ward_nr,
                    (b.dosage*b.times_per_day*b.days)*b.price as ovamount, b.is_outpatient_prescription, b.status,
                    b.bill_number, b.bill_status,d.item_number,b.nr,d.category,d.purchasing_class,w.ward_id
                    FROM care_encounter c
                    INNER JOIN care_encounter_prescription b ON c.encounter_nr=b.encounter_nr
                    LEFT JOIN care_ward w ON c.`current_ward_nr`=w.`nr`
                    INNER JOIN care_tz_drugsandservices d on d.partcode=b.partcode
                    WHERE c.pid='$pid' and drug_class IN('drug_list','SERVICE','Dental','MEDICAL-SUPPLIES','physiotherapy','Theatre')
                    and b.status='pending' and b.bill_status='pending' AND b.weberpsync=0 and b.posted=0";
//if ($debug)
    echo 'save_pres: '.$mysql . "<br>";

$result = $db->Execute($mysql);
while($rows = $result->FetchRow()){
$IS_PATIENT_INSURED = $insurance_obj->is_patient_insured($rows[pid]);
$insuranceid=$insurance_obj->Get_insuranceID_from_pid($rows[pid]);

    $strCategory=array('Service','Dental','physiotherapy','Theatre');
    if ($rows[2] == 2 and !$insuranceid) {
       // createPhrmQuote($encounter_nr);
        $bill_obj->updateFinalBill($encounter_nr,$rows[nr],$is_new_post);
    }elseif($rows[2] == 1 and $rows[purchasing_class]=='PHYSIOTHERAPY' or
        $rows[purchasing_class]=='THEATRE' || $rows[purchasing_class]=='DENTAL' || $rows[purchasing_class]=='SERVICE'){
       // createPhrmQuote($encounter_nr);
        $bill_obj->updateFinalBill($encounter_nr,$rows[nr],$is_new_post);
        $weberp_obj = new_weberp();
        

        if (!$weberp_obj->transfer_bill_to_webERP_asSalesInvoice($rows)) {
            $sql = "update care_encounter_prescription set weberpSync=1 where weberpSync=0";
            $db->Execute($sql);
            if ($debug)echo $sql;
            //echo "Successfully updated sales invoice for pid $row[pid], $row[Prec_desc], $row[amount] <br>";
        }
        else {
            echo 'failed';
        }
    }elseif($rows[2] == 2  and $insuranceid<>'-1'){
            // createPhrmQuote($encounter_nr);
//            $bill_obj->updateFinalBill($encounter_nr,$rows[nr],$is_new_post);
//            $weberp_obj = new_weberp();
//
//
//            if (!$weberp_obj->transfer_bill_to_webERP_asSalesInvoice($rows)) {
//                $sql = "update care_encounter_prescription set weberpSync=1 where weberpSync=0";
//                $db->Execute($sql);
//                if ($debug)echo $sql;
//                //echo "Successfully updated sales invoice for pid $row[pid], $row[Prec_desc], $row[amount] <br>";
//            }else {
//                echo 'failed';
//            }
    }elseif($rows[2] == 2 and ($insuranceid)){
        //createPhrmQuote($encounter_nr);
        }
    }
}

header("location:" . $thisfile . URL_REDIRECT_APPEND . "&target=$target&type_nr=$type_nr&allow_update=1&backpath=" . urlencode($backpath) . "&prescrServ=" . $_GET['prescrServ'] . "&pid=" . $_SESSION['sess_pid']);

exit();
?>

