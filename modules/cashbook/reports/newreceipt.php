<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require('roots.php');
require($root_path . 'include/inc_environment_global.php');
?>
 <link rel="stylesheet" href="reportsCss.css">
<div class="book">
    <?php 
    $pid = $_REQUEST['pid'];
    $encNo = $_REQUEST['en'];
    $billNumber = $_REQUEST['billNumber'];
    $debug=false;
    

    $sql = "SELECT * FROM care_ke_invoice";
    $global_result = $db->Execute($sql);
    if ($global_result) {
        while ($data_result = $global_result->FetchRow()) {
            $company = $data_result ['CompanyName'];
            $address = $data_result ['Address'];
            $town = $data_result ['Town'];
            $postal = $data_result ['Postal'];
            $tel = $data_result ['Tel'];
            $invoice_no = $data_result ['new_bill_nr'];
        }
        $global_config_ok = 1;
    } else {
        $global_config_ok = 0;
    }

    $imagePath="../../../icons/logo2.jpg";

    global $db;
    $debug=false;
    $datePrinted=date('Y-m-d H:i:s');
    echo "<div class='page'>";
     echo "<div class='subpage'>
            <table border=0>
                <tr> <td colspan='3' class='logo'> <img src='../../../icons/logo2.jpg' width='200' height='100' ></td></tr>
                <tr> <td colspan='3' class='summaryTitle'>PATIENT RECEIPT</td></tr>";


         echo "<tr><td class='itemTitles' colspan='3'>$company:</td></tr>
               <tr><td class='itemTitles' colspan='3'>$address - $postal:</td></tr>
               <tr><td class='itemTitles' colspan='3'>Tel:$tel </td></tr>
               <tr><td class='itemTitles' colspan='3'><hr></td></tr>";

         $reprint=$_REQUEST['reprint'];

            if($reprint=='REPRINT'){
                echo "<tr><td class='reprintSS' colspan='3' align=center >NOTE: THIS IS A RECEIPT COPY</td></tr>";
                echo "<tr><td colspan='3'><hr></td></tr>";
            }

                     
     echo"<tr><td class='itemTitles'>Dates:</td><td colspan=2  class=invDetails>".$_REQUEST['rdate']."</td></tr>
          <tr><td class='itemTitles'>Receipt No:</td><td colspan=2  class=invDetails>".$_REQUEST['refno']."</td></tr>
          <tr><td class='itemTitles'>Patient No:</td><td colspan=2  class=invDetails>".$_REQUEST['pno']."</td></tr>
         <tr><td class='itemTitles'>Patient name:</td><td colspan=2 class=invDetails>".$_REQUEST['PatientName']."</td></tr>
         <tr><td class='itemTitles'>Payment mode:</td><td colspan=2  class=invDetails>".$_REQUEST['PaymentMode']."</td></tr>";

    echo "<tr><td colspan='3'><hr></td></tr>";

    echo"<tr><td class='itemTitles'>ITEM:</td>
          <td class='itemTitles' align='center'>QTY:</td>
          <td class='itemTitles' align=left>AMOUNT</td>";

    echo "<tr><td colspan='3'><hr></td></tr>";

    $cashpoint=$_REQUEST['cashpoint'];
    $refno=$_REQUEST['refno'];
    $pno=$_REQUEST['pno'];
    $shiftNo=$_REQUEST['shiftno'];

    $r_sql = "select rev_desc, proc_qty, `Prec_desc`, total, amount, rev_code, proc_code,mpesaRef from care_ke_receipts
            where ref_no='$refno' and cash_point='$_REQUEST[cashpoint]' AND patient='$pno' and shift_no='$shiftNo'";

    $result = $db->Execute($r_sql);
    //echo $r_sql;
    $curr_point=150;
    $mpesaRef='';
	$total=0;
    while ( $row = $result->FetchRow()) {
        echo"<tr><td class='invDetails'>".$row['Prec_desc']."</td>
          <td class='invDetails' align='center'>".$row['proc_qty']."</td>
          <td class='invDetails' align='r'>".number_format($row['total'],2)."</td>";
		$total=$total+$row['total'];
        $mpesaRef=$row[mpesaRef];
    }

    echo "<tr><td colspan='3'><hr></td></tr>";

    $sql = "SELECT cash,mpesa,visa FROM care_ke_receipts
     WHERE ref_no='$refno' AND cash_point='$cashpoint' AND patient='$pno' and shift_no='$shiftNo'";
    $result = $db->Execute($sql);
    //echo $sql;
    $row = $result->FetchRow();
    echo"<tr><td class='itemTitles'>".substr($row['Prec_desc'])."</td>
          <td class='itemTitles'>TOTAL</td>
          <td class='itemTitles'>".number_format($total,2)."</td>";

    $bal=($row['cash']+$row['mpesa']+$row['visa'])-$total;
    echo "<tr><td colspan='3'><hr></td></tr>";

    echo"<tr><td class='itemTitles'>Cash Amount Paid:</td><td class=invDetails colspan='2'>".$row['cash']."</td></tr>
          <tr><td class='itemTitles'>Mpesa Amount Paid:</td><td class=invDetails colspan='2'>".$row['mpesa']."</td></tr>
          <tr><td class='itemTitles'>Visa Amount paid:</td><td class=invDetails colspan='2'>".$row['visa']."</td></tr>
          <tr><td class='itemTitles'>Change given:</td><td class=invDetails colspan='2'>".number_format($bal,2)."</td></tr>";

    if($row['mpesa']>0){
        echo"<tr><td class='itemTitles'>MPESA REF NO:</td><td class=invDetails>".$mpesaRef."</td></tr>";
    }

    echo "<tr><td colspan='3'><hr></td></tr>";

    echo"<tr><td class='itemTitles'>Paid By.....:</td><td class=invDetails colspan='2'>".$_REQUEST['PatientName']."</td></tr>
          <tr><td class='itemTitles'>Cashier.....:</td><td class=invDetails colspan='2'>".$_REQUEST['cashier']."</td></tr>
          <tr><td class='itemTitles'>Cash Point..:</td><td class=invDetails colspan='2'>".$_REQUEST['cashpoint']."</td></tr>
         <tr><td class='itemTitles' colspan='3'>Thank`s and wish you a quick recovery:</td></tr>";

    echo "<tr><td colspan='3'><hr></td></tr>";

?>
</div>
                 <div class='pageNos'></div>
            </div>
   
</div>        

