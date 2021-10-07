<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('roots.php');
require($root_path.'include/inc_environment_global.php');
$desc2=$_REQUEST['q'];
$desc3=$_REQUEST['q2'];

printform($desc2,$desc3);

function printform($desc2,$desc3){

echo ' <div class=pgtitle>Shift Report for '.$desc2.' and Shift No '.$desc3.'</div>';
echo '<table border="0" width="100%">
        <tr>
           <td align=right>
                '.getInvoices($desc2,$desc3).'
         </td>
  </tr></table>';
}

function getInvoices($desc2,$desc3,$cashier) {
global $db;
$sql = "SELECT * FROM care_ke_invoice";
	$global_result = $db->Execute ( $sql );
	if ($global_result) {
		while ( $data_result = $global_result->FetchRow () ) {
			$company = $data_result ['CompanyName'];
			$address = $data_result ['Address'];
			$town = $data_result ['Town'];
			$postal = $data_result ['Postal'];
			$tel = 'Phone: '.$data_result ['Tel'];
			$invoice_no = $data_result ['new_bill_nr'];
		}
		$global_config_ok = 1;
	} else {
		$global_config_ok = 0;
	}

   $sql = "SELECT b.Shift_no,b.ref_no,b.`type`,b.input_time,b.patient,b.name,b.rev_code,b.rev_desc,
b.proc_code,b.prec_desc,b.payer,b.location,b.pay_mode,sum(b.amount) as amount,b.proc_qty,sum(b.total) as total,a.start_date,a.start_time,b.currdate,
b.pay_mode       
FROM care_ke_receipts b JOIN care_ke_shifts a  ON b.shift_no=a.shift_no 
WHERE b.cash_point='$desc2' and b.shift_no='$desc3' group by ref_no";

   
//echo $sql;
echo '<table width="100%"><tbody>';
$sql1='select * from care_ke_shifts where cash_point="'.$desc2.'" and shift_no='.$desc3;
//echo $sql1;
$result1=$db->execute($sql1);
$row=$result1->FetchRow();

echo '
                     <tr>
                        <td align=left>'.$company.'<br>'.$address.'<br>'.$town.' - '.$postal.'
                            <br>'.$tel.'</td>
                        <td colspan=7><center><b>SUMMARY SHIFT REPORT</b></center></td>
                        <td colspan=2><b>Start Date:</b> '.$row[start_date].' '.$row[start_time].'<br>
                            <b>End Date:</b> '.$row[end_date].' '.$row[end_time].'<br>
                            <b>Cash Point:</b> '.$row[end_date].'
                            <br> <b>Shift No:</b> '.$desc3.'<br>
                             <b>Casher:</b> '.$row[cashier].'</td>
                     </tr>
                    <tr class="prow">
                        <td>Receipt No</td>
                        <td>Type</td>
                        <td>Date</td>
                        <td>Time</td>
                        <td>Patient ID</td>
                        <td>Patient Names</td>
                        <td>Revenue code</td>
                        <td>Pay Mode</td>
                        <td>Amount</td>
                        <td>Cheques</td>
                        <td>Others</td>
                     </tr>';
    $rowbg='white';
    if ($result=$db->Execute($sql)){
    while($row = $result->FetchRow()){
         if ($bg == "silver")
                    $bg = "white";
                else
                    $bg="silver";
          echo '<tr bgcolor='.$bg.' class="shiftContent">
                        <td>'.$row[1].'</td>
                        <td>'.$row[2].'</td>
                            <td>'.$row['currdate'].'</td>
                       <td>'.$row[3].'</td>
                       
                        <td>'.$row[4].'</td>
                       
                        <td>'.$row[5].'</td>
                        <td>'.$row[6].'</td>
                        <td>'.$row['pay_mode'].'</td>
                        <td align=right>';
                                    if($row['pay_mode']=='CAS' || $row['pay_mode']=='cas')
                                          echo 'Ksh '.$row['total'];
                                     else 
                                         echo "";
                                     
                            echo '</td>
                        <td>';
                            if($row['pay_mode']=='CHQ' || $row['pay_mode']=='chq')
                                echo 'Ksh '.$row['total'];
                            else 
                                echo "";
                            
                                echo '</td>
                        <td>';
                            if($row['pay_mode']=='MPESA' || $row['pay_mode']=='mpesa' 
                                    || $row['pay_mode']=='visa' || $row['pay_mode']=='visa' ||$row['pay_mode']=='')
                                echo 'Ksh '.$row['total'];
                            else 
                                echo "";
                            
                                echo '</td>
                     </tr>';
                 $rowbg='white';
    }}else{
        echo 'error ';
    }
    //get total collection
    $sqli = "SELECT SUM(r.total) AS total FROM care_ke_receipts r  WHERE 
r.cash_point='$desc2' AND r.shift_no='$desc3' and pay_mode='cas'";
    $resulti=$db->Execute($sqli);
    $rowi = $resulti->FetchRow();
    $casTotal=$rowi[0];
    
//    echo $sqli;
    
    $sqlj = "SELECT SUM(r.total) AS total FROM care_ke_receipts r WHERE 
r.cash_point='$desc2' AND r.shift_no='$desc3' and pay_mode='chq'";
    $resultj=$db->Execute($sqlj);
    $rowj = $resultj->FetchRow();
    $chqTotal=$rowj[0];
    
//    echo $sqlj;
    
    $sqlk = "SELECT SUM(r.total) AS total FROM care_ke_receipts r WHERE 
r.cash_point='$desc2' AND r.shift_no='$desc3' and pay_mode NOT IN ('CAS','CHQ','cas','cas')";
    
//    echo $sqlk;
    
    
    $resultk=$db->Execute($sqlk);
    $rowk = $resultk->FetchRow();
    $othesTotal=$rowk[0];
    $total=intval($casTotal+$chqTotal+$othesTotal);
            echo "<tr><td colspan=8 align=right><b>Sub Totals </td>
                <td align=right><b>".number_format($casTotal,2)."</b></td>
                <td align=right><b>".number_format($chqTotal,2)."</b></td>
                <td align=right><b>".number_format($othesTotal,2)."</b></td><tr>";
            echo "<tr><td colspan=8 align=right><b>Totals </td>
                <td align=right><b>".number_format($total,2)."</b></td>
                <td align=right><b></b></td>
                <td align=right><b></b></td><tr>";

    // report summary grouped by revenue codesSELECT sum(total) as total FROM care_ke_receipts WHERE cash_point='$desc2' and shift_no='$desc3'";
    echo '<tr><td colspan=9><hr></td><tr><td colspan=9>
                  <table>
                      <tr class=prow><td colspan=3>Breakdown of Cash Sale</td></tr>
                      <tr class="prow">
                                <td>rev code</td>
                                <td>pay mode</td>
                                <td>amount</td>
                    </tr>';
            $sql = "SELECT r.rev_desc,r.pay_mode, SUM(r.total) AS total FROM care_ke_receipts r 
            WHERE r.cash_point='$desc2' and r.shift_no='$desc3'
                     GROUP BY r.rev_desc";
//            echo $sql;
            $result=$db->Execute($sql);
            $totalt=0;
            while ($row = $result->FetchRow($result)){
                if ($bg == "silver")
                    $bg = "white";
                else
                    $bg="silver";
                   echo '<tr bgcolor='.$bg.'>
                                <td>'.$row[0].'</td>
                                <td>'.$row[1].'</td>
                                <td>'.$row[2].'</td>
                                </tr>';
                          $rowbg='white';
                          $totalt=intval($totalt+$row[2]);
            }
            echo  '<tr><td colspan=2><b>Total:</b></td><td><b>'.$casTotal+$chqTotal+$othesTotal.'</b></td></tr>';

        echo '<tr bgcolor='.$bg.'>
                                <td></td>
                                <td>TOTAL:</td>
                                <td><b>'.number_format($totalt,2).'</b></td></tr>';
        
            echo "</table></td></tr>";
  echo '</tbody></table>';
}
?>

