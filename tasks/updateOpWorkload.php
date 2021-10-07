<?php
require('./roots.php');
require('../include/inc_environment_global.php');

$debug=true;
 
//$reportDate="2020-04-20";//date('Y-m-d');

//date_default_timezone_set('UTC');

	// Start date
$date = date('Y-m-d');//'2020-08-01';
// End date
$end_date = date('Y-m-d');//'2020-08-05';

while (strtotime($date) <= strtotime($end_date)) {
    //echo "$date<br>";
    $reportDate=$date;
	//$reportDate=date('Y-m-d');//$date;
    if(verifyCurrentDate($reportDate)){
        updateDailyNewCounts($reportDate);
        echo "Report for $reportDate already Posted";
    }else{
        if(insertDefaultColumns($reportDate)){
             updateDailyNewCounts($reportDate);
             updateDailyReturnCounts($reportDate);
        }else{
            echo "unable to create default columns";
        }
    }

    $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
}

if(verifyCurrentDate($reportDate)){
    updateDailyNewCounts($reportDate);
    echo "Report for $reportDate already Posted";
}else{
    if(insertDefaultColumns($reportDate)){
         updateDailyNewCounts($reportDate);
         updateDailyReturnCounts($reportDate);
    }else{
        echo "unable to create default columns";
    }
}

function updateDailyNewCounts($reportDate){
    global $db;
    $debug=true;

    $sql="SELECT `deptID`,`parent`,`opCode`,`Description`,`isParent` FROM `care_ke_opvisitsvars`";
    if($debug) echo $sql."<br>";
    $results=$db->execute($sql);
    while($row=$results->FetchRow()){
        $sql = "select count(e.encounter_nr) as encCounter,e.pid from care_encounter e 
        INNER JOIN care_person p on e.pid=p.pid
        INNER JOIN care_encounter_location l ON e.`encounter_nr`=l.`encounter_nr`
        WHERE  l.location_nr='$row[deptID]' and encounter_date = '$reportDate'";

        if ($row['Description']==='Over 5-Male') {
            $sql = $sql . " AND (YEAR(NOW())-YEAR(p.date_birth))>5 and p.sex='m'";
        }

        if ($row['Description']==='Over 5-Female') { 
            $sql = $sql . " AND (YEAR(NOW())-YEAR(p.date_birth))>5 and p.sex='f'";
        }

        if ($row['opCode']==='A.1.3') {
            $sql = $sql . " AND (YEAR(NOW())-YEAR(p.date_birth))<5 and p.sex='m'";
        }

        if ($row['opCode']==='A.1.4') {
            $sql = $sql . " AND (YEAR(NOW())-YEAR(p.date_birth))<5 and p.sex='f'";
        }

            $sql = $sql . " and e.encounter_class_nr=2 group by pid";
            if ($debug)
                echo $sql."<br>";
            $results1 = $db->Execute($sql);
            //            $row=$result->FetchRow();
            $counter = 0;
            while ($row2 = $results1->FetchRow()) {
                $sql3 = "select count(e.pid) as prevPid from care_encounter e 
                        where encounter_date<'$reportDate'
                        and e.pid=$row2[1]";
                if ($debug)
                    echo $sql3 ."<br>";
                $result3 = $db->Execute($sql3);
                $row3 = $result3->FetchRow();
                if ($row3[0] == 0) {
                    $counter = $counter + 1;
                }
            }
            echo "<br>Counter is ".$counter." Code is ".$row['opCode']." <br>";
            $sql="UPDATE care_ke_opworkload set `New` = '$counter' WHERE `opCode` = '".$row['opCode']."' and reportDate='$reportDate' 
                  AND opCode<>Parent";
            if($debug) echo $sql;
            $db->Execute($sql);
    }
    

}

function updateDailyReturnCounts($reportDate){
    global $db;
    $debug=true;

    $sql="SELECT `deptID`,`parent`,`opCode`,`Description`,`isParent` FROM `care_ke_opvisitsvars`";
    if($debug) echo $sql;
    $results=$db->execute($sql);
    
    while($row=$results->FetchRow()){
        $sql = "SELECT count(e.encounter_nr) as encCounter,e.pid from care_encounter e 
        INNER JOIN care_person p on e.pid=p.pid
        INNER JOIN care_encounter_location l ON e.`encounter_nr`=l.`encounter_nr`
        WHERE  l.location_nr='$row[deptID]' and encounter_date = '$reportDate'";

        if ($row['Description']==='Over 5-Male') {
            $sql = $sql . " AND (YEAR(NOW())-YEAR(p.date_birth))>5 and p.sex='m'";
        }

        if ($row['Description']==='Over 5-Female') { 
            $sql = $sql . " AND (YEAR(NOW())-YEAR(p.date_birth))>5 and p.sex='f'";
        }

        if ($row['opCode']==='A.1.3') {
            $sql = $sql . " AND (YEAR(NOW())-YEAR(p.date_birth))<5 and p.sex='m'";
        }

        if ($row['opCode']==='A.1.4') {
            $sql = $sql . " AND (YEAR(NOW())-YEAR(p.date_birth))<5 and p.sex='f'";
        }

            $sql = $sql . " and e.encounter_class_nr=2 group by pid";
            if ($debug)
                echo $sql."<br>";
            $result = $db->Execute($sql);
            //            $row=$result->FetchRow();
            $counter = 0;
            while ($row2 = $result->FetchRow()) {
                $sql3 = "select count(e.pid) as prevPid from care_encounter e 
                        where encounter_date<'$reportDate'
                        and e.pid=$row2[1]";
                if ($debug)
                    echo $sql3."<br>";
                $result3 = $db->Execute($sql3);
                $row3 = $result3->FetchRow();
                if ($row3[0] > 0) {
                    $counter = $counter + 1;
                }
            }  
            echo "<br>Counter is ".$counter." Code is ".$row['opCode']." <br>";
            $sql4="UPDATE care_ke_opworkload set `Return` = '$counter' WHERE `opCode` = '".$row['opCode']."' and reportDate='$reportDate' AND opCode<>Parent";
            if($debug) echo $sql4."<br>";
            $db->Execute($sql4);
    }
       

}


function insertDefaultColumns($reportDate){
    global $db;
    $debug=false;
    $reportTime=date('H:i:s');
    
    $sql="INSERT INTO `care_ke_opworkload` (`Parent`,`OpCode`,`Description`,`ReportDate`,`ReportTime`)
      SELECT `Parent`,`opCode`,`Description`,'$reportDate','$reportTime' FROM `care_ke_opvisitsvars` WHERE parent LIKE 'A%'";
        if($debug) echo $sql."<br>";
      if($db->Execute($sql)){
          return true;
      }else{
          return false;
      }
}

function verifyCurrentDate($reportDate){
    global $db;
    $debug=false;

    $sql="Select count(opcode) from care_ke_opworkload where reportDate='$reportDate'";
    if($debug) echo $sql;
    $results=$db->Execute($sql);
    $row=$results->FetchRow();

    if($row[0]>0){
        return true;
    }else{
        return false;
    }
}

?>