<?php 
require('roots.php');
require( "../extIncludes.php"); ?>
<link rel="stylesheet" type="text/css" href="../accounting.css">

<script type="text/javascript" src="../reportFunctions.js"></script>
<?php
printform();

function printform() {
    require('roots.php');

    require_once 'myLinks_1.php';

    echo "<table width=100% border=0>
        <tr class='titlebar'><td colspan=2 bgcolor=#99ccff><font color='#330066'>Detailed Invoice</font></td></tr>
    <tr><td align=left valign=top>";
    require($root_path . "modules/accounting/aclinks.php");
    echo '</td>
           <td align=left>
                <table border=0><tr class="tr2">
                    <td>';
    require_once 'psearch.php';
    echo ' </td></tr>
                    <tr>
                        <td><font size="4" color="blue"><b>' . $_REQUEST['caller'] . ' Invoice</b></font><br>
            <div class="style5" id="txtHint">Person Invoice will be Displayed here.</b></div>
  </td>
                    </tr>
              </table>
         </td>
  </tr></table>';
}
?>
<script>
    function invoicePdf(name,receipt,bills){
        if(document.getElementById("receipt").checked==true){
            str2='ON';
        }else{
            str2='';
        }
        invType=document.getElementById("invType").value;
       var bills = document.bills.billNumbers.value;
        if(invType==1){
            window.open('finalDetail_invoice_pdf.php?pid='+name+"&receipt="+str2+"&final="+invType+"&billNumber="+bills ,"Summary Invoice","menubar=yes,toolbar=yes,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
        }else{
            window.open('detail_invoice_pdf.php?pid='+name+"&receipt="+str2+"&final="+invType+"&billNumber="+bills ,"Summary Invoice","menubar=yes,toolbar=yes,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes"); 
        }
    }

</script>