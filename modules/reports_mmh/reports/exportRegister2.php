<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Africa/Nairobi');

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

/** Include PHPExcel */
require_once '../../../../ExcelClasses/PHPExcel.php';


// Create new PHPExcel object
echo date('H:i:s') , "Treatment Register" , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("George Maina")
    ->setLastModifiedBy("George Maina")
    ->setTitle("Treatment Register")
    ->setSubject("Treatment Register")
    ->setDescription("Treatment Register contains all daily cash collections")
    ->setKeywords("Treatment Register")
    ->setCategory("Treatment Register");

$objPHPExcel->getActiveSheet(0)->mergeCells('A1:F1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Treatment Register');


$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'PID');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'Screening Day');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', 'Screening month');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', 'Screening Year');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', 'Patient Name');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', 'NationalID');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2', 'DOB');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H2', 'Sex');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I2', 'Mobile Consent');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J2', 'Mobile');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K2', 'NewPatient');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L2', 'Return Patient');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M2', 'Location');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N2', 'Initial Systolic');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O2', 'Initial Diastolic');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P2', 'BP First Systolic');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q2', 'BP First Diastolic');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R2', 'BP Second Systolic');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S2', 'BP Second Diastolic');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T2', 'Normal');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U2', 'Pre_Hypertensive');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('V2', 'Hypertensive');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('W2', 'Weight');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('X2', 'Height');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y2', 'Diabetic');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z2', 'Smoking');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA2', 'Drinking');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB2', 'Medication');

//$objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'TREATMENT REGISTER');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
// Set fonts
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

$objPHPExcel->getActiveSheet()->getStyle('A1:V1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:V1')->getFill()->getStartColor()->setARGB('#2555a3');



$strDate1 = $_REQUEST[strDate1];
$strDate2 = $_REQUEST[strDate2];

$sql="SELECT distinct p.PID,ScreeningDate,PatientName,NationalID,Dob,p.Sex,MobileConsent,Mobile,
IF(PatientLocation='',l.`citizenship`,PatientLocation) AS PatientLocation,v.`BPInitial1`,v.`BPInitial2`,
            v.`BPFirstReading1`,v.`BPFirstReading2`,v.`BPSecondReading1`,v.`BPSecondReading2`,v.`Normal`,v.`Pre_hypertensive`,
            v.`Hypertensive`,v.`Weight`,v.`Height`, `Diabetes`
            , `Smoking`, `Drinking`,v.`NewPatient`,v.`ReturnPatient`, v.`EncounterNr` FROM care_hha_patients p
            LEFT JOIN care_hha_vitals v ON p.`PID`=v.`PID`
            LEFT JOIN care_hha_questions q ON p.`PID`= q.`PID` AND q.Diabetes IS NOT NULL
            LEFT JOIN care_person l ON v.`PID`=l.`PID`";

if($strDate1<>'' and $strDate2<>''){
    $sql.=" where v.inputdate between '$strDate1' and '$strDate2'";
}

//echo $sql;

$result=$db->Execute($sql);
$numRows=$result->RecordCount();
$i=3;
while($row=$result->FetchRow()){
    $drugsList=getPrescriptionItems($row[EncounterNr]);

    $dob=new DateTime($row['Dob']);
    $yearBirth = $dob->format("Y");
    
    $scnDay=new DateTime($row['ScreeningDate']);
    $screeningDay = $scnDay->format("d");
    
    $scnMnth=new DateTime($row['ScreeningDate']);
    $screeningMonth = $scnMnth->format("m");
    
    $scnY=new DateTime($row['ScreeningDate']);
    $screeningYear = $scnY->format("Y");
    
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$i",$row['PID']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$i",$screeningDay);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$i",$screeningMonth);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$i",$screeningYear);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$i",$row['PatientName']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F$i",$row['NationalID']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$i",$yearBirth);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H$i",$row['Sex']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I$i",$row['MobileConsent']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J$i",$row['Mobile']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K$i",$row['NewPatient']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L$i",$row['ReturnPatient']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("M$i",$row['PatientLocation']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("N$i",$row['BPInitial1']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("O$i",$row['BPInitial2']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("P$i",$row['BPFirstReading1']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("Q$i",$row['BPFirstReading2']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("R$i",$row['BPSecondReading1']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("S$i",$row['BPSecondReading2']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("T$i",$row['Normal']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("U$i",$row['Pre_hypertensive']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("V$i",$row['Hypertensive']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("W$i",$row['Weight']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("X$i",$row['Height']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("Y$i",$row['Diabetes']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("Z$i",$row['Smoking']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("AA$i",$row['Drinking']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("AB$i",$drugsList);

    $i=$i+1;
}

$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

$objPHPExcel->getActiveSheet()->getColumnDimension('C2')->setWidth(30);

$objPHPExcel->getActiveSheet()->setTitle('TreatmentRegister');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save(str_replace('php','xlsx',$root_path."/docs/TreatmentRegister.xlsx"));

echo "Created file : ".str_replace('php','xlsx',$root_path."docs/TreatmentRegister.xlsx" ),EOL;

$objReader=PHPExcel_IOFactory::load($root_path."docs/TreatmentRegister.xlsx");


function getPrescriptionItems($encNo){
    global $db;
    $debug=false;

    $sql="SELECT article FROM care_encounter_prescription WHERE encounter_nr='$encNo'
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
<script>
    window.open('../../../docs/TreatmentRegister.xlsx', "Treatment Register",
        "menubar=no,toolbar=no,width=600,height=400,location=yes,resizable=yes,scrollbars=yes,status=yes");
</script>

