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
echo date('H:i:s') , " Pharmacy Revenue Report" , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("George Maina")
    ->setLastModifiedBy("George Maina")
    ->setTitle("Pharmacy Drugs Revenue Report")
    ->setSubject("Pharmacy Drugs Revenue Report")
    ->setDescription("Pharmacy Drugs Revenue Report contains all daily cash collections")
    ->setKeywords("Pharmacy Drugs Revenue Report")
    ->setCategory("Pharmacy Drugs Revenuet Report");

$objPHPExcel->getActiveSheet(0)->mergeCells('A1:F1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Pharmacy Drugs Revenue Report');


$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'PART CODE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'DESCRIPTION' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', 'CATEGORY' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', 'UNIT PRICE' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', 'QUANTITIES' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', 'TOTAL AMOUNT' );



$date1 = new DateTime($_REQUEST[startDate]);
$startDate = $date1->format("Y-m-d");

$date2 = new DateTime($_REQUEST[endDate]);
$endDate = $date2->format("Y-m-d");


$sql = "SELECT b.`item_id`,b.`Item_desc`,p.price,sum(b.`qty`) as qty,sum(b.`orign_qty`) as issued,
            b.balance,sum((b.`orign_qty`*b.price)) as TotalCost,p.drug_class
            FROM care_ke_internal_orders b LEFT JOIN care_encounter_prescription p on b.presc_nr=p.nr
            WHERE p.drug_class in ('Drug_list','Medical Supplies')";

            if ($startDate <> "" && $endDate <> "") {
                  $sql = $sql . " and b.order_date between '$startDate' and '$endDate' ";
            }

      $sql = $sql . "GROUP BY item_id ORDER BY Total DESC";
//if($debug) 
    echo $sql;

$result=$db->Execute($sql);
$i=3;
while($row=$result->FetchRow()){

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$i",$row['item_id']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$i",$row['Item_desc']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$i",$row['drug_class']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$i",$row['price']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$i",$row['issued']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F$i",$row['TotalCost']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);

    $i=$i+1;
}

$objPHPExcel->getActiveSheet()->setTitle('Pharmacy drugs Revenue');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save(str_replace('php','xlsx',$root_path."/docs/PharmacyRevenue.xlsx"));

echo "Created file : ".str_replace('php','xlsx',$root_path."docs/PharmacyRevenue.xlsx" ),EOL;

$objReader=PHPExcel_IOFactory::load($root_path."docs/PharmacyRevenue.xlsx");

?>
<script>
    window.open('../../../docs/PharmacyRevenue.xlsx', "Pharmacy Revenue Report",
        "menubar=no,toolbar=no,width=600,height=300,location=yes,resizable=yes,scrollbars=yes,status=yes");
</script>
