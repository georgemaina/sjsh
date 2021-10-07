<?php
require('./roots.php');
require('../include/inc_environment_global.php');

$debug=true;
//$daysBefore=date('Y-m-d', strtotime('-2 day', strtotime(date('Y-m-d'))));

$sql="SELECT DATE_FORMAT(TIMESTAMP,'%Y-%m-%d'),c.`type`, icd_10_code,icd_10_description,COUNT(icd_10_code) AS dcount FROM care_tz_diagnosis d
        LEFT JOIN `care_icd10_en` c ON d.`ICD_10_code`=c.`diagnosis_code`
        WHERE d.type='New' AND c.`type` IN ('OP','OPC') 
        GROUP BY icd_10_code,TIMESTAMP";

if($debug) echo $sql;

$result=$db->Execute($sql);

while($row=$result->FetchRow()){
    $sql1="INSERT INTO `care_ke_opmobodity` (
            `Month`,`Date`,`IDCode`,`Disease`,
          ) VALUES('Month',`Date','IDCode', 'Disease')";

    if($debug) echo $sql1;
    $day=date('d');
     if($result1=$db->Execute($sql1)){
        for($i=0;$i<=31;$i++){
            if($day===$i){
                $sql="Update care_ke_opmobidity set '$i'='$row[dcount]' where icdCode='$row[icd_10_code]'";
                if($debug) echo $sql;
                $db->Execute($sql);
                echo "Posted Diagnosis nr $row[icd_10_code] <br>";
            }
        }
          
    }else{
        echo "error:".$sql1.'<br>';
    }
    
}

?>