<script language="JavaScript">

function chkform(d) {
	if(d.date.value==""){
		alert("<?php echo $LDPlsEnterDate; ?>");
		d.date.focus();
		return false;
	}else if(d.notes.value==""){
		alert("");
		d.notes.focus();
		return false;
	}else if(d.personell_name.value==""){
		alert("<?php echo $LDPlsEnterFullName; ?>");
		d.personell_name.focus();
		return false;
	}else{
		return true;
	}
}

    function GetXmlHttpObject()
    {
        try {
            var xmlHttp = null;
            if (window.XMLHttpRequest)
            {
                // If IE7, Mozilla, Safari, etc: Use native object
                xmlHttp = new XMLHttpRequest()
            } else
            {
                if (window.ActiveXObject)
                {
                    // ...otherwise, use the ActiveX control for IE5.x and IE6
                    xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
            }

            return xmlHttp;
        } catch (e)
        {
            alert(e.message);
        }
    }
	
	function getEncounterVitals(encNr){
		// alert(str)
        xmlhttp = GetXmlHttpObject();
        if (xmlhttp === null)
        {
            alert("Browser does not support HTTP Request");
            return;
        }
        var url = "data/getDataFunctions.php?task=getEncounterVitals";
        url = url + "&pid=" + pid;
        url = url + "&validateSource=1";
        url = url + "&sid=" + Math.random();
        xmlhttp.onreadystatechange = stateChanged10;
        xmlhttp.open("POST", url, true);
        xmlhttp.send(null);
	}
	
	function stateChanged10()
    {
        //get payment description
        if (xmlhttp.readyState === 4)
        {
            var str3 = xmlhttp.responseText;
            // if(str3>0){
            document.getElementById('innitialMode').value = str3;
            //}


        }
    }


    function checkIfInnitialExists(pid) {
        // alert(str)
        xmlhttp = GetXmlHttpObject();
        if (xmlhttp === null)
        {
            alert("Browser does not support HTTP Request");
            return;
        }
        var url = "data/getDataFunctions.php?task=validatePatient";
        url = url + "&pid=" + pid;
        url = url + "&validateSource=1";
        url = url + "&sid=" + Math.random();
        xmlhttp.onreadystatechange = stateChanged9;
        xmlhttp.open("POST", url, true);
        xmlhttp.send(null);

    }

    function stateChanged9()
    {
        //get payment description
        if (xmlhttp.readyState === 4)
        {
            var str3 = xmlhttp.responseText;
            // if(str3>0){
            document.getElementById('innitialMode').value = str3;
            //}
        }
    }

    function checkBP() {
        alert('Test')
    }



</script>

<form method="post" name="notes_form" onSubmit="chkform(this)">
 <table border=0 cellpadding=2 width=100%>
   <tr bgcolor="#f6f6f6">
     <td><font SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDDate; ?></td>
     <td>
	 	<input type="text" name="date" size=10 maxlength=10  onBlur="IsValidDate(this,'<?php echo $date_format ?>')" onKeyUp="setDate(this,'<?php echo $date_format ?>','<?php echo $lang ?>')">
	 	<a href="javascript:show_calendar('notes_form.date','<?php echo $date_format ?>')">
 		<img <?php echo createComIcon($root_path,'show-calendar.gif','0','absmiddle',TRUE); ?>></a> 
 		<font size=1>[ <?php   
 		$dfbuffer="LD_".strtr($date_format,".-/","phs");
  		echo $$dfbuffer;
 		?> ] </font>
	</td>
   </tr>
   <tr bgcolor="#f6f6f6">
     <td><font SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDApplication.' '.$LDNotes; ?></td>
     <td><textarea name="notes" id="notes" cols=40 rows=8 wrap="physical"></textarea>
         </td>
   </tr>
   <tr bgcolor="#f6f6f6">
     <td><font SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDSendCopyTo; ?></td>
     <td><input type="text" name="send_to_name" size=50 maxlength=60></td>
   </tr>
   <tr bgcolor="#f6f6f6">
     <td><font SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDBy; ?></td>
     <td><input type="text" name="personell_name" size=50 maxlength=60 value="<?php echo $_SESSION['sess_user_name']; ?>" readonly></td>
   </tr>
 </table>
<input type="text" name="encounter_nr" value="<?php echo $_SESSION['sess_en']; ?>">
<input type="text" name="pid" value="<?php echo $_SESSION['sess_pid']; ?>">
<input type="hidden" name="modify_id" value="<?php echo $_SESSION['sess_user_name']; ?>">
<input type="hidden" name="create_id" value="<?php echo $_SESSION['sess_user_name']; ?>">
<input type="hidden" name="create_time" value="null">
<input type="hidden" name="mode" value="create">
<input type="hidden" name="personell_nr">
<input type="hidden" name="send_to_pid">
<input type="hidden" name="type_nr" value="<?php echo $type_nr; ?>">
<input type="hidden" name="target" value="<?php echo $target; ?>">
<input type="hidden" name="history" value="Created: <?php echo date('Y-m-d H:i:s'); ?> : <?php echo $_SESSION['sess_user_name']."\n"; ?>">
<input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0'); ?>>

</form>
