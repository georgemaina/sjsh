<?php

require ('roots.php');
require ($root_path . 'include/inc_environment_global.php');

require_once 'Zend/Pdf.php';
$pdf = new Zend_Pdf ();
$page = new Zend_Pdf_Page(200, 600);;
$headlineStyle = new Zend_Pdf_Style ();
$headlineStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
$headlineStyle->setFont($font, 8);
$page->setStyle($headlineStyle);
            
$pageHeight = 600;
$width = 200;
$topPos = $pageHeight - 2;
$leftPos = 10;
    

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
$page->drawText("$company", $leftPos + 10, $topPos - 20);   
$page->drawText("$address , $town", $leftPos + 10, $topPos - 30); 
$page->drawText("Tel:$tel", $leftPos + 10, $topPos - 40); 
$page->drawText("________________________________________", $leftPos + 10, $topPos - 50); 
        
$page->drawText('Date:', $leftPos + 10, $topPos - 60); 
$page->drawText($_REQUEST['rdate'], $leftPos + 40, $topPos - 60); 

$page->drawText('Receipt No:', $leftPos + 10, $topPos - 80); 
$page->drawText($_REQUEST['refno'], $leftPos + 80, $topPos - 80); 

$page->drawText('Patient No:', $leftPos + 10, $topPos - 90); 
$page->drawText($_REQUEST['pno'], $leftPos + 80, $topPos - 90);

$page->drawText('Patient Name:', $leftPos + 10, $topPos - 100); 
$page->drawText($_REQUEST['PatientName'], $leftPos + 80, $topPos - 100);

$page->drawText('Payment Mode:', $leftPos + 10, $topPos - 110); 
$page->drawText($_REQUEST['PaymentMode'], $leftPos + 80, $topPos - 110);

$page->drawText('_________________________________________', $leftPos + 10, $topPos - 121);
$page->drawText('CODE', $leftPos + 10, $topPos - 130);
$page->drawText('AMOUNT', $leftPos + 110, $topPos - 130);
$page->drawText('_________________________________________', $leftPos + 10, $topPos - 135);

$cashpoint=$_REQUEST['cashpoint'];
$refno=$_REQUEST['refno'];
$pno=$_REQUEST['pno'];

$r_sql = "select rev_desc, proc_qty, `Prec_desc`, total, amount, rev_code, proc_code
from care_ke_receipts where ref_no='$refno' and cash_point='$_REQUEST[cashpoint]' AND patient='$pno'";

    $result = $db->Execute($r_sql);
//        echo $r_sql;
    $curr_point=150;
    while ( $row = $result->FetchRow()) {
        $page->drawText($row['Prec_desc'], $leftPos + 10, $topPos - $curr_point);
        $page->drawText($row['amount'], $leftPos + 120, $topPos - $curr_point);
        $curr_point=$curr_point+10;
    }
    $topPos = $topPos - $curr_point;
 $page->drawText('__________________________________________', $leftPos + 10, $topPos - 11);
    
$sql = "SELECT SUM(total) AS total FROM care_ke_receipts
     WHERE ref_no='$refno' AND cash_point='$cashpoint' AND patient='$pno'";
       $result = $db->Execute($sql);
//         echo $sql;
       $row = $result->FetchRow();
             $page->drawText("Total", $leftPos + 10, $topPos - 20);
             $page->drawText($row['total'], $leftPos + 120, $topPos - 20);
        
 $page->drawText('__________________________________________', $leftPos + 10, $topPos - 26);
 $page->drawText('Paid By.....:', $leftPos + 10, $topPos - 40);
 $page->drawText($_REQUEST['PatientName'], $leftPos + 60, $topPos - 40);
 $page->drawText('Cashier.....:', $leftPos + 10, $topPos - 50);
 $page->drawText($_REQUEST['cashier'], $leftPos + 60, $topPos - 50);
 $page->drawText('Cash Point..:', $leftPos + 10, $topPos - 60);
 $page->drawText($_REQUEST['cashpoint'], $leftPos + 60, $topPos - 60);
 $page->drawText('Thank', $leftPos + 50, $topPos - 80);
 $page->drawText('__________________________________________', $leftPos + 10, $topPos - 120);

    
    
    
    array_push($pdf->pages, $page);
    header('Content-type: application/pdf');
    echo $pdf->render();


?>
