
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
        url=url+"&billNumbers="+bills
        url=url+"&sid="+Math.random();
    }else{
        url="getInvoice.php";
        url=url+"?q="+str;
        url=url+"&r="+str2;
        url=url+"&billNumbers="+bills
        url=url+"&sid="+Math.random();
    }

    xmlhttp.onreadystatechange=stateChanged;
    xmlhttp.open("GET",url,true);
    xmlhttp.send(null);
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

