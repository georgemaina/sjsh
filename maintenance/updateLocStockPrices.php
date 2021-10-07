<?php
require('roots.php');
require($root_path . 'include/inc_environment_global.php');

global $db;
$debug=false;
($debug) ? $db->debug=true : $db->debug=FALSE;

$sql="SELECT item,price FROM missingitems WHERE Stockid IN (SELECT partcode FROM care_tz_drugsandservices) AND price>0";
$result = $db->Execute($sql);
echo $sql;
while($row = $result ->FetchRow()){
       	$sql3="UPDATE care_tz_drugsandservices SET unit_price = '$row[price]' WHERE item_description ='$row[item]'";
      echo $sql3;
	    if($db->Execute($sql3)){
            echo "Success - ".$sql3.'<br>';
        }else{
            echo "Failure - ".$sql3.'<br>';
        }
}

