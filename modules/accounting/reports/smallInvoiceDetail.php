<?php

require ('roots.php');
require ($root_path . 'include/inc_environment_global.php');
require '../../../include/care_api_classes/class_ward.php';
$wrd = new Ward ();
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

$billNumber=10089065; //$_REQUEST['billNumber'];
$pid=10119140; //$_REQUEST['pid'];

$sql2 = "SELECT b.pid,b.encounter_nr, CONCAT(p.name_first,' ', p.name_2,' ', p.name_last) as pnames, p.date_birth, p.addr_zip
            ,p.cellphone_1_nr, p.citizenship, b.`IP-OP`,b.bill_number
         FROM care_ke_billing b INNER JOIN care_person p ON (b.pid = p.pid)
            WHERE (b.`IP-OP`='1' and b.pid='" . $pid . "' and b.bill_number='$billNumber')";
echo $sql2;
$info_result = $db->Execute($sql2);
$row=$info_result->FetchRow();

$row2 = $wrd->EncounterLocationsInfo2($row['encounter_nr']);
$bed_nr = $row2 [6];
$room_nr = $row2 [5];
$ward_nr = $row2 [0];
$ward_name = $row2 [1];
$admDate = $row2 [7];
$disc_date = $row2 [8];

$page->drawText("PATIENT INVOICE", $leftPos + 10, $topPos - 10);
$page->drawText($company, $leftPos + 10, $topPos - 20); 
$page->drawText($address .'-'.$postal.'-'.$town, $leftPos + 10, $topPos - 30); 
$page->drawText("Tel:".$tel, $leftPos + 10, $topPos - 40); 
$page->drawText("________________________________________", $leftPos + 10, $topPos - 50); 
        
$page->drawText('PID:', $leftPos + 10, $topPos - 60);
$page->drawText($row['pid'], $leftPos + 40, $topPos - 60);

$page->drawText('Patient Names:', $leftPos + 10, $topPos - 80);
$page->drawText($row['pnames'], $leftPos + 80, $topPos - 80);

$page->drawText('Invoice No:', $leftPos + 10, $topPos - 90);
$page->drawText($_REQUEST['bill_number'], $leftPos + 80, $topPos - 90);

$page->drawText('Admission Date:', $leftPos + 10, $topPos - 100);
$page->drawText($row['adm_date'], $leftPos + 80, $topPos - 100);

$page->drawText('Discharge Date:', $leftPos + 10, $topPos - 100);
$page->drawText($row['dis_date'], $leftPos + 80, $topPos - 100);


$page->drawText('_________________________________________', $leftPos + 10, $topPos - 121);
$page->drawText('DESC', $leftPos + 10, $topPos - 130);
$page->drawText('Qty', $leftPos + 130, $topPos - 130);
$page->drawText('AMOUNT', $leftPos + 150, $topPos - 130);
$page->drawText('_________________________________________', $leftPos + 10, $topPos - 135);



$r_sql = "select Description, Qty, `Total`, total from care_ke_Billing where pid='$pid' and bill_number='$billNumber'";

    $result = $db->Execute($r_sql);
    //echo $r_sql;
    $curr_point=150;
    while ( $row = $result->FetchRow()) {
        $page->drawText(substr($row['Description'],0,30), $leftPos + 10, $topPos - $curr_point);
        $page->drawText($row['Qty'], $leftPos + 130, $topPos - $curr_point);
        $page->drawText($row['Total'], $leftPos + 160, $topPos - $curr_point);
        $curr_point=$curr_point+10;
    }
    $topPos = $topPos - $curr_point;
 $page->drawText('__________________________________________', $leftPos + 10, $topPos - 11);
    
$sql = "SELECT SUM(total) AS total FROM care_ke_receipts
     WHERE ref_no='$refno' AND cash_point='$cashpoint' AND patient='$pno' and shift_no='$shiftNo'";
       $result = $db->Execute($sql);
//         echo $sql;
       $row = $result->FetchRow();
             $page->drawText("Total", $leftPos + 10, $topPos - 20);
             $page->drawText($row['total'], $leftPos + 155, $topPos - 20);
        
 $page->drawText('__________________________________________', $leftPos + 10, $topPos - 26);
 $page->drawText('Paid By.....:', $leftPos + 10, $topPos - 40);
 $page->drawText($_REQUEST['PatientName'], $leftPos + 60, $topPos - 40);
 $page->drawText('Cashier.....:', $leftPos + 10, $topPos - 50);
 $page->drawText($_REQUEST['cashier'], $leftPos + 60, $topPos - 50);
 $page->drawText('Cash Point..:', $leftPos + 10, $topPos - 60);
 $page->drawText($_REQUEST['cashpoint'], $leftPos + 60, $topPos - 60);
 $page->drawText('Thank`s and wish you a quick recovery', $leftPos + 30, $topPos - 80);
 $page->drawText('__________________________________________', $leftPos + 10, $topPos - 120);

    
    
    
    array_push($pdf->pages, $page);
    header('Content-type: application/pdf');
    echo $pdf->render();


?>
