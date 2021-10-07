<?php

require ('roots.php');
require ($root_path . 'include/inc_environment_global.php');
$pid = $_REQUEST['pid'];
$receipt = $_REQUEST['receipt'];
$billNumber = $_GET["billNumber"];
$nhif = $_REQUEST['nhif'];
$date1 = '2013-05-23';
$date2 = '2013-05-23';

createNHIFStatement($db, $date1, $date2);

function createNHIFStatement($db, $date1, $date2) {
    require ('roots.php');
    require_once 'Zend/Pdf.php';
    $pdf = new Zend_Pdf ();
    $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
    require '../../../include/care_api_classes/class_ward.php';
    //require('../../../include/class_ward.php');
    //require('../../../include/care_api_classes/class_encounter.php');
    $wrd = new Ward ();
    // $obj_enr=new Encounter();

    $strMsg1="I enclosed herewith claim forms as detailed to the value of Shs";
    $strMsg2="being benefits allowed to contributions. Please arrange to reimburse this hospital at your earliest convinience";
    
    $pageHeight = $page->getHeight();
    $width = $page->getWidth();
    $topPos = $pageHeight - 10;
    $leftPos = 2;
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

    $title = 'NHIF';

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


    //$page->drawRectangle ( $leftPos + 36, $topPos - 170, $leftPos + 500, $topPos - 170, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE );
    //draw row headings
    $rectStyle = new Zend_Pdf_Style ();
//    $rectStyle->setLineDashingPattern(array(2), 1.6);
    $rectStyle->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
    $rectStyle->setFont($font, 10);
    $page->setStyle($rectStyle);
    $page->setLineWidth(0.5);
    $page->drawRectangle($leftPos + 32, $topPos - 360, $leftPos + 560, $topPos - 800, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    $page->drawRectangle($leftPos + 32, $topPos - 320, $leftPos + 560, $topPos - 360, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    
    $page->drawRectangle($leftPos + 75, $topPos - 320, $leftPos + 75, $topPos - 800, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    $page->drawRectangle($leftPos + 130, $topPos - 320, $leftPos + 130, $topPos - 800, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    $page->drawRectangle($leftPos + 240, $topPos - 320, $leftPos + 240, $topPos - 800, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    $page->drawRectangle($leftPos + 305, $topPos - 320, $leftPos + 305, $topPos - 800, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    $page->drawRectangle($leftPos + 370, $topPos - 320, $leftPos + 305, $topPos - 800, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    $page->drawRectangle($leftPos + 400, $topPos - 320, $leftPos + 400, $topPos - 800, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    $page->drawRectangle($leftPos + 460, $topPos - 320, $leftPos + 460, $topPos - 800, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    $page->drawRectangle($leftPos + 490, $topPos - 320, $leftPos + 490, $topPos - 800, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

    $claimNo = strip_tags('Claim No');
    $claimNo = wordwrap($claimNo, 6, '\n');

    $claimArray = explode('\n', $claimNo);
    
    $currpoint = 500;
    foreach ($claimArray as $line) {
        $line = ltrim($line);
        $page->drawText($line, $leftPos + 36, $currpoint);
        $currpoint = $currpoint - 10;
    }
//    $topPos =$topPos - $currpoint;

    
    $ccpNo = strip_tags('C.C.P No/ Receipt No.');
    $ccpNo = wordwrap($ccpNo, 12, '\n');

    $ccpArray = explode('\n', $ccpNo);

    $currpoint = 500;
    foreach ($ccpArray as $line) {
        $line = ltrim($line);
        $page->drawText($line, $leftPos + 75, $currpoint);
        $currpoint = $currpoint - 10;
    }
//    $topPos =$topPos - $currpoint;
//    $page->drawText('C.C.P No/ Receipt No.', $leftPos + 80, $topPos - 160);
    $page->drawText('Names of Patient', $leftPos + 140, $topPos - 330);

    $nhifNo = strip_tags("Contributor`s Membership Number");
    $nhifNo = wordwrap($nhifNo, 12, '\n');

    $nhifNoArray = explode('\n', $nhifNo);

    $currpoint = 500;
    foreach ($nhifNoArray as $line) {
        $line = ltrim($line);
        $page->drawText($line, $leftPos + 240, $currpoint);
        $currpoint = $currpoint - 10;
    }

//    $page->drawText('Nhif No', $leftPos + 250, $topPos - 185);

    $billNo = strip_tags("Statement Invoice Number");
    $billNo = wordwrap($billNo, 12, '\n');
    $billNoArray = explode('\n', $billNo);
    $currpoint = 500;
    foreach ($billNoArray as $line) {
        $line = ltrim($line);
        $page->drawText($line, $leftPos + 310, $currpoint );
        $currpoint = $currpoint - 10;
    }

//    $page->drawText('pid', $leftPos + 300, $topPos - 185);
    
    $pdays = strip_tags("Patient Days");
    $pdays = wordwrap($pdays, 7, '\n');
    $pdaysArray = explode('\n', $pdays);
    $currpoint = 500;
    foreach ($pdaysArray as $line) {
        $line = ltrim($line);
        $page->drawText($line, $leftPos + 370, $currpoint);
        $currpoint = $currpoint - 10;
    }

    
    $amount = strip_tags("Amount Claimed");
    $amount = wordwrap($amount, 7, '\n');
    $amountArray = explode('\n', $amount);
    $currpoint = 500;
    foreach ($amountArray as $line) {
        $line = ltrim($line);
        $page->drawText($line, $leftPos + 410, $currpoint);
        $currpoint = $currpoint - 10;
    }
    
    $bedNo = strip_tags("Bed No");
    $bedNo = wordwrap($bedNo, 3, '\n');
    $bedNoArray = explode('\n', $bedNo);
    $currpoint = 500;
    foreach ($bedNoArray as $line) {
        $line = ltrim($line);
        $page->drawText($line, $leftPos + 460, $currpoint);
        $currpoint = $currpoint - 10;
    }

    $page->drawText('Remarks', $leftPos + 510, $topPos - 330);


    $topPos=$topPos+100;
    $sql3 = "SELECT creditNo,admno,bill_number,`names`,nhifno,wrddays,totalCredit FROM `care_ke_nhifcredits` 
        WHERE inputdate between '$date1' and '$date2'";
//    echo $sql3;

    $results = $db->Execute($sql3);
    $resultsStyle = new Zend_Pdf_Style ();
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $resultsStyle->setFont($font, 9);
    $page->setStyle($resultsStyle);

    $lineStyle = new Zend_Pdf_Style ();
    $lineStyle->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $lineStyle->setFont($font, 10);
    $page->setLineWidth(0.5);

    while ($row = $results->FetchRow()) {
        if ($topPos < 230) {
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
            $rectStyle = new Zend_Pdf_Style ();
//            $rectStyle->setLineDashingPattern(array(2), 1.6);
            $rectStyle->setLineColor(new Zend_Pdf_Color_GrayScale(1.0));
            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
            $rectStyle->setFont($font, 10);
            $page->setLineWidth(0.5);
            $page->setStyle($rectStyle);
            $page->drawRectangle($leftPos + 32, $topPos - $currpoint, $leftPos + 500, $topPos - $currpoint, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
        }
        $page->drawText($row['creditNo'], $leftPos + 36, $topPos - $currpoint);
        $page->drawText($row['admno'], $leftPos + 80, $topPos - $currpoint);
        $page->drawText(ucwords(strtolower(substr($row['names'], 0, 20))), $leftPos + 140, $topPos - $currpoint);
        $page->drawText($row['nhifno'], $leftPos + 250, $topPos - $currpoint);
        $page->drawText($row['bill_number'], $leftPos + 310, $topPos - $currpoint);
        $page->drawText($row['wrddays'], $leftPos + 380, $topPos - $currpoint);
        $page->drawText($row['totalCredit'], $leftPos + 410, $topPos - $currpoint);
        $page->drawText($row[''], $leftPos + 460, $topPos - $currpoint);
        $page->drawText($row[''], $leftPos + 500, $topPos - $currpoint);
//        $currpoint=$currpoint+10;

        $page->setStyle($lineStyle);

        $page->drawRectangle($leftPos + 32, $topPos - $currpoint - 3, $leftPos + 560, $topPos - $currpoint - 3, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
        $topPos = $topPos - 20;
    }

    $currpoint = $currpoint + 5;
    $page->drawRectangle($leftPos + 32, $topPos - $currpoint, $leftPos + 560, $topPos - $currpoint, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

    $topPos = $topPos - 10;
    array_push($pdf->pages, $page);
    header('Content-type: application/pdf');
    echo $pdf->render();
}

?>
