<?php

require ('roots.php');
require ($root_path . 'include/inc_environment_global.php');
$pid = $_REQUEST['pid'];
$receipt = $_REQUEST['receipt'];
$billNumber = $_GET["billNumber"];



createOccupancy($db,$pdfBase);


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

function getBalance($pid,$sign,$enc_nr){
    global $db;
    
    $sql="select sum(b.total) as total from care_ke_billing b
    where b.pid=$pid and b.encounter_nr=$enc_nr and b.service_type $sign'Payment' and 
    b.`IP-OP`=1 group by b.pid";
    $request=$db->Execute($sql);
    if($row=$request->FetchRow()){
       return $row[0];
    }else{
        return '0';
    }
    
}

function getTotals($sign){
    global $db;
    
    $sql="select sum(b.total) as total from care_ke_billing b
    where b.service_type $sign'Payment' and 
    b.`IP-OP`=1";
    $request=$db->Execute($sql);
    if($row=$request->FetchRow()){
       return $row[0];
    }else{
        return '0';
    }
}

function createOccupancy($db,$pdfBase) {
    require ('roots.php');
    require_once 'Zend/Pdf.php';
    $pdf = new Zend_Pdf ();

    require ($root_path . 'include/care_api_classes/Library_Pdf_Base.php');
    $pdfBase = new Library_Pdf_Base();

    $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4_LANDSCAPE);
    require '../../../include/care_api_classes/class_ward.php';
    //require('../../../include/class_ward.php');
    //require('../../../include/care_api_classes/class_encounter.php');
    $wrd = new Ward ();
    // $obj_enr=new Encounter();

    $pageHeight = $page->getHeight();
    $width = $page->getWidth();
    $topPos = $pageHeight - 10;
    $leftPos = 5;
  
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
    
    $headlineStyle = new Zend_Pdf_Style ();
    $headlineStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD);
    $headlineStyle->setFont($font, 10);
    $page->setStyle($headlineStyle);
    $page->drawText($company, $leftPos + 36, $topPos - 15);
    $page->drawText($address, $leftPos + 36, $topPos - 35);
    $page->drawText($town . ' - ' . $postal, $leftPos + 36, $topPos - 55);
    $page->drawText($tel, $leftPos + 36, $topPos - 75);
    
    $title = 'WARD OCCUPANCY';

    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
    $headlineStyle2 = new Zend_Pdf_Style ();
    $headlineStyle2->setFont($font, 13);
    $page->setStyle($headlineStyle2);
    $page->drawText($title, $leftPos + 300, $topPos - 15);
    $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD), 9);

    $page->drawText('Date:  ' . date('d-m-Y'), $leftPos + 600, $topPos - 15);
    $page->drawRectangle($leftPos + 10, $topPos - 90, $leftPos + 800, $topPos - 90, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);

    $headlineStyle4 = new Zend_Pdf_Style ();
    $headlineStyle4->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD);
    $headlineStyle4->setFont($font, 10);
    $page->setStyle($headlineStyle4);
    $page->drawText('PID:', $leftPos + 10, $topPos - 100);
    $page->drawText('Names:      ', $leftPos + 80, $topPos - 100);
    $page->drawText('Adm Date:   ', $leftPos + 190, $topPos - 100);
    $page->drawText('BillNumber:   ', $leftPos + 250, $topPos - 100);
    $page->drawText('Days:   ', $leftPos + 310, $topPos - 100);
    $page->drawText('Ward:      ', $leftPos + 340, $topPos - 100);
    $page->drawText('Bed:     ', $leftPos + 455, $topPos - 100);

    $page->drawText('Bill Amount: ', $leftPos + 480, $topPos - 100);
    $page->drawText('Deposit: ', $leftPos + 550, $topPos - 100);
    $page->drawText('Balance:       ', $leftPos + 610, $topPos - 100);
    $page->drawText('Payment Method:   ', $leftPos + 680, $topPos - 100);

    $sql = "SELECT p.pid,e.encounter_nr, CONCAT(p.name_first,' ',p.name_last,' ',p.name_2) as names,e.encounter_date,
           e.current_ward_nr,w.name as wardnames,b.`bill_number`,DATEDIFF(NOW(),e.`encounter_date`) AS BedDays ,SUM(IF( b.service_type NOT IN('payment','NHIF'),total,0)) AS bill,
           SUM(IF(b.service_type IN ('payment','NHIF'),total,0)) AS payment,b.`bill_number`,c.`name` as company
           FROM care_encounter e
            LEFT JOIN care_ke_billing b ON e.encounter_nr=b.`encounter_nr`
            LEFT JOIN care_person p  ON e.pid=p.pid
            LEFT JOIN care_ward w ON e.current_ward_nr=w.nr
            LEFT JOIN care_tz_company c ON p.`insurance_ID`=c.`id`
            WHERE e.encounter_class_nr=1 AND e.is_discharged<>1 AND e.`encounter_class_nr`=1
            GROUP BY pid
            ORDER BY w.name ASC";
    $results=$db->Execute($sql);     

    $currpoint=120;
    $billTotal=0;
    $paymentTotal=0;
    $totalBal=0;
    $count=0;
    while ($row = $results->FetchRow()) {
        if ($topPos < 120) {
            array_push($pdf->pages, $page);
            $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4_LANDSCAPE);
            $resultsStyle = new Zend_Pdf_Style ();
            $resultsStyle->setLineDashingPattern(array(2), 1.6);
            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES);
            $resultsStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
            $resultsStyle->setFont($font, 9);
            $page->setStyle($resultsStyle);
            $pageHeight = $page->getHeight();
            $topPos = $pageHeight - 20;
            $currpoint = 30;
            $page->setStyle($headlineStyle4);
                $page->drawText('PID:', $leftPos + 10, $topPos - 15);
                $page->drawText('Names:      ', $leftPos + 100, $topPos - 15);
                $page->drawText('Adm Date:   ', $leftPos + 200, $topPos - 15);
                $page->drawText('BillNumber:   ', $leftPos + 250, $topPos - 15);
                $page->drawText('Days:   ', $leftPos + 310, $topPos - 15);
                $page->drawText('Ward:      ', $leftPos + 340, $topPos - 15);
                $page->drawText('Bed:     ', $leftPos + 460, $topPos - 15);

                $page->drawText('Bill Amount: ', $leftPos + 480, $topPos - 15);
                $page->drawText('Deposit: ', $leftPos + 550, $topPos - 15);
                $page->drawText('Balance:       ', $leftPos + 610, $topPos - 15);
                $page->drawText('Payment Method:   ', $leftPos + 680, $topPos - 15);
                $page->drawRectangle($leftPos + 10, $topPos - 20, $leftPos + 760, $topPos - 20, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);
        }  
            
            $resultsStyle = new Zend_Pdf_Style ();
            $resultsStyle->setLineDashingPattern(array(2), 1.6);
            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES);
            $resultsStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
            $resultsStyle->setFont($font, 9);
            $page->setStyle($resultsStyle);

        if(!$row[company]){
            $paymentMethod='CASH PAYMENT';
        }else{
            $paymentMethod=$row[company];
        }
            
        $page->drawText($row['pid'], $leftPos + 10, $topPos - $currpoint);
        $page->drawText($row['names'], $leftPos + 45, $topPos - $currpoint);
        $page->drawText($row['encounter_date'], $leftPos + 190, $topPos - $currpoint);
        $page->drawText($row['bill_number'], $leftPos + 250, $topPos - $currpoint);
        $page->drawText($row['BedDays'], $leftPos + 315, $topPos - $currpoint);
        $page->drawText($row['wardnames'], $leftPos + 340, $topPos - $currpoint);
        $page->drawText($row['current_ward_nr'], $leftPos + 460, $topPos - $currpoint);
        
//        $bill=getBalance($row['pid'],'<>',$row['encounter_nr']);
//        $depo=getBalance($row['pid'],'=',$row['encounter_nr']);
        $bill=$row[bill];
        $depo=$row[payment];
        
         $bal=intval($bill-$depo);

        $pdfBase->drawText($page, number_format($bill, 2), $leftPos + 530, $topPos - $currpoint, $leftPos + 510, right);
        $pdfBase->drawText($page, number_format($depo, 2), $leftPos + 580, $topPos - $currpoint, $leftPos + 560, right);
        $pdfBase->drawText($page, number_format($bal, 2), $leftPos + 640, $topPos - $currpoint, $leftPos + 640, right);

        $page->drawText( $paymentMethod, $leftPos + 680, $topPos - $currpoint);
        $topPos = $topPos - 20;
        
        $billTotal=$billTotal+$bill;
          $paymentTotal=$paymentTotal+ $depo;
        $count=$count+1;
    }
   
    $totalBill=$billTotal;//getTotals('<>');
    $totalDepo=$paymentTotal;//getTotals('=');
    $totalBal=intval($totalBill-$totalDepo);
    $currpoint=$currpoint+10;
     
     $resultsStyle = new Zend_Pdf_Style ();
    $resultsStyle->setLineDashingPattern(array(2), 1.6);
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD);
    $resultsStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $resultsStyle->setFont($font, 9);
    $page->setStyle($resultsStyle);
    
    $page->drawRectangle($leftPos + 10, $topPos - 20, $leftPos + 800, $topPos - 20, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);
    
    $page->drawText( number_format($totalBill,2), $leftPos + 500, $topPos - $currpoint);
    $page->drawText( number_format($totalDepo,2), $leftPos + 600, $topPos - $currpoint);
    $page->drawText( number_format($totalBal,2), $leftPos + 700, $topPos - $currpoint);

    $page->drawText("Total Patients in Wards are ".$count, $leftPos + 10, $topPos - $currpoint);
    
  
    $topPos = $topPos - 10;
    array_push($pdf->pages, $page);
    header('Content-type: application/pdf');
    echo $pdf->render();
}

?>
