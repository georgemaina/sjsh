<?php
require ('roots.php');
require ($root_path . 'include/inc_environment_global.php');
require($root_path . 'include/myFunctions.php');

require_once 'Zend/Pdf.php';
$pdf = new Zend_Pdf ();
$page = new Zend_Pdf_Page(200, 600);
$headlineStyle = new Zend_Pdf_Style ();
$headlineStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
$headlineStyle->setFont($font, 8);
$page->setStyle($headlineStyle);
            
$pageHeight = 600;
$width = 200;
$topPos = $pageHeight - 2;
$leftPos = 10;

$slipNo=$_REQUEST['slipNo'];
$pid=$_REQUEST['pid'];
$reprint=$_REQUEST['reprint'];


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
            $email=$data_result['email'];
        }
        $global_config_ok = 1;
    } else {
        $global_config_ok = 0;
    }

$imagePath="../../../icons/logo2.jpg";
$image = Zend_Pdf_Image::imageWithPath($imagePath);
$page->drawImage($image, $leftPos+50, $topPos-80, $leftPos+125, $topPos);

$topPos=$topPos-80;

$sql2="SELECT DISTINCT b.pid,CONCAT(b.name_first,' ',name_last,' ',name_2) AS PatientNames,c.`name` as company,s.`Slip_no`,s.slip_date,s.served_by,m.memberID, b.date_birth
        FROM care_person b LEFT JOIN care_tz_company c ON b.`insurance_ID`=c.`id` LEFT JOIN care_ke_slips s ON b.`pid`=s.`pid` LEFT JOIN care_ke_debtormembers m on b.pid=m.PID
    WHERE b.pid='$pid'";
//echo $sql;

$result=$db->Execute($sql2);
$row=$result->FetchRow();


$inputUser=$row['served_by'];
$memberNo=$row['memberID'];

$headlineStyle4 = new Zend_Pdf_Style ();
$headlineStyle4->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD);
$headlineStyle4->setFont($font, 11);
$page->setStyle($headlineStyle4);

$normalStyle4 = new Zend_Pdf_Style ();
$normalStyle4->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES);
$normalStyle4->setFont($font, 10);

$dupeStyle = new Zend_Pdf_Style ();
$dupeStyle->setFillColor(new Zend_Pdf_Color_RGB(3, 0, 0));
$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD_ITALIC);
$dupeStyle->setFont($font, 10);


//$page->drawText("CREDIT SLIP", $leftPos + 10, $topPos - 10);
$page->drawText($company, $leftPos + 10, $topPos - 20);
$page->setStyle($normalStyle4);
$page->drawText($address .'-'.$postal.'-'.$town, $leftPos + 10, $topPos - 30); 
$page->drawText("Tel:".$tel, $leftPos + 10, $topPos - 40);
$page->drawText("Email:".$email, $leftPos + 10, $topPos - 50);
$page->drawText("________________________________________", $leftPos + 10, $topPos - 54);

$page->setStyle($dupeStyle);
if($reprint==1){
    $page->drawText("DUPLICATE", $leftPos + 40, $topPos - 65);
}

$page->setStyle($headlineStyle4);

$page->drawText('Credit  Slip No:', $leftPos + 10, $topPos - 80);
$page->drawText('Date', $leftPos + 10, $topPos - 90);
$page->drawText('Patient No:', $leftPos + 10, $topPos - 100);
$page->drawText('Patient Name:', $leftPos + 10, $topPos - 110);
$page->drawText('Date of Birth:', $leftPos + 10, $topPos - 120);

$page->setStyle($normalStyle4);
$page->drawText($row['Slip_no'], $leftPos + 90, $topPos - 80);
$page->drawText($row['slip_date'], $leftPos + 90, $topPos - 90);
$page->drawText($pid, $leftPos + 90, $topPos - 100);
//$page->drawText($row['PatientNames'], $leftPos + 80, $topPos - 110);
$msg= explode("\n", getWrappedText($row['PatientNames'], $headlineStyle, 80));  // COLUMN B
foreach ($msg as $line2) {
    $page->drawText($line2, $leftPos + 90, $topPos - 110);
    $topPos-=15;
}
//$age=getAge($row['date_birth']);
$page->drawText($row['date_birth'], $leftPos + 90, $topPos - 90);



$page->setStyle($headlineStyle4);

 $page->drawText('To all departments:', $leftPos + 10, $topPos - 110);

$page->setStyle($normalStyle4);

 $message="Please accord medical services to ".strtoupper($row['PatientNames']) ." Reg No: ".$pid." whose bill is payable by ".$row['company'];

$msg= explode("\n", getWrappedText($message, $headlineStyle, 150));  // COLUMN B
foreach ($msg as $line2) {
    $page->drawText($line2, $leftPos + 10, $topPos - 130);
    $topPos-=15;
}

$page->setStyle($headlineStyle4);

$page->drawText('MEMBER NO: '.$memberNo, $leftPos + 10, $topPos - 125);

$page->drawText('for ADMINISTRATOR', $leftPos + 10, $topPos - 150);

$page->setStyle($normalStyle4);

$page->drawText('Date Printed:', $leftPos + 10, $topPos - 170);
$page->drawText(date('d-m-Y H:i:s'), $leftPos + 80, $topPos - 170);

$page->drawText('Printed By:', $leftPos + 10, $topPos - 190);
$page->drawText($inputUser, $leftPos + 80, $topPos - 190);
    
    
    array_push($pdf->pages, $page);
    header('Content-type: application/pdf');
    echo $pdf->render();


?>
