<link rel="stylesheet" type="text/css" href="accounting.css">
<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('roots.php');
require($root_path.'include/inc_environment_global.php');
printform();

function printform(){
require('roots.php');
    echo "<table width=100% border=0>
        <tr class='titlebar'><td colspan=2 bgcolor=#99ccff><font color='#330066'>Debit</font></td></tr>
    <tr><td align=left valign=top>";

require($root_path."modules/accounting/aclinks.php");
echo '</td>
           <td><div><input type="submit" value="Display" name="display" onclick="getOccupiedBeds()"/>
               <button onclick="bedCharge()" id="charge">Charge Occupied Beds</button></div> 
           <div class="style5" id="txtHint3"></dev>
               <div class="style5" id="txtHint2"><b>Click here to Display patients in Wards.</b>
               </div>
              
         </td>
  </tr></table>';

} ?>

<script type="text/javascript" src="selectInvoice.js"></script>
<script>
var xmlhttp;

function getOccupiedBeds()
{
    xmlhttp=GetXmlHttpObject();
    if (xmlhttp==null)
    {
        alert ("Browser does not support HTTP Request");
        return;
    }
    var url;

    url="getBedcharge.php";
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
function bedCharge(){
    xmlhttp=GetXmlHttpObject();
    if (xmlhttp==null)
    {
        alert ("Browser does not support HTTP Request");
        return;
    }
    var url;

    url="chargeBeds.php";

     show_progressbar('txtHint2');
    xmlhttp.onreadystatechange=stateChanged3;
    xmlhttp.open("GET",url,true);
    xmlhttp.send(null);
}

function show_progressbar(id) {
    document.getElementById('txtHint2').innerHTML='<img src="../ajax-loader2.gif" border="0" alt="Loading, please wait..." />';
}

function stateChanged3()
{
    if (xmlhttp.readyState==4)
    {
//        alert(xmlhttp.responseText);
        document.getElementById("txtHint2").innerHTML=xmlhttp.responseText;
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
