<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require('./roots.php');
require('../include/inc_environment_global.php');

      $debug=TRUE;

        $sql="SELECT `nr`,`item_id`, `group_id`, `name`, `fullDescription`,`id`,`field_type`,`price`,`oldPartCode` FROM `care_tz_laboratory_param`";

                $result=$db->Execute($sql);
                if($debug) echo $sql.'<br>';
            while($row=$result->FetchRow()) {
                    $sql="UPDATE `care_tz_laboratory_resultstypes` SET `item_id`='$row[item_id]' WHERE item_id='$row[oldPartCode]'";
                if($debug) echo $sql.'<br>';
                     if($db->Execute($sql)){
                         echo "update price for item $row[oldPartCode] to price $row[item_id]<br>";
                     }else{
                         echo "unable to update $row[oldPartCode] to $row[item_id] $sql";
                     }
            }
        
//    }
?>
