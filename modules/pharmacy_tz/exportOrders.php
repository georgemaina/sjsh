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

$worksheet = $workbook->addWorksheet('Internal Orders');

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

$rstatus=$_REQUEST['ordStatus'];
    $ordLoc=$_REQUEST['ordLoc'];
    $orddt1=$_REQUEST['orddt1'];
    $orddt2=$_REQUEST['orddt2'];  
    $inputUser=$_REQUEST['inputUser'];

$worksheet->write(1, 1, 'Internal Orders from '.$ordLoc, $formatHeader);

$indexRow = 4;
$indexCol = 0; // Start @ column 0

 
    
   
//Order No Prescription No Order Date OP No Patient Name Stock Code Description Unit Cost 
//Qty Prescribed Qty Issued Pending qty Total Amount 	Issuing Store 	Issued By
// Create the header for the data starting @ row 4
    $worksheet->write($indexRow, $indexCol++, 'Order No', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Status', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Order Date', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Part Code', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Description', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Unit Cost', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Qty Ordered', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Qty Pending', $formatReportHeader);
//    $worksheet->write($indexRow, $indexCol++, 'Total Amount', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Ordered From', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Ordered By', $formatReportHeader);


    $indexRow++;   // Advance to the next row
    $indexCol = 0; // Start @ column 0

 $rstatus=$_REQUEST['ordStatus'];
    $ordLoc=$_REQUEST['ordLoc'];
    $orddt1=$_REQUEST['orddt1'];
    $orddt2=$_REQUEST['orddt2'];
    $inputUser=$_REQUEST['inputUser'];

 
    
    $sql='SELECT r.`req_no`,r.`status`,r.`req_date`,r.`req_time`, r.`item_id`,r.`Item_desc`,
        k.unit_price,r.`qty`,IF(r.balance=0,r.qty,r.balance) AS pending_qty, 
        r.`sup_storeDesc`,r.`input_user`,s.st_name FROM `care_ke_internalreq` r 
        left JOIN care_tz_drugsandservices k ON r.item_id=k.partcode left join care_ke_stlocation s
        on r.store_loc=s.st_id
        where ';
     if($rstatus){
        $sql=$sql.' r.status="'.$rstatus.'"';
    }else{
         $sql=$sql.' r.status in ("pending","serviced","cancelled")';
    }
    
    if($ordLoc){
        $sql=$sql.' and r.store_loc="'.$ordLoc.'"';
    }else{
         $sql=$sql.'';
    }
    
      $dt1=new DateTime($orddt1);
     $dto1=$dt1->format('Y-m-d');
      $dt2=new DateTime($orddt2);
     $dto2=$dt2->format('Y-m-d');
             
    if($orddt1<>'' && $orddt2==''){
        $sql=$sql.' and req_date="'.$dto1.'"';
    }
    if($orddt1=='' && $orddt2<>''){
         $sql=$sql.' and req_date="'.$dto2.'"';
    }
    if($orddt1<>"" && $orddt2<>""){
        $sql=$sql.' and req_date between "'.$dto1.'" and "'.$dto2.'"';
    }else{
         $sql=$sql.'';
    }
    
    if( $inputUser<>'')
    {
       $sql=$sql." and r.`input_user` like '$inputUser%'";
    }    
//    echo $sql;
    $request3=$db->Execute($sql);
    
    while ($row = $request3->FetchRow()) {
        $worksheet->write($indexRow, $indexCol++, $row['req_no'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['status'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['req_date'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['item_id'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['Item_desc'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['unit_price'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['qty'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['pending_qty'], $formatData);
//        $worksheet->write($indexRow, $indexCol++, intval($row['unit_price']*$row['qty']). $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['st_name'], $formatData);
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
$workbook->send('Internal Orders ' . date('Hms') . '.xls');

// Calls finalization methods.
// This method should always be the last one to be called on every workbook
$workbook->close();
?>
