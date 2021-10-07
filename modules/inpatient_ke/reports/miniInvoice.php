<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require ('roots.php');
require ($root_path . 'include/inc_environment_global.php');

require_once 'Zend/Pdf.php';
require ($root_path . 'include/care_api_classes/Library_Pdf_base.php');

$pdf = new Zend_Pdf ();
$page = new Zend_Pdf_Page(230, 800);;
$headlineStyle = new Zend_Pdf_Style ();
$headlineStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
$headlineStyle->setFont($font, 8);
$page->setStyle($headlineStyle);

$titleStyle = new Zend_Pdf_Style ();
$titleStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
$titleStyle->setFont($font, 11);

$pageHeight = 800;
$width = 200;
$topPos = $pageHeight - 2;
$leftPos = 10;

$pid = $_REQUEST['pid'];
$receipt = $_REQUEST['receipt'];
$billNumber = $_GET["billNumber"];
$nhif = $_REQUEST['nhif'];


$pdfBase = new Library_Pdf_Base();

$nhif = $_REQUEST['nhif'];
    
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


$debug=false;

$topPos=$topPos-10;

$r_sql = "SELECT DISTINCT
    care_ke_billing.pid
    ,care_ke_billing.encounter_nr
    , care_person.name_first
    , care_person.name_2
    , care_person.name_last
    , care_person.date_birth
    , care_person.addr_zip
    , care_person.cellphone_1_nr
    , care_person.citizenship
    , care_ke_billing.`IP-OP`
    ,care_ke_billing.bill_number
FROM
    care_ke_billing
    INNER JOIN care_person
        ON (care_ke_billing.pid = care_person.pid)
WHERE (care_ke_billing.`IP-OP`='1' and care_ke_billing.pid='" . $pid . "' and bill_number='$billNumber')";

if($debug) echo $r_sql;

$request=$db->Execute($r_sql);
$row=$request->FetchRow();

$page->setStyle($titleStyle);
$page->drawText("DETAIL INVOICE", $leftPos + 55, $topPos - 10);

$page->setStyle($headlineStyle);

$page->drawText($company, $leftPos + 45, $topPos - 20);
$page->drawText($address .'-'.$postal.'-'.$town, $leftPos + 30, $topPos - 30);
$page->drawText("Tel:".$tel, $leftPos + 60, $topPos - 40);
$page->drawText("________________________________________", $leftPos + 10, $topPos - 50);

$page->drawText('Date:', $leftPos + 10, $topPos - 70);
$page->drawText(date("Y-m-d H:i:s"), $leftPos + 40, $topPos - 70);

$page->drawText('Invoice No:', $leftPos + 10, $topPos - 80);
$page->drawText($row['bill_number'], $leftPos + 80, $topPos - 80);

$page->drawText('Patient No:', $leftPos + 10, $topPos - 90);
$page->drawText($row['pid'], $leftPos + 80, $topPos - 90);

$page->drawText('Patient Name:', $leftPos + 10, $topPos - 100);
$page->drawText($row['name_first'].' '.$row['name_last'].' '.$row['name_2'], $leftPos + 80, $topPos - 100);

$page->drawText('_________________________________________', $leftPos + 10, $topPos - 121);
$page->drawText('Item', $leftPos + 10, $topPos - 130);
$page->drawText('Qty', $leftPos + 130, $topPos - 130);
$page->drawText('AMOUNT', $leftPos + 150, $topPos - 130);
$page->drawText('_________________________________________', $leftPos + 10, $topPos - 135);



$sql3 = "SELECT
    prescribe_date
    , description
    , bill_number
    , sum(price) as price
    , sum(qty) as qty
    , sum(total) as total
FROM
    care_ke_billing
WHERE (pid ='" . $pid . "' AND service_type NOT IN
            ('payment','payment adjustment','NHIF') and bill_number=$billNumber and `ip-op`=1)
            group by description";

    $result = $db->Execute($sql3);


    $curr_point=150;
    while ( $row = $result->FetchRow()) {

        $page->drawText(substr($row['description'],0,30), $leftPos + 10, $topPos - $curr_point);
        $pdfBase->drawText($page, $row['qty'], $leftPos + 140, $topPos - $curr_point, $leftPos + 140, right);
        $pdfBase->drawText($page, number_format($row['total'], 2), $leftPos + 190, $topPos - $curr_point, $leftPos + 190, right);
        $curr_point=$curr_point+10;
    }
    $topPos = $topPos - $curr_point;
 $page->drawText('__________________________________________', $leftPos + 10, $topPos - 11);

$sql4 = "SELECT sum(total) as total FROM care_ke_billing WHERE pid = '$pid' AND
        service_type NOT IN ('payment','payment adjustment','NHIF') and `ip-op`=1 and bill_number=$billNumber";

$results = $db->Execute($sql4);
$row = $results->FetchRow();
$bill = $row['total'];

       $row = $result->FetchRow();
     $page->drawText("Total", $leftPos + 10, $topPos - 20);
     $page->drawText(number_format($bill,2), $leftPos + 155, $topPos - 20);

$page->drawText("PAYMENTS AND RECEIPTS", $leftPos + 10, $topPos - 40);

$totalReceipts=0;
$topPos=$topPos-50;
if ($receipt <> '') {
    $sqli = "SELECT * FROM care_ke_billing WHERE (pid ='" . $pid . "' AND service_type IN
            ('payment','payment adjustment') and `ip-op`=1 and bill_number=$billNumber)";

    $resultsi = $db->Execute($sqli);
    $curr_point=10;

    while($row=$resultsi->FetchRow()){
        if ($topPos < 150) {

            array_push($pdf->pages, $page);
            $page = new Zend_Pdf_Page(230, 600);
//            $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
            $resultsStyle = new Zend_Pdf_Style ();
            $resultsStyle->setLineDashingPattern(array(2), 1.6);
            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
            $resultsStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
            $resultsStyle->setFont($font, 9);
            $page->setStyle($resultsStyle);
            $pageHeight = $page->getHeight();

            $topPos = $pageHeight - 20;
            $currpoint = 30;

            $page->setStyle($resultsStyle);
//            $page->drawRectangle($leftPos + 32, $topPos - $currpoint, $leftPos + 500, $topPos - $currpoint, Zend_Pdf_Page::SHAPE_DRAW_STROKE);
        }


        $page->drawText("Receipt No (".$row['batch_no'].')', $leftPos + 10, $topPos - $curr_point);
        $pdfBase->drawText($page, number_format($row['total'], 2), $leftPos + 190, $topPos - $curr_point, $leftPos + 190, right);
        $curr_point=$curr_point+10;
        $totalReceipts=$totalReceipts+$row['total'];
    }

}

if ($nhif <> '' and $receipt == '') {
    $sqli = "SELECT * FROM care_ke_billing WHERE (pid ='" . $pid . "' AND service_type IN
            ('NHIF') and `ip-op`=1 and bill_number=$billNumber)";

    $resultsi = $db->Execute($sqli);

    while($row=$resultsi->FetchRow()){
        $page->drawText("Receipt No ".$row['batch_no'], $leftPos + 10, $topPos - 20);
        $pdfBase->drawText($page, number_format($row['total'], 2), $leftPos + 190, $topPos - $curr_point, $leftPos + 190, right);
        $curr_point=$curr_point+10;
        $totalReceipts=$totalReceipts+$row['total'];
    }

}
$topPos = $topPos - 80;

$page->drawText('__________________________________________', $leftPos + 10, $topPos - 1);
$page->drawText("TOTAL RECEIPTS ", $leftPos + 10, $topPos - 15);
$pdfBase->drawText($page, number_format($totalReceipts, 2), $leftPos + 190, $topPos - 15, $leftPos + 190, right);
$page->drawText('_____________', $leftPos + 140, $topPos - 20);
$page->drawText('_____________', $leftPos + 140, $topPos - 22);

$page->setStyle($titleStyle);

$page->drawText("BILL BALANCE ", $leftPos + 10, $topPos - 40);
$balance=$bill-$totalReceipts;
$pdfBase->drawText($page, number_format($balance, 2), $leftPos + 190, $topPos - 40, $leftPos + 190, right);

$page->setStyle($headlineStyle);

 $page->drawText('Thank`s and wish you a quick recovery', $leftPos + 30, $topPos - 80);
 $page->drawText('__________________________________________', $leftPos + 10, $topPos - 120);

    
    
    
    array_push($pdf->pages, $page);
    header('Content-type: application/pdf');
    echo $pdf->render();


?>
