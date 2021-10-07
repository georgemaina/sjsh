<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');
require ($root_path . 'include/care_api_classes/class_lab.php');


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
$worksheet->setColumn(1, 1, 12); // Name, set the width to 12
$worksheet->setColumn(1, 1, 15); // Email, set the width to 15

$worksheet->write(1, 1, 'Diagnosis Report', $formatHeader);

// Create the header for the data starting @ row 4
$indexCol = 0;
$indexRow = 4;
$worksheet->write($indexRow, $indexCol++, 'Group', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'ItemID', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Description', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Male', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Female', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Total', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, '<5 Yrs', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, '5-14Yrs', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, '>14 Yrs', $formatReportHeader);


$indexRow++;   // Advance to the next row
$indexCol = 0; // Start @ column 0

$date1 = new DateTime($_REQUEST[startDate]);
$startDate = $date1->format("Y-m-d");

$date2 = new DateTime($_REQUEST[endDate]);
$endDate = $date2->format("Y-m-d");


$sql="SELECT p.`group_id`,p.Item_Id,p.name AS Description FROM care_tz_laboratory_param p
            LEFT JOIN `care_test_request_chemlabor_sub` s ON p.`item_id`=s.`item_id`
            GROUP BY s.`item_id`";

    

    $result=$db->Execute($sql);
    while($row=$result->FetchRow()){

        $maleCounts=$labObj->getLabCounts($row[Item_Id],'m',$startDate,$endDate,$sign);
        $femaleCounts=$labObj->getLabCounts($row[Item_Id],'f',$startDate,$endDate,$sign);
        $total=$maleCounts+$femaleCounts;
        $below5=$labObj->getLabCounts($row[Item_Id],'',$startDate,$endDate,"<");
        $between5and14=$labObj->getLabCounts($row[Item_Id],'',$startDate,$endDate,"between");
        $above14=$labObj->getLabCounts($row[Item_Id],'',$startDate,$endDate,">");


        $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['group_id'],
            $formatData);

        $worksheet->write(
            $indexRow,
            $indexCol++,
            $row[Item_Id],
            $formatData);
        
         $worksheet->write(
            $indexRow,
            $indexCol++,
           $row[Description],
            $formatData);
        
         $worksheet->write(
            $indexRow,
            $indexCol++,
             $maleCounts,
            $formatData);
         
         $worksheet->write(
            $indexRow,
            $indexCol++,
             $femaleCounts,
            $formatData);

         $worksheet->write(
            $indexRow,
            $indexCol++,
            $total ,
            $formatData);

        $worksheet->write(
            $indexRow,
            $indexCol++,
            $below5 ,
            $formatData);

        $worksheet->write(
            $indexRow,
            $indexCol++,
            $between5and14 ,
            $formatData);

        $worksheet->write(
            $indexRow,
            $indexCol++,
            $above14 ,
            $formatData);

        $indexCol=0;
        $indexRow++;
        
    }
    
    // Sends HTTP headers for the Excel file.
$workbook->send('labs'.date('Hms').'.xls');
    
// Calls finalization methods.
// This method should always be the last one to be called on every workbook
$workbook->close();
?>
