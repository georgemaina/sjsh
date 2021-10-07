<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require('./roots.php');
require('../include/inc_environment_global.php');

      $debug=TRUE;

        $sql="SELECT partcode,unit_price FROM labolatory WHERE unit_price>0";

                $result=$db->Execute($sql);
                if($debug) echo $sql.'<br>';
            while($row=$result->FetchRow()) {
                    $sql="update care_tz_drugsandservices set unit_price='$row[Selling_Price]' where partcode='$row[partcode]'";
                if($debug) echo $sql.'<br>';
                     if($db->Execute($sql)){
                         echo "update corpratePrice for item $row[2] to price $row[1]<br>";
                     }else{
                         echo "unable to update $row[0] to $row[1]";
                     }
            }
        
//    }
?>
