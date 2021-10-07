<?php

//echo var_dump($_POST);
$debug = true;
require_once($root_path . 'include/care_api_classes/class_tz_Billing.php');
require_once($root_path . 'include/care_api_classes/accounting.php');
require_once($root_path . 'include/care_api_classes/class_tz_insurance.php');
$insurance_obj = new Insurance_tz;

($debug) ? $db->debug = FALSE : $db->debug = FALSE;

$issDate = $_POST[issDate];
$issNo = $_POST[issNo];
$pid = $_POST[pid];
$pname = $_POST[pname];
$docId = $_POST[docID];
$docName = $_POST[docName];
$supStoreid = $_POST[storeID];
$supStoreDesc = $_POST[supStoredesc];
$period = '2009';
$input_user = $_SESSION['sess_login_username'];
$total = $_POST[totalCost];
$enc_no = $_POST[enc_no];
$encClass = $_POST[issType];

echo var_dump($_POST);
//echo 'rows ' . $_POST[gridbox_rowsadded];

$acc_obj = new accounting();

function insertData($db, $rowid, $issNo, $issDate, $pid, $pname, $docId, $docName, $supStoreid, $supStoreDesc,
        $period, $total, $input_user, $nr,$encClass) {
    $debug = true;

    $itemId = $_POST["gridbox_" . $rowid . "_0"];
    $item_Desc = $_POST["gridbox_" . $rowid . "_1"];
    $qty = $_POST["gridbox_" . $rowid . "_4"];
    $price = $_POST["gridbox_" . $rowid . "_3"];
    $total = $_POST["gridbox_" . $rowid . "_5"];

    $csql = "INSERT INTO care_ke_internal_orders
	(order_no,STATUS,order_date,order_time,order_type,store_loc,store_desc,adm_no,
	OP_no,patient_name,item_id,Item_desc,qty,price,unit_msr,unit_cost,
	balance,period,input_user,total,presc_nr
	)
	VALUES
	('$issNo',
	'issued',
	'" . date("Y-m-d") . "',
	'" . date("H:i:s") . "',
	'cash sale',
	'$supStoreid',
	'$supStoreDesc',
	'$encClass',
	'$pid',
	'$pname',
	'$itemId',
	'$item_Desc',
	'$qty',
	'$price',
	'',
	'$total',
	'0',
	'$period',
	'$input_user',
	'$total',
         '$nr'
	)";

    if ($debug)
        echo $csql . '<br>';
    $db->Execute($csql);

//    reduceStock($db,$itemId,$supStoreid,$qty);
    reduceStock($db, $itemId, $supStoreid, $qty, $nr, $issNo);
}

$result3 = $acc_obj->getEncounterDetails($pid);
$row = $result3->FetchRow();
if ($row['encounter_class_nr'] == '1') {
    $enc = 1;
} else if($row['encounter_class_nr'] == '2') {
    $enc = 2;
}else{
    $enc='error';
}
$sql = "SELECT a.nr,article_item_number,a.encounter_nr,b.encounter_class_nr
FROM care_encounter_prescription a
INNER JOIN  care_encounter b
ON a.encounter_nr=b.encounter_nr
WHERE b.pid='$pid' AND b.encounter_class_nr='$encClass' AND a.drug_class='drug_list' and a.status='pending'";
$result = $db->Execute($sql);
if ($debug)
    echo $sql . '<br>';

while ($row3 = $result->fetchRow()) {
//       echo '<br>added row '.$row3[1].'<br>';
    insertData($db, $row3[0], $issNo, $issDate, $pid, $pname, $docId, $docName, $supStoreid, $supStoreDesc, 
            $period, $total, $input_user, $row3[0],$encClass);

}
$insuranceid=$insurance_obj->Get_insuranceID_from_pid($pid);  
if ($enc == 1 || isset($insuranceid)) {
    createPhrmQuote($row['encounter_nr']);
    require_once($root_path . 'include/care_api_classes/accounting.php');

    $sql = 'select b.pid, a.price,a.partcode,a.Description as article,a.prescribe_date,a.bill_number,
                    (a.dosage*a.times_per_day*a.days) as ovamount,ledger as salesArea
                    from care_ke_billing a, care_encounter b
                    where a.encounter_nr=b.encounter_nr and a.encounter_nr="' . $row['encounter_nr'] . '" 
                        and service_type="drug_list" and a.weberpSync=0';
    $result = $db->Execute($sql);
    $weberp_obj = new_weberp();
    //$arr=Array();
    while ($row = $result->FetchRow()) {
        //$weberp_obj = new_weberp();
        if (!$weberp_obj->transfer_bill_to_webERP_asSalesInvoice($row)) {
             echo 'failed to transmit to weberp';
            
        } else {
           $sql = "update care_encounter_prescription set weberpSync=1 where weberpSync=0";
            $db->Execute($sql);
            $sql2 = "update care_ke_billing set weberpSync=1 where weberpSync=0";
            $db->Execute($sql2);
        }
        destroy_weberp($weberp_obj);
    }
}

function reduceStock($db, $stockid, $store, $qty, $presc_nr, $order_no) {

    $debug = true;
    $sql3 = 'SELECT IF(qty_balance>0,qty_balance,(a.dosage*a.times_per_day*a.days)) AS qty FROM care_encounter_prescription a where nr=' . $presc_nr;
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

    $sql1 = 'select quantity from care_ke_locstock where stockid="' . $stockid . '" and loccode="'.$store.'"';
    $result2 = $db->Execute($sql1);
    if ($debug)
        echo $sql1 . '<br>';
    $row = $result2->FetchRow();
    $newQty = intval($row[0]) - intval($qty);


    $sql = 'update care_ke_locstock set quantity="' . $newQty . '" where stockid="' . $stockid . '" and loccode="'.$store.'"';
    $db->Execute($sql);
    if ($debug)
        echo $sql . '<br>';

    if ($balance > 0) {
        $stat = "pending";
    } else {
        $stat = "serviced";
    }
    
    $sqlp = 'update care_encounter_prescription set  status="' . $stat . '",qty_balance="' . $balance . '" where 
        partcode="' . $stockid . '" AND `status` != "serviced" and nr="'.$presc_nr.'"';
    $db->Execute($sqlp);
    if ($debug)
        echo $sqlp . '<br>';
    
//     $sql2 = 'update care_ke_internalreq set status="serviced" where ';
//    $db->Execute($sql2);
//    if ($debug)
//        echo $sql2 . '<br>';
    //$row=$result->fetchRow();
}
?>



