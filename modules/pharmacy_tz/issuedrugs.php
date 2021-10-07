<?php
$debug = true;

require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
require_once($root_path . 'include/care_api_classes/accounting.php');
require_once($root_path . 'include/care_api_classes/class_tz_insurance.php');
require_once($root_path . 'include/care_api_classes/class_person.php');

$insurance_obj = new Insurance_tz;
$person_obj = new Person;
$bill_obj = new Bill;


//($debug) ? $db->debug = FALSE : $db->debug = FALSE;

$issDate = $_POST[issDate];
echo $issDate;
$pid = $_POST[pid];
$pname = $_POST[pname];
$docId = $_POST[docID];
$docName = $_POST[docName];
$supStoreid = $_POST[storeID];
$supStoreDesc = $_POST[supStoredesc];
$period = date("Y");
$input_user = $_SESSION['sess_login_username'];
$total = $_POST[totalCost];
$enc_no = $_POST[enc_no];
$encClass = $_POST[issType];

//echo var_dump($_POST).'<br><br>';
//echo 'rows ' . $_POST[gridbox_rowsadded].'<br><br>';
//echo 'Update Value is '.$_POST[updatebill].'<br><br>';
//echo 'Submit Value is '.$_POST[submit].'<br><br>';
//echo 'The added Rows are '.$_POST[numRows].'<br><br>';

$insuranceid = $insurance_obj->Get_insuranceID_from_pid($pid);

$sql="SELECT MAX(order_no) as issNo FROM care_ke_internal_orders group by order_no";
echo $sql;
$result=$db->Execute($sql);
$row=$result->FetchRow();
$issNo = $row[issNo];

if($_POST[submit]=='Update'){
        $added_rows=$_POST[numRows];
        $arr_rows= explode(",", $added_rows);
        foreach ($arr_rows as $i) {
          // echo "the Price is ".$_POST["gridbox_".$i."_8"].'<br><br>';

             $dose=$_POST["gridbox_".$i."_4"];
              $times=$_POST["gridbox_".$i."_5"];
               $days=$_POST["gridbox_".$i."_6"];

            $price=$_POST["gridbox_".$i."_8"];
            $partcode=$_POST["gridbox_".$i."_2"];
            $prescNo=$i;
            $total=$_POST["gridbox_".$i."_11"];
            if($_POST["gridbox_".$i."_10"]<>''){
                $qty=$_POST["gridbox_".$i."_10"];
            }else{
                $qty=$_POST["gridbox_".$i."_9"];
            }
           //UpdateBillPrices($prescNo,$price,$partcode,$qty,$total,$dose,$times,$days);
        }
}else{
    $is_new_post = true;
        // is a previous session set for this form and is the form being posted
        if (isset($_SESSION["myform_key"]) && isset($_POST["myform_key"])) {
            // is the form posted and do the keys match
            if($_POST["myform_key"] == $_SESSION["myform_key"] ){
                $is_new_post = false;
            }
        }
        if($is_new_post){
            // register the session key variable
            $_SESSION["myform_key"] = $_POST["myform_key"];
            
        $sql = "SELECT a.nr,article_item_number,a.encounter_nr,b.encounter_class_nr,a.nr
                FROM care_encounter_prescription a
                INNER JOIN  care_encounter b
                ON a.encounter_nr=b.encounter_nr
                WHERE b.pid='$pid' AND b.encounter_class_nr='$encClass' AND a.drug_class in ('drug_list','Medical-Supplies')
                and a.status='pending'";
                $result = $db->Execute($sql);
                if ($debug)
                    echo $sql . '<br>';

                while ($row3 = $result->fetchRow()) {
                    insertData($db, $row3[0], $issNo, $issDate, $pid, $pname, $docId, $docName, $supStoreid, $supStoreDesc,
                                        $period, $total, $input_user, $row3[0],$enc_no,$insurance_obj,$bill_obj);

                    if ($encClass == 1 || $insuranceid > 0) {
                        //        createPhrmQuote($enc_no);
                        $bill_obj->updateFinalBill($enc_no,$row3[nr],$is_new_post);
                    }
                }

            $statusType="Doctors Room";
            $status="Drugs Issued to Patient in Pharmacy";
            $statusDesc="Drugs Issued to Patient in Pharmacy";
            $bill_obj->updatePatientStatus($enc_no,$issNo,$statusType,$status,$statusDesc,$currUser);


            $sql="update care_ke_transactionnos set transNo=transNo+1 where typeID='3'";
                if($debug) echo $sql;
                $db->Execute($sql);

        }
}

function updateBillPrices($prescNo,$price,$partcode,$qty,$total,$dose,$times,$days){
    global $db;
    $debug=false;

    $sql1="Update care_encounter_prescription set price='$price',dosage='$dose', times_per_day='$times',days='$days' where nr='$prescNo'";
    if($debug) echo $sql1;
    $db->Execute($sql1);

    $sql2="Update care_ke_billing set price='$price',qty='$qty',total='$total' where batch_no='$prescNo'";
    if($debug) echo $sql2;
    $db->Execute($sql2);

    $sql3="Update care_tz_drugsandservices set unit_price='$price' where partcode='$partcode'";
    if($debug) echo $sql3;
    $db->Execute($sql3);
}

$enc_no = $person_obj->CurrentEncounter($pid);

if (empty($enc_no)) {
    $enc_no = $person_obj->CurrentMaxEncounter($pid);
}

function insertData($db, $rowid, $issNo, $issDate, $pid, $pname, $docId, $docName, $supStoreid, $supStoreDesc, $period,
                    $total, $input_user, $nr,$enc_no,$encClass,$bill_obj)
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
    $desc=addslashes($item_Desc);
    $csql = "INSERT INTO care_ke_internal_orders
                (order_no,STATUS,order_date,order_time,order_type,store_loc,store_desc,adm_no,
                OP_no,patient_name,item_id,Item_desc,qty,price,unit_msr,unit_cost,issued,orign_qty,
                balance,period,input_user,total,presc_nr,weberpsync
                )
                VALUES
                ('$issNo', 'issued','" . $issDate. "','" . date("H:i:s") . "','cash sale','$supStoreid','$supStoreDesc',
                '','$pid','$pname','$itemId', '$desc','$qty','$price','each','$total','$qtyIssued','$qty',
                '$bal','$period','$input_user','$total','$nr','0' )";

    if ($debug)
        echo $csql . '<br>';
    if($db->Execute($csql)){
        reduceStock($db, $itemId, $supStoreid, $qtyIssued,$qty,$bal, $nr, $issNo);
//        updateStockERP($db,$itemId,'MAIN',$issDate,$qtyIssued,$bal,$nr);
        //updateBillERP($encClass,$pid,$enc_no,$bill_obj);
        updateStockMovement($itemId,$supStoreid,$pid,$price,$qtyIssued,$price);
        
        if ($bal > 0) {
            $stat = "pending";
        } else {
            $stat = "serviced";
        }

//        $sqlp = 'update care_encounter_prescription set  status="' . $stat . '",bill_status="billed",qty_balance="' . $bal
//                . '",bill_status="pending"  where
//                partcode="' . $itemId . '" AND `status` = "pending" and nr="' . $nr . '"';
//        $db->Execute($sqlp);
//        if ($debug)
//            echo $sqlp . '<br>';
        }

}

function updateStockMovement($stockid,$loccode,$pid,$price,$qty,$standardcost){
    require_once('roots.php');
    require_once('../../include/care_api_classes/class_tz_drugsandservices.php');
    $drugs_obj = new DrugsAndServices;

    getCurrentQty($stockid,$loccode);
    $currQty=getCurrentQty($stockid,$loccode);
    $newqoh=$currQty[quantity];
    
    $moveDetails[stockid]=$stockid;
    $moveDetails[type]=3;
    $moveDetails[loccode]=$loccode;
    $moveDetails[pid]=$pid;
    $moveDetails[price]=$price;
    $moveDetails[reference]='Drug issue to Patient PID '.$pid;
    $moveDetails[qty]=$qty;
    $moveDetails[standardcost]=$standardcost;
    $moveDetails[newqoh]=$newqoh;
    $moveDetails[narrative]='Drug issue to Patient PID '.$pid;   
    
   $drugs_obj->updateStockMovement($moveDetails); 
}

function getCurrentQty($partcode,$supStore){
    global $db;
    $debug=false;
  
    $sql1 = "select quantity from care_ke_locstock where stockid='$partcode' and loccode='$supStore'";
    $result2 = $db->Execute($sql1);
    if ($debug) echo $sql1 . '<br>';

    $row = $result2->FetchRow();

    return $row;
}

function updateStockERP($db, $partcode, $supStore, $issueDate,$qtyIssued,$bal,$presc_nr)
{
    $debug=false;
    $weberp_obj = new_weberp();
//    $itemId=$_POST["gridbox_".$rowid."_0"];

    $pSdate = new DateTime($issueDate);
    $pdateS = $pSdate->format('Y-m-d');
    $servDate = $pdateS;
    if ($weberp_obj->stock_adjustment_in_webERP($partcode, $supStore, 'Main', $qtyIssued, $servDate) == 'failure') {
        // echo "failed to transmit $row[item_id] to weberp GL<br>";
    } else {
        //echo "transmitted $row[item_id] GLs successfully<br>";
    }

    $accDB=$_SESSION['sess_accountingdb'];
    $sql1 = "select sum(quantity) as quantity from $accDB.locstock where stockid='$partcode'";
    $result2 = $db->Execute($sql1);
    if ($debug) echo $sql1 . '<br>';

    $row = $result2->FetchRow();
    $newQty = intval($row[0]) - intval($qtyIssued);

    if($newQty<1){
        $sql="Update care_tz_drugsandservices set item_status='2' where partcode='$partcode'";
        $db->Execute($sql);
    }else{
        $sql="Update care_tz_drugsandservices set item_status='1' where partcode='$partcode'";
        $db->Execute($sql);
    }

    if ($bal > 0) {
        $stat = "pending";
    } else {
        $stat = "serviced";
    }

    $sqlp = 'update care_encounter_prescription set  status="' . $stat . '",qty_balance="' . $bal
        . '",bill_status="pending"  where
        partcode="' . $partcode . '" AND `status` = "pending" and nr="' . $presc_nr . '"';
    $db->Execute($sqlp);
    if ($debug)
        echo $sqlp . '<br>';

}

function updateBillERP($encClass,$pid,$enc_no,$bill_obj){
    global $db,$insurance_obj;
    $debug=false;

    $insuranceid = $insurance_obj->Get_insuranceID_from_pid($pid);

    if ($encClass == 1 || $insuranceid > 0) {
//        createPhrmQuote($enc_no);
//        $bill_obj->updateFinalBill($enc_no);

        $sql = "SELECT a.OP_no AS pid, a.price,a.item_id AS partcode,a.item_desc AS article,a. order_date AS prescribe_date,a.order_no AS bill_number,
                a.unit_cost AS ovamount,store_loc AS salesArea,d.`category`
                FROM care_ke_internal_orders a LEFT JOIN care_tz_drugsandservices d ON a.`item_id`=d.`partcode`
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
}

function reduceStock($db, $stockid, $store, $qtyIssued,$OrigQty,$bal, $presc_nr, $order_no)
{
    $debug =false;
//    $sql3 = 'SELECT IF(qty_balance>0,qty_balance,(a.dosage*a.times_per_day*a.days)) AS qty FROM care_encounter_prescription a
//        where nr=' . $presc_nr;
//    $result3 = $db->Execute($sql3);
//    if ($debug)
//        echo $sql3 . '<br>';
//    $row3 = $result3->FetchRow();
//    $oldqty = $row3[0];
//    $balance = $oldqty - $qty;



//    $sql4 = 'update care_ke_internal_orders set orign_qty="' . $OrigQty . '",balance="' . $bal . '"
//        where item_id="' . $stockid . '" and order_no="' . $order_no . '"';
//    $db->Execute($sql4);
//    if ($debug)
//        echo $sql4 . '<br>';

    $sql1 = "select quantity from care_ke_locstock where stockid='$stockid' and loccode='$store'";
    $result2 = $db->Execute($sql1);
    if ($debug) echo $sql1 . '<br>';

    $row = $result2->FetchRow();
    $newQty = intval($row[0]) - intval($OrigQty);


    $sql = "update care_ke_locstock set quantity='$newQty' where stockid='$stockid' and loccode='$store'";
    $db->Execute($sql);
    if ($debug) echo $sql . '<br>';

    if ($bal > 0) {
        $stat = "pending";
    } else {
        $stat = "serviced";
    }

    //if($newQty<1){
     //   $sql="Update care_tz_drugsandservices set item_status='2' where partcode='$stockid'";
     //   $db->Execute($sql);
   // }

    $sqlp = "update care_encounter_prescription set  status='$stat',qty_balance='$bal',qtyIssued='$qtyIssued',bill_status='pending',posted='1'  where
        partcode='$stockid' AND `status` = 'pending' and nr='$presc_nr'";
    $db->Execute($sqlp);
    if ($debug)
        echo $sqlp . '<br>';

}

?>



