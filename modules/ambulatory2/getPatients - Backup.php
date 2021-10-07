<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require('roots.php');
require($root_path . 'include/inc_environment_global.php');
require($root_path.'include/inc_img_fx.php');

?>


<?php
$detp_nr = $_GET["dept_nr"];

$debug=false;

$param='';

$dt1=new DateTime(date($_GET["startDate"]));
$startDate=$dt1->format('Y-m-d');

$dt2=new DateTime(date($_GET["endDate"]));
$endDate=$dt2->format('Y-m-d');

if(isset($startDate) and isset($endDate)){
    $param=$param.' and encounter_date between cast("'.$startDate.'" as datetime) and cast("'.$endDate.'" as datetime)';
}

if($_GET["nameSearch"]<>""){
    $name=$_GET["nameSearch"];
    $param.=' and name_first like "'.$name.'%" or name_last like "'.$name.'%" or pid='.$name.' or selian_pid='.$name;
}


// Number of records to show per page
$recordsPerPage =10;  # 0

// default startup page
$pageNum = 1;

if(isset($_GET['pageNo'])) {
    $pageNum = $_GET['pageNo'];
    settype($pageNum, 'integer');
 }

 $offset = ($pageNum - 1) * $recordsPerPage;
$sql = "SELECT e.encounter_nr,e.encounter_class_nr, e.encounter_date, e.pid,e.insurance_class_nr,p.selian_pid,p.name_last,p.name_first,p.name_2,p.date_birth,p.sex, p.photo_filename,
									a.date, a.time,a.urgency,i.name AS insurance_name,
									n.nr AS notes
							FROM care_encounter AS e
									LEFT JOIN care_person AS p ON e.pid=p.pid
									LEFT JOIN care_appointment AS a ON e.encounter_nr=a.encounter_nr
									LEFT JOIN care_class_insurance AS i ON e.insurance_class_nr=i.class_nr
									LEFT JOIN care_encounter_notes as n ON (e.encounter_nr=n.encounter_nr AND n.type_nr=6)
									LEFT JOIN care_encounter_location l ON e.`encounter_nr`=l.`encounter_nr`
							WHERE e.encounter_nr<>''  AND e.`encounter_class_nr`=2 AND l.`discharge_type_nr`<>8 ";



                                        if($detp_nr){
                                            $sql=$sql." and e.current_dept_nr='$detp_nr'";
                                        }

                                        if(isset($startDate) || $startDate<>'' && isset($endDate) || $endDate<>''){
                                                
                                                $sql=$sql." and encounter_date between '$startDate' and '$endDate'";
                                          }else{
                                                $sql=$sql.'';
                                          }
                                           $name=$_GET["nameSearch"];
                                          if($_GET["sOptions"]=='Name'){
                                               $sql.=' and name_first like "'.$name.'%" or name_last like "'.$name.'%" or name_2 like "'.$name.'%"';
                                            }else if($_GET["sOptions"]=='PID'){
                                                $sql.=' and p.pid='.$name ;
                                            }else if($_GET["sOptions"]=='File_No'){
                                                $sql.=' and selian_pid='.$name;
                                            }
                                          
                                          $gender=$_GET["gender"];
                                          if(isset($gender) && $gender<>''){
                                              $sql.=" and p.sex='$gender'";
                                          }
                                          
//                                           
                                           $ageSign=$_GET["ageSign"];
                                           $age=$_GET["age"];
                                          if(isset($age) && $age<>''){
                                              $sql.=" and ((YEAR(NOW())-YEAR(p.date_birth)))$ageSign $age";
                                          }
                                            
                                            $sql.=" ORDER BY p.name_last ASC";// LIMIT $offset, $recordsPerPage;";

$result = $db->Execute($sql);
if($debug)
    echo $sql;

$strResult='';

$strResult=$strResult. '<table border="0" width=100% id="tblResults">';
$strResult=$strResult. '<tr class="gridHeading"><td>Charts Folder</td>
                                                <td>Encounter Date</td>
                                                <td>Sex</td><td>Names</td>
                                                <td>Date of Birth</td>
                                                <td>Patient ID</td>
                                                <td>Hospital File No</td>
                                                <td>Insurance</td>
                                                <td>Options</td>';
$rws = "0";
$count=0;
while ($row = $result->FetchRow()) {
    if ($rws == 1) {
             if($row['sex']=="m"){
                   $sexImage='<a title="Show photo" href="javascript:popPic('.$row['pid'].')"><img height="15" border="0" width="25" src="../../gui/img/common/default/spm.gif"></a>';
                }else{
                     $sexImage='<a title="Show photo" href="javascript:popPic('.$row['pid'].')"><img height="15" border="0" width="25" src="../../gui/img/common/default/spf.gif"></a>';
                }
        $strResult=$strResult.'<tr bgcolor="#CFCFCF" class="trColor">
                <td><a href="javascript:getinfo('.$row['encounter_nr'].')">
			<img width="80" height="18" border="0" align="absmiddle"
                        alt="Click to set or reset color rider"
                        src="../../main/imgcreator/imgcreate_colorbar_small.php?sid=d25ba74a4b0c68667a2c5067d83ca30c&amp;ntid=false&amp;lang=en&amp;pn='.$row['encounter_nr'].'">
			</a></td>
                <td>' . $row['encounter_date'] . '</td>
                <td>' . $sexImage. '</td>
                <td><a href="../../modules/registration_admission/aufnahme_pass.php'.URL_APPEND.'&target=search&amp;fwd_nr='.$row[encounter_nr].'" title="Click to show data">' . $row['surname'] . ' ' . $row['name_first'] . ' ' . $row['name_last'] . '</a></td>
                <td>' . $row['date_birth'] . '</td>
                <td>' . $row['pid'] . '</td>
                <td>' . $row['selian_pid'] . '</td>
                <td>' . $row['selian_pid'] . '</td>';
            if ($dept_nr==42)
                    {
//                            $o_arv_patient=&new ART_patient($patient['pid']);
//                            if($o_arv_patient->is_arv_admitted($patient['pid'])) {
                                    $temp_image="<a href=\"javascript:getARV('".$patient['pid']."','".$patient['encounter_nr']."')\"><img ".createComIcon($root_path,'ball_gray.png','0','',TRUE)." alt=\"inARV\"></a>";
//                            }
//                            else {
//                                    $temp_image="<a href=\"javascript:getARV('".$patient['pid']."','".$patient['encounter_nr']."')\"><img ".createComIcon($root_path,'ball_red.png','0','',TRUE)." alt=\"not_inARV\"></a>";
//                            }
                    }

                    if ($dept_nr==7)
                    {
                            $temp_image="<a href=\"javascript:getEyeclinic('".$patient['pid']."','".$patient['encounter_nr']."')\"><img width=17 height=17 ".createComIcon($root_path,'eye.gif','0','',TRUE)." alt=\"Eye Examination\"></a>";
                    }
               $strResult=$strResult. '<td>'.$temp_image.'<a href="javascript:getinfo('.$row['encounter_nr'].')">
                                <img height="20" border="0" width="20" alt="Open patients charts folder" src="../../gui/img/common/default/open.gif"></a>
                     <a href="javascript:getrem('.$row['encounter_nr'].')">
                            <img height="14" border="0" width="15" alt="Read or write notice" src="../../gui/img/common/default/bubble2.gif"></a>
                      <a href="javascript:Transfer('.$row[encounter_nr].','.$row[pid].','.$row[pid].')">
                                <img height="12" border="0" width="20" alt="Transfer patient" src="../../gui/img/common/default/xchange.gif"></a></td>
            </tr>';
        $rws = "0";
    } else {
        $strResult=$strResult. '<tr bgcolor="#FFFFFF" class="trColor">
               <td><a href="javascript:getinfo('.$row['encounter_nr'].')">
			<img width="80" height="18" border="0" align="absmiddle"
                        alt="Click to set or reset color rider"
                        src="../../main/imgcreator/imgcreate_colorbar_small.php?sid=d25ba74a4b0c68667a2c5067d83ca30c&amp;ntid=false&amp;lang=en&amp;pn='.$row['encounter_nr'].'">
			</a> </td>
                <td>' . $row['encounter_date'] . '</td>
                <td>' .$sexImage. '</td>
                <td><a href="../../modules/registration_admission/aufnahme_pass.php'.URL_APPEND.'&target=search&amp;fwd_nr='.$row[encounter_nr].'" title="Click to show data">' . $row['name_first'] . ' ' . $row['name_2'] . ' ' . $row['name_last'] . '</a></td>
                <td>' . $row['date_birth'] . '</td>
                <td>' . $row['pid'] . '</td>
                <td>' . $row['selian_pid'] . '</td>';
        
                    if ($dept_nr==42)
                    {
//                            $o_arv_patient=&new ART_patient($patient['pid']);
//                            if($o_arv_patient->is_arv_admitted($patient['pid'])) {
                                    $temp_image="<a href=\"javascript:getARV('".$patient['pid']."','".$patient['encounter_nr']."')\"><img ".createComIcon($root_path,'ball_gray.png','0','',TRUE)." alt=\"inARV\"></a>";
//                            }
//                            else {
//                                    $temp_image="<a href=\"javascript:getARV('".$patient['pid']."','".$patient['encounter_nr']."')\"><img ".createComIcon($root_path,'ball_red.png','0','',TRUE)." alt=\"not_inARV\"></a>";
//                            }
                    }

                    if ($dept_nr==7)
                    {
                            $temp_image="<a href=\"javascript:getEyeclinic('".$patient['pid']."','".$patient['encounter_nr']."')\"><img width=17 height=17 ".createComIcon($root_path,'eye.gif','0','',TRUE)." alt=\"Eye Examination\"></a>";
                    }
                $strResult=$strResult. '<td>'.$temp_image.'<a href="javascript:getinfo('.$row['encounter_nr'].')">
                                <img height="20" border="0" width="20" alt="Open patients charts folder" src="../../gui/img/common/default/open.gif"></a> 
                     <a href="javascript:getrem('.$row['encounter_nr'].')">
                            <img height="14" border="0" width="15" alt="Read or write notice" src="../../gui/img/common/default/bubble2.gif"></a> 
                      <a href="javascript:Transfer('.$row[encounter_nr].','.$row[pid].','.$row[pid].')">
                                <img height="12" border="0" width="20" alt="Transfer patient" src="../../gui/img/common/default/xchange.gif"></a></td>
            </tr>';
        $rws = "1";
    }
    $count=$count+1;
}
// Update this query with same where clause you are using above.
 $strResult=$strResult."<tr><td colspan=8> Total Patients are $count</td></tr>";
$strResult=$strResult. '</table>';

echo $strResult;


?>
