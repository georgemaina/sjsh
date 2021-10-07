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

$debug=true;
// Create new PHPExcel object
echo date('H:i:s') , " NHIF Credits" , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("George Maina")
    ->setLastModifiedBy("George Maina")
    ->setTitle("NHIF Credits Report")
    ->setSubject("NHIF Credits Report")
    ->setDescription("NHIF Credits Report")
    ->setKeywords("NHIF Credits Report")
    ->setCategory("NHIF Credits Report");

    // b.creditNo,b.inputDate,b.admno,b.Names,b.admDate,b.disDate,b.wrdDays,b.nhifNo,
	// b.debtorDesc, b.invAmount,b.totalCredit,b.balance,b.bill_number,b.inputUser

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'Credit No');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'InputDate');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', 'Pid');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', 'Names');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', 'Admission Date');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', 'Discharge Date');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2', 'Ward Days');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H2', 'NHIF No');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I2', 'Invoice Amount');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J2', 'Total Credit');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K2', 'Balance');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L2', 'Bill Number');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M2', 'Input User');
// Merge cells
$objPHPExcel->getActiveSheet()->mergeCells('A1:J1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'NHIF Credits');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
// Set fonts
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFill()->getStartColor()->setARGB('FF808080');

$searchParam=$_REQUEST['searchParam'];
$startDate=$_REQUEST['startDate'];
$endDate=$_REQUEST['endDate'];

$sql = "SELECT b.creditNo,b.inputDate,b.admno,b.Names,b.admDate,b.disDate,b.wrdDays,b.nhifNo,b.nhifDebtorNo,
b.debtorDesc, b.invAmount,b.totalCredit,b.balance,b.bill_number,b.inputUser
FROM care_ke_nhifcredits b 
WHERE b.bill_number<>'' ";

if($searchParam<>'' and is_numeric($searchParam)){
    $sql.="and  pid='$searchParam'";
}elseif($searchParam<>'' and !is_numeric($searchParam)){
    $sql.="and b.Names like '%$searchParam%'";
}

if ($startDate<>'null' && $endDate<>'null') {
    $date1 = new DateTime($startDate);
    $dt1 = $date1->format("Y-m-d");

    $date2 = new DateTime($endDate);
    $dt2 = $date2->format("Y-m-d");

    $sql = $sql . "and b.inputDate between '$dt1' and '$dt2'";
}
if ($debug) echo $sql;

// b.creditNo,b.inputDate,b.admno,b.Names,b.admDate,b.disDate,b.wrdDays,b.nhifNo,b.nhifDebtorNo,
// b.debtorDesc, b.invAmount,b.totalCredit,b.balance,b.bill_number,b.inputUser

$result=$db->Execute($sql);
$i=3;
while($row=$result->FetchRow()){
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$i",$row['creditNo']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$i",$row['inputDate']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$i",$row['admno']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$i",$row['Names']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$i",$row['admDate']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F$i",$row['disDate']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$i",$row['wrdDays']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H$i",$row['nhifNo']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I$i",$row['invAmount']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J$i",$row['totalCredit']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K$i",$row['balance']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L$i",$row['bill_number']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("M$i",$row['inputUser']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);

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

$objPHPExcel->getActiveSheet()->setTitle('NHIF Credits Report');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save(str_replace('php','xlsx',$root_path."docs/NhifCredits.xlsx"));

echo "Created file : ".str_replace('php','xlsx',$root_path."docs/NhifCredits.xlsx" ),EOL;

$objReader=PHPExcel_IOFactory::load($root_path."docs/NhifCredits.xlsx");

?>
<script>
    window.open('../../../docs/NhifCredits.xlsx', "NHIF Credits Report",
        "menubar=no,toolbar=no,width=500,height=300,location=yes,resizable=yes,scrollbars=yes,status=yes");
</script>
