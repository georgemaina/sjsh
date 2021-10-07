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
echo date('H:i:s') , " Pataientdrugstatement " , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("George Maina")
    ->setLastModifiedBy("George Maina")
    ->setTitle("Patient drug statement ")
    ->setSubject("Pataientdrugstatement")
    ->setDescription("Pataientdrugstatement")
    ->setKeywords("Pataientdrugstatement")
    ->setCategory("Pataientdrugstatement");




$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'PID');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'Prescription Date' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', 'Prescription Time' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', 'EncounterNo' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', 'Part Code' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', 'Description' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2', 'Qty Issued' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H2', 'Price' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I2', 'Total' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J2', 'Running Total' );


// Merge cells
$objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Pataientdrugstatement');
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

$age1 = $_REQUEST[age1];
    $age2 = $_REQUEST[age2];
    $date1 = $_REQUEST[date1];
    $date2 = $_REQUEST[date2];
    $gender = $_REQUEST[gender];
    $icd1 = $_REQUEST[icd1];
    $icd2 = $_REQUEST[icd2];
    $task = $_REQUEST[task];
    $visits = $_REQUEST[visits];
    $pid= $_REQUEST[pid];

     $searchParam=$_REQUEST[searchParam];

   $sql = "SELECT b.`order_date`,b.`order_time`,b.`OP_no` AS pid,e.encounter_class_nr,b.`patient_name`,b.`item_id`,
        b.`item_desc`,b.`qty`,b.price,b.`orign_qty`,b.`total`,b.`input_user` FROM care_ke_internal_orders b
        LEFT JOIN `care_encounter_prescription` p ON b.`presc_nr`=p.`nr`
        LEFT JOIN care_encounter e ON p.`encounter_nr`=e.`encounter_nr` AND b.`order_date`=e.`encounter_date`";
		
	if (isset($date1) && isset($date2) && $date1 <> "" && $date1 <> "") {
        $date = new DateTime($date1);
        $dt1 = $date->format("Y-m-d");

        $date = new DateTime($date2);
        $dt2 = $date->format("Y-m-d");

        $sql = $sql . " WHERE b.order_date between '$dt1' and '$dt2' ";
    } else {
        $sql = $sql . " WHERE b.order_date<=now()";
    }
	
	if(isset($pid) and $pid<>''){
		$sql=$sql." and b.`OP_no`='$pid'";
	}

    $sql = $sql . " order by b.order_date desc";
   //if($debug) 
	   echo $sql;


  $request = $db->Execute($sql);
        
$i=3;
$runTotal=0;
while($row=$request->FetchRow()){
	$runTotal=$runTotal+$row['total'];

    $bal= $row[bill]-$row[payment];

    $admDate= new DateTime($row['encounter_date']);
    $admDate2 = $admDate->format("d-m-Y");

    $disDate= new DateTime($row['Pataientdrugstatement_date']);
    $disDate2 = $disDate->format("d-m-Y");

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$i",$row['pid']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$i",$row['order_date']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
	
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$i",$row['order_time']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
	
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$i","");
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$i",$row['item_id']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F$i",$row['item_desc']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$i",$row['qty']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H$i",$row['price']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I$i",$row['total']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("J$i",$runTotal);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
    
    
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

$objPHPExcel->getActiveSheet()->setTitle('Pataientdrugstatement');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save(str_replace('php','xlsx',$root_path."docs/Pataientdrugstatement.xlsx"));

echo "Created file : ".str_replace('php','xlsx',$root_path."docs/Pataientdrugstatement.xlsx" ),EOL;

$objReader=PHPExcel_IOFactory::load($root_path."docs/Pataientdrugstatement.xlsx");

?>
<script>
    window.open('../../../docs/Pataientdrugstatement.xlsx', "Pataientdrugstatement",
        "menubar=no,toolbar=no,width=500,height=300,location=yes,resizable=yes,scrollbars=yes,status=yes");
</script>
