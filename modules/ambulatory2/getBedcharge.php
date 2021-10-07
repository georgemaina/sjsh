<?php
require('roots.php');
require($root_path.'include/inc_environment_global.php');


    $sql='SELECT DISTINCT r.group_nr AS ward_nr,r.location_nr AS room_nr,r.nr AS room_loc_nr,b.location_nr AS bed_nr,b.encounter_nr,
        b.nr AS bed_loc_nr,p.pid,p.name_last,p.name_first,p.date_birth,p.title,p.sex,r.date_from,k.charge
        FROM care_encounter_location AS r
        LEFT JOIN care_encounter_location AS b  ON 	(r.encounter_nr=b.encounter_nr
        AND r.group_nr=b.group_nr
        AND	b.type_nr=5
        AND b.status NOT IN ("discharged","closed")
        )
        LEFT JOIN care_encounter AS e ON b.encounter_nr=e.encounter_nr
        LEFT JOIN care_person AS p ON e.pid=p.pid
        LEFT JOIN care_room AS k ON r.location_nr=k.room_nr
        WHERE r.type_nr=4
        AND e.status NOT IN ("discharged")
        ORDER BY r.group_nr,b.location_nr';

    $result=$db->Execute($sql);
    //$row=$result->FetchRow();

echo '<table width=100%><tbody>
        <tr><td colspan=10><button onclick="bedCharge()" id="charge">Charge Occupied Beds</button></td></tr>
                    <tr class="prow">
                        <td>PID</td>
                        <td>First Name</td>
                        <td>Last Name</td>
                        <td>ward_nr</td>
                        <td>room_nr</td>
                        <td>bed_nr</td>
                        <td>date_from</td>
                        <td>charge</td>
                        <td></td>
                        <td></td>
                     </tr>';
    $rowbg='white';
    while($row = $result->FetchRow($result)){
          echo '<tr bgcolor='.$rowbg.'>
                        <td>'.$row[6].'</td>
                        <td>'.$row[7].'</td>
                        <td>'.$row[8].'</td>
                        <td>'.$row[0].'</td>
                        <td>'.$row[1].'</td>
                            <td>'.$row[3].'</td>
                        <td>'.$row[12].'</td>
                        <td align=right>Ksh.<b>'.$row[13].'</b></td>
                        <td><a href="reports/refreshInvoice.php?pid='.$row[6].'">View</a></td>
                        <td><a href="reports/refreshInvoice.php?pid='.$row[6].'">Remove from List</a></td>
                     </tr>';
                 $rowbg='white';
    
  
}
echo '<tr><td colspan=10><input type="submit" value="Charge the Patients" name="charge" /></td></tr>
</tbody></table>';