<?php

error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
require_once($root_path.'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path.'include/inc_init_xmlrpc.php');
require_once($root_path.'modules/accounting/billing_ke_pharm_quote.php');
require_once($root_path.'include/care_api_classes/class_tz_diagnostics.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
/**
* CARE2X Integrated Hospital Information System beta 2.0.1 - 2004-07-04
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@care2x.org,
*
* See the file "copy_notice.txt" for the licence notice
*/

global $db;
$lang_tables[]='aufnahme.php';
$lang_tables[]='pharmacy.php';
require($root_path.'include/inc_front_chain_lang.php');
require_once($root_path.'include/care_api_classes/class_person.php');
$person_obj = new Person;
$bill_obj = new Bill;

$diagnos_obj = new Diagnostics;
$enc_obj = new Encounter;
$externalcall=$_REQUEST['externalcall'];

// If this side is called by an external cross link, this will be stored into a session variable:
//echo $externalcall."....".$target;exit();

//if (empty($encounter_nr) and !empty($pid)) {
//	$encounter_nr = $person_obj->CurrentEncounter($pid);
//        $_SESSION['sess_en'] = $encounter_nr;
//}

$encounter_nr2 = $person_obj->CurrentMaxEncounter($pid); 

if(empty($_SESSION['sess_en'])){
    $_SESSION['sess_en'] = $encounter_nr2;
} 

$encounterClass=$enc_obj->EncounterClass($_SESSION['sess_en']);
     
$debug=false;

($debug)?$db->debug=TRUE:$db->debug=FALSE;
if ($debug) {
	if (!empty($back_path)) $backpath=$back_path;

    echo "file: show_prescription<br>";
    if (!isset($externalcall))
      echo "internal call<br>";
    else
      echo "external call=$externalcall<br>";

    echo "mode=".$mode."<br>";

    echo "show=".$show."<br>";

    echo "nr=".$nr."<br>";

    echo "breakfile: ".$breakfile."<br>";

    echo "backpath: ".$backpath."<br>";

    echo "pid:".$pid."<br>";

    echo "encounter_nr:".$encounter_nr."<br>";

    echo "Session-ecnounter_nr: ".$encounter_nr2." ".$_SESSION['sess_en']."<br>";

    echo "show onyl pharamcy articles is set to: ".$ShowOnlyPharmacy."<br>";

    echo "prescrServ: ".$prescrServ."<br>";
}

//echo '$prescrServ = '.$_GET['prescrServ'];

if(!$prescription_date) $prescription_date = date("Y-m-d");
define('NO_2LEVEL_CHK',1);
$thisfile=basename($_SERVER['PHP_SELF']);

$diagnosis=$diagnos_obj->checkCurrentEncounterDignosis($encounter_nr2);
$notes=$diagnos_obj->checkCurrentEncounterNotes($encounter_nr2);
$vitals=$diagnos_obj->checkCurrentEncounterVitals($encounter_nr2);

$deptNr=$diagnos_obj->getCurrentDepartment($encounter_nr2);




if(!isset($mode)){
	$mode='show';
} elseif($mode=='create'||$mode=='update' || $mode=='delete') {

	//include_once($root_path.'include/care_api_classes/class_prescription.php');
	//if(!isset($obj)) $obj=new Prescription;

	include_once($root_path.'include/inc_date_format_functions.php');

	if($_POST['prescribe_date']) $_POST['prescribe_date']=@formatDate2STD($_POST['prescribe_date'],$date_format,"-");
	else $_POST['prescribe_date']=date('Y-m-d');

	$_POST['create_id']=$_SESSION['sess_user_name'];

	//$db->debug=true;
	// Insert the prescription without other checks into the database. This should be dony be the doctor and
	// there was the requirement that there should be no restrictions given...
	//include('./include/save_admission_data.inc.php');

	include('include/save_prescription_data.inc.php');

}

/* For external call, there is no encounter number given. This can be determined if we have the encounter no in the $pn variable */

if (isset($pn)) {
  require_once($root_path.'include/care_api_classes/class_encounter.php');
  $encounter_obj=new Encounter($pn);
  $pid=$encounter_obj->EncounterExists($pn);
  $_SESSION['sess_pid']=$pid;
  $_SESSION['sess_en']=$pn;
}

//$consultation=$bill_obj->checkConsultationPayment( $_SESSION['sess_en']);
//    if($consultation){


require('./include/init_show.php');

if($parent_admit){
    $sql="SELECT pr.*, e.encounter_class_nr,e.current_dept_nr FROM care_encounter AS e, care_person AS p, 
        care_encounter_prescription AS pr, care_tz_drugsandservices as service
		WHERE p.pid=".$_SESSION['sess_pid']."
			AND p.pid=e.pid
			AND e.encounter_nr=".$_SESSION['sess_en']."
			AND e.encounter_nr=pr.encounter_nr
			AND service.item_id=pr.article_item_number
			AND service.is_labtest=0
                        AND pr.drug_class in ('drug_list')
                        AND pr.status='pending'
		ORDER BY pr.modify_time DESC";
//    echo $sql;
}else{
	if ($ShowOnlyPharmacy) {
		$sql="SELECT pr.*, e.encounter_class_nr,e.current_dept_nr FROM care_encounter AS e, care_person AS p, care_encounter_prescription AS pr, care_tz_drugsandservices
		  WHERE p.pid=".$_SESSION['sess_pid']." AND p.pid=e.pid AND e.encounter_nr=pr.encounter_nr AND pr.article_item_number=care_tz_drugsandservices.item_id AND ( purchasing_class = 'drug_list' OR purchasing_class ='Medical-Supplies' OR purchasing_class ='Dental-Services')
		  ORDER BY pr.modify_time DESC";
	} else {
		//echo '$prescr = '.$_GET['prescrServ'];
		if ($_GET['prescrServ']=="serv")
		{
			$SQLCrit = "( service.purchasing_class = 'xray' OR service.purchasing_class = 'service' OR service.purchasing_class ='Dental-Services' OR service.purchasing_class ='physiotherapy' OR service.purchasing_class ='Theatre')";
		}
		else
		{
			$SQLCrit = "( service.purchasing_class = 'drug_list' OR service.purchasing_class ='Medical-Supplies' OR service.purchasing_class ='supplies_laboratory' OR service.purchasing_class ='special_others_list')";
		}

		$sql="SELECT pr.*, e.encounter_class_nr,e.current_dept_nr FROM care_encounter AS e, care_person AS p, care_encounter_prescription AS pr, care_tz_drugsandservices as service
		  WHERE p.pid=".$_SESSION['sess_pid']." AND p.pid=e.pid AND e.encounter_nr=pr.encounter_nr AND service.item_id=pr.article_item_number
			AND service.is_labtest=0 AND ".$SQLCrit." ORDER BY pr.modify_time DESC";

//		echo $sql;

		// old code:

		/*
		$sql="SELECT pr.*, e.encounter_class_nr FROM care_encounter AS e, care_person AS p, care_encounter_prescription AS pr, care_tz_drugsandservices as service
		  WHERE p.pid=".$_SESSION['sess_pid']." AND p.pid=e.pid AND e.encounter_nr=pr.encounter_nr AND service.item_id=pr.article_item_number
			AND service.is_labtest=0 AND ( service.purchasing_class = 'drug_list' OR service.purchasing_class ='supplies' OR purchasing_class ='dental')
		  ORDER BY pr.modify_time DESC";
		 */

	}
}
//echo $sql;

if($result=$db->Execute($sql)){
	$rows=$result->RecordCount();
}else{
// echo $sql;
}



if($resultTemp=$db->Execute($sql)){
	//echo 'executed successfully';
}else{
// echo $sql;
}


$showHist = '';


while ($row_hist = $resultTemp->FetchRow())
{

	$tmp = str_replace ("\r","",$row_hist['history']);
	$tmp = str_replace ("\n","",$tmp);
	$showHist .= $tmp;
	$showHist .= '\n';

}

//echo '$showHist: '.$showHist;


$subtitle=$LDPrescriptions;
$notestype='prescription';
$_SESSION['sess_file_return']=$thisfile;

$buffer=str_replace('~tag~',$title.' '.$name_last,$LDNoRecordFor);
//$norecordyet=str_replace('~obj~',strtolower($subtitle),$buffer);

if ($prescrServ != "serv")
$norecordyet=str_replace('~obj~',strtolower($LDPrescription),$buffer);
else
$norecordyet=str_replace('~obj~',strtolower($subtitle),$buffer);


//else{
	require('./gui_bridge/default/gui_show.php');
//}

/* Load GUI page */
        
//    }else{
//         header("location:../validateFile.php?target=Diagnosis&allow_update=1&pid=".$_SESSION['sess_pid']);
//	    exit;
//    }

?>
