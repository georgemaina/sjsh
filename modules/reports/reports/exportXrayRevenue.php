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
echo date('H:i:s') , " Cashiers Shift Report" , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("George Maina")
    ->setLastModifiedBy("George Maina")
    ->setTitle("Xray Revenue Report")
    ->setSubject("Xray Revenue Report")
    ->setDescription("Xray Revenue Report contains all daily cash collections")
    ->setKeywords("Xray Revenue Report")
    ->setCategory("Xray Revenue Report");

$objPHPExcel->getActiveSheet(0)->mergeCells('A1:F1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Xray Revenue Report');


$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'Xray CODE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'DESCRIPTION' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', 'QUANTITY' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', 'UNIT PRICE' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', 'TOTAL AMOUNT' );

$date1 = $_REQUEST[date1];
$date2 = $_REQUEST[date2];


 $sql = "SELECT b.`proc_code` AS partcode, b.`rev_desc` AS service_type,b.`Prec_desc` AS Description,
b.`amount` AS price,COUNT(b.`proc_code`) AS xray_Count
        FROM care_ke_receipts b WHERE b.`rev_code` = 'XRAT'";

			
    if ($startDate <> "" && $endDate <> "") {

        $sql = $sql . " and b.currdate between '$startDate' and '$endDate' ";
    } 

    $sql = $sql . "  GROUP BY b.proc_code,b.rev_desc, b.Prec_desc order by b.`proc_code` desc";
    if($debug) echo $sql;
    //p.pid,p.name_first,p.name_last,p.name_2,b.bill_date,b.bill_number,b.total

    $request = $db->Execute($sql);
    $rowCnt=$request->RecordCount();
$i=3;
while($row=$request->FetchRow()){

    $startDate = new DateTime($dt1);
    $date1 = $startDate->format("Y-m-d");

    $dt2 = $_REQUEST[date2];
    $endDate = new DateTime($dt2);
    $date2 = $endDate->format("Y-m-d");

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$i",$row['partcode']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$i",$row['service_type']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$i",$row['Description']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$i",$row['price']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$i",$row['xray_Count']);

    $i=$i+1;
}

$objPHPExcel->getActiveSheet()->setTitle('Xray Revenue');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save(str_replace('php','xlsx',$root_path."/docs/XrayRevenue .xlsx"));

echo "Created file : ".str_replace('php','xlsx',$root_path."docs/XrayRevenue .xlsx" ),EOL;

$objReader=PHPExcel_IOFactory::load($root_path."docs/XrayRevenue .xlsx");

?>
<script>
    window.open('../../../docs/XrayRevenue .xlsx', "Cashiers Shift Report",
        "menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
</script>
