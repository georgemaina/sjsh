<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
/*** CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@care2x.org, 
*
* See the file 'copy_notice.txt' for the licence notice
*/

/**
* Internal function to check if the image files are existing in the image folder
* @param string The directory path to the files with a trailing slash
* @param string A filter string for filenames
*/
require_once($root_path.'include/inc_date_format_functions.php');
?>
<style type="text/css" name="1">
.va12_n{font-family:verdana,arial; font-size:12; color:#000099;font-weight: bold}
.a10_b{font-family:arial; font-size:10; color:#000000}
.a10_n{font-family:arial; font-size:10; color:#000099}
.a12_b{font-family:arial; font-size:12; color:#000000}
.j{font-family:verdana; font-size:12; color:#000000}
.wardlistrow1{background-color: #EFEFEF}
.wardlistrow2{background-color: #DCDCDC}
.wardlistrow0{background-color: #dd0000}
.adm_item{background-color:cyan}
.adm-input{background-color: #DCDCDC}


</style>
<?php
function containFiles($sDirPath='',$sDiscString=''){
	$iFileCount=0;
	if(empty($sDirPath)){
		return FALSE;
	}elseif(file_exists($sDirPath.'.')){
		$handle=opendir($sDirPath.'.');
		while (FALSE!==($filename = readdir($handle))) {
			if ($filename != '.' && $filename != '..') {
				if(empty($sDiscString)){
					$iFileCount++;
				}else{
					if(stristr($filename,$sDiscString)) $iFileCount++;
				}
			}
		}
		closedir($sDirPath.'.');
		return $iFileCount;
	}else{
		return FALSE;
	}
}

define('LANG_FILE','radio.php');
//define('NO_2LEVEL_CHK',1);
$local_user='ck_radio_user';
require_once($root_path.'include/inc_front_chain_lang.php');

$thisfile=basename($_SERVER['PHP_SELF']);

//$breakfile='radiolog.php'.URL_APPEND;
$local_user='ck_pflege_user';
  	$breakfile=$root_path.'modules/nursing/nursing-station-patientdaten.php'.URL_APPEND.'&pn='.$pn.'&edit='.$edit;
	$encounter_nr=$pn;

$db->debug=0;

if($mode=='search'&&!empty($searchkey)){
	# Convert other wildcards
	$searchkey=strtr($searchkey,'*?','%_');

	if(is_numeric($searchkey)) $searchkey=(int)$searchkey;

	# Load date formatter
	include_once($root_path.'include/inc_date_format_functions.php');
	
	include_once($root_path.'include/care_api_classes/class_image.php');
	$img_obj=new Image;
	$result=$img_obj->DicomImages($searchkey);
	//	echo $img_obj->getLastQuery();

	//echo $img_obj->LastRecordCount();
	$rows=$img_obj->LastRecordCount();
}
# Prepare some parameters based on selected dicom viewer module

$pop_only=false;

switch($_SESSION['sess_dicom_viewer']){
	case 'raimjava':
			$pop_only=true;
			break;
	default:
				# Default viewer
}



echo '
<table width="100%"  cellspacing="0" border="0">
<tbody class="main">
<tr> 
    <td valign="top" height="35" align="center">
        <table class="titlebar" cellspacing="0" border="0" width=100%>
            <tbody>
                <tr class="titlebar" valign="top">
                <td bgcolor="#99ccff">
                <font color="#330066">Xray Report</font>
                </td>
                <td bgcolor="#99ccff" align="right">
                <a href="javascript:gethelp("request_radio.php","6")">
                <img width="76" height="21" border="0" onmouseout="hilite(this,0)" onmouseover="hilite(this,1)" style="filter:alpha(opacity=70)" alt="" src="../../gui/img/control/blue_aqua/en/en_hilfe-r.gif">
                </a>
                <a href="'.$breakfile.'">
                <img width="76" height="21" border="0" onmouseout="hilite(this,0)" onmouseover="hilite(this,1)" style="filter:alpha(opacity=70)" alt="" src="../../gui/img/control/blue_aqua/en/en_close2.gif">
                </a>
                </td>
                </tr>
            </tbody>
       </table>
    </td>
</tr>';
$sql0='select p.pid,p.selian_pid,p.name_last,p.name_first,p.name_2,p.date_birth from care_person p LEFT JOIN care_encounter e
    ON p.pid=e.pid where e.encounter_nr="'.$pn.'"';
$result0=$db->Execute($sql0);
$row0=$result0->FetchRow();

echo '<tr valign=top><td>
            <table width=27%>
                <tr class="adm_item"><td>'.$LDCaseNr.'</td><td class="adm-input">'.$pn.'</td> </tr>
                <tr class="adm_item"><td>PID</td><td class="adm-input">'.$row0['pid'].'</td> </tr>
                <tr class="adm_item"><td>Hosp File No</td><td class="adm-input">'.$row0['selian_pid'].'</td> </tr>
                <tr class="adm_item"><td>'.$LDLastName.'</td><td class="adm-input">'.$row0['name_last'].'</td> </tr>
                <tr class="adm_item"><td>'.$LDName.'</td><td class="adm-input">'.$row0['name_first'].' '.$row0['name_2'].'</td> </tr>
                <tr class="adm_item"><td>'.$LDBday.'</td><td class="adm-input">'.formatDate2Local($row0['date_birth'],$date_format).'</td> </tr>
                 <tr><td colspan=2>&nbsp;</td> </tr>
            </table>
    </td>
</tr><tr valign=top><td>';
    $sql='SELECT p.encounter_nr,p.batch_nr,p.xray_date,d.item_description,p.clinical_info,p.results,p.r_cm_2,p.mtr,c.findings,c.diagnosis FROM care_test_request_radio p
        LEFT JOIN care_test_findings_radio c ON p.batch_nr=c.batch_nr
	LEFT JOIN care_tz_drugsandservices d ON d.item_id=p.test_request
        WHERE p.encounter_nr="'.$pn.'"';

$request=$db->Execute($sql);
echo '<table border=0 width=100%>
         <tr class="wardlistrow0">
            <td class="va12_n"><font color="#ffffff">Xray No</font></td>
            <td class="va12_n"><font color="#ffffff">Xray Date</font></td>
            <td class="va12_n"><font color="#ffffff">Test Name</td></font>
            <td class="va12_n"><font color="#ffffff">Clinical Info</font></td>
            <td class="va12_n"><font color="#ffffff">Results</font></td>
            <td class="va12_n"><font color="#ffffff">r/cmÂ²</font></td>
            <td class="va12_n"><font color="#ffffff">Findings</td>
            <td class="va12_n"><font color="#ffffff">Diagnosis</td>
         </tr>';
$class='wardlistrow1';
while($row=$request->FetchRow()){
    echo '<tr><td class="'.$class .'">'.$row['batch_nr'].'</td>
              <td class="'.$class .'">'.$row['xray_date'].'</td>
              <td class="'.$class .'">'.$row['item_description'].'</td>
              <td class="'.$class .'">'.$row['clinical_info'].'</td>
              <td class="'.$class .'">'.$row['results'].'</td>
              <td class="'.$class .'">'.$row['r_cm_2'].'</td>
              <td class="'.$class .'">'.$row['findings'].'</td>
              <td class="'.$class .'">'.$row['diagnosis'].'</td>
          </tr>';
    $class=='wardlistrow1' ? $class='wardlistrow2' : $class='wardlistrow1';
}
echo '</table>';

	
echo '</td><tb>
</td></tr>
<tr><td>';

$sql3='select r.create_id,r.mtr,f.doctor_id from care_test_request_radio r left join care_test_findings_radio f
on r.batch_nr=f.batch_nr where r.encounter_nr="'.$pn.'"';
$result3=$db->Execute($sql3);
$row3=$result3->FetchRow();

echo '<br><br>
        <table width=27%>
            <tr class="adm_item"><td>Requesting Doctor</td><td class="adm-input">'.$row3['create_id'].'</td></tr>
            <tr class="adm_item"><td>Radiologist</td><td class="adm-input">'.$row3['doctor_id'].'</td></tr>
            <tr class="adm_item"><td>Radiographer </td><td class="adm-input">'.$row3['mtr'].'</td></tr>
        </table>

<td></tr>
</tbody>
</table>
';
?>