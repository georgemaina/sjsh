<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
/**
* CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
* GNU General Public License
* Copyright 2005 Robert Meggle based on the development of Elpidio Latorilla (2002,2003,2004,2005)
* elpidio@care2x.org, meggle@merotech.de
*
* See the file "copy_notice.txt" for the licence notice
*/
require_once($root_path.'include/care_api_classes/class_encounter.php');
$enc_obj=new Encounter;

$prescrServ=$_GET['prescrServ'];

$debug = false;

if ($debug) {
  echo $pn."<br>";
  echo $prescription_date."<br>";
  echo "comming from ".$comming_from."<br>";
  echo "back path:".$back_path."<br>";
  echo "prescrServ: ".$prescrServ."<br>";

}

if (empty($back_path))
  $RETURN_PATH= $root_path."modules/pharmacy_tz/pharmacy_tz.php?ntid=false&lang=$lang";
else {
  if ($back_path=="billing")
    $RETURN_PATH= $root_path."modules/billing_tz/billing_tz.php";
  if ($back_path=="laboratory")
    $RETURN_PATH= $root_path."modules/laboratory/labor.php";
}
//http://localhost/care2x/modules/pharmacy_tz/pharmacy_tz_pending_prescriptions.php
//?sid=db2af2f4c2b741fcf39a75a5aecbec25&ntid=false&
//lang=en&target=search&task=newprescription&back_path=&pharmacy=yes
if ($mode=="done" && isset($pn) && isset($prescription_date)) {
	
  // Update the datbase: Set this prescription as "done"
  $sql = "UPDATE
                care_encounter_prescription
          SET status = 'done'
          WHERE encounter_nr = ".$pn."
                AND prescribe_date = '".$prescription_date."'";
  ($debug) ? $db->debug=TRUE : $db->debug=FALSE;
  $db -> Execute ($sql);
  if($discharge)
  	header ( 'Location: ../ambulatory/amb_clinic_discharge.php'.URL_REDIRECT_APPEND.'&pn='.
            $encounter.'&pyear='.date("Y").'&pmonth='.date("n").'&pday='.date(j).'
            &tb='.str_replace("#","",$cfg['top_bgcolor']).'&tt='.str_replace("#","",
            $cfg['top_txtcolor']).'&bb='.str_replace("#","",$cfg['body_bgcolor']).'&d='.
            $cfg['dhtml'].'&station='.$station.'&backpath='.
            urlencode('../pharmacy_tz/pharmacy_tz_pending_prescriptions.php').'&dept_nr='.$dept_nr);


  // Clear the status:
  $mode = "";
  $pn="";
  $prescription_date="";
} else {
  // Fall back, either mode is not done or batch number is missing
  // => make a usual form here
  $mode = "";
}
	if(!$mode) /* Get the pending test requests */
	{

	$sql="SELECT p.pid, p.selian_pid, name_first, name_last, pr.encounter_nr, pr.prescribe_date, 
            p.pid as batch_nr,e.encounter_class_nr FROM care_encounter_prescription pr 
                inner join care_encounter e on pr.encounter_nr = e.encounter_nr 
                and (pr.status='pending' OR pr.status='') 
                inner join care_person p on e.pid = p.pid 
                inner join care_tz_drugsandservices d on pr.article_item_number=d.item_id 
                and (pr.drug_class = 'drug_list' OR d.purchasing_class ='MEDICAL-SUPPLIES'
                OR d.purchasing_class ='Dental' and pr.article not like '%consult%') ";

        $encClass=$_REQUEST[encounterClass];
        if($encClass){
            $sql.=" where e.encounter_class_nr='$encClass' ";
        }

        $sql.=" group by e.encounter_class_nr ,pr.prescribe_date, pr.encounter_nr, p.pid,
                p.selian_pid, name_first, name_last
                having datediff(now(),pr.prescribe_date)<7 ORDER BY pr.prescribe_date ASC";
                
               // echo $sql;
                                            
		if($requests=$db->Execute($sql)){

		  if ($requests->RecordCount()>0) {

  			/* If request is available, load the date format functions */

  			if ($debug) echo $sql;
  			require_once($root_path.'include/inc_date_format_functions.php');

  			$batchrows=$requests->RecordCount();
  			//if($batchrows && (!isset($batch_nr) || !$batch_nr)) {
  			if($batchrows ) {

  			  if ($debug) echo "<br>got rows...";

  				$test_request=$requests->FetchRow();
  				 /* Check for the patietn number = $pn. If available get the patients data */
  				$requests->MoveFirst();
  				/*
  				while($test_request=$requests->FetchRow())
  				  echo $test_request['encounter_nr']."<br>";
  				*/
  				if (empty($pn))
  				  $pn=$test_request['encounter_nr'];
  				if (empty($prescription_date))
  				  $prescription_date = $test_request['prescribe_date'];
  				if (empty($batch_nr))
  				  $batch_nr=$test_request['batch_nr'];
  				if ($debug) echo $batch_nr."<br>".$prescription_date."<br>";
  			}
  		} else {
  		    $NO_PENDING_PRESCRIPTIONS = TRUE;
  	  }
		}else{
			echo "<p>$sql<p>$LDDbNoRead";
			exit;
		}
		$mode="show";
	}

$lang_tables[]='billing.php';

require($root_path.'include/inc_front_chain_lang.php');

require ("gui/gui_pharmacy_tz_pending_prescriptions.php");

?>