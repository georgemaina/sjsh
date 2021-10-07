<?php

//error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
error_reporting(E_ALL);
require ('./roots.php');
require ($root_path . 'include/inc_environment_global.php');
define('COL_MAX', 6); # define here the maximum number of rows for displaying the parameters
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

$allowedarea = $allow_area['lab_r'];

# Create encounter object
require_once ($root_path . 'include/care_api_classes/class_encounter.php');
$enc_obj = new Encounter($encounter_nr);
if ($encounter = $enc_obj->getBasic4Data($encounter_nr)) {
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

if (!empty($pdata) ) $allow_update = TRUE; else $allow_update = FALSE;
if ($mode == 'save') {
    $nbuf = array();
    //Prepare parameter values
    //gjergji  group
    $groupCount = 0;
//    foreach ($_POST as $z=>$y) {
//   //    while (list($z, $y) = each($_POST)) {
//        if (substr($z, 0, 5) == 'group') {
//            $strz= explode('-',$z) ;
//            $arr=array($strz[1]);
//
//            foreach($arr as $items){
//                $nTestResult= $lab_obj->getTestParam($items);
//                $nTestRow=$nTestResult->FetchRow();
//                $nTest= $nTestRow[id];
//
//            }
//            $arr2=array($strz[2]);
//            foreach($arr2 as $items2){
//                $nbuf[$z] .= $y;
////                echo $nbuf[$z];
//            }
//
//        }
//        if ($result_tests = $lab_obj->GetTestsToDo($job_id))
//            while ($row_tests = $result_tests->FetchRow()) {
//                if ($z == $row_tests['paramater_name']) {
//                    $nbuf[$z] = $y;
//                }
//            }
//    }

    foreach ($_POST as $z=>$y) {
    //while (list($z,$y)=each($_POST)) {
        if($result_tests = $lab_obj->GetTestsToDo($job_id))
            while($row_tests = $result_tests->FetchRow()) {
                if ($z == $row_tests['paramater_name'] ) {
                    $nbuf[$z]=$y;
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
                $parsedParamList['test_date'] = date('Y-m-d');
                $parsedParamList['test_time'] = date('H:i:s');
                $parsedParamList['history'] = "Create " . date('Y-m-d H:i:s') . " " . $_SESSION['sess_user_name'] . "\n";
                $parsedParamList['create_id'] = $_SESSION['sess_user_name'];
                $parsedParamList['create_time'] = date('YmdHis');
				$parsedParamList['clinical_history'] = $clinical_history;
				$parsedParamList['comments'] = $comments;
//                $examDate=new DateTime($examinationDate." ".date('H:i:s'));
//                $parsedParamList['examinationDate'] = $examDate->format('Y-m-d H:i:s');
//                $collectDate=new DateTime($collectionDate);
//                $parsedParamList['collectionDate'] = $collectDate->format('Y-m-d H:i:s');
//                $receivedate=new DatetTime($receivedDate);
//                $parsedParamList['receivedDate'] = $receivedate->format('Y-m-d H:i:s');
//                $reportdate=new DateTime($reportingDate);
//                $parsedParamList['reportingDate'] = $reportdate->format('Y-m-d H:i:s');
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
               window.open("../registration_admission/labResultsPDF.php?pid="<?php echo $pid;?>+"&encounterNr="+<?php echo $encounter_nr;?>,
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
        $dbuf['test_date'] = date('Y-m-d');
        $dbuf['test_time'] = date('H:i:s');
        $dbuf['status'] = 'pending';
        $dbuf['history'] = "Create " . date('Y-m-d H:i:s') . " " . $_SESSION['sess_user_name'] . "\n";
        $dbuf['create_id'] = $_SESSION['sess_user_name'];
        $dbuf['create_time'] = date('YmdHis');
        $dbuf['ExaminationDate'] = $examinationDate;
        $dbuf['CollectionDate'] = $collectionDate;
        $dbuf['ReceivedDate'] = $receivedDate;
        $dbuf['ReportingDate'] = $reportingDate;
		$dbuf['clinical_history'] = $clinical_history;
		$dbuf['comments'] = $comments;
        $dbuf['DoneBy'] = $doneBy;
        $dbuf['ReviewedBy'] = $reviewedBy;

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
                    $parsedParamList['reportingDate'] = $receivedDate;
					$parsedParamList['clinical_history'] = $clinical_history;
					$parsedParamList['comments'] = $comments;
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
         print "<script>";
               print "window.open('../registration_admission/labResultsPDF.php?pid=$pid&encounterNr=$encounter_nr,";
               print "'Laboratory Results,menubar=yes,toolbar=yes,width=500,height=550,location=yes,resizable=no,scrollbars=yes,status=yes')";
         print "</script>";
//        header("location:../registration_admission/labResultsPDF.php?pid=$pid&encounterNr=$encounter_nr");
//        include_once($root_path . 'include/inc_visual_signalling_fx.php');
//        # Set the visual signal
//        setEventSignalColor($encounter_nr, SIGNAL_COLOR_DIAGNOSTICS_REPORT);
       // header("location:labor_test_request_admin_chemlabor.php?sid=$sid");
      // exit;
               
        
         
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

?>
<script>
    var Ext = Ext || {};
    Ext.theme = {
        name: ""
    };
</script>
<script src="../../../ext-6/build/ext-all.js"></script>
<script src="../../../ext-6/build/classic/theme-classic/theme-classic.js"></script>
<link rel="stylesheet" href="../../../ext-6/build/classic/theme-classic/resources/theme-classic-all.css">

<link rel="stylesheet" href="laboratory.css">

<script language="javascript" name="j1">


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

//var xmlHttp = createXmlHttpRequestObject();
// retrieves the XMLHttpRequest object
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

function updateCollectionDate(jobNo,item_id){
   // alert('Test test');
    xmlhttp=GetXmlHttpObject();
    if (xmlhttp==null)
    {
        alert ("Browser does not support HTTP Request");
        return;
    }
    var url="getLaboratoryData.php?task=updateCollectionDate";
    url=url+"&sid="+Math.random();
    url=url+"&jobNo="+jobNo;
    url=url+"&item_id="+item_id;
    xmlhttp.onreadystatechange=sampleStatus;
    xmlhttp.open("POST",url,true);
    xmlhttp.send(null);
}

function updateReceptionDate(jobNo,item_id){
    // alert('Test test');
    xmlhttp=GetXmlHttpObject();
    if (xmlhttp==null)
    {
        alert ("Browser does not support HTTP Request");
        return;
    }
    var url="getLaboratoryData.php?task=updateReceptionDate";
    url=url+"&sid="+Math.random();
    url=url+"&jobNo="+jobNo;
    url=url+"&item_id="+item_id;
    xmlhttp.onreadystatechange=sampleStatus;
    xmlhttp.open("POST",url,true);
    xmlhttp.send(null);
}

function sampleStatus()
{
    //get payment description
    if (xmlhttp.readyState==4)//show point desc
    {
        var str=xmlhttp.responseText;
        alert(str);
        //document.getElementById("colImage").style.visibility = "hidden";
      //  document.getElementById("recImage").style.visibility = "hidden";
    }
}

Ext.require([
    'Ext.form.field.Date'
]);

Ext.onReady(function() {
    Ext.create('Ext.field.DatePicker', {
        label: 'Examination Date',
        value: new Date(),
        renderTo: 'examDate'
    });
});

</script>

<form action="<?php echo $thisfile; ?>">

<table class="main">
    <th class="txtheading" colspan="4"> <?php echo $sTitle; ?></th>
    <tr class='pg1'>
        <td colspan="4">
            <?php
           echo "<table>
                <tr class='pg1'><td> PID </td><td> $patient[pid]</td></tr>
                <tr class='pg1'><td> Encounter No </td><td> $encounter_nr</td></tr>
                <tr class='pg1'><td> Patient Name </td><td> $patient[name_first] $patient[name_last]</td></tr>
                <tr class='pg1'><td> Date of Birth </td><td> $patient[date_birth]</td></tr>
                <tr class='pg1'><td> Job No </td><td> $job_id .<input type=hidden name=job_id value=$job_id></td></tr>
                <tr class='pg1'><td> Examimation Date </td><td><div id=examDate></div></td></tr>
                <tr class='pg1'><td> Report Date </td><td id=collecDate></td></tr>
                <tr class='pg1'><td> History</td><td id='history'></td></tr>
            </table>";
            ?>
        </td>
    </tr>

<?php



if ($result_tests = $lab_obj->GetTestsToDo2($job_id)) {
    function checkSampleStatus($strDate,$job_id,$item_id){
        global $db;
        $debug=false;

        $sql="Select $strDate from care_test_request_chemlabor_sub where batch_nr='$job_id' and item_id='$item_id'";
        if($debug) echo $sql;
        $results=$db->Execute($sql);
        $row=$results->FetchRow();

        return $row[0];
    }


    while ($tRow = $result_tests->FetchRow()) {
        echo "<tr class='pg1'>";
        echo "<td valign='top' class='testName'>$tRow[testName]</td> <td>";
        $parId = $tRow['id'];
        //echo $parId;
        if ($tRow['field_type'] == 'input_box') {
            echo "<input type='text' name='$parId' id='$parId' value='$pdata[$parId]' />";
        }else if($tRow['field_type']=='TEXT_AREA'){
            echo "<td><textarea name='$parId' id='$parId' rows='3' cols='20'/>$pdata[$parId]</textarea></td>";
        } else if ($tRow['field_type'] == 'drop_down') {
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
        } else if ($tRow['field_type'] == 'group_field') {
            if ($result_Params = $lab_obj->GetResultParams($tRow['item_id'])) {
                echo "<table class='pg2'>";
                echo "<tr><td>Field</td><td>Value</td><td>Normal</td><td>Ranges</td></tr>";

                while ($pRows = $result_Params->FetchRow()) {
                    $parId2 = 'group-' . $pRows['item_id'] . '-' . $pRows['resultID'];
                    echo "<tr><td>".$pRows['results']."</td>";

                    if ($pRows['input_type'] == 'INPUT_BOX') {
                        echo "<td><input type='text' name='$parId2' id='$parId2' value='$pdata[$parId2]' size=10 /></td>";
                    }else if($pRows['input_type']=='TITLE'){
                        echo "<td><b>$pRows[results]</b></td>";
                    }else if($pRows['input_type']=='TEXT_AREA'){
                        echo "<td><textarea name='$parId2' id='$parId2' rows='3' cols='20'/>$pdata[$parId2]</textarea></td>";
                    }else if ($pRows['input_type'] == 'COMBO_BOX') {
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
                    echo "<td>".$pRows['normal']."</td><td>".$pRows['ranges']."</td></tr>";
                }
                echo "</table>";
            }
        }


        $itemID=$tRow['item_id'];
        $colImg= createLDImgSrc($root_path, 'samples.gif', '0', 'absmiddle');
        $recImg= createLDImgSrc($root_path, 'samples2.gif', '0', 'absmiddle');
        $colButton="<a href=javascript:updateCollectionDate($job_id,'$itemID')><img $recImg alt='".$LDDone. "' id='colImage'></a>";
        $recButton="<a href=javascript:updateReceptionDate($job_id,'$itemID')><img $colImg alt='".$LDDone."' id='recImage'></a>";

        $colStat=checkSampleStatus('sampleCollectionDate',$job_id,$itemID);
        $recStat=checkSampleStatus('sampleReceptionDate',$job_id,$itemID);
        ?>
<!--        </td><td>--><?php //echo ($colStat!='0000-00-00' ? $colButton : ''); ?><!--</td>-->
<!--             <td>--><?php //echo ($recStat!='0000-00-00' ? $recButton : '');?><!--</td></tr>-->
<!--                <tr><td colspan=4> <HR></td></tr>-->

        </td><td><?php echo $colButton; ?></td>
        <td><?php echo $recButton;?></td></tr>
        <tr><td colspan=4> <HR></td></tr>
        <?php
    }
    echo '</tr>';
	echo "<tr class='pg1'><td colspan=4> &nbsp;</td></tr>";
            function getStaff(){
                global $db;
                $debug=false;

                $sql="Select id,staff_name from care_ke_staff";
                if($debug) echo $sql;
                $result=$db->Execute($sql);

                return $result;
            }
	echo "<tr class='pg1'><td>Done By</td><td><select name=doneBy><option value=''></option>";
                getStaff();
                
               $sql="Select id,staff_name from care_ke_staff";
                if($debug) echo $sql;
                $result=$db->Execute($sql);
                    while($row=$result->FetchRow()){
                        if (($doneBy==$row[id]))
					$selected = 'selected="selected"';
				else
					$selected = '';

                        echo "<option value='$row[id]' $selected>".$row['staff_name']."</option>";
                    }
                    
	echo "	</select></td></tr>";
	echo "<tr class='pg1'><td colspan=2> &nbsp;</td></tr>";
	echo "<tr class='pg1'><td>Reviewd By</td><td><select name=reviewedBy><option value=''></option>";
                getStaff();
                
               $sql="Select id,staff_name from care_ke_staff";
                if($debug) echo $sql;
                $result=$db->Execute($sql);
                    while($row=$result->FetchRow()){
                        if (($reviewedBy==$row['id']))
					$selected = 'selected="selected"';
				else
					$selected = '';
                        echo "<option value='$row[id]' $selected>$row[staff_name]</option>";
                    }
                    
	echo "	</select></td></tr>";
}
echo "<tr class='pg1'><td colspan=2> &nbsp;</td></tr>";
echo '<tr><td colspan="2"><input  type="image" '.createLDImgSrc($root_path,'savedisc.gif','0').'></td>';
echo '<td colspan="2"><a href="labor_datalist_noedit.php'.URL_APPEND.'&encounter_nr='.$encounter_nr.'&noexpand=1&from=input&job_id='.$job_id.'&parameterselect='.$parameterselect.'&allow_update='.$allow_update.'&nostat=1&user_origin='.$user_origin.'"><img '.createLDImgSrc($root_path,'showreport.gif','0','absmiddle').' alt="'.$LDClk2See.'"></a></td></tr>';


?>
</table>

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
<input type=hidden name="mode" value="save">
</form>
    <br><br>
<!--<h2>Help Links</h2>-->
<!--<a href="Javascript:gethelp('lab.php','input','param')">--><?php //echo $LDParamNoSee; ?><!--</a><br>-->
<!--<a href="Javascript:gethelp('lab.php','input','few')">--><?php //echo $LDOnlyPair; ?><!--</a>-->
<!--<a href="Javascript:gethelp('lab.php','input','save')">--><?php //echo $LDHow2Save; ?><!--</a>-->
<!--<a href="Javascript:gethelp('lab.php','input','correct')">--><?php //echo $LDWrongValueHow; ?><!--</a>-->
<!--<a href="Javascript:gethelp('lab.php','input','note')">--><?php //echo $LDVal2Note; ?><!--</a>-->
<!--<a href="Javascript:gethelp('lab.php','input','done')">--><?php //echo $LDImDone; ?><!--</a>-->


