
<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');
require_once($root_path . 'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path . 'include/inc_init_xmlrpc.php');
require_once($root_path.'global_conf/areas_allow.php');

$allowedarea=&$allow_area['admit'];

$weberp_obj = new_weberp();

$limit = $_REQUEST[limit];
$start = $_REQUEST[start];
$formStatus = $_REQUEST[formStatus];
$searchParam = $_REQUEST[sParam];

$item_number = $_REQUEST[partcode];
$partcode = $_REQUEST[partcode];
$item_description = $_REQUEST[item_description];
$item_full_description = $_REQUEST[item_full_description];
$unit_price = $_REQUEST[unit_price];
$purchasing_class = $_REQUEST[purchasing_class];
$category = $_REQUEST[category];
$itemStatus = $_REQUEST[Item_Status];
$sellingPrice = $_REQUEST[selling_price];
$maximum = $_REQUEST[maximum];
$minimum = $_REQUEST[minimum];
$reorder = $_REQUEST[reorder];
$unitMeasure = $_REQUEST[unit_measure];
$gl_sales_acct = $_REQUEST[gl_sales_acct];
$gl_inventory_acct = $_REQUEST[gl_inventory_acct];
$gl_costofsales_acct = $_REQUEST[gl_costofsales_acct];
$Purchasing_Unit = $_REQUEST[Purchasing_Unit];
$Unit_qty = $_REQUEST[Unit_qty];
$MoreInfo = $_REQUEST[MoreInfo];
$SalesArea = $_REQUEST[SalesArea];
//$category=GetItemCategory($_REQUEST[partcode]);

$currUser=$_SESSION["sess_login_username"];

$units = 'each';

$task = ($_REQUEST['task']) ? ($_REQUEST['task']) : '';

switch ($task) {
    case "getOrdersTemplate":
        getOrdersTemplate();
        break;
    case "deleteItemLocation":
        deleteItemLocation();
        break;
    case "saveiItems":
        if ($formStatus == 'insert') {
            SaveItems($_POST);
            break;
        } else if ($formStatus == 'update') {
            UpdateItems($_POST);
            break;
        }
    case "getDispensingLocations":
        getDispensingLocations($start,$limit);
        break;
    case "getItemsList":
        getItemsList($searchParam ,$start, $limit);
        break;
    case "InsertItem":
        if ($formStatus == 'insert') {
            InsertItem($partcode, $item_description, $item_full_description, $unit_price, $purchasing_class, $category,
                $itemStatus, $sellingPrice, $maximum, $minimum, $reorder, $unitMeasure, $gl_sales_acct, $gl_inventory_acct,
                $gl_costofsales_acct, $weberp_obj,$presLevel,$presNHIF,$Purchasing_Unit,$Unit_qty,$MoreInfo,$SalesArea);
        } else if ($formStatus == 'update') {
            updateItem($partcode, $item_description, $item_full_description, $unit_price, $purchasing_class, $category,
                $itemStatus, $sellingPrice, $maximum, $minimum, $reorder, $unitMeasure, $gl_sales_acct, $gl_inventory_acct,
                $gl_costofsales_acct, $weberp_obj,$presLevel,$presNHIF,$Purchasing_Unit,$Unit_qty,$MoreInfo,$SalesArea);
        }
        break;
    case "getItemsCategory":
        getItemsCategory($start, $limit);
        break;
    case "getItemsSubCategory":
        getItemsSubCategory($start, $limit);
        break;
    case "getUnitsofMeasure":
        getUnitsofMeasure($start, $limit);
        break;
    case "getStoreLocations":
        getStoreLocations($start, $limit);
        break;
    case "getItemLocation":
        getItemLocations2($start, $limit);
        break;

    case "deleteItem":
        deleteItem();
        break;
    case "getGLAccounts":
        getGLAccounts();
        break;
    case "getItemPrices":
        getItemPrices($partcode);
        break;
    case "getPriceTypes":
        getPriceTypes();
        break;
    case "deletePrices":
        deleteItemPrices();
        break;
    case "exportItemsToExcel":
        exportItemToExel($catID);
        break;
    case "getItemStatus":
        getItemStatus();
        break;
    case "checkDeletePermisssion":
        checkDeletePermisssion($currUser);
        break;
    case "glaccounts";
        glaccounts($group);
        break;
    case "getPriceTypes2":
        getItemPrices2($partcode,$item_description);
        break;
    case "insertPriceTypes":
        insertPriceTypes();
        break;
    case "insertNewPrice":
        if($formStatus == 'insert'){
            insertNewPrice($_POST);
        }else{
            updatePrice($_POST);
        }
        break;
    default:
        echo "{failure:true}";
        break;
}//end switch


function getOrdersTemplate(){
    global $db;
    $debug=true;


    $sql="SELECT partcode,item_description,purchasing_unit,l.Quantity FROM care_tz_drugsandservices d
            LEFT JOIN maua.`locstock` l ON d.`partcode`=l.`stockid` where purchasing_class in ('Drug_List','Medical-Supplies')";

    $results=$db->execute($sql);
    $total=$results->RecordCount();

    echo '{
    "total":"' . $total . '","salesItems":[';
    $counter = 0;
    while ($row = $results->FetchRow()) {

        $monthlyUsage=0;
        $reorderLevel=0;

        echo '{"StockCode":"' . $row[partcode] . '","Description":"' . $row[item_description] . '","Purchasing_Unit":' . purchasing_unit
            . ',"Level":"' . $row[Quantity] . '","MonthlyUsage":' . $monthlyUsage . '","ReorderLevel":' . $reorderLevel  . '}';

        $counter++;
        if ($counter < $total) {
            echo ",";
        }
    }
    echo ']}';
}


function insertPriceTypes(){
    global $db;
    $debug=false;

    $priceTypes=$_POST[priceTypes];

    $sql="Insert into care_ke_pricetypes(priceType) values('$priceTypes')";
    if($debug) echo $sql;

    if($db->Execute($sql)){
        echo "{success:true}";
    }else{
        ECHO "{failure:true}";
    }
}

function  checkDeletePermisssion($userName){
    global $db;

    $sql="Select delete_items from care_users where login_id='$userName'";
   // echo $sql;
    $request=$db->Execute($sql);

    $row=$request->FetchRow();

    if($row[0]=="1"){
        echo "{success:true}";
    }else{
        echo "{failure:true}";
    }

}

function getGLAccounts() {
    global $db;
    $debug = false;

    $group=$_REQUEST[group];

    $sql = "Select accountcode,accountname from chartmaster";

    if($group<>''){
        $sql.=" where group_='$group'";
    }

    if($debug) echo $sql;
    $request = $db->Execute($sql);

    $total = $request->RecordCount();

    echo '{
    "total":"' . $total . '","glaccounts":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {

        echo '{"accountcode":"' . $row[accountcode] . '","accountname":"' . $row[accountname] . '"}';


        if ($counter <> $total) {
            echo ",";
        }
        $counter++;
    }

    echo ']}';
}

function deleteItemLocation() {
    global $db;
    $debug = false;

    $stockid = $_REQUEST['stockid'];
    $loccode = $_REQUEST['loccode'];

    $sql = "DELETE FROM CARE_KE_LOCSTOCK WHERE stockid='$stockid' and loccode='$loccode'";

    if ($debug) {
        echo $sql;
    }
    if ($db->Execute($sql)) {
        echo '{success:true}';
    } else {
        echo "{'failure':'true','stockid':'$stockid'}";
    }
}

function saveItems($itemslist) {
    global $db;
    $debug = false;

    $table = $itemslist['formtype'];
    unset($itemslist['formtype']);
    unset($itemslist['formStatus']);
    unset($itemslist['IdParam']);
    $partcode = $itemslist['partcode'];

    foreach ($itemslist as $key => $value) {
        $FieldNames.=$key . ', ';
        $FieldValues.='"' . $value . '", ';
    }

    $sql = 'INSERT INTO ' . $table . ' (' . substr($FieldNames, 0, -2) . ') ' .
        'VALUES (' . substr($FieldValues, 0, -2) . ') ';

    if ($debug)
        echo $sql;
    if ($db->Execute($sql)) {
        //transmitWeberp($itemsList, $stid = true);
        echo '{success:true}';
    } else {
        echo "{'failure':'true','partcode':'$partcode'}";
    }
}

function UpdateItems($itemslist) {
    global $db;
    $debug = TRUE;

    $id = $itemslist['IdParam'];
    $tableName=$itemslist['formtype'];

    $sql = "UPDATE $tableName SET ";

    unset($itemslist['formStatus']);
//    unset($itemslist['ID']);
//    unset($itemslist['CatID']);
    unset($itemslist['formtype']);
    unset($itemslist['IdParam']);

    foreach ($itemslist as $key => $value) {
        $sql .= $key . '="' . $value . '", ';
    }

    $sql = substr($sql, 0, -2) . " WHERE $id='" .$itemslist[$id] . "'";

    if ($debug)
        echo $sql;

    if ($db->Execute($sql)) {
        $results = '{success: true }';
    } else {
        $results = "{failure: true}"; // Return the error message(s)
    }
    echo $results;
}

function deleteItems($itemslist) {
    global $db;
    $debug = false;

    $table = $itemslist['formtype'];
    unset($itemslist['formtype']);

    foreach ($itemslist as $key => $value) {
        $FieldNames.=$key . ', ';
        $FieldValues.='"' . $value . '", ';
    }

    $sql = 'INSERT INTO ' . $table . ' (' . substr($FieldNames, 0, -2) . ') ' .
            'VALUES (' . substr($FieldValues, 0, -2) . ') ';

    if ($debug)
        echo $sql;
    if ($db->Execute($sql)) {
        echo '{success:true}';
    } else {
        echo "{'failure':'true','partcode':'$partcode'}";
    }
}

function getSalesItems($start, $limit) {
    global $db;
    $debug = false;

    $sql = "SELECT  d.partcode,d.`item_description`,d.`unit_price`,l.`quantity`,l.`loccode` FROM care_tz_drugsandservices d 
           INNER JOIN care_ke_locstock l
           ON d.`partcode`=l.`stockid` WHERE d.`purchasing_class` LIKE 'drug%' AND l.`loccode`='main'";

    if ($start <> '' && $limit <> '')
        $sql.=" limit $start,$limit";

    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);

    $sqlTotal = "SELECT  COUNT(d.partcode) AS partcode FROM care_tz_drugsandservices d 
           INNER JOIN care_ke_locstock l
           ON d.`partcode`=l.`stockid` WHERE d.`purchasing_class` LIKE 'drug%' AND l.`loccode`='main'";

    $request2 = $db->Execute($sqlTotal);

    $row = $request2->FetchRow();
    $total = $row[0];

    echo '{
    "total":"' . $total . '","salesItems":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {
        if ($row[unit_price] <> '') {
            $price = $row[unit_price];
        } else {
            $price = 0;
        }

        if ($row[quantity] <> '') {
            $qty = $row[quantity];
        } else {
            $qty = 0;
        }
        echo '{"itemcode":"' . $row[partcode] . '","description":"' . $row[item_description] . '","qty":' . $qty
        . ',"loccode":"' . $row[loccode] . '","price":' . $price . '}';



        if ($counter < $total) {
            echo ",";
        }
        $counter++;
    }
    echo ']}';
//    echo "<br><br><br>".$counter;
}

function deleteItemPrices(){
    global $db;
    $debug=false;
    $partcode=$_REQUEST[partcode];

    if($_REQUEST[priceType]=='COMPANY'){
        $priceType=2;
    }else{
        $priceType=1;
    }

    $sql="delete from care_ke_prices where partcode='$partcode' and priceType='$priceType'";
    if($debug) echo $sql;

    if($db->Execute($sql)){
        echo "{success:true}";
    }else{
        echo "{failure:true}";
    }
}

function getPriceTypes() {
    global $db;
    $debug = FALSE;

    $sql = "Select ID,PriceType from care_ke_priceTypes ";

    if ($debug) {
        echo $sql;
    }

    $results = $db->Execute($sql);
    $total = $results->RecordCount();

    echo '{
    "total":"' . $total . '","pricetypes":[';
    $counter = 0;
    while ($row = $results->FetchRow()) {

        echo '{"ID":"' . $row[ID] . '","PriceType":"' . $row[PriceType] . '"}';

        $counter++;

        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']}';
}

function getPriceTypeIDFromName($priceType){
    global $db;

    $sql="Select ID from care_ke_pricetypes where PriceType='$priceType'";
    $results=$db->Execute($sql);
    $row=$results->FetchRow();

    return $row[0];
}

function insertNewPrice($rctData) {
    global $db;
    $debug = false;

    unset($rctData['ID']);
    unset($rctData['description']);
    unset($rctData['formStatus']);


    foreach ($rctData as $key => $value) {
        $FieldNames.=$key . ', ';
        $FieldValues.='"' . $value . '", ';
    }

    $sql = 'INSERT INTO care_ke_prices (' . substr($FieldNames, 0, -2) . ') ' .
            'VALUES (' . substr($FieldValues, 0, -2) . ') ';
    if ($debug) {
        echo $sql;
    }
    if ($db->Execute($sql)) {
        echo "{success:true}";
    } else {
        echo "{failure:true}";
    }
}

function updatePrice($rctData) {
    global $db;
    $debug = false;

    $typeID=getPriceTypeIDFromName($rctData[PriceType]);

    $sql = "update care_ke_prices set
	            price='$rctData[Price]'
          where partcode='$rctData[PartCode]' and priceType='$typeID'";

    if ($debug)  echo $sql;

    if ($db->Execute($sql)) {
        echo "{success:true}";
    } else {
        echo "{failure:true}";
    }
}



function getItemPrices2($partcode,$description) {
    global $db;
    $debug = false;

    $searchParam=$_REQUEST[searchParam];

    $sql = "SELECT p.`partcode`,d.`item_description`,p.`priceType` as TypeID,t.`PriceType`,p.`price` FROM care_ke_prices p 
            INNER JOIN care_tz_drugsandservices d ON p.`partcode`=d.`partcode`
            INNER JOIN `care_ke_pricetypes` t ON p.`priceType`=t.`ID` where p.partcode<>''";


    if (isset($searchParam)) {
            $sql.=" and p.partcode like '%$searchParam%' OR d.item_description like '%$searchParam%'";
    }

    $sql.=" ORDER BY p.`partcode` ASC";

    if ($debug) echo $sql;

    $results = $db->Execute($sql);
    $total = $results->RecordCount();

    echo '{
    "total":"' . $total . '","itemprices":[';
    $counter = 0;
    while ($row = $results->FetchRow()) {
        $description=trim(preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[item_description]));

        echo '{"PartCode":"' . $row[partcode] . '","Description":"' . $description . '","PriceID":"' . $row[TypeID]
            . '","PriceType":"' . $row[PriceType] . '","Price":"' . $row[price]. '"}';

        $counter++;

        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']}';
}

function getItemPrices($partcode) {
    global $db;
    $debug = FALSE;

    $json = $_REQUEST['writePrices'];
    $rctData = json_decode($json, true);

    if (count($rctData) > 0) {
        insertNewPrice($rctData);
    }


    $sql = "Select p.ID,t.PriceType,PartCode,Price from care_ke_prices p left join care_ke_priceTypes t on p.priceType=t.ID";

//    if (isset($partcode)) {
        $sql.=" where partcode='$partcode'";
//    }

    if ($debug) {
        echo $sql;
    }

    $results = $db->Execute($sql);
    $total = $results->RecordCount();

    echo '{
    "total":"' . $total . '","itemPrices":[';
    $counter = 0;
    while ($row = $results->FetchRow()) {

        echo '{"ID":"' . $row[ID] . '","PriceType":"' . $row[PriceType] . '","PartCode":"' . $row[PartCode]
        . '","Price":"' . $row[Price] . '"}';

        $counter++;

        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']}';
}

function getItemsList($searchParam, $start, $limit) {
    global $db;
    $debug = false;
    $category = $_REQUEST[category];

    $sql = "SELECT partcode,REPLACE(item_description,'\"','') AS item_description,REPLACE(item_full_description,'\"','') AS item_full_description,
              `unit_measure`,unit_price,unit_price_1,selling_price,purchasing_class, c.`item_Cat` AS category,item_status,`reorderlevel`
              ,`minimum`,maximum,gl_sales_acct,gl_inventory_acct,gl_costofsales_acct,PresLevel,PresNHIF,d.SalesAreas,
              Purchasing_Unit,Unit_qty,MoreInfo
            FROM care_tz_drugsandservices d LEFT JOIN  care_tz_itemscat c
            ON d.`category`=c.`catID` WHERE partcode <> ''";

    
    if (isset($searchParam)) {
        $sql.=" and partcode like '%$searchParam%' or item_description like '%$searchParam%'";
    }

    if (isset($category)) {
        $sql.=" and category='$category'";
    }


    if (isset($start) && isset($limit)) {
        $sql.=" limit $start,$limit";
    }

    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);

    $total = $request->RecordCount();


    echo '{"total":"' . $total . '","itemsList":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {

        $desc= trim(preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[item_description]));
        $desc2= trim(preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[item_full_description]));
        $stCat= trim(preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[category]));
        $units=trim(preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[unit_measure]));

        echo '{"partcode":"' . $row[partcode] . '","item_description":"' . $desc . '","item_full_description":"' . $desc2
        . '","unit_measure":"' . $units . '","unit_price":"' . $row[unit_price] . '","selling_price":"' . $row[selling_price]
        . '","purchasing_class":"' . $row[purchasing_class] . '","category":"' . $stCat
        . '","Item_Status":"' . $row[item_status] . '","reorder":"' . $row[reorderlevel] . '","minimum":"' . $row[minimum]
        . '","maximum":"' . $row[maximum] . '","gl_sales_acct":"' . $row[gl_sales_acct] . '","gl_inventory_acct":"' . $row[gl_inventory_acct]
        . '","gl_costofsales_acct":"' . $row[gl_costofsales_acct]. '","presLevel":"' . $row[PresLevel]
            . '","presNHIF":"' . $row[PresNHIF] . '","SalesAreas":"' . $row[SalesAreas]
            . '","Purchasing_Unit":"' . $row[Purchasing_Unit] . '","Unit_qty":"' . $row[Unit_qty] . '","MoreInfo":"' . $row[MoreInfo].'"}';

        $counter++;

        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']}';
//    echo "<br><br><br>".$counter;
}

function getUnitsofMeasure($start, $limit) {
    global $db;
    $debug = false;

    $sql = "select id,`name` from care_unit_measurement order by `name` asc";
    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);


    $total = $request->RecordCount();

    echo '{
    "total":"' . $total . '","unitsMeasure":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {

        echo '{"ID":"' . $row[id] . '","name":"' . $row[name] . '"}';


        if ($counter <> $total) {
            echo ",";
        }
        $counter++;
    }

    echo ']}';
}

function getItemsCategory($start, $limit) {
    global $db;
    $debug = false;

    $sql = "select ID,catName from care_tz_categories order by catName asc";

    if (isset($start) && isset($limit)) {
        $sql.=" limit $start,$limit";
    }

    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);

    $sqlTotal = "select count(ID) as ccount from care_tz_categories";

    $request2 = $db->Execute($sqlTotal);

    $row2 = $request2->FetchRow();
    $total = $row2[0];

    echo '{
    "total":"' . $total . '","itemsCategory":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {

        echo '{"ID":"' . $row[ID] . '","catName":"' . $row[catName] . '"}';

        $counter++;
        if ($counter <> $total) {
            echo ",";
        }
    }

    echo ']}';
}

function getItemsSubCategory($start, $limit) {
    global $db;
    $debug = false;
    $category = $request[query];

    $sql = "select catID,item_cat from care_tz_itemscat ";

//    if(isset($category)){
//        $sql.=" where item_cat= like '$category%'";
//    }

    $sql.=" order by item_cat asc";

    if (isset($start) && isset($limit)) {
        $sql.=" limit $start,$limit";
    }

    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);

    $sqlTotal = "select count(catID) as ccount from care_tz_itemscat";

    $request2 = $db->Execute($sqlTotal);

    $row2 = $request2->FetchRow();
    $total = $row2[0];

    echo '{
    "total":"' . $total . '","itemsSubCategory":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {

        echo '{"catID":"' . $row[catID] . '","item_cat":"' . trim($row[item_cat]) . '"}';

        $counter++;
        if ($counter <> $total) {
            echo ",";
        }
    }

    echo ']}';
}

function getItemStatus() {
    global $db;
    $debug = false;

    $sql = "SELECT ID,Description FROM care_ke_itemstatus";


    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);

    $total = $request->RecordCount();

    echo '{
    "total":"' . $total . '","itemStatus":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {

        echo '{"ID":"' . $row[ID] . '","Description":"' . $row[Description] . '"}';


        if ($counter <> $total) {
            echo ",";
        }
        $counter++;
    }

    echo ']}';
}


function getStoreLocations($start, $limit) {
    global $db;
    $debug = false;

    $sql = "SELECT st_id,st_name,store,mainStore FROM care_ke_stlocation order by st_name asc";

    if (isset($start) && isset($limit)) {
        $sql.=" limit $start,$limit";
    }

    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);

    $sqlTotal = "select count(st_id) as ccount from  care_ke_stlocation ";

    $request2 = $db->Execute($sqlTotal);

    $row2 = $request2->FetchRow();
    $total = $row2[0];

    echo '{
    "total":"' . $total . '","storeLocations":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {

        echo '{"st_id":"' . $row[st_id] . '","st_name":"' . $row[st_name] . '","store":"' . $row[store] . '","mainStore":"' . $row[mainStore] . '"}';


        if ($counter <> $total) {
            echo ",";
        }
        $counter++;
    }

    echo ']}';
}

function updateItemLocation($rctData, $partcode) {
    global $db;
    $debug = false;

    foreach ($rctData as $key => $value) {
        $FieldNames.=$key . ', ';
        $FieldValues.='"' . $value . '", ';
    }

    $sql = "update care_ke_locstock set
	quantity='$rctData[quantity]'
        where stockid='$rctData[stockid]' and loccode='$rctData[loccode]'";

    if ($debug) {
        echo $sql;
    }
    if ($db->Execute($sql)) {
        echo "{success:true}";
    } else {
        echo "{failure:true}";
    }
}

function insertNewItemLocation($rctData) {
    global $db;
    $debug = false;

    foreach ($rctData as $key => $value) {
        $FieldNames.=$key . ', ';
        $FieldValues.='"' . $value . '", ';
    }

    $sql = 'INSERT INTO care_ke_locstock (' . substr($FieldNames, 0, -2) . ') ' .
            'VALUES (' . substr($FieldValues, 0, -2) . ') ';
    if ($debug) {
        echo $sql;
    }
    if ($db->Execute($sql)) {
        echo "{success:true}";
    } else {
        echo "{failure:true}";
    }
}

function verifyItemLocation($partcode, $loccode) {
    global $db;
    $debug = false;

    $sql = "Select * from care_ke_locstock where stockid='$partcode' and loccode='$loccode'";
    if ($debug)
        echo $sql;

    $results = $db->Execute($sql);
    $rcount = $results->RecordCount();
    if ($rcount > 0) {
        return true;
    } else {
        return false;
    }
}

function getItemLocations($start, $limit) {
    global $db;
    $debug = false;
    $partcode = $_REQUEST[partcode];

    $json = $_REQUEST['editData'];
    $rctData = json_decode(file_get_contents("php://input"), true);

    $loccode = $rctData[stockid];

    if (count($rctData) > 0) {
        
        if (verifyItemLocation($rctData[stockid], $rctData[loccode])) {
            updateItemLocation($rctData, $partcode);
        } else {
            insertNewItemLocation($rctData);
        }
        getItemLocations2();
    } else {
        getItemLocations2();
    }
}

    function getItemLocations2($searchParam,$locParam) {
        global $db;
        $debug = false;

        $sql = "SELECT l.`loccode`,l.`stockid`,d.`item_description`,d.`unit_measure`,d.`units`,l.`quantity` 
                    FROM `care_ke_locstock` l 
                    LEFT JOIN care_tz_drugsandservices d ON l.`stockid`=d.`partcode` where l.loccode<>''";

        if($searchParam<>''){
            $sql=$sql." and d.item_description like '%$searchParam%' OR l.stockid='$searchParam'";
        }

        if($locParam<>''){
            $sql=$sql." and l.loccode='$locParam'";
        }

        if ($debug) echo $sql;

        $request = $db->Execute($sql);
        $total = $request->RecordCount();

        echo '{
    "total":"' . $total . '","itemLocations":[';
        $counter = 0;
        while ($row = $request->FetchRow()) {

            echo '{"LocCode":"' . $row[loccode] . '","StockID":"' . $row[stockid] . '","Description":"' . $row[item_description]
                . '","UnitMeasure":"' . $row[unit_measure] . '","Conversion":"' . $row[units] . '","Level":"' . $row[quantity]. '"}';

            $counter++;
            if ($counter <> $total) {
                echo ",";
            }

        }

        echo ']}';
    }


function getDispensingLocations($start, $limit) {
    global $db;
    $debug = false;
    $partcode = $_REQUEST[partcode];

    $sql = "SELECT st_id,st_name,store,mainstore FROM `care_ke_stlocation` WHERE  Dispensing=1";
    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);
    $total = $request->RecordCount();

    echo '{"total":"' . $total . '","dispenseLocations":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {

        echo '{"st_id":"' . $row[st_id] . '","st_name":"' . $row[st_name] . '"}';

        if ($counter <> $total) {
            echo ",";
        }
        $counter++;
    }

    echo ']}';
}

function GetItemCategory($partcode) {
    global $db;
    $sql = "SELECT category FROM care_tz_drugsandservices where partcode='$partcode';";
//   echo $sql;
    $result = $db->Execute($sql);
    $myrow = $result->FetchRow();
    return $myrow[0];
}

    function GetItemCategoryID($partcode) {
        global $db;
        $sql = "SELECT category FROM care_tz_drugsandservices where partcode='$partcode'";
        //echo $sql;
        $result = $db->Execute($sql);
        $myrow = $result->FetchRow();
        return $myrow[0];
    }

function GetItemCategoryName($item_cat) {
    global $db;
    $debug=false;

    $sql = "SELECT catID FROM care_tz_itemscat where item_cat='$item_cat'";
    if($debug) echo $sql;
    //echo $sql;
    $result = $db->Execute($sql);
    $myrow = $result->FetchRow();
    return $myrow[0];
}

function checkStockid($partcode) {
    global $db;
    $debug=false;
    
    $psql = 'select count(item_number) as pcount from care_tz_drugsandservices where partcode="' . $partcode . '"';
    if($debug) echo $psql;
    $presult = $db->Execute($psql);
    $row = $presult->FetchRow();
    return $row[0];
}



function InsertItem($partcode, $item_description, $item_full_description, $unit_price, $purchasing_class, $category,
                    $itemStatus, $sellingPrice,$maximum, $minimum, $reorder, $unitMeasure, $gl_sales_acct,
                    $gl_inventory_acct, $gl_costofsales_acct, $weberp_obj,$presLevel,$presNHIF,
                    $Purchasing_Unit,$Unit_qty,$MoreInfo,$SalesAreas) {
    global $db;
    $debug = FALSE;

    $stid = checkStockid($partcode);
//    $catID=GetItemCategoryID($partcode);

    if ($stid > 0) {
        echo "{'failure':'true','errNo':1,'partcode':'$partcode'}";
    } else {
        $sql = 'insert into care_tz_drugsandservices(item_number, 
                partcode, item_description, item_full_description, unit_price, purchasing_class,category,
                reorderlevel,minimum,maximum,unit_measure,selling_price,item_status, gl_sales_acct,gl_inventory_acct, gl_costofsales_acct
                ,PresLevel,PresNHIF,Purchasing_Unit,Unit_qty,MoreInfo,SalesAreas)
                values("' . $partcode . '", "' . $partcode . '", "' . $item_description . '", "' . $item_full_description . '",
                "' . $unit_price . '", "' . $purchasing_class . '","' . $category . '","' . $reorder . '","' . $minimum . '","' . $maximum . '","' . $unitMeasure
                . '","' . $sellingPrice . '","' . $itemStatus . '","' . $gl_sales_acct . '","' . $gl_inventory_acct
                . '","' . $gl_costofsales_acct  . '","' . $presLevel . '","' . $presNHIF
                . '","' . $Purchasing_Unit  . '","' . $Unit_qty . '","' . $MoreInfo .'","' . $SalesArea.'")';
        if ($debug)
            echo $sql;
        if ($db->Execute($sql)) {
            if($purchasing_class='Drug_List'|| $purchasing_class='Medical-Supplies'){
                $sql="Insert into care_locstock(LOCCODE,STOCKID,QUANTITY) VALUES ('DISPENS','$partcode','0')";
                $db->Execute($sql);
            }
                    transmitWeberp($partcode, $item_description, $item_full_description, $unit_price, $purchasing_class, $category, $itemStatus,
                    $sellingPrice, $maximum, $minimum, $reorder, $unitMeasure, $stid = true);
        } else {
            echo "{'failure':'true','errNo':3}";
        }
    }
}

function checkStockidFromName($item_cat){
    global $db;
    $debug=false;

    $sql="Select CatID from care_tz_itemscat where item_cat='$item_cat'";
    if($debug) echo $sql;
    $results=$db->Execute($sql);
    $count=$results->RecordCount();

    if($count>0){
        return true;
    }else{
        return false;
    }
}

function updateItem($partcode, $item_description, $item_full_description, $unit_price, $purchasing_class, $category,
                    $itemStatus, $sellingPrice, $maximum, $minimum, $reorder, $unitMeasure, $gl_sales_acct, $gl_inventory_acct,
                    $gl_costofsales_acct, $weberp_obj,$presLevel,$presNHIF,$Purchasing_Unit,$Unit_qty,$MoreInfo,$SalesAreas) {
    global $db;
    $debug = FALSE;

   if(checkStockidFromName($category)){
       $categ=GetItemCategoryName($category);
   }else{
       $categ=$category;
   }

    $sql2 = "update care_tz_drugsandservices set item_number = '$partcode' ,item_description = '$item_description'
            ,item_full_description ='$item_full_description',unit_price ='$unit_price',purchasing_class ='$purchasing_class'
            ,category='$categ',reorderlevel='$reorder',minimum='$minimum'
            ,maximum='$maximum',unit_measure='$unitMeasure'
            ,selling_price='$sellingPrice',item_status='$itemStatus'
            ,gl_sales_acct='$gl_sales_acct',gl_costofsales_acct='$gl_costofsales_acct',PresLevel='$presLevel'
            ,PresNHIF='$presNHIF',Purchasing_Unit='$Purchasing_Unit',Unit_qty='$Unit_qty',MoreInfo='MoreInfo'
            ,SalesAreas='$SalesAreas' where partcode ='$partcode'";

    if ($debug)
        echo $sql2;

    if ($db->Execute($sql2)) {
        echo "{success:true}";
//            transmitWeberp($partcode, $item_description, $item_full_description, $unit_price, $purchasing_class,
//                $catID, $itemStatus, $sellingPrice, $maximum, $minimum, $reorder, $unitMeasure, $stid = false);
    } else {
        echo "{'failure':'true','partcode':'$partcode'}";
    }
}

function transmitWeberp($partcode, $item_description, $item_full_description, $unit_price, $purchasing_class, $category,
                        $itemStatus, $sellingPrice, $maximum, $minimum, $reorder, $unitMeasure, $stid) {
    global $root_path;
    require_once($root_path . 'include/care_api_classes/class_weberp_c2x.php');
    require_once($root_path . 'include/inc_init_xmlrpc.php');
//    $itemCat=GetItemCategory($partcode);

    $itemdata[stockid] = $partcode;
    $itemdata[categoryid] = $category;
    $itemdata[description] = $item_description;
    $itemdata[longdescription] = $item_full_description;
//    $itemdata[units] = $unitMeasure;
//    $itemdata[mbflag] = 'B';
    //$itemdata[lastcurcostdate] = date('d-m-Y');
    $itemdata[actualcost] = $unit_price;
    $itemdata[lastcost] = $unit_price;

    if ($weberp_obj = new_weberp()) {
        if ($category) {
            $weberp_obj->create_stock_item_in_webERP($itemdata);
            $results = '{success: true }';
        } else {
            $weberp_obj->modify_stock_item_in_webERP($itemdata);
            $results = '{success: true }';
        }
    } else {

        $results = '{failure: true }';
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
    $users = $_SESSION['sess_login_username'];


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
	values( '" . $item_number . "', '" . $row[0] . "', '" . $qty . "', '$users',
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

function deleteItem() {
    global $db;
    $debug = false;
    $partcode = $_POST[partcode];
    $sql = "DELETE FROM care_ke_locstock where stockid='$partcode'";
    if ($debug)
        echo $sql;
    if ($db->Execute($sql)) {
        $sql1 = "INSERT INTO `care_tz_drugsandservices_del`
                  SELECT * FROM `care_tz_drugsandservices`  WHERE partcode='$partcode'";
        if ($debug)
            echo $sql1;
        if ($db->Execute($sql1)) {
            $query = 'DELETE FROM care_tz_drugsandservices WHERE partcode = "' . $partcode . '"';
            if ($debug)
                echo $query;
            if ($db->Execute($query)) { //returns number of rows deleted

                $accDB=$_SESSION['sess_accountingdb'];
//                $pharmLoc=$_SESSION['sess_pharmloc'];


                $sql1="DELETE FROM $accDB.stockcheckfreeze WHERE STOCKID='$partcode'";
                $db->Execute($sql1);
                $sql2="DELETE FROM $accDB.PRICES WHERE STOCKID='$partcode'";
                $db->Execute($sql2);
                $sql3="DELETE FROM $accDB.LOCSTOCK WHERE STOCKID='$partcode'";
                $db->Execute($sql3);
                $sql4="DELETE FROM $accDB.STOCKMASTER WHERE STOCKID='$partcode'";
                $db->Execute($sql4);



                echo 'Item Successfully deleted from the database';
            } else {
                echo "Unable to delete item from table, Please Contact System Admin";
            }
        } else {
            echo "Unable to log deleted item, Please Contact System Admin";
        }
    } else {
        echo "unable to delete item in store locations,Consult System Admin";
    }
}

?>
