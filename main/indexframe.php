<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require('../releaseinfo.php');
require($root_path.'include/inc_environment_global.php');
require_once($root_path.'include/care_api_classes/class_tz_billing.php');
/**
* CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@care2x.org,
*
* See the file "copy_notice.txt" for the licence notice
*/
define('LANG_FILE','indexframe.php');
define(NO_CHAIN,1);

require_once($root_path.'include/inc_front_chain_lang.php');

# Set here the window title
$wintitle=$LDTitle;
 $bill_obj = new Bill;
/**
* We check again the language variable lang. If table file not available use default (lang = "en")
*/

if(!isset($lang)||empty($lang))  include($root_path.'chklang.php');

/* Load the language table */
if(file_exists($root_path.'language/'.$lang.'/lang_'.$lang.'_indexframe.php')){
	include($root_path.'language/'.$lang.'/lang_'.$lang.'_indexframe.php');
}else{
	include($root_path.'language/en/lang_en_indexframe.php');
	$lang='en'; // last desperate effort to settle the language
}

// echo $_COOKIE['ck_config']; // for debugging only

if(($mask==2)&&!$nonewmask){
	header ("location: indexframe2.php?sid=".$sid."&lang=".$lang."&boot=".$boot."&cookie=".$cookie);
	exit;
}

# Get the global config for language usage
require_once($root_path.'include/care_api_classes/class_globalconfig.php');
$GLOBALCONFIG=array();
$gc=new GlobalConfig($GLOBALCONFIG);
$gc->getConfig('language_%');

# Prepare additional data for the gui template
$charset=setCharSet();

# Load dept & ward classes
require_once($root_path.'include/care_api_classes/class_department.php');
require_once($root_path.'include/care_api_classes/class_ward.php');
$dept=new Department();
$ward=new Ward();
$Billtime = date("H:i:s");

if($Billtime>'9:30:00'){
if(!$ward->checkIfCharged()){
$sql='SELECT a.encounter_nr,a.pid, b.name_first,b.name_2,b.name_last,a.encounter_class_nr,
	a.encounter_status,a.current_ward_nr, a.current_room_nr,a.in_ward,a.is_discharged
        FROM care2x.care_encounter a, care_person b WHERE a.pid=b.pid AND a.encounter_class_nr=1  AND a.is_discharged =0';
        $result=$db->Execute($sql);
         if ($debug) echo $sql."<br>";
         while($row=$result->FetchRow()){
               if(!isset($new_bill_number)) {
                    $new_bill_number=$bill_obj->checkBillEncounter($row[0]);
               }
            $ward->updateBedCharge($row[0],$row[1],$new_bill_number);
       }
       
       }
}


require('./gui_bridge/gui_indexframe_ke.php');
?>
