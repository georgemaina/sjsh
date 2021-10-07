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

$worksheet = $workbook->addWorksheet('Treatment Register');

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
$worksheet->setColumn(6, 6, 15); // Email, set the width to 15
$worksheet->setColumn(8, 8, 20); // Email, set the width to 15

$inputUser=$_REQUEST['inputUser'];

$worksheet->write(1, 1, 'TREATMENT REGISTER REPORT', $formatHeader);

$indexRow = 4;
$indexCol = 0; // Start @ column 0

 
    
   
//Order No Prescription No Order Date OP No Patient Name Stock Code Description Unit Cost 
//Qty Prescribed Qty Issued Pending qty Total Amount 	Issuing Store 	Issued By
// Create the header for the data starting @ row 4
    $worksheet->write($indexRow, $indexCol++, 'PID', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'ScreeningDate', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'PatientName', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'NationalID', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Date of Birth', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Sex', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'MobileConsent', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Mobile', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Location', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Initial BP', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'BP First Reading', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'BP Second Reading', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Normal', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Pre_hypertensive', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Hypertensive', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Weight', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Height', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Diabetic', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Smoking', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Drinking', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'NewPatient', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'ReturnPatient', $formatReportHeader);
    $worksheet->write($indexRow, $indexCol++, 'Medication', $formatReportHeader);


    $indexRow++;   // Advance to the next row
    $indexCol = 0; // Start @ column 0


$strDate1 = $_REQUEST[strDate1];
$strDate2 = $_REQUEST[strDate2];


$sql="SELECT p.PID,ScreeningDate,PatientName,NationalID,Dob,Sex,MobileConsent,Mobile,PatientLocation,v.`BPInitial1`,v.`BPInitial2`,
            v.`BPFirstReading1`,v.`BPFirstReading2`,v.`BPSecondReading1`,v.`BPSecondReading2`,v.`Normal`,v.`Pre_hypertensive`,
            v.`Hypertensive`,v.`Weight`,v.`Height`, `Diabetes`
            , `Smoking`, `Drinking`,v.`NewPatient`,v.`ReturnPatient`, v.`EncounterNr` FROM care_hha_patients p
            LEFT JOIN care_hha_vitals v ON p.`PID`=v.`PID`
            LEFT JOIN care_hha_questions q ON p.`PID`= q.`PID` and q.Diabetes is not null";

if($strDate1<>'' and $strDate2<>''){
    $sql.=" where v.inputdate between '$strDate1' and '$strDate2'";
}

//echo $sql;

$result=$db->Execute($sql);
$numRows=$result->RecordCount();


    while ($row = $result->FetchRow()) {

        $drugsList=getPrescriptionItems($row[EncounterNr]);

        $worksheet->write($indexRow, $indexCol++, $row['PID'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['ScreeningDate'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['PatientName'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['NationalID'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['Dob'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['Sex'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['MobileConsent'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['Mobile'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['PatientLocation'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['BPInitial1'].'/'.$row['BPInitial2'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['BPFirstReading1'].'/'.$row['BPFirstReading2'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['BPSecondReading1'].'/'.$row['BPSecondReading2'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['Normal'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['Pre_hypertensive'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['Hypertensive'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['Weight'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['Height'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['Diabetes'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['Smoking'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['Drinking'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['NewPatient'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $row['ReturnPatient'], $formatData);
        $worksheet->write($indexRow, $indexCol++, $drugsList, $formatData);
        // Advance to the next row
        $indexCol = 0;
        $indexRow++;
    }


// Sends HTTP headers for the Excel file.
$workbook->send('Treatment Register' . date('Hms') . '.xls');

// Calls finalization methods.
// This method should always be the last one to be called on every workbook
$workbook->close();


function getPrescriptionItems($encNo){
    global $db;
    $debug=false;

    $sql="SELECT article FROM care_encounter_prescription WHERE encounter_nr=$encNo
            AND drug_class='Drug_list'";

    if($debug) echo $sql;

    $result=$db->Execute($sql);
    $drugCount=$result->RecordCount();

    $drugs='';
    $counter=0;
    while($row=$result->FetchRow()){
        $drugs=$drugs."$row[article]";

        $counter++;
        if($counter<>$drugCount){
            $drugs=$drugs.",";
        }

    }
    return $drugs;

}
?>
