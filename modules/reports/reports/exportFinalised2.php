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

$worksheet=$workbook->addWorksheet('Finalised Invoices');

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

$worksheet->write(1, 1, 'Finalised Invoices', $formatHeader);

// Create the header for the data starting @ row 4
$indexCol = 0;
$indexRow = 4;
$worksheet->write($indexRow, $indexCol++, 'Pid', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'File No', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Names', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Admission Date', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Discharge Date', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'BillNumber', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Ward', $formatReportHeader);

$indexRow++;   // Advance to the next row
$indexCol = 0; // Start @ column 0


$date1 = new DateTime($_REQUEST[startDate]);
$startDate = $date1->format("Y-m-d");

$date2 = new DateTime($_REQUEST[endDate]);
$endDate = $date2->format("Y-m-d");


$sql = "SELECT b.pid,p.`selian_pid` AS fileNo,b.encounter_nr,e.`encounter_class_nr`,CONCAT(p.`name_first`,' ',p.name_last,' ',p.`name_2`) AS `names`,bill_number
,e.`is_discharged`,e.`encounter_date` AS admissionDate,e.`discharge_date`,finalised,w.`description`
FROM care_ke_billing b LEFT JOIN care_person p ON b.`pid`=p.`pid`  
LEFT JOIN care_encounter e ON b.`encounter_nr`=e.`encounter_nr`
LEFT JOIN care_ward w ON e.current_ward_nr=w.nr
WHERE finalised=1 AND e.`encounter_class_nr`=1 AND e.`is_discharged`=1 ";

if($startDate && $endDate){

    $sql=$sql." and e.discharge_date between '$startDate' and '$endDate'";
}

$sql=$sql." GROUP BY encounter_nr";

if($debug) echo $sql;
$result=$db->Execute($sql);
$total=$result->RecordCount();
    $result=$db->Execute($sql);
    
    while($row=$result->FetchRow()){
        $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['pid'],
            $formatData);
        
         $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['fileNo'],
            $formatData);

        $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['names'],
            $formatData);

         $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['admissionDate'],
            $formatData);
        
         $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['discharge_date'],
            $formatData);
         
         $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['description'],
            $formatData);

         // Advance to the next row
        $indexCol=0;
        $indexRow++;
        
    }
    
    // Sends HTTP headers for the Excel file.
$workbook->send('FinalisedInvoices'.date('Hms').'.xls');
    
// Calls finalization methods.
// This method should always be the last one to be called on every workbook
$workbook->close();
?>
