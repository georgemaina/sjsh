<?php
require('roots.php');
require($root_path . 'include/inc_environment_global.php');

$sql="INSERT INTO `kendu`.`stockmaster` (
      `stockid`,`categoryid`,`description`,`longdescription`,`units`,`mbflag`,
      `actualcost`,`lastcost`,`materialcost`,`taxcatid`,`lastcostupdate`,`lastcurcostdate`)
    SELECT partcode,category,item_description,item_description,'each','B',unit_price,
        unit_price,unit_price,'1','2016-07-01','2016-07-01' FROM care_tz_drugsandservices WHERE partcode NOT IN(
    SELECT stockid FROM kendu.`stockmaster`)";
  $result=$db->Execute($sql);

//while($row=$result->FetchRow()){
//    $sql1="update care_tz_drugsandservices set PURCHASING_CLASS=$row[0] where PARTCODE='$row[1]'";
//
//    if($result1=$db->Execute($sql1)){
//          echo "Success <br>";
//    }else{
//        echo "error:".$sql1.'<br>';
//    }
//
//}

?>