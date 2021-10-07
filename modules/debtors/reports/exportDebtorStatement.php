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
echo date('H:i:s') , " Debtors Statement" , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("George Maina")
    ->setLastModifiedBy("George Maina")
    ->setTitle("Debtors Statement Report")
    ->setSubject("Debtors Statement Report")
    ->setDescription("Debtors Statement Report contains all ")
    ->setKeywords("Debtors Statement Report")
    ->setCategory("Debtors Statement Report");

$objPHPExcel->getActiveSheet(0)->mergeCells('A1:F1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Debtors Statement');


$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'Acc No');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'Trans No' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', 'PID' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', 'EncounterNo' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', 'Patient Name' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', 'Trans Date' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2', 'Bill No' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H2', 'Amount');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I2', 'Type' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J2', 'Allocated' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K2', 'Allocated Amount' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L2', 'Invoice Balance' );

$searchParam = $_REQUEST[searchParam];
$showAllocated=$_REQUEST[showAllocated];

$accno=$_REQUEST[accNo];

$sql = "SELECT d.accno,d.transno,d.pid,d.`encounter_nr`,p.`name_first`,p.`name_2`,p.`name_last`
            ,d.`lastTransDate`,d.`bill_number`, d.`amount`,e.`encounter_date`,e.`discharge_date`,e.`encounter_class_nr`,
            p.`addr_zip`,p.`phone_1_nr`,p.`cellphone_1_nr`,p.`citizenship`,p.`email`,c.`name` AS cname,d.transdate,d.transtype,
            d.`allocated`,d.`allocatedAmount`,d.`invoiceBalance`
            FROM care_ke_debtortrans d
            LEFT JOIN care_person p ON d.`pid`=p.`pid`
            LEFT JOIN care_ke_debtors c ON d.`accno`=c.`accno`
	      LEFT JOIN care_encounter e ON d.`encounter_nr`=e.`encounter_nr` WHERE  d.accno<>''";


$sql2 = "select count(d.pid) as countp from care_ke_debtortrans d left join care_person p on d.pid=p.pid where d.accno<>''";

if($showAllocated=='false'){
    $sql.=" and d.allocated=0";
    $sql2.=" and d.allocated=0";
}

if (isset($accno)) {
    $sql.=" and d.accno='$accno'";
    $sql2.=" and d.accno='$accno'";
}

if ($_REQUEST[strDate1] <> '' && $_REQUEST[strDate2] <> '') {
    $startd = new DateTime($_REQUEST[strDate1]);
    $startDate = $startd->format('Y-m-d');

    $end = new DateTime($_REQUEST[strDate2]);
    $endDate = $end->format('Y-m-d');
    if ($startDate > '2000-01-01' && $endDate > '2000-01-01') {
        $sql.=" and d.lastTransDate between '$startDate' and '$endDate'";
        $sql2.=" and d.lastTransDate between '$startDate' and '$endDate'";
    }
}
//
if (isset($searchParam)) {
    $sql.=" and d.pid=$searchParam or p.name_first like'%$searchParam%' or d.name_2 like'%$searchParam%' or p.name_last like'%$searchParam%'";
    $sql2.=" and d.pid=$searchParam or p.name_first like'%$searchParam%' or d.name_2 like'%$searchParam%' or p.name_last like'%$searchParam%'";
}

$result=$db->Execute($sql);
$i=3;
while($row=$result->FetchRow()){

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$i",$row['accno']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$i",$row['transno']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$i",$row['pid']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$i",$row['encounter_nr']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$i",$row['name_first'].' '.$row['name_2'].' '.$row['name_last']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F$i",$row['lastTransDate']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$i",$row['bill_number']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H$i",$row['amount']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I$i",$row['transtype']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J$i",$row['allocated']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K$i",$row['allocatedamount']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L$i",$row['invoicebalance']);

    $i=$i+1;
}

$objPHPExcel->getActiveSheet()->setTitle('DebtorsStatement');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save(str_replace('php','xlsx',$root_path."/docs/DebtorsStatement.xlsx"));

echo "Created file : ".str_replace('php','xlsx',$root_path."docs/DebtorsStatement.xlsx" ),EOL;

$objReader=PHPExcel_IOFactory::load($root_path."docs/DebtorsStatement.xlsx");

?>
<script>
    window.open('../../../docs/DebtorsStatement.xlsx', "CDebtors Statement",
        "menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
</script>
