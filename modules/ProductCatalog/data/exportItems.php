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

$debug=false;
// Create new PHPExcel object
echo date('H:i:s') , " Items List" , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("George Maina")
    ->setLastModifiedBy("George Maina")
    ->setTitle("Items List Report")
    ->setSubject("Items List Report")
    ->setDescription("Items List Report")
    ->setKeywords("Items List Report")
    ->setCategory("Items List Report");

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'PartCode');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'Description');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', 'Category');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', 'CategoryID');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', 'Category');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', 'Unit Price');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2', 'ReorderLevel');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H2', 'Item Status');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I2', 'Units');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J2', 'Unit of Measure');
// Merge cells
$objPHPExcel->getActiveSheet()->mergeCells('A1:J1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'ITEMS LIST');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
// Set fonts
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFill()->getStartColor()->setARGB('FF808080');


$category=$_REQUEST[category];
$searchParam=$_REQUEST[searchParam];

$sql = "SELECT `partcode`,`item_description`,`unit_price`,`purchasing_class`, `category`,c.`item_Cat`,`reorderlevel`,`units`,
        `unit_measure`,i.`Description` AS itemStatus, `Unit_qty`,`Purchasing_Unit` 
      FROM `care_tz_drugsandservices` d LEFT JOIN `care_tz_itemscat` c ON d.`category`=c.`catID`
      LEFT JOIN `care_ke_itemstatus` i ON d.`item_status`=i.`ID`";

if ($category<>"") {
    $sql = $sql . " and category='$category'";
}

if ($searchParam<>"" && !is_null($searchParam)) {
    $sql = $sql . " and partcode='$searchParam' OR item_description like '%$searchParam%'";
}


$sql = $sql . "  order by item_description asc";

if($debug) echo $sql;

$result=$db->Execute($sql);
$i=3;
while($row=$result->FetchRow()){
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$i",$row['partcode']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$i",$row['item_description']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$i",$row['purchasing_class']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$i",$row['category']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$i",$row['item_Cat']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F$i",$row['unit_price']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$i",$row['reorderlevel']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H$i",$row['itemStatus']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I$i",$row['units']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J$i",$row['unit_measure']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);

    $i=$i+1;
}

// Merge cells
//$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');

//// Set fonts
//$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('Candara');
//$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
//$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
//$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
//$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

$objPHPExcel->getActiveSheet()->setTitle('Items List');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save(str_replace('php','xlsx',$root_path."docs/ItemsList.xlsx"));

echo "Created file : ".str_replace('php','xlsx',$root_path."docs/ItemsList.xlsx" ),EOL;

$objReader=PHPExcel_IOFactory::load($root_path."docs/ItemsList.xlsx");

?>
<script>
    window.open('../../../docs/ItemsList.xlsx', "Items List Report",
        "menubar=no,toolbar=no,width=500,height=300,location=yes,resizable=yes,scrollbars=yes,status=yes");
</script>
