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
$issType = $_REQUEST[issType];
$encNo = $_REQUEST[encNo];

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
$page->drawText("$company", $leftPos + 10, $topPos - 20);
$page->drawText("$address , $town", $leftPos + 10, $topPos - 30);
$page->drawText("Tel:$tel", $leftPos + 10, $topPos - 40);
$page->drawText("________________________________________", $leftPos + 1, $topPos - 50);

$titleStyle = new Zend_Pdf_Style ();
$titleStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
$font2 = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER_BOLD);
$titleStyle->setFont($font2, 9);
$page->setStyle($titleStyle);
$page->drawText("PATIENTS PRESCRIPTION", $leftPos + 30, $topPos - 65);

$page->drawText("________________________________________", $leftPos + 1, $topPos - 70);
$sql2 = "SELECT
    p.pid
    , p.name_first
    , p.name_2
    , p.name_last
    , p.date_birth
    , p.addr_zip
    , p.cellphone_1_nr
    , p.citizenship
FROM care_person  p
WHERE (p.pid='" . $pid . "')";

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

$page->drawText('_________________________________________', $leftPos + 1, $topPos - 131);
$page->setStyle($titleStyle);
$page->drawText('PartCode', $leftPos + 10, $topPos - 140);
$page->drawText('Description', $leftPos + 70, $topPos - 140);
$page->drawText('Qty', $leftPos + 165, $topPos - 140);
$page->drawText('_________________________________________', $leftPos + 1, $topPos - 145);

$r_sql = "SELECT a.nr,b.encounter_nr,b.encounter_class_nr,a.partcode,b.pid,a.article_item_number,a.article,
            a.price AS amnt,(a.dosage*times_per_day*a.days) AS qty,(a.price*(a.dosage*times_per_day*a.days)) AS total,
            l.quantity,a.prescriber,a.prescriber,A.`dosage`,A.`times_per_day`,A.`days`
            FROM care_encounter_prescription a
            INNER JOIN  care_encounter b ON a.encounter_nr=b.encounter_nr
            LEFT JOIN care_ke_locstock l ON a.partcode=l.stockid
            WHERE b.pid='$pid' AND a.drug_class='drug_list' AND b.encounter_class_nr=$issType AND a.status 
            NOT IN('serviced','done','Cancelled')";

//echo $r_sql;

$result = $db->Execute($r_sql);


$curr_point = 160;
$doctor='';
$page->setStyle($ItemsStyle);
while ($row = $result->FetchRow()) {
    $page->drawText($row['partcode'], $leftPos + 10, $topPos - $curr_point);
    $page->drawText($row['article'], $leftPos + 60, $topPos - $curr_point);
    $page->drawText($row['qty'], $leftPos + 165, $topPos - $curr_point);
    $curr_point = $curr_point + 7;
    
    $doseStyle = new Zend_Pdf_Style ();
    $doseStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
    $font5 = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER_ITALIC);
    $doseStyle->setFont($font5, 6);
    $page->setStyle($doseStyle);
    
    $page->drawText("Dose ".$row['dosage'].' , '.$row['times_per_day'].' Times per day, for '.$row['days'].' Days', $leftPos + 30, $topPos - $curr_point);
         
    $curr_point = $curr_point + 12;
    $page->setStyle($ItemsStyle);
    $doctor=$row['prescriber'];
    $presNo=$row['nr'];
}
$topPos = $topPos - $curr_point;
$page->drawText('__________________________________________', $leftPos + 1, $topPos - 11);

//$page->drawText('__________________________________________', $leftPos + 10, $topPos - 26);
$page->drawText('Prepared By.....: ', $leftPos + 10, $topPos - 30);
$page->drawText($doctor, $leftPos + 70, $topPos - 30);
$page->drawText('Printed By .....: ', $leftPos + 10, $topPos - 40);
$page->drawText($_SESSION['sess_login_username'], $leftPos + 70, $topPos - 40);

$page->drawText('Thank You', $leftPos + 50, $topPos - 70);
$page->drawText('__________________________________________', $leftPos + 1, $topPos - 75);




array_push($pdf->pages, $page);
header('Content-type: application/pdf');
echo $pdf->render();
?>
