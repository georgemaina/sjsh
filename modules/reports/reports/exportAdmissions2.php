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
echo date('H:i:s') , " Admissions Report" , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
//echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("George Maina")
    ->setLastModifiedBy("George Maina")
    ->setTitle("Admissions Report")
    ->setSubject("Admissions Report")
    ->setDescription("Admissions Report")
    ->setKeywords("Admissions Report")
    ->setCategory("Admissions Report");

$objPHPExcel->getActiveSheet(0)->mergeCells('A1:F1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Admissions Report');


$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'PID');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'NAME');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', 'SEX');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', 'DOB');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', 'AGE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', 'ADMISSION DATE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2', 'DISCHARGE DATE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H2', 'BED DAYS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I2', 'WARD');
/* $objPHPExcel->setActiveSheetIndex(0) ->setCellValue('J2', 'INVOICE NUMBER' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K2', 'PAYING ACCOUNT' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L2', 'INVOICE AMOUNT' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M2', 'AMOUNT PAID' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N2', 'BALANCE' );  */


$date1 = new DateTime($_REQUEST['startDate']);
$startDate = $date1->format("Y-m-d");

$date2 = new DateTime($_REQUEST['endDate']);
$endDate = $date2->format("Y-m-d");

    /* $wards = $_REQUEST[ward];
    $disType = $_REQUEST[disType];
    $grpWards = $_REQUEST[grpWards];
    $sex = $_REQUEST[sex]; */

  $sql = "SELECT e.pid,e.newAdm_No,CONCAT(p.name_first,' ',p.name_last,' ',p.name_2) AS names,p.sex,
        p.date_birth,(DATEDIFF(DATE(NOW()),p.date_birth))/365 as age,e.encounter_date,e.encounter_time,
  e.current_ward_nr,w.name  AS wardname,(DATEDIFF(DATE(NOW()),e.encounter_date)) AS BedDays FROM care_person p
  LEFT JOIN care_encounter e ON (p.pid = e.pid) left join care_ward w on e.current_ward_nr=w.nr";



    if ($startDate <> "" && $endDate <> "") {
        $sql = $sql . " where e.encounter_date between '$startDate' and '$endDate' ";
    }

    if ($wards) {
        $sql = $sql . " and e.current_ward_nr=$wards";
    }

    if ($sex) {
        $sql = $sql . " and p.sex='$sex'";
    }

    if ($grpWards) {
        if ($grpWards == 'adults') {
            $wards = " in('1','2','4','5')";
        } elseif ($grpWards == 'paeds') {
            $wards = " in ('6')";
        } elseif ($grpWards == 'mat') {
            $wards = " in ('5')";
        }
        $sql = $sql . " and e.current_ward_nr $wards";
    }

    $sql = $sql . " and e.encounter_class_nr = 1 order by e.encounter_date desc";

    if($debug) echo $sql;

$result=$db->Execute($sql);
$i=3;
while($row=$result->FetchRow()){

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$i",$row['pid']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$i",$row['names']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$i",$row['sex']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$i",$row['date_of_birth']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$i",$row['age']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F$i",$row['admission_date']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$i","");
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H$i",$row['bed_days']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("I$i",$row['wardname']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
/*     $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J$i",$row['invoice_number']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K$i",$row['paying_account']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L$i",$row['invoice_amount']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
	 $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K$i",$row['amount_paid']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L$i",$row['balance']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10); */

    $i=$i+1;
}

$objPHPExcel->getActiveSheet()->setTitle('Admissions Report');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save(str_replace('php','xlsx',$root_path."/docs/AdmissionsReport.xlsx"));

echo "Created file : ".str_replace('php','xlsx',$root_path."docs/AdmissionsReport.xlsx" ),EOL;

$objReader=PHPExcel_IOFactory::load($root_path."docs/AdmissionsReport.xlsx");

?>
<script>
    window.open('../../../docs/AdmissionsReport.xlsx', "admissionsReport",
        "menubar=no,toolbar=no,width=400,height=400,location=yes,resizable=yes,scrollbars=yes,status=yes");
</script>
