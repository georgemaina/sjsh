<?php
/**
 * Created by PhpStorm.
 * User: george
 * Date: 10/9/2014
 * Time: 12:52 PM
 */
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');
//require ('getDataFunctions.php');


$limit = $_REQUEST[limit];
$start = $_REQUEST[start];
$formStatus = $_REQUEST[formStatus];
$searchParam = $_REQUEST[sParam];
$groupID=$_REQUEST[groupID];
$orderNos= $_REQUEST[OrderNos];
$TechID=$_REQUEST[techname];
$partIDs=$_REQUEST[partIDs];
$returnQtys=$_REQUEST[returnQtys];
$OrderNo=$_REQUEST[OrderNo];

//Insert and Update tables parameter
$ID = $_POST[ID];
$tableName = $_POST[tableName];
$fieldParam = $_POST[fieldParam];
$valueParam = $_POST[fieldValue];

$OrderNo=$_REQUEST['OrderNo'];
$OrderStatus=$_REQUEST['OrderStatus'];
$OrderDate=$_REQUEST['OrderDate'];
$Comment=$_REQUEST['Comment'];

$JobNo=$_REQUEST['JobNo'];

$PartNo=$_POST['PartNo'];
$StID=$_POST['StID'];


$itemdetails = $_POST;

$task = ($_REQUEST['task']) ? ($_REQUEST['task']) : '';

switch ($task) {

    case "updateOrders":
        if ($formStatus == 'update') {
            updateOrders($_POST);
        } else if ($formStatus == 'insert') {
            insertOrders($_POST);
        }
        break;
    case "updateCustomers":
        if ($formStatus == 'update') {
            updateCustomers($_POST);
        } else if ($formStatus == 'insert') {
            insertCustomers($_POST);
        }
        break;
    case "updateModels":
        if ($formStatus == 'update') {
            updateModels($_POST);
        } else if ($formStatus == 'insert') {
            insertModels($_POST);
        }
        break;
    case "updateTechnicians":
        if ($formStatus == 'update') {
            updateTechnicians($_POST);
        } else if ($formStatus == 'insert') {
            insertTechnicians($_POST);
        }
        break;
    case "assignJobs":
        assignJobs($orderNos,$TechID);
        break;
    case "updateJobAssignments":
        updateJobAssignments($_POST);
        break;
    case "updateStatusTypes":
        if ($formStatus == 'update') {
            updateStatusTypes($_POST);
        } else if ($formStatus == 'insert') {
            insertStatusTypes($_POST);
        }
        break;
    case "updateItems":
        if ($formStatus == 'update') {
            updateItem($itemdetails, $tableName, $fieldParam, $valueParam);
        } else {
            insertItem($itemdetails, $tableName, $fieldParam, $valueParam);
        }
        break;
    case "updateCompany":
        updateCompany($_POST);
        break;
    case "saveOrderedItems":
        saveOrderedItems($_POST);
        break;
    case "saveReceivedItems":
        saveReceivedItems($_POST);
        break;
    case "issueParts":
        issueParts($partIDs);
        break;
    case "saveReceivedItems":
        saveReceivedItems($_POST);
        break;
    case "updateEstimate":
        updateEstimate($_POST);
        break;
    case "updateInvoice":
        if ($formStatus == 'update') {
            updateInvoice($_POST);
        } else if ($formStatus == 'insert') {
            insertInvoices($_POST);
        }
        break;
    case "approveJobEstimate":
        approvejobEstimate($JobNo);
        break;
    case "rejectJobEstimate":
        rejectJobEstimate($JobNo);
        break;
    case "printInvoice":
        printInvoice($JobNo);
        break;
    case "qtyAdjustment":
        qtyAdjustment($PartNo,$StID);
        break;
    case "transferParts":
        transferParts();
        break;
    case "updateSales":
        updateSales($_POST);
        break;
    case "insertSalesInvoice";
        if ($formStatus == 'update') {
            updateSalesInvoice($_POST);
        } else if ($formStatus == 'insert') {
            insertSalesInvoice($_POST);
        }
        break;
    case "issueSalesParts":
        issueSalesParts($partIDs,$issueQtys);
        break;
    case "returnSalesParts":
        returnSalesParts($partIDs,$OrderNo);
        break;
    default:
        echo "{failure:true}";
        break;
}//end switch



function updateSales($salesOrderDetails){
    global $db;
    $debug=false;

    $OrderNo=$salesOrderDetails[OrderNo];
    $OrderDate=$salesOrderDetails[OrderDate];
    $CustomerCode=$salesOrderDetails[CustomerCode];
    $CustomerName=$salesOrderDetails[CustomerName];
    $OrderedBy=$_SESSION['userID'];
    $OrderStatus="Pending";  //$orderDetails[OrderStatus];

    $sql="INSERT INTO `Sales_Orders` (`OrderNo`,`OrderDate`, `CustomerCode`,`CustomerName`,`OrderedBy`,`OrderStatus`)
            VALUES('$OrderNo','$OrderDate','$CustomerCode','$CustomerName','$OrderedBy','$OrderStatus')" ;
    if($debug) echo $sql;

    if($db->Execute($sql)){
        $sql2="INSERT INTO `anisuma`.`sales_order_details` (
                      `OrderNo`,`OrderDate`,`PartNo`,`ItemDescription`,`QtyOrdered`,`QtyInvoiced`,`Location`,`QtyInStore`,
                      `UnitPrice`,`CostPrice`,`QtyIssued`,`TotalCost`,`Status`,`Comment`,`OrderedBy`,`IssuedBy`,`IssueDate`)
                SELECT `OrderNo`,`OrderDate`,`PartNo`,`ItemDescription`,`QtyOrdered`,`QtyInvoiced`,`Location`,`QtyInStore`,`UnitPrice`,
                      `CostPrice`,`QtyIssued`,`TotalCost`,`Status`,`Comment`,`OrderedBy`,`IssuedBy`,`IssueDate`
                  FROM `sales_order_details_temp` ";
        if($debug) echo $sql2;
        if($db->Execute($sql2)){

            //updateJobStatus($JobNo,8);

            $sql3="delete from sales_order_details_Temp where OrderNo='$OrderNo'";
            if($db->Execute($sql3)){
                $sql3="SELECT LastSalesNo+1 as LastSalesNo FROM company";
                $results=$db->execute($sql3);
                $row=$results->FetchRow();
                $newNo=$row[0]+1;

                $sql="update company set LastSalesNo=$newNo";
                if($db->Execute($sql)){
                    echo "{success:true}";
                }else{
                    echo "{failure:true}";
                }
            }else{
                echo "{failure:true,'error':'Unable to Remove Order No '.$OrderNo.' from Temp'}";
            }
        }else{
            echo "{failure:true,'error':'Unable to Add Order Items to Order No '.$OrderNo}";
        }
    }else{
        echo "{failure:true,'error':'Unable to Create Order '}";
    }

}

function transferParts(){
    global $db;
    $debug=true;

    $PartNo=$_POST[PartNo];
    $StID=$_POST[StID];
    $StID2=$_POST[StID2];
    $qty=$_POST[Quantity];
    $comment=$_POST[Comment];
    $dateTransfered=date('Y-m-d H:i:s');
    $period=date('m');
    $loggedUser=$_SESSION['userID'];

    $Store1CurrentQty=getCurrentQty($PartNo,$StID);
    $Store2CurrentQty=getCurrentQty($PartNo,$StID2);

    $Store1NewQty=$Store1CurrentQty-$qty;
    $Store2NewQty=$Store2CurrentQty+$qty;


    $sql="UPDATE locstock set Quantity='$Store2NewQty',comment='$comment' where StID='$StID2' and PartNo='$PartNo'";
    if($debug) echo $sql;

    if($db->Execute($sql)){
        $sql2="UPDATE locstock set Quantity='$Store1NewQty',comment='$comment' where StID='$StID' and PartNo='$PartNo'";
        if($debug) echo $sql2;
        $db->Execute($sql2);

        $sql3="INSERT INTO `stock_movement` (`PartNo`,`moveType`,`Location`,`transDate`,`price`,`period`,`reference`,`quantity`,`inputUser`,TransNo)
              VALUES ('$PartNo','Part Transfer','$StID2','$dateTransfered','0','$period','Transfered by $loggedUser'
              ,'$qty','$loggedUser','0')";
        $db->Execute($sql3);
        if($debug) echo $sql3;

        $results = '{success: true }';
    } else {
        $results = "{success: false,errors:'Could not Update Quantity'}"; // Return the error message(s)
    }
    echo $results;


}

function qtyAdjustment($PartNo,$StID){
    global $db;
    $debug = false;

    unset($_POST['formStatus']);
    unset($_POST['ID']);
    $dateAdjustment=date('Y:m:d H:i:s');
    $period=date('m');
    $loggedUser=$_SERVER['userID'];
    $qty=$_POST[Quantity];

   $sql="UPDATE locstock set Quantity='$qty' WHERE PartNo='$PartNo' and StID='$StID'";

    if ($debug)
        echo $sql;

    if ($db->Execute($sql)) {
        $sql="INSERT INTO `stock_movement` (`PartNo`,`moveType`,`Location`,`transDate`,`price`,`period`,`reference`,`quantity`,`inputUser`,TransNo)
              VALUES ('$PartNo','Qty Adjustment','$StID','$dateAdjustment','0','$period','Adjusted by $loggedUser'
              ,'$qty','$loggedUser','0')";
        if($debug) echo $sql;

        $results = '{success: true }';
    } else {
        $results = "{success: false,errors:'Could not Update Quantity'}"; // Return the error message(s)
    }
    echo $results;
}

function printInvoice($JobNo){
    global $db;
    $debug=true;

    $sql="Update Invoices set InvoiceStatus='2' where JobNo='$JobNo'";
    if($debug) echo $sql;
    if($db->Execute($sql)){
        updateJobStatus($JobNo,11,'Invoice Printed');
    }

}


function getTechnicianNameByID($techID){
    global $db;
    $debug=false;

    $sql="Select TechNames from technicians where TechID='$techID'";
    $result=$db->Execute($sql);
    $rcount=$result->RecordCount();
    if($rcount>0){
        $row=$result->FetchRow();

        return $row[0];
    }else{
        return false;
    }
}

function getTechnicianIDByName($techName){
    global $db;
    $debug=false;

    $sql="Select TechID from technicians where TechNames='$techName'";
    $result=$db->Execute($sql);
    $rcount=$result->RecordCount();
    if($rcount>0){
        $row=$result->FetchRow();

        return $row[0];
    }else{
        return false;
    }
}

function rejectJobEstimate($jobNo){
    global $db;
    $debug=false;

    $comment=$_POST[Comment];
    if($jobNo==''){
        $jobNo=$_POST[JobNo];
    }

        $sql="UPDATE Job_Assignments set JobStatus='13' where jobno='$jobNo'";
        if($debug) echo $sql;

    if($db->Execute($sql)){
        $sql2="UPDATE Job_Estimates set followupstatus='Rejected',Comments='$comment' where OrderNo='$jobNo'";
        if($debug) echo $sql2;
        $db->Execute($sql2);

        updateJobStatus($jobNo,14,$comment);
        echo '{success:true}';
    }else{
        echo '{failure:true}';
    }

}

function approveJobEstimate($jobNo){
    global $db;
    $debug=true;

    $invoiceNo=generateInvoiceNo();
    $invoiceDate=date('Y-m-d');
    $sql="SELECT e.OrderNo,e.ServiceType,o.`ReceivedDate`,o.`Modelno`,o.`SerialNo`,o.`CustomerName`,
                o.`JobType`,e.PartsAmount,e.TechCharges,e.TotalEstimate FROM job_estimates e
                LEFT JOIN orders o ON e.`OrderNo`=o.`OrderNo`
                WHERE e.OrderNo='$jobNo'";
    if($debug) echo $sql;
    $results=$db->Execute($sql);
    $rcount=$results->RecordCount();

    if($rcount>0){
        $error=0;
        $row=$results->FetchRow();
        $sql1="INSERT INTO `invoices` (
                 `InvoiceNo`,`InvoiceDate`,`InvoiceStatus`, `JobNo`,`ServiceType`,`ReceivedDate`,`Model`,`SerialNo`,
                  `Customer`,`JobType`,`PartsAmount`,`TechCharges`,`InvoiceAmount`)
              Values('$invoiceNo','$invoiceDate','1','$row[OrderNo]','$row[ServiceType]','$row[ReceivedDate]',
                    '$row[Modelno]','$row[SerialNo]','$row[CustomerName]','$row[JobType]','$row[PartsAmount]','$row[TechCharges]','$row[TotalEstimate]')";
        if($debug) echo $sql1;
        if($db->Execute($sql1)){
            $sql="UPDATE Job_Assignments set JobStatus='7' where jobno='$jobNo'";
            if($debug) echo $sql;
            $db->Execute($sql);

            $sql2="UPDATE Job_Estimates set followupstatus='Approved' where OrderNo='$jobNo'";
            if($debug) echo $sql2;
            $db->Execute($sql2);


            updateJobStatus($jobNo,7,'Job Estimate approved');

            $sql3 = "SELECT LastInvoiceNo+1 as LastInvoiceNo FROM company";
            $results = $db->execute($sql3);
            $row = $results->FetchRow();

            $sql = "update company set LastInvoiceNo=$row[0]";
            $db->Execute($sql);


            $error=0;
        }else{
            $error=2;
        }

    }
    if($error==0){
        echo '{success:true}';
    }else{
        echo '{failure:true}';
    }


}

function validateInvoice($jobNo){
    global $db;
    $debug=false;

    $sql="Select JobNo from invoices where JobNo='$jobNo'";
    if($debug) echo $sql;
    $results=$db->Execute($sql);
    $rcount=$results->RecordCount();

    if($rcount>0){
        return '1';
    }else{
        return '0';
    }
}

function generateInvoiceNo(){
    global $db;
    $debug=false;

    $sql="Select LastInvoiceNo from company";
    if($debug) echo $sql;

    $result=$db->Execute($sql);
    $row=$result->FetchRow();
    $lastNo=$row[0]+1;
    $newNo=$lastNo;

    return $newNo;

}


function insertSalesInvoice($InvoiceDetails) {
    global $db;
    $debug = false;

    //$table = $registerdetails['formtype'];
    unset($InvoiceDetails['formStatus']);
    unset($InvoiceDetails['ID']);

    $InvoiceDetails['InvoiceType']='Sales';


    if(validateInvoice($InvoiceDetails['JobNo'])=='1'){
        echo "{failure:true,'errNo':'1'}";
    }else {
        foreach ($InvoiceDetails as $key => $value) {
            $FieldNames .= $key . ', ';
            $FieldValues .= '"' . $value . '", ';
        }

        $sql = 'INSERT INTO Invoices (' . substr($FieldNames, 0, -2) . ') ' .
            'VALUES (' . substr($FieldValues, 0, -2) . ') ';

        if ($debug)
            echo $sql;
        if ($db->Execute($sql)) {

            $sql4 = "UPDATE sales_orders set InvoiceNo='$InvoiceDetails[InvoiceNo]' where OrderNo='$InvoiceDetails[OrderNo]'";
            $db->execute($sql4);

            $sql3 = "SELECT LastSalesInvoiceNo+1 as LastInvoiceNo FROM company";
            $results = $db->execute($sql3);
            $row = $results->FetchRow();

            $sql = "update company set LastSalesInvoiceNo=$row[0]";
            if ($db->Execute($sql)) {
                echo "{success:true}";
            } else {
                echo "{failure:true}";
            }
        } else {
            echo "{'failure':'true','InvoiceNo':'$InvoiceDetails[InvoiceNo]'}";
        }
    }
}

function insertInvoices($InvoiceDetails) {
    global $db;
    $debug = false;

    //$table = $registerdetails['formtype'];
    unset($InvoiceDetails['formStatus']);
    unset($InvoiceDetails['ID']);

    $InvoiceDetails['InvoiceType']='Service';


    if(validateInvoice($InvoiceDetails['JobNo'])=='1'){
        echo "{failure:true,'errNo':'1'}";
    }else {
        foreach ($InvoiceDetails as $key => $value) {
            $FieldNames .= $key . ', ';
            $FieldValues .= '"' . $value . '", ';
        }

        $sql = 'INSERT INTO Invoices (' . substr($FieldNames, 0, -2) . ') ' .
            'VALUES (' . substr($FieldValues, 0, -2) . ') ';

        if ($debug)
            echo $sql;
        if ($db->Execute($sql)) {
            $sql1="Update orders set AdvanceReceived='Yes',AdvanceAmount='$InvoiceDetails[AdvanceAmount]' where OrderNo='$InvoiceDetails[JobNo]'";
            $db->Execute($sql1);


            updateJobStatus($InvoiceDetails['JobNo'],10,'Invoice Created');

            $sql3 = "SELECT LastInvoiceNo+1 as LastInvoiceNo FROM company";
            $results = $db->execute($sql3);
            $row = $results->FetchRow();

            $sql = "update company set LastInvoiceNo=$row[0]";
            if ($db->Execute($sql)) {
                echo "{success:true}";
            } else {
                echo "{failure:true}";
            }
        } else {
            echo "{'failure':'true','InvoiceNo':'$InvoiceDetails[InvoiceNo]'}";
        }
    }
}

function updateSalesInvoice($InvoiceDetails){
    global $db;
    $debug = false;
    $sql = 'UPDATE Invoices SET ';

    $ID=$InvoiceDetails['ID'];
    unset($InvoiceDetails['formStatus']);
    unset($InvoiceDetails['ID']);
    $OrderNo=$InvoiceDetails['OrderNo'];

    foreach ($InvoiceDetails as $key => $value) {
        $sql .= $key . '="' . $value . '", ';
    }
    $sql = substr($sql, 0, -2) . " WHERE OrderNo='$OrderNo'";

    if ($debug)
        echo $sql;

    if ($db->Execute($sql)) {
        $results = '{success: true }';
    } else {
        $results = "{success: false,errors:{clientNo:'Could not update Company, Please check your values'}}"; // Return the error message(s)
    }
    echo $results;
}

function updateInvoice($InvoiceDetails){
    global $db;
    $debug = false;
    $sql = 'UPDATE Invoices SET ';

    $ID=$InvoiceDetails['ID'];
    unset($InvoiceDetails['formStatus']);
    unset($InvoiceDetails['ID']);

    foreach ($InvoiceDetails as $key => $value) {
        $sql .= $key . '="' . $value . '", ';
    }
    $sql = substr($sql, 0, -2) . " WHERE ID='$ID'";

    if ($debug)
        echo $sql;

    if ($db->Execute($sql)) {

        $sql="Update Invoices set InvoiceStatus='4' where JobNo='$InvoiceDetails[JobNo]'";
        $db->Execute($sql);

        updateJobStatus($InvoiceDetails['JobNo'],10,'Invoice Created');
        $results = '{success: true }';
    } else {
        $results = "{success: false,errors:{clientNo:'Could not update Company, Please check your values'}}"; // Return the error message(s)
    }
    echo $results;
}

function updateEstimate($estimateDetails){
    global $db;
    $debug=false;

    unset($estimateDetails['formStatus']);
    unset($estimateDetails['ID']);

    if(!$estimateDetails[EstimateSent]){
        $estimateDetails[EstimateSent]='0000-00-00 00:00:00';
    }

    if(!$estimateDetails[EstimateApproved]){
        $estimateDetails[EstimateApproved]='0000-00-00 00:00:00';
    }


        $sql = "INSERT INTO `job_estimates` (
                  `OrderNo`,`ServiceType`,`Customer`,`EstimatedBy`,`EstimatedOn`,`EstimateSent`,`EstimateApproved`,
                  `PartsAmount`,`TechCharges`,`TotalEstimate`,`CERA`,`FollowupStatus`,`NextFollowup`,`Comments`)
                  VALUES ('$estimateDetails[OrderNo]','$estimateDetails[ServiceType]','$estimateDetails[CustomerCode]',
                  '$estimateDetails[EstimatedBy]','$estimateDetails[EstimatedOn]','$estimateDetails[EstimateSent]','$estimateDetails[EstimateApproved]',
                  '$estimateDetails[PartsAmount]','$estimateDetails[TechAmount]','$estimateDetails[TotalEstimate]',
                  '$estimateDetails[CERA]','$estimateDetails[FollowupStatus]','$estimateDetails[NextFollowup]',
                  '$estimateDetails[Comments]')";

    if ($debug) echo $sql;
    if ($db->Execute($sql)) {
        $sql="Update orders set Estimated='Yes',EstimatedUpto='$estimateDetails[TotalEstimate]' where OrderNo='$estimateDetails[OrderNo]'";
        if($debug) echo $sql;
        $db->Execute($sql);

        $sql2="update job_assignments set JobStatus='6' where JobNo='$estimateDetails[OrderNo]'";
        if($debug) echo $sql2;
        $db->Execute($sql2);

        updateJobStatus($estimateDetails[OrderNo],6,'Waiting for Estimate Approval');

        echo '{success:true}';
    } else {
        echo "{'failure':'true','errNo':'2'}";
    }

}


function returnSalesParts($partIDs,$OrderNo){
    global $db;
    $debug=false;

    $dateReturned=date("Y-m-d H:i:s");
    $loggedUser=$_SESSION['userID'];
    $Location=$_POST['StID'];
    $ID=$_POST['ID'];
    $OrderNo=$_POST['OrderNo'];
    $returnQtys=$_POST[QtyReturned];
    $Comment=$_POST['Comment'];

    $period=date('m');

    $sql="SELECT s.ID,s.`OrderNo`,s.`OrderDate`,o.`CustomerName`,s.`PartNo`,s.`ItemDescription`,s.`QtyOrdered`,s.`QtyInvoiced`,s.`Location`,s.`QtyInStore`,
          s.`UnitPrice`,s.`CostPrice`,s.`QtyIssued`,s.`TotalCost`,s.`Status`,s.`Comment`, s.`OrderedBy`,s.`IssuedBy`,s.`IssueDate`
        FROM`sales_order_details` s LEFT JOIN sales_orders o ON s.`OrderNo`=o.`OrderNo`  WHERE s.ID =$ID and s.OrderNo='$OrderNo'";

    if($debug) echo $sql;
    $results1=$db->Execute($sql);



    $error=0;
    while($row=$results1->FetchRow()){
        $sql="INSERT INTO `stock_movement` (`PartNo`,`moveType`,`Location`,`transDate`,`price`,`period`,`reference`,`quantity`,`QtyinStore`,`inputUser`,TransNo,comment)
              VALUES ('$row[PartNo]','Sales Returns','$Location','$dateReturned','$row[CostPrice]','$period','Returned By $row[CustomerName]'
                    ,'$row[QtyOrdered]','$row[QtyInStore]','$loggedUser','$row[OrderNo]','$Comment')";
        if($debug) echo $sql;

        if($db->Execute($sql)) {

            $newQty = intval($row[QtyInStore]) + intval($row[$returnQtys]);

            $sql3 = "Update LocStock set quantity=$newQty where PartNo='$row[PartNo]' and StID='$Location'";
            if ($debug) echo $sql3;
            $db->Execute($sql3);

            $sql3 = "Update sales_orders set OrderStatus=4 where OrderNo='$row[OrderNo]'";
            if ($debug) echo $sql3;
            $db->Execute($sql3);

            $sql4 = "Update sales_order_details set Status='4',QtyIssued='$row[QtyOrdered]',IssuedBy='$loggedUser',IssueDate='$dateReturned',Comment='$Comment'
                            where PartNo='$row[PartNo]' and OrderNo='$row[OrderNo]'";
            if ($debug)
                echo $sql4;
            if ($db->Execute($sql4)) {
                $error = 0;
            } else {
                $error = 1;
            }
        }
    }
    if($error==0){
        echo "{success:true}";
    }else{
        echo "{failure:true}";
    }
}


function issueSalesParts($partIDs,$issueQtys){
    global $db;
    $debug=false;

    $dateIssued=date("Y-m-d H:i:s");
    $loggedUser=$_SESSION['userID'];
    $period=date('m');

    $sql="SELECT s.ID,s.`OrderNo`,s.`OrderDate`,o.`CustomerName`,s.`PartNo`,s.`ItemDescription`,s.`QtyOrdered`,s.`QtyInvoiced`,s.`Location`,s.`QtyInStore`,
          s.`UnitPrice`,s.`CostPrice`,s.`QtyIssued`,s.`TotalCost`,s.`Status`,s.`Comment`, s.`OrderedBy`,s.`IssuedBy`,s.`IssueDate`
        FROM`sales_order_details` s LEFT JOIN sales_orders o ON s.`OrderNo`=o.`OrderNo`  WHERE s.ID IN ($partIDs)";

    if($debug) echo $sql;
    $results1=$db->Execute($sql);

    $error=0;
    while($row=$results1->FetchRow()){
        $sql="INSERT INTO `stock_movement` (`PartNo`,`moveType`,`Location`,`transDate`,`price`,`period`,`reference`,`quantity`,`QtyinStore`,`inputUser`,TransNo)
              VALUES ('$row[PartNo]','Sales Issues','$row[Location]','$dateIssued','$row[CostPrice]','$period','Issued to $row[CustomerName]'
                    ,'$row[QtyOrdered]','$row[QtyInStore]','$loggedUser','$row[OrderNo]')";
        if($debug) echo $sql;

        if($db->Execute($sql)) {

            $newQty = intval($row[QtyInStore]) - intval($row[QtyOrdered]);

            $sql3 = "Update LocStock set quantity=$newQty where PartNo='$row[PartNo]' and StID='$row[Location]'";
            if ($debug) echo $sql3;
            $db->Execute($sql3);

            $sql3 = "Update sales_orders set OrderStatus=3 where OrderNo='$row[OrderNo]'";
            if ($debug) echo $sql3;
            $db->Execute($sql3);

            $sql4 = "Update sales_order_details set Status='3',QtyIssued='$row[QtyOrdered]',IssuedBy='$loggedUser',IssueDate='$dateIssued'
                            where PartNo='$row[PartNo]' and OrderNo='$row[OrderNo]'";
            if ($debug)
                echo $sql4;
            if ($db->Execute($sql4)) {
                $error = 0;
            } else {
                $error = 1;
            }
        }
    }
    if($error==0){
        echo "{success:true}";
    }else{
        echo "{failure:true}";
    }
}

function issueParts($partIDs){
    global $db;
    $debug=true;

    $dateIssued=date("Y-m-d H:i:s");
    $loggedUser=$_SESSION['userID'];
    $period=date('m');

    $sql1="SELECT
              o.`ID`,o.`PartNo`,o.`ItemDescription`,`OrderNo`,`OrderStatus`,`OrderDate`,`QtyOrdered`,`QtyIssued`,i.`SellingPrice`,
              `OrderedBy`,`IssuedBy`,`DateIssued`,`comment`,o.`JobNo`,`StID`
            FROM `item_order_details` o LEFT JOIN inventory i ON o.`PartNo`=i.`PartNo`
            WHERE o.ID IN ($partIDs)";
    if($debug) echo $sql1;
    $results1=$db->Execute($sql1);

    $error=0;
    while($row=$results1->FetchRow()){
        $sql="INSERT INTO `stock_movement` (`PartNo`,`moveType`,`Location`,`transDate`,`price`,`period`,`reference`,`quantity`,`inputUser`,TransNo)
              VALUES ('$row[PartNo]','Issues','$row[StID]','$dateIssued','$row[SellingPrice]','$period','Issued to $row[OrderedBy]','$row[QtyOrdered]','$loggedUser','$row[OrderNo]')";
        if($debug) echo $sql;

        if($db->Execute($sql)) {
            $sql2 = "Select quantity from locstock where PartNo='$row[PartNo]' and StID='$row[StID]'";
            $result = $db->Execute($sql2);
            $row2 = $result->FetchRow();
            $newQty = $row2[0] - $row[QtyOrdered];

            $sql3 = "Update LocStock set quantity=$newQty where PartNo='$row[PartNo]' and StID='$row[StID]'";
            if ($debug) echo $sql3;
            $db->Execute($sql3);

            $sql3 = "Update item_orders set OrderStatus=3 where OrderNo='$row[OrderNo]'";
            if ($debug) echo $sql3;
            $db->Execute($sql3);

            updateJobStatus($row[JobNo],12,'Parts Issued by '.$loggedUser);

            $sql4 = "Update item_order_details set OrderStatus='3',QtyIssued='$row[QtyOrdered]',IssuedBy='$loggedUser',DateIssued='$dateIssued' where PartNo='$row[PartNo]'";
            if ($debug)
                echo $sql4;
            if ($db->Execute($sql4)) {
                $error = 0;
            } else {
                $error = 1;
            }
        }
    }
    if($error==0){
        echo "{success:true}";
    }else{
        echo "{failure:true}";
    }
}

function saveOrderedItems($orderDetails){
    global $db;
    $debug=false;
    $JobNo=$orderDetails[JobNo];
    $OrderNo=$orderDetails[OrderNo];
    $OrderDate=$orderDetails[OrderDate];
    $OrderedBy=$_SESSION['userID'];
    $OrderStatus="1";  //$orderDetails[OrderStatus];

    $sql="INSERT INTO `item_orders` (`OrderNo`,`JobNo`, `OrderStatus`,`OrderDate`,`OrderedBy`)
            VALUES('$OrderNo','$JobNo','$OrderStatus','$OrderDate','$OrderedBy')" ;
    if($debug) echo $sql;

    if($db->Execute($sql)){
        $sql2="INSERT INTO `item_order_details` (
                  `PartNo`,`ItemDescription`,`OrderNo`,`JobNo`,`OrderStatus`,`OrderDate`,`StID`,`QtyInStore`,
                  `QtyOrdered`,`QtyIssued`,`OrderedBy`,`IssuedBy`,`DateIssued`,`comment`
                )
                SELECT `PartNo`,`ItemDescription`,`OrderNo`,`JobNo`,`OrderStatus`,`OrderDate`,`StID`,`QtyInStore`,
                  `QtyOrdered`,`QtyIssued`,`OrderedBy`,`IssuedBy`,`DateIssued`,`comment`
                FROM `item_order_details_temp` WHERE OrderNo='$OrderNo'";
        if($debug) echo $sql2;
        if($db->Execute($sql2)){

            updateJobStatus($JobNo,8,'New Job Created');

            $sql3="delete from item_order_details_Temp where OrderNo='$OrderNo'";
            if($db->Execute($sql3)){
                $sql3="SELECT LastOrderNo+1 as LastOrderNo FROM company";
                $results=$db->execute($sql3);
                $row=$results->FetchRow();

                $sql3="update item_order_details set OrderStatus='1' where  OrderNo='$OrderNo'";
                $db->Execute($sql3);

                $sql="update company set LastOrderNo=$row[0]";
                if($db->Execute($sql)){
                    echo "{success:true}";
                }else{
                    echo "{failure:true}";
                }
            }else{
                echo "{failure:true,'error':'Unable to Remove Order No '.$OrderNo.' from Temp'}";
            }
        }else{
            echo "{failure:true,'error':'Unable to Add Order Items to Order No '.$OrderNo}";
        }
    }else{
        echo "{failure:true,'error':'Unable to Create Order '}";
    }

}



function getCurrentQty($partNo,$StID){
    global $db;
    $debug=false;

    $sql="Select Quantity from locstock where PartNo='$partNo' and StID='$StID'";
    if($debug) echo $sql;

    $result=$db->Execute($sql);
    $rowCount=$result->RecordCount();
    if($rowCount>0){
        $row=$result->FetchRow();
        $currQty=$row[0];
    }

    return $currQty;
}

function saveReceivedItems($orderDetails){
    global $db;
    $debug=true;
    $grnNo=$orderDetails[grnNo];
    $receiveDate=$orderDetails[ReceiveDate];
    $receivedBy=$_SESSION['userID'];
    $Comment=$orderDetails[Comment];


    $sql="SELECT `ID`,`grnno`,`partNo`,`itemDescription`,`deliverydate`,`StID`,`QtyInStore`,`qtyReceived`,`quantityinv`,`stdcostunit`,
            `receivedBy` FROM`grns_temp` where grnno='$grnNo'";
    if($debug) echo $sql;
    $results=$db->Execute($sql);
    while($row=$results->FetchRow()) {
        $sql1 = "INSERT INTO `stock_movement` (
              `PartNo`,`moveType`,`transNo`,`Location`,`QtyInStore`,`transDate`,`price`,`period`,`reference`,`quantity`,`inputUser`,`comment`)
            VALUES('$row[partNo]','Receive','$grnNo','$row[StID]','$row[QtyInStore]','$receiveDate','0.00', '0','$row[itemDescription] Received By $receivedBy',
            '$row[qtyReceived]','$receivedBy','$Comment')";
        if ($debug) echo $sql1;
        if ($db->Execute($sql1)) {
            $oldQty = getCurrentQty($row[partNo],$row[StID]);
            $newQty = $oldQty + $row[qtyReceived];

            $sql2 = "Update LocStock set Quantity='$newQty' where PartNo='$row[partNo]' and StID='$row[StID]'";
            if($debug) echo $sql2;
            $db->Execute($sql2);

        }
    }

        $sql2="INSERT INTO `grns` (`grnno`,`partNo`,`itemDescription`,`deliverydate`,`qtyReceived`,
                  `quantityinv`,`stdcostunit`,`receivedBy`)
                SELECT `grnno`,`partNo`,`itemDescription`,`deliverydate`,`qtyReceived`,
                  `quantityinv`,`stdcostunit`,`receivedBy` FROM `grns_Temp` WHERE grnNo='$grnNo'";
        if($debug) echo $sql2;
        if($db->Execute($sql2)){
            $sql3="delete from grns_Temp where grnNo='$grnNo'";
            if($debug) echo $sql3;
            if($db->Execute($sql3)){

                $sql3="SELECT LastGrnNo+1 as LastGrnNo FROM company";
                $results=$db->execute($sql3);
                $row=$results->FetchRow();

                $sql="update company set LastGrnNo=$row[0]";
                $db->Execute($sql);

                echo '{success:true}';
            }else{
                echo "{failure:true,'error':'Unable to Remove Order No '.$grnNo.' from Temp'}";
            }
        }else{
            echo "{failure:true,'error':'Unable to receive Items on grnNo '.$grnNo}";
        }

}


function updateCompany($companyDetails){
    global $db;
    $debug = false;
    $sql = 'UPDATE Company SET ';
    unset($companyDetails['formStatus']);
    foreach ($companyDetails as $key => $value) {
        $sql .= $key . '="' . $value . '", ';
    }
    $sql = substr($sql, 0, -2) . ' WHERE ID="1"';

    if ($debug)
        echo $sql;

    if ($db->Execute($sql)) {
        $results = '{success: true }';
    } else {
        $results = "{success: false,errors:{clientNo:'Could not update Company, Please check your values'}}"; // Return the error message(s)
    }
    echo $results;
}


function validateItem($tableName, $fieldParam, $valueParam) {
    global $db;
    $debug = false;

    $sql = "Select $fieldParam from $tableName where $fieldParam='$valueParam'";
    if ($debug) {
        echo $sql;
    }
    $request = $db->Execute($sql);
    $numRows = $request->RecordCount();

    if ($numRows > 0) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function insertItem($itemdetails, $tableName, $fieldParam, $valueParam) {
    global $db;
    $debug = false;

    //$table = $registerdetails['formtype'];
    if (validateItem($tableName, $fieldParam, $itemdetails["$fieldParam"])) {
        echo '{failure:true,"errNo":"1"}';
    } else {
        unset($itemdetails['formStatus']);

        unset($itemdetails['tableName']);
        unset($itemdetails['fieldParam']);
        unset($itemdetails['fieldValue']);

        foreach ($itemdetails as $key => $value) {
            $FieldNames.=$key . ', ';
        $FieldValues.='"' . $value . '", ';
    }

    $sql = "INSERT INTO $tableName (" . substr($FieldNames, 0, -2) . ")" .
        'VALUES (' . substr($FieldValues, 0, -2) . ') ';

        if ($debug)
            echo $sql;
        if ($db->Execute($sql)) {

            if($tableName=='inventory'){

                $sql2="SELECT StID FROM inventory_locations";
                $results=$db->Execute($sql2);
                while($row=$results->FetchRow()){
                    $sql3="INSERT INTO `locstock` ( `PartNo`, `StID`,`Quantity`,`Comment`)
                        VALUES('".$itemdetails[PartNo]."','$row[0]','0','')";
                    if($debug) echo $sql3;
                    $db->Execute($sql3);
                }
            }
            echo '{success:true}';
        } else {
            echo "{'failure':'true','errNo':'2'}";
        }
    }
}

function updateItem($itemdetails, $tableName, $fieldParam, $valueParam) {
    global $db;
    $debug = false;
    $id = $itemdetails['ID'];
    $paramField=$valueParam;
    $sql = "UPDATE $tableName SET ";
    unset($itemdetails['formStatus']);
    unset($itemdetails['ID']);
    unset($itemdetails['tableName']);
    unset($itemdetails['fieldParam']);
    unset($itemdetails['fieldValue']);

    foreach ($itemdetails as $key => $value) {
        $sql .= $key . '="' . $value . '", ';
    }
    $sql = substr($sql, 0, -2) . " WHERE $valueParam='" .$id . "'";

    if ($debug)
        echo $sql;

    if ($db->Execute($sql)) {
        $results = '{success: true }';
    } else {
        $results = "{failure: true}"; // Return the error message(s)
    }
    echo $results;
}

function updateStatusTypes($statusDetails){
    global $db;
    $debug=true;

    $sql = 'UPDATE repair_status SET ';
    $ID=$statusDetails['ID'];
    unset($statusDetails['ID']);
    unset($statusDetails['formStatus']);
    foreach ($statusDetails as $key => $value) {
        $sql .= $key . '="' . $value . '", ';
    }
    $sql = substr($sql, 0, -2) . ' WHERE ID="' . $ID . '"';

    if ($debug)
        echo $sql;

    if ($db->Execute($sql)) {
        $results = '{success: true }';
    } else {
        $results = "{success: false,errors:{jobNo:'Could not update Status List, Please check your values'}}"; // Return the error message(s)
    }
    echo $results;
}


function insertStatusTypes($statusDetails){
    global $db;
    $debug = false;

    //$table = $registerdetails['formtype'];
    unset($statusDetails['formStatus']);
    unset($statusDetails['ID']);

    foreach ($statusDetails as $key => $value) {
        $FieldNames.=$key . ', ';
        $FieldValues.='"' . $value . '", ';
    }

    $sql = 'INSERT INTO repair_status(' . substr($FieldNames, 0, -2) . ') ' .
        'VALUES (' . substr($FieldValues, 0, -2) . ') ';

    if ($debug)
        echo $sql;
    if ($db->Execute($sql)) {
        echo '{success:true}';
    } else {
        echo "{'failure':'true'}";
    }
}


function updateJobAssignments($assignmentDetails){
    global $db;
    $debug=false;

    $sql = 'UPDATE job_assignments SET ';
    $ID=$assignmentDetails['ID'];
    unset($assignmentDetails['ID']);
    unset($assignmentDetails['formStatus']);
    foreach ($assignmentDetails as $key => $value) {
        $sql .= $key . '="' . $value . '", ';
    }
    $sql = substr($sql, 0, -2) . ' WHERE ID="' . $ID . '"';

    if ($debug)
        echo $sql;

    if ($db->Execute($sql)) {
        updateJobStatus($assignmentDetails[JobNo],$assignmentDetails[JobStatus],$assignmentDetails['AssignmentInfo']);
        $results = '{success: true }';
    } else {
        $results = "{success: false,errors:{jobNo:'Could not update Assignments, Please check your values'}}"; // Return the error message(s)
    }
    echo $results;
}

function updateJobStatus($jobNo,$jobStatus,$Comments){
    global $db;
    $debug=false;
    $statusChangeDate=date("Y-m-d H:i:s");
    $loggedUser=$_SESSION['userID'];

    $sql="Insert into jobstatus(JobNo,JobStatus,StatusDate,UpdatedBy,Comments)
          Values('$jobNo','$jobStatus','$statusChangeDate','$loggedUser','$Comments')";
    if($debug) echo $sql;
    if($db->Execute($sql)){
        $sql1="Update job_assignments set JobStatus='$jobStatus' where JobNo='$jobNo'";
        $db->Execute($sql1);
    }else{
        return 1;
    }
}


function assignJobs($orderNos,$TechID){
    global $db;
    $debug=false;

    $dateAssigned=date("Y-m-d H:i:s");
    $loggedUser=$_SESSION['userID'];
    //$receivedBy=$_SESSION['userID'];

    $strTechID=getTechnicianIDByName($TechID);

    $sql1="SELECT orderNo,ShortComplaint,`RepPriority`,`status` FROM orders
          WHERE Assigned=0 and orderNo in ('$orderNos')";
    if($debug) echo $sql1;
    $results1=$db->Execute($sql1);

    $error=0;
    while($row=$results1->FetchRow()){
        $sql="INSERT INTO `job_assignments` (`JobNo`,`TechID`,`Complaint`,`RepPriority`,`JobStatus`,`DateAssigned`,`AssignedBy`,`DateCreated`)
              VALUES ('$row[orderNo]','$strTechID','$row[ShortComplaint]','$row[RepPriority]','9','$dateAssigned','$loggedUser','$dateAssigned')";
        if($debug) echo $sql;

        updateJobStatus($row[orderNo],9,'Assigned job to '.$TechID);

        if($db->Execute($sql)){
            $sql2="Update orders set Assigned=1,AssignedTo='$TechID' where OrderNo='$row[orderNo]'";
            if($debug) echo $sql2;
            if($db->Execute($sql2)){
                $error=0;
            }else{
                $error=1;
            }
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

function insertTechnicians($registerdetails) {
    global $db;
    $debug = false;

    //$table = $registerdetails['formtype'];
    unset($registerdetails['formStatus']);

    foreach ($registerdetails as $key => $value) {
        $FieldNames.=$key . ', ';
        $FieldValues.='"' . $value . '", ';
    }

    $sql = 'INSERT INTO Technicians(' . substr($FieldNames, 0, -2) . ') ' .
        'VALUES (' . substr($FieldValues, 0, -2) . ') ';

    if ($debug)
        echo $sql;
    if ($db->Execute($sql)) {
        echo '{success:true}';
    } else {
        echo "{'failure':'true','pid':'$registerdetails[ID]'}";
    }
}

function updateTechnicians($registerdetails) {
    global $db;
    $debug = FALSE;
    $sql = 'UPDATE Technicians SET ';
    $techID=$registerdetails['TechID'];
    unset($registerdetails['formStatus']);
    unset($registerdetails['TechID']);
    foreach ($registerdetails as $key => $value) {
        $sql .= $key . '="' . $value . '", ';
    }
    $sql = substr($sql, 0, -2) . ' WHERE TechID="' . $techID . '"';

    if ($debug)
        echo $sql;

    if ($db->Execute($sql)) {
        $results = '{success: true }';
    } else {
        $results = "{success: false,errors:{clientNo:'Could not update Technicians, Please check your values'}}"; // Return the error message(s)
    }
    echo $results;
}

function insertModels($registerdetails) {
    global $db;
    $debug = false;

    unset($registerdetails['formStatus']);


   $sql = "INSERT INTO `makesandmodels` (`ModelNo`,`Model`,`Make`)
             VALUE( '$_POST[ModelNo]','$_POST[Model]','$_POST[Make]')";

    if ($debug)
        echo $sql;
    if ($db->Execute($sql)) {
        echo '{success:true}';
    } else {
        echo "{'failure':'true','pid':'$registerdetails[ID]'}";
    }
}

function updateModels($registerdetails) {
    global $db;
    $debug = true;
    $sql = 'UPDATE makesandmodels SET ';
    unset($registerdetails['formStatus']);
    foreach ($registerdetails as $key => $value) {
        $sql .= $key . '="' . $value . '", ';
    }
    $sql = substr($sql, 0, -2) . ' WHERE ModelNo="' . $registerdetails['ModelNo'] . '"';

    if ($debug)
        echo $sql;

    if ($db->Execute($sql)) {
        $results = '{success: true }';
    } else {
        $results = "{success: false,errors:{clientNo:'Could not update Debtor, Please check your values'}}"; // Return the error message(s)
    }
    echo $results;
}


function insertCustomers($registerdetails) {
    global $db;
    $debug = false;

    //$table = $registerdetails['formtype'];
    unset($registerdetails['formStatus']);
    unset($registerdetails['ID']);

    foreach ($registerdetails as $key => $value) {
        $FieldNames.=$key . ', ';
        $FieldValues.='"' . $value . '", ';
    }

    $sql = 'INSERT INTO customers (' . substr($FieldNames, 0, -2) . ') ' .
        'VALUES (' . substr($FieldValues, 0, -2) . ') ';

    if ($debug) echo $sql;
    if ($db->Execute($sql)) {
        echo '{success:true}';
    } else {
        echo "{'failure':'true'}";
    }
}

function getCustomerTypeID($desc){
    global $db;

    $sql="Select ID from customer_types where Description='$desc'";
    if($result=$db->Execute($sql)){
        $row=$result->FetchRow();

        return $row[0];
    }else{
        return '';
    }

}

function updateCustomers($registerdetails) {
    global $db;
    $debug = false;

    if(is_int($registerdetails['CustomerType'])){
        $registerdetails['CustomerType']=$registerdetails['CustomerType'];
    }else{
        $registerdetails['CustomerType']=getCustomerTypeID($registerdetails['CustomerType']);
    }


    $sql = 'UPDATE customers SET ';

    $ID=$registerdetails['ID'];

    unset($registerdetails['formStatus']);
    unset($registerdetails['ID']);

    foreach ($registerdetails as $key => $value) {
        $sql .= $key . '="' . $value . '", ';
    }
    $sql = substr($sql, 0, -2) . ' WHERE ID="' . $ID . '"';

    if ($debug)
        echo $sql;

    if ($db->Execute($sql)) {
        $results = '{success: true }';
    } else {
        $results = "{success: false}"; // Return the error message(s)
    }
    echo $results;
}

function insertOrders($registerdetails) {
    global $db;
    $debug = true;

    //$table = $registerdetails['formtype'];
    unset($registerdetails['formStatus']);

    foreach ($registerdetails as $key => $value) {
        $FieldNames.=$key . ', ';
        $FieldValues.='"' . $value . '", ';
    }

    $sql = 'INSERT INTO orders (' . substr($FieldNames, 0, -2) . ') ' .
        'VALUES (' . substr($FieldValues, 0, -2) . ') ';

    if ($debug)
        echo $sql;
    if ($db->Execute($sql)) {
        echo '{success:true}';
    } else {
        echo "{'failure':'true','pid':'$registerdetails[ID]'}";
    }
}

function updateOrders($registerdetails) {
    global $db;
    $debug = false;
    $sql = 'UPDATE orders SET ';
    unset($registerdetails['formStatus']);
    foreach ($registerdetails as $key => $value) {
        $sql .= $key . '="' . $value . '", ';
    }
    $sql = substr($sql, 0, -2) . ' WHERE ID="' . $registerdetails['ID'] . '"';

    if ($debug)
        echo $sql;

    if ($db->Execute($sql)) {
        $results = '{success: true }';
    } else {
        $results = "{success: false,errors:{clientNo:'Could not update Debtor, Please check your values'}}"; // Return the error message(s)
    }
    echo $results;
}

