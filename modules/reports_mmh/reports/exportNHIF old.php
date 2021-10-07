<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');
require_once 'spreadsheet/Excel/Writer.php';
// Lets define some custom colors codes
define('CUSTOM_DARK_BLUE', 20);
define('CUSTOM_BLUE', 21);
define('CUSTOM_LIGHT_BLUE', 22);
define('CUSTOM_YELLOW', 23);
define('CUSTOM_GREEN', 24);

$workbook=new Spreadsheet_Excel_Writer();

$worksheet=$workbook->addWorksheet('NHIF Credits');

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

$worksheet->write(1, 1, 'NHIF Credits', $formatHeader);

// Create the header for the data starting @ row 4
$indexCol = 0;
$indexRow = 4;
$worksheet->write($indexRow, $indexCol++, 'Credit No', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'NHIF No', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'PID', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Names', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Bill Number', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Admission Date', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Discharge Date', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Bed Days', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Invoice Amount', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Total Credit', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Balance', $formatReportHeader);

$indexRow++;   // Advance to the next row
$indexCol = 0; // Start @ column 0


$date1 = new DateTime($_REQUEST[startDate]);
$startDate = $date1->format("Y-m-d");

$date2 = new DateTime($_REQUEST[endDate]);
$endDate = $date2->format("Y-m-d");
    
    $sql = "SELECT b.creditNo,b.inputDate,b.admno,b.NAMES,b.admDate,b.disDate,b.wrdDays,b.nhifNo,b.nhifDebtorNo,
	b.debtorDesc,b.invAmount,b.totalCredit,b.balance,n.bill_number
	FROM care_ke_nhifcredits b left join care_ke_billing n on b.creditno=n.batch_no";
      
     if($startDate && $endDate){

        $sql=$sql."and b.inputDate between '$startDate' and '$endDate'";
    } 
    $result=$db->Execute($sql);
    
    while($row=$result->FetchRow()){
        $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['creditNo'],
            $formatData);
        
         $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['nhifNo'],
            $formatData);

//        $worksheet->write(
//            $indexRow,
//            $indexCol++,
//            $row['inputDate'],
//            $formatData);
        
         $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['admno'],
            $formatData);
        
         $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['NAMES'],
            $formatData);
         
         $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['bill_number'],
            $formatData);
         
         $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['admDate'],
            $formatData);
         
         $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['disDate'],
            $formatData);
         
         $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['wrdDays'],
            $formatData);

        $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['invAmount'],
            $formatData);
        $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['totalCredit'],
            $formatData);
        $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['balance'],
            $formatData);

         // Advance to the next row
        $indexCol=0;
        $indexRow++;
        
    }
    
    // Sends HTTP headers for the Excel file.
$workbook->send('NHIF Credits'.date('Hms').'.xls');
    
// Calls finalization methods.
// This method should always be the last one to be called on every workbook
$workbook->close();
?>
