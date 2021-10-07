<link rel="stylesheet" type="text/css" href="reports.css">
<?php
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require('roots.php');
require($root_path . 'include/inc_environment_global.php');
require_once '../mylinks.php';
require_once 'shift_search.php';
?>
<!-- dhtmlxWindows -->
<link rel="stylesheet" type="text/css" href="../../../include/dhtmlxWindows/codebase/dhtmlxwindows.css">
<link rel="stylesheet" type="text/css" href="../../../include/dhtmlxWindows/codebase/skins/dhtmlxwindows_dhx_skyblue.css">
<script src="../../../include/dhtmlxWindows/codebase/dhtmlxcommon.js"></script>
<script src="../../../include/dhtmlxWindows/codebase/dhtmlxwindows.js"></script>

<!-- dhtmlxGrid -->
<link rel='STYLESHEET' type='text/css' href='../../../include/dhtmlxGrid/codebase/dhtmlxgrid.css'>
<script src='../../../include/dhtmlxGrid/codebase/dhtmlxcommon.js'></script>
<script src='../../../include/dhtmlxGrid/codebase/dhtmlxgrid.js'></script>
<!--<script src='../../../include/dhtmlxGrid/codebase/dhtmlxgrid_form.js'></script>-->
<script src='../../../include/dhtmlxGrid/codebase/ext/dhtmlxgrid_filter.js'></script>
<script src='../../../include/dhtmlxGrid/codebase/ext/dhtmlxgrid_srnd.js'></script>
<script src='../../../include/dhtmlxGrid/codebase/dhtmlxgridcell.js'></script>
<script src="../../../include/dhtmlxWindows/codebase/dhtmlxcontainer.js"></script>

<script src='../../../include/dhtmlxConnector/codebase/connector.js'></script>

<!-- dhtmlxCalendar -->
<link rel="STYLESHEET" type="text/css" href="../../../include/dhtmlxCalendar/codebase/dhtmlxcalendar.css">
<script src='../../../include/dhtmlxCalendar/codebase/dhtmlxcalendar.js'></script>
<script src='../../../include/dhtmlxCalendar/codebase/dhtmlxcommon.js'></script>
<script>window.dhx_globalImgPath = "'../../../include/dhtmlxCalendar/codebase/imgs/";</script>


<link rel="stylesheet" type="text/css" href="../../../include/Extjs/resources/css/ext-all.css" />
<script type="text/javascript" src="../../../include/Extjs/adapter/ext/ext-base.js"></script>
<script type="text/javascript" src="../../../include/Extjs/ext-all.js"></script>

<!--<script src="../../../../ext-4/ext-all.js"></script>-->
<!--<link rel="stylesheet" href="../../../../ext-4/resources/css/ext-all.css">-->

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

                <table align="right" width="100%" border='0'><tr><td>
                            <?php
                            $sql = "Select pcode,name,next_receipt_no from care_ke_cashpoints";

                            $result = $db->Execute($sql);
                            ?>
                            <select id="cash_point" name="cash_point"  onchange="getShiftNos(this.value)">
                                <option></option>
                                <?php
                                while ($row = $result->FetchRow()) {

                                    echo "<option value=$row[0]>$row[0]</option>";
                                }
                                ?>
                            </select>
                            <input type='text' id='shift_No' name='shift_No' ondblclick="getShiftNos(document.getElementById('cash_point').value)">
                            <input type="submit" id="getShift" value="Preview"
                                   onclick="getShiftReport(document.getElementById('cash_point').value,
                                               document.getElementById('shift_No').value)">
                            <input type="submit" id="Export" value="Export  " size="35"
                                   onclick="exportShiftReport(document.getElementById('cash_point').value,
                                               document.getElementById('shift_No').value)">

                            <?php
                            if($_REQUEST['report']=='detail'){
                                echo "<Select id='pgLayout' name='pglayout'>
                                                <option value='portrait'>Potrait</option>
                                                <option value='landascape'>landscape</option>
                                         </Select>";
                                ?> <input type="button" value="Print" name="printpdf" id="printpdf"
                                   onclick="printCollectionsPdf(document.getElementById('cash_point').value,
                                    document.getElementById('shift_No').value,document.getElementById('pgLayout').value)"/>
                            <?php }elseif($_REQUEST['report']=='revenue') { ?>
                                <input type='button' value='Export' name='exportRevenue' id='exportRevenue'
                                       onclick="exportRevenueRpt()" />
                            <?php }else { ?>
                               <input type='button' value='Print' name='printpdf' id='printpdf'
                                   onclick="printCollectionsPdf2(document.getElementById('cash_point').value, document.getElementById('shift_No').value)" />
                            <?php } ?>

                            <input type="text" id="reportid" name="reportid" value="<?php echo $_REQUEST['report']; ?>" readonly="false" />

                           
                        </td><td align='left'> <table><tr><td id="datefield">Start Date</td><td id="datefield2">End Date</td></tr></table></td></tr>
                    <tr><td id="txtHint" valign="top" colspan="2">
                            <b>Shift Report will be Displayed here.</b>
                        </td></tr>
                </table>

            </td> 
        </tr></table>
    <?php
}
?>
<script>

    function exportRevenueRpt() {
        var reportid=document.getElementById('reportid').value;
        var date1=document.getElementById('strDate1').value;
        var date2=document.getElementById('strDate2').value;

        window.open('exportRevenueReport.php?reportid=' + reportid + '&date1=' + date1+ '&date2=' + date2, "Revenue Report",
                    "menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");


    }



    function printCollectionsPdf(cashpoint, shiftno,pglayout) {
        var reportid=document.getElementById('reportid').value;
        var date1=document.getElementById('strDate1').value;
        var date2=document.getElementById('strDate2').value;
        
        if(reportid==='detail'){
            if(pglayout==='portrait'){
                window.open('shift_report_pdf.php?cashpoint=' + cashpoint + '&shiftno=' + shiftno, "Summary Invoice",
                    "menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
            }else{
                window.open('shift_report_pdfLandscape.php?cashpoint=' + cashpoint + '&shiftno=' + shiftno, "Summary Invoice",
                    "menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
            }

        }else if(reportid==='summary'){
            window.open('shift_report_Summary_pdf.php?cashpoint=' + cashpoint + '&shiftno=' + shiftno, "Summary Invoice",
            "menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
        }else if(reportid==='summaryByItem'){
            window.open('shift_reportByItem_pdf.php?cashpoint=' + cashpoint + '&shiftno=' + shiftno, "Summary Invoice",
                "menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
        }else if(reportid==='collection'){
            window.open('collectionsReport_pdf.php?cashpoint=' + cashpoint + '&shiftno=' + shiftno+'&reportid='+reportid+'&date1='+date1+'&date2='+date2, "Summary Invoice", 
            "menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
        }else if(reportid==='payments'){
            window.open('payments_pdf.php?cashpoint=' + cashpoint + '&shiftno=' + shiftno+ '&date1=' + date1+ '&date2=' + date2, "Payments Report",
                "menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
        }else if(reportid==='payments2'){
            window.open('payments_pdf2.php?cashpoint=' + cashpoint + '&shiftno=' + shiftno+ '&date1=' + date1+ '&date2=' + date2, "Payments Report",
                "menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
        }else if(reportid==='breakdown'){
            window.open('shift_breakdown_pdf.php?cashpoint=' + cashpoint + '&shiftno=' + shiftno, "Shift Breakdown Report",
                "menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
        }else if(reportid==='revenue'){
            window.open('revenue_breakdown_pdf.php?StartDate=' + startDate + '&EndDate=' + EndDate, "Revenue Breakdown Report",
                "menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
        }else if(reportid==='revenue'){
            window.open('revenue_breakdown_pdf2.php?StartDate=' + startDate + '&EndDate=' + EndDate, "Daily Cash Breakdown Report",
                "menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
        }
            
    }

    function printCollectionsPdf2(cashpoint, shiftno) {
        var reportid = document.getElementById('reportid').value;
        var date1 = document.getElementById('strDate1').value;
        var date2 = document.getElementById('strDate2').value;

        if (reportid === 'summary') {
            window.open('shift_report_Summary_pdf.php?cashpoint=' + cashpoint + '&shiftno=' + shiftno, "Summary Invoice",
                "menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
        } else if (reportid === 'collection') {
            window.open('collectionsReport_pdf.php?cashpoint=' + cashpoint + '&shiftno=' + shiftno + '&reportid=' + reportid + '&date1=' + date1 + '&date2=' + date2, "Summary Invoice",
                "menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
        } else if (reportid === 'summaryByItem') {
            window.open('shift_reportByItem_pdf.php?cashpoint=' + cashpoint + '&shiftno=' + shiftno, "Summary Invoice",
                "menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
        } else if (reportid === 'breakdown') {
            window.open('shift_breakdown_pdf.php?cashpoint=' + cashpoint + '&shiftno=' + shiftno, "Shift Breakdown Report",
                "menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
        } else if (reportid === 'payments') {
            window.open('payments_pdf.php?cashpoint=' + cashpoint + '&shiftno=' + shiftno + '&date1=' + date1 + '&date2=' + date2, "Payments Report",
                "menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
        } else if (reportid === 'payments2') {
            window.open('payments_pdf2.php?cashpoint=' + cashpoint + '&shiftno=' + shiftno + '&date1=' + date1 + '&date2=' + date2, "Payments Report",
                "menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
        }
    }
    var dhxWins, w1, grid;
    function getShiftNos(cashPoint) {
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
        grid.loadXML("Shift_find.php?cashpoint=" + cashPoint);
        grid.attachEvent("onRowSelect", doOnRowSelected);
        grid.attachEvent("onEnter", doOnEnter);
        grid.attachEvent("onRightClick", doOnRightClick);
        grid.attachEvent("onRightClick", doOnRightClick);
        grid.init();
        grid.enableSmartRendering(true);
    }

    function doOnRightClick(id, ind) {
        document.getElementById('shift_No').value = grid.cells(id, 1).getValue();
//        document.getElementById('Description').value=grid.cells(id,1).getValue();
//        document.getElementById('Amount').value=grid.cells(id,2).getValue();
        closeWindow();
    }
    function doOnRowSelected(id, ind) {
        document.getElementById('shift_No').value = grid.cells(id, 1).getValue();
//        document.getElementById('Description').value=grid.cells(id,1).getValue();
//        document.getElementById('Amount').value=grid.cells(id,2).getValue();

    }

    function doOnEnter(rowId, cellInd) {
        document.getElementById('shift_No').value = grid.cells(rowId, 1).getValue();
//        document.getElementById('Description').value=grid.cells(rowId,1).getValue();
//        document.getElementById('Amount').value=grid.cells(rowId,2).getValue();

        closeWindow();
    }
    function closeWindow() {
        dhxWins.window("w1").close();
    }


    var xmlhttp;

    function exportShiftReport(cashpoint, shiftNo){
        window.open('exportShiftReport.php?cashpoint=' + cashpoint + '&shiftno=' + shiftNo, "Cashiers Shift Report",
            "menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
    }

    function getShiftReport(cashpoint, shiftNo)
    {
       // alert('test');
        var reportid=document.getElementById('reportid').value;
        var date1=document.getElementById('strDate1').value;
        var date2=document.getElementById('strDate2').value;
        
        xmlhttp = GetXmlHttpObject();
        if (xmlhttp == null)
        {
            alert("Browser does not support HTTP Request");
            return;
        }
        var url = "getshiftreport.php";
        url = url + "?cashpoint=" + cashpoint;
        url = url + "&shiftNo=" + shiftNo;
        url = url + "&reportid="+reportid;
        url = url + "&date1="+date1;
        url = url + "&date2="+date2;
        url = url + "&sid=" + Math.random();
        xmlhttp.onreadystatechange = stateChanged;
        xmlhttp.open("GET", url, true);
        xmlhttp.send(null);
    }

    function stateChanged()
    {
        if (xmlhttp.readyState == 4)
        {
            document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
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

    function geoMsg(str) {
        alert('my name is ' + str);

    }



</script>



