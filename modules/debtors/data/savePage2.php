<?php
$Balanitis=$_POST[Balanitis];
$Posthitis=$_POST[Posthitis];
$adhesions=$_POST[adhesions];
$analgesiaGiven2=$_POST[analgesiaGiven2];
$balano=$_POST[balano];
$bloodPressure=$_POST[bloodPressure];
$bloodPressure2=$_POST[bloodPressure2];
$buriedPenis=$_POST[buriedPenis];
$chordee=$_POST[chordee];
$comments=$_POST[comments];
$complicationDesc=$_POST[complicationDesc];
$complications=$_POST[complications];
$deviceClamp=$_POST[deviceClamp];
$deviceClampsize=$_POST[deviceClampsize];
$diabetes=$_POST[diabetes];
$diathemyUsed=$_POST[diathemyUsed];
$diathermy=$_POST[diathermy];
$dilation=$_POST[dilation];
$dorsalSlit=$_POST[dorsalSlit];

$startTime1=new DateTime($_POST[startTime]);
$startTime=$startTime1->format('H:i:s');

$endTime1=new DateTime($_POST[endTime]);
$endTime=$endTime1->format('H:i:s');


$epispadias=$_POST[epispadias];
$foreskinRetracting=$_POST[foreskinRetracting];
$genCondition=$_POST[genCondition];
$genitalWarts=$_POST[genitalWarts];
$hamophilia=$_POST[hamophilia];
$hypospadias=$_POST[hypospadias];
$ioComplications=$_POST[ioComplications];
$lignocaine=$_POST[lignocaine];
$longPenis=$_POST[longPenis];
$lymphad=$_POST[lymphad];
$macaine=$_POST[macaine];


$macaine2='';
foreach ($_POST as $key=>$value){
     if(substr($key,0,8)=='macaine1'){
        $macaine2=$macaine2.$value.',';
    }
}
$macaine1=$macaine2;


$methodsCon='';
foreach ($_POST as $key=>$value){
     if(substr($key,0,10)=='methodsCon'){
        $methodsCon=$methodsCon.$value.',';
    }
}
$methodsCon2=$methodsCon;

$microPenis=$_POST[microPenis];
$micturitionPain=$_POST[micturitionPain];
$moderateTightRing=$_POST[moderateTightRing];
$otherPains=$_POST[otherPains];
$overhangingForeskin=$_POST[overhangingForeskin];
$painErection=$_POST[painErection];
$pallor=$_POST[pallor];
$paraPhimosis=$_POST[paraPhimosis];
$penileTorsion=$_POST[penileTorsion];
$penoscotal=$_POST[penoscotal];
$phimosis=$_POST[phimosis];
$phimosisPinHole=$_POST[phimosisPinHole];
$poInstructionSheet=$_POST[poInstructionSheet];
$prepucial=$_POST[prepucial];
$pulse=$_POST[pulse];
$pulse2=$_POST[pulse2];

$reviewPeriod='';
foreach ($_POST as $key=>$value){
     if(substr($key,0,13)=='reviewPeriod1'){
        $reviewPeriod=$reviewPeriod.$value.',';
    }
}
$reviewPeriod1=$reviewPeriod;

$reviewPeriod3='';
foreach ($_POST as $key=>$value){
     if(substr($key,0,13)=='reviewPeriod2'){
        $reviewPeriod3=$reviewPeriod3.$value.',';
    }
}
$reviewPeriod2=$reviewPeriod3;

$scrotumSwelling=$_POST[scrotumSwelling];
$shortSkin=$_POST[shortSkin];
$soakedDressing=$_POST[soakedDressing];

$surgicalOP=$_POST[surgicalOP];
$surgicalOPDesc=$_POST[surgicalOPDesc];
$tightRing=$_POST[tightRing];
$urethra=$_POST[urethra];
$wasting=$_POST[wasting];
$weight=$_POST[weight];

require 'config.php';
$conn1 = mysqli_connect($server, $user, $pass, $database, $port);
if (!$conn1) {
    die('Could not connect to MySQL: ' . mysqli_connect_error());
}
mysqli_query($conn1, 'SET NAMES \'utf8\'');

$sql="INSERT INTO `mmc_clientsform2`
            (`clientNo`,`Balanitis`,`Posthitis`,`adhesions`,`analgesiaGiven2`,
             `balano`,`bloodPressure`,`bloodPressure2`,`buriedPenis`,`chordee`,`comments`,`complicationDesc`,
             `complications`,`deviceClamp`,`deviceClampsize`,`diabetes`,`diathemyUsed`,`diathermy`,`dilation`,
             `dorsalSlit`,`endTime`,`epispadias`,`foreskinRetracting`,`genCondition`,`genitalWarts`,`hamophilia`,
             `hypospadias`,`ioComplications`, `lignocaine`,`longPenis`,`lymphad`,`macaine`,`macaine1`, `methodsConventional`,
             `microPenis`, `micturitionPain`,`moderateTightRing`,`otherPains`,`overhangingForeskin`, `painErection`,
             `pallor`,`paraPhimosis`, `penileTorsion`,`penoscotal`, `phimosis`,
             `phimosisPinHole`,`poInstructionSheet`,`prepucial`,`pulse`,`pulse2`, `reviewPeriod1`,`reviewPeriod2`,
             `scrotumSwelling`,`shortSkin`,`soakedDressing`,`startTime`,`surgicalOP`,`surgicalOPDesc`,`tightRing`,
             `urethra`,`wasting`,`weight`)
VALUES (      '1001','$Balanitis','$Posthitis','$adhesions','$analgesiaGiven2',
              '$balano','$bloodPressure','$bloodPressure2','$buriedPenis','$chordee','$comments','$complicationDesc',
              '$complications','$deviceClamp','$deviceClampsize','$diabetes','$diathemyUsed','$diathermy','$dilation',
               '$dorsalSlit','$endTime','$epispadias','$foreskinRetracting','$genCondition','$genitalWarts','$hamophilia',
              '$hypospadias','$ioComplications','$lignocaine','$longPenis','$lymphad','$macaine','$macaine1','$methodsCon2',
              '$microPenis','$micturitionPain','$moderateTightRing','$otherPains','$overhangingForeskin','$painErection',
              '$pallor','$paraPhimosis','$penileTorsion','$penoscotal','$phimosis',
              '$phimosisPinHole','$poInstructionSheet','$prepucial','$pulse','$pulse2','$reviewPeriod1','$reviewPeriod2',
              '$scrotumSwelling','$shortSkin','$soakedDressing','$startTime','$surgicalOP','$surgicalOPDesc','$tightRing',
              '$urethra','$wasting','$weight')";

if($result = mysqli_query($conn1, $sql)){
    echo '{success:true,msg:"Form Successfully Saved"}';
}else{
    echo '{success:false,msg:"Form could not be saved please check you entries"}';
}

mysqli_close($conn1);
                
?>