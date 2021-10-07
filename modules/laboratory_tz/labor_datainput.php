<?php
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require ('./roots.php');
require ($root_path . 'include/inc_environment_global.php');
define(COL_MAX, 6); # define here the maximum number of rows for displaying the parameters
/**
 * CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
 * GNU General Public License
 * Copyright 2002,2003,2004,2005 Elpidio Latorilla
 * elpidio@care2x.org,
 *
 * See the file "copy_notice.txt" for the licence notice
 */
$lang_tables = array('chemlab_groups.php', 'chemlab_params.php');
define('LANG_FILE', 'lab.php');
$local_user = 'ck_lab_user';
require_once ($root_path . 'include/inc_front_chain_lang.php');

$debug = false;
($debug) ? $db->debug = true : $db->debug = FALSE;

if (!$encounter_nr) {
    header('Location:' . $root_path . 'language/' . $lang . '/lang_' . $lang . '_invalid-access-warning.php');
    exit();
}

if (!isset($user_origin) || empty($user_origin))
    $user_origin = 'lab';

# Create encounter object
require_once ($root_path . 'include/care_api_classes/class_encounter.php');
$encounter = new Encounter($encounter_nr);
$enc_obj = new Encounter($encounter_nr);
if ($encounter = &$enc_obj->getBasic4Data($encounter_nr)) {
    $patient = $encounter->FetchRow();
}

$enc_obj->loadEncounterData($encounter_nr);
$pid = $enc_obj->PID();

//echo $pid;



$thisfile = 'labor_datainput.php';

# Create lab object
require_once ($root_path . 'include/care_api_classes/class_lab.php');
$lab_obj = new Lab($encounter_nr);
$lab_obj_sub = new Lab($encounter_nr, true);

//to avoid reinserting allready done analysis
if ($result = $lab_obj->getResult($job_id, $parameterselect)) {
    while ($row = $result->FetchRow()) {
        $batch_nr = $row['batch_nr'];
        $pdata[$row['paramater_name']] = $row['parameter_value'];
    }
}

if (!empty($pdata))
    $allow_update = TRUE;
else
    $allow_update = FALSE;
# Load the date formatter
include_once ($root_path . 'include/inc_date_format_functions.php');

if ($debug) {
    echo "Parameterselect is set to:" . $parameterselect . "<br>";
    echo "mode is set to:" . $mode . "<br>";
    echo "allow update is set to:" . $allow_update . "<br>";
    echo "job id is set to:" . $job_id . "<br>";
    echo "encounter number is: " . $encounter_nr . "<br>";
}

if ($mode == 'save') {
    $nbuf = array();
    //Prepare parameter values
    //gjergji  group
    $groupCount = 0;

    foreach($_POST AS $z=>$y){
    //while (list($z, $y) = each($_POST)) {
        if (substr($z, 0, 5) == 'group') {
            $strz= explode('-',$z) ;
            $arr=array($strz[1]);
            
            foreach($arr as $items){
                $nTestResult= $lab_obj->getTestParam($items);
                $nTestRow=$nTestResult->FetchRow();
                $nTest= $nTestRow[id];
                
            }    
            $arr2=array($strz[2]);
            foreach($arr2 as $items2){
                $nbuf[$z] .= $y;
//                echo $nbuf[$z];
            }

        }
        if ($result_tests = $lab_obj->GetTestsToDo($job_id))
            while ($row_tests = $result_tests->FetchRow()) {
                if ($z == $row_tests['paramater_name']) {
                    $nbuf[$z] = $y;
                }
            }
    }

    $dbuf['job_id'] = $job_id;
    $dbuf['encounter_nr'] = $encounter_nr;
    if ($allow_update == TRUE) {
        $dbuf['modify_id'] = $_SESSION['sess_user_name'];
        $dbuf['modify_time'] = date('YmdHis');
        $dbuf['status'] = 'pending';
        
        # Recheck the date, ! bug pat	$dbuf['modify_id']=$_SESSION['sess_user_name'];
        if ($_POST['std_date'] == DBF_NODATE)
            $dbuf['test_date'] = date('Y-m-d');
        $lab_obj_sub->deleteOldValues($batch_nr, $encounter_nr);
        foreach ($nbuf as $key => $value) {
            if (isset($value) && !empty($value)) {

                //if (substr($key,0,5) == 'group') {
                   // $key = $nTest;
                //}

                $parsedParamList['test_date'] = date('Y-m-d');
                $parsedParamList['batch_nr'] = $batch_nr;
                $parsedParamList['job_id'] = $job_id;
                $parsedParamList['encounter_nr'] = $encounter_nr;
                $parsedParamList['paramater_name'] = $key;
                $parsedParamList['parameter_value'] = $value;
                $parsedParamList['test_time'] = date('H:i:s');
                $parsedParamList['history'] = "Create " . date('Y-m-d H:i:s') . " " . $_SESSION['sess_user_name'] . "\n";
                $parsedParamList['create_id'] = $_SESSION['sess_user_name'];
                $parsedParamList['create_time'] = date('YmdHis');
                $parsedParamList['examinationDate'] = $examinationDate;
                $parsedParamList['collectionDate'] = $collectionDate;
                $parsedParamList['receivedDate'] = $receivedDate;
                $parsedParamList['reportingDate'] = $reportingDate;
                $parsedParamList['doneBy'] = $doneBy;
                $parsedParamList['reviewedBy'] = $reviewedBy;
                
                $lab_obj_sub->setDataArray($parsedParamList);
                if ($lab_obj_sub->insertDataFromInternalArray()) {
                    $saved = TRUE;
                } else {
                    echo "<p>" . $lab_obj->getLastQuery() . "$LDDbNoSave";
                }
            }
        }


        # If save successful, jump to display values
        if ($saved) {
          ?>
            <script>
               window.open(../registration_admission/labResultsPDF.php?pid=+<?php echo $pid;?>+'&encounterNr='+<?php echo $encounter_nr;?>,
                "Laboratory Results","menubar=yes,toolbar=yes,width=500,height=550,location=yes,resizable=no,scrollbars=yes,status=yes");
           </script><?php
        
//            include_once ($root_path . 'include/inc_visual_signalling_fx.php');
//            # Set the visual signal
//            setEventSignalColor($encounter_nr, SIGNAL_COLOR_DIAGNOSTICS_REPORT);
//            //header("location:$thisfile?sid=$sid&lang=$lang&saved=1&batch_nr=$batch_nr&encounter_nr=$encounter_nr&job_id=$job_id&parameterselect=$parameterselect&allow_update=1&user_origin=$user_origin&tickerror=$tickerror");
//            header("location:labor_test_request_admin_chemlabor.php?sid=$sid");
//            exit;
        }
    } else {
        $dbuf['test_date'] = formatDate2STD($_POST['test_date'], $date_format,false,false,'-');
        $dbuf['test_time'] = date('H:i:s');
        $dbuf['status'] = 'pending';
        $dbuf['history'] = "Create " . date('Y-m-d H:i:s') . " " . $_SESSION['sess_user_name'] . "\n";
        $dbuf['create_id'] = $_SESSION['sess_user_name'];
        $dbuf['create_time'] = date('YmdHis');
//        $dbuf['ExaminationDate'] = $examinationDate;
//        $dbuf['CollectionDate'] = $collectionDate;
//        $dbuf['ReceivedDate'] = $receivedDate;
//        $dbuf['ReportingDate'] = $reportingDate;
//        $dbuf['DoneBy'] = $doneBy;
//        $dbuf['ReviewedBy'] = $reviewedBy;

        # Insert new job record
        $lab_obj->setDataArray($dbuf);
        if ($lab_obj->insertDataFromInternalArray()) {
            $pk_nr = $db->Insert_ID();
            $batch_nr = $lab_obj->LastInsertPK('batch_nr', $pk_nr);
            foreach ($nbuf as $key => $value) {
                if (isset($value) && !empty($value)) {
                    
//                    if (substr($key,0,5) == 'group') {
//                        $key = $nTest;
//                    }
                    
                    $parsedParamList['batch_nr'] = $batch_nr;
                    $parsedParamList['encounter_nr'] = $encounter_nr;
                    $parsedParamList['job_id'] = $job_id;
                    $parsedParamList['paramater_name'] = $key;
                    $parsedParamList['parameter_value'] = $value;
                    $parsedParamList['examinationDate'] = $examinationDate;
                    $parsedParamList['collectionDate'] = $collectionDate;
                    $parsedParamList['receivedDate'] = $receivedDate;
                    $parsedParamList['reportingDate'] = $reportingDate;
                    $parsedParamList['doneBy'] = $doneBy;
                    $parsedParamList['reviewedBy'] = $reviewedBy;
                
                    $lab_obj_sub->setDataArray($parsedParamList);
                    $lab_obj_sub->insertDataFromInternalArray();
                }
            }
            $saved = true;
               
        } else {
            echo "<p>" . $lab_obj->getLastQuery() . "$LDDbNoSave";
        }
    }
    # If save successful, jump to display values
    }
 
    if ($saved) {
//        print "<script>";
//              print "window.open('../registration_admission/labResultsPDF.php?pid=$pid&encounterNr=$encounter_nr,";
//              print "'Laboratory Results,menubar=yes,toolbar=yes,width=500,height=550,location=yes,resizable=no,scrollbars=yes,status=yes')";
//        print "</script>";
//        header("location:../registration_admission/labResultsPDF.php?pid=$pid&encounterNr=$encounter_nr");
//        include_once($root_path . 'include/inc_visual_signalling_fx.php');
//        # Set the visual signal
//        setEventSignalColor($encounter_nr, SIGNAL_COLOR_DIAGNOSTICS_REPORT);
//        header("location:labor_test_request_admin_chemlabor.php?sid=$sid");
//        exit;
               
        
         
# end of if(mode==save)
} else { #If mode is not "save" then get the basic personal data
    if ($debug)
        echo "mode is not save then get the basic personal data<br>";

    # If previously saved, get the values
    if ($saved) {
        if ($result = &$lab_obj->getBatchResult($batch_nr)) {
            while ($row = $result->FetchRow()) {
                $pdata[$row['paramater_name']] = $row['parameter_value'];
            }
        }
    } else {
        if ($result = &$lab_obj->getResult($job_id, $parameterselect)) {
            while ($row = $result->FetchRow()) {
                $pdata[$row['paramater_name']] = $row['parameter_value'];
            }
        } else {
            # disallow update if group does not exist yet
            $allow_update = false;
        }
    }


    //echo $lab_obj->getLastQuery();
    # Get the test test groups
    $tgroups = &$lab_obj->TestActiveGroups();
    # Get the test parameter values
    //gjergji : take all the params for this group...
    $tparams = &$lab_obj->TestParams();

    # Set the return file
    if (isset($job_id) && $job_id) {
        switch ($user_origin) {
            case 'lab_mgmt' :
                $breakfile = "labor_test_request_admin_chemlabor.php" . URL_APPEND . "&pn=$encounter_nr&batch_nr=$job_id&user_origin=lab";
                break;
            default :
                //$breakfile="labor_data_check_arch.php".URL_APPEND."&versand=1&encounter_nr=$encounter_nr";
                $breakfile = "labor.php";
        }
    } else {
        $breakfile = "labor_data_patient_such.php" . URL_APPEND . "&mode=edit";
    }
}

# Prepare title
if ($update)
    $sTitle = "$LDLabReport - $LDEdit";
else
    $sTitle = "$LDNew $LDLabReport";

# Start Smarty templating here
/**
 * LOAD Smarty
 */
# Note: it is advisable to load this after the inc_front_chain_lang.php so
# that the smarty script can use the user configured template theme


require_once ($root_path . 'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care('common');

# Title in toolbar
$smarty->assign('sToolbarTitle', $sTitle);

# href for help button
$smarty->assign('pbHelp', "javascript:gethelp('lab_report_edit.php','Laboratories :: Lab Report Edit','main','$job_id')");

# hide return  button
$smarty->assign('pbBack', FALSE);

# href for close button
$smarty->assign('breakfile', $breakfile);

# Window bar title
$smarty->assign('sWindowTitle', $sTitle);

# collect extra javascript code
ob_start();
?>

<style type="text/css" name="1">
    .va12_n {
        font-family: verdana, arial;
        font-size: 12;
        color: #000099
    }

    .a10_b {
        font-family: arial;
        font-size: 10;
        color: #000000
    }

    .a10_n {
        font-family: arial;
        font-size: 10;
        color: #000099
    }

    .pg1{
        border-top: solid;border-bottom: solid;border-left: solid;border-right: solid;border-width: thin;
        background-color:#b0ccf2;
    }

    .pg2{
        border-top: solid;border-bottom: solid;border-left: solid;border-right: solid;border-width: thin;
        width:400px; ;background-color:#b0ccf2;
    }

    .testName{
        font-size:medium ;color: #800000;font-weight: bold;
    }

</style>

<script language="javascript" name="j1">
<!--
    function pruf(d)
    {
        if (!d.job_id.value)
        {
            alert("<?php echo $LDAlertJobId ?>");
            d.job_id.focus();
            return false;
        }
        else
        {
            if (d.test_date) {
                if (!d.test_date.value)
                {
                    alert("<?php echo $LDAlertTestDate ?>");
                    d.test_date.focus();
                    return false;
                }
                else
                    return true;
            }
        }
    }

    function posneg(f)
    {
        //if(d."<?php echo $adddata [$tp ['id']] ?>[0].checked || d."<?php echo $adddata [$tp ['id']] ?>"[1].checked)
        //{
        // alert(<?php
echo $_POST ['_add' . $x . '_'];
?>);
        //return false;
        //}
        //else return true;

    }

    function limitedInput(inputId, range) {
        var inputElement = document.getElementById(inputId);
        var rangeArray = range.split("-");
        if (inputElement.value != '') {
            if (Number(inputElement.value).toFixed(6) < Number(rangeArray[0]).toFixed(6) ||
                    Number(inputElement.value).toFixed(6) > Number(rangeArray[1]).toFixed(6)) {
                alert('Value must be between ranges : ' + rangeArray[0] + ' and ' + rangeArray[1] + '!');
                inputElement.value = '';
            }
        }

    }


    function chkselect(d)
    {
        if (d.parameterselect.value == "<?php echo $parameterselect ?>") {
            return false;
        }
    }

    function printReport(pid,encounter_nr) {
        window.open('../registration_admission/labResultsPDF.php?pid='+pid+'&encounterNr='+encounter_nr,
            "Laboratory Results","menubar=yes,toolbar=yes,width=500,height=550,location=yes,resizable=no,scrollbars=yes,status=yes");

    }

    function labReport() {
        window.location.replace("<?php
echo 'labor_datalist_noedit.php' . URL_REDIRECT_APPEND . '&encounter_nr=' . $encounter_nr . '&noexpand=1&from=input&job_id=' . $job_id . '&parameterselect=' . $parameterselect . '&allow_update=' . $allow_update . '&nostat=1&user_origin=lab';
?>");
    }
<?php
require ($root_path . 'include/inc_checkdate_lang.php');
?>
// -->
</script>
<script language="javascript"
src="<?php echo $root_path ?>js/checkdate.js" type="text/javascript"></script>
<script language="javascript"
src="<?php echo $root_path ?>js/setdatetime.js"></script>
<script language="javascript"
src="<?php echo $root_path; ?>js/dtpick_care2x.js"></script>

<?php
$sTemp = ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript', $sTemp);

# Assign patient basic elements
$smarty->assign('LDCaseNr', $LDPatientID);
$smarty->assign('LDLastName', $LDLastName);
$smarty->assign('LDName', $LDName);
$smarty->assign('LDBday', $LDBday);
$smarty->assign('LDJobIdNr', $LDJobIdNr);
$smarty->assign('LDExamDate', $LDExamDate);

# Assign patient basic data
//$smarty->assign ( 'encounter_nr', $encounter_nr );
$smarty->assign('sPID', $pid);
$smarty->assign('sLastName', $patient ['name_last']);
$smarty->assign('sName', $patient ['name_first']);
$smarty->assign('sBday', formatDate2Local($patient ['date_birth'], $date_format,false,false,'-'));

$smarty->assign('sJobIdNr', $job_id . '<input type=hidden name=job_id value="' . $job_id . '">');
$smarty->assign('sExamDate', '<input name="examinationDate" type="text" size="14" value="'.$examinationDate.'" onBlur="IsValidDate(this,\'' . $date_format . '\')")  onKeyUp="setDate(this,\'' . $date_format . '\',\'' . $lang . '\')">');

$smarty->assign('sCollectionDate', '<input name="collectionDate" type="text" size="14" value="' . $collectionDate . '" onBlur="IsValidDate(this,\'' . $date_format . '\')")  onKeyUp="setDate(this,\'' . $date_format . '\',\'' . $lang . '\')">');

$smarty->assign('sReceivedDate', '<input name="receivedDate" type="text" size="20" value="' . $receivedDate . '" onKeyUp="setDate(this,\'' . $date_format . '\',\'' . $lang . '\')">');

$smarty->assign('sReportDate', '<input name="reportingDate" type="text" size="20" value="' . $reportingDate . '" onKeyUp="setDate(this,\'' . $date_format . '\',\'' . $lang . '\')">');

$smarty->assign('sMiniCalendar', "<a href=\"javascript:show_calendar('datain.test_date','$date_format')\"><img " . createComIcon($root_path, 'show-calendar.gif', '0', 'absmiddle') . "></a>");

$smarty->assign('sHistory', '<textarea rows=4 cols=50 name=history value="' . $history . '"></textarea>');

# Assign parameter elements
//$smarty->assign ( 'sParamGroup', strtr ( $parametergruppe [$parameterselect], "_", "-" ) );

$smarty->assign('pbSave', '<input  type="image" ' . createLDImgSrc($root_path, 'send.gif', '0') . ' >');
//$smarty->assign('pbShowReport', '<a href="labor_datalist_noedit.php' . URL_APPEND . '&encounter_nr=' . $encounter_nr . '&noexpand=1&from=input&job_id=' . $job_id . '&parameterselect=' . $parameterselect . '&allow_update=' . $allow_update . '&nostat=1&user_origin=' . $user_origin . '"><img ' . createLDImgSrc($root_path, 'showreport.gif', '0', 'absmiddle') . ' alt="' . $LDClk2See . '"></a>');
$smarty->assign('pbCompleteTest', '<button onclick="completeTest('.$pid.','.$encounter_nr.')">Complete Test</button>');
$smarty->assign('pbShowReport', '<button onclick="printReport('.$pid.','.$encounter_nr.')">Print Report</button>');

if ($saved || $update)
    $sCancelBut = '<img ' . createLDImgSrc($root_path, 'close2.gif', '0', 'absmiddle') . '>';
else
    $sCancelBut = '<img  ' . createLDImgSrc($root_path, 'cancel.gif', '0', 'absmiddle') . '>';

//$smarty->assign('pbCancel',"<a href=\"$breakfile\">$sCancelBut</a>");
$smarty->assign('pbCancel', "<a href=\"$breakfile\">$sCancelBut</a>");

$smarty->assign('sAskIcon', "<img " . createComIcon($root_path, 'small_help.gif', '0') . ">");

$smarty->assign('sFormAction', $thisfile);

# Buffer parameter items generation


ob_start();
?>

<?php
if ($tickerror > 0) :
    ?>
    <tr bgcolor=#ffffee>
        <td colspan=4>
    <center><font face=arial color=#7700ff size=4> An error occured! Please
        be sure to insert valid values and also fill out the text boxes right
        beside the checkboxes!</font> </center>
    </td>
    </tr>

<?php endif;
?>


<?php
$paramnum = sizeof($parameters);
//$pcols = ceil ( $paramnum / ROW_MAX );

if ($result_tests = $lab_obj->GetTestsToDo2($job_id)) {

    while ($tRow = $result_tests->FetchRow()) {
        echo "<tr class='pg1'>";
        echo "<td valign='top' class='testName'>$tRow[testName]</td> <td>";
        $parId = $tRow[id];
        //echo $parId;
        if ($tRow[field_type] == 'input_box') {
            echo "<input type='text' name='$parId' id='$parId' value='$pdata[$parId]' />";
        }else if($tRow[field_type]=='TEXT_AREA'){
            echo "<td><textarea name='$parId' id='$parId' rows='3' cols='20'/>$pdata[$parId]</textarea></td>";
        } else if ($tRow[field_type] == 'drop_down') {
            echo "<select name='$parId'>
                       <option value=''></option>";
            $str = explode(",", $tRow[add_type]);
            $total = count($str);
            for ($i = 0; $i <= $total; $i++) {
                echo "<option value=$str[$i]";
                        if ($str[$i] == $pdata[$parId])
                                 echo ' selected="selected" ';
                echo ">$str[$i]</option>";
            }
           
            
            echo "</select>";
        } else if ($tRow[field_type] == 'group_field') {
            if ($result_Params = $lab_obj->GetResultParams($tRow[item_id])) {
                echo "<table class='pg2'>";
                echo "<tr><td>Field</td><td>Value</td><td>Normal</td><td>Ranges</td></tr>";

                while ($pRows = $result_Params->FetchRow()) {
                    $parId2 = 'group-' . $pRows[item_id] . '-' . $pRows[resultID];
                    echo "<tr><td>$pRows[results]</td>";

                    if ($pRows[input_type] == 'INPUT_BOX') {
                        echo "<td><input type='text' name='$parId2' id='$parId2' value='$pdata[$parId2]' size=10 /></td>";
                    }else if($pRows[input_type]=='TITLE'){
                        echo "<td><b>$pRows[results]</b></td>";
                    }else if($pRows[input_type]=='TEXT_AREA'){
                        echo "<td><textarea name='$parId2' id='$parId2' rows='3' cols='20'/>$pdata[$parId2]</textarea></td>";
                    }else if ($pRows[input_type] == 'COMBO_BOX') {
                        echo "<td><select name='$parId2'>
                                    <option value=''></option>";
                        $str = explode(",", $pRows[result_values]);
                        $total = count($str);
                        for ($i = 0; $i <= $total; $i++) {
                           // echo"<option value=$str[$i]>$str[$i]</option>";
                           echo "<option value=$str[$i]";
                            if ($str[$i] == $pdata[$parId2])
                                 echo ' selected="selected" ';
                            echo ">$str[$i]</option>";
                        }
                       
                        echo "</select><td>";
                    }
                    echo "<td>$pRows[normal]</td><td>$pRows[ranges]</td></tr>";
                }
                echo "</table>";
            }
        }
        echo "</td><tr>";
    }
    echo '</tr>';
	echo "<tr><td colspan=2> &nbsp;</td></tr>";
            function getStaff(){
                global $db;
                $debug=false;

                $sql="Select id,staff_name from care_ke_staff";
                if($debug) echo $sql;
                $result=$db->Execute($sql);

                return $result;
            }
	echo "<tr><td>Done By</td><td><select name=doneBy><option value=''></option>";
                getStaff();
                
               $sql="Select id,staff_name from care_ke_staff";
                if($debug) echo $sql;
                $result=$db->Execute($sql);
                    while($row=$result->FetchRow()){
                        if (($doneBy==$row[id]))
					$selected = 'selected="selected"';
				else
					$selected = '';

                        echo "<option value='$row[id]' $selected>$row[staff_name]</option>";
                    }
                    
	echo "	</select></td></tr>";
	echo "<tr><td colspan=2> &nbsp;</td></tr>";
	echo "<tr><td>Reviewd By</td><td><select name=reviewedBy><option value=''></option>";
                getStaff();
                
               $sql="Select id,staff_name from care_ke_staff";
                if($debug) echo $sql;
                $result=$db->Execute($sql);
                    while($row=$result->FetchRow()){
                        if (($reviewedBy==$row[id]))
					$selected = 'selected="selected"';
				else
					$selected = '';
                        echo "<option value='$row[id]' $selected>$row[staff_name]</option>";
                    }
                    
	echo "	</select></td></tr>";
}

$sTemp = ob_get_contents();
ob_end_clean();

$smarty->assign('sParameters', $sTemp);

# Collect hidden inputs for the parameters form


ob_start();
?>

<input type=hidden name="parameterselect" value=<?php echo $parameterselect; ?>>
<input type=hidden name="encounter_nr" value="<?php echo $encounter_nr; ?>">
<input type=hidden name="sid" value="<?php echo $sid; ?>">
<input type=hidden name="lang" value="<?php echo $lang; ?>">
<input type=hidden name="update" value="<?php echo $update; ?>">
<input type=hidden name="allow_update" 	value="<?php if (isset($allow_update)) echo $allow_update; ?>">
<input type=hidden name="batch_nr" value="<?php if (isset($row ['batch_nr'])) echo $row ['batch_nr']; ?>">
<input type=hidden name="newid" value="<?php echo $newid; ?>">
<input type=hidden name="user_origin" value="<?php echo $user_origin; ?>">
<input type=hidden name="mode" value="save">
<?php
$sTemp = ob_get_contents();
ob_end_clean();
$smarty->assign('sSaveParamHiddenInputs', $sTemp);

# Assign help items
$smarty->assign('LDParamNoSee', "<a href=\"Javascript:gethelp('lab.php','input','param')\">$LDParamNoSee</a>");
$smarty->assign('LDOnlyPair', "<a href=\"Javascript:gethelp('lab.php','input','few')\">$LDOnlyPair</a>");
$smarty->assign('LDHow2Save', "<a href=\"Javascript:gethelp('lab.php','input','save')\">$LDHow2Save</a>");
$smarty->assign('LDWrongValueHow', "<a href=\"Javascript:gethelp('lab.php','input','correct')\">$LDWrongValueHow</a>");
$smarty->assign('LDVal2Note', "<a href=\"Javascript:gethelp('lab.php','input','note')\">$LDVal2Note</a>");
$smarty->assign('LDImDone', "<a href=\"Javascript:gethelp('lab.php','input','done')\">$LDImDone</a>");

# Assign the include file to mainframe


$smarty->assign('sMainBlockIncludeFile', 'laboratory/chemlab_data_results.tpl');;

/**
 * show Template
 */
$smarty->display('common/mainframe.tpl');
?>
