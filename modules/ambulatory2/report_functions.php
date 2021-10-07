<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function getDebtorCat() {
    global $db;
    $sql = 'Select `code`,`name` from care_ke_debtorcat order by `code` asc';
    $request = $db->execute($sql);
    return $request;
}

function getDept() {
    global $db;
    $sql = 'Select st_id,st_name from care_ke_stlocation order by st_name asc';
    $request = $db->execute($sql);
    return $request;
}

function getStatement() {
    global $db;
    ?>
    <table class="tbl1" border=0 width="80%">
        <tr>
            <td>Debtor Category</td>
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
            <td> From Acc No: </td>
            <td><input type="text" id="acc1" name="acc1" value="" ondblclick="getDebtors()"></td>
        <tr>
            <td>From Date:</td>
            <td id="datefield"></td>
            <td>To Acc No: </td>
            <td><input type="text" id="acc2" name="acc2" value=""></td>
        </tr>
        <tr>
            <td>From Date:</td>
            <td id="datefield2"></td>
            <td> </td>
            <td></td>
        </tr>
        <tr>
            <td><button id="preview" onclick="getStatement(document.getElementById('acc1').value)">Preview Report</button></td>
            <td><button id="preview" onclick="exportExcel(document.getElementById('acc1').value,
                document.getElementById('strDate1').value,document.getElementById('strDate2').value)">Export to Excel</button></td> 
            <td><button id="preview" onclick="printStatement(document.getElementById('acc1').value,
                document.getElementById('strDate1').value,document.getElementById('strDate2').value)">Print Report</button></td>

            <td></td>
        </tr>
    </table>
    <div id='loading' style='display: none'><img src="../ajax-loader.gif" title="Loading" /></div>
    <?php
}

function getHeaders() {
    
}

function getDebtorInvoice($title) {
    ?>
    <table class="tbl1" border=0 width="80%">

        <tbody>
            <tr>
                <td>Issue Status</td>
                <td><select name="issueStatus" id="issueStatus">
                        <option></option>
                        <option>Pending</option>
                        <option>Issued </option>
                    </select>
                </td>
                <td>Start Date</td>
                <td id="datefield"></td>
            </tr>
            <tr>
                <td>Location</td>
                <td><?php
    $request = getDept();
    echo '<select id="st_id2" name="st_id2"><option></option>';
    while ($row = $request->FetchRow()) {
        echo '<option value=' . $row['st_id'] . '>' . $row['st_name'] . '</option>';
    }
    echo '</select><br>';
    ?></td>
                <td>End Date</td>
                <td id="datefield2"></td>
            </tr>
            <tr>
                <td>From Pid</td>
                <td><input type="text" id="pid1" name="pid1" value="" /></td>
                <td>From Issue No</td>
                <td><input type="text" id="issue1" name="issue1" value="" /></td>
            </tr>
            <tr>
                <td>To Pid</td>
                <td><input type="text" id="pid2" name="pid2" value="" /></td>
                <td>To Issue</td>
                <td><input type="text" id="issue2" name="issue2" value="" /></td>
            </tr>
            <tr>
                <td><button id="preview" onclick="getIssues(document.getElementById('issueStatus').value)">Preview Report</button></td>
                <td></td>
                <td></td>
                <td><button id="preview" onclick="getIssues(document.getElementById('issueStatus').value)">Print Report</button></td>
            </tr>
        </tbody>
    </table>
    <?php
}

function getAgedBalance($title) {
    ?>
    <table class="tbl1" border=0 width="80%">

        <tbody>
            <tr>
                <td>Order Statush</td>
                <td><select name="orderStatus" id="orderStatus">
                        <option></option>
                        <option>Pending</option>
                        <option>Serviced</option>
                    </select>
                </td>
                <td>Start Date</td>
                <td id="datefield"></td>
            </tr>
            <tr>
                <td>Location</td>
                <td><?php
    $request = getDept();
    echo '<select id="st_id" name="st_id" onchange="getOrders(this.value)">
                        <option></option>';
    while ($row = $request->FetchRow()) {
        echo '<option value=' . $row['st_id'] . '>' . $row['st_name'] . '</option>';
    }
    echo '</select><br>';
    ?></td>
                <td>End Date</td>
                <td id="datefield2"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><button id="preview" onclick="getOrders(document.getElementById('orderStatus').value)">Preview Report</button></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <?php

    

}
?>


<script>
    function getInvoice(name,bill_number,dt1,dt2){
        //alert("Hellp");
        window.open('reports/detail_invoice_pdf.php?pid='+name+'&billNumber='+bill_number ,"Summary Invoice","menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
    }
    function getLevels(str){
        xmlhttp=GetXmlHttpObject();
        if (xmlhttp==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        
        var url="getReportsData.php?catID="+str;
        url=url+"&sid="+Math.random();
        url=url+"&task=getLevels";
         url=url+"&date1="+dt1;
          url=url+"&date2="+dt2;
        xmlhttp.onreadystatechange=stateChanged;
        xmlhttp.open("POST",url,true);
        xmlhttp.send(null);
    }

    function stateChanged()
    {
        //get payment description
        if (xmlhttp.readyState==4)
        {
            //            var str=xmlhttp.responseText;
            //str2=str.search(/,/)+1;
            document.getElementById("myContent").innerHTML=xmlhttp.responseText;
        }
    }
    
    
    function getOrders(str){
        xmlhttp=GetXmlHttpObject();
        if (xmlhttp==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        strLoc=document.getElementById('st_id').value;
        strdt1=document.getElementById('strDate1').value;
        strdt2=document.getElementById('strDate2').value;

        
        var url="getReportsData.php?ordStatus="+str;
        url=url+"&ordLoc="+strLoc;
        url=url+"&orddt1="+strdt1+"&orddt2="+strdt2;
        url=url+"&sid="+Math.random();
        url=url+"&task=getOrders";
        xmlhttp.onreadystatechange=stateChanged2;
        xmlhttp.open("POST",url,true);
        xmlhttp.send(null);
    }

    function stateChanged2()
    {
        //get payment description
        if (xmlhttp.readyState==4)
        {
            //            var str=xmlhttp.responseText;
            //str2=str.search(/,/)+1;
            document.getElementById("myContent").innerHTML=xmlhttp.responseText;
        }
    }
    
    function getStatement(str){
        xmlhttp=GetXmlHttpObject();
        if (xmlhttp==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        
        var date1=document.getElementById('strDate1').value;
        var date2=document.getElementById('strDate2').value;
        //Before starting ajax request display the div (paste it in  the beginning of your ajax request function

           
        var url="getReportsData.php?acc1="+str;
        url=url+"&sid="+Math.random();
        url=url+"&task=getStatement";
        url=url+"&date1="+date1;
        url=url+"&date2="+date2;
        
        show_progressbar('myContent');
        xmlhttp.onreadystatechange=stateChanged3;
        xmlhttp.open("POST",url,true);
        xmlhttp.send(null);
        
      
    }

    function show_progressbar(id) {
        document.getElementById('myContent').innerHTML='<img src="../ajax-loader2.gif" border="0" alt="Loading, please wait..." />';
    }
    function stateChanged3()
    {
        if (xmlhttp.readyState==4)
        {
     
        
            document.getElementById("myContent").innerHTML=xmlhttp.responseText;
            //execute the code below after the ajax request is complete
      
        }
    }
    
    
    function exportExcel(str,date1,date2){
        window.open('exportStatement.php?acc1='+str+'&date1='+date1+'&date2='+date2+'&rptType=statement'
        ,"Reports","menubar=yes,toolbar=yes,width=500,height=550,location=yes,resizable=no,scrollbars=yes,status=yes");

    }
    
    
    function printStatement(str,strDate1,strDate2){

        window.open('reports/statement_pdf.php?acc1='+str+'&strDate1='+strDate1+'&strDate2='+strDate2+'&rptType=statement'
        ,"Reports","menubar=yes,toolbar=yes,width=500,height=550,location=yes,resizable=no,scrollbars=yes,status=yes");

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
        str2=grid.cells(id,0).getValue();
        document.getElementById('acc1').value=str;
        document.getElementById('acc2').value=str2
        document.getElementById('accName').value=str2
    }
    
    function doOnEnter4(rowId,cellInd){

        closeWindow();
    }

</script>