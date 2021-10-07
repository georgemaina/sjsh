<?php
require('roots.php');
require('../include/inc_environment_global.php');

//global $db;
$debug=false;
($debug) ? $db->debug=true : $db->debug=FALSE;

$sql="select `partcode`,`unit_measure`,`Unit_qty`,`Purchasing_Unit` from `care_tz_drugsandservices3`";
echo $sql;
$result = $db->Execute($sql);

while($row = $result->FetchRow()){

       	$sql3="UPDATE care_tz_drugsandservices  SET unit_measure='$row[unit_measure]',
                `Purchasing_Unit`='$row[Purchasing_Unit]',`Unit_qty`='$row[Unit_qty]' WHERE partcode ='$row[partcode]'";
	    if($db->Execute($sql3)){
            echo "Success - ".$sql3.'<br>';
        }else{
            echo "Failure - ".$sql3.'<br>';
        }
}

