<?php

require_once 'roots.php';
require($root_path . 'include/inc_environment_global.php');
require_once($root_path . 'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path . 'include/inc_init_xmlrpc.php');
require_once($root_path . 'include/care_api_classes/class_tz_billing.php');
$bill_obj = new Bill;

$debug=false;
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
    while ($row = $result->FetchRow()) {
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

                   $sql2 = "INSERT INTO care_ke_billing (pid, encounter_nr,bill_date,bill_time,`ip-op`,bill_number,
                         service_type, price,`Description`,notes,prescribe_date,input_user,item_number,partcode,qty,total,rev_code,weberpsync)
                        value('" . $row['pid'] . "','" . $row['encounter_nr'] . "','" . date("Y-m-d") . "','" . date("H:i:s") . "','1','"
                       . $new_bill_number . "','Nursing Charges','" . $charges['Charge2']
                       . "','Nursing Charges','Nursing Charges','" . date("Y-m-d")
                       . "','$user','NUR','NUR','1','" . $charges['Charge2'] . "','NUR',0)";

                   if($debug) echo $sql2;
                   $db->Execute($sql2);
                   
                   $sql = "INSERT INTO care_ke_billing (pid, encounter_nr,bill_date,bill_time,`ip-op`,bill_number,
                            service_type, price,`Description`,notes,prescribe_date,input_user,item_number,partcode,qty,total,rev_code,weberpsync)
                           value('" . $row['pid'] . "','" . $row['encounter_nr'] . "','" . date("Y-m-d") . "','" . date("H:i:s") . "','1','"
                          . $new_bill_number . "','DOCTORS FEE','" . $charges['Charge3']
                          . "','DOCTORS FEE','DOCTORS FEE','" . date("Y-m-d")
                          . "','$user','DOR','DOR','1','" . $charges['Charge3']. "','DOR',0)";

            if($debug) echo $sql;
            $db->Execute($sql);
            }
            
            $dischargeType=$bill_obj->getDischargeType($row['encounter_nr']);
            
         if($row['current_ward_nr']=='7'){
            $sql = "INSERT INTO care_ke_billing (pid, encounter_nr,bill_date,bill_time,`ip-op`,bill_number,
                  service_type, price,`Description`,notes,prescribe_date,input_user,item_number,partcode,qty,total,rev_code,weberpsync)
                 value('" . $row['pid'] . "','" . $row['encounter_nr'] . "','" . date("Y-m-d") . "','" . date("H:i:s") . "','1','"
                . $new_bill_number . "','Mortuary Daily Fee','" . $charges['Charge']
                . "','Mortuary Daily Fee','Mortuary Daily Fee','" . date("Y-m-d")
                . "','$user','SMOM15','SMOM15','1','" . $charges['Charge']. "','SMOM15',0)";

            if($debug) 
                echo $sql;
            $db->Execute($sql);
        }
        //updateBedErp($db,$row[pid]);

    }
}



        getBeds();

       
        
function updateBedErp($db, $pn) {
    global $db, $root_path;
    $debug = false;
    if ($debug)
        echo "<b>class_tz_billing::updateBedErp()</b><br>";
    if ($debug)
        echo "encounter no: $pn <br>";
    ($debug) ? $db->debug = TRUE : $db->debug = FALSE;
    $sql = 'SELECT b.pid, c.unit_price AS price,c.partcode,c.item_Description AS article,
            a.prescribe_date,a.qty AS ovamount,a.bill_number
            FROM care_ke_billing a INNER JOIN care_tz_drugsandservices c ON a.item_number=c.partcode
            INNER JOIN care_encounter b ON a.pid=b.pid
            where b.pid=' . $pn . ' and weberpSync=0 and rev_code="bed"';
    if ($debug)
        echo $sql;
    $result = $db->Execute($sql);
    if ($weberp_obj = new_weberp()) {
        //$arr=Array();
        while ($row = $result->FetchRow()) {
            //$weberp_obj = new_weberp();
            if (!$weberp_obj->transfer_bill_to_webERP_asSalesInvoice($row)) {
                $sql = 'update care_ke_billing set weberpSync=1';
                $db->Execute($sql);
            } else {
                echo 'failed';
            }
            destroy_weberp($weberp_obj);
        }
    } else {
        echo 'could not create object: debug level ';
    }
}

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


function getBeds(){
    global $db;
     $sql='SELECT r.group_nr AS ward_nr,r.location_nr AS room_nr,r.nr AS room_loc_nr,b.location_nr AS bed_nr,b.encounter_nr,
        b.nr AS bed_loc_nr,p.pid,p.name_last,p.name_first,p.date_birth,p.title,p.sex,r.date_from,k.charge,
          DATEDIFF("'.date('Y-m-d').'",r.date_from) AS days,k.charge2,k.charge3, w.name
        FROM care_encounter_location AS r
        LEFT JOIN care_encounter_location AS b  ON 	(r.encounter_nr=b.encounter_nr
        AND r.group_nr=b.group_nr
        AND	b.type_nr=5
        AND b.status NOT IN ("discharged","closed")
        )
        LEFT JOIN care_encounter AS e ON b.encounter_nr=e.encounter_nr
        LEFT JOIN care_person AS p ON e.pid=p.pid
        LEFT JOIN care_room AS k ON r.location_nr=k.room_nr
        INNER JOIN care_ward AS w ON w.nr=e.current_ward_nr
        AND e.status NOT IN ("discharged")
        GROUP BY b.encounter_nr
        ORDER BY r.group_nr,b.location_nr';
// echo $sql;
    $result=$db->Execute($sql);
    //$row=$result->FetchRow();

echo '<table width=100%><tbody>
        <tr><td colspan=10>Patients is Wards</td></tr>
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
                        <td>Consultation Fee</td>
                        <td>Nursing Fee</td>
                        <td></td>
                        <td></td>
                     </tr>';
    $rowbg='white';
    while($row = $result->FetchRow($result)){
          echo '<tr bgcolor='.$rowbg.'>
                        <td>'.$row[6].'</td>
                        <td>'.$row[7].'</td>
                        <td>'.$row[8].'</td>
                        <td>'.$row['name'].'</td>
                        <td>'.$row[1].'</td>
                            <td>'.$row[3].'</td>
                        <td>'.$row[12].'</td>
                             <td>'.$row['days'].'</td>
                        <td align=right>Ksh.<b>'.$row[13].'</b></td>
                             <td align=right>Ksh.<b>'.$row[extra_charge].'</b></td>
                                  <td align=right>Ksh.<b>'.$row[extra_charge2].'</b></td>
                        <td><a href="reports/refreshInvoice.php?pid='.$row[6].'">View</a></td>
                        <td><a href="reports/refreshInvoice.php?pid='.$row[6].'">Remove from List</a></td>
                     </tr>';
                 $rowbg='white';
    
  
}
echo '<tr><td colspan=10><input type="submit" value="Charge the Patients" name="charge" /></td></tr>
</tbody></table>';
}

?>
