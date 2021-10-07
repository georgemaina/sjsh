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

$title = 'INVENTORY QUANTITIES REPORT';

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
$page->drawRectangle($leftPos + 10, $topPos - 90, $leftPos + 550, $topPos - 90, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);

$titleStyle = new Zend_Pdf_Style ();
$titleStyle->setLineDashingPattern(array(2), 1.6);
$titleStyle->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
$titleStyle->setFont($font, 11);
$page->setStyle($titleStyle);
$page->drawRectangle($leftPos + 10, $topPos - 100, $leftPos + 550, $topPos - 120, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
$page->drawRectangle($leftPos + 10, $topPos - 100, $leftPos + 550, $topPos - 800, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

$page->drawText('Part Code', $leftPos + 10, $topPos - 115);
$page->drawText('Description', $leftPos + 80, $topPos - 115);
$page->drawText('Location', $leftPos + 320, $topPos - 115);
$page->drawText('Quantity', $leftPos + 400, $topPos - 115);
$page->drawText('Reorder Level', $leftPos + 460, $topPos - 115);

$catID = $_REQUEST[catID];
$itemName = $_POST[itemName];
$storeid=$_REQUEST[storeid];
$qtyFilter=$_REQUEST[qtyFilter];

$sql = "select b.item_number, k.loccode,b.item_Description,k.quantity,k.reorderlevel,e.item_cat,k.comment
        from care_tz_drugsandservices b inner join care_tz_itemscat e
        on b.category=e.catid
        inner join care_ke_locstock k on k.stockid=b.item_number where b.category <>''";

if ($catID <> '') {
    $sql.=" and b.category ='$catID'";
}

if ($itemName <> '') {
    $sql.=" and b.item_Description ='$itemName'";
}

if ($storeid <> '') {
    $sql.=" and k.loccode ='$storeid'";
}

if($qtyFilter<>""){
    switch($qtyFilter){
        case "neg":
            $sql.=" and k.quantity<0";
            break;
        case "zero":
            $sql.=" and k.quantity=0";
            break;
        case "reorder":
            $sql.=" and k.quantity<k.reorderlevel";
            break;

    }
}

$result = $db->Execute($sql);
//    $numRows = $result->RecordCount();
//echo $sql;

$sql2 = 'select count(item_id) from care_tz_drugsandservices';
$result2 = $db->Execute($sql2);
$total = $result2->FetchRow();
$total = $total[0];

$request = $db->Execute($sql);

//echo $sql;
$currpoint = 130;
$rectStyle = new Zend_Pdf_Style ();
$rectStyle->setLineDashingPattern(array(2), 1.6);
$rectStyle->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_ROMAN);
$rectStyle->setFont($font, 10);
$page->setStyle($rectStyle);

while ($row = $request->FetchRow()) {
    if ($topPos < 180) {
        array_push($pdf->pages, $page);
        $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
        $resultsStyle = new Zend_Pdf_Style ();
        $resultsStyle->setLineDashingPattern(array(2), 1.6);
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_ROMAN);
        $resultsStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
        $resultsStyle->setFont($font, 9);
        $page->setStyle($resultsStyle);
        $pageHeight = $page->getHeight();
        $topPos = $pageHeight - 20;
        $currpoint = 30;


        $page->setStyle($titleStyle);
        $page->drawText('Part Code', $leftPos + 10, $topPos - 15);
        $page->drawText('Description', $leftPos + 80, $topPos - 15);
        $page->drawText('Location', $leftPos + 320, $topPos - 15);
        $page->drawText('Quantity', $leftPos + 400, $topPos - 15);
        $page->drawText('Reorder Level', $leftPos + 460, $topPos - 15);

        $rectStyle = new Zend_Pdf_Style ();
        $rectStyle->setLineDashingPattern(array(2), 1.6);
        $rectStyle->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
        $rectStyle->setFont($font, 9);
        $page->setStyle($rectStyle);

        //            $page->drawRectangle($leftPos + 32, $topPos - 30, $leftPos + 500, $topPos - 148, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
        $page->drawRectangle($leftPos + 10, $topPos - 20, $leftPos + 550, $topPos - 800, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    }
    $total = intval($row[total] + $total);

    $page->drawText($row['item_number'], $leftPos + 12, $topPos - $currpoint);
    $page->drawText($row['item_Description'], $leftPos + 80, $topPos - $currpoint);
    $page->drawText($row['loccode'], $leftPos + 320, $topPos - $currpoint);
    $page->drawText($row['quantity'], $leftPos + 400, $topPos - $currpoint);
    $page->drawText($row['reorderlevel'], $leftPos + 480, $topPos - $currpoint);
    $topPos = $topPos - 20;
}
$page->drawRectangle($leftPos + 10, $topPos - $currpoint, $leftPos + 550, $topPos - $currpoint, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
$topPos = $topPos - $currpoint;

//    $page->drawRectangle($leftPos + 32, $topPos - 230, $leftPos + 500, $topPos - 230, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

$topPos = $topPos - 10;
array_push($pdf->pages, $page);
header('Content-type: application/pdf');
echo $pdf->render();
?>
