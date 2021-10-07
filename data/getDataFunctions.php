<?php   //111380
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');
require ($root_path . 'include/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_measurement.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
require_once($root_path.'include/care_api_classes/class_tz_insurance.php');
include_once($root_path.'include/care_api_classes/class_person.php');
require_once($root_path.'include/care_api_classes/class_ward.php');
require_once($root_path.'include/care_api_classes/class_tz_billing.php');
require_once($root_path . 'include/care_api_classes/class_department.php');
require_once($root_path . 'include/care_api_classes/class_tz_drugsandservices.php');
require_once ($root_path . 'include/care_api_classes/class_lab.php');
require_once ('weberpFunctions.php');

$ward_obj= new Ward;
$bill_obj= new bill;
$person=new Person;
$enc_obj=new Encounter;
$insurance=new Insurance_tz;
$dept_obj = new Department;
$items_obj= new DrugsAndServices;
$insurance_obj=new Insurance_tz;
//$activity

if(!$encoder) $encoder=$_COOKIE[$local_user.$sid];

$limit = $_REQUEST['limit'];
$start = $_REQUEST['start'];
$formStatus = $_REQUEST['formStatus'];
$searchParam = $_REQUEST['sParam'];
$pid = $_REQUEST['pid'];
$item_number = $_REQUEST['partcode'];
$partcode = $_REQUEST['partcode'];
$item_description = $_REQUEST['item_description'];
$item_full_description = $_REQUEST['item_full_description'];
$unit_price = $_REQUEST['unit_price'];
$purchasing_class = $_REQUEST['purchasing_class'];
$category = $_REQUEST['category'];
$itemStatus = $_REQUEST['item_status'];
$sellingPrice = $_REQUEST['selling_price'];
$maximum = $_REQUEST['maximum'];
$minimum = $_REQUEST['minimum'];
$reorder = $_REQUEST['reorder'];
$unitMeasure = $_REQUEST['unit_measure'];
$icdParam = $_REQUEST['query'];
$store=$_REQUEST['store'];
$prescNo=$_REQUEST['prescNo'];
$store=$_REQUEST['store'];

$encNr = ($_REQUEST['encNr'])?($_REQUEST['encNr']):($_POST['encNr']);
$procedureNo= $_REQUEST['procedureNo'];
$procedureType= $_REQUEST['procedureType'];
$wrdNo=$_REQUEST['wardNo'];
$rm=$_REQUEST['room'];
$bed=$_REQUEST['bed'];
if(is_numeric($bed)){
    $bd=$bed;
}else{
    $bd=ord(strtolower($bed))-96;
}

$claimNo = $_REQUEST['claimNo'];
$bill_number = $_REQUEST['bill_number'];
$ward_nr=$_REQUEST['wardId'];
$currUser=$_SESSION["sess_login_username"];
$includeReceipt=$_REQUEST['includeReceipt'];
$includeNhif=$_REQUEST['includeNhif'];
$invoiceType=$_REQUEST['invoiceType'];
//$value = json_decode($_REQUEST['data']);
//echo 'The id is ' .$value[0][0];

$lab_obj = new Lab($encNr);
$lab_obj_sub = new Lab($encNr, true);
$typeID=$_REQUEST['typeID'];

$units = 'each';
$items='nr,ward_id,name';
$ward_info=$ward_obj->getAllWardsItemsObject($items);
$labNo=($_REQUEST['labNo']) ? ($_REQUEST['labNo']) : $_POST['labNo'];

$task = ($_REQUEST['task']) ? ($_REQUEST['task']) : $_POST['task'];
$accDB=$_SESSION['sess_accountingdb'];

$startDate=$_REQUEST['startDate'];
$endDate=$_REQUEST['endDate'];

switch ($task) {
    case "getPatientSafety":
        getPatientSafety($encNr);
        break;
    case "getAnesthesiaCharts":
        getAnesthesiaCharts($encNr);
        break;
    case "CreateAnesthesiaForm":
        CreateAnesthesiaForm($_POST);
        break;
    case "createCheckList1":
        CreateCheckList1($_POST);
        break;
    case "createCheckList2":
        CreateCheckList2($_POST);
        break;
    case "getSurgicalCheckLists":
        getSurgicalCheckLists($start, $limit);
        break;
    case "createCheckList3":
        CreateCheckList3($_POST);
        break;
    case "getItemsList":
        getItemsList($store,$searchParam,$accDB, $start, $limit);
        break;
    case "getServicesList":
        getServicesList($start,$limit);
        break;
    case "getStatusList":
        getStatusList();
        break;
    case "InsertItem":
        if ($formStatus == 'insert') {
            InsertItem($partcode, $item_description, $item_full_description, $unit_price, $purchasing_class, $category, $itemStatus, $sellingPrice, $maximum, $minimum, $reorder, $unitMeasure);
        } else if ($formStatus == 'update') {
            updateItem($partcode, $item_description, $item_full_description, $unit_price, $purchasing_class, $category, $itemStatus, $sellingPrice, $maximum, $minimum, $reorder, $unitMeasure);
        }
        break;
    case "createBooking":
        if ($formStatus == 'insert') {
            createBooking($_POST);
        } else if ($formStatus == 'update') {
            updateBooking($_POST);
        }
        break;
    case "getItemsCategory":
        getItemsCategory($start, $limit);
        break;
    case "getStaff":
        getStaff($start, $limit);
        break;
    case "getPatientDetails":
        getPatientDetails($searchParam,$person,$dept_obj,$ward_obj,$enc_obj,$items_obj,$start, $limit);
        break;
    case "getTheatreRooms":
        getTheatreRooms($start, $limit);
        break;
    case "getProcedureClass":
        getProcedureClass($start, $limit);
        break;
    case "getProcedureClassList":
        if (isset($_REQUEST['data'])) {
            writeprocedureclass();
        } else {
            getProcedureClassList();
        }
        break;
    case "getProcedures":
        getProcedures($searchParam, $start, $limit);
        break;
    case "getDiagnosisList":
        getDiagnosisList($icdParam, $start, $limit);
        break;
    case "getClassCodes":
        getClassCodes($start, $limit);
        break;
    case "getTheatreList":
        getTheatreList($start, $limit);
        break;
    case "getItemsSubCategory":
        getItemsSubCategory($start, $limit);
        break;
    case "getUnitsofMeasure":
        getUnitsofMeasure($start, $limit);
        break;
    case "getStoreLocations":
        getStoreLocations($start, $limit);
        break;
    case "getItemLocation":
        getItemLocations($start, $limit);
        break;
    case "deleteItem":
        deleteItem();
        break;
    case "addSpongeItems":
        addSpongeItems($encNr,$procedureType,$procedureNo);
        break;
    case "getSpongeItems":
        getSpongeItems($encNr,$procedureNo,$procedureType);
        break;
    case "getPendingPrescriptions":
        getPendingPrescriptions();
        break;
    case "saveVitals":
        saveVitals();
        break;
    case "getStockLevels":
        getStockLevels($start, $limit,$partcode);
        break;
    case "dischargePatients":
        dischargePatients($enc_obj,$person,$encoder);
        break;
    case "getWaitingList":
        getWaitingList($wrdNo,$ward_obj);
        break;
    case "getCounties":
        getCounties();
        break;
    case "getClinicsList":
        getClinicsList($dept_obj);
        break;
    case "getPrescriptions":
        getPrescriptions($encNr,$store);
        break;
    case "getVitals":
        getVitals($encNr);
        break;
    case "getDiagnosis":
        getDiagnosis($encNr);
        break;
    case "getLabTests":
        getLabTests($encNr);
        break;
    case "getNotes":
        getNotes($encNr);
        break;
    case "getRadiology":
        getRadiology($encNr);
        break;
    case "getClinicsInfo":
        getClinicsInfo();
        break;
    case "getClinicalRooms":
        getClinicalRooms();
        break;
    case "getAnnouncements":
        getAnnouncements();
        break;
    case "getOpdPatients":
        getOpdPatients($dept_obj,$ward_obj,$items_obj,$person);
        break;
    case "getIcd10Code":
        getIcd10Codes($start, $limit);
        break;
    case "saveComplaints":
        saveComplaints();
        break;
    case 'assignBed':
        assignBed($encNr,$wrdNo,$rm,$bd,$ward_obj);
        break;
    case 'getLabPatients':
        getLabPatients();
        break;
    case 'getLabParams':
        getLabParams();
        break;
    case "getPendingTests":
        getPendingTests($labNo);
        break;
    case "collectSpecimen":
        collectSpecimen($labNo,$encNr);
        break;
    case "getSpecimens":
        getSpecimens();
        break;
    case "getStatusLog":
        getStatusLog($labNo);
        break;
    case "receiveSpecimen":
        receiveSpecimen($labNo,$encNr);
        break;
    case "saveLabResults":
        saveLabResults($lab_obj,$lab_obj_sub,$labNo,$encounter_nr);
        break;
    case "getLabResults":
        getLabResults($labNo,$item_id);
        break;
    case "verifyResults":
        verifyResults($labNo,$encNr);
        break;
    case "approveResults":
        approveResults($labNo,$encNr);
        break;
    case "getLabParamGroups":
        getLabParamGroups();
        break;
    case "createStaff":
        createStaff();
        break;
    case "checkUserAccess":
        checkUserAccess($currUser,$activity);
        break;
    case "getHaemoParams":
        getHaemoParams();
        break;
    case "updateUserRights":
        updateUserRights();
        break;
    case "getAlllabTests":
        getAlllabTests();
        break;
    case "getTransNos":
        getTransNos($typeID);
        break;
    case "saveInternalOrder":
        saveInternalOrder();
        break;
    case "selectPendingOrders":
        selectPendingOrders();
        break;
    case "detailedOrderItems":
        detailedOrderItems();
        break;
    case "serviceOrders":
        serviceOrders($items_obj);
        break;
    case "adjustStock":
        adjustStock($currUser,$items_obj);
        break;
    case "savePrescription":
        savePrescription($bill_obj,$encNr,$currUser);
        break;
    case "getPatientPresc":
        getPatientPresc($encNr,$pid);
        break;
    case "issueDrugs":
        issueDrugs($items_obj,$bill_obj);
        break;
    case "getDrugsToReturn":
        getDrugsToReturn($pid,$store);
        break;
    case "returnOrderedDrugs":
            returnOrderedDrugs();
            break;
    case "getInternalOrders":
        getInternalOrders();
        break;
    case "getStockMovements":
        getStockMovements();
        break;
    case "getStockAdjustments":
        getStockAdjustments();
        break;
    case "getValuationReport":
        getValuationReport();
        break;
    case "getIssuedPrescriptions":
        getIssuedPrescriptions($store);
        break;
    case "cancelOrder":
        cancelOrder();
        break;
    case "getNhifRates":
        getNhifRates();
        break;
    case "getBillNumbers":
        getBillNumbers($pid);
        break;
    case "getBills":
        getBills($pid, $bill_number);
        break;
    case "getEncounterNumbers":
        getEncounterNumbers($pid);
        break;
    case "getEncounter":
        getEncounter($enc_obj, $pid);
        break;
    case "geNextTransNo":
        geNextTransNo();
        break;
    case "getTotalBill":
        getTotalBill($pid, $bill_number);
        break;
    case "getNhifCreditNo":
        getNhifCreditNo();
        break;
    case "combineBills":
        combineBills($pid);
        break;
    case "deleteBillItem":
        deleteBillItem($pid);
        break;
    case "deleteReceiptItem":
        deleteReceiptItem($pid);  //32367
        break;
    case "getPatientsInWard":
        getPatientsInWard($ward_obj,$ward_nr);
        break;
    case "saveDebit":
        saveDebits($enc_obj,$insurance_obj);
        break;
    case "saveNotes":
        saveNotes($currUser);
        break;
    case "getWardInfo":
        getWardInfo($ward_info);
        break;
    case "generateInvoice":
        generateInvoice($pid,$encNr,$bill_number,$includeReceipt,$includeNhif,$invoiceType);
        break;
    case "getDepartments":
        getDepartments();
        break;
    case "getWardsList":
        getWardsList();
        break;
    case "getXrayItems":
        getXrayItems();
    break;
    case "updateBillItems":
        updateBillItems($_POST,$pid);
        break;
    case "insertNhifCredit";
        insertNhifCredit($_POST, $bill_obj);
        break;
    case "getNHIFCredits":
        getNHIFCredits($startDate, $endDate);
        break;
    case "getIPPendingBills":
        getIPPendingBills();
    break;
    case "getCurrentPrescriptions":
        getCurrentPrescriptions($encNr);
    break;
    case "getReceiptNo":
        getReceiptNo($pid,$encNr);
    break;
    case "saveLabRequest":
        saveLabRequest($bill_obj,$encNr,$currUser);
        break;
    default:
        echo "{failure:true}";
        break;
}//end switch

function getReceiptNo($pid,$encNr){
    global $db;
    $debug=false;

    $sql="SELECT batch_no,SUM(total) AS amount FROM care_ke_billing r
    LEFT JOIN care_encounter e ON r.`pid`=e.`pid`
    LEFT JOIN care_tz_drugsandservices d ON r.`partcode`=d.`partcode`
    WHERE  r.pid='$pid' AND service_type='Payment'
    AND e.encounter_nr='$encNr' AND d.`purchasing_class` IN('drug_list','medical-supplies')";
   
    if($debug) echo $sql;
  
    $request=$db->Execute($sql);
  
    $row=$request->FetchRow();

    return $row;
}

function getCurrentPrescriptions($encNr){
    global $db;
    $debug=false;

    $sql="SELECT nr,encounter_nr,partcode,article,dosage,`times_per_day`,`days`,notes,`status` FROM `care_encounter_prescription`
     where encounter_nr='$encNr'";

    echo "[";
    $request=$db->Execute($sql);
    $counter=0;
    while($row=$request->FetchRow()){
        echo '{"Nr":"' .  $row['nr'] . '","EncounterNo":"' . $row['encounter_nr'] . '","PartCode":"' .  $row['partcode'] 
             . '","Description":"' .  $row['article']. '","Dosage":"' .  $row['dosage'] 
             . '","TimesPerDay":"' .  $row['times_per_day'] . '","Days":"' .  $row['days']
             . '","Notes":"' .  $row['notes']. '","Status":"' .  $row['status']. '"}';

        $counter++;
        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']}';
}

function getNHIFCredits($startDate, $endDate) {
    global $db;
    $debug = false;
    $searchParam=$_REQUEST['searchParam'];

    $sql = "SELECT b.creditNo,b.inputDate,b.admno,b.Names,b.admDate,b.disDate,b.wrdDays,b.nhifNo,b.nhifDebtorNo,
	b.debtorDesc, b.invAmount,b.totalCredit,b.balance,b.bill_number,b.inputUser
	FROM care_ke_nhifcredits b 
    WHERE b.bill_number<>'' ";

    if($searchParam<>'' and is_numeric($searchParam)){
        $sql.="and  pid='$searchParam'";
    }else{
        $sql.="and b.Names like '%$searchParam%'";
    }

    if ($startDate<>'' && $endDate<>'') {
        $date1 = new DateTime(date($startDate));
        $dt1 = $date1->format("Y-m-d");

        $date2 = new DateTime(date($endDate));
        $dt2 = $date2->format("Y-m-d");

        $sql = $sql . "and b.inputDate between '$dt1' and '$dt2'";
    }
    if ($debug)
    echo $sql;

    $result = $db->Execute($sql);
    $total=$result->RecordCount();

    $counter=0;

    echo '{"total":'.$total.',"nhifcredits":[';
    while ($row = $result->FetchRow($result)) {
        echo '{"CreditNo":"'.$row['creditNo'] . '","Pid":"'. $row['admno'] . '","Names":"'. $row['Names']
            . '","BillNumber":"'. $row['bill_number'] .'","InputDate":"'.$row['inputDate'] .'","AdmissionDate":"'.$row['admDate']
            . '","DischargeDate":"'.$row['disDate'] .'","ReleaseDate":"'.$row['disDate'] . '","WardDays":"'. $row['wrdDays']
            . '","NhifNo":"'.$row['nhifNo'] .'","NhifDebtorNo":"'.$row['nhifDebtorNo'] . '","TotalCredit":"'. number_format($row['totalCredit'],2)
            . '","InvoiceAmount":"' . number_format($row['invAmount'],2) . '","Balance":"' . number_format($row['balance'],2) .'"}';

            $counter++;

            if ($counter <> $total) {
                echo ",";
            }

    }

    echo "]}";
}

function insertNhifCredit($nhifDetails, $bill_obj) {
    global $db;
    $debug = false;

    $new_bill_number = $bill_obj->checkBillEncounter($nhifDetails['encounterNr']);
    $input_User = $_SESSION['sess_login_username'];

    $user = $_SESSION['sess_login_username'];
    $sql = "INSERT INTO care_ke_nhifcredits(creditNo,bill_number,inputDate,admno,NAMES, admDate,
            disDate,wrdDays,nhifNo,nhifDebtorNo,debtordesc, totalCredit,invAmount, balance,inputUser)
	    VALUES('$nhifDetails[creditNo]','$new_bill_number','" . date('Y-m-d') . "','$nhifDetails[pid]', 
        '$nhifDetails[pname]','$nhifDetails[admissionDate]', '$nhifDetails[dischargeDate]','$nhifDetails[days]', 
                '$nhifDetails[nhifNo]','$nhifDetails[accno]', '$nhifDetails[nhifAccount]','$nhifDetails[totalCredit]',
                '$nhifDetails[invoiceAmount]','$nhifDetails[balance]','$input_User')";

    if ($debug)echo $sql;
    if ($db->Execute($sql)) {
        updateNhifBill($nhifDetails, $bill_obj);
        $invBalance = ($nhifDetails['totalCredit'] - $nhifDetails['invAmount']);
        if ($invBalance <> 0) {
            updateNhifGainloss($nhifDetails, $bill_obj);
        }
        echo '{success:true,"msg":"Successfully saved NHIF Credit"}';
    } else {
        echo '{failure:true,"msg":"Could not save NHIF Credit, Please check your values"}';
    }
}

function updateNhifBill($nhifDetails, $bill_obj) {
    global $db;
    $debug = false;
    $user = $_SESSION['sess_user_name'];
    $sql3 = "INSERT INTO care_ke_billing (pid, encounter_nr,insurance_id,bill_date,bill_time,`ip-op`,bill_number,service_type, 
    price ,`Description`,notes,input_user,`status`,`days`,qty,total,rev_code,batch_no)  
    VALUES('" . $nhifDetails['pid'] . "','" . $nhifDetails['encounterNr'] . "','" . $nhifDetails['nhifDbAc']. "','" 
    . date("Y-m-d") . "','" . date("H:i:s") . "','1','" . $nhifDetails['billNumber'] . "','NHIF','".$nhifDetails['totalCredit']
    ."','NHIF Credit No','NHIF Credit','$user','billed','" . $nhifDetails['days'] . "','1','".$nhifDetails['totalCredit']."','NHIF','".$nhifDetails['creditNo']."')";

    if ($debug) echo $sql3 . "<br>";
    if ($db->Execute($sql3)) {
        $trnsNo = $bill_obj->getTransNo(2);
        $sql4 = "INSERT into `care_ke_debtortrans`(`transno`,`transtype`,`accno`, `pid`,`transdate`,`bill_number`,`amount`
        ,`lastTransDate`,`lasttransTime`,`settled`,encounter_nr,accountNo)
                 VALUES('$trnsNo','2','NHIF', '$nhifDetails[pid]','" . date('Y-m-d') . "','$nhifDetails[billNumber]'
                 ,'$nhifDetails[totalCredit]','" . date('Y-m-d') . "','" . date('H:i:s') . "',0,'$nhifDetails[encounterNr]'
                 ,'$nhifDetails[nhifNo]')";
        if ($debug)echo $sql4;
        if ($db->Execute($sql4)) {
            $newTransNo = ($trnsNo + 1);
            $sql3 = "update care_ke_transactionNos set transNo=$newTransNo where typeid=2";
            if ($debug) echo $sql3;
            $db->Execute($sql3);
        }
    }
}

function updateNhifGainloss($nhifDetails, $bill_obj) {
    global $db;
    $debug = false;

    $invBalance = ($nhifDetails['totalCredit'] - $nhifDetails['invAmount']);
    $trnsNo = $bill_obj->getTransNo(2);
    $sql = "INSERT into `care_ke_debtortrans`(`transno`,`transtype`,`accno`, `pid`,`transdate`,`bill_number`
    ,`amount`,`lastTransDate`,`lasttransTime`,`settled`,encounter_nr,accountNo)
             VALUE('$trnsNo','2','NHIF2', '$nhifDetails[pid]','" . date('Y-m-d') . "','$nhifDetails[billNumber]'
             ,'$invBalance','" . date('Y-m-d') . "','" . date('H:i:s'). "',0,'$nhifDetails[encounterNr]','$nhifDetails[nhifNo]')";
    if ($debug) echo $sql;

    if ($db->Execute($sql)) {
        $newTransNo = ($trnsNo + 1);
        $sql2 = "update care_ke_transactionNos set transNo=$newTransNo where typeid=2";
        if ($debug) echo $sql2;
        $db->Execute($sql2);
    }
}

function updateBillItems($strData, $pid) {
    global $db;
    $debug = false;
    $UpdateRowsCount = $_POST['selectedCount'];

    $error = 0;
    if ($UpdateRowsCount > 1) {
        foreach ($strData as $key => $value) {
            //echo "<br> Items are in group ". $key;
            $sql = "UPDATE care_ke_billing SET ";
            foreach ($value as $k => $strVal) {
                //echo "<br> Items in second loop  $k and Value $strVal";
                if ($k == 'ID') {
                    $id = $strVal;
                }
                
                if($k=="Bill_Date"){
                    $date1 = new DateTime($strVal);
                    $strVal = $date1->format("Y-m-d");
                }
                
                $sql .= $k . '="' . $strVal . '", ';
                foreach ($strVal as $x => $xval) {
                    //echo "<br> Items in third loop  $x and Value $xval";
                }
            }
            $sql = substr($sql, 0, -2) . " WHERE ID='$id'";

            if ($debug)
                echo $sql;

            if ($db->Execute($sql)) {
                $error = 0;
            } else {
                $error = 1;
            }
        }
    } else {
        $sql = "UPDATE CARE_KE_BILLING SET ";
        $id = '';
        foreach ($strData as $key => $value) {
            $sql .= $key . '="' . $value . '", ';
            if ($key == 'ID') {
                $id = $value;
            }
            if ($key == 'Bill_Date') {
                $date1 = new DateTime($value);
                $value = $date1->format("Y-m-d");
            }
        }
        $sql = substr($sql, 0, -2) . " WHERE ID='$id'";
        if ($debug)
            echo $sql;
        if ($db->Execute($sql)) {
            $error = 0;
        } else {
            $error = 1;
        }
    }
    if ($error == 0) {
        $results = "{success: true,'Error':'Successfully saved Bill'}";
    //getAllBills($pid,$bill_number);
    } else {
        $results = "{failure: true,'Error':'Cannot update bill Error No $error'}"; // Return the error message(s)
    }
    echo $results;
}


function getAllBills($pid, $bill_number) {
    global $db;
    $debug = false;

    $pid2 = ($pid <> "" ? $pid = $pid : $pid = '1000');

    $sql = "SELECT ID,`pid`,`encounter_nr`,`insurance_id`,`IP-OP` as IPOP,`bill_date`,`bill_time`, `bill_number`, `batch_no`,`service_type`,
          `partcode`,`Description`,`price`,`qty`,`total`,`input_user`,
          `notes` FROM `care_ke_billing` where pid='$pid2'";

    if (isset($bill_number) && $bill_number <> '') {
        $sql = $sql . " and bill_number='$bill_number'";
    }

    if ($debug)
        echo $sql;

    $result = $db->Execute($sql);
    $total = $result->RecordCount();
    echo '{"bills":[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        echo '{"ID":"' . $row['ID'] . '","Pid":"' . $row['pid'] . '","Encounter_Nr":"' . $row['encounter_nr'] . '","IP-OP":"' . $row['IPOP'] . '","Bill_Date":"' . $row['bill_date']
        . '","Bill_Time":"' . $row['bill_time'] . '","Bill_Number":"' . $row['bill_number'] . '","Service_Type":"' . $row['service_type']
        . '","PartCode":"' . $row['partcode'] . '","Description":"' . $row['Description'] . '","Price":"' . number_format($row['price'], 2) . '","Qty":"' . $row['qty']
        . '","Total":"' . number_format($row['total'], 2) . '","InputUser":"' . $row['input_user'] . '"}';

        $counter++;

        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']}';
}

function getIPPendingBills(){
    global $db;
    $debug=false;
    $paymentPlan=$_REQUEST['paymentPlan'];
    $debtorCat=$_REQUEST['debtorCat'];
        
    $sql="SELECT p.pid,e.encounter_nr, p.name_first,p.name_last,p.name_2,e.encounter_date,
        e.current_ward_nr,w.name as ward,DATEDIFF(NOW(),e.`encounter_date`) AS BedDays 
        ,SUM(IF( b.service_type NOT IN('payment','NHIF'),total,0)) AS bill,
        SUM(IF(b.service_type IN ('payment','NHIF'),total,0)) AS payment,b.`bill_number`,c.`name` AS company
        FROM care_encounter e
        LEFT JOIN care_ke_billing b ON e.encounter_nr=b.`encounter_nr`
        LEFT JOIN care_person p  ON e.pid=p.pid
        LEFT JOIN care_ward w ON e.current_ward_nr=w.nr
        LEFT JOIN care_tz_company c ON p.`insurance_ID`=c.`id`
        WHERE e.encounter_class_nr=1 AND e.is_discharged=0
        GROUP BY pid
        ORDER BY w.name ASC";
       
    if($debug) echo $sql;
    $results=$db->Execute($sql);
    $total = $results->RecordCount();

    echo '[';
    $counter = 0;
    while ($row = $results->FetchRow()) {
        $names=$row['name_first'].' '.$row['name_last'].' '.$row['name_2'];
        $balance=$row['bill']-$row['payment'];
        if($row['company']<>'') { 
            $paymode=$row['company']; 
        }else{ 
            $paymode='CASH';
        }

        echo '{"Pid":"' . $row['pid'].'","EncounterNo":"' . $row['encounter_nr'].'","Names":"' . $names
            .'","AdmissionDate":"' . $row['encounter_date'].'","BedDays":"' . $row['BedDays']
            .'","Ward":"' . $row['ward'].'","BillNumber":"' . $row['bill_number']
                .'","Bill":"' . number_format($row['bill'],2).'","Payment":"' . $row['payment']
                .'","Balance":"' . $balance.'","PaymentMode":"' . $paymode. '"}';
        $counter++;
        if ($counter < $total) {
            echo ",";
        }
    }
    echo ']';
}

function getBills($pid, $bill_number) {
    //    var_dump($_POST);
        $json_data = $_POST['updatedBills']; // file_get_contents('php://input');
        $strData = json_decode($json_data);
    
        if (!empty($strData)) {
            updateBillItems($strData, $pid);
        } else {
            getAllBills($pid, $bill_number);
        }
    }


function getXrayItems(){
    global $db;
    $$deub=true;

    $sql="SELECT partcode,item_description,unit_price,purchasing_class FROM care_tz_drugsandservices WHERE purchasing_class='xray'";

    echo "[";
    $request=$db->Execute($sql);
    $counter=0;
    while($row=$request->FetchRow()){
        echo '{"PartCode":"' .  $row['partcode'] . '","Description":"' . $row['item_description'] . '","Price":"' .  $row['unit_price'] . '"}';

        $counter++;
        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']}';
}

function getSummaryInvoice($pid,$encNr,$bill_number,$includeReceipt,$includeNhif,$invoiceType){
    global $db;
    $debug=false;
    $invOutput='';

    $invOutput=$invOutput. "<div class=book>";

    if($invoiceType=="Final Summary Invoice"){
        $title="FINAL SUMMARY INVOICE";
    }else{
        $title="INTERIM SUMMARY INVOICE";
    }

    $sql = "SELECT p.pid,e.encounter_nr,e.encounter_class_nr,concat(name_first,' ',name_2,' ',name_last) as pnames
                            ,e.current_ward_nr,w.name as ward,e.current_dept_nr,d.name_formal as dept,p.date_birth,p.sex
                            ,e.encounter_date,e.discharge_date,e.current_room_nr  FROM care_person p
                    LEFT JOIN care_encounter e on p.pid=e.pid
                    LEFT JOIN care_ward w on e.current_ward_nr=w.nr 
                    LEFT JOIN care_department d on e.current_dept_nr=d.nr 
                    WHERE p.pid=$pid and e.encounter_nr=$encNr order by e.encounter_date desc";
    if($debug)
        echo $sql;
    $results = $db->Execute($sql);
    while ($row = $results->FetchRow()) {
        $datePrinted = date('Y-m-d H:i:s');
        $invOutput=$invOutput. "<div class=page>";
        $invOutput=$invOutput. "<div class=subpage>
                        <table border=0 width='100%'>
                            <tr>
                                <td colspan=7 class=invTitle>$title</td>
                            </tr>";


        $invOutput=$invOutput. "<tr><td class=itemTitles>PATIENT NAME:</td><td class=invDetails>" . ucfirst(strtolower($row['pnames'])) . "</td>
                               <td class=itemTitles>REG NO:</td><td class=invDetails>" . $pid . "</td>
                               <td class=itemTitles>Invoice NO:</td><td class=invDetails colspan='2'>" . $bill_number . "</td></tr>
                           <tr>";
        $invOutput=$invOutput. "<tr><td class=itemTitles>DATE OF BIRTH:</td><td class='invDetails'>".$row['date_birth']."</td><td class=itemTitles>SEX</td>
                               <td class=invDetails colspan='4'>" . (($row['sex'] == 'm') ? 'MALE' : 'FEMALE') . "</td></tr>
                           <tr>";
        if ($row['encounter_class_nr'] == 1) {
            $invOutput=$invOutput. "<td class=itemTitles>WARD NAME</td><td class=invDetails>" . ucfirst(strtolower($row['ward'])) . "</td>";
        } else {
            $invOutput=$invOutput. "<td class=itemTitles>DEPARTMENT</td><td class=invDetails>" . ucfirst(strtolower($row['dept'])) . "</td>";
        }
        $invOutput=$invOutput. "<td class=itemTitles>ROOM No</td><td class=invDetails colspan='4'>" . $row['current_room_nr'] . "</td>";

        $invOutput=$invOutput. "</tr>
                           <tr><td class=itemTitles>DATE OF ADMISSION:</td><td class=invDetails>".$row['encounter_date']."</td>
                               <td class=itemTitles>DATE OF DISCHARGE:</td><td class=invDetails colspan=4>".$row['discharge_date']."</td></tr>";

        $invOutput=$invOutput."<tr><td colspan='7'><hr></td></tr>";

        $invOutput=$invOutput."<tr><th colspan='2'>Service Description</th><th>Amount</th><th colspan='4'></th></tr>";

        $sql3 = "SELECT i.`item_Cat` AS service_type , sum(price) as price , sum(qty) as qty , sum(total) as total
                FROM care_ke_billing b
                LEFT JOIN care_tz_drugsandservices d ON b.`partcode`=d.`partcode`
                LEFT JOIN care_tz_itemscat i ON d.`category`=i.`catID`
                WHERE (pid ='" . $pid . "' AND service_type not in
                ('Payment','payment adjustment','NHIF') and `ip-op`=1) and bill_number=$bill_number group by d.`category`";

        $totalBill=0;
        if ($resultS = $db->Execute($sql3)) {
            $runningTotals=0;
            while ($row = $resultS->FetchRow()) {
                    $invOutput=$invOutput. "<tr><td colspan='2'>" . $row['service_type']. "</td>
                                        <td align='right'>" . number_format($row['total'],2) . "</td>
                                        <td colspan='4'></td><tr>";
                    $totalBill=$totalBill+$row['total'];
            }
        }
        $invOutput=$invOutput."<tr><td colspan='7'><hr></td></tr>";
        $invOutput=$invOutput. " <tr><td colspan=2 class=summaryTitle><b>Total Bill:</b></td>
                            <td class='summaryTitle'><b>Ksh." . number_format($totalBill,2) . "</b></td><td colspan='4'></td></tr> ";
        $invOutput=$invOutput."<tr><td colspan='2'></td><td colspan='5'><hr></td></tr>";

        $sqli = "SELECT * FROM care_ke_billing WHERE (pid ='" . $pid . "' AND service_type IN
            ('payment','payment adjustment','NHIF') and `ip-op`=1 and bill_number=$bill_number)";
        $resultsi = $db->Execute($sqli);
        $ntotals=0;
        $totalPaid=0;
        while ($rowi = $resultsi->FetchRow()) {
            if ($includeReceipt =='true' AND $rowi['service_type']=='Payment') {
                $invOutput = $invOutput . "<tr><td >$rowi[bill_date]</td>
                                               <td>" . $rowi['Description'] . "(" . intval($rowi['batch_no']) . ")</td>
                                               <td align='right'>Ksh." . number_format(intval($rowi['total']), 2) . "</td>
                                               <td colspan='4'></td> 
                                            <tr>";
                $ntotals=$ntotals+$rowi['total'];
            }

            if ($includeNhif =='true' AND $rowi['service_type']=='NHIF') {
                $invOutput = $invOutput . "<tr><td >$rowi[bill_date]</td>
                                               <td>" . $rowi['Description'] . "(" . intval($rowi['batch_no']) . ")</td>
                                               <td align='right'>Ksh." . number_format(intval($rowi['total']), 2) . "</td>
                                               <td colspan='4'></td> 
                                            <tr>";
                $ntotals=$ntotals+$rowi['total'];
            }

        }
        $totalPaid=$ntotals;
        $balance=$totalBill - $totalPaid;

        $invOutput=$invOutput."<tr><td colspan='7'><hr></td></tr>";
        $invOutput=$invOutput. " <tr><td colspan=2 class=summaryTitle><b>Total Paid:</b></td>
                            <td class='summaryTitle'><b>Ksh." . number_format($totalPaid,2) . "</b></td><td colspan='4'></td></tr> ";
        $invOutput=$invOutput."<tr><td colspan='2'></td><td colspan='5'><hr></td></tr>";
        $invOutput=$invOutput. " <tr><td colspan=2 class=summaryTitle><b>Balance:</b></td>
                            <td class='summaryTitle'><b>Ksh." . number_format($balance,2) . "</b></td><td colspan='4'></td></tr> ";
        $invOutput=$invOutput."<tr><td colspan='2'></td><td colspan='5'><hr></td></tr>";


        $invOutput=$invOutput. "</table>
                </div>
                 <div class=pageNos></div>
            </div>";

    }

    $invOutput=$invOutput."</div>";
    // $outPut=escapeJsonString($invOutput);
    echo $invOutput;
}


function getDetailInvoice($pid,$encNr,$bill_number,$includeReceipt,$includeNhif,$invoiceType){
global $db;
$debug=false;
$invOutput='';

 $invOutput=$invOutput. "<div class=book>";

    if($invoiceType=="Final Detail Invoice"){
        $title="FINAL DETAILED INVOICE";
    }else{
        $title="INTERIM DETAILED INVOICE";
    }

            $sql = "SELECT p.pid,e.encounter_nr,e.encounter_class_nr,concat(name_first,' ',name_2,' ',name_last) as pnames
                            ,e.current_ward_nr,w.name as ward,e.current_dept_nr,d.name_formal as dept,p.date_birth,p.sex
                            ,e.encounter_date,e.discharge_date,e.current_room_nr  FROM care_person p
                    LEFT JOIN care_encounter e on p.pid=e.pid
                    LEFT JOIN care_ward w on e.current_ward_nr=w.nr 
                    LEFT JOIN care_department d on e.current_dept_nr=d.nr 
                    WHERE p.pid=$pid and e.encounter_nr=$encNr order by e.encounter_date desc";
                        if($debug)
                            echo $sql;
            $results = $db->Execute($sql);
            while ($row = $results->FetchRow()) {
                $datePrinted = date('Y-m-d H:i:s');
                $invOutput=$invOutput. "<div class=page>";
                $invOutput=$invOutput. "<div class=subpage>
                        <table border=0 width='100%'>
                            <tr>
                                <td colspan=7 class=invTitle>$title</td>
                            </tr>";


                $invOutput=$invOutput. "<tr><td class=itemTitles>PATIENT NAME:</td><td class=invDetails>" . ucfirst(strtolower($row['pnames'])) . "</td>
                               <td class=itemTitles>REG NO:</td><td class=invDetails>" . $pid . "</td>
                               <td class=itemTitles>Invoice NO:</td><td class=invDetails colspan='2'>" . $bill_number . "</td></tr>
                           <tr>";
                $invOutput=$invOutput. "<tr><td class=itemTitles>DATE OF BIRTH:</td><td class='invDetails'>".$row['date_birth']."</td><td class=itemTitles>SEX</td>
                               <td class=invDetails colspan='4'>" . (($row['sex'] == 'm') ? 'MALE' : 'FEMALE') . "</td></tr>
                           <tr>";
                if ($row['encounter_class_nr'] == 1) {
                    $invOutput=$invOutput. "<td class=itemTitles>WARD NAME</td><td class=invDetails>" . ucfirst(strtolower($row['ward'])) . "</td>";
                } else {
                    $invOutput=$invOutput. "<td class=itemTitles>DEPARTMENT</td><td class=invDetails>" . ucfirst(strtolower($row['dept'])) . "</td>";
                }
                $invOutput=$invOutput. "<td class=itemTitles>ROOM No</td><td class=invDetails colspan='4'>" . $row['current_room_nr'] . "</td>";

                $invOutput=$invOutput. "</tr>
                           <tr><td class=itemTitles>DATE OF ADMISSION:</td><td class=invDetails>".$row['encounter_date']."</td>
                               <td class=itemTitles>DATE OF DISCHARGE:</td><td class=invDetails colspan=4>".$row['discharge_date']."</td></tr>";

                $invOutput=$invOutput."<tr><td colspan='7'><hr></td></tr>";

                $invOutput=$invOutput."<tr><th>Date</th><th>Type</th><th>Description</th><th>Price</th><th>Qty</th><th>Total</th><th>Running Totals</th></tr>";

                $sqlS = "SELECT bill_date,`IP-OP`,service_type,Description,price,qty,total FROM care_ke_billing WHERE pid = '$pid' and 
                            service_type NOT IN ('payment','payment adjustment','NHIF') and bill_number=$bill_number order by bill_date asc";

                $totalBill=0;
                if ($resultS = $db->Execute($sqlS)) {
                    $runningTotals=0;
                    while ($rowS = $resultS->FetchRow()) {
                        if ($rowS['IP-OP'] == 1) {
                            $runningTotals=$runningTotals+$rowS['total'];
                            $invOutput=$invOutput. "<tr>  <td>" . $rowS['bill_date'] . "</td>
                                        <td>" .  ucfirst(strtolower($rowS['service_type'])) . "</td>
                                        <td>" .  ucfirst(strtolower($rowS['Description'])) . "</td>
                                        <td align='right'>" . number_format($rowS['price'],2) . "</td>
                                        <td align='center'>" . (($rowS['qty'] > 0) ? $rowS['qty'] : 0) . "</td>
                                        <td align='right'>" . number_format(intval($rowS['total']),2) . "</td>
                                        <td align='right'>" . number_format($runningTotals,2).  "</td>
                                  <tr>";
                            $totalBill=$totalBill+$rowS['total'];
                        } else {
                            $invOutput=$invOutput. " <tr><td class='tdbolder' colspan=7>There was a problem retreivin patient data
                                            <br >Please Preview the Report In Outpationt Module.</td><tr><b>";
                        }

                    }
                }
                $invOutput=$invOutput."<tr><td colspan='7'><hr></td></tr>";
                $invOutput=$invOutput. " <tr><td colspan=5 class=summaryTitle><b>Total Bill:</b></td>
                            <td class='summaryTitle' colspan='2'><b>Ksh." . number_format($totalBill,2) . "</b></td></tr> ";
                $invOutput=$invOutput."<tr><td colspan='3'></td><td colspan='4'><hr></td></tr>";

                $sqli = "SELECT bill_date,service_type,Description,batch_no,total FROM care_ke_billing WHERE (pid ='" . $pid . "' AND service_type IN
            ('payment','payment adjustment','NHIF') and `ip-op`=1 and bill_number=$bill_number)";
           // echo $sqli;
                $resultsi = $db->Execute($sqli);
                $ntotals=0;
                $totalPaid=0;
               // echo "Receipt value ".$includeReceipt;
                while ($rowi = $resultsi->FetchRow()) {
                    if ($includeReceipt =="true" AND $rowi['service_type']=='Payment') {
                        $invOutput = $invOutput . "<tr><td >$rowi[bill_date]</td>
                                               <td>Bill </td>
                                               <td>" . $rowi['service_type'] . "</td>
                                               <td>" . $rowi['Description'] . "(" . intval($rowi['batch_no']) . ")</td>
                                               <td>Ksh</td>
                                               <td>" . number_format(intval($rowi['total']), 2) . "</td>
                                            <tr>";
                            $ntotals=$ntotals+$rowi['total'];
                    }

                    if ($includeNhif =="true" AND $rowi['service_type']=='NHIF') {
                        $invOutput = $invOutput . "<tr><td >$rowi[bill_date]</td>
                                               <td>Bill </td>
                                               <td>" . $rowi['service_type'] . "</td>
                                               <td>" . $rowi['Description'] . "(" . intval($rowi['batch_no']) . ")</td>
                                               <td>Ksh</td>
                                               <td>" . number_format(intval($rowi['total']), 2) . "</td>
                                            <tr>";
                        $ntotals=$ntotals+$rowi['total'];
                    }

                }
                $totalPaid=$ntotals;
                $balance=$totalBill - $totalPaid;

                $invOutput=$invOutput."<tr><td colspan='7'><hr></td></tr>";
                $invOutput=$invOutput. " <tr><td colspan=5 class=summaryTitle><b>Total Paid:</b></td>
                            <td class='summaryTitle' colspan='2'><b>Ksh." . number_format($totalPaid,2) . "</b></td></tr> ";
                $invOutput=$invOutput."<tr><td colspan='3'></td><td colspan='4'><hr></td></tr>";
                $invOutput=$invOutput. " <tr><td colspan=5 class=summaryTitle><b>Balance:</b></td>
                            <td class='summaryTitle' colspan='2'><b>Ksh." . number_format($balance,2) . "</b></td></tr> ";
                $invOutput=$invOutput."<tr><td colspan='3'></td><td colspan='4'><hr></td></tr>";


                $invOutput=$invOutput. "</table>
                </div>
                 <div class=pageNos></div>
            </div>";

            }

    $invOutput=$invOutput."</div>";
           // $outPut=escapeJsonString($invOutput);
    echo $invOutput;
}


function generateInvoice($pid,$encNr,$bill_number,$includeReceipt,$includeNhif,$invoiceType){
    if($invoiceType=="Interim Detail Invoice"){
        getDetailInvoice($pid,$encNr,$bill_number,$includeReceipt,$includeNhif,$invoiceType);
    }elseif ($invoiceType=="Interim Summary Invoice"){
        getSummaryInvoice($pid,$encNr,$bill_number,$includeReceipt,$includeNhif,$invoiceType);
    }elseif ($invoiceType=="Final Detail Invoice"){
        getDetailInvoice($pid,$encNr,$bill_number,$includeReceipt,$includeNhif,$invoiceType);
    }elseif($invoiceType=="Final Summary Invoice"){
        getSummaryInvoice($pid,$encNr,$bill_number,$includeReceipt,$includeNhif,$invoiceType);
    }
}

function saveNotes($currUser){
    global $db;
    $debug=false;
    $enounterNo=$_REQUEST['encounterNo'];
    $notesType=$_POST['notesType'];
    $notes=$_POST['notes'];
    $inputDate=date('Y-m-d');
    $inputTime=date('Y-m-d H:i:s');
    $create_time=date('Y-m-d H:i:s');
    $locationNr=$_REQUEST['locationNr'];
    $statusType="Doctors Room";
    $history="Created:". $create_time.":".$currUser;
    $status="Patients Notes Updated in the Doctors room";
    $statusDesc="Patients Notes Updated in the Doctors room";

    $sql="INSERT INTO `care_encounter_notes` (
          `encounter_nr`, `type_nr`, `notes`, `personell_name`,
          `date`, `time`, `location_nr`, `status`,`history`, `modify_id`,
          `modify_time`,`create_id`,`create_time`) 
        VALUES  (
            '$enounterNo','$notesType', '$notes', '$currUser','$inputDate', '$inputTime', '$locationNr','$status', '$history',
            '$currUser','$inputTime','$currUser','$inputTime')";
    if($debug) echo $sql;
    if($db->Execute($sql)){
        echo "{success:true}";
    }else{
        echo "{failure:true,Error:$sql}";
    }
}

function saveDebits($enc_obj, $insurance_obj) {
    global $db;
    $debug = false;

    $billDate = $_REQUEST['debitDate'];

    $date1 = new DateTime($_POST['debitDate']);
    $debitDate = $date1->format("Y-m-d");

    $inputUser = $_SESSION['sess_login_username'];
    $pid=$_REQUEST['pid'];

    $debitData = $_REQUEST['gridData'];
    $data = json_decode($debitData, true);

    $encounterNr = $_REQUEST['encounterNo'];
    $billNumber = $_REQUEST['billNumber'];
    $debitNo=$_REQUEST['debitNo'];

    $insuranceID=$insurance_obj->Get_insuranceID_from_pid($pid);

    $error=0;
    foreach ($data as $row) {
        $partcode = $row['partcode'];
        $description = $row['item_description'];
        $serviceType = $row['category'];
        $price = $row['unit_price'];
        $qty = $row['qty'];
        $Total = $row['unit_price']*$row['qty'];

        $sql = "INSERT care_ke_billing(encounter_nr,pid,bill_number,bill_date,service_type,item_number,
            Description,price,qty,total,`status`,`IP-OP`,prescribe_date,weberpSync,partcode,
            bill_time,ledger,insurance_id,batch_no,input_user,rev_code,debtorUpdate)
            values('" . $encounterNr . "','" . $pid . "','" . $billNumber . "','" . $debitDate
            . "','" . $serviceType . "','" . $partcode . "','" . $description . "','" . $price
            . "','" . $qty . "','" . $Total . "','billed','1','" . $debitDate
            . "',0,'" . $partcode . "','" . date('H:i:s')
            . "','DB','".$insuranceID."','".$debitNo."','" . $inputUser . "','" . $partcode . "',0)";
        if ($debug) echo $sql;

        if ($db->Execute($sql)) {
            $error=0;
        } else {
            $error=1;
        }
    }

    if ($error==0) {
        echo "{success:true}";
    } else {
        echo "{failure:true}";
    }
}

function getWardInfo($ward_info){
    $total=$ward_info->RecordCount();
    echo '{"total":"' . $total . '","wards":[';
    $counter = 0;
    while($stations=$ward_info->FetchRow()) {
        echo '{"Nr":"' .  $stations['nr'] . '","WardID":"' . $stations['ward_id'] . '","Name":"' .  $stations['name'] . '"}';

        $counter++;
        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']}';
}

function getPatientsInWard($ward_obj,$ward_nr){
    global $db;
    $debug=false;
    $ward_info=$ward_obj->getWardInfo($ward_nr);
    $room_obj=$ward_obj->getRoomInfo($ward_nr,$ward_info['room_nr_start'],$ward_info['room_nr_end']);
    if(is_object($room_obj)) {
        $room_ok=true;
    }else{
        $room_ok=false;
    }

    $nr_beds=$ward_obj->countBeds($ward_nr);
    $patients_obj=$ward_obj->getDayWardOccupants($ward_nr);

# Prepare patients data into array matrix
    if(is_object($patients_obj)){
        # Prepare patients data into array matrix
        while($buf=$patients_obj->FetchRow()){
            $patient[$buf['room_nr']][$buf['bed_nr']]=$buf;
        }
        $patients_ok=true;
        $occup='ja';
    }else{
        $patients_ok=false;
    }
    $ward_ok=true;

    $toggle=1;
    $room_info=array();
    # Set occupied bed counter
    $occ_beds=0;
    $cflag=$ward_info['room_nr_start'];
    $sListRows='';
    $strPatientRecord='';

    echo '{"occupancy":[';
    for ($i=$ward_info['room_nr_start'];$i<=$ward_info['room_nr_end'];$i++){
        if($room_ok){
            $room_info=$room_obj->FetchRow();
        }else{
            $room_info['nr_of_beds']=1;
            $edit=false;
        }

        for($j= $room_info['firstBedNo'];$j<=$room_info['lastBedNo'];$j++){
            if($patients_ok){

                if(isset($patient[$i][$j])){
                    $bed=$patient[$i][$j];
                    $is_patient=true;
                    # Increase occupied bed nr
                    $occ_beds++;
                }else{
                    $is_patient=false;
                    $bed=NULL;
                }
            }

            //echo "Bed parient is ".$bed['name_first']."<br>";

//        if($j== $room_info['firstBedNo']) {
//            echo '{"RoomNo":"Room ' . $i;
//        } else{
//            echo'{"RoomNo":"';
//        }
//1116012251     tsj221
//$smarty->assign('sBed',strtoupper(chr($j+96)));

            echo '{"RoomNo":"Room ' . $i;

            echo '","BedNo":"' . $j;
            //echo '","BedNo":"' . $j;
            echo '","Sex":"' . $bed['sex'];

            $names=$bed['name_first']." ".$bed['name_last']." ".$bed['name_2'];

            echo '","Names":"'.$names;
            echo '","BirthDate":"'.$bed['date_birth'];
            echo '","Pid":"'. $bed['pid'];
            echo '","AdmissionDate":"'. $bed['encounter_date'];

            if($bed['insurance']<>""){
                $payment=preg_replace('/[^a-zA-Z0-9_ -]/s', '', $bed['insurance']);
                // $paymentMode=$bed['insurance'];
            }elseif($bed['insurance']=="" && $bed['pid']<>""){
                $payment="CASH";
            }else{
                $payment="";
            }

            echo '","PaymentMode":"'.$payment;
            echo '","EncounterNo":"'.$bed['encounter_nr'];
            echo '","UrlAppend":"'.URL_REDIRECT_APPEND;
            echo '","BillNumber":"'.$bed['bill_number'];
            echo '","WardID":"'.$bed['wardID'].'"}';

            if($i<=$ward_info['room_nr_end']){
                echo ',';
            }

        }
        // echo  $strPatientRecord;
    }
    echo ']}';
}
function deleteReceiptItem($pid) {
    global $db;
    $debug = false;

    $ID = chop($_REQUEST['ID'], ',');

    $sql = "Delete from care_ke_receipts where sale_id in ($ID) and patient='$pid'";
    if ($debug)
        echo $sql;

    if ($db->Execute($sql)) {
        echo "{success:true,'Error':'Successfully Deleted Item'}";
    } else {
        echo "{failure:true,'Error':'Unable to Delete Item $sql'}";
    }
}

function deleteBillItem($pid) {
    global $db;
    $debug = false;

    $ID = chop($_REQUEST['ID'], ',');

    $sql = "Delete from care_ke_billing where ID in ($ID) and pid='$pid'";
    if ($debug)
        echo $sql;

    if ($db->Execute($sql)) {
        echo "{success:true,'Error':'Successfully Deleted Item'}";
    } else {
        echo "{failure:true,'Error':'Unable to Delete Item $sql'}";
    }
}

function combineBills($pid) {
    global $db;
    $debug = false;

    $bill1 = $_REQUEST['bill1'];
    $bill2 = $_REQUEST['bill2'];
    $enc1 = $_REQUEST['enc1'];
    $enc2 = $_REQUEST['enc2'];

    $sql = "update care_ke_billing set bill_number='$bill1',encounter_nr='$enc1' "
        . "where bill_number='$bill2' and pid='$pid'";
    if ($debug)
        echo $sql;

    if ($db->Execute($sql)) {
        echo "{success:true}";
    } else {
        echo "{failure:true}";
    }
}

function getWardsList() {
    global $db;
    $debug = false;

    $sql = "SELECT nr,ward_id, `name` FROM care_ward";
    $results = $db->Execute($sql);
    $total=$results->RecordCount();
    echo '[';
    $counter = 0;
    while ($row = $results->FetchRow()) {
        // $description=preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[name]);
        echo '{"Nr":"' . $row['nr'] . '","WardID":"' . $row['ward_id'] . '","Name":"' . $row['name']. '"}';
        $counter++;
        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']';
}


function getNhifCreditNo() {
    global $db;
    $debug = false;

    $sql = "select max(creditNo) as creditNo from care_ke_nhifcredits";
    $results = $db->Execute($sql);
    $recCount = $results->RecordCount();
    if ($recCount > 0) {
        $row = $results->FetchRow();
        $creditNo = $row[0] + 1;
    } else {
        $creditNo = '1000';
    }

    echo "{'credits':[{'creditNo':'$creditNo'}]}";
}

function getTotalBill($pid, $bill_number) {
    global $db;
    $debug = false;

    $sql = "SELECT sum(total) as total FROM care_ke_billing WHERE pid = '$pid' AND 
        service_type IN ('payment','payment adjustment','NHIF') and `ip-op`=1 and bill_number=$bill_number";
    if ($debug)
        echo $sql;

    $results = $db->Execute($sql);
    $row = $results->FetchRow();
    $totalPayment = $row[0];

    $sql2 = "SELECT sum(total) as total FROM care_ke_billing WHERE pid = '$pid' AND 
        service_type NOT IN ('payment','payment adjustment','NHIF') and `ip-op`=1 and bill_number=$bill_number";
    if ($debug)
        echo $sql2;

    $results2 = $db->Execute($sql2);
    $row2 = $results2->FetchRow();
    $totalBill = $row2[0];

    $billBalance = $totalBill - $totalPayment;

    echo "{'invoiceAmount':[{'amount':'$billBalance'}]}";
}

function geNextTransNo() {
    global $db;
    $debug = false;
    $transNo=$_REQUEST['transType'];
    $sql = "select transNo from care_ke_transactionnos where typeID=$transNo";
    $results = $db->Execute($sql);
    $recCount = $results->RecordCount();
    if ($recCount > 0) {
        $row = $results->FetchRow();
        $transNo = $row[0] + 1;
    } else {  
        $transNo = '1000';
    }

    echo "{'transNo':'$transNo'}";
}

function getEncounter($enc_obj, $pid) {
    $encounterJson = $enc_obj->getCurrentEncounter($pid);

    echo $encounterJson;
}
function getEncounterNumbers($pid) {
    global $db;

    $pid2 = ($pid <> "" ? $pid = $pid : $pid = '1000');

    $sql = "select DISTINCT encounter_nr from care_ke_billing where pid=$pid2
    and `IP-OP`=1 order by bill_date desc";

    $result = $db->Execute($sql);
    if (!$result) {
        echo 'Could not run query: ' . $sql;
        exit;
    }
    $total = $result->RecordCount();
    echo '{
    "total":"' . $total . '","encounters":[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        echo '{"EncounterNumbers":"' . $row['encounter_nr'] . '"}';

        $counter++;

        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']}';
}

function getBillNumbers($pid) {
    global $db;
    $debug = false;

    $pid2 = ($pid <> "" ? $pid = $pid : $pid = '1000');

    $sql = "SELECT bill_number,encounter_date FROM care_encounter WHERE pid=$pid and encounter_class_nr=1 
ORDER BY encounter_date DESC";

    if ($debug)
        echo $sql;

    $result = $db->Execute($sql);
    if (!$result) {
        echo 'Could not run query: ' . $sql;
        exit;
    }
    $total = $result->RecordCount();
    echo '{
    "total":"' . $total . '","billnumbers":[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        echo '{"BillNumbers":"' . $row['bill_number'] . '"}';

        $counter++;

        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']}';
}

function getNhifRates(){
    global $db;
    $debug = false;
    $rates = $_REQUEST['rate'];

    $sql = "SELECT ID,RateType,RateValue,rateCalc FROM care_ke_rates";

    if ($rates <> '') {
        $sql = $sql . " where ID=$rates";
    }
    if ($debug)
        echo $sql;

    $result = $db->Execute($sql);
    $total=$result->RecordCount();

    echo '{
    "total":"' . $total . '","nhifRates":[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        // $description=preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[name]);
        echo '{"ID":"' . $row['ID'] . '","RateType":"' . $row['RateType'] . '","RateValue":"' . $row['RateValue'] . '","rateCalc":"' . $row['rateCalc'] . '"}';

        $counter++;

        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']}';
}

function cancelOrder(){
    global $db;
    $debug=false;
    $reqno=$_REQUEST['reqno'];

    $sql="UPDATE care_ke_internalreq set `status`='Cancelled' where req_no='$reqno'";
    if($debug) echo $sql;
    if($db->Execute($sql)){
        echo "{success:true}";
    }else{
        echo "{failure:true}";
    }
}

function getValuationReport(){
    global $db;
    $debug=true;

    $accDB=$_SESSION['sess_accountingdb'];
    $pharmLoc=$_SESSION['sess_pharmloc'];

    $catID1 = $_REQUEST['catID1'];
    $catID2 = $_REQUEST['catID2'];
    $detsum = $_POST['detsum'];
    $storeid=$_REQUEST['storeid'];
    $sql1 = 'select catID,item_Cat from care_tz_itemscat where catID="' . $catID1 . '"';
    $result1 = $db->Execute($sql1);
    $catName = $result1->FetchRow();

    $sql = "SELECT b.PartCode, k.loccode,b.Item_Description,k.Quantity,k.reorderlevel,e.item_cat,s.`lastcost`,(s.`lastcost` * k.`quantity`) AS TotalCost
                FROM care_tz_drugsandservices b LEFT JOIN care_tz_itemscat e ON b.category=e.catid LEFT JOIN care_ke_locstock k ON k.stockid=b.item_number
                LEFT JOIN $accDB.`stockmaster` s ON k.`stockid`=s.`stockid` WHERE b.category <>'' AND K.`quantity`>0 and s.lastcost>0";

    if ($storeid <> '') {
        $sql.=" and k.loccode ='$storeid'";
    }
    if($debug) echo $sql;

    $result = $db->Execute($sql);
    $numRows=$result->RecordCount();
    echo '[';
    $counter=0;
    while ($row = $result->FetchRow()) {
        echo '{"PartCode":"'. $row['item_number'].'","Description":"'. $row['item_description']
            .'","Quantity":"'. $row['Quantity'].'","Category":"'. $row['item_cat'].'","LastCost":"'. $row['lastcost']
            .'","TotalCost":"'. $row['TotalCost'].'","Location":"'. $row['loccode'].'"}';
        if ($counter<>$numRows){
            echo ",";
        }
        $counter++;
    }
    echo ']';

}

function getStockMovements(){
    global $db;
    $debug=true;

}

function getStockAdjustments(){
    global $db;
    $debug=true;
    $ordLoc = $_REQUEST['ordLoc'];
    $orddt1 = $_REQUEST['startdate'];
    $orddt2 = $_REQUEST['enddate'];
    $itemID = $_REQUEST['partcode'];

    $sql = "SELECT a.ID, a.item_number, d.item_description,a.prev_qty, a.new_qty, 
           a.user, a.adjDate, a.adjTime, a.Reason,a.st_id
           FROM `care_ke_adjustments` a left join care_tz_drugsandservices d  on a.item_number=d.partcode
            where a.item_number<>''";

    if ($ordLoc) {
        $sql = $sql . ' and a.st_id="' . $ordLoc . '"';
    }

    if ($itemID) {
        $sql = $sql . ' and a.item_number="' . $itemID . '"';
    } else {
        $sql = $sql . '';
    }

    $dt1 = new DateTime($orddt1);
    $dto1 = $dt1->format('Y-m-d');
    $dt2 = new DateTime($orddt2);
    $dto2 = $dt2->format('Y-m-d');

    if ($orddt1 <> '' && $orddt2 == '') {
        $sql = $sql . ' and a.adjDate="' . $dto1 . '"';
    }
    if ($orddt1 == '' && $orddt2 <> '') {
        $sql = $sql . ' and a.adjDate="' . $dto2 . '"';
    }
    if ($orddt1 <> "" && $orddt2 <> "") {
        $sql = $sql . ' and a.adjDate between "' . $dto1 . '" and "' . $dto2 . '"';
    } else {
        $sql = $sql . '';
    }

    $result=$db->Execute($sql);

    $numRows=$result->RecordCount();
    echo '[';
    $counter=0;
    //ID, PartCode, Description,PrevQty, NewQty, InputUser, AdjDate, AdjTime, Reason,Store
    while ($row = $result->FetchRow()) {
        echo '{"ID":"'. $row['ID'].'","PartCode":"'. $row['item_number'].'","Description":"'. $row['item_description']
            .'","PrevQty":"'. $row['prev_qty'].'","NewQty":"'. $row['new_qty'].'","AdjDate":"'. $row['adjDate']
            .'","AdjTime":"'. $row['adjTime'].'","Reason":"'. $row['Reason'].'","Store":"'. $row['st_id']
            .'","InputUser":"'. $row['user'].'"}';
        if ($counter<>$numRows){
            echo ",";
        }
        $counter++;
    }
    echo ']';
}

function getPendingPrescriptions(){
    global $db;
    $debug = false;
    $prescParams=$_REQUEST['prescParams'];
    $sql="SELECT DISTINCT n.`pid`,CONCAT(n.`name_first`,' ',n.name_last,' ',name_2) AS pnames,e.`encounter_nr`,p.`prescribe_date`,
                p.status,p.nr,encounter_class_nr,p.`drug_class`,p.nr FROM care_encounter_prescription p
        INNER JOIN care_encounter e ON p.`encounter_nr`=e.`encounter_nr`
        INNER JOIN care_person n ON e.`pid`=n.`pid`
        WHERE p.status='pending' AND p.`drug_class` IN ('drug_list','MEDICAL-SUPPLIES')";

    $encClass=$_REQUEST['encounterClass'];
    if($encClass){
        $sql.=" and e.encounter_class_nr='$encClass' ";
    }

    if($prescParams<>''){
        $sql.=" and n.pid='$prescParams' OR n.name_first like '%$prescParams%' 
        OR n.name_last like '%$prescParams%' OR n.name_2 like '%$prescParams%' ";
    }

     $sql.=" group by p.encounter_nr ORDER BY e.encounter_date DESC";

    if ($debug) echo $sql;

    $result=$db->Execute($sql);

    $numRows=$result->RecordCount();
    echo '[';
    $counter=0;
    while ($row = $result->FetchRow()) {
         $pnames=preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row['pnames']);
        echo '{"Pid":"'. $row['pid'].'","EncounterNo":"'. $row['encounter_nr'].'","Names":"'. $pnames
            .'","PrescribeDate":"'. $row['prescribe_date'].'","Nr":"'. $row['nr'].'","EncounterClassNr":"'. $row['nr'].'"}';
        if ($counter<>$numRows){
            echo ",";
        }
        $counter++;
    }
    echo ']';

}

function getPatientPresc($encNr,$pid){
    global $db;
    $debug=false;
    $nr=$_REQUEST['nr'];
    $sql="SELECT p.pid,CONCAT(p.name_first,' ',p.name_2,' ',p.name_last) AS patientNames,MAX(b.encounter_nr) AS encNr,
            b.is_discharged ,MAX(a.prescriber) AS prescriber,p.date_birth,b.bill_number,p.`insurance_ID`,d.`name` as Payment,
            b.encounter_date
                FROM care_person p 
                INNER JOIN  care_encounter b ON p.pid=b.pid
                LEFT JOIN care_encounter_prescription a ON b.encounter_nr=a.encounter_nr
                LEFT JOIN `care_tz_company` d ON p.`insurance_ID`=d.`ID`
                WHERE b.pid='$pid' and a.nr='$nr' AND a.drug_class IN('drug_list','Medical-Supplies','THEATRE')
				group by b.encounter_nr,a.prescriber";
    if($debug) echo $sql;

    $result=$db->Execute($sql);

    $numRows = $result->RecordCount();
    echo '[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        $receiptDetails=getReceiptNo($pid,$encNr);
        $receiptNo=$receiptDetails['batch_no'];
        $receiptAmount=$receiptDetails['amount'];
        $age=exactAge($row['date_birth']);
        if(!$row['Payment']){
            $payment='CASH PAYMENT';
        }else{
            $payment=$row['Payment'];
        }
        echo '{"Pid":"' . $row['pid'] .'","Names":"' . $row['patientNames'] .'","EncounterNo":"' . $row['encNr']
            .'","Age":"' . $age .'","Prescriber":"' . $row['prescriber'] .'","Billnumber":"' . $row['bill_number']
            .'","Payment":"' . $payment.'","PrescribeDate":"' .$row['encounter_date'] 
            .'","ReceiptNo":"' .$receiptNo .'","ReceiptAmount":"' .$receiptAmount .'"}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }
    }
    echo ']';

}

function adjustStock($input_user,$items_obj) {
    global $db;
    $debug=true;
    $partcode = $_REQUEST['partcode'];
    $item_description = $_REQUEST['item_Description'];
    $qty = $_REQUEST['Quantity'];
    $roorder = $_REQUEST['ReorderLevel'];
    $loccode = $_REQUEST['Store'];
    $comment = $_REQUEST['Comment'];


    $sql = "select quantity from care_ke_locstock where stockid='$partcode'and loccode='$loccode'";
    if($debug) echo $sql;
    $result = $db->Execute($sql);
    $row = $result->FetchRow();
    $currQty=$row[0];

    $csql = "update care_ke_locstock set
				quantity='$qty',
				reorderlevel='$roorder',
				comment='$comment'
				where stockid='$partcode' and loccode='$loccode'";
    if($debug) echo $csql;

    $ksql = "insert into care_ke_adjustments
        (item_number, prev_qty, new_qty, user, adjDate, adjTime, Reason,st_id)
        values( '" . $partcode . "', '" . $row[0] . "', '" . $qty . "', '$input_user',
            '" . date('Y-m-d') . "', '" . date('H:i:s') . "', '" . $comment . "','$loccode')";
    if($debug) echo $ksql;
    if ($db->Execute($ksql)) {
        $db->Execute($csql);

        $sql="Update care_tz_drugsandservices set item_status=1 where partcode='$partcode'";
        if($debug) echo $sql;
        $db->Execute($sql);

        updateStockMovement($partcode,$loccode,$qty,$currQty,$input_user,$items_obj);

        $sql="update care_ke_transactionnos set transNo=transNo+1 where typeID='5'";
        if($debug) echo $sql;
        $db->Execute($sql);

        $results = '{success: true }';
    } else {
        // Errors. Set the json to return the fieldnames and the associated error messages
        $results = '{success: false, sql:' . $ksql . '}'; // Return the error message(s)
//        echo $sql;
    }

    echo $results;
}

function serviceOrders($items_obj){
    global $db;
    $debug = false;

    $date1 = new DateTime($_POST['orderDate']);
    $issue_date = $date1->format("Y-m-d");
    $issue_time=date('H:i:s');
    $issueNo=$_POST['issuenumber'];

    $input_user= $_SESSION['sess_login_username'];
    $req_no=$_POST['req_no'];
    $orderData = $_REQUEST['gridData'];
    $data = json_decode($orderData, true);
    $store_loc = $_POST['store'];
    $sup_storeId = $_POST['SelectSupplyingStore'];
    $supStoreDesc=$_REQUEST['sup_storedesc'];
    $store_desc=$_REQUEST['store_desc'];
    $period=date('Y');
    $orderNo=$_POST['orderNo'];
    $error=0;
    foreach ($data as $row) {
        $partcode = $row['PartCode'];
        $description = $row['Description'];
        $qty = $row['Qty'];
//        $price = $row['unit_price'];
        if($row['Qty_Issued']<>''){
            $qty_issued = $row['Qty_Issued'];
        }else{
            $qty_issued = $row['Qty'];
        }
        $bal=($qty_issued-$row['Qty']);

        $sql = "INSERT INTO care_ke_internalserv
              ( req_no,STATUS,req_date,req_time,store_loc,Store_desc,sup_storeId,sup_storeDesc,
                item_id,Item_desc,qty,qty_issued,period,input_user,balance,issue_date,issue_time)
	        VALUES('$req_no','serviced', '$issue_date','$issue_time', '$store_loc',
                '$store_desc','$sup_storeId','$supStoreDesc','$partcode','$description','$qty',
                '$qty_issued','$period','$input_user','$bal','$issue_date','$issue_time')";
        if ($debug) echo $sql;

        if ($db->Execute($sql)) {
            //if (checkStoreType($store_loc) == 1) {
            $sql2 = "Update care_ke_locstock set quantity=quantity+$qty_issued where stockid='$partcode' and loccode='$store_loc'";
            $db->Execute($sql2);
            if ($debug)
                echo $sql2;
            //}

            if ($sup_storeId == 'MAIN' || $sup_storeId == 'GEN') {
                // $weberp_obj->stock_adjustment_in_webERP($itemId, $sup_storeId, $store_loc, $qty_issued, date('Y-m-d'));
                StockAdjustment($partcode,$sup_storeId,$store_loc,$qty_issued, date('Y-m-d'));
            } else {
                reduceStock($db, $partcode, $sup_storeId, $qty_issued, $req_no,"");
            }

            $sql3 = "update care_ke_internalreq set status='Serviced',qty_issued='$qty_issued',balance='$bal',
                            issue_date='$issue_date',issue_time='$issue_time' where req_no='$req_no' and item_id='$partcode'";
            $db->Execute($sql3);
            if ($debug)
                echo $sql3;

            updateStockMovement($partcode, $sup_storeId, $store_loc, $qty, $input_user, $items_obj);
            $error=0;
        } else {
            $error=1;
        }
    }

    if ($error==0) {
        echo "{success:true}";
    } else {
        echo "{failure:true}";
    }
}

function getCurrentQty($partcode, $supStore) {
    global $db;
    $debug = false;
    $accDB = $_SESSION['sess_accountingdb'];
    $sql1 = "select quantity from care_ke_locstock where stockid='$partcode' and loccode='$supStore'";
    $result2 = $db->Execute($sql1);
    if ($debug)
        echo $sql1 . '<br>';

    $row = $result2->FetchRow();

    return $row;
}

function updateStockMovement($stockid, $sup_storeId, $store_loc, $qty, $input_user, $items_obj) {
    getCurrentQty($stockid, $store_loc);
    $currQty = getCurrentQty($stockid, $store_loc);
    $newqoh = $currQty['quantity'];

    $moveDetails['stockid'] = $stockid;
    $moveDetails['type'] = 4;
    $moveDetails['loccode'] = $store_loc;
    $moveDetails['pid'] = '';
    $moveDetails['price'] = 0;
    $moveDetails['reference'] = "Service orders from $sup_storeId to $store_loc by " . $input_user;
    $moveDetails['qty'] = $qty;
    $moveDetails['standardcost'] = 0;
    $moveDetails['newqoh'] = $newqoh;
    $moveDetails['narrative'] = "Service orders from $sup_storeId to $store_loc by " . $input_user;
    $moveDetails['sup_storeId'] = $sup_storeId;

    $items_obj->updateStockMovement($moveDetails);
}

function checkStoreType($loccode) {
    global $db;
    $debug = false;
    $sql = "select mainstore from care_ke_stlocation where st_id='$loccode'";
    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);
    $row = $request->FetchRow();
    return $row[0];
}

function detailedOrderItems(){
    global $db;
    $debug=false;

    $storeLoc=$_REQUEST['store'];
    $req_no=$_REQUEST['req_no'];
    $accDB=$_SESSION['sess_accountingdb'];

    $sql = "select r.item_id,item_desc,d.purchasing_unit,qty,qty_issued,balance,s.quantity AS Qty_In_Store,unit_qty as TotalUnits,Unit_qty
     from care_ke_internalreq r";

    if($storeLoc=='MAIN' || $storeLoc=='GEN') {
        $sql .= " INNER JOIN $accDB.`locstock` s ON r.`item_id`=s.`stockid`
                 INNER JOIN care_tz_drugsandservices d ON r.item_id=d.partcode";
    }else{
        $sql.=" INNER JOIN `care_ke_locstock` s ON r.`item_id`=s.`stockid` 
                 INNER JOIN care_tz_drugsandservices d ON r.item_id=d.partcode";
    }

    $sql.=" AND req_no='$req_no' and status='pending' and s.loccode='$storeLoc'";

    if ($debug) echo $sql;

    $result = $db->Execute($sql);

    $numRows = $result->RecordCount();
    echo '[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        echo '{"PartCode":"' . $row['item_id'] .'","Description":"' . $row['item_desc'] .'","Unit_Qty":"' . $row['unit_qty']
            .'","Purchasing_Unit":"' . $row['purchasing_unit'] .'","Qty":"' . $row['qty'] .'","Qty_Issued":"' . $row['qty_issued']
            .'","Balance":"' . $row['balance'].'","Qty_In_Store":"' . $row['Qty_In_Store'].'"}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }
    }
    echo ']';
}

function getInternalOrders(){
    global $db;
    $debug=false;

    $sql="SELECT req_no,`status`,req_date,req_time,item_id,item_desc,qty,price,qty_issued,balance,store_loc
            ,store_desc,sup_storeid,issue_date,issue_date,issue_time,sup_storeDesc,input_user
         FROM care_ke_internalreq";

    if ($debug) echo $sql;
    $result = $db->Execute($sql);
    $numRows = $result->RecordCount();
    echo '[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        echo '{"ReqNo":"' . $row['req_no'] .'","Status":"' . $row['status'] .'","ReqDate":"' . $row['req_date'].' '.$row['req_time']
             .'","PartCode":"' . $row['item_id'] .'","Description":"' . $row['item_desc'] .'","Qty":"' . $row['qty']
            .'","Price":"' . $row['price'].'","QtyIssued":"' . $row['qty_issued'].'","Balance":"' . $row['balance']
            .'","OrderingDept":"' . $row['store_desc'].'","SupplyingStore":"' . $row['sup_storeDesc']
            .'","IssueDate":"' . $row['issue_date'].' '.$row['issue_time'] .'","InputUser":"' . $row['input_user'] .'"}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }
    }
    echo ']';
}

function selectPendingOrders(){
    global $db;
    $debug=false;
    $store=$_REQUEST['store'];

    $sql="SELECT req_date,req_time,req_no,store_loc,store_desc,sup_storeid,sup_storeDesc,`status`,input_user
         FROM care_ke_internalreq WHERE`status`='pending' AND sup_storeid LIKE '$store'
         GROUP BY req_no";

    if ($debug) echo $sql;
    $result = $db->Execute($sql);
    $numRows = $result->RecordCount();
    echo '[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        echo '{"date":"' . $row['req_date'] .'","time":"' . $row['req_time'] .'","req_no":"' . $row['req_no']
            .'","store":"' . $row['store_loc'] .'","store_desc":"' . $row['store_desc'] .'","sup_storeid":"' . $row['sup_storeid']
            .'","sup_storedesc":"' . $row['sup_storeDesc'].'","status":"' . $row['status'].'","input_user":"' . $row['input_user'].'"}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }
    }
    echo ']';
}


function issueDrugs($items_obj,$bill_obj){
    global $db;
    $debug = false;

    $date1 = new DateTime($_POST['issueDate']);
    $issueDate = $date1->format("Y-m-d");
    $issueNumber=$_POST['IssueNumber'];
    $orderType=$_POST['orderType'];
    $inputUser = $_SESSION['sess_login_username'];
    $orderData = $_REQUEST['gridData'];
    $data = json_decode($orderData, true);
    $pid = $_POST['Pid'];
    $patientName = $_POST['patientName'];
    $doctor = $_POST['Doctor'];
    $encounterNr = $_POST['encounterNr'];
    $issueingStore = $_POST['Store'];
    $period=date('Y');
    $encClass=$_POST['orderType'];
    $insuranceid=$_POST['PaymentMethod'];
    $error=0;
    foreach ($data as $row) {
        $nr = $row['Nr'];
        $partcode = $row['PartCode'];
        $description = $row['Description'];
        $totalQty = $row['TotalQty'];
        $issuedQty = $row['Issued'];
        $price = $row['Price'];

        if($issuedQty<>""){
            $qtyIssued=$issuedQty;
        }else{
            $qtyIssued=$totalQty;
        }
        $total = $row['Price']*$qtyIssued;
        $bal=$totalQty-$qtyIssued;

        $csql = "INSERT INTO care_ke_internal_orders
                (order_no,STATUS,order_date,order_time,order_type,store_loc,store_desc,adm_no,
                OP_no,patient_name,item_id,Item_desc,qty,price,unit_msr,unit_cost,issued,orign_qty,
                balance,period,input_user,total,presc_nr,weberpsync)
                VALUES
                ('$issueNumber', 'issued','" . $issueDate. "','" . date("H:i:s") . "','$orderType','$issueingStore','$issueingStore',
                '$encounterNr','$pid','$patientName','$partcode', '$description','$qtyIssued','$price','each','$price','$qtyIssued','$totalQty',
                '$bal','$period','$inputUser','$total','$nr','0')";

        if ($debug)
            echo $csql . '<br>';
        if($db->Execute($csql)){
            if ($encClass == 1 || $insuranceid<>"CASH PAYMENT") {
                //        createPhrmQuote($enc_no);
                $bill_obj->updateFinalBill($encounterNr,$nr,1);
            }
            reduceStock($db, $partcode, $issueingStore, $qtyIssued,$bal, $nr, $issueNumber);
            updateStockMovement($partcode, $issueingStore, $issueingStore, $qtyIssued, $inputUser, $items_obj);

            $error=0;
        } else {
            $error=1;
        }
    }

    if ($error==0) {
        $sqls="UPDATE care_ke_transactionnos set transNo=transNo+1 where typeID='3'";
        $db->Execute($sqls);
        echo "{success:true}";
    } else {
        echo "{failure:true}";
    }
}


function reduceStock($db, $stockid, $store, $qtyIssued,$bal, $presc_nr){
    $debug =false;
    $inputUser = $_SESSION['sess_login_username'];

    $sql1 = "select quantity from care_ke_locstock where stockid='$stockid' and loccode='$store'";
    $result2 = $db->Execute($sql1);
    if ($debug) echo $sql1 . '<br>';

    $row = $result2->FetchRow();
    $newQty = intval($row[0]) - intval($qtyIssued);


    $sql = "update care_ke_locstock set quantity='$newQty' where stockid='$stockid' and loccode='$store'";
    $db->Execute($sql);
    if ($debug) echo $sql . '<br>';

    if ($bal > 0) {
        $stat = "pending";
    } else {
        $stat = "serviced";
    }

        $ksql = "insert into care_ke_adjustments
        (item_number, prev_qty, new_qty, user, adjDate, adjTime, Reason)
        values( '" . $stockid . "', '" . $row[0] . "', '" . $qtyIssued . "', '$inputUser',
        '" . date('Y-m-d') . "', '" . date('H:i:s') . "', 'Drugs Issued to patient')";

        $db->Execute($ksql);

    $sqlp = "update care_encounter_prescription set  status='$stat',qty_balance='$bal',qtyIssued='$qtyIssued',bill_status='billed',posted='1'  where
        partcode='$stockid' AND `status` = 'pending' and nr='$presc_nr'";
    $db->Execute($sqlp);
    if ($debug)
        echo $sqlp . '<br>';

}

function returnOrderedDrugs(){
    global $db;
    $debug = false;
//ID PrescNo STATUS OrderDate OrderTime Store EncounterNr PID PatientName PartCode Description Qty Price Issued Balance QtyReturn
    $returnDate =date("Y-m-d");
    $returnTime=date('H:i:s');
    $inputUser = $_SESSION['sess_login_username'];
    $transNo=getTransNos(6);
    $returnData = $_REQUEST['gridData'];
    $data = json_decode($returnData, true);
    $supStore = $_POST['Store'];
    $period=date('Y');

    $error=0;
    foreach ($data as $row) {
        $pid=$row['PID'];
        $enc_nr=$row['EncounterNr'];
        $pnames=$row['PatientName'];
        $prescNo=$row['PrescNo'];
        $partcode = $row['partcode'];
        $description = $row['item_description'];
        $qty = $row['qty'];
        $price = $row['Price'];
        $qty = $row['qty'];
        $qtyReturn=$row['QtyReturn'];
        $total=$row['Price']*$qtyReturn;

        $sql = "INSERT INTO `care_ke_internal_returns` (
                  `return_no`,`presc_nr`,`status`,`return_date`,`return_time`,`return_type`,`store_loc`,`store_desc`,
                  `OP_no`,`patient_name`,`item_id`,`Item_desc`,`qty`,`price`,`return_qty`,`total`,`period`,`input_user`, ) 
	            VALUES('$transNo','$prescNo','Returned','$returnDate','$returnTime','Patient Return','$supStore','$supStore'
	            ,'$pid','$pnames','$partcode','$description','$qty','$price','$qtyReturn','$total','$period','$inputUser')";
        if ($debug) echo $sql;

        if ($db->Execute($sql)) {
             adjustStock($db,$pid,$partcode,$description,$supStore,$qty,$prescNo,$transNo,$enc_nr,$enc_nr,$price,$inputUser,$prescNo) ;
             updateStockMovement($partcode,$supStore,$pid,$price,$qtyReturn,$price);
            $error=0;
        } else {
            $error=1;
        }
    }



    if ($error==0) {
        $sqls="UPDATE care_ke_transactionnos set transNo=transNo+1 where typeID='4'";
        $db->Execute($sqls);
        echo "{success:true}";
    } else {
        echo "{failure:true}";
    }

}

//function returnOrderedDrugs2(){
//    global $db;
//    $debug = false;
//
//    $date1 = new DateTime($_POST['orderDate']);
//    $orderDate = $date1->format("Y-m-d");
//    $orderTime=date('H:i:s');
//
//    $inputUser = $_SESSION['sess_login_username'];
////    $orderNo=$_POST['orderNo'];
//    $orderData = $_REQUEST[gridData];
//    $data = json_decode($orderData, true);
//    $department = $_POST['department'];
//    $supStore = $_POST['suppStore'];
//    $supName=$_REQUEST['suppName'];
//    $deptName=$_REQUEST['deptName'];
//    $period=date('Y');
////    $transNo=getTransNos(4);
////    $obj = json_decode($transNo);
////    $orderNo=$obj->transNo;
//    $orderNo=$_POST['orderNo'];
//    $error=0;
//    foreach ($data as $row) {
//        $partcode = $row['partcode'];
//        $description = $row['item_description'];
////        $qty = $row['qty'];
////        $price = $row['unit_price'];
//        $qty_to_order = $row['Qty_to_Order'];
//
//        $sql = "INSERT INTO care_ke_internalreq
//              ( req_no,STATUS,req_date,req_time,store_loc,Store_desc,sup_storeId,sup_storeDesc,
//                item_id,Item_desc,qty,period,input_user)
//	            VALUES('$orderNo','pending','$orderDate','$orderTime','$department','$deptName',
//                '$supStore','$supName','$partcode','$description',
//                '$qty_to_order','$period','$inputUser'
//                );";
//        if ($debug) echo $sql;
//
//        if ($db->Execute($sql)) {
//            $error=0;
//        } else {
//            $error=1;
//        }
//    }
//
//    if ($error==0) {
//        $sqls="UPDATE care_ke_transactionnos set transNo=transNo+1 where typeID='4'";
//        $db->Execute($sqls);
//        echo "{success:true}";
//    } else {
//        echo "{failure:true}";
//    }
//
////            adjustStock($db,$pid,$itemId,$item_Desc,$storeID,$qty,$prescNO,$issueNo,$enc_nr,$enc,$price,$input_user,$prescNO) ;
////            updateStockMovement($itemId,$storeID,$pid,$price,$qty,$price);
//
//    }

//    if ($error==0) {
//        $sqls="UPDATE care_ke_transactionnos set transNo=transNo+1 where typeID='4'";
//        $db->Execute($sqls);
//        echo "{success:true}";
//    } else {
//        echo "{failure:true}";
//    }
//}


function saveInternalOrder(){
    global $db;
    $debug = false;

    $date1 = new DateTime($_POST['orderDate']);
    $orderDate = $date1->format("Y-m-d");
    $orderTime=date('H:i:s');

    $inputUser = $_SESSION['sess_login_username'];
//    $orderNo=$_POST['orderNo'];
    $orderData = $_REQUEST['gridData'];
    $data = json_decode($orderData, true);
    $department = $_POST['department'];
    $supStore = $_POST['suppStore'];
    $supName=$_REQUEST['suppName'];
    $deptName=$_REQUEST['deptName'];
    $period=date('Y');
  // $transNo=getTransNos(4);
//    $obj = json_decode($transNo);
//    $orderNo=$obj->transNo;
    $orderNo=$_POST['orderNo'];
    $error=0;
    foreach ($data as $row) {
        $partcode = $row['partcode'];
        $description = $row['item_description'];
//        $qty = $row['qty'];
//        $price = $row['unit_price'];
        $qty_to_order = $row['Qty_to_Order'];

        $sql = "INSERT INTO care_ke_internalreq
              ( req_no,STATUS,req_date,req_time,store_loc,Store_desc,sup_storeId,sup_storeDesc,
                item_id,Item_desc,qty,period,input_user)
	            VALUES('$orderNo','pending','$orderDate','$orderTime','$department','$deptName',
                '$supStore','$supName','$partcode','$description',
                '$qty_to_order','$period','$inputUser'
                );";
        if ($debug) echo $sql;

        if ($db->Execute($sql)) {
            $error=0;
        } else {
            $error=1;
        }
    }

    if ($error==0) {
        $sqls="UPDATE care_ke_transactionnos set transNo=transNo+1 where typeID='4'";
        $db->Execute($sqls);
        echo "{success:true}";
    } else {
        echo "{failure:true}";
    }
}

function getTransNos($typeID){
    global $db;
    $debug=false;

    $sql="SELECT typeID,typeName,transNo FROM `care_ke_transactionnos` where typeID='$typeID'";

    if ($debug) echo $sql;

    $result = $db->Execute($sql);

    $numRows = $result->RecordCount();
    echo '[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        echo '{"ID":"' . $row['typeID'] .'","typeName":"' . $row['typeName'] .'","transNo":' . $row['transNo'].'}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }
    }
    echo ']';
}

function getAlllabTests(){
    global $db;
    $debug=false;

    $sql="SELECT p.`pid`,e.`encounter_nr`,e.`encounter_date`,CONCAT(name_first,' ',name_2,' ',name_last) AS pnames,
        l1.`batch_nr`,l1.`paramater_name`,l1.`item_id`,lp.`name`,l1.TestStatus AS TestName FROM care_person p
        LEFT JOIN care_encounter e ON p.`pid`=e.`pid`
        INNER JOIN `care_test_request_chemlabor_sub` l1 ON e.`encounter_nr`=l1.`encounter_nr`
        INNER JOIN `care_tz_laboratory_param` lp ON l1.`paramater_name`=lp.`id`";

    if ($debug) echo $sql;

    $result = $db->Execute($sql);

    $numRows = $result->RecordCount();
    echo '[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        echo '{"Pid":"' . $row['pid'] .'","EncounterNo":"' . $row['encounter_nr'] .'","Names":"' . $row['pnames']
            .'","EncounterDate":"' . $row['encounter_date'] .'","BatchNo":"' . $row['batch_nr'] .'","ParameterName":"' . $row['paramater_name']
            .'","TestName":"' . $row['name'].'","TestStatus":"' . $row['TestStatus'].'"}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }
    }
    echo ']';
}

function updateUserRights(){
    global $db;
    $debug=false;

    $sql="UPDATE `care_ke_staff` 
        SET `EnterRequest` = '$_POST[EnterRequest]',`EnterResults` = '$_POST[EnterResults]',
          `ViewResults` = '$_POST[ViewResults]', `TakeSamples` = '$_POST[TakeSamples]'
          , `ReceiveSamples` = '$_POST[ReceiveSamples]',`VerifyResults` = '$_POST[VerifyResults]'
          , `ApproveResults` = '$_POST[ApproveResults]' WHERE `ID` = '$_POST[ID]'";

    if($debug) echo $sql;
    if($db->Execute($sql)){
        echo "{success:true}";
    }else{
        echo "{failure:true}";
    }
}

function getHaemoParams(){
    global $db;

    $sql="SELECT results,normal,ranges FROM care_tz_laboratory_resultstypes WHERE item_id='LTEST70'";
    $result = $db->Execute($sql);

    $numRows = $result->RecordCount();
    echo '[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        echo '{"results":"' . $row['results'] .'","normal":"' . $row['normal'] .'","ranges":"' . $row['ranges'].'"}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }
    }
    echo ']';

}

function  checkUserAccess($currUser,$activity){
    global $db;
    $debug=false;

    $sql="SELECT staff_name,$activity from care_ke_staff where staff_name='$currUser'";
    if($debug) echo $sql;
    if($result=$db->Execute($sql)){
        $row=$result->FetchRow();
        if($row[1]=='Yes'){
            echo "{success:true,'Resp':'Access'}";
        }else{
            echo "{failure:true,'Resp':'Denied'}";
        }
    }else{
        echo "{failure:true}";
    }

}

function createStaff(){
    global $db;
    $debug=false;

    $sql="INSERT INTO `care_ke_staff` (
      `staff_id`,`staff_name`,`JobTitle`,`Department`,`EnterRequest`,`EnterResults`,`ViewResults`,
      `TakeSamples`,`ReceiveSamples`,`VerifyResults`,`ApproveResults`) 
    VALUES('".$_POST['staff_id']."','".$_POST['staff_name']."','".$_POST['jobTitle']."','".$_POST['department']."','".$_POST['EnterRequest']."','".$_POST['EnterResults']."',
        '".$_POST['ViewResults']."','".$_POST['TakeSamples']."','".$_POST['ReceiveSamples']."','".$_POST['VerifyResults']."','".$_POST['ApproveResults']."')";

    if($debug) echo $sql;

    if($db->Execute($sql)){
        echo "{success:true}";
    }else{
        echo "{failure:true}";
    }
}

function approveResults($labNo,$encounter_nr){
    global $db,$currUser;

    $collDate=date('Y-m-d H:i:s');
    $approvedBy=($_POST['approvedBy'])?($_POST['approvedBy']):$currUser;

    $sql="Update care_test_request_chemlabor_sub set ApprovedTime='$collDate',ApprovedBy='$approvedBy'
          where batch_nr='$labNo'";

    if($db->Execute($sql)){
        if(updatePatientStatus($encounter_nr,$labNo,"Laboratory","Results Approved","Results Approved",$approvedBy)){
            echo "{success:true}";
        }else{
            echo "{failure:true}";
        }
    }
}

function verifyResults($labNo,$encounter_nr){
    global $db,$currUser;

    $collDate=date('Y-m-d H:i:s');

    $sql="Update care_test_request_chemlabor_sub set resultsVerifiedTime='$collDate',verifiedby='$currUser'
          where batch_nr='$labNo'";

    if($db->Execute($sql)){
        if(updatePatientStatus($encounter_nr,$labNo,"Laboratory","Results Verified","Results Verified",$currUser)){
            echo "{success:true}";
        }else{
            echo "{failure:true}";
        }
    }
}

function getTestName($partcode){
    global $db;
    $debug=false;

    $sql="SELECT `name` from care_tz_laboratory_param where item_id='$partcode'";
    if($debug) echo $sql;
    $result=$db->Execute($sql);
    $row=$result->FetchRow();

    return $row['name'];
}

function getLabResults($labNo,$item_id){
    global $db;
    $debug=false;

    $sql="SELECT DISTINCT p.paramater_name,r.`results`,p.parameter_value,r.`normal`,r.`ranges`,p.paramater_name FROM care_test_findings_chemlabor_sub p
                LEFT JOIN care_test_findings_chemlab k ON p.job_id=k.job_id
                 LEFT JOIN care_test_request_chemlabor_sub t ON k.job_id=t.batch_nr
                 LEFT JOIN `care_tz_laboratory_resultstypes` r ON r.`resultID`= SUBSTRING_INDEX(p.paramater_name,'-',-1)
                WHERE p.job_id='$labNo' ORDER BY p.job_id ASC";

    if ($debug) {  echo $sql; }
    $result = $db->Execute($sql);

    $numRows = $result->RecordCount();
    echo '[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        $strItem=explode('-',$row['paramater_name']);
        //echo $strItem[0];
        if($strItem[0]=='group'){
          //  echo "Partcode is ". $strItem[1];
            $partcode=$strItem[1];
            $testName=getTestName($partcode);
            $resultName=getResultName($testName);
           // $resultName=$strItem[2];
            $inputType='group';
        }else{
            $partcode=$row['paramater_name'];
            $testName=getTestName($partcode);
            $resultName= getResultName($testName);
            //$resultName=$testName;
            $inputType='others';
        }

                echo '{"PartCode":"' . $partcode .'","TestName":"' .$testName .'","ResultName":"' . $row['results']
            .'","ResultsValue":"' . $row['parameter_value'] .'","LowerRange":"' . $row['normal']
            .'","UpperRange":"' . $row['ranges'].'"}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }
    }
    echo ']';

}

function getResultName($id){
    global $db;
    $debug=false;

    $sql="select results from care_tz_laboratory_resultstypes where resultID='$id'";
    if($debug) echo $sql;
    $result=$db->Execute($sql);
    $row=$result->FetchRow();

    echo $row[0];
}

function saveLabRequest($bill_obj,$encounter_nr,$currUser){
    global $db;
    $debug=false;
    $error=0;
    for($i=0;$i<$_POST['counter'];$i++) {

            $dosage =$_POST['dose'.$i];
           // $notes = $_POST['comment'.$i];
            $partCode = $_POST['partCode'.$i];
            $article = $_POST['description'.$i];
            // $timesperday = $_POST['timesperday'.$i];
            // $days = $_POST['days'.$i];

            $searchsql = "SELECT item_id, item_description,unit_price,partcode,purchasing_class FROM care_tz_drugsandservices WHERE partcode='$partCode'";
            if($debug) echo $searchsql;
            $searchresult = $db->Execute($searchsql);
            $row = $searchresult->FetchRow();

            $financialClass=$bill_obj->getFinancialClass($encounter_nr);
            $price=$bill_obj->getItemPrice($partCode, $financialClass);

            $sql = "INSERT INTO care_encounter_prescription (`encounter_nr`,`prescription_type_nr`,
                        `article`,`article_item_number`,`partcode`,`price`,`drug_class`,`dosage`,`application_type_nr`,
                        `notes`,`times_per_day`,`days`,`prescribe_date`,`prescriber`,`is_outpatient_prescription`,
                        `history`,`create_time`,`modify_id`, status,weberpSync,bill_status,store,posted)
                    VALUES ('" . $encounter_nr . "',0,'" .$article . "','" . $row['item_id'] . "','" . $partCode . "',
                          '" . $price . "','" . $row['purchasing_class'] . "','" . $dosage . "',0,'" . $notes . "',
                          '" . $timesperday . "','" . $days . "','" . date('Y-m-d H:i:s') . "','" . $currUser . "',
                          '1','','" . date('Y-m-d H:i:s') . "','" . $_SESSION['create_id'] . "','pending',0,
                          'pending','Dispens',0)";

            if($debug) echo $sql;
           if( $db->Execute($sql)){
               $error==0;
           }else{
               $error==1;
           }
     }

    if($error==0){
        echo "{success:true}";
    }else{
        echo "{failure:true}";
    }

}

function savePrescription($bill_obj,$encounter_nr,$currUser){
    global $db;
    $debug=false;
    $error=0;
    for($i=0;$i<$_POST['counter'];$i++) {

            $dosage =$_POST['dose'.$i];
            $notes = $_POST['comment'.$i];
            $partCode = $_POST['partCode'.$i];
            $article = $_POST['description'.$i];
            $timesperday = $_POST['timesperday'.$i];
            $days = $_POST['days'.$i];

            $searchsql = "SELECT item_id, item_description,unit_price,partcode,purchasing_class FROM care_tz_drugsandservices WHERE partcode='$partCode'";
            if($debug) echo $searchsql;
            $searchresult = $db->Execute($searchsql);
            $row = $searchresult->FetchRow();

            $financialClass=$bill_obj->getFinancialClass($encounter_nr);
            $price=$bill_obj->getItemPrice($partCode, $financialClass);

            $sql = "INSERT INTO care_encounter_prescription (`encounter_nr`,`prescription_type_nr`,
                        `article`,`article_item_number`,`partcode`,`price`,`drug_class`,`dosage`,`application_type_nr`,
                        `notes`,`times_per_day`,`days`,`prescribe_date`,`prescriber`,`is_outpatient_prescription`,
                        `history`,`create_time`,`modify_id`, status,weberpSync,bill_status,store,posted)
                    VALUES ('" . $encounter_nr . "',0,'" .$article . "','" . $row['item_id'] . "','" . $partCode . "',
                          '" . $price . "','" . $row['purchasing_class'] . "','" . $dosage . "',0,'" . $notes . "',
                          '" . $timesperday . "','" . $days . "','" . date('Y-m-d H:i:s') . "','" . $currUser . "',
                          '1','','" . date('Y-m-d H:i:s') . "','" . $_SESSION['create_id'] . "','pending',0,
                          'pending','Dispens',0)";

            if($debug) echo $sql;
           if( $db->Execute($sql)){
               $error==0;
           }else{
               $error==1;
           }
     }

    if($error==0){
        echo "{success:true}";
    }else{
        echo "{failure:true}";
    }

}

function saveLabResults($lab_obj,$lab_obj_sub,$labNo,$encounter_nr){
    global $db,$currUser;
    $nTest='';
    $nbuf = array();
    foreach($_POST AS $z=>$y){
        //while (list($z, $y) = each($_POST)) {
        if (substr($z, 0, 5) == 'group') {
            $strz= explode('-',$z) ;
            $arr=array($strz[1]);

            foreach($arr as $items){
                $nTestResult= $lab_obj->getTestParam($items);
                $nTestRow=$nTestResult->FetchRow();
                $nTest= $nTestRow['id'];

            }
            $arr2=array($strz[2]);
            foreach($arr2 as $items2){
                $nbuf[$z] .= $y;
//                echo $nbuf[$z];
            }
        }

        if ($result_tests = $lab_obj->GetTestsToDo($labNo)) {
            while ($row_tests = $result_tests->FetchRow()) {
                if ($z == $row_tests['item_id']) {
                    $nbuf[$z] = $y;
                }
            }
        }
    }

    $dbuf['job_id'] = $labNo;//$_POST['labNo'];
    $dbuf['encounter_nr'] = $encounter_nr;
    $dbuf['test_date'] = date('Y-m-d');
    $dbuf['test_time'] = date('H:i:s');
    $dbuf['status'] = 'Results Entered';
    $dbuf['history'] = "Create " . date('Y-m-d H:i:s') . " " . $_SESSION['sess_user_name'] . "\n";
    $dbuf['create_id'] = $_SESSION['sess_user_name'];
    $dbuf['create_time'] = date('YmdHis');

    # Insert new job record
    $lab_obj->setDataArray($dbuf);
    $saved=false;
    if ($lab_obj->insertDataFromInternalArray()) {
//        $pk_nr = $db->Insert_ID();
//        $batch_nr = $lab_obj->LastInsertPK('batch_nr', $pk_nr);
        $sql="SELECT LAST_INSERT_ID()";
        $request=$db->Execute($sql);
        $row=$request->FetchRow();
        $pk_nr = $row[0];
        $batch_nr = $pk_nr;//$lab_obj->LastInsertPK('batch_nr', $pk_nr);
        foreach ($nbuf as $key => $value) {
            if (isset($value) && !empty($value)) {

//                    if (substr($key,0,5) == 'group') {
//                        $key = $nTest;
//                    }


                $parsedParamList['batch_nr'] = $batch_nr;
                $parsedParamList['encounter_nr'] = $encounter_nr;
                $parsedParamList['job_id'] = $labNo;//$_POST['job_id'];
                $parsedParamList['paramater_name'] = $key;
                $parsedParamList['parameter_value'] = $value;
                $parsedParamList['doneBy'] = $currUser;

                $lab_obj_sub->setDataArray($parsedParamList);
                $lab_obj_sub->insertDataFromInternalArray();
            }
        }
        $saved = true;

    } else {
        echo "{failure:true}";
    }

    if($saved){
        if(updatePatientStatus($encounter_nr,$labNo,"Laboratory","Test Done","Test Done",$currUser)){

            $sql="Update care_test_request_chemlabor_sub set TestStatus='Test Done' where batch_nr='$labNo'";
            $db->Execute($sql);
            echo "{success:true}";
        }else{
            echo "{failure:true}";
        }
    }
}


function updatePatientStatus($encounter_nr,$batch_nr,$statusType,$status,$statusDesc,$currUser){
    global $db;
    $debug=false;
    $updateTime=date('Y-m-d H:i:s');

    $sql="INSERT INTO `care_test_request_status` (`encounter_nr`,`batch_nr`,`updateTime`,`statusType`,`status`,`statusDesc`,`inputby`) 
            VALUES('$encounter_nr', '$batch_nr','$updateTime','$statusType','$status','$statusDesc','$currUser')";
    if($debug) echo $sql;
    if($db->Execute($sql)){
        return true;
    }else{
        return false;
    }
}

function getStatusLog($labNo){
    global $db;
    $debug=false;

    $sql="SELECT `ID`,`encounter_nr`,`batch_nr`,`updateTime`,`statusType`,`status`,`statusDesc`,`inputby`
FROM `care_test_request_status` where batch_nr='$labNo'";
    if ($debug) echo $sql;

    $result = $db->Execute($sql);

    $numRows = $result->RecordCount();
    echo '[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        echo '{"ID":"' . $row['ID'] .'","EncounterNo":"' . $row['encounter_nr'] .'","LabNo":"' . $row['batch_nr']
        .'","UpdateTime":"' . $row['updateTime'] .'","StatusType":"' . $row['statusType'] .'","Status":"' . $row['status']
        .'","Description":"' . $row['statusDesc'] .'","InputBy":"' . $row['inputby'] .'"}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }
    }
    echo ']';

}

function receiveSpecimen($labNo,$encounter_nr){
    global $db,$currUser;

    $collDate=date('Y-m-d H:i:s');
    $receivedBy=($_POST['receivedBy'])?($_POST['receivedBy']):$currUser;

    $sql="Update care_test_request_chemlabor_sub set sampleReceivedTime='$collDate',sampleReceivedBy='$receivedBy'
          where batch_nr='$labNo'";

    if($db->Execute($sql)){
        if(updatePatientStatus($encounter_nr,$labNo,"Laboratory","Sample Received","Sample Received",$receivedBy)){
            echo "{success:true}";
        }else{
            echo "{failure:true}";
        }
    }
}

function getSpecimens(){
    global $db;
    $debug=FALSE;
    $sql = "SELECT `ID`,`Description` FROM `care_tz_laboratory_Specimens`";

    if ($debug) echo $sql;

    $result = $db->Execute($sql);

    $numRows = $result->RecordCount();
    echo '[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        echo '{"ID":"' . $row['ID'] . '","Description":"' . $row['Description']. '"}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }

    }
    echo ']';
}

function collectSpecimen($labNo,$encounter_nr){
    global $db,$currUser;

    $collDate=date('Y-m-d H:i:s');
    $sampleName=$_POST['sampleName'];

    $collectedBy=($_POST['collectedBy'])?($_POST['collectedBy']):$currUser;

    $sql="Update care_test_request_chemlabor_sub set sampleCollectionTime='$collDate',SampleName='$sampleName'
          ,SampleCollectedby='$collectedBy' where batch_nr='$labNo'";

    if($db->Execute($sql)){
        if(updatePatientStatus($encounter_nr,$labNo,"Laboratory","Sample collected","Sample Collected",$collectedBy)){
            echo "{success:true}";
        }else{
            echo "{failure:true}";
        }
    }
}

if (!function_exists('json_esc')) {
    function json_esc($input, $esc_html = true) {
        $result = '';
        if (!is_string($input)) {
            $input = (string) $input;
        }

        $conv = array("\x08" => '\\b', "\t" => '\\t', "\n" => '\\n', "\f" => '\\f', "\r" => '\\r', '"' => '\\"', "'" => "\\'", '\\' => '\\\\');
        if ($esc_html) {
            $conv['<'] = '\\u003C';
            $conv['>'] = '\\u003E';
        }

        for ($i = 0, $len = strlen($input); $i < $len; $i++) {
            if (isset($conv[$input[$i]])) {
                $result .= $conv[$input[$i]];
            }
            else if ($input[$i] < ' ') {
                $result .= sprintf('\\u%04x', ord($input[$i]));
            }
            else {
                $result .= $input[$i];
            }
        }

        return $result;
    }
}

function escapeJsonString($value) { # list from www.json.org: (\b backspace, \f formfeed)
    $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
    $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
    $result = str_replace($escapers, $replacements, $value);
    return $result;
}


function getPendingTests($labNo){
    global $db;
    $debug=false;

    $sql="SELECT t.`batch_nr`,t.`encounter_nr`,p.`name`,t.`notes`,s.`item_id`,s.`sampleCollectionTime`,s.`SampleName`,s.`SampleCollectedby`
            ,s.`sampleReceivedTime`,s.`TestDoneby`,s.`resultsReceivedTime`,s.`resultsVerifiedTime`,s.`verifiedby`,s.`ApprovedBy`
            ,s.`ApprovedTime`,s.testStatus,p.field_type,p.add_label FROM
              care_test_request_chemlabor t LEFT JOIN care_test_request_chemlabor_sub s ON t.batch_nr = s.batch_nr
              LEFT JOIN `care_tz_laboratory_param` p ON s.`item_id`=p.`item_id`
            WHERE t.batch_nr = '$labNo'";

    if ($debug) echo $sql;

    $result = $db->Execute($sql);

    $numRows = $result->RecordCount();
    echo '[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
          $notes= escapeJsonString($row['notes']);
          //$notes = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row['Full Description']);
        echo '{"LabNo":"' . $row['batch_nr'] . '","Description":"' . $row['name'] . '","PartCode":"' . $row['item_id']. '","SampleCollectionTime":"' . $row['sampleCollectionTime']
            . '","SampleName":"' . $row['SampleName']. '","SampleCollectedBy":"' . $row['SampleCollectedby']. '","SampleReceivedTime":"' . $row['sampleReceivedTime']
            . '","TestDoneby":"' . $row['TestDoneby']. '","ResultsReceivedTime":"' . $row['resultsReceivedTime']. '","ResultsVerifiedTime":"' . $row['resultsVerifiedTime']
            . '","Verifiedby":"' . $row['verifiedby']. '","ApprovedBy":"' . $row['ApprovedBy']. '","ApprovedTime":"' . $row['ApprovedTime']
            . '","Notes":"' . $notes. '","EncounterNo":"' . $row['encounter_nr']. '","TestStatus":"' . $row['testStatus']. '","InputType":"' . $row['field_type']
            . '","Label":"' . $row['add_label']. '"}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }

    }
    echo ']';
}

function getLabParamGroups(){
    global $db;
    $debug=FALSE;
    $sql = "SELECT nr,`name` FROM `care_tz_laboratory_param` WHERE group_id='-1'";

    if ($debug) echo $sql;

    $result = $db->Execute($sql);

    $numRows = $result->RecordCount();
    echo '[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        echo '{"ID":"' . $row['nr']. '","Description":"' . $row['name'] . '"}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }

    }
    echo ']';
}

function getLabParams(){
    global $db;
    $debug=FALSE;
    $sql = "SELECT `item_id`,`group_id`,`name`,`price`,`field_type` FROM `care_tz_laboratory_param`";

    if ($debug) echo $sql;

    $result = $db->Execute($sql);

    $numRows = $result->RecordCount();
    echo '[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        echo '{"PartCode":"' . $row['item_id']. '","Description":"' . $row['name'] . '","GroupID":"' . $row['group_id']
            . '","Price":"' . $row['price']. '","FieldType":"' . $row['field_type']. '"}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }

    }
    echo ']';
}

function getLabPatients(){
    global $db;
    $debug=false;
    $enclass=2;
    $sql = "SELECT p.pid,concat(name_first,' ',name_last) as names, batch_nr, tr.encounter_nr,p.selian_pid,tr.send_date,
       p.sex,p.date_birth,p.phone_1_nr,p.insurance_ID,tr.priority,tr.create_id FROM care_test_request_chemlabor tr
                left join care_encounter e on tr.encounter_nr=e.encounter_nr
                left join care_person p on e.pid=p.pid
                WHERE (tr.status='pending' OR tr.status='')";

    if($enclass<>''){
        $sql=$sql." and e.encounter_class_nr=$enclass";
    }
    $sql=$sql." and date_format(tr.send_date,'%Y-%m-%d')='".date('Y-m-d')."' ORDER BY  tr.send_date ASC";

    if ($debug) echo $sql;

    $result = $db->Execute($sql);

    $numRows = $result->RecordCount();
    echo '[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
      //  $description = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row['Full Description']);
        echo '{"Pid":"' . $row['pid'] . '","EncounterNo":"' . $row['encounter_nr'] . '","FileNo":"' . $row['selian_pid']. '","Names":"' . $row['names']
            . '","LabNo":"' . $row['batch_nr']. '","RequestDate":"' . $row['send_date']. '","Sex":"' . $row['sex']. '","Dob":"' . $row['date_birth']
            . '","Phone":"' . $row['phone_1_nr']. '","PayMode":"' . $row['insurance_ID']. '","CreateID":"' . $row['create_id']. '","Priority":"' . $row['priority']. '"}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }

    }
    echo ']';


}

function assignBed($encNr,$wrdNo,$rm,$bd,$ward_obj){
    global $db;

    if($ward_obj->AdmitInWard($encNr,$wrdNo,$rm,$bd)){
        //echo "ok";
        if($ward_obj->setAdmittedInWard($encNr,$wrdNo,$rm,$bd)){
            echo "{success:true}";
        }else{
            echo "{failure:true}";
        }
    }else{
        echo "{failure:true}";
    }
}

function saveComplaints(){
    global $db;

    $encounter_nr=$_REQUEST["encNr"];
    $type_nr="5";
    $notes=$_REQUEST["complaint"];
    $short_notes=$_REQUEST["comment"];
    $personell_name=$_SESSION['sess_user_name'];
    $date=date('Y-m-d');
    $time=date('H:i:s');
    $status="Active";
    $history="Created: " . date('Y-m-d H:i:s') . " : " . $_SESSION['sess_user_name'] ."\n";
    $modify_id=$_SESSION['sess_user_name'];
    $modify_time=date('Y-m-d H:i:s');
    $create_id=$_SESSION['sess_user_name'];
    $create_time=date('Y-m-d H:i:s');
    
    $sql="INSERT INTO `care_encounter_notes` (
      `encounter_nr`,  `type_nr`,`notes`,short_notes ,`personell_name`,`date`,`time`,`status`,
      `history`, `modify_id`,`modify_time`,`create_id`,`create_time`) 
    VALUES (
        '$encounter_nr','$type_nr', '$notes','$short_notes','$personell_name','$date','$time','$status',
        '$history','$modify_id','$modify_time','$create_id','$create_time')";

    if($db->Execute($sql)){
        echo "{success:true,Error:'Notes Successfully Saved'}";
    }else{
        echo "{failure:true,Error:$sql}";
    }
}

function getIcd10Codes($start, $limit){
    global $db;
    $debug=false;

    $sql = "SELECT `Category Code`,`Category Title`,`Diagnosis Code`,`Full Code`,`Abbreviated Description`,`Full Description` 
              FROM  `care_icd10_new`";

//    if ($start <> '' && $limit <> '')
//        $sql.=" limit $start,$limit";

    if ($debug) echo $sql;

    $result = $db->Execute($sql);

    $numRows = $result->RecordCount();
    echo '[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        $description = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row['Full Description']);

        echo '{"CategoryTitle":"' . $row['Category Title'] . '","FullCode":"' . $row['Full Code'] . '","FullDescription":"' . $description. '"}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }

    }
    echo ']';

}

function getAnnouncements(){
    global $db;
    $debug=false;

    $sql = "SELECT nr,preface,body FROM care_news_article where status='pending'";

    if ($debug) echo $sql;

    $result = $db->Execute($sql);

    $numRows = $result->RecordCount();
    echo '{
    "Announcements":[';
    $counter = 0;
    while ($row = $result->FetchRow()) {

        $text = str_replace("\r\n","\n",$row['body']);
        $paragraphs = preg_split("/[\n]{2,}/",$text);
        foreach ($paragraphs as $key => $p) {
            $paragraphs[$key] = "<p>".str_replace("\n","<br />",$paragraphs[$key])."</p>";
        }

        $text = implode("", $paragraphs);

        $body= $text;

        echo '{"nr":"' . $row['nr'] . '","title":"' . $row['preface'] . '","body":"' . $body. '"}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }

    }
    echo ']}';

}


function getClinicalRooms(){
    global $db;
    $debug=false;

    $sql = "SELECT ID,name_formal FROM care_department WHERE TYPE=4";

    if ($debug) echo $sql;

    $result = $db->Execute($sql);

    $numRows = $result->RecordCount();
    echo '{
    "rooms":[';
    $counter = 0;
    while ($row = $result->FetchRow()) {

        echo '{"ID":"' . $row['ID'] . '","Description":"' . $row['name_formal'] . '"}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }

    }
    echo ']}';

}

function getClinicsInfo(){
    global $db;
    $debug=false;

    $currDate=date('Y-m-d');

    $sql="SELECT e.current_dept_nr,d.`name_formal` FROM care_encounter e LEFT JOIN care_department d
            ON e.`current_dept_nr`=d.`nr`
            WHERE encounter_date='$currDate' and e.encounter_class_nr=2 and e.current_dept_nr<>0 GROUP BY d.`nr`ORDER BY d.`name_formal` ASC";
    if($debug) echo $sql;

    $results=$db->Execute($sql);
    $numRows=$results->RecordCount();

    echo '{
    "OpdVisits":[';
    $counter=0;

    while($row=$results->FetchRow()){
        $totalPatients=getTotalOPDPatients($row[0]);
        $males=getTotalPatientsByGender($row[0],'m');
        $females=getTotalPatientsByGender($row[0],'f');
        $below5=getTotalPatientsByAge($row[0],'<=','5');
        $above5=getTotalPatientsByAge($row[0],'>','5');

        echo '{"Clinic":"'.$row[1].'","TotalPatients":"'.$totalPatients.'","Males":"'.$males.'","Females":"'
            .$females.'","Below5":"'.$below5.'","Above5":"'.$above5.'"}';

        $counter++;
        if($counter<$numRows){
            echo ",";
        }
    }

    echo "]}";
}


function getTotalOPDPatients($clinic){
    global $db;
    $debug=false;

    $currDate=date('Y-m-d');

    $sql="SELECT COUNT(encounter_nr) FROM care_encounter WHERE current_dept_nr='$clinic' AND encounter_date='$currDate' GROUP BY current_dept_nr";
    if($debug) echo $sql;
    $result=$db->Execute($sql);
    $row=$result->FetchRow();

    return $row[0];
}


function getTotalPatientsByGender($clinic,$gender){
    global $db;
    $debug=false;

    $currDate=date('Y-m-d');

    $sql="SELECT COUNT(encounter_nr) FROM care_encounter e
            LEFT JOIN care_person p ON e.`pid`=p.`pid`
             WHERE current_dept_nr='$clinic' AND encounter_date='$currDate' AND p.`sex`='$gender' GROUP BY current_dept_nr";
    if($debug) echo $sql;
    $result=$db->Execute($sql);
    $row=$result->FetchRow();

    return $row[0];
}

function getTotalPatientsByAge($clinic,$sign,$age){
    global $db;
    $debug=false;

    $currDate=date('Y-m-d');

    $sql="SELECT COUNT(encounter_nr) FROM care_person p LEFT JOIN care_encounter e
            ON p.`pid`=e.`pid`
             WHERE e.`encounter_date`='$currDate' AND e.`current_dept_nr`=$clinic AND
            (TIMESTAMPDIFF(YEAR, date_birth, CURDATE()))$sign $age";
    if($debug) echo $sql;
    $result=$db->Execute($sql);
    $row=$result->FetchRow();

    return $row[0];
}

function getRadiology($encNo)
{
    global $db;
    $debug = false;

    $sql = "select a.batch_nr,a.encounter_nr,b.partcode,b.item_description,a.clinical_info,
                a.send_date,a.test_date,b.unit_price,a.status,a.create_id
                from care_test_request_radio a
                inner join care_tz_drugsandservices b on a.test_request=b.partcode
                inner join care_encounter d on a.encounter_nr=d.encounter_nr
                WHERE d.encounter_class_nr='2' and a.encounter_nr=$encNo
                ORDER BY d.encounter_date DESC , a.encounter_nr ASC";

    if ($debug) echo $sql;

    $result = $db->Execute($sql);

    $numRows = $result->RecordCount();
    echo '[';
    $counter = 0;
    while ($row = $result->FetchRow()) {

        echo '{"Status":"' . $row['status'] . '","BatchNo":"' . $row['batch_nr'] . '","Description":"' . $row['item_description']  . '","TimeRequested":"' . $row['send_date'] . '","RequestedBy":"' . $row['create_id']. '"}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }

    }
    echo ']';
}

function getNotes($encNo)
{
    global $db;
    $debug = false;

    $sql = "SELECT CONCAT(`date`,' ',`time`) AS createTime,t.`name`,notes,n.`personell_name` FROM care_encounter_notes n
                LEFT JOIN care_type_notes t ON n.`type_nr`=t.nr WHERE encounter_nr='$encNo'";

    if ($debug) echo $sql;

    $result = $db->Execute($sql);

    $numRows = $result->RecordCount();
    echo '[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
            $notes= escapeJsonString($row['notes']);
            $text = escapeJsonString($row['short_notes']);

        echo '{"NotesType":"' . $row['name'] .'","Notes":"' . $notes. '","CreateTime":"' . $row['createTime']  . '","TreatedBy":"' . $row['personell_name']. '"}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }

    }
    echo ']';
}

function getLabTests($encNo)
{
    global $db;
    $debug = false;

    $sql = "SELECT s.encounter_nr,s.batch_nr,s.paramater_name,c.send_date,c.status,c.create_id FROM care_test_request_chemlabor_sub s
              INNER JOIN care_test_request_chemlabor c ON s.batch_nr=c.batch_nr WHERE s.encounter_nr='$encNo'";

    if ($debug) echo $sql;

    $result = $db->Execute($sql);

    $numRows = $result->RecordCount();
    echo '[';
    $counter = 0;
    while ($row = $result->FetchRow()) {

        echo '{"Status":"' . $row['status'] . '","BatchNo":"' . $row['batch_nr'] . '","Description":"' . $row['paramater_name']  . '","TimeRequested":"' . $row['send_date'] . '","RequestedBy":"' . $row['create_id']. '"}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }

    }
    echo ']';
}

function getDiagnosis($encNo)
{
    global $db;
    $debug = false;

    $sql = "SELECT ICD_10_code,icd_10_description,TIMESTAMP,TYPE FROM care_tz_diagnosis
            WHERE encounter_nr='$encNo'";

    if ($debug) echo $sql;

    $result = $db->Execute($sql);

    $numRows = $result->RecordCount();
    echo '[';
    $counter = 0;
    while ($row = $result->FetchRow()) {

        echo '{"Code":"' . $row['ICD_10_code'] . '","Description":"' . $row['icd_10_description']  . '","Time":"' . $row['TIMESTAMP'] . '","Type":"' . $row['TYPE']. '"}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }

    }
    echo ']';
}

function getVitals($encNo)
{
    global $db;
    $debug = false;

    $sql = "SELECT m.encounter_nr,m.msr_type_nr,t.name,m.value,m.`create_time`,t.`lower`,t.`upper` FROM care_encounter_measurement m
            LEFT JOIN care_type_measurement t ON m.msr_type_nr=t.nr
            WHERE m.encounter_nr='$encNo'";

    if ($debug) echo $sql;

    $result = $db->Execute($sql);

    $numRows = $result->RecordCount();
    echo '[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        ($row['lower']<>''? $lower=$row['lower']:$lower=0);
        ($row['upper']<>''? $upper=$row['upper']:$upper=0);

        echo '{"EncounterNo":"' . $row['encounter_nr'] . '","VitalsTime":"' . $row['create_time']
            . '","VitalID":"' . $row['msr_type_nr'] . '","Description":"' . $row['name']
            . '","Value":"' . $row['value'] .'","Lower":' . $lower.',"Upper":' . $upper. '}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }

    }
    echo ']';
}

function getProcedures($encNo){
    global $db;
    $debug=false;

    $sql="SELECT d.`status`,d.`nr`,d.`drug_class`,d.partcode,d.`article`,(d.`dosage`*d.`days`*d.`times_per_day`) AS Qty,d.`price`
            ,d.`modify_time` FROM care_encounter_prescription d
            LEFT JOIN care_encounter p ON d.`encounter_nr`=p.`encounter_nr`
            WHERE d.`drug_class` not in('Drug_list','medical-supplies','XRAY','LABORATORY') and d.encounter_nr='$encNo'";
    if($debug) echo $sql;

    $result=$db->Execute($sql);

    $numRows=$result->RecordCount();
    echo '[';
    $counter=0;
    while ($row = $result->FetchRow()) {

        echo '{"Status":"'. $row['status'].'","No":"'. $row['nr'].'","PartCode":"'. $row['partcode'].'","Description":"'. $row['article']
            .'","Qty":"'. $row['Qty'] .'","Price":"'. $row['price'].'","EncounterNo":"'. $row['encounter_nr'].'","RequestTime":"'. $row['modify_time'].'"}';
        if ($counter<>$numRows){
            echo ",";
        }
        $counter++;
    }
    echo ']';

}

//Status,Pid,PartCode,Description,Dosage,TimesPerDay,Days,Price,Total,EncounterNo,PrescribeDate,Notes
function getDrugsToReturn($pid, $store) {
//    var_dump($_POST);
    $json_data = $_POST['updateReturnDrugs']; // file_get_contents('php://input');
    $strData = json_decode($json_data);

    if (!empty($strData)) {
        updateReturnDrugs($strData, $pid);
    } else {
        getIssuedPrescriptions($store);
    }
}

function updateReturnDrugs($strData, $pid) {
    global $db;
    $debug = true;
    $UpdateRowsCount = $_POST["selectedCount"];

    $error = 0;
    if ($UpdateRowsCount > 1) {
        foreach ($strData as $key => $value) {
            //echo "<br> Items are in group ". $key;
            $sql = "INSERT INTO `care_ke_internal_returns` (
                  `return_no`,`presc_nr`,`status`,`return_date`,`return_time`,`return_type`,`store_loc`,`store_desc`,
                  `OP_no`,`patient_name`,`item_id`,`Item_desc`,`qty`,`price`,`return_qty`,`total`,`period`,`input_user` ) VALUES(";
            foreach ($value as $k => $strVal) {
                //echo "<br> Items in second loop  $k and Value $strVal";

                $sql .= "'" . $strVal . "',";
                foreach ($strVal as $x => $xval) {
                    $sql .= "'" . $xval . "',";
                    //echo "<br> Items in third loop  $x and Value $xval";
                }
            }
            echo ")";

            if ($debug)
                echo $sql;

            if ($db->Execute($sql)) {
                $error = 0;
            } else {
                $error = 1;
            }
        }
    } else {
        $sql ="INSERT INTO `care_ke_internal_returns` (
                  `return_no`,`presc_nr`,`status`,`return_date`,`return_time`,`return_type`,`store_loc`,`store_desc`,
                  `OP_no`,`patient_name`,`item_id`,`Item_desc`,`qty`,`price`,`return_qty`,`total`,`period`,`input_user` ) VALUES(";
        $id = '';
        foreach ($strData as $key => $value) {
            $sql .= "'" . $value . "',";
            if ($key == 'ID') {
                $id = $value;
            }
//            if ($key == 'Bill_Date') {
//                $date1 = new DateTime($value);
//                $value = $date1->format("Y-m-d");
//            }
        }
        echo ")";
        //$sql = substr($sql, 0, -2) . " WHERE ID='$id'";
        if ($debug)
            echo $sql;
        if ($db->Execute($sql)) {
            $error = 0;
        } else {
            $error = 1;
        }
    }
    if ($error == 0) {
        $results = "{success: true,'Error':'Successfully saved Returns'}";
//        getAllBills($pid,$bill_number);
    } else {
        $results = "{failure: true,'Error':'Cannot update Return Error No $error'}"; // Return the error message(s)
    }
    echo $results;
}

function getIssuedPrescriptions($store){
    global $db;
    $debug=false;
    $prescSource=$_REQUEST['prescSource'];
    $requestparam=$_REQUEST['requestparam'];
    if($store==''){
        $store='Dispens';
    }

    $sql="SELECT `id`,`presc_nr`,`status`,`order_date`,`order_time`,
          `store_loc`,`adm_no`,`OP_no`,`patient_name`,`item_id`,`Item_desc`,
          `qty`,`price`,`issued`,`orign_qty`,`balance`,unit_cost,input_user
        FROM `care_ke_internal_orders` where order_date>'2020-01-01'";

    if(isset($prescSource) && $prescSource<>''){
        $sql=$sql." and `IP-OP`='$prescSource'";
    }

    if(isset($requestparam) && $requestparam<>''){
        $sql=$sql." and `OP_no`='$requestparam' OR patient_name LIKE '%$requestparam%'";
    }
    $sql=$sql." ORDER BY order_date DESC LIMIT 0,100";

    if($debug) echo $sql;

    $result=$db->Execute($sql);

    $numRows=$result->RecordCount();
    echo '[';
    $counter=0;
    while ($row = $result->FetchRow()) {
        echo '{"ID":"'. $row['id'].'","PrescNo":"'. $row['presc_nr'].'","Status":"'. $row['status']
            .'","OrderDate":"'. $row['order_date'].' '. $row['order_time'].'","Store":"'. $row['store_loc']
            .'","EncounterNr":"'. $row['adm_no'] .'","PID":"'. $row['OP_no'].'","PatientName":"'. $row['patient_name'].'","PartCode":"'. $row['item_id']
            .'","Description":"'. $row['Item_desc'].'","Qty":"'. $row['qty'].'","Price":"'. $row['price']
            .'","Issued":"'. $row['issued'].'","Balance":"'. $row['balance']
            .'","TotalCost":"'. $row['unit_cost'] .'","IssuedBy":"'. $row['input_user'].'"}';
        if ($counter<>$numRows){
            echo ",";
        }
        $counter++;
    }
    echo ']';
}



function getPrescriptions($encNo,$store){
    global $db;
    $debug=false;
    $source=$_REQUEST['prescSource'];
    if($store==''){
        $store='Dispens';
    }

    $sql="SELECT d.`nr`,d.`status`,p.`pid`,d.`encounter_nr`,CONCAT(pr.`name_first`,' ',pr.`name_last`,' ',pr.`name_2`) AS pnames,
            d.`drug_class`,d.partcode,d.`article`,d.`dosage`,d.`days`,d.`times_per_day`,d.`price`,
            ROUND(d.`dosage`*d.`days`*d.`times_per_day`,0) AS QtyOrdered,l.`quantity` AS QtyInStore
            ,d.`modify_time`,d.notes,d.nr,d.prescriber,p.encounter_date,d.prescribe_date FROM care_encounter_prescription d
            LEFT JOIN care_encounter p ON d.`encounter_nr`=p.`encounter_nr`
            LEFT JOIN `care_person` pr ON p.`pid`=pr.`pid`
            LEFT JOIN `care_ke_locstock` l ON d.`partcode`=l.`stockid`
            WHERE d.`drug_class` IN('Drug_list','medical-supplies')
            AND l.`loccode`='$store' and d.status='pending'";

    if($source=='Prescription'){
        $sql=$sql." AND d.encounter_nr='$encNo'";
    }

        $sql=$sql." ORDER BY d.prescribe_date DESC";

    if($debug) echo $sql;

    $result=$db->Execute($sql);

    $numRows=$result->RecordCount();
    echo '[';
    $counter=0;
    while ($row = $result->FetchRow()) {
        $total=$row['price']*$row['QtyOrdered'];
        $desc=escapeJsonString($row['article']);
        $notes=escapeJsonString($row['notes']);
        echo '{"Status":"'. $row['status'].'","Pid":"'. $row['pid'].'","PNames":"'. $row['pnames'].'","PartCode":"'. $row['partcode']
            .'","Description":"'. $desc.'","Dosage":"'. $row['dosage'].'","TimesPerDay":"'. $row['times_per_day']
            .'","Days":"'. $row['days'] .'","Price":"'. $row['price'].'","Total":"'. $total.'","EncounterNo":"'. $row['encounter_nr'].'","PrescribeDate":"'. $row['modify_time']
            .'","Notes":"'. $notes.'","Nr":"'. $row['nr'].'","Prescriber":"'. $row['prescriber'].'","TotalQty":"'. $row['QtyOrdered'].'","QtyInStore":"'. $row['QtyInStore'].'","QtyIssued":""}';
        if ($counter<>$numRows){
            echo ",";
        }
        $counter++;
    }
    echo ']';
}

function getClinicsList($dept_obj){
    $medical_depts = $dept_obj->getAllMedical();

    echo '{
    "clinics":[';
    foreach($medical_depts as $x => $v){
    //while (list($x, $v) = each($medical_depts)) {
        echo '{"ID":"'.$v['nr'] .'","Name":"' . $v['name_formal'] . '"},';
    }

    echo "]}";



}
function getCounties(){
    global $db;

    $sql="SELECT `Code`,`County` FROM care_ke_counties";
    $results=$db->Execute($sql);
    $total=$results->RecordCount();

    echo '{
    "total":"' . $total . '","counties":[';
    $counter=0;
    while($row=$results->FetchRow()){
        echo '{"Code":"' . $row['Code'] .'","County":"' . $row['County'] .'"}';

        $counter++;

        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']}';
}


function getWaitingList($wrdNo,$ward_obj){

    $waitlist=$ward_obj->createWaitingInpatientList($wrdNo);
    $waitlist_count=$ward_obj->LastRecordCount();

    if($waitlist){
        echo '[';
        $counter=0;
        
        while($row=$waitlist->FetchRow()){
            $names=$row['name_first']." " . $row['name_last'];
            echo '{"Pid":"' . $row['pid'] .'","Encounter_Nr":"' . $row['encounter_nr'] .'","Names":"'
                . $names.'","Dob":"' . $row['date_birth'] .'","WardNo":"' . $row['current_ward_nr']
                .'","AdmissionDate":"' . $row['encounter_date'].'","Sex":"' . $row['sex'].'"}';
    
            $counter++;
    
            if ($counter <> $waitlist_count) {
                echo ",";
            }
        }
         echo ']';
    }else{
        echo "{failure:true}";
    }
	
}

function  dischargePatients($enc_obj,$person,$encoder){
    $encNr=$_POST['encounter_nr'];
    $mode='release';
    $relart=$_POST['dischargeType'];
    $info=$_POST['dischargeSummary'];
    $results='';
    //echo "adfdafdaf";
    if( $enc_obj->loadEncounterData($encNr)) {
        $x_date = new DateTime($_POST['dischargeDate']);
        $x_time = $_POST['dischargeTime'];
        if ($mode == 'release') {
            $date = $x_date->format('Y-m-d');
            $time = $x_time;
            switch ($relart) {
                case 1:
                    $released = $enc_obj->Discharge($encNr, $relart, $date, $time);  #Regular Discharged
                    $results= $released;
                case 2:
                    {
                    }
                case 7:
                    {  # If patient died
                        $death['death_date'] = $date;
                        $death['death_encounter_nr'] = $encNr;
                        $death['history'] = $enc_obj->ConcatHistory("Discharged (cause: death) " . date('Y-m-d H:i:s') . " $encoder\n");
                        $death['modify_id'] = $encoder;
                        $death['modify_time'] = date('YmdHis');
                        $person->setDeathInfo($enc_obj->PID(), $death);
                    }
                case 3:
                    {
                    }
                    break;
                case 9:
                    $released = $enc_obj->DischargeMedically($encNr, $relart, $date, $time); # Medical Discharge
                    break;
                default:
                    $released = false;
            }

            if ($released) {
                if (!empty($info)) {
                    $data_array['notes'] = $info;
                    $data_array['encounter_nr'] = $encNr;
                    $data_array['date'] = $date;
                    $data_array['time'] = $time;
                    $data_array['personell_name'] = $encoder;
                    $enc_obj->saveDischargeNotesFromArray($data_array);
                }
                echo $results;
            }
        }    // end of if (mode=release)
    }else{
        echo "{failure:true,'Released':'could not load enounter data'}";
    }

}

function saveVitals(){
    global $db;
    $pid=$_POST['pid'];
    $enounterNo=$_POST['encounterNo'];
    $weight=$_POST['weight'];
    $height=$_POST['height'];
    $head_c=$_POST['head_c'];
    $bp=$_POST['bp'];
    $bp2=$_POST['bp2'];
    $pulse=$_POST['pulse'];
    $resprate=$_POST['resprate'];
    $temperature=$_POST['temperature'];
    $notes=$_POST['notes'];
    $bmi=$_POST['bmi'];
    $spo2=$_POST['spo2'];
    $htc=$_POST['htc'];
    $vitalsDate=date('Y-m-d');
    $vitalsTime=date('H:i:s');
    $inputUser=$_SESSION["sess_login_username"];

    $sql="INSERT INTO `care_encounter_vitals` (
          `PID`,`EncounterNo`,`temperature`,`pulse`,`respiration`,`systolic`,`diastolic`,`height`, `weight`,
          `bmi`, `head_circumference`,`spo2`,`notes`,`htc`,VitalsDate,VitalsTime,inputUser
        ) 
        VALUES  (
            '$pid','$enounterNo', '$temperature', '$pulse','$resprate', '$bp', '$bp2','$height', '$weight',
            '$bmi','$head_c','$spo2','$notes','$htc','$vitalsDate','$vitalsTime','$inputUser')";

    if($db->Execute($sql)){
        echo "{success:true}";
    }else{
        echo "{failure:true,Error:$sql}";
    }
}


function saveVitals2(){
global $db;
$weight=$_POST['weight'];
$height=$_POST['height'];
$head_c=$_POST['head_c'];
$bp=$_POST['bp'];
$bp2=$_POST['bp2'];
$pulse=$_POST['pulse'];
$resprate=$_POST['resprate'];
$temperature=$_POST['temperature'];
$notes=$_POST['notes'];
$bmi=$_POST['bmi'];
$spo2=$_POST['spo2'];
$htc=$_POST['htc'];


if(!isset($wt_unit_nr)||!$wt_unit_nr) $wt_unit_nr=4; # set your default unit of weight msrmnt type, default 6 = kilogram
if(!isset($ht_unit_nr)||!$ht_unit_nr) $ht_unit_nr=1; # set your default unit of height msrmnt type, default 7 = centimeter
if(!isset($hc_unit_nr)||!$hc_unit_nr) $hc_unit_nr=1; # set your default unit of head circumfernce msrmnt type, default 7 = centimeter
if(!isset($htc_unit_nr)||!$htc_unit_nr) $htc_unit_nr=86; # set your default unit of head hct msrmnt type, default 12 = percentage
if(!isset($bmi_unit_nr)||!$bmi_unit_nr) $bmi_unit_nr=13; # set your default unit of head bmi msrmnt type, default 7 = percentage
if(!isset($spo2_unit_nr)||!$spo2_unit_nr) $spo2_unit_nr=154; # set your default unit of head bmi msrmnt type, default 7 = percentage
if(!isset($pulse_unit_nr)||!$pulse_unit_nr) $pulse_unit_nr=8; # set your default unit of head bmi msrmnt type, default 7 = percentage
if(!isset($bp_unit_nr)||!$bp_unit_nr) $bp_unit_nr=7; # set your default unit of head bmi msrmnt type, default 7 = percentage
if(!isset($resprate_unit_nr)||!$resprate_unit_nr) $resprate_unit_nr=11; # set your default unit of head bmi msrmnt type, default 7 = percentage
if(!isset($temp_unit_nr)||!$temp_unit_nr) $temp_unit_nr=5; # set your default unit of head bmi msrmnt type, default 7 = percentage


$_POST['msr_date']=date('Y-m-d');
# Non standard time format
$_POST['msr_time']=date('H:i:s');
$_POST['create_time']=date('YmdHis'); # Create the timestamp to group the values
$_POST['create_id']=$_SESSION['sess_user_name'];
$_POST['history']="Create: ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n";
$_POST['measured_by']=$_SESSION['sess_user_name'];

if($weight||$height||$head_c||$bp||$bp2||$pulse||$resprate||$temperature) {
    # Set to no redirect
    if ($weight) {
        $_POST['value'] = $weight;
        $_POST['msr_type_nr'] = 6; # msrmt type 6 = weight
        $_POST['notes'] = $notes;
        $_POST['unit_nr'] = $wt_unit_nr;
        $_POST['unit_type_nr'] = 4; # 2 = weight
        include('save_admission_data.inc.php');
    }
    if ($height) {
        $_POST['value'] = $height;
        $_POST['msr_type_nr'] = 7;  # msrmt type 7 = height
        $_POST['notes'] = $notes;
        $_POST['unit_nr'] = $ht_unit_nr;
        $_POST['unit_type_nr'] = 1; # 3 = length
        include('save_admission_data.inc.php');
    }
    if ($head_c) {
        $_POST['value'] = $head_c;
        $_POST['msr_type_nr'] = 9; # msrmt type 9 = head circumference
        $_POST['notes'] = $notes;
        $_POST['unit_nr'] = $hc_unit_nr;
        $_POST['unit_type_nr'] = 1; # 3 = length
        include('save_admission_data.inc.php');
    }
    if ($bp) {
        $_POST['value'] = $bp;
        $_POST['msr_type_nr'] = 1; # msrmt type 9 = head circumference
        $_POST['notes'] = $notes;
        $_POST['unit_nr'] = $bp_unit_nr;
        $_POST['unit_type_nr'] = 7; # 3 = length
        include('save_admission_data.inc.php');
    }
    if ($bp2) {
        $_POST['value'] = $bp2;
        $_POST['msr_type_nr'] = 2; # msrmt type 9 = head circumference
        $_POST['notes'] = $notes;
        $_POST['unit_nr'] = $bp_unit_nr;
        $_POST['unit_type_nr'] = 7; # 3 = length
        include('save_admission_data.inc.php');
    }
    if ($pulse) {
        $_POST['value'] = $pulse;
        $_POST['msr_type_nr'] = 10; # msrmt type 10 pulse
        $_POST['notes'] = $notes;
        $_POST['unit_nr'] = $pulse_unit_nr;
        $_POST['unit_type_nr'] = 8; # 3 = length
        include('/save_admission_data.inc.php');
    }
    if ($resprate) {
        $_POST['value'] = $resprate;
        $_POST['msr_type_nr'] = 11; # msrmt type 9 = head circumference
        $_POST['notes'] = $notes;
        $_POST['unit_nr'] = $resprate_unit_nr;
        $_POST['unit_type_nr'] = 11; # 3 = length
        include('save_admission_data.inc.php');
    }
    if ($temperature) {
        $_POST['value'] = $temperature;
        $_POST['msr_type_nr'] = 3; # msrmt type 9 = head circumference
        $_POST['notes'] = $notes;
        $_POST['unit_nr'] = $temp_unit_nr;
        $_POST['unit_type_nr'] = 5; # 3 = length
        include('save_admission_data.inc.php');
    }

    if ($bmi) {
        $_POST['value'] = $bmi;
        $_POST['msr_type_nr'] = 13; # msrmt type 9 = head circumference
        $_POST['notes'] = $notes;
        $_POST['unit_nr'] = $bmi_unit_nr;
        $_POST['unit_type_nr'] = 13; # 12 = Percentage
        include('./include/save_admission_data.inc.php');
    }
    if ($spo2) {
        $_POST['value'] = $spo2;
        $_POST['msr_type_nr'] = 12; # msrmt type 9 = head circumference
        $_POST['notes'] = $notes;
        $_POST['unit_nr'] = $spo2_unit_nr;
        $_POST['unit_type_nr'] = 6; # 12 = Percentage
        include('./include/save_admission_data.inc.php');
    }

    if ($htc) {
        $_POST['value'] = $htc;
        $_POST['msr_type_nr'] = 13; # msrmt type 9 = head circumference
        $_POST['notes'] = $notes;
        $_POST['unit_nr'] = $htc_unit_nr;
        $_POST['unit_type_nr'] = 6; # 3 = length
        //$_POST['htc_reason']=$htc_reason; # 3 = length
        include('./include/save_admission_data.inc.php');
    }
	
	echo "{success:true}";
}
}



function checkSponges($encNr,$procedureNo){
    global $db;
    $debug=false;
    
    $sql="Select count(id) from care_ke_anestheatresponge where encounter_nr=$encNr and ProcedureNo='$procedureNo'";
    if($debug){ echo $sql; }
    $request=$db->Execute($sql);
    $row=$request->FetchRow();
    
    if($row[0]>0){
        return true;
    }else{
        return false;
    }
}

function  getSpongeItems($encNr,$procedureNo,$procedureType){
    global $db;
    
    $debug=false;
    
   
    
    $json = $_POST['editedData'];
    $rctData = json_decode($json, TRUE);
    //echo $rctData[encounter_nr];

    if ($encNr == '') {
        $encNr = $rctData['Encounter_Nr'];
    }
    
     if ($procedureType == '') {
        $procedureType = $rctData['ProcedureType'];
    }
    
     if ($procedureNo == '') {
        $procedureNo = $rctData['ProcedureNo'];
    }
    
     if(!checkSponges($encNr,$procedureNo)){
        addSpongeItems($encNr,$procedureType,$procedureNo);
    }
    
    if (count($rctData) > 0) {
        //$encNr = $rctData[encounter_nr];
        updateAnesthesiaSponges($rctData);
    }
    
    $sql="Select ID,Encounter_Nr,ProcedureNo,ProcedureType,Counts,Start,Additions,Close from care_ke_anestheatresponge
             where encounter_nr=$encNr and procedureNo='$procedureNo'";
    if($debug) { 
        echo $sql; 
    }
    
    $result=$db->Execute($sql);
    $total=$result->RecordCount();
    echo '{
    "total":"' . $total . '","spongecounts":[';
    $counter=0;
    while($row=$result->FetchRow()){
        echo '{"ID":"' . $row['ID'] .'","Encounter_Nr":"' . $row['Encounter_Nr'] .'","ProcedureNo":"' . $row['ProcedureNo'].'","ProcedureType":"' . $row['ProcedureType']
                .'","Counts":"' . $row['Counts'] .'","Start":"' . $row['Start'] .'","Additions":"' . $row['Additions'].'","Close":"' . $row['Close'] .'"}';

        $counter++;

        if ($counter <> $total) {
            echo ",";
        }
    }
     echo ']}';
}

function updateAnesthesiaSponges($anesData) {
    global $db;
    $debug = false;
    $sql = 'UPDATE care_ke_anestheatresponge SET ';
    foreach ($anesData as $key => $value) {
        $sql .="`$key`='$value' , ";
    }
    $sql = substr($sql, 0, -2) . ' WHERE ID="' . $anesData['ID'] . '"';

    if ($debug)
        echo $sql;

    if($db->Execute($sql)){
        echo "{success:true}";
    }else{
        echo "{failure:true}";
    }
}

function addSpongeItems($encNr,$procedureType,$procedureNo){
    global $db;
    $debug=false;
    
    $sql="select spongename from care_ke_anesspongetypes";
     if($debug) { echo $sql; }
     
    $request=$db->Execute($sql);
   
    while($row=$request->FetchRow()){
        $sql2="Insert into care_ke_anestheatresponge(encounter_nr,ProcedureNo,ProcedureType,Counts,start,additions,close)
                values ('$encNr','$procedureNo','$procedureType','$row[spongename]','','','')";
        if($debug) { echo $sql2; }

        $db->Execute($sql2);
    }
    
          
}


function getPatientSafety($encNr) {
    global $db;
    $debug = false;

    $sql = "select `id`,`Encounter_nr`, `Anes_Machine`,`Anes_Machine2`,`Airway_IV`,`Safety_Belt`,`Arms_90`,
                `Pressure_Points`,`Eye_Case`,`Patient_Post`,`Armboard_Restraints`,`Arms_Tucked`,`Axillary_Roll`,`Ointment`,
                `Taped`,`Patient_Post1` from `care_ke_anespatientsafety` where encounter_nr=$encNr";
    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);

    $total = $request->RecordCount();

    echo '{"total":"' . $total . '","patientsafety":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {
        echo '{"id":"' . $row['id'] . '","Encounter_nr":"' . $row['Encounter_nr'] . '","pat_Anes_Machine":"' . $row['Anes_Machine']
                . '","pat_Anes_Machine2":"' . $row['Anes_Machine2']. '","pat_Airway_IV":"' . $row['Airway_IV']. '","pat_Safety_Belt":"' . $row['Safety_Belt']
                . '","pat_Arms_90":"' . $row['Arms_90']. '","pat_Pressure_Points":"' . $row['Pressure_Points']. '","pat_Eye_Case":"' . $row['Eye_Case']
                . '","pat_Patient_Post":"' . $row['Patient_Post']. '","pat_Armboard_Restraints":"' . $row['Armboard_Restraints']. '","pat_Arms_Tucked":"' . $row['Arms_Tucked']
                . '","pat_Axillary_Roll":"' . $row['Axillary_Roll']. '","pat_Axillary_Roll":"' . $row['Axillary_Roll']. '","pat_Axillary_Roll":"' . $row['Axillary_Roll']
                . '","pat_Ointment":"' . $row['Ointment']. '","pat_Taped":"' . $row['Taped']. '","pat_Patient_Post1":"' . $row['Patient_Post1']. '"}';

        $counter++;

        if ($counter < $total) {
            echo ",";
        }
    }
    echo ']}';
}


function checkAnesthesiaChart($encNr) {
    global $db;

    $debug = false;

    $sql = "Select count(encounter_nr) as encNr from care_ke_anesthesia2 where encounter_nr =$encNr";
    if ($debug)
        echo $sql;
    $result = $db->Execute($sql);
    $row = $result->FetchRow();
    if ($row[0] < 1) {
        $sql2 = "select field_group,field_items from care_ke_anesthesia_chart_fields";
        if ($debug)
            echo $sql2;
        $result2 = $db->Execute($sql2);
        while ($row2 = $result2->FetchRow()) {
            $sql3 = "Insert into care_ke_anesthesia2(encounter_nr,item_description,item_group)
                    values ('$encNr','$row2[field_items]','$row2[field_group]')";
            if ($debug)
                echo $sql3;
            $db->Execute($sql3);
        }
    }
}

function updateAnesthesiaCharts($anesData) {
    global $db;
    $debug = false;
    $sql = 'UPDATE care_ke_anesthesia2 SET ';
    foreach ($anesData as $key => $value) {
        $sql .="`$key`='$value' , ";
    }
    $sql = substr($sql, 0, -2) . ' WHERE ID="' . $anesData['ID'] . '"';

    if ($debug)
        echo $sql;

    $db->Execute($sql);
}

function getAnesthesiaCharts($encNr) {
    global $db;
    $debug = false;
    $json = $_POST['editedData'];
    $rctData = json_decode($json, TRUE);
    //echo $rctData[encounter_nr];

    if ($encNr == '') {
        $encNr = $rctData['encounter_nr'];
    }
    if (count($rctData) > 0) {
        $encNr = $rctData['encounter_nr'];
        updateAnesthesiaCharts($rctData);
    }


    $groupItem = $_REQUEST['groupField'];

    checkAnesthesiaChart($encNr);

    $psql = "SELECT `ID`,`encounter_nr`,`item_description`,`1`,`2`,`3`,`4`,
            `5`,`6`,`7`, `8`,`9`,`10`,`11`,`12`,`13`,`14`,`15`,`16`,`17`,`18`,
            `19`,`20`,`21`,`22`,`23`,`24` FROM `care_ke_anesthesia2` 
            where item_group='$groupItem'";

    if ($encNr <> '') {
        $psql = $psql . " and encounter_nr=$encNr";
    }

    if ($debug)
        echo $psql;

    $request = $db->Execute($psql);
    $total = $request->RecordCount();



    echo '{
    "total":"' . $total . '","chartsItems":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {

        echo '{"ID":"' . $row['ID'] . '","encounter_nr":"' . $row['encounter_nr'] . '","item_description":"' . $row['item_description']
        . '","group":"' . $row['group'] . '","1":"' . $row[1] . '","2":"' . $row[2]
        . '","3":"' . $row[3] . '","4":"' . $row[4] . '","5":"' . $row[5]
        . '","6":"' . $row[6] . '","7":"' . $row[7] . '","8":"' . $row[8] . '","9":"' . $row[9]
        . '","10":"' . $row[10] . '","11":"' . $row[11] . '","12":"' . $row[12] . '"}';

        $counter++;

        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']}';
}

function validateCheckList($encounterNo, $form_type) {
    global $db;
    $debug = false;

    $psql = "select encounter_nr from care_ke_surgicalchecklist where encounter_nr='$encounterNo' and form_type='$form_type'";

    if ($debug)
        echo $psql;
    if ($presult = $db->Execute($psql)) {
        $row = $presult->FetchRow();
        if ($row[0] <> "") {
            return '1';
        } else {
            return '0';
        }
    } else {
        return '0';
    }
}

function checkAnesthesiaExists($strTable, $encNr) {
    global $db;
    $debug = false;

    $sql = "Select count(encounter_nr) as enCount from $strTable where encounter_nr=$encNr";

    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);
    $row = $request->FetchRow();
    if ($row[0] > 0) {
        return true;
    } else {
        return false;
    }
}

function CreateAnesthesiaForm($AnesthesiaDetails) {
    global $db;
    global $db;
    $debug = FALSE;
    $name = $_SESSION['sess_login_username'];


    $pjdate = new DateTime($AnesthesiaDetails['procedure_date']);
    $AnesthesiaDetails['procedure_date'] = $pjdate->format('Y-m-d');
    $AnesthesiaDetails['input_user'] = $name;
    $AnesthesiaDetails['create_time'] = date('H:i:s');
    $AnesthesiaDetails['pat_Encounter_nr'] = $AnesthesiaDetails['encounter_nr'];
    $AnesthesiaDetails['air_Encounter_nr'] = $AnesthesiaDetails['encounter_nr'];
    $AnesthesiaDetails['ane_Encounter_nr'] = $AnesthesiaDetails['encounter_nr'];
    $AnesthesiaDetails['mon_Encounter_nr'] = $AnesthesiaDetails['encounter_nr'];
    $AnesthesiaDetails['rem_Encounter_nr'] = $AnesthesiaDetails['encounter_nr'];

    $AnesthesiaDetails['history'] = "$name:Executed-Create new Checlist Sign_In";


    $FieldNames='';
    $FieldValues='';
    $FieldNames2='';
    $FieldValues2='';
    $FieldNames3='';
    $FieldValues3='';
    $FieldNames4='';
    $FieldValues4='';
    $FieldNames5='';
    $FieldValues5='';
    $FieldNames6='';
    $FieldValues6='';

    foreach ($AnesthesiaDetails as $key => $value) {
        $items = array("air_", "pat_", "ane_", "mon_", "rem_");
        $strField = substr($key, 0, 4);
        if (!in_array($strField, $items)) {
            $FieldNames.=$key . ', ';
            $FieldValues.='"' . $value . '", ';
        } else {
            if ($strField == "air_") {
                $FieldNames2.=substr($key, 4) . ', ';
                $FieldValues2.='"' . $value . '", ';
            } else if ($strField == "pat_") {
                $FieldNames3.=substr($key, 4) . ', ';
                $FieldValues3.='"' . $value . '", ';
            } else if ($strField == "ane_") {
                $FieldNames4.=substr($key, 4) . ', ';
                $FieldValues4.='"' . $value . '", ';
            } else if ($strField == "mon_") {
                $FieldNames5.='`' . substr($key, 4) . '`, ';
                $FieldValues5.='"' . $value . '", ';
            } else if ($strField == "rem_") {
                $FieldNames6.='`' . substr($key, 4) . '`, ';
                $FieldValues6.='"' . $value . '", ';
            }
        }
    }

    //unset($checkDetails['formStatus']);

    if (checkAnesthesiaExists('care_ke_anesthesia', $AnesthesiaDetails['encounter_nr'])) {

        $sql = 'UPDATE care_ke_anesthesia SET ';
        foreach ($AnesthesiaDetails as $key => $value) {
            $items = array("air_", "pat_", "ane_", "mon_", "rem_");
            $strField = substr($key, 0, 4);
            if (!in_array($strField, $items)) {
                $sql .= $key . '="' . $value . '", ';
            }
        }
        $sql = substr($sql, 0, -2) . ' WHERE encounter_nr="' . $AnesthesiaDetails['encounter_nr'] . '"';
    } else {
        $sql = 'INSERT INTO care_ke_anesthesia (' . substr($FieldNames, 0, -2) . ') ' .
                'VALUES (' . substr($FieldValues, 0, -2) . ') ';
    }
    if ($debug)
        echo $sql;

    if ($db->Execute($sql)) {
        $items = array("air_", "pat_", "ane_", "mon_", "rem_");
        $strField = substr($key, 0, 4);

        if (checkAnesthesiaExists('care_ke_anesairwaymanage', $AnesthesiaDetails['air_Encounter_nr'])) {
            $sql2 = 'UPDATE care_ke_anesairwaymanage SET ';
            foreach ($AnesthesiaDetails as $key => $value) {
                if ($strField == "air_") {
                    $sql2 .= $key . '="' . $value . '", ';
                }
            }
            $sql2 = substr($sql2, 0, -2) . ' WHERE encounter_nr="' . $AnesthesiaDetails['air_Encounter_nr'] . '"';
        } else {
            $sql2 = 'INSERT INTO care_ke_anesairwaymanage (' . substr($FieldNames2, 0, -2) . ') ' .
                    'VALUES (' . substr($FieldValues2, 0, -2) . ') ';
        }

        if ($debug) {
            echo $sql2;
        }
        if ($db->Execute($sql2)) {
            $results = "{success:true}";
        } else {
            $results = "{failure: true,errNo:'2'}"; // Return the error message(s)
        }

        if (checkAnesthesiaExists('care_ke_anespatientsafety', $AnesthesiaDetails['air_Encounter_nr'])) {
            $sql3 = 'UPDATE care_ke_anespatientsafety SET ';
            foreach ($AnesthesiaDetails as $key => $value) {
                $items = array("air_", "pat_", "ane_", "mon_", "rem_");
                $strField = substr($key, 0, 4);
                if ($strField == "pat_") {
                    $sql3 .= substr($key, 4) . '="' . $value . '", ';
                }
            }
            $sql3 = substr($sql3, 0, -2) . ' WHERE encounter_nr="' . $AnesthesiaDetails['air_Encounter_nr'] . '"';
        } else {
            $sql3 = 'INSERT INTO care_ke_anespatientsafety (' . substr($FieldNames3, 0, -2) . ') ' .
                    'VALUES (' . substr($FieldValues3, 0, -2) . ') ';
        }

        if ($debug) {
            echo $sql3;
        }

        if ($db->Execute($sql3)) {
            $results = "{success:true}";
        } else {
            $results = "{failure: true,errNo:'3'}"; // Return the error message(s)
        }

        if (checkAnesthesiaExists('care_ke_anestechnique', $AnesthesiaDetails['ane_Encounter_nr'])) {
            $sql4 = 'UPDATE care_ke_anestechnique SET ';
            foreach ($AnesthesiaDetails as $key => $value) {
                $items = array("air_", "pat_", "ane_", "mon_", "rem_");
                $strField = substr($key, 0, 4);
                if ($strField == "ane_") {
                    $sql4 .= substr($key, 4) . '="' . $value . '", ';
                }
            }
            $sql4 = substr($sql4, 0, -2) . ' WHERE encounter_nr="' . $AnesthesiaDetails['air_Encounter_nr'] . '"';
        } else {
            $sql4 = 'INSERT INTO care_ke_anestechnique (' . substr($FieldNames4, 0, -2) . ') ' .
                    'VALUES (' . substr($FieldValues4, 0, -2) . ') ';
        }
        if ($debug) {
            echo $sql4;
        }

        if ($db->Execute($sql4)) {
            $results = "{success:true}";
        } else {
            $results = "{failure: true,errNo:'4'}"; // Return the error message(s)
        }


        if (checkAnesthesiaExists('care_ke_anesmonitors', $AnesthesiaDetails['ane_Encounter_nr'])) {
            $sql5 = 'UPDATE care_ke_anesmonitors SET ';
            foreach ($AnesthesiaDetails as $key => $value) {
                $items = array("air_", "pat_", "ane_", "mon_", "rem_");
                $strField = substr($key, 0, 4);
                if ($strField == "mon_") {
                    $sql5 .= '`' . substr($key, 4) . '`="' . $value . '", ';
                }
            }
            $sql5 = substr($sql5, 0, -2) . ' WHERE encounter_nr="' . $AnesthesiaDetails['air_Encounter_nr'] . '"';
        } else {
            $sql5 = 'INSERT INTO care_ke_anesmonitors (' . substr($FieldNames5, 0, -2) . ') ' .
                    'VALUES (' . substr($FieldValues5, 0, -2) . ') ';
        }
        if ($debug) {
            echo $sql5;
        }
        if ($db->Execute($sql5)) {
            $results = "{success:true}";
        } else {
            $results = "{failure: true,errNo:'5'}"; // Return the error message(s)
        }


        if (checkAnesthesiaExists('care_ke_anesthesiaRemarks', $AnesthesiaDetails['rem_Encounter_nr'])) {
            $sql6 = 'UPDATE care_ke_anesthesiaRemarks SET ';
            foreach ($AnesthesiaDetails as $key => $value) {
                $items = array("air_", "pat_", "ane_", "mon_", "rem_");
                $strField = substr($key, 0, 4);
                if ($strField == "rem_") {
                    $sql6 .= '`' . substr($key, 4) . '`="' . $value . '", ';
                }
            }
            $sql6 = substr($sql6, 0, -2) . ' WHERE encounter_nr="' . $AnesthesiaDetails['rem_Encounter_nr'] . '"';
        } else {
            $sql6 = 'INSERT INTO care_ke_anesthesiaRemarks (' . substr($FieldNames6, 0, -2) . ') ' .
                    'VALUES (' . substr($FieldValues6, 0, -2) . ') ';
        }
        if ($debug) {
            echo $sql6;
        }
        if ($db->Execute($sql6)) {
            $results = "{success:true}";
        } else {
            $results = "{failure: true,errNo:'6'}"; // Return the error message(s)
        }
    } else {
        $results = "{failure: true,errNo:'0'}"; // Return the error message(s)
    }


    echo $results;
}

function CreateCheckList1($checkDetails) {
    global $db;
    global $db;
    $debug = false;
    $name = $_SESSION['sess_login_username'];

    $checkDetails['form_type'] = 'SIGN_IN';
    $pjdate = new DateTime($checkDetails['procedure_date2']);
    $checkDetails['procedure_date'] = $pjdate->format('Y-m-d');
    $checkDetails['input_user'] = $name;
    $checkDetails['create_time'] = date('H:i:s');
    $checkDetails['pid'] = $checkDetails['pid2'];
    $checkDetails['encounter_nr'] = $checkDetails['encounter_nr'];
    $FieldNames='';
    $FieldValues='';

    unset($checkDetails['procedure_date2']);
    unset($checkDetails['pid2']);
    unset($checkDetails['pname']);
//    $checkDetails[create_date] = date('Y:m:d');
    $checkDetails['history'] = "$name:Executed-Create new Checlist Sign_In";

    if (validateCheckList($checkDetails['encounter_nr'], $checkDetails['form_type']) == '1') {
        $results = "{Faulure:true,errNo:'SIGN_IN Checklist has already been entered'}";
    } else {
        foreach ($checkDetails as $key => $value) {
            $FieldNames.=$key . ', ';
            $FieldValues.='"' . $value . '", ';
        }

//unset($checkDetails['formStatus']);

        $sql = 'INSERT INTO care_ke_surgicalchecklist (' . substr($FieldNames, 0, -2) . ') ' .
                'VALUES (' . substr($FieldValues, 0, -2) . ') ';
        if ($debug)
            echo $sql;
        if ($db->Execute($sql)) {
            $results = "{success: true}"; // Return the error message(s)
        } else {
            $results = "{success: false,errNo:2}"; // Return the error message(s)
        }
    }

    echo $results;
}

function CreateCheckList2($checkDetails) {
    global $db;
    global $db;
    $debug = false;
    $name = $_SESSION['sess_login_username'];
// $checkDetails[notes] = htmlspecialchars($checkDetails[notes]);
//$checkDetails[history] = "$name Execute:Create new Booking;";

    $checkDetails['form_type'] = 'TIME_OUT';
    $pjdate = new DateTime($checkDetails['procedure_date3']);
    $checkDetails['procedure_date'] = $pjdate->format('Y-m-d');
    $checkDetails['input_user'] = $name;
    $checkDetails['create_time'] = date('H:i:s');
    $checkDetails['pid'] = $checkDetails['pid3'];
    $checkDetails['procedure_name'] = $checkDetails['procedure_name3'];
    $checkDetails['encounter_nr'] = $checkDetails['encounter_nr2'];

    unset($checkDetails['procedure_date3']);
    unset($checkDetails['pid3']);
    unset($checkDetails['pname3']);
    unset($checkDetails['procedure_name3']);
    unset($checkDetails['encounter_nr2']);
    $FieldNames='';
    $FieldValues='';
//    $checkDetails[create_date] = date('Y:m:d');
    $checkDetails['history'] = "$name:Executed-Create new Checlist Time_OUT";

    if (validateCheckList($checkDetails['encounter_nr'], $checkDetails['form_type']) == '1') {
        $results = "{Faulure:true,errNo:'TIME_OUT Checklist has already been entered'}";
    } else {

        foreach ($checkDetails as $key => $value) {
            $FieldNames.=$key . ', ';
            $FieldValues.='"' . $value . '", ';
        }

//unset($checkDetails['formStatus']);

        $sql = 'INSERT INTO care_ke_surgicalchecklist (' . substr($FieldNames, 0, -2) . ') ' .
                'VALUES (' . substr($FieldValues, 0, -2) . ') ';
        if ($debug)
            echo $sql;
        if ($db->Execute($sql)) {
            $results = "{success: true}"; // Return the error message(s)
        } else {
            $results = "{success: false,errNo:2}"; // Return the error message(s)
        }
    }

    echo $results;
}

function CreateCheckList3($checkDetails) {
    global $db;
    global $db;
    $debug = false;
    $name = $_SESSION['sess_login_username'];
// $checkDetails[notes] = htmlspecialchars($checkDetails[notes]);
//$checkDetails[history] = "$name Execute:Create new Booking;";

    $checkDetails['form_type'] = 'SIGN_OUT';
    $pjdate = new DateTime($checkDetails['procedure_date4']);
    $checkDetails['procedure_date'] = $pjdate->format('Y-m-d');
    $checkDetails['input_user'] = $name;
    $checkDetails['create_time'] = date('H:i:s');
    $checkDetails['pid'] = $checkDetails['pid4'];
    $checkDetails['procedure_name'] = $checkDetails['procedure_name4'];
    $checkDetails['encounter_nr'] = $checkDetails['encounter_nr4'];

    unset($checkDetails['procedure_date4']);
    unset($checkDetails['pid4']);
    unset($checkDetails['pname4']);
    unset($checkDetails['procedure_name4']);
    unset($checkDetails['encounter_nr4']);
    $FieldNames='';
    $FieldValues='';
//    $checkDetails[create_date] = date('Y:m:d');
    $checkDetails['history'] = "$name:Executed-Create new Checlist SIGN_OUT";


    if (validateCheckList($checkDetails['encounter_nr'], $checkDetails['form_type']) == '1') {
        $results = "{Faulure:true,errNo:'SIGN_OUT Checklist has already been entered'}";
    } else {

        foreach ($checkDetails as $key => $value) {
            $FieldNames.=$key . ', ';
            $FieldValues.='"' . $value . '", ';
        }

//unset($checkDetails['formStatus']);

        $sql = 'INSERT INTO care_ke_surgicalchecklist (' . substr($FieldNames, 0, -2) . ') ' .
                'VALUES (' . substr($FieldValues, 0, -2) . ') ';
        if ($debug)
            echo $sql;
        if ($db->Execute($sql)) {
            $results = "{success: true}"; // Return the error message(s)
        } else {
            $results = "{success: false,errNo:2}"; // Return the error message(s)
        }
    }

    echo $results;
}

function getStatusList() {
    global $db;
    $debug = false;

    $sql = "select value from care_config_global where type='procStatus'";
    if ($debug)
        echo $sql;

    $result = $db->Execute($sql);
    $row = $result->FetchRow();

    $str = explode(",", $row[0]);
    $total = count($str);
    $str1 = '{"procStatus":[';
    for ($i = 0; $i <= $total; $i++) {
        $str1.='{"sname":"' . $str[$i] . '"}';

        if ($i < $total)
            $str1.= ',';
    }
    $str1.= "]}";

    echo $str1;
}

function validateClass($ID) {
    global $db;
    $debug = true;

    $sql = "SELECT count(id) from care_ke_procedureclasses where ID='$ID'";

    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);
    $row = $request->FetchRow();

    if ($row[0] == 1) {
        return '1';
    } else {
        return '0';
    }
}

function writeprocedureclass() {
    global $db;
    $debug = true;

    $procs = $_REQUEST['data'];
    $procData = json_decode($procs, TRUE);

    foreach ($procData as $value) {
        $ID = $value['ID'];
        $proc_class = $value['proc_class'];
        $class_value = $value['class_value'];
        $cost = $value['cost'];

        echo "returned $ID - $proc_class -  $class_value -  $cost";
        if (validateClass($ID) == '1') {
            $sql = "UPDATE care_ke_procedureclasses set proc_class='$proc_class', class_value='$class_value',cost='$cost'";
        } else {
            $sql = "INSERT INTO care_ke_procedureclasses(ID,proc_class,class_value,cost)
Values('$ID','$proc_class','$class_value','$cost')";
        }
        if ($debug)
            echo $sql . '<br>';

        if ($db->Execute($sql)) {
            echo '{success:true,errNo:"Successfully saved data"}';
        } else {
            echo '{failure:true,errNo:"Data could not be saved-' . $sql . '"}';
        }
    }
}

function getProcedureClassList() {
    global $db;
    $debug = false;

    $sql = "select ID,proc_class,class_value,cost from care_ke_procedureclasses";
    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);

    $total = $request->RecordCount();

    echo '{
"total":"' . $total . '","procedureClassLists":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {
        echo '{"proc_class":"' . $row['proc_class'] . '","class_value":"' . $row['class_value'] . '","cost":"' . $row['cost'] . '","ID":"' . $row['ID'] . '"}';

        $counter++;

        if ($counter < $total) {
            echo ",";
        }
    }
    echo ']}';
}

function getSalesItems($start, $limit) {
    global $db;
    $debug = false;

    $sql = "SELECT  d.partcode,d.`item_description`,d.`unit_price`,l.`quantity`,l.`loccode` FROM care_tz_drugsandservices d 
INNER JOIN care_ke_locstock l
ON d.`partcode`=l.`stockid` WHERE d.`purchasing_class` LIKE 'drug%' AND l.`loccode`='main'";

    if ($start <> '' && $limit <> '')
        $sql.=" limit $start,$limit";

    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);

    $sqlTotal = "SELECT  COUNT(d.partcode) AS partcode FROM care_tz_drugsandservices d 
INNER JOIN care_ke_locstock l
ON d.`partcode`=l.`stockid` WHERE d.`purchasing_class` LIKE 'drug%' AND l.`loccode`='main'";

    $request2 = $db->Execute($sqlTotal);

    $row = $request2->FetchRow();
    $total = $row[0];

    echo '{
"total":"' . $total . '","salesItems":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {
        if ($row['unit_price'] <> '') {
            $price = $row['unit_price'];
        } else {
            $price = 0;
        }

        if ($row['quantity'] <> '') {
            $qty = $row['quantity'];
        } else {
            $qty = 0;
        }
        echo '{"itemcode":"' . $row['partcode'] . '","description":"' . $row['item_description'] . '","qty":' . $qty
        . ',"loccode":"' . $row['loccode'] . '","price":' . $price . '}';



        if ($counter < $total) {
            echo ",";
        }
        $counter++;
    }
    echo ']}';
//    echo "<br><br><br>".$counter;
}

function getServicesList($start, $limit) {
    global $db;
   $debug = false;
    $sParams=$_REQUEST['searchParam'];

    $sql = "SELECT partcode,item_description,item_full_description,`unit_measure`,unit_price,unit_price_1,selling_price,
            purchasing_class AS category,item_status FROM care_tz_drugsandservices
             where purchasing_class NOT IN ('drug_list','medical-supplies','SURGICAL SUPPLIES','supplies','Repairs')";

    if($sParams){
        $sql=$sql." where item_description like '%$sParams%'";
    }

     $sql=$sql." order by item_description asc ";

     if($start<>''and $limit<>''){
        $sql=$sql." LIMIT $start,$limit";
     }
     else{
        $sql=$sql." LIMIT 1,300";
     }

    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);

    $sql2="select count(partcode) FROM care_tz_drugsandservices where purchasing_class NOT IN ('drug_list','medical-supplies','SURGICAL SUPPLIES','supplies','Repairs')";
    $request2 = $db->Execute($sql2);
    $row=$request2->FetchRow();
    $total=$row[0];

    echo '{"total":"'.$total.'","services":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {
        $desc=  escapeJsonString($row['item_description']);
        $category=escapeJsonString($row['category']);
        $partCode=  escapeJsonString($row['partcode']);
        echo '{"partcode":"' . $partCode . '","item_description":"' . $desc . '","item_full_description":"' . $desc
        . '","unit_measure":"' . $row['unit_measure'] . '","unit_price":"' . $row['unit_price'] . '","selling_price":"' . $row['selling_price']
        . '","purchasing_class":"' . $row['purchasing_class'] . '","category":"' . $category
        . '","item_status":"' . $row['item_status'] .'"}';

        $counter++;
        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']}';
//    echo "<br><br><br>".$counter;
}

function getItemsList($store,$searchParam,$accDB,$start, $limit) {
    global $db;
   $debug = false;
    $category = $_REQUEST['category'];
    $sParams=$_REQUEST['sParams'];

    $sql = "SELECT d.partcode,item_description,item_full_description,`unit_measure`,unit_price,unit_price_1,selling_price,
            purchasing_class, c.`item_Cat` AS category,d.item_status,s.`reorderlevel`,`minimum`,maximum,s.quantity,s.`loccode`
            FROM care_tz_drugsandservices d LEFT JOIN  care_tz_itemscat c ON d.`category`=c.`catID`
            LEFT JOIN care_ke_locstock s ON d.`partcode`=s.stockid where partcode<>''";

    if($store<>''){
        $sql.=" AND s.loccode='$store'";
    }

    if($sParams){
        $sql=$sql." and purchasing_class IN ($sParams) ";
    }
     $sql=$sql." order by item_description asc";

    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);
    $total=$request->RecordCount();

    echo '[';
    $counter = 0;
    while ($row = $request->FetchRow()) {
        $desc=  escapeJsonString($row['item_description']);
        $category=escapeJsonString($row['category']);
        $partCode=  escapeJsonString($row['partcode']);

        if($row['quantity']<1){$qty=0;}else{$qty=$row['quantity'];}
        $total=$qty*$row['unit_price'];
        echo '{"partcode":"' . $partCode . '","item_description":"' . $desc . '","item_full_description":"' . $desc
        . '","unit_measure":"' . $row['unit_measure'] . '","unit_price":"' . $row['unit_price'] . '","selling_price":"' . $row['selling_price']
        . '","purchasing_class":"' . $row['purchasing_class'] . '","category":"' . $category
        . '","item_status":"' . $row['item_status'] . '","reorderlevel":"' . $row['reorderlevel']
            . '","minimum":"' . $row['minimum'] . '","maximum":"' . $row['maximum']
            . '","qty":"' . $qty . '","Total":"' . $total.'"}';

        $counter++;
        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']';
//    echo "<br><br><br>".$counter;
}

function getUnitsofMeasure($start, $limit) {
    global $db;
    $debug = false;

    $sql = "select id,name from care_unit_measurement";
    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);


    $total = $request->RecordCount();

    echo '{
"total":"' . $total . '","unitsMeasure":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {

        echo '{"ID":"' . $row['id'] . '","name":"' . $row['name'] . '"}';


        if ($counter <> $total) {
            echo ",";
        }
        $counter++;
    }

    echo ']}';
}

function getStaff($start, $limit) {
    global $db;
    $debug = false;

    $sql = "select ID,staff_name,JobTitle, Department,EnterRequest,EnterResults,
                  ViewResults,TakeSamples,ReceiveSamples,VerifyResults,ApproveResults  from care_ke_staff";


    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);

    $total = $request->RecordCount();

    echo '[';
    $counter = 0;
    while ($row = $request->FetchRow()) {

        echo '{"ID":"' . $row['ID'] .'","staff_name":"'. $row['staff_name'] .'","JobTitle":"'. $row['JobTitle']
            .'","Department":"'. $row['Department'] .'","EnterRequest":"'. $row['EnterRequest'] .'","EnterResults":"'. $row['EnterResults']
            .'","ViewResults":"'. $row['ViewResults'] .'","TakeSamples":"'. $row['TakeSamples'] .'","ReceiveSamples":"'. $row['ReceiveSamples']
            .'","VerifyResults":"'. $row['VerifyResults'] .'","ApproveResults":"'. $row['ApproveResults'].'"}';

        $counter++;
        if ($counter <> $total) {
            echo ",";
        }
    }

    echo ']';
}

function getTheatreRooms($start, $limit) {
    global $db;
    $debug = false;

    $sql = "select room_no,room_name from care_ke_TheatreRooms";
    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);

    $total = $request->RecordCount();

    echo '{
"total":"' . $total . '","theatreRooms":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {

        echo '{"room_no":"' . $row['room_no'] . '","room_name":"' . $row['room_name'] . '"}';

        $counter++;
        if ($counter <> $total) {
            echo ",";
        }
    }

    echo ']}';
}

function getTheatreList($start, $limit) {
    global $db;
    $debug = false;

    $sql = "SELECT BookingNo,`procedure_date`,`surgeon`,`asst_surgeon`,`pid`, `encounter_nr`, `pnames`,
                `diagnosis`,`procedure_type`,`procedure_class`,`class_code`, `op_starttime`,`op_endtime`,
                `scrub_nurse`,`op_room`,`status`,`notes`,`formStatus`,`selian_pid`,`sex`,`allergies`,`date_birth`
            FROM `care_ke_procedures`";
    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);

    $total = $request->RecordCount();

    echo '{
"total":"' . $total . '","theatrelist":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {
        $notes = html_entity_decode($row['notes']);
        $notes2 = html_entity_decode($notes);
        echo '{"BookingNo":"' . $row['BookingNo'] . '","pid":"' . $row['pid'] . '","selian_pid":"' . $row['selian_pid'] . '","procedure_date":"' . $row['procedure_date']
        . '","surgeon":"' . $row['surgeon'] . '","asst_surgeon":"' . $row['asst_surgeon'] . '","encounter_nr":"' . $row['encounter_nr']
        . '","pnames":"' . $row['pnames'] . '","diagnosis":"' . $row['diagnosis'] . '","procedure_type":"' . $row['procedure_type']
        . '","procedure_class":"' . $row['procedure_class'] . '","class_code":"' . $row['class_code'] . '","op_starttime":"' . $row['op_starttime']
        . '","op_endtime":"' . $row['op_endtime'] . '","scrub_nurse":"' . $row['scrub_nurse'] . '","op_room":"' . $row['op_room']
        . '","notes":"' . str_replace('"', "'", html_entity_decode($notes)) . '","formStatus":"' . $row['formStatus'] . '","sex":"' . $row['sex']
        . '","allergies":"' . $row['allergies'] . '","date_birth":"' . $row['date_birth'] . '","status":"' . $row['status'] . '"}';

        $counter++;
        if ($counter <> $total) {
            echo ",";
        }
    }

    echo ']}';
}

function getDiagnosisList($icddesc) {
    global $db;
    $debug = false;

    $sql = "SELECT diagnosis_code,description FROM care_icd10_en";

    if ($icddesc) {
        $sql.=" where description like '%$icddesc%'";
    }

    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);

    $total = $request->RecordCount();

    echo '{
"total":"' . $total . '","diagnosisList":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {

        echo '{"diagnosis_code":"' . $row['diagnosis_code'] . '","description":"' . trim($row['description']) . '"}';

        $counter++;
        if ($counter <> $total) {
            echo ",";
        }
    }

    echo ']}';
}


function getOpdPatients($dept_obj,$ward_obj,$items_obj,$person){
    global $db;
    $debug=false;
    $searchParam=$_REQUEST['searchParam'];

    $sql = "SELECT d.`status`,c.`pid`,CONCAT(c.`name_first`,' ',c.`name_2`,' ', c.`name_last`) AS pnames, IF(t.`name`<>'',t.`name`,'CASH PAYMENT') AS PaymentMode,c.`date_birth`, c.`sex`,e.`name_formal`,
              d.`encounter_date`,d.encounter_nr,d.encounter_time,d.encounter_class_nr,d.current_ward_nr,d.current_dept_nr,d.consultation_fee
            FROM care_person c
            INNER JOIN care_encounter d ON c.pid=d.pid 
            INNER JOIN care_department e ON e.nr=d.current_dept_nr
            LEFT JOIN care_tz_company t ON c.`insurance_ID`=t.`id`
            WHERE e.`type`=1 AND d.encounter_class_nr=2 and d.encounter_date='".date('Y-m-d')."'";

    if($searchParam<>""){
        $sql.=" and c.name_first like '%$searchParam%' or c.name_2 like '%$searchParam%' 
                    or c.name_last like '%$searchParam%' or c.pid like '%$searchParam%' and d.`is_discharged`=0";
    }

    $sql.=" order by d.encounter_time desc";

    if($debug) echo $sql;

    $result=$db->Execute($sql);
    $numRows=$result->RecordCount();
    echo '{
    "OutPatients":[';
    $counter=0;
    while ($row = $result->FetchRow()) {

        if($row['encounter_class_nr']==1){
            $encClass='Inpatient';
            $ward=$ward_obj->getWardInfo($row['current_ward_nr']);
            $dept=$ward['name'];
        }elseif($row['encounter_class_nr']==2){
            $encClass='Outpatient';
            $dept=$dept_obj->FormalName($row['current_dept_nr']);
        }else{
            $encClass='';
            $dept='';
        }

        if($row[5]=='f'){
            $gender="Female";
        }else{
            $gender="Male";
        }

        $age=exactAge($row['date_birth']);

        if($row['status']==''){
            $status='Active';
        }else{
            $status='Discharged';
        }

        $payMode=preg_replace('/[^a-zA-Z0-9_ -]/s', '',$row['PaymentMode']);
        $consultationFee=$items_obj->getItemDetails($row['consultation_fee']);

        echo '{"Status":"'. $status.'","Pid":"'. $row['pid'].'","Names":"'. $row['pnames'].'","PaymentMode":"'. $payMode.'","EncounterTime":"'. $row['encounter_time'].'","EncounterDate":"'. $row['encounter_date'] . '","EncounterClass":"'. $encClass. '","Department":"'. $dept . '","ConsultationFee":"'. $consultationFee['item_description']
            .'","Gender":"'. $gender .'","Clinic":"'. $row['name_formal'].'","EncounterNo":"'. $row['encounter_nr'].'","DateOfBirth":"'. $row['date_birth'].'","Age":"'. trim($age).'"}';
        if ($counter<>$numRows){
            echo ",";
        }
        $counter++;
    }
    echo ']}';
//1146
}

function getPatientDetails($searchParam,$person,$dept_obj,$ward_obj,$enc_obj,$items_obj,$start,$limit) {
    global $db;
    $debug = false;

    $today=date("Y-m-d");

    $sql = "SELECT p.`pid`,selian_pid,name_first,name_last,name_2 ,date_birth,sex,citizenship,phone_1_nr,date_reg FROM care_person p WHERE p.pid<>''";

   if(isset($searchParam) && $searchParam<>'') {
       if (is_numeric($searchParam)) {
           $sql .= "	AND pid=$searchParam or selian_pid='$searchParam' or nat_id_nr='$searchParam' or phone_1_nr='$searchParam'";
       } else {
           $cbuffer = explode(' ', $searchParam);

           for ($x = 0; $x < sizeof($cbuffer); $x++) {
               $cbuffer[$x] = trim($cbuffer[$x]);
           }

           if(sizeof($cbuffer) == 1){
               $sql.=" AND name_first like'%$searchParam%' or name_last like'%$searchParam%' or name_2 like'%$searchParam%'
                or citizenship like '%$searchParam%'";
           }elseif (sizeof($cbuffer) == 2) {
               $sql .= " AND (name_first like '%$cbuffer[0]%' AND name_2 like '%$cbuffer[1]%')";
           } elseif (sizeof($cbuffer > 2)) {
               $sql .= " AND (name_first like '%$cbuffer[0]%' AND name_2 like '%$cbuffer[1]%' AND name_2 LIKE '%$cbuffer[3]%')";
           }
       }
   }else{
        $sql.=" AND date_reg>'$today 00:00:00'";
   }

    if (isset($start) && isset($limit)) {
        $sql.=" limit $start,$limit";
    }

    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);

    $sql2="select pid from care_person where pid<>'' and pid='$searchParam' or selian_pid='$searchParam' or name_first like'%$searchParam%' 
       or name_last like'%$searchParam%' or name_2    like'%$searchParam%' or phone_1_nr='$searchParam' or citizenship like '%$searchParam%'";
   // echo $sql2;
     $request2=$db->Execute($sql2);
    $total = $request2->RecordCount();

    echo '{
"total":"' . $total . '","patientInfo":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {
        $current_encounter = $person->CurrentEncounter($row['pid']);
        if ($row['sex'] == 'f') {
            $sex = 'FEMALE';
        } else if ($row['sex'] == 'm') {
            $sex = 'MALE';
        }

        if($current_encounter['encounter_class_nr']==1){
            $encClass='Inpatient';
            $ward=$ward_obj->getWardInfo($current_encounter['current_ward_nr']);
            $dept=$ward['name'];
        }elseif($current_encounter['encounter_class_nr']==2){
            $encClass='Outpatient';
            $dept=$dept_obj->FormalName($current_encounter['current_dept_nr']);
        }else{
            $encClass='';
            $dept='';
        }


        $financeClass=$enc_obj->getFinanceClassByName($current_encounter['financial_class']);
        $consultationFee=$items_obj->getItemDetails($current_encounter['consultation_fee']);




        $pnames=$row['name_first']." ".$row['name_last']." ". $row['name_2'];
        $encounterDate=$current_encounter['encounter_date'] .' '. $current_encounter['encounter_time'];

        echo '{"Pid":"' . $row['pid'] .'","FileNumber":"' . $row['selian_pid'] . '","FirstName":"' . $row['name_first']
            . '","LastName":"' . $row['name_2'] . '","SurName":"' . $row['name_last']. '","DateOfBirth":"' . $row['date_birth']
        . '","Gender":"' . $sex . '","pnames":"'. $pnames . '","Village":"'. $row['citizenship'] . '","Phone":"'. $row['phone_1_nr']
            . '","RegistrationDate":"'. $row['date_reg']. '","CurrentEncounter":"'. $current_encounter['encounter_nr']
            . '","EncounterClass":"'. $encClass. '","Department":"'. $dept. '","PaymentMode":"'.$financeClass
            . '","ConsultationFee":"'. $consultationFee['item_description']
            . '","EncounterDate":"'. $encounterDate. '"}';

        $counter++;
        if ($counter <> $total) {
            echo ",";
        }
    }

    echo ']}';
}


function getProcedureClass($start, $limit) {
    global $db;
    $debug = false;

    $sql = "select DISTINCT proc_class from care_ke_procedureclasses";
    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);

    $total = $request->RecordCount();

    echo '{
"total":"' . $total . '","procedureClass":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {

        echo '{"ID":"' . $row['proc_class'] . '","proc_class":"' . $row['proc_class'] . '"}';

        $counter++;
        if ($counter <> $total) {
            echo ",";
        }
    }

    echo ']}';
}

function getSurgicalCheckLists($start, $limit) {
    global $db;
    $debug = false;

    $sql = "SELECT `ID`,`form_type`,`pid`,`encounter_nr`,`procedure_date`,`Time`,`Procedure_name`,d.`item_description`,`identity`,`site`,`procedure_check`,`consent`,`age`,
`ID_bracelet`,`site_marked`,`bathed`,`scrubbed`, `allergy`,`allergy_name`,`blood_available`,`xray`,`HB`,`HCT`,`weight`,`pre_anaesthesia_eva`,
`solids_from`,`liquids_from`,`breastfeeding_from`,`Medication_rs`,`antibiotic_check`,`antibiotic_given`,`BP`,`HR`,
`RR`,`temp`,`O2_sat`,`other_vitals`,`PT_Voided`,`removal_extras`, `members_confirm`, `new_member`,`antibiotic_prophy_60`,
`antibiotic_reason`, `surgeon_reviews`,`anaesthesia_review`,`nursing_review`,`instruments`,`throat_pack`, `speciment_label`,
`equipment_problems`,`final_review`,`checklist_user`
FROM
`care_ke_surgicalchecklist` c  LEFT JOIN care_tz_drugsandservices d ON c.`Procedure_name`=d.`partcode`
";
    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);

    $total = $request->RecordCount();

    echo '{
"total":"' . $total . '","surgChecklists":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {

        echo '{"form_type":"' . $row['form_type'] . '","pid":"' . $row['pid'] . '","encounter_nr":"' . $row['encounter_nr']
        . '","procedure_date":"' . $row['procedure_date'] . '","procedure_ID":"' . $row['Procedure_name'] . '","procedure_name":"' . $row['item_description']
        . '","identity":"' . $row['identity'] . '","site":"' . $row['site'] . '","checklist_user":"' . $row['checklist_user']
        . '","procedure_check":"' . $row['procedure_check'] . '","consent":"' . $row['consent'] . '","age":"' . $row['age']
        . '","ID_bracelet":"' . $row['ID_bracelet'] . '","site_marked":"' . $row['site_marked'] . '","bathed":"' . $row['bathed']
        . '","scrubbed":"' . $row['scrubbed'] . '","allergy":"' . $row['allergy'] . '","allergy_name":"' . $row['allergy_name']
        . '","blood_available":"' . $row['blood_available'] . '","xray":"' . $row['xray'] . '","HB":"' . $row['HB']
        . '","HCT":"' . $row['HCT'] . '","weight":"' . $row['weight'] . '","pre_anaesthesia_eva":"' . $row['pre_anaesthesia_eva']
        . '","solids_from":"' . $row['solids_from'] . '","liquids_from":"' . $row['liquids_from'] . '","breastfeeding_from":"' . $row['breastfeeding_from']
        . '","Medication_rs":"' . $row['Medication_rs'] . '","antibiotic_check":"' . $row['antibiotic_check'] . '","antibiotic_given":"' . $row['antibiotic_given']
        . '","BP":"' . $row['BP'] . '","HR":"' . $row['HR'] . '","RR":"' . $row['RR']
        . '","temp":"' . $row['temp'] . '","O2_sat":"' . $row['O2_sat'] . '","other_vitals":"' . $row['other_vitals']
        . '","PT_Voided":"' . $row['PT_Voided'] . '","removal_extras":"' . $row['removal_extras'] . '","members_confirm":"' . $row['members_confirm']
        . '","new_member":"' . $row['new_member'] . '","antibiotic_prophy_60":"' . $row['antibiotic_prophy_60'] . '","antibiotic_reason":"' . $row['antibiotic_reason']
        . '","surgeon_reviews":"' . $row['surgeon_reviews'] . '","anaesthesia_review":"' . $row['anaesthesia_review'] . '","nursing_review":"' . $row['nursing_review']
        . '","instruments":"' . $row['instruments'] . '","throat_pack":"' . $row['throat_pack'] . '","speciment_label":"' . $row['speciment_label']
        . '","equipment_problems":"' . $row['equipment_problems'] . '","final_review":"' . $row['final_review'] . '"}';

        $counter++;
        if ($counter <> $total) {
            echo ",";
        }
    }

    echo ']}';
}

function getClassCodes($start, $limit) {
    global $db;
    $debug = false;

    $sql = "select distinct class_value from care_ke_procedureclasses";
    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);

    $total = $request->RecordCount();

    echo '{
"total":"' . $total . '","classCodes":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {

        echo '{"ID":"' . $row['class_value'] . '","class_value":"' . $row['class_value'] . '"}';

        $counter++;
        if ($counter <> $total) {
            echo ",";
        }
    }

    echo ']}';
}

function getItemsCategory($start, $limit) {
    global $db;
    $debug = false;

    $sql = "select ID,catName from care_tz_categories order by catName asc";
    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);

    $sqlTotal = "select count(ID) as ccount from care_tz_categories";

    $request2 = $db->Execute($sqlTotal);

    $row2 = $request2->FetchRow();
    $total = $row2[0];

    echo '{
"total":"' . $total . '","itemsCategory":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {

        echo '{"ID":"' . $row['ID'] . '","CatName":"' . $row['catName'] . '"}';

        $counter++;
        if ($counter <> $total) {
            echo ",";
        }
    }

    echo ']}';
}

function getItemsSubCategory($start, $limit) {
    global $db;
    $debug = false;
//    $category = $request[query];

    $sql = "select catID,item_cat from care_tz_itemscat ";

//    if(isset($category)){
//        $sql.=" where item_cat= like '$category%'";
//    }

    $sql.=" order by item_cat asc";

    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);

    $sqlTotal = "select count(catID) as ccount from care_tz_itemscat";

    $request2 = $db->Execute($sqlTotal);

    $row2 = $request2->FetchRow();
    $total = $row2[0];

    echo '{
"total":"' . $total . '","itemsSubCategory":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {

        echo '{"catID":"' . $row['catID'] . '","item_cat":"' . trim($row['item_cat']) . '"}';

        $counter++;
        if ($counter <> $total) {
            echo ",";
        }
    }

    echo ']}';
}


function getDepartments() {
    global $db;
    $debug = false;

    $mainStore=$_REQUEST['mainStore'];

    $sql = "SELECT st_id,st_name,store,mainStore FROM care_ke_stlocation";

//    if(isset($mainStore) && $mainStore==1){
//        $sql.=" AND mainStore='$mainStore'";
//    }

    $sql=$sql." Order by st_name ASC";

    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);
    $total = $request->RecordCount();

    echo '[';
    $counter = 0;
    while ($row = $request->FetchRow()) {
        echo '{"ID":"' . $row['st_id'] . '","Description":"' . $row['st_name'] . '","store":"' . $row['store'] . '","mainStore":"' . $row['mainStore'] . '"}';

        if ($counter <> $total) {
            echo ",";
        }
        $counter++;
    }

    echo ']';
}

function getStoreLocations($start, $limit) {
    global $db;
    $debug = false;

    $mainStore=$_REQUEST['mainStore'];

    $sql = "SELECT st_id,st_name,store,mainStore FROM care_ke_stlocation where store=1";

//    if(isset($mainStore) && $mainStore==1){
//        $sql.=" AND mainStore='$mainStore'";
//    }

    $sql=$sql." Order by st_name ASC";

    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);
    $total = $request->RecordCount();

    echo '[';
    $counter = 0;
    while ($row = $request->FetchRow()) {
        echo '{"ID":"' . $row['st_id'] . '","Description":"' . $row['st_name'] . '","store":"' . $row['store'] . '","mainStore":"' . $row['mainStore'] . '"}';

        if ($counter <> $total) {
            echo ",";
        }
        $counter++;
    }

    echo ']';
}


function getStockLevels($start, $limit,$partcode) {
    global $db;
    $debug = false;

    $sql = "SELECT l.ID,stockid,d.item_description,loccode,quantity,l.reorderlevel,d.category FROM care_ke_locstock l
            LEFT JOIN care_tz_drugsandservices d on l.stockid=d.partcode";
    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);
    $total=$request->RecordCount();

    echo '[';
    $counter = 0;
    while ($row = $request->FetchRow()) {
        $catID=(isset($row['category'])?$row['category']:'Non');
        $desc=  preg_replace('/[^a-zA-Z0-9_ -]/s', '',$row['item_description']);
        echo '{"partcode":"' . $row['stockid']  . '","item_description":"' . $desc
            . '","Store":"' . $row['loccode'] . '","Quantity":"' . $row['quantity']
            . '","ReorderLevel":"' . $row['reorderlevel'] .'","ID":"' . $row['ID']
            . '","Category":"'.$catID.'"}';

        if ($counter <> $total) {
            echo ",";
        }
        $counter++;
    }

    echo ']';
}

function getItemLocations($start, $limit) {
    global $db;
    $debug = false;
    $partcode = $_REQUEST['partcode'];

    $sql = "SELECT stockid AS itemcode,loccode,quantity FROM care_ke_locstock where stockid='$partcode'";
    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);

    $sqlTotal = "select count(stockid) as ccount FROM care_ke_locstock where stockid='$partcode'";

    $request2 = $db->Execute($sqlTotal);

    $row2 = $request2->FetchRow();
    $total = $row2[0];

    echo '{
"total":"' . $total . '","itemLocations":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {

        echo '{"stockid":"' . $row['itemcode'] . '","loccode":"' . $row['loccode'] . '","quantity":"' . $row['quantity'] . '"}';

        if ($counter <> $total) {
            echo ",";
        }
        $counter++;
    }

    echo ']}';
}

function checkStockid($partcode) {
    global $db;
    $psql = 'select count(item_number) as pcount from care_tz_drugsandservices where partcode="' . $partcode . '"';
    $presult = $db->Execute($psql);
    $row = $presult->FetchRow();
    return $row[0];
}

function validateBooking($encounterNo) {
    global $db;
    $debug = false;

    $psql = "select encounter_nr from care_ke_procedures where encounter_nr=$encounterNo";

    if ($debug)
        echo $psql;
    if ($presult = $db->Execute($psql)) {
        $row = $presult->FetchRow();
        if ($row[0] <> "") {
            return '1';
        } else {
            return '0';
        }
    } else {
        return '0';
    }
}

function updateBooking($bookingDetails) {
    global $db;
    $debug = false;


    $name = $_SESSION['sess_login_username'];
    $bookingDetails['notes'] = htmlspecialchars($bookingDetails['notes']);
    $bookingDetails['history'] = "$name Execute:Update new Booking;";

    $pjdate = new DateTime($bookingDetails['procedure_date']);
    $bookingDetails['procedure_date'] = $pjdate->format('Y-m-d');



    $sql = 'UPDATE care_ke_Procedures SET ';
    foreach ($bookingDetails as $key => $value) {
        $sql .= $key . '="' . $value . '", ';
    }
    $sql = substr($sql, 0, -2) . ' WHERE BookingNo="' . $bookingDetails['BookingNo'] . '"';

    if ($debug)
        echo $sql;

    if ($db->Execute($sql)) {
        $results = '{success: true }';
    } else {
        $results = "{failure: true,errNo:'Could no Update booking record for patient:$bookingDetails[pnames], Please check your values'}"; // Return the error message(s)
    }



    echo $results;
}

function createBooking($bookingDetails) {
    global $db;
    $debug = false;

    if ($bookingDetails['encounter_nr'] == '') {
        $results = "{failure:true,errNo:'No encounter has been created for the patient, <br> Records Department need to record the patients Visit in the system'}";
    } else {

        if (validateBooking($bookingDetails['encounter_nr']) == '1') {
            $results = "{failure:true,errNo:'The patient has been booked already'}";
        } else {
            $name = $_SESSION['sess_login_username'];
            $bookingDetails['notes'] = htmlspecialchars($bookingDetails['notes']);
            $bookingDetails['history'] = "$name Execute:Create new Booking;";

            $pjdate = new DateTime($bookingDetails['procedure_date']);
            $bookingDetails['procedure_date'] = $pjdate->format('Y-m-d');
            $FieldNames='';
            $FieldValues='';

            unset($bookingDetails['formStatus']);
            unset($bookingDetails['BookingNo']);

            foreach ($bookingDetails as $key => $value) {
                $FieldNames.=$key . ', ';
                $FieldValues.='"' . $value . '", ';
            }

            $sql = 'INSERT INTO care_ke_Procedures (' . substr($FieldNames, 0, -2) . ') ' .
                    'VALUES (' . substr($FieldValues, 0, -2) . ') ';
            if ($debug)
                echo $sql;
            if ($db->Execute($sql)) {
                $results = "{success: true}"; // Return the error message(s)
            } else {
                $results = "{success: false,errNo:'2'}}"; // Return the error message(s)
            }
        }
    }
    echo $results;
}

function InsertItem($partcode, $item_description, $item_full_description, $unit_price, $purchasing_class, $category, $itemStatus, $sellingPrice, $maximum, $minimum, $reorder, $unitMeasure) {
    global $db;
    $debug = false;

    $stid = checkStockid($partcode);

    if ($stid > 0) {
        echo "{'failure':'true','errNo':'1','partcode':'$partcode'}";
    } else {
        $sql = 'insert into care_tz_drugsandservices(item_number, 
partcode, item_description, item_full_description, unit_price, purchasing_class,category,
reorder,minimum,maximum,unit_measure,selling_price,item_status)
values("' . $category . '", "' . $partcode . '", "' . $item_description . '", "' . $item_full_description . '", 
"' . $unit_price . '", "' . $purchasing_class . '","' . $category . '","' . $reorder . '","' . $minimum . '","' . $maximum . '","' . $unitMeasure
                . '","' . $sellingPrice . '","' . $itemStatus . '")';
        if ($debug)
            echo $sql;
        if ($db->Execute($sql)) {
            echo '{success:true}';
        } else {
            echo "{'failure':'true','partcode':'$partcode'}";
        }
    }
}

function updateItem($partcode, $item_description, $item_full_description, $unit_price, $purchasing_class, $category, $itemStatus, $sellingPrice, $maximum, $minimum, $reorder, $unitMeasure) {
    global $db;
    $debug = false;
    $sql = 'update care_tz_drugsandservices set item_number = "' . $partcode . '" , item_description = "' . $item_description . '" ,
item_full_description = "' . $item_full_description . '" ,unit_price = "' . $unit_price . '" ,
purchasing_class ="' . $purchasing_class . '",category="' . $category
            . '",reorder="' . $reorder . '",minimum="' . $minimum . '",maximum="' . $maximum . '",unit_measure="' . $unitMeasure
            . '",selling_price="' . $sellingPrice . '",item_status="' . $itemStatus . '" where partcode ="' . $partcode . '"';

    if ($debug)
        echo $sql;
    if ($db->Execute($sql)) {
        echo '{success:true}';
    } else {
        echo "{'failure':'true','partcode':'$partcode'}";
    }
}

function transmitWeberp($partcode,$catID,$item_description,$item_full_description,$units,$unit_price,$stid) {

    $billdata['stockid'] = $partcode;
    $billdata['categoryid'] = $catID;
    $billdata['description'] = $item_description;
    $billdata['longdescription'] = $item_full_description;
    $billdata['units'] = $units;
    $billdata['mbflag'] = 'B';
    $billdata['lastcurcostdate'] = date('Y-m-d');
    $billdata['actualcost'] = $unit_price;
    $billdata['lastcost'] = $unit_price;

    if ($weberp_obj = new_weberp()) {
        if ($stid <> "") {
            $weberp_obj->modify_stock_item_in_webERP($billdata);
            $results = '{success: true }';
        } else {
            $weberp_obj->create_stock_item_in_webERP($billdata);
            $results = '{success: true }';
        }
    } else {

        $results = '{success: false }';
    }
}

function stockAdjust($item_number) {
    global $db;
    $item_number = $_REQUEST['item_number'];
    $item_description = $_REQUEST['item_Description'];
    $qty = $_REQUEST['quantity'];
    $roorder = $_REQUEST['reorderlevel'];
    $loccode = $_REQUEST['loccode'];
    $adjDesc = $_REQUEST['comment'];
    $name = $_SESSION['sess_login_username'];

    $sql = 'select quantity from care_ke_locstock where stockid="' . $item_number . '" and loccode="' . $loccode . '"';
    $result = $db->Execute($sql);
    $row = $result->FetchRow();

    $csql = "update care_ke_locstock set
quantity='$qty',
reorderlevel='$roorder',
comment='$adjDesc'
where stockid='$item_number' and loccode='$loccode'";

    $ksql = "insert into care_ke_adjustments
(item_number, prev_qty, new_qty, user, adjDate, adjTime, Reason)
values( '" . $item_number . "', '" . $row[0] . "', '" . $qty . "', '$name',
'" . date('Y-m-d') . "', '" . date('H:i:s') . "', '" . $adjDesc . "')";

    if ($db->Execute($ksql)) {
        $db->Execute($csql);
        $results = '{success: true }';
    } else {
// Errors. Set the json to return the fieldnames and the associated error messages
        $results = '{success: false, sql:' . $ksql . '}'; // Return the error message(s)
//        echo $sql;
    }

    echo $results;
}

function deleteItem() {
    global $db;
    $debug = false;
    $partcode = $_POST['partcode'];
    $sql = "DELETE FROM care_ke_locstock where stockid='$partcode'";
    if ($debug)
        echo $sql;
    if ($db->Execute($sql)) {
        $sql1 = "INSERT INTO `care2x`.`care_tz_drugsandservices_del`
(`item_number`,`partcode`, `is_pediatric`,`is_adult`, `is_other`,`is_consumable`,`is_labtest`,
`item_description`,`item_full_description`,`unit_price`,`unit_price_1`,`unit_price_2`,`unit_price_3`,
`purchasing_class`,`category`,`reorder`,`minimum`, `maximum`,`subCategory`,`unit_measure`, `selling_price`,`item_status`)
SELECT `item_number`,`partcode`,`is_pediatric`,`is_adult`,`is_other`,`is_consumable`, `is_labtest`, `item_description`,
`item_full_description`,`unit_price`,`unit_price_1`,`unit_price_2`,`unit_price_3`, `purchasing_class`, `category`,
`reorder`, `minimum`, `maximum`,`subCategory`,`unit_measure`, `selling_price`,`item_status`
FROM `care_tz_drugsandservices`  WHERE partcode='$partcode'";
        if ($debug)
            echo $sql1;
        if ($db->Execute($sql1)) {
            $query = 'DELETE FROM care_tz_drugsandservices WHERE partcode = "' . $partcode . '"';
            if ($debug)
                echo $query;
            if ($db->Execute($query)) { //returns number of rows deleted
                echo 'Item Successfully deleted from the database';
            } else {
                echo "Unable to delete item from table";
            }
        } else {
            echo "Unable to log deleted item";
        }
    } else {
        echo "unable to delete item in store locations,Consult System Admin";
    }
}
?>
