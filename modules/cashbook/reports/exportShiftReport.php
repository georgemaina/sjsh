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
    ->setTitle("Cashiers Shift Report")
    ->setSubject("Cashiers Shift Report")
    ->setDescription("Cashiers Shift Report contains all daily cash collections")
    ->setKeywords("Cashiers Shift Report")
    ->setCategory("Cashiers Shift Report");

$objPHPExcel->getActiveSheet(0)->mergeCells('A1:F1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Cashiers Shift Report');


$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'RECEIPT NO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'TYPE' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', 'DATE' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', 'TIME' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', 'PID' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', 'NAMES' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2', 'PAY MODE' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H2', 'REVENUE CODE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I2', 'REVENUE DESC' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J2', 'UNIT PRICE' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K2', 'QUANTITY' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L2', 'CASH AMOUNT' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M2', 'MPESA' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N2', 'CHEQUES' );

$cashpoint = $_REQUEST['cashpoint'];
$shiftNo = $_REQUEST['shiftno'];
$cashier = $_SESSION['sess_login_username'];
$reportid = $_REQUEST['reportid'];
$date1=$_REQUEST[date1];
$date2=$_REQUEST[date2];

$sql = "SELECT b.Shift_no,b.ref_no,b.`type`,b.input_time,b.patient,b.name,b.rev_code,b.rev_desc,
b.proc_code,b.prec_desc,b.payer,b.location,b.pay_mode,b.amount,b.proc_qty,b.total,a.start_date,a.start_time,b.currdate,
b.pay_mode ,b.towards
FROM care_ke_receipts b JOIN care_ke_shifts a  ON b.shift_no=a.shift_no
WHERE b.cash_point='$cashpoint' and b.shift_no='$shiftNo' group by sale_id";

//echo $sql;

$result=$db->Execute($sql);
$i=3;
while($row=$result->FetchRow()){

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$i",$row['ref_no']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$i",$row['type']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$i",$row['currdate']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$i",$row['input_time']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$i",$row['patient']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F$i",$row['name']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$i",$row['pay_mode']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H$i",$row['proc_code']);
    if($row['rev_code']=='GL'){
        $procDesc=$row['towards'];
    }else{
        $procDesc=$row['prec_desc'];
    }
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I$i",$procDesc);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J$i",$row['amount']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K$i",$row['proc_qty']);

    if ($row['pay_mode'] == 'CAS' || $row['pay_mode'] == 'cas'){
        $cash= 'Ksh ' . $row['total'];
        $casTotal=$casTotal+$row['total'];
    }else{
        $cash="";
    }

    if ($row['pay_mode'] == 'CHQ' || $row['pay_mode'] == 'chq'){
        $chq= 'Ksh ' . $row['total'];
        $chqTotal=$chqTotal+$row['total'];
    }else{
        $chq="";
    }

    if ($row['pay_mode'] == 'MPESA' || $row['pay_mode'] == 'mpesa' || $row['pay_mode'] == ''){
        $mpesa='Ksh ' . $row['total'];
        $mpesa=$mpesa+$row['total'];
    }else{
        $mpesa="";
    }


    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L$i",$cash);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("M$i",$mpesa);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("N$i",$chq);


    $i=$i+1;
}

$objPHPExcel->getActiveSheet()->setTitle('CashiersReport');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save(str_replace('php','xlsx',$root_path."/docs/CashiersReport.xlsx"));

echo "Created file : ".str_replace('php','xlsx',$root_path."docs/CashiersReport.xlsx" ),EOL;

$objReader=PHPExcel_IOFactory::load($root_path."docs/CashiersReport.xlsx");

?>
<script>
        window.open('../../../docs/CashiersReport.xlsx', "Cashiers Shift Report",
            "menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
</script>
