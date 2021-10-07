<?php
//echo var_dump($_POST);
require_once('roots.php');
require_once($root_path.'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path.'include/inc_init_xmlrpc.php');
$debug=false;

($debug) ? $db->debug=FALSE : $db->debug=FALSE;

$req_no=$_POST[ordIrnNo];
$issueNo=$_POST[issiuNo];
$store_loc=$_POST[storeID];
$store_desc=$_POST[storeDesc];
$sup_storeId=$_POST[supStoreid];
$supStoreDesc=$_POST[supStoredesc];
$period='2009';
$req_date=$_POST[ordDate];
$req_time=date("H:i:s");
$issue_date=date("Y-m-d");
$issue_time=date("H:i:s");

$input_user = $_SESSION['sess_login_username'];

//echo var_dump($_POST);
//echo 'rows '.$_POST[gridbox_rowsadded];

function InsertData($db,$rowid,$req_no,$issueNo,$status,$req_date,$req_time,$store_loc,
        $store_desc,$period,$input_user,$sup_storeId,$supStoreDesc,$issue_date,$issue_time) {
    $debug=false;

    $itemId=$_POST["gridbox_".$rowid."_0"];
    $item_Desc=$_POST["gridbox_".$rowid."_1"];
    $qty_issued=$_POST["gridbox_".$rowid."_4"];
    $qty=$_POST["gridbox_".$rowid."_2"];
    $bal=$_POST["gridbox_".$rowid."_5"];

    if($bal>0){
        $balance=intval($bal-$qty_issued);
    }else{
        $balance=intval($qty-$qty_issued);
    }

    
//    $Price=$_POST["gridbox_".$rowid."_3"];

   $csql="INSERT INTO care_ke_internalserv
              ( req_no,issueno,STATUS,req_date,req_time,store_loc,Store_desc,sup_storeId,sup_storeDesc,
                item_id,Item_desc,qty,qty_issued,period,input_user,balance,issue_date,issue_time)
	VALUES
                ('$req_no',
                '$issueNo',
                'serviced',
                '$req_date',
                '$req_time',
                '$store_loc',
                '$store_desc',
                '$sup_storeId',
                '$supStoreDesc',
                '$itemId',
                '$item_Desc',
                '$qty',
                '$qty_issued',
                '$period',
                '$input_user',
                '$balance',
                '$issue_date',
                '$issue_time'
                )";
    
    $result2=$db->Execute($csql);
    if($debug) echo $csql.'<br>';
   
    if($balance==0){
       $status='Serviced';
    }else if($balance>0){
        $status='Pending';
    }
    
     $sql3="update care_ke_internalreq set status='$status',qty_issued='$qty_issued',balance='$balance',
        issue_date='".date('Y-m-d')."',issue_time='".date('H:i:s')."' where req_no='$req_no' and item_id='$itemId'";
        $db->Execute($sql3);
        if($debug) echo $sql3;
}


function checkStoreType($loccode){
    global $db;
    $debug=false;
    $sql="select mainstore from care_ke_stlocation where st_id='$loccode'";
    if($debug) echo $sql;

    $request=$db->Execute($sql);
    $row=$request->FetchRow();
    return $row[0];
}

$sql3='SELECT count(id) as cnt FROM care_ke_internalreq where req_no="'.$req_no.'"';
$result2=$db->Execute($sql3);
$row=$result2->FetchRow();

if($debug) echo $sql3;

    for($i=1;$i<=$row[0];$i++) {
        InsertData($db,$i,$req_no,$issueNo,$status,$req_date,$req_time,$store_loc,
         $store_desc,$period,$input_user,$sup_storeId,$supStoreDesc,$issue_date,$issue_time); 
         
    }

$weberp_obj = new_weberp();
    
    $sql="SELECT * FROM care_ke_internalserv WHERE req_no=$req_no AND `status`='Serviced'";
    if($debug) echo $sql;

    $result=$db->Execute($sql);
    while($row=$result->FetchRow()){

        $pSdate=new DateTime( $row[req_date]);
        $pdateS=$pSdate->format('Y-m-d');
        $servDate= $pdateS;

//        echo ' Store is '.$sup_storeId;

//        if($sup_storeId=='GEN'){
            if($weberp_obj->stock_adjustment_in_webERP($row[item_id], $row[sup_storeId],$row[store_loc], $row[qty_issued], $servDate)=='failure') {
                //echo "failed to transmit $row[item_id] to weberp GL<br>";
            }else {
                // echo "transmitted $row[item_id] GLs successfully<br>";
            }
//        }
    }







