
<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');

$limit = $_POST[limit];
$start = $_POST[start];
$item_number = $_POST[item_number];

$dt1 = new DateTime(date($_REQUEST[date2]));
$date1 = $dt1->format('Y-m-d');

$dt2 = new DateTime(date($_REQUEST[date1]));
$date2 = $dt2->format('Y-m-d');

$reportMonth=$_REQUEST[reportMonth];
$reportType=$_REQUEST[reportType];


//getIPMorbidity(20, 1);
$task = ($_POST['task']) ? ($_POST['task']) : $_REQUEST['task'];
switch ($task) {
    case "mobidity":
        getMorbidity($date1,$date2,$reportType,$limit, $start);
        break;
    case "ipmorbidity":
        getIPMorbidity($limit, $start);
        break;
    case "ipReports":
        getIPreports();
        break;
    case "opVisits":
        getOPvisits($date1, $date2);
        break;
    case "create":
        createItem($item_number);
        break;
    case "adjustStock":
        stockAdjust($item_number);
        break;
    case "deleteItem":
        deleteItem();
        break;
    case "getStockMovement":
        getStockMovement();
        break;
    default:
        echo "{failure:true}";
        break;
}//end switch

function getStockMovement() {
    global $db;
    $debug=true;

    $startDate =  new DateTime($_REQUEST['startDate']);
    $EndDate = new DateTime($_REQUEST['sndDate']);
    $date1 = $startDate->format('Y-m-d');
    $date2 = $EndDate->format('Y-m-d');

    $LocCode = $_REQUEST['locCode'];
    $PartCode = $_REQUEST['partCode'];
    $category= $_REQUEST['category'];
    
   $sql="select `stkmoveno`,`stockid`,d.`item_description`,d.`unit_measure`,t.`typeName`,s.`transno`,`loccode`,`supLoccode`,`trandate`,`pid`,
            `price`,`qty`,`newqoh`,`hidemovt`,`narrative`,`inputuser` 
          from  `care_ke_stockmovement` s left join care_tz_drugsandservices d on s.`stockid`=d.`partcode`
          left join care_ke_transactionnos t on s.`type`=t.`typeID` where partcode<>''";

        if($PartCode<>''){
            $sql=$sql." and stockid='$PartCode'";
        }
        $request = $db->Execute($sql);

        $total = $request->RecordCount();

    echo '{
    "total":"' . $total . '","stockmovement":[';
    $counter = 0;
    while ($row = $request->FetchRow()) {
        echo '{"PartCode":"' . $row[stockid] . '","Description":"' . $row[item_description] . '","UnitsMeasure":"' . $row[unit_measure] 
                . '","Date":"' . $row[trandate] . '","TransType":"' . $row[typeName]. '","TransNo":"' . $row[transno]
                . '","Narration":"' . $row[narrative]. '","Location":"' . $row[loccode]. '","Cost":"' . $row[price]
                . '","Qty":"' . $row[qty]. '","Level":"' . $row[newqoh]. '","Operator":"' . $row[inputuser]. '"}';
        if ($counter < $total) {
            echo ",";
        }
        $counter++;
    }
    echo ']}';
}

function getMorbidity($date1,$date2,$reportType,$limit, $start) {
    global $db;
    //$strMonth=date(c.timestamp);

    echo '{
   "mobidity":[';

    $sql="select diagnosis_code,Description from care_icd10_en where type='$reportType' order by diagnosis_code asc";
    //echo $sql;
    $results=$db->Execute($sql);

       while($row = $results->FetchRow()){

           echo '{"icdCode":"' . $row[0] . '","disease":"' . $row[1] . '",';
           $totals=0;
           for ($i = 1; $i <= 31; $i++) {
               $rcount= getMobidityCounts($row[0],$reportType,$date1,$date2,$i);

               if($row[0]=='OP63' ||$row[0]=='OPC62'){
                   $rcount=getSummobidityPerDay($date1,$date2,$i,$reportType);
               }
                echo '"'.$i.'":"' . $rcount .'",';
               $totals=$totals+$rcount;
            }
           echo '"TOTALS":"' . $totals. '"},';
       }

    echo ']}';
}

function getSummobidityPerDay($date1,$date2,$reportDay,$reportType){
    global $db;
    $debug=false;

    if($reportDay<10) $reportDay='0'.$reportDay;

    $sql="Select Count(b.`diagnosis_code`) as totalCases from  care_icd10_en b left join care_tz_diagnosis c
            on b.diagnosis_code=c.ICD_10_code where c.timestamp between '$date2' and '$date1'
            and DATE_FORMAT(c.timestamp,'%d')='$reportDay' AND b.class_sub='$reportType'";

    if($debug) echo $sql;

    $results=$db->Execute($sql);
    $row=$results->FetchRow();

     return $row[0];
}


 function getMobidityCounts($rcode,$reportType,$date1,$date2,$reportDay){
     global $db;

     if($reportDay<10) $reportDay='0'.$reportDay;

     $sql1 = "select b.diagnosis_code as rCode,b.description as Disease,day(c.timestamp) as rday,COUNT(b.`diagnosis_code`) AS rcount
        from care_icd10_en b left join care_tz_diagnosis c
            on b.diagnosis_code=c.ICD_10_code where b.type='$reportType' and c.timestamp between '$date2' and '$date1'
            and DATE_FORMAT(c.timestamp,'%d')='$reportDay' and b.diagnosis_code='$rcode' group by b.diagnosis_code";


    // echo $sql1;
     $result1 = $db->Execute($sql1);
     $row=$result1->FetchRow();

     if($row[rcount]<>''){
         return $row[rcount];
     }else{
         return '0';
     }

 }

function doCount($icdcode, $min, $max, $sign) {
    global $db;
    if ($sign == "<") {
        $sql = 'select count(b.ICD_10_code) as rcount from care_tz_diagnosis b inner join care_icd10_en k
        on k.diagnosis_code=b.ICD_10_code
            inner join care_person c on c.pid=b.PID  where k.type<>"OP" and (year(now())-year(c.date_birth))<1 and b.ICD_10_code="' . $icdcode . '"';
    } elseif ($sign == 'Between') {
        $sql = 'select count(b.ICD_10_code) as rcount from care_tz_diagnosis b inner join care_icd10_en k
        on k.diagnosis_code=b.ICD_10_code
            inner join care_person c on c.pid=b.PID  where k.type<>"OP" and (year(now())-year(c.date_birth))
            BETWEEN "' . $min . '" and "' . $max . '" and b.ICD_10_code="' . $icdcode . '"';
    } else {
        $sql = 'select count(b.ICD_10_code) as rcount from care_tz_diagnosis b inner join care_icd10_en k
        on k.diagnosis_code=b.ICD_10_code
            inner join care_person c on c.pid=b.PID  where k.type<>"OP" and
            (year(now())-year(c.date_birth))>65 and b.ICD_10_code="' . $icdcode . '"';
    }
   //echo $sql;
    $result = $db->Execute($sql);
    $row = $result->FetchRow();
    return $row[0];
}

function getIPMorbidity($limit, $start) {
    global $db;
    $sql = 'select icd_10_code,icd_10_description,count(icd_10_code) as dcount from care_tz_diagnosis 
               where icd_10_code NOT LIKE "OP%"';

    $dt1 = $_REQUEST[date1];

    $startDate = new DateTime($dt1);
    $date1 = $startDate->format("Y-m-d");

    $dt2 = $_REQUEST[date2];
    $endDate = new DateTime($dt2);
    $date2 = $endDate->format("Y-m-d");

    if ($dt1 <> '' && $dt2 <> '') {
        $sql = $sql . "  and `timestamp` between '$date1' and '$date2'";
    }

    $sql = $sql . "  group by icd_10_code";

    $result1 = $db->Execute($sql);
    // echo $sql;

    $data1 = '';
    $count = 0;
    while ($row1 = $result1->FetchRow()) {
        $data1[1][] = $row1[0];
        $data1[2][] = $row1[1];
        $count = $count + 1;
    }

    echo '{
            "Total":' . $count . ',"ipmorbidity":[';

    $counter = 0;
    for ($i = 0; $i < $count; $i++) {
        echo '{"icdcode":"' . $data1[1][$i] . '","desc":"' . trim($data1[2][$i]) . '",';

//        $A1sum = 0;
//             for($i=0;$i<$j;$i++){
        $FA1sum = getIpmobidityCount(1, 1, 'FA', $data1[1][$i], '<', $date1, $date2,'F');
        $FD1sum = getIpmobidityCount(1, 1, 'FD', $data1[1][$i], '<', $date1, $date2,'F');
        $FA2sum = getIpmobidityCount(1, 4, 'FA', $data1[1][$i], 'between', $date1, $date2,'F');
        $FD2sum = getIpmobidityCount(1, 4, 'FD', $data1[1][$i], 'between', $date1, $date2,'F');
        $FA3sum = getIpmobidityCount(5, 14, 'FA', $data1[1][$i], 'between', $date1, $date2,'F');
        $FD3sum = getIpmobidityCount(5, 14, 'FD', $data1[1][$i], 'between', $date1, $date2,'F');
        $FA4sum = getIpmobidityCount(15, 44, 'FA', $data1[1][$i], 'between', $date1, $date2,'F');
        $FD4sum = getIpmobidityCount(15, 44, 'FD', $data1[1][$i], 'between', $date1, $date2,'F');
        $FA5sum = getIpmobidityCount(45, 45, 'FA', $data1[1][$i], '>', $date1, $date2,'F');
        $FD5sum = getIpmobidityCount(45, 45, 'FD', $data1[1][$i], '>', $date1, $date2,'F');
        $MA6sum = getIpmobidityCount(1, 1, 'MA', $data1[1][$i], '<', $date1, $date2,'M');
        $MD6sum = getIpmobidityCount(1, 1, 'MD', $data1[1][$i], '<', $date1, $date2,'M');
        $MA7sum = getIpmobidityCount(1, 4, 'MA', $data1[1][$i], 'between', $date1, $date2,'M');
        $MD7sum = getIpmobidityCount(1, 4, 'MD', $data1[1][$i], 'between', $date1, $date2,'M');
        $MA8sum = getIpmobidityCount(5, 14, 'MA', $data1[1][$i], 'between', $date1, $date2,'M');
        $MD8sum = getIpmobidityCount(5, 14, 'MA', $data1[1][$i], 'between', $date1, $date2,'M');
        $MA9sum = getIpmobidityCount(15, 44, 'MA', $data1[1][$i], 'between', $date1, $date2,'M');
        $MD9sum = getIpmobidityCount(15, 44, 'MD', $data1[1][$i], 'between', $date1, $date2,'M');
        $MA10sum = getIpmobidityCount(45, 45, 'MA', $data1[1][$i], '>', $date1, $date2,'M');
        $MD10sum = getIpmobidityCount(45, 45, 'MD', $data1[1][$i], '>', $date1, $date2,'M');

        $totalA=$FA1sum+$FA2sum+$FA3sum+$FA4sum+$FA5sum+$MA6sum+$MA7sum+$MA8sum+$MA9sum+$MA10sum;
        $totalD=$FD1sum+$FD2sum+$FD3sum+$FD4sum+$FD5sum+$MD6sum+$MD7sum+$MD8sum+$MD9sum+$MD10sum;

        $totals=$totalA+$totalD;
//             }
        echo '"FA1":"' . $FA1sum . '","FD1":"' . $FD1sum . '","FA2":"' . $FA2sum . '","FD2":"' . $FD2sum . '",
                    "FA3":"' . $FA3sum . '","FD3":"' . $FD3sum . '","FA4":"' . $FA4sum . '","FD4":"' . $FD4sum . '",
                    "FA5":"' . $FA5sum . '","FD5":"' . $FD5sum . '","MA6":"' . $MA6sum . '","MD6":"' . $MD6sum . '",
                    "MA7":"' . $MA7sum . '","MD7":"' . $MD7sum . '","MA8":"' . $MA8sum . '","MD8":"' . $MD8sum . '",
                    "MA9":"' . $MA9sum . '","MD9":"' . $MD9sum . '","MA10":"' . $MA10sum . '","MD10":"' . $MD10sum . '",
                    "Alive":"' . $totalA . '","Dead":"' . $totalD. '","Totals":"' . $totals . '"}';

        $counter++;

        if ($i <  $counter) {
            echo ',';
        }

    }


    echo ']}';
}

function getIpmobidityCount($age1, $age2, $patientstatus, $icdCode, $sign, $date1, $date2,$sex) {
    global $db;
    $debug = false;
    $ptstatus=substr($patientstatus,1);
    $sql = "select count(b.PID) as pcount from care_tz_diagnosis k inner join
            care_person b on k.pid=b.pid  inner join care_icd10_en e on e.diagnosis_code=k.ICD_10_code
            where k.icd_10_code NOT LIKE 'OP%' AND k.ICD_10_code='$icdCode'
            and k.pataintStatus='$ptstatus'";
    if ($sign == 'between') {
        $sql = $sql . " and (year(now())-year(b.date_birth)) between $age1 and $age2";
    } else if ($sign == '>') {
        $sql = $sql . " and (year(now())-year(b.date_birth)) > $age1";
    } else if ($sign == '<') {
        $sql = $sql . " and (year(now())-year(b.date_birth)) < $age1";
    }

    if($sex){
        $sql=$sql ." and b.sex='$sex'";
    }

    if ($date1 <> '' && $date2 <> '') {
        $sql = $sql . " and `timestamp` between '$date1' and '$date2'";
    }

    if ($debug)
        echo $sql;

    $result = $db->Execute($sql);
    $row = $result->FetchRow();

    return $row[0];
}

function getOPvalues($age, $sex, $clinic, $sign, $encDate1, $encDate2, $sign2) {
    global $db;
    $debug =false;
    if ($sign2 == "=") {

        $sql = "select count(e.encounter_nr) as encCounter,e.pid from care_encounter e 
                left join care_person p on e.pid=p.pid
                where e.current_dept_nr='$clinic' and encounter_date between '$encDate1' and '$encDate2'";

        if ($age) {
            $sql = $sql . " AND (YEAR(NOW())-YEAR(p.date_birth))$sign $age";
        }


        if ($sex) {
            $sql = $sql . " and p.sex='$sex'";
        }

        $sql = $sql . " and e.encounter_class_nr=2 group by pid";
        if ($debug)
            echo $sql;
        $result = $db->Execute($sql);
//            $row=$result->FetchRow();
        $counter = 0;
        while ($row = $result->FetchRow()) {
            $sql3 = "select count(e.pid) as prevPid from care_encounter e 
                    where encounter_date<='$encDate2'
                    and e.pid=$row[1]";
            if ($debug)
                echo $sql3;
            $result3 = $db->Execute($sql3);
            $row3 = $result3->FetchRow();
            if ($row3[0] == 1) {
                $counter = $counter + 1;
            }
        }
        $pcount = $counter;
    } else {
        $sql = "select e.pid,count(e.encounter_nr) as encCounter,e.pid from care_encounter e  
                    left join care_person p on e.pid=p.pid
                    where e.current_dept_nr='$clinic' and encounter_date between '$encDate1' and '$encDate2'";

        if ($age) {
            $sql = $sql . " AND (YEAR(NOW())-YEAR(p.date_birth))$sign $age";
        }
        if ($sex) {
            $sql = $sql . " and p.sex='$sex'";
        }

        $sql = $sql . " and e.encounter_class_nr=2 group by pid";
        if ($debug)
            echo $sql;
        $result = $db->Execute($sql);
//            $row=$result->FetchRow();
        $counter = 0;
        while ($row = $result->FetchRow()) {
            $sql3 = "select e.pid from care_encounter e 
                        where encounter_date<'$encDate2'
                        and e.pid=$row[0]";
            if ($debug)
                echo $sql3;
            $result3 = $db->Execute($sql3);
            $row3 = $result3->FetchRow();
            if ($row3[0] > 1) {
                $counter = $counter + 1;
            }
        }
        $pcount = $counter;
    }

    return $pcount;
}

function getOPvisits($date1, $date2) {
    global $db;

    $sql = 'SELECT parent,opCode,description FROM care_ke_opvisitsvars where parent like "A%"';
    $result = $db->Execute($sql);
    $data = '';
    while ($row = $result->FetchRow()) {
        $data[1][] = $row[1];
        $data[2][] = $row[2];
    }

    $sql = 'SELECT parent,opCode,description FROM care_ke_opvisitsvars where parent like "A%"';
    $result = $db->Execute($sql);
    echo '{"opVisits":[';
    $data[][] = '';
    while ($row = $result->FetchRow()) {
        if ($data[1][1] == $row[1]) {
            $newVar = getOPvalues("5", "m", "40", ">", $date1, $date2, "="); //Over 5-Male
            $retVar = getOPvalues("5", "m", "40", ">", $date1, $date2, ">"); //Over 5-Male
        } elseif ($data[1][2] == $row[1]) {
            $newVar = getOPvalues("5", "f", "40", ">", $date1, $date2, '=');
            $retVar = getOPvalues("5", "f", "40", ">", $date1, $date2, ">");
        } elseif ($data[1][3] == $row[1]) {
            $newVar = getOPvalues("5", "m", "40", "<=", $date1, $date2, '=');
            $retVar = getOPvalues("5", "m", "40", "<=", $date1, $date2, ">");
        } elseif ($data[1][4] == $row[1]) {
            $newVar = getOPvalues("5", "f", "40", "<=", $date1, $date2, '=');
            $retVar = getOPvalues("5", "f", "40", "<=", $date1, $date2, ">");
        } elseif ($data[1][5] == $row[1]) {
            $newVar = getOPvalues("0", "", "40", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "40", ">", $date1, $date2, ">");
        } elseif ($data[1][6] == $row[1]) {
            $newVar = getOPvalues("0", "", "52", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "52", ">", $date1, $date2, ">");
        } elseif ($data[1][7] == $row[1]) {
            $newVar = getOPvalues("0", "", "6", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "6", ">", $date1, $date2, ">");
        } elseif ($data[1][8] == $row[1]) {
            $newVar = getOPvalues("0", "", "7", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "7", ">", $date1, $date2, ">");
        } elseif ($data[1][9] == $row[1]) {
            $newVar = getOPvalues("0", "", "47", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "47", ">", $date1, $date2, ">");
        } elseif ($data[1][10] == $row[1]) {
            $newVar = getOPvalues("0", "", "53", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "53", ">", $date1, $date2, ">");
        } elseif ($data[1][11] == $row[1]) {
            $newVar = getOPvalues("0", "", "54", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "54", ">", $date1, $date2, ">");
        } elseif ($data[1][12] == $row[1]) {
            $newVar = getOPvalues("0", "", "44", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "44", ">", $date1, $date2, ">");
        } elseif ($data[1][13] == $row[1]) {
            $newVar = getOPvalues("0", "", "57", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "57", ">", $date1, $date2, ">");
        } elseif ($data[1][14] == $row[1]) {
            $newVar = getOPvalues("0", "", "56", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "56", ">", $date1, $date2, ">");
        } elseif ($data[1][15] == $row[1]) {
            $newVar = getOPvalues("0", "", "62", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "62", ">", $date1, $date2, ">");
        } elseif ($data[1][16] == $row[1]) {
            $newVar = getOPvalues("0", "", "48", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "48", ">", $date1, $date2, ">");
        } elseif ($data[1][17] == $row[1]) {
            $newVar = getOPvalues("0", "", "55", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "55", ">", $date1, $date2, ">");
        } elseif ($data[1][18] == $row[1]) {
            $newVar = getOPvalues("0", "", "49", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "49", ">", $date1, $date2, ">");
        } elseif ($data[1][19] == $row[1]) {
            $newVar = getOPvalues("0", "", "43", ">", $date1, $date2, '=');
            $retVar = getOPvalues("0", "", "43", ">", $date1, $date2, ">");
        } else {
            $newVar = 0;
            $retVar = 0;
        }

        echo '{"parent":"' . $row[0] . '","opCode":"' . $row[1] . '",
"Description":"' . $row[2] . '","New":"' . number_format($newVar,0) . '","Ret":"' . number_format($retVar,0) . '","Total":"' . intval($newVar + $retVar) . '"},';
    }

    echo ']}';
}

function getIPvalues($age, $sign, $sign2, $service, $ward) {
    global $db;
    $debug = false;

    $sql = 'SELECT COUNT(a.encounter_nr) AS pcount FROM care_encounter a 
LEFT JOIN care_person b ON a.pid=b.pid
LEFT JOIN care_encounter_location c ON a.encounter_nr=c.encounter_nr
LEFT JOIN care_type_discharge d ON c.discharge_type_nr=d.nr
WHERE a.encounter_class_nr=1 AND a.is_discharged=1 
AND (YEAR(NOW())-YEAR(b.date_birth))' . $sign . '"' . $age . '" AND 
c.discharge_type_nr' . $sign2 . '"' . $service . '" AND a.current_ward_nr in (' . $ward . ')';

    if ($debug)
        echo $sql;
    $result = $db->Execute($sql);
    if ($debug)
        echo $sql;
    if ($row = $result->FetchRow()) {
        return $row[0];
    } else {
        return false;
    }
}

function getIPreports() {
    global $db;

    $sql = 'SELECT parent,opCode,description FROM care_ke_opvisitsvars where parent in("B1","B2")';
    $result = $db->Execute($sql);
    while ($row = $result->FetchRow()) {
        $data[1][] = $row[1];
        $data[2][] = $row[2];
    }


    $sql = 'SELECT parent,opCode,description FROM care_ke_opvisitsvars where parent in("B1","B2")';
    $result = $db->Execute($sql);
    echo '{"ipReports":[';
    while ($row = $result->FetchRow()) {
        for ($i = 1; $i < 14; $i++) {
            if ($data[1][$i] == $row[1]) {
//                getIPvalues($age,$sign, $sign2, $service)
                $newVar = getIPvalues("5", ">", "=", "1", "1,2,3,4,6");
                $retVar = getIPvalues("5", "<", "=", "1", "1,2,3,4,6");
                $matVar = getIPvalues("5", ">", "=", "1", "5");
                $amVar = getIPvalues("5", ">", "=", "1", "1,2,3,4,6");
            } else {
                $newVar = 0;
                $retVar = 0;
            }
        }
        $fDesc = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row[2]);

        echo '{"parent":"' . $row[0] . '","opCode":"' . $row[1] . '",
"Description":"' . $fDesc . '","GenAdults":"' . $newVar . '","GenPaed":"' . $retVar . '",
"Martmoms":"' . $matVar . '","Amenity":"' . $amVar . '","Total":"' . intval($newVar + $retVar + $matVar + $amVar) . '"},';
    }

    echo ']}';
}
?>

