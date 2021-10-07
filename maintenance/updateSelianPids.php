<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require('./roots.php');
require('../include/inc_environment_global.php');

      $debug=TRUE;

        $sql="SELECT pid,selian_pid from 2017Patients";

                $result=$db->Execute($sql);
                if($debug) echo $sql.'<br>';
            while($row=$result->FetchRow()) {
                    $sql="update care_person set selian_pid=$row[1] where pid='$row[pid]'";
                if($debug) echo $sql.'<br>';
                     if($db->Execute($sql)){
                         echo "update Selian pid for pid $row[0] to selian_pid $row[1]<br>";
                     }else{
                         echo "unable to update $row[0] to $row[1]";
                     }
            }
        
//    }
?>
