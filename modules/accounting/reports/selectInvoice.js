
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

function invoicePdf(name,receipt,bills){
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
if(invType==1){
    window.open('finalDetail_invoice_pdf.php?pid='+name+"&receipt="+str2+"&nhif="+nhif+"&final="+invType+"&billNumber="+bills ,"Summary Invoice","menubar=yes,toolbar=yes,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
    }else{
    window.open('detail_invoice_pdf.php?pid='+name+"&receipt="+str2+"&nhif="+nhif+"&final="+invType+"&billNumber="+bills ,"Summary Invoice","menubar=yes,toolbar=yes,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
    }
}

