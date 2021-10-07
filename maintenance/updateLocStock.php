<?php
require('roots.php');
require('../include/inc_environment_global.php');

global $db;
$debug=false;
($debug) ? $db->debug=true : $db->debug=FALSE;

$sql="SELECT item_id,SUM(qty_issued) AS Qty FROM `care_ke_internalserv` WHERE STORE_LOC='IPPH' AND sup_storeid='MAIN'
            GROUP BY item_id";
$result = $db->Execute($sql);
echo $sql;
$count=0;
while($row = $result ->FetchRow()){
       	$sql3="update care_ke_locstock set quantity='$row[Qty]' where stockid='$row[item_id]'";
	    if($db->Execute($sql3)){
            echo "Success - ".$sql3.'<br>';
        }else{
            echo "Failure - ".$sql3.'<br>';
        }
        $count=$count+1;
}

echo "Total Stocks updated are ".$count;
