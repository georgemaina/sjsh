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
<script language="javascript">
function release(encounter,pid)
    {
        urlholder="nursing-station-patient-release.php<?php echo URL_REDIRECT_APPEND; ?>&pn="+pid+"&encounter="+encounter+"<?php echo "&pyear=".$pyear."&pmonth=".$pmonth."&pday=".$pday."&tb=".str_replace("#","",$cfg['top_bgcolor'])."&tt=".str_replace("#","",$cfg['top_txtcolor'])."&bb=".str_replace("#","",$cfg['body_bgcolor'])."&d=".$cfg['dhtml']; ?>&station=<?php echo $station; ?>&ward_nr=<?php echo $ward_nr; ?>";
        //indatawin=window.open(urlholder,"bedroom","width=700,height=450,menubar=no,resizable=yes,scrollbars=yes"
        window.location.href=urlholder;
    }
</script>
<?php
$thisfile=basename($_SERVER['PHP_SELF']);

if(!isset($mode)) $mode='show';

require('./include/init_show.php');

# Get all encounter records  of this person
$list_obj=$person_obj->EncounterList($pid);
$rows=$person_obj->LastRecordCount();
//echo $obj->getLastQuery();

# Create encounter object
require_once($root_path.'include/care_api_classes/class_encounter.php');
$enc_obj=new Encounter();

# Get all encounter classes & load in array
if($eclass_obj=$enc_obj->AllEncounterClassesObject()){
	while($ec_row=$eclass_obj->FetchRow()) $enc_class[$ec_row['class_nr']]=$ec_row;
}

$subtitle=$LDListEncounters;
$_SESSION['sess_file_return']=$thisfile;

$buffer=str_replace('~tag~',$title.' '.$name_last,$LDNoRecordFor);
$norecordyet=str_replace('~obj~',strtolower($subtitle),$buffer); 

/* Load GUI page */
require('./gui_bridge/default/gui_show.php');
?>
