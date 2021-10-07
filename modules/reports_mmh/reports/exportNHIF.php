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
echo date('H:i:s') , " NHIF Claims" , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("George Maina")
    ->setLastModifiedBy("George Maina")
    ->setTitle("NHIF Claims Report")
    ->setSubject("NHIF Claims Report")
    ->setDescription("NHIF Claims Report")
    ->setKeywords("NHIF Claims Report")
    ->setCategory("NHIF Claims Report");




$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'Credit No');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'NHIF No' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', 'PID' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', 'NAMES' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', 'BILL NUMBER' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', 'ADMISSION DATE' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2', 'DISCHARGE DATE' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H2', 'BED DAYS' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I2', 'INVOICE AMOUNT' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J2', 'TOTAL CREDIT' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K2', 'BALANCE' );

// Merge cells
$objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'NHIF CLAIMS');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
// Set fonts
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFill()->getStartColor()->setARGB('FF808080');


$date1 = new DateTime($_REQUEST[startDate]);
$startDate = $date1->format("Y-m-d");

$date2 = new DateTime($_REQUEST[endDate]);
$endDate = $date2->format("Y-m-d");

$sql = "SELECT b.creditNo,b.inputDate,b.admno,b.Names,b.admDate,b.disDate,b.wrdDays,b.nhifNo,b.nhifDebtorNo,
        b.debtorDesc,b.invAmount,b.totalCredit,b.balance,b.bill_number
        FROM care_ke_nhifcredits b ";

if($startDate && $endDate){
    $sql=$sql."WHERE b.inputDate between '$startDate' and '$endDate'";
}

if($debug)
    echo $sql;

$result=$db->Execute($sql);
$i=3;
while($row=$result->FetchRow()){

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$i",$row['creditNo']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$i",$row['nhifNo']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$i",$row['admno']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$i",$row['Names']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$i",$row['bill_number']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F$i",$row['admDate']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$i",$row['disDate']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H$i",$row['wrdDays']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I$i",$row['invAmount']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J$i",$row['totalCredit']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K$i",$row['balance']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);

    $i=$i+1;
}

// Merge cells
//$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');

// Set fonts
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

$objPHPExcel->getActiveSheet()->setTitle('NHIF Claims');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save(str_replace('php','xlsx',$root_path."docs/NHIFClaims.xlsx"));

echo "Created file : ".str_replace('php','xlsx',$root_path."docs/NHIFClaims.xlsx" ),EOL;

$objReader=PHPExcel_IOFactory::load($root_path."docs/NHIFClaims.xlsx");

?>
<script>
    window.open('../../../docs/NHIFClaims.xlsx', "Discharges Report",
        "menubar=no,toolbar=no,width=500,height=300,location=yes,resizable=yes,scrollbars=yes,status=yes");
</script>
