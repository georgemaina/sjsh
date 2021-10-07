<?php

require ('roots.php');
require ($root_path . 'include/inc_environment_global.php');

$pid = $_REQUEST['pid'];
$receipt = $_REQUEST['receipt'];
$billNumber = trim($_REQUEST["billNumber"]);
$class=$_REQUEST['encClass'];
$displayType=$_REQUEST['single'];
$accno=$_REQUEST['accNo'];
$date1 = $_REQUEST["strDate1"];
$date2 = $_REQUEST["strDate2"];

require_once 'Zend/Pdf.php';
$pdf = new Zend_Pdf ();
$page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
$pageHeight = $page->getHeight();
$width = $page->getWidth();
$topPos = $pageHeight - 10;
$leftPos = 36;


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

    require('roots.php');

    // $obj_enr=new Encounter();

    $resultsStyle = new Zend_Pdf_Style ();
    $resultsStyle->setLineDashingPattern(array(2), 1.6);
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES);
    $resultsStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $resultsStyle->setFont($font, 9);
    $page->setStyle($resultsStyle);

    $titleStyle = new Zend_Pdf_Style ();
    $titleStyle->setLineDashingPattern(array(2), 1.6);
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD);
    $titleStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $titleStyle->setFont($font, 10);

    $messageStyle = new Zend_Pdf_Style ();
    $messageStyle->setLineDashingPattern(array(2), 1.6);
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER_ITALIC);
    $messageStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $messageStyle->setFont($font, 10);

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

    $title = 'DEBTORS GUARANTORS FORM';

    $headlineStyle = new Zend_Pdf_Style ();
    $headlineStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER);
    $headlineStyle->setFont($font, 10);
    $page->setStyle($headlineStyle);
    $page->drawText($company, $leftPos + 36, $topPos - 36);
    $page->drawText($address, $leftPos + 36, $topPos - 50);
    $page->drawText($town . ' - ' . $postal, $leftPos + 36, $topPos - 65);
    $page->drawText($tel, $leftPos + 36, $topPos - 80);

    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER_BOLD);
    $headlineStyle2 = new Zend_Pdf_Style ();
    $headlineStyle2->setFont($font, 13);
    $page->setStyle($headlineStyle2);
    $page->drawText($title, $leftPos + 180, $topPos - 20);
    $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER), 9);

    $page->drawText('Date:  ' . date('d-m-Y'), $leftPos + 36, $topPos - 90);
    $page->drawRectangle($leftPos + 36, $topPos - 95, $leftPos + 550, $topPos - 95, Zend_Pdf_Page::SHAPE_DRAW_FILL);


    $sql = "SELECT id,accno,`name`,phone_code FROM care_tz_company WHERE id=(SELECT insurance_id FROM 
                care_person WHERE pid=$pid)";
    if ($insu_result = $db->Execute($sql)) {
        $insu_row = $insu_result->FetchRow();
    }
    $headlineStyle4 = new Zend_Pdf_Style ();
    $headlineStyle4->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER);
    $headlineStyle4->setFont($font, 10);
    $page->setStyle($headlineStyle4);

//    if ($insu_row[0] <> '') {
        $page->drawText('Account No  :', $leftPos + 320, $topPos - 36);
        $page->drawText($insu_row[1], $leftPos + 400, $topPos - 36);
        $page->drawText('Account Name:', $leftPos + 320, $topPos - 50);
        $page->drawText($insu_row[2], $leftPos + 400, $topPos - 50);
        $page->drawText('Telephone   :', $leftPos + 320, $topPos - 70);
        $page->drawText($insu_row[3], $leftPos + 400, $topPos - 70);
//    }

    $page->setStyle($titleStyle);

    $page->drawText('GUARANTOR', $leftPos + 36, $topPos - 110);
    $page->drawText('RELATIONSHIP', $leftPos + 250, $topPos - 110);
    $page->drawText('SIGNATURE', $leftPos + 420, $topPos - 110);


    $sql="SELECT `ID`,`accno`,`pid`,`pname`,`guarantor1`,`guarantor2`,`guarantor3`,`relation1`,
                  `relation2`,`relation3`,`security1`,`security2`,`security3`,
                  `invoiceNumber`,`invoiceAmount`,`installmentNo`,`installment1`,`installment2`,`installment3`,
                  `payableDate`,`narrative`,`inputUser` FROM `care_ke_debtorguarantors`
                  WHERE pid='$pid'";
    $results=$db->Execute($sql);
    $row=$results->FetchRow();

    $page->setStyle($resultsStyle);


    $page->drawText('1.'.$row[guarantor1], $leftPos + 36, $topPos - 130);
    $page->drawText('1.'.$row[relation1], $leftPos + 250, $topPos - 130);
    $page->drawText('____________________', $leftPos + 420, $topPos - 130);

    $page->drawText('2.'.$row[guarantor2], $leftPos + 36, $topPos - 150);
    $page->drawText('2.'.$row[relation2], $leftPos + 250, $topPos - 150);
    $page->drawText('____________________', $leftPos + 420, $topPos - 150);

    $page->drawText('3.'.$row[guarantor3], $leftPos + 36, $topPos - 170);
    $page->drawText('3.'.$row[relation3], $leftPos + 250, $topPos - 170);
    $page->drawText('____________________', $leftPos + 420, $topPos - 170);

    $page->drawRectangle($leftPos + 36, $topPos - 190, $leftPos + 550, $topPos - 190, Zend_Pdf_Page::SHAPE_DRAW_FILL);

    $page->setStyle($titleStyle);

    $page->drawText('SECURITIES FIELD', $leftPos + 36, $topPos - 210);

    $page->setStyle($resultsStyle);

    $page->drawText('1.'.$row[security1], $leftPos + 36, $topPos - 230);
    $page->drawText('2.'.$row[security2], $leftPos + 36, $topPos - 250);
    $page->drawText('3.'.$row[security3], $leftPos + 36, $topPos - 270);

    $page->setStyle($titleStyle);

    $page->drawText('PAYMENT CATEGORISATION', $leftPos + 36, $topPos - 300);
    $page->drawText('PAYMENT AGREEMENT:', $leftPos + 36, $topPos - 320);

    $page->setStyle($messageStyle);

    $results=$row[narrative];

        $y = $topPos - 350;

        $narr1=str_replace('$accountName', $row[pname],$results);
        $narr2=str_replace('$AmountToPay',$row[invoiceAmount],$narr1);
        $narr3=str_replace('$InvoiceNo',$row[invoiceNumber],$narr2);
        $narr4=str_replace('$patientName',$row[pname],$narr3);
        $narr5=str_replace('$installment1',$row[installment1],$narr4);
        $narr6=str_replace('$payableDate',$row[payableDate],$narr5);
        $narr7=str_replace('$installmentNo',$row[installmentNo],$narr6);



        $lines = explode("\n", getWrappedText($narr7, $headlineStyle, 400));
        foreach ($lines as $line) {
            $page->drawText($line, $leftPos + 80, $y);
            $y-=20;
        }

$page->drawText('Debtors Signature: __________________________________', $leftPos + 36, $topPos - 540);

$page->drawText('Authorised By: __________________________________', $leftPos + 36, $topPos - 660);
$page->drawText('  (For MMH) ', $leftPos + 36, $topPos - 650);


$topPos = $topPos - 10;
    array_push($pdf->pages, $page);
    header('Content-type: application/pdf');
    echo $pdf->render();

?>
