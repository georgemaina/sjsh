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
echo date('H:i:s') , " Orders Template" , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("George Maina")
    ->setLastModifiedBy("George Maina")
    ->setTitle("Orders Template Report")
    ->setSubject("Orders Template Report")
    ->setDescription("DOrders Template Report contains all ")
    ->setKeywords("Orders Template Report")
    ->setCategory("Orders Template Report");

$objPHPExcel->getActiveSheet(0)->mergeCells('A1:F1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Orders Template');


$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'PartCode');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'Description' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', 'PurchasingUnit' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', 'quantity' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', 'MonthlyUsage' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', 'UsedLastMonth' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2', 'UsedLastQuarter' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H2', 'UsedLastYear');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I2', 'ReorderLevel' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J2', 'MaximumLevel' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K2', 'IfToOrder' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L2', 'SuggestedOrder' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M2', 'OrderInMultiplesOf' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N2', 'QtyToOrder' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O2', 'SupplierCode' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P2', 'AlternateSupplier' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q2', 'Comments' );

$searchParam = $_REQUEST[searchParam];
$showAllocated=$_REQUEST[showAllocated];

$accno=$_REQUEST[accNo];

 $sql="SELECT s.ID,s.`StockID`,`Description`,`PurchasingUnit`,l.`quantity`,`MonthlyUsage`,`UsedLastMonth`,
            `UsedLastQuarter`, `UsedLastYear`, s.`ReorderLevel`,`MaximumLevel`,`IfToOrder`,`SuggestedOrder`,
            `OrderInMultiplesOf`, `QtyToOrder`,`SupplierCode`, `AlternateSupplier`,  `Comments` 
         FROM  `care_ke_orders_template` s LEFT JOIN maua.`locstock` l ON s.`StockID`=l.`stockid`";


$result=$db->Execute($sql);
$i=3;
while($row=$result->FetchRow()){

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$i",$row['StockID']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$i",$row['Description']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$i",$row['PurchasingUnit']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$i",$row['quantity']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$i",$row['MonthlyUsage']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F$i",$row['UsedLastMonth']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$i",$row['UsedLastQuarter']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H$i",$row['UsedLastYear']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I$i",$row['ReorderLevel']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J$i",$row['MaximumLevel']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K$i",$row['IfToOrder']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L$i",$row['SuggestedOrder']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("M$i",$row['OrderInMultiplesOf']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("N$i",$row['QtyToOrder']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("O$i",$row['SupplierCode']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("P$i",$row['AlternateSupplier']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("Q$i",$row['Comments']);

    $i=$i+1;
}

$objPHPExcel->getActiveSheet()->setTitle('Orders Template');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save(str_replace('php','xlsx',$root_path."/docs/OrdersTemplate.xlsx"));

echo "Created file : ".str_replace('php','xlsx',$root_path."docs/OrdersTemplate.xlsx" ),EOL;

$objReader=PHPExcel_IOFactory::load($root_path."docs/OrdersTemplate.xlsx");

?>
<script>
    window.open('../../../docs/OrdersTemplate.xlsx', "Debtors Statement",
        "menubar=no,toolbar=no,width=400,height=300,location=yes,resizable=yes,scrollbars=yes,status=yes");
</script>
