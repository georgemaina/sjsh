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
echo date('H:i:s') , " Price List" , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("George Maina")
    ->setLastModifiedBy("George Maina")
    ->setTitle("Price List Report")
    ->setSubject("Price List Report")
    ->setDescription("Price List Report")
    ->setKeywords("Price List Report")
    ->setCategory("Price List Report");

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'PartCode');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'Description');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', 'TypeID');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', 'PriceType');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', 'Price');
// Merge cells
$objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'PRICE LIST');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
// Set fonts
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFill()->getStartColor()->setARGB('FF808080');


    $searchParam=$_REQUEST['searchParam'];
    $priceType=$_REQUEST['priceType'];

    $sql = "SELECT p.`partcode`,d.`item_description`,p.`priceType` as TypeID,t.`PriceType`,p.`price` FROM care_ke_prices p 
            INNER JOIN care_tz_drugsandservices d ON p.`partcode`=d.`partcode`
            INNER JOIN `care_ke_pricetypes` t ON p.`priceType`=t.`ID` where p.partcode<>''";


    if (isset($searchParam)) {
            $sql.=" and p.partcode like '%$searchParam%' OR d.item_description like '%$searchParam%'";
    }
    
    if(isset($priceType) && $priceType<>''){
        $sql.=" and t.ID = '$priceType'";
    }

    $sql.=" ORDER BY p.`partcode` ASC";

    if ($debug) echo $sql;


$result=$db->Execute($sql);
$i=3;
while($row=$result->FetchRow()){
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$i",$row['partcode']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$i",$row['item_description']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$i",$row['TypeID']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$i",$row['PriceType']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$i",$row['price']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);

    $i=$i+1;
}

$objPHPExcel->getActiveSheet()->setTitle('Price List');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save(str_replace('php','xlsx',$root_path."docs/PriceList.xlsx"));

echo "Created file : ".str_replace('php','xlsx',$root_path."docs/PriceList.xlsx" ),EOL;

$objReader=PHPExcel_IOFactory::load($root_path."docs/PriceList.xlsx");

?>
<script>
    window.open('../../../docs/PriceList.xlsx', "Price List Report",
        "menubar=no,toolbar=no,width=500,height=300,location=yes,resizable=yes,scrollbars=yes,status=yes");
</script>
