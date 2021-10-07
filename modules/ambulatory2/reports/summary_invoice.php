
<!--<link rel="stylesheet" type="text/css" href="../../../css/themes/default/default.css">-->
<?php
require('roots.php');
require($root_path . "modules/accounting/extIncludes.php"); 
?>
<script type="text/javascript" src="../reportFunctions.js"></script>
<link rel="stylesheet" type="text/css" href="../accounting.css">
<?php
printform();

function printform() {
    require('roots.php');

    global $db;
    echo "<table width=100% border=0>
        <tr class='titlebar'><td colspan=2 bgcolor=#99ccff><font color='#330066'>Detailed Invoice</font></td></tr>
    <tr><td align=left valign=top>";
    require($root_path . "modules/ambulatory2/aclinks.php");
    echo '</td>
           <td align=left valign=top>
                <table border=0><tr class="tr2">
                    <td>';
    require_once 'psearch.php';
    echo ' </td></tr>
                    <tr>
                        <td><font size="4" color="blue"><b> ' . $_REQUEST['caller'] . ' Invoice</b></font> <br>
            <div class="style5" id="txtHint"><b>Person Invoice will be Displayed here.</b></div>

  </td>
                    </tr>
                   
              </table>
         </td>
  </tr></table>';
}
?>
<script>
    function invoicePdf(name,receipt){
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
       var bill = document.bills.billNumbers.value;
        if(invType==1){
            window.open('finalSummary_invoice_pdf.php?pid='+name+"&receipt="+str2+"&nhif="+nhif+"&bill="+bill ,"Summary Invoice","menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
        }else{
            window.open('summary_invoice_pdf.php?pid='+name+"&receipt="+str2+"&nhif="+nhif+"&bill="+bill ,"Summary Invoice","menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
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

        window.open('miniInvoiceSummary.php?pid='+name+"&receipt="+str2+"&nhif="+nhif+"&final="+invType+"&billNumber="+bills ,"Summary Invoice","menubar=yes,toolbar=yes,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");


    }

</script>