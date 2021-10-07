<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require('./roots.php');
require('../include/inc_environment_global.php');

      $debug=TRUE;

        $sql="SELECT `CODE`,COMPANY FROM `SERVICES` WHERE COMPANY>0 AND COMPANY IS NOT NULL";

                $result=$db->Execute($sql);
                if($debug) echo $sql.'<br>';
            while($row=$result->FetchRow()) {
                    $sql="INSERT INTO CARE_KE_PRICES(PARTCODE,PRICETYPE,Price) VALUES('$ROW[CODE]','2','$ROW[COMPANY]')";
                if($debug) echo $sql.'<br>';
                     if($db->Execute($sql)){
                         echo "update price for item $row[0] to price $row[1]<br>";
                     }else{
                         echo "unable to update $row[0] to $row[1]";
                     }
            }
        
//    }
?>
