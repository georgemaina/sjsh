<script  language="javascript">
    function popNotes(d){
        alert(d);
    }
    var xmlhttp3;

    function saveTest(){
        alert('Saved Successfully');
    }

    function testRequest(str)
    {
        xmlhttp3=GetXmlHttpObject();
        if (xmlhttp3==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="register_functions.php?desc3="+str;
        url=url+"&sid="+Math.random();
        url=url+"&task=labTestForm";
        xmlhttp3.onreadystatechange=stateChanged;
        xmlhttp3.open("POST",url,true);
        xmlhttp3.send(null);

    }
    function stateChanged()
    {
        //get payment description
        if (xmlhttp3.readyState==4)//show point desc
        {
            var str=xmlhttp3.responseText;
            document.getElementById('testForm').innerHTML=str //.split(",",1);
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

    function getPendingTests(){
        alert('Test');
    }
    
    
    -->
</script>
<script language="javascript" src="<?php echo $root_path; ?>js/check_prescription_form.js"></script>

<table border=0 cellpadding=4 cellspacing=1 width=100% class="frame" onload="getPendingTests()">
    
        <tr bgcolor="#f6f6f6"><td colspan="9">
                <input type=submit value="New Test Request" name="testRequest" onclick="testRequest()"/>
                <input type=submit value="show history" name="show_history" onclick="javascript:alert('$showHist');"/></td></tr>
        <tr bgcolor="#f6f6f6"><td colspan="9" id="testForm"></td></tr>
        <tr bgcolor="#f6f6f6">
            <td <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDDate; ?></td>
            <td <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDEncounterNr; ?></td>
            <td <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDlabBatchNr; ?></td>
            <td <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDlabParameter; ?></td>
            <td <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDlabStatus; ?></td>
            <td <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDBillNumber; ?></td>
            <td <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDBillStatus; ?></td>
            <td <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDlabItem; ?></td>
            <td <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDlabNotes; ?></td>
        </tr>

        <?php
        $toggle = 0;
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
                <td><FONT SIZE=1 ><?php echo $row['paramater_name']; ?></td>
                <td class="labStatus"><?php echo $row['status']; ?></td>
                <td><FONT SIZE=1 ><?php echo $row['bill_number']; ?></td>
                <td class="labStatus"><?php echo $row['bill_status']; ?></td>
                <td><FONT SIZE=1 ><?php echo $row['item_id']; ?></td>
                <td><FONT SIZE=1 ><?php echo $row['notes']; ?></td>
            </tr>

            <?php
        }
//    }
        ?>

</table>
<?php
if ($parent_admit && !$is_discharged) {
    ?>
    <p>
        <img <?php echo createComIcon($root_path, 'bul_arrowgrnlrg.gif', '0', 'absmiddle'); ?>>
        <a href="<?php echo $thisfile . URL_APPEND . '&pid=' . $_SESSION['sess_pid'] . '&target=' . $target . '&mode=new'; ?>"> 
            <?php echo $LDEnterNewRecord; ?>
        </a>
        <?php
    }
    ?>
