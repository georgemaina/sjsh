<?php
require('roots.php');
require($root_path.'include/inc_environment_global.php');


    $sql='select e.pid,p.name_first,p.name_2,p.name_last,e.encounter_nr,e.encounter_date,
        e.current_ward_nr,w.name,e.current_room_nr,
l.date_from,l.time_from,r.nr as bed_nr,r.charge,r.charge3,r.charge2,
DATEDIFF("'.date('Y-m-d').'",l.date_from) AS days
from care_encounter e
left join care_encounter_location l on e.encounter_nr=l.encounter_nr
left join care_room r on e.current_ward_nr=r.ward_nr 
left join care_person p on e.pid=p.pid
left join care_ward w on e.current_ward_nr=w.nr
where e.encounter_class_nr=1 and e.is_discharged=0 and l.type_nr=5
group by e.encounter_nr';
// echo $sql;
    $result=$db->Execute($sql);
    //$row=$result->FetchRow();

echo '<table width=100%><tbody>
        <tr><td colspan=10>Patients in Wards</td></tr>
                    <tr class="prow">
                        <td>PID</td>
                        <td>First Name</td>
                        <td>Last Name</td>
                        <td>ward_nr</td>
                        <td>room_nr</td>
                        <td>bed_nr</td>
                        <td>date_from</td>
                        <td>Bed Days</td>
                        <td>charge</td>
                        <td></td>
                        <td></td>
                     </tr>';
    $rowbg='white';
    $pcount=0;
    while($row = $result->FetchRow($result)){
          echo '<tr bgcolor='.$rowbg.'>
                        <td>'.$row['pid'].'</td>
                        <td>'.$row['name_first'].'</td>
                        <td>'.$row['name_last'].'</td>
                        <td>'.$row['name'].'</td>
                        <td>'.$row['current_room_nr'].'</td>
                        <td>'.$row['bed_nr'].'</td>
                        <td>'.$row['date_from'].'</td>
                        <td>'.$row['days'].'</td>
                        <td align=right>Ksh.<b>'.$row['charge'].'</b></td>
                        <td><a href="reports/refreshInvoice.php?pid='.$row['pid'].'">View</a></td>
                        <td><a href="reports/refreshInvoice.php?pid='.$row['pid'].'">Remove from List</a></td>
                     </tr>';
                 $rowbg='white';
    $pcount=$pcount+1;
  
}
echo '<tr><td colspan=8><input type="submit" value="Charge the Patients" name="charge" /></td>
      <td colspan=2>Total Patients '.$pcount.'</td></tr>
</tbody></table>';