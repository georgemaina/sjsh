<?php

require ('roots.php');
require ($root_path . 'include/inc_environment_global.php');

require_once 'Zend/Pdf.php';
$pdf = new Zend_Pdf ();
$page = new Zend_Pdf_Page(200, 600);

$headlineStyle = new Zend_Pdf_Style ();
$headlineStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER);
$headlineStyle->setFont($font, 8);
$page->setStyle($headlineStyle);

$pageHeight = 600;
$width = 200;
$topPos = $pageHeight - 2;
$leftPos = 1;

$pid = $_REQUEST[pid];
$batch_nr=$_REQUEST[batch_nr];
$encNo = $_REQUEST[pn];

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

$imagePath="../../icons/logo2.jpg";
$image = Zend_Pdf_Image::imageWithPath($imagePath);
$page->drawImage($image, $leftPos+20, $topPos-80, $leftPos+140, $topPos);

$topPos=$topPos-80;


$page->drawText("$company", $leftPos + 10, $topPos - 20);
$page->drawText("$address , $town", $leftPos + 10, $topPos - 30);
$page->drawText("Tel:$tel", $leftPos + 10, $topPos - 40);
$page->drawText("________________________________________", $leftPos + 1, $topPos - 50);

$titleStyle = new Zend_Pdf_Style ();
$titleStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
$font2 = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER_BOLD);
$titleStyle->setFont($font2, 9);
$page->setStyle($titleStyle);
$page->drawText("PATIENTS LAB REQUEST", $leftPos + 30, $topPos - 65);

$page->drawText("________________________________________", $leftPos + 1, $topPos - 70);
$sql2 = "SELECT p.pid, p.name_first , p.name_2, p.name_last, p.date_birth, p.addr_zip, p.cellphone_1_nr, p.citizenship
         FROM care_person  p LEFT JOIN care_encounter e on p.pid=e.pid WHERE e.encounter_nr='" . $encNo . "'";

//echo $sql2;

$info_result = $db->Execute($sql2);
$presNo='';
if ($info_result) {
    $patient_data = $info_result->FetchRow();

    $ItemsStyle = new Zend_Pdf_Style ();
    $ItemsStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $font3 = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER);
    $ItemsStyle->setFont($font3, 8);
    $page->setStyle($ItemsStyle);
    
    $page->drawText('Date:', $leftPos + 10, $topPos - 90);
    $page->drawText(date('Y-m-d'), $leftPos + 40, $topPos - 90);

    $page->drawText('Encounter No:', $leftPos + 10, $topPos - 100);
    $page->drawText($encNo, $leftPos + 80, $topPos - 100);

    $page->drawText('Patient No:', $leftPos + 10, $topPos - 110);
    $page->drawText($patient_data['pid'], $leftPos + 80, $topPos - 110);

    $page->drawText('Patient Name:', $leftPos + 10, $topPos - 120);
    $page->drawText(ucfirst(strtolower($patient_data ['name_first'])) . ' ' . ucfirst(strtolower($patient_data ['name_2'])) . ' ' . ucfirst(strtolower($patient_data ['name_last'])), $leftPos + 80, $topPos - 120);

} else {
    $page->drawText('Cannot connect database', $leftPos + 400, $topPos - 160);
}

$page->drawText('Batch No:', $leftPos + 10, $topPos - 130);
$page->drawText($batch_nr, $leftPos + 80, $topPos - 130);


$page->drawText('_________________________________________', $leftPos + 1, $topPos - 142);
$page->setStyle($titleStyle);

$page->drawText('Description', $leftPos + 10, $topPos - 153);
$page->drawText('Price', $leftPos + 160, $topPos - 153);
$page->drawText('_________________________________________', $leftPos + 1, $topPos - 155);

$r_sql = "SELECT s.encounter_nr,s.item_id,p.`name`,p.`price`,s.`batch_nr`,l.`create_id`FROM `care_test_request_chemlabor_sub` s
LEFT JOIN `care_test_request_chemlabor` l ON s.`batch_nr`=l.`batch_nr`
LEFT JOIN `care_tz_laboratory_param` p ON s.`item_id`=p.`item_id`  where s.encounter_nr=$encNo and s.batch_nr=$batch_nr";

//echo $r_sql;

$result = $db->Execute($r_sql);

$page->setStyle($ItemsStyle);
$curr_point=165;
while ($row = $result->FetchRow()) {
//    $page->drawText($row['batch_nr'], $leftPos + 10, $topPos - $curr_point);
    $page->drawText($row[name], $leftPos + 10, $topPos - $curr_point);
    $page->drawText(number_format($row['price']), $leftPos + 165, $topPos - $curr_point);
    $curr_point = $curr_point + 10;
}

$topPos = $topPos - $curr_point;
$page->drawText('__________________________________________', $leftPos + 1, $topPos - 11);

//$page->drawText('__________________________________________', $leftPos + 10, $topPos - 26);
$page->drawText('Requested By.....: ', $leftPos + 10, $topPos - 30);
$page->drawText($_SESSION['sess_login_username'], $leftPos + 70, $topPos - 30);
$page->drawText('Printed By .....: ', $leftPos + 10, $topPos - 40);
$page->drawText($_SESSION['sess_login_username'], $leftPos + 70, $topPos - 40);

$page->drawText('Thank You', $leftPos + 50, $topPos - 70);
$page->drawText('__________________________________________', $leftPos + 1, $topPos - 75);




array_push($pdf->pages, $page);
header('Content-type: application/pdf');
echo $pdf->render();
?>
