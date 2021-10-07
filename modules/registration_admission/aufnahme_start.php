<?php
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
//error_reporting(E_ALL);
require('./roots.php');
require($root_path . 'include/inc_environment_global.php');
/**
 * CARE2X Integrated Hospital Information System beta 2.0.1 - 2004-07-04
 * GNU General Public License
 * Copyright 2002,2003,2004,2005 Elpidio Latorilla
 * elpidio@care2x.org,
 *
 * See the file "copy_notice.txt" for the licence notice
 */
$lang_tables[] = 'departments.php';
$lang_tables[] = 'prompt.php';
$lang_tables[] = 'help.php';
$lang_tables[] = 'person.php';
define('LANG_FILE', 'aufnahme.php');
$local_user = 'aufnahme_user';
require($root_path . 'include/inc_front_chain_lang.php');

/* If patient nr is invallid jump to registration search module */
/* if(!isset($pid) || !$pid)
  {
  header('Location:patient_register_search.php'.URL_APPEND.'&origin=admit');
  exit;
  }
 */
//require_once($root_path.'include/inc_config_color.php');
require_once($root_path . 'include/inc_date_format_functions.php');
require_once($root_path . 'include/care_api_classes/class_person.php');
require_once($root_path . 'include/care_api_classes/class_insurance.php');
require_once($root_path . 'include/care_api_classes/class_tz_insurance.php');
require_once($root_path . 'include/care_api_classes/class_core.php');
require_once($root_path . 'include/care_api_classes/class_ward.php');
require_once($root_path . 'include/care_api_classes/class_encounter.php');
require_once($root_path . 'include/care_api_classes/class_globalconfig.php');
require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
require_once($root_path . 'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path . 'include/inc_init_xmlrpc.php');

$thisfile = basename($_SERVER['PHP_SELF']);
if ($origin == 'patreg_reg')
    $breakfile = 'patient_register_show.php' . URL_APPEND . '&pid=' . $pid;
elseif ($_COOKIE["ck_login_logged" . $sid])
    $breakfile = $root_path . 'main/startframe.php' . URL_APPEND;
elseif (!empty($_SESSION['sess_path_referer']))
    $breakfile = $root_path . $_SESSION['sess_path_referer'] . URL_APPEND . '&pid=' . $pid;
else
    $breakfile = "aufnahme_pass.php" . URL_APPEND . "&target=entry";

$newdata = 1;

/* Default path for fotos. Make sure that this directory exists! */
$default_photo_path = $root_path . 'fotos/registration';
$photo_filename = 'nopic';
$error = 0;

if (!isset($pid))
    $pid = 0;
if (!isset($encounter_nr))
    $encounter_nr = 0;
if (!isset($mode))
    $mode = '';
if (!isset($forcesave))
    $forcesave = 0;
if (!isset($update))
    $update = 0;

if (!$_SESSION['sess_pid'])
    $_SESSION['sess_pid'];
if (!$_SESSION['sess_full_pid'])
    $_SESSION['sess_full_pid'];
if (!$_SESSION['sess_en'])
    $_SESSION['sess_en'];
if (!$_SESSION['sess_full_en'])
   $_SESSION['sess_full_en'];

$patregtable = 'care_person';  // The table of the patient registration data

$dbtable = 'care_encounter'; // The table of admission data

/* Create new person's insurance object */
$pinsure_obj = new PersonInsurance($pid);
$insurance_obj=new Insurance_tz();
/* Get the insurance classes */
$insurance_classes = $pinsure_obj->getInsuranceClassInfoObject('class_nr,name,LD_var AS "LD_var"');


/* Create new person object */
$person_obj = new Person($pid);
/* Create encounter object */
$encounter_obj = new Encounter($encounter_nr);
/* Create a new billing object */
$bill_obj = new Bill;
/* Get all encounter classes */
$encounter_classes = $encounter_obj->AllEncounterClassesObject();

if ($pid != '' || $encounter_nr != '') {

    /* Get the patient global configs */
    $glob_obj = new GlobalConfig($GLOBAL_CONFIG);
    $glob_obj->getConfig('patient_%');
    $glob_obj->getConfig('person_foto_path');
    $glob_obj->getConfig('encounter_%');

    if (!$GLOBAL_CONFIG['patient_service_care_hide']) {
        /* Get the care service classes */
        $care_service = $encounter_obj->AllCareServiceClassesObject();
    }
   // echo $GLOBAL_CONFIG['patient_service_room_hide'];
    
    if (!$GLOBAL_CONFIG['patient_service_room_hide']) {
        /* Get the room service classes */
        $room_service = $encounter_obj->AllRoomServiceClassesObject();
    }
    if (!$GLOBAL_CONFIG['patient_service_att_dr_hide']) {
        /* Get the attending doctor service classes */
        $att_dr_service = $encounter_obj->AllAttDrServiceClassesObject();
    }

    /* Check whether config path exists, else use default path */
    $photo_path = (is_dir($root_path . $GLOBAL_CONFIG['person_foto_path'])) ? $GLOBAL_CONFIG['person_foto_path'] : $default_photo_path;

    if ($pid) {
        if (isset($transFromOutp)) {
            //echo 'transFrom Outpatient';
            /* Check whether the person is currently admitted. If yes jump to display admission data */
            if (!$update && $encounter_nr = $encounter_obj->isPIDCurrentlyAdmitted($pid)) {
                echo "Patient is Currently Admitted ";

                header('Location:aufnahme_daten_zeigen.php' . URL_REDIRECT_APPEND . '&encounter_nr=' . $encounter_nr . '&origin=admit&sem=isadmitted&target=entry');
                exit;
            }

            if ($encoder == '')
                $encoder = $_SESSION['sess_user_name'];
            # Load date formatter

            require_once($root_path . 'include/care_api_classes/class_encounter.php');

            $enc_obj = new Encounter;

            $pn = $_GET['pn'];

            //echo 'Encounter: '.$pn;
            //$encounter_obj->sql="UPDATE care_encounter SET encounter_nr_prev=$pn where encounter_nr=$pn";
            //
            //$encounter_obj->Transact($encounter_obj->sql);

            if ($encounter_obj->loadEncounterData($pn)) {
                //$db->debug=1;

                $date = (empty($x_date)) ? date('Y-m-d') : formatDate2STD($x_date, $date_format,'/');
                $time = (empty($x_time)) ? date('H:i:s') : convertTimeToStandard($x_time);
                # Check the discharge type


                if ($enc_obj->DischargeFromDept($pn, 8, $date, $time,1)) {
                    //echo 'discharge has been successfull';


                }
                else
                    echo 'couldn\'t discharge outpatient';
            }else
                echo 'could not load encounter data';
        }else {

            /* Check whether the person is currently admitted. If yes jump to display admission data */
            if (!$update && $encounter_nr = $encounter_obj->isPIDCurrentlyAdmitted($pid)) {
                header('Location:aufnahme_daten_zeigen.php' . URL_REDIRECT_APPEND . '&encounter_nr=' . $encounter_nr . '&origin=admit&sem=isadmitted&target=entry');
                exit;
            }
        }

        /* Get the related insurance data */
        $p_insurance = &$pinsure_obj->getPersonInsuranceObject($pid);
        if ($p_insurance == false) {
            $insurance_show = true;
        } else {
            if (!$p_insurance->RecordCount()) {
                $insurance_show = true;
            } elseif ($p_insurance->RecordCount() == 1) {
                $buffer = $p_insurance->FetchRow();
                extract($buffer);
                $insurance_show = true;
                $insurance_firm_name = $pinsure_obj->getFirmName($insurance_firm_id);
            } else {
                $insurance_show = false;
            }
        }


        if (($mode == 'save') || ($forcesave != '')) {

                if (!$forcesave) {
                    //clean and check input data variables
                    /**
                     *  $error = 1 will cause to show the "save anyway" override button to save the incomplete data
                     *  $error = 2 will cause to force the user to enter a data in an input element (no override allowed)
                     */
//


                    $encoder = trim($encoder);
                    if ($encoder == '')
                        $encoder = $_SESSION['sess_user_name'];

                    /* if ($referrer_notes=='') { $errorbesonder=1; $error=1; $errornum++;};

                      $encounter_class_nr=trim($encounter_class_nr);

                      if ($encounter_class_nr=='') { $errorstatus=1; $error=1; $errornum++;};

                      if($insurance_show) {
                      if(trim($insurance_nr) &&  trim($insurance_firm_name)=='') { $error_ins_co=1; $error=1; $errornum++;}
                      }
                     */
                }


                if (!$error) {

                    /* if ($is_transmit_to_weberp_enable == 1) {
                        $persondata = $person_obj->getAllInfoArray();
                        $weberp_obj = new_weberp();
                        if (!$weberp_obj->transfer_patient_to_webERP_asCustomer($pid, $persondata)) {
                            $person_obj->setPatientIsTransmit2ERP($pid, 0);
                        } else {
                            $person_obj->setPatientIsTransmit2ERP($pid, 1);
                        }
                        destroy_weberp($weberp_obj);
                    } */
                    if (!$GLOBAL_CONFIG['patient_service_care_hide']) {
                        if (!empty($sc_care_start))
                            $sc_care_start = formatDate2Std($sc_care_start, $date_format, '/');
                        if (!empty($sc_care_end))
                            $sc_care_end = formatDate2Std($sc_care_end, $date_format, '/');
                        $care_class = compact('sc_care_nr', 'sc_care_class_nr', 'sc_care_start', 'sc_care_end', 'encoder');
                    }
                    if (!$GLOBAL_CONFIG['patient_service_room_hide']) {
                        if (!empty($sc_room_start))
                            $sc_room_start = formatDate2Std($sc_room_start, $date_format, '/');
                        if (!empty($sc_room_end))
                            $sc_room_end = formatDate2Std($sc_room_end, $date_format, '/');
                        $room_class = compact('sc_room_nr', 'sc_room_class_nr', 'sc_room_start', 'sc_room_end', 'encoder');
                    }
                    if (!$GLOBAL_CONFIG['patient_service_att_dr_hide']) {
                        if (!empty($sc_att_dr_start))
                            $sc_att_dr_start = formatDate2Std($sc_att_dr_start, $date_format, '/');
                        if (!empty($sc_att_dr_end))
                            $sc_att_dr_end = formatDate2Std($sc_att_dr_end, $date_format, '/');
                        $att_dr_class = compact('sc_att_dr_nr', 'sc_att_dr_class_nr', 'sc_att_dr_start', 'sc_att_dr_end', 'encoder');
                    }

                    if ($update || $encounter_nr) {
                        //echo formatDate2STD($geburtsdatum,$date_format);
                        $itemno = $itemname;
                        $_POST['modify_id'] = $encoder;
                        if ($dbtype == 'mysql' || $dbtype == 'mysqli') {
                            $_POST['history'] = "CONCAT(history,\"\n Update: " . date('Y-m-d H:i:s') . " = $encoder\")";
                        } else {
                            $_POST['history'] = "(history || '\n Update: " . date('Y-m-d H:i:s') . " = $encoder')";
                        }
                        if (isset($_POST['encounter_nr']))
                            unset($_POST['encounter_nr']);
                        if (isset($_POST['pid']))
                            unset($_POST['pid']);

                        $encounter_obj->setDataArray($_POST);

                        if ($encounter_obj->updateEncounterFromInternalArray($encounter_nr)) {
                            /* Save the service classes */
                            if (!$GLOBAL_CONFIG['patient_service_care_hide']) {
                                $encounter_obj->updateCareServiceClass($care_class);
                            }
                            if (!$GLOBAL_CONFIG['patient_service_room_hide']) {
                                $encounter_obj->updateRoomServiceClass($room_class, $encounter_nr);
                            }
                            if (!$GLOBAL_CONFIG['patient_service_att_dr_hide']) {
                                $encounter_obj->updateAttDrServiceClass($att_dr_class, $encounter_nr);
                            }

                            $type_r = "R";
                            $type_c = "C";

//                        if ($registration_fee != '')
//                            $bill_obj->disable_reg($encounter_nr, $type_r, $registration_fee);

//                            if ($consultation_fee != '')
//                                $bill_obj->disable_reg($encounter_nr, $type_c, $consultation_fee);

                            $IS_PATIENT_INSURED = $insurance_obj->is_patient_insured($pid);
                            $insurance_ID = $insurance_obj->Get_insuranceID_from_pid($pid);

                            if ($IS_PATIENT_INSURED) {
                                $sql4 = "update care_encounter set insurance_firm_id='$insurance_ID' where encounter_nr='$encounter_nr'";
                                //echo $sql4;
                                $db->Execute($sql4);
                            }
//
//                        if ($registration_fee != '')
//                            $bill_obj->new_reg($encounter_nr, $registration_fee, $user);
////                      $bill_obj->updateRegBill($encounter_nr);
////                      $bill_obj->updateCashErp($db,$encounter_nr);
                        if ($consultation_fee != ''){
                            $bill_obj->new_reg($encounter_nr, $consultation_fee, $user,$financial_class);
                        }

//                            if ($consultation_fee == 'CON02') {
//                                $bill_obj->createLabTest($encounter_nr, $encoder);
//                            }

                            $new_bill_number = $bill_obj->checkBillEncounter($encounter_nr);
                            $sql2 = "Update care_encounter set bill_number='$new_bill_number' where encounter_nr='$encounter_nr'";
                            //echo $sql2;
                            $db->Execute($sql2);

                            $bill_obj->updateRegBill($db, $encounter_nr, $pid);
//                            if ($bill_obj->checkKits($consultation_fee)) {
//                                $bill_obj->billItemKits($consultation_fee, $pid, $encounter_nr, 2);
//                            }
                        //$bill_obj->updateCashErp($db, $pid);
                            //$bill_obj->updateDebtorsTrans($pid,"2",$encounter_nr);
                            header("location: aufnahme_daten_zeigen.php" . URL_REDIRECT_APPEND . "&encounter_nr=$encounter_nr&origin=admit&target=entry&newdata=$newdata");
                            exit;
                        }
                    } else {

                        $newdata = 1;
                        /* Determine the format of the encounter number */
                        if ($GLOBAL_CONFIG['encounter_nr_fullyear_prepend'])
                            $ref_nr = (int)date('Y') . $GLOBAL_CONFIG['encounter_nr_init'];
                        else
                            $ref_nr = $GLOBAL_CONFIG['encounter_nr_init'];
                        //echo $ref_nr;
                        switch ($_POST['encounter_class_nr']) {
                            case '1':
                                $_POST['encounter_nr'] = $encounter_obj->getNewEncounterNr($ref_nr + $GLOBAL_CONFIG['patient_inpatient_nr_adder'], 1);
                                break;
                            case '2':
                                $_POST['encounter_nr'] = $encounter_obj->getNewEncounterNr($ref_nr + $GLOBAL_CONFIG['patient_outpatient_nr_adder'], 2);
                        }

                        $_POST['encounter_date'] = date('Y-m-d');
                        $_POST['modify_id'] = $encoder;
                        $_POST['encounter_time'] = date('H:i:s');
                        $_POST['create_id'] = $encoder;
                        $_POST['create_time'] = date('YmdHis');
                        $_POST['history'] = 'Create: ' . date('Y-m-d H:i:s') . ' = ' . $encoder;
                        if (isset($_POST['encounter_nr']))
                            unset($_POST['encounter_nr']);
//                    print_r($_POST);


//                    


                        $encounter_obj->setDataArray($_POST);

                        if ($encounter_obj->insertDataFromInternalArray()) {
                            /* Get last insert id */
                            if ($dbtype == 'mysql' || $dbtype == 'mysqli') {
//                            $encounter_nr = $db->Insert_ID();
                                $encounter_nr = $encounter_obj->getCurrentEncounterNr($pid);
                            } else {
                                $encounter_nr = $encounter_obj->postgre_Insert_ID($dbtable, 'encounter_nr', $db->Insert_ID());
                            } // end of if($dbtype=='mysql')

                            $encounter_obj->assignInDept($encounter_nr, $current_dept_nr, $current_dept_nr, date('Y-m-d'), date('H:i:s'));

                            $new_bill_number = $bill_obj->checkBillEncounter($encounter_nr);
                            $sql2 = "Update care_encounter set bill_number='$new_bill_number' where encounter_nr='$encounter_nr'";
                            //echo $sql2;
                            $db->Execute($sql2);

                            $statusType="Registration";
                            $status="Patatient admitted in Outpatient in dept $current_dept_nr ";
                            $statusDesc="Patatient admitted in Outpatient dept $current_dept_nr ";
                            $bill_obj->updatePatientStatus($encounter_nr,$encounter_nr,$statusType,$status,$statusDesc,$currUser);

                            $IS_PATIENT_INSURED = $insurance_obj->is_patient_insured($pid);
                            $insurance_ID = $insurance_obj->Get_insuranceID_from_pid($pid);
                            if ($IS_PATIENT_INSURED) {
                                $sql4 = "update care_encounter set insurance_firm_id='$insurance_ID',conStatus='billed' where encounter_nr='$encounter_nr'";
                                //echo $sql4;
                                $db->Execute($sql4);
                            }


//                        if ($is_transmit_to_weberp_enable == 1) {
////                                    $weberp_obj = new weberp($webERPServerURL,$weberpuser,$weberppassword,$weberpDebugLevel);
////                                    $weberp_obj->make_patient_workorder_in_webERP($encounter_nr)
////                                    ;
//                            $bill_obj->updateRegBill($db, $encounter_nr);
//                            $bill_obj->updateCashErp($db, $encounter_nr);
                           // $bill_obj->updateCashErp($db,$encounter_nr);
//                        }


                            # If appointment number available, mark appointment as "done"
                            if (isset($appt_nr) && $appt_nr)
                                $encounter_obj->markAppointmentDone($appt_nr, $_POST['encounter_class_nr'], $encounter_nr);

                            //echo $encounter_obj->getLastQuery();
                            // Add to table presciptions the registration fee's comming from admission seciton...
//                        if ($registration_fee != '')
//                            $bill_obj->new_reg($encounter_nr, $registration_fee, $user);


                            if (isset($transFromOutp)) {
                                global $pn;
                                $enc_new = $_POST['encounter_nr'];
//                            $encounter_obj->sql_ = "UPDATE care_encounter SET encounter_nr_prev='$pn' where encounter_nr='$enc_new'";
                                $encounter_obj->sql_old = "UPDATE care_encounter SET is_discharged='1', discharge_date='$date', discharge_time='$time' where encounter_nr='$pn'";
                                //echo $encounter_obj->sql_;
                                if ($db->Execute($db->Execute($encounter_obj->sql_old))) {
                                    $bill_obj->transferBillToIP($pn, $encounter_nr, $pid);

                                    if ($encounter_classes == 1) {
                                        $bill_obj->billAdmissionFee($pid, $encounter_nr);
                                    }
                                } else {
                                    //echo 'problem occured at aufnahme_start.php: '.$encounter_obj->sql_.' and/or '.$encounter_obj->sql_old;
                                }
                            }

                            if ($consultation_fee != '') {
                                $bill_obj->new_reg($encounter_nr, $consultation_fee, $user, $financial_class);
                            }

                            if($financial_class<>1){
                                $sql="UPDATE care_encounter set ConStatus='billed' WHERE encounter_nr=$encounter_nr";
                                echo $sql;
                                $db->Execute($sql);
                            }

//                            if ($consultation_fee == 'CON02') {
//                                $bill_obj->createLabTest($encounter_nr, $encoder);
//
//                            }

                            $bill_obj->updateRegBill($db, $encounter_nr, $pid);
//g7a69i
//                            if ($bill_obj->checkKits($consultation_fee)) {
//                                $bill_obj->billItemKits($consultation_fee, $pid, $encounter_nr, 2);
//                            }
                           // $bill_obj->getCashDeposit($db, $pid, $encounter_nr);

                            //if ($is_transmit_to_weberp_enable == 1) {
//                                    $weberp_obj = new weberp($webERPServerURL,$weberpuser,$weberppassword,$weberpDebugLevel);
//                                    $weberp_obj->make_patient_workorder_in_webERP($encounter_nr)
//                                    ;


                           // }


                            $sql = "select newAdmNo from care_ke_invoice";
                            $result = $db->Execute($sql);
                            $row = $result->FetchRow();
//                    echo $sql;
                            $new_nr = intval($row[0] + 1);

                            if ($newAdm_No == $row[0]) {
                                $sql2 = "update care_ke_invoice set newAdmNo='$new_nr'";
                                $result2 = $db->Execute($sql2);
//                        echo $sql2;
                            }


                            // Show the admission data:
                            header("location: aufnahme_daten_zeigen.php" . URL_REDIRECT_APPEND . "&encounter_nr=$encounter_nr&origin=admit&target=entry&newdata=$newdata");
                            exit;

                        } else {
                            echo $LDDbNoSave . '<p>'; //.$encounter_obj->getLastQuery();
                        }
                        //  }


                    }// end of if(update) else()
                } // end of if($error)
            } // end of if($mode)

    } elseif ($encounter_nr != '') {
        /* Load encounter data */
        $encounter_obj->loadEncounterData();
        if ($encounter_obj->is_loaded) {
            $zeile = &$encounter_obj->encounter;
            //load data
            extract($zeile);

            // Get insurance firm name
            $insurance_firm_name = $pinsure_obj->getFirmName($insurance_firm_id);

            /* GEt the patient's services classes */

            if (!empty($GLOBAL_CONFIG['patient_financial_class_single_result']))
                $encounter_obj->setSingleResult(true);

            if (!$GLOBAL_CONFIG['patient_service_care_hide']) {
                if ($buff = &$encounter_obj->CareServiceClass()) {
                    while ($care_class = $buff->FetchRow()) {
                        extract($care_class);
                    }
                    reset($care_class);
                }
            }
            if (!$GLOBAL_CONFIG['patient_service_room_hide']) {
                if ($buff = &$encounter_obj->RoomServiceClass()) {
                    while ($room_class = $buff->FetchRow()) {
                        extract($room_class);
                    }
                    reset($room_class);
                }
            }
            if (!$GLOBAL_CONFIG['patient_service_att_dr_hide']) {
                if ($buff = &$encounter_obj->AttDrServiceClass()) {
                    while ($att_dr_class = $buff->FetchRow()) {
                        extract($att_dr_class);
                    }
                    reset($att_dr_class);
                }
            }
        }
    }

    if (!$encounter_nr || $encounter_class_nr == 1) {
        # Load all  wards info
        $ward_obj = new Ward;
        $items = 'nr,name';
        $ward_info = $ward_obj->getAllWardsItemsObject($items);
    }
    if (!$encounter_nr || $encounter_class_nr == 2) {
        # Load all medical departments
        include_once($root_path . 'include/care_api_classes/class_department.php');
        $dept_obj = new Department;
        $all_meds = $dept_obj->getAllMedicalObject();
    }

    $person_obj->setPID($pid);
    if ($data = &$person_obj->BasicDataArray($pid)) {
        //while(list($x,$v)=each($data))	$$x=$v;
        extract($data);
    }

    # Prepare the photo filename
    include_once($root_path . 'include/inc_photo_filename_resolve.php');
    /* Get the citytown name */
    $addr_citytown_name = $person_obj->CityTownName($addr_citytown_nr);
}
# Prepare text and resolve the numbers
include_once($root_path . 'include/inc_patient_encounter_type.php');

# Prepare the title
if ($encounter_nr)
    $headframe_title = "$headframe_title $headframe_append ";

# Prepare onLoad JS code
if (!$encounter_nr && !$pid)
    $sOnLoadJs = 'onLoad="if(document.searchform.searchkey.focus) document.searchform.searchkey.focus();"';


# Start Smarty templating here
/**
 * LOAD Smarty
 */
# Note: it is advisable to load this after the inc_front_chain_lang.php so
# that the smarty script can use the user configured template theme

require_once($root_path . 'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care('common');

# Title in the toolbar
$smarty->assign('sToolbarTitle', $headframe_title);

# href for help button
$smarty->assign('pbHelp', "javascript:gethelp('','')");

$smarty->assign('breakfile', $breakfile);

# Window bar title
$smarty->assign('title', $headframe_title);

# Onload Javascript code
$smarty->assign('sOnLoadJs', $sOnLoadJs);

# href for help button
$smarty->assign('pbHelp', "javascript:gethelp('registration_overview.php','Person Registration :: Overview')");

# Hide the return button
$smarty->assign('pbBack', FALSE);


# Start collectiong extra Javascript code
ob_start();

# If  pid exists, output the form checker javascript
if (isset($pid) && $pid) {
    ?>

    <script  language="javascript">
        <!--

        function clickStopper(e)
        {
            // alert("click was blocked"); // ** you can remove this line **
            e.preventDefault(); // equivalent to 'return false'
        }

        function chkform(d){
<!--            encr="-->
    //saveAdmission


            if(d.encounter_class_nr[0] && d.encounter_class_nr[1]&&!d.encounter_class_nr[0].checked&&!d.encounter_class_nr[1].checked){
                alert("<?php echo $LDPlsSelectAdmissionType; ?>");
                return false;
            }else if(d.encounter_class_nr[0]&&d.encounter_class_nr[0].checked&&!d.current_ward_nr.value) {
                alert("<?php echo $LDPlsSelectWard; ?>");
                d.current_ward_nr.focus();
                return false;
            }else if(!d.financial_class.value && d.financial_class.value!="Select Finance Plan" ){
                    alert("<?php echo $LDPlsSelectFinanceClass; ?>");
                    return false
            }else if(!d.current_dept_nr.value){
                alert("<?php echo $LDPlsEnterClinic; ?>");
                d.current_dept_nr.focus();
                return false;
            //}else if(!d.consultation_fee.value && d.consultation_fee.value!="Select Consultation"){
            //    alert("<?php echo $LDPlsEnterConsultationFee; ?>");
            //    d.consultation_fee.focus();
             //   return false;
            }else if(d.encounter_class_nr[1]&&d.encounter_class_nr[1].checked&&!d.current_dept_nr.value){
                alert("<?php echo $LDPlsSelectDept; ?>");
                d.current_dept_nr.focus();
                return false;
            }else if(!d.encounter_class_nr[0]&&encr==1&&!d.current_ward_nr.value){
                alert("<?php echo $LDPlsSelectWard; ?>");
                d.current_ward_nr.focus();
                return false;
            }else if(!d.encounter_class_nr[1]&&encr==2&&!d.current_dept_nr.value){
                alert("<?php echo $LDPlsSelectDept; ?>");
                d.current_dept_nr.focus();
                return false;
            }else if(!d.newAdm_No.value){
                alert("<?php echo $LDPlsSelectAdm; ?>");
                d.newAdm_No.focus();
                return false;
            }else if(d.encoder.value=="") {
                alert("<?php echo $LDPlsEnterFullName; ?>");
                d.encoder.focus();
                return false;
            }else if(d.current_dept_nr.value==""){
                alert("<?php echo $LDPlsEnterClinic; ?>");
                d.current_dept_nr.focus();
                return false;
            }else{
                return true;
            }
        }

        function checkAdm(sex){
            if(sex=='m'){
                alert("<?php echo $LDPladmMale; ?>");
                d.current_ward_nr.focus();
                return false;
            }
        }

        function resolveLoc(){
            d=document.aufnahmeform;
            if(d.encounter_class_nr[0].checked==true) d.current_dept_nr.selectedIndex=0;
            else d.current_ward_nr.selectedIndex=0;
        }

    <?php require($root_path . 'include/inc_checkdate_lang.php'); ?>

    </script>
    <script language="javascript" src="<?php echo $root_path; ?>js/setdatetime.js"></script>
    <script language="javascript" src="<?php echo $root_path; ?>js/checkdate.js"></script>
    <script language="javascript" src="<?php echo $root_path; ?>js/dtpick_care2x.js"></script>

    <?php
} // End of if(isset(pid))

require('./include/js_popsearchwindow.inc.php');

$sTemp = ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript', $sTemp);

# Load tabs
$target = 'search';

$parent_admit = TRUE;

include('./gui_bridge/default/gui_tabs_patadmit.php');

# If the origin is admission link, show the search prompt
if (!isset($pid) || !$pid) {

    # Set color values for the search mask
    $searchmask_bgcolor = "#f3f3f3";
    $searchprompt = $LDEntryPrompt;
    $entry_block_bgcolor = '#fff3f3';
    $entry_body_bgcolor = '#ffffff';

    $smarty->assign('entry_border_bgcolor', '#6666ee');

    $smarty->assign('sSearchPromptImg', '<img ' . createComIcon($root_path, 'angle_down_l.gif', '0', '', TRUE) . '>');

    $smarty->assign('LDPlsSelectPatientFirst', $LDPlsSelectPatientFirst);
    $smarty->assign('sMascotImg', '<img ' . createMascot($root_path, 'mascot1_l.gif', '0', 'absmiddle') . '>');

    # Start buffering the searchmask

    ob_start();

    $search_script = 'patient_register_search.php';
    $user_origin = 'admit';
    include($root_path . 'include/inc_patient_searchmask.php');

    $sTemp = ob_get_contents();

    ob_end_clean();

    $smarty->assign('sSearchMask', $sTemp);
    $smarty->assign('sWarnIcon', '<img ' . createComIcon($root_path, 'warn.gif', '0', 'absmiddle', TRUE) . '>');
    $smarty->assign('LDRedirectToRegistry', $LDRedirectToRegistry);
} else {

    $smarty->assign('bSetAsForm', TRUE);

    if ($error) {
        $smarty->assign('error', TRUE);
        $smarty->assign('sMascotImg', '<img ' . createMascot($root_path, 'mascot1_r.gif', '0', 'bottom') . ' align="absmiddle">');

        if ($errornum > 1)
            $smarty->assign('LDError', $LDErrorS);
        else
            $smarty->assign('LDError', $LDError);
    }

    $smarty->assign('LDCaseNr', $LDCaseNr);
    if (isset($encounter_nr) && $encounter_nr)
        $smarty->assign('encounter_nr', $encounter_nr);
    else
        $smarty->assign('encounter_nr', '<font color="red">' . $LDNotYetAdmitted . '</font>');

    $smarty->assign('img_source', "<img $img_source>");

    $smarty->assign('LDNewAdmNo', $LDNewAdmNo);

//     if ($update || $encounter_nr) {
//        $sqlAdm="SELECT newAdm_no FROM care_encounter where encounter_nr= ".$encounter_nr;
//        $resultAdm=$db->Execute($sqlAdm);
//        $rowadm=$resultAdm->FetchRow();
//        $newAdm=$rowadm[0];
//     }else{
//        $sqlAdm="SELECT MAX(newAdm_no) AS currAdm FROM care_encounter";
//        $resultAdm=$db->Execute($sqlAdm);
//        $rowadm=$resultAdm->FetchRow();
//        $newAdm=intval($rowadm[0]+1);
//     }

    $sql1 = "select newAdmNo from care_ke_invoice";
    $result1 = $db->Execute($sql1);
    $row = $result1->FetchRow();
    $newAdm = $row[0];

    $smarty->assign('newAdm_No', '<input type="text" name="newAdm_No"  value="' . $newAdm . '"  size=20 maxlength=10>');



    $smarty->assign('LDAdmitDate', $LDAdmitDate);


    if (isset($encounter_nr) && $encounter_nr)
        $smarty->assign('sAdmitDate', @formatDate2Local(date('Y-m-d'), $date_format,FALSE,FALSE,'-'));

    $smarty->assign('LDAdmitTime', $LDAdmitTime);

    if (isset($encounter_nr) && $encounter_nr)
        $smarty->assign('sAdmitTime', @convertTimeToLocal(date('H:i:s')));

    $smarty->assign('LDTitle', $LDTitle);
    $smarty->assign('title', $title);
    $smarty->assign('LDLastName', $LDLastName);
    $smarty->assign('name_last', $name_last);
    $smarty->assign('LDFirstName', $LDFirstName);
    $smarty->assign('name_first', $name_first);

    # Set a row span counter, initialize with 6
    $iRowSpan = 6;

    if ($GLOBAL_CONFIG['patient_name_2_show'] && $name_2) {
        $smarty->assign('LDName2', $LDName2);
        $smarty->assign('name_2', $name_2);
        $iRowSpan++;
    }

    if ($GLOBAL_CONFIG['patient_name_3_show'] && $name_3) {
        $smarty->assign('LDName3', $LDName3);
        $smarty->assign('name_3', $name_3);
        $iRowSpan++;
    }

    if ($GLOBAL_CONFIG['patient_name_middle_show'] && $name_middle) {
        $smarty->assign('LDNameMid', $LDNameMid);
        $smarty->assign('name_middle', $name_middle);
        $iRowSpan++;
    }
    $smarty->assign('sRowSpan', "rowspan=\"$iRowSpan\"");

    $smarty->assign('LDBday', $LDBday);
    $smarty->assign('sBdayDate', @formatDate2Local($date_birth, $date_format,'/',FALSE,FALSE,'-'));

    //TODO: Disable admitting of males to female and Maternity ward and females to Male ward

    $smarty->assign('LDSex', $LDSex);
    if ($sex == 'm')
        $smarty->assign('sSexType', $LDMale);
    elseif ($sex == 'f')
        $smarty->assign('sSexType', $LDFemale);

    $smarty->assign('LDBloodGroup', $LDBloodGroup);
    if ($blood_group) {
        $buf = 'LD' . $blood_group;
        $smarty->assign('blood_group', $$buf);
    }

    $smarty->assign('LDAddress', $LDAddress);

    $smarty->assign('addr_str', $addr_str);
    $smarty->assign('addr_str_nr', $addr_str_nr);
    $smarty->assign('addr_zip', $addr_zip);
    $smarty->assign('addr_citytown', $addr_citytown_name);

    $smarty->assign('LDAdmitClass', $LDAdmitClass);


    if (is_object($encounter_classes)) {
        $sTemp = '';
        while ($result = $encounter_classes->FetchRow()) {
            $LD = $result['LD_var'];
            //if($in_ward && ($encounter_class_nr==$result['class_nr'])){ # If in ward, freeze encounter class
            if ($encounter_nr) { # If admitted, freeze encounter class
                if ($encounter_class_nr == $result['class_nr']) {
                    if (isset($$LD) && !empty($$LD))
                        $sTemp = $sTemp . $$LD;
                    else
                        $sTemp = $sTemp . $result['name'];
                    $sTemp = $sTemp . '<input name="encounter_class_nr" type="hidden"  value="' . $encounter_class_nr . '">';
                    break;
                }
            }else {
                //old code:
//						$sTemp = $sTemp.'<input name="encounter_class_nr" onClick="resolveLoc()" type="radio"  value="'.$result['class_nr'].'" ';
//						if($encounter_class_nr==$result['class_nr']) $sTemp = $sTemp.'checked';
//						$sTemp = $sTemp.'>';
//
//						if(isset($$LD)&&!empty($$LD)) $sTemp = $sTemp.$$LD;
//							else $sTemp = $sTemp.$result['name'];
                //new code:

                if ($encounter_class_nr == $result['class_nr']) {
                    $sTemp = $sTemp . '<input name="encounter_class_nr"  type="hidden"  value="' . $result['class_nr'] . '" ';
                    $sTemp = $sTemp . '>';
                }
            }
        }

        if ($encounter_class_nr == 1)
            $sTemp .= $LDStationary;
        else if ($encounter_class_nr == 2)
            $sTemp .= $LDAmbulant;

        $smarty->assign('sAdmitClassInput', $sTemp);
    }



    # If no encounter nr or inpatient, show ward/station info, 1 = inpatient
    if (!$encounter_nr || $encounter_class_nr == 1) {
        if ($errorward || $encounter_class_nr == 1) {
            $smarty->assign('LDWard', "<font color=red>$LDWard</font>");
        }
        $smarty->assign('LDWard', $LDWard);
        $sTemp = '';
        if ($in_ward) {

            while ($station = $ward_info->FetchRow()) {
                if (isset($current_ward_nr) && ($current_ward_nr == $station['nr'])) {
                    $sTemp = $sTemp . $station['name'];
                    $sTemp = $sTemp . '<input name="current_ward_nr" type="hidden"  value="' . $current_ward_nr . '">';
                    break;
                }
            }
        } else {
            $sTemp = $sTemp . '<select name="current_ward_nr" id="current_ward_nr">
								<option value=""></option>';
            if (!empty($ward_info) && $ward_info->RecordCount()) {
                while ($station = $ward_info->FetchRow()) {
                    $sTemp = $sTemp . '
								<option value="' . $station['nr'] . '" ';
                    if (isset($current_ward_nr) && ($current_ward_nr == $station['nr']))
                        $sTemp = $sTemp . 'selected';
                    $sTemp = $sTemp . '>' . $station['name'] . '</option>';
                }
            }
            $sTemp = $sTemp . '</select>
							<font size=1><img ' . createComIcon($root_path, 'redpfeil_l.gif', '0', '', TRUE) . '> ' . $LDForInpatient . '</font>';
        }
        //old code:
        //$smarty->assign('sWardInput',$sTemp);
        //new code:
        if ($encounter_class_nr == 1
        )
            $smarty->assign('sWardInput', $sTemp);
    } //  End of if no encounter nr
    # If no encounter nr or outpatient, show clinic/department info, 2 = outpatient
    $sTemp = $sTemp . '<input name="current_dept_nr" type="hidden"  value="' . $current_dept_nr . '">';
    if (!$encounter_nr || $encounter_class_nr == 2) {

        if ($errorward || $encounter_class_nr == 2)
            $smarty->assign('LDDepartment', "<font color=red>$LDClinic/$LDDepartment</font>");
        else
            $smarty->assign('LDDepartment', "$LDClinic/$LDDepartment");
        $sTemp = '';
        if ($in_dept) {
            while ($deptrow = $all_meds->FetchRow()) {
                if (isset($current_dept_nr) && ($current_dept_nr == $deptrow['nr'])) {
                    $sTemp = $sTemp . $deptrow['name_formal'];
                    $sTemp = $sTemp . '<input name="current_dept_nr" type="hidden"  value="' . $current_dept_nr . '">';
                    break;
                }
            }
        } else {
            $sTemp = $sTemp . '<span style="color: red">*</span><select name="current_dept_nr">
							<option value=""></option>';

            if (is_object($all_meds)) {
                while ($deptrow = $all_meds->FetchRow()) {
                    $sTemp = $sTemp . '
								<option value="' . $deptrow['nr'] . '" ';
                    if (isset($current_dept_nr) && ($current_dept_nr == $deptrow['nr']))
                        $sTemp = $sTemp . 'selected';
                    $sTemp = $sTemp . '>';
                    if ($$deptrow['LD_var'] != '')
                        $sTemp = $sTemp . $$deptrow['LD_var'];
                    else
                        $sTemp = $sTemp . $deptrow['name_formal'];
                    $sTemp = $sTemp . '</option>';
                }
            }
            $sTemp = $sTemp . '</select><font size=1><img ' . createComIcon($root_path, 'redpfeil_l.gif', '0', '', TRUE) . '> ' . $LDForOutpatient . '</font>';
        }
        //old code:
        //$smarty->assign('sDeptInput',$sTemp);
        //new code:
        if ($encounter_class_nr == 2
        )
            $smarty->assign('sDeptInput', $sTemp);
    } // End of if no encounter nr

    $smarty->assign('LDDiagnosis', $LDDiagnosis);
    $smarty->assign('referrer_diagnosis', '<input name="referrer_diagnosis" type="text" size="60" value="' . $referrer_diagnosis . '">');

    $smarty->assign('LDTherapy', $LDCon);


    // CONSULTATION FEE
    $cTemp = $cTemp . '<span style="color: red">*</span><select name="consultation_fee">
			<option value="">Select Consultation</option>';


    $sql_con = "SELECT  partcode,item_description FROM care_tz_drugsandservices WHERE
isConsultation='Yes' order by item_description asc";
    $db_con = $db->Execute($sql_con);
    while ($conrow = $db_con->FetchRow()) {
        $cTemp = $cTemp . '<option value="' . $conrow['partcode'] . '" ';
        $cTemp = $cTemp . '>';
        $cTemp = $cTemp . $conrow['item_description'];
        $cTemp = $cTemp . '</option>';
    }

    $cTemp = $cTemp . '</select>';

    $smarty->assign('consultation_fee', $cTemp);

    $smarty->assign('LDRecBy', $LDReg);


    // REGISTRATION FEE
    //$rTemp = $rTemp.'<select name="registration_fee">
    //			<option value=""></option>';
//    $sql_reg = "SELECT  item_description FROM care_tz_drugsandservices WHERE  partcode LIKE 'Reg%' and category='2'";
//    $db_reg = $db->Execute($sql_reg);
//    while ($regrow = $db_reg->FetchRow()) {
//        $rTemp = $rTemp . '<input type="radio" name="registration_fee" value="' . $regrow['item_description'] . '" ';
//        $rTemp = $rTemp . '>';
//        $rTemp = $rTemp . $regrow['item_description'];
//    }
    //	while($regrow=$db_reg->FetchRow()){
    //	$rTemp = $rTemp.'<option value="'.$regrow['item_description'].'" ';
    //$rTemp = $rTemp.'>';
    //$rTemp = $rTemp.$regrow['item_description'];
    //$rTemp = $rTemp.'</option>';
    //}
    //$rTemp = $rTemp.'</select>';

    $smarty->assign('registration_fee', $rTemp);
    
    
    $fTemp = $fTemp . '<span style="color: red" >*</span><select name="financial_class">
			<option value="">Select Finance Plan</option>';
    $sql_price = "SELECT  ID,PriceType FROM care_ke_pricetypes";
    $requestf= $db->Execute($sql_price);
    while ($pricerow = $requestf->FetchRow()) {
        $fTemp = $fTemp . '<option value="' . $pricerow['ID'] . '" ';
        $fTemp = $fTemp . '>';
        $fTemp = $fTemp . $pricerow['PriceType'];
        $fTemp = $fTemp . '</option>';
    }

    $fTemp = $fTemp . '</select>';

    $smarty->assign('LDFinanceClass', $LDFinanceClass);
    $smarty->assign('finance_class', $fTemp);




    $rTemp = $rTemp . '<select name="referals">
			<option value=""></option>';
        $rTemp = $rTemp . '<option value="Referal from Other Health Facility">Referal from Other Health Facility</option>';
        $rTemp = $rTemp . '<option value="Referal from Community Unit">Referal from Community Unit</option>';

    $rTemp = $rTemp . '</select>';

    $smarty->assign('LDReferals', $LDReferals);
    $smarty->assign('referals', $rTemp);

    $smarty->assign('LDSpecials', $LDSpecials);
    $smarty->assign('referrer_notes', '<input name="referrer_notes" type="text" size="60" value="' . $referrer_notes . '">');


//
//
//    if ($errorinsclass)
//        $smarty->assign('LDBillType', "<font color=red>$LDBillType</font>");
//    else
//        $smarty->assign('LDBillType', $LDBillType);
//
//    $sTemp = '';
//    if (is_object($insurance_classes)) {
//        while ($result = $insurance_classes->FetchRow()) {
//
//            $sTemp = $sTemp . '<input name="insurance_class_nr" type="radio"  value="' . $result['class_nr'] . '" ';
//            if ($insurance_class_nr == $result['class_nr'])
//                $sTemp = $sTemp . 'checked';
//            $sTemp = $sTemp . '>';
//
//            $LD = $result['LD_var'];
//            if (isset($$LD) && !empty($$LD))
//                $sTemp = $sTemp . $$LD;
//            else
//                $sTemp = $sTemp . $result['name'];
//        }
//    }
//    $smarty->assign('sBillTypeInput', $sTemp);
//    $sTemp = '';
//    if ($error_ins_nr)
//        $smarty->assign('LDInsuranceNr', "<font color=red>$LDInsuranceNr</font>");
//    else
//        $smarty->assign('LDInsuranceNr', $LDInsuranceNr);
//    if (isset($insurance_nr) && $insurance_nr)
//        $sTemp = $insurance_nr;
//    $smarty->assign('insurance_nr', '<input name="insurance_nr" type="text" size="60" value="' . $sTemp . '">');
//
//    $sTemp = '';
//    if (isset($insurance_firm_name))
//        $sTemp = $insurance_firm_name;
//    if ($error_ins_co)
//        $smarty->assign('LDInsuranceCo', "<font color=red>$LDInsuranceCo</font>");
//    else
//        $smarty->assign('LDInsuranceCo', $LDInsuranceCo);
//
//    $sBuffer = "<a href=\"javascript:popSearchWin('insurance','aufnahmeform.insurance_firm_id','aufnahmeform.insurance_firm_name')\"><img " . createComIcon($root_path, 'l-arrowgrnlrg.gif', '0', '', TRUE) . "></a>";
//    $smarty->assign('insurance_firm_name', '<input name="insurance_firm_name" type="text" size="60" value="' . $sTemp . '">' . $sBuffer);
//
//    if (!$GLOBAL_CONFIG['patient_service_care_hide'] && is_object($care_service)) {
//        $smarty->assign('LDCareServiceClass', $LDCareServiceClass);
//        $sTemp = '';
//
//        $sTemp = $sTemp . '<select name="sc_care_class_nr" >';
//
//        while ($buffer = $care_service->FetchRow()) {
//            $sTemp = $sTemp . '
//						<option value="' . $buffer['class_nr'] . '" ';
//            if ($sc_care_class_nr == $buffer['class_nr'])
//                $sTemp = $sTemp . 'selected';
//            $sTemp = $sTemp . '>';
//            if (empty($$buffer['LD_var']))
//                $sTemp = $sTemp . $buffer['name'];
//            else
//                $sTemp = $sTemp . $$buffer['LD_var'];
//            $sTemp = $sTemp . '</option>';
//        }
//        $sTemp = $sTemp . '</select>';
//
//        $smarty->assign('sCareServiceInput', $sTemp);
//
//        $smarty->assign('LDFrom', $LDFrom);
//        $sTemp = '';
//        if (!empty($sc_care_start))
//            $sTemp = @formatDate2Local($sc_care_start, $date_format,'/');
//
//        $smarty->assign('sCSFromInput', '<input type="text" name="sc_care_start"  value="' . $sTemp . '" size=9 maxlength=10   onBlur="IsValidDate(this,\'' . $date_format . '\')" onKeyUp="setDate(this,\'' . $date_format . '\',\'' . $lang . '\')">');
//        $smarty->assign('LDTo', $LDTo);
//        $sTemp = '';
//        if (!empty($sc_care_end))
//            $sTemp = @formatDate2Local($sc_care_end, $date_format,'/');
//        $smarty->assign('sCSToInput', '<input type="text" name="sc_care_end"  value="' . $sTemp . '"  size=9 maxlength=10   onBlur="IsValidDate(this,\'' . $date_format . '\')" onKeyUp="setDate(this,\'' . $date_format . '\',\'' . $lang . '\')">');
//        $smarty->assign('sCSHidden', '<input type="hidden" name="sc_care_nr" value="' . $sc_care_nr . '">');
//    }
//
//    if (!$GLOBAL_CONFIG['patient_service_room_hide'] && is_object($room_service)) {
//        $smarty->assign('LDRoomServiceClass', $LDRoomServiceClass);
//        $sTemp = '';
//
//        $sTemp = $sTemp . '<select name="sc_room_class_nr" >';
//
//        while ($buffer = $room_service->FetchRow()) {
//            $sTemp = $sTemp . '
//						<option value="' . $buffer['class_nr'] . '" ';
//            if ($sc_room_class_nr == $buffer['class_nr'])
//                $sTemp = $sTemp . 'selected';
//            $sTemp = $sTemp . '>';
//            if (empty($$buffer['LD_var']))
//                $sTemp = $sTemp . $buffer['name'];
//            else
//                $sTemp = $sTemp . $$buffer['LD_var'];
//            $sTemp = $sTemp . '</option>';
//        }
//        $sTemp = $sTemp . '</select>';
//
//        $smarty->assign('sCareRoomInput', $sTemp);
//
//        //$smarty->assign('LDFrom',$LDFrom);
//        $sTemp = '';
//        if (!empty($sc_room_start))
//            $sTemp = @formatDate2Local($sc_room_start, $date_format,'/');
//
//        $smarty->assign('sRSFromInput', '<input type="text" name="sc_room_start"  value="' . $sTemp . '" size=9 maxlength=10   onBlur="IsValidDate(this,\'' . $date_format . '\')" onKeyUp="setDate(this,\'' . $date_format . '\',\'' . $lang . '\')">');
//        //$smarty->assign('LDTo',$LDTo);
//        $sTemp = '';
//        if (!empty($sc_room_end))
//            $sTemp = @formatDate2Local($sc_room_end, $date_format,'/');
//        $smarty->assign('sRSToInput', '<input type="text" name="sc_room_end"  value="' . $sTemp . '"  size=9 maxlength=10   onBlur="IsValidDate(this,\'' . $date_format . '\')" onKeyUp="setDate(this,\'' . $date_format . '\',\'' . $lang . '\')">');
//        $smarty->assign('sRSHidden', '<input type="hidden" name="sc_room_nr" value="' . $sc_room_nr . '">');
//    }
//
//    if (!$GLOBAL_CONFIG['patient_service_att_dr_hide'] && is_object($att_dr_service)) {
//        $smarty->assign('LDAttDrServiceClass', $LDAttDrServiceClass);
//        $sTemp = '';
//
//        $sTemp = $sTemp . '<select name="sc_att_dr_class_nr" >';
//
//        while ($buffer = $att_dr_service->FetchRow()) {
//            $sTemp = $sTemp . '
//						<option value="' . $buffer['class_nr'] . '" ';
//            if ($sc_att_dr_class_nr == $buffer['class_nr'])
//                $sTemp = $sTemp . 'selected';
//            $sTemp = $sTemp . '>';
//            if (empty($$buffer['LD_var']))
//                $sTemp = $sTemp . $buffer['name'];
//            else
//                $sTemp = $sTemp . $$buffer['LD_var'];
//            $sTemp = $sTemp . '</option>';
//        }
//        $sTemp = $sTemp . '</select>';
//
//        $smarty->assign('sCareDrInput', $sTemp);
//
//        //$smarty->assign('LDFrom',$LDFrom);
//        $sTemp = '';
//        if (!empty($sc_att_dr_start))
//            $sTemp = @formatDate2Local($sc_att_dr_start, $date_format,'/');
//
//        $smarty->assign('sDSFromInput', '<input type="text" name="sc_att_dr_start"  value="' . $sTemp . '" size=9 maxlength=10   onBlur="IsValidDate(this,\'' . $date_format . '\')" onKeyUp="setDate(this,\'' . $date_format . '\',\'' . $lang . '\')">');
//        //$smarty->assign('LDTo',$LDTo);
//        $sTemp = '';
//        if (!empty($sc_att_dr_end))
//            $sTemp = @formatDate2Local($sc_att_dr_end, $date_format,'/');
//        $smarty->assign('sDSToInput', '<input type="text" name="sc_att_dr_end"  value="' . $sTemp . '"  size=9 maxlength=10   onBlur="IsValidDate(this,\'' . $date_format . '\')" onKeyUp="setDate(this,\'' . $date_format . '\',\'' . $lang . '\')">');
//        $smarty->assign('sDSHidden', '<input type="hidden" name="sc_att_dr_nr" value="' . $sc_att_dr_nr . '">');
//    }

    $smarty->assign('LDAdmitBy', $LDAdmitBy);
    if (empty($encoder))
        $encoder = $_COOKIE[$local_user . $sid];
    $smarty->assign('encoder', '<input  name="encoder" type="text" value="' . $encoder . '" size="28">');


    $sTemp = '<input type="hidden" name="pid" value="' . $pid . '">
				<input type="hidden" name="encounter_nr" value="' . $encounter_nr . '">
				<input type="hidden" name="appt_nr" value="' . $appt_nr . '">
				<input type="hidden" name="sid" value="' . $sid . '">
				<input type="hidden" name="lang" value="' . $lang . '">
				<input type="hidden" name="mode" value="save">
				<input type="hidden" name="insurance_firm_id" value="' . $insurance_firm_id . '">
				<input type="hidden" name="insurance_show" value="' . $insurance_show . '">';

    if ($update)
        $sTemp = $sTemp . '<input type="hidden" name=update value=1>';

    $smarty->assign('sHiddenInputs', $sTemp);

    $smarty->assign('pbSave', '<input  type="image" ' . createLDImgSrc($root_path, 'savedisc.gif', '0') . ' title="' . $LDSaveData . '" onclick="this.addEventListener(\'click\', clickStopper, false);" align="absmiddle">');

    $smarty->assign('pbRegData', '<a href="patient_register_show.php' . URL_APPEND . '&pid=' . $pid . '"><img ' . createLDImgSrc($root_path, 'reg_data.gif', '0') . '  title="' . $LDRegistration . '"  align="absmiddle"></a>');
    $smarty->assign('pbCancel', '<a href="' . $breakfile . '"><img ' . createLDImgSrc($root_path, 'cancel.gif', '0') . '  title="' . $LDCancel . '"  align="absmiddle"></a>');
    //<!-- Note: uncomment the ff: line if you want to have a reset button  -->
    /* <!--
      $smarty->assign('pbRefresh','<a href="javascript:document.aufnahmeform.reset()"><img '.createLDImgSrc($root_path,'reset.gif','0').' alt="'.$LDResetData.'"  align="absmiddle"></a>');
      -->
     */

    $sql = 'select a.pid,b.encounter_nr,a.insurance_ID from care_person a 
    inner join care_encounter b  on b.pid=a.pid where insurance_ID<>-1 and b.encounter_nr="' . $encounter_nr . '"';
    $result = $db->Execute($sql);
//    echo $sql;

    if ($row = $result->FetchRow()) {

        $smarty->assign('pbSlip', '<input type="image"' . createLDImgSrc($root_path, 'print_slip.gif', '0', 'absmiddle') . ' onclick="printSlip(' . $pid . ')">');
    }
    $smarty->assign('pbLabel', '<input type="image"' . createLDImgSrc($root_path, 'btn_aqua_label.gif', '0', 'absmiddle') . 'onclick="displayLabel(' . $pid . ')">');
    if ($error == 1)
        $smarty->assign('sErrorHidInputs', '<input type="hidden" name="forcesave" value="1">
				<input  type="submit" value="' . $LDForceSave . '">');

    if (!($newdata)) {

        $sTemp = '
		<form action=' . $thisfile . ' method=post>
		<input type="hidden" name=sid value=' . $sid . '>
		<input type="hidden" name=patnum value="">
		<input type="hidden" name="lang" value="' . $lang . '">
		<input type=submit value="' . $LDNewForm . '">
		</form>';

        $smarty->assign('sNewDataForm', $sTemp);
    }
}  // end of if !isset($pid...
# Prepare shortcut links to other functions

$smarty->assign('sSearchLink', '<img ' . createComIcon($root_path, 'varrow.gif', '0') . '> <a href="aufnahme_daten_such.php' . URL_APPEND . '">' . $LDPatientSearch . '</a>');
$smarty->assign('sArchiveLink', '<img ' . createComIcon($root_path, 'varrow.gif', '0') . '> <a href="aufnahme_list.php' . URL_APPEND . '&newdata=1&from=entry">' . $LDArchive . '</a>');

$smarty->assign('sMainBlockIncludeFile', 'registration_admission/admit_input.tpl');

$smarty->display('common/mainframe.tpl');
?>
