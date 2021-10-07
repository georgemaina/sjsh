<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require ('roots.php');
require ($root_path . 'include/inc_environment_global.php');
$pid = $_REQUEST['pid'];
$q = $_GET["q"];
$r = $_GET["r"];

require '../../../include/care_api_classes/class_ward.php';
$wrd = new Ward ();
// $obj_enr=new Encounter();

require ('roots.php');
require_once 'Zend/Pdf.php';
$pdf = new Zend_Pdf ();

    $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
    $pageHeight = $page->getHeight();
    $width = $page->getWidth();
    $topPos = $pageHeight - 10;
    $leftPos = 36;

$accno =  $_GET["accno"];
$date1 = $_REQUEST["date1"];
$date2 = $_REQUEST["date2"];
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

    $sql3 = "SELECT b.`ip-op`, p.pid,p.name_first,p.name_last,p.name_2,b.bill_date,b.bill_number,SUM(b.total) AS total,
            e.encounter_date,e.discharge_date,
        p.addr_zip,p.phone_1_nr,p.date_birth,p.citizenship  FROM  care_ke_billing b INNER JOIN care_person p 
            ON b.pid=p.pid INNER JOIN care_encounter e ON e.pid=p.pid
            INNER JOIN care_tz_company c ON c.id=p.insurance_ID
            INNER JOIN care_ke_debtors d ON c.accno=d.accno
            WHERE c.accno='$accno'";
    

        if ($dt1 <> "" && $dt2 <> "") {
            $sql3 = $sql3 . " and b.bill_date between '$dt1' and '$dt2' ";
        } else if ($dt1 <> '' && $dt2 == '') {
            $sql3 = $sql3 . " and b.bill_date = '$dt1'";
        } else {
            $sql3 = $sql3 . " and b.bill_date<=now()";
        }
        $sql3=$sql3. " GROUP BY b.bill_number ORDER BY b.bill_date asc";
//        echo $sql3;
        
    $results = $db->Execute($sql3);
    while ($row = $results->FetchRow()) {
        getPages($page,$topPos,$leftPos,$row[1],$row['bill_number']);
        
//        if ($topPos < 230) {
            array_push($pdf->pages, $page);
            $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
            $resultsStyle = new Zend_Pdf_Style ();
            $resultsStyle->setLineDashingPattern(array(2), 1.6);
            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
            $resultsStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
            $resultsStyle->setFont($font, 9);
            $page->setStyle($resultsStyle);
            $pageHeight = $page->getHeight();
            $topPos = $pageHeight - 20;
            $currpoint = 30;
    //}
    }

        
function getPages($page,$topPos,$leftPos,$pid,$bill_number){
    global $db;
    $config_type = 'main_info_%';
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

    $title = 'INVOICE';
    
    
    $headlineStyle = new Zend_Pdf_Style();
    $headlineStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0.9));
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $headlineStyle->setFont($font, 10);
    $page->setStyle($headlineStyle);
    $page->drawText($company, $leftPos + 36, $topPos - 36);
    $page->drawText($address, $leftPos + 36, $topPos - 50);
    $page->drawText(trim($town) . '-' . trim($postal), $leftPos + 36, $topPos - 65);
    $page->drawText($tel, $leftPos + 36, $topPos - 80);

    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
    $headlineStyle2 = new Zend_Pdf_Style ();
    $headlineStyle2->setFont($font, 13);
    $page->setStyle($headlineStyle2);
    $page->drawText($title, $leftPos + 250, $topPos - 36);
    $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD), 9);

    $page->drawText('Date:  ' . date('d-m-Y'), $leftPos + 400, $topPos - 50);
    $page->drawRectangle($leftPos + 36, $topPos - 90, $leftPos + 500, $topPos - 90, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);

    $headlineStyle4 = new Zend_Pdf_Style ();
    $headlineStyle4->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0.9));
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
    $headlineStyle4->setFont($font, 10);
    $page->setStyle($headlineStyle4);
    $page->drawText('Patient No:', $leftPos + 36, $topPos - 100);
    $page->drawText('Name:      ', $leftPos + 36, $topPos - 115);
    $page->drawText('Address:   ', $leftPos + 36, $topPos - 130);
    $page->drawText('Town:      ', $leftPos + 36, $topPos - 145);
    $page->drawText('Phone:     ', $leftPos + 36, $topPos - 160);

//	$page->drawText ( 'Admission Date: ', $leftPos + 330, $topPos - 100 );
//	$page->drawText ( 'Discharge Date: ', $leftPos + 330, $topPos - 115 );
    $page->drawText('Invoice No:    ', $leftPos + 405, $topPos - 36);
    $page->drawText('Account No:', $leftPos + 265, $topPos - 130);
    $page->drawText('Account:', $leftPos + 265, $topPos - 145);


    $sql2 = "SELECT DISTINCT b.pid,b.encounter_nr,c.`accno`,c.`name`, p.name_first, p.name_2
    , p.name_last, p.date_birth, p.addr_zip, p.cellphone_1_nr, p.citizenship
    , b.`IP-OP`,b.bill_number FROM care_ke_billing B INNER JOIN care_person p
    ON (b.pid = p.pid) LEFT JOIN care_tz_company c ON p.insurance_id=c.ID
    WHERE (b.pid='$pid') and b.bill_number='$bill_number'";
//	echo $sql2;
    $info_result = $db->Execute($sql2);

    if ($info_result) {
        $patient_data = $info_result->FetchRow();

        $headlineStyle = new Zend_Pdf_Style ();
        $headlineStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0.9));
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
        $headlineStyle->setFont($font, 10);
        $page->setStyle($headlineStyle);
        $page->drawText($patient_data ['pid'], $leftPos + 110, $topPos - 100);
        $page->drawText(ucfirst(strtolower($patient_data ['name_first'])) . ' ' . ucfirst(strtolower($patient_data ['name_2'])) . ' ' . ucfirst(strtolower($patient_data ['name_last'])), $leftPos + 110, $topPos - 115);
        $page->drawText('P.O. Box ' . ucfirst(strtolower($patient_data ['addr_zip'])), $leftPos + 110, $topPos - 130);
        $page->drawText(ucfirst(strtolower($patient_data ['citizenship'])) . 'Postal code ' . $postal, $leftPos + 110, $topPos - 145);
        $page->drawText($patient_data ['cellphone_1_nr'], $leftPos + 110, $topPos - 160);

//        $row2 = $wrd->EncounterLocationsInfo2($patient_data ['encounter_nr']);
//        $admDate = $row2 [7];
//        $Disc_date = $row2 [8];
//		$page->drawText ( $admDate, $leftPos + 430, $topPos - 100 );
//		$page->drawText ( $Disc_date, $leftPos + 430, $topPos - 115 );
        $page->drawText($patient_data ['bill_number'], $leftPos + 460, $topPos - 36);

        $page->drawText($patient_data ['accno'], $leftPos + 330, $topPos - 130);
        $page->drawText($patient_data ['name'], $leftPos + 330, $topPos - 145);
    } else {
        $page->drawText('Cannot connect database', $leftPos + 400, $topPos - 160);
    }

//$page->drawRectangle ( $leftPos + 36, $topPos - 170, $leftPos + 500, $topPos - 170, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE );
//draw row headings
    $rectStyle = new Zend_Pdf_Style ();
    $rectStyle->setLineDashingPattern(array(2), 1.6);
    $rectStyle->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
    $rectStyle->setFont($font, 10);
    $page->setStyle($rectStyle);
    $page->drawRectangle($leftPos + 32, $topPos - 175, $leftPos + 500, $topPos - 188, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    $page->drawRectangle($leftPos + 32, $topPos - 175, $leftPos + 500, $topPos - 800, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    $page->drawText('Date', $leftPos + 36, $topPos - 185);
    $page->drawText('Service Description:', $leftPos + 100, $topPos - 185);
    $page->drawText('ref No', $leftPos + 270, $topPos - 185);
    $page->drawText('Price', $leftPos + 330, $topPos - 185);
    $page->drawText('Quantity', $leftPos + 380, $topPos - 185);
    $page->drawText('Total', $leftPos + 450, $topPos - 185);

    $sql3 = "SELECT
    prescribe_date
    , description
    , bill_number
    , price
    , qty
    , total
FROM
    care_ke_billing
WHERE (pid ='" . $pid . "') and bill_number='$bill_number'";

    $results = $db->Execute($sql3);
    $resultsStyle = new Zend_Pdf_Style ();
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $resultsStyle->setFont($font, 9);
    $page->setStyle($resultsStyle);

    while ($row = $results->FetchRow()) {
        $page->drawText($row['prescribe_date'], $leftPos + 36, $topPos - 200);
        $page->drawText($row['description'], $leftPos + 100, $topPos - 200);
        $page->drawText($row['bill_number'], $leftPos + 270, $topPos - 200);
        $page->drawText(number_format($row['price'], 2), $leftPos + 330, $topPos - 200);
        $page->drawText($row['qty'], $leftPos + 380, $topPos - 200);
        $page->drawText(number_format($row['total'], 2), $leftPos + 450, $topPos - 200);
        $topPos = $topPos - 20;
    }
    $page->drawRectangle($leftPos + 32, $topPos - 210, $leftPos + 500, $topPos - 210, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    $sql4 = "SELECT sum(total) as total FROM care_ke_billing WHERE pid = '$pid' and bill_number='$bill_number'";

    $results = $db->Execute($sql4);
    $row = $results->FetchRow();
    $resultsStyle = new Zend_Pdf_Style ();
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
    $resultsStyle->setFont($font, 9);
    $page->setStyle($resultsStyle);
    $page->drawText('Total', $leftPos + 380, $topPos - 220);
    $page->drawText('Ksh. ' . number_format($row['total'], 2), $leftPos + 430, $topPos - 220);
    $page->drawRectangle($leftPos + 32, $topPos - 230, $leftPos + 500, $topPos - 230, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
}

$topPos = $topPos - 10;
array_push($pdf->pages, $page);
header('Content-type: application/pdf');
echo $pdf->render();



//}
?>
