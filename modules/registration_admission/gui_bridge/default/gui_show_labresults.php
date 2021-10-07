<script  language="javascript">
    function popNotes(d) {
        alert(d);
    }

    function closeLabTest(encounterNr){
//       alert(batchNo);

        xmlhttp3=GetXmlHttpObject();
        if (xmlhttp3==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="register_functions.php?task=closeLabTest";
        url=url+"&sid="+Math.random();
        url=url+"&encNo="+encounterNr;
        xmlhttp3.onreadystatechange=stateCloseTest;
        xmlhttp3.open("POST",url,true);
        xmlhttp3.send(null);
    }

    function stateCloseTest()
    {
        //get payment description
        if (xmlhttp3.readyState==4)//show point desc
        {
            var str=xmlhttp3.responseText;
            if(str=='success')
                document.getElementById('testStatus').innerHTML="UNABLE TO CLOSE TEST: "+str;
            }else{
                document.getElementById('testStatus').innerHTML="TEST IS CLOSED";
        }
    }


    function getTestsHistory(){
   
        xmlhttp3=GetXmlHttpObject();
        if (xmlhttp3==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="register_functions.php?task=getTestsHistory";
        url=url+"&sid="+Math.random();
        xmlhttp3.onreadystatechange=stateTestHistory;
        xmlhttp3.open("POST",url,true);
        xmlhttp3.send(null);
     
    }

    function stateTestHistory()
    {
        //get payment description
        if (xmlhttp3.readyState==4)//show point desc
        {
            var str=xmlhttp3.responseText;
            document.getElementById('history').innerHTML=str //.split(",",1);
        }
    }
    
    function stateTestHistory()
    {
        //get payment description
        if (xmlhttp3.readyState==4)//show point desc
        {
            var str=xmlhttp3.responseText;
            document.getElementById('history').innerHTML=str //.split(",",1);
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

    function printResults(pid,encNo){
        window.open('labResultsPDF.php?pid='+pid+'&encounterNr='+encNo,
            "Laboratory Results","menubar=yes,toolbar=yes,width=500,height=550,location=yes,resizable=no,scrollbars=yes,status=yes");

    }

</script>
<table border=0 cellpadding=4 cellspacing=0 width=100% class="frame" onload="getPendingTests()">
    <tr bgcolor="#f6f6f6"><td>
            <button name="show_history" onclick="getTestsHistory()">Show History</button>
            Select Previous Encounter Results
            <select>
                <option></option>
                <?php
                    $pid=$_SESSION['sess_pid'];
                    $sql="Select encounter_date from care_encounter where pid=$pid";
                    $results=$db->Execute($sql);
                    while($row=$results->FetchRow()){
                        echo "<option value='$row[0]'>$row[0]</option>";
                    }
                ?>
            </select>
        </td></tr>
    <tr bgcolor="#f6f6f6"><td id="testForm"></td></tr>
</table>
<table border=0 cellpadding=4 cellspacing=1 width=100% class="frame">
    <tr><td colspan=10><span class="testTitle">Patient Tests Requests and Results(<?php echo $_SESSION['sess_full_pid'] ?>)</span>
        <span id="testStatus" class="testStatus"></span></td></tr>
    <tr bgcolor="#f6f6f6">
        <td <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDDate; ?></td>
        <td <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDEncounterNr; ?></td>
        <td  <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDlabBatchNr; ?></td>
        <td  <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDlabParameter; ?></td>
        <td  <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDlabStatus; ?></td>
        <td  <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDBillNumber; ?></td>
        <td  <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDBillStatus; ?></td>
        <td  <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDlabItem; ?></td>
        <td  <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDlabNotes; ?></td>
    </tr>

    <?php
    $toggle = 0;
    $debug=false;
//    if ($row = $result->RecordCount()) {
    while ($row = $result->FetchRow()) {
        if ($toggle)
            $bgc = '#f3f3f3';
        else
            $bgc = '#fefefe';
        $toggle = !$toggle;
        ?>
        <!--c1.`batch_nr`,c1.`encounter_nr`,c1.`parameters`,c2.`item_id`,c2.`paramater_name`,
                      c1.`send_date`,c1.`status`,c1.`bill_number`,c1.`bill_status`-->
        <tr bgcolor="<?php echo $bgc; ?>">
            <td><?php echo $row['send_date']; ?></td>
            <td><?php echo $row['encounter_nr']; ?></td>
            <td><FONT SIZE=1 ><?php echo $row['batch_nr']; ?></td>
            <td><span style='color: #007020; font-size:small; font-weight: bold'><?php echo $row['paramater_name']; ?></span></td>
            <td class="labStatus"><?php echo $row['status']; ?></td>
            <td><?php echo $row['bill_number']; ?></td>
            <td class="labStatus"><?php echo $row['bill_status']; ?></td>
            <td><FONT SIZE=1 ><?php echo $row['item_id']; ?></td>
            <td><FONT SIZE=1 ><?php echo $row['notes']; ?></td>
        </tr>

        <?php

        echo "<tr bgcolor='#f6f6c2'><td><span style='font-size: small; font-weight: bold; color: #660000' >RESULTS</span> </td><td colspan=7>";
        if($row[field_type]=='group_field'){
            $sql="SELECT DISTINCT p.encounter_nr,k.test_date,k.test_time,p.paramater_name,p.parameter_value,
            p.job_id,p.batch_nr FROM care_test_findings_chemlabor_sub p
                LEFT JOIN care_test_findings_chemlab k ON p.job_id=k.job_id
                JOIN care_test_request_chemlabor_sub t ON k.job_id=t.batch_nr
                WHERE p.encounter_nr='$row[encounter_nr]' AND p.paramater_name LIKE '%$row[item_id]%'
                 ORDER BY job_id ASC";
            if($debug) echo $sql;

            $results=$db->Execute($sql);


            while($row2=$results->FetchRow()){
                $params=explode('-',$row2[paramater_name]);
                $sql="Select s.resultID,s.item_id,p.name,s.results,s.`normal`,s.`ranges`  from care_tz_laboratory_resultstypes s left join care_tz_laboratory_param p
                    on s.item_id=p.item_id where resultID='$params[2]' and s.item_id='$params[1]' order by p.name asc";
                if($debug)
                    echo $sql;
                $results3=$db->Execute($sql);
                $row3=$results3->FetchRow();
                echo " <tr><td>$row3[results]</td>
                            <td>$row2[parameter_value]</td>
                            <td>$row3[normal]</td>
                            <td>$row3[ranges]</td></tr>";


//                    echo " <span style='color: red; font-size:small;'>$row3[results]=</span>
//                            <span style='color: #3b19a7; font-size:small;'>$row2[parameter_value]</span>
//                            <span style='color: #3b19a7; font-size:small' - >$row3[normal];</span>
//                            <span style='color: #3b19a7; font-size:small;'>$row3[ranges];</span><br>";
            }

//            echo "</table>";

        }else{
            $sql = "SELECT p.encounter_nr,k.test_date,k.test_time,c.group_id,c.name,p.paramater_name,p.parameter_value,
         p.job_id,p.batch_nr,c.`item_id` FROM care_test_findings_chemlabor_sub p
        LEFT JOIN care_tz_laboratory_param c ON p.paramater_name=c.id
        LEFT JOIN care_test_findings_chemlab k ON p.job_id=k.job_id
        LEFT JOIN care_test_request_chemlabor t ON t.batch_nr=k.job_id
        WHERE p.encounter_nr='".$_SESSION['sess_en']."'
        and c.`item_id`= '$row[item_id]' ORDER BY job_id asc";

            $request = $db->Execute($sql);
            $rowCount = $request->RecordCount();
            if($rowCount>0){
                $row2=$request->FetchRow();
                echo " <table><tr><td>$row2[name]</td>
                            <td>$row2[parameter_value]</td>
                            <td>$row3[normal]</td>
                            <td>$row3[ranges]</td></tr></table>";
            }
        }
        echo "</td></tr>" ?>
        <tr class="testRow"><td class="testRow" colspan="9"></td></tr>
    <?php
    }

    ?>
    <tr><td><button class='careButton' onclick='closeLabTest(<?php echo $_SESSION['sess_en'] ?>)'>Complete</button></td>
        <td><button class='careButton' onclick='printResults(<?php echo $_SESSION['sess_pid']?>,<?php echo $_SESSION['sess_en'] ?>)'>Print Results</button></td></tr>

<tr bgcolor="#f6f6f6"><td colspan="10"> 
            <table border=0 cellpadding=4 cellspacing=1 width=100% class="frame" id="history"></table>
        </td></tr>
</table>
<?php
if ($parent_admit && !$is_discharged) {
    ?>
    <p>
        <img <?php echo createComIcon($root_path, 'bul_arrowgrnlrg.gif', '0', 'absmiddle'); ?>>
        <a href="<?php echo $thisfile . URL_APPEND . '&pid=' . $_SESSION['sess_pid'] . '&target=' . $target . '&mode=new'; ?>"> 
            <?php echo $LDEnterNewRecord; ?>
        </a>
        <button comple>
        <?php
    }
    ?>
