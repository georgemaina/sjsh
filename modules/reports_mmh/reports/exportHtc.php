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
echo date('H:i:s') , " Htc Report" , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("George Maina")
    ->setLastModifiedBy("George Maina")
    ->setTitle("Htc Report")
    ->setSubject("Htc Report")
    ->setDescription("Htc Report")
    ->setKeywords("Htc Report")
    ->setCategory("Htc Report");

$objPHPExcel->getActiveSheet(0)->mergeCells('A1:F1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Htc Report');


$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'Encounter Nr');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'Htc Date' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', 'Opt' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', 'Reason' );

$debug = true;
$date1 = new DateTime($_REQUEST[startDate]);
$startDate = $date1->format("Y-m-d");

$date2 = new DateTime($_REQUEST[endDate]);
$endDate = $date2->format("Y-m-d");

  $htcs=$_REQUEST[htcs];
  $htcsReason=$_REQUEST[htcReason];
    
    $sql="SELECT encounter_nr,msr_date AS HtcDate,VALUE AS OPT,notes
           FROM care_encounter_measurement WHERE msr_type_nr=12";
    
    if($startDate<>"" && $endDate<>""){
        $sql=$sql." and msr_date between '$startDate' and '$endDate'";
    }
    
    if($htcs<>""){
        $sql=$sql." and VALUE='$htcs'";
    }
        
    if($htcsReason<>""){
        $sql=$sql." and notes='$htcsReason'";
    }
    
    if($debug) echo $sql;
    
      $request = $db->Execute($sql);

        $total = $request->RecordCount();
$i=3;
while($row=$request ->FetchRow()){

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$i",$row['encounter_nr']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$i",$row['HtcDate']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$i",$row['OPT']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$i",$row['notes']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);

    $i=$i+1;
}

$objPHPExcel->getActiveSheet()->setTitle('Clinic Activities');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save(str_replace('php','xlsx',$root_path."/docs/Htc Report.xlsx"));

echo "Created file : ".str_replace('php','xlsx',$root_path."docs/Htc Report.xlsx" ),EOL;

$objReader=PHPExcel_IOFactory::load($root_path."docs/Htc Report.xlsx");

?>
<script>
    window.open('../../../docs/Htc Report.xlsx', "Htc Report",
        "menubar=no,toolbar=no,width=400,height=400,location=yes,resizable=yes,scrollbars=yes,status=yes");
</script>
