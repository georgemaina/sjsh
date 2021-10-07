<link rel="stylesheet" type="text/css" href="accounting.css">
<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('roots.php');
require($root_path.'include/inc_environment_global.php');
printform();

function printform(){
require('roots.php');
echo ' <div class=pgtitle>Daily Bed Charge</div>';
?>
<table border="0">
    <tr><td align=left valign="top">
<?php
require($root_path."modules/accounting/aclinks.php");
?>
</td>
           <td>
               <div class="style5" id="txtHint2" onclick="getPInvoice()"><b>Click here to Display patients in Wards.<input type="submit" value="Display" name="display" onclick="getPInvoice()"/></b></div>
         </td>
  </tr></table>

<?php } ?>

<script type="text/javascript" src="selectInvoice.js"></script>
<script>
var xmlhttp;
function bedCharge(){
    xmlhttp=GetXmlHttpObject();
    if (xmlhttp==null)
    {
        alert ("Browser does not support HTTP Request");
        return;
    }
    var url;

    url="chargeBeds.php";

    xmlhttp.onreadystatechange=stateChanged3;
    xmlhttp.open("GET",url,true);
    xmlhttp.send(null);
}

function stateChanged3()
{
    if (xmlhttp.readyState==4)
    {
        alert(xmlhttp.responseText);
        //document.getElementById("txtHint2").innerHTML=xmlhttp.responseText;
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
