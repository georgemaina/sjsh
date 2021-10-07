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

/*    $headlineStyle = new Zend_Pdf_Style ();
    $headlineStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $headlineStyle->setFont($font, 10);
    $page->setStyle($headlineStyle);
    $page->drawText($company, $leftPos + 180, $topPos - 20);
    $page->drawText($address.' '.$town, $leftPos + 180, $topPos - 30);*/

        $imagePath="../../../icons/logo.jpg";
        $image = Zend_Pdf_Image::imageWithPath($imagePath);
        $page->drawImage($image, $leftPos+20, $topPos-70, $leftPos+500, $topPos+10);

        $topPos=$topPos-60;
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
    $headlineStyle1 = new Zend_Pdf_Style ();
    $headlineStyle1->setFont($font, 13);
    $page->setStyle($headlineStyle1);

        $page->drawText("UNPAID BILL FORM", $leftPos + 20, $topPos - 50);

        $sql = "SELECT `accno`,d.`name`,c.`name` AS category,`address1`,`address2`,`phone`,`altPhone`,`contact`,`email`,`joined`,
            `cr_limit`,`OP_Cover`,`IP_Cover`,`OP_Usage`,`IP_Usage`,`OP_Exceed`, `IP_Exceed`,
            `assChief`,`chief`,`creditLimit`, `village`,`villageElder`, `dbStatus`,
            `location`, `nearSchool`,`subLocation`,`guarantorsName`,`guarantorsID`,`guarantorsLocation`,
            `guarantorsSubLoc`, `guarantorsVillage`,`guarantorsAddress`,`guarantorsPhone`,`guarantorsRelation`,
             `guarantorsAmount`,nextPaymentDate,openingBL,otherInfo,statementInfo,openingBL FROM care_ke_debtors d
            LEFT JOIN care_ke_debtorcat c ON d.`category`=c.`code` WHERE accno='$accno'";

        if ($debug)
            echo $sql;
        $results=$db->Execute($sql);
        $row=$results->FetchRow();

        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
        $headlineStyle2 = new Zend_Pdf_Style ();
        $headlineStyle2->setFont($font, 9);
        $page->setStyle($headlineStyle2);
       // $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 9);

        $page->drawText("DATE:                         HOSP. NO:                           DEPT:____________________ UB. NO_____________________", $leftPos + 20, $topPos - 70);
        $page->drawText("PATIENT NAME:", $leftPos + 20, $topPos - 90);
        $page->drawText("FAMILY NAME:", $leftPos + 300, $topPos - 90);
        $page->drawText("LOCATION:", $leftPos + 20, $topPos - 110);
        $page->drawText("SUB. LOCATION:", $leftPos + 300, $topPos - 110);
        $page->drawText("CHIEF:", $leftPos + 20, $topPos - 130);
        $page->drawText("ASS CHIEF:", $leftPos + 300, $topPos - 130);
        $page->drawText("VILLAGE:", $leftPos + 20, $topPos - 150);
        $page->drawText("VILLAGE ELDER:", $leftPos + 300, $topPos - 150);
        $page->drawText("NEAREST SCHOOL:", $leftPos + 20, $topPos - 170);
        $page->drawText("ADDRESS:", $leftPos + 300, $topPos - 170);

        $page->drawText("TOTAL BILL KSH:", $leftPos + 20, $topPos - 190);
        $page->drawText('_____________________', $leftPos + 100, $topPos - 190);
        $page->drawText("UB/EXP.KSH:", $leftPos + 220, $topPos - 190);
        $page->drawText('_____________________', $leftPos + 280, $topPos - 190);
        $page->drawText("RT.NO:", $leftPos + 400, $topPos - 190);
        $page->drawText('_____________________', $leftPos + 430, $topPos - 190);

        $page->drawText("AMOUNT PAID KSH:", $leftPos + 20, $topPos - 210);
        $page->drawText('_________________', $leftPos + 120, $topPos - 210);
        $page->drawText("RECEIPT NO:", $leftPos + 220, $topPos - 210);
        $page->drawText('_________________', $leftPos + 300, $topPos - 210);
        $page->drawText("BALANCE DUE KSHS:", $leftPos + 20, $topPos - 230);
        $page->drawText('_________________', $leftPos + 120, $topPos - 230);
        $page->drawText("BAL TO BE PAID:", $leftPos + 220, $topPos - 230);
        $page->drawText('_________________', $leftPos + 300, $topPos - 230);


        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD_ITALIC);
        $itemsStyle2 = new Zend_Pdf_Style ();
        $itemsStyle2->setFont($font, 8);
        $page->setStyle($itemsStyle2);
       // $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD_ITALIC), 8);


        $page->drawText($row[joined].'                              '.$row[accno], $leftPos + 50, $topPos - 70);
        $page->drawText($row[name], $leftPos + 110, $topPos - 90);
        $page->drawText($row[name], $leftPos + 380, $topPos - 90);
        $page->drawText($row[location], $leftPos + 110, $topPos - 110);
        $page->drawText($row[subLocation], $leftPos + 380, $topPos - 110);
        $page->drawText($row[chief], $leftPos + 110, $topPos - 130);
        $page->drawText($row[assChief], $leftPos + 380, $topPos - 130);
        $page->drawText($row[village], $leftPos + 110, $topPos - 150);
        $page->drawText($row[villageElder], $leftPos + 380, $topPos - 150);
        $page->drawText($row[nearSchool], $leftPos + 110, $topPos - 170);
        $page->drawText($row[address1], $leftPos + 380, $topPos - 170);
        $page->drawText(number_format($row[openingBL],2), $leftPos + 120, $topPos - 190);



        $page->setStyle($headlineStyle1);
        $page->drawText("GUARANTORS DETAILS", $leftPos + 20, $topPos - 260);


        $page->setStyle($headlineStyle2);
        $page->drawText("I:", $leftPos + 20, $topPos - 300);
        $page->drawText("ID.NO:", $leftPos + 200, $topPos - 300);
        $page->drawText("LOCATION:", $leftPos + 20, $topPos - 320);
        $page->drawText("SUB. LOCATION: ", $leftPos + 200, $topPos - 320);
        $page->drawText("VILLAGE:", $leftPos + 380, $topPos - 320);
        $page->drawText("ADDRESS:", $leftPos + 20, $topPos - 340);
        $page->drawText("TEL:", $leftPos + 200, $topPos - 340);
        $page->drawText("GUARANTEE THE PAYMENT OF KSHS:____________________", $leftPos + 20, $topPos - 360);
        $page->drawText("SIGN:__________________________________ ", $leftPos + 300, $topPos - 360);
        $page->drawText("DATE:", $leftPos + 20, $topPos - 380);
        $page->drawText("RELATIONSHIP TO PATIENT:", $leftPos + 200, $topPos - 380);
        $page->drawText("FILLED BY___________________________________SIGN:_____________________________DATE:_____________________", $leftPos + 20, $topPos - 410);
        $page->drawText("AUTHORIZED BY:______________________________SIGN:_____________________________DATE:_____________________", $leftPos + 20, $topPos - 440);



        $page->setStyle($itemsStyle2);
        $page->drawText($row[guarantorsName],$leftPos + 30, $topPos - 300);
        $page->drawText($row[guarantorsID],$leftPos + 230, $topPos - 300);
        $page->drawText($row[guarantorsLocation],$leftPos + 80, $topPos - 320);
        $page->drawText($row[guarantorsSubLoc],$leftPos + 280, $topPos - 320);
        $page->drawText($row[guarantorsVillage],$leftPos + 420, $topPos - 320);
        $page->drawText($row[guarantorsAddress], $leftPos + 80, $topPos - 340);
        $page->drawText($row[guarantorsPhone], $leftPos + 230, $topPos - 340);
        $page->drawText(number_format($row[guarantorsAmount],2), $leftPos + 200, $topPos - 360);
        $page->drawText(date('Y-m-d') , $leftPos + 80, $topPos - 380);
        $page->drawText($row[guarantorsRelation] , $leftPos + 350, $topPos - 380);

        $rectStyle = new Zend_Pdf_Style ();
        $rectStyle->setLineWidth(1, 1.8);
        $rectStyle->setLineColor(new Zend_Pdf_Color_GrayScale(0.8));
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
        $rectStyle->setFont($font, 10);
        $page->setStyle($rectStyle);

        $page->drawRectangle($leftPos + 10, $topPos - 460, $leftPos + 520, $topPos - 680, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
        $currPoint=480;
        for($i=0;$i<=10;$i++){
            $page->drawLine($leftPos + 10, $topPos - $currPoint, $leftPos + 520, $topPos - $currPoint);
            $currPoint=$currPoint+20;
        }

        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
        $thStyle2 = new Zend_Pdf_Style ();
        $thStyle2->setFont($font, 10);
        $page->setStyle($thStyle2);


        $sql="SELECT lastTransDate,transDate,bill_number,amount,invoiceBalance FROM care_ke_debtortrans
                  WHERE accno='$accno' AND transtype=1";
        $result=$db->Execute($sql);
        $currPoint=495;
        while($row=$result->fetchRow()){
            $page->drawText($row[lastTransDate], $leftPos + 25, $topPos - $currPoint);
            $page->drawText($row[transDate], $leftPos + 85, $topPos - $currPoint);
            $page->drawText($row[transDate], $leftPos + 165, $topPos - $currPoint);
            $page->drawText($row[bill_number], $leftPos + 245, $topPos - $currPoint);
            $page->drawText($row[amount], $leftPos + 335, $topPos - $currPoint);
            $page->drawText($row[invoiceBalance], $leftPos + 425, $topPos - $currPoint);
            $currPoint=$currPoint+20;
        }


        $page->drawLine($leftPos + 80, $topPos - 460, $leftPos + 80, $topPos - 680);
        $page->drawLine($leftPos + 160, $topPos - 460, $leftPos + 160, $topPos - 680);
        $page->drawLine($leftPos + 240, $topPos - 460, $leftPos + 240, $topPos - 680);
        $page->drawLine($leftPos + 330, $topPos - 460, $leftPos + 330, $topPos - 680);
        $page->drawLine($leftPos + 420, $topPos - 460, $leftPos + 420, $topPos - 680);

        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
        $thStyle2 = new Zend_Pdf_Style ();
        $thStyle2->setFont($font, 10);
        $page->setStyle($thStyle2);


        $page->drawText("Date", $leftPos + 25, $topPos - 475);
        $page->drawText("Reminded", $leftPos + 85, $topPos - 475);
        $page->drawText("Date Paid", $leftPos + 165, $topPos - 475);
        $page->drawText("Receipt No", $leftPos + 245, $topPos - 475);
        $page->drawText("Amount Paid", $leftPos + 335, $topPos - 475);
        $page->drawText("Balance", $leftPos + 425, $topPos - 475);

        $page->drawText("Remarks/Reason........................................................................................................................................................", $leftPos + 25, $topPos - 720);
        $page->drawText(".....................................................................................................................................................................................", $leftPos + 25, $topPos - 750);


        $topPos = $topPos - 10;
    array_push($pdf->pages, $page);
    header('Content-type: application/pdf');
    echo $pdf->render();
}

?>
