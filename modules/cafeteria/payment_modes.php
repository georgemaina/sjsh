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
echo '<td valign="top"><table  class="style1"><tr><td>
        <div id="paymentm_grid" width="700px" height="400px" style="background-color:white;overflow:hidden"></div>
        <button onclick="addRowy()">Add Cash point</button>
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

        function addRowy(){
                var newId = (new Date()).valueOf();
                paygrid.addRow(newId,"",paygrid.getRowsNum())
                paygrid.selectRow(paygrid.getRowIndex(newId),false,false,true);
            }
        </script>