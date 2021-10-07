<?php

require ('roots.php');
require ($root_path . 'include/inc_environment_global.php');
require_once 'Zend/Pdf.php';
$pdf = new Zend_Pdf ();
require ($root_path . 'include/care_api_classes/Library_Pdf_Base.php');
$pdfBase=new Library_Pdf_Base();    


$pid = $_REQUEST ['pid'];
$cashpoint = $_REQUEST ['cashpoint'];
$shiftno = $_REQUEST ['shiftno'];
$strDate1= new DateTime($_REQUEST[date1]);
        $date1 = $strDate1->format("Y-m-d");
        
        $strDate2= new DateTime($_REQUEST[date2]);
        $date2 = $strDate2->format("Y-m-d");
//   $date1='2013-04-01';
//   $date2='2013-04-15';
    
createInvoiceTitle($db, $cashpoint, $shiftno,$date1,$date2,$pdf,$pdfBase);

function createInvoiceTitle($db, $cashpoint, $shiftno,$date1,$date2,$pdf,$pdfBase) {
//    require ('roots.php');
//    require_once 'Zend/Pdf.php';
//    $pdf = new Zend_Pdf ();
    $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);

    $pageHeight = $page->getHeight();
    $width = $page->getWidth();
    $topPos = $pageHeight - 10;
    $leftPos = 5;
    $config_type = 'main_info_%';
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

    $title = 'CASH COLLECTION SUMMARY REPORT';

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
    $page->drawText($title, $leftPos + 180, $topPos - 20);
    $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD), 9);


   
    //$page->drawRectangle ( $leftPos + 36, $topPos - 170, $leftPos + 500, $topPos - 170, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE );
    //draw row headings
    $rectStyle = new Zend_Pdf_Style ();
//    $rectStyle->setLineDashingPattern(array(2), 1);
    $rectStyle->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
    $rectStyle->setFont($font, 8);
    $page->setStyle($rectStyle);
    $page->drawRectangle($leftPos + 10, $topPos - 95, $leftPos + 580, $topPos - 115, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    
    $page->drawRectangle($leftPos + 10, $topPos - 115, $leftPos + 10, $topPos - 810, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    $page->drawRectangle($leftPos + 580, $topPos - 115, $leftPos + 580, $topPos - 810, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    
    $page->drawText('Point:', $leftPos + 11, $topPos - 110);
    $page->drawText('Shift', $leftPos + 35, $topPos - 110);
    $page->drawText('Type', $leftPos + 70, $topPos - 110);
    $page->drawText('Cashier', $leftPos + 100, $topPos - 110);
    $page->drawText('Start Date', $leftPos + 210, $topPos - 110);
    $page->drawText('Start Time', $leftPos + 270, $topPos - 110);
    $page->drawText('End Date', $leftPos + 315, $topPos - 110);
    $page->drawText('End Time', $leftPos + 370, $topPos - 110);
    $page->drawText('Amount', $leftPos + 420, $topPos - 110);
    $page->drawText('Cheques', $leftPos + 470, $topPos - 110);
    $page->drawText('Others', $leftPos + 530, $topPos - 110);
    $currpoint = 125;

    $sql = "SELECT r.Cash_point,r.Shift_no,r.type,s.`start_date`,s.`start_time`,s.`end_date`,s.`end_time`,username,pay_mode,SUM(total) AS total FROM care_ke_receipts r 
                LEFT JOIN care_ke_shifts s ON r.`Shift_no`=s.shift_no AND r.`cash_point`=s.cash_point
                WHERE s.`start_date` BETWEEN '$date1' AND '$date2'
                GROUP BY s.shift_no,s.cash_point
                ORDER BY s.`shift_no` DESC";
    $result = $db->Execute($sql);
    //$row = $result->FetchRow ();

    $resultsStyle = new Zend_Pdf_Style ();
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $resultsStyle->setFont($font, 8);
    $page->setStyle($resultsStyle);

    $amount = 0;
    $othesTotal = 0;
    $chqTotal = 0;
    while ($row = $result->FetchRow()) {
        if ($topPos < 140) {
            array_push($pdf->pages, $page);
            $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
            $resultsStyle = new Zend_Pdf_Style ();
            $resultsStyle->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);

            $resultsStyle->setFont($font, 8);
            $page->setStyle($resultsStyle);
            $pageHeight = $page->getHeight();
            $topPos = $pageHeight - 20;
            $currpoint = 20;
            $page->drawRectangle($leftPos + 10, $topPos - 20, $leftPos + 10, $topPos - 810, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
            $page->drawRectangle($leftPos + 580, $topPos - 20, $leftPos + 580, $topPos - 810, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
        }
        $page->setStyle($resultsStyle);

        if ($row[type] == 'RC') {
            $rctype = 'Receipt';
        } else if ($row[type] == 'RCJ') {
            $rctype = 'Receipt Adjustment';
        }
        $page->drawText($row ['Cash_point'], $leftPos + 11, $topPos - $currpoint);
        $page->drawText($row ['Shift_no'], $leftPos + 35, $topPos - $currpoint);
        $page->drawText($rctype, $leftPos + 60, $topPos - $currpoint);
        $page->drawText($row ['username'], $leftPos + 100, $topPos - $currpoint);
        $page->drawText($row ['start_date'], $leftPos + 210, $topPos - $currpoint);
        $page->drawText($row['start_time'], $leftPos + 270, $topPos - $currpoint);
        $page->drawText($row ['end_date'], $leftPos + 315, $topPos - $currpoint);
        $page->drawText($row ['end_time'], $leftPos + 370, $topPos - $currpoint);

        if (strtoupper($row['pay_mode']) == 'CAS') {
            $cas =  number_format($row['total'], 2);
            $amount = $amount + $row['total'];
        } else {
            $cas = "";
        }
//        $page->drawText($cas, $leftPos + 420, $topPos - $currpoint);
        $pdfBase->drawText($page,$cas, $leftPos + 460, $topPos - $currpoint,$leftPos + 450,right);

        if (strtoupper($row['pay_mode']) == 'CHQ') {
            $chq = number_format($row['total'], 2);
            $chqTotal = $chqTotal + $row['total'];
        } else {
            $chq = "";
        }
//        $page->drawText($chq, $leftPos + 470, $topPos - $currpoint);
        $pdfBase->drawText($page,$chq, $leftPos + 510, $topPos - $currpoint,$leftPos + 510,right);

        if (strtoupper($row['pay_mode']) == 'MPESA' || strtoupper($row['pay_mode']) == 'VISA' || $row['pay_mode'] == '') {
            $others =  number_format($row['total'], 2);
            $othesTotal = $othesTotal + $row['total'];
        } else {
            $others = "";
        }
        $page->drawText($others, $leftPos + 530, $topPos - $currpoint);
        
        $lineStyle3 = new Zend_Pdf_Style ();
        $lineStyle3->setLineDashingPattern(array(2), 0.6);
        $lineStyle3->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
        $page->setStyle($lineStyle3);
        
       $page->drawRectangle($leftPos + 10, $topPos - $currpoint-3, $leftPos + 580, $topPos - $currpoint-3, Zend_Pdf_Page::SHAPE_DRAW_FILL);
        $topPos = $topPos - 20;
    }
    //end of while loop
    $topPos = $topPos - $currpoint;

    $resultsStyle3 = new Zend_Pdf_Style ();
//    $resultsStyle3->setLineDashingPattern(array(2), 1.6);
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
    $resultsStyle3->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $resultsStyle3->setFont($font, 8);
    $page->setStyle($resultsStyle3);

    $page->drawText('Sub Totals:', $leftPos + 350, $topPos - 10);
//    $page->drawText(number_format($amount, 2), $leftPos + 420, $topPos - 10);
    $pdfBase->drawText($page,  number_format($amount,2), $leftPos + 460, $topPos - 10,$leftPos + 450,right);
//    $page->drawText(number_format($chqTotal, 2), $leftPos + 470, $topPos - 10);
    $pdfBase->drawText($page,  number_format($chqTotal,2), $leftPos + 510, $topPos - 10,$leftPos + 510,right);
    $page->drawText(number_format($othesTotal, 2), $leftPos + 530, $topPos - 10);
    $page->drawText('Totals:', $leftPos + 350, $topPos - 30);
//    $page->drawText(number_format(intval($amount + $chqTotal + $othesTotal), 2), $leftPos + 420, $topPos - 30);
    $pdfBase->drawText($page,  number_format(intval($amount + $chqTotal + $othesTotal), 2), $leftPos + 460, $topPos - 30,$leftPos + 460,right);


    $topPos = $topPos +60;

    $page->drawText('Checked Signature : ____________________________', $leftPos + 50, $topPos - 200);
    $page->drawText('ID : __________________________________', $leftPos + 300, $topPos - 200);

    $page->drawText('Verified By Signature : ____________________________', $leftPos + 50, $topPos - 240);
    $page->drawText('ID : __________________________________', $leftPos + 300, $topPos - 240);

    $page->drawText('Approved Signature : ____________________________', $leftPos + 50, $topPos - 280);
    $page->drawText('ID : __________________________________', $leftPos + 300, $topPos - 280);

    $page->drawText('Banked By Signature : ____________________________', $leftPos + 50, $topPos - 320);
    $page->drawText('ID : __________________________________', $leftPos + 300, $topPos - 320);

    $topPos = $topPos - 10;
    array_push($pdf->pages, $page);
    header('Content-type: application/pdf');
    echo $pdf->render();
}

?>
