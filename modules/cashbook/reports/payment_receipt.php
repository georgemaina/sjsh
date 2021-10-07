<?php

require ('roots.php');
require ($root_path . 'include/inc_environment_global.php');
$pid = $_REQUEST ['pid'];
$cashpoint = $_REQUEST ['cashpoint'];
$voucherNo = $_REQUEST ['voucherNo'];
$payMode = $_REQUEST ['payMode'];
$shiftno = $_REQUEST ['shiftno'];
$cheqID = $_REQUEST ['cheqID'];


//createcheque($db,$cheqID);

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

//function createcheque($db,$cheqID) {
//    require ('roots.php');
    require_once 'Zend/Pdf.php';
    $pdf = new Zend_Pdf ();
    $page = new Zend_Pdf_Page(650, 315);

    $pageHeight = 315;
    $width = 650;
    $topPos = $pageHeight - 10;
    $leftPos = 10;

    $sql = "SELECT cash_point,Voucher_No,Pay_mode,cheque_no,Total FROM care_ke_payments where ID in ($cheqID)";
    $result = $db->Execute($sql);
//    echo $sql;

    while ($row1 = $result->FetchRow()) {
//        echo $sql;
         $cashpoint=$row1[0];
         $voucherNo=$row1[1];
         $payMode=$row1[2];
         
         getCheques($cashpoint,$voucherNo,$payMode,$page,$topPos,$leftPos);
         
           array_push($pdf->pages, $page);
            $page = new Zend_Pdf_Page(650, 315);;
            $headlineStyle = new Zend_Pdf_Style ();
            $headlineStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES);
            $headlineStyle->setFont($font, 10);
            $page->setStyle($headlineStyle);
            
            $pageHeight = 315;
            $width = 650;
            $topPos = $pageHeight - 10;
            $leftPos = 10;
            
            $psql="update care_ke_payments set printed=1 where voucher_no='$voucherNo'";
            $resultp = $db->Execute($psql);
            
       
    }
    $topPos = $topPos - 5;
    array_push($pdf->pages, $page);
    header('Content-type: application/pdf');
    echo $pdf->render();
//}


function getCheques($cashpoint,$voucherNo,$payMode,$page,$topPos,$leftPos){
      global $db;
        $r_sql = "select payee,total from care_ke_payments where 
      cash_point='$cashpoint' AND pay_mode='$payMode' AND voucher_no='$voucherNo'";
        $result = $db->Execute($r_sql);
//        echo $r_sql;
        $row = $result->FetchRow();
        
        $headlineStyle = new Zend_Pdf_Style ();
        $headlineStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES);
        $headlineStyle->setFont($font, 10);
        $page->setStyle($headlineStyle);
        $page->drawText(date('d-m-Y'), $leftPos + 370, $topPos - 20);
        $page->drawText(number_format($row[total], 2), $leftPos + 356, $topPos - 130);
        $page->drawText($row[payee], $leftPos + 30, $topPos - 143);

        $words = convert_number($row['total']);

//        $Data=$Data. "".strtoupper(substr($words, 42)) ."\n";
//    $page->drawText(strtoupper($words), $leftPos + 50, $topPos - 130);
        $y = $topPos - 172;
        $lines = explode("\n", getWrappedText(strtoupper($words), $headlineStyle, 260));
        foreach ($lines as $line) {
            $page->drawText($line, $leftPos + 70, $y);
            $y-=25;
            $leftPos = $leftPos - 40;
        }
        
        
}

?>
