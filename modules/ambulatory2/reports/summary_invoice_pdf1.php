<?php

require ('roots.php');
require ($root_path . 'include/inc_environment_global.php');
$pid = $_REQUEST['pid'];
$receipt = $_REQUEST['receipt'];
createInvoiceTitle($db, $pid, $receipt);

function createInvoiceTitle($db, $pid, $receipt) {
    require ('roots.php');
    require_once 'Zend/Pdf.php';
    $pdf = new Zend_Pdf ();
    $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
    require '../../../include/care_api_classes/class_ward.php';
    //require('../../../include/class_ward.php');
    //require('../../../include/care_api_classes/class_encounter.php');
    $wrd = new Ward ();
    // $obj_enr=new Encounter();

    $pageHeight = $page->getHeight();
    $width = $page->getWidth();
    $topPos = $pageHeight - 10;
    $leftPos = 36;
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

    $headlineStyle = new Zend_Pdf_Style ();
    $headlineStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $headlineStyle->setFont($font, 10);
    $page->setStyle($headlineStyle);
    $page->drawText($company, $leftPos + 36, $topPos - 36);
    $page->drawText($address, $leftPos + 36, $topPos - 50);
    $page->drawText($town . ' - ' . $postal, $leftPos + 36, $topPos - 65);
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
    $headlineStyle4->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
    $headlineStyle4->setFont($font, 10);
    $page->setStyle($headlineStyle4);
    $page->drawText('Patient No:', $leftPos + 36, $topPos - 100);
    $page->drawText('Name:      ', $leftPos + 36, $topPos - 115);
    $page->drawText('Address:   ', $leftPos + 36, $topPos - 130);
    $page->drawText('Town:      ', $leftPos + 36, $topPos - 145);
    $page->drawText('Phone:     ', $leftPos + 36, $topPos - 160);

    $page->drawText('Admission Date: ', $leftPos + 330, $topPos - 100);


    $sql2 = "SELECT
    care_ke_billing.pid
    ,care_ke_billing.encounter_nr
    , care_person.name_first
    , care_person.name_2
    , care_person.name_last
    , care_person.date_birth
    , care_person.addr_zip
    , care_person.cellphone_1_nr
    , care_person.citizenship
    , care_ke_billing.`IP-OP`
    ,care_ke_billing.bill_number
FROM
    care_ke_billing
    INNER JOIN care_person
        ON (care_ke_billing.pid = care_person.pid)
WHERE (care_ke_billing.`IP-OP`='2' and care_ke_billing.pid='" . $pid . "')";

    $info_result = $db->Execute($sql2);

    if ($info_result) {
        $patient_data = $info_result->FetchRow();

        $headlineStyle = new Zend_Pdf_Style ();
        $headlineStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
        $headlineStyle->setFont($font, 10);
        $page->setStyle($headlineStyle);
        $page->drawText($patient_data ['pid'], $leftPos + 110, $topPos - 100);
        $page->drawText(ucfirst(strtolower($patient_data ['name_first'])) . ' ' . ucfirst(strtolower($patient_data ['name_2'])) . ' ' . ucfirst(strtolower($patient_data ['name_last'])), $leftPos + 110, $topPos - 115);
        $page->drawText('P.O. Box ' . ucfirst(strtolower($patient_data ['addr_zip'])), $leftPos + 110, $topPos - 130);
        $page->drawText(ucfirst(strtolower($patient_data ['citizenship'])) . '    Postal code ' . $postal, $leftPos + 110, $topPos - 145);
        $page->drawText($patient_data ['cellphone_1_nr'], $leftPos + 110, $topPos - 160);

        $row2 = $wrd->EncounterLocationsInfo2($patient_data ['encounter_nr']);
        $admDate = $row2['date_from'];

        $page->drawText($admDate, $leftPos + 430, $topPos - 100);
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
    //$page->drawText ( 'Date', $leftPos + 36, $topPos - 185 );
    $page->drawText('Service Description:', $leftPos + 36, $topPos - 185);
    $page->drawText('Total', $leftPos + 270, $topPos - 185);

    $sql3 = "SELECT
      service_type
    , sum(price) as price
    , sum(qty) as qty
    , sum(total) as total
FROM
    care_ke_billing
WHERE (pid ='" . $pid . "' AND service_type not in ('payment','payment adjustment','NHIF') and `ip-op`=2)  group by service_type";

    $results = $db->Execute($sql3);
    $resultsStyle = new Zend_Pdf_Style ();
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $resultsStyle->setFont($font, 9);
    $page->setStyle($resultsStyle);

    while ($row3 = $results->FetchRow()) {

        $page->drawText($row3['service_type'], $leftPos + 36, $topPos - 200);

        $page->drawText( number_format($row3['total'],2), $leftPos + 270, $topPos - 200);
        $topPos = $topPos - 20;
    }

    $sql4 = "SELECT sum(total) as total FROM care_ke_billing WHERE pid = '$pid' and `IP-OP`=2 and 
        service_type not in ('payment','payment adjustment','NHIF')";

    $results = $db->Execute($sql4);
    $row4 = $results->FetchRow();
    $totalBill =  number_format($row4['total'],2);

    $page->drawRectangle($leftPos + 32, $topPos - 210, $leftPos + 500, $topPos - 210, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 10);
    $page->drawText('Total Bill: ' . $totalBill, $leftPos + 230, $topPos - 220);
    $page->drawRectangle($leftPos + 32, $topPos - 230, $leftPos + 500, $topPos - 230, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

    $sqli = "SELECT * FROM care_ke_billing WHERE (pid ='" . $pid . "' AND 
                    service_type in ('NHIF') and `ip-op`=2)";
    $resultsi = $db->Execute($sqli);
    $rowi = $resultsi->FetchRow();
    $resultsStyle = new Zend_Pdf_Style ();
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $resultsStyle->setFont($font, 10);
    $page->setStyle($resultsStyle);
    $page->drawText($rowi['Description'] . "(" . intval($rowi['batch_no']) . ")", $leftPos + 100, $topPos - 245);
    $page->drawText(' Ksh.' . number_format($rowi['total'], 2), $leftPos + 250, $topPos - 245);


    $sqli = "SELECT * FROM care_ke_billing WHERE (pid ='" . $pid . "' AND 
                    service_type in ('payment','payment adjustment') and `ip-op`=2)";

    $resultsi = $db->Execute($sqli);
    $resultsStyle = new Zend_Pdf_Style ();
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $resultsStyle->setFont($font, 9);
    $page->setStyle($resultsStyle);

    if ($receipt <> '') {
        while ($rowi = $resultsi->FetchRow()) {
            $page->drawText($rowi['prescribe_date'], $leftPos + 36, $topPos - 265);

            $page->drawText($rowi['Description'], $leftPos + 70, $topPos - 265);
            $page->drawText('receipt No ( ', $leftPos + 160, $topPos - 265);
            $page->drawText($rowi['batch_no'] . ' )', $leftPos + 265, $topPos - 265);
            $page->drawText('Ksh', $leftPos + 350, $topPos - 265);
            $page->drawText(number_format($rowi['total'], 2), $leftPos + 380, $topPos - 265);
            $topPos = $topPos - 15;
        }
    }

    $page->drawLine($leftPos + 32, $topPos - 265, $leftPos + 500, $topPos - 265, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

    $sql5 = "SELECT sum(total) as total FROM care_ke_billing WHERE pid = '$pid' AND 
 service_type in ('payment','payment adjustment','NHIF') and `ip-op`=2";

    $results5 = $db->Execute($sql5);
    $row5 = $results5->FetchRow();
    $totalPaid = $row5['total'];

    $resultsStyle = new Zend_Pdf_Style ();
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
    $resultsStyle->setFont($font, 10);
    $page->setStyle($resultsStyle);

//        if($receipt<>''){
//            $page->drawText ( 'Total Paid:', $leftPos + 320, $topPos - 260 );
//            $page->drawText ( ' Ksh. '.$row5['total'], $leftPos + 380, $topPos - 260 );
//        }
//        
    $page->drawText('AMOUNT DUE:', $leftPos + 180, $topPos - 280);
    $page->drawText(' Ksh. ' . number_format(intval($totalBill - $totalPaid), 2), $leftPos + 250, $topPos - 280);
    $page->drawLine($leftPos + 32, $topPos - 250, $leftPos + 500, $topPos - 250, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

//    $strMsg = 'PLEASE BE ADVISED:';
//    $strMsg2 = 'This is a provisional bill, final invoice may include charges not billed at the time of discharge, Payment for such';
//    $strMsg2.='charges will be expected. NHIF rebate will be claimed at the time of discharge';
//    $entrydata = strip_tags($strMsg2);
//    $entrydata = wordwrap($entrydata, 90, '\n');
//    $articleArray = explode('\n', $entrydata);
//
//    $page->drawText($strMsg, $leftPos + 32, $topPos - 300);
//    foreach ($articleArray as $line) {
//        $page->drawText($line, $leftPos + 32, $topPos - 320);
//        $topPos = $topPos - 16;
//    }
//    $strMsg4 = 'E&OE';
    
//    $page->drawText($strMsg2, $leftPos + 32, $topPos - 320);
    $page->drawText($strMsg4, $leftPos + 32, $topPos - 370);

    $pdf->pages [0] = ($page);
    header('Content-type: application/pdf');
    echo $pdf->render();
}

?>
