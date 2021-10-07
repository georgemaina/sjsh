<?php
/*------begin------ This protection code was suggested by Luki R. luki@karet.org ---- */
if (eregi('save_admission_data.inc.php',$_SERVER['PHP_SELF']))
	die('<meta http-equiv="refresh" content="0; url=../">');

$debug=false;
($debug)?$db->debug=TRUE:$db->debug=FALSE;
if ($debug) {
	if (!isset($externalcall))
		echo "internal call<br>";
	else
		echo "external call<br>";

	echo "mode=".$mode."<br>";

	echo "show=".$show."<br>";

	echo "nr=".$nr."<br>";

	echo "breakfile: ".$breakfile."<br>";

	echo "backpath: ".$backpath."<br>";

	echo "pid: d ".$pid."<br>";

	echo "encounter_nr:".$encounter_nr;

	echo "prescrServ: ".$_GET['prescrServ'];
}
$i=0;
if($mode=='delete') $arr_item_number[0] = $nr;
foreach ($arr_item_number AS $item_number) {

  $dosage               = $arr_dosage[$i];
  $notes                = $arr_notes[$i];
  $article_item_number  = $arr_article_item_number[$i];
  $price                = $arr_price[$i];
  $article              = $arr_article[$i];
  $timesperday		= $arr_timesperday[$i];
  $days			= $arr_days[$i];
  $history		= $arr_history[$i];

  $i++;

  //$obj->setDataArray($_POST);
$searchsql="SELECT item_id, item_full_description,unit_price,partcode FROM care_tz_drugsandservices WHERE partcode='".$article_item_number."'";
$searchresult=$db->Execute($searchsql);
$row=$searchresult->FetchRow();

  switch($mode){
  		case 'create':
  		            $sql="INSERT INTO care_encounter_prescription (
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
  		                          `modify_id`)
  		                          VALUES (
  		                          '".$encounter_nr."',
  		                          0,
  		                          '".$row[1]."',
  		                          '".$row[0]."',
                                          '".$row[3]."',
  		                          '".$row[2]."',
  		                          'drug_list',
  		                          '".$dosage."',
  		                          0,
  		                          '".$notes."',
  		                          '".$timesperday."',
  		                          '".$days."',
  		                          '".date('Y-m-d',time())."',
  		                          '".$prescriber."',
  		                          '1',
  		                          '".$history."',
  		                          '".date('Y-m-d',time())."',
  		                          '".$_SESSION['create_id']."'
  		                          )";
                  $db->Execute($sql);
//echo $sql;
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
                                    include_once($root_path.'include/inc_visual_signalling_fx.php');
                                    // Set the visual signal
                                    setEventSignalColor($encounter_nr,SIGNAL_COLOR_DOCTOR_INFO);
                                   
                                   
                                    //$WONumber=$WONumberArray[0];
                                    //if($is_transmit_to_weberp_enable == 1)
                //{



                //}

                                    
                                    break;
  		case 'update':

  					$sqlOld = "SELECT * from care_encounter_prescription WHERE nr=$nr";
  					$result = $db->Execute($sqlOld);
  					$row = $result->FetchRow();

  					$historyEntry = '';
  					$core = new Core;

  					if ($row['dosage']!=$dosage){

  						$historyEntry .= 'history ='.$core->ConcatFieldString('history', "Update dosage from ".$row['dosage']." to ".$dosage." / ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']." \n").', ';
						//echo $historyEntry;

  					}



  					if ($row['times_per_day']!=$timesperday){

  						$historyEntry .= "history =".$core->ConcatFieldString('history', "Update times_per_day from ".$row['times_per_day']." to ".$timesperday." / ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']." \n").", ";
						//echo $historyEntry;
  					}

  					if ($row['days']!=$days){

  						$historyEntry .= "history =".$core->ConcatFieldString('history', "Update days from ".$row['days']." to ".$days." / ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']." \n").", ";
						//echo $historyEntry;
  					}

  					if ($row['notes']!=$notes){

  						$historyEntry .= "history =".$core->ConcatFieldString('history', "Update notes from".$row['notes']." to ".$notes." / ".date('Y-m-d H:i:s')." ".$_SESSION['notes']." \n").", ";

  					}

  					if ($historyEntry != '')
  					{
						$historyEntry = substr($historyEntry, 0, -2);
						$sqlHist = 'UPDATE care_encounter_prescription SET '.$historyEntry.' WHERE nr = '.$nr;
						//echo $sqlHist;
						$db->execute($sqlHist);
  					}

  					//echo 'UPDATE care_encounter_prescription SET '.$historyEntry.' WHERE nr = '.$nr;




  		            $sql="UPDATE care_encounter_prescription SET
  		                          `dosage`='$dosage',
  		                          `times_per_day`='$timesperday',
  		                          `days`='$days',
  		                          `notes`='$notes',
  		                          `prescriber`='$prescriber'
  		                  WHERE nr=$nr";

  		                  //echo $sql;

                  $db->Execute($sql);

                  					//*******
 								  	// Load the visual signalling functions
									include_once($root_path.'include/inc_visual_signalling_fx.php');
									// Set the visual signal
									setEventSignalColor($encounter_nr,SIGNAL_COLOR_DOCTOR_INFO);
									//********
  								break;
  		case 'delete':
  		            $sql="DELETE FROM care_encounter_prescription WHERE nr=$nr";
                  $db->Execute($sql);

								  //if (isset($externalcall))
									//  header("location:".$thisfile.URL_REDIRECT_APPEND."&target=$target&type_nr=$type_nr&allow_update=1&externalcall=".$externalcall."&pid=".$_SESSION['sess_pid']);
 								  //exit;
  								break;
  }// end of switch
} // end of foreach
require_once($root_path.'include/care_api_classes/class_tz_Billing.php');
//$bill_obj = new Bill;
//$result3=$bill_obj->checkBillEncounter($encounter_nr);
//if(!$result3) {
//      $bill_obj->updateBillNo($encounter_nr);
//}
 createPhrmQuote($encounter_nr);
 $sql='select b.pid, a.price,a.partcode,a.Description as article,a.prescribe_date,a.bill_number,
(a.dosage*a.times_per_day*a.days) as amount
from care_ke_billing a, care_encounter b
where a.encounter_nr=b.encounter_nr and a.encounter_nr="'.$encounter_nr.'" and service_type="drug_list"';
$result=$db->Execute($sql);
$weberp_obj = new_weberp();
//$arr=Array();
while($row=$result->FetchRow()){
  //$weberp_obj = new_weberp();
        if(!$weberp_obj->transfer_bill_to_webERP_asSalesInvoice($row))
        {
//                echo 'success<br>';
//                echo date($weberp_obj->defaultDateFormat);
        }
        else
        {
                echo 'failed';
        }
        destroy_weberp($weberp_obj);
}

//If patient is insured transmit to weberp
// require_once($root_path.'include/care_api_classes/class_tz_insurance.php');
// $insurance_obj = new Insurance_tz;
//  $sql='SELECT a.insurance_id as pid, a.price,a.partcode,a.description as article,a.prescribe_date,a.bill_number,
//(a.dosage*a.times_per_day*a.days) AS amount,((a.dosage*a.times_per_day*a.days)*a.price) as total
//FROM care2x.care_ke_billing a, care2x.care_encounter b
//WHERE a.encounter_nr=b.encounter_nr AND a.encounter_nr="'.$encounter_nr.'" AND service_type="insured F member"';
//$result=$db->Execute($sql);
//$rows=$result->FetchRow();
//  $IS_PATIENT_INSURED=$insurance_obj->is_patient_insured($pid);
//   if($IS_PATIENT_INSURED){
//$result=$db->Execute($sql);
//
////$arr=Array();
//while($row=$result->FetchRow()){
//  //$weberp_obj = new_weberp();
//        if(!$weberp_obj->transfer_bill_to_webERP_asSalesInvoice($row))
//        {
//                echo 'success member transmission<br>';
//                echo date($weberp_obj->defaultDateFormat);
//        }
//        else
//        {
//                echo 'failed member transmission';
//        }
//        destroy_weberp($weberp_obj);
//}
//   }

//Adjust stocks after issuing drugs
 $sql='select a.partcode,a.prescribe_date,(a.dosage*a.times_per_day*a.days) as amount
from care_encounter_prescription a, care_encounter b
where a.encounter_nr=b.encounter_nr and a.encounter_nr="'.$encounter_nr.'" and drug_class="drug_list"';
$result=$db->Execute($sql);
$weberp_obj = new_weberp();
//$arr=Array();$StockID, $Location, $Quantity, $TranDate
while($row=$result->FetchRow()){
  //$weberp_obj = new_weberp();
        if(!$weberp_obj->stock_adjustment_in_webERP($row[0], 'CHK', $row[2], $row[1]))
        {
                echo 'success<br>';
                echo date($weberp_obj->defaultDateFormat);
        }
        else
        {
                echo 'failed';
        }
        destroy_weberp($weberp_obj);
}


//if (isset($externalcall)){
//	if ($backpath=='billing' || $backpath=='billing')
//  		header("location: $root_path/modules/billing_tz/billing_tz_quotation.php");
//  	else
//  		header("location:".$thisfile.URL_REDIRECT_APPEND."&target=$target&type_nr=$type_nr&allow_update=1&externalcall=".$externalcall."&backpath=".urlencode($backpath)."&prescrServ=".$_GET['prescrServ']."&pid=".$_SESSION['sess_pid']);
//} else
  header("location:".$thisfile.URL_REDIRECT_APPEND."&target=$target&type_nr=$type_nr&allow_update=1&backpath=".urlencode($backpath)."&prescrServ=".$_GET['prescrServ']."&pid=".$_SESSION['sess_pid']);

exit();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head><title></title>
        <link rel="shortcut icon" href="/favicon.ico" />
        <link rel="icon" href="/favicon.ico" />
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        <link href="/css/silverwolf/default.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src = "/javascripts/MiscFunctions.js"></script>
    </head><body>
        <table class="callout_main" cellpadding="0" cellspacing="0">
            <tr><td colspan="2" rowspan="2">
                    <table class="main_page" cellpadding="0" cellspacing="0">
                        <tr><td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
                                    <tr><td></td></tr></table><DIV class="error">
                                        <B>Database Error</B> :
                               <br />You have an error in your SQL syntax;
                               check the manual that corresponds to your
                               MySQL server version for the right syntax to use near '0,"P0833 x 15 @ ")' at
                               line 2</DIV></td></tr></table></td></tr>
        </table><p style="text-align:right">Friday, 13/11/2009 | 10:11</p>
        <table width="100%" id="footer">
            <tr><td class="footer"><a href="http://www.weberp.org" rel="external">
 <img src="/companies/weberp/logo.jpg" width="120" alt="webERP" title="webERP Copyright &copy; webrp.org - 2009" /></a>
                    <br />Version - </td></tr>
            <tr><td class="footer">webERP Copyright &copy; weberp.org - 2009</td>
            </tr><tr><td class="footer"><a href="http://sourceforge.net/projects/web-erp">
      <img src="http://sflogo.sourceforge.net/sflogo.php?group_id=70949&type=12" width="120" height="30" border="0" alt="Get webERP web-based ERP Accounting at SourceForge.net. Fast, secure and Free Open Source software downloads" /></a></td></tr></table></body></html>