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
require($root_path.'../ExcelClasses/PHPExcel.php');

// Create new PHPExcel object
echo date('H:i:s') , " Create new OP Mobidity" , EOL;
$objPHPExcel = new PHPExcel();


$dt1 = new DateTime($_REQUEST[date1]);
$date1 = $dt1->format('Y-m-d');

$dt2 = new DateTime($_REQUEST[date2]);
$date2 = $dt2->format('Y-m-d');

// Set document properties
echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("George Maina")
    ->setLastModifiedBy("George Maina")
    ->setTitle("OP Mobidity")
    ->setSubject("OP Mobidity")
    ->setDescription("OP Mobidity")
    ->setKeywords("OP Mobidity php")
    ->setCategory("OP Mobidity");

$objPHPExcel->getActiveSheet(0)->mergeCells('A1:F1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'OP Mobidity');


$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'ICD CODE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'DESCRIPTION' );

$s = 'B';
for ($i = 1; $i <= 31; $i++) {
       $objPHPExcel->setActiveSheetIndex(0)->setCellValue( ++$s.'2', $i );
}
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AH2', 'TOTALS' );

$reportMonth=$_REQUEST[reportMonth];
$reportType=$_REQUEST[reportType];

$sql="SELECT `ID`,`ReportMonth`,`DateUpdated`,`ICDCode`,`Disease`,`1`,`2`,`3`,`4`,`5`,`6`,`7`,`8`,`9`,`10`,`11`,`12`,`13`,`14`,
            `15`,`16`,`17`,`18`,`19`,`20`,`21`,`22`,`23`,`24`,`25`,`26`,`27`,`28`,`29` `30`,`31` 
            FROM `care_ke_opmobidity` m LEFT JOIN care_icd10_en i ON m.ICDCode=i.diagnosis_code
            where DateUpdated between '$date1' and '$date2' and i.type='$reportType'";
//echo $sql;

$results=$db->Execute($sql);


$indexRow=3;
while($row = $results->FetchRow()){

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$indexRow,  $row['ICDCode'] );
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$indexRow,  $row['Disease'] );

    $totals=0;
    $t='B';
    for ($i = 1; $i <= 31; $i++) {
          $col=++$t;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($col.$indexRow,  $row[$i] );
        $totals=$totals+$row[$i];
    }
    
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AH'.$indexRow,  $totals );

    $indexRow++;
}

$objPHPExcel->getActiveSheet()->setTitle('OP Mobidity');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$currTime=date('YmdHis');

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save(str_replace('php','xlsx',$root_path."/docs/OPMobidity ".$currTime.".xlsx"));

echo "Created file : ".str_replace('php','xlsx',$root_path."docs/OPMobidity ".$currTime.".xlsx" ),EOL;

$objReader=PHPExcel_IOFactory::load($root_path."docs/OPMobidity ".$currTime.".xlsx");
?>

<script>
    window.open("<?php echo $root_path.'docs/OPMobidity '.$currTime.'.xlsx'; ?>","OpMobidity Reports","menubar=yes,toolbar=yes,width=500,height=550,location=yes,resizable=no,scrollbars=yes,status=yes")

</script>

