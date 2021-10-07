<?php

require ('roots.php');
require ($root_path . 'include/inc_environment_global.php');
$pid = $_REQUEST ['pid'];
$cashpoint = $_REQUEST ['cashpoint'];
$shiftno = $_REQUEST ['shiftno'];

require_once 'Zend/Pdf.php';
$pdf = new Zend_Pdf ();
require ($root_path . 'include/care_api_classes/Library_Pdf_Base.php');
$pdfBase = new Library_Pdf_Base();

createInvoiceTitle($db, $cashpoint, $shiftno, $pdf, $pdfBase);

function createInvoiceTitle($db, $cashpoint, $shiftno, $pdf, $pdfBase) {
    require ('roots.php');

    $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
    require '../../../include/care_api_classes/class_ward.php';
    //require('../../../include/class_ward.php');
    //require('../../../include/care_api_classes/class_encounter.php');
    $wrd = new Ward ();
    // $obj_enr=new Encounter()

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

    $title = 'SHIFT REPORT BREAKDOWN';

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
    $page->drawText($title, $leftPos + 180, $topPos - 36);
    $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD), 9);

    $sql1 = "SELECT shift_no , cash_point, cashier, start_date, start_time, end_date , end_time,active
FROM
    care_ke_shifts where cash_point='$cashpoint' and shift_no='$shiftno'";
    $result1 = $db->Execute($sql1);
    $row4 = $result1->FetchRow();
    if ($row4['active'] == 1) {
        $strStatus = 'In Progress';
    } else {
        $strStatus = ',is Closed';
    }
    $date1 = new DateTime($row4['start_date']);
    $date2 = new DateTime($row4['end_date']);
    $page->drawText('Start Date:  ' . $date1->format('d-m-Y') . ' ' . $row4['start_time'], $leftPos + 400, $topPos - 20);
    $page->drawText('End Date:   ' . $date2->format('d-m-Y') . ' ' . $row4['end_time'], $leftPos + 400, $topPos - 36);
    $page->drawText('Cash Point: ' . $row4['cash_point'], $leftPos + 400, $topPos - 50);
    $page->drawText('Shift No:   ' . $row4['shift_no'] . ' ' . $strStatus, $leftPos + 400, $topPos - 65);
    $page->drawText('Cashier:    ' . $row4['cashier'], $leftPos + 400, $topPos - 80);

    $resultsStyle = new Zend_Pdf_Style ();
    $resultsStyle->setLineDashingPattern(array(2), 1.6);
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
    $resultsStyle->setFillColor(new Zend_Pdf_Color_RGB(1, 1, 1));
    $resultsStyle->setFont($font, 9);
    $page->setStyle($resultsStyle);

    $page->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0.7));


    $topPos=$topPos-60;

    $page->drawRectangle($leftPos + 35, $topPos - 40, $leftPos + 700, $topPos - 40, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);
//    $page->drawRectangle($leftPos + 35, $topPos - 55, $leftPos + 400, $topPos - 70, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);
//    $page->setFillColor(new Zend_Pdf_Color_RGB(1, 1, 1));
    $page->drawRectangle($leftPos + 35, $topPos - 73, $leftPos + 400, $topPos - 86, Zend_Pdf_Page::SHAPE_DRAW_FILL);
    $page->setFillColor(new Zend_Pdf_Color_RGB(1, 1, 1));
    //$page->drawText('rev code', $leftPos + 40, $topPos - 80);
    $page->drawText('DESCRIPTION', $leftPos + 40, $topPos - 82);
    $page->drawText('PAYMENT MODE', $leftPos + 240, $topPos - 82);
    $page->drawText('AMOUNT', $leftPos + 325, $topPos - 82);

    $sql5 = "SELECT r.rev_code,r.rev_desc,r.pay_mode, SUM(r.total) AS total,r.prec_desc FROM care_ke_receipts r 
            WHERE r.cash_point='$cashpoint' and r.shift_no='$shiftno'
                group by r.rev_desc";
    $tresult = $db->Execute($sql5);

    $resultsStyle3 = new Zend_Pdf_Style ();
    $resultsStyle3->setLineDashingPattern(array(2), 1.6);
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $resultsStyle3->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $resultsStyle3->setFont($font, 9);
    $page->setStyle($resultsStyle3);

    $total=0;
    while ($row = $tresult->FetchRow()) {
        if ($topPos < 150) {
            array_push($pdf->pages, $page);
            $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
            $resultsStyle = new Zend_Pdf_Style ();
            $resultsStyle->setLineDashingPattern(array(2), 1);
            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
            $resultsStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
            $resultsStyle->setFont($font, 9);
            $page->setStyle($resultsStyle);
            $pageHeight = $page->getHeight();
            $topPos = $pageHeight + 5;
            $currpoint = 5;
            $page->drawRectangle($leftPos + 32, $topPos - 5, $leftPos + 700, $topPos - 810, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
        }

        if($row[rev_desc]<>""){
            $desc=$row[rev_desc];
        }else{
            $desc=$row[rev_code].' - '.$row[prec_desc];
        }
        $page->drawText( strtoupper($desc), $leftPos + 40, $topPos - 95);
//		$page->drawText ( $row [1], $leftPos + 200, $topPos - 95 );
        $page->drawText($row ['pay_mode'], $leftPos + 250, $topPos - 95);
        $amount=$row['total'];
        $page->drawText(number_format($amount,2), $leftPos + 330, $topPos - 95);

        $topPos = $topPos - 20;
        $total=$total+$amount;
    }
    $resultsStyle3 = new Zend_Pdf_Style ();
    $resultsStyle3->setLineDashingPattern(array(2), 1.6);
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
    $resultsStyle3->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $resultsStyle3->setFont($font, 9);
    $page->setStyle($resultsStyle3);

    $page->drawText('TOTAL:', $leftPos + 250, $topPos - 95);
    $page->drawText(number_format(intval($total), 2), $leftPos + 330, $topPos - 95);
    $topPos = $topPos - 10;
    array_push($pdf->pages, $page);
    header('Content-type: application/pdf');
    echo $pdf->render();
}

?>
