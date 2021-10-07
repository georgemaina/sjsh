<?php

require ('roots.php');
require ($root_path . 'include/inc_environment_global.php');

    
$date1 = $_REQUEST[date1];
$date2 = $_REQUEST[date2];
$pid = $_REQUEST[pid];

    require_once 'Zend/Pdf.php';
    $pdf = new Zend_Pdf ();
    $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4_LANDSCAPE);
    $pageHeight = $page->getHeight();
    $width = $page->getWidth();
    $topPos = $pageHeight - 10;
    $leftPos = 8;
    
    
createPrescription($db, $pdf,$page,$topPos,$leftPos,$pid, $date1,$date2);


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


function createPrescription($db, $pdf,$page,$topPos,$leftPos,$pid, $date1,$date2) {
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
            $tel = $data_result ['Tel'];
            $invoice_no = $data_result ['new_bill_nr'];
        }
        $global_config_ok = 1;
    } else {
        $global_config_ok = 0;
    }

    $title = 'Patient Drugs Statement';

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
    $page->drawText($title, $leftPos + 240, $topPos - 36);
   
    $headlineStyle4 = new Zend_Pdf_Style ();
    $headlineStyle4->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER_BOLD);
    $headlineStyle4->setFont($font, 10);
    $page->setStyle($headlineStyle4);
     $page->drawText('Date:  ' . date('d-m-Y'), $leftPos + 550, $topPos - 20);
    $page->drawRectangle($leftPos + 2, $topPos - 90, $leftPos + 600, $topPos - 90, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);

    $page->drawText('Patient No:', $leftPos + 550, $topPos - 35);
    $page->drawText('Name:      ', $leftPos + 550, $topPos - 50);
    $page->drawText('Address:   ', $leftPos + 550, $topPos - 65);
    $page->drawText('Town:      ', $leftPos + 550, $topPos - 80);
    $page->drawText('Phone:     ', $leftPos + 550, $topPos - 95);

        $sql="SELECT id,accno,`name` FROM care_tz_company WHERE id=(SELECT insurance_id FROM 
                care_person WHERE pid=$pid)";
    if($insu_result = $db->Execute($sql)){
        $insu_row=$insu_result->FetchRow();
    }
    $headlineStyle4 = new Zend_Pdf_Style ();
    $headlineStyle4->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER);
    $headlineStyle4->setFont($font, 10);
        $page->setStyle($headlineStyle4);

    if($insu_row[0]<>''){
        $page->drawText('Account No: ', $leftPos + 200, $topPos - 65);
        $page->drawText($insu_row[1], $leftPos + 270, $topPos - 65);
        $page->drawText('Account Name: ', $leftPos + 200, $topPos - 78);
        $page->drawText($insu_row[2], $leftPos + 278, $topPos - 78);
    }
    
    $sql2 = "SELECT
    p.pid
    , p.name_first
    , p.name_2
    , p.name_last
    , p.date_birth
    , p.addr_zip
    , p.cellphone_1_nr
    , p.citizenship
FROM care_person  p
WHERE (p.pid='" . $pid . "')";
    
//echo $sql2;

    $info_result = $db->Execute($sql2);

    if ($info_result) {
        $patient_data = $info_result->FetchRow();

        $headlineStyle = new Zend_Pdf_Style ();
        $headlineStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER);
        $headlineStyle->setFont($font, 10);
        $page->setStyle($headlineStyle);
        $page->drawText($pid, $leftPos + 630, $topPos - 35);
        $page->drawText(ucfirst(strtolower($patient_data ['name_first'])) . ' ' . ucfirst(strtolower($patient_data ['name_2'])) . ' ' . ucfirst(strtolower($patient_data ['name_last'])), $leftPos + 630, $topPos - 50);
        $page->drawText('P.O. Box ' . ucfirst(strtolower($patient_data ['addr_zip'])), $leftPos + 630, $topPos - 65);
        $page->drawText(ucfirst(strtolower($patient_data ['citizenship'])) . ' Postal code ' . $postal, $leftPos + 630, $topPos - 80);
        $page->drawText($patient_data ['cellphone_1_nr'], $leftPos + 630, $topPos - 95);

    } else {
        $page->drawText('Cannot connect database', $leftPos + 400, $topPos - 160);
    }

    //$page->drawRectangle ( $leftPos + 36, $topPos - 170, $leftPos + 500, $topPos - 170, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE );
    //draw row headings
    $rectStyle = new Zend_Pdf_Style ();
    $rectStyle->setLineDashingPattern(array(2), 1.6);
    $rectStyle->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER_BOLD);
    $rectStyle->setFont($font, 10);
    $page->setStyle($rectStyle);
    $page->drawRectangle($leftPos + 1, $topPos - 110, $leftPos + 820, $topPos - 128, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    $page->drawRectangle($leftPos + 1, $topPos - 110, $leftPos + 820, $topPos - 800, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

   $page->drawText('Pid', $leftPos + 2, $topPos - 125);
    $page->drawText('Names', $leftPos + 70, $topPos - 125);
    $page->drawText('Dates', $leftPos + 200, $topPos - 125);
    $page->drawText('Adm', $leftPos + 270, $topPos - 125);
    $page->drawText('PartCode', $leftPos + 300, $topPos - 125);
    $page->drawText('Description', $leftPos + 365, $topPos - 125);
    $page->drawText('Price', $leftPos + 640, $topPos - 125);
    $page->drawText('Qty', $leftPos + 710, $topPos - 125);
    $page->drawText('Total', $leftPos + 770, $topPos - 125);
    

    if($class==''){
        $class=2;
    }
    
    $currpoint = 150;
    $sql = "SELECT p.pid,p.name_first,p.name_last,p.name_2,b.bill_date,b.`IP-OP`,b.partcode,b.service_type,b.Description,
  b.price,b.total AS Total,b.qty AS drug_Count FROM care_ke_billing b LEFT JOIN care_person p ON (b.pid = p.pid)
  WHERE b.service_type = 'drug_list' ";

if (isset($pid) && $pid <> '') {
    $sql.=" and b.pid=$pid";
}

if (isset($date1) && isset($date2) && $date1 <> "" && $date1 <> "") {
    $date = new DateTime($date1);
    $dt1 = $date->format("Y-m-d");

    $date = new DateTime($date2);
    $dt2 = $date->format("Y-m-d");

    $sql = $sql . " and b.bill_date between '$dt1' and '$dt2' ";
} else {
    $sql = $sql . " and b.bill_date<=now()";
}

$sql = $sql . " order by b.bill_date desc";

//    echo $sql;
    //$pid, $receipt,$billNumber,$class
    
    $results = $db->Execute($sql);
    $resultsStyle = new Zend_Pdf_Style ();
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER);
    $resultsStyle->setFont($font, 9);
    $page->setStyle($resultsStyle);

    $sumTotal=0;
    while ($row = $results->FetchRow()) {
        if ($topPos < 230) {
            array_push($pdf->pages, $page);
            $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4_LANDSCAPE);
            $resultsStyle = new Zend_Pdf_Style ();
            $resultsStyle->setLineDashingPattern(array(2), 1.6);
            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER);
            $resultsStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
            $resultsStyle->setFont($font, 9);
            $page->setStyle($resultsStyle);
            $pageHeight = $page->getHeight();
            $topPos = $pageHeight - 20;
            $currpoint = 30;
            $rectStyle = new Zend_Pdf_Style ();
            $rectStyle->setLineDashingPattern(array(2), 1.6);
            $rectStyle->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER);
            $rectStyle->setFont($font, 10);
            $page->setStyle($rectStyle);
            $page->drawRectangle($leftPos + 2, $topPos - $currpoint, $leftPos + 800, $topPos - $currpoint, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
        }
        $page->drawText($row['pid'], $leftPos + 2, $topPos - $currpoint);
        $page->drawText($row['name_first'].' '.$row['name_last'].' '.$row['name_2'], $leftPos + 70, $topPos - $currpoint);
        $page->drawText($row['bill_date'], $leftPos + 200, $topPos - $currpoint);
        $page->drawText($row['IP-OP'], $leftPos + 270, $topPos - $currpoint);
        $page->drawText($row['partcode'], $leftPos + 300, $topPos - $currpoint);
         $page->drawText(substr($row['Description'],0,45), $leftPos + 365, $topPos - $currpoint);
//        $y = $topPos - $currpoint;
//        $lines = explode("\n", getWrappedText($row['Description'], $headlineStyle, 150));
//        foreach ($lines as $line) {
//            $page->drawText($line, $leftPos + 430, $y);
//            $y-=30;
//        }

        if(!empty($row['price']) && is_numeric($row['price'])){
            $price=$row['price'];
        }else if (!is_numeric($row['price'])){
            $price=0;
        }else{
             $price=0;
        }
        if(!empty($row['Total'])){
            $total=$row['Total'];
        }else{
            $total=0;
        }
        
        $page->drawText( number_format($price,2), $leftPos + 640, $topPos - $currpoint);
        $page->drawText($row['drug_Count'], $leftPos + 710, $topPos - $currpoint);
        $page->drawText( number_format($total,2), $leftPos + 770, $topPos - $currpoint);
        $topPos = $topPos - 20;
        $sumTotal=$sumTotal+$total;
        
    }
    $topPos = $topPos - $currpoint;

    $currpoint=15;

    $currpoint=$currpoint+10;
    $resultsStyle = new Zend_Pdf_Style ();
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER_BOLD);
    $resultsStyle->setFont($font, 9);
    $page->setStyle($resultsStyle);
    $page->drawText('Total', $leftPos + 710, $topPos - $currpoint);
    $page->drawText('Ksh.' .  number_format($sumTotal,2), $leftPos + 750, $topPos - $currpoint);
    $currpoint=$currpoint+10;
    $page->drawRectangle($leftPos + 2, $topPos - $currpoint, $leftPos + 820, $topPos - $currpoint, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

    
}
//$topPos = $topPos - 10;
    array_push($pdf->pages, $page);
    header('Content-type: application/pdf');
    echo $pdf->render();

?>
