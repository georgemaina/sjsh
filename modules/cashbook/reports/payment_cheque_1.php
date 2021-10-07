<?php
require ('roots.php');
require ($root_path . 'include/inc_environment_global.php');
$pid = $_REQUEST ['pid'];
$cashpoint = $_REQUEST ['cashpoint'];
$voucherNo = $_REQUEST ['voucherNo'];
$payMode = $_REQUEST ['payMode'];
$shiftno = $_REQUEST ['shiftno'];

createcheque ( $db, $cashpoint,$voucherNo,$payMode );


//require_once 'Zend/Pdf.php';

class My_Pdf extends Zend_Pdf{
    
    /**
     * The default encoding
     * 
     * @var string
     */
    public static $encoding = 'UTF-8';

    /**
     * Align text at left of provided coordinates
     * 
     * @var string
     */
    const TEXT_ALIGN_LEFT = 'left';
    
    /**
     * Align text at right of provided coordinates
     * 
     * @var string
     */
    const TEXT_ALIGN_RIGHT = 'right';
    
    /**
     * Center-text horizontally within provided coordinates
     * 
     * @var string
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
     * @return Zend_Pdf_Page
     */
    public static function drawText(Zend_Pdf_Page $page, $text, $x1, $y1, $x2 = null, $position = self::TEXT_ALIGN_LEFT, $encoding = null){
        if( $encoding == null ) $encoding = self::$encoding;
        
        $bottom = $y1; // could do the same for vertical-centering
        switch ($position) {
            case self::TEXT_ALIGN_LEFT :
                $left = $x1;
                break;
            case self::TEXT_ALIGN_RIGHT :
            if (null === $x2) {
                    throw new Exception ( "Cannot right-align text horizontally, x2 is not provided" );
                }
                $textWidth = self::getTextWidth ( $text, $page );
                $left = $x2 - $textWidth;
                break;
            case self::TEXT_ALIGN_CENTER :
                if (null === $x2) {
                    throw new Exception ( "Cannot center text horizontally, x2 is not provided" );
                }
                $textWidth = self::getTextWidth ( $text, $page );
                $left = $x1 + $textWidth / 2;
                break;
            default :
                throw new Exception ( "Invalid position value \"$position\"" );
        }
        
        // display multi-line text
        $page->drawText ( $text, $left, $y1, $encoding );
        return $page;
    }
    
    /**
     * Draw text inside a box using word wrap
     * 
     * @param Zend_Pdf_Page $page
     * @param string $text
     * @param int $x1
     * @param int $y1
     * @param int $x2
     * @param int $position
     * @param float $lineHeight
     * @param string $encoding
     * 
     * @return integer bottomPosition
     */
    public static function drawTextBox(Zend_Pdf_Page $page, $text, $x1, $y1, $x2 = null, $position = self::TEXT_ALIGN_LEFT, $lineHeight = 1.1, $encoding = null){
        if( $encoding == null ) $encoding = self::$encoding;
        
        $lines = explode(PHP_EOL, $text);
        
        $bottom = $y1;
        $lineHeight = $page->getFontSize() * $lineHeight;
        foreach( $lines as $line ){
            preg_match_all('/([^\s]*\s*)/i', $line, $matches);
            
            $words = $matches[1];
            
            $lineText = '';
            $lineWidth = 0;
            foreach( $words as $word ){
                $wordWidth = self::getTextWidth($word, $page);
                
                if( $lineWidth+$wordWidth < $x2-$x1 ){
                    $lineText .= $word;
                    $lineWidth += $wordWidth;
                }else{
                    self::drawText( $page, $lineText, $x1, $bottom, $x2, $position, $encoding );
                    $bottom -= $lineHeight;
                    $lineText = $word;
                    $lineWidth = $wordWidth;
                }
            }
            
            self::drawText( $page, $lineText, $x1, $bottom, $x2, $position, $encoding );
            $bottom -= $lineHeight;
        }
        
        return $bottom;
    }
    
    /**
     * Create pages from a text using wrapping
     * 
     * @param Zend_Pdf_Page $template    The template where all new pages are created on
     * @param string $text                The text
     * @param array $margins              array(top, right, bottom, left) Margins from the borders of the document
     * @param align $position            self::TEXT_ALIGN_LEFT
     * @param lineheight $lineHeight    The lineheight, by default 1.1 = 110% of text-height
     * @param string $encoding            If null the self::$encoding is used
     * @return array $pages                Array of created pages
     */
    public static function createPages( Zend_Pdf_Page $template, $text, $margins=array(40,28,40,28), $position = self::TEXT_ALIGN_LEFT, $lineHeight = 1.1, $encoding = null ){
        if( $encoding == null ) $encoding = self::$encoding;
        
        $pages = array();
        $currentPage = null;
        
        $lines = explode("\n", $text);
        
        $lineHeight = $template->getFontSize() * $lineHeight;
        $x1 = $margins[1];
        $x2 = $template->getWidth() - $margins[1];
        $y1 = $template->getHeight() - $margins[0] - $lineHeight;
        $y2 = $margins[2];
        
        $bottom = $y1;
        foreach( $lines as $line ){
            
            if( $currentPage == null || $bottom <= $y2 ){
                $pages[] = $currentPage = new Zend_Pdf_Page( $template );
                $currentPage->setFont( $template->getFont(), $template->getFontSize() );
                $bottom = $y1;
            }
            
            preg_match_all('/([^\s+\-,.\\/]*[\s+\-,.\\/]*)/i', $line, $matches);
            
            $words = $matches[1];
            
            $lineText = '';
            $lineWidth = 0;
            foreach( $words as $word ){
                $wordWidth = self::getTextWidth($word, $currentPage);
                
                if( $lineWidth+$wordWidth < $x2-$x1 ){
                    $lineText .= $word;
                    $lineWidth += $wordWidth;
                }else{
                    self::drawText( $currentPage, $lineText, $x1, $bottom, $x2, $position, $encoding );
                    $bottom -= $lineHeight;
                    $lineText = $word;
                    $lineWidth = $wordWidth;
                }
            }
            
            self::drawText( $currentPage, $lineText, $x1, $bottom, $x2, $position, $encoding );
            $bottom -= $lineHeight;
        }
        
        return $pages;
    }
    
    /**
     * Return length of generated string in points
     *
     * @param string                     $text
     * @param Zend_Pdf_Resource_Font|Zend_Pdf_Page     $font
     * @param int                         $fontSize
     * @return double
     */
    public static function getTextWidth($text, $resource, $fontSize = null, $encoding = null) {
        if( $encoding == null ) $encoding = self::$encoding;
        
        if( $resource instanceof Zend_Pdf_Page ){
            $font = $resource->getFont();
            $fontSize = $resource->getFontSize();
        }elseif( $resource instanceof Zend_Pdf_Resource_Font ){
            $font = $resource;
            if( $fontSize === null ) throw new Exception('The fontsize is unknown');
        }
        
        if( !$font instanceof Zend_Pdf_Resource_Font ){
            throw new Exception('Invalid resource passed');
        }
        
        $drawingText = iconv ( '', $encoding, $text );
        $characters = array ();
        for($i = 0; $i < strlen ( $drawingText ); $i ++) {
            $characters [] = ord ( $drawingText [$i] );
        }
        $glyphs = $font->glyphNumbersForCharacters ( $characters );
        $widths = $font->widthsForGlyphs ( $glyphs );
        $textWidth = (array_sum ( $widths ) / $font->getUnitsPerEm ()) * $fontSize;
        return $textWidth;
    }
    
}

function convert_number($number) {
        if (($number < 0) || ($number > 999999999)) {
            throw new Exception("Number is out of range");
        }

        $Gn = floor($number / 1000000);  /* Millions (giga) */
        $number -= $Gn * 1000000;
        $kn = floor($number / 1000);     /* Thousands (kilo) */
        $number -= $kn * 1000;
        $Hn = floor($number / 100);      /* Hundreds (hecto) */
        $number -= $Hn * 100;
        $Dn = floor($number / 10);       /* Tens (deca) */
        $n = $number % 10;               /* Ones */

        $res = "";

        if ($Gn) {
            $res .= convert_number($Gn) . " Million";
        }

        if ($kn) {
            $res .= (empty($res) ? "" : " ") .
                    convert_number($kn) . " Thousand";
        }

        if ($Hn) {
            $res .= (empty($res) ? "" : " ") .
                    convert_number($Hn) . " Hundred";
        }

        $ones = array("", "One", "Two", "Three", "Four", "Five", "Six",
            "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
            "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen",
            "Nineteen");
        $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty",
            "Seventy", "Eigthy", "Ninety");

        if ($Dn || $n) {
            if (!empty($res)) {
                $res .= " and ";
            }

            if ($Dn < 2) {
                $res .= $ones[$Dn * 10 + $n];
            } else {
                $res .= $tens[$Dn];

                if ($n) {
                    $res .= "-" . $ones[$n];
                }
            }
        }

        if (empty($res)) {
            $res = "zero";
        }

        return $res;
    }

function getWrappedText($string, Zend_Pdf_Style $style,$max_width)
{
    $wrappedText = '' ;
    $lines = explode("\n",$string) ;
    foreach($lines as $line) {
         $words = explode(' ',$line) ;
         $word_count = count($words) ;
         $i = 0 ;
         $wrappedLine = '' ;
         while($i < $word_count)
         {
             /* if adding a new word isn't wider than $max_width,
             we add the word */
             if($this->widthForStringUsingFontSize($wrappedLine.' '.$words[$i]
                 ,$style->getFont()
                 , $style->getFontSize()) < $max_width) {
                 if(!empty($wrappedLine)) {
                     $wrappedLine .= ' ' ;
                 }
                 $wrappedLine .= $words[$i] ;
             } else {
                 $wrappedText .= $wrappedLine."\n" ;
                 $wrappedLine = $words[$i] ;
             }
             $i++ ;
         }
         $wrappedText .= $wrappedLine."\n" ;
     }
     return $wrappedText ;
}    


/**
 * found here, not sure of the author :
 * http://devzone.zend.com/article/2525-Zend_Pdf-tutorial#comments-2535
 */
 function widthForStringUsingFontSize($string, $font, $fontSize)
 {
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

function createcheque($db, $cashpoint,$voucherNo,$payMode) {
	require ('roots.php');
	require_once 'Zend/Pdf.php';
	$pdf = new Zend_Pdf ();
	$page = new Zend_Pdf_Page ( Zend_Pdf_Page::SIZE_A4 );
        
        $My_Pdf=new My_Pdf();

	$pageHeight = $page->getHeight ();
	$width = $page->getWidth ();
	$topPos = $pageHeight - 10;
	$leftPos = 5;
        
	
        $r_sql = "select payee,total from care_ke_payments where 
      cash_point='$cashpoint' AND pay_mode='$payMode' AND voucher_no='$voucherNo'";
	$result = $db->Execute ( $r_sql );
//        echo $r_sql;
        $row=$result->FetchRow();
        
        $headlineStyle = new Zend_Pdf_Style ();
	$headlineStyle->setFillColor ( new Zend_Pdf_Color_RGB ( 0, 0, 0 ) );
	$font = Zend_Pdf_Font::fontWithName ( Zend_Pdf_Font::FONT_HELVETICA );
	$headlineStyle->setFont ( $font, 10 );
	$page->setStyle ( $headlineStyle );
        $page->drawText ( date('d-m-Y'), $leftPos + 450, $topPos - 20 );
        $page->drawText ( $row[total], $leftPos + 400, $topPos - 80 );
	$page->drawText ( $row[payee], $leftPos + 50, $topPos - 110 );
	
        $words=convert_number($row['total']);

//        $Data=$Data. "".strtoupper(substr($words, 42)) ."\n";
   
	$page->drawText (strtoupper($words), $leftPos + 50, $topPos - 130);
	
	
	$topPos = $topPos - 10;
	array_push ( $pdf->pages, $page );
	header ( 'Content-type: application/pdf' );
	echo $pdf->render ();
}

?>
