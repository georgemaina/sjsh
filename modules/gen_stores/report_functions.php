
<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function getStockCat() {
    global $db;
    $sql = 'Select catid,item_cat from care_tz_itemscat order by item_cat asc';
    $request = $db->execute($sql);
    return $request;
}

function getDept() {
    global $db;
    $sql = 'Select st_id,st_name from care_ke_stlocation  order by st_name asc';
    $request = $db->execute($sql);
    return $request;
}

function getUsers() {
    global $db;
    $sql = "select distinct input_user from care_ke_internalreq";
    $request = $db->execute($sql);
    return $request;
}

function getlevels($title) {
    getQueryPop($title);
}

function getHeaders() {
    
}

function getOrders($title) {
    getQueryPop($title);
}

function getIssues($title) {
    getQueryPop($title);
}

function getAdjustments($title) {
    getQueryPop($title);
}

function getStockValuation($title) {
    getQueryPop($title);
}

function getStores() {
   global $db;

    $sql="Select st_id,st_name from care_ke_stlocation where Dispensing=1";
    $results=$db->Execute($sql);
    return $results;
}

function getPatientStatement() {
    global $db;

    $date1 = $_REQUEST[date1];
    $date2 = $_REQUEST[date2];
    $pid = $_REQUEST[pid];

    $sql = "SELECT p.pid,p.name_first,p.name_last,p.name_2,b.bill_date,b.`IP-OP`,b.partcode,b.service_type,b.Description,
  b.price,b.total AS Total,b.qty AS drug_Count FROM care_ke_billing b LEFT JOIN care_person p ON (b.pid = p.pid)
  WHERE b.service_type = 'drug_list' ";

    if (isset($pid) && $pid <> '') {
        $sql.=" and b.pid=$pid";
    }

    if (isset($date1) && isset($date2) && $date1 <> "" && $date1 <> "") {
        $date = new DateTime($date1);
        $dt1 = $date->format("Y-m-d");

        $date = new DateTime($date2);
        $dt2 = $date->format("Y-m-d");

        $sql = $sql . " and b.bill_date between '$dt1' and '$dt2' ";
    } else {
        $sql = $sql . " and b.bill_date<=now()";
    }

    $sql = $sql . " order by b.bill_date desc";
    echo $sql;
    //p.pid,p.name_first,p.name_last,p.name_2,b.bill_date,b.bill_number,b.total

    if ($request = $db->Execute($sql)) {

        $row1 = $request->FetchRow();
        echo '<table width=100% height=14>
        <tr><td colspan=6><br></td></tr>     
        <tr><td align="left"><b>PID:</b></td> <td>' . $row1[pid] . '</td></tr>
        <tr><td align="left"><b>Names:</b></td><td>' . $row1[name_first] . ' ' . $row1[name_last] . ' ' . $row1[name_2] . '</td></tr>
        <tr bgcolor=#6699cc>
                    <td align="left">Date</td>
                    <td align="left">Admission</td>
                    <td align="left">Lab Code</td>
                    <td align="left">Description</td>
                    <td align="left">Qty</td>
                    <td align="left">Price</td>
                    <td align="left">Total</td>
                    <td align="left">running</td>
                 </tr>';
        $bg = '';
//        $total='';
        $lsum = 0;
        while ($row = $request->FetchRow()) {
            if ($bg == "silver")
                $bg = "white";
            else
                $bg = "silver";
            if ($row[`IP-OP`] == 1) {
                $enc_class = 'IP';
            } else {
                $enc_class = 'OP';
            }
            $lsum = intval($lsum + $row[Total]);
            echo '<tr bgcolor=' . $bg . ' height=16>
                
                    <td>' . $row[bill_date] . '</td>  
                    <td>' . $enc_class . '</td>    
                    <td>' . $row[partcode] . '</td>    
                    <td>' . $row[Description] . '</td>    
                    <td>' . $row[drug_Count] . '</td> 
                     <td>' . $row[price] . '</td> 
                    <td align=right>' . number_format($row[Total], 2) . '</td>  
                     <td align=right>' . number_format($lsum, 2) . '</td>
             </tr>';

            $rowbg = 'white';
        }
        $rowCnt = $request->RecordCount();

        echo "<tr><td colspan=6><br>No of Tests $rowCnt</td><td align=right><b>Total Amount " . number_format($lsum, 2) . "<b></td></tr>";
        echo '</table>';
    } else {
        echo 'SQL: Failed=' . $sql;
    }
}

function getQueryPop($title) {
    if ($title == "levels") {
        ?>
        <table class="tbl1" border=0 width="80%">
            <tr>
                <td>From Stock Cat:</td>
                <td>
                    <?php
                    $request = getStockCat();
                    echo '<select name="cat1" id="cat1"><option></option>';
                    while ($row = $request->FetchRow()) {
                        echo '<option value=' . $row['catid'] . '>' . $row['item_cat'] . '</option>';
                    }
                    echo '</select><br>';
                    ?>
                </td>
                <td> From Item Id: </td>
                <td><input type="text" id="itemid1" name="itemid1" value=""></td>
            <tr>
                <td>To Stock Cat:</td>
                <td>
                    <?php
                    $request2 = getStockCat();
                    echo '<select name="cat2" ><option></option>';
                    while ($row = $request2->FetchRow()) {
                        echo '<option value=' . $row['catid'] . '>' . $row['item_cat'] . '</option>';
                    }
                    echo '</select>'
                    ?>

                </td>
                <td>
                    To Item Id: 
                </td>
                <td><input type="text" id="itemid1" name="itemid1" value=""></td>
            </tr>
            <tr>
                <td>Store:</td>
                <td>
                    <?php
                    $request2 = getStores();
                    echo '<select name="storeId" id="storeId"><option></option>';
                    while ($row = $request2->FetchRow()) {
                        echo '<option value=' . $row['st_id'] . '>' . $row['st_name'] . '</option>';
                    }
                    echo '</select>'
                    ?>

                </td>
                <td>Levels </td>
                <td><select name="qtyFilter" id="qtyFilter">
                        <option></option>
                        <option value="neg">Negative</option>
                        <option value="zero">Zero</option>
                        <option value="pos">Above zero</option>
                        <option value="reorder">Below Reorder Level</option>
                        <option value="neg">Above Reorder Leve</option>
                    </select></td>
            </tr>
            <tr>
                <td></td>
                <td><button id="preview" onclick="getLevels()">Preview</button></td>
                <td><button id="print" onclick="printLevels()">Print</button></td>
                <td></td>
            </tr>
        </table>

        <?php
    } elseif ($title == "issues") {
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
        $request = getStores();
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
                    <td><button id="Export"  onclick="exportIssues(document.getElementById('issueStatus').value,
                        document.getElementById('strDate1').value,document.getElementById('strDate2').value)">Export Report</button></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>


        <?php
     } elseif ($title == "drgstatement") {
    ?>
    <table class="tbl1" border=0 width="80%">
        <thead class='title'>
        <tr>
            <th colspan="4"> Stock Adjustments</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Start Date</td>
            <td id="datefield"></td>
            <td>Item Number</td>
            <td><input type="text" id="itemID" name="itemID"></td>
        </tr>
        <tr>
            <td>End Date</td>
            <td id="datefield2"></td>
            <td>Location</td>
            <td><?php
                $request = getDept();
                echo '<select id="st_id2" name="st_id2"><option></option>';
                while ($row = $request->FetchRow()) {
                    echo '<option value=' . $row['st_id'] . '>' . $row['st_name'] . '</option>';
                }
                echo '</select><br>';
                ?></td>
        </tr>
        <tr>
            <td><button id="preview" onclick="getAdjustments(document.getElementById('itemID').value)">Preview Report</button></td>
            <td><button id="Export"  onclick="exportAdjustments(document.getElementById('itemID').value)">Export Report</button></td>
            <td></td>
            <td></td>
        </tr>
        </tbody>
    </table>


<?php
}elseif ($title == "valuation") {
    ?>
    <table class="tbl1" border=0 width="80%">
        <thead class='title'>
        <tr>
            <th colspan="4"> Stock Valuation Report</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>From Inventory Category Code</td>
            <td> <?php
                $request = getStockCat();
                echo '<select name="cat1" id="cat1"><option></option>';
                while ($row = $request->FetchRow()) {
                    echo '<option value=' . $row['catid'] . '>' . $row['item_cat'] . '</option>';
                }
                echo '</select><br>';
                ?></td>
            <td>Location</td>
            <td> <?php
                $request2 = getStores();
                echo '<select name="storeId" id="storeId"><option></option>';
                while ($row = $request2->FetchRow()) {
                    echo '<option value=' . $row['st_id'] . '>' . $row['st_name'] . '</option>';
                }
                echo '</select>'
                ?></td>
        </tr>
        <tr>
            <td>To Inventory Category Code</td>
            <td> <?php
                $request = getStockCat();
                echo '<select name="cat2" id="cat2"><option></option>';
                while ($row = $request->FetchRow()) {
                    echo '<option value=' . $row['catid'] . '>' . $row['item_cat'] . '</option>';
                }
                echo '</select><br>';
                ?></td>
            <td>Detailed or Summary</td>
            <td><select id="detsum" name="detsum">
                    <option value='summary'>Summary Report</option>
                    <option value='Detailed'>Detailed Report</option>
                </select><br>
                </td>
        </tr>
        <tr>
            <td><button id="preview" onclick="getValuation()">Preview Report</button></td>
            <td><button id="Print"  onclick="printValuation()">Print Report</button></td>
            <td><button id="Export"  onclick="exportValuation()">Export Report</button></td>
            <td></td>
        </tr>
        </tbody>
    </table>


<?php
}else {
        ?>
        <table class="tbl1" border=0 width="80%">

            <tbody>
                <tr>
                    <td>Order Status</td>
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
                    <td>Ordered From</td>
                    <td><?php
        $request = getDept();
        echo '<select id="st_id" name="st_id">
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
                    <td>Ordered By</td>
                    <td>
                        <?php
                        $request2 = getUsers();
                        echo '<select id="inputUsers" name="inputUsers">
                        <option></option>';
                        while ($row = $request2->FetchRow()) {
                            echo '<option value=' . $row['input_user'] . '>' . $row['input_user'] . '</option>';
                        }
                        echo '</select><br>';
                        ?>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td><button id="preview" onclick="getOrders(document.getElementById('orderStatus').value)">Preview Order</button></td>
                    <td><button id="printOrder" onclick="getOrdersPdf(document.getElementById('orderStatus').value)">Print Order</button></td>
                    <td><button id="ExportOrder" onclick="exportOrders(document.getElementById('orderStatus').value)">Export Order</button></td>
                    <td></td>
                </tr>
            </tbody>
        </table>



        <?php
    }
}
?>


<script>
    function exportIssues(str,date1,date2){
        window.open('exportIssues.php?issueStat='+str+'&date1='+date1+'&date2='+date2+'&rptType=Issues'
        ,"Reports","menubar=yes,toolbar=yes,width=500,height=550,location=yes,resizable=no,scrollbars=yes,status=yes");

    }
    
    function exportOrders(str){
        strLoc=document.getElementById('st_id').value;
        strdt1=document.getElementById('strDate1').value;
        strdt2=document.getElementById('strDate2').value;
        inputUser=document.getElementById('inputUsers').value;

        window.open('exportOrders.php?ordStatus='+str+'&$inputUser='+inputUser+'&orddt1='+strdt1
            +'&orddt2='+strdt2+'&rptType=Issues&ordLoc='+strLoc
        ,"Reports","menubar=yes,toolbar=yes,width=500,height=550,location=yes,resizable=no,scrollbars=yes,status=yes");

    }

    function printLevels(){
        var catID=document.getElementById('cat1').value;
        var storeid=document.getElementById('storeId').value;
        var qtyFilter=document.getElementById('qtyFilter').value;

        window.open('stockQuantities_pdf.php?catID='+catID+'&storeid='+storeid+'&qtyFilter='+qtyFilter
            ,"Reports","menubar=yes,toolbar=yes,width=500,height=550,location=yes,resizable=no,scrollbars=yes,status=yes");

    }

    function printValuation(){
        var cat2=document.getElementById('cat1').value;
        var cat2=document.getElementById('cat2').value;
        var storeid=document.getElementById('storeId').value;
        var detsum=document.getElementById('detsum').value;

        window.open('StockValuationPDF.php?catID1='+cat1+'&catID2='+cat2+'&storeid='+storeid
            ,"Reports","menubar=yes,toolbar=yes,width=500,height=550,location=yes,resizable=no,scrollbars=yes,status=yes");

    }

    function exportValuation(){
        var cat2=document.getElementById('cat1').value;
        var cat2=document.getElementById('cat2').value;
        var storeid=document.getElementById('storeId').value;
        var detsum=document.getElementById('detsum').value;

        window.open('exportValuation.php?catID1='+cat1+'&catID2='+cat2+'&storeid='+storeid
            ,"Reports","menubar=yes,toolbar=yes,width=500,height=550,location=yes,resizable=no,scrollbars=yes,status=yes");

    }


    function getValuation(){
//        alert('test');
        var cat2=document.getElementById('cat1').value;
        var cat2=document.getElementById('cat2').value;
        var storeid=document.getElementById('storeId').value;
        var detsum=document.getElementById('detsum').value;

        xmlhttp=GetXmlHttpObject();
        if (xmlhttp==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }

        var url="getReportsData.php?catID1="+cat1;
        url=url+"&sid="+Math.random();
        url=url+"&task=getValuation";
        url=url+"&catID2="+cat2;
        url=url+"&storeid="+storeid;
        url=url+"&detsum="+detsum;
        xmlhttp.onreadystatechange=valuationStateChange;
        xmlhttp.open("POST",url,true);
        xmlhttp.send(null);
    }


    function valuationStateChange(){
        //get payment description
        if (xmlhttp.readyState==4)
        {
            //            var str=xmlhttp.responseText;
            //str2=str.search(/,/)+1;
            document.getElementById("myContent").innerHTML=xmlhttp.responseText;
        }
    }

    function getLevels(){
       // alert('test');
        var cat=document.getElementById('cat1').value;
        var storeid=document.getElementById('storeId').value;
        var qtyFilter=document.getElementById('qtyFilter').value;
        xmlhttp=GetXmlHttpObject();
        if (xmlhttp==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        
        var url="getReportsData.php?catID="+cat;
        url=url+"&sid="+Math.random();
        url=url+"&task=getLevels";
        url=url+"&storeid="+storeid;
        url=url+"&qtyFilter="+qtyFilter;
        xmlhttp.onreadystatechange=stateChanged;;
        xmlhttp.open("POST",url,true);
        xmlhttp.send(null);
    }

    function stateChanged(){
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
        inputUser=document.getElementById('inputUsers').value;

        
        var url="getReportsData.php?ordStatus="+str;
        url=url+"&ordLoc="+strLoc;
        url=url+"&orddt1="+strdt1+"&orddt2="+strdt2;
        url=url+"&inputUser="+inputUser;
        url=url+"&sid="+Math.random();
        url=url+"&task=getOrders";
        xmlhttp.onreadystatechange=stateChanged2;
        xmlhttp.open("POST",url,true);
        xmlhttp.send(null);
    }

    function getOrdersPdf(str){
        strLoc=document.getElementById('orderStatus').value;
        strLoc=document.getElementById('st_id').value;
        strdt1=document.getElementById('strDate1').value;
        strdt2=document.getElementById('strDate2').value;
        inputUser=document.getElementById('inputUsers').value;
        
        window.open('internalOrders_PDF.php?ordStatus='+str+'&$inputUser='+inputUser+'&orddt1='+strdt1
            +'&orddt2='+strdt2+'&rptType=Issues&ordLoc='+strLoc
        ,"Orders Reports","menubar=yes,toolbar=yes,width=500,height=550,location=yes,resizable=no,scrollbars=yes,status=yes");
    }

    function stateChanged2() {
        //get payment description
        if (xmlhttp.readyState==4)
        {
            //            var str=xmlhttp.responseText;
            //str2=str.search(/,/)+1;
            document.getElementById("myContent").innerHTML=xmlhttp.responseText;
        }
    }
    
    function getIssues(str){
        xmlhttp=GetXmlHttpObject();
        if (xmlhttp==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        strLoc2=document.getElementById('st_id2').value;
        strdt1=document.getElementById('strDate1').value;
        strdt2=document.getElementById('strDate2').value;        
        pid1=document.getElementById('pid1').value;
        pid2=document.getElementById('pid2').value;
        issue1=document.getElementById('issue1').value;
        issue2=document.getElementById('issue2').value;
        
        
        var url="getReportsData.php?rstatus="+str;
        url=url+"&strLoc2="+strLoc2;
        url=url+"&orddt1="+strdt1;
        url=url+"&orddt2="+strdt2;
        url=url+"&pid1="+pid1+"&pid2="+pid2;
        url=url+"&issue1="+issue1+"&issue2="+issue2;
        url=url+"&sid="+Math.random();
        url=url+"&task=getIssues";
        xmlhttp.onreadystatechange=stateChanged3;
        xmlhttp.open("POST",url,true);
        xmlhttp.send(null);
    }

    function stateChanged3() {
        //get payment description
        if (xmlhttp.readyState==4)
        {
            //            var str=xmlhttp.responseText;
            //str2=str.search(/,/)+1;
            document.getElementById("myContent").innerHTML=xmlhttp.responseText;
        }
    }

    function getAdjustments(str){
        xmlhttp=GetXmlHttpObject();
        if (xmlhttp==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        strLoc=document.getElementById('st_id2').value;
        strdt1=document.getElementById('strDate1').value;
        strdt2=document.getElementById('strDate2').value;
        itemID=document.getElementById('itemID').value;

        
        var url="getReportsData.php?adjustments="+str;
        url=url+"&ordLoc="+strLoc;
        url=url+"&orddt1="+strdt1+"&orddt2="+strdt2;
        url=url+"&itemID="+itemID;
        url=url+"&sid="+Math.random();
        url=url+"&task=getAdjustments";
        xmlhttp.onreadystatechange=stateChanged4;
        xmlhttp.open("POST",url,true);
        xmlhttp.send(null);
    }

    function stateChanged4() {
        //get payment description
        if (xmlhttp.readyState==4)
        {
            //            var str=xmlhttp.responseText;
            //str2=str.search(/,/)+1;
            document.getElementById("myContent").innerHTML=xmlhttp.responseText;
        }
    }

    function GetXmlHttpObject(){
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