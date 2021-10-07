<?php

error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/inc_environment_global.php');
//$db->debug = true;
/**
* CARE2X Integrated Hospital Information System Deployment 2.2 - 2006-07-10
* GNU General Public License
* Copyright 2002,2003,2004,2005,2006 Elpidio Latorilla
* elpidio@care2x.org, 
*
* See the file "copy_notice.txt" for the licence notice
*/
//gjergji :
//data diff for the dob
function dateDiff($dformat, $endDate, $beginDate){
	$date_parts1=explode($dformat, $beginDate);
	$date_parts2=explode($dformat, $endDate);
	$start_date=gregoriantojd($date_parts1[1], $date_parts1[2], $date_parts1[0]);
	$end_date=gregoriantojd($date_parts2[1], $date_parts2[2], $date_parts2[0]);
	return $end_date - $start_date;
}
//gjergji :
//utility function to print out the arrows depending on age / sex
function checkParamValue($paramValue,$pName) {
	global $root_path,$patient;
	$txt = '';
	$dobDiff = dateDiff("-", date("Y-m-d"), $patient['date_birth']);
	switch ($dobDiff) {
	case ( ($dobDiff >= 1) and ($dobDiff <= 30 ) ) :
			if($pName['hi_bound_n']&&$paramValue>$pName['hi_bound_n']){
				$txt.='<img '.createComIcon($root_path,'arrow_red_up_sm.gif','0','',TRUE).'> <font color="red">'.htmlspecialchars($paramValue).'</font>';
			}elseif($paramValue<$pName['lo_bound_n']){
				$txt.='<img '.createComIcon($root_path,'arrow_red_dwn_sm.gif','0','',TRUE).'> <font color="red">'.htmlspecialchars($paramValue).'</font>';
			}else{
				$txt.=htmlspecialchars($paramValue);
			}
			break;
	case ( ($dobDiff >= 31) and ($dobDiff <= 360 ) ) :
			if($pName['hi_bound_y']&&$paramValue>$pName['hi_bound_y']){
				$txt.='<img '.createComIcon($root_path,'arrow_red_up_sm.gif','0','',TRUE).'> <font color="red">'.htmlspecialchars($paramValue).'</font>';
			}elseif($paramValue<$pName['lo_bound_y']){
				$txt.='<img '.createComIcon($root_path,'arrow_red_dwn_sm.gif','0','',TRUE).'> <font color="red">'.htmlspecialchars($paramValue).'</font>';
			}else{
				$txt.=htmlspecialchars($paramValue);
			}
			break;
	case ( $dobDiff >= 361) and ($dobDiff <= 5040 ) :
			if($pName['hi_bound_c']&&$paramValue>$pName['hi_bound_c']){
				$txt.='<img '.createComIcon($root_path,'arrow_red_up_sm.gif','0','',TRUE).'> <font color="red">'.htmlspecialchars($paramValue).'</font>';
			}elseif($paramValue<$pName['lo_bound_c']){
				$txt.='<img '.createComIcon($root_path,'arrow_red_dwn_sm.gif','0','',TRUE).'> <font color="red">'.htmlspecialchars($paramValue).'</font>';
			}else{
				$txt.=htmlspecialchars($paramValue);
			}
			break;	
	case $dobDiff > 5040 :
		if($patient['sex']=='m')
			if($pName['hi_bound']&&$paramValue>$pName['hi_bound']){
				$txt.='<img '.createComIcon($root_path,'arrow_red_up_sm.gif','0','',TRUE).'> <font color="red">'.htmlspecialchars($paramValue).'</font>';
			}elseif($paramValue<$pName['lo_bound']){
				$txt.='<img '.createComIcon($root_path,'arrow_red_dwn_sm.gif','0','',TRUE).'> <font color="red">'.htmlspecialchars($paramValue).'</font>';
			}else{
				$txt.=htmlspecialchars($paramValue);
			}	
		elseif($patient['sex']=='f')	
			if($pName['hi_bound_f']&&$paramValue>$pName['hi_bound_f']){
				$txt.='<img '.createComIcon($root_path,'arrow_red_up_sm.gif','0','',TRUE).'> <font color="red">'.htmlspecialchars($paramValue).'</font>';
			}elseif($paramValue<$pName['lo_bound_f']){
				$txt.='<img '.createComIcon($root_path,'arrow_red_dwn_sm.gif','0','',TRUE).'> <font color="red">'.htmlspecialchars($paramValue).'</font>';
			}else{
				$txt.=htmlspecialchars($paramValue);
			}																				
			break;
	}
	return $txt;
}

function getParameterName($strParam){
    global $db;
    $debug=false;
    $sql="SELECT group_id,name from care_tz_laboratory_param where item_id='$strParam'";
    if($debug) echo $sql;
    $request=$db->Execute($sql);
    
    return $request;
}

function getTestId($strParam){
    global $db;
    $debug=false;
    
    $sql="SELECT results from care_tz_laboratory_resultstypes where resultID='$strParam'";
    if($debug) echo $sql;
    $request=$db->Execute($sql);
    $row=$request->FetchRow();
    return $row[0];
}

define('LANG_FILE','lab.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/inc_front_chain_lang.php');
if(!isset($user_origin)) $user_origin='';

if($user_origin=='lab'||$user_origin=='lab_mgmt'){
  	$local_user='ck_lab_user';
  	if(isset($from) && $from=='input') $breakfile=$root_path.'modules/laboratory_tz/labor_datainput.php'.URL_APPEND.'&encounter_nr='.$encounter_nr.'&job_id='.$job_id.'&parameterselect='.$parameterselect.'&allow_update='.$allow_update.'&user_origin='.$user_origin;
		else $breakfile=$root_path.'modules/laboratory_tz/labor_data_patient_such.php'.URL_APPEND;
}else{
  	$local_user='ck_pflege_user';
  	$breakfile=$root_path.'modules/nursing/nursing-station-patientdaten.php'.URL_APPEND.'&pn='.$pn.'&edit='.$edit;
	$encounter_nr=$pn;
}
if(!$_COOKIE[$local_user.$sid]) {header("Location:".$root_path."language/".$lang."/lang_".$lang."_invalid-access-warning.php"); exit;}; 

if(!$encounter_nr) header("location:".$root_path."modules/laboratory_tz/labor_data_patient_such.php?sid=$sid&lang=$lang");

$thisfile=basename(__FILE__);

///$db->debug=1;

/* Create encounter object */
require_once($root_path.'include/care_api_classes/class_lab.php');
$enc_obj= new Encounter($encounter_nr);
$lab_obj=new Lab($encounter_nr);

$cache='';

if($nostat) $ret=$root_path."modules/laboratory_tz/labor_data_patient_such.php?sid=$sid&lang=$lang&versand=1&keyword=$encounter_nr";
	else $ret=$root_path."modules/nursing/nursing-station-patientdaten.php?sid=$sid&lang=$lang&station=$station&pn=$encounter_nr";
	
# Load the date formatter */
require_once($root_path.'include/inc_date_format_functions.php');

$enc_obj->setWhereCondition("encounter_nr='$encounter_nr'");

if($encounter=&$enc_obj->getBasic4Data($encounter_nr)) {

	$patient=$encounter->FetchRow();

	$recs=&$lab_obj->getAllResults($encounter_nr);
	
	if ($rows=$lab_obj->LastRecordCount()){

		# Check if the lab result was recently modified
		$modtime=$lab_obj->getLastModifyTime();

		$lab_obj->getDBCache('chemlabs_result_'.$encounter_nr.'_'.$modtime,$cache);
		# If cache not available, get the lab results and param items
		if(empty($cache)){
			$records=array();
			$dt=array();
			while($buffer=&$recs->FetchRow()){
				# Prepare the values
				$tmp = array($buffer['paramater_name'] => $buffer['parameter_value']);
				$records[$buffer['job_id']][] = $tmp;
				$tdate[$buffer['job_id']]=&$buffer['test_date'];
				$ttime[$buffer['job_id']]=&$buffer['test_time'];		
			}
		}
	}else{
		if($nostat) header("location:".$root_path."modules/laboratory_tz/labor-nodatafound.php".URL_REDIRECT_APPEND."&user_origin=$user_origin&ln=".strtr($patient['name_last'],' ','+')."&fn=".strtr($patient['name_first'],' ','+')."&bd=".formatDate2Local($patient['date_birth'],$date_format)."&encounter_nr=$encounter_nr&nodoc=labor&job_id=$job_id&parameterselect=$parameterselect&allow_update=$allow_update&from=$from");
		 	else header("location:".$root_path."modules/nursing/nursing-station-patientdaten-nolabreport.php?sid=$sid&lang=$lang&edit=$edit&station=$station&pn=$encounter_nr&nodoc=labor&user_origin=$user_origin");
			exit;
	}

}else{
	echo "<p>".$lab_obj->getLastQuery()."sql$LDDbNoRead";exit;
}


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

lab {
    border-collapse: collapse;
    width: 100%;
}

labHeading{
    text-align: left;
    padding: 8px;
    background-color: #4CAF50;
    color: white;
}

labRows {
     text-align: left;
     padding: 8px;
}
/*th, td {
    text-align: left;
    padding: 8px;
}*/

tr:nth-child(even){background-color: #f2f2f2}

th {
    background-color: #4CAF50;
    color: white;
}

/*tr:hover {background-color: #f5f5f5}*/
</style>

<script language="javascript">

var toggle=true;
function selectall(){

	d=document.labdata;
	var t=d.ptk.value;
	if(t == 1){
		if(toggle==true){ 
			d.tk.checked=true;
		}
	}else{
		for(i = 0; i<t; i++){
			if(toggle==true && d.tk[i]){
				d.tk[i].checked=true; 
			}
		}
	}
	if(toggle==false){ 
		d.reset();
	}
	toggle=(!toggle);
}

function prep2submit(){
	d=document.labdata;
	var j=false;
	var t=d.ptk.value;
	var n=false;
	for(i=0; i<t; i++) {
		if(t==1) {
			n=d.tk;
			v=d.tk.value;
		}else if( d.tk[i]){ 
			n=d.tk[i];
			v=d.tk[i].value;
		}
		if(n.checked==true && d.tk[i]){
			if(j){
				d.params.value=d.params.value +"~"+v;
			}else if( d.tk[i]){ 
				d.params.value=v;	
				j=1;
			}
		 }
	}
	if(d.params.value!=''){
		d.submit();
	}else{
		alert('<?php echo $LDCheckParamFirst ?>');
	}
}

function remove(s, t) {
  /*
  **  Remove all occurrences of a token in a string
  **    s  string to be processed
  **    t  token to be removed
  **  returns new string
  */
  i = s.indexOf(t);
  r = "";
  if (i == -1) return s;
  r += s.substring(0,i) + remove(s.substring(i + t.length), t);
  return r;
}

var skipme = '';
function wichOne(nr) {
	
	if( document.getElementById(nr).checked == true ) {
		if( skipme == '' ) skipme = nr;
		else skipme += "-"+nr;
	} else if ( document.getElementById(nr).checked == false ) 
		skipme = remove(skipme,nr);
}
	
	
function openReport() {
	enc = <?php echo $encounter_nr ?>;
	userId = '<?php echo $_SESSION['sess_user_name']; ?>';
	urlholder="<?php echo $root_path ?>modules/pdfmaker/laboratory/report_all.php<?php echo URL_REDIRECT_APPEND; ?>&encounter_nr="+enc+"&skipme="+skipme+"&userId="+userId;
	window.open(urlholder,'<?php echo $LDOpenReport; ?>',"width=700,height=500,menubar=no,resizable=yes,scrollbars=yes");
}

function hideResults(row){
     document.getElementById("results"+row).innerHTML="";
}

function GetXmlHttpObject()
{ 
    var objXMLHttp=null;
    if (window.XMLHttpRequest)
    {
        objXMLHttp=new XMLHttpRequest();
    }
    else if (window.ActiveXObject)
    {
        objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    return objXMLHttp;
}



function getResults2(encounter_nr,jobNo,paramName,row){
//    alert('test');

        xmlhttp= GetXmlHttpObject();
        if (xmlhttp==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="../laboratory_tz/getLaboratoryData.php?encounterNo="+encounter_nr;
        url=url+"&paramName="+paramName;
        url=url+"&sid="+Math.random();
        url=url+"&task=getLabResults2";
        url=url+"&row="+row;
        url=url+"&jobNo="+jobNo;
        xmlhttp.onreadystatechange=stateChanged10;
        xmlhttp.open("POST",url,true);
        xmlhttp.send(null);

}


function getResults(encounter_nr,jobNo,item_id,row){
//    alert('test');

        xmlhttp= GetXmlHttpObject();
        if (xmlhttp==null)
        {
            alert ("Browser does not support HTTP Request");
            return;
        }
        var url="getLaboratoryData.php?encounterNo="+encounter_nr;
        url=url+"&item_id="+item_id;
        url=url+"&sid="+Math.random();
        url=url+"&task=getLabResults";
        url=url+"&row="+row;
        url=url+"&jobNo="+jobNo;
        xmlhttp.onreadystatechange=stateChanged10;
        xmlhttp.open("POST",url,true);
        xmlhttp.send(null);

}

function stateChanged10()
    {
        //get payment description
        if (xmlhttp.readyState==4)//show point desc
        {
            var str=xmlhttp.responseText;
            str2=str.split(",");
            document.getElementById("results"+str2[1]).innerHTML=str2[0];
        }
    }





</script>

<?php 

function getRange($resultID){
    global $db;
    $debug=false;
    
    $sql="SELECT normal,ranges FROM `care_tz_laboratory_resultstypes` WHERE resultID='$resultID'";
    if($debug) echo $sql;
    $results=$db->Execute($sql);
    $row=$results->FetchRow();
    
    $ranges=$row[normal]."-".$row[ranges];
    
    return $ranges;
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
                <font color="#330066">Lab Report</font>
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
</tr>
<tr valign=top><td>
            <table>
                <tr class="adm_item"><td>'.$LDCaseNr.'</td><td class="adm-input">'.$encounter_nr.'</td> </tr>
                <tr class="adm_item"><td>PID</td><td class="adm-input">'.$patient['pid'].'</td> </tr>
                <tr class="adm_item"><td>Hosp File No</td><td class="adm-input">'.$patient['selian_pid'].'</td> </tr>
                <tr class="adm_item"><td>'.$LDLastName.'</td><td class="adm-input">'.$patient['name_last'].'</td> </tr>
                <tr class="adm_item"><td>'.$LDName.'</td><td class="adm-input">'.$patient['name_first'].' '.$patient['name_2'].'</td> </tr>
                <tr class="adm_item"><td>'.$LDBday.'</td><td class="adm-input">'.formatDate2Local($patient['date_birth'],$date_format).'</td> </tr>
                 <tr><td colspan=2>&nbsp;</td> </tr>
            </table>
    </td>
</tr>
<tr><td>
            <table border=0 width=100% class=lab><tr>
                    <th class=labHeading>ItemID</th><th class=labHeading>LabTest</th><th class=labHeading>JobNo</th>
                    <th class=labHeading>Date</th><th class=labHeading>Status</th><th class=labHeading>BillStatus</th>
                    <th class=labHeading>BillNumber</th></tr>';

                $sql="SELECT c1.`batch_nr`,c1.`encounter_nr`,c1.`parameters`,c2.`item_id`,c2.`paramater_name`,
                                c1.`send_date`,c1.`status`,c1.`bill_number`,c1.`bill_status`,c1.`sample_time`,c1.`notes`,p.`field_type`
                                FROM care_test_request_chemlabor c1 LEFT JOIN care_test_request_chemlabor_sub c2 ON c1.`batch_nr`=c2.`batch_nr`
                                LEFT JOIN `care_tz_laboratory_param` p ON c2.`item_id`=p.`item_id`
                                where c1.`encounter_nr`='$encounter_nr'";
                        if($debug) echo $sql;
                        $results=$db->Execute($sql);
                         $rcount=$results->RecordCount();

                         $i=0;
                        while($row=$results->FetchRow()){
                            $params=explode('-',$row[paramater_name]);
                                            echo "<tr><td class=labRows>$row[item_id]</td><td class=labRows style='font-weight: bold;  font-size: 14px;color:blue'>$row[paramater_name]</td>
                                            <td class=labRows>$row[batch_nr]</td><td class=labRows>$row[send_date]
                                            </td><td class=labRows>$row[status]</td><td class=labRows>$row[bill_status]</td><td class=labRows>$row[bill_number]</td></tr>";
                            if($row[field_type]=='group_field'){
                                    echo "<tr><td><button id=preview onclick=getResults($encounter_nr,$row[batch_nr],'$row[item_id]',$i)>Preview Results</button></td>
                                            <td colspan=5 id=results$i></td>
                                            <td><button id=Hide onclick=hideResults($i)>Hide Results</button></td></tr>";
                            }else{
                                    echo "<tr><td><button id=preview onclick=getResults2($encounter_nr,$row[batch_nr],'$row[paramater_name]',$i)>Preview Results</button></td>
                                             <td colspan=5 id=results$i></td>
                                            <td><button id=Hide onclick=hideResults($i)>Hide Results</button></td></tr>";
                            }

                              $i++;
                        }


echo "</td></tr></tbody></table>";



    
?>