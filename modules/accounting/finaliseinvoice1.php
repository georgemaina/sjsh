<link rel="stylesheet" type="text/css" href="accounting.css">
<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('roots.php');

printform();

function printform(){
require('roots.php');
    echo "<table width=100% border=0>
        <tr class='titlebar'><td colspan=2 bgcolor=#99ccff><font color='#330066'>Process Invoices</font></td></tr>
    <tr><td align=left valign=top>";
require($root_path."modules/accounting/aclinks.php");
echo '</td>
           <td align=right>
                <table class="style3"><tbody>
                     <tr><td colspan=10 align=center class="pgtitle">Process Invoices</td></tr>
                     <tr>
                         <td colspan=4 align=center>Insured Patients<input type="radio" name="insurance" value="insured" onclick="showInsurances(this.value)"/>
                         <div id="strInsurance"></div>
                        </td>
                         <td colspan=5 align=center>Non Insured Patients<input type="radio" name="insurance" value="notinsured" onclick="getInvoice(this.value)"/></td></tr>
                     <tr><td colspan=9 align=center><hr></td></tr>
                     
                     <tr><td colspan=9><div id="divMessage"  class="style5"></div></td></tr>
                     
        </tbody></table>
      </td>
  </tr></table>';
}
//getInvoice(this.value)
?>

<script>
    function showInsurances(str)
    {
        xmlhttp3=GetXmlHttpObject();
        if (xmlhttp3==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="getInsuranceList.php?desc="+str;
        xmlhttp3.onreadystatechange=stateChanged4;
        xmlhttp3.open("POST",url,true);
        xmlhttp3.send(null);

    }

    function stateChanged4()
{
     //get payment description
   if (xmlhttp3.readyState==4)//show point desc
    {
        var str=xmlhttp3.responseText;
//        var str2=str.search(/,/)+1
    document.getElementById("strInsurance").innerHTML =str
//        alert(str);
        //countGridRecords(document.csale.patientId.value);
    }
}

   function processInvoice(str,str2)
    {
        xmlhttp3=GetXmlHttpObject();
        if (xmlhttp3==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="getInvoice2.php?pid="+str+'&billnumber='+str2;
        xmlhttp3.onreadystatechange=stateChanged;
        xmlhttp3.open("POST",url,true);
        xmlhttp3.send(null);

    }

   function getInvoice(str)
    {
        xmlhttp3=GetXmlHttpObject();
        if (xmlhttp3==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="getInvoice.php?desc="+str;
        xmlhttp3.onreadystatechange=stateChanged3;
        xmlhttp3.open("POST",url,true);
        xmlhttp3.send(null);

    }


function stateChanged()
{
     //get payment description
   if (xmlhttp3.readyState==4)//show point desc
    {
        var str=xmlhttp3.responseText;
//        var str2=str.search(/,/)+1
    document.getElementById("patientDetails").innerHTML =str
//        alert(str);
        //countGridRecords(document.csale.patientId.value);
    }
}

function stateChanged3()
{
     //get payment description
   if (xmlhttp3.readyState==4)//show point desc
    {
        var str=xmlhttp3.responseText;
//        var str2=str.search(/,/)+1
    document.getElementById("divMessage").innerHTML =str
//        alert(str);
        //countGridRecords(document.csale.patientId.value);
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

    function popme(mtext){
        alert(mtext);
    }

    function sumColumn(ind){
        var out = 0;
        for(var i=0;i<sgrid.getRowsNum();i++){
            out+= parseFloat(sgrid.cells2(i,ind).getValue())
        }
        return out;
    }
</script>