
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

function export2excel(){
      xmlhttp=GetXmlHttpObject();
    if (xmlhttp==null)
    {
        alert ("Browser does not support HTTP Request");
        return;
    }
    var url;

    url="exportExcel.php";

    xmlhttp.onreadystatechange=stateChanged3;
    xmlhttp.open("GET",url,true);
    xmlhttp.send(null);
}

function stateChanged3()
{
    if (xmlhttp.readyState==4)
    {
        document.getElementById("txtHint3").innerHTML=xmlhttp.responseText;
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




