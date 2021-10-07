<?php

//echo var_dump($_POST);
$debug = false;

($debug) ? $db->debug = FALSE : $db->debug = FALSE;

$status = 'pending';
$count_date = $_POST[strDate1];

$cDate=new DateTime($_POST[strDate1]);
$cDate1=$cDate->format('Y/m/d');
$count_date= $cDate1;//date($this->defaultDateFormat);

$store_loc = $_POST[storeID];
$store_desc = $_POST[storeDesc];
$period = '2012';
$input_user = $_SESSION['sess_login_username'];

//echo var_dump($_POST);
//echo 'rows '.$_POST[gridbox_rowsadded];

function insertData($db, $rowid, $status, $count_date, $store_loc, $store_desc, $input_user) {
    $debug = true;

    $itemId = $_POST["gridbox_" . $rowid . "_0"];
    $item_Desc = $_POST["gridbox_" . $rowid . "_1"];
    $qty = $_POST["gridbox_" . $rowid . "_2"];
    $countQty = $_POST["gridbox_" . $rowid . "_3"];

    $csql = "INSERT INTO `care_ke_stockcount`
            (`partcode`,
             `loccode`,
             `stockCountDate`,
             `CurrQty`,
             `currCount`,
             `inputUser`,
             `status`)
VALUES ('$itemId',
        '$store_loc',
        '$count_date',
        $qty,
        $countQty,
        '$input_user',
        '$status')";

    if ($debug)
        echo $csql;
    $result2 = $db->Execute($csql);
}

if (strstr($_POST[gridbox_rowsadded], ",")) {
    $added_rows = $_POST[gridbox_rowsadded];
    $arr_rows = explode(",", $added_rows);

    for ($i = 0; $i < count($arr_rows); $i++) {
//        echo '<br>added row ' . $arr_rows[$i] . '<br>';
        insertData($db, $arr_rows[$i], $status, $count_date, $store_loc, $store_desc, $input_user);
    }
} else {
    insertData($db, $_POST[gridbox_rowsadded], $status, $count_date, $store_loc, $store_desc, $input_user);
}
?>



