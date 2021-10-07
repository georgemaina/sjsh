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
require($root_path.'../ExcelClasses/PHPExcel.php');


// Create new PHPExcel object
echo date('H:i:s') , " Create new OP Mobidity" , EOL;
$objPHPExcel = new PHPExcel();


$dt1 = new DateTime(date($_REQUEST[date2]));
$date1 = $dt1->format('Y-m-d');

$dt2 = new DateTime(date($_REQUEST[date1]));
$date2 = $dt2->format('Y-m-d');

// Set document properties
echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("George Maina")
    ->setLastModifiedBy("George Maina")
    ->setTitle("OP Mobidity")
    ->setSubject("OP Mobidity")
    ->setDescription("OP Mobidity")
    ->setKeywords("OP Mobidity php")
    ->setCategory("OP Mobidity");

$objPHPExcel->getActiveSheet(0)->mergeCells('A1:F1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'OP Mobidity');


$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'ICD CODE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'DESCRIPTION' );

function getSummobidityPerDay($date1,$date2,$reportDay,$reportType){
    global $db;
    $debug=false;

    if($reportDay<10) $reportDay='0'.$reportDay;

    $sql="Select Count(b.`diagnosis_code`) as totalCases from  care_icd10_en b left join care_tz_diagnosis c
            on b.diagnosis_code=c.ICD_10_code where c.timestamp between '$date2' and '$date1'
            and DATE_FORMAT(c.timestamp,'%d')='$reportDay' AND b.class_sub='$reportType'";

    if($debug) echo $sql;

    $results=$db->Execute($sql);
    $row=$results->FetchRow();

    return $row[0];
}

function getMobidityCounts($rcode,$reportType,$date1,$date2,$reportDay){
    global $db;
    $debug=false;

    if($reportDay<10) $reportDay='0'.$reportDay;

    $sql1 = "select b.diagnosis_code as rCode,b.description as Disease,day(c.timestamp) as rday,COUNT(b.`diagnosis_code`) AS rcount
        from care_icd10_en b left join care_tz_diagnosis c
            on b.diagnosis_code=c.ICD_10_code where b.type='$reportType' and c.timestamp between '$date2' and '$date1'
            and DATE_FORMAT(c.timestamp,'%d')='$reportDay' and b.diagnosis_code='$rcode' group by b.diagnosis_code";

    if($debug) echo $sql1;
    //echo $sql1;
    $result1 = $db->Execute($sql1);
    $row=$result1->FetchRow();

    if($row[rcount]<>''){
        return $row[rcount];
    }else{
        return '0';
    }

}


$s = 'B';
for ($i = 1; $i <= 31; $i++) {
       $objPHPExcel->setActiveSheetIndex(0)->setCellValue( ++$s.'2', $i );
}
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AI2', 'TOTALS' );

$reportMonth=$_REQUEST[reportMonth];
$reportType=$_REQUEST[reportType];

$sql="select diagnosis_code,Description from care_icd10_en where type='$reportType' order by diagnosis_code asc";

$results=$db->Execute($sql);


$indexRow=3;
while($row = $results->FetchRow()){

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$indexRow,  $row['diagnosis_code'] );
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$indexRow,  $row['Description'] );

    $totals=0;
    $t='B';
    for ($i = 1; $i <= 31; $i++) {
        $rcount= getMobidityCounts($row[0],$reportType,$date1,$date2,$i);

        if($row[0]=='OP66' ||$row[0]=='OPC62'){
            $rcount=getSummobidityPerDay($date1,$date2,$i,$reportType);
        }

        $col=++$t;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($col.$indexRow,  $rcount );
    }

    $indexRow++;
}

$objPHPExcel->getActiveSheet()->setTitle('OP Mobidity');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$currTime=date('YmdHis');

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save(str_replace('php','xlsx',$root_path."/docs/OPMobidity ".$currTime.".xlsx"));

echo "Created file : ".str_replace('php','xlsx',$root_path."docs/OPMobidity ".$currTime.".xlsx" ),EOL;

$objReader=PHPExcel_IOFactory::load($root_path."docs/OPMobidity ".$currTime.".xlsx");

?>

<script>
    window.open("<?php echo $root_path.'docs/OPMobidity '.$currTime.'.xlsx'; ?>","OpMobidity Reports","menubar=yes,toolbar=yes,width=500,height=550,location=yes,resizable=no,scrollbars=yes,status=yes")

</script>

