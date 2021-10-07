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

$worksheet = $workbook->addWorksheet('Debtors Statement');

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

$inputUser=$_REQUEST['inputUser'];

$worksheet->write(1, 1, 'DEBTORS REPORT ', $formatHeader);

$indexRow = 4;
$indexCol = 0; // Start @ column 0

 
    
   
//Order No Prescription No Order Date OP No Patient Name Stock Code Description Unit Cost 
//Qty Prescribed Qty Issued Pending qty Total Amount 	Issuing Store 	Issued By
// Create the header for the data starting @ row 4
    $worksheet->write($indexRow, $indexCol++, 'Acc No', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Trans No', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'PID', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'EncounterNo', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Patient Name', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Date', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Bill No', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Amount', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Type', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Allocated', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Allocated Amount', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Invoice Balance', $formatReportHeader);

    $indexRow++;   // Advance to the next row
    $indexCol = 0; // Start @ column 0


$searchParam = $_REQUEST[searchParam];
$showAllocated=$_REQUEST[showAllocated];

$accno=$_REQUEST[accNo];

$sql = "SELECT d.accno,d.transno,d.pid,d.`encounter_nr`,p.`name_first`,p.`name_2`,p.`name_last`
            ,d.`lastTransDate`,d.`bill_number`, d.`amount`,e.`encounter_date`,e.`discharge_date`,e.`encounter_class_nr`,
            p.`addr_zip`,p.`phone_1_nr`,p.`cellphone_1_nr`,p.`citizenship`,p.`email`,c.`name` AS cname,d.transdate,d.transtype,
            d.`allocated`,d.`allocatedAmount`,d.`invoiceBalance`
            FROM care_ke_debtortrans d
            LEFT JOIN care_person p ON d.`pid`=p.`pid`
            LEFT JOIN care_ke_debtors c ON d.`accno`=c.`accno`
	      LEFT JOIN care_encounter e ON d.`encounter_nr`=e.`encounter_nr` WHERE  d.accno<>''";


            $sql2 = "select count(d.pid) as countp from care_ke_debtortrans d left join care_person p on d.pid=p.pid where d.accno<>''";

            if($showAllocated=='false'){
                $sql.=" and d.allocated=0";
                $sql2.=" and d.allocated=0";
            }

            if (isset($accno)) {
                $sql.=" and d.accno='$accno'";
                $sql2.=" and d.accno='$accno'";
            }

            if ($_REQUEST[strDate1] <> '' && $_REQUEST[strDate2] <> '') {
                $startd = new DateTime($_REQUEST[strDate1]);
                $startDate = $startd->format('Y-m-d');

                $end = new DateTime($_REQUEST[strDate2]);
                $endDate = $end->format('Y-m-d');
                if ($startDate > '2000-01-01' && $endDate > '2000-01-01') {
                    $sql.=" and d.lastTransDate between '$startDate' and '$endDate'";
                    $sql2.=" and d.lastTransDate between '$startDate' and '$endDate'";
                }
            }
            //
            if (isset($searchParam)) {
                $sql.=" and d.pid=$searchParam or p.name_first like'%$searchParam%' or d.name_2 like'%$searchParam%' or p.name_last like'%$searchParam%'";
                $sql2.=" and d.pid=$searchParam or p.name_first like'%$searchParam%' or d.name_2 like'%$searchParam%' or p.name_last like'%$searchParam%'";
            }

//            $sql.=" limit " . $start . "," . $limit;
//  echo $sql;

    $request3=$db->Execute($sql);
    
    while ($row = $request3->FetchRow()) {
        $worksheet->write($indexRow, $indexCol++, $row['accno'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['transno'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['pid'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['encounter_nr'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['name_first'].' '.$row['name_2'].' '.$row['name_last'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['transdate'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['bill_number'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['amount'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['transtype'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['allocated'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['allocatedamount'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['invoicebalance'], $formatData);
        // Advance to the next row
        $indexCol = 0;
        $indexRow++;
    }
//    $sql2 = "SELECT sum(total) as total FROM care_ke_billing WHERE pid = '$q' and `IP-OP`=2 and 
//    service_type NOT IN ('payment','payment adjustment') and bill_number='$bill_number'";
//
//    $result2 = $db->Execute($sql2);
//    if ($row2 = $result2->FetchRow()) {
//        $worksheet->write($indexRow, $indexCol=$indexCol+4, 'Total', $formatTotals);
//        $worksheet->write($indexRow, $indexCol=$indexCol+1,  $row2['total'], $formatTotals);
//    }
//    $indexCol = 0;
//    $indexRow=$indexRow+3;

// Sends HTTP headers for the Excel file.
$workbook->send('Debtors Statement' . date('Hms') . '.xls');

// Calls finalization methods.
// This method should always be the last one to be called on every workbook
$workbook->close();
?>
