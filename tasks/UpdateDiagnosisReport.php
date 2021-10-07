<?php
require('./roots.php');
require('../include/inc_environment_global.php');

$debug=true;
 $day=date('d');
 
//$startDate="2020-08-05";//date('Y-m-d');
$startDate=date('Y-m-d');
  
 $sMonth=new DateTime($startDate);
 $eMonth=$sMonth->format('F');

 echo "checkDiagnosisMonth($startDate)=".checkDiagnosisMonth($startDate)."<br>";
 
 if(checkDiagnosisMonth($startDate)==0){
     $sql="INSERT INTO care_ke_opmobidity(`ReportMonth`,`DateUpdated`,ICDCode,Disease)
        SELECT '".$eMonth."' as Month ,'".$startDate."' as date ,diagnosis_code,description FROM care_icd10_en 
        WHERE `TYPE` IN('OP','OPC')";
        if($debug) echo $sql;
        $results=$db->Execute($sql);
 }

$sql2="SELECT DATE_FORMAT(TIMESTAMP,'%Y-%m-%d') as dday,c.`type`, icd_10_code,icd_10_description,COUNT(icd_10_code) AS dcount FROM care_tz_diagnosis d
        LEFT JOIN `care_icd10_en` c ON d.`ICD_10_code`=c.`diagnosis_code`
        WHERE d.type='New' AND c.`type` IN ('OP','OPC') and DATE_FORMAT(TIMESTAMP,'%Y-%m-%d')='$startDate'
        GROUP BY icd_10_code";


if($debug) echo $sql2;
$result=$db->Execute($sql2);
//$i=1;
$dcount=0;
while($row=$result->FetchRow()){
        for($i=1;$i<=31;$i++){
            $dday=new DateTime($row[dday]);
            $ddays=$dday->Format('d');
            if($ddays==$i){
                $sql3="Update care_ke_opmobidity set `$i`='$row[dcount]',DateUpdated='$startDate' where icdCode='$row[icd_10_code]'
				and reportMonth=DATE_FORMAT('$startDate','%M')and DATE_FORMAT(DateUpdated,'%Y')=DATE_FORMAT('$startDate','%Y')";
                if($debug) echo $sql3."<br>";
                $db->Execute($sql3);
               
           }
        }
    
}

//830685

function checkDiagnosisMonth($reportingDate){
    global $db;
    $debug=true;
    
    $sql="SELECT DISTINCT `ReportMonth` FROM care_ke_opmobidity WHERE `ReportMonth`=DATE_FORMAT('$reportingDate','%M')
	and DATE_FORMAT(DateUpdated,'%Y')=DATE_FORMAT('$reportingDate','%Y')";
	 if($debug) echo $sql."<br>";
    $results=$db->Execute($sql);
    $rcount=$results->RecordCount();
    
	//echo "rcount=$rcount"
    if($rcount>0){
        return 1;
    }else{
        return 0;
    }
}

?>