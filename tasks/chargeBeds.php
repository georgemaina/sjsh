<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    table, th, td {
        border: 1px solid black;
    }

    th, td {
        text-align: left;
        padding: 8px;
    }

    tr:nth-child(even){background-color: #f2f2f2}

    th {
        background-color: #4CAF50;
        color: white;
    }
</style>
<?php

require_once 'roots.php';
require($root_path . 'include/inc_environment_global.php');
require_once($root_path . 'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path . 'include/inc_init_xmlrpc.php');
require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
$bill_obj = new Bill;

$debug=false;

function getRoomCharges($wardNr,$roomNr){
    global $db;
    $debug=false;

    $sql="Select Charge,Charge2,Charge3,charge4 from care_room where ward_nr='$wardNr' and room_nr='$roomNr'";
    if($debug) echo $sql;
    if($result=$db->Execute($sql)){
        $row=$result->FetchRow();
        return $row;
    }
}

$sql2="select bill_date from care_ke_billing where bill_date='".date('Y-m-d')."' and rev_code='bed'";
$result2=$db->Execute($sql2);
$rcount=$result2->RecordCount();
if ($debug) echo $sql2;
if($rcount>0){
    echo "<table class='style6'><tr><td>Beds already Charged</td></tr></table>";
}else{
$sql = "select e.pid,p.name_first,p.name_2,p.name_last,e.encounter_nr,e.encounter_date,
        e.current_ward_nr,e.current_room_nr,
l.date_from,l.time_from,r.nr as bed_nr,r.charge,r.charge2,r.charge3,
DATEDIFF('".date('Y-m-d')."',l.date_from) AS days,l.discharge_type_nr,p.insurance_id
from care_encounter e
left join care_encounter_location l on e.encounter_nr=l.encounter_nr
left join care_room r on e.current_ward_nr=r.ward_nr 
left join care_person p on e.pid=p.pid
where e.encounter_class_nr=1 and l.status='' and l.type_nr=5
group by e.encounter_nr";

if ($debug) echo $sql;
$result = $db->Execute($sql);
   $user = $_SESSION['sess_user_name'];
   $qty = 1;
   echo "<table><tr><th>PID</th><th>Patient Names</th><th>EncounterNo</th><th>Date</th><th>Bill Number</th></tr>";
    while ($row = $result->FetchRow()) {
        echo "<tr><td>$row[pid]</td><td>$row[name_first].' '.$row[name_last].' '$row[name_2] </td><td>$row[encounter_nr]</td><td>".date("Y-m-d")."</td><td>$new_bill_number</td>";
        $total = $row['charge'];
        $new_bill_number = $bill_obj->checkBillEncounter($row['encounter_nr']);
        $charges=getRoomCharges($row[current_ward_nr],$row[current_room_nr]);
        
            if($row['current_ward_nr']<>7){
                   $sql = "INSERT INTO care_ke_billing (pid, encounter_nr,bill_date,bill_time,`ip-op`,bill_number,
                             service_type, price,`Description`,notes,prescribe_date,input_user,item_number,partcode,qty,total,rev_code,weberpsync)
                            value('" . $row['pid'] . "','" . $row['encounter_nr'] . "','" . date("Y-m-d") . "','" . date("H:i:s") . "','1','"
                           . $new_bill_number . "','bed charge','" . $charges['Charge']
                           . "','Bed Charge','daily Bed Charge','" . date("Y-m-d")
                           . "','$user','BED','BED','1','" . $charges['Charge'] . "','bed',0)";
                   if($debug) echo $sql;

                   $db->Execute($sql);
                   echo "<tr><td></td><td colspan='3'><table><tr><td>bed charge</td><td>$charges[Charge]</td></tr></tr>";

                   $sql2 = "INSERT INTO care_ke_billing (pid, encounter_nr,bill_date,bill_time,`ip-op`,bill_number,
                         service_type, price,`Description`,notes,prescribe_date,input_user,item_number,partcode,qty,total,rev_code,weberpsync)
                        value('" . $row['pid'] . "','" . $row['encounter_nr'] . "','" . date("Y-m-d") . "','" . date("H:i:s") . "','1','"
                       . $new_bill_number . "','Nursing Charges','" . $charges['Charge2']
                       . "','Nursing Charges','Nursing Charges','" . date("Y-m-d")
                       . "','$user','NUR','NUR','1','" . $charges['Charge2'] . "','NUR',0)";

                   if($debug) echo $sql2;
                   $db->Execute($sql2);

                echo "<tr><td>Nursing Charges</td><td>".$charges[Charge2]."</td></tr>";
                   
                   $sql = "INSERT INTO care_ke_billing (pid, encounter_nr,bill_date,bill_time,`ip-op`,bill_number,
                            service_type, price,`Description`,notes,prescribe_date,input_user,item_number,partcode,qty,total,rev_code,weberpsync)
                           value('" . $row['pid'] . "','" . $row['encounter_nr'] . "','" . date("Y-m-d") . "','" . date("H:i:s") . "','1','"
                          . $new_bill_number . "','DOCTORS FEE','" . $charges['Charge3']
                          . "','DOCTORS FEE','DOCTORS FEE','" . date("Y-m-d")
                          . "','$user','DOR','DOR','1','" . $charges['Charge3']. "','DOR',0)";

            if($debug) echo $sql;
            $db->Execute($sql);
                echo "<tr><td>Doctors Fee</td><td>".$charges[Charge2]."</td></tr>";
             }
            
            $dischargeType=$bill_obj->getDischargeType($row['encounter_nr']);
            
//         if($row['current_ward_nr']=='7'){
//            $sql = "INSERT INTO care_ke_billing (pid, encounter_nr,bill_date,bill_time,`ip-op`,bill_number,
//                  service_type, price,`Description`,notes,prescribe_date,input_user,item_number,partcode,qty,total,rev_code,weberpsync)
//                 value('" . $row['pid'] . "','" . $row['encounter_nr'] . "','" . date("Y-m-d") . "','" . date("H:i:s") . "','1','"
//                . $new_bill_number . "','Mortuary Daily Fee','" . $charges['Charge']
//                . "','Mortuary Daily Fee','Mortuary Daily Fee','" . date("Y-m-d")
//                . "','$user','SMOM15','SMOM15','1','" . $charges['Charge']. "','SMOM15',0)";
//
//            if($debug)
//                echo $sql;
//            $db->Execute($sql);
//             echo "<tr><td>Mortuary Daily Fee</td><td>".$charges[Charge]."</td></tr>";
//        }
        //updateBedErp($db,$row[pid]);
        echo "</table></td></tr>";
    }
    echo "</table>";
}




?>
