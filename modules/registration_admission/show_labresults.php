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

?>
<link rel="stylesheet" type="text/css" href="registerStyles.css">
<?php
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

	$sql="SELECT c1.`batch_nr`,c1.`encounter_nr`,c1.`parameters`,c2.`item_id`,c2.`paramater_name`,
                c1.`send_date`,c1.`status`,c1.`bill_number`,c1.`bill_status`,c1.`sample_time`,c1.`notes`,p.`field_type`
                FROM care_test_request_chemlabor c1 LEFT JOIN care_test_request_chemlabor_sub c2 ON c1.`batch_nr`=c2.`batch_nr`
                LEFT JOIN `care_tz_laboratory_param` p ON c2.`item_id`=p.`item_id`
                where c1.`encounter_nr`='".$_SESSION['sess_en']."'";
               
       if($debug)
            echo $sql;

            $result=$db->Execute($sql);
	if($result=$db->Execute($sql)){
		$rows=$result->RecordCount();
//			while($msr_row=$result->FetchRow()){
//				# group the elements
//				$msr_comp[$msr_row['create_time']]['encounter_nr']=$msr_row['encounter_nr'];
//				$msr_comp[$msr_row['create_time']]['msr_date']=$msr_row['msr_date'];
//				$msr_comp[$msr_row['create_time']]['msr_time']=$msr_row['msr_time'];
//				$msr_comp[$msr_row['create_time']][$msr_row['msr_type_nr']]=$msr_row;
//			}
//		}
	}
}


$subtitle=$LDLabTests;

# Set the type of "notes"
$notestype='msr';

$_SESSION['sess_file_return']=$thisfile;

$buffer=str_replace('~tag~',$title.' '.$name_last,$LDNoRecordFor);
$norecordyet=str_replace('~obj~',strtolower($subtitle),$buffer); 

# Load GUI page

//$sql='SELECT p.encounter_nr,k.test_date,k.test_time,c.group_id,c.name,p.paramater_name,p.parameter_value,
//    p.job_id,p.batch_nr,c.`item_id` FROM care_test_findings_chemlabor_sub p
//        LEFT JOIN care_tz_laboratory_param c ON p.paramater_name=c.id
//        LEFT JOIN care_test_findings_chemlab k ON p.job_id=k.job_id
//        LEFT JOIN care_test_request_chemlabor t ON t.batch_nr=k.job_id
//        WHERE c.group_id<>-1 AND t.status="pending" and p.encounter_nr="'.$_SESSION['sess_en'].'"
//        and c.`item_id` ORDER BY job_id asc';
//
//$request=$db->Execute($sql);
//$rows2=$request->RecordCount();
//
//if($rows2>0){
//    $results=true;
//}


require('./gui_bridge/default/gui_show.php');
?>
