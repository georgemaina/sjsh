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
echo date('H:i:s') , " Out Patient Invoices" , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("George Maina")
    ->setLastModifiedBy("George Maina")
    ->setTitle("Out Patient Invoices")
    ->setSubject("Out Patient Invoices")
    ->setDescription("Out Patient Invoices")
    ->setKeywords("Out Patient Invoices")
    ->setCategory("Out Patient Invoices");

$objPHPExcel->getActiveSheet(0)->mergeCells('A1:F1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'Account Number');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'pid');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', 'Encounter Number' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', 'Names' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', 'Bill Date' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', 'Bill Number');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2', 'Amount' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H2', 'Payment Method' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I2', 'Member No' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J2', 'Diagnosis' );

function getPatientDiagnosis($encounterNr){
    global $db;
    $debug=true;

    $sql="SELECT encounter_nr,`ICD_10_description` FROM `care_tz_diagnosis` WHERE encounter_nr=$encounterNr";
    $results=$db->Execute($sql);
    $diag='';
    while($row=$results->FetchRow()){
        $diag=$diag.$row['ICD_10_description'].",";
    }
    return $diag;
}

$date1 = new DateTime($_REQUEST[startDate]);
$startDate = $date1->format("Y-m-d");

$date2 = new DateTime($_REQUEST[endDate]);
$endDate = $date2->format("Y-m-d");

 $sql="SELECT b.pid,encounter_nr,c.accno,CONCAT(p.`name_first`,' ',p.`name_last`,' ',p.`name_2`) AS pnames
            ,b.bill_number,m.`memberID`,b.bill_date,SUM(b.total) AS Total,
            IF(p.`insurance_ID`='-1','CASH',c.`name`) AS PaymentMethod   FROM care_ke_billing b 
            LEFT JOIN care_person p ON b.`pid`=p.`pid`  
            LEFT JOIN `care_tz_company` c ON p.`insurance_ID`=c.`id`
            LEFT JOIN `care_ke_debtormembers` m ON p.`pid`=m.`PID`
            WHERE b.pid<>''";
       
    if($startDate<>'' and $endDate<>''){
        $sql=$sql." and b.`bill_date` BETWEEN '$startDate' AND '$endDate'";
    }else{
         $sql=$sql." and b.`bill_date`='".date('Y-m-d')."'";
    }
    
    if($pid<>''){
        $sql=$sql." and b.pid='$pid'";
    }
    
    if($paymentPlan<>''){
        $sql=$sql." and p.insurance_ID='$paymentPlan'";
    }
    
    if($debtorCat<>''){
        $sql=$sql." and c.category='$debtorCat'";
    }
        
    $sql=$sql." GROUP BY b.pid ";
	
	
    if($debug) echo $sql;
    $result=$db->Execute($sql);
    $total = $result->RecordCount();


$i=3;
while($row=$result->FetchRow()){
    $diagnosis=getPatientDiagnosis($row['encounter_nr']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$i",$row['accno']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$i",$row['pid']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$i",$row['encounter_nr']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$i",$row['pnames']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$i",$row['bill_number']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F$i",$row['Total']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$i",$row['bill_date']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H$i",$row['PaymentMethod']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I$i",$row['memberID']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J$i",$diagnosis);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
   

    $i=$i+1;
}

$objPHPExcel->getActiveSheet()->setTitle('Out Patient Invoices');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save(str_replace('php','xlsx',$root_path."/docs/Out Patient Invoices.xlsx"));

echo "Created file : ".str_replace('php','xlsx',$root_path."docs/Out Patient Invoices.xlsx" ),EOL;

$objReader=PHPExcel_IOFactory::load($root_path."docs/Out Patient Invoices.xlsx");

?>
<script>
    window.open('../../../docs/Out Patient Invoices.xlsx', "Out Patient Invoices",
        "menubar=no,toolbar=no,width=400,height=400,location=yes,resizable=yes,scrollbars=yes,status=yes");
</script>
