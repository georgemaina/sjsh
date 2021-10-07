<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require('./roots.php');
require('../include/inc_environment_global.php');

      $debug=TRUE;

        $sql="select Partcode,item_status,price from laboratory";

                $result=$db->Execute($sql);
                if($debug) echo $sql.'<br>';
            while($row=$result->FetchRow()) {
                    $sql="update labparams set `status`='$row[item_status]',price='$row[item_status]' where item_id='$row[Partcode]'";
                if($debug) echo $sql.'<br>';
                     if($db->Execute($sql)){
                         echo "update Laboratory successfully<br>";
                     }else{
                         echo "unable to update $row[0] to $row[1]";
                     }
            }
        
//    }
?>
