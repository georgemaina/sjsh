<link rel="stylesheet" type="text/css" href="cashbook.css">

<?php
//start session (see get.php for details)
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require_once 'roots.php';

//require($root_path.'include/inc_environment_global.php');
require_once($root_path.'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path.'include/inc_init_xmlrpc.php');
require_once($root_path.'include/care_api_classes/class_tz_billing.php');
$billObj=new Bill();
?>
<!-- dhtmlxWindows -->
<link rel="stylesheet" type="text/css" href="../../include/dhtmlxWindows/codebase/dhtmlxwindows.css">
<link rel="stylesheet" type="text/css" href="../../include/dhtmlxWindows/codebase/skins/dhtmlxwindows_dhx_skyblue.css">
<script src="../../include/dhtmlxWindows/codebase/dhtmlxcommon.js"></script>
<script src="../../include/dhtmlxWindows/codebase/dhtmlxwindows.js"></script>

<!-- dhtmlxGrid -->
<link rel='STYLESHEET' type='text/css' href='codebase/dhtmlxgrid.css'>
<script src='codebase/dhtmlxcommon.js'></script>
<script src='codebase/dhtmlxgrid.js'></script>
<script src='codebase/dhtmlxgrid_form.js'></script>
<script src='codebase/ext/dhtmlxgrid_filter.js'></script>
<script src='codebase/ext/dhtmlxgrid_srnd.js'></script>
<script src='codebase/dhtmlxgridcell.js'></script>
<script src="../../include/dhtmlxWindows/codebase/dhtmlxcontainer.js"></script>

<script src='../../include/dhtmlxConnector/codebase/connector.js'></script>

<!-- dhtmlxCalendar -->
<link rel="STYLESHEET" type="text/css" href="../../include/dhtmlxCalendar/codebase/dhtmlxcalendar.css">
<script src='../../include/dhtmlxCalendar/codebase/dhtmlxcalendar.js'></script>
<script src='../../include/dhtmlxCalendar/codebase/dhtmlxcommon.js'></script>
<script>window.dhx_globalImgPath="'../../include/dhtmlxCalendar/codebase/imgs/";</script>
<?php
require_once('mylinks.php');

if(isset($_POST["submit"])) {
    $r_sql = "SELECT c.cash_point,c.`ref_no`, c.`payer`, c.`patient`, c.`name`, c.`pay_mode`,
    c.`cheque_no`, c.`total`,c.`username`,c.towards,c.currdate,c.input_time FROM care_ke_receipts c where ref_no='$_POST[ref_no]'
     and cash_point='".$_POST[cashPoint]."' and patient='".$_POST[pid]."'";
    $result=$db->Execute($r_sql);
    $row=$result->FetchRow();
    //echo $r_sql;
 

    $heading ="P.C.E.A KIKUYU HOSPITAL ";
    $box="P.O. BOX 1010 -00902 KIKUYU";
    $rdate =$row[currdate];
    $refno=$refno;
    $cashier=$cashier;
    $pno=$patientid;
    $PatientName=$patientname;
    $PaymentMode=$paymode;
    $inputTime=$row[input_time];

//displayReceipt($row[cash_point],$row[ref_no],$row[username],$row[pay_mode],$row[payer],$row[cheque_no],$row[patient],$row[name],$row[total],"REPRINT");
    $patientNames=$billObj->getPatientNames($_POST[pid]);

if($_POST[receipt_type]=='cash_sale'){
    displayReceipts($rdate,$_POST[ref_no],$row[username],$_POST[pid],$row[name],$row[pay_mode],
        $_POST[cashPoint],$row[payer],'REPRINT',$_POST[shift_no],$inputTime);
}else if($_POST[receipt_type]=='receipt'){
    displayGLReceipts($rdate,$_POST[ref_no],$row[username],$_POST[pid],$row[name],$row[pay_mode],
        $_POST[cashPoint],$row[payer],"REPRINT",$_POST[shift_no],$row[towards],$inputTime);
}else{
    echo 'Select Receipt type';
}


    displayForm();
}else{

    displayForm();
}

function displayForm() {
    echo'<table>';
    echo'<tr><td>'.doLinks().'</td>';
    echo'<form method="POST" action='. $_SERVER['PHP_SELF'] . '>';
    echo '<td><table class="style1">
               <tbody>
                    <tr class="pgtitle">
                        <td colspan=2>Reprint</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Receipt type</td>
                        <td><table border="0">
                                <tbody>
                                <tr>
                                <td>Cash Sale</td>
                                <td><input type="radio" id="receipt_type" name="receipt_type" value="cash_sale" /></td>
                                </tr>
                                <tr>
                                <td>Receipt</td>
                                <td><input type="radio" id="receipt_type" name="receipt_type" value="receipt" /></td>
                                </tr>
                                
                                </tbody>
                                </table>
                                </td>
                    </tr>
                    <tr>
                        <td>Receipt No</td>
                        <td><input type="text" id="ref_no" name="ref_no" value="" ondblclick="initRsearch()"/></td>
                    </tr>
                     <tr>
                        <td>Cash Point</td>
                        <td><input type="text" id="cashPoint" name="cashPoint" value="" /></td>
                    </tr>
                     <tr>
                        <td>Shift No</td>
                        <td><input type="text" id="shift_no" name="shift_no" value=""/></td>
                    </tr>
                     <tr>
                        <td>Patient</td>
                        <td><input type="text" id="pid" name="pid" value=""/>
                            <input type="text" id="pname" name="pname" value="" size=50/></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" name="submit" id="submit" value="reprint"/>
                            <input type="submit" id="cancel" value="Cancel" />
                            &nbsp;&nbsp;<input type="button" id="getReceipts" value="Find Receipts" onclick="initRsearch()"/></button></td>
                    </tr>
                </tbody>
            </table></form>';
    echo '"</td></tr></table>"';


}


?>
<script>
var dhxWins, w1, grid;

    function initRsearch(){
        dhxWins = new dhtmlXWindows();

        dhxWins.setImagePath("../../include/dhtmlxWindows/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 800, 100, 500, 400);
        w1.setText("Search Receipts");

        grid = w1.attachGrid();
        grid.setImagePath("codebase/imgs/");
        grid.setHeader("cash_point,shift_no,ref_no,currdate,patient,name,towards");
        grid.attachHeader("#connector_text_filter,#connector_text_filter,#connector_text_filter,#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ro,ro,ro,ro,ro");
        grid.setInitWidths("80,80,80,80,80,120,80");
        grid.loadXML("receipt_find.php");
        grid.attachEvent("onRowSelect",doOnRowSelected);
        grid.attachEvent("onEnter",doOnEnter);
        //grid.attachEvent("onRightClick",doonRightClick);
        grid.init();
        grid.enableSmartRendering(true);
    }

  
    function doOnRowSelected(id,ind){
        document.getElementById('ref_no').value=grid.cells(id,2).getValue();
         document.getElementById('cashPoint').value=grid.cells(id,0).getValue();
          document.getElementById('shift_no').value=grid.cells(id,1).getValue();
           document.getElementById('pid').value=grid.cells(id,4).getValue();
           document.getElementById('pname').value=grid.cells(id,5).getValue();
       
    }

    function doOnEnter(rowId,cellInd){
        document.getElementById('ref_no').value=grid.cells(rowId,0).getValue();
//        document.getElementById('Description').value=grid.cells(rowId,1).getValue();
//        document.getElementById('Amount').value=grid.cells(rowId,2).getValue();

        closeWindow();
    }
    function closeWindow() {
        dhxWins.window("w1").close();
    }

    </script>