<link rel="stylesheet" type="text/css" href="../../../css/themes/default/default.css">
<link rel="stylesheet" type="text/css" href="../accounting.css">
<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('roots.php');
require($root_path.'include/inc_environment_global.php');
require($root_path.'include/care_api_classes/class_ward.php');
$wrd = new Ward ();

   $sql = "SELECT p.pid,e.encounter_nr, p.name_first,p.name_last,p.name_2,e.encounter_date,
       e.current_ward_nr,w.name
       FROM care_encounter e 
LEFT JOIN care_person p  ON e.pid=p.pid
LEFT JOIN care_ward w ON e.current_ward_nr=w.nr
WHERE e.encounter_class_nr=1 AND e.is_discharged=0
group by pid
ORDER BY w.name asc";
    $result=$db->Execute($sql);
    //$row=$result->FetchRow();

echo '<table width=100%><tbody>
                    <tr>
                        <td colspan=8 align=center class="pgtitle">BED OCCUPANCY</td>
                     </tr>
                     <tr>
                        <td colspan=8 align=center><hr></td>
                     </tr>
                    <tr>
                        <td>PID</td>
                        <td>Names</td>
                        <td>Admission Date</td>
                        <td>Ward</td>
                        <td>Bed</td>
                        <td>Bill</td>
                        <td>Deposit</td>
                        <td>Balance</td>
                     </tr>
                     <tr>
                        <td colspan=8 align=center><hr></td>
                     </tr>';
    $rowbg='white';
    while($row = $result->FetchRow()){
        $row2 = $wrd->EncounterLocationsInfo ($row[1] );
        $bed_nr = $row2 [6];
		$room_nr = $row2 [5];
		$ward_nr = $row2 [0];
		$ward_name = $row2 [1];
                $bill=getBalance($row[0],'<>',$row[1]);
                $depo=getBalance($row[0],'=',$row[1]);
                $bal=intval($bill-$depo);
          echo '<tr bgcolor='.$rowbg.'>
                        <td>'.$row[0].'</td>
                        <td>'.$row[2].' '.$row[3].' '.$row[4].'</td>
                        <td>'.$row['encounter_date'].'</td>
                        <td>'.$row['name'].'</td>
                        <td>'.$bed_nr.'</td>
                        <td>'.number_format($bill,2).'</td>
                        <td>'.number_format($depo,2).'</td>
                        <td>'.number_format($bal,2).'</td>
                     </tr>';
                 $rowbg='white';
    }
//  echo '<tr><td colspan=4 align=center><input type="submit" id="print" name="print" value="Print Report" />
//      <input type="submit" id="export" name="export" value="Export to Excel" /></td></tr>';
  echo '</tbody></table>';
function getBalance($pid,$sign,$enc_nr){
    global $db;
    
    $sql="select sum(b.total) as total from care_ke_billing b  left join care_encounter e
    on b.pid=e.pid where b.pid=$pid and e.encounter_nr=$enc_nr and b.service_type $sign'Payment' and 
    b.`IP-OP`=1 AND e.is_discharged=0 group by b.pid";
    $request=$db->Execute($sql);
    if($row=$request->FetchRow()){
       return $row[0];
    }else{
        return '0';
    }
    
}



?>

