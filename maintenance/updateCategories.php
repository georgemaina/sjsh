<?php
require('roots.php');
require($root_path . 'include/inc_environment_global.php');

$sql="SELECT ST_CODE,UNIT_QTY,DESC1  FROM `cssd`";
$result=$db->Execute($sql);

while($row=$result->FetchRow()){
    $sql1="update care_tz_drugsandservices set unit_qty=$row[1] where PARTCODE='$row[0]'";
    
    if($result1=$db->Execute($sql1)){
          echo "updated drug units $row[DESC1] Successfully to $row[UNIT_QTY]<br>";
    }else{
        echo "error:".$sql1.'<br>';
    }
    
}

?>