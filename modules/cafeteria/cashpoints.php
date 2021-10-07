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
echo '<td valign="top"><table  class="style1"><tr><td>
        <div id="cash_point_grid" width="600px" height="300px" style="background-color:white;overflow:hidden"></div>
        <button onclick="addRowp()">Add Cash point</button>
        <button onclick="removeRow()">Delete Cash Point</button>
        <button onclick="pDP.sendData()()">Save</button>';
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
            pgrid.loadXML("myconnector.php")



            var pDP=new dataProcessor("myconnector.php");
            pDP.setUpdateMode("off");
            pDP.init(pgrid);

            function addRowp(){
                var newId = (new Date()).valueOf();
                pgrid.addRow(newId,"",pgrid.getRowsNum())
                pgrid.selectRow(pgrid.getRowIndex(newId),false,false,true);
            }
        </script>