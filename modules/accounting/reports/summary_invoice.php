
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
    require_once 'myLinks_1.php';
    global $db;
    echo "<table width=100% border=0>
        <tr class='titlebar'><td colspan=2 bgcolor=#99ccff><font color='#330066'>Detailed Invoice</font></td></tr>
    <tr><td align=left valign=top>";
    require($root_path . "modules/accounting/aclinks.php");
    echo '</td>
           <td align=left valign=top>
                <table border=0><tr class="tr2">
                    <td>';
    require_once 'psearch.php';
    echo ' </td></tr>
                    <tr>
                        <td><font size="4" color="blue"><b> ' . $_REQUEST['caller'] . ' Invoice</b></font> <br>
                         <div id="loader"></div>
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
           finalised(name,bill)
        }else{
            window.open('summary_invoice_pdf.php?pid='+name+"&receipt="+str2+"&nhif="+nhif+"&bill="+bill ,"Summary Invoice","menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
        }
    }

    function finalised(encounter_nr,bill){
         xmlhttp6=GetXmlHttpObject();
        if (xmlhttp6==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="../../inpatient_ke/data/getDataFunctions.php?caller=checkFinaliseStatus";
        url=url+"&sid="+Math.random();
        url=url+"&pid="+encounter_nr;
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
            var str=str3.split(",");
            if(str[0]==1){
                var pid= str[1];
                var bill = str[3];
                window.open('finalSummary_invoice_pdf.php?pid='+pid+"&receipt=1&nhif=1&bill="+bill ,"Summary Invoice","menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
            }else{
                document.getElementById('txtHint').innerHTML="<span style='font-size:large; font-weight:bold;color:#F41B19;'>The invoices has not been finalised</span>";
            }
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