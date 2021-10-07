<?php

require_once('roots.php');
require($root_path . 'include/inc_environment_global.php');
require_once($root_path . 'include/care_api_classes/class_tz_insurance.php');
require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
$insurance_obj = new Insurance_tz;
 $bill_obj = new Bill;
$desc = $_GET[rev];
if (!empty($_GET['callerID'])) {
    $caller = $_GET['callerID'];
} else {
    $caller = $_POST['callerID'];
}
if (!empty($_GET['pid'])) {
    $pid = $_GET['pid'];
} else {
    $pid = $_POST['pid'];
}

if (!empty($_GET['billNumber'])) {
    $billNumber = $_GET['billNumber'];
} else {
    $billNumber = $_POST['billNumber'];
}

if (!empty($_GET['fdate'])) {
    $fdate = $_GET['fdate'];
} else {
    $fdate = $_POST['fdate'];
}

if (!empty($_GET['dt1'])) {
    $dt1 = $_GET['dt1'];
} else {
    $dt1 = $_POST['dt1'];
}

if (!empty($_GET['dt2'])) {
    $dt2 = $_GET['dt2'];
} else {
    $dt2 = $_POST['dt2'];
}
$claimNo=$_REQUEST['claimNo'];
$bill_number=$_POST['bill_number'];
//$pid2 = $_POST[pid];
//$caller = ($_POST['caller']) ? ($_POST['caller']) : null;

switch ($caller) {
    case "debit":
        getPNames($pid);
        break;
    case "grid":
        getDescription($desc);
        break;
    case "getBillNumbers":
        getBillNumbers($pid);

        break;
    case "finalize":
        finalizeInvoice($db, $pid, $billNumber, $fdate);
        break;
    case "getNewReceipt":
        getNewReceipt();
        break;
    case "nhif":
        getNHIFCredits($dt1, $dt2);
        break;
    case "closeBill":
        closeBill($insurance_obj,$bill_obj);
        break;
    case "deleteClaim":
        deleteClaim($claimNo,$pid,$bill_number);
        break;
    default:
        echo "{failure:true}";
        break;
}//end switch
//function getBillBumbers($pid) {
//    global $db;
//    $sql = "select billing_number from care_ke_billing where pid=$pid order by bill_date desc";
//    $result = $db->Execute($sql);
//    return $result;
//}

function deleteClaim($claimNo,$pid,$bill_number){
    global $db;
    
    
     $sql3="DELETE FROM CARE_KE_debtortrans WHERE PID='$pid' and bill_number='$bill_number' and accno in('NHIF','NHIF2')";
    if($db->Execute($sql3)){
        $sql2="DELETE FROM CARE_KE_billing WHERE PID='$pid' and batch_no='$claimNo' and service_type='NHIF'";
        if($db->Execute($sql2)){
           $sql="DELETE FROM CARE_KE_NHIFCREDITS WHERE admno='$pid' and creditno='$claimNo'";
                if($db->Execute($sql)){
                    echo "Successfully Removed NHIF Credit for PID $pid";
                }else{
                    echo 'Failure Delete Credit:'.$sql;
                }
        }else{
            echo 'Failure Delete bill:'.$sql2;
        }
    }else{
        echo 'Failure Delete Credit:'.$sql3;
    }
}

function closeBill($insurance_obj,$bill_obj){
   // global $db;
    $pid=$_REQUEST[pid];
    $encounterNo=$_REQUEST[enc_nr];
    $insuCompanyID = $insurance_obj->GetCompanyFromPID2($pid);
    $bill_obj->updateDebtorsTrans($pid,$insuCompanyID,$encounterNo);
}
//
function getNHIFCredits($dt1, $dt2) {
    global $db;
    $debug = false;

    $sql = "SELECT b.creditNo,b.inputDate,b.admno,b.NAMES,b.admDate,b.disDate,b.wrdDays,b.nhifNo,b.nhifDebtorNo,
	b.debtorDesc, b.invAmount,b.totalCredit,b.balance,n.bill_number,b.inputUser
	FROM care_ke_nhifcredits b left join care_ke_billing n on b.creditno=n.batch_no
    WHERE n.rev_code='NHIF' ";

    if ($dt1 && $dt2) {
        $date1 = new DateTime(date($dt1));
        $dt1 = $date1->format("Y-m-d");

        $date2 = new DateTime(date($dt2));
        $dt2 = $date2->format("Y-m-d");

        $sql = $sql . "and b.inputDate between '$dt1' and '$dt2'";
    }
    $result = $db->Execute($sql);
    if ($debug)
        echo $sql;

    $out = $out . '<table width=100%><tbody>
                    <tr>
                        <td colspan=14 align=center class="pgtitle">NHIF Credits</td>
                     </tr>
                     <tr>
                        <td colspan=14 align=center id="msg" class="myMessage"></td>
                     </tr>
                     <tr>
                        <td colspan=14 align=center><hr></td>
                     </tr>
                    <tr>
                        <td>Claim No</td>
                        <td>Date</td>
                        <td>Adm No</td>
                        <td>Names</td>
                        <td>Bill No</td>
                        <td align=right>Invoice</td>
                        <td align=right>Nhif Credit</td>
                        <td align=right>Balance</td>
                        <td align=center>Description</td>
                        <td align=center>G/L Acc</td>
                        <td align=center>User</td>
                        <td>Update</td>
                        <td>Delete</td>
                        <td>Print</td>
                     </tr>
                     <tr>
                        <td colspan=14 align=center><hr></td>
                     </tr>';
    $rowbg = 'white';
    while ($row = $result->FetchRow($result)) {
        $out = $out . '<tr bgcolor=' . $rowbg . '>
                        <td>' . $row[0] . '</td>
                        <td>' . $row[1] . '</td>
                        <td>' . $row[2] . '</td>
                        <td>' . $row[3] . '</td>
                        <td>' . $row['bill_number'] . '</td>
                        <td align=right>' . number_format($row[invAmount],2) . '</td>
                        <td align=right>' . number_format($row[totalCredit],2) . '</td>
                        <td align=right>' . number_format($row[balance],2) . '</td>
                        <td align=right> NHIF CARD ' . $row[7] . '</td>
                        <td align=right></td>
                        <td>' . $row['inputUser'] . '</td>
                        <td><a href="../credits.php?claimNo="'.$row[0].'" onclick="">Edit</a></td>
                            <td><button  onclick="deleteClaim('.$row[0] .','.$row[2] .','.$row[bill_number].')">Delete</button></td>
                        <td><button onclick="invoicePdf(' . $row[2] . ',1, ' . $row['bill_number'] . ')" id="printInv">Print Invoice</button></td>
                     </tr>';
        $rowbg = 'white';
    }
    $out = $out . '<tr><td colspan=12 align=center><input type="submit" id="print" name="print" value="Print Report" />
      <input type="submit" id="export" name="export" value="Export to Excel" /></td></tr>';
    $out = $out . '</tbody></table>';

    echo $out;
}

function getDescription($desc) {
    global $db;
    if ($desc) {

        $sql = "select purchasing_class,item_description, unit_price from care_tz_drugsandservices WHERE partcode='$desc'";
        $result = $db->Execute($sql);
        if (!$result) {
            echo 'Could not run query: ' . mysql_error();
            exit;
        }

        $row = $result->FetchRow();

        echo $row[0] . "," . $row[1] . "," . $row[2] . "," . $rowID; // 42
        //echo "Laboratory $rowID";
    } else {
        echo "....";
    }
}

function getPNames($pid) {
    global $db;
    if ($pid) {
        $sql = "SELECT b.name_first,b.name_2,b.name_last,a.encounter_nr,a.encounter_class_nr,
        a.current_ward_nr,w.description,a.finalised
        from care_person b
                inner join care_encounter a on a.pid=b.pid 
        inner join care_ward w on w.nr=a.current_ward_nr WHERE b.pid='$pid' 
		and a.encounter_class_nr=1";
        // $result = mysql_query("SELECT name,next_receipt_no FROM care_ke_cashpoints WHERE pcode='$desc2'");
        $result = $db->Execute($sql);
        if (!$result) {
            echo 'Could not run query: ' . mysql_error();
            exit;
        }
        $row = $result->FetchRow();

        $sql2 = "SELECT newdebitNo from care_ke_invoice";
        $result2 = $db->Execute($sql2);
        $row2 = $result2->FetchRow();
        if ($row2[0] <> '')
            $debitNo = $row2[0];
        else
            $debitNo = 'D10001';

        echo $row[0] . "," . $row[1] . "," . $row[2] . "," . $row[3] . "," . $row[4] .
        "," . $row[5] . "," . $row[6] . "," . $debitNo .",". $row[7]; // 42
    } else {
        echo "---";
    }
}

//getBillNumbers($pid2);
function getBillNumbers($pid2) {
    global $db;

    $sql = "select bill_number from care_encounter where pid=$pid2 
    and `encounter_class_nr`=1 order by encounter_date desc";

    $result = $db->Execute($sql);
    if (!$result) {
        echo 'Could not run query: ' . $sql;
        exit;
    }
    $billNos = '';
    $billNos = $billNos . "<form name='bills'><select id='billNumbers' name='billNumbers' onchange='finalised($pid2,this.value)'>
            <option>-Select Bill No-</option>";
    while ($row = $result->FetchRow()) {
//             if($row[0]<>''){
        $billNos = $billNos . "<option value='$row[0]'>$row[0]</option>";
//             }
    }
    $billNos = $billNos . "</select><forms>";

    echo $billNos;
}

function finalizeInvoice($db, $pid, $billNumber, $fdate) {
    global $db, $root_path;
    require_once($root_path . 'include/care_api_classes/class_tz_insurance.php');
    require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
    $insurance_obj = new Insurance_tz;
    $bill_obj = new Bill;
    $debug = false;
    if ($billNumber) {
        $sql = "SELECT distinct b.pid,b.bill_number,b.finalised,b.encounter_nr FROM care_ke_billing b LEFT JOIN care_encounter e 
                ON b.pid=e.pid WHERE b.pid='$pid' AND b.`ip-op`=1 and bill_number='$billNumber' AND e.is_discharged=1";

        if ($results = $db->Execute($sql)) {
//           echo 'success success';

            if ($debug)
                echo $sql;
            while ($rows = $results->FetchRow()) {
                $bill_number = $rows[1];
                $enc_nr=$rows[encounter_nr];
                $stat = $rows[2];
                if ($stat <> "1") {
                    $sql2 = "SELECT sum(total) as total FROM care_ke_billing WHERE pid = '$pid' and `IP-OP`=1 
            and service_type no in('payment','nhif') and bill_number=$billNumber";
                    $result2 = $db->Execute($sql2);
                    $row2 = $result2->FetchRow();
                    if ($debug)
                        echo $sql2;
                    $finalResults = $finalResults . 'bill=' . $row2[0] . '<br>';

                    $sql3 = "SELECT sum(total) as total FROM care_ke_billing WHERE pid = '$pid' and `IP-OP`=1 
                        and service_type in ('Payment','NHIF') and bill_number=$billNumber";
                    if ($debug)
                        echo $sql3;
                    $result2 = $db->Execute($sql3);
                    $row3 = $result2->FetchRow();
                    $finalResults = $finalResults . 'paid=' . $row3[0] . '<br>';

                    $balance = intval($row2[0] - $row3[0]);
                    $finalResults = $finalResults . "balance=" . $balance;

                    $sql4 = "select insurance_id from care_person where pid='$pid'";
                    $result4 = $db->Execute($sql4);
                    $row4 = $result4->FetchRow();

                    if ($balance == 0 || $row4[0] > 0) {

                        $sql = "Update care_ke_billing set `status`='Finalized',finalised='1' where pid='$pid' and 
                            bill_number='$bill_number'";
                        if ($debug)
                            echo $sql;
                        if ($db->Execute($sql)) {
//                            $IS_PATIENT_INSURED = $insurance_obj->is_patient_insured($pid);
//                            if ($IS_PATIENT_INSURED) {
//                                $insuCompanyID = $insurance_obj->GetCompanyFromPID2($pid);
//                                $bill_obj->updateDebtorsTrans($pid, $insuCompanyID, $enc_nr);
//                            }

                            $finalResults = $finalResults . "<div class='myMessage'>Invoice number  $bill_number finalized succefully</div>";
                            $finalResults = $finalResults . "<div> <button id='print' onclick=invoicePdf('" . $pid . "')>Print Finalized Invoice</button></div>";
                        }
                    } else {
                        $finalResults = $finalResults . "<div class='myMessage'>The patient has a pending balance of Ksh $balance</div>";
                    }
//                } else if ($balance < 0) {
//                    echo "<div class='myMessage'>The patient has paid in Excess of $balance</div>";
//                }
                } else {
                    $finalResults = $finalResults . "<div class='myMessage'>Invoice number  $bill_number is already finalized</div>";
                }
            }
        }
    } else {
        $finalResults = $finalResults . "<div class='myMessage'>Please Select Bill Numnber to Finalize $billNumber</div>";
    }



    echo $finalResults;
}

?>
