
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
$searchParam = $_REQUEST[stparams];

$reqNo=$_REQUEST[reqNo];

$date1 = new DateTime($_REQUEST[reqDate]);
$reqDate = $date1->format("Y-m-d");

$inputUser=$_SESSION['sess_login_username'];

$transType=$_REQUEST[transType];

$task = ($_REQUEST['task']) ? ($_REQUEST['task']) : '';

switch ($task) {
    case "getOrdersTemplate":
        getOrdersTemplate($searchParam);
        break;
    case "getItemsList":
        getItemsList($searchParam);
        break;
    case "getRequisition":
        getRequisition($reqNo);
        break;
    case "saveOrders":
        saveOrders($reqNo,$reqDate,$inputUser);
        break;
    case "getRequisistions":
        getRequisistions();
        break;
    case "getOrders":
        getOrders();
        break;
    case "getDepartments":
        getDepartments();
        break;
    case "approveOrder":
        approveOrder($reqNo);
        break;
    case "getSuppliers":
        getSuppliers();
        break;
    case "completeOrders":
        completeOrder();
        break;
    case "updateOrdersTemp":
        updateOrdersTemp();
        break;
    case "updateQtyToOrder":
        updateQtyToOrder();
        break;
    case "generateLPO":
        generateLPO();
        break;
    case "GetTransNos":
        GetTransNos($transType);
        break;
    default:
        echo "{failure:true}";
        break;
}//end switch

Function GetTransNos ($TransType){
        global $db;
	$debug=false;
        $accDB=$_SESSION['sess_accountingdb'];
        $transDate=date('d-m-Y');
        
	$SQL = "SELECT transno FROM care_ke_transactionnos WHERE typeid = '" . $TransType . "'";
	$GetTransNoResult = $db->Execute($SQL);

	$myrow = $GetTransNoResult->FetchRow();

	echo "{'success':'true','transNo':'$myrow[0]','transDate':'$transDate'}";
}

Function GetWeberpTransNo ($TransType){
        global $db;
	
        $accDB=$_SESSION['sess_accountingdb'];
        
	$SQL = "SELECT typeno FROM $accDB.systypes WHERE typeid = '" . $TransType . "'";
	$GetTransNoResult = $db->Execute($SQL);

	$myrow = $GetTransNoResult->FetchRow();

	return $myrow[0];
}

function getCompanyDetails(){
    global $db;
    $debug=true;
    $sql = "SELECT * FROM care_ke_invoice";
    $result = $db->Execute($sql);
    $row=$result->FetchRow();
    
    return $row;
    if ($global_result) {
        while ($data_result = $global_result->FetchRow()) {
            $company = $data_result ['CompanyName'];
            $address = $data_result ['Address'];
            $town = $data_result ['Town'];
            $postal = $data_result ['Postal'];
            $tel = $data_result ['Tel'];
            $invoice_no = $data_result ['new_bill_nr'];
        }
        $global_config_ok = 1;
    } else {
        $global_config_ok = 0;
    }

}

function getSupplierDetails($supplierId){
    global $db;
    $debug=true;
    $accDB=$_SESSION['sess_accountingdb'];
    $sql="select * from $accDB.suppliers where supplierId='$supplierId'";
    if($debug) echo $sql;
    $results=$db->Execute($sql);
    $row=$results->FetchRow();
    
    return $row;
}

function generateLPO(){
    global $db;
    $debug=true;
    
    $supplierDetails=getSupplierDetails('ME001');
    $companyDetails=getCompanyDetails();
    $inputUser=$_SESSION['sess_login_username'];
    $orderDate=date('Y-m-d');
    $RequisitionNo= GetWeberpTransNo(18, $db);
    $OrderNo=GetWeberpTransNo(18, $db);
    
    $accDB=$_SESSION['sess_accountingdb'];
    
    $sql = "INSERT INTO maua.purchorders (orderno,supplierno,orddate,rate,initiator,requisitionno,intostocklocation,
        deladd1,deladd2,deladd3,deladd4,deladd5,deladd6,tel,suppdeladdress1,suppdeladdress2,suppdeladdress3,suppdeladdress4,
        suppdeladdress5,suppdeladdress6,supptel,version,revised,deliveryby,status,
        deliverydate,paymentterms,allowprint,stat_comment)
        VALUES('$OrderNo','ME001','$orderDate','1','$inputUser',
        '$RequisitionNo','MAIN','$companyDetails[CompanyName]','$companyDetails[Address]','$companyDetails[Town]',
        '$companyDetails[Postal]','$companyDetails[Tel]','$companyDetails[Email]',
        '$supplierDetails[tel]','$supplierDetails[suppname]','$supplierDetails[address1]',
        '$supplierDetails[address2]','$supplierDetails[address3]','$supplierDetails[supplieraddress4]',
        '$supplierDetails[supplieraddress5]','$supplierDetails[telephone]','1','$orderDate','2017-04-30','pending',
        '2017-04-30','1','0','LPO generated by $inputUser')";
    if($debug) echo $sql;
     
    $error=0;
    if($db->Execute($sql)){
            $sql = "INSERT INTO maua.purchorderdetails (orderno,itemcode,deliverydate,itemdescription,glcode,unitprice,
                        quantityord,suppliersunit,conversionfactor)
                    SELECT $OrderNo,o.stockid,'2017-04-30',o.description,'3113' AS glcode,IFNULL(d.lastcost,0) AS unit_price,
                        QtyToOrder,o.purchasingunit,IFNULL(s.unit_qty,0) AS unit_qty
                    FROM `care_ke_orders` o 
                    LEFT JOIN maua.stockmaster d ON o.stockid=d.stockid 
                    LEFT JOIN care_tz_drugsandservices s ON o.stockid=s.partcode 
                    WHERE o.QtyToOrder>0";
            if($debug) echo $sql;
            if($db->Execute($sql)){
                $SQL = "UPDATE $accDB.systypes SET typeno = typeno+1 WHERE typeid = '18'";
                 $db->Execute($SQL);
                
                $error=0;
            }else{
                $error=1;
            }
    }
    
    if($error==0){
        echo "{success:true}";
    }else{
        echo "{failure:true}";
    }
    

    
}

function completeOrder(){
    global $db;
    $debug=true;
    
    $sql="Insert into care_ke_orders(select * from care_ke_orders_template where QtyToOrder>1)";
    if($debug) echo $sql;
    if($db->Execute($sql)){
        echo "{success:true}";
    }else{
        echo "{failure:true}";
    }
}

function updateQtyToOrder(){
    global $db;
    $debug=false;
    
    $sql="Update care_ke_orders_template set QtyToOrder=SuggestedOrder";
    if($debug) echo $sql;
    if($db->Execute($sql)){
        echo "{success:true}";
    }else{
        echo "{failure:true}";
    }
}

function updateOrdersTemp(){
    global $db;
    $debug=true;
    
    $orderData=$_REQUEST[gridData];
    $orders = json_decode($orderData, true);

    $error=0;
        foreach ($orders as $row){
            $ID = $row['ID'];
            
                $sql = "UPDATE care_ke_orders_template SET ";
                unset($row['ID']);
      
                foreach ($row as $key => $value) {
                    $sql .= $key . '="' . $value . '", ';
                }
                $sql = substr($sql, 0, -2) . " WHERE ID='" .$ID . "'";
                if($db->Execute($sql)){
                    $error=0;
                }else{
                    $error=1;
                }
                if($debug) echo $sql;
        }

    if($error==0){
        echo "{success:true}";
    }else{
        echo "{failure:true}";
    }
}

function getSuppliers(){
    global $db;
    $debug=false;

    $sql="SELECT `supplierid`,`suppname` FROM maua.suppliers";

    if($debug) echo $sql;

    $results=$db->execute($sql);
    $total=$results->RecordCount();

    echo '{
    "total":"' . $total . '","suppliers":[';
    $counter = 0;
    while ($row = $results->FetchRow()) {
        $description=preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[suppname]);

        echo '{"SupplierCode":"' . $row[supplierid] . '","SupplierName":"' . $description.'"}';

        $counter++;
        if ($counter < $total) {
            echo ",";
        }
    }
    echo ']}';

}


function approveOrder($reqNo){
    global $db;
    $debug=true;

    $sql="Update care_ke_requisition set status='Approved' where RequisitionNo='$reqNo'";
    if($db->Execute($sql)){
        echo "{success:true}";
    }else{
        echo "{failure:true}";
    }
}

function getDepartments(){
    global $db;
    $debug=false;

    $sql='SELECT ID,`st_id`,`st_name`,`store`,`mainStore`,`Dispensing` FROM `care_ke_stlocation` order by st_name asc ';

    if($debug) echo $sql;

    $results=$db->execute($sql);
    $total=$results->RecordCount();

    echo '{
    "total":"' . $total . '","departments":[';
    $counter = 0;
    while ($row = $results->FetchRow()) {
        $description=preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[st_name]);

        echo '{"ID":"' . $row[ID] . '","DeptID":"' . $row[st_id]. '","Description":"' . $description. '","Store":"' . $row[store]
            . '","MainStore":"' . $row[mainStore] .'","Dispensing":"' . $row[Dispensing]. '"}';

        $counter++;
        if ($counter < $total) {
            echo ",";
        }
    }
    echo ']}';

}

function getOrders(){
    global $db;
    $debug=false;

    $sql='SELECT r.`Status`,r.`RequisitionNo`,r.`ReqDate`,r.`InputUser` AS InitiatedBy,r.Department,SUM(d.`Total`) AS Total
             FROM `care_ke_requisition` r LEFT JOIN `care_ke_requisitiondetails` d
            ON r.`RequisitionNo`=d.`RequisitionNo` 
            GROUP BY r.`RequisitionNo`';

    if($debug) echo $sql;

    $results=$db->execute($sql);
    $total=$results->RecordCount();

    echo '{
    "total":"' . $total . '","orders":[';
    $counter = 0;
    while ($row = $results->FetchRow()) {
        $description=preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[Description]);

        echo '{"Status":"' . $row[Status] . '","RequisitionNo":"' . $row[RequisitionNo]. '","ReqDate":"' . $row[ReqDate]
            . '","InitiatedBy":"' . $row[InitiatedBy] . '","Department":"' . $row[Department].'","Total":"' . $row[Total]. '"}';

        $counter++;
        if ($counter < $total) {
            echo ",";
        }
    }
    echo ']}';

}

function getRequisistions(){
    global $db;
    $debug=false;

    $sql='SELECT r.`Status`,r.`RequisitionNo`,r.`ReqDate`,d.`StockID`,d.`Description`,d.Category,d.`UnitQty`,d.`Price`,d.`Quantity`,d.`Total`
             FROM `care_ke_requisition` r LEFT JOIN `care_ke_requisitiondetails` d
            ON r.`RequisitionNo`=d.`RequisitionNo`';

    if($debug) echo $sql;

    $results=$db->execute($sql);
    $total=$results->RecordCount();

    echo '{
    "total":"' . $total . '","requisitions":[';
    $counter = 0;
    while ($row = $results->FetchRow()) {
        $description=preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[Description]);

        echo '{"Status":"' . $row[Status] . '","RequisitionNo":"' . $row[RequisitionNo]. '","ReqDate":"' . $row[ReqDate]
            . '","StockID":"' . $row[StockID] .'","Description":"' . $description .'","Category":"' .$row[Category]. '","UnitQty":"' . $row[UnitQty]
            . '","Price":"' . $row[Price] . '","Total":"' . $row[Total]. '"}';

        $counter++;
        if ($counter < $total) {
            echo ",";
        }
    }
    echo ']}';

}

function saveOrders($reqNo,$reqDate,$inputUser){
    global $db;
    $debug=false;

    $orderData=$_REQUEST[gridData];
    $orders = json_decode($orderData, true);

    $sql="INSERT INTO `care_ke_requisition` (
              `RequisitionNo`,`ReqDate`,`Status`,`Comment`,`InputUser`) 
            VALUES ('$reqNo','$reqDate', 'Pending','Comment', '$inputUser')";
    if($debug) echo $sql;

    $error=0;
    if($db->Execute($sql)){
        foreach ($orders as $row){
            $partcode = $row['StockID'];
            $description = $row['Description'];
            $category = $row['Category'];
            $unitqty = $row['UnitQty'];
            $price = $row['Price'];
            $qty = $row['Quantity'];
            $Total = $row['Quantity']*$row['Price'];

            $sql = "INSERT INTO `care_ke_requisitiondetails`(
                      `RequisitionNo`,`StockID`,`Description`,`Category`,`UnitQty`,`Price`,`Quantity`,`Total`) 
                    VALUES('$reqNo','$partcode','$description','$category','$unitqty','$price','$qty','$Total')";
            if ($debug) echo $sql;

            if($db->Execute($sql)){
                $error=0;
            }else{
               $error=1;
            }
        }
    }else{
        $error=1;
    }

    if($error==0){
        echo "{success:true}";
    }else{
        echo "{failure:true}";
    }
}


function getRequisition($reqNo){
    global $db;
    $debug=false;

    $sql="SELECT r.`ID`,r.`RequisitionNo`,r.`StockID`,r.`Description`,r.Category,r.`UnitQty`,r.`Price`,
        r.`Quantity`,r.`Total` FROM `care_ke_requisitionDetails` r where r.`RequisitionNo`='$reqNo'";

    if($debug) echo $sql;

    $results=$db->execute($sql);
    $total=$results->RecordCount();

    echo '{
    "total":"' . $total . '","itemslist":[';
    $counter = 0;
    while ($row = $results->FetchRow()) {
        $description=preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[Item_description]);

        echo '{"ID":"' . $row[ID] . '","RequisitionNo":"' . $row[RequisitionNo]. '","StockID":"' . $row[StockID]
            . '","Description":"' . $description . '","Category":"' . $row[Category] .'","UnitQty":"' . $row[UnitQty]
            . '","Price":"' . $row[Price]. '","Quantity":"' . $row[Quantity] . '","Total":"' . $row[Total]. '"}';

        $counter++;
        if ($counter < $total) {
            echo ",";
        }
    }
    echo ']}';

}

function getItemsList($searchParam){
    global $db;
    $debug=false;

    $sql="Select PartCode,Item_description,c.item_cat as category,unit_qty,unit_price,'0' as Quantity,
            '0' as Total from care_tz_drugsandservices d left join care_tz_itemscat c 
          on d.category=c.catid";

    if($searchParam<>""){
        $sql=$sql." WHERE PartCode='$searchParam' OR Item_descriptoin like '%$searchParam%'";
    }
    if($debug) echo $sql;

    $results=$db->execute($sql);
    $total=$results->RecordCount();

    echo '{
    "total":"' . $total . '","itemslist":[';
    $counter = 0;
    while ($row = $results->FetchRow()) {
        $description=preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[Item_description]);

        echo '{"StockID":"' . $row[PartCode] . '","Description":"' . $description. '","Category":"' . $row[category]. '","UnitQty":"' . $row[unit_qty]
            . '","Price":"' . $row[unit_price]. '","Quantity":"' . $row[Quantity]. '","Total":"' . $row[Total] . '"}';

        $counter++;
        if ($counter < $total) {
            echo ",";
        }
    }
    echo ']}';
}

function loadOrders($searchParam,$startDate){
    global $db;
    $debug=false;
     $sql="SELECT s.ID,s.`StockID`,`Description`,`PurchasingUnit`,l.`quantity`,`MonthlyUsage`,`UsedLastMonth`,
            `UsedLastQuarter`, `UsedLastYear`, s.`ReorderLevel`,`MaximumLevel`,`IfToOrder`,`SuggestedOrder`,
            `OrderInMultiplesOf`, `QtyToOrder`,`SupplierCode`, `AlternateSupplier`,  `Comments` 
         FROM  `care_ke_orders_template` s LEFT JOIN maua.`locstock` l ON s.`StockID`=l.`stockid`";

    if($searchParam<>""){
        $sql=$sql." WHERE s.StockID='$searchParam' OR Description like '%$searchParam%'";
    }
    if($debug) echo $sql;

    $results=$db->execute($sql);
    $total=$results->RecordCount();

    echo '{
    "total":"' . $total . '","orders":[';
    $counter = 0;
    while ($row = $results->FetchRow()) {

        $monthlyUsage=0;
        $reorderLevel=0;

        $description=preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[Description]);

        echo '{"ID":"' . $row[ID] . '","StockID":"' . $row[StockID]. '","Description":"' . $description . '","PurchasingUnit":"' . $row[PurchasingUnit]
            . '","Level":"' . $row[quantity] . '","MonthlyUsage":"' . $row[MonthlyUsage] . '","PreviousMonthUsage":"' . $row[UsedLastMonth]  .
            '","LastQuarterUsage":"' . number_format($row[UsedLastQuarter],2) . '","PreviousYearUsage":"' . number_format($row[UsedLastYear],2) 
                . '","ReorderLevel":"' .  number_format($row[ReorderLevel],2)
            . '","MaximumLevel":"' .  number_format($row[MaximumLevel],2)  . '","IFToOrder":"' . $row[IfToOrder]  . '","SuggestedOrder":"' . $row[SuggestedOrder]
            . '","OrderInMultiplesOf":"' . $row[OrderInMultiplesOf]  . '","QtyToOrder":"' . $row[QtyToOrder] . '","SupplierCode":"' . $row[SupplierCode]
            . '","AlternateSupplier":"' . $row[AlternateSupplier] . '","Comments":"' . $row[Comments]. '"}';

        $counter++;
        if ($counter < $total) {
            echo ",";
        }
    }
    echo ']}';
}

function getOrdersTemplate($searchParam,$startDate){
    global $db;
    $debug=true;

    $ordersTemp=$_REQUEST[ordersTemp];
    if($ordersTemp){
            $orders = json_decode($ordersTemp, true);

            $error=0;
            $ID=$orders[ID];
            unset($orders[ID]);
             $sql = "UPDATE care_ke_orders_template SET ";
                foreach ($orders as $key => $value){
                    
                    $sql .= $key . '="' . $value . '", ';
                }
                 $sql = substr($sql, 0, -2) . " WHERE ID='" .$ID . "'";
                if($debug) echo $sql;
                if($db->Execute($sql)){
                    $error=0;
                }else{
                    $error=1;
                }
            if($error==0){
                echo "{success:true}";
                 loadOrders($searchParam,$startDate);
            }else{
                echo "{failure:true}";
            }
    }else{
        loadOrders($searchParam,$startDate);
    }
    
   
}



?>
