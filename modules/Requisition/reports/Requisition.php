<?php
require ('roots.php');
require ($root_path . 'include/inc_environment_global.php');

require_once 'Zend/Pdf.php';
$pdf = new Zend_Pdf ();

require ($root_path . 'include/care_api_classes/Library_Pdf_Base.php');
$pdfBase = new Library_Pdf_Base();

$reqNo = $_REQUEST ['reqNo'];
$inputUser=$_SESSION['sess_login_username'];


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


$page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);

$pageHeight = $page->getHeight();
$width = $page->getWidth();
$topPos = $pageHeight - 10;
$leftPos = 5;

    $sql = "SELECT `ID`,`RequisitionNo`,`ReqDate`,`Status`,`Comment`,`InputUser`,`Department` FROM `care_ke_requisition` where RequisitionNo='$reqNo'";
    $result = $db->Execute($sql);;
    $row = $result->FetchRow();

    $imagePath="../../../icons/logo3.jpg";
    $image = Zend_Pdf_Image::imageWithPath($imagePath);
    $page->drawImage($image, $leftPos + 25, $topPos-80, 500, $topPos - 5);

      $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD);
    $headingStyle = new Zend_Pdf_Style ();
    $headingStyle->setFont($font, 12);

    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD);
    $titleStyle = new Zend_Pdf_Style ();
    $titleStyle->setFont($font, 10);


    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES);
    $normalStyle = new Zend_Pdf_Style ();
    $normalStyle->setFont($font, 10);

    $page->setStyle($headingStyle);


	$topPos=$topPos-70;
    $page->drawText("REQUISITION FORM", $leftPos + 220, $topPos - 30);
    $page->drawRectangle($leftPos + 36, $topPos - 40, $leftPos + 500, $topPos - 40, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);

 $page->setStyle($titleStyle);



$page->drawText("Requisition No", $leftPos + 36, $topPos - 60);
    $page->drawText($row[RequisitionNo], $leftPos + 120, $topPos - 60);

$page->drawText("Requisition Date", $leftPos + 36, $topPos - 75);
    $page->drawText($row[ReqDate], $leftPos + 120, $topPos - 75);

$page->drawText("Innitiated By", $leftPos + 36, $topPos - 90);
    $page->drawText($row[InputUser], $leftPos + 120, $topPos - 90);

$page->drawText("Department", $leftPos + 36, $topPos - 105);
    $page->drawText($row[Department], $leftPos + 120, $topPos - 105);


	$topPos=$topPos-30;


    $page->drawText("StockID", $leftPos + 36, $topPos - 100);
    $page->drawText("Description", $leftPos + 100, $topPos - 100);
    //$page->drawText("Category ", $leftPos + 150, $topPos - 100);
    $page->drawText("UnitQty", $leftPos + 260, $topPos - 100);
    $page->drawText("Price", $leftPos + 340, $topPos - 100);
    $page->drawText("Quantity", $leftPos + 400, $topPos - 100);
    $page->drawText("Total", $leftPos + 470, $topPos - 100);

    $page->drawRectangle($leftPos + 36, $topPos - 110, $leftPos + 500, $topPos - 110, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);


    $currpoint = 125;
    $sql = "SELECT `RequisitionNo`,`StockID`,`Description`,`Category`,`UnitQty`,`Price`,`Quantity`,`Total` 
				FROM `care_ke_requisitiondetails` where RequisitionNo='$reqNo'";
    $result = $db->Execute($sql);
    //$row = $result->FetchRow ();


     $page->setStyle($normalStyle);
	$currpoint = 130;

	$total=0;
    while ($row = $result->FetchRow()) {
       
        $page->drawText($row ['StockID'], $leftPos + 36, $topPos - $currpoint);
        $page->drawText($row ['Description'], $leftPos + 100, $topPos - $currpoint);
        $page->drawText($row ['UnitQty'], $leftPos + 260, $topPos - $currpoint);
        //$page->drawText(number_format($row ['Price'],2), $leftPos + 340, $topPos - $currpoint);
        $pdfBase->drawText($page, number_format($row['Price'], 2), $leftPos + 370, $topPos - $currpoint, $leftPos + 370, right);
        $page->drawText($row['Quantity'], $leftPos + 410, $topPos - $currpoint);
        //$page->drawText(number_format($row ['Total'], 2), $leftPos + 470, $topPos - $currpoint);
        $pdfBase->drawText($page, number_format($row['Total'], 2), $leftPos + 500, $topPos - $currpoint, $leftPos + 500, right);

        $total=$total+$row['Total'];

        $topPos = $topPos - 20;
    }
    $topPos = $topPos - $currpoint;

     $page->setStyle($titleStyle);

	$page->drawRectangle($leftPos + 460, $topPos - 10, $leftPos + 520, $topPos - 10, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);
    $page->drawText(number_format($total, 2), $leftPos + 470, $topPos - 25);
    $page->drawRectangle($leftPos + 460, $topPos - 30, $leftPos + 520, $topPos - 30, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);
     $page->drawRectangle($leftPos + 460, $topPos - 32, $leftPos + 520, $topPos - 32, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);

   // $page->drawText("COMMENTS:", $leftPos + 36, $topPos - 130);
   // $page->drawRectangle($leftPos + 36, $topPos - 140, $leftPos + 500, $topPos - 140, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);
   // $page->drawRectangle($leftPos + 36, $topPos - 170, $leftPos + 500, $topPos - 170, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);
   // $page->drawRectangle($leftPos + 36, $topPos - 200, $leftPos + 500, $topPos - 200, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);
   // $page->drawRectangle($leftPos + 36, $topPos - 230, $leftPos + 500, $topPos - 230, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);

      $page->setStyle($normalStyle);

    $page->drawText($inputUser, $leftPos + 36, $topPos - 330);
    $page->drawText("Prepared By:", $leftPos + 36, $topPos - 310);

    $page->drawText("Date ", $leftPos + 200, $topPos - 340);
    $page->drawText("___________________________", $leftPos + 200, $topPos - 330);
    $page->drawText("Signature ", $leftPos + 400, $topPos - 340);
    $page->drawText("___________________________", $leftPos + 400, $topPos - 330);

    $topPos=$topPos+20;

    $page->drawText("_____________________________", $leftPos + 36, $topPos - 400);
    $page->drawText("Verified By:", $leftPos + 36, $topPos - 410);
    $page->drawText("Date ", $leftPos + 200, $topPos - 410);
    $page->drawText("___________________________", $leftPos + 200, $topPos - 400);
    $page->drawText("Signature ", $leftPos + 400, $topPos - 410);
    $page->drawText("___________________________", $leftPos + 400, $topPos - 400);

    $page->drawText("______________________________", $leftPos + 36, $topPos - 450);
    $page->drawText("Approved By:", $leftPos + 36, $topPos - 460);
    $page->drawText("Date ", $leftPos + 200, $topPos - 460);
    $page->drawText("___________________________", $leftPos + 200, $topPos - 450);
    $page->drawText("Signature ", $leftPos + 400, $topPos - 460);
    $page->drawText("___________________________", $leftPos + 400, $topPos - 450);


    $topPos = $topPos - 10;
    array_push($pdf->pages, $page);
    header('Content-type: application/pdf');
    echo $pdf->render();

?>

