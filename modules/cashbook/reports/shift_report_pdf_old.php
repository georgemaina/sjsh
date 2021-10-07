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

    $title = 'DETAILED SHIFT REPORT';

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

    $rectStyle = new Zend_Pdf_Style ();
    $rectStyle->setLineDashingPattern(array(2), 1);
    $rectStyle->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
    $rectStyle->setFont($font, 8);
    $page->setStyle($rectStyle);
    $page->drawRectangle($leftPos + 9, $topPos - 95, $leftPos + 580, $topPos - 115, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    $page->drawRectangle($leftPos + 9, $topPos - 95, $leftPos + 580, $topPos - 810, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    $page->drawText('Ref_no:', $leftPos + 10, $topPos - 110);
    $page->drawText('Type', $leftPos + 50, $topPos - 110);
    $page->drawText('Date', $leftPos + 80, $topPos - 110);
    $page->drawText('time', $leftPos + 130, $topPos - 110);
    $page->drawText('Pid', $leftPos + 165, $topPos - 110);
    $page->drawText('Name', $leftPos + 220, $topPos - 110);
    $page->drawText('Code', $leftPos + 382, $topPos - 110);
    $page->drawText('Pay Mode', $leftPos + 415, $topPos - 110);
    $page->drawText('Amount', $leftPos + 460, $topPos - 110);
    $page->drawText('Cheques', $leftPos + 495, $topPos - 110);
    $page->drawText('Others', $leftPos + 540, $topPos - 110);
    $currpoint = 125;
    $sql = "SELECT b.Shift_no,b.ref_no,b.`type`,b.input_time,b.patient,b.name,b.rev_code,b.rev_desc,
                b.proc_code,b.prec_desc,b.payer,b.location,b.pay_mode,b.amount,b.proc_qty,b.total,a.start_date,
                a.start_time,b.currdate,b.pay_mode     
                FROM care_ke_receipts b JOIN care_ke_shifts a  ON b.shift_no=a.shift_no 
		 WHERE b.cash_point='$cashpoint' and b.shift_no='$shiftno' group by sale_id";
    $result = $db->Execute($sql);
    //$row = $result->FetchRow ();

    $resultsStyle = new Zend_Pdf_Style ();
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $resultsStyle->setFont($font, 9);
    $page->setStyle($resultsStyle);

    $casTotal=0;
    $chqTotal=0;
    $othersTotal=0;
    while ($row = $result->FetchRow()) {
        if ($topPos < 150) {
            array_push($pdf->pages, $page);
            $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
            $resultsStyle = new Zend_Pdf_Style ();
            $resultsStyle->setLineDashingPattern(array(2), 1);
            $resultsStyle->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
            $resultsStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
            $resultsStyle->setFont($font, 9);
            $page->setStyle($resultsStyle);
            $pageHeight = $page->getHeight();
            $topPos = $pageHeight - 20;
            $currpoint = 20;
            $page->drawRectangle($leftPos + 9, $topPos - 5, $leftPos + 580, $topPos - 810, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
        }
        $page->drawText($row ['ref_no'], $leftPos + 10, $topPos - $currpoint);
        $page->drawText($row ['type'], $leftPos + 50, $topPos - $currpoint);
        $date = new DateTime($row ['currdate']);
        $page->drawText($date->format('d-m-Y'), $leftPos + 70, $topPos - $currpoint);
        $page->drawText($row ['input_time'], $leftPos + 125, $topPos - $currpoint);
        $page->drawText(substr($row ['patient'], 0, 8), $leftPos + 165, $topPos - $currpoint);
        $page->drawText(trim($row['name']), $leftPos + 200, $topPos - $currpoint);
        $page->drawText($row ['rev_code'], $leftPos + 370, $topPos - $currpoint);
        $page->drawText($row ['pay_mode'], $leftPos + 420, $topPos - $currpoint);


       if ($row['pay_mode'] == 'CAS' || $row['pay_mode'] == 'cas') {
            $cas=$row['total'];
            $casTotal = $casTotal + $row['total'];
        } else {
           $cas = "";
        }
        $pdfBase->drawText($page, number_format($cas, 2), $leftPos + 480, $topPos - $currpoint, $leftPos + 490, right);


        if ($row['pay_mode'] == 'CHQ' || $row['pay_mode'] == 'chq') {
            $chq=$row['total'];
            $chqTotal = $chqTotal + $row['total'];
        } else {
            $chq = "";
        }
        
        $pdfBase->drawText($page, number_format(intval($chq), 2), $leftPos + 540, $topPos - $currpoint, $leftPos + 530, right);


        if ($row['pay_mode'] == 'MPESA' || $row['pay_mode'] == 'mpesa' || $row['pay_mode'] == '') {
            $others=$row['total'];
            $othersTotal = $othersTotal + $row['total'];
        } else {
            $others = "";
        }
        $pdfBase->drawText($page, number_format(intval($others), 2), $leftPos + 580, $topPos - $currpoint, $leftPos + 550, right);

        $topPos = $topPos - 20;
        
        $lineStyle3 = new Zend_Pdf_Style ();
        $lineStyle3->setLineDashingPattern(array(2), 0.6);
        $lineStyle3->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
        $page->setStyle($lineStyle3);
        $page->drawRectangle($leftPos + 10, $topPos - $currpoint - 3, $leftPos + 580, $topPos - $currpoint - 3, Zend_Pdf_Page::SHAPE_DRAW_FILL);
    }
    $topPos = $topPos - $currpoint;
//	$page->drawRectangle ( $leftPos + 35, $topPos - 150, $leftPos + 700, $topPos - 150, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE );

    
    
    $resultsStyle3 = new Zend_Pdf_Style ();
    $resultsStyle3->setLineDashingPattern(array(2), 1.6);
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
    $resultsStyle3->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $resultsStyle3->setFont($font, 8);
    $page->setStyle($resultsStyle3);

    $page->drawText('Sub Totals:', $leftPos + 350, $topPos - 10);
    $pdfBase->drawText($page, number_format($casTotal, 2), $leftPos + 480, $topPos - 10, $leftPos + 490, right);
    $pdfBase->drawText($page, number_format($chqTotal, 2), $leftPos + 540, $topPos - 10, $leftPos + 530, right);
    $pdfBase->drawText($page, number_format($othersTotal, 2), $leftPos + 570, $topPos - 10, $leftPos + 550, right);
    $page->drawText('Totals:', $leftPos + 350, $topPos - 30);
    $pdfBase->drawText($page, number_format(($casTotal + $chqTotal + $othersTotal), 2), $leftPos + 480, $topPos - 30, $leftPos + 490, right);
    
    
    
    
    $resultsStyle = new Zend_Pdf_Style ();
    $resultsStyle->setLineDashingPattern(array(2), 1.6);
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
    $resultsStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $resultsStyle->setFont($font, 9);
    $page->setStyle($resultsStyle);

    $page->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0.7));

    $page->drawRectangle($leftPos + 35, $topPos - 40, $leftPos + 700, $topPos - 40, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);
    $page->drawRectangle($leftPos + 35, $topPos - 55, $leftPos + 400, $topPos - 70, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);
    $page->setFillColor(new Zend_Pdf_Color_RGB(1, 1, 1));
    $page->drawText('Breakdown of Cash Sale', $leftPos + 40, $topPos - 65);
    $page->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0.7));
    $page->drawRectangle($leftPos + 35, $topPos - 73, $leftPos + 400, $topPos - 86, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);
    $page->setFillColor(new Zend_Pdf_Color_RGB(1, 1, 1));
    //$page->drawText('rev code', $leftPos + 40, $topPos - 80);
    $page->drawText('rev desc', $leftPos + 40, $topPos - 80);
    $page->drawText('pay mode', $leftPos + 250, $topPos - 80);
    $page->drawText('amount', $leftPos + 330, $topPos - 80);

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
        $page->drawText($row ['total'], $leftPos + 330, $topPos - 95);

        $topPos = $topPos - 20;
    }
    $resultsStyle3 = new Zend_Pdf_Style ();
    $resultsStyle3->setLineDashingPattern(array(2), 1.6);
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
    $resultsStyle3->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $resultsStyle3->setFont($font, 9);
    $page->setStyle($resultsStyle3);

    $page->drawText('TOTAL:', $leftPos + 200, $topPos - 95);
    $page->drawText(number_format(intval($casTotal + $chqTotal + $othersTotal), 2), $leftPos + 300, $topPos - 95);
    $topPos = $topPos - 10;
    array_push($pdf->pages, $page);
    header('Content-type: application/pdf');
    echo $pdf->render();
}

?>
