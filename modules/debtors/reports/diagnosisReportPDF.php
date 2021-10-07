<?php

require ('roots.php');
require ($root_path . 'include/inc_environment_global.php');
$accno = $_REQUEST[accNo];
$date1 = $_REQUEST[strDate1];
$date2 = $_REQUEST[strDate2];
require_once 'Zend/Pdf.php';
$pdf = new Zend_Pdf ();
require ($root_path . 'include/care_api_classes/Library_Pdf_Base.php');
$pdfBase=new Library_Pdf_Base(); 
$page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);

createReport($db, $accno,$date1,$date2,$pdf,$page,$pdfBase);

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
 * found here, not sure of the author : * http://devzone.zend.com/article/2525-Zend_Pdf-tutorial#comments-2535

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

function substractDate($date,$days)
{
//    $cuurDate = $date;
    $date = new DateTime($date);
    $dt1 = $date->format("d-m-Y");
    $newdate = strtotime ('-'.$days.' day' , strtotime ($dt1)) ;
    $newdate = date ( 'Y/m/d' , $newdate );
   
    return $newdate;
}

function getLastMonth($date) {
    $date = new DateTime(date($date));

    $current_month = $date->format("m");
    ;
    $current_year = $date->format("Y");
    ;
    if ($current_month == 1) {
        $lastmonth = 12;
    } else {
        $lastmonth = $current_month - 1;
    }
    $firstdate = $current_year . "-" . $lastmonth . "-01";

    $timestamp = strtotime($firstdate);

    $lastdateofmonth = date('t', $timestamp); // 	this function will give you the number of days in given month(that will be last date of the month)
//echo '<br>' . $lastdateofmonth;
    $lastdate =  $current_year. "/" . $lastmonth . "/" . $lastdateofmonth;
//    echo '<br>' . $lastdate;
    return $lastdate;
}

function getTotals($sign,$pid) {
        global $db;

        $sql = "select sum(b.total) as total from care_ke_billing b  left join care_encounter e
    on b.pid=e.pid where b.service_type $sign'Payment' and 
    b.pid=$pid";
        $request = $db->Execute($sql);
        if ($row = $request->FetchRow()) {
            return $row[0];
        } else {
            return '0';
        }
    }

function getDiagnosis($pid,$encNo){
    global $db;

    $sql="SELECT icd_10_description FROM care_tz_diagnosis WHERE PID='$pid'
            AND encounter_nr='$encNo'";
    $results=$db->Execute($sql);
    $diags='';
    while($row=$results->FetchRow()){
        $diags=$diags.$row[0].',';
    }

    return $diags;
}

    
    
    function createReport($db, $accno,$date1,$date2,$pdf,$page,$pdfBase) {
    require ('roots.php');
//    require_once 'Zend/Pdf.php';
    
    $pageHeight = $page->getHeight();
    $width = $page->getWidth();
    $topPos = $pageHeight - 10;
    $leftPos = 10;
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

    $title = 'DIAGNOSIS REPORT';

    $headlineStyle = new Zend_Pdf_Style ();
    $headlineStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $headlineStyle->setFont($font, 10);
    $page->setStyle($headlineStyle);
    $page->drawText($company, $leftPos + 330, $topPos - 40);
    $page->drawText($address, $leftPos + 330, $topPos - 55);
    $page->drawText($town . ' - ' . $postal, $leftPos + 330, $topPos - 70);
    $page->drawText($tel, $leftPos + 330, $topPos - 85);

    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
    $headlineStyle2 = new Zend_Pdf_Style ();
    $headlineStyle2->setFont($font, 13);
    $page->setStyle($headlineStyle2);
    $page->drawText($title, $leftPos + 150, $topPos - 20);
    $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD), 9);

    $page->drawText('Date:  ' . date('d-m-Y'), $leftPos + 350, $topPos - 120);
    $page->drawRectangle($leftPos + 10, $topPos - 130, $leftPos + 500, $topPos - 130, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);

    $headlineStyle4 = new Zend_Pdf_Style ();
    $headlineStyle4->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
    $headlineStyle4->setFont($font, 10);
    $page->setStyle($headlineStyle4);
    $page->drawText('Account No:', $leftPos + 36, $topPos - 40);
    $page->drawText('Name:      ', $leftPos + 36, $topPos - 55);
    $page->drawText('Address:   ', $leftPos + 36, $topPos - 70);
    $page->drawText('Town:      ', $leftPos + 36, $topPos - 85);
    $page->drawText('Phone:     ', $leftPos + 36, $topPos - 100);


    $sql2 = "select d.accno,d.name,d.address1,d.address2,d.phone from care_ke_debtors d 
                where d.accno='$accno'";
//	echo $sql2;
    $info_result = $db->Execute($sql2);

    if ($info_result) {
        $patient_data = $info_result->FetchRow();

        $headlineStyle = new Zend_Pdf_Style ();
        $headlineStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
        $headlineStyle->setFont($font, 10);
        
        $page->setStyle($headlineStyle);
        $page->drawText($patient_data ['accno'], $leftPos + 110, $topPos - 40);
        $page->drawText(ucfirst(strtolower($patient_data ['name'])), $leftPos + 110, $topPos - 55);
        $page->drawText(ucfirst(strtolower($patient_data ['address1'])), $leftPos + 110, $topPos - 70);
        $page->drawText(ucfirst(strtolower($patient_data ['address2'])), $leftPos + 110, $topPos - 85);
        $page->drawText($patient_data ['phone'], $leftPos + 110, $topPos - 100);
    } else {
        $page->drawText('Cannot connect database', $leftPos + 110, $topPos - 115);
    }

        //$page->drawRectangle ( $leftPos + 36, $topPos - 170, $leftPos + 500, $topPos - 170, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE );
        //draw row headings
        $rectStyle = new Zend_Pdf_Style ();
        $rectStyle->setLineDashingPattern(array(2), 1.6);
        $rectStyle->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
        $rectStyle->setFont($font, 9);
        $page->setStyle($rectStyle);
        $page->drawRectangle($leftPos + 10, $topPos - 135, $leftPos + 580, $topPos - 148, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
        $page->drawRectangle($leftPos + 10, $topPos - 135, $leftPos + 580, $topPos - 800, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
        $page->drawText('PID', $leftPos + 10, $topPos - 145);
        $page->drawText('Names:', $leftPos + 80, $topPos - 145);
        $page->drawText('Date', $leftPos + 180, $topPos - 145);
        $page->drawText('InvoiceNo', $leftPos + 230, $topPos - 145);
        $page->drawText('MemberNo', $leftPos + 285, $topPos - 145);
        $page->drawText('Amount', $leftPos + 360, $topPos - 145);
        $page->drawText('Diagnosis', $leftPos + 420, $topPos - 145);

        $sql = "SELECT DISTINCT t.pid,p.`MemberNames` AS pnames,
                t.transdate,t.bill_number,t.amount,p.`memberID` AS memberNumber
                ,t.`encounter_nr` FROM care_ke_debtortrans t
                JOIN `care_ke_debtormembers` p ON t.`pid`=p.`PID` WHERE t.accno='$accno'";
    if ($date1) {
        $date = new DateTime($date1);
        $dt1 = $date->format("Y-m-d");
    } else {
        $dt1 = "";
    }
    if ($date2) {
        $dates2 = new DateTime($date2);
        $dt2 = $dates2->format("Y-m-d");
    } else {
        $dt2 = "";
    }

    if ($date1 <> "" && $date2 <> "") {
        $sql = $sql . " and t.transdate between '$date1' and '$date2' ";
    } 
//    else if ($dt1 <> '' && $dt2 == '') {
//        $sql = $sql . " and t.transdate= '$dt1'";
//    } else {
//        $sql = $sql . " and t.transdate<=now()";
//    }
    
    $sql = $sql . " ORDER BY t.transdate asc";
    
  // echo $sql;
    

    $resultsStyle = new Zend_Pdf_Style ();
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $resultsStyle->setFont($font, 9);
    $page->setStyle($resultsStyle);
      
     $currpoint = 160;
        $results = $db->Execute($sql);
        $totalBill=0;
    while ($row = $results->FetchRow()) {
        if ($topPos < 220) {
            array_push($pdf->pages, $page);
            $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
            $resultsStyle = new Zend_Pdf_Style ();
            $resultsStyle->setLineDashingPattern(array(2), 1.6);
            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
            $resultsStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
            $resultsStyle->setFont($font, 7);
            $page->setStyle($resultsStyle);
            $pageHeight = $page->getHeight();
            $topPos = $pageHeight - 20;
            $leftPo=20;
            $currpoint = 30;

            $rectStyle = new Zend_Pdf_Style ();
            $rectStyle->setLineDashingPattern(array(2), 1.6);
            $rectStyle->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
            $rectStyle->setFont($font, 6);
            $page->setStyle($rectStyle);
//            $page->drawRectangle($leftPos + 32, $topPos - 30, $leftPos + 500, $topPos - 148, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
            $page->drawRectangle($leftPos + 12, $topPos - 20, $leftPos + 530, $topPos - 800, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
        }

        $diagnosis=getDiagnosis($row[pid],$row[encounter_nr]);

        $page->drawText($row[pid], $leftPos + 12, $topPos - $currpoint);
        $page->drawText(ucfirst(strtolower($row[pnames])), $leftPos + 60, $topPos - $currpoint);
        $page->drawText($row[transdate], $leftPos + 180, $topPos - $currpoint);
//        $page->drawText($row[bill_number], $leftPos + 250, $topPos - $currpoint);
        $pdfBase->drawText($page, $row[bill_number], $leftPos + 270, $topPos - $currpoint,$leftPos + 270,right);
//        $page->drawText($row[memberNumber], $leftPos + 340, $topPos - $currpoint);
        $pdfBase->drawText($page, $row[memberNumber], $leftPos + 325, $topPos - $currpoint,$leftPos + 325,right);
        $pdfBase->drawText($page, number_format($row[amount],2), $leftPos + 400, $topPos - $currpoint,$leftPos + 400,right);
//        $page->drawText($diagnosis, $leftPos + 400, $topPos - $currpoint);
        $y = $topPos - $currpoint;
        $lines = explode("\n", getWrappedText($diagnosis, $headlineStyle, 150));
        foreach ($lines as $line) {
            $page->drawText($line, $leftPos + 420, $y);
            $y-=10;
            if($lines>1){
                $topPos = $topPos - 10;
            }else{
                $topPos = $topPos - 5;
            }
        }

        $totalBill=$totalBill+$row[amount];
        
        
    }
//    $bal=intval($totalBill-$totalpaid);
    $page->drawRectangle($leftPos + 10, $topPos - $currpoint, $leftPos + 530, $topPos - $currpoint, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
     $topPos = $topPos - $currpoint;

    $topPos = $topPos - 10;
    array_push($pdf->pages, $page);
    header('Content-type: application/pdf');
    echo $pdf->render();
}

?>
