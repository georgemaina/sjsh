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
        <td colspan="2">Pharmacy Codes</td>
    </tr>';
echo '<tr><td class="tdl">&nbsp;</td><td align="left" class="tdl"><div id="menuObj"></div></tr>';
echo '<tr><td>'.doLinks().'</td>';
echo '<td valign="top"><table  class="style1"><tr><td>
        <div id="rev_codes" width="700px" height="400px" style="background-color:white;overflow:hidden"></div>
         <br><br><button onclick="addRowR()">Add Cash point</button>
        <button onclick="removeRow()">Delete Cash Point</button>
 ';
echo '</td></tr>';
echo '</table>';
 ?>

   <script>
            var revgrid = new dhtmlXGridObject('rev_codes');
            revgrid.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");
            revgrid.setHeader("code,description,price");
            revgrid.attachHeader("#connector_text_filter,#connector_text_filter")
            revgrid.setInitWidths("50,300,100");
            revgrid.setSkin("light");
            revgrid.setColSorting("str,str,str");
            revgrid.setColTypes("ed,ed,ed");
            revgrid.enableSmartRendering(true);
            revgrid.enableMultiselect(true);
            revgrid.init();
            revgrid.loadXML("pharmconn_pop.php")

            var revDP=new dataProcessor("pharmconn_pop.php");
            revDP.init(revgrid);

            function addRowR(){
                var newId = (new Date()).valueOf();
                revgrid.addRow(newId,"",revgrid.getRowsNum())
                revgrid.selectRow(revgrid.getRowIndex(newId),false,false,true);
            }
        </script>