
<?php
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');

$limit = $_REQUEST['limit'];
$start = $_REQUEST['start'];

$item_number = $_POST['item_number'];
$rptType= $_REQUEST['rptType'];
$drug=$_REQUEST['partcode'];

$date1 = new DateTime($_REQUEST['startDate']);
$startDate = $date1->format("Y-m-d");

$date2 = new DateTime($_REQUEST['endDate']);
$endDate = $date2->format("Y-m-d");

$wardNo=$_REQUEST['wardNo'];
$labTest=$_REQUEST['labTest'];

$age1=$_REQUEST['age1'];
$age2=$_REQUEST['age2'];

$category=$_REQUEST['category'];
$pid=$_REQUEST['pid'];

$reportMonth=$_REQUEST['reportMonth'];
$reportType=$_REQUEST['reportType'];

$partCode=$_REQUEST['partCode'];

// getRevenues();
$task = ($_REQUEST['task']) ? ($_REQUEST['task']) : $_POST['task'];
switch ($task) {
    case "getDiagnosis":
        getDiagnosis();
        break;
    case "getPersonnel":
        getPersonnel();
        break;
    case "getTopDiagnosis":
        getTopDiagnosis($startDate,$endDate);
        break;
    case "getRevenues":
        getRevenues($rptType,$startDate,$endDate);
        break;
    case "getAdmDis":
        if ($_REQUEST['admdis'] == 'adm') {
            getAdmissions($startDate,$endDate);
        } else {
            getDischarges($startDate,$endDate);
        }
        break;
    case "lab":
        if ($_REQUEST['getLab'] == '1') {
            getLabActivity();
        } else if ($_REQUEST['getLab'] == '2') {
            getLabRevenue();
        } else {
            getLabPatientTests();
        }
        break;
    case "getLabActivities":
        getLabActivities($startDate,$endDate);
        break;
    case "getLabRevenue":
        getLabRevenue($startDate,$endDate);
        break;
    case "getLabResults":
        getLabResults($startDate,$endDate,$pid,$labTest);
        break;
    case 'getXrayActivities':
        getXrayActivities($startDate,$endDate);
        break;
    case "getXrayRevenue":
        getXrayRevenue($startDate,$endDate);
        break;
    case "getRevenueByCat":
        getRevenueByCat($startDate,$endDate,$category);
        break;
    case "getDrugStatement":
        getDrugStatement($drug,$startDate,$endDate,$start,$limit);
        break;
    case "patientdrugstatement":
        getPatientStatement();
        break;
    case "pharmacyrevenue":
        getRevenueByItem($startDate,$endDate);
        break;
    case "drugsperpatient":
        getRevenueByPatients($drug);
        break;
    case "patientstatement":
        getPatientStatement();
        break;
    case "getDiseases":
        getDiseases();
        break;
    case "getLabTests":
        getLabTests($startDate,$endDate);
        break;
    case "getDiagnosisReports":
        getDiagnosisReports($startDate,$endDate);
        break;
    case "getDistype":
        getDistype();
        break;
    case "getWards":
        getWards();
        break;
    case "getItemsList":
        getItemsList($partCode,$start,$limit);
        break;
    case "getNhifClaims":
        getNhifClaims($startDate,$endDate,$start,$limit);
        break;
    case "getClinics":
        getClinics($startDate,$endDate);
        break;
    case 'getFinalisedBills':
        getFinalisedBills($startDate,$endDate,$wardNo);
        break;
    case "mobidity":
        getMorbidity($startDate,$endDate,$reportType,$limit, $start);
        break;
    case "ipmorbidity":
        getIPMorbidity($limit, $start);
        break;
    case "ipReports":
        getIPreports();
        break;
    case "opVisits":
        getOPvisits($startDate, $endDate);
        break;
    case "create":
        createItem($item_number);
        break;
    case "adjustStock":
        stockAdjust($item_number);
        break;
    case "deleteItem":
        deleteItem();
        break;
    case "getStockMovement":
        getStockMovement($startDate,$endDate);
        break;
    case "getWardsInfo":
        getWardsInfo();
        break;
    case "getHTCReport":
        getHtcReport($startDate,$endDate);
        break;
    case "getHtcReasons":
        getHtcReasons();
        break;
    case "getWorkload":
        getWorkLoad($startDate,$endDate);
        break;
    case "getLablist":
        getLabList();
        break;
    case "getStoreLocations":
        getStoreLocations();
        break;
    case "getInventoryUsage":
        getInventoryUsage();
        break;
    case "getDrugCategories";
        getDrugCategories();
        break;
    case "getOPInvoices":
        getOPInvoices($startDate,$endDate,$pid);
        break;
    case "getPaymentPlans":
        getPaymentPlans();
        break;
    case "getTransTypes":
        getTransTypes();
        break;
    case "getDebtorCat":
        getDebtorCat();
        break;
    case "getTreatmentRegister":
        getTreatmentRegister($startDate,$endDate);
        break;
    case "getDentalServices":
        getDentalServices($startDate,$endDate);
        break;
    default:
        echo "{failure:true}";
        break;

}//end switch


function getTreatmentRegister($startDate,$endDate){
    global $db;
    $debug=false;

    $sql="SELECT distinct p.PID,ScreeningDate,PatientName,NationalID,Dob,p.Sex,MobileConsent,Mobile,
            IF(PatientLocation='',l.`citizenship`,PatientLocation) AS PatientLocation,v.`BPInitial1`,v.`BPInitial2`,
            v.`BPFirstReading1`,v.`BPFirstReading2`,v.`BPSecondReading1`,v.`BPSecondReading2`,v.`Normal`,v.`Pre_hypertensive`,
            v.`Hypertensive`,v.`Weight`,v.`Height`, `Diabetes`
            , `Smoking`, `Drinking`,v.`NewPatient`,v.`ReturnPatient`, v.`EncounterNr` FROM care_hha_patients p
            LEFT JOIN care_hha_vitals v ON p.`PID`=v.`PID`
            LEFT JOIN care_hha_questions q ON p.`PID`= q.`PID` and q.Diabetes is not null
            LEFT JOIN care_person l ON v.`PID`=l.`PID`";

    if($startDate<>'' and $endDate<>''){
        $sql.=" where v.inputdate between '$startDate' and '$endDate'";
    }

    if($debug) echo $sql;

        $result=$db->Execute($sql);
    $numRows=$result->RecordCount();
    echo '{
    "treatment":[';
    $counter=0;
    while ($row = $result->FetchRow()) {

        if($row['EncounterNr']<>'' && $row['EncounterNr']>0){
            $drugsList=getPrescriptionItems($row['EncounterNr']);
        }


        echo '{"PID":"'. $row['PID'].'","Date":"'. $row['ScreeningDate'].'","PatientName":"'. $row['PatientName'].'","NationalID":"'. $row['NationalID']
            .'","Dob":"'. $row['Dob'].'","Gender":"'. $row['Sex'] .'","MobileConsent":"'. $row['MobileConsent'].'","Mobile":"'. $row['Mobile']
            .'","Location":"'. $row['PatientLocation'].'","BPInitialReading":"'. $row['BPInitial1'].'/'.$row['BPInitial2']
            .'","BPFirstReading":"'.  $row['BPFirstReading1'].'/'.$row['BPFirstReading2'].'","BPSecondReading":"'.$row['BPSecondReading1'].'/'.$row['BPSecondReading2']
            .'","NormalBP":"'. $row['Normal'] .'","PreHypertensive":"'. $row['Pre_hypertensive'] .'","Hypertensive":"'. $row['Hypertensive']
            .'","Weight":"'.$row['Weight'].'","Height":"'. $row['Height'].'","Diabetic":"'. $row['Diabetes'].'","Smoker":"'. $row['Smoking']
            .'","Alcohol":"'. $row['Drinking'].'","NewPatient":"'. $row['NewPatient'].'","ReturningPatient":"'. $row['ReturnPatient'].'","Medication":"'. $drugsList. '"}';
       
        $counter++;
        if ($counter<>$numRows){
            echo ",";
        }
        
    }
    echo ']}';
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

function getDebtorCat(){
    global $db;
    $debug=false;
        
    $sql="SELECT code as ID,name as Category FROM `care_ke_debtorcat`";
    if($debug) echo $sql;

    $results=$db->Execute($sql);
    $total = $results->RecordCount();

    echo '{"total":"' . $total . '","debtorCats":[';
    $counter = 0;
    while ($row = $results->FetchRow()) {
        echo '{"ID":"' . $row['ID'].'","Category":"' . $row['Category'] . '"}';
        $counter++;
        if ($counter < $total) {
            echo ",";
        }
    }
    echo ']}';
}

function getTransTypes(){
    global $db;
    $debug=false;
        
    $sql="SELECT typeid,typeName FROM `care_ke_transactionnos` WHERE TYPE=1";
    if($debug) echo $sql;

    $results=$db->Execute($sql);
    $total = $results->RecordCount();

    echo '{"total":"' . $total . '","movetypes":[';
    $counter = 0;
    while ($row = $results->FetchRow()) {
        echo '{"typeID":"' . $row['typeid'].'","typeName":"' . $row['typeName'] . '"}';
        $counter++;
        if ($counter < $total) {
            echo ",";
        }
    }
    echo ']}';
}

function getPaymentPlans(){
    global $db;
    $debug=false;
        
    $sql="SELECT DISTINCT insurance_id,IF(insurance_id='-1','CASH',c.`name`) AS name FROM care_person p LEFT JOIN care_tz_company c
                ON p.`insurance_ID`=c.`id` WHERE p.`insurance_ID` IS NOT NULL order by name asc";
    
    if($debug) echo $sql;

    $results=$db->Execute($sql);
    $total = $results->RecordCount();

    echo '{"total":"' . $total . '","paymentplans":[';
    $counter = 0;
    while ($row = $results->FetchRow()) {
        $insuname= escapeJsonString($row['name']);
        echo '{"InsuranceID":"' . $row['insurance_id'].'","Name":"' . $insuname . '"}';
        $counter++;
        if ($counter < $total) {
            echo ",";
        }
    }
    echo ']}';
}


function getOPInvoices($startDate,$endDate,$pid){
    global $db;
    $debug=false;
    $paymentPlan=$_REQUEST['paymentPlan'];
    $debtorCat=$_REQUEST['debtorCat'];
        
    $sql="SELECT b.pid,encounter_nr,c.accno,CONCAT(p.`name_first`,' ',p.`name_last`,' ',p.`name_2`) AS pnames,b.bill_number,b.bill_date,SUM(b.total) AS Total,
            IF(p.`insurance_ID`='-1','CASH',c.`name`) AS PaymentMethod   FROM care_ke_billing b 
            LEFT JOIN care_person p ON b.`pid`=p.`pid`  LEFT JOIN `care_tz_company` c ON p.`insurance_ID`=c.`id`
            WHERE b.pid<>''";
       
    if($startDate<>'' and $endDate<>''){
        $sql=$sql." and b.`bill_date` BETWEEN '$startDate' AND '$endDate'";
    }else{
         $sql=$sql." and b.`bill_date`='".date('Y-m-d')."'";
    }
    
    if($pid<>''){
        $sql=$sql." and b.pid='$pid'";
    }
    
    if($paymentPlan<>''){
        $sql=$sql." and p.insurance_ID='$paymentPlan'";
    }
    
    if($debtorCat<>''){
        $sql=$sql." and c.category='$debtorCat'";
    }
        
    $sql=$sql." GROUP BY b.pid ";
    if($debug) echo $sql;
    $results=$db->Execute($sql);
    $total = $results->RecordCount();

    echo '{"total":"' . $total . '","invoices":[';
    $counter = 0;
    while ($row = $results->FetchRow()) {
        echo '{"Accno":"' . $row['accno'].'","Pid":"' . $row['pid'].'","EncounterNo":"' . $row['encounter_nr'] .'","Names":"' . $row['pnames'].'","BillNumber":"' . $row['bill_number']
                .'","Amount":"' . number_format($row['Total'],2).'","BillDate":"' . $row['bill_date'].'","PaymentMethod":"' . $row['PaymentMethod']. '"}';
        $counter++;
        if ($counter < $total) {
            echo ",";
        }
    }
    echo ']}';
}

function getDrugCategories(){
    global $db;
    $debug=false;
        
    $sql="SELECT DISTINCT  d.`category`,c.`item_Cat` FROM care_tz_drugsandservices d
        LEFT JOIN care_tz_itemscat c ON d.`category`=c.`catID` WHERE purchasing_class='drug_list'";
    
    if($debug) echo $sql;

    $results=$db->Execute($sql);
    $total = $results->RecordCount();

    echo '{"total":"' . $total . '","category":[';
    $counter = 0;
    while ($row = $results->FetchRow()) {
        echo '{"CatID":"' . $row['category'].'","Category":"' . $row['item_Cat'] . '"}';
        $counter++;
        if ($counter < $total) {
            echo ",";
        }
    }
    echo ']}';
}

function getMonthlyUsage(){
    global $db;
    $debug=false;
    
     echo '{"total":"12","usage":[';
    $counter = 0;
    for($m=1; $m<=12; ++$m){
        $strMonth=date('F', mktime(0, 0, 0, $m, 1));
        echo '{"PartCode":"' . $strMonth.'","PartCode":"' . $strMonth .'","January":"' . $strMonth.'","February":"' . $strMonth.'","March":"' . $strMonth
                .'","April":"' . $strMonth.'","May":"' . $strMonth.'","June":"' . $strMonth.'","July":"' 
                . $strMonth.'","August":"' . $strMonth.'","September":"' . $strMonth.'","October":"' . $strMonth
                .'","November":"' . $strMonth.'","December":"' . $strMonth.'"}';
        $counter++;
        if ($counter < 12) {
            echo ",";
        }
    }
    
    echo ']}';
}

function getInventoryUsage(){
    global $db;
    $debug=false;
    
     echo '{"total":"12","usage":[';
    $counter = 0;
    for($m=1; $m<=12; ++$m){
        $strMonth=date('F', mktime(0, 0, 0, $m, 1));
        echo '{"Month":"' . $strMonth. '"}';
        $counter++;
        if ($counter < 12) {
            echo ",";
        }
    }
    echo ']}';
}

function getStoreLocations(){
    global $db;
    $debug=false;
    
    $sql="select st_id,st_name from care_ke_stlocation where store=1";
     if($debug) echo $sql;

    $results=$db->Execute($sql);
    $total = $results->RecordCount();

    echo '{"total":"' . $total . '","locations":[';
    $counter = 0;
    while ($row = $results->FetchRow()) {
        echo '{"LocCode":"' . $row['st_id'].'","Description":"' . $row['st_name'] . '"}';
        $counter++;
        if ($counter < $total) {
            echo ",";
        }
    }
    echo ']}';
    
}

function getTotalPrescriptions($clinician,$startDate,$endDate){
    global $db;
    $debug=false;
    
    $sql="SELECT COUNT(encounter_nr) AS Total_prescriptions FROM `care_encounter_prescription`
            WHERE `prescribe_date` BETWEEN '$startDate' AND '$endDate' AND `prescriber`='$clinician'
            AND drug_class='Drug_list' GROUP BY `prescriber`";
    
    if($debug) echo $sql;
    $results=$db->Execute($sql);
    $row = $results->FetchRow();
    
    return $row['Total_prescriptions'];
}

function getTotalXrays($clinician,$startDate,$endDate){
    global $db;
    $debug=false;
    
    $sqlXray="SELECT COUNT(encounter_nr) AS Total_xrays FROM `care_test_request_radio`
            WHERE `send_date` BETWEEN '$startDate' AND '$endDate' AND create_id='$clinician'
            GROUP BY create_id";
    
    if($debug) echo $sqlXray;
    $results=$db->Execute($sqlXray);
    $row = $results->FetchRow();
    
    return $row['Total_xrays'];
}

function getTotalLabs($clinician,$startDate,$endDate){
    global $db;
    $debug=false;
    
    $sqlNotes="SELECT COUNT(encounter_nr) AS Total_Labs FROM `care_test_request_chemlabor` 
            WHERE `send_date` BETWEEN '$startDate' AND '$endDate' AND create_id='$clinician'
            GROUP BY create_id";
    
    if($debug) echo $sqlNotes;
    $results=$db->Execute($sqlNotes);
    $row = $results->FetchRow();
    
    return $row['Total_Labs'];
}

function getTotalDiagnosis($clinician,$startDate,$endDate){
    global $db;
    $debug=false;
    
    $sqlNotes="SELECT COUNT(encounter_nr) AS Total_Diagnosis FROM `care_tz_diagnosis` WHERE `timestamp` 
                BETWEEN '$startDate' AND '$endDate' and doctor_name='$clinician' GROUP BY `doctor_name`";
    
    if($debug) echo $sqlNotes;
    $results=$db->Execute($sqlNotes);
    $row = $results->FetchRow();
    
    return $row['Total_Diagnosis'];
}

function getNotes($clinician,$startDate,$endDate){
    global $db;
    $debug=false;
    
    $sqlNotes="SELECT COUNT(encounter_nr) AS Total_Notes FROM `care_encounter_notes` 
            WHERE DATE BETWEEN '$startDate' AND '$endDate' and create_id='$clinician'
            GROUP BY create_id";
    
     if($debug) echo $sqlNotes;

    $results=$db->Execute($sqlNotes);

    $row = $results->FetchRow();
    
    return $row['Total_Notes'];
    
}

function getWorkLoad($startDate,$endDate){
    global $db;
    $debug=false;
    $sqlNotes="SELECT create_id FROM `care_encounter_notes` 
            WHERE DATE BETWEEN '$startDate' AND '$endDate'
            GROUP BY create_id";
    if($debug) echo $sqlNotes;
    $results=$db->Execute($sqlNotes);
    $total = $results->RecordCount();

    echo '{
    "total":"' . $total . '","workload":[';
    $counter = 0;
    while ($row = $results->FetchRow()) {
        $notes= getNotes($row['create_id'],$startDate,$endDate);
        $diagnosis= getTotalDiagnosis($row['create_id'],$startDate,$endDate);
        $prescription= getTotalPrescriptions($row['create_id'],$startDate,$endDate);
        $labs=getTotalLabs($row['create_id'],$startDate,$endDate);
        $xray=getTotalXrays($row['create_id'],$startDate,$endDate);
        
        echo '{"Clinician":"' . $row['create_id'] . '","Notes":"' . $notes. '","Diagnosis":"' . $diagnosis
             . '","Labs":"' . $labs. '","Prescription":"' . $prescription. '","Xray":"' . $xray. '"}';
        $counter++;
        if ($counter < $total) { echo ","; }
    }
    echo ']}';
}

function getHtcReasons(){
    global $db;
    $debug=false;

    $sql="Select ID,Description from care_ke_Reasons where rType='Htc'";
    if($debug) echo $sql;

    $results=$db->Execute($sql);
    
      $request = $db->Execute($sql);

        $total = $request->RecordCount();

    echo '{
    "total":"' . $total . '","htcreason":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {
        echo '{"Description":"' . $row['Description'] . '"}';
        if ($counter < $total) {
            echo ",";
        }
        $counter++;
    }
    echo ']}';
}

function getHTCReport($startDate,$endDate){
    global $db;
    $debug=false;
    $htcs=$_REQUEST['htcs'];
    $htcsReason=$_REQUEST['htcReason'];
    
    $sql="SELECT encounter_nr,msr_date AS HtcDate,VALUE AS OPT,notes
           FROM care_encounter_measurement WHERE msr_type_nr=12";
    
    if($startDate<>"" && $endDate<>""){
        $sql=$sql." and msr_date between '$startDate' and '$endDate'";
    }
    
    if($htcs<>""){
        $sql=$sql." and VALUE='$htcs'";
    }
        
    if($htcsReason<>""){
        $sql=$sql." and notes='$htcsReason'";
    }
    
    if($debug) echo $sql;
    
      $request = $db->Execute($sql);

        $total = $request->RecordCount();

    echo '{
    "total":"' . $total . '","htc":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {
        echo '{"EncounterNr":"' . $row['encounter_nr'] . '","HtcDate":"' . $row['HtcDate'] . '","OPT":"' . $row['OPT'] 
                . '","Reason":"' . $row['notes']. '"}';
        if ($counter < $total) {
            echo ",";
        }
        $counter++;
    }
    echo ']}';
}

function getStockMovement($startDate,$endDate) {
    global $db;
    $debug=false;

    $LocCode = $_REQUEST['locCode'];
    $PartCode = $_REQUEST['partCode'];
    $category= $_REQUEST['category'];
    $location= $_REQUEST['location'];
    $location2= $_REQUEST['location2'];
    $transType= $_REQUEST['transType'];
    
   $sql="select `stkmoveno`,`stockid`,d.`item_description`,d.`unit_measure`,t.`typeName`,s.`transno`,`loccode`,`supLoccode`,`trandate`,`pid`,
            `price`,`qty`,`newqoh`,`hidemovt`,`narrative`,`inputuser` 
          from  `care_ke_stockmovement` s left join care_tz_drugsandservices d on s.`stockid`=d.`partcode`
          left join care_ke_transactionnos t on s.`type`=t.`typeID` where partcode<>''";

        if($PartCode<>''){
            $sql=$sql." and stockid='$PartCode'";
        }
               
        if($startDate<>"" && $endDate<>""){
                $sql=$sql." and trandate between '$startDate' and '$endDate'";
        }
        if($category<>''){
            $sql=$sql." AND d.`category`='$category'";
        }
        
        if($transType<>''){
            $sql=$sql." and s.type='$transType'";
        }
        
//        if($location<>'' && $location2==''){
//            $sql=$sql." and supLoccode='$location'";
//        }elseif($location=='' && $location2<>''){
//            $sql=$sql." and loccode='$location2'";
//        }elseif($location<>'' && $location2<>''){
//             $sql=$sql." and supLoccode='$location' AND loccode='$location2'";
//        }
        
        
        if($debug) echo $sql;
        $request = $db->Execute($sql);

        $total = $request->RecordCount();

    echo '{
    "total":"' . $total . '","stockmovement":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {
        $description= escapeJsonString($row['item_description']);
        echo '{"PartCode":"' . $row['stockid'] . '","Description":"' . $description . '","UnitsMeasure":"' . $row['unit_measure'] 
                . '","Date":"' . $row['trandate'] . '","TransType":"' . $row['typeName']. '","TransNo":"' . $row['transno']
                . '","Narration":"' . $row['narrative']. '","Location":"' . $row['loccode']. '","Cost":"' . $row['price']
                . '","Qty":"' . $row['qty']. '","Level":"' . $row['newqoh']. '","Operator":"' . $row['inputuser']. '"}';
        $counter++;
        if ($counter < $total) {
            echo ",";
        }
        
    }
    echo ']}';
}

function getMorbidity($date1,$date2,$reportType,$limit, $start) {
    global $db;
    //$strMonth=date(c.timestamp);
    $debug=false;

//    $date1='2018-01-01';
//    $date2='2018-03-31';
    echo '{
   "mobidity":[';

    $sql="SELECT `ID`,`ReportMonth`,`DateUpdated`,`ICDCode`,`Disease`,`1`,`2`,`3`,`4`,`5`,`6`,`7`,`8`,`9`,`10`,`11`,`12`,`13`,`14`,
            `15`,`16`,`17`,`18`,`19`,`20`,`21`,`22`,`23`,`24`,`25`,`26`,`27`,`28`,`29` `30`,`31` 
            FROM `care_ke_opmobidity` m LEFT JOIN care_icd10_en i ON m.ICDCode=i.diagnosis_code
            where DateUpdated between '$date1' and '$date2' and i.type='$reportType'";
    if($debug) echo $sql;
    $results=$db->Execute($sql);

       while($row = $results->FetchRow()){
           $disease= $desc= escapeJsonString($row['Disease']);
           echo '{"icdCode":"' . $row['ICDCode'] . '","disease":"' . $disease . '",';
           $totals=0;
           for ($i = 1; $i <= 31; $i++) {
                echo '"'.$i.'":"' . $row[$i] .'",';
                $totals=$totals+$row[$i];
            }
           echo '"TOTALS":"' . $totals. '"},';
       }

    echo ']}';
}


function getMorbidityOld($date1,$date2,$reportType,$limit, $start) {
    global $db;
    //$strMonth=date(c.timestamp);

    echo '{
   "mobidity":[';

    $sql="select diagnosis_code,Description from care_icd10_en where type='$reportType' order by diagnosis_code asc";
//    echo $sql;
    $results=$db->Execute($sql);

       while($row = $results->FetchRow()){
           $disease= $desc= escapeJsonString($row[1]);
           echo '{"icdCode":"' . $row[0] . '","disease":"' . $disease . '",';
           $totals=0;
           for ($i = 1; $i <= 31; $i++) {
               $rcount= getMobidityCounts($row[0],$reportType,$date1,$date2,$i);

//               if($row[0]=='OP63' ||$row[0]=='OPC62'){
//                   $rcount=getSummobidityPerDay($date1,$date2,$i,$reportType);
//               }
                echo '"'.$i.'":"' . $rcount .'",';
               $totals=$totals+$rcount;
            }
           echo '"TOTALS":"' . $totals. '"},';
       }

    echo ']}';
}

function getSummobidityPerDay($date1,$date2,$reportDay,$reportType){
    global $db;
    $debug=false;

    if($reportDay<10) $reportDay='0'.$reportDay;

    $sql="Select Count(b.`diagnosis_code`) as totalCases from  care_icd10_en b left join care_tz_diagnosis c
            on b.diagnosis_code=c.ICD_10_code where c.timestamp between '$date1' and '$date2'
            and DATE_FORMAT(c.timestamp,'%d')='$reportDay' AND b.class_sub='$reportType'";

    if($debug) echo $sql;

    $results=$db->Execute($sql);
    $row=$results->FetchRow();

     return $row[0];
}


 function getMobidityCounts($rcode,$reportType,$date1,$date2,$reportDay){
     global $db;
     $debug=false;
     
     if($reportDay<10) $reportDay='0'.$reportDay;

     $sql1 = "select COUNT(b.`diagnosis_code`) AS rcount
        from care_icd10_en b left join care_tz_diagnosis c
            on b.diagnosis_code=c.ICD_10_code where b.type='$reportType' and DATE_FORMAT(c.timestamp,'%Y-%m-%d') between '$date1' and '$date2'
            and DATE_FORMAT(c.timestamp,'%d')='$reportDay' and b.diagnosis_code='OP67' group by b.diagnosis_code";

    if($debug) echo $sql1;
     
     $result1 = $db->Execute($sql1);
     $row=$result1->FetchRow();

//     if($row[rcount]<>''){
//         return $row[rcount];
//     }else{
         return '0';
//     }

 }

function doCount($icdcode, $min, $max, $sign) {
    global $db;
    if ($sign == "<") {
        $sql = 'select count(b.ICD_10_code) as rcount from care_tz_diagnosis b inner join care_icd10_en k
        on k.diagnosis_code=b.ICD_10_code
            inner join care_person c on c.pid=b.PID  where k.type<>"OP" and (year(now())-year(c.date_birth))<1 and b.ICD_10_code="' . $icdcode . '"';
    } elseif ($sign == 'Between') {
        $sql = 'select count(b.ICD_10_code) as rcount from care_tz_diagnosis b inner join care_icd10_en k
        on k.diagnosis_code=b.ICD_10_code
            inner join care_person c on c.pid=b.PID  where k.type<>"OP" and (year(now())-year(c.date_birth))
            BETWEEN "' . $min . '" and "' . $max . '" and b.ICD_10_code="' . $icdcode . '"';
    } else {
        $sql = 'select count(b.ICD_10_code) as rcount from care_tz_diagnosis b inner join care_icd10_en k
        on k.diagnosis_code=b.ICD_10_code
            inner join care_person c on c.pid=b.PID  where k.type<>"OP" and
            (year(now())-year(c.date_birth))>65 and b.ICD_10_code="' . $icdcode . '"';
    }
   //echo $sql;
    $result = $db->Execute($sql);
    $row = $result->FetchRow();
    return $row[0];
}

function getIPMorbidity($limit, $start) {
    global $db;
    $sql = 'select icd_10_code,icd_10_description,count(icd_10_code) as dcount from care_tz_diagnosis 
               where icd_10_code NOT LIKE "OP%"';

    $dt1 = $_REQUEST['date1'];

    $startDate = new DateTime($dt1);
    $date1 = $startDate->format("Y-m-d");

    $dt2 = $_REQUEST['date2'];
    $endDate = new DateTime($dt2);
    $date2 = $endDate->format("Y-m-d");

    if ($dt1 <> '' && $dt2 <> '') {
        $sql = $sql . "  and `timestamp` between '$date1' and '$date2'";
    }

    $sql = $sql . "  group by icd_10_code";

    $result1 = $db->Execute($sql);
    // echo $sql;

    $data1 = '';
    $count = 0;
    while ($row1 = $result1->FetchRow()) {
        $data1[1][] = $row1[0];
        $data1[2][] = $row1[1];
        $count = $count + 1;
    }

    echo '{
            "Total":' . $count . ',"ipmorbidity":[';

    $counter = 0;
    for ($i = 0; $i < $count; $i++) {
        echo '{"icdcode":"' . $data1[1][$i] . '","desc":"' . trim($data1[2][$i]) . '",';

//        $A1sum = 0;
//             for($i=0;$i<$j;$i++){
        $FA1sum = getIpmobidityCount(1, 1, 'FA', $data1[1][$i], '<', $date1, $date2,'F');
        $FD1sum = getIpmobidityCount(1, 1, 'FD', $data1[1][$i], '<', $date1, $date2,'F');
        $FA2sum = getIpmobidityCount(1, 4, 'FA', $data1[1][$i], 'between', $date1, $date2,'F');
        $FD2sum = getIpmobidityCount(1, 4, 'FD', $data1[1][$i], 'between', $date1, $date2,'F');
        $FA3sum = getIpmobidityCount(5, 14, 'FA', $data1[1][$i], 'between', $date1, $date2,'F');
        $FD3sum = getIpmobidityCount(5, 14, 'FD', $data1[1][$i], 'between', $date1, $date2,'F');
        $FA4sum = getIpmobidityCount(15, 44, 'FA', $data1[1][$i], 'between', $date1, $date2,'F');
        $FD4sum = getIpmobidityCount(15, 44, 'FD', $data1[1][$i], 'between', $date1, $date2,'F');
        $FA5sum = getIpmobidityCount(45, 45, 'FA', $data1[1][$i], '>', $date1, $date2,'F');
        $FD5sum = getIpmobidityCount(45, 45, 'FD', $data1[1][$i], '>', $date1, $date2,'F');
        $MA6sum = getIpmobidityCount(1, 1, 'MA', $data1[1][$i], '<', $date1, $date2,'M');
        $MD6sum = getIpmobidityCount(1, 1, 'MD', $data1[1][$i], '<', $date1, $date2,'M');
        $MA7sum = getIpmobidityCount(1, 4, 'MA', $data1[1][$i], 'between', $date1, $date2,'M');
        $MD7sum = getIpmobidityCount(1, 4, 'MD', $data1[1][$i], 'between', $date1, $date2,'M');
        $MA8sum = getIpmobidityCount(5, 14, 'MA', $data1[1][$i], 'between', $date1, $date2,'M');
        $MD8sum = getIpmobidityCount(5, 14, 'MA', $data1[1][$i], 'between', $date1, $date2,'M');
        $MA9sum = getIpmobidityCount(15, 44, 'MA', $data1[1][$i], 'between', $date1, $date2,'M');
        $MD9sum = getIpmobidityCount(15, 44, 'MD', $data1[1][$i], 'between', $date1, $date2,'M');
        $MA10sum = getIpmobidityCount(45, 45, 'MA', $data1[1][$i], '>', $date1, $date2,'M');
        $MD10sum = getIpmobidityCount(45, 45, 'MD', $data1[1][$i], '>', $date1, $date2,'M');

        $totalA=$FA1sum+$FA2sum+$FA3sum+$FA4sum+$FA5sum+$MA6sum+$MA7sum+$MA8sum+$MA9sum+$MA10sum;
        $totalD=$FD1sum+$FD2sum+$FD3sum+$FD4sum+$FD5sum+$MD6sum+$MD7sum+$MD8sum+$MD9sum+$MD10sum;

        $totals=$totalA+$totalD;

        echo '"FA1":"' . $FA1sum . '","FD1":"' . $FD1sum . '","FA2":"' . $FA2sum . '","FD2":"' . $FD2sum . '",
                    "FA3":"' . $FA3sum . '","FD3":"' . $FD3sum . '","FA4":"' . $FA4sum . '","FD4":"' . $FD4sum . '",
                    "FA5":"' . $FA5sum . '","FD5":"' . $FD5sum . '","MA6":"' . $MA6sum . '","MD6":"' . $MD6sum . '",
                    "MA7":"' . $MA7sum . '","MD7":"' . $MD7sum . '","MA8":"' . $MA8sum . '","MD8":"' . $MD8sum . '",
                    "MA9":"' . $MA9sum . '","MD9":"' . $MD9sum . '","MA10":"' . $MA10sum . '","MD10":"' . $MD10sum . '",
                    "Alive":"' . $totalA . '","Dead":"' . $totalD. '","Totals":"' . $totals . '"}';

        $counter++;

        if ($i <$counter) {
            echo ',';
        }

    }


    echo ']}';
}

function getIpmobidityCount($age1, $age2, $patientstatus, $icdCode, $sign, $date1, $date2,$sex) {
    global $db;
    $debug = false;
    $ptstatus=substr($patientstatus,1);
    $sql = "select count(b.PID) as pcount from care_tz_diagnosis k inner join
            care_person b on k.pid=b.pid  inner join care_icd10_en e on e.diagnosis_code=k.ICD_10_code
            where k.icd_10_code NOT LIKE 'OP%' AND k.ICD_10_code='$icdCode'
            and k.pataintStatus='$ptstatus'";
    if ($sign == 'between') {
        $sql = $sql . " and (year(now())-year(b.date_birth)) between $age1 and $age2";
    } else if ($sign == '>') {
        $sql = $sql . " and (year(now())-year(b.date_birth)) > $age1";
    } else if ($sign == '<') {
        $sql = $sql . " and (year(now())-year(b.date_birth)) < $age1";
    }

    if($sex){
        $sql=$sql ." and b.sex='$sex'";
    }

    if ($date1 <> '' && $date2 <> '') {
        $sql = $sql . " and `timestamp` between '$date1' and '$date2'";
    }

    if ($debug)
        echo $sql;

    $result = $db->Execute($sql);
    $row = $result->FetchRow();

    return $row[0];
}

function getOPvalues($age, $sex, $clinic, $sign, $encDate1, $encDate2, $sign2) {
    global $db;
    $debug =false;
    if ($sign2 == "=") {

        $sql = "select count(e.encounter_nr) as encCounter,e.pid from care_encounter e 
                left join care_person p on e.pid=p.pid
                LEFT JOIN care_encounter_location l ON e.`encounter_nr`=l.`encounter_nr`
                where  l.location_nr='$clinic' and encounter_date between '$encDate2' and '$encDate1'";

        if ($age) {
            $sql = $sql . " AND (YEAR(NOW())-YEAR(p.date_birth))$sign $age";
        }


        if ($sex) {
            $sql = $sql . " and p.sex='$sex'";
        }

        $sql = $sql . " and e.encounter_class_nr=2 group by pid";
        if ($debug)
            echo $sql;
        $result = $db->Execute($sql);
//            $row=$result->FetchRow();
        $counter = 0;
        while ($row = $result->FetchRow()) {
            $sql3 = "select count(e.pid) as prevPid from care_encounter e 
                    where encounter_date<='$encDate2'
                    and e.pid=$row[1]";
            if ($debug)
                echo $sql3;
            $result3 = $db->Execute($sql3);
            $row3 = $result3->FetchRow();
            if ($row3[0] == 1) {
                $counter = $counter + 1;
            }
        }
        $pcount = $counter;
    } else {
        $sql = "select e.pid,count(e.encounter_nr) as encCounter,e.pid from care_encounter e  
                    left join care_person p on e.pid=p.pid
                    where e.current_dept_nr='$clinic' and encounter_date between '$encDate2' and '$encDate1'";

        if ($age) {
            $sql = $sql . " AND (YEAR(NOW())-YEAR(p.date_birth))$sign $age";
        }
        if ($sex) {
            $sql = $sql . " and p.sex='$sex'";
        }

        $sql = $sql . " and e.encounter_class_nr=2 group by pid";
        if ($debug)
            echo $sql;
        $result = $db->Execute($sql);
//            $row=$result->FetchRow();
        $counter = 0;
        while ($row = $result->FetchRow()) {
            $sql3 = "select e.pid from care_encounter e 
                        where encounter_date<'$encDate1'
                        and e.pid=$row[0]";
            if ($debug)
                echo $sql3;
            $result3 = $db->Execute($sql3);
            $row3 = $result3->FetchRow();
            if ($row3[0] > 1) {
                $counter = $counter + 1;
            }
        }
        $pcount = $counter;
    }

    return $pcount;
}

function getOPvisits($date1,$date2) {
    global $db;

    $sql = 'SELECT parent,opCode,description FROM care_ke_opvisitsvars where parent like "A%"';
    $result = $db->Execute($sql);
    $data = '';
    while ($row = $result->FetchRow()) {
        $data[1][] = $row[1];
        $data[2][] = $row[2];
    }

    $sql = 'SELECT parent,opCode,description FROM care_ke_opvisitsvars where parent like "A%"';
    $result = $db->Execute($sql);
    echo '{"opVisits":[';
    $data[][] = '';
    while ($row = $result->FetchRow()) {
        if ($data[1][1] == $row[1]) {
            $newVar = getOPvalues("5", "m", "40", ">", $date1, $date2, "="); //Over 5-Male
            $retVar = getOPvalues("5", "m", "40", ">", $date1, $date2, ">"); //Over 5-Male
        } elseif ($data[1][2] == $row[1]) {
            $newVar = getOPvalues("5", "f", "40", ">", $date1, $date2, '=');
            $retVar = getOPvalues("5", "f", "40", ">", $date1, $date2, ">");
        } elseif ($data[1][3] == $row[1]) {
            $newVar = getOPvalues("5", "m", "40", "<=", $date1, $date2, '=');
            $retVar = getOPvalues("5", "m", "40", "<=", $date1, $date2, ">");
        } elseif ($data[1][4] == $row[1]) {
            $newVar = getOPvalues("5", "f", "40", "<=", $date1, $date2, '=');
            $retVar = getOPvalues("5", "f", "40", "<=", $date1, $date2, ">");
        } elseif ($data[1][5] == $row[1]) {
            $newVar = getOPvalues("0", "", "40", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "40", ">", $date1, $date2, ">");
        } elseif ($data[1][6] == $row[1]) {
            $newVar = getOPvalues("0", "", "52", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "52", ">", $date1, $date2, ">");
        } elseif ($data[1][7] == $row[1]) {
            $newVar = getOPvalues("0", "", "6", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "6", ">", $date1, $date2, ">");
        } elseif ($data[1][8] == $row[1]) {
            $newVar = getOPvalues("0", "", "7", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "7", ">", $date1, $date2, ">");
        } elseif ($data[1][9] == $row[1]) {
            $newVar = getOPvalues("0", "", "47", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "47", ">", $date1, $date2, ">");
        } elseif ($data[1][10] == $row[1]) {
            $newVar = getOPvalues("0", "", "53", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "53", ">", $date1, $date2, ">");
        } elseif ($data[1][11] == $row[1]) {
            $newVar = getOPvalues("0", "", "54", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "54", ">", $date1, $date2, ">");
        } elseif ($data[1][12] == $row[1]) {
            $newVar = getOPvalues("0", "", "44", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "44", ">", $date1, $date2, ">");
        } elseif ($data[1][13] == $row[1]) {
            $newVar = getOPvalues("0", "", "57", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "57", ">", $date1, $date2, ">");
        } elseif ($data[1][14] == $row[1]) {
            $newVar = getOPvalues("0", "", "56", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "56", ">", $date1, $date2, ">");
        } elseif ($data[1][15] == $row[1]) {
            $newVar = getOPvalues("0", "", "62", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "62", ">", $date1, $date2, ">");
        } elseif ($data[1][16] == $row[1]) {
            $newVar = getOPvalues("0", "", "48", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "48", ">", $date1, $date2, ">");
        } elseif ($data[1][17] == $row[1]) {
            $newVar = getOPvalues("0", "", "55", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "55", ">", $date1, $date2, ">");
        } elseif ($data[1][18] == $row[1]) {
            $newVar = getOPvalues("0", "", "49", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "49", ">", $date1, $date2, ">");
        } elseif ($data[1][19] == $row[1]) {
            $newVar = getOPvalues("0", "", "43", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "43", ">", $date1, $date2, ">");
        } else {
            $newVar = 0;
            $retVar = 0;
        }

        echo '{"parent":"' . $row[0] . '","opCode":"' . $row[1] . '",
"Description":"' . $row[2] . '","New":"' . number_format($newVar,0) . '","Ret":"' . number_format($retVar,0) . '","Total":"' . intval($newVar + $retVar) . '"},';
    }

    echo ']}';
}

function getIPvalues($age, $sign, $sign2, $service, $ward) {
    global $db;
    $debug = false;

    $sql = 'SELECT COUNT(a.encounter_nr) AS pcount FROM care_encounter a 
LEFT JOIN care_person b ON a.pid=b.pid
LEFT JOIN care_encounter_location c ON a.encounter_nr=c.encounter_nr
LEFT JOIN care_type_discharge d ON c.discharge_type_nr=d.nr
WHERE a.encounter_class_nr=1 AND a.is_discharged=1 
AND (YEAR(NOW())-YEAR(b.date_birth))' . $sign . '"' . $age . '" AND 
c.discharge_type_nr' . $sign2 . '"' . $service . '" AND a.current_ward_nr in (' . $ward . ')';

    if ($debug)
        echo $sql;
    $result = $db->Execute($sql);
    if ($debug)
        echo $sql;
    if ($row = $result->FetchRow()) {
        return $row[0];
    } else {
        return false;
    }
}

function getIPreports() {
    global $db;

    $sql = 'SELECT parent,opCode,description FROM care_ke_opvisitsvars where parent in("B1","B2")';
    $result = $db->Execute($sql);
    while ($row = $result->FetchRow()) {
        $data[1][] = $row[1];
        $data[2][] = $row[2];
    }


    $sql = 'SELECT parent,opCode,description FROM care_ke_opvisitsvars where parent in("B1","B2")';
    $result = $db->Execute($sql);
    echo '{"ipReports":[';
    while ($row = $result->FetchRow()) {
        for ($i = 1; $i < 14; $i++) {
            if ($data[1][$i] == $row[1]) {
//                getIPvalues($age,$sign, $sign2, $service)
                $newVar = getIPvalues("5", ">", "=", "1", "1,2,3,4,6");
                $retVar = getIPvalues("5", "<", "=", "1", "1,2,3,4,6");
                $matVar = getIPvalues("5", ">", "=", "1", "5");
                $amVar = getIPvalues("5", ">", "=", "1", "1,2,3,4,6");
            } else {
                $newVar = 0;
                $retVar = 0;
            }
        }
        $fDesc = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[2]);

        echo '{"parent":"' . $row[0] . '","opCode":"' . $row[1] . '",
"Description":"' . $fDesc . '","GenAdults":"' . $newVar . '","GenPaed":"' . $retVar . '",
"Martmoms":"' . $matVar . '","Amenity":"' . $amVar . '","Total":"' . intval($newVar + $retVar + $matVar + $amVar) . '"},';
    }

    echo ']}';
}

function getFinalisedBills($startDate,$endDate,$wardNo){
    global $db;
    $debug=false;

    $sql = "SELECT b.pid,p.`selian_pid` AS fileNo,b.encounter_nr,e.`encounter_class_nr`,CONCAT(p.`name_first`,' ',p.name_last,' ',p.`name_2`) AS `names`,b.bill_number
,e.`is_discharged`,e.`encounter_date` AS admissionDate,e.`discharge_date`,e.finalised,w.`description`,w.`nr`,b.finaliseby
FROM care_ke_billing b LEFT JOIN care_person p ON b.`pid`=p.`pid`  
LEFT JOIN care_encounter e ON b.`encounter_nr`=e.`encounter_nr`
LEFT JOIN care_ward w ON e.current_ward_nr=w.nr
WHERE e.finalised=1 AND e.`encounter_class_nr`=1 AND e.`is_discharged`=1 ";

    if($startDate<>'' && $endDate<>''){

        $sql=$sql." and e.discharge_date between '$startDate' and '$endDate'";
    }

    if($wardNo){

        $sql=$sql." and w.nr='$wardNo'";
    }

    $sql=$sql." GROUP BY encounter_nr";

    if($debug) echo $sql;
	
    $result=$db->Execute($sql);
    $total=$result->RecordCount();

    echo '{
    "total":"' . $total . '","finalisedBills":[';
    $counter=0;

    while($row=$result->FetchRow()){
        echo '{"pid":"' . $row['pid'] .'","fileNo":"' . $row['fileNo'].'","names":"' . $row['names'] .'","billnumber":"' . $row['bill_number']
            .'","admissionDate":"' . $row['admissionDate'].'","dischargeDate":"' . $row['discharge_date'].'","ward":"' . $row['description']
            .'","finalisedBy":"' . $row['finaliseby'].'"}';

        $counter++;

        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']}';
}

function getNhifClaims($startDate,$endDate,$start,$limit){
    global $db;
    $debug=false;

    $sql = "SELECT b.creditNo,b.inputDate,b.admno,b.Names,b.admDate,b.disDate,b.wrdDays,b.nhifNo,b.nhifDebtorNo,
        b.debtorDesc,b.invAmount,b.totalCredit,b.balance,b.bill_number
        FROM care_ke_nhifcredits b ";

    if($startDate && $endDate){
        $sql=$sql."WHERE b.inputDate between '$startDate' and '$endDate'";
    }

    $sql=$sql. " limit $start,$limit";

    if($debug) echo $sql;
    $result=$db->Execute($sql);
    $total=$result->RecordCount();

    echo '{
    "total":"' . $total . '","nhifclaims":[';
    $counter=0;

    while($row=$result->FetchRow()){
        echo '{"ClaimNo":"' . $row['creditNo'] .'","BillNumber":"' . $row['bill_number'].'","NHIFNo":"' . $row['nhifNo'] .'","PID":"' . $row['admno']
            .'","Names":"' . $row['Names'].'","AdmissionDate":"' . $row['admDate'].'","DischargeDate":"' . $row['disDate'] .'","BedDays":"' . $row['wrdDays']
            .'","InvoiceAmount":"' . $row['invAmount'] .'","TotalCredit":"' . $row['totalCredit'].'","Balance":"' . $row['balance']
            .'","InputDate":"' . $row['inputDate'].'"}';

        $counter++;

        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']}';
}

function escapeJsonString($value) { # list from www.json.org: (\b backspace, \f formfeed)
    $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c","\,");
    $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b", "\\,");
    $result = str_replace($escapers, $replacements, $value);
    return $result;
}

function getDentalServices($startDate,$endDate){
    global $db;
    $debug=false;

    $sql="SELECT r.partcode,Description, COUNT(r.partcode) AS PCOUNT, d.unit_price,SUM(total) AS Amount FROM care_ke_billing r
            LEFT JOIN `care_tz_drugsandservices` d ON r.partcode=d.partcode
            WHERE d.category IN('3') AND service_type not in('payment','nhif','nhif1')";
    
    if($startDate<>'' && $endDate<>''){
        $sql=$sql." and bill_date BETWEEN '$startDate' AND '$endDate' ";
    }else{
         $sql=$sql." and bill_date = '".date('Y-m-d')."'";
    }
    
    $sql=$sql." GROUP BY r.partcode";
    
    if($debug) {
        echo $sql;
    }

    $result=$db->Execute($sql);
    $total=$result->RecordCount();
    echo '{
    "total":"' . $total . '","dentalservices":[';
    $counter=0;
    while($row=$result->FetchRow()){
        $desc=escapeJsonString($row['Description']);
        echo '{"RevenueCode":"' . $row['partcode'] .'","Description":"' . $desc .'","Count":"' . $row['PCOUNT'].'","Price":"' . number_format($row['unit_price'],2).'","Total":"' . number_format($row['Amount'],2).'"}';

        $counter++;

        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']}';

}

function getClinics($startDate,$endDate){
    global $db;
    $debug=false;

    $sql="SELECT r.partcode,Description, COUNT(r.partcode) AS PCOUNT, SUM(total) AS Amount FROM care_ke_billing r
            LEFT JOIN `care_tz_drugsandservices` d ON r.partcode=d.partcode
            WHERE d.category IN('2','3','5','6','17','') AND bill_date BETWEEN '$startDate' AND '$endDate' AND service_type<>'payment'	
            GROUP BY r.partcode";
    if($debug) {
        echo $sql;
    }

    $result=$db->Execute($sql);
    $total=$result->RecordCount();
    echo '{
    "total":"' . $total . '","clinics":[';
    $counter=0;
    while($row=$result->FetchRow()){
        $desc=escapeJsonString($row['Description']);
        echo '{"RevenueCode":"' . $row['partcode'] .'","Description":"' . $desc .'","Count":"' . $row['PCOUNT'].'","Total":"' . $row['Amount'].'"}';

        $counter++;

        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']}';

}

function getPersonnel() {
    global $db;
    $sql = 'Select ID,Staff_Name from care_ke_staff';
    $request = $db->execute($sql);
    echo '{"personnel":[';
    while ($row = $request->FetchRow()) {
        
        echo '{"ID":"' . $row[0] . '","StaffName":"' . $row[1] . '"},';
    }
    echo ']}';
}


function getWards() {
    global $db;
    $sql = 'Select nr,name from care_ward';
    $request = $db->execute($sql);
    echo '{"getwards":[';
    while ($row = $request->FetchRow()) {
        echo '{"No":"' . $row[0] . '","WardName":"' . $row[1] . '"},';
    }
    echo ']}';
}

function getDistype() {
    global $db;
   

    $sql1 = "SELECT nr,`type` FROM care_type_discharge where status<>'disabled'";
    $result1 = $db->Execute($sql1);
    
     echo '{
     "getDistype":[';
    
    $totalCount=$result1->RecordCount();
    $counter=0;
    while ($row = $result1->FetchRow()) {
        echo '{"No":"' . $row[0] . '","DisType":"' . $row[1] . '"}';
        
        $counter++;
        if ($counter < $totalCount) {
            echo ",";
        }
    }
    echo ']}';
}

function getLabCounts($itemID,$sex,$startDate,$endDate,$sign){
    global $db;
    $debug=false;

    $sql="SELECT COUNT(l.`item_id`) AS Male FROM `care_test_request_chemlabor_sub` l
            LEFT JOIN `care_test_request_chemlabor` s ON l.`batch_nr`=s.`batch_nr`
            LEFT JOIN care_encounter e ON l.`encounter_nr`=e.`encounter_nr`
            LEFT JOIN care_person n ON e.`pid`=n.`pid`
            WHERE l.item_id='$itemID' and s.send_date between '$startDate' and '$endDate'";

    if($sex<>""){
        $sql.=" and n.`sex`='$sex'";

    }

    if($sign=="<"){
        $sql.=" and (YEAR(NOW())-YEAR(n.date_birth))<5";
    }else if($sign=="between"){
        $sql.=" and (YEAR(NOW())-YEAR(n.date_birth)) between 5 and 14";
    }else if($sign==">"){
        $sql.=" and (YEAR(NOW())-YEAR(n.date_birth))>14";
    }

    $sql.=" GROUP BY l.`item_id` having count(l.item_id)>0";

    if($debug) echo $sql;

    $request=$db->Execute($sql);
    $row=$request->FetchRow();

    return $row[0];
}

function getLabTests($startDate,$endDate){
    global $db;
    $debug =false;

    $sql="SELECT p.`group_id`,p.Item_Id,p.name AS Description FROM care_tz_laboratory_param p
            LEFT JOIN `care_test_request_chemlabor_sub` s ON p.`item_id`=s.`item_id`
            GROUP BY s.`item_id`";
    if($debug) echo $sql;

    $results=$db->Execute($sql);
    $totalCount=$results->RecordCount();

    echo '{"totalCount":"'.$totalCount.'","labtests":[';

     $counter=0;
    while($row=$results->FetchRow()){

//        $startDate=#;
//        $endDate='2015-07-30';

        $maleCounts=getLabCounts($row['Item_Id'],'m',$startDate,$endDate,"");
        $femaleCounts=getLabCounts($row['Item_Id'],'f',$startDate,$endDate,"");
        $total=$maleCounts+$femaleCounts;
        $below5=getLabCounts($row['Item_Id'],'',$startDate,$endDate,"<");
        $between5and14=getLabCounts($row['Item_Id'],'',$startDate,$endDate,"between");
        $above14=getLabCounts($row['Item_Id'],'',$startDate,$endDate,">");

        echo '{"Group":"'.$row['group_id'].'","ItemID":"'.$row['Item_Id'].'","Description":"'.$row['Description']
              .'","Male":"'.$maleCounts.'","Female":"'.$femaleCounts.'","Total":"'.$total
              .'","Below5":"'.$below5.'","Between5And14":"'.$between5and14.'","Above14":"'.$above14.'"}';

        $counter++;
        if ($counter < $totalCount) {
            echo ",";
        }

    }

  echo "]}";

}

function getLabTestResult($encounterNr,$jobID){
    global $db;
    $debug=true;
    
    $sql="select paramater_name,parameter_value from care_test_findings_chemlabor_sub 
            where encounter_nr=$encounterNr and job_id=$jobID";
    if($debug) {echo $sql; }
    
    $results=$db->Execute($sql);
    $rcount=$results->FetchRow();
    if($rcount>0){
         echo '{ "labresults":[';
         $counter=0;
        while($row=$results->FetchRow()){
            $strLab=explode("-",$row[0]);
            if($strLab[0]=='group'){
                $itemId=$strLab[1];
            }else{
                $itemId=$row[0];
            }echo '{"ParameterName":"' . $row['parameter_value'] . '","ParameterValue":"' . $row['paramater_value'].'"}';

        if ($counter <> $rcount) {
            echo ",";
        }
        $counter++;
            
        }
         echo "]}";
       
    }else{
        return false;
    }
}

function getLabResults($startDate,$endDate,$pid,$labTest) {
    global $db;

    $debug=false;

    $sql = "select p.pid,e.encounter_nr,CONCAT(p.name_first,' ',p.name_2,' ',p.name_last) as pnames,t.encounter_nr,t.batch_nr,
                t.send_date,s.item_id,s.paramater_name,t.create_id from care_test_request_chemlabor t 
                LEFT JOIN care_test_request_chemlabor_sub s on t.batch_nr=s.batch_nr
                LEFT JOIN care_encounter e on t.encounter_nr=e.encounter_nr
                LEFT JOIN care_person p on e.pid=p.pid
                 where t.encounter_nr=10278892";


    if($debug) echo $sql;

    $request = $db->Execute($sql);
    $rowCnt = $request->RecordCount();

    echo '{ "total":"' . $rowCnt . '","labtests":[';

    $counter=0;
    while ($row = $request->FetchRow()) {
        echo '{"Pid":"' . $row['pid'] . '","Names":"' . $row['pnames'] . '","TestDate":"' .  $row['send_date']
            . '","EncounterNr":"' .  $row['encounter_nr'] . '","LabCode":"' .  $row['item_id']
            . '","LabTest":"' .  $row['paramater_name']. '","RequestedBy":"' .  $row['create_id'].'"}';

        if ($counter <> $rowCnt) {
            echo ",";
        }
        $counter++;
    }

    echo "]}";


}


function getDiseases(){
    global $db;
    $debug =false;

    $sql="Select diagnosis_code,Description from care_icd10_en WHERE diagnosis_code LIKE 'O%'";
    if($debug) echo $sql;

    $results=$db->Execute($sql);
    $totalCount=$results->RecordCount();

    echo '{"totalCount":"'.$totalCount.'","diseases":[';

    $counter=0;
    while($row=$results->FetchRow()){
        echo '{"IcdCode":"'.$row[0].'","Description":"'.$row[1].'"}';
        
         $counter++;
        if ($counter < $totalCount) {
            echo ",";
        }
    }

    echo "]}";

}


function getDiagnosisReports($startDate,$endDate) {
    global $db;
    $debug=false;
    
    $age1 = $_REQUEST['age1'];
    $age2 = $_REQUEST['age2'];
    $gender = $_REQUEST['gender'];
    $icd1 = $_REQUEST['icd10'];
    $status = $_REQUEST['status'];
    $visits = $_REQUEST['visits'];
    $pid = $_REQUEST['pid'];

    $sql = "SELECT distinct d.pid,p.selian_pid,p.name_first,p.name_last,p.name_2,p.date_birth,
                p.sex,(YEAR(NOW())-YEAR(p.date_birth)) AS age,
                d.encounter_nr,d.ICD_10_code,d.ICD_10_description,d.type,d.timestamp,d.pataintstatus FROM care_tz_diagnosis d left JOIN care_person p
            ON d.PID=p.pid LEFT join care_encounter e on d.pid=e.pid";

    if ($startDate <> "" && $endDate <> "") {
        $sql = $sql . " where DATE_FORMAT(d.timestamp,'%Y-%m-%d') between '$startDate' and '$endDate' ";
    }

    if (isset($gender) && $gender <> "") {
        if ($gender == 'Male') {
            $sex = 'M';
        } else if($gender == 'Female') {
            $sex = 'F';
        }
        $sql = $sql . " and sex='$sex'";
    }
    if ($icd1 <> "") {
        $sql = $sql . " and ICD_10_code ='$icd1";
    }

    if (isset($age1) && $age2 <> "") {
        $sql = $sql . " having (YEAR(NOW())-YEAR(p.date_birth)) between '$age1' and '$age2'";
    } else if ($age1 <> "" && $age2 == "") {
        $sql = $sql . " having (YEAR(NOW())-YEAR(p.date_birth))='$age1'";
    }

    if ($pid <> "") {
        $sql = $sql . " and d.pid='$pid'";
    }

    if($status<>""){
        if($status=="Dead"){
            $dStat='D';
        }else{
            $dStat='A';
        }
        $sql=$sql." and pataintstatus='$dStat'";
    }

    if($visits){
        $sql=$sql." and `type`='$status";
    }
  if($debug) echo $sql;
    //p.pid,p.name_first,p.name_last,p.name_2,b.bill_date,b.bill_number,b.total

    $request = $db->Execute($sql);
    $rowCnt=$request->RecordCount();

    echo '{ "total":"' . $rowCnt . '","diagnosis":[';

    $counter=0;
        while ($row = $request->FetchRow()) {
            if ($row['pataintstatus'] == 'A') {
                $stat = 'Alive';
            } else if ($row['pataintstatus'] == 'D') {
                $stat = 'Dead';
            } else {
                $stat = '';
            }

            echo '{"PID":"' . $row['pid'] . '","Names":"' .  $row['name_first'] . ' ' . $row['name_last'] . ' ' . $row['name_2']  . '","Date":"' .  $row['timestamp']
                . '","Gender":"' .  $row['sex'] . '","Age":"' .  $row['date_birth']. '","AdmissionNo":"' .  $row['selian_pid']. '","Visit":"' .  $row['type']
                . '","DiagnosisCode":"' .  $row['ICD_10_code']. '","Description":"' . trim($row['ICD_10_description']). '","PatientStatus":"' .  $stat.'"}';

            $counter++;
            if ($counter <> $rowCnt) {
                echo ",";
            }

        }

        echo "]}";

}

function getDiagnosis() {
    global $db;
    $age1 = $_REQUEST['age1'];
    $age2 = $_REQUEST['age2'];
    $date1 = $_REQUEST['date1'];
    $date2 = $_REQUEST['date2'];
    $gender = $_REQUEST['gender'];
    $icd1 = $_REQUEST['icd1'];
    $icd2 = $_REQUEST['icd2'];
    $task = $_REQUEST['task'];
    $visits = $_REQUEST['visits'];
    $pid = $_REQUEST['pid'];

    $sql = "SELECT distinct d.pid,p.selian_pid,p.name_first,p.name_last,p.name_2,p.date_birth,
        p.sex,(YEAR(NOW())-YEAR(p.date_birth)) AS age,
        d.encounter_nr,d.ICD_10_code,d.ICD_10_description,d.type,d.timestamp,d.pataintstatus FROM care_tz_diagnosis d left JOIN care_person p
ON d.PID=p.pid LEFT join care_encounter e on d.pid=e.pid";



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
        $sql = $sql . " where DATE_FORMAT(d.timestamp,'%Y-%m-%d') between '$dt1' and '$dt2' ";
    } else if ($dt1 <> '' && $dt2 == '') {
        $sql = $sql . " where DATE_FORMAT(d.timestamp,'%Y-%m-%d')= '$dt1'";
    } else {
        $sql = $sql . " where DATE_FORMAT(d.timestamp,'%Y-%m-%d')<=now()";
    }

    if (isset($gender) && $gender <> "") {
        if ($gender == 1) {
            $sex = 'M';
        } else {
            $sex = 'F';
        }
        $sql = $sql . " and sex='$sex'";
    }
    if ($icd1 <> "" && $icd2 <> "") {
        $sql = $sql . " and ICD_10_code between '$icd1' and '$icd2'";
    } else if ($icd1 <> "" ) {
        $sql = $sql . " and ICD_10_code = '$icd1'";
    }

    if (isset($age1) && $age2 <> "") {
        $sql = $sql . " having (YEAR(NOW())-YEAR(p.date_birth)) between '$age1' and '$age2'";
    } else if ($age1 <> "" && $age2 == "") {
        $sql = $sql . " having (YEAR(NOW())-YEAR(p.date_birth))='$age1'";
    }

    if ($pid <> "") {
        $sql = $sql . " and d.pid='$pid'";
    }
//   echo $sql;
    //p.pid,p.name_first,p.name_last,p.name_2,b.bill_date,b.bill_number,b.total

    $request = $db->Execute($sql);

    echo '<table width=100% height=14><tr bgcolor=#6699cc>
                    <td align="center">pid</td>
                    <td align="center">Names</td>
                    <td align="center">Date</td>
                     <td align="center">Gender</td>
                      <td align="center">Age</td>
                    <td align="center">Adm NO</td>
                    <td align="center">Status</td>


                    <td align="center">diagnosis code</td>
                    <td align="center">Description</td>
                    <td align="center"> Visit</td>
                 </tr>';
    $bg = '';
//        $total='';
    while ($row = $request->FetchRow()) {
        if ($row['pataintstatus'] == 'A') {
            $stat = 'Alive';
        } else if ($row['pataintstatus'] == 'D') {
            $stat = 'Dead';
        } else {
            $stat = '';
        }
        if ($bg == "silver")
            $bg = "white";
        else
            $bg = "silver";
        echo '<tr bgcolor=' . $bg . ' height=16>
                    <td>' . $row['pid'] . '</td>
                         <td>' . $row['name_first'] . ' ' . $row['name_last'] . ' ' . $row['name_2'] . '</td>
                    <td>' . $row['timestamp'] . '</td>
                    <td>' . $row['sex'] . '</td>
                    <td>' . $row['age'] . '</td>
                    <td>' . $row['selian_pid'] . '</td>
                    <td>' . $stat . '</td>
                    <td>' . $row['ICD_10_code'] . '</td>
                    <td>' . $row['ICD_10_description'] . '</td>
                    <td>' . $row['type'] . '</td>    
                   
             </tr>';

        $rowbg = 'white';
    }
    $rowCnt = $request->RecordCount();

    echo "<tr><td colspan=6><br>No of Patients $rowCnt</td></tr>";
    echo '</table>';
}

function getTopDiagnosis($startDate,$endDate) {
    global $db;
    $debug=false;

    $adm = $_REQUEST['adm'];

    $sql = "SELECT COUNT(d.pid) AS diagCount,d.encounter_nr,d.ICD_10_code,d.ICD_10_description,d.type,d.timestamp 
        FROM care_tz_diagnosis d";

        if ($startDate <> "" && $endDate <> "") {
                $sql = $sql . " where d.timestamp between '$startDate' and '$endDate' ";
        }

    $sql = $sql . " GROUP BY ICD_10_code order by diagCount desc";

    if($debug) echo $sql;

    $results=$db->Execute($sql);
    $totalCount=$results->RecordCount();

    echo '{"totalCount":'.$totalCount.',"topdiseases":[';

    $counter=0;
    while($row=$results->FetchRow()){
        echo '{"IcdCode":"'.$row['ICD_10_code'].'","Description":"'.trim($row['ICD_10_description']).'","DiseaseCount":"'.$row['diagCount'].'"}';

        $counter++;
        if ($counter <> $totalCount) {
            echo ",";
        }

    }

    echo "]}";
}


function getAdmissions($startDate,$endDate) {
    global $db;

    $debug=false;
    $wards = $_REQUEST['ward'];
    $disType = $_REQUEST['disType'];
    $grpWards = $_REQUEST['grpWards'];
    $sex = $_REQUEST['sex'];

    $sql = "SELECT  e.pid,e.newAdm_No,p.name_first,p.name_last,p.name_2,p.sex,
        p.date_birth,(DATEDIFF(DATE(NOW()),p.date_birth))/365 as age,e.encounter_date,e.encounter_time,
  e.current_ward_nr,w.name  AS wardname,(DATEDIFF(DATE(NOW()),e.encounter_date)) AS BedDays FROM care_person p
  LEFT JOIN care_encounter e ON (p.pid = e.pid) left join care_ward w on e.current_ward_nr=w.nr";



    if ($startDate <> "" && $endDate <> "") {
        $sql = $sql . " where e.encounter_date between '$startDate' and '$endDate' ";
    }

    if ($wards) {
        $sql = $sql . " and e.current_ward_nr=$wards";
    }

    if ($sex) {
        $sql = $sql . " and p.sex='$sex'";
    }

    if ($grpWards) {
        if ($grpWards == 'adults') {
            $wards = " in('1','2','4','5')";
        } elseif ($grpWards == 'paeds') {
            $wards = " in ('6')";
        } elseif ($grpWards == 'mat') {
            $wards = " in ('5')";
        }
        $sql = $sql . " and e.current_ward_nr $wards";
    }

    $sql = $sql . " and e.encounter_class_nr = 1 order by e.encounter_date desc";

    if($debug) echo $sql;

    $request = $db->Execute($sql);
    $rowCnt = $request->RecordCount();

    echo '{ "total":"' . $rowCnt . '","admdis":[';

    $counter=0;
    while ($row = $request->FetchRow()) {
        //e.pid,e.newAdm_No,p.name_first,p.name_last,p.name_2,p.sex,p.date_birth,e.encounter_date,e.encounter_time,
        //e.current_ward_nr,w.name
        $strNames=$row['name_first'].' '.$row['name_last'].' '.$row['name_2'];
        $names=preg_replace('/[^a-zA-Z0-9_ -]/s', '', $strNames);

        echo '{"PID":"' . $row['pid'] . '","AdmissionNo":"' . $row['newAdm_No'] . '","Names":"' . $names
            . '","Sex":"' .  $row['sex'] . '","Dob":"' .  $row['date_birth']. '","AdmissionDate":"' .  $row['encounter_date']
            . '","Ward":"' .  $row['wardname']. '","BedDays":"' .  $row['BedDays']. '","Age":"' . number_format($row['age'],1).'"}';

        if ($counter <> $rowCnt) {
            echo ",";
        }
        $counter++;
    }

    echo "]}";


}

function getDischarges($startDate,$endDate) {
    global $db;
    $debug=false;

    $ward= $_REQUEST['ward'];
    $grpWards = $_REQUEST['grpWards'];
    $sex = $_REQUEST['sex'];
    $discType=$_REQUEST['disType'];
    $age1=$_REQUEST['age1'];
    $age2=$_REQUEST['age2'];


        $sql = "SELECT p.pid,e.encounter_nr, CONCAT(p.name_first,' ',p.name_last,' ',p.name_2) AS NAMES,p.`date_birth`,p.`sex`
                ,e.encounter_date,e.`encounter_time`,e.`discharge_date`,(DATEDIFF(DATE(NOW()),p.date_birth))/365 as age,
               l.discharge_type_nr,e.current_ward_nr,w.name AS wardName,
               DATEDIFF(e.discharge_date,e.`encounter_date`) AS BedDays,c.name,n.nhifDebtorNo
        FROM care_encounter e 
        LEFT JOIN care_person p  ON e.pid=p.pid
        LEFT JOIN care_tz_company c on p.insurance_ID=c.id
         LEFT JOIN care_ke_nhifcredits n on e.pid=n.admno
        LEFT JOIN care_ward w ON e.current_ward_nr=w.nr
        LEFT JOIN care_encounter_location l ON l.encounter_nr=e.encounter_nr 
        WHERE  e.encounter_class_nr = 1 AND l.`type_nr`=5";

        if($age1<>'' && $age2<>''){
            $sql=$sql." and ((DATEDIFF(DATE(NOW()),p.date_birth))/365) between '$age1' and '$age2'";
        }
        
        
    if ($startDate <> "" && $endDate <> "") {
        $sql = $sql . " AND e.discharge_date between '$startDate' and '$endDate' ";
    } 

    if ($ward) {
        $sql = $sql . " and w.nr=$ward";
    }

    if ($sex<>'') {
        $sql = $sql . " and p.sex='$sex'";
    }
    
    if ($grpWards<>'') {
        if ($grpWards == 'adults') {
            $wards = " in('11','12','14','15','22','23','24')";
        } elseif ($grpWards == 'paeds') {
            $wards = " in ('13')";
        } elseif ($grpWards == 'mat') {
            $wards = " in ('26')";
        }
        $sql = $sql . " and e.current_ward_nr $wards";
    }

    if ($discType<>'' && $discType<>'null') {
        $sql = $sql . " and e.`is_discharged`=$discType";
    }

//    if ($age1<>"" && $age2 <> "") {
//        $sql = $sql . " having (YEAR(NOW())-YEAR(p.date_birth)) between '$age1' and '$age2'";
//     }
     
   $sql = $sql . " GROUP BY p.`pid` order by e.discharge_date desc";

    if($debug) echo $sql;
    //p.pid,p.name_first,p.name_last,p.name_2,b.bill_date,b.bill_number,b.total

    $request = $db->Execute($sql);

    $rowCnt = $request->RecordCount();

    echo '{ "total":"' . $rowCnt . '","admdis":[';

    $counter=0;
    while ($row = $request->FetchRow()) {

        //e.pid,e.newAdm_No,p.name_first,p.name_last,p.name_2,p.sex,p.date_birth,e.encounter_date,e.encounter_time,
        //e.discharge_date,(DATEDIFF(e.discharge_date,e.encounter_date)) AS bDays,w.name as wardName

        if($row['name']<>''){
            $paymode=$row['name'];
        }else{
            $paymode='CASH';
        }

        if($row['nhifDebtorNo']<>''){
            $NHIF=$row['nhifDebtorNo'];
        }else{
            $NHIF='CASH';
        }

        $bal= $row['bill']-$row['payment'];
        echo '{"PID":"' . $row['pid'] . '","Names":"' . $row['NAMES'] . '","Dob":"' .  $row['date_birth']
            . '","Sex":"' .  $row['sex'] . '","InvoiceNo":"' . $row['bill_number']. '","AdmissionDate":"' . $row['encounter_date']. '","DischargeDate":"' .  $row['discharge_date']
            . '","Ward":"' .  $row['wardName']. '","BedDays":"' . $row['BedDays']. '","DisType":"'. $row['discharge_type_nr']
            . '","Age":"'. number_format($row['age'],1).'","Payment":"' . $paymode.'","NHIFCredit":"' . $NHIF.'"}';

        if ($counter <> $rowCnt) {
            echo ",";
        }
        $counter++;
    }

    echo "]}";
}

function getLabRevenue($startDate,$endDate){
    global $db;
    $debug=false;
    $revType=$_REQUEST['revType'];
    
    if($revType=='Cash'){
        $sql="SELECT b.`proc_code` as partcode, d.`category`,b.`Prec_desc` as Description,
                    b.`amount`,SUM(b.total) AS Total,COUNT(b.`proc_code`) AS Lab_Count
                FROM care_ke_receipts b LEFT JOIN care_tz_drugsandservices d ON b.`proc_code`=d.`partcode`
                WHERE d.`category`='10'";
                 if ($startDate <> "" && $endDate <> "") {
                        $sql = $sql . " and b.currdate between '$startDate' and '$endDate' ";
                } 

                $sql = $sql . "  GROUP BY b.proc_code order by SUM(b.total) desc";
        
    }elseif($revType=='Insurance'){
        $sql="SELECT b.`partcode`, d.`category`,b.`Description`,
                     b.`price` as amount,SUM(b.total) AS Total,COUNT(b.`partcode`) AS Lab_Count
                FROM care_ke_billing b 
                LEFT JOIN care_tz_drugsandservices d ON b.`partcode`=d.`partcode`
                LEFT JOIN care_person p ON b.`pid`=p.`pid` 
                WHERE D.`category`='10' AND p.`insurance_ID`<>'-1'  AND b.`IP-OP`=2";
                if ($startDate <> "" && $endDate <> "") {
                      $sql = $sql . " and b.bill_date between '$startDate' and '$endDate' ";
                } 
                $sql = $sql . "  GROUP BY b.partcode order by SUM(b.total) desc";
    }elseif($revType=='Inpatient'){
          $sql = "SELECT b.`partcode`, d.`category`,b.`Description`,
                        b.`price` as amount,SUM(b.total) AS Total,COUNT(b.`partcode`) AS Lab_Count
                  FROM care_ke_billing b LEFT JOIN care_tz_drugsandservices d ON b.`partcode`=d.`partcode`
                  WHERE D.`category`='10' AND b.`IP-OP`=1";
                if ($startDate <> "" && $endDate <> "") {
                    $sql = $sql . " and b.bill_date between '$startDate' and '$endDate' ";
                } 

                $sql = $sql . "  GROUP BY b.partcode order by SUM(b.total) desc";
    }
    
   if($debug) echo $sql;
    //p.pid,p.name_first,p.name_last,p.name_2,b.bill_date,b.bill_number,b.total

    $request = $db->Execute($sql);
    $rowCnt=$request->RecordCount();

    $counter=0;

    echo '{"totalCount":'.$rowCnt.',"labrevenue":[';
    while ($row = $request->FetchRow()) {
        if($row['admission']=='1'){
            $adm='IP';
        }else{
            $adm='OP';
        }
        echo '{"LabCode":"' . $row['partcode'] . '","Description":"' . $row['Description']
            . '","TotalTests":"' .  $row['Lab_Count']  . '","Price":"' .   number_format($row['amount'],2)  
                . '","TotalCost":"' .  number_format($row['Total'],2).'"}';

        if ($counter <> $rowCnt) {
            echo ",";
        }
        $counter++;
    }

    echo "]}";
}

function getLabActivity() {
    global $db;
    $debug=false;

    $date1 = $_REQUEST['date1'];
    $date2 = $_REQUEST['date2'];
    $age1=$_REQUEST['age1'];
    $age2=$_REQUEST['age2'];


    $sql = "SELECT p.pid,p.name_first,p.name_last,p.name_2,b.`IP-OP`,b.partcode,b.service_type,b.Description,
  b.total AS Total,b.qty AS Lab_Count FROM care_ke_billing b INNER JOIN care_person p ON (b.pid = p.pid)
  WHERE b.service_type = 'laboratory'";

    if (isset($date1) && isset($date2) && $date1 <> "" && $date1 <> "") {
        $date = new DateTime($date1);
        $dt1 = $date->format("Y-m-d");

        $date = new DateTime($date2);
        $dt2 = $date->format("Y-m-d");

        $sql = $sql . " and b.bill_date between '$dt1' and '$dt2' ";
    } else {
        $sql = $sql . " and b.bill_date<=now()";
    }



    $sql = $sql . " order by b.partcode desc";
    if($debug) echo $sql;
    //p.pid,p.name_first,p.name_last,p.name_2,b.bill_date,b.bill_number,b.total

    $request = $db->Execute($sql);

    echo '<table width=100% height=14><tr bgcolor=#6699cc>
                    <td align="left">PID</td>
                    <td align="left">Names</td>
                    <td align="left">Lab Code</td>
                    <td align="left">description</td>
                    <td align="left">Total</td>
                    <td align="left">Qty</td>
                 </tr>';
    $bg = '';
//        $total='';
    while ($row = $request->FetchRow()) {
        if ($bg == "silver")
            $bg = "white";
        else
            $bg = "silver";
        echo '<tr bgcolor=' . $bg . ' height=16>
                    <td>' . $row['pid'] . '</td>
                    <td>' . $row['name_first'] . ' ' . $row['name_last'] . ' ' . $row['name_2'] . '</td>
                    <td>' . $row['partcode'] . '</td>    
                    <td>' . $row['Description'] . '</td>    
                    <td align=right>' . number_format($row['Total'], 2) . '</td>  
                    <td>' . $row['Lab_Count'] . '</td>  
             </tr>';
        $lsum = $lsum + $row['Total'];
        $rowbg = 'white';
    }
    $rowCnt = $request->RecordCount();

    echo "<tr><td colspan=3><br>No of Tests $rowCnt</td><td align=right><b>Total Amount " . number_format($lsum, 2) . "<b></td></tr>";
    echo '</table>';
}

function getLabPatientTests() {
    global $db;
    $debug=false;

    $date1 = $_REQUEST['date1'];
    $date2 = $_REQUEST['date2'];
    $pid = $_REQUEST['pid'];

    $sql = "SELECT p.pid,p.name_first,p.name_last,p.name_2,b.bill_date,b.`IP-OP`,b.partcode,b.service_type,b.Description,
  b.total AS Total,b.qty AS Lab_Count FROM care_ke_billing b INNER JOIN care_person p ON (b.pid = p.pid)
  WHERE b.service_type = 'laboratory'";

    if($pid){
        $sql.="  and b.pid=$pid";
    }

    if (isset($date1) && isset($date2) && $date1 <> "" && $date1 <> "") {
        $date = new DateTime($date1);
        $dt1 = $date->format("Y-m-d");

        $date = new DateTime($date2);
        $dt2 = $date->format("Y-m-d");

        $sql = $sql . " and b.bill_date between '$dt1' and '$dt2' ";
    } else {
        $sql = $sql . " and b.bill_date<=now()";
    }

    $sql = $sql . " order by b.bill_date desc";

    if($debug) echo $sql;
    //p.pid,p.name_first,p.name_last,p.name_2,b.bill_date,b.bill_number,b.total

    if ($request = $db->Execute($sql)) {

        $row1 = $request->FetchRow();
        echo '<table width=100% height=14>
        <tr><td colspan=6><br></td></tr>     
        <tr><td align="left"><b>PID:</b></td> <td>' . $row1['pid'] . '</td></tr>
        <tr><td align="left"><b>Names:</b></td><td>' . $row1['name_first'] . ' ' . $row1['name_last'] . ' ' . $row1['name_2'] . '</td></tr>
        <tr bgcolor=#6699cc>
                    <td align="left">Date</td>
                    <td align="left">Admission</td>
                    <td align="left">Lab Code</td>
                    <td align="left">description</td>
                    <td align="left">Qty</td>
                    <td align="left">Total</td>
                 </tr>';
        $bg = '';
//        $total='';
        while ($row = $request->FetchRow()) {
            if ($bg == "silver")
                $bg = "white";
            else
                $bg = "silver";
            if ($row[`IP-OP`] == 1) {
                $enc_class = 'IP';
            } else {
                $enc_class = 'OP';
            }
            echo '<tr bgcolor=' . $bg . ' height=16>
                
                    <td>' . $row['bill_date'] . '</td>  
                    <td>' . $enc_class . '</td>    
                    <td>' . $row['partcode'] . '</td>    
                    <td>' . $row['Description'] . '</td>    
                    <td>' . $row['Lab_Count'] . '</td>  
                    <td align=right>' . number_format($row['Total'], 2) . '</td>  
             </tr>';
            $lsum = $lsum + $row['Total'];
            $rowbg = 'white';
        }
        $rowCnt = $request->RecordCount();

        echo "<tr><td colspan=3><br>No of Tests $rowCnt</td><td align=right><b>Total Amount " . number_format($lsum, 2) . "<b></td></tr>";
        echo '</table>';
    } else {
        echo 'SQL: Failed=' . $sql;
    }
}

function getLabActivities($startDate,$endDate){
    global $db;
    $debug=false;

    $age1=$_REQUEST['age1'];
    $age2=$_REQUEST['age2'];
    $pid=$_REQUEST['pid'];
    $requestedBy=$_REQUEST['staffName'];
    $partcode=$_REQUEST['partcode'];
    $status=$_REQUEST['status'];
    $gender=$_REQUEST['gender'];

    $sql = "SELECT p.pid,CONCAT(p.name_first,' ',p.name_last,' ',p.name_2) AS pnames,b.`bill_date`,
              b.`bill_time`,b.`IP-OP` AS admission,b.partcode,l.`group_id`,b.service_type,b.Description,
              b.total AS Total,b.qty AS Lab_Count,b.`status`,b.`input_user`  FROM care_ke_billing b 
              INNER JOIN care_person p ON (b.pid = p.pid)
              LEFT JOIN `care_tz_drugsandservices` d ON b.`partcode`=d.`partcode`
              LEFT JOIN `care_tz_laboratory_param` l ON b.`partcode`=l.`item_id`
              WHERE d.`category`='10'";

    if ($startDate <> "" && $endDate <> "") {
        $sql = $sql . " and b.bill_date between '$startDate' and '$endDate' ";
    }
    
    if(isset($status) && $status<>''){
        $sql = $sql . " and b.status='$status'";
    }

    if(isset($pid) && $pid<>''){
        $sql = $sql . " and p.pid='$pid'";
    }
    
    if(isset($gender) && $gender<>''){
        $sql = $sql . " and p.sex='$gender'";
    }

    if(isset($requestedBy) && $requestedBy<>''){
        $sql=$sql . " and b.input_user='$requestedBy'";
    }
    
    if(isset($partcode) && $partcode<>''){
        $sql=$sql . " and b.partcode='$partcode'";
    }

    $sql = $sql . " GROUP BY p.`pid`,b.`Description` order by b.partcode desc";

    if($debug) echo $sql;

    $request = $db->Execute($sql);
    $rowCnt=$request->RecordCount();

    $counter=0;

    echo '{"totalCount":'.$rowCnt.',"labactivities":[';
    while ($row = $request->FetchRow()) {
        if($row['admission']=='1'){
            $adm='IP';
        }else{
            $adm='OP';
        }

        $names=preg_replace('/[^A-Za-z0-9\-]/', '', $row['pnames']);
        echo '{"PID":"' . $row['pid'] . '","Names":"' . $names . '","Bill_Date":"' . $row['bill_date']
            . '","Bill_Time":"' . $row['bill_time']. '","Admission":"' .  $adm. '","LabCode":"' .  $row['partcode']
            . '","GroupID":"' .  $row['group_id']. '","Description":"' .  $row['Description'] . '","Qty":"' .  $row['Lab_Count']
            . '","Total":"' . $row['Total']. '","Status":"' . $row['status']. '","RequestedBy":"' . $row['input_user'].'"}';

        if ($counter <> $rowCnt) {
            echo ",";
        }
        $counter++;
    }

    echo "]}";
}

function getXrayActivities($startDate,$endDate){
    global $db;
    $debug=false;

    $age1= $_REQUEST['age1'];
    $age2=$_REQUEST['age2'];


    $sql = "SELECT p.pid,CONCAT(p.name_first,' ',p.name_last,' ',p.name_2) AS pnames,b.`bill_date`,b.`bill_time`,b.`IP-OP` as admission,
      b.partcode,b.service_type,b.Description, b.total AS Total,b.qty AS Lab_Count,b.`input_user` FROM care_ke_billing b
      INNER JOIN care_person p ON (b.pid = p.pid) WHERE b.service_type = 'XRAY'";

    if ($startDate<>"" && $endDate<>"") {
        $sql = $sql . " and b.bill_date between '$startDate' and '$endDate' ";
    } 

    $sql = $sql . " order by b.partcode desc";
    if($debug) echo $sql;

    $request = $db->Execute($sql);
    $rowCnt=$request->RecordCount();

    $counter=0;

    echo '{"totalCount":'.$rowCnt.',"xrayactivities":[';
    while ($row = $request->FetchRow()) {
        if($row['admission']=='1'){
            $adm='IP';
        }else{
            $adm='OP';
        }
        echo '{"PID":"' . $row['pid'] . '","Names":"' . $row['pnames'] . '","SendDate":"' . $row['bill_date'] . '","SendTime":"' . $row['bill_time']
            . '","Admission":"' .  $adm. '","LabCode":"' .  $row['partcode']  . '","Description":"' .  $row['Description']
            . '","Qty":"' .  $row['Lab_Count']. '","Total":"' . (($row['Total']<>'')?$row['Total']:'0'). '","InputUser":"' .  $row['InputUser'].'"}';

        if ($counter <> $rowCnt) {
            echo ",";
        }
        $counter++;
    }

    echo "]}";
}

function getXrayRevenue($startDate,$endDate){
    global $db;
    $debug=false;

    $sql = "SELECT b.`proc_code` AS partcode, b.`rev_desc` AS service_type,b.`Prec_desc` AS Description,
b.`amount` AS price,COUNT(b.`proc_code`) AS xray_Count
        FROM care_ke_receipts b WHERE b.`rev_desc` = 'xray'";

    if ($startDate <> "" && $endDate <> "") {

        $sql = $sql . " and b.currdate between '$startDate' and '$endDate' ";
    } 

    $sql = $sql . "  GROUP BY b.proc_code,b.rev_desc, b.Prec_desc order by b.`proc_code` desc";
    if($debug) echo $sql;
    //p.pid,p.name_first,p.name_last,p.name_2,b.bill_date,b.bill_number,b.total

    $request = $db->Execute($sql);
    $rowCnt=$request->RecordCount();

    $counter=0;

    echo '{"totalCount":'.$rowCnt.',"xrayrevenue":[';
    while ($row = $request->FetchRow()) {
        if($row['admission']=='1'){
            $adm='IP';
        }else{
            $adm='OP';
        }
        echo '{"xraycode":"' . $row['partcode'] . '","Description":"' . $row['Description']
            . '","TotalTests":"' .  $row['xray_Count']  . '","Price":"' .   number_format($row['price'],2)  
                . '","Total":"' .  number_format($row['price']*$row['xray_Count'],2).'"}';

        $counter++;
        if ($counter <> $rowCnt) {
            echo ",";
        }
        
    }

    echo "]}";
}


function getXrayPatientTests() {
    global $db;

    $date1 = $_REQUEST['date1'];
    $date2 = $_REQUEST['date2'];
    $pid = $_REQUEST['pid'];

    $sql = "SELECT p.pid,p.name_first,p.name_last,p.name_2,b.bill_date,b.`IP-OP`,b.partcode,b.service_type,b.Description,
  b.total AS Total,b.qty AS Lab_Count FROM care_ke_billing b INNER JOIN care_person p ON (b.pid = p.pid)
  WHERE b.service_type = 'xray' and b.pid=$pid";

    if (isset($date1) && isset($date2) && $date1 <> "" && $date1 <> "") {
        $date = new DateTime($date1);
        $dt1 = $date->format("Y-m-d");

        $date = new DateTime($date2);
        $dt2 = $date->format("Y-m-d");

        $sql = $sql . " and b.bill_date between '$dt1' and '$dt2' ";
    } else {
        $sql = $sql . " and b.bill_date<=now()";
    }

    $sql = $sql . " order by b.bill_date desc";
   if($debug) echo $sql;
    //p.pid,p.name_first,p.name_last,p.name_2,b.bill_date,b.bill_number,b.total
   $request->Execute($sql);
   $rowCnt=$request->RecordCount();
   echo '{"totalCount":'.$rowCnt.',"xrayrevenue":[';
    while ($row = $request->FetchRow()) {
        if($row['admission']=='1'){
            $adm='IP';
        }else{
            $adm='OP';
        }
        echo '{"xraycode":"' . $row1['pid']  . '","Names":"' .$row1['name_first'] . ' ' . $row1['name_last'] . ' ' . $row1['name_2']
            . '","Date":"' .  $row['bill_date']. '","Admission":"' .  $enc_class. '","Test":"' .  $row['Description']  
            . '","Price":"' .   $row['Unit_price']  . '","Qty":"' .   $row['xray_Count'] . '","Total":"' .  number_format($row['price']*$row['xray_Count'],2).'"}';

        $counter++;
        if ($counter <> $rowCnt) {
            echo ",";
        }
        
    }

    echo "]}";

}

function getRevenues($rptType,$startDate,$endDate) {
    global $db;
    $debug=false;
    
    if($rptType=='oprevenue'){
         $sql = "SELECT rev_desc,SUM(Total) AS Total FROM care_ke_receipts WHERE currdate BETWEEN '$startDate' AND  '$endDate'
                    GROUP BY rev_desc";
    }else if($rptType=='iprevenue'){
        $sql = "SELECT service_type as rev_desc,SUM(total) AS Total FROM care_ke_billing WHERE bill_date BETWEEN '$startDate' AND '$endDate'
                AND `service_type` not in ('payment','NHIF')
                    GROUP BY service_type";
//
    }else if($rptType=='opDebtorsRevenue'){
    $sql = "SELECT b.`service_type` as rev_desc,SUM(total) AS Total FROM care_ke_billing b LEFT JOIN care_person p
                ON b.`pid`=p.`pid` WHERE `IP-OP`=2 AND bill_date BETWEEN '$startDate' AND  '$endDate'
                AND b.`service_type`<>'Payment' and p.insurance_id<>'-1' GROUP BY b.`service_type`";
    }
    if($debug) echo $sql;

    $request = $db->Execute($sql);
    $total=$request->RecordCount();
        echo '{
        "total":"' . $total . '","revenueList":[';
        $counter = 0;
        while ($row = $request->FetchRow()) {
            
            echo '{"Category":"' . $row['rev_desc'] . '","Amount":"' . number_format($row['Total'],2).'"}';
            
            if ($counter <> $total) {
                echo ",";
            }
            $counter++;
        }
        echo ']}';

}


//function escapeJsonString($value) { # list from www.json.org: (\b backspace, \f formfeed)
//    $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
//    $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
//    $result = str_replace($escapers, $replacements, $value);
//    return $result;
//}

function getLabList(){
    global $db;
    $debug=false;

    $sql="SELECT item_id,NAME FROM `care_tz_laboratory_param` WHERE group_id<>'-1'";
    if($debug) echo $sql;

    $results=$db->Execute($sql);
    $totalCount=$results->RecordCount();

    echo '{"totalCount":'.$totalCount.',"labparams":[';

    $counter=0;
    while($row=$results->FetchRow()){
        $description=escapeJsonString($row['NAME']);

        echo '{"ItemID":"'.$row['item_id'].'","Description":"'.$description.'"}';

        $counter++;
        if ($counter <> $totalCount) {
            echo ",";
        }
    }

    echo "]}";

}

function getItemsList($partCode,$start,$limit){
    global $db;
    $debug=false;

    $sql="select partcode,item_description,purchasing_class from care_tz_drugsandservices where "
            . "purchasing_class in('drug_list','medical-supplies','General Supplies','Kitchen')";
    
    if(isset($partCode)){
        $sql=$sql." where partcode='$partCode'";
    }
    
    $sql=$sql." order by purchasing_class,item_description asc limit $start,$limit";
    
    if($debug) echo $sql;

    $results=$db->Execute($sql);
    $totalCount=$results->RecordCount();

    echo '{"totalCount":'.$totalCount.',"itemslist":[';

    $counter=0;
    while($row=$results->FetchRow()){
        $description=escapeJsonString(trim($row['item_description']));
        echo '{"PartCode":"'.$row['partcode'].'","Description":"'.$description.'","Category":"'.$row['purchasing_class'].'"}';

        $counter++;
        if ($counter <> $totalCount) {
            echo ",";
        }
    }

    echo "]}";

}

function getDrugStatement($drug,$startDate,$endDate,$start,$limit) {
    global $db;
    $debug=false;

    $searchParam=$_REQUEST['searchParam'];

    $sql = "SELECT CONCAT(b.`order_date`,' ',b.`order_time`) AS OrderDate,b.`OP_no` as pid,p.encounter_nr,b.`patient_name`
            ,b.`item_id`,b.`Item_desc`,p.price,p.dosage,p.times_per_day,p.days,p.prescriber,b.`qty`,b.`orign_qty` as issued,
            b.balance,(b.`orign_qty`*b.price) as TotalCost,b.`input_user` as issuedBy
            FROM care_ke_internal_orders b LEFT JOIN care_encounter_prescription p on b.presc_nr=p.nr
            WHERE  b.order_date between '$startDate' and '$endDate'";

    if($searchParam){
        $sql=$sql." AND b.`item_id`='$searchParam'";
    }else{
        $sql=$sql." AND b.`item_id`='$drug'";
    }

    $sql=$sql." ORDER BY b.`order_date` DESC limit $start,$limit";

    if($debug) echo $sql;


    $results=$db->Execute($sql);
    $totalCount=$results->RecordCount();

    echo '{"totalCount":'.$totalCount.',"drugstatement":[';

    $counter=0;
    while($row=$results->FetchRow()){
        echo '{"OrderDate":"'.$row['OrderDate'].'","Pid":"'.$row['pid'].'","EncounterNo":"'.$row['encounter_nr']
            .'","PatientName":"'.$row['patient_name'].'","Price":"'.number_format($row['price'],2).'","Dosage":"'.$row['dosage'].'","Dosage":"'.$row['dosage']
            .'","TimesPerDay":"'.$row['times_per_day'].'","Days":"'.$row['days'].'","Dosage":"'.$row['dosage'].'","TotalQty":"'.$row['qty']
            .'","Issued":"'.$row['issued'].'","Balance":"'.$row['balance'].'","TotalCost":"'.$row['TotalCost']
            .'","Prescriber":"'.$row['prescriber'].'","Issuedby":"'.$row['issuedBy'].'"}';

        $counter++;
        if ($counter <> $totalCount) {
            echo ",";
        }

    }

    echo "]}";
}

function getRevenueByPatients($drug) {
    global $db;

    $date1 = $_REQUEST['date1'];
    $date2 = $_REQUEST['date2'];


    $sql = "SELECT CONCAT(b.`order_date`,' ',b.`order_time`) AS OrderDate,b.`OP_no` as pid,b.`patient_name`,b.`item_id`,
              b.`Item_desc`,b.`qty`,b.`orign_qty`,b.`input_user` FROM care_ke_internal_orders b
            WHERE b.`item_id`='$drug' ";

    if (isset($date1) && isset($date2) && $date1 <> "" && $date1 <> "") {
        $date = new DateTime($date1);
        $dt1 = $date->format("Y-m-d");

        $date = new DateTime($date2);
        $dt2 = $date->format("Y-m-d");

        $sql = $sql . " and b.order_date between '$dt1' and '$dt2' ";
    } else {
        $sql = $sql . " and b.order_date<=now()";
    }

    $sql = $sql . " ORDER BY b.Item_desc DESC";
    //echo $sql;
    //p.pid,p.name_first,p.name_last,p.name_2,b.bill_date,b.bill_number,b.total

    if ($request = $db->Execute($sql)) {

        echo '<table width=100% height=14><tr bgcolor=#6699cc>
                    <td align="left">PID</td>
                    <td align="left">Patient Name</td>
                    <td align="left">OrderDate</td>
                    <td align="left">Item ID</td>
                    <td align="left">Description</td>
                    <td align="left">Qty Prescribed</td>
                    <td align="left">Qty Issued</td>
                    <td align="left">User</td>
                 </tr>';
        $bg = '';
//        $total='';
        while ($row = $request->FetchRow()) {
            if ($bg == "silver")
                $bg = "white";
            else
                $bg = "silver";
            echo "<tr bgcolor= $bg height=16>
                    <td><a href='#' onclick='getPatientStatement('".$row['pid']."')'>$row[pid]</a></td>
                    <td><a href='#' onclick='getPatientStatement('$row[pid]')'>$row[patient_name]</a></td>
                    <td> $row[OrderDate]</td>
                    <td>$row[item_id]</td>
                    <td align=left> $row[Item_desc]</td>
                    <td align=center>$row[qty] </td>
                    <td align=center>$row[orign_qty]</td>
                    <td align=left>$row[input_user]</td>
             </tr>";
            $lsum = $lsum + $row['orign_qty'];
            $rowbg = 'white';
        }
        $rowCnt = $request->RecordCount();

        echo "<tr><td colspan=3><b>Total Patients issued were $rowCnt</b></td><td align=right><b>Total Quantity Issued " . number_format($lsum, 2) . "</b></td></tr>";
        echo '</table>';
    }else {
        echo 'SQl:Error=' . $sql;
    }
}

function getRevenueByItem($startDate,$endDate) {
    global $db;
    $debug=false;

    $sql = "SELECT b.`item_id`,b.`Item_desc`,p.price,sum(b.`qty`) as qty,sum(b.`orign_qty`) as issued,
            b.balance,sum((b.`orign_qty`*b.price)) as TotalCost,p.drug_class
            FROM care_ke_internal_orders b LEFT JOIN care_encounter_prescription p on b.presc_nr=p.nr
            WHERE p.drug_class in ('Drug_list','Medical Supplies')";

            if ($startDate <> "" && $endDate <> "") {
                  $sql = $sql . " and b.order_date between '$startDate' and '$endDate' ";
            }

      $sql = $sql . "GROUP BY item_id ORDER BY Total DESC";
      if($debug) echo $sql;

      $results=$db->Execute($sql);
      $totalCount=$results->RecordCount();

      echo '{"totalCount":'.$totalCount.',"pharmacyrevenue":[';

    $counter=0;
    while($row=$results->FetchRow()){
        echo '{"ItemNo":"'.$row['item_id'].'","Description":"'.trim($row['Item_desc']).'","Category":"'.$row['drug_class']
            .'","UnitPrice":"'.$row['price'].'","Quantities":"'.$row['issued'].'","TotalAmount":"'.$row['TotalCost'].'"}';

        $counter++;
        if ($counter <> $totalCount) {
            echo ",";
        }

    }

    echo "]}";
}



function getRevenueByCat($startDate,$endDate,$category) {
    global $db;
    $debug=false;
    
    $sql = "SELECT b.partcode,b.Description,b.service_type,b.price,SUM(b.total) AS Total, sum(b.qty) AS drug_Count
            FROM care_ke_billing b  LEFT JOIN care_tz_drugsandservices d on b.partcode=d.partcode 
            WHERE b.service_type in ('drug_list','Medical Supplies')";

     if ($startDate <> "" && $endDate <> "") {
                 $sql = $sql . " and b.bill_date between '$startDate' and '$endDate' ";
      }
      
      if($category<>''){
          $sql=$sql." AND d.category='$category'";
      }

    $sql = $sql . "GROUP BY b.partcode ORDER BY Total DESC";
    if($debug) echo $sql;

    $results=$db->Execute($sql);
    $totalCount=$results->RecordCount();

    echo '{"total":'.$totalCount.',"pharmcatrevenue":[';

    $counter=0;
    while($row=$results->FetchRow()){
        $description=escapeJsonString($row['Description']);
        echo '{"ItemNo":"'.$row['partcode'].'","Description":"'.$description.'","Category":"'.$row['service_type'].'","UnitPrice":"'.$row['price'].'","Quantities":"'.$row['drug_Count'].'","TotalAmount":"'.$row['Total'].'"}';

        $counter++;
        if ($counter <> $totalCount) {
            echo ",";
        }

    }

    echo "]}";
}

function exportExcel() {
    global $db;
    $accno = $_REQUEST['acc1'];


    $sql = "select p.pid,p.name_first,p.name_last,p.name_2,b.bill_date,b.bill_number,sum(b.total) as total 
            from care_person p left join care_ke_billing b
            on p.pid=b.pid where b.`IP-OP`=2 and p.insurance_ID='$accno' group by p.pid order by bill_date asc";

    $request = $db->Execute($sql);

    echo '<table width=100% height=14><tr bgcolor=#6699cc>
                    <td align="center">pid</td>
                    <td align="center">Names</td>
                    <td align="center">Bill Date</td>
                    <td align="center">Bill Number</td>
                    <td align="center">Total</td>
                    <td align="center">Running Total</td>
                 </tr>';
    $bg = '';
//        $total='';
    while ($row = $request->FetchRow()) {
        if ($bg == "silver")
            $bg = "white";
        else
            $bg = "silver";
        $total = intval($row['total'] + $total);
        echo '<tr bgcolor=' . $bg . ' height=16>
                    <td>' . $row['pid'] . '</td>
                    <td>' . $row['name_first'] . ' ' . $row['name_last'] . ' ' . $row['name_2'] . '</td>
                    <td>' . $row['bill_date'] . '</td>
                    <td>' . $row['bill_number'] . '</td>
                    <td>' . number_format($row['total'], 2) . '</td>
                    <td>' . number_format($total, 2) . '</td>    
                   
             </tr>';

        $rowbg = 'white';
    }
    echo '</table>';
}


function getPatientStatement() {
    global $db;
    $debug=false;

    $date1 = $_REQUEST['startDate'];
    $date2 = $_REQUEST['endDate'];
    $pid = $_REQUEST['pid'];

   // echo $date1 ." ".$date2;

    $sql = "SELECT b.`order_date`,b.`order_time`,b.`OP_no` AS pid,e.encounter_class_nr,b.`patient_name`,b.`item_id`,
        b.`item_desc`,b.`qty`,b.price,b.`orign_qty`,b.`total`,b.`input_user` FROM care_ke_internal_orders b
        LEFT JOIN `care_encounter_prescription` p ON b.`presc_nr`=p.`nr`
        LEFT JOIN care_encounter e ON p.`encounter_nr`=e.`encounter_nr` AND b.`order_date`=e.`encounter_date`";

    if (isset($date1) && isset($date2) && $date1 <> "" && $date1 <> "") {
        $date = new DateTime($date1);
        $dt1 = $date->format("Y-m-d");

        $date = new DateTime($date2);
        $dt2 = $date->format("Y-m-d");

        $sql = $sql . " WHERE b.order_date between '$dt1' and '$dt2' ";
    } else {
        $sql = $sql . " WHERE b.order_date<='now()'";
    }
	
	if(isset($pid) and $pid<>''){
		$sql=$sql." and b.`OP_no`='$pid'";
	}

    $sql = $sql . " order by b.order_date desc";
   if($debug) echo $sql;


    if ($request = $db->Execute($sql)) {
        $rowCnt=$request->RecordCount();

        echo '{ "total":"' . $rowCnt . '","drugstatement":[';

        $counter=0;
        $runningTotal=0;
        while ($row = $request->FetchRow()) {


            if($row['encounter_class_nr']=2){
                $encounter='OP';
            }else{
                $encounter='IP';
            }
			$runningTotal=$runningTotal+ ($row['price']*$row['orign_qty']);
            echo '{"PID":"' . $row['pid'] . '","PrescriptionDate":"' . $row['order_date'] . '","PrescriptionTime":"' .  $row['order_time']
                . '","Admission":"' .  $encounter . '","PartCode":"' .  $row['item_id']. '","Description":"' .  $row['item_desc']
                . '","QtyIssued":"' .  $row['orign_qty']. '","Price":"' .  $row['price']. '","Total":"' .  $row['price']*$row['orign_qty']
                . '","RunningTotal":"' . $runningTotal.'"}';

            if ($counter <> $rowCnt) {
                echo ",";
            }
            
            $counter++;
        }

        echo "]}";

    } else {
        echo 'SQL: Failed=' . $sql;
    }
}

function getWardsInfo(){
    global $db;
    echo '{"wardinfo":[';
    $sql='SELECT nr FROM care_ward';
    $result = $db->Execute($sql);
    while($row = $result->FetchRow()){
        getWardDetails($row[0]);
    }
    echo ']}';
}

function getWardDetails($wrdNO) {
    global $db;
    $db->debug = 0;
    require('./roots.php');
    require_once($root_path . 'include/care_api_classes/class_ward.php');
    $ward_obj = new Ward;

    $ward_nr = $wrdNO;
    if ($ward_info = &$ward_obj->getWardInfo($ward_nr)) {
        $room_obj = &$ward_obj->getRoomInfo($ward_nr, $ward_info['room_nr_start'], $ward_info['room_nr_end']);
        if (is_object($room_obj)) {
            $room_ok = true;
        } else {
            $room_ok = false;
        }
        $nr_beds = $ward_obj->countBeds($ward_nr);

        $patients_obj = &$ward_obj->getDayWardOccupants($ward_nr);
       
        if (is_object($patients_obj)) {
            # Prepare patients data into array matrix
            while ($buf = $patients_obj->FetchRow()) {
                $patient[$buf['room_nr']][$buf['bed_nr']] = $buf;
            }
            $patients_ok = true;
        } else {
            $patients_ok = false;
        }

        $ward_ok = true;

        if ($ward_ok) {
            $room_info = array();
            $occ_beds = 0;
            $lock_beds = 0;
            $males = 0;
            $females = 0;

            for ($i = $ward_info['room_nr_start']; $i <= $ward_info['room_nr_end']; $i++) {
                $room_info = $room_obj->FetchRow();
                for ($j = 1; $j <= $room_info['nr_of_beds']; $j++) {
                        if (isset($patient[$i][$j])) {
                            $bed = $patient[$i][$j];
                            $occ_beds++;
                        } else {
                            $is_patient = false;
                            $bed = NULL;
                        }
                    

                    if ($is_patient) {
                        if (strtolower($bed['sex']) == 'f') {
                            $females++;
                        } elseif (strtolower($bed['sex']) == 'm') {
                            $males++;
                        }
                    }
                }

                # Check if bed is locked
                if (stristr($room_info['closed_beds'], $j . '/')) {
                    $bed_locked = true;
                    $lock_beds++;
                    # Consider locked bed as occupied so increase occupied bed counter
                    $occ_bed++;
                } else {
                    $bed_locked = false;
                }
            } // end of ward loop
            $sql = 'SELECT c.`nr`, c.`ward_id`, c.`name` FROM care_ward c where  c.`nr`="'.$ward_nr.'"';
            $result = $db->Execute($sql);
            $numRows = $result->RecordCount();
            $row = $result->FetchRow();
            $occ_percent = ceil(($occ_beds / $nr_beds) * 100);

            # Nr of vacant beds
            $vac_beds = $nr_beds - $occ_beds;
            echo '{Ward:"' . $row[2] . '",';
            echo 'Beds:"' . $nr_beds . '",';
            echo 'Occupancy:"' . $occ_percent . '%",';
            echo 'Occupied:' . $occ_beds . ',';
            echo 'Vacant:' . $vac_beds . ',';
//            echo 'Locked:' . $lock_beds . ',';
            echo 'Male:"' . $males . '",';
            echo 'Female:"' . $females. '"},';

        }
    }
}



