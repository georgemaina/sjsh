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
    ->setTitle("Lab Activities Report")
    ->setSubject("Lab Activities Report")
    ->setDescription("Lab Activities Report contains all daily cash collections")
    ->setKeywords("Lab Activities Report")
    ->setCategory("Lab Activities Report");

$objPHPExcel->getActiveSheet(0)->mergeCells('A1:F1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'LAB ACTIVITIES REPORT');


$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'PID');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'PATIENT NAMES' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', 'ADMISSION' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', 'BILL DATE' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', 'LAB CODE' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', 'DESCRIPTION' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2', 'QTY' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H2', 'TOTAL' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I2', 'REQUESTED BY' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J2', 'LAB GROUP' );


$date1 =$_REQUEST[startDate];
$date2 =$_REQUEST[endDate];
$age1=$_REQUEST[age1];
$age2=$_REQUEST[age2];
$pid=$_REQUEST[pid];
$requestedBy=$_REQUEST[staffName];

$sql = "SELECT p.pid,CONCAT(p.name_first,' ',p.name_last,' ',p.name_2) AS pnames,b.`bill_date`,
              b.`bill_time`,b.`IP-OP` AS admission,b.partcode,l.`group_id`,b.service_type,b.Description,
              b.total AS Total,b.qty AS Lab_Count,b.`status`,b.`input_user`  FROM care_ke_billing b 
              INNER JOIN care_person p ON (b.pid = p.pid)
              LEFT JOIN `care_tz_drugsandservices` d ON b.`partcode`=d.`partcode`
              LEFT JOIN `care_tz_laboratory_param` l ON b.`partcode`=l.`item_id`
              WHERE d.`category`='LABT'";

if (isset($date1) && isset($date2) && $date1 <> "" && $date1 <> "") {
    $date = new DateTime($date1);
    $dt1 = $date->format("Y-m-d");

    $date = new DateTime($date2);
    $dt2 = $date->format("Y-m-d");

    $sql = $sql . " and b.bill_date between '$dt1' and '$dt2' ";
} else {
    $sql = $sql . " and b.bill_date<=now()";
}

if(isset($pid) && $pid<>''){
    $sql = $sql . " and p.pid='$pid'";
}

if(isset($requestedBy) && $requestedBy<>''){
    $sql=$sql . " and b.input_user='$requestedBy'";
}

$sql = $sql . " GROUP BY p.`pid`,b.`Description` order by b.partcode desc";

if($debug) echo $sql;

$result=$db->Execute($sql);
$i=3;
while($row=$result->FetchRow()){

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$i",$row['pid']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$i",$row['pnames']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$i",$row['bill_date']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$i",$row['bill_time']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$i",$row['partcode']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F$i",$row['Description']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$i",$row['Lab_Count']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H$i",$row['Total']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I$i",$row['input_user']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J$i",$row['group_id']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);

    $i=$i+1;
}

$objPHPExcel->getActiveSheet()->setTitle('lAB Activities');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save(str_replace('php','xlsx',$root_path."/docs/LabActivities.xlsx"));

echo "Created file : ".str_replace('php','xlsx',$root_path."docs/LabActivities.xlsx" ),EOL;

$objReader=PHPExcel_IOFactory::load($root_path."docs/LabActivities.xlsx");

?>
<script>
    window.open('../../../docs/LabActivities.xlsx', "Lab Activities Report",
        "menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
</script>
