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
        <td colspan="2">Cash Points</td>
    </tr>';
echo '<tr><td class="tdl">&nbsp;</td><td align="left" class="tdl"><div id="menuObj"></div></tr>';
echo '<tr><td>'.doLinks().'</td>';
echo '<td valign="top"><table  class="style1">
    <tr><td width=20%>Cash Point</td><td align=left>
    <input type="text" id="cashPoint" name="cashPoint" value="" /></td></tr>
    <tr><td>Description</td><td><input type="text" id="desc" name="desc" value="" /></td></tr>
    <tr><td>Prefix</td><td><input type="text" id="prefix" name="prefix" value="" /></td></tr>
    <tr><td colspan=2 align=center><button onclick="sendData()">Save</button><button onclick="applyFilter()">Refresh</button></td></tr>
      <tr><td colspan=2>
        <div id="cash_point_grid" width="600px" height="300px" style="background-color:white;overflow:hidden"></div>
       
        <button onclick="removeRow()">Delete Cash Point</button>';
  echo '</td></tr>';
echo '</table>';
?>
<script>

            var pgrid = new dhtmlXGridObject('cash_point_grid');

            pgrid.setImagePath("codebase/imgs/");
            pgrid.setHeader("code,name,prefix,next receit NO,next voucher No,next shift no");
           // mygrid.attachHeader("#connector_text_filter,#connector_text_filter")
            pgrid.setInitWidths("50,150,100,100,100,100");
            pgrid.setSkin("light");
            pgrid.setColSorting("str,str,str,int,int,int");
            pgrid.setColTypes("ed,ed,ed,ed,ed,ed");
            pgrid.enableSmartRendering(true);
            pgrid.enableMultiselect(true);
            pgrid.init();
            pgrid.loadXML("getCashPoints.php")

            var pDP=new dataProcessor("getCashPoints.php");
            pDP.setUpdateMode("off");
            pDP.init(pgrid);


            function applyFilter(){

                paygrid.clearAll(); //remove all data
                gridQString = "getCashPoints.php";
                paygrid.loadXML(gridQString); // load new dataset from sever with additional parameter passed

                var revDP=new dataProcessor(gridQString);
                revDP.init(paygrid);

            }
            
            function addRowp(){
                var newId = (new Date()).valueOf();
                pgrid.addRow(newId,"",pgrid.getRowsNum())
                pgrid.selectRow(pgrid.getRowIndex(newId),false,false,true);
            }
            
                var xmlhttp;
            
             function sendData(){
                 xmlhttp=GetXmlHttpObject();
                if (xmlhttp==null)
                {
                    alert ("Browser does not support HTTP Request");
                    return;
                }
                var cashPoint=document.getElementById('cashPoint').value;
                var prefix=document.getElementById('prefix').value;
                var desc=document.getElementById('desc').value;
                
                var url="gridVals.php?desc3=cashpoints";
                url=url+"&sid="+Math.random();
                url=url+"&cashPoint="+cashPoint;
                url=url+"&prefix="+prefix;
                url=url+"&desc="+desc;
                xmlhttp.onreadystatechange=stateChanged;
                xmlhttp.open("POST",url,true);
                xmlhttp.send(null);
             }
             
             function stateChanged()
                {
                    //get payment description
                    if (xmlhttp.readyState==4)
                    {
                        var str=xmlhttp.responseText;
                        if(str=='success'){
                            
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
        
       