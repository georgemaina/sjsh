
<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');

    require_once($root_path . 'include/care_api_classes/class_weberp_c2x.php');
    require_once($root_path . 'include/inc_init_xmlrpc.php');
$limit = $_POST[limit];
$start = $_POST[start];
$item_number = $_POST[item_number];
$input_user = $_SESSION['sess_login_username'];

$task = ($_POST['task']) ? ($_POST['task']) : $_REQUEST['task'];
switch ($task) {
    case "getList":
        getItemList($limit, $start);
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
    case "getPclass":
        getPurchasingClass();
        break;
    case "create":
        createItem($item_number);
        break;
    case "adjustStock":
        stockAdjust($item_number);
        break;
    case "deleteItem":
        deleteItem($input_user);
        break;
    case "deleteLevelItem":
        deleteLevelItem($input_user);
        break;
    
    default:
        echo "{failure:true}";
        break;
}//end switch

function checkStockid($partcode){
        global $db;
        $psql = 'select item_number from care_tz_drugsandservices where partcode="' . $partcode . '"';
        $presult = $db->Execute($psql);
        $row = $presult->FetchRow();
       return $row[0];
    }
    
function createItem($item_number) {
    global $db;
    $debug=false;
    $item_number = $_REQUEST[item_number];
    $partcode = $_REQUEST[partcode];
    $item_description = $_REQUEST[item_Description];
    $item_full_description = $_REQUEST[full_Description];
    $unit_price = $_REQUEST[unit_price];
    $purchasing_class = $_REQUEST[purchasing_class];
    $category = $_REQUEST[item_category];
    $units='each';
    $stid=checkStockid($partcode);
     $sql4 = 'select catid,ID from care_tz_itemscat where item_cat="' . $category . '"';
        $request4 = $db->Execute($sql4);
        $row4 = $request4->FetchRow();
        $catID=$row4[0];
    if($debug) echo $sql4;
    if ($stid <> "") {

        $sql = 'update care_tz_drugsandservices
	set
	partcode = "' . $partcode . '" ,
	item_description = "' . $item_description . '" ,
	item_full_description = "' . $item_full_description . '" ,
	unit_price = "' . $unit_price . '" ,
	purchasing_class ="' . $purchasing_class . '",
        category="'.$catID.'"
	where
	partcode ="' . $partcode . '"';
        if($debug) echo $sql;
    } else {

        $sql = 'insert into care_tz_drugsandservices
	(item_number, 
	partcode, 
	item_description, 
	item_full_description, 
	unit_price, 
	purchasing_class,
        category
	)
	values
	("' . $item_number . '", 
	"' . $partcode . '", 
	"' . $item_description . '", 
	"' . $item_full_description . '", 
	"' . $unit_price . '", 
        "' . $purchasing_class . '",
	"' . $catID . '")';
        if($debug) echo $sql;
        
    }
       
        $billdata[stockid] = $partcode;
        $billdata[categoryid] = $catID;
        $billdata[description] = $item_description;
        $billdata[longdescription] = $item_full_description;
//        $billdata[units] = $units;
//        $billdata[mbflag] = 'B';
//        $billdata[lastcurcostdate] = date('Y-m-d');
        $billdata[actualcost] = $unit_price;
        $billdata[lastcost] = $unit_price;
//        $billdata[materialcost] = 0;
//        $billdata[labourcost] = 0;
//        $billdata[overheadcost] = 0;
//        $billdata[lowestlevel] = 0;
//        $billdata[discontinued] = 0;
//        $billdata[controlled] = 0;
//        $billdata[eoq] = 0;
//        $billdata[volume] = 0;
//        $billdata[kgs] = 0;
//        $billdata[barcode] = 0;
//        $billdata[discountcategory] = 0;
//        $billdata[taxcatid] = 1;
//        $billdata[serialised] = 0;
//        $billdata[appendfile] = 0;
//        $billdata[perishable] = 0;
//        $billdata[decimalplaces] = 0;
//        $billdata[pansize] = 0;
//        $billdata[shrinkfactor] = 0;
//        $billdata[nextserialno] = 0;
//        $billdata[netweight] = 0;

    if ($db->Execute($sql)) {
        $sqll="SELECT st_id FROM care_ke_stlocation WHERE store=1 AND mainstore=0";
        $resultsl=$db->Execute($sqll);
        while($row=$resultsl->FetchRow()){
            $sql1="insert into care_ke_locstock(loccode,stockid) values('$row[0]','$partcode')";
                $db->Execute($sql1);
        }
        

        if ($weberp_obj = new_weberp()) {
            if($stid <> ""){
                $weberp_obj->modify_stock_item_in_webERP($billdata);
                $results = '{success: true }';
            }else{
                $weberp_obj->create_stock_item_in_webERP($billdata);
                $results = '{success: true }';
            }
        } else {

            $results = '{success: false }';
        }
    } else {
        // Errors. Set the json to return the fieldnames and the associated error messages
        $results = '{success: false, sql:' . $sql . '}'; // Return the error message(s)
//        echo $sql;
    }

    echo $results;
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

        $sql="Update care_tz_drugsandservices set item_status=1 where partcode='$item_number'";
//        echo $sql;
        $db->Execute($sql);


        $results = '{success: true }';
    } else {
        // Errors. Set the json to return the fieldnames and the associated error messages
        $results = '{success: false, sql:' . $ksql . '}'; // Return the error message(s)
//        echo $sql;
    }

    echo $results;
}

function deleteItem($input_user) {
    global $db;
    $key = $_POST['key'];
    $sql = "INSERT INTO care_tz_drugsandservices_del (select * from care_tz_drugsandservices where item_id='" . $key . "')";

    if ($db->Execute($sql)) {
        
//        echo $sql;
        $query = 'DELETE FROM care_tz_drugsandservices WHERE item_id = "' . $key . '"';
        
        if ($db->Execute($query)) { //returns number of rows deleted
//            echo $query;
//            $sql1="Update care_tz_drugsandservices_del set inputuser='$input_user' where item_id='$key'";
//        $db->Execute($sql1);
            echo '{success: true}';
        } else {
            echo '{failure: true}';
        }
    }
}

function deleteLevelItem($input_user) {
    global $db;
    $key = $_POST['key'];
    $sql = "INSERT INTO care_ke_locstock_del (select * from care_ke_locstock where ID='" . $key . "')";
//echo $sql;
    if ($db->Execute($sql)) {
        
        
        $query = 'DELETE FROM care_ke_locstock WHERE ID = "' . $key . '"';
        
        if ($db->Execute($query)) { //returns number of rows deleted
//            echo $query;
//            $sql1="Update care_tz_drugsandservices_del set inputuser='$input_user' where item_id='$key'";
//        $db->Execute($sql1);
            echo '{success: true}';
        } else {
            echo '{failure: true}';
        }
    }
}

//10049997

function getItemList($limit, $start) {
    global $db;
    $catID = $_POST[catID];
    $itemName = $_POST[itemName];
    
//    $sql1 = 'select catID,item_Cat from care_tz_itemscat where item_Cat="' . $catID . '"';
//    //$sql1='SELECT DISTINCT b.catid,b.item_cat FROM care_tz_itemscat b left jOIN care_tz_drugsandservices c ON b.catID=c.category  where catid="'.$catID.'" ORDER BY b.item_cat asc';
//    $result1 = $db->Execute($sql1);
//    $catName = $result1->FetchRow();
    

     $sql = "select b.item_id,b.item_number, b.partcode, b.unit_price,e.item_cat,
         b.item_Description,b.item_full_description,b.purchasing_class
        from care_tz_drugsandservices b inner join care_tz_itemscat e
        on b.category=e.catid ";
    
    
    if ($catID <> '' && $itemName == '') {
        $sql=$sql."where item_cat='$catID' limit $start ,$limit ";
    }
    if ($catID == '' && $itemName <> '') {
        $sql =$sql. "where item_description like '$itemName%'  limit  $start , $limit";
    }
    if ($catID <> '' && $itemName <> '') {
        $sql =$sql. "where item_description like '$itemName%' and item_cat='$catID' limit  $start , $limit";
    } 
     if ($catID == '' && $itemName == '') {
        $sql =$sql. "limit  $start , $limit";
    }
    
    $result = $db->Execute($sql);
//    $numRows = $result->RecordCount();
//    echo $sql;

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
        echo '{"item_id":"' . $row[0] . '","item_number":"' . $row[1] . '",
       "partcode":"' . $row[2] . '","unit_price":"' . $row[3] . '","purchasing_class":"' . $row[purchasing_class] . '",
       "item_Description":"' . $desc . '","full_Description":"' . $fDesc. '",
       "item_category":"' . $row[item_cat]  . '"},';

        $counter++;
        if ($counter < $numRows) {
            echo ",";
        }
        
    }
    echo ']}';
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
        $sql = 'select b.partcode, k.loccode,b.item_Description,k.quantity,k.reorderlevel,e.item_cat,k.comment,k.ID
        from care_tz_drugsandservices b  INNER join care_tz_itemscat e
        on b.category=e.catid
        INNER join care_ke_locstock k on k.stockid=b.partcode where b.category ="' . $catName[0] . '"  limit ' . $start . ',' . $limit;
    } else if ($catID == '' && $itemName <> '') {
        $sql = 'select b.partcode, k.loccode,b.item_Description,k.quantity,k.reorderlevel,e.item_cat,k.comment,k.ID
        from care_tz_drugsandservices b INNER join care_tz_itemscat e
        on b.category=e.catid
        INNER join care_ke_locstock k on k.stockid=b.partcode where b.item_description like "%' . $itemName . '%"  limit ' . $start . ',' . $limit;
    } else {
        $sql = 'select b.partcode, k.loccode,b.item_Description,k.quantity,k.reorderlevel,e.item_cat,k.comment,k.ID
        from care_tz_drugsandservices b INNER join care_tz_itemscat e
        on b.category=e.catid
        INNER join care_ke_locstock k on k.stockid=b.partcode where b.item_description like "%' . $itemName . '%"  limit ' . $start . ',' . $limit;
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
        echo '{"ID":"' . $row[ID] . '","item_number":"' . $itmNo . '","loccode":"' . $row[1] . '",
       "item_Description":"' .$fDesc1. '","quantity":"' . $row[3] . '","reorderlevel":"' . $row[4] . '",
       "item_cat":"' . $row[item_cat] . '","comment":"' . $row[6] . '"},';

        if ($counter < $numRows) {
            echo ",";
        }
        $counter++;
    }
    echo ']}';
}

function getItemCat() {
    global $db;
    $sql = 'SELECT DISTINCT b.catid,b.item_cat FROM care_tz_itemscat b  
        left JOIN care_tz_drugsandservices c ON b.catID=c.category ORDER BY b.item_cat asc';
    $result = $db->Execute($sql);
    $numRows = $result->RecordCount();

    echo '{"Total":'.$numRows.',"itemsCat":[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        echo '{"catid":"' . $row[0] . '","item_category":"' . $row[1] . '"}';

        $counter++;
        if ($counter < $numRows) {
            echo ",";
        }
        
    }
    echo ']}';
}

function getItemCat2() {
    global $db;
    $sql = 'SELECT DISTINCT b.catid,b.item_cat FROM care_tz_itemscat b 
        left jOIN care_tz_drugsandservices c ON b.catID=c.category ORDER BY b.item_cat asc';
    $result = $db->Execute($sql);
    $numRows = $result->RecordCount();

    echo '{"itemsCat2":[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        echo '{"item_category2":"' . $row[1] . '"}';

        $counter++;
        if ($counter < $numRows) {
            echo ",";
        }
        
    }
    echo ']}';
}

function getPurchasingClass() {
    global $db;
    $sql = 'SELECT DISTINCT purchasing_class FROM care_tz_drugsandservices order by purchasing_class asc';
    $result = $db->Execute($sql);
    $numRows = $result->RecordCount();

    echo '{"pclass":[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        echo '{"purchasing_class":"' . $row[0] . '"}';

        if ($counter < $numRows) {
            echo ",";
        }
        $counter++;
    }
    echo ']}';
}
?>

