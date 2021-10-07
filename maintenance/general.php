<?php

require('./roots.php');
require('../include/inc_environment_global.php');

global $db;

$sql="SELECT number,date_reg FROM `register social`";
$result=$db->Execute($sql);
while($row=$result->FetchRow()){
$date = new DateTime($row[1]);
 $newDate=$date->format('Y-m-d H:i:s');
    $sql1="update care_person set date_reg='$newDate' where selian_pid='$row[0]'";
    
    if($result1=$db->Execute($sql1)){
          echo "Success update date from $row[1] to $newDate<br>";
    }else{
        echo "error:".$sql1.'<br>';
    }
    
}

?>