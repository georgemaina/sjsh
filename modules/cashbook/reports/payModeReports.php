<link rel="stylesheet" type="text/css" href="reports.css">
<?php
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require('roots.php');
require($root_path . 'include/inc_environment_global.php');
require_once '../mylinks.php';
require_once 'shift_search.php';
?>
<link rel="stylesheet" type="text/css" href="<?php echo $root_path; ?>include/Extjs/resources/css/ext-all.css"/>
<script type="text/javascript" src="<?php echo $root_path; ?>include/Extjs/adapter/ext/ext-base.js"></script>
<script type="text/javascript" src="<?php echo $root_path; ?>include/Extjs/ext-all-debug.js"></script>


<script type="text/javascript" src="selectShiftSummary.js"></script>
<script type="text/javascript" src="../reportFunctions.js"></script>
<?php
printform($db);
if (isset($_POST[printpdf])) {
    window . open('shift_report_pdf.php');
}

function printform($db) {
    ?>

    <div class=pgtitle>Shift Report</div>
    <table border="1" width="100%">
        <tr><td align=left rowspan="2" width="15%" valign="top"> <?php
    require_once 'reports_menus.php';
    ?>
            </td><td valign=top align=center width="85%">

                <table align="right" width="100%" border="0"><tr><td>
                            <?php
                            $sql = "SELECT DISTINCT payment_mode,description FROM care_ke_paymentmode";

                            $result = $db->Execute($sql);
                            if (!($row = $result->FetchRow())) {
                                echo 'shift Could not run query: ' . mysql_error();
                                exit;
                            }
                            ?>
                            Payment Modes:<select id="payModes" name="payModes">
                                <option value="0">Select Payment Mode</option>
                                <?php
                                //if(!isset($point)) {
                                while ($row = $result->FetchRow()) {
                                    ?>
                                    <option value=<?php echo $row[0] ?>><?php echo $row[1] ?></option>
                                    <?php
                                }
                                //  }
                                ?>
                            </select></td>
                        <td id="datefield" align='left'>Start Date</td>
                        <td id="datefield2" align='left'>End Date</td>
                        <td align='left'><input type="submit" id="getShift" value=" Preview" onclick="getMpesaReport(document.getElementById('payModes').value)"></td>
                        <td align='left'><input type="button" value="  Print  " name="printpdf" id="printpdf"  onclick="mpesaReportpdf(document.getElementById('payModes').value)"/> </td>

                    </tr>
                    <tr><td colspan="5"><hr></td></tr>
                    <tr><td id="txtHint" valign="top" colspan="5">
                            <b>The Report will be Displayed here.</b>
                        </td></tr>
                </table>

            </td> 
        </tr></table>
    <?php
}
?>
<script>
    var xmlhttp10
    function getMpesaReport(str){
        var strDate1=document.getElementById('strDate1').value
        var strDate2=document.getElementById('strDate2').value
//        alert(str +' , '+strDate1 +' , '+strDate2);
        
        xmlhttp10=GetXmlHttpObject();
        if (xmlhttp10==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="../getDesc.php?pmode="+str;
        url=url+"&sid="+Math.random();
        url=url+"&callerID=getPayModeReceipts";
        url=url+"&startDate="+strDate1;
        url=url+"&endDate="+strDate2;
        xmlhttp10.onreadystatechange=stateChanged10;
        xmlhttp10.open("POST",url,true);
        xmlhttp10.send(null);
        
    }
    
    
    function stateChanged10()
    {
        if (xmlhttp10.readyState==4)
        {
            document.getElementById("txtHint").innerHTML=xmlhttp10.responseText;
        }
    }
    
    
    
    function mpesaReportpdf(pmode){
         var strDate1=document.getElementById('strDate1').value
        var strDate2=document.getElementById('strDate2').value
//          alert(pmode +' , '+strDate1 +' , '+strDate2);
        window.open('mpesa_report_pdf.php?pmode='+pmode+'&startDate='+strDate1+'&startDate='+strDate2 ,"Reports By Payment Mode","menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
    }
 
    var dhxWins, w1, grid;
    function getShiftNos(){
        dhxWins = new dhtmlXWindows();

        dhxWins.setImagePath("../../../include/dhtmlxWindows/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 200, 300, 600, 250);
        w1.setText("Search Shifts");
        grid = w1.attachGrid();
        grid.setImagePath("../../../include/dhtmlxGrid/codebase/imgs/");
        grid.setHeader("cash points,shift No,cashier,start_date,start time,end_date,end_time");
        grid.attachHeader("#connector_text_filter,#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ro,ro,ro,ro,ro");
        grid.setColSorting("str,int,str,str,str,str,str");
        grid.setInitWidths("80,80,80,80,80,80,80");
        grid.loadXML("Shift_find.php");
        grid.attachEvent("onRowSelect",doOnRowSelected);
        grid.attachEvent("onEnter",doOnEnter);
        grid.attachEvent("onRightClick",doOnRightClick);
        grid.attachEvent("onRightClick",doOnRightClick);
        grid.init();
        grid.enableSmartRendering(true);
    }

    function doOnRightClick(id,ind){
        document.getElementById('shift_No').value=grid.cells(id,1).getValue();
        //        document.getElementById('Description').value=grid.cells(id,1).getValue();
        //        document.getElementById('Amount').value=grid.cells(id,2).getValue();
        closeWindow();
    }
    function doOnRowSelected(id,ind){
        document.getElementById('shift_No').value=grid.cells(id,1).getValue();
        //        document.getElementById('Description').value=grid.cells(id,1).getValue();
        //        document.getElementById('Amount').value=grid.cells(id,2).getValue();
           
    }

    function doOnEnter(rowId,cellInd){
        document.getElementById('shift_No').value=grid.cells(rowId,1).getValue();
        //        document.getElementById('Description').value=grid.cells(rowId,1).getValue();
        //        document.getElementById('Amount').value=grid.cells(rowId,2).getValue();

        closeWindow();
    }
    function closeWindow() {
        dhxWins.window("w1").close();
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



