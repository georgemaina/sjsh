<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Africa/Nairobi');

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

/** Include PHPExcel */
require_once '../../../../ExcelClasses/PHPExcel.php';


// Create new PHPExcel object
echo date('H:i:s') , " Discharges" , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("George Maina")
    ->setLastModifiedBy("George Maina")
    ->setTitle("Discharge Report")
    ->setSubject("Discharge Report")
    ->setDescription("Discharge Report")
    ->setKeywords("Discharge Report")
    ->setCategory("Discharge Report");




$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'PID');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'NAMES' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', 'SEX' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', 'DATE OF BIRTH' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', 'ADMISSION DATE' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', 'DISCHARGE DATE' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2', 'INVOICE NO' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H2', 'WARD' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I2', 'BED DAYS' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J2', 'INVOICE AMOUNT' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K2', 'AMOUNT PAID' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L2', 'BALANCE' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M2', 'AGE' );
// Merge cells
$objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'DISCHARGE REPORT');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
// Set fonts
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFill()->getStartColor()->setARGB('FF808080');


$date1 = new DateTime($_REQUEST[startDate]);
$startDate = $date1->format("Y-m-d");

$date2 = new DateTime($_REQUEST[endDate]);
$endDate = $date2->format("Y-m-d");

$wardNo=$_REQUEST[wardNo];
$discType=$_REQUEST[disType];
$grpWards=$_REQUEST[groupWards];
$sex=$_REQUEST[sex];



$sql = "SELECT p.pid,e.encounter_nr, CONCAT(p.name_first,' ',p.name_last,' ',p.name_2) AS `Names`,p.`date_birth`,p.`sex`,b.`bill_number`
                ,e.encounter_date,e.`encounter_time`,e.`discharge_date`,(DATEDIFF(DATE(NOW()),p.date_birth))/365 as age,
               l.discharge_type_nr,e.current_ward_nr,w.name AS wardName,DATEDIFF(e.discharge_date,e.`encounter_date`) AS BedDays ,
               SUM(IF( b.service_type NOT IN('payment','NHIF'),total,0)) AS bill,
               SUM(IF(b.service_type IN ('payment','NHIF'),total,0)) AS payment
               FROM care_encounter e
                LEFT JOIN care_ke_billing b ON e.encounter_nr=b.`encounter_nr`
                LEFT JOIN care_person p  ON e.pid=p.pid
                LEFT JOIN care_ward w ON e.current_ward_nr=w.nr
                LEFT JOIN care_encounter_location l ON e.encounter_nr=l.encounter_nr";


if ($startDate <> "" && $endDate <> "") {
    $sql = $sql . " where e.discharge_date between '$startDate' and '$endDate' ";
} else if ($startDate <> '' && $endDate == '') {
    $sql = $sql . " where e.discharge_date = '$startDate'";
} else {
    $sql = $sql . " where e.discharge_date<=now()";
}

if ($ward<>"" and $ward<>null) {
    $sql = $sql . " and w.nr=$ward";
}

if ($sex<>"" and $sex<>'null') {
    $sql = $sql . " and p.sex='$sex'";
}

if ($grpWards<>"" and $grpWards<>null) {
    if ($grpWards == 'adults') {
        $wards = " in('11','12','14','15','22','23','24')";
    } elseif ($grpWards == 'paeds') {
        $wards = " in ('13')";
    } elseif ($grpWards == 'mat') {
        $wards = " in ('26')";
    }
    $sql = $sql . " and e.current_ward_nr $wards";
}
//
//if ($discType<>"" and $discType<>null) {
//    $sql = $sql . " and l.`discharge_type_nr`='$discType'";
//}

$sql = $sql . " and e.encounter_class_nr = 1 AND e.is_discharged = 1 AND l.`type_nr`=5   GROUP BY e.`pid` order by e.discharge_date desc";

if($debug)
    echo $sql;

$result=$db->Execute($sql);
$i=3;
while($row=$result->FetchRow()){

    $bal= $row[bill]-$row[payment];

    $admDate= new DateTime($row['encounter_date']);
    $admDate2 = $admDate->format("d-m-Y");

    $disDate= new DateTime($row['discharge_date']);
    $disDate2 = $disDate->format("d-m-Y");

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$i",$row['pid']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$i",$row['Names']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$i",$row['sex']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$i",$row['date_birth']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$i",$admDate2);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F$i",$disDate2);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$i",$row['bill_number']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H$i",$row['wardName']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I$i",$row['BedDays']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J$i",$row['bill']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K$i",$row['payment']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L$i",$bal);
    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("M$i", number_format($row['age'],1));
    $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);

    $i=$i+1;
}

// Merge cells
//$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');

// Set fonts
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

$objPHPExcel->getActiveSheet()->setTitle('Discharges');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save(str_replace('php','xlsx',$root_path."docs/Discharges.xlsx"));

echo "Created file : ".str_replace('php','xlsx',$root_path."docs/Discharges.xlsx" ),EOL;

$objReader=PHPExcel_IOFactory::load($root_path."docs/Discharges.xlsx");

?>
<script>
    window.open('../../../docs/Discharges.xlsx', "Discharges Report",
        "menubar=no,toolbar=no,width=500,height=300,location=yes,resizable=yes,scrollbars=yes,status=yes");
</script>
