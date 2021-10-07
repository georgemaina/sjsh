<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');
require_once 'spreadsheet/Excel/Writer.php';
require($root_path.'include/care_api_classes/class_ward.php');
$wrd = new Ward ();
// Lets define some custom colors codes
define('CUSTOM_DARK_BLUE', 20);
define('CUSTOM_BLUE', 21);
define('CUSTOM_LIGHT_BLUE', 22);
define('CUSTOM_YELLOW', 23);
define('CUSTOM_GREEN', 24);

$workbook=new Spreadsheet_Excel_Writer();

$worksheet=$workbook->addWorksheet('Bed Occupancy');

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

$worksheet->write(1, 1, 'Bed Occupancy', $formatHeader);

// Create the header for the data starting @ row 4
$indexCol = 0;
$indexRow = 4;
$worksheet->write($indexRow, $indexCol++, 'PID', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Names', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Admission Date', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Bill Number', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Bed Days', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Ward', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Bed', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Bill', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Deposit', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Balance', $formatReportHeader);
$worksheet->write($indexRow, $indexCol++, 'Payment Method', $formatReportHeader);

$indexRow++;   // Advance to the next row
$indexCol = 0; // Start @ column 0

    //$accno=$_REQUEST[acc1];


$sql = "SELECT p.pid,e.encounter_nr, p.name_first,p.name_last,p.name_2,e.encounter_date,
       e.current_ward_nr,w.name,b.`bill_number`,DATEDIFF(NOW(),e.`encounter_date`) AS BedDays ,SUM(IF( b.service_type NOT IN('payment','NHIF'),total,0)) AS bill,
       SUM(IF(b.service_type IN ('payment','NHIF'),total,0)) AS payment,b.`bill_number`,c.`name` as company
       FROM care_encounter e
        LEFT JOIN care_ke_billing b ON e.encounter_nr=b.`encounter_nr`
        LEFT JOIN care_person p  ON e.pid=p.pid
        LEFT JOIN care_ward w ON e.current_ward_nr=w.nr
        LEFT JOIN care_tz_company c ON p.`insurance_ID`=c.`id`
        WHERE e.encounter_class_nr=1 AND e.is_discharged<>1 AND b.`IP-OP`=1
        GROUP BY pid
        ORDER BY w.name ASC";
        //echo $sql;

    $result=$db->Execute($sql);
    while($row=$result->FetchRow()){
          $row2 = $wrd->EncounterLocationsInfo ($row[1] );
        $bed_nr = $row2 [6];
		$room_nr = $row2 [5];
		$ward_nr = $row2 [0];
		$ward_name = $row2 [1];
        $bill=$row[bill];
        $depo=$row[payment];
        $bal=intval($bill-$depo);

        if(!$row[company]){
            $paymentMethod='CASH PAYMENT';
        }else{
            $paymentMethod=$row[company];
        }
        
        $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['pid'],
            $formatData);

        $worksheet->write(
            $indexRow,
            $indexCol++,
            trim($row[2]).' '.trim($row[3]).' '.trim($row[4]),
            $formatData);
        
         $worksheet->write(
            $indexRow,
            $indexCol++,
           $row['encounter_date'],
            $formatData);

        $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['bill_number'],
            $formatData);

        $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['BedDays'],
            $formatData);

         $worksheet->write(
            $indexRow,
            $indexCol++,
            $row['name'],
            $formatData);
         
         $worksheet->write(
            $indexRow,
            $indexCol++,
            $bed_nr,
            $formatData);
         
         $worksheet->write(
            $indexRow,
            $indexCol++,
            number_format($bill,2),
            $formatData);
         
         $worksheet->write(
            $indexRow,
            $indexCol++,
            number_format($depo,2),
            $formatData);
         
         $total=intval($row[total]+$total);
         $worksheet->write(
            $indexRow,
            $indexCol++,
            number_format($bal,2),
            $formatData);

        $worksheet->write(
            $indexRow,
            $indexCol++,
            $paymentMethod,
            $formatData);
         // Advance to the next row
        $indexCol=0;
        $indexRow++;
        
    }
    
    // Sends HTTP headers for the Excel file.
$workbook->send('Bed Occupances'.date('Hms').'.xls');
    
// Calls finalization methods.
// This method should always be the last one to be called on every workbook
$workbook->close();


function getBalance($pid,$sign,$enc_nr){
    global $db;

    $sql="select sum(b.total) as total from care_ke_billing b  left join care_encounter e
    on b.pid=e.pid where b.pid=$pid and e.encounter_nr=$enc_nr and b.service_type $sign'Payment' and
    b.`IP-OP`=1 AND e.is_discharged=0 group by b.pid";
   // echo $sql;
    $request=$db->Execute($sql);
    if($row=$request->FetchRow()){
        return $row[0];
    }else{
        return '0';
    }

}

function getTotals($sign){
    global $db;

    $sql="select sum(b.total) as total from care_ke_billing b  left join care_encounter e
    on b.pid=e.pid where b.service_type $sign'Payment' and
    b.`IP-OP`=1 AND e.is_discharged=0";
    $request=$db->Execute($sql);
    if($row=$request->FetchRow()){
        return $row[0];
    }else{
        return '0';
    }
}
?>
