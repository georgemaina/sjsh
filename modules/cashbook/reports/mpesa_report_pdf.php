<?php
require ('roots.php');
require ($root_path . 'include/inc_environment_global.php');
$pid = $_REQUEST ['pid'];
$cashpoint = $_REQUEST ['cashpoint'];
$shiftno = $_REQUEST ['shiftno'];
createInvoiceTitle ( $db, $cashpoint, $shiftno );

function createInvoiceTitle($db, $cashpoint, $shiftno) {
	require ('roots.php');
	require_once 'Zend/Pdf.php';
	$pdf = new Zend_Pdf ();
	$page = new Zend_Pdf_Page ( Zend_Pdf_Page::SIZE_A4 );


	$pageHeight = $page->getHeight ();
	$width = $page->getWidth ();
	$topPos = $pageHeight - 10;
	$leftPos = 5;
	$config_type = 'main_info_%';
	$sql = "SELECT * FROM care_ke_invoice";
	$global_result = $db->Execute ( $sql );
	if ($global_result) {
		while ( $data_result = $global_result->FetchRow () ) {
			$company = $data_result ['CompanyName'];
			$address = $data_result ['Address'];
			$town = $data_result ['Town'];
			$postal = $data_result ['Postal'];
			$tel = 'Phone: '.$data_result ['Tel'];
			$invoice_no = $data_result ['new_bill_nr'];
		}
		$global_config_ok = 1;
	} else {
		$global_config_ok = 0;
	}
	 $pmode=$_REQUEST[pmode];
        
	$title = $pmode.' SHIFT SUMMARY REPORT';
	
	$headlineStyle = new Zend_Pdf_Style ();
	$headlineStyle->setFillColor ( new Zend_Pdf_Color_RGB ( 0, 0, 0 ) );
	$font = Zend_Pdf_Font::fontWithName ( Zend_Pdf_Font::FONT_HELVETICA );
	$headlineStyle->setFont ( $font, 10 );
	$page->setStyle ( $headlineStyle );
	$page->drawText ( $company, $leftPos + 36, $topPos - 36 );
	$page->drawText ( $address, $leftPos + 36, $topPos - 50 );
	$page->drawText ( $town . ' - ' . $postal, $leftPos + 36, $topPos - 65 );
	$page->drawText ( $tel, $leftPos + 36, $topPos - 80 );
	
	$font = Zend_Pdf_Font::fontWithName ( Zend_Pdf_Font::FONT_HELVETICA_BOLD );
	$headlineStyle2 = new Zend_Pdf_Style ();
	$headlineStyle2->setFont ( $font, 13 );
	$page->setStyle ( $headlineStyle2 );
	$page->drawText ( $title, $leftPos + 200, $topPos - 22 );
	$page->setFont ( Zend_Pdf_Font::fontWithName ( Zend_Pdf_Font::FONT_HELVETICA_BOLD ), 9 );
	

   
	
	$page->drawRectangle ( $leftPos + 32, $topPos - 90, $leftPos + 700, $topPos - 90, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE );
	
	//$page->drawRectangle ( $leftPos + 36, $topPos - 170, $leftPos + 500, $topPos - 170, Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE );
	//draw row headings
	$rectStyle = new Zend_Pdf_Style ();
	$rectStyle->setLineDashingPattern ( array (2 ), 1.6 );
	$rectStyle->setLineColor ( new Zend_Pdf_Color_GrayScale ( 0.8 ) );
	$font = Zend_Pdf_Font::fontWithName ( Zend_Pdf_Font::FONT_HELVETICA_BOLD );
	$rectStyle->setFont ( $font, 8);
	$page->setStyle ( $rectStyle );
	$page->drawRectangle ( $leftPos + 32, $topPos - 95, $leftPos + 700, $topPos - 115, Zend_Pdf_Page::SHAPE_DRAW_STROKE );
	$page->drawRectangle ( $leftPos + 32, $topPos - 95, $leftPos + 700, $topPos - 810, Zend_Pdf_Page::SHAPE_DRAW_STROKE );
	$page->drawText ( 'Point:', $leftPos + 36, $topPos - 110 );
	$page->drawText ( 'shiftNo', $leftPos + 70, $topPos - 110 );
        $page->drawText ( 'ref_no', $leftPos + 110, $topPos - 110 );
	$page->drawText ( 'currdate', $leftPos + 150, $topPos - 110 );
	$page->drawText ( 'PID', $leftPos + 205, $topPos - 110 );
	$page->drawText ( 'Names', $leftPos + 240, $topPos - 110 );
	$page->drawText ( 'Phone', $leftPos + 340, $topPos - 110 );
	$page->drawText ( 'Trans Code', $leftPos + 390, $topPos - 110 );
	$page->drawText ( 'total', $leftPos + 460, $topPos - 110 );
	$page->drawText ( 'Running Total', $leftPos + 495, $topPos - 110 );
	$currpoint = 125;
        
	$sql = "SELECT cash_point,shift_no,pay_mode,ref_no,currdate,patient,payer,mpesaTel,mpesaTransno,proc_code,prec_desc,total 
        FROM care_ke_receipts WHERE pay_Mode='$pmode' ";

        if(isset($startDate) && isset($endDate)){
             $date1=new DateTime($_REQUEST[startDate]);
             $sdate1=$date1->format('Y-m-d');
             
             $date2=new DateTime($_REQUEST[endDate]);
             $sdate2=$date2->format('Y-m-d');
  
          $sql =$sql ." and currdate between '$sdate1' and '$sdate2'";
        }

	$result = $db->Execute ( $sql );
	//$row = $result->FetchRow ();
	
	$resultsStyle = new Zend_Pdf_Style ();
	$font = Zend_Pdf_Font::fontWithName ( Zend_Pdf_Font::FONT_HELVETICA );
	$resultsStyle->setFont ( $font, 9 );
	$page->setStyle ( $resultsStyle );
        
	$rtotal=0;
	while ( $row = $result->FetchRow () ) {
		if ($topPos < 140) {
			array_push ( $pdf->pages, $page );
			$page = new Zend_Pdf_Page ( Zend_Pdf_Page::SIZE_A4);
			$resultsStyle = new Zend_Pdf_Style ();
			$resultsStyle->setLineDashingPattern ( array (2 ), 1.6 );
			$font = Zend_Pdf_Font::fontWithName ( Zend_Pdf_Font::FONT_HELVETICA );
			$resultsStyle->setFillColor ( new Zend_Pdf_Color_RGB ( 0, 0, 0 ) );
			$resultsStyle->setFont ( $font, 9 );
			$page->setStyle ( $resultsStyle );
			$pageHeight = $page->getHeight ();
			$topPos = $pageHeight - 20;
			$currpoint = 20;
			$page->drawRectangle ( $leftPos + 32, $topPos - 5, $leftPos + 700, $topPos - 810, Zend_Pdf_Page::SHAPE_DRAW_STROKE );
		}
                //cash_point,shift_no,pay_mode,ref_no,currdate,patient,payer,mpesaTel,mpesaTransno,proc_code,prec_desc,total
		$page->drawText ( $row ['cash_point'], $leftPos + 36, $topPos - $currpoint );
		$page->drawText ( $row ['shift_no'], $leftPos + 80, $topPos - $currpoint );
                $date = new DateTime($row ['currdate']);
		$page->drawText ( $row ['ref_no'], $leftPos + 110, $topPos - $currpoint );
		$page->drawText ( $date->format('d-m-Y'), $leftPos + 150, $topPos - $currpoint );
		$page->drawText ( $row['patient'], $leftPos + 205, $topPos - $currpoint );
		$page->drawText ( $row['payer'], $leftPos + 230, $topPos - $currpoint );
		$page->drawText ( $row['mpesaTel'], $leftPos + 340, $topPos - $currpoint );
		$page->drawText ( $row['mpesaTransno'], $leftPos + 390, $topPos - $currpoint );
		$page->drawText ( $row['total'], $leftPos + 460, $topPos - $currpoint );
                $rtotal=$rtotal+$row[total];
		$page->drawText ($rtotal, $leftPos + 500, $topPos - $currpoint );
		$topPos = $topPos - 20;
               
	
	}
             $topPos = $topPos - $currpoint;

        $page->drawRectangle ( $leftPos + 32, $topPos - 5, $leftPos + 700, $topPos - 5, Zend_Pdf_Page::SHAPE_DRAW_STROKE );
        
        $totalStyle = new Zend_Pdf_Style ();
        $totalStyle->setLineDashingPattern ( array (2 ), 1.6 );
        $font = Zend_Pdf_Font::fontWithName ( Zend_Pdf_Font::FONT_HELVETICA_BOLD );
        $totalStyle->setFillColor ( new Zend_Pdf_Color_RGB ( 0, 0, 0 ) );
        $totalStyle->setFont ( $font, 11 );
        $page->setStyle ( $totalStyle );
	$page->drawText ( 'TOTAL:', $leftPos + 440, $topPos - 15 );
        $page->drawText ( number_format($rtotal,2), $leftPos + 500, $topPos - 15 );
	$topPos = $topPos - 10;
	array_push ( $pdf->pages, $page );
	header ( 'Content-type: application/pdf' );
	echo $pdf->render ();
}

?>
