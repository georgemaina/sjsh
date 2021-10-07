<?php
require('roots.php');
require_once($root_path . 'include/care_api_classes/class_tz_Billing.php');
require_once($root_path . 'include/care_api_classes/accounting.php');
require_once($root_path . 'include/care_api_classes/class_tz_insurance.php');
require_once($root_path . 'include/care_api_classes/class_person.php');
$insurance_obj = new Insurance_tz;
$person_obj = new Person;
$bill_obj = new Bill;
($debug) ? $db->debug = FALSE : $db->debug = FALSE;


$input_user = $_SESSION['sess_login_username'];

$enc_no = $person_obj->CurrentEncounter($pid);

if (empty($enc_no)) {
    $enc_no = $person_obj->CurrentMaxEncounter($pid);
}

function insertData($db, $issNo, $issDate, $pid, $pname, $input_user, $nr,$price,$qty,$partCode,$itemDesc,$totalCost)
{
    $debug = true;

        //if ($itemNumber == 1) {
    $csql = "INSERT INTO care_ke_internal_orders
	(order_no,STATUS,order_date,order_time,order_type,store_loc,store_desc,adm_no,
	OP_no,patient_name,item_id,Item_desc,qty,price,unit_msr,unit_cost,issued,orign_qty,
	balance,period,input_user,total,presc_nr,weberpsync
	)
	VALUES
	('$issNo',
	'issued',
	'$issDate',
	'" . date("H:i:s") . "',
	'cash sale',
	'DISPENS',
	'PHARMACY',
	'',
	'$pid',
	'$pname',
	'$partCode',
	'$itemDesc',
	'$qty',
	'$price',
	'each',
	'$price',
	'$qty',
	'$qty',
	'0',
	'2016',
	'$input_user',
	'$totalCost',
    '$nr','0'
	)";

    if ($debug)
        echo $csql . '<br>';
    if($db->Execute($csql)){
        reduceStock($db, $partCode, 'DISPENS', $qty, $nr, $issNo);
        // updateStockERP($db,$itemId,'MAIN',$issued,date("d-m-Y"),$nr);
    }

}

function getNewIssueNo(){
    global $db;

    $sql="SELECT MAX(order_no) AS orderNO FROM care_ke_internal_orders";
    $result=$db->Execute($sql);
    $row=$result->FetchRow();

    return $row[0];
}

$sql = "SELECT a.nr,a.`partcode`,a.`article`,b.`pid`,a.encounter_nr,b.encounter_class_nr,a.`prescribe_date`,
        a.`price`,a.`drug_class`,(a.`dosage`*a.times_per_day*a.`days`) AS qty,
        CONCAT(p.`name_first`,' ',p.`name_last`,' ',p.`name_2`) AS pname
        FROM care_encounter_prescription a
        INNER JOIN  care_encounter b ON a.encounter_nr=b.encounter_nr
        INNER JOIN care_person p ON b.`pid`=p.`pid`
        WHERE  prescribe_date BETWEEN '2016-08-01' AND '2016-08-04'
        AND drug_class IN('Drug_list','MEDICAL-SUPPLIES') and a.status='pending'";
$result = $db->Execute($sql);
//if ($debug)
    echo $sql . '<br>';
//


while ($row = $result->fetchRow()) {

    $issNo=getNewIssueNo();
    $totalCost=$row[qty]*$row[price];

    insertData($db, $issNo, $row[prescribe_date],$row[pid],$row[pname],$input_user,$row[0],$row[price],$row[qty],$row[partcode],
        $row[article],$totalCost);

    $insuranceid = $insurance_obj->Get_insuranceID_from_pid($row[pid]);
//    if ($encClass == 1 || $insuranceid > 0) {
//        createPhrmQuote($row[encounter_nr]);
//    }
}

//

function reduceStock($db, $stockid, $store, $qty, $presc_nr, $order_no)
{

    $debug =true ;
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