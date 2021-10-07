<?php

require ('roots.php');
require ($root_path . 'include/inc_environment_global.php');
$accno = $_REQUEST[acc1];
$accno = $_REQUEST[acc1];
$date1 = $_REQUEST[strDate1];
$date2 = $_REQUEST[strDate2];


require_once 'Zend/Pdf.php';
$pdf = new Zend_Pdf ();
$page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);

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

$title = 'STOCK VALUATION';

$headlineStyle = new Zend_Pdf_Style ();
$headlineStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
$headlineStyle->setFont($font, 10);
$page->setStyle($headlineStyle);
$page->drawText($company, $leftPos + 12, $topPos - 40);
$page->drawText($address, $leftPos + 12, $topPos - 55);
$page->drawText($town . ' - ' . $postal, $leftPos + 12, $topPos - 70);
$page->drawText($tel, $leftPos + 12, $topPos - 85);

$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
$headlineStyle2 = new Zend_Pdf_Style ();
$headlineStyle2->setFont($font, 13);
$page->setStyle($headlineStyle2);
$page->drawText($title, $leftPos + 150, $topPos - 20);
$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD), 9);

$page->drawText('Date:  ' . date('d-m-Y'), $leftPos + 350, $topPos - 50);
$page->drawRectangle($leftPos + 10, $topPos - 90, $leftPos + 800, $topPos - 90, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);

$rectStyle = new Zend_Pdf_Style ();
$rectStyle->setLineDashingPattern(array(2), 1.6);
$rectStyle->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
$rectStyle->setFont($font, 11);
$page->setStyle($rectStyle);
$page->drawRectangle($leftPos + 10, $topPos - 100, $leftPos + 570, $topPos - 130, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
$page->drawRectangle($leftPos + 10, $topPos - 100, $leftPos + 570, $topPos - 800, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

//$page->drawText('OrderNo', $leftPos + 10, $topPos - 115);
//$page->drawText('Order Date:', $leftPos + 50, $topPos - 115);


$items=array(
    array("PartCode",10,20),
    array('Location',65,20),
    array('Description',130,20),
    array('Category',300,20),
    array('Quantity',370,20),
    array('Cost',450,20),
    array('TotalCost',500,20)
);

for($row=0;$row<12;$row++){
        $y = $topPos-100;
        $lines = explode("\n", getWrappedText($items[$row][0], $headlineStyle, $items[$row][2]));
        foreach ($lines as $line) {
            $page->drawText($line, $leftPos + $items[$row][1], $y);
            $y-=13;
        }
}

$catID1 = $_REQUEST[catID1];
$catID2 = $_REQUEST[catID2];
$detsum = $_POST[detsum];
$storeid=$_REQUEST[storeid];
$inputUser = $_REQUEST['$inputUser'];

$accDB=$_SESSION['sess_accountingdb'];
$pharmLoc=$_SESSION['sess_pharmloc'];

$sql = "SELECT b.PartCode, k.loccode,b.Item_Description,k.Quantity,k.reorderlevel,e.item_cat,s.`LastCost`,(s.`lastcost` * k.`quantity`) AS TotalCost
                FROM care_tz_drugsandservices b LEFT JOIN care_tz_itemscat e ON b.category=e.catid LEFT JOIN care_ke_locstock k ON k.stockid=b.item_number
                LEFT JOIN $accDB.`stockmaster` s ON k.`stockid`=s.`stockid` WHERE b.category <>'' AND K.`quantity`>0 and s.lastcost>0";

if ($storeid <> '') {
    $sql.=" and k.loccode ='$storeid'";
}

// echo $sql;

$result = $db->Execute($sql);
//    $numRows = $result->RecordCount();
//    echo $sql;

$sql2 = 'select count(item_id) from care_tz_drugsandservices';
$result2 = $db->Execute($sql2);
$total = $result2->FetchRow();
$total = $total[0];


//echo $sql;
$currpoint = 140;
$rectStyle = new Zend_Pdf_Style ();
$rectStyle->setLineDashingPattern(array(2), 1.6);
$rectStyle->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
$rectStyle->setFont($font, 10);
$page->setStyle($rectStyle);

$totalCost=0;
while ($row = $result->FetchRow()) {
    if ($topPos < 180) {
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
        $rectStyle->setFont($font, 9);
        $page->setStyle($rectStyle);
//            $page->drawRectangle($leftPos + 32, $topPos - 30, $leftPos + 500, $topPos - 148, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
        $page->drawRectangle($leftPos + 10, $topPos - 20, $leftPos + 570, $topPos - 800, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    }
    $total = intval($row['TotalCost'] + $total);

    $page->drawText($row['PartCode'], $leftPos + 10, $topPos - $currpoint);
    $page->drawText($row['loccode'], $leftPos + 60, $topPos - $currpoint);
        $y = $topPos-$currpoint;
    $lines = explode("\n", getWrappedText($row['Item_Description'], $headlineStyle, 180));
    foreach ($lines as $line) {
        $page->drawText($line, $leftPos + 120, $y);
        $y-=10;
    }

    $page->drawText($row['item_cat'], $leftPos + 300, $topPos - $currpoint);
    $pdfBase->drawText($page, $row['Quantity'], $leftPos + 400, $topPos - $currpoint, $leftPos + 400, right);

    if($row['LastCost']<>''){
        $price=$row['LastCost'];
    }else{
        $price=0;
    }
    $pdfBase->drawText($page, number_format($price, 2), $leftPos + 470, $topPos - $currpoint, $leftPos + 470, right);
    $pdfBase->drawText($page, number_format($row['TotalCost'], 2), $leftPos + 550, $topPos - $currpoint, $leftPos + 550, right);


    $totalCost=$totalCost+$row['TotalCost'];
    $topPos = $topPos - 30;
}

$totalStyle = new Zend_Pdf_Style ();
$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
$totalStyle->setFont($font, 10);
$page->setStyle($totalStyle);

$page->drawText("TOTAL COST", $leftPos + 400, $topPos - $currpoint);
$pdfBase->drawText($page, number_format($totalCost, 2), $leftPos + 550, $topPos - $currpoint, $leftPos + 550, right);

$currpoint=$currpoint+10;
$page->drawRectangle($leftPos + 10, $topPos - $currpoint, $leftPos + 570, $topPos - $currpoint, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
$topPos = $topPos - $currpoint;

//    $page->drawRectangle($leftPos + 32, $topPos - 230, $leftPos + 500, $topPos - 230, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

$topPos = $topPos - 10;
array_push($pdf->pages, $page);
header('Content-type: application/pdf');
echo $pdf->render();
?>
