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

$worksheet = $workbook->addWorksheet('Issues to patients');

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

$worksheet->write(1, 1, 'Issues to Patients', $formatHeader);

$indexRow = 4;
$indexCol = 0; // Start @ column 0

    $rstatus=$_REQUEST['issueStat'];
    $issLoc=$_REQUEST['strLoc2'];
    $orddt1=$_REQUEST['date1'];
    $orddt2=$_REQUEST['date1'];
    $pid1=$_REQUEST['pid1'];
    $pid2=$_REQUEST['pid2'];
    $issue1=$_REQUEST['issue1'];
    $issue2=$_REQUEST['issue2'];
    
   
//Order No Prescription No Order Date OP No Patient Name Stock Code Description Unit Cost 
//Qty Prescribed Qty Issued Pending qty Total Amount 	Issuing Store 	Issued By
// Create the header for the data starting @ row 4
    $worksheet->write($indexRow, $indexCol++, 'Order No', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Prescription No', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Order Date', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'PID', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Name', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Part Code', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Description', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Unit Cost', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Qty Prescribed', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Qty Issued', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Pending qty', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Total Amount', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Issuing Store', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Prescribed By', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Issued By', $formatReportHeader);


    $indexRow++;   // Advance to the next row
    $indexCol = 0; // Start @ column 0



      $sql = 'SELECT  o.order_no , o.presc_nr , o.status , o.order_date , o.order_time , o.store_loc , o.store_desc ,
                o.OP_no , o.patient_name , o.item_id , o.Item_desc , o.qty ,  o.price , o.unit_cost , o.orign_qty 
                , o.balance , o.input_user , p.prescriber,o.total
                FROM  care_ke_internal_orders o 
                left join care_encounter_prescription p on o.presc_nr=p.nr
                where ';
    
    if($rstatus){
        $sql=$sql.' o.status="'.$rstatus.'"';
    }else{
         $sql=$sql.' o.status="issued"';
    }
    
    if($pid1<>'' && $pid2==''){
        $sql=$sql.' and o.OP_no="'.$pid1.'"';
    }elseif($pid2<>'' && $pid1==''){
         $sql=$sql.' and o.OP_no="'.$pid2.'"';
    }elseif($pid1<>"" && $pid2<>""){
        $sql=$sql.' and o.OP_no in ('.$pid1.','.$pid2.')';
    }else{
         $sql=$sql.'';
    }
    
     $dt1=new DateTime($orddt1);
     $dto1=$dt1->format('Y-m-d');
      $dt2=new DateTime($orddt12);
     $dto2=$dt2->format('Y-m-d');
             
    if($dto1<>'' && $dto2==''){
        $sql=$sql.' and o.order_date="'.$dto1.'"';
    }else if($dto1<>'' && $dto2==''){
         $sql=$sql.' and o.order_date="'.$dto2.'"';
    }else if($dto2<>"" && $dto1<>""){
        $sql=$sql.' and o.order_date between "'.$dto1.'" and "'.$dto2.'"';
    }else{
         $sql=$sql.'';
    }
    
    $result3 = $db->Execute($sql);
    while ($row = $result3->FetchRow()) {
        $worksheet->write($indexRow, $indexCol++, $row['order_no'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['presc_nr'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['order_date'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['OP_no'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['patient_name'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['item_id'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['Item_desc'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['price'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['orign_qty'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['qty'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['balance'], $formatData);
        $worksheet->write($indexRow, $indexCol++, intval($row[orign_qty]*$row[price]), $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['store_desc'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['prescriber'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['input_user'], $formatData);
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
$workbook->send('Issues to patients ' . date('Hms') . '.xls');

// Calls finalization methods.
// This method should always be the last one to be called on every workbook
$workbook->close();
?>
