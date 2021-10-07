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

$worksheet=$workbook->addWorksheet('Admissions Report');

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

$worksheet->write(1, 1, 'Admissions Report', $formatHeader);

// Create the header for the data starting @ row 4
$indexCol = 0;
$indexRow = 4;
$worksheet->write($indexRow, $indexCol++, 'PID', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Admission No', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Names', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Sex', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'DOB', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Admission Date', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Ward', $formatReportHeader);

$indexRow++;   // Advance to the next row
$indexCol = 0; // Start @ column 0

  $sql = "SELECT  e.pid,e.newAdm_No,p.name_first,p.name_last,p.name_2,p.sex,p.date_birth,e.encounter_date,e.encounter_time,
  e.current_ward_nr FROM care_person p INNER JOIN care_encounter e ON (p.pid = e.pid)";

     if ($date1){
        $date = new DateTime($date1);
        $dt1 = $date->format("Y-m-d");
    }else{
        $dt1="";
    }
     if ($date2){
        $date = new DateTime($date2);
        $dt2 = $date->format("Y-m-d");
     }else{
          $dt2="";
     }
  
    if ($dt1 <> "" && $dt2<>"" ) {
        $sql = $sql . " where e.encounter_date between '$dt1' and '$dt2' ";
    }else if($dt1<>''&& $dt2==''){
        $sql = $sql . " where e.encounter_date = '$dt1'";
    }
    else {
        $sql = $sql . " where e.encounter_date<=now()";
    }

    $sql = $sql . " and e.encounter_class_nr = 1 AND e.is_discharged = 0 order by e.encounter_date desc";
    
    $result=$db->Execute($sql);
    while($row=$result->FetchRow()){
        $worksheet->write(
            $indexRow,
            $indexCol++,
            $row[pid],
            $formatData);
        
         $worksheet->write(
            $indexRow,
            $indexCol++,
           $row[newAdm_No],
            $formatData);

        $worksheet->write(
            $indexRow,
            $indexCol++,
             trim($row[name_first]) . ' ' . trim($row[name_last]) . ' ' . trim($row[name_2]),
            $formatData);
        
         $worksheet->write(
            $indexRow,
            $indexCol++,
           $row[sex],
            $formatData);
        
         $worksheet->write(
            $indexRow,
            $indexCol++,
            $row[date_birth],
            $formatData);
         
         $worksheet->write(
            $indexRow,
            $indexCol++,
            $row[encounter_date] ,
            $formatData);
                                       
        $worksheet->write(
            $indexRow,
            $indexCol++,
            $row[Ward] ,
            $formatData);
         
         // Advance to the next row
        $indexCol=0;
        $indexRow++;
        
    }
    
    // Sends HTTP headers for the Excel file.
$workbook->send('Admissions Report '.date('Hms').'.xls');
    
// Calls finalization methods.
// This method should always be the last one to be called on every workbook
$workbook->close();
?>
