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

$workbook = new Spreadsheet_Excel_Writer();

$worksheet = $workbook->addWorksheet('Drugs Statement');

$workbook->setCustomColor(CUSTOM_DARK_BLUE, 31, 73, 125);
$workbook->setCustomColor(CUSTOM_BLUE, 0, 112, 192);
$workbook->setCustomColor(CUSTOM_LIGHT_BLUE, 184, 204, 228);
$workbook->setCustomColor(CUSTOM_YELLOW, 255, 192, 0);
$workbook->setCustomColor(CUSTOM_GREEN, 0, 176, 80);

//$worksheet->hideScreenGridlines();
//custom styles
$formatHeader = &$workbook->addFormat();
$formatHeader = &$workbook->addFormat(
                array('Size' => 16,
                    'VAlign' => 'vcenter',
                    'HAlign' => 'center',
                    'Bold' => 1,
                    'Color' => 'white',
                    'FgColor' => CUSTOM_DARK_BLUE));

$formatReportHeader =
        &$workbook->addFormat(
                array('Size' => 9,
                    'VAlign' => 'bottom',
                    'HAlign' => 'center',
                    'Bold' => 1,
                    'FgColor' => CUSTOM_LIGHT_BLUE,
                    'TextWrap' => true));

$formatData =
        &$workbook->addFormat(
                array(
                    'Size' => 8,
                    'HAlign' => 'left',
                    'VAlign' => 'vcenter'));

$formatPNames =
        &$workbook->addFormat(
                array(
                    'Size' => 9,
                    'Bold' => 1,
                    'HAlign' => 'left',
                    'VAlign' => 'vcenter'));
$formatTotals =
        &$workbook->addFormat(
                array(
                    'Size' => 10,
                    'Bold' => 1,
                    'HAlign' => 'left',
                    'VAlign' => 'vcenter'));

$worksheet->setRow(0, 11, $formatHeader);
$worksheet->setRow(1, 46, $formatHeader);
$worksheet->setRow(2, 11, $formatHeader);

$worksheet->setColumn(0, 0, 12); // User Id, shrink it to 7
$worksheet->setColumn(1, 1, 15); // Name, set the width to 12
$worksheet->setColumn(4, 4, 30); // Email, set the width to 15
$worksheet->setColumn(6, 6, 30); // Email, set the width to 15

$date1 = $_REQUEST[date1];
    $date2 = $_REQUEST[date2];
    $pid = $_REQUEST[pid];
    
$inputUser = $_REQUEST['inputUser'];

$worksheet->write(1, 1, 'Patient Drugs Statement' . $pid, $formatHeader);

$indexRow = 4;
$indexCol = 0; // Start @ column 0
//Order No Prescription No Order Date OP No Patient Name Stock Code Description Unit Cost 
//Qty Prescribed Qty Issued Pending qty Total Amount 	Issuing Store 	Issued By
// Create the header for the data starting @ row 4
$worksheet->write($indexRow, $indexCol++, 'PID', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Names', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Date', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Admission', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Item Number', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Description', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Quantity', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Price', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Total', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Running Total', $formatReportHeader);


$indexRow++;   // Advance to the next row
$indexCol = 0; // Start @ column 0


$sql = "SELECT p.pid,p.name_first,p.name_last,p.name_2,b.bill_date,b.`IP-OP`,b.partcode,b.service_type,b.Description,
  b.price,b.total AS Total,b.qty AS drug_Count FROM care_ke_billing b LEFT JOIN care_person p ON (b.pid = p.pid)
  WHERE b.service_type = 'drug_list' ";

if (isset($pid) && $pid <> '') {
    $sql.=" and b.pid=$pid";
}

if (isset($date1) && isset($date2) && $date1 <> "" && $date1 <> "") {
    $date = new DateTime($date1);
    $dt1 = $date->format("Y-m-d");

    $date = new DateTime($date2);
    $dt2 = $date->format("Y-m-d");

    $sql = $sql . " and b.bill_date between '$dt1' and '$dt2' ";
} else {
    $sql = $sql . " and b.bill_date<=now()";
}

$sql = $sql . " order by b.bill_date desc";

//echo $sql;

$request3 = $db->Execute($sql);


while ($row = $request3->FetchRow()) {
    $worksheet->write($indexRow, $indexCol++, $row['pid'], $formatData);
    $worksheet->write($indexRow, $indexCol++, $row['name_first'].' '.$row['name_last'].' '.$row['name_2'], $formatData);
    $worksheet->write($indexRow, $indexCol++, $row['bill_date'], $formatData);
    $worksheet->write($indexRow, $indexCol++, $row['IP-OP'], $formatData);
    $worksheet->write($indexRow, $indexCol++, $row['partcode'], $formatData);
    $worksheet->write($indexRow, $indexCol++, $row['Description'], $formatData);
    $worksheet->write($indexRow, $indexCol++, $row['drug_Count'], $formatData);
    $worksheet->write($indexRow, $indexCol++, $row['price'], $formatData);
    $worksheet->write($indexRow, $indexCol++, $row['Total'], $formatData);

    $lsum = $lsum + $row['Total'];
    $worksheet->write($indexRow, $indexCol++, $lsum, $formatData);
    $rowbg = 'white';
    $indexCol = 0;
    $indexRow++;
}

$workbook->send('Drugs Statement ' . date('Hms') . '.xls');

// Calls finalization methods.
// This method should always be the last one to be called on every workbook
$workbook->close();
?>
