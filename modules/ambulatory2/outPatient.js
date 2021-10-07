/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


var xmlhttp;
function getPatients(str){
    xmlhttp=GetXmlHttpObject();
    if (xmlhttp==null)
    {
        alert ("Browser does not support HTTP Request");
        return;
    }
     var url="getPatients.php?dept_nr="+str;
            url=url+"&sid="+Math.random();

    xmlhttp.onreadystatechange=stateChanged3;
    xmlhttp.open("GET",url,true);
    xmlhttp.send(null);
}

function stateChanged3()
{
    if (xmlhttp.readyState==4)
    {
//        alert(xmlhttp.responseText);
        document.getElementById("results").innerHTML=xmlhttp.responseText;
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