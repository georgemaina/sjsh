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

$worksheet = $workbook->addWorksheet('Revenue Report');

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

//$worksheet->setColumn(0, 0, 12); // User Id, shrink it to 7
//$worksheet->setColumn(1, 1, 15); // Name, set the width to 12
//$worksheet->setColumn(4, 4, 30); // Email, set the width to 15
//$worksheet->setColumn(6, 6, 30); // Email, set the width to 15

//$inputUser=$_REQUEST['inputUser'];
$date1=$_REQUEST['date1'];
$date2=$_REQUEST['date2'];

$strDate1=new DateTime($date1);
$strDate2=new DateTime($date2);
$diff=$strDate1->diff($strDate2);
//echo "The date Diff is ".$diff->days ;

$yr=$strDate1->format("Y");
$mnth=$strDate1->format("m");

$rptDays=$diff->days+1;

$worksheet->write(1, 2, 'Daily Revenue REPORT for Dates  '.$date1.' and '.$date2, $formatHeader);

$indexRow = 4;
$indexCol = 0; // Start @ column 0

$worksheet->write(2, 0, "Dates" , $formatHeader);
$indexCol=1;
for($i=1;$i<=$rptDays;$i++){
    $worksheet->write(2, $indexCol++, $i , $formatHeader);
}

$indexRow++;   // Advance to the next row
$indexCol = 0; // Start @ column 0

$counter=0;
$sql = "SELECT r.rev_code,r.rev_desc,r.pay_mode, SUM(r.total) AS total,r.prec_desc,count(*) as rNO FROM care_ke_receipts r
                    WHERE currdate BETWEEN '$date1' and '$date2' GROUP BY r.rev_desc";
if($debug)
    echo $sql;
$result = $db->Execute($sql);
$rcount=$result->RecordCount();

while ($row = $result->FetchRow($result)) {

    $worksheet->write($indexRow++, 0, $row[1] , $formatHeader);
    $monthlyTotal=0;
    for($i=1;$i<=$rptDays;$i++) {
        $strDate = $yr . '-' . $mnth . '-' . $i;
        $value=getCashTotal($strDate, $row[rev_desc]);
        $worksheet->write($indexRow, $indexCol++, $row[1] , $formatHeader);

        $monthlyTotal=$monthlyTotal+$value;
        if($i==$rptDays){

            $worksheet->write(2, $rptDays+1, $monthlyTotal , $formatHeader);
        }
    }
    $counter = $counter + 1;


//    $indexCol=0;
//    $indexRow++;
}


// Sends HTTP headers for the Excel file.
$workbook->send('RevenueReport' . date('Hms') . '.xls');

// Calls finalization methods.
// This method should always be the last one to be called on every workbook
$workbook->close();

function getCashTotal($date1,$revDesc){
    global $db;
    $debug=false;

    $sql="select sum(Total) as Total from care_ke_receipts where rev_desc='$revDesc' and currdate='$date1' group by rev_desc";
    if($debug) echo $sql;

    $results=$db->Execute($sql);
    $row=$results->FetchRow();
    $strRow=$row[0];

    return $strRow;

}

function getDailyTotals($date1){
    global $db;
    $debug=false;

    $sql="SELECT  SUM(r.total) AS total FROM care_ke_receipts r WHERE currdate='$date1'";
    if($debug) echo $sql;

    $results=$db->Execute($sql);
    $row=$results->FetchRow();

    return $row[0];

}

?>
