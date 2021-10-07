
<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');
require_once($root_path . 'include/care_api_classes/class_department.php');
require($root_path.'include/inc_date_format_functions.php');
$dept_obj = new Department;


$limit = $_REQUEST[limit];
$start = $_REQUEST[start];
$formStatus = $_REQUEST[formStatus];
$searchParam = $_REQUEST[sParam];
$groupID=$_REQUEST[groupID];

$OrderNo=$_REQUEST['OrderNo'];
$OrderStatus=$_REQUEST['OrderStatus'];
$OrderDate=$_REQUEST['OrderDate'];
$Comment=$_REQUEST['Comment'];
$grnno=$_REQUEST[grnno];
$ReceiveDate=$_REQUEST['ReceiveDate'];
$salesOrderNo=$_REQUEST['OrderNo'];
$userName = $_SESSION['sess_login_username'];

$encNo=$_REQUEST['encNo'];


$task = ($_REQUEST['task']) ? ($_REQUEST['task']) : '';

switch ($task) {
    case "getOpdPatients":
        getOpdPatients();
        break;
    case "getWardInfo":
        getWardInfo();
        break;
    case "getClinicsInfo":
        getClinicsInfo();
        break;
    case "getClinicsList":
        getClinicsList($dept_obj);
        break;
    case "getPrescriptions":
        getPrescriptions($encNo);
        break;
    case "getVitals":
        getVitals($encNo);
        break;
    case "getDiagnosis":
        getDiagnosis($encNo);
        break;
    case "getLabTests":
        getLabTests($encNo);
        break;
    case "getNotes":
        getNotes($encNo);
        break;
    case "getRadiology":
        getRadiology($encNo);
        break;
    case "getClinicalRooms":
        getClinicalRooms();
        break;
    case "getAnnouncements":
        getAnnouncements();
        break;
    case "getProcedures":
        getProcedures($encNo);
        break;
    case "checkUserRole":
        checkUserRole($userName);
        break;
    case "getMainMenus":
        getMainMenus();
        break;
    default:
        echo "{failure:true}";
        break;
}//end switch


function getMainMenus() {
    global $db;
    $debug = false;

    $user=$_SESSION['userID'];
    //$user="admin";

    $sql = "SELECT nr,`name`,url,s_image FROM menus WHERE `type`=1 AND nr IN (SELECT role FROM user_roles WHERE `view`='true' AND username='Admin')";
    if($debug) echo $sql;
    $request = $db->Execute($sql);

    $total = $request->RecordCount();

    echo '{"success":"true","total":"' . $total . '","mainMenus":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {

        echo '{"nr":"' . $row[nr] . '","menuName":"' . $row[name] . '","url":"' . $row[url] . '","sImage":"' . $row[s_image] . '","subMenus":[';

        $sql2="SELECT nr,`name`,url,s_image,`groupID`,dispType FROM menus WHERE `type`=2 and groupID='$row[nr]' and is_visible=1
              AND nr IN (SELECT role FROM user_roles WHERE `view`='true' AND username='Admin')";
        if($debug) echo $sql2;
        $request2 = $db->Execute($sql2);
        $total2=$request2->RecordCount();
        $counter2=0;
        while ($row2 = $request2->FetchRow()) {
            if($row2[nr]<>''){
                echo '{"ID":"' . $row2[nr]. '","groupID":"' . $row2[groupID] . '","subMenuName":"' . $row2[name] . '","url":"' . $row2[url]
                    . '","sImage":"' . $row2[s_image] . '","dispType":"' . $row2[dispType] . '"}';
            }

            $counter2++;
            if ($counter2 <> $total2) {
                echo ",";
            }else{
                echo ']}';
            }

        }

//        echo "]";

        $counter++;
        if ($counter <> $total) {
            echo ",";
        }

    }

    echo ']}';
}

function checkUserRole($userName){
    global $db;
    $debug=false;
    
    $sql="SELECT * FROM care_users WHERE name='$userName' AND permission LIKE '%doctors%'";
    if($debug) echo $sql;
    $result=$db->Execute($sql);
    $rcount=$result->RecordCount();
    
    if($rcount>0){
        echo "{success:true}";
    }else{
        echo "{failure:true}";
    }
}
function getAnnouncements(){
    global $db;
    $debug=false;

    $sql = "SELECT nr,preface,body FROM care_news_article where status='pending'";

    if ($debug) echo $sql;

    $result = $db->Execute($sql);

    $numRows = $result->RecordCount();
    echo '{
    "Announcements":[';
    $counter = 0;
    while ($row = $result->FetchRow()) {

        $text = str_replace("\r\n","\n",$row[body]);
        $paragraphs = preg_split("/[\n]{2,}/",$text);
        foreach ($paragraphs as $key => $p) {
            $paragraphs[$key] = "<p>".str_replace("\n","<br />",$paragraphs[$key])."</p>";
        }

        $text = implode("", $paragraphs);

        $body= $text;

        echo '{"nr":"' . $row[nr] . '","title":"' . $row[preface] . '","body":"' . $body. '"}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }

    }
    echo ']}';

}

function getClinicalRooms(){
    global $db;
    $debug=false;

    $sql = "SELECT ID,name_formal FROM care_department WHERE TYPE=4";

    if ($debug) echo $sql;

    $result = $db->Execute($sql);

    $numRows = $result->RecordCount();
    echo '{
    "rooms":[';
    $counter = 0;
    while ($row = $result->FetchRow()) {

        echo '{"ID":"' . $row[ID] . '","Description":"' . $row[name_formal] . '"}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }

    }
    echo ']}';

}

function getRadiology($encNo)
{
    global $db;
    $debug = false;

    $sql = "select a.batch_nr,a.encounter_nr,b.partcode,b.item_description,a.clinical_info,
                a.send_date,a.test_date,b.unit_price,a.status,a.create_id
                from care_test_request_radio a
                inner join care_tz_drugsandservices b on a.test_request=b.partcode
                inner join care_encounter d on a.encounter_nr=d.encounter_nr
                WHERE d.encounter_class_nr='2' and a.encounter_nr=$encNo
                ORDER BY d.encounter_date DESC , a.encounter_nr ASC";

    if ($debug) echo $sql;

    $result = $db->Execute($sql);

    $numRows = $result->RecordCount();
    echo '[';
    $counter = 0;
    while ($row = $result->FetchRow()) {

        echo '{"Status":"' . $row[status] . '","BatchNo":"' . $row[batch_nr] . '","Description":"' . $row[item_description]  . '","TimeRequested":"' . $row[send_date] . '","RequestedBy":"' . $row[create_id]. '"}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }

    }
    echo ']';
}

function getNotes($encNo)
{
    global $db;
    $debug = false;

    $sql = "SELECT n.`type_nr`,t.`name`,type_nr,encounter_nr,notes,n.create_time,n.create_id FROM care_encounter_notes n
            INNER JOIN care_type_notes t ON n.`type_nr`=t.`nr` WHERE encounter_nr='$encNo'";

    if ($debug) echo $sql;

    $result = $db->Execute($sql);

    $numRows = $result->RecordCount();
    echo '[';
    $counter = 0;
    while ($row = $result->FetchRow()) {

        $text = str_replace("\r\n","\n",$row[notes]);
        $paragraphs = preg_split("/[\n]{2,}/",$text);
        foreach ($paragraphs as $key => $p) {
            $paragraphs[$key] = "<p>".str_replace("\n","<br />",$paragraphs[$key])."</p>";
        }

        $text = implode("", $paragraphs);

        $notes= $text;
        echo '{"NotesType":"' . $row[name] .'","Notes":"' . $notes . '","CreateTime":"' . $row[create_time]  . '","TreatedBy":"' . $row[create_id]. '"}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }

    }
    echo ']';
}

function getLabTests($encNo)
{
    global $db;
    $debug = false;

    $sql = "SELECT s.encounter_nr,s.batch_nr,s.paramater_name,c.send_date,c.status,c.create_id FROM care_test_request_chemlabor_sub s
              INNER JOIN care_test_request_chemlabor c ON s.batch_nr=c.batch_nr WHERE s.encounter_nr='$encNo'";

    if ($debug) echo $sql;

    $result = $db->Execute($sql);

    $numRows = $result->RecordCount();
    echo '[';
    $counter = 0;
    while ($row = $result->FetchRow()) {

        echo '{"Status":"' . $row[status] . '","BatchNo":"' . $row[batch_nr] . '","Description":"' . $row[paramater_name]  . '","TimeRequested":"' . $row[send_date] . '","RequestedBy":"' . $row[create_id]. '"}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }

    }
    echo ']';
}

function getDiagnosis($encNo)
{
    global $db;
    $debug = false;

    $sql = "SELECT ICD_10_code,icd_10_description,TIMESTAMP,TYPE FROM care_tz_diagnosis
            WHERE encounter_nr='$encNo'";

    if ($debug) echo $sql;

    $result = $db->Execute($sql);

    $numRows = $result->RecordCount();
    echo '[';
    $counter = 0;
    while ($row = $result->FetchRow()) {

        echo '{"Code":"' . $row[ICD_10_code] . '","Description":"' . $row[icd_10_description]  . '","Time":"' . $row[TIMESTAMP] . '","Type":"' . $row[TYPE]. '"}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }

    }
    echo ']';
}

function getVitals($encNo)
{
    global $db;
    $debug = false;

    $sql = "SELECT m.encounter_nr,m.msr_type_nr,t.name,m.value,m.`create_time`,t.`lower`,t.`upper` FROM care_encounter_measurement m
            LEFT JOIN care_type_measurement t ON m.msr_type_nr=t.nr
            WHERE m.encounter_nr='$encNo'";

    if ($debug) echo $sql;

    $result = $db->Execute($sql);

    $numRows = $result->RecordCount();
    echo '[';
    $counter = 0;
    while ($row = $result->FetchRow()) {
        ($row[lower]<>''? $lower=$row[lower]:$lower=0);
        ($row[upper]<>''? $upper=$row[upper]:$upper=0);

        echo '{"EncounterNo":"' . $row[encounter_nr] . '","VitalsTime":"' . $row[create_time]
            . '","VitalID":"' . $row[msr_type_nr] . '","Description":"' . $row[name]
            . '","Value":"' . $row[value] .'","Lower":' . $lower.',"Upper":' . $upper. '}';

        $counter++;
        if ($counter <> $numRows) {
            echo ",";
        }

    }
    echo ']';
}

function getProcedures($encNo){
    global $db;
    $debug=false;

    $sql="SELECT d.`status`,d.`nr`,d.`drug_class`,d.partcode,d.`article`,(d.`dosage`*d.`days`*d.`times_per_day`) AS Qty,d.`price`
            ,d.`modify_time` FROM care_encounter_prescription d
            LEFT JOIN care_encounter p ON d.`encounter_nr`=p.`encounter_nr`
            WHERE d.`drug_class` not in('Drug_list','medical-supplies','XRAY','LABORATORY') and d.encounter_nr='$encNo'";
    if($debug) echo $sql;

    $result=$db->Execute($sql);

    $numRows=$result->RecordCount();
    echo '[';
    $counter=0;
    while ($row = $result->FetchRow()) {

        echo '{"Status":"'. $row['status'].'","No":"'. $row['nr'].'","PartCode":"'. $row['partcode'].'","Description":"'. $row['article']
            .'","Qty":"'. $row['Qty'] .'","Price":"'. $row['price'].'","EncounterNo":"'. $row['encounter_nr'].'","RequestTime":"'. $row['modify_time'].'"}';
        if ($counter<>$numRows){
            echo ",";
        }
        $counter++;
    }
    echo ']';

}

function getPrescriptions($encNo){
    global $db;
    $debug=false;

    $sql="SELECT d.`status`,p.`pid`,d.`encounter_nr`,d.`nr`,d.`drug_class`,d.partcode,d.`article`,d.`dosage`,d.`days`,d.`times_per_day`,d.`price`,
            ROUND(d.`dosage`*d.`days`*d.`times_per_day`*d.`price`,2) AS Total
            ,d.`modify_time` FROM care_encounter_prescription d
            LEFT JOIN care_encounter p ON d.`encounter_nr`=p.`encounter_nr`
            WHERE d.`drug_class` in('Drug_list','medical-supplies') and d.encounter_nr='$encNo'";
    if($debug) echo $sql;

    $result=$db->Execute($sql);

    $numRows=$result->RecordCount();
    echo '{
    "Prescriptions":[';
    $counter=0;
    while ($row = $result->FetchRow()) {

        echo '{"Status":"'. $row[status].'","Pid":"'. $row[pid].'","PartCode":"'. $row[partcode].'","Description":"'. $row[article].'","Dosage":"'. $row[dosage].'","TimesPerDay":"'. $row[times_per_day]
            .'","Days":"'. $row[days] .'","Price":"'. $row[price].'","Total":"'. $row[Total].'","EncounterNo":"'. $row[encounter_nr].'","PrescribeDate":"'. $row[modify_time].'"}';
        if ($counter<>$numRows){
            echo ",";
        }
        $counter++;
    }
    echo ']}';

}

function getClinicsList($dept_obj){
    $medical_depts = $dept_obj->getAllMedical();

    echo '{
    "clinics":[';
    while (list($x, $v) = each($medical_depts)) {
        echo '{"ID":"'.$v['nr'] .'","Name":"' . $v['name_formal'] . '"},';
    }

    echo "]}";



}

function getWardInfo(){
    global $db;
    $is_today='';
    $sql = 'SELECT c.`nr`, c.`ward_id`, c.`name` FROM care_ward c';
    $result = $db->Execute($sql);
    $numRows = $result->RecordCount();
    echo '{"Wards":[';
    $counter=0;
    while( $row=$result->FetchRow()){
        getWardInfoDetails($row[0]);

    }
    echo ']}';


}

function getWardInfoDetails($wrdNO) {
    global $db;
    $db->debug = 0;
    require('roots.php');
    require_once('../../include/care_api_classes/class_ward.php');
    $ward_obj = new Ward;
    $is_today='';
    $s_date='';
    $pyear='';
    $pmonth='';
    $pday='';
    $occ_bed=0;

# Load date formatter
    require_once('../../include/inc_date_format_functions.php');
    require_once('../../global_conf/inc_remoteservers_conf.php');
    $ward_nr = $wrdNO;
    if ($ward_info = &$ward_obj->getWardInfo($ward_nr)) {
        $room_obj = &$ward_obj->getRoomInfo($ward_nr, $ward_info['room_nr_start'], $ward_info['room_nr_end']);
        if (is_object($room_obj)) {
            $room_ok = true;
        } else {
            $room_ok = false;
        }
        # GEt the number of beds
        $nr_beds = $ward_obj->countBeds($ward_nr);
//        echo 'number of beds '.$nr_beds.'<br>';
        # Get ward patients
        if ($is_today)
            $patients_obj = &$ward_obj->getDayWardOccupants($ward_nr);
        else
            $patients_obj= & $ward_obj->getDayWardOccupants($ward_nr, $s_date);
//
//    echo $ward_obj->getLastQuery();
//    echo $ward_obj->LastRecordCount();

        if (is_object($patients_obj)) {
            # Prepare patients data into array matrix
            while ($buf = $patients_obj->FetchRow()) {
                $patient[$buf['room_nr']][$buf['bed_nr']] = $buf;
            }
            $patients_ok = true;
            $occup = 'ja';
        } else {
            $patients_ok = false;
        }

        $ward_ok = true;

        # Create the waiting inpatients' list
        $wnr = (isset($w_waitlist) && $w_waitlist) ? 0 : $ward_nr;
        $waitlist = $ward_obj->createWaitingInpatientList($wnr);
        $waitlist_count = $ward_obj->LastRecordCount();

        # Get the doctor's on duty information
        #### Start of routine to fetch doctors on duty
# If ward exists, show the occupancy list

        if ($ward_ok) {
            if ($pyear . $pmonth . $pday < date('Ymd')) {
//                echo '<b>'.$LDAttention.'</font> '.$LDOldList.'</b>';
                # Prevent adding new patients to the list  if list is old
                $edit = FALSE;
            }

            # Start here, create the occupancy list
            # Assign the column  names
            # Initialize help flags
            $toggle = 1;
            $room_info = array();
            # Set occupied bed counter
            $occ_beds = 0;
            $lock_beds = 0;
            $males = 0;
            $females = 0;
            $cflag = $ward_info['room_nr_start'];

            # Initialize list rows container string
            $sListRows = '';

            # Loop trough the ward rooms

            for ($i = $ward_info['room_nr_start']; $i <= $ward_info['room_nr_end']; $i++) {
                if ($room_ok) {
                    $room_info = $room_obj->FetchRow();
                } else {
                    $room_info['nr_of_beds'] = 1;
                    $edit = false;
                }

                // Scan the patients object if the patient is assigned to the bed & room
                # Loop through room beds


                for ($j = 1; $j <= $room_info['nr_of_beds']; $j++) {
                    //for($j=1;$j<=$nr_beds;$j++){
                    # Reset elements


                    if ($patients_ok) {

                        if (isset($patient[$i][$j])) {
                            $bed = &$patient[$i][$j];
                            $is_patient = true;
                            # Increase occupied bed nr
                            $occ_beds++;
                        } else {
                            $is_patient = false;
                            $bed = NULL;
                        }
                    }

                    if ($is_patient) {
                        $sBuffer = '<a href="javascript:popPic(\'' . $bed['pid'] . '\')">';
                        if (strtolower($bed['sex']) == 'f') {
                            $females++;
                        } elseif (strtolower($bed['sex']) == 'm') {
                            $males++;
                        }
                    }
                }
                # set room nr change flag , toggle row color
                if ($cflag != $i) {
                    $toggle = !$toggle;
                    $cflag = $i;
                }

                # Check if bed is locked
                if (stristr($room_info['closed_beds'], $j . '/')) {
                    $bed_locked = true;
                    $lock_beds++;
                    # Consider locked bed as occupied so increase occupied bed counter
                    $occ_bed++;
                } else {
                    $bed_locked = false;
                }
            } // end of ward loop
            $sql = 'SELECT c.`nr`, c.`ward_id`, c.`name` FROM care_ward c where  c.`nr`="'.$ward_nr.'"';
            $result = $db->Execute($sql);
            $numRows = $result->RecordCount();
            $row = $result->FetchRow();
            # Final occupancy list line
            # Prepare the stations quick info data
            # Occupancy in percent
            $occ_percent = ceil(($occ_beds / $nr_beds) * 100);

            # Nr of vacant beds
            $vac_beds = $nr_beds - $occ_beds;
            echo '{"Ward":"' . $row[2] . '","Beds":"' . $nr_beds . '","Occupancy":"' . $occ_percent . '%","Occupied":' . $occ_beds
                . ' ,"Vacant":' . $vac_beds . ',"Males":"' . $males . '","Females":"' . $females. '"},';




        }
    }
}


function getTotalOPDPatients($clinic){
    global $db;
    $debug=false;

    $currDate=date('Y-m-d');

    $sql="SELECT COUNT(encounter_nr) FROM care_encounter WHERE current_dept_nr='$clinic' AND encounter_date='$currDate' GROUP BY current_dept_nr";
    if($debug) echo $sql;
    $result=$db->Execute($sql);
    $row=$result->FetchRow();

    return $row[0];
}


function getTotalPatientsByGender($clinic,$gender){
    global $db;
    $debug=false;

    $currDate=date('Y-m-d');

    $sql="SELECT COUNT(encounter_nr) FROM care_encounter e
            LEFT JOIN care_person p ON e.`pid`=p.`pid`
             WHERE current_dept_nr='$clinic' AND encounter_date='$currDate' AND p.`sex`='$gender' GROUP BY current_dept_nr";
    if($debug) echo $sql;
    $result=$db->Execute($sql);
    $row=$result->FetchRow();

    return $row[0];
}

function getTotalPatientsByAge($clinic,$sign,$age){
    global $db;
    $debug=false;

    $currDate=date('Y-m-d');

    $sql="SELECT COUNT(encounter_nr) FROM care_person p LEFT JOIN care_encounter e
            ON p.`pid`=e.`pid`
             WHERE e.`encounter_date`='$currDate' AND e.`current_dept_nr`=$clinic AND
            (TIMESTAMPDIFF(YEAR, date_birth, CURDATE()))$sign $age";
    if($debug) echo $sql;
    $result=$db->Execute($sql);
    $row=$result->FetchRow();

    return $row[0];
}

function getClinicsInfo(){
    global $db;
    $debug=false;

    $currDate=date('Y-m-d');

    $sql="SELECT e.current_dept_nr,d.`name_formal` FROM care_encounter e LEFT JOIN care_department d
            ON e.`current_dept_nr`=d.`nr`
            WHERE encounter_date='$currDate' and e.encounter_class_nr=2 and e.current_dept_nr<>0 GROUP BY d.`nr`ORDER BY d.`name_formal` ASC";
    if($debug) echo $sql;

    $results=$db->Execute($sql);
    $numRows=$results->RecordCount();

    echo '{
    "OpdVisits":[';
    $counter=0;

    while($row=$results->FetchRow()){
        $totalPatients=getTotalOPDPatients($row[0]);
        $males=getTotalPatientsByGender($row[0],'m');
        $females=getTotalPatientsByGender($row[0],'f');
        $below5=getTotalPatientsByAge($row[0],'<=','5');
        $above5=getTotalPatientsByAge($row[0],'>','5');

        echo '{"Clinic":"'.$row[1].'","TotalPatients":"'.$totalPatients.'","Males":"'.$males.'","Females":"'
            .$females.'","Below5":"'.$below5.'","Above5":"'.$above5.'"}';

        $counter++;
        if($counter<$numRows){
            echo ",";
        }
    }

    echo "]}";
}


?>
