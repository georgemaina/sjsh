<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');
require ($root_path . 'include/care_api_classes/class_lab.php');

$dt1 = new DateTime(date($_REQUEST[date2]));
$date1 = $dt1->format('Y-m-d');

$dt2 = new DateTime(date($_REQUEST[date1]));
$date2 = $dt2->format('Y-m-d');

function getSummobidityPerDay($date1,$date2,$reportDay,$reportType){
    global $db;
    $debug=false;

    if($reportDay<10) $reportDay='0'.$reportDay;

    $sql="Select Count(b.`diagnosis_code`) as totalCases from  care_icd10_en b left join care_tz_diagnosis c
            on b.diagnosis_code=c.ICD_10_code where c.timestamp between '$date2' and '$date1'
            and DATE_FORMAT(c.timestamp,'%d')='$reportDay' AND b.class_sub='$reportType'";

    $results=$db->Execute($sql);
    $row=$results->FetchRow();

    return $row[0];
}

function getMobidityCounts($rcode,$reportType,$date1,$date2,$reportDay){
    global $db;

    if($reportDay<10) $reportDay='0'.$reportDay;

    $sql1 = "select b.diagnosis_code as rCode,b.description as Disease,day(c.timestamp) as rday,COUNT(b.`diagnosis_code`) AS rcount
        from care_icd10_en b left join care_tz_diagnosis c
            on b.diagnosis_code=c.ICD_10_code where b.type='$reportType' and c.timestamp between '$date2' and '$date1'
            and DATE_FORMAT(c.timestamp,'%d')='$reportDay' and b.diagnosis_code='$rcode' group by b.diagnosis_code";


    //echo $sql1;
    $result1 = $db->Execute($sql1);
    $row=$result1->FetchRow();

    if($row[rcount]<>''){
        return $row[rcount];
    }else{
        return '0';
    }

}


require_once 'spreadsheet/Excel/Writer.php';
// Lets define some custom colors codes
define('CUSTOM_DARK_BLUE', 20);
define('CUSTOM_BLUE', 21);
define('CUSTOM_LIGHT_BLUE', 22);
define('CUSTOM_YELLOW', 23);
define('CUSTOM_GREEN', 24);

$workbook=new Spreadsheet_Excel_Writer();
$labObj=new lab();

$worksheet=$workbook->addWorksheet('Diagnosis Report');

$workbook->setCustomColor(CUSTOM_DARK_BLUE, 31, 73, 125);
$workbook->setCustomColor(CUSTOM_BLUE, 0, 112, 192);
$workbook->setCustomColor(CUSTOM_LIGHT_BLUE, 184, 204, 228);
$workbook->setCustomColor(CUSTOM_YELLOW, 255, 192, 0);
$workbook->setCustomColor(CUSTOM_GREEN, 0, 176, 80);

//$worksheet->hideScreenGridlines();

//custom styles
$formatHeader=&$workbook->addFormat();
$formatHeader=&$workbook->addFormat(
        array('Size'=>16,
              'VAlign'=>'vcenter',
              'HAlign'=>'center',
              'Bold'=>1,
              'Color'=>'white',
              'FgColor'=>CUSTOM_DARK_BLUE));

$formatReportHeader =
    &$workbook->addFormat(
        array('Size'     => 9,
              'VAlign'   => 'bottom',
              'HAlign'   => 'center',
              'Bold'     => 1,
              'FgColor'  => CUSTOM_LIGHT_BLUE,
              'TextWrap' => true));

$formatData =
    &$workbook->addFormat(
        array(
            'Size'   => 8,
            'HAlign' => 'left',
            'VAlign' => 'vcenter'));

$worksheet->setRow(0, 11, $formatHeader);
$worksheet->setRow(1, 46, $formatHeader);
$worksheet->setRow(2, 11, $formatHeader);

$worksheet->setColumn(0, 0, 7); // User Id, shrink it to 7
$worksheet->setColumn(1, 1, 25); // Name, set the width to 12


$worksheet->write(1, 1, 'OP Mobidity Report', $formatHeader);

// Create the header for the data starting @ row 4
$indexCol = 0;
$indexRow = 4;
$worksheet->write($indexRow, $indexCol++, 'ICD CODE', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'DESCRIPTION', $formatReportHeader);

for ($i = 1; $i <= 31; $i++) {
    $worksheet->write($indexRow, $indexCol++, $i , $formatReportHeader);
}

$worksheet->write($indexRow, $indexCol++, 'TOTALS', $formatReportHeader);


$indexRow++;   // Advance to the next row
$indexCol = 0; // Start @ column 0

$reportMonth=$_REQUEST[reportMonth];
$reportType=$_REQUEST[reportType];


$sql="select diagnosis_code,Description from care_icd10_en where type='$reportType' order by diagnosis_code asc";
//echo $sql;
$results=$db->Execute($sql);

while($row = $results->FetchRow()){

    $worksheet->write(
        $indexRow,
        $indexCol++,
        $row['diagnosis_code'],
        $formatData);

    $worksheet->write(
        $indexRow,
        $indexCol++,
        $row['Description'],
        $formatData);

    $totals=0;
    for ($i = 1; $i <= 31; $i++) {
        $rcount= getMobidityCounts($row[0],$reportType,$date1,$date2,$i);

        if($row[0]=='OP63' ||$row[0]=='OPC62'){
            $rcount=getSummobidityPerDay($date1,$date2,$i,$reportType);
        }

        $worksheet->setColumn(1, $i, 3); // Email, set the width to 15
        $worksheet->write(
            $indexRow,
            $indexCol++,
            $rcount,
            $formatData);
        $totals=$totals+$rcount;
    }

    $worksheet->write(
        $indexRow,
        $indexCol++,
        $totals,
        $formatData);

    $indexCol=0;
    $indexRow++;
}


    

    
    // Sends HTTP headers for the Excel file.
$workbook->send('OPMobidityReport '.date('Hms').'.xls');
    
// Calls finalization methods.
// This method should always be the last one to be called on every workbook
$workbook->close();
?>
