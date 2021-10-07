<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
/**
* CARE2X Integrated Hospital Information System beta 2.0.1 - 2004-07-04
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@care2x.org, 
*
* See the file "copy_notice.txt" for the licence notice
*/

# Set defaults
if(!isset($wt_unit_nr)||!$wt_unit_nr) $wt_unit_nr=6; # set your default unit of weight msrmnt type, default 6 = kilogram
if(!isset($ht_unit_nr)||!$ht_unit_nr) $ht_unit_nr=7; # set your default unit of height msrmnt type, default 7 = centimeter
if(!isset($hc_unit_nr)||!$hc_unit_nr) $hc_unit_nr=7; # set your default unit of head circumfernce msrmnt type, default 7 = centimeter

$thisfile=basename($_SERVER['PHP_SELF']);

$debug=false;
$isLab=true;

require_once($root_path.'include/care_api_classes/class_measurement.php');
$obj=new Measurement;
$unit_types=$obj->getUnits();
# Prepare unit ids in array
$unit_ids=array();
while(list($x,$v)=each($unit_types)){
	$unit_ids[$v['nr']]=$v['id'];
}
reset($unit_types);

if(!isset($mode)){
	$mode='show';
}elseif($mode=='create'||$mode=='update') {

	include_once($root_path.'include/inc_date_format_functions.php');
	if($_POST['msr_date']) 	$_POST['msr_date']=@formatDate2STD($_POST['msr_date'],$date_format);
		else $_POST['msr_date']=date('Y-m-d');
	
	# Non standard time format
	$_POST['msr_time']=date('H.i');
	$_POST['create_time']=date('YmdHis'); # Create the timestamp to group the values
	$_POST['create_id']=$_SESSION['sess_user_name'];


        header("location:".$thisfile.URL_REDIRECT_APPEND."&target=$target&allow_update=1&pid=".$_SESSION['sess_pid']);
		exit;
}

$lang_tables[]='obstetrics.php';
require('./include/init_show.php');

if($mode=='show'){

	$sql="SELECT c1.`batch_nr`,c1.`encounter_nr`,p.`group_id`,c1.`parameters`,c2.`item_id`,c2.`paramater_name`,p.`name`,
                c1.`send_date`,c1.`status`,c1.`bill_number`,c1.`bill_status`,c1.`sample_time`,c1.`notes`
                FROM care_test_request_chemlabor c1 LEFT JOIN care_test_request_chemlabor_sub c2 ON c1.`batch_nr`=c2.`batch_nr`
                LEFT JOIN care_tz_laboratory_param p ON c2.`item_id`=p.`item_id`
                where c1.`encounter_nr`='".$_SESSION['sess_en']."'";
        
        if($debug) echo $sql;

            $result=$db->Execute($sql);
	if($result=$db->Execute($sql)){
		$rows=$result->RecordCount();

	}
}


$subtitle=$LDLabTests;

# Set the type of "notes"
$notestype='msr';

$_SESSION['sess_file_return']=$thisfile;

$buffer=str_replace('~tag~',$title.' '.$name_last,$LDNoRecordFor);
$norecordyet=str_replace('~obj~',strtolower($subtitle),$buffer); 

# Load GUI page
require('./gui_bridge/default/gui_show.php');
?>
