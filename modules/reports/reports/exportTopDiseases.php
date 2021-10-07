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
echo date('H:i:s') , " Top Diseases" , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("George Maina")
    ->setLastModifiedBy("George Maina")
    ->setTitle("Top Diseases")
    ->setSubject("Top Diseases")
    ->setDescription("Top Diseases")
    ->setKeywords("Top Diseases")
    ->setCategory("Top Diseases");

$objPHPExcel->getActiveSheet(0)->mergeCells('A1:F1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Icd Code');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'Description');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', 'Disease Count' );

$date1 = new DateTime($_REQUEST[startDate]);
$startDate = $date1->format("Y-m-d");

$date2 = new DateTime($_REQUEST[endDate]);
$endDate = $date2->format("Y-m-d");

$sql="Select diagnosis_code,Description from care_icd10_en WHERE diagnosis_code LIKE 'O%'";
    if($debug) echo $sql;

    $results=$db->Execute($sql);
    $totalCount=$results->RecordCount();

$i=3;
while($row=$results->FetchRow()){

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$i",$row['diagnosis_code']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$i",$row['Description']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    //$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$i",$row['totalCount']);
    //$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
    
  
    $i=$i+1;
}

$objPHPExcel->getActiveSheet()->setTitle('Top Diseases');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save(str_replace('php','xlsx',$root_path."/docs/TopDiseases .xlsx"));

echo "Created file : ".str_replace('php','xlsx',$root_path."docs/TopDiseases.xlsx" ),EOL;

$objReader=PHPExcel_IOFactory::load($root_path."docs/TopDiseases.xlsx");

?>
<script>
    window.open('../../../docs/TopDiseases.xlsx', "Diseases",
        "menubar=no,toolbar=no,width=400,height=400,location=yes,resizable=yes,scrollbars=yes,status=yes");
</script>
