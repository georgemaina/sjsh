<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('roots.php');
require($root_path.'include/inc_environment_global.php');

$lang_tables=array('departments.php');
define('LANG_FILE','ambulatory.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/inc_front_chain_lang.php');

// reset all 2nd level lock cookies
require($root_path.'include/inc_2level_reset.php');

if(!$_SESSION['sess_path_referer']) $_SESSION['sess_path_referer'];
$breakfile=$root_path.'modules/news/start_page.php'.URL_APPEND;
$_SESSION['sess_path_referer']=$top_dir.basename(__FILE__);
$_SESSION['sess_user_origin']='amb';
$_SESSION['sess_parent_mod']='';

require_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj= new Department;
$medical_depts=&$dept_obj->getAllMedical();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="css/outpatient.css" media="screen" rel="stylesheet"/>
        <title>HIV CARE ENROLLMENT FORM</title>
<script language="javascript">
function goDept(t) {
	d=document.dept_select;
	if(d.dept_nr.value!=""){
		d.subtarget.value=d.dept_nr.value;
		d.action=t;
		eval("d.dept.value=d.dname"+d.dept_nr.value+".value;");
		d.submit();
	}
}
//  Script End -->
</script>
    </head>
    <body">
        <div class="main">
            <div class="headerBox">
                <div class="headerText">Outpatient</div>
                <div class="headerButtons">
                    <a href="javascript:gethelp('nursing_station.php','','ja','','Ward')"><img height="21" width="76" onmouseout="hilite(this,0)"                                                       onmouseover="hilite(this,1)" style="" alt="" src="../../gui/img/control/blue_aqua/en/en_hilfe-r.gif"></img> </a>
                    <a href="nursing.php?sid=2b2edff2bf9fa94132f26e911c4292c7&amp;ntid=false&amp;lang=en"><img height="21" width="76"
                                                                                                               onmouseout="hilite(this,0)" onmouseover="hilite(this,1)" style="" alt="" src="../../gui/img/control/blue_aqua/en/en_close2.gif"></img> </a>
                </div>
            </div>
        </div>
        <div class="spacer"></div>
        <div class="mainContainer">
            <div class="selectBox">

                <select  name="dept_nr">
                    <option value=""></option>
                    <?php
                    while(list($x,$v)=each($medical_depts)){
                            echo '<option value="'.$v['nr'].'">'.$v['name_formal'].'</option>';
                    }
                    ?>
                
                </select>
                <img height="1" width="16" alt="" src="../../gui/img/common/default/l-arrowgrnlrg.gif"></img>
                <label id="selDepartment">Select Department</label>
            </div>
            <div class="Section1">
                <div class="box1"><img height="16" width="15" src="../../gui/img/common/default/icon-date-hour.gif"></img> </div>
                <div class="box2 link"><a href="javascript:goDept('../../modules/appointment_scheduler/appt_main_pass.php')">Appointments</a></div>
                <div class="box3">Patient appointments with this department</div>
            </div>
            <div class="Section1">
                <div class="box1"><img height="15" width="15" src="../../gui/img/common/default/forums.gif"></img> </div>
                <div class="box2"><a href="amb_clinic_patients_pass.php">Outpatient clinic</a></div>
                <div class="box3">Today's admitted patients in the clinic</div>
            </div>
            <div class="Section1">
                <div class="box1"><img height="15" width="15" src="../../gui/img/common/default/forums.gif"></img> </div>
                <div class="box2"><a href="creditslip.php">Print Credit Slips</a></div>
                <div class="box3"></div>
            </div>
            <div class="Section1">
                <div class="box1"><img height="15" width="15" src="../../gui/img/common/default/forums.gif"></img> </div>
                <div class="box2"><a href="finaliseinvoice1.php">Process Invoices</a></div>
                <div class="box3"></div>
            </div>
            <div class="Section1">
                <div class="box1"><img height="15" width="15" src="../../gui/img/common/default/forums.gif"></img> </div>
                <div class="box2"><a href="accounting.php">Outpatient Transactions</a></div>
                <div class="box3"></div>
            </div>
        </div>
    </body>
</html>

