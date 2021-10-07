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

    $title = 'PAYMENTS REPORT';

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
    $page->drawText($title, $leftPos + 230, $topPos - 36);
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
    $page->drawRectangle($leftPos + 820, $topPos - 115, $leftPos + 580, $topPos - 810, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

    $page->drawText('Payment Date:', $leftPos + 11, $topPos - 110);
    $page->drawText('VoucherNo', $leftPos + 90, $topPos - 110);
    $page->drawText('Cheque No', $leftPos + 200, $topPos - 110);
    $page->drawText('GL Code', $leftPos + 250, $topPos - 110);
    $page->drawText('Description', $leftPos + 300, $topPos - 110);
    $page->drawText('Amount', $leftPos + 520, $topPos - 110);

    $currpoint = 125;

    $sql = "SELECT  CASH_POINT,PDATE,Voucher_No,cheque_no,ledger_code,ledger_desc,Amount FROM CARE_KE_PAYMENTS WHERE CASH_POINT='$cashpoint' AND PDATE BETWEEN '$date1' AND '$date2'";

    $result = $db->Execute($sql);

    $resultsStyle = new Zend_Pdf_Style ();
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $resultsStyle->setFont($font, 8);
    $page->setStyle($resultsStyle);

    $Total=0;
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

        }
        $page->setStyle($resultsStyle);

        if ($row[type] == 'RC') {
            $rctype = 'Receipt';
        } else if ($row[type] == 'RCJ') {
            $rctype = 'Receipt Adjustment';
        }
        $page->drawText($row ['PDATE'], $leftPos + 11, $topPos - $currpoint);
        $page->drawText($row ['Voucher_No'], $leftPos + 90, $topPos - $currpoint);
        $page->drawText($row ['cheque_no'], $leftPos + 200, $topPos - $currpoint);
        $page->drawText($row ['ledger_code'], $leftPos + 250 , $topPos - $currpoint);
        $page->drawText($row ['ledger_desc'], $leftPos + 300, $topPos - $currpoint);
//        $page->drawText(number_format($row['Amount'],2), $leftPos + 500, $topPos - $currpoint);
        $pdfBase->drawText($page,  number_format($row['Amount'], 2), $leftPos + 550, $topPos - $currpoint,$leftPos + 550,right);

        $Total=$Total+$row ['Amount'];

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

    $page->drawText('Totals:', $leftPos + 480, $topPos - 30);
    $pdfBase->drawText($page,  number_format($Total, 2), $leftPos + 550, $topPos - 30,$leftPos + 550,right);

    $topPos = $topPos - 10;
    array_push($pdf->pages, $page);
    header('Content-type: application/pdf');
    echo $pdf->render();
}

?>
