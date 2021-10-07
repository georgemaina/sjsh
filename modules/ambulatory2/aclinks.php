
<?php require_once 'roots.php';
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
$title=$_REQUEST['title'];
?>
<link rel="stylesheet" type="text/css" href="../../css/themes/default/default.css">
<table>
    <tbody class="submenu">
        <tr >
            <td colspan="2" class="submenu_title">Activities</td>
        </tr>
        <tr >
            <td colspan="2" class="submenu_item"><a href="<?php echo $root_path ?>modules/ambulatory2/outpatientAccounting_pass.php?target=Debit">Debit Patient</a></td>
        </tr>
        <tr >
            <td colspan="2" class="submenu_item"><a href="<?php echo $root_path ?>modules/ambulatory2/outpatientAccounting_pass.php?target=Credit">Credit Patient</a></td>
        </tr>
        <tr >
            <td colspan="2" class="submenu_item"><a href="<?php echo $root_path ?>modules/ambulatory2/outpatientAccounting_pass.php?target=creditSlips">Print Credit Slips</a></td>
        </tr>
        <tr>
            <td colspan="2" class="submenu_item"><a href="<?php echo $root_path ?>modules/ambulatory2/outpatientAccounting_pass.php?target=viewSlips">View Credit Slips</a></td>
        </tr>
        <tr>
            <td colspan="2" class="submenu_item"><a href="<?php echo $root_path ?>modules/ambulatory2/finalize_invoice.php">Finalize Invoice</a></td>
        </tr>
        <tr>
            <td colspan="2" class="submenu_item"><a href="<?php echo $root_path ?>modules/ambulatory2/insuranceCredit.php">Insurance Credit</a></td>
        </tr>
        <tr>
            <td colspan="2" class="submenu_item"><a href="<?php echo $root_path ?>modules/ambulatory2/finaliseinvoice1.php">Process Invoices</a></td>
        </tr>
        <tr>
            <td colspan="2"  class="submenu_title">Billing Reports:</td>
        <tr>
            <td class="submenu_item"><img src="<?php echo $root_path ?>gui/img/common/default/eyeglass.gif" ></td>
            <td><a href="<?php echo $root_path ?>modules/ambulatory2/reports/detail_invoice.php?caller=Detail&invType=0">Patient Invoice Detail</a></td>

        </tr>
        <tr>
            <td class="submenu_item"><img src="<?php echo $root_path ?>gui/img/common/default/eyeglass.gif"></td>
            <td><a href="<?php echo $root_path ?>modules/ambulatory2/reports/summary_invoice.php?caller=Summary&invType=0">Patient Invoice Summary</a></td>
        </tr>
        <tr>
            <td class="submenu_item"><img src="<?php echo $root_path ?>gui/img/common/default/eyeglass.gif"></td>
            <td><a href="<?php echo $root_path ?>modules/ambulatory2/reports/detail_invoice.php?caller=FinalDetail&ptype=copy&invType=1">Reprint Finalised Invoice Detail </a></td>
        </tr>
        <tr>
            <td class="submenu_item"><img src="<?php echo $root_path ?>gui/img/common/default/eyeglass.gif"></td>
            <td><a href="<?php echo $root_path ?>modules/ambulatory2/reports/summary_invoice.php?caller=FinalSummary&ptype=copy&invType=1">Reprint Finalised Invoice Summary </a></td>
        </tr>

        <tr>
            <td class="submenu_item"><img src="<?php echo $root_path ?>gui/img/common/default/eyeglass.gif"></td>
            <td><a href="<?php echo $root_path ?>modules/ambulatory2/reports/pending_invoices.php?caller=pending">List of Invoice</a></td>
        </tr>
<!--        <tr>
            <td class="submenu_item"><img src="<?php echo $root_path ?>gui/img/common/default/eyeglass.gif"></td>
            <td><a href="<?php //echo $root_path    ?>modules/ambulatory2/reports/nhifCredit.php?caller=pending">NHIF Credits</a></td>
        </tr>-->
        <tr>
            <td class="submenu_item"><img src="<?php echo $root_path ?>gui/img/common/default/eyeglass.gif"></td>
            <td><a href="<?php echo $root_path ?>modules/ambulatory2/reports/cccInvoices.php?caller=pending">CCC Invoices</a></td>
        </tr>
        <tr>
            <td class="submenu_item"><img src="<?php echo $root_path ?>gui/img/common/default/eyeglass.gif"></td>
            <td><a href="<?php echo $root_path ?>modules/ambulatory2/reports/revBreakdown.php?caller=pending">Outpatient Revenue Breakdown</a></td>
        </tr>
        <tr>
            <td  colspan="2"  class="submenu_title">Debtor Reports:</td>
        </tr>
        <TR>
            <td align=center><img src="<?php echo $root_path ?>gui/img/common/default/documents.gif" border=0 width="16" height="17"></td>
            <TD class="submenu_item"><nobr><a href="<?php echo $root_path ?>modules/ambulatory2/DebtorReports.php?title=statement">Debtor Statement</a></nobr></TD>
</tr>
<tr>
    <td align=center><img src="<?php echo $root_path ?>gui/img/common/default/documents.gif" border=0 width="16" height="17"></td>
    <TD class="submenu_item"><nobr><a href="<?php echo $root_path ?>modules/ambulatory2/DebtorReports.php?title=invoice">debtor Invoice</a></nobr></TD>

</tr>
<tr>
    <td align=center><img src="<?php echo $root_path ?>gui/img/common/default/documents.gif" border=0 width="16" height="17"></td>
    <td class="submenu_item"><nobr><a href="<?php echo $root_path ?>modules/ambulatory2/DebtorReports.php?title=agedBalance">Aged Balances</a></nobr></td>

</tr>
<tr>
    <td align=center><img src="<?php echo $root_path ?>gui/img/common/default/documents.gif" border=0 width="16" height="17"></td>
    <td class="submenu_item"><nobr><a href="<?php echo $root_path ?>modules/ambulatory2/DebtorReports.php?title=detailedInvoice">Detailed Invoice</a></nobr></td>

</tr>
<tr>
    <td align=center><img src="<?php echo $root_path ?>gui/img/common/default/documents.gif" border=0 width="16" height="17"></td>
    <td class="submenu_item"><nobr><a href=".<?php echo $root_path ?>modules/ambulatory2/DebtorReports.php?title=pInvoiceDetail">Patient invoice detail</a></nobr></td>

</tr>
<tr>
    <td align=center><img src="<?php echo $root_path ?>gui/img/common/default/documents.gif" border=0 width="16" height="17"></td>
    <td class="submenu_item"><nobr><a href=".<?php echo $root_path ?>modules/ambulatory2/DebtorReports.php?title=pInvoiceSummary">Patient Invoice Summary</a></nobr></td>

</tr>
</tbody>
</table>


<!-- dhtmlxWindows -->
<!--<link rel="stylesheet" type="text/css" href="--><?php //echo $root_path; ?><!--include/dhtmlxWindows/codebase/dhtmlxwindows.css">-->
<!--<link rel="stylesheet" type="text/css" href="--><?php //echo $root_path; ?><!--include/dhtmlxWindows/codebase/skins/dhtmlxwindows_dhx_skyblue.css">-->
<!--<script src="--><?php //echo $root_path; ?><!--include/dhtmlxWindows/codebase/dhtmlxcommon.js"></script>-->
<!--<script src="--><?php //echo $root_path; ?><!--include/dhtmlxWindows/codebase/dhtmlxwindows.js"></script>-->
<!---->
<!--<!-- dhtmlxGrid -->-->
<!--<link rel='STYLESHEET' type='text/css' href='--><?php //echo $root_path; ?><!--modules/cashbook/codebase/dhtmlxgrid.css'>-->
<!--<script src='--><?php //echo $root_path; ?><!--modules/cashbook/codebase/dhtmlxcommon.js'></script>-->
<!--<script src='--><?php //echo $root_path; ?><!--modules/cashbook/codebase/dhtmlxgrid.js'></script>-->
<!--<script src='--><?php //echo $root_path; ?><!--modules/cashbook/codebase/dhtmlxgrid_form.js'></script>-->
<!--<script src='--><?php //echo $root_path; ?><!--modules/cashbook/codebase/ext/dhtmlxgrid_filter.js'></script>-->
<!--<script src='--><?php //echo $root_path; ?><!--modules/cashbook/codebase/ext/dhtmlxgrid_srnd.js'></script>-->
<!--<script src='--><?php //echo $root_path; ?><!--modules/cashbook/codebase/dhtmlxgridcell.js'></script>-->
<!--<script src="--><?php //echo $root_path; ?><!--include/dhtmlxWindows/codebase/dhtmlxcontainer.js"></script>-->
<!---->
<!--<script src='--><?php //echo $root_path; ?><!--include/dhtmlxConnector/codebase/connector.js'></script>-->



<script>


var xmlhttp;

function getPendingInvoices()
{
    xmlhttp=GetXmlHttpObject();
    if (xmlhttp==null)
    {
        alert ("Browser does not support HTTP Request");
        return;
    }
    var url;

    url="getPendingInvoices.php";

    xmlhttp.onreadystatechange=stateChanged2;
    xmlhttp.open("GET",url,true);
    xmlhttp.send(null);
}

function stateChanged2()
{
    if (xmlhttp.readyState==4)
    {
        document.getElementById("txtHint2").innerHTML=xmlhttp.responseText;
    }
}

function getInvoiceitems(str,invCaller,bills)
{
    xmlhttp=GetXmlHttpObject();
    if (xmlhttp==null)
    {
        alert ("Browser does not support HTTP Request");
        return;
    }
    var url;
    if(document.getElementById("receipt").checked==true){
        str2='ON';
    }else{
        str2='';
    }

    if(document.getElementById("nhif").checked==true){
        nhif='ON';
    }else{
        nhif='';
    }

//    selIdx = document.forms[0].billNumbers.selectedIndex;
//   newSel = document.forms[0].billNumbers.options[selIdx].text;
    var bills = document.bills.billNumbers.value;
//var index = dropdown.selectedIndex;
//var ddlvalue = dropdown.options[index].value;
    //alert("Hello JSCript " + str2);

    if (invCaller=="Detail"){
        url="getDetail_Invoice.php";
        url=url+"?q="+str;
        url=url+"&r="+str2;
        url=url+"&nhif="+nhif;
        url=url+"&billNumbers="+bills
        url=url+"&sid="+Math.random();
    }else{
        url="getInvoice.php";
        url=url+"?q="+str;
        url=url+"&r="+str2;
        url=url+"&nhif="+nhif;
        url=url+"&billNumbers="+bills
        url=url+"&sid="+Math.random();
    }

    show_progressbar('txtHint');
    xmlhttp.onreadystatechange=stateChanged;
    xmlhttp.open("GET",url,true);
    xmlhttp.send(null);
}

function show_progressbar(id) {
    document.getElementById('txtHint').innerHTML='<img src="../../ajax-loader2.gif" border="0" alt="Loading, please wait..." />';
}

function stateChanged()
{
    if (xmlhttp.readyState==4)
    {
        document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
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


var dhxWins, w1, grid;
    function initPsearch(){
        dhxWins = new dhtmlXWindows();

        dhxWins.setImagePath("<?php echo $root_path; ?>include/dhtmlxWindows/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 300, 100, 340, 250);
        w1.setText("Search Patient");

        grid = w1.attachGrid();
        grid.setImagePath("<?php echo $root_path; ?>modules/cashbook/codebase/imgs/");
        grid.setHeader("Patient ID,first Name,Surname,Last name");
        grid.attachHeader("#connector_text_filter,#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ro,ro");
        grid.setInitWidths("80,80,80,80");
        grid.loadXML("pSearch_pop.php");
        grid.attachEvent("onRowSelect",doOnRowSelected3);
        grid.attachEvent("onEnter",doOnEnter3);
        //grid.attachEvent("onRightClick",doonRightClick);
        grid.init();
        grid.enableSmartRendering(true);
    }


    function doOnRowSelected3(id,ind){
        document.getElementById('pid').value=+id;
        document.getElementById('pname').value=document.getElementById("pname").value=grid.cells(id,1).getValue()+' '+grid.cells(id,2).getValue()+' '+grid.cells(id,3).getValue();

    }

    function doOnEnter3(rowId,cellInd){
        document.getElementById('pid').value=+rowId;
        closeWindow();
    }

    function closeWindow() {
        dhxWins.window("w1").close();
    }

    function getInsuredPatient(str)
    {
        xmlhttp3=GetXmlHttpObject();
        if (xmlhttp3==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="getDesc.php?pid="+str;
        url=url+"&callerID=getSlipPatient"
        url=url+"&sid="+Math.random();
        xmlhttp3.onreadystatechange=stateChanged5;
        xmlhttp3.open("POST",url,true);
        xmlhttp3.send(null);
    }

    function stateChanged5()
    {
        //get payment description
        if (xmlhttp3.readyState==4)//show point desc
        {
            var str=xmlhttp3.responseText;
            str3=str.split(",");
            if(str3[1]==='true'){
                document.getElementById('creditMsg').value=str;
//                 document.getElementById('pid').value=str3[1];
                document.getElementById('pname').value=str3[0];
            }else{
                document.getElementById('creditMsg').innerHTML="The Patient does not have a paying Account";
                document.getElementById('pid').value="";
                document.getElementById('pname').value="";
            }
        }
    }

    function getPatient(str)
    {
        xmlhttp3=GetXmlHttpObject();
        if (xmlhttp3==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="cashbookFns.php?desc3="+str;
        url=url+"&callerID=patient"
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
            str3=str.split(",");
            document.getElementById('pname').value=str;//str3[0]+' '+str3[1]+' '+str3[2];

             getBillNumbers(document.getElementById('pid').value)

        }
    }

    function getBillNumbers(str){
         xmlhttp10=GetXmlHttpObject();
        if (xmlhttp10==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="../getDesc.php?pid="+str;
        url=url+"&sid="+Math.random();
        url=url+"&callerID=getBillNumbers";
        xmlhttp10.onreadystatechange=stateChangedPid;
        xmlhttp10.open("POST",url,true);
        xmlhttp10.send(null);
    }
    
    function stateChangedPid()
    {
        //get payment description
        if (xmlhttp10.readyState==4)//show point desc
        {
            var str=xmlhttp10.responseText;
    
            document.getElementById('billNumbers').innerHTML=str;

        }
    }


// function stateChanged8()
//    {
//        //get payment description
//        if (xmlhttp3.readyState==4)//show point desc
//        {
//            var str=xmlhttp3.responseText;
//            document.getElementById('bills').innerHTML=str;
//        }
//    }


                                        
    function printSlip() {
        patientid=document.getElementById("pid").value
        xmlhttp=GetXmlHttpObject();
        if (xmlhttp==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url;
        //        patientid=document.getElementById('pid').value;
        url="chargeslip.php?pid="+patientid;
        xmlhttp.onreadystatechange=stateChanged2;
        xmlhttp.open("GET",url,true);
        xmlhttp.send(null);
    }
                    
    function stateChanged2()
    {
        if (xmlhttp.readyState==4)
        {

            var str=xmlhttp.responseText;
            str3=str.split(",");
            if(str3[0]=='error'){
                alert('Slip has already been printed')
            }else{
                displaySlip(str3[0],str3[1],str3[2]);
            }


        }
    }
                                      
    function displaySlip(pid,slipNo,inputUser) {

        window.open('reports/creditslippdf.php?pid='+pid+'&slipNo='+slipNo+'&inputUser='+inputUser
            ,"receipt","menubar=no,toolbar=no,width=300,height=550,location=yes,resizable=no,scrollbars=no,status=yes");

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


