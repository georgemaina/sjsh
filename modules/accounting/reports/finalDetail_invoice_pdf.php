<?php

require ('roots.php');
require ($root_path . 'include/inc_environment_global.php');
$pid = $_REQUEST['pid'];
$receipt = $_REQUEST['receipt'];
$nhif = $_REQUEST['nhif'];
$billNumber = $_REQUEST['billNumber'];

createGuarantorsForm($db, $pid, $receipt,$billNumber, $nhif,$root_path);


function getWrappedText($string, Zend_Pdf_Style $style, $max_width) {
    $wrappedText = '';
    $lines = explode("\n", $string);
    foreach ($lines as $line) {
        $words = explode(' ', $line);
        $word_count = count($words);
        $i = 0;
        $wrappedLine = '';
        while ($i < $word_count) {
            /* if adding a new word isn't wider than $max_width,
              we add the word */
            if (widthForStringUsingFontSize($wrappedLine . ' ' . $words[$i]
                    , $style->getFont()
                    , $style->getFontSize()) < $max_width) {
                if (!empty($wrappedLine)) {
                    $wrappedLine .= ' ';
                }
                $wrappedLine .= $words[$i];
            } else {
                $wrappedText .= $wrappedLine . "\n";
                $wrappedLine = $words[$i];
            }
            $i++;
        }
        $wrappedText .= $wrappedLine . "\n";
    }
    return $wrappedText;
}

/**
 * found here, not sure of the author :
 * http://devzone.zend.com/article/2525-Zend_Pdf-tutorial#comments-2535
 */
function widthForStringUsingFontSize($string, $font, $fontSize) {
    $drawingString = iconv('UTF-8', 'UTF-16BE//IGNORE', $string);
    $characters = array();
    for ($i = 0; $i < strlen($drawingString); $i++) {
        $characters[] = (ord($drawingString[$i++]) << 8 ) | ord($drawingString[$i]);
    }
    $glyphs = $font->glyphNumbersForCharacters($characters);
    $widths = $font->widthsForGlyphs($glyphs);
    $stringWidth = (array_sum($widths) / $font->getUnitsPerEm()) * $fontSize;
    return $stringWidth;
}

function createGuarantorsForm($db, $pid, $receipt,$bill_number, $nhif,$root_path) {
    require ('roots.php');
    require_once 'Zend/Pdf.php';
    $pdf = new Zend_Pdf ();
    $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);


    require ($root_path . 'include/care_api_classes/Library_Pdf_Base.php');
    $pdfBase=new Library_Pdf_Base();

    require '../../../include/care_api_classes/class_ward.php';
    //require('../../../include/class_ward.php');
    //require('../../../include/care_api_classes/class_encounter.php');
    $wrd = new Ward ();
    // $obj_enr=new Encounter();
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
            $invoice_no = $data_result ['new_bill_nr'];
        }
        $global_config_ok = 1;
    } else {
        $global_config_ok = 0;
    }


    $imagePath="../../../icons/logo.jpg";
    $image = Zend_Pdf_Image::imageWithPath($imagePath);
    $page->drawImage($image, $leftPos+20, $topPos-70, $leftPos+500, $topPos+10);


    $title = 'FINAL DETAIL INVOICE';

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
    $page->drawText('Inpatient No:', $leftPos + 20, $topPos - 100);
    $page->drawText('Patient No:', $leftPos + 20, $topPos - 115);
    $page->drawText('Name:      ', $leftPos + 20, $topPos - 130);
    $page->drawText('Address:   ', $leftPos + 20, $topPos - 145);
    $page->drawText('Town:      ', $leftPos + 20, $topPos - 160);
    $page->drawText('Phone:     ', $leftPos + 20, $topPos - 175);

    $page->drawText('Admission Date: ', $leftPos + 330, $topPos - 100);
    $page->drawText('Discharge Date: ', $leftPos + 330, $topPos - 115);
    $page->drawText('Ward No:       ', $leftPos + 330, $topPos - 130);
    $page->drawText('Room No:       ', $leftPos + 330, $topPos - 145);
    $page->drawText('Bed No:        ', $leftPos + 330, $topPos - 160);
    $page->drawText('Bed Days:        ', $leftPos + 330, $topPos - 180);


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
WHERE (care_ke_billing.`IP-OP`='1' and care_ke_billing.pid='" . $pid . "' and bill_number='$bill_number')";
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

        $row2 = $wrd->EncounterLocationsInfo2($patient_data ['encounter_nr']);
        $bed_nr = $row2 [6];
        $room_nr = $row2 [5];
        $ward_nr = $row2 [0];
        $ward_name = $row2 [1];
        $admDate = $row2 [7];
        $Disc_date = $row2 [8];
        $days=$row2['wardDays'];

        $page->drawText($admDate, $leftPos + 430, $topPos - 100);
        $page->drawText($Disc_date, $leftPos + 430, $topPos - 115);
        $page->drawText($ward_name . ':' . $ward_nr, $leftPos + 430, $topPos - 130);
        $page->drawText($room_nr, $leftPos + 430, $topPos - 145);
        $page->drawText($bed_nr, $leftPos + 430, $topPos - 160);
        $page->drawText($days, $leftPos + 430, $topPos - 180);
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
    $page->drawText('Date', $leftPos + 36, $topPos - 205);
    $page->drawText('Service Description:', $leftPos + 100, $topPos - 205);
    $page->drawText('ref No', $leftPos + 270, $topPos - 205);
    $page->drawText('Price', $leftPos + 330, $topPos - 205);
    $page->drawText('Quantity', $leftPos + 380, $topPos - 205);
    $page->drawText('Total', $leftPos + 450, $topPos - 205);

    $currpoint = 220;
    $sql3 = "SELECT
    prescribe_date
    , description
    , bill_number
    , price
    , qty
    , total
FROM
    care_ke_billing
WHERE (pid ='" . $pid . "' AND service_type NOT IN ('payment','payment adjustment','NHIF') and `ip-op`=1
            and pid='" . $pid . "' and bill_number=$bill_number)";


    $results = $db->Execute($sql3);
    $resultsStyle = new Zend_Pdf_Style ();
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $resultsStyle->setFont($font, 9);
    $page->setStyle($resultsStyle);


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
            $rectStyle->setLineDashingPattern(array(2), 1.6);
            $rectStyle->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
            $rectStyle->setFont($font, 10);
            $page->setStyle($rectStyle);
            $page->drawRectangle($leftPos + 32, $topPos - $currpoint, $leftPos + 500, $topPos - $currpoint, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
        }
        
        $page->drawText($row['prescribe_date'], $leftPos + 36, $topPos - $currpoint);
//        $page->drawText($row['description'], $leftPos + 100, $topPos - $currpoint);
        $y = $topPos - $currpoint;
        $lines = explode("\n", getWrappedText($row['description'], $headlineStyle, 250));
        foreach ($lines as $line) {
            $page->drawText($line, $leftPos + 100, $y);
            $y-=10;
//            $leftPos = $leftPos - 40;
        }
        $page->drawText($row['bill_number'], $leftPos + 270, $topPos - $currpoint);
         if(!empty($row['price'])){
            $price=$row['price'];
        }else{
            $price=0;
        }
        if(!empty($row['total'])){
            $total=$row['total'];
        }else{
            $total=0;
        }
        
//        $page->drawText( number_format($price,2), $leftPos + 330, $topPos - $currpoint);
//        $page->drawText($row['qty'], $leftPos + 380, $topPos - $currpoint);
//        $page->drawText( number_format($total,2), $leftPos + 450, $topPos - $currpoint);

        $pdfBase->drawText($page, number_format($price, 2), $leftPos + 355, $topPos - $currpoint, $leftPos + 355, right);
        $pdfBase->drawText($page, $row['qty'], $leftPos + 410, $topPos - $currpoint, $leftPos + 410, right);
        $pdfBase->drawText($page, number_format($total, 2), $leftPos + 475, $topPos - $currpoint, $leftPos + 475, right);

        $topPos = $topPos - 12;
    }
    $topPos = $topPos - $currpoint;
    $page->drawRectangle($leftPos + 32, $topPos - $currpoint, $leftPos + 500, $topPos - $currpoint, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    $sql4 = "SELECT sum(total) as total FROM care_ke_billing WHERE pid = '$pid' AND 
        service_type NOT IN ('payment','payment adjustment','NHIF') and `ip-op`=1 and care_ke_billing.pid='" . $pid . "' and bill_number=$bill_number";

    $results = $db->Execute($sql4);
    $row = $results->FetchRow();
    $bill = $row['total'];

    $currpoint = 15;
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
        $rectStyle->setLineDashingPattern(array(2), 1.6);
        $rectStyle->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
        $rectStyle->setFont($font, 10);
        $page->setStyle($rectStyle);
        $page->drawRectangle($leftPos + 32, $topPos - $currpoint, $leftPos + 500, $topPos - $currpoint, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    }
//    $topPos = $topPos - $currpoint;
    $currpoint = $currpoint + 10;
    $resultsStyle = new Zend_Pdf_Style ();
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
    $resultsStyle->setFont($font, 9);
    $page->setStyle($resultsStyle);
    $page->drawText('Total', $leftPos + 380, $topPos - $currpoint);
    $totalBill=$row['total'];
    $page->drawText('Ksh.' . number_format($row['total'], 2), $leftPos + 430, $topPos - $currpoint);
    $currpoint = $currpoint + 10;
    $page->drawRectangle($leftPos + 32, $topPos - $currpoint, $leftPos + 500, $topPos - $currpoint, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

    $sqli = "SELECT * FROM care_ke_billing WHERE (pid ='" . $pid . "' AND service_type IN
            ('payment','payment adjustment','NHIF') and `ip-op`=1 and bill_number=$bill_number)";

    $resultsi = $db->Execute($sqli);
    $resultsStyle = new Zend_Pdf_Style ();
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $resultsStyle->setFont($font, 9);
    $page->setStyle($resultsStyle);

    $currpoint = $currpoint + 20;
    $totalPaid=0;
    if ($receipt <> '') {
        $ntotals=0;
        while ($rowi = $resultsi->FetchRow()) {
            if ($topPos < 200) {
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
                $rectStyle->setLineDashingPattern(array(2), 1.6);
                $rectStyle->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
                $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
                $rectStyle->setFont($font, 10);
                $page->setStyle($rectStyle);
                $page->drawRectangle($leftPos + 32, $topPos - $currpoint, $leftPos + 500, $topPos - $currpoint, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
            }
            $page->drawText($rowi['prescribe_date'], $leftPos + 36, $topPos - $currpoint);
            $page->drawText('Bill', $leftPos + 100, $topPos - $currpoint);
            $page->drawText($rowi['service_type'], $leftPos + 150, $topPos - $currpoint);
            $page->drawText('CLAIM No ( ', $leftPos + 270, $topPos - $currpoint);
            $page->drawText($rowi['batch_no'] . ' )', $leftPos + 320, $topPos - $currpoint);
            $page->drawText('Ksh', $leftPos + 380, $topPos - $currpoint);
            $page->drawText($rowi['total'], $leftPos + 450, $topPos - $currpoint);
            $topPos = $topPos - 15;
            
             if($rowi['rev_code']<>'nhif2')  {  
                  $ntotals=$ntotals+$rowi['total'];
              } 
        }
         $totalPaid=$ntotals;
    }



    $resultsi = $db->Execute($sqli);
    $resultsStyle = new Zend_Pdf_Style ();
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $resultsStyle->setFont($font, 9);
    $page->setStyle($resultsStyle);

    $currpoint = $currpoint + 20;
    if ($nhif <> '' and $receipt == '') {
    $nhifdebited=true;
        $sqlj = "SELECT * FROM care_ke_billing WHERE (pid ='" . $pid . "' AND rev_code in('NHIF') and `ip-op`=1 and bill_number=$bill_number)";
        $resultsj = $db->Execute($sqlj);
        $ntotal=0;
        while ($rowi = $resultsj->FetchRow()) {
            if ($topPos < 200) {
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
                $rectStyle->setLineDashingPattern(array(2), 1.6);
                $rectStyle->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
                $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
                $rectStyle->setFont($font, 10);
                $page->setStyle($rectStyle);
                $page->drawRectangle($leftPos + 32, $topPos - $currpoint, $leftPos + 500, $topPos - $currpoint, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
            }
            $page->drawText($rowi['prescribe_date'], $leftPos + 36, $topPos - $currpoint);
            $page->drawText('Bill', $leftPos + 100, $topPos - $currpoint);
            $page->drawText($rowi['service_type'], $leftPos + 150, $topPos - $currpoint);
            $page->drawText('receipt No ( ', $leftPos + 270, $topPos - $currpoint);
            $page->drawText($rowi['batch_no'] . ' )', $leftPos + 320, $topPos - $currpoint);
            $page->drawText('Ksh', $leftPos + 380, $topPos - $currpoint);
            $page->drawText($rowi['total'], $leftPos + 450, $topPos - $currpoint);
            $topPos = $topPos - 15;
             if($rowi['rev_code']<>'nhif2')  {  
                  $ntotal=$ntotal+$rowi['total'];
              } 
           
        }
        $totalPaid=$ntotal;
    }

    $topPos = $topPos - $currpoint;
    $currpoint = 10;
    $page->drawLine($leftPos + 32, $topPos - $currpoint, $leftPos + 500, $topPos - $currpoint, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

    $sql4 = "SELECT sum(total) as total FROM care_ke_billing WHERE pid = '$pid' AND 
        service_type IN ('payment','payment adjustment','NHIF') and `ip-op`=1 and bill_number=$bill_number";

    $results = $db->Execute($sql4);
    $row = $results->FetchRow();
//    if (!empty($row['total'])) {
//        $totalPaid = $row['total'];
//    } else {
//        $totalPaid = 0;
//    }
    $resultsStyle = new Zend_Pdf_Style ();
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
    $resultsStyle->setFont($font, 9);
    $page->setStyle($resultsStyle);
    $currpoint = $currpoint + 20;
    if ($receipt <> '') {
        $page->drawRectangle($leftPos + 32, $topPos - $currpoint, $leftPos + 500, $topPos - $currpoint, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
        $page->drawText('Total Paid:', $leftPos + 380, $topPos - $currpoint);
        $page->drawText(' Ksh. ' . number_format($totalPaid, 2), $leftPos + 430, $topPos - $currpoint);
    }
    $currpoint = $currpoint + 20;
    if($nhifdebited){
            $bal=$totalPaid;
    }else{
            $bal=$totalBill-$totalPaid;
    }
    $page->drawText('Bill balance:', $leftPos + 380, $topPos - $currpoint);
    $page->drawText(' Ksh. ' . number_format(intval($bal ), 2), $leftPos + 430, $topPos - $currpoint);


    $currpoint = $currpoint + 5;
    $page->drawRectangle($leftPos + 32, $topPos - $currpoint, $leftPos + 500, $topPos - $currpoint, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
	
     $currpoint = $currpoint + 60;
    $page->drawText('Sign: _________________________', $leftPos + 30, $topPos - $currpoint);
    $page->drawText('ID Number: _______________________________', $leftPos + 270, $topPos - $currpoint);


    $topPos = $topPos - 10;
    array_push($pdf->pages, $page);
    header('Content-type: application/pdf');
    echo $pdf->render();
}

?>
