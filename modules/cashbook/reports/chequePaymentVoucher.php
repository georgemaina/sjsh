<?php

require ('roots.php');
require ($root_path . 'include/inc_environment_global.php');
$pid = $_REQUEST ['pid'];
$cashpoint = $_REQUEST ['cashpoint'];
$voucherNo = $_REQUEST ['voucherNo'];
$payMode = $_REQUEST ['payMode'];
$shiftno = $_REQUEST ['shiftno'];
$cheqID = $_REQUEST ['cheqID'];


function convert_number($number) {
    if (($number < 0) || ($number > 999999999)) {
        throw new Exception("Number is out of range");
    }

    $Gn = floor($number / 1000000);  /* Millions (giga) */
    $number -= $Gn * 1000000;
    $kn = floor($number / 1000);     /* Thousands (kilo) */
    $number -= $kn * 1000;
    $Hn = floor($number / 100);      /* Hundreds (hecto) */
    $number -= $Hn * 100;
    $Dn = floor($number / 10);       /* Tens (deca) */
    $n = $number % 10;               /* Ones */

    $res = "";

    if ($Gn) {
        $res .= convert_number($Gn) . " Million";
    }

    if ($kn) {
        $res .= (empty($res) ? "" : " ") .
            convert_number($kn) . " Thousand";
    }

    if ($Hn) {
        $res .= (empty($res) ? "" : " ") .
            convert_number($Hn) . " Hundred";
    }

    $ones = array("", "One", "Two", "Three", "Four", "Five", "Six",
        "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
        "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen",
        "Nineteen");
    $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty",
        "Seventy", "Eigthy", "Ninety");

    if ($Dn || $n) {
        if (!empty($res)) {
            $res .= " and ";
        }

        if ($Dn < 2) {
            $res .= $ones[$Dn * 10 + $n];
        } else {
            $res .= $tens[$Dn];

            if ($n) {
                $res .= "-" . $ones[$n];
            }
        }
    }

    if (empty($res)) {
        $res = "zero";
    }

    return $res;
}

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

//function createInvoiceTitle($db, $cashpoint,$voucherNo,$payMode) {
require ('roots.php');
require_once 'Zend/Pdf.php';
$pdf = new Zend_Pdf ();
$page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);

$pageHeight = $page->getHeight();
$width = $page->getWidth();
$topPos = $pageHeight - 10;
$leftPos = 5;

$sql = "SELECT cash_point,Voucher_No,Pay_mode,cheque_no,Total FROM care_ke_payments ";

if($cheqID<>''){
    $sql.="where ID IN($cheqID)";
}else{
    $sql.="where Voucher_No in ('$voucherNo')";
}
//echo $sql;
$result = $db->Execute($sql);

while ($row1 = $result->FetchRow()) {
    $cashpoint=$row1[0];
    $voucherNo=$row1[1];
    $payMode=$row1[2];

    getVouchers($cashpoint,$voucherNo,$payMode,$page,$topPos,$leftPos);

    array_push($pdf->pages, $page);
    $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
    $pageHeight = $page->getHeight();
    $width = $page->getWidth();
    $topPos = $pageHeight - 10;
    $leftPos = 5;

}

$topPos = $topPos - 10;
//array_push($pdf->pages, $page);
header('Content-type: application/pdf');
echo $pdf->render();

//}


function getVouchers($cashpoint,$voucherNo,$payMode,$page,$topPos,$leftPos) {
    global $db;
    require ('roots.php');

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
            $email='Email :'.$data_result['Email'];

        }
        $global_config_ok = 1;
    } else {
        $global_config_ok = 0;
    }

    $title = 'PAYMENT VOUCHER';

    $sql = "SELECT Cash_Point,Voucher_No,gl_acc,GL_Desc,pdate,cheque_no,ledger_code,ledger_desc,payee,toward,Total,input_user 
            FROM `care_ke_payments` 
WHERE cash_point='$cashpoint' AND pay_mode='$payMode' AND voucher_no='$voucherNo'";
    $result = $db->Execute($sql);
//        echo $sql;
    $row = $result->FetchRow();


    $imagePath="../../../icons/logo3.jpg";
    $image = Zend_Pdf_Image::imageWithPath($imagePath);
    $page->drawImage($image, $leftPos + 25, $topPos-80, 500, $topPos - 5);

    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD);
    $titleStyle = new Zend_Pdf_Style ();
    $titleStyle->setFont($font, 18);
    $page->setStyle($titleStyle);
//    $page->drawText($company, $leftPos + 180, $topPos - 25);

    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES);
    $titleStyle = new Zend_Pdf_Style ();
    $titleStyle->setFont($font, 10);
    $page->setStyle($titleStyle);
//    $page->drawText($address.' - '.$postal.' '.$town, $leftPos + 240, $topPos - 40);
//    $page->drawText($tel, $leftPos + 260, $topPos - 60);
//    $page->drawText($email, $leftPos + 240, $topPos - 80);


    $topPos=$topPos-110;

    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
    $headlineStyle2 = new Zend_Pdf_Style ();
    $headlineStyle2->setFont($font, 13);
    $page->setStyle($headlineStyle2);
    $page->drawText($title, $leftPos + 220, $topPos - 36);
    $page->drawRectangle($leftPos + 37, $topPos - 25, $leftPos + 500, $topPos - 25, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);
    $page->drawRectangle($leftPos + 37, $topPos - 39, $leftPos + 500, $topPos - 39, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);

    $headlineStyle = new Zend_Pdf_Style ();
    $headlineStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $headlineStyle->setFont($font, 10);
    $page->setStyle($headlineStyle);
//    $page->drawText($company, $leftPos + 36, $topPos - 50);
//    $page->drawText($address, $leftPos + 36, $topPos - 65);
//    $page->drawText($town, $leftPos + 36, $topPos - 80);
//    $page->drawText($tel, $leftPos + 36, $topPos - 100);

    $page->drawText("Voucher No: " . $row[Voucher_No], $leftPos + 400, $topPos - 50);
    $page->drawText("Date: " . $row[pdate], $leftPos + 400, $topPos - 65);
    $page->drawText("Cheque No: " . $row[cheque_no], $leftPos + 400, $topPos - 80);
    $page->drawText("Payee:   " . $row[payee], $leftPos + 36, $topPos - 60);
    // $page->drawText("VAT:   VAT Withholding Agents: ", $leftPos + 250, $topPos - 130);
    $page->drawText("Towards: " . $row[toward], $leftPos + 36, $topPos - 80);
    // $page->drawText("PIN No:P051098935L", $leftPos + 250, $topPos - 150);

    $gl_acc = $row[gl_acc];
    $gl_desc = $row[GL_Desc];
    $inputUser = $row[input_user];

    $topPos=$topPos+50;

    $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD), 9);

//	$page->drawRectangle ( $leftPos + 36, $topPos - 170, $leftPos + 500, $topPos - 170, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE );
    //draw row headings
    $rectStyle = new Zend_Pdf_Style ();
    $rectStyle->setLineDashingPattern(array(2), 1.6);
    $rectStyle->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
    $rectStyle->setFont($font, 8);
    $page->setStyle($rectStyle);

    $page->drawRectangle($leftPos + 36, $topPos - 160, $leftPos + 500, $topPos - 160, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);
    $page->drawRectangle($leftPos + 36, $topPos - 180, $leftPos + 500, $topPos - 180, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);

    $page->drawText('Account:', $leftPos + 36, $topPos - 170);
    $page->drawText('Name', $leftPos + 150, $topPos - 170);
    $page->drawText('Amount', $leftPos + 450, $topPos - 170);

    $currpoint = 125;
    $sql = "SELECT Cash_Point,Voucher_No,pdate,cheque_no,ledger_code,ledger_desc,payee,toward,Total,amount FROM `care_ke_payments` 
WHERE cash_point='$cashpoint' AND pay_mode='$payMode' AND voucher_no='$voucherNo'";
    $result = $db->Execute($sql);
    //$row = $result->FetchRow ();

    $resultsStyle = new Zend_Pdf_Style ();
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $resultsStyle->setFont($font, 9);
    $page->setStyle($resultsStyle);

    while ($row = $result->FetchRow()) {
        $currpoint = 190;
        $page->drawText($row ['ledger_code'], $leftPos + 36, $topPos - $currpoint);
        $page->drawText($row ['ledger_desc'], $leftPos + 150, $topPos - $currpoint);
        $page->drawText(number_format($row ['amount'], 2), $leftPos + 450, $topPos - $currpoint);

        $topPos = $topPos - 20;
    }
    $topPos = $topPos - $currpoint;


    $sql = "SELECT SUM(amount) AS Total FROM `care_ke_payments` 
            WHERE cash_point='$cashpoint' AND pay_mode='$payMode' AND voucher_no='$voucherNo'";
    $result = $db->Execute($sql);
    $row = $result->FetchRow();
//    echo $sql;

    $page->drawText('Total:', $leftPos + 400, $topPos - 20);
    $page->drawText(number_format($row ['Total'], 2), $leftPos + 450, $topPos - 20);
    $page->drawRectangle($leftPos + 400, $topPos - 10, $leftPos + 500, $topPos - 10, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);
    $page->drawRectangle($leftPos + 400, $topPos - 25, $leftPos + 500, $topPos - 25, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);

//	$page->drawRectangle ( $leftPos + 35, $topPos - 150, $leftPos + 700, $topPos - 150, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE );

    $page->drawText("Account(CR):", $leftPos + 36, $topPos - 60);
    $page->drawText($gl_acc, $leftPos + 100, $topPos - 60);
    $page->drawText(" " . $gl_desc, $leftPos + 120, $topPos - 60);
    $page->drawText("Amount in words:", $leftPos + 36, $topPos - 80);

    $amtWords = convert_number($row ['Total']) . ' ONLY';
    $y = $topPos - 80;
    $lines = explode("\n", getWrappedText(strtoupper($amtWords), $resultsStyle, 400));
    foreach ($lines as $line) {
        $page->drawText($line, $leftPos + 120, $y);
        $y-=15;
//        $leftPos=$leftPos-36;
    }
    $leftPos = 20;
//        $page->drawText ( strtoupper($amtWords).' ONLY', $leftPos + 120, $topPos - 80 );


    $page->drawText("COMMENTS:", $leftPos + 36, $topPos - 130);
    $page->drawRectangle($leftPos + 36, $topPos - 140, $leftPos + 500, $topPos - 140, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);
    $page->drawRectangle($leftPos + 36, $topPos - 170, $leftPos + 500, $topPos - 170, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);
    $page->drawRectangle($leftPos + 36, $topPos - 200, $leftPos + 500, $topPos - 200, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);
    $page->drawRectangle($leftPos + 36, $topPos - 230, $leftPos + 500, $topPos - 230, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);


//          $page->drawRectangle ( $leftPos + 36, $topPos -298, $leftPos + 50, $topPos -298, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE );
    $page->drawText("_______________", $leftPos + 34, $topPos - 330);
    $page->drawText("Prepared By:", $leftPos + 36, $topPos - 340);
    $page->drawText($inputUser, $leftPos + 36, $topPos - 350);

//         $page->drawRectangle ( $leftPos + 160, $topPos -299, $leftPos + 210, $topPos -299, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE );
//    $page->drawText("_______________", $leftPos + 135, $topPos - 330);
//    $page->drawText("Checked By:", $leftPos + 140, $topPos - 340);
    $page->drawText("_______________", $leftPos + 200, $topPos - 330);
    $page->drawText("Verified By:", $leftPos + 205, $topPos - 340);
    $page->drawText("_______________", $leftPos + 345, $topPos - 330);
    $page->drawText("Approved By:", $leftPos + 350, $topPos - 340);
    $page->drawText("_______________", $leftPos + 465, $topPos - 330);
    $page->drawText("Received By:", $leftPos + 470, $topPos - 340);


}

?>
