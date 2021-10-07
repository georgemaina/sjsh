<?php
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require('roots.php');
//require($root_path . "modules/accounting/extIncludes.php");
?>

<script src="../../../../ext-4/ext-all.js"></script>
<link rel="stylesheet" href="../../../../ext-4/resources/css/ext-all.css">

<link rel="stylesheet" type="text/css" href="../accounting.css">
<!--<script type="text/javascript" src="../reportFunctions.js"></script>-->
<?php
require($root_path . 'include/inc_environment_global.php');
printform();

function printform() {
    global $db;

    require('roots.php');
    echo "<table width=100% border=1>
        <tr class='titlebar'><td colspan=2 bgcolor=#99ccff><font color='#330066'>NHIF Credits</font></td></tr>
        
    <tr><td align=left valign=top width=15%>";
    require($root_path . "modules/accounting/aclinks.php");
    echo '</td>
           <td align=left valign=top width=75%>
            <table width=100%>
           
                    <tr>
                        <td id="datefield">Start Date:</td>
                        <td id="datefield2">End Date:</td>
                        <td><button id="preview" onclick="getNHIFCredits()">Preview</button></td>
                        <td><button id="export" 
                        onclick="exportNHIF()">Export to Excel</button></td>
                    </tr>
                     <tr><td colspan=4 bgcolor=#99ccff id="msg" class="myMessage"></td></tr>
                    <tr><td colspan=4><div id="nhifCredits"></div> </td></tr>
                </table>
                    </td>';
    //$row=$result->FetchRow();
    echo "</tr></table>";
}
?>

<script>

    var datefield = new Ext.form.DateField({
        renderTo: 'datefield',
        labelWidth: 100, // label settings here cascade unless overridden
        frame: false,
        width: 180,
        name: 'strDate1',
        id:'strDate1',
        format: 'Y-m-d',
        submitFormat: 'Y-m-d'
    });

    var datefield2 = new Ext.form.DateField({
        renderTo: 'datefield2',
        frame: false,
        width: 180,
        name: 'strDate2',
        id:'strDate2',
        format: 'Y-m-d',
        submitFormat: 'Y-m-d'
    });
   
    function getNHIFCredits(){
        xmlhttp10=GetXmlHttpObject();
        if (xmlhttp10==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }

       // alert(Ext.getCmp("strDate1").getValue());

        var dt1=Ext.Date.format(new Date(Ext.getCmp("strDate1").getValue()), 'Y-m-d');
        var dt2=Ext.Date.format(new Date(Ext.getCmp("strDate2").getValue()), 'Y-m-d');

        
        var url="../getDesc.php?sid="+Math.random();
        url=url+"&callerID=nhif";
        url=url+"&dt1="+dt1;
        url=url+"&dt2="+dt2;
        xmlhttp10.onreadystatechange=stateChanged10;
        xmlhttp10.open("POST",url,true);
        xmlhttp10.send(null);
    }

    function stateChanged10()
    {
        //get payment description
        if (xmlhttp10.readyState==4)//show point desc
        {
            var str=xmlhttp10.responseText;
            
            document.getElementById('nhifCredits').innerHTML=str;
        }
    }
    
    function deleteClaim(claim,pid,bill_number){
         xmlhttp10=GetXmlHttpObject();
        if (xmlhttp10==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        
        if(confirm('Are you sure you want to delete NHIF Credit')){
            var url="../getDesc.php?sid="+Math.random();
            url=url+"&callerID=deleteClaim";
            url=url+"&pid="+pid;
            url=url+"&claimNo="+claim;
            url=url+"&bill_number="+bill_number;
            xmlhttp10.onreadystatechange=stateChangedDelete;
            xmlhttp10.open("POST",url,true);
            xmlhttp10.send(null);
        }
                
        
    }
    
    function stateChangedDelete()
    {
        //get payment description
        if (xmlhttp10.readyState==4)//show point desc
        {
            var str=xmlhttp10.responseText;
            
            document.getElementById('msg').innerHTML=str;
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
    
     function exportNHIF(){
         var dt1=Ext.Date.format(new Date(Ext.getCmp("strDate1").getValue()), 'Y-m-d');
         var dt2=Ext.Date.format(new Date(Ext.getCmp("strDate2").getValue()), 'Y-m-d');
        window.open('exportNHIF.php?dt1='+dt1+'&dt2='+dt2+'&rptType=statement'
        ,"Reports","menubar=yes,toolbar=yes,width=500,height=550,location=yes,resizable=no,scrollbars=yes,status=yes");

    }
    
    function invoicePdf(pid,receipt,billNumber){
         window.open('summary_invoice_pdf.php?pid='+pid+'&receipt='+receipt+'&bill='+billNumber+'&rptType=statement'
        ,"NHIF Invoice","menubar=yes,toolbar=yes,width=500,height=550,location=yes,resizable=no,scrollbars=yes,status=yes");
    }
</script>