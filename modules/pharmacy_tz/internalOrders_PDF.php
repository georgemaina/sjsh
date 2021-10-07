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

$title = 'INTERNAL REQUISITION';

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
$page->drawRectangle($leftPos + 10, $topPos - 100, $leftPos + 820, $topPos - 130, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
$page->drawRectangle($leftPos + 10, $topPos - 100, $leftPos + 820, $topPos - 800, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

//$page->drawText('OrderNo', $leftPos + 10, $topPos - 115);
//$page->drawText('Order Date:', $leftPos + 50, $topPos - 115);


$items=array(
    array("Order No",10,20),
    array('Order Date',50,20),
    array('Stock Code',120,20),
    array('Description',180,20),
    array('Unit Cost',370,20),
    array('Qty Hand',417,15),
    array('Qty Ordered',450,15),
    array('Qty Serviced',500,15),
    array('Pending Qty',560,20),
    array('Total Cost',620,20),
    array('Ordered From',670,20),
    array('Ordered By',760,20)
);

for($row=0;$row<12;$row++){
        $y = $topPos-100;
        $lines = explode("\n", getWrappedText($items[$row][0], $headlineStyle, $items[$row][2]));
        foreach ($lines as $line) {
            $page->drawText($line, $leftPos + $items[$row][1], $y);
            $y-=13;
        }
}

$rstatus = $_REQUEST['ordStatus'];
$ordLoc = $_REQUEST['ordLoc'];
$orddt1 = $_REQUEST['orddt1'];
$orddt2 = $_REQUEST['orddt2'];
$inputUser = $_REQUEST['$inputUser'];

if ($rstatus == 'Pending') {
    $sql = "SELECT r.req_no, r.status, r.issueNo, r.req_date, r.req_time, r.Store_desc, r.sup_storeDesc, r.item_id,'' AS at_hand, r.Item_desc,
                    r.qty AS ordered, d.`unit_price`, r. unit_msr, r.unit_cost, r.qty_issued, r.issue_date, r.issue_time, r.balance, (d.`unit_price` * r.qty) AS Total, r.period, r.input_user
                    FROM `care_ke_internalreq` r LEFT JOIN care_tz_drugsandservices d ON r.item_id=d.`partcode`
                where r.status='Pending'";
    $statDate = 'Order Date';
    $statQty = 'Qty Ordered';
} elseif ($rstatus == 'Serviced') {

    $sql = "SELECT req_no, STATUS, issueNo, req_date, req_time, Store_desc, sup_storeDesc, s.item_id, Item_desc, qty AS ordered, d.`unit_price`, unit_msr,
                qty_issued, issue_date, issue_time, balance, (d.`unit_price`*s.`qty_issued`) AS Total, period, input_user FROM `care_ke_internalserv` s
                LEFT JOIN care_tz_drugsandservices d ON s.`item_id`=d.`partcode` WHERE status='serviced' ";
    $statDate = 'Service Date';
    $statQty = 'Qty Serviced';
} else {
    $sql = "";
}

if ($ordLoc) {
    $sql = $sql . ' and Store_loc="' . $ordLoc . '"';
} else {
    $sql = $sql . '';
}

$dt1 = new DateTime($orddt1);
$dto1 = $dt1->format('Y-m-d');
$dt2 = new DateTime($orddt2);
$dto2 = $dt2->format('Y-m-d');

if ($orddt1 <> '' && $orddt2 == '') {
    $sql = $sql . ' and req_date="' . $dto1 . '"';
}
if ($orddt1 == '' && $orddt2 <> '') {
    $sql = $sql . ' and req_date="' . $dto2 . '"';
}
if ($orddt1 <> "" && $orddt2 <> "") {
    $sql = $sql . ' and req_date between "' . $dto1 . '" and "' . $dto2 . '"';
} else {
    $sql = $sql . '';
}

if ($inputUser <> '') {
    $sql = $sql . " and input_user  like '$inputUser%'";
}
$request = $db->Execute($sql);

//echo $sql;
$currpoint = 140;
$rectStyle = new Zend_Pdf_Style ();
$rectStyle->setLineDashingPattern(array(2), 1.6);
$rectStyle->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
$rectStyle->setFont($font, 10);
$page->setStyle($rectStyle);

$totalCost=0;
while ($row = $request->FetchRow()) {
    if ($topPos < 180) {
        array_push($pdf->pages, $page);
        $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4_LANDSCAPE);
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
        $page->drawRectangle($leftPos + 10, $topPos - 20, $leftPos + 800, $topPos - 800, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    }
    $total = intval($row['total'] + $total);
    if ($rstatus == 'Pending') {
        $rdate= $row['req_date'];
    } elseif ($rstatus == 'Serviced') {
        $rdate=$row[issue_date];
    }

    if ($rstatus == 'Pending') {
        $atHand=$row['at_hand'];
    } else {
        $atHand='';
    }

    if ($rstatus == 'Pending') {
        $serviced='';
        $qtyPending=$row['ordered'];
    } else {
        $serviced=$row['qty_issued'];
        $qtyPending=$row['unit_msr'] ;
    }

    $page->drawText($row['req_no'], $leftPos + 12, $topPos - $currpoint);
    $page->drawText($rdate, $leftPos + 50, $topPos - $currpoint);
    $page->drawText($row['item_id'], $leftPos + 120, $topPos - $currpoint);

    $y = $topPos-$currpoint;
    $lines = explode("\n", getWrappedText($row['Item_desc'], $headlineStyle, 170));
    foreach ($lines as $line) {
        $page->drawText($line, $leftPos + 180, $y);
        $y-=10;
    }

    if($row['unit_price']<>''){
        $price=$row['unit_price'];
    }else{
        $price=0;
    }
    $pdfBase->drawText($page, number_format($price, 2), $leftPos + 400, $topPos - $currpoint, $leftPos + 400, right);
    $page->drawText($atHand, $leftPos + 420, $topPos - $currpoint);
    $page->drawText($row['ordered'], $leftPos + 455, $topPos - $currpoint);
    $page->drawText($serviced, $leftPos + 505, $topPos - $currpoint);
    $page->drawText($qtyPending, $leftPos + 560, $topPos - $currpoint);
    $pdfBase->drawText($page, number_format($row['Total'], 2), $leftPos + 655, $topPos - $currpoint, $leftPos + 655, right);

    $y = $topPos-$currpoint+10;
    $lines = explode("\n", getWrappedText($row['Store_desc'], $headlineStyle, 15));
    foreach ($lines as $line) {
        $page->drawText($line, $leftPos + 670, $y);
        $y-=10;
    }
    $y = $topPos-$currpoint+10;
    $lines = explode("\n", getWrappedText($row['input_user'], $headlineStyle, 15));
    foreach ($lines as $line) {
        $page->drawText($line, $leftPos + 760, $y);
        $y-=10;
    }
    $totalCost=$totalCost+$row[Total];
    $topPos = $topPos - 30;
}

$totalStyle = new Zend_Pdf_Style ();
$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
$totalStyle->setFont($font, 10);
$page->setStyle($totalStyle);

$page->drawText("TOTAL COST", $leftPos + 540, $topPos - $currpoint);
$pdfBase->drawText($page, number_format($totalCost, 2), $leftPos + 655, $topPos - $currpoint, $leftPos + 655, right);

$currpoint=$currpoint+10;
$page->drawRectangle($leftPos + 10, $topPos - $currpoint, $leftPos + 800, $topPos - $currpoint, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
$topPos = $topPos - $currpoint;

//    $page->drawRectangle($leftPos + 32, $topPos - 230, $leftPos + 500, $topPos - 230, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

$topPos = $topPos - 10;
array_push($pdf->pages, $page);
header('Content-type: application/pdf');
echo $pdf->render();
?>
