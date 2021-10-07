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

$worksheet = $workbook->addWorksheet('Stock Valuation');

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
$worksheet->setColumn(3, 3, 15); // Email, set the width to 15

$inputUser=$_REQUEST['inputUser'];

$worksheet->write(1, 1, 'PHARMACY STOCK VALUATION REPORT ', $formatHeader);

$indexRow = 4;
$indexCol = 0; // Start @ column 0

 
    
   
//Order No Prescription No Order Date OP No Patient Name Stock Code Description Unit Cost 
//Qty Prescribed Qty Issued Pending qty Total Amount 	Issuing Store 	Issued By
// Create the header for the data starting @ row 4
    $worksheet->write($indexRow, $indexCol++, 'PartCode', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Location', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Description', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Category', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Quantity', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Cost', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Total Cost', $formatReportHeader);

    $indexRow++;   // Advance to the next row
    $indexCol = 0; // Start @ column 0


$catID1 = $_REQUEST[catID1];
$catID2 = $_REQUEST[catID2];
$detsum = $_POST[detsum];
$storeid=$_REQUEST[storeid];
$inputUser = $_REQUEST['$inputUser'];

$accDB=$_SESSION['sess_accountingdb'];
$pharmLoc=$_SESSION['sess_pharmloc'];

$sql = "SELECT b.PartCode, k.loccode,b.Item_Description,k.Quantity,k.reorderlevel,e.item_cat,s.`LastCost`,(s.`lastcost` * k.`quantity`) AS TotalCost
                FROM care_tz_drugsandservices b LEFT JOIN care_tz_itemscat e ON b.category=e.catid LEFT JOIN care_ke_locstock k ON k.stockid=b.item_number
                LEFT JOIN $accDB.`stockmaster` s ON k.`stockid`=s.`stockid` WHERE b.category <>'' AND K.`quantity`>0 and s.lastcost>0";

if ($storeid <> '') {
    $sql.=" and k.loccode ='$storeid'";
}
// echo $sql;

$result = $db->Execute($sql);

$sql2 = 'select count(item_id) from care_tz_drugsandservices';
$result2 = $db->Execute($sql2);
$total = $result2->FetchRow();
$total = $total[0];
    
    while ($row = $result->FetchRow()) {
        $worksheet->write($indexRow, $indexCol++, $row['PartCode'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['loccode'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['Item_Description'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['item_cat'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['Quantity'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['LastCost'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['TotalCost'], $formatData);
        // Advance to the next row
        $indexCol = 0;
        $indexRow++;
    }

// Sends HTTP headers for the Excel file.
$workbook->send('Pharmacy Stock Valuation' . date('Hms') . '.xls');

// Calls finalization methods.
// This method should always be the last one to be called on every workbook
$workbook->close();
?>
