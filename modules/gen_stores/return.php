
<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');

    require_once($root_path . 'include/care_api_classes/class_weberp_c2x.php');
    require_once($root_path . 'include/inc_init_xmlrpc.php');
$limit = $_POST[limit];
$start = $_POST[start];
$item_number = $_POST[item_number];

$task = ($_POST['task']) ? ($_POST['task']) : $_REQUEST['task'];
switch ($task) {
    case "getList":
        getIssuedItems();
        break;
    case "getLevels":
        getLevels($limit, $start);
        break;
    case "getCat":
        getItemCat();
        break;
    case "getCat2":
        getItemCat2();
        break;
    case "create":
        createItem($item_number);
        break;
    case "adjustStock":
        stockAdjust($item_number);
        break;
    case "deleteItem":
        deleteItem();
        break;
    default:
        echo "{failure:true}";
        break;
}//end switch


function getIssuedItems(){
    $sql="SELECT op_no,item_id,item_desc,unit_cost,qty,total FROM care_ke_internal_orders
WHERE op_no=10275767";
    $result = $db->Execute($sql);

        while ($row = $result->FetchRow()) {
        $item_id = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[item_id]);
        $fDesc = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[item_desc]);
        $unit_cost = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[unit_cost]);
        $qty = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[qty]);
        $total = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[total]);
        
        echo '{"item_number":"' . $item_id . '","desc":"' . $fDesc . '",
       "cost":"' .$unit_cost. '","qty":"' . $qty . '","total":"' . $total . '"},';

        if ($counter < $numRows) {
            echo ",";
        }
        $counter++;
    }
}


function stockAdjust($item_number) {
    global $db;
    $item_number = $_REQUEST[item_number];
    $item_description = $_REQUEST[item_Description];
    $qty = $_REQUEST[quantity];
    $roorder = $_REQUEST[reorderlevel];
    $loccode = $_REQUEST[loccode];
    $adjDesc = $_REQUEST[comment];


    $sql = 'select quantity from care_ke_locstock where stockid="' . $item_number . '" and loccode="' . $loccode . '"';
    $result = $db->Execute($sql);
    $row = $result->FetchRow();

    $csql = "update care_ke_locstock set
	quantity='$qty',
        reorderlevel='$roorder',
        comment='$adjDesc'
        where stockid='$item_number' and loccode='$loccode'";

    $ksql = "insert into care_ke_adjustments
	(item_number, prev_qty, new_qty, user, adjDate, adjTime, Reason)
	values( '" . $item_number . "', '" . $row[0] . "', '" . $qty . "', 'admin',
            '" . date('Y-m-d') . "', '" . date('H:i:s') . "', '" . $adjDesc . "')";

    if ($db->Execute($ksql)) {
        $db->Execute($csql);
        $results = '{success: true }';
    } else {
        // Errors. Set the json to return the fieldnames and the associated error messages
        $results = '{success: false, sql:' . $ksql . '}'; // Return the error message(s)
//        echo $sql;
    }

    echo $results;
}





function getLevels($limit, $start) {
    global $db;
    $catID = $_POST[catID];
    $itemName = $_POST[itemName];
    $sql1 = 'select catID,item_Cat from care_tz_itemscat where item_Cat="' . $catID . '"';
    //$sql1='SELECT DISTINCT b.catid,b.item_cat FROM care_tz_itemscat b left jOIN care_tz_drugsandservices c ON b.catID=c.category  where catID="'.$catID.'" ORDER BY b.item_cat asc';
    $result1 = $db->Execute($sql1);
    $catName = $result1->FetchRow();


    if ($catID <> '' && $itemName == '') {
        $sql = 'select b.item_number, k.loccode,b.item_Description,k.quantity,k.reorderlevel,e.item_cat,k.comment
        from care_tz_drugsandservices b inner join care_tz_itemscat e
        on b.category=e.catid
        inner join care_ke_locstock k on k.stockid=b.item_number where b.category ="' . $catName[0] . '"  limit ' . $start . ',' . $limit;
    } else if ($catID == '' && $itemName <> '') {
        $sql = 'select b.item_number, k.loccode,b.item_Description,k.quantity,k.reorderlevel,e.item_cat,k.comment
        from care_tz_drugsandservices b inner join care_tz_itemscat e
        on b.category=e.catid
        inner join care_ke_locstock k on k.stockid=b.item_number where b.item_description like "' . $itemName . '%"  limit ' . $start . ',' . $limit;
    } else {
        $sql = 'select b.item_number, k.loccode,b.item_Description,k.quantity,k.reorderlevel,e.item_cat,k.comment
        from care_tz_drugsandservices b inner join care_tz_itemscat e
        on b.category=e.catid
        inner join care_ke_locstock k on k.stockid=b.item_number where b.item_description like "' . $itemName . '%"  limit ' . $start . ',' . $limit;
    }
    $result = $db->Execute($sql);
//    $numRows = $result->RecordCount();
//echo $sql;
    $sql2 = 'select count(item_id) from care_tz_drugsandservices';
    $result2 = $db->Execute($sql2);
    $total = $result2->FetchRow();
    $total = $total[0];
    echo '{
    "Total":"' . $total . '","Items":[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        $desc = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[5]);
        $fDesc = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[6]);
        $itmNo = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[0]);
        $fDesc1 = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[2]);
        echo '{"item_number":"' . $itmNo . '","loccode":"' . $row[1] . '",
       "item_Description":"' .$fDesc1. '","quantity":"' . $row[3] . '","reorderlevel":"' . $row[4] . '",
       "item_cat":"' . $row[5] . '","comment":"' . $row[6] . '"},';

        if ($counter < $numRows) {
            echo ",";
        }
        $counter++;
    }
    echo ']}';
}


?>

