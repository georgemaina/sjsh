<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$clientNo=$_POST[clientNo];
$address=$_POST[address];
$age=$_POST[age];

$alcoDate1=new DateTime($_POST[alcoDate]);
$alcoDate=$alcoDate1->format('Y/m/d');

$alcohol_subs=$_POST[alcohol_subs];
$alergies=$_POST[alergies];
$assistant=$_POST[assistant];

$bookingDate1=new DateTime($_POST[regDate]);
$bookingDate=$bookingDate1->format('Y/m/d');

$cd4Taken=$_POST[cd4Taken];
$cd4Count=$_POST[cd4Count];

$cd4CountDate1=new DateTime($_POST[cd4CountDate]);
$cd4CountDate=$cd4CountDate1->format('Y/m/d');

$cellNumber=$_POST[cellNumber];
$circumIndicators1='';
foreach ($_POST as $key=>$value){
    if(substr($key,0,6)=='circum'){
        $circumIndicators1=$circumIndicators1.$value.',';
    }
   
}
$circumIndicators=$circumIndicators1;

$learnCentres1='';
foreach ($_POST as $key=>$value){
 if(substr($key,0,12)=='learnCentres'){
        $learnCentres1=$learnCentres1.$value.',';
        
    }
}
$learnCentres=$learnCentres1;
//echo '"'.$learnCentres1.'"';
$learnMMC1='';
foreach ($_POST as $key=>$value){
     if(substr($key,0,13)=='learnAboutMMC'){
        $learnMMC1=$learnMMC1.$value.',';
    }
}
$learnMMC=$learnMMC1;
//$circumIndicators1=$_POST[circumIndicators1];
//$circumIndicators2=$_POST[circumIndicators2];
$doctor=$_POST[doctor];
$firstName=$_POST[firstName];
$hctStatus=$_POST[hctStatus];
$hivStatus=$_POST[hivStatus];
$idNo=$_POST[idNo];
//$learnCentres1=$_POST[learnCentres1];
//$learnCentres2=$_POST[learnCentres2];
$mmcDate1=new DateTime($_POST[mmcDate]);
$mmcDate=$mmcDate1->format('Y/m/d');

$parentGuard=$_POST[parentGuard];
$relationStatus=$_POST[relationStatus];
$residence=$_POST[residence];
$service=$_POST[service];
$sexPartners=$_POST[sexPartners];
$sexPartners12=$_POST[sexPartners12];
$sexPartnersHIVstat12=$_POST[sexPartnersHIVstat12];
$sexPartnersHivStat=$_POST[sexPartnersHivStat];
$sexStatus=$_POST[sexStatus];
$sexStatus2=$_POST[sexStatus2];
$stiTreatment3mnts=$_POST[stiTreatment3mnts];	
$surname=$_POST[surname];
$tel=$_POST[tel];


require 'config.php';
$conn1 = mysqli_connect($server, $user, $pass, $database, $port);
if (!$conn1) {
    die('Could not connect to MySQL: ' . mysqli_connect_error());
}
mysqli_query($conn1, 'SET NAMES \'utf8\'');


$sql="insert into `mmc_clients`
            (  `clientNo`,`bookingDate`,`firstName`,`surname`,`res_address`,`contactAddress`,`cellNo`,`Tel`,`Age`,
             `IDNo`,`parentGuardTel`,`mmcDate`,`Doctor`,`assistant`,`serviceloc`,`learnCenter`,`circumIndicators`,
             `learnMMC`,`relationStat`,`HCTStat`,`HIVStat`, `CD4Taken`,`CD4CountDate`,`CD4Count`,`sexStat`,`partners12mnt`,
             `HivStat_12mnt`,`sexStat2`,`pertners1mnt`,`hivStat_1mnt`,`alergies`, `alco_substance`,
             `alcoDate`,`STIin6mnts`)
values ('$clientNo','$bookingDate','$firstName', '$surname','$residence','$address','$cellNumber','$tel',
        '$age','$idNo', '$parentGuard', '$mmcDate','$doctor','$assistant','$service','$learnCentres', '$circumIndicators',
        '$learnMMC','$relationStatus','$hctStatus','$hivStatus','$cd4Taken','$cd4CountDate','$cd4Count',
        '$sexStatus','$sexPartners12','$sexPartnersHIVstat12',
        '$sexStatus2','$sexPartners','$sexPartnersHivStat','$alergies','$alcohol_subs','$alcoDate','$stiTreatment3mnts')";

if($result = mysqli_query($conn1, $sql)){
    echo '{success:true,msg:"Form Successfully Saved"}';
}else{
    echo '{success:false,msg:"Form could not be saved please check you entries"}';
}

mysqli_close($conn1);




?>
