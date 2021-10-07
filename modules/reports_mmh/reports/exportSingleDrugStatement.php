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
echo date('H:i:s') , " Single Drug Statement" , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("George Maina")
    ->setLastModifiedBy("George Maina")
    ->setTitle("Single Drug Statement")
    ->setSubject("Single Drug Statement")
    ->setDescription("Single Drug Statement")
    ->setKeywords("Single Drug Statement")
    ->setCategory("Single Drug Statement");

$objPHPExcel->getActiveSheet(0)->mergeCells('A1:F1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Single Drug Statement');


$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'Order Date');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'Pid' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', 'Encounter Number' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', 'Patient Name' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', 'Price');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', 'Dosage' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2', 'Times Per Day' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H2', 'Days' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', 'Total Quantity' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', 'Issued' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', 'Balance');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', 'Total Cost' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2', 'Prescriber' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H2', 'Issued by' );


$date1 = new DateTime($_REQUEST[startDate]);
$startDate = $date1->format("Y-m-d");

$date2 = new DateTime($_REQUEST[endDate]);
$endDate = $date2->format("Y-m-d");

 $sql="SELECT CONCAT(b.`order_date`,' ',b.`order_time`) AS OrderDate,b.`OP_no` as pid,p.encounter_nr,b.`patient_name`
            ,b.`item_id`,b.`Item_desc`,p.price,p.dosage,p.times_per_day,p.days,p.prescriber,b.`qty`,b.`orign_qty` as issued,
            b.balance,(b.`orign_qty`*b.price) as TotalCost,b.`input_user` as issuedBy
            FROM care_ke_internal_orders b LEFT JOIN care_encounter_prescription p on b.presc_nr=p.nr
            WHERE  b.order_date between '$startDate' and '$endDate'";


if($debug) echo $sql;

$result=$db->Execute($sql);
$i=3;
while($row=$result->FetchRow()){

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$i",$row['Order_Date']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$i",$row['pid']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$i",$row['Encounter_Number']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$i",$row['Patient_Name']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$i",$row['Price']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F$i",$row['Dosage']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$i",$row['Times_Per_Day']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H$i",$row['Days']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I$i",$row['Total_Quantity']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J$i",$row['Issued']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K$i",$row['Balance']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L$i",$row['Total_cost']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("M$i",$row['Prescriber']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("N$i",$row['Issued_by']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(10);

    $i=$i+1;
}

$objPHPExcel->getActiveSheet()->setTitle('Single Drug Statement');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save(str_replace('php','xlsx',$root_path."/docs/SingleDrugStatement.xlsx"));

echo "Created file : ".str_replace('php','xlsx',$root_path."docs/SingleDrugStatement.xlsx" ),EOL;

$objReader=PHPExcel_IOFactory::load($root_path."docs/SingleDrugStatement.xlsx");

?>
<script>
    window.open('../../../docs/SingleDrugStatement.xlsx', "Single Drug Statement",
        "menubar=no,toolbar=no,width=400,height=400,location=yes,resizable=yes,scrollbars=yes,status=yes");
</script>
