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
echo date('H:i:s') , " Cashiers Shift Report" , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("George Maina")
    ->setLastModifiedBy("George Maina")
    ->setTitle("Lab Revenue Report")
    ->setSubject("Lab Revenue Report")
    ->setDescription("Lab Revenue Report contains all daily cash collections")
    ->setKeywords("Lab Revenue Report")
    ->setCategory("Lab Revenue Report");

$objPHPExcel->getActiveSheet(0)->mergeCells('A1:F1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'LAB REVENUE REPORT');


$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'LAB CODE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'DESCRIPTION' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', 'QUANTITY' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', 'UNIT PRICE' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', 'TOTAL AMOUNT' );

$date1 = $_REQUEST[date1];
$date2 = $_REQUEST[date2];


$sql = "SELECT b.`proc_code` AS partcode, b.`rev_desc` AS service_type,b.`Prec_desc` AS Description,
                b.`amount`,SUM(b.total) AS Total,COUNT(b.`proc_code`) AS Lab_Count
            FROM care_ke_receipts b WHERE b.`rev_desc` = 'laboratory'";

if (isset($date1) && isset($date2) && $date1 <> "" && $date1 <> "") {
    $date = new DateTime($date1);
    $dt1 = $date->format("Y-m-d");

    $date = new DateTime($date2);
    $dt2 = $date->format("Y-m-d");

    $sql = $sql . " and b.currdate between '$dt1' and '$dt2' ";
} else {
    $sql = $sql . " and b.currdate<=now()";
}

$sql = $sql . "  GROUP BY b.proc_code order by SUM(b.total) desc";
if($debug) echo $sql;

$result=$db->Execute($sql);
$i=3;
while($row=$result->FetchRow()){

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$i",$row['partcode']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$i",$row['Description']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$i",$row['Lab_Count']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$i",$row['amount']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$i",$row['Total']);

    $i=$i+1;
}

$objPHPExcel->getActiveSheet()->setTitle('lAB Revenue');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save(str_replace('php','xlsx',$root_path."/docs/LabRevenue.xlsx"));

echo "Created file : ".str_replace('php','xlsx',$root_path."docs/LabRevenue.xlsx" ),EOL;

$objReader=PHPExcel_IOFactory::load($root_path."docs/LabRevenue.xlsx");

?>
<script>
    window.open('../../../docs/LabRevenue.xlsx', "Cashiers Shift Report",
        "menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
</script>
