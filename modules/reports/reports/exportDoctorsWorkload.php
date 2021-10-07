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

$debug = true;
// Create new PHPExcel object
echo date('H:i:s') , " Doctors Workload" , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("George Maina")
    ->setLastModifiedBy("George Maina")
    ->setTitle("Doctors Workload")
    ->setSubject("Doctors Workload")
    ->setDescription("Doctors Workload")
    ->setKeywords("Doctors Workload")
    ->setCategory("Doctors Workload");

$objPHPExcel->getActiveSheet(0)->mergeCells('A1:F1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Clinician');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'Notes');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'Diagnosis' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', 'Labs' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', 'Prescription' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', 'Xray');

$date1 = new DateTime($_REQUEST[startDate]);
$startDate = $date1->format("Y-m-d");

$date2 = new DateTime($_REQUEST[endDate]);
$endDate = $date2->format("Y-m-d");

   $sqlNotes="SELECT create_id FROM `care_encounter_notes` 
            WHERE DATE BETWEEN '$startDate' AND '$endDate'
            GROUP BY create_id";
    if($debug) echo $sqlNotes;
    $results=$db->Execute($sqlNotes);
    $total = $results->RecordCount();

$i=3;
while($row=$results->FetchRow()){

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$i",$row['Clinician']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$i",$row['Notes']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$i",$row['Diagnosis']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$i",$row['Labs']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$i",$row['Prescription']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F$i",$row['Xray']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
   
    $i=$i+1;
}

$objPHPExcel->getActiveSheet()->setTitle('Doctors Workload');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save(str_replace('php','xlsx',$root_path."/docs/Doctors Workload.xlsx"));

echo "Created file : ".str_replace('php','xlsx',$root_path."docs/Doctors Workload.xlsx" ),EOL;

$objReader=PHPExcel_IOFactory::load($root_path."docs/Doctors Workload.xlsx");

?>
<script>
    window.open('../../../docs/Doctors Workload.xlsx', "Doctors Workload",
        "menubar=no,toolbar=no,width=400,height=400,location=yes,resizable=yes,scrollbars=yes,status=yes");
</script>
