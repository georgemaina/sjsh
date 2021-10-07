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
$pid = $_REQUEST['pid'];

switch ($task) {
    case "labTestForm":
        labTestForm();
        break;
    case "saveTest":
        saveTest();
        break;
    case "getPendingLabTests":
        getPendingLabTests();
        break;
    case "getTestsHistory":
        getTestsHistory($pid);
        break;
    case "closeLabTest":
        stateCloseTest();
        break;
    default:
        echo "{failure:true}";
        break;
}//end switch



function stateCloseTest() {
    global $db;
    $encNo=$_REQUEST[encNo];

    $sql = "UPDATE care_test_request_chemlabor SET status='done' where encounter_nr='$encNo'";
    if($db->Execute($sql)){
        $sql2 = "UPDATE care_test_findings_chemlab SET status='done' where encounter_nr='$encNo'";
        if($db->Execute($sql2)){
            echo 'success';
        }else{
            echo $sql.';'.$sql2;
        }
    }

}


function labTestForm() {
    global $db;
    require_once('roots.php');
    $strForm = '';
    echo "<script language='javascript' src='" . $root_path . "js/check_prescription_form.js'></script>";

    $sql = "SELECT `item_id`,`name` FROM `care_tz_laboratory_param` WHERE group_id<>'-1'";
    $result = $db->Execute($sql);
    $strForm1 = $strForm1 . "<td><select name='itemlist[]' size='10' style='width:330px;' onDblClick='javascript:item_add();'>";
//    while ($row = $result->FetchRow()) {
//        $strForm1 = $strForm1 . '<option value="' . $row[nr] . '">';
    $strForm1 = $strForm1 . getLabTests();
//        $strForm1 = $strForm1 . '</option>';
//    }
    $strForm1 = $strForm1 . "</select>";

    $sql = "SELECT `nr`,`name` FROM `care_tz_laboratory_param` WHERE group_id<>'-1'";
    $result = $db->Execute($sql);
    $strForm2 = '';
    $strForm2 = $strForm2 . "<td><select name='selected_item_list[]' size='10' style='width:330px;' onDblClick='javascript:item_delete();'>";
//    while ($row = $result->FetchRow()) {
//        $strForm2 = $strForm2 . '<option value="' . $row[nr] . '">';
    $strForm2 = $strForm2 . DisplaySelectedItems($items);
//        $strForm2 = $strForm2 . '</option>';
//    }
    $strForm2 = $strForm2 . "</select>";


    $strForm = $strForm . "<form name='labtests' method='POST' action='$thisfile" . URL_APPEND . "&mode=new'>";
    $strForm = $strForm . "<table><tr><td>" . $strForm1 . "</td>";

    $strForm = $strForm . "<td><input type='button' name='add' value='Add >>' onClick='javascript:item_add();'><br><br>
                        <input type='button' name='Del' value='Delete <<' onClick='javascript:item_delete();'></td>";
    $strForm = $strForm . "<td>" . $strForm2 . "</td></tr>";
    $strForm = $strForm . "</table>";
    $strForm = $strForm . "</form>";
    $strForm = $strForm.="<tr><td colspan=3 align='centre'><button id='save' onClick='saveTest()'>Save Lab Test</button>
                        <button name='cancel' onclick='cancelRequest()'>Cancel New Test</button></td></tr>";


    echo $strForm;
}

function saveTest() {
    global $db;
    global $db, $root_path;
    $debug = false;
//    require_once('roots.php');
    require_once($root_path.'include/care_api_classes/class_tz_billing.php');
    $bill_obj = new Bill;
    
    $params = '';
    $batchNo = getBatchNo();
    $data['batch_nr'] = $batch_nr;
    $pid=$_SESSION['sess_full_pid'];
    $encounter_nr = $_SESSION['sess_en'];
    $notes = ' ';
    $send_date = date('Y-m-d H:i:s');
    $sample_time = date('H:i:s');
    $status = 'pending';
    $history = "Create: " . date('Y-m-d H:i:s') . " = " . $_SESSION['sess_user_name'] . "\n";
    $bill_number = 0;
    $bill_status = 'pending';
    $data['modify_id'] = $_SESSION['sess_user_name'];
    $modify_time = date('Y-m-d H:i:s');
    $create_id = $_SESSION['sess_user_name'];
    $create_time = date('Y-m-d H:i:s');
    $weberpsync = '0';
    $i = 0;
    $partcodes = $_REQUEST[partcodes];
    $arr_rows = explode(",", $partcodes);
    $items = '';
    for ($i = 0; $i < count($arr_rows); $i++) {
        if ($arr_rows[$i] <> '' && !empty($arr_rows[$i]))
            $items.="'" . $arr_rows[$i] . "',";
        else
            $items.="'" . $arr_rows[$i] . "'";
    }


    
    $new_bill_number = $bill_obj->checkBillEncounter($encounter_nr);
    
    $sql1 = "SELECT * FROM care_tz_laboratory_param WHERE item_id in($items) and group_id<>'-1'";
    if ($debug)
        echo $sql1;
    $request = $db->Execute($sql1);
    $parameters = '';
    while ($row = $request->FetchRow()) {
        $rows = $request->RecordCount();
        if ($rows > 1) {
            $parameters.=$row[id] . '=1&';
            $itemIds.=$row[item_id] . '&';
        } else {
            $parameters.=$row[id] . '=1';
            $itemIds.=$row[item_id];
        }
    }

    $sqlInsert = "INSERT INTO `care_test_request_chemlabor`
            (`encounter_nr`,`parameters`,
             `notes`, `send_date`,`sample_time`,`status`,`history`,
             `bill_number`,`bill_status`,`modify_id`,`modify_time`,`create_id`,
             `create_time`,`priority`, `item_id`,`weberpsync`)
            values ('$encounter_nr','$parameters',
                    '$notes','$send_date','$sample_time','pending','$history',
                    '$bill_number','$bill_status','$modify_id','$modify_time','$create_id',
                    '$create_time','0','$itemIds', '$weberpsync')";
    if ($debug)
        echo $sqlInsert;

    if ($db->Execute($sqlInsert)) {
        $request2 = $db->Execute($sql1);
        while ($row = $request2->FetchRow()) {
            $sqlInsert2 = "INSERT INTO `care_test_request_chemlabor_sub`
                                (`batch_nr`,`encounter_nr`, `paramater_name`,`parameter_value`,`item_id`)
                           VALUES ('$batchNo','$encounter_nr','$row[id]','1','$row[item_id]')";
            if ($debug)
                echo $sqlInsert2;

            if ($db->Execute($sqlInsert2)) {
                $error = 0;


                $bill_obj->updateFinalLabBill($encounter_nr, $new_bill_number);
                 $sql = 'SELECT b.pid, c.unit_price AS ovamount,c.partcode,c.item_Description AS article,a.prescribe_date,
                        a.qty AS amount,a.bill_number,a.ledger as salesArea
                        FROM care_ke_billing a INNER JOIN care_tz_drugsandservices c
                        ON a.item_number=c.item_number
                        INNER JOIN care_encounter b
                        ON a.encounter_nr=b.encounter_nr and a.encounter_nr="'. $encounter_nr .'" and service_type like "lab%" and a.`weberpsync`=0';
                $result = $db->Execute($sql);
                if ($debug)
                    echo $sql;
                if ($weberp_obj = new_weberp()) {
                    //$arr=Array();

                    while ($row = $result->FetchRow()) {
                        //$weberp_obj = new_weberp();
                        if (!$weberp_obj->transfer_bill_to_webERP_asSalesInvoice($row)) {
                            $sql = "update care_test_request_chemlabor set weberpSync=1,bill_number='$new_bill_number',bill_status='billed' where weberpSync=0";
                            $db->Execute($sql);
                            $sql2 = "update care_ke_billing set weberpSync=1 where weberpSync=0";
                            $db->Execute($sql2);
                        } else {
                            echo 'failed to transmit to weberp';
                        }
                        destroy_weberp($weberp_obj);
                    }
                } else {
                    return 'could not create object: debug level ';
                }
            } else {
                $error = 2;
            }
        }
    } else {
        $error = 1;
    }
//    }

    switch ($error) {
        case 0:
            echo 'Lab Test Successfully Saved';
            break;
        case 1:
            echo 'Error updating table care_test_request_chemlabor';
            break;
        case 2:
            echo 'Error updating table care_test_request_chemlabor_sub';
            break;
        default :
            echo 'Unknown error';
            break;
    }
}

function getBatchNo() {
    global $db;
    $sql = "SELECT batch_nr FROM care_test_request_chemlabor  ORDER BY batch_nr DESC";
    if ($ergebnis = $db->SelectLimit($sql, 1)) {
        if ($batchrows = $ergebnis->RecordCount()) {
            $bnr = $ergebnis->FetchRow();
            $batch_nr = $bnr['batch_nr'];
            if (!$batch_nr)
                $batch_nr = _BATCH_NR_INIT_;
            else
                $batch_nr++;
        }
        else {
            $batch_nr = _BATCH_NR_INIT_;
        }
    } else {
        return "<p>$sql<p>$LDDbNoRead";
        exit;
    }
    return $batch_nr;
}

function getLabTests() {
    global $db;

    $sql = "SELECT `item_id`,`name` FROM `care_tz_laboratory_param`";
    $result = $db->Execute($sql);

    $strOptions = '';
    while ($row = $result->FetchRow()) {
        $strOptions.= "<option value='$row[item_id]'>$row[name]</option>";
    }
    return $strOptions;
}

function DisplaySelectedItems($items) {
    global $db;
    if ($items) {
        $debug = FALSE;
        ($debug) ? $db->debug = TRUE : $db->debug = FALSE;
        $js_command = '<script language="javascript">';
        foreach ($items as $item_no) {
            $this->sql = "SELECT partcode, item_description as description FROM $this->tb_drugsandservices WHERE item_id = '$item_no' ";
            if ($this->result = $db->Execute($this->sql)) {
                if ($this->result->RecordCount()) {
                    $this->item_array = $this->result->GetArray();
                    while (list($x, $v) = each($this->item_array)) {
                        $js_command .= "add_to_list('" . $v['description'] . "', " . $v['partcode'] . ");";
                    }
                } else {
                    return false;
                }
            }
        }
        $js_command .= '</script>';
        echo $js_command;
    }
    return TRUE;
}

function getPendingLabTests() {

    global $db;
    $debug = false;

    $sql = "SELECT c1.`batch_nr`,c1.`encounter_nr`,p.`group_id`,c1.`parameters`,c2.`item_id`,c2.`paramater_name`,p.`name`,
                c1.`send_date`,c1.`status`,c1.`bill_number`,c1.`bill_status`,c1.`sample_time`,c1.`notes`
                FROM care_test_request_chemlabor c1 
                LEFT JOIN care_test_request_chemlabor_sub c2 ON c1.`batch_nr`=c2.`batch_nr`
                LEFT JOIN care_tz_laboratory_param p ON c2.`item_id`=p.`item_id`
                where c1.`encounter_nr`='" . $_SESSION['sess_en'] . "'";

    if ($debug)
        echo $sql;

    $result = $db->Execute($sql);

    $toggle = 0;
    $strResults = '';

    $strResults.="<tr><td colspan=10><font size=3 color='#ffffff'><b>Pending Tests (" . $_SESSION['sess_full_pid'] . ")<br></b></font></td></tr>";
    $strResults.= "<tr bgcolor='#f6f6f6'>
                <td $tbg><FONT color='#000066'> Date</td>
                <td $tbg><FONT color='#000066'> Encounter No</td>
                <td $tbg><FONT color='#000066'> Batch No</td>
                <td $tbg><FONT color='#000066'> Test Group</td>
                <td $tbg><FONT color='#000066'> Test</td>
                <td $tbg><FONT color='#000066'> Status </td>
                <td $tbg><FONT color='#000066'> Bill Number</td>
                <td $tbg><FONT color='#000066'> Bill Status</td>
                <td $tbg><FONT color='#000066'> Item No</td>
                <td $tbg><FONT color='#000066'> Notes</td>
            </tr>";

    while ($row = $result->FetchRow()) {
        if ($toggle)
            $bgc = '#f3f3f3';
        else
            $bgc = '#fefefe';
        $toggle = !$toggle;


        $strResults.= "<tr bgcolor='$bgc' >";
        $strResults.= "<td> $row[send_date]</td>
                        <td> $row[encounter_nr]</td>
                        <td><FONT SIZE=1 >$row[batch_nr]</td>
                        <td><FONT SIZE=1 >$row[group_id]</td>
                        <td><FONT SIZE=1 > $row[name]</td>
                        <td><FONT SIZE=1 > $row[status]</td>
                        <td><FONT SIZE=1 > $row[bill_number]</td>
                        <td><FONT SIZE=1 > $row[bill_status]</td>
                        <td><FONT SIZE=1 >$row[item_id]</td>
                        <td><FONT SIZE=1 > $row[notes]</td>
                </tr>";
    }
    echo $strResults;
}

function getTestsHistory($pid) {

    global $db;
    $debug = false;

    $sql = "SELECT c1.`batch_nr`,c1.`encounter_nr`,p.`group_id`,c1.`parameters`,c2.`item_id`,c2.`paramater_name`,p.`name`,
                c1.`send_date`,c1.`status`,c1.`bill_number`,c1.`bill_status`,c1.`sample_time`,c1.`notes`
                FROM care_test_request_chemlabor c1 
                LEFT JOIN care_test_request_chemlabor_sub c2 ON c1.`batch_nr`=c2.`batch_nr`
                LEFT JOIN care_encounter e ON c1.`encounter_nr`=e.`encounter_nr`
                LEFT JOIN care_tz_laboratory_param p ON c2.`item_id`=p.`item_id`
                where c1.`encounter_nr`<>'" . $_SESSION['sess_en'] . "' and e.pid='" . $_SESSION['sess_full_pid'] . "'";

    if ($debug)
        echo $sql;

    $result = $db->Execute($sql);

    $toggle = 0;
    $strResults = '';

    $strResults.="<tr><td colspan=10><font size=3 color='#ffffff'><b>Patient Tests History(" . $_SESSION['sess_full_pid'] . ")<br></b></font></td></tr>";
    $strResults.= "<tr bgcolor='#f6f6f6'>
                <td $tbg><FONT color='#000066'> Date</td>
                <td $tbg><FONT color='#000066'> Encounter No</td>
                <td $tbg><FONT color='#000066'> Batch No</td>
                <td $tbg><FONT color='#000066'> Test Group</td>
                <td $tbg><FONT color='#000066'> Test</td>
                <td $tbg><FONT color='#000066'> Status </td>
                <td $tbg><FONT color='#000066'> Bill Number</td>
                <td $tbg><FONT color='#000066'> Bill Status</td>
                <td $tbg><FONT color='#000066'> Item No</td>
                <td $tbg><FONT color='#000066'> Notes</td>
            </tr>";

    while ($row = $result->FetchRow()) {
        if ($toggle)
            $bgc = '#f3f3f3';
        else
            $bgc = '#fefefe';
        $toggle = !$toggle;


        $strResults.= "<tr bgcolor='$bgc' >";
        $strResults.= "<td> $row[send_date]</td>
                        <td> $row[encounter_nr]</td>
                        <td><FONT SIZE=1 >$row[batch_nr]</td>
                        <td><FONT SIZE=1 >$row[group_id]</td>
                        <td><FONT SIZE=1 > $row[name]</td>
                        <td><FONT SIZE=1 > $row[status]</td>
                        <td><FONT SIZE=1 > $row[bill_number]</td>
                        <td><FONT SIZE=1 > $row[bill_status]</td>
                        <td><FONT SIZE=1 >$row[item_id]</td>
                        <td><FONT SIZE=1 > $row[notes]</td>
                </tr>";

        $sql = "SELECT p.encounter_nr,k.test_date,k.test_time,c.group_id,c.name,p.paramater_name,p.parameter_value,
         p.job_id,p.batch_nr,c.`item_id` FROM care_test_findings_chemlabor_sub p
        LEFT JOIN care_tz_laboratory_param c ON p.paramater_name=c.id
        LEFT JOIN care_test_findings_chemlab k ON p.job_id=k.job_id
        LEFT JOIN care_test_request_chemlabor t ON t.batch_nr=k.job_id
        WHERE c.group_id<>-1 AND t.status='pending' and p.encounter_nr<>'" . $_SESSION['sess_en'] . "' 
        and c.`item_id`= '$row[item_id]' ORDER BY job_id asc";
//        echo $sql;

        $request = $db->Execute($sql);
        $rows2 = $request->RecordCount();

        if ($rows2 > 0) {
            $row1 = $request->FetchRow();
            $strResults.= "<tr bgcolor='#9ac2e5'><td colspan=3></td>
                        <td><font size=2 color='#000066'>Results for $row1[name]<br>
                           Test Date $row1[test_date]<br> Test Time$row1[test_time]<br></font></td>
                        <td colspan=5><br><font size=2 color='#000066'>$row1[parameter_value]</font><br><br></td>";
        }
    }
    echo $strResults;
}
?>

