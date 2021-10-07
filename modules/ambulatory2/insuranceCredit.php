<?php
require_once 'roots.php';
require($root_path.'include/inc_environment_global.php');
require_once($root_path.'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path.'include/inc_init_xmlrpc.php');
require_once($root_path.'include/care_api_classes/class_tz_billing.php');
?>
<!-- dhtmlxWindows -->
<!-- dhtmlxWindows -->

<link rel="stylesheet" type="text/css" href="../../include/dhtmlxWindows/codebase/dhtmlxwindows.css">
<link rel="stylesheet" type="text/css" href="../../include/dhtmlxWindows/codebase/skins/dhtmlxwindows_dhx_skyblue.css">
<!--<script src='../../include/dhtmlxGrid/codebase/dhtmlxcommon_debug.js'></script>-->
<script src="../../include/dhtmlxWindows/codebase/dhtmlxcommon.js"></script>
<script src="../../include/dhtmlxWindows/codebase/dhtmlxwindows.js"></script>

<!-- Display menus-->
<link rel="stylesheet" type="text/css" href="../../include/dhtmlxMenu/codebase/skins/dhtmlxmenu_dhx_skyblue.css">
<link rel="stylesheet" type="text/css" href="../../include/dhtmlxMenu/codebase/skins/dhtmlxmenu_dhx_blue.css">
<link rel="stylesheet" type="text/css" href="../../include/dhtmlxMenu/codebase/skins/dhtmlxmenu_glassy_blue.css">
<script  src="../../include/dhtmlxMenu/codebase/dhtmlxcommon.js"></script>
<script  src="../../include/dhtmlxMenu/codebase/dhtmlxmenu.js"></script>

<!-- dhtmlxGrid -->
<link rel='STYLESHEET' type='text/css' href='../../include/dhtmlxGrid/codebase/dhtmlxgrid.css'>

<script src='../../include/dhtmlxGrid/codebase/dhtmlxgrid.js'></script>
<script src='../../include/dhtmlxGrid/codebase/ext/dhtmlxgrid_form.js'></script>
<script src='../../include/dhtmlxGrid/codebase/ext/dhtmlxgrid_filter.js'></script>
<script src='../../include/dhtmlxGrid/codebase/ext/dhtmlxgrid_srnd.js'></script>
<script src='../../include/dhtmlxGrid/codebase/dhtmlxgridcell.js'></script>
<script  src="../../include/dhtmlxGrid/codebase/ext/dhtmlxgrid_drag.js"></script>

<script src="../../include/dhtmlxWindows/codebase/dhtmlxcontainer.js"></script>

<script src='../../include/dhtmlxdataprocessor/codebase/dhtmlxdataprocessor.js'></script>
<!--<script src='../../include/dhtmlxdataprocessor/codebase/dhtmlxdataprocessor_debug.js'></script>-->

<!-- dhtmlxCalendar -->
<link rel="STYLESHEET" type="text/css" href="../../include/dhtmlxCalendar/codebase/dhtmlxcalendar.css">
<script src='../../include/dhtmlxCalendar/codebase/dhtmlxcalendar.js'></script>
<script src='../../include/dhtmlxCalendar/codebase/dhtmlxcommon.js'></script>
<script>window.dhx_globalImgPath="'../../include/dhtmlxCalendar/codebase/imgs/";</script>

<!-- dhtmlxCombo -->
<link rel="STYLESHEET" type="text/css" href="../../include/dhtmlxCombo/codebase/dhtmlxcombo.css">
<script>window.dhx_globalImgPath="../../include/dhtmlxCombo/codebase/imgs/";</script>
<script src="../../include/dhtmlxCombo/codebase/dhtmlxcommon.js"></script>
<script src="../../include/dhtmlxCombo/codebase/dhtmlxcombo.js"></script>


<script src='../../include/dhtmlxConnector/codebase/connector.js'></script>
<script src="dateCalculations.js"></script>
<?php
$debug=true;
require($root_path.'include/care_api_classes/accounting.php');
$bill_obj = new Bill;
 echo "<table width=100% border=0>
        <tr class='titlebar'><td colspan=2 bgcolor=#99ccff><font color='#330066'>Insurance Credits</font></td></tr>
    <tr><td align=left valign=top>";
require 'acLinks.php';
echo '</td><td width=80%>';

if(!isset($_POST[submit])) {
    displayForm();
}else {

    echo var_dump($_POST);
    $creditNo= $_POST['crNo'];
    $insuranceID= $_POST['isuranceID'];
    $insurance= $_POST['insuName'];
    $admno= $_POST['pid'];
    $Names= $_POST['pname'];
    $admDate= $_POST['admDate'];
    $disDate= $_POST['discDate'];
    $ReleaseDate= $_POST['releaseDt'];
    $wrdDays= $_POST['wrdDays'];
    $invoiceNo= $_POST['invNo'];
    $BillAmount= $_POST['invAmount'];
    $Premium= $_POST['totalCrdit'];
    $Balance= $_POST['balance'];
    insertData($db,$creditNo,$insuranceID,$insurance,$admno,
        $Names,$admDate,$disDate,$ReleaseDate,$wrdDays,$invoiceNo,$BillAmount,
        $Premium,$Balance);

    //                updateDbtErp($db,$_POST['pid']);
    //                updateinsuredDbt($_POST['pid']);
    displayForm();

}
echo "</td></tr></table>";

function displayForm() {
    echo '<form name="debit" method="post" action="'. $_SERVER['PHP_SELF'] .'">';
    echo '<table width=80% border="0" cellpadding="0" cellspacing="5">';
    echo '<tr><td colspan="6" class=pgtitle>Insurance Credit</td></tr>';
    echo ' <tr><td>Credit No:</td>';
    echo '<td colspan=5><input type="text" name="crNo" id="crNo" value="" onclick="getNextCrdNo()"/>
        <input type="text" name="en_nr" id="en_nr"/>
                      </td></tr>';
    echo '<tr><td>Patient No:</td><td colspan=5>
                     <input type="text" size="10" name="pid" id="pid"
                        ondblclick="initPsearch()" onblur="getPatient(this.value)" onclick="getNextCrdNo()"/>';
    echo '<input type="text" name="pname" id="pname" size="40"/>
                        <input type="button" id="search" value="search"
                                onclick="getPatient(document.getElementById("pid").value)" /></td></tr>';
    echo '<tr><td>Insurance No:</td><td colspan=5>
                     <input type="text" size="10" name="isuranceID" id="isuranceID"
                       onblur="getInsurance()" onclick="getInsurance()"/>';
    echo '<input type="text" name="insuName" id="insuName" size="40"/>
                        <input type="button" id="search" value="search"
                                onclick="getPatient(document.getElementById("pid").value)" /></td></tr>';
    echo '<tr><td>Admission Date:</td';
    echo '<td><input type="text" name="admDate" id="admDate" value=""/></td>';
    echo '<td>Discharge Date:</td><td><input type="text" name="discDate" id="discDate" value=""/></td>';
    echo '<td></td></tr>';
    echo '<tr><td>Days:</td>';
    echo '<td><input type="text" name="wrdDays" id="wrdDays" value="" onclick="getDays()"/></td>
         <td>Release Date:</td><td><input type="text" name="releaseDt" id="releaseDt" value="" onclick="getDays()"/></td><tr>';
    echo '<tr><td>Invoice No:</td>';
    echo '<td><input type="text" name="invNo" id="invNo" /></td>';
    echo '<td>Invoice Amount:</td><td><input type="text" name="invAmount" id="invAmount" /></td><td></td></tr>';
    echo '<tr><td>Total Credit:</td>';
    echo '<td><input type="text" name="totalCrdit" id="totalCrdit" /></td>';
    echo '<td>Balance:</td><td colspan=2><input type="text" name="balance" id="balance" /></td></tr>';
    echo '<tr><td></td><td colspan=4 align=center>';
    echo '<input type="submit" name="submit" id="submit" value="save" />&nbsp&nbsp';
    echo '<input type="button" name="cancel" id="cancel" value="cancel" /></td></tr>';
    echo '</table>';
    echo '</form>';

}

function insertData($db,$creditNo,$insuranceID,$insurance,$admno,
        $Names,$admDate,$disDate,$ReleaseDate,$wrdDays,$invoiceNo,$BillAmount,
        $Premium,$Balance) {
    $debug=true;
    $sql="INSERT INTO care2x.care_ke_insurancecredits
	(creditNo,insuranceID,Insurance,inputDate,admno,
	NAMES,admDate,disDate,ReleaseDate,wrdDays,invoiceNo,
        BillAmount,Premium,Balance)
	VALUES
	('$creditNo','$insuranceID','$insurance','".date('Y-m-d')."','$admno',
       '$Names', '$admDate', '$disDate','$ReleaseDate','$wrdDays',
        '$invoiceNo','$BillAmount', '$Premium','$Balance')";

    $result=$db->Execute($sql);
    if($debug) echo $sql;
}

function updateDbtErp($db,$pn) {
//global $db, $root_path;
    $debug=true;;
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

//If patient is insured transmit to weberp
function updateinsuredDbt($pn) {
    global $db, $root_path;;
    require_once($root_path.'include/care_api_classes/class_tz_insurance.php');
    $insurance_obj = new Insurance_tz;
    $sql='SELECT a.insurance_id as pid, a.price,a.partcode,a.description as article,a.prescribe_date,a.bill_number,
(a.dosage*a.times_per_day*a.days) AS amount,((a.dosage*a.times_per_day*a.days)*a.price) as total
FROM care2x.care_ke_billing a, care2x.care_encounter b
WHERE a.encounter_nr=b.encounter_nr AND a.encounter_nr="'.$pn.'" AND service_type="insured D member"';
    $result=$db->Execute($sql);
    $rows=$result->FetchRow();
    $IS_PATIENT_INSURED=$insurance_obj->is_patient_insured($pn);
    if($IS_PATIENT_INSURED) {
        $result=$db->Execute($sql);

        //$arr=Array();
        while($row=$result->FetchRow()) {
        //$weberp_obj = new_weberp();
            if(!$weberp_obj->transfer_bill_to_webERP_asSalesInvoice($row)) {
                echo 'success member transmission<br>';
                echo date($weberp_obj->defaultDateFormat);
            }
            else {
                echo 'failed member transmission';
            }
            destroy_weberp($weberp_obj);
        }
    }
}
?>
<script>
    cal1=new dhtmlxCalendarObject('dischargeDate',true);
    Cal1.setSkin("dhx_black");

    cal1=new dhtmlxCalendarObject('admDate',true);
    Cal1.setSkin("dhx_black");
</script>

<script>
    var dhxWins, w1, grid;

    sgrid = new dhtmlXGridObject('gridbox');
    sgrid.setImagePath('../../include/dhtmlxGrid/codebase/imgs/');
    sgrid.setHeader("item_number,Description,price,qty,total");
    sgrid.setInitWidths("80,270,100,60,100");
    sgrid.setSkin("light");
    sgrid.setColTypes("ed,ed,ed,ed,ed");
    sgrid.setColSorting("str,str,str,str");
    sgrid.setColumnColor("white,white,white");
    sgrid.enableDragAndDrop(true);
    sgrid.attachEvent("onEditCell",doOnCellEdit);
    sgrid.init();
    sgrid.enableSmartRendering(true);
    myDp=new dataProcessor('');
    sgrid.submitOnlyChanged(false);

    function initPsearch(){
        dhxWins = new dhtmlXWindows();

        dhxWins.setImagePath("../../include/dhtmlxWindows/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 462, 200, 340, 250);
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

        w1 = dhxWins.createWindow("w1", 600, 200, 400, 250);
        w1.setText("Search Patient");

        grid = w1.attachGrid();
        grid.setImagePath("../cashbook/codebase/imgs/");
        grid.setHeader("revenue code,Description,unitprice");
        grid.attachHeader("#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ro");
        grid.setInitWidths("60,200,80");
        grid.loadXML("RSearch_pop.php");
        grid.enableDragAndDrop(true);
        grid.attachEvent("onRowSelect",doOnRowSelected);
        grid.attachEvent("onEnter",doOnEnter);
        //grid.attachEvent("onRightClick",doonRightClick);
        grid.init();
        grid.enableSmartRendering(true);
    }

    function doOnCellEdit(stage,rowId,cellInd){

        var qty=sgrid.cells(sgrid.getSelectedId(),3).getValue();
        var amnt=sgrid.cells(sgrid.getSelectedId(),2).getValue();
        var total=amnt*qty;
        if(stage==2 && cellInd==3){
            sgrid.cells(rowId,4).setValue(total);
            document.getElementById('total').value=sumColumn(4);
        }
        return true;
    }

    function addRows(rowcount){
        var i=0;
        for(i=1; i<=rowcount; i++){
            var newId = (new Date()).valueOf();
            sgrid.addRow(newId,"",sgrid.getRowsNum())
        }
        sgrid.selectRow(mygrid.getRowIndex(newId),false,false,true);

    }

    function getBalance(str){
        xmlhttp2=GetXmlHttpObject();
        if (xmlhttp2==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="accountingFns.php?desc2="+str;
        url=url+"&caller=getAdDsDate";
        url=url+"&sid="+Math.random();
        xmlhttp2.onreadystatechange=stateChanged2;
        xmlhttp2.open("POST",url,true);
        xmlhttp2.send(null);

    }

    function stateChanged2()
    {
        //get payment description
        if (xmlhttp2.readyState==4)//show point desc
        {
            str=xmlhttp2.responseText;
            //str2=str.split(",");

            document.getElementById('admDate').value=str;
//            document.getElementById('disDate').value=str2[1];
   //         document.getElementById('wrdDays').value=str2[2];


        }
    }


	function getDisDate(){
        xmlhttp6=GetXmlHttpObject();
        if (xmlhttp6==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="accountingFns.php?desc5="+document.getElementById('pid').value;
        url=url+"&caller=getDisDate";
        url=url+"&sid="+Math.random();
        xmlhttp6.onreadystatechange=stateChanged6;
        xmlhttp6.open("POST",url,true);
        xmlhttp6.send(null);

    }

    function stateChanged6()
    {
        //get payment description
        if (xmlhttp6.readyState==4)//show point desc
        {
           var str=xmlhttp6.responseText;
        update = new Date(str);
        theMonth = update.getMonth();
        theDate = update.getDate();
        theYear = update.getYear();
        //document.getElementById('discDate').value=theMonth + "/" + theDate + "/" + theYear ;
        document.getElementById('discDate').value=str;


        }
    }

function getInvoiceDetails(){
        xmlhttp1=GetXmlHttpObject();
        if (xmlhttp1==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="insuranceFns.php?desc4="+document.getElementById('pid').value;
        url=url+"&caller=getInvoiceno";
        url=url+"&sid="+Math.random();
        xmlhttp1.onreadystatechange=stateChanged;
        xmlhttp1.open("POST",url,true);
        xmlhttp1.send(null);

    }

     function stateChanged()
    {
        //get payment description
        if (xmlhttp1.readyState==4)//show point desc
        {
           var str=xmlhttp1.responseText;
           str3=str.split(",");
            document.getElementById('invNo').value=str3[0];
            document.getElementById('invAmount').value=str3[1];
//        alert(str);
        }
    }

    function getInsurance(){
        xmlhttp7=GetXmlHttpObject();
        if (xmlhttp7==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="insuranceFns.php?desc2="+document.getElementById('pid').value;
        url=url+"&caller=getInsurance";
        url=url+"&sid="+Math.random();
        xmlhttp7.onreadystatechange=stateChanged7;
        xmlhttp7.open("POST",url,true);
        xmlhttp7.send(null);
    }

     function stateChanged7()
    {
        //get payment description
        if (xmlhttp7.readyState==4)//show point desc
        {
           var str=xmlhttp7.responseText;
            str3=str.split(",");
            document.getElementById('isuranceID').value=str3[0];
            document.getElementById('insuName').value=str3[1];

        }
    }

//     function days_between(date1, date2) {
//
//            var ONE_DAY = 1000 * 60 * 60 * 24
//
//            var date1_ms = date1.getTime()
//            var date2_ms = date2.getTime()
//
//            var difference_ms = Math.abs(date1_ms - date2_ms)
//
//            return Math.round(difference_ms/ONE_DAY)
//
//        }

    function getDays(){
             firstdate=document.getElementById('admDate').value;
            seconddate=document.getElementById('discDate').value;
            strdays=dateDiff(seconddate,firstdate);

            document.getElementById('wrdDays').value=strdays;
    }

    function sumColumn(ind){
        var out = 0;
        for(var i=0;i<sgrid.getRowsNum();i++){
            out+= parseFloat(sgrid.cells2(i,ind).getValue())
        }
        return out;
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
            str3=str.split(" ");
            document.getElementById('pname').value=str3[0]+' '+str3[1]+' '+str3[2];//str.split(",",3);
            document.getElementById('en_nr').value=str3[3];

            getBalance(document.getElementById('en_nr').value);
            getDisDate();
            getInsurance();
            getInvoiceDetails();
            
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


    function getNextCrdNo(){
        xmlhttp5=GetXmlHttpObject();
            if (xmlhttp5==null)
            {
                alert ("Browser does not support HTTP Request");
                return;
            }
            var url="insuranceFns.php?desc1=credit";
            url=url+"&sid="+Math.random();
            url=url+"&caller=creditNo";
            xmlhttp5.onreadystatechange=stateChanged5;
            xmlhttp5.open("POST",url,true);
            xmlhttp5.send(null);
     }

      function stateChanged5()
        {
         //get payment description
            if (xmlhttp5.readyState==4)
            {
                var str=xmlhttp5.responseText;
                 //str2=str.search(/,/)+1;
                document.debit.crNo.value=str;
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
