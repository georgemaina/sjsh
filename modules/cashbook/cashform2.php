<style type="text/css">
    <!--
    .style1 {
        font-size: 20px;
        font-weight: bold;
        color: #0099CC;
    }
    -->
</style>

<table border="0" width="80%">

   <tr>
        

            <?php
            
            if(isset($cashpoint)) {
                $sql = "Select pcode,name,next_receipt_no from care_ke_cashpoints where pcode='$cashpoint'";
            }else {
                $sql = "Select pcode,name,next_receipt_no from care_ke_cashpoints where cashier='$cashier' and active=1";
            }
            if($debug) echo $sql;
             $result=$db->Execute($sql);
            if (!($row2=$result->FetchRow())) {
                echo '<br><br><br><table border="0" align=center width=60%>
                            <tbody>
                            <tr>
                            <td align=center ><font color="red"><b>SHIFT ERROR<b></font><br></td>
                            </tr>
                            <tr>
                            <td align=center><font color="blue"><n>YOU HAVE NOT STARTED ANY SHIFT<BR><br>
                                Please start a shift by clicking on the link Start Shift</b></font></td>
                            </tr>
                            </tbody>
                            </table>
                        ';
                exit;
            }
            echo '<td>Cash Point:</td>
        <td colspan="2" valign="top">';
            echo "<select id=\"cash_point\" name=\"cash_point\" onchange=\"showDesc(this.value)\"
                onblur=\"validatePoint(this.value,".$cashier.")\">";
           // echo "<option value='cash point'>--Select Point--</option>";
            if(!isset($cashpoint)) {
                echo 'Please start a shift';
           }else {
                echo "<option value=$row2[0]>$row2[0]</option>";
                echo "</select>";
               
                echo "<input type=\"text\" size=\"36\" name=\"cashpoint_desc\" id=\"cashpoint_desc\" value=".$row2[1] . "  /></td>";
                echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                echo " <td>Receipt No:</td>";
                echo "<td><input type=\"text\" name=\"receiptNo\" id=\"receiptNo\" value=" .$row2[2] . " /></td></tr>";

            }

            ?>
            <!---------------------------------------------------------
            fields to be entered
            -->
    <tr><td colspan="6"> <hr> </td></tr>
    <tr>
        <td>Payment Mode:</td>
        <td><input type="text" name="paymode" id="paymode" onblur="showPayDesc(this.value)"/></td>
        <td><input type="text" name="paymode_desc" id="paymode_desc"/></td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>G/L Acc No:</td>
        <td><input type="text" name="gl_acc" id="gl_acc" /></td>
    </tr>
    <tr>
        <td>Date:</td>
        <td>
            <input type="text" name="calInput" id="calInput" />
            <script>
                cal1=new dhtmlxCalendarObject('calInput',true);
                mCal.setSkin("dhx_black");
            </script>
        </td>
        <td></td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>Cheque No:</td>
        <td><input type="text" name="cheq_no" id="cheq_no" /></td>
    </tr>
    <tr>
        <td>Payer:</td>
        <td colspan="2"><input name="payer" type="text" id="payer" size="45" /></td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>Patient:</td>
        <td><input type="text" name="patientId" id="patientId" onblur="getPatient(this.value)" ondblclick="initPsearch()"/></td>
        <td colspan="3"><input type="text" name="patient_name" id="patient_name" size="30" onblur="applyFilter()"/></td>
        <td></td>

    </tr>


<!-- onblur="getPatient(this.value)" ondblclick="initPsearch()" -->


<!--  ========================================================================
        Ajax scripts
 =============================================================================-->
<script>
    function validatePoint(str,str2){
        xmlhttp=GetXmlHttpObject();
            if (xmlhttp==null)
            {
                alert ("Browser does not support HTTP Request");
                return;
            }
            var url="cashbookFns.php?desc7="+str;
            url=url+"&sid="+Math.random();
            url=url+"&callerID=points2";
            url=url+"&cashier="+str2;
            xmlhttp.onreadystatechange=stateChanged4;
            xmlhttp.open("POST",url,true);
            xmlhttp.send(null);
    }

    function stateChanged4()
{
     //get payment description
   if (xmlhttp3.readyState==4)//show point desc
    {
        var str=xmlhttp3.responseText;
//        if(str=''){
            alert('you have not started this cashpoint');
//        }
//        document.csale.patient_name.value=str.split(",",1);
        //applyFilter();
        //countGridRecords(document.csale.patientId.value);
    }
}


    function showDesc(str)
    {
            xmlhttp=GetXmlHttpObject();
            if (xmlhttp==null)
            {
                alert ("Browser does not support HTTP Request");
                return;
            }
            var url="cashbookFns.php?desc="+str;
            url=url+"&sid="+Math.random();
            url=url+"&callerID=points";
            xmlhttp.onreadystatechange=stateChanged;
            xmlhttp.open("POST",url,true);
            xmlhttp.send(null);
        
    }

     function showPayDesc(str)
    {
        xmlhttp2=GetXmlHttpObject();
        if (xmlhttp2==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        //var strP=document.csale.cash_point.value;
        var dd = document.csale.cash_point.selectedIndex;
        var strP = document.csale.cash_point[dd].text; 

        var url="cashbookFns.php?desc2="+str;
        url=url+"&sid2="+Math.random();
        url=url+"&callerID=paymode&point="+strP;
        xmlhttp2.onreadystatechange=stateChanged2;
        xmlhttp2.open("POST",url,true);
        xmlhttp2.send(null);

    }

    function getPatient(str)
    {
        xmlhttp3=GetXmlHttpObject();
        if (xmlhttp3==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="cashbookFns.php?desc3="+str;
        url=url+"&sid="+Math.random();
        url=url+"&callerID=patient";
        xmlhttp3.onreadystatechange=stateChanged3;
        xmlhttp3.open("POST",url,true);
        xmlhttp3.send(null);

    }

    

function stateChanged3()
{
     //get payment description
   if (xmlhttp3.readyState==4)//show point desc
    {
        var str=xmlhttp3.responseText;
        var str2=str.search(/,/)+1
        document.csale.patient_name.value=str.split(",",1);
        //applyFilter();
        //countGridRecords(document.csale.patientId.value);
    }
}

function stateChanged2()
{
     //get payment description
    if (xmlhttp2.readyState==4)
    {
        var str=xmlhttp2.responseText;
        var str2=str.search(/,/)+1
        document.csale.paymode_desc.value=str.split(",",1);
        document.csale.gl_acc.value=str.substr(str2);
    }
}


        
 function stateChanged()
{
     //get payment description
    if (xmlhttp.readyState==4)
    {
        var str=xmlhttp.responseText;
        var str2=str.split(",")
        document.csale.cashpoint_desc.value=str2[0];
        document.csale.receiptNo.value=str2[1];
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
</script>