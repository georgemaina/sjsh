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

$titleStyle = new Zend_Pdf_Style ();
$titleStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
$titleFont = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD_ITALIC);
$titleStyle->setFont($titleFont, 10);

            
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

$imagePath="../../../icons/logo2.jpg";
$image = Zend_Pdf_Image::imageWithPath($imagePath);
$page->drawImage($image, $leftPos+20, $topPos-80, $leftPos+140, $topPos);

$topPos=$topPos-80;

$page->drawText("PATIENT RECEIPT", $leftPos + 10, $topPos - 10);
$page->drawText($company, $leftPos + 10, $topPos - 20);
$page->drawText($address .'-'.$postal.'-'.$town, $leftPos + 10, $topPos - 30);
$page->drawText("Tel:".$tel, $leftPos + 10, $topPos - 40);
$page->drawText("________________________________________", $leftPos + 10, $topPos - 45);

$page->setStyle($titleStyle);
$reprint=$_REQUEST['reprint'];

if($reprint='REPRINT'){
    $page->drawText("NOTE: THIS IS A RECEIPT COPY", $leftPos + 20, $topPos - 62);
    $page->drawText("________________________________________", $leftPos + 10, $topPos - 70);
    $topPos=$topPos-30;
}

$page->setStyle($headlineStyle);

//$topPos=$topPos+10;
$page->drawText('Date:', $leftPos + 10, $topPos - 60);
$page->drawText($_REQUEST['rdate'].' '.$_REQUEST['inputTime'], $leftPos + 30, $topPos - 60);

$page->drawText('Receipt No:', $leftPos + 10, $topPos - 80);
$page->drawText($_REQUEST['refno'], $leftPos + 80, $topPos - 80);

$page->drawText('Patient No:', $leftPos + 10, $topPos - 90);
$page->drawText($_REQUEST['pno'], $leftPos + 80, $topPos - 90);

$page->drawText('Patient Name:', $leftPos + 10, $topPos - 100);
$page->drawText($_REQUEST['PatientName'], $leftPos + 80, $topPos - 100);

$page->drawText('Payment Mode:', $leftPos + 10, $topPos - 110);
$page->drawText($_REQUEST['PaymentMode'], $leftPos + 80, $topPos - 110);


$page->drawText('_________________________________________', $leftPos + 10, $topPos - 121);
$page->drawText('CODE', $leftPos + 10, $topPos - 132);
$page->drawText('Qty', $leftPos + 130, $topPos - 132);
$page->drawText('AMOUNT', $leftPos + 150, $topPos - 132);
$page->drawText('_________________________________________', $leftPos + 10, $topPos - 135);

$cashpoint=$_REQUEST['cashpoint'];
$refno=$_REQUEST['refno'];
$pno=$_REQUEST['pno'];
$shiftNo=$_REQUEST['shiftno'];

$r_sql = "select rev_desc, proc_qty, `Prec_desc`, total, amount, rev_code, proc_code,mpesaRef from care_ke_receipts
where ref_no='$refno' and cash_point='$_REQUEST[cashpoint]' AND patient='$pno' and shift_no='$shiftNo'";
echo $r_sql;
    $result = $db->Execute($r_sql);
    
    $curr_point=150;
    $mpesaRef='';
	$total=0;
    while ( $row = $result->FetchRow()) {
        $page->drawText(substr($row['Prec_desc'],0,30), $leftPos + 10, $topPos - $curr_point);
        $page->drawText($row['proc_qty'], $leftPos + 130, $topPos - $curr_point);
        $page->drawText($row['total'], $leftPos + 160, $topPos - $curr_point);
        $curr_point=$curr_point+10;
        $mpesaRef=$row[mpesaRef];
$total=$total+$row['total'];
    }
    $topPos = $topPos - $curr_point;
 $page->drawText('__________________________________________', $leftPos + 10, $topPos - 8);
    
$sql = "SELECT cash,mpesa,visa FROM care_ke_receipts
     WHERE ref_no='$refno' AND cash_point='$cashpoint' AND patient='$pno' and shift_no='$shiftNo'";
       $result = $db->Execute($sql);
//         echo $sql;
       $row = $result->FetchRow();
             $page->drawText("Total", $leftPos + 10, $topPos - 20);
             $page->drawText($total, $leftPos + 155, $topPos - 20);
$topPos=$topPos-10;

$page->drawText("Cash Amount Paid", $leftPos + 10, $topPos - 30);
$page->drawText($row['cash'], $leftPos + 155, $topPos - 30);
$page->drawText("Mpesa Amount Paid", $leftPos + 10, $topPos - 43);
$page->drawText($row['mpesa'], $leftPos + 155, $topPos - 43);
$page->drawText("Visa Amount paid", $leftPos + 10, $topPos - 56);
$page->drawText($row['visa'], $leftPos + 155, $topPos - 56);
$page->drawText("Change given", $leftPos + 10, $topPos - 69);

$bal=($row['cash']+$row['mpesa']+$row['visa'])-$total

$page->drawText(number_format($bal,2), $leftPos + 155, $topPos - 69);

if($row['mpesa']>0){
    $page->drawText('MPESA REF NO:', $leftPos + 10, $topPos - 85);
    $page->drawText($mpesaRef, $leftPos + 80, $topPos - 85);
}

$topPos=$topPos-70;
        
 $page->drawText('__________________________________________', $leftPos + 10, $topPos - 22);
 $page->drawText('Paid By.....:', $leftPos + 10, $topPos - 40);
 $page->drawText($_REQUEST['PatientName'], $leftPos + 60, $topPos - 40);
 $page->drawText('Cashier.....:', $leftPos + 10, $topPos - 50);
 $page->drawText($_REQUEST['cashier'], $leftPos + 60, $topPos - 50);
 $page->drawText('Cash Point..:', $leftPos + 10, $topPos - 60);
 $page->drawText($_REQUEST['cashpoint'], $leftPos + 60, $topPos - 60);
 $page->drawText('Thank`s and wish you a quick recovery', $leftPos + 30, $topPos - 80);
 //$page->drawText('__________________________________________', $leftPos + 10, $topPos - 120);

    
    
    
    array_push($pdf->pages, $page);
    header('Content-type: application/pdf');
    echo $pdf->render();


?>
