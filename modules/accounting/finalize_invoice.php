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
//require_once('report_functions.php');
//    jsIncludes();
echo "<table width=100% border=0>
        <tr class='titlebar'><td colspan=2 bgcolor=#99ccff><font color='#330066'>Finalize Invoices</font></td></tr>
    <tr><td align=left valign=top>";
require 'aclinks.php';
echo '</td><td width=80% valign=top>';

if (!isset($_POST[submit])) {
    Finalize();
} else {
    $pid = $_POST['pid'];
    $finalizeMode = $_POST['finalizeMode'];
    $billNumber=$_POST['billNumber'];
    $accno = $_POST['accNo'];
    $disDate=date('Y-m-d');
    $disTime=date*('Y-m-d');
//Finalize by Account
    if ($finalizeMode == 'accNo') {
        $sql1 = "SELECT distinct b.pid,b.bill_number,b.status,b.encounter_nr FROM care_ke_billing b LEFT JOIN care_encounter e
                ON b.pid=e.pid WHERE b.pid=$pid and bill_number=$billNumber AND b.`ip-op`=1
                AND e.is_discharged=1 and e.finalised=0";
        if($debug)
            echo $sql1;
        $result = $db->Execute($sql1);
        $rcount=$result->RecordCount();
            $rows = $result->FetchRow();

        $sql = "Update care_ke_billing set `finalised`='1' where finalised=0 and
                      insurance_id in (select c.id from care_person p 
                       inner join care_tz_company c on p.insurance_id=c.id 
                      where pid=$pid) and bill_number=$billNumber AND `ip-op`=1";
        if ($result = $db->Execute($sql)) {

                        $IS_PATIENT_INSURED = $insurance_obj->is_patient_insured($pid);
                        $insuCompanyID = $insurance_obj->GetCompanyFromPID2($pid);
                        if ($IS_PATIENT_INSURED) {
                            $bill_obj->updateDebtorsTrans($pid, $insuCompanyID, $rows[encounter_nr]);
                        }

                        $sql="update care_encounter set finalised=1,is_discharged=1,discharge_date='$disDate',dischargeTime='$disTime',
                                release_date='$disDate',status='discharged' where encounter_nr='$rows[encounter_nr]'";
                        $db->Execute($sql);
                        
                        $sql2="update care_encounter_location set discharged_type_nr=1,date_to='$disDate',time_to='$disTime',
                               status='discharged' where encounter_nr='$rows[encounter_nr]'";
                        $db->Execute($sql2);

                    echo "<div class='myMessage'>Invoice number  $bill_number finalized succefully</div>";
                    echo "<div class='myMessage2'><button id='preview' onclick=''>Print Final Invoice</button></div>";
            ?>
            <button id='preview' onclick="printStatement('<?php $accno ?>')">Print Statement</button>
            <button id='print'  onclick="exportExcel('<?php echo $accno ?>')">Export Statement</button>
            <button id='Preview' onclick="getStatement('<?php echo $accno ?>')">Preview Invoices</button></div>
            <?php
            Finalize();
        } else {
            echo "<div class='myMessage'>Error Finalizing Invoice:<br> " . $sql . "</div>";
            Finalize();
        }
    }
    //Finalize by PID
    if ($finalizeMode == 'pid') {
        finalizePID($pid, $bill_obj, $insurance_obj,$billNumber,$accno);
    }
}
echo "</td></tr></table>";


function finalizePID($pid, $bill_obj, $insurance_obj,$billNumber,$accno) {
    global $db;
    $debug = false;
    $sql1 = "SELECT distinct b.pid,b.bill_number,e.finalised,b.encounter_nr,`IP-OP` as encounter_class FROM care_ke_billing b LEFT JOIN care_encounter e 
                ON b.pid=e.pid WHERE b.pid=$pid and b.bill_number=$billNumber AND b.`ip-op`=1
                AND e.finalised=0";
              if($debug) echo $sql1;
        $result = $db->Execute($sql1);
         $rcount=$result->RecordCount();
    if ($rcount>0) {
        $rows = $result->FetchRow();
        $bill_number = $rows[1];
        $encNr = $rows[encounter_nr];
        $encClass=$rows[encounter_class];
        //$stat = $rows[2];
        if ($rows[2] ==0) {
            $sql2 = "SELECT sum(total) as total FROM care_ke_billing WHERE pid = '$pid' and `IP-OP`=1 
                        and service_type not in('payment','nhif','Payment Adjustment') and bill_number='$billNumber'";
                        if($debug) echo $sql2;
            $result2 = $db->Execute($sql2);
            $row2 = $result2->FetchRow();
            //echo 'bill=' . $row2[0] . '<br>';

            $sql3 = "SELECT sum(total) as total FROM care_ke_billing WHERE pid = '$pid' and `IP-OP`=1
                and service_type in('payment','nhif','Payment Adjustment') and rev_code<>'nhif2' and bill_number='$billNumber'";
                if($debug) echo $sql3;

            $result3 = $db->Execute($sql3);
            $row3 = $result3->FetchRow();
           // echo 'paid=' . $row3[0] . '<br>';

            $balance = intval($row2[0] - $row3[0]);
           // echo "balance=" . $balance;

            $IS_PATIENT_INSURED = $insurance_obj->is_patient_insured($pid);

           if ($balance < 0 && !$IS_PATIENT_INSURED) {
            echo "<div class='myMessage'>The patient has paid in Excess of $balance</div>";
           }else{
               if ($balance ==0) {

                   // updateBillERP($encClass,$pid,$encNr,$bill_obj,$billNumber);
                    setFinalized($encNr);
		   
                    echo "<div class='myMessage'>Invoice number  $bill_number finalized succefully</div>";
                    echo "<div class='myMessage2'><button id='preview'>Print Satement</button>
                            <button id='print'>Export Satement</button><button id='Preview' openInvoice($pid,$billNumber)>Preview Invoices</button></div>";
                } else {
                   if ($IS_PATIENT_INSURED) {
                       $sql = "Update care_ke_billing set `finalised`='1' where finalised=0 and pid=$pid and encounter_nr=$encNr";
                       if ($debug) echo $sql;
                       $result = $db->Execute($sql);
                       $insuCompanyID = $insurance_obj->GetCompanyFromPID2($pid);
                       $bill_obj->updateDebtorsTrans($pid, $insuCompanyID, $encNr);
                      
			//updateBillERP($encClass,$pid,$encNr,$bill_obj,$billNumber);
                        
                       $invoice="<script>window.open('reports/finalDetail_invoice_pdf.php?pid=$pid&billNumber=$billNumber&receipt=1&nhif=1&bill=1' 
                        ,'Summary Invoice','menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes');</script>";

                       echo $invoice;


                       echo "<div class='myMessage'>Invoice number $billNumber finalized succefully</div>";
                       echo "<div class='myMessage2'><button id='preview' onclick='openInvoice('.$pid.','.$billNumber.')'>Print Satement</button>";

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

function setFinalized($encNr){
    global $db;
        $debug=false;

      $releaseDate=date('Y-m-d');
      $time=date('H:i:s');

       $sql="update care_encounter set finalised=1,is_discharged=1,discharge_date='$releaseDate',discharge_time='$time',
                release_date='$releaseDate',status='discharged',
               history=CONCAT(history,'Update: " . date('Y-m-d H:i:s') . " = " . $_SESSION['sess_user_name'] . "\n') where encounter_nr='$encNr'";
        if($debug) echo $sql;
        $db->Execute($sql);

        $sql2="update care_encounter_location set discharged_type_nr=1,date_to='$releaseDate',time_to='$time',
               status='discharged' where encounter_nr='$encNr'";
        if($debug) echo $sql2;
        $db->Execute($sql2);
        
        
        $sql = "Update care_ke_billing set `finalised`='1',finalisedBy='".$_SESSION['sess_user_name']."',
                history='CONCAT(history,\'Update: " . date('Y-m-d H:i:s') . " = " . $_SESSION['sess_user_name'] . "\n' )' 
                where finalised=0 and encounter_nr=$encNr";
         if ($debug) echo $sql;
         $db->Execute($sql);

}

function updateBillERP($encClass,$pid,$enc_no,$bill_obj,$bill_number){
    global $db,$insurance_obj;
    $debug=false;

    $sql="SELECT a.pid, a.total AS ovamount,a.`rev_code` AS partcode,a.`Description` AS article,a.rev_code,
        a.bill_date AS prescribe_date, a.qty, total,a.bill_number,
        id,d.`category`,d.`salesAreas` AS salesArea,a.weberpsync
        FROM care_ke_billing a LEFT JOIN care_tz_drugsandservices d ON a.rev_code=d.partcode
        WHERE a.weberpsync=0 and a.pid='$pid' and a.bill_number='$bill_number' and pid regexp '[0-9]'";
    
//        $sql = "SELECT a.OP_no AS pid, a.price,a.item_id AS partcode,a.item_desc AS article,a. order_date AS prescribe_date,a.order_no AS bill_number,
//                a.unit_cost AS ovamount,store_loc AS salesArea,d.`category`
//                FROM care_ke_internal_orders a LEFT JOIN care_tz_drugsandservices d ON a.`item_id`=d.`partcode`
//                WHERE a.Op_no='$pid' and weberpsync=0";
        $result = $db->Execute($sql);
        if ($debug)
            echo $sql;
        //$arr=Array();
        while ($row = $result->FetchRow()) {
            if ($weberp_obj = new_weberp()) {
                if (!$weberp_obj->transfer_bill_to_webERP_asSalesInvoice($row)) {
                    $sql2="update care_ke_billing set finalised=1,history=CONCAT(history,'Update: " . date('Y-m-d H:i:s') . " = " . $_SESSION['sess_user_name'] . "\n') where encounter_nr='$encNr'";
                         if($debug) echo $sql;
                         $db->Execute($sql2);
                } else {
                    //echo "Failed to transmit item_ID --$row[partcode]--$row[article]  to weberp GL: Check GL Linkage<br>";
                }
                destroy_weberp($weberp_obj);
            }
        }
}

?>

<script>
    function openInvoice(pid,billNumber){
       window.open('finalSummary_invoice_pdf.php?pid='+name+"&receipt="+str2+"&nhif=1&bill=1" ,"Summary Invoice","menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
    }
   

</script>
