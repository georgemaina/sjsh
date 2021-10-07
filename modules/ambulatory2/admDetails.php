<?php
require_once('roots.php');
require($root_path.'include/inc_environment_global.php');
	$enr_nr=$_REQUEST[enr_nr];

	$sql='SELECT a.encounter_nr,a.pid, b.name_first,b.name_2,b.name_last,a.encounter_class_nr,
a.encounter_status,a.current_ward_nr,c.name, a.current_room_nr,a.in_ward,a.is_discharged
FROM care2x.care_encounter a INNER JOIN care_person b ON a.pid=b.pid
INNER JOIN care_ward c ON c.nr=a.current_ward_nr
WHERE a.encounter_class_nr=1  AND a.is_discharged =0 AND a.encounter_nr='.$enr_nr;
        
        $result=$db->Execute($sql);
        while($row=$result->FetchRow()){
            echo $row[7].",".$row[8].",".$row[9].",".$row[10];
        }
        	
?>