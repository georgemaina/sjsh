<?php

require ('roots.php');
require ($root_path . 'include/inc_environment_global.php');
$accno = $_REQUEST[acc1];
$accno = $_REQUEST[acc1];
$date1 = $_REQUEST[strDate1];
$date2 = $_REQUEST[strDate2];


require_once 'Zend/Pdf.php';
$pdf = new Zend_Pdf ();
$page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4_LANDSCAPE);

require ($root_path . 'include/care_api_classes/Library_Pdf_Base.php');
$pdfBase = new Library_Pdf_Base();

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
        $tel = $data_result ['Tel'];
        $invoice_no = $data_result ['new_bill_nr'];
    }
    $global_config_ok = 1;
} else {
    $global_config_ok = 0;
}

$title = 'Stock Movement';

$headlineStyle = new Zend_Pdf_Style ();
$headlineStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
$headlineStyle->setFont($font, 10);

$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
$headlineStyle2 = new Zend_Pdf_Style ();
$headlineStyle2->setFont($font, 13);

$rectStyle = new Zend_Pdf_Style ();
$rectStyle->setLineDashingPattern(array(2), 1.6);
$rectStyle->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
$rectStyle->setFont($font, 11);

$page->setStyle($headlineStyle);

$page->drawText($company, $leftPos + 12, $topPos - 40);
$page->drawText($address, $leftPos + 12, $topPos - 55);
$page->drawText($town . ' - ' . $postal, $leftPos + 12, $topPos - 70);
$page->drawText($tel, $leftPos + 12, $topPos - 85);


$page->setStyle($headlineStyle2);


$page->drawText('Date:  ' . date('d-m-Y'), $leftPos + 350, $topPos - 50);
$page->drawRectangle($leftPos + 10, $topPos - 90, $leftPos + 800, $topPos - 90, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);

$page->setStyle($rectStyle);
$page->drawRectangle($leftPos + 10, $topPos - 100, $leftPos + 820, $topPos - 130, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
$page->drawRectangle($leftPos + 10, $topPos - 100, $leftPos + 820, $topPos - 800, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

//$page->drawText('OrderNo', $leftPos + 10, $topPos - 115);
//$page->drawText('Order Date:', $leftPos + 50, $topPos - 115);


$items=array(
    array("PartCode",10,20),
    array('Description',50,20),
    array('UnitsMeasure',120,20),
    array('Date',180,20),
    array('TransType',210,20),
    array('TransNo',270,15),
    array('Narration',300,15),
    array('Location',420,15),
    array('Cost',500,20),
    array('Qty',550,20),
    array('StockLevel',600,20),
    array('Operator',650,20)
);

for($row=0;$row<12;$row++){
        $y = $topPos-100;
        $lines = explode("\n", getWrappedText($items[$row][0], $headlineStyle, $items[$row][2]));
        foreach ($lines as $line) {
            $page->drawText($line, $leftPos + $items[$row][1], $y);
            $y-=13;
        }
}

$startDate =  new DateTime($_REQUEST['startDate']);
$EndDate = new DateTime($_REQUEST['sndDate']);
$LocCode = $_REQUEST['locCode'];

$date1 = $startDate->format('Y-m-d');
$date2 = $EndDate->format('Y-m-d');

$sql="select `stkmoveno`,`stockid`,d.`item_description`,d.`unit_measure`,t.`typeName`,s.`transno`,`loccode`,`supLoccode`,`trandate`,`pid`,
            `price`,`qty`,`newqoh`,`hidemovt`,`narrative`,`inputuser` 
          from  `care_ke_stockmovement` s left join care_tz_drugsandservices d on s.`stockid`=d.`partcode`
          left join care_ke_transactionnos t on s.`type`=t.`typeID`";

if ($startDate <> "" && $endDate <> "") {
    $sql = $sql . ' and trandate between "' . $dto1 . '" and "' . $dto2 . '"';
} else {
    $sql = $sql . '';
}

if ($inputUser <> '') {
    $sql = $sql . " and inputuser  like '$inputUser%'";
}
$request = $db->Execute($sql);

//echo $sql;
$currpoint = 140;
$page->setStyle($rectStyle);

$totalCost=0;
while ($row = $request->FetchRow()) {
    if ($topPos < 180) {
        array_push($pdf->pages, $page);
        $page->setStyle($resultsStyle);
        $pageHeight = $page->getHeight();
        $topPos = $pageHeight - 20;
        $currpoint = 30;

        $page->setStyle($rectStyle);
//            $page->drawRectangle($leftPos + 32, $topPos - 30, $leftPos + 500, $topPos - 148, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
        $page->drawRectangle($leftPos + 10, $topPos - 20, $leftPos + 800, $topPos - 800, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    }
    

    $page->drawText($row['stockid'], $leftPos + 12, $topPos - $currpoint);
    $page->drawText($row['item_description'], $leftPos + 50, $topPos - $currpoint);
    $page->drawText($row['unit_measure'], $leftPos + 120, $topPos - $currpoint);
    $page->drawText($row['trandate'], $leftPos + 180,$topPos - $currpoint);
    $page->drawText($row['typeName'], $leftPos + 210, $topPos - $currpoint);
    $page->drawText($row['transno'], $leftPos + 270, $topPos - $currpoint);
    $page->drawText($row['narrative'], $leftPos + 300, $topPos - $currpoint);
    $page->drawText($row['loccode'], $leftPos + 420, $topPos - $currpoint);
    $page->drawText($row['price'], $leftPos + 500, $topPos - $currpoint);
    $page->drawText($row['qty'], $leftPos + 550, $topPos - $currpoint);
    $page->drawText($row['newqoh'], $leftPos + 600, $topPos - $currpoint);
    $page->drawText($row['inputuser'], $leftPos + 650, $topPos - $currpoint);

//    $y = $topPos-$currpoint;
//    $lines = explode("\n", getWrappedText($row['Item_desc'], $headlineStyle, 170));
//    foreach ($lines as $line) {
//        $page->drawText($line, $leftPos + 180, $y);
//        $y-=10;
//    }

    $topPos = $topPos - 30;
}

$currpoint=$currpoint+10;
$page->drawRectangle($leftPos + 10, $topPos - $currpoint, $leftPos + 800, $topPos - $currpoint, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
$topPos = $topPos - $currpoint;

//    $page->drawRectangle($leftPos + 32, $topPos - 230, $leftPos + 500, $topPos - 230, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

$topPos = $topPos - 10;
array_push($pdf->pages, $page);
header('Content-type: application/pdf');
echo $pdf->render();
?>
