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

$debug = false;

// Create new PHPExcel object
echo date('H:i:s') , " Finalised Invoices" , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("George Maina")
    ->setLastModifiedBy("George Maina")
    ->setTitle("Finalised InvoicesReport")
    ->setSubject("Finalised Invoices Report")
    ->setDescription("Finalised Invoices Report")
    ->setKeywords("Finalised Invoices Report")
    ->setCategory("Finalised Invoices Report");




$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'PID');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'FILE NO' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', 'NAMES' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', 'ADMISSION DATE' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', 'DISCHARGE DATE' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', 'INVOICE NO' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2', 'WARD' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H2', 'FINALISED BY');

// Merge cells
$objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'FINALISED INVOICES');
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


$sql = "SELECT b.pid,p.`selian_pid` AS fileNo,b.encounter_nr,e.`encounter_class_nr`,CONCAT(p.`name_first`,' ',p.name_last,' ',p.`name_2`) AS `names`,b.bill_number
,e.`is_discharged`,e.`encounter_date` AS admissionDate,e.`discharge_date`,b.finalised,w.`description`,w.`nr`,b.finaliseby
FROM care_ke_billing b LEFT JOIN care_person p ON b.`pid`=p.`pid`  
LEFT JOIN care_encounter e ON b.`encounter_nr`=e.`encounter_nr`
LEFT JOIN care_ward w ON e.current_ward_nr=w.nr
WHERE b.finalised=1 AND e.`encounter_class_nr`=1 AND e.`is_discharged`=1 ";

if($startDate && $endDate){

    $sql=$sql." and e.discharge_date between '$startDate' and '$endDate'";
}

if(isset($wardNo) && $wardNo<>'null'){
    $sql=$sql." and w.nr='$wardNo'";
}

$sql=$sql." GROUP BY encounter_nr";


if($debug) echo $sql;

$result=$db->Execute($sql);
$i=3;
while($row=$result->FetchRow()){

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$i",$row['pid']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$i",$row['fileNo']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$i",$row['names']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$i",$row['admissionDate']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$i",$row['discharge_date']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F$i",$row['bill_number']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$i",$row['description']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H$i",$row['finaliseby']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);

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

$objPHPExcel->getActiveSheet()->setTitle('Finalised Invoices');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save(str_replace('php','xlsx',$root_path."/docs/FinalisedInvoices.xlsx"));

echo "Created file : ".str_replace('php','xlsx',$root_path."docs/FinalisedInvoices.xlsx" ),EOL;

$objReader=PHPExcel_IOFactory::load($root_path."docs/FinalisedInvoices.xlsx");

?>
<script>
    window.open('../../../docs/FinalisedInvoices.xlsx', "Finalised Invoices Report",
        "menubar=no,toolbar=no,width=500,height=300,location=yes,resizable=yes,scrollbars=yes,status=yes");
</script>
