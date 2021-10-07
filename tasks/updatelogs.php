<?php
require('./roots.php');
require('../include/inc_environment_global.php');

 $transactiondate=date('Y-m-d H:i:s');
 
     $sql="INSERT INTO audittrail(`transactiondate`,`userid`,querystring)
        VALUES('$transactiondate','Admin','Test test')";
        if($db->Execute($sql)){
            echo "job Created successfully<br>";  
        }
 
// 0 13 * * * /usr/bin/php /var/www/
?>