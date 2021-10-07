<?php
//echo var_dump($_POST);
$debug=false;
($debug) ? $db->debug=FALSE : $db->debug=FALSE;

$req_no=$_POST[ordIrnNo];
$status='pending';
$req_date=date("Y-m-d");
$req_time=date("H:i:s");
$store_loc=$_POST[storeID];
$store_desc=$_POST[storeDesc];
$sup_storeId=$_POST[supStoreid];
$supStoreDesc=$_POST[supStoredesc];
$period='2009';
$input_user = $_SESSION['sess_login_username'];
echo var_dump($_POST);
echo 'rows '.$_POST[gridbox_rowsadded];

function insertData($db,$rowid,$req_no,$status,$req_date,$mydate,$req_time,$store_loc,
        $store_desc,$period,$input_user,$sup_storeId,$supStoreDesc) {
    $debug=false;

    $itemId=$_POST["gridbox_".$rowid."_0"];
    $item_Desc=$_POST["gridbox_".$rowid."_1"];
    $qty=$_POST["gridbox_".$rowid."_3"];
//    $Price=$_POST["gridbox_".$rowid."_3"];

    $csql="INSERT INTO care_ke_internalreq
              ( req_no,STATUS,req_date,req_time,store_loc,Store_desc,sup_storeId,sup_storeDesc,
                item_id,Item_desc,qty,period,input_user)
	VALUES
                ('$req_no',
                '$status',
                '$req_date',
                '$req_time',
                '$store_loc',
                '$store_desc',
                '$sup_storeId',
                '$supStoreDesc',
                '$itemId',
                '$item_Desc',
                '$qty',
                '$period',
                '$input_user'
                );";

    if($debug) echo $csql;
    $result2=$db->Execute($csql);

}


if(strstr($_POST[gridbox_rowsadded],",")) {
    $added_rows=$_POST[gridbox_rowsadded];
    $arr_rows= explode(",", $added_rows);
    for($i=0;$i<count($arr_rows);$i++) {
        echo '<br>added row '.$arr_rows[$i].'<br>';
        insertData($db,$arr_rows[$i],$req_no,$status,$req_date,$mydate,$req_time,$store_loc,
                $store_desc,$period,$input_user,$sup_storeId,$supStoreDesc);
    }
}else{
     insertData($db,$_POST[gridbox_rowsadded],$req_no,$status,$req_date,$mydate,$req_time,$store_loc,
                $store_desc,$period,$input_user,$sup_storeId,$supStoreDesc);
}
//    $bill_obj->updateCashErp($db,$patientno);
//updateCashErp($db,$patientno);
//updateBill($db,$_POST[gridbox_rowsadded],$patientno);









?>



