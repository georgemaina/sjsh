<?php
require('roots.php');
require('../include/inc_environment_global.php');

//global $db;
$debug=false;
($debug) ? $db->debug=true : $db->debug=FALSE;

$sql="SELECT st_code,Purch_unit,Unit_qty,unit_meas FROM `UnitQuantities` where st_code is not null";
echo $sql;
$result = $db->Execute($sql);

while($row = $result->FetchRow()){

       	$sql3="UPDATE care_tz_drugsandservices  SET unit_measure='$row[unit_meas]',
                `Purchasing_Unit`='$row[Purch_unit]',`Unit_qty`='$row[Unit_qty]' WHERE partcode ='$row[st_code]'";
	    if($db->Execute($sql3)){
            echo "Success - ".$sql3.'<br>';
        }else{
            echo "Failure - ".$sql3.'<br>';
        }
}

