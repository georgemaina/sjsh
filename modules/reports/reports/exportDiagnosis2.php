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
echo date('H:i:s') , " Diagnosis " , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("George Maina")
    ->setLastModifiedBy("George Maina")
    ->setTitle("Diagnosis Report")
    ->setSubject("Diagnosis Report")
    ->setDescription("Diagnosis Report")
    ->setKeywords("Diagnosis Report")
    ->setCategory("Diagnosis Report");




$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'PID');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'Names' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', 'Date' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', 'Gender' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', 'Age' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', 'Status' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2', 'IP-OP' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H2', 'diagnosis code' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I2', 'Description' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J2', 'Visit' );


// Merge cells
$objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'DIAGNOSIS REPORT');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
// Set fonts
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFill()->getStartColor()->setARGB('FF808080');


$date1 = new DateTime($_REQUEST['startDate']);
$startDate = $date1->format("Y-m-d");

$date2 = new DateTime($_REQUEST['endDate']);
$endDate = $date2->format("Y-m-d");

$age1 = $_REQUEST['age1'];
    $age2 = $_REQUEST['age2'];
    $date1 = $_REQUEST['startDate'];
    $date2 = $_REQUEST['endDate'];
    $gender = $_REQUEST['gender'];
    $icd1 = $_REQUEST['icd1'];
    $icd2 = $_REQUEST['icd2'];
    $task = $_REQUEST['task'];
    $visits = $_REQUEST['visits'];
    $pid= $_REQUEST['pid'];

    $revType=$_REQUEST['revType'];
    
    $sql = "SELECT distinct d.pid,p.selian_pid,p.name_first,p.name_last,p.name_2,p.date_birth,
                p.sex,(YEAR(NOW())-YEAR(p.date_birth)) AS age,
                d.encounter_nr,d.ICD_10_code,d.ICD_10_description,d.type,d.timestamp,d.pataintstatus FROM care_tz_diagnosis d left JOIN care_person p
            ON d.PID=p.pid LEFT join care_encounter e on d.pid=e.pid";

    if ($startDate <> "" && $endDate <> "") {
        $sql = $sql . " where DATE_FORMAT(d.timestamp,'%Y-%m-%d') between '$startDate' and '$endDate' ";
    }

    // if (isset($gender) && $gender <> "") {
    //     if ($gender == 'Male') {
    //         $sex = 'M';
    //     } else if($gender == 'Female') {
    //         $sex = 'F';
    //     }
    //     $sql = $sql . " and sex='$sex'";
    // }

    if ($icd1 <> "") {
        $sql = $sql . " and ICD_10_code ='$icd1";
    }

    if (isset($age1) && $age2 <> "") {
        $sql = $sql . " having (YEAR(NOW())-YEAR(p.date_birth)) between '$age1' and '$age2'";
    } else if ($age1 <> "" && $age2 == "") {
        $sql = $sql . " having (YEAR(NOW())-YEAR(p.date_birth))='$age1'";
    }

    if ($pid <> "") {
        $sql = $sql . " and d.pid='$pid'";
    }

    if($status<>""){
        if($status=="Dead"){
            $dStat='D';
        }else{
            $dStat='A';
        }
        $sql=$sql." and pataintstatus='$dStat'";
    }

    // if($visits){
    //     $sql=$sql." and `type`='$status";
    // }
    
  //if($debug) 
	   echo $sql;
    //p.pid,p.name_first,p.name_last,p.name_2,b.bill_date,b.bill_number,b.total

    $request = $db->Execute($sql);
$i=3;
while($row=$request->FetchRow()){

    $bal= $row['bill']-$row['payment'];

    $admDate= new DateTime($row['encounter_date']);
    $admDate2 = $admDate->format("d-m-Y");

    $disDate= new DateTime($row['Diagnosis_date']);
    $disDate2 = $disDate->format("d-m-Y");

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$i",$row['pid']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$i",(trim ($row['name_first']) . ' ' . trim($row['name_last']) . ' ' . trim($row['name_2'])));
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
	
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$i",$row['timestamp']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
	
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$i",$row['sex']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$i",$row['age']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F$i",$row['pataintstatus']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$i",$row['encounter_class_nr']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H$i",$row['ICD_10_code']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I$i",$row['ICD_10_description']);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J$i",$row['type']);
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

$objPHPExcel->getActiveSheet()->setTitle('Diagnosis');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save(str_replace('php','xlsx',$root_path."docs/Diagnosis.xlsx"));

echo "Created file : ".str_replace('php','xlsx',$root_path."docs/Diagnosis.xlsx" ),EOL;

$objReader=PHPExcel_IOFactory::load($root_path."docs/Diagnosis.xlsx");

?>
<script>
    window.open('../../../docs/Diagnosis.xlsx', "Diagnosis Report",
        "menubar=no,toolbar=no,width=500,height=300,location=yes,resizable=yes,scrollbars=yes,status=yes");
</script>
