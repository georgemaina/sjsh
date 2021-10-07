<?php

require ('roots.php');
require ($root_path . 'include/inc_environment_global.php');

require_once 'Zend/Pdf.php';

$pdf = new Zend_Pdf ();
require ($root_path . 'include/care_api_classes/Library_Pdf_base.php');

$pdfBase = new Library_Pdf_Base();

$pid = $_REQUEST['pid'];
$receipt = $_REQUEST['receipt'];
$nhif = $_REQUEST['nhif'];
$bill_number = $_REQUEST['bill'];
createInvoiceTitle($db, $pid, $receipt,$bill_number,$nhif,$pdf,$pdfBase);

function createInvoiceTitle($db, $pid, $receipt,$bill_number,$nhif,$pdf,$pdfBase) {
    require ('roots.php');

    $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
    require '../../../include/care_api_classes/class_ward.php';
    //require('../../../include/class_ward.php');
//    require('../../../include/care_api_classes/class_encounter.php');
    $wrd = new Ward ();
     $obj_enr=new Encounter();

      $nhifdebited=false;
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
            $invoice_no = $bill_number;//$data_result ['new_bill_nr'];
        }
        $global_config_ok = 1;
    } else {
        $global_config_ok = 0;
    }


    $imagePath="../../../icons/logo.jpg";
    $image = Zend_Pdf_Image::imageWithPath($imagePath);
    $page->drawImage($image, $leftPos+20, $topPos-70, $leftPos+500, $topPos+10);


    $title = 'FINAL INVOICES';

    $headlineStyle = new Zend_Pdf_Style ();
    $headlineStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $headlineStyle->setFont($font, 10);
    $page->setStyle($headlineStyle);
    $page->drawText('Date Printed:  ' . date('d-m-Y'), $leftPos + 370, $topPos - 95 );

    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
    $headlineStyle2 = new Zend_Pdf_Style ();
    $headlineStyle2->setFont($font, 13);
    $page->setStyle($headlineStyle2);
    $page->drawText($title, $leftPos + 200, $topPos - 90);
    $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD), 9);


    $page->drawText('Invoice No:    ', $leftPos + 370, $topPos - 110);

    $topPos=$topPos-40;
    $page->drawRectangle($leftPos + 36, $topPos - 85, $leftPos + 500, $topPos - 85, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);

    $headlineStyle4 = new Zend_Pdf_Style ();
    $headlineStyle4->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
    $headlineStyle4->setFont($font, 10);
    $page->setStyle($headlineStyle4);
    $page->drawText('Outpatient No:', $leftPos + 20, $topPos - 100);
    $page->drawText('Patient No:', $leftPos + 20, $topPos - 115);
    $page->drawText('Name:      ', $leftPos + 20, $topPos - 130);
    $page->drawText('Address:   ', $leftPos + 20, $topPos - 145);
    $page->drawText('Town:      ', $leftPos + 20, $topPos - 160);
    $page->drawText('Phone:     ', $leftPos + 20, $topPos - 175);

//    $page->drawText('Admission Date: ', $leftPos + 330, $topPos - 100);
//    $page->drawText('Discharge Date: ', $leftPos + 330, $topPos - 115);


    $sql = "SELECT id,accno,`name` FROM care_tz_company WHERE id=(SELECT insurance_id FROM care_person WHERE pid=$pid)";
    if ($insu_result = $db->Execute($sql)) {
        $insu_row = $insu_result->FetchRow();
    }
    $headlineStyle4 = new Zend_Pdf_Style ();
    $headlineStyle4->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $headlineStyle4->setFont($font, 10);
    $page->setStyle($headlineStyle4);

    if ($insu_row[0] <> '') {
        $page->drawText('Account No: ', $leftPos + 20, $topPos - 65);
        $page->drawText($insu_row[1], $leftPos + 92, $topPos - 65);
        $page->drawText('Account Name: ', $leftPos + 20, $topPos - 78);
        $page->drawText($insu_row[2], $leftPos + 92, $topPos - 78);
    }


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
    ,care_person.selian_pid
FROM
    care_ke_billing
    INNER JOIN care_person
        ON (care_ke_billing.pid = care_person.pid)
WHERE (care_ke_billing.`IP-OP`='2' and care_ke_billing.pid='" . $pid . "' and bill_number='$bill_number')";
//echo $sql2;
    $info_result = $db->Execute($sql2);

    if ($info_result) {
        $patient_data = $info_result->FetchRow();

        $headlineStyle = new Zend_Pdf_Style ();
        $headlineStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
        $headlineStyle->setFont($font, 10);
        $page->setStyle($headlineStyle);
        $page->drawText($patient_data ['selian_pid'], $leftPos + 92, $topPos - 100);
        $page->drawText($patient_data ['pid'], $leftPos + 92, $topPos - 115);
        $page->drawText(ucfirst(strtolower($patient_data ['name_first'])) . ' ' . ucfirst(strtolower($patient_data ['name_2'])) . ' ' . ucfirst(strtolower($patient_data ['name_last'])), $leftPos + 92, $topPos - 130);
        $page->drawText('P.O. Box ' . ucfirst(strtolower($patient_data ['addr_zip'])), $leftPos + 92, $topPos - 145);
        $page->drawText(ucfirst(strtolower($patient_data ['citizenship'])) . '    Postal code ' . $postal, $leftPos + 92, $topPos - 160);
        $page->drawText($patient_data ['cellphone_1_nr'], $leftPos + 92, $topPos - 175);


        $row2=$wrd->getEncounterDetails($patient_data ['encounter_nr']);
//        $admDate = $row2['encounter_date'];
//        $Disc_date = $row2['discharge_date'];

//        $page->drawText($admDate, $leftPos + 430, $topPos - 100);
//        $page->drawText($Disc_date, $leftPos + 430, $topPos - 115);

        $page->drawText($patient_data ['bill_number'], $leftPos + 430   , $topPos - 70);
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
    $page->drawRectangle($leftPos + 18, $topPos - 195, $leftPos + 500, $topPos - 210, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    $page->drawRectangle($leftPos + 18, $topPos - 195, $leftPos + 500, $topPos - 800, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

    $topPos=$topPos-20;

    $page->drawText('Service Description:', $leftPos + 36, $topPos - 185);
    $page->drawText('Total', $leftPos + 320, $topPos - 185);


    $sql3 = "SELECT
      service_type
    , sum(price) as price
    , sum(qty) as qty
    , sum(total) as total
FROM
    care_ke_billing
WHERE (pid ='" . $pid . "' AND service_type not in ('payment','payment adjustment','NHIF') and `ip-op`=2)
     and bill_number=$bill_number group by service_type";

    $results = $db->Execute($sql3);
    $resultsStyle = new Zend_Pdf_Style ();
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $resultsStyle->setFont($font, 9);
    $page->setStyle($resultsStyle);

    while ($row3 = $results->FetchRow()) {

        $page->drawText($row3['service_type'], $leftPos + 36, $topPos - 200);

//        $page->drawText( number_format($row3['total'],2), $leftPos + 250, $topPos - 200);
        $pdfBase->drawText($page, number_format($row3['total'], 2), $leftPos + 350, $topPos - 200, $leftPos + 350, right);
        $topPos = $topPos - 20;
    }

    $sql4 = "SELECT sum(total) as total FROM care_ke_billing WHERE pid = '$pid' and `ip-op`=2 and
        service_type not in ('payment','payment adjustment','NHIF')  and bill_number=$bill_number";
//   echo $sql4;
    $results = $db->Execute($sql4);
    $row4 = $results->FetchRow();
    if($row4['total']<>''){
        $totalBill = $row4['total'];
    }else{
        $totalBill = 0;
    }


    $page->drawRectangle($leftPos + 32, $topPos - 210, $leftPos + 500, $topPos - 210, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 10);
    $page->drawText('Total Bill: ' , $leftPos + 260, $topPos - 220);

    $pdfBase->drawText($page, number_format($totalBill, 2), $leftPos + 350, $topPos - 220, $leftPos + 350, right);
    $page->drawRectangle($leftPos + 32, $topPos - 230, $leftPos + 500, $topPos - 230, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

    $sqli = "SELECT * FROM care_ke_billing WHERE (pid ='" . $pid . "' AND
                    service_type in ('payment','payment adjustment','NHIF') and `ip-op`=2) and bill_number=$bill_number";
//    echo $sqli;
    $resultsi = $db->Execute($sqli);
    $resultsStyle = new Zend_Pdf_Style ();
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $resultsStyle->setFont($font, 9);
    $page->setStyle($resultsStyle);

    if ($receipt <> '') {
        $ntotals=0;
        while ($rowi = $resultsi->FetchRow()) {

            $page->drawText($rowi['prescribe_date'], $leftPos + 36, $topPos - 265);

            $page->drawText($rowi['Description'], $leftPos + 36, $topPos - 265);
            $page->drawText('receipt No ( ', $leftPos + 190, $topPos - 265);
            $page->drawText(trim($rowi['batch_no']) . ' )', $leftPos + 240, $topPos - 265);
            $page->drawText(' Ksh:', $leftPos + 290, $topPos - 265);
//              $page->drawText(number_format($rowi['total'],2), $leftPos + 315, $topPos - 265);
            $pdfBase->drawText($page, number_format($rowi['total'], 2), $leftPos + 350, $topPos - 265, $leftPos + 350, right);
            $topPos = $topPos - 15;
            if($rowi['rev_code']<>'nhif2')  {
                $ntotals=$ntotals+$rowi['total'];
            }
        }
        $totalPaid = $ntotals;
    }



    $resultsStyle = new Zend_Pdf_Style ();
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $resultsStyle->setFont($font, 9);
    $page->setStyle($resultsStyle);

    if ($nhif<>'' and $receipt == '') {
        $nhifdebited=true;
        $sqli = "SELECT * FROM care_ke_billing WHERE (pid ='" . $pid . "'
                     and rev_code='NHIF' and `ip-op`=2) and bill_number=$bill_number";
//    echo $sqli;
        $resultsi = $db->Execute($sqli);
        $ntotals2=0;

        while ($rowi = $resultsi->FetchRow()) {
            $page->drawText($rowi['prescribe_date'], $leftPos + 36, $topPos - 265);

            $page->drawText($rowi['Description'], $leftPos + 36, $topPos - 265);
            $page->drawText('receipt No ( ', $leftPos + 140, $topPos - 265);
            $page->drawText(trim($rowi['batch_no']) . ' )', $leftPos + 190, $topPos - 265);
            $page->drawText(' Ksh', $leftPos + 250, $topPos - 265);
            $pdfBase->drawText($page, number_format($rowi['total'], 2), $leftPos + 350, $topPos - 280, $leftPos + 350, right);
            $topPos = $topPos - 15;
            if($rowi['rev_code']<>'nhif2')  {
                $ntotals2=$ntotals2+$rowi['total'];
            }
            if($rowi['rev_code']=='nhif2'){
                $nhifGainLoss=$rowi['total'];
            }

            if($rowi['rev_code']=='nhif'){
                $nhifDebtorAcc=$rowi['total'];

            }
        }
        $totalPaid =$ntotals2;
    }

    $page->drawLine($leftPos + 32, $topPos - 260, $leftPos + 500, $topPos - 260, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    $sql5 = "SELECT sum(total) as total FROM care_ke_billing WHERE pid = '$pid' AND
 service_type in ('payment','payment adjustment','NHIF') and `ip-op`=2 and bill_number=$bill_number";
//echo $sql5;
    $results5 = $db->Execute($sql5);
    $row5 = $results5->FetchRow();


    $page->drawLine($leftPos + 32, $topPos - 280, $leftPos + 500, $topPos - 280, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

    $resultsStyle = new Zend_Pdf_Style ();
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
    $resultsStyle->setFont($font, 10);
    $page->setStyle($resultsStyle);



    if($receipt<>''){
        //$totalPaid = $row5['total'];
        $page->drawText ( 'Total Paid:  Ksh:', $leftPos + 180, $topPos - 270 );
//            $page->drawText ( ' Ksh. '.number_format($totalPaid,2), $leftPos + 250, $topPos - 270 );
        $pdfBase->drawText($page, number_format($totalPaid, 2), $leftPos + 350, $topPos - 270, $leftPos + 350, right);
    }

    if($nhifdebited){
        $bal=$totalPaid;
    }else{
        $bal=$totalBill-$totalPaid;
    }
//
    $page->drawText('Amount Due:  Ksh:', $leftPos + 180, $topPos - 290);
//    $page->drawText(' Ksh. ' . number_format($bal,2), $leftPos + 250, $topPos - 290);
    $pdfBase->drawText($page, number_format($bal, 2), $leftPos + 350, $topPos - 290, $leftPos + 350, right);
  
$strMsg='After 30 days, unpaid invoices will attrect a penalty of 2% of the o/s amount';
$strMsg2='E&OE';
     $page->drawText($strMsg, $leftPos + 32, $topPos - 320);
     $page->drawText($strMsg2, $leftPos + 32, $topPos - 340);

    //    $currpoint = $currpoint + 60;
//    $page->drawText('Sign: _______________________________', $leftPos + 40, $topPos - 400);
//    $page->drawText('ID Number: _______________________________', $leftPos + 270, $topPos - 400);
    
    $pdf->pages [0] = ($page);
    header('Content-type: application/pdf');
    echo $pdf->render();
}

?>
