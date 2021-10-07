<style type="text/css">
    <!--
    .style1 {
        font-size: 20px;
        font-weight: bold;
        color: #0099CC;
    }
    .myMessage{font-size: large;color: #ffffff;background-color: #cc0033;font-weight: bold;
               text-decoration: blink;width: 80%;text-align: center;}
    -->
</style>

<script language="JavaScript">
    function IsNumeric(strString) //  check for valid numeric strings
    {
        if(!/\D/.test(strString)) return true;//IF NUMBER
        else if(/^\d+\.\d+$/.test(strString)) return true;//IF A DECIMAL NUMBER HAVING AN INTEGER ON EITHER SIDE OF THE DOT(.)
        else return false;
    }

    function chkform(d) {
        if(d.cashpoint_desc.value==""){
            alert("Please enter the cashpoint");
            d.cashpoint_desc.focus();
            return false;
        }else if(d.receiptNo.value==""){
            alert("Please enter the receiptNo");
            d.receiptNo.focus();
            return false;
        }else if(d.paymode.value==""){
            alert("Please enter the Payment mode");
            d.paymode.focus();
            return false;
        }else if(d.calInput.value==""){
            alert("Please enter the date");
            d.calInput.focus();
            return false;
        }else if(d.patientId.value==""){
            alert("Please enter the Patient No");
            d.patientId.focus();
            return false;
        }else if(d.patient_name.value==""){
            alert("Enter ther Patient Name");
            d.patient_name.focus();
            return false;
        }else if(d.payer.value==""){
            alert("Enter ther Payer");
            d.payer.focus();
            return false;
        }else if(sgrid.getRowsNum()<0){
            alert("Please enter the revenue_code");
            return false;
        }else if(d.total.value==""){
            alert("Please enter the revenue_code");
            return false;
        }else if(!IsNumeric(d.patientId.value)){
            alert("Please enter the right Patient Number");
            return false;
        }else if(d.paid.value==""){
            alert("Please enter the amount Paid");
            return false;
        }else if(d.paid.value<d.total.value){
            alert("The amount paid cannot be Less than Total bill");
            return false;
        }
    }
</script>
<table border="0" width="80%">
    <tr>
        <td colspan="5">
            <div id="myMessage" class='myMessage' ></div>
        </td></tr>
    <tr><td valign="top"></td>
        <td><input type="radio" name="saleType" value="cash" />Cash
            <input type="radio" name="saleType" value="Credit" />Credit
            <input type="radio" name="saleType" value="Guest" />Guest
           <input type="radio" name="saleType" value="Staff" /> Staff</td>
        <td></td>
        <td></td></tr>

    <tr><td>
            Cash Point:</td>
        <td valign="top">
            <input type='text' size='36' name='cash_point' id='cash_point' value="<?php echo $cashpoint ?>" onblur='getCashPoint(this.value)'/>
            <input type='text' size='36' name='cashpoint_desc' id='cashpoint_desc' value="<?php echo $cname ?>"  /></td>
        <td></td>
        <td>Receipt No:</td>
        <td><input type='text' name='receiptNo' id='receiptNo' value="<?php echo $prefix.''.$receipt_no ?>" readonly="true" /></td></tr>

    <tr><td colspan="5"> <hr> </td></tr>
    <tr>
        <td>Payment Mode:</td>
        <td><input type="text" name="paymode" id="paymode" onblur="showSalesPayDesc(this.value)" value="cas"/>
            <input type="text" name="paymode_desc" id="paymode_desc"/></td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>G/L Acc No:</td>
        <td><input type="text" name="gl_acc" id="gl_acc" /></td>
    </tr>
    <tr>
        <td>Date:</td>
        <td>
            <input type="text" name="calInput" id="calInput" value="<?php echo date('m/d/Y') ?>"/>
            <script>
                cal1=new dhtmlxCalendarObject('calInput',true);
                mCal.setSkin("dhx_black");
            </script>
        </td>

        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>Payer:</td>
        <td colspan="2"><input name="payer" type="text" id="payer" size="45"  /></td>
        <td></td>

        <td><input type="text" name="encounter_nr" value="" /><input type="text" name="encounter_class_nr" value="" /></td>
    </tr>
    <tr>
        <td>Patient:</td>
        <td><input type="text" name="patientId" id="patientId" onblur="getCSalePatient(this.value)" ondblclick="initPsearch() "/>
            <input type="text" name="patient_name" id="patient_name" size="30" onblur="applyFilter()"/></td>
        <td> </td>
        <td>Bill Number: </td>
        <td> <input type="text" name="bill_number" id="bill_number" onblur="sumColumn(6)" onblur="getpBillNumber(document.csale.patientId.value)"/>
        </td>

    </tr>


    <!-- onblur="getPatient(this.value)" ondblclick="initPsearch()" -->


    <!--  ========================================================================
            Ajax scripts
     =============================================================================-->
    <script>
       
        
        function getCashPoint(str){
            xmlhttp=GetXmlHttpObject();
            if (xmlhttp==null)
            {
                alert ("Browser does not support HTTP Request");
                return;
            }
            var url="shiftFns.php?cashPoint="+str;
            url=url+"&sid="+Math.random();
            url=url+"&callerID=Receipts";
            xmlhttp.onreadystatechange=stateChanged7;
            xmlhttp.open("POST",url,true);
            xmlhttp.send(null);
        }
    

        function stateChanged7()
        {
            if (xmlhttp.readyState==4)
            {
                var str=xmlhttp.responseText;
        
                var str2=str.split(",")
                if(str2[0]==1){
                    msg="The CashPoint is Closed";
                    document.getElementById("myMessage").innerHTML =msg;
                    document.csale.cashpoint_desc.value="";
                    document.csale.receiptNo.value="";
                }else if(str2[0]==2){
                    msg="The CashPoint is in use by "+str2[1];
                    document.getElementById("myMessage").innerHTML =msg;
                    document.csale.cashpoint_desc.value="";
                    document.csale.receiptNo.value="";
                }else{
                    document.getElementById("myMessage").innerHTML="";
                    document.csale.cashpoint_desc.value=str2[0];
                    document.csale.receiptNo.value=str2[3]+str2[1];
                     showSalesPayDesc(document.csale.paymode.value)
                }
            }
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

function showSalesPayDesc(str)
    {
            xmlhttp=GetXmlHttpObject();
            if (xmlhttp==null)
            {
                alert ("Browser does not support HTTP Request");
                return;
            }
            cashPoint=document.csale.cash_point.value;
            var url="cashbookFns.php?payMode="+str;
            url=url+"&sid="+Math.random();
            url=url+"&callerID=payDesc";
            url=url+"&cashpoint="+cashPoint;
            xmlhttp.onreadystatechange=stateChangedSales2;
            xmlhttp.open("POST",url,true);
            xmlhttp.send(null);
        
    }

     function stateChangedSales2()
{
     //get payment description
    if (xmlhttp.readyState==4)
    {
        var str=xmlhttp.responseText;
        var str2=str.split(",")
        document.csale.paymode_desc.value=str2[0];
        document.csale.gl_acc.value=str2[1];
//        document.csale.gl_Desc.value=str2[2];
//        document.csale.chequeNo.value=str2[3];
    }
}

        function getCSalePatient(str)
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
            xmlhttp3.onreadystatechange=stateChangedSale;
            xmlhttp3.open("POST",url,true);
            xmlhttp3.send(null);

        }
        function getpBillNumber(str)
        {
            xmlhttp8=GetXmlHttpObject();
            if (xmlhttp8==null)
            {
                alert ("Browser does not support HTTP Request");
                return;
            }
            var url="cashbookFns.php?desc8="+str;
            url=url+"&sid="+Math.random();
            url=url+"&callerID=bill";
            xmlhttp8.onreadystatechange=stateChanged8;
            xmlhttp8.open("POST",url,true);
            xmlhttp8.send(null);

        }

        function stateChanged8()
        {
            //get payment description
            if (xmlhttp8.readyState==4)//show point desc
            {
                var str=xmlhttp8.responseText;
               
                document.csale.bill_number.value=str;
               
                //applyFilter();
                //countGridRecords(document.csale.patientId.value);
            }
        }

        function stateChangedSale()
        {
            //get payment description
            if (xmlhttp3.readyState==4)//show point desc
            {
                var str=xmlhttp3.responseText;
                str2=str.split(",");
                document.csale.patient_name.value=str2[0]+' '+str2[1]+' '+str2[2];
                document.csale.payer.value=str2[0]+' '+str2[1]+' '+str2[2];
                document.csale.encounter_nr.value=str2[3];
                document.csale.encounter_class_nr.value=str2[4];
                //applyFilter();
                //countGridRecords(document.csale.patientId.value);
                getpBillNumber(document.csale.patientId.value)
                getCashPoint(document.csale.cash_point.value);
               
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