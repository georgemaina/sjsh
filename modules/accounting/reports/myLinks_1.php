
<!-- dhtmlxWindows -->

<link rel="stylesheet" type="text/css" href="../../../include/dhtmlxWindows/codebase/dhtmlxwindows.css">
<link rel="stylesheet" type="text/css" href="../../../include/dhtmlxWindows/codebase/skins/dhtmlxwindows_dhx_skyblue.css">
<!--<script src='../../include/dhtmlxGrid/codebase/dhtmlxcommon_debug.js'></script>-->
<script src="../../../include/dhtmlxWindows/codebase/dhtmlxcommon.js"></script>
<script src="../../../include/dhtmlxWindows/codebase/dhtmlxwindows.js"></script>

<!-- Display menus-->
<link rel="stylesheet" type="text/css" href="../../../include/dhtmlxMenu/codebase/skins/dhtmlxmenu_dhx_skyblue.css">
<link rel="stylesheet" type="text/css" href="../../../include/dhtmlxMenu/codebase/skins/dhtmlxmenu_dhx_blue.css">
<link rel="stylesheet" type="text/css" href="../../../include/dhtmlxMenu/codebase/skins/dhtmlxmenu_glassy_blue.css">

<script  src="../../../include/dhtmlxMenu/codebase/dhtmlxcommon.js"></script>
<script  src="../../../include/dhtmlxMenu/codebase/dhtmlxmenu.js"></script>

<!-- dhtmlxGrid -->
<link rel='STYLESHEET' type='text/css' href='../../../include/dhtmlxGrid/codebase/dhtmlxgrid.css'>

<script src='../../../include/dhtmlxGrid/codebase/dhtmlxgrid.js'></script>
<script src='../../../include/dhtmlxGrid/codebase/ext/dhtmlxgrid_form.js'></script>
<script src='../../../include/dhtmlxGrid/codebase/ext/dhtmlxgrid_filter.js'></script>
<script src='../../../include/dhtmlxGrid/codebase/ext/dhtmlxgrid_srnd.js'></script>
<script src='../../../include/dhtmlxGrid/codebase/dhtmlxgridcell.js'></script>
<script src="../../../include/dhtmlxGrid/codebase/ext/dhtmlxgrid_drag.js"></script>

<script src="../../../include/dhtmlxWindows/codebase/dhtmlxcontainer.js"></script>

<script src='../../../include/dhtmlxdataprocessor/codebase/dhtmlxdataprocessor.js'></script>
<!--<script src='../../include/dhtmlxdataprocessor/codebase/dhtmlxdataprocessor_debug.js'></script>

 dhtmlxCalendar -->
<link rel="STYLESHEET" type="text/css" href="../../../include/dhtmlxCalendar/codebase/dhtmlxcalendar.css">
<script src='../../../include/dhtmlxCalendar/codebase/dhtmlxcalendar.js'></script>
<script src='../../../include/dhtmlxCalendar/codebase/dhtmlxcommon.js'></script>
<script>window.dhx_globalImgPath="'../../../include/dhtmlxCalendar/codebase/imgs/";</script>

<!-- dhtmlxWindows -->
<link rel="stylesheet" type="text/css" href="../../../include/dhtmlxWindows/codebase/dhtmlxwindows.css">
<link rel="stylesheet" type="text/css" href="../../../include/dhtmlxWindows/codebase/skins/dhtmlxwindows_dhx_skyblue.css">
<script src="../../../include/dhtmlxWindows/codebase/dhtmlxcommon.js"></script>
<script src="../../../include/dhtmlxWindows/codebase/dhtmlxwindows.js"></script>

<!-- dhtmlxGrid -->


<script src='../../../include/dhtmlxConnector/codebase/connector.js'></script>

<style type="text/css" name="1">
    .pg1{
        border-top: solid;border-bottom: solid;border-left: solid;border-right: solid;
        width: 60%;background-color:#b0ccf2;
    }
    .adml{border-style: solid; border-left: solid; border-width:thin;}
    .adm2{border-style: solid; border-width:thin;}
    .tbl1{width: 60%}
    .pidRw{border-style: solid; border-bottom: solid;border-top: solid;background-color: #8FC4E8}
    .myMessage{font-size: large;color: #ffffff;background-color: #cc0033;font-weight: bold;
               width: 60%;text-decoration: blink;
    }
</style>
<?php

function Finalize(){
    ?>
<form name="finalize" method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">
        <table border="0" class="pg1">
        <thead>
            <tr>
                <th></th>
                <th></th>

            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Date</td>
                <td><input type="text" name="fdate" value="<?php echo date("d-m-Y") ?>" /></td>

            </tr>
            <tr>
                <td>PID</td>
                <td><input type="text" name="pid" id="pid" value=""  ondblclick="initPtsearch()" onblur="getPNames(this.value)"/>
                    <input type="text" name="pname" id="pname" value="" size="30"/></td>

            </tr>
            <tr>
                <td colspan="2">Message to Appear on all Invoices</td>

            </tr>
            <tr>
                <td colspan="2"><textarea name="invText" rows="4" cols="50">
                        Payable as per the Agreement</textarea></td>
            </tr>
            <tr>

                <td align="center" colspan="2"><input type="submit" name="submit" id="submit" value="Finalize" /></td>


            </tr>
        
        </tbody>
    </table>
</form>

<?php
// require_once 'gridfiles_1.php';
}

 function displayForm(){
              echo '<form name="debit" method="POST" action="'. $_SERVER['PHP_SELF'] . '">';
                echo '<table width=90% border="0" cellpadding="0" cellspacing="5">';
                 
                echo '<tr><td colspan="5" class=pgtitle>Debit</td></tr>';
//                echo ' <tr><td>Revenue Codes:</td>';
//                echo '<td colspan=3><input type="text" name="revcode" id="revcode" ondblclick="initRsearch()"/>
//                            </td></tr>';
                echo '<tr><td>Patient No:</td><td colspan=3>
                <input type="text" size="10" name="pid" id="pid" ondblclick="initPtsearch()" onblur="getPatient(this.value)"/>';
                echo '<input type="text" name="pname" id="pname" size="36"/>
                        <input type="button" id="search" value="search" onclick="initPtsearch()"/></td></tr>';
                echo '<tr><td>ref No:</td>';
                echo '<td><input type="text" name="receiptNo" id="receiptNo"/>
                    en_nr:<input type="text" name="en_nr" id="en_nr" size=5/>
                    ward_nr<input type="text" name="ward_nr" id="ward_nr" size=5"/>
                    ward_name<input type="text" name="ward_name" id="ward_name" size=10/>
                    <input type="hidden" name="room_nr" id="room_nr" size=5/>
                    bed_nr<input type="text" name="bed_nr" id="bed_nr" size=5/></td></tr>';
                echo '<tr><td>date:</td';
                echo '<td colspan=4><input type="text" name="calInput" id="calInput" value="'.date("d-m-y").'"/></td></tr>';
                echo '<tr><td colspan="5">
                           <div id="gridbox" height="200px" style="background-color:white;"></div>
                       </td></tr>';
                echo '<tr><td colspan=5 align=right>Total:<input type="text" size="15" name="total" id="total" /></td></tr>';
                echo '<tr><td></td><td colspan=4 align=center>';
                echo '<input type="submit" name="submit" id="submit" value="save" />&nbsp&nbsp';
                echo '<input type="button" name="cancel" id="cancel" value="cancel" /></td></tr>';
                
                echo '</table>';
                echo '</form>';
                echo '<button onclick=addRows(1)>Add Row</button>
                    <button onclick=deleteRow()>Delete</button>
                    <button onclick=initKSearch()>Get Products List</button>';
                 require_once 'gridfiles_1.php';

  }
?>


<script>
function initPtsearch(){
        dhxWins = new dhtmlXWindows();
        //dhxWins.setSkin("modern_red");
        //dhxWins.enableAutoViewport(false);
        //dhxWins.setViewport(50, 50, 700, 400);
        //dhxWins.vp.style.border = "#909090 1px solid";
        dhxWins.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 462, 200, 430, 250);
        w1.setText("Search Patient");

        grid = w1.attachGrid();
        grid.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");
        grid.setHeader("Patient ID,first Name,Surname,Last name,en_nr");
        grid.attachHeader("#connector_text_filter,#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ro,ro,ro");
        grid.setInitWidths("80,80,80,80,80");
        grid.loadXML("searchIP.php");
        grid.attachEvent("onRowSelect",doOnRowSelected3);
        grid.attachEvent("onEnter",doOnEnter3);
        //grid.attachEvent("onRightClick",doonRightClick);
        grid.init();
        grid.enableSmartRendering(true);

    }

 function doOnEnter2(rowId,cellInd){
        //        var qty=grid.cells(grid.getSelectedId(),1).getValue();
        //         protocolIt("User pressed Enter on row with id "+rowId+" and cell index "+cellInd +"["+rev+"]");
        alert(grid.cells2(rowId,cellInd).getValue());
        //
    }

    function doOnRowSelected3(id,ind){
        names=grid.cells(id,1).getValue()+" "+grid.cells(id,3).getValue()+" "+grid.cells(id,2).getValue()
        document.getElementById('pid').value=grid.cells(id,0).getValue();
        document.getElementById('pname').value=grid.cells(id,1).getValue();
//        document.getElementById('en_nr').value=grid.cells(id,4).getValue();
//        sendRequestPost(grid.cells(id,4).getValue());
    }
    
    function getPNames(str)
    {
        xmlhttp10=GetXmlHttpObject();
        if (xmlhttp10==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="../getDesc.php?desc3="+str;
        url=url+"&sid="+Math.random();
        url=url+"&callerID=debit";
        xmlhttp10.onreadystatechange=stateChanged10;
        xmlhttp10.open("POST",url,true);
        xmlhttp10.send(null);

    }

    function stateChanged10()
    {
        //get payment description
        if (xmlhttp10.readyState==4)//show point desc
        {
            var str=xmlhttp10.responseText;
            str2=str.split(",");
            document.finalize.pname.value=str2[0]+' '+str2[1]+' '+str2[2]+' '+str2[2];

            alert(str2[8]);
//
        }
    }


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

function getInvoice(str,invCaller,bills)
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
//alert("Hello JSCript " + dropdown);

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

    dhxWins.setImagePath("../../../include/dhtmlxWindows/codebase/imgs/");

    w1 = dhxWins.createWindow("w1", 300, 100, 340, 250);
    w1.setText("Search Patient");

    grid = w1.attachGrid();
    grid.setImagePath("../cashbook/codebase/imgs/");
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

}

function doOnEnter3(rowId,cellInd){
    document.getElementById('pid').value=+rowId;
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
    var url="cashbookFns.php?desc3="+str;
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

        getBillNumber(document.getElementById('pid').value)

    }
}


function getBillNumber(str){
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


function closeBill(accno,pid,encounterNo){
    //alert('bill closed '+accno+','+ pid+','+encounterNo) ;
    xmlhttpc=GetXmlHttpObject();
    if (xmlhttpc==null)
    {
        alert ("Browser does not support HTTP Request");
        return;
    }
    var url="../getDesc.php?accno="+accno;
    url=url+"&sid="+Math.random();
    url=url+"&pid="+pid;
    url=url+"&enc_nr="+encounterNo;
    url=url+"&callerID=closeBill";

    show_progressbar('loader');
    xmlhttpc.onreadystatechange=stateChangedCloseBill;
    xmlhttpc.open("POST",url,true);
    xmlhttpc.send(null);
}

function show_progressbar(id) {
    document.getElementById('loader').innerHTML='<img src="../../ajax-loader3.gif" border="0" alt="Loading, please wait..." />';
}

function stateChangedCloseBill()
{
    if (xmlhttpc.readyState==4)//show point desc
    {
        var str=xmlhttpc.responseText;
        if(str==0){
            var rst="This bill has been closed successfully, Please proceed to finalize the bill";
        }else if(str==2){
            var rst="This bill is already closed and Debtor Account Updated";
        }else{
            var rst="This bill could not be closed";
        }
//alert(rst);
        document.getElementById('closebill').innerHTML=rst;
        document.getElementById('loader').innerHTML='';
    }
}

function invoicePdf(pid,receipt,bills){
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
    invType=document.getElementById("invType").value;
    var bills = document.bills.billNumbers.value;
    
    //var str=finalised(pid,bills);
    var stra=document.getElementById("finalised").value;
    var str=stra.split(",");
   // alert(str[0]);
    if(invType==1){    
         if(str[0]==1){
                window.open('finalDetail_invoice_pdf.php?pid='+pid+"&receipt="+str2+"&nhif="+nhif+"&final="+invType+"&billNumber="+bills ,"Summary Invoice","menubar=yes,toolbar=yes,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
           }else{
                document.getElementById('txtHint').innerHTML="<span style='font-size:large; font-weight:bold;color:#F41B19;'>The invoice has not been finalised</span>";
           }
    }else{
    	if(str[0]==0){
            window.open('detail_invoice_pdf.php?pid='+pid+"&receipt="+str2+"&nhif="+nhif+"&final="+invType+"&billNumber="+bills ,"Summary Invoice","menubar=yes,toolbar=yes,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
       	}else{
            document.getElementById('txtHint').innerHTML="<span style='font-size:large; font-weight:bold;color:#F41B19;'>The invoice has already been finalised</span>";
       	}
    }
}

	function finalised(pid,bill){
		// alert(pid+","+bill)
         xmlhttp6=GetXmlHttpObject();
        if (xmlhttp6==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="../../inpatient_ke/data/getDataFunctions.php?caller=checkFinaliseStatus";
        url=url+"&sid="+Math.random();
        url=url+"&pid="+pid;
		url=url+"&billNumber="+bill
        xmlhttp6.onreadystatechange=stateFinalised;
        xmlhttp6.open("POST",url,true);
        xmlhttp6.send(null);

        return false;
    }

    function stateFinalised(){
          if (xmlhttp6.readyState==4)
        {
            var str3=xmlhttp6.responseText;
            var str3=xmlhttp6.responseText;
            var str=str3.split(",");
            
            document.getElementById('finalised').value=str;
            //return str;
            //if(str[0]==1){
             //   var pid= str[1];
             //   var bill = str[3];
              //  window.open('finalDetail_invoice_pdf.php?pid='+pid+"&receipt="+str2+"&nhif="+nhif+"&final="+invType+"&billNumber="+bill ,"Summary Invoice","menubar=yes,toolbar=yes,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
           // }else{
             //   document.getElementById('txtHint').innerHTML="<span style='font-size:large; font-weight:bold;color:#F41B19;'>The invoice has not been finalised OK</span>";
           // }
        }
    }

function miniInvoicePdf(name,receipt,bills){
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
    var invType=document.getElementById("invType").value;
    var invCaller=document.getElementById("invCaller").value;
    var bills = document.bills.billNumbers.value;

    window.open('miniInvoice.php?pid='+name+"&receipt="+str2+"&nhif="+nhif+"&final="+invType+"&billNumber="+bills ,"Summary Invoice","menubar=yes,toolbar=yes,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");

}


</script>
