<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require('./roots.php');
require('../include/inc_environment_global.php');

      $debug=TRUE;

        $sql="SELECT newPartCode,PartCode FROM `Lab`";

                $result=$db->Execute($sql);
                if($debug) echo $sql.'<br>';
            while($row=$result->FetchRow()) {
                    $sql="UPDATE `care_tz_laboratory_param` SET `OldPartcode`='$row[PartCode]' WHERE item_id='$row[newPartCode]'";
                if($debug) echo $sql.'<br>';
                     if($db->Execute($sql)){
                         echo "update price for item $sql <br>";
                     }else{
                         echo "unable to update $sql <br>";
                     }
            }
        
//    }
?>
