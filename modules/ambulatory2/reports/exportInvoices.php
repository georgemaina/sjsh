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

$worksheet = $workbook->addWorksheet('Debtors Invoices');

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
$worksheet->setColumn(2, 2, 30); // Email, set the width to 15


$worksheet->write(1, 1, 'Debtors Invoices', $formatHeader);

$indexRow = 4;
$indexCol = 0; // Start @ column 0

$accno = $_REQUEST[acc1];
$date1 = $_REQUEST["date1"];
$date2 = $_REQUEST["date2"];
if ($date1) {
    $date = new DateTime($date1);
    $dt1 = $date->format("Y-m-d");
} else {
    $dt1 = "";
}

if ($date2) {
    $date = new DateTime($date2);
    $dt2 = $date->format("Y-m-d");
} else {
    $dt2 = "";
}

$sql3 = "SELECT b.`ip-op`, p.pid,p.name_first,p.name_last,p.name_2,b.bill_date,b.bill_number,SUM(b.total) AS total,
            e.encounter_date,e.discharge_date,
        p.addr_zip,p.phone_1_nr,p.date_birth,p.citizenship  FROM  care_ke_billing b INNER JOIN care_person p 
            ON b.pid=p.pid INNER JOIN care_encounter e ON e.pid=p.pid
            INNER JOIN care_tz_company c ON c.id=p.insurance_ID
            INNER JOIN care_ke_debtors d ON c.accno=d.accno
            WHERE c.accno='$accno'";

        if ($dt1 <> "" && $dt2 <> "") {
            $sql3 = $sql3 . " and b.bill_date between '$dt1' and '$dt2' ";
        } else if ($dt1 <> '' && $dt2 == '') {
            $sql3 = $sql3 . " and b.bill_date = '$dt1'";
        } else {
            $sql3 = $sql3 . " and b.bill_date<=now()";
        }
        $sql3=$sql3. " GROUP BY b.bill_number ORDER BY b.bill_date asc";

$results = $db->Execute($sql3);

while ($row = $results->FetchRow()) {
    $q = $row[1];
    $indexRow++;   // Advance to the next row
    $indexCol = 0; // Start @ column 0
$bill_number=$row['bill_number'];

      $sql = "SELECT distinct
    care_person.pid
    , care_person.name_first
    , care_person.name_2
    , care_person.name_last
    , care_person.date_birth
    , care_person.addr_zip
    , care_person.cellphone_1_nr
    , care_person.citizenship
    , care_encounter.`encounter_class_nr`
FROM
    care_encounter
    INNER JOIN care_person
        ON (care_encounter.pid = care_person.pid)
WHERE (care_encounter.`encounter_class_nr`='2' and care_encounter.pid='" . $q . "')";

    $result = $db->Execute($sql);

    if ($row = $result->FetchRow()) {
          $worksheet->write($indexRow, $indexCol++, 'Patient Name', $formatPNames);
          $worksheet->write($indexRow++, $indexCol++, $row['name_first'] . " " . $row['name_2'] . " " . $row['name_last'] , $formatData);
          $indexCol = 0; 
          $worksheet->write($indexRow, $indexCol++, 'PID', $formatPNames);
          $worksheet->write($indexRow++, $indexCol++, $row['pid'] , $formatData);
          $indexCol = 0; 
          $worksheet->write($indexRow, $indexCol++, 'BILL NUMBER', $formatPNames);
          $worksheet->write($indexRow++, $indexCol++, $bill_number , $formatData);
          
    } 
    $indexCol = 0; 
    //
// Create the header for the data starting @ row 4
    $worksheet->write($indexRow, $indexCol++, 'Date', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Type', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Description', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Price', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Qty', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Total', $formatReportHeader);


    $indexRow++;   // Advance to the next row
    $indexCol = 0; // Start @ column 0



    $sql3 = "SELECT * FROM care_ke_billing WHERE pid = '$q' and `Ip-op`=2 and 
            service_type NOT IN ('payment','payment adjustment') and bill_number='$bill_number' order by bill_date asc";

    $result3 = $db->Execute($sql3);
    while ($row = $result3->FetchRow()) {
        $worksheet->write(
                $indexRow, $indexCol++, $row['bill_date'], $formatData);
        $worksheet->write(
                $indexRow, $indexCol++, $row['service_type'], $formatData);
        $worksheet->write(
                $indexRow, $indexCol++, $row['Description'], $formatData);
        $worksheet->write(
                $indexRow, $indexCol++, $row['price'], $formatData);
        $worksheet->write(
                $indexRow, $indexCol++, $row['qty'], $formatData);
        $worksheet->write(
                $indexRow, $indexCol++, $row['total'], $formatData);

        // Advance to the next row
        $indexCol = 0;
        $indexRow++;
    }
    $sql2 = "SELECT sum(total) as total FROM care_ke_billing WHERE pid = '$q' and `IP-OP`=2 and 
    service_type NOT IN ('payment','payment adjustment') and bill_number='$bill_number'";

    $result2 = $db->Execute($sql2);
    if ($row2 = $result2->FetchRow()) {
        $worksheet->write($indexRow, $indexCol=$indexCol+4, 'Total', $formatTotals);
        $worksheet->write($indexRow, $indexCol=$indexCol+1,  $row2['total'], $formatTotals);
    }
    $indexCol = 0;
    $indexRow=$indexRow+3;
}
// Sends HTTP headers for the Excel file.
$workbook->send('Debtors Invoices ' . date('Hms') . '.xls');

// Calls finalization methods.
// This method should always be the last one to be called on every workbook
$workbook->close();
?>
