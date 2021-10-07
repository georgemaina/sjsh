<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require('roots.php');
require($root_path . 'include/inc_environment_global.php');
$cashpoint = $_REQUEST['cashpoint'];
$shiftNo = $_REQUEST['shiftNo'];
$cashier = $_SESSION['sess_login_username'];
$reportid = $_REQUEST['reportid'];
$date1=$_REQUEST[date1];
$date2=$_REQUEST[date2];
printform($cashpoint, $shiftNo, $cashier, $reportid,$date1,$date2);

function printform($cashpoint, $shiftNo, $cashier, $reportid,$date1,$date2) {

    echo ' <div class=pgtitle> Shift Report for ' . $cashpoint . ' and Shift No ' . $shiftNo . '</div>';
    echo '<table border="0" width="100%">
        <tr>
           <td align=right>';
    $strDate1= new DateTime($date1);
    $sDate1 = $strDate1->format("Y-m-d");

    $strDate2= new DateTime($date2);
    $sDate2 = $strDate2->format("Y-m-d");
    if ($reportid == 'detail') {
        echo getReceiptsDetail($cashpoint, $shiftNo, $cashier);
    } else if ($reportid == 'summary') {
        echo getReceiptsSummary($cashpoint, $shiftNo, $cashier);
    } else if ($reportid == 'summaryByItem') {
        echo getReceiptsSummaryByItem($cashpoint, $shiftNo, $cashier);
    } else if ($reportid == 'payments') {
        echo getPayments($cashpoint, $shiftNo, $cashier,$sDate1,$sDate2);
    } else if ($reportid == 'payments2') {
        echo getPayments2($cashpoint,$sDate1,$sDate2);
    } else if ($reportid == 'collection') {
        echo getCollectionsSummary($cashpoint, $shiftNo, $cashier,$sDate1,$sDate2);
    }else if ($reportid == 'breakdown') {
        echo getShiftBreakdown($cashpoint, $shiftNo, $cashier);
    }else if ($reportid == 'revenue') {
        echo getRevenueBreakdown($sDate1,$sDate2);
    }else if ($reportid == 'revenue2') {
        echo getRevenue2Breakdown($sDate1,$sDate2);
    }else if ($reportid == 'credit') {
        echo getCreditBreakdown($sDate1,$sDate2);
    }

    echo " </td>
  </tr></table>";
}

function getReceiptsDetail($cashpoint, $shiftNo, $cashier) {
    global $db;
    $sql = "SELECT * FROM care_ke_invoice";
    $global_result = $db->Execute($sql);
    if ($global_result) {
        while ($data_result = $global_result->FetchRow()) {
            $company = $data_result ['CompanyName'];
            $address = $data_result ['Address'];
            $town = $data_result ['Town'];
            $postal = $data_result ['Postal'];
            $tel = 'Phone: ' . $data_result ['Tel'];
            $invoice_no = $data_result ['new_bill_nr'];
        }
        $global_config_ok = 1;
    } else {
        $global_config_ok = 0;
    }

    $sql = "SELECT b.Shift_no,b.ref_no,b.`type`,b.input_time,b.patient,b.name,b.rev_code,b.rev_desc,
b.proc_code,b.prec_desc,b.payer,b.location,b.pay_mode,b.amount,b.proc_qty,b.total,a.start_date,a.start_time,b.currdate,
b.pay_mode ,b.towards,b.`cash`,b.`mpesa` 
FROM care_ke_receipts b JOIN care_ke_shifts a  ON b.shift_no=a.shift_no 
WHERE b.cash_point='$cashpoint' and b.shift_no='$shiftNo' group by sale_id";


//echo $sql;
    echo '<table width="100%"><tbody>';
    $sql1 = 'select * from care_ke_shifts where cash_point="' . $cashpoint . '" and shift_no=' . $shiftNo;
//echo $sql1;
    $result1 = $db->execute($sql1);
    $row = $result1->FetchRow();

    echo '
                     <tr>
                        <td align=left colspan=5>' . $company . '<br>' . $address . '<br>' . $town . ' - ' . $postal . '
                            <br>' . $tel . '</td>
                        <td colspan=3><center><b>COLLECTION DETAIL REPORT</b></center></td>
                        <td colspan=1><b>Start Date:</b> ' . $row[start_date] . ' ' . $row[start_time] . '<br>
                            <b>End Date:</b> ' . $row[end_date] . ' ' . $row[end_time] . '<br>
                            <b>Cash Point:</b> ' . $row[end_date] . '
                            <br> <b>Shift No:</b> ' . $shiftNo . '<br>
                             <b>Casher:</b> ' . $row[cashier] . '</td>
                     </tr>
                    <tr class="prow">
                        <td>Receipt No</td>
                        <td>Type</td>
                        <td>Date</td>
                        <td>Time</td>
                        <td>Patient ID</td>
                        <td>Patient Names</td>
                         <td>Pay Mode</td>
                        <td>Revenue code</td>
                        <td>Revenue Desc</td>
                        <td>Unit Price</td>
                        <td>Quantity</td>
                        <td>Amount</td>
                     </tr>';
    $rowbg = 'white';
    $casTotal=0;
    $chqTotal=0;
    $mpesaTotal=0;
    if ($result = $db->Execute($sql)) {
        while ($row = $result->FetchRow()) {
            if ($bg == "silver")
                $bg = "white";
            else
                $bg = "silver";
            echo '<tr bgcolor=' . $bg . ' class="shiftContent">
                        <td>' . $row['ref_no'] . '</td>
                        <td>' . $row['type'] . '</td>
                        <td>' . $row['currdate'] . '</td>
                        <td>' . $row['input_time'] . '</td>
                        <td>' . $row['patient'] . '</td>
                        <td>' . $row['payer'] . '</td>
                        <td>' . $row['pay_mode'] . '</td>
                        <td>' . $row['proc_code'] . '</td>
                        <td>';
                if($row['rev_code']=='GL'){
                    echo $row['towards'];
                }else{
                    echo $row['prec_desc'];
                }



            echo '<td align="right">' . number_format($row['amount'],2) . '</td>
                  <td align="center">' . $row['proc_qty'] . '</td>
                  <td align=right>';
                        if($row['proc_qty']==0){
                            $row['proc_qty']=1;
                        }
                $cash=  $row['amount']*$row['proc_qty'];
                $casTotal=$casTotal+$row['total'];
                 echo 'Ksh ' .$cash;

            echo '</td></tr>';
            $rowbg = 'white';
        }
    }else {
        echo 'error ';
    }
    
    echo "<tr><td colspan=11 align=right><b>Totals </td>
                <td align=right><b>" . number_format($casTotal, 2) . "</b></td><tr>";


    // report summary grouped by revenue codesSELECT sum(total) as total FROM care_ke_receipts WHERE cash_point='$cashpoint' and shift_no='$shiftNo'";
    echo '<tr><td colspan=9><hr></td><tr><td colspan=9>
                  <table>
                      <tr class=prow><td colspan=3>Breakdown of Cash Sale</td></tr>
                      <tr class="prow">
                                <td>REVENUE CODES</td>
                                <td>CASH</td>
								<td>MPESA</td>
                    </tr>';
	$sql="SELECT if(r.rev_desc<>'',r.rev_desc,r.prec_desc) as Description,r.pay_mode, sum(IF(r.pay_mode='CAS', r.total,'')) AS Cash,
		sum(IF(r.pay_mode='MPESA', r.total,'')) AS Mpesa FROM care_ke_receipts r 
		WHERE r.cash_point='$cashpoint' and r.shift_no='$shiftNo'
		group by Description";
    //$sql = "SELECT r.rev_code,r.rev_desc,r.pay_mode, SUM(r.total) AS total,r.prec_desc FROM care_ke_receipts r 
          //  WHERE r.cash_point='$cashpoint' and r.shift_no='$shiftNo'
                  //   GROUP BY r.rev_desc";
            //echo $sql;
    $result = $db->Execute($sql);
    $mTotal=0;
	$cTotal=0;
    while ($row = $result->FetchRow($result)) {
        if ($bg == "silver")
            $bg = "white";
        else
            $bg = "silver";
        echo '<tr bgcolor=' . $bg . '>';
			   echo ' <td>' . strtoupper($row['Description']) . '</td>
				<td>' . $row['Cash'] .'</td>
				<td>' . $row['Mpesa'] . '</td>
				</tr>';
        $rowbg = 'white';
		$mTotal+=$row['Mpesa'];
	    $cTotal+=$row['Cash'];
        
    }
	$totalt = intval( $cTotal + $mTotal);
    echo '<tr><td><b>Total:</b></td><td><b>' . number_format($cTotal,2) .'</td><td><b>'.number_format($mTotal,2) . '</b></td></tr>';

    echo '<tr bgcolor=' . $bg . '>
                                <td></td>
                                <td>TOTAL:</td>
                                <td colspan=2><b>' . number_format($totalt, 2) . '</b></td></tr>';

    echo "</table></td></tr>";
    echo '</tbody></table>';
}

function getShiftBreakdown($cashpoint, $shiftNo, $cashier){
    global $db;
    $debug=false;

    echo '<table width="100%">
                      <tr class=prow><td colspan=3>Breakdown of Cash Sale</td></tr>
                      <tr class="prow">
                                <td>rev code</td>
                                <td>pay mode</td>
                                <td>amount</td>
                    </tr>';
    $sql = "SELECT r.rev_code,r.rev_desc,r.pay_mode, SUM(r.total) AS total,r.prec_desc FROM care_ke_receipts r
            WHERE r.cash_point='$cashpoint' and r.shift_no='$shiftNo'
                     GROUP BY r.rev_desc";
//            echo $sql;
    $result = $db->Execute($sql);
    $totalt = 0;
    while ($row = $result->FetchRow($result)) {
        if ($bg == "silver")
            $bg = "white";
        else
            $bg = "silver";
        echo '<tr bgcolor=' . $bg . '>';
        if($row[rev_desc]<>""){
            $desc=$row[rev_desc];
        }else{
            $desc=$row[rev_code].'-'.$row[prec_desc];
        }
        echo ' <td>' . strtoupper($desc) . '</td>
                                <td>' . $row[pay_mode] . '</td>
                                <td>' . $row[total]. '</td>
                                </tr>';
        $rowbg = 'white';
        $totalt = intval($totalt + $row[total]);
    }
    echo '<tr><td colspan=2><b>Total:</b></td><td><b>' . $casTotal + $chqTotal + $othesTotal . '</b></td></tr>';

    echo '<tr bgcolor=' . $bg . '>
                                <td></td>
                                <td>TOTAL:</td>
                                <td><b>' . number_format($totalt, 2) . '</b></td></tr>';

    echo "</table>";

}

function getReceiptsSummary($cashpoint, $shiftNo, $cashier) {
    global $db;
    $sql = "SELECT * FROM care_ke_invoice";
    $global_result = $db->Execute($sql);
    if ($global_result) {
        while ($data_result = $global_result->FetchRow()) {
            $company = $data_result ['CompanyName'];
            $address = $data_result ['Address'];
            $town = $data_result ['Town'];
            $postal = $data_result ['Postal'];
            $tel = 'Phone: ' . $data_result ['Tel'];
            $invoice_no = $data_result ['new_bill_nr'];
        }
        $global_config_ok = 1;
    } else {
        $global_config_ok = 0;
    }

    $sql = "SELECT b.ref_no,b.patient,b.name,b.payer,b.pay_mode,SUM(b.total) AS total,b.cash,
                b.mpesa,b.visa,b.currdate,b.input_Time,b.balance from care_ke_receipts b
            WHERE b.cash_point='$cashpoint' AND b.shift_no='$shiftNo' GROUP BY ref_no ORDER BY patient asc";


//echo $sql;
    echo '<table width="100%"><tbody>';
    $sql1 = 'select * from care_ke_shifts where cash_point="' . $cashpoint . '" and shift_no=' . $shiftNo;
//echo $sql1;
    $result1 = $db->execute($sql1);
    $row = $result1->FetchRow();

    echo '
                     <tr>
                        <td align=left>' . $company . '<br>' . $address . '<br>' . $town . ' - ' . $postal . '
                            <br>' . $tel . '</td>
                        <td colspan=5 align="center"><b>COLLECTION SUMMARY BBY PATIENT REPORT</b></td>
                        <td colspan=2><b>Start Date:</b> ' . $row[start_date] . ' ' . $row[start_time] . '<br>
                            <b>End Date:</b> ' . $row[end_date] . ' ' . $row[end_time] . '<br>
                            <b>Cash Point:</b> ' . $row[end_date] . '
                            <br> <b>Shift No:</b> ' . $shiftNo . '<br>
                             <b>Casher:</b> ' . $row[cashier] . '</td>
                     </tr>
                    <tr class="prow">
                        <td>Receipt No</td>
                        <td>Patient ID</td>
                        <td>Name</td>
                        <td>Amount</td>
                        <td>Cash Received</td>
                        <td>Mpesa Received</td>
                        <td>Cheque Received</td>
                        <td>Visa Amount</td>
                     </tr>';
    $rowbg = 'white';

    $totals=0;
    $casTotal=0;
    $mpesaTotal=0;
    $visaTotal=0;
    $chqTotal=0;
    if ($result = $db->Execute($sql)) {
        while ($row = $result->FetchRow()) {
            if ($bg == "silver")
                $bg = "white";
            else
                $bg = "silver";
            echo '<tr bgcolor=' . $bg . ' class="shiftContent">
                        <td>' . $row['ref_no'] . '</td>
                        <td>' . $row['patient'] . '</td>
                        <td>' . $row['name'] . '</td>
                        <td align=right>'.$row['total'].'</td>
                        <td align=right>';
                         if($row['balance']>0){
                             echo $row['cash']-$row['balance'];
                         }else{
                             echo $row['cash'];
                         }

                    echo '</td>
                        <td align=right>'.$row['mpesa'].'</td>';
                            if($row[pay_mode]=='CHQ'){
                                $amount=$row['total'];
                            }else{
                                $amount='';
                            }
                        echo '<td align=right>'.$amount.'</td>';
                        echo'     <td align=right>'.$row['visa'].'</td>
                     </tr>';
            $rowbg = 'white';
            $totals=$totals+$row['total'];
            $casTotal=$casTotal+$row['cash'];
            $mpesaTotal=$mpesaTotal+$row['mpesa'];
            $visaTotal=$visaTotal+$row['visa'];
            $chqTotal=$chqTotal+$amount;

        }
    }else {
        echo 'error ';
    }

    $total = intval($casTotal + $mpesaTotal + $visaTotal);
    echo "<tr><td colspan=3 align=right><b>Sub Totals </td>
                <td align=right><b>" . number_format($totals, 2) . "</b></td>
                <td align=right><b>" . number_format($casTotal, 2) . "</b></td>
                <td align=right><b>" . number_format($mpesaTotal, 2) . "</b></td>
                <td align=right><b>" . number_format($chqTotal, 2) . "</b></td>
                <td align=right><b>" . number_format($visaTotal, 2) . "</b></td><tr>";

    $totals=$casTotal+$mpesaTotal+$visaTotal+$chqTotal;
    echo "<tr><td colspan=4 align=right><b>Totals </td>
                <td align=right><b>" . number_format($totals, 2) . "</b></td>
                <td colspan='2'></td><tr>";

    echo '<tr><td colspan=9><hr></td><tr><td colspan=9>
                  <table>
                      <tr class=prow><td colspan=3>Breakdown of Cash Sale</td></tr>
                      <tr class="prow">
                                <td>rev code</td>
                                <td>pay mode</td>
                                <td>amount</td>
                    </tr>';
    $sql = "SELECT r.rev_code,r.rev_desc,r.pay_mode, SUM(r.total) AS total,r.prec_desc FROM care_ke_receipts r 
            WHERE r.cash_point='$cashpoint' and r.shift_no='$shiftNo'
                     GROUP BY r.rev_desc";
//            echo $sql;
    $result = $db->Execute($sql);
    $totalt = 0;
    while ($row = $result->FetchRow($result)) {
        if ($bg == "silver")
            $bg = "white";
        else
            $bg = "silver";
        
        if($row[rev_desc]<>""){
            $desc=$row[rev_desc];
       }else{
           $desc=$row[rev_code].' - '.$row[prec_desc];
       }
        echo '<tr bgcolor=' . $bg . '>
                                <td>' . strtoupper($desc) . '</td>
                                <td>' . $row[pay_mode] . '</td>
                                <td>' . $row[total] . '</td>
                                </tr>';
        $rowbg = 'white';
        $totalt = intval($totalt + $row[total]);
    }
    echo '<tr><td colspan=2><b>Total:</b></td><td><b>' . $totalt . '</b></td></tr>';

    echo '<tr bgcolor=' . $bg . '>
                                <td></td>
                                <td>TOTAL:</td>
                                <td><b>' . number_format($totalt, 2) . '</b></td></tr>';

    echo "</table></td></tr>";
    echo '</tbody></table>';
}

function getReceiptsSummaryByItem($cashpoint, $shiftNo, $cashier) {
    global $db;
    $sql = "SELECT * FROM care_ke_invoice";
    $global_result = $db->Execute($sql);
    if ($global_result) {
        while ($data_result = $global_result->FetchRow()) {
            $company = $data_result ['CompanyName'];
            $address = $data_result ['Address'];
            $town = $data_result ['Town'];
            $postal = $data_result ['Postal'];
            $tel = 'Phone: ' . $data_result ['Tel'];
            $invoice_no = $data_result ['new_bill_nr'];
        }
        $global_config_ok = 1;
    } else {
        $global_config_ok = 0;
    }
  
    

//echo $sql2;
    echo '<table width="100%"><tbody>';
    $sql1 = 'select * from care_ke_shifts where cash_point="' . $cashpoint . '" and shift_no=' . $shiftNo;
//echo $sql1;
    $result1 = $db->execute($sql1);
    $row = $result1->FetchRow();

    echo '
                     <tr>
                        <td align=left colspan=4>' . $company . '<br>' . $address . '<br>' . $town . ' - ' . $postal . '
                            <br>' . $tel . '</td>
                        <td colspan=3><center><b>COLLECTION SUMMARY REPORT BY ITEM</b></center></td>
                        <td colspan=14><b>Start Date:</b> ' . $row[start_date] . ' ' . $row[start_time] . '<br>
                            <b>End Date:</b> ' . $row[end_date] . ' ' . $row[end_time] . '<br>
                            <b>Cash Point:</b> ' . $row[end_date] . '
                            <br> <b>Shift No:</b> ' . $shiftNo . '<br>
                             <b>Casher:</b> ' . $row[cashier] . '</td>
                     </tr>
                    <tr class="prow">
                        <td>Date</td>
                        <td>Payment Mode</td>
                        <td>Revenue Code</td>
                        <td>Revenue Desc</td>
                        <td>Item ID</td>
                        <td>Description</td>
                        <td>Unit Price</td>
                        <td>Quantity</td>
                        <td>Amount</td>
                        <td>GL Account</td>
                     </tr>';
    $rowbg = 'white';
    
    $sql2="SELECT currdate,pay_mode,rev_code,rev_desc,prec_desc AS Description
        ,Amount AS `Unit_Price`,SUM(Proc_qty) AS Quantity,SUM(total) as `TotalAmount`,proc_code,gl_desc,
        total AS Amount FROM care_ke_receipts WHERE cash_point='$cashpoint' AND shift_no='$shiftNo'
         GROUP BY proc_code";

    if ($result = $db->Execute($sql2)) {
        $casTotal=0;
        while ($row = $result->FetchRow()) {
            if ($bg == "silver")
                $bg = "white";
            else
                $bg = "silver";
            echo '<tr bgcolor=' . $bg . ' class="shiftContent">
                        <td>' . $row['currdate'] . '</td>
                        <td>' . $row['pay_mode'].'</td>
                        <td>' . $row['rev_code'].'</td>
                        <td>' . $row['rev_desc'].'</td>
                        <td>' . $row['proc_code'].'</td>
                        <td>' . $row['Description'].'</td>
                        <td align=right>' . $row['Unit_Price'].'</td>
                        <td align=center>' . $row['Quantity'].'</td>
                        <td align=right>' . $row['TotalAmount'].'</td>
                        <td>' . $row['gl_desc'].'</td>
                     </tr>';
            $rowbg = 'white';
            $casTotal=$casTotal+ $row['TotalAmount'];
        }
    }else {
        echo 'error ';
    }
   
    
    $total = intval($casTotal);
    echo "<tr><td colspan=8 align=right><b>Sub Totals </td>
                <td align=right><b>" . number_format($casTotal, 2) . "</b></td>
                <tr>";

    // report summary grouped by revenue codesSELECT sum(total) as total FROM care_ke_receipts WHERE cash_point='$cashpoint' and shift_no='$shiftNo'";
    echo '</tbody></table>';
}

function getCollectionsSummary($cashpoint, $shiftNo, $cashier,$date1,$date2) {
    global $db;
    $sql = "SELECT * FROM care_ke_invoice";
    $global_result = $db->Execute($sql);
    if ($global_result) {
        while ($data_result = $global_result->FetchRow()) {
            $company = $data_result ['CompanyName'];
            $address = $data_result ['Address'];
            $town = $data_result ['Town'];
            $postal = $data_result ['Postal'];
            $tel = 'Phone: ' . $data_result ['Tel'];
            $invoice_no = $data_result ['new_bill_nr'];
        }
        $global_config_ok = 1;
    } else {
        $global_config_ok = 0;
    }

    $sql = "SELECT r.Cash_point,r.Shift_no,r.type,s.`start_date`,s.`start_time`,s.`end_date`,s.`end_time`,username,pay_mode,SUM(total) AS total FROM care_ke_receipts r 
                LEFT JOIN care_ke_shifts s ON r.`Shift_no`=s.shift_no AND r.`cash_point`=s.cash_point
                WHERE s.`start_date` BETWEEN '$date1' AND '$date2'
                GROUP BY s.shift_no,s.cash_point
                ORDER BY s.`shift_no` DESC";

//    echo $sql;


    echo '
                     <tr>
                        <td align=left>' . $company . '<br>' . $address . '<br>' . $town . ' - ' . $postal . '
                            <br>' . $tel . '</td>
                        <td colspan=4><center><b>CASH COLLECTION SUMMARY REPORT</b></center></td>
                        <td colspan=2></td>
                     </tr>
                    <tr class="prow">
                        <td>CashPoint</td>
                        <td>Shift No</td>
                        <td>Receipt Type</td>
                        <td>Cashier</td>
                        <td>Start Date</td>
                        <td>Start Time</td>
                        <td>End Date</td>
                        <td>End Time</td>
                        <td>Amount</td>
                        <td>Cheques</td>
                        <td>Others</td>
                     </tr>';
    $rowbg = 'white';
    $amount=0;
    $othesTotal=0;
    $chqTotal=0;
    if ($result = $db->Execute($sql)) {
        while ($row = $result->FetchRow()) {
            if ($bg == "silver")
                $bg = "white";
            else
                $bg = "silver";
            
            if($row[type]=='RC'){
                $rctype='Receipt';
            }else if($row[type]=='RCJ'){
                $rctype='Receipt Adjustment';
            }
            echo '<tr bgcolor=' . $bg . ' class="shiftContent">
                        <td>' . $row['Cash_point'] . '</td>
                        <td>' . $row['Shift_no'] . '</td>
                        <td>' . $rctype .'</td>
                        <td>' . $row['username'] . '</td>
                        <td>' . $row['start_date'] . '</td>
                        <td>' . $row['start_time'] . '</td>
                        <td>' . $row['end_date'] . '</td>
                        <td>' . $row['end_time'] . '</td>
                        <td align=right>';
            if (strtoupper($row['pay_mode']) == 'CAS'){
                echo 'Ksh ' . number_format($row['total'],2);
                $amount=$amount+$row['total'];
            }else{
                echo "";
            }

            echo '</td><td align=right>';
            if ( strtoupper($row['pay_mode']) == 'CHQ'){
                echo 'Ksh ' . number_format($row['total'],2);
                $chqTotal=$chqTotal+$row['total'];
            }else{
                echo "";
            }
            echo '</td><td align=right>';
            if (strtoupper($row['pay_mode']) == 'MPESA' || strtoupper($row['pay_mode']) == 'VISA' || strtoupper($row['pay_mode']) == 'EFT' || $row['pay_mode'] == ''){
                echo 'Ksh ' . number_format($row['total'],2);
                $othesTotal=$othesTotal+$row['total'];
            }else{
                echo "";
            }
            echo '</td>
                     </tr>';
            $rowbg = 'white';
        }
    }else {
        echo 'error ';
    }
   

    $total = intval($amount + $chqTotal + $othesTotal);
    echo "<tr><td colspan=8 align=right><b>Sub Totals </td>
                <td align=right><b>" . number_format($amount, 2) . "</b></td>
                <td align=right><b>" . number_format($chqTotal, 2) . "</b></td>
                <td align=right><b>" . number_format($othesTotal, 2) . "</b></td><tr>";
    echo "<tr><td colspan=8 align=right><b>Totals </td>
                <td align=right><b>" . number_format($total, 2) . "</b></td>
                <td align=right><b></b></td>
                <td align=right><b></b></td><tr>";


    echo "</table></td></tr>";
    echo '</tbody></table>';
}

function getPayments($cashpoint, $shiftNo, $cashier,$date1,$date2) {
    global $db;
    $debug=false;
    $sql = "SELECT * FROM care_ke_invoice";
    $global_result = $db->Execute($sql);
    if ($global_result) {
        while ($data_result = $global_result->FetchRow()) {
            $company = $data_result ['CompanyName'];
            $address = $data_result ['Address'];
            $town = $data_result ['Town'];
            $postal = $data_result ['Postal'];
            $tel = 'Phone: ' . $data_result ['Tel'];
            $invoice_no = $data_result ['new_bill_nr'];
        }
        $global_config_ok = 1;
    } else {
        $global_config_ok = 0;
    }

    $sql = "SELECT cash_point,voucher_no,pay_mode,pdate,gl_acc,gl_desc,cheque_no,payee,toward,ledger,ledger_code,ledger_desc,control FROM care_ke_payments
                WHERE cash_point='$cashpoint' and pdate between '$date1' and '$date2'
                ORDER BY voucher_no DESC";
        if($debug) echo $sql;

    echo '<tr>
                        <td align=left>' . $company . '<br>' . $address . '<br>' . $town . ' - ' . $postal . '
                            <br>' . $tel . '</td>
                        <td colspan=4><center><b>PAYMENTS REPORT</b></center></td>
                        <td colspan=2></td>
                     </tr>
                    <tr class="prow">
                        <td>Voucher No</td>
                        <td>Payment Mode</td>
                        <td>Payment Date</td>
                        <td>Gl Account</td>
                        <td>GL Desc</td>
                        <td>Payee</td>
                        <td>Towards</td>
                        <td>Ledger</td>
                        <td>Ledger Code</td>
                        <td>Ledger Desc</td>
                        <td>Amount</td>
                     </tr>';
    $rowbg = 'white';
    $amount=0;
    if ($result = $db->Execute($sql)) {
        while ($row = $result->FetchRow()) {
            if ($bg == "silver")
                $bg = "white";
            else
                $bg = "silver";

            echo '<tr bgcolor=' . $bg . ' class="shiftContent">
                        <td>' . $row['voucher_no'] . '</td>
                        <td>' . $row['pay_mode'] . '</td>
                        <td>' . $row['pdate'] .'</td>
                        <td>' . $row['gl_acc'] .'</td>
                        <td>' . $row['gl_desc'] . '</td>
                        <td>' . $row['payee'] . '</td>
                        <td>' . $row['toward'] . '</td>
                        <td>' . $row['ledger'] . '</td>
                        <td>' . $row['ledger_code'] . '</td>
                        <td>' . $row['ledger_desc'] . '</td>
                        <td> Ksh ' . number_format($row['control'],2) . '</td>';
            $amount=$amount+$row['control'];
            echo '</tr>';
            $rowbg = 'white';
        }
    }else {
        echo 'No Records found ';
    }


    $total = intval($amount + $chqTotal + $othesTotal);
    echo "<tr><td colspan=9 align=right><b>Totals </td>
                <td align=right><b>" . number_format($amount, 2) . "</b></td><tr>";
    echo "</table></td></tr>";
    echo '</tbody></table>';
}

function getPayments2($cashpoint,$date1,$date2) {
    global $db;
    $debug=false;
    $sql = "SELECT * FROM care_ke_invoice";
    $global_result = $db->Execute($sql);
    if ($global_result) {
        while ($data_result = $global_result->FetchRow()) {
            $company = $data_result ['CompanyName'];
            $address = $data_result ['Address'];
            $town = $data_result ['Town'];
            $postal = $data_result ['Postal'];
            $tel = 'Phone: ' . $data_result ['Tel'];
            $invoice_no = $data_result ['new_bill_nr'];
        }
        $global_config_ok = 1;
    } else {
        $global_config_ok = 0;
    }

    $sql = "SELECT  CASH_POINT,PDATE,Voucher_No,cheque_no,ledger_code,ledger_desc,Amount FROM CARE_KE_PAYMENTS 
            WHERE CASH_POINT='$cashpoint' AND PDATE BETWEEN '$date1' AND '$date2'";

    if($debug) echo $sql;

    echo '<tr>
                        <td align=left colspan="2">' . $company . '<br>' . $address . '<br>' . $town . ' - ' . $postal . '
                            <br>' . $tel . '</td>
                        <td colspan="3"><center><b>PAYMENTS SUMMARY REPORT</b></center></td>
                        <td>&nbsp;</td>
                     </tr>
                    <tr class="prow">
                        <td>Payment Date</td>
                        <td>Voucher No</td>
                        <td>Cheque No</td>
                        <td>GL Code</td>
                        <td>Description</td>
                        <td>Amount</td>
                     </tr>';
    $rowbg = 'white';
    $amount=0;
    if ($result = $db->Execute($sql)) {
        while ($row = $result->FetchRow()) {
            if ($bg == "silver")
                $bg = "white";
            else
                $bg = "silver";

            echo '<tr bgcolor=' . $bg . ' class="shiftContent">
                        <td>' . $row['PDATE'] . '</td>
                        <td>' . $row['Voucher_No'] . '</td>
                        <td>' . $row['cheque_no'] . '</td>
                        <td>' . $row['ledger_code'] . '</td>
                        <td>' . $row['ledger_desc'] . '</td>
                        <td align=right>' . number_format($row['Amount'],2) . '</td>';
            $amount=$amount+$row['Amount'];
            echo '</tr>';
            $rowbg = 'white';
        }
    }else {
        echo 'No Records found ';
    }


    echo "<tr><td colspan=5 align=right><b>Totals </td>
                <td align=right><b>" . number_format($amount, 2) . "</b></td><tr>";
    echo "</table></td></tr>";
    echo '</tbody></table>';

    $sql2="SELECT  ledger_code,ledger_desc,SUM(Amount) AS Amount FROM CARE_KE_PAYMENTS WHERE CASH_POINT='$cashpoint'
            AND PDATE BETWEEN '$date1' AND '$date2' GROUP BY ledger_code";
    $result2=$db->Execute($sql2);
    echo "<table>";
    echo "<tr class=\"prow\"><td>GL Code</td><td>Description</td><td>Amount</td></tr>";
    $totals=0;
    while($row2=$result2->FetchRow()){
        if ($bg == "silver")
            $bg = "white";
        else
            $bg = "silver";

        echo "<tr bgcolor='$bg' class='shiftContent'>
                <td>$row2[ledger_code]</td>
                <td>$row2[ledger_desc]</td>
                <td align='right'>".number_format($row2[Amount],2)."</td></tr>";
        $totals=$totals+$row2[Amount];
        $rowbg = 'white';
    }
    echo "<tr><td colspan='2'><b>Total</b></td><td><b>".number_format($totals,2)."</b></td></tr>";
    echo "</table>";

}


function getRevenueBreakdown($date1,$date2){
    global $db;
    $debug=false;

    $strDate1=new DateTime($date1);
    $strDate2=new DateTime($date2);
    $diff=$strDate1->diff($strDate2);
    //echo "The date Diff is ".$diff->days ;

    $yr=$strDate1->format("Y");
    $mnth=$strDate1->format("m");
    $sday=$strDate1->format('d');

    $rptDays=$diff->days+1;
echo 'Start date is '.$sday .' and Days are '.$rptDays;
    echo '<tr>';
    echo "<table border='0'><tr>";
    $cspan=$rptDays+2;
    echo "<td colspan=$cspan class=pgtitle>REVENUE BREAKDOWN REPORT </td>
            <tr class='prow'><td>Days</td>";
    for($i=$sday;$i<=$rptDays;$i++){
        echo "<td>$i</td>";
    }
    echo "<td>Total</td>";


    $counter=0;
    $sql = "SELECT r.rev_code,r.rev_desc,r.pay_mode, SUM(r.total) AS total,r.prec_desc,count(*) as rNO FROM care_ke_receipts r
                    WHERE currdate BETWEEN '$date1' and '$date2' GROUP BY r.rev_desc";
    if($debug) echo $sql;
    $result = $db->Execute($sql);
    $rcount=$result->RecordCount();
//    echo "<tr>";


    while ($row = $result->FetchRow($result)) {
        if ($bg == "silver")
            $bg = "white";
        else
            $bg = "silver";
        echo "<tr bgcolor='$bg' class='shiftContent'>";
        echo "<td>$row[rev_desc]</td>";
        $monthlyTotal=0;
        for($i=1;$i<=$rptDays;$i++) {
            $strDate = $yr . '-' . $mnth . '-' . $i;
            $value=getCashTotal($strDate, $row[rev_desc]);
            echo "<td align='right'>".$value."</td>";
            $monthlyTotal=$monthlyTotal+$value;
            if($i==$rptDays){

                echo "<td align='right'><b>".number_format($monthlyTotal,2)."</b></td></tr>";
            }
        }
        $rowbg = 'white';
        $counter = $counter + 1;

        if ($counter == $rptDays) {
            echo "</tr>";
        }
    }

    echo "<tr  class='shiftContent'><td><b>Totals</b></td>";
    for($i=1;$i<=$rptDays;$i++) {
        $strDate = $yr . '-' . $mnth . '-' . $i;
        $value=getDailyTotals($strDate);
        echo "<td align='right' width='45px'><b>".$value."</b></td>";
    }

    echo "</tr>";

    echo "</table></td></tr>";

    echo '</tbody></table>';

}

function getCashTotal($date1,$revDesc){
    global $db;
    $debug=false;

    $sql="select sum(Total) as Total from care_ke_receipts where rev_desc='$revDesc' and currdate='$date1' group by rev_desc";
    if($debug) echo $sql;

    $results=$db->Execute($sql);
       $row=$results->FetchRow();
       $strRow=$row[0];

      return $strRow;

}

function getDailyTotals($date1){
    global $db;
    $debug=false;

    $sql="SELECT  SUM(r.total) AS total FROM care_ke_receipts r WHERE currdate='$date1'";
    if($debug) echo $sql;

    $results=$db->Execute($sql);
    $row=$results->FetchRow();

    return $row[0];

}

function getRevenue2Breakdown($date1,$date2){
    global $db;
    $debug=false;


    $accDB=$_SESSION['sess_accountingdb'];

    $sql="SELECT accountcode,accountname FROM $accDB.chartmaster";
    $request = $db->Execute($sql);
    $fieldnames = array();
    while ($row = $request->FetchRow()) {
        array_push($fieldnames,$row);
    }

    $sql = "SHOW COLUMNS FROM care_ke_incometrans";
    if ($debug) {
        echo $sql;
    }
    $request = $db->Execute($sql);
    $cols=$request->RecordCount();

    echo '<tr>';
    echo "<table border='0'><tr>";
    echo "<td colspan=$cols class=pgtitle>REVENUE BREAKDOWN REPORT </td> <tr class='prow'>";
    echo "<td>ID</td><td class='titleContent'>Type</td><td>Trans Date</td><td>Time</td>";

    while ($row = $request->FetchRow()) {
        foreach($fieldnames as $values){
                if($values[accountcode]==$row[Field]){
                    echo "<td class='titleContent'> ". ucfirst(strtolower($values[accountname]))."</td>";
                }
        }

    }

    echo "<td class='titleContent'>CashHanded</td><td class='titleContent'>Mpesa</td><td class='titleContent'>Visa</td></tr>";

    $counter=0;
    $sql = "SELECT ID,`TransDate`,`LastTransTime`,`TransType`,`1019`,`1021`,`1018`,`1007`,`1020`,`1006`,`1001`,
                  `1017`,`1015`,`1012`,`1013`,`1009`,`1011`, `CashHanded`,`Mpesa`,`Visa`
                  FROM `care_ke_incometrans` where TransType='receipt'";
    if($debug) echo $sql;
    $result = $db->Execute($sql);
    $rcount=$result->RecordCount();
//    echo "<tr>";


    while ($row = $result->FetchRow($result)) {
        if ($bg == "silver")
            $bg = "white";
        else
            $bg = "silver";
        echo "<tr bgcolor='$bg' class='shiftContent'>";
        echo "<td class='cellContent'>$row[ID]</td><td class='cellContent'>$row[TransType]</td><td class='cellContent'>$row[TransDate]</td><td class='cellContent'>$row[LastTransTime]</td>";
        echo "<td class='cellContent'>$row[1019]</td><td class='cellContent'>$row[1021]</td><td class='cellContent'>$row[1018]</td><td class='cellContent'>$row[1007]</td>";
        echo "<td class='cellContent'>$row[1020]</td><td class='cellContent'>$row[1006]</td><td class='cellContent'>$row[1001]</td><td class='cellContent'>$row[1017]</td>";
        echo "<td class='cellContent'>$row[1015]</td><td class='cellContent'>$row[1012]</td><td class='cellContent'>$row[1013]</td><td class='cellContent'>$row[1009]</td>";
        echo "<td class='cellContent'>$row[1011]</td><td class='cellContent'>$row[CashHanded]</td><td class='cellContent'>$row[Mpesa]</td><td class='cellContent'>$row[Visa]</td>";
        $monthlyTotal=0;

        $rowbg = 'white';
        $counter = $counter + 1;

        if ($counter == $rptDays) {
            echo "</tr>";
        }
    }

    echo "</table></td></tr>";

    echo '</tbody></table>';

}

function getCreditBreakdown($date1,$date2){
    global $db;
    $debug=false;

    $accDB=$_SESSION['sess_accountingdb'];

    $sql="SELECT accountcode,accountname FROM $accDB.chartmaster";
    $request = $db->Execute($sql);
    $fieldnames = array();
    while ($row = $request->FetchRow()) {
        array_push($fieldnames,$row);
    }

    $sql = "SHOW COLUMNS FROM care_ke_incometrans";
    if ($debug) {
        echo $sql;
    }
    $request = $db->Execute($sql);
    $Cols=$request->RecordCount();

    echo '<tr>';
    echo "<table border='0'><tr>";
    echo "<td colspan=$Cols class=pgtitle>CREDIT BREAKDOWN REPORT </td> <tr class='prow'>";
    echo "<td>ID</td><td class='titleContent'>Type</td><td>Trans Date</td><td>Time</td>";

    while ($row = $request->FetchRow()) {
        foreach($fieldnames as $values){
            if($values[accountcode]==$row[Field]){
                echo "<td class='titleContent'> ". ucfirst(strtolower($values[accountname]))."</td>";
            }
        }
    }

    echo "<td class='titleContent'>CashHanded</td><td class='titleContent'>Mpesa</td><td class='titleContent'>Visa</td></tr>";

    $counter=0;
    $sql = "SELECT ID,`TransDate`,`LastTransTime`,`TransType`,`1019`,`1021`,`1018`,`1007`,`1020`,`1006`,`1001`,
                  `1017`,`1015`,`1012`,`1013`,`1009`,`1011`, `CashHanded`,`Mpesa`,`Visa`
                  FROM `care_ke_incometrans` where TransType='invoice'";
    if($debug) echo $sql;
    $result = $db->Execute($sql);
    $rcount=$result->RecordCount();
//    echo "<tr>";


    while ($row = $result->FetchRow($result)) {
        if ($bg == "silver")
            $bg = "white";
        else
            $bg = "silver";
        echo "<tr bgcolor='$bg' class='shiftContent'>";
        echo "<td class='cellContent'>$row[ID]</td><td class='cellContent'>$row[TransType]</td><td class='cellContent'>$row[TransDate]</td><td class='cellContent'>$row[LastTransTime]</td>";
        echo "<td class='cellContent'>$row[1019]</td><td class='cellContent'>$row[1021]</td><td class='cellContent'>$row[1018]</td><td class='cellContent'>$row[1007]</td>";
        echo "<td class='cellContent'>$row[1020]</td><td class='cellContent'>$row[1006]</td><td class='cellContent'>$row[1001]</td><td class='cellContent'>$row[1017]</td>";
        echo "<td class='cellContent'>$row[1015]</td><td class='cellContent'>$row[1012]</td><td class='cellContent'>$row[1013]</td><td class='cellContent'>$row[1009]</td>";
        echo "<td class='cellContent'>$row[1011]</td><td class='cellContent'>$row[CashHanded]</td><td class='cellContent'>$row[Mpesa]</td><td class='cellContent'>$row[Visa]</td>";
        $monthlyTotal=0;

        $rowbg = 'white';
        $counter = $counter + 1;

        if ($counter == $rcount) {
            echo "</tr>";
        }
    }

    echo "</table></td></tr>";

    echo '</tbody></table>';

}

?>

