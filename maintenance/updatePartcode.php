<?php
require('roots.php');
require('../include/inc_environment_global.php');

//global $db;
$debug=false;
($debug) ? $db->debug=true : $db->debug=FALSE;

$sql="SELECT stockid,description, longdescription, units,actualcost, categoryid FROM
  mtkenya.stockmaster WHERE stockid NOT IN (SELECT partcode FROM `care2x`.care_tz_drugsandservices)";
echo $sql;
$result = $db->Execute($sql);

while($row = $result->FetchRow()){

       	$sql3="INSERT INTO `care2x`.`care_tz_drugsandservices` (
  `item_number`,`partcode`,`item_description`,`item_full_description`,`unit_price`,`purchasing_class`,`category`,`units`,`unit_measure`,`salesAreas`) 
 Values('$row[stockid]','$row[stockid]','$row[description]','$row[description]','$row[actualcost]','drug_list'
                ,'$row[categoryid]','$row[units]','$row[units]','DISPENS')";
	    if($db->Execute($sql3)){
            echo "Success - ".$sql3.'<br>';
        }else{
            echo "Failure - ".$sql3.'<br>';
        }
}

