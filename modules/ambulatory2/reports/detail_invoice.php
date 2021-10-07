<?php 
require('roots.php');
require( "../extIncludes.php"); ?>
<link rel="stylesheet" type="text/css" href="../accounting.css">

<script src="../reportFunctions.js"></script>
<script src="selectInvoice.js"></script>
<?php
printform();

function printform() {
    require('roots.php');

    echo "<table width=100% border=0>
        <tr class='titlebar'><td colspan=2 bgcolor=#99ccff><font color='#330066'>Detailed Invoice</font></td></tr>
    <tr><td align=left valign=top>";
    require($root_path . "modules/ambulatory2/aclinks.php");
    echo '</td>
           <td align=left>
                <table border=0><tr class="tr2">
                    <td>';
    require_once 'psearch.php';
    echo ' </td></tr>
                    <tr>
                        <td><font size="4" color="blue"><b>' . $_REQUEST['caller'] . ' Invoice</b></font><br>
             <div id="loader"></div>
            <div class="style5" id="txtHint">Person Invoice will be Displayed here.</b></div>
  </td>
                    </tr>
              </table>
         </td>
  </tr></table>';
}
?>
<script>
    
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

        window.open('miniinvoice.php?pid='+name+"&receipt="+str2+"&nhif="+nhif+"&final="+invType+"&billNumber="+bills ,"Summary Invoice","menubar=yes,toolbar=yes,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");

     }

</script>