<script  language="javascript">
    
    getPendingTests();
    
    function popNotes(d){
        alert(d);
    }
    var xmlhttp3;
    
    function cancelRequest()
    {
        document.getElementById('testForm').innerHTML='';

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
    
    function getPendingTests(){
   
        xmlhttp3=GetXmlHttpObject();
        if (xmlhttp3==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="register_functions.php?task=getPendingLabTests";
        url=url+"&sid="+Math.random();
        xmlhttp3.onreadystatechange=stateChanged3;
        xmlhttp3.open("POST",url,true);
        xmlhttp3.send(null);
     
    }
    
    function stateChanged3()
    {
        //get payment description
        if (xmlhttp3.readyState==4)//show point desc
        {
            var str=xmlhttp3.responseText;
            document.getElementById('results').innerHTML=str //.split(",",1);
        }
    }

    function saveTest(){
        var destination_obj = document.forms[0].elements['selected_item_list[]'];
        var parameters="";
        if (destination_obj.length > 0 )
            for (var i=0 ; i<destination_obj.length; i++ ){
                parameters+= destination_obj.options[i].value+',';
            }
        //destination_obj.length;//destination_obj.options[i].value;
        //     alert('Test Success, saved '+parameters);
        xmlhttp3=GetXmlHttpObject();
        if (xmlhttp3==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="register_functions.php?partcodes="+parameters;
        url=url+"&sid="+Math.random();
        url=url+"&task=saveTest";
        xmlhttp3.onreadystatechange=stateChanged2;
        xmlhttp3.open("POST",url,true);
        xmlhttp3.send(null);
     
    }
 
    function stateChanged2()
    {
        //get payment description
        if (xmlhttp3.readyState==4)//show point desc
        {
            var str=xmlhttp3.responseText;
            document.getElementById('results').innerHTML=str //.split(",",1);
            getPendingTests();
            document.getElementById('testForm').innerHTML='';
        }
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
 
</script>
<script language="javascript" src="<?php echo $root_path; ?>js/check_prescription_form.js">
getPendingTests();
</script>

<table border=0 cellpadding=4 cellspacing=0 width=100% class="frame" onload="getPendingTests()">
    <tr bgcolor="#f6f6f6"><td>
            <button name="testRequest" onclick="testRequest()">New Test Request</button>
            <button name="show_history" onclick="getTestsHistory()">Show History</button></td></tr>
    <tr bgcolor="#f6f6f6"><td id="testForm"></td></tr>
    <tr bgcolor="#f6f6f6"><td> 
            <table border=0 cellpadding=4 cellspacing=1 width=100% class="frame" id="results"></table>
            <table border=0 cellpadding=4 cellspacing=1 width=100% class="frame" id="history"></table>
        </td></tr>

    
</table>
<?php
if ($parent_admit && !$is_discharged) {
    ?>
    <p>
<!--        <img <?php // echo createComIcon($root_path, 'bul_arrowgrnlrg.gif', '0', 'absmiddle'); ?>>
        <a href="<?php // echo $thisfile . URL_APPEND . '&pid=' . $_SESSION['sess_pid'] . '&target=' . $target . '&mode=new'; ?>"> 
            <?php //echo $LDEnterNewRecord; ?>
        </a>-->
        <?php
    }
    ?>
