<?php

require ('roots.php');
require ($root_path . 'include/inc_environment_global.php');
require_once($root_path.'include/care_api_classes/class_person.php');
$person_obj=new Person();

require_once 'Zend/Pdf.php';
$pdf = new Zend_Pdf ();
$page = new Zend_Pdf_Page(200, 800);;
$headlineStyle = new Zend_Pdf_Style ();
$headlineStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
$headlineStyle->setFont($font, 8);

$normalStyle = new Zend_Pdf_Style ();
$normalStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
$normalStyle->setFont($font, 8);
$page->setStyle($normalStyle);

$pageHeight = $page->getHeight();
$width = 200;
$topPos = $pageHeight - 2;
$leftPos = 5;

$debug=false;

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

    function getParamName($param){
        global $db;

        $sql="Select results from `care_tz_laboratory_resultstypes` where resultID='$param'";
        $results=$db->Execute($sql);

        $row=$results->FetchRow();

        return $row[0];
    }

$imagePath="../../icons/logo2.jpg";
$image = Zend_Pdf_Image::imageWithPath($imagePath);
$page->drawImage($image, $leftPos+20, $topPos-80, $leftPos+140, $topPos);

$topPos=$topPos-80;

$pson=$person_obj->getAllInfoArray($_REQUEST['pid']);
$names=$pson[name_first]." ".$pson[name_2]." ".$pson[name_last];

$page->drawText($company, $leftPos + 10, $topPos - 10);
$page->drawText($address .'-'.$postal.'-'.$town, $leftPos + 10, $topPos - 20);
$page->drawText("Tel:".$tel, $leftPos + 10, $topPos - 30);
//$page->drawText("________________________________________", $leftPos + 10, $topPos - 35);
$page->setStyle($headlineStyle);
$page->drawText("LABOLATORY RESULTS", $leftPos + 30, $topPos - 45);

$page->drawText('Date:'.date("Y-m-d H:i:s"), $leftPos + 10, $topPos - 60);
$page->drawText('Patient No:', $leftPos + 10, $topPos - 70);
$page->drawText($_REQUEST['pid'], $leftPos + 80, $topPos - 70);

$page->drawText('Patient Name:', $leftPos + 10, $topPos - 85);
$page->drawText($names, $leftPos + 70, $topPos - 85);
$page->drawText('_________________________________________', $leftPos + 10, $topPos - 90);


$sql="SELECT c1.`batch_nr`,c1.`encounter_nr`,c1.`parameters`,c2.`item_id`,c2.`paramater_name`,
                c1.`send_date`,c1.`status`,c1.`bill_number`,c1.`bill_status`,c1.`sample_time`,c1.`notes`,p.`field_type`,c1.create_id
                FROM care_test_request_chemlabor c1 LEFT JOIN care_test_request_chemlabor_sub c2 ON c1.`batch_nr`=c2.`batch_nr`
                LEFT JOIN `care_tz_laboratory_param` p ON c2.`item_id`=p.`item_id`
                where c1.`encounter_nr`='".$_SESSION['sess_en']."'";

if($debug)
    echo $sql;

$result=$db->Execute($sql);

$currPoint=110;
$requester="";
$dateRequested="";
while ($row = $result->FetchRow()) {
    $requester=$row[create_id];
    $dateRequested=$row[send_date];
    $page->setStyle($headlineStyle);
    $page->drawText( $row['paramater_name'], $leftPos + 10, $topPos-$currPoint );

    $page->setStyle($normalStyle);
    $topPos = $topPos - 20;
    if ($row[field_type] == 'group_field') {
        $sql = "SELECT DISTINCT p.encounter_nr,k.test_date,k.test_time,p.paramater_name,p.parameter_value,
            p.job_id,p.batch_nr FROM care_test_findings_chemlabor_sub p
                LEFT JOIN care_test_findings_chemlab k ON p.job_id=k.job_id
                JOIN care_test_request_chemlabor_sub t ON k.job_id=t.batch_nr
                WHERE p.encounter_nr='$row[encounter_nr]' AND p.paramater_name LIKE '%$row[item_id]%'
                 ORDER BY job_id ASC";
        if ($debug) echo $sql;

        $results = $db->Execute($sql);

//        $currPoint2=$currPoint-90;
        while ($row2 = $results->FetchRow()) {
            if ($topPos < 120) {
                array_push($pdf->pages, $page);
                $page = new Zend_Pdf_Page(200, 800);
                $resultsStyle = new Zend_Pdf_Style ();
                $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
                $resultsStyle->setFont($font, 9);
                $page->setStyle($resultsStyle);
                $$pageHeight = $page->getHeight();
                $width = 200;
                $topPos = $pageHeight - 2;
                $leftPos = 5;
//                $currpoint = 30;

                $page->setStyle($resultsStyle);
            }

            $params = explode('-', $row2[paramater_name]);
            $paramName=getParamName($params[2]);

            $sql = "Select s.resultID,s.item_id,p.name,s.results,s.`normal`,s.`ranges`  from care_tz_laboratory_resultstypes s left join care_tz_laboratory_param p
                    on s.item_id=p.item_id where resultID='$params[2]' and s.item_id='$params[1]' order by p.name asc";
            if ($debug) echo $sql;
            $results3 = $db->Execute($sql);
            $row3 = $results3->FetchRow();

            $page->drawText($paramName. ":", $leftPos + 10, $topPos - $currPoint);
            $page->drawText($row2[parameter_value], $leftPos + 90, $topPos - $currPoint);
            $page->drawText($row3[normal], $leftPos + 150, $topPos - $currPoint);
            $page->drawText($row3[ranges], $leftPos + 170, $topPos - $currPoint);
            $topPos = $topPos - 10;
        }
        $topPos = $topPos - $currpoint;
    } else {
        $sql = "SELECT p.encounter_nr,k.test_date,k.test_time,c.group_id,c.name,p.paramater_name,p.parameter_value,
         p.job_id,p.batch_nr,c.`item_id` FROM care_test_findings_chemlabor_sub p
        LEFT JOIN care_tz_laboratory_param c ON p.paramater_name=c.id
        LEFT JOIN care_test_findings_chemlab k ON p.job_id=k.job_id
        LEFT JOIN care_test_request_chemlabor t ON t.batch_nr=k.job_id
        WHERE p.encounter_nr='" . $_SESSION['sess_en'] . "'
        and c.`item_id`= '$row[item_id]' ORDER BY job_id asc";

        $request = $db->Execute($sql);
        $rowCount = $request->RecordCount();

        $topPos = $topPos - $currpoint;
        if ($rowCount > 0) {
            $row2 = $request->FetchRow();
            $page->drawText($row2[name] . ":", $leftPos + 10, $topPos - $currPoint+5);
            $page->drawText($row2[parameter_value], $leftPos + 80, $topPos - $currPoint+5);
            $currPoint=$currPoint+10;
        }
        $topPos = $topPos - $currpoint-10;
    }

}
$topPos=$topPos-150;
        
 $page->drawText('__________________________________________', $leftPos + 10, $topPos - 15);
$page->drawText('Requested On.:'.$dateRequested, $leftPos + 10, $topPos - 29);
 $page->drawText('Requested By.:'.$requester, $leftPos + 10, $topPos - 40);
 $page->drawText('Prepared By.:'.$_SESSION['sess_login_username'], $leftPos + 10, $topPos - 50);


 $page->drawText('Thank`s and wish you a quick recovery', $leftPos + 30, $topPos - 70);

    
    
    
    array_push($pdf->pages, $page);
    header('Content-type: application/pdf');
    echo $pdf->render();


?>
