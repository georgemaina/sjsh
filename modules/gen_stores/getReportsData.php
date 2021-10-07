<?php
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');

require_once($root_path . 'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path . 'include/inc_init_xmlrpc.php');
$limit = $_POST[limit];
$start = $_POST[start];
$item_number = $_POST[item_number];


$task = ($_REQUEST['task']) ? ($_REQUEST['task']) : '';
switch ($task) {
    case "getLevels":
        getLevels($limit, $start);
        break;
    case "getOrders":
        getOrders();
        break;
    case "getIssues":
        getIssues();
        break;
    case "getAdjustments":
        getAdjustments();
        break;
    case "getValuation":
        getValuations($limit, $start);
        break;
    default:
        echo "{failure:true}";
        break;
}//end switch

function getValuations($limit, $start) {
    global $db;
    $debug=false;

    $accDB=$_SESSION['sess_accountingdb'];
    $pharmLoc=$_SESSION['sess_pharmloc'];

    $catID1 = $_REQUEST[catID1];
    $catID2 = $_REQUEST[catID2];
    $detsum = $_POST[detsum];
    $storeid=$_REQUEST[storeid];
    $sql1 = 'select catID,item_Cat from care_tz_itemscat where catID="' . $catID1 . '"';
    $result1 = $db->Execute($sql1);
    $catName = $result1->FetchRow();


    $sql = "SELECT b.PartCode, k.loccode,b.Item_Description,k.Quantity,k.reorderlevel,e.item_cat,s.`lastcost`,(s.`lastcost` * k.`quantity`) AS TotalCost
                FROM care_tz_drugsandservices b LEFT JOIN care_tz_itemscat e ON b.category=e.catid LEFT JOIN care_ke_locstock k ON k.stockid=b.item_number
                LEFT JOIN $accDB.`stockmaster` s ON k.`stockid`=s.`stockid` WHERE b.category <>'' AND K.`quantity`>0 and s.lastcost>0";

    if ($storeid <> '') {
        $sql.=" and k.loccode ='$storeid'";
    }

    if($debug) echo $sql;

    $result = $db->Execute($sql);
//    $numRows = $result->RecordCount();
//    echo $sql;

    $sql2 = 'select count(item_id) from care_tz_drugsandservices';
    $result2 = $db->Execute($sql2);
    $total = $result2->FetchRow();
    $total = $total[0];

    $counter = 0;
    echo '<table id="pharmacy"><tr>
                    <th align="center" class=titles>PartCode</th>
                    <th align="center" class=titles>Loccode</th>
                    <th align="center" =titles>item_Description</th>
                    <th align="center" class=titles>Quantity</th>
                    <th align="center" class=titles>Category</th>
                    <th align="center" class=titles>Cost</th>
                    <th align="center" class=titles>TotalCost</th></tr>';
    $bg = '';
    while ($row = $result->FetchRow()) {

        $itmNo = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[PartCode]);
        $fDesc1 = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[Item_Description]);
        echo '<tr>
                    <td>' . $itmNo . '</td><td>' . $row[loccode] . '</td><td>' . $fDesc1 . '</td><td>' . $row[Quantity]
                    . '</td><td>' . $row[item_cat] . '</td><td>' . $row[lastcost] . '</td><td>' . $row[TotalCost] . '</td>
             </tr>';
        $rowbg = 'white';
    }
    echo '</table>';
}


function getLevels($limit, $start) {


    global $db;
    $catID = $_REQUEST[catID];
    $itemName = $_POST[itemName];
    $storeid=$_REQUEST[storeid];
    $qtyFilter=$_REQUEST[qtyFilter];

    $sql1 = 'select catID,item_Cat from care_tz_itemscat where catID="' . $catID . '"';
//    echo $sql1;
    //$sql1='SELECT DISTINCT b.catid,b.item_cat FROM care_tz_itemscat b left jOIN care_tz_drugsandservices c ON b.catID=c.category  where catID="'.$catID.'" ORDER BY b.item_cat asc';
    $result1 = $db->Execute($sql1);
    $catName = $result1->FetchRow();


    $sql = "select b.item_number, k.loccode,b.item_Description,k.quantity,k.reorderlevel,e.item_cat,k.comment
        from care_tz_drugsandservices b left join care_tz_itemscat e
        on b.category=e.catid
        left join care_ke_locstock k on k.stockid=b.item_number where b.category <>''";

    if ($catID <> '') {
        $sql.=" and b.category ='$catID'";
    }

    if ($itemName <> '') {
        $sql.=" and b.item_Description ='$itemName'";
    }

    if ($storeid <> '') {
        $sql.=" and k.loccode ='$storeid'";
    }

    if($qtyFilter<>""){
        switch($qtyFilter){
            case "neg":
                $sql.=" and k.quantity<0";
                break;
            case "zero":
                $sql.=" and k.quantity=0";
                break;
            case "pos":
                $sql.=" and k.quantity>0";
                break;
            case "reorder":
                $sql.=" and k.quantity<k.reorderlevel";
                break;

        }
    }

    $result = $db->Execute($sql);
//    $numRows = $result->RecordCount();
    //echo $sql;

    $sql2 = 'select count(item_id) from care_tz_drugsandservices';
    $result2 = $db->Execute($sql2);
    $total = $result2->FetchRow();
    $total = $total[0];

    $counter = 0;
    echo '<table id="pharmacy"><tr>
                    <th align="center" class=titles>item_number</th>
                    <th align="center" class=titles>loccode</th>
                    <th align="center" =titles>item_Description</th>
                    <th align="center" class=titles>Quantity</th>
                    <th align="center" class=titles>reorderlevel</th></tr>';
    $bg = '';
    while ($row = $result->FetchRow()) {
//        if ($bg == "#a9a9a9")
//            $bg = "#ffffdd";
//        else
//            $bg = "#a9a9a9";
        $desc = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[5]);
        $fDesc = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[6]);
        $itmNo = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[0]);
        $fDesc1 = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[2]);
        echo '<tr>
                    <td>' . $itmNo . '</td><td>' . $row[1] . '</td><td>' . $fDesc1 . '</td><td>' . $row[3] . '</td><td>' . $row[4] . '</td>
             </tr>';
        $rowbg = 'white';
    }
    echo '</table>';
}

function getOrders() {
    global $db;
    $rstatus = $_REQUEST['ordStatus'];
    $ordLoc = $_REQUEST['ordLoc'];
    $orddt1 = $_REQUEST['orddt1'];
    $orddt2 = $_REQUEST['orddt2'];
    $inputUser = $_REQUEST['inputUser'];

    if ($rstatus == 'Pending') {
        $sql = "SELECT r.req_no, r.status, r.issueNo, r.req_date, r.req_time, r.Store_desc, r.sup_storeDesc, r.item_id,'' AS at_hand, r.Item_desc,
                    r.qty AS ordered, d.`unit_price`, r. unit_msr, r.unit_cost, r.qty_issued, r.issue_date, r.issue_time, r.balance, (d.`unit_price` * r.qty) AS Total, r.period, r.input_user
                    FROM `care_ke_internalreq` r LEFT JOIN care_tz_drugsandservices d ON r.item_id=d.`partcode`
                where r.status='Pending'";
        $statDate = 'Order Date';
        $statQty = 'Qty Ordered';
    } elseif ($rstatus == 'Serviced') {

        $sql = "SELECT req_no, STATUS, issueNo, req_date, req_time, Store_desc, sup_storeDesc, s.item_id, Item_desc, qty AS ordered, d.`unit_price`, unit_msr,
                qty_issued, issue_date, issue_time, balance, (d.`unit_price`*s.`qty_issued`) AS Total, period, input_user FROM `care_ke_internalserv` s
                LEFT JOIN care_tz_drugsandservices d ON s.`item_id`=d.`partcode` WHERE status='serviced' ";
        $statDate = 'Service Date';
        $statQty = 'Qty Serviced';
    } else {
        $sql = "";
    }


    if ($ordLoc) {
        $sql = $sql . ' and Store_loc="' . $ordLoc . '"';
    } else {
        $sql = $sql . '';
    }

    $dt1 = new DateTime($orddt1);
    $dto1 = $dt1->format('Y-m-d');
    $dt2 = new DateTime($orddt2);
    $dto2 = $dt2->format('Y-m-d');

    if ($orddt1 <> '' && $orddt2 == '') {
        $sql = $sql . ' and req_date="' . $dto1 . '"';
    }
    if ($orddt1 == '' && $orddt2 <> '') {
        $sql = $sql . ' and req_date="' . $dto2 . '"';
    }
    if ($orddt1 <> "" && $orddt2 <> "") {
        $sql = $sql . ' and req_date between "' . $dto1 . '" and "' . $dto2 . '"';
    } else {
        $sql = $sql . '';
    }

    if ($inputUser <> '') {
        $sql = $sql . " and input_user  like '$inputUser%'";
    }
    $request = $db->Execute($sql);

    //echo $sql;

    echo "<table id='pharmacy'><tr>
                    <th align='center' class=titles>Order No</th>
                    <th align='center' class=titles>Order Date</th>
                    <th align='center' class=titles>Stock Code</th>
                    <th align='center' class=titles>Description</th>
                    <th align='center' class=titles>Unit Cost</th>
                    <th align='center' class=titles>Qty at Hand</th>
                    <th align='center' class=titles>Qty Ordered</th>
                    <th align='center' class=titles>Qty Serviced</th>
                    <th align='center' class=titles>Pending Qty</th>
                    <th align='center' class=titles>Total Cost</th>
                    <th align='center' class=titles>Ordered From</th>
                    <th align='center' class=titles>Ordered By</th></tr>";
    $bg = '';
    $totalCost=0;
    while ($row = $request->FetchRow()) {
//        if ($bg == "silver")
//            $bg = "white";
//        else
//            $bg = "silver";
        echo '<tr>
                    <td>' . $row[req_no] . '</td>';
        if ($rstatus == 'Pending') {
            echo "<td>" . $row[req_date] . "</td>";
        } elseif ($rstatus == 'Serviced') {
            echo "<td>" . $row[issue_date] . "</td>";
        }

        echo '<td>' . $row[item_id] . '</td>
                    <td>' . $row[Item_desc] . '</td>
                    <td align=right>' . number_format($row[unit_price],2) . '</td>';
        if ($rstatus == 'Pending') {
            echo '<td>' . $row[at_hand] . '</td>';
        } else {
            echo '<td></td>';
        }
        
        echo'<td>' . $row[ordered] . '</td>';

        if ($rstatus == 'Pending') {
            echo '<td></td>
                  <td>' . $row[ordered] . '</td>';
        } else {
            echo '<td>' . $row[qty_issued] . '</td>
                 <td>' . $row[unit_msr] . '</td>';
        }
        echo'<td align=right>' . number_format($row[Total],2) . '</td>';

        echo'<td>' . $row[Store_desc] . '</td>
                 <td>' . $row[input_user] . '</td>
                </tr>';
        $rowbg = 'white';
        $totalCost=$totalCost+$row[Total];
    }
    echo "<tr><td colspan='8'></td><td class=titles>TOTAL COST</td><td class=titles align=right>".number_format($totalCost,2)."</td><td colspan='2'></td></tr>";
    echo '</table>';
}

function getAdjustments() {
    global $db;
    $ordLoc = $_REQUEST['ordLoc'];
    $orddt1 = $_REQUEST['orddt1'];
    $orddt2 = $_REQUEST['orddt2'];
    $itemID = $_REQUEST['itemID'];

    $sql = "SELECT a.ID, a.item_number, d.item_description,a.prev_qty, a.new_qty, 
           a.user, a.adjDate, a.adjTime, a.Reason,a.st_id
           FROM `care_ke_adjustments` a left join care_tz_drugsandservices d  on a.item_number=d.partcode
            where a.item_number<>''";


    if ($ordLoc) {
        $sql = $sql . ' and a.st_id="' . $ordLoc . '"';
    }

    if ($itemID) {
        $sql = $sql . ' and a.item_number="' . $itemID . '"';
    } else {
        $sql = $sql . '';
    }

    $dt1 = new DateTime($orddt1);
    $dto1 = $dt1->format('Y-m-d');
    $dt2 = new DateTime($orddt2);
    $dto2 = $dt2->format('Y-m-d');

    if ($orddt1 <> '' && $orddt2 == '') {
        $sql = $sql . ' and a.adjDate="' . $dto1 . '"';
    }
    if ($orddt1 == '' && $orddt2 <> '') {
        $sql = $sql . ' and a.adjDate="' . $dto2 . '"';
    }
    if ($orddt1 <> "" && $orddt2 <> "") {
        $sql = $sql . ' and a.adjDate between "' . $dto1 . '" and "' . $dto2 . '"';
    } else {
        $sql = $sql . '';
    }


    $request = $db->Execute($sql);

  // echo $sql;
    echo '<table width=100%  id="pharmacy"><tr>
                    <th align="center" class=titles>ID</th>
                    <th align="center" class=titles>Item Number</th>
                    <th align="center" class=titles>Description</th>
                    <th align="center" class=titles>Date</th>
                    <th align="center" class=titles>Old Qty</th>
                    <th align="center" class=titles>New Qty</th>
                    <th align="center" class=titles>Store</th>
                    <th align="center" class=titles>Reason</td><th>';

    $bg = '';
    while ($row = $request->FetchRow()) {
//        if ($bg == "silver")
//            $bg = "white";
//        else
//            $bg = "silver";
        echo '<tr>
                    <td>' . $row[ID] . '</td>
                    <td>' . $row[item_number] . '</td>
                    <td>' . $row[item_description] . '</td>
                    <td>' . $row[adjDate] . '</td>
                    <td>' . $row[prev_qty] . '</td>
                    <td>' . $row[new_qty] . '</td>
                    <td>' . $row[st_id] . '</td>
                    <td>' . $row[Reason] . '</td>
             </tr>';
        $rowbg = 'white';
    }
    echo '</table>';
}

function getIssues() {
    global $db;
    $rstatus = $_REQUEST['rstatus'];
    $issLoc = $_REQUEST['strLoc2'];
    $orddt1 = $_REQUEST['orddt1'];
    $orddt2 = $_REQUEST['orddt2'];
    $pid1 = $_REQUEST['pid1'];
    $pid2 = $_REQUEST['pid2'];
    $issue1 = $_REQUEST['issue1'];
    $issue2 = $_REQUEST['issue2'];

    if ($rstatus=='Pending') {
        $sql="SELECT nr as presc_nr,r.status,r.create_time as order_date,'' as store_loc,'' as store_desc,p.`pid` as OP_no,
                CONCAT(p.`name_first`,' ',p.`name_last`,' ',p.`name_2`) AS patient_name,
                r.`partcode` as item_id,r.`article` as Item_desc,(r.`dosage`*r.`times_per_day`*r.`days`) AS qty,r.`price`,r.`prescriber` as input_user
                 FROM care_encounter_prescription r LEFT JOIN care_encounter e ON r.`encounter_nr`=e.encounter_nr
                 LEFT JOIN care_person p ON e.`pid`=p.`pid`
                WHERE r.`status`='pending' AND r.`drug_class`='drug_list'";
    } else if ($rstatus=='Issued'){
        $sql = 'SELECT  order_no , presc_nr , status , order_date , order_time , store_loc , store_desc ,
                OP_no , patient_name , item_id , Item_desc , qty ,  price , unit_cost , orign_qty , balance , input_user , total
                FROM  care_ke_internal_orders  where status="Issued"';
    }

    if($issLoc && $rstatus=='Issued'){
        $sql.=" and store_loc='$issLoc'";
    }

    if ($pid1 <> '' && $pid2 == '') {
        $sql = $sql . ' and OP_no="' . $pid1 . '"';
    } elseif ($pid2 <> '' && $pid1 == '') {
        $sql = $sql . ' and OP_no="' . $pid2 . '"';
    } elseif ($pid1 <> "" && $pid2 <> "") {
        $sql = $sql . ' and OP_no in (' . $pid1 . ',' . $pid2 . ')';
    } else {
        $sql = $sql . '';
    }

    $dt1 = new DateTime($orddt1);
    $dto1 = $dt1->format('Y-m-d');
    $dt2 = new DateTime($orddt12);
    $dto2 = $dt2->format('Y-m-d');

    if ($dto1 <> '' && $dto2 == '' && $rstatus=='Pending') {
        $sql = $sql . ' and r.prescribe_date="' . $dto1 . '"';
    } else if ($dto2 <> '' && $dto1 == '' && $rstatus=='Pending') {
        $sql = $sql . ' and r.prescribe_date="' . $dto2 . '"';
    } else if ($dto2 <> "" && $dto1 <> "" && $rstatus=='Pending') {
        $sql = $sql . ' and r.prescribe_date between "' . $dto1 . '" and "' . $dto2 . '"';
    } else {
        $sql = $sql . '';
    }

    if ($dto1 <> '' && $dto2 == '' && $rstatus=='Issued') {
        $sql = $sql . ' and order_date="' . $dto1 . '"';
    } else if ($dto2 <> '' && $dto1 == '' && $rstatus=='Issued') {
        $sql = $sql . ' and order_date="' . $dto2 . '"';
    } else if ($dto2 <> "" && $dto1 <> "" && $rstatus=='Issued') {
        $sql = $sql . ' and order_date between "' . $dto1 . '" and "' . $dto2 . '"';
    } else {
        $sql = $sql . '';
    }

   echo $sql;


    $request = $db->Execute($sql);
//
    echo '<table  id="pharmacy"><tr>
                    <th align="center" class=titles>Order No</th>
                    <th align="center" class=titles>Prescription No</th>
                    <th align="center" class=titles>Order Date</th>
                    <th align="center" class=titles>OP No</th>
                    <th align="center" class=titles>Patient Name</th>
                    <th align="center" class=titles>Stock Code</th>
                    <th align="center" class=titles>Description</th>
                    <th align="center" class=titles>Unit Cost</th>
                    <th align="center" class=titles>Qty Prescribed</th>
                    <th align="center" class=titles>Qty Issued</th>
                    <th align="center" class=titles>Pending qty</th>
                    <th align="center" class=titles>Total Amount</th>
                    <th align="center" class=titles>Issuing Store</th>
                    <th align="center" class=titles>Issued By</th></tr>';
    $bg = '';
    while ($row = $request->FetchRow()) {
//        if ($bg == "silver")
//            $bg = "white";
//        else
//            $bg = "silver";
        echo '<tr>
                    <td>' . $row[order_no] . '</td>
                    <td>' . $row[presc_nr] . '</td>
                    <td>' . $row[order_date] . ' ' . $row[order_time] . '</td>
                    <td>' . $row[OP_no] . '</td>
                    <td>' . $row[patient_name] . '</td>
                    <td>' . $row[item_id] . '</td>
                    <td>' . $row[Item_desc] . '</td>
                    <td>' . $row[price] . '</td>
                    <td>' . $row[qty] . '</td>
                    <td>' . $row[orign_qty] . '</td>
                    <td>' . $row[balance] . '</td>
                    <td>' . intval($row[orign_qty] * $row[price]) . '</td>
                    <td>' . $row[store_desc] . '</td>
                    <td>' . $row[input_user] . '</td>
             </tr>';
        $rowbg = 'white';
    }
    echo '</table>';
}
?>
<!--
<html><head><title></title></head>
    <body style="background-color:dfe ">
        
        
    </body>
</html>-->