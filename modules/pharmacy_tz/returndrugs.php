<?php
//echo var_dump($_POST);
$debug=false;

require_once($root_path.'include/care_api_classes/accounting.php');
require_once($root_path.'include/care_api_classes/class_tz_Billing.php');
$bill_obj=new Bill();
($debug) ? $db->debug=FALSE : $db->debug=FALSE;

$issDate=$_POST[issDate];
$orderNo=$_POST[order_no];
$prescNO=$_POST[prescNO];
$pid=$_POST[patientId];
$pname=$_POST[pname];
//$docId=$_POST[docID];
//$docName=$_POST[docName];
$storeID=$_POST[storeID];
$supStoreDesc=$_POST[supStoredesc];
$period=date("Y");
$input_user = $_SESSION['sess_login_username'];
$total=$_POST[totalCost];
//$enc_no=$_POST[enc_no];
//echo var_dump($_POST);
//echo 'rows '.$_POST[gridbox_rowsadded];
$acc_obj = new accounting();

//echo var_dump($_POST);

function insertData($db,$rowid,$orderNo,$issDate,$pid,$pname,$docId,$docName,$storeID,$supStoreDesc,$period,$total,
        $input_user,$prescNO,$enc_nr,$enc,$bill_obj) {

    $debug=false;
    
    $issueNo=$_POST["gridbox_".$rowid."_0"];
    $prescNo=$_POST["gridbox_".$rowid."_1"];
    $itemId=$_POST["gridbox_".$rowid."_2"];
    $item_Desc=$_POST["gridbox_".$rowid."_3"];
    $qty=$_POST["gridbox_".$rowid."_5"];
    $price=$_POST["gridbox_".$rowid."_4"];
    $total=$_POST["gridbox_".$rowid."_6"];

    $csql="INSERT INTO care_ke_internal_returns
	(return_no,return_date,return_time,return_type,store_loc,store_desc,
	OP_no,patient_name,item_id,Item_desc,qty,price,total,period,input_user,presc_nr
	)
	VALUES
	('$issueNo',
	'".date("Y-m-d")."',
	'".date("H:i:s")."',
	'return',
	'$storeID',
	'$supStoreDesc',
	'$pid',
	'$pname',
	'$itemId',
	'$item_Desc',
	'$qty',
	'$price',
	'$total',
	'$period',
	'$input_user',
        '$prescNo'
	)";

    if($debug) echo $csql.'<br>';
    if($db->Execute($csql)){
        $new_bill_number = $bill_obj->checkBillEncounter($enc_nr);
        $sql = "INSERT INTO care_ke_billing (pid, encounter_nr,bill_date,bill_time,`ip-op`,bill_number,
            service_type, price,`Description`,notes,prescribe_date,input_user,item_number,
            partcode,qty,total,rev_code,ledger)
            value('".$pid."','".$enc_nr."','".date("y-m-d")."','".date("H:i:s")."','".$enc."','"
            .$new_bill_number."','Drug_List','".$price."','$item_Desc(Drugs_Return)','$item_Desc return No".$issueNo."','".date("Y-m-d")
            ."','$input_user','$itemId','$itemId','$qty','-".$price*$qty."','$itemId','Dispens')";
        $db->Execute($sql);
        if($debug) echo $sql.'<br>';

        adjustStock($db,$pid,$itemId,$item_Desc,$storeID,$qty,$prescNO,$issueNo,$enc_nr,$enc,$price,$input_user,$prescNO) ;
		updateStockMovement($itemId,$storeID,$pid,$price,$qty,$price);
    }


}


$result3=$acc_obj->getEncounterDetails($pid);
$row=$result3->FetchRow();
if($row['encounter_class_nr']=='1') {
    $enc=1;
}else {
    $enc=2;
}
$enc_nr=$row[encounter_nr];


$sql="SELECT ID,order_no,presc_nr,OP_no,store_loc,store_desc,item_id,item_desc,qty,issued,balance  FROM care_ke_internal_orders WHERE OP_no=$pid AND order_no= $orderNo AND `status`='issued'";
$result=$db->Execute($sql);
if($debug) echo $sql.'<br>';
if(isset($_POST[gridbox_rowsadded])) {
    $added_rows=$_POST[gridbox_rowsadded];
    $arr_rows= explode(",", $added_rows);
    //echo $arr_rows;
    for($i=0;$i<count($arr_rows);$i++) {
        //echo '<br>added row '.$arr_rows[$i].'<br>';
        insertData($db,$arr_rows[$i],$orderNo,$issDate,$pid,$pname,$docId,$docName,$storeID,$supStoreDesc,$period,$total,
                $input_user,$prescNO,$enc_nr,$enc,$bill_obj);
    }
}else if($result) {
    while($row3=$result->fetchRow()) {
            insertData($db, $arr_rows[$i], $orderNo, $issDate, $pid, $pname, $row3[item_id], $row3[store_loc], $row3[item_desc], $row3[store_desc], $period, $total,
                $input_user, $prescNO, $enc_nr, $enc,$bill_obj);

//        createPhrmQuote($row3['encounter_nr']);
    }

}else {
    insertData($db,$_POST[gridbox_rowsadded],$orderNo,$issDate,$pid,$pname,$docId,$docName,$storeID,$supStoreDesc,$period,
            $total,$input_user,$prescNO,$enc_nr,$enc,$bill_obj);
//createPhrmQuote($row['encounter_nr']);
}
$sql="update care_ke_transactionnos set transNo=transNo+1 where typeID='6'";
if($debug) echo $sql;
$db->Execute($sql);


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
    $moveDetails[reference]='Drugs Return from Patient PID '.$pid;
    $moveDetails[qty]=$qty;
    $moveDetails[standardcost]=$standardcost;
    $moveDetails[newqoh]=$newqoh;
    $moveDetails[narrative]='Drugs Return from Patient PID '.$pid; 
    
   $drugs_obj->updateStockMovement($moveDetails); 
}

function getCurrentQty($partcode,$supStore){
    global $db;
    $debug=false;
    $accDB=$_SESSION['sess_accountingdb'];
    $sql1 = "select quantity from care_ke_locstock where stockid='$partcode' and loccode='$supStore'";
    $result2 = $db->Execute($sql1);
    if ($debug) echo $sql1 . '<br>';

    $row = $result2->FetchRow();

    return $row;
}

function adjustStock($db,$pid,$itemId,$itemDesc,$storeID,$qty,$nr,$orderNo,$enc_nr,$enc,$price,$input_user,$prescNO,
        $returnNo) {
    require('roots.php');

    $debug=false;

      $sql='select quantity from care_ke_locstock where stockid="'.$itemId.'" and loccode="'.$storeID.'"';
      if($debug) echo $sql;
      $result= $db->Execute($sql);
      $row=$result->FetchRow();
      $currqty=$row[0];
      $newQty=intval($currqty+$qty);
      
      $sql='update care_ke_locstock set quantity="'.$newQty.'" where stockid="'.$itemId.'" and loccode="'.$storeID.'"';
    $db->Execute($sql);
    if($debug) echo $sql.'<br>';

    $sql2="update care_ke_internal_orders set qty= qty-$qty,issued=issued-$qty,notes='tock updated via item returns' 
          where item_id='$itemId' and order_no='$orderNo'";
    $db->Execute($sql2);
    if($debug) echo $sql2.'<br>';
    //qty,issued

    

}



?>



