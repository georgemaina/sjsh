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

    function chkform(d) {
        if(d.cashpoint_desc.value==""){
            alert("Please enter the cashpoint");
            d.cashpoint_desc.focus();
            return false;
        }else if(d.salesReceiptNo.value==""){
            alert("Please enter the salesReceiptNo");
            d.salesReceiptNo.focus();
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
            alert("Enter the Patient Name");
            d.patient_name.focus();
            return false;
        }else if(d.payer.value==""){
            alert("Enter the Payer");
            d.payer.focus();
            return false;
        }else if(d.bal.value<0) {
            alert('The balance cannot be negative, Please check your Entries');
            d.cash.focus();
            return false;
        }else if(d.mpesa.value>0 && d.mpesaref.value==""){
            alert("Please enter mpesa Reference number");
            d.mpesaref.focus();
            return false;
        }else if(sgrid.getRowsNum()<0){
            alert("Please enter the revenue_code");
            return false;
        }else if(d.total.value==""){
            alert("Please enter the revenue_code");
            return false;
        }else if(!IsNumeric(trim(d.patientId.value))){
            alert("Please enter the right Patient Number");
            return false;
        }else if(d.cash.value=="") {
            alert("Please enter the amount Paid");
            return false;
        }else if(parseInt(d.cash.value,10)<parseInt(d.total.value,10)){
            alert("The amount paid cannot be Less than Total bill");
            return false;
        }
    }
</script>
<table border="0">
    <tr>
        <td colspan="5">
            <div id="myMessage" class='myMessage' ></div>
        </td></tr>
    <tr><td>
            Cash Point:</td>
        <td valign="top">
            <input type='text' size='36' name='cash_point' id='cash_point' value="<?php echo $cashpoint ?>" onblur='getCashPointS(this.value)' onclick="getCashPointS(this.value)"/>
            <input type='text' size='36' name='cashpoint_desc' id='cashpoint_desc' value="<?php echo $cname ?>"  /></td>
        <td></td>
        <td>Receipt No:</td>
        <td><input type='text' name='salesReceiptNo' id='salesReceiptNo' value="<?php echo $prefix . '' . $receipt_no ?>" readonly="true" /></td></tr>

    <tr><td colspan="5"> <hr> </td></tr>
    <tr>
        <td>Payment Mode:</td>
        <td>
            <SELECT id="paymode" name="paymode" onchange="showSalesPayDesc(this.value)" onblur="showSalesPayDesc(this.value)">
                <?php
                $sql="SELECT DISTINCT Payment_mode FROM care_ke_paymentmode";
                $results=$db->Execute($sql);
                while($row=$results->FetchRow()){
                    echo "<OPTION VALUE='$row[0]'>$row[0]</OPTION>";
                }
                ?>
             </SELECT>
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
        <td>Cheque No:</td>
        <td><input type="text" name="cheq_no" id="cheq_no"  /></td>
    </tr>
    <tr>
        <td>Payer:</td>
        <td colspan="2"><input name="payer" type="text" id="payer" size="45"  /></td>
        <td></td>

        <td><input type="text" name="encounter_nr" id="encounter_nr"  value="" /><input type="text" id="encounter_class_nr" name="encounter_class_nr" value="" /></td>
    </tr>
    <tr>
        <td>Patient:</td>
        <td><input type="text" name="patientId" id="patientId" onblur="getCSalePatient(this.value)" ondblclick="initPatientSearch() "/>
            <input type="text" name="patient_name" id="patient_name" size="30"/></td>
        <td> </td>
        <td>Bill Number: </td>
        <td> <input type="text" name="bill_number" id="bill_number" onblur="sumColumn(6)" />
            <input type="hidden" name="myform_key" value="<?php echo md5(date('Y:m:d H:i:s')); ?>" /></td>

    </tr>


    <!-- onblur="getPatient(this.value)" ondblclick="initPsearch()" -->


    <!--  ========================================================================
            Ajax scripts
     =============================================================================-->
    <script>
       
        function getCSalePatient(str)
        {
            xmlhttp3=GetXmlHttpObject();
            if (xmlhttp3==null)
            {
                alert ("Browser does not support HTTP Request");
                return;
            }
            var url="cashbookfns.php?desc3="+str;
            url=url+"&sid="+Math.random();
            url=url+"&callerID=patient";
            xmlhttp3.onreadystatechange=stateChangedSale;
            xmlhttp3.open("POST",url,true);
            xmlhttp3.send(null);

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
                document.csale.encounter_class_nr.value=str2[6];
				document.csale.bill_number.value=str2[5];
                applyFilter();
                //countGridRecords(document.csale.patientId.value);
                getCashPointS(document.csale.cash_point.value);
                showSalesPayDesc(document.csale.paymode.value)
                //getpBillNumber(document.csale.patientId.value)
               
               
            }
        }
        
        function getCashPointS(str){

            xmlhttp=GetXmlHttpObject();
            if (xmlhttp==null)
            {
                alert ("Browser does not support HTTP Request");
                return;
            }
            var url="shiftFns.php?cashPoint="+str;
            url=url+"&sid="+Math.random();
            url=url+"&callerID=Receipts";
            xmlhttp.onreadystatechange=stateChangedCahpoints;
            xmlhttp.open("POST",url,true);
            xmlhttp.send(null);
        }
    

        function stateChangedCahpoints()
        {
            if (xmlhttp.readyState==4)
            {
                var str=xmlhttp.responseText;
        
                var str2=str.split(",")
                if(str2[0]==1){
                    msg="The CashPoint is Closed";
                    document.getElementById("myMessage").innerHTML =msg;
                    document.csale.cashpoint_desc.value="";
                    document.csale.salesReceiptNo.value="";
                }else if(str2[0]==2){
                    msg="The CashPoint is in use by "+str2[1];
                    document.getElementById("myMessage").innerHTML =msg;
                    document.csale.cashpoint_desc.value="";
                    document.csale.salesReceiptNo.value="";
                }else{
                    document.getElementById("myMessage").innerHTML="";
                    document.csale.cashpoint_desc.value=str2[0];
                    document.getElementById('salesReceiptNo').value=str2[3]+str2[1];
                    //                    
                }
            }
        }


        function showSalesPayDesc(str)
        {
//            alert(str);
            if(str=='MCASH' || str=='mcash'){
                document.getElementById('dmpesa').style.display='block';
                document.getElementById('dvisa').style.display='block';
                document.getElementById('dcredit').style.display='block';
            }

            xmlhttp=GetXmlHttpObject();
            if (xmlhttp==null)
            {
                alert ("Browser does not support HTTP Request");
                return;
            }
            cashPoint=document.csale.cash_point.value;
            var url="cashbookfns.php?payMode="+str;
            url=url+"&sid="+Math.random();
            url=url+"&callerID=payDesc";
            url=url+"&cashpoint="+cashPoint;
            xmlhttp.onreadystatechange=stateChangedPayMode;
            xmlhttp.open("POST",url,true);
            xmlhttp.send(null);
        
        }

        function stateChangedPayMode()
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

        
        
        function getpBillNumber(str)
        {
            xmlhttp8=GetXmlHttpObject();
            var encNo=document.getElementById('encounter_nr').value;
            if (xmlhttp8==null)
            {
                alert ("Browser does not support HTTP Request");
                return;
            }
            var url="cashbookfns.php?desc8="+str;
            url=url+"&sid="+Math.random();
            url=url+"&encNo="+encNo;
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
