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
echo date('H:i:s') , " Stock Movement Report" , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("George Maina")
    ->setLastModifiedBy("George Maina")
    ->setTitle("Stock Movement Report")
    ->setSubject("Stock Movement Reportt")
    ->setDescription("Stock Movement Report")
    ->setKeywords("Stock Movement Reportt")
    ->setCategory("Stock Movement Report");

$objPHPExcel->getActiveSheet(0)->mergeCells('A1:F1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Stock Movement Report');


$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'PART CODE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'DESCRIPTION' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', 'UNITS MEASURE' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', 'TYPE' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', 'TRANS NO' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', 'NARRATION' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2', 'LOCATION' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H2', 'COST' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I2', 'QUANTITY' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J2', 'LEVEL' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K2', 'OPERATOR' );


$date1 = new DateTime($_REQUEST[startDate]);
$startDate = $date1->format("Y-m-d");
$date2 = new DateTime($_REQUEST[endDate]);
$endDate = $date2->format("Y-m-d");

$LocCode = $_REQUEST['locCode'];
$PartCode = $_REQUEST['partCode'];
$category= $_REQUEST['category'];
//$location= $_REQUEST['location'];
//$location2= $_REQUEST['location2'];
$transType= $_REQUEST['transType'];

$sql="select `stkmoveno`,`stockid`,d.`item_description`,d.`unit_measure`,t.`typeName`,s.`transno`,`loccode`,`supLoccode`,`trandate`,`pid`,
            `price`,`qty`,`newqoh`,`hidemovt`,`narrative`,`inputuser` 
          from  `care_ke_stockmovement` s left join care_tz_drugsandservices d on s.`stockid`=d.`partcode`
          left join care_ke_transactionnos t on s.`type`=t.`typeID` where partcode<>''";

        if($PartCode<>''){  $sql=$sql." and stockid='$PartCode'"; }
        if($startDate<>"" && $endDate<>""){ $sql=$sql." and trandate between '$startDate' and '$endDate'";}
        if($category<>''){$sql=$sql." AND d.`category`='$category'";}
        if($transType<>''){ $sql=$sql." and s.type='$transType'";}
        if($location<>'' && !is_null($location)&& $location2=='' && is_null($location2)){$sql=$sql." and supLoccode='$location'";}
        if($location=='' && is_null($location) && $location2<>'' && !is_null($location2) ){$sql=$sql." and loccode='$location2'";}
//        if(!empty($location) && !empty($location2))
//            {
//                 $sql=$sql." and supLoccode='$location' AND loccode='$location2'";
//            }
               
if($debug) 
    echo $sql;
$result=$db->Execute($sql);
$i=3;
while($row=$result->FetchRow()){
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$i",$row['stockid']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$i",$row['item_description']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$i",$row['unit_measure']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$i",$row['typeName']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$i",$row['transno']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F$i",$row['narrative']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$i",$row['loccode']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H$i",$row['price']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I$i",$row['qty']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J$i",$row['newqoh']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K$i",$row['inputuser']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
    $i=$i+1;
}

$objPHPExcel->getActiveSheet()->setTitle('Stock Movement Report');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save(str_replace('php','xlsx',$root_path."/docs/StockMovement.xlsx"));

echo "Created file : ".str_replace('php','xlsx',$root_path."docs/StockMovement.xlsx" ),EOL;

$objReader=PHPExcel_IOFactory::load($root_path."docs/StockMovement.xlsx");

?>
<script>
    window.open('../../../docs/StockMovement.xlsx', "Stock Movement Report",
        "menubar=no,toolbar=no,width=400,height=400,location=yes,resizable=yes,scrollbars=yes,status=yes");
</script>
