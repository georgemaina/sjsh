<link rel="stylesheet" type="text/css" href="../accounting.css">
<link rel="stylesheet" type="text/css" href="../../../css/themes/default/default.css">
<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('roots.php');
require($root_path.'include/inc_environment_global.php');
printform();

function printform(){
require('roots.php');
    echo "<table width=100% border=0>
        <tr class='titlebar'><td colspan=2 bgcolor=#99ccff><font color='#330066'>List of Invoices</font></td></tr>
    <tr><td align=left valign=top>";
 require($root_path."modules/ambulatory2/aclinks.php");
echo '</td>
           <td>
               <div class="style5" id="txtHint2" onclick="getPendingInvoices()"><b>Click here to Display Pedding Invoices.<input type="submit" value="Display" name="display" onclick="getPendingInvoices()"/></b></div>
         </td>
  </tr></table>';

 } ?>


<script type="text/javascript" src="getOPRevenue.js"></script>
<script>
function invoicePdf(name){
    //alert("Hellp");
     window.open('detail_invoice_pdf.php?pid='+name ,"Summary Invoice","menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
}

</script>