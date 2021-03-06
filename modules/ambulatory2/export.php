<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
// Change error reporting for compatibility
// Spreadsheet Excel Writter was built using PHP4,
// so there's a lot of DEPRECATED notices
error_reporting(E_ERROR | E_WARNING | E_PARSE);

/**
 * PEAR package
 *
 * @link http://pear.php.net/package/Spreadsheet_Excel_Writer
 * @see PEAR/Spreadsheet/Excel/Writer.php
 */
require_once 'Spreadsheet/Excel/Writer.php';

// Lets define some custom colors codes
define('CUSTOM_DARK_BLUE', 20);
define('CUSTOM_BLUE', 21);
define('CUSTOM_LIGHT_BLUE', 22);
define('CUSTOM_YELLOW', 23);
define('CUSTOM_GREEN', 24);

// First, we create a Workbook
$workbook = new Spreadsheet_Excel_Writer();

// Add one sheet, called: Users Report
$worksheet = &$workbook->addWorksheet('Users Report');

// Create the custom colors on our new workbook
// This function takes 4 params:
//    - Code index [1 to 64]
//    - RGB colors (0-255)
$workbook->setCustomColor(CUSTOM_DARK_BLUE, 31, 73, 125);
$workbook->setCustomColor(CUSTOM_BLUE, 0, 112, 192);
$workbook->setCustomColor(CUSTOM_LIGHT_BLUE, 184, 204, 228);
$workbook->setCustomColor(CUSTOM_YELLOW, 255, 192, 0);
$workbook->setCustomColor(CUSTOM_GREEN, 0, 176, 80);

// Lets hide gridlines
$worksheet->hideScreenGridlines();

// Lets create some custom styles
$formatHeader = &$workbook->addFormat();
$formatHeader =
    &$workbook->addFormat(
        array('Size'    => 16,
              'VAlign'  => 'vcenter',
              'HAlign'  => 'center',
              'Bold'    => 1,
              'Color'   => 'white',
              'FgColor' => CUSTOM_DARK_BLUE));

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
            'HAlign' => 'center',
            'VAlign' => 'vcenter'));

/**
 * First, format the worksheet, adding the headers
 * and row/columns custom sizes
 */

// Create a nice header with a dark blue background
// The function setRow takes 3 parameters:
//    - row index
//    - row height
//    - Format to apply to row [Optional]
$worksheet->setRow(0, 11, $formatHeader);
$worksheet->setRow(1, 46, $formatHeader);
$worksheet->setRow(2, 11, $formatHeader);

// Set the size of the columns
// The function setColumn takes 5 params:
//     - First column
//     - Last column
//     - Column Width
//     - Format [Optional, default = 0]
//     - Hidden [Optional, default = 0]
$worksheet->setColumn(0, 0, 7); // User Id, shrink it to 7
$worksheet->setColumn(1, 1, 12); // Name, set the width to 12
$worksheet->setColumn(1, 1, 15); // Email, set the width to 15

/**
 *
 * Once we have the format ready, add the text to the spreadsheet
 *
 */
// Write a text header
$worksheet->write(1, 1, 'Users report', $formatHeader);

// Create the header for the data starting @ row 4
$indexCol = 0;
$indexRow = 4;
$worksheet->write($indexRow, $indexCol++, 'User Id', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Name', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Email', $formatReportHeader);

$indexRow++;   // Advance to the next row
$indexCol = 0; // Start @ column 0

// Print the report data
if(count($this->users) == 0) {
    // No data
    $worksheet->write(
        $indexRow,
        $indexCol,
        'No data to display',
        $formatData);

} else {
    // Write the data
    foreach ($this->users as $userId => $user) {
        $worksheet->writeNumber(
            $indexRow,
            $indexCol++,
            $userId,
            $formatData);

        $worksheet->write(
            $indexRow,
            $indexCol++,
            $user['name'],
            $formatData);

        $worksheet->write(
            $indexRow,
            $indexCol++,
            $user['email'],
            $formatData);

        // Advance to the next row
        $indexRow++;
    }
}

/**
 *
 * Response with the excel file
 *
 */

// Sends HTTP headers for the Excel file.
$workbook->send('report.xls');

// Calls finalization methods.
// This method should always be the last one to be called on every workbook
$workbook->close();
?>
