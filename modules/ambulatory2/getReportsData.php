
<?php
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');

require_once($root_path . 'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path . 'include/inc_init_xmlrpc.php');
$limit = $_POST[limit];
$start = $_POST[start];
$item_number = $_POST[item_number];


$task = ($_REQUEST['task']) ? ($_REQUEST['task']) : '';
switch ($task) {
    case "getStatement":
        getStatement();
        break;
    case "exportExcel":
        exportExcel();
        break;
    default:
        echo "{failure:true}";
        break;
}//end switch

function substractDate($date,$days)
{
//    $cuurDate = $date;
    $date = new DateTime($date);
    $dt1 = $date->format("d-m-Y");
    $newdate = strtotime ('-'.$days.' day' , strtotime ($dt1)) ;
    $newdate = date ( 'Y/m/d' , $newdate );
   
    return $newdate;
}
function getLastMonth($date) {
    $date = new DateTime(date($date));

    $current_month = $date->format("m");
    ;
    $current_year = $date->format("Y");
    ;
    if ($current_month == 1) {
        $lastmonth = 12;
    } else {
        $lastmonth = $current_month - 1;
    }
    $firstdate = $current_year . "-" . $lastmonth . "-01";

    $timestamp = strtotime($firstdate);

    $lastdateofmonth = date('t', $timestamp); // 	this function will give you the number of days in given month(that will be last date of the month)
//echo '<br>' . $lastdateofmonth;
    $lastdate =  $current_year. "/" . $lastmonth . "/" . $lastdateofmonth;
//    echo '<br>' . $lastdate;
    return $lastdate;
}

function getStatement() {
    global $db;
    $debug=false;
    $accno = $_REQUEST[acc1];
    $date1 = $_REQUEST[date1];
    $date2 = $_REQUEST[date2];


    $sql = "SELECT p.pid,IF(p.name_first<>'',p.name_first,c.name) AS name_first,p.name_last, 
    p.name_2,b.bill_date,b.bill_number,
    SUM(IF(b.service_type<>'Payment',b.total,0)) AS debit,
    SUM(IF(b.service_type='Payment',b.total,0)) AS credit,
    b.service_type,c.accno,b.`IP-OP`
    FROM care_ke_billing b INNER JOIN care_person p ON b.pid=p.pid 
    INNER JOIN care_tz_company c ON c.id=p.insurance_id INNER JOIN care_ke_debtors d ON d.accno=c.accno 
    WHERE d.accno='$accno' and b.service_type<>'NHIF'";
    if ($date1) {
        $date = new DateTime($date1);
        $dt1 = $date->format("Y-m-d");
    } else {
        $dt1 = "";
    }
    if ($date2) {
        $date = new DateTime($date2);
        $dt2 = $date->format("Y-m-d");
    } else {
        $dt2 = "";
    }

    if ($dt1 <> "" && $dt2 <> "") {
        $sql = $sql . " and b.bill_date between '$dt1' and '$dt2' ";
    } else if ($dt1 <> '' && $dt2 == '') {
        $sql = $sql . " and b.bill_date = '$dt1'";
    } else {
        $sql = $sql . " and b.bill_date<=now()";
    }
    $sql = $sql . " GROUP BY b.bill_number ORDER BY b.bill_date asc";
    if($debug) echo $sql;
    //p.pid,p.name_first,p.name_last,p.name_2,b.bill_date,b.bill_number,b.total

    $request = $db->Execute($sql);
//10009125
    //10072408
    echo '<table width=100% height=14>';

    $sql2 = "select d.accno,d.name,c.id,d.address1,d.address2,d.phone from care_ke_debtors d left join care_tz_company c
        ON d.accno=c.accNo where c.id is not NULL and c.accno='$accno'";

    $request2 = $db->Execute($sql2);
    $patient_data = $request2->FetchRow();

    echo "<tr>";
    echo "<td valign=top colspan=2><b>Account No: </b>" . $patient_data ['accno'] . "<br>"
    . "<b>Name:</b> " . strtoupper($patient_data ['name']) . "<br>"
    . "<b>Address:</b> " . strtoupper($patient_data ['address1']) . "<br>"
    . "<b>Town:</b> " . strtoupper($patient_data ['address2']) . "<br></td>";

    echo "<td valign=top colspan=2><b>STATEMENT OF ACCOUNT</b></td>";

    $sql = "SELECT * FROM care_ke_invoice";
    $global_result = $db->Execute($sql);
    if ($global_result) {
        while ($data_result = $global_result->FetchRow()) {
            echo "<td colspan=2>" . $data_result ['CompanyName'] . "<br>"
            . $data_result ['Address'] . "<br>"
            . $data_result ['Town'] . "<br>"
            . $data_result ['Postal'] . "<br>"
            . $data_result ['Tel'] . "<br>"
            . $data_result ['new_bill_nr'];
        }
        $global_config_ok = 1;
    } else {
        $global_config_ok = 0;
    }


    echo '</td></tr><tr bgcolor=#6699cc>
                    <td align="center">IP-OP</td>
                    <td align="center">pid</td>
                    <td align="center">Names</td>
                    <td align="center">Bill Date</td>
                    <td align="center">Bill Number</td>
                    <td align="center">DB</td>
                    <td align="center">CD</td>
                    <td align="center">Running Total</td>
                    <td>&nbsp;</td>
                 </tr>';
    $bg = '';
    $lastMonth = getLastMonth($dt1);

    $sql1 = "select sum(total) as totalPayments from care_ke_receipts 
                where ledger='DB' and patient='$accno' AND currdate<='$lastMonth'";
    $request1 = $db->Execute($sql1);
    $row1 = $request1->FetchRow();
    $totalPayments = $row1[0];
   if($debug) echo $sql1;
    
    $sql2 = "select sum(total) as balance_bf from care_ke_billing b 
            left join care_person p on b.pid=p.pid 
            left join care_tz_company c on p.insurance_id=c.id
            where c.accno='$accno' and b.bill_date<='$lastMonth'";
      if($debug) echo $sql2;
    $request2 = $db->Execute($sql2);
    $row2 = $request2->FetchRow();
    $balanceBf = intval($row2[0] - $totalPayments);
    echo "<tr><td></td>
                    <td colspan=2><b>Balance bf </b></td>
                    <td><b>$lastMonth </b></td>
                    <td>&nbsp;</td>
                    <td><b>" . number_format($balanceBf, 2) . " </b></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                 </tr>";
    while ($row = $request->FetchRow()) {
        if ($bg == "silver")
            $bg = "white";
        else
            $bg = "silver";
        $total = intval($row[total] + $total);
        if ($row[pid] <> '' ? $pid = $row[pid] : $pid = $row[accno]);
        if($row['IP-OP']==2){
            $adm="OP";
        }else{
             $adm="IP";
        }
        
       //---------------------------------------------------------------------------
        //get last months amount for this bill number
        $dts = new DateTime($lastMonth);
        $dt4 = $dts->format("Y-m-d");
        $threeMonthsAgo=substractDate($dt4,60);
        
         $sqlS="select sum(total) as total from care_ke_billing where pid=$pid 
                and bill_number=$row[bill_number] and bill_date
        between '$threeMonthsAgo' and '$lastMonth' and `ip-op`=1 and service_type<>'Payment'";
        if($requestS = $db->Execute($sqlS)){
         $rowS = $requestS->FetchRow();
            $extTotal=$rowS[0];
         }
         
         if($debug) echo $sqlS;
       //----------------------------------------------------------------------------
        
        echo '<tr bgcolor=' . $bg . ' height=16>
                    <td>'.$adm.'</td>
                    <td>' . $pid . '</td>
                    <td><a href="#" onclick="getInvoice(' . $row[pid] . ',' . $row[bill_number] . ',' . $dt1.','.$dt2. ')">' . $row[name_first] . ' ' . $row[name_last] . ' ' . $row[name_2] . '</a></td>
                    <td>' . $row[bill_date] . '</td>
                    <td>' . $row[bill_number] . '</td>
                    <td>';
        $total = $row[total];

        $DB = $row['debit']+$extTotal;
        if ($DB <> '') {
            $DB = number_format($DB, 2);
        } else {
            $DB = 0;
        }
        echo $DB;
        echo '</td><td>';


        $CD = $row['credit'];

        if ($CD <> '') {
            $CD = number_format($CD, 2);
        } else {
            $CD = 0;
        }
        echo $CD;
        echo '</td>';
        $runBal = $runBal + ($row["debit"]-$row["credit"]);
        echo '<td>' . number_format($runBal, 2) . '</td>    
                 <td><button id="preview" 
                 onclick="getInvoice(' . $row[pid] . ',' . $row[bill_number]. ',' . $dt1.','.$dt2. ')">
                     Preview Invoice</button></td>
             </tr>';
        $rowbg = 'white';
        $totalBill = $totalBill + $DB;
        $totalpaid = $totalpaid + $CD;
    }
    $totalBill = $totalBill + $balanceBf;

    //=====================================================================
    //get all Payments
    $sql4 = "select patient,`name`,payer,ref_no,currdate,total from care_ke_receipts 
where ledger='DB' and patient='$accno' and currdate between '$dt1' and '$dt2'";
   if($debug) echo $sql4;
    $request4 = $db->Execute($sql4);
    $totalPayments2=0;
     
    while($row4 = $request4->FetchRow()){
        
    echo "<tr>
                   
                    <td>$row4[patient]</td>
                    <td>$row4[patient]</td>
                    <td>$row4[name]</td>
                    <td>$row4[currdate] </td>
                    <td>$row4[ref_no]</td>
                    <td>&nbsp;</td>
                    <td>".number_format($row4[total],2)."</td>
                    <td>&nbsp;</td>
                 </tr>";
    $totalPayments2=$totalPayments2+$row4[total];
    }
    //=====================================================================
    $totalpaid=$totalpaid+$totalPayments2;
    
    $bal = intval($totalBill - $totalpaid);
    echo "<tr><td colspan=5 align=right><b>Total:  </b></td>";
    echo "<td><b>" . number_format($totalBill, 2) . "</b></td>";
    echo "<td><b>" . number_format($totalpaid, 2) . "</b></td>";
    echo "<td><b>" . number_format($bal, 2) . "</b></td><td></td>";
    echo '</table>';
}

function getTotals($sign, $pid) {
    global $db;

    $sql = "select sum(b.total) as total from care_ke_billing b  left join care_encounter e
    on b.pid=e.pid where b.service_type $sign'Payment' and 
    b.pid=$pid";
//    echo $sql;
    $request = $db->Execute($sql);
    if ($row = $request->FetchRow()) {
        return $row[0];
    } else {
        return '0';
    }
}

function exportExcel() {
    global $db;
    $accno = $_REQUEST[acc1];
    $date1 = $_REQUEST[date1];
    $date2 = $_REQUEST[date2];

    $sql = "SELECT p.pid,IF(p.name_first<>'',p.name_first,c.name) AS name_first,p.name_last, p.name_2,b.bill_date,b.bill_number,
IF(b.service_type<>'Payment',SUM(b.total),0) AS debit,IF(b.service_type='Payment',SUM(b.total),0) AS credit,b.service_type,c.accno FROM care_ke_billing b INNER JOIN care_person p ON b.pid=p.pid 
INNER JOIN care_tz_company c ON c.id=p.insurance_id INNER JOIN care_ke_debtors d ON d.accno=c.accno 
WHERE d.accno='$accno' and b.service_type<>'NHIF'";
    if ($date1) {
        $date = new DateTime($date1);
        $dt1 = $date->format("Y-m-d");
    } else {
        $dt1 = "";
    }
    if ($date2) {
        $date = new DateTime($date2);
        $dt2 = $date->format("Y-m-d");
    } else {
        $dt2 = "";
    }

    if ($dt1 <> "" && $dt2 <> "") {
        $sql = $sql . " and b.bill_date between '$dt1' and '$dt2' ";
    } else if ($dt1 <> '' && $dt2 == '') {
        $sql = $sql . " and b.bill_date = '$dt1'";
    } else {
        $sql = $sql . " and b.bill_date<=now()";
    }
    $sql = $sql . " GROUP BY b.bill_number ORDER BY b.bill_date asc";

//    echo $sql;
    //p.pid,p.name_first,p.name_last,p.name_2,b.bill_date,b.bill_number,b.total

    $request = $db->Execute($sql);

    echo '<table width=100% height=14><tr bgcolor=#6699cc>
                    <td align="center">pid</td>
                    <td align="center">Names</td>
                    <td align="center">Bill Date</td>
                    <td align="center">Bill Number</td>
                    <td align="center">Total</td>
                    <td align="center">Running Total</td>
                 </tr>';
    $bg = '';
//        $total='';
    while ($row = $request->FetchRow()) {
        if ($bg == "silver")
            $bg = "white";
        else
            $bg = "silver";
        $total = intval($row[total] + $total);
        echo '<tr bgcolor=' . $bg . ' height=16>
                    <td>' . $row[pid] . '</td>
                    <td>' . $row[name_first] . ' ' . $row[name_last] . ' ' . $row[name_2] . '</td>
                    <td>' . $row[bill_date] . '</td>
                    <td>' . $row[bill_number] . '</td>';

        $totalBill = getTotals('<>', $row[pid]);
        $totalDepo = getTotals('=', $row[pid]);
        $totalBal = intval($totalBill - $totalDepo);
        $runBal = $runBal + $totalBal;
        echo '<td>' . number_format($totalBal, 2) . '</td>
                    <td>' . number_format($runBal, 2) . '</td>    
                   
             </tr>';

        $rowbg = 'white';
    }
    echo '</table>';
}
?>
<!--
<html><head><title></title></head>
    <body style="background-color:dfe ">
        
        
    </body>
</html>-->