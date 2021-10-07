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
    $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4_LANDSCAPE);

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
    $page->drawText($title, $leftPos + 350, $topPos - 36);
    $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD), 9);



    //$page->drawRectangle ( $leftPos + 36, $topPos - 170, $leftPos + 500, $topPos - 170, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE );
    //draw row headings
    $rectStyle = new Zend_Pdf_Style ();
//    $rectStyle->setLineDashingPattern(array(2), 1);
    $rectStyle->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
    $rectStyle->setFont($font, 8);
    $page->setStyle($rectStyle);
    $page->drawRectangle($leftPos + 10, $topPos - 95, $leftPos + 820, $topPos - 115, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

    $page->drawRectangle($leftPos + 10, $topPos - 115, $leftPos + 10, $topPos - 810, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    $page->drawRectangle($leftPos + 820, $topPos - 115, $leftPos + 820, $topPos - 810, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

    $page->drawText('Point:', $leftPos + 11, $topPos - 110);
    $page->drawText('VoucherNo', $leftPos + 35, $topPos - 110);
    $page->drawText('PMode', $leftPos + 90, $topPos - 110);
    $page->drawText('Date', $leftPos + 120, $topPos - 110);
    $page->drawText('GL Acc', $leftPos + 210, $topPos - 110);
    $page->drawText('Payee', $leftPos + 270, $topPos - 110);
    $page->drawText('Towards', $leftPos + 350, $topPos - 110);
    $page->drawText('Ledger', $leftPos + 480, $topPos - 110);
    $page->drawText('Ledger Code', $leftPos + 520, $topPos - 110);
    $page->drawText('ledger Desc', $leftPos + 600, $topPos - 110);
    $page->drawText('Amount', $leftPos + 780, $topPos - 110);
    $currpoint = 125;

    $sql = "SELECT cash_point,voucher_no,pay_mode,pdate,gl_acc,gl_desc,cheque_no,payee,toward,ledger,ledger_code,ledger_desc,control FROM care_ke_payments
                WHERE cash_point='$cashpoint' and pdate between '$date1' and '$date2'
                ORDER BY voucher_no DESC";
    //echo $sql;
    $result = $db->Execute($sql);
    //$row = $result->FetchRow ();

    $resultsStyle = new Zend_Pdf_Style ();
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $resultsStyle->setFont($font, 8);
    $page->setStyle($resultsStyle);

    $Total=0;
    while ($row = $result->FetchRow()) {
        if ($topPos < 140) {
            array_push($pdf->pages, $page);
            $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4_LANDSCAPE);
            $resultsStyle = new Zend_Pdf_Style ();
            $resultsStyle->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);

            $resultsStyle->setFont($font, 8);
            $page->setStyle($resultsStyle);
            $pageHeight = $page->getHeight();
            $topPos = $pageHeight - 20;
            $currpoint = 20;
//            $page->drawRectangle($leftPos + 10, $topPos - 20, $leftPos + 10, $topPos - 810, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
//            $page->drawRectangle($leftPos + 580, $topPos - 20, $leftPos + 820, $topPos - 810, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
        }
        $page->setStyle($resultsStyle);

        if ($row[type] == 'RC') {
            $rctype = 'Receipt';
        } else if ($row[type] == 'RCJ') {
            $rctype = 'Receipt Adjustment';
        }
        $page->drawText($row ['cash_point'], $leftPos + 11, $topPos - $currpoint);
        $page->drawText($row ['voucher_no'], $leftPos + 35, $topPos - $currpoint);
        $page->drawText($row ['pay_mode'], $leftPos + 90, $topPos - $currpoint);
        $page->drawText($row ['pdate'], $leftPos + 120 , $topPos - $currpoint);
        $page->drawText($row ['gl_desc'], $leftPos + 180, $topPos - $currpoint);
        $page->drawText($row ['payee'], $leftPos + 250, $topPos - $currpoint);
        $page->drawText($row ['toward'], $leftPos + 320, $topPos - $currpoint);
        $page->drawText($row ['ledger'], $leftPos + 490, $topPos - $currpoint);
        $page->drawText($row ['ledger_code'], $leftPos + 540, $topPos - $currpoint);
        $page->drawText($row ['ledger_desc'], $leftPos + 580, $topPos - $currpoint);
//        $page->drawText($row ['control'], $leftPos + 780, $topPos - $currpoint);
        $pdfBase->drawText($page,  number_format($row ['control'],2), $leftPos + 810, $topPos - $currpoint,$leftPos + 810,right);

        $Total=$Total+$row ['control'];

        $lineStyle3 = new Zend_Pdf_Style ();
        $lineStyle3->setLineDashingPattern(array(2), 0.6);
        $lineStyle3->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
        $page->setStyle($lineStyle3);

       $page->drawRectangle($leftPos + 10, $topPos - $currpoint-3, $leftPos + 820, $topPos - $currpoint-3, Zend_Pdf_Page::SHAPE_DRAW_FILL);
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

    $page->drawText('Totals:', $leftPos + 720, $topPos - 30);
    $pdfBase->drawText($page,  number_format($Total, 2), $leftPos + 810, $topPos - 30,$leftPos + 810,right);

    $topPos = $topPos - 10;
    array_push($pdf->pages, $page);
    header('Content-type: application/pdf');
    echo $pdf->render();
}

?>
