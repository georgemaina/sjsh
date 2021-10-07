<?php

//echo var_dump($_POST);
require_once('roots.php');
require_once($root_path . 'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path . 'include/inc_init_xmlrpc.php');
require 'roots.php';
require $root_path . 'include/care_api_classes/class_tz_drugsandservices.php';
$drugs_obj = new DrugsAndServices;
$debug = false;

($debug) ? $db->debug = FALSE : $db->debug = FALSE;

$req_no = $_POST['ordIrnNo'];
$issueNo = $_POST[issiuNo];
$store_loc = $_POST[storeID];
$store_desc = $_POST[storeDesc];
$sup_storeId = $_POST[supStoreid];
$supStoreDesc = $_POST[supStoredesc];
$period = '2009';
$req_date = $_POST[ordDate];
$req_time = date("H:i:s");
$issue_date = date("Y-m-d");
$issue_time = date("H:i:s");

$input_user = $_SESSION['sess_login_username'];
$accDB=$_SESSION['sess_accountingdb'];
$weberp_obj = new_weberp();
echo var_dump($_POST);
echo 'rows '.$_POST[gridbox_rowsadded];



function InsertData($db, $rowid, $req_no, $issueNo, $status, $req_date, $req_time, $store_loc, $store_desc, $period,
                    $input_user, $sup_storeId, $supStoreDesc, $issue_date, $issue_time, $drugs_obj,$weberp_obj) {
    $debug = true;

    $itemId = $_POST["gridbox_" . $rowid . "_0"];
    $item_Desc = $_POST["gridbox_" . $rowid . "_1"];
    $unitQty = $_POST["gridbox_" . $rowid . "_2"];
    $unitsPerpack = $_POST["gridbox_" . $rowid . "_3"];
    $qty = $_POST["gridbox_" . $rowid . "_4"];
    $qty_issued = $_POST["gridbox_" . $rowid . "_5"];
    $bal = $_POST["gridbox_" . $rowid . "_6"];
    $totalUnits = $_POST["gridbox_" . $rowid . "_8"];

    if ($bal > 0) {
        $balance = intval($bal - $qty_issued);
    } else {
        $balance = intval($qty - $qty_issued);
    }


//    $Price=$_POST["gridbox_".$rowid."_3"];
    $desc = addslashes($item_Desc);
    $csql = "INSERT INTO care_ke_internalserv
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
                '$desc',
                '$qty',
                '$qty_issued',
                '$period',
                '$input_user',
                '$balance',
                '$issue_date',
                '$issue_time'
                )";

    $result2 = $db->Execute($csql);
    if ($debug)
        echo $csql . '<br>';

    $sql1 = 'select quantity from care_ke_locstock where stockid="' . $itemId . '" and loccode="' . $store_loc . '"';
    $result = $db->Execute($sql1);
    if ($debug)
        echo $sql1;
    $row = $result->fetchRow();
    $newQty = intval($row[0]) + intval($qty_issued);

    if (checkStoreType($sup_storeId) == 1) {
        $sql2 = "Update care_ke_locstock set quantity=quantity+$totalUnits where stockid='$itemId' and loccode='$store_loc'";
        $db->Execute($sql2);
        if ($debug)
            echo $sql2;
    }

    if ($sup_storeId == 'MAIN' || sup_storeId == 'GEN') {
       // $weberp_obj->stock_adjustment_in_webERP($itemId, $sup_storeId, $store_loc, $qty_issued, date('Y-m-d'));
        StockAdjustment($itemId, $store_loc,$sup_storeId,$qty_issued, date('Y-m-d'));
    } else {
        reduceStock($db, $itemId, $sup_storeId, $qty_issued, $req_no);
    }

    $sql3 = "update care_ke_internalreq set status='Serviced',qty_issued='$qty_issued',balance='$balance',
        issue_date='" . date('Y-m-d') . "',issue_time='" . date('H:i:s') . "' where req_no='$req_no' and item_id='$itemId'";
    $db->Execute($sql3);
    if ($debug)
        echo $sql3;

    updateStockMovement($itemId, $sup_storeId, $store_loc, $qty, $input_user, $drugs_obj);


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

function updateStockMovement($stockid, $sup_storeId, $store_loc, $qty, $input_user, $drugs_obj) {
    getCurrentQty($stockid, $store_loc);
    $currQty = getCurrentQty($stockid, $store_loc);
    $newqoh = $currQty[quantity];

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

    $drugs_obj->updateStockMovement($moveDetails);
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

    $is_new_post = true;
// is a previous session set for this form and is the form being posted
if (isset($_SESSION["myform_key"]) && isset($_POST["myform_key"])) {
    // is the form posted and do the keys match
    if($_POST["myform_key"] == $_SESSION["myform_key"]){
        $is_new_post = false;
    }
}

//echo $_SESSION["myform_key"]);

if($is_new_post) {
	
	 $_SESSION["myform_key"] = $_POST["myform_key"];
	
	$sql3 = 'SELECT count(id) as cnt FROM care_ke_internalreq where req_no="' . $req_no . '"';
    $result2 = $db->Execute($sql3);
    $row = $result2->FetchRow();

    if ($debug)
        echo $sql3;

    for ($i = 1; $i <= $row[0]; $i++) {
//        echo '<br>added row '.$arr_rows[$i].'<br>';
        InsertData($db, $i, $req_no, $issueNo, $status, $req_date, $req_time, $store_loc, $store_desc, $period,
                    $input_user, $sup_storeId, $supStoreDesc, $issue_date, $issue_time, $drugs_obj,$weberp_obj);
    }
    $sql = "update care_ke_transactionnos set transNo=transNo+1 where typeID='4'";
    if ($debug)
        echo $sql;
    $db->Execute($sql);

}

function reduceStock($db, $stockid, $store, $qty, $req_no) {
    global $db;
    $debug = false;
    $sql1 = "select quantity from care_ke_locstock where stockid='$stockid' and loccode='$store'";
    $result2 = $db->Execute($sql1);
    if ($debug)
        echo $sql1 . '<br>';

    $row = $result2->FetchRow();
    $newQty = intval($row[0]) - intval($qty);


    $sql = "update care_ke_locstock set quantity='$newQty' where stockid='$stockid' and loccode='$store'";
    $db->Execute($sql);
    if ($debug)
        echo $sql . '<br>';

}



/////////////////////////////////////////////////////////////////////////////////////
/// WEBERP Updates
///
function getSalesArea($orderingLoc){
    global $db,$accDB;

    $sql = "SELECT * FROM $accDB.areas WHERE areacode='$orderingLoc'";
    echo $sql;
    $result = $db->Execute($sql);
    if ($result->recordCount()==0) {
        $Errors[0]=NoSuchArea;
        return $Errors;
    } else {
        $Errors[0]=0;
        $Errors[1]=$result->FetchRow();
        return $Errors;
    }
}

function VerifyStockCodeExists($StockCode, $i, $Errors, $db) {
    global $accDB;
    $Searchsql = "SELECT count(stockid)
				FROM $accDB.stockmaster
				WHERE stockid='".$StockCode."'";
    $SearchResult=$db->Execute($Searchsql);
    $answer = $SearchResult->FetchRow();
    if ($answer[0]==0) {
        $Errors[$i] = StockCodeDoesntExist;
    }
    return $Errors;
}


function GetStockBalance2($StockID) {
    global $db,$accDB;
    $Errors = array();
    $Errors = VerifyStockCodeExists($StockID, sizeof($Errors), $Errors, $db);
    if (sizeof($Errors)!=0) {
        return $Errors;
    }
    $sql="SELECT quantity, loccode FROM $accDB.locstock WHERE stockid='$StockID'";
    $result = $db->Execute($sql);
    if (sizeof($Errors)==0) {
        $i=0;
        $myrow=$result->FetchRow();
        $answer=$myrow['quantity'];
        //$answer[$i]['loccode']=$myrow['loccode'];
        //$i++;
        //}
        //	$Errors[0]=0;
        $Errors=$answer;
        return $Errors;
    } else {
        return $Errors;
    }
}


function GetStockItem($StockID) {
    global $db,$accDB;
    $Errors = array();

    $Errors = VerifyStockCodeExists($StockID, sizeof($Errors), $Errors, $db);
    if (sizeof($Errors)!=0) {
        return $Errors;
    }
    $sql="SELECT * FROM $accDB.stockmaster WHERE stockid='$StockID'";
    $result = $db->Execute($sql, $db);
    if (sizeof($Errors)==0) {
        $Errors[0]=0;
        $Errors[1]=$result->GetArray();
        return $Errors;
    } else {
        return $Errors;
    }
}

function GetCategoryGLCode($CategoryID, $field, $db) {
    global $accDB;
    $sql="SELECT '.$field.' FROM $accDB.stockcategory WHERE categoryid='$CategoryID'";
    $result = $db->Execute($sql);
    $myrow = $result->FetchRow();
    return $myrow[0];
}

function GetCOGSGLCode($Area, $StockCat, $SalesType,$db) {
    global $accDB;
    $sql="SELECT glcode
				FROM $accDB.cogsglpostings
                		WHERE area = '$Area'
                		AND stkcat = '$StockCat'
                		AND salestype = '$SalesType'";
    $result = $db->Execute($sql);
    $myrow = $result->FetchRow();
    return $myrow[0];
}

function GetNextTransactionNo($type, $db) {
    global $accDB;
    $sql = "select typeno from $accDB.systypes where typeid=" . $type;
    $result = $db->Execute($sql);
    $myrow = $result->FetchRow();
    $NextTransaction = $myrow[0] + 1;
    return $NextTransaction;
}

function GetPeriodFromTransactionDate($TranDate, $i, $Errors, $db) {
    global $accDB;
    $sql = "select confvalue from $accDB.config where confname='DefaultDateFormat'";
    $result = $db->Execute($sql);
    $myrow = $result->FetchRow();
    $DateFormat = $myrow[0];
    $DateArray = array();
//    if (strstr('/', $PeriodEnd)) {
//        $Date_Array = explode('/', $PeriodEnd);
//    } elseif (strstr('.', $PeriodEnd)) {
//        $Date_Array = explode('.', $PeriodEnd);
//    }
    if ($DateFormat == 'd/m/Y') {
        $Day = $DateArray[0];
        $Month = $DateArray[1];
        $Year = $DateArray[2];
    } elseif ($DateFormat == 'm/d/Y') {
        $Day = $DateArray[1];
        $Month = $DateArray[0];
        $Year = $DateArray[2];
    } elseif ($DateFormat == 'Y/m/d') {
        $Day = $DateArray[2];
        $Month = $DateArray[1];
        $Year = $DateArray[0];
    } elseif ($DateFormat == 'd.m.Y') {
        $Day = $DateArray[0];
        $Month = $DateArray[1];
        $Year = $DateArray[2];
    }
    $DateArray = explode('/', $TranDate);
    $Day = $DateArray[2];
    $Month = $DateArray[1];
    $Year = $DateArray[0];
    $Date = $Year . '-' . $Month . '-' . $Day;
    $sql = 'select max(periodno) from litein.periods where lastdate_in_period<="' . $Date . '"';
    $result = $db->Execute($sql);
    $myrow = $result->FetchRow();
    return $myrow[0] + 1;
}



function StockAdjustment($StockID, $Location,$OrderingLoc,$Quantity, $TranDate) {
    global $db,$accDB;
    $Errors = array();

    $salesArea=getSalesArea($OrderingLoc);

    $Errors = VerifyStockCodeExists($StockID, sizeof($Errors), $Errors, $db);

    $balances=GetStockBalance2($StockID);

    $newqoh = $balances-$Quantity;

    $SalesType='AN';
    $itemdetails = GetStockItem($StockID);

    $adjglact=GetCategoryGLCode($itemdetails[1]['categoryid'], 'adjglact', $db);
    $stockact=GetCategoryGLCode($itemdetails[1]['categoryid'], 'stockact', $db);

    $COGSGLCode=  GetCOGSGLCode($OrderingLoc, $itemdetails[1]['categoryid'], $SalesType, $db);

    $PeriodNo=GetPeriodFromTransactionDate($TranDate, sizeof($Errors), $Errors, $db);

    $transNo=GetNextTransactionNo(17, $db);

    $stockmovesql='INSERT INTO '.$accDB.'.stockmoves (stockid, type, transno, loccode, trandate, prd, reference, qty, newqoh)
				VALUES ("'.$StockID.'", 17,'.$transNo.',"'.$Location.'","'.$TranDate.
        '",'.$PeriodNo.',"Orders from '.$salesArea[1]['areadescription'].' serviced",'.$Quantity.','.$newqoh.')';
    $locstocksql='UPDATE $accDB.locstock SET quantity = quantity -'.$Quantity.' WHERE loccode="'.
        $Location.'" AND stockid="'.$StockID.'"';

    $glupdatesql1='INSERT INTO '.$accDB.'.gltrans (type, typeno, trandate, periodno, account, amount, narrative)
                                    VALUES (17,'.$transNo.',"'.$TranDate.
        '",'.$PeriodNo.','.$adjglact.','.$itemdetails[1]['materialcost']*$Quantity.
        ',"'.$StockID.' x '.$Quantity.' @ '.$itemdetails[1]['materialcost'].'")';

    $glupdatesql2='INSERT INTO '.$accDB.'.gltrans (type, typeno, trandate, periodno, account, amount, narrative)
                   VALUES (17,'.$transNo.',"'.$TranDate.
        '",'.$PeriodNo.
        ','.$stockact.','.$itemdetails[1]['materialcost']*$Quantity.
        ',"'.$StockID.' x '.$Quantity.' @ '.$itemdetails[1]['materialcost'].'")';
//
////             /*now the Debit Expences per department account suspense entry*/


    $glupdatesql3 = "INSERT INTO $accDB.gltrans (type,typeno,trandate,periodno,account,narrative,amount)
                           VALUES (17,'$transNo','$TranDate','$PeriodNo','$COGSGLCode',
                           'RQS-$transNo : LOC- $Location : StockID- $StockID- ".$itemdetails[1]['description'].":  Qty Issued-$Quantity',
                           '". $itemdetails[1]['materialcost'] * $Quantity . "')";

    $glupdatesql4 = "INSERT INTO $accDB.gltrans (type,typeno,trandate,periodno,account,narrative,amount)
                           VALUES (17,'" . $transNo . "','" . $TranDate. "','" . $PeriodNo . "','$stockact',
                           'RQS-$transNo : LOC-$Location : StockID-".$StockID.'-'.$itemdetails[1]['description'].":  Qty Issued-$Quantity',
                           '" . $itemdetails[1]['materialcost'] * -$Quantity . "')";
//
    $systypessql = 'UPDATE '.$accDB.'.systypes set typeno='.GetNextTransactionNo(17, $db).' where typeid=17';

//                 echo $stockmovesql .'<br>';
//                 echo $locstocksql .'<br>';
//                 echo $glupdatesql1 .'<br>';
//                 echo $locstocksql .'<br>';

    //DB_Txn_Begin($db);
    $db->Execute($stockmovesql);
    $db->Execute($locstocksql);
    $db->Execute($glupdatesql1);
    $db->Execute($glupdatesql2);
    $db->Execute($glupdatesql3);
    $db->Execute($glupdatesql4);
    $db->Execute($systypessql);
    // DB_Txn_Commit($db);
    if (DB_error_no($db) != 0) {
        $Errors[0] = DatabaseUpdateFailed;
        $Errors[] = $transNo;
        return $Errors;
    } else {
        return $Errors;
    }
}