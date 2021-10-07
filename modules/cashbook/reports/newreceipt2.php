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
                <tr><td class='itemTitles' colspan='3'><hr></td></tr>
				<tr> <td colspan='3' class='mainTitles' align=center>RECEIPT</td></tr>
				<tr><td class='itemTitles' colspan='3'><hr></td></tr>";
				if($reprint=='REPRINT'){
				     echo "<tr> <td colspan='3' class='summaryTitle'>NOTE: THIS IS A RECEIPT COPY</td></tr>
						<tr><td class='itemTitles' colspan='3'><hr></td></tr>";
				}


         echo "<tr><td class='itemTitles' colspan='3'>$company:</td></tr>
               <tr><td class='itemTitles' colspan='3'>$address - $postal:</td></tr>
               <tr><td class='itemTitles' colspan='3'>Tel:$tel </td></tr>
               <tr><td class='itemTitles' colspan='3'><hr></td></tr>";

         $reprint=$_REQUEST['reprint'];

            if($reprint=='REPRINT'){
                echo "<tr><td class='itemTitles' colspan='3'>NOTE: THIS IS A RECEIPT COPY</td></tr>";
            }

                     
     echo"<tr><td class='itemTitles'>Dates:</td><td class=invDetails colspan='3'>".$_REQUEST['rdate']."</td></tr>
          <tr><td class='itemTitles'>Receipt No:</td><td class=invDetails>".$_REQUEST['refno']."</td></tr>
          <tr><td class='itemTitles'>Patient No:</td><td class=invDetails>".$_REQUEST['pno']."</td></tr>
         <tr><td class='itemTitles'>Customer:</td><td class=invDetails>".$_REQUEST['PatientName']."</td></tr>
		 <tr><td class='itemTitles'>Towards:</td><td class=invDetails>".$_REQUEST['towards']."</td></tr>
         <tr><td class='itemTitles'>Payment mode:</td><td class=invDetails>".$_REQUEST['PaymentMode']."</td></tr>";

    echo "<tr><td colspan='3'><hr></td></tr>";

	$shiftNo=$_REQUEST['shiftno'];
    echo"<tr><td class='itemTitles' colspan=2>ITEM:</td>
          <td class='itemTitles'>AMOUNT</td></tr>";

    echo "<tr><td colspan='3'><hr></td></tr>";

	$cashpoint=$_REQUEST['cashpoint'];
	$refno=$_REQUEST['refno'];
	$pno=$_REQUEST['pno'];

	$r_sql = "select rev_desc, proc_qty, `Prec_desc`, total, amount, rev_code, proc_code,towards
	from care_ke_receipts where ref_no='$refno' and cash_point='$_REQUEST[cashpoint]' AND patient='$pno' and shift_no='$shiftNo'";

    $result = $db->Execute($r_sql);
    //echo $r_sql;
    $curr_point=150;
    $mpesaRef='';

    while ( $row = $result->FetchRow()) {
        echo"<tr><td class='itemTitles' colspan=2>".$row['towards']."</td>
          <td class='itemTitles' align='center'>".number_format($row['total'],2)."</td>";

        $mpesaRef=$row[mpesaRef];
    }

    echo "<tr><td colspan='3'><hr></td></tr>";

	$sql = "SELECT SUM(total) AS total FROM care_ke_receipts
     WHERE ref_no='$refno' AND cash_point='$cashpoint' AND patient='$pno' and shift_no='$shiftNo'";
       $result = $db->Execute($sql);
    //         echo $sql;
    $row = $result->FetchRow();
    echo"<tr><td class='itemTitles'>".substr($row['Prec_desc'])."</td>
          <td class='itemTitles' align=center>TOTAL</td>
          <td class='itemTitles'>".number_format($row['total'],2)."</td>";

        echo "<tr><td colspan='3'><hr></td></tr>";

    echo"<tr><td class='itemTitles'>Paid By.....:</td><td class=invDetails colspan='2'>".$_REQUEST['PatientName']."</td></tr>
          <tr><td class='itemTitles'>Cashier.....:</td><td class=invDetails colspan='2'>".$_REQUEST['cashier']."</td></tr>
          <tr><td class='itemTitles'>Cash Point..:</td><td class=invDetails colspan='2'>".$_REQUEST['cashpoint']."</td></tr>
         <tr><td class='itemTitles' colspan='3' align=center>Thank You:</td></tr>";

    echo "<tr><td colspan='3'><hr></td></tr>";

?>
</div>
                 <div class='pageNos'></div>
            </div>
   
</div>        

