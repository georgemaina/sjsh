<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
require($root_path.'include/care_api_classes/class_tz_billing.php');
/**
* CARE2X Integrated Hospital Information System beta 2.0.1 - 2004-07-04
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla ,2017 George Maina
* george@chak.or.ke, 
*
* See the file "copy_notice.txt" for the licence notice
*/
?>
<script>
        var Ext = Ext || {};
        Ext.theme = {
            name: ""
        };
    </script>
    <script src="../../../ext-6/build/ext-all.js"></script>
    <script src="../../../ext-6/build/classic/theme-classic/theme-classic.js"></script>
    <script src="htsAdult.js"></script>
    <script src="htsChild.js"></script>
    <link rel="stylesheet" href="../../../ext-6/build/classic/theme-classic/resources/theme-classic-all.css">
    <?php
$bill_obj = new Bill;

# Set defaults
if(!isset($wt_unit_nr)||!$wt_unit_nr) $wt_unit_nr=4; # set your default unit of weight msrmnt type, default 6 = kilogram
if(!isset($ht_unit_nr)||!$ht_unit_nr) $ht_unit_nr=1; # set your default unit of height msrmnt type, default 7 = centimeter
if(!isset($hc_unit_nr)||!$hc_unit_nr) $hc_unit_nr=1; # set your default unit of head circumfernce msrmnt type, default 7 = centimeter
if(!isset($htc_unit_nr)||!$htc_unit_nr) $htc_unit_nr=86; # set your default unit of head hct msrmnt type, default 12 = percentage
if(!isset($bmi_unit_nr)||!$bmi_unit_nr) $bmi_unit_nr=13; # set your default unit of head bmi msrmnt type, default 7 = percentage
if(!isset($spo2_unit_nr)||!$spo2_unit_nr) $spo2_unit_nr=258; # set your default unit of head bmi msrmnt type, default 7 = percentage

$thisfile=basename($_SERVER['PHP_SELF']);

require_once($root_path.'include/care_api_classes/class_measurement.php');

$obj=new Measurement;
$unit_types=$obj->getUnits();
# Prepare unit ids in array
$unit_ids=array();
foreach ($unit_types as $x=>$v){
//while(list($x,$v)=each($unit_types)){
	$unit_ids[$v['nr']]=$v['id'];
}
reset($unit_types);
//799211
if(!isset($mode)){
	$mode='show';
}elseif($mode=='create'||$mode=='update') {

	include_once($root_path.'include/inc_date_format_functions.php');
	//if($_POST['msr_date']) 	$_POST['msr_date']=@formatDate2STD($_POST['msr_date'],$date_format,"-");
	$_POST['msr_date']=date('Y-m-d');
	
	# Non standard time format
	$_POST['msr_time']=date('H:i:s');
	$_POST['create_time']=date('YmdHis'); # Create the timestamp to group the values
	$_POST['create_id']=$_SESSION['sess_user_name'];

	if($weight||$height||$head_c||$bp||$bp2||$pulse||$resprate||$temperature){
		# Set to no redirect
		$no_redirect=true;
//   $vitals=array($weight=array($_POST['value']=>$weight,$_POST['msr_type_nr']=>6,$_POST['notes']=$wt_notes, $_POST['unit_nr']=>$wt_unit_nr, $_POST['unit_type_nr']=>2),
//    $height=array($_POST['value']=>$height, $_POST['msr_type_nr']=>7, $_POST['notes']=>$ht_notes, $_POST['unit_nr']=>$ht_unit_nr, $_POST['unit_type_nr']=>3),
//    $head_c=array($_POST['value']=>$head_c,$_POST['msr_type_nr']=>9,$_POST['notes']=>$hc_notes,$_POST['unit_nr']=>$hc_unit_nr, $_POST['unit_type_nr']=>3),
//    $bp=array($_POST['value']=$bp,$_POST['msr_type_nr']=8,$_POST['notes']=$bp_notes,$_POST['unit_nr']=$bp_unit_nr,$_POST['unit_type_nr']=7),
//    $pulse=array($_POST['value']=$pulse,$_POST['msr_type_nr']=10,$_POST['notes']=$pulse_notes,$_POST['unit_nr']=$pulse_unit_nr,$_POST['unit_type_nr']=8),
//    $resprate=array($_POST['value']=$resprate,$_POST['msr_type_nr']=11, $_POST['notes']=$resprate_notes,$_POST['unit_nr']=$resprate_unit_nr,$_POST['unit_type_nr']=11),
//    $temperature=array($_POST['value']=$temperature,$_POST['msr_type_nr']=3,$_POST['notes']=$temp_notes,$_POST['unit_nr']=$temp_unit_nr,$_POST['unit_type_nr']=5));


        if($weight){
			$_POST['value']=$weight;
			$_POST['msr_type_nr']=6; # msrmt type 6 = weight
			$_POST['notes']=$wt_notes;
			$_POST['unit_nr']=$wt_unit_nr;
			$_POST['unit_type_nr']=4; # 2 = weight
			include('./include/save_admission_data.inc.php');
		}
    if($height){
			$_POST['value']=$height;
			$_POST['msr_type_nr']=7;  # msrmt type 7 = height
			$_POST['notes']=$ht_notes;
			$_POST['unit_nr']=$ht_unit_nr;
			$_POST['unit_type_nr']=1; # 3 = length
			include('./include/save_admission_data.inc.php');
		}
		if($head_c){
			$_POST['value']=$head_c;
			$_POST['msr_type_nr']=9; # msrmt type 9 = head circumference
			$_POST['notes']=$hc_notes;
			$_POST['unit_nr']=$hc_unit_nr;
			$_POST['unit_type_nr']=1; # 3 = length
			include('./include/save_admission_data.inc.php');
		}
        if($bp){
			$_POST['value']=$bp;
			$_POST['msr_type_nr']=1; # msrmt type 9 = head circumference
			$_POST['notes']=$bp_notes;
			$_POST['unit_nr']=$bp_unit_nr;
			$_POST['unit_type_nr']=7; # 3 = length
			include('./include/save_admission_data.inc.php');
		}
        if($bp2){
            $_POST['value']=$bp2;
            $_POST['msr_type_nr']=2; # msrmt type 9 = head circumference
            $_POST['notes']=$bp_notes;
            $_POST['unit_nr']=$bp_unit_nr;
            $_POST['unit_type_nr']=7; # 3 = length
            include('./include/save_admission_data.inc.php');
        }
                if($pulse){
			$_POST['value']=$pulse;
			$_POST['msr_type_nr']=10; # msrmt type 9 = head circumference
			$_POST['notes']=$pulse_notes;
			$_POST['unit_nr']=$pulse_unit_nr;
			$_POST['unit_type_nr']=8; # 3 = length
			include('./include/save_admission_data.inc.php');
		}
                if($resprate){
			$_POST['value']=$resprate;
			$_POST['msr_type_nr']=11; # msrmt type 9 = head circumference
			$_POST['notes']=$resprate_notes;
			$_POST['unit_nr']=$resprate_unit_nr;
			$_POST['unit_type_nr']=11; # 3 = length
			include('./include/save_admission_data.inc.php');
		}
        if($temperature){
			$_POST['value']=$temperature;
			$_POST['msr_type_nr']=3; # msrmt type 9 = head circumference
			$_POST['notes']=$temp_notes;
			$_POST['unit_nr']=$temp_unit_nr;
			$_POST['unit_type_nr']=5; # 3 = length
			include('./include/save_admission_data.inc.php');
		}
        
        if($bmi){
            $_POST['value']=$bmi;
            $_POST['msr_type_nr']=13; # msrmt type 9 = head circumference
            $_POST['notes']=$bmi_notes;
            $_POST['unit_nr']=$bmi_unit_nr;
            $_POST['unit_type_nr']=13; # 12 = Percentage
            include('./include/save_admission_data.inc.php');
        }
        if($spo2){
            $_POST['value']=$spo2;
            $_POST['msr_type_nr']=14; # msrmt type 9 = head circumference
            $_POST['notes']=$spo2_notes;
            $_POST['unit_nr']=$spo2_unit_nr;
            $_POST['unit_type_nr']=258; # 12 = Percentage
            include('./include/save_admission_data.inc.php');
        }
        
        if($htc){
            $_POST['value']=$htc;
            $_POST['msr_type_nr']=12; # msrmt type 9 = head circumference
            $_POST['notes']=$htc_reason;
            $_POST['unit_nr']=$htc_unit_nr;
            $_POST['unit_type_nr']=12; # 3 = length
            $_POST['htc_reason']=$htc_reason; # 3 = length
            include('./include/save_admission_data.inc.php');
            
            
        }

        $statusType="Triage";
        $status="Vitals Taken in the Triage";
        $statusDesc="Vitals Taken in the Triage";
        $bill_obj->updatePatientStatus($encounter_nr,$encounter_nr,$statusType,$status,$statusDesc,$currUser);

		header("location:".$thisfile.URL_REDIRECT_APPEND."&target=$target&allow_update=1&pid=".$_SESSION['sess_pid']);
		exit;
	}
}

$lang_tables[]='obstetrics.php';
require('./include/init_show.php');
//if($mode=='update'){
//    $nr=$_REQUEST[nr];
//    $sql="SELECT m.nr,m.value,m.msr_date,m.msr_time,m.unit_nr,m.encounter_nr,m.msr_type_nr,m.create_time, m.notes
//		FROM 	care_encounter AS e, 
//					care_person AS p, 
//					care_encounter_measurement AS m
//		WHERE p.pid=".$_SESSION['sess_pid']." 
//			AND p.pid=e.pid 
//			AND e.encounter_nr=m.encounter_nr  
//			AND (m.msr_type_nr=3 OR m.msr_type_nr=6 OR m.msr_type_nr=7 OR m.msr_type_nr=8 OR m.msr_type_nr=9
//                        OR m.msr_type_nr=10 OR m.msr_type_nr=11)
//                        and m.nr=$nr
//		ORDER BY m.msr_date DESC";
//    $result=$db->Execute($sql);
//    while ($row=$result->FetchRow()) {
//        $weight=$row[];
//    }
//}

// $consultation=$bill_obj->checkConsultationPayment($_SESSION['sess_en']);
//    if($consultation){
        if($mode=='show'){
                    $sql="SELECT m.nr,m.value,m.msr_date,m.msr_time,m.unit_nr,m.encounter_nr,m.msr_type_nr,m.create_time, m.notes
                            FROM 	care_encounter AS e, 
                                                    care_person AS p, 
                                                    care_encounter_measurement AS m
                            WHERE p.pid=".$_SESSION['sess_pid']." 
                                    AND p.pid=e.pid 
                                    AND e.encounter_nr=m.encounter_nr  
                                    AND (m.msr_type_nr=3 OR m.msr_type_nr=2 OR m.msr_type_nr=6 OR m.msr_type_nr=7 OR m.msr_type_nr=1
                                    OR m.msr_type_nr=8 OR m.msr_type_nr=9 OR m.msr_type_nr=10
                                    OR m.msr_type_nr=11  OR m.msr_type_nr=12 OR m.msr_type_nr=13 OR m.msr_type_nr=14)
                            ORDER BY m.msr_date DESC";

               // echo $sql;
                    if($result=$db->Execute($sql)){
                            if($rows=$result->RecordCount()){
                                    while($msr_row=$result->FetchRow()){
                                            # group the elements
                                            $msr_comp[$msr_row['create_time']]['encounter_nr']=$msr_row['encounter_nr'];
                                            $msr_comp[$msr_row['create_time']]['msr_date']=$msr_row['msr_date'];
                                            $msr_comp[$msr_row['create_time']]['msr_time']=$msr_row['msr_time'];
                                            $msr_comp[$msr_row['create_time']][$msr_row['msr_type_nr']]=$msr_row;
                                    }
                            }
                        }
                 }
            # set your default unit of msrmnt type, default 6 = kilogram
            if(!isset($wt_unit_nr)||!$wt_unit_nr) $wt_unit_nr=8;

            # set your default unit of msrmnt type, default 7 = centimeter
            if(!isset($ht_unit_nr)||!$ht_unit_nr) $ht_unit_nr=1;
            # set your default unit of msrmnt type, default 7 = centimeter
            if(!isset($bp_unit_nr)||!$bp_unit_nr) $bp_unit_nr=7;
            # set your default unit of msrmnt type, default 7 = centimeter
            if(!isset($temp_unit_nr)||!$temp_unit_nr) $temp_unit_nr=5;
            # set your default unit of msrmnt type, default 7 = centimeter
            if(!isset($resprate_unit_nr)||!$resprate_unit_nr) $resprate_unit_nr=11;
            # set your default unit of msrmnt type, default 7 = centimeter
            if(!isset($pulse_unit_nr)||!$pulse_unit_nr) $pulse_unit_nr=8;

            if(!isset($spo2_unit_nr)||!$spo2_unit_nr) $spo2_unit_nr=258;

            $subtitle=$LDMeasurements;//$LDLabTests

            # Set the type of "notes"
            $notestype='msr';

            $_SESSION['sess_file_return']=$thisfile;

            $buffer=str_replace('~tag~',$title.' '.$name_last,$LDNoRecordFor);
            $norecordyet=str_replace('~obj~',strtolower($subtitle),$buffer); 

            # Load GUI page
            require('./gui_bridge/default/gui_show.php');
//    }else{
//            header("location:../validateFile.php?target=Vital Signs&allow_update=1&pid=".$_SESSION['sess_pid']);
//	    exit;
//    }

?>