
<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');

require_once($root_path . 'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path . 'include/inc_init_xmlrpc.php');
require_once($root_path . 'include/care_api_classes/class_tz_insurance.php');
require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
$insurance_obj = new Insurance_tz;
$bill_obj = new Bill;

$limit = $_REQUEST[limit];
$start = $_REQUEST[start];

$task = ($_REQUEST['task']) ? ($_REQUEST['task']) : '';

$regDate1 = new DateTime($_POST[dateJoined]);
$dateJoined = $regDate1->format('Y/m/d');

$debtorAddress = $_POST[debtorAddress];
$debtorCategory = $_POST[debtorCategory];
$debtorEmail = $_POST[debtorEmail];
$debtorLocation = $_POST[debtorLocation];
$debtorName = $_POST[debtorName];
$debtorNo = $_POST[debtorNo];
$openingBL = $_POST[openingBL];
$debtorPhone = $_POST[debtorPhone];
$altPhone = $_POST[altPhone];
$assChief = $_POST[assChief];
$chief = $_POST[chief];
$creditLimit = $_POST[creditLimit];
$debtorContact = $_POST[debtorContact];
$debtorLocation = $_POST[debtorLocation];
$ipCover = $_POST[ipCover];
$ipExceed = $_POST[ipExceed];
$ipUsage = $_POST[ipUsage];
$location = $_POST[location];
$nearSchool = $_POST[nearSchool];
$opCover = $_POST[opCover];
$opExceed = $_POST[opExceed];
$opUsage = $_POST[opUsage];
$subLocation = $_POST[subLocation];
$village = $_POST[village];
$villageElder = $_POST[villageElder];
$username = $_SESSION['sess_login_username'];

$debtorFormStat=$_POST[formStatus];
$AllocationItems=$_REQUEST[transNos];

if (isset($_POST[debtorStatus])) {
    $suspended = 1;
} else {
    $suspended = 0;
}

$accno = ($_REQUEST['accno']) ? ($_REQUEST['accno']) : ($_POST['accno']);
$debtorParams = $_POST[params];

switch ($task) {
    case "getCustomerJobs":
        getCustomerJobs();
        break;
    case "getDebtorJobs":
        getDebtorJobs();
        break;
    case "getDebtorDetails":
        getDebtorDetails($start, $limit);
        break;
    case "getDebtorTransactions":
        getDebtorTransactions($start, $limit);
        break;
    case "getCustomerTypes":
        getCustomerTypes();
        break;
    case "getCustomerInfo":
        getCustomerInfo();
        break;
    case "getCustomerBill":
        getCustomerBill();
        break;
    case "getCustomersList":
        getCustomersList($start, $limit);
        break;
    case "insertDebtor":
        if($debtorFormStat=='insert'){
            insertDebtor($_POST);
        }else{
            updateDebtor($_POST);
        }
        break;
    case "insertGuarantor":
//        if($debtorFormStat=='insert'){
            insertGuarantor($_POST);
//        }else{
//            updateDebtor($_POST);
//        }
        break;
    case "getDebtorsList":
        getDebtorsList($start, $limit);
        break;
    case "getInvoicesReceipts":
        getInvoicesReceipts($start, $limit);
        break;
    case "getAllocations":
        getAllocations($start, $limit);
        break;
    case "addMember":
        addMember($bill_obj);
        break;
    case "removeMember":
        removeMember();
        break;
    case "getBillNumbers":
        getBillNumbers();
        break;
    case "closeInvoice":
        closeInvoice($insurance_obj, $bill_obj);
        break;
    case "getUnallocatedReceipts":
        getUnallocatedReceipts($start, $limit);
        break;
    case "getUnallocatedInvoices":
        getUnallocatedInvoices($start, $limit);
        break;
    case "allocateReceipts":
        allocateReceipts($AllocationItems);
        break;
    case "getMembersList":
        getMembersList($accno);
        break;
    case "getDebtorBalances":
        getDebtorBalances($start,$limit);
        break;
    case "getKins":
        getKins();
        break;
    case "getDebtorLocations":
        getDebtorLocations();
        break;
    case "getDebtorSubLocations":
        getDebtorSubLocations();
        break;
    case "insertCredit":
        insertCredit($username,$_POST);
        break;
    default:
        echo "{failure:true}";
        break;
}//end switch

function insertCredit($username,$creditDetails) {
        global $db,$bill_obj;
        $debug = false;
        $trnsNo = $bill_obj->getTransNo(7);
        
        $cdate = new DateTime($creditDetails[creditDate]);
        $creditDate = $cdate->format('Y/m/d');


        $sql = "insert into `care_ke_debtortrans`
                    (`transno`,`transtype`,`accno`, `pid`,`transdate`,`refNo`,`amount`,`lastTransDate`,
                    `lasttransTime`,`settled`,encounter_class_nr,reference,inputUser)
                    values('$trnsNo','7','$creditDetails[accno]', '$creditDetails[pid]','$creditDate','$trnsNo',
                    '$creditDetails[amount]','$creditDate','" . date('H:i:s') . "','1','2','$creditDetails[description]','$username')";
        if ($debug)
            echo $sql;

        if ($db->Execute($sql)) {
            $newTransNo = ($trnsNo + 1);
            $sql3 = "update care_ke_transactionNos set transNo='$newTransNo' where typeid='7'";
            if ($debug)
                echo $sql3;
            $db->Execute($sql3);

            echo '{success:true}';
        }else {
            echo '{failure:true}';
        }
    }

function getDebtorSubLocations(){
    global $db;
    $debug=false;
    $sql='SELECT CODE, NAME FROM care_ke_sublocation';
    if($debug) echo $sql;

    $result=$db->Execute($sql);
    $total=$result->RecordCount();

    echo '{
    "total":"' . $total . '","SubLocations":[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        echo '{"ID":"' . $row[CODE] . '","Location":"' . $row[NAME] .'"}';
        $counter++;
        if ($counter < $total) {
            echo ",";
        }
    }
    echo ']}';
}

function getDebtorLocations(){
    global $db;
    $debug=false;
    $sql='SELECT CODE, NAME FROM care_ke_location ';
    if($debug) echo $sql;

    $result=$db->Execute($sql);
    $total=$result->RecordCount();

    echo '{
    "total":"' . $total . '","locations":[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        echo '{"ID":"' . $row[CODE] . '","Location":"' . $row[NAME] .'"}';
        $counter++;
        if ($counter < $total) {
            echo ",";
        }
    }
    echo ']}';
}


function getKins(){
    global $db;
    $debug=false;

    $sql='SELECT `ID`,`Kin` FROM `care_ke_kins`';

    if($debug) echo $sql;
    $result=$db->Execute($sql);
    $total=$result->RecordCount();
    echo '{
    "total":"' . $total . '","kins":[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        echo '{"ID":"' . $row[ID] . '","Kin":"' . $row[Kin] .'"}';
        $counter++;
       if ($counter < $total) {
            echo ",";
        }
        
    }
    echo ']}';
}

function getMembersList($accno){
    global $db;
    $debug=false;

    $sql='SELECT `ID`,`accno`,`memberID`,`PID`,`MemberNames`,`memberType`,`OP_Usage`,`IP_Usage`,`DOB`,`inputDate`,`inputUser` FROM `care_ke_debtormembers`';

    if(isset($accno) && $accno<>''){
        $sql.=" where accno='$accno'";
    }

    if($debug) echo $sql;

    $result=$db->Execute($sql);

    $total=$result->RecordCount();

    echo '{
    "total":"' . $total . '","memberslist":[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        if ($row[dbNames] <> '')
            $dnames = $row[dbNames];
        else
            $dnames = $row[cnames];

        echo '{"ID":"' . $row[ID] . '","accno":"' . $row[accno] . '","memberID":"' . $row[memberID] . '","PID":"' . $row[PID]
            . '","MemberNames":"' . $row[MemberNames]. '","MemberType":"'.$row[memberType]. '","OP_Usage":"'.$row[OP_Usage]. '","IP_Usage":"'.$row[IP_Usage]
            . '","DOB":"'.$row[DOB]. '","DateJoined":"'.$row[inputDate].'"}';
        if ($counter < $total) {
            echo ",";
        }
        $counter++;
    }
    echo ']}';
}

function getBillDetails($billNumber){
    global $db;
    $debug=false;

    $sql="SELECT DISTINCT encounter_nr,`IP-OP` FROM care_ke_billing WHERE bill_number='$billNumber'";
    if($debug) echo $sql;
    $results=$db->Execute($sql);
    $row=$results->FetchRow();

    return $row;
}

function allocateReceipts($AllocationItems){
    global $db;
    $debug=false;
    $allocDate=date('Y-m-d');
    $allocTime=date('H:m:i');
    $inputUser= $_SESSION['sess_user_name'];

    $receiptTransNo=$_REQUEST[receiptTransNo];
    $receiptAmount=$_REQUEST[receiptAmount];
    $pid=$_REQUEST[pid];
    $billNumber=$_REQUEST[billNumber];

    $pairs = explode(',', $AllocationItems);

    foreach ($pairs as $pair) {
        $items = explode('-', $pair);
        for($i=0;$i<count($items);$i++){
            $transNos= $items[0];
            $invAmount=$items[1];
            $allocAmnt=$items[2];
            $invBalance=intval($invAmount)-intval($allocAmnt);
        }
        $sql="INSERT INTO `care_ke_debtorallocations`
            (`debtorTrans`,`accno`,`pid`,`bill_number`,`allocDate`,`allocTime`,`InvoiceAmount`,`AmountAllocated`,`InvoiceBalance`,`inputuser`)
              SELECT transNo,accno,pid,bill_number, '$allocDate','$allocTime',$invAmount,$allocAmnt,$invBalance,'$inputUser' FROM care_ke_debtortrans WHERE ID = $transNos";
        if($debug)
            echo $sql;

        $billDetails=getBillDetails($billNumber);

        updateBalances($db,$billDetails[0],$pid,$billNumber,$billDetails[1],$allocAmnt);

            //update the invoices and set them to allocated and set the allocated amount and the balances
        if($db->Execute($sql)){
            if($invBalance==0){
                $sql="update care_ke_debtortrans set allocated=1,allocatedAmount=$allocAmnt,invoiceBalance=$invBalance,
                  lastTransDate='$allocDate',lasttransTime='$allocTime',modifyID='$inputUser' where allocated=0 and ID =$transNos";
            }else{
                $sql="update care_ke_debtortrans set allocated=0,allocatedAmount=$allocAmnt,invoiceBalance=$invBalance,
                 lastTransDate='$allocDate',lasttransTime='$allocTime',modifyID='$inputUser' where allocated=0 and ID =$transNos";
            }
            if($debug)
                echo $sql;

            if($db->Execute($sql)){
                //update the receipt status to allocated, set the allocated amount and the balances.
            $sql="update care_ke_debtortrans set allocated=1,allocatedAmount=$receiptAmount,invoiceBalance=$invBalance,
                  lastTransDate='$allocDate',lasttransTime='$allocTime',modifyID='$inputUser' where allocated=0 and transno =$receiptTransNo";

                if($debug)
                    echo $sql;

                if($db->Execute($sql)){
                   $error=0;
                }else{
                   $error=1;
                }
            }
        }

    }

    if($error==0){
        echo "{success:true}";
    }else{
        echo "{failure:true}";
    }
}

function updateBalances($db,$encounter_nr,$pid,$new_bill_number,$encounter_class_nr,$amount){
    $debug=false;
    $username=$_SESSION['sess_user_name'];
    $inputDt=date('Y-m-d');

    $sql2="INSERT INTO care_ke_billing
                (
                    pid,encounter_nr,`IP-OP`, bill_date,bill_number,service_type,Description,
                    price,qty,total,input_user,notes, STATUS,batch_no,bill_time,rev_code,partcode
        ,item_number,weberpsync)
                VALUES('$pid','$encounter_nr','$encounter_class_nr','$inputDt','$new_bill_number','Payment',
    'Receipt Allocated','$amount','1','$amount','$username','Bill Payment','Paid','$new_bill_number',
    '".date('H:i:s')."','Alloc','Alloc','Alloc',1)";

    if($debug) echo $sql2;

    $db->Execute($sql2);

}

function getUnallocatedInvoices($start, $limit) {
    global $db;
    $debug=true;
    $accno = $_REQUEST['accno'];
    $searchParam=$_REQUEST['searchParam'];

    $startd = new DateTime($_REQUEST['startDate']);
    $startDate = $startd->format('Y-m-d');

    $endD = new DateTime($_REQUEST['endDate']);
    $endDate= $endD->format('Y-m-d');

    $sql = "SELECT t.ID,t.transno,t.accno,t.`pid`,d.`name`,CONCAT(p.`name_first`,' ',p.`name_2`,' ',p.`name_last`) AS pname,t.transdate
                ,t.refNo,t.amount,t.invoiceBalance,TRIM(t.bill_number) AS bill_number,n.nhifNo FROM care_ke_debtortrans t
            LEFT JOIN care_ke_debtors d ON t.`accno`=d.`accno`
            LEFT JOIN care_person p ON t.`pid`=p.`pid`
            LEFT JOIN care_ke_nhifcredits n on t.pid=n.admno
            WHERE t.allocated=0 AND t.transtype=2";

    if (isset($accno) && $accno<>'') {
        $sql = $sql . " and t.accno='$accno'";
    }

    if (isset($searchParam) && $searchParam<>'') {
        $sql = $sql . " and t.pid='$searchParam' OR t.bill_number='$searchParam' or p.name_first like '%$searchParam%'";
    }

    if($startDate<>'' && $endDate<>''){
        $sql=$sql." and t.transdate between '$startDate' and '$endDate'";
    }

    $sql = $sql . " limit $start,$limit";

    if($debug) echo $sql;

    $request = $db->Execute($sql);

    $total = $request->RecordCount();


    echo '{
    "total":"' . $total . '","invoiceList":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {
        if ($row[dbNames] <> '')
            $dnames = $row[dbNames];
        else
            $dnames = $row[cnames];

        if($row[invoiceBalance]>0){
            $amount=$row[invoiceBalance];
        }else{
            $amount=$row[amount];
        }

        echo '{"transno":"' . $row[ID] . '","accno":"' . $row[accno] . '","debtorname":"' . $row[name]
            . '","pid":"' . $row[pid] . '","pname":"' . $row[pname] . '","transdate":"' . $row[transdate]
        . '","InvoiceAmount":"' . $amount. '","AllocatedAmount":"","bill_number":"'.$row[bill_number]. '","memberNo":"' . $row[nhifNo].'"}';
        if ($counter < $total) {
            echo ",";
        }
        $counter++;
    }
    echo ']}';
}

function getUnallocatedReceipts($start, $limit) {
    global $db;
    $accno = $_REQUEST[accno];
    $category = $_REQUEST[category];
    $rtpType = $_REQUEST[rptType];
    $irParam = $_REQUEST[irParam];



    $sql = "SELECT t.transno,t.accno,d.`name`,t.transdate,t.refNo,t.amount FROM care_ke_debtortrans t 
        LEFT JOIN care_ke_debtors d ON t.`accno`=d.`accno`
        WHERE t.allocated=0 AND t.transtype=1 AND t.accno NOT IN ('NHIF2','D30') limit $start,$limit";
//    echo $sql;

    $request = $db->Execute($sql);


    $total = $request->RecordCount();


    echo '{
    "total":"' . $total . '","receiptsList":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {
        if ($row[dbNames] <> '')
            $dnames = $row[dbNames];
        else
            $dnames = $row[cnames];

        $dbnames= $desc = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[name]);

        echo '{"transno":"' . $row[transno] . '","accno":"' . $row[accno] . '","debtorname":"' . $dbnames . '","transdate":"' . $row[transdate]
        . '","InvoiceAmount":"' . $row[amount] . '"}';
        if ($counter < $total) {
            echo ",";
        }
        $counter++;
    }
    echo ']}';
}

function closeInvoice($insurance_obj, $bill_obj) {
    // global $db;
    $pid = $_REQUEST[pid];
    $encounterNo = $_REQUEST[encounterNo];
    $insuCompanyID = $insurance_obj->GetCompanyFromPID2($pid);
    $bill_obj->updateDebtorsTrans($pid, $insuCompanyID, $encounterNo);
}

function getAllocations($start, $limit) {
    global $db;
    $debug=false;

    $accno = $_REQUEST[accno];
    $category = $_REQUEST[category];
    $irParam = $_REQUEST[irParam];


    $sql = "SELECT d.pid,d.`allocDate`,d.`allocTime`,d.`bill_number`,d.accno,c.`name` AS debtor,CONCAT(TRIM(p.`name_first`),' ',TRIM(p.`name_last`),' ',TRIM(p.`name_2`)) AS patient,
            d.`InvoiceAmount`,d.`AmountAllocated`,d.`invoiceBalance`
            FROM care_ke_debtorallocations d LEFT JOIN care_person p ON d.`pid`=p.`pid`
            LEFT JOIN care_ke_debtors c ON d.`accno`=c.`accno`";

    if (isset($irParam)) {
        $sql.=" and p.pid='$irParam' or p.name_first like '%$irParam%' or p.name_last like '%$irParam%' or p.name_2 like '%$irParam%'";
    }

    if (isset($accno)) {
        $sql.=" and d.accno like '$accno%' or c.name like '$accno%'";
    }

    if (isset($category)) {
        $sql.=" and c.category='$category'";
    }

    if(isset($start) && isset($limit)){
        $sql.=" limit $start,$limit";
    }


    if($debug) echo $sql;

    $request = $db->Execute($sql);

    $sqlTotal = "select count(accno) as counts from care_ke_debtorallocations";

    $request2 = $db->Execute($sqlTotal);

    $row = $request2->FetchRow();
    $total = $row[0];

    echo '{
    "total":"' . $total . '","allocationsLists":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {
        if ($row[dbNames] <> '')
            $dnames = $row[dbNames];
        else
            $dnames = $row[cnames];

        echo '{"accno":"' . $row[accno] . '","debtor":"' . $row[debtor] . '","pid":"' . $row[pid] . '","patient":"' . $row[patient] . '","AllocationDate":"' . $row[allocDate].' '. $row[allocTime]
        . '","billNumber":"' . $row[bill_number] . '","InvoiceAmount":"' . $row[InvoiceAmount] . '","AmountAllocated":"' . $row[AmountAllocated] . '","invoiceBalance":"' . $row[invoiceBalance] . '"}';
        if ($counter <> $total) {
            echo ",";
        }
        $counter++;
    }
    echo ']}';
}

function getInvoicesReceipts($start, $limit) {
    global $db;
    $debug=false;
    
    $accno = $_REQUEST[accno];
    $category = $_REQUEST[category];
    $rtpType = $_REQUEST[rptType];
    $irParam = $_REQUEST[irParam];

    $sql = "SELECT d.pid,d.`transdate`,d.`bill_number`,d.accno,CONCAT(TRIM(p.`name_first`),' ',TRIM(p.`name_last`),' ',TRIM(p.`name_2`)) AS dbNames,
            amount,d.`transtype`,c.`name` as cname,c.`name` as cnames
            FROM care_ke_debtortrans d LEFT JOIN care_person p ON d.`pid`=p.`pid`
            LEFT JOIN care_ke_debtors c ON d.`accno`=c.`accno`
            WHERE  allocated=0 and d.accno NOT IN ('NHIF','NHIF1','NHIF2')";

    if (isset($irParam) and $irParam<>'') {
        $sql.=" OR p.pid like '%$irParam%' or p.name_first like '%$irParam%' or p.name_last like '%$irParam%' or p.name_2 like '%$irParam%' or c.`name` like '%$irParam%'";
    }

    if (isset($accno) && $accno<>'') {
        $sql.=" and c.category ='$accno'";
    }

    if (isset($rtpType) && $rtpType == 'invoices') {
        $sql.=" and d.`transtype`=2";
    } else if (isset($rtpType) && $rtpType == 'receipts') {
        $sql.=" and d.`transtype`=1";
    }

    if (isset($category) && $category<>'') {
        $sql.=" and d.category='$category'";
    }

    if ($_REQUEST[startDate] <> '' && $_REQUEST[endDate] <> '') {
        $startd = new DateTime($_REQUEST[startDate]);
        $startDate = $startd->format('Y-m-d');

        $end = new DateTime($_REQUEST[endDate]);
        $endDate = $end->format('Y-m-d');
        if ($startDate > '2000-01-01' && $endDate > '2000-01-01') {
            $sql.=" and d.`transdate` between '$startDate' and '$endDate'";
        }
    }

    $sql.="  order by d.`transdate` asc limit $start,$limit";

    if($debug) { echo $sql; }

    $request = $db->Execute($sql);

    $sqlTotal = "select count(accno) as counts from care_ke_debtortrans where accno<>''";
    if (isset($rtpType) && $rtpType == 'receipts') {
        $sqlTotal.=" and `transtype`=1";
    } else {
        $sqlTotal.=" and `transtype`=2";
    }
    $request2 = $db->Execute($sqlTotal);

    $row2 = $request2->FetchRow();
    $total = $row2[0];

    echo '{
    "total":"' . $total . '","invoiceLists":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {
        if ($row[dbNames] <> '')
            $dnames = $row[dbNames];
        else
            $dnames = $row[cnames];

        echo '{"accno":"' . $row[accno] . '","pid":"' . $row[pid] . '","dbNames":"' . $dnames . '","transdate":"' . $row[transdate]
        . '","billNumber":"' . $row[bill_number] . '","amount":"' . $row[amount] . '","cname":"' . $row[cname] . '"}';
        
         $counter++;
         
        if ($counter < $total) {
            echo ",";
        }
       
    }
    echo ']}';
}

function getDebtorsList($start, $limit) {
    global $db;
    $accno = $_REQUEST[accno];
    $category = $_REQUEST[category];
    $debug = false;


    $sql = "SELECT `accno`,d.`name`,c.`name` AS category,`address1`,`address2`,`phone`,`altPhone`,`contact`,`email`,`joined`,
            `cr_limit`,`OP_Cover`,`IP_Cover`,`OP_Usage`,`IP_Usage`,`OP_Exceed`, `IP_Exceed`,
            `assChief`,`chief`,`creditLimit`, `village`,`villageElder`, `dbStatus`,
            `location`, `nearSchool`,`subLocation`,`guarantorsName`,`guarantorsID`,`guarantorsLocation`,
            `guarantorsSubLoc`, `guarantorsVillage`,`guarantorsAddress`,`guarantorsPhone`,`guarantorsRelation`,
             `guarantorsAmount`,nextPaymentDate,openingBL,otherInfo,statementInfo,StaffDiscount FROM care_ke_debtors d
            LEFT JOIN care_ke_debtorcat c ON d.`category`=c.`code` WHERE accno<>''";

    if (isset($accno) && $accno<>'') {
        $sql.=" and accno like '$accno%' or d.name like '$accno%'";
    }

    if (isset($category) && $category<>'') {
        $sql.=" and d.category='$category'";
    }

    $sql.=" limit $start,$limit";
    if ($debug)
        echo $sql;

    $sql2 = "SELECT count(d.accno) as countAcc FROM care_ke_debtors d LEFT JOIN care_ke_debtorcat c ON d.`category`=c.`code` WHERE accno<>''";

    if (isset($accno)) {
        $sql2.=" and accno like '$accno%' or d.name like '$accno%'";
    }

    if (isset($category)) {
        $sql2.=" and d.category='$category'";
    }
    if ($debug)
        echo $sql2;
    $request = $db->Execute($sql);
    $request2 = $db->Execute($sql2);
    $row2 = $request2->FetchRow();

    $total = $row2[0];

    echo '{
    "total":"' . $total . '","debtorsList":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {
        echo '{"accno":"' . $row[accno] . '","name":"' . trim($row[name]) . '","category":"' . trim($row[category])
        . '","address1":"' . trim($row[address1]) . '","address2":"' . $row[address2] . '","phone":"' . $row[phone] . '","altphone":"' . $row[altPhone]
        . '","contact":"' . $row[contact] . '","email":"' . $row[email]
        . '","joined":"' . $row[joined] . '","cr_limit":"' . $row[cr_limit] . '","OP_Cover":"' . $row[OP_Cover] . '","IP_Cover":"' . $row[IP_Cover]
        . '","IP_Usage":"' . $row[IP_Usage] . '","OP_Usage":"' . $row[OP_Usage] . '","IP_Usage":"' . $row[IP_Usage] . '","OP_Exceed":"' . $row[OP_Exceed]
        . '","IP_Exceed":"' . $row[IP_Exceed] . '","assChief":"' . $row[assChief] . '","chief":"' . $row[chief] . '","creditLimit":"' . $row[creditLimit]
        . '","village":"' . $row[village] . '","villageElder":"' . $row[villageElder] . '","dbStatus":"' . $row[dbStatus] . '","location":"' . $row[location]
        . '","nearSchool":"' . $row[nearSchool] . '","subLocation":"' . $row[subLocation] . '","guarantorsName":"' . $row[guarantorsName]
        . '","guarantorsID":"' . $row[guarantorsID] . '","guarantorsLocation":"' . $row[guarantorsLocation]
        . '","creditLimit":"' . $row[creditLimit] . '","village":"' . $row[village] . '","villageElder":"' . $row[villageElder] 
        . '","location":"' . $row[location] . '","guarantorsSubLoc":"' . $row[guarantorsSubLoc] . '","guarantorsVillage":"' . $row[guarantorsVillage]
        . '","guarantorsAddress":"' . $row[guarantorsAddress]
        . '","guarantorsPhone":"' . $row[guarantorsPhone] . '","guarantorsRelation":"' . $row[guarantorsRelation]
        . '","guarantorsAmount":"' . $row[guarantorsAmount] . '","fax":"' . $row[fax]
        . '","nextPaymentDate":"' . $row[nextPaymentDate]. '","openingBL":"' . $row[openingBL]. '","otherInfo":"' . $row[otherInfo]
        . '","statementInfo":"' . $row[statementInfo]. '","staffdiscount":"' . $row[StaffDiscount] .'"}';

        $counter++;
        if ($counter <> $total) {
            echo ',';
        }
    }
    echo ']}';
}

function getCustomersList($start, $limit) {
    global $db;
    $param1 = $_REQUEST[param1];
    //$category=$_REQUEST[category];

    $sql = "SELECT p.pid,p.selian_pid,CONCAT(TRIM(p.name_first),' ',TRIM(p.name_2),' ',TRIM(p.name_last)) AS pnames,
            p.`date_reg`,p.`addr_zip`,p.`citizenship`,p.`date_birth`,p.`sex`,p.`phone_1_nr`,p.`cellphone_1_nr`
            FROM care_person p WHERE p.`insurance_ID`>0";

    if (isset($param1)) {
        $sql.=" and p.pid=$param1";
    }

    $sql.=" limit $start,$limit";
    ///echo $sql;

    $request = $db->Execute($sql);

    $sql2 = "SELECT count(pid) as pcount FROM care_person p WHERE p.`insurance_ID`>0";
    $request2 = $db->Execute($sql2);
    $row = $request2->FetchRow();
    $total = $row[0];

    echo '{
    "total":"' . $total . '","customersList":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {
        echo '{"pid":"' . $row[pid] . '","fileNo":"' . $row[selian_pid] . '","pnames":"' . $row[pnames]
        . '","regDate":"' . $row[date_reg] . '","dob":"' . $row[date_birth] . '","gender":"' . $row[sex]
        . '","address":"' . $row[addr_zip] . '","residence":"' . $row[citizenship] . '","phone1":"' . $row[phone_1_nr]
        . '","phone2":"' . $row[cellphone_1_nr] . '"}';
        if ($counter <> $total) {
            echo ",";
        }
        $counter++;
    }
    echo ']}';
}


function validateDebtorNo($debtorNo){
    global $db;
    $psql = "select accno from care_ke_debtors where accno='$debtorNo'";
//    echo $psql;
    $presult = $db->Execute($psql);
    $row = $presult->FetchRow();
    $rowCount=$presult->RecordCount();

    return $rowCount;
}

function updateDebtor($debtorDetails) {
    global $db;
    $sql='UPDATE care_ke_debtors SET ';
		foreach ($debtorDetails as $key => $value) {
			$sql .= $key.'="'.$value.'", ';
		}
		$sql = substr($sql,0,-2).' WHERE accno="'.$debtorDetails['accno'].'"';
    // echo $sql;

    if ($db->Execute($sql)) {
        $results = "{success: true}";
    } else {
        $results = "{success: false,errors:{clientNo:'Could no save new Debtor, Please check your values'}}"; // Return the error message(s)

    }
    echo $results;
}


function insertDebtor($debtorDetails) {
    global $db,$bill_obj;
    $debug=false;
    
    $debtorDetails[name]=$bill_obj->escapeJsonString($debtorDetails[name]);
    $name=$debtorDetails[name];
    
    foreach ($debtorDetails as $key => $value) {
        $FieldNames.=$key . ', ';
        $FieldValues.='"' . $value . '", ';
    }
 
    if(validateDebtorNo($debtorDetails['accno'])<1){
        unset($debtorDetails['formStatus']);

        $sql = 'INSERT INTO care_ke_debtors ('.substr($FieldNames,0,-2).') '.
		  		'VALUES ('. substr($FieldValues,0,-2).') ';
       if($debug) echo $sql;
        if ($db->Execute($sql)) {
           // $name= addslashes($debtorDetails[name]);
            $sql2 = "INSERT INTO `care_tz_company`(`accNo`,`name`,`contact`,`po_box`,`city`,
                    `start_date`,`end_date`,`invoice_flag`,
                    `credit_preselection_flag`,`hide_company_flag`,`prepaid_amount`,`email`
                    ,`phone_code`,category)
                VALUES ('$debtorDetails[accno]','$name','$debtorDetails[address1]','$debtorDetails[address2]','$debtorDetails[address2]',
                '0','0','0', '1','0','0','','$debtorDetails[phone]','$debtorDetails[category]')";
            if($debug) echo $sql;

            if ($db->Execute($sql2)) {
                $results = "{success: true}";
            } else {
                $results = "{success: false,errors:{clientNo:'Could not update Insurance Company'}}";
            }
        } else {
            $results = "{success: false,errors:{clientNo:'Could no save new Debtor, Please check your values',sql}}"; // Return the error message(s)
        }
    }else{
         $results = "{success: false,errors:{clientNo:'Debtor No Already Exists'}}"; // Return the error message(s)
    }
    
    echo $results;
}

function insertGuarantor($debtorDetails) {
    global $db;
    $debug=FALSE;    
    foreach ($debtorDetails as $key => $value) {
        $FieldNames.=$key . ', ';
        $FieldValues.='"' . $value . '", ';
    }
    $date1 = new DateTime($debtorDetails[payableBy]);
    $paybable = $date1->format("Y-m-d");


    $inputUser=$_SESSION['sess_user_name'];

            $sql2 = "INSERT INTO `care_ke_debtorguarantors` (
                      `accno`,`pid`,`pname`,`guarantor1`,`guarantor2`,`guarantor3`,`relation1`,`relation2`,`relation3`,`security1`, `security2`,`security3`,
                      `invoiceNumber`,`invoiceAmount`,`installmentNo`,`installment1`,`installment2`,`installment3`,`payableDate`,`narrative`,  `inputUser`
                    ) 
                    VALUES
                      ('$debtorDetails[accNo]','$debtorDetails[pid]','$debtorDetails[pnames]','$debtorDetails[guarantor1]', 
                      '$debtorDetails[guarantor2]','$debtorDetails[guarantor3]','$debtorDetails[relation1]','$debtorDetails[relation2]',
                      '$debtorDetails[relation3]','$debtorDetails[security1]','$debtorDetails[security2]',
                       '$debtorDetails[security3]','$debtorDetails[billNumber]','$debtorDetails[amount]',
                       '$debtorDetails[installmentNo]','$debtorDetails[installment1]','$debtorDetails[installment2]','$debtorDetails[installment3]',
                       '$paybable','$debtorDetails[narrative]', '$inputUser')";

;
            if($debug) echo $sql2;

           if($db->Execute($sql2)){
               echo "{success:true}";
           } else{
               echo "{failure:true}";
           }

}


function getDebtorJobs() {
    global $db;
    $debug=false;
    global $bill_obj;

    $category = $_REQUEST[category];
    $accno = $_REQUEST[accno];

    $sql = "SELECT c.accno ,c.`name`,SUM(amount) AS invAmount,SUM(InvoiceBalance) AS balance,d.`allocated`
            FROM care_ke_debtortrans d 
            LEFT JOIN care_ke_debtors c ON d.`accno`=c.`accno` WHERE d.transtype=2";
    if (isset($category)) {
        $sql.=" and c.category='$category'";
    }

    if (isset($accno)) {
        $sql.=" and c.accno like '$accno%' or c.name like '$accno%'";
    }

    $sql.= " GROUP BY accno HAVING  allocated=0";

      if($debug) echo $sql;

    $request = $db->Execute($sql);

    $total = $request->RecordCount();

    echo '{
    "total":"' . $total . '","Debtors":[';
    $counter = 0;

    while ($row = $request->FetchRow()) {
        if($row[balance]>0){
            $balance=$row[balance];
        }else{
            $balance=$row[invAmount];
        }
        $names = $bill_obj->escapeJsonString($row[name]);
        echo '{"accno":"' . $row[accno] . '","names":"' . $names . '","balance":"' . $balance . '"}';
        $counter++;
        if ($counter <> $total) {
            echo ",";
        }

    }
    echo ']}';
}

function getDebtorDetails($start, $limit) {
    global $db;
    $accno = $_REQUEST[accno];

    $sql = "SELECT d.accno,d.name,c.`name` as category,d.address1,d.address2,d.phone,d.altphone,d.contact,d.email,d.fax FROM care_ke_debtors d
        LEFT JOIN care_ke_debtorcat c ON d.`category`=c.`code` WHERE accno='$accno' limit $start,$limit";
    $request = $db->Execute($sql);

    $total = $request->RecordCount();

    echo '{
    "total":"' . $total . '","debtorDetails":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {
        if ($row[name] <> '') {
            $dname = $row[name];
        } else {
            $dname = $row[category];
        }
        echo '{"Accno":"' . $row[accno] . '","accName":"' . $dname . '","accCategory":"' . $row[category] . '","accAddress1":"' . $row[address1]
        . '","accAddress2":"' . $row[address2] . '","accPhone":"' . $row[phone]
        . '","accAltphone":"' . $row[altphone] . '","accContact":"' . $row[contact] . '","accEmail":"' . $row[email]
        . '","accFax":"' . $row[fax] . '"}';
       
         $counter++;
        if ($counter <> $total) {
            echo ",";
        }
       
    }
    echo ']}';
}



function getDebtorTransactions($start, $limit) {
    global $db;
    $debug=false;
    $accno = $_REQUEST[accno];
    $searchParam = $_REQUEST[searchParam];
    $showAllocated=$_REQUEST[showAllocated];
    

    $sql = "SELECT d.accno,d.transno,d.pid,d.`encounter_nr`,p.`name_first`,p.`name_2`,p.`name_last`
            ,d.`lastTransDate`,d.`bill_number`, d.`amount`,
            p.`addr_zip`,p.`phone_1_nr`,p.`cellphone_1_nr`,p.`citizenship`,p.`email`,c.`name` AS cname,d.transdate,t.typeName,
            d.`allocated`,d.`allocatedAmount`,d.`invoiceBalance`
            FROM care_ke_debtortrans d 
            LEFT JOIN care_person p ON d.`pid`=p.`pid` 
            LEFT JOIN care_ke_debtors c ON d.`accno`=c.`accno` 
            LEFT JOIN `care_ke_transactionnos` t ON d.`transtype`=t.typeID            
            WHERE  d.accno<>''";


    $sql2 = "select count(d.pid) as countp from care_ke_debtortrans d left join care_person p on d.pid=p.pid where d.accno<>''";

    if($showAllocated=='false'){
        $sql.=" and d.allocated=0";
        $sql2.=" and d.allocated=0";
    }

    if (isset($accno)) {
        $sql.=" and d.accno='$accno'";
        $sql2.=" and d.accno='$accno'";
    }

    if ($_REQUEST[startDate] <> '' && $_REQUEST[endDate] <> '') {
        $startd = new DateTime($_REQUEST[startDate]);
        $startDate = $startd->format('Y-m-d');

        $end = new DateTime($_REQUEST[endDate]);
        $endDate = $end->format('Y-m-d');
        if ($startDate > '2000-01-01' && $endDate > '2000-01-01') {
            $sql.=" and d.`transdate` between '$startDate' and '$endDate'";
            $sql2.=" and d.`transdate` between '$startDate' and '$endDate'";
        }
    }
//            
    if (isset($searchParam)) {
        $sql.=" and d.pid=$searchParam or p.name_first like'%$searchParam%' or d.name_2 like'%$searchParam%' or p.name_last like'%$searchParam%'";
        $sql2.=" and d.pid=$searchParam or p.name_first like'%$searchParam%' or d.name_2 like'%$searchParam%' or p.name_last like'%$searchParam%'";
    }

    $sql.=" limit " . $start . "," . $limit;

    if($debug) echo $sql;


    $request = $db->Execute($sql);
    $request2 = $db->Execute($sql2);
    $row2 = $request2->FetchRow();
    $total = $row2[0];
//    $total= $request->RecordCount();

    echo '{
    "total":"' . $total . '","debtorTrans":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {

        if ($row[name_first] == '' && $row[name_last] == '') {
            $dname = $row[cname];
        } else {
            $dname = $row[name_first] . ' ' . $row[name_2] . ' ' . $row[name_last];
        }

//        if ($row[encounter_date] <> '') {
//            $transdate = $row[encounter_date];
//        } else {
            $transdate = $row[transdate];
//        }

//        if ($row[transtype] == 1) {
//            $transType = 'Receipts';
//        } else {
//            $transType = 'Invoice';
//        }



        echo '{"accno":"' . $row[accno] . '","transno":"' . $row[transno] . '","pid":"' . $row[pid] . '","encounter_nr":"' . $row[encounter_nr]
        . '","pnames":"' . $dname . '","admDate":"' . $row[encounter_date] . '","disDate":"' . $row[discharge_date]
        . '","addr_zip":"' . $row[addr_zip] . '","phone_1_nr":"' . $row[phone_1_nr] . '","cellphone_1_nr":"' . $row[cellphone_1_nr]
        . '","addr_zip2":"' . $row[citizenship] . '","email":"' . $row[email] . '","encClass":"' . $row[encounter_class_nr]
        . '","lastTransDate":"' . $transdate . '","billNumber":"' . $row[bill_number] . '","amount":"' . $row[amount]
        . '","transType":"' . $row[typeName] . '","allocated":"' . $row[allocated] . '","allocatedAmount":"' . $row[allocatedAmount]. '","invoiceBalance":"' . $row[invoiceBalance] . '"}';
        if ($counter < $total) {
            echo ",";
        }
        $counter++;
    }
    echo ']}';
}

function getDebtorBalances($start, $limit) {
    global $db;
    $debug=false;
    $accno = $_REQUEST[accno];
    $searchParam = $_REQUEST[searchParam];
    $showAllocated=$_REQUEST[showAllocated];
    $category=$_REQUEST[category];

    $sql = "SELECT d.accno,c.name,d.transno,d.pid,d.`encounter_nr`,p.`name_first`,p.`name_2`,p.`name_last`
            ,d.`lastTransDate`,d.`bill_number`, d.`amount`,e.`encounter_date`,e.`discharge_date`,e.`encounter_class_nr`,
            p.`addr_zip`,p.`phone_1_nr`,p.`cellphone_1_nr`,p.`citizenship`,p.`email`,c.`name` AS cname,d.transdate,d.transtype,
            d.`allocated`,d.`allocatedAmount`,d.`invoiceBalance`
            FROM care_ke_debtortrans d
            LEFT JOIN care_person p ON d.`pid`=p.`pid`
            LEFT JOIN care_ke_debtors c ON d.`accno`=c.`accno`
	      LEFT JOIN care_encounter e ON d.`encounter_nr`=e.`encounter_nr` WHERE  d.accno<>''";


    $sql2 = "select count(d.pid) as countp from care_ke_debtortrans d left join care_person p on d.pid=p.pid
                    LEFT JOIN care_ke_debtors c ON d.`accno`=c.`accno` where d.accno<>''";

    if($showAllocated=='false'){
        $sql.=" and d.allocated=0";
        $sql2.=" and d.allocated=0";
    }

    if (isset($accno) && $accno<>'') {
        $sql.=" and d.accno='$accno'";
        $sql2.=" and d.accno='$accno'";
    }

    if (isset($category) && $category<>'') {
        $sql.=" and c.category='$category'";
        $sql2.=" and c.category='$category'";
    }

    if ($startDate > '2015-01-01' && $endDate > '2015-01-01') {
        $sql.=" and e.encounter_date between '$startDate' and '$endDate'";
        $sql2.=" and d.transdate between '$startDate' and '$endDate'";
    }
    
//
    if (isset($searchParam) && $searchParam<>'') {
        $sql.=" and d.pid like '%$searchParam%' or p.name_first like '%$searchParam%' or p.name_2 like '%$searchParam%' or p.name_last like '%$searchParam%'";
        $sql2.=" and d.pid like '%$searchParam%' or p.name_first like '%$searchParam%' or p.name_2 like '%$searchParam%' or p.name_last like '%$searchParam%'";
    }

    $sql.=" limit " . $start . "," . $limit;
    if ($debug) echo $sql;


    $request = $db->Execute($sql);
    $request2 = $db->Execute($sql2);
    $row2 = $request2->FetchRow();
    $total = $row2[0];
//    $total= $request->RecordCount();

    echo '{
    "total":"' . $total . '","debtorbalances":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {

        if ($row[name_first] == '' && $row[name_last] == '') {
            $dname = $row[cname];
        } else {
            $dname = $row[name_first] . ' ' . $row[name_2] . ' ' . $row[name_last];
        }

        if ($row[encounter_date] <> '') {
            $transdate = $row[encounter_date];
        } else {
            $transdate = $row[transdate];
        }

        if ($row[transtype] == 1) {
            $transType = 'Receipts';
        } else {
            $transType = 'Invoice';
        }



        echo '{"accno":"' . $row[accno]. '","debtorName":"' . $row[name] . '","transno":"' . $row[transno] . '","pid":"' . $row[pid] . '","encounter_nr":"' . $row[encounter_nr]
            . '","pnames":"' . $dname . '","admDate":"' . $row[encounter_date] . '","disDate":"' . $row[discharge_date]
            . '","addr_zip":"' . $row[addr_zip] . '","phone_1_nr":"' . $row[phone_1_nr] . '","cellphone_1_nr":"' . $row[cellphone_1_nr]
            . '","addr_zip2":"' . $row[citizenship] . '","email":"' . $row[email] . '","encClass":"' . $row[encounter_class_nr]
            . '","lastTransDate":"' . $transdate . '","billNumber":"' . $row[bill_number] . '","amount":"' . $row[amount]
            . '","transType":"' . $transType . '","allocated":"' . $row[allocated] . '","allocatedAmount":"' . $row[allocatedAmount]. '","invoiceBalance":"' . $row[invoiceBalance] . '"}';
        if ($counter < $total) {
            echo ",";
        }
        $counter++;
    }
    echo ']}';
}


function getCustomerJobs() {
    global $db;

    $sql = "SELECT d.pid,c.accno,CONCAT(TRIM(p.`name_first`),' ',TRIM(p.`name_last`),' ',TRIM(p.`name_2`)) AS names,
            SUM(amount) AS Balance 
            FROM care_ke_debtortrans d LEFT JOIN care_person p ON d.`pid`=p.`pid`
            LEFT JOIN care_ke_debtors c ON d.`accno`=c.accno
            GROUP BY pid";

    $request = $db->Execute($sql);

    $total = $request->RecordCount();

    echo '{
    "total":"' . $total . '","Customers":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {
        echo '{"pid":"' . $row[pid] . '","accno":"' . $row[accno] . '","names":"' . $row[names] . '","balance":"' . $row[Balance] . '"}';
        if ($counter < $total) {
            echo ",";
        }
        $counter++;
    }
    echo ']}';
}

function getCustomerTypes() {
    global $db;

    $sql = "SELECT `code`,trim(`name`) as names FROM care_ke_debtorcat";

    $request = $db->Execute($sql);

    $total = $request->RecordCount();

    echo '{
    "total":"' . $total . '","CustomerType":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {
        echo '{"ID":"' . $row[code] . '","custNames":"' . $row[names] . '"}';
        if ($counter <> $total) {
            echo ",";
        }
        $counter++;
    }
    echo ']}';
}

function getCustomerInfo() {
    global $db;
    $pid = $_REQUEST['pid'];
    $encNr = $_REQUEST['encNr'];

    $sql = "SELECT p.pid,e.`encounter_nr`,CONCAT(p.`name_first`,' ',p.`name_2`,' ',p.`name_last`) AS pnames,
                p.`addr_zip`,p.`phone_1_nr`,p.`cellphone_1_nr`,p.`citizenship`,p.`email`,
                e.`encounter_date`,e.`discharge_date`,e.`encounter_class_nr`
                FROM care_person p LEFT JOIN care_encounter e ON p.`pid`=e.`pid`
                WHERE p.`pid`=$pid";

    if (isset($encNr) && $encNr <> '') {
        $sql.=" AND e.`encounter_nr`=$encNr";
    }
//    echo $sql;
    $sql2 = "SELECT count(p.pid) as pcount FROM care_person p LEFT JOIN care_encounter e ON p.`pid`=e.`pid`
                WHERE p.`pid`=$pid";
    //echo $sql2;

    $request = $db->Execute($sql);
    $request2 = $db->Execute($sql2);
    $row2 = $request2->FetchRow();
    $total = $row2[0];

    echo '{
    "total":"' . $total . '","CustomerInfo":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {
        echo '{"pid":"' . $row[pid] . '","encNr":"' . $row[encounter_nr] . '","pnames":"' . $row[pnames] .
        '","addr_zip":"' . $row[addr_zip] . '","addr_zip2":"' . $row[citizenship] .
        '","cell":"' . $row[phone_1_nr] . '","altCell":"' . $row[cellphone_1_nr] .
        '","email":"' . $row[email] .
        '","admDate":"' . $row[encounter_date] . '","disDate":"' . $row[discharge_date] .
        '","encClass":"' . $row[encounter_class_nr] . '"}';
        if ($counter <> $total) {
            echo ",";
        }
        $counter++;
    }
    echo ']}';
}

function getBillNumbers() {
    global $db;
    $pid = $_REQUEST['pid'];

    $sql = "SELECT DISTINCT bill_number,encounter_nr FROM care_ke_billing WHERE pid=$pid";
    $request = $db->Execute($sql);
    $total = $request->RecordCount();

    echo '{
    "total":"' . $total . '","billNumbers":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {
        echo '{"billNumber":"' . $row[bill_number] . '","encounterNr":"' . $row[encounter_nr] . '"}';
        if ($counter <> $total) {
            echo ",";
        }
        $counter++;
    }
    echo ']}';
}

function getCustomerInfo2() {
    global $db;
    $pid = $_REQUEST['pid'];
    $encNr = $_REQUEST['encNr'];

    $sql = "SELECT p.pid,e.`encounter_nr`,CONCAT(p.`name_first`,' ',p.`name_2`,' ',p.`name_last`) AS pnames,
                p.`addr_zip`,p.`phone_1_nr`,p.`cellphone_1_nr`,p.`citizenship`,p.`email`,
                e.`encounter_date`,e.`discharge_date`,e.`encounter_class_nr`
                FROM care_person p LEFT JOIN care_encounter e ON p.`pid`=e.`pid`
                WHERE p.`pid`=$pid";

    if (isset($encNr) && $encNr <> '') {
        $sql.=" AND e.`encounter_nr`=$encNr";
    }
//    echo $sql;
    $sql2 = "SELECT count(p.pid) as pcount FROM care_person p LEFT JOIN care_encounter e ON p.`pid`=e.`pid`
                WHERE p.`pid`=$pid";

    $request = $db->Execute($sql);
    $request2 = $db->Execute($sql2);

    $total = $request2->RecordCount();

    echo '{
    "total":"' . $total . '","CustomerInfo":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {
        echo '{"pid":"' . $row[pid] . '","encNr":"' . $row[encounter_nr] . '","pnames":"' . $row[pnames] .
        '","addr_zip":"' . $row[addr_zip] . '","addr_zip2":"' . $row[citizenship] .
        '","cell":"' . $row[phone_1_nr] . '","altCell":"' . $row[cellphone_1_nr] .
        '","email":"' . $row[email] .
        '","admDate":"' . $row[encounter_date] . '","disDate":"' . $row[discharge_date] .
        '","encClass":"' . $row[encounter_class_nr] . '"}';
        if ($counter <> $total) {
            echo ",";
        }
        $counter++;
    }
    echo ']}';
}

function getCustomerBill() {
    global $db;
    $pid = $_REQUEST['pid'];
    $encNr = $_REQUEST['encNr'];

    $sql = "SELECT b.`pid`,b.`service_type`,b.`partcode`,b.`Description`,b.`price`,b.`qty`,b.`total` FROM care_ke_billing b
  
        where b.pid=$pid and b.encounter_nr=$encNr";

    $request = $db->Execute($sql);

    $total = $request->RecordCount();

    echo '{
    "total":"' . $total . '","CustomerBill":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {
        echo '{"pid":"' . $row[pid] . '","service_type":"' . $row[service_type] .
        '","partcode":"' . $row[partcode] . '","Description":"' . $row[Description] .
        '","price":"' . $row[price] . '","qty":"' . $row[qty] .
        '","total":"' . $row[total] . '"}';
        if ($counter <> $total) {
            echo ",";
        }
        $counter++;
    }
    echo ']}';
}

function addMember($bill_obj) {
    global $db;
    $debug=false;

    $pid = $_POST[txtPid];
    $accno = $_POST[txtAccno];
    $memberID=$_POST[memberID];
    $user= $_SESSION['sess_user_name'];

    $sql1 = "select ID from care_tz_company where accno='$accno'";
    $result1 = $db->Execute($sql1);
    $row = $result1->FetchRow();
    if($debug) echo  $sql1;

    $encounterNr=$bill_obj->getLastEncounterNo($pid);

    $sql3 = "update care_person set insurance_ID='$row[0]' where pid=$pid";
    if($debug) echo  $sql3;
    if ($db->Execute($sql3)) {
        echo '{success:true},';
    } else {
        echo '{success:false,errors:{pid:"Could not Update Pid Insurance"}},';
    }
    
    $sql4="update care_encounter set insurance_firm_id='$row[0]' where encounter_nr='$encounterNr'";   
    if($debug) echo $sql4;
    $db->Execute($sql4);   

    $sql2 = "update care_ke_billing set insurance_ID='$row[0]',status='billed' where pid='$pid' and status='pending'";
    if($debug) echo $sql2;
    if ($db->Execute($sql2)) {

        $sql3="INSERT INTO `care_ke_debtormembers`
            (`accno`,`PID`,`memberID`,`MemberNames`, `memberType`,`OP_Usage`,`IP_Usage`,`DOB`,`inputDate`,`inputUser`)
            SELECT c.`accno`,if(p.pid<>'',p.pid,c.accno) as pid,'$memberID',CONCAT(p.`name_first`,' ',p.`name_2`,' ',p.`name_last`) AS pnames,'Both','0','0'
            ,p.`date_birth`,p.`date_reg`,'".$user."' FROM care_person p
            LEFT JOIN care_tz_company c ON p.`insurance_ID`=c.`id`
            WHERE pid=$pid ";

        if($debug) echo $sql3;
        if($db->Execute($sql3)){
            echo '{success:true}';
        } else {
            echo '{success:false,errors:{billInsurance:"Could not update billing insurance"}}';
        }
    }
}

function removeMember() {
    global $db;
    $debug=false;

    $pid = $_POST[txtPid];
    $accno = $_POST[txtAccno];
    $user= $_SESSION['sess_user_name'];

    $sql1 = "select ID from care_tz_company where accno='$accno'";
    $result1 = $db->Execute($sql1);
    $row = $result1->FetchRow();
    //echo $sql1 . '<br>';



    $sql3 = "update care_person set insurance_ID='-1' where pid=$pid";
    if($debug) echo  $sql3;
    if ($db->Execute($sql3)) {

        echo '{success:true},';
    } else {
        echo '{success:false,errors:{pid:"Could not Update Pid Insurance"}},';
    }

    $sql2 = "update care_ke_billing set insurance_ID='',status='pending' where pid='$pid' and status='pending'";
    if($debug) echo $sql2;
    if ($db->Execute($sql2)) {

        $sql3="DELETE FROM `care_ke_debtormembers` WHERE pid=$pid AND accno='$accno'";

        if($debug) echo $sql3;
        if($db->Execute($sql3)){
            echo '{success:true}';
        } else {
            echo '{success:false,errors:{billInsurance:"Could not update billing insurance"}}';
        }
    }
}

function addDependants() {
    global $db;
    $pid = $_POST[pid];
    $accno = $_POST[accno];
    $memberID = $_POST[memberID];
    $Names = $_POST[names];
    $memberType = $_POST[memberType];

    $date1 = $_POST[dob];
    $date = new DateTime(date($date1));
    $DOB = $date->format("Y/m/d");

    $inputDate = date('Y-m-d');
    $inputUser = $_SESSION['sess_login_username'];

    $sql1 = "select ID from care_tz_company where accno='$accno'";
    $result1 = $db->Execute($sql1);
    $row = $result1->FetchRow();
//    echo $sql1 . '<br>';
//    $accno1=substr($accno,2);
    $sql = "INSERT INTO `care_ke_debtormembers`
            (`accno`,`memberID`,`PID`,`Names`,`memberType`,`OP_Usage`, `IP_Usage`,
             `DOB`,`inputDate`,`inputUser`)
         VALUES ('$accno','$memberID', '$pid','$Names','$memberType','0.00','0.00',
                '$DOB','$inputDate','$inputUser');";
    if ($db->Execute($sql)) {
        echo '{success:true}';
    } else {
        echo '{success:false,sql' . $sql . '}';
    }



    $sql3 = "update care_person set insurance_ID='$row[0]' where pid=$pid";
    if ($db->Execute($sql3)) {
        echo '{success:true}';
    } else {
        echo '{success:false,sql' . $sql3 . '}';
    }

    $sql2 = "update care_ke_billing set insurance_ID='$row[0]',status='billed' where pid=$pid and status='pending'";
    if ($db->Execute($sql2)) {
        echo '{success:true}';
    } else {
        echo '{success:false,sql' . $sql2 . '}';
    }
}

?>
