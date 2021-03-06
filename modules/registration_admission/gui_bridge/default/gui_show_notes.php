<?php
# Resolve href for return button
//echo 'test';
if (isset($_SESSION['sess_file_return']) && !empty($_SESSION['sess_file_return']))
    $returnfile = $_SESSION['sess_file_return'];
else
    $returnfile = $top_dir . 'show_appointment.php';

# Patch 2003-11-20 
if ($parent_admit) {
    $retbuf = '&encounter_nr=' . $_SESSION['sess_full_en'];
    $sTitleNr = ($_SESSION['sess_full_en']);
} else {
    $retbuf = '&pid=' . $_SESSION['sess_pid'];
    $sTitleNr = ($_SESSION['sess_full_pid']);
}

//echo $mode;

# Resolve href for close button

//if (!$_COOKIE["ck_login_logged" . $sid])
//    $breakfile = $root_path . "main/startframe.php" . URL_APPEND;
//else
//    $breakfile = $_SESSION['backpath_diag'];

if ($_COOKIE["ck_login_logged" . $sid])
    $breakfile =  $_SESSION['backpath_diag'];
else
    $breakfile = $root_path . "main/startframe.php" . URL_APPEND;
    $breakfile = $breakfile . "&target=entry";

# Start Smarty templating here
/**
 * LOAD Smarty
 */
# Note: it is advisable to load this after the inc_front_chain_lang.php so
# that the smarty script can use the user configured template theme

require_once($root_path . 'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care('common');

# Title in the toolbar
$smarty->assign('sToolbarTitle', "$page_title ($sTitleNr)");

$smarty->assign('breakfile', $breakfile);

# Window bar title
$smarty->assign('title', "$page_title ($sTitleNr)");

# href for help button
$smarty->assign('pbHelp', "javascript:gethelp('notes_router.php','$notestype','" . strtr($subtitle, ' ', '+') . "','$mode','$rows')");

# Onload Javascript code
$smarty->assign('sOnLoadJs', 'onLoad="if (window.focus) window.focus();"');

# href for the return button
$smarty->assign('pbBack', $returnfile . URL_APPEND . $retbuf . '&target=' . $target . '&mode=show&type_nr=' . $type_nr);

/**
 * Helper function to generate rows
 */
function createTR($ld_text, $input_val, $colspan = 1) {
    global $toggle, $root_path;
    ?>

    <tr>
        <td bgColor="#eeeeee" ><FONT SIZE=-1  FACE="Arial,verdana,sans serif"><?php echo $ld_text ?>:</FONT>
        </td>
        <td colspan=<?php echo $colspan; ?> bgcolor="#ffffee"><FONT SIZE=-1  FACE="Arial,verdana,sans serif"><?php echo $input_val; ?></FONT>
        </td>
    </tr>

    <?php
    $toggle = !$toggle;
}

# Collect extra javascript code

ob_start();
?>

<script  language="javascript">

    function checkBP() {
        alert('Test')
    }
    
    function chkform(d) {
        if (d.date.value == "") {
            alert("<?php echo $LDPlsEnterDate; ?>");
            d.date.focus();
            return false;
        } else if (d.notes.value == "") {
            alert("<?php echo $LDPlsEnterReport; ?>");
            d.notes.focus();
            return false;
        } else if (d.personell_name.value == "") {
            alert("<?php echo $LDPlsEnterFullName; ?>");
            d.personell_name.focus();
            return false;
        } else {
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
	
// 	function getEncounterVitals(encounterNr){
// 		// alert(str)
//         xmlhttp = GetXmlHttpObject();
//         if (xmlhttp === null)
//         {
//             alert("Browser does not support HTTP Request");
//             return;
//         }
//         var url = "data/getDataFunctions.php?task=getEncounterVitals";
//          url = url + "&EncounterNo=" + encounterNr;
//         url = url + "&validateSource=1";
//         url = url + "&sid=" + Math.random();
//         xmlhttp.onreadystatechange = stateChanged10;
//         xmlhttp.open("POST", url, true);
//         xmlhttp.send(null);
// 	}
// 	
// 	function stateChanged10()
//     {
//         //get payment description
//         if (xmlhttp.readyState === 4)
//         {
//             var str3 = xmlhttp.responseText;
//             // if(str3>0){
//             document.getElementById('innitialMode').value = str3;
//             //}
// 
// 
//         }
//     }


    function checkIfInnitialExists(pid) {
         // Ext.Msg.alert("Test", pid);
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
             // Ext.Msg.alert("Test", str3);
            // if(str3>0){
            document.getElementById('innitialMode').value = str3;
           
            //}
        }
    }

       function showVitals(encounterNr) {
              //alert(encounterNr)
              xmlhttp = GetXmlHttpObject();
              if (xmlhttp === null)
              {
                  alert("Browser does not support HTTP Request");
                  return;
              }
              var url = "data/getDataFunctions.php?task=showVitals";
              url = url + "&EncounterNo=" + encounterNr;
              url = url + "&sid=" + Math.random();
              xmlhttp.onreadystatechange = stateChanged11;
              xmlhttp.open("POST", url, true);
              xmlhttp.send(null);

      }

    function stateChanged11()
    {
        //get payment description
        if (xmlhttp.readyState === 4)
        {
            var str = xmlhttp.responseText;
            var str2=str.split(",")
            // if(str3>0){
            document.getElementById('bp1').value = str2[0];
            document.getElementById('bp2').value = str2[1];
            document.getElementById('weight').value = str2[2];
            document.getElementById('height').value = str2[3];
            document.getElementById('BMI').value = str2[4];
            //}

      }
    }


    
     function printDischargeSummary(encounterNr)
      {
          //alert('Test Test');
          urlholder="../nursing/dischargeSummary.php?en="+encounterNr;
          window.open(urlholder, "histwin<?php echo $sid ?>", "menubar=yes,width=1000,height=550,resizable=yes,scrollbars=yes");
          //window.location.href=urlholder;
      }
                    
     
    function popRecordHistory(table,pid) {
        urlholder = "./record_history.php<?php //echo URL_REDIRECT_APPEND; ?>//&table=" + table + "&pid=" + pid;
        HISTWIN<?php echo $sid ?> = window.open(urlholder, "histwin<?php echo $sid ?>", "menubar=no,width=400,height=550,resizable=yes,scrollbars=yes");
    }

    function popNotesDetails(n, t) {
        urlholder = "./show_notes_details.php<?php echo URL_REDIRECT_APPEND; ?>&nr=" + n + "&title=" + t + "&ln=<?php echo $name_last ?>&fn=<?php echo $name_first ?>&bd=<?php echo $date_birth ?>";
        HISTWIN<?php echo $sid ?> = window.open(urlholder, "histwin<?php echo $sid ?>", "menubar=no,width=400,height=550,resizable=yes,scrollbars=yes");
    }

	

<?php require($root_path . 'include/inc_checkdate_lang.php'); ?>

 </script>
<script language="javascript" src="<?php echo $root_path; ?>js/setdatetime.js"></script>
<script language="javascript" src="<?php echo $root_path; ?>js/checkdate.js"></script>
<script language="javascript" src="<?php echo $root_path; ?>js/dtpick_care2x.js"></script>

<?php
if ($parent_admit)
    include($root_path . 'include/inc_js_barcode_wristband_popwin.php');

$sTemp = ob_get_contents();

ob_end_clean();

$smarty->append('JavaScript', $sTemp);

/* Load the tabs */
if ($parent_admit) {
    $tab_bot_line = '#66ee66';
    include('./gui_bridge/default/gui_tabs_patadmit.php');
    $smarty->assign('sTabsFile', 'registration_admission/admit_tabs.tpl');
    $smarty->assign('sClassItem', 'class="adm_item"');
    $smarty->assign('sClassInput', 'class="adm_input"');
} else {
    $tab_bot_line = '#66ee66';
    include('./gui_bridge/default/gui_tabs_patreg.php');
    $smarty->assign('sTabsFile', 'registration_admission/reg_tabs.tpl');
    $smarty->assign('sClassItem', 'class="reg_item"');
    $smarty->assign('sClassInput', 'class="reg_input"');
}

# If encounter is already discharged, show warning

if ($parent_admit && $is_discharged) {

    $smarty->assign('is_discharged', TRUE);
    $smarty->assign('sWarnIcon', "<img " . createComIcon($root_path, 'warn.gif', '0', 'absmiddle') . ">");
    if ($current_encounter)
        $smarty->assign('sDischarged', $LDEncounterClosed);
    else
        $smarty->assign('sDischarged', $LDPatientIsDischarged);
}

if ($parent_admit)
    $smarty->assign('LDCaseNr', $LDAdmitNr);
else
    $smarty->assign('LDCaseNr', $LDRegistrationNr);

if ($parent_admit)
    $smarty->assign('sEncNrPID', $_SESSION['sess_full_en']);
else
    $smarty->assign('sEncNrPID', $_SESSION['sess_full_pid']);

$smarty->assign('img_source', "<img $img_source>");

$smarty->assign('LDTitle', $LDTitle);
$smarty->assign('title', $title);
$smarty->assign('LDLastName', $LDLastName);
$smarty->assign('name_last', $name_last);
$smarty->assign('LDFirstName', $LDFirstName);
$smarty->assign('name_first', $name_first);

# If person is dead show a black cross and assign death date

if ($death_date && $death_date != DBF_NODATE) {
    $smarty->assign('sCrossImg', '<img ' . createComIcon($root_path, 'blackcross_sm.gif', '0') . '>');
    $smarty->assign('sDeathDate', @formatDate2Local($death_date, $date_format));
}

# Set a row span counter, initialize with 7
$iRowSpan = 7;

if ($GLOBAL_CONFIG['patient_name_2_show'] && $name_2) {
    $smarty->assign('LDName2', $LDName2);
    $smarty->assign('name_2', $name_2);
    $iRowSpan++;
}

if ($GLOBAL_CONFIG['patient_name_3_show'] && $name_3) {
    $smarty->assign('LDName3', $LDName3);
    $smarty->assign('name_3', $name_3);
    $iRowSpan++;
}

if ($GLOBAL_CONFIG['patient_name_middle_show'] && $name_middle) {
    $smarty->assign('LDNameMid', $LDNameMid);
    $smarty->assign('name_middle', $name_middle);
    $iRowSpan++;
}

$smarty->assign('sRowSpan', "rowspan=\"$iRowSpan\"");

$smarty->assign('LDBday', $LDBday);
$smarty->assign('sBdayDate', @formatDate2Local($date_birth, $date_format,FALSE,FALSE,'-'));

$smarty->assign('LDSex', $LDSex);
if ($sex == 'm')
    $smarty->assign('sSexType', $LDMale);
elseif ($sex == 'f')
    $smarty->assign('sSexType', $LDFemale);

$smarty->assign('LDBloodGroup', $LDBloodGroup);
if ($blood_group) {
    $buf = 'LD' . $blood_group;
    $smarty->assign('blood_group', $$buf);
}

# Assign common element names

$smarty->assign('LDDate', $LDDate);
$smarty->assign('LDBy', $LDBy);


# If mode is to show the data
//echo 'Rows are '.$rows.' and mode is '.$mode;
if ($mode == 'show') {
    if ($rows) {
        if ($parent_admit)
            $bgimg = 'tableHeaderbg3.gif';
        else
            $bgimg = 'tableHeader_gr.gif';
        $tbg = 'background="' . $root_path . 'gui/img/common/' . $theme_com_icon . '/' . $bgimg . '"';

        $smarty->assign('subtitle', $subtitle);
        $smarty->assign('LDDetails', $LDDetails);

        if (!$parent_admit) {
            $smarty->assign('parent_admit', TRUE);
            $smarty->assign('LDEncounterNr', $LDEncounterNr);
        }

        # Start displaying the data in a list using the "report_row.tpl" template

        $toggle = 0;
        $sRows = '';

        while ($row = $result->FetchRow()) {
            if ($toggle) {
                $sRowClass = 'class="wardlistrow2"';
            } else {
                $sRowClass = 'class="wardlistrow1"';
            }
            $toggle = !$toggle;

            $smarty->assign('sRowClass', $sRowClass);

            if (!empty($row['date']))
                $smarty->assign('sDate', @formatDate2Local($row['date'], $date_format,false,false,'-'));

            $sTemp = '';

            if (!empty($row['notes']))
                $sTemp = hilite(substr($row['notes'], 0, $GLOBAL_CONFIG['notes_preview_maxlen']));
            if (strlen($row['notes']) > $GLOBAL_CONFIG['notes_preview_maxlen'])
                $sTemp = $sTemp . ' [...]';
            $sTemp = $sTemp . '<br>';
            if (!empty($row['short_notes']))
                $sTemp = $sTemp . '[ ' . hilite($row['short_notes']) . ' ]';
            $smarty->assign('sPreview', $sTemp);
            
           # Link to pdf generator
            if($this_type['nr']==3){
                 $topdf = '<a href="javascript:printDischargeSummary(\''.$row['encounter_nr'].'\');"><img '.createComIcon($root_path,'billing_print_out.gif','0','',TRUE).' alt="'.$LDReleasePatient.'"></a>';
                  //$smarty->assign('sDetails', '<a href="javascript:popNotesDetails(\'' . $row['nr'] . '\',\'' . strtr($subtitle, "' ", "???+") . '\',\'' . $this_type['LD_var'] . '\')"><img ' . createComIcon($root_path, 'info3.gif', '0', '', TRUE) . '></a>');
                  $smarty-> assign('sMakePdf', $topdf);
            }else{
                $topdf = '<a href="' . $root_path . 'modules/pdfmaker/emr_generic/report.php' . URL_APPEND . '&enc=' . $row['encounter_nr'] . '&recnr=' . $row['nr'] . '&type_nr=' . $this_type['nr'] . '&LD_var=' . $this_type['LD_var'] . '" target=_blank><img ' . createComIcon($root_path, 'pdf_icon.gif', '0', '', TRUE) . '></a>';
                if (strlen($row['notes']) > $GLOBAL_CONFIG['notes_preview_maxlen']) {
                    $smarty->assign('sDetails', '<a href="javascript:popNotesDetails(\'' . $row['nr'] . '\',\'' . strtr($subtitle, "' ", "???+") . '\',\'' . $this_type['LD_var'] . '\')"><img ' . createComIcon($root_path, 'info3.gif', '0', '', TRUE) . '></a>');
                    $smarty-> assign('sMakePdf', $topdf);
                } elseif (!empty($row['notes'])) {
                    $smarty->assign('sMakePdf', $topdf);
                }
            }
                
            
            if ($row['personell_name'])
                $smarty->assign('sAuthor', $row['personell_name']);

            if (!$parent_admit) {
                $smarty->assign('sEncNr', '<a href="aufnahme_daten_zeigen.php' . URL_APPEND . '&encounter_nr=' . $row['encounter_nr'] . '&origin=patreg_reg">' . $row['encounter_nr'] . '</a>');
            }
            # buffer the row and assign to array
            ob_start();
            $smarty->display('registration_admission/report_row.tpl');
            $sRows = $sRows . ob_get_contents();
            ob_end_clean();
        }
        $smarty->assign('sReportRows', $sRows);
    } else {

        # Else prompt no data available yet.
        
        $smarty->assign('bShowNoRecord', TRUE);
        $smarty->assign('sMascotImg', '<img ' . createMascot($root_path, 'mascot1_r.gif', '0', 'absmiddle') . '>');
        $smarty->assign('norecordyet', $norecordyet);
    } // end of if $rows
} else {

    # Else, mode is new data entry. Show the entry form

    $smarty->assign('bShowEntryForm', TRUE);
    $smarty->assign('bSetAsForm', TRUE);

    # collect Javascript for the form

    ob_start();

    $sTemp = ob_get_contents();
    ob_end_clean();

    $smarty->assign('sFormJavaScript', $sTemp);

    $smarty->assign('sDateInput', '<input type="text" name="date" size=10 maxlength=10 value='. date('d/m/Y').' onFocus="this.select()"  onBlur="IsValidDate(this,\'' . $date_format . '\')" onKeyUp="setDate(this,\'' . $date_format . '\',\'' . $lang . '\')">');

    $sTemp = '<a href="javascript:show_calendar(\'notes_form.date\',\'' . $date_format . '\')">
 						<img ' . createComIcon($root_path, 'show-calendar.gif', '0', 'absmiddle', TRUE) . '></a>
 						<font size=1>[';
    $dfbuffer = "LD_" . strtr($date_format, ".-/", "phs");
    $sTemp = $sTemp . $$dfbuffer . '] </font>';

    $smarty->assign('sDateMiniCalendar', $sTemp);

    $smarty->assign('LDNotes', $LDApplication . ' ' . $LDNotes);
    $smarty->assign('sNotesInput', '<textarea name="notes" id="notes" cols=40 rows=8 wrap="virtual"></textarea>');
    $smarty->assign('LDShortNotes', $LDShortNotes);
    $smarty->assign('sShortNotesInput', '<input type="text" name="short_notes" id="short_notes" size=50 maxlength=25"><span id="htnKnown"></span>');
    $smarty->assign('LDSendCopyTo', $LDSendCopyTo);
    $smarty->assign('sSendCopyInput', '<input type="text" name="send_to_name" size=50 maxlength=60>');

    $smarty->assign('sAuthorInput', '<input type="text" name="personell_name" size=50 maxlength=60 value="' . $_SESSION['sess_user_name'] . '" readonly>');

    $sTemp = '<input type="hidden" name="encounter_nr"  id="encounter_nr" value="' . $_SESSION['sess_en'] . '">
              <input type="hidden" name="pid"  id="pid" value="' . $_SESSION['sess_pid'] . '">
              <input type="hidden" name="modify_id" value="' . $_SESSION['sess_user_name'] . '">
              <input type="hidden" name="create_id" value="' . $_SESSION['sess_user_name'] . '">
              <input type="hidden" id="bp1" value="">
              <input type="hidden" id="bp2" value="">
              <input type="hidden" id="height" value="">
              <input type="hidden" id="weight" value="">
              <input type="hidden" id="BMI" value="">
              <input type="text" id="innitialMode" value="">
              <input type="hidden" name="create_time" value="null">
              <input type="hidden" name="mode" value="create">
              <input type="hidden" name="personell_nr">
              <input type="hidden" name="send_to_pid">
              <input type="hidden" name="type_nr" value="' . $type_nr . '">
              <input type="hidden" name="target" value="' . $target . '">
              <input type="hidden" name="history" value="Created: ' . date('Y-m-d H:i:s') . ' : ' . $_SESSION['sess_user_name'] . "\n" . '">';

    $smarty->assign('sHiddenInputs', $sTemp);

    $smarty->assign('pbSubmit', '<input type="image" ' . createLDImgSrc($root_path, 'savedisc.gif', '0') . '>');
}  // End of if mode

$smarty->assign('sBackIcon', '<img ' . createComIcon($root_path, 'l-arrowgrnlrg.gif', '0', 'absmiddle') . '>');

if ($parent_admit)
    $buf = '&encounter_nr=' . $_SESSION['sess_full_en'];
else
    $buf = '&pid=' . $_SESSION['sess_full_pid'];

$smarty->assign('sBackLink', '<a href="' . $returnfile . URL_APPEND . $buf . '&target=' . $target . '&mode=show&type_nr=' . $type_nr . '">' . $LDBackToOptions . '</a>');

# Type nr 3 = discharge summary/notes
# Type nr 99 = auxilliary notes

//if ($parent_admit && (!$is_discharged || $type_nr == 3 || $type_nr == 99)) {

    $smarty->assign('sNewRecIcon', '<img ' . createComIcon($root_path, 'bul_arrowgrnlrg.gif', '0', 'absmiddle') . '>');
    $smarty->assign('sNewRecLink', '<a href="' . $thisfile . URL_APPEND . '&pid=' . $_SESSION['sess_pid'] . '&target=' . $target . '&mode=new&type_nr=' . $type_nr . '">' . $LDEnterNewRecord . '</a>');
    //$smarty->assign('pbSubmit','<img '.createLDImgSrc($root_path,'savedisc.gif','0').'>');
//}
    $smarty->assign('sNewRecIcon', '<img ' . createComIcon($root_path, 'bul_arrowgrnlrg.gif', '0', 'absmiddle') . '>');
    $smarty->assign('sNewAppointLink', '<a href="' . $root_path . 'modules/registration_admission/show_appointment.php'.URL_APPEND.'&pid=' . $_SESSION['sess_pid'] . '&target=search&mode=new">Schedule New Appointment</a>');
//http://localhost/litein/modules/registration_admission/show_appointment.php?sid=olgblmfi82oto75ror0eccnd96&ntid=false&lang=en&pid=17945&target=search&mode=new

// Buffer the options table

ob_start();
?>
<!- Column for the options table -->
<!-- Load the options table  -->

<div class="vi_data">
    <img <?php echo createComIcon($root_path, 'angle_left_s.gif', 0); ?>>
    <br>
                        <?php echo "$LDNotes $LDAndSym $LDReports $LDTypes" ?>
</div>

<!-- Reports/Notes types table -->

<table cellSpacing=0 cellPadding=0 class="frame" border=0>
    <tbody>
        <tr>
            <td>
                <table cellSpacing=1 cellPadding=2 border=0>
                    <tbody class="submenu" >
                                <?php
                                while (list($x, $v) = each($types)) {
                                    ?>
                            <TR>
                                <td align=center>
                                    <img
                                        <?php
                                        # Type nr 3 = discharge summary/notes
                                        # Type nr 99 = auxilliary notes

                                        if ($parent_admit && (!$is_discharged || $v['nr'] == 3 || $v['nr'] == 99))
                                            echo createComIcon($root_path, 'comments.gif', '0');
                                        else
                                            echo createComIcon($root_path, 'docu_unrd.gif', '0');
                                        ?>>
                                </td>
                                <TD vAlign=top >
                                    <a href="show_notes.php<?php echo URL_APPEND ?>&pn=<?php echo $_SESSION['sess_en'] ?>&pid=<?php echo $_SESSION['sess_en'] ?>&target=<?php echo $target ?>&type_nr=<?php echo $v['nr'] ?>">
    <?php
//    if (isset($$v['LD_var']) && !empty($$v['LD_var']))
        echo $v['name'];
//    else
//        echo $v['name']
        ?>
                                    </a>
                                </TD>
                            </TR>
                            <?php
                        }
                        if ($parent_admit) {
                            ?>
                            <TR>
                                <td align=center>
                                    <img <?php echo createComIcon($root_path, 'icon_acro.gif', '0'); ?>>
                                </td>
                                <TD vAlign=top >
                                    <a href="<?php echo $root_path . "modules/pdfmaker/emr_generic/report_all.php" . URL_APPEND . "&enc=" . $_SESSION['sess_en']; ?>" target=_blank>
    <?php
    echo $LDPrintPDFDocAllReport;
    ?>
                                    </a>
                                </TD>
                            </TR>
        <?php
    }
    ?>
                    </TBODY>
                </TABLE>
            </TD>
        </TR>
    </TBODY>
</TABLE>

<!-- End of main data block table -->

<p>

    <?php
    $sTemp = ob_get_contents();
    ob_end_clean();

    $smarty->assign('sOptionsMenu', $sTemp);

# Now buffer the bottom controls

    ob_start();

    if ($parent_admit) {
        include('./include/bottom_controls_admission_options.inc.php');
    } else {
        include('./include/bottom_controls_registration_options.inc.php');
    }

    $sTemp = ob_get_contents();
    ob_end_clean();

    $smarty->assign('sBottomControls', $sTemp);

    $smarty->assign('pbCancel', '<a href="' . $returnfile . URL_APPEND . $buf . '&target=' . $target . '&mode=show&type_nr=' . $type_nr . '"><img ' . createLDImgSrc($root_path, 'cancel.gif', '0') . ' alt="' . $LDCancelClose . '"></a>');

    $smarty->assign('sMainBlockIncludeFile', 'registration_admission/common_report.tpl');

    $smarty->display('common/mainframe.tpl');
    ?>
