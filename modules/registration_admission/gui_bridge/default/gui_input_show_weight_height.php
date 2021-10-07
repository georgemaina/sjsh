
<script language="JavaScript">
    function chkform(d) {
        if (d.msr_date.value === "") {
            alert("<?php echo $LDPlsEnterDate; ?>");
            d.msr_date.focus();
            return false;
        } else if (d.weight.value === "" || d.v.value < 1) {
            alert("<?php echo "Weight cannot be empty" ?>");
            d.weight.focus();
            return false;
        } else if (d.bp.value || d.bp.value < 1) {
            d.bp.focus(); // patch for Konqueror
            alert("<?php echo "BP cannot be empty"; ?>");
            d.bp.focus();
            return false;
        } else if (d.bp2.value < "" || d.bp2.value < 0) {
            d.bp2.focus(); // patch for Konqueror
            alert("<?php echo "BP cannot be empty" ?>");
            d.bp2.focus();
            return false;
        } else if (d.measured_by.value === "") {
            alert("<?php echo $LDPlsEnterFullName; ?>");
            d.measured_by.focus();
            return false;
        } else {
            return true;
        }
    }

    function roundNumber(num, dec) {
        var result = Math.round(num * Math.pow(10, dec)) / Math.pow(10, dec);
        return result;
    }

    function getBmi(k) {
        //alert('Test '+k );
//    document.bmi.value='Test'+k
        var weight = document.getElementById('weight').value;
        var height = k / 100;
        var bmi = weight / (height * height);
        var strBmi = roundNumber(bmi, 2);
        document.getElementById('bmi').value = strBmi;
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

    function setHtcNotes(str) {
        // alert(str)
        xmlhttp = GetXmlHttpObject();
        if (str == "OPTOUT") {

            if (xmlhttp == null)
            {
                alert("Browser does not support HTTP Request");
                return;
            }
            var url = "myFunctions.php?task=getHtcReasons";
            url = url + "&sid=" + Math.random();
            xmlhttp.onreadystatechange = stateChanged7;
            xmlhttp.open("POST", url, true);
            xmlhttp.send(null);
        }

    }

    function stateChanged7()
    {
        //get payment description
        if (xmlhttp.readyState == 4)
        {
            var str3 = xmlhttp.responseText;

            document.getElementById('optOutReason').innerHTML = str3;


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

<?php

function setDoctorsList() {
    global $db;

    $sql = "Select staff_name from care_ke_staff";
    $result = $db->Execute($sql);
    $strDoctorsList = "<SELECT name='attending_doctor'>
                        <OPTION></OPTION>";

    while ($row = $result->FetchRow()) {
        $strDoctorsList .= "<OPTION VALUE='$row[staff_name]'>$row[staff_name]</OPTION>>";
    }
    $strDoctorsList .= "</SELECT>";

    return $strDoctorsList;
}
?>

<form method="post" name="wtht_form" onSubmit="return chkform(this)">
    <table border=0 cellpadding=2 width=100%>
        <tr bgcolor="#f6f6f6">
            <td width="100"><font color=red>*</font><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDDate; ?></td>
            <td width="100"><input type="text" name="msr_date" size=10 maxlength=10 value="<?php echo date('d-m-Y'); ?>"  onBlur="IsValidDate(this, '<?php echo $date_format ?>')" onKeyUp="setDate(this, '<?php echo $date_format ?>', '<?php echo $lang ?>')">
                <a href="javascript:show_calendar('wtht_form.msr_date','<?php echo $date_format; ?>')"><img <?php echo createComIcon($root_path, 'show-calendar.gif', '0', 'absmiddle', TRUE); ?>></a>
            </td>
            <td width="400"></td>
            <td></td>
            <td></td>
        </tr>
     <!--    <tr bgcolor="#f6f6f6">
          <td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDTime; ?></td>
          <td><input type="text" name="msr_time" size=10 maxlength=5 ></td>
        </tr>
        -->   
        <tr bgcolor="#f6f6f6">
            <td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDType; ?></td>
            <td><font color=red>*</font><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDUnit . ' ' . $LDValue; ?></td>
            <td width="400"></td>
            <td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDUnit . ' ' . $LDType; ?></td>
            <td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo "$LDNotes ($LDOptional)"; ?></td>
        </tr>

        <tr bgcolor="#f6f6f6">
            <td><FONT SIZE=-1  FACE="Arial"><?php echo $LDWeight; ?></td>
            <td><span style="color: #ff0000">*</span>
                <FONT SIZE=-1  FACE="Arial"><input type="text" name="weight" id="weight" size=10 maxlength=10 value="<?php echo $weight; ?>"></td>
            <td></td>
            <td><FONT SIZE=-1  FACE="Arial">
                <select name="wt_unit_nr">
                    <?php
                    while (list($x, $v) = each($unit_types)) {
                        echo '<option value="' . $v['nr'] . '"';
                        if ($v['nr'] == $wt_unit_nr)
                            echo 'selected';
                        echo '>';
                        if (isset($$v['LD_var']) && !empty($$v['LD_var']))
                            echo $$v['LD_var'];
                        else
                            echo $v['name'];
                        echo '</option>
				';
                    }
                    reset($unit_types);
                    ?>
                </select>
            </td>
            <td width="400"><FONT SIZE=-1  FACE="Arial"><input type="text" name="wt_notes" size=40 maxlength=60 value="<?php echo $wt_notes; ?>"></td>

        </tr>

        <tr bgcolor="#f6f6f6">
            <td><FONT SIZE=-1  FACE="Arial"><?php echo $LDHeight; ?></td>
            <td><span style="color: white">*</span>
                <FONT SIZE=-1  FACE="Arial"><input type="text" name="height" id="height" size=10 maxlength=10 value="<?php echo $height; ?>"  onchange="getBmi(this.value)"></td>
            <td></td>
            <td><FONT SIZE=-1  FACE="Arial">
                <select name="ht_unit_nr">
                    <?php
                    while (list($x, $v) = each($unit_types)) {
                        echo '<option value="' . $v['nr'] . '"';
                        if ($v['nr'] == $ht_unit_nr)
                            echo 'selected';
                        echo '>';
                        if (isset($$v['LD_var']) && !empty($$v['LD_var']))
                            echo $$v['LD_var'];
                        else
                            echo $v['name'];
                        echo '</option>
				';
                    }
                    reset($unit_types);
                    ?>
                </select>
            </td>
            <td><FONT SIZE=-1  FACE="Arial"><input type="text" name="ht_notes" size=40 maxlength=60 value="<?php echo $ht_notes; ?>"></td>

        </tr>

        <tr bgcolor="#f6f6f6">
            <td><FONT SIZE=-1  FACE="Arial" ><?php echo $LD['head_circumference']; ?></td>
            <td><span style="color: white">*</span>
                <FONT SIZE=-1  FACE="Arial"><input type="text" name="head_c" size=10 maxlength=10 value="<?php echo $head_c; ?>"></td>
            <td></td>
            <td><FONT SIZE=-1  FACE="Arial">
                <select name="hc_unit_nr">
                    <?php
                    while (list($x, $v) = each($unit_types)) {
                        echo '<option value="' . $v['nr'] . '"';
                        if ($v['nr'] == $hc_unit_nr)
                            echo 'selected';
                        echo '>';
                        if (isset($$v['LD_var']) && !empty($$v['LD_var']))
                            echo $$v['LD_var'];
                        else
                            echo $v['name'];
                        echo '</option>
				';
                    }
                    reset($unit_types);
                    ?>
                </select>
            </td>
            <td><FONT SIZE=-1  FACE="Arial"><input type="text" name="hc_notes" size=40 maxlength=60 value="<?php echo $hc_notes; ?>"></td>

        </tr>

        <tr bgcolor="#f6f6f6">
            <td><FONT SIZE=-1  FACE="Arial" ><?php echo $LDBP; ?></td>
            <td><span style="color: #ff0000">*</span>
                <FONT SIZE=-1  FACE="Arial"><span id="container"></span><input type="text" name="bp" id="bp" size=5 maxlength=5 value="<?php echo $bp; ?>">
                <input type="text" name="bp2" size=5 id="bp2" maxlength=5 value="<?php echo $bp2; ?>" > 
				<input type="hidden" size="5" name="innitialMode" id="innitialMode"  value="">
				<span id="htnKnown"></span></td>
            <td></td>
            <td><FONT SIZE=-1  FACE="Arial">
                <select name="bp_unit_nr">
                    <?php
                    while (list($x, $v) = each($unit_types)) {
                        echo '<option value="' . $v['nr'] . '"';
                        if ($v['nr'] == $bp_unit_nr)
                            echo 'selected';
                        echo '>';
                        if (isset($$v['LD_var']) && !empty($$v['LD_var']))
                            echo $$v['LD_var'];
                        else
                            echo $v['name'];
                        echo '</option>
				';
                    }
                    reset($unit_types);
                    ?>
                </select>
            </td>
            <td><FONT SIZE=-1  FACE="Arial"><input type="text" name="bp_notes" size=40 maxlength=60 value="<?php echo $bp_notes; ?>"></td>

        </tr>


        <tr bgcolor="#f6f6f6">
            <td><FONT SIZE=-1  FACE="Arial" ><?php echo $LDPulse; ?></td>
            <td><span style="color: white">*</span>
                <FONT SIZE=-1  FACE="Arial"><input type="text" name="pulse" size=10 maxlength=10 value="<?php echo $pulse; ?>"></td>
            <td></td>
            <td><FONT SIZE=-1  FACE="Arial">
                <select name="pulse_unit_nr">
                    <?php
                    while (list($x, $v) = each($unit_types)) {
                        echo '<option value="' . $v['nr'] . '"';
                        if ($v['nr'] == $pulse_unit_nr)
                            echo 'selected';
                        echo '>';
                        if (isset($$v['LD_var']) && !empty($$v['LD_var']))
                            echo $$v['LD_var'];
                        else
                            echo $v['name'];
                        echo '</option>
				';
                    }
                    reset($unit_types);
                    ?>
                </select>
            </td>
            <td><FONT SIZE=-1  FACE="Arial"><input type="text" name="pulse_notes" size=40 maxlength=60 value="<?php echo $pulse_notes; ?>"></td>
        </tr>


        <tr bgcolor="#f6f6f6">
            <td><FONT SIZE=-1  FACE="Arial" ><?php echo $LDResprate; ?></td>
            <td><span style="color: white">*</span>
                <FONT SIZE=-1  FACE="Arial"><input type="text" name="resprate" size=10 maxlength=10 value="<?php echo $resprate; ?>"></td>
            <td></td>
            <td><FONT SIZE=-1  FACE="Arial">
                <select name="resprate_unit_nr">
                    <?php
                    while (list($x, $v) = each($unit_types)) {
                        echo '<option value="' . $v['nr'] . '"';
                        if ($v['nr'] == $resprate_unit_nr)
                            echo 'selected';
                        echo '>';
                        if (isset($$v['LD_var']) && !empty($$v['LD_var']))
                            echo $$v['LD_var'];
                        else
                            echo $v['name'];
                        echo '</option>
				';
                    }
                    reset($unit_types);
                    ?>
                </select>
            </td>
            <td><FONT SIZE=-1  FACE="Arial"><input type="text" name="resprate_notes" size=40 maxlength=60 value="<?php echo $resprate_notes; ?>"></td>

        </tr>


        <tr bgcolor="#f6f6f6">
            <td><FONT SIZE=-1  FACE="Arial" ><?php echo $LDTemparature; ?></td>
            <td><span style="color: white">*</span>
                <FONT SIZE=-1  FACE="Arial"><input type="text" name="temperature" size=10 maxlength=10 value="<?php echo $temperature; ?>"></td>
            <td></td>
            <td><FONT SIZE=-1  FACE="Arial">
                <select name="temp_unit_nr">
                    <?php
                    while (list($x, $v) = each($unit_types)) {
                        echo '<option value="' . $v['nr'] . '"';
                        if ($v['nr'] == $temp_unit_nr)
                            echo 'selected';
                        echo '>';
                        if (isset($$v['LD_var']) && !empty($$v['LD_var']))
                            echo $$v['LD_var'];
                        else
                            echo $v['name'];
                        echo '</option>
				';
                    }
                    reset($unit_types);
                    ?>
                </select>
            </td>

            <td><FONT SIZE=-1  FACE="Arial"><input type="text" name="temp_notes" size=40 maxlength=60 value="<?php echo $temp_notes; ?>"></td>

        </tr>


        <tr bgcolor="#f6f6f6">
            <td><FONT SIZE=-1  FACE="Arial" ><?php echo $LDHtc; ?></td>
            <td><span style="color: white">*</span>
                <FONT SIZE=-1  FACE="Arial"><select name="htc"  onchange="setHtcNotes(this.value)">
                    <option></option>
                    <option value="OPTIN">OPT IN</option>
                    <option value="OPTOUT">OPT OUT</option></select></td>
            <td id="optOutReason"></td>
     <!--                   HTC Reason:<input type="text" name="htc_reason" id="htc_reason" size="30" value="--><?php //echo $htc_reason;    ?><!--"></td>-->
            <td><FONT SIZE=-1  FACE="Arial">
                <select name="htc_unit_nr">
                    <?php
                    while (list($x, $v) = each($unit_types)) {
                        echo '<option value="' . $v['nr'] . '"';
                        if ($v['nr'] == $htc_unit_nr)
                            echo 'selected';
                        echo '>';
                        if (isset($$v['LD_var']) && !empty($$v['LD_var']))
                            echo $$v['LD_var'];
                        else
                            echo $v['name'];
                        echo '</option>';
                    }
                    reset($unit_types);
                    ?>
                </select>

            </td>

            <td id="htcNotes"><FONT SIZE=-1  FACE="Arial"><input type="text" name="bmi_notes" size=40 maxlength=60 value="<?php echo $htc_notes; ?>"></td>

        </tr>


        <tr bgcolor="#f6f6f6">
            <td><FONT SIZE=-1  FACE="Arial" ><?php echo $LDBMI; ?></td>
            <td><span style="color: white">*</span>
                <FONT SIZE=-1  FACE="Arial"><input type="text" name="bmi" id="bmi" size=10 maxlength=10 value="<?php echo $bmi; ?>"></td>
            <td></td>
            <td><FONT SIZE=-1  FACE="Arial">
                <select name="bmi_unit_nr">
                    <?php
                    while (list($x, $v) = each($unit_types)) {
                        echo '<option value="' . $v['nr'] . '"';
                        if ($v['nr'] == $htc_unit_nr)
                            echo 'selected';
                        echo '>';
                        if (isset($$v['LD_var']) && !empty($$v['LD_var']))
                            echo $$v['LD_var'];
                        else
                            echo $v['name'];
                        echo '</option>
				';
                    }
                    reset($unit_types);
                    ?>
                </select>
            </td>

            <td><FONT SIZE=-1  FACE="Arial"><input type="text" name="bmi_notes" size=40 maxlength=60 value="<?php echo $bmi_notes; ?>"></td>

        </tr>

        <tr bgcolor="#f6f6f6">
            <td><FONT SIZE=-1  FACE="Arial" ><?php echo $LDSPO2; ?></td>
            <td><span style="color: white">*</span>
                <FONT SIZE=-1  FACE="Arial"><input type="text" name="spo2" size=10 maxlength=10 value="<?php echo $spo2; ?>"  id="spo2"></td>
            <td></td>
            <td><FONT SIZE=-1  FACE="Arial">
                <select name="spo2_unit_nr">
                    <?php
                    while (list($x, $v) = each($unit_types)) {
                        echo '<option value="' . $v['nr'] . '"';
                        if ($v['nr'] == $spo2_unit_nr)
                            echo 'selected';
                        echo '>';
                        if (isset($$v['LD_var']) && !empty($$v['LD_var']))
                            echo $$v['LD_var'];
                        else
                            echo $v['name'];
                        echo '</option>
				';
                    }
                    reset($unit_types);
                    ?>
                </select>
            </td>

            <td><FONT SIZE=-1  FACE="Arial"><input type="text" name="spo2_notes" size=40 maxlength=60 value="<?php echo $spo2_notes; ?>"></td>

        </tr>
        <!--  wt = 6, ht= 7 -->
        <tr bgcolor="#f6f6f6">
            <td colspan=5>&nbsp;</td>
        </tr>   
        <tr bgcolor="#f6f6f6">
            <td><span style="color: #ff0000">*</span><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDMeasuredBy; ?></td>
            <td><input type="text" name="measured_by" size=20 maxlength=60 value="<?php echo $_SESSION['sess_user_name']; ?>"></td>
            <td></td>
            <td style="font-size: small; font-family: arial, serif; color: #000088"><span style="color: #ff0000">*</span>Attending Doctor</td>
            <td><FONT SIZE=-1  FACE="Arial"><?php echo setDoctorsList(); ?></td>
        </tr>
    </table>
    <input type="hidden" name="encounter_nr" id="encounter_nr" value="<?php echo $_SESSION['sess_en']; ?>">
    <input type="hidden" name="pid" id="pid" value="<?php echo $_SESSION['sess_pid']; ?>">
    <!--<input type="hidden" name="modify_id" value="<?php echo $_SESSION['sess_user_name']; ?>">
    <input type="hidden" name="create_id" value="<?php echo $_SESSION['sess_user_name']; ?>">-->
    <!-- <input type="hidden" name="create_time" value="<?php echo date('YmdHis'); ?>">
    -->

    <input type="hidden" name="mode" value="create">
    <input type="hidden" name="target" value="<?php echo $target; ?>">
    <input type="hidden" name="history" value="Created: <?php echo date('Y-m-d H:i:s'); ?> : <?php echo $_SESSION['sess_user_name'] . "\n"; ?>">
    <input type="image" <?php echo createLDImgSrc($root_path, 'savedisc.gif', '0'); ?>>

</form>
