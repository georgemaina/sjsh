<?php


/////////////////////////////////////////////////////////////////////////////////////
/// WEBERP Updates
/////////////////////////////////////////////////////////////////////////////////////
function getSalesArea($orderingLoc){
    global $db,$accDB;
    $debug=false;

    $sql = "SELECT * FROM $accDB.areas WHERE areacode='$orderingLoc'";
    if($debug) echo $sql;
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
    $debug=false;
    $Searchsql = "SELECT count(stockid)
				FROM $accDB.stockmaster
				WHERE stockid='".$StockCode."'";
    if($debug) echo $Searchsql;
    $SearchResult=$db->Execute($Searchsql);
    $answer = $SearchResult->FetchRow();
    if ($answer[0]==0) {
        $Errors[$i] = StockCodeDoesntExist;
    }
    return $Errors;
}


function GetStockBalance2($StockID) {
    global $db,$accDB;
    $debug=false;
    $Errors = array();
    $Errors = VerifyStockCodeExists($StockID, sizeof($Errors), $Errors, $db);
    if (sizeof($Errors)!=0) {
        return $Errors;
    }
    $sql="SELECT quantity, loccode FROM $accDB.locstock WHERE stockid='$StockID'";
    if($debug) echo $sql;
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
    $debug=false;
    $Errors = array();

    $Errors = VerifyStockCodeExists($StockID, sizeof($Errors), $Errors, $db);
    if (sizeof($Errors)!=0) {
        return $Errors;
    }
    $sql="SELECT * FROM $accDB.stockmaster WHERE stockid='$StockID'";
    if($debug) echo $sql;
    $result = $db->Execute($sql);
    if (sizeof($Errors)==0) {
        $Errors[0]=0;
        $Errors[1]=$result->FetchRow();
        return $Errors;
    } else {
        return $Errors;
    }
}

function GetCategoryGLCode($CategoryID, $field, $db) {
    global $accDB;
    $debug=false;
    $sql="SELECT '.$field.' FROM $accDB.stockcategory WHERE categoryid='$CategoryID'";
    if($debug) echo $sql;
    $result = $db->Execute($sql);
    $myrow = $result->FetchRow();
    return $myrow[0];
}

function GetCOGSGLCode($Area, $StockCat, $SalesType,$db) {
    global $accDB;
    $debug=false;
    $sql="SELECT glcode
				FROM $accDB.cogsglpostings
                		WHERE area = '$Area'
                		AND stkcat = '$StockCat'
                		AND salestype = '$SalesType'";
    if($debug) echo $sql;
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
    $debug=false;
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
    $sql = "select max(periodno) from $accDB.periods where lastdate_in_period<='$Date'";
    if($debug) echo $sql;
    $result = $db->Execute($sql);
    $myrow = $result->FetchRow();
    return $myrow[0] + 1;
}



function StockAdjustment($StockID, $Location,$OrderingLoc,$Quantity, $TranDate) {
    global $db,$accDB;
    $debug=false;
    $Errors = array();

    $salesArea=getSalesArea($OrderingLoc);

    $Errors = VerifyStockCodeExists($StockID, sizeof($Errors), $Errors, $db);

    $balances=GetStockBalance2($StockID);
    //echo $balances."<br>";

    $newqoh = $balances-$Quantity;
    //echo "<br> newqoh is".$newqoh;

    $SalesType='AN';
    $itemdetails = GetStockItem($StockID);
    //echo '<br>Stock item is '.$itemdetails[1]['categoryid'];

    $adjglact=GetCategoryGLCode($itemdetails[1]['categoryid'], 'adjglact', $db);
    // echo '<br> adjglact code is '.$adjglact;
    $stockact=GetCategoryGLCode($itemdetails[1]['categoryid'], 'stockact', $db);
    // echo '<br> stockact code is '.$stockact;
    $COGSGLCode=  GetCOGSGLCode($OrderingLoc, $itemdetails[1]['categoryid'], $SalesType, $db);
    //echo '<br> COGSGLCode code is '.$COGSGLCode;
    $PeriodNo=GetPeriodFromTransactionDate($TranDate, sizeof($Errors), $Errors, $db);
    //echo '<br> PeriodNo code is '.$PeriodNo;
    $transNo=GetNextTransactionNo(17, $db);
    //echo '<br> transNo code is '.$transNo;

    $stockmovesql='INSERT INTO '.$accDB.'.stockmoves (stockid, type, transno, loccode, trandate, prd, reference, qty, newqoh)
				VALUES ("'.$StockID.'", 17,'.$transNo.',"'.$Location.'","'.$TranDate.
        '",'.$PeriodNo.',"Orders from '.$salesArea[1]['areadescription'].' serviced",'.$Quantity.','.$newqoh.')';
    if($debug) echo $stockmovesql;

    $locstocksql="UPDATE $accDB.locstock SET quantity = quantity -'$Quantity' WHERE loccode='$Location' AND stockid='$StockID'";
    if($debug) echo $locstocksql;

    $glupdatesql1='INSERT INTO '.$accDB.'.gltrans (type, typeno, trandate, periodno, account, amount, narrative)
                                    VALUES (17,'.$transNo.',"'.$TranDate.
        '",'.$PeriodNo.','.$adjglact.','.$itemdetails[1]['materialcost']*$Quantity.
        ',"'.$StockID.' x '.$Quantity.' @ '.$itemdetails[1]['materialcost'].'")';
    if($debug) echo $glupdatesql1;

    $glupdatesql2='INSERT INTO '.$accDB.'.gltrans (type, typeno, trandate, periodno, account, amount, narrative)
                   VALUES (17,'.$transNo.',"'.$TranDate.
        '",'.$PeriodNo.
        ','.$stockact.','.$itemdetails[1]['materialcost']*$Quantity.
        ',"'.$StockID.' x '.$Quantity.' @ '.$itemdetails[1]['materialcost'].'")';
    if($debug) echo $glupdatesql2;
////             /*now the Debit Expences per department account suspense entry*/


    $glupdatesql3 = "INSERT INTO $accDB.gltrans (type,typeno,trandate,periodno,account,narrative,amount)
                           VALUES (17,'$transNo','$TranDate','$PeriodNo','$COGSGLCode',
                           'RQS-$transNo : LOC- $Location : StockID- $StockID- ".$itemdetails[1]['description'].":  Qty Issued-$Quantity',
                           '". $itemdetails[1]['materialcost'] * $Quantity . "')";
    if($debug) echo $glupdatesql3;

    $glupdatesql4 = "INSERT INTO $accDB.gltrans (type,typeno,trandate,periodno,account,narrative,amount)
                           VALUES (17,'" . $transNo . "','" . $TranDate. "','" . $PeriodNo . "','$stockact',
                           'RQS-$transNo : LOC-$Location : StockID-".$StockID.'-'.$itemdetails[1]['description'].":  Qty Issued-$Quantity',
                           '" . $itemdetails[1]['materialcost'] * -$Quantity . "')";
    if($debug) echo $glupdatesql4;
//
    $systypessql = 'UPDATE '.$accDB.'.systypes set typeno='.GetNextTransactionNo(17, $db).' where typeid=17';
    if($debug) echo $systypessql;

    // echo $stockmovesql .'<br>';
    // echo $locstocksql .'<br>';
    // echo $glupdatesql1 .'<br>';
    // echo $locstocksql .'<br>';

    //DB_Txn_Begin($db);
    $db->Execute($stockmovesql);
    $db->Execute($locstocksql);
    $db->Execute($glupdatesql1);
    $db->Execute($glupdatesql2);
    $db->Execute($glupdatesql3);
    $db->Execute($glupdatesql4);
    $db->Execute($systypessql);
    // DB_Txn_Commit($db);
    if ($db->Execute($systypessql)) {

        return $Errors;
    } else {
        $Errors[0] = DatabaseUpdateFailed;
        $Errors[] = $transNo;
        return $Errors;
    }
}
