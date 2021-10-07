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
echo date('H:i:s') , "Outpatient Clinic" , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("George Maina")
    ->setLastModifiedBy("George Maina")
    ->setTitle("Outpatient Clinic Report")
    ->setSubject("Outpatient Clinic Report")
    ->setDescription("Outpatient Clinic Report contains all ")
    ->setKeywords("Outpatient Clinic Report")
    ->setCategory("Outpatient Clinic Report");

$objPHPExcel->getActiveSheet(0)->mergeCells('A1:F1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Outpatient Clinic');


$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'Encounter Date');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'Names' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', 'Sex' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', 'Date of Birth' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', 'PID' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', 'Hospital File No' );
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2', 'Insurance' );


$detp_nr = $_GET["dept_nr"];

$dt1=new DateTime(date($_GET["startDate"]));
$startDate=$dt1->format('Y-m-d');

$dt2=new DateTime(date($_GET["endDate"]));
$endDate=$dt2->format('Y-m-d');

if(isset($startDate) and isset($endDate)){
    $param=$param.' and encounter_date between cast("'.$startDate.'" as datetime) and cast("'.$endDate.'" as datetime)';
}

if($_GET["nameSearch"]<>""){
    $name=$_GET["nameSearch"];
    $param.=' and name_first like "'.$name.'%" or name_last like "'.$name.'%" or pid='.$name.' or selian_pid='.$name;
}

$sql = "SELECT e.encounter_nr,e.encounter_class_nr, e.encounter_date, e.pid,e.insurance_class_nr,p.selian_pid,p.name_last,
        p.name_first,p.name_2,p.date_birth,p.sex, p.photo_filename, a.date, a.time,a.urgency,c.`name` AS insurance_name,p.`insurance_ID`, n.nr AS notes
        FROM care_encounter AS e LEFT JOIN care_person AS p ON e.pid=p.pid LEFT JOIN care_appointment AS a ON e.encounter_nr=a.encounter_nr
        LEFT JOIN care_tz_company c ON c.`id`=p.`insurance_ID`
        LEFT JOIN care_encounter_notes as n ON (e.encounter_nr=n.encounter_nr AND n.type_nr=6)
        LEFT JOIN care_encounter_location l ON e.`encounter_nr`=l.`encounter_nr`
        WHERE e.encounter_nr<>''  AND e.`encounter_class_nr`=2 AND l.`discharge_type_nr`<>8 ";



if($detp_nr){
    $sql=$sql." and e.current_dept_nr='$detp_nr'";
}

if(isset($startDate) || $startDate<>'' && isset($endDate) || $endDate<>''){

    $sql=$sql." and encounter_date between '$startDate' and '$endDate'";
}else{
    $sql=$sql.'';
}
$name=$_GET["nameSearch"];
if($_GET["sOptions"]=='Name'){
    $sql.=' and name_first like "'.$name.'%" or name_last like "'.$name.'%" or name_2 like "'.$name.'%"';
}else if($_GET["sOptions"]=='PID'){
    $sql.=' and p.pid='.$name ;
}else if($_GET["sOptions"]=='File_No'){
    $sql.=' and selian_pid='.$name;
}

$gender=$_GET["gender"];
if(isset($gender) && $gender<>''){
    $sql.=" and p.sex='$gender'";
}

//
$ageSign=$_GET["ageSign"];
$age=$_GET["age"];
if(isset($age) && $age<>''){
    $sql.=" and ((YEAR(NOW())-YEAR(p.date_birth)))$ageSign $age";
}

$sql.=" ORDER BY p.name_last ASC";// LIMIT $offset, $recordsPerPage;";

$result = $db->Execute($sql);
if($debug)
    echo $sql;

$i=3;
while($row=$result->FetchRow()){

    if($row[insurance_ID]=='-1'){
        $insurance='CASH PAYMENT';
    }else{
        $insurance=$row[insurance_name];
    }

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$i",$row['encounter_date']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$i",$row['name_first'] . ' ' . $row['name_2'] . ' ' . $row['name_last']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$i",$row['sex']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$i",$row['date_birth']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$i",$row['pid']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F$i",$row['selian_pid']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$i",$insurance);

    $i=$i+1;
}

$objPHPExcel->getActiveSheet()->setTitle('Outpatient Clinic');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save(str_replace('php','xlsx',$root_path."/docs/OutpatientClinic.xlsx"));

echo "Created file : ".str_replace('php','xlsx',$root_path."docs/OutpatientClinic.xlsx" ),EOL;

$objReader=PHPExcel_IOFactory::load($root_path."docs/OutpatientClinic.xlsx");

?>
<script>
    window.open('../../../docs/OutpatientClinic.xlsx', "Outpatient Clinic",
        "menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
</script>
