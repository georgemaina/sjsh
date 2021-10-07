<?php

require ('roots.php');
require ($root_path . 'include/inc_environment_global.php');

require_once 'Zend/Pdf.php';
$pdf = new Zend_Pdf ();


class Library_Pdf_Base extends Zend_Pdf {
    /**
     * Align text at left of provided coordinates
     */

    const TEXT_ALIGN_LEFT = 'left';

    /**
     * Align text at right of provided coordinates
     */
    const TEXT_ALIGN_RIGHT = 'right';

    /**
     * Center-text horizontally within provided coordinates
     */
    const TEXT_ALIGN_CENTER = 'center';

    /**
     * Extension of basic draw-text function to allow it to vertically center text
     *
     * @param Zend_Pdf_Page $page
     * @param string $text
     * @param int $x1
     * @param int $y1
     * @param int $x2
     * @param int $position
     * @param string $encoding
     * @return self
     */
    public function drawText(Zend_Pdf_Page $page, $text, $x1, $y1, $x2 = null, $position = self::TEXT_ALIGN_LEFT, $encoding = null) {
        $bottom = $y1; // could do the same for vertical-centering

        switch ($position) {
            case self::TEXT_ALIGN_LEFT:
                $left = $x1;
                break;
            case self::TEXT_ALIGN_RIGHT:
                $text_width = $this->getTextWidth($text, $page->getFont(), $page->getFontSize());
                $left = $x1 - $text_width;
                break;
            case self::TEXT_ALIGN_CENTER:
                if (null === $x2) {
                    throw new Exception("Cannot center text horizontally, x2 is not provided");
                }
                $text_width = $this->getTextWidth($text, $page->getFont(), $page->getFontSize());
                $box_width = $x2 - $x1;
                $left = $x1 + ($box_width - $text_width) / 2;
                break;
            default:
                throw new Exception("Invalid position value \"$position\"");
        }

        // display multi-line text
        foreach (explode(PHP_EOL, $text) as $i => $line) {
            $page->drawText($line, $left, $bottom - $i * $page->getFontSize(), $encoding);
        }
        return $this;
    }

    /**
     * Return length of generated string in points
     *
     * @param string $string
     * @param Zend_Pdf_Resource_Font $font
     * @param int $font_size
     * @return double
     */
    public function getTextWidth($text, Zend_Pdf_Resource_Font $font, $font_size) {
        $drawing_text = iconv('', 'UTF-16BE', $text);
        $characters = array();
        for ($i = 0; $i < strlen($drawing_text); $i++) {
            $characters[] = (ord($drawing_text[$i++]) << 8) | ord($drawing_text[$i]);
        }
        $glyphs = $font->glyphNumbersForCharacters($characters);
        $widths = $font->widthsForGlyphs($glyphs);
        $text_width = (array_sum($widths) / $font->getUnitsPerEm()) * $font_size;
        return $text_width;
    }

}

$pdfBase=new Library_Pdf_Base();

$page = new Zend_Pdf_Page(200, 600);;
$headlineStyle = new Zend_Pdf_Style ();
$headlineStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
$headlineStyle->setFont($font, 8);
$page->setStyle($headlineStyle);


$titleStyle = new Zend_Pdf_Style ();
$titleStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
$titleFont = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD_ITALIC);
$titleStyle->setFont($titleFont, 10);
            
$pageHeight = 600;
$width = 200;
$topPos = $pageHeight - 2;
$leftPos = 10;
    
    
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

$imagePath="../../../icons/logo2.jpg";
$image = Zend_Pdf_Image::imageWithPath($imagePath);
$page->drawImage($image, $leftPos+20, $topPos-80, $leftPos+140, $topPos);

$topPos=$topPos-80;

//http://localhost/litein/modules/cashbook/reports/receiptPDF2.php?rdate=Friday%206th%20October%202017&refno=Q72&
//cashier=Admin&pno=1559&PatientName=MARY%20KARIMI%20GITARI&PaymentMode=&cashpoint=RM34B&shiftno=4
//&payer=MARY%20KIRIMI%20GITARI&reprint%20=&towards=BILLS%20PAYMENT&inputTime=

$page->drawText("$company", $leftPos + 40, $topPos - 20);   
$page->drawText("$address $town ,$postal", $leftPos + 30, $topPos - 30); 
$page->drawText("Tel:$tel", $leftPos + 40, $topPos - 40); 
$page->drawText("________________________________________", $leftPos + 10, $topPos - 50); 

$page->setStyle($titleStyle);
$reprint=$_REQUEST['reprint'];

if($reprint=='REPRINT'){
    $page->drawText("NOTE: THIS IS A RECEIPT COPY", $leftPos + 20, $topPos - 62);
    $page->drawText("________________________________________", $leftPos + 10, $topPos - 70);
    $topPos=$topPos-30;
}

$page->setStyle($headlineStyle);
        
$page->drawText('Date:', $leftPos + 10, $topPos - 60); 
$page->drawText($_REQUEST['rdate'], $leftPos + 40, $topPos - 60); 

$page->drawText('Receipt No:', $leftPos + 10, $topPos - 80); 
$page->drawText($_REQUEST['refno'], $leftPos + 80, $topPos - 80); 

$page->drawText('PID:', $leftPos + 10, $topPos - 90); 
$page->drawText($_REQUEST['pno'], $leftPos + 80, $topPos - 90);

$page->drawText('Customer:', $leftPos + 10, $topPos - 100); 
$page->drawText($_REQUEST['PatientName'], $leftPos + 80, $topPos - 100);

$page->drawText('Towards:', $leftPos + 10, $topPos - 110); 
$page->drawText($_REQUEST['towards'], $leftPos + 80, $topPos - 110);

$page->drawText('Payment Mode:', $leftPos + 10, $topPos - 120); 
$page->drawText($_REQUEST['PaymentMode'], $leftPos + 80, $topPos - 120);
$shiftNo=$_REQUEST['shiftno'];

$page->drawText('_________________________________________', $leftPos + 10, $topPos - 131);
$page->drawText('CODE', $leftPos + 10, $topPos - 142);
$page->drawText('AMOUNT', $leftPos + 140, $topPos - 142);
$page->drawText('_________________________________________', $leftPos + 10, $topPos - 145);

$cashpoint=$_REQUEST['cashpoint'];
$refno=$_REQUEST['refno'];
$pno=$_REQUEST['pno'];

$r_sql = "select rev_desc, proc_qty, `Prec_desc`, total, amount, rev_code, proc_code,towards
from care_ke_receipts where ref_no='$refno' and cash_point='$_REQUEST[cashpoint]' AND patient='$pno' and shift_no='$shiftNo'";

    $result = $db->Execute($r_sql);
//        echo $r_sql;
    $curr_point=160;
    while ( $row = $result->FetchRow()) {
        $page->drawText(substr($row['towards'],0,32), $leftPos + 10, $topPos - $curr_point);
        
        $pdfBase->drawText($page, number_format($row['total'],2), $leftPos + 160, $topPos - $curr_point,$leftPos + 160,right);
//        $page->drawText(number_format(intval($row['amount']),2), $leftPos + 140, $topPos - $curr_point);
        $curr_point=$curr_point+10;
    }
    $topPos = $topPos - $curr_point;
 $page->drawText('__________________________________________', $leftPos + 10, $topPos - 11);
    
$sql = "SELECT SUM(total) AS total FROM care_ke_receipts
     WHERE ref_no='$refno' AND cash_point='$cashpoint' AND patient='$pno' and shift_no='$shiftNo'";
       $result = $db->Execute($sql);
//         echo $sql;
       $row = $result->FetchRow();
             $page->drawText("Total", $leftPos + 10, $topPos - 20);
             $page->drawText(number_format($row['total'],2), $leftPos + 140, $topPos - 20);
        
 $page->drawText('__________________________________________', $leftPos + 10, $topPos - 26);
 $page->drawText('Paid By.....:', $leftPos + 10, $topPos - 40);
 $page->drawText($_REQUEST['payer'], $leftPos + 60, $topPos - 40);
 $page->drawText('Cashier.....:', $leftPos + 10, $topPos - 50);
 $page->drawText($_REQUEST['cashier'], $leftPos + 60, $topPos - 50);
 $page->drawText('Cash Point..:', $leftPos + 10, $topPos - 60);
 $page->drawText($_REQUEST['cashpoint'], $leftPos + 60, $topPos - 60);
 $page->drawText('Thank', $leftPos + 50, $topPos - 80);
 $page->drawText('__________________________________________', $leftPos + 10, $topPos - 100);

    
    
    
    array_push($pdf->pages, $page);
    header('Content-type: application/pdf');
    echo $pdf->render();


?>
