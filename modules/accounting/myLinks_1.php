<!-- dhtmlxWindows -->

<link rel="stylesheet" type="text/css" href="<?php echo $root_path; ?>include/dhtmlxWindows/codebase/dhtmlxwindows.css">
<link rel="stylesheet" type="text/css" href="<?php echo $root_path; ?>include/dhtmlxWindows/codebase/skins/dhtmlxwindows_dhx_skyblue.css">
<!--<script src='<?php echo $root_path; ?>include/dhtmlxGrid/codebase/dhtmlxcommon_debug.js'></script>-->
<script src="<?php echo $root_path; ?>include/dhtmlxWindows/codebase/dhtmlxcommon.js"></script>
<script src="<?php echo $root_path; ?>include/dhtmlxWindows/codebase/dhtmlxwindows.js"></script>

<!-- Display menus-->
<link rel="stylesheet" type="text/css" href="<?php echo $root_path; ?>include/dhtmlxMenu/codebase/skins/dhtmlxmenu_dhx_skyblue.css">
<link rel="stylesheet" type="text/css" href="<?php echo $root_path; ?>include/dhtmlxMenu/codebase/skins/dhtmlxmenu_dhx_blue.css">
<link rel="stylesheet" type="text/css" href="<?php echo $root_path; ?>include/dhtmlxMenu/codebase/skins/dhtmlxmenu_glassy_blue.css">

<script  src="<?php echo $root_path; ?>include/dhtmlxMenu/codebase/dhtmlxcommon.js"></script>
<script  src="<?php echo $root_path; ?>include/dhtmlxMenu/codebase/dhtmlxmenu.js"></script>

<!-- dhtmlxGrid -->
<link rel='STYLESHEET' type='text/css' href='<?php echo $root_path; ?>include/dhtmlxGrid/codebase/dhtmlxgrid.css'>

<script src='<?php echo $root_path; ?>include/dhtmlxGrid/codebase/dhtmlxgrid.js'></script>
<script src='<?php echo $root_path; ?>include/dhtmlxGrid/codebase/ext/dhtmlxgrid_form.js'></script>
<script src='<?php echo $root_path; ?>include/dhtmlxGrid/codebase/ext/dhtmlxgrid_filter.js'></script>
<script src='<?php echo $root_path; ?>include/dhtmlxGrid/codebase/ext/dhtmlxgrid_srnd.js'></script>
<script src='<?php echo $root_path; ?>include/dhtmlxGrid/codebase/dhtmlxgridcell.js'></script>
<script src="<?php echo $root_path; ?>include/dhtmlxGrid/codebase/ext/dhtmlxgrid_drag.js"></script>

<script src="<?php echo $root_path; ?>include/dhtmlxWindows/codebase/dhtmlxcontainer.js"></script>

<script src='<?php echo $root_path; ?>include/dhtmlxDataProcessor/codebase/dhtmlxdataprocessor.js'></script>
<!--<script src='<?php echo $root_path; ?>include/dhtmlxdataprocessor/codebase/dhtmlxdataprocessor_debug.js'></script>

 dhtmlxCalendar -->
<link rel="STYLESHEET" type="text/css" href="<?php echo $root_path; ?>include/dhtmlxCalendar/codebase/dhtmlxcalendar.css">
<script src='<?php echo $root_path; ?>include/dhtmlxCalendar/codebase/dhtmlxcalendar.js'></script>
<script src='<?php echo $root_path; ?>include/dhtmlxCalendar/codebase/dhtmlxcommon.js'></script>
<script>window.dhx_globalImgPath="'<?php echo $root_path; ?>include/dhtmlxCalendar/codebase/imgs/";</script>

<!-- dhtmlxWindows -->
<link rel="stylesheet" type="text/css" href="<?php echo $root_path; ?>include/dhtmlxWindows/codebase/dhtmlxwindows.css">
<link rel="stylesheet" type="text/css" href="<?php echo $root_path; ?>include/dhtmlxWindows/codebase/skins/dhtmlxwindows_dhx_skyblue.css">
<script src="<?php echo $root_path; ?>include/dhtmlxWindows/codebase/dhtmlxcommon.js"></script>
<script src="<?php echo $root_path; ?>include/dhtmlxWindows/codebase/dhtmlxwindows.js"></script>

<!-- dhtmlxGrid -->


<script src='<?php echo $root_path; ?>include/dhtmlxConnector/codebase/connector.js'></script>

<style type="text/css" name="1">
    .pg1{
        border-top: solid;border-bottom: solid;border-left: solid;border-right: solid;
        width: 60%;background-color:#b0ccf2;
    }
    .adml{border-style: solid; border-left: solid; border-width:thin;}
    .adm2{border-style: solid; border-width:thin;}
    .tbl1{width: 60%}
    .pidRw{border-style: solid; border-bottom: solid;border-top: solid;background-color: #8FC4E8}
    .myMessage{font-size: large;color: #ffffff;background-color: #cc0033;font-weight: bold;
               width: 60%;text-decoration: blink;}
    .strWhite{background-color: #ffffff; font-style: normal;color: #cc0033; font: bold}
</style>
<?php

function getDebtorCat() {
    global $db;
    $sql = 'Select `code`,`name` from care_ke_debtorcat order by `code` asc';
    $request = $db->execute($sql);
    return $request;
}

function Finalize() {
    ?>
    <form name="finalize" method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">
        <table border="0" class="pg1">
            <thead>
                <tr>
                    <th></th>
                    <th></th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Finalize By </td>
                    <td>Date:<input type="radio" name="finalizeMode" value="date" />
                        Account NO:<input type="radio" name="finalizeMode" value="accNo" />
                        Category:<input type="radio" name="finalizeMode" value="DBCat" />
                        PID:<input type="radio" name="finalizeMode" value="pid" /></td>

                </tr>
                <tr>
                    <td>Date</td>
                    <td><input type="text" id="fdate" name="fdate" value="<?php echo date("d-m-Y") ?>" /></td>

                </tr>
                <tr>
                    <td>Account No</td>
                    <td><input type="text" name="accNo" id="accNo" value=""  ondblclick="getDebtors()"/>
                        <input type="text" name="accName" id="accName" value="" size="30"/></td>

                </tr>
                <tr>
                    <td>Finalize Accounts by Category</td>
                    <td>
                        <?php
                        $request = getDebtorCat();
                        echo '<select name="debtorType" id="debtorType"><option></option>';
                        while ($row = $request->FetchRow()) {
                            echo '<option value=' . $row['code'] . '>' . $row['name'] . '</option>';
                        }
                        echo '</select><br>';
                        ?>

                    </td>

                </tr>
                <tr>
                    <td>PID</td>
                    <td><input type="text" name="pid" id="pid" value=""  ondblclick="initPtsearch()" onblur="getPNames(this.value)"/>
                        <input type="text" name="pname" id="pname" value="" size="30"/></td>

                </tr>
                <tr>
                    <td>Bill Number</td>
                    <td><input type="text" name="billNumber" id="billNumber" value="" /></td>

                </tr>
                <tr>
                    <td colspan="2">Message to Appear on all Invoices</td>

                </tr>
                <tr>
                    <td colspan="2"><textarea name="invText" rows="4" cols="50">Payable as per the Agreement</textarea></td>
                </tr>
                <tr>

                    <td align="center" colspan="2"><input type="submit" name="submit" id="submit" value="Finalize" /></td>


                </tr>

            </tbody>
        </table>
    </form>

    <?php
// require_once 'gridfiles_1.php';
}


 function displayForm($formType){
              echo '<form name="debit" method="POST" action="'. $_SERVER['PHP_SELF'] . '">';
                echo '<table width=90% border="0" cellpadding="0" cellspacing="5">';
                 
                echo "<tr><td colspan='2' class=pgtitle>$formType</td></tr>";
//                echo ' <tr><td>Revenue Codes:</td>';
//                echo '<td colspan=3><input type="text" name="revcode" id="revcode" ondblclick="initRsearch()"/>
//                            </td></tr>';
                echo '<tr><td>Patient No:</td><td>
                <input type="text" size="10" name="pid" id="pid" ondblclick="initPtsearch()" onblur="getPatient(this.value)"/>';
                echo '<input type="text" name="pname" id="pname" size="36"/>
                        <input type="button" id="search" value="search" onclick="initPtsearch()"/></td></tr>';
                echo '<tr><td>ref No:</td>';
                echo '<td><input type="text" name="receiptNo" id="receiptNo" onclick("getNewReceipt()")/>
                    en_nr:<input type="text" name="en_nr" id="en_nr" size=5/>
                    ward_nr<input type="text" name="ward_nr" id="ward_nr" size=5"/>
                    ward_name<input type="text" name="ward_name" id="ward_name" size=10/>
                    <input type="hidden" name="room_nr" id="room_nr" size=5/>
                    bed_nr<input type="text" name="bed_nr" id="bed_nr" size=5/></td></tr>';
                echo '<tr><td>date:</td>';
                echo '<td><input type="text" name="calInput" id="calInput" value="'.date("d-m-Y").'"/></td></tr>';
                echo '<tr><td colspan=2>
                           <div id="gridbox" height="200px" style="background-color:white;"></div>
                       </td></tr>';
                echo '<tr><td align=right>Total:<input type="text" size="15" name="total" id="total" /></td></tr>';
                echo '<tr><td></td><td align=center>';
                echo '<input type="submit" name="submit" id="submit" value="save" />&nbsp&nbsp';
                echo '<input type="button" name="cancel" id="cancel" value="cancel" /></td></tr>';
                
                echo '</table>';
                echo '</form>';
                echo '<button onclick=addRows(1)>Add Row</button>
                    <button onclick=deleteRow()>Delete</button>
                    <button onclick=initKSearch()>Get Products List</button>';
                 require_once 'gridfiles_1.php';

  }
?>


<script>
        var finalise=true;
function initPtsearch(){
        dhxWins = new dhtmlXWindows();
        //dhxWins.setSkin("modern_red");
        //dhxWins.enableAutoViewport(false);
        //dhxWins.setViewport(50, 50, 700, 400);
        //dhxWins.vp.style.border = "#909090 1px solid";
        dhxWins.setImagePath("<?php echo $root_path; ?>include/dhtmlxGrid/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 462, 200, 430, 250);
        w1.setText("Search Patient");

        grid = w1.attachGrid();
        grid.setImagePath("<?php echo $root_path; ?>include/dhtmlxGrid/codebase/imgs/");
        grid.setHeader("Patient ID,first Name,Surname,Last name,en_nr");
        grid.attachHeader("#connector_text_filter,#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ro,ro,ro");
        grid.setInitWidths("80,80,80,80,80");
        grid.loadXML("searchIP.php");
        grid.attachEvent("onRowSelect",doOnRowSelected3);
        grid.attachEvent("onEnter",doOnEnter3);
        //grid.attachEvent("onRightClick",doonRightClick);
        grid.init();
        grid.enableSmartRendering(true);

    }
    
    function getDebtors(){
        dhxWins = new dhtmlXWindows();
        //dhxWins.setSkin("modern_red");
        //dhxWins.enableAutoViewport(false);
        //dhxWins.setViewport(50, 50, 700, 400);
        //dhxWins.vp.style.border = "#909090 1px solid";
        dhxWins.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");

        w1 = dhxWins.createWindow("w1", 600, 220, 500, 320);
        w1.setText("Debtors Codes");

        grid = w1.attachGrid();
        grid.setImagePath("../../include/dhtmlxGrid/codebase/imgs/");
        grid.setHeader("accno,name,category,os_bal,last_trans");
        grid.attachHeader("#connector_text_filter,#connector_text_filter");
        grid.setSkin("light");
        grid.setColTypes("ro,ro,ro,ro,ro");
        grid.setInitWidths("80,180,60,80,80");
        grid.loadXML("debtor_conn.php");
        grid.attachEvent("onRowSelect",doOnRowSelected5);
        grid.attachEvent("onEnter",doOnEnter4);
        //grid.attachEvent("onRightClick",doonRightClick);
        grid.init();
        grid.enableSmartRendering(true);
    }

    function doOnRowSelected5(id,ind){
        str= grid.cells(id,0).getValue();
        str2=grid.cells(id,1).getValue();
        document.getElementById('accNo').value=str
        document.getElementById('accName').value=str2
    }
    
    function doOnEnter4(rowId,cellInd){

        closeWindow();
    }

 function doOnEnter2(rowId,cellInd){
        //        var qty=grid.cells(grid.getSelectedId(),1).getValue();
        //         protocolIt("User pressed Enter on row with id "+rowId+" and cell index "+cellInd +"["+rev+"]");
        alert(grid.cells2(rowId,cellInd).getValue());
        //
    }

    function doOnRowSelected3(id,ind){
        names=grid.cells(id,1).getValue()+" "+grid.cells(id,3).getValue()+" "+grid.cells(id,2).getValue()
        document.getElementById('pid').value=grid.cells(id,0).getValue();
        document.getElementById('pname').value=grid.cells(id,1).getValue();
//        document.getElementById('en_nr').value=grid.cells(id,4).getValue();
//        sendRequestPost(grid.cells(id,4).getValue());
    }
    
    function getPNames(str)
    {
        xmlhttp10=GetXmlHttpObject();
        if (xmlhttp10==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="getDesc.php?pid="+str;
        url=url+"&sid="+Math.random();
        url=url+"&callerID=debit";
        xmlhttp10.onreadystatechange=stateChanged10;
        xmlhttp10.open("POST",url,true);
        xmlhttp10.send(null);

    }

    function stateChanged10()
    {
        //get payment description
        if (xmlhttp10.readyState==4)//show point desc
        {
            var str=xmlhttp10.responseText;
             str2=str.split(",");
            document.getElementById('pname').value=str2[0]+' '+str2[1]+' '+str2[2];

//            document.csale.encounter_class_nr.value=str2[4];
            getBillNumbers(document.getElementById("pid").value);
            //countGridRecords(document.csale.patientId.value);
        }
    }
    
    function getBillNumbers(str){
         xmlhttp10=GetXmlHttpObject();
        if (xmlhttp10==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="getDesc.php?pid="+str;
        url=url+"&sid="+Math.random();
        url=url+"&callerID=getBillNumbers";
        xmlhttp10.onreadystatechange=stateChangedPid;
        xmlhttp10.open("POST",url,true);
        xmlhttp10.send(null);
    }
    
    function stateChangedPid()
    {
        //get payment description
        if (xmlhttp10.readyState==4)//show point desc
        {
            var str=xmlhttp10.responseText;
    
            document.getElementById('billNumbers').innerHTML=str;
            

        }
    }
    var finalise=true;
    
    function getAmounts(){
         xmlhttp10=GetXmlHttpObject();
        if (xmlhttp10==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var pid=document.getElementById("pid").value;
        var billNumbers = document.bills.billNumbers.value;
        
        var url="getDesc.php?pid="+pid;
        url=url+"&billNumber="+billNumbers;
        url=url+"&sid="+Math.random();
        url=url+"&callerID=Amounts";
        xmlhttp10.onreadystatechange=stateChangedAmounts;
        xmlhttp10.open("POST",url,true);
        xmlhttp10.send(null);
        
       
    }
    
    function stateChangedAmounts()
    {
        //get payment description
        if (xmlhttp10.readyState==4)//show point desc
        {
            var str=xmlhttp10.responseText;
             str2=str.split(",");
           if(finalise){
            document.getElementById('invAmount').innerHTML='Invoice Amount='+str2[0];
            document.getElementById('invNHIF').innerHTML='NHIF='+str2[1];
            document.getElementById('invPayment').innerHTML='Total Payment='+str2[2];
            document.getElementById('invBalance').innerHTML='Balance='+str2[3];
        }else{
            return;
        }

        }
    }
    
    function finalizeInvoice(){
         xmlhttp10=GetXmlHttpObject();
        if (xmlhttp10==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var pid=document.getElementById("pid").value;
//        var billNumbers=document.getElementById("billNumbers").value;
        var billNumbers = document.bills.billNumbers.value;
        var fdate=document.getElementById("fdate").value;
        
        var url="getDesc.php?pid="+pid;
        url=url+"&billNumber="+billNumbers;
        url=url+"&fdate="+fdate;
        url=url+"&sid="+Math.random();
        url=url+"&callerID=finalize";
        xmlhttp10.onreadystatechange=stateChangedFinalize;
        xmlhttp10.open("POST",url,true);
        xmlhttp10.send(null);
    }
    
    function stateChangedFinalize()
    {
        //get payment description
        if (xmlhttp10.readyState==4)//show point desc
        {
            var str=xmlhttp10.responseText;
    
            document.getElementById('finalizeResults').innerHTML=str;

        }
    }
</script>
