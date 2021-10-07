<?php

require('roots.php');
require($root_path . 'include/inc_environment_global.php');

$pid = $_REQUEST['pid'];
$receipt = $_REQUEST['receipt'];
$nhif = $_REQUEST['nhif'];
$billNumber = $_REQUEST['billNumber'];
$date1 = $_REQUEST["strDate1"];
$date2 = $_REQUEST["strDate2"];
$displayType = $_REQUEST[single];
$accno=$_REQUEST[accNo];

require_once 'Zend/Pdf.php';
$pdf = new Zend_Pdf ();
$page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
$pageHeight = $page->getHeight();
$width = $page->getWidth();
$topPos = $pageHeight - 10;
$leftPos = 36;
//$pdf->_helper->layout->disableLayout(true);
require($root_path . 'include/care_api_classes/Library_Pdf_Base.php');
$pdfBase = new Library_Pdf_Base();

if ($displayType = false) {
    createInvoiceTitle($pdf, $page, $topPos, $leftPos, trim($pid), $receipt, trim($billNumber), $class, $pdfBase,$accno);
} else {
    //getPages($page,$topPos,$leftPos,$row[1],$row['bill_number']);
    $sql = "Select accno,bill_Number,encounter_class_nr,pid from care_ke_debtorTrans where accno='$accno'  and bill_number<>'' 
    and transtype=2";
    if ($date1 <> "" && $date2 <> "") {
        $sql = $sql . " and transdate between '$date1' and '$date2' ";
    }

    $sql = $sql . " GROUP BY bill_number ORDER BY transdate asc";

//       echo $sql;

    $results = $db->Execute($sql);
    while ($row = $results->FetchRow()) {
        createInvoiceTitle($pdf, $page, $topPos, $leftPos, trim($row[pid]), 1, trim($row[bill_Number]), $row[encounter_class_nr], $pdfBase,$accno);

        array_push($pdf->pages, $page);
        $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
        $resultsStyle = new Zend_Pdf_Style ();
        $resultsStyle->setLineDashingPattern(array(2), 1.6);
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER);
        $resultsStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
        $resultsStyle->setFont($font, 9);
        $page->setStyle($resultsStyle);
        $pageHeight = $page->getHeight();
        $topPos = $pageHeight - 20;
        $currpoint = 30;
    }

}

function getWrappedText($string, Zend_Pdf_Style $style, $max_width)
{
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
                    , $style->getFontSize()) < $max_width
            ) {
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

function widthForStringUsingFontSize($string, $font, $fontSize)
{
    $drawingString = iconv('UTF-8', 'UTF-16BE//IGNORE', $string);
    $characters = array();
    for ($i = 0; $i < strlen($drawingString); $i++) {
        $characters[] = (ord($drawingString[$i++]) << 8) | ord($drawingString[$i]);
    }
    $glyphs = $font->glyphNumbersForCharacters($characters);
    $widths = $font->widthsForGlyphs($glyphs);
    $stringWidth = (array_sum($widths) / $font->getUnitsPerEm()) * $fontSize;
    return $stringWidth;
}


function createInvoiceTitle($pdf, $page, $topPos, $leftPos, $pid, $receipt, $bill_number, $class, $pdfBase,$accno)
{
    require('roots.php');
    global $db;

    $nhifdebited = false;
    $pageHeight = $page->getHeight();
    $width = $page->getWidth();
    $topPos = $pageHeight - 10;
    $leftPos = 36;

    $imagePath = "../../../icons/logo.jpg";
    $image = Zend_Pdf_Image::imageWithPath($imagePath);
    $page->drawImage($image, $leftPos + 20, $topPos - 70, $leftPos + 500, $topPos + 10);

    $headlineStyle = new Zend_Pdf_Style ();
    $headlineStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
    $headlineStyle->setFont($font, 10);
    $page->setStyle($headlineStyle);
    $page->drawText('Date Printed:  ' . date('d-m-Y'), $leftPos + 370, $topPos - 95);


    $page->drawText('FINAL DETAIL INVOICE', $leftPos + 230, $topPos - 80);

    $page->drawText('Invoice No:    '.$bill_number, $leftPos + 370, $topPos - 110);

    $topPos = $topPos - 40;
    $page->drawRectangle($leftPos + 36, $topPos - 85, $leftPos + 500, $topPos - 85, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);

    //$page->drawText('Inpatient No:', $leftPos + 20, $topPos - 100);
    $page->drawText('Patient No:', $leftPos + 20, $topPos - 115);
    $page->drawText('Name:      ', $leftPos + 20, $topPos - 130);
    $page->drawText('Address:   ', $leftPos + 20, $topPos - 145);
    $page->drawText('Town:      ', $leftPos + 20, $topPos - 160);
    $page->drawText('Phone:     ', $leftPos + 20, $topPos - 175);


    $sql = "SELECT id,accno,`name` FROM care_tz_company WHERE id =(SELECT insurance_id FROM care_person WHERE pid='$pid')";
    //echo $sql;
    $insu_result = $db->Execute($sql);
    $insu_row = $insu_result->FetchRow();


    if ($insu_row[0] <> '') {
        $page->drawText('Account No: ', $leftPos + 20, $topPos - 65);
        $page->drawText($insu_row[1], $leftPos + 95, $topPos - 65);
        $page->drawText('Account Name: ', $leftPos + 20, $topPos - 78);
        $page->drawText($insu_row[2], $leftPos + 95, $topPos - 78);
    }


    $headlineStyle4 = new Zend_Pdf_Style ();
    $headlineStyle4->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $headlineStyle4->setFont($font, 10);
    $page->setStyle($headlineStyle4);

    $sql2 = "SELECT DISTINCT
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
WHERE (care_ke_billing.`IP-OP`='2' and care_ke_billing.pid='$pid' and bill_number='$bill_number')";
//echo $sql2;
    $info_result = $db->Execute($sql2);

    if ($info_result) {
        $patient_data = $info_result->FetchRow();

        $headlineStyle = new Zend_Pdf_Style ();
        $headlineStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
        $headlineStyle->setFont($font, 10);
        $page->setStyle($headlineStyle);
        //$page->drawText($patient_data ['selian_pid'], $leftPos + 92, $topPos - 100);
        $page->drawText($patient_data ['pid'], $leftPos + 92, $topPos - 115);
        $page->drawText(ucfirst(strtolower($patient_data ['name_first'])) . ' ' . ucfirst(strtolower($patient_data ['name_2'])) . ' ' . ucfirst(strtolower($patient_data ['name_last'])), $leftPos + 92, $topPos - 130);
        $page->drawText('P.O. Box ' . ucfirst(strtolower($patient_data ['addr_zip'])), $leftPos + 92, $topPos - 145);
        $page->drawText(ucfirst(strtolower($patient_data ['citizenship'])) . 'Postal code ' . $patient_data[postal], $leftPos + 92, $topPos - 160);
        $page->drawText($patient_data ['cellphone_1_nr'], $leftPos + 92, $topPos - 175);

//        $row2 = $wrd->EncounterLocationsInfo2($patient_data ['encounter_nr']);
//        $bed_nr = $row2 [6];
//        $room_nr = $row2 [5];
//        $ward_nr = $row2 [0];
//        $ward_name = $row2 [1];
//        $admDate = $row2 [7];
//        $Disc_date = $row2 [8];
//        $days=$row2['wardDays'];

//        $page->drawText($admDate, $leftPos + 430, $topPos - 100);
//        $page->drawText($Disc_date, $leftPos + 430, $topPos - 115);
//        $page->drawText($ward_name . ':' . $ward_nr, $leftPos + 430, $topPos - 130);
//        $page->drawText($room_nr, $leftPos + 430, $topPos - 145);
//        $page->drawText($bed_nr, $leftPos + 430, $topPos - 160);
//        $page->drawText($days, $leftPos + 430, $topPos - 180);
//        $page->drawText($patient_data ['bill_number'], $leftPos + 430   , $topPos - 70);
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
WHERE (pid ='" . $pid . "' AND service_type NOT IN ('payment','payment adjustment','NHIF') and `ip-op`=2
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
            $y -= 10;
//            $leftPos = $leftPos - 40;
        }
        $page->drawText($row['bill_number'], $leftPos + 270, $topPos - $currpoint);
        if (!empty($row['price'])) {
            $price = $row['price'];
        } else {
            $price = 0;
        }
        if (!empty($row['total'])) {
            $total = $row['total'];
        } else {
            $total = 0;
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
        service_type NOT IN ('payment','payment adjustment','NHIF') and `ip-op`=2 and care_ke_billing.pid='" . $pid . "' and bill_number=$bill_number";

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
    $page->drawText('Ksh.' . number_format($row['total'], 2), $leftPos + 430, $topPos - $currpoint);
    $currpoint = $currpoint + 10;
    $page->drawRectangle($leftPos + 32, $topPos - $currpoint, $leftPos + 500, $topPos - $currpoint, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

    $sqli = "SELECT DISTINCT d.`memberID`,b.* FROM care_ke_billing b LEFT JOIN care_ke_debtormembers d
            ON b.`pid`=d.`PID` WHERE (b.pid ='" . $pid . "' AND service_type IN
            ('payment','payment adjustment','NHIF') and `ip-op`=2 and bill_number=$bill_number)";
    //echo $sqli;

    $resultsi = $db->Execute($sqli);
    $resultsStyle = new Zend_Pdf_Style ();
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $resultsStyle->setFont($font, 9);
    $page->setStyle($resultsStyle);

    $currpoint = $currpoint + 20;

    $paid = 0;
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

        if ($rowi['service_type'] == 'NHIF') {
            $page->drawText($rowi['prescribe_date'], $leftPos + 36, $topPos - $currpoint);
            $page->drawText('NHIF CARD ', $leftPos + 100, $topPos - $currpoint);
            $page->drawText($rowi['memberID'], $leftPos + 150, $topPos - $currpoint);
            $page->drawText('CLAIM No ( ', $leftPos + 270, $topPos - $currpoint);
            $page->drawText($rowi['batch_no'] . ' )', $leftPos + 320, $topPos - $currpoint);
            $page->drawText('Ksh', $leftPos + 380, $topPos - $currpoint);
            $page->drawText(number_format($rowi['total'], 2), $leftPos + 450, $topPos - $currpoint);

        } else {
            $page->drawText($rowi['prescribe_date'], $leftPos + 36, $topPos - $currpoint);
            $page->drawText('Bill', $leftPos + 100, $topPos - $currpoint);
            $page->drawText($rowi['service_type'], $leftPos + 150, $topPos - $currpoint);
            $page->drawText('receipt No ( ', $leftPos + 270, $topPos - $currpoint);
            $page->drawText($rowi['batch_no'] . ' )', $leftPos + 320, $topPos - $currpoint);
            $page->drawText('Ksh', $leftPos + 380, $topPos - $currpoint);
            $page->drawText(number_format($rowi['total'], 2), $leftPos + 450, $topPos - $currpoint);
            $topPos = $topPos - 15;
        }
        $paid = $paid + $rowi['total'];

    }

    $bal = $bill - $paid;

    $currpoint = $currpoint + 10;
    $page->drawLine($leftPos + 32, $topPos - $currpoint, $leftPos + 500, $topPos - $currpoint, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    $currpoint = $currpoint + 20;

    $page->setStyle($resultsStyle);
    $page->drawText('Bill balance:', $leftPos + 370, $topPos - $currpoint);
    $page->drawText(' Ksh. ' . number_format(intval($bal), 2), $leftPos + 430, $topPos - $currpoint);

    $currpoint = $currpoint + 5;
    $page->drawRectangle($leftPos + 32, $topPos - $currpoint, $leftPos + 500, $topPos - $currpoint, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

}

$topPos = $topPos - 10;
array_push($pdf->pages, $page);
header('Content-type: application/pdf');
echo $pdf->render();

?>
