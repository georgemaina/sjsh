<?php

require ('roots.php');
require ($root_path . 'include/inc_environment_global.php');
$accno = $_REQUEST[acc1];
$date1 = $_REQUEST[strDate1];
$date2 = $_REQUEST[strDate2];
require_once 'Zend/Pdf.php';
$pdf = new Zend_Pdf ();
require ($root_path . 'include/care_api_classes/Library_Pdf_Base.php');
$pdfBase=new Library_Pdf_Base(); 
$page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);

createStatement($db, $accno,$date1,$date2,$pdf,$page,$pdfBase);

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

    function createStatement($db, $accno,$date1,$date2,$pdf,$page,$pdfBase) {
    require ('roots.php');
//    require_once 'Zend/Pdf.php';
    
    require '../../../include/care_api_classes/class_ward.php';
    //require('../../../include/class_ward.php');
    //require('../../../include/care_api_classes/class_encounter.php');
    $wrd = new Ward ();
     
    // $obj_enr=new Encounter();

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

    $title = 'STATEMENT OF ACCOUNT';

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
    $page->drawRectangle($leftPos + 36, $topPos - 130, $leftPos + 500, $topPos - 130, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);

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
    $rectStyle->setFont($font, 10);
    $page->setStyle($rectStyle);
    $page->drawRectangle($leftPos + 10, $topPos - 135, $leftPos + 530, $topPos - 148, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    $page->drawRectangle($leftPos + 10, $topPos - 135, $leftPos + 530, $topPos - 800, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    $page->drawText('ip/op', $leftPos + 10, $topPos - 145);
    $page->drawText('pid:', $leftPos + 40, $topPos - 145);
    $page->drawText('Name', $leftPos + 100, $topPos - 145);
    $page->drawText('Date', $leftPos + 240, $topPos - 145);
    $page->drawText('Bill No', $leftPos + 290, $topPos - 145);
    $page->drawText('DB', $leftPos + 380, $topPos - 145);
    $page->drawText('CD', $leftPos + 430, $topPos - 145);
    if($accno<>'NHIF2')
        $page->drawText('Running Total', $leftPos + 460, $topPos - 145);

    
    
    
       $sql = "SELECT p.pid,CONCAT(TRIM(p.name_first),' ',TRIM(p.name_last),' ',TRIM(p.name_2)) AS pnames,c.name, 
                b.lastTransDate,b.bill_number,b.amount,b.accno,b.encounter_class_nr as encClass,b.transtype
            FROM care_ke_debtortrans b left JOIN care_person p ON b.pid=p.pid 
            left JOIN care_ke_debtors c ON b.accno=c.accno
            WHERE b.accno='$accno' ";
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

    if ($dt1 <> "" && $dt2 <> "") {
        $sql = $sql . " and b.lastTransDate between '$dt1' and '$dt2' ";
    } else if ($dt1 <> '' && $dt2 == '') {
        $sql = $sql . " and b.lastTransDate = '$dt1'";
    } else {
        $sql = $sql . " and b.lastTransDate<=now()";
    }
    $sql = $sql . " ORDER BY b.lastTransDate asc";
    
//    echo $sql;
    
    $results = $db->Execute($sql);
    $resultsStyle = new Zend_Pdf_Style ();
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $resultsStyle->setFont($font, 9);
    $page->setStyle($resultsStyle);

//     $sql2="SELECT pid,rev_code,description,total,bill_date FROM care_ke_billing WHERE rev_code='bal_bf' 
//    AND pid='$accno'";
//    $request2 = $db->Execute($sql2);
//    $row2 = $request2->FetchRow();
//    
     $page->drawText($pid, $leftPos + 30, $topPos - $currpoint);
     $page->drawText(trim($row['name_first']) . ' ' . trim($row['name_last']) . ' ' . trim($row['name_2']), $leftPos + 80, $topPos - $currpoint);
     $page->drawText($row['bill_date'], $leftPos + 230, $topPos - $currpoint);
     $page->drawText($row['bill_number'], $leftPos + 290, $topPos - $currpoint);
    
     $lastMonth=getLastMonth($dt1);
     
    $sql1 = "select sum(AMOUNT) as totalPayments from care_ke_debtortrans 
                where transType='1' and accno='$accno' AND lastTransDate<='$lastMonth'";
    if($debug) echo $sql1;
    
    if($request1 = $db->Execute($sql1)){
         $row1 = $request1->FetchRow();
         $totalPayments = $row1[0];
    }else{
         $totalPayments = 0;
    }
   
     
     
      $sql2 = "select sum(amount) as balance_bf from care_ke_debtorTrans b
              where b.accno='$accno' and b.lastTransDate<='$lastMonth' and transtype=2";
      if($debug) echo $sql2;
      if($request2 = $db->Execute($sql2)){
           $row2 = $request2->FetchRow();
           $totalInvoices=$row2[0];
      }else{
          $totalInvoices=0;
      }
    
   
    
   
            
    $balanceBf = intval($totalInvoices - $totalPayments);
     $runBal=$balanceBf;
     $currpoint = 160;
    $page->drawText("Balance bf", $leftPos + 30, $topPos - $currpoint);
     $page->drawText($lastMonth, $leftPos + 80, $topPos - $currpoint);
      $page->drawText(number_format($balanceBf, 2) , $leftPos + 150, $topPos - $currpoint);
      
      
     $currpoint = 180;
    while ($row = $results->FetchRow()) {
        if ($topPos < 370) {
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
            $leftPo=36;
            $currpoint = 30;

            $rectStyle = new Zend_Pdf_Style ();
            $rectStyle->setLineDashingPattern(array(2), 1.6);
            $rectStyle->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
            $rectStyle->setFont($font, 9);
            $page->setStyle($rectStyle);
//            $page->drawRectangle($leftPos + 32, $topPos - 30, $leftPos + 500, $topPos - 148, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
            $page->drawRectangle($leftPos + 12, $topPos - 20, $leftPos + 530, $topPos - 800, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
        }
        $total = intval($row[total] + $total);
        if($row['encClass']==2){
            $adm="OP";
        }else{
             $adm="IP";
        }
        
         if($row[pid]<>''?$pid=$row[pid]:$pid=$row[accno]);

         //---------------------------------------------------------------------------
        //get last months amount for this bill number
        //get last months amount for this bill number
        $dts = new DateTime($lastMonth);
        $dt4 = $dts->format("Y-m-d");
        $threeMonthsAgo=substractDate($dt4,60);
              
         if($debug) echo $sqlS;
       //----------------------------------------------------------------------------
        
        $page->drawText($adm, $leftPos + 12, $topPos - $currpoint);
         if($row[pid]<>''?$pid=$row[pid]:$pid=$row[accno]);
        $page->drawText($pid, $leftPos + 30, $topPos - $currpoint);
        if($row[pnames]<>''){
            $dnames=$row[pnames];
        }else{
             $dnames=$row[name];
        }
        
           
        $page->drawText($dnames, $leftPos + 80, $topPos - $currpoint);
        $page->drawText($row['lastTransDate'], $leftPos + 230, $topPos - $currpoint);
        $page->drawText($row['bill_number'], $leftPos + 290, $topPos - $currpoint);

//        $total =$row['amount'];
        
        if($row['transtype']==1){
            $DB='';
            $CD=$row['amount'];
            
        }else{
            $DB=$row['amount'];
            $CD='';
        }
        
//        $page->drawText($DB, $leftPos + 350, $topPos - $currpoint);
        $pdfBase->drawText($page, number_format(intval($DB), 2), $leftPos + 390, $topPos - $currpoint,$leftPos + 390,right);
  
//        $page->drawText($CD, $leftPos + 400, $topPos - $currpoint);
        $pdfBase->drawText($page, number_format(intval($CD), 2), $leftPos + 450, $topPos - $currpoint,$leftPos + 440,right);
        
        $runBal=$runBal+$DB-$CD;   
        
        if($accno<>'NHIF2')
            $page->drawText(number_format($runBal, 2), $leftPos + 480, $topPos - $currpoint);
        $currpoint=$currpoint+5;
        $page->drawRectangle($leftPos + 10, $topPos - $currpoint, $leftPos + 530, $topPos - $currpoint, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
        
        $topPos = $topPos - 20;
        $totalBill=$DB+$totalBill;
        $totalpaid=$totalpaid+$CD;
        
    }
//    $bal=intval($totalBill-$totalpaid);
    $page->drawRectangle($leftPos + 10, $topPos - $currpoint, $leftPos + 530, $topPos - $currpoint, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
     $topPos = $topPos - $currpoint;
     if($accno<>'NHIF2')
        $totalBill = $totalBill;
      
    $bal = intval( $totalpaid+$totalBill);
    
    $totalStyle = new Zend_Pdf_Style ();
    $totalStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $font3 = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD);
    $totalStyle->setFont($font3, 10);
    $page->setStyle($totalStyle);
    
    $topPos = $topPos - 20;
    $page->drawText('This Month Balance', $leftPos + 250, $topPos - 20);
    $page->drawText( number_format($totalBill, 2), $leftPos + 350, $topPos - 20);
    $page->drawText( number_format($totalpaid, 2), $leftPos + 410, $topPos - 20);
    //if($accno<>'NHIF2')
    //   $page->drawText( number_format($bal, 2), $leftPos + 450, $topPos - 20);
    $topPos = $topPos - 20;
    $page->drawText('Total Balance', $leftPos + 250, $topPos - 20);
    $page->drawText( number_format($runBal, 2), $leftPos + 350, $topPos - 20);

        $sql="Select otherinfo from care_ke_debtors where accno='$accno'";
        $results=$db->Execute($sql);
        $row=$results->FetchRow();

        $y = $topPos - 60;
        $lines = explode("\n", getWrappedText($row['otherinfo'], $headlineStyle, 500));
        foreach ($lines as $line) {
            $page->drawText($line, $leftPos + 20, $y);
            $y-=20;
//            $leftPos = $leftPos - 40;
        }
//    $page->drawRectangle($leftPos + 32, $topPos - 230, $leftPos + 500, $topPos - 230, Zend_Pdf_Page::SHAPE_DRAW_STROKE);

    $topPos = $topPos - 10;
    array_push($pdf->pages, $page);
    header('Content-type: application/pdf');
    echo $pdf->render();
}

?>
