<?php
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once 'roots.php';require($root_path . "modules/accounting/extIncludes.php"); ?>
<link rel="stylesheet" type="text/css" href="accounting.css">
<?php
require($root_path . 'include/inc_environment_global.php');
require_once($root_path . 'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path . 'include/inc_init_xmlrpc.php');
require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
require($root_path . 'include/care_api_classes/accounting.php');
require_once($root_path . 'include/care_api_classes/class_tz_insurance.php');

$insurance_obj = new Insurance_tz;
$bill_obj = new Bill;
require_once('myLinks_1.php');

$debug=false;
$pid = $_POST['pid'];
$finalizeMode = $_POST['finalizeMode'];
$billNumber=$_POST['billNumber'];
$accno = $_POST['accNo'];
$catName=$_POST['debtorType'];
//$finDate=$_POST['fdate'];

$date1 = new DateTime($_POST[fdate]);
$finDate = $date1->format("Y-m-d");

//require_once('report_functions.php');
//    jsIncludes();
echo "<table width=100% border=0>
        <tr class='titlebar'><td colspan=2 bgcolor=#99ccff><font color='#330066'>Finalize Invoices</font></td></tr>
    <tr><td align=left valign=top>";
require 'acLinks.php';
echo '</td><td width=80% valign=top>';

if (!isset($_POST[submit])) {
    Finalize();
} else {
    
    if ($finalizeMode == 'pid') {
            finalizePID($pid, $bill_obj, $insurance_obj,$billNumber,$accno);
    }elseif ($finalizeMode == 'accNo') {
            finalizeAccount($accno,$bill_obj,$finDate,$insurance_obj);
    }elseif ($finalizeMode == 'DBCat') {
            finalizeByCategory($catName,$bill_obj);
    } else {
            echo "<div class='myMessage'>Error Finalizing Invoice:<br> " . $sql . "</div>";
            Finalize();
    }
                
 }

echo "</td></tr></table>";


function getDiscount($accno){
    global $db;
    $debug=false;
    
    $sql="select staffDiscount from care_ke_debtors where accno='$accno'";
    if($debug) echo $sql;
    if($results=$db->Execute($sql)){
        $row=$results->FetchRow();
        return $row[0];
    }else{
        return '0';
    }
    
    
}

function finalizeByCategory($catName,$bill_obj){
    global $db;
    $debug = false;
    $sql = "SELECT b.pid,b.encounter_nr,`ip-op`,bill_date,b.`bill_number`,SUM(total) AS total,e.debtorUpdate,c.id,c.accno,p.`insurance_ID`,e.`finalised`
            FROM care_ke_billing b LEFT JOIN care_person p ON b.`pid`=p.`pid` 
            LEFT JOIN CARE_TZ_COMPANY C ON P.`insurance_ID`=c.`id` 
            LEFT JOIN care_encounter e ON b.`encounter_nr`=e.`encounter_nr`
            WHERE service_type NOT IN('payment','NHIF','NHIF2','NHIF3','NHIF4') AND category='$catName'
            AND e.`finalised`=0 GROUP BY encounter_nr";
           if($debug) echo $sql;

   if ($result = $db->Execute($sql)) {
           while($row=$result->FetchRow()){

                   $encNr=$row[encounter_nr];
                   $pid=$row[pid];
                   $billNumber=$row[bill_number];
                   $bill_date=$row[bill_date];
                   $balance=$bill_obj->getDebtorBalance($encNr);
                   $accno=$row[accno];
                   if($accno=="11046" || $accno=="11047"){
                           if($balance<2000){
                                   $capitation=2000-$balance;
                                   $bill_obj->updateDebtorBill($encNr,$accno,$pid,$capitation,$bill_date);
                           }
                   }
                   
                   if($catName==='SS'){
                        $percDiscount=getDiscount($accno);
                        $discount=$percDiscount/100*$balance;
                        $bill_obj->updateDebtorDiscount($encNr,$accno,$pid,$discount,$bill_date);  
                   }
                   
                   $bill_obj->updateDebtorsTrans($pid, $accno, $encNr);

                  // echo "<script>window.open('reports/finalisedDetailsPDF.php?accNo=$accno&single=true','Reports','menubar=yes,toolbar=yes,width=500,height=550,location=yes,resizable=no,scrollbars=yes,status=yes');</script>";

                   echo "<div class='myMessage'>Invoice number  $billNumber finalized succefully</div>";
           }
   }

   Finalize();
}


function finalizePID($pid, $bill_obj, $insurance_obj,$billNumber,$accno) {
    global $db;
    $debug = false;
    $sql1 = "SELECT distinct b.pid,b.bill_number,b.encounter_nr,bill_date FROM care_ke_billing b LEFT JOIN care_encounter e 
                ON b.pid=e.pid WHERE b.pid=$pid and b.bill_number=$billNumber AND b.`ip-op`=2
                AND b.finalised=0";
    if($debug) echo $sql1;
    $result = $db->Execute($sql1);
    $rcount=$result->RecordCount();
    if ($rcount>0) {
        $rows = $result->FetchRow();
        $bill_number = $rows[1];
        $encNr = $rows[encounter_nr];
		$bill_date=$rows[bill_date];
        $stat = $rows[2];
        if ($stat <> "Finalized") {
            $sql2 = "SELECT sum(total) as total FROM care_ke_billing WHERE pid = '$pid' and `IP-OP`=2 
                        and service_type not in('payment','nhif','nhif3','Payment Adjustment') and bill_number='$billNumber'";
            if($debug) echo $sql2;
            $result2 = $db->Execute($sql2);
            $row2 = $result2->FetchRow();
          //  echo 'bill=' . $row2[0] . '<br>';

            $sql3 = "SELECT sum(total) as total FROM care_ke_billing WHERE pid = '$pid' and `IP-OP`=2
                and service_type in('payment','nhif','nhif3','Payment Adjustment') and rev_code<>'nhif2' and bill_number='$billNumber'";
            if($debug) echo $sql3;

            $result3 = $db->Execute($sql3);
            $row3 = $result3->FetchRow();
           // echo 'paid=' . $row3[0] . '<br>';

            $balance = intval($row2[0] - $row3[0]);
			
			
           // echo "balance=" . $balance;


            if ($balance < 0) {
                echo "<div class='myMessage'>The patient has paid in Excess of $balance</div>";
            }else{
                if ($balance ==0) {

                    $sql = "Update care_ke_billing set `finalised`='1' where finalised=0 and pid=$pid and encounter_nr=$encNr";
                    if ($debug) echo $sql;
                    $db->Execute($sql);

                    $sql="update care_encounter set is_discharged=1 and discharge_date='".date('Y-m-d')."' where pid=$pid and encounter_nr='$encNr'";
                    $db->Execute($sql);


                    echo "<div class='myMessage'>Invoice number  $bill_number finalized succefully</div>";
                    echo "<div class='myMessage2'><button id='preview'>Print Satement</button>
                            <button id='print'>Export Satement</button><button id='Preview'>Preview Invoices</button></div>";
                } else {
                    $IS_PATIENT_INSURED = $insurance_obj->is_patient_insured($pid);

                    if ($IS_PATIENT_INSURED) {

                        $sql="SELECT b.pid,encounter_nr,`ip-op`,bill_date,bill_number,b.`partcode`,b.`rev_code`,
                                b.`service_type`,b.`Description`,b.`total`,b.`status`,d.`gl_sales_acct`
                                FROM care_ke_billing b left join `care_tz_drugsandservices` d on b.`partcode`=d.`partcode`
                                 WHERE b.pid=$pid and encounter_nr=$encNr
                                AND service_type NOT IN('payment','NHIF','NHIF2','NHIF3','NHIF4')";
                        if($debug) echo $sql;
                        $results=$db->Execute($sql);

                        $insuCompanyID = $insurance_obj->GetCompanyFromPID2($pid);
                        $capitation=0;
                            $balance=$bill_obj->getDebtorBalance($encNr);
                            if($insuCompanyID=="11046" || $insuCompanyID=="11047"){
                                if($balance<2000){
                                    $capitation=2000-$balance;
                                    $bill_obj->updateDebtorBill($encNr,$insuCompanyID,$pid,$capitation,$bill_date);
                                }
                            }
                            
                            $debtorCat=checkDebtorCategory($insuCompanyID);
                            
                            if($debtorCat==='SS'){
                                $percDiscount=getDiscount($insuCompanyID);
                                $discount=$percDiscount/100*$balance;
                                $bill_obj->updateDebtorDiscount($encNr,$accno,$pid,$discount,$bill_date);  
                            }



//                        if($bill_obj->checkIncomeTrans('invoice')){
//                        while($row=$results->FetchRow()){
////                                $glCode=$bill_obj->getItemGL($rev_code);
//                            $bill_obj->updateIncomeTrans($row[gl_sales_acct],$row[total],$row[bill_date],$row[bill_number],'+','invoice');
//                            }
//                        }



                        $bill_obj->updateDebtorsTrans($pid, $insuCompanyID, $encNr);

//                        $sql = "Update care_ke_billing set `finalised`='1' where finalised=0 and pid=$pid and encounter_nr='$encNr'";
//                        if ($debug) echo $sql;
//                        $db->Execute($sql);
//
//                        $sql="update care_encounter set is_discharged=1,discharge_date='".date('Y-m-d')."', discharge_time='".date('H:i:s')."', finalised='1',bill_number='$billNumber',status='discharged' where pid=$pid and encounter_nr='$encNr'";
//                        $db->Execute($sql);

                        echo "<div class='myMessage'>Invoice number  $billNumber finalized succefully</div>";
                        echo "<script>window.open('reports/finalDetail_invoice_pdf.php?pid=$pid&receipt=1&nhif=1&final=1&billNumber=$billNumber' ,'Summary Invoice','menubar=yes,toolbar=yes,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes')</script>";

                    }else{
                        echo "<div class='myMessage'>The patient has a pending balance of Ksh $balance</div>";
                    }

                }
            }
            Finalize();
        } else {
            echo "<div class='myMessage'>Invoice number  $bill_number is already finalized</div>";
            Finalize();
        }
    }else{
        echo "<div class='myMessage'>Invoice number  $billNumber is already finalized</div>";
        Finalize();
    }
}

function checkDebtorCategory($accno){
    global $db;
    $debug=false;
    
    $sql="SELECT category FROM care_ke_debtors where accno='$accno'";
    $result=$db->Execute($sql);
    $rowCount=$result->RecordCount();
    if($rowCount>0){
        $row=$result->FetchRow();
        return $row[0];
    }else{
        return false;
    }
    
}


function finalizeAccount($accno,$bill_obj,$finDate,$insurance_obj){
    global $db;
    $debug = false;
    
    $str1=date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d'))));
    $yesterDate=$str1;
    
    $insuranceid=$insurance_obj->getInsuranceID_from_accno($accno);

    if($accno=="11046" || $accno=="11047"){
         $bill_obj->updateAONCapitation($insuranceid,$finDate);
    }
    
    $debtorCat=checkDebtorCategory($accno);
    
    $sql="SELECT b.pid,encounter_nr,CONCAT(p.`name_first`,' ',p.`name_last`,' ',p.`name_2`) AS pnames,b.bill_number,b.bill_date,
            SUM(b.total) AS Total,`ip-op`
            FROM care_ke_billing b 
            LEFT JOIN care_person p ON b.`pid`=p.`pid`
            WHERE b.pid<>'' and p.insurance_ID='$insuranceid' and finalised=0";
    
    if($finDate<>''){
        $sql=$sql." and b.bill_date='$finDate'";
    }else{
        $sql=$sql." and b.bill_date='$yesterDate'";
    }
    
    $sql=$sql." GROUP BY b.pid ";
    
           if($debug) echo $sql;
           $recsCount=0;
   if ($result = $db->Execute($sql)) {
           while($row=$result->FetchRow()){
               
                    if($debtorCat==='SS'){
                        $percDiscount=getDiscount($accno);
                        $discount=$percDiscount/100*$balance;
                        $bill_obj->updateDebtorDiscount($row[encounter_nr],$accno,$row[pid],$discount,$row[bill_date]);  
                    }

                   $bill_obj->updateDebtorsTrans($row[pid], $accno, $row[encounter_nr]);
      
                   $recsCount=$recsCount+1;
                  // echo "<script>window.open('reports/finalisedDetailsPDF.php?accNo=$accno&single=true','Reports','menubar=yes,toolbar=yes,width=500,height=550,location=yes,resizable=no,scrollbars=yes,status=yes');</script>";
                   echo "<div class='myMessage'>Pid $row[pid] ,Invoice number  $row[bill_number] finalized succefully
                                transdate=$row[bill_date] Total Records Finalized=$recsCount </div>";
           }
   }

   Finalize();
}

?>

