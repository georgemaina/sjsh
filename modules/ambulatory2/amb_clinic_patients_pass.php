<?php
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require('roots.php');
require($root_path . 'include/inc_environment_global.php');

$lang_tables=array('person.php','actions.php');
define('LANG_FILE','stdpass.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/inc_front_chain_lang.php');

require_once($root_path.'global_conf/areas_allow.php');
$allowedarea=&$allow_area['admit'];
$append=URL_REDIRECT_APPEND; 

$lang_tables[]='ambulatory.php';
$lang_tables[] = 'prompt.php';
$lang_tables[] = 'departments.php';
define('LANG_FILE', 'nursing.php');
$local_user = 'ck_pflege_user';
require_once($root_path . 'include/inc_front_chain_lang.php');

$fileforward='amb_clinic_patients.php'.$append.'&origin=pass&target=list&dept_nr='.$dept_nr; 
$lognote=$LDAppointments.'ok';

$thisfile=basename($_SERVER['PHP_SELF']);
//$edit=true;
?>
<script type="text/javascript">
    /*
     * To change this template, choose Tools | Templates
     * and open the template in the editor.
     */

    var xmlhttp;

    function getID(str){

    }

    function exportPatients(str,str2){

        xmlhttp=GetXmlHttpObject();
        if (xmlhttp==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }

        var names=document.getElementById('nameSearch').value;
        var sOptions;
        if(document.getElementById('byName').checked){
            sOptions=document.getElementById('byName').value;
        } else if(document.getElementById('byPid').checked){
            sOptions=document.getElementById('byPid').value;
        } else if(document.getElementById('byFile').checked){
            sOptions=document.getElementById('byFile').value;
        } else{
            sOptions="";
        }

        if(names=='' && sOptions!=''){
            alert('Please enter the '+sOptions);
            return false;
        }


        if(document.getElementById('startDate').value==null ) {
            var startDate=Date.now()
        }else{
            var startDate=document.getElementById('startDate').value;
        }

        if(document.getElementById('endDate').value==null ){
            var endDate=Date.now()
        }else{
            var endDate=document.getElementById('endDate').value
        }
        var gender=document.getElementById('gender').value
        var ageSign=document.getElementById('ageSign').value
        var age=document.getElementById('age').value

        var url="exportPatients.php?dept_nr="+str;
        url=url+"&nameSearch="+names;
        url=url+"&startDate="+startDate;
        url=url+"&endDate="+endDate;
        url=url+"&gender="+gender;
        url=url+"&ageSign="+ageSign;
        url=url+"&age="+age;

        window.open('reports/'+url,"Reports",
            "menubar=yes,toolbar=yes,width=500,height=550,location=yes,resizable=no,scrollbars=yes,status=yes");

    }


    function getPatients(str,str2){
        
        xmlhttp=GetXmlHttpObject();
        if (xmlhttp==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        
        var names=document.getElementById('nameSearch').value;
         var sOptions;
        if(document.getElementById('byName').checked){
               sOptions=document.getElementById('byName').value;
         } else if(document.getElementById('byPid').checked){
               sOptions=document.getElementById('byPid').value;
         } else if(document.getElementById('byFile').checked){
               sOptions=document.getElementById('byFile').value;
         } else{
             sOptions="";
         }
         
        if(names=='' && sOptions!=''){
            alert('Please enter the '+sOptions);
            return false;
        }
        
        
          if(document.getElementById('startDate').value==null ) {
            var startDate=Date.now()  
        }else{
             var startDate=document.getElementById('startDate').value;
        }

        if(document.getElementById('endDate').value==null ){
            var endDate=Date.now()  
        }else{
             var endDate=document.getElementById('endDate').value
        }
        var gender=document.getElementById('gender').value
        var ageSign=document.getElementById('ageSign').value
        var age=document.getElementById('age').value
        
        var url="getPatients.php?dept_nr="+str;
        url=url+"&pageNo="+str2;
        url=url+"&nameSearch="+names;
        url=url+"&startDate="+startDate;
        url=url+"&endDate="+endDate;
        url=url+"&gender="+gender;
        url=url+"&ageSign="+ageSign;
        url=url+"&age="+age;
        url=url+"&sOptions="+sOptions;

        xmlhttp.onreadystatechange=stateChanged3;
        xmlhttp.open("GET",url,true);
        xmlhttp.send(null);
    }

    function stateChanged3()
    {
        if (xmlhttp.readyState==4)
        {
            //        alert(xmlhttp.responseText);
            document.getElementById("results").innerHTML=xmlhttp.responseText;
            var rows = document.getElementById("tblResults").getElementsByTagName("tr").length;
            document.getElementById("navBar").innerHTML="Total Patients are "+(rows-2);
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

    var urlholder;

    function getinfo(pn){
<?php
                /* if($edit) */ {
                    echo '
urlholder="' . $root_path . 'modules/nursing/nursing-station-patientdaten.php' . URL_REDIRECT_APPEND;
                    echo '&pn=" + pn + "';
                    echo "&pday=$pday&pmonth=$pmonth&pyear=$pyear&edit=$edit&station=$station";
                    echo '";';
                    echo '
patientwin=window.open(urlholder,pn,"width=500,height=400,menubar=no,resizable=yes,scrollbars=yes");

patientwin.moveTo(0,0)
patientwin.resizeTo(screen.availWidth,screen.availHeight)';
                }
                /* else echo '
                  window.location.href=\'nursing-station-pass.php'.URL_APPEND.'&rt=pflege&edit=1&station='.$station.'\''; */
?>
    }

    function popPic(pid,nm){

        if(pid!="") regpicwindow = window.open("../../main/pop_reg_pic.php?sid=<?php echo $sid . "&lang=" . $lang; ?>&pid="+pid+"&nm="+nm,"regpicwin","toolbar=no,scrollbars,width=180,height=250");

    }

    function getARV(pid,encounter_nr) {
        urlholder="<?php echo $root_path ?>modules/arv_2/arv_menu.php<?php echo URL_REDIRECT_APPEND; ?>&pid="+pid+"&encounter_nr="+encounter_nr;
        //patientwin=window.open(urlholder,"arv","menubar=no,resizable=yes,scrollbars=yes")
        window.location.href=urlholder;
        patientwin.resizeTo(screen.availWidth,screen.availHeight);
        patientwin.focus();
    }

    function getEyeclinic(pid,encounter_nr) {
        urlholder="<?php echo $root_path ?>modules/eyeclinic/eye_menu.php<?php echo URL_REDIRECT_APPEND; ?>&pid="+pid+"&encounter_nr="+encounter_nr;
        //patientwin=window.open(urlholder,"arv","menubar=no,resizable=yes,scrollbars=yes")
        window.location.href=urlholder;
        patientwin.resizeTo(screen.availWidth,screen.availHeight);
        patientwin.focus();
    }


    function getrem(pn){
        urlholder="<?php echo $root_path ?>modules/nursing/nursing-station-remarks.php<?php echo URL_REDIRECT_APPEND; ?>&pn="+pn+"<?php echo "&dept_nr=$dept_nr&pday=$pday&pmonth=$pmonth&pyear=$pyear"; ?>";
        patientwin=window.open(urlholder,pn,"width=700,height=500,menubar=no,resizable=yes,scrollbars=yes");
    }

    function release(nr)
    {
        urlholder="amb_clinic_discharge.php<?php echo URL_REDIRECT_APPEND; ?>&pn="+nr+"<?php echo "&pyear=" . $pyear . "&pmonth=" . $pmonth . "&pday=" . $pday . "&tb=" . str_replace("#", "", $cfg['top_bgcolor']) . "&tt=" . str_replace("#", "", $cfg['top_txtcolor']) . "&bb=" . str_replace("#", "", $cfg['body_bgcolor']) . "&d=" . $cfg['dhtml']; ?>&station=<?php echo $station; ?>&dept_nr=<?php echo $dept_nr; ?>";
        //indatawin=window.open(urlholder,"bedroom","width=700,height=450,menubar=no,resizable=yes,scrollbars=yes"
        window.location.href=urlholder;
    }

    function popinfo(l,d)
    {
        urlholder='<?php echo $root_path ?>modules/doctors/doctors-dienstplan-popinfo.php<?php echo URL_REDIRECT_APPEND ?>&nr="+l+"&dept_nr="+d+"&user=<?php echo $aufnahme_user . '"' ?>';

        infowin=window.open(urlholder,"dienstinfo","width=400,height=450,menubar=no,resizable=yes,scrollbars=yes");

    }
    function assignWaiting(pn,pw)
    {
        urlholder="amb_clinic_assignwaiting.php<?php echo URL_REDIRECT_APPEND ?>&pn="+pn+"&pdept="+pw+"&dept_nr=<?php echo $dept_nr ?>&station=<?php echo $station ?>";
        asswin<?php echo $sid ?>=window.open(urlholder,"asswind<?php echo $sid ?>","width=650,height=500,menubar=no,resizable=yes,scrollbars=yes");

    }
    function Transfer(pn,pw,patnr)
    {
        if(confirm("<?php echo "Are you sure you want to transfer this patient" ?>")){
            urlholder="amb_clinic_transfer_select.php<?php echo URL_REDIRECT_APPEND ?>&pn="+pn+"&pat_station="+pw+"&dept_nr=<?php echo $dept_nr ?>&station=<?php echo $station ?>&patnr="+patnr;
            transwin<?php echo $sid ?>=window.open(urlholder,"transwin<?php echo $sid ?>","width=800,height=620,menubar=no,resizable=yes,scrollbars=yes");
        }
    }

</script>
<?php

require_once($root_path . 'include/care_api_classes/class_department.php');
$dept_obj = new Department;
$medical_depts = $dept_obj->getAllMedical();
//require($root_path.'include/inc_checkdate_lang.php');

if(isset($_GET[dept_nr])){
    ?>
<script type="text/javascript">
    getPatients(<?php echo $_GET[dept_nr]?>,1);
</script>
<?php }
?>
<link href="css/outpatient.css" media="screen" rel="stylesheet"/>
<link rel="STYLESHEET" type="text/css" href="../../include/dhtmlxCalendar/codebase/dhtmlxcalendar.css">
<script src='../../include/dhtmlxCalendar/codebase/dhtmlxcalendar.js'></script>
<script src='../../include/dhtmlxCalendar/codebase/dhtmlxcommon.js'></script>
<script>window.dhx_globalImgPath="'../../include/dhtmlxCalendar/codebase/imgs/";</script>



<div class="main">
    <div class="headerBox">
        <div class="headerText">Clinic Patients</div>
        <div class="headerButtons">
            <img height="21" border="0" width="76" onmouseout="hilite(this,0)" onmouseover="hilite(this,1)" style="" alt="" src="../../gui/img/control/blue_aqua/en/en_hilfe-r.gif">
            <img height="21" border="0" width="76" onmouseout="hilite(this,0)" onmouseover="hilite(this,1)" style="" alt="" src="../../gui/img/control/blue_aqua/en/en_close2.gif">
        </div>
    </div>
</div>
<div class="mainContainer">
    <div class="selectBox">
        <div class="option1">
            <label id="selDepartment">Select Department</label>
            <select  name="dept_nr" id="dept_nr" onclick="getID(this.value)">
                <option value=""></option>
                <?php
                while (list($x, $v) = each($medical_depts)) {
                    echo '<option value="' . $v['nr'] . '">' . $v['name_formal'] . '</option>';
                }
                ?>
            </select>
       
            Search By Name:<input type="checkbox" name="sOptions" id="byName" value="Name" />
                    PID:    <input type="checkbox" name="sOptions" id="byPid" value="PID" />
                    File No:<input type="checkbox" name="sOptions" id="byFile" value="File_No" />
            <input type="text" id="nameSearch" name="nameSearch" value="" size="15" />
            Gender<select id="gender">
                        <option></option>
                        <option value="M">Males</option>
                        <option value="F">Females</option>
                    </select>
            Age<select id="ageSign">
                        <option value=">">Above</option>
                        <option value="<">Below</option>
                        <option value="=">Equal</option>
                    </select>
            <input type="text" id="age" value="" size="4"/>
        </div>
        &nbsp;&nbsp;&nbsp; Start Date: <input type="text" name="startDate" id="startDate" value="<?php echo date('Y-m-d')?>"/>
            <script>
                cal1=new dhtmlxCalendarObject('startDate',true);
                mCal.setSkin("dhx_black");
            </script>
        &nbsp;&nbsp; End Date: <input type="text" name="endDate" id="endDate" value="<?php echo date('Y-m-d')?>"/>
            <script>
                cal1=new dhtmlxCalendarObject('endDate',true);
                mCal.setSkin("dhx_black");
            </script>
         &nbsp;&nbsp;  <button id="search" name="search" onclick="getPatients(document.getElementById('dept_nr').value,1)">Search</button>
         &nbsp;&nbsp; <button id="export" name="export" onclick="exportPatients(document.getElementById('dept_nr').value,1)">Export</button>
    </div>
    <div id="results" class="ptResults"> </div>
    <div id="navBar" class="navBar"> Patients Counts</div>
</div>

