<?php
require('roots.php');
require('../include/inc_environment_global.php');

//global $db;
$debug=false;
($debug) ? $db->debug=true : $db->debug=FALSE;

$sql="SELECT partcode,reorderlevel from care_tz_drugsandservices where reorderlevel<>''";
echo $sql;
$result = $db->Execute($sql);
while($row = $result->FetchRow()){
       	$sql3="UPDATE care_ke_locstock  SET reorderlevel='$row[1]' WHERE stockid ='$row[partcode]' and reorderlevel<>0";
	if($db->Execute($sql3)){
            echo "Success - ".$sql3.'<br>';
        }else{
            echo "Failure - ".$sql3.'<br>';
        }
}

