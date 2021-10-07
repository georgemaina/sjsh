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

$worksheet=$workbook->addWorksheet('Diagnosis Report');

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

$worksheet->write(1, 1, 'Diagnosis Report', $formatHeader);

// Create the header for the data starting @ row 4
$indexCol = 0;
$indexRow = 4;
$worksheet->write($indexRow, $indexCol++, 'PID', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Names', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Date', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Gender', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Age', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Status', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'IP-OP', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'diagnosis code', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Description', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Visit', $formatReportHeader);

$indexRow++;   // Advance to the next row
$indexCol = 0; // Start @ column 0

 $age1 = $_REQUEST[age1];
    $age2 = $_REQUEST[age2];
    $date1 = $_REQUEST[date1];
    $date2 = $_REQUEST[date2];
    $gender = $_REQUEST[gender];
    $icd1 = $_REQUEST[icd1];
    $icd2 = $_REQUEST[icd2];
    $task = $_REQUEST[task];
    $visits = $_REQUEST[visits];
    $pid= $_REQUEST[pid];

    $sql = "SELECT distinct d.pid,p.selian_pid,p.name_first,p.name_last,p.name_2,p.date_birth,p.sex,(YEAR(NOW())-YEAR(p.date_birth)) AS age,
        d.encounter_nr,d.ICD_10_code,d.ICD_10_description,d.type,d.timestamp,d.pataintstatus FROM care_tz_diagnosis d left JOIN care_person p
ON d.PID=p.pid LEFT join care_encounter e on d.pid=e.pid";

    
    
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
        $sql = $sql . " where d.timestamp between '$dt1' and '$dt2' ";
    }else if($dt1<>''&& $dt2==''){
        $sql = $sql . " where d.timestamp = '$dt1'";
    }
    else {
        $sql = $sql . " where d.timestamp<=now()";
    }

    if (isset($gender) && $gender <> "") {
        if ($gender == 1) {
            $sex = 'M';
        } else {
            $sex = 'F';
        }
        $sql = $sql . " and sex='$sex'";
    }
    if ($icd1<>"" && $icd2 <> "") {
        $sql = $sql . " and ICD_10_code between '$icd1' and '$icd2'";
    }else if($icd1<>"" && $cd2==""){
        $sql = $sql . " and ICD_10_code = '$icd1'";
    }
    
      if (isset($age1) && $age2 <> "") {
        $sql = $sql . " having (YEAR(NOW())-YEAR(p.date_birth)) between '$age1' and '$age2'";
      }else if($age1<>"" && $age2==""){
            $sql = $sql . " having (YEAR(NOW())-YEAR(p.date_birth))='$age1'";
      }

      if ($pid<>"") {
        $sql = $sql . " and d.pid='$pid'";
      }
//    echo $sql;
    
    $result=$db->Execute($sql);
    while($row=$result->FetchRow()){
        $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['pid'],
            $formatData);

        $worksheet->write(
            $indexRow,
            $indexCol++,
             trim($row[name_first]) . ' ' . trim($row[name_last]) . ' ' . trim($row[name_2]),
            $formatData);
        
         $worksheet->write(
            $indexRow,
            $indexCol++,
           $row[timestamp],
            $formatData);
        
         $worksheet->write(
            $indexRow,
            $indexCol++,
            $row[sex],
            $formatData);
         
         $worksheet->write(
            $indexRow,
            $indexCol++,
            $row[age] ,
            $formatData);
         
         if($row[pataintstatus]=='A'){
            $stat='Alive';
        }else if($row[pataintstatus]=='D'){
            $stat='Dead';
        }else{
            $stat='';
        }
   
            $worksheet->write(
            $indexRow,
            $indexCol++,
            $stat,
            $formatData);
            
            if($row[encounter_class_nr]==1){
                $enc='IP';      
            }else{
                $enc='OP';
            }
            
            $worksheet->write(
            $indexRow,
            $indexCol++,
            $enc,
            $formatData);
         
         $worksheet->write(
            $indexRow,
            $indexCol++,
           $row[ICD_10_code] ,
            $formatData);
         
         $worksheet->write(
            $indexRow,
            $indexCol++,
            $row[ICD_10_description],
            $formatData);
			
			$worksheet->write(
            $indexRow,
            $indexCol++,
            $row[type],
            $formatData);
         // Advance to the next row
        $indexCol=0;
        $indexRow++;
        
    }
    
    // Sends HTTP headers for the Excel file.
$workbook->send('Diagnosis Report '.date('Hms').'.xls');
    
// Calls finalization methods.
// This method should always be the last one to be called on every workbook
$workbook->close();
?>
