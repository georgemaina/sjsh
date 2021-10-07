<?php
# Loads the standard gui tags for the registration display page
require('./gui_bridge/default/gui_std_tags.php');

echo StdHeader();
echo setCharSet();
?>
<TITLE><?php echo $LDPatientRegister ?></TITLE>

<script  language="javascript">
    <!--

<?php require($root_path . 'include/inc_checkdate_lang.php'); ?>

    function popRecordHistory(table,pid) {
        urlholder="./record_history.php<?php echo URL_REDIRECT_APPEND; ?>&table="+table+"&pid="+pid;
        HISTWIN<?php echo $sid ?>=window.open(urlholder,"histwin<?php echo $sid ?>","menubar=no,width=250,height=300,resizable=yes,scrollbars=yes");
    }

    -->
</script>
<style type="text/css" name="1">
    .pg1{border-style: solid;border-width:thin ;font-family: serif;font-size: large;font-weight: bold; border-color: #01699E}
    .adml{border-style: solid; border-left: solid; border-width:thin; font-weight: bold; text-decoration: blink;color: #f6f2f2}
    .adm2{ background: #800;}
    .tbl1{width: 100%}
</style>
<?php
require($root_path . 'include/inc_js_gethelp.php');
require($root_path . 'include/inc_css_a_hilitebu.php');
?>

</HEAD>

<body bgcolor="<?php echo $cfg['bot_bgcolor']; ?>" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 onLoad="if (window.focus) window.focus();"
<?php
if (!$cfg['dhtml']) {
    echo 'link=' . $cfg['body_txtcolor'] . ' alink=' . $cfg['body_alink'] . ' vlink=' . $cfg['body_txtcolor'];
}
?>>


    <table width=100% border=0 cellspacing="0"  cellpadding=0 >

        <tr>
            <td bgcolor="<?php echo $cfg['top_bgcolor']; ?>">
                <FONT  COLOR="<?php echo $cfg['top_txtcolor']; ?>"  SIZE=+2  FACE="Arial"><STRONG> &nbsp;<?php echo $LDPatientRegister ?></STRONG> <font size=+2>(<?php echo ($pid) ?>)</font></FONT>
            </td>
            <td bgcolor="<?php echo $cfg['top_bgcolor']; ?>" align="right">
                <a href="javascript:gethelp('registration_overview.php','Person Registration :: Overview')"><img <?php echo createLDImgSrc($root_path, 'hilfe-r.gif', '0') ?>  <?php if ($cfg['dhtml'])
          echo'style=filter:alpha(opacity=70) onMouseover=hilite(this,1) onMouseOut=hilite(this,0)>'; ?></a><a href="<?php
                        if ($_COOKIE["ck_login_logged" . $sid])
                            echo "startframe.php?sid=" . $sid . "&lang=" . $lang;
                        else
                            echo $breakfile . "?sid=$sid&target=entry&lang=$lang";
?>
                       "><img <?php echo createLDImgSrc($root_path, 'close2.gif', '0') ?> alt="<?php echo $LDCloseWin ?>"   <?php if ($cfg['dhtml'])
                        echo'style=filter:alpha(opacity=70) onMouseover=hilite(this,1) onMouseOut=hilite(this,0)>'; ?></a>
            </td>
        </tr>

        <?php
        /* Create the tabs */
        $tab_bot_line = '#66ee66';
        require('./gui_bridge/default/gui_tz_tabs_patreg.php');
        ?>

        <tr>
            <td colspan=3   bgcolor="<?php echo $cfg['body_bgcolor']; ?>">

                <!-- table container for the data display block -->
                <table cellspacing=0 cellpadding="0">
                    <tbody>
                        <tr valign="top">
                            <td>
                                <?php
                                # Display the data
                                $person->display($pid);
                                ?>
                            </td>
                            <td>
                                <?php
                                require('./gui_bridge/default/gui_patient_reg_options.php');
                                $sTemp = ob_get_contents();
                                # Load and display the options table
                                if ($current_encounter)
//		  require('./gui_bridge/default/gui_patient_reg_options.php');
                                    
                                    ?>
                            </td>
<!--                            <td>-->
<!--                                --><?php
//                                $sql='SELECT a.pid,SUM(a.total) AS total,a.bill_number,a.bill_date FROM care_ke_billing a INNER JOIN care_encounter e
//                                ON a.pid=e.pid INNER JOIN care_person p ON e.pid=p.pid
//                                WHERE a.pid="'.$pid.'" AND e.is_discharged=1 AND p.insurance_ID=-1 AND a.status="pending" GROUP BY a.PID';
//                                $request=$db->Execute($sql);
//                                $row=$request->FetchRow();
//
//                                $sql2 = "SELECT sum(total) as total FROM care_ke_billing WHERE pid = '$pid' and `IP-OP`=1 and service_type<>'payment'";
//                                $result2 = $db->Execute($sql2);
//                                if ($row2 = $result2->FetchRow()) {
//                                    $bill=$row2[0];
//                                }
//
//                                $sql2 = "SELECT sum(total) as total FROM care_ke_billing WHERE pid = '$pid' and `IP-OP`=1 and service_type='Payment'";
//                                $result2 = $db->Execute($sql2);
//                                if ($row2 = $result2->FetchRow()) {
//                                    $paid=$row2[0];
//                                }
//                                $balance=$bill-$paid;
//                                if($balance>0){
//                                    echo ' <table class="adm2">
//                                    <tr><td class="adml">
//                                            '.$LDwarningMsg.'<br>
//                                            '.$LDwarningP1.': '.$row['bill_number'].'<br>
//                                            '.$LDwarningP2.': '.$row['bill_date'].'<br>
//                                            '.$LDwarningP3.': '.$balance.'<br>
//                                        </td></tr>
//                                </table>';
//                                }else{
//                                    echo '';
//                                }
//                                ?>
<!--                               -->
<!--                            </td>-->
                        </tr>
                    </tbody>
                </table>
                

                    <?php if (!$newdata) { ?>
                        <?php
                        if ($target == "search")
                            $newsearchfile = 'patient_register_search.php' . URL_APPEND;
                        else
                            $newsearchfile='patient_register_archive.php' . URL_APPEND;
                        ?>
                        <a href="<?php echo $newsearchfile ?>"><img
                                <?php echo createLDImgSrc($root_path, 'new_search.gif', '0', 'absmiddle') ?>></a>
                            <?php
                        }

           //==================================================
           //check user permission to update, if edit is yes show update button

                    $user = $_SESSION['sess_login_username'];
                    $sql="select updateRegister from care_users where login_id='$user'";
                    $result=$db->Execute($sql);
                    $row=$result->FetchRow();

                        if($row[0]=1){
                    ?>
                             <a href="patient_register.php<?php echo URL_APPEND ?>&pid=<?php echo $pid ?>&update=1"><img
                                    <?php echo createLDImgSrc($root_path, 'update_data.gif', '0', 'absmiddle') ?>></a>
                        <?php
                        } else{
                            echo " ";
                        }

                        //==========================================================================================================
# If currently admitted show button link to admission data display
                        if ($current_encounter) {
                            ?>
                        <a href="aufnahme_daten_zeigen.php<?php echo URL_APPEND ?>&encounter_nr=<?php echo $current_encounter ?>&origin=patreg_reg"><img <?php echo createLDImgSrc($root_path, 'admission_data.gif', '0', 'absmiddle') ?>></a>
                        <?php
//                        $sql = 'SELECT a.pid,b.encounter_nr,a.insurance_ID FROM care_person a
//                                INNER JOIN care_encounter b  ON b.pid=a.pid  INNER JOIN care_tz_company c
//                                ON c.id=a.insurance_id INNER JOIN care_ke_debtors d ON d.accno=c.accno
//                                WHERE
//                                 b.encounter_nr="' . $current_encounter . '"';
//                        $result = $db->Execute($sql);
//                       echo $sql;
//
//                        if ($row = $result->FetchRow()) {
//                            ?>
<!--                            <img --><?php //echo createLDImgSrc($root_path, 'print_slip.gif', '0', 'absmiddle') ?><!-- onclick="printSlip(--><?php //echo $pid ?>//)">
//                        <?php //} ?>
<!--                        <img --><?php //echo createLDImgSrc($root_path, 'btn_aqua_label.gif', '0', 'absmiddle') ?><!-- onclick="displayLabel(--><?php //echo $pid ?>//)">
//                        <script>
//                            var xmlHttp = createXmlHttpRequestObject();
//
//
//                            // retrieves the XMLHttpRequest object
//                            function GetXmlHttpObject()
//                            {
//                                if (window.XMLHttpRequest)
//                                {
//                                    // code for IE7+, Firefox, Chrome, Opera, Safari
//                                    return new XMLHttpRequest();
//                                }
//                                if (window.ActiveXObject)
//                                {
//                                    // code for IE6, IE5
//                                    return new ActiveXObject("Microsoft.XMLHTTP");
//                                }
//                                return null;
//                            }
//
//                            function printSlip(patientid) {
//                                xmlhttp=GetXmlHttpObject();
//                                if (xmlhttp==null)
//                                {
//                                    alert ("Browser does not support HTTP Request");
//                                    return;
//                                }
//                                var url;
//
//                                url="chargeSlip.php?pid="+patientid;
//                                xmlhttp.onreadystatechange=stateChanged2;
//                                xmlhttp.open("GET",url,true);
//                                xmlhttp.send(null);
//                            }
//
//                            function stateChanged2()
//                            {
//                                if (xmlhttp.readyState==4)
//                                {
//                                    //                                    if(xmlhttp.responseText=='success'){
//                                    alert(xmlhttp.responseText);
//                                    //                                    }
//                                    if(xmlhttp.responseText=='success'){
//                                        displaySlip(<?php //echo $pid.'' ?>//);
//                                    }
//                                }
//                            }
//
//                            function displaySlip(strPid,strSlip) {
//                                window.open('reports/creditSlipPdf.php?pid='+strPid+'&slipNo='+strSlip+'&reprint=1',
//                                    "receipt","menubar=no,toolbar=no,width=300,height=550,location=yes,resizable=no,scrollbars=no,status=yes");
//                            }
//
//                            function displayLabel(pid) {
//                                window.open('http://localhost/receipt/label.php?pid='+pid
//                                ,"Label","menubar=no,toolbar=no,width=300,height=550,location=yes,resizable=no,scrollbars=no,status=yes");
//                            }
//                        </script>
                        
                        <?php
# Else if person still living, show button links to admission
                    } elseif (!$death_date || $death_date == $dbf_nodate) {
                        ?>
                        <a href="<?php echo $admissionfile ?>&pid=<?php echo $pid ?>&origin=patreg_reg&encounter_class_nr=1"><img <?php echo createLDImgSrc($root_path, 'admit_inpatient.gif', '0', 'absmiddle') ?>></a>
                        <a href="<?php echo $admissionfile ?>&pid=<?php echo $pid ?>&origin=patreg_reg&encounter_class_nr=2"><img <?php echo createLDImgSrc($root_path, 'admit_outpatient.gif', '0', 'absmiddle') ?>></a>
                        <img <?php echo createLDImgSrc($root_path, 'btn_aqua_label.gif', '0', 'absmiddle') ?> onclick="displayLabel(<?php echo $pid ?>)">
                         <?php
                    }
                    ?>

                    <?php
                    /*
                      # Don't show register patient button - is confusing staff
                      <form action="patient_register.php" method=post>
                      <input type=submit value="<?php echo $LDRegisterNewPerson ?>" >
                      <input type=hidden name="sid" value=<?php echo $sid; ?>>
                      <input type=hidden name="lang" value="<?php echo $lang; ?>">
                      </form> */
# Load and display the options table
//ob_start();
//ob_end_clean();
                    ?>


                    </ul>

                    </FONT>
                <p>
            </td>
        </tr>
    </table>
    <p>
    <ul>
        <FONT    SIZE=2  FACE="Arial">
        <img <?php echo createComIcon($root_path, 'varrow.gif', '0') ?>> <a href="patient_register_search.php<?php echo URL_APPEND; ?>"><?php echo $LDPersonSearch ?></a><br>
        <img <?php echo createComIcon($root_path, 'varrow.gif', '0') ?>> <a href="patient_register_archive.php<?php echo URL_APPEND; ?>&newdata=1&from=entry"><?php echo $LDArchive ?></a><br>


            <a href="
            <?php
            if ($_COOKIE['ck_login_logged' . $sid])
                echo $root_path . 'main/menu/startframe.php' . URL_APPEND;
            else
                echo $breakfile . URL_APPEND;
            ?>
               "><img <?php echo createLDImgSrc($root_path, 'cancel.gif', '0') ?> alt="<?php echo $LDCancelClose ?>"></a>
    </ul>

        <?php
        require($root_path . 'include/inc_load_copyrite.php');
        ?>
        </font>
        <?php
        StdFooter();
        ?>
</body>