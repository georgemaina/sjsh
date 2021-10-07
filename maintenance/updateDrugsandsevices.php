<?php
require('roots.php');
require('../include/inc_environment_global.php');

//global $db;
$debug=false;
($debug) ? $db->debug=true : $db->debug=FALSE;

$sql="SELECT partcode,reorderlevel,salesareas,units,unit_measure,isStockItem from care_tz_drugsandservices2";
echo $sql;
$result = $db->Execute($sql);
while($row = $result->FetchRow()){
       	$sql3="UPDATE care_tz_drugsandservices SET reorderlevel='$row[reorderlevel]',salesareas='$row[salesareas]',units='$row[units]'
                ,unit_measure='$row[unit_measure]',isStockItem='$row[isStockItem]' WHERE partcode ='$row[partcode]'";
	if($db->Execute($sql3)){
            echo "Success - ".$sql3.'<br>';
        }else{
            echo "Failure - ".$sql3.'<br>';
        }
}

