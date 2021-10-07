<?php
/**
 * Created by PhpStorm.
 * User: george
 * Date: 10/14/2015
 * Time: 04:23 PM
 */


error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');

require_once($root_path . 'include/care_api_classes/class_tz_insurance.php');
require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
$insurance_obj = new Insurance_tz;
$bill_obj = new Bill;

$limit = $_REQUEST[limit];
$start = $_REQUEST[start];

$task = ($_REQUEST['task']) ? ($_REQUEST['task']) : '';

$sDate = new DateTime($_POST[StartDate]);
$startDate= $sDate->format('Y/m/d');

$sDate = new DateTime($_POST[EndDate]);
$endDate = $sDate->format('Y/m/d');

$inputDate=date('Y-m-d H:i:s');
$inputTime=date('H:i:s');

$debtorFormStat=$_POST[formStatus];
$AllocationItems=$_REQUEST[transNos];

if (isset($_POST[debtorStatus])) {
    $suspended = 1;
} else {
    $suspended = 0;
}

$consumer_key = 'anonymous';
$consumer_secret = 'anonymous';
$oauth_key = 'test_client';
$oauth_secret = 'test_secret';
$server_url="https://hha-api.slade360.co.ke";
$auth_url="/oauth2/token/";
$screening_url="/screening/staging/";
$treatment_url="/treatment/staging/";
$username = 'george@chak.or.ke';
$password = '485fe15b8d';

$searchParam = $_REQUEST[searchParam];

switch ($task) {
    case "getPrescriptions":
        getPrescription($_REQUEST[EncounterNo]);
        break;
    case "getPatientsList":
        getPatientsList($start, $limit);
        break;
    case "saveinitial":
         saveInitialEncounter($_POST);
        break;
    case "getEncountersList":
        getEncountersList($searchParam,$start, $limit);
        break;case "saveContinuationCare":
        saveContinuationCare($_POST);
        break;
    case "getTreatmentRegister":
        getTreatmentRegister($startDate,$endDate);
        break;
    case "getHhaDrugs";
        getHhaDrugs();
        break;
    case "addPrescription";
        addPrescription($inputDate,$inputTime);
        break;
    case "getDataToSync":
        getDataToSync();
        break;
    case "syncData":
        syncData();
        break;
    case "getAccessToken";
        getAccessToken($oauth_key,$oauth_secret,$server_url,$auth_url);
        break;
    default:
        echo "{failure:true}";
        break;
}//end switch

function getHospitalDetails(){
    global $db;
    $debug=false;

    $sql="SELECT CompanyName,Town,County,Mflcode FROM care_ke_invoice";
    if($debug) echo $sql;
    $result=$db->Execute($sql);
    if($result->RecordCount()>0){
        $row=$result->FetchRow();
        return $row;
    }


}


function getAccessToken(){
    $service_url = 'https://hha-api.slade360.co.ke/oauth2/token/';
    $curl = curl_init($service_url);
    $curl_post_data = array(
        "grant_type" => 'password',
        "username" => 'george@chak.or.ke',
        "password" => '485fe15b8d',
        "client_id" => '7UT4wQGhUzmn1eE9QWLTWxxtRrB3m77zQGxuziMy',
        "client_secret" => 'TrzzZF6jSD7zgTglJO6DP0hCT4xrOQKoyu0PvJTzTetubN0SxVxchUXTPwwri7jlnmYXLSi51XKNFad34YO8dYeE0sOioVEyAiMDkIvx5R5ZGP3S0U73XFB0fZvuVsnc'
    );
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $curl_response = curl_exec($curl);
    curl_close($curl);

//    echo $curl_response;
    return $curl_response;

}


function syncData(){
    global $db;

    $tokens=getAccessToken();

    $json = json_decode($tokens, true);

    $token=$json['access_token'];
    $tokenType=$json['token_type'];

    $jsonData=$_REQUEST[jsonData];

//    $JSON = json_encode($jsonData);

    $ch=curl_init("https://hha-api.slade360.co.ke/treatment/staging/");


    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: '.$tokenType.' '.$token,
        'Accept: application/json',
        'Content-type: application/json'
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 600); //timeout in seconds
    set_time_limit(0);// to infinity for example
    $curl_response = curl_exec($ch);
    curl_close($ch);
//    curl_exec($ch);

    if($curl_response){
        $resp=json_decode($curl_response,true);
        if(!$resp['errors']){
            $sql="Update care_hha_patients set synced=1 where synced=0";
            $db->Execute($sql);
        }
        echo $curl_response;
    }
//    echo $curl_response;
}

function getUniqueID($nationalID,$phone){
    if($nationalID<>''){
        $uniqueID=sha1($nationalID);
    }else if($nationalID='' && $phone<>''){
        $uniqueID=sha1($phone);
    }

    return $uniqueID;
}

function getDataToSync(){
    global $db;
    $debug=false;

    $strDate1=$_REQUEST[StartDate];
    $strDate2=$_REQUEST[EndDate];

    $sql="SELECT p.`County`,p.`ClinicLocation`,p.`FacilityName`,p.`FacilityID`,ScreeningDate,p.`UniqueID`,Dob,Sex,PatientLocation,
	          v.`NewPatient`,v.`ReturnPatient`,v.`BPInitial1`,v.`BPInitial2`,
            v.`BPFirstReading1`,v.`BPFirstReading2`,v.`BPSecondReading1`,v.`BPSecondReading2`,
            v.`Weight`,v.`Height`, `Diabetes`, `Smoking`, `Drinking`, `EncounterNr` FROM care_hha_patients p
            LEFT JOIN care_hha_vitals v ON p.`PID`=v.`PID`
            LEFT JOIN care_hha_questions q ON p.`PID`= `PID` where p.synced=0";

    if($strDate1<>'' and $strDate2<>''){
        $sql.=" and ScreeningDate between '$strDate1' and '$strDate2'";
    }

    if($debug) echo $sql;

    $result=$db->Execute($sql);
    $numRows=$result->RecordCount();
   // echo '[';
    $counter=0;
    echo "[";
    while ($row = $result->FetchRow()) {
        $sDate = new DateTime($row[ScreeningDate]);
        $ScreeningDate = $sDate->format('Y-m-d');

        $dDate = new DateTime($row[Dob]);
        $dob = $dDate->format('Y');

        $drugsList=getPrescriptionItems($row[EncounterNr]);


        echo '{"county":"'. $row[County].'","facilityLocation":"'. $row[ClinicLocation].'","facilityName":"'. $row[FacilityName].'","mflCode":"'. $row[FacilityID]
            .'","date":"'. $ScreeningDate.'","uniqueIdentifier":"'. $row[UniqueID] .'","yearOfBirth":"'. $dob .'","gender":"'. $row[Sex]
            .'","location":"'. $row[PatientLocation].'","newPatient":'. $row[NewPatient].',"returningPatients":'. $row[ReturnPatient]
            .',"initialSystolicBpReading":"'. $row[BPInitial1]
            .'","initialDiastolicBpReading":"'.  $row[BPInitial2].'","firstSystolicBpReading":"'.$row[BPFirstReading1]
            .'","firstDiastolicBpReading":"'. $row[BPFirstReading2] .'","secondSystolicBpReading":"'. $row[BPSecondReading1]
            .'","secondDiastolicBpReading":"'. $row[BPSecondReading2]
            .'","weight":"'.$row[Weight].'","height":"'. $row[Height].'","diabetic":"'. $row[Diabetes].'","smoker":"'. $row[Smoking]
            .'","alcohol":"'. $row[Drinking].'","htnMedicationDispensed":"'.$drugsList.'"}';

        $counter++;
        if ($counter<>$numRows){
            echo ",";
        }

    }
    echo ']';

}


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

function getPrescription($encNo){
    global $db;
    $debug=false;

    $sql="SELECT encounter_nr,article,partcode,modify_time AS prescribeDate,dosage,times_per_day,days FROM care_encounter_prescription WHERE encounter_nr=$encNo
            AND drug_class='Drug_list'";

    if($debug) echo $sql;

   if($result=$db->Execute($sql)){
       $numRows=$result->RecordCount();
       echo '{
    "prescription":[';
       $counter=0;
       while ($row = $result->FetchRow()) {
           echo '{"EncounterNo":"'. $row[encounter_nr].'","Description":"'. $row[article].'","DrugCode":"'. $row[partcode].'","Dosage":"'. $row[dosage]
               .'","TimesPerDay":"'. $row[times_per_day].'","Days":"'. $row[days] .'","InputDate":"'. $row[prescribeDate].'"}';

           $counter++;
           if ($counter<>$numRows){
               echo ",";
           }

       }
       echo ']}';
   }


}

function addPrescription($nputdate,$inputtime){
    global $db;
    $debug=false;


    $sql="INSERT INTO `care_hha_prescriptions_temp` (
             `PID`,`EncounterNr`,`InputDate`,`InputTime`,`DrugCode`,`DrugName`,
              `Strength`,`PrescriptionStatus`,`Frequency`,`InputUser`)
            VALUES('$_REQUEST[PID]','$_REQUEST[EncNo]','$nputdate','$inputtime','$_REQUEST[DrugCode]'
            ,'$_REQUEST[Desription]','$_REQUEST[Strength]','$_REQUEST[PrescriptionStatus]',
                '$_REQUEST[Frequency]','Admin')";

    if($debug) echo $sql;

    if($db->Execute($sql)){
        echo "{success:true}";
    }else{
        echo "{success:false}";
    }
}

function getHhaDrugs(){
    global $db;
    $debug=false;

    $sql="SELECT partcode,`Item_Description` FROM care_tz_drugsandservices WHERE subCategory='HYP'";

    $result=$db->Execute($sql);
    $numRows=$result->RecordCount();
    echo '{
    "drugslist":[';
    $counter=0;
    while ($row = $result->FetchRow()) {
        echo '{"PartCode":"'. $row[partcode].'","Description":"'. $row[Item_Description].'"}';
        if ($counter<>$numRows){
            echo ",";
        }
        $counter++;
    }
    echo ']}';
}



function getTreatmentRegister($strDate1,$strDate2){
    global $db;
    $debug=false;

    $strDate1=$_REQUEST[StartDate];
    $strDate2=$_REQUEST[EndDate];

    $sql="SELECT distinct p.PID,ScreeningDate,PatientName,NationalID,Dob,p.Sex,MobileConsent,Mobile,
            IF(PatientLocation='',l.`citizenship`,PatientLocation) AS PatientLocation,v.`BPInitial1`,v.`BPInitial2`,
            v.`BPFirstReading1`,v.`BPFirstReading2`,v.`BPSecondReading1`,v.`BPSecondReading2`,v.`Normal`,v.`Pre_hypertensive`,
            v.`Hypertensive`,v.`Weight`,v.`Height`, `Diabetes`
            , `Smoking`, `Drinking`,v.`NewPatient`,v.`ReturnPatient`, v.`EncounterNr` FROM care_hha_patients p
            LEFT JOIN care_hha_vitals v ON p.`PID`=v.`PID`
            LEFT JOIN care_hha_questions q ON p.`PID`= q.`PID` and q.Diabetes is not null
            LEFT JOIN care_person l ON v.`PID`=l.`PID`";

    if($strDate1<>'' and $strDate2<>''){
        $sql.=" where v.inputdate between '$strDate1' and '$strDate2'";
    }

    if($debug) echo $sql;

        $result=$db->Execute($sql);
    $numRows=$result->RecordCount();
    echo '{
    "treatment":[';
    $counter=0;
    while ($row = $result->FetchRow()) {

        if($row[EncounterNr]<>'' && $row[EncounterNr]>0){
            $drugsList=getPrescriptionItems($row[EncounterNr]);
        }


        echo '{"PID":"'. $row[PID].'","Date":"'. $row[ScreeningDate].'","PatientName":"'. $row[PatientName].'","NationalID":"'. $row[NationalID]
            .'","Dob":"'. $row[Dob].'","Gender":"'. $row[Sex] .'","MobileConsent":"'. $row[MobileConsent].'","Mobile":"'. $row[Mobile]
            .'","Location":"'. $row[PatientLocation].'","BPInitialReading":"'. $row[BPInitial1].'/'.$row[BPInitial2]
            .'","BPFirstReading":"'.  $row[BPFirstReading1].'/'.$row[BPFirstReading2].'","BPSecondReading":"'.$row[BPSecondReading1].'/'.$row[BPSecondReading2]
            .'","NormalBP":"'. $row[Normal] .'","PreHypertensive":"'. $row[Pre_hypertensive] .'","Hypertensive":"'. $row[Hypertensive]
            .'","Weight":"'.$row[Weight].'","Height":"'. $row[Height].'","Diabetic":"'. $row[Diabetes].'","Smoker":"'. $row[Smoking]
            .'","Alcohol":"'. $row[Drinking].'","NewPatient":"'. $row[NewPatient].'","ReturningPatient":"'. $row[ReturnPatient].'","Medication":"'. $drugsList. '"}';
       
        $counter++;
        if ($counter<>$numRows){
            echo ",";
        }
        
    }
    echo ']}';
}

function getMeasurements($encNo){
    global $db;
    $debug=false;

    $sql="SELECT m.msr_type_nr,t.name,m.`value` FROM care_encounter_measurement m
            LEFT JOIN care_type_measurement t ON m.msr_type_nr=t.nr
             WHERE encounter_nr=$encNo";

    if($debug) echo $sql;
    $result=$db->Execute($sql);
   // $unit_ids=array();
//    $row=$result->FetchRow();
     

    return $result;
}

function getEncountersList($searchParam,$start,$limit){
    global $db;
    $debug=FALSE;

    $user= $_SESSION['sess_user_name'];
    $hospDetails=getHospitalDetails();
    $ScreeningCode=$hospDetails[CompanyName];
    $FacilityID=$hospDetails[Mflcode];
    $FacilityName=$hospDetails[CompanyName];
    $County=$hospDetails[County];



    $sql = "SELECT c.`pid`,c.`name_first`, c.`name_2`, c.`name_last`, c.`date_birth`, c.`sex`,e.`name_formal`, d.`encounter_date`,
                d.encounter_nr,d.encounter_time,c.phone_1_nr,c.nat_id_nr,c.pid as UniqueID,c.citizenship,c.region as County,d.hhaUpdate,
                v.`BPFirstReading1`,v.`BPFirstReading2`,v.`BPInitial1`,v.`BPInitial2`,v.`BPSecondReading1`,v.`BPSecondReading2`,
                v.`Normal`,v.`Pre_hypertensive`,v.`Hypertensive`,v.`ReturnPatient`, Smoking, Drinking, Cardiovascular, Diabetes,
                 Observations, LMP, DrugAllergies, AllergiesDetails, AdheringMedications, AdheringMedicationsDetails,
                 MildHypertensionLife, MildHypertensionDiuretic, MildHypertensionCcbs, MildHypertensionOthers,
                 ModerateHypetensionLife, ModerateHypetensionDiuretic, ModerateHypetensionCcbs, ModerateHypetensionOthers,
                 ModerateHypetensionAce, FollowupPlan, Clinician, q.Designation
            FROM care_person c
            INNER JOIN care_encounter d on c.pid=d.pid 
            inner join care_department e on e.nr=d.current_dept_nr
            LEFT JOIN care_hha_patients p on d.pid=p.PID
            LEFT JOIN care_hha_vitals v ON d.`encounter_nr`=v.`EncounterNr`
            LEFT JOIN care_hha_questions q ON d.encounter_nr= q.EncounterNr
            WHERE e.`type`=1 and d.encounter_class_nr=2 and d.encounter_date=date(now()) and d.hhaUpdate<>'Yes'";
//echo $sql;

    if($searchParam<>''){
        $sql.=" and (c.pid='$searchParam' OR c.`name_first` like '%$searchParam%'  OR c.`name_2` like '%$searchParam%'  OR c.`name_last` like '%$searchParam%')";
    }

    $sql.=" order by d.encounter_time desc ";

    if($debug) echo $sql;

    $result=$db->Execute($sql);
    $numRows=$result->RecordCount();
    echo '{
    "encounters":[';
    $counter=0;
    while ($row = $result->FetchRow()) {
        $results=getMeasurements($row[encounter_nr]);

        while($msr=$results->FetchRow()){
            switch ($msr[msr_type_nr]) {
                case "1":
                    $Systolic = $msr['value'];
                    break;
                case "2":
                    $Diastolic=$msr['value'];
                    break;
                case "6":
                    $Weight=$msr['value'];
                    break;
                case "7":
                    $Height=$msr['value'];
                    break;
                case "13":
                    $Bmi=$msr['value'];
                    break;
//                default:
//                    echo "{failure:true}";
//                    break;
            }
        }

        if($row[5]=='m'){
            $sex='Male';
        }else{
            $sex='Female';
        }
        echo '{"PID":"'. $row[0].'","PatientName":"'. $row[1].' '. $row[2].' '. $row[3].'","ScreeningDate":"'. $row[encounter_date]
            .'","Time":"'. $row[encounter_time].'","Sex":"'. $sex .'","MobileNumber":"'. $row[phone_1_nr].'","Clinic":"'. $row[6]
            .'","EncounterNo":"'. $row[encounter_nr].'","DOB":"'. $row[4].'","NationalID":"'. $row[nat_id_nr].'","UniqueID":"'. $row[UniqueID]
            .'","County":"KIRINYAGA","Location":"'. $row[citizenship].'","hhaUpdate":"'. $row[hhaUpdate]
            .'","ClinicianName":"'. $user.'","ScreeningSite":"'. $ScreeningCode.'","ScreeningCode":"1","FacilityID":"'. $FacilityID
            .'","FacilityName":"'. $FacilityName.'","BPInitial1":"'. $Systolic
            .'","BPInitial2":"'. $Diastolic.'","Weight":"'. $Weight.'","Height":"'. $Height.'","BMI":"'. $Bmi
            .'","BPFirstReading1":"'.$row[BPFirstReading1].'","BPFirstReading2":"'.$row[BPFirstReading2]
            .'","BPSecondReading1":"'.$row[BPSecondReading1].'","BPSecondReading2":"'.$row[BPFirstReading2].'","Normal":"'.$row[Normal]
            .'","Smoking":"'.$row[Smoking].'","Drinking":"'.$row[Drinking].'","Cardiovascular":"'.$row[Cardiovascular].'","Diabetes":"'.$row[Diabetes]
            .'","Pre_hypertensive":"'.$row[Pre_hypertensive].'","Hypertensive":"'.$row[Hypertensive].'","ReturnPatient":"'.trim($row[ReturnPatient])
            .'","Observations":"'.$row[Observations].'","LMP":"'.$row[LMP].'","DrugAllergies":"'.$row[DrugAllergies]
            .'","AllergiesDetails":"'.$row[AllergiesDetails].'","AdheringMedications":"'.$row[AdheringMedications]
            .'","AdheringMedicationsDetails":"'.$row[AdheringMedicationsDetails].'","MildHypertensionLife":"'.$row[MildHypertensionLife]
            .'","MildHypertensionDiuretic":"'.$row[MildHypertensionDiuretic].'","MildHypertensionCcbs":"'.$row[MildHypertensionCcbs]
            .'","MildHypertensionOthers":"'.$row[MildHypertensionOthers].'","ModerateHypetensionLife":"'.$row[ModerateHypetensionLife]
            .'","ModerateHypetensionDiuretic":"'.$row[ModerateHypetensionDiuretic].'","ModerateHypetensionCcbs":"'.$row[ModerateHypetensionCcbs]
            .'","ModerateHypetensionOthers":"'.$row[ModerateHypetensionOthers].'","ModerateHypetensionAce":"'.$row[ModerateHypetensionAce]
            .'","FollowupPlan":"'.$row[FollowupPlan].'","Clinician":"'.$row[Clinician].'","Designation":"'.$row[Designation].'"}';

        $counter++;

        if ($counter<>$numRows){
            echo ",";
        }

    }
    echo ']}';
}

function getPatientsList($start, $limit) {
    global $db;
    $debug = false;

    $sql = "SELECT `PID`,`PatientName`,`FacilityName`,`FacilityID`,`ScreeningDate`,`UniqueID`,`NationalID`,
   `Address`,`Dob`,`Sex`,`MobileConsent`,`Mobile`,`InputUser` FROM `care_hha_patients`";

//    $sql.=" limit $start,$limit";
    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);

    $total = $request->RecordCount();

    echo '{
    "total":"' . $total . '","patientslist":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {
        echo '{"PID":"' . $row[PID] . '","PatientName":"' . trim($row[PatientName]) . '","Dob":"' . $row[Dob]
            . '","Sex":"' . $row[Sex] . '","InitialDate":"' . $row[ScreeningDate] . '","UniqueID":"' . $row[UniqueID] . '","NationalID":"' . $row[NationalID]
            . '","Address":"' . $row[Address] . '","FacilityName":"' . $row[FacilityName]
            . '","FacilityID":"' . $row[FacilityID] . '","MobileConsent":"' . $row[MobileConsent] . '","Mobile":"' . $row[Mobile]  .'"}';

        $counter++;
        if ($counter <> $total) {
            echo ',';
        }
    }
    echo ']}';
}

function validatePatient($pid){
    global $db;
    $debug=false;
    $psql = "SELECT * FROM care_hha_patients WHERE pid=$pid";
    if($debug) echo $psql;

    $presult = $db->Execute($psql);
    $row = $presult->FetchRow();
    $rowCount=$presult->RecordCount();

    return $rowCount;
}

function saveInitialEncounter($encounterDetails) {
    global $db;
    $debug=false;
    $error=0;
    $inputDate=date('Y-m-d');
    $inputTime=date('H:i:s');

    $sDate = new DateTime($encounterDetails[ScreeningDate]);
    $ScreeningDate = $sDate->format('Y/m/d');

    $dob = new DateTime($encounterDetails[DOB]);
    $DOB = $dob->format('Y/m/d');


    $inputUser= $_SESSION['sess_user_name'];

    $uniqueID=getUniqueID($encounterDetails[NationalID],$encounterDetails[MobileNumber]);

            $sql2 = "INSERT INTO `care_hha_patients`
                        (`PID`,`PatientName`,`FacilityName`,`FacilityID`,`ScreeningDate`,`UniqueID`,`NationalID`,
                          `County`,`Dob`,`Sex`,`MobileConsent`,`Mobile`,`InputUser`,`PatientLocation`,`ClinicLocation`)
                    VALUES
                      ( '$encounterDetails[PID]','$encounterDetails[PatientName]','$encounterDetails[FacilityName]','$encounterDetails[FacilityID]'
                        ,'$ScreeningDate','$uniqueID','$encounterDetails[NationalID]'
                        ,'$encounterDetails[County]','$DOB','$encounterDetails[Sex]','$encounterDetails[MobileConsent]'
                        ,'$encounterDetails[MobileNumber]','$inputUser','$encounterDetails[Location]','LITEIN')";

            if($debug) echo $sql2;

    if( validatePatient($encounterDetails[PID])>0){
        $error=4;
    }else{
        if ($db->Execute($sql2)) {
            if($_REQUEST[Vitals]>0){
                $error=insertVitals($encounterDetails,$inputTime,$inputDate,$inputUser);
            }

            if($_REQUEST[Questions]>0) {
                $error=insertQuestions($encounterDetails,$inputTime,$inputDate,$inputUser);
            }

        } else {
            $error=1;
        }
    }

        if($error==0){
            $sql="Update care_encounter set hhaUpdate='Yes' where encounter_nr='$encounterDetails[EncounterNo]'";
            $db->Execute($sql);
            echo "{success:true}";
        }else if($error==1){
            echo "{failure:true,'error':'Unable to create Initial Encounter'}";
        }else if($error==2){
            echo "{failure:true,'error':'Unable to create Vital Signs'}";
        }else if($error==3){
            echo "{failure:true,'error':'Unable to create Observations'}";
        }else if($error==4){
            echo "{failure:true,error:'Initial encounter already exists,Please use Continuation of Care Form'}";
        }

}

function insertVitals($encounterDetails,$inputTime,$inputDate,$inputUser){
    global $db;
    $debug=false;

    if($encounterDetails[BPSecondReading1]<=129 and $encounterDetails[BPSecondReading2]<=84){
        $normal='Y';
    }else{
        $normal='N';
    }

    if($encounterDetails[BPSecondReading1]>130 and $encounterDetails[BPSecondReading1]<=139 
                        and $encounterDetails[BPSecondReading2]>85 and $encounterDetails[BPSecondReading2]<=89){
        $pre_hype='Y';
    }else{
        $pre_hype='N';
    }

    if($encounterDetails[BPSecondReading1]>=140 and $encounterDetails[BPSecondReading2]>=90){
        $hyper='Y';
    }else{
        $hyper='N';
    }

    $initialVitals=getInitialVitals($encounterDetails[PID]);

    if($encounterDetails[BPInitial1] && $encounterDetails[BPInitial2]){
        $bpInitial1=$encounterDetails[BPInitial1];
        $bpInitial2=$encounterDetails[BPInitial2];

        $newPatient='true';
        $returnPatient='false';
    }else{
        $bpInitial1=$initialVitals[0];
        $bpInitial2=$initialVitals[1];

        $newPatient='false';
        $returnPatient='true';
    }




    $sql1="INSERT INTO `care_hha_vitals` (
                          `FormID`,`PID`,`EncounterNr`,`InputDate`,`InputTime`,`BPInitial1`,`BPInitial2`,
                          `BPFirstReading1`,`BPFirstReading2`,`BPSecondReading1`,`BPSecondReading2`,`Normal`,`Pre_hypertensive`,`Hypertensive`,
                          `Height`,`Weight`,`BMI`,`Clinian`,`Designation`,`InputUser`,`NewPatient`,`ReturnPatient`)
                        VALUES ( '$encounterDetails[Vitals]','$encounterDetails[PID]','$encounterDetails[EncounterNo]','$inputDate'
                        ,'$inputTime','$bpInitial1','$bpInitial2','$encounterDetails[BPFirstReading1]'
                        ,'$encounterDetails[BPFirstReading2]','$encounterDetails[BPSecondReading1]','$encounterDetails[BPSecondReading2]'
                        ,'$normal','$pre_hype','$hyper','$encounterDetails[Height]'
                        ,'$encounterDetails[Weight]' ,'$encounterDetails[BMI]','$encounterDetails[ClinicianName]',
                        '$encounterDetails[Designation]','$inputUser','$newPatient','$returnPatient')";

    if($debug) echo $sql1;

    if($db->Execute($sql1)){
        return 0;
    }else{
        return 2;
    }

}

function getInitialVitals($pid){
    global $db;
    $debug=false;

    $sql="SELECT BPInitial1,BPInitial2 FROM care_hha_vitals WHERE pid=$pid AND FormID=1";
    $result=$db->Execute($sql);

    $rcount=$result->RecordCount();
    if($rcount>0){
        $row=$result->FetchRow();
        return $row;
    }else{
        return 0;
    }
}

function insertQuestions($encounterDetails,$inputTime,$inputDate,$inputUser)
{
    global $db;    //22295021
    $debug = false;

    $sql2 = "INSERT INTO `care_hha_questions` (
                              `FormID`,`PID`,`EncounterNr`,`InputDate`,`Smoking`,`Drinking`,`Cardiovascular`,`Diabetes`,`Observations`,
                              `LMP`,`DrugAllergies`,`AllergiesDetails`,`AdheringMedications`,`AdheringMedicationsDetails`,`FollowupPlan`
                              ,`Clinician`,`Designation`,`MildHypertensionLife`,`MildHypertensionCcbs`,`MildHypertensionDiuretic`,`MildHypertensionOthers`
                              ,`ModerateHypetensionLife`,`ModerateHypetensionCcbs`,`ModerateHypetensionDiuretic`,`ModerateHypetensionAce`,
                              `ModerateHypetensionOthers`,`InputUser`,`Notes`)
                            VALUES('$encounterDetails[Questions]','$encounterDetails[UniqueID]','$encounterDetails[EncounterNo]','$inputDate'
                            , '$encounterDetails[Smoking]','$encounterDetails[Drinking]','$encounterDetails[Cardiovascular]','$encounterDetails[Diabetes]'
                            ,'$encounterDetails[Observations]','$encounterDetails[LMPfemale]','$encounterDetails[DrugAllergies]','$encounterDetails[AllergiesSpecify]'
                            ,'$encounterDetails[AdheringMedication]','$encounterDetails[AdheringSpecify]','$encounterDetails[FollowupPlan]'
                            ,'$encounterDetails[ClinicianName]','$encounterDetails[Designation]',
                            '$encounterDetails[MildHypertensionLife]','$encounterDetails[MildHypertensionCcbs]','$encounterDetails[MildHypertensionDiuretic]',
                            '$encounterDetails[MildHypertensionOthers]'
                            ,'$encounterDetails[ModerateHypetensionLife]','$encounterDetails[ModerateHypetensionCcbs]'
                            ,'$encounterDetails[ModerateHypetensionDiuretic]','$encounterDetails[ModerateHypetensionAce]'
                            ,'$encounterDetails[ModerateHypetensionOthers]','$inputUser','$encounterDetails[Notes]')";

    if ($debug) echo $sql2;

    if ($db->Execute($sql2)) {
        return  0;
    } else {
        return  3;
    }
}

function saveContinuationCare($encounterDetails) {
    global $db;
    $debug=false;
    $error=0;
    $inputDate=date('Y-m-d');
    $inputTime=date('H:i:s');

    $sDate = new DateTime($encounterDetails[ScreeningDate]);
    $ScreeningDate = $sDate->format('Y/m/d');

    $dob = new DateTime($encounterDetails[DOB]);
    $DOB = $dob->format('Y/m/d');

    $inputUser='Admin';

  if(validatePatient($encounterDetails[PID])<1){
      $sql2 = "INSERT INTO `care_hha_patients`
                (`PID`,`PatientName`,`FacilityName`,`FacilityID`,`ScreeningDate`,`UniqueID`,`NationalID`,
                  `County`,`Dob`,`Sex`,`MobileConsent`,`Mobile`,`InputUser`,`PatientLocation`,`ClinicLocation`)
            VALUES
              ( '$encounterDetails[PID]','$encounterDetails[PatientName]','$encounterDetails[FacilityName]','$encounterDetails[FacilityID]'
                ,'$ScreeningDate','$encounterDetails[UniqueID]','$encounterDetails[NationalID]'
                ,'$encounterDetails[County]','$DOB','$encounterDetails[Sex]','$encounterDetails[MobileConsent]'
                ,'$encounterDetails[MobileNumber]','$inputUser','$encounterDetails[Location]','LITEIN')";

      if($debug) echo $sql2;
      if($db->Execute($sql2)){
          $error=0;
      }else{
          $error=1;
      }

  }


    if($_REQUEST[Vitals]==3){
        $error=insertVitals($encounterDetails,$inputTime,$inputDate,$inputUser);
    }

    if($_REQUEST[Questions]==4) {
        $sql2 = "INSERT INTO `care_hha_questions` (
                              `FormID`,`PID`,`EncounterNr`,`InputDate`,`Observations`
                              ,`DrugAllergies`,`AllergiesDetails`,`AdheringMedications`,`AdheringMedicationsDetails`,`FollowupPlan`
                              ,`Clinician`,`Designation`,`MildHypertensionLife`,`MildHypertensionCcbs`,`MildHypertensionDiuretic`,`MildHypertensionOthers`
                              ,`ModerateHypetensionLife`,`ModerateHypetensionCcbs`,`ModerateHypetensionDiuretic`,`ModerateHypetensionAce`,
                              `ModerateHypetensionOthers`,`InputUser`,`Notes`)
                            VALUES('$encounterDetails[Questions]','$encounterDetails[UniqueID]','$encounterDetails[EncounterNo]','$inputDate'
                            ,'$encounterDetails[Observations]','$encounterDetails[DrugAllergies]','$encounterDetails[AllergiesSpecify]'
                            ,'$encounterDetails[AdheringMedication]','$encounterDetails[AdheringSpecify]','$encounterDetails[FollowupPlan]'
                            ,'$encounterDetails[ClinicianName]','$encounterDetails[Designation]',
                            '$encounterDetails[MildHypertensionLife]','$encounterDetails[MildHypertensionCcbs]','$encounterDetails[MildHypertensionDiuretic]',
                            '$encounterDetails[MildHypertensionOthers]'
                            ,'$encounterDetails[ModerateHypetensionLife]','$encounterDetails[ModerateHypetensionCcbs]'
                            ,'$encounterDetails[ModerateHypetensionDiuretic]','$encounterDetails[ModerateHypetensionAce]'
                            ,'$encounterDetails[ModerateHypetensionOthers]','$inputUser','$encounterDetails[Notes]')";

        if ($debug) echo $sql2;

        if ($db->Execute($sql2)) {
            return  0;
        } else {
            return  3;
        }
    }

    if($error==0){
        $sql="Update care_encounter set hhaUpdate='Yes' where encounter_nr='$encounterDetails[EncounterNo]'";
        $db->Execute($sql);
        echo "{success:true}";
    }else{
        echo "{failure:true,'error':'$error'}";
    }



}

?>
