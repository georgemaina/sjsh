<?php
//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
error_reporting(E_ALL);
require('./roots.php');
require($root_path . 'include/inc_environment_global.php');

/*
  CARE2X Integrated Information System beta 2.0.1 - 2004-07-04 for Hospitals and Health Care Organizations and Services
  Copyright (C) 2002,2003,2004,2005  Elpidio Latorilla & Intellin.org
  GNU GPL.
  For details read file "copy_notice.txt".
 */
$lang_tables[] = 'prompt.php';
$lang_tables[] = 'person.php';
$lang_tables[] = 'departments.php';
define('LANG_FILE', 'aufnahme.php');
$local_user = 'aufnahme_user';
require_once($root_path . 'include/inc_front_chain_lang.php');
require_once($root_path . 'include/care_api_classes/class_encounter.php');
require_once($root_path . 'include/care_api_classes/class_person.php');
require_once($root_path . 'include/care_api_classes/class_insurance.php');
require_once($root_path . 'include/care_api_classes/class_ward.php');
require_once($root_path . 'include/care_api_classes/class_globalconfig.php');


if (!$_SESSION['sess_parent_mod'])
    $_SESSION['sess_parent_mod'];

# Create objects
$encounter_obj = new Encounter($encounter_nr);
$person_obj = new Person();
$insurance_obj = new Insurance;

$thisfile = basename($_SERVER['PHP_SELF']);

if ($_COOKIE['ck_login_logged' . $sid])
    $breakfile = $root_path . 'main/startframe.php' . URL_APPEND;
else
    $breakfile = 'aufnahme_pass.php' . URL_APPEND . '&target=entry';

//$breakfile='aufnahme_pass.php'.URL_APPEND;

$GLOBAL_CONFIG = array();
$glob_obj = new GlobalConfig($GLOBAL_CONFIG);

/* Get the patient global configs */
$glob_obj->getConfig('patient_%');
$glob_obj->getConfig('person_foto_path');

$updatefile = 'aufnahme_start.php';

/* Default path for fotos. Make sure that this directory exists! */
$default_photo_path = $root_path . 'fotos/registration';
$photo_filename = 'nopic';

$dbtable = 'care_encounter';

$db->debug = 0;




/* 		$sql='SELECT * FROM '.$dbtable.' AS enc LEFT JOIN care_person AS reg ON reg.pid = enc.pid
  WHERE enc.encounter_nr="'.$encounter_nr.'"';

  if($ergebnis=$db->Execute($sql)) {
  if($ergebnis->RecordCount()) {
  $encounter=$ergebnis->FetchRow();
  while(list($x,$v)=each($encounter)) $$x=$v;
  }
  } */
if (!empty($GLOBAL_CONFIG['patient_financial_class_single_result']))
    $encounter_obj->setSingleResult(true);

if (!$GLOBAL_CONFIG['patient_service_care_hide']) {
    /* Get the care service classes */
    $care_service = $encounter_obj->AllCareServiceClassesObject();

    if ($buff = &$encounter_obj->CareServiceClass()) {
        $care_class = $buff->FetchRow();
        //while(list($x,$v)=each($care_class))	$$x=$v;
        extract($care_class);
        reset($care_class);
    }
}
if (!$GLOBAL_CONFIG['patient_service_room_hide']) {
    /* Get the room service classes */
    $room_service = $encounter_obj->AllRoomServiceClassesObject();

    if ($buff = &$encounter_obj->RoomServiceClass()) {
        $room_class = $buff->FetchRow();
        //while(list($x,$v)=each($room_class))	$$x=$v;
        extract($room_class);
        reset($room_class);
    }
}
if (!$GLOBAL_CONFIG['patient_service_att_dr_hide']) {
    /* Get the attending doctor service classes */
    $att_dr_service = $encounter_obj->AllAttDrServiceClassesObject();

    if ($buff = &$encounter_obj->AttDrServiceClass()) {
        $att_dr_class = $buff->FetchRow();
        //while(list($x,$v)=each($att_dr_class))	$$x=$v;
        extract($att_dr_class);
        reset($att_dr_class);
    }
}

$encounter_obj->loadEncounterData();
if ($encounter_obj->is_loaded) {
    $row = &$encounter_obj->encounter;
    //load data
    //while(list($x,$v)=each($row)) $$x=$v;
    extract($row);
    # Set edit mode
    if (!$is_discharged)
        $edit = true;
    else
        $edit = false;
    # Fetch insurance and encounter classes
    $insurance_class = &$encounter_obj->getInsuranceClassInfo($insurance_class_nr);
    $encounter_class = &$encounter_obj->getEncounterClassInfo($encounter_class_nr);

    //if($data_obj=&$person_obj->getAllInfoObject($pid))
    $list = 'title,name_first,name_last,name_2,name_3,name_middle,name_maiden,name_others,date_birth,
		         sex,addr_str,addr_str_nr,addr_zip,addr_citytown_nr,photo_filename';

    $person_obj->setPID($pid);
    if ($row = &$person_obj->getValueByList($list)) {
        //while(list($x,$v)=each($row))	$$x=$v;
        extract($row);
    }

    $addr_citytown_name = $person_obj->CityTownName($addr_citytown_nr);
    $encoder = $encounter_obj->RecordModifierID();
    # Get current encounter to check if current encounter is this encounter nr
    $current_encounter = $person_obj->CurrentEncounter($pid);

    # Get the overall status
    if ($stat = &$encounter_obj->AllStatus($encounter_nr)) {
        $enc_status = $stat->FetchRow();
    }

    # Get ward or department infos
    if ($encounter_class_nr == 1) {
        # Get ward name
        include_once($root_path . 'include/care_api_classes/class_ward.php');
        $ward_obj = new Ward;
        $current_ward_name = $ward_obj->WardName($current_ward_nr);
    } elseif ($encounter_class_nr == 2) {
        # Get ward name
        include_once($root_path . 'include/care_api_classes/class_department.php');
        $dept_obj = new Department;
        //$current_dept_name=$dept_obj->FormalName($current_dept_nr);
        $current_dept_LDvar = $dept_obj->LDvar($current_dept_nr);

        if (isset($$current_dept_LDvar) && !empty($$current_dept_LDvar))
            $current_dept_name = $$current_dept_LDvar;
        else
            $current_dept_name = $dept_obj->FormalName($current_dept_nr);
    }



    $count = 2;
    $type = "R";

    for ($i = 0; $i < $count; $i++) {

        $sql = "select * from care_encounter_prescription as a
					inner join care_tz_drugsandservices as b on a.article_item_number = b.item_id
					where a.encounter_nr=$encounter_nr AND (isnull(a.is_disabled) OR a.is_disabled='')  AND b.item_number like '$type%'
				 ";
        $result = $db->Execute($sql);

        while ($encounter = $result->FetchRow()) {
            if ($type == "R") {
                $registration_fee = $encounter['article'];
            } else if ($type == "C") {
                $consultation_fee = $encounter['article'];
            }
        }
        $type = "C";
    }
}

include_once($root_path . 'include/inc_date_format_functions.php');


/* Update History */
if (!$newdata)
    $encounter_obj->setHistorySeen($_SESSION['sess_user_name'], $encounter_nr);
/* Get insurance firm name */
$insurance_firm_name = $insurance_obj->getFirmName($insurance_firm_id);
/* Check whether config path exists, else use default path */
$photo_path = (is_dir($root_path . $GLOBAL_CONFIG['person_foto_path'])) ? $GLOBAL_CONFIG['person_foto_path'] : $default_photo_path;


/* Prepare text and resolve the numbers */
require_once($root_path . 'include/inc_patient_encounter_type.php');

/* Save encounter nrs to session */
$_SESSION['sess_pid'] = $pid;
$_SESSION['sess_en'] = $encounter_nr;
$_SESSION['sess_full_en'] = $full_en;
$_SESSION['sess_parent_mod'] = 'admission';
$_SESSION['sess_user_origin'] = 'admission';
$_SESSION['sess_file_return'] = $thisfile;

/* Prepare the photo filename */
require_once($root_path . 'include/inc_photo_filename_resolve.php');
$user = $_COOKIE[$local_user . $sid];


# Start Smarty templating here
/**
 * LOAD Smarty
 */
# Note: it is advisable to load this after the inc_front_chain_lang.php so
# that the smarty script can use the user configured template theme

require_once($root_path . 'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care('common');

# Title in the toolbar
$smarty->assign('sToolbarTitle', $LDPatientData . ' (PID: ' .$pid . ')');

# href for help button
$smarty->assign('pbHelp', "javascript:gethelp('registration_overview.php','Person Registration :: Overview')");

$smarty->assign('breakfile', $breakfile);

# Window bar title
$smarty->assign('title', $LDPatientData . ' (' . $encounter_nr . ')');

# href for help button
$smarty->assign('pbHelp', "javascript:gethelp('registration_overview.php','Person Registration :: Overview')");

# Hide the return button
$smarty->assign('pbBack', FALSE);

# Collect extra javascript

ob_start();

require($root_path . 'include/inc_js_barcode_wristband_popwin.php');
require('./include/js_poprecordhistorywindow.inc.php');

$sTemp = ob_get_contents();

ob_end_clean();

$smarty->append('JavaScript', $sTemp);

# Load tabs
$parent_admit = TRUE;
//$target='entry';
include('./gui_bridge/default/gui_tabs_patadmit.php');

if ($is_discharged) {

    $smarty->assign('is_discharged', TRUE);
    $smarty->assign('sWarnIcon', "<img " . createComIcon($root_path, 'warn.gif', '0', 'absmiddle') . ">");
    if ($current_encounter)
        $smarty->assign('sDischarged', $LDEncounterClosed);
    else
        $smarty->assign('sDischarged', $LDPatientIsDischarged);
}

$smarty->assign('LDCaseNr', $LDCaseNr);
$smarty->assign('encounter_nr', $encounter_nr);

# Create the encounter barcode image

if (file_exists($root_path . 'cache/barcodes/en_' . $encounter_nr . '.png')) {
    $smarty->assign('sEncBarcode', '<img src="' . $root_path . 'cache/barcodes/en_' . $encounter_nr . '.png" border=0 width=180 height=35>');
} else {
//    $smarty->assign('sHiddenBarcode', "<img src='" . $root_path . "classes/barcode/image.php?code=" . $encounter_nr . "&style=68&type=I25&width=180&height=50&xres=2&font=5&label=2&form_file=en' border=0 width=0 height=0>");
//    $smarty->assign('sEncBarcode', "<img src='" . $root_path . "classes/barcode/image.php?code=" . $encounter_nr . "&style=68&type=I25&width=180&height=40&xres=2&font=5' border=0>");
}
$smarty->assign('LDNewAdmNo', $LDNewAdmNo);
$smarty->assign('newAdm_No', $newAdm_No);
$smarty->assign('img_source', "<img $img_source>");

$smarty->assign('LDAdmitDate', $LDAdmitDate);
$sDate = new DateTime($encounter_date);
$encDate = $sDate->format('d-m-Y');


$smarty->assign('sAdmitDate', $encDate);

$smarty->assign('LDAdmitTime', $LDAdmitTime);

$smarty->assign('sAdmitTime', $encounter_time);

$smarty->assign('LDTitle', $LDTitle);
$smarty->assign('title', $title);
$smarty->assign('LDLastName', $LDLastName);
$smarty->assign('name_last', $name_last);
$smarty->assign('LDFirstName', $LDFirstName);
$smarty->assign('name_first', $name_first);

# If person is dead show a black cross and assign death date

if ($death_date && $death_date != DBF_NODATE) {
    $smarty->assign('sCrossImg', '<img ' . createComIcon($root_path, 'blackcross_sm.gif', '0') . '>');
    $smarty->assign('sDeathDate', @formatDate2Local($death_date, $date_format));
}

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
$smarty->assign('sBdayDate', formatDate2Local($date_birth, $date_format,TRUE,TRUE,"-"));

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


$smarty->assign('addr_str', $addr_str);
$smarty->assign('addr_str_nr', $addr_str_nr);
$smarty->assign('addr_zip', $addr_zip);
$smarty->assign('addr_zip', $addr_zip);
$smarty->assign('addr_citytown', $addr_citytown_name);

$smarty->assign('LDAdmitClass', $LDAdmitClass);

# Suggested by Dr. Sarat Nayak to emphasize the OUTPATIENT encounter type

if (isset($$encounter_class['LD_var']) && !empty($$encounter_class['LD_var'])) {
    $eclass = $$encounter_class['LD_var'];
    //$fcolor='red';
} else {
    $eclass = $encounter_class['name'];
}

if ($encounter_class_nr == 1) {
    $fcolor = 'black';
} else {
    $fcolor = 'red';
    $eclass = '<b>' . strtoupper($eclass) . '</b>';
}

$smarty->assign('sAdmitClassInput', "<font color=$fcolor>$eclass</font>");

if ($encounter_class_nr == 1) {

    $smarty->assign('LDWard', $LDWard);

    $smarty->assign('sWardInput', '<a href="' . $root_path . 'modules/nursing/' . strtr('nursing-station-pass.php' . URL_APPEND . '&rt=pflege&edit=1&station=' . $current_ward_name . '&location_id=' . $current_ward_name . '&ward_nr=' . $current_ward_nr, ' ', ' ') . '">' . $current_ward_name . '</a>');
} elseif ($encounter_class_nr == 2) {

    $smarty->assign('LDWard', "$LDClinic/$LDDepartment");

    $smarty->assign('sWardInput', '<a href="' . $root_path . 'modules/ambulatory2/' . strtr('amb_clinic_patients_pass.php' . URL_APPEND . '&rt=pflege&edit=1&dept=' . $$current_dept_LDvar . '&location_id=' . $$current_dept_LDvar . '&dept_nr=' . $current_dept_nr, ' ', ' ') . '">' . $current_dept_name . '</a>');
}

$smarty->assign('LDDiagnosis', $LDDiagnosis);
$smarty->assign('referrer_diagnosis', $referrer_diagnosis);
$smarty->assign('LDRecBy', $LDReg);
$smarty->assign('registration_fee', $registration_fee);
#$smarty->assign('LDTherapy',$LDReg);
$smarty->assign('LDTherapy', $LDCon);
$smarty->assign('consultation_fee', $consultation_fee);
$smarty->assign('LDFinanceClass', $LDFinanceClass);
$smarty->assign('finance_class', $financial_class);
$smarty->assign('LDReferals', $LDReferals);
$smarty->assign('referals', $referals);
$smarty->assign('LDSpecials', $LDSpecials);
$smarty->assign('referrer_notes', $referrer_notes);

$smarty->assign('LDBillType', $LDBillType);

if (isset($$insurance_class['LD_var']) && !empty($$insurance_class['LD_var']))
    $smarty->assign('sBillTypeInput', $$insurance_class['LD_var']);
else
    $smarty->assign('sBillTypeInput', $insurance_class['name']);

$smarty->assign('LDInsuranceNr', $LDInsuranceNr);
if (isset($insurance_nr) && $insurance_nr)
    $smarty->assign('insurance_nr', $insurance_nr);

$smarty->assign('LDInsuranceCo', $LDInsuranceCo);
$smarty->assign('insurance_firm_name', $insurance_firm_name);

$smarty->assign('LDFrom', $LDFrom);
$smarty->assign('LDTo', $LDTo);

if (!$GLOBAL_CONFIG['patient_service_care_hide'] && $sc_care_class_nr) {
    $smarty->assign('LDCareServiceClass', $LDCareServiceClass);

    while ($buffer = $care_service->FetchRow()) {
        if ($sc_care_class_nr == $buffer['class_nr']) {
            if (empty($$buffer['LD_var']))
                $smarty->assign('sCareServiceInput', $buffer['name']);
            else
                $smarty->assign('sCareServiceInput', $$buffer['LD_var']);
            break;
        }
    }

    if ($sc_care_start && $sc_care_start != DBF_NODATE) {
        $smarty->assign('sCSFromInput', ' [ ' . @formatDate2Local($sc_care_start, $date_format,"-") . ' ] ');
        $smarty->assign('sCSToInput', ' [ ' . @formatDate2Local($sc_care_end, $date_format,"-") . ' ]');
    }
}


if (!$GLOBAL_CONFIG['patient_service_room_hide'] && $sc_room_class_nr) {
    $smarty->assign('LDRoomServiceClass', $LDRoomServiceClass);

    while ($buffer = $room_service->FetchRow()) {
        if ($sc_room_class_nr == $buffer['class_nr']) {
            if (empty($$buffer['LD_var']))
                $smarty->assign('sCareRoomInput', $buffer['name']);
            else
                $smarty->assign('sCareRoomInput', $$buffer['LD_var']);
            break;
        }
    }
    if ($sc_room_start && $sc_room_start != DBF_NODATE) {
        $smarty->assign('sRSFromInput', ' [ ' . @formatDate2Local($sc_room_start, $date_format,"-") . ' ] ');
        $smarty->assign('sRSToInput', ' [ ' . @formatDate2Local($sc_room_end, $date_format,"-") . ' ]');
    }
}

if (!$GLOBAL_CONFIG['patient_service_att_dr_hide'] && $sc_att_dr_class_nr) {
    $smarty->assign('LDAttDrServiceClass', $LDAttDrServiceClass);

    while ($buffer = $att_dr_service->FetchRow()) {
        if ($sc_att_dr_class_nr == $buffer['class_nr']) {
            if (empty($$buffer['LD_var']))
                $smarty->assign('sCareDrInput', $buffer['name']);
            else
                $smarty->assign('sCareDrInput', $$buffer['LD_var']);
            break;
        }
    }
    if ($sc_att_dr_start && $sc_att_dr_start != DBF_NODATE) {
        $smarty->assign('sDSFromInput', ' [ ' . @formatDate2Local($sc_att_dr_start, $date_format,"-") . ' ] ');
        $smarty->assign('sDSToInput', ' [ ' . @formatDate2Local($sc_att_dr_end, $date_format,"-") . ' ]');
    }
}

$smarty->assign('LDAdmitBy', $LDAdmitBy);
if (empty($encoder))
    $encoder = $_COOKIE[$local_user . $sid];
$smarty->assign('encoder', $encoder);

# Buffer the options block

ob_start();

require('./gui_bridge/default/gui_patient_encounter_showdata_options.php');
$sTemp = ob_get_contents();



ob_end_clean();

$smarty->assign('sAdmitOptions', $sTemp);
$sTemp = '';

if (!$is_discharged) {

    # Buffer the control buttons
    ob_start();

    include('./include/bottom_controls_admission.inc.php');
    $sTemp = ob_get_contents();

    ob_end_clean();

    $smarty->assign('sAdmitBottomControls', $sTemp);
}

$smarty->assign('pbBottomClose', '<a href="' . $breakfile . '"><img ' . createLDImgSrc($root_path, 'close2.gif', '0') . '  title="' . $LDCancel . '"  align="absmiddle"></a>');

$smarty->assign('sAdmitLink', '<img ' . createComIcon($root_path, 'varrow.gif', '0') . '> <a href="aufnahme_start.php' . URL_APPEND . '&mode=?">' . $LDAdmWantEntry . '</a>');
$smarty->assign('sSearchLink', '<img ' . createComIcon($root_path, 'varrow.gif', '0') . '> <a href="aufnahme_daten_such.php' . URL_APPEND . '">' . $LDAdmWantSearch . '</a>');
$smarty->assign('sArchiveLink', '<img ' . createComIcon($root_path, 'varrow.gif', '0') . '> <a href="aufnahme_list.php' . URL_APPEND . '&newdata=1">' . $LDAdmWantArchive . '</a>');
$currUser = $_SESSION['sess_user_name'];
if ($currUser == 'Admin') {
    $smarty->assign('sUpdateDates', '<img ' . createComIcon($root_path, 'varrow.gif', '0') . '> <a href="#" id="show-btn">Update Dates</a>');
}
$smarty->assign('sMainBlockIncludeFile', 'registration_admission/admit_show.tpl');

$smarty->display('common/mainframe.tpl');

?>
<input type='text' id='encNo' value='<?php echo $_REQUEST['encounter_nr']; ?>'>
<input type='text' id='pid2' value='<?php echo $pid; ?>'>
<input type='text' id='names2' value='<?php echo $name_first.' '.$name_last.' '.$name_2; ?>'>
<input type='text' id='dob' value='<?php echo $date_birth; ?>'>
<!--<link rel="stylesheet" type="text/css" href="../../include/Extjs/resources/css/ext-all.css" />-->
<!--<script type="text/javascript" src="../../include/Extjs/adapter/ext/ext-base.js"></script>-->
<!--<script type="text/javascript" src="../../include/Extjs/ext-all.js"></script>-->

<script src="../../../ext-6.5/build/ext-all-debug.js"></script>
<link rel="stylesheet" href="../../../ext-6.5/build/classic/theme-triton/resources/theme-triton-all.css">
<link rel="stylesheet" href="reportsCss.css">
<script language="javascript" src="<?php echo $root_path; ?>js/dtpick_care2x.js"></script>

<!--<link rel="stylesheet" type="text/css" href="../../include/Extjs/shared/examples.css" />-->
<!--<script type="text/javascript" src="../../include/Extjs/shared/examples.js"></script>-->
<!-- <script language="javascript" src="vitals.js"></script>
<script language="javascript" src="prescriptions.js"></script> -->


<script>

    Ext.onReady(function(){
        var win;
        var button = Ext.get('show-btn');
		var encNo=document.getElementById('encNo').value;

        //document.getElementById('patientStatus').innerHTML='My name is George';
		Ext.Ajax.request({
		url: '../../data/getPatientFunctions.php?caller=getTreatmentData&encNo='+encNo,

			 success: function(response, opts) {
				 var obj = Ext.decode(response.responseText);
				 console.dir(obj);
				 
				 var outputTable="<table width=100%><tr><td>"+obj.treatment+"<td><tr>";
				 outputTable+="<tr><td></td></tr>";
				 outputTable+="<tr><td>"+obj.status+"</td></tr>";
                

				 document.getElementById('patientStatus').innerHTML=outputTable;
			 },

			 failure: function(response, opts) {
				 console.log('server-side failure with status code ' + response.status);
			 }
		 });


        button.on('click', function(){
        
            var admForm = new Ext.FormPanel({
                labelWidth: 75, // label settings here cascade unless overridden
                url:'updateAdmissionDate.php',
                frame:true,
                bodyStyle:'padding:5px 5px 0',
                width: 300,
                defaults: {width: 230},
                defaultType: 'textfield',

                items: [{
                        fieldLabel: 'PID',
                        name: 'pid',
                        id: 'pid',
                        allowBlank:false
                    },{
                        fieldLabel: 'Encounter No',
                        name: 'encNo',
                        id: 'encNo'
                    },   new Ext.form.DateField({
                        fieldLabel: 'Admission Date',
                        name: 'admDate',
                        id: 'admDate',
                        width:190,
                        allowBlank:false
                    })
                ],

                buttons: [{
                        text: 'Save', handler: function() {
                            var pid = admForm.getForm().findField("pid").getValue();
                            var encNo=admForm.getForm().findField('encNo').getValue();
                            var admDate=admForm.getForm().findField('admDate').getValue();
                            var d=new Date();
                        if(admDate>d){
                            Ext.Msg.alert('Invalid Date','Admission date cannot be greater that Now.'+d);
                        }else{
                            Ext.Ajax.request({
                                url: 'updateAdmDates.php',
                                method: 'POST',
                                params: {
                                    pid:pid,
                                    encNo:encNo,
                                    admDate:admDate,
                                    task:"updateAdm"
                                },
                                waitMsg:'Saving Data...',
                                success: function (form, action) {
                                    win.hide();
                                },
                                failure:function(form, action) {
                                    Ext.MessageBox.alert('Message', 'Save failed, Check that all values are OK ');
                                }
                            });
                        }

                        }
                    },{
                        text: 'Cancel', handler: function() {
                            win.hide();
                        }
                    }]
            });
            // create the window on the first click and reuse on subsequent clicks
            if(!win){
                win = new Ext.Window({
                    applyTo:'hello-win',
                    layout:'fit',
                    title: 'Admission Date Form',
                    width:500,
                    height:300,
                    closeAction:'hide',
                    plain: true,
                    items: [admForm]

                });
            }
            win.show(this);
            admForm.getForm().findField("pid").setValue("<?php echo $pid ?>");
            admForm.getForm().findField("encNo").setValue("<?php echo $encounter_nr ?>");
        });
    });
    
    function getDatesForm(){
        alert("Update admission Dates");
    }
    
    function displayLabel(pid) { 
        window.open('http://localhost/receipt/label.php?pid='+pid
        ,"Label","menubar=no,toolbar=no,width=300,height=550,location=yes,resizable=no,scrollbars=no,status=yes");
    }
</script>