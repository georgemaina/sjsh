<style type="text/css">

    .style1 {
        font-size: 20px;
        font-weight: bold;
        color: #0099CC;
    }

</style>

<!--<div id="ta" style="margin-top: 50px; width: 500px; height: 50px; border: #909090 1px solid; overflow: auto; font-size: 10px; font-family: Tahoma;"></div>-->

<table class="style1">
    <tr valign="top" class="pgtitle">
        <td colspan="6">Cash Sale</td>
    </tr>
   <tr class="prow"><td colspan=6>You are using Cashpoint number <?php echo $point ?> and shift No <?php echo $shiftNo ?></td></tr>
   <tr>
       <td colspan="6">&nbsp;</td>
    </tr>
   <tr valign="top"  class="prow2">
        <td>Cash Point:</td>
        <td colspan="2">

            <?php

            if(isset($point)) {
                $sql = "Select Pcode,name,next_receipt_no from care_ke_cashpoints where pcode='$point'";
            }else {
                $sql = "Select pcode,name,next_receipt_no from care_ke_cashpoints";
            }

             $result=$db->Execute($sql);
            if (!($row=$result->FetchRow())) {
                echo 'shift Could not run query: ' . mysql_error();
                exit;
            }

            echo "<select id=\"cash_point\" name=\"cash_point\" onchange=\"showDesc(this.value)\">";

            if(!isset($point)) {
                while($row=$result->FetchRow()) {
                    echo "<option value=$row[0]>$row[0]</option>";
                }
                echo "</select>";
                echo "<input type=\"text\" size=\"36\" name=\"cashpoint_desc\" id=\"cashpoint_desc\" value=".$row[1] ."/></td>";
                echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                echo " <td>Receipt No:</td>";
                echo "<td><input type=\"text\" name=\"receiptNo\" id=\"receiptNo\" value=" .$row[2] . "/></td></tr>";
           }else {
                echo "<option value=$row[0]>$row[0]</option>";
                echo "</select>";

                echo "<input type=\"text\" size=\"36\" name=\"cashpoint_desc\" id=\"cashpoint_desc\" value=".$row[1] . "  /></td>";
                echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                echo " <td>Receipt No:</td>";
                echo "<td><input type=\"text\" name=\"receiptNo\" id=\"receiptNo\" value=" .$row[2] . " /></td></tr>";

            }

            ?>
            <!---------------------------------------------------------
            fields to be entered
            -->
    <tr><td colspan="6"> <hr> </td></tr>
    <tr class="prow2">
        <td>Payment Mode:4334</td>
        <td><input type="text" name="paymode" id="paymode" onblur="showPayDesc(this.value)"/></td>
        <td><input type="text" name="paymode_desc" id="paymode_desc"/></td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>G/L Acc No:</td>
        <td><input type="text" name="gl_acc" id="gl_acc" /></td>
    </tr>
    <tr class="prow2">
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
    <tr class="prow2">
        <td>Payer:</td>
        <td colspan="2"><input name="payer" type="text" id="payer" size="45" /></td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr class="prow2">
        <td>Patient:</td>
        <td><input type="text" name="patientId" id="patientId" onblur="getPatient(this.value)" ondblclick="initPsearch()"/></td>
        <td colspan="3"><input type="text" name="patient_name" id="patient_name" size="30" onblur="applyFilter()"/></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr class="prow2">
        <td><div id="importText"></div></td>
        <td><div id="importbutton"></div></td>
        <td colspan="3"></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>


<!-- onblur="getPatient(this.value)" ondblclick="initPsearch()" -->


<!--  ========================================================================
        Ajax scripts
 =============================================================================-->
<script>

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

//     function showPayDesc(str)
//    {
//        xmlhttp2=GetXmlHttpObject();
//        if (xmlhttp2==null)
//        {
//            alert ("Browser does not support HTTP Request");
//            return;
//        }
//        //var strP=document.csale.cash_point.value;
//        var dd = document.csale.cash_point.selectedIndex;
//        var strP = document.csale.cash_point[dd].text;
//
//        var url="cashbookFns.php?desc2="+str;
//        url=url+"&sid2="+Math.random();
//        url=url+"&callerID=paymode&point="+strP;
//        xmlhttp2.onreadystatechange=stateChanged2;
//        xmlhttp2.open("POST",url,true);
//        xmlhttp2.send(null);
//
//    }

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
        //document.csale.paymode_desc.value=str.split(",",1);
        document.csale.gl_acc.value=str.substr(str2);


    }
}



 function stateChanged()
{
     //get payment description
    if (xmlhttp.readyState==4)
    {
        var str=xmlhttp.responseText;
        var str2=str.search(/,/)+1
        document.csale.cashpoint_desc.value=str.split(",",1);
        document.csale.receiptNo.value=str.substr(str2);
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