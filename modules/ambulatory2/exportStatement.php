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

$worksheet=$workbook->addWorksheet('Debtors Statement');

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

$worksheet->write(1, 1, 'Debtors Statement', $formatHeader);

// Create the header for the data starting @ row 4
$indexCol = 0;
$indexRow = 4;
$worksheet->write($indexRow, $indexCol++, 'PID', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'First name', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'last Name', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'SurName', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Bill Date', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Bill Number', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Debit', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Credit', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Running Total', $formatReportHeader);

$indexRow++;   // Advance to the next row
$indexCol = 0; // Start @ column 0

    $accno=$_REQUEST[acc1];
    $date1 = $_REQUEST[date1];
    $date2 = $_REQUEST[date2];
    
     function substractDate($date,$days)
{
//    $cuurDate = $date;
    $date = new DateTime($date);
    $dt1 = $date->format("d-m-Y");
    $newdate = strtotime ('-'.$days.' day' , strtotime ($dt1)) ;
    $newdate = date ( 'Y/m/d' , $newdate );
   
    return $newdate;
}
function getLastMonth($date) {
    $date = new DateTime(date($date));

    $current_month = $date->format("m");
    ;
    $current_year = $date->format("Y");
    ;
    if ($current_month == 1) {
        $lastmonth = 12;
    } else {
        $lastmonth = $current_month - 1;
    }
    $firstdate = $current_year . "-" . $lastmonth . "-01";

    $timestamp = strtotime($firstdate);

    $lastdateofmonth = date('t', $timestamp); // 	this function will give you the number of days in given month(that will be last date of the month)
//echo '<br>' . $lastdateofmonth;
    $lastdate =  $current_year. "/" . $lastmonth . "/" . $lastdateofmonth;
//    echo '<br>' . $lastdate;
    return $lastdate;
}

     $sql = "SELECT p.pid,IF(p.name_first<>'',p.name_first,c.name) AS name_first,p.name_last, 
    p.name_2,b.bill_date,b.bill_number,
    SUM(IF(b.service_type<>'Payment',b.total,0)) AS debit,
    SUM(IF(b.service_type='Payment',b.total,0)) AS credit,
    b.service_type,c.accno,b.`IP-OP`
    FROM care_ke_billing b INNER JOIN care_person p ON b.pid=p.pid 
    INNER JOIN care_tz_company c ON c.id=p.insurance_id INNER JOIN care_ke_debtors d ON d.accno=c.accno 
    WHERE d.accno='$accno' and b.service_type<>'NHIF'";
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

    if ($dt1 <> "" && $dt2 <> "") {
        $sql = $sql . " and b.bill_date between '$dt1' and '$dt2' ";
    } else if ($dt1 <> '' && $dt2 == '') {
        $sql = $sql . " and b.bill_date = '$dt1'";
    } else {
        $sql = $sql . " and b.bill_date<=now()";
    }
    $sql = $sql . " GROUP BY b.bill_number ORDER BY b.bill_date asc";
    
    $result=$db->Execute($sql);

    $lastMonth = getLastMonth($dt1);
  
    
     $sql2 = "select sum(total) as balance_bf from care_ke_billing b 
            left join care_person p on b.pid=p.pid 
            left join care_tz_company c on p.insurance_id=c.id
            where c.accno='$accno' and b.bill_date<='$lastMonth'";
      if($debug) echo $sql2;
    $request2 = $db->Execute($sql2);
    $row2 = $request2->FetchRow();
    
    $balanceBf = intval($row2[0] - $totalPayments);
    
    while($row=$result->FetchRow()){
        $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['pid'],
            $formatData);

        $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['name_first'],
            $formatData);
        
         $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['name_last'],
            $formatData);
        
         $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['name_2'],
            $formatData);
         
         $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['bill_date'],
            $formatData);
         
         $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['bill_number'],
            $formatData);
         
         //---------------------------------------------------------------------------
        //get last months amount for this bill number
        //get last months amount for this bill number
        $dts = new DateTime($lastMonth);
        $dt4 = $dts->format("Y-m-d");
        $threeMonthsAgo=substractDate($dt4,60);
        
         $sqlS="select sum(total) as total from care_ke_billing where pid=$row[pid] 
                and bill_number=$row[bill_number] and bill_date
        between '$threeMonthsAgo' and '$lastMonth' and `ip-op`=1 and service_type<>'Payment'";
        if($requestS = $db->Execute($sqlS)){
         $rowS = $requestS->FetchRow();
            $extTotal=$rowS[0];
         }
         
         if($debug) echo $sqlS;
       //----------------------------------------------------------------------------
          $DB=$row['debit']+$extTotal;
          
         $worksheet->write(
            $indexRow,
            $indexCol++,
            $DB,
            $formatData);
         
         $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['credit'],
            $formatData);

         $total=intval($row[debit]+$total);
         $worksheet->write(
            $indexRow,
            $indexCol++,
            $total,
            $formatData);
         // Advance to the next row
        $indexCol=0;
        $indexRow++;
        
    }
    
    // Sends HTTP headers for the Excel file.
$workbook->send('Debtors Statement '.date('Hms').'.xls');
    
// Calls finalization methods.
// This method should always be the last one to be called on every workbook
$workbook->close();


?>
