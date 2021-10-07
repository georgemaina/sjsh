<link rel="stylesheet" type="text/css" href="accounting.css">
 <?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require_once('roots.php');
require_once 'myLinks.php';
 jsIncludes();

//require_once($root_path.'include/care_api_classes/class_weberp_c2x.php');
//require_once($root_path.'include/inc_init_xmlrpc.php');
//require_once($root_path.'include/care_api_classes/class_tz_billing.php');

require_once 'dhxMenus.php';
echo '<table border="0" width="90%">';
echo '<tr valign="top" class="pgtitle">
        <td colspan="2">Payment Modes</td>
    </tr>';
echo '<tr><td class="tdl">&nbsp;</td><td align="left" class="tdl"><div id="menuObj"></div></tr>';
echo '<tr><td>'.doLinks().'</td>';
echo '<td valign="top"><table  class="style1">
      <tr><td width=20%>Cash Point</td><td align=left><input type="text" name="cashPoint" id="cashPoint"  value="" /></td></tr>
    <tr><td>Description</td><td><input type="text" id="desc" name="desc" value="" /></td></tr>
    <tr><td>Prefix</td><td><input type="text" id="prefix" name="prefix" id="prefix" value="" /></td></tr>
    <tr><td>PaymentMode</td><td><input type="text" id="payMode" name="payMode" value="" />
                            <input type="text" id="payDesc" name="payDesc" value="" /></td></tr>
    <tr><td>GL Account</td><td><input type="text" id="gl" name="gl" value="" />
                                <input type="text" id="glDesc" name="glDesc" value="" /></td></tr>
    <tr><td colspan=2><button onclick="sendData()">Save</button><button onclick="applyFilter()">Refresh</button></td></tr>
    <tr><td colspan=2>
        <div id="paymentm_grid" width="700px" height="400px" style="background-color:white;overflow:hidden"></div>
        <button onclick="removeRow()">Delete Cash Point</button>';
 echo '</td></tr>';
echo '</table>';
?>
<script>
            var paygrid = new dhtmlXGridObject('paymentm_grid');
            paygrid.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");
            paygrid.setHeader("cash point, pay mode, description, gl account");
           // mygrid.attachHeader("#connector_text_filter,#connector_text_filter")
            paygrid.setInitWidths("50,150,100,100");
            paygrid.setSkin("light");
            paygrid.setColSorting("str,str,str,int");
            paygrid.setColTypes("ed,ed,ed,ed");
            paygrid.enableSmartRendering(true);
            paygrid.enableMultiselect(true);
            paygrid.init();
            paygrid.loadXML("payconnect.php")

            var payDP=new dataProcessor("payconnect.php");
            payDP.init(paygrid);

        function applyFilter(){

                paygrid.clearAll(); //remove all data
                gridQString = "payconnect.php";
                paygrid.loadXML(gridQString); // load new dataset from sever with additional parameter passed

                var revDP=new dataProcessor(gridQString);
                revDP.init(paygrid);

            }
            
            
        function addRowy(){
                var newId = (new Date()).valueOf();
                paygrid.addRow(newId,"",paygrid.getRowsNum())
                paygrid.selectRow(paygrid.getRowIndex(newId),false,false,true);
            }
            
       function sendData(str){
           var CashPoint=document.getElementById('cashPoint').value;
           var Description=document.getElementById('desc').value;	
           var Prefix=document.getElementById('prefix').value;		
           var Paymode=document.getElementById('payMode').value;
           var PayDesc=document.getElementById('payDesc').value;
           var glAcc=document.getElementById('gl').value;	
           var glDesc=document.getElementById('glDesc').value;

            xmlhttp3=GetXmlHttpObject();
            if (xmlhttp3==null)
            {
                alert ("Browser does not support HTTP Request");
                return;
            }
            var url="cashbookFns.php?desc10="+CashPoint;
            url=url+"&sid="+Math.random();
            url=url+"&callerID=paymentMode";
            url=url+"&cashpoint="+CashPoint;
            url=url+"&Description="+Description;	
            url=url+"&Prefix="+Prefix;
            url=url+"&payMode="+Paymode;
            url=url+"&PayDesc="+PayDesc;
            url=url+"&GL="+glAcc;
            url=url+"&glDesc="+glDesc;
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
                //  document.csale.patient_name.value=str2[0]+' '+str2[1]+' '+str2[2];
                if(str2[0]=='1'){
                    alert('Success');
                }
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
       
        </script>