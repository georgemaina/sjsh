<?php
    require_once 'roots.php';
    require($root_path.'include/inc_environment_global.php');
    require_once($root_path.'include/care_api_classes/class_weberp_c2x.php');
    require_once($root_path.'include/inc_init_xmlrpc.php');
    require_once($root_path.'include/care_api_classes/class_tz_billing.php');
?>
<!-- dhtmlxWindows -->
<link rel="stylesheet" type="text/css" href="../../include/dhtmlxWindows/codebase/dhtmlxwindows.css">
<link rel="stylesheet" type="text/css" href="../../include/dhtmlxWindows/codebase/skins/dhtmlxwindows_dhx_skyblue.css">
<script src="../../include/dhtmlxWindows/codebase/dhtmlxcommon.js"></script>
<script src="../../include/dhtmlxWindows/codebase/dhtmlxwindows.js"></script>

<!-- dhtmlxGrid -->
<link rel='STYLESHEET' type='text/css' href='../cashbook/codebase/dhtmlxgrid.css'>
<script src='../cashbook/codebase/dhtmlxcommon.js'></script>
<script src='../cashbook/codebase/dhtmlxgrid.js'></script>
<script src='../cashbook/codebase/dhtmlxgrid_form.js'></script>
<script src='../cashbook/codebase/ext/dhtmlxgrid_filter.js'></script>
<script src='../cashbook/codebase/ext/dhtmlxgrid_srnd.js'></script>
<script src='../cashbook/codebase/dhtmlxgridcell.js'></script>
<script src="../../include/dhtmlxWindows/codebase/dhtmlxcontainer.js"></script>

<script src='../../include/dhtmlxConnector/codebase/connector.js'></script>

<!-- dhtmlxCalendar -->
<link rel="STYLESHEET" type="text/css" href="../../include/dhtmlxCalendar/codebase/dhtmlxcalendar.css">
<script src='../../include/dhtmlxCalendar/codebase/dhtmlxcalendar.js'></script>
<script src='../../include/dhtmlxCalendar/codebase/dhtmlxcommon.js'></script>
<script>window.dhx_globalImgPath="'../../include/dhtmlxCalendar/codebase/imgs/";</script>

 <?php

    require($root_path.'include/care_api_classes/accounting.php');
    $bill_obj = new Bill;
    echo ' <div class=pgtitle>Patient Bill Management</div>';
    echo "<table> 
    <tr><td align=left>" . require_once 'acLinks.php';
    echo '</td><td align=right>';

            if(!isset($_POST[submit])){
                    displayForm();
            }else{
            if(!isset($new_bill_number)) {
                   $new_bill_number=$bill_obj->checkBillEncounter($_POST['en_nr']);
            }
            
                $_POST['bill_number']=$new_bill_number;

                foreach ($_POST as $key => $value) {
			if ($value<>'' and $key<>'submit') {
				$DebitDetails[$key] = $value;
			}
		}

                
                $obj_acconts=new accounting();
                $obj_acconts->updateDebit($DebitDetails);
                $_POST['encounter_nr']=$obj_acconts->getEncounter($_POST['pid']);

                updateDbtErp($db,$_POST['pid']);
                displayForm();

            }
        echo "</td></tr></table>";

      function displayForm(){
                echo '<form name="debit" method="post" action="">';
                echo '<table border="0" cellpadding="0" cellspacing="5" class="style1">';
                echo '<tr><td colspan="5" class=pgtitle>Debit</td></tr>';
                echo ' <tr><td>Revenue Codes:</td>';
                echo '<td colspan=3><input type="text" name="revcode" id="revcode" ondblclick="initRsearch()"/>
                        <input type="text" name="en_nr" id="en_nr"/>    </td></tr>';
                echo '<tr><td>Patient No:</td><td colspan=3>
                <input type="text" size="10" name="pid" id="pid" ondblclick="initPsearch()" onblur="getPatient(this.value)"/>';
                echo '<input type="text" name="pname" id="pname" size=\"36\" "/>
                        <input type="button" id="search" value="search" onclick="getPatient(document.getElementById("pid").value)"/></td></tr>';
                echo '<tr><td>ref No:</td>';
                echo '<td><input type="text" name="receiptNo" id="receiptNo"/></td></tr>';
                echo '<tr><td>Description:</td>';
                echo '<td><input type="text" size="36" name="Description" id="Description" /></td>';
                echo '<td>date:</td>';
                echo '<td><input type="text" name="calInput" id="calInput" value="'.date("d-m-y").'"/></td></tr>';
                echo '<tr><td><td><td>Price:</td>';
                echo '<td><input type="text" name="Amount" id="Amount" /></td></tr>';
                echo '<tr><td><td><td>Quantity:</td>';
                echo '<td><input type="text" name="qty" id="qty" onkeyup="getBalance(this.value)"/></td><tr>';
                echo '<tr><td><td><td>Total:</td>';
                echo '<td><input type="text" name="total" id="total" /></td></tr>';
                echo '<tr><td colspan=5><br><br></td></tr>';
                echo '<tr><td colspan=5 align=center>';
                echo '<input type="submit" name="submit" id="submit" value="save" />&nbsp&nbsp';
                echo '<input type="button" name="cancel" id="cancel" value="cancel" /></td></tr>';
                echo '</table></form>';
  }
  function updateDbtErp($db,$pn) {
      //global $db, $root_path;
      $debug=false;;
        if ($debug) echo "<b>class_tz_billing::updateDbtErp()</b><br>";
        if ($debug) echo "encounter no: $pn <br>";
        ($debug) ? $db->debug=TRUE : $db->debug=FALSE;
        $sql='SELECT b.pid, c.unit_price AS price,c.partcode,c.item_Description AS article,a.prescribe_date,a.qty AS amount,a.bill_number
    FROM care2x.care_ke_billing a INNER JOIN care_tz_drugsandservices c
    ON a.item_number=c.partcode
    INNER JOIN care2x.care_encounter b
    ON a.pid=b.pid and b.pid="'.$pn.'" and service_type="ward procedure"';
        $result=$db->Execute($sql);
        if($weberp_obj = new_weberp()) {
        //$arr=Array();

            while($row=$result->FetchRow()) {
            //$weberp_obj = new_weberp();
                if(!$weberp_obj->transfer_bill_to_webERP_asSalesInvoice($row)) {
                    echo 'success<br>';
                    echo date($weberp_obj->defaultDateFormat);
                }
                else {
                    echo 'failed';
                }
                destroy_weberp($weberp_obj);
            }
        }else {
            echo 'could not create object: debug level ';
        }
    }

    ?>
<script>
    cal1=new dhtmlxCalendarObject('calInput',true);
    mCal.setSkin("dhx_black");
</script>

<script>
function getBalance(str){
    var qty=str;
    var Amount=document.debit.Amount.value;
    var total=qty*Amount;
    document.debit.total.value=total;
}
</script>

<script>
var dhxWins, w1, grid;
    function initPsearch(){
        dhxWins = new dhtmlXWindows();

        dhxWins.setImagePath("../../include/dhtmlxWindows/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 462, 600, 340, 250);
        w1.setText("Search Patient");

        grid = w1.attachGrid();
        grid.setImagePath("../cashbook/codebase/imgs/");
        grid.setHeader("Patient ID,first Name,Surname,Last name,en_nr");
        grid.attachHeader("#connector_text_filter,#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ro,ro,ro");
        grid.setInitWidths("80,80,80,80,60");
        grid.loadXML("reports/pSearch_pop.php");
        grid.attachEvent("onRowSelect",doOnRowSelected3);
        grid.attachEvent("onEnter",doOnEnter3);
        //grid.attachEvent("onRightClick",doonRightClick);
        grid.init();
        grid.enableSmartRendering(true);
    }

    function initRsearch(){
        dhxWins = new dhtmlXWindows();

        dhxWins.setImagePath("../../include/dhtmlxWindows/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 462, 600, 340, 250);
        w1.setText("Search Patient");

        grid = w1.attachGrid();
        grid.setImagePath("../cashbook/codebase/imgs/");
        grid.setHeader("revenue code,Description,unitprice");
        grid.attachHeader("#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ro");
        grid.setInitWidths("80,80,80");
        grid.loadXML("RSearch_pop.php");
        grid.attachEvent("onRowSelect",doOnRowSelected);
        grid.attachEvent("onEnter",doOnEnter);
        //grid.attachEvent("onRightClick",doonRightClick);
        grid.init();
        grid.enableSmartRendering(true);
    }

    function doOnRowSelected3(id,ind){
        names=grid.cells(id,1).getValue()+" "+grid.cells(id,3).getValue()+" "+grid.cells(id,2).getValue()
        document.getElementById('pid').value=grid.cells(id,0).getValue();
        document.getElementById('pname').value=names;
        document.getElementById('en_nr').value=grid.cells(id,4).getValue();
    }

    function doOnEnter3(rowId,cellInd){
        names=grid.cells(rowId,1).getValue()+" "+grid.cells(rowId,3).getValue()+" "+grid.cells(rowId,2).getValue()
        document.getElementById('pid').value=grid.cells(rowId,0).getValue();
        document.getElementById('pname').value=names;
        document.getElementById('en_nr').value=grid.cells(id,4).getValue();
        closeWindow();
    }
    function doOnRowSelected(id,ind){
        document.getElementById('revcode').value=grid.cells(id,0).getValue();
        document.getElementById('Description').value=grid.cells(id,1).getValue();
        document.getElementById('Amount').value=grid.cells(id,2).getValue();

    }

    function doOnEnter(rowId,cellInd){
        document.getElementById('revcode').value=grid.cells(rowId,0).getValue();
        document.getElementById('Description').value=grid.cells(rowId,1).getValue();
        document.getElementById('Amount').value=grid.cells(rowId,2).getValue();

        closeWindow();
    }
    function closeWindow() {
        dhxWins.window("w1").close();
    }

     function getPatient(str)
    {
        
        xmlhttp3=GetXmlHttpObject();
        if (xmlhttp3==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="accountingFns.php?desc3="+str;
        url=url+"&caller=debitGetPt";
        url=url+"&sid="+Math.random();
        xmlhttp3.onreadystatechange=stateChanged3;
        xmlhttp3.open("POST",url,true);
        xmlhttp3.send(null);

    }

    function stateChanged3()
{
     //get payment description
   if (xmlhttp3.readyState==4)//show point desc
    {
        var str=xmlhttp3.responseText;
        var str2=str.search(/,/)+1
        document.getElementById('pname').value=str //.split(",",1);
        //applyFilter();

    }
}

    function getCodeDesc(str)
    {
        
        xmlhttp4=GetXmlHttpObject();
        if (xmlhttp4==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="accountingFns.php?desc4="+str;
        url=url+"&caller=debitGetRv";
        url=url+"&sid="+Math.random();
        xmlhttp4.onreadystatechange=stateChanged4;
        xmlhttp4.open("POST",url,true);
        xmlhttp4.send(null);

    }

    function stateChanged4()
{
     //get payment description
   if (xmlhttp4.readyState==4)//show point desc
    {
        var str=xmlhttp4.responseText;
        var str2=str.search(/,/)+1
        document.getElementById('Description').value=str //.split(",",1);
        //applyFilter();

    }
}


function GetXmlHttpObject()
{
    if (window.XMLHttpRequest)
    {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        return new XMLHttpRequest();
    }
    if (window.ActiveXObject)
    {
        // code for IE6, IE5
        return new ActiveXObject("Microsoft.XMLHTTP");
    }
    return null;
}
    </script>