<?php
require('roots.php');
require('../include/inc_environment_global.php');

//global $db;
$debug=false;
($debug) ? $db->debug=true : $db->debug=FALSE;

$sql="select item_id,name,group_id,price from care_tz_laboratory_param where group_id<>'-1'";
echo $sql;
$result = $db->Execute($sql);

while($row = $result->FetchRow()){

       	$sql3="INSERT INTO care_tz_drugsandservices 
  ( `item_number`,`partcode`,`item_description`,`item_full_description`,`unit_price`,`purchasing_class`,`category`,
  `reorderlevel`,`unit_measure`,`item_status`,`Unit_qty`,`Purchasing_Unit`,`PresLevel`,`PresNHIF`,moreInfo)
  values ('$row[item_id]','$row[item_id]','$row[name]','$row[name]','$row[price]','LABORATORY','10',
  '1','Each','1','1','1','1','1','$row[group_id]') ";
	    if($db->Execute($sql3)){
            echo "Success - ".$sql3.'<br>';
        }else{
            echo "Failure - ".$sql3.'<br>';
        }
}

