<?php
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
error_reporting(E_ALL && ~E_NOTICE);
require('./roots.php');
require($root_path . 'include/inc_environment_global.php');
require_once($root_path . 'include/care_api_classes/class_encounter.php');
$enc_obj = new Encounter;

require_once($root_path . 'include/care_api_classes/class_core.php');
require_once($root_path . 'include/care_api_classes/class_prescription.php');
require_once($root_path . 'include/care_api_classes/class_encounter.php');
require_once($root_path . 'include/care_api_classes/class_person.php');
require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
require_once($root_path . 'include/care_api_classes/class_lab.php');
//
require_once($root_path . 'include/care_api_classes/class_tz_insurance.php');
require_once($root_path . 'include/care_api_classes/class_weberp.php');
require_once($root_path . 'include/inc_init_xmlrpc.php');
require_once($root_path . 'include/care_api_classes/class_tz_drugsandservices.php');

$insurance_obj = new Insurance_tz;
$drg_obj = new DrugsAndServices;
$billing_tz = new Bill();
$per_obj = new Person;
$pres_obj = new Prescription;
//require_once($root_path.'include/care_api_classes/class_tz_drugsandservices.php');
//$drg_obj = new DrugsAndServices;

/**
 * CARE2X Integrated Hospital Information System version deployment 1.1 (mysql) 2004-01-11
 * GNU General Public Licensev
 * Copyright 2002,2003,2004,2005 Elpidio Latorilla
 * , elpidio@care2x.org
 *
 * See the file "copy_notice.txt" for the licence notice
 */

/**
 * Funtion prepareTestElemenst() will process the POST vars containg the test elements
 * and other variables: sampling day & sampling time
 * return: 1= if  test element(s) set, (paramlist is not empty),
 * return: 0 = if no test element set, (paramlist empty)
 */
function prepareTestElements() {
    global $_POST, $paramlist, $sday, $sample_time, $param_array;

    /* Prepare the parameters
     *  Check the first char of the POST_VARS. Concatenate all POST vars with
     *  the content having "_" as the first character , then save it to  "parameters"
     */
    //gjergji new parameter handling code
    $paramlist = '';

    while (list($x, $v) = each($_POST)) {
        if ((substr($x, 0, 1) == '_') && ($_POST[$x] == 1)) {
            if ($paramlist == '') {
                $paramlist = $x . '=1';
            } else {
                $paramlist.='&' . $x . '=1';
            }
        }
    }

    /* If the paramlist is not empty then the user had set a test parameter,
     *  go ahead and prepare the other data for saving
     *  otherwise, the user sent a form without setting any test parameter.
     *  In such a case, do not save data and show the form again.
     */
//	echo 'param='.$paramlist;
    if ($paramlist != '') {
        /* Prepare the sampling minutes */
        for ($i = 15; $i < 46; $i = $i + 15) {
            $hmin = "min_" . $i;
            if ($_POST[$hmin]) {
                $tmin = $i;
                break;
            }
        }
        if (!$tmin)
            $tmin = 0;

        /* Prepare the sampling ten hours */
        if ($_POST['hrs_20'])
            $th = 20;
        elseif ($_POST['hrs_10'])
            $th = 10;

        /* Prepare the sampling one hours */
        for ($i = 0; $i < 10; $i++) {
            $h1s = 'hrs_' . $i;
            if ($_POST[$h1s]) {
                $to = $i;
                break;
            }
        }
        if (!$to)
            $to = 0;

        /* Prepare the weekday */
        for ($i = 0; $i < 7; $i++) {
            $tday = "day_" . $i;
            if ($_POST[$tday]) {
                $sday = $i;
                break;
            }
        }

        /* Finalize sampling time in TIME format */
        $sample_time = ($th + $to) . ":" . $tmin . ":00";

        return 1;
    } else {
        return 0;
    }
}

/* Start initializations */
define('LANG_FILE', 'konsil_chemlabor.php');


/* We need to differentiate from where the user is coming:
 *  $user_origin != lab ;  from patient charts folder
 *  $user_origin == lab ;  from the laboratory
 *  and set the user cookie name and break or return filename
 */
$debug = false;
if ($user_origin == 'lab') {
    $local_user = 'ck_lab_user';
    $breakfile = $root_path . "modules/laboratory_tz/labor.php" . URL_APPEND;
    if ($debug)
        echo "User Origin is $user_origin and Breakfile is " . $breakfile . "<br>";
} else {
    $local_user = 'ck_pflege_user';
    if ($user_origin == 'bill')
        $breakfile = $root_path . "modules/billing_tz/billing_tz_quotation.php";
    else
        $breakfile=$root_path . "modules/nursing/nursing-station-patientdaten.php" . URL_APPEND . "&edit=" . $edit . "&station=" . $station . "&pn=" . $pn . "&saved=insert&user_origin=" . $user_origin . "&status=" . $status . "&target=chemlabor&noresize=" . $noresize . "&batch_nr=" . $batch_nr;
    if ($debug)
        echo "User Origin is $user_origin and Breakfile is " . $breakfile . "<br>";
}

require_once($root_path . 'include/inc_front_chain_lang.php'); ///* invoke the script lock*/

$thisfile = 'nursing-station-patientdaten-doconsil-chemlabor.php';

$bgc1 = '#fff3f3'; /* The main background color of the form */
$abtname = get_meta_tags($root_path . "global_conf/$lang/konsil_tag_dept.pid");
$edit_form = 0;
$read_form = 0;

$db_request_table = $target;
$paramlist = '';
$sday = '';
$sample_time = '';
$data = array();

//echo 'Mode is '.$mode;

$formtitle = $abtname[$konsil];
define('_BATCH_NR_INIT_', 10000000);
/*
 *  The following are  batch nr inits for each type of test request
 *   chemlabor = 10000000; patho = 20000000; baclabor = 30000000; blood = 40000000; generic = 50000000;
 */

/* Here begins the real work */
include_once($root_path . 'include/care_api_classes/class_lab.php');
$lab_obj = new Lab;

/* Check for the patietn number = $pn. If available get the patients data, otherwise set edit to 0 */
if (isset($pn) && $pn) {
    include_once($root_path . 'include/care_api_classes/class_encounter.php');
    $enc_obj = new Encounter;

    if ($enc_obj->loadEncounterData($pn)) {
        $edit = true;
        $full_en = $pn;
        $_SESSION['sess_en'] = $pn;
        $_SESSION['sess_full_en'] = $full_en;
        include_once($root_path . 'include/care_api_classes/class_tz_drugsandservices.php');
        $drg_obj = new DrugsAndServices;
        include_once($root_path . 'include/care_api_classes/class_diagnostics.php');
        $diag_obj = new Diagnostics;
        $diag_obj->useChemLabRequestTable();
        $diag_obj_sub = new Diagnostics;
        $diag_obj_sub->useChemLabRequestSubTable();
        $bill_obj=new Bill();
    } else {
        $edit = 0;
        $mode = '';
        $pn = '';
    }
}

if (!isset($mode))
    $mode = '';
$coreObj = new Core;

echo 'Mode is '.$mode;

switch ($mode) {
    case 'save':
        if (prepareTestElements()) {
            $data['batch_nr'] = $batch_nr;
            $data['encounter_nr'] = $pn;
            $data['item_id'] = $pres_obj->GetItemIDByNumber('LTEST' . $value);
            $data['room_nr'] = $room_nr;
            $data['dept_nr'] = $dept_nr;
            $data['parameters'] = $paramlist;
            $data['doctor_sign'] = $doctor_sign;
            $data['highrisk'] = $_highrisk_;
            $data['notes'] = ' ';
            $data['send_date'] = date('Y-m-d H:i:s');
            $data['sample_time'] = $sample_time;
            $data['sample_weekday'] = $sday;
            $data['status'] = $status;
            $data['history'] = "Create: " . date('Y-m-d H:i:s') . " = " . $_SESSION['sess_user_name'] . "\n";
            $data['bill_number'] = $bill_nr;
            $data['bill_status'] = $bill_status;
            $data['is_disabled'] = $is_disabled;
            $data['modify_id'] = $_SESSION['sess_user_name'];
            $data['modify_time'] = date('Y-m-d H:i:s');
            $data['create_id'] = $_SESSION['sess_user_name'];
            $data['create_time'] = date('Y-m-d H:i:s');
            $data['priority'] = $urgency;
            $data['weberpsync'] = '0';
            $data['posted'] = '0';
            $diag_obj->setDataArray($data);
            if ($diag_obj->insertDataFromInternalArray()) {
                //gjergji : new lab handlign code
                //sub values management
                $diag_obj_sub->useChemLabRequestSubTable();
                $singleParam = explode("&", $paramlist);
                foreach ($singleParam as $key => $value) {
                    $tmpParam = explode("=", $value);
                    $parsedParamList['batch_nr'] = $batch_nr;
                    $parsedParamList['encounter_nr'] = $pn;
                    $parsedParamList['paramater_name'] = $tmpParam[0];
                    $parsedParamList['parameter_value'] = $tmpParam[1];
                    $diag_obj_sub->setDataArray($parsedParamList);
                    //echo 'Name: '.$tmpParam[0];
                    $diag_obj_sub->insertDataFromInternalArray();

                    $item_id = $pres_obj->GetItemIDByNumber($diag_obj->getItemNrByParamName($tmpParam[0]));
                    $diag_obj->setItemID($item_id, $batch_nr);
                }
                //TODO : check if it works - gjergji
                // Get for each lab-request-id the item_id of this lab-test out of drugsandservices-table
                //$item_id = $drg_obj->GetItemIDByNumber('LAB'.$value);
                // TODO: Set here the function to store it as bill - element -> drug_class='lab'
                //$prescription_obj->insert_prescription($pn,$item_id);
                //new code:

                $sqlInner1 = "SELECT * from care_tz_laboratory_param WHERE id='" . $parsedParamList['paramater_name'] . "'";

                $resultInner1 = $db->Execute($sqlInner1);
                $row1 = $resultInner1->FetchRow();
                //echo 'id='.$row1['id'];

                $sqlInner2 = "SELECT * from care_tz_laboratory_tests WHERE name='" . $row1['name'] . "'";

                $resultInner2 = $db->Execute($sqlInner2);
                //echo $resultInner;
                $row2 = $resultInner2->FetchRow();

                $pre_item_id = $row2['id'];
//					echo 'item id='.$pre_item_id;

                $pres_obj = new Prescription;

//                $pres_obj->insert_prescription($pn, $item_id, 1);
                  $sqlr = "SELECT k.item_id, c.batch_nr,c.encounter_nr,c.paramater_name,c.parameter_value FROM care_test_request_chemlabor_sub c LEFT JOIN care_tz_laboratory_param k 
                    ON k.id=c.paramater_name   WHERE batch_nr='$batch_nr'";

                  $resultr = $db->Execute($sqlr);
        
                while ( $rowr = $resultr->FetchRow()) {
                    // Get for each lab-request-id the item_id of this lab-test out of drugsandservices-table
//                    $item_id = $prescription_obj->GetItemIDByNumber($rowr[0]);
                    // TODO: Set here the function to store it as bill - element -> drug_class='lab'
                    $pres_obj->insert_prescription($pn, $rowr[0]);
                } // end of while (list($u,$v) = each ($param_array))
//                                
//                                 
//                                createlabQuote($batch_nr,$pn,$breakfile,$root_path);

                $IS_PATIENT_INSURED = $insurance_obj->is_patient_insured($pn);
                if (!isset($new_bill_number)) {
                    $new_bill_number = $bill_obj->checkBillEncounter($pn);
                }

                $bill_obj->setItemID($batch_nr);
                if ($debug)
                    echo "<b>billing_tz_quotation_create.php</b>";
                $in_op = $bill_obj->getEncounterClassLab($pn, $batch_nr);

                $result = $bill_obj->GetNewQuotation_Laboratory($pn, $in_op);
                if (!$result) {
                    echo "error " . mysql_error();
                } else {
                    while ($row = $result->FetchRow()) {
                        $billcounter = 0;
                        $pid = $row[pid];
                        //$batch_nr=$_REQUEST[batchno];
                        $encounter_nr = $pn;
                        $in_outpatient = 'inpatient';
                        $createid = $row['create_id'];
                        $drug_class = 'lab test';
                        $bill_nr = $row['bill_number'];
                        $parameter_name = $row['parameter_name'];
                        $labtest_nr = $row['sub_id'];
                        $item_id = $row['item_number'];
                        $description = $row['item_description'];
                        
                        //$price = $row['unit_price'];
                        $financialClass=$bill_obj->getFinancialClass($pn);
                        $price=$bill_obj->getItemPrice($item_id, $financialClass);
                        
                        $test_date = $row['create_time'];
                        $billnumber = $row['bill_number'];

                        $user = $_SESSION['sess_user_name'];
                        $encr_class = $row['encounter_class_nr'];

                        $per_obj = new Person;
                        $enc_obj = new Encounter;
                        $bill_obj = new Bill;
                        $insurance_obj = new Insurance_tz;
                        $drg_obj = new DrugsAndServices;


                        if ($debug)
                            echo 'batch_nr: ' . $batch_nr . '<br>';
                        if ($debug)
                            echo 'item_number: ' . $item_id . '<br>';
                        if ($debug)
                            echo "drug_class:" . $drug_class . "<br>";
                        if ($debug)
                            echo "old_bill_nr:" . $bill_nr . "<br>";
                        if ($debug)
                            echo "labtest_nr:" . $labtest_nr . "<br>";
                        if ($debug)
                            echo 'unit_price: ' . $price . '<br>';
                        if ($debug)
                            echo "bill number:" . $billnumber . "<br>";
                        if ($debug)
                            echo "user:" . $user . "<br>";
                        if ($debug)
                            echo "pid:" . $pid . "<br>";
                        if ($debug)
                            echo "encounter_nr:" . $pn . "<br>";
                        if ($debug)
                            echo "encounter class:" . $encr_class . "<br>";
                        if ($debug)
                            echo "Test Date:" . $test_date . "<br>";

                        if ($debug)
                            echo "Description:" . $description . "<br>";


                        $bill_obj->StoreLaboratoryItemToBill($pid, $labtest_nr, $batch_nr, $new_bill_number, $price, $description);

                        $bill_obj->UpdateBillNumberNewLaboratory($labtest_nr, $new_bill_number);
                    }
                }
                $bill_obj->updateFinalLabBill($pn, $new_bill_number);
                $sql = 'SELECT b.pid, c.unit_price AS ovamount,c.partcode,c.item_Description AS article,a.prescribe_date,
                        a.qty AS amount,a.bill_number,a.ledger as salesArea
                        FROM care_ke_billing a INNER JOIN care_tz_drugsandservices c
                        ON a.item_number=c.item_number
                        INNER JOIN care_encounter b
                        ON a.encounter_nr=b.encounter_nr and a.encounter_nr="'. $pn .'" and service_type like "lab%"';
                $result = $db->Execute($sql);
                if ($debug)
                    echo $sql;
//                if ($weberp_obj = new_weberp()) {
//                    //$arr=Array();
//
//                    while ($row = $result->FetchRow()) {
//                        //$weberp_obj = new_weberp();
//                        if (!$weberp_obj->transfer_bill_to_webERP_asSalesInvoice($row)) {
//                            $sql = "update care_test_request_chemlabor set weberpSync=1 where weberpSync=0";
//                            $db->Execute($sql);
//                            $sql2 = "update care_ke_billing set weberpSync=1 where weberpSync=0";
//                            $db->Execute($sql2);
//                        } else {
//                            echo 'failed to transmit to weberp';
//                        }
//                        destroy_weberp($weberp_obj);
//                    }
//                } else {
//                    return 'could not create object: debug level ';
//                }

                // Load the visual signalling functions
                include_once($root_path . 'include/inc_visual_signalling_fx.php');
                // Set the visual signal
                setEventSignalColor($pn, SIGNAL_COLOR_DIAGNOSTICS_REQUEST);
                //print_r($data);
//				echo "The breakfile is :".$breakfile;
                //$batch_nr&pid=$h_pid&encounterNr=$pn
//                                echo $sql;
                /* If the findings are saved, signal the availability of report
                 */
//                                	header("location:".$breakfile);

                header("location:" . $root_path . "modules/laboratory_tz/labor_test_request_aftersave.php" . URL_REDIRECT_APPEND . "&edit=$edit&saved=insert&pn=$pn&station=$station&user_origin=$user_origin&status=$status&target=chemlabor&noresize=$noresize&batch_nr=$batch_nr");
//
                exit;

//                             header("location:".$root_path."modules/laboratory_tz/labor_test_request_aftersave.php".URL_REDIRECT_APPEND."&edit=$edit&saved=insert&pn=$pn&station=$station&user_origin=$user_origin&status=$status&target=chemlabor&noresize=$noresize&batch_nr=$batch_nr");
//		      header("location:".$root_path."modules/laboratory_tz/labor_test_request_aftersave.php?sid=".$sid."&lang=".$lang."&edit=".$edit."&saved=insert&pn=".$pn."&station=".$station."&user_origin=".$user_origin."&status=".$status."&target=".$target."&noresize=".$noresize."&batch_nr=".$batch_nr);
//                              header("location:".$root_path."modules/laboratory_tz/labor_test_request_aftersave.php".URL_REDIRECT_APPEND."&edit=$edit&saved=insert&pn=$pn&station=$station&user_origin=$user_origin&status=$status&target=chemlabor&noresize=$noresize&batch_nr=$batch_nr");
//                             header("location:".$root_path."modules/laboratory_tz/labor_test_request_aftersave.php".URL_REDIRECT_APPEND."&edit=$edit&saved=insert&pn=$pn&station=$station&user_origin=$user_origin&status=$status&target=chemlabor&noresize=$noresize&batch_nr=$batch_nr");
            } else {
                echo "wooops: <p>$sql<p>$LDDbNoSave";
                $mode = '';
            }
        } //end of prepareTestElements()

        break; // end of case 'save'

    case 'update':
        if (prepareTestElements()) {
           // echo $sql;
            $data['batch_nr'] = $batch_nr;
            $data['room_nr'] = $room_nr;
            $data['dept_nr'] = $dept_nr;
            $data['parameters'] = $paramlist;
            $data['doctor_sign'] = $doctor_sign;
            $data['highrisk'] = $_highrisk_;
            $data['notes'] = $notes;
            $data['sample_time'] = $sample_time;
            $data['sample_weekday'] = $sday;
            $data['status'] = $status;
            $data['history'] = "CONCAT(history,'Update: " . date('Y-m-d H:i:s') . " = " . $_SESSION['sess_user_name'] . "\n')";
            $data['is_disabled'] = $is_disabled;
            $data['modify_id'] = $_SESSION['sess_user_name'];
            $data['modify_time'] = date('Y-m-d H:i:s');
            $data['priority'] = $urgency;
            $diag_obj->setDataArray($data);
            $diag_obj->setWhereCond(" batch_nr=$batch_nr");
            if ($diag_obj->updateDataFromInternalArray($batch_nr)) {
                $diag_obj_sub->useChemLabRequestSubTable();
//                echo " <br>paramlist is " .$paramlist;
                $singleParam = explode("&", $paramlist);
                foreach ($singleParam as $key => $value) {
                    $tmpParam = explode("=", $value);
                    $parsedParamList['batch_nr'] = $batch_nr;
                    $parsedParamList['encounter_nr'] = $pn;
                    $parsedParamList['paramater_name'] = $tmpParam[0];
                    $parsedParamList['parameter_value'] = $tmpParam[1];
                    $diag_obj_sub->setDataArray($parsedParamList);
                    $diag_obj_sub->setWhereCond(" batch_nr=$batch_nr");
                    $diag_obj_sub->updateDataFromInternalArray($batch_nr);
                    $item_id = $pres_obj->GetItemIDByNumber($diag_obj->getItemNrByParamName($tmpParam[0]));
                    $diag_obj->setItemID($item_id, $batch_nr);
                }
                // Load the visual signalling functions
                include_once($root_path . 'include/inc_visual_signalling_fx.php');
                // Set the visual signal
                setEventSignalColor($pn, SIGNAL_COLOR_DIAGNOSTICS_REQUEST);
                header("location:" . $root_path . "modules/laboratory_tz/labor_test_request_aftersave.php" . URL_REDIRECT_APPEND . "&edit=" . $edit . "&saved=update&pn=" . $pn . "&station=" . $station . "&user_origin=" . $user_origin . "&status=" . $status . "&target=chemlabor&batch_nr=" . $batch_nr . "&noresize=" . $noresize);
                exit;
            } else {
                echo "Woopssss: <p>$sql<p>$LDDbNoSave";
                $mode = "";
            }
        } //end of prepareTestElements()

        break; // end of case 'save'


    /* If mode is edit, get the stored test request when its status is either "pending" or "draft"
     *  otherwise it is not editable anymore which happens when the lab has already processed the request,
     *  or when it is discarded, hidden, locked, or otherwise.
     *
     *  If the "parameter" element is not empty, parse it to the $stored_param variable
     */
    case 'edit':
        //echo $batch_nr;
        $sql = "SELECT * FROM care_test_request_" . $db_request_table . "  WHERE batch_nr='" . $batch_nr . "' AND (status='pending' OR status='draft' OR status='')";
        // echo $sql;
        if ($ergebnis = $db->Execute($sql)) {
            if ($editable_rows = $ergebnis->RecordCount()) {

                $stored_request = $ergebnis->FetchRow();

                if ($stored_request['parameters'] != '') {

                    //echo $stored_request['parameters'];
                    parse_str($stored_request['parameters'], $stored_param);
                    $edit_form = 1;
                }
            }

            //if edit is enabled the remove all previous billing data from care2x and weberp
//            removeLabBilling($pn,$batch_nr);
        }

        break; ///* End of case 'edit': */

    default: $mode = "";
}// end of switch($mode)


//function removeLabBilling($encounter_nr,$batch_nr){
//    global $db;
//
//    $sql="Delete from care_ke_billing"
//}

if (!$mode) /* Get a new batch number */ {

    $sql = "SELECT batch_nr FROM care_test_request_" . $db_request_table . "  ORDER BY batch_nr DESC";
    if ($ergebnis = $db->SelectLimit($sql, 1)) {
        if ($batchrows = $ergebnis->RecordCount()) {
            $bnr = $ergebnis->FetchRow();
            $batch_nr = $bnr['batch_nr'];
            if (!$batch_nr)
                $batch_nr = _BATCH_NR_INIT_; else
                $batch_nr++;
        }
        else {
            $batch_nr = _BATCH_NR_INIT_;
        }
    } else {
        echo "<p>$sql<p>$LDDbNoRead";
        exit;
    }
    $mode = "save";
}

if (!isset($edit))
    $edit = FALSE;

# Start Smarty templating here
/**
 * LOAD Smarty
 */
# Note: it is advisable to load this after the inc_front_chain_lang.php so
# that the smarty script can use the user configured template theme

require_once($root_path . 'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care('common');

# Title in toolbar
$smarty->assign('sToolbarTitle', "$LDDiagnosticTest :: $LDCentralLab");

# href for help button

$smarty->assign('pbHelp', "javascript:gethelp('laboratory_testrequest.php','Laboratories :: Test Request','$user_origin')");

# hide return  button
$smarty->assign('pbBack', FALSE);

# href for close button
$smarty->assign('breakfile', "javascript:window.close()");

# Window bar title
$smarty->assign('sWindowTitle', "$LDDiagnosticTest :: $LDCentralLab");

# Prepare new form start button
if ($user_origin == 'lab' && $pn) {
    $smarty->assign('gifAux1', createLDImgSrc($root_path, 'newpat2.gif', '0'));
    $smarty->assign('pbAux1', $thisfile . URL_APPEND . "&station=$station&user_origin=$user_origin&status=$status&target=$target&noresize=$noresize");
}

# Prepare Body onLoad javascript code
$sTemp = 'onLoad="if (window.focus) window.focus(); loadM(\'form_test_request\');';
if ($pn == "")
    $sTemp = $sTemp . 'document.searchform.searchkey.focus();';

$smarty->assign('sOnLoadJs', $sTemp . '"');

# collect extra javascript code
ob_start();
?>

<style type="text/css">
    .lab {
        font-family: arial;
        font-size: 9px;
        color: purple;
    }

    .lmargin {
        margin-left: 5px;
    }
    .container {
        position: relative;
        width: 100%;
    }

    .right {
        position: absolute;
        right: -100px;
        width: 200px;
        background-color: #ff7870;
        font-size: smaller;
    }

    .left {
        position: absolute;
        right: -202px;
        width: 100px;
        background-color: #b0e0e6;
        font-size: smaller;
    }
</style>

<script language="javascript">
    <!--

    var xmlHttp = createXmlHttpRequestObject();
    // retrieves the XMLHttpRequest object
    function GetXmlHttpObject()
    {
        if (window.XMLHttpRequest)
        {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            return new XMLHttpRequest();
        }
        if (window.ActiveXObject)
        {
            // code for IE6, IE5
            return new ActiveXObject("Microsoft.XMLHTTP");
        }
        return null;
    }



    function chkForm(d){
        return true
    }

    function loadM(fn){
        mBlank=new Image();
        mBlank.src="b.gif";
        mFilled=new Image();
        mFilled.src="f.gif";

        form_name=fn;
    }


    function setM(m){
        eval("marker=document.images."+m);
        eval("element=document."+form_name+"."+m);

        if(marker.src!=mFilled.src)	{
            marker.src=mFilled.src;
            element.value=1;
//            alert(element.name);
            strElem=element.name;

            getItemCosts(strElem)

        }else{
            marker.src=mBlank.src;
            element.value=0;
//            alert(element.name+element.value);
            resetItemCosts(strElem);
        }
    }

    function resetItemCosts(elem){
        document.getElementById('CostsTag').innerHTML="";
        document.getElementById('CostsTag2').innerHTML="";


    }



    function getItemCosts(elem){
        xmlhttp=GetXmlHttpObject();
        if (xmlhttp==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="getData.php?task=getLabcost";
        url=url+"&id="+elem;
        url=url+"&sid="+Math.random();
        xmlhttp.onreadystatechange=stateChanged4;
        xmlhttp.open("POST",url,true);
        xmlhttp.send(null);
    }

    function stateChanged4()
    {
        //get payment description
        if (xmlhttp.readyState==4)//show point desc
        {
            var str=xmlhttp.responseText;
            var str3=str.split(",");
            document.getElementById('CostsTag').innerHTML=document.getElementById('CostsTag').innerHTML+"<br>"+str3[0];
            document.getElementById('CostsTag2').innerHTML=document.getElementById('CostsTag2').innerHTML+"<br>"+parseFloat(str3[1]);
        }
    }

    function setThis(prep,elem,begin,end,step){
        for(i=begin;i<end;i=i+step)  {
            x=prep + i;
            if(elem!=i)     {
                eval("marker=document.images."+x);
                if(marker.src==mFilled.src)  setM(x);
            }
        }
        setM(prep+elem);
    }

    function sendLater(){
        document.form_test_request.status.value="draft";
        if(chkForm(document.form_test_request)) document.form_test_request.submit();
    }

    function printOut(){
        urlholder="<?php echo $root_path; ?>modules/nursing/lab_request_pdf.php?sid=<?php echo $sid ?>&lang=<?php echo $lang ?>&user_origin=<?php echo $user_origin ?>&subtarget=chemlabor&batch_nr=<?php echo $batch_nr ?>&pn=<?php echo $pn; ?>";

        testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=200,height=600,menubar=no,resizable=yes,scrollbars=yes");
        testprintout<?php echo $sid ?>.print();
    }

    function selectAllParams(group_id) {
        var r = document.getElementById('table_param');
        var rA = r.getElementsByTagName('*');
        var x,i = 0;
        while(x = rA[i++]){
            if(a = x.id) {
                param = a.substr(-(group_id.length),group_id.length);
                if( param == group_id) {
                    setM(a);
                }
            }
        }
    }
<?php require($root_path . 'include/inc_checkdate_lang.php'); ?>

    //-->
</script>
<script
language="javascript" src="<?php echo $root_path; ?>js/setdatetime.js"></script>
<script
language="javascript" src="<?php echo $root_path; ?>js/checkdate.js"></script>

<?php
$sTemp = ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript', $sTemp);

# Buffer page output

ob_start();

# Show list and actual form

if (!$noresize) {
    ?>

    <script>
        window.moveTo(0,0);
        window.resizeTo(1000,740);
    </script>

    <?php
}
?>

<ul>
    <?php
    if ($edit) {
        ?>
        <form name="form_test_request" method="post"
              action="<?php echo $thisfile ?>"><?php
    /* If in edit mode display the control buttons */

    $controls_table_width = 1000;

    require($root_path . 'include/inc_test_request_controls.php');
    } elseif (!$read_form && !$no_proc_assist) {
        ?>

            <table border=0 align="left" width="">
                <tr>
                    <td><img
                  <?php echo createMascot($root_path, 'mascot1_r.gif', '0', 'absmiddle') ?>></td>
                    <td class="prompt"><?php echo $LDPlsSelectPatientFirst ?></td>
                    <td valign="bottom"><img
    <?php echo createComIcon($root_path, 'angle_down_r.gif', '0', '', TRUE) ?>></td>
                </tr>
            </table>
    <?php
}
?> <br>

        <!-- outermost table for the form -->
        <table border=0 cellpadding=1 cellspacing=0 id="table_param"
               bgcolor="#606060">
            <tr>
                <td><!-- table for the form simulating the border -->
                    <table border=0 cellspacing=0 cellpadding=0 bgcolor="white">
                        <tr>
                            <td><!-- Here begins the table for the form  -->

                                <table cellpadding=0 cellspacing=0 border=0 width=1000>
                                    <tr valign="top">

                                        <td bgcolor="<?php echo $bgc1 ?>">
                                            <div class="lmargin"><font size=3 color="#990000" face="arial"> <?php
                        if ($edit) {
                            /*
                              ?>
                              <input type="text" name="room_nr" size=10 maxlength=10
                              value="<?php
                              if($edit_form||$read_form) echo stripslashes($stored_request['room_nr']);
                              else   echo $_COOKIE['ck_thispc_room']
                              ?>">
                              <?php
                             */
                        } else {
                            if ($edit_form || $read_form)
                                echo stripslashes($stored_request['room_nr']);
                        }
?> <!--  Table for the day and month code -->


                                                <table border=0 cellspacing=0 cellpadding=0 width="1">
                                                    <tr align="center">

                                                    </tr>
                                                    <tr align="center">

                                                    </tr>

                                                    <tr align="center">
                                                    </tr>
                                                    <tr align="center">
                                                    </tr>
                                                    <!-- Input blocks for 10, 20 Time row -->
                                                    <tr align="center">
                                                        <td><font size=1 face="arial" color="purple"></font></td>
                                                        <td colspan=8><font size=1 face="arial" color="purple"></font></td>

                                                    </tr>

                                                    <tr align="center">
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                    </tr>
                                                </table></font></div>
                                        </td>

                                        <!-- Middle block of first row -->
                                        <td bgcolor="<?php echo $bgc1 ?>">
                                            <table border=1 cellpadding=0 bgcolor="">
                                                <tr>
                                                    <td><?php
                                                if ($edit) {
                                                    $sql_headline = "SELECT     care_person.pid,
                                                                  care_person.selian_pid,
                                                                  name_first,
                                                                  name_last,
                                                                  sex,
                                                                  care_encounter.encounter_nr,
                                                                  date_birth
                                                       FROM care_person, care_encounter
                                                       WHERE care_encounter.pid = care_person.pid AND care_encounter.encounter_nr ='" . $pn . "'";

                                                    if ($h_requests = $db->Execute($sql_headline)) {
                                                        if ($test_request_headline = $h_requests->FetchRow()) {
                                                            $h_pid = $test_request_headline['pid'];
                                                            $h_batch_nr = $test_request_headline['batch_nr'];
                                                            $h_encounter_nr = $test_request_headline['encounter_nr'];
                                                            $h_selian_file_number = $test_request_headline['selian_pid'];
                                                            $h_name_first = $test_request_headline['name_first'];
                                                            $h_name_last = $test_request_headline['name_last'];
                                                            $h_birthdate = $test_request_headline['date_birth'];
                                                            $h_sex = $test_request_headline['sex'];
                                                            if ($_sex == "f")
                                                                $h_sex_img = "spf.gif";
                                                            else
                                                                $h_sex_img="spm.gif";
                                                        } // end of if ($test_request_headline = $h_requests->FetchRow())
                                                    } // end of if($h_requests=$db->Execute($sql_headline))
                                                    echo '</td>
                                                            <td width="25%">
                                                            <font color="purple">' . $LDHospitalFileNr . '</font>
                                                                <font color="#ffffee" class="vi_data"><b>' . $h_selian_file_number . '</font>
                                                            </td>
                                                            <td width="25%">

                                                            <font color="purple">' . $LDPatientID . '</font>
                                                                <font color="#ffffee" class="vi_data"><b>' . $h_pid . '</font>
                                                            </td>
                                                            <td width="25%">
                                                                <font color="purple">	' . $LDSurnameUkoo . '</font>
                                                                <font color="#ffffee" class="vi_data"><b>
                                                                ' . $h_name_last . '</b></font>
                                                            </td>

                                                            <td width="25%">
                                                            <font color="purple"> ' . $LDFirstName . '</font>
                                                            <font color="#ffffee" class="vi_data"><b>
                                                                ' . $h_name_first . ' </b></font>
                                                            </td>
                                                        </tr>

                                                        <tr><td color="#ffffee" class="vi_data"colspan="2"><b>Edit Mode: </b>The Payment Method is  </td>';
                                                            echo '<td color="#ffffee" class="vi_data" colspan="6" align="left">'.$billing_tz->getBillPaymentMethod($h_pid).'';
                                                            echo '<div class="container">
                                                                        <div class="right" id="CostsTag" style="border-left:20px;"></div>
                                                                        <div class="left" id="CostsTag2" style="border-left:20px;"></div>
                                                                  </div></td></tr>
                                                        <tr>';
                                                    //echo '<img src="'.$root_path.'main/imgcreator/barcode_label_single_large.php?sid='.$sid.'&lang='.$lang.'&fen='.$full_en.'&en='.$pn.'" width=282 height=178>';
                                                }
                                                elseif (empty($pn)) {
                                                    $searchmask_bgcolor = 'white';
                                                    include($root_path . 'include/inc_test_request_searchmask.php');
                                                }
?>
                                                    </td>
                                            </table>
                                        </td>


                                        <td bgcolor="<?php echo $bgc1 ?>" align="right"><!--  Block for the casenumber codes -->
                                            <table border=0 cellspacing=0 cellpadding=0>
                                                <tr align="center">
                                                </tr>

                                                <tr>
                                                </tr>

                                                <tr>
                                                    <td colspan=10 align="right"><?php
                                                        /* Barcode for the batch nr */

                                                        //echo '<font size=1 color="#990000" face="verdana,arial">'.$batch_nr.'</font>&nbsp;&nbsp;<br>';
                                                        /**
                                                         *  The barcode image is first searched in the cache. If present, it will be displayed.
                                                         *  Otherwise an image will be generated, stored in the cache and displayed.
                                                         */
                                                        /* $in_cache=1;

                                                          if(!file_exists('../cache/barcodes/form_'.$batch_nr.'.png'))
                                                          {
                                                          echo "<img src='".$root_path."classes/barcode/image.php?code=".$batch_nr."&style=68&type=I25&width=145&height=40&xres=2&font=5&label=1&form_file=1' border=0 width=0 height=0>";
                                                          if(!file_exists($root_path.'cache/barcodes/form_'.$batch_nr.'.png'))
                                                          {
                                                          echo "<img src='".$root_path."classes/barcode/image.php?code=".$batch_nr."&style=68&type=I25&width=145&height=40&xres=2&font=5' border=0>";
                                                          $in_cache=0;
                                                          }
                                                          }

                                                          if($in_cache)   echo '<img src="'.$root_path.'cache/barcodes/form_'.$batch_nr.'.png"  border=0>';

                                                          /* Prepare the narrow batch nr barcode for specimen labels
                                                          if(!file_exists('../cache/barcodes/lab_'.$batch_nr.'.png'))
                                                          {
                                                          echo "<img src='".$root_path."classes/barcode/image.php?code=".$batch_nr."&style=68&type=I25&width=145&height=60&xres=1&font=5&label=1&form_file=lab' border=0 width=0 height=0>";
                                                          }
                                                         */
?></td>
                                                </tr>

                                            </table>

                                        </td>

                                    </tr>
                                    <!--  The  row for batch number -->
                                    <tr bgcolor="<?php echo $bgc1 ?>">
                                        <td>&nbsp;</td>
                                        <td>
                                            <table cellpadding="0" border="0" bgcolor="">
                                                <tbody>
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <td><FONT SIZE=1 FACE="Arial" color="#000066"><?php echo 'Urgency : '; ?></FONT></td>
                                                        <td><FONT SIZE=1 FACE="Arial" color="#000066"> <input
                                                                type="radio" name="urgency" value="0"
<?php if ($urgency == 0)
    echo 'checked'; ?>><?php echo 'Normal'; ?></FONT></td>
                                                        <td><input type="radio" name="urgency" value="3"
<?php if ($urgency == 3)
    echo 'checked'; ?>><?php echo 'Priority'; ?></td>
                                                        <td><input type="radio" name="urgency" value="5"
<?php if ($urgency == 5)
    echo 'checked'; ?>><?php echo 'Urgent'; ?></td>
                                                        <td><input type="radio" name="urgency" value="7"
                                                                <?php if ($urgency == 7)
                                                                    echo 'checked'; ?>><?php echo 'Emergency'; ?></td>
                                                        <td width=10%>&nbsp;</td>
                                                        <td align="left" border="1"></td>
                                                        <td width=20% align="left" border="1"><font size=1
                                                                                                    color="purple" face="verdana,arial"><?php echo $LDBatchNumber ?></font><font
                                                                                                    color="#000000" size=2> <?php echo $batch_nr ?></td>
                                                        <td width="25%" align="right" border="1"><?php $user = $_SESSION['sess_user_name'] ?>
                                                            <font size=1 color="purple" face="verdana,arial"><?php echo $LDDoctorRequest ?></font><font
                                                                color="#000000" size=2> <?php echo $enc_obj->ConsultingDr($pn) ?></font>

                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>

                                        <td>&nbsp;</td>

                                    </tr>

                                </table>

                                <!--  The test parameters begin  -->

                                <table border=0 cellpadding=0 cellspacing=0 width=1000
                                       bgcolor="<?php echo $bgc1 ?>">
<?php
# Start buffering the output
ob_start();
for ($i = 0; $i <= $max_row; $i++) {
    echo '<tr class="lab">';
    for ($j = 0; $j <= $column; $j++) {
        if ($LD_Elements[$j][$i]['type'] == 'top') {
            echo '<td bgcolor="#ee6666" colspan="2" onclick="selectAllParams(\'' . $LD_Elements[$j][$i]['id'] . '\');"><font color="white" style="cursor : pointer;">&nbsp;<b>' . $LD_Elements[$j][$i]['value'] . '</b></font></td>';
        } else {
            if ($LD_Elements[$j][$i]['value']) {
                echo '<td>';
                if ($edit) {
                    if (isset($stored_param[$LD_Elements[$j][$i]['id']]) && !empty($stored_param[$LD_Elements[$j][$i]['id']])) {
                        echo '<input type="hidden" name="' . $LD_Elements[$j][$i]['id'] . '" value="1">
							<a href="javascript:setM(\'' . $LD_Elements[$j][$i]['id'] . '\')">';
                    } else {
                        echo '<input type="hidden" name="' . $LD_Elements[$j][$i]['id'] . '" value="0">
							<a href="javascript:setM(\'' . $LD_Elements[$j][$i]['id'] . '\')">';
                    }
                }
                if (isset($stored_param[$LD_Elements[$j][$i]['id']]) && !empty($stored_param[$LD_Elements[$j][$i]['id']])) {
                    echo '<img src="f.gif" border=0 width=18 height=6 id="' . $LD_Elements[$j][$i]['id'] . '">';
                } else {
                    echo '<img src="b.gif" border=0 width=18 height=6 id="' . $LD_Elements[$j][$i]['id'] . '">';
                } if ($edit) {
                    echo '</a>';
                }
                echo '</td><td>';
                if ($edit)
                    echo '<a href="javascript:setM(\'' . $LD_Elements[$j][$i]['id'] . '\')">' . $LD_Elements[$j][$i]['value'] . '</a>';
                else
                    echo $LD_Elements[$j][$i]['value'];
                echo '</td>';
            } else {
                echo '<td colspan=2>&nbsp;</td>';
            }
        }
    }
    echo '</tr><tr>';
    if ($i < $max_row) {
        for ($k = 0; $k <= $column; $k++) {
            echo '<td bgcolor="#ffcccc" colspan=2><img src="p.gif"  width=1 height=1></td>';
        }
        echo '</tr>';
    }
    echo '</td>';
}

//$sTemp=ob_get_contents();
ob_end_flush();
//echo $sTemp;
?>
                                </table>
                                <table border=0 cellpadding=0 cellspacing=0 width=745
                                       bgcolor="<?php echo $bgc1 ?>">
                                    <tr>
                                        <td colspan=9><!-- deleted 4th of july 2006 --> &nbsp;</td>
                                        <td colspan=11><!-- deleted 4th of july 2006 --> &nbsp;</td>
                                    </tr>
                                    <tr>
                                            <!--<td colspan=20><font size=2 face="verdana,arial" color="purple">&nbsp;<?php echo $LDEmergencyProgram . ' &nbsp;&nbsp;&nbsp;<img ' . createComIcon($root_path, 'violet_phone.gif', '0', 'absmiddle', TRUE) . '> ' . $LDPhoneOrder ?></td>-->
                                    </tr>

                                </table>
                                <!-- End of the main table holding the form --></td>
                        </tr>
                    </table>
                    <!-- End of table simulating the border --></td>
            </tr>
        </table>
        <!--  End of the outermost table bordering the form -->
        <p><?php
                                           if ($edit) {

                                               /* If in edit mode display the control buttons */
                                               require($root_path . 'include/inc_test_request_controls.php');

                                               require($root_path . 'include/inc_test_request_hiddenvars.php');
    ?>

        </form>

                <?php
            }
            ?>

</ul>

<?php
$sTemp = ob_get_contents();
ob_end_clean();

# Assign the page output to main frame template

$smarty->assign('sMainFrameBlockData', $sTemp);

/**
 * show Template
 */
$smarty->display('common/mainframe.tpl');
?>
