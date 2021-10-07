<?php

//echo var_dump($_POST);
$debug = false;
require_once($root_path . 'include/care_api_classes/class_tz_Billing.php');
require_once($root_path . 'include/care_api_classes/accounting.php');
require_once($root_path . 'include/care_api_classes/class_tz_insurance.php');
require_once($root_path . 'include/care_api_classes/class_person.php');
$insurance_obj = new Insurance_tz;
$person_obj = new Person;
$bill_obj = new Bill;
($debug) ? $db->debug = FALSE : $db->debug = FALSE;

$issDate = $_POST[issDate];
$issNo = $_POST[issNo];
$pid = $_POST[pid];
$pname = $_POST[pname];
$docId = $_POST[docID];
$docName = $_POST[docName];
$supStoreid = $_POST[storeID];
$supStoreDesc = $_POST[supStoredesc];
$period = date("Y");
$input_user = $_SESSION['sess_login_username'];
$total = $_POST[totalCost];
//$enc_no = $_POST[enc_no];
$encClass = $_POST[issType];

//echo var_dump($_POST);
//echo 'rows ' . $_POST[gridbox_rowsadded];

//$acc_obj = new accounting();

$enc_no = $person_obj->CurrentEncounter($pid);

if (empty($enc_no)) {
    $enc_no = $person_obj->CurrentMaxEncounter($pid);
}

function insertData($db, $rowid, $issNo, $issDate, $pid, $pname, $docId, $docName, $supStoreid, $supStoreDesc, $period,
                    $total, $input_user, $nr)
{
    $debug = false;
    $itemNumber = $_POST["gridbox_" . $rowid . "_0"];
    $prescribeDate = $_POST["gridbox_" . $rowid . "_1"];
    $itemId = $_POST["gridbox_" . $rowid . "_2"];
    $item_Desc = $_POST["gridbox_" . $rowid . "_3"];
    $qty = $_POST["gridbox_" . $rowid . "_9"];
    $price = $_POST["gridbox_" . $rowid . "_8"];
    $issued=$_POST["gridbox_". $rowid ."_10"];
    $total = $_POST["gridbox_" . $rowid . "_11"];

    if($issued<>""){
        $qtyIssued=$issued;
    }else{
        $qtyIssued=$qty;
    }

    $bal=$qty-$qtyIssued;

    //if ($itemNumber == 1) {
    $csql = "INSERT INTO care_ke_internal_orders
	(order_no,STATUS,order_date,order_time,order_type,store_loc,store_desc,adm_no,
	OP_no,patient_name,item_id,Item_desc,qty,price,unit_msr,unit_cost,issued,orign_qty,
	balance,period,input_user,total,presc_nr,weberpsync
	)
	VALUES
	('$issNo',
	'issued',
	'" . date("Y-m-d") . "',
	'" . date("H:i:s") . "',
	'cash sale',
	'$supStoreid',
	'$supStoreDesc',
	'',
	'$pid',
	'$pname',
	'$itemId',
	'$item_Desc',
	'$qty',
	'$price',
	'each',
	'$total',
	'$qtyIssued',
	'$qty',
	'$bal',
	'$period',
	'$input_user',
	'$total',
         '$nr','0'
	)";

    if ($debug)
        echo $csql . '<br>';
    if($db->Execute($csql)){
        reduceStock($db, $itemId, $supStoreid, $qtyIssued, $nr, $issNo);
       // updateStockERP($db,$itemId,'MAIN',$issued,date("d-m-Y"),$nr);
    }

}

function updateStockERP($db, $partcode, $supStore, $qty, $issueDate,$presc_nr)
{
    $debug=false;
    $weberp_obj = new_weberp();
//    $itemId=$_POST["gridbox_".$rowid."_0"];

    $pSdate = new DateTime($issueDate);
    $pdateS = $pSdate->format('Y-m-d');
    $servDate = $pdateS;
    if ($weberp_obj->stock_adjustment_in_webERP($partcode, $supStore, 'Main', $qty, $servDate) == 'failure') {
        // echo "failed to transmit $row[item_id] to weberp GL<br>";
    } else {
        //echo "transmitted $row[item_id] GLs successfully<br>";
    }

    $accDB=$_SESSION['sess_accountingdb'];
    $sql1 = "select quantity from $accDB.locstock where stockid='$partcode' and loccode='$supStore'";
    $result2 = $db->Execute($sql1);
    if ($debug) echo $sql1 . '<br>';

    $row = $result2->FetchRow();
    $newQty = intval($row[0]) - intval($qty);

    $sql3 = 'SELECT IF(qty_balance>0,qty_balance,(a.dosage*a.times_per_day*a.days)) AS qty FROM care_encounter_prescription a
        where nr=' . $presc_nr;
    $result3 = $db->Execute($sql3);
    if ($debug)
        echo $sql3 . '<br>';
    $row3 = $result3->FetchRow();
    $oldqty = $row3[0];
    $balance = $oldqty - $qty;


    if($newQty<1){
        $sql="Update care_tz_drugsandservices set item_status='2' where partcode='$partcode'";
        $db->Execute($sql);
    }else{
        $sql="Update care_tz_drugsandservices set item_status='1' where partcode='$partcode'";
        $db->Execute($sql);
    }

    if ($balance > 0) {
        $stat = "pending";
    } else {
        $stat = "serviced";
    }

    $sqlp = 'update care_encounter_prescription set  status="' . $stat . '",qty_balance="' . $balance
        . '",bill_status="pending"  where
        partcode="' . $partcode . '" AND `status` = "pending" and nr="' . $presc_nr . '"';
    $db->Execute($sqlp);
    if ($debug)
        echo $sqlp . '<br>';

}

$sql = "SELECT a.nr,article_item_number,a.encounter_nr,b.encounter_class_nr
FROM care_encounter_prescription a
INNER JOIN  care_encounter b
ON a.encounter_nr=b.encounter_nr
WHERE b.pid='$pid' AND b.encounter_class_nr='$encClass' AND a.drug_class in ('drug_list','Medical-Supplies')
and a.status='pending'";
$result = $db->Execute($sql);
if ($debug)
    echo $sql . '<br>';
//
while ($row3 = $result->fetchRow()) {
//       echo '<br>added row '.$row3[1].'<br>';
    insertData($db, $row3[0], $issNo, $issDate, $pid, $pname, $docId, $docName, $supStoreid, $supStoreDesc, $period, $total, $input_user, $row3[0]);
}

$insuranceid = $insurance_obj->Get_insuranceID_from_pid($pid);
//$IS_PATIENT_INSURED = $insurance_obj->is_patient_insured($pid);
if ($encClass == 1 || $insuranceid > 0) {
    createPhrmQuote($enc_no);
    // $bill_obj->updateDebtorsTrans($pid,"2",$enc_no);

    $sql = "SELECT a.OP_no AS pid, a.price,a.item_id AS partcode,a.item_desc AS article,a. order_date AS prescribe_date,a.order_no AS bill_number,
                a.unit_cost AS ovamount,store_loc AS salesArea
                FROM care_ke_internal_orders a
                WHERE a.Op_no='$pid' and weberpsync=0";
    $result = $db->Execute($sql);
    if ($debug)
        echo $sql;
    //$arr=Array();
    while ($row = $result->FetchRow()) {
        if ($weberp_obj = new_weberp()) {
            if (!$weberp_obj->transfer_bill_to_webERP_asSalesInvoice($row)) {
                $sql = "update care_ke_internal_orders set weberpSync=1 where weberpSync=0 and order_no='$row[bill_number]'";
                //echo $sql;
                $db->Execute($sql);

            } else {
                //echo "Failed to transmit item_ID --$row[partcode]--$row[article]  to weberp GL: Check GL Linkage<br>";
            }
            destroy_weberp($weberp_obj);
        }
    }

}
//

function reduceStock($db, $stockid, $store, $qty, $presc_nr, $order_no)
{

    $debug =false;
    $sql3 = 'SELECT IF(qty_balance>0,qty_balance,(a.dosage*a.times_per_day*a.days)) AS qty FROM care_encounter_prescription a
        where nr=' . $presc_nr;
    $result3 = $db->Execute($sql3);
    if ($debug)
        echo $sql3 . '<br>';
    $row3 = $result3->FetchRow();
    $oldqty = $row3[0];
    $balance = $oldqty - $qty;

    $sql4 = 'update care_ke_internal_orders set orign_qty="' . $oldqty . '",balance="' . $balance . '"
        where item_id="' . $stockid . '" and order_no="' . $order_no . '"';
    $db->Execute($sql4);
    if ($debug)
        echo $sql4 . '<br>';

    $sql1 = 'select quantity from care_ke_locstock where stockid="' . $stockid . '" and loccode="' . $store . '"';
    $result2 = $db->Execute($sql1);
    if ($debug) echo $sql1 . '<br>';

    $row = $result2->FetchRow();
    $newQty = intval($row[0]) - intval($qty);


    $sql = 'update care_ke_locstock set quantity="' . $newQty . '" where stockid="' . $stockid . '" and loccode="' . $store . '"';
    $db->Execute($sql);
    if ($debug) echo $sql . '<br>';

    if ($balance > 0) {
        $stat = "pending";
    } else {
        $stat = "serviced";
    }

    if($newQty<1){
        $sql="Update care_tz_drugsandservices set item_status='2' where partcode='$stockid'";
        $db->Execute($sql);
    }

    $sqlp = 'update care_encounter_prescription set  status="' . $stat . '",qty_balance="' . $balance
        . '",bill_status="pending"  where
        partcode="' . $stockid . '" AND `status` = "pending" and nr="' . $presc_nr . '"';
    $db->Execute($sqlp);
    if ($debug)
        echo $sqlp . '<br>';

}

?>



