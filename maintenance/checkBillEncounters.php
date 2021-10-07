<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');
require_once($root_path . 'include/care_api_classes/class_weberp_c2x.php');
require_once($root_path . 'include/inc_init_xmlrpc.php');

$debug=true;


$sql="SELECT b.`pid`,b.`encounter_nr`,b.`bill_date`,b.`bill_number` FROM care_ke_billing b LEFT JOIN care_encounter e
ON b.`encounter_nr`=e.`encounter_nr`
WHERE e.`is_discharged`=0
GROUP BY b.`encounter_nr`";

$results=$db->Execute($sql);
$counter=0;
while($row=$results->FetchRow()){
    $sql2="SELECT `encounter_nr`,`pid`,`encounter_date`,`encounter_class_nr`,`finalised`,`bill_number`,`is_discharged`
        FROM care_encounter WHERE encounter_class_nr=1 AND is_discharged=0 and encounter_nr='$row[encounter_nr]'";
    //if($debug) echo $sql2;
    $results2=$db->Execute($sql2);
    //if($results->RecordCount()>0){
        $row2=$results2->FetchRow();
        if($row2[bill_number]<>$row[bill_number]){
            $sql3="update care_encounter set bill_number='$row[bill_number]' where encounter_nr=$row[encounter_nr]";
            if($debug) echo $sql3 ."  ";
            $db->Execute($sql3);
            echo "Successfully updated encounterNr $row[encounter_nr] from billNumber $row2[bill_number] to $row[bill_number] $counter<br>";
        }
    //}
    $counter++;
}