<?php
//echo var_dump($_POST);
$debug=false;

require_once($root_path.'include/care_api_classes/accounting.php');
($debug) ? $db->debug=FALSE : $db->debug=FALSE;

$issDate=$_POST[issDate];
$returnNo=$_POST[returnNo];
$prescNO=$_POST[prescNO];
$pid=$_POST[patientId];
$pname=$_POST[pname];
//$docId=$_POST[docID];
//$docName=$_POST[docName];
$storeID=$_POST[storeID];
$supStoreDesc=$_POST[supStoredesc];
$period='2011';
$input_user = $_SESSION['sess_login_username'];
$total=$_POST[totalCost];
//$enc_no=$_POST[enc_no];
//echo var_dump($_POST);
//echo 'rows '.$_POST[gridbox_rowsadded];
$acc_obj = new accounting();

 

function insertData($db,$rowid,$returnNo,$issDate,$pid,$pname,$docId,$docName,$storeID,$supStoreDesc,$period,$total,
        $input_user,$prescNO,$enc_nr,$enc) {
    $debug=false;
    
    $issueNo=$_POST["gridbox_".$rowid."_0"];
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
	('$returnNo',
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
         '$prescNO'
	)";

    if($debug) echo $csql.'<br>';
    $db->Execute($csql);

//    reduceStock($db,$itemId,$storeID,$qty);
    adjustStock($db,$pid,$itemId,$itemDesc,$storeID,$qty,$nr,$returnNo,$enc_nr,$enc,$price,$input_user,$prescNO) ;


}


$result3=$acc_obj->getEncounterDetails($pid);
$row=$result3->FetchRow();
if($row['encounter_class_nr']=='1') {
    $enc=1;
}else {
    $enc=2;
}
$enc_nr=$row[encounter_nr];


$sql="SELECT ID,order_no,presc_nr,OP_no FROM care_ke_internal_orders WHERE OP_no=$pid AND presc_nr= $prescNO AND `status`='issued'";
$result=$db->Execute($sql);
if($debug) echo $sql.'<br>';
if(strstr($_POST[gridbox_rowsadded],",")) {
    $added_rows=$_POST[gridbox_rowsadded];
    $arr_rows= explode(",", $added_rows);
    for($i=0;$i<count($arr_rows);$i++) {
//        echo '<br>added row '.$arr_rows[$i].'<br>';
        insertData($db,$arr_rows[$i],$returnNo,$issDate,$pid,$pname,$docId,$docName,$storeID,$supStoreDesc,$period,$total,
                $input_user,$prescNO,$enc_nr,$enc);
    }
}else if($result) {
    while($row3=$result->fetchRow()) {
//       echo '<br>added row '.$row3[1].'<br>';
        insertData($db,$row3[0],$returnNo,$issDate,$pid,$pname,$docId,$docName,$storeID,$supStoreDesc,$period,$total,
                $input_user,$prescNO,$enc_nr,$enc);
//        createPhrmQuote($row3['encounter_nr']);
    }

}else {
    insertData($db,$_POST[gridbox_rowsadded],$returnNo,$issDate,$pid,$pname,$docId,$docName,$storeID,$supStoreDesc,$period,
            $total,$input_user,$prescNO,$enc_nr,$enc);
//createPhrmQuote($row['encounter_nr']);
}


function adjustStock($db,$pid,$itemId,$itemDesc,$storeID,$qty,$nr,$returnNo,$enc_nr,$enc,$price,$input_user,$prescNO,
        $returnNo) {
    require('roots.php');
    require_once($root_path.'include/care_api_classes/class_tz_Billing.php');
    $bill_obj=new Bill();
    $debug=false;

      $sql='select quantity from care_ke_locstock where stockid="'.$itemId.'" and loccode="'.$storeID.'"';
      $result= $db->Execute($sql);
      $row=$result->FetchRow();
      $currqty=$row[0];
      $newQty=intval($currqty+$qty);
      
      $sql='update care_ke_locstock set quantity="'.$newQty.'" where stockid="'.$itemId.'" and loccode="'.$storeID.'"';
    $db->Execute($sql);
    if($debug) echo $sql.'<br>';
    
   $new_bill_number = $bill_obj->checkBillEncounter($enc_nr);
    
    $sql = "INSERT INTO care_ke_billing (pid, encounter_nr,bill_date,bill_time,`ip-op`,bill_number,
            service_type, price,`Description`,notes,prescribe_date,input_user,item_number,
            partcode,qty,total,rev_code,ledger)
            value('".$pid."','".$enc_nr."','".date("y-m-d")."','".date("H:m:s")."','".$enc."','"
            .$new_bill_number."','Drug_List','".$price."','Drugs_Return','$itemDesc return No".$returnNo."','".date("y-m-d")
            ."','$inputUser','$itemId','$itemId','$qty','-".$price*$qty."','$itemId','25')";
    $db->Execute($sql);
    if($debug) echo $sql.'<br>';
}



?>



