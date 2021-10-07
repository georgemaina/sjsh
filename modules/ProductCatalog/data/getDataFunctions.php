
<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');
require_once($root_path . 'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path . 'include/inc_init_xmlrpc.php');
require_once($root_path.'global_conf/areas_allow.php');

$allowedarea=$allow_area['admit'];

$weberp_obj = new_weberp();

$limit = $_REQUEST['limit'];
$start = $_REQUEST['start'];
$formStatus = $_REQUEST['formStatus'];
$searchParam = $_REQUEST['searchParam'];
$locParam=$_REQUEST['location'];

$item_number = $_POST['PartCode'];
$partcode = $_POST['PartCode'];
$item_description = $_POST['Item_Description'];
$item_full_description = $_POST['Item_Full_Description'];
$unit_price = $_POST['Unit_Price'];
$purchasing_class = $_POST['Purchasing_Class'];
$category = $_POST['Category'];
$itemStatus = $_POST['Item_Status'];
$sellingPrice = $_POST['selling_price'];
$maximum = $_POST['Minimum'];
$minimum = $_POST['Maximum'];
$reorder = $_POST['ReorderLevel'];
$unitMeasure = $_POST['Unit_Measure'];
$gl_sales_acct = $_POST['Gl_Sales_Acct'];
$gl_inventory_acct = $_POST['Gl_Inventory_Acct'];
$gl_costofsales_acct = $_POST['Gl_CostOfSales_Acct'];
$Purchasing_Unit = $_POST['Purchasing_Unit'];
$Unit_qty = $_POST['Unit_Qty'];
$MoreInfo = $_POST['MoreInfo'];
$SalesArea = $_POST['SalesAreas'];
$presLevel = $_POST['PresLevel'];
$presNHIF = $_POST['PresNHIF'];
$isStockItem=$_POST['stockItem'];
$chargeInAdmission=$_POST['ChargeInAdmission'];
$isConsultation=$_POST['isConsultation'];
//$category=GetItemCategory($_REQUEST[partcode]);

$currUser=$_SESSION["sess_login_username"];

$units = 'each';

$task = ($_REQUEST['task']) ? ($_REQUEST['task']) : '';

switch ($task) {
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
        if($formStatus =='insert'){
             InsertItems($partcode, $item_description, $item_full_description, $unit_price, $purchasing_class, $category,
                    $itemStatus, $sellingPrice,$maximum, $minimum, $reorder, $unitMeasure, $gl_sales_acct,
                    $gl_inventory_acct, $gl_costofsales_acct, $weberp_obj,$presLevel,$presNHIF,
                    $Purchasing_Unit,$Unit_qty,$MoreInfo,$SalesAreas,$isStockItem,$chargeInAdmission,$isConsultation);
        }else{
             updateItem($partcode, $item_description, $item_full_description, $unit_price, $purchasing_class, $category,
                     $itemStatus, $sellingPrice, $maximum, $minimum, $reorder, $unitMeasure, $gl_sales_acct, $gl_inventory_acct,
                     $gl_costofsales_acct, $weberp_obj,$presLevel,$presNHIF,$Purchasing_Unit,$Unit_qty,$MoreInfo,$SalesArea
					 ,$isStockItem,$chargeInAdmission,$isConsultation);
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
        getItemLocations2($searchParam,$locParam);
        break;
    case "getCashPoints":
        getCashPoints($start, $limit);
        break;
    case "getPaymentModes":
        getPaymentModes($start, $limit);
        break;
    case "getCurrentPos":
        getCurrentPos($start, $limit);
        break;
    case "getSalesItems":
        getSalesItems($start, $limit);
        break;
    case "SaveReceipt":
        SaveReceipt($start, $limit);
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
    case "InsertCategory":
        InsertCategory();
        break;
    case "insertNewItemLocation":
        if($formStatus=='insert'){
             insertNewItemLocation($_POST);
        }else{
            updateItemLocation($_POST, $partcode);
        }
        break;
    case "InsertSubCategory":
        InsertSubCategory();
        break;
    case "InsertLocations":
        InsertLocations();
        break;
    case "getStoreUsers":
        getStoreUsers();
        break;
    case "getUsers":
        getUsers();
        break;
    case "insertUsers":
        InsertUsers();
        break;
    case "removeUser":
        removeUser();
        break;
    default:
        echo "{failure:true}";
        break;
}//end switch

function removeUser(){
    global $db;
    $debug=false;

    $Username=$_REQUEST['UserName'];
    $StoreID= $_REQUEST['StoreID'];

    $sql="Delete from `care_ke_storeusers` where Username='$Username' and st_id='$StoreID'";
    if($debug) echo $sql;

    if($db->Execute($sql)){
        echo "{success:true,'msg':'User Successfully removed from Location'}";
    }else{
        ECHO "{failure:true,'msg':'Failed to remove user from Location'}";
    }
}

function InsertUsers(){
    global $db;
    $debug=false;

    $Username=$_POST['UserName'];
    $StoreID= $_POST['StoreID'];
    $IssueDrugs= $_POST['issueDrugs'];
    $ServiceDrugs= $_POST['serviceDrugs'];

    $sql="INSERT INTO `care_ke_storeusers` (
                    `Username`,`st_id`,IssueDrugs,ServiceDrugs
                  ) VALUES('$Username','$StoreID','$IssueDrugs','$ServiceDrugs') ;";
    if($debug) echo $sql;

    if($db->Execute($sql)){
        echo "{success:true,'msg':'User Successfully added to Location'}";
    }else{
        ECHO "{failure:true,'msg':'Failed to add user to Location'}";
    }
}

function getUsers() {
    global $db;
    $debug=FALSE;
    $sql = "SELECT `name`,`login_id` FROM `care_users`";
    
    if ($debug) echo $sql;
    $result = $db->Execute($sql);
    $total = $result->RecordCount();

    echo '{
    "total":"' . $total . '","users":[';
    $counter = 0;
    while ($row = $result->FetchRow()) {

        echo '{"Name":"' . $row['name'] . '","LoginID":"' . $row['login_id'] .'"}';

        $counter++;

        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']}';
}


function getStoreUsers() {
    global $db;
    $debug=false;
    $sql = "SELECT s.`ID`,`Username`,L.`ID`,s.`st_id`,L.`st_name`,s.IssueDrugs,s.ServiceDrugs FROM `care_ke_storeusers` s
            LEFT JOIN care_ke_stlocation l ON s.`st_id`=l.`st_id`";
    
    if ($debug) echo $sql;
    $result = $db->Execute($sql);
    $total = $result->RecordCount();

    echo '{
    "total":"' . $total . '","storeusers":[';
    $counter = 0;
    while ($row = $result->FetchRow()) {

        echo '{"ID":"' . $row['ID'] . '","UserName":"' . $row['Username'] . '","StoreID":"' . $row['st_id']
        . '","StoreName":"' . $row['st_name']. '","IssueDrugs":"' . $row['IssueDrugs']
            . '","ServiceDrugs":"' . $row['ServiceDrugs'] . '"}';

        $counter++;

        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']}';
}

function InsertLocations(){
    global $db;
    $debug=false;

    $ID=$_POST[ID];
    $Description=$_POST['Description'];
    $subStore= ($_POST['subStore']<>'' ? $_POST['subStore']:'0');
    $mainStore=($_POST['mainStore']<>'' ? $_POST['mainStore']:'0');
    $dispensStore=($_POST['dispensStore']<>'' ? $_POST['dispensStore']:'0');

    $sql="Insert into care_ke_stLocation(st_id,st_name,store,mainStore,Dispensing) "
            . "values('$ID','$Description','$subStore','$mainStore',$dispensStore)";
    if($debug) echo $sql;

    if($db->Execute($sql)){
        echo "{success:true,'msg':'Location successfully saved'}";
    }else{
        ECHO "{failure:true,msg:failed to save Location}";
    }
}



function InsertSubCategory(){
    global $db;
    $debug=FALSE;
    
    $catID=$_POST['CatID'];
     $itemCat=$_POST['CatName'];
      $parentID=$_POST['ParentID'];
    
    $sql="Insert into care_tz_itemscat(CatID,item_cat,item_type) values('$catID','$itemCat','$parentID')";
    if($debug) echo $sql;
    
    if($db->Execute($sql)){
        echo "{success:true}";
    }else{
        echo "{failure:true}";
    }
}


function InsertCategory(){
    global $db;
    $debug=false;
    
    $catName=$_POST['CatName'];
    
    $sql="Insert into care_tz_categories(catName) values('$catName')";
    if($debug) echo $sql;
    
    if($db->Execute($sql)){
        echo "{success:true}";
    }else{
        echo "{failure:true}";
    }
}

function insertPriceTypes(){
    global $db;
    $debug=false;

    $priceTypes=$_POST['priceTypes'];

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

    $group=$_REQUEST['group'];

    $sql = "Select accountcode,accountname from litein.chartmaster";

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

        echo '{"AccountCode":"' . $row['accountcode'] . '","AccountName":"' . $row['accountname'] . '"}';


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
    $debug = TRUE;

    $table = $itemslist['formtype'];
    unset($itemslist['formtype']);
    unset($itemslist['formStatus']);
    unset($itemslist['IdParam']);
    $partcode = $itemslist['partcode'];
    $FieldNames='';
    $FieldValues='';
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
        echo "{'failure':'true','partcodeS':'$partcode'}";
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

    $FieldNames='';
    $FieldValues='';

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
        echo "{'failure':'true'}";
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
        if ($row['unit_price'] <> '') {
            $price = $row['unit_price'];
        } else {
            $price = 0;
        }

        if ($row['quantity'] <> '') {
            $qty = $row['quantity'];
        } else {
            $qty = 0;
        }
        echo '{"itemcode":"' . $row['partcode'] . '","description":"' . $row['item_description'] . '","qty":' . $qty
        . ',"loccode":"' . $row['loccode'] . '","price":' . $price . '}';



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
//    $priceType=$_REQUEST[priceType];
    if($_REQUEST[priceType]=='Ordinary'){
        $priceType=1;
    }else{
        $priceType=2;
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

        echo '{"ID":"' . $row['ID'] . '","PriceType":"' . $row['PriceType'] . '"}';

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

    $FieldNames='';
    $FieldValues='';

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

    $typeID=getPriceTypeIDFromName($rctData['PriceType']);

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

    $searchParam=$_REQUEST['searchParam'];
    $priceType=$_REQUEST['priceType'];

    $sql = "SELECT p.`partcode`,d.`item_description`,p.`priceType` as TypeID,t.`PriceType`,p.`price` FROM care_ke_prices p 
            INNER JOIN care_tz_drugsandservices d ON p.`partcode`=d.`partcode`
            INNER JOIN `care_ke_pricetypes` t ON p.`priceType`=t.`ID` where p.partcode<>''";


    if (isset($searchParam)) {
            $sql.=" and p.partcode like '%$searchParam%' OR d.item_description like '%$searchParam%'";
    }
    
    if(isset($priceType) && $priceType<>''){
        $sql.=" and t.ID = '$priceType'";
    }

    $sql.=" ORDER BY p.`partcode` ASC";

    if ($debug) echo $sql;

    $results = $db->Execute($sql);
    $total = $results->RecordCount();

    echo '{
    "total":"' . $total . '","itemprices":[';
    $counter = 0;
    while ($row = $results->FetchRow()) {
        $description=trim(preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row['item_description']));

        echo '{"PartCode":"' . $row['partcode'] . '","Description":"' . $description . '","PriceID":"' . $row['TypeID']
            . '","PriceType":"' . $row['PriceType'] . '","Price":"' . $row['price']. '"}';

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

        echo '{"ID":"' . $row['ID'] . '","PriceType":"' . $row['PriceType'] . '","PartCode":"' . $row['PartCode']
        . '","Price":"' . $row['Price'] . '"}';

        $counter++;

        if ($counter <> $total) {
            echo ",";
        }
    }
    echo ']}';
}

if (!function_exists('json_esc')) {
    function json_esc($input, $esc_html = true) {
        $result = '';
        if (!is_string($input)) {
            $input = (string) $input;
        }

        $conv = array("\x08" => '\\b', "\t" => '\\t', "\n" => '\\n', "\f" => '\\f', "\r" => '\\r', '"' => '\\"', "'" => "\\'", '\\' => '\\\\');
        if ($esc_html) {
            $conv['<'] = '\\u003C';
            $conv['>'] = '\\u003E';
        }

        for ($i = 0, $len = strlen($input); $i < $len; $i++) {
            if (isset($conv[$input[$i]])) {
                $result .= $conv[$input[$i]];
            }
            else if ($input[$i] < ' ') {
                $result .= sprintf('\\u%04x', ord($input[$i]));
            }
            else {
                $result .= $input[$i];
            }
        }

        return $result;
    }
}

function escapeJsonString($value) { # list from www.json.org: (\b backspace, \f formfeed)
    $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
    $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
    $result = str_replace($escapers, $replacements, $value);
    return $result;
}

function getItemsList($searchParam, $start, $limit) {
    global $db;
    $debug = false;
    $category = $_REQUEST['category'];

    $sql = "SELECT partcode,item_description,item_full_description,
              `unit_measure`,unit_price,unit_price_1,selling_price,purchasing_class, c.`item_Cat` AS category,item_status,`reorderlevel`
              ,`minimum`,maximum,gl_sales_acct,gl_inventory_acct,gl_costofsales_acct,PresLevel,PresNHIF,d.SalesAreas,
              Purchasing_Unit,Unit_qty,MoreInfo,isStockItem,ChargeInAdmission,isConsultation
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

//        $desc= trim(preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[item_description]));
        $desc= escapeJsonString($row['item_description']);
//        $desc2= trim(preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[item_full_description]));
        $desc2= escapeJsonString($row['item_full_description']);
		
      echo '{"PartCode":"' . $row['partcode'] . '","Item_Description":"' . $desc . '","Item_Full_Description":"' . $desc2
            . '","Unit_Measure":"' . $row['unit_measure']  . '","Unit_Price":"' . $row['unit_price'] . '","Purchasing_Class":"' . $row['purchasing_class'] . '","Category":"' . $row['category']
            . '","Item_Status":"' . $row['item_status'] . '","Minimum":"' . $row['minimum']. '","Maximum":"' . $row['maximum'] . '","Gl_Sales_Acct":"' . $row['gl_sales_acct']
            . '","Gl_Inventory_Acct":"' . $row['gl_inventory_acct']. '","Gl_CostOfSales_Acct":"' . $row['gl_costofsales_acct']. '","PresLevel":"' . $row['PresLevel']
            . '","PresNHIF":"' . $row['PresNHIF'] . '","SalesAreas":"' . $row['SalesAreas']. '","Purchasing_Unit":"' . $row['Purchasing_Unit']
            . '","Unit_Qty":"' . $row['Unit_qty'] . '","MoreInfo":"' . $row['MoreInfo'] . '","IsStockItem":"' . $row['isStockItem'] 
            . '","ChargeInAdmission":"' . $row['ChargeInAdmission']. '","isConsultation":"' . $row['isConsultation'].'"}';

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

        echo '{"ID":"' . $row['id'] . '","Name":"' . $row['name'] . '"}';


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

        echo '{"ID":"' . $row['ID'] . '","CatName":"' . $row['catName'] . '"}';

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
    $category = $_REQUEST['query'];

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

        echo '{"CatID":"' . $row['catID'] . '","ItemCat":"' . trim($row['item_cat']) . '"}';

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

        echo '{"ID":"' . $row['ID'] . '","Description":"' . $row['Description'] . '"}';


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

//    if (isset($start) && isset($limit)) {
//        $sql.=" limit $start,$limit";
//    }

    if ($debug)
        echo $sql;

    $request = $db->Execute($sql);

    $total = $request->RecordCount();

    echo '{
    "total":"' . $total . '","storeLocations":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {

        echo '{"ID":"' . $row['st_id'] . '","Description":"' . $row['st_name'] . '","Store":"' . $row['store'] . '","MainStore":"' . $row['mainStore'] . '"}';


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

    $FieldNames='';
    $FieldValues='';

    foreach ($rctData as $key => $value) {
        $FieldNames.=$key . ', ';
        $FieldValues.='"' . $value . '", ';
    }

    $sql = "update care_ke_locstock set
	quantity='$rctData[Quantity]',item_status='$rctData[Item_Status]'
        where stockid='$rctData[PartCode]' and loccode='$rctData[LocCode]'";

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
    $debug = FALSE;

    $FieldNames='';
    $FieldValues='';
    
    UNSET($rctData['formStatus']);
    $rctData['StockID']=$rctData['PartCode'];
    UNSET($rctData['PartCode']);

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
        echo "{success:true,'msg':'Added Item In $rctData[LocCode] Store Successfully'}";
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
    $partcode = $_REQUEST['partcode'];

    $searchParam=$_REQUEST['searchParam'];
    $locParam=$_REQUEST['locParam'];

    $json = $_REQUEST['editData'];
    $rctData = json_decode(file_get_contents("php://input"), true);

    $loccode = $rctData['stockid'];

    if (count($rctData) > 0) {
        
        if (verifyItemLocation($rctData['stockid'], $rctData['loccode'])) {
            updateItemLocation($rctData, $partcode);
        } else {
            insertNewItemLocation($rctData);
        }
        getItemLocations2($searchParam,$locParam);
    } else {
        getItemLocations2($searchParam,$locParam);
    }
}

    function getItemLocations2($searchParam,$locParam) {
        global $db;
        $debug = false;

        $sql = "SELECT l.`loccode`,l.`stockid`,d.`item_description`,d.`unit_measure`,d.`units`,l.`quantity`,i.`description`
                    FROM `care_ke_locstock` l 
                    LEFT JOIN care_tz_drugsandservices d ON l.`stockid`=d.`partcode` 
                    LEFT JOIN care_ke_itemstatus i ON l.item_status=i.id where l.loccode<>''";

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
             $desc= trim(preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row['item_description']));
            echo '{"LocCode":"' . $row['loccode'] . '","PartCode":"' . $row['stockid'] . '","Description":"' . $desc
                . '","UnitMeasure":"' . $row['unit_measure'] . '","Conversion":"' . $row['units'] 
                    . '","Quantity":"' . $row['quantity']. '","ReorderLevel":"' . $row['quantity']. '","Item_Status":"' . $row['description']. '"}';

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

        echo '{"st_id":"' . $row['st_id'] . '","st_name":"' . $row['st_name'] . '"}';

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

function InsertItems($partcode, $item_description, $item_full_description, $unit_price, $purchasing_class, $category,
                    $itemStatus, $sellingPrice,$maximum, $minimum, $reorder, $unitMeasure, $gl_sales_acct,
                    $gl_inventory_acct, $gl_costofsales_acct, $weberp_obj,$presLevel,$presNHIF,
                    $Purchasing_Unit,$Unit_qty,$MoreInfo,$SalesAreas,$isStockItem,$chargeInAdmission,$isConsultation)
{
    global $db;
    $debug = false;

    $stid = checkStockid($partcode);
//    $catID=GetItemCategoryID($partcode);

    //$errorMsg = 0;
    
   $shortDesc=addslashes($item_description);
   $longDesc=addslashes($item_full_description);
    if ($stid > 0) {
        $errorMsg = 1;
    } else {
        $sql = 'insert into care_tz_drugsandservices(item_number, 
                partcode, item_description, item_full_description, unit_price, purchasing_class,category,
                reorderlevel,minimum,maximum,unit_measure,selling_price,item_status, gl_sales_acct,gl_inventory_acct, gl_costofsales_acct
                ,PresLevel,PresNHIF,Purchasing_Unit,Unit_qty,MoreInfo,SalesAreas,isStockItem,ChargeInAdmission,isConsultation)
                values("' . $partcode . '", "' . $partcode . '", "' . $shortDesc . '", "' . $longDesc . '",
                "' . $unit_price . '", "' . $purchasing_class . '","' . $category . '","' . $reorder . '","' . $minimum . '","' . $maximum . '","' . $unitMeasure
            . '","' . $sellingPrice . '","' . $itemStatus . '","' . $gl_sales_acct . '","' . $gl_inventory_acct
            . '","' . $gl_costofsales_acct . '","' . $presLevel . '","' . $presNHIF
            . '","' . $Purchasing_Unit . '","' . $Unit_qty . '","' . $MoreInfo . '","' . $SalesAreas . '","' . $isStockItem  
            . '","' . $chargeInAdmission . '","' . $isConsultation. '")';
        
		if ($debug) echo $sql;
		
         if ($db->Execute($sql)) {
             if ($purchasing_class = 'Drug_List' || $purchasing_class = 'Medical-Supplies') {
                $sql = "Insert into care_locstock(LOCCODE,STOCKID,QUANTITY) VALUES ('DISPENS','$partcode','0')";
                $db->Execute($sql);

                 $errorMsg = 0;
            }
            
           transmitWeberp($partcode, $item_description, $item_full_description, $unit_price, $purchasing_class, $category, $itemStatus,
                $sellingPrice, $maximum, $minimum, $reorder, $unitMeasure, $stid = true);

        }else{
             $errorMsg=2;
        }  
    }

    if($errorMsg==0){
        echo "{success:true, 'msg':'Item Successfully Saved'}";
    }else if($errorMsg==1){
        echo "{failure:true, 'msg':'Item Code Already Exists'}";
    }else if($errorMsg==2){
        echo "{failure:true, 'msg':'Could not save Item, Please check your values'}";
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
                    $gl_costofsales_acct, $weberp_obj,$presLevel,$presNHIF,$Purchasing_Unit,$Unit_qty,$MoreInfo,$SalesAreas,
                    $isStockItem,$chargeInAdmission,$isConsultation) {
    global $db;
    $debug = false;

   if(checkStockidFromName($category)){
       $categ=GetItemCategoryName($category);
   }else{
       $categ=$category;
   }
   
   $shortDesc=addslashes($item_description);
   $longDesc=addslashes($item_full_description);

    $sql2 = "update care_tz_drugsandservices set item_number = '$partcode' ,item_description = '$shortDesc'
            ,item_full_description ='$longDesc',unit_price ='$unit_price',purchasing_class ='$purchasing_class'
            ,category='$categ',reorderlevel='$reorder',minimum='$minimum'
            ,maximum='$maximum',unit_measure='$unitMeasure'
            ,selling_price='$sellingPrice',item_status='$itemStatus'
            ,gl_sales_acct='$gl_sales_acct',gl_costofsales_acct='$gl_costofsales_acct',PresLevel='$presLevel'
            ,PresNHIF='$presNHIF',Purchasing_Unit='$Purchasing_Unit',Unit_qty='$Unit_qty',MoreInfo='MoreInfo'
            ,SalesAreas='$SalesAreas',isStockItem='$isStockItem',ChargeInAdmission='$chargeInAdmission'
            ,isConsultation='$isConsultation' where partcode ='$partcode'";

    if ($debug)
        echo $sql2;

    if ($db->Execute($sql2)) {
        echo "{success:true,'msg':'Item Updated Successfully Saved'}";
//            transmitWeberp($partcode, $item_description, $item_full_description, $unit_price, $purchasing_class,
//                $catID, $itemStatus, $sellingPrice, $maximum, $minimum, $reorder, $unitMeasure, $stid = false);
    } else {
        echo "{'failure':'true','msg':'Unable to Update Item'}";
    }
}

function transmitWeberp($partcode, $item_description, $item_full_description, $unit_price, $purchasing_class, $category,
                        $itemStatus, $sellingPrice, $maximum, $minimum, $reorder, $unitMeasure, $stid) {
    global $root_path;
    require_once($root_path . 'include/care_api_classes/class_weberp_c2x.php');
    require_once($root_path . 'include/inc_init_xmlrpc.php');
//    $itemCat=GetItemCategory($partcode);

    $itemdata['stockid'] = $partcode;
    $itemdata['categoryid'] = $category;
    $itemdata['description'] = $item_description;
    $itemdata['longdescription'] = $item_full_description;
//    $itemdata[units] = $unitMeasure;
//    $itemdata[mbflag] = 'B';
    //$itemdata[lastcurcostdate] = date('d-m-Y');
    $itemdata['actualcost'] = $unit_price;
    $itemdata['lastcost'] = $unit_price;

    if ($weberp_obj = new_weberp()) {
        if ($category) {
            $weberp_obj->create_stock_item_in_webERP($itemdata);
           // $results = '{success: true }';
        } else {
            $weberp_obj->modify_stock_item_in_webERP($itemdata);
            //$results = '{success: true }';
        }
    } else {

        $results = '{failure: true }';
    }
    echo $results;
}

function stockAdjust($item_number) {
    global $db;
    $item_number = $_REQUEST['item_number'];
    $item_description = $_REQUEST['item_Description'];
    $qty = $_REQUEST['quantity'];
    $roorder = $_REQUEST['reorderlevel'];
    $loccode = $_REQUEST['loccode'];
    $adjDesc = $_REQUEST['comment'];
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
