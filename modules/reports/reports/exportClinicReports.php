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
echo date('H:i:s') , " Clinics Visit Report" , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("George Maina")
    ->setLastModifiedBy("George Maina")
    ->setTitle("Clinics Visit Report")
    ->setSubject("Clinics Visit Report")
    ->setDescription("Clinics Visit Report")
    ->setKeywords("Clinics Visit Report")
    ->setCategory("Clinics Visit Report");

$objPHPExcel->getActiveSheet(0)->mergeCells('A1:F1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Clinics Visit Report');


$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'REVENUE CODE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'DESCRIPTION' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', 'COUNT' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', 'AMOUNT' );


$date1 = new DateTime($_REQUEST[startDate]);
$startDate = $date1->format("Y-m-d");

$date2 = new DateTime($_REQUEST[endDate]);
$endDate = $date2->format("Y-m-d");

 $sql="SELECT proc_code,prec_desc, COUNT(prec_desc) AS PCOUNT, SUM(total) AS Amount FROM care_ke_receipts r
LEFT JOIN `care_tz_drugsandservices` d ON r.`rev_code`=d.partcode
            WHERE d.category IN('COPD','CMCH') AND currdate BETWEEN '$startDate' AND '$endDate'	
            GROUP BY proc_code";


if($debug) echo $sql;

$result=$db->Execute($sql);
$i=3;
while($row=$result->FetchRow()){

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$i",$row['proc_code']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$i",$row['prec_desc']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$i",$row['PCOUNT']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$i",$row['Amount']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);

    $i=$i+1;
}

$objPHPExcel->getActiveSheet()->setTitle('Clinic Activities');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save(str_replace('php','xlsx',$root_path."/docs/ClinicActivities.xlsx"));

echo "Created file : ".str_replace('php','xlsx',$root_path."docs/ClinicActivities.xlsx" ),EOL;

$objReader=PHPExcel_IOFactory::load($root_path."docs/ClinicActivities.xlsx");

?>
<script>
    window.open('../../../docs/ClinicActivities.xlsx', "Clinic Activities Report",
        "menubar=no,toolbar=no,width=400,height=400,location=yes,resizable=yes,scrollbars=yes,status=yes");
</script>
