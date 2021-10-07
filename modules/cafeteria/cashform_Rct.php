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
        if(d.cashPoint_Desc.value==""){
            alert("Please enter the cashPoint Desc");
            d.cashPoint_Desc.focus();
            return false;
        }else if(d.receiptNo.value==""){
            alert("Please enter the voucher No");
            d.receiptNo.focus();
            return false;
        }else if(d.paymode.value==""){
            alert("Please enter the Payment mode");
            d.paymode.focus();
            return false;
        }else if(d.pdate.value==""){
            alert("Please enter the date");
            d.pdate.focus();
            return false;
        }else if(d.gl_acc.value==""){
            alert("Please enter the gl Acc No");
             d.gl_acc.focus();
            return false;
        }else if(d.gl_Desc.value==""){
            alert("Enter ther gl Desc ");
            d.gl_Desc.focus();
            return false;
        }else if(sgrid.getRowsNum()<0){
            alert("Please enter the ledgers");
            return false;
        }else if(d.payee.value==""){
            alert("Please enter the payee");
            d.payee.focus();
            return false;
        }else if(d.amtControl.value==""){
            alert("Please enter the Control amount ");
            d.amtControl.focus()
            return false;
        }else if(d.amtControl.value!=d.total.value){
            alert("The amount paid cannot be Less than Control Amount");
            d.total.focus()
            return false;
        }
    }
</script>

<table border="0" width="70%">
<tr>
    <td colspan="5">
        <div id="myMessage" class='myMessage' ></div>
    </td></tr>
  <tr>
        <td>Cash Point:</td>
        <td><input type="text" name="cashPoint" id="cashPoint" onblur="getCashPoint(this.value)"/></td>
        <td><input type="text" name="cashPoint_Desc" id="cashPoint_Desc" size="35"/></td>
        <td>Receipt No:</td>
        <td><input type="text" name="receiptNo" id="receiptNo" readonly="true" /></td>

    </tr>
    <tr>
        <td>Payment Mode:</td>
        <td><input type="text" name="paymode" id="paymode" onblur="showPayDesc(this.value)"/></td>
        <td><input type="text" name="paymode_desc" id="paymode_desc" size="35"/></td>
        <td>Date:</td>
        <td><input type="text" name="pdate" id="pdate" value="<?php echo date("d-m-Y")?>" /></td>

    </tr>
     <tr>
        <td>Patient No(PID):</td>
        <td><input type="text" name="pid" id="pid"  onblur="getPatientDesc3(this.value)" ondblclick="getPatients()"/></td>
        <td><input type="text" name="pname" id="pname" size="35"/></td>
        <td></td>
        <td></td>

    </tr>
    <tr>
        <td>GL Acc</td>
        <td><input type="text" name="gl_acc" id="gl_acc" /></td>
        <td><input type="text" name="gl_Desc" id="gl_Desc" size="35" /></td>
        <td></td>
        <td></td>

    </tr>
    <tr>
        <td>Payee:</td>
        <td colspan="2"><input name="payee" type="text" id="payee" size="50" /></td>
        <td></td>
        <td></td>
        
    </tr>
    <tr>
        <td>Toward:</td>
        <td colspan="2"><input type="text" name="toward" id="toward" size="50"/></td>
        <td></td>
        <td></td>
        
    </tr>
    <tr>
        <td>Drawers Bank</td>
        <td colspan="2"><input type="text" name="drawerBank" id="drawerBank" size="50"/></td>
        <td>Cheque:</td>
        <td><input type="text" name="chequeNo" id="chequeNo"/></td>
        
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
        }else if(str2[0]==2){
            msg="The CashPoint is in use by "+str2[1];
            document.getElementById("myMessage").innerHTML =msg;
        }else{
             document.getElementById("myMessage").innerHTML="";
            document.csale.cashPoint_Desc.value=str2[0];
            document.csale.receiptNo.value=str2[3]+''+str2[1];
        }
    }
}


    function showPayDesc(str)
    {
            xmlhttp=GetXmlHttpObject();
            if (xmlhttp==null)
            {
                alert ("Browser does not support HTTP Request");
                return;
            }
            cashPoint=document.csale.cashPoint.value;
            var url="cashbookFns.php?payMode="+str;
            url=url+"&sid="+Math.random();
            url=url+"&callerID=payDesc";
            url=url+"&cashpoint="+cashPoint;
            xmlhttp.onreadystatechange=stateChanged2;
            xmlhttp.open("POST",url,true);
            xmlhttp.send(null);
        
    }

     function stateChanged2()
{
     //get payment description
    if (xmlhttp.readyState==4)
    {
        var str=xmlhttp.responseText;
        var str2=str.split(",")
        document.csale.paymode_desc.value=str2[0];
        document.csale.gl_acc.value=str2[1];
        document.csale.gl_Desc.value=str2[2];
        document.csale.chequeNo.value=str2[3];
    }
}

    function getPatientDesc3(pid){
    xmlhttp=GetXmlHttpObject();
        if (xmlhttp==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="cashbookFns.php?PID="+pid;
        url=url+"&sid="+Math.random();
        url=url+"&callerID=Patients";
        xmlhttp.onreadystatechange=stateChanged3;
        xmlhttp.open("POST",url,true);
        xmlhttp.send(null);
    }

    

function stateChanged3()
{
     //get payment description
   if (xmlhttp.readyState==4)//show point desc
    {
        var str=xmlhttp.responseText;
         var str2=str.split(",")
        document.csale.pname.value=str;//str2[0];//+' '+str2[1]+' '+str2[2];
        //applyFilter();
        //countGridRecords(document.csale.patientId.value);
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