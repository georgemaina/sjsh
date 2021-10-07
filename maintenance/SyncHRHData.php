<?php
/**
 * Created by PhpStorm.
 * User: george
 * Date: 12/9/2015
 * Time: 03:30 PM
 */


require('./roots.php');
require('../include/inc_environment_global.php');

    $tokens=getAccessToken();

    $json = json_decode($tokens, true);

    $token=$json['access_token'];
    $tokenType=$json['token_type'];



    $jsonData=getDataToSync();

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
            v.`Weight`,v.`Height`,q.`Diabetes`,q.`Smoking`,q.`Drinking`,q.`EncounterNr` FROM care_hha_patients p
            LEFT JOIN care_hha_vitals v ON p.`PID`=v.`PID`
            LEFT JOIN care_hha_questions q ON p.`PID`=q.`PID` where p.synced=0";

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
            .'","weight":"'.$row[Weight].'","height":"'. $row[Height].'","diabetic":'. $row[Diabetes].',"smoker":'. $row[Smoking]
            .',"alcohol":'. $row[Drinking].',"htnMedicationDispensed":"'.$drugsList.'"}';

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

    $result=$db->Execute($sql);
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